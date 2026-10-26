<?php

class Map extends MX_Controller {

	private $_redirect = '/dashboard';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library(array('form_validation', 'core'));
		$this->load->library('form_validation');

		$this->lang->load('referensi/referensi', 'indonesia');
		
		$this->load->helper('cookie');
		$this->load->helper('language');
		
		$this->load->model('map_model');
		$this->load->model('muser');
		$this->load->model('mmaster');
	}
	
	public function index()
	{
		$data['gambar_marker'] = $this->map_model->get_marker();
		$this->theme->view('map_view_dh', $data);
	}
	
	public function pilih_kandidat(){ 
		$nomor = $this->input->post('nomor');
		$site_id = $this->input->post('site_id');
		
		$status = "ERROR";
		$pesan="";
		
		$data = array(
		   'site_id' => $site_id,
		   'tp_id' => $nomor ,
		   'created_by' => $this->session->userdata('user_id') ,
		   'created_on' => date('Y-m-d H:i:s'),
		   'status' => 1
		);
		if($this->map_model->cek_site_kandidat($site_id, $nomor)){
			$pesan="Site already exist";
		}else{
			$update=$this->map_model->update_kandidat($nomor);
			$add=$this->map_model->add_kandidat($data);
			if($update && $add){
				$status = "OK";
			}else{
				$status = "ERROR";
				$pesan = "Failed insert to database";
			}	
		}
		
		echo json_encode(array(
			'status'=>$status,
			'pesan'=>$pesan
		));
		return;
	}	

	public function prelease_kandidat(){ 
		$nomor = $this->input->post('nomor');
		$site_id = $this->input->post('site_id');
		$data = array(
			   'site_id' => $site_id,
			   'tp_id' => $nomor 
				);

		$update_release=$this->map_model->update_release_tp($nomor);
		$delete=$this->map_model->delete_kandidat_sitetp($data);
		if($update_release && $delete){
			$status = "OK";
		}else{
			$status = "ERROR";
				$pesan = "Failed to release";
		}
		echo json_encode(array(
			'status'=>$status,
			'pesan'=>$pesan
		));
		return;
	}

	
	public function mapnsf()
	{
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		$site_nom=3;
		//$radius=400;
		$site_nom=isset($_POST['nomsite']) ? $_POST['nomsite'] : 3;
		//$radius=isset($_POST['radius']) ? $_POST['radius'] : 400;
		$data_nom = $this->map_model->get_site_by_id($site_nom);
		$nomlat=$data_nom[0]['latitude'];
		$nomlong=$data_nom[0]['longitude'];
		$nomname=$data_nom[0]['site_code'];
		$data['petak']=$this->map_model->get_tp_kandidat_by_siteid($site_nom, $nomlat, $nomlong);
		
		$kandidat=0;
		$tp=1;
		$koord_nomsend = $this->map_model->get_koord_nom_site($site_nom);
	    $nomlat=$koord_nomsend['lat'];
	    $nomlong=$koord_nomsend['long'];
	    
		//$data['radiuss'] = $radius;
		$data['koord_nom_lat'] = $nomlat;
		$data['koord_nom_long'] = $nomlong;
		$data['nomname'] = $nomname;
		$data['list_site_id'] = $this->map_model->get_site_name();
		$data['gambar_marker'] = $this->map_model->get_marker();
		$this->theme->set('page_title', 'PMTS - Map');		
		$this->theme->view('mapnsf_view', $data);
	}	
	
	public function kandidat(){ // INI YG DIPAKE
		$nomor = base64_decode($_GET['nomor']);
		$site_id = base64_decode($_GET['site_id']);
		//die('nomor '.$nomor.' site_id'.$site_id);
		//$this->core->decode($this->uri->segment(3));
		$data = array(
		   'site_id' => $site_id,
		   'tp_id' => $nomor ,
		   'created_by' => $this->session->userdata('user_id') ,
		   'created_on' => date('Y-m-d H:i:s'),
		   'status' => 1
		);
		if($this->map_model->cek_site_kandidat($site_id, $nomor)){
			$error_msg="record already exist";
			$this->kandidat_site($site_id);
			//echo $error_msg;
		}else{
			$error_msg="";
			$update=$this->map_model->update_kandidat($nomor);
			$add=$this->map_model->add_kandidat($data);
			if($update && $add){
				// $this->kandidat_site($site_id);
				//redirect('referensi/site_kandidat?site_id_map='.$site_id);
				redirect('referensi/map');
			}else{
				//echo "gagal";
				redirect('referensi/map');
			}	
		}
	}	

	public function release_kandidat(){ //DIPAKE
		$nomor = base64_decode($_GET['nomor']);
		$site_id = base64_decode($_GET['site_id']);
		$data = array(
			   'site_id' => $site_id,
			   'site_id_tp' => $nomor 
				);

		$update_release=$this->map_model->update_release_tp($nomor);
		$delete=$this->map_model->delete_kandidat_sitetp($data);
		if($update_release && $delete){
			// redirect('referensi/site_kandidat?site_id_map='.$site_id);
			// redirect('/site_info/edit/'.$site_id).'?src=cnd';
			redirect('referensi/map');
		}else{
			//echo "gagal";
			redirect('referensi/map');
		}	
	}

	public function kandidat_site($site_id){ //GA DIPAKE
		if ($this->core->is_ajax()) {
			
			if ($this->input->get('cmd') == 'GET_KANDIDAT') {
			
				$json = $this->map_model->get_site_kandidat($site_id);
				
				echo json_encode($json);
				exit;
			}
			
		}
		
		$this->theme->set('page_title', 'PMTS - Site Info - Kandidat Site');		
		$this->theme->view('kandidat_view3');

	}

	public function kandidat_site_list(){ //UDAH GA DIPAKE
		$site_id=isset($_GET['nomsite']) ? $_GET['nomsite'] : 3;
		//die("site_id ".$site_id);
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		$json2 = $this->map_model->get_site_kandidat($site_id);
		//die(print_r($json2));
		if ($this->core->is_ajax()) {
			
			if ($this->input->get('cmd') == 'GET_KANDIDAT') {
				
				if ($site_id) {
					$json = $this->map_model->get_site_kandidat($site_id);
				} else {
					$json = $this->map_model->get_site_kandidat();
				}
				
				echo json_encode($json);
				exit;
			}
			
		}
		
		$data['list_site_id'] = $this->map_model->get_site_name();
		
		// $this->load->view("template/smashing", $data);
		$this->theme->set('page_title', 'PMTS - Site Info - Kandidat Site');		
		$this->theme->view('kandidat_site_list_view2',$data);

	}

	public function kandidat_filter($site_id=1){
		//1=semua, 2=Belum Kandidat, 3=Kandidat, 4=Disewa
		//$site_id=1;
		//$site_id = $this->input->post('site_id');
		$status = $_GET['status'];//$this->input->post('status');
		$site_idd = $_GET['site_id_filter'];
		//die('>>'.$site_id);
		$data['tower_provider'] = $this->map_model->get_tower_provider_filter($site_idd,$status);
		$data['site_idd']= $site_idd;
		$this->theme->set('page_title', 'PMTS - Site Info - Kandidat Site');		
		$this->theme->view('kandidat_view', $data);

	}

	public function kandidat_site_list_nsf(){
		$site_id=isset($_GET['nomsite']) ? $_GET['nomsite'] : 3;
		//die("site_id ".$site_id);
		if (!$this->session->userdata('is_login')) {
			redirect('/login/' . $this->core->encode($this->uri->uri_string()));
		}
		$json2 = $this->map_model->get_site_kandidat($site_id);
		//die(print_r($json2));
		if ($this->core->is_ajax()) {
			
			if ($this->input->get('cmd') == 'GET_KANDIDAT') {
				
				if ($site_id) {
					$json = $this->map_model->get_site_kandidat($site_id);
				} else {
					$json = $this->map_model->get_site_kandidat();
				}
				
				echo json_encode($json);
				exit;
			}
			
		}
		
		$data['list_site_id'] = $this->map_model->get_site_name();
		
		$this->theme->set('page_title', 'PMTS - Site Info - Kandidat Site');		
		$this->theme->view('kandidat_site_list_nsf_view',$data);

	}

	public function disewa(){ //GA DIPAKE
		$nomor = $_GET['nomor'];
		$site_id = $_GET['site_id'];
		$update=$this->map_model->update_disewa($nomor);
		if($update){
		    $this->kandidat_site($site_id);
		}else{
		    //echo "gagal";
		    redirect('referensi/map');
		}

	}	

	public function pemenang(){ //INI YANG DIPAKE
		$nomsite_id = $_GET['nomsite_id'];
		$site_id_tp = $_GET['site_id_tp'];
		$update_kandidat=$this->map_model->update_pemenang_site_kandidat($nomsite_id, $site_id_tp);
		if($update_kandidat){
			$update_tp=$this->map_model->update_pemenang_tp($site_id_tp);
			if($update_tp){
				$update_site=$this->map_model->update_pemenang_master_site($nomsite_id, $site_id_tp);
				if($update_site){
					redirect('referensi/site_kandidat?site_id_map='.$nomsite_id);
				}else{
					redirect('referensi/map');
				}
			}else{
				redirect('referensi/map');
			}
		}else{
		    //echo "gagal";
		    redirect('referensi/map');
		}
	}	

	public function batalpemenang(){ //INI YANG DIPAKE
		$nomsite_id = $_GET['nomsite_id'];
		$site_id_tp = $_GET['site_id_tp'];
		$update_kandidat=$this->map_model->batal_pemenang_site_kandidat($nomsite_id, $site_id_tp);
		if($update_kandidat){
			$update_tp=$this->map_model->batal_pemenang_tp($site_id_tp);
			if($update_tp){
				$update_site=$this->map_model->batal_pemenang_master_site($nomsite_id, $site_id_tp);
				if($update_site){
					redirect('referensi/site_kandidat?site_id_map='.$nomsite_id);
				}else{
					redirect('referensi/map');
				}
			}else{
				redirect('referensi/map');
			}
		}else{
		    //echo "gagal";
		    redirect('referensi/map');
		}
	}	

	function all_site(){
		$data['site_name'] = $this->map_model->get_site_name();
	}

	function detil_tp(){
		$tp_id = $_GET['tp_id'];
		$data['tp_detil'] = $this->map_model->get_tp_by_id($tp_id);
		$this->theme->set('page_title', 'PMTS - Site Info - Kandidat Site - TP Detil');		
		$this->theme->view('detil_tp_view', $data);
	}
	
	function verifikasi_kandidat(){
		$tp_id = $_GET['tp_id'];
		$nomsite_id = $_GET['nomsite_id'];
		$update=$this->map_model->update_status_verified_tp($tp_id,$nomsite_id);	
		if($update){
		    echo "sukses";
			//$this->kandidat_site($site_id);
		}else{
		    echo "gagal";
		    //redirect('referensi/map');
		}
	}

	function pilih_pemenang(){//GA DIPAKE
		$tp_id = $_GET['tp_id'];
		$nomsite_id = $_GET['nomsite_id'];
		$update=$this->map_model->update_status_pemenang_tp($tp_id,$nomsite_id);	
		if($update){
		    echo "sukses";
		}else{
		    echo "gagal";
		}
		
	}

	function topology(){
//		$data['sitelink']=$this->map_model->get_site_link();
//		$data['topolink']=$this->map_model->get_topology_link();
//		$data['topofolink']=$this->map_model->get_topology_link(1);
//		$data['topocolink']=$this->map_model->get_topology_link(2);
		$data['gambar_marker'] = $this->map_model->get_marker();
		$this->theme->set('page_title', 'PMTS - Site Info - Topology');		
		$this->theme->view('map_topology',$data);	
	}
	
	
	public function tree(){
		$data['site_link'] = $this->map_model->get_site_link_tree_level2();
		if($data['site_link']!=null)
		{
			$i = 0;
			$detail=array();

			foreach($data['site_link'] as $row)
			{
				$detail=$this->referensi_model->get_site_link_tree_child($row['vendor_id1']);
				
				$data['site_link'][$i]['detail'] = $detail;
				$i++;
			}
		}
		//die(print_r($data['site_link']));
		$this->theme->set('page_title', 'PMTS - Referensi - Tree');		
		$this->theme->view('treemenu',$data);
	
	}
	
	public function iwa(){
		print_r($this->session->userdata);
	}

	public function iwates(){
		$qr = "select f_get_sitecode(59) as site_code";
        $qr_site        = $this->db->query($qr);
        $arr_site       = $qr_site->result();
		print_r($arr_site[0]->site_code);
	}

	public function verify_kandidat(){
		//Cek apakah user group = NSF (7)
		if($this->session->userdata('user_group_id')==7){
			$nomsite_id = $_GET['nomsite_id'];
			$site_id_tp = $_GET['site_id_tp'];
			$update_kandidat = $this->map_model->update_verified_site_kandidat($nomsite_id, $site_id_tp);
			if($update_kandidat){
				$update_tp=$this->map_model->update_verified_tp($site_id_tp);
				redirect('referensi/site_kandidat');
			}else{
				//echo "gagal";
				redirect('referensi/map');
			}		
		}
	}	
	
	public function failed_kandidat(){
		//Cek apakah user group = NSF (7)
		if($this->session->userdata('user_group_id')==7){
			$nomsite_id = $_GET['nomsite_id'];
			$site_id_tp = $_GET['site_id_tp'];
			$update_kandidat = $this->map_model->update_failed_site_kandidat($nomsite_id, $site_id_tp);
			if($update_kandidat){
				$update_tp=$this->map_model->update_failed_tp($site_id_tp);
				redirect('referensi/site_kandidat');
			}else{
				//echo "gagal";
				redirect('referensi/map');
			}		
		}
	}
	
	public function show_topology(){
		//get data site
		$region = $this->input->post('region');
		$hasil=array();
		$query=$this->map_model->getSiteAll($region);
		if (count($query) > 0) {
		  foreach ($query as $row) {
                        $icon = "merah.png";
//			$icon = "kuning.png";
//			if($row->lookup_desc == "BTS"){
//				$icon = "merah.png";
//			}elseif($row->lookup_desc == "BB;BTS"){
//				$icon = "hijau.png";
//			}
		    $hasil['site'][] = array(
		      'site_id' 	=> $row->site_id,
		      'site_code' 	=> $row->site_code,
		      'site_name' 	=> $row->site_name,
		      'vendor_id' 	=> $row->vendor_id,
		      'latitude' 	=> $row->latitude,
		      'longitude' 	=> $row->longitude,
		      'site_address'	=> $row->site_address,
		      'region_id'   	=> $row->region_id,
//		      'site_profile'	=> $row->lookup_desc,
                      'site_profile'	=> '-',  
		      'icon'		=> $icon
		    );
		  }
		}
		
		//menampilkan topo link
		$hasil_link=array(); $jml_link = 0;
		//if($this->input->post('link') == 'on'){
			$query_link=$this->map_model->get_topology_link($region);
			if (count($query_link) > 0) {
			  foreach ($query_link as $row) {
				$warna = "#f7d960"; $icon = "yellow_MarkerX.png";
//				if($row->lookup_desc == "MW"){
//					$warna = "#d01414"; $icon = "red_MarkerX.png";
//				}elseif($row->lookup_desc == "FO"){
//					$warna = "#0b1dc2"; $icon = "blue_MarkerX.png";
//				}elseif($row->lookup_desc == "Leased Line"){
//					$warna = "#0ba204"; $icon = "green_MarkerX.png";
//				}
			    $hasil_link['site'][] = array(
			      'site_code'   	=> $row->site_code,
			      'site_address'	=> $row->site_address,
			      'link_type'   	=> $row->site_code,
			      'capacity'	=> $row->capacity,
			      'latitude1' 	=> $row->latitude1,
			      'longitude1' 	=> $row->longitude1,
			      'latitude2' 	=> $row->latitude2,
			      'longitude2' 	=> $row->longitude2,
			      'frequency1'	=> $row->frequency,
			      'frequency2'	=> $row->frequency2,
			      'diameter1'	=> $row->diameter,
			      'diameter2'	=> $row->diameter2,
			      'link_code_id'	=> $row->link_code_id,
//			      'lookup_desc'	=> $row->lookup_desc,
                               'lookup_desc'	=> '-',
			      'warna'		=> $warna,
			      'icon'		=> $icon
			    );
			  }
		//	}
			$jml_link = count($query_link);
		}
		
		echo json_encode(array(
			'hasil'=> $hasil,
			'jml_site' => count($query),
			'hasil_link'=>$hasil_link,
			'jml_link' => $jml_link
		));
		return;
	}
	
	//show kandidat
	public function show_kandidat(){
		$site_nom = $this->input->post('site_code');
		$radius   = $this->input->post('radius');
		
		
		$hasil=array(); $status = "OK"; $pesan = "";
		
		if($site_nom == "" || $radius == "" || !is_numeric($radius) ){
			$status = "ERROR";
			if($site_nom == "" || $radius == ""){
				$pesan = "Site ID and Radius is Required";
			}else{
				if( !is_numeric($radius) ){
					$pesan = "Radius is required number";
				}
			}
			
			echo json_encode(array(
				'status'=> $status,
				'pesan' => $pesan
			));
			return;
		}
		
		//get lat lon
		$data_nom = $this->map_model->get_site_by_code($site_nom);
		if(count($data_nom)>0){
			$nomlat=$data_nom[0]['latitude'];
			$nomlong=$data_nom[0]['longitude'];
			$site_id=$data_nom[0]['site_id'];
			
			$hasil['latitude'] = $nomlat;
			$hasil['longitude'] = $nomlong;
			$hasil['radius'] = (int)$radius;
			
			$query = $this->map_model->get_tp_by_radius($site_id, $radius, $nomlat, $nomlong);
			if (count($query) > 0) {
				$ic             = array();
				$gambar_marker  = $this->map_model->get_marker();
				foreach($gambar_marker as $row){
                                    $pid        = $row['company_id'];
                                    $ic[$pid]   = $row['gis_marker'];
				}
				foreach ($query as $row) {
					
					$icon = "kuning.png";
					if ($row->tp_name == "smfsite") {
                                            $icon = "tower_blue.gif";
					} elseif ($row->tp_name == "nomsite") {
                                            $icon = "tower_red.gif";
					} else {
                                            $icon = $ic[$row->partner_id];
					}
					
					$hasil['site'][] = array(
					  'id' 	=> $row->id,
					  'site_id'	=> $this->core->encode($row->id),
					  'site_code' 	=> $row->site_code,
					  'vendor_id' 	=> $row->vendor_id,
					  'tp_name' 	=> $row->tp_name,
					  'site_id_tp' 	=> $row->site_id_tp,
					  'rank' 	=> $row->rank,
					  'site_name' 	=> $row->site_name,
					  
					  'actual_address'	=> $row->actual_address,
					  'actual_latitude'	=> $row->actual_latitude,
					  'actual_longitude'	=> $row->actual_longitude,
					  
					  'address' 	=> $row->address,
					  'kandidat' 	=> $row->kandidat,
					  'latitude' 	=> $row->latitude,
					  'longitude' 	=> $row->longitude,
					  
					  'partner_id'	=> $row->partner_id,
					  'distance'   	=> number_format($row->distance* 1000, 2, '.', ','),
					  'icon'	=> $icon
					);
				}
			}
			
		}
		echo json_encode(array(
			'status'=> $status,
			'pesan' => $pesan,
			'hasil'=> $hasil,
			'jml_site' => count($query),
			'site_id'=>$site_id
		));
		return;
	}
	
	public function testPoint(){
		$this->load->library('pointlocation');
		$this->load->model('map_model');
		
		$polygon = array("-5.692515936746771 106.226806640625","-6.32621808201486 105.27099609375","-7.231698708367139 105.1611328125","-7.700104531441816 106.6552734375","-8.548429781881914 110.3466796875","-8.895925996417885 114.67529296875","-7.5694373362514344 114.598388671875","-6.730075707109153 114.400634765625","-6.599130675207247 112.796630859375","-6.446317749457633 111.829833984375","-6.249776453227819 110.731201171875","-6.479067290763031 110.3466796875","-6.500899137995968 109.588623046875","-6.315298538330033 108.863525390625","-5.9657536710655235 108.03955078125","-5.769035866122123 106.875","-5.725311447610142 106.358642578125");
		
		$getSiteAll = $this->map_model->getSiteAll();
		foreach($getSiteAll as $r){
			$point= "$r->latitude $r->longitude";
			echo "point $r->site_id :: $r->site_name :: $r->site_address ($point): " . $this->pointlocation->pointInPolygon($point, $polygon) . "<br>";
		}
	}
	
	public function show_jalan(){
		$jenis = $this->input->post('jenis');
		$key = $this->input->post('key');
		
		$jalan = $key; $lat = ""; $long = "";
		$hasil=array();
		if($jenis == "2"){
			$query=$this->map_model->getSiteForSearch( $key );
			if (count($query) > 0) {
			  foreach ($query as $row) {
				$icon = "kuning.png";
				if($row->lookup_desc == "BTS"){
					$icon = "merah.png";
				}elseif($row->lookup_desc == "BB;BTS"){
					$icon = "hijau.png";
				}
			    $hasil['site'][] = array(
			      'site_id' 	=> $row->site_id,
			      'site_code' 	=> $row->site_code,
			      'site_name' 	=> $row->site_name,
			      'vendor_id' 	=> $row->vendor_id,
			      'latitude' 	=> $row->latitude,
			      'longitude' 	=> $row->longitude,
			      'site_address'	=> $row->site_address,
			      'region_id'   	=> $row->region_id,
			      'site_profile'	=> $row->lookup_desc,
			      'icon'		=> $icon
			    );
			  }
			}
			
			echo json_encode(array(
				'jenis'=> $jenis,
				'jalan'=> $key,
				'hasil' => $hasil,
				'jml_site' => count($query)
			));
			return;		
		}else{
			echo json_encode(array(
				'jenis'=> $jenis,
				'jalan'=> $key
			));
			return;
		}
	}
	
	
	public function show_pic( $ugroup=11 ){
		$this->load->model('gis/mgis');
		$hasil = $this->mgis->getPic( $ugroup );
		echo json_encode( $hasil );
		return;	
	}
	
	public function show_pic_si( $ugroup=8 ){
		$this->load->model('gis/mgis');
		$hasil = $this->mgis->getPic( $ugroup );
		echo json_encode( $hasil );
		return;	
	}
	
	///tracking
	function tracking_map(){
		$data['gambar_marker'] = $this->map_model->get_marker();
		$this->theme->set('page_title', 'PMTS - Site Info - Topology');		
		$this->theme->view('gis_tracking',$data);	
	}
	
	function tracking_map_pm(){
		$data['gambar_marker'] = $this->map_model->get_marker();
		$this->theme->set('page_title', 'PMTS - Site Info - Topology');		
		$this->theme->view('gis_tracking-pm',$data);	
	}
	
	///dody@6jan
	public function show_tracking(){
		//get data
		$hasil=array();
		//menampilkan polyline
		$hasil_link=array(); $jml_link = 0;
		$query_link=$this->map_model->getMapTracking();
		if (count($query_link) > 0) {
			foreach ($query_link as $row) {
				$index = $row->created_by."::".$row->tracking_date;
				$hasil_link['site'][$index][] = array(
					'created_by' 	=> $row->created_by,
					'tracking_date' => $row->tracking_date,
					'latitude' 	=> $row->latitude,
					'longitude' 	=> $row->longitude,
					'type_id'	=> $row->type_id
				);
			}
			
			//get schedule
			$qschedule=$this->map_model->getMapTrackingPMSchedule();
			$sch = array(); $smarker = array();
			foreach($qschedule as $r){
				$index = $r->created_by."::".$r->visit_date;
				
				$sch[$index][] = array('site_code'=>$r->site_code,
						       'site_name'=>$r->site_name,
						       'vendor_id'=>$r->vendor_id,
						       'address'=>$r->site_address);
				$hasil_link['smarker'][] = array('site_code'=>$r->site_code,
						       'site_name'=>$r->site_name,
						       'vendor_id'=>$r->vendor_id,
						       'address'=>$r->site_address,
						       'latitude'=>$r->latitude,
						       'longitude'=>$r->longitude); //=> site yg harus dikunjungi
			}
			$schedule = array(); $js = 0;
			foreach($sch as $key => $dt){
				$info = array();
                                $no = 0;
				foreach($dt as $r){
					$info[] = "
                                                  <tr><td width='70px'>Site ID</td><td>: ".$no++ ."</td></tr>  
                                                  <tr><td width='70px'>Site ID</td><td>: ".$r['site_code'] ."</td></tr>
                                                  <tr><td>Site Name</td><td>: ".$r['site_name'] ."</td></tr>
                                                  <tr><td>Vendor ID</td><td>: ".$r['vendor_id'] ."</td></tr>
                                                  <tr><td>Address</td><td>: ".$r['address']."</td></tr>
                                                  ";
				}
				$schedule[$key] = "<table width='280px'>" . implode("<tr><td colspan=2><hr/></td></tr>",$info). "</table>";
				$js++;
			}
			
			$hasil_link['schedule'] = $schedule;
			$hasil_link['jml_schedule'] = $js;
			$hasil_link['batas_tracking'] = $this->mglobal->showdata('lookup_value','t_mtr_lookup',array('lookup_key'=>'TrackingRouteLimit'));
		}
		$jml_link = count($query_link);
		
		echo json_encode(array(
			'hasil'=> $hasil,
			'hasil_link'=>$hasil_link,
			'jml_link' => $jml_link
		));
		return;
	}
	
	public function show_tracking_pm(){
		//get data
		$hasil=array();
		//menampilkan polyline
		$hasil_link=array(); 
                $jml_link = 0;
		$query_link=$this->map_model->getMapTrackingPM();
		if (count($query_link) > 0) {
			foreach ($query_link as $row) {
				$index = $row->created_by."::".$row->tracking_date;
				$hasil_link['site'][$index][] = array(
					'created_by' 	=> $row->created_by,
                                        'user_name' 	=> $row->user_name,
                                        'created_on'    => $row->created_on,
					'tracking_date' => $row->tracking_date,
					'latitude' 	=> $row->latitude,
					'longitude' 	=> $row->longitude,
					'type_id'	=> $row->type_id
				);
			}
			
			//get schedule
			$qschedule=$this->map_model->getMapTrackingPMSchedule();
			$sch = array(); 
                        //$hasil_link['smarker'][] = array();
			foreach($qschedule as $r){
				$index = $r->user_id."::".$r->visit_date;
				
				$sch[$index][] = array('site_code'=>$r->site_code,
						       'site_name'=>$r->site_name,
						       'vendor_id'=>$r->vendor_id,
						       'address'=>$r->site_address);
				$hasil_link['smarker'][] = array('site_code'=>$r->site_code,
						       'site_name'=>$r->site_name,
						       'vendor_id'=>$r->vendor_id,
						       'address'=>$r->site_address,
						       'latitude'=>$r->latitude,
						       'longitude'=>$r->longitude); //=> site yg harus dikunjungi
			}
			$schedule = array();
                        $js = 0;
			foreach($sch as $key => $dt){
				$info = array();
                                $no = 1;
				foreach($dt as $r){
					$info[] = "
                                            <tr style='border-top:0.1px solid grey'><td width='80px'>".$no++.") Site ID</td><td> : </td><td>".$r['site_code'] ."</td></tr>
                                              <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Site Name</td><td> : </td><td>".$r['site_name'] ."</td></tr>
                                              <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Vendor ID</td><td> : </td><td>".$r['vendor_id'] ."</td></tr>
                                              <tr><td valign='top'>&nbsp;&nbsp;&nbsp;&nbsp;Address</td><td valign='top'> : </td><td valign='top'>".$r['address']."</td></tr>";
				}
				$schedule[$key] = "<table width='300px'>" . implode("",$info). "</table>";
				$js++;
			}
			
			$hasil_link['schedule'] = $schedule;
			$hasil_link['jml_schedule'] = $js;
			$hasil_link['batas_tracking'] = $this->mglobal->showdata('lookup_value','t_mtr_lookup',array('lookup_key'=>'TrackingRouteLimit'));
		}
		$jml_link = count($query_link);
		
		echo json_encode(array(
			'hasil'=> $hasil,
			'hasil_link'=>$hasil_link,
			'jml_link' => $jml_link
		));
		return;
	}
	
	//////////////////
	function gis_pic_position(){
		$data['gambar_marker'] = $this->map_model->get_marker();
                $data['regions'] = $this->map_model->get_regions();
		$this->theme->set('page_title', 'GIS PIC Position');		
		$this->theme->view('gis_pic_position',$data);	
	}
	
	public function show_pic_position(){
		//get data
		$hasil=array();
		//PIC Tracking
		$hasil_link=array(); 
                $jml_link = 0;
                $site_code = array();
		$query_link=$this->map_model->getPICPosition();
		if (count($query_link) > 0) {
		  foreach ($query_link as $row) {
		    $hasil_link['site'][] = array(
                      'created_by' 	=> $row->user_name,
		      'site_code' 	=> $row->site_code,
		      'site_name' 	=> $row->site_name,
		      'site_address' 	=> $row->site_address,
		      'status' 		=> $row->status,
		      'latitude' 	=> $row->latitude,
		      'longitude' 	=> $row->longitude
		    );
                    $site_code[] = $row->site_code;
		  }
		}
		$jml_link = count($query_link);
//		print_r($site_code[0]);
//                print_r($site_code[1]);
		//show site
		$hasil_site=array(); 
                $jml_site = 0;
		//$query_site=$this->map_model->getSitePICTracking( $this->input->post('territory'));
                $query_site=$this->map_model->getSitePICTracking($site_code);
		if (count($query_site) > 0) {
		  foreach ($query_site as $row) {
		    $hasil_site['site'][] = array(
		      'site_code' 	=> $row->site_code,
		      'site_name' 	=> $row->site_name,
		      'site_address' 	=> $row->site_address,
		      'vendor_id' 	=> $row->vendor_id,
		      'latitude' 	=> $row->latitude,
		      'longitude' 	=> $row->longitude
		    );
		  }
		}
		$jml_site = count($query_site);
		
		echo json_encode(array(
			'hasil'=> $hasil,
			'hasil_link'=>$hasil_link,
			'jml_link' => $jml_link,
			'hasil_site'=>$hasil_site,
			'jml_site' => $jml_site
		));
		return;
	}
	
	//untuk PM
	function gis_pic_position_si(){
		$data['gambar_marker'] = $this->map_model->get_marker();
		$this->theme->set('page_title', 'GIS PIC Position');		
		$this->theme->view('gis_pic_position_si',$data);	
	}
	
	public function show_pic_position_si(){
		//get data
		$hasil=array();
		//PIC Tracking
		$hasil_link=array(); $jml_link = 0;
		$query_link=$this->map_model->getPICPositionSI(  );
		if (count($query_link) > 0) {
		  foreach ($query_link as $row) {
		    $hasil_link['site'][] = array(
		      'site_code' 	=> $row->site_code,
		      'site_name' 	=> $row->site_name,
		      'site_address' 	=> $row->site_address,
		      'status' 		=> $row->status,
		      
		      'latitude' 	=> $row->latitude,
		      'longitude' 	=> $row->longitude
		    );
		  }
		}
		$jml_link = count($query_link);
		
		//show site
		$hasil_site=array(); $jml_site = 0;
		$query_site=$this->map_model->getSitePICTracking($this->input->post('territory') );
		if (count($query_site) > 0) {
		  foreach ($query_site as $row) {
		    $hasil_site['site'][] = array(
		      'site_code' 	=> $row->site_code,
		      'site_name' 	=> $row->site_name,
		      'site_address' 	=> $row->site_address,
		      'vendor_id' 	=> $row->vendor_id,
		      'latitude' 	=> $row->latitude,
		      'longitude' 	=> $row->longitude
		    );
		  }
		}
		$jml_site = count($query_site);
		
		echo json_encode(array(
			'hasil'=> $hasil,
			'hasil_link'=>$hasil_link,
			'jml_link' => $jml_link,
			
			'hasil_site'=>$hasil_site,
			'jml_site' => $jml_site
		));
		return;
	}
	
	///////////////////////////////////////////////////
	//dody@13dec12
	public function searchPIC( $role=11 ){

		$data['judul'] = "PIC List";
		if($role == 8){
                    $data['judul'] = "Network Project Implementor List";
		} else if ($role==11) {
                    $data['judul'] = "FOPS List";
                }
		$data['role'] = $role;
		$data['seq'] = $this->input->get('seq');
		$this->load->view("form_search_pic", $data);
		return;		
	}
	
	public function getAllPIC( $role = 11 ){
		$d=$this->input->post();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : '';
		$a = isset($_POST['a']) ? $_POST['a'] : '';
//                $c = isset($_POST['c']) ? $_POST['c'] : '';
		$offset = ($page-1)*$rows;
		
		$qr = $this->map_model->get_pic_for_search( $role, $q, $a, $rows,$offset );
	}
        
        public function getClusterByReg() {
           $option_cluster = $this->map_model->get_cluster_by_reg();
           
           echo '&nbsp;<b>Cluster</b>&nbsp;'.form_dropdown("cluster_ops_id",$option_cluster,'', 'id="cluster_ops_id"');
//            $this->load->view('cluster.php', $data);
        }
        
}
