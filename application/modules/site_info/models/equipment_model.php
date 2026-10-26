<?php

class Equipment_model extends CI_Model {

  protected $_user_id;
  protected $_date_now;

  public function __construct() {
    parent::__construct();
    $this->_user_id = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function load_equipment_list() {
    $per_page = $this->input->get('per_page');
    $offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
    $search = $this->input->get('query');
    $order = $this->input->get('order');
    $sort = $this->input->get('sort');

    $where = " e.active = 1";
    $where .= " AND (UPPER(s.site_code) LIKE '%" . strtoupper($search) . "%'";
    $where .= " OR UPPER(s.site_name) LIKE '%" . strtoupper($search) . "%'";
    $where .= " OR UPPER(s.vendor_id) LIKE '%" . strtoupper($search) . "%'";
    $where .= " OR UPPER(s.vendor_name) LIKE '%" . strtoupper($search) . "%')";

    /* $where .= " OR UPPER(f.lookup_desc) LIKE '%".strtoupper($search)."%'";
      $where .= " OR UPPER(m.morphology_name) LIKE '%".strtoupper($search)."%'";
      $where .= " OR UPPER(b.bts_mode_name) LIKE '%".strtoupper($search)."%'";
      $where .= " OR UPPER(br.brand_name) LIKE '%".strtoupper($search)."%'";
      $where .= " OR UPPER(t.bts_type_name) LIKE '%".strtoupper($search)."%')";
      $sql = "
      SELECT e.equipment_id, s.site_code, s.site_name, f.lookup_desc AS site_profile_name, m.morphology_name, b.bts_mode_name, br.brand_name, t.bts_type_name
      FROM t_mtr_site_l_equipment e
      LEFT JOIN t_mtr_site s ON s.site_id = e.site_id
      LEFT JOIN t_mtr_lookup f ON CAST(f.lookup_value AS INTEGER) = e.site_profile_id AND f.lookup_key = 'site_profile'
      LEFT JOIN t_mtr_morphology m ON m.morphology_id = e.morphology
      LEFT JOIN t_mtr_bts_mode b ON b.bts_mode_id = e.bts_mode
      LEFT JOIN t_mtr_bts_brand br ON br.brand_id = e.bts_brand
      LEFT JOIN t_mtr_bts_type t ON t.bts_type_id = e.bts_type
      WHERE {$where}
      ";
     */
    $sql = "
			SELECT e.equipment_id, s.site_code, s.site_name, s.vendor_id, s.vendor_name
			FROM t_mtr_site_l_equipment e
			LEFT JOIN t_mtr_site s ON s.site_id = e.site_id
			WHERE {$where}			
		";
    $query = $this->db->query($sql);

    $total_rows = $query->num_rows();

    if ($order) {
      $sql .= " ORDER BY {$order} {$sort}";
    } else {
      $sql .= " ORDER BY equipment_id DESC";
    }

    $sql .= " LIMIT {$per_page} OFFSET {$offset}";
    $query = $this->db->query($sql);

    $this->load->library('pagination');

    $config['base_url'] = site_url('/site_info/equipment/index');
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;
    $config['uri_segment'] = 4;

    $config['full_tag_open'] = '<ul>';
    $config['full_tag_close'] = '</ul>';

    $config['cur_tag_open'] = '<li class="active"><a href="">';
    $config['cur_tag_close'] = '</a></li>';

    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';

    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';

    $config['next_link'] = 'Next &rsaquo;';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';

    $config['prev_link'] = '&lsaquo; Prev';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';

    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    $total_rows_perpage = $query->num_rows();

    $data['total_rows'] = $total_rows;
    $data['rec_info'] = 'Records from ' . ($total_rows > 0 ? ($offset + 1) : 0) . ' to ' . ($offset + $total_rows_perpage) . ' of ' . $total_rows;

    $results = $query->result_array();
    $rows = array();
    foreach ($results as $r) {
      $r['equipment_id'] = $this->core->encode($r['equipment_id']);
      $rows[] = $r;
    }

    $data['data'] = $rows;

    $data['pagination'] = $this->pagination->create_links();
    $data['sql'] = str_replace("\t", ' ', str_replace("\n", '', $sql));

    return $data;
  }

  public function get_equipment($equipment_id) {
    /* return $this->db
      ->select('t_mtr_site.site_code, t_mtr_site.site_name, t_mtr_lookup.lookup_desc AS site_profile_name, t_mtr_morphology.morphology_name')
      ->select('t_mtr_bts_mode.bts_mode_name, t_mtr_bts_brand.brand_name, t_mtr_bts_type.bts_type_name, t_mtr_site_l_equipment.bts_capacity')
      ->select('t_mtr_site_l_equipment.bts_capacity_uom, t_mtr_site_l_equipment.equipment_id, t_mtr_site_l_equipment.site_id')
      ->select('t_mtr_site_l_equipment.battery_brand, t_mtr_site_l_equipment.battery_capacity, t_mtr_site_l_equipment.battery_total_bank, t_mtr_site_l_equipment.battery_total_shunt')
      ->join('t_mtr_site', 't_mtr_site.site_id = t_mtr_site_l_equipment.site_id', 'left')
      ->join('t_mtr_lookup', 't_mtr_lookup.lookup_value = t_mtr_site_l_equipment.site_profile_id', 'left')
      ->join('t_mtr_morphology', 't_mtr_morphology.morphology_id = t_mtr_site_l_equipment.morphology', 'left')
      ->join('t_mtr_bts_mode', 't_mtr_bts_mode.bts_mode_id = t_mtr_site_l_equipment.bts_mode', 'left')
      ->join('t_mtr_bts_brand', 't_mtr_bts_brand.brand_id = t_mtr_site_l_equipment.bts_brand', 'left')
      ->join('t_mtr_bts_type', 't_mtr_bts_type.bts_type_id = t_mtr_site_l_equipment.bts_type', 'left')
      ->where('t_mtr_site_l_equipment.equipment_id', $equipment_id)
      ->where('t_mtr_site_l_equipment.active', 1)
      ->where('t_mtr_lookup.lookup_key', 'site_profile')
      ->get('t_mtr_site_l_equipment')
      ->row_array(); */

    return $this->db
                    ->select('e.equipment_id, s.site_id, s.site_code, s.site_name, s.vendor_id, s.vendor_name')
                    ->join('t_mtr_site s', 's.site_id = e.site_id', 'left')
                    //->where('e.equipment_id', $equipment_id)
                    ->where('e.site_id', $equipment_id)
                    ->where('e.active', 1)
                    ->get('t_mtr_site_l_equipment e')
                    ->row_array();
  }

  public function load_mw_list() {
    $equipment_id = $this->input->get('equipment_id');
    $near_end = $this->input->get('site_id_ne');
    $per_page = $this->input->get('per_page');
    $offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
    $search = $this->input->get('query');
    $order = $this->input->get('order');
    $sort = $this->input->get('sort');

    $where = " mw.active = 1 AND mw.equipment_id = {$equipment_id} AND mw.site_id_ne = {$near_end}";
    $where .= " AND (UPPER(mw.link_code_id) LIKE '%" . strtoupper($search) . "%'";
    $where .= " OR UPPER(s1.site_code) LIKE '%" . strtoupper($search) . "%'";
    $where .= " OR UPPER(s2.site_code) LIKE '%" . strtoupper($search) . "%'";
    $where .= " OR UPPER(mw.link_code_id) LIKE '%" . strtoupper($search) . "%'";
    $where .= " OR UPPER(mw.ant_model) LIKE '%" . strtoupper($search) . "%')";
    $sql = "
			SELECT s1.site_code AS near_end, s2.site_code AS far_end, mw.link_code_id, mw.ant_model, mw.frequency, mw.capacity
			FROM t_mtr_site_l_equipment_mw mw
			LEFT JOIN t_mtr_site s1 ON s1.site_id = mw.site_id_ne
			LEFT JOIN t_mtr_site s2 ON s2.site_id = mw.site_id_fe
			WHERE {$where}
		";
    $query = $this->db->query($sql);

    $total_rows = $query->num_rows();

    if ($order) {
      $sql .= " ORDER BY {$order} {$sort}";
    } else {
      $sql .= " ORDER BY equipment_mw_id DESC";
    }

    $sql .= " LIMIT {$per_page} OFFSET {$offset}";
    $query = $this->db->query($sql);

    $this->load->library('pagination');

    $config['base_url'] = site_url('/site_info/equipment/index');
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;
    $config['uri_segment'] = 4;

    $config['full_tag_open'] = '<ul>';
    $config['full_tag_close'] = '</ul>';

    $config['cur_tag_open'] = '<li class="active"><a href="">';
    $config['cur_tag_close'] = '</a></li>';

    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';

    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';

    $config['next_link'] = 'Next &rsaquo;';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';

    $config['prev_link'] = '&lsaquo; Prev';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';

    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    $total_rows_perpage = $query->num_rows();

    $data['total_rows'] = $total_rows;
    $data['rec_info'] = 'Records from ' . ($total_rows > 0 ? ($offset + 1) : 0) . ' to ' . ($offset + $total_rows_perpage) . ' of ' . $total_rows;
    $data['data'] = $query->result_array();
    $data['pagination'] = $this->pagination->create_links();
    $data['sql'] = str_replace("\t", ' ', str_replace("\n", '', $sql));

    return $data;
  }

  public function load_rf_list() {
    $equipment_id = $this->input->get('equipment_id');
    $per_page = $this->input->get('per_page');
    $offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
    $search = $this->input->get('query');
    $order = $this->input->get('order');
    $sort = $this->input->get('sort');

    $where = " rf.active = 1 AND rf.equipment_id = {$equipment_id}";
    $where .= " AND (UPPER(rf.cell) LIKE '%" . strtoupper($search) . "%'";
    $where .= " OR UPPER(lo2.lookup_desc) LIKE '%" . strtoupper($search) . "%')";
    $sql = "
			SELECT lo1.lookup_desc AS cell, rf.frequency, lo2.lookup_desc AS ant_model, rf.azimuth, rf.electrical_tilt, rf.mechanical_tilt
			FROM t_mtr_site_l_equipment_bts rf
			LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = rf.cell AND lo1.lookup_key = 'antenna_cell'
			LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = rf.ant_model AND lo2.lookup_key = 'antenna_band'
			WHERE {$where}
		";
    $query = $this->db->query($sql);

    $total_rows = $query->num_rows();

    if ($order) {
      $sql .= " ORDER BY {$order} {$sort}";
    } else {
      $sql .= " ORDER BY equipment_bts_id DESC";
    }

    $sql .= " LIMIT {$per_page} OFFSET {$offset}";
    $query = $this->db->query($sql);

    $this->load->library('pagination');

    $config['base_url'] = site_url('/site_info/equipment/index');
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;
    $config['uri_segment'] = 4;

    $config['full_tag_open'] = '<ul>';
    $config['full_tag_close'] = '</ul>';

    $config['cur_tag_open'] = '<li class="active"><a href="">';
    $config['cur_tag_close'] = '</a></li>';

    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';

    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';

    $config['next_link'] = 'Next &rsaquo;';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';

    $config['prev_link'] = '&lsaquo; Prev';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';

    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    $total_rows_perpage = $query->num_rows();

    $data['total_rows'] = $total_rows;
    $data['rec_info'] = 'Records from ' . ($total_rows > 0 ? ($offset + 1) : 0) . ' to ' . ($offset + $total_rows_perpage) . ' of ' . $total_rows;
    $data['data'] = $query->result_array();
    $data['pagination'] = $this->pagination->create_links();
    $data['sql'] = str_replace("\t", ' ', str_replace("\n", '', $sql));

    return $data;
  }

  public function get_equipment_mw($site_id) {
    return $this->db
                    ->query("
				SELECT tran.*, site.site_code
				FROM t_mtr_site_l_equipment_mw tran
				LEFT JOIN t_mtr_site site ON site.site_id = tran.site_id_fe
				WHERE tran.site_id_ne = {$site_id}
				ORDER BY equipment_mw_id DESC
			")
                    ->result_array();
  }

  public function get_equipment_rf($site_id) {
    return $this->db
                    ->query("
				SELECT
					rf.equipment_rf_id, rf.\"type\", brand.antenna_id AS model_id, brand.model, brand.brand, rf.cell, rf.ant_model, brand.hbwd, brand.vbwd,
					brand.dimension_long, brand.dimension_width, brand.dimension_height, brand.weight, rf.height, rf.azimuth, rf.electrical_tilt, rf.mechanical_tilt
				FROM t_mtr_site_l_equipment_rf rf
				LEFT JOIN t_mtr_site_l_equipment_rf_antenna_brand brand ON brand.antenna_id = rf.model
				LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = rf.cell::VARCHAR AND lo1.active = 1 AND lo1.lookup_key = 'antenna_cell'
				WHERE rf.site_id = {$site_id} AND rf.active = 1
				ORDER BY lo1.order ASC
			")
                    ->result_array();
  }

  public function get_equipment_carrier($site_id) {
    return $this->db
                    ->query("
				SELECT carr.\"system\", carr.\"configuration\"
				FROM t_mtr_site_l_equipment_carrier carr
				LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = carr.\"system\"::VARCHAR AND lo1.active = 1 AND lo1.lookup_key = 'carrier_system'
				WHERE carr.site_id = {$site_id} AND carr.active = 1
				ORDER BY lo1.\"order\" ASC
			")
                    ->result_array();
  }

  public function get_equipment_gps($site_id) {
    return $this->db
                    ->where('site_id', $site_id)
                    ->get('t_mtr_site_l_equipment_gps')
                    ->result_array();
  }

  public function get_bts_info($param) {
    if (is_array($param)) {
      $this->db->where($param);
    } else {
      $this->db->where('bts_id', $param);
    }
    $this->db->order_by('bts_id', 'asc');
    $query = $this->db->get('t_mtr_site_l_equipment_btsinfo');

    return $query->row();
  }

  public function get_bts_info_full($param) {
    $sql = "SELECT tdd.bts_id, tdd.tdd_no, tdd.freq, tdd.\"type\" AS tdd_type, sect.sector_type, sect.tilt 
            FROM t_mtr_site_l_equipment_btsinfo_tdd tdd
            JOIN t_mtr_site_l_equipment_btsinfo_sector sect ON sect.tdd_id = tdd.tdd_id
            WHERE bts_id = {$param}
            ORDER BY sect.tdd_id ASC, sect.sector_id ASC";
    
    return $this->db->query($sql)->result();
  }

  public function get_bts_tdd($param) {
    if (is_array($param)) {
      $this->db->where($param);
    } else {
      $this->db->where('tdd_id', $param);
    }
    $this->db->where('active', 1);
    $this->db->order_by('tdd_id', 'asc');
    $query = $this->db->get('t_mtr_site_l_equipment_btsinfo_tdd');

    return $query->result();
  }

  public function get_bts_sector($param) {
    if (is_array($param)) {
      $this->db->where($param);
    } else {
      $this->db->where('sector_id', $param);
    }
    $this->db->where('active', 1);
    $this->db->order_by('sector_id', 'asc');
    $query = $this->db->get('t_mtr_site_l_equipment_btsinfo_sector');

    return $query->result();
  }

  public function update_bts_info($data, $param) {
    if (is_array($param)) {
      foreach ($param as $value) {
        $this->db->where($value, $data[$value]);
        unset($data[$value]);
      }
    } else {
      $this->db->where('bts_id', $param);
      unset($data[$param]);
    }
    $data['active'] = 1;
    $data['updated_by'] = $this->_user_id;
    $data['updated_on'] = $this->_date_now;

    $this->db->update('t_mtr_site_l_equipment_btsinfo', $data);
  }

}