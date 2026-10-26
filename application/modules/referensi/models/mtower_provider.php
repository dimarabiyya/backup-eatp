<?php
class mtower_provider extends CI_Model {
	function mtower_provider(){
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
	function getSiteType( $id= 0, $group="" ){
		$group = 1;//$this->session->userdata('group');
		$dblokal = $this->load->database("default", TRUE);
		$q = "select * FROM t_mtr_lookup WHERE lookup_key='site_type' order by lookup_desc ASC";
		$query = $dblokal->query($q);
		return $query->result();
	}
	function getPartner( $id= 0, $group="" ){
		$group = 1;//$this->session->userdata('group');
		$dblokal = $this->load->database("default", TRUE);
		$q = "select * FROM t_mtr_partner WHERE status='1' order by partner_name ASC";
		$query = $dblokal->query($q);
		return $query->result();
	}
	function getSiteNamebyID($id) {
        $this->db->order_by("site_name", "ASC");
        $this->db->where("site_id", $id);
        $query = $this->db->get("t_mtr_site");
        if ($query->num_rows() > 0) return $query->result();             
    }
	function getSiteID( $id= 0, $group="" ){
		$group = 1;//$this->session->userdata('group');
		$dblokal = $this->load->database("default", TRUE);
		$q = "select * FROM t_mtr_site WHERE active='1' order by site_code ASC";
		$query = $dblokal->query($q);
		return $query->result();
	}
	function getTowerType( $id= 0, $group="" ){
		$group = 1;//$this->session->userdata('group');
		$dblokal = $this->load->database("default", TRUE);
		$q = "select * FROM t_mtr_tower_type order by tower_type_name ASC";
		$query = $dblokal->query($q);
		return $query->result();
	}
	function getReg( $id= 0, $group="" ){
		$group = 1;//$this->session->userdata('group');
		$dblokal = $this->load->database("default", TRUE);
		$q = "select * FROM t_mtr_region WHERE status='1' order by region_name ASC";
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
	function getCity( $region= 0, $group="" ){
		
		$group = 1;//$this->session->userdata('group');
		$dblokal = $this->load->database("default", TRUE);
		$q = "select * FROM t_mtr_lokasi where jenis='KB' AND lokasi_id='$region' order by nama ASC";
		$query = $dblokal->query($q);
		return $query->result();
	}
	function get_city_by_state($id) {
        $this->db->order_by("nama", "ASC");
        $this->db->where("parent_id", $id);
        $this->db->where("jenis", 'KB');
        $query = $this->db->get("t_mtr_lokasi");
        if ($query->num_rows() > 0) return $query->result();             
    }
	############################################
	function gettower_providerDetail( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT * FROM t_mtr_tower_provider WHERE tp_id = $id ";
		$query = $dblokal->query($q);
		return $query->result();
	}
	
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','tp_name','site_id','site_name','province','region','city','status','lattitude','longitude','site_type','tower_type','building_height_mtr','tower_height_mtr','address','shareable_status','site_code','remark','partner_id','kandidat');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$data = array('tp_company_name' => $tp_name,
				'tp_code' => null_empty($site_id),
				'tp_site_name' => null_empty($site_name),
				'province' => null_empty($province),
				'region' => null_empty($region),
				'city' => null_empty($city),
				'status' => null_empty($status),
				'latitude' => null_empty($lattitude),
				'longitude' => null_empty($longitude),
				'site_type' => null_empty($site_type),
				'tower_type' => null_empty($tower_type),
				'building_height_mtr' => $building_height_mtr,
				'tower_height_mtr' => $tower_height_mtr,
				'address' => $address,
				'shareable_status' => $shareable_status,
				'site_code' => $site_code,
				'remark' => $remark,
				'partner_id' => null_empty($partner_id),
				'candidate_status' => null_empty($kandidat),
			      'active'=>1 );
		
		if($id == "") {
			$data['created_on'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			
			$dblokal->insert('t_mtr_tower_provider', $data);
			$dblokal->insert_id();
		}else{
			$data['updated_on'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			
			$dblokal->where(array('tp_id'=>$id));
			$dblokal->update('t_mtr_tower_provider', $data);
		}
	}
	
	public function deleteData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'tp_name');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$dblokal->where( array('tp_id'=>$id) );
		$dblokal->update('t_mtr_tower_provider',array('active'=>0));
	}
	
	public function loadData() {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND LOWER(aa.tp_company_name) LIKE LOWER('%$query%')";
		
		$q = "SELECT * FROM t_mtr_tower_provider aa, t_mtr_partner bb WHERE aa.partner_id=bb.partner_id AND aa.active=1 $where";
			
		$query = $dblokal->query( $q );
		$total = $query->num_rows;
		$npage = ceil($total / $dataperpage);
		 
		$start = $curpage * $dataperpage;
		$end = $start + $dataperpage;
		
		$q .= " ORDER BY aa.tp_id
			LIMIT $dataperpage OFFSET $start";
		$query = $dblokal->query( $q );
		
		$hasil = array(
			'data' => array(),
			'pagination' => '',
			'curpage' => $curpage,
			'numpage' => $npage - 1,
		);
		if ($query->num_rows() > 0) {
		  foreach ($query->result() as $row) {
		    $hasil['data'][] = array(
		      'no' => $row->tp_id,
		      'tp_code' => $row->tp_code,
		      'tp_site_name' => $row->tp_site_name,
		      'tp_company_name' => $row->tp_company_name,
		      'province' => $row->province,
		      'region' => $row->region,
		      'city' => $row->city,
		      'status' => $row->status,
		      'latitude' => $row->latitude,
		      'longitude' => $row->longitude,
		      'site_type' => $row->site_type,
		      'tower_type' => $row->tower_type,
		      'building_height_mtr' => $row->building_height_mtr,
		      'tower_height_mtr' => $row->tower_height_mtr,
		      'address' => $row->address,
		      'shareable_status' => $row->shareable_status,
		      'site_code' => $row->site_code,
		      'remark' => $row->remark,
		      'partner_id' => $row->partner_name,
		      'candidate_status' => $row->candidate_status
		    );
		  }
		}
		
				    
		$hasil['pagination'] .= '<ul>
			<li class="'. ($curpage == 0 ? 'disabled' : '') .'" onclick="return Document.prevPage()"><a href>&laquo;</li>';
			$nmargin = 5;
			
			$nmulai = 1; $nselesai = $npage;
			if($curpage >= $nmargin) $nmulai = $curpage - $nmargin;
			if($curpage+$nmargin <= $npage) $nselesai = $curpage + $nmargin;
			
			for ($i = $nmulai; $i <= $nselesai; $i++) {
				$hasil['pagination'] .= '<li class="' . ($curpage == ($i - 1) ? 'active' : '') . '" onclick="return Document.setPage(' . ($i - 1) .')"><a href>' . $i . '</a></li>'; 
			}
		$hasil['pagination'] .= '<li class="' . ($curpage == $npage - 1 ? 'disabled' : '') . '" onclick="return Document.nextPage()"><a href>&raquo;</a></li>
		  </ul>'; 
		 
		echo json_encode($hasil, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
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
		$sql = "SELECT * FROM t_mtr_tower_provider2 WHERE alias = '". $sistem ."' AND parent_id=0 ";
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
		// return $this->db->query("SELECT * from t_mtr_tower_provider2 where parent_id = $parent_id")->result();
		if ($group_id ==''){
			$group_id = 0;
		}
		 $sql="select mn.id, mn.name as menu_name,rl.*  from t_mtr_tower_provider2 mn
			left join t_mtr_user_role rl on mn.id = rl.menu_id and rl.user_group_id = $group_id
			LEFT JOIN t_mtr_user_group grp on grp.user_group_id = rl.user_group_id 
			where mn.parent_id = $parent_id and mn.published = 1
			order by mn.ordering";

		return $dblokal->query($sql)->result();
	}

	function getMenuId($sistem=''){
		$dblokal  = $this->load->database("default", TRUE);
		$hasil ="";
		$sql = "SELECT * FROM t_mtr_tower_provider WHERE menu_path = '".$sistem."' and not parent_id=0 ";
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
		$sql = 'insert into t_mtr_tower_provider2 ('.$key.') values ('.$val.')';
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