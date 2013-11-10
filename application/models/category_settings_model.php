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
			$sql = $this->db->get_where('categories',array('category'=>$this->input->post('category_name')));
			$cat_record = $sql -> row();
			return $cat_record -> cat_id;
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

	public function add_subcategory($cat_id,$subcategory){
		$data = array(
				'cat_id'=>$cat_id,
				'subcat_name'=>$subcategory['name']
				);
				
		$did_add_subcategory = $this->db->insert('subcategories', $data);

		if($did_add_subcategory){
			$sql = $this->db->get_where('subcategories',array('subcat_name'=>$subcategory['name'], 'cat_id'=>$cat_id));
			$subcat_record = $sql -> row();
			return $subcat_record -> subcat_id;
		}
		
	}

	public function add_criteria($criteria){

		$data = array(
				'subcat_id'=>$criteria['subcat_id'],
				'criteria_description'=>$criteria['desc'],
				'criteria_points'=>$criteria['points']
				);
				
		$did_add_criteria = $this->db->insert('subcat_criteria', $data);

		if($did_add_criteria){
			return true;
		}
		return false;
	}

	public function get_category($category_name){
		$sql = $this->db->get_where('categories',array('category'=>$category_name));
		$category = $sql -> row();
		return $category;
	}

	public function get_subcategories($cat_id){
		$sql = $this->db->get_where('subcategories',array('cat_id'=>$cat_id));
		return $sql -> result();
	}

	public function get_criteria($subcat_id){
		$sql = $this->db->get_where('subcat_id', array('subcat_id'=>$subcat_id));
		return $sql -> result();
	}

}