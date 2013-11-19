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
		$this->load->model('general_settings_model');
		$data['settings']=$this->general_settings_model->get_settings();
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
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]|callback_valid_domain');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|callback_password_check');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]');

		if($this->input->post('type')=='participant')
		{
			$this->form_validation->set_rules('category', 'Project Category', 'required|trim');
			$this->form_validation->set_rules('project_title', 'Project Title', 'required|trim');
			$this->form_validation->set_rules('project_desc', 'Project Description', 'required|trim|callback_limit_words');
		}

		
		$this->form_validation->set_message('is_unique', "That email address already exists.");
		
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
			$redirect=$this->session->set_flashdata('success','You have been registered. You can now login with your WSU credentials.');
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
			$redirect=$this->session->set_flashdata('success','You have been registered. You can now login with your WSU credentials.');
			redirect($this->input->post('redirect'));
		}
		else{
			$redirect=$this->session->set_flashdata('errors','Database Error. Try Again or Contact the Site Admin');
			redirect(base_url()."gerss/registration?type=".$this->input->post('type'),$this->input->post('redirect'));
		}
	}

//----------------CHECK FOR VALID WSU EMAIL ADDRESS-------------//	
	public function valid_domain($str){
		if (stristr($str,'@wayne.edu') !== false) return true;
			$this->form_validation->set_message(__FUNCTION__, "$str is not a valid WSU email address.");
	  	return FALSE;
	}

//----------------LIMIT DESCRIPTION TO 250 WORDS-------------//	
	public function limit_words($str){
		$this->load->helper('text');
		$string = word_limiter($str, 250);
		if ($string <= $str) return true;
		$this->form_validation->set_message(__FUNCTION__, "Description should not exceed 250 words.");
	  	return false;
	}

//----------------PASSWORD CHECK-------------//	
public function password_check($str)
{
   if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) return TRUE;
   $this->form_validation->set_message(__FUNCTION__, "Your password should contain a number and a letter.");
   return FALSE;
}
/* Require number,letter,and special characters
if (!preg_match ("/[&@<>%\*\,\^!#$%().]/i", $str))

    {
        $this->form_validation->set_message('password_check', 'Your password should contain a number,letter,and special characters"');
        return FALSE;
    }
    else
    {
        return TRUE;
    }}
*/

//----------------PROJECTS PAGE-----------------------------//
	public function projects_participants(){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('users_model');
			$this->load->model('judge_assignment_model');		
			$data['participant']=$this->users_model->get_participant_info();
			$data['judge_assignment']=$this->judge_assignment_model->get_all_from('assigned_judges');
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
			$this->load->model('judge_assignment_model');		
			$data['judge']=$this->users_model->get_judge_info();
			foreach($data['judge'] as $judge){
				$judge->assignment_count=$this->judge_assignment_model->count_assigned_projects($judge->id);
			}
			$data['projects']=$this->judge_assignment_model->get_judge_projects($judge->id);
			$data['title']="WSU-GERSS :: Projects";
			$this->load->view('projects_judges_view',$data);
		}
		else{
			redirect('gerss/home');
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
