<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

	public function set_user_session(){
		$email = $this->input->post('email');
		$user_type = $this->get_single_user_type('users',$email);

		$data = array (
				'email'=>$email,
				'is_logged_in' => 1,
				'role'=>$user_type
			);
		$this->session->set_userdata($data);
	}

	public function can_log_in(){
		$this->db->where('email',$this->input->post('email'));
		$this->db->where('password',md5($this->input->post('password')));
		$query = $this->db->get('users');

		if($query->num_rows==1){
			return true;
		} else {
			return false;
		}
	}

	public function add_temp_user($key){
		$data = array (
			'usertype'=>$this->input->post('type'),
			'firstname'=>$this->input->post('firstname'),
			'lastname' =>$this->input->post('lastname'),
			'department'=>$this->input->post('department'),
			'email' => $this->input->post('email'),
			'password'=>md5($this->input->post('password')),
			'category'=>$this->input->post('category'),
			'title'=>$this->input->post('project_title'),
			'description'=>$this->input->post('project_desc'),
			'key' => $key
		);

		$query = $this->db->insert('temp_users',$data);
		if($query){
			return true;
		}
		else {
			return false;
		}
	}

	/*//Valid Key
	public function is_key_valid($key){
		$this->db->where('key', $key);
		$query = $this->db->get('temp_users');
		
		if ($query->num_rows() == 1){
			return true;
		}else return false;
	}*/
	
	//Add User To USERS Table
	public function add_user($key){
		$this->db->where('key', $key);
		$temp_user = $this->db->get('temp_users');
		
		if ($temp_user->num_rows() == 1){
			$row = $temp_user->row();
			
			$data = array(
				'email' => $row->email,
				'password'=>$row->password,
				'key' => $row->key,
				'firstname'=>$row->firstname,
				'lastname' =>$row->lastname,
				'department'=>$row->department,
				'title'=>$row->title,
				'description'=>$row->description,
				'usertype'=>$row->usertype,
				'category'=>$row->category
				);
				
				$did_add_user = $this->db->insert('users', $data);
		}
		if($did_add_user){
			$this->db->where('key', $key);
			$this->db->delete('temp_users');
			return true;
		}return false;
	}

	
	//FIND User In The USERS Table
	public function find_user($table_name,$usertype,$search){
		$this->db->like('lastname',$search,'after');
		$this->db->where('usertype',$usertype);
		$sql = $this->db->get($table_name);
		return $sql -> result();
	}


	public function get_user_by_username($username){
		$email=$username."@";
		$this->db->like('email',$email);
		$sql = $this->db->get('users');
		return $sql->row_array();
	}


	//Admin adds a user, skips temp-users table
	public function admin_add_user(){
		$data = array(
				'usertype'=>$this->input->post('type'),
				'firstname'=>$this->input->post('firstname'),
				'lastname' =>$this->input->post('lastname'),
				'department'=>$this->input->post('department'),
				'email' => $this->input->post('email'),
				'password'=>md5($this->input->post('password'))
				);
				
		$did_add_user = $this->db->insert('users', $data);

		if($did_add_user){
			return true;
		}
		return false;

	}

	public function add_participant(){
		$data = array(
				'usertype'=>$this->input->post('type'),
				'firstname'=>$this->input->post('firstname'),
				'lastname' =>$this->input->post('lastname'),
				'department'=>$this->input->post('department'),
				'email' => $this->input->post('email'),
				'password'=>md5($this->input->post('password')),
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
	public function get_single_user_type($table_name,$user_email){
		$sql = $this->db->get_where($table_name,array('email'=>$user_email));
		$user_record = $sql -> row();
		return $user_record -> usertype;
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
				'firstname'=>$this->input->post('firstname'),
				'lastname' =>$this->input->post('lastname'),
				'department'=>$this->input->post('department'),
				'email' => $this->input->post('email')
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
				'firstname'=>$this->input->post('firstname'),
				'lastname' =>$this->input->post('lastname'),
				'department'=>$this->input->post('department'),
				'email' => $this->input->post('email'),
				'password'=>md5($this->input->post('password')),
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
		$this->db->select('*');
		$this->db->from('participants');
		$this->db->join('projects', 'participants.project_id = projects.project_id');
		$this->db->join('users', 'participants.participant_id = users.id');

		$sql = $this->db->get();

		return $sql->result();
	}

	public function filter_participant_info($search){
		$this->db->like('lastname',$search,'after');
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
}