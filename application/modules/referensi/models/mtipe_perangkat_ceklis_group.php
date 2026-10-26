<?php
class mtipe_perangkat_ceklis_group extends CI_Model {
	function mtipe_perangkat_ceklis_group(){
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
	
	function getMenu(){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT property_id, nama as tnama FROM t_m_tipe_perangkat_site where status='1' order by nama ASC";
		$query = $dblokal->query($q);
		return $query->result();
	}
	
	
	
	############################################
	function gettipe_perangkat_ceklis_groupDetail( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT * FROM t_m_tipe_perangkat_ceklis_group WHERE group_id = $id ";
		$query = $dblokal->query($q);
		return $query->result();
	}
	
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','type','nama');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$data = array('tipe_perangkat_id' => $type,
			      'nama' => $nama,
			      'status'=>1 );
		
		if($id == "") {
			$data['created_on'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			
			$dblokal->insert('t_m_tipe_perangkat_ceklis_group', $data);
			$dblokal->insert_id();
		}else{
			$data['updated_on'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			
			$dblokal->where(array('group_id'=>$id));
			$dblokal->update('t_m_tipe_perangkat_ceklis_group', $data);
		}
	}
	
	public function deleteData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'nama');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$dblokal->where( array('group_id'=>$id) );
		$dblokal->update('t_m_tipe_perangkat_ceklis_group',array('status'=>0));
	}
	
	public function loadData() {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND LOWER(t_m_tipe_perangkat_ceklis_group.nama) LIKE LOWER('%$query%')";
		
		$q = "SELECT t_m_tipe_perangkat_ceklis_group.group_id, t_m_tipe_perangkat_ceklis_group.nama, t_m_tipe_perangkat_site.nama as namatipe FROM t_m_tipe_perangkat_ceklis_group, t_m_tipe_perangkat_site WHERE t_m_tipe_perangkat_ceklis_group.status=1 and t_m_tipe_perangkat_site.perangkat_id = t_m_tipe_perangkat_ceklis_group.tipe_perangkat_id $where";
			
		$query = $dblokal->query( $q );
		$total = $query->num_rows;
		$npage = ceil($total / $dataperpage);
		 
		$start = $curpage * $dataperpage;
		$end = $start + $dataperpage;
		
		$q .= " ORDER BY t_m_tipe_perangkat_ceklis_group.group_id
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
		      'group_id' => $row->group_id,
		      'namatipe' => $row->namatipe,
		      'nama' => $row->nama
		    );
		  }
		}
		
				    
		$hasil['pagination'] .= '<ul>
			<li class="'. ($curpage == 0 ? 'disabled' : '') .'" onclick="return Document.prevPage()"><a href>&laquo;</li>';
			$nmulai = 1;
			//if($curpage >= 5) $nmulai = $curpage - 5;
			for ($i = 1; $i <= $npage; $i++) {
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
		$sql = "SELECT * FROM t_m_tipe_perangkat_ceklis_group WHERE alias = '". $sistem ."' AND parent_id=0 ";
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
		// return $this->db->query("SELECT * from t_m_tipe_perangkat_ceklis_group where parent_id = $parent_id")->result();
		if ($group_id ==''){
			$group_id = 0;
		}
		 $sql="select mn.id, mn.name as menu_name,rl.*  from t_m_tipe_perangkat_ceklis_group mn
			left join t_mtr_user_role rl on mn.id = rl.menu_id and rl.user_group_id = $group_id
			LEFT JOIN t_mtr_user_group grp on grp.user_group_id = rl.user_group_id 
			where mn.parent_id = $parent_id and mn.published = 1
			order by mn.ordering";

		return $dblokal->query($sql)->result();
	}

	function getMenuId($sistem=''){
		$dblokal  = $this->load->database("default", TRUE);
		$hasil ="";
		$sql = "SELECT * FROM t_m_tipe_perangkat_ceklis_group WHERE menu_path = '".$sistem."' and not parent_id=0 ";
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
		$sql = 'insert into t_m_tipe_perangkat_ceklis_group ('.$key.') values ('.$val.')';
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