<?php

class Mmobile_app extends CI_Model {

  function get_list() {
    $post = $this->input->post();

    $page = intval($post['page']);
    $limit = intval($post['rows']);
    $offset = ($page - 1) * $limit;

    $where = '';
    if (isset($post['q']) && $post['q'] != '') {
      $post['q'] = $this->db->escape_str($post['q']);
      $where = " WHERE version_number LIKE '%{$post['q']}%' OR app_url LIKE '%{$post['q']}%'";
    }

    $sql = " SELECT *, f_get_user(created_by) AS creator FROM t_trx_mobile_app_version
            {$where}
            ORDER BY {$post['sort']} {$post['order']}
            LIMIT {$limit} OFFSET {$offset}";
    $result['rows'] = $this->db->query($sql)->result();

    $sql = "SELECT COUNT(version_id) AS count FROM t_trx_mobile_app_version {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;

    return $result;
  }

  function add($file = array()) {
    $this->db->trans_start();

    $post = $this->input->post();
    $data = array(
        'version_number' => $post['version_number'],
        'app_url' => "{$file['file_name']}",
        'release_date' => MakeUpDate($post['release_date']),
    );

    if (isset($post['active'])) {
      $this->db->update('t_trx_mobile_app_version', array('active' => 0));
      $data['active'] = 1;
    }

    $this->mglobal->saveData('t_trx_mobile_app_version', $data);

    $this->dblog->AuditTrail('mobile_app-add', $data);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  function get_row($param = '') {
    $this->db->where('version_id', $param);
    return $this->db->get('t_trx_mobile_app_version')->row();
  }

  function edit($file = array()) {
    $this->db->trans_start();

    $post = $this->input->post();
    $data = array(
        'version_id' => $this->core->decode($post['version_id']),
        'version_number' => $post['version_number'],
        'release_date' => MakeUpDate($post['release_date']),
        'active' => isset($post['active']) ? 1 : 0
    );

    if ($file != NULL) {
      $data['app_url'] = $file['file_name'];
    }

    $this->db->update('t_trx_mobile_app_version', array('active' => 0));
//    if (isset($post['active'])) {
//      $data['active'] = 1;
//    }

    $this->mglobal->saveData('t_trx_mobile_app_version', $data, 'version_id');

    $this->dblog->AuditTrail('mobile_app-edit', $data);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  function delete() {
    $this->db->trans_start();

    $post = $this->input->post();
    $data = array(
        'version_id' => $this->core->decode($post['version_id'])
    );
    $this->db->where($data);
    $this->db->delete('t_trx_mobile_app_version');

    $this->dblog->AuditTrail('mobile_app-delete', $data);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

}

?>