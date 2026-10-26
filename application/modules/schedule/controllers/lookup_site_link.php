<?php

class lookup_site_link extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model("site_link_model");			
		$this->load->library('parser');
	}

	public function index(){
		$data = array();
		if($this->input->get("cmd") == "getJson"){
			if ($this->core->is_ajax()){
				echo json_encode($this->site_link_model->getSiteLinkList());
				exit;
			}			
		}
		echo $this->parser->parse('lookup_sitelink', $data);
				exit;	
	}
	public function getlinkpo(){
		$po_id = $this->input->get("po");
		echo json_encode($this->site_link_model->getLinkByPO($po_id));

	}
}
