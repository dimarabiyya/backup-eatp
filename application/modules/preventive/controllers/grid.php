<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grid extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->mglobal->is_logged_in();
    $this->load->model('mgrid');
  }
  public function index() {

  }
	public function getCluster(){

    $region_id = $this->input->post('region_id');
	$cluster = $this->mgrid->getCluster($region_id);
	// echo "<pre>";
	// print_r($cluster);
	// echo "</pre>"
	
	//return $cluster;
	$option ='<option value="">ALL</option>';
	foreach ($cluster as $key => $value) {
		$option .= '<option value="'.$value['cluster_ops_id'].'">'.$value['cluster_name'].'</option>';
	}    //echo json_encode($cluster);
	echo $option;
	}

  public function pic_site() {
    $page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
    $limit = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 10;
    $offset = ($page - 1)*$limit;
    $keyword = $this->input->post('keyword');
    $region_id = $this->input->post('region_id');
	$cluster_ops_id = $this->input->post('cluster_ops_id');// die($cluster_ops_id);

    $r = $this->mgrid->getPicMap($keyword, $region_id, $limit, $offset, $cluster_ops_id);
    // echo $this->db->last_query();
    // echo "<pre>";
	// print_r($r);
	// echo "</pre>";	
    echo json_encode($r);
  }

  public function pic_site_mapping(){
    $page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
    $limit = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 20;
    $offset = ($page - 1)*$limit;
    $q = $this->input->post('user_id');
    $t = $this->input->post('table_id');
    $k = $this->input->post('keyword');

    $r = $this->mgrid->getPicSiteMap($q, $t, $k, $limit, $offset);

    echo json_encode($r);
  }


  public function cluster_coordinator() {
    $page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
    $limit = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 10;
    $offset = ($page - 1)*$limit;
    $keyword = $this->input->post('keyword');
    $region_id = $this->input->post('region_id');
    $cluster_id = $this->input->post('cluster_id');

    $cluster_id=='null' ? false : $cluster_id; 
    $r = $this->mgrid->getClusterCoordinator($keyword, $region_id, $cluster_id, $limit, $offset);
    echo json_encode($r);
  }
  
    public function cluster_coordinator_mapping(){
    $page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
    $limit = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 20;
    $offset = ($page - 1)*$limit;
    $q = $this->input->post('user_id');
	//$region_id = $this->input->post('region_id');
    $t = $this->input->post('table_id');
    $k = $this->input->post('keyword');

	//$r = $this->mgrid->getClusterCoordinatorMap($q, $region_id, $t, $k, $limit, $offset);
    $r = $this->mgrid->getClusterCoordinatorMap($q, $t, $k, $limit, $offset);

    echo json_encode($r);
  }

}