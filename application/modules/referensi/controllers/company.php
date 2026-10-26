<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class company extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mcompany');
	}
	
	########################################################################
	//Manag.company
	public function index(){
		$data['judul'] = "TP Company";
		$data['content_id'] = "company_view";
//		$this->theme->view("company_view", $data);
        $this->load->view('template/smashing', $data);

	}
	
	public function deleteData(){
		$this->mcompany->deleteData();
	}
	
	public function loadData(){
		$this->mcompany->loadData();
	}
	
	function listVisit(){
    $dt = $this->input->post();
    $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
    $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
    $dt['offset'] = ($dt['page']-1)*$dt['rows'];
	$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
    $data['q'] = strtolower($q);
	
    $d=$this->input->get();
    $qs=$this->mcompany->getListVisits($data);
		$result["total"] = $qs->num_rows();  

    $q=$this->mcompany->getListVisit($dt['offset'],$dt['rows'],$data);		
		$items = array();
			foreach ($q->result() as $row) {
				
				$row->action='<a onclick="Document.editData('.$row->company_id.')" title="Edit" class="btn-action glyphicons pencil btn-info" ><i></i></a><a onclick="Document.deleteData('.$row->company_id.')" title="Delete" class="btn-action glyphicons remove_2 btn-danger" ><i></i></a>';
				
	            array_push($items, $row);
			}    
			$result["rows"] = $items;
	      echo json_encode($result);
    }
	
	
	public function addeditdata(){
		$input = array('company_id','company_name','tpgroup_id','contact_name','contact_phone','commercial_rank','technical_rank','support_rank','remark');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add TP Company";
		if($data['id'] <> "") {
			$data['judul'] = "Edit TP Company";
			$getDetail = $this->mcompany->getcompanyDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-company", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','company_name','tpgroup_id','contact_name','contact_phone','commercial_rank','technical_rank','support_rank');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($company_name == ""){
			$hasil = "Found Error: \n";

			if( $company_name == "" ){
				$hasil .= "Please Input Company Name \n";
			}
			
		} elseif ( $company_name != '' && $this->core->view_row('t_mtr_tp_company', array('UPPER(company_name)' => strtoupper($company_name), 'company_id !='=> (int) $id)) ) {
			$hasil .= "Company Name has already in used \n";
				
			}else{
			//simpan data ke sini
			$status = "OK";
			$this->mcompany->saveData();
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
		$data['content_id'] = "company/f-group-role";
		
		$data['group'] = $this->mcompany->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mcompany->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */