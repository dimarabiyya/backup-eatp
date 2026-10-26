<?php

class Pln extends MX_Controller {

  private $_partials = array();
  private $_monthsName = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
  private $_upload_dir;
  private $_upload_cache_dir;
  private $_upload_url;

  public function __construct() {
    parent::__construct();

    $this->load->model('pln_model');
    $this->load->model('image_model');
    $this->load->model('mreport');

    $this->load->library('parser');

    $this->_upload_dir = FCPATH . 'file/upload/site-info/pln-usage';
    $this->_upload_cache_dir = FCPATH . 'file/upload/cache/site-info/pln-usage';
    $this->_upload_url = base_url('/file/upload/site-info/pln-usage');

    if (!is_dir($this->_upload_dir)) {
      mkdir($this->_upload_dir, 0777);
    }

    $uri = $this->uri->segment(1) . '/' . $this->uri->segment(2);
    $this->_partials['menu'] = $this->mmenu->getMenuId($uri);
  }

  public function index() {
    $this->_check_valid_loggedin();

    $data = $this->_partials;

    if ($this->core->is_ajax()) {

      if ($this->input->get('cmd') == 'LOAD_DATA') {

        $json = array();

        $data = $this->pln_model->load_pln_list();

        $json = $data;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_DATAGRID_PLN_USAGE') {

        $rows = $this->pln_model->get_pln_list();

        echo json_encode($rows);
        exit;
      }

      if ($this->input->get('cmd') == 'DELETE') {

        $json = array();

        $id = (int) $this->core->decode($this->input->get('id'));

        // soft delete
        $this->core->update('t_trx_site_pln_usage', array('active' => 0), array('id' => $id));

        $json = array('message' => 'PLN Usage data has been deleted successfully.');

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_SITE') {

        $json = array();
        $site_id = $this->input->get('site_id');

        if ($site_id) {
          $sql = "SELECT site_id, site_code, site_name, vendor_id, vendor_name FROM t_mtr_site WHERE site_id = {$site_id} ORDER BY site_code ASC";
        } else {
          $sql = "SELECT site_id, site_code, site_name, vendor_id, vendor_name FROM t_mtr_site ORDER BY site_code ASC LIMIT 20";
        }

        $results = $this->db
                ->query($sql)
                ->result_array();

        if ($this->input->get('q')) {
          $q = $this->input->get('q');

          $results = $this->db
                  ->query("
							SELECT site_id, site_code, site_name, vendor_id, vendor_name 
							FROM t_mtr_site 
							WHERE UPPER(site_code) LIKE '%" . strtoupper($q) . "%' 
							OR UPPER(site_name) LIKE '%" . strtoupper($q) . "%' 
							OR UPPER(vendor_id) LIKE '%" . strtoupper($q) . "%' 
							OR UPPER(vendor_name) LIKE '%" . strtoupper($q) . "%'
							LIMIT 20
						")
                  ->result_array();
        }

        $json = $results;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_DATA_AFTER') {

        $site_id = (int) $this->input->get('siteId');
        $month = $this->input->get('month');
        $year = $this->input->get('year');

        $where = " WHERE site_id = {$site_id}";
        if ($month != 0 && $year != 0) {
          $where .= " AND bulan = " . ($month - 1) . " AND tahun = {$year}";
        }

        $json = $this->db
                ->query("SELECT * FROM t_trx_site_pln_usage {$where}")
                ->row_array();

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_ROW_SITE') {

        $site_id = (int) $this->input->get('siteId');

        $json = $this->db
                ->query("
						SELECT pln.cost_center, pln.id_pelanggan_pln, pln.atas_nama_pelanggan, pln.pln_capacity, pln.company_code, lo1.lookup_desc AS company, lo2.lookup_desc AS tariff_name, lo3.lookup_desc AS tariff_type
						FROM t_mtr_site site
						LEFT JOIN t_mtr_site_l_pln pln ON pln.site_id = site.site_id
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = pln.company_code::VARCHAR AND lo1.lookup_key = 'company' AND lo1.active = 1
						LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = pln.tarif_name::VARCHAR AND lo2.lookup_key = 'pln_tariff_name' AND lo2.active = 1
						LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = pln.tarif_type::VARCHAR AND lo3.lookup_key = 'tariff_type' AND lo3.active = 1
						WHERE site.site_id = {$site_id}
					")
                ->row_array();

        echo json_encode($json);
        exit;
      }
    }

    $data['content_id'] = 'pln_list_view';

    $this->load->view('template/smashing', $data);
  }

  public function add() {
    $this->_check_valid_loggedin();

    if (!$this->mmenu->cekPermission($this->_partials['menu'], 'menu_add')) {
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }

    if ($this->input->post('cmd') == 'add_attachment') {

      $json = array();

      if (!is_dir($this->_upload_dir)) {
        mkdir($this->_upload_dir, 0777);
      }

      if ($this->input->post('act') == 'upload') { // just upload file
        $allowed = array(
            '.pdf',
            '.jpg',
            '.jpeg',
            '.gif',
            '.png'
        );

        $upload_key = array_keys($_FILES);

        if (!empty($_FILES[$upload_key[0]]['name']) && $_FILES[$upload_key[0]]['error'] == UPLOAD_ERR_OK) {
          $tmp_name = $_FILES[$upload_key[0]]['tmp_name'];
          $ext = strtolower(strrchr($_FILES[$upload_key[0]]['name'], '.'));
          $file_name = $this->input->post('image') ? $this->input->post('image') : time() . $ext;
          $size = $_FILES[$upload_key[0]]['size'];

          if (!in_array($ext, $allowed)) {
            $json = array('error' => TRUE, 'message' => 'Invalid file type. Please upload file only type: pdf, jpg, png, gif');
          } elseif (ltrim($ext, '.') == 'pdf' && $size > 300000) { // not more than 300KB
            $json = array('error' => TRUE, 'message' => 'File too big please keep below 300KB.');
          } else {
            if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_upload_dir . '/' . $file_name)) {
              $file = $this->_upload_url . '/' . $file_name;
              $thumb = '';
              if (in_array($ext, array('.jpg', '.jpeg', '.gif', '.png'))) {
                // resize first
                $image = $this->_upload_dir . '/' . $file_name;
                $size = getimagesize($image);
                $width = $size[0];
                $height = $size[1];
                $expected_width = 800;
                $expected_height = 600;
                if ($width > $expected_width && $height > $expected_height) {
                  $img = new Image($image);
                  $img->resizeImage($expected_width, $expected_height, 'auto');
                  $img->saveImage($image, 80);
                }

                $thumb = $this->image_model->resize($image, 60, 60, 'crop');
              }

              $json = array('error' => FALSE, 'ref' => $upload_key[0], 'file_name' => $file_name, 'file' => $file, 'thumb' => $thumb, 'message' => 'File uploaded.');
            }
          }
        }
      } elseif ($this->input->post('act') == 'delete') {

        $file_name = $this->input->post('file_name');
        $pathinfo = pathinfo($file_name);
        $ext = strtolower(strrchr($pathinfo['basename'], '.'));

        if (is_file($this->_upload_dir . '/' . $pathinfo['basename']) && file_exists($this->_upload_dir . '/' . $pathinfo['basename'])) {
          @unlink($this->_upload_dir . '/' . $pathinfo['basename']);
        }

        if (in_array($ext, array('.jpg', '.jpeg', '.gif', '.png'))) {
          if (is_file($this->_upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension']) && file_exists($this->_upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension'])) {
            @unlink($this->_upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension']);
          }
        }

        $this->db->delete('t_trx_site_pln_usage_attachment', array('attachment' => $file_name));

        $json = array('error' => FALSE, 'message' => 'File has been deleted successfully.');

        echo json_encode($json);
        exit;
      }

      echo json_encode($json);
      exit;
    }

    if ($this->core->is_ajax()) {

      if ($this->input->post('act') == 'check_validation') {

        $post = $this->core->get_post();
        $json = array();
        $error = array();
        $warning = array();

        if ($this->_is_field_empty($post['siteId'])) {
          $error[] = 'Please choose Site ID.';
        }
        /*
          if ( $this->core->view_row('t_trx_site_pln_usage', array('site_id' => (int) $post['siteId'])) ) {
          $error[] = 'Site ID is already used. Please choose another Site ID.';
          }
         */
        if ($this->_is_field_empty($post['paymentDate'])) {
          $error[] = 'Please choose Payment Date.';
        }
        if ($this->_is_field_empty($post['month'])) {
          $error[] = 'Please choose Period Month.';
        }
        if ($this->_is_field_empty($post['year'])) {
          $error[] = 'Please choose Period Year.';
        }
        if ($this->_is_field_empty($post['amount'])) {
          $error[] = 'Amount can not empty.';
        }
        if ($this->_is_field_empty($post['paymentMethod'])) {
          $error[] = 'Please choose Payment Method.';
        }
        if ($this->_is_field_empty($post['paymentType'])) {
          $error[] = 'Please choose Payment Type.';
        }
        /*
          if ($this->_is_field_empty($post['paymentUnder'])) {
          $error[] = 'Please choose Under Payment.';
          }
          if ($this->_is_field_empty($post['kwhBefore'])) {
          $error[] = 'KWH Before can not empty.';
          }
          if ($this->_is_field_empty($post['kwhAfter'])) {
          $error[] = 'KWH After can not empty.';
          }
          if ($this->_is_field_empty($post['kwhUsage'])) {
          $error[] = 'KWH Usage can not empty.';
          }
         */
        if ($this->_is_field_empty($post['kwhBefore']) && $this->_is_field_empty($post['kwhAfter'])) {
          $warning[] = 'Kwh usage is empty.';
        }
        if (@$this->_is_field_empty($this->input->post('file'))) {
          $warning[] = 'Attachment is empty.';
        }

        if (count($error) == 0) {
          if (count($warning) > 0) {
            $json = array('warning' => TRUE, 'message' => $warning);
          }
        } else {
          $json = array('error' => TRUE, 'message' => $error);
        }
      } elseif ($this->input->post('act') == 'save') {

        $post = $this->core->get_post();

        $this->core->db_debug = TRUE;

        $created_by = $updated_by = $this->session->userdata('user_id');
        $created_on = $updated_on = date('Y-m-d H:i:s');

        $this->core->set['site_id'] = $post['siteId'];
        $this->core->set['customer_id'] = $post['customerId'];
        $this->core->set['tanggal_bayar'] = $post['paymentDate'];
        $this->core->set['tahun'] = $post['year'];
        $this->core->set['bulan'] = $post['month'];
        $this->core->set['biaya'] = $post['amount'];
        $this->core->set['payment_method_id'] = $post['paymentMethod'];
        $this->core->set['payment_type'] = $post['paymentType'];
        //$this->core->set['payment_under_id'] = $post['paymentUnder'];
        $this->core->set['kwh_awal'] = (float) $post['kwhBefore'];
        $this->core->set['kwh_akhir'] = (float) $post['kwhAfter'];
        $this->core->set['pemakaian_kwh'] = (float) $post['kwhUsage'];
        $this->core->set['remark'] = $post['remarks'];
        $this->core->set['created_by'] = $created_by;
        $this->core->set['created_on'] = $created_on;
        $this->core->set['updated_by'] = $updated_by;
        $this->core->set['updated_on'] = $updated_on;

        $this->core->save('t_trx_site_pln_usage');

        $id = $this->db->insert_id();

        // Attachment
        $this->db->delete('t_trx_site_pln_usage_attachment', array('pln_usage_id' => $id));

        $fileName = $this->input->post('file');

        if (@is_array($fileName)) {
          ksort($fileName);
          foreach ($fileName as $idx => $value) {
            $this->core->set['pln_usage_id'] = $id;
            $this->core->set['attachment'] = $value;

            $this->core->save('t_trx_site_pln_usage_attachment');
          }
        }

        $json = array('error' => FALSE, 'message' => 'New PLN Usage data has been added successfully.');
      }

      echo json_encode($json);
      exit;
    }

    $this->_pln_form();
  }

  public function edit($id = 0) {
    $this->_check_valid_loggedin();

    if (!$this->mmenu->cekPermission($this->_partials['menu'], 'menu_edit')) {
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }

    $id = (int) $this->core->decode($id);

    if ($this->input->post('cmd') == 'edit_attachment') {

      $json = array();
      $this->_upload_dir = FCPATH . 'file/upload/site-info/pln-usage';
      $this->_upload_cache_dir = FCPATH . 'file/upload/cache/site-info/pln-usage';
      $this->_upload_url = base_url('/file/upload/site-info/pln-usage');

      if (!is_dir($this->_upload_dir)) {
        mkdir($this->_upload_dir, 0777);
      }

      if ($this->input->post('act') == 'upload') { // just upload file
        $allowed = array(
            '.pdf',
            '.jpg',
            '.jpeg',
            '.gif',
            '.png'
        );

        $upload_key = array_keys($_FILES);

        if (!empty($_FILES[$upload_key[0]]['name']) && $_FILES[$upload_key[0]]['error'] == UPLOAD_ERR_OK) {
          $tmp_name = $_FILES[$upload_key[0]]['tmp_name'];
          $ext = strtolower(strrchr($_FILES[$upload_key[0]]['name'], '.'));
          $file_name = $this->input->post('image') ? $this->input->post('image') : time() . $ext;
          $size = $_FILES[$upload_key[0]]['size'];

          if (!in_array($ext, $allowed)) {
            $json = array('error' => TRUE, 'message' => 'Incorrect file type. Please upload file only type: pdf, jpg, png, gif');
          } elseif (ltrim($ext, '.') == 'pdf' && $size > 300000) { // not more than 300KB
            $json = array('error' => TRUE, 'message' => 'File too big please keep below 300KB.');
          } else {
            if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_upload_dir . '/' . $file_name)) {
              $file = $this->_upload_url . '/' . $file_name;
              $thumb = '';
              if (in_array($ext, array('.jpg', '.jpeg', '.gif', '.png'))) {
                // resize first
                $image = $this->_upload_dir . '/' . $file_name;
                $size = getimagesize($image);
                $width = $size[0];
                $height = $size[1];
                $expected_width = 800;
                $expected_height = 600;
                if ($width > $expected_width && $height > $expected_height) {
                  $img = new Image($image);
                  $img->resizeImage($expected_width, $expected_height, 'auto');
                  $img->saveImage($image, 80);
                }

                $thumb = $this->image_model->resize($image, 60, 60, 'crop');
              }

              $json = array('error' => FALSE, 'ref' => $upload_key[0], 'file_name' => $file_name, 'file' => $file, 'thumb' => $thumb, 'message' => 'File uploaded.');
            }
          }
        }
      } elseif ($this->input->post('act') == 'delete') {

        $file_name = $this->input->post('file_name');
        $pathinfo = pathinfo($file_name);
        $ext = strtolower(strrchr($pathinfo['basename'], '.'));

        if (is_file($this->_upload_dir . '/' . $pathinfo['basename']) && file_exists($this->_upload_dir . '/' . $pathinfo['basename'])) {
          @unlink($this->_upload_dir . '/' . $pathinfo['basename']);
        }

        if (in_array($ext, array('.jpg', '.jpeg', '.gif', '.png'))) {
          if (is_file($this->_upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension']) && file_exists($this->_upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension'])) {
            @unlink($this->_upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension']);
          }
        }

        $this->db->delete('t_trx_site_pln_usage_attachment', array('attachment' => $file_name));

        $json = array('error' => FALSE, 'message' => 'File has been deleted successfully.');

        echo json_encode($json);
        exit;
      }

      echo json_encode($json);
      exit;
    }

    if ($this->core->is_ajax()) {

      if ($this->input->post('act') == 'check_validation') {

        $post = $this->core->get_post();
        $json = array();
        $error = array();
        $warning = array();

        if ($this->_is_field_empty($post['siteId'])) {
          $error[] = 'Please choose Site ID.';
        }
        if ($this->_is_field_empty($post['paymentDate'])) {
          $error[] = 'Payment Date can not empty.';
        }
        if ($this->_is_field_empty($post['month'])) {
          $error[] = 'Please choose Period Month.';
        }
        if ($this->_is_field_empty($post['year'])) {
          $error[] = 'Please choose Period Year.';
        }
        if ($this->_is_field_empty($post['amount'])) {
          $error[] = 'Amount can not empty.';
        }
        if ($this->_is_field_empty($post['paymentMethod'])) {
          $error[] = 'Please choose Payment Method.';
        }
        if ($this->_is_field_empty($post['paymentType'])) {
          $error[] = 'Please choose Payment Type.';
        }
        /*
          if ($this->_is_field_empty($post['paymentUnder'])) {
          $error[] = 'Please choose Under Payment.';
          }
          if ($this->_is_field_empty($post['kwhBefore'])) {
          $error[] = 'KWH Before can not empty.';
          }
          if ($this->_is_field_empty($post['kwhAfter'])) {
          $error[] = 'KWH After can not empty.';
          }
          if ($this->_is_field_empty($post['kwhUsage'])) {
          $error[] = 'KWH Usage can not empty.';
          }
         */

        if ($this->_is_field_empty($post['kwhBefore']) && $this->_is_field_empty($post['kwhAfter'])) {
          $warning[] = 'Kwh usage is empty.';
        }
        if (@$this->_is_field_empty($this->input->post('file'))) {
          $warning[] = 'Attachment is empty.';
        }

        if (count($error) == 0) {
          if (count($warning) > 0) {
            $json = array('warning' => TRUE, 'message' => $warning);
          }
        } else {
          $json = array('error' => TRUE, 'message' => $error);
        }
      } elseif ($this->input->post('act') == 'save') {

        $post = $this->core->get_post();

        $this->core->db_debug = TRUE;

        $created_by = $updated_by = $this->session->userdata('user_id');
        $created_on = $updated_on = date('Y-m-d H:i:s');

        $this->core->set['site_id'] = $post['siteId'];
        $this->core->set['customer_id'] = $post['customerId'];
        $this->core->set['tanggal_bayar'] = $post['paymentDate'];
        $this->core->set['tahun'] = $post['year'];
        $this->core->set['bulan'] = $post['month'];
        $this->core->set['biaya'] = $post['amount'];
        $this->core->set['payment_method_id'] = $post['paymentMethod'];
        $this->core->set['payment_type'] = $post['paymentType'];
        //$this->core->set['payment_under_id'] = $post['paymentUnder'];
        $this->core->set['kwh_awal'] = (float) $post['kwhBefore'];
        $this->core->set['kwh_akhir'] = (float) $post['kwhAfter'];
        $this->core->set['pemakaian_kwh'] = (float) $post['kwhUsage'];
        $this->core->set['remark'] = $post['remarks'];
        $this->core->set['updated_by'] = $updated_by;
        $this->core->set['updated_on'] = $updated_on;

        $this->core->save('t_trx_site_pln_usage', array('id' => $id));

        // Attachment
        $this->db->delete('t_trx_site_pln_usage_attachment', array('pln_usage_id' => $id));

        $fileName = $this->input->post('file');

        if (@is_array($fileName)) {
          ksort($fileName);
          foreach ($fileName as $idx => $value) {
            $this->core->set['pln_usage_id'] = $id;
            $this->core->set['attachment'] = $value;

            $this->core->save('t_trx_site_pln_usage_attachment');
          }
        }

        $json = array('error' => FALSE, 'message' => 'PLN Usage data has been updated successfully.');

        $this->session->set_flashdata('success', $json['message']);
      }

      echo json_encode($json);
      exit;
    }

    $this->_pln_form($id);
  }

  public function view($id = 0) {
    $this->_check_valid_loggedin();

    if (!$this->mmenu->cekPermission($this->_partials['menu'], 'menu_view')) {
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }

    $id = (int) $this->core->decode($id);

    if (!$this->core->view_row('t_trx_site_pln_usage', array('id' => $id))) {
      show_404();
    }

    $data['pln'] = $this->pln_model->get_pln($id);

    $result = $this->core->view_all('t_trx_site_pln_usage_attachment', array('pln_usage_id' => $id));

    $dir = FCPATH . 'file/upload/site-info/pln-usage';

    $data['pln']['attachment'] = array();
    foreach ($result as $r) {
      if (is_file($dir . '/' . $r['attachment']) && file_exists($dir . '/' . $r['attachment'])) {
        $r['file'] = base_url('/file/upload/site-info/pln-usage/' . $r['attachment']);
        $r['file_path'] = FCPATH . 'file/upload/site-info/pln-usage/' . $r['attachment'];

        $ext = end(explode('.', $r['attachment']));
        $thumb = '';
        if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {
          $thumb = $this->image_model->resize($dir . '/' . $r['attachment'], 60, 60, 'crop');
        }

        $r['thumb'] = $thumb;
        $r['ext'] = $ext;

        $data['pln']['attachment'][] = $r;
      }
    }

    $data['content_id'] = 'pln_view_view';

    $this->load->view('template/smashing', $data);
  }

  private function _pln_form($id = '') {
    $data = array();

    if ($this->uri->segment(3) == 'edit') {
      if ($this->core->view_row('t_trx_site_pln_usage', array('id' => $id))) {
        $data['pln'] = $this->pln_model->get_pln($id);

        $result = $this->core->view_all('t_trx_site_pln_usage_attachment', array('pln_usage_id' => $id));

        $data['pln']['attachment'] = array();
        foreach ($result as $r) {
          if (is_file($this->_upload_dir . '/' . $r['attachment']) && file_exists($this->_upload_dir . '/' . $r['attachment'])) {
            $r['file'] = $this->_upload_url . '/' . $r['attachment'];
            $r['file_path'] = $this->_upload_dir . '/' . $r['attachment'];

            $ext = end(explode('.', $r['attachment']));
            $thumb = '';
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {
              $thumb = $this->image_model->resize($this->_upload_dir . '/' . $r['attachment'], 60, 60, 'crop');
            }

            $r['thumb'] = $thumb;
            $r['ext'] = $ext;

            $data['pln']['attachment'][] = $r;
          }
        }
      } else {
        show_404();
      }
    }

    $data['content_id'] = 'pln_form_view';

    $this->load->view('template/smashing', $data);
  }

  private function _is_timestamp($timestamp) {
    $check = (is_int($timestamp) OR is_float($timestamp)) ? $timestamp : (string) (int) $timestamp;

    return ($check === $timestamp) AND ( (int) $timestamp <= PHP_INT_MAX) AND ( (int) $timestamp >= ~PHP_INT_MAX);
  }

  public function test() {
    $file = 'D:/pln_usage.xls';

    $this->load->library('PHPExcel');
    $this->load->library('PHPExcel/IOFactory');

    $objPHPExcel = new PHPExcel();

    try {
      $objReader = IOFactory::createReader('Excel5');
      $objPHPExcel = $objReader->load($file);
    } catch (Exception $e) {
      $objReader = IOFactory::createReader('Excel2007');
      $objPHPExcel = $objReader->load($file);
    }

    echo '<pre>';
    $rows = array();
    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
      $i = 0;
      foreach ($worksheet->getRowIterator() as $row) {
        if ($i != 0) {
          $cellIterator = $row->getCellIterator();
          $cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
          $j = 0;
          foreach ($cellIterator as $cell) {
            if (!is_null($cell)) {
              //$rows[$i][$j]=$cell->getFormattedValue();
              $rows[$i][$j] = $cell->getValue();
              //echo '(r:'.$i.', c:'.$j.')';
              //echo $cell->getValue().'|';
              if ($j == 6) {
                if (PHPExcel_Shared_Date::isDateTime($cell)) {
                  echo 'ok ';
                  $PHPTimeStamp = PHPExcel_Shared_Date::ExcelToPHP($cell->getValue());
                  echo date('Y-m-d', $PHPTimeStamp) . "\n";
                } else {
                  echo 'not ok ';
                }
              }
              if ($j == 5) {
                if (PHPExcel_Calculation_Functions::IS_NUMBER($cell->getValue())) {
                  echo 'ok2 ' . (int) $cell->getValue();
                } else {
                  echo 'not ok2 ' . (int) $cell->getValue() . '|' . $cell->getValue();
                }
              }
            } else {
              $rows[$i][$j] = 0;
            }
            $j++;
          }
          echo '<br>';
        }
        $i++;
      }
    }
    echo '</pre>';
    exit;
  }

  public function import() {
    if ($this->input->post('cmd') == 'upload') {

      $json = array();

      $allowed = array('.xls', '.xlsx');

      $upload_key = array_keys($_FILES);

      if (!empty($_FILES[$upload_key[0]]['name']) && $_FILES[$upload_key[0]]['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES[$upload_key[0]]['tmp_name'];
        $ext = strtolower(strrchr($_FILES[$upload_key[0]]['name'], '.'));
        $file_name = $_FILES[$upload_key[0]]['name'];

        if (!in_array($ext, $allowed)) {
          $json = array('error' => TRUE, 'message' => 'Invalid file type. Please upload file only type: .xls, .xlsx');
        } else {
          if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $this->_upload_dir . '/' . $file_name)) {

            $json = array('error' => FALSE, 'message' => 'File uploaded.');
          }
        }
      }

      echo json_encode($json);
      exit;
    }

    if ($this->input->post('cmd') == 'check_validation') {

      $json = array();
      $file = $this->_upload_dir . '/' . $this->input->post('file');

      $this->load->library('PHPExcel');
      $this->load->library('PHPExcel/IOFactory');

      $objPHPExcel = new PHPExcel();

      try {
        $objReader = IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($file);
      } catch (Exception $e) {
        $objReader = IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($file);
      }

      $error = array();
      $month = $this->input->post('month');
      $year = $this->input->post('year');

      $total_rows = 0;
      //$rows = array();
      foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
        $i = 0;
        foreach ($worksheet->getRowIterator() as $row) {
          if ($i != 0) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
            $j = 0;
            foreach ($cellIterator as $cell) {
              if (!is_null($cell)) {
                //$rows[$i][$j]=$cell->getFormattedValue();
                //$rows[$i][$j] = $cell->getValue();

                if ($j == 0) { // ID Pelanggan
                  $site = $this->db->query("SELECT site_id FROM t_mtr_site_l_pln WHERE id_pelanggan_pln = '" . trim($cell->getValue()) . "'")->row_array();
                  if (!isset($site['site_id'])) {
                    $error[] = array('row' => $i, 'column' => ($j + 1), 'cause' => 'Customer ID is not registered.');
                  }

                  if (@$this->core->view_row('t_trx_site_pln_usage', array('bulan' => $month, 'tahun' => $year, 'site_id' => $site['site_id']))) {
                    $error[] = array('row' => $i, 'column' => ($j + 1), 'cause' => 'Period ' . $this->_monthsName[$month] . ' ' . $year . ' already exist.');
                  }
                }

                if ($j == 1) { // Payment Method
                  $res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE UPPER(lookup_desc) = '" . strtoupper(trim($cell->getValue())) . "' AND lookup_key = 'payment_method' AND active = 1")->row_array();
                  if (!$res) {
                    $error[] = array('row' => $i, 'column' => ($j + 1), 'cause' => 'Payment Method is not registered.');
                  }
                }

                if ($j == 2) { // Payment Type
                  $res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE UPPER(lookup_desc) = '" . strtoupper(trim($cell->getValue())) . "' AND lookup_key = 'payment_type' AND active = 1")->row_array();
                  if (!$res) {
                    $error[] = array('row' => $i, 'column' => ($j + 1), 'cause' => 'Payment Type is not registered.');
                  }
                }

                if ($j == 3) { // KWH Awal
                  if (!PHPExcel_Calculation_Functions::IS_NUMBER($cell->getValue())) {
                    $error[] = array('row' => $i, 'column' => ($j + 1), 'cause' => 'Incorrect KWH Before format.');
                  }
                }

                if ($j == 4) { // KWH Akhir
                  if (!PHPExcel_Calculation_Functions::IS_NUMBER($cell->getValue())) {
                    $error[] = array('row' => $i, 'column' => ($j + 1), 'cause' => 'Incorrect KWH After format.');
                  }
                }

                if ($j == 5) { // Amount
                  if (!PHPExcel_Calculation_Functions::IS_NUMBER($cell->getValue())) {
                    $error[] = array('row' => $i, 'column' => ($j + 1), 'cause' => 'Incorrect Amount format.');
                  }
                }

                if ($j == 6) { // Tanggal Bayar
                  if (!PHPExcel_Shared_Date::isDateTime($cell) || !PHPExcel_Calculation_Functions::IS_NUMBER($cell->getValue())) {
                    $error[] = array('row' => $i, 'column' => ($j + 1), 'cause' => 'Incorrect Payment Date format.');
                  }
                }
              }

              $j++;
            }
          }

          $total_rows = $total_rows + $i;

          $i++;
        }
      }

      if (count($error) > 0) {
        $json = array('error' => TRUE, 'causes' => $error, 'message' => 'Import failed. Please correct it and import again.', 'total_rows' => ($total_rows - 1));
      } else {
        $json = array('error' => FALSE);
      }

      echo json_encode($json);
      exit;
    }

    if ($this->input->post('cmd') == 'import') {

      $json = array();
      $file = $this->_upload_dir . '/' . $this->input->post('file');

      $this->load->library('PHPExcel');
      $this->load->library('PHPExcel/IOFactory');

      $objPHPExcel = new PHPExcel();

      try {
        $objReader = IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($file);
      } catch (Exception $e) {
        $objReader = IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($file);
      }

      $rows = array();
      foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
        $i = 0;
        foreach ($worksheet->getRowIterator() as $row) {
          if ($i != 0) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
            $j = 0;
            foreach ($cellIterator as $cell) {
              if (!is_null($cell)) {
                //$rows[$i][$j]=$cell->getFormattedValue();
                $rows[$i][$j] = $cell->getValue();
              } else {
                $rows[$i][$j] = 0;
              }
              $j++;
            }
          }
          $i++;
        }
      }

      $date = date('Y-m-d H:i:s');
      $by = $this->session->userdata('user_id');
      $month = $this->input->post('month');
      $year = $this->input->post('year');

      $this->core->db_debug = TRUE;

      foreach ($rows as $row) {
        $this->core->set['bulan'] = $month;
        $this->core->set['tahun'] = $year;

        $res = $this->db->query("SELECT site_id FROM t_mtr_site_l_pln WHERE id_pelanggan_pln = '" . trim($row[0]) . "'")->row_array();
        $this->core->set['site_id'] = $res['site_id'];

        $this->core->set['customer_id'] = trim($row[0]);

        $res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE lookup_key = 'payment_method' AND UPPER(lookup_desc) = '" . strtoupper($row[1]) . "' AND active = 1")->row_array();
        $this->core->set['payment_method_id'] = $res['lookup_value'];

        $res = $this->db->query("SELECT lookup_value FROM t_mtr_lookup WHERE lookup_key = 'payment_type' AND UPPER(lookup_desc) = '" . strtoupper($row[2]) . "' AND active = 1")->row_array();
        $this->core->set['payment_type'] = $res['lookup_value'];

        //$payment_date = explode('/', $row[6]);

        $this->core->set['kwh_awal'] = $row[3];
        $this->core->set['kwh_akhir'] = $row[4];
        $this->core->set['pemakaian_kwh'] = ($row[4] - $row[3]);
        $this->core->set['biaya'] = $row[5];
        //$this->core->set['tanggal_bayar'] = $payment_date[2].'-'.$payment_date[1].'-'.$payment_date[0];
        $PHPTimeStamp = PHPExcel_Shared_Date::ExcelToPHP($row[6]);
        $this->core->set['tanggal_bayar'] = date('Y-m-d', $PHPTimeStamp);

        $this->core->set['created_on'] = $date;
        $this->core->set['created_by'] = $by;
        $this->core->set['updated_on'] = $date;
        $this->core->set['updated_by'] = $by;
        $this->core->set['active'] = 1;

        $this->core->save('t_trx_site_pln_usage');
      }

      $json = array('error' => FALSE, 'message' => 'PLN Usage data has been imported successfully.');

      echo json_encode($json);
      exit;
    }

    $data['content_id'] = 'pln_import';

    $this->load->view('template/smashing', $data);
  }

  public function export_() {
    $config = array(
        'Creator' => 'sims',
        'LastModified' => date('Ymd'),
        'Title' => 'PLN Usage',
        'Subtitle' => 'PLN Usage',
        'Subject' => 'PLN Usage',
        'Description' => 'PLN Usage',
    );

    $this->load->model('export');

    $query = $this->db
            ->query("
				SELECT
					site.site_code AS \"Site ID\",
					site.site_name AS \"Site Name\",
					site.vendor_id AS \"Vendor ID\",
					pln.customer_id AS \"Customer ID\",
					mtr_pln.atas_nama_pelanggan AS \"Customer Name\",
					pln.tahun AS \"Year\",
					pln.bulan AS \"Month\",
					pln.tanggal_bayar AS \"Payment Date\",
					lo1.lookup_desc AS \"Payment Method\",
					lo3.lookup_desc AS \"Payment Type\",
					pln.kwh_awal AS \"KWH Before\",
					pln.kwh_akhir AS \"KWH After\",
					pln.pemakaian_kwh AS \"KWH Usage\",
					pln.biaya AS \"Amount\",
					mtr_pln.company_code AS \"Company Code\",
					lo2.lookup_desc AS \"Company\",
					mtr_pln.cost_center AS \"Cost Center\",
					mtr_pln.pln_capacity AS \"Capacity\",
					lo4.lookup_desc AS \"Tariff Type\",
					lo5.lookup_desc AS \"Golongan Tariff\"
				FROM t_trx_site_pln_usage pln
				LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(pln.payment_method_id AS VARCHAR) AND lo1.lookup_key = 'payment_method' AND lo1.active = 1
				LEFT JOIN t_mtr_site_l_pln mtr_pln ON mtr_pln.site_id = pln.site_id
				LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = mtr_pln.company_code AND lo2.lookup_key = 'company' AND lo2.active = 1
				LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(pln.payment_type AS VARCHAR) AND lo3.lookup_key = 'payment_type' AND lo3.active = 1
				LEFT JOIN t_mtr_lookup lo4 ON lo4.lookup_value = CAST(mtr_pln.tarif_type AS VARCHAR) AND lo4.lookup_key = 'tariff_type' AND lo4.active = 1
				LEFT JOIN t_mtr_lookup lo5 ON lo5.lookup_value = CAST(mtr_pln.tarif_name AS VARCHAR) AND lo5.lookup_key = 'pln_tariff_name' AND lo5.active = 1
				LEFT JOIN t_mtr_site site ON site.site_id = pln.site_id AND site.active = 1
				WHERE pln.active = 1
				ORDER BY pln.updated_on DESC
			");

    $this->export->_do_export($query, 'excel', $config);
  }

  public function export() {
    if ($this->core->is_ajax()) {

      if ($this->input->get('cmd') == 'show_form_export') {

        $data = array();

        echo $this->parser->parse('partials/lookup_pln_export', $data);
        exit;
      }
    }

    //--- row di bawah kaga dipake. caciaaaaan deh luh!

    $this->load->library('excel');

    $excel = new Excel();

    $months = array(0 => '', 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');

    $param = $this->input->get();

    $year = $param['year'];
    $month = $param['month'];

    $where = '';
    if ($year != 0) {
      $where .= " AND tahun = " . $year;
    }
    if ($month != 0) {
      $where .= " AND bulan = " . $month;
    }

    $rows = $this->db->query("SELECT DISTINCT site_id FROM t_trx_site_pln_usage WHERE active = 1 {$where} ORDER BY site_id ASC")->result_array();
    $periods = $this->db->query("SELECT tahun, bulan FROM t_trx_site_pln_usage WHERE active = 1 {$where} GROUP BY tahun, bulan ORDER BY tahun, bulan ASC")->result_array();

    $data = array();
    $i = 0;
    foreach ($rows as $row) {
      $data[$i] = $row;

      //$site_row = $this->db->query('SELECT * FROM v_test_ajah')->row_array();
      /* $site_row = $this->db->query("
        SELECT site.site_code, site.vendor_id, site.site_name, site.company_code, pln.customer_id, pln.tanggal_bayar, lo1.lookup_desc AS company, mtr_pln.cost_center,
        mtr_pln.atas_nama_pelanggan, mtr_pln.pln_capacity, lo2.lookup_desc AS tariff_type, lo3.lookup_desc AS golongan_tariff
        FROM t_mtr_site site
        LEFT JOIN t_trx_site_pln_usage pln ON pln.site_id = site.site_id
        LEFT JOIN t_mtr_site_l_pln mtr_pln ON mtr_pln.site_id = pln.site_id
        LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = mtr_pln.company_code AND lo1.lookup_key = 'company' AND lo1.active = 1
        LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(mtr_pln.tarif_type AS VARCHAR) AND lo2.lookup_key = 'tariff_type' AND lo2.active = 1
        LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(mtr_pln.tarif_name AS VARCHAR) AND lo3.lookup_key = 'pln_tariff_name' AND lo3.active = 1
        WHERE site.site_id = ".$row['site_id']." AND site.active = 1
        ")->row_array();

        $data[$i]['Site ID'] = $site_row['site_code'];
        $data[$i]['Vendor ID'] = $site_row['vendor_id'];
        $data[$i]['Site Name'] = $site_row['site_name'];
        $data[$i]['Customer ID'] = $site_row['customer_id'];
        $data[$i]['Customer Name'] = $site_row['atas_nama_pelanggan'];
        $data[$i]['Company Code'] = $site_row['company_code'];
        $data[$i]['Company'] = $site_row['company'];
        $data[$i]['Cost Center'] = $site_row['cost_center'];
        $data[$i]['Capacity'] = $site_row['pln_capacity'];
        $data[$i]['Tariff Type'] = $site_row['tariff_type'];
        $data[$i]['Golongan Tariff'] = $site_row['golongan_tariff'];

        $data[$i]['Payment Date'] = $site_row['tanggal_bayar']; */


      // data column per month and year
      //$periods = $this->db->query("SELECT tahun, bulan FROM t_trx_site_pln_usage WHERE active = 1 AND site_id = ".$row['site_id']." {$where} ORDER BY tahun, bulan ASC")->result_array();

      /* foreach ($periods as $period) {
        $period_row = $this->db->query("
        SELECT pln.tahun, pln.bulan, pln.kwh_awal, pln.kwh_akhir, pln.pemakaian_kwh, pln.biaya, lo1.lookup_desc AS payment_method, lo2.lookup_desc AS payment_type
        FROM t_trx_site_pln_usage pln
        LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(pln.payment_method_id AS VARCHAR) AND lo1.lookup_key = 'payment_method' AND lo1.active = 1
        LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(pln.payment_type AS VARCHAR) AND lo2.lookup_key = 'payment_type' AND lo2.active = 1
        WHERE pln.active = 1 AND pln.site_id = ".$row['site_id']." AND pln.bulan = ".$period['bulan']." AND pln.tahun = ".$period['tahun']
        )->row_array();

        $month_year = $months[$period['bulan']]."'".$period['tahun'];
        if ($period_row) {
        $data[$i]['KWH Before '.$month_year] = $period_row['kwh_awal'];
        $data[$i]['KWH After '.$month_year] = $period_row['kwh_akhir'];
        $data[$i]['KWH Used '.$month_year] = $period_row['pemakaian_kwh'];
        $data[$i]['Amount '.$month_year] = $period_row['biaya'];
        $data[$i]['Payment Method '.$month_year] = $period_row['payment_method'];
        $data[$i]['Payment Type '.$month_year] = $period_row['payment_type'];
        } else {
        $data[$i]['KWH Before '.$month_year] = 0;
        $data[$i]['KWH After '.$month_year] = 0;
        $data[$i]['KWH Used '.$month_year] = 0;
        $data[$i]['Amount '.$month_year] = 0;
        $data[$i]['Payment Method '.$month_year] = '';
        $data[$i]['Payment Type '.$month_year] = '';
        }
        } */

      $i++;
    }
    //print_rr($data);
    //echo count($data);
    //exit;
    // Send Header
    $excel->setHeader('PLN_USAGE_' . date('Ymd') . '_' . date('His') . '.xls');
    $excel->BOF();

    $i = 0;
    foreach ($data[0] as $head_idx => $head_val) {
      $excel->writeLabel(0, $i, $head_idx);
      /* $j = 1;
        foreach ($data as $res_idx => $res_val) {
        if (is_numeric($res_val[$head_idx])) {
        $excel->writeNumber($j, $i, $res_val[$head_idx]);
        } else {
        $excel->writeLabel($j, $i, $res_val[$head_idx]);
        }
        $j++;
        } */

      $i++;
    }

    $excel->EOF();
    exit();
  }

  private function _check_valid_loggedin() {
    if (!$this->session->userdata('is_login')) {

      if ($this->core->is_ajax()) {

        echo json_encode(array(
            'loggedin' => FALSE,
            'message' => 'You are not logged in. You will be redirected to login page.',
            'redirect_url' => site_url('/login/' . $this->core->encode($this->uri->uri_string()))
        ));
        exit;
      } else {
        redirect('/login/' . $this->core->encode($this->uri->uri_string()));
      }
    }
  }

  private function _is_field_empty($field) {
    if (@is_array($field)) {
      $i = 0;
      foreach ($field as $arr) {
        if (trim($arr) == '' || (trim($arr) != '' && $arr == '0') || (trim($arr) != '' && $arr == '0.00')) {
          $i++;
        }
      }

      if ($i > 0) {
        return TRUE;
      } else {
        return FALSE;
      }
    } else {
      if (trim($field) == '' || (trim($field) != '' && $field == '0') || (trim($field) != '' && $field == '0.00')) {
        return TRUE;
      } else {
        return FALSE;
      }
    }
  }

}