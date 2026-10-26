<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class tp_category extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mtp_category');
	}
	
	########################################################################
	//Manag.tp_category
	public function index(){
		$data['judul'] = "TP Category";
		$data['content_id'] = "tp_category_view";
		$this->theme->view("tp_category_view", $data);
	}
	
	public function deleteData(){
		$this->mtp_category->deleteData();
	}
	
	public function loadData(){
		$this->mtp_category->loadData();
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
			$getDetail = $this->mtp_category->gettp_categoryDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-tp_category", $data);
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
				$hasil .= "Please Input TP Category Name \n";
			}
			
			if( $order == "" ){
				$hasil .= "Please Input Order Value \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mtp_category->saveData();
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
		$data['content_id'] = "tp_category/f-group-role";
		
		$data['group'] = $this->mtp_category->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mtp_category->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */