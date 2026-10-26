<?php

/**
 * This model provide data for Engineering Approval at ISR BTS
 *
 * @author tumbas
 */
class Spp_payment_model extends CI_Model {

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

  public function __construct() {
    parent::__construct();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }
  
  public function get_spp_payment_new_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status = '" . self::SPP_PAY_NEW . "' ";  
    if ($param != '') {
      $keyword = $param;
      $where .= " AND (app_no ILIKE '%{$keyword}%' OR spp_no ILIKE '%{$keyword}%')";
    }

    $sql = "SELECT fin.spp_fin_id, app.app_id, app_no, spp_no, to_char(spp_start_date, 'DD/MM/YYYY') AS spp_start_date, to_char(spp_due_date, 'DD/MM/YYYY') as spp_due_date, 
            to_char(give_to_fin_date, 'DD/MM/YYYY') as give_to_fin_date, fin.remark, count(spp_fin_detail_id) AS total_link, sum(bhp) as total_bhp, 
            app.doc, f_get_user(fin.created_by) as created_by, fin.created_on 
            FROM t_trx_isr_bts_spp_fin fin
            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin.spp_fin_id = fin_det.spp_fin_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = fin_det.link_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = fin.app_id
            {$where}
            GROUP BY fin.spp_fin_id, app.app_id, app_no, spp_no, spp_start_date, spp_due_date, give_to_fin_date, fin.remark, app.doc, fin.created_by, fin.created_on
            ORDER BY fin.spp_fin_id DESC
            LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(DISTINCT(fin.spp_fin_id) ) AS count
            FROM t_trx_isr_bts_spp_fin fin
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = fin.app_id
            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin.spp_fin_id = fin_det.spp_fin_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = fin_det.link_id
            {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }
  
  public function get_spp_payment_renewal_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status = '" . self::SPP_PAY_RENEWAL . "' ";  
    if ($param != '') {
      $keyword = $param;
      $where .= " AND (app_no ILIKE '%{$keyword}%' OR spp_no ILIKE '%{$keyword}%')";
    }

    $sql = "SELECT fin.spp_fin_id, app.app_id, app_no, spp_no, to_char(spp_start_date, 'DD/MM/YYYY') AS spp_start_date, to_char(spp_due_date, 'DD/MM/YYYY') as spp_due_date, 
            to_char(give_to_fin_date, 'DD/MM/YYYY') as give_to_fin_date, fin.remark, count(spp_fin_detail_id) AS total_link, sum(bhp) as total_bhp, 
            app.doc, f_get_user(fin.created_by) as created_by, fin.created_on 
            FROM t_trx_isr_bts_spp_fin fin
            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin.spp_fin_id = fin_det.spp_fin_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = fin_det.link_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = fin.app_id
            {$where}
            GROUP BY fin.spp_fin_id, app.app_id, app_no, spp_no, spp_start_date, spp_due_date, give_to_fin_date, fin.remark, app.doc, fin.created_by, fin.created_on
            ORDER BY fin.spp_fin_id DESC
            LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(DISTINCT(fin.spp_fin_id) ) AS count
            FROM t_trx_isr_bts_spp_fin fin
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = fin.app_id
            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin.spp_fin_id = fin_det.spp_fin_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = fin_det.link_id
            {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }

  public function get_spp_payment_header($param=0) {
    $sql = "SELECT fin.spp_fin_id, app.app_id, app_no, spp_no, to_char(spp_start_date, 'DD/MM/YYYY') AS spp_start_date, to_char(spp_due_date, 'DD/MM/YYYY') as spp_due_date, 
            to_char(give_to_fin_date, 'DD/MM/YYYY') as give_to_fin_date, fin.remark, count(spp_fin_detail_id) AS total_link, sum(bhp) as total_bhp, 
            file_name as doc, f_get_user(fin.created_by) as created_by, fin.created_on 
            FROM t_trx_isr_bts_spp_fin fin
            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin.spp_fin_id = fin_det.spp_fin_id
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = fin_det.link_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = fin.app_id
            LEFT JOIN t_trx_isr_bts_doc docs ON docs.doc_id = fin.doc_id
            WHERE fin.spp_fin_id = {$param}
            GROUP BY fin.spp_fin_id, app.app_id, app_no, spp_no, spp_start_date, spp_due_date, give_to_fin_date, fin.remark, file_name, fin.created_by, fin.created_on";
    $query = $this->db->query($sql);
    
    return $query->row();

  }
  
  public function get_spp_payment_detail_list($param=0) {
   $sql = "SELECT link.*, bhp, lookup_desc AS transmitter_polarization_desc, f_get_sitecode(link.site_id) AS site_code, 
            f_get_regionnamebysiteid_nocluster(link.site_id) AS region
            FROM t_trx_isr_bts_spp_fin_detail fin_det
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = fin_det.link_id
            LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = link.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
            WHERE spp_fin_id = {$param} ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();

  }
  
  public function get_spp_payment_submitted_list($limit, $offset, $param = '') {
    $where = '';
    if ($param != '') {
      $keyword = $param;
      $where = "WHERE app_no ILIKE '%{$keyword}%' OR bpv_no ILIKE '%{$keyword}%'";
    }

    $sql = "SELECT pay.spp_pay_id, app_no, bpv_no, to_char(payment_date, 'DD/MM/YYYY') as payment_date, pay.remark, count(spp_pay_detail_id) AS total_link, 
            sum(bhp) as total_bhp, file_name as doc, f_get_user(pay.created_by) as created_by, pay.created_on, doc_type
            FROM t_trx_isr_bts_spp_pay pay
            LEFT JOIN t_trx_isr_bts_spp_pay_detail pay_det ON pay.spp_pay_id = pay_det.spp_pay_id
            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin_det.link_id = pay_det.link_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = pay.app_id
            LEFT JOIN t_trx_isr_bts_doc docs ON docs.doc_id = pay.doc_id
            {$where}
            GROUP BY pay.spp_pay_id, app_no, bpv_no, payment_date, pay.remark, file_name, pay.created_by, pay.created_on, doc_type
            ORDER BY pay.spp_pay_id DESC
            LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(spp_pay_id) AS count
            FROM t_trx_isr_bts_spp_pay pay
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = pay.app_id
            {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }
  
  public function get_spp_payment_submitted_detail_list($param=0) {
     $sql = "SELECT link.*, bhp, lookup_desc AS transmitter_polarization_desc 
            FROM t_trx_isr_bts_spp_pay_detail pay_det
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = pay_det.link_id
            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin_det.link_id = pay_det.link_id
            LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = link.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
            WHERE spp_pay_id = {$param} ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }

}

?>
