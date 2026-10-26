<?php

class Referensi extends MX_Controller {

	private $_redirect = '/dashboard';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library(array('form_validation', 'core'));
		
		$this->lang->load('referensi/referensi', 'indonesia');
		
		$this->load->helper('cookie');
		$this->load->helper('language');
		
		$this->load->model('referensi_model');
		$this->load->model('mmaster');
	}
	
	public function index()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->theme->set('page_title', 'PMTS - Referensi');		
		$this->theme->view('referensi_view');
	}

	public function site_type()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}

		
		redirect('/referensi/site_type');
	}

	public function status_tp()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		//die(print_r($this->referensi_model->get_status_tp_all()));
		if ($this->core->is_ajax()) {
			
			if ($this->input->get('cmd') == 'GET_STATUS_TP') {
			
				$json = $this->referensi_model->get_status_tp_all();
				
				echo json_encode($json);
				exit;
			}
			
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Status Tp');		
		$this->theme->view('status_tp_view');
	}

	public function site_specification()
	{
		redirect('/referensi/site_specification');
	}

	public function region()
	{
		redirect('/referensi/region');
	}

	public function shelter_type()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		redirect('/referensi/shelter_type');
	}

	public function tipe_perangkat_site()
	{
		redirect('/referensi/tipe_perangkat_site');
		
	}
	
	public function site_tp_kandidat()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		//die(print_r($this->referensi_model->get_status_tp_all()));
		if ($this->core->is_ajax()) {
			
			if ($this->input->get('cmd') == 'GET_SITE_TP_KANDIDAT') {
			
				$json = $this->referensi_model->get_site_tp_kandidat_all();
				
				echo json_encode($json);
				exit;
			}
			
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Site TP Kandidat');		
		$this->theme->view('site_tp_kandidat_view');
	}
	
	public function tipe_perangkat_ceklis_group()
	{
		redirect('/referensi/tipe_perangkat_ceklis_group');
	}
	
	public function ceklis_property_form()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		//die(print_r($this->referensi_model->get_status_tp_all()));
		if ($this->core->is_ajax()) {
			
			if ($this->input->get('cmd') == 'GET_CEKLIS_PROPERTY_FORM') {
			
				$json = $this->referensi_model->get_ceklis_property_form_all();
				
				echo json_encode($json);
				exit;
			}
			
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Ceklis Property Form');		
		$this->theme->view('ceklis_property_form_view');
	}
	
	public function perangkat_site_ceklis()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		//die(print_r($this->referensi_model->get_status_tp_all()));
		if ($this->core->is_ajax()) {
			
			if ($this->input->get('cmd') == 'GET_PERANGKAT_SITE_CEKLIS') {
			
				$json = $this->referensi_model->get_perangkat_site_ceklis_all();
				
				echo json_encode($json);
				exit;
			}
			
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Perangkat Site Ceklis ');		
		$this->theme->view('perangkat_site_ceklis_view');
	}
	
	public function pm_checklist()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		redirect('/referensi/pm_checklist');
	}
	
	public function partner()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		redirect('/referensi/partner');
	}

	public function add_partner()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('partnerCode', 'Partner Code', 'required');
		$this->form_validation->set_rules('partnerName', 'Partner Name', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('city', 'City', 'required');
		$this->form_validation->set_rules('provinceId', 'Province ID', 'required');
		$this->form_validation->set_rules('imagePath', 'Image Path', 'required');
		$this->form_validation->set_rules('lon', 'Lon', 'required');
		$this->form_validation->set_rules('lat', 'Lat', 'required');
		$this->form_validation->set_rules('partnerType', 'Partner Type', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('partnerIdText', 'Partner ID Text', 'required');
		$this->form_validation->set_rules('gisMarker', 'Gis Marker', 'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['partner_code'] = $post['partnerCode'];
			$this->core->set['partner_name'] = $post['partnerName'];
			$this->core->set['address'] = $post['address'];
			$this->core->set['city'] = $post['city'];
			$this->core->set['province_id'] = $post['provinceId'];
			$this->core->set['image_path'] = $post['imagePath'];
			$this->core->set['lon'] = $post['lon'];
			$this->core->set['lat'] = $post['lat'];
			$this->core->set['partner_type'] = $post['partnerType'];
			$this->core->set['status'] = $post['status'];
			$this->core->set['partner_id_text'] = $post['partnerIdText'];
			$this->core->set['gis_marker'] = $post['gisMarker'];
			
			$this->core->save('t_mtr_partner');
			
			redirect('/referensi/partner');
		}
		
		$this->_form_partner();
	}
	
	public function edit_partner($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('partnerCode', 'Partner Code', 'required');
		$this->form_validation->set_rules('partnerName', 'Partner Name', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('city', 'City', 'required');
		$this->form_validation->set_rules('provinceId', 'Province ID', 'required');
		$this->form_validation->set_rules('imagePath', 'Image Path', 'required');
		$this->form_validation->set_rules('lon', 'Lon', 'required');
		$this->form_validation->set_rules('lat', 'Lat', 'required');
		$this->form_validation->set_rules('partnerType', 'Partner Type', 'required');
		$this->form_validation->set_rules('status', 'Status', 'readonly');
		$this->form_validation->set_rules('partnerIdText', 'Partner ID Text', 'required');
		$this->form_validation->set_rules('gisMarker', 'Gis Marker', 'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['partner_code'] = $post['partnerCode'];
			$this->core->set['partner_name'] = $post['partnerName'];
			$this->core->set['address'] = $post['address'];
			$this->core->set['city'] = $post['city'];
			$this->core->set['province_id'] = $post['provinceId'];
			$this->core->set['image_path'] = $post['imagePath'];
			$this->core->set['lon'] = $post['lon'];
			$this->core->set['lat'] = $post['lat'];
			$this->core->set['partner_type'] = $post['partnerType'];
			$this->core->set['status'] = $post['status'];
			$this->core->set['partner_id_text'] = strtolower($post['partnerName']);
			$this->core->set['gis_marker'] = $post['gisMarker'];
			
			$this->core->save('t_mtr_partner', array('partner_id' => $id));
			
			redirect('/referensi/partner');
		}
		
		$this->_form_partner($id);
	}
	
	private function _form_partner($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_partner') {
		
			$data['partner'] = $this->core->view_row('t_mtr_partner', array('partner_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Partner ');		
		$this->theme->view('form_partner_view', $data);
	}
	
	public function delete_partner($id)
	{
		$this->core->update('t_mtr_partner', array('status' => 0), array('partner_id' => $id));
  
		redirect('/referensi/referensi/partner');
	}
	
	///////////////--> Site Type <--/////////////////////////
	public function add_site_type()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('siteTypeName', 'Site Type Name', 'required');
		$this->form_validation->set_rules('idText', 'Id Text', 'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['site_type_name'] = $post['siteTypeName'];
			$this->core->set['id_text'] = $post['idText'];
			
			$this->core->save('t_mtr_site_type');
			
			redirect('/referensi/site_type');
		}
		
		$this->_form_site_type();
	}
	
	public function edit_site_type($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('siteTypeName', 'Site Type Name', 'required');
		$this->form_validation->set_rules('idText', 'Id Text', 'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['site_type_name'] = $post['siteTypeName'];
			$this->core->set['id_text'] = strtolower($post['idText']);
			
			$this->core->save('t_mtr_site_type', array('type_id' => $id));
			
			redirect('/referensi/site_type');
		}
		
		$this->_form_site_type($id);
	}
	
	private function _form_site_type($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_site_type') {
		
			$data['site_type'] = $this->core->view_row('t_mtr_site_type', array('type_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Site Type ');		
		$this->theme->view('form_site_type_view', $data);
	}
	
	public function delete_site_type($id)
	{
		$this->core->update('t_mtr_site_type', array('status' => 0), array('type_id' => $id));
  
		redirect('/referensi/referensi/site_type');
	}
	
//////////////--> Region <--//////////////////////////////////////////////
	public function add_region()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('regionName', 'Region Name', 'required');
		$this->form_validation->set_rules('regionIdText', 'Region Id Text', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['region_name'] = $post['regionName'];
			$this->core->set['region_id_text'] = $post['regionIdText'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_mtr_region');
			
			redirect('/referensi/region');
		}
		
		$this->_form_region();
	}
	
	public function edit_region($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('regionName', 'Region Name', 'required');
		$this->form_validation->set_rules('regionIdText', 'Region Id Text', 'required');
		$this->form_validation->set_rules('status', 'status', 'readonly');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['region_name'] = $post['regionName'];
			$this->core->set['region_id_text'] = strtolower($post['regionIdText']);
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_mtr_region', array('region_id' => $id));
			
			redirect('/referensi/region');
		}
		
		$this->_form_region($id);
	}
	
	private function _form_region($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_region') {
		
			$data['region'] = $this->core->view_row('t_mtr_region', array('region_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Region ');		
		$this->theme->view('form_region_view', $data);
	}
	
	public function delete_region($id)
	{
		$this->core->update('t_mtr_region', array('status' => 0), array('region_id' => $id));
  
		redirect('/referensi/referensi/region');
	}
	//////////////--> Shelter Type <--//////////////////////////////////////////////
	public function add_shelter_type()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('descr', 'Descr', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required' );
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['descr'] = $post['descr'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_mtr_shelter_type');
			
			redirect('/referensi/shelter_type');
		}
		
		$this->_form_shelter_type();
	}
	
	public function edit_shelter_type($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('descr', 'Descr', 'required');
		$this->form_validation->set_rules('status', 'Status', 'readonly');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['descr'] = $post['descr'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_mtr_shelter_type', array('shelter_type_id' => $id));
			
			redirect('/referensi/shelter_type');
		}
		
		$this->_form_shelter_type($id);
	}
	
	private function _form_shelter_type($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_shelter_type') {
		
			$data['shelter_type'] = $this->core->view_row('t_mtr_shelter_type', array('shelter_type_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Shelter Type ');		
		$this->theme->view('form_shelter_type_view', $data);
	}
	
	public function delete_shelter_type($id)
	{
		$this->core->update('t_mtr_shelter_type', array('status' => 0), array('shelter_type_id' => $id));
  
		redirect('/referensi/referensi/shelter_type');
	}
	//////////////--> Tipe Perangkat Site <--//////////////////////////////////////////////
	public function add_tipe_perangkat_site()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['nama'] = $post['nama'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_m_tipe_perangkat_site');
			
			redirect('/referensi/tipe_perangkat_site');
		}
		
		$this->_form_tipe_perangkat_site();
	}
	
	public function edit_tipe_perangkat_site($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('status', 'Status', 'readony');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['nama'] = $post['nama'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_m_tipe_perangkat_site', array('perangkat_id' => $id));
			
			redirect('/referensi/tipe_perangkat_site');
		}
		
		$this->_form_tipe_perangkat_site($id);
	}
	
	private function _form_tipe_perangkat_site($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_tipe_perangkat_site') {
		
			$data['tipe_perangkat_site'] = $this->core->view_row('t_m_tipe_perangkat_site', array('perangkat_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Tipe Perangkat Site ');		
		$this->theme->view('form_tipe_perangkat_site_view', $data);
	}
	
	public function delete_tipe_perangkat_site($id)
	{
		$this->core->update('t_m_tipe_perangkat_site', array('status' => 0), array('perangkat_id' => $id));
  
		redirect('/referensi/referensi/tipe_perangkat_site');
	}
	//////--> Site TP Kandidat <--//////////////////////////////////////////////
	public function add_site_tp_kandidat()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('site_id_tp', 'Site ID TP', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required' );
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['site_id_tp'] = $post['siteIdTp'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_site_tp_kandidat');
			
			redirect('/referensi/site_tp_kandidat');
		}
		
		$this->_form_site_tp_kandidat();
	}
	
	public function edit_site_tp_kandidat($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('siteIdTp', 'Site ID TP', 'required');
		$this->form_validation->set_rules('status', 'Status', 'readonly');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['site_id_tp'] = $post['siteIdTp'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_site_tp_kandidat', array('site_id' => $id));
			
			redirect('/referensi/site_tp_kandidat');
		}
		
		$this->_form_site_tp_kandidat($id);
	}
	
	private function _form_site_tp_kandidat($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_site_tp_kandidat') {
		
			$data['site_tp_kandidat'] = $this->core->view_row('t_site_tp_kandidat', array('site_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Site TP Kandidat ');		
		$this->theme->view('form_site_tp_kandidat_view', $data);
	}
	
	
	public function delete_site_tp_kandidat($id)
	{
		$this->core->update('t_site_tp_kandidat', array('status' => 0), array('site_id' => $id));
  
		redirect('/referensi/referensi/site_tp_kandidat');
	}
	//////////////--> Site Specification <--//////////////////////////////////////////////
	public function add_site_specification()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('descr', 'Descr', 'required');
		$this->form_validation->set_rules('status', 'Status',  'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['descr'] = $post['descr'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_mtr_site_specification');
			
			redirect('/referensi/site_specification');
		}
		
		$this->_form_site_specification();
	}
	
	public function edit_site_specification($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('descr', 'Descr', 'required');
		$this->form_validation->set_rules('status', 'Status' , 'readonly');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['descr'] = $post['descr'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_mtr_site_specification', array('specification_id' => $id));
			
			redirect('/referensi/site_specification');
		}
		
		$this->_form_site_specification($id);
	}
	
	private function _form_site_specification($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_site_specification') {
		
			$data['site_specification'] = $this->core->view_row('t_mtr_site_specification', array('specification_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Site Specification ');		
		$this->theme->view('form_site_specification_view', $data);
	}
	
	public function delete_site_specification($id)
	{
		$this->core->update('t_mtr_site_specification', array('status' => 0), array('specification_id' => $id));
  
		redirect('/referensi/referensi/site_specification');
	}
	//////////////--> Tipe Perangkat Ceklis Group <--//////////////////////////////////////////////
	public function add_tipe_perangkat_ceklis_group()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['nama'] = $post['nama'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_m_tipe_perangkat_ceklis_group');
			
			redirect('/referensi/tipe_perangkat_ceklis_group');
		}
		
		$this->_form_tipe_perangkat_ceklis_group();
	}
	
	public function edit_tipe_perangkat_ceklis_group($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('status', 'Status', 'readonly');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['nama'] = $post['nama'];
			$this->core->set['status'] = $post['status'];
			
			$this->core->save('t_m_tipe_perangkat_ceklis_group', array('group_id' => $id));
			
			redirect('/referensi/tipe_perangkat_ceklis_group');
		}
		
		$this->_form_tipe_perangkat_ceklis_group($id);
	}
	
	private function _form_tipe_perangkat_ceklis_group($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_tipe_perangkat_ceklis_group') {
		
			$data['tipe_perangkat_ceklis_group'] = $this->core->view_row('t_m_tipe_perangkat_ceklis_group', array('group_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Tipe Perangkat Ceklis Group ');		
		$this->theme->view('form_tipe_perangkat_ceklis_group_view', $data);
	}
	
	public function delete_tipe_perangkat_ceklis_group($id)
	{
		$this->core->update('t_m_tipe_perangkat_ceklis_group', array('status' => 0), array('group_id' => $id));
  
		redirect('/referensi/referensi/tipe_perangkat_ceklis_group');
	}
	//////////////--> Status TP <--//////////////////////////////////////////////
	public function add_status_tp()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('statusTpName', 'Status TP Name', 'required');
		$this->form_validation->set_rules('statusTpIdText', 'Status TP Id Text', 'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['status_tp_name'] = $post['statusTpName'];
			$this->core->set['status_tp_id_text'] = $post['statusTpIdText'];
			
			$this->core->save('t_mtr_status_tp');
			
			redirect('/referensi/status_tp');
		}
		
		$this->_form_status_tp();
	}
	
	public function edit_status_tp($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('statusTpName', 'Status TP Name', 'required');
		$this->form_validation->set_rules('statusTpIdText', 'Status TP Id Text', 'readonly');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['status_tp_name'] = $post['statusTpName'];
			$this->core->set['status_tp_id_text'] = strtolower($post['statusTpIdText']);
			
			$this->core->save('t_mtr_status_tp', array('status_tp_id' => $id));
			
			redirect('/referensi/status_tp');
		}
		
		$this->_form_status_tp($id);
	}
	
	private function _form_status_tp($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_status_tp') {
		
			$data['status_tp'] = $this->core->view_row('t_mtr_status_tp', array('status_tp_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Status TP ');		
		$this->theme->view('form_status_tp_view', $data);
	}
	
	public function delete_status_tp($id)
	{
		$this->core->update('t_mtr_status_tp', array('status' => 0), array('status_tp_id' => $id));
  
		redirect('/referensi/referensi/status_tp');
	}
	//////////////--> Ceklis Property Form <--//////////////////////////////////////////////
	public function add_ceklis_property_form()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('descr', 'Descr', 'required' );
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['nama'] = $post['nama'];
			$this->core->set['descr'] = $post['descr'];
			
			$this->core->save('t_m_ceklis_property_form');
			
			redirect('/referensi/ceklis_property_form');
		}
		
		$this->_form_ceklis_property_form();
	}
	
	public function edit_ceklis_property_form($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('descr', 'Descr', 'required', 'readonly');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['nama'] = $post['nama'];
			$this->core->set['descr'] = $post['Descr'];
			
			$this->core->save('t_m_ceklis_property_form', array('property_id' => $id));
			
			redirect('/referensi/ceklis_property_form');
		}
		
		$this->_form_ceklis_property_form($id);
	}
	
	private function _form_ceklis_property_form($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_ceklis_property_form') {
		
			$data['tipe_perangkat_ceklis_group'] = $this->core->view_row('t_m_ceklis_property_form', array('property_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - Ceklis Property Form ');		
		$this->theme->view('form_ceklis_property_form_view', $data);
	}
	
	public function delete_ceklis_property_form($id)
	{
		$this->core->update('t_m_ceklis_property_form', array('status' => 0), array('property_id' => $id));
  
		redirect('/referensi/referensi/ceklis_property_form');
	}
	
	//////////////--> PM Checklist <--//////////////////////////////////////////////
	public function add_pm_checklist()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('checklistId', 'Checklist ID', 'required');
		$this->form_validation->set_rules('groupId', 'Group ID', 'required');
		$this->form_validation->set_rules('descr', 'Descr', 'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['checklist_id'] = $post['checklistId'];
			$this->core->set['group_id'] = $post['groupId'];
			$this->core->set['descr'] = $post['descr'];
			
			$this->core->save('t_mtr_pm_checklist');
			
			redirect('/referensi/pm_checklist');
		}
		
		$this->_form_pm_checklist();
	}
	
	public function edit_pm_checklist($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->form_validation->set_rules('checklistId', 'Checklist ID', 'required');
		$this->form_validation->set_rules('groupId', 'Group ID', 'required');
		$this->form_validation->set_rules('descr', 'Descr', 'required');
		
		$this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');
		
		if ($this->form_validation->run()) {
		
			$post = $this->core->get_post();
			
			$this->core->set['checklist_id'] = $post['checklistId'];
			$this->core->set['group_id'] = $post['groupId'];
			$this->core->set['descr'] = $post['descr'];
			
			$this->core->save('t_mtr_pm_checklist', array('checklist_id' => $id));
			
			redirect('/referensi/pm_checklist');
		}
		
		$this->_form_pm_checklist($id);
	}
	
	private function _form_pm_checklist($id = '')
	{ 
		$data = array();
		
		if (!empty($id) && $this->uri->segment(3) == 'edit_pm_checklist') {
		
			$data['pm_checklist'] = $this->core->view_row('t_mtr_pm_checklist', array('checklist_id' => $id));
		}
		
		//$this->theme->set_layout('referensi-layout');
		$this->theme->set('page_title', 'PMTS - Referensi - PM Checklist ');		
		$this->theme->view('form_pm_checklist_view', $data);
	}
	
	public function delete_pm_checklist($id)
	{
		$this->core->update('t_mtr_pm_checklist', array('status' => 0), array('checklist_id' => $id));
  
		redirect('/referensi/referensi/pm_checklist');
	}
//////////////////////////////////////////
	public function pm_cheklist_property_option($id)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		redirect('/referensi/pm_checklist_property_option');
	}
	
	
}