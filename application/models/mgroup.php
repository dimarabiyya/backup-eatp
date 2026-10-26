<?php
class mgroup extends CI_Model {
	function Muser(){
		parent::__construct();
	}
	
	function getDetailData( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT * FROM t_mtr_user_group WHERE user_group_id = '$id'";
		$query = $dblokal->query($q);
		return $query->result();
	}
	
	############################################
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','nama', 'caption', 'status', 'area_restriction');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$data = array('user_group_name' => $nama,
			      'user_group_caption' => $caption,
			      'status' => ($status=="on"?1:0),
			      'area_restriction' => $area_restriction
			     );
			      
		if($id == "") {
			$data['created_on'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			
			$dblokal->trans_begin();
			
			$dblokal->insert('t_mtr_user_group', $data);
			$this->dblog->AuditTrail('group-create', $data);
			if ($dblokal->trans_status() === FALSE) {
			    $dblokal->trans_rollback();
			} else {
			    $dblokal->trans_commit();
			}
		}else{
			$data['updated_on'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			
			$dblokal->trans_begin();
		
			$dblokal->where(array('user_group_id'=>$id));
			$dblokal->update('t_mtr_user_group', $data);
			$this->dblog->AuditTrail('group-update', $data);
			if ($dblokal->trans_status() === FALSE) {
			    $dblokal->trans_rollback();
			} else {
			    $dblokal->trans_commit();
			}
		}
	}
	
	public function deleteData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$dblokal->trans_begin();
		$x = array(
				'status' => -1, 
				'updated_on' => date("Y-m-d H:i:s"),
				'updated_by' => $this->session->userdata('user_id')
			);

		$dblokal->where( array('user_group_id'=>$id) );
		$dblokal->update('t_mtr_user_group', $x);
		$this->dblog->AuditTrail('group-delete', "Group ID: $id");
		if ($dblokal->trans_status() === FALSE) {
		    $dblokal->trans_rollback();
		} else {
		    $dblokal->trans_commit();
		}
	}
	
	public function loadData($d=NULL,$sort,$order,$limit=0,$offset=0) {
		$dblokal = $this->load->database("default", TRUE);
		 
		$where = "";
		if(isset($d['q'])){
			$query = $dblokal->escape_like_str($d['q']);
			$where = " AND (
				lower(g.user_group_name) LIKE '%$query%'
				) ";
		}
		$q = "SELECT g.*, l.lookup_desc as area_restriction
			FROM t_mtr_user_group g
			left join t_mtr_lookup l on l.lookup_value = g.area_restriction and l.lookup_key = 'area_restriction'
			WHERE g.status IN (0,1)
			$where
			ORDER BY $sort $order ";
		if ($limit>0) $q .=" LIMIT $limit OFFSET $offset ";
		
		$query = $dblokal->query( $q );
		return $query;
	}
	
	public function loadDataGroupByID( $id ) {
		$dblokal = $this->load->database("default", TRUE);
			
		$query = $dblokal->query( "SELECT g.* , l.lookup_key, l.lookup_value, l.lookup_desc
			FROM t_mtr_user_group g left join t_mtr_lookup l on l.lookup_value = g.area_restriction
			and l.lookup_key = 'area_restriction' WHERE g.user_group_id='$id'" );
		
		$fields = $query->list_fields(); 
		$q = $query->result();
		$hasil = array();
		foreach($q as $r){
			foreach ($fields as $field){
				$hasil['hasil'][$field] = $r->$field;
			}
		}
                $hasil['status'] = 'OK';
		echo json_encode($hasil, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
	}
	
	////--------------------------
	public function loadDataDivByID( $id ) {
		$dblokal = $this->load->database("default", TRUE);
			
		$query = $dblokal->query( "SELECT * 
			FROM t_mtr_user_division 
			WHERE user_div_id='$id'" );
		
		$fields = $query->list_fields(); 
		$q = $query->result();
		$hasil = array();
		foreach($q as $r){
			foreach ($fields as $field){
				$hasil['hasil'][$field] = $r->$field;
			}
		}
                $hasil['status'] = 'OK';
		echo json_encode($hasil, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
	}

	############################################
	/*
	function get_territory_name()
	{
	    $this->db->select('territory_type_id, territory_type_name');
	    $this->db->order_by('territory_type_name');
	    $sql = $this->db->get('t_mtr_territory_type');
	    return $sql->result();
	}
    */
	function get_parent()
	{
	    $this->db->select('user_group_id, user_group_name');
	    $this->db->order_by('user_group_name');
	    $sql = $this->db->get('t_mtr_user_group');
	    return $sql->result();
	}
	
	function get_parent_div()
	{
	    $this->db->select('user_div_id, user_div_name');
	    $this->db->order_by('user_div_name');
	    $sql = $this->db->get('t_mtr_user_division');
	    return $sql->result();
	}
	
	
	//------------------------------ Division  /  Group
	public function loadDataDiv($d=NULL,$sort,$order,$limit=0,$offset=0) {
		$dblokal = $this->load->database("default", TRUE);
		 
		$where = "";
		if(isset($d['q'])){
			$query = $dblokal->escape_like_str($d['q']);
			$where = " AND (
				lower(a.user_div_name) LIKE '%$query%'
				) ";
		}
		$q = "select a.* from t_mtr_user_division as a
			where a.status IN (0,1) 
			$where
			ORDER BY $sort $order ";
		if ($limit>0) $q .=" LIMIT $limit OFFSET $offset ";
		
		$query = $dblokal->query( $q );
		return $query;
	}
	
	public function saveDataDiv(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','nama', 'status'); //'parent', 

		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$data = array('user_div_name' => $nama,  //'parent_id' => $parent,
			      'status' => ($status=="on"?1:0)
			     );
			      
		if($id == "") {
			$data['created_on'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			
			// $dblokal->trans_begin();
			$dblokal->insert('t_mtr_user_division', $data);
			$this->dblog->AuditTrail('division-create', $data);
			// if ($dblokal->trans_status() === FALSE) {
			    // $dblokal->trans_rollback();
			// } else {
			    // $dblokal->trans_commit();
			// }
		}else{
			$data['updated_on'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			
			$dblokal->trans_begin();
		
			$dblokal->where(array('user_div_id'=>$id));
			$dblokal->update('t_mtr_user_division', $data);
			$this->dblog->AuditTrail('division-update', $data);
			if ($dblokal->trans_status() === FALSE) {
			    $dblokal->trans_rollback();
			} else {
			    $dblokal->trans_commit();
			}
		}
	}
	
	public function deleteDataDiv(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$dblokal->trans_begin();
		
		$dblokal->where( array('user_div_id'=>$id) );
		$dblokal->update('t_mtr_user_division',array('status'=>-1));
		$this->dblog->AuditTrail('division-delete', "Div ID: $id");
		if ($dblokal->trans_status() === FALSE) {
		    $dblokal->trans_rollback();
		    $result=array('status' => 'ERROR','hasil' => 'Delete is unsuccessful. <br>Data is being used.');
		} else {
		    $dblokal->trans_commit();
		    $result = array('status' => 'OK','hasil' => 'Scope of Work Group Deleted sucessfully.');
		}
		echo json_encode($result);
                return;
	}
}
?>