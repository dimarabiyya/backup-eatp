<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class site_kandidat extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('msite_kandidat');
		$this->load->model('referensi_model');
		$this->load->model('map_model');
		$this->load->library('session');

	}
	
	########################################################################
	//Manag.site_kandidat
	public function index(){
		
		$site_id_map = 0;
		$site_id_map = $this->input->get('site_id_map');
		//die('site_id_map nya='.$site_id_map);
		$data['site_id_map'] = $site_id_map;
		$data['judul'] = "List Site Candidate";
		$data['content_id'] = "site_kandidat_view";
		$this->theme->view("site_kandidat_view", $data);
	}
	
	public function deleteData(){
		$this->msite_kandidat->deleteData();
	}
	
	public function loadData(){
		$this->msite_kandidat->loadData();
	}
	
	public function addeditdata(){
		$input = array('site_id','site_id_tp','status');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['site_id'] = $this->input->post('site_id');
		$data['judul'] = "Add Site Candidate";
		if($data['site_id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->msite_kandidat->getsite_typeDetail( $data['site_id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-site_kandidat", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('parent', 'site_id_tp','status', 'url');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($parent == "" || $url == ""){
			$hasil = "Found Error: \n";
			if($parent == "" ){
				$hasil .= "Please Input Parent \n";
			}
			if( $url == "" ){
				$hasil .= "Please Input URL \n";
			}
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->msite_kandidat->saveData();
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		return;
	}
	########################################################################
	//Manag.Dialog Kandidat
	//4dec13@dody
	public function dialogKandidat(){ 
		
		$status = $this->input->post('status');
		$hasil = "<table width=100%>
				<tr><td>Status: $status</td></tr>
				<tr><td>Remark: <textarea name='remark' id='remark'></textarea></td></tr>
			</table>";
		echo $hasil;
	}
	
	public function dialogKandidatNSF(){ 
		$d = $this->input->get();
		$hasil = "
			<div class='widget'>
			<div class='widget-head'><h4 class='heading'>".strtoupper($d['status'])."</h4></div><table width=100%>
			<div class='widget-body'>
				<div>
				Remark<br>
				<textarea name='remark' id='remark' style='width:480px; resize:none;'></textarea>
				</div>
				</div><div class='form-actions'>
						<button type='submit' onclick='savedata(".$d['site_id'].", ".$d['nomsite'].",\"".$d['status']."\")' class='btn btn-icon btn-primary glyphicons circle_ok'><i></i>Confirm</button>
						<button type='button' onclick='$.fancybox.close();' class='btn btn-icon btn-close btn-default glyphicons circle_remove'><i></i>Close</button>
				</div>
			</div>";
		echo $hasil;
	}
	
	public function saveKandidat(){ 
		$this->load->model('map_model');
		$input = array('nomsite_id', 'site_id','status');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$hasil = "";
		$update_kandidat = $this->map_model->update_site_kandidat();
		
		echo json_encode(array(
			'status_update'=> $update_kandidat
		));
		return;
	}
	########################################################################
	
	public function chooseKandidat(){
		$this->load->model('map_model');
		$input = array('nomsite_id', 'site_id','status');
		foreach ($input as $val)
			$$val = $this->input->post($val);
			
		$update_kandidat=$this->map_model->update_site_kandidat_menang_kalah();
		
		echo json_encode(array(
			'status_update'=> $update_kandidat
		));
		return;
	}
	
	##############################################################
	//dody@13dec12
	public function searchSite(){

		$data['judul'] = "Site List";
		$data['seq'] = $this->input->get('seq');
		$this->load->view("form_search_site_kandidat", $data);
		return;		
	}
	
	public function getAllSite(){
		$d=$this->input->post();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : '';
		$offset = ($page-1)*$rows;
		//$qr = $this->mproblem->getSite($q,$rows,$offset);
		$qr = $this->map_model->get_site_name_for_search( $q,$rows,$offset );
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */