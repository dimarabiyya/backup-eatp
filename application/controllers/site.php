<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Site extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
                $this->load->model('msite');
	}
	
	########################################################################
	//Manag.Menu
	public function index(){
		$data['judul'] = "Site Info";
		$data['content_id'] = "site/f-site-info";
		$this->load->view("template/smashing", $data);
	}
	
        public function SiteInfo(){
            $this->index();
        }
        
	public function deleteData(){
		$this->msite->deleteData();
	}
	
	public function loadData(){
		$this->msite->loadData();
	}
	
	public function addeditdata(){
		$input = array('id','name','parent_id','alias');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Page";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Page";
			$getDetail = $this->msite->getMenuDetail( $data['id'] );
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
		$input = array('parent', 'nama', 'url');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($parent == "" || $nama == "" || $url == ""){
			$hasil = "Terdapat kesalahan: \n";
			if($parent == "" ){
				$hasil .= "Parent harus diisi \n";
			}
			if( $nama == "" ){
				$hasil .= "Nama Menu harus diisi \n";
			}
			if( $url == "" ){
				$hasil .= "URL harus diisi \n";
			}
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->msite->saveData();
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
		$data['content_id'] = "menu/f-group-role";
		
		$data['group'] = $this->msite->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->msite->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */