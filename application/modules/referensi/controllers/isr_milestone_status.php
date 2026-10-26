<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class isr_milestone_status extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('misr_milestone_status');
	}
	
	########################################################################
	//Manag.isr_milestone_status
	public function index(){
		$data['judul'] = "ISR Milestone Status";
		$data['content_id'] = "isr_milestone_status_view";
		$this->theme->view("isr_milestone_status_view", $data);
	}
	
	public function deleteData(){
		$this->misr_milestone_status->deleteData();
	}
	
	public function loadData(){
		$this->misr_milestone_status->loadData();
	}
	
	public function addeditdata(){
		$input = array('id','name','active');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->misr_milestone_status->getisr_milestone_statusDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-isr_milestone_status", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','name');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($name == "" ){
			$hasil = "Found Error: \n";
			if($name == "" ){
				$hasil = "Please Input Name \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->misr_milestone_status->saveData();
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
		$data['content_id'] = "isr_milestone_status/f-group-role";
		
		$data['group'] = $this->misr_milestone_status->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->misr_milestone_status->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */