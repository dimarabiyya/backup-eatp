<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class pm_checklist_property_option extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpm_checklist_property_option');
	}
	
	########################################################################
	//Manag.pm_checklist_property_option
	public function index(){
		$data['judul'] = "PM Checklist Property Option";
		$data['content_id'] = "pm_checklist_property_option_view";
		$this->theme->view("pm_checklist_property_option_view", $data);
	}
	
	public function deleteData(){
		$this->mpm_checklist_property_option->deleteData();
	}
	
	public function loadData(){
		$this->mpm_checklist_property_option->loadData();
	}
	
	public function addeditdata(){
		$input = array('checklist_id','property_id','option','bobot','urutan','remarks','active');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mpm_checklist_property_option->getpm_checklist_property_optionDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-pm_checklist_property_option", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','property_id','option','bobot','urutan','remarks');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($property_id == "" || $option == "" || $bobot == "" || $urutan == "" || $remarks == "" ){
			$hasil = "Found Error: \n";
			if($property_id == "" ){
				$hasil .= "Please Input Property ID \n";
			}
			if( $option == "" ){
				$hasil .= "Please Input PM Checklist Property Option  \n";
			}
			if( $bobot == "" ){
				$hasil .= "Please Input Bobot \n";
			}
			if( $urutan == "" ){
				$hasil .= "Please Input Order \n";
			}
			if( $remarks == "" ){
				$hasil .= "Please Input Remarks \n";
			}
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mpm_checklist_property_option->saveData();
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
		$data['content_id'] = "pm_checklist_property_option/f-group-role";
		
		$data['group'] = $this->mpm_checklist_property_option->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mpm_checklist_property_option->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */