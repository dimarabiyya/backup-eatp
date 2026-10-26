<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Division extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->mglobal->is_logged_in();
    $this->load->model('mdivision');
  }

  ########################################################################
  //Manag.division

  public function index() {
    $data['judul'] = "Division";
    $data['content_id'] = "division/index";
    $this->theme->view("division/index", $data);
  }

  public function deleteData() {
    $this->mdivision->deleteData();
  }

  function get_list() {
    $dt = $this->input->post();
    $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
    $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
    $dt['offset'] = ($dt['page'] - 1) * $dt['rows'];
    $q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : '';
    $data['q'] = strtolower($q);

    $d = $this->input->get();
    $qs = $this->mdivision->get_lists($data);
    $result["total"] = $qs->num_rows();

    $q = $this->mdivision->get_list($dt['offset'], $dt['rows'], $data);
    $items = array();
    foreach ($q->result() as $row) {
      $row->action = '<a onclick="Document.editData(' . $row->user_div_id . ')" title="Edit" class="btn-action glyphicons pencil btn-info" >
								<i></i></a><a onclick="Document.deleteData(' . $row->user_div_id . ')" title="Delete" class="btn-action glyphicons remove_2 btn-danger" ><i></i></a>';

      array_push($items, $row);
    }
    $result["rows"] = $items;
    echo json_encode($result);
  }

  public function addeditdata() {
    $input = array('user_div_id', 'user_div_name');
    foreach ($input as $val) {
      $data[$val] = "";
    }
    $data['user_div_id'] = $this->input->post('user_div_id');
    $data['judul'] = "Add Division";
    if ($data['user_div_id'] <> "") {
      $data['judul'] = "Edit Division";
      $getDetail = $this->mdivision->getdivisionDetail($data['user_div_id']);
      foreach ($getDetail as $r) {
        foreach ($input as $val) {
          $data[$val] = $r->$val;
        }
      }
    }
    echo $this->load->view("division/form", $data);
  }

  public function saveData() {
    //lakukan validasi di sini
    $input = array('user_div_id', 'user_div_name');
    foreach ($input as $val)
      $$val = $this->input->post($val);
    $status = "ERROR";
    $hasil = "";
    if ($user_div_name == "") {
      $hasil = "Found Error: \n";
      if ($user_div_name == "") {
        $hasil .= "Please Input Division Name \n";
      }
    } elseif ($user_div_name != '' && $this->core->view_row('t_mtr_user_division', array('UPPER(user_div_name)' => strtoupper($user_div_name), 'status' => 1, 'user_div_id !=' => (int) $user_div_id))) {
      $hasil .= "Division Name has already in used \n";
    } else {
      //simpan data ke sini
      $status = "OK";
      $this->mdivision->saveData();
    }
    echo json_encode(array(
        'status' => $status,
        'hasil' => $hasil
    ));
    return;
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */