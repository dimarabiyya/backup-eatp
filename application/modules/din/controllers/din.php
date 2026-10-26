<?php

class din extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('din_model');
		$this->load->helper('file');
		$this->load->library('form_validation');
		
	}
	public function index(){
		$this->_check_access();
		if ($this->core->is_ajax())
		{
			if ($this->input->get('cmd') == 'getDinList')
			{
				$rows = $this->din_model->dinList();
				echo json_encode($rows);
				exit;
			}
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'DELETE')
		{
			$id = (int) $this->core->decode($this->input->get('dinId'));
			
			$this->din_model->delete($id);
			
			$json = array('status' => 'success', 'message' => 'Data deleted successfully.');
			echo json_encode($json);
			exit;
		}
		/*Load PO*/
		
		$po_no = array();
		$rs_po = $this->mglobal->getAllPo();
		foreach($rs_po as $item ){
			$po_no[$item->po_id] = $item->po_no ;
		}
		/*Get All Regions*/
		$rows_region = $this->mglobal->getAllRegions();
		
		$regions = array();
		foreach ($rows_region as $v)
		{
			$regions[$v->region_id] = $v->region_name;
		}
		$din_type = array("Software"=>"Software","Material"=>"Material");
		$data['din_type'] = json_encode($din_type);
		$data['po_no'] = json_encode($po_no);
		$data['regions'] = json_encode($regions);
		$data['content_id'] = 'index';
		
		$this->load->view('template/smashing', $data);
	}

	public function add(){
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{

			$postdata = $this->input->post();			
			$param_keys = array('din_no', 'po_id','file','din_type');
			
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
						'field' => 'din_no',
						'label' => 'DIN NO.',
						'rules' => 'trim|required'
					),
					array(
						'field' => 'po_id',
						'label' => 'PO Id',
						'rules' => 'required'
					),
					array(
						'field' => 'din_type',
						'label' => 'Type DIN',
						'rules' => 'required'
					)
				);
		
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<li>', '</li>');
		
				if ($this->form_validation->run())
				{
					/*Store the File*/
					if(!isset($_FILES["file"])){
						$json = array('status' => 'error', 'message' => 'Select ESS File');
				
						echo json_encode($json);
						exit;
					}
					/*Before Save To Database*/
					/*Store the File*/
					$path_parts = pathinfo($_FILES["file"]["name"]);
					//printf($path_parts); die();
					$extension = $path_parts['extension'];
					$file_name ="DIN-".$data["din_no"].".".$extension;
					uploadDocument('file','document','din',$file_name);
					/*End Block Saving File to Folder Upload/din*/
					$post = new stdClass;
					
					$post->din_no = $data['din_no'];
					//$post->po_id = $data['po_id'];
					$post->din_file = $file_name;
					$post->created_on = date('Y-m-d H:i:s');
					$post->created_by = $this->session->userdata('user_id');

					/*Call Model to Insert Data*/
					$last_id = $this->din_model->insert($post);
					/*Insert into din_po*/
					$this->din_model->insert_din_po($last_id,$data['po_id']);
					/*Update Progress Status PO*/
					$this->din_model->updateProgressStatus($data['po_id']);
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
			}else{
				echo "here";
			}
		}
	}
	public function edit($id)
	{
		$id = $this->core->decode($id);
		
		if ($this->input->server('REQUEST_METHOD') == 'GET'){
				
			$row = $this->din_model->getDin($id);
		
			echo json_encode($row);
			exit;
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$postdata = $this->input->post();

			$param_keys = array('din_no', 'po_id','file','din_type');
			
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
						'field' => 'din_no',
						'label' => 'dinNO',
						'rules' => 'trim|required'
					),
					array(
						'field' => 'po_id',
						'label' => 'poID',
						'rules' => 'required'
					),
					array(
						'field' => 'din_type',
						'label' => 'Type DIN',
						'rules' => 'required'
					)
				);
		
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<li>', '</li>');
		
				if ($this->form_validation->run())
				{
					/*Before Save To Database*/
					
					
					/*End Block Saving File to Folder Upload/din*/
					$post = new stdClass;
					
					$post->din_no = $data['din_no'];
					$post->din_type = $data['din_type'];
					//$post->po_id = $data['po_id'];
					if (!empty($_FILES['file']['name'])) {
						/*Store the File*/
						$path_parts = pathinfo($_FILES["file"]["name"]);
						$extension = $path_parts['extension'];
						$file_name ="DIN-".$data["din_no"].".".$extension;
						uploadDocument('file','document','din',$file_name);
						$post->din_file = $file_name;	
					}					
					$post->updated_on = date('Y-m-d H:i:s');
					$post->updated_by = $this->session->userdata('user_id');

					/*Call Model to Update Data*/
					$this->din_model->update($post,$id);
					/*Inser to many-to-many table din_po*/
					/*Insert into din_po*/
					$this->din_model->update_din_po($id,$data['po_id']);
					$this->din_model->updateProgressStatus($data['po_id']);
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
	
	public function exportDin()
	{
		$this->din_model->exportDin();
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
	public function view($view)
	{
		$this->load->view($view);
	}


}
