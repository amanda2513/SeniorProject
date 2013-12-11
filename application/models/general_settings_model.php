<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_settings_model extends CI_Model {
	
	public function update_settings(){

		$settings=$this->get_settings();

		if($settings['restrict_access']!=$this->input->post('restrict_access')){
			$this->restrict_access($this->input->post('restrict_access'));
		}

		$data = array(
				'homepage_message'=>$this->input->post('home_msg'),
				'exhib_date'=>date('Y-m-d',strtotime($this->input->post('exhib_date'))),
				'exhib_location'=>$this->input->post('exhib_location'),
				'exhib_start' =>$this->input->post('exhib_start'),
				'exhib_end'=>$this->input->post('exhib_end'),
				'reg_start_date'=>date('Y-m-d',strtotime($this->input->post('reg_start_date'))),
				'reg_cutoff_date' =>date('Y-m-d',strtotime($this->input->post('reg_cutoff_date'))),
				'restrict_access'=>$this->input->post('restrict_access'),
				'judges_per_project'=>$this->input->post('judges_per_project'),
				'projects_per_judge'=>$this->input->post('projects_per_judge')
				);
		
		//$this->db->where('year',$current_year)
		$did_update_settings = $this->db->update('system_settings', $data);

		if($did_update_settings){
			return true;
		}
		return false;
	}

	public function initialize_settings(){

		//insert default data into settings db
		$data = array(
				'homepage_message'=>"Welcome! We're very excited about the next Graduate Exhibition. <br> Pardon us while we set up the system. Please visit us again soon!",
				'exhib_date'=>'0000-00-00',
				'exhib_location'=>NULL,
				'exhib_start' =>NULL,
				'exhib_end'=>NULL,
				'reg_start_date' =>'0000-00-00',
				'reg_cutoff_date' =>'0000-00-00',
				'restrict_access'=>'Off',
				'judges_per_project'=>NULL,
				'projects_per_judge'=>NULL
				);

		$this->db->insert('system_settings', $data);
		
		//return to get_settings with a call to itself
		return $this->get_settings();
	}

	public function get_settings(){

		$sql = $this->db->query('SELECT * FROM system_settings');

		if ($sql->num_rows() > 0){
			//return settings
			return $sql -> row_array();
		}
		else{
			//if there are no settings, call initialize settings and return it's result
			return $this->initialize_settings();
		}
	}

	public function format_date($field){
		$this->load->helper('date');
		
		//There is no easy way to set the input field to blank of bootstrap datepicker plugin
		//Except to only set it if there is something to set
		if($field != '0000-00-00'){
			$data['input']=date('m/d/y',strtotime($field));
			$data['cal_selected']=date('m/d/y',strtotime($field));
		
		return $data;
		}
	}

	public function scores_are_entered(){
		$rows=$this->db->count_all_results('criteria_scores');

		if($rows>=1){
			return true;
		}
		return false;
	}

	public function restrict_access($status){
		$this->load->model('users_model');
		$users=$this->users_model->get_all_users('users');
		if($status==0){
			$status='Enabled';
		}
		else{
			$status='Disabled';
		}

		foreach($users as $user){
			if($user->usertype == 'participant' || $user->usertype == 'judge'){
				if($user->status != $status){
					$this->users_model->change_user_status($user->id,$status);
				}
			}
		}
	}

	public function reset_system(){
		//delete all users that aren't admins
		$this->db->where('usertype !=','admin');
		$this->db->delete('users');

		$files = glob('abstract_uploads/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
		    	unlink($file); // delete file
		}

		//truncate all tables except categories, subcategories, and subcat_criteria
		$this->db->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->db->truncate('projects');
		$this->db->truncate('assigned_judges');
		$this->db->truncate('participants');
		$this->db->truncate('criteria_scores');
		$this->db->truncate('system_settings');

		$this->db->query('SET FOREIGN_KEY_CHECKS = 1');
	}
}