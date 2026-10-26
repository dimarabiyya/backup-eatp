<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class mr_reference extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mmr_reference');
	}
	
	########################################################################
	//Manag.mr_reference
	public function index(){
		$data['judul'] = "More Reference";
		$data['content_id'] = "mr_reference_view";
		$this->theme->view("mr_reference_view", $data);
	}
	
	public function deleteData(){
		$this->mmr_reference->deleteData();
	}
	
	public function loadData(){
		$this->mmr_reference->loadData();
	}
	
	function listVisit(){
    $dt = $this->input->post();
    $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
    $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
    $dt['offset'] = ($dt['page']-1)*$dt['rows'];
	$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
    $data['q'] = strtolower($q);
	
    $d=$this->input->get();
    $qs=$this->mmr_reference->getListVisits($data);
		$result["total"] = $qs->num_rows();  

    $q=$this->mmr_reference->getListVisit($dt['offset'],$dt['rows'],$data);		
		$items = array();
			foreach ($q->result() as $row) {
				
				$row->action='<a onclick="Document.editData('.$row->lookup_id.')" title="Edit" class="btn-action glyphicons pencil btn-info" ><i></i></a><a onclick="Document.deleteData('.$row->lookup_id.')" title="Delete" class="btn-action glyphicons remove_2 btn-danger" ><i></i></a>';
				
	            array_push($items, $row);
			}    
			$result["rows"] = $items;
	      echo json_encode($result);
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
			$getDetail = $this->mmr_reference->getmr_referenceDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-mr_reference", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id', 'lookup_key', 'lookup_value', 'lookup_desc', 'order', 'active');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($lookup_key == "" || $lookup_value == "" || $lookup_desc == "" || $order == "" || $active == "" ){
			$hasil = "Found Error: \n";
			
			if( $lookup_key == "" ){
				$hasil .= "Please Input Lookup Key \n";
			}
			
			if( $lookup_value == "" ){
				$hasil .= "Please Input Lookup Value \n";
			}
			
			if( $lookup_desc == "" ){
				$hasil .= "Please Input Lookup Description \n";
			}
			
			if( $order == "" ){
				$hasil .= "Please Input Order \n";
			}
			
			if( $active == "" ){
				$hasil .= "Please Input Active \n";
			}
			
			// } elseif ( $mr_referencetone_name != '' && $this->core->view_row('t_mtr_pmo_mr_referencetone', array('UPPER(mr_referencetone_name)' => strtoupper($mr_referencetone_name), 'status' => 1, 'mr_referencetone_id !='=> (int) $id)) ) {
				// $hasil .= "mr_referencetone Name has already in used \n";
				
			}else{
			//simpan data ke sini
			$status = "OK";
			$this->mmr_reference->saveData();
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
		$data['content_id'] = "mr_reference/f-group-role";
		
		$data['group'] = $this->mmr_reference->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mmr_reference->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */