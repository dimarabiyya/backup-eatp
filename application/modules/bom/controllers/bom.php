<?php

class bom extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('bom_model');
		$this->load->model('reference_model');
		$this->load->helper('file');
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
	}
	
	public function index()
	{
		$this->_check_access();
		
		if ($this->core->is_ajax())
		{
			if ($this->input->get('cmd') == 'getBomList')
			{
				$rows = $this->bom_model->bomList();

				echo json_encode($rows);
				exit;
			}
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'DELETE')
		{
			$id = (int) $this->core->decode($this->input->get('bomId'));
			
			$this->bom_model->delete($id);
			
			$json = array('status' => 'success', 'message' => 'Data deleted successfully.');
			echo json_encode($json);
			exit;
		}

		//Regions
		$rows = $this->mglobal->getAllRegions();
		
		$regions = array();
		foreach ($rows as $v)
		{
			$regions[$v->region_id] = $v->region_name;
		}
		
		$data['regions'] = json_encode($regions);

		//load PO
		$po_no = array();
		$rs_po = $this->mglobal->getAllPo();
		foreach($rs_po as $item ){
			$po_no[$item->po_id] = $item->po_no;
		}
		/*Status Boq*/
		$status_boq = array();
		$status_boq[1] = "Active";
		$status_boq[0] = "Non Active";
		//echo json_encode($rs_po);die;
		$data['po_no'] = json_encode($po_no);
		$data['status_boq'] = json_encode($status_boq);
		$data['content_id'] = 'index';
		
		$this->load->view('template/smashing', $data);
	}
	
	public function add()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			//$_POST = json_decode(file_get_contents('php://input'), true);
			$postdata = $this->input->post();
			
			$param_keys = array('po_id', 'config', 'code', 'material_description', 'qty_requirement', 'qty_po', 'qty_actual', 'status_boq', 'remarks', 'uom', 'link_id_int');
			
			if (is_array($postdata))
			{
				$data = array();
				foreach ($param_keys as $key)
				{
					if (array_key_exists($key, $postdata))
					{
						$data[$key] = $postdata[$key];
						
						if (trim($data[$key]) == '')
						{
							$data[$key] = NULL;
						}
					}
					else
					{
						$data[$key] = NULL;
					}
				}
				
				$validation_rules = array(
					array(
						'field' => 'po_id',
						'label' => 'poID',
						'rules' => 'required',
					),
					array(
						'field' => 'config',
						'label' => 'Config Item',
						'rules' => 'required',
					),
					array(
						'field' => 'code',
						'label' => 'Code',
						'rules' => 'required',
					),
					array(
						'field' => 'material_description',
						'label' => 'Material Description',
						'rules' => 'required',
					),
					array(
						'field' => 'uom',
						'label' => 'Unit of Measurement',
						'rules' => 'required',
					)
					,
					array(
						'field' => 'link_id_int',
						'label' => 'Site Link',
						'rules' => 'required',
					),
					array(
						'field' => 'qty_requirement',
						'label' => 'Minimum Value is 0',
						'rules' => 'required|greater_than[-1]|numeric'
					),
					array(
						'field' => 'qty_po',
						'label' => 'Minimum Value is 0',
						'rules' => 'required|greater_than[-1]|numeric'
					),
					array(
						'field' => 'qty_actual',
						'label' => 'Minimum Value is 0',
						'rules' => 'required|greater_than[-1]|numeric'
					),
					array(
						'field' => 'status_boq',
						'label' => 'You have to chooose Status Boq',
						'rules' => 'required'
					)
				);
		
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<li>', '</li>');
		
				if ($this->form_validation->run())
				{
					$post = new stdClass;
					
					$post->po_id 				= $data['po_id'];
					$post->config 				= $data['config'];
					$post->code 				= $data['code'];
					$post->material_description = $data['material_description'];
					$post->qty_requirement 		= $data['qty_requirement'];
					$post->qty_po 				= $data['qty_po'];
					$post->qty_actual 			= $data['qty_actual'];
					$post->status_boq 			= $data['status_boq'];
					$post->remarks 				= $data['remarks'];
					$post->uom 					= $data['uom'];
					$post->link_id_int 			= $data['link_id_int'];
					$post->created_on 			= date('Y-m-d H:i:s');
					$post->created_by 			= $this->session->userdata('user_id');
					
					$this->bom_model->insert($post);
					
					$json = array('status' => 'success', 'message' => 'Data added successfully.');
				
					echo json_encode($json);
					exit;
				}
				else
				{
					$json = array('status' => 'error', 'message' => validation_errors());
					
					echo json_encode($json);
					exit;
				}
			}
		}
	}
	
	public function edit($id)
	{
		$id = $this->core->decode($id);
		
		if ($this->input->server('REQUEST_METHOD') == 'GET')
		{
			$row = $this->bom_model->getBom($id);
		
			echo json_encode($row);
			exit;
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			//$_POST = json_decode(file_get_contents('php://input'), true);
			$postdata = $this->input->post();
			
			$param_keys = array('po_id', 'config', 'code', 'material_description', 'qty_requirement', 'qty_po', 'qty_actual', 'status_boq', 'remarks', 'uom', 'link_id_int');
			
			if (is_array($postdata))
			{
				$data = array();
				foreach ($param_keys as $key)
				{
					if (array_key_exists($key, $postdata))
					{
						$data[$key] = $postdata[$key];
						
						if (trim($data[$key]) == '')
						{
							$data[$key] = NULL;
						}
					}
					else
					{
						$data[$key] = NULL;
					}
				}
				
				$validation_rules = array(
					array(
						'field' => 'po_id',
						'label' => 'poID',
						'rules' => 'required',
					),
					array(
						'field' => 'config',
						'label' => 'Config Item',
						'rules' => 'required',
					),
					array(
						'field' => 'code',
						'label' => 'Code',
						'rules' => 'required',
					),
					array(
						'field' => 'remarks',
						'label' => 'Remark',
						'rules' => 'required',
					),
					array(
						'field' => 'uom',
						'label' => 'Unit of Measurement',
						'rules' => 'required',
					)
					,
					array(
						'field' => 'link_id_int',
						'label' => 'Site Link',
						'rules' => 'required',
					),
					array(
						'field' => 'qty_requirement',
						'label' => 'Minimum Value Qty_Requirement is 0',
						'rules' => 'required|greater_than[-1]|numeric'
					),
					array(
						'field' => 'qty_po',
						'label' => 'Minimum Value Qty_PO is 0',
						'rules' => 'required|greater_than[-1]|numeric'
					),
					array(
						'field' => 'qty_actual',
						'label' => 'Minimum Value Qty_Actual is 0',
						'rules' => 'required|greater_than[-1]|numeric'
					),
					array(
						'field' => 'status_boq',
						'label' => 'You have to chooose Status Boq',
						'rules' => 'required'
					)
				);	
		
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<li>', '</li>');
		
				if ($this->form_validation->run())
				{
					$post = new stdClass;
					
					$post->po_id 				= $data['po_id'];
					$post->config 				= $data['config'];
					$post->code 				= $data['code'];
					$post->material_description = $data['material_description'];
					$post->qty_requirement 		= $data['qty_requirement'];
					$post->qty_po 				= $data['qty_po'];
					$post->qty_actual 			= $data['qty_actual'];
					$post->status_boq 			= $data['status_boq'];
					$post->remarks 				= $data['remarks'];
					$post->uom 					= $data['uom'];
					$post->link_id_int 			= $data['link_id_int'];
					$post->created_on 			= date('Y-m-d H:i:s');
					$post->created_by 			= $this->session->userdata('user_id');
					
					$this->bom_model->update($post, $id);
					
					$json = array('status' => 'success', 'message' => 'Data updated successfully.');
					echo json_encode($json);
					exit;
				}
				else
				{
					$json = array('status' => 'error', 'message' => validation_errors());
					
					echo json_encode($json);
					exit;
				}
			}
		}
	}
	
	public function exportBom()
	{
		$this->bom_model->exportBom();
	}
	
	public function view($view)
	{
		$this->load->view($view);
	}
	
	private function _check_access()
	{
		if ( ! $this->session->userdata('is_login'))
		{
			if ($this->core->is_ajax())
			{
				echo json_encode(array(
					'loggedin' => FALSE,
					'message' => 'You are not logged in. You will be redirected to login page.',
					'redirect_url' => site_url('login')
				));
				exit;
			}
			else
			{
				redirect('login');
			}
		}
	}

	/*Function Return Link_ID Based on PO No*/
	public function linkidBypo($po_id){
		$res = $this->reference_model->getLinkByPO($po_id);
		echo json_encode($res);
		exit;
	}
}
