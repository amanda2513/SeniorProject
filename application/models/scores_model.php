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

	public function get_judge_scores($project_id, $judge_id){
		$data = array(
			'project_id' => $project_id,
			'judge_id' => $judge_id
		);

		$this->db->select('*');
		$this->db->where($data);
		$this->db->from('criteria_scores');
		$sql = $this->db->get();
		return $sql->result();
	}

	public function score_exists($data){
		$data = array(
			'project_id' => $data['project_id'],
			'judge_id' => $data['judge_id'],
			'criteria_id' => $data['criteria_id']
		);

		$this->db->where($data);
		$rows = $this->db->count_all_results('criteria_scores');

		if($rows>0){
			return true;
		}
		return false;
	}

	public function add_score($data){
		$data = array(
			'project_id' => $data['project_id'],
			'judge_id' => $data['judge_id'],
			'criteria_id' => $data['criteria_id'],
			'criteria_score' => $data['criteria_score']
		);

		$this->db->insert('criteria_scores', $data);
	}

	public function update_score($data){
		
		$score = array(
			'criteria_score' => $data['criteria_score']
		);

		$data = array(
			'project_id' => $data['project_id'],
			'judge_id' => $data['judge_id'],
			'criteria_id' => $data['criteria_id']
		);

		$this->db->where($data);
		$this->db->update('criteria_scores', $score);
	}

	public function get_project_scores(){
		$this->db->select('*');
		$this->db->from('criteria_scores');
		$this->db->join('assigned_judges', 'assigned_judges.judge_id = criteria_scores.judge_id and assigned_judges.project_id = criteria_scores.project_id', 'right');
		$this->db->join('projects', 'projects.project_id = assigned_judges.project_id');
		$this->db->join('participants','projects.project_id = participants.project_id');
		$this->db->join('users', 'participants.participant_id = users.id');
		$this->db->join('subcat_criteria','subcat_criteria.criteria_id = criteria_scores.criteria_id','left outer');
		$sql = $this->db->get();

		return $sql->result();
	}

	public function get_judge_entry_count($project_id){
		$this->db->select('DISTINCT(judge_id)');
		$this->db->where('project_id',$project_id);
		$this->db->from('criteria_scores');
		$sql = $this->db->get();

		return $sql->num_rows();
	}

	public function get_distinct_criteria_ids($project_id){
		$this->db->select('DISTINCT(criteria_id)');
		$this->db->where('project_id',$project_id);
		$this->db->from('criteria_scores');
		$sql = $this->db->get();

		return $sql->result();
	}

	public function average_criterion_score($criteria_id){
		$this->db->select_avg('criteria_score','average_score');
		$this->db->where('criteria_id',$criteria_id);
		$sql = $this->db->get('criteria_scores');

		return $sql -> result_array();
	}

}