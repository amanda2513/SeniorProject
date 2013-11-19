<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scores extends CI_Controller {

	public function input(){
		if ($this->session->userdata('is_logged_in')){
			$data['title']="WSU-GERSS :: Scores";
			$this->load->model('users_model');
			$this->load->model('judge_assignment_model');
			$this->load->model('scores_model');
			$this->load->model('category_settings_model');
			$data['judges']=$this->users_model->get_all_user_type('users','judge');
			$data['assignments']=$this->scores_model->get_assigned_projects();

			if($this->uri->segment(3)){
				$judge_id = $this->uri->segment(3);
				$participant_ln = $this->uri->segment(4);
				$participant_fn = $this->uri->segment(5);

				$data['project']=$this->scores_model->get_selected_project($judge_id, $participant_ln, $participant_fn);

				$data['category']=$this->category_settings_model->get_category($data['project']->category);
				$data['subcategory']=$this->category_settings_model->get_all("subcategories");
				$data['subcat_criteria']=$this->category_settings_model->get_all("subcat_criteria");
			}

			$this->load->view('input_scores_view',$data);
		}
		else{
			redirect('gerss/home');
		}
	}

	public function view(){
		if ($this->session->userdata('is_logged_in')){
			$data['title']="WSU-GERSS :: Scores";
			$this->load->view('view_scores_view',$data);
		}
		else{
			redirect('gerss/home');
		}
	}

	
}