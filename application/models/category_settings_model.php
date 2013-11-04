<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_settings_model extends CI_Model {
	
	public function get_all($table_name){
		$sql = $this->db->query('SELECT * FROM ' . $table_name);
		return $sql -> result();
	}

	public function add_category(){
		$data = array(
				'category'=>$this->input->post('category_name'),
				);
				
		$did_add_category = $this->db->insert('categories', $data);

		if($did_add_category){
			return true;
		}
		return false;
	}

	public function delete_category($category_id){

		$did_del_user = $this->db->delete('categories', array('cat_id' => $category_id));

		if($did_del_user){
			return true;
		}
		return false;
	}

}