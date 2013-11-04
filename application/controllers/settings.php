<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

	public function general(){
		if ($this->session->userdata('is_logged_in')){
			$data['title'] = "WSU-GERSS :: Settings";

			$this->load->model('general_settings_model');
			$data['settings']= $this->general_settings_model->get_settings();

			$data['exhib_date']=$this->general_settings_model->format_date($data['settings']['exhib_date']);
			$data['reg_cutoff_date']=$this->general_settings_model->format_date($data['settings']['reg_cutoff_date']);
					
			$this->load->view('settings_general_view', $data);
		}
		else{
			redirect('gerss/home');
		}
	}

	public function categories(){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('category_settings_model');		
			$data['title'] = "WSU-GERSS :: Settings";
			switch($this->uri->segment(3)){
				case 'add':
					$this->add_category($this->uri->segment(4));
					break;
				case 'edit':
					$this->edit_category($this->uri->segment(4));
					break;
				case 'delete':
					if($this->category_settings_model->delete_category($this->uri->segment(4))){
						$redirect=$this->session->set_flashdata('success','Project Category Deleted');
						redirect(base_url()."settings/categories",$this->input->post('redirect'));
					}
				default:
					$data['category']=$this->category_settings_model->get_all("categories");
					$this->load->view('settings_category_view', $data);
					break;
			}
		}
		else{
			redirect('gerss/home');
		}
	}

	public function general_settings_form(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('exhib_date','Exhibition Date','required|trim');
		$this->form_validation->set_rules('exhib_start','Exhibition Start Time','required|trim');
		$this->form_validation->set_rules('exhib_end','Exhibition End Time','required|trim');
		$this->form_validation->set_rules('exhib_location','Exhibition Location','required|trim|xss_clean');
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

	public function add_category($category_name){
		if ($this->session->userdata('is_logged_in')){
			$data['title'] = "WSU-GERSS :: Settings";					
			$this->load->view('add_category_view', $data);
		}
		else{
			redirect('gerss/home');
		}	
	}

	public function add_category_validation(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('category_name','Category Name','required|trim|xss_clean|is_unique[categories.category]');
		
		if ($this->form_validation->run()){

			$this->load->model('category_settings_model');
			
			if ($this->category_settings_model->add_category()){

				$redirect=$this->session->set_flashdata('success','Project Category Added');
				redirect(base_url()."settings/categories",$this->input->post('redirect'));

				//}	else echo "could not send the email.";
			}	else echo "problem adding to database.";

		}
		else{
			
			$redirect=$this->session->set_flashdata('errors',validation_errors());
			redirect(base_url()."settings/categories/add",$this->input->post('redirect'));
		}
	}

}