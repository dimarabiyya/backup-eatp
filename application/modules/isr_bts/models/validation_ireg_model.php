<?php

/**
 * This model provide data for Validation at ISR BTS
 *
 * @author tumbas
 */
class Validation_ireg_model extends CI_Model {

  var $region = '';
  private $_user_now;
  private $_date_now;

  public function __construct() {
    parent::__construct();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
    $user_region = $this->session->userdata('user_region');
    foreach ($user_region as $key) {
      $region[] = $key['region_id'];
      $this->region = implode(',', $region);
    }
  }

  function get_validation_ireg_list($filter, $limit, $offset, $warning_day) {
    $where = "";
    if ($filter != '') {
      $q = $filter;
      $or_arr = array('app_no', 'license_number', 'CAST(app_issued AS VARCHAR)', 'CAST(app_expired AS VARCHAR)', 'doc', 'app.remark');
      foreach ($or_arr as $val) {
        if ($where == "")
          $where.="AND (lower($val) ilike '%$q%'";
        else
          $where.=" or lower($val) ilike '%$q%'";
      }
      $where.=")";
    }

    $sql = "SELECT	COUNT (DISTINCT (app.app_id,app.app_no,app.license_number,app.app_issued,app.app_expired,app.doc,fin.spp_due_date)) AS total
            FROM t_trx_isr_bts_app app
            JOIN t_trx_isr_bts_app_link link ON link.app_id = app.app_id
            LEFT JOIN t_trx_isr_bts_spp_fin fin ON fin.app_id = app.app_id
            WHERE app_expired > CURRENT_DATE AND \"isComplete\" = 1 AND link.status_active = 1 AND link.milestone_status NOT IN (1341, 1641) --AND (f_get_regionidbysitecode (link.site_id) IN ($this->region))
            {$where}";
    $result['total'] = $this->db->query($sql)->row()->total;

    $sql = "SELECT DISTINCT app.app_id, app.app_no, app.license_number, to_char(app.app_issued, 'DD/MM/YYYY') AS app_issued, app.app_expired, 
            app.doc, fin.spp_due_date, (SELECT COUNT (sub.link_id) FROM	t_trx_isr_bts_app_link sub WHERE sub.app_id = app.app_id AND sub.milestone_status NOT IN (1341, 1641)) AS total_link,	
            f_get_unprocess_milestone (app.app_id) AS status, \"isComplete\" 
            FROM t_trx_isr_bts_app app
            JOIN t_trx_isr_bts_app_link link ON link.app_id = app.app_id
            LEFT JOIN t_trx_isr_bts_spp_fin fin ON fin.app_id = app.app_id
            WHERE app_expired > CURRENT_DATE AND \"isComplete\" = 1 AND link.status_active = 1 AND link.milestone_status NOT IN (1341, 1641) --AND (f_get_regionidbysitecode (link.site_id) IN ($this->region))
            {$where}
            ORDER BY app.app_id DESC
            LIMIT {$limit} OFFSET {$offset}";
    $result['rows'] = $this->db->query($sql)->result();

    return $result;
  }
  
  public function get_validation_ireg_detail_list($param=0) {
   $sql = "SELECT link.*, lookup_desc AS transmitter_polarization_desc, f_get_sitecode(link.site_id) AS site_code, 
            f_get_regionnamebysiteid_nocluster(link.site_id) AS region, f_get_status_name(link.milestone_status) as milestone_status
            FROM t_trx_isr_bts_app_link link 
            LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = link.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
            WHERE app_id = {$param} ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }
  
  public function get_validation_ireg_header($param=0) {
     $sql = "SELECT app_id, app_no, to_char(app_issued, 'DD Mon YYYY') AS app_issued,  to_char(app_expired, 'DD Mon YYYY') AS app_expired
            FROM t_trx_isr_bts_app
            WHERE app_id = {$param}";
    $query = $this->db->query($sql);
    
    return $query->row();
  }
  
}

?>
