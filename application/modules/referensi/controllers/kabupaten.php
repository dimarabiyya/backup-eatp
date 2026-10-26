<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kabupaten extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mkabupaten');
	}
	
	########################################################################
	//Manag.kabupaten
	public function index(){
		$data['judul'] = "City";
		$data['content_id'] = "kabupaten_view";
		$this->theme->view("kabupaten_view", $data);
	}
	
	public function deleteData(){
		$this->mkabupaten->deleteData();
	}
	
	public function loadData(){
		$dt = $this->input->post();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
		$data['q'] = strtolower($q);
	
		$qr=$this->mkabupaten->loadData($data,$q);
		$result["total"] = $qr->num_rows();
		$q=$this->mkabupaten->loadDatas($offset,$rows,$q);		
		$items = array();
			foreach ($q->result() as $row) {
				// $row->action='<a href="#" onclick="return Document.editData('.$row->child_id.')" title="Edit" class="btn-action glyphicons pencil btn-success" ><i></i></a>
							// <a href="#" onclick="return Document.deleteData('.$row->child_id.')" title="Delete" class="btn-action glyphicons remove_2 btn-danger" ><i></i></a>';
	            array_push($items, $row);
			}    
			$result["rows"] = $items;
	    echo json_encode($result);
	}
	
	function ListChildDetail(){
		$dt = $this->input->post();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'child_id';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
		$data['q'] = strtolower($q);
		$offset = ($page-1)*$rows;
		$d=$this->input->get();
	
		$qs=$this->mkabupaten->getListDetails($this->input->get('child_id'),$q);
		$result["total"] = $qs->num_rows();    
		$q=$this->mkabupaten->getListDetails($this->input->get('child_id'),$q,$offset,$rows);
		
		$items = array();
		foreach ($q->result() as $r) {
			$r->action='<a href="#" onclick="return Document.editData('.$r->child_id.')" title="Edit" class="btn-action glyphicons pencil btn-success" ><i></i></a>
					    <a href="#" onclick="return Document.deleteData('.$r->child_id.')" title="Delete" class="btn-action glyphicons remove_2 btn-danger" ><i></i></a>';
			array_push($items, $r);
		}    
		$result["rows"] = $items;
		echo json_encode($result);
		
    }
	
	public function addeditdata(){
		$input = array('lokasi_id','nama','child_id','parent_id');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mkabupaten->getkabupatenDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-kabupaten", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id', 'descr','province');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($descr == "" || $province == "" ){
			$hasil = "Found Error: \n";
			
			if( $descr == "" ){
				$hasil .= "Please Input Kabupaten Name \n";
			}
			if( $province == "" ){
				$hasil .= "Please Input Province Name \n";
			}
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mkabupaten->saveData();
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		return;
	}
	########################################################################
	#######################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */