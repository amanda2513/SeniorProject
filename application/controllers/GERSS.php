<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gerss extends CI_Controller {

	public function index()
	{
		$this->home();
	}
//-----------------PAGES-------------------------//

//-----------------HOME PAGE---------------------//
	public function home(){
		$data['title']="WSU-GERSS :: Home";
		$this->load->view('home_view',$data);
	}

	public function login_validation(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', '', 'required|trim|xss_clean|callback_validate_credentials');
		$this->form_validation->set_rules('password','','required|md5|trim');

		if ($this->form_validation->run()){
			
			$this->load->model('users_model');
			$this->users_model->set_user_session();

			redirect('gerss/projects_participants');
		}
		else {
			$redirect= $this->session->set_flashdata('errors', validation_errors());

			$data['title']="WSU-GERSS :: Home";
			redirect($this->input->post('redirect'));
		}
	}

	public function validate_credentials() {
		$this->load->model('users_model');
		return $this->users_model->can_log_in();
	}

//---------------REGISTRATION-------------------------------//
	public function registration() {
		$data['title']="WSU-GERSS :: Register";
		$this->load->view('registration_view',$data);
	}

	public function registration_validation(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('type','Type','required|trim');
		$this->form_validation->set_rules('firstname','First Name', 'required|trim');
		$this->form_validation->set_rules('lastname','Last Name', 'required|trim');
		$this->form_validation->set_rules('department','Department','required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]');

		if($this->input->post('type')=='participant')
		{
			$this->form_validation->set_rules('category', 'Project Category', 'required|trim');
			$this->form_validation->set_rules('project_title', 'Project Title', 'required|trim');
			$this->form_validation->set_rules('project_desc', 'Project Description', 'required|trim');
		}

		
		$this->form_validation->set_message('is_unique', "That email address already exists.");
		
		if ($this->form_validation->run()){
			
			//generate a random key
			$key = md5(uniqid());

		//	$this->load->library('email', array('mailtype'=>'html'));
			$this->load->model('users_model');
			
		//	$this->email->from('info@gradproject.dynu.com', "Plamen");
		//	$this->email->to($this->input->post('email'));
		//	$this->email->subject("Confirm your account.");
			
		//	$message = "<p>Thank you for signing up!</p>";
		//	$message .= "<p><a href='GERSS/register_user/$key' >Click here</a> to confirm your account</p>";
			
		//	$this->email->message($message);
			
			if ($this->users_model->add_temp_user($key)){
				//if ($this->email->send()){
					//echo "The email has been sent!";
				$redirect=$this->session->set_flashdata('success','Your account has been created. Thank you.');
				redirect($this->input->post('redirect'));

				//}	else echo "could not send the email.";
			}	else echo "problem adding to database.";

		}
		else{
			
			$redirect=$this->session->set_flashdata('errors',validation_errors());
			redirect(base_url()."gerss/registration?type=".$this->input->post('type'),$this->input->post('redirect'));
		}
	}

//----------------PROJECTS PAGE-----------------------------//
	public function projects_participants(){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('users_model');		
			$data['participant']=$this->users_model->get_all_user_type("users","participant");
			$data['title']="WSU-GERSS :: Projects";
			$this->load->view('projects_participants_view',$data);
		}
		else{
			redirect('gerss/home');
		}
	}

	public function projects_judges(){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('users_model');		
			$data['judge']=$this->users_model->get_all_user_type("users","judge");
			$data['title']="WSU-GERSS :: Projects";
			$this->load->view('projects_judges_view',$data);
		}
		else{
			redirect('gerss/home');
		}
	}

//---------------Multiple Pages----------------------------//
	public function logout(){
		$this->session->sess_destroy();
		redirect('gerss/home');
	}
}
