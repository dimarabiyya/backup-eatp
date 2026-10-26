<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class sla extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('msla');
	}
	
	########################################################################
	//Manag.sla
	public function index(){
		$data['judul'] = "SLA";
		$data['content_id'] = "sla_view";
		$this->theme->view("sla_view", $data);
	}
	
	public function deleteData(){
		$this->msla->deleteData();
	}
	
	public function loadData(){
		$this->msla->loadData();
	}
	
	public function addeditdata(){
		$input = array('sla_id','loc_id','sev_id','sla');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add SLA";
		if($data['id'] <> "") {
			$data['judul'] = "Edit SLA";
			$getDetail = $this->msla->getslaDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}//print_r($getDetail);
		}	
		echo $this->load->view("r-sla", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','loc_id','sev_id','sla');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($loc_id == "" || $sev_id == "" || $sla == ""){
			$hasil = "Found Error: \n";
			
			if( $loc_id == "" ){
				$hasil .= "Please Choose Location \n";
			}
			
			if( $sev_id == "" ){
				$hasil .= "Please Chooose Network Severity \n";
			}
			
			if( $sla == "" ){
				$hasil .= "Please Input SLA \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->msla->saveData();
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
		$data['content_id'] = "sla/f-group-role";
		
		$data['group'] = $this->msla->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->msla->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */