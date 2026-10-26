<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class MSetting extends CI_Model {

  function get_result($lookup_keys = '') {
    $sql = "SELECT * FROM t_mtr_lookup WHERE lookup_key IN ({$lookup_keys})";
    $result = $this->db->query($sql)->result();

    $config = array();
    foreach ($result as $value) {
      $config[$value->lookup_key][$value->lookup_desc] = $value->lookup_value;
    }

    return $config;
  }

  function save() {
    $this->db->trans_start();

    $post = $this->input->post();
    foreach ($post as $key => $value) {
      if (is_array($value)) {
        foreach ($value as $key1 => $value1) {
          $this->db->where('lookup_key', $key);
          $this->db->where('lookup_desc', $key1);
          $data = array(
              'lookup_value' => $value1
          );
          $this->db->update('t_mtr_lookup', $data);
        }
      } else {
        $this->db->where('lookup_key', $key);
        $data = array(
            'lookup_value' => $value
        );
        $this->db->update('t_mtr_lookup', $data);
      }
    }

    $this->dblog->AuditTrail('setting-save', $post);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

}

?>