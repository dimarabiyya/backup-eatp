<?php

class reference_model extends CI_Model {
	
	/*Get Region list from table t_mtr_region_xl*/
	public function regionList(){
		$this->db->select('region.region_id, region.region_name');
		$this->db->where("region.is_deleted",0);
		$this->db->from('t_mtr_region_xl region');		
		$res = $this->db->get()->result();
		/*Declare temporary array variable*/
		/*Push each key and value to array*/
		$data = array();
		foreach($res as $v){
			$data[$v->region_id] = $v->region_name;
		}
		return $data;
	}
	/*Get List PIC*/
	public function getPICList(){
		$data = array();
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;	
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'user_id';	
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));		
		
		$where = "
			WHERE
				(status = 1)
		";
		
		if ( ! empty($search))
		{
			$where .= "
				AND (user_name ILIKE '%".$search."%' 
				OR email ILIKE '%".$search."%')
			";
		}
		
		$sql = "
			SELECT * from t_mtr_user
			{$where}
			ORDER BY
			{$sort} {$order}			
		";
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
		
		$total_rows = (int) $query->row()->total;
		
		$sql .= " LIMIT {$rows} OFFSET {$offset}";

		$query = $this->db->query($sql);
		$total_rows = $query->num_rows();
		$rows = $query->result();
		/*Declare temporary array variable*/
		/*Push each key and value to array*/
		$data = array();
		$data[""] = "- Choose One -";
		foreach($rows as $v){
			$data[$v->user_id] = $v->user_id." | ".$v->user_name;
		}
		return $data;
	}
	/*Function Get List Site*/
	public function siteList(){
		$this->db->select('site.site_id,site.site_name');
		$this->db->from('t_mtr_site_xl site');		
		$res = $this->db->get()->result();
		/*Declare temporary array variable*/
		/*Push each key and value to array*/
		$data = array();
		$data[""] = "- Choose One -";
		foreach($res as $v){
			$data[$v->site_id] = $v->site_name;
		}
		return $data;
	}
	/*Function Get List PO*/
	public function getListPo(){
		$this->db->select('xl.po_id,xl.po_no');
		$this->db->where("xl.is_deleted",0);
		$this->db->from('t_trx_po_xl xl');
		$res = $this->db->get()->result();
		/*Declare temporary array variable*/
		/*Push each key and value to array*/
		$data = array();
		$data[""] = "- Choose One -";
		foreach($res as $v){
			$data[$v->po_id] = $v->po_no;
		}
		return $data;
	}
	/*function Detail Site*/
	/*Select by ID*/
	public function getDetailSite($id){
		$where = "where site.site_id = '".$id."'";
		$sql = "
				SELECT site.site_id,site.site_name,
				site.site_address, site.latitude,site.longitude,
				reg.region_name
				FROM t_mtr_site_xl site
				LEFT JOIN t_mtr_region_xl reg
				on site.region_id = reg.region_id
				{$where}
		";
		$result = $this->db->query($sql);			
		$rs = $result->row();
		return $rs;
	}
	/*function get list SOW*/
	public function getListSOW(){
		/*Get Data From t_mtr_checklist_options where option_key == pm_visit_type*/
		$this->db->select('opt.option_id,opt.option_value,opt.option_label,opt.locked');
		$this->db->from('t_mtr_checklist_options opt');		
		$this->db->where("opt.option_key = 'pm_visit_type'");
		$this->db->where("opt.status = 1");
		$this->db->order_by("opt.order_number","ASC");
		$res = $this->db->get()->result();
		/*Declare temporary array variable*/
		/*Push each key and value to array*/
		$data = $res;
		return $data;
	}
	/*function get list Approval*/
	public function getListApproval(){
		/*Get Data From t_mtr_checklist_options where option_key == pm_visit_type*/
		$this->db->select('template_name');
		$this->db->distinct();
		$this->db->from('t_mtr_approval');		
		$this->db->where("is_deleted = NULL OR is_deleted = 0");		
		$res = $this->db->get()->result();
		/*Declare temporary array variable*/
		/*Push each key and value to array*/
		$data = array();
		$data[""] = "- Choose One -";
		foreach($res as $v){
			$data[$v->template_name] = "". strtoupper($v->template_name);
                        
		}
		return $data;
	}

	/*Get PO Info and Site Info from LInk*/
	public function getSiteInfo($link){
		$where = "where link.link_id_int = ".$link."";
		$sql = "
				SELECT 
				DISTINCT po.po_no,
				site1.site_address as \"address_fe\",
				site1.longitude as \"longitude_fe\",
				site1.latitude as \"latitude_fe\",
				site2.site_address as \"address_ne\",
				site2.longitude as \"longitude_ne\",
				site2.latitude as \"latitude_ne\"
				FROM t_mtr_site_link link
				LEFT JOIN t_trx_po_link poli on poli.link_id_int = link.link_id_int
				LEFT JOIN t_trx_po_xl po on poli.po_id = po.po_id
				LEFT JOIN t_mtr_site_xl site1 on link.site_id_fe = site1.site_id
				LEFT JOIN t_mtr_site_xl site2 on link.site_id_ne = site2.site_id
				{$where}
		";
		$result = $this->db->query($sql);			
		$rs = $result->row();
		return $rs;
	}

	/*Get Region from PO_id*/
	public function getDetailPO($po_id){
		$this->db->where("po_id",$po_id);
		$rs = $this->db->get("t_trx_po_xl");
		return $rs->row();
	}
	/*Function get User id from template approval*/
	function getuserapproval($template_name){
		$this->db->where("is_deleted",0);
		$this->db->where("template_name",$template_name);
		$this->db->order_by("order_no","asc");
		$rs = $this->db->get("t_mtr_approval");
		return $rs->result();
	}
	
}
