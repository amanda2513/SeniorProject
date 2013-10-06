<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

	public function set_user_session(){
		$data = array (
				'email'=>$this->input->post('email'),
				'is_logged_in' => 1
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
			'email' => $this->input->post('email'),
			'password'=>md5($this->input->post('password')),
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

	public function getUsers($table_name){
		$sql = $this->db->query('SELECT * FROM '.$table_name);
		return $sql -> result();

	}
}