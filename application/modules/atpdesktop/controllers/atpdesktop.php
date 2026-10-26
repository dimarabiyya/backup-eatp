<?php

class atpdesktop extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('atpdesktop_model');
		$this->load->model('reference_model');
		$this->load->helper('file');
	}
	public function index(){
		$this->_check_access();
		if ($this->core->is_ajax())
		{
			if ($this->input->get('cmd') == 'getList')
			{
				$rows = $this->atpdesktop_model->schedulelist();
				echo json_encode($rows);
				exit;
			}
		}		
		/*Get All The Region Data*/
		$row_regions = $this->reference_model->regionList();
		
		$data['regions'] = json_encode($row_regions);
		$data['content_id'] = 'index';
		
		$this->load->view('template/smashing', $data);
	}
	private function _check_access()
	{
		if ( ! $this->session->userdata('is_login'))
		{
			if ($this->core->is_ajax())
			{
				echo json_encode(array(
					'loggedin' => FALSE,
					'message' => 'You are not logged in. You will be redirected to login page.',
					'redirect_url' => site_url('login')
				));
				exit;
			}
			else
			{
				redirect('login');
			}
		}
	}
	/*Function for ATP Desktop*/
	public function detail($id){
		$schedule_id = $this->core->decode($id);
		$data['content_id'] = 'detail';
		$data['title'] = 'ATP Desktop';
		$data['siteinfo'] = $this->atpdesktop_model->getSchedule($schedule_id);/*0 as Default Schedule*/
		$data['action_url'] = base_url("/atpdesktop/saveAnswer");
		/*List of Checklist*/
		$visitinfo  = $this->atpdesktop_model->pmdesktopdata($schedule_id);
	    $properties = $this->atpdesktop_model->siteProperties();

	   
	    $x = 0;
	    $data['checklist_item'] = array();
	    $answers_by_checklist = array();
	    foreach ($visitinfo['answer'] as $answer) {
	        $answers_by_checklist[$answer['checklist_id']][] = $answer;
	    }
	    foreach ($visitinfo['checklists'] as $checklistitem) {
	      $data['checklist_item'][$x] = $checklistitem;
	      $data['checklist_item'][$x]['answer'] = array();
	      if (isset($answers_by_checklist[$checklistitem['checklist_id']])) {
	          $data['checklist_item'][$x]['answer'] = $answers_by_checklist[$checklistitem['checklist_id']];
	      }
	      if ( ! empty($visitinfo['answer'])) {
	          $data['checklist_item'][$x]['view'] = $this->generate_view($checklistitem, $data['checklist_item'][$x]['answer']);
	      }
	      $x++;
	    }
	     
	   
	    $siteProperties = array();
	    $x   = 0;
	    $items_by_group = array();
	    foreach ($data['checklist_item'] as $ii) {
	        $items_by_group[$ii['checklist_group']][] = $ii;
	    }
	    foreach ($properties['group'] as $gg) {
	      $siteProperties[$x] = $gg;
	      if (isset($items_by_group[$gg['group_id']])) {
	          $siteProperties[$x]['checklists'] = $items_by_group[$gg['group_id']];
	      }
	      $x++;
	    }

	    $new = array();
	    foreach ($siteProperties as $a){
	      $new[$a['parent_id']][] = $a;
	    }
	    $data['checklistsgrouping'] = $this->treeGenerator($new, $new[598]);
		/*End List of Checklist*/
		
		$this->load->view('template/smashing', $data);
	}
	/*Function for Save ATP Desktop Result*/
	//* UPDATE (Kompres gambar via )
	public function compress($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
				$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
				$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
				$image = @imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
	}
	//*
	/*Function for Save ATP Desktop Result*/
	public function saveAnswer()
	{
		ini_set("memory_limit", "-1");
		set_time_limit(0);
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$visit_id = $this->core->decode($this->input->post('visit_id'));

			$checklist = $this->input->post();

			// print_r([
			// 	'checklist' => $checklist,
			// 	'file'		=> $_FILES
			// ]);die();
	    	
	    	foreach($checklist as $key=>$value)
	    	{
	    		if ($key != "visit_id" && (!empty($value)))
	    		{
		    		$data_posted = new stdClass;
		    		$data_posted->visit_id = $visit_id;
		    		$data_posted->checklist_id = $key;
		    		$data_posted->checklist_result = $value;
		    		$data_posted->created_on = date('Y-m-d H:i:s');
					$data_posted->created_by = $this->session->userdata('user_id');
					
					$this->atpdesktop_model->saveAnswer($data_posted);
		    	}
	    	}
	    	

	    	foreach($_FILES as $key=> $file)
	    	{
	    		$check_id = substr($key, 4);
	    		
	    		if ($_FILES[$key]["name"])
	    		{		
						$path_parts = pathinfo($_FILES[$key]["name"]);
						//printf($path_parts); die();
						$extension = $path_parts['extension'];
						$photo_name = "desk-".$key."-".time();
						//echo $photo_name;
						$file_name  = $photo_name.".".$extension;
						if ( ! is_safe_upload_name($file_name)) continue;
						//$root_folder = "file/upload";
						/*Check if Folder atp desktop if exist*/
						$path = "file/upload/";
						$path_atpdesktop = $path . "atpdesktop/";
						$year_folder = $path_atpdesktop . date("Y");
						$month_folder = $year_folder . '/' . date("m");
						$date_folder = $month_folder . '/' . date("d");

						!file_exists($path_atpdesktop) && mkdir($path_atpdesktop , 0777);				
						!file_exists($year_folder) && mkdir($year_folder , 0777);
						!file_exists($month_folder) && mkdir($month_folder, 0777);
						!file_exists($date_folder) && mkdir($date_folder, 0777);
						
						$folder_save = $date_folder;

						//* KOMPRES GAMBAR 
						$file_location_temp = FCPATH.$folder_save.'/'.$file_name; //
						move_uploaded_file($_FILES[$key]["tmp_name"], $file_location_temp);
						$this->compress($file_location_temp, $file_location_temp, 20);
						copy($file_location_temp, $_FILES[$key]["tmp_name"]);
						unlink($file_location_temp);
						//*

						//Start::upload - 22/05/2022 (ilham sihabudin)
						$headers = array("Content-Type:multipart/form-data");
						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_URL => 'http://10.0.5.85:5050/upload.php',
							CURLOPT_HEADER => true,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_HTTPHEADER => $headers,
							CURLOPT_ENCODING => "",
							CURLOPT_TIMEOUT => 30,
							CURLOPT_POST => 1,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => "POST",
							CURLOPT_POSTFIELDS => [
								'path' 	=> 'file/upload/atpdesktop/'.date('Y/m/d').'/',
								'image' => new CURLFile($_FILES[$key]['tmp_name'], $_FILES[$key]['type'], $file_name),
							],
						));
						$response = curl_exec($curl);
						//End::upload - 22/05/2022 (ilham sihabudin)

						$data_posted = new stdClass;
						$data_posted->visit_id = $visit_id;
						$data_posted->checklist_id = $check_id;
						$data_posted->photo_name = $folder_save."/".$file_name;
						$data_posted->photo_thumbnail = $folder_save."/".$file_name;
						$data_posted->created_on = date('Y-m-d H:i:s');
						$data_posted->created_by = $this->session->userdata('user_id');					
							
						$this->atpdesktop_model->saveAnswerPhoto($data_posted);

						// $result_array = uploadDocument($key,'image',$folder_save,$file_name);
						
						// if ($result_array["success"])
						// {					
						// 	//Start::ftp upload - 22/05/2022 (ilham sihabudin)
						// 	// $this->load->library('ftp');

						// 	// $config['hostname'] = '10.0.5.85';
						// 	// $config['username'] = 'msdev';
						// 	// $config['password'] = '@4l1t4devms';
						// 	// $config['debug'] = TRUE;
					
						// 	// $this->ftp->connect($config);
					
						// 	// if ($this->ftp->list_files('/Backup_eatpTRM2/file/upload/atpdesktop/'.date('Y/m/d').'/') == FALSE) {
						// 	// 	$this->ftp->mkdir('/Backup_eatpTRM2/file/upload/atpdesktop/'.date('Y/m/d').'/', DIR_WRITE_MODE);
						// 	// }

						// 	// $destination = '/Backup_eatpTRM2/file/upload/atpdesktop/'.date('Y/m/d').'/'.$file_name;

						// 	// $this->ftp->upload($folder_save.'/'.$file_name, $destination);
					
						// 	// $this->ftp->close();
							
						// }
					}
	    		
	    	}
	    	
	    	/*Update Schedule*/
	    	$sch_post = new stdClass;
	    	$sch_post->status = 3;/*visited*/
	    	$sch_post->pm_desktop_by = $this->session->userdata('user_id');	
	    	$sch_post->pm_desktop_on = date('Y-m-d H:i:s');
	    	
	    	$this->atpdesktop_model->updateSchedule($sch_post,$visit_id);
	    	
	    	$referred_from = site_url('atpdesktop');
			
			redirect($referred_from, 'refresh');
		}
		else
		{
			redirect('atpdesktop');
		}
	}

	public function view($view)
	{
		$this->load->view($view);
	}
	function treeGenerator(&$list, $parent){
	    $tree = array();
	    foreach ($parent as $k=>$l) {
	      if(isset($list[$l['group_id']])){
	        $l['sub_group'] = $this->treeGenerator($list, $list[$l['group_id']]);
	      }
	      $tree[] = $l;
	    } 
	    return $tree;
	}

	private function generate_view($data, $answer)
  {
    $vclass = '';
    if($data['mandatory'] == 1){
      $vclass = "required";
    }
    $d = '';
    switch ($data['checklist_type']) {
      case 'textbox': $d = $this->_gen_textbox($data, $answer, $vclass); break;
      case 'textarea': $d = $this->_gen_textarea($data, $answer, $vclass); break;
      case 'datepicker': $d = $this->_gen_datepicker($data, $answer, $vclass); break;
      case 'radiobutton': $d = $this->_gen_radiobutton($data, $answer, $vclass); break;
    }
    if($data['has_photo'] == 1) {
      $d .= '<div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="input-append">
                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="pic_'.$data["checklist_id"].'" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
              </div>';
        if($data["photo_thumbnail"]){
        	$d .= '&nbsp;&nbsp;<code>File Uploaded : <a href="'.base_url('')."".$data["photo_thumbnail"].'" target="_blank"><i class="icon-picture"></i></a></code>';	
        }      	
        $d .= '</div>';
    }
    return $d;
  }

  private function _gen_textarea($data, $answer, $vclass) {
    $s = '<textarea name="'.$data["checklist_id"].'" id="checklist_id_'.$data["checklist_id"].'" '.$vclass.' >'.$data["pm_result"].'</textarea>';
    return $s;
  }
  private function _gen_textbox($data, $answer, $vclass) {
    $s = '<input type="text" name="'.$data["checklist_id"].'" id="checklist_id_'.$data["checklist_id"].'" value="'.$data["pm_result"].'" '.$vclass.' />';
    return $s;
  }
  private function _gen_datepicker($data, $answer, $vclass) {
    $s = '<input name="'.$data["checklist_id"].'" id="checklist_id_'.$data["checklist_id"].'" class="datepicker" '.$vclass.' />'.$data["pm_result"];
    return $s;
  }
  private function _gen_checkbox($data, $answer, $vclass) {
    foreach ($answer as $k) {
      if($k['option'] == $data['pm_result']) {
        $s .= '<input type="radio" name="'.$data["checklist_id"].'" value="'.$k["option"].'" id="option_id_'.$k["option_id"].'" checked '.$vclass.' /> 
             <label for="option_id_'.$k["option_id"].'">'.$k["option"].'</labe>';
      }
      else {
        $s .= '<input type="radio" name="'.$data["checklist_id"].'" value="'.$k["option"].'" id="option_id_'.$k["option_id"].'" /> 
             <label for="option_id_'.$k["option_id"].'" '.$vclass.'>'.$k["option"].'</labe>';
      }
    }
    return $s;
  }

  private function _gen_radiobutton($data, $answer, $vclass) {
    $s = '';
    foreach ($answer as $k) {
      if($k['option'] == $data['pm_result']) {
        $s .= '<label for="option_id_'.$k["option_id"].'">
              <input type="radio" name="'.$data["checklist_id"].'" value="'.$k["option"].'" id="option_id_'.$k["option_id"].'" checked '.$vclass.' /> 
             '.$k["option"].'</label>';
      }
      else {
        $s .= '<label for="option_id_'.$k["option_id"].'">
              <input type="radio" name="'.$data["checklist_id"].'" value="'.$k["option"].'" id="option_id_'.$k["option_id"].'" '.$vclass.' /> 
             '.$k["option"].'</label>';
      }
    }
    return $s;
  }
  private function _gen_photo($data, $answer, $vclass)
  {
    # code...
  }
}
