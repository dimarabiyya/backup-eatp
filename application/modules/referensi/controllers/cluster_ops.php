<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class cluster_ops extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mcluster_ops');
	}
	
	########################################################################
	//Manag.cluster_ops
	public function index(){
		$data['judul'] = "Cluster Operation";
		$data['content_id'] = "cluster_ops_view";
		$this->theme->view("cluster_ops_view", $data);
	}
	
	public function deleteData(){
		$this->mcluster_ops->deleteData();
	}
	
	public function loadData(){
		$this->mcluster_ops->loadData();
	}
	
	public function addeditdata(){
		$input = array('cluster_ops_id','cluster_code','cluster_name','region_id');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Cluster Operation";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Cluster Operation";
			$getDetail = $this->mcluster_ops->getcluster_opsDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = $r->$val;
				}
			}//print_r($getDetail);
		}	
		echo $this->load->view("r-cluster_ops", $data);
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
			
		} elseif ( $cluster_code != '' && $this->core->view_row('t_mtr_cluster_ops', array('UPPER(cluster_code)' => strtoupper($cluster_code), 'status' => 1, 'cluster_ops_id !='=> (int) $id)) ) {
			$hasil .= "Cluster Code has already in used \n";
			
		} elseif ( $cluster_name != '' && $this->core->view_row('t_mtr_cluster_ops', array('UPPER(cluster_name)' => strtoupper($cluster_name), 'status' => 1, 'cluster_ops_id !='=> (int) $id)) ) {
			$hasil .= "Cluster Name has already in used \n";
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mcluster_ops->saveData();
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
		$data['content_id'] = "cluster_ops/f-group-role";
		
		$data['group'] = $this->mcluster_ops->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mcluster_ops->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */