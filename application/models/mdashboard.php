<?php

class mdashboard extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function getReportByRegionAndDate($region,$date_start,$date_end){
    	$where = " AND (po.region_id = ".$region.")";
    	$where .= " AND (po.po_date BETWEEN '{$date_start}' AND '{$date_end}')";
    	$sql = "
    		SELECT poli.po_type,mreg.region_name,
			count(po.po_id) as total_po,
			count(dipo.po_id) as total_din,
			count(ess.po_id) as total_ess,
			count(schd.po_id) as total_atp,
			count(n1.*) as total_done,
			count(n2.*) as total_notstart
			FROM t_trx_po_link poli
			LEFT JOIN t_trx_po_xl po on poli.po_id = po.po_id
			LEFT JOIN t_mtr_region_xl mreg on po.region_id = mreg.region_id
			LEFT JOIN t_trx_din_po dipo on poli.po_id = dipo.po_id
			LEFT JOIN t_trx_ess ess on poli.po_id = ess.po_id
			LEFT JOIN t_trx_schedule schd on poli.po_id = schd.po_id
			LEFT JOIN (
				SELECT tsc.po_id FROM t_trx_schedule tsc  
				WHERE	tsc.status = 3 and tsc.is_deleted = 0
			)n1 on poli.po_id = n1.po_id
			LEFT JOIN (
				SELECT tsc2.po_id FROM t_trx_schedule tsc2  
				WHERE	tsc2.status != 3 and tsc2.is_deleted = 0
			)n2 on poli.po_id = n2.po_id
			WHERE po.is_deleted = 0 {$where}
			GROUP BY poli.po_type, mreg.region_name
			ORDER BY poli.po_type DESC
    	";
    	//echo $sql;die;
    	$query = $this->db->query($sql);
    	return $query->result();
    }

    /*Get Region list from table t_mtr_region_xl*/
	public function getAllRegion(){
		$this->db->select('region.region_id, region.region_name');
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

}
