<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class partner extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpartner');
	}
	
	########################################################################
	//Manag.partner
	public function index(){
		$data['judul'] = "Partner";

		$data['content_id'] = "partner_view";
		$this->theme->view("partner_view", $data);
	}
	
	public function deleteData(){
		$this->mpartner->deleteData();
	}
	
	public function loadData(){
		//$this->mpartner->loadData();
		$dt = $this->input->post();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
		$data['q'] = strtolower($q);
	
		$qr=$this->mpartner->loadData($data,$q);
		$result["total"] = $qr->num_rows();
		$q=$this->mpartner->loadDatas($offset,$rows,$q);		
		$items = array();
			foreach ($q->result() as $row) {
				$row->action='<a href="#" onclick="return Document.editData('.$row->partner_id.')" title="Edit" class="btn-action glyphicons pencil btn-success" ><i></i></a>
							  <a href="#" onclick="return Document.deleteData('.$row->partner_id.')" title="Delete" class="btn-action glyphicons remove_2 btn-danger" ><i></i></a>';
	            array_push($items, $row);
			}    
			$result["rows"] = $items;
	    echo json_encode($result);
	}
	
	public function addeditdata(){
		$input = array('partner_id','partner_code','partner_name','gis_marker','city_id','province_id','partner_type');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Page";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Page";
			$getDetail = $this->mpartner->getpartnerDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-partner", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id', 'partner_code','partner_name','gis_marker','city_id','province_id','partner_type');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($partner_code == "" || $partner_name == "" ){
			$hasil = "Found Error: \n";
			if($partner_code == "" ){
				$hasil .= "Please Input Parent \n";
			}
			if( $partner_name == "" ){
				$hasil .= "Please Input Partner Name \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mpartner->saveData();
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
		$data['content_id'] = "partner/f-group-role";
		
		$data['group'] = $this->mpartner->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mpartner->saveGroupRole();
	}
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
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */