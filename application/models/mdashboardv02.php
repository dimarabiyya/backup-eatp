<?php
/*
Model Dashboard V02
*/
class mdashboardv02 extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    /*Get Region list from table t_mtr_region_xl*/
	public function getRegions(){
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
	/*Select MAX Year in POLINK*/
	public function getMaxYear(){
		$sql = "SELECT date_part('year', max(po.po_date)) as year
				from t_trx_po_link poli
				INNER JOIN t_trx_po_xl po on poli.po_id  = po.po_id
				";
		$rs = $this->db->query($sql)->row();
		return $rs->year;
	}
	/*Select MAX Year in POLINK*/
	public function getMinYear(){
		$sql = "SELECT date_part('year', min(po.po_date)) as year
				from t_trx_po_link poli
				INNER JOIN t_trx_po_xl po on poli.po_id  = po.po_id
				";
		$rs = $this->db->query($sql)->row();
		return $rs->year;
	}
	/*Select Count POLINK with tipe installation or addservice*/
	public function getCountPoli($is_installation,$region,$year){
		$date_start = $year."-01-01";
		$date_end = $year."-12-31";
		$where = "WHERE po.is_deleted = 0 AND (po.region_id = ".$region.")";
    	$where .= " AND (po.po_date BETWEEN '{$date_start}' AND '{$date_end}')";		
		if($is_installation){
			$where .= " AND poli.po_type = 'Installation'";
		}else{
			$where .= " AND poli.po_type != 'Installation'";
		}
		$sql = "SELECT count(*) from t_trx_po_link poli
				LEFT JOIN t_trx_po_xl po on poli.po_id = po.po_id
				{$where}
		";
		$res = $this->db->query($sql)->row();
		return $res->count;
	}
	/*Get Count DIN*/
	public function getCountDin($is_installation,$region,$year){
		$date_start = $year."-01-01";
		$date_end = $year."-12-31";
		$where = "WHERE po.is_deleted = 0 AND (po.region_id = ".$region.")";
    	$where .= " AND (po.po_date BETWEEN '{$date_start}' AND '{$date_end}')";		
		if($is_installation){
			$where .= " AND poli.po_type = 'Installation'";
		}else{
			$where .= " AND poli.po_type != 'Installation'";
		}
		$sql = "SELECT count(din.*) from t_trx_din_po din
				WHERE din.po_id IN (
					SELECT poli.po_id from t_trx_po_link poli
					LEFT JOIN t_trx_po_xl po on poli.po_id = po.po_id
					{$where}
				)				
		";
		$res = $this->db->query($sql)->row();
		return $res->count;
	}
	/*GET Count ESS*/
	/*Get Count DIN*/
	public function getCountESS($is_installation,$region,$year){
		$date_start = $year."-01-01";
		$date_end = $year."-12-31";
		$where = "WHERE po.is_deleted = 0 AND (po.region_id = ".$region.")";
    	$where .= " AND (po.po_date BETWEEN '{$date_start}' AND '{$date_end}')";		
		if($is_installation){
			$where .= " AND poli.po_type = 'Installation'";
		}else{
			$where .= " AND poli.po_type != 'Installation'";
		}
		$sql = "SELECT count(ess.*) from t_trx_ess ess
				WHERE ess.po_id  IN (
					SELECT poli.po_id from t_trx_po_link poli
					LEFT JOIN t_trx_po_xl po on poli.po_id = po.po_id
					{$where}
				)				
		";
		$res = $this->db->query($sql)->row();
		return $res->count;
	}
	/*Get All POLINK*/
	public function getPolink($region,$year){
		$date_start = $year."-01-01";
		$date_end = $year."-12-31";
		$where = "WHERE po.is_deleted = 0 AND (po.region_id = ".$region.")";
    	$where .= " AND (po.po_date BETWEEN '{$date_start}' AND '{$date_end}')";
		$sql = "SELECT poli.*,
				CASE WHEN poli.po_type = 'Installation' THEN 1 ELSE 0 END as tipe 
				FROM t_trx_po_link poli
				LEFT JOIN t_trx_po_xl po on poli.po_id = po.po_id
				{$where}";
		$res = $this->db->query($sql)->result();
		return $res;
	}
	/*Select Count PO, DIN, ESS group by type with param region and year*/
	public function getSummaryPolink($region,$year){
	 	$date_start = $year."-01-01";
		$date_end = $year."-12-31";
		$where = " where (po.region_id = ".$region.")";
    	$where .= " AND (po.po_date BETWEEN '{$date_start}' AND '{$date_end}')";
		$sql = "SELECT poli.po_type,poli.po_id,poli.link_id_int, po.region_id,
			 	CASE WHEN poli.po_type = 'Installation' THEN 1 ELSE 0 END as tipe 
				FROM t_trx_po_link poli 
				LEFT JOIN t_trx_po_xl po on poli.po_id = po.po_id 
				LEFT JOIN t_trx_din_po din on poli.po_id = din.po_id 
				LEFT JOIN t_trx_ess ess on poli.po_id = ess.po_id  
				{$where}
				GROUP by poli.po_type, tipe, po.region_id,poli.po_id,poli.link_id_int
				";
		$rs = $this->db->query($sql)->result();
		echo $sql;die;
		return $rs;
	}
	/*Select All Progress Status Based on Region and po_type*/
	public function getSummaryPolinkProgress($po_id,$link,$is_installation,$progress_flag){		
		$sql_type = "where poli.po_type != 'Installation'";
		if($is_installation){
			$sql_type = "where poli.po_type = 'Installation'";
		}
		$where = " AND (poli.po_id = ".$po_id." AND poli.link_id_int = ".$link.")";
		$where .= "  AND poli.progress_status = ".$progress_flag;
		$where = $sql_type."".$where;
		$sql = "SELECT count(*)
				FROM t_trx_po_link poli
				{$where}
				";	
		$res = $this->db->query($sql)->row();		
		return $res;
	}
}
