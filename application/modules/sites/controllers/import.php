<?php

class import extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('site_model');	
		$this->load->model('reference_model');
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));	
	}
	
	public function upload(){
		/*Check First if the User has been logged*/
		/*Exit if False*/
		$this->_check_access();
		if(empty($_FILES['file']['name'])){
			$json = array('status' => 'error', 'message' => "Please select file first");
			echo json_encode($json);
			exit;
		}

		/*Check if Folder is Exsist*/
		$import_folder = './file/upload/import/site/';
		!file_exists($import_folder) && mkdir($import_folder , 0777);

		/*Get File .xls uploaded with name file*/
        $fileName = time()."-".$_FILES['file']['name'];
         
        $config['upload_path'] = './file/upload/import/site'; //buat folder dengan nama site pada folder import di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 10000;
         
        $this->load->library('upload');
        $this->upload->initialize($config);
         
        if(! $this->upload->do_upload('file') )
        if($this->upload->display_errors())
        {
        	$json = array('status' => 'error', 'message' => "The filetype you are attempting to upload is not allowed.<br/>You have to upload only *.xls or *.xlsx file");				
			echo json_encode($json); 
			die;
        }
             
        $media = $this->upload->data('file');
        $inputFileName = './file/upload/import/site/'.$media['file_name'];
         
        try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }
 
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();


			$dataexcel = Array();
			$message = "Data added successfully.";
			$status = "success";
			for ($row = 2; $row <= $highestRow; $row++) {
			  	$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
			  	/*Save Data to Standard Class*/
			  	$post = new stdClass;				
				$post->site_id = $rowData[$row-1][0];
				$post->site_name = $rowData[$row-1][1];
				$post->site_address = $rowData[$row-1][2];
				$post->latitude = $rowData[$row-1][3];
				$post->longitude = $rowData[$row-1][4];
				

				/*If Empty Continue*/
				if(empty($rowData[$row-1][0])){
					continue;					
				}

				/*Check if Latitude and Longitude is Double*/
				try{
					if(is_float($rowData[$row-1][3]) && is_float($rowData[$row-1][4])){						
						//continue;
					}else{
						$message = "We found latitude or longitude is not valid";
						$json = array('status' => 'error', 'message' => $message);				
						echo json_encode($json);
						die;
					}
				}catch(Exception $e){
					$message = "Make sure your latitude and longitude in double type";
					$json = array('status' => 'error', 'message' => $message);				
					echo json_encode($json);
					die;
				}

				/*Lookup Area Name */
				$res_area= $this->reference_model->getAreaByName($rowData[$row-1][6]);
				if(!$res_area){
					$status = "error";
					$message = "Data Area ".$rowData[$row-1][6]." is not found in database, please check it again";
					$json = array('status' => $status, 'message' => $message);				
					echo json_encode($json);
					die;
				}else{
					$post->area_id = $res_area->area_id;
					$post->region_id = $res_area->region_id;
				}

				/*Get ID Sit type*/
				$res_type= $this->reference_model->getSiteType($rowData[$row-1][5]);
				if(!$res_type){
					$status = "error";
					$message = "Site type ".$rowData[$row-1][5]." not found in database, please check it again";
					$json = array('status' => $status, 'message' => $message);				
					echo json_encode($json);
					die;
				}else{
					$post->site_type = $res_type->lookup_id;
				}
				
				$region_id = $res_area->region_id;
				/*Set Region ID*/
				$post->region_id = $region_id;
				$post->remarks = '-';
				/*select Site BY Site_ID*/
				$rs_site = $this->site_model->getSiteByName($rowData[$row-1][0]);
				if($rs_site){
					/*Update Data*/
					$post->updated_on = date('Y-m-d H:i:s');
					$post->updated_by = $this->session->userdata('user_id');
					/*Call Update data*/
					//echo json_encode($post);
					$this->site_model->update($post,$rs_site->site_id);
				}else{
					/*Insert New Data*/
					$post->created_on = date('Y-m-d H:i:s');
					$post->created_by = $this->session->userdata('user_id');
					/*Call Insert data*/
					$this->site_model->insert($post);
					//echo json_encode($post);
				}
				//die;
				
			  	/*End Saving Data to Standard Class*/
			  	
			}
			/*Delete Import File*/
                     
			$json = array('status' => $status, 'message' => $message);				
			echo json_encode($json);
			exit;
            
       exit;
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
}
