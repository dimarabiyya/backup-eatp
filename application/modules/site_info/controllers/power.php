<?php

class Power extends MX_Controller {

	private $_siteinfo_dir;
	private $_power_dir;
	private $_power_cache_dir;
	private $_power_url;
	private $_partials;
	
	public function __construct()
	{
		$this->load->library('parser');
		
		$this->load->model('power_model');
		$this->load->model('image_model');
		$this->load->model('site_info_model');
		
		$siteinfo_dir = FCPATH.'file/upload/site-info';
		$this->_siteinfo_dir = $siteinfo_dir;
		if ( ! is_dir($siteinfo_dir)) {
			mkdir($siteinfo_dir, 0777);
		}
		
		$this->_power_dir = FCPATH.'file/upload/site-info/power-equipment';
		if ( ! is_dir($this->_power_dir)) {
			mkdir($this->_power_dir, 0777);
		}
		
		$this->_power_cache_dir = FCPATH.'file/upload/cache/site-info/power-equipment';
		$this->_power_url = base_url('/file/upload/site-info/power-equipment');
		
		$this->_partials['power_dir'] = $this->_power_dir;
		$this->_partials['power_url'] = $this->_power_url;
		
		$uri = $this->uri->segment(1).'/'.$this->uri->segment(2);
		$this->_partials['menu'] = $this->mmenu->getMenuId($uri);
	}
	
	public function index()
	{
		$this->_check_valid_loggedin();
		
		$data = $this->_partials;
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->get('cmd') == 'LOAD_DATA_RECTIFIER') {
			
				$json = array();
				
				$data = $this->power_model->load_rectifier();
				
				$json = $data;
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'LOAD_DATA_ACPDB') {
			
				$json = array();
				
				$data = $this->power_model->load_acpdb();
				
				$json = $data;
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'LOAD_DATA_DCPDB') {
			
				$json = array();
				
				$data = $this->power_model->load_dcpdb();
				
				$json = $data;
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'LOAD_DATA_BATTERY') {
			
				$json = array();
				
				$data = $this->power_model->load_battery();
				
				$json = $data;
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'GET_SITE') {
			
				$json = array();
				$site_id = $this->input->get('site_id');
				
				if ($site_id) {
					$sql = "SELECT site_id, site_code, site_name, vendor_id, vendor_name FROM t_mtr_site WHERE site_id = {$site_id} ORDER BY site_code ASC";
				} else {
					$sql = "SELECT site_id, site_code, site_name, vendor_id, vendor_name FROM t_mtr_site ORDER BY site_code ASC LIMIT 20";
				}
				
				$results = $this->db
						->query($sql)
						->result_array();
				
				if ($this->input->get('q')) {
					$q = $this->input->get('q');
					
					$results = $this->db
						->query("SELECT site_id, site_code, site_name, vendor_id, vendor_name FROM t_mtr_site WHERE UPPER(site_code) LIKE '%".strtoupper($q)."%' OR UPPER(site_name) LIKE '%".strtoupper($q)."%' OR UPPER(vendor_name) LIKE '%".strtoupper($q)."%'")
						->result_array();
				}
				
				$json = $results;
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_DATAGRID_POWER') {
			
				$json = array();
			
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$search = $this->input->get('query');
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'site_code';
				$order = $this->input->get('order') ? $this->input->get('order') : 'ASC';
				
				$where = "
					WHERE (
						s.site_id IN (SELECT site_id FROM t_mtr_site_l_acpdb WHERE \"active\" = 1)
						OR s.site_id IN (SELECT site_id FROM t_mtr_site_l_dcpdb WHERE \"active\" = 1)
						OR s.site_id IN (SELECT site_id FROM t_mtr_site_l_rectifier WHERE \"active\" = 1)
						OR s.site_id IN (SELECT site_id FROM t_mtr_site_l_battery WHERE \"active\" = 1)
					) AND
				";
				$where .= "(UPPER(s.site_code) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(s.site_code_old) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(s.site_name) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(s.vendor_id) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(r.region_name) LIKE '%".strtoupper($search)."%'";
				$where .= "OR UPPER(lok.nama) LIKE '%".strtoupper($search)."%')";
				
				$sql = "
					SELECT s.site_id, s.site_code, s.site_code_old, s.site_name, s.vendor_id, r.region_name, lok.nama AS city
					FROM t_mtr_site s
					LEFT JOIN t_mtr_lokasi lok ON lok.lokasi_id = s.kabupaten AND UPPER(lok.jenis) = 'KB' AND lok.status = 1
					LEFT JOIN t_mtr_region r ON r.region_id = s.region_id
					{$where}
					GROUP BY s.site_id, r.region_name, s.site_code,s.site_code_old,s.site_name,s.vendor_id,lok.nama
					ORDER BY  {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				
				$rows = array();
				foreach ($query->result_array() as $r) {
					$r['site_id'] = $this->core->encode($r['site_id']);
					$rows[] = $r;
				}
				
				$json['rows'] = $rows;				
				$json['sql'] = str_replace(array("\n", "\t"), ' ', $sql);
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'DELETE_POWER') {
			
				$json = array();
				
				$id = (int) $this->core->decode($this->input->get('id'));
				
				$sql[] = "UPDATE t_mtr_site_l_acpdb SET \"active\" = 0 WHERE site_id = {$id}";
				$sql[] = "UPDATE t_mtr_site_l_rectifier SET \"active\" = 0 WHERE site_id = {$id}";
				$sql[] = "UPDATE t_mtr_site_l_dcpdb SET \"active\" = 0 WHERE site_id = {$id}";
				$sql[] = "UPDATE t_mtr_site_l_battery SET \"active\" = 0 WHERE site_id = {$id}";
				
				foreach ($sql as $query) {
					$this->db->query($query);
				}
				
				$json = array('error' => FALSE, 'message' => 'Power data has been deleted successfully.', 'sqls' => $sql);
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_DATAGRID_ACPDB') {
			
				$json = array();
			
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$search = $this->input->get('query');
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'acpdb.updated_on';
				$order = $this->input->get('order') ? $this->input->get('order') : 'DESC';
				$site_id = (int) $this->input->get('site_id');
				
				$where = "
					WHERE acpdb.site_id = {$site_id} AND acpdb.active = 1 AND
					(UPPER(CAST(acpdb.capacity_r AS VARCHAR)) LIKE '%".strtoupper($search)."%'
					OR UPPER(CAST(acpdb.capacity_s AS VARCHAR)) LIKE '%".strtoupper($search)."%'
					OR UPPER(CAST(acpdb.capacity_t AS VARCHAR)) LIKE '%".strtoupper($search)."%'
					OR UPPER(look1.lookup_desc) LIKE '%".strtoupper($search)."%'
					OR UPPER(look3.lookup_desc) LIKE '%".strtoupper($search)."%')
				";
				
				$sql = "
					SELECT acpdb.acpdb_id, acpdb.capacity_r, acpdb.capacity_s, acpdb.capacity_t, look1.lookup_desc AS mcb_capacity_tp, look2.lookup_desc AS mcb_capacity, look3.lookup_desc AS mcb_capacity_acpdb, look4.lookup_desc AS acpdb_mcb_capacity
					FROM t_mtr_site_l_acpdb acpdb 
					LEFT JOIN t_mtr_lookup look1 ON look1.lookup_value = acpdb.mcb_capacity_tp::VARCHAR AND look1.lookup_key = 'power_capacity'
					LEFT JOIN t_mtr_lookup look2 ON look2.lookup_value = acpdb.mcb_capacity::VARCHAR AND look2.lookup_key = 'power_phase'
					LEFT JOIN t_mtr_lookup look3 ON look3.lookup_value = acpdb.mcb_capacity_acpdb::VARCHAR AND look3.lookup_key = 'power_capacity'
					LEFT JOIN t_mtr_lookup look4 ON look4.lookup_value = acpdb.acpdb_mcb_capacity::VARCHAR AND look4.lookup_key = 'power_phase'
					{$where}
					ORDER BY {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json = array();
				
				$json['total'] = $total_rows;
				
				$rows = array();
				foreach ($query->result_array() as $r) {
					$r['acpdb_id'] = $this->core->encode($r['acpdb_id']);
					
					$sql = "
						SELECT lo.lookup_value, lo.lookup_desc, pho.acpdb_id, pho.photo_name FROM t_mtr_lookup lo
						LEFT JOIN t_mtr_site_l_acpdb_photo pho ON CAST(pho.type_id AS VARCHAR) = lo.lookup_value AND pho.acpdb_id = ".(int) $this->core->decode($r['acpdb_id'])."
						WHERE lo.lookup_key = 'power_acpdb_photo_type' AND lo.active = 1
						ORDER BY lo.\"order\" ASC
					";
					$query = $this->db->query($sql);
					
					foreach ($query->result_array() as $res) {
						//$thumb = '';
						//if (file_exists($this->_power_dir.'/'.$res['photo_name']) && is_file($this->_power_dir.'/'.$res['photo_name'])) {
						//	$thumb = $this->image_model->resize($this->_power_dir.'/'.$res['photo_name'], 30, 30, 'crop');
						//}
						
						$r['photo']['large'][$res['lookup_value']] = $this->_power_url.'/'.$res['photo_name'];
						$r['photo'][$res['lookup_value']] = $this->image_model->resize($this->_power_dir.'/'.$res['photo_name'], 30, 30, 'crop');
					}
					
					$rows[] = $r;
				}
				
				$json['rows'] = $rows;				
				
				$sql = str_replace("\t", '', $sql);
				$sql = str_replace("\n", ' ', $sql);
				$json['sql'] = $sql;
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_DATAGRID_ACPDB_DETAIL') {
			
				$json = array();
			
				$acpdb_id = (int) $this->core->decode($this->input->get('acpdb_id'));
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'look1.order';
				$order = $this->input->get('order') ? $this->input->get('order') : 'ASC';
				
				$where = "WHERE acpdb_detail.acpdb_id = {$acpdb_id}";
				
				$sql = "
					SELECT look1.lookup_desc AS item, acpdb_detail.capacity, acpdb_detail.capacity_actual, look2.lookup_desc AS phase_used, look3.lookup_desc AS allocation, acpdb_detail.linked_to_rectifier_no
					FROM t_mtr_site_l_acpdb_detail acpdb_detail
					LEFT JOIN t_mtr_lookup look1 ON look1.lookup_value = acpdb_detail.item::VARCHAR AND look1.lookup_key = 'power_mcb_item'
					LEFT JOIN t_mtr_lookup look2 ON look2.lookup_value = acpdb_detail.phase_used::VARCHAR AND look2.lookup_key = 'power_phase'
					LEFT JOIN t_mtr_lookup look3 ON look3.lookup_value = acpdb_detail.phase_used::VARCHAR AND look3.lookup_key = 'power_allocation'
					{$where}
					ORDER BY {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				
				$rows = array();
				foreach ($query->result_array() as $r) {
					$r['capacity'] = $r['capacity'].' Amp(AC)';
					$r['capacity_actual'] = $r['capacity_actual'].' Amp(AC)';
					$rows[] = $r;
				}
				
				$json['rows'] = $rows;
				$json['sql'] = str_replace(array("\n", "\t"), ' ', $sql);
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'DELETE_ACPDB') {
			
				$json = array();
				
				$id = (int) $this->core->decode($this->input->get('id'));
				
				$sql = "UPDATE t_mtr_site_l_acpdb SET \"active\" = 0 WHERE acpdb_id = {$id}";
				
				$this->db->query($sql);
				
				$json = array('error' => FALSE, 'message' => 'ACPDB data has been deleted successfully.', 'sqls' => $sql);
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_DATAGRID_RECTIFIER') {
			
				$json = array();
				
				$site_id = (int) $this->input->get('site_id');
				$search = $this->input->get('query');
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'rec.updated_on';
				$order = $this->input->get('order') ? $this->input->get('order') : 'DESC';
				
				$where = "WHERE rec.site_id = {$site_id} AND rec.active = 1 AND (";
				$where .= " UPPER(lo1.lookup_desc) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(lo2.lookup_desc) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(lo3.lookup_desc) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(lo4.lookup_desc) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(CAST(rec.capacity_r AS VARCHAR)) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(CAST(rec.capacity_s AS VARCHAR)) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(CAST(rec.capacity_t AS VARCHAR)) LIKE '%".strtoupper($search)."%'";
				$where .= ")";
				
				$sql = "
					SELECT rec.rectifier_id, rec.rectifier_no, rec.slot_used, lo3.lookup_desc AS dc_power, rec.dcdc_power_capacity, lo1.lookup_desc AS mcb_capacity_tp, lo2.lookup_desc AS mcb_capacity, rec.capacity_r, rec.capacity_s, rec.capacity_t
					FROM t_mtr_site_l_rectifier rec
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(rec.mcb_capacity_tp AS VARCHAR) AND lo1.lookup_key = 'power_capacity' AND lo1.active = 1
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(rec.mcb_capacity AS VARCHAR) AND lo2.lookup_key = 'power_phase' AND lo2.active = 1
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(rec.dc_power AS VARCHAR) AND lo3.lookup_key = 'power_dc' AND lo3.active = 1
					LEFT JOIN t_mtr_lookup lo4 ON lo4.lookup_value = CAST(rec.rectifier_no AS VARCHAR) AND lo4.lookup_key = 'rectifier_number' AND lo4.active = 1
					{$where}
					ORDER BY {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				
				$rows = array();
				foreach ($query->result_array() as $r) {
					$sql = "
						SELECT lo.lookup_value, lo.lookup_desc, pho.rectifier_id, pho.photo_name FROM t_mtr_lookup lo
						LEFT JOIN t_mtr_site_l_rectifier_photo pho ON CAST(pho.type_id AS VARCHAR) = lo.lookup_value AND pho.rectifier_id = ".$r['rectifier_id']."
						WHERE lo.lookup_key = 'power_rectifier_photo_type' AND lo.active = 1
						ORDER BY lo.\"order\" ASC
					";
					$query = $this->db->query($sql);
					
					foreach ($query->result_array() as $res) {
						$r['photo']['large'][$res['lookup_value']] = $this->_power_url.'/'.$res['photo_name'];
						$r['photo'][$res['lookup_value']] = $this->image_model->resize($this->_power_dir.'/'.$res['photo_name'], 60, 60, 'crop');
					}
					
					$rows[] = $r;
				}
				$json['rows'] = $rows;
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'GET_DATAGRID_RECTIFIER_DETAIL') {
			
				$json = array();
				
				$rectifier_id = (int) $this->input->get('rectifier_id');
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'item';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
				
				$sql = "
					SELECT lo1.lookup_desc AS item, lo2.lookup_desc AS capacity, recdet.capacity_actual, lo3.lookup_desc AS llvd_info, lo4.lookup_desc AS allocation, site.site_code, lo5.lookup_desc AS system 
					FROM t_mtr_site_l_rectifier_detail recdet
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(recdet.item AS VARCHAR) AND lo1.lookup_key = 'power_mcb_item'
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(recdet.capacity AS VARCHAR) AND lo2.lookup_key = 'power_capacity'
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(recdet.llvd_info AS VARCHAR) AND lo3.lookup_key = 'llvd_info'
					LEFT JOIN t_mtr_lookup lo4 ON lo4.lookup_value = CAST(recdet.allocation AS VARCHAR) AND lo4.lookup_key = 'power_allocation'
					LEFT JOIN t_mtr_site site ON site.site_id = recdet.fe_id
					LEFT JOIN t_mtr_lookup lo5 ON lo5.lookup_value = CAST(recdet.system AS VARCHAR) AND lo5.lookup_key = 'power_system'
					WHERE recdet.rectifier_id = {$rectifier_id}
					ORDER BY {$sort} {$order}
				";
				
				$total_rows = $this->db->query($sql)->num_rows();
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				$json['rows'] = $query->result_array();
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'DELETE_RECTIFIER') {
			
				$json = array();
				
				$id = (int) $this->input->get('id');
				
				$sql = "UPDATE t_mtr_site_l_rectifier SET \"active\" = 0 WHERE rectifier_id = {$id}";
				
				$this->db->query($sql);
				
				$json = array('error' => FALSE, 'message' => 'Rectifier data has been deleted successfully.', 'sqls' => $sql);
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'GET_RECTIFIER_DATA') {
			
				$id = $this->input->get('id');
				
				$json = array();
				
				$result = $this->core->view_row('t_mtr_site_l_rectifier', array('rectifier_id' => $id));
				$result['slot_used'] = strpos($result['slot_used'], ',') !== FALSE ? explode(',', $result['slot_used']) : (array) $result['slot_used'];
				
				$sql = "
					SELECT lo.lookup_value, lo.lookup_desc, pho.rectifier_id, pho.photo_name FROM t_mtr_lookup lo
					LEFT JOIN t_mtr_site_l_rectifier_photo pho ON CAST(pho.type_id AS VARCHAR) = lo.lookup_value AND pho.rectifier_id = ".$id."
					WHERE lo.lookup_key = 'power_rectifier_photo_type' AND lo.active = 1
					ORDER BY lo.\"order\" ASC
				";
				$query = $this->db->query($sql);
				
				foreach ($query->result_array() as $res) {
					$result['photo_'.$res['lookup_value']] = (is_file($this->_power_dir.'/'.$res['photo_name']) && file_exists($this->_power_dir.'/'.$res['photo_name'])) ? $res['photo_name'] : '';
					$result['photo_'.$res['lookup_value'].'_thumb'] = (is_file($this->_power_dir.'/'.$res['photo_name']) && file_exists($this->_power_dir.'/'.$res['photo_name'])) ? $this->image_model->resize($this->_power_dir.'/'.$res['photo_name'], 60, 60, 'crop') : base_url('/file/upload/site-info/no_image-60x60.jpg');
					$result['photo_'.$res['lookup_value'].'_large'] = (is_file($this->_power_dir.'/'.$res['photo_name']) && file_exists($this->_power_dir.'/'.$res['photo_name'])) ? $this->_power_url.'/'.$res['photo_name'] : 'javascript:void(0)';
				}
				
				$json['data'] = $result;
				
				echo json_encode($json);
				exit;
			}
			
			/* if ($this->input->get('cmd') == 'GET_RECTIFIER_NO') {
			
				$site_id = $this->input->get('siteId');
				
				$json['data'] = $this->db
					->query("
						SELECT lookup_desc AS rectifier_no
						FROM t_mtr_lookup
						WHERE lookup_key = 'rectifier_number' AND active = 1
						AND lookup_value IN (SELECT CAST(rectifier_no AS VARCHAR) FROM t_mtr_site_l_rectifier WHERE site_id = {$site_id})
					")
					->result_array();
				
				echo json_encode($json);
				exit;
			} */
		
			if ($this->input->get('cmd') == 'GET_DATAGRID_DCPDB') {
			
				$json = array();
				
				$site_id = (int) $this->input->get('site_id');
				$search = $this->input->get('query');
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'dcpdb.updated_on';
				$order = $this->input->get('order') ? $this->input->get('order') : 'DESC';
				
				$where = "WHERE dcpdb.site_id = {$site_id} AND dcpdb.active = 1 AND (";
				$where .= " UPPER(CAST(dcpdb.main_mcb_capacity AS VARCHAR)) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(CAST(dcpdb.cable_diameter AS VARCHAR)) LIKE '%".strtoupper($search)."%'";
				$where .= ")";
				
				$sql = "
					SELECT dcpdb.site_id, dcpdb.rectifier_no, dcpdb.dcpdb_id, dcpdb.main_mcb_capacity, dcpdb.cable_diameter
					FROM t_mtr_site_l_dcpdb dcpdb
					{$where}
					ORDER BY {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				
				$rows = array();
				foreach ($query->result_array() as $r) {
					$sql = "
						SELECT lo.lookup_value, lo.lookup_desc, pho.dcpdb_id, pho.photo_name FROM t_mtr_lookup lo
						LEFT JOIN t_mtr_site_l_dcpdb_photo pho ON CAST(pho.type_id AS VARCHAR) = lo.lookup_value AND pho.dcpdb_id = ".$r['dcpdb_id']."
						WHERE lo.lookup_key = 'power_dcpdb_photo_type' AND lo.active = 1
						ORDER BY lo.\"order\" ASC
					";
					$query = $this->db->query($sql);
					
					foreach ($query->result_array() as $res) {
						$r['photo']['large'][$res['lookup_value']] = $this->_power_url.'/'.$res['photo_name'];
						$r['photo'][$res['lookup_value']] = $this->image_model->resize($this->_power_dir.'/'.$res['photo_name'], 60, 60, 'crop');
					}
					
					$rows[] = $r;
				}
				$json['rows'] = $rows;
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'GET_DATAGRID_DCPDB_DETAIL') {
			
				$json = array();
				
				$dcpdb_id = (int) $this->input->get('dcpdb_id');
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'item';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
				
				$sql = "
					SELECT lo1.lookup_desc AS item, lo2.lookup_desc AS capacity, dcdet.actual_capacity, lo3.lookup_desc AS allocation, site.site_code, lo4.lookup_desc AS system, dcdet.linked_to_rectifier_no
					FROM t_mtr_site_l_dcpdb_detail dcdet
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(dcdet.item AS VARCHAR) AND lo1.lookup_key = 'power_mcb_item'
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(dcdet.capacity AS VARCHAR) AND lo2.lookup_key = 'power_capacity'					
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(dcdet.allocation AS VARCHAR) AND lo3.lookup_key = 'power_allocation'
					LEFT JOIN t_mtr_site site ON site.site_id = dcdet.fe_id
					LEFT JOIN t_mtr_lookup lo4 ON lo4.lookup_value = CAST(dcdet.system AS VARCHAR) AND lo4.lookup_key = 'power_system'
					WHERE dcdet.dcpdb_id = {$dcpdb_id}
					ORDER BY {$sort} {$order}
				";
				
				$total_rows = $this->db->query($sql)->num_rows();
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				$json['rows'] = $query->result_array();
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'DELETE_DCPDB') {
			
				$json = array();
				
				$id = (int) $this->input->get('id');
				
				$sql = "UPDATE t_mtr_site_l_dcpdb SET \"active\" = 0 WHERE dcpdb_id = {$id}";
				
				$this->db->query($sql);
				
				$json = array('error' => FALSE, 'message' => 'DCPDB data has been deleted successfully.', 'sqls' => $sql);
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'GET_DCPDB_DATA') {
			
				$id = $this->input->get('id');
				
				$json = array();
				
				$result = $this->core->view_row('t_mtr_site_l_dcpdb', array('dcpdb_id' => $id));
				
				$sql = "
					SELECT lo.lookup_value, lo.lookup_desc, pho.dcpdb_id, pho.photo_name FROM t_mtr_lookup lo
					LEFT JOIN t_mtr_site_l_dcpdb_photo pho ON CAST(pho.type_id AS VARCHAR) = lo.lookup_value AND pho.dcpdb_id = ".$id."
					WHERE lo.lookup_key = 'power_dcpdb_photo_type' AND lo.active = 1
					ORDER BY lo.\"order\" ASC
				";
				$query = $this->db->query($sql);
				
				foreach ($query->result_array() as $res) {
					$result['photo_'.$res['lookup_value']] = (is_file($this->_power_dir.'/'.$res['photo_name']) && file_exists($this->_power_dir.'/'.$res['photo_name'])) ? $res['photo_name'] : '';
					$result['photo_'.$res['lookup_value'].'_thumb'] = (is_file($this->_power_dir.'/'.$res['photo_name']) && file_exists($this->_power_dir.'/'.$res['photo_name'])) ? $this->image_model->resize($this->_power_dir.'/'.$res['photo_name'], 60, 60, 'crop') : base_url('/file/upload/site-info/no_image-60x60.jpg');
					$result['photo_'.$res['lookup_value'].'_large'] = (is_file($this->_power_dir.'/'.$res['photo_name']) && file_exists($this->_power_dir.'/'.$res['photo_name'])) ? $this->_power_url.'/'.$res['photo_name'] : 'javascript:void(0)';
				}
				
				$json['data'] = $result;
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'GET_DATAGRID_SITEINFO_POWER_ACPDB') { // untuk read only di siteinfo edit mode
			
				$json = array();
				
				$acpdb_id = $this->input->get('acpdb_id');
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$search = $this->input->get('query');
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'acpdb_id';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
				
				$where = "
					WHERE acpdb_det.acpdb_id = {$acpdb_id}
					AND (UPPER(lo1.lookup_desc) LIKE '%".strtoupper($search)."%'
					OR UPPER(lo2.lookup_desc) LIKE '%".strtoupper($search)."%'
					OR UPPER(lo3.lookup_desc) LIKE '%".strtoupper($search)."%'
					OR UPPER(lo4.lookup_desc) LIKE '%".strtoupper($search)."%')
				";
				
				$sql = "
					SELECT lo1.lookup_desc AS item, lo2.lookup_desc AS capacity, acpdb_det.capacity_actual, lo3.lookup_desc AS phase_used, lo4.lookup_desc AS allocation
					FROM t_mtr_site_l_acpdb_detail acpdb_det
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(acpdb_det.item AS VARCHAR) AND lo1.lookup_key = 'power_mcb_item' AND lo1.active = 1
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(acpdb_det.capacity AS VARCHAR) AND lo2.lookup_key = 'power_capacity' AND lo2.active = 1
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(acpdb_det.phase_used AS VARCHAR) AND lo3.lookup_key = 'power_phase' AND lo3.active = 1
					LEFT JOIN t_mtr_lookup lo4 ON lo4.lookup_value = CAST(acpdb_det.allocation AS VARCHAR) AND lo4.lookup_key = 'power_allocation' AND lo4.active = 1
					{$where}
					ORDER BY  {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				
				$rows = array();
				foreach ($query->result_array() as $r) {
					$rows[] = $r;
				}
				
				$json['rows'] = $rows;				
				$json['sql'] = str_replace(array("\n", "\t"), ' ', $sql);
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'GET_DATAGRID_SITEINFO_POWER_RECTIFIER') { // untuk read only di siteinfo edit mode
			
				$json = array();
				
				$site_id = (int) $this->input->get('site_id');
				$search = $this->input->get('query');
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'rectifier_id';
				$order = $this->input->get('order') ? $this->input->get('order') : 'ASC';
				
				$where = "WHERE rec.site_id = {$site_id} AND rec.active = 1 AND (";
				$where .= " UPPER(lo1.lookup_desc) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(lo2.lookup_desc) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(lo3.lookup_desc) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(CAST(rec.capacity_r AS VARCHAR)) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(CAST(rec.capacity_s AS VARCHAR)) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(CAST(rec.capacity_t AS VARCHAR)) LIKE '%".strtoupper($search)."%'";
				$where .= ")";
				
				$sql = "
					SELECT rec.rectifier_id, rec.rectifier_no, rec.slot_used, lo3.lookup_desc AS dc_power, rec.dcdc_power_capacity, lo1.lookup_desc AS mcb_capacity_tp, lo2.lookup_desc AS mcb_capacity, rec.capacity_r, rec.capacity_s, rec.capacity_t
					FROM t_mtr_site_l_rectifier rec
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(rec.mcb_capacity_tp AS VARCHAR) AND lo1.lookup_key = 'power_capacity'
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(rec.mcb_capacity AS VARCHAR) AND lo2.lookup_key = 'power_phase'
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(rec.dc_power AS VARCHAR) AND lo3.lookup_key = 'power_dc'
					{$where}
					ORDER BY {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				
				$rows = array();
				foreach ($query->result_array() as $r) {
					$sql = "
						SELECT lo.lookup_value, lo.lookup_desc, pho.rectifier_id, pho.photo_name FROM t_mtr_lookup lo
						LEFT JOIN t_mtr_site_l_rectifier_photo pho ON CAST(pho.type_id AS VARCHAR) = lo.lookup_value AND pho.rectifier_id = ".$r['rectifier_id']."
						WHERE lo.lookup_key = 'power_rectifier_photo_type' AND lo.active = 1
						ORDER BY lo.\"order\" ASC
					";
					$query = $this->db->query($sql);
					
					foreach ($query->result_array() as $res) {
						$r['photo']['large'][$res['lookup_value']] = $this->_power_url.'/'.$res['photo_name'];
						$r['photo'][$res['lookup_value']] = $this->image_model->resize($this->_power_dir.'/'.$res['photo_name'], 60, 60, 'crop');
					}
					
					$rows[] = $r;
				}
				$json['rows'] = $rows;
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'GET_DATAGRID_SITEINFO_POWER_DCPDB') { // untuk read only di siteinfo edit mode
			
				$json = array();
				
				$site_id = (int) $this->input->get('site_id');
				$search = $this->input->get('query');
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'dcpdb_id';
				$order = $this->input->get('order') ? $this->input->get('order') : 'ASC';
				
				$where = "WHERE dcpdb.site_id = {$site_id} AND dcpdb.active = 1 AND (";
				$where .= " UPPER(CAST(dcpdb.main_mcb_capacity AS VARCHAR)) LIKE '%".strtoupper($search)."%'";
				$where .= " OR UPPER(CAST(dcpdb.cable_diameter AS VARCHAR)) LIKE '%".strtoupper($search)."%'";
				$where .= ")";
				
				$sql = "
					SELECT dcpdb.site_id, dcpdb.dcpdb_id,  dcpdb.rectifier_no, dcpdb.main_mcb_capacity, dcpdb.cable_diameter
					FROM t_mtr_site_l_dcpdb dcpdb
					{$where}
					ORDER BY {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				
				$rows = array();
				foreach ($query->result_array() as $r) {
					$sql = "
						SELECT lo.lookup_value, lo.lookup_desc, pho.dcpdb_id, pho.photo_name FROM t_mtr_lookup lo
						LEFT JOIN t_mtr_site_l_dcpdb_photo pho ON CAST(pho.type_id AS VARCHAR) = lo.lookup_value AND pho.dcpdb_id = ".$r['dcpdb_id']."
						WHERE lo.lookup_key = 'power_dcpdb_photo_type' AND lo.active = 1
						ORDER BY lo.\"order\" ASC
					";
					$query = $this->db->query($sql);
					
					foreach ($query->result_array() as $res) {
						$r['photo']['large'][$res['lookup_value']] = $this->_power_url.'/'.$res['photo_name'];
						$r['photo'][$res['lookup_value']] = $this->image_model->resize($this->_power_dir.'/'.$res['photo_name'], 60, 60, 'crop');
					}
					
					$rows[] = $r;
				}
				$json['rows'] = $rows;
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'GET_DATAGRID_SITEINFO_POWER_BATTERY') { // untuk read only di siteinfo edit mode
			
				$json = array();
				
				$site_id = (int) $this->input->get('site_id');
				$search = $this->input->get('query');
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'lo3.order';
				$order = $this->input->get('order') ? $this->input->get('order') : 'ASC';
				
				$where = "
					WHERE battery.site_id = {$site_id} AND battery.active = 1
					AND (UPPER(lo1.lookup_desc) LIKE '%".strtoupper($search)."%'
					OR CAST(battery.capacity AS VARCHAR) LIKE '%".strtoupper($search)."%'
					OR UPPER(lo2.lookup_desc) LIKE '%".strtoupper($search)."%')
				";
				
				$sql = "
					SELECT lo3.lookup_desc AS mcb_item, lo1.lookup_desc AS brand, battery.capacity, battery.total_bank, battery.total_shunt, battery.weight_per_cell, lo2.lookup_desc AS voltage_per_cell
					FROM t_mtr_site_l_battery battery
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(battery.brand AS VARCHAR) AND lo1.lookup_key = 'battery_brand' AND lo1.active = 1
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(battery.voltage_per_cell AS VARCHAR) AND lo2.lookup_key = 'voltage_per_cell' AND lo2.active = 1
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(battery.mcb_item AS VARCHAR) AND lo3.lookup_key = 'power_mcb_item' AND lo3.active = 1
					{$where}
					ORDER BY {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				
				$rows = array();
				foreach ($query->result_array() as $r) {
					$rows[] = $r;
				}
				$json['rows'] = $rows;
				
				echo json_encode($json);
				exit;
			}
		
		}
		
		$data['content_id'] = 'power_list_view';
		
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
		
			if ($this->input->get('load_content') == 'RECTIFIER') {
			
				$data = array();
				
				echo $this->parser->parse('partials/content_power_rectifier', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'RECTIFIER_DETAIL') {
			
				$id = $this->input->get('rectifier_id');
				
				$data['rectifier_id'] = $id;
				$data['rectifier_detail'] = $this->db
					->query("
						SELECT recdet.*, site.site_code FROM t_mtr_site_l_rectifier_detail recdet
						LEFT JOIN t_mtr_site site ON site.site_id = recdet.fe_id
						WHERE recdet.rectifier_id = {$id}
						ORDER BY item ASC
					")
					->result_array();
				
				echo $this->parser->parse('partials/content_power_rectifier_detail', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'ACPDB') {
			
				$data = array();
				
				echo $this->parser->parse('partials/content_power_acpdb', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'DCPDB') {
			
				$data = array();
				
				echo $this->parser->parse('partials/content_power_dcpdb', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'DCPDB_DETAIL') {
			
				$id = $this->input->get('dcpdb_id');
				
				$data['dcpdb_id'] = $id;
				$data['dcpdb_detail'] = $this->db
					->query("
						SELECT dcdet.*, site.site_code FROM t_mtr_site_l_dcpdb_detail dcdet
						LEFT JOIN t_mtr_site site ON site.site_id = dcdet.fe_id
						WHERE dcdet.dcpdb_id = {$id}
						ORDER BY item ASC
					")
					->result_array();
				
				echo $this->parser->parse('partials/content_power_dcpdb_detail', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'BATTERY') {
			
				$data = array();
				
				if ($site_id = $this->input->get('site_id')) {
					$data['battery'] = $this->db
					->query("
						SELECT battery.*
						FROM t_mtr_site_l_battery battery
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = battery.mcb_item::VARCHAR AND lo1.lookup_key = 'power_mcb_item' AND lo1.active = 1
						WHERE battery.site_id = {$site_id} AND battery.active = 1
						ORDER BY lo1.\"order\"
					")
					->result_array();
				}
				
				echo $this->parser->parse('partials/content_power_battery', $data);
				exit;
			}
			
			/* if ($this->input->get('cmd') == 'ADD_ACPDB_DETAIL') {
			
				$data = array();
				
				echo $this->parser->parse('partials/content_power_acpdb_detail', $data);
				exit;
			} */
		}
		
		if ($this->input->post('cmd') == 'ADD_RECTIFIER') {
		
			$json = array();
			
			if ($this->input->post('act') == 'SAVE') { // save data
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Please choose Site ID.';
				}
				if ($this->_is_field_empty($post['rectifierNo'])) {
					$error[] = 'Rectifier Number can not unselected.';
				}
				if ( ! $this->_is_field_empty($post['rectifierNo']) && $this->core->view_row('t_mtr_site_l_rectifier', array('site_id' => (int) $post['siteId'], 'rectifier_no' => $post['rectifierNo'], 'rectifier_id !=' => (int) $post['rectifierId'])) ) {
					$error[] = 'Rectifier Number has been already in used.';
				}
				if ($this->_is_field_empty(@$post['slotUsed'])) {
					$error[] = 'Slot Used can not unchecked.';
				}
				if ($this->_is_field_empty($post['dcPower'])) {
					$error[] = 'DC Power can not unselected.';
				}
				if ($this->_is_field_empty($post['dcdcPowerCapacity'])) {
					$error[] = 'DC DC Power can not empty.';
				}
				if ($this->_is_field_empty($post['mcbCapacityTP'])) {
					$error[] = 'MCB Capacity can not unselected.';
				}
				if ($this->_is_field_empty($post['capacityR']) && $this->_is_field_empty($post['capacityS']) && $this->_is_field_empty($post['capacityT'])) {
					$error[] = 'Actual Load can not empty.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					if (@is_array($post['slotUsed'])) {
						sort($post['slotUsed']);
						$slot_used = implode(',', $post['slotUsed']);
					} else {
						$slot_used = NULL;
					}
					
					$this->core->set['site_id'] = $post['siteId'];
					$this->core->set['rectifier_no'] = $post['rectifierNo'];
					$this->core->set['slot_used'] = $slot_used;
					$this->core->set['capacity_r'] = null_empty($post['capacityR']);
					$this->core->set['capacity_s'] = null_empty($post['capacityS']);
					$this->core->set['capacity_t'] = null_empty($post['capacityT']);
					$this->core->set['dc_power'] = $post['dcPower'];
					$this->core->set['dcdc_power_capacity'] = null_empty($post['dcdcPowerCapacity']);
					$this->core->set['mcb_capacity_tp'] = $post['mcbCapacityTP'];
					$this->core->set['mcb_capacity'] = $post['mcbCapacity'];
					
					$rectifier_id = (int) $post['rectifierId'];
					
					if ( $this->core->view_row('t_mtr_site_l_rectifier', array('rectifier_id' => $rectifier_id)) ) {					
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
					
						$this->core->save('t_mtr_site_l_rectifier', array('rectifier_id' => $post['rectifierId']));
					} else {
						$this->core->set['created_by'] = $created_by;
						$this->core->set['created_on'] = $created_on;
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
						
						$this->core->save('t_mtr_site_l_rectifier');
						
						$rectifier_id = $this->db->insert_id();
					}
					
					$this->db->delete('t_mtr_site_l_rectifier_photo', array('rectifier_id' => $rectifier_id));
					
					$lookup = $this->core->get_lookup('power_rectifier_photo_type');
					foreach ($lookup as $lo) {
						if ($post['photoRectifier'.$lo['id']] != '') {
							$this->core->set['rectifier_id'] = $rectifier_id;
							$this->core->set['type_id'] = $lo['id'];
							$this->core->set['photo_name'] = $post['photoRectifier'.$lo['id']];
							
							$this->core->save('t_mtr_site_l_rectifier_photo');
						}
					}
					
					// detail
					/* $this->db->delete('t_mtr_site_l_rectifier_detail', array('rectifier_id' => $id));
					foreach ($post['item'] as $k_item => $v_item) {
						$this->core->set['rectifier_id'] = $id;
						$this->core->set['item'] = $post['item'][$k_item];
						$this->core->set['capacity'] = $post['capacity'][$k_item];
						$this->core->set['llvd_info'] = $post['llvdInfo'][$k_item];
						$this->core->set['allocation'] = $post['allocation'][$k_item];
						$this->core->set['fe_id'] = $post['feId'][$k_item];
						$this->core->set['system'] = $post['system'][$k_item];
						
						$this->core->save('t_mtr_site_l_rectifier_detail');
					} */
					
					$json = array('error' => FALSE, 'message' => 'Rectifier has been added successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
			} elseif ($this->input->post('act') == 'UPLOAD') { // just upload file
				
				$post = $this->core->get_post();
				
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
					$file_name = $post['image'] != '' ? $post['image'] : time().$ext;
					
					if ( ! in_array($ext, $allowed)) {
						$json = array('error' => TRUE, 'message' => 'Invalid file type. Please just upload file .jpg, .gif, .png.');
					} else {					
						if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_power_dir.'/'.$file_name)) {
							// resize first
							$file = $this->_power_dir.'/'.$file_name;
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
							
							$image = $this->_power_url.'/'.$file_name;
							$thumb = $this->image_model->resize($file, 60, 60, 'crop');
							$json = array('error' => FALSE, 'type' => $upload_key[0], 'file' => $file_name, 'image' => $image, 'thumb' => $thumb, 'message' => 'File uploaded.');
						}
					}
				
				}
			
			} elseif ($this->input->post('act') == 'DELETE_PHOTO') {
			
				$photo_name = $this->input->post('photo_name');
				$photo_type = $this->input->post('photo_type');
				$pathinfo = pathinfo($photo_name);
				
				if (is_file($this->_power_dir.'/'.$pathinfo['basename'])) {
					@unlink($this->_power_dir.'/'.$pathinfo['basename']);
				}
				
				if (is_file($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension'])) {
					@unlink($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension']);
				}
				
				$json = array('error' => FALSE, 'type' => $photo_type, 'message' => 'File has been deleted successfully.');
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'ADD_RECTIFIER_DETAIL') {
		
			$post = $this->core->get_post();
			$error = array();
			$json = array();
			
			if ($this->_is_field_empty(@$post['item'])) {
				$error[] = 'Item can not unselected.';
			}
			if ($this->_is_field_empty(@$post['capacity'])) {
				$error[] = 'Capacity can not unselected.';
			}
			if ($this->_is_field_empty(@$post['capacityActual'])) {
				$error[] = 'Actual Capacity can not empty.';
			}
			if ($this->_is_field_empty(@$post['llvdInfo'])) {
				$error[] = 'LLVD can not unselected.';
			}
			if ($this->_is_field_empty(@$post['allocation'])) {
				$error[] = 'Allocation can not unselected.';
			}
			if ($this->_is_field_empty(@$post['farEndCode'])) {
				$error[] = 'FE ID can not empty.';
			}
			if ($this->_is_field_empty(@$post['system'])) {
				$error[] = 'system can not unselected.';
			}
			
			if (count($error) == 0) {
			
				$this->db->delete('t_mtr_site_l_rectifier_detail', array('rectifier_id' => $post['rectifierDetailId']));
				
				if (@is_array($post['item'])) {
					foreach ($post['item'] as $k_item => $v_item) {
						$this->core->set['rectifier_id'] = $post['rectifierDetailId'];
						$this->core->set['item'] = $post['item'][$k_item];
						$this->core->set['capacity'] = $post['capacity'][$k_item];
						$this->core->set['capacity_actual'] = $post['capacityActual'][$k_item];
						$this->core->set['llvd_info'] = $post['llvdInfo'][$k_item];
						$this->core->set['allocation'] = $post['allocation'][$k_item];
						$this->core->set['fe_id'] = null_empty($post['farEnd'][$k_item]);
						$this->core->set['system'] = $post['system'][$k_item];
						
						$this->core->save('t_mtr_site_l_rectifier_detail');
					}
				}
				
				$json = array('error' => FALSE, 'message' => 'Rectifier Detail has been created successfully.');
			} else {
				$json = array('error' => TRUE, 'message' => $error);
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'ADD_ACPDB') {
		
			$json = array();
			
			if ($this->input->post('act') == 'SAVE') { // save data
				
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Please choose Site ID.';
				}
				/*
				if ( $this->core->view_row('t_mtr_site_l_acpdb', array('site_id' => (int) $post['siteId'])) ) {
					$error[] = 'Site ID has already in used.';
				}
				*/
				if ($this->_is_field_empty($post['capacityR']) && $this->_is_field_empty($post['capacityS']) && $this->_is_field_empty($post['capacityT'])) {
					$error[] = 'Actual Load can not empty.';
				}
				if ($this->_is_field_empty($post['mcbCapacityTP']) || $this->_is_field_empty($post['mcbCapacity'])) {
					$error[] = 'MCB Capacity of TP can not unselected.';
				}
				if ($this->_is_field_empty($post['mcbCapacityACPDB']) || $this->_is_field_empty($post['mcbCapacity2'])) {
					$error[] = 'MCB Capacity of ACPDB can not unselected.';
				}
				
				if ($this->_is_field_empty($post['item'])) {
					$error[] = 'Item can not unselected.';
				}
				if ($this->_is_field_empty($post['capacity'])) {
					$error[] = 'Capacity can not unselected.';
				}
				if ($this->_is_field_empty($post['capacityActual'])) {
					$error[] = 'Actual Capacity can not empty.';
				}
				if ($this->_is_field_empty($post['phaseUsed'])) {
					$error[] = 'Phase Used can not unselected.';
				}
				if ($this->_is_field_empty($post['allocation'])) {
					$error[] = 'Allocation can not unselected.';
				}
				if ($this->_is_field_empty($post['linkedToRectifierNo'])) {
					$error[] = 'Linked to Rectifier No. can not unselected.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					$this->core->set['site_id'] = $post['siteId'];
					$this->core->set['capacity_r'] = $post['capacityR'];
					$this->core->set['capacity_s'] = $post['capacityS'];
					$this->core->set['capacity_t'] = $post['capacityT'];
					$this->core->set['capacity_r'] = $post['capacityR'];
					$this->core->set['mcb_capacity_tp'] = $post['mcbCapacityTP'];
					$this->core->set['mcb_capacity'] = $post['mcbCapacity'];
					$this->core->set['mcb_capacity_acpdb'] = $post['mcbCapacityACPDB'];
					$this->core->set['acpdb_mcb_capacity'] = $post['mcbCapacity2'];
					
					$acpdb_id = 0;
					
					if ( ! $this->input->post('acpdbId') && ! $this->core->view_row('t_mtr_site_l_acpdb', array('acpdb_id' => (int) $this->input->post('acpdbId')))) {
						$this->core->set['created_by'] = $created_by;
						$this->core->set['created_on'] = $created_on;
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
					
						$this->core->save('t_mtr_site_l_acpdb');
					
						$acpdb_id = $this->db->insert_id();
					} else {
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
						
						$this->core->save('t_mtr_site_l_acpdb', array('acpdb_id' => $this->input->post('acpdbId')));
						
						$acpdb_id = $this->input->post('acpdbId');
					}
					
					// photo
					$this->db->delete('t_mtr_site_l_acpdb_photo', array('acpdb_id' => $acpdb_id));
					
					$lookup = $this->core->get_lookup('power_acpdb_photo_type');
					foreach ($lookup as $lo) {
						if ($post['photoACPDB'.$lo['id']] != '') {
							$this->core->set['acpdb_id'] = $acpdb_id;
							$this->core->set['type_id'] = $lo['id'];
							$this->core->set['photo_name'] = $post['photoACPDB'.$lo['id']];
							
							$this->core->save('t_mtr_site_l_acpdb_photo');
						}
					}
					
					// detail
					$this->db->delete('t_mtr_site_l_acpdb_detail', array('acpdb_id' => $acpdb_id));
					if (@is_array($post['item'])) {
						foreach ($post['item'] as $k_item => $v_item) {
							$this->core->set['acpdb_id'] = $acpdb_id;
							$this->core->set['item'] = $post['item'][$k_item];
							$this->core->set['phase_used'] = $post['phaseUsed'][$k_item];
							$this->core->set['allocation'] = $post['allocation'][$k_item];
							$this->core->set['capacity'] = $post['capacity'][$k_item];
							$this->core->set['capacity_actual'] = $post['capacityActual'][$k_item];
							$this->core->set['linked_to_rectifier_no'] = $post['linkedToRectifierNo'][$k_item];
							
							$this->core->save('t_mtr_site_l_acpdb_detail');
						}
					}
					
					$json = array('error' => FALSE, 'message' => 'ACPDB has been added successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
				
			} elseif ($this->input->post('act') == 'UPLOAD') { // just upload file
				
				$post = $this->core->get_post();
				
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
					$file_name = $post['image'] != '' ? $post['image'] : time().$ext;
					
					if ( ! in_array($ext, $allowed)) {
						$json = array('error' => TRUE, 'message' => 'Invalid file type. Please just upload file .jpg, .gif, .png.');
					} else {					
						if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_power_dir.'/'.$file_name)) {
							// resize first
							$file = $this->_power_dir.'/'.$file_name;
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
							
							$image = $this->_power_url.'/'.$file_name;
							$thumb = $this->image_model->resize($file, 60, 60, 'crop');
							$json = array('error' => FALSE, 'type' => $upload_key[0], 'file' => $file_name, 'image' => $image, 'thumb' => $thumb, 'message' => 'File uploaded.');
						}
					}
				
				}
				
				echo json_encode($json);
				exit;
				
			} elseif ($this->input->post('act') == 'DELETE_PHOTO') {
			
				$photo_name = $this->input->post('photo_name');
				$photo_type = $this->input->post('photo_type');
				$pathinfo = pathinfo($photo_name);
				
				if (is_file($this->_power_dir.'/'.$pathinfo['basename'])) {
					@unlink($this->_power_dir.'/'.$pathinfo['basename']);
				}
				
				if (is_file($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension'])) {
					@unlink($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension']);
				}
				
				$json = array('error' => FALSE, 'type' => $photo_type, 'message' => 'File has been deleted successfully.');
			
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->post('act') == 'add') {
			
				$data = array();
				
				echo $this->parser->parse('partials/content_power_acpdb_form', $data);
				exit;
			}
			
			if ($this->input->post('act') == 'edit') {
			
				$acpdb_id = (int) $this->core->decode($this->input->post('acpdb_id'));
				
				$data['acpdb'] = $this->core->view_row('t_mtr_site_l_acpdb', array('acpdb_id' => $acpdb_id));
				$data['acpdb']['detail'] = $this->db
					->query("
						SELECT acpdb_detail.*
						FROM t_mtr_site_l_acpdb_detail acpdb_detail
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = acpdb_detail.item::VARCHAR AND lo1.lookup_key = 'power_mcb_item' AND lo1.active = 1
						WHERE acpdb_detail.acpdb_id = {$acpdb_id}
						ORDER BY lo1.\"order\" ASC
					")
					->result_array();
				
				echo $this->parser->parse('partials/content_power_acpdb_form', $data);
				exit;
			}
		}
		
		if ($this->input->post('cmd') == 'ADD_DCPDB') {
		
			$json = array();
			
			if ($this->input->post('act') == 'SAVE') { // save data
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Please choose Site ID.';
				}
				/* if ( $this->core->view_row('t_mtr_site_l_dcpdb', array('site_id' => (int) $post['siteId'])) ) {
					$error[] = 'Site ID is already used.';
				} */
				if ($this->_is_field_empty($post['linkedToRectifierNo'])) {
					$error[] = 'Linked to Rectifier Number can not unselected.';
				}
				if ($this->_is_field_empty($post['mainMcbCapacity'])) {
					$error[] = 'Main MCB Capacity can not empty.';
				}
				if ($this->_is_field_empty($post['cableDiameter'])) {
					$error[] = 'Cable Input Diameter can not empty.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->set['site_id'] = $post['siteId'];
					$this->core->set['rectifier_no'] = $post['linkedToRectifierNo'];
					$this->core->set['main_mcb_capacity'] = $post['mainMcbCapacity'];
					$this->core->set['cable_diameter'] = $post['cableDiameter'];
					
					//$this->core->set['panel_photo'] = $post['file-acpdb'];
					
					$dcpdb_id = (int) $post['dcpdbId'];
					
					if ( $this->core->view_row('t_mtr_site_l_dcpdb', array('dcpdb_id' => (int) $post['dcpdbId'])) ) {
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
						
						$this->core->save('t_mtr_site_l_dcpdb', array('dcpdb_id' => (int) $post['dcpdbId']));
					} else {
						$this->core->set['created_by'] = $created_by;
						$this->core->set['created_on'] = $created_on;
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
						
						$this->core->save('t_mtr_site_l_dcpdb');
						
						$dcpdb_id = $this->db->insert_id();
					}
					
					$this->db->delete('t_mtr_site_l_dcpdb_photo', array('dcpdb_id' => $dcpdb_id));
					
					$lookup = $this->core->get_lookup('power_dcpdb_photo_type');
					foreach ($lookup as $lo) {
						if ($post['photoDCPDB'.$lo['id']] != '') {
							$this->core->set['dcpdb_id'] = $dcpdb_id;
							$this->core->set['type_id'] = $lo['id'];
							$this->core->set['photo_name'] = $post['photoDCPDB'.$lo['id']];
							
							$this->core->save('t_mtr_site_l_dcpdb_photo');
						}
					}
					
					// detail
					/* $this->db->delete('t_mtr_site_l_dcpdb_detail', array('dcpdb_id' => $id));
					foreach ($post['item'] as $k_item => $v_item) {
						$this->core->set['dcpdb_id'] = $id;
						$this->core->set['item'] = $post['item'][$k_item];
						$this->core->set['capacity'] = $post['capacity'][$k_item];
						$this->core->set['allocation'] = $post['allocation'][$k_item];
						$this->core->set['fe_id'] = $post['feId'][$k_item];
						$this->core->set['system'] = $post['system'][$k_item];
						
						$this->core->save('t_mtr_site_l_dcpdb_detail');
					} */
					
					$json = array('error' => FALSE, 'message' => 'DCPDB has been added successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
			} elseif ($this->input->post('act') == 'UPLOAD') { // just upload file
				
				$post = $this->core->get_post();
				
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
					$file_name = $post['image'] != '' ? $post['image'] : time().$ext;
					
					if ( ! in_array($ext, $allowed)) {
						$json = array('error' => TRUE, 'message' => 'Invalid file type. Please just upload file .jpg, .gif, .png.');
					} else {					
						if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_power_dir.'/'.$file_name)) {
							// resize first
							$file = $this->_power_dir.'/'.$file_name;
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
							
							$image = $this->_power_url.'/'.$file_name;
							$thumb = $this->image_model->resize($file, 60, 60, 'crop');
							$json = array('error' => FALSE, 'type' => $upload_key[0], 'file' => $file_name, 'image' => $image, 'thumb' => $thumb, 'message' => 'File uploaded.');
						}
					}
				
				}
			
			} elseif ($this->input->post('act') == 'DELETE_PHOTO') {
			
				$photo_name = $this->input->post('photo_name');
				$photo_type = $this->input->post('photo_type');
				$pathinfo = pathinfo($photo_name);
				
				if (is_file($this->_power_dir.'/'.$pathinfo['basename'])) {
					@unlink($this->_power_dir.'/'.$pathinfo['basename']);
				}
				
				if (is_file($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension'])) {
					@unlink($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension']);
				}
				
				$json = array('error' => FALSE, 'type' => $photo_type, 'message' => 'File has been deleted successfully.');
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'ADD_DCPDB_DETAIL') {
		
			$post = $this->core->get_post();
			$error = array();
			$json = array();
			
			if ($this->_is_field_empty(@$post['item'])) {
				$error[] = 'Item can not unselected.';
			}
			if ($this->_is_field_empty(@$post['capacity'])) {
				$error[] = 'Capacity can not unselected.';
			}
			if ($this->_is_field_empty(@$post['actualCapacity'])) {
				$error[] = 'Actual Capacity can not empty.';
			}
			if ($this->_is_field_empty(@$post['allocation'])) {
				$error[] = 'Allocation can not unselected.';
			}
			if ($this->_is_field_empty(@$post['feCode'])) {
				$error[] = 'FE ID can not empty.';
			}
			if ($this->_is_field_empty(@$post['system'])) {
				$error[] = 'System can not unselected.';
			}
			if ($this->_is_field_empty(@$post['linkedToRectifierNo'])) {
				$error[] = 'Linked to Rectifier No can not unselected.';
			}
			
			if (count($error) == 0) {
			
				$this->db->delete('t_mtr_site_l_dcpdb_detail', array('dcpdb_id' => $post['DCPDBDetailId']));
				
				if (@is_array($post['item'])) {
					foreach ($post['item'] as $k_item => $v_item) {
						$this->core->set['dcpdb_id'] = $post['DCPDBDetailId'];
						$this->core->set['item'] = $post['item'][$k_item];
						$this->core->set['capacity'] = $post['capacity'][$k_item];
						$this->core->set['actual_capacity'] = $post['actualCapacity'][$k_item];
						$this->core->set['allocation'] = $post['allocation'][$k_item];
						$this->core->set['fe_id'] = null_empty($post['feId'][$k_item]);
						$this->core->set['system'] = $post['system'][$k_item];
						$this->core->set['linked_to_rectifier_no'] = $post['linkedToRectifierNo'][$k_item];
						
						$this->core->save('t_mtr_site_l_dcpdb_detail');
					}
				}
				
				$json = array('error' => FALSE, 'message' => 'DCPDB Detail has been added successfully.');
			} else {
				$json = array('error' => TRUE, 'message' => $error);
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'ADD_BATTERY') {
		
			$json = array();
			$post = $this->core->get_post();
			$error = array();
			
			if ($this->_is_field_empty($post['siteId'])) {
				$error[] = 'Please choose Site ID.';
			}
			/*
			if ( $this->core->view_row('t_mtr_site_l_battery', array('site_id' => (int) $post['siteId'])) ) {
				$error[] = 'Site ID is already used.';
			}
			*/
			if ($this->_is_field_empty(@$post['mcbItem'])) {
				$error[] = 'Item(s) can not unselected.';
			}
			if ($this->_is_field_empty(@$post['batteryBrand'])) {
				$error[] = 'Brand / Model can not unselected.';
			}
			if ($this->_is_field_empty(@$post['batteryCapacity'])) {
				$error[] = 'Capacity per Cell can not empty.';
			}
			if ($this->_is_field_empty(@$post['batteryTotalBank'])) {
				$error[] = 'Total Bank can not empty.';
			}
			if ($this->_is_field_empty(@$post['batteryTotalShunt'])) {
				$error[] = 'Total Shunt can not empty.';
			}
			if ($this->_is_field_empty(@$post['weightPerCell'])) {
				$error[] = 'Weight per Cell can not empty.';
			}
			if ($this->_is_field_empty(@$post['voltagePerCell'])) {
				$error[] = 'Voltage per Cell can not unselected.';
			}
			if ($this->_is_field_empty(@$post['linkedToRectifierNo'])) {
				$error[] = 'Linked to Rectifier Number can not unselected.';
			}
			
			if (count($error) == 0) {
				
				$created_by = $updated_by = $this->session->userdata('user_id');
				$created_on = $updated_on = date('Y-m-d H:i:s');
				
				$this->core->update('t_mtr_site_l_battery', array('active' => 0), array('site_id' => $post['siteId']));
				
				if (@is_array($post['batteryBrand'])) {
					foreach ($post['batteryBrand'] as $idx => $val) {
						if ( $this->core->view_row('t_mtr_site_l_battery', array('site_id' => $post['siteId'], 'battery_id' => (int) $post['batteryId'][$idx])) ) {
							
							$this->core->set['site_id'] = $post['siteId'];
							$this->core->set['mcb_item'] = $post['mcbItem'][$idx];
							$this->core->set['brand'] = $post['batteryBrand'][$idx];
							$this->core->set['capacity'] = $post['batteryCapacity'][$idx];
							$this->core->set['total_bank'] = $post['batteryTotalBank'][$idx];
							$this->core->set['total_shunt'] = $post['batteryTotalShunt'][$idx];
							$this->core->set['weight_per_cell'] = $post['weightPerCell'][$idx];
							$this->core->set['voltage_per_cell'] = $post['voltagePerCell'][$idx];
							$this->core->set['rectifier_no'] = $post['linkedToRectifierNo'][$idx];
							$this->core->set['updated_by'] = $updated_by;
							$this->core->set['updated_on'] = $updated_on;
							$this->core->set['active'] = 1;
							
							$this->core->save('t_mtr_site_l_battery', array('site_id' => $post['siteId'], 'battery_id' => (int) $post['batteryId'][$idx]));
						} else {
							$this->core->set['site_id'] = $post['siteId'];
							$this->core->set['mcb_item'] = $post['mcbItem'][$idx];
							$this->core->set['brand'] = $post['batteryBrand'][$idx];
							$this->core->set['capacity'] = $post['batteryCapacity'][$idx];
							$this->core->set['total_bank'] = $post['batteryTotalBank'][$idx];
							$this->core->set['total_shunt'] = $post['batteryTotalShunt'][$idx];
							$this->core->set['weight_per_cell'] = $post['weightPerCell'][$idx];
							$this->core->set['voltage_per_cell'] = $post['voltagePerCell'][$idx];
							$this->core->set['rectifier_no'] = $post['linkedToRectifierNo'][$idx];
							$this->core->set['created_by'] = $created_by;
							$this->core->set['created_on'] = $created_on;
							$this->core->set['updated_by'] = $updated_by;
							$this->core->set['updated_on'] = $updated_on;
							$this->core->set['active'] = 1;
							
							$this->core->save('t_mtr_site_l_battery');
						}
					}
				}
				
				$json = array('error' => FALSE, 'message' => 'Battery has been added successfully.');
			} else {
				$json = array('error' => TRUE, 'message' => $error);
			}
			
			echo json_encode($json);
			exit;
		}
		
		$this->_power_form();
	}
	
	public function edit($id = 0)
	{
		$this->_check_valid_loggedin();
		
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_edit')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$data = $this->_partials;
		
		$id = (int) $this->core->decode($id);
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->get('load_content') == 'RECTIFIER') {
			
				$result = $this->core->view_row('t_mtr_site_l_rectifier', array('site_id' => $id), 'rectifier_id');
				
				$data['rectifier'] = array();
				$data['rectifier']['detail'] = array();
				
				if ($result) {
					$data['rectifier'] = $result;
					$data['rectifier']['detail'] = $this->core->view_all('t_mtr_site_l_rectifier_detail', array('rectifier_id' => $result['rectifier_id']), 'rectifier_id');
				}
				
				$updated = $this->db
					->query("
						SELECT rectifier.updated_on, usr.user_name AS updated_by
						FROM t_mtr_site_l_rectifier rectifier
						LEFT JOIN t_mtr_user usr ON usr.user_id = rectifier.updated_by
						WHERE rectifier.updated_on IS NOT NULL 
						ORDER BY rectifier.updated_on DESC 
						LIMIT 1
					")
					->row_array();
				
				if ($updated) {
					$data['last_updated'] = 'Last updated on <strong>'.date('d-M-Y H:i:s', strtotime($updated['updated_on'])).'</strong> by <strong>'.$updated['updated_by'].'</strong>';
				} else {
					$data['last_updated'] = 'Data not available.';
				}
				
				echo $this->parser->parse('partials/content_power_rectifier', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'RECTIFIER_DETAIL') {
			
				$id = $this->input->get('rectifier_id');
				
				$data['rectifier_id'] = $id;
				$data['rectifier_detail'] = $this->db
					->query("
						SELECT recdet.*, site.site_code FROM t_mtr_site_l_rectifier_detail recdet
						LEFT JOIN t_mtr_site site ON site.site_id = recdet.fe_id
						WHERE recdet.rectifier_id = {$id}
						ORDER BY item ASC
					")
					->result_array();
				
				echo $this->parser->parse('partials/content_power_rectifier_detail', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'ACPDB') {
			
				if ($result = $this->core->view_row('t_mtr_site_l_acpdb', array('site_id' => $id))) {
				
					$data['acpdb'] = $result;
					$data['acpdb']['detail'] = $this->db
						->query("
							SELECT acpdb_detail.*
							FROM t_mtr_site_l_acpdb_detail acpdb_detail
							LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = acpdb_detail.item::VARCHAR AND lo1.lookup_key = 'power_mcb_item' AND lo1.active = 1
							WHERE acpdb_detail.acpdb_id = ".$result['acpdb_id']."
							ORDER BY lo1.\"order\"
						")
						->result_array();
				}
				
				$updated = $this->db
					->query("
						SELECT acpdb.updated_on, usr.user_name AS updated_by
						FROM t_mtr_site_l_acpdb acpdb
						LEFT JOIN t_mtr_user usr ON usr.user_id = acpdb.updated_by
						WHERE acpdb.updated_on IS NOT NULL 
						ORDER BY acpdb.updated_on DESC 
						LIMIT 1
					")
					->row_array();
				
				if ($updated) {
					$data['last_updated'] = 'Last updated on <strong>'.date('d-M-Y H:i:s', strtotime($updated['updated_on'])).'</strong> by <strong>'.$updated['updated_by'].'</strong>';
				} else {
					$data['last_updated'] = 'Data not available.';
				}
				
				echo $this->parser->parse('partials/content_power_acpdb', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'DCPDB') {
			
				$data['dcpdb'] = $this->core->view_row('t_mtr_site_l_dcpdb', array('dcpdb_id' => $id));
				$data['dcpdb']['detail'] = $this->core->view_all('t_mtr_site_l_dcpdb_detail', array('dcpdb_id' => $id), 'dcpdb_id');
				
				$updated = $this->db
					->query("
						SELECT dcpdb.updated_on, usr.user_name AS updated_by
						FROM t_mtr_site_l_dcpdb dcpdb
						LEFT JOIN t_mtr_user usr ON usr.user_id = dcpdb.updated_by
						WHERE dcpdb.updated_on IS NOT NULL 
						ORDER BY dcpdb.updated_on DESC 
						LIMIT 1
					")
					->row_array();
				
				if ($updated) {
					$data['last_updated'] = 'Last updated on <strong>'.date('d-M-Y H:i:s', strtotime($updated['updated_on'])).'</strong> by <strong>'.$updated['updated_by'].'</strong>';
				} else {
					$data['last_updated'] = 'Data not available.';
				}
				
				echo $this->parser->parse('partials/content_power_dcpdb', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'DCPDB_DETAIL') {
			
				$id = $this->input->get('dcpdb_id');
				
				$data['dcpdb_id'] = $id;
				$data['dcpdb_detail'] = $this->db
					->query("
						SELECT dcdet.*, site.site_code FROM t_mtr_site_l_dcpdb_detail dcdet
						LEFT JOIN t_mtr_site site ON site.site_id = dcdet.fe_id
						WHERE dcdet.dcpdb_id = {$id}
						ORDER BY item ASC
					")
					->result_array();
				
				echo $this->parser->parse('partials/content_power_dcpdb_detail', $data);
				exit;
			}
			
			if ($this->input->get('load_content') == 'BATTERY') {
			
				$data['battery'] = $this->db
					->query("
						SELECT battery.*
						FROM t_mtr_site_l_battery battery
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = battery.mcb_item::VARCHAR AND lo1.lookup_key = 'power_mcb_item' AND lo1.active = 1
						WHERE battery.site_id = {$id} AND battery.active = 1
						ORDER BY lo1.\"order\"
					")
					->result_array();
				
				$updated = $this->db
					->query("
						SELECT battery.updated_on, usr.user_name AS updated_by
						FROM t_mtr_site_l_battery battery
						LEFT JOIN t_mtr_user usr ON usr.user_id = battery.updated_by
						WHERE battery.updated_on IS NOT NULL 
						ORDER BY battery.updated_on DESC 
						LIMIT 1
					")
					->row_array();
				
				if ($updated) {
					$data['last_updated'] = 'Last updated on <strong>'.date('d-M-Y H:i:s', strtotime($updated['updated_on'])).'</strong> by <strong>'.$updated['updated_by'].'</strong>';
				} else {
					$data['last_updated'] = 'Data not available.';
				}
				
				echo $this->parser->parse('partials/content_power_battery', $data);
				exit;
			}
		}
		
		if ($this->input->post('cmd') == 'EDIT_RECTIFIER') {
		
			$json = array();
			
			if ($this->input->post('act') == 'SAVE') { // save data
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Please choose Site ID.';
				}
				if ($this->_is_field_empty($post['rectifierNo'])) {
					$error[] = 'Rectifier Number can not unselected.';
				}
				if ( ! $this->_is_field_empty($post['rectifierNo']) && $this->core->view_row('t_mtr_site_l_rectifier', array('site_id' => (int) $post['siteId'], 'rectifier_no' => $post['rectifierNo'], 'rectifier_id !=' => (int) $post['rectifierId'])) ) {
					$error[] = 'Rectifier Number has been already in used.';
				}
				if ($this->_is_field_empty(@$post['slotUsed'])) {
					$error[] = 'Slot Used can not unchecked.';
				}
				if ($this->_is_field_empty($post['dcPower'])) {
					$error[] = 'DC Power can not unselected.';
				}
				if ($this->_is_field_empty($post['dcdcPowerCapacity'])) {
					$error[] = 'DC DC Power can not empty.';
				}
				if ($this->_is_field_empty($post['mcbCapacityTP'])) {
					$error[] = 'MCB Capacity can not unselected.';
				}
				if ($this->_is_field_empty($post['capacityR']) && $this->_is_field_empty($post['capacityS']) && $this->_is_field_empty($post['capacityT'])) {
					$error[] = 'Actual Load can not empty.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					if (@is_array($post['slotUsed'])) {
						sort($post['slotUsed']);
						$slot_used = implode(',', $post['slotUsed']);
					} else {
						$slot_used = NULL;
					}
					
					$this->core->db_debug = TRUE;
					
					$this->core->set['site_id'] = $post['siteId'];
					$this->core->set['rectifier_no'] = $post['rectifierNo'];
					$this->core->set['slot_used'] = $slot_used;
					$this->core->set['capacity_r'] = null_empty($post['capacityR']);
					$this->core->set['capacity_s'] = null_empty($post['capacityS']);
					$this->core->set['capacity_t'] = null_empty($post['capacityT']);
					$this->core->set['dc_power'] = $post['dcPower'];
					$this->core->set['dcdc_power_capacity'] = null_empty($post['dcdcPowerCapacity']);
					$this->core->set['mcb_capacity_tp'] = $post['mcbCapacityTP'];
					$this->core->set['mcb_capacity'] = $post['mcbCapacity'];
					
					$rectifier_id = (int) $post['rectifierId'];
					
					if ( $this->core->view_row('t_mtr_site_l_rectifier', array('rectifier_id' => (int) $post['rectifierId'])) ) {					
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
					
						//$this->core->save('t_mtr_site_l_rectifier', array('rectifier_id' => $post['rectifierId']));
						$data = $this->core->set;
						$data['rectifier_id'] = $post['rectifierId'];
						
						$this->site_info_model->save_data('t_mtr_site_l_rectifier', $data, 'rectifier_id');
					} else {
						$this->core->set['created_by'] = $created_by;
						$this->core->set['created_on'] = $created_on;
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
						
						$this->core->save('t_mtr_site_l_rectifier');
						
						$rectifier_id = $this->db->insert_id();
					}
					unset($this->core->set);
					
					$this->db->delete('t_mtr_site_l_rectifier_photo', array('rectifier_id' => $rectifier_id));
					
					$lookup = $this->core->get_lookup('power_rectifier_photo_type');
					foreach ($lookup as $lo) {
						if ($post['photoRectifier'.$lo['id']] != '') {
							$this->core->set['rectifier_id'] = $rectifier_id;
							$this->core->set['type_id'] = $lo['id'];
							$this->core->set['photo_name'] = $post['photoRectifier'.$lo['id']];
							
							$this->core->save('t_mtr_site_l_rectifier_photo');
						}
					}
					
					
					// detail
					/* $this->db->delete('t_mtr_site_l_rectifier_detail', array('rectifier_id' => $id));
					foreach ($post['item'] as $k_item => $v_item) {
						$this->core->set['rectifier_id'] = $id;
						$this->core->set['item'] = $post['item'][$k_item];
						$this->core->set['capacity'] = $post['capacity'][$k_item];
						$this->core->set['llvd_info'] = $post['llvdInfo'][$k_item];
						$this->core->set['allocation'] = $post['allocation'][$k_item];
						$this->core->set['fe_id'] = $post['feId'][$k_item];
						$this->core->set['system'] = $post['system'][$k_item];
						
						$this->core->save('t_mtr_site_l_rectifier_detail');
					} */
					
					$json = array('error' => FALSE, 'message' => 'Rectifier has been updated successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
			} elseif ($this->input->post('act') == 'UPLOAD') { // just upload file
				
				$post = $this->core->get_post();
				
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
					$file_name = $post['image'] != '' ? $post['image'] : time().$ext;
					
					if ( ! in_array($ext, $allowed)) {
						$json = array('error' => TRUE, 'message' => 'Invalid file type. Please just upload file .jpg, .gif, .png.');
					} else {					
						if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_power_dir.'/'.$file_name)) {
							// resize first
							$file = $this->_power_dir.'/'.$file_name;
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
							
							$image = $this->_power_url.'/'.$file_name;
							$thumb = $this->image_model->resize($file, 60, 60, 'crop');
							$json = array('error' => FALSE, 'type' => $upload_key[0], 'file' => $file_name, 'image' => $image, 'thumb' => $thumb, 'message' => 'File uploaded.');
						}
					}
				
				}
			
			} elseif ($this->input->post('act') == 'DELETE_PHOTO') {
			
				$photo_name = $this->input->post('photo_name');
				$photo_type = $this->input->post('photo_type');
				$pathinfo = pathinfo($photo_name);
				
				if (is_file($this->_power_dir.'/'.$pathinfo['basename'])) {
					@unlink($this->_power_dir.'/'.$pathinfo['basename']);
				}
				
				if (is_file($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension'])) {
					@unlink($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension']);
				}
				
				$json = array('error' => FALSE, 'type' => $photo_type, 'message' => 'File has been deleted successfully.');
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'EDIT_RECTIFIER_DETAIL') {
		
			$post = $this->core->get_post();
			$error = array();
			$json = array();
			
			if ($this->_is_field_empty(@$post['item'])) {
				$error[] = 'Item can not unselected.';
			}
			if ($this->_is_field_empty(@$post['capacity'])) {
				$error[] = 'Capacity can not unselected.';
			}
			if ($this->_is_field_empty(@$post['capacityActual'])) {
				$error[] = 'Actual Capacity can not empty.';
			}
			if ($this->_is_field_empty(@$post['llvdInfo'])) {
				$error[] = 'LLVD can not unselected.';
			}
			if ($this->_is_field_empty(@$post['allocation'])) {
				$error[] = 'Allocation can not unselected.';
			}
			if ($this->_is_field_empty(@$post['farEndCode'])) {
				$error[] = 'FE ID can not empty.';
			}
			if ($this->_is_field_empty(@$post['system'])) {
				$error[] = 'system can not unselected.';
			}
			
			if (count($error) == 0) {
			
				$this->db->delete('t_mtr_site_l_rectifier_detail', array('rectifier_id' => $post['rectifierDetailId']));
				
				if (@is_array($post['item'])) {
					foreach ($post['item'] as $k_item => $v_item) {
						$this->core->set['rectifier_id'] = $post['rectifierDetailId'];
						$this->core->set['item'] = $post['item'][$k_item];
						$this->core->set['capacity'] = $post['capacity'][$k_item];
						$this->core->set['capacity_actual'] = $post['capacityActual'][$k_item];
						$this->core->set['llvd_info'] = $post['llvdInfo'][$k_item];
						$this->core->set['allocation'] = $post['allocation'][$k_item];
						$this->core->set['fe_id'] = null_empty($post['farEnd'][$k_item]);
						$this->core->set['system'] = $post['system'][$k_item];
						
						$this->core->save('t_mtr_site_l_rectifier_detail');
					}
				}
				
				$json = array('error' => FALSE, 'message' => 'Rectifier Detail has been updated successfully.');
			} else {
				$json = array('error' => TRUE, 'message' => $error);
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'EDIT_ACPDB') {
		
			$json = array();
			
			if ($this->input->post('act') == 'SAVE') { // save data
				
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Please choose Site ID.';
				}
				if ($this->_is_field_empty($post['capacityR']) && $this->_is_field_empty($post['capacityS']) && $this->_is_field_empty($post['capacityT'])) {
					$error[] = 'Actual Load can not empty.';
				}
				if ($this->_is_field_empty($post['mcbCapacityTP']) || $this->_is_field_empty($post['mcbCapacity'])) {
					$error[] = 'MCB Capacity of TP can not unselected.';
				}
				if ($this->_is_field_empty($post['mcbCapacityACPDB']) || $this->_is_field_empty($post['mcbCapacity2'])) {
					$error[] = 'MCB Capacity of ACPDB can not unselected.';
				}
				
				if ($this->_is_field_empty($post['item'])) {
					$error[] = 'Item can not unselected.';
				}
				if ($this->_is_field_empty($post['capacity'])) {
					$error[] = 'Capacity can not unselected.';
				}
				if ($this->_is_field_empty($post['capacityActual'])) {
					$error[] = 'Actual Capacity can not empty.';
				}
				if ($this->_is_field_empty($post['phaseUsed'])) {
					$error[] = 'Phase Used can not unselected.';
				}
				if ($this->_is_field_empty($post['allocation'])) {
					$error[] = 'Allocation can not unselected.';
				}
				if ($this->_is_field_empty($post['linkedToRectifierNo'])) {
					$error[] = 'Linked to Rectifier No. can not unselected.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					$this->core->set['site_id'] = $post['siteId'];
					$this->core->set['capacity_r'] = (float) $post['capacityR'];
					$this->core->set['capacity_s'] = (float) $post['capacityS'];
					$this->core->set['capacity_t'] = (float) $post['capacityT'];
					$this->core->set['capacity_r'] = (float) $post['capacityR'];
					$this->core->set['mcb_capacity_tp'] = $post['mcbCapacityTP'];
					$this->core->set['mcb_capacity'] = $post['mcbCapacity'];
					$this->core->set['mcb_capacity_acpdb'] = $post['mcbCapacityACPDB'];
					$this->core->set['acpdb_mcb_capacity'] = $post['mcbCapacity2'];
					
					$acpdb_id = 0;
					
					if ( ! $this->input->post('acpdbId') && ! $this->core->view_row('t_mtr_site_l_acpdb', array('acpdb_id' => (int) $this->input->post('acpdbId')))) {
					
						$this->core->set['created_by'] = $created_by;
						$this->core->set['created_on'] = $created_on;
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
						
						$this->core->save('t_mtr_site_l_acpdb');
						
						$acpdb_id = $this->db->insert_id();
					} else {
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
					
						$this->core->save('t_mtr_site_l_acpdb', array('acpdb_id' => $this->input->post('acpdbId')));
						
						$acpdb_id = $this->input->post('acpdbId');
					}
					
					// photo
					$this->db->delete('t_mtr_site_l_acpdb_photo', array('acpdb_id' => $acpdb_id));
					
					$lookup = $this->core->get_lookup('power_acpdb_photo_type');
					foreach ($lookup as $lo) {
						if ($post['photoACPDB'.$lo['id']] != '') {
							$this->core->set['acpdb_id'] = $acpdb_id;
							$this->core->set['type_id'] = $lo['id'];
							$this->core->set['photo_name'] = $post['photoACPDB'.$lo['id']];
							
							$this->core->save('t_mtr_site_l_acpdb_photo');							
						}
					}
					
					// detail
					$this->db->delete('t_mtr_site_l_acpdb_detail', array('acpdb_id' => $acpdb_id));
					if (@is_array($post['item'])) {
						foreach ($post['item'] as $k_item => $v_item) {
							$this->core->set['acpdb_id'] = $acpdb_id;
							$this->core->set['item'] = $post['item'][$k_item];
							$this->core->set['phase_used'] = $post['phaseUsed'][$k_item];
							$this->core->set['allocation'] = $post['allocation'][$k_item];
							$this->core->set['capacity'] = $post['capacity'][$k_item];
							$this->core->set['capacity_actual'] = $post['capacityActual'][$k_item];
							$this->core->set['linked_to_rectifier_no'] = $post['linkedToRectifierNo'][$k_item];
							
							$this->core->save('t_mtr_site_l_acpdb_detail');
						}
					}
					
					$json = array('error' => FALSE, 'message' => 'ACPDB has been updated successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
				
			} elseif ($this->input->post('act') == 'UPLOAD') { // just upload file
				
				$post = $this->core->get_post();
				
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
					$file_name = $post['image'] != '' ? $post['image'] : time().$ext;
					
					if ( ! in_array($ext, $allowed)) {
						$json = array('error' => TRUE, 'message' => 'Invalid file type. Please just upload file .jpg, .gif, .png.');
					} else {					
						if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_power_dir.'/'.$file_name)) {
							// resize first
							$file = $this->_power_dir.'/'.$file_name;
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
							
							$image = $this->_power_url.'/'.$file_name;
							$thumb = $this->image_model->resize($file, 60, 60, 'crop');
							$json = array('error' => FALSE, 'type' => $upload_key[0], 'file' => $file_name, 'image' => $image, 'thumb' => $thumb, 'message' => 'File uploaded.');
						}
					}
				
				}
				
				echo json_encode($json);
				exit;
			
			} elseif ($this->input->post('act') == 'DELETE_PHOTO') {
			
				$photo_name = $this->input->post('photo_name');
				$photo_type = $this->input->post('photo_type');
				$pathinfo = pathinfo($photo_name);
				
				if (is_file($this->_power_dir.'/'.$pathinfo['basename'])) {
					@unlink($this->_power_dir.'/'.$pathinfo['basename']);
				}
				
				if (is_file($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension'])) {
					@unlink($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension']);
				}
				
				$json = array('error' => FALSE, 'type' => $photo_type, 'message' => 'File has been deleted successfully.');
			
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->post('act') == 'add') {
			
				$data = array();
				
				echo $this->parser->parse('partials/content_power_acpdb_form', $data);
				exit;
			}
			
			if ($this->input->post('act') == 'edit') {
			
				$acpdb_id = (int) $this->core->decode($this->input->post('acpdb_id'));
				
				$data['acpdb'] = $this->core->view_row('t_mtr_site_l_acpdb', array('acpdb_id' => $acpdb_id));
				$data['acpdb']['detail'] = $this->db
					->query("
						SELECT acpdb_detail.*
						FROM t_mtr_site_l_acpdb_detail acpdb_detail
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = acpdb_detail.item::VARCHAR AND lo1.lookup_key = 'power_mcb_item' AND lo1.active = 1
						WHERE acpdb_detail.acpdb_id = {$acpdb_id}
						ORDER BY lo1.\"order\" ASC
					")
					->result_array();
				
				echo $this->parser->parse('partials/content_power_acpdb_form', $data);
				exit;
			}
		}
		
		if ($this->input->post('cmd') == 'EDIT_DCPDB') {
		
			$json = array();
			
			if ($this->input->post('act') == 'SAVE') { // save data
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['siteId'])) {
					$error[] = 'Please choose Site ID.';
				}
				if ($this->_is_field_empty($post['linkedToRectifierNo'])) {
					$error[] = 'Linked to Rectifier Number can not unselected.';
				}
				if ($this->_is_field_empty($post['mainMcbCapacity'])) {
					$error[] = 'Main MCB Capacity can not empty.';
				}
				if ($this->_is_field_empty($post['cableDiameter'])) {
					$error[] = 'Cable Input Diameter can not empty.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->set['site_id'] = $post['siteId'];
					$this->core->set['rectifier_no'] = $post['linkedToRectifierNo'];
					$this->core->set['main_mcb_capacity'] = $post['mainMcbCapacity'];
					$this->core->set['cable_diameter'] = $post['cableDiameter'];
					
					//$this->core->set['panel_photo'] = $post['file-acpdb'];
					
					$dcpdb_id = (int) $post['dcpdbId'];
					
					if ( $this->core->view_row('t_mtr_site_l_dcpdb', array('dcpdb_id' => (int) $post['dcpdbId'])) ) {
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
						
						//$this->core->save('t_mtr_site_l_dcpdb', array('dcpdb_id' => (int) $post['dcpdbId']));
						$data = $this->core->set;
						$data['dcpdb_id'] = (int) $post['dcpdbId'];
						
						$this->site_info_model->save_data('t_mtr_site_l_dcpdb', $data, 'dcpdb_id');
					} else {
						$this->core->set['created_by'] = $created_by;
						$this->core->set['created_on'] = $created_on;
						$this->core->set['updated_by'] = $updated_by;
						$this->core->set['updated_on'] = $updated_on;
						
						$this->core->save('t_mtr_site_l_dcpdb');
						
						$dcpdb_id = $this->db->insert_id();
					}
					unset($this->core->set);
					
					$this->db->delete('t_mtr_site_l_dcpdb_photo', array('dcpdb_id' => $dcpdb_id));
					
					$lookup = $this->core->get_lookup('power_dcpdb_photo_type');
					foreach ($lookup as $lo) {
						if ($post['photoDCPDB'.$lo['id']] != '') {
							$this->core->set['dcpdb_id'] = $dcpdb_id;
							$this->core->set['type_id'] = $lo['id'];
							$this->core->set['photo_name'] = $post['photoDCPDB'.$lo['id']];
							
							$this->core->save('t_mtr_site_l_dcpdb_photo');
						}
					}
					
					// detail
					/* $this->db->delete('t_mtr_site_l_dcpdb_detail', array('dcpdb_id' => $id));
					foreach ($post['item'] as $k_item => $v_item) {
						$this->core->set['dcpdb_id'] = $id;
						$this->core->set['item'] = $post['item'][$k_item];
						$this->core->set['capacity'] = $post['capacity'][$k_item];
						$this->core->set['allocation'] = $post['allocation'][$k_item];
						$this->core->set['fe_id'] = $post['feId'][$k_item];
						$this->core->set['system'] = $post['system'][$k_item];
						
						$this->core->save('t_mtr_site_l_dcpdb_detail');
					} */
					
					$json = array('error' => FALSE, 'message' => 'DCPDB has been updated successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
			} elseif ($this->input->post('act') == 'UPLOAD') { // just upload file
				
				$post = $this->core->get_post();
				
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
					$file_name = $post['image'] != '' ? $post['image'] : time().$ext;
					
					if ( ! in_array($ext, $allowed)) {
						$json = array('error' => TRUE, 'message' => 'Invalid file type. Please just upload file .jpg, .gif, .png.');
					} else {					
						if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_power_dir.'/'.$file_name)) {
							// resize first
							$file = $this->_power_dir.'/'.$file_name;
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
							
							$image = $this->_power_url.'/'.$file_name;
							$thumb = $this->image_model->resize($file, 60, 60, 'crop');
							$json = array('error' => FALSE, 'type' => $upload_key[0], 'file' => $file_name, 'image' => $image, 'thumb' => $thumb, 'message' => 'File uploaded.');
						}
					}
				
				}
			
			} elseif ($this->input->post('act') == 'DELETE_PHOTO') {
			
				$photo_name = $this->input->post('photo_name');
				$photo_type = $this->input->post('photo_type');
				$pathinfo = pathinfo($photo_name);
				
				if (is_file($this->_power_dir.'/'.$pathinfo['basename'])) {
					@unlink($this->_power_dir.'/'.$pathinfo['basename']);
				}
				
				if (is_file($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension'])) {
					@unlink($this->_power_cache_dir.'/'.$pathinfo['filename'].'-60x60.'.$pathinfo['extension']);
				}
				
				$json = array('error' => FALSE, 'type' => $photo_type, 'message' => 'File has been deleted successfully.');
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'EDIT_DCPDB_DETAIL') {
		
			$post = $this->core->get_post();
			$error = array();
			$json = array();
			
			if ($this->_is_field_empty(@$post['item'])) {
				$error[] = 'Item can not unselected.';
			}
			if ($this->_is_field_empty(@$post['capacity'])) {
				$error[] = 'Capacity can not unselected.';
			}
			if ($this->_is_field_empty(@$post['actualCapacity'])) {
				$error[] = 'Actual Capacity can not empty.';
			}
			if ($this->_is_field_empty(@$post['allocation'])) {
				$error[] = 'Allocation can not unselected.';
			}
			if ($this->_is_field_empty(@$post['feCode'])) {
				$error[] = 'FE ID can not empty.';
			}
			if ($this->_is_field_empty(@$post['system'])) {
				$error[] = 'System can not unselected.';
			}
			if ($this->_is_field_empty(@$post['linkedToRectifierNo'])) {
				$error[] = 'Linked to Rectifier No can not unselected.';
			}
			
			if (count($error) == 0) {
			
				$this->db->delete('t_mtr_site_l_dcpdb_detail', array('dcpdb_id' => $post['DCPDBDetailId']));
				
				if (@is_array($post['item'])) {
					foreach ($post['item'] as $k_item => $v_item) {
						$this->core->set['dcpdb_id'] = $post['DCPDBDetailId'];
						$this->core->set['item'] = $post['item'][$k_item];
						$this->core->set['capacity'] = $post['capacity'][$k_item];
						$this->core->set['actual_capacity'] = $post['actualCapacity'][$k_item];
						$this->core->set['allocation'] = $post['allocation'][$k_item];
						$this->core->set['fe_id'] = null_empty($post['feId'][$k_item]);
						$this->core->set['system'] = $post['system'][$k_item];
						$this->core->set['linked_to_rectifier_no'] = $post['linkedToRectifierNo'][$k_item];
						
						$this->core->save('t_mtr_site_l_dcpdb_detail');
					}
				}
				
				$json = array('error' => FALSE, 'message' => 'DCPDB Detail has been updated successfully.');
			} else {
				$json = array('error' => TRUE, 'message' => $error);
			}
			
			echo json_encode($json);
			exit;
		}
		
		if ($this->input->post('cmd') == 'EDIT_BATTERY') {
		
			$json = array();
			$post = $this->core->get_post();
			$error = array();
			
			if ($post['siteId'] == '') {
				$error[] = 'Please choose Site ID.';
			}
			if ($this->_is_field_empty(@$post['mcbItem'])) {
				$error[] = 'Item(s) can not unselected.';
			}
			if ($this->_is_field_empty(@$post['batteryBrand'])) {
				$error[] = 'Brand / Model can not unselected.';
			}
			if ($this->_is_field_empty(@$post['batteryCapacity'])) {
				$error[] = 'Capacity per Cell can not empty.';
			}
			if ($this->_is_field_empty(@$post['batteryTotalBank'])) {
				$error[] = 'Total Bank can not empty.';
			}
			if ($this->_is_field_empty(@$post['batteryTotalShunt'])) {
				$error[] = 'Total Shunt can not empty.';
			}
			if ($this->_is_field_empty(@$post['weightPerCell'])) {
				$error[] = 'Weight per Cell can not empty.';
			}
			if ($this->_is_field_empty(@$post['voltagePerCell'])) {
				$error[] = 'Voltage per Cell can not unselected.';
			}
			if ($this->_is_field_empty(@$post['linkedToRectifierNo'])) {
				$error[] = 'Linked to Rectifier Number can not unselected.';
			}
			
			if (count($error) == 0) {
				
				$this->core->db_debug = TRUE;
				
				$created_by = $updated_by = $this->session->userdata('user_id');
				$created_on = $updated_on = date('Y-m-d H:i:s');
				
				$this->core->update('t_mtr_site_l_battery', array('active' => 0), array('site_id' => $post['siteId']));
				
				if (@is_array($post['batteryBrand'])) {
					foreach ($post['batteryBrand'] as $idx => $val) {
						if ( $this->core->view_row('t_mtr_site_l_battery', array('battery_id' => (int) $post['batteryId'][$idx])) ) {
							$this->core->set['site_id'] = $post['siteId'];
							$this->core->set['mcb_item'] = $post['mcbItem'][$idx];
							$this->core->set['brand'] = $post['batteryBrand'][$idx];
							$this->core->set['capacity'] = $post['batteryCapacity'][$idx];
							$this->core->set['total_bank'] = $post['batteryTotalBank'][$idx];
							$this->core->set['total_shunt'] = $post['batteryTotalShunt'][$idx];
							$this->core->set['weight_per_cell'] = $post['weightPerCell'][$idx];
							$this->core->set['voltage_per_cell'] = $post['voltagePerCell'][$idx];
							$this->core->set['rectifier_no'] = $post['linkedToRectifierNo'][$idx];
							$this->core->set['updated_by'] = $updated_by;
							$this->core->set['updated_on'] = $updated_on;
							$this->core->set['active'] = 1;
						
							//$this->core->save('t_mtr_site_l_battery', array('battery_id' => $post['batteryId'][$idx]));
							$data = $this->core->set;
							$data['battery_id'] = (int) $post['batteryId'][$idx];
							
							$this->site_info_model->save_data('t_mtr_site_l_battery', $data, 'battery_id');
						} else {
							$this->core->set['site_id'] = $post['siteId'];
							$this->core->set['mcb_item'] = $post['mcbItem'][$idx];
							$this->core->set['brand'] = $post['batteryBrand'][$idx];
							$this->core->set['capacity'] = $post['batteryCapacity'][$idx];
							$this->core->set['total_bank'] = $post['batteryTotalBank'][$idx];
							$this->core->set['total_shunt'] = $post['batteryTotalShunt'][$idx];
							$this->core->set['weight_per_cell'] = $post['weightPerCell'][$idx];
							$this->core->set['voltage_per_cell'] = $post['voltagePerCell'][$idx];
							$this->core->set['rectifier_no'] = $post['linkedToRectifierNo'][$idx];
							$this->core->set['created_by'] = $created_by;
							$this->core->set['created_on'] = $created_on;
							$this->core->set['updated_by'] = $updated_by;
							$this->core->set['updated_on'] = $updated_on;
							$this->core->set['active'] = 1;
							
							$this->core->save('t_mtr_site_l_battery');
						}
					}
				}
				
				$json = array('error' => FALSE, 'message' => 'Battery has been updated successfully.');
			} else {
				$json = array('error' => TRUE, 'message' => $error);
			}
			
			echo json_encode($json);
			exit;
		}
		
		$this->_power_form($id);
	}
	
	public function view($site_id = 0)
	{
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_view')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$data['content_id'] = 'power_view_view';
		
		$this->load->view('template/smashing', $data);
	}
	
	private function _power_form($id = 0)
	{
		$data = $this->_partials;
		
		if ($this->uri->segment(3) == 'edit') {
			$data['power'] = $this->db
				->query("
					SELECT site.site_id, site.site_code, site.site_code_old, site.site_name, site.vendor_id, reg.region_name					
					FROM t_mtr_site site
					LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
					WHERE site.site_id = {$id}
				")
				->row_array();
		}
		
		$results = $this->db
			->query("
				SELECT site_id FROM t_mtr_site_l_acpdb WHERE active = 1
				UNION
				SELECT site_id FROM t_mtr_site_l_rectifier WHERE active = 1
				UNION
				SELECT site_id FROM t_mtr_site_l_dcpdb WHERE active = 1
				UNION
				SELECT site_id FROM t_mtr_site_l_battery WHERE active = 1
			")
			->result_array();
			
		$site_ids = array();
		foreach ($results as $res) {
			$site_ids[] = $res['site_id'];
		}
		sort($site_ids);
		
		$data['not_appear_siteid'] = implode(',', $site_ids);
		
		$data['content_id'] = 'power_form_view';
		
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