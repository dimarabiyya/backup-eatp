<?php

/*
@Rony Tampubolon*/
/**
* This Model is a Reference Model that return some Reference
* 
*/
class reference_model extends CI_Model {
	
	/*Get Region list from table t_mtr_region_xl*/
	public function regionList(){
		$this->db->select('region.region_id, region.region_name');
		$this->db->from('t_mtr_region_xl region');		
		$res = $this->db->get()->result();
		/*Declare temporary array variable*/
		/*Push each key and value to array*/
		$data = array();
		$data[0] = "All";
		foreach($res as $v){
			$data[$v->region_id] = $v->region_name;
		}
		return $data;
	}
	/*Get detail visit*/
	public function getSchedule($id){
		$this->db->where("visit_id",$id);
		$rs = $this->db->get("t_trx_schedule");
		return $rs->row();
	}
}
