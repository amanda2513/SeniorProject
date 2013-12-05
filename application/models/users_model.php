<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {
/*
	public function set_user_session(){
		$username = $this->input->post('username');
		$user_type = $this->get_single_user_type('users',$username);

		$data = array (
				'username'=>$username,
				'is_logged_in' => 1,
				'role'=>$user_type
			);
		$this->session->set_userdata($data);
	}
*/
	public function is_registered(){
		//$this->db->where('username',md5($this->input->post('username')));
		$this->db->where('username',($this->input->post('username')));
		$query = $this->db->get('users');

		if($query->num_rows==1){
			return true;
		} 
		else{
			return false;
		}
	}

	public function can_log_in(){
		//$this->db->where('username',md5($this->input->post('username')));
		$this->db->where('username',($this->input->post('username')));
		$query = $this->db->get('users');
		$user_record = $query->row();
		$user_status = $user_record->status;

		if($user_status == "Enabled"){
			return true;
		} 
		else{
			return false;
		}
	}
	
	//Add User To USERS Table
	public function add_user(){
		$data = array (
			//'usertype'=>$this->input->post('type'),
			//'firstname'=>$this->input->post('firstname'),
			//'lastname' =>$this->input->post('lastname'),
			//'department'=>$this->input->post('department'),
			'id' => $this->input->post('id'),
			'category'=>$this->input->post('category'),
			'title'=>$this->input->post('project_title'),
			'description'=>$this->input->post('project_desc'),
		);
				
		$did_add_user = $this->db->insert('users', $data);
			if($did_add_user){
				return true;
			}else{
				return false;
			}
	}

	
	//FIND User In The USERS Table
	public function find_user($table_name,$usertype,$search){
		$this->db->like('lastname',$search,'after');
		$this->db->where('usertype',$usertype);
		$sql = $this->db->get($table_name);
		return $sql -> result();
	}


	public function get_user_by_username($username){
		$this->db->where('username',$username);
		$sql = $this->db->get('users');
		return $sql->row_array();
	}


	//Admin adds a user, skips temp-users table
	public function admin_add_user($ldap_info){
		$username = $this->input->post('userid');
		
		$data = array(
				'usertype'=>$this->input->post('type'),
				'firstname'=>$ldap_info['fn'],
				'lastname' =>$ldap_info['ln'],
				'department'=>$ldap_info['coll'],
				//'username'=>md5($this->input->post('userid')),
				'username'=>$username,
				);
				
		$did_add_user = $this->db->insert('users', $data);

		if($did_add_user){
			return true;
		}
		return false;

	}

	public function add_participant($ldap_info){

		$username = $this->input->post('userid');

		$data = array(
				'usertype'=>$this->input->post('type'),
				'firstname'=>$ldap_info['fn'],
				'lastname' =>$ldap_info['ln'],
				'department'=>$ldap_info['coll'],
				//'username'=>md5($this->input->post('userid')),
				'username'=>$username,
				);

			$did_add_user = $this->db->insert('users', $data);
			$participant_id = mysql_insert_id();

		if($did_add_user){
			$data = array(
				'category'=>$this->input->post('category'),
				'title'=>$this->input->post('project_title'),
				'description'=>$this->input->post('project_desc')
				);
			$did_add_project = $this->db->insert('projects',$data);
			$project_id = mysql_insert_id();

			if($did_add_project){
				$data = array(
					'participant_id'=>$participant_id,
					'project_id'=>$project_id
					);
				//bridge table
				$did_add_participant = $this->db->insert('participants',$data);
				if($did_add_participant){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//Admin delete a user
	public function admin_del_user($user_id){
						
		$did_del_user = $this->db->delete('users', array('id' => $user_id));
		
		if($did_del_user){
			return true;
		}
		return false;

	}

	public function admin_del_participant($participant_id){
		$project_id = $this->get_project_id($participant_id);
		$this->db->where('id',$participant_id);
		$did_delete_user = $this->db->delete('users');

		$this->db->where('project_id',$project_id);
		$did_delete_user =$this->db->delete('projects');

		if($did_delete_user){
			return true;
		}
		return false;

	}

	//Returns all users from table specified in variable table_name
	public function get_all_users($table_name){
		$sql = $this->db->query('SELECT * FROM '.$table_name);
		return $sql -> result();
	}

	//Returns all users that are the type (participant, judge, admin, score_entry) specified in user_type from the table specified in table_name
	public function get_all_user_type($table_name,$user_type){
		$this->db->order_by("lastname","asc");
		$sql = $this->db->get_where($table_name,array('usertype'=>$user_type));
		return $sql -> result();
	}

	//Returns a user's type (participant, judge, admin, score_entry) based on their email.
	public function get_single_user_type($table_name,$username){
		//$sql = $this->db->get_where($table_name,array('username'=>md5($username)));
		$sql = $this->db->get_where($table_name,array('username'=>$username));
		$user_record = $sql -> row();
		return $user_record -> usertype;
	}

	public function get_user_by_id($id){
		$sql = $this->db->get_where('users',array('id'=>$id));
		return $sql -> row();
	}

	//Returns a user's type (participant, judge, admin, score_entry) based on their id.
	public function get_user_type($user_id){
		$sql = $this->db->get_where('users',array('id'=>$user_id));
		$user_record = $sql -> row();
		return $user_record -> usertype;
	}

	public function update_user($id){
		$data = array(
				'usertype'=>$this->input->post('type'),
				//'firstname'=>$this->input->post('firstname'),
				//'lastname' =>$this->input->post('lastname'),
				//'department'=>$this->input->post('department'),
				//'username' => $this->input->post('userid')
				);

		$this->db->where('id',$id);
		$did_add_user = $this->db->update('users', $data);

		if($did_add_user){
			return true;
		}
		return false;

	}

	public function update_participant($id){
		$data = array(
				'usertype'=>$this->input->post('type'),
				//'firstname'=>$this->input->post('firstname'),
				//'lastname' =>$this->input->post('lastname'),
				//'department'=>$this->input->post('department'),
				//'username' => $this->input->post('username')
				);
		
		$this->db->where('id',$id);
		$did_update_user = $this->db->update('users', $data);

		$project_id = $this->get_project_id($id);

		if($did_update_user){
			$data = array(
				'category'=>$this->input->post('category'),
				'title'=>$this->input->post('project_title'),
				'description'=>$this->input->post('project_desc')
				);
			$this->db->where('project_id',$project_id);
			$did_update_project = $this->db->update('projects',$data);
		}
		else{
			return false;
		}
	}

	public function get_participant_info(){
		//echo "get_participant_info";
		$this->db->select('*');
		$this->db->from('participants');
		$this->db->join('projects', 'participants.project_id = projects.project_id');
		$this->db->join('users', 'participants.participant_id = users.id');

		$sql = $this->db->get();

		return $sql->result();
	}
//NOTE: Missing in Plamen's.
	public function get_selected_project_info($project_id){
		$this->db->where('projects.project_id',$project_id);
		$this->db->from('projects');
		$this->db->join('participants', 'participants.project_id = projects.project_id');
		$this->db->join('users', 'participants.participant_id = users.id');

		$sql = $this->db->get();

		return $sql->row();
	}

	public function filter_participant_info($search){
		$this->db->like('lastname',$search,'after');
		$this->db->from('participants');
		$this->db->join('projects', 'participants.project_id = projects.project_id');
		$this->db->join('users', 'participants.participant_id = users.id');

		$sql = $this->db->get();

		return $sql->result();
	}
//NOTE: Missing in Plamen's
	public function filter_projects_by_category($category_name){
		$this->db->where('category',$category_name);
		$this->db->from('projects');
		$this->db->join('participants', 'participants.project_id = projects.project_id');
		$this->db->join('users', 'participants.participant_id = users.id');

		$sql = $this->db->get();

		return $sql->result();
	}

	public function get_project_by_username($username){
		$this->db->where('username',$username);
		$this->db->from('participants');
		$this->db->join('projects', 'participants.project_id = projects.project_id');
		$this->db->join('users', 'participants.participant_id = users.id');

		$sql = $this->db->get();

		return $sql->result();
	}

	public function get_judge_info(){
		$this->db->where('usertype','judge');
		$sql = $this->db->get('users');

		return $sql->result();
	}

	public function get_logged_in_judge(){
		$username=$this->session->userdata('username');
		$this->db->where('username',$username);
		$sql = $this->db->get('users');

		return $sql->result();
	}

	public function filter_judge_info($search){
		$this->db->like('lastname',$search,'after');
		$this->db->where('usertype','judge');
		$sql = $this->db->get('users');

		return $sql->result();
	}

	public function get_project_id($participant_id){
		$this->db->where('participant_id',$participant_id);
		$sql = $this->db->get('participants');
		$result = $sql->row();
		$project_id = $result->project_id;
		return $project_id;
	}

	public function get_project_data($project_id){
		$this->db->where('project_id',$project_id);
		$sql = $this->db->get('projects');
		return $sql->row();
	}

	public function change_user_status($user_id, $status){
		$this->db->where('id',$user_id);
		$this->db->update('users', array('status'=>$status));
	}

	public function change_role_status($role, $status){
		$this->db->where('usertype',$role);
		$this->db->update('users', array('status'=>$status));
	}
}