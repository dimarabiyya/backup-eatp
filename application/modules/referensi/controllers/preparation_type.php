<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class preparation_type extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpreparation_type');
	}
	
	########################################################################
	//Manag.preparation_type
	public function index(){
		$data['judul'] = "Tools Group";
		$data['content_id'] = "preparation_type_view";
		$this->theme->view("preparation_type_view", $data);
	}
	
	public function deleteData(){
		$this->mpreparation_type->deleteData();
	}
	
	public function loadData(){
		$this->mpreparation_type->loadData();
	}
	
	public function addeditdata(){
		$input = array('preparation_type_id','descr','order_no');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Tools Group";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Tools Group";
			$getDetail = $this->mpreparation_type->getpreparation_typeDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-preparation_type", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id', 'descr', 'order_no');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($descr == "" || $order_no == ""){
			$hasil = "Found Error: \n";
			
			if( $descr == "" ){
				$hasil .= "Please Input Tools Name \n";
			}
			
			if( $order_no == "" ){
				$hasil .= "Please Input Order \n";
			}
			
			} elseif ( $descr != '' && $this->core->view_row('t_mtr_pm_preparation_type', array('UPPER(descr)' => strtoupper($descr), 'status' => 1, 'preparation_type_id !='=> (int) $id)) ) {
				$hasil .= "Tools Name has already in used \n";
			}else{
			//simpan data ke sini
			$status = "OK";
			$this->mpreparation_type->saveData();
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
		$data['content_id'] = "preparation_type/f-group-role";
		
		$data['group'] = $this->mpreparation_type->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mpreparation_type->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */