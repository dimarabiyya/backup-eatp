<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Propinsi extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpropinsi');
	}
	
	########################################################################
	//Manag.propinsi
	public function index(){
		$data['judul'] = "Province";
		$data['content_id'] = "wilayah_view";
		$this->theme->view("wilayah_view", $data);
	}
	
	public function deleteData(){
		$this->mpropinsi->deleteData();
	}
	
	public function loadData(){
		$this->mpropinsi->loadData();
	}
	
	public function addeditdata(){
		$input = array('lokasi_id','nama');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mpropinsi->getpropinsiDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-propinsi", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id', 'descr');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($descr == "" ){
			$hasil = "Found Error: \n";
			
			if( $descr == "" ){
				$hasil .= "Please Input Province Name \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mpropinsi->saveData();
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
		$data['content_id'] = "propinsi/f-group-role";
		
		$data['group'] = $this->mpropinsi->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mpropinsi->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */