<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Site_specification extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('msite_specification');
	}
	
	########################################################################
	//Manag.site_specification
	public function index(){
		$data['judul'] = "Site Specification";
		$data['content_id'] = "site_specification_view";
		$this->theme->view("site_specification_view", $data);
	}
	
	public function deleteData(){
		$this->msite_specification->deleteData();
	}
	
	public function loadData(){
		$this->msite_specification->loadData();
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
			$getDetail = $this->msite_specification->getsite_specificationDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-site_specification", $data);
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
				$hasil .= "Please Input Site Specification Name \n";
			}
			
			if( $order == "" ){
				$hasil .= "Please Input Order Value \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->msite_specification->saveData();
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
		$data['content_id'] = "site_specification/f-group-role";
		
		$data['group'] = $this->msite_specification->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->msite_specification->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */