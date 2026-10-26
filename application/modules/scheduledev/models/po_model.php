<?php

/*@ Class for Select PIC User group ID = 11*/
class po_model extends CI_Model {
	
	/*Get List PO Link*/
	public function getList(){		
		$data = array();
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;	
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'po.created_on';	
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));		
		
		
		$where = "
			WHERE
				(po.is_deleted is Null OR po.is_deleted = 0)
		";
		
		if ( ! empty($search))
		{
			$where .= "
					AND (po.po_no ILIKE '%".$search."%')
			";
		}
		
		$sql = "
			SELECT po.po_id,po.po_no,mreg.region_name,po.po_date from t_trx_po_xl po
			LEFT JOIN t_mtr_region_xl mreg on po.region_id = mreg.region_id 
			{$where}
			ORDER BY
			{$sort} {$order}			
		";

		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
		
		$total_rows = (int) $query->row()->total;
		
		$sql .= " LIMIT {$rows} OFFSET {$offset}";

		$query = $this->db->query($sql);
		$rows = $query->result_array();
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		return $data;
	}	
	
}
