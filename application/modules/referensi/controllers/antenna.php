<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class antenna extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mantenna');
	}
	
	########################################################################
	//Manag.antenna
	public function index(){
		$data['judul'] = "List Antenna";
		$data['content_id'] = "antenna_view";
		$this->theme->view("antenna_view", $data);
	}
	
	public function deleteData(){
		$this->mantenna->deleteData();
	}
	
	public function loadData(){
		$this->mantenna->loadData();
	}
	
	public function addeditdata(){
		$input = array('id','type','band','beamwidth','beamwidth_value','dimension_l','dimension_h','dimension_w','active');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mantenna->getantennaDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-antenna", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','type','band','beamwidth','beamwidth_value','dimension_l','dimension_h','dimension_w');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($type == "" || $band == "" || $beamwidth == "" || $beamwidth_value == "" || $dimension_l == "" || $dimension_h == "" || $dimension_w == "" ){
			$hasil = "Found Error : \n";
			
			if($type == "" ){
				$hasil = "Please Input Type \n";
			}
			if($band == "" ){
				$hasil = "Please Choose Band \n";
			}
			if($beamwidth == "" ){
				$hasil = "Please Choose Beamwidth \n";
			}
			if($beamwidth_value == "" ){
				$hasil = "Please Input Beamwidth Value \n";
			}
			if($dimension_l == "" ){
				$hasil = "Please Input Dimension L \n";
			}
			if($dimension_h == "" ){
				$hasil = "Please Input Dimension H \n";
			}
			if($dimension_w == "" ){
				$hasil = "Please Input Dimension W \n";
			}
			
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mantenna->saveData();
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
		$data['content_id'] = "antenna/f-group-role";
		
		$data['group'] = $this->mantenna->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mantenna->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */