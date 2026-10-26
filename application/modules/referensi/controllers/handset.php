<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class handset extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mhandset');
	}
	
	########################################################################
	//Manag.handset
	public function index(){
		$data['judul'] = "Manage Handset";
		$data['content_id'] = "handset_view";
		$this->theme->view("handset_view", $data);
	}
	
	public function deleteData(){
		$this->mhandset->deleteData();
	}
	
	public function searchUser(){
		
		$data['q'] = $this->input->post('q');
		$this->mhandset->getUser($data['q']);
	}
	
	public function loadData(){
		$this->mhandset->loadData();
	}
	
	public function addeditdata(){
		$input = array('handset_id','handset_model','handset_man','user_id','imei','imsi','msisdn');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mhandset->gethandsetDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		$this->load->view('r-handset', $data, FALSE);//$this->theme->view("r-handset", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id', 'handset_model','handset_man','user_id','imei','msisdn');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($handset_model == "" || $handset_man == "" || $user_id == "" || $imei == "" || $msisdn == "" ){
			$hasil = "Found Error: \n";
			
			if( $handset_model == "" ){
				$hasil .= "Please Input Handset Model \n";
			}
			
			if( $handset_man == "" ){
				$hasil .= "Please Input Handset Man \n";
			}
			
			if( $user_id == "" ){
				$hasil .= "Please Choose User ID \n";
			}
			
			if( $imei == "" ){
				$hasil .= "Please Input IMEI \n";
			}
			
			// if( $imsi == "" ){
			// 	$hasil .= "Please Input IMSI \n";
			// }
			
			if( $msisdn == "" ){
				$hasil .= "Please Input MSISDN \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mhandset->saveData();
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
		$data['content_id'] = "handset/f-group-role";
		
		$data['group'] = $this->mhandset->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mhandset->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */