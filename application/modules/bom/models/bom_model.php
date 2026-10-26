<?php

class bom_model extends CI_Model {
	
	public function bomList()
	{
		$data = array();
		
		//$site_id = $this->input->get('site_id');
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'bom.created_on';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));
		$areaId = $this->input->get('cluster');
		$regionId = $this->input->get('region');
		//$poDateFrom = $this->input->get('poDateFrom');
		//$poDateTo = $this->input->get('poDateTo');
		
		$where = "
			WHERE
				(bom.is_deleted != 1 and po.is_deleted = 0)
		";
		
		if ( ! empty($search))
		{
			$where .= "
				AND ((bom.material_description ILIKE '%".$search."%')
				OR (bom.code ILIKE '%".$search."%')
				OR (bom.remarks ILIKE '%".$search."%')
				OR (sl.link_id ILIKE '%".$search."%')
				OR (bom.uom ILIKE '%".$search."%')
				OR (po.po_no ILIKE '%".$search."%'))
				
			";
		}
		
		if ( ! empty($regionId))
		{
			$where .= " AND region.region_id = {$regionId}";
		}
		
		if ( ! empty($areaId))
		{
			$where .= " AND clu.cluster_ops_id = {$clusterId}";
		}
		/*if ( ! empty($poDateFrom) && ! empty($poDateTo))
		{
			$where .= " AND (po.po_date BETWEEN '{$poDateFrom}' AND '{$poDateTo}')";
		}*/
		
		$sql = "
			SELECT
				bom.bom_id,
				po.po_no,
				bom.config,
				bom.code,
				bom.material_description,
				bom.qty_requirement,
				bom.qty_po,
				bom.qty_actual,
				bom.status_boq,
				bom.remarks,
				bom.uom,
				bom.link_id_int,
				sl.link_id,				
				region.region_name,
				CASE WHEN bom.updated_on IS NULL THEN bom.created_on ELSE bom.updated_on END AS order_date,
				CASE WHEN bom.status_boq = 1 THEN 'Active' ELSE 'Non-Active' END AS status_desc
			FROM
				t_trx_bom bom
				LEFT JOIN t_trx_po_xl po ON bom.po_id = po.po_id
				LEFT JOIN t_mtr_region_xl region ON region.region_id = po.region_id
				left JOIN t_mtr_site_link sl ON bom.link_id_int = sl.link_id_int
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
			$r['action'] = '<a href="#/edit/'.$this->core->encode($r['bom_id']).'" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>';

			$r['action'] .= '<a href="javascript:;" onclick="angular.element(document.getElementById(\'bomList\')).scope().delete(\''.$this->core->encode($r['bom_id']).'\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>';
			if($r['config']=="null") $r['config'] = "-";
			if($r['code']=="null") $r['code'] = "-";
			if($r['remarks']=="null") $r['remarks'] = "-";
			
			$rows[] = $r;
		}
		
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		
		return $data;
	}

	public function getBom($id)
	{
		$where = "where bom_id = {$id}";
		$sql ="SELECT bm.*,sl.link_id as link_id,po.po_no from t_trx_bom bm
				left JOIN t_mtr_site_link sl
				ON bm.link_id_int = sl.link_id_int
				left join t_trx_po_xl po on bm.po_id = po.po_id
				{$where}
				";
		$res = $this->db->query($sql);
		return $res->row();
	}
	
	public function insert($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_trx_bom', $data);

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
			->where('bom_id', $id)
			->update('t_trx_bom', $data);

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
			->where('bom_id', $id)
			->update('t_trx_bom', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}

	public function exportBom($format = 'excel')
	{
		if ($format == 'excel')
		{
			$sql = "
				SELECT
				bom.bom_id,
				po.po_no,
				bom.config,
				bom.code,
				bom.material_description,
				bom.qty_requirement,
				bom.qty_po,
				bom.qty_actual,
				bom.status_boq,
				bom.remarks,
				bom.uom,
				bom.link_id_int,
				sl.link_id,				
				region.region_name,
				CASE WHEN bom.updated_on IS NULL THEN bom.created_on ELSE bom.updated_on END AS order_date,
				CASE WHEN bom.status_boq = 1 THEN 'Active' ELSE 'Non-Active' END AS status_desc
				FROM t_trx_bom bom
				LEFT JOIN t_trx_po_xl po ON bom.po_id = po.po_id
				LEFT JOIN t_mtr_region_xl region ON region.region_id = po.region_id
				left JOIN t_mtr_site_link sl ON bom.link_id_int = sl.link_id_int
				WHERE (bom.is_deleted IS NULL OR bom.is_deleted = 0) ORDER BY bom.created_on ASC
			";
			
			$query = $this->db->query($sql);
			$header = array("PO NO.","Config","Code","Material Desc","Qty Requirement","Qty PO","Qty Actual","Status Boq","Remarks","Uom","LINK ID","Region");
			$exclude = array("po_no","config","code","material_description","qty_requirement",
				"qty_po","qty_actual","status_boq","remarks","uom","link_id","region_name");
			$this->exportExcel($header,$query,$exclude);
		}
	}
  	
  	public function exportExcel($head,$query,$exclude){
 
        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();	
			$records = $query->result_array();
        //set Sheet yang akan diolah 
        $objPHPExcel->setActiveSheetIndex(0);
        /*Fill Data Header to Excel File*/
        $row =1;
        for($i=0;$i<count($head);$i++){
        	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(($i),$row,$head[$i]);	
        	$objPHPExcel->getActiveSheet()->getStyle('A'.($i+1))->getFill()->getStartColor()->setRGB('FF0000');
        }
        /*Fille Data PO in Excel*/
        $row=2;
        foreach ($records as $item){	
			$column=0;		
			foreach($exclude as $header_key => $header_val){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(($column),$row,$item[$header_val]);
				$column++;
			}
			$row++;
		}
        
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,2,'Rony');
        $objPHPExcel->getActiveSheet()
				    ->getStyle('A1:L1')
				    ->getFill()
				    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				    ->getStartColor()
				    ->setARGB('FFCCCCCC');
        //set title pada sheet (me rename nama sheet)
        $objPHPExcel->getActiveSheet()->setTitle('Export BOM EATP');

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya 
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="BOM_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
        //unduh file
        $objWriter->save("php://output");
	}
}
