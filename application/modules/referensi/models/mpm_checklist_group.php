<?php
class mpm_checklist_group extends CI_Model {
	function mpm_checklist_group(){
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
	    
		$data['getJudul']      	 	= "";//$this->mjadwal->getJudul($this->uri->segment(2));
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
	//new
	function getGridParent($parent_id){
		$dblokal  = $this->load->database("default", TRUE);
		
		 $sql="select *
			from t_mtr_checklist_group 
			where parent_id = $parent_id AND status='1'
			order by order_number ASC";

		return $dblokal->query($sql)->result();
	}
	
	
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','parent','group','order','desc','last','group_key');
		foreach ($input as $val)
			$$val = $this->input->post($val);
			 $this->db->select('checklist_group_id')->select('order_number')
            ->where('parent_id',$parent)
            ->where('order_number >= ',$order);
        $obsolete_order=$this->db->get('t_mtr_checklist_group')->result_array();
        //echo json_encode(($obsolete_order));
        foreach($obsolete_order as $key=>$val){

		  $this->db->update('t_mtr_checklist_group',array('order_number' => $val['order_number']+1),array('checklist_group_id'=>$val['checklist_group_id']));
		}
		if($last == 'on'){
				$lasts = 1 ;
			}else{
				$lasts = 0 ;
			}
		$data = array('parent_id' => $parent,
				  'group_name' => $group,
				  'order_number' => $order,
				  //'description' => $desc,
				  'is_last_child' => $lasts,
				  'group_key' => $group_key,
			      'status' =>1 );
		
		if($id == "") {
			$data['created_on'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			
			$dblokal->insert('t_mtr_checklist_group', $data);
		}else{
			$data['updated_on'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			
			$dblokal->where(array('checklist_group_id'=>$id));
			$dblokal->update('t_mtr_checklist_group', $data);
		}
	}
	############################################
	
	public function treeGrid(){
		$dblokal = $this->load->database("default", TRUE);
		if (isset($d['q'])){
            $q=$d['q'];
            $or_arr=array('t_mtr_site.site_code','t_trx_site_inspection_schedule.visit_type');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="(lower($val) like lower('%$q%')";
                else
                    $or_str.=" or lower($val) like lower('%$q%')";
            }
            $or_str.=")";
        }
		 $q = "SELECT * 
			  FROM t_mtr_checklist_group 
			  WHERE parent_id = '1' and status='1' ORDER By t_mtr_checklist_group.order_number ASC";
		
		$query = $dblokal->query( $q );
        return $query;
	}
	public function has_child($id){
		//die($id);
		// if($id==''){
		// $id=9000;
		// } 
		$dblokal = $this->load->database("default", TRUE);
		
		$rs = $dblokal->query("
		select count(*) AS jml FROM t_mtr_checklist_group 
			  WHERE parent_id =$id AND status='1'");
			  
		$row = $rs->row();
		$hasil = $row->jml;
		
		$rs2 = $dblokal->query("
		select count(*) AS jml FROM t_mtr_checklist 
			  WHERE checklist_group =$id AND status='1'");
			  
		$row2 = $rs2->row();
		$hasil2 = $row2->jml;
		
		$hasil3 = $hasil + $hasil2;
		
		return $hasil3 > 0 ? true : false;
			  
		// $row = $rs->row();
		// $hasil = $row->jml;
		// return $hasil> 0 ? true : false;
	}

/*
	public function treeGrido($offset,$rows){
		$dblokal = $this->load->database("default", TRUE);
		 $q = "SELECT * 
			  FROM t_mtr_checklist_group 
			  WHERE parent_id = '1' and status='1'  order by  t_mtr_checklist_group.order_number asc LIMIT $rows OFFSET $offset";
		
		$query = $dblokal->query( $q );
        return $query;
	}


	public function treeGrids($id){
		$dblokal = $this->load->database("default", TRUE);
		
		 $q = "SELECT * 
			  FROM t_mtr_checklist_group 
			  WHERE parent_id = '$id' and status='1' order by  t_mtr_checklist_group.order_number asc ";
		
		$query = $dblokal->query( $q );
        return $query;
	}*/

		

	public function treeGrido($offset,$rows){
		$dblokal = $this->load->database("default", TRUE);
		// checklist_group_id||'_'||checklist_group_id tree_id,		// -- checklist_group_id || '-' || checklist_group_id tree_id,
		

/*
		 $q = "SELECT 
			 checklist_group_id || '-' || checklist_group_id tree_id,
			 -- '' || checklist_group_id tree_id, 
			 checklist_group_id group_id, 
			 group_name tree_name, 
			 order_number, 
			 null as interface, 
			 status, 'grouping' as tree_type 
			 FROM t_mtr_checklist_group 
			 WHERE parent_id = '1' and status='1'  order by  t_mtr_checklist_group.order_number asc LIMIT $rows OFFSET $offset ";
	*/

		 $q = "SELECT
		cg.checklist_group_id || '-' || ck.checklist_id tree_id,
		-- checklist_group AS group_id,
		NULL AS group_id,
		ck.checklist_id,
		-- 0 group_id,
		ck.checklist_name tree_name,
		ck.order_number,
		ck.interface,
		ck.status,
		'checklist' AS tree_type
	FROM
		t_mtr_checklist ck
	LEFT JOIN t_mtr_checklist_group cg ON ck.checklist_group = cg.checklist_group_id
	WHERE
		cg.status = 1
	AND ck.status = 1
	AND ck.checklist_group = '1'

	UNION ALL
		SELECT
			checklist_group_id || '-' || checklist_group_id tree_id,
			checklist_group_id group_id,
			NULL AS checklist_id,
			group_name tree_name,
			order_number,
			NULL AS interface,
			status,
			'grouping' AS tree_type
		FROM
			t_mtr_checklist_group
		WHERE
			parent_id = '1'
		AND status = '1'
		ORDER BY order_number asc
		";
	
		 // die($q);
		$query = $dblokal->query( $q );
        return $query;
	}
	public function treeDetail($id){
		$dblokal = $this->load->database("default", TRUE);
		
		$q = "SELECT
	cg.checklist_group_id || '-' || ck.checklist_id tree_id,
	-- '1' || ck.checklist_id tree_id,
	NULL group_id,
	ck.checklist_id,
	ck.checklist_name tree_name,
	ck.order_number,
	ck.interface,
	'checklist' AS tree_type
FROM
	t_mtr_checklist ck
LEFT JOIN t_mtr_checklist_group cg ON ck.checklist_group = cg.checklist_group_id
WHERE
	cg.status = 1
AND ck.status = 1
AND ck.checklist_group = '$id'

UNION

SELECT
	cg.checklist_group_id || '-' || cg.checklist_group_id tree_id,
	-- '' || cg.checklist_group_id tree_id,
	cg.checklist_group_id group_id,
	NULL checklist_id,
	cg.group_name tree_name,
	cg.order_number,
	NULL AS interface,
	'grouping' AS tree_type
FROM
	t_mtr_checklist_group cg
WHERE
	cg.status = 1
AND cg.parent_id = '$id'
ORDER BY
	tree_id
		";
			//die($q);
		
		$query = $dblokal->query( $q );
        return $query;
	}
	

	###########################################################################
	function getpm_checklist_groupDetail( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT * FROM t_mtr_checklist_group WHERE checklist_group_id = $id ";
		$query = $dblokal->query($q);
		return $query->result();
	}
	public function deleteData(){
	/*	$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'lookup_key', 'lookup_desc', 'order');
		foreach ($input as $val)
			$$val = $this->input->post($val);
			
		$dblokal->where( array('checklist_group_id'=>$id) );
		$dblokal->update('t_mtr_checklist_group',array('status'=>0));
		$obsolete_order=$this->db->get('t_mtr_checklist_group')->result_array();
        foreach($obsolete_order as $key=>$val){
			
		  $this->db->update('t_mtr_checklist_group',array('order_number' => $val['order_number']-1),array('order_number'>0),array('checklist_group_id'=>$val['checklist_group_id']));
			
		}
	
	}*/
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'lookup_key', 'lookup_desc', 'order','parent','group','order','desc','last','group_key');
		foreach ($input as $val)
			$$val = $this->input->post($val);
			    
        $parent=$this->mglobal->showData('parent_id','t_mtr_checklist_group',array('checklist_group_id'=>$id));
        $order=$this->mglobal->showData('order_number','t_mtr_checklist_group',array('checklist_group_id'=>$id));
          
        $this->db->select('checklist_group_id')->select('order_number')
			 ->where('parent_id',$parent)
			->where('order_number >= ',$order);
		$dblokal->where( array('checklist_group_id'=>$id) );
		$dblokal->update('t_mtr_checklist_group',array('status'=>0));
		$obsolete_order=$this->db->get('t_mtr_checklist_group')->result_array();
        foreach($obsolete_order as $key=>$val){
			$this->db->update('t_mtr_checklist_group',array('order_number' => $val['order_number']-1),array('checklist_group_id'=>$val['checklist_group_id']));
		}
	
	/*
			$dblokal = $this->load->database("default", TRUE);
		$input = array('id','parent','group','order','desc','last','group_key');
		foreach ($input as $val)
			$$val = $this->input->post($val);
			 $this->db->select('checklist_group_id')->select('order_number')
            ->where('parent_id',$parent)
            ->where('order_number >= ',$order);
        $obsolete_order=$this->db->get('t_mtr_checklist_group')->result_array();
        //echo json_encode(($obsolete_order));
        foreach($obsolete_order as $key=>$val){

		  $this->db->update('t_mtr_checklist_group',array('order_number' => $val['order_number']+1),array('checklist_group_id'=>$val['checklist_group_id']));
		}
	*/
	}
	public function loadData() {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND LOWER(group_name) LIKE LOWER('%$query%')";
		
		$q = "SELECT * FROM t_mtr_checklist_group where parent_id in (SELECT checklist_group_id FROM t_mtr_checklist_group WHERE parent_id='1') or parent_id='1' ORDER BY order_number ASC  $where";
	
		/*$q = "SELECT aa.parent_id,aa.order_number
			FROM t_mtr_checklist_group aa
			WHERE parent_id IN (SELECT aa.checklist_group_id FROM t_mtr_checklist_group aa WHERE aa.parent_id='1') or parent_id='1'
			ORDER BY order_number ASC $where";*/
		
			
		$query = $dblokal->query( $q );
		$total = $query->num_rows;
		$npage = ceil($total / $dataperpage);
		 
		$start = $curpage * $dataperpage;
		$end = $start + $dataperpage;
		
		$q .= " ORDER BY group_name
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
		      'id' => $row->checklist_group_id,
		      'group_name' => $row->group_name
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
		$sql = "SELECT * FROM t_mtr_pm_checklist_group WHERE alias = '". $sistem ."' AND parent_id=0 ";
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
		// return $this->db->query("SELECT * from t_mtr_pm_checklist_group where parent_id = $parent_id")->result();
		if ($group_id ==''){
			$group_id = 0;
		}
		 $sql="select mn.id, mn.name as menu_name,rl.*  from t_mtr_pm_checklist_group mn
			left join t_mtr_user_role rl on mn.id = rl.menu_id and rl.user_group_id = $group_id
			LEFT JOIN t_mtr_user_group grp on grp.user_group_id = rl.user_group_id 
			where mn.parent_id = $parent_id and mn.published = 1
			order by mn.ordering";

		return $dblokal->query($sql)->result();
	}

	function getMenuId($sistem=''){
		$dblokal  = $this->load->database("default", TRUE);
		$hasil ="";
		$sql = "SELECT * FROM t_mtr_pm_checklist_group WHERE menu_path = '".$sistem."' and not parent_id=0 ";
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
		$sql = 'insert into t_mtr_pm_checklist_group ('.$key.') values ('.$val.')';
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
	############################################
	// dipindah dari model mpm_checklist
/*
	function getpm_checklistDetail( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT *
					FROM t_mtr_checklist aa
					JOIN t_mtr_checklist_group cc ON aa.checklist_group=cc.checklist_group_id
					LEFT JOIN t_mtr_checklist_answer bb ON aa.checklist_id=bb.checklist_id
					WHERE aa.checklist_id = $id ";
					// die($q);
		$query = $dblokal->query($q);
		return $query->result();
	}*/

	
	function getpm_checklistDetail( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT *
					FROM t_mtr_checklist aa
					JOIN t_mtr_checklist_group cc ON aa.checklist_group=cc.checklist_group_id
					WHERE checklist_id = $id ";
					// die($q);

		$query = $dblokal->query($q);
		return $query->result();
	}
	
	function getpm_checklistDetail2( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT checklist_id, option_id, option, is_critical FROM t_mtr_checklist_answer WHERE checklist_id = $id ";
		$query = $dblokal->query($q);
		return $query->result_array();
	}
	
		public function checkChild($id){
		$dblokal = $this->load->database("default", TRUE);
		$q = " SELECT count (parent_id) as jumlah from t_mtr_checklist_group where parent_id='". (int) $id."' and status='1'";
		$jumlah = 0;
		$query =$dblokal->query($q);		
		foreach ($query->result_array() as $v){
			$jumlah = $v['jumlah'];
		}
		return $jumlah;
				
	}
		
		public function saveDataChecklist(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'group_id','descr','property_id','urutan','name','interface', 'has_photo', 'dedicated', 'totphoto','option','mandatory');
		foreach ($input as $val)
			$$val = $this->input->post($val);
        $this->db->select('checklist_id')->select('order_number')
            ->where('checklist_group',$name)
            ->where('order_number >= ',$urutan);
        $obsolete_order=$this->db->get('t_mtr_checklist')->result_array();
        //echo json_encode(($obsolete_order));
        foreach($obsolete_order as $key=>$val){

		  $this->db->update('t_mtr_checklist',array('order_number' => $val['order_number']+1),array('checklist_id'=>$val['checklist_id']));
		}
		//$total_photo =($totphoto == "") ? "0" : $totphoto;
		
        $data = array('checklist_group' => $name,
				  'checklist_name' => $descr,
				  'checklist_type' => $property_id,
				  'order_number' => $urutan,
				  'has_photo' => $has_photo,
				  'dedicated_photo' => $dedicated,
				  'total_photo' => (int) $totphoto,
				  'interface' => $interface,
				  'mandatory' => $mandatory,
			      'status' =>1 );
		
		if($id == "") {

			$is_critical = $this->input->post('is_critical');
		
			$data['created_on'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			
			$dblokal->insert('t_mtr_checklist', $data);
			$dblokal->insert_id();
			$idku = $dblokal->insert_id();
			
			for ($i = 0; $i < count($option); $i++) {
						if($option[$i]!= ""){
							
							if($is_critical[$i] == 1){
								$is_critical[$i] = $is_critical[$i];
							} else {
								$is_critical[$i] = 0;
							}
							
							$queryInsert = "INSERT INTO t_mtr_checklist_answer (option,checklist_id,is_critical) Values ('".$option[$i]."','".$idku."','".$is_critical[$i]."')";
							$dblokal->query($queryInsert);
							}
					}
		}else{
			$option = $this->input->post('option');
			
			$is_critical = $this->input->post('is_critical');
			
			// print_r($is_critical);
			// die();						$data['updated_on'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			for ($i = 0; $i < count($option); $i++) {
							$queryInsert = "DELETE FROM t_mtr_checklist_answer WHERE checklist_id='".$id."'";
							$dblokal->query($queryInsert);
					}
			
			$dblokal->where(array('checklist_id'=>$id));
			$dblokal->update('t_mtr_checklist', $data);
			for ($i = 0; $i < count($option); $i++) {
				
							if($is_critical[$i] == 1){
								$is_critical[$i] = $is_critical[$i];
							} else {
								$is_critical[$i] = 0;
							}
							
							$queryInsert = "INSERT INTO t_mtr_checklist_answer (option,checklist_id,is_critical) Values ('".$option[$i]."','".$id."','".$is_critical[$i]."')";
							$dblokal->query($queryInsert);
					}
		}
	}

		public function deleteDataChecklist(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'name', 'descr', 'urutan', 'property_id','urutan','interface');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
			$name=$this->mglobal->showData('checklist_group','t_mtr_checklist',array('checklist_id'=>$id));
			$order=$this->mglobal->showData('order_number','t_mtr_checklist',array('checklist_id'=>$id));
        
        
        	 $this->db->select('checklist_id')->select('order_number')
			 ->where('checklist_group',$name)
			 ->where('order_number >= ',$order);
		
		$dblokal->where( array('checklist_id'=>$id) );
		$dblokal->update('t_mtr_checklist',array('status'=>0));
		
		$obsolete_order=$this->db->get('t_mtr_checklist')->result_array();
        foreach($obsolete_order as $key=>$val){
			
		  $this->db->update('t_mtr_checklist',array('order_number' => $val['order_number']-1),array('checklist_id'=>$val['checklist_id']));
			
		}
	}
		

public function comboBox(){
	$this->db->select('checklist_group_id group_id, group_name, parent_id');
    $this->db->where('checklist_group_id !=', 1);
    $this->db->where('group_key', 'pm');
    $this->db->where('status', 1);
    $this->db->order_by('checklist_group_id');
    $q = $this->db->get('t_mtr_checklist_group');
	
	echo $this->db->last_query();
    return $q->result_array();
}
}
?>