<?php

/**
 * This model provide data for Engineering Approval at ISR BTS
 *
 * @author tumbas
 */
class Spp_finance_model extends CI_Model {

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

  public function __construct() {
    parent::__construct();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function get_spp_finance_new_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status = '" . self::SPP_FIN_NEW . "' ";
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

  public function get_spp_finance_renewal_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status = '" . self::SPP_FIN_RENEWAL . "' ";
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

  public function get_spp_finance_submitted_list($limit, $offset, $param = '') {
    $where = '';
    if ($param != '') {
      $keyword = $param;
      $where = "WHERE app_no ILIKE '%{$keyword}%' OR spp_no ILIKE '%{$keyword}%'";
    }

    $sql = "SELECT distinct fin.spp_fin_id, app_no, spp_no, to_char(spp_start_date, 'DD/MM/YYYY') AS spp_start_date, to_char(spp_due_date, 'DD/MM/YYYY') as spp_due_date, 
            to_char(give_to_fin_date, 'DD/MM/YYYY') as give_to_fin_date, fin.remark, count(spp_fin_detail_id) AS total_link, sum(bhp) as total_bhp, 
            file_name as doc, doc_type, f_get_user(fin.created_by) as created_by, fin.created_on 
            FROM t_trx_isr_bts_spp_fin fin
            LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin.spp_fin_id = fin_det.spp_fin_id
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = fin.app_id
            LEFT JOIN t_trx_isr_bts_doc docs ON docs.doc_id = fin.doc_id
            {$where}
            GROUP BY fin.spp_fin_id, app_no, spp_no, spp_start_date, spp_due_date, give_to_fin_date, fin.remark, file_name, doc_type, fin.created_by, fin.created_on
            ORDER BY fin.spp_fin_id DESC
            LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(spp_fin_id ) AS count
            FROM t_trx_isr_bts_spp_fin fin
            LEFT JOIN t_trx_isr_bts_app app ON app.app_id = fin.app_id
            {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }

  public function get_spp_finance_submitted_detail_list($param = 0) {
    $sql = "SELECT link.*, bhp, lookup_desc AS transmitter_polarization_desc FROM t_trx_isr_bts_spp_fin_detail fin_det
            LEFT JOIN t_trx_isr_bts_app_link link ON link.app_link_id = fin_det.link_id
            LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = link.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
            WHERE spp_fin_id = {$param} ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }

  public function get_spp_finance_detail_list($resi_id = '') {
    $sql = "SELECT bts.*, polar.lookup_desc AS transmitter_polarization_desc, f_get_sitecode(bts.site_id) AS site_code, 
        f_get_regionnamebysiteid_nocluster(bts.site_id) AS region
        FROM t_trx_isr_bts_app_resi_link bts
        LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
        WHERE app_resi_id = '{$resi_id}'
      ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }

  public function get_spp_finance_detail_list2($app_id = '') {
    $sql = "SELECT bts.*, polar.lookup_desc AS transmitter_polarization_desc, f_get_sitecode(bts.site_id) AS site_code, 
        f_get_regionnamebysiteid_nocluster(bts.site_id) AS region, 0 AS bhp
        FROM t_trx_isr_bts_app_link bts
        LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
        LEFT JOIN t_trx_isr_bts_app app ON app.app_id = bts.app_id
        --LEFT JOIN t_trx_isr_bts_spp_fin fin ON fin.app_id = app.ref_doc_id
       -- LEFT JOIN t_trx_isr_bts_spp_fin_detail fin_det ON fin_det.spp_fin_id = fin.spp_fin_id
        WHERE bts.app_id = '{$app_id}'
      ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }

  public function get_last_bhp($param = 0) {
    $sql = "SELECT bhp FROM t_trx_isr_bts_spp_fin_detail fin_det
          JOIN t_trx_isr_bts_spp_fin fin ON fin.spp_fin_id = fin_det.spp_fin_id
          WHERE app_id = {$param} ORDER BY spp_fin_detail_id ASC";
          
    return $this->db->query($sql)->result();
  }

  public function spp_finance_new_header($app_id = 0) {
    $sql = "SELECT app.app_resi_id, app.app_no, app.app_issued, app.doc, app.remark, app.\"isUsed\", near_end_v_id, far_end_v_id, f_get_user(app.created_by) AS created_by,
                to_char(resp.issued_date, 'DD Mon YYYY HH24:MI:SS') AS created_on, (select count(*) from t_trx_isr_bts_app_resi_link where app_resi_id=app.app_resi_id) as count, 
                COALESCE(resp.concession_number, ' - ') AS concession_number, resp.app_index, COALESCE(resp.app_ref, ' - ') AS app_ref, COALESCE(resp.batch_no::VARCHAR, ' - ') AS batch_no,
                COALESCE(resp.license_number, ' - ') AS license_number
                FROM t_trx_isr_bts_app_resi app
                LEFT JOIN t_trx_isr_bts_spp_finance_upload_excel_new_header upload_header ON upload_header.app_id = app.app_resi_id
                LEFT JOIN (
                        SELECT *, resp_detail.created_on AS issued_date
                        FROM t_trx_isr_bts_spp_finance_response_new_detail resp_detail
                        JOIN t_trx_isr_bts_spp_finance_response_new_header resp_header ON resp_header.isr_response_header_new_id = resp_detail.isr_response_header_new_id
                ) AS resp ON resp.spp_finance_log_new_id = upload_header.spp_finance_log_new_id
                WHERE app.app_resi_id={$app_id} ORDER BY upload_header.isr_upload_header_new_id DESC LIMIT 1";
    $query = $this->db->query($sql);

    return $query->row();
  }

  function spp_finance_new_detail_history($app_id) {
    $sql = "SELECT resp_header.response_code, resp_header.response_message, f_get_user(resp_header.created_by) AS created_by, to_char(resp_header.created_on, 'DD Mon YYYY HH24:MI:SS') AS created_on
                FROM t_trx_isr_bts_app_resi app
                JOIN t_trx_isr_bts_spp_finance_upload_excel_new_header upload_header ON upload_header.app_id = app.app_resi_id
                JOIN t_trx_isr_bts_spp_finance_response_new_header resp_header  ON resp_header.spp_finance_log_new_id = upload_header.spp_finance_log_new_id
                WHERE app.app_resi_id={$app_id} ORDER BY upload_header.isr_upload_header_new_id DESC";
    $query = $this->db->query($sql);

    return $query->result();
  }

  //Get information resi by app_id, andedi 2015-05-27
  public function get_response_by_resi($app_id = 0) {
    $sql = "SELECT app.app_resi_id, app.app_no AS resi_no, app.app_issued AS resi_issued, COALESCE(resp.concession_number, ' - ') AS concession_number, resp.app_index, resp.app_ref AS app_no, COALESCE(resp.batch_no::VARCHAR, ' - ') AS batch_no,
                COALESCE(resp.license_number, ' - ') AS license_number
                FROM t_trx_isr_bts_app_resi app
                LEFT JOIN t_trx_isr_bts_m2m_upload_excel_new_header upload_header ON upload_header.app_id = app.app_resi_id
                LEFT JOIN (
                        SELECT *
                        FROM t_trx_isr_bts_m2m_response_new_detail resp_detail
                        JOIN t_trx_isr_bts_m2m_response_new_header resp_header ON resp_header.isr_response_header_new_id = resp_detail.isr_response_header_new_id
                ) AS resp ON resp.m2m_log_new_id = upload_header.m2m_log_new_id
                WHERE app.app_resi_id={$app_id} ORDER BY upload_header.isr_upload_header_new_id DESC LIMIT 1";
    $query = $this->db->query($sql);

    return $query->row();
  }

  /**
   * 
   * Get parent/previous application info
   */
  function get_previous_application($app_id) {
    $sql = "SELECT app.app_id, app2.app_id AS app_id_2, app.app_no, app2.license_number, app2.app_expired
      FROM t_trx_isr_bts_app app 
      JOIN t_trx_isr_bts_app app2 ON app2.app_id = app.ref_doc_id WHERE app.app_id = {$app_id}";
    $query = $this->db->query($sql);

    return $query->row();
  }

}

?>
