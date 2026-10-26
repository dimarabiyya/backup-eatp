<?php

class lookup_pic extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model("user_pic_model");			
		$this->load->library('parser');
	}
	
	public function index(){
		$data = array();
		if($this->input->get("cmd") == "getJson"){
			if ($this->core->is_ajax()){
				echo json_encode($this->user_pic_model->getUserList(11));
				exit;
			}			
		}
		echo $this->parser->parse('lookup_pic', $data);
				exit;	
	}
}
