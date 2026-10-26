<?php

class Export_model extends CI_Model {

	//Berita acara serah terima
	private function schedule($visit_id)
	{
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

		//Site ID / Site Name FE diambil di $sql1
		//Site ID / Site Name NE diambil khusus di $sql2
		$sql2 = "
			SELECT *
			FROM t_trx_schedule AS sch
				LEFT JOIN t_mtr_site_link AS site_link ON sch.link_id = site_link.link_id_int
				LEFT JOIN t_mtr_site_xl AS site ON site_link.site_id_fe = site.site_id
			WHERE sch.visit_id = $visit_id";
		$query2 = $this->db->query($sql2);
		$rs2 = array('opposite_site_name'=>array_shift($query2->result_array())['site_name']);

		$data[] = array_merge($rs1,$rs2);
		return array_shift($data);
	}

	//get Tanda Tangan XL, Alita, SPV, PIC
	private function ttd($visit_id){
		$sql_fop_alita = "SELECT * FROM t_trx_schedule_sign AS sch LEFT JOIN t_mtr_user AS usr ON sch.user_id = usr.user_id WHERE sign_type = 1 AND visit_id = $visit_id ORDER BY sch.created_on DESC LIMIT 1";
		$sql_fop_hs = "SELECT * FROM t_trx_schedule_sign AS sch LEFT JOIN t_mtr_user AS usr ON sch.user_id = usr.user_id WHERE sign_type = 2 AND visit_id = $visit_id ORDER BY sch.created_on DESC LIMIT 1";
		$query_alita = $this->db->query($sql_fop_alita);
		$query_hs = $this->db->query($sql_fop_hs);
		$data_alita = array_shift($query_alita->result_array());
		$data_hs = array_shift($query_hs->result_array());
		
		$path_fop_alita = $data_alita['image_sign']; //PT. XL Axiata Tbk.
		$path_fop_hs = $data_hs['image_sign'];		 //PT. Alita Praya Mitra
		
		$data['alita_name'] = '';
		$data['alita_date'] = '';
		$data['alita_contact'] = '';
		$data['hs_name'] = '';
		$data['hs_date'] = '';
		$data['hs_contact'] = '';
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
			WHERE sch.visit_id = $visit_id";
		$query1 = $this->db->query($sql1);
		$progres = array_shift($query1->result_array());

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
			imagettftext($image_ttd_hs, 24, 0, 10, 40, $color_hs, $font_path, $data_hs['created_on']);
			imagettftext($image_ttd_hs, 24, 0, 10, 80, $color_hs, $font_path, $data_hs['latitude'].','.$data_hs['longitude']);
			imagettftext($image_ttd_hs, 24, 0, 10, 120, $color_hs, $font_path, $data_hs['contact_no']);	
			imagejpeg($image_ttd_hs,'./file/upload/ttd/ttd_fop_hs.jpg');	// Send Image to Browser
			imagedestroy($image_ttd_hs);	// Clear Memory
			$data['ttd_fop_hs'] = '/file/upload/ttd/ttd_fop_hs.jpg';

			$data['hs_name'] = $data_hs['user_name'];
			$data['hs_date'] = $data_hs['created_on'];
			$data['hs_contact'] = $data_hs['contact_no'];	
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

			$data['ttd_spv'] = '/file/upload/ttd/ttd_spv.jpg';
			$data['spv_username'] = $spv['user_name'];
		}else{
			$data['ttd_spv'] = '/assets/images/noimage.png';;
			$data['spv_username'] = '';
		}

		return $data;
	}


	//01. Form Material Dismantle
	public function form_material($visit_id)
	{
		//(3) GROUP MW Radio System NE		//(4) GROUP MW Radio System FE		//(5) GROUP DISMANTLE
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result) => (3735-3770) (116-255) (302-440)
		$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 521 AND 834) AND visit_id = $visit_id";
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
		for ($i=521; $i <= 834; $i++) { 
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			$data['ID'.$value] = '';
		}

		return $data;
	}

	//09. Pre Survey
	public function pre_survey($visit_id)
	{
		$data = array();
		$schedule = $this->schedule($visit_id);
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_photo)
		$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE (checklist_id BETWEEN 3676 AND 3679 OR checklist_id BETWEEN 3691 AND 3694) AND visit_id = $visit_id";
		$query_photo = $this->db->query($photo);
		$data['photo'] = $query_photo->result_array();

		//All checklist_ID SOW 3 (t_trx_checklist_result)
		$result = "
				SELECT checklist_id, checklist_result
				FROM t_trx_checklist_result
				WHERE (checklist_id BETWEEN 3654 AND 3675 OR checklist_id BETWEEN 3680 AND 3690 OR checklist_id BETWEEN 3695 AND 3699) AND visit_id = $visit_id";
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
		for ($i=3654; $i <= 3699; $i++) { 
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if(($value >= 3676 && $value <= 3679) || ($value >= 3691 && $value <= 3694)){ //t_trx_checklist_result_photo
				$data['ID'.$value] = '/assets/images/noimage.png';
			}else{ //t_trx_checklist_result
				$data['ID'.$value] = '';
			}
		}

		return $data;
	}

	//END
}