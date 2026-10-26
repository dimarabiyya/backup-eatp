<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menu extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
	}
	
	########################################################################
	//Manag.Menu
	public function index(){
		$url = $this->uri->segment(2) != '' ? $url = $this->uri->segment(1).'/'.$this->uri->segment(2) : $this->uri->segment(1) ;
		$menu = $this->mmenu->getMenuId($url);
		if ($this->mmenu->cekPermission($menu, 'menu_view' )==false){
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$data['judul'] = "Data Master Menu";
		$data['group_id']=1;
		$data['content_id'] = "menu/f-menu";
		$this->load->view("template/smashing", $data);
	}
	
	public function testTree(){
		echo($this->mmenu->getMenuTree(0));
	}
	
	public function DataMasterMenu(){
		$this->index();
	}
	public function deleteData(){
		$this->mmenu->deleteData();
	}
	
	public function loadData(){
		$this->mmenu->loadData();
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
			$getDetail = $this->mmenu->getMenuDetail( $data['id'] );
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
		$input = array('parent', 'nama', 'url', 'order', 'status');
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
			$this->mmenu->saveData();
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		return;
	}
	########################################################################
	//Manag.GroupRole
	public function GroupRole( $group_id="",$reload_element="" ){
		$url = $this->uri->segment(2) != '' ? $url = $this->uri->segment(1).'/'.$this->uri->segment(2) : $this->uri->segment(1) ;
		$menu = $this->mmenu->getMenuId($url);
		if ($this->mmenu->cekPermission($menu, 'menu_view' )==false){
			$data['content_id'] = "error/forbidden";
			$this->load->view('template/smashing', $data);
			return;
		}
		
		$data['judul'] = "Role Access Management";
		$data['content_id'] = "menu/f-group-role";
		
		$data['group'] = $this->mmenu->getGroup();
		$data['group_id'] = $group_id;
		$data['reload_element'] = $reload_element;
		$this->load->view("template/smashing", $data);
		
		if($reload_element <> ""){
			$this->session->set_flashdata('msg', 'Form Element is updated');
			redirect(base_url()."menu/GroupRole/$group_id");
		}
		
	}
	
	public function saveGroupRole(){
		$this->mmenu->saveGroupRole();
	}
	
	public function reloadFormElement(){
		$input = array('alias', 'form_id', 'group_id');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$frm = $this->mmenu->getElementFromURL($alias, $form_id, $group_id);
		$pesan = "Tidak Ada Element Form Baru.";
		if($frm >0) $pesan = "Element Form Ditemukan.";
		$hasil = $this->load->view('menu/f-form-role',array('form_id'=>$form_id,'group_id'=>$group_id), TRUE);
		echo json_encode(array(
			'status'=> "OK",
			'hasil'=>$hasil,
			'pesan'=>$pesan
		));
		return;
	}
	
	########################################################################
	//Manag.GroupRole
	public function FormRole( $group_id="" ){
		$data['judul'] = "Form Role";
		$data['content_id'] = "menu/f-form-role";
		
		$data['group'] = $this->mmenu->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function getElementFromURL($url="", $menu_id="", $group=""){
		$this->mmenu->getElementFromURL($url, $menu_id, $group);
		return $this->mmenu->getElement();
	}
	
	########################################################################
	//Manag.GroupRole
	public function manage_element_form(){
		$hasil = $this->load->view('menu/f-manage-form-element', TRUE);
		echo $hasil;
	}
	
	public function delete_element_form(){
		$this->mmenu->deleteElementForm();
		
		$fform = $this->input->post('form_id');
		$fgroup = $this->input->post('group_id');
		$menu_id = $this->input->post('menu_id');
		
		$status = "OK";
			$elements = $this->mmenu->getElementForm($menu_id, $fgroup);
			$forms = $this->load->view('menu/f-form-role',array('element'=>$elements), TRUE);
			$hasil ='<a class="form_element_btn">[Hide form element]</a>
							<div class="form_element_content" style="margin-top: 10px;display:block">'.$forms.'</div>';
		$pesan = "Element Berhasil Dihapus";
			
		echo json_encode(array(
			'status'=> 'OK',
			'hasil'=>$hasil,
			'pesan'=>$pesan,
			'form_id'=>$fform
		));
		return;
	}
	
	public function save_manage_element_form(){
		
		$input = array('fform', 'fgroup', 'fname','frole');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$pesan = ""; $hasil ="";
		if($fname == ""){
			$status = "ERROR";
			$pesan = "Nama Element harus diisi";
		}else{
			$status = "OK";
			$this->mmenu->saveElementFormLangsung($fform, $fname, $fgroup, $frole);
			
			$elements = $this->mmenu->getElementForm($fform, $fgroup);
			$forms = $this->load->view('menu/f-form-role',array('element'=>$elements), TRUE);
			$hasil ='<a class="form_element_btn">[Hide form element]</a>
							<div class="form_element_content" style="margin-top: 10px;display:block">'.$forms.'</div>';
			$pesan = "Element Berhasil Disimpan";
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>$hasil,
			'pesan'=>$pesan,
			'form_id'=>$fform
		));
		return;
	}
	
	public function EditMenu(){
		
		$getData = $this->mmenu->loadDataMenuByID( $this->input->post('id') );
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */