<?php
class mpreparation_used extends CI_Model {
	function mpreparation_used(){
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
	
	// function getMenu(){
		// $dblokal = $this->load->database("default", TRUE);
		// $q = "SELECT preparation_type_id, descr as tdescr FROM t_trx_pm_preparation_used where status='1' order by descr ASC";
		// $query = $dblokal->query($q);
		// return $query->result();
	// }
	
	############################################
	//datagrid jeasyui
	public function getListVisits($d=NULL,$q, $year_ops, $opr_month){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);       
            $or_arr=array('mu.user_name');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="AND (lower($val) like lower('%$q%')";
                else
                    $or_str.="or lower($val) like lower('%$q%')";
            }
            $or_str.=")"; 
	
			$q = "SELECT mu.user_name AS user_name, pu.created_by,pu.created_on 
					FROM t_trx_pm_preparation_used pu
					LEFT JOIN t_mtr_user mu on pu.created_by=mu.user_id
					WHERE date_part('year', pu.created_on) = $year_ops AND date_part('month', pu.created_on) = $opr_month $or_str
					GROUP BY mu.user_name,pu.created_by,pu.created_on ";
			
			
		$query = $dblokal->query( $q );
        return $query;
    }
	 public function getListVisit($offset=0,$rows=0,$q, $year_ops, $opr_month){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);       
            $or_arr=array('mu.user_name');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="AND (lower($val) like lower('%$q%')";
                else
                    $or_str.="or lower($val) like lower('%$q%')";
            }
            $or_str.=")";   
	
			$q = "SELECT mu.user_name AS user_name, pu.created_by,pu.created_on 
					FROM t_trx_pm_preparation_used pu
					LEFT JOIN t_mtr_user mu on pu.created_by=mu.user_id
					WHERE date_part('year', pu.created_on) = $year_ops AND date_part('month', pu.created_on) = $opr_month $or_str
					GROUP BY mu.user_name,pu.created_by,pu.created_on 
					ORDER by created_on desc ";
			
					
			if ($rows!=0){
				$q .= "LIMIT ".$rows." OFFSET ".$offset." ";
			}
		$query = $dblokal->query( $q );
        return $query;
    }
	public function getListDetails($id,$date,$offset=0,$rows=0){
		$dblokal = $this->load->database("default", TRUE);
		
		$q = "SELECT aa.created_on,bb.descr,cc.user_name,aa.created_by, aa.jumlah  
				FROM t_trx_pm_preparation_used aa, t_mtr_pm_preparation_item bb, t_mtr_user cc 
				where aa.pt_id=bb.pt_id AND aa.created_by=cc.user_id AND aa.created_by='$id' and aa.created_on='$date'";
			
									
		if ($rows!=0){
            $q .= "LIMIT ".$rows." OFFSET ".$offset." ";
        }
					
		$query = $dblokal->query( $q );
        return $query;
    }
	
	############################################
	// function getchecklist_resultDetailsas($pmid){
		// $dblokal = $this->load->database("default", TRUE);
		// $idkuk = intval($pmid);
		 // $query2 = "SELECT cc.site_code, aa.descr, bb.jumlah, dd.visit_id
									// FROM t_mtr_pm_preparation_item aa 
									// LEFT JOIN t_trx_pm_preparation_used bb ON aa.pt_id=bb.pt_id
									// JOIN t_mtr_site cc UHVGGVY
									// JOIN t_trx_pm_schedule dd ON dd.visit_id=bb.prep_used_id and dd.site_id=cc.site_id
									// WHERE dd.visit_id=$pmid";
		
		// // echo $dblokal->last_query();
		// // print_r ($query->result_array() );
		// $querys = $dblokal->query( $query2 );
		// $hasil2 = array(
			// 'data' => array()
		// );
		
		
		  // foreach ($querys->result() as $row2) {
		    // $hasil2['data'][] = array(
		      // 'site_code' => $row2->site_code,
		      // 'descr' => $row2->descr,
		      // 'jumlah' => $row2->jumlah,
		      // 'pm_id' => $row2->visit_id
		    // );
		  // }
		
		// echo json_encode($hasil2, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
	// }
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','type','descr');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$data = array('type_id' => $type,
			      'descr' => $descr,
			      'status'=>1 );
		
		if($id == "") {
			
			
			$dblokal->insert('t_mtr_pm_preparation_item', $data);
			$dblokal->insert_id();
		}else{
			
			
			$dblokal->where(array('pt_id'=>$id));
			$dblokal->update('t_mtr_pm_preparation_item', $data);
		}
	}
	
	public function deleteData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'descr');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$dblokal->where( array('pt_id'=>$id) );
		$dblokal->update('t_mtr_pm_preparation_item',array('status'=>0));
	}
	
	public function loadData() {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND LOWER(cc.user_name) LIKE LOWER('%$query%')";
		
		$q = "SELECT bb.descr, aa.prep_used_id, aa.jumlah, cc.user_name, aa.created_on as dates 
				FROM t_trx_pm_preparation_used aa,t_mtr_pm_preparation_item bb, t_mtr_user cc 
				WHERE aa.created_by=cc.user_id AND aa.pt_id=bb.pt_id $where";
			
		$query = $dblokal->query( $q );
		$total = $query->num_rows;
		$npage = ceil($total / $dataperpage);
		 
		$start = $curpage * $dataperpage;
		$end = $start + $dataperpage;
		
		$q .= " ORDER BY aa.created_on DESC
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
		      'prep_used_id' => $row->prep_used_id,
		      'descr' => $row->descr,
		      'jumlah' => $row->jumlah,
		      'user_name' => $row->user_name,
		      'dates' => $row->dates
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
		$sql = "SELECT * FROM t_mtr_pm_preparation_item WHERE alias = '". $sistem ."' AND parent_id=0 ";
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
		// return $this->db->query("SELECT * from t_mtr_pm_preparation_item where parent_id = $parent_id")->result();
		if ($group_id ==''){
			$group_id = 0;
		}
		 $sql="select mn.id, mn.name as menu_name,rl.*  from t_mtr_pm_preparation_item mn
			left join t_mtr_user_role rl on mn.id = rl.menu_id and rl.user_group_id = $group_id
			LEFT JOIN t_mtr_user_group grp on grp.user_group_id = rl.user_group_id 
			where mn.parent_id = $parent_id and mn.published = 1
			order by mn.ordering";

		return $dblokal->query($sql)->result();
	}

	function getMenuId($sistem=''){
		$dblokal  = $this->load->database("default", TRUE);
		$hasil ="";
		$sql = "SELECT * FROM t_mtr_pm_preparation_item WHERE menu_path = '".$sistem."' and not parent_id=0 ";
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
		$sql = 'insert into t_mtr_pm_preparation_item ('.$key.') values ('.$val.')';
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