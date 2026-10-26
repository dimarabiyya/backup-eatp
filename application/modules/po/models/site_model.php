<?php

/*@ Class for Select Site*/
class site_model extends CI_Model {
	
	/*Get List Site */
	public function getSiteList(){		
		$data = array();
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;	
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'site.created_on';	
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));	
		
		$region = $this->input->get('region');
		
		$where = "
			WHERE
				(site.is_deleted is Null OR site.is_deleted = 0)
				AND site.active = 1 
		";
		
		if ( ! empty($region) && $region!= 0){
			$where .= "AND site.region_id = ".$region."";
		}

		if ( ! empty($search))
		{
			$where .= "
				AND (site.site_id ILIKE '%".$search."%' 
				OR site.site_name ILIKE '%".$search."%'
				OR mreg.region_name ILIKE '%".$search."%'
				OR site.site_address ILIKE '%".$search."%')
			";
		}

		
		
		$sql = "
			SELECT site.*,mreg.region_name from t_mtr_site_xl site
			LEFT JOIN t_mtr_region_xl mreg on site.region_id = mreg.region_id
			{$where}
			ORDER BY
			{$sort} {$order}			
		";
		//echo $sql;die;
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
