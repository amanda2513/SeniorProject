<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects_participants_model extends CI_Model {

	function getParticipantProjects(){
		$sql = mysql_query('SELECT * FROM participants');
		return $sql;
	}
}