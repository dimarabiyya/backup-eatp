<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class shelter_type extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mshelter_type');
	}
	
	########################################################################
	//Manag.shelter_type
	public function index(){
		$data['judul'] = "Shelter Type";
		$data['content_id'] = "shelter_type_view";
		$this->theme->view("shelter_type_view", $data);
	}
	
	public function deleteData(){
		$this->mshelter_type->deleteData();
	}
	
	public function loadData(){
		$this->mshelter_type->loadData();
	}
	
	public function addeditdata(){
		$input = array('shelter_type_id','descr','order');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mshelter_type->getshelter_typeDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-shelter_type", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id', 'descr' , 'order');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($descr == "" || $order == "" ){
			$hasil = "Found Error: \n";
			
			if( $descr == "" ){
				$hasil .= "Please Input Shelter Type Name \n";
			}
			
			if( $order == "" ){
				$hasil .= "Please Input Order \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mshelter_type->saveData();
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
		$data['content_id'] = "shelter_type/f-group-role";
		
		$data['group'] = $this->mshelter_type->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mshelter_type->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */