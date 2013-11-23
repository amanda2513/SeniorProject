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

				$data['scores']=$this->scores_model->get_judge_scores($data['project']->project_id, $judge_id);
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

			$this->load->model('users_model');
			$this->load->model('judge_assignment_model');
			$this->load->model('scores_model');
			$this->load->model('category_settings_model');
			
			$data['projects']=$this->users_model->get_participant_info();
			//$data['scores'] =$this->scores_model->get_project_scores();

			foreach($data['projects'] as $project){
				$project->judge_count=$this->judge_assignment_model->count_assigned_judges($project->project_id);
				$category = $this->category_settings_model->get_category($project->category);
				$project->category_pts_possible = $this->category_settings_model->get_category_pts_possible($category->cat_id);

				$project->judge_entry_count = $this->scores_model->get_judge_entry_count($project->project_id);

				$criteria=$this->scores_model->get_distinct_criteria_ids($project->project_id);
				
				$project->total_averaged_score = 0;

				foreach($criteria as $key=>$criterion){
					$project->criterion[$key]['criteria_id']=$criterion->criteria_id;
					$project->criterion[$key]['desc']=$this->category_settings_model->get_criteria_name($criterion->criteria_id);
					$avg_scores = $this->scores_model->average_criterion_score($criterion->criteria_id);
					$project->criterion[$key]['avg_score'] = $avg_scores[0]['average_score'];
					$project->total_averaged_score += floatval($project->criterion[$key]['avg_score']);
				}
			}

			$this->load->view('view_scores_view',$data);
		}
		else{
			redirect('gerss/home');
		}
	}

	public function score_validation(){
		if ($this->session->userdata('is_logged_in')){
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
				redirect(base_url()."scores/input/".$judge_id.'/'.$participant_ln.'/'.$participant_fn,$this->input->post('redirect'));
			}
			else{
				//errors in form
				$redirect=$this->session->set_flashdata(array('errors'=>'Criteria Scores Are Required',$data));
				redirect(base_url()."scores/input/".$judge_id.'/'.$participant_ln.'/'.$participant_fn,$this->input->post('redirect'));
			}
		}
		else{
			//lacking permissions
			redirect('gerss/home');
		}
	}
	
}