<?php

class Export extends CI_Model {

	var $default_config= array(
                          'Creator'=>'sims',
                          'LastModified'=>'Admin',
                          'Title'=>'Sims Excel File',
                          'Subtitle'=>'Sims Export ',
                          'Subject'=>'Sims Excel File',
                          'Description'=>'Export Sims File ',
                        );
	
	function _do_export($q,$format='excel',$config=null){
                 if ($format=='excel'){
                    $this->_write_excel($config,$q);
                 }   
    }
    
    function _write_excel($config=null,$rows){
        if ($config==null){
            $config= $this->default_config;        
        }


        $records     = $rows->result_array();
        $config      = (object)$config;

        $columns     = array();
        $columnCount = 0;

        foreach ($rows->row_array() as $key => $value) {
            $columns[]=$key;
            $columnCount++;
        }
        // if ($columnCount>50)
        //     $LastField ='B'.chr(65+($columnCount-51));
        // elseif ($columnCount>25)
        //     $LastField ='A'.chr(65+($columnCount-25));
        // else

                    $col_batch = 0;$count=0;
                    foreach ($columns as $value) {
                        $count++;
                            if ($count % 26 == 0){
                              $col_batch ++;
                              $count = 1;
                            }
                    }
                    if ($col_batch>0)
                      $LastField = chr(64+$col_batch).chr(64+$count);
                     else                                        
                      $LastField = chr(65+$count);
                ini_set('memory_limit', '-1');
                set_time_limit(0);
                /** Caching to discISAM 1.0*/
                // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                // $cacheSettings = array( 'memoryCacheSize' => '512MB');
     
                // if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings))
                //    die('CACHEING ERROR');


                    $this->load->library('PHPExcel');
                    $this->load->library('PHPExcel/IOFactory');
                    $objPHPExcel = new PHPExcel();

                    $desc = $config->Description;
                    $objPHPExcel->getProperties()->setCreator($config->Creator)
                                                 ->setLastModifiedBy($config->LastModified)
                                                 ->setTitle($config->Title)
                                                 ->setSubject($config->Subject)
                                                 ->setDescription($desc);

                    $objPHPExcel->getDefaultStyle()->getFont()->setName('Tahoma'); 
                    $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
                    $currentSheet = $objPHPExcel->getActiveSheet();
                    
                    $currentSheet->setCellValue('A1', $config->Title)
                                 ->setCellValue('A2', $config->Subtitle)
                                 ->mergeCells("A1:E1")->mergeCells("A2:E2");

                    // $objPHPExcel->getActiveSheet()->getStyle('A1')
                    //             ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    // $objPHPExcel->getActiveSheet()->getStyle('A2')
                    //             ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $currentSheet->getRowDimension(1)->setRowHeight(20);
                    $currentSheet->getRowDimension(2)->setRowHeight(20);

                    $styleArray = array('font' => array('bold' => true,'size'=>12));
                    $currentSheet->getStyle("A1:E2")->applyFromArray($styleArray);
                    $idx=3;$count=0;
                   

                    $currentSheet->setCellValue('A'.$idx, 'No');
                    $currentSheet->getColumnDimension('A')->setAutoSize(true);

                    $col_batch = 0;
                    foreach ($columns as $value) {
                        $count++;
                            if ($count % 26 == 0){
                              $col_batch ++;
                              $count = 1;
                            }

                            if ($col_batch>0)
                              $str_col_cel = chr(64+$col_batch).chr(64+$count);
                            else                                        
                              $str_col_cel = chr(65+$count);

                    //        echo $str_col_cel."<br>";
                            $currentSheet->setCellValue($str_col_cel.$idx, $value);

                            $currentSheet->getColumnDimension($str_col_cel)->setAutoSize(true);
                    }
                    $currentSheet->getRowDimension($idx)->setRowHeight(25);
                    $currentSheet->getStyle('A'.$idx.":$LastField".$idx)->getFill()
                                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('8E0404');

                    $styleArray = array('font' => array('bold' => true,'size'=>10));
                    $currentSheet->getStyle('A'.$idx.":$LastField".$idx)->applyFromArray($styleArray);
                    $currentSheet->getStyle('A'.$idx.":$LastField".$idx)
                                ->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

            $no=0;$idx++;

            foreach ($records as $row) {
                $no++;

                $count = 0;
                $col_batch = 0;
                    $currentSheet->setCellValue('A'.$idx, $no);
                    foreach ($columns as $value) {
                        $count++;

                            if ($count % 26 == 0){
                              $col_batch ++;
                              $count = 1;
                            }

                            if ($col_batch>0)
                              $str_col_cel = chr(64+$col_batch).chr(64+$count);
                            else                                        
                              $str_col_cel = chr(65+$count);


                            $currentSheet->setCellValue($str_col_cel.$idx, $row[$value]);
                    }

                    $styleArray = array('font' => array('bold' => true,'size'=>10));
                    $currentSheet->getStyle('A'.$idx.":$LastField".$idx)->applyFromArray($styleArray);
                    $currentSheet->getStyle('A'.$idx.":$LastField".$idx)
                                        ->getFont()->getColor()->setARGB('5c5c5c');

                $idx++;
            }



            $objPHPExcel->setActiveSheetIndex(0);
                                                                    
            // Redirect output to a client's web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=".$config->Description.".xlsx");
            header('Cache-Control: max-age=0');

            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;

    }
}