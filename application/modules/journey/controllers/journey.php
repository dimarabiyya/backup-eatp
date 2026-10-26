<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Journey extends CI_Controller
{
	
	public function __construct() {
		parent::__construct();
		$this->load->library('enc');
		$this->load->model('mservices');
		$this->load->model('mmaster' );
		//$this->load->model('mglobal');
		//$this->load->model('mjc');
		//$this->load->model('mjourney');		
		$this->load->model('journey_models');
		//$this->load->model('musers');
		$this->is_logged_in();
	}

	public function index()
	{
		if (!$this->session->userdata('is_logged_in')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		//die(print_r($this->referensi_model->get_status_tp_all()));
		if ($this->core->is_ajax()) {
			
			if ($this->input->get('cmd') == 'GET_JOURNEY') {
			
				$json = $this->referensi_model->get_status_tp_all();
				
				echo json_encode($json);
				exit;
			}
			
		}
		$data['yearss']    = date('Y'); 
		$data['yearse']    = date('Y')+5;
		$this->theme->set('page_title', 'PMTS - Referensi - Status Tp');		
		$this->theme->view('journey_view',$data);
	}

	public function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			redirect(base_url() .'index.php/login');
		}
	}


	public function choice($id) {
		$data['template'] = "shell/smooth";
        $data['users']    = $this->db->query('SELECT * FROM t_mtr_site_general');
        $data['main_view'] = 'journey_view';
		//$data['proses']	= $proses;
		$data['id']	= $id;
		$data['yearss']    = date('Y'); 
		$data['yearse']    = date('Y')+5;
		
		
        $this->load->view($data['template'],$data);
	}
	

}
/* End of file jc.php */
/* Location: ./application/controller/jc.php */