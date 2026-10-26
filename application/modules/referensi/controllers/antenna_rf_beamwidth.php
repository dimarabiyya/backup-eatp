<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class antenna_rf_beamwidth extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mantenna_rf_beamwidth');
	}
	
	########################################################################
	//Manag.antenna_rf_beamwidth
	public function index(){
		$data['judul'] = "Antenna RF Beamwidth";
		$data['content_id'] = "antenna_rf_beamwidth_view";
		$this->theme->view("antenna_rf_beamwidth_view", $data);
	}
	
	public function deleteData(){
		$this->mantenna_rf_beamwidth->deleteData();
	}
	
	public function loadData(){
		$this->mantenna_rf_beamwidth->loadData();
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
			$getDetail = $this->mantenna_rf_beamwidth->getantenna_rf_beamwidthDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-antenna_rf_beamwidth", $data);
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
			$this->mantenna_rf_beamwidth->saveData();
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
		$data['content_id'] = "antenna_rf_beamwidth/f-group-role";
		
		$data['group'] = $this->mantenna_rf_beamwidth->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mantenna_rf_beamwidth->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */