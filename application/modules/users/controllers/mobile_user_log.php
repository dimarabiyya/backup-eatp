<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Mobile_user_log extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->mglobal->is_logged_in();
    $this->load->model('mmobile_user_log');
  }

  public function index() {
    $data['judul'] = "Mobile User Log";
    $data['content_id'] = "mobile_user_log/index";
    $this->_generate_view($data);
  }

  protected function _generate_view($data, $template = 'template/smashing') {
    $this->load->view($template, $data);
  }

  private function _is_ajax() {
    if (!$this->input->is_ajax_request()) {
      show_404();
    }
  }

  public function get_list() {
    $this->_is_ajax();

    $result = $this->mmobile_user_log->get_list();
    $menu = $this->mmenu->getMenuId("referensi/mobile_user_log");

    $response['total'] = $result['total'];
    $response['rows'] = array();
    foreach ($result['rows'] as $row) {
      $decode = json_decode($row->log_action);
      $row->created_on = $row->login_date;
      $row->mdn =  (isset($decode->mdn)) ? $decode->mdn : '-';
      $row->meid =  (isset($decode->imei)) ? $decode->imei : '-';
      $row->iccid =  (isset($decode->iccid)) ? $decode->iccid : '-';
      $row->app_version =  (isset($decode->app_version)) ? $decode->app_version : '-';
      array_push($response['rows'], $row);
    }

    echo json_encode($response);
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */