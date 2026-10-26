<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Setting extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->mglobal->is_logged_in();
    $this->load->model('msetting');
  }

  private function _is_ajax() {
    if (!$this->input->is_ajax_request()) {
      show_404();
    }
  }

  private function _validate() {
    $this->load->library('form_validation');
    $config = array(
        array(
            'field' => 'default_password',
            'label' => 'Default Password',
            'rules' => 'required|trim|alpha_numeric'
        ),
        array(
            'field' => 'email_configuration[host]',
            'label' => 'Email Host ',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'email_configuration[port]',
            'label' => 'Email Port ',
            'rules' => 'required|trim|is_natural_no_zero'
        ),
        array(
            'field' => 'email_configuration[opsi_ssl]',
            'label' => 'Email SSL ',
            'rules' => 'required|trim|is_natural_no_zero'
        ),
        array(
            'field' => 'email_configuration[email_address]',
            'label' => 'Email Address ',
            'rules' => 'required|trim|valid_email'
        ),
//        array(
//            'field' => 'email_configuration[password]',
//            'label' => 'Email Password ',
//            'rules' => 'required|trim'
//        ),
        array(
            'field' => 'sms_configuration[ip_address]',
            'label' => 'SMS IP Address ',
            'rules' => 'required|trim|valid_ip'
        ),
        array(
            'field' => 'sms_configuration[port]',
            'label' => 'SMS Port',
            'rules' => 'required|trim|is_natural_no_zero'
        ),
    );

    $this->form_validation->set_error_delimiters('<div>* ', '</div>');

    $this->form_validation->set_rules($config);
    return $this->form_validation->run();
  }

  public function index() {
    $data = $this->msetting->get_result("'default_password', 'email_configuration', 'sms_configuration'");

    $data['judul'] = "Setting";
    $data['content_id'] = "setting/index";
    $this->load->view("template/smashing", $data);
  }

  public function save() {
    $this->_is_ajax();

    $response = array();

    if ($this->_validate() == TRUE) {
      $query = $this->msetting->save();
      if ($query) {
        $response = array(
            'status' => 'success',
            'message' => 'Settings successfully saved.'
        );
      } else {
        $response = array(
            'status' => 'error',
            'message' => 'Settings failed saved.'
        );
      }
    } else {
      $response = array(
          'status' => 'warning',
          'message' => validation_errors()
      );
    }

    echo json_encode($response);
  }

  public function test_connection() {
    $this->_is_ajax();

//    error_reporting(0);
    $resp = array();
    $errno = 0;
    $errstr = '';
    $post = $this->input->post();

    if ($post['port'] == '465') {
      $post['host'] = 'ssl://' . $post['host'];
    }

    $fp = fsockopen($post['host'], $post['port'], $errno, $errstr, 5);
    if ($fp) {
      $resp = array(
          'status' => 'success',
          'message' => 'Test connection successfull.'
      );
    } else {
      $resp = array(
          'status' => 'error',
          'message' => $errstr . ". No {$post['test_type']} server responded on {$post['host']} on port {$post['port']}, please validate your configuration."
      );
    }

    echo json_encode($resp);
  }

  private function _validate_send_mail() {
    $this->load->library('form_validation');
    $config = array(
        array(
            'field' => 'host',
            'label' => 'Email Host ',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'port',
            'label' => 'Email Port ',
            'rules' => 'required|trim|is_natural_no_zero'
        ),
//        array(
//            'field' => 'email_configuration[opsi_ssl]',
//            'label' => 'Email SSL ',
//            'rules' => 'required|trim|is_natural_no_zero'
//        ),
        array(
            'field' => 'email_address',
            'label' => 'Email Address ',
            'rules' => 'required|trim|valid_email'
        ),
//        array(
//            'field' => 'email_password',
//            'label' => 'Email Password',
//            'rules' => 'required|trim'
//        ),
        array(
            'field' => 'email_receipt',
            'label' => 'Email Receipt ',
            'rules' => 'required|trim|valid_email'
        ),
    );

    $this->form_validation->set_error_delimiters('<div>* ', '</div>');

    $this->form_validation->set_rules($config);
    return $this->form_validation->run();
  }

  function send_mail() {
    $this->_is_ajax();
    error_reporting(0); //for prod mode
    if ($this->_validate_send_mail() == TRUE) {
      $post = $this->input->post();
      if ($post['port'] == '465') {
        $post['host'] = 'ssl://' . $post['host'];
      }
      $this->load->library('email');
      $this->email->initialize(array(
          'protocol' => 'smtp',
          'smtp_host' => $post['host'],
          'smtp_port' => $post['port'],
          'smtp_user' => $post['email_address'],
          'smtp_pass' => $post['email_password'],
          'mailtype' => 'html',
          'charset' => 'iso-8859-1',
          'newline' => "\r\n" // kode yang harus di tulis pada konfigurasi controler email
      ));

      $from = $post['email_address'];
      $nama = 'SIMS';
      $to = $post['email_receipt'];
      $subject = 'Testing Email Server';
      $message = $this->load->view('setting/email_test_page', $post, true);
      $this->email->from($from, $nama)
              ->to($to)
              ->subject($subject)
              ->message($message);
      if ($this->email->send()) {
        $resp = array(
            'status' => 'success',
            'message' => 'Email sent, please check your inbox.'
        );
      } else {
        echo $this->email->print_debugger();
        $resp = array(
            'status' => 'error',
            'message' => 'Email could not be sent, please check your configuration.'
        );
      }
    } else {
      $resp = array(
          'status' => 'warning',
          'message' => validation_errors()
      );
    }

    echo json_encode($resp);
  }

  function test_page() {
    error_reporting(0);
    $this->load->view('setting/email_test_page');
  }

  function load_email_receipt() {
    $this->_is_ajax();
    $this->load->view('setting/email_receipt');
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */