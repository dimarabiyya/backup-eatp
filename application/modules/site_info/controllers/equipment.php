<?php

class Equipment extends MX_Controller {

  private $_partials = array();
  private $_regions = array();

  public function __construct()
  {
    parent::__construct();

    $this->load->model('equipment_model', 'equipment_model');
    $this->load->model('site_info_model', 'site_info');

    $this->load->library('parser');

    if ($this->session->userdata('user_region')) {
      foreach ($this->session->userdata['user_region'] as $reg) {
        if ($reg['region_id'] != '') {
          $this->_regions[] = $reg['region_id'];
        }
      }
    }

    $uri = $this->uri->segment(1) . '/' . $this->uri->segment(2);
    $this->_partials['menu'] = $this->mmenu->getMenuId($uri);
    $this->load->helper('inflector');
  }

  public function index()
  {
    $this->_check_valid_loggedin();

    $data = $this->_partials;

    if ($this->core->is_ajax())
	{
      if ($this->input->get('cmd') == 'LOAD_DATA')
	  {
        $json = array();

        $data = $this->equipment_model->load_equipment_list();

        $json = $data;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'DELETE')
	  {
        $json = array();
        $id = (int) $this->core->decode($this->input->get('id'));

        // soft delete
        $this->core->update('t_mtr_site_l_equipment', array('active' => 0), array('equipment_id' => $id));

        $json = array('message' => 'Equipment data has been deleted successfully.');

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'LOAD_DATA_MW')
	  {
        $json = array();

        $data = $this->equipment_model->load_mw_list();

        $json = $data;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'LOAD_DATA_RF')
	  {

        $json = array();

        $data = $this->equipment_model->load_rf_list();

        $json = $data;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_FAREND')
	  {

        $far_end = (int) $this->input->get('farend');
        $near_end = (int) $this->input->get('nearend');

        $json = array();

        $data['data'] = $this->core->view_row('t_mtr_site_l_equipment_mw', array('site_id_fe' => $far_end, 'site_id_ne' => $near_end, 'active' => 1));

        $json = $data;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_LINKID')
	  {

        $far_end = (int) $this->input->get('farend');
        $near_end = (int) $this->input->get('nearend');

        $json = array();

        $result = $this->db
                ->query("SELECT s1.vendor_id AS ne, s2.vendor_id AS fe FROM  t_mtr_site s1, t_mtr_site s2 WHERE s1.site_id = {$near_end} AND s2.site_id = {$far_end}")
                ->row_array();

        $link_id = $result ? $result['ne'] . '#' . $result['fe'] . '#01' : '';

        $json = array('link_id' => $link_id);

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_SITE')
	  {
        $json = array();
        $site_id = $this->input->get('site_id');

        if ($site_id)
		{
          $sql = "SELECT site_id, site_code, site_name, vendor_id, vendor_name FROM t_mtr_site WHERE site_id = {$site_id} ORDER BY site_code ASC";
        }
		else
		{
          $sql = "SELECT site_id, site_code, site_name, vendor_id, vendor_name FROM t_mtr_site ORDER BY site_code ASC LIMIT 20";
        }

        $results = $this->db
                ->query($sql)
                ->result_array();

        if ($this->input->get('q'))
		{
          $q = $this->input->get('q');

          $results = $this->db
                  ->query("
							SELECT site_id, site_code, site_name, vendor_id, vendor_name 
							FROM t_mtr_site 
							WHERE UPPER(site_code) LIKE '%" . strtoupper($q) . "%' 
							OR UPPER(site_name) LIKE '%" . strtoupper($q) . "%' 
							OR UPPER(vendor_id) LIKE '%" . strtoupper($q) . "%' 
							OR UPPER(vendor_name) LIKE '%" . strtoupper($q) . "%'
							LIMIT 20
						")
                  ->result_array();
        }

        $json = $results;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_DATAGRID_EQUIPMENT')
	  {
        $json = array();

        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
        $offset = ($page - 1) * $rows;
        $search = $this->db->escape_str($this->input->get('query'));
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 's.updated_on';
        $order = $this->input->get('order') ? $this->input->get('order') : 'DESC';

        $region = array();
        foreach ($this->session->userdata['user_region'] as $reg)
		{
          if ($reg['region_id'] != '')
		  {
            $region[] = $reg['region_id'];
          }
        }

        $where = "
					WHERE (
						s.site_id IN (SELECT DISTINCT site_id FROM t_mtr_site_l_equipment_rf WHERE \"active\" = 1)
						OR s.site_id IN (SELECT DISTINCT site_id_ne FROM t_mtr_site_l_equipment_mw WHERE \"active\" = 1)
						OR s.site_id IN (SELECT DISTINCT site_id FROM t_mtr_site_l_equipment_carrier WHERE \"active\" = 1)
						OR s.site_id IN (SELECT DISTINCT site_id FROM t_mtr_site_l_equipment_gps)
						OR s.site_id IN (SELECT DISTINCT site_id FROM t_mtr_site_l_equipment_btsinfo WHERE \"active\" = 1)
					) AND
				";
        $where .= "(UPPER(s.site_code) LIKE '%" . strtoupper($search) . "%'";
        //$where .= "OR UPPER(s.site_code_old) LIKE '%".strtoupper($search)."%'";
        $where .= "OR UPPER(s.site_name) LIKE '%" . strtoupper($search) . "%'";
        $where .= "OR UPPER(s.vendor_id) LIKE '%" . strtoupper($search) . "%'";
        $where .= "OR UPPER(r.region_name) LIKE '%" . strtoupper($search) . "%')";

        if (count($region) > 0)
		{
          $where .= " AND s.region_id IN (" . implode(', ', $region) . ")";
        }

        $sql = "
					SELECT s.site_id, s.site_code, s.site_code_old, s.site_name, s.vendor_id, r.region_name, s.updated_on
					FROM t_mtr_site s
					LEFT JOIN t_mtr_region r ON r.region_id = s.region_id
					{$where}
					GROUP BY s.site_id, s.site_code, s.site_code_old, s.site_name, s.vendor_id, r.region_name, s.updated_on
					ORDER BY  {$sort} {$order}
				";

        $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

        $total_rows = (int) $query->row()->total;

        $sql .= " LIMIT {$rows} OFFSET {$offset}";

        $query = $this->db->query($sql);

        $json['total'] = $total_rows;

        $rows = array();
        foreach ($query->result_array() as $r)
		{
          $r['site_id'] = $this->core->encode($r['site_id']);
          $rows[] = $r;
        }
        //print_r($rows);
        $json['rows'] = $rows;
        $json['sql'] = str_replace(array("\n", "\t", "\r"), ' ', $sql);

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'DELETE_EQUIPMENT')
	  {
        $json = array();

        $id = (int) $this->core->decode($this->input->get('id'));

        $sql[] = "UPDATE t_mtr_site_l_equipment_btsinfo SET \"active\" = 0 WHERE site_id = {$id}";
        $sql[] = "UPDATE t_mtr_site_l_equipment_carrier SET \"active\" = 0 WHERE site_id = {$id}";
        $sql[] = "UPDATE t_mtr_site_l_equipment_rf SET \"active\" = 0 WHERE site_id = {$id}";
        $sql[] = "UPDATE t_mtr_site_l_equipment_mw SET \"active\" = 0 WHERE site_id_ne = {$id}"; // OR site_id_fe = {$id}";
        $sql[] = "DELETE FROM t_mtr_site_l_equipment_gps WHERE site_id = {$id}";

        foreach ($sql as $query)
		{
          $this->db->query($query);
        }

        $json = array('error' => FALSE, 'message' => 'Equipment data has been deleted successfully.', 'sqls' => $sql);

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_DATAGRID_EQUIPMENT_RF') // bwt read only di siteinfo edit mode
	  {
        $json = array();

        $site_id = $this->input->get('site_id');
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
        $offset = ($page - 1) * $rows;
        $search = $this->db->escape_str($this->input->get('query'));
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'equipment_rf_id';
        $order = $this->input->get('order') ? $this->input->get('order') : 'ASC';

        $where = "WHERE rf.site_id = {$site_id} AND rf.active = 1";
        $where .= " AND (UPPER(lo1.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
        $where .= "OR UPPER(lo2.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
        $where .= "OR UPPER(lo3.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
        $where .= "OR UPPER(lo4.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
        $where .= "OR UPPER(lo5.lookup_desc) LIKE '%" . strtoupper($search) . "%')";

        $sql = "
					SELECT lo1.lookup_desc AS type, lo2.lookup_desc AS model, lo3.lookup_desc AS cell, lo4.lookup_desc AS ant_model, rf.azimuth, rf.electrical_tilt, rf.mechanical_tilt, 
					lo5.lookup_desc AS beamwidth, rf.beamwidth_value, rf.dimension_long, rf.dimension_width, rf.dimension_height, rf.height, rf.weight
					FROM t_mtr_site_l_equipment_rf rf
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(rf.type AS VARCHAR) AND lo1.lookup_key = 'antenna_rf_type' AND lo1.active = 1
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(rf.model AS VARCHAR) AND lo2.lookup_key = 'antenna_rf_model' AND lo2.active = 1
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(rf.cell AS VARCHAR) AND lo3.lookup_key = 'antenna_cell' AND lo3.active = 1
					LEFT JOIN t_mtr_lookup lo4 ON lo4.lookup_value = CAST(rf.ant_model AS VARCHAR) AND lo4.lookup_key = 'antenna_band' AND lo4.active = 1
					LEFT JOIN t_mtr_lookup lo5 ON lo5.lookup_value = CAST(rf.beamwidth AS VARCHAR) AND lo5.lookup_key = 'antenna_rf_beamwidth' AND lo5.active = 1
					{$where}
					ORDER BY  {$sort} {$order}
				";

        $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

        $total_rows = (int) $query->row()->total;

        $sql .= " LIMIT {$rows} OFFSET {$offset}";

        $query = $this->db->query($sql);

        $json['total'] = $total_rows;

        $rows = array();
        foreach ($query->result_array() as $r)
		{
          $rows[] = $r;
        }

        $json['rows'] = $rows;
        $json['sql'] = str_replace(array("\n", "\t"), ' ', $sql);

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_DATAGRID_EQUIPMENT_MW') // bwt read only di siteinfo edit mode
	  {
        $json = array();

        $site_id = $this->input->get('site_id');
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
        $offset = ($page - 1) * $rows;
        $search = $this->db->escape_str($this->input->get('query'));
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'equipment_mw_id';
        $order = $this->input->get('order') ? $this->input->get('order') : 'ASC';

        $where = "WHERE mw.site_id_ne = {$site_id} AND mw.active = 1 AND mw.pair_id = mw.equipment_mw_id";
        $where .= " AND (UPPER(lo1.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
        $where .= "OR UPPER(lo2.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
        $where .= "OR UPPER(lo3.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
        $where .= "OR UPPER(lo4.lookup_desc) LIKE '%" . strtoupper($search) . "%')";

        $sql = "
					SELECT lo1.lookup_desc AS type, mw.ip_address, mw.link_code_id, lo2.lookup_desc AS equipment_model, mw.frequency, mw.capacity, 
					mw.ant_model, mw.bandwidth, lo3.lookup_desc AS protection_config, mw.tx_power, lo4.lookup_desc AS ant_diameter, mw.number_of_channel,
					site1.site_code, site1.vendor_id, site2.site_code AS site_id_fe, site2.vendor_id AS vendor_id_fe
					FROM t_mtr_site_l_equipment_mw mw
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(mw.type AS VARCHAR) AND lo1.lookup_key = 'transmission_info_type' AND lo1.active = 1
					LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(mw.equipment_model AS VARCHAR) AND lo2.lookup_key = 'equipment_model' AND lo2.active = 1
					LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(mw.protection_config AS VARCHAR) AND lo3.lookup_key = 'transmission_info_protection' AND lo3.active = 1
					LEFT JOIN t_mtr_lookup lo4 ON lo4.lookup_value = CAST(mw.ant_diameter AS VARCHAR) AND lo4.lookup_key = 'antenna_diamter' AND lo4.active = 1
					LEFT JOIN t_mtr_site site1 ON site1.site_id = mw.site_id_ne
					LEFT JOIN t_mtr_site site2 ON site2.site_id = mw.site_id_fe
					{$where}
					ORDER BY  {$sort} {$order}
				";

        $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

        $total_rows = (int) $query->row()->total;

        $sql .= " LIMIT {$rows} OFFSET {$offset}";

        $query = $this->db->query($sql);

        $json['total'] = $total_rows;

        $rows = array();
        foreach ($query->result_array() as $r)
		{
          $rows[] = $r;
        }

        $json['rows'] = $rows;
        $json['sql'] = str_replace(array("\n", "\t"), ' ', $sql);

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_DATAGRID_EQUIPMENT_GPS') // bwt read only di siteinfo edit mode
	  {
        $json = array();

        $site_id = $this->input->get('site_id');
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
        $offset = ($page - 1) * $rows;
        $search = $this->db->escape_str($this->input->get('query'));
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'ant_gps_model';
        $order = $this->input->get('order') ? $this->input->get('order') : 'ASC';

        $where = "
					WHERE gps.site_id = {$site_id}
					AND (UPPER(lo1.lookup_desc) LIKE '%" . strtoupper($search) . "%'
					OR CAST(gps.ant_gps_height AS VARCHAR) LIKE '%" . strtoupper($search) . "%')
				";

        $sql = "
					SELECT lo1.lookup_desc AS ant_gps_model, gps.ant_gps_height
					FROM t_mtr_site_l_equipment_gps gps
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(gps.ant_gps_model AS VARCHAR) AND lo1.lookup_key = 'antenna_gps_model' AND lo1.active = 1
					{$where}
					ORDER BY  {$sort} {$order}
				";

        $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

        $total_rows = (int) $query->row()->total;

        $sql .= " LIMIT {$rows} OFFSET {$offset}";

        $query = $this->db->query($sql);

        $json['total'] = $total_rows;

        $rows = array();
        foreach ($query->result_array() as $r) {
          $rows[] = $r;
        }

        $json['rows'] = $rows;
        $json['sql'] = str_replace(array("\n", "\t"), ' ', $sql);

        echo json_encode($json);
        exit;
      }
    }

    if ($this->input->get('cmd') == 'get_lookup_master_antenna_rf')
	{
      $data = array();

      if ($this->input->get('act') == 'get_data')
	  {
        $json = array();

        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
        $offset = ($page - 1) * $rows;
        $search = $this->db->escape_str($this->input->get('query'));
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'updated_on';
        $order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';

        $where = "WHERE (";
        $where .= " UPPER(brand) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR UPPER(model) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR UPPER(band) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR UPPER(hbwd) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR UPPER(vbwd) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR UPPER(etilt) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR CAST(polarization AS VARCHAR) LIKE '%" . $search . "%'";
        $where .= " OR CAST(vswr AS VARCHAR) LIKE '%" . $search . "%'";
        $where .= " OR CAST(impedance AS VARCHAR) LIKE '%" . $search . "%'";
        $where .= " OR UPPER(gain) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR UPPER(max_input_power) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR CAST(isolations AS VARCHAR) LIKE '%" . $search . "%'";
        $where .= " OR CAST(weight AS VARCHAR) LIKE '%" . $search . "%'";
        $where .= " OR CAST(dimension_long AS VARCHAR) LIKE '%" . $search . "%'";
        $where .= " OR CAST(dimension_width AS VARCHAR) LIKE '%" . $search . "%'";
        $where .= " OR CAST(dimension_height AS VARCHAR) LIKE '%" . $search . "%'";
        $where .= ")";

        $sql = "
					SELECT *
					FROM t_mtr_site_l_equipment_rf_antenna_brand
					{$where}
					ORDER BY {$sort} {$order}
				";

        $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

        $total_rows = (int) $query->row()->total;

        $sql .= " LIMIT {$rows} OFFSET {$offset}";

        $query = $this->db->query($sql);

        $json['total'] = $total_rows;
        $json['rows'] = $query->result_array();
        $json['sql'] = $sql;

        echo json_encode($json);
        exit;
      }

      echo $this->parser->parse('partials/lookup_master_antenna_rf', $data);
      exit;
    }

    $data['content_id'] = 'equipment_list_view';

    $this->load->view('template/smashing', $data);
  }

  public function add()
  {
    $this->_check_valid_loggedin();

    if (!$this->mmenu->cekPermission($this->_partials['menu'], 'menu_add'))
	{
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }

    if ($this->core->is_ajax())
	{
      if ($this->input->get('load_content') == 'BTS_INFO')
	  {
        $site_id = $this->input->get('site_id');
        $data['bts_info'] = $this->core->view_all('t_mtr_site_l_equipment_btsinfo', array('site_id' => $site_id));

        echo $this->parser->parse('partials/content_equipment_btsinfo', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'RF')
	  {
        $data = array();

        echo $this->parser->parse('partials/content_equipment_rf', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'TRANSMISSION')
	  {
        $data = array();

        if ($site_id = $this->input->get('site_id'))
		{
          $data['transmission'] = array();

          $results1 = $this->db
                  ->query("
							SELECT mw.equipment_mw_id, mw.site_id_ne, mw.site_id_fe, mw.pair_id, mw.link_code_id, site.site_code, site.vendor_id, mw.ip_address, mw.ant_model, mw.ketinggian, mw.ant_diameter, mw.ant_diameter,
								mw.azimuth, mw.equipment_model, mw.polarization, mw.path_length, mw.tx_frequency, mw.rx_frequency, mw.power, mw.sd_ant_model, mw.sd_ant_height,
								mw.sd_ant_diameter, mw.req_capacity, mw.inst_capacity, mw.used_capacity, mw.treshold, mw.plan_rsl, mw.actual_rsl, mw.fm, mw.availability
							FROM t_mtr_site_l_equipment_mw mw
							LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_ne
							WHERE mw.site_id_ne = {$site_id} AND mw.active = 1
							ORDER BY mw.site_id_fe, mw.pair_id ASC
						")
                  ->result_array();

          $a = array();
          foreach ($results1 as $res1)
		  {
            $res2 = $this->db
                    ->query("
								SELECT mw.equipment_mw_id, mw.site_id_ne, mw.site_id_fe, mw.pair_id, mw.link_code_id, site.site_code, site.vendor_id, mw.ip_address, mw.ant_model, mw.ketinggian, mw.ant_diameter, mw.ant_diameter,
									mw.azimuth, mw.equipment_model, mw.polarization, mw.path_length, mw.tx_frequency, mw.rx_frequency, mw.power, mw.sd_ant_model, mw.sd_ant_height,
									mw.sd_ant_diameter, mw.req_capacity, mw.inst_capacity, mw.used_capacity, mw.treshold, mw.plan_rsl, mw.actual_rsl, mw.fm, mw.availability
								FROM t_mtr_site_l_equipment_mw mw
								LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_ne
								WHERE mw.site_id_ne = " . $res1['site_id_fe'] . " AND mw.pair_id = " . $res1['pair_id'] . " AND mw.active = 1
								ORDER BY mw.site_id_fe, mw.pair_id ASC
							")
                    ->row_array();

            $a[] = array('ne' => $res1, 'fe' => $res2);
          }

          $data['site_class_transm'] = $this->db->query("SELECT site_class_transm FROM t_mtr_site WHERE site_id = {$site_id}")->row_array();
          $data['transmission'] = $a;
        }

        echo $this->parser->parse('partials/content_equipment_transmission', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'GPS')
	  {
        $data = array();

        echo $this->parser->parse('partials/content_equipment_gps', $data);
        exit;
      }

      if ($this->input->post('cmd') == 'ADD_BTS_INFO')
	  {

        $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($this->_is_field_empty($post['siteId']))
		{
          $error[] = 'Please determine Site.';
        }
        if ($this->core->view_row('t_mtr_site_l_equipment_btsinfo', array('site_id' => (int) $post['siteId'])))
		{
          $error[] = 'Site ID is already in use. Please Choose another Site ID';
        }
        if ($this->_is_field_empty($post['morphology']))
		{
          $error[] = 'Morphology can not unselected.';
        }
        if ($this->_is_field_empty($post['vendorId']))
		{
          $error[] = 'Vendor ID can not empty.';
        }
        if ($this->_is_field_empty($post['mode']))
		{
          $error[] = 'BTS Mode can not unselected.';
        }
        if ($this->_is_field_empty($post['brand'])) {
          $error[] = 'BTS Brand can not unselected.';
        }
        if ($this->_is_field_empty($post['type']))
		{
          $error[] = 'BTS Type can not unselected.';
        }

        $vendor_ids = array();
        for ($i = (count($post['vendorId']) - 1); $i >= 0; $i--)
		{
          $vendor_ids[] = $post['vendorId'][$i];
        }

        $vid = array();
        $onair = '';
        foreach ($vendor_ids as $val)
		{
          $sql = "SELECT vendor_id, onair_offair FROM v_site_info_master_site WHERE site_code = '" . $post['siteCode'] . "' AND vendor_id LIKE '%{$val}%' AND lower(status) = 'active' AND lower(onair_offair) = 'on air'";
          $rows = $this->db->query($sql)->result_array();
          foreach ($rows as $r)
		  {
            $vendor_ids_2 = explode(';', $r['vendor_id']);
            foreach ($vendor_ids_2 as $val2)
			{
              if (trim($val) == trim($val2))
			  {
                $vid[] = $val;
              }
            }
            $onair = $r['onair_offair'];
          }
        }

        if (count($vid) > 0)
		{
          $error[] = 'Vendor ID ' . implode(', ', $vid) . ' is already ' . $onair;
        }

        if (count($error) == 0)
		{
          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s');

          $this->core->db_debug = TRUE;

          $this->db->update('t_mtr_site_l_equipment_btsinfo', array('active' => 0), array('site_id' => $post['siteId']));

          if (@is_array($post['btsId']))
		  {
            foreach ($post['btsId'] as $idx => $val)
			{
              if ($this->core->view_row('t_mtr_site_l_equipment_btsinfo', array('bts_id' => (int) $post['btsId'][$idx])))
			  {
                $this->core->set['site_id'] = $post['siteId'];
                $this->core->set['morphology'] = $post['morphology'];
                $this->core->set['bts_mode'] = $post['mode'][$idx];
                $this->core->set['bts_brand'] = $post['brand'][$idx];
                $this->core->set['bts_type'] = $post['type'][$idx];
                $this->core->set['updated_on'] = $updated_on;
                $this->core->set['updated_by'] = $updated_by;
                $this->core->set['active'] = 1;
                $this->core->set['vendor_id'] = $post['vendorId'][$idx];
                $this->core->set['height_asl'] = null_empty($post['height_asl'][$idx]);
                $this->core->set['radius_of_operation'] = null_empty($post['radius_of_operation'][$idx]);
                $this->core->set['equipment_type'] = null_empty($post['equipment_type'][$idx]);
                $this->core->set['equipment_output_power'] = null_empty($post['equipment_output_power'][$idx]);
                $this->core->set['bandwidth'] = null_empty($post['bandwidth'][$idx]);
                $this->core->set['tx_freq'] = null_empty($post['tx_freq'][$idx]);
                $this->core->set['rx_freq'] = null_empty($post['rx_freq'][$idx]);
                $this->core->set['antenna_type'] = null_empty($post['antenna_type'][$idx]);
                $this->core->set['feeding_loss'] = null_empty($post['feeding_loss'][$idx]);
                $this->core->set['connector_loss'] = null_empty($post['connector_loss'][$idx]);
                $this->core->set['branch_loss'] = null_empty($post['branch_loss'][$idx]);
                $this->core->set['antenna_height_agl'] = null_empty($post['antenna_height_agl'][$idx]);
                $this->core->set['transmitter_polarization'] = $post['transmitter_polarization'][$idx];

                $this->core->save('t_mtr_site_l_equipment_btsinfo', array('bts_id' => (int) $post['btsId'][$idx]));
              }
			  else
			  {
                $this->core->set['site_id'] = $post['siteId'];
                $this->core->set['morphology'] = $post['morphology'];
                $this->core->set['bts_mode'] = $post['mode'][$idx];
                $this->core->set['bts_brand'] = $post['brand'][$idx];
                $this->core->set['bts_type'] = $post['type'][$idx];
                $this->core->set['created_on'] = $created_on;
                $this->core->set['created_by'] = $created_by;
                $this->core->set['updated_on'] = $updated_on;
                $this->core->set['updated_by'] = $updated_by;
                $this->core->set['active'] = 1;
                $this->core->set['vendor_id'] = $post['vendorId'][$idx];
                $this->core->set['height_asl'] = null_empty($post['height_asl'][$idx]);
                $this->core->set['radius_of_operation'] = null_empty($post['radius_of_operation'][$idx]);
                $this->core->set['equipment_type'] = null_empty($post['equipment_type'][$idx]);
                $this->core->set['equipment_output_power'] = null_empty($post['equipment_output_power'][$idx]);
                $this->core->set['bandwidth'] = null_empty($post['bandwidth'][$idx]);
                $this->core->set['tx_freq'] = null_empty($post['tx_freq'][$idx]);
                $this->core->set['rx_freq'] = null_empty($post['rx_freq'][$idx]);
                $this->core->set['antenna_type'] = null_empty($post['antenna_type'][$idx]);
                $this->core->set['feeding_loss'] = null_empty($post['feeding_loss'][$idx]);
                $this->core->set['connector_loss'] = null_empty($post['connector_loss'][$idx]);
                $this->core->set['branch_loss'] = null_empty($post['branch_loss'][$idx]);
                $this->core->set['antenna_height_agl'] = null_empty($post['antenna_height_agl'][$idx]);
                $this->core->set['transmitter_polarization'] = $post['transmitter_polarization'][$idx];

                $this->core->save('t_mtr_site_l_equipment_btsinfo');
              }
            }

            $vendor_ids = array();
            for ($i = (count($post['vendorId']) - 1); $i >= 0; $i--)
			{
              $vendor_ids[] = $post['vendorId'][$i];
            }

            $this->db->update('t_mtr_site', array('vendor_id' => implode(';', $vendor_ids)), array('site_id' => $post['siteId']));
          }

          $json = array('error' => FALSE, 'message' => 'BTS Info has been added successfully.', 'site_id' => $this->core->encode($post['siteId']));
        } else {
          $json = array('error' => TRUE, 'message' => $error);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'ADD_RF')
	  {
        $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($this->_is_field_empty($post['siteId']))
		{
          $error[] = 'Please determine Site.';
        }
        if ($this->core->view_row('t_mtr_site_l_equipment_rf', array('site_id' => (int) $post['siteId'])))
		{
          $error[] = 'Site ID is already in use. Please Choose another Site ID';
        }
        if ($this->_is_field_empty(@$post['system']))
		{
          $error[] = 'System can not unselected.';
        }
        if ($this->_is_field_empty(@$post['config']))
		{
          $error[] = 'Configuration can not unselected.';
        }

        if ($this->_is_field_empty(@$post['typeRF']))
		{
          $error[] = 'Type can not unselected.';
        }
        if ($this->_is_field_empty(@$post['rfModelId']))
		{
          $error[] = 'Model can not unselected.';
        }
        if ($this->_is_field_empty(@$post['cell']))
		{
          $error[] = 'Cell can not unselected.';
        }
        if ($this->_is_field_empty(@$post['rfBand']))
		{
          $error[] = 'Band can not unselected.';
        }
        /*
          if ($this->_is_field_empty(@$post['antenaAzimuth'])) {
          $error[] = 'Azimuth can not empty.';
          }
          if ($this->_is_field_empty(@$post['electricalTilt'])) {
          $error[] = 'Electrical Tilt can not empty.';
          }
          if ($this->_is_field_empty(@$post['mechanicalTilt'])) {
          $error[] = 'Mechanical Tilt can not empty.';
          }
          if ($this->_is_field_empty(@$post['height'])) {
          $error[] = 'Height can not empty.';
          }
          if ($this->_is_field_empty(@$post['verticalBeamwidth'])) {
          $error[] = 'Vertical Beamwidth can not empty.';
          }
          if ($this->_is_field_empty(@$post['horizontalBeamwidth'])) {
          $error[] = 'Horizontal Beamwidth can not empty.';
          }
          if ($this->_is_field_empty(@$post['dimensionLong']) || $this->_is_field_empty(@$post['dimensionWidth']) || $this->_is_field_empty(@$post['dimensionHeight'])) {
          $error[] = 'Dimension can not empty.';
          }
          if ($this->_is_field_empty(@$post['weight'])) {
          $error[] = 'Weight can not empty.';
          } */

        if (count($error) == 0)
		{
          $this->core->db_debug = TRUE;

          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s');

          $this->db->delete('t_mtr_site_l_equipment_carrier', array('site_id' => $post['siteId']));

          if (@is_array($post['system']))
		  {
            foreach ($post['system'] as $idx => $val)
			{
              $this->core->set['system'] = $post['system'][$idx];
              $this->core->set['configuration'] = $post['config'][$idx];
              $this->core->set['active'] = 1;
              $this->core->set['site_id'] = $post['siteId'];
              //$this->core->set['updated_on'] = $updated_on;
              //$this->core->set['updated_by'] = $updated_by;

              $this->core->save('t_mtr_site_l_equipment_carrier');
            }
          }

          // Save Antenna RF
          $this->db->delete('t_mtr_site_l_equipment_rf', array('site_id' => $post['siteId']));

          if (@is_array($post['rfId']))
		  {
            foreach ($post['rfId'] as $rf_key => $rf_val)
			{
              $this->core->set['site_id'] = $post['siteId'];
              $this->core->set['type'] = $post['typeRF'][$rf_key];
              $this->core->set['model'] = $post['rfModelId'][$rf_key];
              $this->core->set['cell'] = $post['cell'][$rf_key];
              //$this->core->set['frequency'] = null_empty($post['antenaFrequency'][$rf_key]);
              $this->core->set['ant_model'] = null_empty($post['rfBand'][$rf_key]);
              $this->core->set['height'] = null_empty($post['height'][$rf_key]);
              $this->core->set['azimuth'] = null_empty($post['antenaAzimuth'][$rf_key]);
              $this->core->set['electrical_tilt'] = null_empty($post['electricalTilt'][$rf_key]);
              $this->core->set['mechanical_tilt'] = null_empty($post['mechanicalTilt'][$rf_key]);
              //$this->core->set['beamwidth'] = null_empty($post['beamwidth'][$rf_key]);
              //$this->core->set['beamwidth_value'] = $post['beamwidthValue'][$rf_key];
              //$this->core->set['vertical_beamwidth'] = $post['verticalBeamwidth'][$rf_key];
              //$this->core->set['horizontal_beamwidth'] = $post['horizontalBeamwidth'][$rf_key];
              //$this->core->set['dimension_long'] = null_empty($post['dimensionLong'][$rf_key]);
              //$this->core->set['dimension_height'] = null_empty($post['dimensionHeight'][$rf_key]);
              //$this->core->set['dimension_width'] = null_empty($post['dimensionWidth'][$rf_key]);
              //$this->core->set['weight'] = null_empty($post['weight'][$rf_key]);
              $this->core->set['active'] = 1;
              $this->core->set['updated_by'] = $updated_by;
              $this->core->set['updated_on'] = $updated_on;
              $this->core->set['created_by'] = $created_by;
              $this->core->set['created_on'] = $created_on;

              $this->core->save('t_mtr_site_l_equipment_rf');
            }
          }

          $json = array('error' => FALSE, 'message' => 'Antenna RF has been added successfully.');
        }
		else
		{
          $json = array('error' => TRUE, 'message' => $error);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'ADD_TRANSMISSION__') {

        $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($post['siteId'] == 0) {
          $error[] = 'Please determine Site.';
        }
        if ($this->core->view_row('t_mtr_site_l_equipment_mw', array('site_id_ne' => (int) $post['siteId']))) {
          $error[] = 'Site ID is already in use. Please Choose another Site ID';
        }

        if (count($error) == 0) {

          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s.u');

          if (@is_array($post['mwId'])) {
            foreach ($post['mwId'] as $mw_key => $mw_val) {
              $this->core->set['type'] = $post['typeTrans'][$mw_key];
              $this->core->set['ip_address'] = $post['ipAddress'][$mw_key];
              $this->core->set['site_id_ne'] = $post['siteId'];
              $this->core->set['site_id_fe'] = null_empty($post['farEnd'][$mw_key]);
              $this->core->set['link_code_id'] = null_empty($post['linkId'][$mw_key]);
              $this->core->set['ant_model'] = null_empty($post['antModel'][$mw_key]);
              $this->core->set['frequency'] = null_empty($post['mwFrequency'][$mw_key]);
              $this->core->set['capacity'] = null_empty($post['mwCapacity'][$mw_key]);
              $this->core->set['equipment_model'] = $post['equipmentModel'][$mw_key];
              $this->core->set['bandwidth'] = $post['bandwidth'][$mw_key];
              $this->core->set['protection_config'] = $post['protectionConfig'][$mw_key];
              $this->core->set['tx_power'] = $post['txPower'][$mw_key];
              $this->core->set['ant_diameter'] = $post['antDiameter'][$mw_key];
              $this->core->set['number_of_channel'] = $post['numberOfChannel'][$mw_key];
              $this->core->set['active'] = 1;
              //$this->core->set['equipment_id'] = $equipment_id;
              // edit old one
              if ($this->core->view_row('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => null_empty($mw_val)))) {

                $this->core->set['updated_by'] = $updated_by;
                $this->core->set['updated_on'] = $updated_on;

                $this->core->save('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => $mw_val));

                // add new one
              } else {
                // #1
                $this->core->set['created_by'] = $created_by;
                $this->core->set['created_on'] = $created_on;

                $this->core->save('t_mtr_site_l_equipment_mw');

                $last_id = $this->db->insert_id();

                $this->core->update('t_mtr_site_l_equipment_mw', array('pair_id' => $last_id), array('equipment_mw_id' => $last_id));

                // #2
                $near_end = $post['siteId'];
                $far_end = (int) null_empty($post['farEnd'][$mw_key]);

                $result = $this->db
                        ->query("SELECT s1.vendor_id AS ne, s2.vendor_id AS fe FROM  t_mtr_site s1, t_mtr_site s2 WHERE s1.site_id = {$near_end} AND s2.site_id = {$far_end}")
                        ->row_array();

                $link_id = $result ? $result['fe'] . '#' . $result['ne'] . '#02' : '';

                $this->core->set['type'] = $post['typeTrans'][$mw_key];
                $this->core->set['ip_address'] = $post['ipAddress'][$mw_key];
                $this->core->set['site_id_ne'] = null_empty($post['farEnd'][$mw_key]);
                $this->core->set['site_id_fe'] = $post['siteId'];
                $this->core->set['link_code_id'] = $link_id;
                $this->core->set['ant_model'] = NULL;
                $this->core->set['frequency'] = NULL;
                $this->core->set['capacity'] = NULL;
                $this->core->set['equipment_model'] = $post['equipmentModel'][$mw_key];
                $this->core->set['bandwidth'] = $post['bandwidth'][$mw_key];
                $this->core->set['protection_config'] = $post['protectionConfig'][$mw_key];
                $this->core->set['tx_power'] = $post['txPower'][$mw_key];
                $this->core->set['ant_diameter'] = $post['antDiameter'][$mw_key];
                $this->core->set['number_of_channel'] = $post['numberOfChannel'][$mw_key];
                $this->core->set['active'] = 1;
                //$this->core->set['equipment_id'] = $equipment_id;
                $this->core->set['created_by'] = $created_by;
                $this->core->set['created_on'] = $created_on;

                $this->core->save('t_mtr_site_l_equipment_mw');

                $last_id2 = $this->db->insert_id();

                $this->core->update('t_mtr_site_l_equipment_mw', array('pair_id' => $last_id), array('equipment_mw_id' => $last_id2));
              }
            }
          }

          $json = array('error' => FALSE, 'message' => 'Transmission has been added successfully.');
        } else {
          $json = array('error' => TRUE, 'message' => $error);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'ADD_TRANSMISSION')
	  {
        $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($this->_is_field_empty($post['siteId']))
		{
          $error[] = 'Please determine Site.';
        }
        if ($this->_is_field_empty($post['siteID1Code']) || $this->_is_field_empty($post['siteID2Code']))
		{
          $error[] = 'Site ID can not empty.';
        }
        if ($this->_is_field_empty($post['pathLength1']) || $this->_is_field_empty($post['pathLength2']))
		{
          $error[] = 'Path Length can not empty.';
        }
        if ($this->_is_field_empty($post['linkID1']) || $this->_is_field_empty($post['linkID2']))
		{
          $error[] = 'Link ID can not empty.';
        }
        if ($this->_is_field_empty($post['linkType1']) || $this->_is_field_empty($post['linkType2']))
		{
          $error[] = 'Link Type can not empty.';
        }
        /*
          if ($this->_is_field_empty($post['txFreq1']) || $this->_is_field_empty($post['txFreq2'])) {
          $error[] = 'TX Frequency can not empty.';
          }
          if ($this->_is_field_empty($post['rxFreq1']) || $this->_is_field_empty($post['rxFreq2'])) {
          $error[] = 'RX Frequency can not empty.';
          }
         */
        if ($this->_is_field_empty($post['freqUsed1']) || $this->_is_field_empty($post['freqUsed2']))
		{
          $error[] = 'Freq. Used can not empty.';
        }
        if ($this->_is_field_empty($post['power1']) || $this->_is_field_empty($post['power2']))
		{
          $error[] = 'Power can not empty.';
        }
        /*
          if ($this->_is_field_empty($post['ipAddress1']) || $this->_is_field_empty($post['ipAddress2'])) {
          $error[] = 'IP Address can not empty.';
          }
         */
        if ($this->_is_field_empty($post['mainAntModel1']) || $this->_is_field_empty($post['mainAntModel2']))
		{
          $error[] = 'Main Ant. Model can not empty.';
        }
        if ($this->_is_field_empty($post['mainAntHeight1']) || $this->_is_field_empty($post['mainAntHeight2']))
		{
          $error[] = 'Main Ant. Height can not empty.';
        }
        if ($this->_is_field_empty($post['mainAntDiameter1']) || $this->_is_field_empty($post['mainAntDiameter2']))
		{
          $error[] = 'Main Ant. Diameter can not unselected.';
        }
        /*
          if ($this->_is_field_empty($post['sdAntModel1']) || $this->_is_field_empty($post['sdAntModel2'])) {
          $error[] = 'SD Ant. Model can not empty.';
          }
          if ($this->_is_field_empty($post['sdAntHeight1']) || $this->_is_field_empty($post['sdAntHeight2'])) {
          $error[] = 'SD Ant. Height can not empty.';
          }
          if ($this->_is_field_empty($post['sdAntDiameter1']) || $this->_is_field_empty($post['sdAntDiameter2'])) {
          $error[] = 'SD Ant. Diameter can not unselected.';
          }
         */
        if ($this->_is_field_empty($post['azimuth1']) || $this->_is_field_empty($post['azimuth2']))
		{
          $error[] = 'Azimuth can not empty.';
        }
        if ($this->_is_field_empty($post['eqModel1']) || $this->_is_field_empty($post['eqModel2']))
		{
          $error[] = 'Eq. Model can not unselected.';
        }
        if ($this->_is_field_empty($post['polarization1']) || $this->_is_field_empty($post['polarization2']))
		{
          $error[] = 'Polarization can not unselected.';
        }
        if ($this->_is_field_empty($post['reqCapacity1']) || $this->_is_field_empty($post['reqCapacity2']))
		{
          $error[] = 'Req. Capacity can not empty.';
        }
        if ($this->_is_field_empty($post['instCapacity1']) || $this->_is_field_empty($post['instCapacity2']))
		{
          $error[] = 'Inst. Capacity can not empty.';
        }
        if ($this->_is_field_empty($post['usedCapacity1']) || $this->_is_field_empty($post['usedCapacity2']))
		{
          $error[] = 'Used Capacity can not empty.';
        }
        if ($this->_is_field_empty($post['treshold1']) || $this->_is_field_empty($post['treshold2']))
		{
          $error[] = 'Treshold can not empty.';
        }
        if ($this->_is_field_empty($post['planRSL1']) || $this->_is_field_empty($post['planRSL2']))
		{
          $error[] = 'Plan RSL can not empty.';
        }
        /*
          if ($this->_is_field_empty($post['actualRSL1']) || $this->_is_field_empty($post['actualRSL2'])) {
          $error[] = 'Actual RSL can not empty.';
          }
         */
        if ($this->_is_field_empty($post['fm1']) || $this->_is_field_empty($post['fm2']))
		{
          $error[] = 'FM can not empty.';
        }
        if ($this->_is_field_empty($post['availability1']) || $this->_is_field_empty($post['availability2']))
		{
          $error[] = 'Availability can not empty.';
        }

        if (count($error) == 0)
		{
          $this->db->trans_begin();

          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s');

          $this->core->db_debug = TRUE;

          $this->core->update('t_mtr_site', array('site_class_transm' => $post['siteClassTransm']), array('site_id' => $post['siteId']));

          $this->core->update('t_mtr_site_l_equipment_mw', array('active' => 0), array('site_id_ne' => $post['siteId']));

          ksort($post['id1']);
          ksort($post['txFreq1']);

          if (@is_array($post['id1']))
		  {
            //echo $abc;
            foreach ($post['id1'] as $idx => $val)
			{
              ksort($post['txFreq1'][$idx]);
              foreach ($post['txFreq1'][$idx] as $idx2 => $val2)
			  {

                $this->db->where(array(
                    'site_id_ne' => $post['siteID1'][$idx],
                    'site_id_fe' => $post['siteID2'][$idx],
                    'tx_frequency' => $post['txFreq1'][$idx][$idx2],
                    'rx_frequency' => $post['rxFreq1'][$idx][$idx2],
                    'polarization' => $post['polarization1'][$idx][$idx2]
                ));

                $query = $this->db->get('t_mtr_site_l_equipment_mw');
                if ($query->row() != NULL)
				{
                  $error[] = 'Tx Frequency, Rx Frequency And Polarization must be different in same link.';
                  break 2;
                }

                // NE
                //$this->core->update('t_mtr_site_l_equipment_mw', array('active' => 0), array('site_id_ne' => (int) $post['id1'][$idx]));
                $data_ne['site_id_ne'] = $post['siteID1'][$idx];
                $data_ne['site_id_fe'] = $post['siteID2'][$idx];
                $data_ne['path_length'] = $post['pathLength1'][$idx];
                $data_ne['link_code_id'] = $post['linkID1'][$idx];
                $data_ne['link_type'] = $post['linkType1'][$idx];
                $data_ne['tx_frequency'] = $post['txFreq1'][$idx][$idx2];
                $data_ne['rx_frequency'] = $post['rxFreq1'][$idx][$idx2];
                $data_ne['frequency_used'] = $post['freqUsed1'][$idx];
                $data_ne['config'] = $post['config1'][$idx];
                $data_ne['power'] = $post['power1'][$idx];
                $data_ne['ip_address'] = $post['ipAddress1'][$idx];
                $data_ne['ant_model'] = $post['mainAntModel1'][$idx];
                $data_ne['ketinggian'] = $post['mainAntHeight1'][$idx];
                $data_ne['ant_diameter'] = $post['mainAntDiameter1'][$idx];
                $data_ne['sd_ant_model'] = null_empty($post['sdAntModel1'][$idx]);
                $data_ne['sd_ant_height'] = null_empty($post['sdAntHeight1'][$idx]);
                $data_ne['sd_ant_diameter'] = null_empty($post['sdAntDiameter1'][$idx]);
                $data_ne['azimuth'] = $post['azimuth1'][$idx];
                $data_ne['equipment_model'] = $post['eqModel1'][$idx];
                $data_ne['polarization'] = $post['polarization1'][$idx][$idx2];
                $data_ne['req_capacity'] = $post['reqCapacity1'][$idx];
                $data_ne['inst_capacity'] = $post['instCapacity1'][$idx];
                $data_ne['used_capacity'] = $post['usedCapacity1'][$idx];
                $data_ne['treshold'] = $post['treshold1'][$idx];
                $data_ne['plan_rsl'] = $post['planRSL1'][$idx];
                $data_ne['actual_rsl'] = $post['actualRSL1'][$idx];
                $data_ne['fm'] = $post['fm1'][$idx];
                $data_ne['availability'] = $post['availability1'][$idx];
                $data_ne['active'] = 1;

                $pair_id = 0;

                // edit old one
                if ($this->core->view_row('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => (int) $post['id1'][$idx], 'pair_id' => (int) $post['pairID'][$idx])))
				{
                  $pair = $this->db->query("SELECT pair_id FROM t_mtr_site_l_equipment_mw WHERE equipment_mw_id = " . (int) $post['id1'][$idx])->row_array();
                  $pair_id = $pair['pair_id'];

                  $data_ne['pair_id'] = $pair_id;

//                  $data_ne['updated_by'] = $updated_by;
//                  $data_ne['updated_on'] = $updated_on;
                  $data_ne['equipment_mw_id'] = $post['id1'][$idx];

                  //$this->core->save('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => $post['id1'][$idx]));
                  $this->mglobal->saveData('t_mtr_site_l_equipment_mw', $data_ne, 'equipment_mw_id');

                  // add new one
                }
				else
				{

//                  $this->core->set['created_by'] = $created_by;
//                  $this->core->set['created_on'] = $created_on;
//                  $this->core->set['updated_by'] = $updated_by;
//                  $this->core->set['updated_on'] = $updated_on;
//
//                  $this->core->save('t_mtr_site_l_equipment_mw');
                  $last_id = $this->mglobal->saveData('t_mtr_site_l_equipment_mw', $data_ne);
//                  $last_id = $this->db->insert_id();
//
                  $pair_id = $last_id;

                  $this->mglobal->saveData('t_mtr_site_l_equipment_mw', array('pair_id' => $pair_id, 'equipment_mw_id' => $last_id), 'equipment_mw_id');
                }

                $this->db->where(array(
                    'site_id_ne' => $post['siteID2'][$idx],
                    'site_id_fe' => $post['siteID1'][$idx],
                    'tx_frequency' => $post['txFreq2'][$idx][$idx2],
                    'rx_frequency' => $post['rxFreq2'][$idx][$idx2],
                    'polarization' => $post['polarization2'][$idx][$idx2]
                ));

                $query = $this->db->get('t_mtr_site_l_equipment_mw');
                if ($query->row() != NULL)
				{
                  $error[] = 'Tx Frequency, Rx Frequency And Polarization must be different in same link.';
                  break 2;
                }

                // FE
                //$this->core->update('t_mtr_site_l_equipment_mw', array('active' => 0), array('site_id_ne' => (int) $post['id2'][$idx]));
                $data_fe['site_id_ne'] = $post['siteID2'][$idx];
                $data_fe['site_id_fe'] = $post['siteID1'][$idx];
                $data_fe['path_length'] = $post['pathLength2'][$idx];
                $data_fe['link_code_id'] = $post['linkID2'][$idx];
                $data_fe['link_type'] = $post['linkType2'][$idx];
                $data_fe['tx_frequency'] = $post['txFreq2'][$idx][$idx2];
                $data_fe['rx_frequency'] = $post['rxFreq2'][$idx][$idx2];
                $data_fe['frequency_used'] = $post['freqUsed2'][$idx];
                $data_fe['config'] = $post['config2'][$idx];
                $data_fe['power'] = $post['power2'][$idx];
                $data_fe['ip_address'] = $post['ipAddress2'][$idx];
                $data_fe['ant_model'] = $post['mainAntModel2'][$idx];
                $data_fe['ketinggian'] = $post['mainAntHeight2'][$idx];
                $data_fe['ant_diameter'] = $post['mainAntDiameter2'][$idx];
                $data_fe['sd_ant_model'] = null_empty($post['sdAntModel2'][$idx]);
                $data_fe['sd_ant_height'] = null_empty($post['sdAntHeight2'][$idx]);
                $data_fe['sd_ant_diameter'] = null_empty($post['sdAntDiameter2'][$idx]);
                $data_fe['azimuth'] = $post['azimuth2'][$idx];
                $data_fe['equipment_model'] = $post['eqModel2'][$idx];
                $data_fe['polarization'] = $post['polarization2'][$idx][$idx2];
                $data_fe['req_capacity'] = $post['reqCapacity2'][$idx];
                $data_fe['inst_capacity'] = $post['instCapacity2'][$idx];
                $data_fe['used_capacity'] = $post['usedCapacity2'][$idx];
                $data_fe['treshold'] = $post['treshold2'][$idx];
                $data_fe['plan_rsl'] = $post['planRSL2'][$idx];
                $data_fe['actual_rsl'] = $post['actualRSL2'][$idx];
                $data_fe['fm'] = $post['fm2'][$idx];
                $data_fe['availability'] = $post['availability2'][$idx];
                $data_fe['active'] = 1;
                $data_fe['pair_id'] = $pair_id;

                // edit old one
                if ($this->core->view_row('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => (int) $post['id2'][$idx], 'pair_id' => (int) $post['pairID'][$idx])))
				{
//                  $this->core->set['updated_by'] = $updated_by;
//                  $this->core->set['updated_on'] = $updated_on;
                  $data_fe['equipment_mw_id'] = $post['id2'][$idx];

                  $this->mglobal->saveData('t_mtr_site_l_equipment_mw', $data_fe, 'equipment_mw_id');

                  // add new one
                }
				else
				{

//                  $this->core->set['created_by'] = $created_by;
//                  $this->core->set['created_on'] = $created_on;
//                  $this->core->set['updated_by'] = $updated_by;
//                  $this->core->set['updated_on'] = $updated_on;
                  $this->mglobal->saveData('t_mtr_site_l_equipment_mw', $data_fe);

                  //$last_id = $this->db->insert_id();
                  //$this->core->update('t_mtr_site_l_equipment_mw', array('pair_id' => $pair_id), array('equipment_mw_id' => $last_id));
                }
              }
            }
          }

          if (count($error) == 0 && $this->db->trans_status() == TRUE)
		  {
            $json = array('error' => FALSE, 'message' => '<h4>Success</h4>Transmission has been added successfully.');
            $this->db->trans_commit();
          }
		  else
		  {
            $json = array('error' => TRUE, 'message' => $error);
            $this->db->trans_rollback();
          }
        }
		else
		{
          $json = array('error' => TRUE, 'message' => $error);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'ADD_GPS') {

        $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($this->_is_field_empty($post['siteId'])) {
          $error[] = 'Please determine Site.';
        }
        if ($this->core->view_row('t_mtr_site_l_equipment_gps', array('site_id' => (int) $post['siteId']))) {
          $error[] = 'Site ID is already in use. Please Choose another Site ID';
        }
        if ($this->_is_field_empty(@$post['gpsModel'])) {
          $error[] = 'Model can not unselected.';
        }
        if ($this->_is_field_empty(@$post['gpsHeight'])) {
          $error[] = 'Height can not unselected.';
        }

        if (count($error) == 0) {

          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s.u');

          $this->db->delete('t_mtr_site_l_equipment_gps', array('site_id' => (int) $post['siteId']));

          if (@is_array($post['gpsModel'])) {
            foreach ($post['gpsModel'] as $modelIdx => $model) {
              $this->core->set['ant_gps_model'] = $post['gpsModel'][$modelIdx];
              $this->core->set['ant_gps_height'] = $post['gpsHeight'][$modelIdx];
              $this->core->set['site_id'] = $post['siteId'];

              $this->core->save('t_mtr_site_l_equipment_gps');
            }
          }

          $json = array('error' => FALSE, 'message' => 'Antenna GPS has been added successfully.');
        } else {
          $json = array('error' => TRUE, 'message' => $error);
        }

        echo json_encode($json);
        exit;
      }
    }

    $this->_equipment_form();
  }

  public function edit($site_id = 0) {
    $this->_check_valid_loggedin();

    if (!$this->mmenu->cekPermission($this->_partials['menu'], 'menu_edit')) {
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }

    $site_id = (int) $this->core->decode($site_id);

    if ($this->core->is_ajax()) {

      if ($this->input->get('load_content') == 'BTS_INFO') {

//        $bts_info = $this->core->view_all('t_mtr_site_l_equipment_btsinfo', array('site_id' => $site_id, 'active' => 1), 'bts_id');
//
//        $row = $this->db->query("SELECT vendor_id FROM t_mtr_site WHERE site_id = {$site_id}")->row_array();
//        $vendor_ids = explode(';', $row['vendor_id']);
//
//        $rows = array();
//        foreach ($vendor_ids as $key => $val) {
//          $bts_info[$key]['vendor_id'] = trim($val);
//
//          $rows[] = $bts_info[$key];
//        }
//
//        $data['bts_info'] = $rows;
        $data['bts_info'] = $this->core->view_all('t_mtr_site_l_equipment_btsinfo', array('site_id' => $site_id, 'active' => 1), 'bts_id');

        echo $this->parser->parse('partials/content_equipment_btsinfo', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'RF') {

        $data['carrier'] = $this->equipment_model->get_equipment_carrier($site_id);
        $data['rf'] = $this->equipment_model->get_equipment_rf($site_id);

        echo $this->parser->parse('partials/content_equipment_rf', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'TRANSMISSION') {

        $data['transmission'] = array();

        $results1 = $this->db
                ->query("
						SELECT mw.equipment_mw_id, mw.site_id_ne, mw.site_id_fe, mw.pair_id, mw.link_id, mw.link_type, site.site_code, site.vendor_id, mw.ip_address, mw.ant_model, mw.ketinggian, mw.ant_diameter, mw.ant_diameter,
							mw.azimuth, mw.equipment_model, mw.polarization, mw.path_length, mw.tx_frequency, mw.rx_frequency, mw.power, mw.sd_ant_model, mw.sd_ant_height, mw.frequency_used, mw.config,
							mw.sd_ant_diameter, mw.req_capacity, mw.inst_capacity, mw.used_capacity, mw.treshold, mw.plan_rsl, mw.actual_rsl, mw.fm, mw.availability
						FROM t_mtr_site_l_equipment_mw mw
						LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_ne
						WHERE mw.site_id_ne = {$site_id} AND mw.active = 1
						ORDER BY mw.link_id ASC, mw.pair_id ASC
					")
                ->result_array();

        $a = array();
        foreach ($results1 as $res1) {
          $res2 = $this->db
                  ->query("
							SELECT mw.equipment_mw_id, mw.site_id_ne, mw.site_id_fe, mw.pair_id, mw.link_id, mw.link_type, site.site_code, site.vendor_id, mw.ip_address, mw.ant_model, mw.ketinggian, mw.ant_diameter, mw.ant_diameter,
								mw.azimuth, mw.equipment_model, mw.polarization, mw.path_length, mw.tx_frequency, mw.rx_frequency, mw.power, mw.sd_ant_model, mw.sd_ant_height, mw.frequency_used, mw.config,
								mw.sd_ant_diameter, mw.req_capacity, mw.inst_capacity, mw.used_capacity, mw.treshold, mw.plan_rsl, mw.actual_rsl, mw.fm, mw.availability
							FROM t_mtr_site_l_equipment_mw mw
							LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_ne
							WHERE mw.site_id_ne = " . $res1['site_id_fe'] . " AND mw.pair_id = " . $res1['pair_id'] . " AND mw.active = 1
							ORDER BY mw.link_id ASC, mw.pair_id ASC
						")
                  ->row_array();

          $a[] = array('ne' => $res1, 'fe' => $res2);
        }

        $data['site_class_transm'] = $this->db->query("SELECT site_class_transm FROM t_mtr_site WHERE site_id = {$site_id}")->row_array();
        $data['transmission'] = $a;

        echo $this->parser->parse('partials/content_equipment_transmission', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'GPS') {

        $data['gps'] = $this->equipment_model->get_equipment_gps($site_id);

        echo $this->parser->parse('partials/content_equipment_gps', $data);
        exit;
      }

      if ($this->input->post('cmd') == 'EDIT_BTS_INFO') {

        $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($this->_is_field_empty($post['siteId'])) {
          $error[] = 'Please determine Site.';
        }
        if ($this->_is_field_empty($post['morphology'])) {
          $error[] = 'Morphology can not unselected.';
        }
        if ($this->_is_field_empty($post['vendorId'])) {
          $error[] = 'Vendor ID can not empty.';
        }
        if ($this->_is_field_empty($post['mode'])) {
          $error[] = 'BTS Mode can not unselected.';
        }
        if ($this->_is_field_empty($post['brand'])) {
          $error[] = 'BTS Brand can not unselected.';
        }
        if ($this->_is_field_empty($post['type'])) {
          $error[] = 'BTS Type can not unselected.';
        }

        $vendor_ids = array();
        for ($i = (count($post['vendorId']) - 1); $i >= 0; $i--) {
          $vendor_ids[] = $post['vendorId'][$i];
        }

        $vid = array();
        $onair = '';
        foreach ($vendor_ids as $val) {
          $sql = "SELECT vendor_id, onair_offair FROM v_site_info_master_site WHERE site_code = '" . $post['siteCode'] . "' AND vendor_id LIKE '%{$val}%' AND lower(status) = 'active' AND lower(onair_offair) = 'on air' AND site_id != {$site_id}";
          $rows = $this->db->query($sql)->result_array();
          foreach ($rows as $r) {
            $vendor_ids_2 = explode(';', $r['vendor_id']);
            foreach ($vendor_ids_2 as $val2) {
              if (trim($val) == trim($val2)) {
                $vid[] = $val;
              }
            }
            $onair = $r['onair_offair'];
          }
        }

        if (count($vid) > 0) {
          $error[] = 'Vendor ID ' . implode(', ', $vid) . ' is already ' . $onair;
        }
        $this->db->trans_start();
        if (count($error) == 0) {

          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s');

          $this->core->db_debug = TRUE;

          $this->db->update('t_mtr_site_l_equipment_btsinfo', array('active' => 0), array('site_id' => $post['siteId']));

          if (@is_array($post['btsId'])) {
            foreach ($post['btsId'] as $idx => $val) {
              if ($this->core->view_row('t_mtr_site_l_equipment_btsinfo', array('bts_id' => (int) $post['btsId'][$idx]))) {
                $this->core->set['site_id'] = $post['siteId'];
                $this->core->set['morphology'] = $post['morphology'];
                $this->core->set['bts_mode'] = $post['mode'][$idx];
                $this->core->set['bts_brand'] = $post['brand'][$idx];
                $this->core->set['bts_type'] = $post['type'][$idx];
                $this->core->set['updated_on'] = $updated_on;
                $this->core->set['updated_by'] = $updated_by;
                $this->core->set['active'] = 1;
                $this->core->set['vendor_id'] = $post['vendorId'][$idx];
                $this->core->set['height_asl'] = null_empty($post['height_asl'][$idx]);
                $this->core->set['radius_of_operation'] = null_empty($post['radius_of_operation'][$idx]);
                $this->core->set['equipment_type'] = null_empty($post['equipment_type'][$idx]);
                $this->core->set['equipment_output_power'] = null_empty($post['equipment_output_power'][$idx]);
                $this->core->set['bandwidth'] = null_empty($post['bandwidth'][$idx]);
//                $this->core->set['tx_freq'] = $post['tx_freq'][$idx];
                $this->core->set['rx_freq'] = null_empty($post['rx_freq'][$idx]);
                $this->core->set['antenna_type'] = null_empty($post['antenna_type'][$idx]);
                $this->core->set['feeding_loss'] = null_empty($post['feeding_loss'][$idx]);
                $this->core->set['connector_loss'] = null_empty($post['connector_loss'][$idx]);
                $this->core->set['branch_loss'] = null_empty($post['branch_loss'][$idx]);
                $this->core->set['antenna_height_agl'] = null_empty($post['antenna_height_agl'][$idx]);
                $this->core->set['transmitter_polarization'] = $post['transmitter_polarization'][$idx];
                $this->core->set['cdma_id'] = $post['cdma_id'][$idx];
                $this->core->set['bts_system'] = $post['bts_system'][$idx];

                $this->core->save('t_mtr_site_l_equipment_btsinfo', array('bts_id' => (int) $post['btsId'][$idx]));
                //$data = $this->core->set;
                //$this->site_info->save_data('t_mtr_site_l_equipment_btsinfo', $data, 'site_id');
              } else {
                $this->core->set['site_id'] = $post['siteId'];
                $this->core->set['morphology'] = $post['morphology'];
                $this->core->set['bts_mode'] = $post['mode'][$idx];
                $this->core->set['bts_brand'] = $post['brand'][$idx];
                $this->core->set['bts_type'] = $post['type'][$idx];
                $this->core->set['created_on'] = $created_on;
                $this->core->set['created_by'] = $created_by;
                $this->core->set['updated_on'] = $updated_on;
                $this->core->set['updated_by'] = $updated_by;
                $this->core->set['active'] = 1;
                $this->core->set['vendor_id'] = $post['vendorId'][$idx];
                $this->core->set['height_asl'] = null_empty($post['height_asl'][$idx]);
                $this->core->set['radius_of_operation'] = null_empty($post['radius_of_operation'][$idx]);
                $this->core->set['equipment_type'] = null_empty($post['equipment_type'][$idx]);
                $this->core->set['equipment_output_power'] = null_empty($post['equipment_output_power'][$idx]);
                $this->core->set['bandwidth'] = null_empty($post['bandwidth'][$idx]);
//                $this->core->set['tx_freq'] = $post['tx_freq'][$idx];
                $this->core->set['rx_freq'] = null_empty($post['rx_freq'][$idx]);
                $this->core->set['antenna_type'] = null_empty($post['antenna_type'][$idx]);
                $this->core->set['feeding_loss'] = null_empty($post['feeding_loss'][$idx]);
                $this->core->set['connector_loss'] = null_empty($post['connector_loss'][$idx]);
                $this->core->set['branch_loss'] = null_empty($post['branch_loss'][$idx]);
                $this->core->set['antenna_height_agl'] = null_empty($post['antenna_height_agl'][$idx]);
                $this->core->set['transmitter_polarization'] = $post['transmitter_polarization'][$idx];
                $this->core->set['cdma_id'] = $post['cdma_id'][$idx];
                $this->core->set['bts_system'] = $post['bts_system'][$idx];

                $this->core->save('t_mtr_site_l_equipment_btsinfo');
              }
            }

            $vendor_ids = array();
            for ($i = (count($post['vendorId']) - 1); $i >= 0; $i--) {
              $vendor_ids[] = $post['vendorId'][$i];
            }

            $this->db->update('t_mtr_site', array('vendor_id' => implode(';', $vendor_ids)), array('site_id' => $post['siteId']));
          }

          $json = array('error' => FALSE, 'message' => 'BTS Info has been updated successfully.', 'site_id' => $this->core->encode($post['siteId']));
        } else {
          $json = array('error' => TRUE, 'message' => $error);
        }
        $this->db->trans_complete();

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'EDIT_RF') {

        $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($this->_is_field_empty($post['siteId'])) {
          $error[] = 'Please determine Site.';
        }
        if ($this->_is_field_empty(@$post['system'])) {
          $error[] = 'System can not unselected.';
        }
        if ($this->_is_field_empty(@$post['config'])) {
          $error[] = 'Configuration can not unselected.';
        }

        if ($this->_is_field_empty(@$post['typeRF'])) {
          $error[] = 'Type can not unselected.';
        }
        if ($this->_is_field_empty(@$post['rfModelId'])) {
          $error[] = 'Model can not unselected.';
        }
        if ($this->_is_field_empty(@$post['cell'])) {
          $error[] = 'Cell can not unselected.';
        }
        if ($this->_is_field_empty(@$post['rfBand'])) {
          $error[] = 'Band can not unselected.';
        }
        /*
          if ($this->_is_field_empty(@$post['verticalBeamwidth'])) {
          $error[] = 'Vertical Beamwidth can not empty.';
          }
          if ($this->_is_field_empty(@$post['horizontalBeamwidth'])) {
          $error[] = 'Horizontal Beamwidth can not empty.';
          }
          if ($this->_is_field_empty(@$post['dimensionLong']) || $this->_is_field_empty(@$post['dimensionWidth']) || $this->_is_field_empty(@$post['dimensionHeight'])) {
          $error[] = 'Dimension can not empty.';
          }
          if ($this->_is_field_empty(@$post['weight'])) {
          $error[] = 'Weight can not empty.';
          } */

        if (count($error) == 0) {

          $this->core->db_debug = TRUE;

          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s');

          //$this->db->delete('t_mtr_site_l_equipment_carrier', array('site_id' => $post['siteId']));
          $this->db->update('t_mtr_site_l_equipment_carrier', array('active' => 0), array('site_id' => $post['siteId']));

          if (@is_array($post['system'])) {
            foreach ($post['system'] as $idx => $val) {
              $this->core->set['system'] = $post['system'][$idx];
              $this->core->set['configuration'] = $post['config'][$idx];
              $this->core->set['active'] = 1;
              $this->core->set['site_id'] = $post['siteId'];

              if ($this->core->view_row('t_mtr_site_l_equipment_carrier', array('equipment_carrier_id' => (int) $post['carrierId'][$idx]))) {
                $this->core->save('t_mtr_site_l_equipment_carrier', array('equipment_carrier_id' => (int) $post['carrierId'][$idx]));
              } else {
                $this->core->save('t_mtr_site_l_equipment_carrier');
              }
            }
          }

          // Save Antenna RF
          //$this->db->delete('t_mtr_site_l_equipment_rf', array('site_id' => $post['siteId']));
          $this->db->update('t_mtr_site_l_equipment_rf', array('active' => 0), array('site_id' => $post['siteId']));

          if (@is_array($post['rfId'])) {
            foreach ($post['rfId'] as $rf_key => $rf_val) {
              $this->core->set['site_id'] = $post['siteId'];
              $this->core->set['type'] = $post['typeRF'][$rf_key];
              $this->core->set['model'] = $post['rfModelId'][$rf_key];
              $this->core->set['cell'] = $post['cell'][$rf_key];
              //$this->core->set['frequency'] = null_empty($post['antenaFrequency'][$rf_key]);
              $this->core->set['ant_model'] = null_empty($post['rfBand'][$rf_key]);
              $this->core->set['height'] = null_empty($post['height'][$rf_key]);
              $this->core->set['azimuth'] = null_empty($post['antenaAzimuth'][$rf_key]);
              $this->core->set['electrical_tilt'] = null_empty($post['electricalTilt'][$rf_key]);
              $this->core->set['mechanical_tilt'] = null_empty($post['mechanicalTilt'][$rf_key]);
              //$this->core->set['vertical_beamwidth'] = $post['verticalBeamwidth'][$rf_key];
              //$this->core->set['horizontal_beamwidth'] = $post['horizontalBeamwidth'][$rf_key];
              //$this->core->set['dimension_long'] = null_empty($post['dimensionLong'][$rf_key]);
              //$this->core->set['dimension_height'] = null_empty($post['dimensionHeight'][$rf_key]);
              //$this->core->set['dimension_width'] = null_empty($post['dimensionWidth'][$rf_key]);
              //$this->core->set['weight'] = null_empty($post['weight'][$rf_key]);
              $this->core->set['active'] = 1;


              if ($this->core->view_row('t_mtr_site_l_equipment_rf', array('equipment_rf_id' => (int) $post['rfId'][$rf_key]))) {
                $this->core->set['updated_by'] = $updated_by;
                $this->core->set['updated_on'] = $updated_on;

                $this->core->save('t_mtr_site_l_equipment_rf', array('equipment_rf_id' => (int) $post['rfId'][$rf_key]));
              } else {
                $this->core->set['created_by'] = $created_by;
                $this->core->set['created_on'] = $created_on;
                $this->core->set['updated_by'] = $updated_by;
                $this->core->set['updated_on'] = $updated_on;

                $this->core->save('t_mtr_site_l_equipment_rf');
              }
            }
          }

          $json = array('error' => FALSE, 'message' => 'Antenna RF has been updated successfully.');
        } else {
          $json = array('error' => TRUE, 'message' => $error);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'EDIT_TRANSMISSION__') {

        $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($post['siteId'] == 0) {
          $error[] = 'Please determine Site.';
        }

        if (count($error) == 0) {

          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s.u');

          $this->core->db_debug = TRUE;

          $this->db->delete('t_mtr_site_l_equipment_mw', array('site_id_ne' => $post['siteId']));
          $this->db->delete('t_mtr_site_l_equipment_mw', array('site_id_fe' => $post['siteId']));

          if (@is_array($post['mwId'])) {
            foreach ($post['mwId'] as $mw_key => $mw_val) {
              $this->core->set['type'] = $post['typeTrans'][$mw_key];
              $this->core->set['ip_address'] = $post['ipAddress'][$mw_key];
              $this->core->set['site_id_ne'] = $post['siteId'];
              $this->core->set['site_id_fe'] = null_empty($post['farEnd'][$mw_key]);
              $this->core->set['link_code_id'] = null_empty($post['linkId'][$mw_key]);
              $this->core->set['ant_model'] = null_empty($post['antModel'][$mw_key]);
              $this->core->set['frequency'] = null_empty($post['mwFrequency'][$mw_key]);
              $this->core->set['capacity'] = null_empty($post['mwCapacity'][$mw_key]);
              $this->core->set['equipment_model'] = $post['equipmentModel'][$mw_key];
              $this->core->set['bandwidth'] = $post['bandwidth'][$mw_key];
              $this->core->set['protection_config'] = $post['protectionConfig'][$mw_key];
              $this->core->set['tx_power'] = $post['txPower'][$mw_key];
              $this->core->set['ant_diameter'] = $post['antDiameter'][$mw_key];
              $this->core->set['number_of_channel'] = $post['numberOfChannel'][$mw_key];
              $this->core->set['active'] = 1;
              //$this->core->set['equipment_id'] = $equipment_id;
              // edit old one
              if ($this->core->view_row('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => null_empty($mw_val)))) {

                $this->core->set['updated_by'] = $updated_by;
                $this->core->set['updated_on'] = $updated_on;

                $this->core->save('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => $mw_val));

                // add new one
              } else {
                // #1
                $this->core->set['created_by'] = $created_by;
                $this->core->set['created_on'] = $created_on;

                $this->core->save('t_mtr_site_l_equipment_mw');

                $last_id = $this->db->insert_id();

                $this->core->update('t_mtr_site_l_equipment_mw', array('pair_id' => $last_id), array('equipment_mw_id' => $last_id));

                // #2
                $near_end = $post['siteId'];
                $far_end = (int) null_empty($post['farEnd'][$mw_key]);

                $result = $this->db
                        ->query("SELECT s1.vendor_id AS ne, s2.vendor_id AS fe FROM  t_mtr_site s1, t_mtr_site s2 WHERE s1.site_id = {$near_end} AND s2.site_id = {$far_end}")
                        ->row_array();

                $link_id = $result ? $result['fe'] . '#' . $result['ne'] . '#02' : '';

                $this->core->set['type'] = $post['typeTrans'][$mw_key];
                $this->core->set['ip_address'] = $post['ipAddress'][$mw_key];
                $this->core->set['site_id_ne'] = null_empty($post['farEnd'][$mw_key]);
                $this->core->set['site_id_fe'] = $post['siteId'];
                $this->core->set['link_code_id'] = $link_id;
                $this->core->set['ant_model'] = NULL;
                $this->core->set['frequency'] = NULL;
                $this->core->set['capacity'] = NULL;
                $this->core->set['equipment_model'] = $post['equipmentModel'][$mw_key];
                $this->core->set['bandwidth'] = $post['bandwidth'][$mw_key];
                $this->core->set['protection_config'] = $post['protectionConfig'][$mw_key];
                $this->core->set['tx_power'] = $post['txPower'][$mw_key];
                $this->core->set['ant_diameter'] = $post['antDiameter'][$mw_key];
                $this->core->set['number_of_channel'] = $post['numberOfChannel'][$mw_key];
                $this->core->set['active'] = 1;
                //$this->core->set['equipment_id'] = $equipment_id;
                $this->core->set['created_by'] = $created_by;
                $this->core->set['created_on'] = $created_on;

                $this->core->save('t_mtr_site_l_equipment_mw');

                $last_id2 = $this->db->insert_id();

                $this->core->update('t_mtr_site_l_equipment_mw', array('pair_id' => $last_id), array('equipment_mw_id' => $last_id2));
              }
            }
          }

          $json = array('error' => FALSE, 'message' => 'Transmission has been updated successfully.');
        } else {
          $json = array('error' => TRUE, 'message' => $error);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'EDIT_TRANSMISSION') {

        $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($this->_is_field_empty($post['siteId'])) {
          $error[] = 'Please determine Site.';
        }
        if ($this->_is_field_empty($post['siteID1Code']) || $this->_is_field_empty($post['siteID2Code'])) {
          $error[] = 'Site ID can not empty.';
        }
        if ($this->_is_field_empty($post['pathLength1']) || $this->_is_field_empty($post['pathLength2'])) {
          $error[] = 'Path Length can not empty.';
        }
        if ($this->_is_field_empty($post['linkID1']) || $this->_is_field_empty($post['linkID2'])) {
          $error[] = 'Link ID can not empty.';
        }
        if ($this->_is_field_empty($post['linkType1']) || $this->_is_field_empty($post['linkType2'])) {
          $error[] = 'Link Type can not empty.';
        }
        /*
          if ($this->_is_field_empty($post['txFreq1']) || $this->_is_field_empty($post['txFreq2'])) {
          $error[] = 'TX Frequency can not empty.';
          }
          if ($this->_is_field_empty($post['rxFreq1']) || $this->_is_field_empty($post['rxFreq2'])) {
          $error[] = 'RX Frequency can not empty.';
          }
         */
        if ($this->_is_field_empty($post['freqUsed1']) || $this->_is_field_empty($post['freqUsed2'])) {
          $error[] = 'Freq. Used can not empty.';
        }
        if ($this->_is_field_empty($post['power1']) || $this->_is_field_empty($post['power2'])) {
          $error[] = 'Power can not empty.';
        }
        /*
          if ($this->_is_field_empty($post['ipAddress1']) || $this->_is_field_empty($post['ipAddress2'])) {
          $error[] = 'IP Address can not empty.';
          }
         */
        if ($this->_is_field_empty($post['mainAntModel1']) || $this->_is_field_empty($post['mainAntModel2'])) {
          $error[] = 'Main Ant. Model can not empty.';
        }
        if ($this->_is_field_empty($post['mainAntHeight1']) || $this->_is_field_empty($post['mainAntHeight2'])) {
          $error[] = 'Main Ant. Height can not empty.';
        }
        if ($this->_is_field_empty($post['mainAntDiameter1']) || $this->_is_field_empty($post['mainAntDiameter2'])) {
          $error[] = 'Main Ant. Diameter can not unselected.';
        }
        /*
          if ($this->_is_field_empty($post['sdAntModel1']) || $this->_is_field_empty($post['sdAntModel2'])) {
          $error[] = 'SD Ant. Model can not empty.';
          }
          if ($this->_is_field_empty($post['sdAntHeight1']) || $this->_is_field_empty($post['sdAntHeight2'])) {
          $error[] = 'SD Ant. Height can not empty.';
          }
          if ($this->_is_field_empty($post['sdAntDiameter1']) || $this->_is_field_empty($post['sdAntDiameter2'])) {
          $error[] = 'SD Ant. Diameter can not unselected.';
          }
         */
        if ($this->_is_field_empty($post['azimuth1']) || $this->_is_field_empty($post['azimuth2'])) {
          $error[] = 'Azimuth can not empty.';
        }
        if ($this->_is_field_empty($post['eqModel1']) || $this->_is_field_empty($post['eqModel2'])) {
          $error[] = 'Eq. Model can not unselected.';
        }
        if ($this->_is_field_empty($post['polarization1']) || $this->_is_field_empty($post['polarization2'])) {
          $error[] = 'Polarization can not unselected.';
        }
        if ($this->_is_field_empty($post['reqCapacity1']) || $this->_is_field_empty($post['reqCapacity2'])) {
          $error[] = 'Req. Capacity can not empty.';
        }
        if ($this->_is_field_empty($post['instCapacity1']) || $this->_is_field_empty($post['instCapacity2'])) {
          $error[] = 'Inst. Capacity can not empty.';
        }
        if ($this->_is_field_empty($post['usedCapacity1']) || $this->_is_field_empty($post['usedCapacity2'])) {
          $error[] = 'Used Capacity can not empty.';
        }
        if ($this->_is_field_empty($post['treshold1']) || $this->_is_field_empty($post['treshold2'])) {
          $error[] = 'Treshold can not empty.';
        }
        if ($this->_is_field_empty($post['planRSL1']) || $this->_is_field_empty($post['planRSL2'])) {
          $error[] = 'Plan RSL can not empty.';
        }
        /*
          if ($this->_is_field_empty($post['actualRSL1']) || $this->_is_field_empty($post['actualRSL2'])) {
          $error[] = 'Actual RSL can not empty.';
          }
         */
        if ($this->_is_field_empty($post['fm1']) || $this->_is_field_empty($post['fm2'])) {
          $error[] = 'FM can not empty.';
        }
        if ($this->_is_field_empty($post['availability1']) || $this->_is_field_empty($post['availability2'])) {
          $error[] = 'Availability can not empty.';
        }

        if (count($error) == 0) {
          $this->db->trans_begin();
          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s');

          $this->core->db_debug = TRUE;

          $this->core->update('t_mtr_site', array('site_class_transm' => $post['siteClassTransm']), array('site_id' => $site_id));

          $this->core->update('t_mtr_site_l_equipment_mw', array('active' => 0), array('site_id_ne' => $site_id));

          ksort($post['id1']);
          ksort($post['txFreq1']);
          if (@is_array($post['id1'])) {
            //echo $abc;
            foreach ($post['id1'] as $idx => $val) {
              ksort($post['txFreq1'][$idx]);
              foreach ($post['txFreq1'][$idx] as $idx2 => $val2) {

                $this->db->where(array(
                    'site_id_ne' => $post['siteID1'][$idx],
                    'site_id_fe' => $post['siteID2'][$idx],
                    'tx_frequency' => $post['txFreq1'][$idx][$idx2],
                    'rx_frequency' => $post['rxFreq1'][$idx][$idx2],
                    'polarization' => $post['polarization1'][$idx][$idx2],
                    'active' => 1
                ));

                $query = $this->db->get('t_mtr_site_l_equipment_mw');
                if ($query->row() != NULL) {
                  $error[] = 'Near End : Tx Frequency, Rx Frequency And Polarization must be different in same link.';
                  break 2;
                }

                // NE
                //$this->core->update('t_mtr_site_l_equipment_mw', array('active' => 0), array('site_id_ne' => (int) $post['id1'][$idx]));
                $data_ne = array();
                $data_ne['site_id_ne'] = $post['siteID1'][$idx];
                $data_ne['site_id_fe'] = $post['siteID2'][$idx];
                $data_ne['path_length'] = $post['pathLength1'][$idx];
                $data_ne['link_code_id'] = $post['linkID1'][$idx];
                $data_ne['link_type'] = $post['linkType1'][$idx];
                $data_ne['tx_frequency'] = $post['txFreq1'][$idx][$idx2];
                $data_ne['rx_frequency'] = $post['rxFreq1'][$idx][$idx2];
                $data_ne['polarization'] = $post['polarization1'][$idx][$idx2];
                $data_ne['frequency_used'] = $post['freqUsed1'][$idx];
                $data_ne['config'] = $post['config1'][$idx];
                $data_ne['power'] = $post['power1'][$idx];
                $data_ne['ip_address'] = $post['ipAddress1'][$idx];
                $data_ne['ant_model'] = $post['mainAntModel1'][$idx];
                $data_ne['ketinggian'] = $post['mainAntHeight1'][$idx];
                $data_ne['ant_diameter'] = $post['mainAntDiameter1'][$idx];
                $data_ne['sd_ant_model'] = null_empty($post['sdAntModel1'][$idx]);
                $data_ne['sd_ant_height'] = null_empty($post['sdAntHeight1'][$idx]);
                $data_ne['sd_ant_diameter'] = null_empty($post['sdAntDiameter1'][$idx]);
                $data_ne['azimuth'] = $post['azimuth1'][$idx];
                $data_ne['equipment_model'] = $post['eqModel1'][$idx];
                //$data_ne['polarization'] = $post['polarization1'][$idx];
                $data_ne['req_capacity'] = $post['reqCapacity1'][$idx];
                $data_ne['inst_capacity'] = $post['instCapacity1'][$idx];
                $data_ne['used_capacity'] = $post['usedCapacity1'][$idx];
                $data_ne['treshold'] = $post['treshold1'][$idx];
                $data_ne['plan_rsl'] = $post['planRSL1'][$idx];
                $data_ne['actual_rsl'] = $post['actualRSL1'][$idx];
                $data_ne['fm'] = $post['fm1'][$idx];
                $data_ne['availability'] = $post['availability1'][$idx];
                //$data_ne['remark'] = $post['remark1'][$idx];
                $data_ne['active'] = 1;

                $pair_id = 0;

                // edit old one
                if (isset($post['pairID1'][$idx][$idx2]) && $this->core->view_row('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => (int) $post['id1'][$idx][$idx2], 'pair_id' => (int) $post['pairID1'][$idx][$idx2]))) {
                  $pair = $this->db->query("SELECT pair_id FROM t_mtr_site_l_equipment_mw WHERE equipment_mw_id = " . (int) $post['id1'][$idx][$idx2])->row_array();
                  $pair_id = $pair['pair_id'];

                  $data_ne['pair_id'] = $pair_id;

//                  $data_ne['updated_by'] = $updated_by;
//                  $data_ne['updated_on'] = $updated_on;
                  $data_ne['equipment_mw_id'] = $post['id1'][$idx][$idx2];

                  //$this->core->save('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => $post['id1'][$idx]));
                  $this->mglobal->saveData('t_mtr_site_l_equipment_mw', $data_ne, 'equipment_mw_id');
                  // add new one
                } else {
                  //echo $idx . ' ' . $idx2;
//                $this->core->set['created_by'] = $created_by;
//                $this->core->set['created_on'] = $created_on;
//
//                $this->core->save('t_mtr_site_l_equipment_mw');
//
//                $last_id = $this->db->insert_id();
                  //$data_ne['equipment_mw_id'] = 9999999;
                  //$data_ne['pair_id'] = 9999999;
                  $last_id = $this->mglobal->saveData('t_mtr_site_l_equipment_mw', $data_ne);

                  $pair_id = $last_id;

                  $this->mglobal->saveData('t_mtr_site_l_equipment_mw', array('pair_id' => $pair_id, 'equipment_mw_id' => $last_id), 'equipment_mw_id');
                }

                // FE
                //$this->core->update('t_mtr_site_l_equipment_mw', array('active' => 0), array('site_id_ne' => (int) $post['id2'][$idx]));

                $this->db->where(array(
                    'site_id_ne' => $post['siteID2'][$idx],
                    'site_id_fe' => $post['siteID1'][$idx],
                    'tx_frequency' => $post['txFreq2'][$idx][$idx2],
                    'rx_frequency' => $post['rxFreq2'][$idx][$idx2],
                    'polarization' => $post['polarization2'][$idx][$idx2],
                    'active' => 0
                ));

                $query = $this->db->get('t_mtr_site_l_equipment_mw');
                if ($query->row() != NULL) {
                  $error[] = 'Far End : Tx Frequency, Rx Frequency And Polarization must be different in same link.';
                  break 2;
                }

                $data_fe = array();
                $data_fe['site_id_ne'] = $post['siteID2'][$idx];
                $data_fe['site_id_fe'] = $post['siteID1'][$idx];
                $data_fe['path_length'] = $post['pathLength2'][$idx];
                $data_fe['link_code_id'] = $post['linkID2'][$idx];
                $data_fe['link_type'] = $post['linkType2'][$idx];
                $data_fe['tx_frequency'] = $post['txFreq2'][$idx][$idx2];
                $data_fe['rx_frequency'] = $post['rxFreq2'][$idx][$idx2];
                $data_fe['polarization'] = $post['polarization2'][$idx][$idx2];
                $data_fe['frequency_used'] = $post['freqUsed2'][$idx];
                $data_fe['config'] = $post['config2'][$idx];
                $data_fe['power'] = $post['power2'][$idx];
                $data_fe['ip_address'] = $post['ipAddress2'][$idx];
                $data_fe['ant_model'] = $post['mainAntModel2'][$idx];
                $data_fe['ketinggian'] = $post['mainAntHeight2'][$idx];
                $data_fe['ant_diameter'] = $post['mainAntDiameter2'][$idx];
                $data_fe['sd_ant_model'] = null_empty($post['sdAntModel2'][$idx]);
                $data_fe['sd_ant_height'] = null_empty($post['sdAntHeight2'][$idx]);
                $data_fe['sd_ant_diameter'] = null_empty($post['sdAntDiameter2'][$idx]);
                $data_fe['azimuth'] = $post['azimuth2'][$idx];
                $data_fe['equipment_model'] = $post['eqModel2'][$idx];
                $data_fe['req_capacity'] = $post['reqCapacity2'][$idx];
                $data_fe['inst_capacity'] = $post['instCapacity2'][$idx];
                $data_fe['used_capacity'] = $post['usedCapacity2'][$idx];
                $data_fe['treshold'] = $post['treshold2'][$idx];
                $data_fe['plan_rsl'] = $post['planRSL2'][$idx];
                $data_fe['actual_rsl'] = $post['actualRSL2'][$idx];
                $data_fe['fm'] = $post['fm2'][$idx];
                $data_fe['availability'] = $post['availability2'][$idx];
                //$data_fe['remark'] = $post['remark2'][$idx];
                $data_fe['active'] = 1;
                $data_fe['pair_id'] = $pair_id;

                // edit old one
                if (isset($post['id2'][$idx][$idx2]) && $this->core->view_row('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => (int) $post['id2'][$idx][$idx2], 'pair_id' => (int) $post['pairID2'][$idx][$idx2]))) {
//                $data_fe['updated_by'] = $updated_by;
//                $data_fe['updated_on'] = $updated_on;
//
//                $this->core->save('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => $post['id2'][$idx]));
                  $data_fe['equipment_mw_id'] = $post['id2'][$idx][$idx2];
                  $this->mglobal->saveData('t_mtr_site_l_equipment_mw', $data_fe, 'equipment_mw_id');
                  // add new one
                } else {
//                $data_fe['created_by'] = $created_by;
//                $data_fe['created_on'] = $created_on;
//                $this->core->save('t_mtr_site_l_equipment_mw');
//                $last_id = $this->db->insert_id();
//                $this->core->update('t_mtr_site_l_equipment_mw', array('pair_id' => $pair_id), array('equipment_mw_id' => $last_id));
                  $this->mglobal->saveData('t_mtr_site_l_equipment_mw', $data_fe);
                }
              }
            }
          }

          if (count($error) == 0 && $this->db->trans_status() == TRUE) {
            $json = array('error' => FALSE, 'message' => '<h4>Success</h4>Transmission has been added successfully.');
            $this->db->trans_commit();
          } else {
            $json = array('error' => TRUE, 'message' => $error);
            $this->db->trans_rollback();
          }
        } else {
          $json = array('error' => TRUE, 'message' => $error);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'EDIT_GPS') {

        $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($this->_is_field_empty($post['siteId'])) {
          $error[] = 'Please determine Site.';
        }
        if ($this->_is_field_empty(@$post['gpsModel'])) {
          $error[] = 'Model can not unselected.';
        }
        if ($this->_is_field_empty(@$post['gpsHeight'])) {
          $error[] = 'Height can not unselected.';
        }

        if (count($error) == 0) {

          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s.u');

          $this->db->delete('t_mtr_site_l_equipment_gps', array('site_id' => $site_id));

          if (@is_array($post['gpsModel'])) {
            foreach ($post['gpsModel'] as $modelIdx => $model) {
              $this->core->set['ant_gps_model'] = $post['gpsModel'][$modelIdx];
              $this->core->set['ant_gps_height'] = $post['gpsHeight'][$modelIdx];
              $this->core->set['site_id'] = $post['siteId'];

              $this->core->save('t_mtr_site_l_equipment_gps');
            }
          }

          $json = array('error' => FALSE, 'message' => 'Antenna GPS has been updated successfully.');
        } else {
          $json = array('error' => TRUE, 'message' => $error);
        }

        echo json_encode($json);
        exit;
      }

      /* $post = $this->core->get_post();
        $json = array();
        $error = array();

        if ($post['siteId'] == 0) {
        $error[] = 'Please determine Site.';
        }
        if (@in_array(0, $post['farEnd'])) {
        $error[] = 'Please choose Far End.';
        }
        if (@in_array('', $post['cell'])) {
        $error[] = 'Please choose Cell.';
        }

        if (count($error) == 0) {

        $created_by = $updated_by = $this->session->userdata('user_id');
        $created_on = $updated_on = date('Y-m-d H:i:s.u');

        $this->core->set['site_id'] = $post['siteId'];
        //$this->core->set['site_profile_id'] = null_empty($post['siteProfile']);
        //$this->core->set['morphology'] = null_empty($post['morphology']);
        //$this->core->set['bts_mode'] = null_empty($post['btsMode']);
        //$this->core->set['bts_brand'] = null_empty($post['btsBrand']);
        //$this->core->set['bts_type'] = null_empty($post['btsType']);
        //$this->core->set['bts_capacity'] = null_empty($post['capacity']);
        //$this->core->set['bts_capacity_uom'] = null_empty($post['capacityUnit']);

        // Battery
        //$this->core->set['battery_brand'] = $post['batteryBrand'];
        //$this->core->set['battery_capacity'] = null_empty($post['batteryCapacity']);
        //$this->core->set['battery_total_bank'] = null_empty($post['batteryTotalBank']);
        //$this->core->set['battery_total_shunt'] = null_empty($post['batteryTotalShunt']);

        $this->core->set['updated_by'] = $updated_by;
        $this->core->set['updated_on'] = $updated_on;

        $this->core->save('t_mtr_site_l_equipment', array('equipment_id' => $equipment_id));

        // save Carrier Configuration
        $this->core->update('t_mtr_site_l_equipment_carrier', array('active' => 0), array('equipment_id' => $equipment_id));

        if (@is_array($post['carrierId'])) {
        foreach ($post['carrierId'] as $car_key => $car_val) {
        if ( $this->core->view_row('t_mtr_site_l_equipment_carrier', array('equipment_carrier_id' => null_empty($car_val))) ) {

        $this->core->set['system'] = $post['system'][$car_key];
        $this->core->set['configuration'] = $post['config'][$car_key];
        $this->core->set['active'] = 1;
        $this->core->set['equipment_id'] = $equipment_id;
        //$this->core->set['updated_by'] = $updated_by;
        //$this->core->set['updated_on'] = $updated_on;

        $this->core->save('t_mtr_site_l_equipment_carrier', array('equipment_carrier_id' => $car_val));

        } else {

        $this->core->set['system'] = $post['system'][$car_key];
        $this->core->set['configuration'] = $post['config'][$car_key];
        $this->core->set['active'] = 1;
        $this->core->set['equipment_id'] = $equipment_id;
        //$this->core->set['updated_by'] = $updated_by;
        //$this->core->set['updated_on'] = $updated_on;

        $this->core->save('t_mtr_site_l_equipment_carrier');
        }
        }
        }

        // save BTS (RF), but soft delete first
        $this->core->update('t_mtr_site_l_equipment_bts', array('active' => 0), array('equipment_id' => $equipment_id));

        if (@is_array($post['rfId'])) {
        foreach ($post['rfId'] as $rf_key => $rf_val) {
        if ( $this->core->view_row('t_mtr_site_l_equipment_bts', array('equipment_bts_id' => null_empty($rf_val))) ) {

        $this->core->set['type'] = $post['typeRF'][$rf_key];
        $this->core->set['cell'] = $post['cell'][$rf_key];
        $this->core->set['frequency'] = null_empty($post['antenaFrequency'][$rf_key]);
        $this->core->set['ant_model'] = null_empty($post['antenaModel'][$rf_key]);
        $this->core->set['azimuth'] = null_empty($post['antenaAzimuth'][$rf_key]);
        $this->core->set['electrical_tilt'] = null_empty($post['electricalTilt'][$rf_key]);
        $this->core->set['mechanical_tilt'] = null_empty($post['mechanicalTilt'][$rf_key]);
        $this->core->set['beamwidth'] = null_empty($post['beamwidth'][$rf_key]);
        $this->core->set['beamwidth_value'] = $post['beamwidthValue'][$rf_key];
        $this->core->set['dimension_long'] = null_empty($post['dimensionLong'][$rf_key]);
        $this->core->set['dimension_height'] = null_empty($post['dimensionHeight'][$rf_key]);
        $this->core->set['dimension_width'] = null_empty($post['dimensionWidth'][$rf_key]);
        $this->core->set['height'] = null_empty($post['height'][$rf_key]);
        $this->core->set['active'] = 1;
        $this->core->set['equipment_id'] = $equipment_id;
        $this->core->set['updated_by'] = $updated_by;
        $this->core->set['updated_on'] = $updated_on;

        $this->core->save('t_mtr_site_l_equipment_bts', array('equipment_bts_id' => $rf_val));

        } else {

        $this->core->set['type'] = $post['typeRF'][$rf_key];
        $this->core->set['cell'] = $post['cell'][$rf_key];
        $this->core->set['frequency'] = null_empty($post['antenaFrequency'][$rf_key]);
        $this->core->set['ant_model'] = null_empty($post['antenaModel'][$rf_key]);
        $this->core->set['azimuth'] = null_empty($post['antenaAzimuth'][$rf_key]);
        $this->core->set['electrical_tilt'] = null_empty($post['electricalTilt'][$rf_key]);
        $this->core->set['mechanical_tilt'] = null_empty($post['mechanicalTilt'][$rf_key]);
        $this->core->set['beamwidth'] = null_empty($post['beamwidth'][$rf_key]);
        $this->core->set['beamwidth_value'] = $post['beamwidthValue'][$rf_key];
        $this->core->set['dimension_long'] = null_empty($post['dimensionLong'][$rf_key]);
        $this->core->set['dimension_height'] = null_empty($post['dimensionHeight'][$rf_key]);
        $this->core->set['dimension_width'] = null_empty($post['dimensionWidth'][$rf_key]);
        $this->core->set['height'] = null_empty($post['height'][$rf_key]);
        $this->core->set['active'] = 1;
        $this->core->set['equipment_id'] = $equipment_id;
        $this->core->set['created_by'] = $created_by;
        $this->core->set['created_on'] = $created_on;

        $this->core->save('t_mtr_site_l_equipment_bts');
        }
        }
        }

        // save MW, but soft delete first
        $this->core->update('t_mtr_site_l_equipment_mw', array('active' => 0), array('equipment_id' => $equipment_id, 'site_id_ne' => $post['siteId']));

        if (@is_array($post['mwId'])) {
        foreach ($post['mwId'] as $mw_key => $mw_val) {
        // edit old one
        if ( $this->core->view_row('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => null_empty($mw_val))) ) {

        $this->core->set['type'] = $post['typeTrans'][$mw_key];
        $this->core->set['ip_address'] = $post['ipAddress'][$mw_key];
        $this->core->set['site_id_ne'] = $post['siteId'];
        $this->core->set['site_id_fe'] = null_empty($post['farEnd'][$mw_key]);
        $this->core->set['link_code_id'] = null_empty($post['linkId'][$mw_key]);
        $this->core->set['ant_model'] = null_empty($post['antModel'][$mw_key]);
        $this->core->set['frequency'] = null_empty($post['mwFrequency'][$mw_key]);
        $this->core->set['capacity'] = null_empty($post['mwCapacity'][$mw_key]);
        $this->core->set['equipment_model'] = $post['equipmentModel'][$mw_key];
        $this->core->set['bandwidth'] = $post['bandwidth'][$mw_key];
        $this->core->set['protection_config'] = $post['protectionConfig'][$mw_key];
        $this->core->set['tx_power'] = $post['txPower'][$mw_key];
        $this->core->set['ant_diameter'] = $post['antDiameter'][$mw_key];
        $this->core->set['number_of_channel'] = $post['numberOfChannel'][$mw_key];
        $this->core->set['active'] = 1;
        $this->core->set['equipment_id'] = $equipment_id;
        $this->core->set['updated_by'] = $updated_by;
        $this->core->set['updated_on'] = $updated_on;

        $this->core->save('t_mtr_site_l_equipment_mw', array('equipment_mw_id' => $mw_val));

        // add new one
        } else {
        // #1
        $this->core->set['type'] = $post['typeTrans'][$mw_key];
        $this->core->set['ip_address'] = $post['ipAddress'][$mw_key];
        $this->core->set['site_id_ne'] = $post['siteId'];
        $this->core->set['site_id_fe'] = null_empty($post['farEnd'][$mw_key]);
        $this->core->set['link_code_id'] = null_empty($post['linkId'][$mw_key]);
        $this->core->set['ant_model'] = null_empty($post['antModel'][$mw_key]);
        $this->core->set['frequency'] = null_empty($post['mwFrequency'][$mw_key]);
        $this->core->set['capacity'] = null_empty($post['mwCapacity'][$mw_key]);
        $this->core->set['equipment_model'] = $post['equipmentModel'][$mw_key];
        $this->core->set['bandwidth'] = $post['bandwidth'][$mw_key];
        $this->core->set['protection_config'] = $post['protectionConfig'][$mw_key];
        $this->core->set['tx_power'] = $post['txPower'][$mw_key];
        $this->core->set['ant_diameter'] = $post['antDiameter'][$mw_key];
        $this->core->set['number_of_channel'] = $post['numberOfChannel'][$mw_key];
        $this->core->set['active'] = 1;
        $this->core->set['equipment_id'] = $equipment_id;
        $this->core->set['created_by'] = $created_by;
        $this->core->set['created_on'] = $created_on;

        $this->core->save('t_mtr_site_l_equipment_mw');

        $last_id = $this->db->insert_id();

        $this->core->update('t_mtr_site_l_equipment_mw', array('pair_id' => $last_id), array('equipment_mw_id' => $last_id));

        // #2
        $near_end = $post['siteId'];
        $far_end = null_empty($post['farEnd'][$mw_key]);

        $result = $this->db
        ->query("SELECT s1.vendor_id AS ne, s2.vendor_id AS fe FROM  t_mtr_site s1, t_mtr_site s2 WHERE s1.site_id = {$near_end} AND s2.site_id = {$far_end}")
        ->row_array();

        $link_id = $result['fe'].'#'.$result['ne'].'#02';

        $this->core->set['type'] = $post['typeTrans'][$mw_key];
        $this->core->set['ip_address'] = $post['ipAddress'][$mw_key];
        $this->core->set['site_id_ne'] = null_empty($post['farEnd'][$mw_key]);
        $this->core->set['site_id_fe'] = $post['siteId'];
        $this->core->set['link_code_id'] = $link_id;
        $this->core->set['ant_model'] = NULL;
        $this->core->set['frequency'] = NULL;
        $this->core->set['capacity'] = NULL;
        $this->core->set['equipment_model'] = $post['equipmentModel'][$mw_key];
        $this->core->set['bandwidth'] = $post['bandwidth'][$mw_key];
        $this->core->set['protection_config'] = $post['protectionConfig'][$mw_key];
        $this->core->set['tx_power'] = $post['txPower'][$mw_key];
        $this->core->set['ant_diameter'] = $post['antDiameter'][$mw_key];
        $this->core->set['number_of_channel'] = $post['numberOfChannel'][$mw_key];
        $this->core->set['active'] = 1;
        $this->core->set['equipment_id'] = $equipment_id;
        $this->core->set['created_by'] = $created_by;
        $this->core->set['created_on'] = $created_on;

        $this->core->save('t_mtr_site_l_equipment_mw');

        $last_id2 = $this->db->insert_id();

        $this->core->update('t_mtr_site_l_equipment_mw', array('pair_id' => $last_id), array('equipment_mw_id' => $last_id2));
        }
        }
        }

        $json = array('error' => FALSE, 'message' => 'Equipment data updated.');

        $this->session->set_flashdata('success', $json['message']);
        } else {
        $json = array('error' => TRUE, 'message' => $error);
        } */

      echo json_encode($json);
      exit;
    }

    $this->_equipment_form($site_id);
  }

  public function view($site_id = 0) {
    $this->_check_valid_loggedin();

    if (!$this->mmenu->cekPermission($this->_partials['menu'], 'menu_view')) {
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }

    $site_id = (int) $this->core->decode($site_id);

    $data['equipment'] = $this->db
            ->query("
					SELECT site.site_id, site.site_code, site.site_code_old, site.site_name, site.vendor_id, reg.region_name
					FROM t_mtr_site site
					LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
					WHERE site.site_id = {$site_id}
				")
            ->row_array();

    if ($this->core->is_ajax()) {

      if ($this->input->get('load_content') == 'BTS_INFO_VIEW') {

        $bts_info = $this->db
                ->query("
						SELECT *,
							f_get_lookup_name('bts_morphology', morphology) AS morphology,
							f_get_lookup_name('bts_brand', bts_brand) AS bts_brand,
							f_get_lookup_name('bts_mode', bts_mode) AS bts_mode,
							f_get_lookup_name('bts_type', bts_type) AS bts_type,
                            f_get_lookup_name('bts_system', bts_system) AS bts_system,
                            f_get_lookup_name('transmission_polarization', transmitter_polarization) AS transmitter_polarization
						FROM t_mtr_site_l_equipment_btsinfo WHERE site_id = {$site_id} AND active = 1
						ORDER BY bts_id ASC
					")
                ->result_array();
//die($this->db->last_query());
        $row = $this->db->query("SELECT vendor_id FROM t_mtr_site WHERE site_id = {$site_id}")->row_array();
        $vendor_ids = explode(';', $row['vendor_id']);

        $rows = array();
        foreach ($vendor_ids as $key => $val) {
          $bts_info[$key]['vendor_id'] = trim($val);

          $rows[] = $bts_info[$key];
        }

        $data['bts_info'] = $rows;

        echo $this->parser->parse('partials/content_equipment_btsinfo_view', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'RF_VIEW') {

        $data['carrier'] = $this->equipment_model->get_equipment_carrier($site_id);
        $data['rf'] = $this->equipment_model->get_equipment_rf($site_id);

        echo $this->parser->parse('partials/content_equipment_rf_view', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'TRANSMISSION_VIEW') {

        $data['transmission'] = array();

        $results1 = $this->db
                ->query("
						SELECT mw.equipment_mw_id, mw.site_id_ne, mw.site_id_fe, mw.pair_id, mw.link_code_id, site.site_code, mw.ip_address, mw.ant_model, mw.ketinggian, mw.ant_diameter, mw.ant_diameter,
							mw.azimuth, mw.equipment_model, mw.polarization, mw.path_length, mw.tx_frequency, mw.rx_frequency, mw.power, mw.sd_ant_model, mw.sd_ant_height, mw.frequency_used, lo1.lookup_desc AS config,
							mw.sd_ant_diameter, mw.req_capacity, mw.inst_capacity, mw.used_capacity, mw.treshold, mw.plan_rsl, mw.actual_rsl, mw.fm, mw.availability
						FROM t_mtr_site_l_equipment_mw mw
						LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_ne
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = mw.config::VARCHAR AND lo1.lookup_key = 'transmission_config_hop' AND lo1.active = 1
						WHERE mw.site_id_ne = {$site_id} AND mw.active = 1
						ORDER BY mw.pair_id ASC
					")
                ->result_array();

        $a = array();
        foreach ($results1 as $res1) {
          $res2 = $this->db
                  ->query("
							SELECT mw.equipment_mw_id, mw.site_id_ne, mw.site_id_fe, mw.pair_id, mw.link_code_id, site.site_code, mw.ip_address, mw.ant_model, mw.ketinggian, mw.ant_diameter, mw.ant_diameter,
								mw.azimuth, mw.equipment_model, mw.polarization, mw.path_length, mw.tx_frequency, mw.rx_frequency, mw.power, mw.sd_ant_model, mw.sd_ant_height, mw.frequency_used, lo1.lookup_desc AS config,
								mw.sd_ant_diameter, mw.req_capacity, mw.inst_capacity, mw.used_capacity, mw.treshold, mw.plan_rsl, mw.actual_rsl, mw.fm, mw.availability
							FROM t_mtr_site_l_equipment_mw mw
							LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_ne
							LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = mw.config::VARCHAR AND lo1.lookup_key = 'transmission_config_hop' AND lo1.active = 1
							WHERE mw.site_id_ne = " . $res1['site_id_fe'] . " AND mw.pair_id = " . $res1['pair_id'] . " AND mw.active = 1
							ORDER BY mw.pair_id ASC
						")
                  ->row_array();

          $a[] = array('ne' => $res1, 'fe' => $res2);
        }

        $data['transmission'] = $a;
        $data['site_class_transm'] = $this->db->query("SELECT site_class_transm FROM t_mtr_site WHERE site_id = {$site_id}")->row_array();

        echo $this->parser->parse('partials/content_equipment_transmission_view', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'GPS_VIEW') {

        $data['gps'] = $this->equipment_model->get_equipment_gps($site_id);

        echo $this->parser->parse('partials/content_equipment_gps_view', $data);
        exit;
      }
    }

    $data['content_id'] = 'equipment_view_view';

    $this->load->view('template/smashing', $data);
  }

  private function _equipment_form($site_id = 0) {
    $data = array();

    if ($this->uri->segment(3) == 'edit') {

      $data['equipment'] = $this->db
              ->query("
					SELECT site.site_id, site.site_code, site.site_code_old, site.site_name, site.vendor_id, reg.region_name
					FROM t_mtr_site site
					LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
					WHERE site.site_id = {$site_id}
				")
              ->row_array();
    }


    $where = '';
    if (count($this->_regions) > 0) {
      $where = "AND site.region_id IN (" . implode(', ', $this->_regions) . ")";
    }

    $results = $this->db
            ->query("
				SELECT btsinfo.site_id FROM t_mtr_site_l_equipment_btsinfo btsinfo LEFT JOIN t_mtr_site site ON site.site_id = btsinfo.site_id WHERE btsinfo.active = 1 {$where}
				UNION
				SELECT carr.site_id FROM t_mtr_site_l_equipment_carrier carr LEFT JOIN t_mtr_site site ON site.site_id = carr.site_id WHERE carr.active = 1 {$where}
				UNION
				SELECT gps.site_id FROM t_mtr_site_l_equipment_gps gps LEFT JOIN t_mtr_site site ON site.site_id = gps.site_id {$where}
				UNION
				SELECT mw.site_id_ne FROM t_mtr_site_l_equipment_mw mw LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_ne WHERE mw.active = 1 {$where}
				UNION
				SELECT rf.site_id FROM t_mtr_site_l_equipment_rf rf LEFT JOIN t_mtr_site site ON site.site_id = rf.site_id WHERE rf.active = 1 {$where}
			")
            ->result_array();

    $site_ids = array();
    foreach ($results as $res) {
      //$site_ids[] = $res['site_id'];
    }
    sort($site_ids);

    $data['not_appear_siteid'] = $this->core->encode(implode(',', $site_ids));

    $data['content_id'] = 'equipment_form_view';

    $this->load->view('template/smashing', $data);
  }

  public function view_all($equipment_id = 0) {
    $this->_check_valid_loggedin();

    $equipment_id = (int) $this->core->decode($equipment_id);

    $data['equipment'] = '';

    if ($equipment = $this->equipment_model->get_equipment($equipment_id)) {

      $data['equipment'] = $equipment;
    } else {
      show_404();
    }

    $this->theme->view('equipment_viewall_view', $data);
  }

  private function _check_valid_loggedin() {
    if (!$this->session->userdata('is_login')) {

      if ($this->core->is_ajax()) {

        echo json_encode(array(
            'loggedin' => FALSE,
            'message' => 'You are not logged in. You will be redirected to login page.',
            'redirect_url' => site_url('/login')
        ));
        exit;
      } else {
        redirect('/login');
      }
    }
  }

  private function _is_field_empty($field) {
    if (@is_array($field)) {
      $i = 0;
      foreach ($field as $arr) {
        if (@is_array($arr)) {
          foreach ($arr as $value) {
            if (trim($value) == '' || (trim($value) != '' && $value == '0') || (trim($value) != '' && $value == '0.00')) {
              $i++;
            }
          }
        } else {
          if (trim($arr) == '' || (trim($arr) != '' && $arr == '0') || (trim($arr) != '' && $arr == '0.00')) {
            $i++;
          }
        }
      }

      if ($i > 0) {
        return TRUE;
      } else {
        return FALSE;
      }
    } else {
      if (trim($field) == '' || (trim($field) != '' && $field == '0') || (trim($field) != '' && $field == '0.00')) {
        return TRUE;
      } else {
        return FALSE;
      }
    }
  }

  public function bts_load_import_form() {
    $data['title'] = 'Import Equipment BTS';

    $this->load->view('partials/content_equipment_btsinfo_import', $data);
    //echo $this->parser->parse('partials/content_equipment_btsinfo_import', $data);
  }

  public function bts_read_import_file() {
    if (isset($_FILES['file_app'])) {
      if ($_FILES['file_app']['name'] != '') {
        $file = explode('.', $_FILES['file_app']['name']);
        $length = count($file);
        if ($file[$length - 1] == 'xlsx' || $file[$length - 1] == 'xls') {
          $tmp = $_FILES['file_app']['tmp_name'];
          require_once APPPATH . '/libraries/PHPExcel.php';
          require_once APPPATH . '/libraries/PHPExcel/IOFactory.php';
          $this->load->library('excel_new');
          $read = IOFactory::createReaderForFile($tmp);
          $read->setReadDataOnly(true);
          $excel = $read->load($tmp);
          $sheet = $excel->setActiveSheetIndexByName('SITES');
          $max_row = $sheet->getHighestRow();
          $max_col = range('A', 'R');

          $field = array();

          foreach ($max_col as $key => $column) {
            $field[$key] = strtolower($sheet->getCell($column . '2')->getCalculatedValue());
          }

//          $this->db->trans_begin();
//          $vendor_ids = array();
//          $site_id = 0;
          $response = array();
          for ($i = 3; $i <= $max_row; $i++) {
//            $data_bts = array();
            foreach ($max_col as $k => $column) {
              $cellValue = $sheet->getCell($column . $i)->getCalculatedValue();
              $data_bts[$field[$k]] = $cellValue;
            }
            
            array_push($response, $data_bts);
            
            //Check vendor id is used?
//            $sql = "SELECT site_code FROM t_mtr_site WHERE site_id != {$data_bts['site_id']} AND vendor_id LIKE '%{$data_bts['vendor_id']}%'";
//            $site = $this->db->query($sql)->row();
//            if (!empty($site)) {
//              $response = array(
//                  'status' => 'error',
//                  'message' => "Import BTS Info failed, Vendor ID {$data_bts['vendor_id']} was used by SITE ID {$site->site_code}."
//              );
//              echo json_encode($response);
//              exit();
//            }

//            $bts_info = $this->equipment_model->get_bts_info(array('vendor_id' => (string) $data_bts['vendor_id']));
//            if (empty($bts_info)) {
//              $this->mglobal->saveData('t_mtr_site_l_equipment_btsinfo', $data_bts);
//            } else {
//              $this->equipment_model->update_bts_info($data_bts, array('site_id', 'vendor_id'));
//            }

            //Collect Vendor ID
//            if ($site_id == $data_bts['site_id']) {
//              $vendor_ids[] = $data_bts['vendor_id'];
//            } else {
//              $vendor_ids = array();
//              $vendor_ids[] = $data_bts['vendor_id'];
//              $site_id = $data_bts['site_id'];
//            }

            //Update Vendor ID
//            $site = $this->db->get_where('t_mtr_site', array('site_id' => $data_bts['site_id']))->row();
//            $site_vendor = explode(';', $site->vendor_id);
//            $data_vendor = array_unique(array_merge($site_vendor, $vendor_ids));
//            $this->db->update('t_mtr_site', array('vendor_id' => implode(';', $data_vendor)), array('site_id' => $data_bts['site_id']));
          }

          //die($this->db->last_query());
//          if ($this->db->trans_status() == TRUE) {
//            $this->db->trans_commit();
//            $response = array(
//                'status' => 'success',
//                'message' => 'Import BTS Info success.'
//            );
//          } else {
//            $response = array(
//                'status' => 'error',
//                'message' => 'Import BTS Info failed.'
//            );
//          }

          echo json_encode($response);
        } else {
          $response = array('type' => 'error', 'message' => 'File type is not allowed.');
          echo json_encode($response);
        }
      } else {
        $response = array('type' => 'error', 'message' => 'File cannot be empty.');
        echo json_encode($response);
      }
    }
  }
  
  public function bts_read_import_file_multisite() {
    if (isset($_FILES['file_app'])) {
      if ($_FILES['file_app']['name'] != '') {
        $file = explode('.', $_FILES['file_app']['name']);
        $length = count($file);
        if ($file[$length - 1] == 'xlsx' || $file[$length - 1] == 'xls') {
          $tmp = $_FILES['file_app']['tmp_name'];
          require_once APPPATH . '/libraries/PHPExcel.php';
          require_once APPPATH . '/libraries/PHPExcel/IOFactory.php';
          $this->load->library('excel_new');
          $read = IOFactory::createReaderForFile($tmp);
          $read->setReadDataOnly(true);
          $excel = $read->load($tmp);
          $sheet = $excel->setActiveSheetIndexByName('SITES');
          $max_row = $sheet->getHighestRow();
          $max_col = range('A', 'S');

          $field = array();

          foreach ($max_col as $key => $column) {
            $field[$key] = strtolower($sheet->getCell($column . '2')->getCalculatedValue());
          }

          $response = array();
          for ($i = 3; $i <= $max_row; $i++) {
            foreach ($max_col as $k => $column) {
              $cellValue = $sheet->getCell($column . $i)->getCalculatedValue();
              $data_bts[$field[$k]] = $cellValue;
            }
            
            array_push($response, $data_bts);
          }

          echo json_encode($response);
        } else {
          $response = array('type' => 'error', 'message' => 'File type is not allowed.');
          echo json_encode($response);
        }
      } else {
        $response = array('type' => 'error', 'message' => 'File cannot be empty.');
        echo json_encode($response);
      }
    }
  }

  public function get_bts_detail($param = 0) {
    if ($this->input->is_ajax_request()) {
      error_reporting(0);
      $data['bts_info'] = $this->equipment_model->get_bts_info($param);
      $data['bts_tdd'] = $this->equipment_model->get_bts_tdd(array('bts_id' => $param));
//      $data['bts_sector'] = $this->equipment_model->get_bts_sector(array('tdd_id' => $data['bts_tdd'][0]->tdd_id));
//      $rows = $this->equipment_model->get_bts_info_full($param);
//      $rows = $this->equipment_model->get_bts_tdd(array('bts_id' => $param));
//      $data['bts_tdd'] = array();
//      foreach ($rows as $key => $value) {
//        
//      }
//      die($this->db->last_query());

      $this->load->view('partials/content_equipment_btsinfo_detail', $data);
    } else {
      echo 'Forbidden access!';
    }
  }

  public function save_bts_detail() {
    if ($this->input->is_ajax_request()) {
      $this->db->trans_start();
      $data_post = $this->input->post();
      //diactivate tdd early
      $this->mglobal->saveData('t_mtr_site_l_equipment_btsinfo_tdd', array('bts_id' => $data_post['bts_id'], 'active' => 0), 'bts_id');
      //save tdd
      foreach ($data_post['tdd_no'] as $key => $value) {
        $data_tdd = array(
            'bts_id' => $data_post['bts_id'],
            'tdd_no' => $data_post['tdd_no'][$key],
            'freq' => $data_post['freq'][$key],
            'type' => $data_post['type'][$key],
            'active' => 1,
        );

        if (isset($data_post['tdd_id'][$key])) {
          $data_tdd['tdd_id'] = $data_post['tdd_id'][$key];
          $this->mglobal->saveData('t_mtr_site_l_equipment_btsinfo_tdd', $data_tdd, 'tdd_id');
          $tdd_id = $data_post['tdd_id'][$key];
        } else {
          $tdd_id = $this->mglobal->saveData('t_mtr_site_l_equipment_btsinfo_tdd', $data_tdd);
        }
        //diactivate sector early
        $this->mglobal->saveData('t_mtr_site_l_equipment_btsinfo_sector', array('tdd_id' => $tdd_id, 'active' => 0), 'tdd_id');
        //save sector
        foreach ($data_post['sector_type'][$key] as $key2 => $value) {
          $data_sector = array(
              'tdd_id' => $tdd_id,
              'sector_type' => $data_post['sector_type'][$key][$key2],
              'tilt' => $data_post['tilt'][$key][$key2],
              'active' => 1,
          );
          if (isset($data_post['sector_id'][$key][$key2])) {
            $data_sector['sector_id'] = $data_post['sector_id'][$key][$key2];
            $this->mglobal->saveData('t_mtr_site_l_equipment_btsinfo_sector', $data_sector, 'sector_id');
            $sector_id = $data_post['sector_id'][$key][$key2];
          } else {
            $sector_id = $this->mglobal->saveData('t_mtr_site_l_equipment_btsinfo_sector', $data_sector);
          }
        }
      }

      $this->db->trans_complete();

      $response = array();
      if ($this->db->trans_status() == TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Data saved successfully.'
        );
      } else {
        $response = array(
            'status' => 'error',
            'message' => 'Saving data failed, please try again or contact the administrator.'
        );
      }
      echo json_encode($response);
    } else {
      echo 'Forbidden access!';
    }
  }

}
