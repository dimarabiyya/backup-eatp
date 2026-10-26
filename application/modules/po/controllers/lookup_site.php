<?php

class lookup_site extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model("site_model");			
		$this->load->library('parser');
	}

	public function index(){
		$data = array();
		if($this->input->get("cmd") == "getJson"){
			if ($this->core->is_ajax()){
				echo json_encode($this->site_model->getSiteList());
				exit;
			}			
		}
		$data["region"] = $this->input->get("region");
		echo $this->parser->parse('lookup_site', $data);
				exit;	
	}
	public function test(){		
		echo json_encode($this->site_model->getSiteList());

	}
}
