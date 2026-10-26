<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class battery_brand extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mbattery_brand');
	}
	
	########################################################################
	//Manag.battery_brand
	public function index(){
		$data['judul'] = "Battery Brand";
		$data['content_id'] = "battery_brand_view";
		$this->theme->view("battery_brand_view", $data);
	}
	
	public function deleteData(){
		$this->mbattery_brand->deleteData();
	}
	
	public function loadData(){
		$this->mbattery_brand->loadData();
	}
	
	public function addeditdata(){
		$input = array('lookup_id','lookup_desc','lookup_value','order');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mbattery_brand->getbattery_brandDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-battery_brand", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id', 'lookup_desc','lookup_value','order');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($lookup_desc == "" || $order == "" ){
			$hasil = "Found Error: \n";
			
			if( $lookup_desc == "" ){
				$hasil .= "Please Input Battery Brand Name \n";
			}
			
			if( $order == "" ){
				$hasil .= "Please Input Order Value \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mbattery_brand->saveData();
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
		$data['content_id'] = "battery_brand/f-group-role";
		
		$data['group'] = $this->mbattery_brand->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mbattery_brand->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */