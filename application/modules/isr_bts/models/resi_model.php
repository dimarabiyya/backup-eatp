<?php

/**
 * This model provide data for Resi at ISR BTS
 *
 * @author tumbas
 */
class Resi_model extends CI_Model {

  private $_user_now;
  private $_date_now;

  public function __construct() {
    parent::__construct();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function get_proposed_new_list($limit, $offset, $param='') {
    $where = "";
    if ($param != '') {
      $where = " WHERE app.app_no ILIKE '%{$param}%' OR CAST(app.app_issued AS VARCHAR) ILIKE '%{$param}%' OR CAST(app.app_expired AS VARCHAR) ILIKE '%{$param}%' OR
        app.doc ILIKE '%{$param}%' OR app.remark ILIKE '%{$param}%'";
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
  
  public function get_resi_detail_list($resi_id = '') {
    $sql = "SELECT bts.*, bts.bandwidth::INT AS bandwidth,  polar.lookup_desc AS transmitter_polarization_desc 
        FROM t_trx_isr_bts_app_resi_link bts
        LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
        WHERE app_resi_id = '{$resi_id}'
      ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }
  
  public function get_bts_list($limit, $offset, $param) {
    $where = "";
    if ($param != '') {
      $where = " WHERE bts.vendor_id ILIKE '%{$param}%'";
    }

    $sql = " SELECT site.site_code, site.site_name, site.site_address, site.latitude, site.longitude, f_get_lokasi_propinsi(site.site_id) AS site_province, f_get_lokasi_kabupaten(site.site_id) AS site_city, 
            f_get_lokasi_kecamatan(site.site_id) AS site_district, bts.*, morp.lookup_desc AS morphology_desc, bmode.lookup_desc AS bts_mode_desc, bbrand.lookup_desc AS bts_brand_desc, 
            btype.lookup_desc AS bts_type_desc, polar.lookup_desc AS transmitter_polarization_desc
            FROM t_mtr_site_l_equipment_btsinfo bts
             LEFT JOIN t_mtr_lookup morp ON morp.lookup_value::INT = bts.morphology AND morp.lookup_key = 'bts_morphology'
             LEFT JOIN t_mtr_lookup bmode ON bmode.lookup_value::INT = bts.bts_mode AND bmode.lookup_key = 'bts_mode'
             LEFT JOIN t_mtr_lookup bbrand ON bbrand.lookup_value::INT = bts.bts_brand AND bbrand.lookup_key = 'bts_brand'
             LEFT JOIN t_mtr_lookup btype ON btype.lookup_value::INT = bts.bts_type AND btype.lookup_key = 'bts_type'
             LEFT JOIN t_mtr_lookup polar ON polar.lookup_value::INT = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
             LEFT JOIN t_mtr_site site ON site.site_id = bts.site_id
            {$where}
            ORDER BY vendor_id ASC
            LIMIT {$limit} OFFSET {$offset}";

    $result['rows'] = $this->db->query($sql)->result();
    
    $sql = "SELECT COUNT (DISTINCT bts_id) AS count
            FROM t_mtr_site_l_equipment_btsinfo bts
            {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;
    
    return $result;
  }


  public function get_proposed_discontinue_list($limit, $offset, $param='') {
    $where = "";
    if ($param != '') {
      $where = " WHERE app.app_no ILIKE '%{$param}%' OR CAST(app.app_issued AS VARCHAR) ILIKE '%{$param}%' OR CAST(app.app_expired AS VARCHAR) ILIKE '%{$param}%' OR
        app.doc ILIKE '%{$param}%' OR app.remark ILIKE '%{$param}%'";
    }

    $sql = " SELECT DISTINCT app.app_id, app.app_no, to_char(app.app_issued, 'DD/MM/YYYY') AS app_issued , app.doc, app.remark, f_get_user(app.created_by) AS created_by, 
            app.created_on, (SELECT COUNT(DISTINCT(app_id, site_id)) FROM t_trx_isr_bts_app_link WHERE app_id=app.app_id) as total_links
            FROM t_trx_isr_bts_app app
            JOIN t_trx_isr_bts_app_link link ON app.app_id=link.app_id
            {$where}
            ORDER BY app.app_id DESC
            LIMIT {$limit} OFFSET {$offset}";

    $result['rows'] = $this->db->query($sql)->result();
    
    $sql = "SELECT COUNT (DISTINCT app.app_id) AS count
                FROM t_trx_isr_bts_app app
                JOIN t_trx_isr_bts_app_link link ON app.app_id=link.app_id
                {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;
    
    return $result;
  }

}

?>
