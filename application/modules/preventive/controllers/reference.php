<?php

class Reference extends MX_Controller {

	public function __construct()
	{
		$this->lang->load('preventive/reference', 'english');
		
		$this->load->helper('language');
	}
	
	public function index($key = '', $action = '', $param = '')
	{
		if (empty($key)) {
			show_404();
		}
		
		$data['key'] = $key;
		$data['content_id'] = 'reference_list';
		$data['add_url'] = site_url('/preventive/reference/index/'.$key.'/add');
		$data['edit_url'] = site_url('/preventive/reference/index/'.$key.'/edit/'.$param);
		$data['list_url'] = site_url('/preventive/reference/index/'.$key);
		
		$results = $this->core->view_all('t_mtr_lookup', array('lookup_key' => $key, 'active' => 1), 'order');
		$data['current_order'] = array();
		foreach ($results as $res) {
			$data['current_order'][] = $res['lookup_id'];
		}
		
		$lookup = $this->core->get_lookup($key, FALSE);
		$order = 1;
		
		//get lookup value
		$this->db->order_by('lookup_value', 'desc');
		$this->db->limit(1);
		$qval = $this->db->get_where('t_mtr_lookup', array("lookup_key"=>"$key","active"=>1));
		if ($qval->num_rows()==0){
			$l_value = 1;
		}else{
			$l_value = $qval->row()->lookup_value + 1;
		}
		
		$data['order_options'] = array(0 => '');
		if (count($lookup) > 0) {			
			foreach ($lookup as $lo) {
				$data['order_options'][$order] = $order;
				$order++;
			}
		} else {
			$data['order_options'][1] = 1;
		}
		
		if ( ! empty($action)) {
			
			if ($action == 'add') {
				
				$data['order_options'][$order] = $order;
				$data['reference']['lookup_value'] = count($lookup) + 1;
				$data['reference']['order'] = $order;
				
				if ($_POST && $this->core->is_ajax()) {
					
					$json = array();
					$post = $this->core->get_post();
					$error = array();
					
					if ($post['name'] == '') {
						$error[] = 'Name can not empty.';
					}
					if ( $post['name'] != '' && $this->core->view_row('t_mtr_lookup', array('UPPER(lookup_desc)' => strtoupper($post['name']), 'lookup_key' => $key, 'active' => 1)) ) {
						$error[] = 'Name is already in used.';
					}
					if ($post['value'] == 0) {
						$error[] = 'Value can not empty.';
					}
					// if ( $post['value'] != 0 && $this->core->view_row('t_mtr_lookup', array('lookup_value' => $post['value'], 'lookup_key' => $key, 'active' => 1)) ) {
						// $error[] = 'Value is already in used.';
					// }
					if ($post['order'] == 0) {
						$error[] = 'Order can not empty.';
					}
					
					if (count($error) == 0) {
						$this->core->db_debug = TRUE;
						
						$created_by = $updated_by = $this->session->userdata('user_id');
						$created_on = $updated_on = date('Y-m-d H:i:s.u');
						
						$this->core->set['lookup_key'] = $key;
						$this->core->set['lookup_desc'] = $post['name'];
						$this->core->set['lookup_value'] = $post['value'];
						//$this->core->set['order'] = $post['order'];
						$this->core->set['created_on'] = $created_on;
						$this->core->set['created_by'] = $created_by;
						$this->core->set['updated_on'] = $updated_on;
						$this->core->set['updated_by'] = $updated_by;
						
						
						$this->core->save('t_mtr_lookup');
						$id = $this->db->insert_id();
						
						$i = 1;
						foreach ($post['myorder'] as $ord) {
							if ($ord == 'undefined') {
								$ord = $id;
							}
							$this->core->set['order'] = $i;
							$this->core->save('t_mtr_lookup', array('lookup_id' => $ord, 'lookup_key' => $key));
							$i++;
						}
						
						$json = array('error' => FALSE, 'message' => 'Reference has been added successfully.');
					} else {
						$json = array('error' => TRUE, 'message' => $error);
					}
					
					echo json_encode($json);
					exit;
				}
			
			} elseif ($action == 'edit') {			
			
				if (empty($param)) {
					show_404();
				}
				
				$id = (int) $this->core->decode($param);
				$data['reference'] = $this->core->view_row('t_mtr_lookup', array('lookup_id' => $id));
				
				if ($_POST && $this->core->is_ajax()) {
					
					$json = array();
					$post = $this->core->get_post();
					$error = array();
					
					if ($post['name'] == '') {
						$error[] = 'Name can not empty.';
					}
					if ( $post['name'] != '' && $this->core->view_row('t_mtr_lookup', array('UPPER(lookup_desc)' => strtoupper($post['name']), 'lookup_key' => $key, 'active' => 1, 'lookup_id !=' => $id)) ) {
						$error[] = 'Name is already in used.';
					}
					if ($post['value'] == 0) {
						$error[] = 'Value can not empty.';
					}
					if ( $post['value'] != 0 && $this->core->view_row('t_mtr_lookup', array('lookup_value' => $post['value'], 'lookup_key' => $key, 'active' => 1, 'lookup_id !=' => $id)) ) {
						$error[] = 'Value is already in used.';
					}
					if ($post['order'] == 0) {
						$error[] = 'Order can not empty.';
					}
					
					if (count($error) == 0) {
						$this->core->db_debug = TRUE;

						$created_by = $updated_by = $this->session->userdata('user_id');
						$created_on = $updated_on = date('Y-m-d H:i:s.u');
						
						$this->core->set['lookup_desc'] = $post['name'];
						$this->core->set['lookup_value'] = $post['value'];
						//$this->core->set['order'] = $post['order'];
						$this->core->set['updated_on'] = $updated_on;
						$this->core->set['updated_by'] = $updated_by;
						
						$this->core->save('t_mtr_lookup', array('lookup_id' => $id, 'lookup_key' => $key));
						
						$i = 1;
						foreach ($post['myorder'] as $ord) {
							$this->core->set['order'] = $i;
							$this->core->save('t_mtr_lookup', array('lookup_id' => $ord, 'lookup_key' => $key));
							$i++;
						}
						
						$json = array('error' => FALSE, 'message' => 'Reference has been updated successfully.');
					} else {
						$json = array('error' => TRUE, 'message' => $error);
					}
					
					echo json_encode($json);
					exit;
				}
			
			} else {
				show_404();
			}
			
			$data['action'] = $action;
			$data['content_id'] = 'reference_form';
		}
		
		// List grid
		if ($this->core->is_ajax()) {
		
			if ($this->input->get('cmd') == 'GET_DATAGRID_REFERENCE') {
				$json = array();
			
				$page = $this->input->get('page') ? $this->input->get('page') : 1;
				$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
				$offset = ($page - 1) * $rows;
				$search = $this->input->get('query');
				$sort = $this->input->get('sort') ? $this->input->get('sort') : 'order';
				$order = $this->input->get('order') ? $this->input->get('order') : 'ASC';
				
				$where = "WHERE lookup_key = '{$key}' AND active = 1";
				$where .= " AND (UPPER(lookup_desc) LIKE '%".strtoupper($search)."%'";
				//$where .= " OR UPPER(lookup_value) LIKE '%".strtoupper($search)."%'";
				$where .= " OR CAST(\"order\" AS VARCHAR) LIKE '%".strtoupper($search)."%')";
				
				$sql = "SELECT lookup_id, lookup_value, lookup_desc, \"order\" FROM t_mtr_lookup {$where} ORDER BY  \"{$sort}\" {$order}";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				
				$results = $query->result_array();
				$json['rows'] = array();
				foreach ($results as $r) {
					$r['lookup_id'] = $this->core->encode($r['lookup_id']);
					$json['rows'][] = $r;
				}
				
				$json['sql'] = str_replace(array("\n", "\t"), ' ', $sql);
				
				echo json_encode($json);
				exit;
			}
			
			if ($this->input->get('cmd') == 'DELETE_REFERENCE') {
			
				$json = array();
				
				$id = (int) $this->core->decode($this->input->get('id'));
				
				$this->db->query("UPDATE t_mtr_lookup SET \"active\" = 0 WHERE lookup_id = {$id}");
				
				$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => $key, 'active' => 1), 'order');
				$ord = 1;
				foreach ($lookup as $lo) {
					$this->db->query("UPDATE t_mtr_lookup SET \"order\" = {$ord} WHERE lookup_key = '{$key}' AND lookup_id = ".$lo['lookup_id']);
					$ord++;
				}
				
				$json = array('error' => FALSE, 'message' => 'Reference data deleted succeeded.');
				
				echo json_encode($json);
				exit;
			}
		}
		
		$this->load->view('template/smashing', $data);
	}
}