<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kecamatan extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mkecamatan');
	}
	
	########################################################################
	//Manag.kabupaten
	public function index(){
		$data['judul'] = "Kecamatan";
		$data['content_id'] = "kabupaten_view";
		$this->theme->view("kecamatan_view", $data);
	}
	
	public function deleteData(){
		$this->mkecamatan->deleteData();
	}
	
	public function loadData(){
		$this->mkecamatan->loadData();
	}
	
	public function addeditdata(){
		//$input = array('lokasi_id','nama','child_id','parent_id');
		$input = array('lokasi_id','parent_id','kecamatan','kabupaten');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mkecamatan->getkabupatenDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	//print_rr($data);
		
		$id = $this->input->post('id');
		
		$data['kecamatan'] = $this->core->view_row('t_mtr_lokasi', array('lokasi_id' => $id));
		$data['kabupaten'] = $this->core->view_row('t_mtr_lokasi', array('lokasi_id' => $data['kecamatan']['parent_id']));
		$data['propinsi'] = $this->core->view_row('t_mtr_lokasi', array('lokasi_id' => $data['kabupaten']['parent_id']));
		
		echo $this->load->view("r-kecamatan", $data);
	}
	
	public function saveData__(){
		//lakukan validasi di sini
		$input = array('id', 'descr','province');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($descr == "" || $province == "") {
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
	
	public function saveData()
	{
		if ($this->core->is_ajax()) {
		
			$post = $this->core->get_post();
			$json = array();
			$error = array();
			
			if ($post['province'] == 0) {
				$error[] = 'Please choose Province.';
			}
			if ($post['kabupaten'] == 0) {
				$error[] = 'Please choose Kabupaten.';
			}
			if ($post['kecamatan'] == '') {
				$error[] = 'Kecamatan can not empty.';
			}
			
			if (count($error) == 0) {
				
				$date = date('Y-m-d H:i:s');
				$by = $this->session->userdata('user_id');
				
				$this->core->set['parent_id'] = $post['kabupaten'];
				$this->core->set['jenis'] = 'KC';
				$this->core->set['nama'] = $post['kecamatan'];
				$this->core->set['status'] = 1;
				$this->core->set['created_date'] = $date;
				$this->core->set['created_by'] = $by;
				$this->core->set['updated_date'] = $date;
				$this->core->set['updated_by'] = $by;
				
				if ($post['id'] != '') {
					$this->core->save('t_mtr_lokasi', array('lokasi_id' => $post['id']));
				} else {
					$this->core->save('t_mtr_lokasi');
				}
				
				$json = array('status' => 'OK', 'hasil' => 'Kecamatan data has been added successfully.');
			} else {
				$json = array('status' => 'ERROR', 'hasil' => $error);
			}
			
			echo json_encode($json);
			exit;
		}
	}
	########################################################################
	#######################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */