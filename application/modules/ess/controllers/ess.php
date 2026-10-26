<?php

class ess extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ess_model');
		$this->load->helper('file');
	}
	
	public function index()
	{
		$this->_check_access();
		
		if ($this->core->is_ajax())
		{
			if ($this->input->get('cmd') == 'getEssList')
			{
				$rows = $this->ess_model->essList();

				echo json_encode($rows);
				exit;
			}
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'DELETE')
		{
			$id = (int) $this->core->decode($this->input->get('essId'));
			
			$this->ess_model->delete($id);
			
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
		//echo json_encode($rs_po);die;
		$data['po_no'] = json_encode($po_no);
		$data['content_id'] = 'index';
		
		$this->load->view('template/smashing', $data);
	}
	
	public function add()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			//$_POST = json_decode(file_get_contents('php://input'), true);
			$postdata = $this->input->post();
			
			$param_keys = array('po_id', 'ess_no', 'file');
			
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
						'field' => 'ess_no',
						'label' => 'essNo',
						'rules' => 'trim|required'
					),
					array(
						'field' => 'po_id',
						'label' => 'poID',
						'rules' => 'required'
					)
				);
		
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<li>', '</li>');
		
				if ($this->form_validation->run())
				{
					/*Before Save To Database*/
					/*Store the File*/
					if(!isset($_FILES["file"])){
						$json = array('status' => 'error', 'message' => 'Select ESS File');
				
						echo json_encode($json);
						exit;
					}
					$path_parts = pathinfo($_FILES["file"]["name"]);
					$extension = $path_parts['extension'];
					$file_name ="ESS-".$data["ess_no"].".".$extension;
					uploadDocument('file','document','ess',$file_name);
					/*End Block Saving File to Folder Upload/din*/

					$post = new stdClass;
					
					$post->ess_no = $data['ess_no'];
					$post->po_id = $data['po_id'];
					$post->ess_file = $file_name;
					$post->created_on = date('Y-m-d H:i:s');
					$post->created_by = $this->session->userdata('user_id');
					
					$this->ess_model->insert($post);
					
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
			$row = $this->ess_model->getEss($id);
		
			echo json_encode($row);
			exit;
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			//$_POST = json_decode(file_get_contents('php://input'), true);
			$postdata = $this->input->post();
			
			$param_keys = array('ess_no', 'po_id', 'file');
			
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
						'field' => 'ess_no',
						'label' => 'PO No',
						'rules' => 'trim|required'
					),
					array(
						'field' => 'po_id',
						'label' => 'PO Date',
						'rules' => 'required'
					)
				);
		
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<li>', '</li>');
		
				if ($this->form_validation->run())
				{
					/*Before Save To Database*/
					$post = new stdClass;
					
					$post->ess_no = $data['ess_no'];
					$post->po_id = $data['po_id'];
					if (!empty($_FILES['file']['name'])) {
						/*Store the File*/
						$path_parts = pathinfo($_FILES["file"]["name"]);
						$extension = $path_parts['extension'];
						$file_name ="ESS-".$data["ess_no"].".".$extension;
						$flag =uploadDocument('file','document','ess',$file_name);
						
						/*End Block Saving File to Folder Upload/ess*/
						$post->ess_file = $file_name;	
					}	
					$post->updated_on = date('Y-m-d H:i:s');
					$post->updated_by = $this->session->userdata('user_id');
					
					$this->ess_model->update($post, $id);
					
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
	
	public function exportEss()
	{
		$this->ess_model->exportEss();
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
}
