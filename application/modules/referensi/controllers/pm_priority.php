<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class pm_priority extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpm_priority');
	}
	
	########################################################################
	//Manag.pm_priority
	public function index(){
		$data['judul'] = "Priority";
		$data['content_id'] = "pm_priority_view";
		$this->theme->view("pm_priority_view", $data);
	}
	
	public function deleteData(){
		$this->mpm_priority->deleteData();
	}
	
	public function loadData(){
		$this->mpm_priority->loadData();
	}
	
	public function addeditdata(){
		$input = array('lookup_id','lookup_key','lookup_desc','order','active');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Priority";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Priority";
			$getDetail = $this->mpm_priority->getpm_priorityDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-pm_priority", $data);
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
				$hasil = "Please Input Order \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mpm_priority->saveData();
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
		$data['content_id'] = "pm_priority/f-group-role";
		
		$data['group'] = $this->mpm_priority->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mpm_priority->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */