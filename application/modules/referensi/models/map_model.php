<?php

class Map_model extends CI_Model {
	
	
	public function get_all_tower_provider()
	{
		$query = $this->db->where('active',1);
		$query = $this->db->get('t_mtr_tower_provider');//edited 30 okt 2013
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function get_koord_nom_site($site_id){
	    $lat=0;
	    $long=0;
	    $this->db->where('site_id',$site_id);
	    $query=$this->db->get('t_mtr_site');
	    $data_koord = $query->result_array();
	    $baris=$query->num_rows();    

	    if($baris>0){
	        foreach ($data_koord as $ko){
	            $lat=$ko["nom_latitude"];
	            $long=$ko["nom_longitude"];
	        }
	    }
	    $arr = array("lat" => $lat, "long" => $long);
	    return $arr;
	}
	
	function get_koord_nom_site_code($site_id){
	    $lat=0;
	    $long=0;
	    $this->db->where('site_code',$site_id);
	    $query=$this->db->get('t_mtr_site');
	    $data_koord = $query->result_array();
	    $baris=$query->num_rows();    

	    if($baris>0){
	        foreach ($data_koord as $ko){
	            $lat=$ko["nom_latitude"];
	            $long=$ko["nom_longitude"];
	        }
	    }
	    $arr = array("lat" => $lat, "long" => $long);
	    return $arr;
	}
	
	function update_kandidat($id){
		$data = array(
               'candidate_status' => 1,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('tp_id', $id);//edited 31 okt
		$this->db->update('t_mtr_tower_provider', $data); 
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  

	}
	
	function update_release_tp($id){
		$data = array(
               'candidate_status' => 0,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('tp_id', $id);
		$this->db->update('t_mtr_tower_provider', $data); //edited 31 okt 2013
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  

	}
	
	public function delete_kandidat_sitetp($data){
		$this->db->delete('t_site_tp_kandidat', $data); 
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  

	}

	function update_disewa($id){  //GA DIPAKE
		$data = array(
               'disewa' => 1
            );

		$this->db->where('tp_id', $id);
		$this->db->update('t_mtr_tower_provider', $data); 

		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  
	}
	
	public function update_pemenang_site_kandidat($nomsite_id, $site_id_tp){ // INI YG DIPAKE
		$data = array(
               'status' => 3,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('site_id', $nomsite_id);
		$this->db->where('tp_id', $site_id_tp);
		$this->db->update('t_site_tp_kandidat',$data); 

		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  
	}
	
	public function update_pemenang_tp($site_id_tp){ // INI YG DIPAKE
		$data = array(
               'candidate_status' => 3,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('tp_id', $site_id_tp);
		$this->db->update('t_mtr_tower_provider',$data); //edited 31 okt 2013

		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  
	}
	
	public function update_pemenang_master_site($nomsite_id, $site_id_tp){ //INI YG DIPAKE
		$this->db->select('latitude,longitude,address');//edited 31 okt 2013
		$this->db->where('tp_id',$site_id_tp);
		$query=$this->db->get('t_mtr_tower_provider');
	    $data_tp = $query->result_array();
		
	    if($query->num_rows()>0){
	        foreach ($data_tp as $row){
	            $lat=$row["latitude"];
	            $long=$row["longitude"];
	            $address=$row["address"];
	            $status=1;
	        }
	    }

		$data = array(
               'latitude' => $lat,
               'longitude' => $long,
               'status' => 1,
               'site_address' => $address,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('site_id', $nomsite_id);
		$this->db->update('t_mtr_site',$data); 

		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  	
	}
	
	public function batal_pemenang_site_kandidat($nomsite_id, $site_id_tp){ // INI YG DIPAKE
		$data = array(
               'status' => 2,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('site_id', $nomsite_id);
		$this->db->where('tp_id', $site_id_tp);
		$this->db->update('t_site_tp_kandidat',$data); 

		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  
	}
	
	public function batal_pemenang_tp($site_id_tp){ // INI YG DIPAKE
		$data = array(
               'candidate_status' => 2,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('tp_id', $site_id_tp);
		$this->db->update('t_mtr_tower_provider',$data); //edited 31 okt 2013

		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  
	}
	
	public function batal_pemenang_master_site($nomsite_id, $site_id_tp){ //INI YG DIPAKE
		$this->db->select('nom_latitude,nom_longitude,nom_address');
		$this->db->where('site_id',$nomsite_id);
		$query=$this->db->get('t_mtr_site');
	    $data_tp = $query->result_array();
		
	    if($query->num_rows()>0){
	        foreach ($data_tp as $row){
	            $lat=$row["nom_latitude"];
	            $long=$row["nom_longitude"];
	            $address=$row["nom_address"];
	        }
	    }

		$data = array(
               'latitude' => $lat,
               'longitude' => $long,
               'site_address' => $address,
               'status' => 0,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('site_id', $nomsite_id);
		$this->db->update('t_mtr_site',$data); 

		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  	
	}
	
	public function get_tower_provider($site_id)
	{
		$query = $this->db->where('tp_id',$site_id);// edited 31 okt 2013
		$query = $this->db->where('active',1);
		$query = $this->db->get('t_mtr_tower_provider');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_tower_provider_filter($site_id, $status)//kyknya ga dipake
	{
		//1=semua, 2=Belum Kandidat, 3=Kandidat, 4=Disewa

		if($status==2){
			$query = $this->db->where('kandidat',0);
		} else if($status==3){
			$query = $this->db->where('kandidat',1);			
			$query = $this->db->where('disewa',0);			
		} else if($status==4){
			$query = $this->db->where('disewa',1);			
		}

		$query = $this->db->where('tp_id',$site_id);//edited 31 okt 2013
		$query = $this->db->where('active',1);
		$query = $this->db->get('t_mtr_tower_provider');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_site_name(){
		$this->db->select('site_id, site_code, site_name');
		$this->db->where('status', 0);
		$this->db->where('active', 1);
		$query= $this->db->get('t_mtr_site');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_site_name_for_search4(){
		$this->db->select('site_id, site_code, site_name');
		$this->db->where('status', 0);
		$this->db->where('active', 1);
		$query= $this->db->get('t_mtr_site');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	
	
	function get_site_name_for_search($d=NULL,$rows=0,$offset=0) {
		
		$dblokal = $this->load->database("default", TRUE);
		$qrs = "";
		$qr = "select ms.site_id, ms.site_code, ms.site_name, ms.vendor_id, mr.territory_name
			from t_mtr_site ms
			left join t_mtr_territory mr on ms.region_id=mr.territory_id
			where ms.site_id is not null AND ms.active=1 AND ms.status IN(1,0) ";
		
		if (isset($d)){
			$q=$d;
			$or_arr=array('ms.site_code','ms.site_name', 'ms.vendor_id', 'mr.territory_name');
			$or_str="";
			
			foreach ($or_arr as $val) {
				if ($or_str=="")
					$or_str.="($val ilike '%".$q."%'";
				else
					$or_str.=" or $val ilike '%".$q."%'";
			}
			$or_str.=")";
			$qr .= "AND ".$or_str;
		}		
       
		if ($rows!=0){
			$qrs .= $qr." LIMIT ".$rows." OFFSET ".$offset." ";
		}

		$queryc = $dblokal->query($qr);
		$total = $queryc->num_rows;
		$query = $dblokal->query($qrs);
		$result["total"] = $total;
		$items = array();
		
		foreach ($query->result() as $row) {
			 array_push($items, $row);				
		}
        
		$result["rows"] = $items;
		echo json_encode($result);
	}
	

	public function get_site_by_id($site_id=3){
		$this->db->select('site_id, site_name, nom_latitude as latitude, nom_longitude as longitude, nom_address as site_address, site_code');
		$this->db->where('site_id', $site_id);
		$query= $this->db->get('t_mtr_site');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_site_by_code($site_code){
		$this->db->select('site_id, site_name, nom_latitude as latitude, nom_longitude as longitude, nom_address as site_address, site_code');
		$this->db->where('site_code', $site_code);
		$query= $this->db->get('t_mtr_site');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}	


	public function get_tp_by_radius($site_id, $radius, $nomlat, $nomlong){
		$radiuskm=$radius/1000;
		$query=$this->db->query("
                                        (select site_id as id, site_code, vendor_id, 'nomsite' as tp_name, ' ' as site_id_tp,0 as rank, site_name,
                                        site_address as actual_address,latitude as actual_latitude, longitude as actual_longitude,
                                        nom_address as address,2 as kandidat, 
                                        nom_latitude as latitude, nom_longitude as longitude, 0 as partner_id,0 as distance 
                                        from t_mtr_site
                                        where site_id=".$site_id.")
                                        UNION
                                        (select * from (SELECT a.tp_id AS id, ''as site_code, '-' as vendor_id, b.company_name as tp_name, a.tp_code as site_id_tp,b.commercial_rank as rank, a.tp_site_name,
                                        'aaa' as actual_address,0 as actual_latitude, 0 as actual_longitude,
                                        a.address,a.candidate_status, a.latitude AS latitude, a.longitude,b.company_id as partner_id,
                                        ( 6371 * acos( cos( radians(".$nomlat.") ) * cos( radians( a.latitude ) ) * cos( radians( a.longitude ) - radians(".$nomlong.") ) + sin( radians(".$nomlat.") ) * sin( radians( a.latitude ) ) ) )AS distance
                                        FROM t_mtr_tower_provider AS a LEFT JOIN t_mtr_tp_company AS b on a.company_id=b.company_id) tp
                                        WHERE tp.distance <".$radiuskm.")
                                        UNION 
                                        (select * from 
                                        (select site_id as id, site_code, vendor_id, 'smfsite' as tp_name, ' ' as site_id_tp,0 as rank, site_name, site_address as actual_address,latitude as actual_latitude, longitude as actual_longitude, 
                                        nom_address as address,2 as kandidat, nom_latitude as latitude, nom_longitude as longitude, 0 as partner_id,
                                        ( 6371 * acos( cos( radians(".$nomlat.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$nomlong.") ) + sin( radians(".$nomlat.") ) * sin( radians( latitude ) ) ) )AS distance 
                                        from t_mtr_site ) smf where smf.distance <".$radiuskm.")									
                                        ");
		return $query->result();
	}
	
	public function get_marker(){
            $sql = "SELECT company_name, company_id, gis_marker FROM t_mtr_tp_company ORDER BY company_name ASC";
            $query = $this->db->query($sql);
            return $query->result_array();
            
//            $this->db->select('company_name, company_id');
//            $this->db->order_by('company_name', 'asc');
//            $query= $this->db->get('t_mtr_tp_company');
//            if ($query->num_rows()) {
//                    return $query->result_array();
//            } else {
//                    return FALSE;
//            }
//		$this->db->select('partner_name, gis_marker, partner_id');
//		$this->db->order_by('partner_name', 'asc');
//		$query= $this->db->get('t_mtr_partner');
//		if ($query->num_rows()) {
//			return $query->result_array();
//		} else {
//			return FALSE;
//		}
                
	}	
	
	public function add_kandidat($data){

	$this->db->insert('t_site_tp_kandidat', $data); 
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  

	}
	
	public function get_site_kandidat($site_id = ''){
		if (!empty($site_id)) {
			$query=$this->db->query("SELECT b.site_id AS nomsite_id,b.site_code, b.site_name, c.tp_code, c.tp_site_name, c.latitude, c.longitude, c.address, 	a.status, c.tp_id
							FROM t_site_tp_kandidat as a
							LEFT JOIN t_mtr_site as b on a.site_id=b.site_id
							LEFT JOIN t_mtr_tower_provider as c on a.tp_id=c.tp_id
							where a.site_id=".$site_id);
		} else {
			$query=$this->db->query("SELECT
										b.site_id AS nomsite_id,
										b.site_code,
										b.site_name,
										C .tp_code,
										C .tp_company_name,
										C .latitude,
										C .longitude,
										C .address,
										A .status,
										C . tp_id
									FROM
										t_site_tp_kandidat AS A
									LEFT JOIN t_mtr_site AS b ON A.site_id = b.site_id
									LEFT JOIN t_mtr_tower_provider AS C ON A.tp_id = C.tp_id");
		}
		
		return $query->result_array();
	}
	
	public function get_site_kandidat_by_siteid(){
		
	}
	
	public function get_tp_kandidat_by_siteid($site_id, $nomlat, $nomlong){
	$query=$this->db->query("(SELECT site_id AS id, 'nomsite' AS tp_name, site_name,nom_address AS address,2 AS kandidat, nom_latitude as latitude, nom_longitude as longitude, 0 AS distance FROM t_mtr_site
							WHERE site_id=".$site_id.")
							UNION
							(SELECT c.no AS id, c.tp_name, c.site_id AS site_name, c.address, c.kandidat, c.lattitude, c.longitude,
							(6371 * ACOS( COS( RADIANS(".$nomlat.") ) * COS( RADIANS( c.lattitude ) ) * COS( RADIANS( c.longitude ) - RADIANS(".$nomlong.") ) + 
							SIN( RADIANS(".$nomlat.") ) * SIN( RADIANS( c.lattitude ) ) ) )  AS distance									
							FROM t_site_tp_kandidat AS a
							LEFT JOIN t_mtr_site AS b on a.site_id=b.site_id
							LEFT JOIN t_mtr_tower_provider2 AS c on a.site_id_tp=c.no
							WHERE a.site_id=".$site_id.")");	
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_tp_by_id($tp_id){
		$this->db->where('tp_id', $tp_id);
		$query= $this->db->get('t_mtr_tower_provider');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}	
/*
	function update_status_verified_tp($tp_id,$nomsite_id){//GA DIPAKE
		$data = array(
               'status' => 2,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('site_id_tp', $tp_id);
		$this->db->where('site_id', $nomsite_id);
		$this->db->update('t_site_tp_kandidat', $data); 
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  

	}
	*/
	function update_status_pemenang_tp($tp_id,$nomsite_id){
		$data = array(
               'status' => 3,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('tp_id', $tp_id);
		$this->db->where('site_id', $nomsite_id);
		$this->db->update('t_site_tp_kandidat', $data); 
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  

	}

	function cek_site_kandidat($site_id, $site_id_tp){
		$this->db->where('site_id', $site_id);
		$this->db->where('tp_id', $site_id_tp);
		$query= $this->db->get('t_site_tp_kandidat');
		if ($query->num_rows()>0) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}
	
	public function get_site_link(){
		$query= $this->db->query("SELECT t_site_link.*, t_mtr_site.site_address, t_mtr_site.site_name, t_mtr_site.vendor_id
					 FROM t_site_link
					 LEFT JOIN t_mtr_site ON t_mtr_site.site_code = t_site_link.site_id1");
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return null;
		}
	}
	
	//dody@9dec
	public function get_topology_link( $region="" ){
		
		$where = "";
		if($region <>""){
			$where = "ne.region_id='$region' AND fe.region_id='$region'";
		}
		$query= $this->db->query("SELECT eq.link_code_id, eq.ant_model, ne.latitude as latitude1, ne.longitude as longitude1, fe.latitude as latitude2, fe.longitude as longitude2,
					ne.site_address, ne.site_code, ne.vendor_id, ne.site_name,
                                        -- lo.lookup_desc, 
                                        eq.capacity, eq.frequency , 
					eq.diameter, eq_ne.frequency as frequency2, eq_ne.diameter as diameter2
					FROM t_mtr_site_l_equipment_mw as eq
					LEFT JOIN t_mtr_site as ne ON ne.site_id = eq.site_id_ne
					LEFT JOIN t_mtr_site as fe ON fe.site_id = eq.site_id_fe
					LEFT JOIN t_mtr_site_l_equipment_mw as eq_ne ON eq_ne.equipment_id = eq.site_id_ne
					-- LEFT JOIN t_mtr_lookup lo on eq.link_type=CAST(lo.lookup_value as integer)
					where 
                                        -- eq.link_type=1 
                                        -- and 
                                        -- lo.lookup_key= 'transmission_info_type' 
                        $where ");
		return $query->result();
	}

	public function load_kandidat_tp(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND alias LIKE '%$query%'";
		
		$q = "SELECT mn.\"id\" AS menu_id,mn.name, mn.alias, mn.parent_id, rl.menu_view,
				grp.user_group_name,grp.user_group_id 
			FROM t_mtr_menu mn 
			JOIN t_mtr_user_role rl ON mn.\"id\"=rl.menu_id 
			JOIN t_mtr_user_group grp ON grp.user_group_id = rl.user_group_id 
			WHERE mn.published = 1
			$where";
			
		$query = $dblokal->query( $q );
		$total = $query->num_rows;
		$npage = ceil($total / $dataperpage);
		 
		$start = $curpage * $dataperpage;
		$end = $start + $dataperpage;
		
		$q .= " ORDER BY mn.id
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
		      'menu_id' => $row->menu_id,
		      'name' => $row->name,
		      'alias' => $row->alias
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
	
	function get_site_link_tree_level2(){
		$query = $this->db->query('SELECT vendor_id1,vendor_id2,level_child FROM t_site_link
							where level_child = 2
							');
				
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return null;
		}
	 
	 }
	
	 function get_site_link_tree_child($parent){
		$query = $this->db->query("SELECT vendor_id1,vendor_id2,level_child FROM t_site_link
							where vendor_id2='".$parent."' ");
				
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return null;
		}
	 
	 }

	function update_verified_site_kandidat($nomsite_id, $site_id_tp){
		$data = array(
               'status' => 2,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('site_id', $nomsite_id);
		$this->db->where('tp_id', $site_id_tp);
		$this->db->update('t_site_tp_kandidat',$data); 

		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  	
	}

	function update_verified_tp($id){
		$data = array(
               'candidate_status' => 2,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('tp_id', $id);
		$this->db->update('t_mtr_tower_provider', $data); 
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  
	
	}	
	
	function update_failed_site_kandidat($nomsite_id, $site_id_tp){
		$data = array(
               'status' => 0,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('site_id', $nomsite_id);
		$this->db->where('tp_id', $site_id_tp);
		$this->db->update('t_site_tp_kandidat',$data); 

		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  	
	}

	function update_failed_tp($id){
		$data = array(
               'candidate_status' => 0,
               'updated_by' => $this->session->userdata('user_id'),
               'updated_on' => date('Y-m-d H:i:s')
            );

		$this->db->where('tp_id', $id);
		$this->db->update('t_mtr_tower_provider', $data); 
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  
	
	}
	
	
	//---------------------------------------------------------------------------
	//4dec@dody
	function update_site_kandidat(){
		
		$input = array('nomsite_id', 'site_id','status');
		foreach ($input as $val)
			$$val = $this->input->post($val);
			
		$cstatus = ($status == 'verify'?2:0); //status kandidat & tower
		
		$data = array(
			'status' => $cstatus,
			'updated_by' => $this->session->userdata('user_id'),
			'updated_on' => date('Y-m-d H:i:s')
		);

		$this->db->where('site_id', $nomsite_id);
		$this->db->where('tp_id', $site_id);
		$this->db->update('t_site_tp_kandidat',$data);
		
		
		//------- updae tower
		$data_tower = array(
			'candidate_status' => $cstatus,
			'updated_by' => $this->session->userdata('user_id'),
			'updated_on' => date('Y-m-d H:i:s')
		     );
		$this->db->where('tp_id', $site_id);
		$this->db->update('t_mtr_tower_provider', $data_tower);
		
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  	
	}
	
	public function update_site_kandidat_menang_kalah(){
		
		$input = array('nomsite_id', 'site_id','status');
		foreach ($input as $val)
			$$val = $this->input->post($val);
			
		$cstatus = ($status == 'choose'?3:2); //status kandidat & tower
		$sstatus = ($status == 'choose'?1:0); //status site
		
		$data = array(
			'status' => $cstatus,
			'updated_by' => $this->session->userdata('user_id'),
			'updated_on' => date('Y-m-d H:i:s')
		     );

		$this->db->where('site_id', $nomsite_id);
		$this->db->where('tp_id', $site_id);
		$this->db->update('t_site_tp_kandidat',$data);
		
		///-----------------------------------------------------------------
		$data = array(
			'candidate_status' => $cstatus,
			'updated_by' => $this->session->userdata('user_id'),
			'updated_on' => date('Y-m-d H:i:s')
		);

		$this->db->where('tp_id', $site_id);
		$this->db->update('t_mtr_tower_provider',$data); 
		
		
		///-----------------------------------------------------------------
		$this->db->select('latitude,longitude,address');
		$this->db->where('tp_id',$site_id);
		$query=$this->db->get('t_mtr_tower_provider');
		$data_tp = $query->result_array();
		$lat = ''; $long = ''; $address = ''; 
		if($query->num_rows()>0){
		    foreach ($data_tp as $row){
			$lat=$row["latitude"];
			$long=$row["longitude"];
			$address=$row["address"];
		    }
		}
    
		$data = array(
		   'latitude' => $lat,
		   'longitude' => $long,
		   'status' => $sstatus,
		   'site_address' => $address,
		   'updated_by' => $this->session->userdata('user_id'),
		   'updated_on' => date('Y-m-d H:i:s')
		);
    
		$this->db->where('site_id', $nomsite_id);
		$this->db->update('t_mtr_site',$data); 

		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  
	}
	
	public function cek_sudah_menang( $nomsie_id){
		$this->db->select('latitude,longitude,address');
		$this->db->where('site_id',$nomsite_id);
		$this->db->where('status',3);
		$query = $this->db->get('t_site_tp_kandidat'); 
		if($query->num_rows()>0){
			return true;
		}else{
			return false;
		}
	}
	
	function in_multiarray($elem, $array,$field)
	{
	    $top = sizeof($array) - 1;
	    $bottom = 0;
	    while($bottom <= $top)
	    {
		if($array[$bottom][$field] == $elem)
		    return true;
		else 
		    if(is_array($array[$bottom][$field]))
			if(in_multiarray($elem, ($array[$bottom][$field])))
			    return true;
	
		$bottom++;
	    }        
	    return false;
	}
	
	public function getSiteAll( $region="" ){
		$this->db->select('*'); $where = "";
		if($region <>""){
			$where = " AND ms.region_id = $region";
		}
		$query = $this->db->query("select ms.*, lo.lookup_desc
					from t_mtr_site ms
					left join t_mtr_lookup lo on lo.lookup_value=CAST(ms.site_profile AS VARCHAR) 
					where lo.lookup_key='site_profile' and ms.active=1 and ms.status IN (0,1) $where "); 
		return $query->result();
	}
	
	public function getSiteByID( $site_code="" ){
		$this->db->select('*'); 
		
		$where = " AND ms.site_code = '$site_code' ";
		$query = $this->db->query("select ms.*, lo.lookup_desc
					from t_mtr_site ms
					left join t_mtr_lookup lo on lo.lookup_value=CAST(ms.site_profile AS VARCHAR) 
					where lo.lookup_key='site_profile' $where "); 
		return $query->result();
	}
	
	public function getSiteForSearch( $key="" ){
		$query = $this->db->query("select ms.*, lo.lookup_desc
					from t_mtr_site ms
					left join t_mtr_lookup lo on lo.lookup_value=CAST(ms.site_profile AS VARCHAR) 
					where lo.lookup_key='site_profile'
						AND (upper(ms.site_code) like upper('%$key%')
							or upper(ms.site_name) like upper('%$key%')
							or upper(ms.vendor_id) like upper('%$key%')
							or upper(ms.site_address) like upper('%$key%')) "); 
		return $query->result();
	}
	
	//tracking
	public function getMapTracking() {
		$pic = array();
		$input = array('territory','pic','date_from','date_to');
		foreach($input as $val){
			$$val = $this->input->post( $val );
		}
		
		$pic=explode(';',$pic);
		
		//$date_from = "2014-01-04";
		//$date_to = "2014-01-04";
		$where = "";
		if(count($pic) >0 && $pic <>""){
			$where .=" AND created_by IN ('". implode("','", $pic) ."') ";
		}
		
		if($date_from <>"" && $date_to <>""){
			$where .=" and date(created_on) BETWEEN '$date_from' AND '$date_to' ";
		}
		$query = $this->db->query("select id, jc_id,type_id,waktu,latitude, longitude, created_by, date(created_on) as tracking_date
						FROM t_trx_site_inspection_tracking
						WHERE id>0 $where
						order by id asc");
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return array();
		}
	}
	
	public function getMapTrackingPM() {
		$pic = array();
		$input = array('territory','pic','date_from','date_to');
		foreach($input as $val){
			$$val = $this->input->post( $val );
		}
//		$pic=explode(';',$pic);
		//$date_from = "2014-01-04";
		//$date_to = "2014-01-04";
		$where = "";
		if(count($pic) >0 && $pic <>""){
                    $pic=explode(';',$pic);
                    $where .=" AND A.created_by IN ('". implode("','", $pic) ."') ";
		}
		
		if($date_from <>"" && $date_to <>""){
			$where .=" and date(A.created_on) BETWEEN '$date_from' AND '$date_to' ";
		}
//		$query = $this->db->query("select id, jc_id,type_id,waktu,latitude, longitude, created_by, date(created_on) as tracking_date
//						FROM t_trx_pm_tracking
//						WHERE id>0 $where
//						order by id asc");
                $query = $this->db->query("select A.id, A.jc_id, A.type_id, A.waktu, A.latitude, A.longitude, A.created_by, B.user_name, date(A.created_on) as tracking_date, to_char(A.created_on, 'dd/MM/YYYY') as created_on
                                FROM t_trx_pm_tracking A INNER JOIN t_mtr_user B ON A.created_by=B.user_id
                                WHERE A.id>0 $where
                                order by A.id asc");
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return array();
		}
	}
	
	public function getMapTrackingPMSchedule() {
		$pic = array();
		$input = array('territory','pic','date_from','date_to');
		foreach($input as $val){
			$$val = $this->input->post( $val );
		}
		//$pic=explode(';',$pic);
		$where = "";
		if(count($pic) >0 && $pic <>""){
                    $pic=explode(';',$pic);
                    $where .=" and sc.user_id IN ('". implode("','", $pic) ."') ";
		}
		
		if($date_from <>"" && $date_to <>""){
			$where .=" and date(sc.visit_date) BETWEEN '$date_from' AND '$date_to' ";
		}
		
                $query = $this->db->query("select sc.created_by, sc.user_id, sc.visit_date, st.site_code, st.site_name, st.latitude, st.longitude, st.site_address, st.vendor_id
						from t_trx_pm_schedule sc, t_mtr_site st
						where sc.site_id = st.site_id
						$where
						order by sc.user_id, sc.visit_date");
//		$query = $this->db->query("select sc.created_by, sc.visit_date, st.site_code, st.site_name, st.latitude, st.longitude, st.site_address, st.vendor_id
//						from t_trx_pm_schedule sc, t_mtr_site st
//						where sc.site_id = st.site_id
//						$where
//						order by sc.created_by, sc.visit_date");
		return $query->result();
	}
	
	public function getMapTrackingSchedule() {
		$pic = array();
		$input = array('territory','pic','date_from','date_to');
		foreach($input as $val){
			$$val = $this->input->post( $val );
		}
		$pic=explode(';',$pic);
		$where = "";
		if(count($pic) >0 && $pic <>""){
			$where .=" and sc.created_by IN ('". implode("','", $pic) ."') ";
		}
		
		if($date_from <>"" && $date_to <>""){
			$where .=" and date(sc.visit_date) BETWEEN '$date_from' AND '$date_to' ";
		}
		
		$query = $this->db->query("select sc.created_by, sc.visit_date, st.site_code, st.site_name, st.latitude, st.longitude, st.site_address, st.vendor_id
						from t_trx_site_inspection_schedule sc, t_mtr_site st
						where sc.site_id = st.site_id
						$where
						order by sc.created_by, sc.visit_date");
		return $query->result();
	}

	public function getPICPosition() {
		$pic = array();
		//$input = array('territory','pic','date_from');
                $input = array('region_id','pic','date_from');
		foreach($input as $val){
			$$val = $this->input->post( $val );
		}
		$pic=explode(';',$pic);
		$where = ""; $where2 = "";
		if(count($pic) >0 && $pic <>""){
			$where .=" AND tr.created_by IN ('". implode("','", $pic) ."') ";
//                        $where .=" AND tr.created_by IN ('$pic') ";
		}
		
		 if($region_id <>""){
			 $tr = explode('-',$region_id);
			 if($tr[1] == "reg"){
				 $where .=" AND st.region_id = '$tr[0]' ";
			 }else{
				 $where .=" AND st.cluster_ops_id = '$tr[0]' ";
			 }
		 }
		
		$query = $this->db->query("SELECT tr.id, tr.jc_id,tr.type_id,tr.waktu,tr.latitude, tr.longitude, tr.created_by, tu.user_name,
							date(tr.created_on) as tracking_date, sc.site_id, sc.status, st.site_code, st.site_name,
							st.site_address, st.region_id, st.cluster_ops_id
						from t_trx_pm_tracking tr
                                                INNER JOIN t_mtr_user tu ON tr.created_by=tu.user_id
						LEFT JOIN ( select sc1.* from t_trx_pm_schedule sc1,
								(select max(created_on) as max_date, user_id, visit_date
								from t_trx_pm_schedule
								where user_id is not null
								group by user_id, visit_date order by max_date DESC) as sc2
								where sc1.created_on = sc2.max_date and sc1.user_id=sc2.user_id ) sc on sc.user_id = tr.created_by AND sc.visit_date = date(tr.created_on)
						LEFT JOIN t_mtr_site st ON st.site_id = sc.site_id
						, (select max(created_on) as max_date, created_by, date(created_on)as tgl_aja
						from t_trx_pm_tracking
						group by created_by, tgl_aja order by max_date DESC) as trk 
						where trk.max_date = tr.created_on and trk.created_by = tr.created_by and sc.site_id is not null
							and date(tr.created_on) = '$date_from' $where");
		
		if( $query->num_rows() > 0 ) {
			return $query->result(); 
		} else {
			return array();
		}
	}
	
	
	
	public function getPICPositionSI() {
		$pic = array();
		$input = array('territory','pic','date_from');
		foreach($input as $val){
			$$val = $this->input->post( $val );
		}
		
		$where = ""; $where2="";
		if(count($pic) >0 && $pic <>""){
			$where .=" AND tr.created_by IN ('". implode("','", $pic) ."') ";
		}
		
		// if($territory <>""){
			// $tr = explode('-',$territory);
			// if($tr[1] == "reg"){
				// $where .=" AND st.region_id = '$tr[0]' ";
			// }else{
				// $where .=" AND st.cluster_ops_id = '$tr[0]' ";
			// }
		// }
		
		$query = $this->db->query("select tr.id, tr.jc_id,tr.type_id,tr.waktu,tr.latitude, tr.longitude, tr.created_by,
							date(tr.created_on) as tracking_date, sc.site_id, sc.status, st.site_code, st.site_name,
							st.site_address, st.region_id, st.cluster_ops_id
						from t_trx_site_inspection_tracking tr
						LEFT JOIN ( select sc1.* from t_trx_site_inspection_schedule sc1,
								(select max(created_on) as max_date, user_id, visit_date
								from t_trx_site_inspection_schedule
								where user_id is not null
								group by user_id, visit_date order by max_date DESC) as sc2
								where sc1.created_on = sc2.max_date and sc1.user_id=sc2.user_id ) sc on sc.user_id = tr.created_by AND sc.visit_date = date(tr.created_on)
						LEFT JOIN t_mtr_site st ON st.site_id = sc.site_id
						, (select max(created_on) as max_date, created_by, date(created_on)as tgl_aja
						from t_trx_site_inspection_tracking
						group by created_by, tgl_aja order by max_date DESC) as trk 
						where trk.max_date = tr.created_on and trk.created_by = tr.created_by and sc.site_id is not null
							and date(tr.created_on) = '$date_from' $where ");
		
		
		if( $query->num_rows() > 0 ) {
			return $query->result(); 
		} else {
			return array();
		}
	}
	
//	public function getSitePICTracking($region=""){
//		if($region=="")  return array();
//		$tr = explode('-',$region);
//		//$q = "SELECT * FROM t_mtr_site WHERE region_id='$tr[0]'";
//                $q = "SELECT * FROM t_mtr_site WHERE region_id='$tr[0]' AND status=1 ORDER BY site_code";
//		if($tr[1] == "cls"){
//			$q = "SELECT * FROM t_mtr_site WHERE cluster_ops_id='$tr[0]'";
//		}
//		
//		$query = $this->db->query( $q );
//		if( $query->num_rows() > 0 ) {
//			return $query->result(); 
//		} else {
//			return array();
//		}
//	}
        public function getSitePICTracking($site_code){
                $q = "SELECT * FROM t_mtr_site WHERE region_id IN (SELECT region_id FROM t_mtr_site WHERE site_code IN ('".implode("','", $site_code)."')) AND (latitude!=0 OR longitude!=0) ORDER BY site_code";
		if($tr[1] == "cls"){
			$q = "SELECT * FROM t_mtr_site WHERE cluster_ops_id='$tr[0]'";
		}
		$query = $this->db->query( $q );
		if( $query->num_rows() > 0 ) {
			return $query->result(); 
		} else {
			return array();
		}
	}
	
	//////
	function get_pic_for_search($role=11, $d=NULL, $a=NULL, $rows=0,$offset=0) {
		
		$dblokal = $this->load->database("default", TRUE);
		$qrs = "";
		$qr = "select DISTINCT(t_mtr_user.user_id), user_name, region.region_name, cls.cluster_name, region.region_id_all, cls.cluster_id_all
			from t_mtr_user"; 
                if (!empty($a)){
                    $qr.="  LEFT JOIN t_mtr_user_territory ON t_mtr_user_territory.user_id =t_mtr_user.user_id"; 
                }
			$qr.=" LEFT JOIN (SELECT user_id, array_to_string(array_agg(distinct region_name), ', ') AS region_name
							, array_to_string(array_agg(distinct t_mtr_region.region_id), ', ') AS region_id_all
								FROM t_mtr_user_territory, t_mtr_region  
								WHERE t_mtr_user_territory.region_id=t_mtr_region.region_id
								GROUP BY t_mtr_user_territory.user_id)as region on region.user_id = t_mtr_user.user_id
			
			LEFT JOIN (SELECT user_id, array_to_string(array_agg(cluster_name), ', ') AS cluster_name
							, array_to_string(array_agg(t_mtr_cluster_ops.cluster_ops_id), ', ') AS cluster_id_all
								FROM t_mtr_user_territory, t_mtr_cluster_ops  
								WHERE t_mtr_user_territory.cluster_ops_id =t_mtr_cluster_ops.cluster_ops_id
								GROUP BY t_mtr_user_territory.user_id)as cls on cls.user_id = t_mtr_user.user_id
			LEFT JOIN (
                        SELECT DISTINCT (user_id) FROM t_mtr_user_multi_role
                        WHERE
                                (
                                        t_mtr_user_multi_role.user_group_id = $role
                                        OR t_mtr_user_multi_role.user_group_id = 55
                                )
                        GROUP BY user_id
                        ) AS als_t_mtr_user_multi_role ON als_t_mtr_user_multi_role.user_id = t_mtr_user.user_id
			WHERE ";
		
		if (isset($d)){
			$q=$d;
			$or_arr=array('t_mtr_user.user_id','t_mtr_user.user_name');
			$or_str="";
			
			foreach ($or_arr as $val) {
				if ($or_str=="")
					$or_str.=" ($val ilike '%".$q."%'";
				else
					$or_str.=" or $val ilike '%".$q."%'";
			}
			$or_str.=")";
			//$qr .= " AND ".$or_str;
                        $qr .= $or_str;
		}
		
		if (isset($a)){
			$a = explode("-",$a);
			if(count($a)==2){
				if($a[1] == 'cls'){
					$qr .=" AND cluster_id_all like '%".$a[0]."%'";
				}else{
					$qr .=" AND t_mtr_user_territory.region_id =".$a[0];
				}
			}
		}
       
		if ($rows!=0){
			$qrs .= $qr." order by user_name LIMIT ".$rows." OFFSET ".$offset." ";
		}
                //die($qr);
		$queryc = $dblokal->query($qr);
		$total = $queryc->num_rows;
		$query = $dblokal->query($qrs);
		$result["total"] = $total;
		$items = array();
		
		foreach ($query->result() as $row) {
			 array_push($items, $row);				
		}
        
		$result["rows"] = $items;
		echo json_encode($result);
	}
	
	function getTerritoryTreeNewOpt(){
		$query = $this->db->query("SELECT * FROM t_mtr_region WHERE status = 1 ORDER BY order_no");
                $hasil = "<option value=''> - Choose Region - </option>";
		if($query->num_rows()>0){
			
			foreach($query->result() as $r){
                            //$hasil .="<option value='$r->region_id'>$r->region_name</option>";
				$hasil .="<option value='".$r->region_id."-reg'>$r->region_name</option>";
//				#cek punya children gak ?
//				$cek = $this->db->query("SELECT * FROM t_mtr_cluster_ops WHERE status = 1 AND region_id='$r->region_id' ORDER BY cluster_name");
//				if($cek->num_rows()>0){
//					foreach($cek->result() as $s){
//						$hasil .="<option value='".$s->cluster_ops_id."-cls'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$s->cluster_name</option>";
//					}
//				}
			}
		}
		return $hasil;
	}
	
	function getBatasTracking(){
		//batas_tracking
	}
        
        function get_regions() {
            $result =array();
            $query = $this->db->query("SELECT * FROM t_mtr_region WHERE status = 1 ORDER BY region_id");
            $result[0] = '- All Region -';
            foreach($query->result() as $s){
                $result[$s->region_id] = $s->region_name;
            }
            return $result;
      }
        
        function get_cluster_by_reg() {
            $reg_id = $this->input->post('region_id');
            $region_id = explode('-reg', $reg_id);
            $result =array();
            $result[0] = '- All Cluster -';
            if (!empty($reg_id)) {
                $cek = $this->db->query("SELECT * FROM t_mtr_cluster_ops WHERE status = 1 AND region_id=$region_id[0] ORDER BY cluster_name");
                foreach($cek->result() as $s){
                    $result[$s->cluster_ops_id.'-cls'] = $s->cluster_name;
                }
            }
            return $result;
      }
        
}