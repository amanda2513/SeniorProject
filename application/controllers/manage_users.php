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
			$data['score_entry_user']=$this->users_model->get_all_user_type("users","score_entry");
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

}