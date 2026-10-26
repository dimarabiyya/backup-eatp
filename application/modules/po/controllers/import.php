 
<?php

class import extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('po_model');	
		$this->load->model('reference_model');			
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));	
	}
	
	public function old_upload(){
		/*Check First if the User has been logged*/
		/*Exit if False*/
		$this->_check_access();
		if(empty($_FILES['file']['name'])){
			$json = array('status' => 'error', 'message' => "Please select file first");
			echo json_encode($json);
			exit;
		}
		/*Check if Folder is Exsist*/
		$import_folder = './file/upload/import/po/';
		!file_exists($import_folder) && mkdir($import_folder , 0777);

		/*Get File .xls uploaded with name file*/
        $fileName = time()."-".$_FILES['file']['name'];
         
        $config['upload_path'] = './file/upload/import/po'; //buat folder dengan nama site pada folder import di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';
        $config['max_size'] = 10000;
         
        $this->load->library('upload');
        $this->upload->initialize($config);
         
        if(! $this->upload->do_upload('file') )
        $error = $this->upload->display_errors();

        $media = $this->upload->data('file');
        $inputFileName = './file/upload/import/po/'.$media['file_name'];
         
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
			for ($row = 2; $row <= $highestRow; $row++) {
			  	$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
			  	/*Date Format From Excel
				: 20-01-2017 => d-m-Y
			  	*/
			  	/*If Empty Continue*/
				if(empty($rowData[$row-1][0])){
					continue;
				}
			  	/*Save Data to Standard Class*/
			  	$post = new stdClass;				
				$post->po_no = $rowData[$row-1][0];
				/*Convert to Date Y-m-D Format from Excel*/
				$post->po_date = date("Y-m-d",strtotime($rowData[$row-1][1]));				
				$post->item = $rowData[$row-1][2];
				$post->order_type = $rowData[$row-1][3];
				$post->description = $rowData[$row-1][4];	
				$post->region_id = $rowData[$row-1][5];
				
				/*select PO BY PO_no*/
				$rs_po = $this->po_model->getByPoNo($rowData[$row-1][0]);
				if($rs_po){
					/*Update Data*/
					$post->updated_on = date('Y-m-d H:i:s');
					$post->updated_by = $this->session->userdata('user_id');
					/*Call Update data*/
					$this->po_model->update($post,$rs_po->po_no);
				}else{
					/*Insert New Data*/
					$post->created_on = date('Y-m-d H:i:s');
					$post->created_by = $this->session->userdata('user_id');
					/*Call Insert data*/
					$this->po_model->insert($post);
				}
				
			  	/*End Saving Data to Standard Class*/
			  	
			}
			/*Delete Import File*/
                     
			$json = array('status' => 'success', 'message' => 'Data added successfully.');				
			echo json_encode($json);
			exit;
            
       exit;
    }

    /*public upload new template import*/
    public function upload(){
    	/*Check First if the User has been logged*/

		/*Exit if False*/
		$this->_check_access();
		if(empty($_FILES['file']['name'])){
			$json = array('status' => 'error', 'message' => "Please select file first");
			echo json_encode($json);
			exit;
		}
		/*Get File .xls uploaded with name file*/
        $fileName = time()."-".$_FILES['file']['name'];
         
        $config['upload_path'] = './file/upload/import/po'; //buat folder dengan nama site pada folder import di root folder
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
        $inputFileName = './file/upload/import/po/'.$media['file_name'];
         
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
			$flag_success = true;
			$message_error = "";
			for ($row = 2; $row <= $highestRow; $row++) {
			  	$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
			  	// echo "<pre/>";
			  	// echo $rowData[$row-1][5];
			  	// print_r($rowData);
			  	/*Get Region Data*/
			  	$region_id = 0;
			  	$excelDate = $rowData[$row-1][1];
			  	$stringDate = \PHPExcel_Style_NumberFormat::toFormattedString($excelDate, 'YYYY-MM-DD');
			  	//echo $stringDate;
			  	if(!$this->isDate($stringDate))
			  	{
			  		$json = array('status' => 'error', 'message' => 'Wrong Date Format, it should be (YYYY-MM-DD)'.$stringDate);				
					echo json_encode($json);
					die;
			  	}

			  	$dt_region = $this->reference_model->getRegion($rowData[$row-1][5]);
			  	if($dt_region){
			  		$region_id = $dt_region->region_id;
			  	}else{
			  		$flag_success = false;
			  		$message_error = $message_error . "Row[".($row-1)."] = Region [".($rowData[$row-1][5])."] Not found in Data Source.<br/>";
			  	}
			  	/*Get Area Data*/
			  	$dt_area = $this->reference_model->getArea($rowData[$row-1][6]);
				$area_id = 0 ;
				
				if($dt_area){
					$area_id = $dt_area->area_id;
				}else{
			  		$flag_success = false;
			  		$message_error = $message_error . "Row[".($row-1)."] = Area [".($rowData[$row-1][6])."] Not found in Data Source.<br/>";
			  	}
				if(!$flag_success){
					continue;
				}
				
				/*Get Data with Po No.*/
				$rs_po = $this->reference_model->getPO($rowData[$row-1][0]);
				/*Check if PO is not Exist*/
				if(!$rs_po){
					/*Object FOR PO Data*/
				  	$data_po = new stdClass;					
					$data_po->po_no = $rowData[$row-1][0];
					$data_po->po_date =date("Y-m-d",strtotime($stringDate));	
					$data_po->region_id = $dt_region->region_id;
					$data_po->created_on = date('Y-m-d H:i:s');
					$data_po->created_by = $this->session->userdata('user_id');
					$po_id = $this->po_model->insert($data_po);
				}else{
					/*make sure that PO is active is_deleted = 0*/
					$data = new stdClass;
					$data->is_deleted = 0;
					$data->updated_by = $this->session->userdata('user_id');
					$data->updated_on = date('Y-m-d H:i:s');
					$data->po_date =date("Y-m-d",strtotime($stringDate));	
					$data->region_id = $dt_region->region_id;
					$this->po_model->update($data, $rs_po->po_id);					
					$po_id = $rs_po->po_id;
				}
			  	
				/*END Class FOR PO Data*/

				/*Check if Data Site Exist*/
				$rs_site_01 = $this->reference_model->getSite($rowData[$row-1][10]);
				if(!$rs_site_01){
					/*Object FOR Site Data 1*/
					$data_site_01 = new stdClass;
					$data_site_01->site_id = $rowData[$row-1][10];
					$data_site_01->site_name = $rowData[$row-1][10];  
					$lat_01 = 0;
					if($rowData[$row-1][12]){
						$lat_01 = floatval($rowData[$row-1][12]);
					} 
					$data_site_01->latitude = $lat_01;
					$long_01 = 0;
					if($rowData[$row-1][13]){
						$long_01 = floatval($rowData[$row-1][13]);
					} 
					$data_site_01->longitude = $long_01;
					$data_site_01->site_address = $rowData[$row-1][11];
					$data_site_01->region_id = $dt_region->region_id;
					$data_site_01->area_id = $dt_area->area_id;
					$type_01 = 0;
					if($rowData[$row-1][14]){
						$type_01 = intval($rowData[$row-1][14]);
					}
					$data_site_01->site_type = $type_01;
					$data_site_01->remarks = '';
					$data_site_01->created_on = date('Y-m-d H:i:s');
					$data_site_01->created_by = $this->session->userdata('user_id');
					$site_id_01 = $this->reference_model->insert_site($data_site_01);
					/*Object FOR Site Data 1*/
				}else{
					$site_id_01 = $rs_site_01->site_id;
				}
				
				$rs_site_02 = $this->reference_model->getSite($rowData[$row-1][15]);
				if(!$rs_site_02){
					/*Object FOR Site Data 2*/
					$data_site_02 = new stdClass;
					$data_site_02->site_id = $rowData[$row-1][15]; 
					$data_site_02->site_name = $rowData[$row-1][15]; 
					$lat_02 = 0;
					if($rowData[$row-1][17]){
						$lat_02 = floatval($rowData[$row-1][17]);
					} 
					$data_site_02->latitude = $lat_02 ;
					$long_02 = 0;
					if($rowData[$row-1][18]){
						$long_02 = floatval($rowData[$row-1][18]);
					} 
					$data_site_02->longitude = $long_02;
					$data_site_02->site_address = $rowData[$row-1][16];
					$data_site_02->region_id = $dt_region->region_id;
					$data_site_02->area_id = $dt_area->area_id;
					$type_02 = 0;
					if($rowData[$row-1][19]){
						$type_02 = intval($rowData[$row-1][19]);
					}
					$data_site_02->site_type = $type_02;
					$data_site_02->remarks = '';
					$data_site_02->created_on = date('Y-m-d H:i:s');
					$data_site_02->created_by = $this->session->userdata('user_id');
					$site_id_02 = $this->reference_model->insert_site($data_site_02);

					/*Object FOR Site Data 2*/
				}else{
					$site_id_02 = $rs_site_02->site_id;
				}
				
				/*insert into t_mtr_site LInk*/
				$link_site = $rowData[$row-1][10]."<>".$rowData[$row-1][15];

				/*Check if Site Link exist*/
				$rs_site_link = $this->reference_model->getLink($link_site);
				if(!$rs_site_link){
					$data_site_link = new stdClass;
					$data_site_link->site_id_ne = $rowData[$row-1][10];
					$data_site_link->site_id_fe = $rowData[$row-1][15];
					$data_site_link->link_id = $link_site;
					$data_site_link->created_on = date('Y-m-d H:i:s');
					$data_site_link->created_by = $this->session->userdata('user_id');
					$link_id_int = $this->reference_model->insert_site_link($data_site_link);
				}else{
					/*Condition if sitelink imported is exist in db and will be updated*/
					$link_id_int = $rs_site_link->link_id_int;
					/*Update data Site Link*/
					$data = new stdClass;
					$data->is_deleted = 0;
					$this->reference_model->update_site_link($data,$link_id_int);					
				}
					
				$rs_link_po = $this->reference_model->getLinkPO($po_id,$link_id_int);
				if(!$rs_link_po){
					/*Insert Into PO LInk*/
					$data_po_link = new stdClass;
					$data_po_link->po_id = $po_id;
					$data_po_link->link_id_int = $link_id_int;
					$data_po_link->ewo_no =  $rowData[$row-1][9];
					$data_po_link->item =  $rowData[$row-1][2];
					$data_po_link->po_type =  $rowData[$row-1][7];
					$data_po_link->po_description =  $rowData[$row-1][4];
					/*check if po_type*/
					if($rowData[$row-1][3] == "EWO"){
						$data_po_link->order_type = 2;
					}else{ /*NBWO*/
						$data_po_link->order_type = 1;
					}
					$this->reference_model->insert_po_link($data_po_link);
				}
																  
			  }
			  if($flag_success){
			  	$json = array('status' => 'success', 'message' => 'Data added successfully.');				
				echo json_encode($json);
			  }else{
			  	$json = array('status' => 'error', 'message' => $message_error);				
				echo json_encode($json);
			  }
		
		exit;

    }

    public function checkPO(){
    	$this->_check_access();
		if(empty($_FILES['file']['name'])){
			$json = array('status' => 'error', 'message' => "Please select file first");
			echo json_encode($json);
			exit;
		}
		/*Get File .xls uploaded with name file*/
        $fileName = time()."-".$_FILES['file']['name'];
         
        $config['upload_path'] = './file/upload/import/po'; //buat folder dengan nama site pada folder import di root folder
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
        $inputFileName = './file/upload/import/po/'.$media['file_name'];
         
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
			$flag_success = true;
			$found_same_po = false;
			$po_data ="";
			for ($row = 2; $row <= $highestRow; $row++) {
			  	$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
                                
			  		$rs_po = $this->reference_model->getPO($rowData[$row-1][0]);
                                            
			  		if($rs_po){
			  			$found_same_po = true;
			  			$po_data = $rowData[$row-1][0];			  			
			  			break;
			  		}
			 }
			 if($found_same_po){
			 	$json = array('status' => 'data-found', 'message' => "We Found PO data with PO NO. = ".$po_data." in Database, are you sure want to update?");
				echo json_encode($json);
				exit;
			 }else{
			 	$json = array('status' => 'success', 'message' => "PO Data is Unique");
				echo json_encode($json);
				exit;
			 }


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
	function isDate($value) 
	{
	    if (!$value) {
	        return false;
	    }

	    try {
	        new \DateTime($value);
	        return true;
	    } catch (\Exception $e) {
	        return false;
	    }
	}
}
 