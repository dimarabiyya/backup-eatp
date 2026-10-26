<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ins_checklist_group extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mins_checklist_group');
	}
	
	########################################################################
	//Manag.ins_checklist_group
	public function index(){
		$data['judul'] = "Daftar INS Checklist Group";
		$data['content_id'] = "ins_checklist_group_view";
		$this->theme->view("ins_checklist_group_view", $data);
	}
	
	public function deleteData(){
		$this->mins_checklist_group->deleteData();
	}
	
	public function loadData(){
		$this->mins_checklist_group->loadData();
	}
	
	public function addeditdata(){
		$input = array('lookup_id','lookup_key','lookup_desc','order','active');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mins_checklist_group->getins_checklist_groupDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-ins_checklist_group", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','lookup_desc','order');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($lookup_desc == "" || $order == "" ){
			$hasil = "Found Error: \n";
			
			if($lookup_desc == "" ){
				$hasil = "Please Input Lookup Description \n";
			}
			if($order == "" ){
				$hasil = "Please Input Order \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mins_checklist_group->saveData();
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
		$data['content_id'] = "ins_checklist_group/f-group-role";
		
		$data['group'] = $this->mins_checklist_group->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mins_checklist_group->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */