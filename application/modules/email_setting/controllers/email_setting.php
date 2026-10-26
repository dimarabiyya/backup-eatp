<?php

class email_setting extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('email_setting_model');			
		$this->load->library('form_validation');
	}

	public function index(){
		$this->_check_access();
                $data = $this->email_setting_model->email_settingList();
                //var_dump($data);
		if ($this->core->is_ajax())
		{
			if ($this->input->get('cmd') == 'getApprovalList')
			{
				$rows = $this->email_setting_model->email_settingList();
                                //var_dump($rows);
				echo json_encode($rows);
				exit;
			}
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'DELETE')
		{
			$id = (int) $this->core->decode($this->input->get('email_settingId'));
			
			$this->email_setting_model->delete($id);
			
			$json = array('status' => 'success', 'message' => 'Data deleted successfully.');
			echo json_encode($json);
			exit;
		}
		
		$data['content_id'] = 'index';
		
		$this->load->view('template/smashing', $data);
	}
	public function add(){
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$postdata = $this->input->post();
			
			$param_keys = array('template_name', 'user_id','order_no','remark','is_mandatory');
			
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
						'field' => 'template_name',
						'label' => 'Template Name',
						'rules' => 'trim|required'
					),
					array(
						'field' => 'user_id',
						'label' => 'User ID',
						'rules' => 'required'
					),
					array(
						'field' => 'order_no',
						'label' => 'Order No',
						'rules' => 'required'
					)
				);
		
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<li>', '</li>');
		
				if ($this->form_validation->run())
				{
					/*Create Approval Master*/
					$post = new stdClass;
					
					$post->user_id = $data['user_id'];
					$post->order_no = $data['order_no'];
					$is_mandatory = 0;
					if($data['is_mandatory']){
						$is_mandatory =1;
					}
					$post->is_mandatory = $is_mandatory;
					$remark = "";
					if($data['remark']){
						$remark = $data['remark'] ;
					}
					$post->remark = $remark;
					$post->template_name = $data['template_name'];
					$post->created_on = date('Y-m-d H:i:s');
					$post->created_by = $this->session->userdata('user_id');

					/*Call Model to Insert Data*/
					$this->email_setting_model->insert($post);
					
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
	public function edit($id){
		$id = $this->core->decode($id);
		
		if ($this->input->server('REQUEST_METHOD') == 'GET'){
				
			$row = $this->email_setting_model->getApproval($id);
		
			echo json_encode($row);
			exit;
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$postdata = $this->input->post();
			//print_r($postdata);die;
			$param_keys = array('template_name', 'user_id','order_no','remark','is_mandatory');
			
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
						'field' => 'template_name',
						'label' => 'Template Name',
						'rules' => 'trim|required'
					),
					array(
						'field' => 'user_id',
						'label' => 'User ID',
						'rules' => 'required'
					)
				);
		
				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<li>', '</li>');
		
				if ($this->form_validation->run())
				{
					/*Block update data*/
					$post = new stdClass;					
					$post->user_id = $data['user_id'];
					$post->order_no = $data['order_no'];
					$is_mandatory = 0;
					if($data['is_mandatory']){
						$is_mandatory =1;
					}
					$post->is_mandatory = $is_mandatory;
					$post->remark = $data['remark'];
					$post->template_name = $data['template_name'];
					$post->updated_on = date('Y-m-d H:i:s');
					$post->updated_by = $this->session->userdata('user_id');

					/*Call Model to Update Data*/
					$this->email_setting_model->update($post,$id);
					
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
