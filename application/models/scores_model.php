<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scores_model extends CI_Model {

	public function is_scoring_open(){
		//the exhibition has to start to open scoring (date & start time from settings)
		$sql = $this->db->get('system_settings');
		$exhib_info = $sql->result_array();

		date_default_timezone_set('EST');
		$today = date('Y-m-d');

		foreach($exhib_info as $info){
			if( date('Y-m-d',strtotime($info['exhib_date'])) <= date('Y-m-d',strtotime($today))){
				$exhibition_started = true;
			}
			else{
				$exhibition_started = false;
			}
		}
		//all projects must have at least one judge assigned to it
		$sql = $this->db->get('projects');
		$projects = $sql -> result_array();

		$sql = $this->db->get('assigned_judges');
		$assigned_projects = $sql -> result_array();

		$all_projects_assigned = true;

		foreach($projects as $project){
			$judge_count = 0;
			foreach($assigned_projects as $assigned){
				if($project['project_id']==$assigned['project_id']){
					$judge_count+=1;
					break;
				}
			}
			if($judge_count == 0){
				$all_projects_assigned = false;
				break;
			}
		}

		$requirements['all_projects_assigned'] = $all_projects_assigned;
		$requirements['exhibition_started'] = $exhibition_started;

		return $requirements;
	}

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

	public function get_score_entry_count($judge_id){
		$this->db->select('DISTINCT(project_id)');
		$this->db->where('judge_id',$judge_id);
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

	public function average_criterion_score($criteria_id,$project_id){
		$this->db->select_avg('criteria_score','avg_score');
		$this->db->where(array('criteria_id'=>$criteria_id,'project_id'=>$project_id));
		$sql = $this->db->get('criteria_scores');

		return $sql->row('avg_score');
	}

	public function get_final_scores(){
		$this->load->model('users_model');
		$this->load->model('judge_assignment_model');
		$this->load->model('category_settings_model');
		
		$data['projects']=$this->users_model->get_some_participant_info();

		foreach($data['projects'] as $project){
			$category = $this->category_settings_model->get_category($project->category);
			$project->TotalScore = 0;
			$subcategories=$this->category_settings_model->get_subcategories($category->cat_id);
			foreach($subcategories as $scat_key=>$subcat){
				$criteria_count = 0;
				$subcat_score=0;
				$subcat->criteria = $this->category_settings_model->get_criteria($subcat->subcat_id);
				foreach($subcat->criteria as $crit_key=>$criterion){
					$criterion->avg_points = $this->scores_model->average_criterion_score($criterion->criteria_id,$project->project_id);
					$project->TotalScore+=$criterion->avg_points;
				}
			}
			$project->PointsPossible = $this->category_settings_model->get_category_pts_possible($category->cat_id);
			$project->ScorePercent = round($project->TotalScore / $project->PointsPossible * 100,2);

		}
		
		$scores = json_decode(json_encode($data['projects']), true);
		
		return $scores;

		// echo '<pre>';
		// print_r($scores);
		// echo '</pre>';

	}

	public function export_scores($data){

		$colnames = array(
			'project_id' => "Project ID",
			'username' => "WSU Access ID",
			'lastname' => "Last Name",
			'firstname' => "First Name",
			'college' => "College",
			'department' => "Department",
			'category' => "Project Category",
			'TotalScore' => "Score",
			'PointsPossible' => "Points Possible",
			'ScorePercent' => "Score Percentage"
		);

		function map_colnames($input){
			global $colnames;
			return isset($colnames[$input]) ? $colnames[$input] : $input;
		}


	    function cleanData(&$str){
			$str = preg_replace("/\t/", "\\t", $str);
			$str = preg_replace("/\r?\n/", "\\n", $str);
			if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
		}

		// filename for download
		$filename = "GradExped_Scores_" . date('MY') . ".xls";

		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: application/vnd.ms-excel");
		//header("Content-Type: text/plain");
		$flag = false;
		$i = 0;
		while($i<count($data)) {
			if(!$flag) {
				// display field/column names as first row
				$firstline = array_map("map_colnames", $colnames);
				echo implode("\t", $firstline) . "\r\n";
				$flag = true;
			}
			array_walk($data[$i], 'cleanData');
			echo implode("\t", array_values($data[$i])) . "\r\n";
			$i++;
		}
	}
}