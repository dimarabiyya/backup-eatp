<?php

class Plan_info extends MX_Controller {

	private $_partials = array();
	
	public function __construct()
	{
		$this->load->library('parser');
		
		$uri = $this->uri->segment(1).'/'.$this->uri->segment(2);
		$this->_partials['menu'] = $this->mmenu->getMenuId($uri);
	}
	
	public function index()
	{
		$this->_check_valid_loggedin();
		
		$data = $this->_partials;
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->get('cmd') == 'GET_DATAGRID_PLAN') {
			
				$json = array();
				
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 15;
				$offset = ($page - 1) * $rows;
				$search = $this->input->get('query');
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'ord';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
				
				$region = array();
				foreach ($this->session->userdata['user_region'] as $reg) {
					if ($reg['region_id'] != '') {
						$region[] = $reg['region_id'];
					}
				}
				
				$where = "WHERE plan.active = 1";
				$where .= " AND (UPPER(plan.nom_site_code) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(plan.nom_site_name) LIKE '%".strtoupper($search)."%'";
				$where .= "OR CAST(plan.nom_latitude AS VARCHAR) LIKE '%".strtoupper($search)."%'";
				$where .= "OR CAST(plan.nom_longitude AS VARCHAR) LIKE '%".strtoupper($search)."%')";
				if (count($region) > 0) {
					$where .= " AND plan.region_id IN (".implode(', ', $region).")";
				}
				
				$sql = "
					SELECT
						plan.nom_site_id, plan.nom_site_code, plan.nom_site_name, plan.nom_latitude, plan.nom_longitude, plan.nom_address, reg.region_name,
						CASE WHEN plan.updated_on IS NULL THEN plan.created_on ELSE plan.updated_on END AS ord
					FROM t_mtr_site_plan plan
					LEFT JOIN t_mtr_region reg ON reg.region_id = plan.region_id
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
					$r['nom_site_id'] = $this->core->encode($r['nom_site_id']);
					$r['nom_latitude'] = number_format($r['nom_latitude'],6);
					$r['nom_longitude'] = number_format($r['nom_longitude'],6);
					$rows[] = $r;
				}
				
				$json['total'] = $total_rows;
				$json['rows'] = $rows;
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'DELETE_PLAN') {
			
				$json = array();
				
				$id = (int) $this->core->decode($this->input->get('id'));
				
				$sql = "UPDATE t_mtr_site_plan SET active = 0 WHERE nom_site_id = {$id}";
				$this->db->query($sql);
				
				$json = array('error' => FALSE, 'message' => 'Plan data has been deleted successfully.');
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'get_lookup_area_code') {
		
				$data = array();
				
				if ($this->input->get('act') == 'get_data') {
		
					$json = array();
					
					$page = $this->input->get('page') ? $this->input->get('page') : 1;
					$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
					$offset = ($page - 1) * $rows;
					$search = $this->input->get('query');
					$sort = $this->input->get('sort') ? $this->input->get('sort') : 'area_code';
					$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
					
					$where = "WHERE active = 1 AND (";
					$where .= " UPPER(area_code) LIKE '%".strtoupper($search)."%'";
					$where .= " OR UPPER(description) LIKE '%".strtoupper($search)."%'";
					$where .= ")";
					
					$sql = "
						SELECT area_id, area_code, description FROM t_mtr_site_area_code
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
					$json['sql'] = $sql;
					
					echo json_encode($json);
					exit;
				}
				
				echo $this->parser->parse('partials/lookup_area_code', $data);
				exit;
			}
		
			if ($this->input->get('cmd') == 'get_area_code') {
			
				$json = array();
				$code = $this->input->get('code');
				
				$res = $this->db
					->query("
						SELECT REPLACE(site_code, SUBSTRING(site_code, 0, 5), '') AS last_id
						FROM t_mtr_site
						WHERE SUBSTRING(site_code, 0, 5) = 'S{$code}'
						ORDER BY site_code DESC
						LIMIT 1
					")
					->row_array();
				
				if ($res) {
					$last_id = ($res['last_id'] + 1);
				} else {
					$last_id = 1;
				}
				
				$json['site_code'] = 'S'.$code . sprintf('%04d', $last_id);
				
				echo json_encode($json);
				exit;
			}
		}
		
		$data['content_id'] = 'plan_info_list';
		
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
			
			if ($this->input->get('load_content') == 'SITE') {
			
				$data = array();
				
				if ($site_id = $this->input->get('site_id')) {
					$data['site'] = $this->core->view_row('t_mtr_site_plan', array('nom_site_id' => $site_id));
				}
				
				echo $this->parser->parse('partials/plan_site_form', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'RF') {
			
				$data = array();
				
				if ($site_id = $this->input->get('site_id')) {
					$data['rf'] = $this->core->view_all('t_mtr_site_plan_rf', array('site_plan_id' => $site_id, 'active' => 1), 'site_plan_rf_id');
				}
				
				echo $this->parser->parse('partials/plan_rf_form', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'TRANSMISSION') {
			
				$data = array();
				
				if ($site_id = $this->input->get('site_id')) {
					$data['transm'] = $this->db
						->query("
							SELECT mw.*, site.site_code
							FROM t_mtr_site_plan_mw mw
							LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_fe
							WHERE mw.active = 1 AND site_plan_id = {$site_id}
							ORDER BY mw.site_plan_mw_id ASC
						")
						->result_array();
				}
				
				echo $this->parser->parse('partials/plan_transmission_form', $data);
				exit;
			}
			
			if ($this->input->post('cmd') == 'ADD_SITE') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['areaCode'])) {
					$error[] = 'Area Code can not empty.';
				}
				if ($this->_is_field_empty($post['siteCode'])) {
					$error[] = 'Site ID can not empty.';
				}
				if ( $this->core->view_row('t_mtr_site_plan', array('nom_site_code' => $post['siteCode'], 'nom_site_id !=' => (int) $post['siteId'], 'active' => 1)) ||
					$this->core->view_row('t_mtr_site', array('site_code' => $post['siteCode'], 'active' => 1)) ) {
					$error[] = 'Site ID is already in used.';
				}
				if ($this->_is_field_empty($post['siteName'])) {
					$error[] = 'Site Name can not empty.';
				}
				if ($this->_is_field_empty($post['nominalLatitude']) || $this->_is_field_empty($post['nominalLongitude'])) {
					$error[] = 'Latitude &amp; Longitude can not empty.';
				}
				if ($this->_is_field_empty($post['searchRadius'])) {
					$error[] = 'Search Radius can not empty.';
				}
				if ($this->_is_field_empty($post['btsType'])) {
					$error[] = 'BTS Type can not unselected.';
				}
				if ($this->_is_field_empty($post['region'])) {
					$error[] = 'Region can not unselected.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->set['nom_site_code'] = $post['siteCode'];
					$this->core->set['nom_site_name'] = $post['siteName'];
					$this->core->set['vendor_id'] = '';
					$this->core->set['nom_latitude'] = (float) $post['nominalLatitude'];
					$this->core->set['nom_longitude'] = (float) $post['nominalLongitude'];
					$this->core->set['search_radius'] = $post['searchRadius'];
					$this->core->set['bts_type'] = $post['btsType'];
					$this->core->set['bts_ant_height'] = (float) $post['RFHeight'];
					$this->core->set['bts_dimension_l'] = (float) $post['RFDimensionL'];
					$this->core->set['bts_dimension_w'] = (float) $post['RFDimensionW'];
					$this->core->set['bts_dimension_h'] = (float) $post['RFDimensionH'];
					$this->core->set['power_requirement'] = $post['powerRequirement'];
					
					$this->core->set['nom_address'] = $post['address'];
					$this->core->set['propinsi_id'] = $post['propinsi'];
					$this->core->set['kabupaten'] = $post['kabupaten'];
					$this->core->set['kelurahan'] = $post['kelurahan'];
					$this->core->set['kecamatan'] = $post['kecamatan'];
					$this->core->set['kode_pos'] = $post['kodePos'];
					
					$this->core->set['region_id'] = $post['region'];
					$this->core->set['cluster_ops_id'] = $post['clusterOpr'];
					$this->core->set['cluster_net_id'] = $post['clusterNet'];
					$this->core->set['cluster_com_id'] = $post['clusterCom'];
					
					$this->core->set['active'] = 1;
					
					$this->core->db_debug = TRUE;
					
					$site_id = 0;
					
					if ( ! $this->core->view_row('t_mtr_site_plan', array('nom_site_id' => (int) $post['siteId'])) ) {
						$this->core->set['created_on'] = $created_on;
						$this->core->set['created_by'] = $created_by;
						$this->core->set['updated_on'] = $updated_on;
						$this->core->set['updated_by'] = $updated_by;
					
						$this->core->save('t_mtr_site_plan');
						
						$site_id = $this->db->insert_id();
						
						// add joega data jang di t_mtr_site
						$this->core->set['site_id_plan'] = $site_id;
						$this->core->set['site_code'] = $post['siteCode'];
						$this->core->set['site_name'] = $post['siteName'];
						$this->core->set['nom_latitude'] = (float) $post['nominalLatitude'];
						$this->core->set['nom_longitude'] = (float) $post['nominalLongitude'];
						$this->core->set['site_address'] = nl2br($post['address']);
						$this->core->set['propinsi_id'] = $post['propinsi'];
						$this->core->set['kabupaten'] = $post['kabupaten'];
						$this->core->set['kecamatan'] = $post['kecamatan'];
						$this->core->set['kelurahan'] = $post['kelurahan'];
						$this->core->set['kode_pos'] = $post['kodePos'];					
						$this->core->set['region_id'] = $post['region'];
						$this->core->set['cluster_ops_id'] = $post['clusterOpr'];
						$this->core->set['cluster_net_id'] = $post['clusterNet'];
						$this->core->set['cluster_com_id'] = $post['clusterCom'];
						$this->core->set['updated_on'] = $updated_on;
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['created_on'] = $created_on;
						$this->core->set['created_by'] = $created_by;
						$this->core->set['status'] = 0;
						$this->core->set['active'] = 1;
						$this->core->set['area_id'] = $post['areaCode2'];
						
						$this->core->save('t_mtr_site');
						
						$message = 'New Site has been added successfully.';
					} else {
						$this->core->set['updated_on'] = $updated_on;
						$this->core->set['updated_by'] = $updated_by;
						
						$this->core->save('t_mtr_site_plan', array('nom_site_id' => $post['siteId']));
						
						$site_id = $post['siteId'];
						$message = 'Site has been updated successfully.';
					}
					
					$json = array('error' => FALSE, 'message' => $message, 'site_id' => $site_id);
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->post('cmd') == 'ADD_RF') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Site ID can not empty.';
				}
				if ($this->_is_field_empty($post['antennaType'])) {
					$error[] = 'Antenna Type can not unselected';
				}
				if ($this->_is_field_empty($post['height'])) {
					$error[] = 'Height can not empty.';
				}
				if ($this->_is_field_empty($post['dimensionL']) || $this->_is_field_empty($post['dimensionW']) || $this->_is_field_empty($post['dimensionH'])) {
					$error[] = 'Dimension can not empty.';
				}
				if ($this->_is_field_empty($post['weight'])) {
					$error[] = 'Weight can not empty.';
				}
				
				if (count($error) == 0) {
					
					$this->core->db_debug = TRUE;
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s.u');
					
					$this->core->update('t_mtr_site_plan_rf', array('active' => 0), array('site_plan_id' => $post['siteId']));
					
					if (@is_array($post['id'])) {
						foreach ($post['id'] as $idx => $val) {
							$this->core->set['site_plan_id'] = $post['siteId'];
							$this->core->set['antenna'] = $post['antennaType'][$idx];
							$this->core->set['height'] = $post['height'][$idx];
							$this->core->set['dimension_l'] = $post['dimensionL'][$idx];
							$this->core->set['dimension_w'] = $post['dimensionW'][$idx];
							$this->core->set['dimension_h'] = $post['dimensionH'][$idx];
							$this->core->set['weight'] = $post['weight'][$idx];
							$this->core->set['active'] = 1;
							
							if ( $this->core->view_row('t_mtr_site_plan_rf', array('site_plan_rf_id' => (int) $post['id'][$idx])) ) {
								$this->core->set['updated_on'] = $updated_on;
								$this->core->set['updated_by'] = $updated_by;
							
								$this->core->save('t_mtr_site_plan_rf', array('site_plan_rf_id' => $post['id'][$idx]));
							} else {
								$this->core->set['created_on'] = $created_on;
								$this->core->set['created_by'] = $created_by;
								
								$this->core->save('t_mtr_site_plan_rf');
							}
						}
					}
					
					$json = array('error' => FALSE, 'message' => 'Antenna RF has been added successfully.', 'site_id' => $post['siteId']);
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->post('cmd') == 'ADD_TRANSMISSION') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Site ID can not empty.';
				}
				if ($this->_is_field_empty(@$post['farEnd']) && $this->_is_field_empty(@$post['farEndCode'])) {
					$error[] = 'Far End can not empty.';
				}
				if ($this->_is_field_empty(@$post['linkType'])) {
					$error[] = 'Link Type can not unselected.';
				}
				if ($this->_is_field_empty(@$post['antHeight'])) {
					$error[] = 'Main Ant. Height can not empty.';
				}
				if ($this->_is_field_empty(@$post['antDiameter'])) {
					$error[] = 'Main Ant. Diameter can not empty.';
				}
				if ($this->_is_field_empty(@$post['antFrequency'])) {
					$error[] = 'Main Ant. Frequency can not empty.';
				}
				if ($this->_is_field_empty(@$post['eqModel'])) {
					$error[] = 'Equipment Model can not unselected.';
				}
				if ($this->_is_field_empty(@$post['antCapacity'])) {
					$error[] = 'Capacity can not empty.';
				}
				if ($this->_is_field_empty(@$post['antOwner'])) {
					$error[] = 'Owner can not empty.';
				}
				
				if (count($error) == 0) {
					
					$this->core->db_debug = TRUE;
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s.u');
					
					$this->core->update('t_mtr_site_plan_mw', array('active' => 0), array('site_plan_id' => $post['siteId']));
					
					if (@is_array($post['id'])) {
						foreach ($post['id'] as $idx => $val) {
							$this->core->set['site_plan_id'] = $post['siteId'];
							$this->core->set['site_id_fe'] = $post['farEnd'][$idx];
							$this->core->set['link_type'] = $post['linkType'][$idx];
							$this->core->set['ant_height'] = $post['antHeight'][$idx];
							$this->core->set['ant_diameter'] = $post['antDiameter'][$idx];
							$this->core->set['frequency'] = $post['antFrequency'][$idx];
							$this->core->set['equip_model'] = $post['eqModel'][$idx];
							$this->core->set['capacity'] = $post['antCapacity'][$idx];
							$this->core->set['owner'] = $post['antOwner'][$idx];
							$this->core->set['active'] = 1;
							
							if ( $this->core->view_row('t_mtr_site_plan_mw', array('site_plan_mw_id' => (int) $post['id'][$idx])) ) {
								$this->core->set['updated_on'] = $updated_on;
								$this->core->set['updated_by'] = $updated_by;
							
								$this->core->save('t_mtr_site_plan_mw', array('site_plan_mw_id' => $post['id'][$idx]));
							} else {
								$this->core->set['created_on'] = $created_on;
								$this->core->set['created_by'] = $created_by;
								
								$this->core->save('t_mtr_site_plan_mw');
							}
						}
					}
					
					$json = array('error' => FALSE, 'message' => 'Transmission has been added successfully.', 'site_id' => $post['siteId']);
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		
		}
		
		$this->_plan_info_form();
	}
	
	public function edit($site_id = 0)
	{
		$this->_check_valid_loggedin();
		
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_edit')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		if (empty($site_id)) {
			show_404();
		}
		
		$site_id = (int) $this->core->decode($site_id);
		
		if ( ! $this->core->view_row('t_mtr_site_plan', array('nom_site_id' => $site_id)) ) {
			show_404();
		}
		
		if ($this->core->is_ajax()) {
			
			if ($this->input->get('load_content') == 'SITE') {
			
				$data['site'] = $this->core->view_row('t_mtr_site_plan', array('nom_site_id' => $site_id));
				
				echo $this->parser->parse('partials/plan_site_form', $data);
				exit;			
			}
			
			if ($this->input->get('load_content') == 'RF') {
			
				$data['rf'] = $this->core->view_all('t_mtr_site_plan_rf', array('site_plan_id' => $site_id, 'active' => 1), 'site_plan_rf_id');
				
				echo $this->parser->parse('partials/plan_rf_form', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'TRANSMISSION') {
			
				$data['transm'] = $this->db
					->query("
						SELECT mw.*, site.site_code
						FROM t_mtr_site_plan_mw mw
						LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_fe
						WHERE mw.active = 1 AND site_plan_id = {$site_id}
						ORDER BY mw.site_plan_mw_id ASC
					")
					->result_array();
				
				echo $this->parser->parse('partials/plan_transmission_form', $data);
				exit;
			}
			
			if ($this->input->post('cmd') == 'EDIT_SITE') { // General Ingpoh
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId']) || $this->_is_field_empty($post['siteCode'])) {
					$error[] = 'Site ID can not empty.';
				}
				if ( $this->core->view_row('t_mtr_site_plan', array('nom_site_code' => $post['siteCode'], 'nom_site_id !=' => $post['siteId'], 'active' => 1)) ) {
					$error[] = 'Site ID is already in used.';
				}
				if ($this->_is_field_empty($post['siteName'])) {
					$error[] = 'Site Name can not empty.';
				}
				if ($this->_is_field_empty($post['nominalLatitude']) || $this->_is_field_empty($post['nominalLongitude'])) {
					$error[] = 'Latitude &amp; Longitude can not empty.';
				}
				if ($this->_is_field_empty($post['searchRadius'])) {
					$error[] = 'Search Radius can not empty.';
				}
				if ($this->_is_field_empty($post['btsType'])) {
					$error[] = 'BTS Type can not unselected.';
				}
				if ($this->_is_field_empty($post['region'])) {
					$error[] = 'Region can not unselected.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->set['nom_site_code'] = $post['siteCode'];
					$this->core->set['nom_site_name'] = $post['siteName'];
					$this->core->set['nom_latitude'] = (float) $post['nominalLatitude'];
					$this->core->set['nom_longitude'] = (float) $post['nominalLongitude'];
					$this->core->set['search_radius'] = $post['searchRadius'];
					$this->core->set['bts_type'] = $post['btsType'];
					$this->core->set['bts_ant_height'] = (float) $post['RFHeight'];
					$this->core->set['bts_dimension_l'] = (float) $post['RFDimensionL'];
					$this->core->set['bts_dimension_w'] = (float) $post['RFDimensionW'];
					$this->core->set['bts_dimension_h'] = (float) $post['RFDimensionH'];
					$this->core->set['power_requirement'] = $post['powerRequirement'];
					
					$this->core->set['nom_address'] = $post['address'];
					$this->core->set['propinsi_id'] = $post['propinsi'];
					$this->core->set['kabupaten'] = $post['kabupaten'];
					$this->core->set['kelurahan'] = $post['kelurahan'];
					$this->core->set['kecamatan'] = $post['kecamatan'];
					$this->core->set['kode_pos'] = $post['kodePos'];
					
					$this->core->set['region_id'] = $post['region'];
					$this->core->set['cluster_ops_id'] = $post['clusterOpr'];
					$this->core->set['cluster_net_id'] = $post['clusterNet'];
					$this->core->set['cluster_com_id'] = $post['clusterCom'];
					
					$this->core->set['active'] = 1;
					$this->core->set['updated_on'] = $updated_on;
					$this->core->set['updated_by'] = $updated_by;
					
					$this->core->db_debug = TRUE;
					
					$this->core->save('t_mtr_site_plan', array('nom_site_id' => $post['siteId']));
					
					// update joega data jang di t_mtr_site
					$this->core->set['site_id_plan'] = $post['siteId'];
					$this->core->set['site_code'] = $post['siteCode'];
					$this->core->set['site_name'] = $post['siteName'];
					$this->core->set['nom_latitude'] = (float) $post['nominalLatitude'];
					$this->core->set['nom_longitude'] = (float) $post['nominalLongitude'];
					$this->core->set['site_address'] = nl2br($post['address']);
					$this->core->set['propinsi_id'] = $post['propinsi'];
					$this->core->set['kabupaten'] = $post['kabupaten'];
					$this->core->set['kecamatan'] = $post['kecamatan'];
					$this->core->set['kelurahan'] = $post['kelurahan'];
					$this->core->set['kode_pos'] = $post['kodePos'];					
					$this->core->set['region_id'] = $post['region'];
					$this->core->set['cluster_ops_id'] = $post['clusterOpr'];
					$this->core->set['cluster_net_id'] = $post['clusterNet'];
					$this->core->set['cluster_com_id'] = $post['clusterCom'];
					$this->core->set['updated_on'] = $updated_on;
					$this->core->set['updated_by'] = $updated_by;
					$this->core->set['status'] = 0;
					$this->core->set['active'] = 1;
					
					$this->core->save('t_mtr_site', array('site_id_plan' => $post['siteId']));
					
					$json = array('error' => FALSE, 'message' => 'Site has been updated successfully.', 'site_id' => $post['siteId']);
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->post('cmd') == 'EDIT_RF') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Site ID can not empty.';
				}
				if ($this->_is_field_empty($post['antennaType'])) {
					$error[] = 'Antenna Type can not unselected';
				}
				if ($this->_is_field_empty($post['height'])) {
					$error[] = 'Height can not empty.';
				}
				if ($this->_is_field_empty($post['dimensionL']) || $this->_is_field_empty($post['dimensionW']) || $this->_is_field_empty($post['dimensionH'])) {
					$error[] = 'Dimension can not empty.';
				}
				if ($this->_is_field_empty($post['weight'])) {
					$error[] = 'Weight can not empty.';
				}
				
				if (count($error) == 0) {
					
					$this->core->db_debug = TRUE;
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s.u');
					
					$this->core->update('t_mtr_site_plan_rf', array('active' => 0), array('site_plan_id' => $post['siteId']));
					
					if (@is_array($post['id'])) {
						foreach ($post['id'] as $idx => $val) {
							$this->core->set['site_plan_id'] = $post['siteId'];
							$this->core->set['antenna'] = $post['antennaType'][$idx];
							$this->core->set['height'] = $post['height'][$idx];
							$this->core->set['dimension_l'] = $post['dimensionL'][$idx];
							$this->core->set['dimension_w'] = $post['dimensionW'][$idx];
							$this->core->set['dimension_h'] = $post['dimensionH'][$idx];
							$this->core->set['weight'] = $post['weight'][$idx];
							$this->core->set['active'] = 1;
							
							if ( $this->core->view_row('t_mtr_site_plan_rf', array('site_plan_rf_id' => (int) $post['id'][$idx])) ) {
								$this->core->set['updated_on'] = $updated_on;
								$this->core->set['updated_by'] = $updated_by;
							
								$this->core->save('t_mtr_site_plan_rf', array('site_plan_rf_id' => $post['id'][$idx]));
							} else {
								$this->core->set['created_on'] = $created_on;
								$this->core->set['created_by'] = $created_by;
								
								$this->core->save('t_mtr_site_plan_rf');
							}
						}
					}
					
					$json = array('error' => FALSE, 'message' => 'Antenna RF has been updated successfully.', 'site_id' => $post['siteId']);
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->post('cmd') == 'EDIT_TRANSMISSION') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Site ID can not empty.';
				}
				if ($this->_is_field_empty(@$post['farEnd']) && $this->_is_field_empty(@$post['farEndCode'])) {
					$error[] = 'Far End can not empty.';
				}
				if ($this->_is_field_empty(@$post['linkType'])) {
					$error[] = 'Link Type can not unselected.';
				}
				if ($this->_is_field_empty(@$post['antHeight'])) {
					$error[] = 'Main Ant. Height can not empty.';
				}
				if ($this->_is_field_empty(@$post['antDiameter'])) {
					$error[] = 'Main Ant. Diameter can not empty.';
				}
				if ($this->_is_field_empty(@$post['antFrequency'])) {
					$error[] = 'Main Ant. Frequency can not empty.';
				}
				if ($this->_is_field_empty(@$post['eqModel'])) {
					$error[] = 'Equipment Model can not unselected.';
				}
				if ($this->_is_field_empty(@$post['antCapacity'])) {
					$error[] = 'Capacity can not empty.';
				}
				if ($this->_is_field_empty(@$post['antOwner'])) {
					$error[] = 'Owner can not empty.';
				}
				
				if (count($error) == 0) {
					
					$this->core->db_debug = TRUE;
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s.u');
					
					$this->core->update('t_mtr_site_plan_mw', array('active' => 0), array('site_plan_id' => $post['siteId']));
					
					if (@is_array($post['id'])) {
						foreach ($post['id'] as $idx => $val) {
							$this->core->set['site_plan_id'] = $post['siteId'];
							$this->core->set['site_id_fe'] = $post['farEnd'][$idx];
							$this->core->set['link_type'] = $post['linkType'][$idx];
							$this->core->set['ant_height'] = $post['antHeight'][$idx];
							$this->core->set['ant_diameter'] = $post['antDiameter'][$idx];
							$this->core->set['frequency'] = $post['antFrequency'][$idx];
							$this->core->set['equip_model'] = $post['eqModel'][$idx];
							$this->core->set['capacity'] = $post['antCapacity'][$idx];
							$this->core->set['owner'] = $post['antOwner'][$idx];
							$this->core->set['active'] = 1;
							
							if ( $this->core->view_row('t_mtr_site_plan_mw', array('site_plan_mw_id' => (int) $post['id'][$idx])) ) {
								$this->core->set['updated_on'] = $updated_on;
								$this->core->set['updated_by'] = $updated_by;
							
								$this->core->save('t_mtr_site_plan_mw', array('site_plan_mw_id' => $post['id'][$idx]));
							} else {
								$this->core->set['created_on'] = $created_on;
								$this->core->set['created_by'] = $created_by;
								
								$this->core->save('t_mtr_site_plan_mw');
							}
						}
					}
					
					$json = array('error' => FALSE, 'message' => 'Transmission has been updated successfully.', 'site_id' => $post['siteId']);
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		
		}
		
		$this->_plan_info_form($site_id);
	}
	
	public function view($site_id = 0)
	{
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_view')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$site_id = (int) $this->core->decode($site_id);
		
		if ( ! $this->core->view_row('t_mtr_site_plan', array('nom_site_id' => $site_id)) ) {
			show_404();
		}
		
		if ($this->core->is_ajax()) {
			
			if ($this->input->get('load_content') == 'SITE_VIEW') {
			
				$data['site'] = $this->core->view_row('t_mtr_site_plan', array('nom_site_id' => $site_id));
				
				echo $this->parser->parse('partials/plan_site_view', $data);
				exit;			
			}
			
			if ($this->input->get('load_content') == 'RF_VIEW') {
			
				$data['rf'] = $this->core->view_all('t_mtr_site_plan_rf', array('site_plan_id' => $site_id, 'active' => 1), 'site_plan_rf_id');
				
				echo $this->parser->parse('partials/plan_rf_view', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'TRANSMISSION_VIEW') {
			
				$data['transm'] = $this->db
					->query("
						SELECT mw.*, site.site_code
						FROM t_mtr_site_plan_mw mw
						LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_fe
						WHERE mw.active = 1 AND site_plan_id = {$site_id}
						ORDER BY mw.site_plan_mw_id ASC
					")
					->result_array();
				
				echo $this->parser->parse('partials/plan_transmission_view', $data);
				exit;
			}
		}
		
		$data['plan'] = $this->core->view_row('t_mtr_site_plan', array('nom_site_id' => $site_id));
		$data['content_id'] = 'plan_info_form_view';
		
		$this->load->view('template/smashing', $data);
	}
	
	private function _plan_info_form($id = 0)
	{
		if ($this->uri->segment(3) == 'edit') {
			$data['plan'] = $this->core->view_row('t_mtr_site_plan', array('nom_site_id' => $id));
		}
		
		$data['content_id'] = 'plan_info_form';
		
		$this->load->view('template/smashing', $data);
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