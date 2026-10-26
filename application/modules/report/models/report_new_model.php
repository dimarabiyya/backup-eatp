<?php
class Report_new_model extends CI_Model
{
	//Berita acara serah terima
	public function schedule($visit_id)
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

		$data[] = array_merge($rs1, $rs2);
		return array_shift($data);
	}

	public function ttd($visit_id)
	{
		$sql_fop_alita = "SELECT usr.user_name, sch.image_sign, sch.latitude, sch.longitude, sch.contact_no, sch.created_on FROM t_trx_schedule_sign AS sch LEFT JOIN t_mtr_user AS usr ON sch.user_id = usr.user_id WHERE sign_type = 1 AND visit_id = $visit_id ORDER BY sch.created_on DESC LIMIT 1";
		$sql_fop_hs = "SELECT usr.user_name, sch.image_sign, sch.latitude, sch.longitude, sch.contact_no, sch.created_on FROM t_trx_schedule_sign AS sch LEFT JOIN t_mtr_user AS usr ON sch.user_id = usr.user_id WHERE sign_type = 2 AND visit_id = $visit_id ORDER BY sch.created_on DESC LIMIT 1";
		$sql_qc_alita = "SELECT a.user_id, usr.user_name, a.updated_on AS created_on, usr.mobile_number AS contact_no FROM t_trx_schedule_result_approval a LEFT JOIN t_mtr_user AS usr ON a.user_id = usr.user_id where a.visit_id = $visit_id AND a.order_no=1";
		$query_alita = $this->db->query($sql_fop_alita);
		$query_hs = $this->db->query($sql_fop_hs);
		$query_qc_alita = $this->db->query($sql_qc_alita);
		$data_alita = array_shift($query_alita->result_array());
		$data_hs = array_shift($query_hs->result_array());
		$data_qc_alita = array_shift($query_qc_alita->result_array());

		$path_fop_alita = $data_alita['image_sign']; //PIC PT. Alita Praya Mitra
		$path_fop_hs = $data_hs['image_sign'];		 //PT. XL Axiata Tbk.
		$path_qc_alita = 'file/upload/fop_if_empty/' . $data_qc_alita['user_id'] . '.png'; //QC PT. Alita Praya Mitra

		$data['alita_name'] = '';
		$data['alita_date'] = '';
		$data['alita_contact'] = '';

		$data['alita_qc_name'] = '';
		$data['alita_qc_date'] = '';
		$data['alita_qc_contact'] = '';

		$data['hs_name'] = '';
		$data['hs_date'] = '';
		$data['hs_contact'] = '';

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
		$font_path = './file/verdana.ttf';	// Set Path to Font File
		if (!empty($path_fop_alita) && ($progres['progress_status'] == 5)) {
			// $file = base_url().$path_fop_alita;
			$file = "http://10.0.5.85:5050/" . $path_fop_alita;
			// $file = "https://privaterepositorynas.alita.id/" . $path_fop_alita;
			// print_r("http://10.0.5.37/".$path_fop_alita);die();
			//log_message('debug_file1',$file);
			
			//* EDITED (14-11-2023) PENAMBAHAN GET IMAGE SIZE
			if (@getimagesize($file)) {
				$what = @getimagesize($file);
				switch (strtolower($what['mime'])) {
				case 'image/png':
					$image_ttd_alita = imagecreatefrompng($file);
					break;
				case 'image/jpeg':
					$image_ttd_alita = imagecreatefromjpeg($file);
					break;
				case 'image/gif':
					$image_ttd_alita = imagecreatefromgif($file);
					break;
					default:
					die();
				}
				//$image_ttd_alita = imagecreatefrompng(base_url().$path_fop_alita);
				$color_alita = imagecolorallocate($image_ttd_alita, 0, 0, 0); //black ( ,0,0,0)
				imagettftext($image_ttd_alita, 24, 0, 10, 40, $color_alita, $font_path, $data_alita['created_on']);
				imagettftext($image_ttd_alita, 24, 0, 10, 80, $color_alita, $font_path, $data_alita['latitude'] . ',' . $data_alita['longitude']);
				imagettftext($image_ttd_alita, 24, 0, 10, 120, $color_alita, $font_path, $data_alita['contact_no']);
				imagejpeg($image_ttd_alita, './file/upload/ttd/ttd_fop_alita.jpg');	// Send Image to Browser
				imagedestroy($image_ttd_alita);	// Clear Memory
			}

			$data['ttd_fop_alita'] = '/file/upload/ttd/ttd_fop_alita.jpg';
			$data['alita_name'] = $data_alita['user_name'];
			$data['alita_date'] = $data_alita['created_on'];
			$data['alita_contact'] = $data_alita['contact_no'];
			//*
		} else {
			$sql_if_empty = "SELECT sch.visit_id,sch.pic_id,sch.updated_on,usr.user_name,usr.mobile_number FROM t_trx_schedule AS sch LEFT JOIN t_mtr_user AS usr ON sch.pic_id = usr.user_id WHERE sch.visit_id = $visit_id";
			$query_if_empty = $this->db->query($sql_if_empty);
			$result = array_shift($query_if_empty->result_array());
			$path_if_empty = 'file/upload/fop_if_empty/' . $result['pic_id'] . '.png';

			if (!empty($path_if_empty)) {
				// $file = base_url().$path_if_empty;
				$file = "http://10.0.5.68/" . $path_if_empty;
				//log_message('debug_file2',$file);
				$what = @getimagesize($file);
				if (!empty($what)) {
					switch (strtolower($what['mime'])) {
						case 'image/png':
							$image_ttd_alita = imagecreatefrompng($file);
							break;
						case 'image/jpeg':
							$image_ttd_alita = imagecreatefromjpeg($file);
							break;
						case 'image/gif':
							$image_ttd_alita = imagecreatefromgif($file);
							break;
						default:
							die();
					}
					$color_alita = imagecolorallocate($image_ttd_alita, 0, 0, 0); //black ( ,0,0,0)
					imagettftext($image_ttd_alita, 24, 0, 10, 40, $color_alita, $font_path, $result['updated_on']);
					imagettftext($image_ttd_alita, 24, 0, 10, 120, $color_alita, $font_path, $result['mobile_number']);
					imagejpeg($image_ttd_alita, './file/upload/ttd/' . $result['pic_id'] . '.png');	// Send Image to Browser
					imagedestroy($image_ttd_alita);	// Clear Memory

					$data['ttd_fop_alita'] = '/file/upload/ttd/' . $result['pic_id'] . '.png';
					$data['alita_name'] = $result['user_name'];
					$data['alita_date'] = $result['updated_on'];
					$data['alita_contact'] = $result['mobile_number'];
				} else {
					$data['ttd_fop_alita'] = '/assets/images/noimage.png';
				}
			} else {
				$data['ttd_fop_alita'] = '/assets/images/noimage.png';
			}
		}

		//QC Alita
		if (!empty($path_qc_alita) && ($progres['progress_status'] == 5)) {
			// $file = base_url().$path_qc_alita;
			$file = "http://10.0.5.68/" . $path_qc_alita;
			$what = @getimagesize($file);
			switch (strtolower($what['mime'])) {
				case 'image/png':
					$image_ttd_alita = imagecreatefrompng($file);
					break;
				case 'image/jpeg':
					$image_ttd_alita = imagecreatefromjpeg($file);
					break;
				case 'image/gif':
					$image_ttd_alita = imagecreatefromgif($file);
					break;
				default:
					die();
			}
			//$image_ttd_alita = imagecreatefrompng(base_url().$path_qc_alita);
			$color_alita = imagecolorallocate($image_ttd_alita, 0, 0, 0); //black ( ,0,0,0)
			imagettftext($image_ttd_alita, 24, 0, 10, 40, $color_alita, $font_path, $data_qc_alita['created_on']);
			//imagettftext($image_ttd_alita, 24, 0, 10, 80, $color_alita, $font_path, $data_qc_alita['latitude'].','.$data_qc_alita['longitude']);
			imagettftext($image_ttd_alita, 24, 0, 10, 120, $color_alita, $font_path, $data_qc_alita['contact_no']);
			imagejpeg($image_ttd_alita, './file/upload/ttd/' . $data_qc_alita['user_id'] . '.png');	// Send Image to Browser
			imagedestroy($image_ttd_alita);	// Clear Memory

			$data['ttd_qc_alita'] = '/file/upload/ttd/' . $data_qc_alita['user_id'] . '.png';
			$data['alita_qc_name'] = $data_qc_alita['user_name'];
			$data['alita_qc_date'] = $data_qc_alita['created_on'];
			$data['alita_qc_contact'] = $data_qc_alita['contact_no'];
		} else {
			$data['ttd_qc_alita'] = '/assets/images/noimage.png';
		}

		if (!empty($path_fop_hs) && ($progres['progress_status'] == 5)) {
			// $file_hs = base_url().$path_fop_hs;
			$file_hs = "http://10.0.5.85:5050/" . $path_fop_hs;
			// $file_hs = "https://privaterepositorynas.alita.id/" . $path_fop_hs;
			// print_r($file_hs);die();
			//* EDITED (14-11-2023) PENAMBAHAN GET IMAGE SIZE
			if (@getimagesize($file_hs)) {
				# code...
				$what_hs = @getimagesize($file_hs);
				switch (strtolower($what_hs['mime'])) {
				case 'image/png':
					$image_ttd_hs = imagecreatefrompng($file_hs);
					break;
				case 'image/jpeg':
					$image_ttd_hs = imagecreatefromjpeg($file_hs);
					break;
				case 'image/gif':
					$image_ttd_hs = imagecreatefromgif($file_hs);
					break;
					default:
					die();
				}
				//$image_ttd_hs = imagecreatefrompng(base_url().$path_fop_hs);
				$color_hs = imagecolorallocate($image_ttd_hs, 0, 0, 0); //black ( ,0,0,0)
				imagettftext($image_ttd_hs, 24, 0, 10, 40, $color_hs, $font_path, $data_hs['created_on']);
				imagettftext($image_ttd_hs, 24, 0, 10, 80, $color_hs, $font_path, $data_hs['latitude'] . ',' . $data_hs['longitude']);
				imagettftext($image_ttd_hs, 24, 0, 10, 120, $color_hs, $font_path, $data_hs['contact_no']);
				imagejpeg($image_ttd_hs, './file/upload/ttd/ttd_fop_hs.jpg');	// Send Image to Browser
				imagedestroy($image_ttd_hs);	// Clear Memory
			}
			//*

			$data['ttd_fop_hs'] = '/file/upload/ttd/ttd_fop_hs.jpg';
			$data['hs_name'] = $data_hs['user_name'];
			$data['hs_date'] = $data_hs['created_on'];
			$data['hs_contact'] = $data_hs['contact_no'];
		} else {
			$sql_if_empty = "SELECT a.user_id, usr.user_name, a.updated_on AS created_on, usr.mobile_number AS contact_no FROM t_trx_schedule_result_approval a LEFT JOIN t_mtr_user AS usr ON a.user_id = usr.user_id where a.visit_id = $visit_id AND a.order_no=2";
			$query_if_empty = $this->db->query($sql_if_empty);
			$result = array_shift($query_if_empty->result_array());
			$path_if_empty = 'file/upload/fop_if_empty/' . $result['user_id'] . '.png';

			// add by Hadi 24 April 2020
			if (!empty($path_if_empty)) {
				$path_if_empty = 'file/upload/fop_if_empty/no_fop1.png';
			}
			// end by Hadi 24 April 2020

			if (!empty($path_if_empty)) {
				// $file = base_url().$path_if_empty;
				$file = "http://10.0.5.68/" . $path_if_empty;
				//log_message('debug_file2',$file);
				$what = @getimagesize($file);
				switch (strtolower($what['mime'])) {
					case 'image/png':
						$image_ttd = imagecreatefrompng($file);
						break;
					case 'image/jpeg':
						$image_ttd = imagecreatefromjpeg($file);
						break;
					case 'image/gif':
						$image_ttd = imagecreatefromgif($file);
						break;
					default:
						die();
				}
				$color_alita = imagecolorallocate($image_ttd, 0, 0, 0); //black ( ,0,0,0)
				imagettftext($image_ttd, 24, 0, 10, 40, $color_alita, $font_path, $result['created_on']);
				imagettftext($image_ttd, 24, 0, 10, 120, $color_alita, $font_path, $result['contact_no']);
				imagejpeg($image_ttd, './file/upload/ttd/' . $result['user_id'] . '.png');	// Send Image to Browser
				imagedestroy($image_ttd);	// Clear Memory

				$data['ttd_fop_hs'] = '/file/upload/ttd/' . $result['user_id'] . '.png';
				$data['hs_name'] = $result['user_name'];
				$data['hs_date'] = $result['created_on'];
				$data['hs_contact'] = $result['contact_no'];
			} else {
				$data['ttd_fop_hs'] = '/assets/images/noimage.png';
			}
		}

		$sql_spv = "SELECT a.user_id, usr.user_name, a.updated_on AS created_on, usr.mobile_number AS contact_no, usr.image_signature FROM t_trx_schedule_result_approval a LEFT JOIN t_mtr_user AS usr ON a.user_id = usr.user_id where a.visit_id = $visit_id AND a.order_no=3";
		$query_spv = $this->db->query($sql_spv);
		$spv = array_shift($query_spv->result_array());

		if (!empty($spv['image_signature']) && $progres['progress_status'] == 5) {
			//$blob = base64_decode(pg_unescape_bytea($spv['image_signature']));
			//blank image
			$blob = base64_decode(pg_unescape_bytea('iVBORw0KGgoAAAANSUhEUgAAAakAAAEwCAIAAACCJxmHAAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAWJQAAFiUBSVIk8AAAA81JREFUeF7t1AEBAAAIwyD7l75BBiG4AfS4DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DerYHfu9+TQMxZ5gAAAAASUVORK5CYII='));
			$image = imagecreatefromstring($blob);

			$color_spv = imagecolorallocate($image, 0, 0, 0); //black ( ,0,0,0)
			imagettftext($image, 24, 0, 10, 40, $color_spv, $font_path, $spv['created_on']);
			imagettftext($image, 24, 0, 10, 80, $color_spv, $font_path, $spv['contact_no']);
			imagejpeg($image, './file/upload/ttd/' . $spv['user_id'] . '.png');	// Send Image to Browser
			imagedestroy($image);

			$data['ttd_spv'] = '/file/upload/ttd/' . $spv['user_id'] . '.png';
			$data['spv_username'] = $spv['user_name'];
			$data['spv_created_on'] = $spv['created_on'];
			$data['mobile_number'] = $spv['contact_no'];
		} else {
			$data['ttd_spv'] = '/assets/images/noimage.png';
			$data['spv_username'] = '';
			$data['spv_created_on'] = '';
			$data['mobile_number'] = '';
		}

		return $data;
	}

	//01. ATP Instalasi iPasolink (Roll Out, Reroute, Swap)
	public function report1($visit_id)
	{
		//(3) GROUP MW Radio System NE		//(4) GROUP MW Radio System FE		//(5) GROUP DISMANTLE
		$data = array();
		$schedule = $this->schedule($visit_id);
		//log_message('debug', 'writeHTMLToPDF1='.$html);
		//log_message('debug', print_r($schedule, TRUE));
		$ttd = $this->ttd($visit_id);

		//All checklist_ID SOW 3 (t_trx_checklist_result) => (3735-3770) (116-255) (302-440)
		/*$result = "
			SELECT checklist_id, checklist_result
			FROM t_trx_checklist_result
			WHERE (checklist_id BETWEEN 3735 AND 3770 OR checklist_id BETWEEN 116 AND 255 OR checklist_id BETWEEN 302 AND 440 OR checklist_id BETWEEN 353 AND 354 ) AND visit_id = $visit_id";*/
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
		if (!empty($data['result'])) {
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID' . $data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		//All checklist_ID SOW 3 (t_trx_checklist_photo) => (256-301) (441-520)
		//Add by Hadi 2018-02-23 (3864-3865)
		/*$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE (checklist_id BETWEEN 256 AND 301 OR checklist_id BETWEEN 441 AND 520 OR checklist_id BETWEEN 3864 AND 3865) AND visit_id = $visit_id";
				//log_message('debug', "SQL photo : " . $photo);*/
		$photo = "
				SELECT checklist_id, photo_name
				FROM t_trx_checklist_result_photo
				WHERE visit_id = $visit_id
				ORDER BY checklist_id ASC";
		//log_message('debug', "SQL photo : " . $photo);
		$query_photo = $this->db->query($photo);
		$data['photo'] = $query_photo->result_array();
		//modified arrray
		$photo_id = array("default");
		$photo_name = array("default");
		$photo_checklistID = array();
		if (!empty($data['photo'])) {
			foreach ($data['photo'] as $data) {
				$photo_id[] = 'ID' . $data['checklist_id'];
				//$photo_name[] = $data['photo_name'];
				//start edit by hadi 20180810
				//$flag_photo = '';
				if (@getimagesize('https://privaterepositorynas.alita.id/' . $data['photo_name'])) {
					//str_replace("world","Peter","Hello world!");
					//$photo_name[] = $data['photo_name'];
					$photo_name[] = str_replace(' ', '%20', $data['photo_name']);
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
		for ($i = 116; $i <= 520; $i++) {
			$all_checklistID[] = $i;
		}
		for ($i = 3735; $i <= 3770; $i++) {
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if (($value >= 256 && $value <= 301) || ($value >= 441 && $value <= 520)) { //t_trx_checklist_result_photo =>(256-301) (441-520)
				$data['ID' . $value] = '/assets/images/noimage.png';
			} else { //t_trx_checklist_result
				$data['ID' . $value] = '';
			}
		}

		//ACCEPTANCE STATUS && ADDITIONAL NOTES
		$sql_acpt = "SELECT * 
					FROM t_trx_schedule_result_approval_sum
					WHERE visit_id = $visit_id ORDER BY created_on DESC LIMIT 1";
		$query_acpt = $this->db->query($sql_acpt);
		$acceptance = array_shift($query_acpt->result_array());
		$data['acceptance_date'] = $acceptance['created_on'];
		if (!empty($acceptance)) {
			$data['acceptance_status'] = $acceptance['acceptance_status'];
			$data['notes'] = $acceptance['remark'];
		} else {
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

		//start updated by hadi - 2019-03-30
		//root
		$kegiatan = array(
			array('id' => '6', 'name' => 'Material Return Form'),
			array('id' => '51', 'name' => 'Persiapan Instalasi'),
			array('id' => '52', 'name' => 'Instalasi'),
			array('id' => '53', 'name' => 'Setup / Commissioning'),
			array('id' => '54', 'name' => 'Others'),
			array('id' => '55', 'name' => 'DISMANTLE (if Available)')
		);

		//'id' => '6',
		$kegiatan[0]['child'] = array(
			array('id' => '83', 'name' => 'Detail Information'),
			array('id' => '82', 'name' => 'General Information'),
			array('id' => '87', 'name' => 'Received by:'),
			array('id' => '85', 'name' => 'Approved By:'),
			array('id' => '84', 'name' => 'Sent By: (yang mengeluarkan)'),
			array('id' => '86', 'name' => 'Delivered By: (courier/forwarder)')
		);
		//'id' => '6',
		//'id' => '83',
		$kegiatan[0]['child'][0]['child'] = array(
			array('id' => '533', 'name' => 'Warranty *) 1'),
			array('id' => '527', 'name' => 'Material Code 1'),
			array('id' => '528', 'name' => 'Description 1'),
			array('id' => '529', 'name' => 'Serial Number 1'),
			array('id' => '530', 'name' => 'Qty 1'),
			array('id' => '531', 'name' => 'Measurement 1'),
			array('id' => '532', 'name' => 'Completeness 1'),
			array('id' => '534', 'name' => 'Material Code 2'),
			array('id' => '535', 'name' => 'Description 2'),
			array('id' => '536', 'name' => 'Serial Number 2'),
			array('id' => '537', 'name' => 'Qty 2'),
			array('id' => '538', 'name' => 'Measurement 2'),
			array('id' => '539', 'name' => 'Completeness 2'),
			array('id' => '540', 'name' => 'Warranty *) 2'),
			array('id' => '541', 'name' => 'Material Code 3'),
			array('id' => '542', 'name' => 'Description 3'),
			array('id' => '543', 'name' => 'Serial Number 3'),
			array('id' => '544', 'name' => 'Qty 3'),
			array('id' => '545', 'name' => 'Measurement 3'),
			array('id' => '546', 'name' => 'Completeness 3'),
			array('id' => '547', 'name' => 'Warranty *) 3'),
			array('id' => '548', 'name' => 'Material Code 4'),
			array('id' => '549', 'name' => 'Description 4'),
			array('id' => '550', 'name' => 'Serial Number 4'),
			array('id' => '551', 'name' => 'Qty 4'),
			array('id' => '552', 'name' => 'Measurement 4'),
			array('id' => '553', 'name' => 'Completeness 4'),
			array('id' => '554', 'name' => 'Warranty *) 4'),
			array('id' => '555', 'name' => 'Material Code 5'),
			array('id' => '556', 'name' => 'Description 5'),
			array('id' => '557', 'name' => 'Serial Number 5'),
			array('id' => '558', 'name' => 'Qty 5'),
			array('id' => '559', 'name' => 'Measurement 5'),
			array('id' => '560', 'name' => 'Completeness 5'),
			array('id' => '561', 'name' => 'Warranty *) 5'),
			array('id' => '562', 'name' => 'Material Code 6'),
			array('id' => '563', 'name' => 'Description 6'),
			array('id' => '564', 'name' => 'Serial Number 6'),
			array('id' => '565', 'name' => 'Qty 6'),
			array('id' => '566', 'name' => 'Measurement 6'),
			array('id' => '568', 'name' => 'Warranty *) 6'),
			array('id' => '569', 'name' => 'Material Code 7'),
			array('id' => '570', 'name' => 'Description 7'),
			array('id' => '571', 'name' => 'Serial Number 7'),
			array('id' => '572', 'name' => 'Qty 7'),
			array('id' => '573', 'name' => 'Measurement 7'),
			array('id' => '574', 'name' => 'Completeness 7'),
			array('id' => '575', 'name' => 'Warranty *) 7'),
			array('id' => '576', 'name' => 'Material Code 8'),
			array('id' => '577', 'name' => 'Description 8'),
			array('id' => '578', 'name' => 'Serial Number 8'),
			array('id' => '579', 'name' => 'Qty 8'),
			array('id' => '580', 'name' => 'Measurement 8'),
			array('id' => '581', 'name' => 'Completeness 8'),
			array('id' => '582', 'name' => 'Warranty *) 8'),
			array('id' => '583', 'name' => 'Material Code 9'),
			array('id' => '584', 'name' => 'Description 9'),
			array('id' => '585', 'name' => 'Serial Number 9'),
			array('id' => '586', 'name' => 'Qty 9'),
			array('id' => '587', 'name' => 'Measurement 9'),
			array('id' => '588', 'name' => 'Completeness 9'),
			array('id' => '589', 'name' => 'Warranty *) 9'),
			array('id' => '590', 'name' => 'Material Code 10'),
			array('id' => '591', 'name' => 'Description 10'),
			array('id' => '593', 'name' => 'Qty 10'),
			array('id' => '594', 'name' => 'Measurement 10'),
			array('id' => '595', 'name' => 'Completeness 10'),
			array('id' => '596', 'name' => 'Warranty *) 10'),
			array('id' => '597', 'name' => 'Material Code 11'),
			array('id' => '598', 'name' => 'Description 11'),
			array('id' => '599', 'name' => 'Serial Number 11'),
			array('id' => '600', 'name' => 'Qty 11'),
			array('id' => '601', 'name' => 'Measurement 11'),
			array('id' => '602', 'name' => 'Completeness 11'),
			array('id' => '603', 'name' => 'Warranty *) 11'),
			array('id' => '604', 'name' => 'Material Code 12'),
			array('id' => '605', 'name' => 'Description 12'),
			array('id' => '606', 'name' => 'Serial Number 12'),
			array('id' => '607', 'name' => 'Qty 12'),
			array('id' => '608', 'name' => 'Measurement 12'),
			array('id' => '609', 'name' => 'Completeness 12'),
			array('id' => '610', 'name' => 'Warranty *) 12'),
			array('id' => '611', 'name' => 'Remark'),
			array('id' => '567', 'name' => 'Completeness 6'),
			array('id' => '592', 'name' => 'Serial Number 10')
		);
		//'id' => '6',
		//'id' => '82',
		$kegiatan[0]['child'][1]['child'] = array(
			array('id' => '522', 'name' => 'Doc. Header Text'),
			array('id' => '523', 'name' => 'Receiving Plant'),
			array('id' => '524', 'name' => 'Storage Location'),
			array('id' => '525', 'name' => 'Recipient'),
			array('id' => '521', 'name' => 'Delivery Date'),
			array('id' => '526', 'name' => 'Nama penerima barang')
		);
		//'id' => '6',
		//'id' => '87',
		$kegiatan[0]['child'][2]['child'] = array(
			array('id' => '624', 'name' => 'Sign'),
			array('id' => '625', 'name' => 'Name'),
			array('id' => '626', 'name' => 'Department'),
			array('id' => '627', 'name' => 'Date')
		);
		//'id' => '6',
		//'id' => '85',
		$kegiatan[0]['child'][3]['child'] = array(
			array('id' => '616', 'name' => 'Sign'),
			array('id' => '617', 'name' => 'Name'),
			array('id' => '618', 'name' => 'Department'),
			array('id' => '619', 'name' => 'Date')
		);
		//'id' => '6',
		//'id' => '84',
		$kegiatan[0]['child'][4]['child'] = array(
			array('id' => '612', 'name' => 'Sign'),
			array('id' => '613', 'name' => 'Name'),
			array('id' => '615', 'name' => 'Date'),
			array('id' => '614', 'name' => 'Department')
		);
		//'id' => '6',
		//'id' => '86',
		$kegiatan[0]['child'][5]['child'] = array(
			array('id' => '620', 'name' => 'Sign'),
			array('id' => '621', 'name' => 'Name'),
			array('id' => '622', 'name' => 'Department'),
			array('id' => '623', 'name' => 'Date')
		);

		//'id' => '51',
		$kegiatan[1]['child'] = array(
			array('id' => '300', 'name' => 'Dokumen Persiapan Instalasi'),
			array('id' => '301', 'name' => 'Alat Keselamatan'),
			array('id' => '302', 'name' => 'Site View'),
			array('id' => '303', 'name' => 'Tower View'),
			array('id' => '304', 'name' => 'Shelter Inside View')
		);
		//'id' => '51',
		//'id' => '300',
		$kegiatan[1]['child'][0]['child'] = array(
			array('id' => '12', 'name' => 'MOP'),
			array('id' => '13', 'name' => 'RFC'),
			array('id' => '14', 'name' => 'Link Budget'),
			array('id' => '15', 'name' => 'Network Planning Design (IP)'),
			array('id' => '16', 'name' => 'Surat Ijin Kerja dari PT. HS atau Tower Rental'),
			array('id' => '11', 'name' => 'DIN / BOQ')
		);
		//'id' => '51',
		//'id' => '301',
		$kegiatan[1]['child'][1]['child'] = array(
			array('id' => '17', 'name' => 'Alat Keselamatan & Peralatan Kerja')
		);
		//'id' => '51',
		//'id' => '302',
		$kegiatan[1]['child'][2]['child'] = array(
			array('id' => '18', 'name' => 'Access'),
			array('id' => '19', 'name' => 'All Shelter & Tower')
		);
		//'id' => '51',
		//'id' => '303',
		$kegiatan[1]['child'][3]['child'] = array(
			array('id' => '20', 'name' => 'Leg 1'),
			array('id' => '21', 'name' => 'Leg 2'),
			array('id' => '22', 'name' => 'Leg 3'),
			array('id' => '23', 'name' => 'Leg 4')
		);
		//'id' => '51',
		//'id' => '304',
		$kegiatan[1]['child'][4]['child'] = array(
			array('id' => '24', 'name' => 'All Rack 19 Full Rack 19'),
			array('id' => '25', 'name' => 'All Rack 19 Full DCPDB'),
			array('id' => '27', 'name' => 'All Rectifier Load Percentage'),
			array('id' => '28', 'name' => 'Main Busbar'),
			array('id' => '26', 'name' => 'All Rectifier Full Rectifier')
		);

		//'id' => '52',
		$kegiatan[2]['child'] = array(
			array('id' => '305', 'name' => 'Outdoor'),
			array('id' => '306', 'name' => 'Indoor')
		);
		//'id' => '52',
		//'id' => '305',
		$kegiatan[2]['child'][0]['child'] = array(
			array('id' => '29', 'name' => 'Antenna Mounting & Antenna Bracket'),
			array('id' => '30', 'name' => 'Antenna Pemasangan Antenna'),
			array('id' => '31', 'name' => 'Antenna Tampak Belakang Keseluruhan'),
			array('id' => '32', 'name' => 'Antenna Serial Number'),
			array('id' => '33', 'name' => 'Antenna Pelabelan Antenna'),
			array('id' => '34', 'name' => 'ODU Tampak Keseluruhan'),
			array('id' => '35', 'name' => 'ODU Serial Number'),
			array('id' => '37', 'name' => 'ODU Grounding ODU to Busbar'),
			array('id' => '38', 'name' => 'Kabel Coaxial Pemasangan Perlindungan Air (Isolation & Ruber Tape)'),
			array('id' => '39', 'name' => 'Kabel Coaxial Pelabelan Kabel Coaxial'),
			array('id' => '40', 'name' => 'Kabel Coaxial Pemberian Spare Kabel Coaxial'),
			array('id' => '41', 'name' => 'Kabel Coaxial Pemasangan Earthingkit/Groundingkit to Busbar'),
			array('id' => '42', 'name' => 'Kabel Coaxial Pelabelan Earthingkit/Groundingkit to Busbar'),
			array('id' => '43', 'name' => 'Kabel Coaxial Pemasangan Clamp Coaxial pada Tray Tower'),
			array('id' => '44', 'name' => 'Kabel Coaxial Lekukan Kabel sebelum masuk shelter'),
			array('id' => '45', 'name' => 'Kabel Coaxial Pemasangan Silikon di FEP'),
			array('id' => '36', 'name' => 'ODU Pelabelan ODU')
		);
		//'id' => '52',
		//'id' => '306',
		$kegiatan[2]['child'][1]['child'] = array(
			array('id' => '46', 'name' => 'Rack 19 (Pemasangan Baru) Pemasangan Dynabolt pada rack 19'),
			array('id' => '47', 'name' => 'Rack 19 (Pemasangan Baru) Pemasangan Busbar Rack 19'),
			array('id' => '48', 'name' => 'Rack 19 (Pemasangan Baru) Terminasi Busbar Rack 19 ke Main Busbar'),
			array('id' => '49', 'name' => 'Rack 19 (Pemasangan Baru) Pemasangan DCPDB'),
			array('id' => '50', 'name' => 'Rack 19 (Pemasangan Baru) Load Percentage Rectifier yang akan digunakan'),
			array('id' => '51', 'name' => 'Rack 19 (Pemasangan Baru) Pelabelan Rack 19'),
			array('id' => '52', 'name' => 'IDU (Rack 19 existing) Rack 19 yang akan digunakan'),
			array('id' => '53', 'name' => 'IDU (Rack 19 existing) MCB yang akan digunakan'),
			array('id' => '54', 'name' => 'IDU (Rack 19 existing) Pelabelan pada MCB'),
			array('id' => '55', 'name' => 'IDU (Rack 19 existing) Load Percentage Rectifier yang akan digunakan'),
			array('id' => '56', 'name' => 'IDU (Rack 19 existing) Serial Number IDU'),
			array('id' => '57', 'name' => 'IDU (Rack 19 existing) IDU & Rack 19 Tampak Keseluruhan'),
			array('id' => '58', 'name' => 'IDU (Rack 19 existing) IDU Tampak Keseluruhan'),
			array('id' => '59', 'name' => 'IDU (Rack 19 existing) Slot pada IDU yang digunakan'),
			array('id' => '60', 'name' => 'IDU (Rack 19 existing) Pelabelan tipe IDU (Tipe IDU - Index)'),
			array('id' => '61', 'name' => 'IDU (Rack 19 existing) Pelabelan IP IDU (IP Address IDU)'),
			array('id' => '62', 'name' => 'IDU (Rack 19 existing) Grounding pada slot modem IDU'),
			array('id' => '63', 'name' => 'IDU (Rack 19 existing) Terminasi Grounding IDU ke Busbar Rack 19'),
			array('id' => '64', 'name' => 'DDF/LSA DDF/LSA'),
			array('id' => '65', 'name' => 'DDF/LSA Grounding DDF/LSA'),
			array('id' => '66', 'name' => 'DDF/LSA Terminasi Grounding DDF/LSA ke Busbar Rack 19'),
			array('id' => '67', 'name' => 'DDF/LSA Pelabelan DDF/LSA'),
			array('id' => '68', 'name' => 'Kabel Coaxial Pemberian Spare Kabel Coaxial di Tray Indoor'),
			array('id' => '69', 'name' => 'Kabel Coaxial Pelabelan Kabel Coaxial di IDU')
		);

		//'id' => '53',
		$kegiatan[3]['child'] = array(
			array('id' => '307', 'name' => 'Firmware'),
			array('id' => '308', 'name' => 'Current Status'),
			array('id' => '309', 'name' => 'Equipment Setup'),
			array('id' => '310', 'name' => 'Provisioning'),
			array('id' => '311', 'name' => 'PMON / RMON Report'),
			array('id' => '312', 'name' => 'Metering'),
			array('id' => '313', 'name' => 'Equipment Utility'),
			array('id' => '314', 'name' => 'User Account / Security Setting'),
			array('id' => '315', 'name' => 'Inventory')
		);
		//'id' => '53',
		//'id' => '307',
		$kegiatan[3]['child'][0]['child'] = array(
			array('id' => '70', 'name' => 'Checking Firmware Serial (Firmware with the Latest Update)')
		);
		//'id' => '53',
		//'id' => '308',
		$kegiatan[3]['child'][1]['child'] = array(
			array('id' => '71', 'name' => 'Checking Current Status (makesure clear from alarm)')
		);
		//'id' => '53',
		//'id' => '309',
		$kegiatan[3]['child'][2]['child'] = array(
			array('id' => '72', 'name' => 'Equipment Configuration'),
			array('id' => '73', 'name' => 'Radio Configuration'),
			array('id' => '74', 'name' => 'AMR / Radio Mapping Configuration'),
			array('id' => '75', 'name' => 'Network Management Configuration General Setting (Detail) - Auto Discovery Setting'),
			array('id' => '76', 'name' => 'Network Management Configuration Routing Setting'),
			array('id' => '77', 'name' => 'Ping NMS to Server PNMSj')
		);
		//'id' => '53',
		//'id' => '310',
		$kegiatan[3]['child'][3]['child'] = array(
			array('id' => '78', 'name' => 'Modem Function Setting Modem Port Setting'),
			array('id' => '79', 'name' => 'Modem Function Setting Radio Switch Setting'),
			array('id' => '80', 'name' => 'Modem Function Setting TX Power Setting'),
			array('id' => '81', 'name' => 'ETH Function Setting ETH Port Setting'),
			array('id' => '82', 'name' => 'ETH Function Setting VLAN Setting'),
			array('id' => '83', 'name' => 'ETH Function Setting Link Aggregation Setting (if Available)'),
			array('id' => '84', 'name' => 'QOS / Classification Setting Classify Setting'),
			array('id' => '85', 'name' => 'QOS / Classification Setting Port Setting'),
			array('id' => '86', 'name' => 'E1 / STM-1 / Cross Connect Setting Cross Connect Setting'),
			array('id' => '87', 'name' => 'E1 / STM-1 / Cross Connect Setting E1 Port Setting'),
			array('id' => '88', 'name' => 'Equipment Clock / Synchronization Setting Equipment Clock Setting')
		);
		//'id' => '53',
		//'id' => '311',
		$kegiatan[3]['child'][4]['child'] = array(
			array('id' => '89', 'name' => 'MODEM PMON Report')
		);
		//'id' => '53',
		//'id' => '312',
		$kegiatan[3]['child'][5]['child'] = array(
			array('id' => '90', 'name' => 'Current Metering'),
			array('id' => '91', 'name' => 'Current Metering (for Interference Checking)')
		);
		//'id' => '53',
		//'id' => '313',
		$kegiatan[3]['child'][6]['child'] = array(
			array('id' => '92', 'name' => 'Date / Time Setting')
		);
		//'id' => '53',
		//'id' => '314',
		$kegiatan[3]['child'][7]['child'] = array(
			array('id' => '93', 'name' => 'User Account Management'),
			array('id' => '94', 'name' => 'Security Management Service Status Setting (SNMP Community & FTP)')
		);
		//'id' => '53',
		//'id' => '315',
		$kegiatan[3]['child'][8]['child'] = array(
			array('id' => '95', 'name' => 'Equipment Inventory Information IDU'),
			array('id' => '96', 'name' => 'Equipment Inventory Information ODU'),
			array('id' => '97', 'name' => 'S/W License Information ETH')
		);

		//'id' => '54',
		$kegiatan[4]['child'] = array(
			array('id' => '316', 'name' => 'Kabel Optik (if Available)'),
			array('id' => '317', 'name' => 'Kabel LAN'),
			array('id' => '318', 'name' => 'Kabel Traffic'),
			array('id' => '319', 'name' => 'SIM-F'),
			array('id' => '320', 'name' => 'IMS'),
			array('id' => '321', 'name' => 'Kebersihan')
		);
		//'id' => '54',
		//'id' => '316',
		$kegiatan[4]['child'][0]['child'] = array(
			array('id' => '98', 'name' => 'Pemasangan Kabel Optik'),
			array('id' => '99', 'name' => 'Pengukuran Optik (power meter)'),
			array('id' => '100', 'name' => 'Pelabelan Kabel Optik')
		);
		//'id' => '54',
		//'id' => '317',
		$kegiatan[4]['child'][1]['child'] = array(
			array('id' => '101', 'name' => 'Pemasangan Kabel LAN'),
			array('id' => '102', 'name' => 'Pelabelan Kabel LAN')
		);
		//'id' => '54',
		//'id' => '318',
		$kegiatan[4]['child'][2]['child'] = array(
			array('id' => '103', 'name' => 'Pemasangan Kabel Traffic'),
			array('id' => '104', 'name' => 'Pengukuran Traffic (BER-Test)'),
			array('id' => '105', 'name' => 'Pelabelan Kabel Traffic')
		);
		//'id' => '54',
		//'id' => '319',
		$kegiatan[4]['child'][3]['child'] = array(
			array('id' => '106', 'name' => 'SIM-F')
		);
		//'id' => '54',
		//'id' => '320',
		$kegiatan[4]['child'][4]['child'] = array(
			array('id' => '107', 'name' => 'IMS')
		);
		//'id' => '54',
		//'id' => '321',
		$kegiatan[4]['child'][5]['child'] = array(
			array('id' => '108', 'name' => 'Kebersihan & Kerapihan Area Kerja')
		);

		//'id' => '55',
		$kegiatan[5]['child'] = array(
			array('id' => '322', 'name' => 'Add Foto Before Dismantle'),
			array('id' => '323', 'name' => 'Add Foto After Dismantle'),
			array('id' => '324', 'name' => 'Foto Equipment'),
			array('id' => '325', 'name' => 'List material'),
			array('id' => '326', 'name' => 'SIM-F')
		);
		//'id' => '55',
		//'id' => '322',
		$kegiatan[5]['child'][0]['child'] = array(
			array('id' => '109', 'name' => 'Outdoor'),
			array('id' => '110', 'name' => 'Indoor')
		);
		//'id' => '55',
		//'id' => '323',
		$kegiatan[5]['child'][1]['child'] = array(
			array('id' => '111', 'name' => 'Outdoor'),
			array('id' => '112', 'name' => 'Indoor')
		);
		//'id' => '55',
		//'id' => '324',
		$kegiatan[5]['child'][2]['child'] = array(
			array('id' => '113', 'name' => 'Add Foto Equipment Dismantle')
		);
		//'id' => '55',
		//'id' => '325',
		$kegiatan[5]['child'][3]['child'] = array(
			array('id' => '114', 'name' => 'List Material Dismantle')
		);
		//'id' => '55',
		//'id' => '326',
		$kegiatan[5]['child'][4]['child'] = array(
			array('id' => '115', 'name' => 'SIM-F Dismantle')
		);

		$sql5 = 'SELECT remark FROM t_trx_schedule_result_approval WHERE visit_id = ' . $visit_id . ' AND order_no = 1 AND status = 1';
		$query5 = $this->db->query($sql5);
		$remark = array_shift($query5->result_array());

		$i = 0;
		foreach ($kegiatan as $p1) {
			$j = 0;
			foreach ($kegiatan[$i]['child'] as $p2) {
				$k = 0;
				foreach ($kegiatan[$i]['child'][$j]['child'] as $p3) {
					$kegiatan[$i]['child'][$j]['child'][$k]['answer'] = 'OK';
					$kegiatan[$i]['child'][$j]['child'][$k]['remark'] = $remark['remark'];
					$k++;
				}
				$j++;
			}
			$i++;
		}
		//end updated by hadi - 2019-03-30
		$data['kegiatan'] = $kegiatan;

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
		if (!empty($data['result'])) {
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID' . $data['checklist_id'];
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
		if (!empty($data['photo'])) {
			foreach ($data['photo'] as $data) {
				$photo_id[] = 'ID' . $data['checklist_id'];
				$photo_name[] = $data['photo_name'];
				$photo_checklistID[] = $data['checklist_id'];
			}
		}
		$result = array_combine($result_id, $result_name);
		$photo = array_combine($photo_id, $photo_name);
		$data = array_merge($ttd, $schedule, $result, $photo); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i = 3485; $i <= 3519; $i++) {
			$all_checklistID[] = $i;
		}
		for ($i = 3771; $i <= 3802; $i++) {
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID, $photo_checklistID);
		foreach ($check_issetID as $key => $value) {
			if ($value <= 3519) { //t_trx_checklist_result_photo
				$data['ID' . $value] = '/assets/images/noimage.png';
			} else { //t_trx_checklist_result
				$data['ID' . $value] = '';
			}
		}

		return $data;
	}

	//03. ATP Outdorization
	public function report3($visit_id)
	{
		$data = array();
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
		if (!empty($data['result'])) {
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID' . $data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		$result = array_combine($result_id, $result_name);
		$data = array_merge($ttd, $result); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i = 3520; $i <= 3541; $i++) {
			$all_checklistID[] = $i;
		}
		$all_checklistID[] = 3803; //ada yang ketinggalan pendaftaran checklist_id

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			if ($value <= 3527) { //t_trx_checklist_result_photo
				$data['ID' . $value] = '/assets/images/noimage.png';
			} else { //t_trx_checklist_result
				$data['ID' . $value] = '';
			}
		}

		//ACCEPTANCE STATUS //
		$sql_acpt = "SELECT * 
					FROM t_trx_schedule_result_approval_sum
					WHERE visit_id = $visit_id ORDER BY created_on DESC LIMIT 1";
		$query_acpt = $this->db->query($sql_acpt);
		$acceptance = array_shift($query_acpt->result_array());
		$data['acceptance_date'] = $acceptance['created_on'];

		if (!empty($acceptance)) {
			$data['acceptance_status'] = $acceptance['acceptance_status'];
		} else {
			$data['acceptance_status'] = 0;
		}

		return $data;
	}
	//04. ATP Inject License
	public function report4($visit_id)
	{
		$data = array();
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
		if (!empty($data['result'])) {
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID' . $data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		$result = array_combine($result_id, $result_name);
		$data = array_merge($ttd, $result); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i = 3541; $i <= 3562; $i++) {
			$all_checklistID[] = $i;
		}
		$all_checklistID[] = 3563; //checklist_id yang tertinggal pas di daftarin ke db

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			if ($value <= 3548 || $value == 3563) { //t_trx_checklist_result_photo
				$data['ID' . $value] = '/assets/images/noimage.png';
			} else { //t_trx_checklist_result
				$data['ID' . $value] = '';
			}
		}

		return $data;
	}
	//05. ATP Los Survey
	public function report5($visit_id)
	{
		$data = array();
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
		if (!empty($data['result'])) {
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID' . $data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		$result = array_combine($result_id, $result_name);
		$data = array_merge($ttd, $result); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i = 3586; $i <= 3599; $i++) {
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			$data['ID' . $value] = '';
		}

		return $data;
	}
	//06. ATP INstalasi Fixtrout
	public function report6($visit_id)
	{
		$data = array();
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
		if (!empty($data['result'])) {
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID' . $data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		$result = array_combine($result_id, $result_name);
		$data = array_merge($ttd, $result); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i = 3600; $i <= 3617; $i++) {
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			if ($value <= 3603) { //t_trx_checklist_result_photo
				$data['ID' . $value] = '/assets/images/noimage.png';
			} else { //t_trx_checklist_result
				$data['ID' . $value] = '';
			}
		}

		return $data;
	}
	//07. ATP INstalasi Rack'19
	public function report7($visit_id)
	{
		$data = array();
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
		if (!empty($data['result'])) {
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID' . $data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		$result = array_combine($result_id, $result_name);
		$data = array_merge($ttd, $result); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i = 3618; $i <= 3635; $i++) {
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			if ($value <= 3621) { //t_trx_checklist_result_photo
				$data['ID' . $value] = '/assets/images/noimage.png';
			} else { //t_trx_checklist_result
				$data['ID' . $value] = '';
			}
		}

		return $data;
	}
	//08. ATP RFI SCAN
	public function report8($visit_id)
	{
		$data = array();
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
		if (!empty($data['result'])) {
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID' . $data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		$result = array_combine($result_id, $result_name);
		$data = array_merge($ttd, $result); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i = 3636; $i <= 3653; $i++) {
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			if ($value <= 3639) { //t_trx_checklist_result_photo
				$data['ID' . $value] = '/assets/images/noimage.png';
			} else { //t_trx_checklist_result
				$data['ID' . $value] = '';
			}
		}

		return $data;
	}
	//09. ATP SITE SURVEY
	public function report9($visit_id)
	{
		$data = array();
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
		if (!empty($data['result'])) {
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID' . $data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		$result = array_combine($result_id, $result_name);
		$data = array_merge($ttd, $schedule, $result); //All data

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i = 3700; $i <= 3713; $i++) {
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			$data['ID' . $value] = '';
		}

		return $data;
	}
	//10. ATP ADD SERVICE
	public function report10($visit_id)
	{
		$data = array();
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
		if (!empty($data['result'])) {
			foreach ($data['result'] as $data) {
				$result_id[] = 'ID' . $data['checklist_id'];
				$result_name[] = $data['checklist_result'];
				$result_checklistID[] = $data['checklist_id'];
			}
		}

		$result = array_combine($result_id, $result_name);
		$data = array_merge($ttd, $result); //All data	

		//Cek checklist_id not set in t_trx_checklist_result & t_trx_checklist_result_photo = '';
		$checklist_id = array();
		for ($i = 3714; $i <= 3734; $i++) {
			$all_checklistID[] = $i;
		}

		$check_issetID = array_diff($all_checklistID, $result_checklistID);
		foreach ($check_issetID as $key => $value) {
			if ($value <= 3720) { //t_trx_checklist_result_photo
				$data['ID' . $value] = '/assets/images/noimage.png';
			} else { //t_trx_checklist_result
				$data['ID' . $value] = '';
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
