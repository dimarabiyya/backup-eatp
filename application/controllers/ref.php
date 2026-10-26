<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ref extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mreferensi');
	}
	
	########################################################################
	//Manag.Menu
	public function index(){
		$data['judul'] = "Referensi - Region";
		$data['content_id'] = "referensi/f-region";
		$this->load->view("template/smashing", $data);
	}
	
	public function deleteData(){
		$this->mreferensi->deleteData();
	}
	
	public function loadData(){
		$this->mreferensi->loadData();
	}
	
	public function addeditdata(){
		$input = array('region_id','region_name','status');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Page";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Page";
			$getDetail = $this->mreferensi->getDataDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("referensi/r-region", $data ,TRUE);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('region', 'pic', 'status');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$status = "ERROR";
		$hasil = "";
		if($region == "" || $pic == "" || $status == ""){
			$hasil = "Terdapat kesalahan: \n";
			if($region == "" ){
				$hasil .= "Nama Region harus diisi \n";
			}
			if( $pic == "" ){
				$hasil .= "PIC harus diisi \n";
			}
			if( $status == "" ){
				$hasil .= "Status harus diisi \n";
			}
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mreferensi->saveData();
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		return;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */