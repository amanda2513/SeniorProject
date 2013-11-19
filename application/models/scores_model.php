<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scores_model extends CI_Model {

	public function get_assigned_projects(){
		$this->db->select('*');
		$this->db->from('assigned_judges');
		$this->db->join('projects','projects.project_id = assigned_judges.project_id');
		$this->db->join('participants','participants.project_id = assigned_judges.project_id');
		$this->db->join('users','participants.participant_id = users.id');
		$this->db->order_by('judge_id','asc');

		$sql = $this->db->get();

		return $sql->result();
	}

	public function get_selected_project($judge_id,$participant_ln,$participant_fn){
		$array = array('judge_id'=>$judge_id, 'firstname'=>$participant_fn, 'lastname'=>$participant_ln);
		$this->db->where($array);
		$this->db->from('participants');
		$this->db->join('projects', 'participants.project_id = projects.project_id');
		$this->db->join('assigned_judges','assigned_judges.project_id = projects.project_id');
		$this->db->join('users', 'participants.participant_id = users.id');
		$sql = $this->db->get();
		return $sql->row();
	}

}