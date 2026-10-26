<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class tipe_perangkat_site extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mtipe_perangkat_site');
	}
	
	########################################################################
	//Manag.tipe_perangkat_site
	public function index(){
		$data['judul'] = "Daftar Type Perangkat Site";
		$data['content_id'] = "tipe_perangkat_site_view";
		$this->theme->view("tipe_perangkat_site_view", $data);
	}
	
	public function deleteData(){
		$this->mtipe_perangkat_site->deleteData();
	}
	
	public function loadData(){
		$this->mtipe_perangkat_site->loadData();
	}
	
	public function addeditdata(){
		$input = array('perangkat_id','nama');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mtipe_perangkat_site->gettipe_perangkat_siteDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-tipe_perangkat_site", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id', 'nama');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($nama == "" ){
			$hasil = "Found Error: \n";
			
			if( $nama == "" ){
				$hasil .= "Please Input Tipe Perangkat Site Name \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mtipe_perangkat_site->saveData();
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
		$data['content_id'] = "tipe_perangkat_site/f-group-role";
		
		$data['group'] = $this->mtipe_perangkat_site->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mtipe_perangkat_site->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */