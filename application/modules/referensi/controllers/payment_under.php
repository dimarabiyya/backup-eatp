<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class payment_under extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpayment_under');
	}
	
	########################################################################
	//Manag.payment_under
	public function index(){
		$data['judul'] = "Payment Under";
		$data['content_id'] = "payment_under_view";
		$this->theme->view("payment_under_view", $data);
	}
	
	public function deleteData(){
		$this->mpayment_under->deleteData();
	}
	
	public function loadData(){
		$this->mpayment_under->loadData();
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
			$getDetail = $this->mpayment_under->getpayment_underDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-payment_under", $data);
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
				$hasil .= "Please Input Payment Under Name \n";
			}
			
			if( $order == "" ){
				$hasil .= "Please Input Order Value \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mpayment_under->saveData();
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
		$data['content_id'] = "payment_under/f-group-role";
		
		$data['group'] = $this->mpayment_under->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mpayment_under->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */