<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

	public function set_user_session(){
		$email = $this->input->post('email');
		$user_type = $this->get_single_user_type('users',$email);

		$data = array (
				'email'=>$email,
				'is_logged_in' => 1,
				'user_type'=>$user_type
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

		//$query = $this->db->insert('users_test',$data);
		$query = $this->db->insert('temp_users',$data);
		if($query){
			return true;
		}
		else {
			return false;
		}
	}

	//Valid Key
	public function is_key_valid($key){
		$this->db->where('key', $key);
		$query = $this->db->get('temp_users');
		
		if ($query->num_rows() == 1){
			return true;
		}else return false;
	}
	
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
		$sql = $this->db->get_where($table_name,array('lastname'=>$search, 'usertype'=>$usertype));
		return $sql -> result();
	}


	public function get_user_by_id($user_id){
		$sql = $this->db->get_where('users',array('id'=>$user_id));
		return $sql->row_array();
	}


	//Admin adds a user, skips temp-users table FOR NOW
	public function admin_add_user(){
		$data = array(
				'usertype'=>$this->input->post('type'),
				'firstname'=>$this->input->post('firstname'),
				'lastname' =>$this->input->post('lastname'),
				'department'=>$this->input->post('department'),
				'email' => $this->input->post('email'),
				'password'=>md5($this->input->post('password')),
				'category'=>$this->input->post('category'),
				'title'=>$this->input->post('project_title'),
				'description'=>$this->input->post('project_desc')
				);
				
		$did_add_user = $this->db->insert('users', $data);

		if($did_add_user){
			return true;
		}
		return false;

	}
	
	//Admin delete a user
	public function admin_del_user($table_name, $del_user){
						
		$did_del_user = $this->db->delete($table_name, array('id' => $del_user));
		
		//$this->db->delete('mytable', array('id' => $del_user)); 

		if($did_del_user){
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
		$sql = $this->db->get_where($table_name,array('usertype'=>$user_type));
		return $sql -> result();
	}

	//Returns a user's type (participant, judge, admin, score_entry) based on their email.
	public function get_single_user_type($table_name,$user_email){
		$sql = $this->db->get_where($table_name,array('email'=>$user_email));
		$user_record = $sql -> row();
		return $user_record -> usertype;
	}
}