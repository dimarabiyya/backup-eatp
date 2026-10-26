<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class site_info_ref extends MX_Controller  {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('msite_info_ref');
	}
	
	########################################################################
	//Manag.site_info_ref
	public function index(){
		$data['judul'] = "REFERENCES";
		$data['juduldetail'] = "Site Info References";
		$data['content_id'] = "site_info_ref_view";
		$this->theme->view("site_info_ref_view", $data);
	}
	
	########################################################################
	#######################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */