<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class site_area_code extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('msite_area_code');
	}
	
	########################################################################
	//Manag.site_area_code
	public function index(){
		$data['judul'] = "SF Area Code";
		$data['content_id'] = "site_area_code_view";
		$this->theme->view("site_area_code_view", $data);
	}
	
	public function deleteData(){
		$this->msite_area_code->deleteData();
	}
	
	public function loadData(){
		$this->msite_area_code->loadData();
	}
	
	public function addeditdata(){
		$input = array('area_id','area_code','description');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Area Code";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Area Code";
			$getDetail = $this->msite_area_code->getsite_area_codeDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-site_area_code", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','area_code','description');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($area_code == "" || $description == "" ){
			$hasil = "Found Error: \n";
			
			if($area_code == "" ){
				$hasil .= "Please Input Area Code \n";
			}
			
			if($description == "" ){
				$hasil .= "please Input Description \n";
			}
			
		} elseif ( $area_code != '' && $this->core->view_row('t_mtr_site_area_code', array('UPPER(area_code)' => strtoupper($area_code), 'active' => 1, 'area_id !='=> (int) $id)) ) {
			$hasil .= "Area Code has already in used \n";
			
		} elseif ( $description != '' && $this->core->view_row('t_mtr_site_area_code', array('UPPER(description)' => strtoupper($description), 'active' => 1, 'area_id !='=> (int) $id)) ) {
			$hasil .= "Description has already in used \n";
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->msite_area_code->saveData();
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
		$data['content_id'] = "site_area_code/f-group-role";
		
		$data['group'] = $this->msite_area_code->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->msite_area_code->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */