<?php

class Bts_optimization extends MX_Controller {

	private $_bts_optimization_dir;
	private $_bts_optimization_cache_dir;
	private $_bts_optimization_url;
	private $_partials = array();
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('parser');
		
		$this->load->model('image_model', 'image_model');
		$this->load->model('bts_optimization_model', 'bts_optimization_model');
		$this->load->model('site_info_model', 'site_info');
		
		$siteinfo_dir = FCPATH.'file/upload/site-info';		
		if ( ! is_dir($siteinfo_dir)) {
			mkdir($siteinfo_dir, 0777);
		}
		
		$this->_bts_optimization_dir = FCPATH.'file/upload/site-info/bts-optimization';
		if ( ! is_dir($this->_bts_optimization_dir)) {
			mkdir($this->_bts_optimization_dir, 0777);
		}
		
		$this->_bts_optimization_cache_dir = FCPATH.'file/upload/cache/site-info/bts-optimization';
		$this->_bts_optimization_url = base_url('/file/upload/site-info/bts-optimization');
		
		$this->_partials['bts_optimization_dir'] = $this->_bts_optimization_dir;
		$this->_partials['bts_optimization_url'] = $this->_bts_optimization_url;
		
		$uri = $this->uri->segment(1).'/'.$this->uri->segment(2);
		$this->_partials['menu'] = $this->mmenu->getMenuId($uri);
	}
	
	public function index()
	{
		$this->_check_valid_loggedin();
		
		$data = $this->_partials;
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->get('cmd') == 'LOAD_DATA') {
			
				$json = array();
				
				$data = $this->bts_optimization_model->load_bts_optimization_list();
				
				$json = $data;
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'DELETE_VISIT') {
			
				$json = array();
				$visit_id = (int) $this->core->decode($this->input->get('visit_id'));
				
				$this->core->db_debug = TRUE;
				
				// soft delete
				$this->core->update('t_trx_bts_optimization', array('active' => 0), array('visit_id' => $visit_id));
				
				$json = array('message' => 'BTS Optimization data has been deleted successfully.');
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_BTS_LIST') {
			
				$json = array();
				$site_id = (int) $this->input->get('site_id');
				
				$json = $this->bts_optimization_model->get_bts_list($site_id);
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'LOAD_DATA_BTS') {
			
				$this->load->model('image_model');
				
				$json = array();
				$visit_id = $this->input->get('visit_id');
				
				$dataBTS = $this->bts_optimization_model->load_bts_list($visit_id);
				$data = array();
				foreach ($dataBTS['data'] as $bts) {
					$data[] = array(
						'azimuth' => $bts['azimuth_after'],
						'electrical_tilt' => $bts['electrical_tilt_after'],
						'mechanical_tilt' => $bts['mechanical_tilt_after'],
						'photo_cat' => $bts['photo_cat'],
						'photo' => $this->image_model->resize(FCPATH.'file/upload/bts/'.$bts['photo_name'], 50, 50),
						'photo_big' => base_url('/file/upload/bts/'.$bts['photo_name'])
					);
				}
				
				$json['total_rows'] = $dataBTS['total_rows'];
				$json['rec_info'] = $dataBTS['rec_info'];
				$json['data'] = $data;
				$json['pagination'] = $dataBTS['pagination'];
				$json['sql'] = $dataBTS['sql'];
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_BTS_OPTIMIZ') {
				
				$json = array();
				
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 15;
				$offset = ($page-1) * $rows;
				$search = $this->db->escape_str($this->input->get('query'));
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'last_visit';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
				$year = $this->input->get('year');
				$month = $this->input->get('month');
				
				$region = array();
				foreach ($this->session->userdata['user_region'] as $reg) {
					if ($reg['region_id'] != '') {
						$region[] = $reg['region_id'];
					}
				}
				
				$where = "WHERE o.visit_id IN (SELECT visit_id FROM t_trx_bts_optimization_adjust WHERE active = 1) AND o.active = 1";
				$where .= " AND (UPPER(s.site_code) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(s.site_code_old) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(s.vendor_id) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(s.site_name) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(reg.region_name) LIKE '%".strtoupper($search)."%')";
				//$where .= " OR (SELECT visit_date FROM t_trx_bts_optimization op2 WHERE op2.site_id = o.site_id AND op2.active = 1 ORDER BY op2.visit_date DESC LIMIT 1)::VARCHAR LIKE '%".$search."%'";
				
				if (count($region) > 0) {
					$where .= " AND s.region_id IN (".implode(', ', $region).") ";
				}
				
				if ($year != 0) {
					//$where .= " AND date_part('year', (SELECT visit_date FROM t_trx_bts_optimization WHERE site_id = o.site_id AND active = 1 ORDER BY visit_date DESC LIMIT 1)) = ".$year;
					$where .= " AND EXTRACT(YEAR FROM o.visit_date) = ".$year;
				}
				if ($month != 0) {
					//$where .= "  AND date_part('month', (SELECT visit_date FROM t_trx_bts_optimization WHERE site_id = o.site_id AND active = 1 ORDER BY visit_date DESC LIMIT 1)) = ".$month;
					$where .= " AND EXTRACT(MONTH FROM o.visit_date) = ".$month;
				}
				
				$sql = "
					SELECT DISTINCT s.site_id, s.site_code, s.site_code_old, s.vendor_id, s.site_name, reg.region_name,
					(SELECT COUNT(*) FROM t_trx_bts_optimization op1 WHERE op1.site_id = o.site_id AND op1.active = 1) AS number_of_visit,
					(SELECT visit_date FROM t_trx_bts_optimization op2 WHERE op2.site_id = o.site_id AND op2.active = 1 ORDER BY op2.visit_date DESC LIMIT 1) AS last_visit
					FROM t_trx_bts_optimization o
					LEFT JOIN t_mtr_site s ON s.site_id = o.site_id
					LEFT JOIN t_mtr_region reg ON reg.region_id = s.region_id
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
					$r['site_id'] = $this->core->encode($r['site_id']);
					$rows[] = $r;					
				}
				
				$json['total'] = $total_rows;
				$json['rows'] = $rows;
				
				$sql = str_replace("\t", '', $sql);
				$sql = str_replace("\n", ' ', $sql);
				$json['sql'] = $sql;
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_BTS_OPTIMIZ_DETAIL') {
				
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 15;
				$site_id = (int) $this->core->decode($this->input->get('site_id'));
				$offset = ($page -1) * $rows;
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'visit_id';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
				
				$sql = "
					SELECT o.visit_id, o.visit_date, lo1.lookup_desc AS reason, o.vendor_engineer, o.vendor_rigger, o.sf_engineer, o.sf_rigger
					FROM t_trx_bts_optimization o
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(o.reason AS VARCHAR) AND lo1.lookup_key = 'bts_optimization_reason' AND lo1.active = 1
					WHERE o.site_id = {$site_id} AND o.active = 1
					ORDER BY  {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$results = $query->result_array();
				$rows = array();
				foreach ($results as $r) {
					$r['visit_id'] = $this->core->encode($r['visit_id']);
					$rows[] = $r;					
				}
				
				$json['total'] = $total_rows;
				$json['rows'] = $rows;
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_BTS_NOT_OPTIMIZ') {
				
				$json = array();
				
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page-1) * $rows;
				$search = $this->db->escape_str($this->input->get('query'));
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 's.site_code';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
				
				$region = array();
				foreach ($this->session->userdata['user_region'] as $reg) {
					if ($reg['region_id'] != '') {
						$region[] = $reg['region_id'];
					}
				}
				
				$where = "WHERE rf.site_id NOT IN (SELECT site_id FROM t_trx_bts_optimization) AND rf.active = 1";
				$where .= " AND (UPPER(s.site_code) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(s.site_code_old) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(s.vendor_id) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(s.site_name) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(reg.region_name) LIKE '%".strtoupper($search)."%')";
				
				if (count($region) > 0) {
					$where .= " AND s.region_id IN (".implode(', ', $region).") ";
				}
				
				$sql = "
					SELECT DISTINCT rf.site_id, s.site_code, s.site_code_old, s.vendor_id, s.site_name, reg.region_name
					FROM t_mtr_site_l_equipment_rf rf
					LEFT JOIN t_mtr_site s ON s.site_id = rf.site_id
					LEFT JOIN t_mtr_region reg ON reg.region_id = s.region_id
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
					$r['site_id'] = $this->core->encode($r['site_id']);
					$rows[] = $r;					
				}
				
				$json['total'] = $total_rows;
				$json['rows'] = $rows;
				
				$sql = str_replace("\t", '', $sql);
				$sql = str_replace("\n", ' ', $sql);
				$json['sql'] = $sql;
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_BTS_NOT_OPTIMIZ_DETAIL') {
				
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$site_id = (int) $this->core->decode($this->input->get('site_id'));
				$offset = ($page -1) * $rows;
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'lo1.order';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
				
				$sql = "
					SELECT
						rf.equipment_rf_id, lo2.lookup_desc AS \"type\", brand.model, brand.brand, lo1.lookup_desc AS cell, lo3.lookup_desc AS band,  brand.hbwd, brand.vbwd,
						brand.dimension_long, brand.dimension_width, brand.dimension_height, brand.weight, rf.height, rf.azimuth, rf.electrical_tilt, rf.mechanical_tilt
					FROM t_mtr_site_l_equipment_rf rf
					LEFT JOIN t_mtr_site_l_equipment_rf_antenna_brand brand ON brand.antenna_id = rf.model
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = rf.cell::VARCHAR AND lo1.active = 1 AND lo1.lookup_key = 'antenna_cell'
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = rf.\"type\"::VARCHAR AND lo2.active = 1 AND lo2.lookup_key = 'antenna_rf_type'
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = rf.ant_model::VARCHAR AND lo3.active = 1 AND lo3.lookup_key = 'antenna_band'
					WHERE rf.site_id = {$site_id} AND rf.active = 1
					ORDER BY {$sort} {$order}
				";
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json = array();
				
				$json['total'] = $query->num_rows();
				
				$results = $query->result_array();
				$rows = array();
				foreach ($results as $r) {
					$r['equipment_rf_id'] = $this->core->encode($r['equipment_rf_id']);
					$rows[] = $r;
				}
				
				$json['rows'] = $rows;
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('load_content') == 'ANTENNA_RF_OPTIM') {
			
				$json = array();
				$site_id = (int) $this->input->get('site_id');
				$act = $this->input->get('act');
				
				$results = array();
				
				if ($act == 'add') {
					$results = $this->db
						->query("
							SELECT
								rf.equipment_rf_id, rf.\"type\", rf.cell, br.model, br.brand, rf.ant_model, br.hbwd, br.vbwd, br.dimension_long, br.dimension_width, br.dimension_height, 
								br.weight, rf.height AS height_before, rf.azimuth AS azimuth_before, rf.electrical_tilt AS electrical_tilt_before, rf.mechanical_tilt AS mechanical_tilt_before
							FROM t_mtr_site_l_equipment_rf rf
							LEFT JOIN t_mtr_site_l_equipment_rf_antenna_brand br ON br.antenna_id = rf.model
							LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = rf.cell::VARCHAR AND lo1.active = 1 AND lo1.lookup_key = 'antenna_cell'
							WHERE rf.site_id = {$site_id} AND rf.active = 1
							ORDER BY lo1.order ASC
						")
						->result_array();
						
				} elseif ($act == 'edit') {
					$visit_id = (int) $this->core->decode($this->input->get('visit_id'));
					
					$results = $this->db
						->query("
							SELECT
								adj.equipment_rf_id, rf.\"type\", rf.cell, br.model, br.brand, rf.ant_model, br.hbwd, br.vbwd, br.dimension_long, br.dimension_width, br.dimension_height, 
								br.weight, adj.height_before, adj.height_after, adj.azimuth_before, adj.azimuth_after, adj.electrical_tilt_before, adj.electrical_tilt_after,
								adj.mechanical_tilt_before, adj.mechanical_tilt_after
							FROM t_trx_bts_optimization_adjust adj
							LEFT JOIN t_trx_bts_optimization opt ON opt.visit_id = adj.visit_id
							LEFT JOIN t_mtr_site_l_equipment_rf rf ON rf.equipment_rf_id = adj.equipment_rf_id
							LEFT JOIN t_mtr_site_l_equipment_rf_antenna_brand br ON br.antenna_id = rf.model
							LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = rf.cell::VARCHAR AND lo1.active = 1 AND lo1.lookup_key = 'antenna_cell'
							WHERE adj.visit_id = {$visit_id} AND opt.active = 1 AND adj.active = 1
							ORDER BY lo1.\"order\" ASC
						")
						->result_array();
				}
				
				$data['bts_rf'] = $results;
				
				echo $this->parser->parse('partials/content_equipment_rf_optim', $data);
				exit;
			}
		
			if ($this->input->get('cmd') == 'export') {
		
				$data = array();
				
				echo $this->parser->parse('partials/bts_optimization_export', $data);
				exit;
			}
		
			if ($this->input->get('cmd') == 'get_lookup_site') {
		
				$data = array();
				
				if ($this->input->get('act') == 'get_data') {
		
					$json = array();
					
					$page = $this->input->get('page') ? $this->input->get('page') : 1;
					$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
					$offset = ($page - 1) * $rows;
					$search = $this->db->escape_str($this->input->get('query'));
					$sort = $this->input->get('sort') ? $this->input->get('sort') : 's.updated_on';
					$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
					
					$region = array();
					foreach ($this->session->userdata['user_region'] as $reg) {
						if ($reg['region_id'] != '') {
							$region[] = $reg['region_id'];
						}
					}
					
					$where = "WHERE s.active = 1 AND s.site_id IN (SELECT site_id FROM t_mtr_site_l_equipment_rf WHERE active = 1) AND (";
					$where .= " UPPER(s.site_code) LIKE '%".strtoupper($search)."%'";
					$where .= " OR UPPER(s.site_code_old) LIKE '%".strtoupper($search)."%'";
					$where .= " OR UPPER(s.site_name) LIKE '%".strtoupper($search)."%'";			
					$where .= " OR UPPER(s.vendor_id) LIKE '%".strtoupper($search)."%'";
					$where .= " OR UPPER(r.region_name) LIKE '%".strtoupper($search)."%'";
					$where .= ")";
					if (count($region) > 0) {
						$where .= " AND s.region_id IN (".implode(', ', $region).") ";
					}
					
					$sql = "
						SELECT s.site_id, s.site_code, s.site_code_old, s.site_name, s.vendor_id, r.region_name
						FROM t_mtr_site s 
						LEFT JOIN t_mtr_region r ON r.region_id = s.region_id
						{$where}
						ORDER BY {$sort} {$order}
					";
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
					
					$total_rows = (int) $query->row()->total;
					
					$sql .= " LIMIT {$rows} OFFSET {$offset}";
					
					$query = $this->db->query($sql);
					
					$json = array();
					
					$json['total'] = $total_rows;
					$json['rows'] = $query->result_array();
					
					$sql = str_replace("\t", '', $sql);
					$sql = str_replace("\n", ' ', $sql);
					$json['sql'] = $sql;
					
					echo json_encode($json);
					exit;
				}
				
				echo $this->parser->parse('partials/lookup_sites_bts_optim', $data);
				exit;
			}
		}
		
		if ($this->input->get('cmd') == 'DELETE') {
			
			$json = array();
			$id = $this->input->get('id');
			
			// soft delete
			$this->core->update('t_trx_bts_optimization', array('active' => 0), array('visit_id' => $id));
			
			redirect('/site_info/bts_optimization');
		}
		
		if ($this->input->get('cmd') == 'PRINT') {
		
			$visit_id = (int) $this->core->decode($this->input->get('visitId'));
			
			$data = $this->_partials;
			
			$data['visit'] = $this->db
				->query("
					SELECT site.site_code, site.site_name, site.site_address, reg.region_name, lok.nama AS city, cnet.cluster_name AS rf_cluster_id,
						site.latitude, site.longitude, season.phase, lo1.lookup_desc AS site_type, opt.visit_date, opt.vendor_engineer, opt.vendor_rigger, 
						site.vendor_name, opt.sf_engineer, lo2.lookup_desc AS reason, opt.sf_rigger, usr1.user_name AS pic_smart, site.pic_smart_phone,
						part.pic_name, site.pic_cme_partner_phone, opt.updated_on AS visit_time,
						opt.vendor_engineer_contact_no, opt.vendor_rigger_contact_no, opt.sf_engineer_contact_no, opt.sf_rigger_contact_no
					FROM t_trx_bts_optimization opt
					LEFT JOIN t_mtr_site site ON site.site_id = opt.site_id
					LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
					LEFT JOIN t_mtr_lokasi lok ON lok.lokasi_id = site.kabupaten AND lok.jenis = 'KB'
					LEFT JOIN t_mtr_cluster_net cnet ON cnet.cluster_net_id = site.cluster_net_id
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(site.site_type AS VARCHAR) AND lo1.lookup_key = 'site_type' AND lo1.active = 1
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(opt.reason AS VARCHAR) AND lo2.lookup_key = 'bts_optimization_reason' AND lo2.active = 1
					LEFT JOIN t_mtr_user usr1 ON usr1.user_id = site.pic_smart
					LEFT JOIN t_mtr_partner_pic part ON CAST(part.partner_pic_id AS VARCHAR) = site.pic_cme_partner
					LEFT JOIN t_mtr_site_season season ON season.site_id = opt.site_id
					WHERE visit_id = {$visit_id}
				")
				->row_array();
				
			$data['antenna'] = $this->db
				->query("
					SELECT lo1.lookup_desc AS cell, lo2.lookup_desc AS ant_model, adj.height_before, adj.height_after, adj.azimuth_before, adj.azimuth_after, adj.electrical_tilt_before, adj.electrical_tilt_after, adj.mechanical_tilt_before, adj.mechanical_tilt_after
					FROM t_trx_bts_optimization_adjust adj
					LEFT JOIN t_mtr_site_l_equipment_rf rf ON rf.equipment_rf_id = adj.equipment_rf_id
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(rf.cell AS VARCHAR) AND lo1.lookup_key = 'antenna_cell' AND lo1.active = 1
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(rf.ant_model AS VARCHAR) AND lo2.lookup_key = 'antenna_band' AND lo2.active = 1
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = rf.cell::VARCHAR AND lo3.lookup_key = 'antenna_cell' AND lo3.active = 1
					WHERE adj.visit_id = {$visit_id}
					ORDER BY lo3.order ASC
				")
				->result_array();
			
			$results = $this->db
				->query("
					SELECT photo_cat, photo_name FROM t_trx_bts_optimization_photo
					WHERE visit_id = {$visit_id}
				")
				->result_array();
			$data['photos'] = array();
			foreach ($results as $res) {
				$data['photos'][$res['photo_cat']] = $res['photo_name'];
			}
			
			echo $this->parser->parse('partials/content_equipment_rf_optim_print', $data);
			exit;
		}
		
		$data['content_id'] = 'bts_optimization_list_view';
		
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
		
		if ($this->input->post('cmd') == 'ADD_BO') {
			
			if ($this->input->post('act') == 'SAVE') {
				
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Site ID can not empty.';
				}
				if ($this->_is_field_empty($post['visitDate'])) {
					$error[] = 'Visit Date can not empty.';
				}
				if ($this->_is_field_empty($post['reason'])) {
					$error[] = 'Reason for Change can not unselected.';
				}
				if ($this->_is_field_empty($post['vendorEngineer'])) {
					$error[] = 'Vendor Engineer can not empty.';
				}
				if ($this->_is_field_empty($post['vendorRigger'])) {
					$error[] = 'Vendor Rigger can not empty.';
				}
				if ($this->_is_field_empty($post['sfEngineer'])) {
					$error[] = 'SF Engineer can not empty.';
				}
				if ($this->_is_field_empty($post['sfRigger'])) {
					$error[] = 'SF Rigger can not empty.';
				}
				
				/* if ($this->_is_field_empty(@$post['azimuth'])) {
					$error[] = 'Azimuth can not empty.';
				}
				if ($this->_is_field_empty(@$post['electricalTilt'])) {
					$error[] = 'Electrical Tilt can not empty.';
				}
				if ($this->_is_field_empty(@$post['mechanicalTilt'])) {
					$error[] = 'Mechanical Tilt Tilt can not empty.';
				}
				if ($this->_is_field_empty(@$post['height'])) {
					$error[] = 'Height can not empty.';
				} */
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					$this->core->set['site_id'] = $post['siteId'];
					$this->core->set['visit_date'] = null_empty($post['visitDate']);
					$this->core->set['vendor_engineer'] = $post['vendorEngineer'];
					$this->core->set['vendor_engineer_contact_no'] = $post['vendorEngineerContactNo'];
					$this->core->set['vendor_rigger'] = $post['vendorRigger'];
					$this->core->set['vendor_rigger_contact_no'] = $post['vendorRiggerContactNo'];
					$this->core->set['sf_engineer'] = $post['sfEngineer'];
					$this->core->set['sf_engineer_contact_no'] = $post['sfEngineerContactNo'];
					$this->core->set['sf_rigger'] = $post['sfRigger'];
					$this->core->set['sf_rigger_contact_no'] = $post['sfRiggerContactNo'];
					$this->core->set['reason'] = $post['reason'];
					$this->core->set['remarks'] = $post['remarks'];
					
					$this->core->set['created_on'] = $created_on;
					$this->core->set['created_by'] = $created_by;
					$this->core->set['updated_on'] = $updated_on;
					$this->core->set['updated_by'] = $updated_by;
					
					$this->core->save('t_trx_bts_optimization');
					$visit_id = $this->db->insert_id();
					
					$this->core->update('t_trx_bts_optimization_adjust', array('active' => 0), array('visit_id' => $visit_id));
					
					if (@is_array($post['rfId'])) {
						foreach ($post['rfId'] as $idx => $val) {
							$this->core->set['visit_id'] = $visit_id;
							$this->core->set['equipment_rf_id'] = (int) $post['rfId'][$idx];
							$this->core->set['azimuth_after'] = (float) $post['azimuth'][$idx];
							$this->core->set['electrical_tilt_after'] = (float) $post['electricalTilt'][$idx];
							$this->core->set['mechanical_tilt_after'] = (float) $post['mechanicalTilt'][$idx];
							$this->core->set['height_after'] = (float) $post['height'][$idx];
							$this->core->set['created_on'] = $created_on;
							$this->core->set['created_by'] = $created_by;
							$this->core->set['updated_on'] = $updated_on;
							$this->core->set['updated_by'] = $updated_by;
							$this->core->set['active'] = 1;
							
							if ( $bo_adj = $this->core->view_row('t_mtr_site_l_equipment_rf', array('equipment_rf_id' => (int) $post['rfId'][$idx])) ) {
								$this->core->set['azimuth_before'] = $bo_adj['azimuth'];
								$this->core->set['electrical_tilt_before'] = $bo_adj['electrical_tilt'];
								$this->core->set['mechanical_tilt_before'] = $bo_adj['mechanical_tilt'];
								$this->core->set['height_before'] = $bo_adj['height'];
							}
							
							$this->core->save('t_trx_bts_optimization_adjust');
							
							// update RF master
							$azimuth = (float) $post['azimuth'][$idx];
							$electrical_tilt = (float) $post['electricalTilt'][$idx];
							$mechanical_tilt = (float) $post['mechanicalTilt'][$idx];
							$height = (float) $post['height'][$idx];
							
							$rf = $this->core->view_row('t_mtr_site_l_equipment_rf', array('equipment_rf_id' => (int) $post['rfId'][$idx], 'site_id' => $post['siteId']));
							
							$this->core->set['azimuth'] = !$this->_is_field_empty($post['azimuth'][$idx]) ? $azimuth : $rf['azimuth'];
							$this->core->set['electrical_tilt'] = !$this->_is_field_empty($post['electricalTilt'][$idx]) ? $electrical_tilt : $rf['electrical_tilt'];
							$this->core->set['mechanical_tilt'] = !$this->_is_field_empty($post['mechanicalTilt'][$idx]) ? $mechanical_tilt : $rf['mechanical_tilt'];
							$this->core->set['height'] = !$this->_is_field_empty($post['height'][$idx]) ? $height : $rf['height'];
							$this->core->set['updated_on'] = $updated_on;
							$this->core->set['updated_by'] = $updated_by;
							$this->core->set['active'] = 1;
						
							$this->core->save('t_mtr_site_l_equipment_rf', array('equipment_rf_id' => (int) $post['rfId'][$idx], 'site_id' => $post['siteId']));
						}
					}
					
					// Photo(s)
					$lookup = $this->core->get_lookup('bts_jenis_foto');
					foreach ($lookup as $lo) {
						if ($post['photoRF'.$lo['id']] != '') {
							$this->core->set['visit_id'] = $visit_id;
							$this->core->set['photo_cat'] = $lo['id'];
							$this->core->set['photo_name'] = $post['photoRF'.$lo['id']];
								
							if ( $this->core->view_row('t_trx_bts_optimization_photo', array('photo_cat' => $lo['id'], 'visit_id' => $visit_id)) ) {
								$this->core->set['updated_on'] = $updated_on;
								$this->core->set['updated_by'] = $updated_by;
						
								$this->core->save('t_trx_bts_optimization_photo', array('photo_cat' => $lo['id'], 'visit_id' => $visit_id));
							} else {
								$this->core->set['created_on'] = $created_on;
								$this->core->set['created_by'] = $created_by;
								$this->core->set['updated_on'] = $updated_on;
								$this->core->set['updated_by'] = $updated_by;
						
								$this->core->save('t_trx_bts_optimization_photo');
							}
						}
					}
					
					$json = array('error' => FALSE, 'message' => 'BTS Optimization has been added successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			
			} elseif ($this->input->post('act') == 'UPLOAD') { // just upload file
				
				$post = $this->core->get_post();
				
				$json = array();
				
				$allowed = array(
					'.jpg',
					'.jpeg',
					'.gif',
					'.png'
				);
				
				$upload_key = array_keys($_FILES);
				
				if ( ! empty($_FILES[$upload_key[0]]['name']) && $_FILES[$upload_key[0]]['error'] == UPLOAD_ERR_OK) {
					$tmp_name = $_FILES[$upload_key[0]]['tmp_name'];
					$ext = strtolower(strrchr($_FILES[$upload_key[0]]['name'], '.'));
					$file_name = $post['image'] != '' ? $post['image'] : strtoupper(md5(time())).$ext;
					
					if ( ! in_array($ext, $allowed)) {
						$json = array('error' => TRUE, 'message' => 'Invalid file type. Please just upload file .jpg, .gif, .png.');
					} else {					
						if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_bts_optimization_dir.'/'.$file_name)) {
							// resize first
							$file = $this->_bts_optimization_dir.'/'.$file_name;
							$size = getimagesize($file);
							$width = $size[0];
							$height = $size[1];
							$expected_width = 550;
							$expected_height = 550;
							if ($width > $expected_width && $height > $expected_height) {
								$image = new Image($file);
								$image->resizeImage($expected_width, $expected_height, 'auto');
								$image->saveImage($file, 80);
							}
							
							$image = $this->_bts_optimization_url.'/'.$file_name.'?ts='.time();
							$thumb = $this->image_model->resize($file, 60, 60, 'crop').'?ts='.time();
							$json = array('error' => FALSE, 'type' => $upload_key[0], 'file' => $file_name, 'image' => $image, 'thumb' => $thumb, 'message' => 'File uploaded.');
						}
					}
				
				}
				
				echo json_encode($json);
				exit;
			
			} elseif ($this->input->post('act') == 'DELETE_PHOTO') {
			
				$json = array();
				
				$photo_name = $this->input->post('photo_name');
				$photo_type = $this->input->post('photo_type');
				$pathinfo = pathinfo($photo_name);
				
				if (is_file($this->_bts_optimization_dir.'/'.$pathinfo['basename'])) {
					@unlink($this->_bts_optimization_dir.'/'.$pathinfo['basename']);
				}
				
				if (is_file($this->_bts_optimization_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension'])) {
					@unlink($this->_bts_optimization_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension']);
				}
				
				$json = array('error' => FALSE, 'type' => $photo_type, 'message' => 'File has been deleted successfully.');
			
				echo json_encode($json);
				exit;
			}
		}
		
		$this->_bts_optimization_form();
	}
	
	public function edit($visit_id = 0)
	{
		$this->_check_valid_loggedin();
		
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_edit')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$visit_id = (int) $this->core->decode($visit_id);
		
		if ($this->input->post('cmd') == 'EDIT_BO') {
			
			if ($this->input->post('act') == 'SAVE') {
				
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Site ID can not empty.';
				}
				if ($this->_is_field_empty($post['visitDate'])) {
					$error[] = 'Visit Date can not empty.';
				}
				if ($this->_is_field_empty($post['reason'])) {
					$error[] = 'Reason for Change can not unselected.';
				}
				if ($this->_is_field_empty($post['vendorEngineer'])) {
					$error[] = 'Vendor Engineer can not empty.';
				}
				if ($this->_is_field_empty($post['vendorRigger'])) {
					$error[] = 'Vendor Rigger can not empty.';
				}
				if ($this->_is_field_empty($post['sfEngineer'])) {
					$error[] = 'SF Engineer can not empty.';
				}
				if ($this->_is_field_empty($post['sfRigger'])) {
					$error[] = 'SF Rigger can not empty.';
				}
				
				/* if ($this->_is_field_empty(@$post['height'])) {
					$error[] = 'Height can not empty.';
				}
				if ($this->_is_field_empty(@$post['azimuth'])) {
					$error[] = 'Azimuth can not empty.';
				}
				if ($this->_is_field_empty(@$post['electricalTilt'])) {
					$error[] = 'Electrical Tilt can not empty.';
				}
				if ($this->_is_field_empty(@$post['mechanicalTilt'])) {
					$error[] = 'Mechanical Tilt Tilt can not empty.';
				} */
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					//$this->core->set['site_id'] = $post['siteId'];
					$this->core->set['visit_date'] = null_empty($post['visitDate']);
					$this->core->set['vendor_engineer'] = $post['vendorEngineer'];
					$this->core->set['vendor_engineer_contact_no'] = $post['vendorEngineerContactNo'];
					$this->core->set['vendor_rigger'] = $post['vendorRigger'];
					$this->core->set['vendor_rigger_contact_no'] = $post['vendorRiggerContactNo'];
					$this->core->set['sf_engineer'] = $post['sfEngineer'];
					$this->core->set['sf_engineer_contact_no'] = $post['sfEngineerContactNo'];
					$this->core->set['sf_rigger'] = $post['sfRigger'];
					$this->core->set['sf_rigger_contact_no'] = $post['sfRiggerContactNo'];
					$this->core->set['reason'] = $post['reason'];
					$this->core->set['remarks'] = $post['remarks'];
					
					$this->core->set['updated_on'] = $updated_on;
					$this->core->set['updated_by'] = $updated_by;
					
					//$this->core->save('t_trx_bts_optimization', array('visit_id' => $visit_id));
					$data = $this->core->set;
					$data['visit_id'] = $visit_id;
					$this->site_info->save_data('t_trx_bts_optimization', $data, 'visit_id');
					unset($this->core->set);
					
					$this->core->update('t_trx_bts_optimization_adjust', array('active' => 0), array('visit_id' => $visit_id));
					
					if (@is_array($post['rfId'])) {
						foreach ($post['rfId'] as $idx => $val) {
							$this->core->set['visit_id'] = $visit_id;
							$this->core->set['equipment_rf_id'] = (int) $post['rfId'][$idx];
							$this->core->set['azimuth_after'] = (float) $post['azimuth'][$idx];
							$this->core->set['electrical_tilt_after'] = (float) $post['electricalTilt'][$idx];
							$this->core->set['mechanical_tilt_after'] = (float) $post['mechanicalTilt'][$idx];
							$this->core->set['height_after'] = (float) $post['height'][$idx];
							$this->core->set['updated_on'] = $updated_on;
							$this->core->set['updated_by'] = $updated_by;
							$this->core->set['active'] = 1;
							
							$this->core->save('t_trx_bts_optimization_adjust', array('equipment_rf_id' => (int) $post['rfId'][$idx], 'visit_id' => $visit_id));
							
							// update RF master
							$this->core->set['azimuth'] = (float) $post['azimuth'][$idx];
							$this->core->set['electrical_tilt'] = (float) $post['electricalTilt'][$idx];
							$this->core->set['mechanical_tilt'] = (float) $post['mechanicalTilt'][$idx];
							$this->core->set['height'] = (float) $post['height'][$idx];
							
							$this->core->set['updated_on'] = $updated_on;
							$this->core->set['updated_by'] = $updated_by;
							
							$this->core->save('t_mtr_site_l_equipment_rf', array('equipment_rf_id' => (int) $post['rfId'][$idx]));
						}
					}
					
					// Photo(s)
					$lookup = $this->core->get_lookup('bts_jenis_foto');
					foreach ($lookup as $lo) {
						if ($post['photoRF'.$lo['id']] != '') {
							$this->core->set['visit_id'] = $visit_id;
							$this->core->set['photo_cat'] = $lo['id'];
							$this->core->set['photo_name'] = $post['photoRF'.$lo['id']];
								
							if ( $this->core->view_row('t_trx_bts_optimization_photo', array('photo_cat' => $lo['id'], 'visit_id' => $visit_id)) ) {
								$this->core->set['updated_on'] = $updated_on;
								$this->core->set['updated_by'] = $updated_by;
						
								$this->core->save('t_trx_bts_optimization_photo', array('photo_cat' => $lo['id'], 'visit_id' => $visit_id));
							} else {
								$this->core->set['created_on'] = $created_on;
								$this->core->set['created_by'] = $created_by;
								$this->core->set['updated_on'] = $updated_on;
								$this->core->set['updated_by'] = $updated_by;
						
								$this->core->save('t_trx_bts_optimization_photo');
							}
						}
					}
					
					$json = array('error' => FALSE, 'message' => 'BTS Optimization has been updated successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			
			} elseif ($this->input->post('act') == 'UPLOAD') { // just upload file
				
				$post = $this->core->get_post();
				
				$json = array();
				
				$allowed = array(
					'.jpg',
					'.jpeg',
					'.gif',
					'.png'
				);
				
				$upload_key = array_keys($_FILES);
				
				if ( ! empty($_FILES[$upload_key[0]]['name']) && $_FILES[$upload_key[0]]['error'] == UPLOAD_ERR_OK) {
					$tmp_name = $_FILES[$upload_key[0]]['tmp_name'];
					$ext = strtolower(strrchr($_FILES[$upload_key[0]]['name'], '.'));
					$file_name = $post['image'] != '' ? $post['image'] : strtoupper(md5(time())).$ext;
					
					if ( ! in_array($ext, $allowed)) {
						$json = array('error' => TRUE, 'message' => 'Invalid file type. Please just upload file .jpg, .gif, .png.');
					} else {					
						if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_bts_optimization_dir.'/'.$file_name)) {
							// resize first
							$file = $this->_bts_optimization_dir.'/'.$file_name;
							$size = getimagesize($file);
							$width = $size[0];
							$height = $size[1];
							$expected_width = 550;
							$expected_height = 550;
							if ($width > $expected_width && $height > $expected_height) {
								$image = new Image($file);
								$image->resizeImage($expected_width, $expected_height, 'auto');
								$image->saveImage($file, 80);
							}
							
							$image = $this->_bts_optimization_url.'/'.$file_name.'?ts='.time();
							$thumb = $this->image_model->resize($file, 60, 60, 'crop').'?ts='.time();
							$json = array('error' => FALSE, 'type' => $upload_key[0], 'file' => $file_name, 'image' => $image, 'thumb' => $thumb, 'message' => 'File uploaded.');
						}
					}
				
				}
				
				echo json_encode($json);
				exit;
			
			} elseif ($this->input->post('act') == 'DELETE_PHOTO') {
			
				$json = array();
				
				$photo_name = $this->input->post('photo_name');
				$photo_type = $this->input->post('photo_type');
				$pathinfo = pathinfo($photo_name);
				
				if (is_file($this->_bts_optimization_dir.'/'.$pathinfo['basename'])) {
					@unlink($this->_bts_optimization_dir.'/'.$pathinfo['basename']);
				}
				
				if (is_file($this->_bts_optimization_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension'])) {
					@unlink($this->_bts_optimization_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension']);
				}
				
				$json = array('error' => FALSE, 'type' => $photo_type, 'message' => 'File has been deleted successfully.');
			
				echo json_encode($json);
				exit;
			}
		}
		
		$this->_bts_optimization_form($visit_id);
	}
	
	public function new_visit($site_id = 0)
	{
		$site_id = (int) $this->core->decode($site_id);
		
		$data = $this->_partials;
		
		if ( $bts_optimization = $this->db
			->query("
				SELECT opt.*, site.site_code, site.site_code_old, site.site_name, site.vendor_id, reg.region_name
				FROM t_trx_bts_optimization opt
				LEFT JOIN t_mtr_site site ON site.site_id = opt.site_id
				LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
				WHERE site.site_id = {$site_id} AND opt.active = 1
				ORDER BY opt.visit_id DESC LIMIT 1
			")
			->row_array() ) {
			
			$data['bts_optimization'] = $bts_optimization;
		} else {
			show_404();
		}
		
		$data['content_id'] = 'bts_optimization_visit_form';
		
		$this->load->view('template/smashing', $data);
	}
	
	public function view($visit_id = 0)
	{
		$this->_check_valid_loggedin();
		
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_view')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$visit_id = (int) $this->core->decode($visit_id);
		
		$data = $this->_partials;
		
		if ( $bts_optimization = $this->db
			->query("
				SELECT opt.*, site.site_code, site.site_code_old, site.site_name, site.vendor_id, reg.region_name
				FROM t_trx_bts_optimization opt
				LEFT JOIN t_mtr_site site ON site.site_id = opt.site_id
				LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
				WHERE opt.visit_id = {$visit_id}
			")
			->row_array() ) {
			
			$data['bts_optim'] = $bts_optimization;
			$data['bts_optim']['rf'] = $this->db
				->query("
					SELECT
						rf.\"type\", rf.cell, br.model, br.brand, rf.ant_model, adj.height_before, adj.height_after, adj.azimuth_before, adj.azimuth_after, adj.electrical_tilt_before, adj.electrical_tilt_after,
						adj.mechanical_tilt_before, adj.mechanical_tilt_after, br.hbwd, br.vbwd, br.dimension_long, br.dimension_width, br.dimension_height, br.weight
					FROM t_trx_bts_optimization_adjust adj
					LEFT JOIN t_mtr_site_l_equipment_rf rf ON rf.equipment_rf_id = adj.equipment_rf_id
					LEFT JOIN t_mtr_site_l_equipment_rf_antenna_brand br ON br.antenna_id = rf.model
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = rf.cell::VARCHAR AND lo1.active = 1 AND lo1.lookup_key = 'antenna_cell'
					WHERE adj.visit_id = {$visit_id} AND adj.active = 1
					ORDER BY lo1.order ASC
				")
				->result_array();
			$data['bts_optim']['photo'] = $this->core->view_all('t_trx_bts_optimization_photo', array('visit_id' => $visit_id), 'photo_id');
		
		} else {
			show_404();
		}
		
		$data['content_id'] = 'bts_optimization_view_view';
		
		$this->load->view('template/smashing', $data);
	}
	
	private function _bts_optimization_form($visit_id = 0)
	{
		$data = $this->_partials;
		
		if ($this->uri->segment(3) == 'edit') {
			if ( $bts_optimization = $this->db
				->query("
					SELECT opt.*, site.site_code, site.site_code_old, site.site_name, site.vendor_id, reg.region_name
					FROM t_trx_bts_optimization opt
					LEFT JOIN t_mtr_site site ON site.site_id = opt.site_id
					LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
					WHERE opt.visit_id = {$visit_id}
				")
				->row_array() ) {
				
				$data['bts_optimization'] = $bts_optimization;
				$data['bts_optimization']['rf'] = $this->core->view_all('t_mtr_site_l_equipment_rf', array('site_id' => $bts_optimization['site_id'], 'active' => 1), 'equipment_rf_id');
				$data['bts_optimization']['photo'] = $this->core->view_all('t_trx_bts_optimization_photo', array('visit_id' => $visit_id), 'photo_id');
			} else {
				show_404();
			}
		}
		
		$data['content_id'] = 'bts_optimization_form_view';
		
		$this->load->view('template/smashing', $data);
	}
	
	public function export($format = 'xls')
	{
		set_time_limit(3600);
		
		require_once APPPATH.'/libraries/PHPExcel.php';
		require_once APPPATH.'/libraries/PHPExcel/IOFactory.php';
		
		$site_ids = explode(',', $this->input->get('site_ids'));
		foreach ($site_ids as $idx => $id) {
			$site_ids[$idx] = (int) $this->core->decode($id);
		}
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Vendor ID');
		$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Site Name');
		$objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cell');
		$objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Lat');
		$objPHPExcel->getActiveSheet()->mergeCells('D1:D2');
		
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Lng');
		$objPHPExcel->getActiveSheet()->mergeCells('E1:E2');
		
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Region');
		$objPHPExcel->getActiveSheet()->mergeCells('F1:F2');
		
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'City');
		$objPHPExcel->getActiveSheet()->mergeCells('G1:G2');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'RF Cluster');
		$objPHPExcel->getActiveSheet()->mergeCells('H1:H2');
		
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'HBWD');
		$objPHPExcel->getActiveSheet()->mergeCells('I1:I2');
		
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'VBWD');
		$objPHPExcel->getActiveSheet()->mergeCells('J1:J2');
		
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Ant. Height');
		$objPHPExcel->getActiveSheet()->mergeCells('K1:L1');
		$objPHPExcel->getActiveSheet()->setCellValue('K2', 'Plan');
		$objPHPExcel->getActiveSheet()->setCellValue('L2', 'Actual');
		
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Azimuth');
		$objPHPExcel->getActiveSheet()->mergeCells('M1:N1');
		$objPHPExcel->getActiveSheet()->setCellValue('M2', 'Before');
		$objPHPExcel->getActiveSheet()->setCellValue('N2', 'After');
		
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'M. Tilt');
		$objPHPExcel->getActiveSheet()->mergeCells('O1:P1');
		$objPHPExcel->getActiveSheet()->setCellValue('O2', 'Before');
		$objPHPExcel->getActiveSheet()->setCellValue('P2', 'After');
		
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'E. Tilt');
		$objPHPExcel->getActiveSheet()->mergeCells('Q1:R1');
		$objPHPExcel->getActiveSheet()->setCellValue('Q2', 'Before');
		$objPHPExcel->getActiveSheet()->setCellValue('R2', 'After');
		
		$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Optimization Date');
		$objPHPExcel->getActiveSheet()->mergeCells('S1:S2');
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
		
		$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Reason for Change');
		$objPHPExcel->getActiveSheet()->mergeCells('T1:T2');
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
		
		$objPHPExcel->getActiveSheet()->setCellValue('U1', 'Engineer Name');
		$objPHPExcel->getActiveSheet()->mergeCells('U1:V1');
		$objPHPExcel->getActiveSheet()->setCellValue('U2', 'Smartfren');
		$objPHPExcel->getActiveSheet()->setCellValue('V2', 'Vendor');
		
		$objPHPExcel->getActiveSheet()->setCellValue('W1', 'Rigger Name');
		$objPHPExcel->getActiveSheet()->mergeCells('W1:X1');
		$objPHPExcel->getActiveSheet()->setCellValue('W2', 'Smartfren');
		$objPHPExcel->getActiveSheet()->setCellValue('X2', 'Vendor');
		
		$objPHPExcel->getActiveSheet()->setCellValue('Y1', 'Ant. Model');
		$objPHPExcel->getActiveSheet()->mergeCells('Y1:Y2');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
		
		$objPHPExcel->getActiveSheet()->setCellValue('Z1', 'Remarks');
		$objPHPExcel->getActiveSheet()->mergeCells('Z1:Z2');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:Z2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Z2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Z2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('U1:V1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('Z1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Z2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Z2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('8E0404');
		$objPHPExcel->getActiveSheet()->getStyle('A1:Z2')->getFont()->getColor()->setRGB('FFFFFF');
		//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		
		$results = $this->db
			->query("
				SELECT
					site.vendor_id, site.site_name, lo1.lookup_desc AS cell, site.latitude, site.longitude, reg.region_name, lok.nama AS city, cnet.cluster_name AS rf_cluster, br.hbwd AS horizontal_beamwidth,
					br.vbwd AS vertical_beamwidth, adj.height_before, adj.height_after, adj.azimuth_before, adj.azimuth_after, adj.mechanical_tilt_before, adj.mechanical_tilt_after, adj.electrical_tilt_before,
					adj.electrical_tilt_after, opt.visit_date, lo2.lookup_desc AS reason, opt.sf_engineer, opt.vendor_engineer, opt.sf_rigger, opt.vendor_rigger, lo3.lookup_desc AS ant_model, opt.remarks
				FROM t_trx_bts_optimization_adjust adj
				LEFT JOIN t_trx_bts_optimization opt ON opt.visit_id = adj.visit_id
				LEFT JOIN t_mtr_site site ON site.site_id = opt.site_id
				LEFT JOIN t_mtr_site_l_equipment_rf rf ON rf.equipment_rf_id = adj.equipment_rf_id
				LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id AND reg.status = 1
				LEFT JOIN t_mtr_cluster_net cnet ON cnet.cluster_net_id = site.cluster_net_id AND cnet.status = 1
				LEFT JOIN t_mtr_lokasi lok ON lok.lokasi_id = site.kabupaten AND lok.jenis = 'KB' AND lok.status = 1
				LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(rf.cell AS VARCHAR) AND lo1.lookup_key = 'antenna_cell' AND lo1.active = 1
				LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(opt.reason AS VARCHAR) AND lo2.lookup_key = 'bts_optimization_reason' AND lo2.active = 1
				LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(rf.ant_model AS VARCHAR) AND lo3.lookup_key = 'antenna_band' AND lo3.active = 1
				LEFT JOIN t_mtr_site_l_equipment_rf_antenna_brand br ON br.antenna_id = rf.model
				WHERE adj.active = 1 AND opt.active = 1 AND site.site_code IS NOT NULL AND site.site_id IN (".implode(', ', $site_ids).")
				ORDER BY site.vendor_id, adj.visit_id, lo1.\"order\" ASC
			")
			->result_array();
		
		$i = 3;
		foreach ($results as $res) {
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $res['vendor_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $res['site_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $res['cell']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $res['latitude']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $res['longitude']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $res['region_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $res['city']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $res['rf_cluster']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $res['horizontal_beamwidth']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $res['vertical_beamwidth']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $res['height_before']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $res['height_after']);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $res['azimuth_before']);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $res['azimuth_after']);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $res['mechanical_tilt_before']);
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $res['mechanical_tilt_after']);
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $res['electrical_tilt_before']);
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $res['electrical_tilt_after']);
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, date('d-m-Y', strtotime($res['visit_date'])));
			$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $res['reason']);
			$objPHPExcel->getActiveSheet()->setCellValue('U'.$i, $res['sf_engineer']);
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, $res['vendor_engineer']);
			$objPHPExcel->getActiveSheet()->setCellValue('W'.$i, $res['sf_rigger']);
			$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, $res['vendor_rigger']);
			$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $res['ant_model']);
			$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $res['remarks']);
			
			//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			//$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			
			$objPHPExcel->getActiveSheet()->getStyle('A3:Z'.$i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$i++;
		}
		
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="summary_list_bts_optimization_' . date('YmdHis') . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		//$objWriter->save("D:/summary_list_bts_optimization.".$format);
		exit;
	}
	
	public function not_optim()
	{
		$this->_check_valid_loggedin();
		
		$data = $this->_partials;
		
		$data['content_id'] = 'bts_optimization_not_optim_list';
		
		$this->load->view('template/smashing', $data);
	}
	
	public function export_not_optim($format = 'xls')
	{
		$this->load->model('export');
		
		$config= array(
					  'Creator'=>'sims',
					  'LastModified'=>date('Ymd'),
					  'Title'=>'Site List Not Optim',
					  'Subtitle'=>'Site List Not Optim',
					  'Subject'=>'Site List Not Optim',
					  'Description'=>'site_list_not_optim_'.date('YmdHis'),
					);
		
		$site_ids = explode(',', $this->input->get('site_ids'));
		foreach ($site_ids as $idx => $id) {
			$site_ids[$idx] = (int) $this->core->decode($id);
		}
		
		$query = $this->db
			->query("
				SELECT
					site.site_code AS \"Site ID\", site.site_name AS \"Site Name\", site.vendor_id AS \"Vendor ID\", lo2.lookup_desc AS \"Type\", brand.model AS \"Model\", brand.brand AS \"Brand\", lo1.lookup_desc AS \"Cell\", lo3.lookup_desc AS \"Band\",  brand.hbwd AS \"HBWD\", brand.vbwd AS \"VBWD\",
					CONCAT(brand.dimension_long, ' x ', brand.dimension_width, ' x ', brand.dimension_height) AS \"Dimension (mm)\", brand.weight AS \"Weight\", rf.height AS \"Height\", rf.azimuth AS \"Azimuth\", rf.electrical_tilt AS \"Electrical Tilt\", rf.mechanical_tilt AS \"Mechanical Tilt\"
				FROM t_mtr_site_l_equipment_rf rf
				LEFT JOIN t_mtr_site_l_equipment_rf_antenna_brand brand ON brand.antenna_id = rf.model
				LEFT JOIN t_mtr_site site ON site.site_id = rf.site_id
				LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = rf.cell::VARCHAR AND lo1.active = 1 AND lo1.lookup_key = 'antenna_cell'
				LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = rf.\"type\"::VARCHAR AND lo2.active = 1 AND lo2.lookup_key = 'antenna_rf_type'
				LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = rf.ant_model::VARCHAR AND lo3.active = 1 AND lo3.lookup_key = 'antenna_band'
				WHERE rf.site_id IN (".implode(', ', $site_ids).") AND rf.active = 1
				ORDER BY site.vendor_id, lo1.\"order\" ASC
			");
		
		$this->export->_do_export($query,'excel',$config);
	}
	
	private function _check_valid_loggedin()
	{
		if ( ! $this->session->userdata('is_login')) {
			
			if ($this->core->is_ajax()) {
				
				echo json_encode(array(
					'loggedin' => FALSE,
					'message' => 'You are not logged in. You will be redirected to login page.',
					'redirect_url' => site_url('/login/'.$this->core->encode($this->uri->uri_string()))
				));
				exit;
			} else {
				redirect('/login/'.$this->core->encode($this->uri->uri_string()));
			}
		}
	}
	
	public function view_all($visit_id = 0)
	{
		$this->_check_valid_loggedin();
		
		$data['bts_optimization'] = '';
		
		if ($bts_optimization = $this->bts_optimization_model->get_bts_optimization($visit_id)) {
		
			$data['bts_optimization'] = $bts_optimization;
			
		} else {
			show_404();
		}
		
		$this->theme->view('bts_optimization_viewall_view', $data);
	}
	
	private function _is_field_empty($field)
	{
		if (@is_array($field)) {
			$i = 0;
			foreach ($field as $arr) {
				if (trim($arr) == '' || (trim($arr) != '' && $arr == '0') || (trim($arr) != '' && $arr == '0.00')) {
					$i++;
				}
			}
			
			if ($i > 0) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			if (trim($field) == '' || (trim($field) != '' && $field == '0') || (trim($field) != '' && $field == '0.00')) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}
}