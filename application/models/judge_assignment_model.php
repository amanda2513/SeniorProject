<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Judge_assignment_model extends CI_Model {

	public function get_assignment_settings(){
		$sql = $this->db->get('system_settings');
		$data['judges_per_project']=$sql->row('judges_per_project');

		$sql = $this->db->get('system_settings');
		$data['projects_per_judge']=$sql->row('projects_per_judge');

		return $data;
	}

	public function get_all_from($table_name){
		$sql=$this->db->get($table_name);
		return $sql->result();
	}

	public function get_participant_department($project_id){
		$this->db->where('project_id',$project_id);
		$sql=$this->db->get('participants');
		$participant_id = $sql->row('participant_id');

		$this->db->where('id',$participant_id);
		$sql = $this->db->get('users');
		return $sql->row('department');
	}

	public function get_judges(){
		$sql = $this->db->get_where('users',array('usertype'=>'judge'));
		return $sql -> result();
	}

	public function count_assigned_judges($project_id){
		$this->db->where('project_id',$project_id);
		$this->db->from('assigned_judges');
		return $this->db->count_all_results();
	}

	public function count_assigned_projects($judge_id){
		$this->db->where('judge_id',$judge_id);
		$this->db->from('assigned_judges');
		return $this->db->count_all_results();
	}

	public function assign_judge($project_id, $judge_id){
		$data = array(
			'project_id' => $project_id,
			'judge_id' => $judge_id
			);

		$this->db->insert('assigned_judges',$data);

	}

	public function remove_assignment($project_id,$judge_id){
		$data = array(
			'project_id' => $project_id,
			'judge_id' => $judge_id
			);

		$this->db->where($data);
		$this->db->delete('assigned_judges');
	}

	public function get_judge_projects($judge_id){
		$this->db->select('*');
		$this->db->from('assigned_judges');
		$this->db->join('projects','assigned_judges.project_id = projects.project_id');

		$sql = $this->db->get();

		return $sql->result();
	}

	public function get_assigned_projects($judge_id){
		$this->db->where('judge_id',$judge_id);
		$this->db->from('assigned_judges');
		$this->db->join('projects','assigned_judges.project_id = projects.project_id');
		$this->db->join('participants','projects.project_id=participants.project_id');
		$this->db->join('users','participants.participant_id=users.id');

		$sql = $this->db->get();

		return $sql->result();
	}

	public function get_logged_in_judge_projects($username){
		$this->db->where('username',$username);
		$this->db->from('users');
		$sql = $this->db->get();
		$judge_record = $sql->row();

		$this->db->where('judge_id',$judge_record->id);
		$this->db->from('assigned_judges');
		$this->db->join('projects','assigned_judges.project_id = projects.project_id');
		$this->db->join('participants','projects.project_id=participants.project_id');
		$this->db->join('users','participants.participant_id=users.id');

		$sql = $this->db->get();

		return $sql->result();
	}

	public function truncate_table($table_name){
		$this->db->query('SET FOREIGN_KEY_CHECKS = 0');

		$this->db->truncate($table_name);

		$this->db->query('SET FOREIGN_KEY_CHECKS = 1');

	}

	public function get_assigned_judges($project_id){
		$sql= $this->db->get_where('assigned_judges',array('project_id'=>$project_id));
		return $sql->result();
	}

	public function get_project_judges($project_id){
		$this->db->where('project_id',$project_id);
		$this->db->from('assigned_judges');
		$this->db->join('users','assigned_judges.judge_id=users.id');

		$sql = $this->db->get();

		return $sql->result();
	}

}