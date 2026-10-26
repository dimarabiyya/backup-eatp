<?php

class din_model extends CI_Model {
	
	public function dinList()
	{
		$data = array();
		
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'din.created_on';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));

		/*Get Input Region*/
		$region = $this->input->get("region") ;
		
		$where = "
			WHERE
				(din.is_deleted IS NULL OR din.is_deleted = 0 and po.is_deleted = 0)
		";
		
		if ( ! empty($search))
		{
			$where .= "
				AND (din.din_no ILIKE '%".$search."%'
				OR po.po_no ILIKE '%".$search."%'
				OR din.din_file ILIKE '%".$search."%')
			";
		}
		if ( ! empty($region))
		{
			$where .= "
				AND (mreg.region_id = ".$region.")
			";
		}
		
		$sql = "		
			SELECT 
			    din.din_id,
			    din.din_no,
			    po.po_no AS po_no,
			    po.po_no AS link,
			    mreg.region_name AS region,
			    din.din_no AS din_no,
			    din.din_file AS file
			   FROM t_trx_din_po din_po 
				 LEFT JOIN t_trx_din din on din_po.din_id = din.din_id
				 LEFT JOIN t_trx_po_xl po on din_po.po_id = po.po_id
     			 LEFT JOIN t_mtr_region_xl mreg on po.region_id = mreg.region_id
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
			$r['download_file'] = '<a href='.''.base_url().'file/upload/din/'.$r['file'].' >'.$r['file'].'</a>';
			$r['action'] = '<a href="#/edit/'.$this->core->encode($r['din_id']).'" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>';
			$r['action'] .= '<a href="javascript:;" onclick="angular.element(document.getElementById(\'dinList\')).scope().delete(\''.$this->core->encode($r['din_id']).'\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>';
			
			$rows[] = $r;
		}
		
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		
		return $data;
	}

	public function getDin($id)
	{
		$this->db->where("din.din_id",$id);
		$this->db->select("din.*,po.po_no,po.po_id");
		$this->db->from("t_trx_din din");
		$this->db->join('t_trx_din_po dinpo', 'din.din_id = dinpo.din_id', 'LEFT');
		$this->db->join('t_trx_po_xl po', 'dinpo.po_id = po.po_id', 'LEFT');
		$res = $this->db->get()->row();
		return $res;
	}
	
	public function insert($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_trx_din', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }

	    return $this->db->insert_id();

	}
	public function insert_din_po($din_id,$po_id){
		$data = array('din_id'=> $din_id,
						'po_id' => $po_id); 
		$this->db->trans_begin();
		
		$this->db->insert('t_trx_din_po',$data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}
	public function update_din_po($din_id,$po_id){
		$data = array('po_id' => $po_id); 
		$this->db->trans_begin();

		$this->db
			->where('din_id', $din_id)
			->update('t_trx_din_po', $data);

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
			->where('din_id', $id)
			->update('t_trx_din', $data);

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
			->where('din_id', $id)
			->update('t_trx_din', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}

	/*update status for all po selected*/
	public function updateProgressStatus($po)
	{
		$data = new stdClass;
		$data->progress_status = 3;/*On Going*/
		$this->db->trans_begin();

		$this->db
			->where('po_id', $po)
			->update('t_trx_po_link', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}

	public function exportDin($format = 'excel')
	{
		if ($format == 'excel')
		{
			$sql = "
				SELECT 
			    din.din_id,
			    po.po_no AS po_no,
			    po.po_no AS link,
			    area.area_name AS area,
			    mreg.region_name AS region,
			    din.din_no AS din_no,
			    din.din_file AS file
			   FROM (((t_trx_din din
			     LEFT JOIN t_trx_po_xl po ON ((po.po_id = din.po_id)))
			     LEFT JOIN t_mtr_region_xl mreg ON ((mreg.region_id = po.region_id)))
			     LEFT JOIN t_mtr_area clu ON ((area.area_id = po.cluster_ops_id)))	
				WHERE (din.is_deleted IS NULL OR din.is_deleted = 0) ORDER BY din.created_on ASC
			";
			
			$query = $this->db->query($sql);
			
			$this->_writeToExcel(null, $query, array('din_id', 'is_deleted', 'created_on', 'created_by', 'updated_on', 'updated_by'));
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
				'Subtitle' => 'DIN Data',
				'Subject' => 'e-ATP of DIN Excel File',
				'Description' => 'DIN Data',
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
