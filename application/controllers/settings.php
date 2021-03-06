<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

	public function general(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){
			$data['title'] = "WSU-GERSS :: Settings";

			$this->load->model('general_settings_model');
			$data['settings']= $this->general_settings_model->get_settings();

			$data['exhib_date']=$this->general_settings_model->format_date($data['settings']['exhib_date']);
			$data['reg_cutoff_date']=$this->general_settings_model->format_date($data['settings']['reg_cutoff_date']);
			$data['reg_start_date']=$this->general_settings_model->format_date($data['settings']['reg_start_date']);

			$data['scoring_started']=$this->general_settings_model->scores_are_entered();
					
			$this->load->view('settings_general_view', $data);
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function general_settings_form(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('home_msg','Welcome Message','required|trim|xss_clean');
			$this->form_validation->set_rules('exhib_date','Exhibition Date','required|trim');
			$this->form_validation->set_rules('exhib_start','Exhibition Start Time','required|trim');
			$this->form_validation->set_rules('exhib_end','Exhibition End Time','required|trim');
			$this->form_validation->set_rules('exhib_location','Exhibition Location','required|trim|xss_clean');
			$this->form_validation->set_rules('reg_start_date','Registration Start Date','required|trim');
			$this->form_validation->set_rules('reg_cutoff_date','Registration Cutoff Date','required|trim');
			$this->form_validation->set_rules('judges_per_project','Judges Per Project','required|numeric|trim');
			$this->form_validation->set_rules('projects_per_judge','Projects Per Judge','required|numeric|trim|xss_clean');
			$this->form_validation->set_rules('restrict_access','Restrict Access','required|trim');
			
			if ($this->form_validation->run()){

				$this->load->model('general_settings_model');

				if ($this->general_settings_model->update_settings()){
					$redirect=$this->session->set_flashdata('success','Settings Have Been Updated');
					redirect(base_url()."settings/general",$this->input->post('redirect'));
				}
				else{
					$redirect=$this->session->set_flashdata('errors','Problem Adding to the Database');
					redirect(base_url()."settings/general",$this->input->post('redirect'));
				}

			}
			else{
				$redirect=$this->session->set_flashdata('errors',validation_errors());
				redirect(base_url()."settings/general",$this->input->post('redirect'));
			}
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function categories(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){
			$this->load->model('category_settings_model');		
			$data['title'] = "WSU-GERSS :: Settings";
			switch($this->uri->segment(3)){
				case 'add':
					$this->add_category(urldecode($this->uri->segment(4)));
					break;
				case 'edit':
					$this->edit_category(urldecode($this->uri->segment(4)));
					break;
				case 'delete':
					if($this->category_settings_model->is_category_used($this->uri->segment(4))){
						$redirect=$this->session->set_flashdata('errors','Cannot delete it at this time. A project is registered under that category.');
						redirect(base_url()."settings/categories",$this->input->post('redirect'));
					}
					else{
						$this->category_settings_model->delete_category($this->uri->segment(4));
						$redirect=$this->session->set_flashdata('success','Project Category Deleted');
						redirect(base_url()."settings/categories",$this->input->post('redirect'));
					}
				default:
					$data['category']=$this->category_settings_model->get_all("categories");
					$data['subcategory']=$this->category_settings_model->get_all("subcategories");
					$data['subcat_criteria']=$this->category_settings_model->get_all("subcat_criteria");
					$this->load->view('settings_category_view', $data);
					break;
			}
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function add_category($category_name){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){
			$data['title'] = "WSU-GERSS :: Settings";					
			$this->load->view('add_category_view', $data);
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}	
	}

	public function edit_category($category_name){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){
			$data['title'] = "WSU-GERSS :: Settings";

			$this->load->model('category_settings_model');

			$data['category']=$this->category_settings_model->get_category($category_name);
			$data['subcategory']=$this->category_settings_model->get_all("subcategories");
			$data['subcat_criteria']=$this->category_settings_model->get_all("subcat_criteria");

			$this->load->view('edit_category_view', $data);
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function edit_category_validation(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){
//			echo '<pre>'; print_r($_POST); echo '</pre>';

			$this->load->library('form_validation');

			$this->form_validation->set_rules('category_name','Category Name','required|trim|xss_clean');

			foreach($_REQUEST['subcategory'] as $subcat_id=>$subcategory){
				$this->form_validation->set_rules(
						'subcategory['.$subcat_id.'][name]',
						'Subcategory Name','required|trim|xss_clean');
				
				if(isset($subcategory['criteria'])){
					foreach($subcategory['criteria'] as $criteria_id=>$subcat_criteria){
						$this->form_validation->set_rules(
							'subcategory['.$subcat_id.'][criteria]['.$criteria_id.'][desc]',
							'Subcategory Criterion - Description','required|trim|xss_clean');
						$this->form_validation->set_rules(
							'subcategory['.$subcat_id.'][criteria]['.$criteria_id.'][points]',
							'Subcategory Criterion - Points Possible','required|trim|numeric|xss_clean');
					}
				}
			}

			$orig_catname = $this->uri->segment(3);

			if ($this->form_validation->run()){

				
				$cat_id = $this->uri->segment(4);

				$this->load->model('category_settings_model');

				$did_update_category=$this->category_settings_model->update_category($cat_id);

				if ($did_update_category){
					foreach($_REQUEST['subcategory'] as $subcat_index=>$subcategory){

						if(isset($subcategory['id'])){					
							$subcat_id = $this->category_settings_model->update_subcategory($cat_id, $subcategory);
						}
						else{
							$subcat_id = $this->category_settings_model->add_subcategory($cat_id, $subcategory);
						}

	//TODO: I don't want to redirect on all db errors for subcategories and their criteria.
	//I want to add subcat/criteria name to an array and output list of failed attempts after 
	// everything that's valid has been added to db			

						if(isset($subcat_id)){
							if(isset($subcategory['criteria'])){
								foreach($subcategory['criteria'] as $criteria_index=>$subcat_criteria){
									

									if(isset($subcat_criteria['id'])){
										$this->input->post($subcat_criteria['id']);
									
										$data= array(
											'subcat_id' => $subcat_id,
											'desc' => $subcat_criteria['desc'],
											'points' => $subcat_criteria['points'],
											'criteria_id' => $subcat_criteria['id']
										);
										if(!$this->category_settings_model->update_criteria($data)){
											$redirect=$this->session->set_flashdata('errors','Database Error from '. $subcat_criteria['desc'] .' and '. $subcat_criteria['points'] . ' Please Try Again.');
											redirect(base_url()."settings/categories/edit/".$orig_catname,$this->input->post('redirect'));
										}
									}
									else{
									
										$data= array(
											'subcat_id' => $subcat_id,
											'desc' => $subcat_criteria['desc'],
											'points' => $subcat_criteria['points']
										);

										if(!$this->category_settings_model->add_criteria($data)){
											$redirect=$this->session->set_flashdata('errors','Database Error from '. $subcat_criteria['desc'] .' and '. $subcat_criteria['points'] . ' Please Try Again.');
											redirect(base_url()."settings/categories/edit/".$orig_catname,$this->input->post('redirect'));
										}
									}
								}
							}
						}
						else{
							$redirect=$this->session->set_flashdata('errors','Database Error from' . $subcategory['name'] . '. Please Try Again or Contact the Site Administrator');
							redirect(base_url()."settings/categories/edit/".$orig_catname,$this->input->post('redirect'));
						}
					}
				}
				else{
					$redirect=$this->session->set_flashdata('errors','Database Error. Please Try Again or Contact the Site Administrator');
					redirect(base_url()."settings/categories/edit/".$orig_catname,$this->input->post('redirect'));
				}
			}
			else{
				$redirect=$this->session->set_flashdata('errors',validation_errors());
				redirect(base_url()."settings/categories/edit/".$orig_catname,$this->input->post('redirect'));
			}

			//no errors
			$redirect=$this->session->set_flashdata('success','Project Category Updated');
			redirect(base_url()."settings/categories",$this->input->post('redirect'));
	 	}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function add_category_validation(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){
			$this->load->library('form_validation');

			$this->form_validation->set_rules('category_name','Category Name','required|trim|xss_clean|is_unique[categories.category]');

			foreach($_REQUEST['subcategory'] as $subcat_id=>$subcategory){
				$this->form_validation->set_rules(
						'subcategory['.$subcat_id.'][name]',
						'Subcategory Name','required|trim|xss_clean');
				
				foreach($subcategory['criteria'] as $criteria_id=>$subcat_criteria){
					$this->form_validation->set_rules(
						'subcategory['.$subcat_id.'][criteria]['.$criteria_id.'][desc]',
						'Subcategory Criterion - Description','required|trim|xss_clean');
					$this->form_validation->set_rules(
						'subcategory['.$subcat_id.'][criteria]['.$criteria_id.'][points]',
						'Subcategory Criterion - Points Possible','required|trim|numeric|xss_clean');
				}
			}


			if ($this->form_validation->run()){

				$this->load->model('category_settings_model');

				$cat_id = $this->category_settings_model->add_category();

				if (isset($cat_id)){
					foreach($_REQUEST['subcategory'] as $subcat_index=>$subcategory){
						
						$subcat_id = $this->category_settings_model->add_subcategory($cat_id, $subcategory);

	//TODO: I don't want to redirect on all db errors for subcategories and their criteria.
	//I want to add subcat/criteria name to an array and output list of failed attempts after 
	// everything that's valid has been added to db			

						if(isset($subcat_id)){
							foreach($subcategory['criteria'] as $criteria_index=>$subcat_criteria){
								$data= array(
									'subcat_id' => $subcat_id,
									'desc' => $subcat_criteria['desc'],
									'points' => $subcat_criteria['points']
								);
								if(!$this->category_settings_model->add_criteria($data)){
									$redirect=$this->session->set_flashdata('errors','Database Error from '. $subcat_criteria['desc'] .' and '. $subcat_criteria['points'] . ' Please Try Again.');
									redirect(base_url()."settings/categories/add",$this->input->post('redirect'));
								}
							}
						}
						else{
							$redirect=$this->session->set_flashdata('errors','Database Error from' . $subcategory['name'] . '. Please Try Again or Contact the Site Administrator');
							redirect(base_url()."settings/categories/add",$this->input->post('redirect'));
						}
					}
				}
				else{
					$redirect=$this->session->set_flashdata('errors','Database Error. Please Try Again or Contact the Site Administrator');
					redirect(base_url()."settings/categories/add",$this->input->post('redirect'));
				}
			}
			else{
				$redirect=$this->session->set_flashdata('errors',validation_errors());
				redirect(base_url()."settings/categories/add",$this->input->post('redirect'));
			}

			//no errors
			$redirect=$this->session->set_flashdata('success','Project Category Added');
			redirect(base_url()."settings/categories",$this->input->post('redirect'));
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function assign_judges(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){

			$this->load->model("judge_assignment_model");
			$this->load->model("general_settings_model");

			$assigned_judges = $this->judge_assignment_model->get_all_from('assigned_judges');

			$scoring_started=$this->general_settings_model->scores_are_entered();

			if($scoring_started == false)
			{
				if(count($assigned_judges)!=0){
					$this->judge_assignment_model->truncate_table('assigned_judges');
				}

				$settings = $this->judge_assignment_model->get_assignment_settings();

				$project = $this->judge_assignment_model->get_all_from('projects');
				$judge_type = $this->judge_assignment_model->get_judges();

				foreach($project as $proj){
					$participant_department = $this->judge_assignment_model->get_participant_department($proj->project_id);

					foreach($judge_type as $judge){

						$assigned_judges_count = $this->judge_assignment_model->count_assigned_judges($proj->project_id);
						
						if($assigned_judges_count < $settings['judges_per_project']){
							if($judge->department != $participant_department || $participant_department == 'Other' || $judge->department == 'Other'){
								$judge_project_count = $this->judge_assignment_model->count_assigned_projects($judge->id);
								if($judge_project_count < $settings['projects_per_judge']){
									$this->judge_assignment_model->assign_judge($proj->project_id, $judge->id);
								}
							}
						}
					}
				}
				$redirect=$this->session->set_flashdata('success','Judges Have Been Assigned');
				redirect(base_url()."settings/general",$this->input->post('redirect'));
			}
			else{
				$redirect=$this->session->set_flashdata('errors','Automatic Judge Assignment Disabled. Scoring Has Started.');
				redirect(base_url()."settings/general",$this->input->post('redirect'));
			}
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function delete_subcategory(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){
			$category = $this->uri->segment(3);
			$subcat_id = $this->uri->segment(4);
			$this->load->model("category_settings_model");
			$this->category_settings_model->delete_subcategory($subcat_id);
			redirect(base_url().'settings/categories/edit/'.$category);
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function delete_criteria(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){
			$category = $this->uri->segment(3);
			$criteria_id = $this->uri->segment(4);
			$this->load->model("category_settings_model");
			$this->category_settings_model->delete_criteria($criteria_id);
			redirect(base_url().'settings/categories/edit/'.$category);
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function export_scores(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){
			$this->load->model('scores_model');
			$scores = $this->scores_model->get_final_scores();
			$this->scores_model->export_scores($scores);
		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}

	public function reset_system(){
		if ($this->session->userdata('is_logged_in')&&$this->session->userdata('role')=='admin'){
			$this->load->model('general_settings_model');
			$this->general_settings_model->reset_system();

			$redirect=$this->session->set_flashdata('success','The system has been reset.');
			redirect(base_url()."settings/general",$this->input->post('redirect'));

		}
		else{
			$redirect=$this->session->set_flashdata('errors','You do not have sufficient permissions to view that page.');
			redirect(base_url()."gerss/home",$this->input->post('redirect'));
		}
	}
}