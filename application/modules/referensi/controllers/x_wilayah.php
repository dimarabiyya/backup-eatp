<?php

class Wilayah extends MX_Controller {

	private $_redirect = '/dashboard';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library(array('form_validation', 'core'));
		
		$this->lang->load('referensi/referensi', 'indonesia');
		
		$this->load->helper('cookie');
		$this->load->helper('language');
		
		$this->load->model('wilayah_model');
		$this->load->model('mmaster');
	}
	
	public function index()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->load->library('pagination');
		
		$per_page = 20;
		$offset = $this->uri->segment(3);
		$data['propinsi'] = $this->wilayah_model->get_propinsi($per_page, $offset);
		
		$config = array(
			'base_url'		=> base_url('/referensi/wilayah'),
			'total_rows'	=> $this->wilayah_model->get_rows_propinsi(),
			'per_page'		=> $per_page,
			'uri_segment'	=> 3
		);
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->theme->set('page_title', 'PMTS - Wilayah');
		$this->theme->view('wilayah_view', $data);
	}
	
	public function propinsi()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		redirect('/referensi/propinsi');

	}
	
	public function kabupaten($parent_id=7)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		redirect('/referensi/kabupaten');
	
	}
	
	
	public function kecamatan_del($id) {
		//echo "del";
		
		$this->core->update('t_mtr_lokasi', array('status' => 0), array('lokasi_id' => $id));
		
		redirect('/referensi/wilayah/kecamatan');
	}
	public function kecamatan($parent_id=7)
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		redirect('/referensi/kecamatan');
	}
	
	
}