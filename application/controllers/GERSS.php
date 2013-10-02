<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GERSS extends CI_Controller {

	public function index()
	{
		$this->projects_participants();
	}

	public function home(){
		$data['title']="WSU-GERSS :: Home";
		$this->load->view('home_view',$data);
	}

	public function projects_participants(){
		$data['title']="WSU-GERSS :: Projects";
		$this->load->view('projects_participants_view',$data);
	}

	public function projects_judges(){
		$data['title']="WSU-GERSS :: Projects";
		$this->load->view('projects_judges_view',$data);
	}

}
