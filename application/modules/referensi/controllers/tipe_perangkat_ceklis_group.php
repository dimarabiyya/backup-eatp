<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class tipe_perangkat_ceklis_group extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mtipe_perangkat_ceklis_group');
	}
	
	########################################################################
	//Manag.tipe_perangkat_ceklis_group
	public function index(){
		$data['judul'] = "Type Perangkat Ceklis Group";
		$data['content_id'] = "tipe_perangkat_ceklis_group_view";
		$this->theme->view("tipe_perangkat_ceklis_group_view", $data);
	}
	
	public function deleteData(){
		$this->mtipe_perangkat_ceklis_group->deleteData();
	}
	
	public function loadData(){
		$this->mtipe_perangkat_ceklis_group->loadData();
	}
	
	public function addeditdata(){
		$input = array('group_id','nama');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mtipe_perangkat_ceklis_group->gettipe_perangkat_ceklis_groupDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-tipe_perangkat_ceklis_group", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','type','nama');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($type == "" || $nama == "" ){
			$hasil = "Found Error: \n";
			if($type == "" ){
				$hasil .= "Please Choose Type \n";
			}
			if( $nama == "" ){
				$hasil .= "Please Input Tipe Perangkat Ceklis Grup \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mtipe_perangkat_ceklis_group->saveData();
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
		$data['content_id'] = "tipe_perangkat_ceklis_group/f-group-role";
		
		$data['group'] = $this->mtipe_perangkat_ceklis_group->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mtipe_perangkat_ceklis_group->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */