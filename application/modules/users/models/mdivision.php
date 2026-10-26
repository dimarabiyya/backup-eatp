<?php

class Mdivision extends CI_Model {
  public function get_lists($data = NULL) {
    if ($data['q'] != '') {
      $q = $data['q'];
      $q = strtolower($q);
      $or_arr = array('user_div_name');
      $or_str = "";
      foreach ($or_arr as $val) {
        if ($or_str == "")
          $or_str.="(lower($val) like '%$q%'";
        else
          $or_str.=" or lower($val) like '%$q%'";
      }
      $or_str.=")";
      $this->db->where($or_str);

      unset($data['q']);
    }
    $this->db->where('status', 1);
    $q = $this->db->get('t_mtr_user_division');
    return $q;
  }

  public function get_list($offset = 0, $rows = 0, $data = '') {
    if ($data['q'] != '') {
      $q = $data['q'];
      $or_arr = array('user_div_name');
      $or_str = "";
      $q = strtolower($q);
      foreach ($or_arr as $val) {
        if ($or_str == "")
          $or_str.="(lower($val) like '%$q%'";
        else
          $or_str.=" or lower($val) like '%$q%'";
      }
      $or_str.=")";
      $this->db->where($or_str);

      unset($data['q']);
    }
    $this->db->where('status', 1);
    $this->db->order_by('user_div_name', 'asc');
    if ($rows == 0)
      $q = $this->db->get(' t_mtr_user_division');
    else
      $q = $this->db->get(' t_mtr_user_division', $rows, $offset);
    return $q;
  }

  ############################################

  function getdivisionDetail($id) {
    $dblokal = $this->load->database("default", TRUE);
    $q = "SELECT * FROM t_mtr_user_division WHERE user_div_id = $id ";
    $query = $dblokal->query($q);
    return $query->result();
  }

  public function saveData() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('user_div_id', 'user_div_name');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $data = array('user_div_name' => $user_div_name,
        'status' => 1);

    if ($user_div_id == "") {
      $data['created_on'] = date("Y-m-d H:i:s");
      $data['created_by'] = $this->session->userdata('user_id');

      $dblokal->insert('t_mtr_user_division', $data);
      $dblokal->insert_id();
    } else {
      $data['updated_on'] = date("Y-m-d H:i:s");
      $data['updated_by'] = $this->session->userdata('user_id');

      $dblokal->where(array('user_div_id' => $user_div_id));
      $dblokal->update('t_mtr_user_division', $data);
    }
  }

  public function deleteData() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('user_div_id');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $dblokal->where(array('user_div_id' => $user_div_id));
    $dblokal->update('t_mtr_user_division', array('status' => 0));
  }

}

?>