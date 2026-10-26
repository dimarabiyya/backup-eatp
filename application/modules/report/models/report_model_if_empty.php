<?php
ini_set('memory_limit', '8096M');

class Report_model extends CI_Model {
	//Berita acara serah terima
	private function schedule($visit_id)
	{
		//Site ID / Site Name FE diambil di $sql1

		$data = array();
		$rs1 = array("default");
		$rs2 = array("default");
		$sql1 = "
			SELECT *
			FROM t_trx_schedule AS sch
				LEFT JOIN t_trx_po_xl AS po ON sch.po_id = po.po_id
				LEFT JOIN t_trx_po_link AS pol ON sch.link_id = pol.link_id_int
				LEFT JOIN t_mtr_site_link AS site_link ON sch.link_id = site_link.link_id_int
				LEFT JOIN t_mtr_site_xl AS site ON site_link.site_id_ne = site.site_id
				LEFT JOIN t_mtr_area AS area ON site.area_id = area.area_id
			WHERE sch.visit_id = $visit_id";
		$query1 = $this->db->query($sql1);
		$rs1 = array_shift($query1->result_array());

		//Site ID / Site Name NE diambil khusus di $sql2
		$sql2 = "
			SELECT *
			FROM t_trx_schedule AS sch
				LEFT JOIN t_mtr_site_link AS site_link ON sch.link_id = site_link.link_id_int
				LEFT JOIN t_mtr_site_xl AS site ON site_link.site_id_fe = site.site_id
			WHERE sch.visit_id = $visit_id";
		$query2 = $this->db->query($sql2);
		$rslt = array_shift($query2->result_array());
		$rs2['opposite_site_name'] = $rslt['site_name'];

		$data[] = array_merge($rs1,$rs2);
		return array_shift($data);
	}

	//get Tanda Tangan XL, Alita, SPV, PIC
	private function ttd($visit_id){
		$sql_fop_alita = "SELECT usr.user_name, sch.image_sign, sch.latitude, sch.longitude, sch.contact_no, sch.created_on FROM t_trx_schedule_sign AS sch LEFT JOIN t_mtr_user AS usr ON sch.user_id = usr.user_id WHERE sign_type = 1 AND visit_id = $visit_id ORDER BY sch.created_on DESC LIMIT 1";
		$sql_fop_hs = "SELECT usr.user_name, sch.image_sign, sch.latitude, sch.longitude, sch.contact_no, sch.created_on FROM t_trx_schedule_sign AS sch LEFT JOIN t_mtr_user AS usr ON sch.user_id = usr.user_id WHERE sign_type = 2 AND visit_id = $visit_id ORDER BY sch.created_on DESC LIMIT 1";
		$sql_fop_hs_if_empty = "SELECT a.user_id, usr.user_name, a.updated_on AS created_on, usr.mobile_number AS contact_no FROM t_trx_schedule_result_approval a LEFT JOIN t_mtr_user AS usr ON a.user_id = usr.user_id where a.visit_id = $visit_id AND a.order_no=2";
		$query_alita = $this->db->query($sql_fop_alita);
		$query_hs = $this->db->query($sql_fop_hs);
		$query_hs_if_empty = $this->db->query($sql_fop_hs_if_empty);
		$data_alita = array_shift($query_alita->result_array());
		$data_hs = array_shift($query_hs->result_array());
		$data_hs_if_empty = array_shift($query_hs_if_empty->result_array());
		
		$path_fop_alita = $data_alita['image_sign']; //PT. Alita Praya Mitra
		$path_fop_hs = '';		 //PT. XL Axiata Tbk.
		
		$data['alita_name'] = '';
		$data['alita_date'] = '';
		$data['alita_contact'] = '';
		
		$data['hs_name'] = '';
		$data['hs_date'] = '';
		$data['hs_contact'] = '';
		
		if(empty($data_hs)){
			$path_fop_hs = 'file/upload/fop/if_empty/FOPSIGN_'.$data_hs_if_empty['user_id'].'.png';		 //PT. XL Axiata Tbk.
			$data['hs_name'] = $data_hs_if_empty['user_name'];
			$data['hs_date'] = $data_hs_if_empty['created_on'];
			$data['hs_contact'] = $data_hs_if_empty['contact_no'];
			
			$data_hs_created_on = $data_hs_if_empty['created_on'];
			$data_hs_latitude = $data_hs_if_empty['latitude'];
			$data_hs_longitude = $data_hs_if_empty['longitude'];
			$data_hs_contact_no = $data_hs_if_empty['contact_no'];
		}else{
			$path_fop_hs = $data_hs['image_sign'];		 //PT. XL Axiata Tbk.
			$data['hs_name'] = $data_hs['user_name'];
			$data['hs_date'] = $data_hs['created_on'];
			$data['hs_contact'] = $data_hs['contact_no'];
			
			$data_hs_created_on = $data_hs['created_on'];
			$data_hs_latitude = $data_hs['latitude'];
			$data_hs_longitude = $data_hs['longitude'];
			$data_hs_contact_no = $data_hs['contact_no'];
		}
		
		//Watermark: datetime, coordinate, contact_no
		//$remark_ttd_alita = $data_alita['created_on'].' / ('.$data_alita['latitude'].','.$data_alita['longitude'].') / '.$data_alita['contact_no'];
		//$remark_ttd_hs = $data_hs['created_on'].' / ('.$data_hs['latitude'].','.$data_hs['longitude'].') / '.$data_hs['contact_no'];

		//Cek file penyimpanan ttd remarked
		$directory_ttd_remark = "./file/upload/ttd/";
		if (!file_exists($directory_ttd_remark)) {
		    mkdir($directory_ttd_remark, 0777, true);
		}

		//Cek status progres closed => 5
		$sql1 = "
			SELECT pol.progress_status
			FROM t_trx_schedule AS sch
				LEFT JOIN t_trx_po_link AS pol ON sch.link_id = pol.link_id_int
			WHERE sch.visit_id = $visit_id
			ORDER BY pol.po_id DESC LIMIT 1";
		$query1 = $this->db->query($sql1);
		$progres = array_shift($query1->result_array());
		
		//start update by Hadi 20180801
		$progres['progress_status'] = 5;
		//end

		//Add Remark
		//imagettftext ( resource image, int size, int angle, int x, int y, int color, string fontfile, string text
		$font_path ='./file/verdana.ttf';	// Set Path to Font File
		if(!empty($path_fop_alita) && ($progres['progress_status'] == 5))
		{
			$file = base_url().$path_fop_alita;
			$what = getimagesize($file);
			switch(strtolower($what['mime']))
			{
			    case 'image/png':
			        $image_ttd_alita = imagecreatefrompng($file);
			        break;
			    case 'image/jpeg':
			        $image_ttd_alita = imagecreatefromjpeg($file);
			        break;
			    case 'image/gif':
			        $image_ttd_alita = imagecreatefromgif($file);
			        break;
			    default: die();
			}
			//$image_ttd_alita = imagecreatefrompng(base_url().$path_fop_alita);
			$color_alita = imagecolorallocate($image_ttd_alita, 0,0,0); //black ( ,0,0,0)
			imagettftext($image_ttd_alita, 24, 0, 10, 40, $color_alita, $font_path, $data_alita['created_on']);
			imagettftext($image_ttd_alita, 24, 0, 10, 80, $color_alita, $font_path, $data_alita['latitude'].','.$data_alita['longitude']);
			imagettftext($image_ttd_alita, 24, 0, 10, 120, $color_alita, $font_path, $data_alita['contact_no']);		
			imagejpeg($image_ttd_alita,'./file/upload/ttd/ttd_fop_alita.jpg');	// Send Image to Browser
			imagedestroy($image_ttd_alita);	// Clear Memory
			$data['ttd_fop_alita'] = '/file/upload/ttd/ttd_fop_alita.jpg';

			$data['alita_name'] = $data_alita['user_name'];
			$data['alita_date'] = $data_alita['created_on'];
			$data['alita_contact'] = $data_alita['contact_no'];
		}else{
			$data['ttd_fop_alita'] = '/assets/images/noimage.png';
		}

		if(!empty($path_fop_hs) && ($progres['progress_status'] == 5))
		{
			$file_hs = base_url().$path_fop_hs;
			$what_hs = getimagesize($file_hs);
			switch(strtolower($what_hs['mime']))
			{
			    case 'image/png':
			        $image_ttd_hs = imagecreatefrompng($file_hs);
			        break;
			    case 'image/jpeg':
			        $image_ttd_hs = imagecreatefromjpeg($file_hs);
			        break;
			    case 'image/gif':
			        $image_ttd_hs = imagecreatefromgif($file_hs);
			        break;
			    default: die();
			}
			//$image_ttd_hs = imagecreatefrompng(base_url().$path_fop_hs);
			$color_hs = imagecolorallocate($image_ttd_hs, 0,0,0); //black ( ,0,0,0)
			imagettftext($image_ttd_hs, 24, 0, 10, 40, $color_hs, $font_path, $data_hs_created_on);
			imagettftext($image_ttd_hs, 24, 0, 10, 80, $color_hs, $font_path, $data_hs_latitude.','.$data_hs_longitude);
			imagettftext($image_ttd_hs, 24, 0, 10, 120, $color_hs, $font_path, $data_hs_contact_no);	
			imagejpeg($image_ttd_hs,'./file/upload/ttd/ttd_fop_hs.jpg');	// Send Image to Browser
			imagedestroy($image_ttd_hs);	// Clear Memory
			$data['ttd_fop_hs'] = '/file/upload/ttd/ttd_fop_hs.jpg';
			
		}else{
			$data['ttd_fop_hs'] = '/assets/images/noimage.png';
		}

		//TTD SPV (SUPERVISOR)
		$sql_spv = "SELECT *
					FROM t_trx_schedule_result_approval AS app
					LEFT JOIN t_mtr_user AS usr ON app.user_id = usr.user_id
					WHERE app.visit_id = $visit_id LIMIT 1";
		$query_spv = $this->db->query($sql_spv);
		$spv = array_shift($query_spv->result_array());
		
		if(!empty($spv['image_signature']) && $progres['progress_status'] == 5){
			$blob = base64_decode(pg_unescape_bytea($spv['image_signature']));
			$image = imagecreatefromstring($blob);

			$color_spv = imagecolorallocate($image, 0,0,0); //black ( ,0,0,0)
			imagettftext($image, 24, 0, 10, 40, $color_spv, $font_path, $spv['created_on']);
			imagettftext($image, 24, 0, 10, 80, $color_spv, $font_path, $spv['mobile_number']);
			imagejpeg($image,'./file/upload/ttd/ttd_spv.jpg');	// Send Image to Browser
			imagedestroy($image);

			/*
			$data['ttd_spv'] = '/file/upload/ttd/ttd_spv.jpg';
			$data['spv_username'] = $spv['user_name'];
			$data['mobile_number'] = $spv['mobile_number'];
			*/
			$data['ttd_spv_alita'] = '/file/upload/ttd/ttd_spv.jpg';
			$data['spv_username_alita'] = $spv['user_name'];
			$data['mobile_number_alita'] = $spv['mobile_number'];
		}else{
			$data['ttd_spv'] = '/assets/images/noimage.png';;
			$data['spv_username'] = '';
			$data['mobile_number'] = '';
		}
		
		$data['ttd_spv'] = '/file/upload/ttd/spv_hs_fadhli_ttd.jpg';;
		$data['spv_username'] = 'Fadhli Nofenri';
		$data['mobile_number'] = '6281932909338';

		return $data;
	}

	//01. ATP Instalasi iPasolink (Roll Out, Reroute, Swap)
	public function report1($visit_id)
	{
		//(3) GROUP MW Radio System NE		//(4) GROUP MW Radio System FE		//(5) GROUP DISMANTLE
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result) => (3735-3770) (116-255) (302-440)
		/*$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3735 AND 3770 OR checklist_id BETWEEN 116 AND 255 OR checklist_id BETWEEN 302 AND 440 OR checklist_id BETWEEN 353 AND 354 ) AND visit_id = $visit_id";*/
			/*
		$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3735 AND 3770 OR checklist_id BETWEEN 116 AND 255 OR checklist_id BETWEEN 302 AND 440 OR checklist_id BETWEEN 353 AND 354 ) AND visit_id = $visit_id";
		$query_result = $this->db->query($result);
		$data['result'] = $query_result->result_array();
		//modified arrray
		$result_id = array("default");
		$result_name = array("default");
		$result_checklistID = array();
		if(!empty($data['result'])){
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID'.$data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		//All checklist_ID SOW 3 (t_trx_checklist_photo) => (256-301) (441-520)
		//Add by Hadi 2018-02-23 (3864-3865)
		$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE (checklist_id BETWEEN 256 AND 301 OR checklist_id BETWEEN 441 AND 520 OR checklist_id BETWEEN 3864 AND 3865) AND visit_id = $visit_id";
				//log_message('debug', "SQL photo : " . $photo);
		$query_photo = $this->db->query($photo);
		$data['photo'] = $query_photo->result_array();
		//modified arrray
		$photo_id = array("default");
		$photo_name = array("default");
		$photo_checklistID = array();
		if(!empty($data['photo'])){
			foreach ($data['photo'] as $data) {
				$photo_id[] = 'ID'.$data['checklist_id'];
				//$photo_name[] = $data['photo_name'];
				//start edit by hadi 20180810
				//$flag_photo = '';
				if (is_file('C:/xampp/htdocs/'.$data['photo_name'])) {
					//str_replace("world","Peter","Hello world!");
					//$photo_name[] = $data['photo_name'];
					$photo_name[] = str_replace(' ','%20',$data['photo_name']);
					//$flag_photo = 'TRUE';
				} else {
					$photo_name[] = 'assets/images/noimage.png';
					//$flag_photo = 'FALSE';
				}
				//log_message('debug', $flag_photo.'#'.$data['checklist_id'].'#'.$data['photo_name']);
				//end edit by hadi 20180810
				$photo_checklistID[] = $data['checklist_id'];
			}
		}
		$result = array_combine($result_id, $result_name);
		$photo = array_combine($photo_id, $photo_name);
		$data = array_merge($ttd, $schedule, $result, $photo); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i=116; $i <= 520; $i++) { 
			$all_checklistID[] = $i;
		}
		for ($i=3735; $i <= 3770; $i++) { 
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if( ($value >= 256 && $value <= 301) || ($value >= 441 && $value <= 520) ){ //t_trx_checklist_result_photo =>(256-301) (441-520)
				$data['ID'.$value] = '/assets/images/noimage.png';
			}else{ //t_trx_checklist_result
				$data['ID'.$value] = '';
			}
		}

		//ACCEPTANCE STATUS && ADDITIONAL NOTES
		$sql_acpt = "SELECT * 
					FROM t_trx_schedule_result_approval_sum
					WHERE visit_id = $visit_id ORDER BY created_on DESC LIMIT 1";
		$query_acpt = $this->db->query($sql_acpt);
		$acceptance = array_shift($query_acpt->result_array());
		$data['acceptance_date'] = $acceptance['created_on'];
		if(!empty($acceptance)){
			$data['acceptance_status'] = $acceptance['acceptance_status'];
			$data['notes'] = $acceptance['remark'];
		}else{
			$data['acceptance_status'] = 0;
			$data['notes'] = '';
		}

		//PENDING ITEMS
		$sql_pending = "SELECT * 
					FROM t_trx_schedule_pending_item
					WHERE visit_id = $visit_id ";
		$query_pending = $this->db->query($sql_pending);
		$data['pending'] = $query_pending->result_array();

		//(2) GROUP Instalasi Radio MW
		$parent = array();
		$sql1 = 'SELECT checklist_group_id AS id, group_name AS name FROM t_mtr_checklist_group WHERE parent_id = 2';
		$query1 = $this->db->query($sql1);
		$parent1 = $query1->result_array();

		$i=0; 
		foreach ($parent1 as $p1) { //51
			$kegiatan[$i] = $p1;
			$sql2 = 'SELECT checklist_group_id AS id, group_name AS name FROM t_mtr_checklist_group WHERE parent_id = '.$p1['id'].'';
			$query2 = $this->db->query($sql2);
			$parent2 = $query2->result_array();

			$j=0;
			foreach ($parent2 as $p2) { //300
				$kegiatan[$i]['child'][$j] = $p2;
				
				$sql3 = 'SELECT checklist_id AS id, checklist_name AS name FROM t_mtr_checklist WHERE checklist_group = '.$p2['id'].'';
				$query3 = $this->db->query($sql3);
				$parent3 = $query3->result_array();
				$k=0;
				foreach ($parent3 as $p3) { //11
					$kegiatan[$i]['child'][$j]['child'][$k] = $p3;

					$sql4 = 'SELECT checklist_result AS answer FROM t_trx_checklist_result WHERE checklist_id = '.$p3['id'].'';
					$query4 = $this->db->query($sql4);
					$answer = array_shift($query4->result_array());

					$sql5 = 'SELECT remark FROM t_trx_checklist_result_approval WHERE checklist_id = '.$p3['id'].'';
					$query5 = $this->db->query($sql5);
					$remark = array_shift($query5->result_array());
 
					$kegiatan[$i]['child'][$j]['child'][$k]['answer'] = ($answer['answer']);
					$kegiatan[$i]['child'][$j]['child'][$k]['remark'] = ($remark['remark']);
					$k++;
				}
				$j++;
			}
			$i++;
		}

		$data['kegiatan'] = $kegiatan ;
		*/

		return $data;
	}

	//02. ATP Instalasi Dismantle
	public function report2($visit_id)
	{
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result)
		$result = "
				SELECT checklist_id, checklist_result
				FROM t_trx_checklist_result
				WHERE (checklist_id BETWEEN 3771 AND 3802) AND visit_id = $visit_id";
		$query_result = $this->db->query($result);
		$data['result'] = $query_result->result_array();
		//modified arrray
		$result_id = array("default");
		$result_name = array("default");
		$result_checklistID = array();
		if(!empty($data['result'])){
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID'.$data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		//All checklist_ID SOW 3 (t_trx_checklist_photo)
		$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE (checklist_id BETWEEN 3485 AND 3519) AND visit_id = $visit_id";
		$query_photo = $this->db->query($photo);
		$data['photo'] = $query_photo->result_array();
		//modified arrray
		$photo_id = array("default");
		$photo_name = array("default");
		$photo_checklistID = array();
		if(!empty($data['photo'])){
			foreach ($data['photo'] as $data) {
				$photo_id[] = 'ID'.$data['checklist_id'];
				$photo_name[] = $data['photo_name'];
				$photo_checklistID[] = $data['checklist_id'];
			}
		}
		$result = array_combine($result_id, $result_name);
		$photo = array_combine($photo_id, $photo_name);
		$data = array_merge($ttd, $schedule, $result, $photo); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i=3485; $i <= 3519; $i++) { 
			$all_checklistID[] = $i;
		}
		for ($i=3771; $i <= 3802; $i++) { 
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if($value <= 3519){ //t_trx_checklist_result_photo
				$data['ID'.$value] = '/assets/images/noimage.png';
			}else{ //t_trx_checklist_result
				$data['ID'.$value] = '';
			}
		}

		return $data;
	}

	//03. ATP Outdorization
	public function report3($visit_id)
	{
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result)
		$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3528 AND 3541) OR checklist_id = 3803 AND visit_id = $visit_id";
		$query_result = $this->db->query($result);
		$data['result'] = $query_result->result_array();
		//modified arrray
		$result_id = array("default");
		$result_name = array("default");
		$result_checklistID = array();
		if(!empty($data['result'])){
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID'.$data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		//All checklist_ID SOW 3 (t_trx_checklist_photo)
		$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE (checklist_id BETWEEN 3520 AND 3527) AND visit_id = $visit_id";
		$query_photo = $this->db->query($photo);
		$data['photo'] = $query_photo->result_array();
		//modified arrray
		$photo_id = array("default");
		$photo_name = array("default");
		$photo_checklistID = array();
		if(!empty($data['photo'])){
			foreach ($data['photo'] as $data) {
				$photo_id[] = 'ID'.$data['checklist_id'];
				$photo_name[] = $data['photo_name'];
				$photo_checklistID[] = $data['checklist_id'];
			}
		}

		$result = array_combine($result_id, $result_name);
		$photo = array_combine($photo_id, $photo_name);
		$data = array_merge($ttd, $schedule, $result, $photo); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i=3520; $i <= 3541; $i++) { 
			$all_checklistID[] = $i;
		}
		$all_checklistID[] = 3803; //ada yang ketinggalan pendaftaran checklist_id

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if($value <= 3527){ //t_trx_checklist_result_photo
				$data['ID'.$value] = '/assets/images/noimage.png';
			}else{ //t_trx_checklist_result
				$data['ID'.$value] = '';
			}
		}

		//ACCEPTANCE STATUS //
		$sql_acpt = "SELECT * 
					FROM t_trx_schedule_result_approval_sum
					WHERE visit_id = $visit_id ORDER BY created_on DESC LIMIT 1";
		$query_acpt = $this->db->query($sql_acpt);
		$acceptance = array_shift($query_acpt->result_array());
		$data['acceptance_date'] = $acceptance['created_on'];

		if(!empty($acceptance)){
			$data['acceptance_status'] = $acceptance['acceptance_status'];	
		}else{
			$data['acceptance_status'] = 0;
		}

		return $data;
	}
	//04. ATP Inject License
	public function report4($visit_id)
	{
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result)
		$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3549 AND 3562) AND visit_id = $visit_id";
		$query_result = $this->db->query($result);
		$data['result'] = $query_result->result_array();
		//modified arrray
		$result_id = array("default");
		$result_name = array("default");
		$result_checklistID = array();
		if(!empty($data['result'])){
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID'.$data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		//All checklist_ID SOW 3 (t_trx_checklist_photo)
		$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE (checklist_id BETWEEN 3541 AND 3548 OR checklist_id = 3563) AND visit_id = $visit_id";
		$query_photo = $this->db->query($photo);
		$data['photo'] = $query_photo->result_array();
		//modified arrray
		$photo_id = array("default");
		$photo_name = array("default");
		$photo_checklistID = array();
		if(!empty($data['photo'])){
			foreach ($data['photo'] as $data) {
				$photo_id[] = 'ID'.$data['checklist_id'];
				$photo_name[] = $data['photo_name'];
				$photo_checklistID[] = $data['checklist_id'];
			}
		}
		$result = array_combine($result_id, $result_name);
		$photo = array_combine($photo_id, $photo_name);
		$data = array_merge($ttd, $schedule, $result, $photo); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i=3541; $i <= 3562; $i++) { 
			$all_checklistID[] = $i;
		}
		$all_checklistID[] = 3563; //checklist_id yang tertinggal pas di daftarin ke db

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if($value <= 3548 || $value == 3563){ //t_trx_checklist_result_photo
				$data['ID'.$value] = '/assets/images/noimage.png';
			}else{ //t_trx_checklist_result
				$data['ID'.$value] = '';
			}
		}

		return $data;
	}
	//05. ATP Los Survey
	public function report5($visit_id)
	{
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result)
		$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3586 AND 3599) AND visit_id = $visit_id";
		$query_result = $this->db->query($result);
		$data['result'] = $query_result->result_array();
		//modified arrray
		$result_id = array("default");
		$result_name = array("default");
		$result_checklistID = array();
		if(!empty($data['result'])){
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID'.$data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		$result = array_combine($result_id, $result_name);
		$data = array_merge($ttd, $schedule, $result); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i=3586; $i <= 3599; $i++) { 
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			$data['ID'.$value] = '';
		}

		return $data;
	}
	//06. ATP INstalasi Fixtrout
	public function report6($visit_id)
	{
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result)
		$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3604 AND 3617) AND visit_id = $visit_id";
		$query_result = $this->db->query($result);
		$data['result'] = $query_result->result_array();
		//modified arrray
		$result_id = array("default");
		$result_name = array("default");
		$result_checklistID = array();
		if(!empty($data['result'])){
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID'.$data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		//All checklist_ID SOW 3 (t_trx_checklist_photo)
		$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE (checklist_id BETWEEN 3600 AND 3603) AND visit_id = $visit_id";
		$query_photo = $this->db->query($photo);
		$data['photo'] = $query_photo->result_array();
		//modified arrray
		$photo_id = array("default");
		$photo_name = array("default");
		$photo_checklistID = array();
		if(!empty($data['photo'])){
			foreach ($data['photo'] as $data) {
				$photo_id[] = 'ID'.$data['checklist_id'];
				$photo_name[] = $data['photo_name'];
				$photo_checklistID[] = $data['checklist_id'];
			}
		}
		$result = array_combine($result_id, $result_name);
		$photo = array_combine($photo_id, $photo_name);
		$data = array_merge($ttd, $schedule, $result, $photo); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i=3600; $i <= 3617; $i++) { 
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if($value <= 3603){ //t_trx_checklist_result_photo
				$data['ID'.$value] = '/assets/images/noimage.png';
			}else{ //t_trx_checklist_result
				$data['ID'.$value] = '';
			}
		}

		return $data;
	}
	//07. ATP INstalasi Rack'19
	public function report7($visit_id)
	{
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result)
		$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3622 AND 3635) AND visit_id = $visit_id";
		$query_result = $this->db->query($result);
		$data['result'] = $query_result->result_array();
		//modified arrray
		$result_id = array("default");
		$result_name = array("default");
		$result_checklistID = array();
		if(!empty($data['result'])){
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID'.$data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		//All checklist_ID SOW 3 (t_trx_checklist_photo)
		$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE (checklist_id BETWEEN 3618 AND 3621) AND visit_id = $visit_id";
		$query_photo = $this->db->query($photo);
		$data['photo'] = $query_photo->result_array();
		//modified arrray
		$photo_id = array("default");
		$photo_name = array("default");
		$photo_checklistID = array();
		if(!empty($data['photo'])){
			foreach ($data['photo'] as $data) {
				$photo_id[] = 'ID'.$data['checklist_id'];
				$photo_name[] = $data['photo_name'];
				$photo_checklistID[] = $data['checklist_id'];
			}
		}
		$result = array_combine($result_id, $result_name);
		$photo = array_combine($photo_id, $photo_name);
		$data = array_merge($ttd, $schedule, $result, $photo); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i=3618; $i <= 3635; $i++) { 
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if($value <= 3621){ //t_trx_checklist_result_photo
				$data['ID'.$value] = '/assets/images/noimage.png';
			}else{ //t_trx_checklist_result
				$data['ID'.$value] = '';
			}
		}

		return $data;
	}
	//08. ATP RFI SCAN
	public function report8($visit_id)
	{
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result)
		$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3640 AND 3653) AND visit_id = $visit_id";
		$query_result = $this->db->query($result);
		$data['result'] = $query_result->result_array();
		//modified arrray
		$result_id = array("default");
		$result_name = array("default");
		$result_checklistID = array();
		if(!empty($data['result'])){
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID'.$data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		//All checklist_ID SOW 3 (t_trx_checklist_photo)
		$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE (checklist_id BETWEEN 3636 AND 3639) AND visit_id = $visit_id";
		$query_photo = $this->db->query($photo);
		$data['photo'] = $query_photo->result_array();
		//modified arrray
		$photo_id = array("default");
		$photo_name = array("default");
		$photo_checklistID = array();
		if(!empty($data['photo'])){
			foreach ($data['photo'] as $data) {
				$photo_id[] = 'ID'.$data['checklist_id'];
				$photo_name[] = $data['photo_name'];
				$photo_checklistID[] = $data['checklist_id'];
			}
		}
		$result = array_combine($result_id, $result_name);
		$photo = array_combine($photo_id, $photo_name);
		$data = array_merge($ttd, $schedule, $result, $photo); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i=3636; $i <= 3653; $i++) { 
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if($value <= 3639){ //t_trx_checklist_result_photo
				$data['ID'.$value] = '/assets/images/noimage.png';
			}else{ //t_trx_checklist_result
				$data['ID'.$value] = '';
			}
		}

		return $data;
	}
	//09. ATP SITE SURVEY
	public function report9($visit_id)
	{
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result)
		$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3700 AND 3713) AND visit_id = $visit_id";
		$query_result = $this->db->query($result);
		$data['result'] = $query_result->result_array();

		//modified arrray
		$result_id = array("default");
		$result_name = array("default");
		$result_checklistID = array();
		if(!empty($data['result'])){
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID'.$data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}
		
		$result = array_combine($result_id, $result_name);
		$data = array_merge($ttd, $schedule, $result); //All data

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i=3700; $i <= 3713; $i++) { 
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			$data['ID'.$value] = '';
		}

		return $data;
	}
	//10. ATP ADD SERVICE
	public function report10($visit_id)
	{
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result)
		$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3721 AND 3734) AND visit_id = $visit_id";
		$query_result = $this->db->query($result);
		$data['result'] = $query_result->result_array();
		//modified arrray
		$result_id = array("default");
		$result_name = array("default");
		$result_checklistID = array();
		if(!empty($data['result'])){
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID'.$data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		//All checklist_ID SOW 3 (t_trx_checklist_photo)
		$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE (checklist_id BETWEEN 3714 AND 3720) AND visit_id = $visit_id";
		$query_photo = $this->db->query($photo);
		$data['photo'] = $query_photo->result_array();
		//modified arrray
		$photo_id = array("default");
		$photo_name = array("default");
		$photo_checklistID = array();
		if(!empty($data['photo'])){
			foreach ($data['photo'] as $data) {
				$photo_id[] = 'ID'.$data['checklist_id'];
				$photo_name[] = $data['photo_name'];
				$photo_checklistID[] = $data['checklist_id'];
			}
		}
		$result = array_combine($result_id, $result_name);
		$photo = array_combine($photo_id, $photo_name);
		$data = array_merge($ttd, $schedule, $result, $photo); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i=3714; $i <= 3734; $i++) { 
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if($value <= 3720){ //t_trx_checklist_result_photo
				$data['ID'.$value] = '/assets/images/noimage.png';
			}else{ //t_trx_checklist_result
				$data['ID'.$value] = '';
			}
		}

		return $data;
	}

	//get name report
	public function getReportName($visit_type)
	{
		$sql = "SELECT option_value FROM t_mtr_checklist_options WHERE option_id = $visit_type";
		$query = $this->db->query($sql);
		$report_name = array_shift($query->result_array());

		return $report_name['option_value'];
	}

	//END
}