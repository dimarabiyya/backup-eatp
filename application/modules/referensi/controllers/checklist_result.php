<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class checklist_result extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mchecklist_result');
	}
	
	########################################################################
	//Manag.checklist_result
	public function index(){
		$data['judul'] = "Visit Summary";
		$data['content_id'] = "checklist_result_view";
		$this->theme->view("checklist_result_view", $data);
	}
	
	public function deleteData(){
		$this->mchecklist_result->deleteData();
	}
	
	public function loadData(){
		$this->mchecklist_result->loadData();
	}
	public function loadData2(){
		$this->mchecklist_result->loadData2();
	}
	public function detildata(){
		
		//$data['id'] = $this->uri->segment(4);
		$data['id'] = $this->input->post('id');
		$this->mchecklist_result->getchecklist_resultDetail( $data['id'] );
	}
	function listVisit($status){
    $dt = $this->input->post();
    $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
    $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
    $dt['offset'] = ($dt['page']-1)*$dt['rows'];
	$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
    $data['q'] = strtolower($q);
	
    $d=$this->input->get();
    $q=$this->mchecklist_result->getListVisit($status,$data);
		$result["total"] = $q->num_rows();    
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
      $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'pm_id';
      $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

      $offset = ($page-1)*$rows;
    $d=$this->input->get();
	
    $qs=$this->mchecklist_result->getListDetails($this->input->get('pm_id'));
	$result["total"] = $qs->num_rows();    
    $q=$this->mchecklist_result->getListDetails($this->input->get('pm_id'),$offset,$rows);
	
		$filenotfound = base_url().'file/upload/not-found.png';	
		$dataempty = base_url().'file/upload/dataempty.jpg';	
		$items = array();
			foreach ($q->result() as $row) {
				if($row->photo_before==""){
					$row->photo_before="<center><img width='50px' src='".$dataempty."' alt='Album'/></center>"; 
				}else{					
					$filebefore= base_url().'file/upload/'.$row->photo_before;
					$patch = FCPATH.'file/upload/'.$row->photo_before;
						if (file_exists($patch)) {					
							$row->photo_before="<center><a class='group' href='".$filebefore."'><img width='50px' src='".$filebefore."' alt='Album'/></a></center>";
						}else{
								$row->photo_before="<center><img width='50px' src='".$filenotfound."' alt='Album'/></center>"; 
						}
				}
				if($row->photo_after==""){
					$row->photo_after="<center><img width='50px' src='".$dataempty."' alt='Album'/></center>"; 
				}else{					
					$fileafter= base_url().'file/upload/'.$row->photo_after;
					$patchafter = FCPATH.'file/upload/'.$row->photo_after;
					if (file_exists($patchafter)) {	
						$row->photo_after="<center><img width='50px' src='".$fileafter."' alt='Album'/></center>";
					}else{
						$row->photo_after="<center><img width='50px' src='".$filenotfound."' alt='Album'/></center>"; 
					}
				}
	            array_push($items, $row);
			}    
			$result["rows"] = $items;
	      echo json_encode($result);
		
    }
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id', 'descr');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($descr == "" ){
			$hasil = "Terdapat kesalahan: \n";
			
			if( $descr == "" ){
				$hasil .= "Nama checklist_result harus diisi \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mchecklist_result->saveData();
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
		$data['content_id'] = "checklist_result/f-group-role";
		
		$data['group'] = $this->mchecklist_result->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mchecklist_result->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */