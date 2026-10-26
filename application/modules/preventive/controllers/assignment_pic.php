<?php

class Assignment_pic extends MX_Controller {

	public function __construct()
	{
		$this->load->library('parser');
	}
	
	public function index()
	{
		$this->_check_valid_loggedin();
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->get('cmd') == 'get_lookup_users') {
			
				$data = array();
				
				if ($this->input->get('act') == 'get_data') {
			
					$json = array();
				
					$page = $this->input->get('page') ? $this->input->get('page') : 1;
					$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
					$offset = ($page - 1) * $rows;
					$search  = $this->input->get('query');
					$keyword = $search['keyword'];
					$region  = $search['region'];
					$roles   = $search['roles'];
					$sort = $this->input->get('sort') ? $this->input->get('sort') : 'usr.user_name';
					$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
					
					$where = "WHERE usr.status = 1 AND grp.area_restriction = 'site_assignment'";
					if($keyword != '') {
						$kw = "(UPPER(usr.user_name) LIKE '%".strtoupper($keyword)."%' OR lower(usr.user_id) like '%".strtolower($keyword)."%')";
					}
					if($region != '') {
						$rg = " AND f_user_content_region(usr.user_id, $region) is true";
						if($keyword == '') {
							$rg = substr($rg, 4);
						}
					}
					if($roles != '') {
						$rl = " AND f_user_content_roles(usr.user_id, $roles) is true";
						if($keyword == '' AND $region == ''){
							$rl = substr($rl, 4);
						}
					}
					if($keyword OR $region OR $roles) {
						$where .= " AND ($kw $rg $rl)";
					}

					$sql = "SELECT distinct mrole.user_id, usr.user_name, f_grouped_user_role(mrole.user_id) as group_caption, f_grouped_user_region(mrole.user_id) as user_region FROM t_mtr_user_multi_role mrole JOIN t_mtr_user usr ON usr.user_id = mrole.user_id JOIN t_mtr_user_group grp ON grp.user_group_id = mrole.user_group_id {$where} ORDER BY {$sort} {$order}";
					// die($sql);
					$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
					
					$total_rows = (int) $query->row()->total;
					
					$sql .= " LIMIT {$rows} OFFSET {$offset}";
					
					$query = $this->db->query($sql);
					// echo $sql;
					$json['total'] = $total_rows;
					$json['rows'] = $query->result_array();
					echo json_encode($json);
					exit;
				}
				
				echo $this->parser->parse('lookup_users', $data);
				exit;
			}
		
			if ($this->input->get('cmd') == 'get_lookup_sites') {
				$data = array();
				$user_id = $this->input->get_post('user_id');
				if ($this->input->get('act') == 'get_data') {
				
					$json = array();
				
					$site_id = $this->input->get('site_id');
					$page = $this->input->get('page') ? $this->input->get('page') : 1;
					$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
					$offset = ($page - 1) * $rows;
					$search = $this->input->get('query');
					$keyword = $search['keyword'];
					$region = $search['region'];
					$cluster_ops_id = $search['cluster_ops_id'];
					$sort = $this->input->get('sort') ? $this->input->get('sort') : 'ss.updated_on';
					$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
					
		

					$where = "WHERE ss.active = 1";
					
					if($cluster_ops_id != '') {
						$where .= " and cc.cluster_ops_id = $cluster_ops_id";
					}
					if($region != '') {
						$where .= " and rr.region_id = $region";
					}
					else {
						$where .= " and rr.region_id in (SELECT distinct region_id from t_mtr_user_territory where user_id = '$user_id')";
					}
					if($keyword != '' ) {
						$where .= " AND (UPPER(ss.site_code) LIKE '%".strtoupper($keyword)."%'
	                 OR UPPER(ss.site_code_old) LIKE '%".strtoupper($keyword)."%'
	                 OR UPPER(ss.site_name) LIKE '%".strtoupper($keyword)."%'
	                 OR UPPER(ss.vendor_id) LIKE '%".strtoupper($keyword)."%'
	                 OR UPPER(rr.region_name) LIKE '%".strtoupper($keyword)."%'
	                 OR UPPER(ss.tp_name) LIKE '%".strtoupper($keyword)."%'
	                 OR CAST(ss.latitude AS VARCHAR) LIKE '%".strtoupper($keyword)."%'
	                 OR CAST(ss.longitude AS VARCHAR) LIKE '%".strtoupper($keyword)."%')";
					}
					
					$sql = "SELECT ss.site_id, ss.site_code, ss.site_code_old, ss.site_name, ss.tp_name, ss.vendor_id, rr.region_name, ss.latitude, ss.longitude, cc.cluster_name, CASE ss.status WHEN 0 THEN 'Nom' WHEN 1 THEN 'Active' END AS status FROM t_mtr_site ss JOIN t_mtr_region rr ON rr.region_id = ss.region_id
						LEFT JOIN t_mtr_cluster_ops cc on cc.cluster_ops_id = ss.cluster_ops_id
						{$where} ORDER BY {$sort} {$order}
					";
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
					$total_rows = (int) $query->row()->total;
					$sql .= " LIMIT {$rows} OFFSET {$offset}";
					//die($sql);
					$query = $this->db->query($sql);
					$json['total'] = $total_rows;
					$rows = array();
					foreach ($query->result_array() as $r) {
						$r['site_id'] = $this->core->encode($r['site_id']);
						$rows[] = $r;
					}
					$json['rows'] = $rows;
					$json['sql'] = $sql;
					echo json_encode($json);
					exit;
				}

			/*
				$this->db->select('region_id')->distinct();
							$this->db->where('user_id',$user_id);
							$region_id = $this->db->get('t_mtr_user_territory')->row_array();
							
							if($region_id['region_id'] !=''){
								$this->db->where('region_id',$region_id['region_id']);
								}
								$this->db->where('status',1);
								  $q = $this->db->get('t_mtr_cluster_ops');		
							$data['cluster'] = $q->result_array();
			*/
			
				//var_dump($data['cluster']);

				$data['user_id'] = $user_id;
				$this->load->model('mpreventive');
				$data['regions'] = $this->mpreventive->getUserRegions($user_id);
				echo $this->parser->parse('lookup_sites', $data);
				exit;
			}
		
			if ($this->input->get('cmd') == 'get_assignment_pic_list') {
				
				$json = array();
				
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$search = $this->input->get('query');
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'usr.user_name';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
				
				$where = "WHERE usr.status = 1 AND grp.user_group_caption = 'PICS' AND (";
				$where .= " UPPER(usr.user_name) LIKE '%".strtoupper($search)."%'";
				$where .= ")";
				$where .= " AND usr.user_id IN (SELECT pic_smart FROM t_mtr_site)";
				
				$sql = "
					SELECT mrole.user_id, usr.user_name
					FROM t_mtr_user_multi_role mrole
					LEFT JOIN t_mtr_user usr ON usr.user_id = mrole.user_id
					LEFT JOIN t_mtr_user_group grp ON grp.user_group_id = mrole.user_group_id
					{$where}
					ORDER BY {$sort} {$order}
				";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				$json['rows'] = array();
				foreach ($query->result_array() as $r) {
					$sqls = $this->db
						->query("SELECT site_code FROM t_mtr_site WHERE pic_smart = '".$r['user_id']."'")
						->result_array();
					
					$r['sites'] = array();
					foreach ($sqls as $q) {
						$r['sites'][] = $q['site_code'];
					}
					
					$r['user_id'] = $this->core->encode($r['user_id']);
					$json['rows'][] = $r;
				}
				
				$json['sql'] = $sql;
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'delete_assignment') {
			
//				$json = array();
				$id = $this->core->decode($this->input->get('id'));
				
				$this->db->query("UPDATE t_mtr_site SET pic_smart = NULL WHERE pic_smart = '{$id}'");
				
				$json = array('message' => 'Assignment PIC Site data has been deleted successfully.');
				
				echo json_encode($json);
				exit;
			}
		}
		
		$data['content_id'] = 'grid_pic_site_assignment';
		// die($this->input->get('cmd'));
		$this->load->view('template/smashing', $data);
	}
	
	public function add()
	{
		// echo "<pre>";
		// print_r($post['picId']);
		// echo "</pre>";
	
		$this->_check_valid_loggedin();
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->post('cmd') == 'add_assignment_pic') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['picId'])) {
					$error[] = 'PIC can not empty.';
				}
				if (@$this->_is_field_empty($post['sites'])) {
					$error[] = 'Site can not empty.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					$this->db->query("UPDATE t_mtr_site SET pic_smart = NULL WHERE pic_smart = '{$post['picId']}'");
								
					foreach ($post['sites'] as $site) {
						$site_id = (int) $this->core->decode($site);
						
						$this->core->set['pic_smart'] = $post['picId'];
						
						$this->core->save('t_mtr_site', array('site_id' => $site_id));
					}
					
					$json = array('error' => FALSE, 'message' => 'Assignment PIC has been added successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		}
		
		$this->_assignment_form();
	}
	
	public function edit($id)
	{
		//die($id);
		// echo "<pre>";
		// print_r($this->core->get_post());
		// echo "</pre>";
		
		$this->_check_valid_loggedin();
		
		$id = $this->core->decode($id);
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->post('cmd') == 'edit_assignment_pic') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['picId'])) {
					$error[] = 'PIC can not empty.';
				}
				if (@$this->_is_field_empty($post['sites'])) {
					$error[] = 'Site can not empty.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					$this->db->query("UPDATE t_mtr_site SET pic_smart = NULL WHERE pic_smart = '{$id}'");
					
					foreach ($post['sites'] as $site) {
						$site_id = (int) $this->core->decode($site);
						
						$this->core->set['pic_smart'] = $post['picId'];
						
						$this->core->save('t_mtr_site', array('site_id' => $site_id));
					}
					
					$json = array('error' => FALSE, 'message' => 'Assignment PIC has been edited successfully.');
				} else {
					$json = array('error' => TRUE, 'message' => $error);
				}
				
				echo json_encode($json);
				exit;
			}
		}
		
		$this->_assignment_form($id);
	}
	
	private function _assignment_form($id = 0)
	{
		$data['content_id'] = 'assignment_pic_form';
		
		if ($this->uri->segment(3) == 'edit') {
			$data['assignment']['user_id'] = $id;
			
			if ($res = $this->db->query("SELECT usr.user_name FROM ".$this->db->dbprefix('t_mtr_user')." usr WHERE usr.user_id = '{$id}'")->row_array()) {
				$data['assignment']['user_name'] = $res['user_name'];
			}
/*
			if ($res = $this->db->query("SELECT site_id, site_code, site_code_old, site_name, tp_name FROM ".$this->db->dbprefix('t_mtr_site')." WHERE pic_smart = '{$id}' ORDER BY updated_on DESC")->result_array()) {
				$data['assignment']['sites'] = $res;
			}*/

			if ($res = $this->db->query("
                            SELECT
                                    t_mtr_site.site_id,
                                    t_mtr_site.site_code,
                                    t_mtr_site.site_code_old,
                                    t_mtr_site.site_name,
                                    t_mtr_site.tp_name,
                                    t_mtr_site.vendor_id,
                                    t_mtr_region.region_name,
                                    t_mtr_cluster_ops.cluster_name
                            FROM
                                    t_mtr_site 
                            JOIN t_mtr_region ON t_mtr_region.region_id = t_mtr_site.region_id
                            LEFT JOIN t_mtr_cluster_ops ON t_mtr_cluster_ops.cluster_ops_id = t_mtr_site.cluster_ops_id 
                            where t_mtr_site.pic_smart = '{$id}'")->result_array()) {
				$data['assignment']['sites'] = $res;
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

	public function get_sites() {
		// $this->load->helper('encode');
		$user_id = $this->input->post('user_id');
		//$q = "SELECT site_id, site_code from t_mtr_site where pic_smart = '$user_id'";
		//$q = "SELECT site_id, site_code, site_code_old, site_name, tp_name from t_mtr_site where pic_smart = '$user_id'";
		
		$q = "
		SELECT
	t_mtr_site.site_id,
	t_mtr_site.site_code,
	t_mtr_site.site_code_old,
	t_mtr_site.site_name,
	t_mtr_site.tp_name,
	t_mtr_site.vendor_id,
	t_mtr_region.region_name,
	t_mtr_cluster_ops.cluster_name
FROM

	t_mtr_site 
JOIN t_mtr_region ON t_mtr_region.region_id = t_mtr_site.region_id
LEFT JOIN t_mtr_cluster_ops ON t_mtr_cluster_ops.cluster_ops_id = t_mtr_site.cluster_ops_id 
where t_mtr_site.pic_smart ='$user_id'";
		
		$r = $this->db->query($q)->result();
		foreach ($r as $k) {
			$k->site_id = $this->core->encode($k->site_id);
		}
		echo json_encode($r);
	}

	public function deletePicSiteMapping() {
		echo $site_id = $this->input->post('site_id');
		echo $user_id = $this->input->post('user_id');
		
		$d = array(
			'pic_smart' => '',
		);
		
		$this->mglobal->setData('t_mtr_site', $d, array('site_id'=>$site_id));
	}

	public function deleteSiteAssignmentPic(){
		$user_id = $this->input->post('user_id');
		$d = array(
			'pic_smart' => '',
		);
		
		$this->mglobal->setData('t_mtr_site', $d, array('pic_smart'=>$user_id));
	}
}