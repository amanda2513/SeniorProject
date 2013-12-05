<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scores extends CI_Controller {

	public function input(){
		if ($this->session->userdata('is_logged_in') && (($this->session->userdata('role')=='admin')||($this->session->userdata('role')=='seu')||($this->session->userdata('role')=='judge'))){
				$data['title']="WSU-GERSS :: Scores";

				$this->load->model('scores_model');

				$scoring_requirements=$this->scores_model->is_scoring_open();
				$data['user_type'] = $this->session->userdata('role');

			if($scoring_requirements['all_projects_assigned'] && $scoring_requirements['exhibition_started'])
			{
				$this->load->model('users_model');
				$this->load->model('judge_assignment_model');
				$this->load->model('category_settings_model');
				if($this->session->userdata('role')=='judge'){
					$judges=$this->users_model->get_logged_in_judge();
				}
				else
				{
					$judges=$this->users_model->get_all_user_type('users','judge');
				}

				foreach($judges as $judge){
					$judge->scores_entered=$this->scores_model->get_score_entry_count($judge->id);
					$judge->projects_assigned = $this->judge_assignment_model->count_assigned_projects($judge->id);
				}

				$data['judges']=$judges;

				if($this->session->userdata('role')=='judge'){
					$data['assignments']=$this->scores_model->judge_assignment_model->get_logged_in_judge_projects($this->session->userdata('username'));
				}
				else
				{
					$data['assignments']=$this->scores_model->get_assigned_projects();
				}

				

				if($this->uri->segment(3)){
					$judge_id = $this->uri->segment(3);
					$participant_ln = $this->uri->segment(4);
					$participant_fn = $this->uri->segment(5);

					$data['project']=$this->scores_model->get_selected_project($judge_id, $participant_ln, $participant_fn);

					$data['category']=$this->category_settings_model->get_category($data['project']->category);
					$data['subcategory']=$this->category_settings_model->get_all("subcategories");
					$data['subcat_criteria']=$this->category_settings_model->get_all("subcat_criteria");

					$data['scores']=$this->scores_model->get_judge_scores($data['project']->project_id, $judge_id);
				}

				$this->load->view('input_scores_view',$data);
			}
			else{
				$data['scoring_requirements']=$scoring_requirements;
				$this->load->view('cannot_enter_scores_view',$data);
			}
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function view(){
		if ($this->session->userdata('is_logged_in')&& (($this->session->userdata('role')=='admin')||($this->session->userdata('role')=='seu')||($this->session->userdata('role')=='judge'))){
				$data['title']="WSU-GERSS :: Scores";

				$this->load->model('scores_model');

				$scoring_requirements=$this->scores_model->is_scoring_open();
				$data['user_type'] = $this->session->userdata('role');

			if($scoring_requirements['all_projects_assigned'] && $scoring_requirements['exhibition_started'])
			{
				$search_participant = $this->input->post('search_participants');
				if($this->uri->segment(3)=='filter'){
					$filtered = $this->uri->segment(3);
				}

				if(isset($filtered)){
					if($this->input->post('category')){
						$category = $this->input->post('category');
						$data['selected_category']=$category;
					}
				}

				$this->load->model('users_model');
				$this->load->model('judge_assignment_model');
				$this->load->model('category_settings_model');

				$data['categories']=$this->category_settings_model->get_all('categories');
				
				if($search_participant){
					$data['projects']=$this->users_model->filter_participant_info($search_participant);
				}
				elseif(isset($category)){
					$data['projects']=$this->users_model->filter_projects_by_category($category);
				}
				else{
					$data['projects']=$this->users_model->get_participant_info();
				}

				foreach($data['projects'] as $project){
					$project->judge_count=$this->judge_assignment_model->count_assigned_judges($project->project_id);
					$category = $this->category_settings_model->get_category($project->category);
					$project->category_pts_possible = $this->category_settings_model->get_category_pts_possible($category->cat_id);

					$project->judge_entry_count = $this->scores_model->get_judge_entry_count($project->project_id);
					$project->total_averaged_score = 0;
					$project->subcategories=$this->category_settings_model->get_subcategories($category->cat_id);
					foreach($project->subcategories as $scat_key=>$subcat){
						$criteria_count = 0;
						$subcat_score=0;
						$subcat->criteria = $this->category_settings_model->get_criteria($subcat->subcat_id);
						foreach($subcat->criteria as $crit_key=>$criterion){
							$criterion->avg_points = $this->scores_model->average_criterion_score($criterion->criteria_id,$project->project_id);
							$project->total_averaged_score+=$criterion->avg_points;
						}
					}
				}
				if($this->uri->segment(3)=='print'){
					$this->load->view('print_scores_view',$data);
				}
				else{
					$this->load->view('view_scores_view',$data);
				}
			}
			else{
				$data['scoring_requirements']=$scoring_requirements;
				$this->load->view('cannot_enter_scores_view',$data);
			}
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function score_validation(){
		if ($this->session->userdata('is_logged_in')&& (($this->session->userdata('role')=='admin')||($this->session->userdata('role')=='seu')||($this->session->userdata('role')=='judge'))){
			$project_id = $this->uri->segment(3);
			$judge_id = $this->uri->segment(4);
			$participant_ln = $this->uri->segment(5);
			$participant_fn = $this->uri->segment(6);

			//set validation rules
			$this->load->library('form_validation');
			foreach($_REQUEST['subcategory'] as $subcat_key=>$subcategory){

				foreach($subcategory['criteria'] as $criteria_key=>$subcat_criteria){

					$this->form_validation->set_rules(
						'subcategory['.$subcat_key.'][criteria]['.$criteria_key.'][score]',
						'Criteria Score','required|trim|numeric|xss_clean');
				}
			}

			//rules are met - add to database
			if($this->form_validation->run()){

				foreach($_REQUEST['subcategory'] as $subcategory){

					foreach($subcategory['criteria'] as $criteria_index=>$subcat_criteria){

							$data= array(
								'project_id' => $project_id,
								'judge_id' => $judge_id,
								'criteria_id' => $subcat_criteria['db_id'],
								'criteria_score' => $subcat_criteria['score']
							);
				

						$this->load->model('scores_model');

						if($this->scores_model->score_exists($data)){
							$this->scores_model->update_score($data);
						}
						else{
							$this->scores_model->add_score($data);
						}
					}
				}
				$redirect=$this->session->set_flashdata('success','Scores Have Been Updated');
				redirect(base_url()."scores/input",$this->input->post('redirect'));
			}
			else{
				//errors in form
				$redirect=$this->session->set_flashdata(array('errors'=>'Criteria Scores Are Required',$data));
				redirect(base_url()."scores/input/".$judge_id.'/'.$participant_ln.'/'.$participant_fn,$this->input->post('redirect'));
			}
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function participant_scorecard(){
		if ($this->session->userdata('is_logged_in')&& (($this->session->userdata('role')=='admin')||($this->session->userdata('role')=='seu')||($this->session->userdata('role')=='judge'))){
			$data['title']="WSU-GERSS :: Scorecard";

			$participant_id = $this->uri->segment(3);

			$this->load->model('scores_model');

			$this->load->model('users_model');
			$this->load->model('judge_assignment_model');
			$this->load->model('category_settings_model');
			
			$project_id = $this->users_model->get_project_id($participant_id);
			$project=$this->users_model->get_selected_project_info($project_id);

			$category = $this->category_settings_model->get_category($project->category);
			$project->category_pts_possible = $this->category_settings_model->get_category_pts_possible($category->cat_id);

			$data['subcategories']=$this->category_settings_model->get_subcategories($category->cat_id);
			$scats=array();
			foreach($data['subcategories'] as $scat_key=>$subcat){
				$criteria_count = 0;
				$subcat_score=0;
				$scats[$scat_key]['name']=$subcat->subcat_name;
				$criteria = $this->category_settings_model->get_criteria($subcat->subcat_id);
				$scats[$scat_key]['crits']=array();
				foreach($criteria as $crit_key=>$criterion){
					$scats[$scat_key]['crits'][$crit_key]['desc']=$criterion->criteria_description;
					$scats[$scat_key]['crits'][$crit_key]['points'] = $criterion->criteria_points;
					$criteria_count+=1;
					$subcat_score+=$criterion->criteria_points;
				}
				$scats[$scat_key]['criteria_count']=$criteria_count;
				$scats[$scat_key]['subcat_score']=$subcat_score;
			}
			$data['project']=$project;
			$data['category']=$category;
			$data['subcats']=$scats;
			$this->load->view('scorecard_view',$data);
		}
		else
		{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function judge_scorecard(){
		if ($this->session->userdata('is_logged_in')&& (($this->session->userdata('role')=='admin')||($this->session->userdata('role')=='seu')||($this->session->userdata('role')=='judge'))){
			$data['title']="WSU-GERSS :: Scorecard";

			$judge_id = $this->uri->segment(3);

			$this->load->model('scores_model');

			$this->load->model('users_model');
			$this->load->model('judge_assignment_model');
			$this->load->model('category_settings_model');

			$data['judge'] = $this->users_model->get_user_by_id($judge_id);

			$assignments = $this->judge_assignment_model->get_assigned_projects($judge_id);
			
			foreach($assignments as $assigned){			
				$project_id = $this->users_model->get_project_id($assigned->participant_id);
				$project=$this->users_model->get_selected_project_info($project_id);

				$category = $this->category_settings_model->get_category($project->category);
				$project->category_pts_possible = $this->category_settings_model->get_category_pts_possible($category->cat_id);

				$data['subcategories']=$this->category_settings_model->get_subcategories($category->cat_id);
				$scats=array();
				foreach($data['subcategories'] as $scat_key=>$subcat){
					$criteria_count = 0;
					$subcat_score=0;
					$scats[$scat_key]['name']=$subcat->subcat_name;
					$criteria = $this->category_settings_model->get_criteria($subcat->subcat_id);
					$scats[$scat_key]['crits']=array();
					foreach($criteria as $crit_key=>$criterion){
						$scats[$scat_key]['crits'][$crit_key]['desc']=$criterion->criteria_description;
						$scats[$scat_key]['crits'][$crit_key]['points'] = $criterion->criteria_points;
						$criteria_count+=1;
						$subcat_score+=$criterion->criteria_points;
					}
					$scats[$scat_key]['criteria_count']=$criteria_count;
					$scats[$scat_key]['subcat_score']=$subcat_score;
				}
				$data['project']=$project;
				$data['category']=$category;
				$data['subcats']=$scats;
				$this->load->view('scorecard_view',$data);
			}
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function all_participant_scorecards(){
		if ($this->session->userdata('is_logged_in') && $this->session->userdata('role')=='admin'){
			$data['title']="WSU-GERSS :: Scorecard";

			$participant_id = $this->uri->segment(3);

			$this->load->model('scores_model');

			$this->load->model('users_model');
			$this->load->model('judge_assignment_model');
			$this->load->model('category_settings_model');
			
			$projects = $this->users_model->get_participant_info();

			foreach($projects as $project){
				$category = $this->category_settings_model->get_category($project->category);
				$project->category_pts_possible = $this->category_settings_model->get_category_pts_possible($category->cat_id);

				$data['subcategories']=$this->category_settings_model->get_subcategories($category->cat_id);
				$scats=array();
				foreach($data['subcategories'] as $scat_key=>$subcat){
					$criteria_count = 0;
					$subcat_score=0;
					$scats[$scat_key]['name']=$subcat->subcat_name;
					$criteria = $this->category_settings_model->get_criteria($subcat->subcat_id);
					$scats[$scat_key]['crits']=array();
					foreach($criteria as $crit_key=>$criterion){
						$scats[$scat_key]['crits'][$crit_key]['desc']=$criterion->criteria_description;
						$scats[$scat_key]['crits'][$crit_key]['points'] = $criterion->criteria_points;
						$criteria_count+=1;
						$subcat_score+=$criterion->criteria_points;
					}
					$scats[$scat_key]['criteria_count']=$criteria_count;
					$scats[$scat_key]['subcat_score']=$subcat_score;
				}
				$data['project']=$project;
				$data['category']=$category;
				$data['subcats']=$scats;
				$this->load->view('scorecard_view',$data);
			}
		}
		else
		{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function all_judge_scorecards(){
		if ($this->session->userdata('is_logged_in') && $this->session->userdata('role')=='admin'){
			$data['title']="WSU-GERSS :: Scorecard";

			$this->load->model('scores_model');
			$this->load->model('users_model');
			$this->load->model('judge_assignment_model');
			$this->load->model('category_settings_model');

			$projects = $this->users_model->get_participant_info();
			
			foreach($projects as $project){			
				$category = $this->category_settings_model->get_category($project->category);
				$project->category_pts_possible = $this->category_settings_model->get_category_pts_possible($category->cat_id);

				$data['subcategories']=$this->category_settings_model->get_subcategories($category->cat_id);
				$scats=array();
				foreach($data['subcategories'] as $scat_key=>$subcat){
					$criteria_count = 0;
					$subcat_score=0;
					$scats[$scat_key]['name']=$subcat->subcat_name;
					$criteria = $this->category_settings_model->get_criteria($subcat->subcat_id);
					$scats[$scat_key]['crits']=array();
					foreach($criteria as $crit_key=>$criterion){
						$scats[$scat_key]['crits'][$crit_key]['desc']=$criterion->criteria_description;
						$scats[$scat_key]['crits'][$crit_key]['points'] = $criterion->criteria_points;
						$criteria_count+=1;
						$subcat_score+=$criterion->criteria_points;
					}
					$scats[$scat_key]['criteria_count']=$criteria_count;
					$scats[$scat_key]['subcat_score']=$subcat_score;
				}
				$data['project']=$project;
				$data['category']=$category;
				$data['subcats']=$scats;

				$judges=$this->judge_assignment_model->get_project_judges($project->project_id);
				foreach($judges as $judge){
					$data['judge'] = $judge;
					$this->load->view('scorecard_view',$data);
				}
			}
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}
}