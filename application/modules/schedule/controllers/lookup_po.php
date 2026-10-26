<?php

class lookup_po extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model("po_model");			
		$this->load->library('parser');
	}

	public function index(){
		$data = array();
		if($this->input->get("cmd") == "getJson"){
			if ($this->core->is_ajax()){
				echo json_encode($this->po_model->getList());
				exit;
			}			
		}
		echo $this->parser->parse('lookup_po', $data);
				exit;	
	}
}
