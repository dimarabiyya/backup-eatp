<?php

class lookup_fop extends MX_Controller {
	
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
				echo json_encode($this->user_pic_model->getUserList(3)); /*As Group FOP*/
				exit;
			}			
		}
		echo $this->parser->parse('lookup_fop', $data);
				exit;	
	}
}
