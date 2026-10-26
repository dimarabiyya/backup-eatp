<?php

/**
 * This model provide data for Engineering Approval at ISR BTS
 *
 * @author tumbas
 */
class Engineering_approval_model extends CI_Model {

  private $_user_now;
  private $_date_now;

  const ENGIN_APPROV_APPROVED = 1;
  const ENGIN_APPROV_REJECTED = 2;

//  ISR Status
  //Engineering Approval
  const ENGIN_APPROV_NEW = '1161';
  const ENGIN_APPROV_RENEWAL = '1261';
  const ENGIN_APPROV_DISC_DISMANTLE = '1361';
  const ENGIN_APPROV_DISC_ISR_CHANGE = '1661';
  //M2M
  const M2M_NEW = '1171';
  const M2M_RENEWAL = '1271';
  const M2M_DISC_DISMANTLE = '1371';
  const M2M_DISC_ISR_CHANGE = '1671';
  //SPP to Finance
  const SPP_FIN_NEW = '1121';
  const SPP_FIN_RENEWAL = '1211';
  //Release DGPT
  const RELEASE_DGPT_DISC_DISMANTLE = '1331';
  const RELEASE_DGPT_DISC_ISR_CHANGE = '1631';

  public function __construct() {
    parent::__construct();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function get_engineering_approval_new_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status = '" . self::ENGIN_APPROV_NEW . "' ";
    if ($param != '') {
      $where .= " AND (app.app_no ILIKE '%{$param}%' OR CAST(app.app_issued AS VARCHAR) ILIKE '%{$param}%' OR CAST(app.app_expired AS VARCHAR) ILIKE '%{$param}%' OR
        app.doc ILIKE '%{$param}%' OR app.remark ILIKE '%{$param}%')";
    }

    $sql = " SELECT DISTINCT app.app_resi_id, app.app_no, to_char(app.app_issued, 'DD/MM/YYYY') AS app_issued , app.doc, app.remark, f_get_user(app.created_by) AS created_by, 
            app.created_on, (SELECT COUNT(DISTINCT(resi_link_id)) FROM t_trx_isr_bts_app_resi_link  WHERE app_resi_id=app.app_resi_id) as total_links, app.\"isUsed\"
            FROM t_trx_isr_bts_app_resi app
            LEFT JOIN t_trx_isr_bts_app_resi_link link ON app.app_resi_id=link.app_resi_id
            {$where}
            ORDER BY app.app_resi_id DESC
            LIMIT {$limit} OFFSET {$offset}";

    $result['rows'] = $this->db->query($sql)->result();

    $sql = "SELECT COUNT (DISTINCT app.app_resi_id) AS count
                FROM t_trx_isr_bts_app_resi app
                LEFT JOIN t_trx_isr_bts_app_resi_link link ON app.app_resi_id=link.app_resi_id
                {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;

    return $result;
  }
  
  public function get_engineering_approval_renewal_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status IN (".self::ENGIN_APPROV_RENEWAL.") ";
    if ($param != '') {
      $where .= " AND (app.app_no ILIKE '%{$param}%' OR CAST(app.app_issued AS VARCHAR) ILIKE '%{$param}%' OR CAST(app.app_expired AS VARCHAR) ILIKE '%{$param}%' OR
        app.doc ILIKE '%{$param}%' OR app.remark ILIKE '%{$param}%')";
    }

    $sql = " SELECT DISTINCT app.app_id, app.app_no, to_char(app.app_issued, 'DD/MM/YYYY') AS app_issued , app.doc, app.remark, f_get_user(app.created_by) AS created_by, 
            app.created_on, (SELECT COUNT(DISTINCT(app_link_id)) FROM t_trx_isr_bts_app_link  WHERE app_id=app.app_id) as total_links, app.\"isUsed\"
            FROM t_trx_isr_bts_app app
            LEFT JOIN t_trx_isr_bts_app_link link ON app.app_id=link.app_id
            {$where}
            ORDER BY app.app_id DESC
            LIMIT {$limit} OFFSET {$offset}";

    $result['rows'] = $this->db->query($sql)->result();

    $sql = "SELECT COUNT (DISTINCT app.app_id) AS count
                FROM t_trx_isr_bts_app app
                LEFT JOIN t_trx_isr_bts_app_link link ON app.app_id=link.app_id
                {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;

    return $result;
  }
  
  public function get_engineering_approval_discontinued_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status IN (".self::ENGIN_APPROV_DISC_DISMANTLE.", ".self::ENGIN_APPROV_DISC_ISR_CHANGE.") ";
    if ($param != '') {
      $where .= " AND (app.app_no ILIKE '%{$param}%' OR CAST(app.app_issued AS VARCHAR) ILIKE '%{$param}%' OR CAST(app.app_expired AS VARCHAR) ILIKE '%{$param}%' OR
        app.doc ILIKE '%{$param}%' OR app.remark ILIKE '%{$param}%')";
    }

    $sql = " SELECT DISTINCT app.app_id, app.app_no, to_char(app.app_issued, 'DD/MM/YYYY') AS app_issued , app.doc, app.remark, f_get_user(app.created_by) AS created_by, 
            app.created_on, (SELECT COUNT(DISTINCT(app_link_id)) FROM t_trx_isr_bts_app_link  WHERE app_id=app.app_id) as total_links, app.\"isUsed\"
            FROM t_trx_isr_bts_app app
            LEFT JOIN t_trx_isr_bts_app_link link ON app.app_id=link.app_id
            {$where}
            ORDER BY app.app_id DESC
            LIMIT {$limit} OFFSET {$offset}";

    $result['rows'] = $this->db->query($sql)->result();

    $sql = "SELECT COUNT (DISTINCT app.app_id) AS count
                FROM t_trx_isr_bts_app app
                LEFT JOIN t_trx_isr_bts_app_link link ON app.app_id=link.app_id
                {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;

    return $result;
  }

  public function get_engineering_approval_detail_new_list($param = '', $resi_id = '') {
    $where = " WHERE app_resi_id = '{$resi_id}' ";

    $sql = "SELECT bts.*, polar.lookup_desc AS transmitter_polarization_desc 
        FROM t_trx_isr_bts_app_resi_link bts
        LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
        {$where} 
      ORDER BY station_identifier ASC";

    $result['rows'] = $this->db->query($sql)->result();

    $sql = "SELECT COUNT (DISTINCT resi_link_id) AS count
                FROM t_trx_isr_bts_app_resi_link
                {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;

    return $result;
  }
  
  public function get_engineering_approval_detail_renewal_list($param = '', $app_id = '') {
    $where = " WHERE app_id = '{$app_id}' ";

    $sql = "SELECT bts.*, polar.lookup_desc AS transmitter_polarization_desc 
        FROM t_trx_isr_bts_app_link bts
        LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
        {$where} 
      ORDER BY station_identifier ASC";

    $result['rows'] = $this->db->query($sql)->result();

    $sql = "SELECT COUNT (DISTINCT app_link_id) AS count
                FROM t_trx_isr_bts_app_link
                {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;

    return $result;
  }
  
  public function get_engineering_approval_detail_discontinued_list($param = '', $app_id = '') {
    $where = " WHERE app_id = '{$app_id}' ";

    $sql = "SELECT bts.*, polar.lookup_desc AS transmitter_polarization_desc 
        FROM t_trx_isr_bts_app_link bts
        LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
        {$where} 
      ORDER BY station_identifier ASC";

    $result['rows'] = $this->db->query($sql)->result();

    $sql = "SELECT COUNT (DISTINCT app_link_id) AS count
                FROM t_trx_isr_bts_app_link
                {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;

    return $result;
  }

  public function get_app_resi_link($param) {
    $this->db->_protect_identifiers = false;
    $this->db->select('bts.*, polar.lookup_desc AS transmitter_polarization_desc');
    if (is_array($param)) {
      $this->db->where($param);
    } else {
      $this->db->where('resi_link_id', $param);
    }
    $this->db->join('t_mtr_lookup polar', "polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'", 'left');
    $this->db->order_by('station_identifier', 'asc');

    return $this->db->get('t_trx_isr_bts_app_resi_link bts')->result();
  }
  
  public function get_app_link($param) {
    $this->db->_protect_identifiers = false;
    $this->db->select('bts.*, polar.lookup_desc AS transmitter_polarization_desc');
    if (is_array($param)) {
      $this->db->where($param);
    } else {
      $this->db->where('app_id', $param);
    }
    $this->db->join('t_mtr_lookup polar', "polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'", 'left');
    $this->db->order_by('station_identifier', 'asc');

    return $this->db->get('t_trx_isr_bts_app_link bts')->result();
  }

  function update_email_rejected_link($data, $app_id) {
    $cek_email = $this->db->get_where('t_trx_isr_bts_app_resi_link', array('app_resi_id' => $app_id))->row()->email_rejected;
    if ($cek_email != NULL || $cek_email != '') {
      $data['email_rejected'] = ',' . $data['email_rejected'];
    }
    $sql = "UPDATE t_trx_isr_bts_app_resi_link SET engineering_approval_status = '" . self::ENGIN_APPROV_REJECTED . "', rejected_by = '{$this->_user_now}', 
      rejected_on = '{$this->_date_now}', email_rejected = COALESCE(email_rejected, '')||'{$data['email_rejected']}' WHERE app_resi_id = {$app_id} ";
    $query = $this->db->query($sql);

    return $query;
  }
  
  function update_email_rejected_link2($data, $app_id) {
    $cek_email = $this->db->get_where('t_trx_isr_bts_app_link', array('app_id' => $app_id))->row()->email_rejected;
    if ($cek_email != NULL || $cek_email != '') {
      $data['email_rejected'] = ',' . $data['email_rejected'];
    }
    $sql = "UPDATE t_trx_isr_bts_app_link SET engineering_approval_status = '" . self::ENGIN_APPROV_REJECTED . "', rejected_by = '{$this->_user_now}', 
      rejected_on = '{$this->_date_now}', email_rejected = COALESCE(email_rejected, '')||'{$data['email_rejected']}' WHERE app_id = {$app_id} ";
    $query = $this->db->query($sql);

    return $query;
  }

  function check_app_link_status($app_id) {
    $this->db->where('app_resi_id', $app_id);
    $this->db->where('engineering_approval_status IS NULL');
    $this->db->or_where('engineering_approval_status', self::ENGIN_APPROV_REJECTED);
    $query = $this->db->get('t_trx_isr_bts_app_resi_link');

    if ($query->row() == NULL) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
  
  function check_app_link_status2($app_id) {
    $this->db->where('app_id', $app_id);
    $this->db->where('engineering_approval_status IS NULL');
    $this->db->or_where('engineering_approval_status', self::ENGIN_APPROV_REJECTED);
    $query = $this->db->get('t_trx_isr_bts_app_link');

    if ($query->row() == NULL) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function get_engineering_approval_approved_header_list($limit, $offset, $param) {
    //$where = 'app.app_type = 2';
    $where = '';
    if (isset($param)) {
      $where .= "WHERE (app.app_no ILIKE '%{$param}%')";
    }

    $sql = "SELECT DISTINCT  app.app_resi_id, app.app_no, to_char(app.app_issued, 'DD/MM/YYYY') AS app_issued, isr_doc.file_name AS doc, app.remark,
            f_get_user(approv2.created_by) AS created_by, approv2.created_on, (SELECT COUNT(resi_link_id) FROM t_trx_isr_bts_app_resi_link WHERE app_resi_id = app.app_resi_id) AS total_links
            FROM t_trx_isr_bts_app_resi app 
            JOIN (SELECT MAX(approval_id) approval_id, app_id FROM t_trx_isr_bts_approval GROUP BY app_id) AS approv ON approv.app_id = app.app_resi_id
            JOIN t_trx_isr_bts_approval approv2 ON approv2.approval_id = approv.approval_id
            LEFT JOIN t_trx_isr_bts_doc isr_doc ON isr_doc.doc_id = approv2.doc_id
            JOIN t_trx_isr_bts_app_resi_link link ON link.app_resi_id = app.app_resi_id --AND link.milestone_status NOT IN (" . self::ENGIN_APPROV_NEW . ", " . self::ENGIN_APPROV_RENEWAL . ")
            {$where}
            ORDER BY approv2.created_on DESC
            LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(DISTINCT app.app_resi_id)
            FROM t_trx_isr_bts_app_resi app 
            JOIN (SELECT MAX(approval_id) approval_id, app_id FROM t_trx_isr_bts_approval GROUP BY app_id) AS approv ON approv.app_id = app.app_resi_id
            JOIN t_trx_isr_bts_approval approv2 ON approv2.approval_id = approv.approval_id
            JOIN t_trx_isr_bts_app_resi_link link ON link.app_resi_id = app.app_resi_id --AND link.milestone_status NOT IN (" . self::ENGIN_APPROV_NEW . ", " . self::ENGIN_APPROV_RENEWAL . ")
            {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }

  public function get_engineering_approval_detail_list($resi_id = '') {
    $sql = "SELECT bts.*, polar.lookup_desc AS transmitter_polarization_desc 
        FROM t_trx_isr_bts_app_resi_link bts
        LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
        WHERE app_resi_id = '{$resi_id}'
      ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }
  
  function get_engineering_approval_approved_renewal_discontinued_list($limit, $offset, $param) {
    //$where = 'app.app_type = 2';
    $where = '';
    if (isset($param)) {
      $where .= "WHERE (app.app_no ILIKE '%{$param}%')";
    }

    $sql = "SELECT DISTINCT  app.app_id, app.app_no, to_char(app.app_issued, 'DD/MM/YYYY') AS app_issued, isr_doc.file_name AS doc, app.remark,
            f_get_user(approv2.created_by) AS created_by, approv2.created_on, (SELECT COUNT(app_link_id) FROM t_trx_isr_bts_app_link WHERE app_id = app.app_id) AS total_links
            FROM t_trx_isr_bts_app app 
            JOIN (SELECT MAX(approval_id) approval_id, app_id FROM t_trx_isr_bts_approval GROUP BY app_id) AS approv ON approv.app_id = app.app_id
            JOIN t_trx_isr_bts_approval approv2 ON approv2.approval_id = approv.approval_id
            LEFT JOIN t_trx_isr_bts_doc isr_doc ON isr_doc.doc_id = approv2.doc_id
            JOIN t_trx_isr_bts_app_link link ON link.app_id = app.app_id --AND link.milestone_status NOT IN (" . self::ENGIN_APPROV_NEW . ", " . self::ENGIN_APPROV_RENEWAL . ")
            {$where}
            ORDER BY approv2.created_on DESC
            LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(DISTINCT app.app_id)
            FROM t_trx_isr_bts_app app 
            JOIN (SELECT MAX(approval_id) approval_id, app_id FROM t_trx_isr_bts_approval GROUP BY app_id) AS approv ON approv.app_id = app.app_id
            JOIN t_trx_isr_bts_approval approv2 ON approv2.approval_id = approv.approval_id
            JOIN t_trx_isr_bts_app_link link ON link.app_id = app.app_id --AND link.milestone_status NOT IN (" . self::ENGIN_APPROV_NEW . ", " . self::ENGIN_APPROV_RENEWAL . ")
            {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }
  
  public function get_engineering_approval_detail_list2($app_id = '') {
    $sql = "SELECT bts.*, polar.lookup_desc AS transmitter_polarization_desc 
        FROM t_trx_isr_bts_app_link bts
        LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
        WHERE app_id = '{$app_id}'
      ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }

}

?>
