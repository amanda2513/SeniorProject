<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

	public function set_user_session(){
		$email = $this->input->post('email');
		$user_type = $this->getSingleUserType('users',$email);

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

		$query = $this->db->insert('users',$data);
		if($query){
			return true;
		}
		else {
			return false;
		}
	}

	//Returns all users from table specified in variable table_name
	public function getAllUsers($table_name){
		$sql = $this->db->query('SELECT * FROM '.$table_name);
		return $sql -> result();
	}

	//Returns all users that are the type (participant, judge, admin, score_entry) specified in user_type from the table specified in table_name
	public function getAllUserType($table_name,$user_type){
		$sql = $this->db->get_where($table_name,array('usertype'=>$user_type));
		return $sql -> result();
	}

	//Returns a user's type (participant, judge, admin, score_entry) based on their email.
	public function getSingleUserType($table_name,$user_email){
		$sql = $this->db->get_where($table_name,array('email'=>$user_email));
		$user_record = $sql -> row();
		return $user_record -> usertype;
	}
}