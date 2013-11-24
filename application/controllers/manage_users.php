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
		$this->form_validation->set_rules('category', 'Category', 'required|trim');

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
			
			$user=$this->uri->segment(4);
			
			$data['user_data']=$this->users_model->get_user_by_username($user);

			if($data['user_data']['usertype']=='participant'){
				$project_id=$this->users_model->get_project_id($data['user_data']['id']);
				$data['project_data']=$this->users_model->get_project_data($project_id);
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
}