<?php
class mcluster_nt extends CI_Model {
	function mcluster_nt(){
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
	public function getListVisits($data=NULL){
		$dblokal = $this->load->database("default", TRUE);

			$this->db->select('cn.cluster_net_id, cn.cluster_name, cn.cluster_code, r.region_id, r.region_name');
			$this->db->join('t_mtr_region r','cn.region_id=r.region_id',"left");
	        if ($data['q'] == ''){
	            $q=$data['q'];
	            $or_arr=array('cn.cluster_name', 'cn.cluster_code', 'r.region_name');
	                $or_str="";$q=strtolower($q);
	                foreach ($or_arr as $val) {
	                    if ($or_str=="")
	                        $or_str.="(lower($val) like '%$q%'";
	                    else
	                        $or_str.=" or lower($val) like '%$q%'";
	                }
	                $or_str.=")";
	            $this->db->where($or_str);

	            unset($data['q']);
	        }
			$this->db->where('cn.status', 1);
			$this->db->where('r.status', 1);
			$q = $this->db->get('t_mtr_cluster_net cn');
        return $q;
    }
	 public function getListVisit($offset=0,$rows=0,$data=''){
			$this->db->select('cn.cluster_net_id, cn.cluster_name, cn.cluster_code, r.region_id, r.region_name');
			$this->db->join('t_mtr_region r','cn.region_id=r.region_id',"left");
	        if ($data['q'] != ''){
	            $q=$data['q'];
	            $or_arr=array('cn.cluster_name', 'cn.cluster_code', 'r.region_name');
	                $or_str="";$q=strtolower($q);
	                foreach ($or_arr as $val) {
	                    if ($or_str=="")
	                        $or_str.="(lower($val) like '%$q%'";
	                    else
	                        $or_str.=" or lower($val) like '%$q%'";
	                }
	                $or_str.=")";
	            $this->db->where($or_str);

	            unset($data['q']);
	        }
			$this->db->where('cn.status', 1);
			$this->db->where('r.status', 1);
	        $this->db->order_by('cn.cluster_net_id', 'asc');
	        if ($rows==0)
			$q = $this->db->get(' t_mtr_cluster_net cn');
			else
			$q = $this->db->get(' t_mtr_cluster_net cn',$rows,$offset);
        return $q;
    }
	
	############################################
	function getcluster_ntDetail( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT * FROM t_mtr_cluster_net WHERE cluster_net_id = $id ";
		$query = $dblokal->query($q);
		return $query->result();
	}
	
	function getRegion(){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT region_id, region_name as rname FROM t_mtr_region where status='1' order by rname ASC";
		$query = $dblokal->query($q);
		return $query->result();
	}
	
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','cluster_name','cluster_code','region_id');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$data = array('cluster_name' => $cluster_name,
				  'cluster_code' => $cluster_code,
				  'region_id' => $region_id,
			      'status'=>1 );
		
		if($id == "") {
			$data['created_on'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			
			$dblokal->insert('t_mtr_cluster_net', $data);
			$dblokal->insert_id();
		}else{
			$data['updated_on'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			
			$dblokal->where(array('cluster_net_id'=>$id));
			$dblokal->update('t_mtr_cluster_net', $data);
		}
	}
	
	public function deleteData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'cluster_name', 'cluster_code', 'region_id');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$dblokal->where( array('cluster_net_id'=>$id) );
		$dblokal->update('t_mtr_cluster_net',array('status'=>0));
	}
	
	public function loadData() {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND LOWER('cn.cluster_name') LIKE LOWER('%$query%')";
		
		$q = "SELECT cn.cluster_net_id, cn.cluster_name, cn.cluster_code, r.region_id, r.region_name
			  FROM t_mtr_cluster_net cn
			  INNER JOIN  t_mtr_region r
			  ON cn.region_id=r.region_id
			  WHERE cn.status=1 and r.status=1 $where";
			
		$query = $dblokal->query( $q );
		$total = $query->num_rows;
		$npage = ceil($total / $dataperpage);
		 
		$start = $curpage * $dataperpage;
		$end = $start + $dataperpage;
		
		$q .= " ORDER BY cn.cluster_net_id ASC
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
		      'cluster_net_id' => $row->cluster_net_id,
		      'cluster_name' => $row->cluster_name,
			  'cluster_code' => $row->cluster_code,
			  'region_id' => $row->region_name,
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
		$sql = "SELECT * FROM t_mtr_cluster_net WHERE alias = '". $sistem ."' AND parent_id=0 ";
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
		// return $this->db->query("SELECT * from t_mtr_cluster_net where parent_id = $parent_id")->result();
		if ($group_id ==''){
			$group_id = 0;
		}
		 $sql="select mn.id, mn.name as menu_name,rl.*  from t_mtr_cluster_net mn
			left join t_mtr_user_role rl on mn.id = rl.menu_id and rl.user_group_id = $group_id
			LEFT JOIN t_mtr_user_group grp on grp.user_group_id = rl.user_group_id 
			where mn.parent_id = $parent_id and mn.published = 1
			order by mn.ordering";

		return $dblokal->query($sql)->result();
	}

	function getMenuId($sistem=''){
		$dblokal  = $this->load->database("default", TRUE);
		$hasil ="";
		$sql = "SELECT * FROM t_mtr_cluster_net WHERE menu_path = '".$sistem."' and not parent_id=0 ";
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
		$sql = 'insert into t_mtr_cluster_net ('.$key.') values ('.$val.')';
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