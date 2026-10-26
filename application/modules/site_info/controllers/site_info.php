<?php

class Site_info extends MX_Controller {

  private $_partials = array();

  public function __construct()
  {
    parent::__construct();

    $this->load->library('form_validation');
    $this->load->library('parser');

    $this->lang->load('site_info/site_info', 'indonesia');

    $this->load->helper('cookie');
    $this->load->helper('language');
    $this->load->helper('text');

    $this->load->model('site_info_model');
    $this->load->model('image_model');
    $this->load->model('mmaster');

    $uri = $this->uri->segment(1);
    $this->_partials['menu'] = $this->mmenu->getMenuId($uri);
  }

  public function index()
  {
    $this->_check_valid_loggedin();

    $data = $this->_partials;

    if ($this->core->is_ajax())
	{
      if ($this->input->get('cmd') == 'check_site_id')
	  {
        $site_id = $this->input->get('site_id');
        $site_code = $this->input->get('site_code');

        $check = $this->site_info_model->check_siteid_exist();

        $json = array();
        $error = array();

        //if ($this->_is_field_empty($site_id)) {
        //	$error[] = 'Site ID can not empty.';
        //}
        if (!$this->_is_field_empty($site_id) && $check)
		{
          $error[] = 'Site ID ' . $site_code . ' already registered.';
        }

        if (count($error) > 0)
		{
          $json = array('error' => TRUE, 'message' => $error);
        }
		else
		{
          $json = array('error' => FALSE);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'get_lookup_area_code')
	  {
        $data = array();

        if ($this->input->get('act') == 'get_data')
		{
          $json = array();

          $page = $this->input->get('page') ? $this->input->get('page') : 1;
          $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
          $offset = ($page - 1) * $rows;
          $search = $this->input->get('query');
          $sort = $this->input->get('sort') ? $this->input->get('sort') : 'area_code';
          $order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';

          $where = "WHERE active = 1 AND (";
          $where .= " UPPER(area_code) LIKE '%" . strtoupper($search) . "%'";
          $where .= " OR UPPER(description) LIKE '%" . strtoupper($search) . "%'";
          $where .= ")";

          $sql = "
						SELECT area_id, area_code, description FROM t_mtr_site_area_code
						{$where}
						ORDER BY {$sort} {$order}
					";

          $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

          $total_rows = (int) $query->row()->total;

          $sql .= " LIMIT {$rows} OFFSET {$offset}";

          $query = $this->db->query($sql);

          $json = array();

          $json['total'] = $total_rows;
          $json['rows'] = $query->result_array();
          $json['sql'] = $sql;

          echo json_encode($json);
          exit;
        }

        echo $this->parser->parse('partials/lookup_area_code', $data);
        exit;
      }

      if ($this->input->get('cmd') == 'get_area_code')
	  {
        $json = array();
        $code = $this->input->get('code');

        $res = $this->db
                ->query("
						SELECT REPLACE(site_code, SUBSTRING(site_code, 0, 5), '') AS last_id
						FROM t_mtr_site
						WHERE SUBSTRING(site_code, 0, 5) = 'S{$code}'
						ORDER BY site_code DESC
						LIMIT 1
					")
                ->row_array();

        if ($res)
		{
          $last_id = ($res['last_id'] + 1);
        }
		else
		{
          $last_id = 1;
        }

        $json['site_code'] = 'S' . $code . sprintf('%04d', $last_id);

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_MASTER_SITE')
	  {
        $json = array();

        $master_site = $this->core->view_all('t_mtr_site', array('active' => 1), 'site_id');
        $data_site = array();
        foreach ($master_site as $mtr)
		{
          $mtr['active'] = $mtr['active'] == 1 ? 'Yes' : 'No';
          $data_site[] = $mtr;
        }
        $json = $data_site;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'LOAD_DATA')
	  {
        $json = array();

        $data = $this->site_info_model->load_site_info();

        $json = $data;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_DATAGRID_SITES')
	  {
        $rows = $this->site_info_model->get_sites_list();

        echo json_encode($rows);
        exit;
      }

      if ($this->input->get('cmd') == 'DELETE_SITE')
	  {
        $json = array();

        $id = (int) $this->core->decode($this->input->get('id'));

        // soft delete
        $this->core->update('t_mtr_site', array('active' => 0), array('site_id' => $id));

        $json = array('message' => 'Site data has been deleted successfully.');

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'LOAD_DATA_SITE_CANDIDATE')
	  {
        $json = array();
        $site_id = $this->input->get('site_id');

        $data = $this->site_info_model->load_site_candidate($site_id);

        $json = $data;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'LOAD_DATA_PROBLEM')
	  {
        $json = array();

        $data = $this->site_info_model->load_problem();

        $json = $data;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'LOAD_DATA_EQUIPMENT')
	  {
        $json = array();

        $data = $this->site_info_model->load_equipment_list();

        $json = $data;

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_DATAGRID_PLAN_RF')
	  {
        $json = array();

        $site_id = (int) $this->core->decode($this->input->get('site_id'));
        $site_code = $this->db->query("SELECT site_code FROM t_mtr_site WHERE site_id = {$site_id}")->row_array();
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
        $offset = ($page - 1) * $rows;
        $search = $this->input->get('query');
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'site_plan_rf_id';
        $order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';

        $where = "WHERE rf.active = 1 AND plan.nom_site_code = '" . $site_code['site_code'] . "'";
        $where .= " AND (UPPER(lo.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR UPPER(CAST(rf.height AS VARCHAR)) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR UPPER(CAST(rf.weight AS VARCHAR)) LIKE '%" . strtoupper($search) . "%')";

        $sql = "
					SELECT rf.height, rf.dimension_l, rf.dimension_w, rf.dimension_h, rf.weight, lo.lookup_desc AS antenna
					FROM t_mtr_site_plan_rf rf
					LEFT JOIN t_mtr_site_plan plan ON plan.nom_site_id = rf.site_plan_id
					LEFT JOIN t_mtr_lookup lo ON CAST(lo.lookup_value AS INTEGER) = rf.antenna AND lo.lookup_key = 'antenna_type'
					{$where}
					ORDER BY  {$sort} {$order}
				";

        $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

        $total_rows = (int) $query->row()->total;

        $sql .= " LIMIT {$rows} OFFSET {$offset}";

        $query = $this->db->query($sql);

        $json['total'] = $total_rows;
        $json['rows'] = $query->result_array();

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_DATAGRID_PLAN_TRANSM')
	  {
        $json = array();

        $site_id = (int) $this->core->decode($this->input->get('site_id'));
        $site_code = $this->db->query("SELECT site_code FROM t_mtr_site WHERE site_id = {$site_id}")->row_array();
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
        $offset = ($page - 1) * $rows;
        $search = $this->input->get('query');
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'site_plan_mw_id';
        $order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'ASC';

        $where = "WHERE tr.active = 1 AND plan.nom_site_code = '" . $site_code['site_code'] . "'";
        $where .= " AND (UPPER(lo1.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR UPPER(lo2.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
        $where .= " OR UPPER(site.site_code) LIKE '%" . strtoupper($search) . "%')";

        $sql = "
					SELECT plan.nom_site_code AS ne, site.site_code AS fe, lo1.lookup_desc AS link_type, tr.ant_height, tr.ant_diameter, tr.frequency, lo2.lookup_desc AS equip_model, tr.capacity, tr.owner
					FROM t_mtr_site_plan_mw tr
					LEFT JOIN t_mtr_site_plan plan ON plan.nom_site_id = tr.site_plan_id
					LEFT JOIN t_mtr_lookup lo1 ON CAST(lo1.lookup_value AS INTEGER) = tr.link_type AND lo1.lookup_key = 'transmission_link_type'
					LEFT JOIN t_mtr_lookup lo2 ON CAST(lo2.lookup_value AS INTEGER) = tr.link_type AND lo2.lookup_key = 'equipment_model'
					LEFT JOIN t_mtr_site site ON site.site_id = tr.site_id_fe
					{$where}
					ORDER BY  {$sort} {$order}
				";

        $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

        $total_rows = (int) $query->row()->total;

        $sql .= " LIMIT {$rows} OFFSET {$offset}";

        $query = $this->db->query($sql);

        $json['total'] = $total_rows;
        $json['rows'] = $query->result_array();

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_TP_INFO')
	  {
        $name_id = $this->input->get('name_id');

        $json = $this->db
                ->query("
						SELECT tp.tp_name_group, lok.nama AS kabupaten
						FROM t_mtr_tower_provider tp
						LEFT JOIN t_mtr_lokasi lok ON lok.lokasi_id = tp.city_id AND UPPER(lok.jenis) = 'KB'
						WHERE tp.tp_company_name  = '{$name_id}' AND (tp.tp_name_group IS NOT NULL AND lok.nama IS NOT NULL)
						LIMIT 1
					")
                ->row_array();

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_KABUPATEN')
	  {
        $propinsi_id = (int) $this->input->get('propinsi_id');

        $json['province'] = array();

        $kabupaten = $this->core->view_all('t_mtr_lokasi', array('jenis' => 'KB', 'parent_id' => $propinsi_id), 'nama');
        foreach ($kabupaten as $kab)
		{
          $json['province'][] = array('id' => $kab['child_id'], 'name' => $kab['nama']);
        }

        $json['island'] = $this->db
                ->query("
						SELECT pul.island_name
						FROM t_mtr_lokasi lok
						LEFT JOIN t_mtr_lokasi_pulau pul ON pul.island_id = lok.island_id AND pul.active = 1
						WHERE lok.jenis = 'P' AND lok.lokasi_id = {$propinsi_id}
					")
                ->row_array();

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_KECAMATAN')
	  {
        $kabupaten_id = (int) $this->input->get('kabupaten_id');

        $json = array();

        $kecamatan = $this->core->view_all('t_mtr_lokasi', array('jenis' => 'KC', 'parent_id' => $kabupaten_id, 'status' => 1), 'nama');
        foreach ($kecamatan as $kec)
		{
          $json[] = array('id' => $kec['child_id'], 'name' => $kec['nama']);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_CLUSTER')
	  {
        $region_id = (int) $this->input->get('region_id');

        $json['ops'] = array();
        $json['net'] = array();
        $json['com'] = array();

        $clusters = $this->core->view_all('t_mtr_cluster_ops', array('region_id' => $region_id, 'cluster_ops_id !=' => 0, 'status' => 1), 'cluster_name');
        foreach ($clusters as $cluster)
		{
          $cluster_code = '';
          if ($cluster['cluster_code'] != '')
		  {
            $cluster_code = ' [' . $cluster['cluster_code'] . ']';
          }
          $json['ops'][] = array('id' => $cluster['cluster_ops_id'], 'name' => $cluster['cluster_name'] . $cluster_code);
        }

        $clusters_network = $this->core->view_all('t_mtr_cluster_net', array('region_id' => $region_id, 'cluster_net_id !=' => 0, 'status' => 1), 'cluster_name');
        foreach ($clusters_network as $cluster)
		{
          $cluster_code = '';
          if ($cluster['cluster_code'] != '')
		  {
            $cluster_code = ' [' . $cluster['cluster_code'] . ']';
          }

          $json['net'][] = array('id' => $cluster['cluster_net_id'], 'name' => $cluster['cluster_name'] . $cluster_code);
        }

        $clusters_commercial = $this->core->view_all('t_mtr_cluster_com', array('region_id' => $region_id, 'cluster_com_id !=' => 0, 'status' => 1), 'cluster_name');
        foreach ($clusters_commercial as $cluster)
		{
          $cluster_code = '';
          if ($cluster['cluster_code'] != '')
		  {
            $cluster_code = ' [' . $cluster['cluster_code'] . ']';
          }

          $json['com'][] = array('id' => $cluster['cluster_com_id'], 'name' => $cluster['cluster_name'] . $cluster_code);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'get_lookup_tower_provider')
	  {
        $data = array();

        if ($this->input->get('act') == 'get_data')
		{
          $json = array();

          $page = $this->input->get('page') ? $this->input->get('page') : 1;
          $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
          $offset = ($page - 1) * $rows;
          $search = $this->input->get('query');
          $sort = $this->input->get('sort') ? $this->input->get('sort') : 'ord';
          $order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';

          $region = array();
          foreach ($this->session->userdata['user_region'] as $reg)
		  {
            if ($reg['region_id'] != '')
			{
              $region[] = $reg['region_id'];
            }
          }

          $where = "WHERE tp.tp_code IS NOT NULL AND tp.active = 1 AND (";
          $where .= " UPPER(tp.tp_code) LIKE '%" . strtoupper($search) . "%'";
          $where .= " OR UPPER(tp_co.company_name) LIKE '%" . strtoupper($search) . "%'";
          $where .= " OR UPPER(lo1.lookup_desc) LIKE '%" . strtoupper($search) . "%'";
          $where .= " OR UPPER(tp.address) LIKE '%" . strtoupper($search) . "%'";
          $where .= " OR UPPER(loc1.nama) LIKE '%" . strtoupper($search) . "%'";
          $where .= " OR UPPER(loc2.nama) LIKE '%" . strtoupper($search) . "%'";
          $where .= " OR UPPER(reg.region_name) LIKE '%" . strtoupper($search) . "%'";
          $where .= ")";

          if (count($region) > 0)
		  {
            //$where .= " AND tp.region_id IN (".implode(', ', $region).") ";
          }

          $sql = "
						SELECT
							tp.tp_id, tp.tp_code, tp_co.company_name, tp_grp.tpgroup_name, lo1.lookup_desc AS site_category, tp.address, reg.region_name AS region, loc1.nama AS province, loc2.nama AS city,
							CASE WHEN tp.updated_on IS NOT NULL THEN tp.updated_on ELSE tp.created_on END AS ord
						FROM t_mtr_tower_provider tp
						LEFT JOIN t_mtr_tp_company tp_co ON tp_co.company_id = tp.company_id
						LEFT JOIN t_mtr_tp_company_group tp_grp ON tp_grp.tpgroup_id = tp_co.tpgroup_id
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = tp.site_category::VARCHAR AND lo1.lookup_key = 'tp_category' AND lo1.active = 1
						LEFT JOIN t_mtr_region reg ON reg.region_id = tp.region_id AND reg.status = 1
						LEFT JOIN t_mtr_lokasi loc1 ON loc1.lokasi_id = tp.propinsi_id AND loc1.jenis = 'P' AND loc1.status = 1
						LEFT JOIN t_mtr_lokasi loc2 ON loc2.lokasi_id = tp.city_id AND loc2.jenis = 'KB' AND loc2.status = 1
						{$where}
						ORDER BY {$sort} {$order}
					";

          $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

          $total_rows = (int) $query->row()->total;

          $sql .= " LIMIT {$rows} OFFSET {$offset}";

          $query = $this->db->query($sql);

          $json = array();

          $json['total'] = $total_rows;
          $json['rows'] = $query->result_array();

          $sql = str_replace("\t", '', $sql);
          $sql = str_replace("\n", ' ', $sql);
          $json['sql'] = $sql;

          echo json_encode($json);
          exit;
        }

        echo $this->parser->parse('partials/lookup_tp', $data);
        exit;
      }
    }

    if ($this->input->get('cmd') == 'GET_MAP')
	{
      $data['cmd'] = $this->input->get('cmd');

      echo $this->parser->parse('partials/siteinfo_map', $data);
      exit;
    }

    if ($this->input->get('cmd') == 'GET_MAP_NOMINAL')
	{
      $data['cmd'] = $this->input->get('cmd');

      echo $this->parser->parse('partials/siteinfo_map', $data);
      exit;
    }

    if ($this->input->get('cmd') == 'GET_MAP_ACTUAL')
	{
      $data['cmd'] = $this->input->get('cmd');

      echo $this->parser->parse('partials/siteinfo_map', $data);
      exit;
    }

    if ($this->input->get('cmd') == 'GET_LOOKUP_SITE')
	{
      $data = array();

      $data['not_appear_siteid'] = '';

      if ($site_ids = $this->input->get('not_appear_siteid'))
	  {
        $data['not_appear_siteid'] = $site_ids;
      }

      echo $this->parser->parse('partials/lookup_sites', $data);
      exit;
    }

    if ($this->input->get('cmd') == 'GET_LOOKUP_SITE_DATA')
	{
      $json = array();

      $page = $this->input->get('page') ? $this->input->get('page') : 1;
      $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
      $offset = ($page - 1) * $rows;
      $search = $this->input->get('query');
      $sort = $this->input->get('sort') ? $this->input->get('sort') : 'ord';
      $order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
      $not_appear_siteid = $this->input->get('not_appear_siteid');

      $region = array();
      foreach ($this->session->userdata['user_region'] as $reg)
	  {
        if ($reg['region_id'] != '')
		{
          $region[] = $reg['region_id'];
        }
      }

      $where = "WHERE s.active = 1 AND s.site_active = 1 AND (";
      $where .= " UPPER(s.site_code) LIKE '%" . strtoupper($search) . "%'";
      $where .= " OR UPPER(s.site_code_old) LIKE '%" . strtoupper($search) . "%'";
      $where .= " OR UPPER(s.site_name) LIKE '%" . strtoupper($search) . "%'";
      $where .= " OR UPPER(s.vendor_id) LIKE '%" . strtoupper($search) . "%'";
      $where .= " OR UPPER(r.region_name) LIKE '%" . strtoupper($search) . "%'";
      $where .= ")";

      if (count($region) > 0)
	  {
        $where .= " AND s.region_id IN (" . implode(', ', $region) . ")";
      }

      if ($not_appear_siteid != '')
	  {
        $where .= " AND s.site_id NOT IN ({$not_appear_siteid})";
      }

      $sql = "
				SELECT
					s.site_id, s.site_code, s.site_code_old, s.site_name, s.vendor_id, r.region_name,
					CASE WHEN s.updated_on IS NOT NULL THEN s.updated_on ELSE s.created_on END AS ord
				FROM t_mtr_site s 
				LEFT JOIN t_mtr_region r ON r.region_id = s.region_id
				{$where}
				ORDER BY {$sort} {$order}
			";

      $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

      $total_rows = (int) $query->row()->total;

      $sql .= " LIMIT {$rows} OFFSET {$offset}";

      $query = $this->db->query($sql);

      $json = array();

      $json['total'] = $total_rows;
      $json['rows'] = $query->result_array();
      $json['sql'] = $sql;

      echo json_encode($json);
      exit;
    }

    $data['content_id'] = 'site_info_list';

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

      if ($this->input->get('cmd') == 'GET_CME_PARTNER')
	  {
        $json = array();

        $key = $this->input->get('key');

        $cme_partner = $this->site_info_model->get_cme_partner($key);
        if ($cme_partner)
		{
          $json['data'] = array();
          foreach ($cme_partner as $partner)
		  {
            $json['data'][] = array(
                'id' => $partner['partner_id'],
                'value' => trim($partner['partner_name']),
            );
          }
        }
		else
		{
          $json['data'][] = array('id' => '', 'value' => 'Results not found');
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('cmd') == 'GET_TENANT')
	  {
        $json = array();

        $key = $this->input->get('key');

        $tenants = $this->site_info_model->get_tenant($key);
        if ($tenants)
		{
          $json['data'] = array();
          foreach ($tenants as $tenant)
		  {
            $json['data'][] = array(
                'id' => $tenant['tenant_id'],
                'value' => trim($tenant['tenant_name']),
            );
          }
        }
		else
		{
          $json['data'][] = array('id' => '', 'value' => 'Results not found');
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('load_content') == 'GENERAL_INFO')
	  {
        $data = array();

        echo $this->parser->parse('partials/content_general_info', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'SITAC')
	  {
        $data = array();

        echo $this->parser->parse('partials/content_sitac', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'CME')
	  {
        $data = array();

        echo $this->parser->parse('partials/content_cme', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'OPERATIONAL')
	  {
        $data = array();

        echo $this->parser->parse('partials/content_operational', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'EQUIPMENT')
	  {
        $data = array();

        echo $this->parser->parse('partials/content_equipment', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'PLAN_INFO')
	  {
        $data = array();

        echo $this->parser->parse('partials/content_plan_info', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'PHOTO_REFERENCE')
	  {
        $data = array();

        echo $this->parser->parse('partials/content_photo_reference', $data);
        exit;
      }

      if ($this->input->post('cmd') == 'ADD_GENERAL_INFO')
	  {
        $post = $this->core->get_post();

        $error = array();
        $json = array();

        if ($this->_is_field_empty($post['areaCode'])) {
          $error[] = 'Area Code can not empty.';
        }
        if ($this->_is_field_empty($post['siteIdText'])) {
          $error[] = 'Site ID can not empty.';
        }
        if (!$this->_is_field_empty($post['siteIdText']) && $this->core->view_all('t_mtr_site', array('site_code' => $post['siteIdText'], 'site_active' => 1))) {
          $error[] = 'Site ID has already registered.';
        }
        /*
		if ($this->_is_field_empty($post['oldSiteId'])) {
          $error[] = 'Reference Site ID can not empty.';
		}
        if ($this->site_info_model->check_old_site_id($post['oldSiteId'])) {
          $error[] = 'Reference Site ID has already registered.';
        }
		*/
        if ($this->_is_field_empty($post['siteName'])) {
          $error[] = 'Site Name can not empty.';
        }
        if (!$this->_is_field_empty($post['siteName']) && $this->core->view_all('t_mtr_site', array('site_name' => $post['siteName'], 'site_active' => 1))) {
          $error[] = 'Site Name has already registered.';
        }
        if ($this->_is_field_empty($post['siteType'])) {
          $error[] = 'Site Type can not empty.';
        }
        if ($this->_is_field_empty($post['siteProfile'])) {
          $error[] = 'Site Profile can not empty.';
        }
        if ($this->_is_field_empty($post['vendorId'])) {
          $error[] = 'Vendor ID can not empty.';
        }
        if (!$this->_is_field_empty($post['vendorId']) && $this->core->view_all('t_mtr_site', array('vendor_id' => $post['vendorId'], 'site_active' => 1))) {
          $error[] = 'Vendor ID has already registered.';
        }
        /* if ($this->_is_field_empty($post['vendorName'])) {
          $error[] = 'Vendor Name can not empty.';
          }
          if ($this->_is_field_empty($post['tpId'])) {
          $error[] = 'TP Site ID can not empty.';
          } */
        if ($this->_is_field_empty($post['region'])) {
          $error[] = 'Region can not unselected.';
        }

        $vendor_ids_1 = explode(';', $post['vendorId']);
        $vid = array();
        $onair = '';
        foreach ($vendor_ids_1 as $val) {
          $sql = "SELECT vendor_id, onair_offair FROM v_site_info_master_site WHERE site_code = '" . $post['siteIdText'] . "' AND vendor_id LIKE '%{$val}%' AND lower(status) = 'active' AND lower(onair_offair) = 'on air' AND site_active = 1";
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

        if (count($error) == 0) {

          $created_on = $updated_on = date('Y-m-d H:i:s');
          $created_by = $updated_by = $this->session->userdata('user_id');

          $this->core->set['area_id'] = $post['areaCode2'];
          $this->core->set['site_code'] = $post['siteIdText'];
          $this->core->set['site_code_old'] = null_empty($post['oldSiteId']);
          $this->core->set['site_name'] = $post['siteName'];
          $this->core->set['site_type'] = $post['siteType'];
          $this->core->set['site_profile'] = $post['siteProfile'];
          $this->core->set['vendor_name'] = $post['vendorName'];
          $this->core->set['vendor_id'] = $post['vendorId'];
          //$this->core->set['nom_latitude'] = null_empty($post['nominalLatitude']);
          //$this->core->set['nom_longitude'] = null_empty($post['nominalLongitude']);
          $this->core->set['latitude'] = null_empty($post['actualLatitude']);
          $this->core->set['longitude'] = null_empty($post['actualLongitude']);
          $this->core->set['site_specification'] = $post['siteSpecification'];

          $this->core->set['site_address'] = nl2br($post['siteAddress']);
          $this->core->set['propinsi_id'] = $post['propinsi'];
          $this->core->set['kabupaten'] = $post['kabupaten'];
          $this->core->set['kecamatan'] = $post['kecamatan'];
          $this->core->set['kelurahan'] = $post['kelurahan'];
          $this->core->set['kode_pos'] = $post['kodePos'];

          $this->core->set['region_id'] = $post['region'];
          $this->core->set['cluster_ops_id'] = $post['clusterOpr'];
          $this->core->set['cluster_net_id'] = $post['clusterNet'];
          $this->core->set['cluster_com_id'] = $post['clusterCom'];

          // Finance
          $this->core->set['contract_under'] = null_empty($post['contractUnder']);
          $this->core->set['company_code'] = null_empty($post['companyCode']);
          $this->core->set['cost_center'] = $post['costCenter'];
          $this->core->set['harga_sewa'] = $post['priceLease'];
          $this->core->set['price_lease_period'] = $post['priceLeasePeriod'];
          // $this->core->set['term_of_payment'] = null_empty($post['termOfPayment']);
          $this->core->set['lcd_start_date'] = null_empty($post['lcdStartDate']);
          $this->core->set['lcd_end_date'] = null_empty($post['lcdEndDate']);
          $this->core->set['lcd_renewal_status'] = null_empty($post['lcdRenewalStatus']);
          $this->core->set['lcd_renewal_remark'] = $post['lcdRenewalRemarks'];
          $this->core->set['remarks'] = $post['financeRemarks'];

          // TP
          //$this->core->set['tp_name'] = null_empty($post['tpName']);
          $this->core->set['tp_id'] = null_empty($post['tpId']);
          //$this->core->set['tp_name_group'] = null_empty($post['tpNameGroup']);
          //$this->core->set['site_category'] = null_empty($post['siteCategory']);

          $this->core->set['status'] = 1; // 1: active, 0: nom

          $this->core->set['created_on'] = $created_on;
          $this->core->set['created_by'] = $created_by;
          $this->core->set['updated_on'] = $updated_on;
          $this->core->set['updated_by'] = $updated_by;
          $this->core->set['active'] = 1;

          if (!$this->core->save('t_mtr_site')) {
            echo json_encode(array('error' => TRUE, 'message' => $this->core->error_message));
            exit;
          }

          $site_id = $this->db->insert_id();

          $json = array('error' => FALSE, 'message' => 'General Info Added.', 'site_id' => $site_id, 'site_id_enc' => $this->core->encode($site_id));
        } else {
          $json = array('error' => TRUE, 'message' => $error, 'site_id' => '');
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'ADD_SITAC') {

        $post = $this->core->get_post();

        $error = array();
        $json = array();

        if ($post['siteId'] == '') {
          $error[] = 'General Info is not complete.';
        }

        $site_id = $post['siteId'];

        if (count($error) == 0) {

          // Lahan
          $this->core->set['kepemilikan_lahan'] = $post['kepemilikanLahan'];
          $this->core->set['jenis_surat_lahan'] = $post['jenisSuratLahan'];
          $this->core->set['no_ajb'] = $post['noAJB'];
          $this->core->set['date_ajb'] = null_empty($post['dateAJB']);
          $this->core->set['no_surat_lahan'] = $post['noSuratLahan'];
          $this->core->set['luas_lahan'] = $post['luasLahan'];
          $this->core->set['akses_jalan_lbr'] = $post['aksesJalanLebar'];
          $this->core->set['akses_jalan_pjg'] = $post['aksesJalanPanjang'];
          $this->core->set['sudah_balik_nama'] = null_empty($post['sudahBalikNama']);
          $this->core->set['no_baru'] = $post['noBaru'];
          $this->core->set['lease_term'] = $post['leaseTerm'];
          $this->core->set['lease_start_date'] = null_empty($post['leaseStartDate']);
          $this->core->set['lease_finish_date'] = null_empty($post['leaseFinishDate']);
          $this->core->set['nama_pemilik_lahan'] = $post['namaPemilikLahan'];
          $this->core->set['no_telp_pemilik_lahan'] = $post['noTelpPemilikLahan'];
          $this->core->set['contact_person_lahan'] = $post['contactPersonLahan'];
          $this->core->set['no_telp_contact_person'] = $post['noTelpContactPerson'];
          $this->core->set['remarks'] = $post['spaceRemarks'];

          $this->core->set['status'] = null_empty($post['smgStatus']);
          $this->core->set['company'] = $post['smgCompany'];

          $updated_date = date('Y-m-d H:i:s');
          $updated_by = $this->session->userdata('user_id');

          $this->core->set['active'] = 1;

          if ($this->core->view_row('t_mtr_site_l_lahan', array('site_id' => $site_id))) {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_lahan', array('site_id' => $site_id));
          } else {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_lahan');
          }

          // Perijinan
          $this->core->set['iw_needed'] = null_empty($post['iwNeeded']);
          if ($post['iwNeeded'] == 1) {
            $this->core->set['iw_date'] = null_empty($post['iwDate']);
          } else {
            $this->core->set['iw_date'] = NULL;
          }
          $this->core->set['retribusi'] = null_empty($post['retribusi']);
          if ($post['retribusi'] == 1) {
            $this->core->set['biaya_retribusi'] = $post['biayaRetribusi'];
            $this->core->set['tipe_retribusi'] = $post['retributionType'];
          } else {
            $this->core->set['biaya_retribusi'] = NULL;
            $this->core->set['tipe_retribusi'] = NULL;
          }
          $this->core->set['iw_remarks'] = $post['iwRemarks'];

          $this->core->set['ho_needed'] = null_empty($post['hoNeeded']);
          if ($post['hoNeeded'] == 1) {
            $this->core->set['ho_type'] = $post['hoType'];
            $this->core->set['ho_number'] = $post['hoNumber'];
            $this->core->set['ho_date'] = null_empty($post['hoDate']);
            $this->core->set['ho_expired'] = null_empty($post['hoExpired']);
            $this->core->set['ho_remarks'] = $post['hoRemarks'];
          } else {
            $this->core->set['ho_type'] = NULL;
            $this->core->set['ho_number'] = NULL;
            $this->core->set['ho_date'] = NULL;
            $this->core->set['ho_expired'] = NULL;
            $this->core->set['ho_remarks'] = NULL;
          }

          $this->core->set['pbb_needed'] = null_empty($post['pbbNeeded']);
          if ($post['pbbNeeded'] == 1) {
            $this->core->set['nomor_pbb'] = $post['nomorPBB'];
            $this->core->set['atas_nama'] = $post['atasNama'];
            $this->core->set['jatuh_tempo'] = null_empty($post['jatuhTempo']);
            $this->core->set['nilai_pbb'] = $post['nilaiPBB'];
            $this->core->set['pic_pbb'] = $post['picPBB'];
            $this->core->set['pbb_remarks'] = $post['pbbRemarks'];
          } else {
            $this->core->set['nomor_pbb'] = NULL;
            $this->core->set['atas_nama'] = NULL;
            $this->core->set['jatuh_tempo'] = NULL;
            $this->core->set['nilai_pbb'] = NULL;
            $this->core->set['pic_pbb'] = NULL;
            $this->core->set['pbb_remarks'] = NULL;
          }

          $this->core->set['imb_needed'] = null_empty($post['imbNeeded']);
          if ($post['imbNeeded'] == 1) {
            $this->core->set['imb_type'] = $post['imbType'];
            $this->core->set['nomor_imb'] = $post['nomorIMB'];
            $this->core->set['date'] = null_empty($post['imbDate']);
            $this->core->set['expired'] = null_empty($post['imbExpired']);
            $this->core->set['imb_remarks'] = $post['imbRemarks'];
          } else {
            $this->core->set['imb_type'] = NULL;
            $this->core->set['nomor_imb'] = NULL;
            $this->core->set['date'] = NULL;
            $this->core->set['expired'] = NULL;
            $this->core->set['imb_remarks'] = NULL;
          }

          $this->core->set['rkt_needed'] = null_empty($post['rktNeeded']);
          if ($post['rktNeeded'] == 1) {
            $this->core->set['rkt_type'] = $post['rktType'];
            $this->core->set['rkt_number'] = $post['rktNumber'];
            $this->core->set['rkt_date'] = null_empty($post['rktDate']);
            $this->core->set['rkt_expired'] = null_empty($post['rktExpired']);
            $this->core->set['remarks'] = $post['remarks'];
          } else {
            $this->core->set['rkt_type'] = NULL;
            $this->core->set['rkt_number'] = NULL;
            $this->core->set['rkt_date'] = NULL;
            $this->core->set['rkt_expired'] = NULL;
            $this->core->set['remarks'] = NULL;
          }

          if ($this->core->view_row('t_mtr_site_l_perijinan', array('site_id' => $site_id))) {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_perijinan', array('site_id' => $site_id));
          } else {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_perijinan');
          }

          $json = array('error' => FALSE, 'message' => 'SITAC added.', 'site_id' => $site_id);
        } else {
          $json = array('error' => TRUE, 'message' => $error, 'site_id' => $site_id);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'ADD_CME') {

        $post = $this->core->get_post();

        $error = array();
        $json = array();

        if ($post['siteId'] == '') {
          $error[] = 'General Info is not complete.';
        }

        $site_id = $post['siteId'];

        if (count($error) == 0) {

          // Tower
          $this->core->set['kepemilikan_tower'] = $post['kepemilikanTower'];
          $this->core->set['nama_pemilik_tower'] = $post['namaPemilikTower'];
          $this->core->set['no_telp_pemilik_tower'] = $post['telpPemilikTower'];
          $this->core->set['tower_type'] = null_empty($post['towerType']);
          $this->core->set['tower_leg_no'] = null_empty($post['towerLegNo']);
          $this->core->set['tower_height'] = $post['towerHeight'];
          $this->core->set['tower_fabricator'] = $post['towerFabricator'];
          $this->core->set['remarks'] = $post['towerRemarks'];
          $this->core->set['active'] = 1;

          $updated_date = date('Y-m-d H:i:s');
          $updated_by = $this->session->userdata('user_id');

          if ($this->core->view_row('t_mtr_site_l_tower', array('site_id' => $site_id))) {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_tower', array('site_id' => $site_id));
          } else {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_tower');
          }

          // Shelter
          $this->core->set['shelter_needed'] = null_empty($post['shelterNeeded']);
          $this->core->set['kepemilikan_shelter'] = $post['kepemilikanShelter'];
          $this->core->set['nama_pemilik_shelter'] = $post['namaPemilikShelter'];
          $this->core->set['no_telp_pemilik_shelter'] = $post['noTelpPemilikShelter'];
          $this->core->set['shelter_type'] = $post['shelterType'];

          $shelter_size = @$post['shelterSizeL'] . 'x' . @$post['shelterSizeW'] . 'x' . @$post['shelterSizeH'];
          $this->core->set['shelter_size'] = $shelter_size;
          $this->core->set['shelter_fabricator'] = $post['shelterFabricator'];
          $this->core->set['remarks'] = $post['shelterRemarks'];
          $this->core->set['active'] = 1;

          if ($this->core->view_row('t_mtr_site_l_shelter', array('site_id' => $site_id))) {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_shelter', array('site_id' => $site_id));
          } else {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_shelter');
          }

          // PLN
          $this->core->set['handover_project'] = $post['handoverProject'];
          //$this->core->set['handover_remark'] = $post['handoverRemark'];
          $this->core->set['id_pelanggan_pln'] = $post['idPelangganPLN'];
          $this->core->set['atas_nama_pelanggan'] = $post['atasNamaPelanggan'];
          $this->core->set['pln_connected_date'] = null_empty($post['plnConnectedDate']);
          $this->core->set['pln_disconnect_date'] = null_empty($post['plnDisconnectDate']);
          $this->core->set['nama_upj_pln'] = $post['namaUPJPLN'];
          $this->core->set['wo_dismantle_date'] = null_empty($post['woDismantleDate']);
          $this->core->set['trafo_blast'] = null_empty($post['trafoBlast']);
          $this->core->set['trafo_capacity'] = $post['trafoCapacity'];
          //$this->core->set['trafo_maintained_by_smartfren'] = $post['trafoMaintainedBySmartfren'];
          $this->core->set['trafo_maintained_by'] = $post['trafoMaintainedBy'];
          $this->core->set['kwh_type'] = $post['kwhType'];
          $this->core->set['pln_phase'] = $post['plnPhase'];
          $this->core->set['pln_capacity'] = $post['plnCapacity'];
          $this->core->set['tarif_type'] = null_empty($post['tarifType']);
          $this->core->set['tarif_name'] = $post['tarifName'];
          $this->core->set['cara_pembayaran'] = $post['caraPembayaran'];
          $this->core->set['contract_under'] = $post['contractUnder'];
          $this->core->set['cost_center'] = $post['costCenter'];
          $this->core->set['company_code'] = $post['companyCode'];
          $this->core->set['remarks'] = $post['plnRemarks'];
          $this->core->set['active'] = 1;

          // Genset
          $this->core->set['permanent_genset'] = null_empty($post['permanentGenset']);
          $this->core->set['genset_brand'] = $post['gensetBrand'];
          $this->core->set['genset_capacity'] = $post['gensetCapacity'];
          $this->core->set['genset_ownership'] = null_empty($post['gensetOwnership']);
          $this->core->set['tank_capacity_daily'] = $post['tankCapacityDaily'];
          $this->core->set['tank_capacity_monthly'] = $post['tankCapacityMonthly'];

          if ($this->core->view_row('t_mtr_site_l_pln', array('site_id' => $site_id))) {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_pln', array('site_id' => $site_id));
          } else {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_pln');
          }

          // AC
          $this->core->set['remarks'] = $post['acRemarks'];

          $ac_id = 0;

          if ($this->core->view_row('t_mtr_site_l_ac', array('site_id' => $site_id))) {
            $this->core->set['updated_on'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_ac', array('site_id' => $site_id));

            $ac = $this->core->view_row('t_mtr_site_l_ac', array('site_id' => $site_id));
            $ac_id = $ac['ac_id'];
          } else {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_on'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_ac');
            $ac_id = $this->db->insert_id();
          }

          $this->core->delete('t_mtr_site_l_ac_detail', array('ac_id' => $ac_id));

          foreach ($post['brandAC'] as $key_br => $val_br) {
            if ($val_br != '') {
              $this->core->set['ac_id'] = $ac_id;
              $this->core->set['ac_brand_id'] = $val_br;
              $this->core->set['model'] = $post['typeAC'][$key_br];
              $this->core->set['ac_capacity_id'] = null_empty($post['capacityAC'][$key_br]);
              $this->core->set['inverter_type'] = $post['inverterType'][$key_br];
              $this->core->set['sn_indoor_part'] = $post['snIndoorPart'][$key_br];
              $this->core->set['sn_outdoor_part'] = $post['snOutdoorPart'][$key_br];

              $this->core->save('t_mtr_site_l_ac_detail');
            }
          }

          $json = array('error' => FALSE, 'message' => 'CME added.', 'site_id' => $site_id);
        } else {
          $json = array('error' => TRUE, 'message' => $error, 'site_id' => $site_id);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'ADD_OPERATIONAL') {

        $post = $this->core->get_post();

        $error = array();
        $json = array();

        if ($post['siteId'] == '') {
          $error[] = 'General Info is not complete.';
        }
        if (isset($post['dateStatus']) && $post['dateStatus'] == '') {
          $error[] = 'Date Status can not empty.';
        }
        if (isset($post['status']) && $post['dateStatus'] == '') {
          $error[] = 'Status can not unselected.';
        }

        $site_id = $post['siteId'];

        if (count($error) == 0) {

          // Site Keepers
          $this->core->set['site_keeper_availability'] = $post['siteKeeperAvailability'];
          $this->core->set['name'] = $post['siteKeeperName'];
          $this->core->set['address'] = $post['siteKeeperAddress'];
          $this->core->set['phone'] = $post['siteKeeperPhone'];
          $this->core->set['salary'] = $post['siteKeeperSalary'];
          $this->core->set['guard_house'] = $post['guardHouse'];
          //$this->core->set['hired_by_smart'] = $post['hiredBySmart'];
          $this->core->set['hired_by'] = $post['hiredBy'];
          $this->core->set['remark'] = $post['siteKeepersRemarks'];

          $updated_date = date('Y-m-d H:i:s');
          $updated_by = $this->session->userdata('user_id');

          if ($this->core->view_row('t_mtr_site_l_keepers', array('site_id' => $site_id))) {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_keepers', array('site_id' => $site_id));
          } else {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_keepers');
          }

          // PIC (tadinya di General Ingpoh)
          //$this->core->set['polis_number'] = $post['polisNumber'];
          $this->core->set['maintenance_type'] = $post['cmeMaintenanceType'];
          $this->core->set['cme_partner_id'] = $post['cmePartner'];
          $this->core->set['pic_smart'] = $post['picSmart'];
          $this->core->set['pic_smart_phone'] = $post['phonePicSmart'];
          $this->core->set['pic_cme_partner'] = $post['picCmePartner'];
          $this->core->set['pic_cme_partner_phone'] = $post['phonePicCmePartner'];

          $this->core->set['access_permit'] = $post['accessPermit'];
          $this->core->set['access_permit_hour_from'] = $post['accessPermitHourFrom'] != '' ? $post['accessPermitHourFrom'] . ':00' : NULL;
          $this->core->set['access_permit_hour_to'] = $post['accessPermitHourTo'] != '' ? $post['accessPermitHourTo'] . ':00' : NULL;

          // Insurance
          $this->core->set['cme_insured'] = null_empty($post['cmeInsured']);
          $this->core->set['cme_polis_number'] = $post['cmePolisNumber'];
          $this->core->set['equipment_insured'] = null_empty($post['equipmentInsured']);
          $this->core->set['equipment_polis_number'] = $post['equipmentPolisNumber'];

          $this->core->save('t_mtr_site', array('site_id' => $site_id));

          // Colloc Sell (tenant)
          $tenancy_sell_id = 0;

          $this->core->set['available_for_rent'] = null_empty($post['availableForRent']);

          if ($this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_SELL'))) {
            $this->core->set['updated_on'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_SELL'));

            $ten = $this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_SELL'));
            $tenancy_sell_id = $ten['tenancy_id'];
          } else {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['tenancy_type'] = 'COLLOC_SELL';
            $this->core->set['created_on'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_tenancy');
            $tenancy_sell_id = $this->db->insert_id();
          }

          $this->core->delete('t_mtr_site_l_tenancy_detail', array('tenancy_id' => $tenancy_sell_id));

          if (@is_array($post['tenantCollocSell'])) {
            foreach ($post['tenantCollocSell'] as $key_sell => $val_sell) {
              if ($val_sell != '') {
                $this->core->set['tenancy_id'] = $tenancy_sell_id;
                $this->core->set['tenant_id'] = $val_sell;
                $this->core->set['tenancy_date'] = null_empty($post['tenantDateCollocSell'][$key_sell]);

                $this->core->save('t_mtr_site_l_tenancy_detail');
              }
            }
          }

          // Colloc Buy (tenant)
          $tenancy_buy_id = 0;

          $this->core->set['seq_number'] = null_empty($post['seqNumberOfTenant']);

          if ($this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_BUY')))
		  {
            $this->core->set['updated_on'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_BUY'));

            $ten = $this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_BUY'));
            $tenancy_buy_id = $ten['tenancy_id'];
          }
		  else
		  {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['tenancy_type'] = 'COLLOC_BUY';
            $this->core->set['created_on'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_tenancy');
            $tenancy_buy_id = $this->db->insert_id();
          }

          $this->core->delete('t_mtr_site_l_tenancy_detail', array('tenancy_id' => $tenancy_buy_id));

          if (@is_array($post['tenantCollocBuy']))
		  {
            foreach ($post['tenantCollocBuy'] as $key_buy => $val_buy)
			{
              if ($val_sell != '')
			  {
                $this->core->set['tenancy_id'] = $tenancy_buy_id;
                $this->core->set['tenant_id'] = $val_buy;
                $this->core->set['tenancy_date'] = null_empty($post['tenantDateCollocBuy'][$key_buy]);

                $this->core->save('t_mtr_site_l_tenancy_detail');
              }
            }
          }

          // Last site status
          if (isset($post['dateStatus']) || isset($post['status']) || isset($post['statusRemarks']))
		  {
            $this->core->set['active'] = 0;
            $this->core->save('t_mtr_site_l_last_status', array('site_id' => $site_id));

            $this->core->set['site_id'] = $site_id;
            $this->core->set['date_status'] = null_empty($post['dateStatus']);
            $this->core->set['status'] = $post['status'];
            $this->core->set['remarks'] = $post['statusRemarks'];
            $this->core->set['active'] = 1;
            $this->core->set['created_on'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_last_status');
          }

          $json = array('error' => FALSE, 'message' => 'Operational added.', 'site_id' => $site_id);
        }
		else
		{
          $json = array('error' => TRUE, 'message' => $error, 'site_id' => $site_id);
        }

        echo json_encode($json);
        exit;
      }
    }

    if ($this->input->post('cmd') == 'ADD_PHOTO_REFERENCE')
	{
      $json = array();
      $upload_dir = FCPATH . 'file/upload/site-info/file-reference';
      $upload_cache_dir = FCPATH . 'file/upload/cache/site-info/file-reference';
      $upload_url = base_url('/file/upload/site-info/file-reference');

      if ($this->input->post('act') == 'SAVE') // save data
	  {
        $post = $this->core->get_post();
        $error = array();

        if ($this->_is_field_empty($post['siteId']))
		{
          $error[] = 'General Info is not complete.';
        }
        if ($this->_is_field_empty($post['fileType']))
		{
          $error[] = 'File Type can not unselected.';
        }
        if ($this->_is_field_empty($post['file']))
		{
          $error[] = 'File can not empty.';
        }

        if (count($error) == 0)
		{
          $site_id = $this->input->post('siteId');
          $fileType = $this->input->post('fileType');
          $fileName = $this->input->post('file');
          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s');

          $this->db->delete('t_mtr_site_l_photo_reference', array('site_id' => $site_id));

          if (@is_array($fileType))
		  {
            ksort($fileType);
            foreach ($fileType as $k_type => $v_type)
			{
              $this->core->set['site_id'] = $site_id;
              $this->core->set['photo_type'] = $fileType[$k_type];
              $this->core->set['photo_name'] = $fileName[$k_type];
              $this->core->set['updated_by'] = $updated_by;
              $this->core->set['updated_on'] = $updated_on;

              $this->core->save('t_mtr_site_l_photo_reference');
            }
          }

          $json = array('error' => FALSE, 'message' => 'Photo Reference has been updated successfully.', 'site_id' => $site_id);
        } else {
          $json = array('error' => TRUE, 'message' => $error, 'site_id' => $this->input->post('siteId'));
        }
      }
	  elseif ($this->input->post('act') == 'UPLOAD') // just upload file
	  {
        $allowed = array(
            '.jpg',
            '.jpeg',
            '.gif',
            '.png',
            '.doc',
            '.docx',
            '.xls',
            '.xlsx',
            '.pdf',
            '.txt'
        );

        $upload_key = array_keys($_FILES);

        if (!empty($_FILES[$upload_key[0]]['name']) && $_FILES[$upload_key[0]]['error'] == UPLOAD_ERR_OK)
		{
          $tmp_name = $_FILES[$upload_key[0]]['tmp_name'];
          $ext = strtolower(strrchr($_FILES[$upload_key[0]]['name'], '.'));
          $file_name = $this->input->post('image') ? $this->input->post('image') : time() . $ext;

          if (!in_array($ext, $allowed))
		  {
            $json = array('error' => TRUE, 'message' => 'Invalid file type. Please just upload file [.jpg, .gif, .png] / [.doc, docx, xls, xlsx, pdf, txt]');
          }
		  else
		  {
            if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $upload_dir . '/' . $file_name))
			{
              $file = $upload_url . '/' . $file_name;
              $thumb = '';
              if (in_array($ext, array('.jpg', '.jpeg', '.gif', '.png')))
			  {
                // resize first
                $image = $upload_dir . '/' . $file_name;
                $size = getimagesize($image);
                $width = $size[0];
                $height = $size[1];
                $expected_width = 550;
                $expected_height = 550;
                if ($width > $expected_width && $height > $expected_height)
				{
                  $img = new Image($image);
                  $img->resizeImage($expected_width, $expected_height, 'auto');
                  $img->saveImage($image, 80);
                }

                $thumb = $this->image_model->resize($image, 60, 60, 'crop');
              }

              $json = array('error' => FALSE, 'ref' => $upload_key[0], 'file_name' => $file_name, 'file' => $file, 'thumb' => $thumb, 'message' => 'File uploaded.');
            }
          }
        }
      }
	  elseif ($this->input->post('act') == 'DELETE')
	  {

        $file_name = $this->input->post('file_name');
        $pathinfo = pathinfo($file_name);
        $ext = strtolower(strrchr($pathinfo['basename'], '.'));

        if (is_file($upload_dir . '/' . $pathinfo['basename']) && file_exists($upload_dir . '/' . $pathinfo['basename']))
		{
          @unlink($upload_dir . '/' . $pathinfo['basename']);
        }

        if (in_array($ext, array('.jpg', '.jpeg', '.gif', '.png')))
		{
          if (is_file($upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension']) && file_exists($upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension']))
		  {
            @unlink($upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension']);
          }
        }

        $json = array('error' => FALSE, 'message' => 'File has been deleted successfully.');

        echo json_encode($json);
        exit;
      }

      echo json_encode($json);
      exit;
    }

    /*
      if ($this->input->post('cmd') == 'ADD_PHOTO_REF') {

      $site_id = $this->input->post('siteId');
      $fileType = $this->input->post('fileType');
      $fileName = time();
      $upload_dir = FCPATH.'file/upload/photo-ref';

      $created_by = $updated_by = $this->session->userdata('user_id');
      $created_on = $updated_on = date('Y-m-d H:i:s');

      $this->db->delete('t_mtr_site_l_photo_reference', array('site_id' => $site_id));

      foreach ($fileType as $k_type => $v_type) {
      $tmp_name = $_FILES['file']['tmp_name'][$k_type];
      $ext = end(explode('.', $_FILES['file']['name'][$k_type]));
      $fileName = time().'.'.$ext;

      if (is_safe_upload_name($fileName) && @move_uploaded_file($tmp_name, $upload_dir.'/'.$fileName)) {
      $this->core->set['site_id'] = $site_id;
      $this->core->set['photo_type'] = $fileType[$k_type];
      $this->core->set['photo_name'] = $fileName;
      $this->core->set['created_by'] = $created_by;
      $this->core->set['created_on'] = $created_on;

      $this->core->save('t_mtr_site_l_photo_reference');

      $this->session->set_flashdata('input_photo_ref_succ', 'Photo Reference saved.');

      redirect($this->uri->uri_string());
      }
      }
      }
     */

    $this->_site_form();
  }

  public function edit($site_id = 0)
  {
    $this->_check_valid_loggedin();

    $site_id = (int) $this->core->decode($site_id);

    if (empty($site_id))
	{
      redirect('/site_info');
    }

    if (!$this->mmenu->cekPermission($this->_partials['menu'], 'menu_edit'))
	{
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }

    if ($this->core->is_ajax())
	{
      if ($this->input->get('load_content') == 'GENERAL_INFO')
	  {
        $data['general_info'] = $this->site_info_model->get_site($site_id);

        echo $this->parser->parse('partials/content_general_info', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'SITAC')
	  {
        $data['lahan'] = $this->core->view_row('t_mtr_site_l_lahan', array('site_id' => $site_id));
        $data['perijinan'] = $this->core->view_row('t_mtr_site_l_perijinan', array('site_id' => $site_id));

        echo $this->parser->parse('partials/content_sitac', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'CME')
	  {
        $data['tower'] = $this->site_info_model->get_tower($site_id);
        $data['shelter'] = $this->core->view_row('t_mtr_site_l_shelter', array('site_id' => $site_id));
        $data['pln'] = $this->core->view_row('t_mtr_site_l_pln', array('site_id' => $site_id));
        $data['ac'] = $this->core->view_row('t_mtr_site_l_ac', array('site_id' => $site_id));
        @$data['ac']['detail'] = $this->core->view_all('t_mtr_site_l_ac_detail', array('ac_id' => $data['ac']['ac_id']), 'ac_id');

        echo $this->parser->parse('partials/content_cme', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'OPERATIONAL')
	  {
        $data['site_keeper'] = $this->core->view_row('t_mtr_site_l_keepers', array('site_id' => $site_id));
        $data['general_info'] = $this->core->view_row('t_mtr_site', array('site_id' => $site_id));
        $data['tenancy_sell'] = $this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_SELL'));
        @$data['tenancy_sell']['detail'] = $this->core->view_all('t_mtr_site_l_tenancy_detail', array('tenancy_id' => $data['tenancy_sell']['tenancy_id']), 'tenancy_id');
        $data['tenancy_buy'] = $this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_BUY'));
        @$data['tenancy_buy']['detail'] = $this->core->view_all('t_mtr_site_l_tenancy_detail', array('tenancy_id' => $data['tenancy_buy']['tenancy_id']), 'tenancy_id');

        $data['site_last_status'] = $this->db->query("
					SELECT status.date_status, lo1.lookup_desc AS status, status.remarks, status.created_on, status.created_by
					FROM t_mtr_site_l_last_status status
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = status.status::VARCHAR AND lo1.lookup_key = 'site_last_status' AND lo1.active = 1
					WHERE status.site_id = {$site_id}
					ORDER BY date_status ASC
				")->result_array();

        $data['site_status'] = $this->core->view_all('t_mtr_site_l_used_status', array('site_id' => $site_id), 'created_on');

        echo $this->parser->parse('partials/content_operational', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'EQUIPMENT')
	  {
        $data['pln'] = $this->core->view_row('t_mtr_site_l_pln', array('site_id' => $site_id));

        echo $this->parser->parse('partials/content_equipment', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'CANDIDATE')
	  {
        $data['site_id'] = $this->input->get('site_id');

        echo $this->parser->parse('partials/content_candidate', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'PLAN_INFO')
	  {
        $site = $this->core->view_row('t_mtr_site', array('site_id' => $site_id));

        $data['plan'] = $this->db
                ->query("
						SELECT plan.*, lo1.lookup_desc AS bts_type_name, loc1.nama AS plan_kecamatan, loc2.nama AS plan_kabupaten, loc3.nama AS plan_propinsi
						FROM t_mtr_site_plan plan
						LEFT JOIN t_mtr_lookup lo1 ON CAST(lo1.lookup_value AS INTEGER) = plan.bts_type AND lo1.lookup_key = 'bts_type'
						LEFT JOIN t_mtr_lokasi loc1 ON loc1.lokasi_id = plan.kecamatan AND loc1.jenis = 'KC'
						LEFT JOIN t_mtr_lokasi loc2 ON loc2.lokasi_id = plan.kabupaten AND loc2.jenis = 'KB'
						LEFT JOIN t_mtr_lokasi loc3 ON loc3.lokasi_id = plan.propinsi_id AND loc3.jenis = 'P'
						WHERE plan.nom_site_code = '" . $site['site_code'] . "'
					")
                ->row_array();

        echo $this->parser->parse('partials/content_plan_info', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'PHOTO_REFERENCE')
	  {
        $site_id = (int) $this->core->decode($this->uri->segment(3));
        $result = $this->core->view_all('t_mtr_site_l_photo_reference', array('site_id' => $site_id), 'photo_ref_id');
        $dir = FCPATH . 'file/upload/site-info/file-reference';

        $data['photo_reference'] = array();
        foreach ($result as $r)
		{
          if (is_file($dir . '/' . $r['photo_name']) && file_exists($dir . '/' . $r['photo_name']))
		  {
            $r['file'] = base_url('/file/upload/site-info/file-reference/' . $r['photo_name']);
            $r['file_path'] = FCPATH . 'file/upload/site-info/file-reference/' . $r['photo_name'];

            $ext = end(explode('.', $r['photo_name']));
            $thumb = '';
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {
              $thumb = $this->image_model->resize($dir . '/' . $r['photo_name'], 60, 60, 'crop');
            }

            $r['thumb'] = $thumb;
            $r['ext'] = $ext;

            $data['photo_reference'][] = $r;
          }
        }

        echo $this->parser->parse('partials/content_photo_reference', $data);
        exit;
      }

      if ($this->input->post('cmd') == 'EDIT_GENERAL_INFO')
	  {
        $post = $this->core->get_post();

        $error = array();
        $json = array();

        /*
          if ($post['oldSiteId'] == '') {
          $error[] = 'Reference Site ID can not empty.';
          }
         */
        if ($this->_is_field_empty($post['siteName']))
		{
          $error[] = 'Site Name can not empty.';
        }
        if ($this->_is_field_empty($post['siteType']))
		{
          $error[] = 'Site Type can not empty.';
        }
        if ($this->_is_field_empty($post['siteProfile']))
		{
          $error[] = 'Site Profile can not empty.';
        }
        if ($this->_is_field_empty($post['vendorId']))
		{
          $error[] = 'Vendor ID can not empty.';
        }
        /* if ($this->_is_field_empty($post['vendorName'])) {
          $error[] = 'Vendor Name can not empty.';
          }
          if ($this->_is_field_empty($post['tpId'])) {
          $error[] = 'TP Site ID can not empty.';
          } */
        if ($this->_is_field_empty($post['region']))
		{
          $error[] = 'Region can not unselected.';
        }

        $vendor_ids_1 = explode(';', $post['vendorId']);
        $vid = array();
        $onair = '';
        foreach ($vendor_ids_1 as $val)
		{
          $sql = "
			SELECT vendor_id, onair_offair
			FROM v_site_info_master_site
			WHERE site_code = '" . $post['siteIdText'] . "' 
			AND vendor_id ILIKE '%{$val}%' AND lower(status) = 'active' 
			AND lower(onair_offair) = 'on air' 
			AND site_id != {$site_id}";
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
          $updated_on = date('Y-m-d H:i:s');
          $updated_by = $this->session->userdata('user_id');

          //$this->core->set['site_code'] = $post['siteId'];
          $curSiteCode = $post['curSiteCode'];
          $newSiteCode = $post['siteIdText'];
          if ($curSiteCode !== $newSiteCode) {
            
          }
          $this->core->set['site_code'] = $newSiteCode;
          $this->core->set['site_code_old'] = null_empty($post['oldSiteId']);
          $this->core->set['site_name'] = $post['siteName'];
          $this->core->set['site_type'] = $post['siteType'];
          $this->core->set['site_profile'] = $post['siteProfile'];
          $this->core->set['vendor_name'] = $post['vendorName'];
          $this->core->set['vendor_id'] = $post['vendorId'];
          //$this->core->set['nom_latitude'] = $post['nominalLatitude'];
          //$this->core->set['nom_longitude'] = $post['nominalLongitude'];
          $this->core->set['latitude'] = null_empty($post['actualLatitude']);
          $this->core->set['longitude'] = null_empty($post['actualLongitude']);
          $this->core->set['site_specification'] = $post['siteSpecification'];

          $this->core->set['site_address'] = nl2br($post['siteAddress']);
          $this->core->set['propinsi_id'] = $post['propinsi'];
          $this->core->set['kabupaten'] = $post['kabupaten'];
          $this->core->set['kecamatan'] = $post['kecamatan'];
          $this->core->set['kelurahan'] = $post['kelurahan'];
          $this->core->set['kode_pos'] = $post['kodePos'];

          $this->core->set['region_id'] = $post['region'];
          $this->core->set['cluster_ops_id'] = $post['clusterOpr'];
          $this->core->set['cluster_net_id'] = $post['clusterNet'];
          $this->core->set['cluster_com_id'] = $post['clusterCom'];

          // Finance
          $this->core->set['contract_under'] = null_empty($post['contractUnder']);
          $this->core->set['company_code'] = null_empty($post['companyCode']);
          $this->core->set['cost_center'] = $post['costCenter'];
          $this->core->set['harga_sewa'] = $post['priceLease'];
          $this->core->set['price_lease_period'] = $post['priceLeasePeriod'];
          // $this->core->set['term_of_payment'] = null_empty($post['termOfPayment']);
          $this->core->set['lcd_start_date'] = null_empty($post['lcdStartDate']);
          $this->core->set['lcd_end_date'] = null_empty($post['lcdEndDate']);
          $this->core->set['lcd_renewal_status'] = null_empty($post['lcdRenewalStatus']);
          $this->core->set['lcd_renewal_remark'] = $post['lcdRenewalRemarks'];
          $this->core->set['remarks'] = $post['financeRemarks'];

          // TP
          //$this->core->set['tp_name'] = null_empty($post['tpName']);
          $this->core->set['tp_id'] = null_empty($post['tpId']);
          //$this->core->set['tp_name_group'] = null_empty($post['tpNameGroup']);
          //$this->core->set['site_category'] = null_empty($post['siteCategory']);

          $this->core->set['updated_on'] = $updated_on;
          $this->core->set['updated_by'] = $updated_by;
          $this->core->set['active'] = 1;

          $data = $this->core->set;
          $data['site_id'] = $post['siteId'];

          /* if ( ! $this->core->save('t_mtr_site', array('site_id' => $site_id))) {
            echo json_encode(array('error' => TRUE, 'message' => $this->core->error_message));
            exit;
            } */

          //$this->site_info_model->save_to_history('t_mtr_site', $data, 'site_id');
          // save data + history
          $this->site_info_model->save_data('t_mtr_site', $data, 'site_id');

          // send notification
          $site = $this->core->view_row('t_mtr_site', array('site_id' => (int) $post['siteId']));

          // update lat-lng TP
          $this->core->update('t_mtr_tower_provider', array('latitude' => null_empty($post['actualLatitude']), 'longitude' => null_empty($post['actualLongitude'])), array('tp_id' => $site['tp_id']));

          $region = array();
          foreach ($this->session->userdata['user_region'] as $reg)
		  {
            if ($reg['region_name'] != '')
			{
              $region[] = $reg['region_name'];
            }
          }
          $mail_param = array(
              '[site_id]' => $site['site_code'],
              '[updated_on]' => date("D, d F Y G:ia", strtotime($updated_on)),
              '[updated_by]' => $updated_by
          );
          $schedule_param = array('date' => date('Y-m-d'), 'time' => date('G:i:s'), 'foreign_id' => (int) $post['siteId']);
          /* $pic_mail = array(
            array('name' => 'weleh-weleh', 'email' => 'blekedeg@gmail.com', 'recipient_type' => 'to'),
            array('name' => 'W. Iwa', 'email' => 'wirda97@gmail.com', 'recipient_type' => 'cc')
            ); */

          if ($notif = $this->core->view_row('t_ref_mail_notif', array('template_name' => 'SINFO_EDIT')))
		  {
            if ($this->core->view_all('t_ref_mail_notif_recipient_user', array('mail_notif_id' => $notif['id'])))
			{
              $this->mglobal->pushMail('SINFO_EDIT', $region, $mail_param, $schedule_param, NULL);
            }
          }
          if ($notif = $this->core->view_row('t_ref_sms_notif', array('template_name' => 'SINFO_EDIT')))
		  {
            if ($this->core->view_all('t_ref_sms_notif_recipient_user', array('sms_notif_id' => $notif['id'])))
			{
              $this->mglobal->pushSMS('SINFO_EDIT', $region, $mail_param, $schedule_param, NULL);
            }
          }

          $json = array('error' => FALSE, 'message' => 'General Info has been updated successfully.', 'site_id' => $site_id, 'site_id_enc' => $this->core->encode($site_id));
        }
		else
		{
          $json = array('error' => TRUE, 'message' => $error, 'site_id' => $site_id);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'EDIT_SITAC')
	  {
        $post = $this->core->get_post();

        $error = array();
        $json = array();

        if (count($error) == 0)
		{
          $this->core->db_debug = TRUE;

          // Lahan
          $this->core->set['kepemilikan_lahan'] = $post['kepemilikanLahan'];
          $this->core->set['jenis_surat_lahan'] = $post['jenisSuratLahan'];
          $this->core->set['no_ajb'] = $post['noAJB'];
          $this->core->set['date_ajb'] = null_empty($post['dateAJB']);
          $this->core->set['no_surat_lahan'] = $post['noSuratLahan'];
          $this->core->set['luas_lahan'] = $post['luasLahan'];
          $this->core->set['akses_jalan_lbr'] = $post['aksesJalanLebar'];
          $this->core->set['akses_jalan_pjg'] = $post['aksesJalanPanjang'];
          $this->core->set['sudah_balik_nama'] = null_empty($post['sudahBalikNama']);
          $this->core->set['no_baru'] = $post['noBaru'];
          $this->core->set['lease_term'] = $post['leaseTerm'];
          $this->core->set['lease_start_date'] = null_empty($post['leaseStartDate']);
          $this->core->set['lease_finish_date'] = null_empty($post['leaseFinishDate']);
          $this->core->set['nama_pemilik_lahan'] = $post['namaPemilikLahan'];
          $this->core->set['no_telp_pemilik_lahan'] = $post['noTelpPemilikLahan'];
          $this->core->set['contact_person_lahan'] = $post['contactPersonLahan'];
          $this->core->set['no_telp_contact_person'] = $post['noTelpContactPerson'];
          $this->core->set['remarks'] = $post['spaceRemarks'];

          // SMG Property
          $this->core->set['status'] = null_empty($post['smgStatus']);
          $this->core->set['company'] = $post['smgCompany'];

          $updated_date = date('Y-m-d H:i:s');
          $updated_by = $this->session->userdata('user_id');

          $this->core->set['active'] = 1;

          if ($this->core->view_row('t_mtr_site_l_lahan', array('site_id' => $site_id)))
		  {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            //$this->core->save('t_mtr_site_l_lahan', array('site_id' => $site_id));
            $data = $this->core->set;
            $data['site_id'] = $post['siteId'];

            $this->site_info_model->save_data('t_mtr_site_l_lahan', $data, 'site_id');
          }
		  else
		  {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_lahan');
          }

          unset($this->core->set);

          // Perijinan
          $this->core->set['iw_needed'] = null_empty($post['iwNeeded']);
          if ($post['iwNeeded'] == 1)
		  {
            $this->core->set['iw_date'] = null_empty($post['iwDate']);
          }
		  else
		  {
            $this->core->set['iw_date'] = NULL;
          }
          $this->core->set['retribusi'] = null_empty($post['retribusi']);
          if ($post['retribusi'] == 1)
		  {
            $this->core->set['biaya_retribusi'] = $post['biayaRetribusi'];
            $this->core->set['tipe_retribusi'] = $post['retributionType'];
          }
		  else
		  {
            $this->core->set['biaya_retribusi'] = NULL;
            $this->core->set['tipe_retribusi'] = NULL;
          }
          $this->core->set['iw_remarks'] = $post['iwRemarks'];

          $this->core->set['ho_needed'] = null_empty($post['hoNeeded']);
          if ($post['hoNeeded'] == 1)
		  {
            $this->core->set['ho_type'] = $post['hoType'];
            $this->core->set['ho_number'] = $post['hoNumber'];
            $this->core->set['ho_date'] = null_empty($post['hoDate']);
            $this->core->set['ho_expired'] = null_empty($post['hoExpired']);
            $this->core->set['ho_remarks'] = $post['hoRemarks'];
          }
		  else
		  {
            $this->core->set['ho_type'] = NULL;
            $this->core->set['ho_number'] = NULL;
            $this->core->set['ho_date'] = NULL;
            $this->core->set['ho_expired'] = NULL;
            $this->core->set['ho_remarks'] = NULL;
          }

          $this->core->set['pbb_needed'] = null_empty($post['pbbNeeded']);
          if ($post['pbbNeeded'] == 1)
		  {
            $this->core->set['nomor_pbb'] = $post['nomorPBB'];
            $this->core->set['atas_nama'] = $post['atasNama'];
            $this->core->set['jatuh_tempo'] = null_empty($post['jatuhTempo']);
            $this->core->set['nilai_pbb'] = $post['nilaiPBB'];
            $this->core->set['pic_pbb'] = $post['picPBB'];
            $this->core->set['pbb_remarks'] = $post['pbbRemarks'];
          }
		  else
		  {
            $this->core->set['nomor_pbb'] = NULL;
            $this->core->set['atas_nama'] = NULL;
            $this->core->set['jatuh_tempo'] = NULL;
            $this->core->set['nilai_pbb'] = NULL;
            $this->core->set['pic_pbb'] = NULL;
            $this->core->set['pbb_remarks'] = NULL;
          }

          $this->core->set['imb_needed'] = null_empty($post['imbNeeded']);
          if ($post['imbNeeded'] == 1)
		  {
            $this->core->set['imb_type'] = $post['imbType'];
            $this->core->set['nomor_imb'] = $post['nomorIMB'];
            $this->core->set['date'] = null_empty($post['imbDate']);
            $this->core->set['expired'] = null_empty($post['imbExpired']);
            $this->core->set['imb_remarks'] = $post['imbRemarks'];
          }
		  else
		  {
            $this->core->set['imb_type'] = NULL;
            $this->core->set['nomor_imb'] = NULL;
            $this->core->set['date'] = NULL;
            $this->core->set['expired'] = NULL;
            $this->core->set['imb_remarks'] = NULL;
          }

          $this->core->set['rkt_needed'] = null_empty($post['rktNeeded']);
          if ($post['rktNeeded'] == 1)
		  {
            $this->core->set['rkt_type'] = $post['rktType'];
            $this->core->set['rkt_number'] = $post['rktNumber'];
            $this->core->set['rkt_date'] = null_empty($post['rktDate']);
            $this->core->set['rkt_expired'] = null_empty($post['rktExpired']);
            $this->core->set['remarks'] = $post['remarks'];
          }
		  else
		  {
            $this->core->set['rkt_type'] = NULL;
            $this->core->set['rkt_number'] = NULL;
            $this->core->set['rkt_date'] = NULL;
            $this->core->set['rkt_expired'] = NULL;
            $this->core->set['remarks'] = NULL;
          }

          if ($this->core->view_row('t_mtr_site_l_perijinan', array('site_id' => $site_id)))
		  {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            //$this->core->save('t_mtr_site_l_perijinan', array('site_id' => $site_id));
            $data = $this->core->set;
            $data['site_id'] = $post['siteId'];

            $this->site_info_model->save_data('t_mtr_site_l_perijinan', $data, 'site_id');
          }
		  else
		  {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_perijinan');
          }

          unset($this->core->set);
          $this->core->set['updated_on'] = $updated_date;
          $this->core->set['updated_by'] = $updated_by;
          $this->core->save('t_mtr_site', array('site_id' => $site_id));

          $json = array('error' => FALSE, 'message' => 'SITAC has been updated successfully.', 'site_id' => $site_id);
        }
		else
		{
          $json = array('error' => TRUE, 'message' => $error, 'site_id' => $site_id);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'EDIT_CME')
	  {
        $post = $this->core->get_post();

        $error = array();
        $json = array();

        if (count($error) == 0)
		{
          $this->core->db_debug = TRUE;

          // Tower
          $this->core->set['kepemilikan_tower'] = $post['kepemilikanTower'];
          $this->core->set['nama_pemilik_tower'] = $post['namaPemilikTower'];
          $this->core->set['no_telp_pemilik_tower'] = $post['telpPemilikTower'];
          $this->core->set['tower_type'] = null_empty($post['towerType']);
          $this->core->set['tower_leg_no'] = null_empty($post['towerLegNo']);
          $this->core->set['tower_height'] = $post['towerHeight'];
          $this->core->set['tower_fabricator'] = $post['towerFabricator'];
          $this->core->set['remarks'] = $post['towerRemarks'];
          $this->core->set['active'] = 1;

          $updated_date = date('Y-m-d H:i:s');
          $updated_by = $this->session->userdata('user_id');

          if ($this->core->view_row('t_mtr_site_l_tower', array('site_id' => $site_id)))
		  {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            //$this->core->save('t_mtr_site_l_tower', array('site_id' => $site_id));
            $data = $this->core->set;
            $data['site_id'] = $post['siteId'];

            $this->site_info_model->save_data('t_mtr_site_l_tower', $data, 'site_id');
          }
		  else
		  {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_tower');
          }

          unset($this->core->set);

          // Shelter
          $this->core->set['shelter_needed'] = null_empty($post['shelterNeeded']);
          if ($post['shelterNeeded'] == 1)
		  {
            $this->core->set['kepemilikan_shelter'] = $post['kepemilikanShelter'];
            $this->core->set['nama_pemilik_shelter'] = $post['namaPemilikShelter'];
            $this->core->set['no_telp_pemilik_shelter'] = $post['noTelpPemilikShelter'];
            $this->core->set['shelter_type'] = $post['shelterType'];

            $shelter_size = @$post['shelterSizeL'] . 'x' . @$post['shelterSizeW'] . 'x' . @$post['shelterSizeH'];
            $this->core->set['shelter_size'] = $shelter_size;
            $this->core->set['shelter_fabricator'] = $post['shelterFabricator'];
            $this->core->set['remarks'] = $post['shelterRemarks'];
          }
		  else
		  {
            $this->core->set['kepemilikan_shelter'] = NULL;
            $this->core->set['nama_pemilik_shelter'] = NULL;
            $this->core->set['no_telp_pemilik_shelter'] = NULL;
            $this->core->set['shelter_type'] = NULL;

            $shelter_size = 0 . 'x' . 0 . 'x' . 0;
            $this->core->set['shelter_size'] = $shelter_size;
            $this->core->set['shelter_fabricator'] = NULL;
            $this->core->set['remarks'] = NULL;
          }
          $this->core->set['active'] = 1;

          if ($this->core->view_row('t_mtr_site_l_shelter', array('site_id' => $site_id)))
		  {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            //$this->core->save('t_mtr_site_l_shelter', array('site_id' => $site_id));
            $data = $this->core->set;
            $data['site_id'] = $post['siteId'];

            $this->site_info_model->save_data('t_mtr_site_l_shelter', $data, 'site_id');
          }
		  else
		  {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_shelter');
          }

          unset($this->core->set);

          // PLN
          $this->core->set['handover_project'] = $post['handoverProject'];
          //$this->core->set['handover_remark'] = $post['handoverRemark'];
          $this->core->set['id_pelanggan_pln'] = $post['idPelangganPLN'];
          $this->core->set['atas_nama_pelanggan'] = $post['atasNamaPelanggan'];
          $this->core->set['pln_connected_date'] = null_empty($post['plnConnectedDate']);
          $this->core->set['pln_disconnect_date'] = null_empty($post['plnDisconnectDate']);
          $this->core->set['nama_upj_pln'] = $post['namaUPJPLN'];
          $this->core->set['wo_dismantle_date'] = null_empty($post['woDismantleDate']);
          $this->core->set['trafo_blast'] = null_empty($post['trafoBlast']);
          $this->core->set['trafo_capacity'] = $post['trafoCapacity'];
          //$this->core->set['trafo_maintained_by_smartfren'] = $post['trafoMaintainedBySmartfren'];
          $this->core->set['trafo_maintained_by'] = $post['trafoMaintainedBy'];
          $this->core->set['kwh_type'] = $post['kwhType'];
          $this->core->set['pln_phase'] = $post['plnPhase'];
          $this->core->set['pln_capacity'] = $post['plnCapacity'];
          $this->core->set['tarif_type'] = null_empty($post['tarifType']);
          $this->core->set['tarif_name'] = $post['tarifName'];
          $this->core->set['cara_pembayaran'] = $post['caraPembayaran'];
          $this->core->set['contract_under'] = $post['contractUnder'];
          $this->core->set['cost_center'] = $post['costCenter'];
          $this->core->set['company_code'] = $post['companyCode'];
          $this->core->set['remarks'] = $post['plnRemarks'];
          $this->core->set['active'] = 1;

          // Genset
          $this->core->set['permanent_genset'] = null_empty($post['permanentGenset']);
          $this->core->set['genset_brand'] = $post['gensetBrand'];
          $this->core->set['genset_capacity'] = $post['gensetCapacity'];
          $this->core->set['genset_ownership'] = null_empty($post['gensetOwnership']);
          $this->core->set['tank_capacity_daily'] = $post['tankCapacityDaily'];
          $this->core->set['tank_capacity_monthly'] = $post['tankCapacityMonthly'];

          if ($this->core->view_row('t_mtr_site_l_pln', array('site_id' => $site_id)))
		  {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            //$this->core->save('t_mtr_site_l_pln', array('site_id' => $site_id));
            $data = $this->core->set;
            $data['site_id'] = $post['siteId'];

            $this->site_info_model->save_data('t_mtr_site_l_pln', $data, 'site_id');
          }
		  else
		  {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_pln');
          }

          unset($this->core->set);

          // AC
          $this->core->set['remarks'] = $post['acRemarks'];

          $ac_id = 0;

          if ($this->core->view_row('t_mtr_site_l_ac', array('site_id' => $site_id)))
		  {
            $this->core->set['updated_on'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_ac', array('site_id' => $site_id));

            $ac = $this->core->view_row('t_mtr_site_l_ac', array('site_id' => $site_id));
            $ac_id = $ac['ac_id'];
          }
		  else
		  {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_on'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_ac');
            $ac_id = $this->db->insert_id();
          }

          $this->core->delete('t_mtr_site_l_ac_detail', array('ac_id' => $ac_id));

          foreach ($post['brandAC'] as $key_br => $val_br)
		  {
            if ($val_br != '')
			{
              $this->core->set['ac_id'] = $ac_id;
              $this->core->set['ac_brand_id'] = $val_br;
              $this->core->set['model'] = $post['typeAC'][$key_br];
              $this->core->set['ac_capacity_id'] = null_empty($post['capacityAC'][$key_br]);
              $this->core->set['inverter_type'] = $post['inverterType'][$key_br];
              $this->core->set['sn_indoor_part'] = $post['snIndoorPart'][$key_br];
              $this->core->set['sn_outdoor_part'] = $post['snOutdoorPart'][$key_br];

              $this->core->save('t_mtr_site_l_ac_detail');
            }
          }

          unset($this->core->set);
          $this->core->set['updated_on'] = $updated_date;
          $this->core->set['updated_by'] = $updated_by;
          $this->core->save('t_mtr_site', array('site_id' => $site_id));

          $json = array('error' => FALSE, 'message' => 'CME has been updated successfully.', 'site_id' => $site_id);
        }
		else
		{
          $json = array('error' => TRUE, 'message' => $error, 'site_id' => $site_id);
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->post('cmd') == 'EDIT_OPERATIONAL')
	  {
        $post = $this->core->get_post();

        $error = array();
        $json = array();

        if (isset($post['dateStatus']) && $post['dateStatus'] == '')
		{
          $error[] = 'Date Status can not empty.';
        }
        if (isset($post['status']) && $post['dateStatus'] == '')
		{
          $error[] = 'Status can not unselected.';
        }

        if (isset($post['dateStatusOfSite']) && $post['dateStatusOfSite'] == '')
		{
          $error[] = 'Date Status can not empty.';
        }
        if (isset($post['statusOfSiteUsed']) && $post['dateStatusOfSite'] == '')
		{
          $error[] = 'Status can not unselected.';
        }

        if (count($error) == 0)
		{
          $this->core->db_debug = TRUE;

          // Site Keepers
          $this->core->set['site_keeper_availability'] = $post['siteKeeperAvailability'];
          $this->core->set['name'] = $post['siteKeeperName'];
          $this->core->set['address'] = $post['siteKeeperAddress'];
          $this->core->set['phone'] = $post['siteKeeperPhone'];
          $this->core->set['salary'] = $post['siteKeeperSalary'];
          $this->core->set['guard_house'] = $post['guardHouse'];
          //$this->core->set['hired_by_smart'] = $post['hiredBySmart'];
          $this->core->set['hired_by'] = $post['hiredBy'];
          $this->core->set['remark'] = $post['siteKeepersRemarks'];

          $updated_date = date('Y-m-d H:i:s');
          $updated_by = $this->session->userdata('user_id');

          if ($this->core->view_row('t_mtr_site_l_keepers', array('site_id' => $site_id)))
		  {
            $this->core->set['updated_date'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_keepers', array('site_id' => $site_id));
          }
		  else
		  {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['created_date'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_keepers');
          }

          // PIC (tadinya di General Ingpoh)
          //$this->core->set['polis_number'] = $post['polisNumber'];
          $this->core->set['maintenance_type'] = $post['cmeMaintenanceType'];
          $this->core->set['cme_partner_id'] = $post['cmePartner'];
          $this->core->set['pic_smart'] = $post['picSmart'];
          $this->core->set['pic_smart_phone'] = $post['phonePicSmart'];
          $this->core->set['pic_cme_partner'] = $post['picCmePartner'];
          $this->core->set['pic_cme_partner_phone'] = $post['phonePicCmePartner'];

          $this->core->set['access_permit'] = $post['accessPermit'];
          $this->core->set['access_permit_hour_from'] = $post['accessPermitHourFrom'] != '' ? $post['accessPermitHourFrom'] . ':00' : NULL;
          $this->core->set['access_permit_hour_to'] = $post['accessPermitHourTo'] != '' ? $post['accessPermitHourTo'] . ':00' : NULL;

          // Insurance
          $this->core->set['cme_insured'] = null_empty($post['cmeInsured']);
          $this->core->set['cme_polis_number'] = $post['cmePolisNumber'];
          $this->core->set['equipment_insured'] = null_empty($post['equipmentInsured']);
          $this->core->set['equipment_polis_number'] = $post['equipmentPolisNumber'];

          $this->core->set['site_status'] = $post['siteStatus'];

          $this->core->save('t_mtr_site', array('site_id' => $site_id));

          // Colloc Sell (tenant)
          $tenancy_sell_id = 0;

          $this->core->set['available_for_rent'] = null_empty($post['availableForRent']);

          if ($this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_SELL')))
		  {
            $this->core->set['updated_on'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_SELL'));

            $ten = $this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_SELL'));
            $tenancy_sell_id = $ten['tenancy_id'];
          }
		  else
		  {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['tenancy_type'] = 'COLLOC_SELL';
            $this->core->set['created_on'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_tenancy');
            $tenancy_sell_id = $this->db->insert_id();
          }

          $this->core->delete('t_mtr_site_l_tenancy_detail', array('tenancy_id' => $tenancy_sell_id));

          if (@is_array($post['tenantCollocSell']))
		  {
            foreach ($post['tenantCollocSell'] as $key_sell => $val_sell)
			{
              if ($val_sell != '')
			  {
                $this->core->set['tenancy_id'] = $tenancy_sell_id;
                $this->core->set['tenant_id'] = $val_sell;
                $this->core->set['tenancy_date'] = null_empty($post['tenantDateCollocSell'][$key_sell]);

                $this->core->save('t_mtr_site_l_tenancy_detail');
              }
            }
          }

          // Colloc Buy (tenant)
          $tenancy_buy_id = 0;

          $this->core->set['seq_number'] = null_empty($post['seqNumberOfTenant']);

          if ($this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_BUY')))
		  {
            $this->core->set['updated_on'] = $updated_date;
            $this->core->set['updated_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_BUY'));

            $ten = $this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_BUY'));
            $tenancy_buy_id = $ten['tenancy_id'];
          }
		  else
		  {
            $this->core->set['site_id'] = $site_id;
            $this->core->set['tenancy_type'] = 'COLLOC_BUY';
            $this->core->set['created_on'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;
            $this->core->set['active'] = 1;

            $this->core->save('t_mtr_site_l_tenancy');
            $tenancy_buy_id = $this->db->insert_id();
          }

          $this->core->delete('t_mtr_site_l_tenancy_detail', array('tenancy_id' => $tenancy_buy_id));

          if (@is_array($post['tenantCollocBuy']))
		  {
            foreach ($post['tenantCollocBuy'] as $key_buy => $val_buy)
			{
              if ($val_sell != '')
			  {
                $this->core->set['tenancy_id'] = $tenancy_buy_id;
                $this->core->set['tenant_id'] = $val_buy;
                $this->core->set['tenancy_date'] = null_empty($post['tenantDateCollocBuy'][$key_buy]);

                $this->core->save('t_mtr_site_l_tenancy_detail');
              }
            }
          }

          unset($this->core->set);
          $this->core->set['updated_on'] = $updated_date;
          $this->core->set['updated_by'] = $updated_by;
          $this->core->save('t_mtr_site', array('site_id' => $site_id));

          // Last site status
          if (isset($post['dateStatus']) || isset($post['status']) || isset($post['statusRemarks']))
		  {
            $this->core->set['active'] = 0;
            $this->core->save('t_mtr_site_l_last_status', array('site_id' => $site_id));

            $this->core->set['site_id'] = $site_id;
            $this->core->set['date_status'] = null_empty($post['dateStatus']);
            $this->core->set['status'] = $post['status'];
            $this->core->set['remarks'] = $post['statusRemarks'];
            $this->core->set['active'] = 1;
            $this->core->set['created_on'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_last_status');
          }

          // status of site
          if (isset($post['dateStatusOfSite']) || isset($post['statusOfSiteUsed']) || isset($post['statusOfSiteRemarks']))
		  {
            $this->core->set['active'] = 0;
            $this->core->save('t_mtr_site_l_used_status', array('site_id' => $site_id));

            $this->core->set['site_id'] = $site_id;
            $this->core->set['date_status'] = null_empty($post['dateStatusOfSite']);
            $this->core->set['status'] = $post['statusOfSiteUsed'];
            $this->core->set['remarks'] = $post['statusOfSiteRemarks'];
            $this->core->set['active'] = 1;
            $this->core->set['created_on'] = $updated_date;
            $this->core->set['created_by'] = $updated_by;

            $this->core->save('t_mtr_site_l_used_status');

            $this->core->set['site_active'] = $post['statusOfSiteUsed'];
            $this->core->save('t_mtr_site', array('site_id' => $site_id));
          }

          $json = array('error' => FALSE, 'message' => 'Operational has been updated successfully.', 'site_id' => $site_id);
        }
		else
		{
          $json = array('error' => TRUE, 'message' => $error, 'site_id' => $site_id);
        }

        echo json_encode($json);
        exit;
      }
    }

    if ($this->input->post('cmd') == 'EDIT_PHOTO_REFERENCE')
	{
      $json = array();
      $upload_dir = FCPATH . 'file/upload/site-info/file-reference';
      $upload_cache_dir = FCPATH . 'file/upload/cache/site-info/file-reference';
      $upload_url = base_url('/file/upload/site-info/file-reference');

      if (!is_dir($upload_dir))
	  {
        mkdir($upload_dir, 0777);
      }

      if ($this->input->post('act') == 'SAVE') // save data
	  {
        $post = $this->core->get_post();
        $error = array();

        if ($this->_is_field_empty($post['siteId']))
		{
          $error[] = 'General Info is not complete.';
        }
        if ($this->_is_field_empty($post['fileType']))
		{
          $error[] = 'File Type can not unselected.';
        }
        if ($this->_is_field_empty($post['file']))
		{
          $error[] = 'File can not empty.';
        }

        if (count($error) == 0)
		{
          $site_id = $this->input->post('siteId');
          $fileType = $this->input->post('fileType');
          $fileName = $this->input->post('file');
          $created_by = $updated_by = $this->session->userdata('user_id');
          $created_on = $updated_on = date('Y-m-d H:i:s');

          $this->db->delete('t_mtr_site_l_photo_reference', array('site_id' => $site_id));

          if (@is_array($fileType))
		  {
            ksort($fileType);
            foreach ($fileType as $k_type => $v_type)
			{
              $this->core->set['site_id'] = $site_id;
              $this->core->set['photo_type'] = $fileType[$k_type];
              $this->core->set['photo_name'] = $fileName[$k_type];
              $this->core->set['updated_by'] = $updated_by;
              $this->core->set['updated_on'] = $updated_on;

              $this->core->save('t_mtr_site_l_photo_reference');
            }
          }

          unset($this->core->set);
          $this->core->set['updated_on'] = $updated_on;
          $this->core->set['updated_by'] = $updated_by;
          $this->core->save('t_mtr_site', array('site_id' => $site_id));

          $json = array('error' => FALSE, 'message' => 'Photo Reference has been updated successfully.', 'site_id' => $site_id);
        }
		else
		{
          $json = array('error' => TRUE, 'message' => $error, 'site_id' => $site_id);
        }
      }
	  elseif ($this->input->post('act') == 'UPLOAD') // just upload file
	  {
        $allowed = array(
            '.jpg',
            '.jpeg',
            '.gif',
            '.png',
            '.doc',
            '.docx',
            '.xls',
            '.xlsx',
            '.pdf',
            '.txt'
        );

        $upload_key = array_keys($_FILES);

        if (!empty($_FILES[$upload_key[0]]['name']) && $_FILES[$upload_key[0]]['error'] == UPLOAD_ERR_OK)
		{
          $tmp_name = $_FILES[$upload_key[0]]['tmp_name'];
          $ext = strtolower(strrchr($_FILES[$upload_key[0]]['name'], '.'));
          $file_name = $this->input->post('image') ? $this->input->post('image') : time() . $ext;

          if (!in_array($ext, $allowed))
		  {
            $json = array('error' => TRUE, 'message' => 'Invalid file type. Please just upload file [.jpg, .gif, .png] / [.doc, docx, xls, xlsx, pdf, txt]');
          }
		  else
		  {
            if (is_safe_upload_name($file_name) && @move_uploaded_file($tmp_name, $upload_dir . '/' . $file_name))
			{
              $file = $upload_url . '/' . $file_name;
              $thumb = '';
              if (in_array($ext, array('.jpg', '.jpeg', '.gif', '.png')))
			  {
                // resize first
                $image = $upload_dir . '/' . $file_name;
                $size = getimagesize($image);
                $width = $size[0];
                $height = $size[1];
                $expected_width = 550;
                $expected_height = 550;
                if ($width > $expected_width && $height > $expected_height)
				{
                  $img = new Image($image);
                  $img->resizeImage($expected_width, $expected_height, 'auto');
                  $img->saveImage($image, 80);
                }

                $thumb = $this->image_model->resize($image, 60, 60, 'crop');
              }

              $json = array('error' => FALSE, 'ref' => $upload_key[0], 'file_name' => $file_name, 'file' => $file, 'thumb' => $thumb, 'message' => 'File uploaded.');
            }
          }
        }
      }
	  elseif ($this->input->post('act') == 'DELETE')
	  {
        $file_name = $this->input->post('file_name');
        $pathinfo = pathinfo($file_name);
        $ext = strtolower(strrchr($pathinfo['basename'], '.'));

        if (is_file($upload_dir . '/' . $pathinfo['basename']) && file_exists($upload_dir . '/' . $pathinfo['basename']))
		{
          @unlink($upload_dir . '/' . $pathinfo['basename']);
        }

        if (in_array($ext, array('.jpg', '.jpeg', '.gif', '.png')))
		{
          if (is_file($upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension']) && file_exists($upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension']))
		  {
            @unlink($upload_cache_dir . '/' . $pathinfo['filename'] . '-60x60.' . $pathinfo['extension']);
          }
        }

        $json = array('error' => FALSE, 'message' => 'File has been deleted successfully.');

        echo json_encode($json);
        exit;
      }

      echo json_encode($json);
      exit;
    }


    /*
      if ($this->input->post('cmd') == 'EDIT_PHOTO_REF') {

      $site_id = $this->input->post('siteId');
      $fileType = $this->input->post('fileType');
      $fileName = time();
      $upload_dir = FCPATH.'file/upload/photo-ref';

      $created_by = $updated_by = $this->session->userdata('user_id');
      $created_on = $updated_on = date('Y-m-d H:i:s');

      $this->db->delete('t_mtr_site_l_photo_reference', array('site_id' => $site_id));

      foreach ($fileType as $k_type => $v_type) {
      $tmp_name = $_FILES['file']['tmp_name'][$k_type];
      $ext = end(explode('.', $_FILES['file']['name'][$k_type]));
      $fileName = time().'.'.$ext;

      if (is_safe_upload_name($fileName) && @move_uploaded_file($tmp_name, $upload_dir.'/'.$fileName)) {
      $this->core->set['site_id'] = $site_id;
      $this->core->set['photo_type'] = $fileType[$k_type];
      $this->core->set['photo_name'] = $fileName;
      $this->core->set['updated_by'] = $updated_by;
      $this->core->set['updated_on'] = $updated_on;

      $this->core->save('t_mtr_site_l_photo_reference');

      $this->session->set_flashdata('input_photo_ref_succ', 'Photo Reference saved.');

      redirect($this->uri->uri_string());
      }
      }
      }
     */

    $this->_site_form($site_id);
  }

  public function view($site_id)
  {
    $this->_check_valid_loggedin();

    if (!$this->mmenu->cekPermission($this->_partials['menu'], 'menu_view'))
	{
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }

    $site_id = (int) $this->core->decode($site_id);

    if (!$this->core->view_row('t_mtr_site', array('site_id' => $site_id))) {
      show_404();
    }

    $data['general_info'] = $this->core->view_row('t_mtr_site', array('site_id' => $site_id));

    if ($this->core->is_ajax())
	{
      if ($this->input->get('load_content') == 'GENERAL_INFO_VIEW')
	  {
        $data['general_info'] = $this->core->view_row('t_mtr_site', array('site_id' => $site_id));

        echo $this->parser->parse('partials/content_general_info_view', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'SITAC_VIEW')
	  {
        $data['lahan'] = $this->core->view_row('t_mtr_site_l_lahan', array('site_id' => $site_id));
        $data['perijinan'] = $this->core->view_row('t_mtr_site_l_perijinan', array('site_id' => $site_id));

        echo $this->parser->parse('partials/content_sitac_view', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'CME_VIEW')
	  {
        $data['tower'] = $this->site_info_model->get_tower($site_id);
        $data['shelter'] = $this->core->view_row('t_mtr_site_l_shelter', array('site_id' => $site_id));
        $data['pln'] = $this->core->view_row('t_mtr_site_l_pln', array('site_id' => $site_id));
        $data['ac'] = $this->core->view_row('t_mtr_site_l_ac', array('site_id' => $site_id));
        @$data['ac']['detail'] = $this->core->view_all('t_mtr_site_l_ac_detail', array('ac_id' => $data['ac']['ac_id']), 'ac_id');

        echo $this->parser->parse('partials/content_cme_view', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'OPERATIONAL_VIEW')
	  {
        $data['site_keeper'] = $this->core->view_row('t_mtr_site_l_keepers', array('site_id' => $site_id));
        $data['general_info'] = $this->core->view_row('t_mtr_site', array('site_id' => $site_id));
        $data['tenancy_sell'] = $this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_SELL'));
        @$data['tenancy_sell']['detail'] = $this->core->view_all('t_mtr_site_l_tenancy_detail', array('tenancy_id' => $data['tenancy_sell']['tenancy_id']), 'tenancy_id');
        $data['tenancy_buy'] = $this->core->view_row('t_mtr_site_l_tenancy', array('site_id' => $site_id, 'tenancy_type' => 'COLLOC_BUY'));
        @$data['tenancy_buy']['detail'] = $this->core->view_all('t_mtr_site_l_tenancy_detail', array('tenancy_id' => $data['tenancy_buy']['tenancy_id']), 'tenancy_id');

        $data['site_last_status'] = $this->db->query("
					SELECT status.date_status, lo1.lookup_desc AS status, status.remarks, status.created_on, status.created_by
					FROM t_mtr_site_l_last_status status
					LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = status.status::VARCHAR AND lo1.lookup_key = 'site_last_status' AND lo1.active = 1
					WHERE status.site_id = {$site_id}
					ORDER BY date_status ASC
				")->result_array();

        $data['site_status'] = $this->core->view_all('t_mtr_site_l_used_status', array('site_id' => $site_id), 'created_on');

        echo $this->parser->parse('partials/content_operational_view', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'EQUIPMENT_VIEW')
	  {
        $data['pln'] = $this->core->view_row('t_mtr_site_l_pln', array('site_id' => $site_id));

        echo $this->parser->parse('partials/content_equipment', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'CANDIDATE_VIEW')
	  {
        $data['site_id'] = $this->input->get('site_id');

        echo $this->parser->parse('partials/content_candidate', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'PLAN_INFO_VIEW')
	  {
        $site = $this->core->view_row('t_mtr_site', array('site_id' => $site_id));

        $data['plan'] = $this->db
                ->query("
						SELECT plan.*, lo1.lookup_desc AS bts_type_name FROM t_mtr_site_plan plan
						LEFT JOIN t_mtr_lookup lo1 ON CAST(lo1.lookup_value AS INTEGER) = plan.bts_type AND lo1.lookup_key = 'bts_type'
						WHERE plan.nom_site_code = '" . $site['site_code'] . "'
					")
                ->row_array();

        echo $this->parser->parse('partials/content_plan_info', $data);
        exit;
      }

      if ($this->input->get('load_content') == 'PHOTO_REFERENCE_VIEW')
	  {
        $site_id = (int) $this->core->decode($this->uri->segment(3));
        $result = $this->core->view_all('t_mtr_site_l_photo_reference', array('site_id' => $site_id), 'photo_ref_id');
        $dir = FCPATH . 'file/upload/site-info/file-reference';

        $data['photo_reference'] = array();
        foreach ($result as $r)
		{
          if (is_file($dir . '/' . $r['photo_name']) && file_exists($dir . '/' . $r['photo_name']))
		  {
            $r['file'] = base_url('/file/upload/site-info/file-reference/' . $r['photo_name']);
            $r['file_path'] = FCPATH . 'file/upload/site-info/file-reference/' . $r['photo_name'];

            $ext = end(explode('.', $r['photo_name']));
            $thumb = '';
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif')))
			{
              $thumb = $this->image_model->resize($dir . '/' . $r['photo_name'], 60, 60, 'crop');
            }

            $r['thumb'] = $thumb;
            $r['ext'] = $ext;

            $data['photo_reference'][] = $r;
          }
        }

        echo $this->parser->parse('partials/content_photo_reference_view', $data);
        exit;
      }
    }

    $data['content_id'] = 'site_info_view';

    $this->load->view('template/smashing', $data);
  }

  private function _site_form($id = 0)
  {
    $propinsi = $this->core->view_all('t_mtr_propinsi', NULL, 'descr');
    $data['propinsi'] = array('' => '- Choose one -');
    foreach ($propinsi as $prop)
	{
		$data['propinsi'][$prop['propinsi_id']] = $prop['descr'];
    }

    $data['kabupaten'] = array('' => '- Choose one -');
    $data['kecamatan'] = array('' => '- Choose one -');

    $data['cmd_input_general_info'] = form_hidden('cmd', set_value('cmd', 'ADD_GENERAL_INFO'));
    $data['cmd_input_site_keepers'] = form_hidden('cmd', set_value('cmd', 'ADD_SITE_KEEPERS'));
    $data['cmd_input_pln'] = form_hidden('cmd', set_value('cmd', 'ADD_PLN'));
    $data['cmd_input_tower'] = form_hidden('cmd', set_value('cmd', 'ADD_TOWER'));
    $data['cmd_input_shelter'] = form_hidden('cmd', set_value('cmd', 'ADD_SHELTER'));
    $data['cmd_input_lahan'] = form_hidden('cmd', set_value('cmd', 'ADD_LAHAN'));
    $data['cmd_input_perijinan'] = form_hidden('cmd', set_value('cmd', 'ADD_PERIJINAN'));
    $data['cmd_input_tenancy'] = form_hidden('cmd', set_value('cmd', 'ADD_TENANCY'));
    $data['cmd_input_ac'] = form_hidden('cmd', set_value('cmd', 'ADD_AC'));
    $data['cmd_input_equipment'] = form_hidden('cmd', set_value('cmd', 'ADD_EQUIPMENT'));

    if ($this->uri->segment(2) == 'edit' && !empty($id))
	{
      if ($this->core->view_row('t_mtr_site', array('site_id' => $id)))
	  {

        $data['cmd_input_general_info'] = form_hidden('cmd', set_value('cmd', 'EDIT_GENERAL_INFO'));
        $data['cmd_input_site_keepers'] = form_hidden('cmd', set_value('cmd', 'EDIT_SITE_KEEPERS'));
        $data['cmd_input_pln'] = form_hidden('cmd', set_value('cmd', 'EDIT_PLN'));
        $data['cmd_input_tower'] = form_hidden('cmd', set_value('cmd', 'EDIT_TOWER'));
        $data['cmd_input_shelter'] = form_hidden('cmd', set_value('cmd', 'EDIT_SHELTER'));
        $data['cmd_input_lahan'] = form_hidden('cmd', set_value('cmd', 'EDIT_LAHAN'));
        $data['cmd_input_perijinan'] = form_hidden('cmd', set_value('cmd', 'EDIT_PERIJINAN'));
        $data['cmd_input_tenancy'] = form_hidden('cmd', set_value('cmd', 'EDIT_TENANCY'));
        $data['cmd_input_ac'] = form_hidden('cmd', set_value('cmd', 'EDIT_AC'));
        $data['cmd_input_equipment'] = form_hidden('cmd', set_value('cmd', 'EDIT_EQUIPMENT'));

        $general_info = $this->site_info_model->get_general_info_by_id($id);
        $data['general_info'] = $general_info;

        $site_keepers = $this->site_info_model->get_site_keepers_by_id($id);
        $data['site_keepers'] = $site_keepers;

        $data['ac'] = $this->core->view_row('t_mtr_site_l_ac', array('site_id' => $id));
        $data['perijinan'] = $this->core->view_row('t_mtr_site_l_perijinan', array('site_id' => $id));
        $data['lahan'] = $this->core->view_row('t_mtr_site_l_lahan', array('site_id' => $id));
        $data['shelter'] = $this->core->view_row('t_mtr_site_l_shelter', array('site_id' => $id));
        $data['pln'] = $this->core->view_row('t_mtr_site_l_pln', array('site_id' => $id));
        $data['tower'] = $this->site_info_model->get_tower($id);

        $kabupaten = $this->site_info_model->get_kabupaten($general_info['propinsi_id']);
        $data['kabupaten'] = array('' => '- Choose one -');
        foreach ($kabupaten as $kab)
		{
			$data['kabupaten'][$kab['kabupaten_id']] = $kab['descr'];
        }

        $kecamatan = $this->site_info_model->get_kecamatan($general_info['kabupaten']);
        $data['kecamatan'] = array('' => '- Choose one -');
        foreach ($kecamatan as $kec)
		{
          $data['kecamatan'][$kec['kecamatan_id']] = $kec['descr'];
        }

        $data['tenancy_sell'] = $this->core->view_all('t_mtr_site_l_tenancy', array('site_id' => $id, 'tenancy_type' => 'COLLOC_SELL'));
        $data['tenancy_buy'] = $this->core->view_all('t_mtr_site_l_tenancy', array('site_id' => $id, 'tenancy_type' => 'COLLOC_BUY'));
        $data['equipment'] = $this->core->view_row('t_mtr_site_l_equipment', array('site_id' => $id));
      }
	  else
	  {
        show_404();
      }
    }

    $data['content_id'] = 'site_info_form';

    $this->load->view('template/smashing', $data);
  }

  public function tower_provider2()
  {
    $this->_check_valid_loggedin();

    $this->load->library('pagination');

    $per_page = 20;
    $offset = $this->uri->segment(4);
    $data['tower_provider2'] = $this->site_info_model->get_all_tower_provider2($per_page, $offset);

    $config = array(
        'base_url' => site_url('/referensi/wilayah/propinsi'),
        'total_rows' => $this->site_info_model->get_rows_tower_provider2(),
        'per_page' => $per_page,
        'uri_segment' => 4
    );

    $this->pagination->initialize($config);
    $data['pagination'] = $this->pagination->create_links();

    if ($this->core->is_ajax())
	{
      if ($this->input->get('cmd') == 'GET_TOWER_PROVIDER2')
	  {
        $json = $this->core->view_all('t_mtr_tower_provider2', array('active' => 1), 'no');

        echo json_encode($json);
        exit;
      }
    }

    $this->theme->set('page_title', 'PMTS - Site Info - Tower Provider');
    $this->theme->view('tower_provider2_view', $data);
  }

  //////////////--> Tower Provider <--//////////////////////////////////////////////
  public function add_tower_provider2()
  {
    $this->_check_valid_loggedin();

    $this->form_validation->set_rules('no', 'ID', 'readonly');
    $this->form_validation->set_rules('tpName', 'TP Name', 'required');
    $this->form_validation->set_rules('siteId', 'Site ID', 'required');
    $this->form_validation->set_rules('siteName', 'Site Name', 'required');
    $this->form_validation->set_rules('province', 'Province', 'required');
    $this->form_validation->set_rules('region', 'Region', 'required');
    $this->form_validation->set_rules('city', 'City', 'required');
    $this->form_validation->set_rules('status', 'Status', 'required');
    $this->form_validation->set_rules('lattitude', 'Latitude', 'required');
    $this->form_validation->set_rules('longitude', 'Longitude', 'required');
    $this->form_validation->set_rules('siteType', 'Site Type', 'required');
    $this->form_validation->set_rules('towerType', 'Tower Type', 'required');
    $this->form_validation->set_rules('buildingHeightMtr', 'Buildin Height (m)', 'required');
    $this->form_validation->set_rules('towerHeightMtr', 'Tower Height (m)', 'required');
    $this->form_validation->set_rules('address', 'Address', 'required');
    $this->form_validation->set_rules('shareableStatus', 'Shareable Status', 'required');
    $this->form_validation->set_rules('siteCode', 'Site Code', 'required');
    $this->form_validation->set_rules('remark', 'Remark', 'required');
    $this->form_validation->set_rules('userId', 'User ID', 'required');
    $this->form_validation->set_rules('propinsiId', 'Propinsi ID', 'required');
    $this->form_validation->set_rules('regionId', 'Region ID', 'required');
    $this->form_validation->set_rules('cityId', 'City ID', 'required');
    $this->form_validation->set_rules('statusTpId', 'Status TP ID', 'required');
    $this->form_validation->set_rules('typeId', 'Type ID', 'required');
    $this->form_validation->set_rules('towerTypeId', 'Tower Type ID', 'required');
    $this->form_validation->set_rules('partnerId', 'Partner ID', 'required');
    $this->form_validation->set_rules('active', 'Active', 'required');
    $this->form_validation->set_rules('kandidat', 'Kandidat', 'required');

    $this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');

    if ($this->form_validation->run())
	{
      $post = $this->core->get_post();

      $this->core->set['no'] = $post['no'];
      $this->core->set['tp_name'] = $post['tpName'];
      $this->core->set['site_id'] = $post['siteId'];
      $this->core->set['site_name'] = $post['siteName'];
      $this->core->set['province'] = $post['province'];
      $this->core->set['region'] = $post['region'];
      $this->core->set['city'] = $post['city'];
      $this->core->set['status'] = $post['status'];
      $this->core->set['lattitude'] = $post['lattitude'];
      $this->core->set['longitude'] = $post['longitude'];
      $this->core->set['site_type'] = $post['siteType'];
      $this->core->set['tower_type'] = $post['towerType'];
      $this->core->set['building_height_mtr'] = $post['buildingHeightMtr'];
      $this->core->set['tower_height_mtr'] = $post['towerHeightMtr'];
      $this->core->set['address'] = $post['address'];
      $this->core->set['shareable_status'] = $post['shareableStatus'];
      $this->core->set['site_code'] = $post['siteCode'];
      $this->core->set['remark'] = $post['remark'];
      $this->core->set['user_id'] = $post['userId'];
      $this->core->set['propinsi_id'] = $post['propinsiId'];
      $this->core->set['region_id'] = $post['regionId'];
      $this->core->set['city_id'] = $post['cityId'];
      $this->core->set['status_tp_id'] = $post['statusTpId'];
      $this->core->set['type_id'] = $post['typeId'];
      $this->core->set['tower_type_id'] = $post['towerTypeId'];
      $this->core->set['partner_id'] = $post['partnerId'];
      $this->core->set['active'] = $post['active'];
      $this->core->set['kandidat'] = $post['kandidat'];

      $this->core->save('t_mtr_tower_provider2');

      redirect('/site_info/tower_provider2');
    }

    $this->_form_tower_provider2();
  }

  public function edit_tower_provider2($id)
  {
    $this->_check_valid_loggedin();

    $this->form_validation->set_rules('no', 'ID', 'readonly');
    $this->form_validation->set_rules('tpName', 'TP Name', 'required');
    $this->form_validation->set_rules('siteId', 'Site ID', 'required');
    $this->form_validation->set_rules('siteName', 'Site Name', 'required');
    $this->form_validation->set_rules('province', 'Province', 'required');
    $this->form_validation->set_rules('region', 'Region', 'required');
    $this->form_validation->set_rules('city', 'City', 'required');
    $this->form_validation->set_rules('status', 'Status', 'required');
    $this->form_validation->set_rules('lattitude', 'Latitude', 'required');
    $this->form_validation->set_rules('longitude', 'Longitude', 'required');
    $this->form_validation->set_rules('siteType', 'Site Type', 'required');
    $this->form_validation->set_rules('towerType', 'Tower Type', 'required');
    $this->form_validation->set_rules('buildingHeightMtr', 'Buildin Height (m)', 'required');
    $this->form_validation->set_rules('towerHeightMtr', 'Tower Height (m)', 'required');
    $this->form_validation->set_rules('address', 'Address', 'required');
    $this->form_validation->set_rules('shareableStatus', 'Shareable Status', 'required');
    $this->form_validation->set_rules('siteCode', 'Site Code', 'required');
    $this->form_validation->set_rules('remark', 'Remark', 'required');
    $this->form_validation->set_rules('userId', 'User ID', 'required');
    $this->form_validation->set_rules('propinsiId', 'Propinsi ID', 'required');
    $this->form_validation->set_rules('regionId', 'Region ID', 'required');
    $this->form_validation->set_rules('cityId', 'City ID', 'required');
    $this->form_validation->set_rules('statusTpId', 'Status TP ID', 'required');
    $this->form_validation->set_rules('typeId', 'Type ID', 'required');
    $this->form_validation->set_rules('towerTypeId', 'Tower Type ID', 'required');
    $this->form_validation->set_rules('partnerId', 'Partner ID', 'required');
    $this->form_validation->set_rules('active', 'Active', 'required');
    $this->form_validation->set_rules('kandidat', 'Kandidat', 'required');

    $this->form_validation->set_error_delimiters($prefix = '<div>', $suffix = '</div>');

    if ($this->form_validation->run())
	{
      $post = $this->core->get_post();

      $this->core->set['no'] = $post['no'];
      $this->core->set['tp_name'] = $post['tpName'];
      $this->core->set['site_id'] = $post['siteId'];
      $this->core->set['site_name'] = $post['siteName'];
      $this->core->set['province'] = $post['province'];
      $this->core->set['region'] = $post['region'];
      $this->core->set['city'] = $post['city'];
      $this->core->set['status'] = $post['status'];
      $this->core->set['lattitude'] = $post['lattitude'];
      $this->core->set['longitude'] = $post['longitude'];
      $this->core->set['site_type'] = $post['siteType'];
      $this->core->set['tower_type'] = $post['towerType'];
      $this->core->set['building_height_mtr'] = $post['buildingHeightMtr'];
      $this->core->set['tower_height_mtr'] = $post['towerHeightMtr'];
      $this->core->set['address'] = $post['address'];
      $this->core->set['shareable_status'] = $post['shareableStatus'];
      $this->core->set['site_code'] = $post['siteCode'];
      $this->core->set['remark'] = $post['remark'];
      $this->core->set['user_id'] = $post['userId'];
      $this->core->set['propinsi_id'] = $post['propinsiId'];
      $this->core->set['region_id'] = $post['regionId'];
      $this->core->set['city_id'] = $post['cityId'];
      $this->core->set['status_tp_id'] = $post['statusTpId'];
      $this->core->set['type_id'] = $post['typeId'];
      $this->core->set['tower_type_id'] = $post['towerTypeId'];
      $this->core->set['partner_id'] = $post['partnerId'];
      $this->core->set['active'] = $post['active'];
      $this->core->set['kandidat'] = $post['kandidat'];

      $this->core->save('t_mtr_tower_provider2', array('no' => $id));

      redirect('/site_info/tower_provider2');
    }

    $this->_form_tower_provider2($id);
  }

  private function _form_tower_provider2($id = '')
  {
    $data = array();

    if (!empty($id) && $this->uri->segment(3) == 'edit_tower_provider2')
	{

      $data['tower_provider2'] = $this->core->view_row('t_mtr_tower_provider2', array('no' => $id));
    }

    $this->theme->set_layout('site_info-layout');
    $this->theme->set('page_title', 'PMTS - Site Info - Tower Provider ');
    $this->theme->view('form_tower_provider2_view', $data);
  }

  public function delete_tower_provider2($id)
  {
    $this->core->update('t_mtr_tower_provider2', array('status' => 0), array('no' => $id));

    redirect('/site_info/tower_provider2');
  }

  public function site_general()
  {
    $this->_check_valid_loggedin();

    $this->load->library('pagination');

    $per_page = 20;
    $offset = $this->uri->segment(4);
    $data['site_general'] = $this->site_info_model->get_all_site($per_page, $offset);

    $config = array(
        'base_url' => base_url('/referensi/wilayah/propinsi'),
        'total_rows' => $this->site_info_model->get_rows_site(),
        'per_page' => $per_page,
        'uri_segment' => 4
    );

    $this->pagination->initialize($config);
    $data['pagination'] = $this->pagination->create_links();

    $this->theme->view('site_general_view', $data);
  }

  private function _check_valid_loggedin()
  {
	if (!$this->session->userdata('is_login'))
	{
      if ($this->core->is_ajax())
	  {
        echo json_encode(array(
            'loggedin' => FALSE,
            'message' => 'You are not logged in. You will be redirected to login page.',
            'redirect_url' => site_url('/login')
        ));
        exit;
      }
	  else
	  {
        redirect('/login');
      }
    }
  }

  private function _is_field_empty($field)
  {
    if (@is_array($field))
	{
      $i = 0;
      foreach ($field as $arr)
	  {
        if (trim($arr) == '' || (trim($arr) != '' && $arr == '0') || (trim($arr) != '' && $arr == '0.00'))
		{
          $i++;
        }
      }

      if ($i > 0)
	  {
        return TRUE;
      }
	  else
	  {
        return FALSE;
      }
    }
	else
	{
      if (trim($field) == '' || (trim($field) != '' && $field == '0') || (trim($field) != '' && $field == '0.00'))
	  {
        return TRUE;
      }
	  else
	  {
        return FALSE;
      }
    }
  }

  public function lookup_site()
  {
    $data['judul'] = "Site List";
    $data['seq'] = $this->input->get('seq');
    $this->load->view("site_lookup", $data);
    return;
  }

  public function get_lookup_site()
  {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : '';
    $offset = ($page - 1) * $rows;
    $sites = $this->site_info_model->get_site_lookup($q, $rows, $offset);

    echo json_encode($sites);
  }

  public function get_address()
  {
    $site_id = $this->input->post('site_id');
    $data = $this->site_info_model->get_address($site_id);
    echo $data->site_address;
  }
  
}
