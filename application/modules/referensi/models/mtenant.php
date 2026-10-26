<?php

class Mtenant extends CI_Model {
	
	public function get_list()
	{
		$data = array();
		
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'ord';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->input->get('query');
		
		$where = "
			WHERE tenant.status = 1
			AND
			(
			UPPER(tenant.tenant_name) LIKE '%".strtoupper($search)."%'
			)
		";
		
		$sql = "
			SELECT
				tenant.tenant_id,
				tenant.tenant_name,
				CASE
					WHEN tenant.updated_on IS NULL THEN tenant.created_on
				ELSE
					tenant.updated_on
				END as ord
			FROM t_mtr_tenant tenant
			{$where}
			ORDER BY  {$sort} {$order}
		";
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
		
		$total_rows = (int) $query->row()->total;
		
		$sql .= " LIMIT {$rows} OFFSET {$offset}";
		
		$query = $this->db->query($sql);
		
		$rows = array();
		foreach ($query->result_array() as $r) {
			$r['tenant_id'] = $this->core->encode($r['tenant_id']);
			$rows[] = $r;
		}
		
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		
		$sql = str_replace("\t", '', $sql);
		$sql = str_replace("\n", ' ', $sql);
		$data['sql'] = $sql;
		
		return $data;
	}

	public function get_tenant($id)
	{
		return $this->db
			->get_where('t_mtr_tenant', array('tenant_id' => $id, 'status' => 1))
			->row();
	}
	
	public function add($data)
	{
		$this->db->insert('t_mtr_tenant', $data);
	}
	
	public function edit($data, $id)
	{
		$this->db->update('t_mtr_tenant', $data, array('tenant_id' => $id));
	}
	
	public function delete($id)
	{
		$this->edit(array('status' => 0), $id);
	}
	
	public function get_tenants($params)
	{
		return $this->db
			->get_where('t_mtr_tenant', $params)
			->result();
	}
	
	public function get_list_fields()
	{
		return $this->db->list_fields('t_mtr_tenant');
	}
}