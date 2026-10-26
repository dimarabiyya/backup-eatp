<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class qs_root_cause extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mqs_root_cause');
	}
	
	########################################################################
	//Manag.qs_root_cause
	public function index(){
		$data['judul'] = "Root Cause";
		$data['content_id'] = "qs_root_cause_view";
		
		$this->theme->view("qs_root_cause_view", $data);
	}
	
	public function deleteData(){
		$this->mqs_root_cause->deleteData();
	}
	
	public function loadData(){
		$this->mqs_root_cause->loadData();
	}
	
	public function addeditdata(){
		$input = array('lookup_id','lookup_key','lookup_desc','order','active');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Root Cause";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Root Cause";
			$getDetail = $this->mqs_root_cause->getqs_root_causeDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-qs_root_cause", $data);
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
				$hasil = "Please Input Root Cause Description \n";
			}
			if($order == "" ){
				$hasil = "Please Input Order \n";
			}
			} elseif ( $lookup_desc != '' && $this->core->view_row('t_mtr_lookup', array('UPPER(lookup_desc)' => strtoupper($lookup_desc), 'active' => 1, 'lookup_id !='=> (int) $id)) ) {
			$hasil .= "Root Cause Description has already in used \n";
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mqs_root_cause->saveData();
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=> $hasil
		));
		return;
	}
	########################################################################
	//Manag.GroupRole
	public function GroupRole( $group_id="" ){
		$data['judul'] = "Group Role";
		$data['content_id'] = "qs_root_cause/f-group-role";
		
		$data['group'] = $this->mqs_root_cause->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mqs_root_cause->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */