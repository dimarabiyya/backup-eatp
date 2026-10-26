<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class referensiv2 extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mreferences');
	}
	
	########################################################################
	//Manag.partner
	public function index(){
		$data['judul'] = "List References";
		$data['content_id'] = "references_view";
		$this->theme->view("references_view", $data);
	}
	
	public function deleteData(){
		$this->mreferences->deleteData();
	}
	
	public function loadData(){
		$this->mreferences->loadData();
	}
	
	public function addeditdata(){
		$input = array('lookup_id','lookup_key','lookup_value','lookup_desc','order','active');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Reference";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Reference";
			$getDetail = $this->mreferences->getDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		//die("coba");
		echo $this->load->view("r-references", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('lookup_id', 'lookup_key','lookup_value','lookup_desc','order','active');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil  = "";
		
		if($lookup_value == "" || $lookup_desc == "" || $order == "" ){
			$hasil = "Error Found: \n";
			
			if($lookup_value == "" ){
				$hasil .= "Value harus diisi \n";
			}
			
			if( $lookup_desc == "" ){
				$hasil .= "Description harus diisi \n";
			}
			
			if( $order == "" ){
				$hasil .= "Order harus diisi \n";
			}
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mreferences->saveData();
		}
		
		//simpan data ke sini
			$status = "OK";
			$this->mreferences->saveData();
			
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
		$data['content_id'] = "referensiv2/f-group-role";
		
		$data['group'] = $this->mreferences->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mreferences->saveGroupRole();
	}
	
	/*
	function getCityy($id){
		 $tmp    = '';
        $data   = $this->mpartner->get_city_by_state($id);
        if(!empty($data)){
            $tmp .= "<option value=''>Pilih Kota / Kabupaten</option>";
            foreach($data as $row) {   
                $tmp .= "<option value='".$row->lokasi_id."'>".$row->nama."</option>";
            }
        } else {
            $tmp .= "<option value=''>Pilih Kota / Kabupaten</option>";
        }
        die($tmp);
	}
	*/
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */