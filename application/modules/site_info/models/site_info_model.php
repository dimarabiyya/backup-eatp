<?php

class Site_info_model extends CI_Model {
	
	public function __construct()
	{
		$this->load->helper('text');
	}
	
	public function get_sites_list()
	{
		$data = array();
			
		$site_id = $this->input->get('site_id');
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$search = trim($this->input->get('query'));
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'ord';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$regi = $this->input->get('region');
		$status = $this->input->get('status');
		
		$region = array();
		foreach ($this->session->userdata['user_region'] as $reg) {
			if ($reg['region_id'] != '') {
				$region[] = $reg['region_id'];
			}
		}
		
		$where = "
			WHERE site.active = 1
		";
		
		if ( ! empty($search)) {
			$where .= "
				AND (UPPER(site.site_code) LIKE '%".strtoupper($search)."%'
				OR UPPER(site.site_code_old) LIKE '%".strtoupper($search)."%'
				OR UPPER(site.site_name) LIKE '%".strtoupper($search)."%'
				OR UPPER(site.vendor_id) LIKE '%".strtoupper($search)."%'
				OR UPPER(reg.region_name) LIKE '%".strtoupper($search)."%'
				OR UPPER(tpcom.company_name) LIKE '%".strtoupper($search)."%'
				OR CAST(site.latitude AS VARCHAR) LIKE '%".strtoupper($search)."%'
				OR CAST(site.longitude AS VARCHAR) LIKE '%".strtoupper($search)."%'
				OR UPPER(
				CASE
					WHEN used_status.status = 1 OR used_status.status IS NULL THEN 'Active'
					ELSE 'Inactive'
				END
				) LIKE '%".strtoupper($search)."%')
			";
		}
		
		if (count($region > 0)) {
			$where .= "AND site.region_id IN (".implode(', ', $region).")";
		}
		if ($regi != '') {
			$where .= " AND site.region_id = {$regi}";
		}
		if ($status != '') {
			if ($status == 0) {
				$where .= " AND (used_status.status = {$status})";
			} else {
				$where .= " AND (used_status.status = 1 OR used_status.status IS NULL)";
			}
		}
		
		$sql = "
			SELECT site.site_id, site.site_code, site.site_code_old, site.site_name, tpcom.company_name AS tp_name, site.vendor_id, reg.region_name, site.latitude, site.longitude, lo1.lookup_desc AS site_last_status,
			(CASE site.status
				WHEN 0 THEN 'Nom'
				WHEN 1 THEN 'Active'
			END) AS status,
			CASE WHEN used_status.status = 1 OR used_status.status IS NULL THEN 'Active' ELSE 'Inactive' END AS site_status,
			CASE WHEN site.updated_on IS NULL THEN site.created_on ELSE site.updated_on END AS ord
			FROM t_mtr_site site
			LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
			LEFT JOIN t_mtr_site_l_last_status lstatus ON lstatus.site_id = site.site_id AND lstatus.active = 1
			LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = lstatus.status::VARCHAR AND lo1.lookup_key = 'site_last_status' AND lo1.active = 1 
			-- LEFT JOIN t_mtr_tp_company tp ON tp.company_id::VARCHAR = site.tp_name
			LEFT JOIN t_mtr_tower_provider tp ON tp.tp_id::VARCHAR = site.tp_id
			LEFT JOIN t_mtr_tp_company tpcom ON tpcom.company_id = tp.company_id
			LEFT JOIN t_mtr_site_l_used_status used_status ON used_status.site_id = site.site_id AND used_status.active = 1
			{$where}
			ORDER BY {$sort} {$order}
		";
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
		
		$total_rows = (int) $query->row()->total;
		
		$sql .= " LIMIT {$rows} OFFSET {$offset}";
		
		$query = $this->db->query($sql);
		
		$rows = array();
		foreach ($query->result_array() as $r) {
			$r['site_id'] = $this->core->encode($r['site_id']);
			$r['latitude'] = number_format($r['latitude'], 6);
			$r['longitude'] = number_format($r['longitude'], 6);
			$rows[] = $r;
		}
		
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		//$data['sql'] = str_replace(array("\n", "\t"), ' ', $sql);
		
		return $data;
	}
	
	public function get_all_tower_provider2()
	{
		$query = $this->db->where('active', 1);
		$query = $this->db->order_by('tp_id', 'ASC');
		$query = $this->db->get('t_mtr_tower_provider');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_rows_tower_provider2()
	{
		$this->db->where('active', 1);
		$this->db->from('t_mtr_tower_provider');
		return $this->db->count_all_results();
	}
	
	public function get_tower($site_id)
	{
		/* return $this->db
			->select('t_mtr_site_l_tower.*, t_mtr_tower_provider.tp_company_name')
			->join('t_mtr_tower_provider', 't_mtr_tower_provider.tp_id = t_mtr_site_l_tower.tp_id', 'left')
			->where('t_mtr_site_l_tower.site_id', $site_id)
			->get('t_mtr_site_l_tower')
			->row_array(); */
		
		return $this->db
			->query("SELECT * FROM t_mtr_site_l_tower WHERE site_id = {$site_id}")
			->row_array();
	}

	public function get_all_site()
	{
		$query = $this->db->where('active',1);
		$query = $this->db->order_by('site_id','ASC');
		$query = $this->db->get('t_mtr_site');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_rows_site()
	{
		$this->db->where('active', 1);
		$this->db->from('t_mtr_site');	
		return $this->db->count_all_results();
	}	

	public function get_general_info_by_id($site_id)
	{
		$this->db
			->select('t_mtr_site.*, t_mtr_site_area_code.area_code')
			->join('t_mtr_site_area_code', 't_mtr_site_area_code.area_id = t_mtr_site.area_id', 'left')
			->where('site_id', $site_id);
		
		$query = $this->db->get('t_mtr_site');
		
		return $query->row_array();
	}
	
	public function get_site($site_id)
	{
		return $this->db
			->query("
				SELECT site.*, tp.tp_code, tp_co.company_name AS tp_company_name, tp_grp.tpgroup_name, lo1.lookup_desc AS tp_site_category, reg1.region_name AS tp_region, loc2.nama AS tp_kabupaten, area.area_code
				FROM t_mtr_site site
				LEFT JOIN t_mtr_tower_provider tp ON tp.tp_id::VARCHAR = site.tp_id AND tp.active = 1
				LEFT JOIN t_mtr_tp_company tp_co ON tp_co.company_id = tp.company_id
				LEFT JOIN t_mtr_tp_company_group tp_grp ON tp_grp.tpgroup_id = tp_co.tpgroup_id
				LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = tp.site_category::VARCHAR AND lo1.lookup_key = 'tp_category' AND lo1.active = 1
				LEFT JOIN t_mtr_region reg1 ON reg1.region_id = tp.region_id AND reg1.status = 1
				LEFT JOIN t_mtr_lokasi loc1 ON loc1.lokasi_id = tp.propinsi_id AND loc1.jenis = 'P' AND loc1.status = 1
				LEFT JOIN t_mtr_lokasi loc2 ON loc2.lokasi_id = tp.city_id AND loc2.jenis = 'KB' AND loc2.status = 1
				LEFT JOIN t_mtr_site_area_code area ON area.area_id = site.area_id AND area.active = 1
				WHERE site.site_id = {$site_id}
			")
			->row_array();
	}
	
	public function get_site_by_sitecode($site_code)
	{
		return $this->db
			->query("SELECT * FROM ".$this->db->dbprefix('t_mtr_site')." WHERE site_code = '{$site_code}'")
			->row_array();
	}
	
	public function get_site_keepers_by_id($site_id)
	{
		$this->db->where('site_id', $site_id);
		
		$query = $this->db->get('t_mtr_site_l_keepers');
		
		return $query->row_array();
	}
	
	public function get_site_type()
	{
		$this->db->order_by('nama', 'ASC');
		$this->db->where('status', 1);
		
		$query = $this->db->get('t_mtr_site_type');
		
		return $query->result_array();
	}
	
	public function get_site_profile()
	{
		$this->db->order_by('descr', 'ASC');
		$this->db->where('status', 1);
		
		$query = $this->db->get('t_mtr_site_profile');
		
		return $query->result_array();
	}
	
	public function get_site_specification()
	{
		$this->db->order_by('descr', 'ASC');
		$this->db->where('status', 1);
		
		$query = $this->db->get('t_mtr_site_specification');
		
		return $query->result_array();
	}
	
	public function check_old_site_id($old_site_id)
	{
		$this->db
			->where('upper(site_code_old)', strtoupper($old_site_id))
			->where('site_code_old IS NOT NULL')
			->where('site_active', 1);
		
		$query = $this->db->get('t_mtr_site');
		
		if ($query->num_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function check_site_keepers($site_id)
	{
		$this->db->where('site_id', $site_id);
		
		$query = $this->db->get('t_mtr_site_l_keepers');
		
		if ($query->num_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function get_propinsi()
	{
		$this->db->where('status', 1);
		$this->db->order_by('descr', 'ASC');
		
		$query = $this->db->get('t_mtr_propinsi');
		
		return $query->result_array();
	}
	
	public function get_kabupaten($propinsi_id = '')
	{
		$this->db->where('propinsi_id', $propinsi_id);
		$this->db->where('status', 1);
		$this->db->order_by('descr', 'ASC');
		
		$query = $this->db->get('t_mtr_kabupaten');
		
		return $query->result_array();
	}
	
	public function get_kecamatan($kabupaten_id = '')
	{
		$this->db->where('kabupaten_id', $kabupaten_id);
		$this->db->where('status', 1);
		$this->db->order_by('descr', 'ASC');
		
		$query = $this->db->get('t_mtr_kecamatan');
		
		return $query->result_array();
	}
	
	public function get_tower_provider()
	{
		$this->db->where('active', 1);
		
		$query = $this->db->get('t_mtr_tower_provider');
		
		return $query->result_array();
	}
	
	public function get_cme_partner($key)
	{
		$this->db->like('partner_name', $key);
		$this->db->where('status', 1);
		$this->db->limit(20);
		
		$query = $this->db->get('t_mtr_partner');
		
		return $query->result_array();
	}
	
	public function get_tenant($key)
	{
		$this->db->like('UPPER(tenant_name)', strtoupper($key));
		$this->db->where('status', 1);
		$this->db->limit(20);
		
		$query = $this->db->get('t_mtr_tenant');
		
		return $query->result_array();
	}
	
	public function get_privilege_actions($menu_id, $user_group_id)
	{
		$this->db->select('
			COALESCE (menu_add, 0) AS menu_add,
			COALESCE (menu_view, 0) AS menu_view,
			COALESCE (menu_edit, 0) AS menu_edit,
			COALESCE (menu_delete, 0) AS menu_delete,
			COALESCE (menu_approve, 0) AS menu_approve,
			COALESCE (menu_reject, 0) AS menu_reject'
			, FALSE
		);
		$this->db->where('user_group_id', $user_group_id);
		$this->db->where('menu_id', $menu_id);
		
		$query = $this->db->get('t_mtr_user_role');
		
		return $query->row_array();
	}
	
	public function load_site_info()
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$search = $this->input->get('query');
		$order = $this->input->get('order');
		$sort = $this->input->get('sort');
		
		$region = array();
		foreach ($this->session->userdata['user_region'] as $reg) {
			if ($reg['region_id'] != '') {
				$region[] = $reg['region_id'];
			}
		}
		
		$where = "
			active = 1 AND (
			UPPER(site.site_code) LIKE '%".strtoupper($search)."%'
			OR UPPER(site.site_name) LIKE '%".strtoupper($search)."%'
			OR UPPER(site.tp_name) LIKE '%".strtoupper($search)."%'
			OR UPPER(site.vendor_id) LIKE '%".strtoupper($search)."%'
			OR UPPER(reg.region_name) LIKE '%".strtoupper($search)."%'
			)
		";
		if (count($region > 0)) {
			$where .= "AND site.region_id IN (".implode(', ', $region).")";
		}
		
		$sql = "
			SELECT site.site_id, site.site_code_old, site.site_code, site.site_name, site.tp_name, site.vendor_name, site.vendor_id, reg.region_name, site.latitude, site.longitude, site.site_address, site.status
			FROM t_mtr_site site
			LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
			WHERE {$where}			
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		if ($order) {
			$sql .= " ORDER BY {$order} {$sort}";
		} else {
			$sql .= " ORDER BY site_id DESC";
		}
		
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/index');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 3;		
		
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = 'Next &rsaquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&lsaquo; Prev';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);
		
		$total_rows_perpage = $query->num_rows();
		
		$results = $query->result_array();
		$rows = array();
		foreach ($results as $r) {
			$r['site_id'] = $this->core->encode($r['site_id']);
			$rows[] = $r;
		}
		
		$data['total_rows'] = $total_rows;
		$data['rec_info']= 'Records from '.($total_rows > 0 ? ($offset + 1) : 0).' to '.($offset + $total_rows_perpage).' of '.$total_rows;
		$data['data'] = $rows;
		$data['pagination'] = $this->pagination->create_links();
		$data['sql'] = str_replace("\t", ' ', str_replace("\n", '', $sql));
		
		return $data;
	}
	
	public function load_site_candidate($site_id)
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$search = $this->input->get('query');
		
		$where = " b.site_id = {$site_id}";
		$where .= " AND (UPPER(b.site_name) LIKE '%".strtoupper($search)."%' OR UPPER(b.site_code) LIKE '%".strtoupper($search)."%' OR UPPER(d.partner_name) LIKE '%".strtoupper($search)."%')";
		$sql = "
			SELECT b.site_id AS nomsite_id,b.site_code, b.site_name, a.site_id, d.partner_name as tp_name, d.rank, c.latitude, c.longitude, c.address, a.status, c.tp_id,
			( 1000*6371 * acos( cos( radians(b.latitude) ) * cos( radians( c.latitude ) ) * cos( radians( c.longitude ) - radians(b.longitude) ) + sin( radians(b.latitude) ) * sin( radians( c.latitude ) ) ) )AS distance
			FROM t_site_tp_kandidat AS a
			JOIN t_mtr_site AS b ON a.site_id = b.site_id
			JOIN t_mtr_tower_provider AS c ON a.tp_id = c.tp_id
			left join t_mtr_partner d ON d.partner_id = c.partner_id WHERE {$where}
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		$sql .= " ORDER BY site_id DESC";
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/index');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 3;
		
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = 'Next &rsaquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&lsaquo; Prev';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);		
		
		$total_rows_perpage = $query->num_rows();
		
		$data['total_rows'] = $total_rows;
		$data['rec_info']= 'Records from '.($total_rows > 0 ? ($offset + 1) : 0).' to '.($offset + $total_rows_perpage).' of '.$total_rows;
		$data['data'] = $query->result_array();
		$data['pagination'] = $this->pagination->create_links();
		$data['sql'] = str_replace("\t", ' ', str_replace("\n", '', $sql));
		
		return $data;
	}
	
	public function load_problem()
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$site_id = $this->input->get('site_id');
		$search = $this->input->get('query');
		$order = $this->input->get('order');
		$sort = $this->input->get('sort');
		
		$where = " p.active = 1 AND p.site_id = {$site_id}";
		$where .= " AND (UPPER(problem_descr) LIKE '%".strtoupper($search)."%' OR UPPER(p.problem_solution) LIKE '%".strtoupper($search)."%')";
		$sql = "
			SELECT p.problem_id, p.date_raised AS open_date, p.problem_descr, p.close_date, p.statuss AS status, p.problem_solution AS impact, u.user_name AS issued_by, l.lookup_desc AS problem_group
			FROM t_trx_problem p 
			LEFT JOIN t_mtr_user u ON u.user_id = p.problem_creator
			LEFT JOIN t_mtr_lookup l ON l.lookup_id = p.problem_group AND l.lookup_key = 'problem_group'
			WHERE {$where}			
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		if ($order) {
			$sql .= " ORDER BY {$order} {$sort}";
		} else {
			$sql .= " ORDER BY p.problem_id DESC";
		}
		
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/index');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 3;		
		
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = 'Next &rsaquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&lsaquo; Prev';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);
		
		$total_rows_perpage = $query->num_rows();
		
		$data['total_rows'] = $total_rows;
		$data['rec_info']= 'Records from '.($total_rows > 0 ? ($offset + 1) : 0).' to '.($offset + $total_rows_perpage).' of '.$total_rows;
		
		$results = $query->result_array();
		$rows = array();
		foreach ($results as $r) {
			$r['open_date'] = date_format(date_create($r['open_date']), 'd-M-Y H:i');
			$r['close_date'] = $r['close_date'] != '' ? date_format(date_create($r['close_date']), 'd-M-Y H:i') : '&nbsp;';
			$r['problem_descr'] = word_limiter($r['problem_descr'], 10);
			
			$detail = $this->db
				->select('pd.problem_detail_desc, u.user_name AS recipe_to')
				->join('t_mtr_user u', 'u.user_id = pd.recipe_id', 'left')
				->where('pd.problem_id', $r['problem_id'])
				->get('t_trx_problem_detail pd')
				->result_array();
			
			$r['detail'] = $detail;
			$rows[] = $r;
		}
		
		$data['data'] = $rows;
		$data['pagination'] = $this->pagination->create_links();
		$data['sql'] = str_replace("\t", ' ', str_replace("\n", '', $sql));
		
		return $data;
	}
	
	public function load_equipment_list()
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
		$site_id = $this->input->get('site_id');
		$search = $this->input->get('query');
		$order = $this->input->get('order');
		$sort = $this->input->get('sort');
		
		$where = " e.active = 1 AND e.site_id = {$site_id}";
		$where .= " AND (UPPER(s.site_code) LIKE '%".strtoupper($search)."%'";
		$where .= " OR UPPER(s.site_name) LIKE '%".strtoupper($search)."%'";
		$where .= " OR UPPER(f.lookup_desc) LIKE '%".strtoupper($search)."%'";
		$where .= " OR UPPER(m.morphology_name) LIKE '%".strtoupper($search)."%'";
		$where .= " OR UPPER(b.bts_mode_name) LIKE '%".strtoupper($search)."%'";
		$where .= " OR UPPER(br.brand_name) LIKE '%".strtoupper($search)."%'";
		$where .= " OR UPPER(t.bts_type_name) LIKE '%".strtoupper($search)."%')";
		$sql = "
			SELECT e.equipment_id, s.site_code, s.site_name, f.lookup_value AS site_profile_name, m.morphology_name, b.bts_mode_name, br.brand_name, t.bts_type_name
			FROM t_mtr_site_l_equipment e
			LEFT JOIN t_mtr_site s ON s.site_id = e.site_id
			LEFT JOIN t_mtr_lookup f ON CAST(f.lookup_value AS INTEGER) = e.site_profile_id AND lookup_key = 'site_profile'
			LEFT JOIN t_mtr_morphology m ON m.morphology_id = e.morphology
			LEFT JOIN t_mtr_bts_mode b ON b.bts_mode_id = e.bts_mode
			LEFT JOIN t_mtr_bts_brand br ON br.brand_id = e.bts_brand
			LEFT JOIN t_mtr_bts_type t ON t.bts_type_id = e.bts_type
			WHERE {$where}			
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		if ($order) {
			$sql .= " ORDER BY {$order} {$sort}";
		} else {
			$sql .= " ORDER BY equipment_id DESC";
		}
		
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/equipment/index');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;		
		
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = 'Next &rsaquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&lsaquo; Prev';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);
		
		$total_rows_perpage = $query->num_rows();
		
		$data['total_rows'] = $total_rows;
		$data['rec_info']= 'Records from '.($total_rows > 0 ? ($offset + 1) : 0).' to '.($offset + $total_rows_perpage).' of '.$total_rows;
		$data['data'] = $query->result_array();
		$data['pagination'] = $this->pagination->create_links();
		$data['sql'] = str_replace("\t", ' ', str_replace("\n", '', $sql));
		
		return $data;
	}
	
	function save_data($tableName, $data, $key=''){

        if (empty($data[$key])){
	        unset($data[$key]);
	        if (isset($data['created_by'])) {
				$data['created_by']=$this->session->userdata('user_id');
			}
	        if (isset($data['created_on'])) {
				$data['created_on']=date('Y-m-d H:i:s');
			}
			if (isset($data['created_date'])) {
				$data['created_date']=date('Y-m-d H:i:s');
			}
		    $q=$this->db->insert($tableName,$data);
	      if ($q)
	        $q=$this->db->insert_id();
	    }else{
                $this->db->start_cache();
	        $this->db->where($key,$data[$key]);            
                $this->db->stop_cache();
                $history=$this->db->get($tableName)->row();
                $metadata=$this->db->field_data($tableName);
                foreach($metadata as $k=>$value){
                    $meta[$value->name]=$value->type;  
                }
                foreach($history as $k=>$value){
                    if(isset($data[$k])){
                        if($meta[$k]=='timestamp')
                            $data[$k]=MakeUpDate($data[$k],'Ymd','Y-m-d 00:00:00');
                        if ($data[$k]!= $value)
                            $edit[$k]=$value;
                    }

                    if($k=='updated_by'){
                        if($value!=''){
                            $edit[$k]=$value;
                        }else{
                            $edit[$k]=$history->created_by;
                        }
                    }                
                    
                    if($k=='updated_on'){
                        if($value!=''){
                            $edit[$k]=$value;
                        }else{
                            $edit[$k]=$history->created_on;
                        }
                    }
                    
                    if($k=='updated_date'){
                        if($value!=''){
                            $edit[$k]=$value;
                        }else{
                            $edit[$k]=$history->created_date;
                        }
                    }
                }
                
	        if (isset($data['updated_by'])) {
                    $data['updated_by']=$this->session->userdata('user_id');
                }
                
	        if (isset($data['updated_on'])) {
                    $data['updated_on']=date('Y-m-d H:i:s');
                }
                
                if (isset($data['updated_date'])) {
                    $data['updated_date']=date('Y-m-d H:i:s');
                }
                
	        unset($data[$key]);
	        $q=$this->db->update($tableName,$data);    
                $this->db->flush_cache();  
                
                if(isset($edit)){
                    if(count($edit)){
                        $this->db->set('table_name',$tableName);
                        $this->db->set('pk_table',$key);
                        $this->db->set('pk_value',$history->$key);
                        $this->db->set('created_by',$edit['updated_by']);
                        
                        if (isset($edit['updated_on'])) {
                            $this->db->set('created_on',$edit['updated_on']);
                        }
                        
                        if (isset($edit['updated_date'])) {
                            $this->db->set('created_on',$edit['updated_date']);
                        }

                        $this->db->insert('t_trx_history_head');
                        $id=$this->db->insert_id();

                        if (isset($edit['updated_on'])) {
                                //unset($edit['updated_on']);
                        }
                        
                        if (isset($edit['updated_date'])) {
                                //unset($edit['updated_date']);
                        }
                                            //unset($edit['updated_by']);

                        foreach($edit as $k=>$v){
                            $this->db->set('parent_id',$id)
                                     ->set('field_name',$k)
                                     ->set('value_before',$v)
                                     ->set('value_after',$data[$k]);
                            $this->db->insert('t_trx_history_detail');
                        }
                    }
                }
	    }

            return $q;
	}
	
	public function check_siteid_exist()
	{
		$site_id = (int) $this->input->get('site_id');
		$site_code = $this->input->get('site_code');
		
		$res = $this->db
			->query("SELECT * FROM ".$this->db->dbprefix('t_mtr_site')." WHERE site_code = '{$site_code}' AND site_id != {$site_id}")
			->row_array();
			
		if ($res) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
        
        function get_site_lookup($d = NULL, $rows = 0, $offset = 0) { //andedi 20141022
            $dblokal = $this->load->database("default", TRUE);
            $qrs = "";
            $qr = "
                select 
                ms.site_id, 
                ms.site_code, 
                ms.site_name, 
                ms.vendor_id, 
                mr.region_name 
                from t_mtr_site ms 
                left join t_mtr_region mr on ms.region_id=mr.region_id 
                where 
                ms.status=1 
                AND ms.active=1 ";

            if (isset($d)) {
                $q = $d;
                $or_arr = array('ms.site_code', 'ms.site_name', 'ms.vendor_id', 'mr.region_name');
                $or_str = "";
                
                foreach ($or_arr as $val) {
                    if ($or_str == "")
                        $or_str.="($val ilike '%" . $q . "%'";
                    else
                        $or_str.=" or $val ilike '%" . $q . "%'";
                }

                $or_str.=")";
                $qr .= "AND " . $or_str;
            }

            if ($rows != 0) {
                $qrs .= $qr . " LIMIT " . $rows . " OFFSET " . $offset . " ";
            }

            $queryc = $dblokal->query($qr);
            $total = $queryc->num_rows;
            $query = $dblokal->query($qrs);
            $result["total"] = $total;
            $items = array();

            foreach ($query->result() as $row) {
                array_push($items, $row);
            }
            $result["rows"] = $items;
            
            return $result;
        }

        function get_address($site_id=0) { //andedi 20141022
            $sql    = 'SELECT site_address FROM t_mtr_site WHERE site_id=?';
            $query  = $this->db->query($sql, array($site_id));
            return $query->row();
        }
        
        function insert_relocation($data) { //andedi 20141023
            $sql = "INSERT INTO t_trx_site_relocation (vendor_id, relocation_type, dismantle_start_date, dismantle_end_date, site_destination, cause, doc_name, remark, created_by, created_on, active) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $query  = $this->db->query($sql, array(
                $data['vendor_id'],
                $data['relocation_type'],
                $data['dismantle_start_date'],
                $data['dismantle_end_date'],
                $data['site_destination'],
                $data['cause'],
                $data['doc_name'],
                $data['remark'],
                $data['created_by'],
                $data['created_on'],
                $data['active']
            ));
            return $query;
        }
        
        function update_relocation($data) { //andedi 20141024
            $sql = "
                UPDATE t_trx_site_relocation 
                SET vendor_id = ?, 
                relocation_type = ?, 
                dismantle_start_date = ?, 
                dismantle_end_date = ?, 
                site_destination = ?, 
                cause = ?, 
                doc_name = ?, 
                remark = ?, 
                updated_by = ?, 
                updated_on = ?, 
                active = ?
                WHERE site_relocation_id = ?
                ";
            $query  = $this->db->query($sql, array(
                $data['vendor_id'],
                $data['relocation_type'],
                $data['dismantle_start_date'],
                $data['dismantle_end_date'],
                $data['site_destination'],
                $data['cause'],
                $data['doc_name'],
                $data['remark'],
                $data['updated_by'],
                $data['updated_on'],
                $data['active'],
                $data['site_relocation_id']
            ));
            return $query;
        }
        
        function get_data_reloc($param) { //andedi 20141023
            $sql = '
                SELECT
                A.site_relocation_id, A.vendor_id, A.relocation_type, 
                to_char(A.dismantle_start_date, \'DD/MM/YYYY\') AS "dismantle_start_date",
                to_char(A.dismantle_end_date, \'DD/MM/YYYY\') AS "dismantle_end_date",
                A.cause, A.doc_name, A.remark, B.site_code AS "site_destination"
                FROM t_trx_site_relocation A
                LEFT JOIN t_mtr_site B ON B.site_id = A.site_destination
                ORDER BY A.site_relocation_id DESC  
                LIMIT ? OFFSET ?';
            $query = $this->db->query($sql, array($param['limit'], $param['offset']));
            return $query->result();
        }
        
        function get_data_reloc_by_id($id) { //andedi 20141023
            $sql = '
                SELECT
                A.site_relocation_id,
                A.vendor_id, 
                A.relocation_type, 
                to_char(A.dismantle_start_date, \'DD-MM-YYYY\') AS "dismantle_start_date",
                to_char(A.dismantle_end_date, \'DD-MM-YYYY\') AS "dismantle_end_date",
                A.cause,
                A.doc_name,
                A.remark,
                B.site_id AS "site_destination",
                B.site_code AS "site_destination_code",
                B.site_address AS "site_destination_address"
                FROM t_trx_site_relocation A
                LEFT JOIN t_mtr_site B ON B.site_id = A.site_destination
                WHERE A.site_relocation_id = ?';
            $query = $this->db->query($sql, array($id));
            return $query->row();
        }
        
        function get_data_reloc_count() { //andedi 20141023
            $sql = "
                SELECT 
                vendor_id 
                FROM t_trx_site_relocation 
                ORDER BY dismantle_start_date";
            $query = $this->db->query($sql);
            return $query->num_rows();
        }
        
}
