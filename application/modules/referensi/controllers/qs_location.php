<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class qs_location extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mqs_location');
	}
	
	########################################################################
	//Manag.qs_location
	public function index(){
		$data['judul'] = "Location";
		$data['content_id'] = "qs_location_view";
		
		$this->theme->view("qs_location_view", $data);
	}
	
	public function deleteData(){
		$this->mqs_location->deleteData();
	}
	
	public function loadData(){
		$this->mqs_location->loadData();
	}
	
	public function addeditdata(){
		$input = array('lookup_id','lookup_key','lookup_desc','order','active');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Location";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Location";
			$getDetail = $this->mqs_location->getqs_locationDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-qs_location", $data);
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
				$hasil = "Please Input Location Description \n";
			}
			if($order == "" ){
				$hasil = "Please Input Order \n";
			}
			} elseif ( $lookup_desc != '' && $this->core->view_row('t_mtr_lookup', array('UPPER(lookup_desc)' => strtoupper($lookup_desc), 'active' => 1, 'lookup_id !='=> (int) $id)) ) {
			$hasil .= "Location Description has already in used \n";
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mqs_location->saveData();
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
		$data['content_id'] = "qs_location/f-group-role";
		
		$data['group'] = $this->mqs_location->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mqs_location->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */