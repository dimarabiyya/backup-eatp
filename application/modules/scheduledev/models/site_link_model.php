<?php

class site_link_model extends CI_Model {
	
	/*Get List Site Link*/
	public function getSiteLinkList(){		
		$data = array();
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;	
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'created_on';	
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));		
		
		
		$where = "
			WHERE
				(is_deleted is Null OR is_deleted = 0)
		";
		
		if ( ! empty($search))
		{
			$where .= "
				AND (link_id ILIKE '%".$search."%' 
				OR site_id_ne ILIKE '%".$search."%'
				OR site_id_fe ILIKE '%".$search."%')
			";
		}
		
		$sql = "
			SELECT * from t_mtr_site_link
			{$where}
			ORDER BY
			{$sort} {$order}			
		";
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
		
		$total_rows = (int) $query->row()->total;
		
		$sql .= " LIMIT {$rows} OFFSET {$offset}";

		$query = $this->db->query($sql);
		$total_rows = $query->num_rows();
		$rows = $query->result_array();
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		return $data;
	}
	public function getLinkByPO($po){
		//$where = "where poli.po_id = ".$po." AND link.is_deleted = 0";
		$where = "where poli.po_id = ".$po." AND link.is_deleted = 0 and link.link_id_int NOT IN 
				(
					Select sch.link_id from t_trx_schedule sch 
					where sch.po_id = {$po}
				)";
		$sql = "
				SELECT link.link_id_int, link.link_id from t_mtr_site_link link
				LEFT JOIN t_trx_po_link poli				
				ON link.link_id_int = poli.link_id_int
				{$where}
		";
		
		$result = $this->db->query($sql);			
		$rs = $result->result();
		return $rs;
	}	
}
