<?php

class sites extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('site_model');
		$this->load->model('reference_model');
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
	}
	public function index(){
		$this->_check_access();
		if ($this->core->is_ajax())
		{
			if ($this->input->get('cmd') == 'getSiteList')
			{
				$rows = $this->site_model->siteList();
				echo json_encode($rows);
				exit;
			}
		}

		if ($this->input->server('REQUEST_METHOD') == 'DELETE')
		{
			$id = $this->core->decode($this->input->get('siteID'));

			$this->site_model->delete($id);

			$json = array('status' => 'success', 'message' => 'Data deleted successfully.');
			echo json_encode($json);
			exit;
		}
		/*Get All The Region Data*/
		$row_regions = $this->reference_model->regionList();
		/*Get All The Area Data*/
		$row_areas = $this->reference_model->areaList();
		/*Get All The Sity Type*/
		$row_site_types = $this->reference_model->siteTypeList();
		/*Get All The Location Id*/
		$row_locations_id = $this->reference_model->locationIdList();

		$data['regions'] = json_encode($row_regions);
		$data['areas'] = json_encode($row_areas);
		$data['site_types'] = json_encode($row_site_types);
		$data['locations_id'] = json_encode($row_locations_id);
		//var_dump($data['site_types']);
		$data['content_id'] = 'index';

		$this->load->view('template/smashing', $data);
	}
	public function add(){
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{

			$postdata = $this->input->post();

			$param_keys = array('site_id', 'site_name','latitude','longitude','site_address',
				'region_id','area_id','site_type','location_id','remark');

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
						'field' => 'site_id',
						'label' => 'Site ID',
						'rules' => 'required'
					),
					array(
						'field' => 'site_name',
						'label' => 'Name of Site',
						'rules' => 'required'
					),
					array(
						'field' => 'latitude',
						'label' => 'LATITUDE',
						'rules' => 'required|numeric'
					),
					array(
						'field' => 'longitude',
						'label' => 'LONGITUDE',
						'rules' => 'required|numeric'
					),
					array(
						'field' => 'site_address',
						'label' => 'Site Address',
						'rules' => 'required'
					),
					array(
						'field' => 'area_id',
						'label' => 'area_id',
						'rules' => 'required'
					),
					array(
						'field' => 'site_type',
						'label' => 'Type of Site',
						'rules' => 'required'
					),
					array(
						'field' => 'location_id',
						'label' => 'Location Id',
						'rules' => 'required'
					)

				);

				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<li>', '</li>');

				if ($this->form_validation->run())
				{
					/*Convert All Post Date to Standard Object*/
					$post = new stdClass;

					$post->site_name = $data['site_name'];
					$post->latitude = $data['latitude'];
					$post->longitude = $data['longitude'];
					$post->site_address = $data['site_address'];
					/*Lookup Region ID where area_id is $post->area_id*/
					$res_area= $this->reference_model->getRegionByArea($data['area_id']);
					$region_id = isset($res_area->region_id) ? $res_area->region_id : 0;
					/*Set Region ID*/
					$post->region_id = $region_id;

					$post->area_id = $data['area_id'];
					$post->site_type = $data['site_type'];
					$post->location_id = $data['location_id'];
					$post->remarks = $data['remark'];
					$post->created_on = date('Y-m-d H:i:s');
					$post->created_by = $this->session->userdata('user_id');

					/*Check if Site Data is Exsist*/
					if($this->site_model->getSiteBySiteID($data['site_id'])){
						$post->updated_on = date('Y-m-d H:i:s');
						$post->updated_by = $this->session->userdata('user_id');
						$post->is_deleted = 0;
						/*Call Update*/
						$this->site_model->update($post,$data['site_id']);

					}else{
						/*Call Model to Insert Data*/
						$post->site_id = $data['site_id'];
						$this->site_model->insert($post);
					}


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
	/*Edit Form*/
	public function edit($id){
		$id = $this->core->decode($id);

		if ($this->input->server('REQUEST_METHOD') == 'GET'){

			$row = $this->site_model->getSite($id);

			echo json_encode($row);
			exit;
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$postdata = $this->input->post();

			$param_keys = array('site_id', 'site_name','latitude','longitude','site_address',
				'region_id','area_id','site_type','location_id','remark');

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
						'field' => 'site_id',
						'label' => 'Site ID',
						'rules' => 'required'
					),
					array(
						'field' => 'site_name',
						'label' => 'Name of Site',
						'rules' => 'required'
					),
					array(
						'field' => 'latitude',
						'label' => 'LATITUDE',
						'rules' => 'required|numeric'
					),
					array(
						'field' => 'longitude',
						'label' => 'LONGITUDE',
						'rules' => 'required|numeric'
					),
					array(
						'field' => 'site_address',
						'label' => 'Site Address',
						'rules' => 'required'
					),
					array(
						'field' => 'area_id',
						'label' => 'area_id',
						'rules' => 'required'
					),
					array(
						'field' => 'site_type',
						'label' => 'Type of Site',
						'rules' => 'required'
					),
					array(
						'field' => 'location_id',
						'label' => 'Location Id',
						'rules' => 'required'
					)

				);

				$this->form_validation->set_rules($validation_rules);
				$this->form_validation->set_error_delimiters('<li>', '</li>');

				if ($this->form_validation->run())
				{
					/*Convert All Post Date to Standard Object*/
					$post = new stdClass;

					$post->site_id = $data['site_id'];
					$post->site_name = $data['site_name'];
					$post->latitude = $data['latitude'];
					$post->longitude = $data['longitude'];
					$post->site_address = $data['site_address'];
					/*Lookup Region ID where area_id is $post->area_id*/
					$res_area= $this->reference_model->getRegionByArea($data['area_id']);
					$region_id = $res_area->region_id;
					/*Set Region ID*/
					$post->region_id = $region_id;

					$post->area_id = $data['area_id'];
					$post->site_type = $data['site_type'];
					$post->location_id = $data['location_id'];
					$post->remarks = $data['remark'];
					$post->updated_on = date('Y-m-d H:i:s');
					$post->updated_by = $this->session->userdata('user_id');

					/*Call Model to Insert Data*/
					$this->site_model->update($post,$id);

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

	public function exportSite()
	{
		$this->site_model->exportSite();


	}

	public function view($view)
	{
		$this->load->view($view);
	}
	/*filter area by name*/
	public function filterareabyregion($region=0){
		if($region==0){
			echo json_encode($this->reference_model->areaList());
		}else{
			echo json_encode($this->reference_model->areaListByRegion($region));
		}

	}
}
