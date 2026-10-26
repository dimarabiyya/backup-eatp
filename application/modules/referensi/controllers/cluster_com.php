<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class cluster_com extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mcluster_com');
	}
	
	########################################################################
	//Manag.cluster_com
	public function index(){
		$data['judul'] = "Cluster Commercial";
		$data['content_id'] = "cluster_com_view";
		$this->theme->view("cluster_com_view", $data);
	}
	
	public function deleteData(){
		$this->mcluster_com->deleteData();
	}
	
	public function loadData(){
		$this->mcluster_com->loadData();
	}
	
	function listVisit(){
    $dt = $this->input->post();
    $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
    $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
    $dt['offset'] = ($dt['page']-1)*$dt['rows'];
	$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
    $data['q'] = strtolower($q);
	
    $d=$this->input->get();
    $qs=$this->mcluster_com->getListVisits($data);
		$result["total"] = $qs->num_rows();  

    $q=$this->mcluster_com->getListVisit($dt['offset'],$dt['rows'],$data);		
		$items = array();
			foreach ($q->result() as $row) {
				
				$row->action='<a onclick="Document.editData('.$row->cluster_com_id.')" title="Edit" class="btn-action glyphicons pencil btn-info" ><i></i></a><a onclick="Document.deleteData('.$row->cluster_com_id.')" title="Delete" class="btn-action glyphicons remove_2 btn-danger" ><i></i></a>';
				
	            array_push($items, $row);
			}    
			$result["rows"] = $items;
	      echo json_encode($result);
    }
	
	
	public function addeditdata(){
		$input = array('cluster_com_id','cluster_code','cluster_name','region_id');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Cluster Commercial";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Cluster Commercial";
			$getDetail = $this->mcluster_com->getcluster_comDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}
		}	
		echo $this->load->view("r-cluster_com", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','cluster_code','cluster_name','region_id');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($cluster_code == "" || $cluster_name == "" || $region_id == ""){
			$hasil = "Found Error: \n";
			
			if( $cluster_code == "" ){
				$hasil .= "Please Input Cluster Code \n";
			}
			
			if( $cluster_name == "" ){
				$hasil .= "Please Input Cluster Name \n";
			}
			
			if( $region_id == "" ){
				$hasil .= "Please Input Region Name \n";
			}
			
		} elseif ( $cluster_code != '' && $this->core->view_row('t_mtr_cluster_com', array('UPPER(cluster_code)' => strtoupper($cluster_code), 'status' => 1, 'cluster_com_id !='=> (int) $id)) ) {
			$hasil .= "Cluster Code has already in used \n";
			
		} elseif ( $cluster_name != '' && $this->core->view_row('t_mtr_cluster_com', array('UPPER(cluster_name)' => strtoupper($cluster_name), 'status' => 1, 'cluster_com_id !='=> (int) $id)) ) {
			$hasil .= "Cluster Name has already in used \n";
				
			}else{
			//simpan data ke sini
			$status = "OK";
			$this->mcluster_com->saveData();
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
		$data['content_id'] = "cluster_com/f-group-role";
		
		$data['group'] = $this->mcluster_com->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mcluster_com->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */