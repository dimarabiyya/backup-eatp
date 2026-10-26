<?php

class Po_model extends CI_Model {
	
	public function poList()
	{
		$data = array();
		
		$site_id = $this->input->get('site_id');
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'po.po_date';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));
		$regionId = $this->input->get('region');
		$poDateFrom = $this->input->get('poDateFrom');
		$poDateTo = $this->input->get('poDateTo');
		
		$where = "
			WHERE
				(po.is_deleted IS NULL OR po.is_deleted = 0 and li.is_deleted = 0 and mreg.is_deleted = 0 )
		";
		
		if ( ! empty($search))
		{
			
			/*Sort By EWO & NBWO type*/
			if(strtolower($search) == "ewo"){
				$where .= "
						AND (poli.order_type = 2)
					";	
			}else if(strtolower($search) == "nbwo"){
				$where .= "
						AND (poli.order_type = 1)
					";	
			}else{
				$where .= "
					AND (po.po_no ILIKE '%".$search."%' 
					OR poli.po_description ILIKE '%".$search."%'
					OR li.link_id ILIKE '%".$search."%'
					OR poli.ewo_no ILIKE '%".$search."%')
				";
			}
		}

		
		if ( ! empty($regionId))
		{
			$where .= " AND po.region_id = {$regionId}";
		}
		
		if ( ! empty($poDateFrom) && ! empty($poDateTo))
		{
			$poDateFrom = date("Y-m-d",strtotime($poDateFrom));
			$poDateTo = date("Y-m-d",strtotime($poDateTo));
			$where .= " AND (po.po_date BETWEEN '{$poDateFrom}' AND '{$poDateTo}')";
		}
			
		/*if function is work*/
		/*
		f_get_lookup_name(\'po_order_type\'::character varying, (poli.po_type)::integer) AS "po_type",
		*/
		$sql = '
			SELECT po.po_id,
			    po.po_no AS "po_no",
			    po.po_date AS "po_date",
			    poli.item AS "item",
			    poli.po_description AS "description",
			    poli.ewo_no AS "ewo_no",
			    li.link_id AS "link_id",			    
			    mreg.region_name AS "region_name",poli.progress_status,
				CASE 
				WHEN poli.progress_status = 1 THEN \'Not Start\'
				WHEN poli.progress_status = 2 THEN \'Hold\'
				WHEN poli.progress_status = 3 THEN \'On Going\'
				WHEN poli.progress_status = 4 THEN \'Done\'
				WHEN poli.progress_status = 5 THEN \'Closed\'
				END AS progress_desc,					
			    CASE
						WHEN poli.order_type = 2 THEN \'EWO\'
						WHEN poli.order_type = 1 THEN \'NBWO\'
					END AS order_type			    
				FROM (((t_trx_po_xl po
			     LEFT JOIN t_trx_po_link poli ON ((poli.po_id = po.po_id)))
			     LEFT JOIN t_mtr_site_link li ON ((li.link_id_int = poli.link_id_int)))
			     LEFT JOIN t_mtr_region_xl mreg ON ((mreg.region_id = po.region_id)))			
		';
		$sql = $sql . "
					{$where}
						ORDER BY
					{$sort} {$order}";

		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
		
		$total_rows = (int) $query->row()->total;
		
		$sql .= " LIMIT {$rows} OFFSET {$offset}";
		
		$query = $this->db->query($sql);
		
		$rows = array();
		foreach ($query->result_array() as $r)
		{
			$r['action'] = '<a href="#/detail/'.$this->core->encode($r['po_id']).'" class="btn-action glyphicons eye_open btn-success" title="Detail"><i></i></a>';
			$r['action'] .= '<a href="'.base_url("/po/edit")."/".$this->core->encode($r['po_id']).'" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>';
			$r['action'] .= '<a href="javascript:;" onclick="angular.element(document.getElementById(\'poList\')).scope().delete(\''.$this->core->encode($r['po_id']).'\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>';
			$r['po_date_format'] = "".date('d-M-Y',strtotime($r['po_date']));
			
			if($r['progress_status']==1){
				$r['progress_badge'] = "<span class='label label-warning'>".$r['progress_desc']."</span>";
			}else if($r['progress_status']==5 || $r['progress_status']==4){
				$r['progress_badge'] = "<span class='label label-sucess'>".$r['progress_desc']."</span>";
			}else{
				$r['progress_badge'] = "<span class='label label-important'>".$r['progress_desc']."</span>";
			}
			
			$rows[] = $r;
		}
		
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		
		return $data;
	}

	public function getPo($id)
	{
		$data_po = array();
		$this->db->select('po.*,reg.region_name');
		$this->db->from('t_trx_po_xl po');
		$this->db->join('t_mtr_region_xl reg', 'po.region_id = reg.region_id');
		$this->db->where('po.po_id', $id);		
		$query = $this->db->get()->row();
		$data_po["detail_po"] = $query;
		/*get Detail Po_link*/
		$where = "where poli.po_id = ".$id."";
		$sql ="SELECT 
				poli.*,po.po_no,sl.link_id, 
				sitene.site_id as \"site_id_ne\", sitene.site_name as \"site_name_ne\",
				sitefe.site_id as \"site_id_fe\", sitefe.site_name as \"site_name_fe\",
				CASE
						WHEN poli.progress_status = 1 THEN 'Not Start'
						WHEN poli.progress_status = 2 THEN 'Hold'
						WHEN poli.progress_status = 3 THEN 'On Going'
						WHEN poli.progress_status = 4 THEN 'Done'
						WHEN poli.progress_status = 5 THEN 'Closed'
				END AS progress

				FROM t_trx_po_link poli
				LEFT JOIN t_mtr_site_link sl
				ON poli.link_id_int = sl.link_id_int
				LEFT JOIN t_mtr_site_xl sitene
				ON sl.site_id_ne = sitene.site_id
				LEFT JOIN t_mtr_site_xl sitefe
				ON sl.site_id_fe = sitefe.site_id
				LEFT JOIN t_trx_po_xl po 
				ON poli.po_id = po.po_id
				{$where}
			";
		$rs_polink = $this->db->query($sql);
		$data_po["po_link"] = $rs_polink->result();
		return $data_po;
	}
	
	public function insert($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_trx_po_xl', $data);

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
	
	public function update($data, $id)
	{
		$this->db->trans_begin();

		$this->db
			->where('po_id', $id)
			->update('t_trx_po_xl', $data);

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
		/*Delete PO*/
		//start edit by hadi 20190304
		/*
		//original
		$this->db
			->where('po_id', $id)
			->update('t_trx_po_xl', $data);		
		$rs_po_link = $this->db->where("po_id",$id)->select("link_id_int")->get("t_trx_po_link")->result();
		$link_rs = array();
		foreach($rs_po_link as $item){
			array_push($link_rs, $item->link_id_int);
		}		
		/*SOFT Delete Site Link*/
		/*
		if(count($link_rs)){
			$site_link = new stdClass;
			$site_link->is_deleted = 1;
			$this->db
				->where_in('link_id_int',$link_rs)
				->update('t_mtr_site_link', $site_link);
		}
		*/
		/*
		$this->db
			->where('po_id', $id)
			->update('t_trx_po_xl', $data);
		*/	
		$this->db->where('po_id', $id);
		$this->db->delete('t_trx_po_xl');
			
		$rs_po_link = $this->db->where("po_id",$id)->select("link_id_int")->get("t_trx_po_link")->result();
		$link_rs = array();
		foreach($rs_po_link as $item){
			array_push($link_rs, $item->link_id_int);
		}		
		/*SOFT Delete Site Link*/
		if(count($link_rs)){
			$site_link = new stdClass;
			$site_link->is_deleted = 1;
			/*
			$this->db
				->where_in('link_id_int',$link_rs)
				->update('t_mtr_site_link', $site_link);
			*/
			$this->db->where_in('link_id_int',$link_rs);
			$this->db->delete('t_mtr_site_link');
		}
		
		//end edit by hadi 20190304

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}

	public function exportPo($format = 'excel')
	{
		if ($format == 'excel')
		{
			$sql = '
					SELECT po.po_id,
				    po.po_no AS "po_no",
				    po.po_date AS "po_date",
				    poli.item AS "item",
				    poli.po_description AS "description",
				    poli.ewo_no AS "ewo_no",
				    li.link_id AS "link_id",			    
				    mreg.region_name AS "region_name",
				    poli.po_type AS "project_type",					
					area_ne.area_name as "area_ne",
					area_fe.area_name as "area_fe",
					sne.site_id as "site_id_ne",
					sne.site_address as "site_address_ne",
					sne.latitude as "site_latitude_ne",
					sne.longitude as "site_longitude_ne",
					sne.site_type as "site_type_ne",
					sfe.site_id as "site_id_fe",
					sfe.site_address as "site_address_fe",
					sfe.latitude as "site_latitude_fe",
					sfe.longitude as "site_longitude_fe",
					sfe.site_type as "site_type_fe",
				    CASE
							WHEN poli.order_type = 2 THEN \'EWO\'
							WHEN poli.order_type = 1 THEN \'NBWO\'
						END AS po_type			    
					FROM (((((((t_trx_po_xl po
				     LEFT JOIN t_trx_po_link poli ON ((poli.po_id = po.po_id)))
				     LEFT JOIN t_mtr_site_link li ON ((li.link_id_int = poli.link_id_int)))
				     LEFT JOIN t_mtr_region_xl mreg ON ((mreg.region_id = po.region_id)))
						 LEFT JOIN t_mtr_site_xl sne on li.site_id_ne = sne.site_id)
						 LEFT JOIN t_mtr_site_xl sfe on li.site_id_fe = sfe.site_id)
						 LEFT JOIN t_mtr_area area_ne on sne.area_id = area_ne.area_id )
						 LEFT JOIN t_mtr_area area_fe on sfe.area_id = area_fe.area_id )
					where po.is_deleted =0 
			';	
			
		        $query = $this->db->query($sql);
                        return $query;
                        
                       
			/*$header = array("PO NO.","PO Date","Item","PO Type","Description","Region","Project Type","LINK ID",
							"EWO NO.",
							"Site ID NE","Area","Site Address NE","Latitute NE","Longitude NE","Site Type NE",
							"Site ID FE","Area","Site Address FE","Latitute FE","Longitude FE","Site Type FE",
							"");
			$exclude = array('po_no', 'po_date', 'item', 'po_type','description','region_name','project_type','link_id','ewo_no','site_id_ne', 'area_ne','site_address_ne','site_latitude_ne','site_longitude_ne','site_type_ne',
 		 					  	'site_id_fe', 'area_fe','site_address_fe','site_latitude_fe','site_longitude_fe','site_type_fe');
			$this->exportExcel($header,$query,$exclude);
			*/
                        
		}
	}
  
	
	/*Function to get PO Data from PO-NO this function should be used in import function*/
	public function getByPoNo($po_no){
		$this->db->select('po.*');
		$this->db->from('t_trx_po_xl po');
		$this->db->where("po.po_no ilike '".$po_no."'");
		$query = $this->db->get()->row();
		return $query;
	}
	/*Change Progress Status POLINK*/
	public function updatePOLIProgressStatus($po_id,$link_id,$data){
		
		$this->db
			->where('link_id_int',$link_id)
			->where('po_id',$po_id)
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

	/*@Delete PO LINK and Site LINK 
		Params : $po_id , site_ne,site_fe
	*/
	public function deletepolink($po_id,$link_id_int){
		$this->db->where('po_id', $po_id);
		$this->db->where('link_id_int', $link_id_int);
   		$this->db->delete('t_trx_po_link');    		
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
				    ->getStyle('A1:U1')
				    ->getFill()
				    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				    ->getStartColor()
				    ->setARGB('FFCCCCCC');
       
        
          //set title pada sheet (me rename nama sheet)
        $objPHPExcel->getActiveSheet()->setTitle('Export PO EATP');

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="PO_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
        //unduh file
        $objWriter->save("php://output");
	}
}