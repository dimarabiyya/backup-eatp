<?php
class mkabupaten extends CI_Model {
	function mkabupaten(){
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
			FROM t_mtr_lokasi mn
			JOIN t_mtr_user_role rl ON mn.\"id\"=rl.menu_id
			JOIN t_mtr_user_group grp ON grp.user_group_id = rl.user_group_id
			WHERE mn.parent_id = $id 
				AND mn.status = 1 AND rl.user_group_id = '$group'
			ORDER BY ordering";
		$query = $dblokal->query($q);
		return $query->result();
	}
		function getProv( $id= 0, $group="" ){
		$group = 1;//$this->session->userdata('group');
		$dblokal = $this->load->database("default", TRUE);
		$q = "select * FROM t_mtr_lokasi where jenis='P' order by nama ASC";
		$query = $dblokal->query($q);
		return $query->result();
	}
	############################################
	function getkabupatenDetail( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT * FROM t_mtr_lokasi WHERE child_id = $id AND jenis='KB'";
		$query = $dblokal->query($q);
		return $query->result();
	}
	
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','descr','province');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$q = "SELECT MAX(aa.child_id)+1 as maxchild
			FROM t_mtr_lokasi aa where jenis='KB'";
		$querys = $dblokal->query( $q );
		$rows = $querys->row_array();
		$data = array('nama' => $descr,
					'parent_id' => $province,
					'jenis' => 'KB',
					'status' =>1 );
		
		if($id == "") {
			$data['created_date'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			$data['child_id'] = $rows['maxchild'];
			
			$dblokal->insert('t_mtr_lokasi', $data);
			$dblokal->insert_id();
		}else{
			$data['updated_date'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			
			$dblokal->where(array('child_id'=>$id,'jenis'=>'KB'));
			$dblokal->update('t_mtr_lokasi', $data);
		}
	}
	
	public function deleteData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$dblokal->where( array('child_id'=>$id,'jenis'=>'KB') );
		$dblokal->update('t_mtr_lokasi',array('status'=>0));
		
	}
	
	public function loadData($data=NULL, $q) {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		    $or_arr=array('aa.nama');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="AND (lower($val) like lower('%$q%')";
                else
                    $or_str.="or lower($val) like lower('%$q%')";
            }
            $or_str.=")";
		 
		$q = "SELECT DISTINCT aa.child_id as child_id,
				aa.nama AS provinsi
				FROM t_mtr_lokasi aa
				WHERE aa.jenis='P' AND aa.status='1' $or_str
				ORDER BY provinsi";
		$query = $dblokal->query( $q );
        return $query;
	}
	public function loadDatas($offset,$rows,$q){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);       
            $or_arr=array('aa.nama');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="AND (lower($val) like lower('%$q%')";
                else
                    $or_str.="or lower($val) like lower('%$q%')";
            }
            $or_str.=")";   
			$q = "SELECT DISTINCT aa.child_id as child_id,
					aa.nama AS provinsi
					FROM t_mtr_lokasi aa
					WHERE aa.jenis='P' AND aa.status='1' $or_str
					ORDER BY provinsi
					LIMIT $rows OFFSET $offset";
		$query = $dblokal->query( $q );
        return $query;
    }
	public function getListDetails($id,$q,$offset=0,$rows=0){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);       
            $or_arr=array('aa.nama');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="AND (lower($val) like lower('%$q%')";
                else
                    $or_str.="or lower($val) like lower('%$q%')";
            }
            $or_str.=")";   
		$q = "SELECT DISTINCT aa.child_id as child_id,
					aa.nama AS kabupaten
					FROM t_mtr_lokasi aa
					WHERE aa.jenis='KB' AND aa.status='1' AND aa.parent_id='$id' $or_str
					ORDER BY kabupaten ";
									
		if ($rows!=0){
            $q .= "LIMIT ".$rows." OFFSET ".$offset." ";
        }
					
		$query = $dblokal->query( $q );
        return $query;
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
		$sql = "SELECT * FROM t_mtr_lokasi WHERE alias = '". $sistem ."' AND parent_id=0 ";
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
		// return $this->db->query("SELECT * from t_mtr_lokasi where parent_id = $parent_id")->result();
		if ($group_id ==''){
			$group_id = 0;
		}
		 $sql="select mn.id, mn.name as menu_name,rl.*  from t_mtr_lokasi mn
			left join t_mtr_user_role rl on mn.id = rl.menu_id and rl.user_group_id = $group_id
			LEFT JOIN t_mtr_user_group grp on grp.user_group_id = rl.user_group_id 
			where mn.parent_id = $parent_id and mn.status = 1
			order by mn.ordering";

		return $dblokal->query($sql)->result();
	}

	function getMenuId($sistem=''){
		$dblokal  = $this->load->database("default", TRUE);
		$hasil ="";
		$sql = "SELECT * FROM t_mtr_lokasi WHERE menu_path = '".$sistem."' and not parent_id=0 ";
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
		$sql = 'insert into t_mtr_lokasi ('.$key.') values ('.$val.')';
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