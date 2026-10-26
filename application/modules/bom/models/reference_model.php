<?php

/*
@Rony Tampubolon*/
/**
* This Model is a Reference Model that return some Reference
*/
class reference_model extends CI_Model {
	
	/*Get PO_id by param po_no in trx_po_xl*/
	public function getPoBy($po_no){
		$this->db->select('po.*');
		$this->db->from('t_trx_po_xl po');		
		$this->db->where("po.po_no = '".$po_no."'");		
		$query = $this->db->get()->row();
		return $query;
	}

	/*Get link_int_id by param link_id from t_mtr_site_link*/
	public function getLink($link_id){
		$this->db->select('link.*');
		$this->db->from('t_mtr_site_link link');		
		$this->db->where("link.link_id ilike '".$link_id."'");	
		$query = $this->db->get()->row();
		return $query;
	}
	/*get Link by PO_id from t_mtr_Site_link*/
	public function getLinkByPO($po_id){
		$where = "where poli.po_id = {$po_id}"; 
		$sql =" SELECT poli.link_id_int ,sli.link_id from t_trx_po_link poli
				LEFT join t_mtr_site_link sli
				ON poli.link_id_int = sli.link_id_int
				{$where}
				";
		$qry_res = $this->db->query($sql);
		$res = $qry_res->result();
		return $res;
	}

	/*Check if exist BOM data by param po_no & link_id*/
	public function getBomByPoLink($po_id,$link_id_int,$code,$link_name){
		$this->db->select('bom.*');
		$this->db->from('t_trx_bom bom');		
		$this->db->where('bom.po_id',$po_id);
		$this->db->where('bom.link_id_int',''.$link_id_int);
		$this->db->where('bom.code',''.$code);
		$this->db->where('bom.config',''.$link_name);
		$query = $this->db->get()->row();
		return $query;
	}
	
}
