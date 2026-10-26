<?php

class msite_info_ref extends CI_Model {

	function getMenu($id){
		$group = 1;//$this->session->userdata('group');
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT *
			FROM t_mtr_menu_reference
			WHERE parent_id = {$id} AND published = 1 ORDER BY name ASC";
		$query = $dblokal->query($q);
		return $query->result();
	}
}