<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ho_type extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mho_type');
	}
	
	########################################################################
	//Manag.ho_type
	public function index(){
		$data['judul'] = "HO Type";
		$data['content_id'] = "ho_type_view";
		$this->theme->view("ho_type_view", $data);
	}
	
	public function deleteData(){
		$this->mho_type->deleteData();
	}
	
	public function loadData(){
		$this->mho_type->loadData();
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
			$getDetail = $this->mho_type->getho_typeDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-ho_type", $data);
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
				$hasil .= "Please Input HO Type Name \n";
			}
			
			if( $order == "" ){
				$hasil .= "Please Input Order Value \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mho_type->saveData();
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
		$data['content_id'] = "ho_type/f-group-role";
		
		$data['group'] = $this->mho_type->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mho_type->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */