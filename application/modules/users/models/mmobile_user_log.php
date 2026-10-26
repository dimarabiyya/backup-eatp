<?php

class Mmobile_user_log extends CI_Model {

  function get_list() {
    $post = $this->input->post();

    $page = intval($post['page']);
    $limit = intval($post['rows']);
    $offset = ($page - 1) * $limit;

    $where = "WHERE log_key IN ('user_login', 'user_logout') AND interface = 'mobile'";
    if (isset($post['q']) && $post['q'] != '') {
      $post['q'] = $this->db->escape_like_str(strtolower($post['q']));
      $where .= " AND (LOWER(log_key) LIKE '%{$post['q']}%' OR LOWER(log_action) LIKE '%{$post['q']}%' OR LOWER(f_get_user(user_id)) LIKE '%{$post['q']}%')";
    }
    
    if (isset($post['start_date']) && $post['start_date'] != '' && isset($post['end_date']) && $post['end_date'] != '') {
      $where .= " AND created_on::DATE BETWEEN '{$post['start_date']}' AND '{$post['end_date']}'";
    }

    $sql = " SELECT log_key, log_action, f_get_user(user_id) AS creator, to_char(created_on, 'DD FMMon YYYY HH24:MI:SS') AS login_date
            FROM t_log
            {$where}
            ORDER BY {$post['sort']} {$post['order']}
            LIMIT {$limit} OFFSET {$offset}";
    $result['rows'] = $this->db->query($sql)->result();
//    die($this->db->last_query());
    $sql = "SELECT COUNT(log_id) AS count FROM t_log {$where}";
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
      $this->db->update('t_trx_mobile_user_log_version', array('active' => 0));
      $data['active'] = 1;
    }

    $this->mglobal->saveData('t_trx_mobile_user_log_version', $data);

    $this->dblog->AuditTrail('mobile_user_log-add', $data);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  function get_row($param = '') {
    $this->db->where('version_id', $param);
    return $this->db->get('t_trx_mobile_user_log_version')->row();
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

    $this->db->update('t_trx_mobile_user_log_version', array('active' => 0));

    $this->mglobal->saveData('t_trx_mobile_user_log_version', $data, 'version_id');

    $this->dblog->AuditTrail('mobile_user_log-edit', $data);
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
    $this->db->delete('t_trx_mobile_user_log_version');

    $this->dblog->AuditTrail('mobile_user_log-delete', $data);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

}

?>