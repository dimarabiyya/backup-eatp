<?php
class mpreventive extends CI_Model {
	function mpreventive(){
		parent::__construct();
	}
	
	function general()
	{
		$this->load->library('session');
		$data = array();
		$username           		= $this->session->userdata('username');
		$data['webtitle'] 	        = "";//$this->getNamaSistem2();
		$data['getMenuSistem']      = "";//$this->getMenuSistem();
		//$data['template']         = "shell/admin_template";
	    
		$data['getJudul']       = "";//$this->mjadwal->getJudul($this->uri->segment(2));
		return $data;
	}
	#######################################datagrid jeasyui
	public function getListVisit($type,$d=NULL){
		$dblokal = $this->load->database("default", TRUE);
		if (isset($d['q'])){
            $q=$d['q'];
            $or_arr=array('t_mtr_site.site_code','t_trx_pm_schedule.visit_type');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="(lower($val) like lower('%$q%')";
                else
                    $or_str.=" or lower($val) like lower('%$q%')";
            }
            $or_str.=")";
        }
		 $q = "SELECT * FROM t_mtr_site , t_trx_pm_schedule, t_mtr_user WHERE t_mtr_site.site_id=t_trx_pm_schedule.site_id and t_mtr_user.user_id=t_trx_pm_schedule.user_id AND (t_trx_pm_schedule.status='1' or t_trx_pm_schedule.status='$type') AND $or_str ";
		
		$query = $dblokal->query( $q );
        return $query;
    }
	 
	public function getListDetails($id,$offset=0,$rows=0){
		$dblokal = $this->load->database("default", TRUE);
		
		 $q = "SELECT aa.pm_id, cc.checklist_name,cc.interface
									from t_trx_pm_schedule aa
									join t_trx_pm_schedule_checklist bb on aa.pm_id = bb.pm_id
									join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
									where aa.pm_id ='$id' AND cc.interface='desktop'";
									
		if ($rows!=0){
            $q .= "LIMIT ".$rows." OFFSET ".$offset." ";
        }
					
		$query = $dblokal->query( $q );
        return $query;
    }
	public function addeditdata($id,$d=NULL){
		$dblokal = $this->load->database("default", TRUE);
		if (isset($d['q'])){
            $q=$d['q'];
            $or_arr=array('cc.checklist_name');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="(lower($val) like lower('%$q%')";
                else
                    $or_str.=" or lower($val) like lower('%$q%')";
            }
            $or_str.=")";
        }
		
		 $q = "SELECT aa.pm_id, cc.checklist_name, cc.interface
           from t_trx_pm_schedule aa
           join t_trx_pm_schedule_checklist bb on aa.pm_id = bb.pm_id
           join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
           where aa.pm_id ='$id' AND cc.interface='desktop' AND $or_str";

      $query = $dblokal->query( $q );
      return $query;
    }

  public function getUserRegions($user_id) {
    $this->db->select('rr.region_id, rr.region_name');
    $this->db->join('t_mtr_region rr', 'rr.region_id = tt.region_id');
    $this->db->where('tt.user_id', $user_id);
    $q = $this->db->get('t_mtr_user_territory tt');
    return $q->result();
  }

  public function getCluster($region_id) {
    $this->db->select('co.cluster_ops_id, co.cluster_name, co.region_id');
    $this->db->where('co.status = 1');
    $this->db->where_in('co.region_id', $region_id);
    $q = $this->db->get('t_mtr_cluster_ops co');
    return $q->result_array();
  }

/*
	public function deleteCCAssignment($user_id, $cluster_id) {
    $this->db->trans_begin();
      $this->db->where('user_id', $user_id);
      $this->db->where('cluster_ops_id', $cluster_id);
      $log = $this->db->get('t_mtr_cluster_mapping')->result();

      $this->db->where('user_id', $user_id);
      $this->db->where('cluster_ops_id', $cluster_id);
      $this->db->delete('t_mtr_cluster_mapping');

      if($this->db->affected_rows() > 0) {
        $this->dblog->AuditTrail('delete-cluster-mapping-assignment', $log);
      }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return false;
    }
    else {
      $this->db->trans_commit();
      return true;
    }
  }*/

  	public function deleteAllCCAssignment($user_id) {
    $this->db->trans_begin();
      $this->db->where('user_id', $user_id);
      //$this->db->where('cluster_ops_id', $cluster_id);
      $log = $this->db->get('t_mtr_cluster_mapping')->result();

      $this->db->where('user_id', $user_id);
      //$this->db->where('cluster_ops_id', $cluster_id);
      $this->db->delete('t_mtr_cluster_mapping');

      if($this->db->affected_rows() > 0) {
        $this->dblog->AuditTrail('delete-cluster-mapping-assignment', $log);
      }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return false;
    }
    else {
      $this->db->trans_commit();
      return true;
    }
  }
		public function deleteCCAssignment($user_id, $cluster_id) {
    $this->db->trans_begin();
      $this->db->where('user_id', $user_id);
      $this->db->where('cluster_ops_id', $cluster_id);
      $log = $this->db->get('t_mtr_cluster_mapping')->result();

      $this->db->where('user_id', $user_id);
      $this->db->where('cluster_ops_id', $cluster_id);
      $this->db->delete('t_mtr_cluster_mapping');

      if($this->db->affected_rows() > 0) {
        $this->dblog->AuditTrail('delete-cluster-mapping-assignment', $log);
      }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return false;
    }
    else {
      $this->db->trans_commit();
      return true;
    }
  }
}

/* End of file mpreventive.php */
/* Location: ./application/modules/preventive/models/mpreventive.php */
