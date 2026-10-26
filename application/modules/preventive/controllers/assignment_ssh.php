<?php

class Assignment_ssh extends MX_Controller {

	public function __construct()
	{
		$this->load->library('parser');
	}
	
	public function index()
	{
		$this->_check_valid_loggedin();
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->get('cmd') == 'get_lookup_ssh') {
			
				$data = array();
				
				if ($this->input->get('act') == 'get_data') {
			
					$json = array();
				
					$page = $this->input->get('page') ? $this->input->get('page') : 1;
					$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
					$offset = ($page - 1) * $rows;
					$search = $this->input->get('query');
					$keyword = $this->db->escape_str($search['keyword']);
					$region  = $search['region'] != '' ? (int) $search['region'] : '';
					$roles   = $search['roles'];
					$sort = $this->input->get('sort') ? $this->input->get('sort') : 'usr.user_name';
					$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';

					$where = "WHERE usr.status = 1 AND grp.area_restriction = 'cluster_assignment'";
					$kw  = ''; $rg  = ''; $rl = ''; 
					if($keyword != '') {
						$kw = "(UPPER(usr.user_name) LIKE '%".strtoupper($keyword)."%' or lower(usr.user_id) like '%".strtolower($keyword)."%')";
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
					
					$sql = "SELECT mrole.user_id, usr.user_name, f_grouped_user_role(mrole.user_id) as group_caption, f_grouped_user_region(mrole.user_id) as user_region FROM t_mtr_user_multi_role mrole JOIN t_mtr_user usr ON usr.user_id = mrole.user_id JOIN t_mtr_user_group grp ON grp.user_group_id = mrole.user_group_id {$where} ORDER BY {$sort} {$order}";
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
					
					$total_rows = (int) $query->row()->total;
					
					$sql .= " LIMIT {$rows} OFFSET {$offset}";
					
					$query = $this->db->query($sql);
					
					$json['total'] = $total_rows;
					$json['rows'] = $query->result_array();
					$json['sql'] = $sql;
					
					echo json_encode($json);
					exit;
				}
				
				echo $this->parser->parse('lookup_ssh', $data);
				exit;
			}
		
			if ($this->input->get('cmd') == 'get_lookup_cluster_ops') {
			
				$data = array();
				$user_id = $this->input->get_post('user_id');
				if ($this->input->get('act') == 'get_data') {
				
					$json = array();
					
					$site_id = $this->input->get('site_id');
					$page = $this->input->get('page') ? $this->input->get('page') : 1;
					$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
					$offset = ($page - 1) * $rows;
					$search = $this->input->get('query');
					$keyword = $this->db->escape_str($search['keyword']);
					$region  = $search['region'] != '' ? (int) $search['region'] : '';
					$sort = $this->input->get('sort') ? $this->input->get('sort') : 'cluster_name';
					$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
					$regi = $this->input->get('region');
					$status = $this->input->get('status');

					$where = "WHERE tt.user_id = '{$user_id}' and co.status = 1";
					
					if ($keyword != '') {
						$where .= " AND co.cluster_name ILIKE '%".$keyword."%'";
					}
					
					if ($region != '') {
						$where .= " AND co.region_id = {$region}";

					}
					
					$sql = "
						SELECT DISTINCT rr.region_name, co.cluster_ops_id, co.cluster_name 
						FROM t_mtr_user_territory tt
						JOIN t_mtr_region rr on rr.region_id = tt.region_id
						JOIN t_mtr_cluster_ops co on rr.region_id = co.region_id
						{$where} ORDER BY {$sort} {$order}
					";
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
					$total_rows = (int) $query->row()->total;
					$sql .= " LIMIT {$rows} OFFSET {$offset}";
					
					$query = $this->db->query($sql);
					
					$json['total'] = $total_rows;
					
					$rows = array();
					foreach ($query->result_array() as $r) {
						$r['cluster_ops_id'] = $this->core->encode($r['cluster_ops_id']);
						$rows[] = $r;
					}
					
					$json['rows'] = $rows;
					//$json['sql'] = str_replace(array("\n", "\t"), ' ', $sql);
					
					echo json_encode($json);
					exit;
				}
				
				$data['user_id'] = $user_id;
				$this->load->model('mpreventive');
				$data['regions'] = $this->mpreventive->getUserRegions($user_id);
				
				echo $this->parser->parse('lookup_cluster_ops', $data);
				exit;
			}
		
			if ($this->input->get('cmd') == 'get_assignment_ssh_list') {
		
				$json = array();
				
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$search = $this->input->get('query');
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'usr.user_name';
				$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';
				
				$where = "WHERE ssh.active = 1 AND (";
				$where .= " UPPER(usr.user_name) LIKE '%".strtoupper($search)."%'";
				$where .= ")";
				//$where .= " AND usr.user_id IN (SELECT pic_smart FROM t_mtr_site)";
				
				$sql = "
					SELECT ssh.user_id, usr.user_name
					FROM t_mtr_cluster_mapping ssh
					LEFT JOIN t_mtr_user usr ON usr.user_id = ssh.user_id
					{$where}
					GROUP BY ssh.user_id, usr.user_name
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
						->query("
							SELECT ops.cluster_name
							FROM t_mtr_cluster_mapping ssh
							LEFT JOIN t_mtr_cluster_ops ops ON ops.cluster_ops_id = ssh.cluster_ops_id
							WHERE ssh.user_id = '".$r['user_id']."'
						")
						->result_array();
					
					$r['clusters'] = array();
					foreach ($sqls as $q) {
						$r['clusters'][] = $q['cluster_name'];
					}
					
					$r['user_id'] = $this->core->encode($r['user_id']);
					$json['rows'][] = $r;
				}
				
				$json['sql'] = $sql;
				
				echo json_encode($json);
				exit;
			}
		
			if ($this->input->get('cmd') == 'delete_assignment') {
			
				$json = array();
				$id = $this->core->decode($this->input->get('id'));
				
				$this->db->query("UPDATE t_mtr_cluster_mapping SET active = 0 WHERE user_id = '{$id}'");
				
				$json = array('message' => 'Assignment SSH Site data has been deleted successfully.');
				
				echo json_encode($json);
				exit;
			}
		}
		
		$data['content_id'] = 'grid_cluster_assignment';
		
		$this->load->view('template/smashing', $data);
	}
	
	public function get_cluster()
	{
		$this->load->model('mpreventive');
		$region_id = $this->input->post('region_id');
		$c = $this->mpreventive->getCluster($region_id);
		echo json_encode($c);
	}

	public function add()
	{
/*
		echo "<pre>";
		print_r($post['sshId']);
		echo "</pre>";*/
	
		$this->_check_valid_loggedin();
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->post('cmd') == 'add_assignment_ssh') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['sshId'])) {
					$error[] = 'SSH can not empty.';
				}
				if (@$this->_is_field_empty($post['clusterOps'])) {
					$error[] = 'Cluster Ops. can not empty.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					$this->db->where('user_id', $post['sshId']);
					$this->db->delete('t_mtr_cluster_mapping');
					
					foreach ($post['clusterOps'] as $ops) {
						$ops_id = (int) $this->core->decode($ops);	
						$this->core->set['user_id'] = $post['sshId'];
						$this->core->set['cluster_ops_id'] = $ops_id;
						$this->core->set['active'] = 1;
						
				/*
						if ($this->core->view_row('t_mtr_cluster_mapping', array('user_id' => $post['sshId'], 'cluster_ops_id' => $ops_id))) {
											$this->core->set['updated_by'] = $updated_by;
											$this->core->set['updated_on'] = $updated_on;
										
											$this->core->save('t_mtr_cluster_mapping', array('user_id' => $post['sshId'], 'cluster_ops_id' => $ops_id));
										} else {*/
				
							$this->core->set['created_by'] = $created_by;
							$this->core->set['created_on'] = $created_on;
							/*
							$this->core->set['updated_by'] = $updated_by;
							$this->core->set['updated_on'] = $updated_on;*/
							
							
							$this->core->save('t_mtr_cluster_mapping');
					/*
						}
					*/
										}
					
					$json = array('error' => FALSE, 'message' => 'Assignment SSH has been added successfully.');
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
		$this->_check_valid_loggedin();
		
		$id = $this->core->decode($id);
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->post('cmd') == 'edit_assignment_ssh') {
			
				$json = array();
				$post = $this->core->get_post();
				$error = array();
				
				if ($this->_is_field_empty($post['sshId'])) {
					$error[] = 'SSH can not empty.';
				}
				if (@$this->_is_field_empty($post['clusterOps'])) {
					$error[] = 'Cluster Ops. can not empty.';
				}
				
				if (count($error) == 0) {
					
					$created_by = $updated_by = $this->session->userdata('user_id');
					$created_on = $updated_on = date('Y-m-d H:i:s');
					
					$this->core->db_debug = TRUE;
					
					$this->db->where('user_id', $post['sshId']);
					$this->db->delete('t_mtr_cluster_mapping');
					
					foreach ($post['clusterOps'] as $ops) {
						$ops_id = (int) $this->core->decode($ops);
						
						$this->core->set['user_id'] = $post['sshId'];
						$this->core->set['cluster_ops_id'] = $ops_id;
						$this->core->set['active'] = 1;
						
		/*
						if ($this->core->view_row('t_mtr_cluster_mapping', array('user_id' => $post['sshId'], 'cluster_ops_id' => $ops_id))) {
									$this->core->set['updated_by'] = $updated_by;
									$this->core->set['updated_on'] = $updated_on;
								
									$this->core->save('t_mtr_cluster_mapping', array('user_id' => $post['sshId'], 'cluster_ops_id' => $ops_id));
								} else {*/
		
							/*
							$this->core->set['created_by'] = $created_by;
							$this->core->set['created_on'] = $created_on;*/
							
							$this->core->set['updated_by'] = $updated_by;
							$this->core->set['updated_on'] = $updated_on;
							
							$this->core->save('t_mtr_cluster_mapping');
						/*}*/
					}
					
					$json = array('error' => FALSE, 'message' => 'Assignment SSH has been edited successfully.');
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
		$data['content_id'] = 'assignment_ssh_form';
		
		if ($this->uri->segment(3) == 'edit') {
			$data['assignment']['user_id'] = $id;
			
			if ($res = $this->db->query("SELECT usr.user_name FROM ".$this->db->dbprefix('t_mtr_user')." usr WHERE usr.user_id = '{$id}'")->row_array()) {
				$data['assignment']['user_name'] = $res['user_name'];
			}
			if ($res = $this->db->query("
				SELECT ssh.cluster_ops_id, ops.cluster_name, t_mtr_region.region_name
				FROM t_mtr_cluster_mapping ssh
				LEFT JOIN t_mtr_cluster_ops ops ON ops.cluster_ops_id = ssh.cluster_ops_id
				LEFT JOIN t_mtr_region ON t_mtr_region.region_id = ops.region_id
				WHERE ssh.user_id = '{$id}'
			")->result_array()) {
				$data['assignment']['clusters'] = $res;
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

	public function get_clusters()
	{
		$user_id = $this->input->post('user_id');
		$q = "SELECT cc.cluster_ops_id, cc.cluster_name,t_mtr_region.region_name
		from t_mtr_cluster_mapping mm 
		join t_mtr_cluster_ops cc on cc.cluster_ops_id = mm.cluster_ops_id
		join t_mtr_region on t_mtr_region.region_id = cc.region_id
		where mm.user_id = '$user_id' and cc.status = 1";
		$r = $this->db->query($q)->result();
		foreach ($r as $k) {
			$k->cluster_ops_id = $this->core->encode($k->cluster_ops_id);
		}
		echo json_encode($r);
	}

	public function deleteAssignment()
	{
		$user_id = $this->input->post('user_id');
		$cluster_id = $this->input->post('cluster_id');
		$this->load->model('mpreventive');

		$r = $this->mpreventive->deleteCCAssignment($user_id, $cluster_id);
		echo json_encode($r);
	}

	public function deleteAllAssignment()
	{
		$user_id = $this->input->post('user_id');
		$this->load->model('mpreventive');

		$r = $this->mpreventive->deleteAllCCAssignment($user_id);
		echo json_encode($r);
	}
}
