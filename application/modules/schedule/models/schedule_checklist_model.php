<?php

class schedule_checklist_model extends CI_Model {
	
	/*Function Get Checklist from Schedule ID*/
	public function getChecklistOption($sch_id){
		$this->db->where("visit_id",$sch_id);
		$rs = $this->db->get("t_trx_schedule_checklist");
		return $rs->result();
	}
	/*Function save data checklist*/
	public function insert($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_trx_schedule_checklist', $data);

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
	/*Remove all Checklist Model when update*/
	public function remove($sch_id){
		$this->db->trans_begin();
		$this->db->where("visit_id",$sch_id);
		$this->db->delete("t_trx_schedule_checklist");	
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
