<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_users extends CI_Controller {

	public function participants(){
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

	public function judges(){
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


	public function add(){
		$data['title']="WSU-GERSS :: Add User";
		$this->load->view('add_user_view',$data);
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
		
		$this->form_validation->set_message('is_unique', "That email address already exists.");
		
		if ($this->form_validation->run()){
			
			//generate a random key
			$key = md5(uniqid());

			$this->load->model('users_model');
			$email = $this->input->post('email');

			if ($this->users_model->admin_add_user()){
				//if ($this->email->send()){
					//echo "The email has been sent!";
				//$redirect=$this->session->set_flashdata('success','Your account has been created. Thank you.');
				$redirect=$this->session->set_flashdata('success','User Added');
				redirect(base_url()."manage_users/add?type=".$this->input->post('type'),$this->input->post('redirect'));

				//}	else echo "could not send the email.";
			}	else echo "problem adding to database.";

		}
		else{
			
			$redirect=$this->session->set_flashdata('errors',validation_errors());
			redirect(base_url()."manage_users/add?type=".$this->input->post('type'),$this->input->post('redirect'));
		}
	}

}