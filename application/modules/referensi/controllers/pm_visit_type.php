<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class pm_visit_type extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpm_visit_type');
	}
	
	########################################################################
	//Manag.pm_visit_type
	public function index(){
		$data['judul'] = "PM Visit Type";
		$data['content_id'] = "pm_visit_type_view";
		$this->theme->view("pm_visit_type_view", $data);
	}
	
	public function deleteData(){
		$this->mpm_visit_type->deleteData();
	}
	
	public function loadData(){
		$this->mpm_visit_type->loadData();
	}
	
	public function addeditdata(){
		$input = array('lookup_id','lookup_key','lookup_desc','order','active');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mpm_visit_type->getpm_visit_typeDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-pm_visit_type", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','lookup_desc','order');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($lookup_desc == "" || $order == "" ){
			$hasil = "Found Error: \n";
			
			if($lookup_desc == "" ){
				$hasil = "Please Input Lookup Description \n";
			}
			if($order == "" ){
				$hasil = "please Input Order \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mpm_visit_type->saveData();
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		return;
	}
	########################################################################
	//Manag.GroupRole
	public function GroupRole( $group_id="" ){
		$data['judul'] = "Group Role";
		$data['content_id'] = "pm_visit_type/f-group-role";
		
		$data['group'] = $this->mpm_visit_type->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mpm_visit_type->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */