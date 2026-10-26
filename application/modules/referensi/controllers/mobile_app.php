<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Mobile_app extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->mglobal->is_logged_in();
    $this->load->model('mmobile_app');
  }

  public function index() {
    $data['judul'] = "Mobile App";
    $data['content_id'] = "mobile_app/index";
    $this->_generate_view($data);
  }

  protected function _validate() {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<div>* ', '</div>');

    $this->form_validation->set_rules('version_number', 'Version Number', 'trim|required|callback__version');
    $this->form_validation->set_rules('release_date', 'Release Date', 'trim|required');
    $this->form_validation->set_rules('active', 'Version Number', 'trim|is_natural_no_zero');

    return $this->form_validation->run();
  }

  function _version($param) {
    if (!preg_match('/^[0-9\.]+$/', $param)) {
      $this->form_validation->set_message('_version', 'The %s field must contains only numeric & dot character.');
      return FALSE;
    }

    if (!preg_match('/^\d/', $param)) {
      $this->form_validation->set_message('_version', 'The %s field must numeric prefix.');
      return FALSE;
    }

    if (!preg_match('/\d\z/', $param)) {
      $this->form_validation->set_message('_version', 'The %s field must numeric suffix.');
      return FALSE;
    }

    return TRUE;
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

    $result = $this->mmobile_app->get_list();
    $menu = $this->mmenu->getMenuId("referensi/mobile_app");

    $response['total'] = $result['total'];
    $response['rows'] = array();
    foreach ($result['rows'] as $row) {
      $version_id = $this->core->encode($row->version_id);
      $row->action = '';
      if ($this->mmenu->cekPermission($menu, 'menu_edit')) {
        $row->action.= '<button title="Add New" class="btn btn-action glyphicons pencil btn-success"  onclick="editData(\'' . $version_id . '\')"><i></i></button> &nbsp;';
      }
      if ($this->mmenu->cekPermission($menu, 'menu_delete')) {
        $row->action.= '<button title="Delete" class="btn btn-action glyphicons remove_2 btn-danger"  onclick="deleteData(\'' . $version_id . '\')"><i></i></button> &nbsp;';
      }
      $row->release_date = MakeUpDate($row->release_date, "Ymd", "d M Y");
      $row->active = $this->_get_status($row->active);
      $row->app_url = anchor(base_url("download/{$row->app_url}"), $row->app_url);
      $row->creator = "{$row->creator}<br><span class='gray'>{$row->created_on}</span>";
      array_push($response['rows'], $row);
    }

    echo json_encode($response);
  }

  private function _get_status($param) {
    $status = '';
    if ($param == 1) {
      $status = '<span class="badge badge-success">Active</span>';
    } else {
      $status = '<span class="badge badge-default">Not Active</span>';
    }
    return $status;
  }

  public function add() {
    $this->_is_ajax();

    if ($this->input->post() == FALSE) {
      $this->load->view('mobile_app/add');
    } else {

      if ($this->_validate() == TRUE) {
        $file = $this->_upload_apk();

        if ($file['status'] != 'success') {
          echo json_encode($file);
          exit();
        }

        $query = $this->mmobile_app->add($file['data']);
        if ($query) {
          $response = array(
              'status' => 'success',
              'message' => 'Data APK successfully saved.',
          );
        } else {
          $response = array(
              'status' => 'error',
              'message' => 'Data APK could not be saved.',
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
  }

  public function edit($param = '') {
    $this->_is_ajax();
    $param = $this->core->decode($param);
    if ($this->input->post() == FALSE) {
      $row = $this->mmobile_app->get_row($param);
      $row->version_id = $this->core->encode($row->version_id);
      $row->release_date = MakeUpDate($row->release_date, "Ymd", "d M Y");
      $data['row'] = $row;

      $this->load->view('mobile_app/edit', $data);
    } else {

      if ($this->_validate() == TRUE) {

        if ($_FILES['userfile']['name'] != '') {
          $file = $this->_upload_apk();
          if ($file['status'] != 'success') {
            echo json_encode($file);
            exit();
          }
        } else {
          $file = array('data' => NULL);
        }
        $query = $this->mmobile_app->edit($file['data']);
        if ($query) {
          $response = array(
              'status' => 'success',
              'message' => 'Data APK successfully updated.',
          );
        } else {
          $response = array(
              'status' => 'error',
              'message' => 'Data APK could not be updated.',
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
  }

  private function _upload_apk() {
    $config['upload_path'] = $this->_get_upload_path();
    $config['allowed_types'] = 'apk';

    $this->load->library('upload', $config);
    $resp = array();
    if ($this->upload->do_upload()) {
      $resp = array(
          'status' => 'success',
          'message' => 'APK succesfully uploaded.',
          'data' => $this->upload->data()
      );
    } else {
      $resp = array(
          'status' => 'warning',
          'message' => $this->upload->display_errors()
      );
    }

    return $resp;
  }

  private function _get_upload_path() {
    $path = 'download';
    if (!file_exists($path)) {
      $make_dir = mkdir($path, 0755, true);
      if ($make_dir == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to make directory "Download"'
        ));
        exit();
      }
    }

    return $path;
  }

  function delete() {
    $this->_is_ajax();
    $query = $this->mmobile_app->delete();
    if ($query) {
      $response = array(
          'status' => 'success',
          'message' => 'Data APK successfully deleted.',
      );
    } else {
      $response = array(
          'status' => 'error',
          'message' => 'Data APK could not be delleted.',
      );
    }

    echo json_encode($response);
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */