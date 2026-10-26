<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class g_company extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mg_company');
	}
	
	########################################################################
	//Manag.g_company
	public function index(){
		$data['judul'] = "TP Company Group";
		$data['content_id'] = "g_company_view";
//		$this->theme->view("g_company_view", $data);
       $this->load->view('template/smashing', $data);
	}
	
	public function deleteData(){
		$this->mg_company->deleteData();
	}
	
	public function loadData(){
		$this->mg_company->loadData();
	}
	
	function listVisit(){
    $dt = $this->input->post();
    $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
    $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
    $dt['offset'] = ($dt['page']-1)*$dt['rows'];
	$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
    $data['q'] = strtolower($q);
	
    $d=$this->input->get();
    $qs=$this->mg_company->getListVisits($data);
		$result["total"] = $qs->num_rows();  

    $q=$this->mg_company->getListVisit($dt['offset'],$dt['rows'],$data);		
		$items = array();
			foreach ($q->result() as $row) {
				
				$row->action='<a onclick="Document.editData('.$row->tpgroup_id.')" title="Edit" class="btn-action glyphicons pencil btn-info" ><i></i></a><a onclick="Document.deleteData('.$row->tpgroup_id.')" title="Delete" class="btn-action glyphicons remove_2 btn-danger" ><i></i></a>';
				
	            array_push($items, $row);
			}    
			$result["rows"] = $items;
	      echo json_encode($result);
    }
	
	
	public function addeditdata(){
		$input = array('tpgroup_id','tpgroup_name','tpgroup_description');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add TP Company Group";
		if($data['id'] <> "") {
			$data['judul'] = "Edit TP Company Group";
			$getDetail = $this->mg_company->getg_companyDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-g_company", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','tpgroup_name','tpgroup_description');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($tpgroup_name == "" || $tpgroup_description == "" ){
			$hasil = "Found Error: \n";
			
			if( $tpgroup_name == "" ){
				$hasil .= "Please Input TP Group Name \n";
			}
			
			if( $tpgroup_description == "" ){
				$hasil .= "Please Input TP Group Description \n";
			}
			
		} elseif ( $tpgroup_name != '' && $this->core->view_row('t_mtr_tp_company_group', array('UPPER(tpgroup_name)' => strtoupper($tpgroup_name), 'tpgroup_id !='=> (int) $id)) ) {
			$hasil .= "TP Group Name has already in used \n";
			
		} elseif ( $tpgroup_description != '' && $this->core->view_row('t_mtr_tp_company_group', array('UPPER(tpgroup_description)' => strtoupper($tpgroup_description), 'tpgroup_id !='=> (int) $id)) ) {
			$hasil .= "TP Group Description has already in used \n";
				
			}else{
			//simpan data ke sini
			$status = "OK";
			$this->mg_company->saveData();
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
		$data['content_id'] = "g_company/f-group-role";
		
		$data['group'] = $this->mg_company->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mg_company->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */