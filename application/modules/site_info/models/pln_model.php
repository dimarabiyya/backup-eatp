<?php

class Pln_model extends CI_Model {

	public function load_pln_list()
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
		$search = $this->db->escape_str($this->input->get('query'));
		$order = $this->input->get('order');
		$sort = $this->input->get('sort');
		
		$where = " p.active = 1";
		$where .= " AND (UPPER(s.site_code) LIKE '%".strtoupper($search)."%'";
		$where .= " OR UPPER(c.descr) LIKE '%".strtoupper($search)."%')";
		
		$sql = "
			SELECT p.id, p.site_id, s.site_code, p.tahun, p.bulan, p.biaya, c.descr AS payment_method, p.kwh_awal, p.kwh_akhir, p.pemakaian_kwh
			FROM t_trx_site_pln_usage p
			LEFT JOIN t_mtr_site s ON s.site_id = p.site_id
			LEFT JOIN t_mtr_cara_pembayaran c ON c.cara_pembayaran_id = p.payment_method_id
			WHERE {$where}			
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		if ($order) {
			$sql .= " ORDER BY {$order} {$sort}";
		} else {
			$sql .= " ORDER BY id DESC";
		}
		
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/pln/index');
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
		
		$results = $query->result_array();
		$rows = array();
		foreach ($results as $r) {
			$r['site_id'] = $this->core->encode($r['site_id']);
			$rows[] = $r;
		}
		
		$data['total_rows'] = $total_rows;
		$data['rec_info']= 'Records from '.($total_rows > 0 ? ($offset + 1) : 0).' to '.($offset + $total_rows_perpage).' of '.$total_rows;
		$data['data'] = $rows;
		$data['pagination'] = $this->pagination->create_links();
		$data['sql'] = str_replace("\t", ' ', str_replace("\n", '', $sql));
		
		return $data;
	}
	
	public function get_pln($id)
	{
		return $this->db
			->query("
				SELECT pln.*, site.site_code, site.site_code_old, site.site_name, site.vendor_id, reg.region_name, pln.customer_id AS id_pelanggan_pln, pln2.atas_nama_pelanggan, pln2.company_code, pln2.cost_center, pln2.pln_capacity, lo2.lookup_desc AS tariff_name, lo3.lookup_desc AS tariff_type, lo1.lookup_desc AS company
				FROM t_trx_site_pln_usage pln
				LEFT JOIN t_mtr_site site ON site.site_id = pln.site_id
				LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id
				LEFT JOIN t_mtr_site_l_pln pln2 ON pln2.site_id = site.site_id
				LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = pln2.company_code::VARCHAR AND lo1.lookup_key = 'company' AND lo1.active = 1
				LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = pln2.tarif_name::VARCHAR AND lo2.lookup_key = 'pln_tariff_name' AND lo2.active = 1
				LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = pln2.tarif_type::VARCHAR AND lo3.lookup_key = 'tariff_type' AND lo3.active = 1
				WHERE pln.id = {$id}
			")
			->row_array();
	}

	public function get_pln_list()
	{
		$data = array();
		
		$site_id = $this->input->get('site_id');
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'ord';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		
		$search = $this->db->escape_str($this->input->get('query'));
		$year = $this->input->get('year');
		$month = $this->input->get('month');
		
		$region = array();
		foreach ($this->session->userdata['user_region'] as $reg) {
			if ($reg['region_id'] != '') {
				$region[] = $reg['region_id'];
			}
		}
		
		$where = "
			WHERE pln.active = 1
			AND (UPPER(site.site_code) LIKE '%".strtoupper($search)."%'
			OR UPPER(site.site_name) LIKE '%".strtoupper($search)."%'
			OR UPPER(site.vendor_id) LIKE '%".strtoupper($search)."%'
			OR UPPER(pln.customer_id) LIKE '%".strtoupper($search)."%'
			OR UPPER(CASE pln.bulan
				WHEN 1 THEN 'January'
				WHEN 2 THEN 'February'
				WHEN 3 THEN 'March'
				WHEN 4 THEN 'April'
				WHEN 5 THEN 'May'
				WHEN 6 THEN 'June'
				WHEN 7 THEN 'July'
				WHEN 8 THEN 'August'
				WHEN 9 THEN 'September'
				WHEN 10 THEN 'October'
				WHEN 11 THEN 'November'
				WHEN 12 THEN 'December'
			END) LIKE '%".strtoupper($search)."%'
			OR CAST(pln.tahun AS VARCHAR) LIKE '%".strtoupper($search)."%'
			OR UPPER(lo1.lookup_desc) LIKE '%".strtoupper($search)."%'
			OR site.company_code::VARCHAR LIKE '%".$search."%'
			OR UPPER(site.cost_center) LIKE '%".strtoupper($search)."%'
			OR CAST(pln.biaya AS VARCHAR) LIKE '%".strtoupper($search)."%'
			OR CAST(pln.kwh_awal AS VARCHAR) LIKE '%".strtoupper($search)."%'
			OR CAST(pln.kwh_akhir AS VARCHAR) LIKE '%".strtoupper($search)."%'
			OR CAST(pln.pemakaian_kwh AS VARCHAR) LIKE '%".strtoupper($search)."%')
		";
		if ($year != 0) {
			$where .= " AND pln.tahun = ".$year;
		}
		if ($month != 0) {
			$where .= "  AND pln.bulan = ".$month;
		}
		
		if (count($region > 0)) {
			$where .= "AND site.region_id IN (".implode(', ', $region).")";
		}
		
		$sql = "
			SELECT
				pln.id,
				site.site_id,
				site.site_code,
				site.site_name,
				site.vendor_id,
				mtr_pln.id_pelanggan_pln,
				pln.customer_id,
				pln.tahun,
				pln.bulan,
				pln.biaya,
				lo1.lookup_desc AS payment_method,
				pln.kwh_awal,
				pln.kwh_akhir,
				pln.pemakaian_kwh,
				site.company_code,
				site.cost_center,
				CASE WHEN pln.updated_on IS NULL THEN pln.created_on ELSE pln.updated_on END as ord
			FROM t_trx_site_pln_usage pln
			LEFT JOIN t_mtr_site site ON site.site_id = pln.site_id AND site.active = 1
			LEFT JOIN t_mtr_site_l_pln mtr_pln ON mtr_pln.site_id = pln.site_id AND mtr_pln.active = 1
			LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(pln.payment_method_id AS VARCHAR) AND lo1.lookup_key = 'payment_method' AND lo1.active = 1
			{$where}
			ORDER BY  {$sort} {$order}
		";
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
		
		$total_rows = (int) $query->row()->total;
		
		$sql .= " LIMIT {$rows} OFFSET {$offset}";
		
		$query = $this->db->query($sql);
		
		$rows = array();
		foreach ($query->result_array() as $r) {
			$r['id'] = $this->core->encode($r['id']);
			$rows[] = $r;
		}
		
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		
		$sql = str_replace("\t", '', $sql);
		$sql = str_replace("\n", ' ', $sql);
		$data['sql'] = $sql;
		
		return $data;
	}
}