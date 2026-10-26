<?php

class lookup_user extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model("user_approval_model");			
		$this->load->library('parser');
	}
	
	public function index(){
		$data = array();
		$this->load->model("user_approval_model");
		if($this->input->get("cmd") == "getJson"){
			if ($this->core->is_ajax()){
				echo json_encode($this->user_approval_model->getListUser());
				exit;
			}			
		}
		echo $this->parser->parse('lookup_users', $data);
				exit;	
	}
}
