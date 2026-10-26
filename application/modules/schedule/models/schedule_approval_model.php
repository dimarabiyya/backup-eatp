<?php

/*Model for Table t_trx_schedule_result_approval*/
class schedule_approval_model extends CI_Model {
	
	/*Function save data checklist*/
	public function insert($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_trx_schedule_result_approval', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    	return 0;
	    }
		else
		{
	    	$this->db->trans_commit();
	    	return 1;//$this->db->insert_id();
	    }
	    
	}
	/*Fuction remove use approve when edit schedule*/
	public function remove($sch_id){
		$this->db->trans_begin();
		$this->db->where("visit_id",$sch_id);
		$this->db->delete("t_trx_schedule_result_approval");	
		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}
}
