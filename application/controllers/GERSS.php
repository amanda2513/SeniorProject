<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gerss extends CI_Controller {

	public function index()
	{
		$this->home();
	}
//-----------------PAGES-------------------------//
/*
	echo $this->session->userdata('fn');
		echo " ";  |is_unique[users.username]
		echo $this->session->userdata('ln');
		echo " ";
		echo $this->session->userdata('coll');
		//$this->users_model->set_user_session();
*/
//-----------------HOME PAGE---------------------//
	public function home(){
		$data['title']="WSU-GERSS :: Home";
		$this->load->model('general_settings_model');
		$data['logged_in'] = $this->session->userdata('is_logged_in');
		$data['settings']=$this->general_settings_model->get_settings();
		$today = date('Y-m-d');
		$data['in_registration_period'] = date('Y-m-d',strtotime($data['settings']['reg_cutoff_date'])) >= date('Y-m-d',strtotime($today)) && date('Y-m-d',strtotime($data['settings']['reg_start_date'])) <= date('Y-m-d',strtotime($today));
		$data['reg_cutoff']=$data['settings']['reg_cutoff_date'];
		$this->load->view('home_view',$data);
	}
	
	public function login_validation($errorMsg = NULL){
		$this->session->keep_flashdata('tried_to');
		$this->load->library('form_validation');
		$this->load->library('authldap');
		$this->load->model('users_model');
		$this->load->model('general_settings_model');

		$settings=$this->general_settings_model->get_settings();
		$today = date('Y-m-d');

		$rules = $this->form_validation;
		$rules->set_rules('username', '', 'required|trim|xss_clean');
		$rules->set_rules('password','','required|trim');
		
		
		if($rules->run() && $this->authldap->login($rules->set_value('username'), $rules->set_value('password'))){
			if($this->users_model->is_registered()){
				if($this->users_model->can_log_in()){
					$this->session->set_userdata('is_logged_in',TRUE);
					if($this->session->userdata('role')=='admin' || $this->session->userdata('role')=='participant'){
						redirect(base_url().'gerss/projects_participants');
					}
					elseif($this->session->userdata('role')=='judge'){
						redirect(base_url().'gerss/projects_judges');
					}
					else{
						redirect(base_url().'scores/input');
					}
				}
				else{
					$redirect=$this->session->set_flashdata('errors','Login has been disabled by the site admin. Please try again later.');
					redirect(base_url()."gerss/home",$this->input->post('redirect'));
				}
			}
			elseif( date('Y-m-d',strtotime($settings['reg_cutoff_date'])) >= date('Y-m-d',strtotime($today)) && date('Y-m-d',strtotime($settings['reg_start_date'])) <= date('Y-m-d',strtotime($today))){
				redirect(base_url().'gerss/registration?type=0');
			}
			else{
				$redirect=$this->session->set_flashdata('credentials_error','Sorry, registration has ended.');
				redirect(base_url()."gerss/home",$this->input->post('redirect'));
			}
		}
		else {
			$redirect= $this->session->set_flashdata('credentials_error', validation_errors());

			$data['title']="WSU-GERSS :: Home";
			redirect($this->input->post('redirect'));	
		}
	}
	
	//public function validate_credentials() {
		//$this->load->model('users_model');
		//return $this->users_model->can_log_in();
	//}

//---------------REGISTRATION-------------------------------//
	public function registration() {
		$data['title']="WSU-GERSS :: Register";
		$this->load->model('category_settings_model');
		$data['categories']=$this->category_settings_model->get_all('categories');
		$this->load->view('registration_view',$data);
	}

	public function registration_validation(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('type','Type','required|trim');
		$this->form_validation->set_rules('firstname','First Name', 'required|trim');
		$this->form_validation->set_rules('lastname','Last Name', 'required|trim');
		$this->form_validation->set_rules('department','Department','required|trim');
		$this->form_validation->set_rules('userid', 'Username', 'required|trim|xss_clean|is_unique[users.username]');

		if($this->input->post('type')=='participant')
		{
			$this->form_validation->set_rules('category', 'Project Category', 'required|trim');
			$this->form_validation->set_rules('project_title', 'Project Title', 'required|trim');
			$this->form_validation->set_rules('project_desc', 'Project Description', 'required|trim|callback_limit_words');
		}
		
		$this->form_validation->set_message('is_unique', "User ".$this->session->userdata('username')." is already registered.");
		
		if ($this->form_validation->run()){
			if($this->input->post('type')=='participant'){
				$this->register_participant();
			}
			else{
				$this->register_user();
			}
		}
		else{
			$redirect=$this->session->set_flashdata('errors',validation_errors());
			redirect(base_url()."gerss/registration?type=".$this->input->post('type'),$this->input->post('redirect'));
		}
	}
//----------------REGISTER USER----------------------------//	
	public function register_user(){
		$this->load->model('users_model'); 
		
		if($this->users_model->admin_add_user()){
			$this->load->model('users_model');
			$user_type = $this->users_model->get_single_user_type('users',$this->session->userdata('username'));
			$this->session->set_userdata('role',$user_type);

			$redirect=$this->session->set_flashdata('success','You have been registered.');
			redirect($this->input->post('redirect'));
		}
		else{
			$redirect=$this->session->set_flashdata('errors',validation_errors());
			redirect(base_url()."gerss/registration?type=".$this->input->post('type'),$this->input->post('redirect'));
		}
		
	}

	public function register_participant(){
		$this->load->model('users_model'); 
		
		if($this->users_model->add_participant()){
			$this->load->model('users_model');
			$user_type = $this->users_model->get_single_user_type('users',$this->session->userdata('username'));
			$this->session->set_userdata('role',$user_type);

			$redirect=$this->session->set_flashdata('success','You have been registered.');
			redirect($this->input->post('redirect'));
		}
		else{
			$redirect=$this->session->set_flashdata('errors','Database Error. Try Again or Contact the Site Admin');
			redirect(base_url()."gerss/registration?type=".$this->input->post('type'),$this->input->post('redirect'));
		}
	}

//----------------LIMIT DESCRIPTION TO 250 WORDS-------------//	
	public function limit_words($str){
		$this->load->helper('text');
		$string = word_limiter($str, 250);
		if ($string <= $str) return true;
		$this->form_validation->set_message(__FUNCTION__, "Description should not exceed 250 words.");
	  	return false;
	}

//----------------PROJECTS PAGE-----------------------------//
	public function projects_participants(){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('users_model');
			$this->load->model('judge_assignment_model');
			$role = $this->session->userdata('role');
			if($role=='participant'){
				$data['participant']=$this->users_model->get_project_by_username($this->session->userdata('username'));
			}
			elseif($role=='seu'||$role=='admin'){
				$data['participant']=$this->users_model->get_participant_info();
			}
			elseif($role=='judge'){
				$data['participant']=$this->judge_assignment_model->get_logged_in_judge_projects($this->session->userdata('username'));
			}
			else{
				$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
				redirect(base_url()."gerss/home",$this->input->post('redirect'));	
			}
			$data['judge_assignment']=$this->judge_assignment_model->get_all_from('assigned_judges');
			$data['title']="WSU-GERSS :: Projects";
			$this->load->view('projects_participants_view',$data);
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));	
		}
	}

	public function projects_judges(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')!='participant'){
			$this->load->model('users_model');
			$this->load->model('judge_assignment_model');
			if($this->session->userdata('role')=='judge'){
				$data['judge'] = $this->users_model->get_logged_in_judge();
			}
			else{
				$data['judge']=$this->users_model->get_judge_info();
			}
			foreach($data['judge'] as $judge){
				$judge->assignment_count=$this->judge_assignment_model->count_assigned_projects($judge->id);
				$judge->assignments=$this->judge_assignment_model->get_assigned_projects($judge->id);
			}
			$data['projects']=$this->judge_assignment_model->get_judge_projects($judge->id);
			$data['title']="WSU-GERSS :: Projects";
			$this->load->view('projects_judges_view',$data);
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}
	
//---------------------------SEARCH PROJECTS---------------------------//	
	public function search($id){
		$form = $id;
		
		switch($form) {
		   case '1': 
		   		 $search = $this->input->post('search');
		   		 $this->load->model('users_model');
		   		 $this->load->model('judge_assignment_model');
				 $data['participant']=$this->users_model->filter_participant_info($search);
				 $data['judge_assignment']=$this->judge_assignment_model->get_all_from('assigned_judges');
				 $data['title']="WSU-GERSS :: Projects";
				 $this->load->view('projects_participants_view',$data);
				 break;
		   case '2': 
				 $search = $this->input->post('search');
		   		 $this->load->model('users_model');
		   		 $this->load->model('judge_assignment_model');
				 $data['judge']=$this->users_model->filter_judge_info($search);
				 $data['judge_assignment']=$this->judge_assignment_model->get_all_from('assigned_judges');
				 $data['title']="WSU-GERSS :: Projects";
				 $this->load->view('projects_judges_view',$data);
				 break;
		};
	}	

//---------------Multiple Pages----------------------------//
	public function logout(){
		$this->session->sess_destroy();
		redirect('gerss/home');
	}
}
