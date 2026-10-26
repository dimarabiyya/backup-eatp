<?php

class ess_model extends CI_Model {
	
	public function essList()
	{
		$data = array();
		
		//$site_id = $this->input->get('site_id');
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'order_date';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));
		$areaId = $this->input->get('cluster');
		$regionId = $this->input->get('region');
		//$poDateFrom = $this->input->get('poDateFrom');
		//$poDateTo = $this->input->get('poDateTo');
		
		$where = "
			WHERE
				(ess.is_deleted IS NULL OR ess.is_deleted = 0 and po.is_deleted = 0)
		";
		
		if ( ! empty($search))
		{
			$where .= "
				AND (ess.ess_no ILIKE '%".$search."%'
				OR po.po_no ILIKE '%".$search."%'
				OR region.region_name ILIKE '%".$search."%'				
				OR ess.ess_file ILIKE '%".$search."%')
			";
		}
		
		if ( ! empty($regionId))
		{
			$where .= " AND region.region_id = {$regionId}";
		}
		
		
		/*if ( ! empty($poDateFrom) && ! empty($poDateTo))
		{
			$where .= " AND (po.po_date BETWEEN '{$poDateFrom}' AND '{$poDateTo}')";
		}*/
		
		$sql = "
			SELECT
				ess.ess_id,
				ess.ess_no,
				po.po_no,
				ess.ess_file,				
				region.region_name,
				CASE WHEN ess.updated_on IS NULL THEN ess.created_on ELSE ess.updated_on END AS order_date
			FROM
				t_trx_ess ess
				LEFT JOIN t_trx_po_xl po ON ess.po_id = po.po_id
				LEFT JOIN t_mtr_region_xl region ON region.region_id = po.region_id
			{$where}
			ORDER BY
				{$sort} {$order}
		";
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
		
		$total_rows = (int) $query->row()->total;
		
		$sql .= " LIMIT {$rows} OFFSET {$offset}";
		
		$query = $this->db->query($sql);
		
		$rows = array();
		foreach ($query->result_array() as $r)
		{
			$r['download_file'] = '<a href='.''.base_url().'file/upload/ess/'.$r['ess_file'].' >'.$r['ess_file'].'</a>';
			$r['action'] = '<a href="#/edit/'.$this->core->encode($r['ess_id']).'" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>';
			$r['action'] .= '<a href="javascript:;" onclick="angular.element(document.getElementById(\'essList\')).scope().delete(\''.$this->core->encode($r['ess_id']).'\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>';
			
			$rows[] = $r;
		}
		
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		
		return $data;
	}

	public function getEss($id)
	{
		$this->db->where("ess.ess_id",$id);
		$this->db->select("ess.*,po.po_no");
		$this->db->from("t_trx_ess ess");
		$this->db->join('t_trx_po_xl po', 'ess.po_id = po.po_id', 'LEFT');
		$res = $this->db->get()->row();
		return $res;		
	}
	
	public function insert($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_trx_ess', $data);

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
			->where('ess_id', $id)
			->update('t_trx_ess', $data);

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
			->where('ess_id', $id)
			->update('t_trx_ess', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}

	public function exportEss($format = 'excel')
	{
		if ($format == 'excel')
		{
			$sql = "
				SELECT
					ess.ess_id,
					ess.ess_no,
					po.po_no,
					ess.ess_file,
					clu.cluster_name, /*Area*/
					region.region_name,
					CASE WHEN ess.updated_on IS NULL THEN ess.created_on ELSE ess.updated_on END AS order_date
				FROM
					t_trx_ess ess
					LEFT JOIN t_trx_po_xl po ON ess.po_id = po.po_id
					LEFT JOIN t_mtr_region_xl region ON region.region_id = po.region_id
					LEFT JOIN t_mtr_cluster_ops clu ON clu.cluster_ops_id = po.cluster_ops_id
				WHERE (ess.is_deleted IS NULL OR ess.is_deleted = 0) ORDER BY ess.created_on ASC
			";
			
			$query = $this->db->query($sql);
			
			$this->_writeToExcel(null, $query, array('po_id', 'is_deleted', 'created_on', 'created_by', 'updated_on', 'updated_by'));
		}
	}
  
	private function _writeToExcel($config = null, $query, $exclude)
	{
		$this->load->library('excel');

		$excel = new Excel();

		if ($config == null)
		{
			$config = array(
				'Creator' => $this->session->userdata('user_id'),
				'LastModified' => date('Y-m-d H:i:s'),
				'Title' => 'e-ATP Excel File',
				'Subtitle' => 'ESS Data',
				'Subject' => 'e-ATP of ESS Excel File',
				'Description' => 'ESS Data',
			);
		}

		$config = (object) $config;

		$excel->setHeader(str_replace(' ', '_', trim($config->Description).'_'.date('YmdHis').'.xls'));
		$excel->BOF();

		$headers = $query->row_array();
		$records = $query->result_array();
		
		$i = 0;
		foreach ($headers as $head_idx => $head_val)
		{
			if ( ! in_array($head_idx, $exclude))
			{
				$excel->writeLabel(0, $i, $head_idx);
				
				$j = 1;
				foreach ($records as $res_idx => $res_val)
				{
					if (is_numeric($res_val[$head_idx]))
					{
						$excel->writeNumber($j, $i, $res_val[$head_idx]);
					}
					else
					{
						$excel->writeLabel($j, $i, $res_val[$head_idx]);
					}
					
					$j++;
				}
				
				$i++;
			}
		}

		$excel->EOF();
		exit;
	}

}
