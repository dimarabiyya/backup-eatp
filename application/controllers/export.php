<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed'); error_reporting(E_ALL);
/* Export Controller
 * Author      : alen@alenlabs.com
 * Module demo : http://10.0.15.5/0/svn/sims/isr
 *
 * How to use?
 * 1. Define your function name (ex: isr_list)
 * 2. To export a list,Create a button on your form and navigate to http://10.0.15.5/export/?module=isr_list&format=excel
 * 3. Now it suppport excel format, pdf format soon (not promised yet).
 * 4. From the url above it will refer to your isr_list function and export the query to excel file
 * 5. You can add parameter to your function by adding this to your url, example:
  http://10.0.15.5/export/?module=isr_list&format=excel&param[isactive]=1&param[name]=alen
  etc..
  6. Please take a look on my "isr_list" function for example.
  7. Try before ask.
 * 8. If you are developer and don't know what kind of thing i mentioned above, it's time to go home.
 */

class Export extends CI_Controller {

  var $default_config = array(
      'Creator' => 'sims',
      'LastModified' => 'Admin',
      'Title' => 'Sims Excel File',
      'Subtitle' => 'Sims Export ',
      'Subject' => 'Sims Excel File',
      'Description' => 'Export Sims File ',
  );

  function __construct() {
    parent::__construct();
    $this->load->helper('file');
    $this->load->helper('directory');

    $this->mglobal->is_logged_in();
  }

  function isr_list($format, $param = null) {
    $config = array(
        'Creator' => 'sims',
        'LastModified' => 'ISR Admin',
        'Title' => 'ISR List',
        'Subtitle' => 'Active Link ' . date("Ymd"),
        'Subject' => 'Sims ISR',
        'Description' => 'ISR Raw Data ' . date("Ymd"),
    );
    $q = $this->db->get('v_isr_data');
    // print_r($q); die();
    $this->_do_export($q, $format, $config);
  }

  public function pmschedule($format) {
    $param['opr_year'] = $this->input->post('opr_year');
    $param['opr_month'] = $this->input->post('opr_month');
    $param['keyword'] = $this->input->post('keyword');

    if ($this->input->post('opr_cluster') == 'undefined') {
      $param['opr_cluster'] = false;
    } else {
      $param['opr_cluster'] = $this->input->post('opr_cluster');
    }
    if ($this->input->post('opr_region') == 'undefined') {
      $param['opr_region'] = false;
    } else {
      $param['opr_region'] = $this->input->post('opr_region');
    }


    $cluster_name = $this->input->post('cluster_name');
    $region_name = $this->input->post('region_name');
    $tabletype = $this->input->post('tabletype');

    $config = array(
        'Creator' => $this->session->userdata('user_name'),
        'LastModified' => $this->session->userdata('user_name'),
        'Subject' => 'Preventive Maintenance Schedule',
        'Subtitle' => $param['opr_year']
    );
    if ($cluster_name != 'null') {
      $config['Description'] = "Preventive Maintenance Schedule Cluster " . $cluster_name;
    } else {
      $config['Description'] = "Preventive Maintenance Schedule Region " . $region_name;
    }

    $this->load->model('schedule/mpm', 'pm');
    // print_r($param); die();
    $q = '';
    if ($tabletype == 'pic') {
      $config['Title'] = "PM Schedule (Schedule by PIC) Cluster " . $cluster_name;
      $q = $this->pm->export_bypic($param);
    } else if ($tabletype == 'sites') {
      $config['Title'] = "PM Schedule (Schedule by Site) Cluster " . $cluster_name;
      $q = $this->pm->export_bysites($param);
    } else if ($tabletype == 'revision') {
      $config['Title'] = "PM Schedule (Schedule by Site) Cluster " . $cluster_name;
      $q = $this->pm->export_revision($param);
    }
    $this->_do_export($q, $format, $config);
  }

  public function pmsummary($format, $status = 0) {
    $param['opr_year'] = $this->input->post('opr_year');
    $param['opr_month'] = $this->input->post('opr_month');
    $param['opr_cluster'] = $this->input->post('opr_cluster');
    $param['keyword'] = $this->input->post('keyword');
    $param['opr_region'] = $this->input->post('opr_region');
    $param['territorytype'] = $this->input->post('territorytype');
    $cluster_name = $this->input->post('cluster_name');
    $tabletype = $this->input->post('tabletype');

    if ($status == 1) {
      $status = 0;
    }
    $month_name = array(
        '1' => 'January', '7' => 'July',
        '2' => 'February', '8' => 'August',
        '3' => 'March', '9' => 'September',
        '4' => 'April', '10' => 'October',
        '5' => 'May', '11' => 'November',
        '6' => 'June', '12' => 'December'
    );
    $config = array(
        'Creator' => $this->session->userdata('user_name'),
        'LastModified' => $this->session->userdata('user_name'),
        'Subject' => 'Preventive Maintenance Schedule Summary',
        'Title' => 'Preventive Maintenance Schedule Summary',
        'Subtitle' => $param['opr_year'] . ' ' . $month_name[$param['opr_month']],
        'Description' => "Preventive Maintenance Schedule Summary Cluster " . $cluster_name,
    );
    $this->load->model('schedule/mpm', 'pm');
    $q = '';
    $q = $this->pm->export_summary($param, $status);
    $this->_do_export($q, $format, $config);
  }

  public function sischedule($format) {
    $param['opr_year'] = $this->input->post('opr_year');
    $param['opr_month'] = $this->input->post('opr_month');
    $param['opr_cluster'] = $this->input->post('opr_cluster');
    $param['keyword'] = $this->input->post('keyword');
    $cluster_name = $this->input->post('cluster_name');
    $tabletype = $this->input->post('tabletype');

    $config = array(
        'Creator' => $this->session->userdata('user_name'),
        'LastModified' => $this->session->userdata('user_name'),
        'Subtitle' => $param['opr_year'],
        'Subject' => 'Site Inspection Schedule',
        'Description' => "Site Inspection Schedule Region " . $cluster_name,
    );
    $this->load->model('schedule/minspection', 'msi');
    // print_r($param); die();
    $q = '';
    if ($tabletype == 'site') {
      $config['Title'] = "Site Inspection Schedule (Schedule by Site) Region " . $cluster_name;
      $q = $this->msi->export_bysite($param);
    } else if ($tabletype == 'pic') {
      $config['Title'] = "Site Inspection Schedule (Schedule by PIC) Region " . $cluster_name;
      $q = $this->msi->export_bypic($param);
    } else if ($tabletype == 'revision') {
      $config['Title'] = "Site Inspection Schedule (Schedule by PIC) Region " . $cluster_name;
      $q = $this->msi->export_byrev($param);
    }
    $this->_do_export($q, $format, $config);
  }

  function site_info($query_type, $format = 'excel') {
    $config = array(
        'Creator' => 'sims',
        'LastModified' => $this->session->userdata('user_name'),
        'Title' => 'Site Info | ' . $query_type,
        'Subtitle' => 'Data ' . $query_type . ' ' . date("Ymd"),
        'Subject' => 'Site Info',
        'Description' => $query_type . '_' . date("Ymd") . '_' . date("His"),
    );
    $this->load->model('export/' . __FUNCTION__, 'exporter');
    $data = $this->exporter->$query_type($this->input->get());
    //die($this->db->last_query());
    $this->_do_export($data, $format, $config);
  }

  /* Export Project Template */

  function temp_project($query_type, $format = 'excel') {
    $config = array(
        'Creator' => 'sims',
        'LastModified' => $this->session->userdata('user_name'),
        'Title' => 'Site Info | ' . $query_type,
        'Subtitle' => 'Data ' . $query_type . ' ' . date("Ymd"),
        'Subject' => 'Site Info',
        'Description' => 'Project_Template_' . date("Ymd") . '_' . date("His"),
    );
    $this->load->model('export/' . __FUNCTION__, 'exporter');
    $data = $this->exporter->template($query_type);
    $this->_do_export($data, $format, $config);
  }

  /* Export Project Template FOR NPI */

  function temp_project_npi($format = 'excel') {
    $temp_proj_id = $this->input->post('temp_proj_id');
    $template_name = $this->input->post('template_name');
    $config = array(
        'Creator' => 'SIMS',
        'LastModified' => $this->session->userdata('user_name'),
        'Title' => 'Site Info | ' . $temp_proj_id,
        'Subtitle' => 'Data ' . $temp_proj_id . ' ' . date("Ymd"),
        'Subject' => 'Site Info',
        'Description' => 'Template_' . $template_name . '_' . date("Ymd") . '_' . date("His"),
    );
    $this->load->model('export/' . __FUNCTION__, 'exporter');
    $data = $this->exporter->template_npi($temp_proj_id);
    $this->_do_export_npi($data, $format, $config);
  }

  /* Export Problem Monitoring => andedi 2014/10/03 */

  function problem_mo($format = 'excel') {
    $config = array(
        'Creator' => 'SIMS',
        'LastModified' => $this->session->userdata('user_name'),
        'Title' => 'Problem Monitoring | ',
        'Subtitle' => 'Data ' . date("Ymd"),
        'Subject' => 'Problem Monitoring',
        'Description' => 'Problem_Monitoring_' . date("Ymd") . '_' . date("His"),
    );
    $this->load->model('export/' . __FUNCTION__, 'exporter');
    $data = $this->exporter->problem_mo();
    $this->_do_export($data, $format, $config);
  }

//    Export Checklist Template on Preventive Maintenance - andedi 20141210
  function pm_checklist_template($template_id, $template_name, $format = 'excel') {
//        $template_name = str_replace('', "", $template_name);
    $template_name = preg_replace('/[^a-z0-9]20/i', '_', rawurldecode($template_name));
    $config = array(
        'Creator' => 'SIMS',
        'LastModified' => $this->session->userdata('user_name'),
        'Title' => 'Preventive Maintenance - Checklist Template',
        'Subtitle' => 'Data ' . date("Ymd"),
        'Subject' => 'Preventive Maintenance - Checklist Template',
        'Description' => 'PM_Checklist_Template_' . $template_name . '_' . date("Ymd") . '_' . date("His"),
    );
    $this->load->model('export/preventive_maintenance', 'exporter');
    $data = $this->exporter->checklist_template($template_id);
//    die($this->db->last_query());
    $this->_do_export($data, $format, $config, array('temp_order'));
  }

  //User History
  public function user_history() {
    $this->load->model('export/user', 'exporter');

    $title = '';
    $report_type = $this->input->post('report_type');
    if ($report_type != 'load_data_chart__users_attempt_monitoring') {
      $data = $this->exporter->get_user_history('active');
      $title = 'User Active';
    } else {
      $data = $this->exporter->get_user_history();
      $title = 'User Attempt';
    }

    $config = array(
        'Creator' => 'SIMS',
        'LastModified' => $this->session->userdata('user_name'),
        'Title' => $title,
        'Subtitle' => 'Data ' . date("Ymd"),
        'Subject' => $title,
        'Description' => $title . '_' . MakeUpDate($this->input->post('date_from2')) . '_' . MakeUpDate($this->input->post('date_to2')),
    );

//    die($this->db->last_query());
    $this->_do_export($data, 'excel', $config);
  }

  /* MODULE CORE FUNCTION, STAY AWAY FROM THIS AREA */

  function index() {
    $d = $this->input->get();
    if (isset($d['module']) && isset($d['format'])) {
      $module = $d['module'];
      $this->$module($d['format'], $d['param']);
    } else {
      die("Please define module and format type to export.");
    }
  }

  function _do_export($q, $format = 'excel', $config = null, $exclude = array()) {
    if ($format == 'excel') {
      $this->_write_excel($config, $q, $exclude);
    }
  }

  function _do_export_npi($q, $format = 'excel', $config = null) {
    if ($format == 'excel') {
      $this->_write_excel_npi($config, $q);
    }
  }

  function _write_excel_old($config = null, $rows) {
    if ($config == null) {
      $config = $this->default_config;
    }


    $records = $rows->result_array();
    $config = (object) $config;

    $columns = array();
    $columnCount = 0;

    foreach ($rows->row_array() as $key => $value) {
      $columns[] = $key;
      $columnCount++;
    }
    // if ($columnCount>50)
    //     $LastField ='B'.chr(65+($columnCount-51));
    // elseif ($columnCount>25)
    //     $LastField ='A'.chr(65+($columnCount-25));
    // else

    $col_batch = 0;
    $count = 0;
    foreach ($columns as $value) {
      $count++;
      if ($count % 26 == 0) {
        $col_batch++;
        $count = 1;
      }
    }
    if ($col_batch > 0)
      $LastField = chr(64 + $col_batch) . chr(64 + $count);
    else
      $LastField = chr(65 + $count);
    ini_set('memory_limit', '-1');
    set_time_limit(0);
    /** Caching to discISAM 1.0 */
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

    $styleArray = array('font' => array('bold' => true, 'size' => 12));
    $currentSheet->getStyle("A1:E2")->applyFromArray($styleArray);
    $idx = 3;
    $count = 0;


    $currentSheet->setCellValue('A' . $idx, 'No');
    $currentSheet->getColumnDimension('A')->setAutoSize(true);

    $col_batch = 0;
    foreach ($columns as $value) {
      $count++;
      if ($count % 26 == 0) {
        $col_batch++;
        $count = 1;
      }

      if ($col_batch > 0) {
        $str_col_cel = chr(64 + $col_batch) . chr(64 + $count);
      } else {
        $str_col_cel = chr(65 + $count);
      }
      //        echo $str_col_cel."<br>";
      $currentSheet->setCellValue($str_col_cel . $idx, $value);

      $currentSheet->getColumnDimension($str_col_cel)->setAutoSize(true);
    }
    $currentSheet->getRowDimension($idx)->setRowHeight(25);
    $currentSheet->getStyle('A' . $idx . ":$LastField" . $idx)->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setARGB('8E0404');

    $styleArray = array('font' => array('bold' => true, 'size' => 10));
    $currentSheet->getStyle('A' . $idx . ":$LastField" . $idx)->applyFromArray($styleArray);
    $currentSheet->getStyle('A' . $idx . ":$LastField" . $idx)
            ->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

    $no = 0;
    $idx++;

    foreach ($records as $row) {
      $no++;

      $count = 0;
      $col_batch = 0;
      $currentSheet->setCellValue('A' . $idx, $no);
      foreach ($columns as $value) {
        $count++;

        if ($count % 26 == 0) {
          $col_batch++;
          $count = 1;
        }

        if ($col_batch > 0)
          $str_col_cel = chr(64 + $col_batch) . chr(64 + $count);
        else
          $str_col_cel = chr(65 + $count);


        $currentSheet->setCellValue($str_col_cel . $idx, $row[$value]);
      }

      $styleArray = array('font' => array('bold' => FALSE, 'size' => 10));
      $currentSheet->getStyle('A' . $idx . ":$LastField" . $idx)->applyFromArray($styleArray);
      $currentSheet->getStyle('A' . $idx . ":$LastField" . $idx);
      //->getFont()->getColor()->setARGB('5c5c5c');

      $idx++;
    }



    $objPHPExcel->setActiveSheetIndex(0);

    // Redirect output to a client's web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=" . $config->Description . ".xlsx");
    header('Cache-Control: max-age=0');

    $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
  }

  public function _write_excel($config = null, $rows, $exclude) {
    $this->load->library('excel');

    $excel = new Excel();

    if ($config == null) {
      $config = $this->default_config;
    }

    $config = (object) $config;

    // Send Header
    $excel->setHeader(str_replace(' ', '_', strtoupper(trim($config->Description))) . '.xls');
    $excel->BOF();

    $headers = $rows->row_array();
    $records = $rows->result_array();

    $i = 0;
    foreach ($headers as $head_idx => $head_val) {
      if (!in_array($head_idx, $exclude)) {
        $excel->writeLabel(0, $i, $head_idx);
        $j = 1;
        foreach ($records as $res_idx => $res_val) {
          if (is_numeric($res_val[$head_idx])) {
            $excel->writeNumber($j, $i, $res_val[$head_idx]);
          } else {
            $excel->writeLabel($j, $i, $res_val[$head_idx]);
          }
          $j++;
        }

        $i++;
      }
    }

    $excel->EOF();
    exit();
  }

  public function _write_excel_npi($config = null, $rows) {
    $this->load->library('excel');

    $excel = new Excel();

    if ($config == null) {
      $config = $this->default_config;
    }

    $config = (object) $config;

    // Send Header
    $excel->setHeader(str_replace(' ', '_', strtoupper(trim($config->Description))) . '.xls');
    $excel->BOF();

    $headers = $rows->row_array();
    $records = $rows->result_array();

    $i = 0;
    foreach ($headers as $head_idx => $head_val) {
      if ($head_idx != 'site_id') {
        $excel->writeLabel(0, $i, $head_idx);
        $j = 1;
        foreach ($records as $res_idx => $res_val) {
          if (is_numeric($res_val[$head_idx])) {
            $excel->writeNumber($j, $i, $res_val[$head_idx]);
          } else {
            $excel->writeLabel($j, $i, $res_val[$head_idx]);
          }
          $j++;
        }

        $i++;
      }
    }

    $excel->EOF();
    exit();
  }

}
