<?php

class Tower_provider extends MX_Controller {

	private $_partials = array();
	private $_upload_dir;
	
	public function __construct()
	{
		$this->load->model('site_info_model', 'site_info');
		
		$this->_upload_dir = FCPATH.'file/upload/site-info/tower-provider';
		
		if ( ! is_dir($this->_upload_dir)) {
			mkdir($this->_upload_dir, 0777);
		}
		
		$uri = $this->uri->segment(1).'/'.$this->uri->segment(2);
		$this->_partials['menu'] = $this->mmenu->getMenuId($uri);
	}
	
	public function index()
	{
		$this->_check_valid_loggedin();
		
		$data = $this->_partials;
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->get('cmd') == 'GET_DATAGRID_TP') {
			
				$json = array();
				
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$search = $this->input->get('query');
				$tp = $this->input->get('tp');
				$status = $this->input->get('status');
				$tp_region = $this->input->get('region');
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'ord';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
				
				$region = array();
				foreach ($this->session->userdata['user_region'] as $reg) {
					if ($reg['region_id'] != '') {
						$region[] = $reg['region_id'];
					}
				}
				
				$where = "WHERE tp.active = 1";
				$where .= " AND (UPPER(co.company_name) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(tp.tp_site_name) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(tp.tp_code) LIKE '%".strtoupper($search)."%'";
				//$where .= "OR UPPER(tp.status_tp_id) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(lo1.lookup_desc) LIKE '%".strtoupper($search)."%'";
				//$where .= "OR UPPER(tp.province) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(loc1.nama) LIKE '%".strtoupper($search)."%'";
				//$where .= "OR UPPER(tp.region) LIKE '%".strtoupper($search)."%'";
				//$where .= "OR UPPER(reg.region_name) LIKE '%".strtoupper($search)."%'";
				//$where .= "OR UPPER(tp.city) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(loc2.nama) LIKE '%".strtoupper($search)."%'";
				$where .= "OR CAST(tp.latitude AS VARCHAR) LIKE '%".strtoupper($search)."%'";
				$where .= "OR CAST(tp.longitude AS VARCHAR) LIKE '%".strtoupper($search)."%'";
				//$where .= "OR UPPER(tp.site_type) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(lo2.lookup_desc) LIKE '%".strtoupper($search)."%'";
				//$where .= "OR UPPER(tp.tower_type) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(lo3.lookup_desc) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(
					(CASE tp.candidate_status 
						WHEN 1 THEN 'Candidate'
						WHEN 2 THEN 'Verified'
						WHEN 3 THEN 'Selected Candidate'
						ELSE ''
					END)) LIKE '%".strtoupper($search)."%')";
				
				/* if (count($region) > 0) {
					$where .= " AND tp.region_id IN (".implode(', ', $region).")";
				} */
				if ($tp != '') {
					$where .= " AND tp.company_id = '".$tp."'";
				}
				if ($status != '') {
					$where .= " AND tp.status_tp_id = '{$status}'";
				}
				/*
				if ($tp_region != '') {
					$where .= " AND tp.region_id = {$tp_region}";
				}
				*/
				
				$sql = "
					SELECT
						tp.tp_id,
						tp.tp_code,
						co.company_name,
						tp.tp_site_name,
						loc1.nama AS province,
						reg.region_name,
						loc2.nama AS city,
						tp.latitude,
						tp.longitude,
						lo2.lookup_desc AS site_type,
						lo3.lookup_desc AS tower_type,
						lo4.lookup_desc AS site_category,
						tp.building_height_mtr,
						tp.tower_height_mtr,
						tp.shareable_status,
						-- part.partner_name,
						(CASE tp.candidate_status 
							WHEN 1 THEN 'Candidate'
							WHEN 2 THEN 'Verified'
							WHEN 3 THEN 'Selected Candidate'
							ELSE ''
						END) AS candidate_status,
						lo1.lookup_desc AS tp_status,
						CASE WHEN tp.updated_on IS NULL THEN tp.created_on ELSE tp.updated_on END AS ord
					FROM t_mtr_tower_provider tp
					-- LEFT JOIN t_mtr_partner part ON part.partner_id = tp.partner_id
					LEFT JOIN t_mtr_region reg ON reg.region_id = tp.region_id
					LEFT JOIN t_mtr_lokasi loc1 ON loc1.lokasi_id = tp.propinsi_id AND loc1.jenis = 'P'
					LEFT JOIN t_mtr_lokasi loc2 ON loc2.lokasi_id = tp.city_id AND loc2.jenis = 'KB'
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(tp.status_tp_id AS VARCHAR) AND lo1.lookup_key = 'tower_status'
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value  = CAST(tp.site_type_id AS VARCHAR) AND lo2.lookup_key = 'site_type'
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value  = CAST(tp.tower_type_id AS VARCHAR) AND lo3.lookup_key = 'tower_type'
					LEFT JOIN t_mtr_lookup lo4 ON lo4.lookup_value  = CAST(tp.site_category AS VARCHAR) AND lo4.lookup_key = 'tp_category'
					LEFT JOIN t_mtr_tp_company co ON co.company_id = tp.company_id
					{$where}
					ORDER BY  {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$results = $query->result_array();
				$rows = array();
				foreach ($results as $r) {
					$r['tp_id'] = $this->core->encode($r['tp_id']);
					$rows[] = $r;
				}
				
				$json['total'] = $total_rows;
				$json['rows'] = $rows;
				
				//$res = $this->db->query("SELECT COUNT(*) AS total_tp FROM (SELECT DISTINCT tp_company_name FROM t_mtr_tower_provider WHERE active = 1) as cnt")->row_array();
				$res = $this->db->query("SELECT COUNT(*) AS total_tp FROM t_mtr_tp_company")->row_array();
				$json['total_tp'] = $res['total_tp'];
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'DELETE_TP') {
			
				$json = array();
				
				$id = (int) $this->core->decode($this->input->get('id'));
				
				$sql = "UPDATE t_mtr_tower_provider SET active = 0 WHERE tp_id = {$id}";
				$this->db->query($sql);
				
				$json = array('error' => FALSE, 'message' => 'Plan data deleted successfully.');
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_CITY') {
			
				$propinsi_id = (int) $this->input->get('propinsi_id');
				
				$json = array();
				
				$kabupaten = $this->core->view_all('t_mtr_lokasi', array('jenis' => 'KB', 'parent_id' => $propinsi_id), 'nama');
				foreach ($kabupaten as $kab) {
					$json[] = array('id' => $kab['lokasi_id'], 'name' => $kab['nama']);
				}
				
				echo json_encode($json);
				exit;
			}
		}
		
		$data['content_id'] = 'tower_provider_list';
		
		$this->load->view('template/smashing', $data);
	}
	
	public function add()
	{
		$this->_check_valid_loggedin();
		
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_add')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->post('cmd') == 'ADD_TP') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($post['siteCategory'] == 0) {
					$error[] = 'Site Category can not unselected.';
				}
				/*
				if ( $post['tpCode'] != '' && $this->core->view_row('t_mtr_tower_provider', array('tp_code' => $post['tpCode'])) ) {
					$error[] = 'TP Code has already in used.';
				}
				*/
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					$this->core->set['company_id'] = $post['tpName'];
					$this->core->set['tp_code'] = $post['tpCode'];
					$this->core->set['tp_site_name'] = $post['tpSiteName'];
					$this->core->set['latitude'] = (float) $post['latitude'];
					$this->core->set['longitude'] = (float) $post['longitude'];
					$this->core->set['status_tp_id'] = $post['statusTP'];
					$this->core->set['site_type_id'] = $post['siteType'];
					$this->core->set['tower_type_id'] = $post['towerType'];
					$this->core->set['site_category'] = $post['siteCategory'];
					$this->core->set['building_height_mtr'] = $post['buildingHeight'];
					$this->core->set['tower_height_mtr'] = $post['towerHeight'];
					$this->core->set['propinsi_id'] = $post['propinsi'];
					$this->core->set['city_id'] = $post['city'];
					//$this->core->set['region_id'] = $post['region'];
					$this->core->set['address'] = $post['address'];
					$this->core->set['shareable_status'] = $post['shareableStatus'];
					//$this->core->set['partner_id'] = $post['partner'];
					$this->core->set['remark'] = $post['remarks'];
					$this->core->set['active'] = 1;
					
					$this->core->set['created_on'] = $created_on;
					$this->core->set['created_by'] = $created_by;
					$this->core->set['updated_on'] = $updated_on;
					$this->core->set['updated_by'] = $updated_by;
					
					$this->core->save('t_mtr_tower_provider');
					
					$tp_id = $this->db->insert_id();
					
					$json = array('error' => FALSE, 'message' => 'Tower Provider has been added successfully.', 'id' => $this->core->encode($tp_id));
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		}
		
		$this->_tp_form();
	}
	
	public function edit($tp_id = 0)
	{
		$this->_check_valid_loggedin();
		
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_edit')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		if (empty($tp_id)) {
			show_404();
		}
		
		$tp_id = (int) $this->core->decode($tp_id);
		
		if ( ! $this->core->view_row('t_mtr_tower_provider', array('tp_id' => $tp_id)) ) {
			show_404();
		}
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->post('cmd') == 'EDIT_TP') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($post['siteCategory'] == 0) {
					$error[] = 'Site Category can not unselected.';
				}
				/*
				if ( $post['tpCode'] != '' && $this->core->view_row('t_mtr_tower_provider', array('tp_code' => $post['tpCode'], 'tp_id !=' => $tp_id)) ) {
					$error[] = 'TP Code has already in used.';
				}
				*/
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					$this->core->set['company_id'] = $post['tpName'];
					$this->core->set['tp_code'] = $post['tpCode'];
					$this->core->set['tp_site_name'] = $post['tpSiteName'];
					$this->core->set['latitude'] = (float) $post['latitude'];
					$this->core->set['longitude'] = (float) $post['longitude'];
					$this->core->set['status_tp_id'] = $post['statusTP'];
					$this->core->set['site_type_id'] = $post['siteType'];
					$this->core->set['tower_type_id'] = $post['towerType'];
					$this->core->set['site_category'] = $post['siteCategory'];
					$this->core->set['building_height_mtr'] = $post['buildingHeight'];
					$this->core->set['tower_height_mtr'] = $post['towerHeight'];
					$this->core->set['propinsi_id'] = $post['propinsi'];
					$this->core->set['city_id'] = $post['city'];
					//$this->core->set['region_id'] = $post['region'];
					$this->core->set['address'] = $post['address'];
					$this->core->set['shareable_status'] = $post['shareableStatus'];
					//$this->core->set['partner_id'] = $post['partner'];
					$this->core->set['remark'] = $post['remarks'];
					$this->core->set['active'] = 1;
					
					$this->core->set['updated_on'] = $updated_on;
					$this->core->set['updated_by'] = $updated_by;
					
					//$this->core->save('t_mtr_tower_provider', array('tp_id' => $tp_id));
					$data = $this->core->set;
					$data['tp_id'] = $tp_id;
					$this->site_info->save_data('t_mtr_tower_provider', $data, 'tp_id');
					
					// edit lat-lng master site
					$this->core->update('t_mtr_site', array('latitude' => (float) $post['latitude'], 'longitude' => (float) $post['longitude']), array('tp_id' => (string) $tp_id));
					
					$json = array('error' => FALSE, 'message' => 'Tower Provider has been updated successfully.', 'id' => $this->core->encode($tp_id));
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		}
		
		$this->_tp_form($tp_id);
	}
	
	public function view($tp_id = 0)
	{
		$this->_check_valid_loggedin();
		
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_view')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$tp_id = (int) $this->core->decode($tp_id);
		
		if ( ! $this->core->view_row('t_mtr_tower_provider', array('tp_id' => $tp_id)) ) {
			show_404();
		}
		
		$data['tp'] = $this->core->view_row('t_mtr_tower_provider', array('tp_id' => $tp_id));
		
		$data['content_id'] = 'tower_provider_view';
		
		$this->load->view('template/smashing', $data);
	}
	
	private function _tp_form($id = 0)
	{
		if ($this->uri->segment(3) == 'edit') {
			$data['tp'] = $this->core->view_row('t_mtr_tower_provider', array('tp_id' => $id));
		}
		
		$data['content_id'] = 'tower_provider_form';
		
		$this->load->view('template/smashing', $data);
	}
	
	private function _check_valid_loggedin()
	{
		if ( ! $this->session->userdata('is_login')) {
			
			if ($this->core->is_ajax()) {
				
				echo json_encode(array(
					'loggedin' => FALSE,
					'message' => 'You are not logged in. You will be redirected to login page.',
					'redirect_url' => site_url('/login')
				));
				exit;
			} else {
				redirect('/login');
			}
		}
	}

	public function import_old()
	{
		if ($this->input->post('cmd') == 'upload') {
		
			$json = array();
			
			$allowed = array('.xls', '.xlsx');
			
			$upload_key = array_keys($_FILES);
			
			if ( ! empty($_FILES[$upload_key[0]]['name']) && $_FILES[$upload_key[0]]['error'] == UPLOAD_ERR_OK) {
				$tmp_name = $_FILES[$upload_key[0]]['tmp_name'];
				$ext = strtolower(strrchr($_FILES[$upload_key[0]]['name'], '.'));
				$file_name = $_FILES[$upload_key[0]]['name'];
				
				if ( ! in_array($ext, $allowed)) {
					$json = array('error' => TRUE, 'message' => 'Invalid file type. Please upload file only type: .xls, .xlsx');
				} else {
					if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_upload_dir.'/'.$file_name)) {
						
						$json = array('error' => FALSE, 'message' => 'File uploaded.');
					}
				}
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'check_validation') {
		
			$json = array();
			$file = $this->_upload_dir.'/'.$this->input->post('file');
			
			$this->load->library('PHPExcel');
            $this->load->library('PHPExcel/IOFactory');
			
			$objPHPExcel = new PHPExcel();
			
			try {
				$objReader = IOFactory::createReader('Excel5');
				$objPHPExcel = $objReader->load($file);
			} catch (Exception $e) {
				$objReader = IOFactory::createReader('Excel2007');
				$objPHPExcel = $objReader->load($file);
			}
			
			$error = array();
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			
			$total_rows = 0;
			//$rows = array();
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$i = 0;
				foreach ($worksheet->getRowIterator() as $row) {
					if ($i != 0) {
						$cellIterator = $row->getCellIterator();
						$cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
						$j = 0;
						foreach ($cellIterator as $cell) {
							if ( ! is_null($cell)) {
								if ($j == 0) { // TP ID
									$res = $this->db->query("SELECT company_id FROM t_mtr_tp_company WHERE upper(company_name) = '".strtoupper(trim($cell->getValue()))."'")->row_array();
									if ( ! isset($res['company_id'])) {
										$error[] = array('row' => ($i + 1), 'column' => chr(65), 'cause' => 'TP Name is not registered.');
									}
								}
								
								if ($j == 3) { // Site Category
									$res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE lookup_key = 'tp_category' AND active = 1 AND upper(lookup_desc) = '".trim(strtoupper($cell->getValue()))."'")->row_array();
									if ( ! $res) {
										$error[] = array('row' => ($i + 1), 'column' => chr(65 + 3), 'cause' => 'Site Category is not registered.');
									}
								}
								
								if ($j == 10) { // Status TP
									$res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE lookup_key = 'tower_status' AND active = 1 AND upper(lookup_desc) = '".trim(strtoupper($cell->getValue()))."'")->row_array();
									if ( ! $res) {
										$error[] = array('row' => ($i + 1), 'column' => chr(65 + 10), 'cause' => 'Status TP is not registered.');
									}
								}
								
								if ($j == 11) { // Site Type
									$res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE lookup_key = 'site_type' AND active = 1 AND upper(lookup_desc) = '".trim(strtoupper($cell->getValue()))."'")->row_array();
									if ( ! $res) {
										$error[] = array('row' => ($i + 1), 'column' => chr(65 + 11), 'cause' => 'Site Type is not registered.');
									}
								}
								
								if ($j == 12) { // Tower Type
									$res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE lookup_key = 'site_type' AND active = 1 AND upper(lookup_desc) = '".trim(strtoupper($cell->getValue()))."'")->row_array();
									if ( ! $res) {
										$error[] = array('row' => ($i + 1), 'column' => chr(65 + 12), 'cause' => 'Tower Type is not registered.');
									}
								}
								
								if ($j == 13) { // Partner
									$res = $this->db->query("SELECT partner_id FROM t_mtr_partner WHERE status = 1 AND upper(partner_name) = '".trim(strtoupper($cell->getValue()))."'")->row_array();
									if ( ! $res) {
										$error[] = array('row' => ($i + 1), 'column' => chr(65 + 13), 'cause' => 'Partner is not registered.');
									}
								}
								
								if ($j == 14) { // Region
									$res = $this->db->query("SELECT region_id FROM t_mtr_region WHERE status = 1 AND upper(region_name) = '".trim(strtoupper($cell->getValue()))."'")->row_array();
									if ( ! $res) {
										$error[] = array('row' => ($i + 1), 'column' => chr(65 + 14), 'cause' => 'Region is not registered.');
									}
								}
								
								if ($j == 15) { // Province
									$res = $this->db->query("SELECT lokasi_id FROM t_mtr_lokasi WHERE status = 1 AND jenis = 'P' AND upper(nama) = '".trim(strtoupper($cell->getValue()))."'")->row_array();
									if ( ! $res) {
										$error[] = array('row' => ($i + 1), 'column' => chr(65 + 15), 'cause' => 'Province is not registered.');
									}
								}
								
								if ($j == 16) { // City
									$res = $this->db->query("SELECT lokasi_id FROM t_mtr_lokasi WHERE status = 1 AND jenis = 'KB' AND upper(nama) = '".trim(strtoupper($cell->getValue()))."'")->row_array();
									if ( ! $res) {
										$error[] = array('row' => ($i + 1), 'column' => chr(65 + 16), 'cause' => 'City is not registered.');
									}
								}
							}
							
							$j++;
						}
						
						$total_rows = $i;
					}
					
					$i++;
				}
				
			}
			
			if (count($error) > 0) {
				@unlink($file);
				
				$json = array('error' => TRUE, 'causes' => $error, 'message' => 'Import failed. Please correct it and import again.', 'total_rows' => $total_rows);
			} else {
				$json = array('error' => FALSE);
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'import') {
		
			$json = array();
			$file = $this->_upload_dir.'/'.$this->input->post('file');
			
			$this->load->library('PHPExcel');
            $this->load->library('PHPExcel/IOFactory');
			
			$objPHPExcel = new PHPExcel();
			
			try {
				$objReader = IOFactory::createReader('Excel5');
				$objPHPExcel = $objReader->load($file);
			} catch (Exception $e) {
				$objReader = IOFactory::createReader('Excel2007');
				$objPHPExcel = $objReader->load($file);
			}
			
			$rows = array();
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$i = 0;
				foreach ($worksheet->getRowIterator() as $row) {
					if ($i != 0) {
						$cellIterator = $row->getCellIterator();
						$cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
						$j = 0;
						foreach ($cellIterator as $cell) {
							if ( ! is_null($cell)) {
								//$rows[$i][$j]=$cell->getFormattedValue();
								$rows[$i][$j] = $cell->getValue();
							} else {
								$rows[$i][$j] = 0;
							}
							$j++;
						}
					}
					$i++;
				}
			}
			
			$date = date('Y-m-d H:i:s');
			$by = $this->session->userdata('user_id');
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			
			$this->core->db_debug = TRUE;
			
			foreach ($rows as $row) {
				//$PHPTimeStamp = PHPExcel_Shared_Date::ExcelToPHP($row[6]);
				//$this->core->set['tanggal_bayar'] = date('Y-m-d', $PHPTimeStamp);
				
				$res = $this->db->query("SELECT company_id FROM t_mtr_tp_company WHERE upper(company_name) = '".strtoupper(trim($row[0]))."'")->row_array();
				$tp_name = $res['company_id'];

				$res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE lookup_key = 'tp_category' AND active = 1 AND upper(lookup_desc) = '".trim(strtoupper($row[3]))."'")->row_array();
				$site_category = $res['lookup_value'];

				$res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE lookup_key = 'tower_status' AND active = 1 AND upper(lookup_desc) = '".trim(strtoupper($row[10]))."'")->row_array();
				$status_tp = $res['lookup_value'];

				$res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE lookup_key = 'site_type' AND active = 1 AND upper(lookup_desc) = '".trim(strtoupper($row[11]))."'")->row_array();
				$site_type = $res['lookup_value'];

				$res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE lookup_key = 'site_type' AND active = 1 AND upper(lookup_desc) = '".trim(strtoupper($row[12]))."'")->row_array();
				$tower_type = $res['lookup_value'];

				$res = $this->db->query("SELECT partner_id FROM t_mtr_partner WHERE status = 1 AND upper(partner_name) = '".trim(strtoupper($row[13]))."'")->row_array();
				$partner = $res['partner_id'];

				$res = $this->db->query("SELECT region_id FROM t_mtr_region WHERE status = 1 AND upper(region_name) = '".trim(strtoupper($row[14]))."'")->row_array();
				$region = $res['region_id'];

				$res = $this->db->query("SELECT lokasi_id FROM t_mtr_lokasi WHERE status = 1 AND jenis = 'P' AND upper(nama) = '".trim(strtoupper($row[15]))."'")->row_array();
				$province = $res['lokasi_id'];

				$res = $this->db->query("SELECT lokasi_id FROM t_mtr_lokasi WHERE status = 1 AND jenis = 'KB' AND upper(nama) = '".trim(strtoupper($row[16]))."'")->row_array();
				$city = $res['lokasi_id'];
				
				$this->core->set['company_id'] = $tp_name;
				$this->core->set['tp_code'] = $row[1];
				$this->core->set['tp_site_name'] = $row[2];
				$this->core->set['site_category'] = $site_category;
				$this->core->set['latitude'] = $row[4];
				$this->core->set['longitude'] = $row[5];
				$this->core->set['building_height_mtr'] = $row[6];
				$this->core->set['tower_height_mtr'] = $row[7];
				$this->core->set['address'] = $row[8];
				$this->core->set['shareable_status'] = $row[9];
				$this->core->set['status_tp_id'] = $status_tp;
				$this->core->set['site_type_id'] = $site_type;
				$this->core->set['tower_type_id'] = $tower_type;
				$this->core->set['partner_id'] = $partner;
				$this->core->set['region_id'] = $region;
				$this->core->set['propinsi_id'] = $province;
				$this->core->set['city_id'] = $city;
				$this->core->set['remark'] = $row[17];
				$this->core->set['active'] = 1;
				
				$res = $this->db->query("SELECT tp_code FROM t_mtr_tower_provider WHERE active = 1 AND upper(tp_code) = '".strtoupper(@$row[1])."'")->row_array();
				if ( ! $res) {
				
					$this->core->set['created_on'] = $date;
					$this->core->set['created_by'] = $by;
					$this->core->set['updated_on'] = $date;
					$this->core->set['updated_by'] = $by;
					
					$this->core->save('t_mtr_tower_provider');
				} else {
					$this->core->set['updated_on'] = $date;
					$this->core->set['updated_by'] = $by;
					
					$this->core->save('t_mtr_tower_provider', array('upper(tp_code)' => strtoupper(@$row[1])));
				}
			}
			
			@unlink($file);
			
			$json = array('error' => FALSE, 'message' => 'Data has been imported successfully.');
			
			echo json_encode($json);
			exit;
		}
		
		$data['content_id'] = 'tower_provider_import';
		
		$this->load->view('template/smashing', $data);
	}

	public function import()
	{
		if ($this->input->post('cmd') == 'upload') {
		
			ini_set('memory_limit', '-1');
			set_time_limit(0);
			
			$json = array();
			
			$tmp_table_import = 'public.tmp_tower_provider_'.date('Ymd_His', time());
			$allowed = array('.xls', '.xlsx');
			
			$upload_key = array_keys($_FILES);
			
			if ( ! empty($_FILES[$upload_key[0]]['name']) && $_FILES[$upload_key[0]]['error'] == UPLOAD_ERR_OK) {
				$tmp_name = $_FILES[$upload_key[0]]['tmp_name'];
				$ext = strtolower(strrchr($_FILES[$upload_key[0]]['name'], '.'));
				//$file_name = $_FILES[$upload_key[0]]['name'];
				$file_name = 'tmp_tower_provider_'.date('Ymd_His', time()).$ext;
				
				if ( ! in_array($ext, $allowed)) {
					$json = array('error' => TRUE, 'message' => 'Invalid file type. Please upload file only type: .xls, .xlsx');
				} else {
					if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_upload_dir.'/'.$file_name)) {
						
						$sql = "
							CREATE TABLE {$tmp_table_import} (
								row_number INT8,
								tp_name VARCHAR(200),
								tp_code VARCHAR(100),
								tp_site_name VARCHAR(200),
								site_category VARCHAR(100),
								latitude FLOAT8,
								longitude FLOAT8,
								building_height VARCHAR(100),
								tower_height VARCHAR(100),
								address TEXT,
								shareable_status VARCHAR(200),
								status_tp VARCHAR(100),
								site_type VARCHAR(100),
								tower_type VARCHAR(100),
								-- partner VARCHAR(200),
								-- region VARCHAR(100),
								province VARCHAR(200),
								city VARCHAR(200),
								remarks TEXT
							)
						";
						
						$this->db->query($sql);
						
						require_once dirname(__FILE__).'/../../../libraries/SpreadsheetReader/php-excel-reader/excel_reader2.php';
						require_once dirname(__FILE__).'/../../../libraries/SpreadsheetReader/SpreadsheetReader.php';
					
						$reader = new SpreadsheetReader($this->_upload_dir.'/'.$file_name);
						
						$this->core->db_debug = TRUE;
						
						$i = 0;
						foreach ($reader as $row) {
							if ($i > 0) {
							
								$this->core->set['row_number'] = $i;
								$this->core->set['tp_name'] = utf8_encode($row[0]);
								$this->core->set['tp_code'] = str_replace("'", "", utf8_encode($row[1]));
								$this->core->set['tp_site_name'] = utf8_encode($row[2]);
								$this->core->set['site_category'] = utf8_encode($row[3]);
								$this->core->set['latitude'] = (float) $row[4];
								$this->core->set['longitude'] = (float) $row[5];
								$this->core->set['building_height'] = utf8_encode($row[6]);
								$this->core->set['tower_height'] = utf8_encode($row[7]);
								$this->core->set['address'] = utf8_encode($row[8]);
								$this->core->set['shareable_status'] = utf8_encode($row[9]);
								$this->core->set['status_tp'] = utf8_encode($row[10]);
								$this->core->set['site_type'] = utf8_encode($row[11]);
								$this->core->set['tower_type'] = utf8_encode($row[12]);
								$this->core->set['province'] = utf8_encode($row[13]);
								$this->core->set['city'] = utf8_encode($row[14]);
								$this->core->set['remarks'] = utf8_encode($row[15]);
								
								$this->core->save($tmp_table_import);
							
							}
						
							$i++;
						}
						
						$json = array('error' => FALSE, 'message' => 'File uploaded.', 'fileName' => $file_name, 'tmp_table_import' => $tmp_table_import);
					}
				}
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'import') {
		
			ini_set('memory_limit', '-1');
			set_time_limit(0);
			
			$tmp_table_import = $this->input->post('tmp_table_import');
			$file = $this->input->post('file');
			
			$json = array();
			$error = array();
			
			$rows = $this->db
				->query("
					SELECT
						tp.row_number,
						tp.tp_name AS tp_name_text,
						-- t_mtr_tp_company.company_id AS tp_name,
						CASE WHEN (SELECT count(*) FROM t_mtr_tp_company WHERE upper(company_name) = upper(tp.tp_name)) = 0 THEN 0 ELSE t_mtr_tp_company.company_id END AS tp_name,
						tp.tp_code,
						tp.tp_site_name,
						-- tp.site_category,
						lo1.lookup_value AS site_category,
						-- CASE WHEN (SELECT count(*) FROM t_mtr_lookup WHERE upper(lookup_desc) = upper(tp.site_category) AND lookup_key = 'tp_category' AND active = 1) = 0 THEN 0 ELSE lo1.lookup_value::INTEGER END AS site_category,
						tp.latitude,
						tp.longitude,
						tp.building_height,
						tp.tower_height,
						tp.address,
						tp.shareable_status,
						-- tp.status_tp,
						lo2.lookup_value AS status_tp,
						-- CASE WHEN (SELECT count(*) FROM t_mtr_lookup WHERE trim(upper(lookup_desc)) = trim(upper(tp.status_tp)) AND lookup_key = 'tower_status' AND active = 1) = 0 THEN 0 ELSE lo2.lookup_value::INTEGER END AS status_tp,
						-- tp.site_type,
						lo3.lookup_value AS site_type,
						-- CASE WHEN (SELECT count(*) FROM t_mtr_lookup WHERE trim(upper(lookup_desc)) = trim(upper(tp.site_type)) AND lookup_key = 'site_type' AND active = 1) = 0 THEN 0 ELSE lo3.lookup_value::INTEGER END AS site_type,
						-- tp.tower_type,
						lo4.lookup_value AS tower_type,
						-- CASE WHEN (SELECT count(*) FROM t_mtr_lookup WHERE trim(upper(lookup_desc)) = trim(upper(tp.tower_type)) AND lookup_key = 'site_type' AND active = 1) = 0 THEN 0 ELSE lo4.lookup_value::INTEGER END AS tower_type,
						-- tp.partner,
						-- t_mtr_partner.partner_id AS partner,
						-- CASE WHEN (SELECT count(*) FROM t_mtr_partner WHERE trim(upper(partner_name)) = trim(upper(tp.partner)) AND status = 1) = 0 THEN 0 ELSE t_mtr_partner.partner_id END AS partner,
						-- tp.region,
						-- t_mtr_region.region_id AS region,
						-- CASE WHEN (SELECT count(*) FROM t_mtr_region WHERE trim(upper(region_name)) = trim(upper(tp.region)) AND status = 1) = 0 THEN 0 ELSE t_mtr_region.region_id END AS region,
						-- tp.province,
						location1.lokasi_id AS province,
						-- CASE WHEN (SELECT count(*) FROM t_mtr_lokasi WHERE trim(upper(nama)) = trim(upper(tp.province)) AND jenis = 'P' AND status = 1) = 0 THEN 0 ELSE location1.lokasi_id END AS province,
						-- tp.city,
						location2.lokasi_id AS city,
						-- CASE WHEN (SELECT count(*) FROM t_mtr_lokasi WHERE trim(upper(nama)) = trim(upper(tp.city)) AND jenis = 'KB' AND status = 1) = 0 THEN 0 ELSE location2.lokasi_id END AS city,
						tp.remarks
					FROM {$tmp_table_import} tp
					LEFT JOIN t_mtr_tp_company ON trim(upper(t_mtr_tp_company.company_name)) = trim(upper(tp.tp_name)) AND tp.tp_name IS NOT NULL
					LEFT JOIN t_mtr_lookup lo1 ON trim(upper(lo1.lookup_desc)) = trim(upper(tp.site_category)) AND lo1.lookup_key = 'tp_category' AND lo1.active = 1
					LEFT JOIN t_mtr_lookup lo2 ON trim(upper(lo2.lookup_desc)) = trim(upper(tp.status_tp)) AND lo2.lookup_key = 'tower_status' AND lo2.active = 1
					LEFT JOIN t_mtr_lookup lo3 ON trim(upper(lo3.lookup_desc)) = trim(upper(tp.site_type)) AND lo3.lookup_key = 'site_type' AND lo3.active = 1
					LEFT JOIN t_mtr_lookup lo4 ON trim(upper(lo4.lookup_desc)) = trim(upper(tp.tower_type)) AND lo4.lookup_key = 'site_type' AND lo4.active = 1
					-- LEFT JOIN t_mtr_partner ON trim(upper(t_mtr_partner.partner_name)) = trim(upper(tp.partner)) AND t_mtr_partner.status = 1
					-- LEFT JOIN t_mtr_region ON trim(upper(t_mtr_region.region_name)) = trim(upper(tp.region)) AND t_mtr_region.status = 1
					LEFT JOIN t_mtr_lokasi location1 ON trim(upper(location1.nama)) = trim(upper(tp.province)) AND location1.jenis = 'P' AND location1.status = 1
					LEFT JOIN t_mtr_lokasi location2 ON trim(upper(location2.nama)) = trim(upper(tp.city)) AND location2.jenis = 'KB' AND location2.status = 1
					ORDER BY tp.row_number ASC
				")
				->result_array();
			
			$this->core->db_debug = TRUE;
			
			$succeed = 0;
			foreach ($rows as $row) {
				$inserted = TRUE;
				
				if ($row['tp_name'] == 0) {
					$error[] = array('row' => ($row['row_number'] + 1), 'column' => chr(65),  'err' => 'TP Name "'.$row['tp_name_text'].'" is not registered.');
					$inserted = FALSE;
				}
				if ($row['tp_code'] == '') {
					$error[] = array('row' => ($row['row_number'] + 1), 'column' => chr(65 + 1),  'err' => 'TP ID can not empty.');
					$inserted = FALSE;
				}
				if ($row['latitude'] == '') {
					$error[] = array('row' => ($row['row_number'] + 1), 'column' => chr(65 + 4),  'err' => 'Latitude can not empty.');
					$inserted = FALSE;
				}
				if ($row['longitude'] == '') {
					$error[] = array('row' => ($row['row_number'] + 1), 'column' => chr(65 + 5),  'err' => 'Longitude can not empty.');
					$inserted = FALSE;
				}
				
				/*
				if ($row['site_category'] == 0) {
					$error[] = array('row' => $row['row_number'], 'column' => chr(65 + 3), 'err' => 'Site Category is not registered.');
					$inserted = FALSE;
				}
				if ($row['status_tp'] == 0) {
					$error[] = array('row' => $row['row_number'], 'column' => chr(65 + 10),  'err' => 'Status TP is not registered.');
					$inserted = FALSE;
				}
				if ($row['site_type'] == 0) {
					$error[] = array('row' => $row['row_number'], 'column' => chr(65 + 11),  'err' => 'Site Type is not registered.');
					$inserted = FALSE;
				}
				if ($row['tower_type'] == 0) {
					$error[] = array('row' => $row['row_number'], 'column' => chr(65 + 12),  'err' => 'Tower Type is not registered.');
					$inserted = FALSE;
				}
				if ($row['partner'] == 0) {
					$error[] = array('row' => $row['row_number'], 'column' => chr(65 + 13),  'err' => 'Partner is not registered.');
					$inserted = FALSE;
				}
				if ($row['region'] == 0) {
					$error[] = array('row' => $row['row_number'], 'column' => chr(65 + 14),  'err' => 'Region is not registered.');
					$inserted = FALSE;
				}
				if ($row['province'] == 0) {
					$error[] = array('row' => $row['row_number'], 'column' => chr(65 + 15),  'err' => 'Province is not registered.');
					$inserted = FALSE;
				}
				if ($row['city'] == 0) {
					$error[] = array('row' => $row['row_number'], 'column' => chr(65 + 16),  'err' => 'City is not registered.');
					$inserted = FALSE;
				}
				*/
				
				if ($inserted) {
					if ( ! $this->core->view_row('t_mtr_tower_provider', array('tp_code' => $row['tp_code'])) ) {
						$this->core->set['company_id'] = $row['tp_name'];
						$this->core->set['tp_code'] = $row['tp_code'];
						$this->core->set['tp_site_name'] = utf8_encode($row['tp_site_name']);
						$this->core->set['site_category'] = $row['site_category'];
						$this->core->set['latitude'] = $row['latitude'];
						$this->core->set['longitude'] = $row['longitude'];
						$this->core->set['building_height_mtr'] = $row['building_height'];
						$this->core->set['tower_height_mtr'] = $row['tower_height'];
						$this->core->set['address'] = utf8_encode($row['address']);
						$this->core->set['shareable_status'] = $row['shareable_status'];
						$this->core->set['status_tp_id'] = null_empty($row['status_tp']);
						$this->core->set['site_type_id'] = null_empty($row['site_type']);
						$this->core->set['tower_type_id'] = null_empty($row['tower_type']);
						$this->core->set['propinsi_id'] = $row['province'];
						$this->core->set['city_id'] = $row['city'];
						$this->core->set['remark'] = $row['remarks'];
						$this->core->set['created_on'] = date('Y-m-d H:i:s');
						$this->core->set['created_by'] = 'importer';
						$this->core->set['active'] = 1;
						
						$this->core->save('t_mtr_tower_provider');
						
						$succeed++;
					
					} else {
						$this->core->set['company_id'] = $row['tp_name'];
						$this->core->set['tp_code'] = $row['tp_code'];
						$this->core->set['tp_site_name'] = utf8_encode($row['tp_site_name']);
						$this->core->set['site_category'] = $row['site_category'];
						$this->core->set['latitude'] = $row['latitude'];
						$this->core->set['longitude'] = $row['longitude'];
						$this->core->set['building_height_mtr'] = $row['building_height'];
						$this->core->set['tower_height_mtr'] = $row['tower_height'];
						$this->core->set['address'] = utf8_encode($row['address']);
						$this->core->set['shareable_status'] = $row['shareable_status'];
						$this->core->set['status_tp_id'] = null_empty($row['status_tp']);
						$this->core->set['site_type_id'] = null_empty($row['site_type']);
						$this->core->set['tower_type_id'] = null_empty($row['tower_type']);
						$this->core->set['propinsi_id'] = $row['province'];
						$this->core->set['city_id'] = $row['city'];
						$this->core->set['remark'] = $row['remarks'];
						$this->core->set['updated_on'] = date('Y-m-d H:i:s');
						$this->core->set['updated_by'] = 'importer';
						$this->core->set['active'] = 1;
						
						$this->core->save('t_mtr_tower_provider', array('tp_code' => $row['tp_code']));
						
						$succeed++;
					}
				
				} else {
					// ...
				}
			}
			
			$this->db->query("DROP TABLE {$tmp_table_import}");
			
			@unlink($this->_upload_dir.'/'.$file);
			
			$json = array('error' => $error, 'message' => $succeed.' row(s) has been imported and '.count($error).' error(s).', 'total_rows' => count($rows));
			
			echo json_encode($json);
			exit;
		}
		
		$data['content_id'] = 'tower_provider_import';
		
		$this->load->view('template/smashing', $data);
	}
	
	private function _read_excel_file($file)
	{
		error_reporting(E_ALL);
		set_time_limit(0);
		
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		
		$objPHPExcel = new PHPExcel();
		
		try {
			$objReader = IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load($file);
		} catch (Exception $e) {
			$objReader = IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($file);
		}
		
		$rows = array();
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$i = 0;
			foreach ($worksheet->getRowIterator() as $row) {
				if ($i != 0) {
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
					$j = 0;
					foreach ($cellIterator as $cell) {
						if ( ! is_null($cell)) {
							//$rows[$i][$j]=$cell->getFormattedValue();
							$rows[$i][$j] = $cell->getValue();
						} else {
							$rows[$i][$j] = 0;
						}
						$j++;
					}
				}
				$i++;
			}
		}
		
		return $rows;
	}

	public function foo()
	{
		require_once dirname(__FILE__).'/../../../libraries/SpreadsheetReader/php-excel-reader/excel_reader2.php';
		require_once dirname(__FILE__).'/../../../libraries/SpreadsheetReader/SpreadsheetReader.php';
	
		$Reader = new SpreadsheetReader('D:/NUTECH/SMARTFREN/TODO/Phase 1.b/MASTER_SITES/tp_template_test.xlsx');
		foreach ($Reader as $Row)
		{
			print_rr($Row);
		}
	}
}