<?php

class user_approval_model extends CI_Model {
	
	public function getListUser(){
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
		$rows = $query->result_array();
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		return $data;
	}
}

	