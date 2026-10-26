<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class preparation_item extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpreparation_item');
	}
	
	########################################################################
	//Manag.preparation_item
	public function index(){
		$data['judul'] = "Tools Item";
		$data['content_id'] = "preparation_item_view";
		$this->theme->view("preparation_item_view", $data);
	}
	
	public function deleteData(){
		$this->mpreparation_item->deleteData();
	}
	
	public function loadDataUT(){
		$dt = $this->input->post();
		$dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
		$dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
		$dt['sort'] = isset($dt['sort']) ? strval($dt['sort']) : 'tdescr';
		$dt['order'] = isset($dt['order']) ? strval($dt['order']) : 'ASC';
		$dt['offset'] = ($dt['page']-1)*$dt['rows'];
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
		$data['q'] = strtolower($q);
		
		$d=$this->input->get();
		$qs=$this->mpreparation_item->loadDataUT($data,$q);
			$result["total"] = $qs->num_rows();

		$q=$this->mpreparation_item->loadDataUTs($dt['sort'],$dt['order'],$dt['offset'],$dt['rows'],$q);			
			$items = array();
				foreach ($q->result() as $row) {
					array_push($items, $row);
				}    
				$result["rows"] = $items;
			  echo json_encode($result);
		
	}
	public function loadData(){
		$dt = $this->input->post();
		$dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
		$dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
		$dt['sort'] = isset($dt['sort']) ? strval($dt['sort']) : 'tdescr';
		$dt['order'] = isset($dt['order']) ? strval($dt['order']) : 'ASC';
		$dt['offset'] = ($dt['page']-1)*$dt['rows'];
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
		$data['q'] = strtolower($q);
		
		$d=$this->input->get();
		$qs=$this->mpreparation_item->loadData($q,$this->input->get('type'));
			$result["total"] = $qs->num_rows();

		$q=$this->mpreparation_item->loadDatas($dt['sort'],$dt['order'],$dt['offset'],$dt['rows'],$q,$this->input->get('type'));			
			$items = array();
				foreach ($q->result() as $row) {
				
					$row->action='<a href="#" class="btn-action glyphicons pencil btn-success" title="Edit" onclick="return Document.editData('.$row->pt_id.')"><i></i></a><a href="#" class="btn-action glyphicons remove_2 btn-danger" title="Delete" onclick="return Document.deleteData('.$row->pt_id.')"><i></i></a>';
					array_push($items, $row);
				}    
				$result["rows"] = $items;
			  echo json_encode($result);
		
	}
	
	/*
	public function loadData(){
		$dt = $this->input->post();
		$dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
		$dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
		$dt['offset'] = ($dt['page']-1)*$dt['rows'];
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
		$data['q'] = strtolower($q);
		
		$d=$this->input->get();
		$qs=$this->mpreparation_item->loadData($data);
			$result["total"] = $qs->num_rows();

		$q=$this->mpreparation_item->loadDatas($dt['offset'],$dt['rows']);			
			$items = array();
				foreach ($q->result() as $row) {
				
					$row->action='<a href="#" class="btn-action glyphicons pencil btn-success" onclick="return Document.editData('.$row->pt_id.')"><i></i></a><a href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData('.$row->pt_id.')"><i></i></a>';
					array_push($items, $row);
				}    
				$result["rows"] = $items;
			  echo json_encode($result);
		
	}
	*/
	public function addeditdata(){
		$input = array('pt_id','descr','type_id');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Tools Item";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Tools Item";
			$getDetail = $this->mpreparation_item->getpreparation_itemDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-preparation_item", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','type','descr');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($type == "" || $descr == "" ){
			$hasil = "Found Error: \n";
			if($type == "" ){
				$hasil = "Please Choose Type \n";
			}
			if( $descr == "" ){
				$hasil = "PLease Input Preparation Item Name \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mpreparation_item->saveData();
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
		$data['content_id'] = "preparation_item/f-group-role";
		
		$data['group'] = $this->mpreparation_item->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mpreparation_item->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */