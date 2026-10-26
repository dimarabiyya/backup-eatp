<?php
class mchecklist_result extends CI_Model {
	function mchecklist_result(){
		parent::__construct();
	}
	
	function general()
	{
		$this->load->library('session');
		$data = array();
		$username           = $this->session->userdata('username');
		$data['webtitle'] 	        = "";//$this->getNamaSistem2();
		$data['getMenuSistem']      = "";//$this->getMenuSistem();
		//$data['template']           = "shell/admin_template";
	    
		$data['getJudul']       = "";//$this->mjadwal->getJudul($this->uri->segment(2));
		return $data;
	}
	
	function getMenu( $id= 0, $group="" ){
		$group = 1;//$this->session->userdata('group');
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT mn.\"id\" AS menu_id,mn.name, mn.alias, mn.parent_id, rl.menu_view,
				grp.user_group_name,grp.user_group_id
			FROM t_mtr_menu mn
			JOIN t_mtr_user_role rl ON mn.\"id\"=rl.menu_id
			JOIN t_mtr_user_group grp ON grp.user_group_id = rl.user_group_id
			WHERE mn.parent_id = $id 
				AND mn.published = 1 AND rl.user_group_id = '$group'
			ORDER BY ordering";
		$query = $dblokal->query($q);
		return $query->result();
	}
	############################################
	//datagrid jeasyui
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
		
		 $q = "SELECT aa.pm_id, dd.checklist_id, dd.used_sparepart, cc.descr, dd.pm_result, dd.photo_before, dd.photo_after, cc.interface, dd.pm_result
									from t_trx_pm_schedule aa
									join t_trx_pm_schedule_checklist bb on aa.pm_id = bb.pm_id
									join t_mtr_pm_checklist cc on cc.checklist_id = bb.checklist_id
									LEFT JOIN t_trx_pm_checklist_result dd on dd.pm_id = aa.pm_id and dd.checklist_id = bb.checklist_id
									where aa.pm_id ='$id' ";
									
		if ($rows!=0){
            $q .= "LIMIT ".$rows." OFFSET ".$offset." ";
        }
					
		$query = $dblokal->query( $q );
        return $query;
    }
	############################################
	function getchecklist_resultDetail($id){
		$dblokal = $this->load->database("default", TRUE);
		
		 $query = $dblokal->query("SELECT aa.pm_id, dd.checklist_id, dd.used_sparepart, cc.descr, dd.pm_result, dd.photo_before, dd.photo_after, cc.interface
									from t_trx_pm_schedule aa
									join t_trx_pm_schedule_checklist bb on aa.pm_id = bb.pm_id
									join t_mtr_pm_checklist cc on cc.checklist_id = bb.checklist_id
									LEFT JOIN t_trx_pm_checklist_result dd on dd.pm_id = aa.pm_id and dd.checklist_id = bb.checklist_id
									where aa.pm_id ='$id'");
		
		$hasil2 = array(
			'data' => array()
		);
		
		
		  foreach ($query->result() as $row2) {
		    $hasil2['data'][] = array(
		      'pm_result' => (empty($row2->pm_result)?"":$row2->pm_result),
		      'pm_id' => $row2->pm_id,
		      'photo_after' => $row2->photo_after,
		      'photo_before' => $row2->photo_before,
		      'descr' => $row2->descr,
		      'interface' => $row2->interface
		    );
		  }
		//return json_encode($hasil2);
		echo json_encode($hasil2);
	}
	
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','descr');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$data = array('descr' => $descr,
			      'status'=>1 );
		
		if($id == "") {
			$data['created_on'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			
			$dblokal->insert('t_mtr_propinsi', $data);
			$dblokal->insert_id();
		}else{
			$data['updated_on'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			
			$dblokal->where(array('propinsi_id'=>$id));
			$dblokal->update('t_mtr_propinsi', $data);
		}
	}
	
	public function deleteData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'descr');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$dblokal->where( array('propinsi_id'=>$id) );
		$dblokal->update('t_mtr_propinsi',array('status'=>0));
	}
	
	public function loadData2() {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND LOWER(t_mtr_site.site_name) LIKE LOWER('%$query%')";
		
		$q = "SELECT * FROM t_mtr_site , t_trx_pm_schedule, t_mtr_user WHERE t_mtr_site.site_id=t_trx_pm_schedule.site_id and t_mtr_user.user_id=t_trx_pm_schedule.user_id AND (t_trx_pm_schedule.status='1' or t_trx_pm_schedule.status='0') $where";
		
			/*$q = "SELECT t_trx_pm_schedule.pm_id as cpm_id, * FROM t_trx_pm_schedule 
			LEFT JOIN t_trx_pm_checklist_result ON t_trx_pm_checklist_result.pm_id=t_trx_pm_schedule.pm_id 
			LEFT JOIN t_mtr_site ON t_mtr_site.site_id=t_trx_pm_schedule.site_id
			LEFT JOIN t_mtr_user ON t_mtr_user.user_id=t_trx_pm_schedule.user_id $where";*/
			
		$query = $dblokal->query( $q );
		
		$no = 1;
		  foreach ($query->result() as $row) {
		    $hasils['aaData'][] = array(
				'no' => $no,
		      'pm_id' => $row->pm_id,
		      'user_name' => $row->user_name,
		      'user_name' => $row->user_name,
		      'site_code' => $row->site_code,
		      'visit_type' => $row->visit_type,
		      'visit_date' => $row->visit_date,
		      'site_name' => $row->site_name
		    );
			$no++;
		  }
		
		 
		echo json_encode($hasils);
	}
	public function loadData() {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND LOWER(t_mtr_site.site_name) LIKE LOWER('%$query%')";
		
		$q = "SELECT * FROM t_mtr_site , t_trx_pm_schedule, t_mtr_user WHERE t_mtr_site.site_id=t_trx_pm_schedule.site_id and t_mtr_user.user_id=t_trx_pm_schedule.user_id AND t_trx_pm_schedule.status='3' $where";
		
			/*$q = "SELECT t_trx_pm_schedule.pm_id as cpm_id, * FROM t_trx_pm_schedule 
			LEFT JOIN t_trx_pm_checklist_result ON t_trx_pm_checklist_result.pm_id=t_trx_pm_schedule.pm_id 
			LEFT JOIN t_mtr_site ON t_mtr_site.site_id=t_trx_pm_schedule.site_id
			LEFT JOIN t_mtr_user ON t_mtr_user.user_id=t_trx_pm_schedule.user_id $where";*/
			
		$query = $dblokal->query( $q );
		
		$no = 1;
		  foreach ($query->result() as $row) {
		    $hasil['aaData'][] = array(
				'no' => $no,
		      'pm_id' => $row->pm_id,
		      'user_name' => $row->user_name,
		      'user_name' => $row->user_name,
		      'site_code' => $row->site_code,
		      'visit_type' => $row->visit_type,
		      'visit_date' => $row->visit_date,
		      'site_name' => $row->site_name
		    );
			$no++;
		  }
		
		 
		echo json_encode($hasil);
	}
	############################################
	
	function getIdSistem()
	{
		$dblokal  = $this->load->database("default", TRUE);
		$hasil 	  = 0;
		$sistem   = $this->uri->segment(1);
		
		if($sistem == "") $sistem = "main";
		#$nmsistem = explode("_",$url);
		#$sistem = $nmsistem[0];
		$sql = "SELECT * FROM t_mtr_propinsi WHERE alias = '". $sistem ."' AND parent_id=0 ";
		$query = $dblokal->query($sql);
		$r = $query->result();
		if(count($r) > 0) {
			foreach($r as $d){
				$hasil= $d->menu_id;
			}
		}
		return $hasil;
	}
	
	function getGridMenu($parent_id, $group_id=''){
		$dblokal  = $this->load->database("default", TRUE);
		// return $this->db->query("SELECT * from t_mtr_propinsi where parent_id = $parent_id")->result();
		if ($group_id ==''){
			$group_id = 0;
		}
		 $sql="select mn.id, mn.name as menu_name,rl.*  from t_mtr_propinsi mn
			left join t_mtr_user_role rl on mn.id = rl.menu_id and rl.user_group_id = $group_id
			LEFT JOIN t_mtr_user_group grp on grp.user_group_id = rl.user_group_id 
			where mn.parent_id = $parent_id and mn.published = 1
			order by mn.ordering";

		return $dblokal->query($sql)->result();
	}

	function getMenuId($sistem=''){
		$dblokal  = $this->load->database("default", TRUE);
		$hasil ="";
		$sql = "SELECT * FROM t_mtr_propinsi WHERE menu_path = '".$sistem."' and not parent_id=0 ";
		$query = $dblokal->query($sql);
		$r = $query->result();
		if(count($r) > 0) {
			foreach($r as $d){
				$hasil= $d->menu_code;
			}
		} else {
			$hasil= '';
		}
		return $hasil;

	}

	function saveRoles($sql, $act=''){
		// print_r($sql);die;
		$dblokal  = $this->load->database("default", TRUE);
		if($dblokal->query($sql)){
			// $this->mglobal->saveLog('Grouproles',$act,$this->session->userdata('username'), '1');
			echo json_encode(array(
					'pesan'=>  'Save Konfigurasi group role suksess'
			));
		}
		else {
			echo json_encode(array(
					'pesan'=>  'error Save data '
			));
		}
	}

	function addMenu($data){
		$key = $data['key'];
		$val =$data['val'];

		$dblokal = $this->load->database("default", TRUE);
		$sql = 'insert into t_mtr_propinsi ('.$key.') values ('.$val.')';
		if($dblokal->query($sql)){
			echo json_encode(array(
					'pesan'=>  'Save Menu Baru suksess'

			));

		}else {
			echo json_encode(array(
					'pesan'=>  'error Save Menu Baru '

			));
		}
	}
	
	###get nama group
	function getGroup(){
		$dblokal = $this->load->database("default", TRUE);
		$group = $dblokal->order_by('user_group_name', 'asc')->get('t_mtr_user_group')->result();
		return $group;
	}
	
	########################################################################
	#save group role
	function saveGroupRole(){
		$dblokal = $this->load->database("default", TRUE);
		
		$data['menu_view']   	= 0;
		$data['menu_add']    	= 0;
		$data['menu_edit']   	= 0;
		$data['menu_delete'] 	= 0;
		$data['menu_approve'] 	= 0;
		$data['menu_reject'] 	= 0;
		
		$data['menu_id']   	= $this->input->post('id');
		$data['user_group_id'] 	= $this->input->post('group');
		$test = $dblokal->escape($this->input->post('id'));
		
		$role = $this->input->post('role');
		//echo count($role);
		
		if($data['user_group_id'] == ""){
			echo json_encode(array('pesan'=>  'Group Role belum dipilih'));
			return;
		}
		
		if ( count($role) >1 ) {
			$post1 =  array_diff( $role , array('checkall') );
			if (in_array('menu_view', $post1)){
				$data['menu_view'] = 1;
			} 
			if (in_array('menu_add', $post1)){
				$data['menu_add'] = 1;
			}
			if (in_array('menu_delete', $post1)){
				$data['menu_delete'] = 1;
			}
			if (in_array('menu_edit', $post1)){
				$data['menu_edit'] = 1;
			}
			if (in_array('menu_approve', $post1)){
				$data['menu_approve'] = 1;
			}
			if (in_array('menu_reject', $post1)){
				$data['menu_reject'] = 1;
			}
		}
		
		$update = $dblokal->query('select * from t_mtr_user_role where menu_id ='.$data['menu_id'].' and  user_group_id = '.$data['user_group_id']);
		$get = $update->row();
		 //print_r($update->num_rows);die;
		$hasil = "";
		if ($update->num_rows == 0){
			$data['created_by'] = $this->session->userdata('user_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			
			$act ='Insert-new-role';
			if($dblokal->insert('t_mtr_user_role',$data)){
				$hasil = "OK";
			}
		} else {
			$data['updated_by'] = $this->session->userdata('user_id');
			$data['updated_on'] = date('Y-m-d H:i:s');
			
			$act ='update_role';
			if( $dblokal->where(array('menu_id'=>$data['menu_id'],'user_group_id'=>$data['user_group_id']))->update('t_mtr_user_role',$data) ){
				$hasil = "OK";
			}
		}
		
		if($hasil == "OK"){
			echo json_encode(array('pesan'=>  'Save Konfigurasi group role suksess'));
		}else {
			echo json_encode(array('pesan'=>  'error Save data'));
		}
	}
}
?>