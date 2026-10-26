<?php

class import extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('bom_model');
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
		$import_folder = './file/upload/import/bom/';
		!file_exists($import_folder) && mkdir($import_folder , 0777);

		/*Get File .xls uploaded with name file*/
        $fileName = time()."-".$_FILES['file']['name'];
         
        $config['upload_path'] = './file/upload/import/bom'; //buat folder dengan nama site pada folder import di root folder
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

        $inputFileName = './file/upload/import/bom/'.$media['file_name'];
         
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

            $flag_po_or_link = false;
			$dataexcel = Array();
			$message = "Data added successfully.";
			$message_error = "";
			for ($row = 2; $row <= $highestRow; $row++) {
			  	$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
			  	//print_r($rowData);
			  	/*Date Format From Excel
				: 20-01-2017 => d-m-Y
			  	*/
			  	
			  	/*Save Data to Standard Class*/
			  	$post = new stdClass;				
				$post->code = $rowData[$row-1][3];
				$post->config = $rowData[$row-1][2];
				$post->material_description = $rowData[$row-1][4];
				$post->uom = $rowData[$row-1][5];
				$post->qty_requirement = $rowData[$row-1][6];
				$post->qty_po = $rowData[$row-1][7];
				$post->qty_actual = 0;
				$post->status_boq = 1;
				
				
				/*If Empty Continue*/
				if(empty($rowData[$row-1][0])){
					continue;
				}
				/*Lookup PO_ID by PO_NO from trx_po_xl*/
				
				$rs_po = $this->reference_model->getPoBy($rowData[$row-1][0]);
				if($rs_po){
					$post->po_id = $rs_po->po_id;

				}else{		
					$flag_po_or_link =true;	
					$message_error .="PO [".$rowData[$row-1][0]."] is not found in database<br/>";	
					continue;
				}
				/*Lookup link_int_id by link_id from t_mtr_site_link*/
				$rs_link = $this->reference_model->getLink($rowData[$row-1][1]);
				if($rs_link){
					$post->link_id_int = $rs_link->link_id_int;
				}else{
					$flag_po_or_link =true;
					$message_error .="Link [".$rowData[$row-1][1]."] is not found in database<br/>";
					continue;
				}
				/*select BOM BY PO_NO & LINK_ID*/
				$rs_bom = $this->reference_model->getBomByPoLink($rs_po->po_id,$rs_link->link_id_int,$post->code,$post->config);
				if($rs_bom){
					/*Update Data*/
					$post->is_deleted = 0;
					$post->updated_on = date('Y-m-d H:i:s');
					$post->updated_by = $this->session->userdata('user_id');
					/*Call Update data*/
					$this->bom_model->update($post,$rs_bom->bom_id);
				}else{
					/*Insert New Data*/
					$post->created_on = date('Y-m-d H:i:s');
					$post->created_by = $this->session->userdata('user_id');
					/*Call Insert data*/
					$this->bom_model->insert($post);				
				}
				
			  	/*End Saving Data to Standard Class*/
			  	
			}
			/*Delete Import File*/
            if($flag_po_or_link){
            	$json = array('status' => 'error', 'message' => $message_error);
            }else{
            	$json = array('status' => 'success', 'message' => $message);				
            }			
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
