<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_users extends CI_Controller {

	public function participant(){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('users_model');		
			$data['participant']=$this->users_model->get_all_user_type("users","participant");
			$data['title']="WSU-GERSS :: Manage Users";
			$this->load->view('users_participant_view',$data);
		}
		else{
			redirect('gerss/home');
		}
	}

	public function judge(){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('users_model');		
			$data['judge']=$this->users_model->get_all_user_type("users","judge");
			$data['title']="WSU-GERSS :: Manage Users";
			$this->load->view('users_judge_view',$data);
		}
		else{
			redirect('gerss/home');
		}
	}

	public function seu(){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('users_model');		
			$data['score_entry_user']=$this->users_model->get_all_user_type("users","seu");
			$data['title']="WSU-GERSS :: Manage Users";
			$this->load->view('users_seu_view',$data);
		}
		else{
			redirect('gerss/home');
		}
	}

	public function admin(){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('users_model');		
			$data['admin']=$this->users_model->get_all_user_type("users","admin");
			$data['title']="WSU-GERSS :: Manage Users";
			$this->load->view('users_admin_view',$data);
		}
		else{
			redirect('gerss/home');
		}
	}

	
//---------------------------SEARCH MANAGE USERS---------------------------//	
	public function search_users_participant_view($id){
		$form = $id;
		
		switch($form) {
		   case '1': 
		   		 $search = $this->input->post('search_participant');
		   		 $this->load->model('users_model');
				 $data['participant']=$this->users_model->find_user("users", "participant", $search);
				 $data['title']="WSU-GERSS :: Projects";
				 $this->load->view('users_participant_view',$data);
				 break;
		   case '2': 
				 $search = $this->input->post('search_judge');
				 $this->load->model('users_model');
        		 $data['judge']=$this->users_model->find_user("users", "judge", $search);
				 $data['title']="WSU-GERSS :: Projects";
			     $this->load->view('users_judge_view',$data);
         		 break;
		  case '3': 
				 $search = $this->input->post('search_seu');
				 $this->load->model('users_model');
        		 $data['score_entry_user']=$this->users_model->find_user("users", "seu", $search);
				 $data['title']="WSU-GERSS :: Projects";
			     $this->load->view('users_seu_view',$data);
         		 break;
		  case '4': 
				 $search = $this->input->post('search_admin');
				 $this->load->model('users_model');
        		 $data['admin']=$this->users_model->find_user("users", "admin", $search);
				 $data['title']="WSU-GERSS :: Projects";
			     $this->load->view('users_admin_view',$data);
         		 break;
		};
	}

	public function delete(){
		$this->load->model('users_model');
		$user_type=$this->uri->segment(3);
		$user_id = $this->uri->segment(4);
		if($user_type == 'participant'){
			$user_was_deleted = $this->users_model->admin_del_participant($user_id);
		}
		else{
			$user_was_deleted = $this->users_model->admin_del_user($user_id);
		}

		if($user_was_deleted){
			$redirect=$this->session->set_flashdata('success','User Has Been Deleted');
			redirect(base_url()."manage_users/".$this->uri->segment(3),$this->input->post('redirect'));
		}
		else{
			$redirect=$this->session->set_flashdata('error','Error Deleting User');
			redirect(base_url()."manage_users/".$this->uri->segment(3),$this->input->post('redirect'));
		}
	}
	
	public function add(){
		if ($this->session->userdata('is_logged_in')){
			$data['title']="WSU-GERSS :: Add User";
			$this->load->model('category_settings_model');
			$data['categories']=$this->category_settings_model->get_all('categories');
			$this->load->view('add_user_view',$data);
		}
		else{
			redirect('gerss/home');
		}
	}

	public function add_user_validation(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('type','Type','required|trim');
		$this->form_validation->set_rules('firstname','First Name', 'required|trim');
		$this->form_validation->set_rules('lastname','Last Name', 'required|trim');
		$this->form_validation->set_rules('department','Department','required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]|callback_valid_domain');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]');
		if($this->input->post('type')=='participant'){
			$this->form_validation->set_rules('category', 'Category', 'required|trim');
		}

		$this->form_validation->set_message('is_unique', "That email address already exists.");
		
		if ($this->form_validation->run()){

			$this->load->model('users_model');
			$email = $this->input->post('email');

			if($this->input->post('type')=='participant'){
				$this->users_model->add_participant();
				$redirect=$this->session->set_flashdata('success','User Added');
				redirect(base_url()."manage_users/add?type=".$this->input->post('type'),$this->input->post('redirect'));
			}
			else{
				$this->users_model->admin_add_user();
				$redirect=$this->session->set_flashdata('success','User Added');
				redirect(base_url()."manage_users/add?type=".$this->input->post('type'),$this->input->post('redirect'));
			}

		}
		else{
			
			$redirect=$this->session->set_flashdata('errors',validation_errors());
			redirect(base_url()."manage_users/add?type=".$this->input->post('type'),$this->input->post('redirect'));
		}
	}

	public function edit(){
		if ($this->session->userdata('is_logged_in')){
			$data['title']="WSU-GERSS :: Edit User";

			$this->load->model("users_model");
			$this->load->model("judge_assignment_model");
			$this->load->model("general_settings_model");
			
			$user=$this->uri->segment(4);

			$data['logged_in_as']=$this->session->userdata('role');
			
			$data['user_data']=$this->users_model->get_user_by_username($user);


			$settings=$this->general_settings_model->get_settings();
			$data['judges_per_project']=$settings['judges_per_project'];
			$data['projects_per_judge']=$settings['projects_per_judge'];

			//for populating participant project info for editing
			if($data['user_data']['usertype']=='participant'){
				$project_id=$this->users_model->get_project_id($data['user_data']['id']);
				$data['project_data']=$this->users_model->get_project_data($project_id);
			

				//for manual judge assignment on edit participant
				if($data['logged_in_as']=='admin'){
					$data['assigned_judges']=$this->judge_assignment_model->get_assigned_judges($project_id);
					$data['all_judges']=$this->judge_assignment_model->get_judges();

					foreach($data['all_judges'] as $key=>$judge){
						$data['all_judges'][$key]->assignment_count=$this->judge_assignment_model->count_assigned_projects($judge->id);
					}
				}
			}

			//for manual judge assignment on edit judge
			if($data['user_data']['usertype']=='judge' && $data['logged_in_as']=='admin'){
				$data['all_participants']=$this->users_model->get_participant_info();
				$data['judge_assignment']=$this->judge_assignment_model->get_all_from('assigned_judges');
			}

			$this->load->model("category_settings_model");
			$data['categories']=$this->category_settings_model->get_all('categories');

			$this->load->view('edit_user_view',$data);
			
		}

		else{
			redirect('gerss/home');
		}
	}

	public function edit_user_validation(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('type','Type','required|trim');
		$this->form_validation->set_rules('firstname','First Name', 'required|trim');
		$this->form_validation->set_rules('lastname','Last Name', 'required|trim');
		$this->form_validation->set_rules('department','Department','required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_valid_domain');
				
		
		
		if ($this->form_validation->run()){
			
			$id=$this->uri->segment(5);

			$this->load->model('users_model');
			$email = $this->input->post('email');

			if ($this->input->post('type')=='participant'){
				$this->users_model->update_participant($id);
				$redirect=$this->session->set_flashdata('success','User Info Has Been Updated');
				redirect(base_url()."manage_users/edit/".$this->input->post('type').'/'.$this->uri->segment(4),$this->input->post('redirect'));
			}
			else{
				$this->users_model->update_user($id);
				$redirect=$this->session->set_flashdata('success','User Info Has Been Updated');
				redirect(base_url()."manage_users/edit/".$this->input->post('type').'/'.$this->uri->segment(4),$this->input->post('redirect'));
			}
		}
		else{
			
			$redirect=$this->session->set_flashdata('errors',validation_errors());
			redirect(base_url()."manage_users/edit/".$this->uri->segment(3).'/'.$this->uri->segment(4),$this->input->post('redirect'));
		}
	}

	public function manual_assignment_participant_validation(){
		if ($this->session->userdata('is_logged_in') && $this->session->userdata('role')=='admin'){
			$this->load->model('judge_assignment_model');
			$project_id = $_POST['project_id'];
			$prev_judges = $this->judge_assignment_model->get_assigned_judges($_POST['project_id']);
			$prev_assigned=array();

			foreach($prev_judges as $key=>$previous){
				$prev_assigned[$key]=$previous->judge_id;
			}
			
			if(isset($_POST['assign_judge'])){
				$posted_assignments = $_POST['assign_judge'];
				$additional_judges = array_diff($posted_assignments,$prev_assigned);
				$removed_judges = array_diff($prev_assigned,$posted_assignments);

				foreach($additional_judges as $new_judge_id){
					$this->judge_assignment_model->assign_judge($project_id,$new_judge_id);
				}

				foreach($removed_judges as $old_judge_id){
					$this->judge_assignment_model->remove_assignment($project_id,$old_judge_id);
				}
			}
			else{//all assignments were removed
				foreach($prev_assigned as $judge_id){
					$this->judge_assignment_model->remove_assignment($project_id,$judge_id);
				}
			}

			$redirect=$this->session->set_flashdata('success','Judge Assignments Have Been Updated');
			redirect(base_url()."manage_users/edit/".$this->uri->segment(3).'/'.$this->uri->segment(4),$this->input->post('redirect'));
		}
		else{
			redirect('gerss/home');
		}
	}

	public function manual_assignment_judge_validation(){
		if ($this->session->userdata('is_logged_in') && $this->session->userdata('role')=='admin'){
			$this->load->model('judge_assignment_model');
			$judge_id = $_POST['judge_id'];
			$prev_assigned = $this->judge_assignment_model->get_assigned_projects($judge_id);
			$prev_projects=array();

			foreach($prev_assigned as $key=>$previous){
				$prev_projects[$key]=$previous->project_id;
			}
			
			if(isset($_POST['assign_project'])){
				$posted_assignments = $_POST['assign_project'];
				$additional_projects = array_diff($posted_assignments,$prev_projects);
				$removed_projects = array_diff($prev_projects,$posted_assignments);

							
				foreach($additional_projects as $project_id){
					$this->judge_assignment_model->assign_judge($project_id,$judge_id);
				}

				foreach($removed_projects as $project_id){
					$this->judge_assignment_model->remove_assignment($project_id,$judge_id);
				}
			}
			else{//all assignments were removed
				foreach($prev_projects as $project_id){
					$this->judge_assignment_model->remove_assignment($project_id,$judge_id);
				}
			}

			$redirect=$this->session->set_flashdata('success','Judge Assignments Have Been Updated');
			redirect(base_url()."manage_users/edit/".$this->uri->segment(3).'/'.$this->uri->segment(4),$this->input->post('redirect'));
		}
		else{
			redirect('gerss/home');
		}
	}

	public function change_user_status(){
		if ($this->session->userdata('is_logged_in') && $this->session->userdata('role')=='admin'){
			$user_type=$this->uri->segment(3);
			$new_status=$this->uri->segment(4);
			$user_id=$this->uri->segment(5);

			$this->load->model('users_model');
			$this->users_model->change_user_status($user_id,$new_status);

			redirect(base_url()."manage_users/".$user_type);

		}
		else{
			redirect('gerss/home');
		}
	}
}