<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class references extends MX_Controller  {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mreferences');
	}
	
	########################################################################
	//Manag.references
	public function index(){
		$data['judul'] = "REFERENCES";
		$data['juduldetail'] = "Preventive Maintenance References";
		$data['content_id'] = "references_view";
		$this->theme->view("references_view", $data);
	}
	
        ########################################################################
	#######################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */