<?php

class email_setting_model extends CI_Model {
	
	public function email_settingList(){
		$data = array();
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'apr.created_on';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));	
			
		
		$where = "
			WHERE
				(apr.is_deleted = 0 )
		";
		
		if ( ! empty($search))
		{
			$where .= "
				AND (apr.template_name ILIKE '%".$search."%')
			";
		}

		$sql = "
			SELECT DISTINCT
                        (apr.template_name),is_default,case when is_default = 1 then 'Active' when is_default = 0 then 'Non Active' end as status
			from t_mtr_approval apr    			 
			{$where}
			 			
		";
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
		
		$total_rows = (int) $query->row()->total;
		
		$sql .= " LIMIT {$rows} OFFSET {$offset}";
		
		$query = $this->db->query($sql);
		
		$rows = array();
		foreach ($query->result_array() as $r)
		{
			 
			//$r['email_setting_type'] = $email_setting_type;
			$r['action'] = '<a href="#/edit/'.$this->core->encode($r['template_name']).'" class="btn-action glyphicons pencil btn-success" title="Mange"><i></i></a>';
			//$r['action'] .= '<a href="javascript:;" onclick="angular.element(document.getElementById(\'email_settingList\')).scope().delete(\''.$this->core->encode($r['email_setting_id']).'\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>';
			
			$rows[] = $r;
		}
		
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		
		return $data;
	}

	public function getApproval($id)
	{
		$this->db->select('apr.*,usr.user_name');
		$this->db->where('apr.email_setting_id',$id);
		$this->db->from('t_mtr_email_setting apr');
		$this->db->join('t_mtr_user usr', 'apr.user_id = usr.user_id');
		$query = $this->db->get()->row();
		return $query;
	}
	
	public function insert($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_mtr_email_setting', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}
	
	public function update($data, $id)
	{
		$this->db->trans_begin();

		$this->db
			->where('email_setting_id', $id)
			->update('t_mtr_email_setting', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}
	
	public function delete($id)
	{
		$data = new stdClass;
		$data->is_deleted = 1;
		
		$this->db->trans_begin();

		$this->db
			->where('email_setting_id', $id)
			->update('t_mtr_email_setting', $data);

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

		