<?php

/**
 * This model provide data for Engineering Approval at ISR BTS
 *
 * @author tumbas
 */
class Release_dgpt_model extends CI_Model {

  private $_user_now;
  private $_date_now;

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
  //SPP payment
  const SPP_PAY_NEW = '1131';
  const SPP_PAY_RENEWAL = '1221';
   //DGPT Released
  const DGPT_NEW = '1141';
  const DGPT_RENEWAL = '1231';
  const DGPT_DISC_DISMANTLE = '1331';
  const DGPT_DISC_ISR_CHANGE = '1631';
  const DGPT_RELEASED_NEW = '1151';
  const DGPT_RELEASED_RENEWAL = '1241';

  public function __construct() {
    parent::__construct();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }
  
  public function get_release_dgpt_new_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status IN ('" . self::DGPT_NEW . "') ";  
    if ($param != '') {
      $keyword = $param;
      $where .= " AND (app_no ILIKE '%{$keyword}%' OR bpv_no ILIKE '%{$keyword}%')";
    }

    $sql = "SELECT pay.spp_pay_id, app.app_id, app_no, to_char(payment_date, 'DD/MM/YYYY') AS payment_date, bpv_no, pay.remark, app.doc AS doc, 
            COUNT(spp_pay_detail_id) AS total_link, f_get_user(pay.created_by) AS created_by, pay.created_on
            FROM t_trx_isr_bts_spp_pay pay
            LEFT JOIN t_trx_isr_bts_spp_pay_detail pay_det ON pay_det.spp_pay_id = pay.spp_pay_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = pay.app_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = pay_det.link_id
            {$where}
            GROUP BY pay.spp_pay_id,  app.app_id,  app_no, payment_date, bpv_no, pay.remark, app.doc, pay.created_by, pay.created_on
            ORDER BY pay.spp_pay_id DESC
            LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(DISTINCT(pay.spp_pay_id)) AS count
            FROM t_trx_isr_bts_spp_pay pay
            LEFT JOIN t_trx_isr_bts_spp_pay_detail pay_det ON pay_det.spp_pay_id = pay.spp_pay_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = pay.app_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = pay_det.link_id
            {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }

  public function get_release_dgpt_renewal_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status IN ('" . self::DGPT_RENEWAL . "') ";  
    if ($param != '') {
      $keyword = $param;
      $where .= " AND (app_no ILIKE '%{$keyword}%' OR bpv_no ILIKE '%{$keyword}%')";
    }

    $sql = "SELECT pay.spp_pay_id, app.app_id, app_no, to_char(payment_date, 'DD/MM/YYYY') AS payment_date, bpv_no, pay.remark, app.doc AS doc, 
            COUNT(spp_pay_detail_id) AS total_link, f_get_user(pay.created_by) AS created_by, pay.created_on
            FROM t_trx_isr_bts_spp_pay pay
            LEFT JOIN t_trx_isr_bts_spp_pay_detail pay_det ON pay_det.spp_pay_id = pay.spp_pay_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = pay.app_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = pay_det.link_id
            {$where}
            GROUP BY pay.spp_pay_id,  app.app_id,  app_no, payment_date, bpv_no, pay.remark, app.doc, pay.created_by, pay.created_on
            ORDER BY pay.spp_pay_id DESC
            LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(DISTINCT(pay.spp_pay_id)) AS count
            FROM t_trx_isr_bts_spp_pay pay
            LEFT JOIN t_trx_isr_bts_spp_pay_detail pay_det ON pay_det.spp_pay_id = pay.spp_pay_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = pay.app_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = pay_det.link_id
            {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }
  
  public function get_release_dgpt_discontinued_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status IN ('" . self::DGPT_DISC_DISMANTLE . "', '".self::DGPT_DISC_ISR_CHANGE."') ";  
    if ($param != '') {
      $keyword = $param;
      $where .= " AND (app_no ILIKE '%{$keyword}%' OR bpv_no ILIKE '%{$keyword}%')";
    }

    $sql = "SELECT pay.spp_pay_id, app.app_id, app_no, to_char(payment_date, 'DD/MM/YYYY') AS payment_date, bpv_no, app.remark, app.doc AS doc, 
            COUNT(spp_pay_detail_id) AS total_link, f_get_user(pay.created_by) AS created_by, pay.created_on
            FROM t_trx_isr_bts_spp_pay pay
            LEFT JOIN t_trx_isr_bts_spp_pay_detail pay_det ON pay_det.spp_pay_id = pay.spp_pay_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = pay.app_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = pay_det.link_id
            {$where}
            GROUP BY pay.spp_pay_id,  app.app_id,  app_no, payment_date, bpv_no, app.remark, app.doc, pay.created_by, pay.created_on
            ORDER BY pay.spp_pay_id DESC
            LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(DISTINCT(pay.spp_pay_id)) AS count
            FROM t_trx_isr_bts_spp_pay pay
            LEFT JOIN t_trx_isr_bts_spp_pay_detail pay_det ON pay_det.spp_pay_id = pay.spp_pay_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = pay.app_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = pay_det.link_id
            {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }
//  public function get_release_dgpt_detail_list($param=0) {
//   $sql = "SELECT link.*, bhp, lookup_desc AS transmitter_polarization_desc 
//            FROM t_trx_isr_bts_spp_pay_detail pay_det
//            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = pay_det.link_id
//            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin_det.link_id = pay_det.link_id
//            LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = link.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
//            WHERE spp_pay_id = {$param} ORDER BY station_identifier ASC";
//
//    return $this->db->query($sql)->result();
//  }
  
  public function get_release_dgpt_header($param=0) {
    $sql = "SELECT pay.spp_pay_id, app.app_id, ref_doc_id, app_no, to_char(app_issued, 'DD Mon YYYY') AS app_issued,  to_char(app_expired, 'DD Mon YYYY') AS app_expired,
            to_char(payment_date, 'DD/MM/YYYY') AS payment_date, bpv_no, pay.remark, file_name AS doc, COUNT(spp_pay_detail_id) AS total_link, 
            f_get_user(pay.created_by) AS created_by, pay.created_on, link.milestone_status
            FROM t_trx_isr_bts_spp_pay pay
            LEFT JOIN t_trx_isr_bts_spp_pay_detail pay_det ON pay_det.spp_pay_id = pay.spp_pay_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = pay.app_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = pay_det.link_id
            LEFT JOIN t_trx_isr_bts_doc docs ON docs.doc_id = pay.doc_id
            WHERE pay.spp_pay_id = {$param}
            GROUP BY pay.spp_pay_id,  app.app_id, ref_doc_id, app_no, app_issued, app_expired, payment_date, bpv_no, pay.remark, file_name, pay.created_by, pay.created_on, link.milestone_status";
    $query = $this->db->query($sql);
    
    return $query->row();
  }
  
  public function get_release_dgpt_detail_list($param=0) {
   $sql = "SELECT link.*, bhp, lookup_desc AS transmitter_polarization_desc, f_get_sitecode(link.site_id) AS site_code, 
            f_get_regionnamebysiteid_nocluster(link.site_id) AS region
            FROM t_trx_isr_bts_spp_pay_detail pay_det
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = pay_det.link_id
            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin_det.link_id = pay_det.link_id
            LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = link.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
            WHERE spp_pay_id = {$param} ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }
  
  public function get_release_dgpt_history_list($limit, $offset, $param = '') {
    $where = '';
    if ($param != '') {
      $keyword = $param;
      $where = "WHERE app_no ILIKE '%{$keyword}%'";
    }

    $sql = "SELECT dgpt.release_dgpt_id, app_no, to_char(doc_issued, 'DD/MM/YYYY') AS doc_issued, file_name AS doc, COUNT(release_dgpt_detail_id) AS total_link, 
            f_get_user(dgpt.created_by) AS created_by, dgpt.created_on, app.remark, doc_type
            FROM t_trx_isr_bts_release_dgpt dgpt
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = dgpt.app_id
            LEFT JOIN t_trx_isr_bts_release_dgpt_detail dgpt_det ON dgpt_det.release_dgpt_id = dgpt.release_dgpt_id
            LEFT JOIN t_trx_isr_bts_doc docs ON docs.doc_id = dgpt.doc_id
            {$where}
            GROUP BY dgpt.release_dgpt_id, app_no, doc_issued, file_name, dgpt.created_by, dgpt.created_on, app.remark, doc_type
            ORDER BY dgpt.release_dgpt_id DESC
            LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(DISTINCT(dgpt.release_dgpt_id)) AS count
            FROM t_trx_isr_bts_release_dgpt dgpt
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = dgpt.app_id
            LEFT JOIN t_trx_isr_bts_release_dgpt_detail dgpt_det ON dgpt_det.release_dgpt_id = dgpt.release_dgpt_id
            LEFT JOIN t_trx_isr_bts_doc docs ON docs.doc_id = dgpt.doc_id
            {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }
  
  public function get_release_dgpt_history_detail_list($param=0) {
     $sql = "SELECT link.*, bhp, lookup_desc AS transmitter_polarization_desc 
            FROM t_trx_isr_bts_release_dgpt_detail dgpt_det
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = dgpt_det.link_id
            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin_det.link_id = dgpt_det.link_id
            LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = link.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
            WHERE release_dgpt_id = {$param} ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }

}

?>
