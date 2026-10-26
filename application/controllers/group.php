<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('mgroup');
		$this->mglobal->is_logged_in();
	}
	
	//Manag.
	public function index(){
		$this->GroupManagement();
	}
	
	public function GroupManagement( ){
		
		$url = $this->uri->segment(2) != '' ? $url = $this->uri->segment(1).'/'.$this->uri->segment(2) : $this->uri->segment(1) ;
		$menu = $this->mmenu->getMenuId($url);
		if ($this->mmenu->cekPermission($menu, 'menu_view' )==false){
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$data['judul'] = "Role Management";
		$data['content_id'] = "group/f-group";
		$data['area_restriction'] = $this->mglobal->lookup('area_restriction');
		//$data['user_group_data'] = $this->mgroup->get_territory_name();
		$data['parent_group_data'] = $this->mgroup->get_parent();
		
		$this->load->view("template/smashing", $data);
	}
	
	public function EditGroup(){
		
		$getData = $this->mgroup->loadDataGroupByID( $this->input->post('id') );
	}
	
	public function deleteData(){
		$this->mgroup->deleteData();
		echo json_encode(array(
			'status'=> 'OK'
		));
		return;
	}
	
	public function loadData(){
		$items = array();
		$d=$this->input->post();
		
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'user_group_name';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1)*$rows;
		  
		$q=$this->mgroup->loadData($d,$sort,$order);
		$result["total"] = $q->num_rows();    
		$qr=$this->mgroup->loadData($d,$sort,$order,$rows,$offset);    
	    
		foreach ($qr->result() as $row) {
		  $row->status = ($row->status==1) ? 'Active' : 'Not Active' ;
		  $modulEdit = "doEdit('".$row->user_group_id."')";
		  $modulDelete = "doDelete('".$row->user_group_id."')";
		  $row->action=
			  '<a title="Edit" group_id="'.$row->user_group_id.'" href="#" class="btn-action glyphicons pencil btn-success" onclick="'.$modulEdit.'"><i></i></a>
			   <a title="Delete" href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="'.$modulDelete.'"><i></i></a>
			   ';
		  
		  array_push($items, $row);
		}    
		$result["rows"] = $items;
		echo json_encode($result);
	}
	
	public function addeditdata(){
		$input = array('id','name','parent_id','alias');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Page";
		if($data['id'] <> "") {
			$data['judul'] = "Edit User";
			$getDetail = $this->mgroup->getDetailData( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("menu/r-menu", $data ,TRUE);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('nama', 'caption','having_stock','is_use_top','area_restriction');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($nama == "" || $caption == "" ){
			$hasil = "Terdapat kesalahan: \n";
			if( $nama == "" ){
				$hasil .= "Role Name is required \n";
			}
			if( $caption == "" ){
				$hasil .= "Description is required \n";
			}
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mgroup->saveData();
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		return;
	}
	########################################################################
	//manage Division
	public function DivManagement( ){
		
		$url = $this->uri->segment(2) != '' ? $url = $this->uri->segment(1).'/'.$this->uri->segment(2) : $this->uri->segment(1) ;
		$menu = $this->mmenu->getMenuId($url);
		if ($this->mmenu->cekPermission($menu, 'menu_view' )==false){
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$data['judul'] = "Group Management";
		$data['content_id'] = "group/f-division";
		
		$this->load->view("template/smashing", $data);
	}
	
	public function deleteDataDiv(){
		$this->mgroup->deleteDataDiv();
	}
	
	public function EditDiv(){
		
		$getData = $this->mgroup->loadDataDivByID( $this->input->post('id') );
	}
	
	public function loadDataDiv(){ 
		$items = array();
		$d=$this->input->post();
		
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'user_div_name';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1)*$rows;
		  
		$q=$this->mgroup->loadDataDiv($d,$sort,$order);
		$result["total"] = $q->num_rows();    
		$qr=$this->mgroup->loadDataDiv($d,$sort,$order,$rows,$offset);    
	    
		foreach ($qr->result() as $row) {
		  $row->status = ($row->status==1) ? 'active' : 'not active' ;
		  $modulEdit = "doEdit('".$row->user_div_id."')";
		  $modulDelete = "doDelete('".$row->user_div_id."')";
		  $row->action=
			  '<a title="Edit" href="#" class="btn-action glyphicons pencil btn-success" onclick="'.$modulEdit.'"><i></i></a>
			   <a title="Delete" href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="'.$modulDelete.'"><i></i></a>
			   ';
		  
		  array_push($items, $row);
		}    
		$result["rows"] = $items;
		echo json_encode($result);
	}
	
	public function addeditdatadiv(){
		$input = array('id','name','parent_id','alias');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Page";
		if($data['id'] <> "") {
			$data['judul'] = "Edit User";
			$getDetail = $this->mgroup->getDetailData( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("menu/r-menu", $data ,TRUE);
	}
	
	public function saveDataDiv(){
		//lakukan validasi di sini
		$input = array('nama', 'caption', 'teritori', 'status','having_stock','is_use_top');  //'parent', 
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($nama == "" ){
			$hasil = "Terdapat kesalahan: \n";
			$hasil .= "Nama Group harus diisi \n";
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mgroup->saveDataDiv();
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		return;
	}
	 
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */