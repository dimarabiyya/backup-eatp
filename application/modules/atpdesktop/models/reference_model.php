<?php

/*
@Rony Tampubolon*/
/**
* This Model is a Reference Model that return some Reference
* Date in Module AtpDesktop Such as Region,schedule
*/
class reference_model extends CI_Model {
	
	/*Get Region list from table t_mtr_region_xl*/
	public function regionList(){
		$this->db->where("is_deleted",0);
		$this->db->select('region.region_id, region.region_name');
		$this->db->from('t_mtr_region_xl region');
		$this->db->order_By("region.order_no","ASC");			
		$res = $this->db->get()->result();
		/*Declare temporary array variable*/
		/*Push each key and value to array*/
		$data = array();
		foreach($res as $v){
			$data[$v->region_id] = $v->region_name;
		}
		return $data;
	}
}
