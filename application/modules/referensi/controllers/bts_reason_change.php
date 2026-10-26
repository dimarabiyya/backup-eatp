<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class bts_reason_change extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mbts_reason_change');
	}
	
	########################################################################
	//Manag.bts_reason_change
	public function index(){
		$data['judul'] = "BTS Reason Change";
		$data['content_id'] = "bts_reason_change_view";
		$this->theme->view("bts_reason_change_view", $data);
	}
	
	public function deleteData(){
		$this->mbts_reason_change->deleteData();
	}
	
	public function loadData(){
		$this->mbts_reason_change->loadData();
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
			$getDetail = $this->mbts_reason_change->getbts_reason_changeDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-bts_reason_change", $data);
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
			$this->mbts_reason_change->saveData();
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
		$data['content_id'] = "bts_reason_change/f-group-role";
		
		$data['group'] = $this->mbts_reason_change->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mbts_reason_change->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */