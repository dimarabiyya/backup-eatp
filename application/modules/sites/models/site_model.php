<?php

class site_model extends CI_Model {

	/*Function that return the llist of Site and Handle the Search and Filter of Site Grid*/
	public function siteList(){
		$data = array();
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'site.created_on';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));

		/*Filter by Region, Area and Site Type*/
		$region = $this->input->get('region');
		$area = $this->input->get('area');
		$site_type = $this->input->get('site_type');
		//echo $site_type;
		$where = "
			WHERE
				(site.is_deleted is NULL or site.is_deleted =0)
		";

		if ( ! empty($search))
		{
			$where .= "
				AND (site.site_name ILIKE '%".$search."%'
				OR site.site_address ILIKE '%".$search."%'
				OR mreg.region_name ILIKE '%".$search."%'
				OR lup.lookup_desc ILIKE '%".$search."%'
				OR lup.lookup_desc ILIKE '%".$search."%'
				OR area.area_name ILIKE '%".$search."%'

			";

			/*If search is float*/
			if(is_float($search) || is_numeric($search) ){
				$where .= "OR site.latitude = ".$search." OR site.longitude = ".$search."";
			}
			$where .= " OR site.site_id ILIKE '%".$search."%')";
		}
		/*check if region is not empty*/
		if ( ! empty($region))
		{
			$where .= "
				AND (site.region_id = {$region})
			";
		}
		/*check if area is not empty*/
		if ( ! empty($area))
		{
			$where .= "
				AND (site.area_id = {$area})
			";
		}
		/*check if site type is not empty*/
		if ( ! empty($site_type))
		{
			$where .= "
				AND (site.site_type = {$site_type})
			";
		}

		$sql = "
			SELECT
				site.site_id,
				site.site_name,
				site.site_address,
				site.latitude,
				site.longitude,
				site.site_type,
				site.location_id,
				lup.lookup_desc as site_type_name,
				lup.lookup_id,
				mreg.region_name, mreg.region_id,
				area.area_name, area.area_id
				FROM (((t_mtr_site_xl site
		     LEFT JOIN t_mtr_region_xl mreg ON ((mreg.region_id = site.region_id)))
				 LEFT JOIN t_mtr_lookup lup ON ((lup.lookup_id = site.site_type)))
		     LEFT JOIN t_mtr_area area ON ((area.area_id = site.area_id)))
			{$where}
			ORDER BY
			{$sort} {$order}
		";
		//echo $sql;die;
		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

		$total_rows = (int) $query->row()->total;

		$sql .= " LIMIT {$rows} OFFSET {$offset}";

		$query = $this->db->query($sql);

		$rows = array();
		foreach ($query->result_array() as $r)
		{

			$r['action'] = '<a href="#/detail/'.$this->core->encode($r['site_id']).'" class="btn-action glyphicons eye_open btn-success" title="Detail"><i></i></a>';
			$r['action'] .= '<a href="#/edit/'.$this->core->encode($r['site_id']).'" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>';
			$r['action'] .= '<a href="javascript:;" onclick="angular.element(document.getElementById(\'siteList\')).scope().delete(\''.$this->core->encode($r['site_id']).'\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>';

			$rows[] = $r;
		}

		$data['total'] = $total_rows;
		$data['rows'] = $rows;

		return $data;
	}
	/*Select by ID*/
	public function getSite($id){
		$this->db->select('site.*,area.area_name,reg.region_name,lup.lookup_desc as site_type_name');
		$this->db->from('t_mtr_site_xl site');
		$this->db->join('t_mtr_area area', 'site.area_id = area.area_id');
		$this->db->join('t_mtr_region_xl reg', 'site.region_id = reg.region_id');
		$this->db->join('t_mtr_lookup lup', 'site.site_type = lup.lookup_id','LEFT');
		$this->db->where('site.site_id', $id);
		$query = $this->db->get()->row();
		return $query;
	}
	/*Insert - $data as Parameter*/
	public function insert($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_mtr_site_xl', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}
	/*Insert - $data and $id as Parameter*/
	public function update($data, $id)
	{
		$this->db->trans_begin();

		$this->db
			->where('site_id', $id)
			->update('t_mtr_site_xl', $data);

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
			->where("site_id = '".$id."'")
			->update('t_mtr_site_xl', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}
	/*Get Site y Site ID , this is used when upload file .xls
	to check if SITE ID is exist => update, else false => create*/
	public function getSiteBySiteID($siteID){
		$this->db->select('site.*');
		$this->db->from('t_mtr_site_xl site');
		$this->db->where("site.site_id ilike '".$siteID."'");
		$query = $this->db->get()->row();
		return $query;
	}

	/*Get site by Name*/
	public function getSiteByName($site){
		$this->db->select('site.*');
		$this->db->from('t_mtr_site_xl site');
		$this->db->where("site.site_id ilike '".$site."'");
		$query = $this->db->get()->row();
		return $query;
	}

	//Export
	public function exportSite($format = 'excel')
	{
		if ($format == 'excel')
		{
			$sql = "
				SELECT
					site.site_id,
					site.site_name,
					site.site_address,
					site.latitude,
					site.longitude,
					site.site_type,
					lup.lookup_desc as site_type_name,
					lup.lookup_id,
					mreg.region_name, mreg.region_id,
					area.area_name, area.area_id
				FROM (((t_mtr_site_xl site
			    LEFT JOIN t_mtr_region_xl mreg ON ((mreg.region_id = site.region_id)))
				LEFT JOIN t_mtr_lookup lup ON ((lup.lookup_id = site.site_type)))
			    LEFT JOIN t_mtr_area area ON ((area.area_id = site.area_id)))
				WHERE (site.is_deleted IS NULL OR site.is_deleted = 0) ORDER BY site.created_on ASC
			";

			$query = $this->db->query($sql);
			$header = array('Site ID','Site Name','Address','Latitude','Longitude','Type','Region','Area');
			$exclude = array('site_id','site_name','site_address','latitude','longitude','site_type_name','region_name','area_name');
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
				    ->getStyle('A1:H1')
				    ->getFill()
				    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				    ->getStartColor()
				    ->setARGB('FFCCCCCC');
        //set title pada sheet (me rename nama sheet)
        $objPHPExcel->getActiveSheet()->setTitle('Export SITE EATP');

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="Site_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
        //unduh file
        $objWriter->save("php://output");
	}
}
