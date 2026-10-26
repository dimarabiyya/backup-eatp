<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class preparation_used extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpreparation_used');
	}
	
	########################################################################
	//Manag.preparation_used
	public function index(){
		$data['judul'] = "Preparation Used";
		$data['content_id'] = "preparation_used_view";
		$this->load->model('mglobal');
		//$this->theme->view("preparation_used_view", $data);
			
		// ubah lagi, untuk cluster tree jangan ambil dari session. tapi dari tabel t_mtr_cluster_mapping
		$userTerritory      = $this->mglobal->userTerritory($this->session->userdata('user_id'));
		$territory = array();
		$x   = 0;
		foreach ($userTerritory['region'] as $rr) {
		  $territory[$x] = $rr;
		  foreach ($userTerritory['cluster'] as $cc) {
			if ($cc['region_id'] == $rr['region_id'])
			  {
				$territory[$x]['cluster'][] = $cc;
			  }
		  }
		  $x++;
		}
		$data["territory"]  = $territory;
		$this->theme->view("preparation_used_view", $data);
	}
	
	public function deleteData(){
		$this->mpreparation_used->deleteData();
	}
	
	public function loadData(){
		$this->mpreparation_used->loadData();
	}
	function listVisit(){
    $dt = $this->input->post();
    $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
    $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
    $dt['offset'] = ($dt['page']-1)*$dt['rows'];
	$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
    $data['q'] = strtolower($q);
		$opr_year    = $this->input->get_post('opr_year');
		$opr_month   = $this->input->get_post('opr_month');
		$opr_cluster = $this->input->get_post('opr_cluster');
		
		if(!$opr_year)
		{ $opr_year = date('Y'); }
		if(!$opr_month)
		{ $opr_month = date('m'); }
		if(!$opr_cluster)
		{ $opr_cluster = $this->session->userdata('cluster_ops_id'); }
	
    $d=$this->input->get();
    $qs=$this->mpreparation_used->getListVisits($data,$q, $opr_year, $opr_month);
		$result["total"] = $qs->num_rows(); 
	$q=$this->mpreparation_used->getListVisit($dt['offset'],$dt['rows'],$q, $opr_year, $opr_month);

    //$q=$this->mpreparation_used->getListVisit($dt['offset'],$dt['rows']);		
		$items = array();
			foreach ($q->result() as $row) {
				
	            array_push($items, $row);
			}    
			$result["rows"] = $items;
	      echo json_encode($result);
    }
	
	function ListVisitDetail(){
	 $dt = $this->input->post();
     $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
      $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'prep_id';
      $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

      $offset = ($page-1)*$rows;
    $d=$this->input->get();
	
	
    $qs=$this->mpreparation_used->getListDetails($this->input->get('user'),$this->input->get('date'));
	$result["total"] = $qs->num_rows();    
    $q=$this->mpreparation_used->getListDetails($this->input->get('user'),$this->input->get('date'),$offset,$rows);
	
		$items = array();
			foreach ($q->result() as $row) {
				
	            array_push($items, $row);
			}    
			$result["rows"] = $items;
	      echo json_encode($result);
		  
    }
	public function addeditdata(){
		$input = array('pt_id','descr','type_id');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['date'] = $this->input->post('date');
		$data['judul'] = "Add";
		if($data['id'] <> "") {
			$data['judul'] = "Edit";
			$getDetail = $this->mpreparation_used->getpreparation_usedDetail( $data['id'],$data['date']  );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-preparation_used", $data);
	}
	// public function expand(){
		
		// $data['pmid'] = $this->input->post('pmid');
		// $this->mpreparation_used->getchecklist_resultDetailsas( $data['pmid'] );
	// }
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
				$hasil .= "Please Choose Type \n";
			}
			if( $descr == "" ){
				$hasil .= "Please Input Preparation Name \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mpreparation_used->saveData();
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
		$data['content_id'] = "preparation_used/f-group-role";
		
		$data['group'] = $this->mpreparation_used->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mpreparation_used->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */