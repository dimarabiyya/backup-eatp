<?php

class Season extends MX_Controller {

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
		$data['fields'] = $this->db->list_fields('t_mtr_site_season');
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->get('cmd') == 'get_site_season') {
				
				$json = array();
				
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page-1) * $rows;
				$search = $this->input->get('query');
				$season_name = $this->input->get('season_name');
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'season.site_id';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
				
				$fields = $this->db->list_fields('t_mtr_site_season');
				$f = array();
				foreach ($fields as $field) {
					$f[] = 'season.'.$field;
				}
				
				$where = "WHERE 1 = 1";
				$where .= " AND (UPPER(site.site_code) LIKE '%".strtoupper($search)."%'";
				
				$fields = $this->db->list_fields('t_mtr_site_season');
				foreach ($fields as $field) {
					$where .= " OR (UPPER(CAST(season.{$field} AS VARCHAR)) LIKE '%".strtoupper($search)."%')";
				}
				
				$where .= ")";
				
				$sql = "
					SELECT ".implode(', ', $f).", site.site_code
					FROM t_mtr_site_season season
					LEFT JOIN t_mtr_site site ON site.site_id = season.site_id
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
				$json['sql'] = str_replace("\n", ' ', str_replace("\t", '', $sql));
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'get_lookup_sites') {
			
				$data = array();
				
				if ($this->input->get('act') == 'get_data') {
				
					$json = array();
				
					$site_id = $this->input->get('site_id');
					$page = $this->input->get('page') ? $this->input->get('page') : 1;
					$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
					$offset = ($page - 1) * $rows;
					$search = $this->input->get('query');
					$season_name = $this->input->get('season_name');
					$season_value = $this->input->get('season_value');
					$sort = $this->input->get('sort') ? $this->input->get('sort') : 'site.updated_on';
					$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
					
					$region = array();
					foreach ($this->session->userdata['user_region'] as $reg) {
						if ($reg['region_id'] != '') {
							$region[] = $reg['region_id'];
						}
					}
					
					$where = "
						WHERE site.active = 1
						AND (UPPER(site.site_code) LIKE '%".strtoupper($search)."%'
						OR UPPER(site.site_code_old) LIKE '%".strtoupper($search)."%'
						OR UPPER(site.site_name) LIKE '%".strtoupper($search)."%'
						OR UPPER(site.vendor_id) LIKE '%".strtoupper($search)."%'
						OR UPPER(reg.region_name) LIKE '%".strtoupper($search)."%')
					";
					
					if (count($region > 0)) {
						$where .= "AND site.region_id IN (".implode(', ', $region).")";
					}
					if ($season_name != '0') {
						$where .= "AND site.site_id NOT IN (SELECT site_id FROM t_mtr_site_season WHERE {$season_name} = '{$season_value}')";
					}
					
					$sql = "
						SELECT site.site_id, site.site_code, site.site_code_old, site.site_name, site.vendor_id, reg.region_name
						FROM t_mtr_site site
						LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
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
						$r['site_id'] = $this->core->encode($r['site_id']);
						$rows[] = $r;
					}
					
					$json['rows'] = $rows;
					$json['sql'] = str_replace(array("\n", "\t"), ' ', $sql);
					
					echo json_encode($json);
					exit;
				}
				
				echo $this->parser->parse('partials/lookup_season_sites', $data);
				exit;
			}
		
			if ($this->input->get('cmd') == 'delete_site_season') {
			
				$json = array();
				$id = (int) $this->core->decode($this->input->get('id'));
				
				$this->db->delete('t_mtr_site_season', array('site_id' => $id));
				
				$json = array('message' => 'Site Season data has been deleted successfully.');
				
				echo json_encode($json);
				exit;
			}
		}
		
		$data['content_id'] = 'season_list';
		
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
		
			if ($this->input->post('cmd') == 'add_site_season') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['seasonName'])) {
					$error[] = 'Season Name can not empty.';
				}
				if ($this->_is_field_empty($post['seasonValue'])) {
					$error[] = 'Season Value can not empty.';
				}
				if (@$this->_is_field_empty($post['sites'])) {
					$error[] = 'Site can not empty.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					foreach ($post['sites'] as $site) {
						$site_id = (int) $this->core->decode($site);
						
						$this->core->set['site_id'] = $site_id;
						$this->core->set[$post['seasonName']] = $post['seasonValue'];
						
						if ( $this->core->view_row('t_mtr_site_season', array('site_id' => $site_id)) ) {
							$this->core->save('t_mtr_site_season', array('site_id' => $site_id));
						} else {
							$this->core->save('t_mtr_site_season');
						}
					}
					
					$json = array('error' => FALSE, 'message' => 'Site Season has been added successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		}
		
		$this->_form_season();
	}
	
	public function edit($id = 0)
	{
		$this->_check_valid_loggedin();
		
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_edit')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$id = (int) $this->core->decode($id);
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->post('cmd') == 'edit_site_season') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['seasonName'])) {
					$error[] = 'Season Name can not empty.';
				}
				if ($this->_is_field_empty($post['seasonValue'])) {
					$error[] = 'Season Value can not empty.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					$this->core->set[$post['seasonName']] = $post['seasonValue'];
					
					$this->core->save('t_mtr_site_season', array('site_id' => $id));
					
					$json = array('error' => FALSE, 'message' => 'Site Season has been updated successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		}
		
		$this->_form_season($id);
	}
	
	public function view($id = 0)
	{
		$this->_check_valid_loggedin();
		
		$id = (int) $this->core->decode($id);
		
		$data['fields'] = $this->db->list_fields('t_mtr_site_season');
		$data['content_id'] = 'season_view';
		
		$res = $this->db
			->query("
			SELECT season.*, site.site_code
			FROM t_mtr_site_season season
			LEFT JOIN t_mtr_site site ON site.site_id = season.site_id
			WHERE season.site_id = '{$id}'
		")
		->row_array();
			
		if ($res) {
			$data['season'] = $res;
		}
		
		$this->load->view('template/smashing', $data);
	}
	
	private function _form_season($id = 0)
	{
		$data['fields'] = $this->db->list_fields('t_mtr_site_season');
		$data['content_id'] = 'season_form';
		
		if ($this->uri->segment(3) == 'edit') {
			$res = $this->db
				->query("
				SELECT season.*, site.site_code
				FROM t_mtr_site_season season
				LEFT JOIN t_mtr_site site ON site.site_id = season.site_id
				WHERE season.site_id = '{$id}'
			")
			->row_array();
			
			if ($res) {
				$data['season'] = $res;
			}
		}
		
		$this->load->view('template/smashing', $data);
	}
	
	public function season_name()
	{
		if ($this->core->is_ajax()) {
		
			if ($this->input->get('cmd') == 'get_season_name') {
				
				$json = array();
				
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page-1) * $rows;
				$search = $this->input->get('query');
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'column_name';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
				
				$where = "WHERE table_name = 't_mtr_site_season' AND column_name != 'site_id'";
				
				if (trim($search) != '') {
					$where .= " AND (UPPER(column_name) LIKE '%".$this->slugify($search)."%')";
				}
				
				$sql = "
					SELECT column_name
					FROM information_schema.columns
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
					$rows[] = $r;
				}
				
				$json['total'] = $total_rows;
				$json['rows'] = $rows;
				$json['sql'] = str_replace("\n", ' ', str_replace("\t", '', $sql));
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'delete_season_name') {
			
				$json = array();
				
				$this->db->query("ALTER TABLE t_mtr_site_season DROP COLUMN ".$this->input->get('id'));
				
				$json = array('message' => 'Season Name data has been deleted successfully.');
				
				echo json_encode($json);
				exit;
			}
		}
		
		$data['content_id'] = 'season_name_list';
		
		$this->load->view('template/smashing', $data);
	}
	
	public function add_season_name()
	{
		$this->_check_valid_loggedin();
		
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_add')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->post('cmd') == 'add_season_name') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['seasonName'])) {
					$error[] = 'Season Name can not empty.';
				}
				
				if ( ! $this->_is_field_empty($post['seasonName']) && $this->db->field_exists($this->slugify($post['seasonName']), 't_mtr_site_season') ) {
					$error[] = 'Season Name already exist.';
				}
				
				if (count($error) == 0) {
					
					$this->core->db_debug = TRUE;
					
					$this->db->query("ALTER TABLE t_mtr_site_season ADD COLUMN ".$this->slugify($post['seasonName'])." VARCHAR(100) DEFAULT NULL");
					
					$json = array('error' => FALSE, 'message' => 'Season Name has been added successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		}
		
		$this->_form_season_name();
	}
	
	public function edit_season_name($id = '')
	{
		$this->_check_valid_loggedin();
		
		if ( ! $this->mmenu->cekPermission($this->_partials['menu'], 'menu_edit')) {
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->post('cmd') == 'edit_season_name') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['seasonName'])) {
					$error[] = 'Season Name can not empty.';
				}
				
				if ( ! $this->_is_field_empty($post['seasonName']) && $this->db->field_exists($this->slugify($post['seasonName']), 't_mtr_site_season') ) {
					$error[] = 'Season Name already exist.';
				}
				
				if (count($error) == 0) {
					
					$this->core->db_debug = TRUE;
					
					$this->db->query("ALTER TABLE t_mtr_site_season RENAME COLUMN ".$id." TO ".$this->slugify($post['seasonName']));
					
					$json = array('error' => FALSE, 'message' => 'Season Name has been updated successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		}
		
		$this->_form_season_name($id);
	}
	
	private function _form_season_name($id = '')
	{
		$data['content_id'] = 'season_name_form';
		
		if ($this->uri->segment(3) == 'edit_season_name') {
			$res = $this->db
				->query("
				SELECT column_name
				FROM information_schema.columns
				WHERE column_name = '{$id}'
			")
			->row_array();
			
			if ($res) {
				$data['season_name'] = $res;
			}
		}
		
		$this->load->view('template/smashing', $data);
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

	private function slugify($text)
	{ 
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '_', $text);

		// trim
		$text = trim($text, '_');

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
		{
			return 'n-a';
		}

		return $text;
	}
}