<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); error_reporting(0);
    /* Import Controller
     * Author      : alen@alenlabs.com
     * Module demo : http://10.0.15.5/0/svn/sims/isr
     *
     * How to use?
     * 1. Define your module name (ex: isr)
     * 2. Using fancybox,open base_url("import/index/$module_name"), ex: base_url("import/index/isr") . This will open multiple upload form.
     * 3. Create function in this controller and named like your module name with 'import_' as the prefix, ex : function import_isr(){}
     * 4. When the upload file is success, it will reffer to your function you've created.
     * 5. See import_isr() function for example to read EXCEL format.
     * 6. If you are developer and don't know what kind of thing i mentioned above, it's time to go home.
     * 7. ....
     * 8. Why still reading this? don't you find it useless? now go back to work.
    */
class Import extends CI_Controller {
    var $allowed_filetypes = array('xlsx','xls');

    function __construct()
    { 
        parent::__construct();
        $this->load->helper('file');
        $this->load->helper('directory');

        $this->mglobal->is_logged_in();
    } 


/* BEGIN MODULE FUNCTION 
   Write down your function here*/



    function import_isr($targetFile){
          //get file extension
          $x = explode('.', $targetFile);          
          $file_ext = $x[count($x)-1];

          $x = explode('/', $targetFile);
          $file_name = $x[count($x)-1];
          
          if($file_ext == 'xls'  || $file_ext == 'xlsx')
          {
                $rows = $this->_read_excel_file($targetFile,$file_ext);
                $total_records = 0;
                $x=0;
                foreach($rows as $key=>$row) {
                    // get cell $row[0],$row[1].. depends on indexes, do your stuff here

                    $total_records++;
                }

                echo $this->_markup("<b>$file_name</b> success, $total_records records has been inserted.");

//                unlink($targetFile); // remove file, because i found it useless.
          }else
          {

                echo $this->_markup("$file_name upload failed.","error");
          }

    }



/* END MODULE FUNCTION */

























































    function _markup($text,$type="success"){
      $baseurl=base_url();
      switch ($type) {
        case 'success':
          return "<img src='$baseurl/assets/images/notifications/tick.png'/>$text<br>"; 
          break;
        case 'error':
          return "<img src='$baseurl/assets/images/notifications/exclamation.png'/>$text<br>";
          break;
        case 'critical':
          return "<img src='$baseurl/assets/images/notifications/critical.png'/>$text<br>";
          break;
        default:
          return "<img src='$baseurl/assets/images/notifications/exclamation.png'/>$text<br>";
          break;
      }
    }
    function index($module_name=""){
        $data['module_name'] = $module_name;
        $data['judul']       = "Select file to import";
        $this->load->view('import/form', $data, FALSE);
    }
    function doUpload($module_name=""){
            if ($module_name==""){
               echo $this->_markup("Please define module name.!","critical");
                return;
            }
            if (!file_exists('uploads/'.$module_name)){
                mkdir('uploads/'.$module_name);
                chmod('uploads/'.$module_name, 0777);
            }
            $absolutePath  =str_replace($_SERVER['DOCUMENT_ROOT'], "", FCPATH);
            $targetFolder  = $absolutePath .'uploads/'.$module_name; 
            $function_name = 'import_'.$module_name;

            $verifyToken = md5('unique_salt' . $_POST['timestamp']);

            if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
            $targetFile = rtrim($targetPath,'/') . '/'. $_FILES['Filedata']['name'];
            
            // Validate the file type
            $fileTypes = $this->allowed_filetypes; // File extensions
            $fileParts = pathinfo($_FILES['Filedata']['name']);
            
            if (in_array($fileParts['extension'],$fileTypes) && is_safe_upload_name($_FILES['Filedata']['name'])) {
              move_uploaded_file($tempFile,$targetFile);
              $this->$function_name($targetFile);

            } else {
                echo $this->_markup("$file_name upload failed!","error");
            }
          }

    }
    function _read_excel_file($targetFile,$file_ext){
                $file_ext=strtolower($file_ext);
                $this->load->library('PHPExcel');
                $this->load->library('PHPExcel/IOFactory');
                        $objPHPExcel = new PHPExcel();

                      if ($file_ext=='csv'){
                        try {
                             $objReader = IOFactory::createReader('Excel5');
                             $objPHPExcel = $objReader->load($targetFile);                                        
                            } catch (Exception $e) {                                    
                             $objReader = IOFactory::createReader('Excel2007');
                             $objPHPExcel = $objReader->load($targetFile);
                            }
                      }else{
                             $objReader = IOFactory::createReader('CSV');
                             $objPHPExcel = $objReader->load($targetFile);                        
                      }
                                if (!$objPHPExcel){
                                    $baseurl=base_url();
                                    echo "<img src='$baseurl/assets/images/notifications/critical.png'/> Invalid excel file.<br>";
                                    return;
                                }

                                    $rows="";
                                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                                        $i=0;
                                        foreach ($worksheet->getRowIterator() as $row) {
                                            if($i != 0) {
                                                $cellIterator = $row->getCellIterator();
                                                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                                                $j=0;
                                                foreach ($cellIterator as $cell) {
                                                    if (!is_null($cell)) {
                                                        //$rows[$i][$j]=$cell->getFormattedValue();
                                                        $rows[$i][$j]=$cell->getValue();
                                                    }
                                                    else {
                                                        $rows[$i][$j]=0;
                                                    }
                                                    $j++;
                                                }
                                            }
                                            $i++;
                                        }                       
                                    }

                return $rows;

    }










}