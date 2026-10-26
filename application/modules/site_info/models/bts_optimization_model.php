<?php

class Bts_optimization_model extends CI_Model {

	public function load_bts_optimization_list()
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
		$search = $this->input->get('query');
		$order = $this->input->get('order');
		$sort = $this->input->get('sort');
		
		$where = " bo.active = 1";
		$where .= " AND (UPPER(s.site_code) LIKE '%".strtoupper($search)."%'";
		$where .= " OR UPPER(bo.vendor_engineer) LIKE '%".strtoupper($search)."%'";
		$where .= " OR UPPER(bo.vendor_rigger) LIKE '%".strtoupper($search)."%'";
		$where .= " OR UPPER(bo.sf_engineer) LIKE '%".strtoupper($search)."%')";
		$sql = "
			SELECT bo.visit_id, s.site_code, bo.visit_date, bo.vendor_engineer, bo.vendor_rigger, bo.sf_engineer
			FROM t_trx_bts_optimization bo
			LEFT JOIN t_mtr_site s ON s.site_id = bo.site_id
			WHERE {$where}			
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		if ($order) {
			$sql .= " ORDER BY {$order} {$sort}";
		} else {
			$sql .= " ORDER BY visit_id DESC";
		}
		
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/bts_optimization/index');
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
		$data['rec_info']= 'Records from '.($total_rows > 0 ? ($offset + 1) : 0).' to '.($offset + $total_rows_perpage).' of '.$total_rows;
		$data['data'] = $query->result_array();
		$data['pagination'] = $this->pagination->create_links();
		$data['sql'] = str_replace("\t", ' ', str_replace("\n", '', $sql));
		
		return $data;
	}
	
	public function get_bts_list($site_id)
	{
		return $this->db
			->query("
				SELECT * FROM t_mtr_site_l_equipment_rf WHERE site_id = {$site_id} AND active = 1
				ORDER BY equipment_rf_id ASC
			")
			->result_array();
	}
	
	public function get_bts_optimization($visit_id)
	{
		return $this->db
			->select('o.*, s.site_code')
			->join('t_mtr_site s', 's.site_id = o.site_id', 'left')
			->where('o.active', 1)
			->where('o.visit_id', $visit_id)
			->get('t_trx_bts_optimization o')
			->row_array();
	}
	
	public function load_bts_list($visit_id)
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
		$search = $this->input->get('query');
		$order = $this->input->get('order');
		$sort = $this->input->get('sort');
		
		$where = " adj.visit_id = {$visit_id}";
		//$where .= " AND (UPPER(s.site_code) LIKE '%".strtoupper($search)."%'";
		$sql = "
			SELECT adj.azimuth_after, adj.electrical_tilt_after, adj.mechanical_tilt_after, loo.lookup_desc AS photo_cat, photo_name
			FROM t_trx_bts_optimization_adjust adj
			LEFT JOIN t_trx_bts_optimization_photo pho ON pho.equipment_bts_id = adj.equipment_bts_id
			LEFT JOIN t_mtr_lookup loo ON loo.lookup_value = pho.photo_cat AND loo.lookup_key = 'bts_jenis_foto'
			WHERE {$where}			
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		if ($order) {
			$sql .= " ORDER BY {$order} {$sort}";
		} else {
			$sql .= " ORDER BY adj.visit_id DESC";
		}
		
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/bts_optimization/index');
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
		$data['rec_info']= 'Records from '.($total_rows > 0 ? ($offset + 1) : 0).' to '.($offset + $total_rows_perpage).' of '.$total_rows;
		$data['data'] = $query->result_array();
		$data['pagination'] = $this->pagination->create_links();
		$data['sql'] = str_replace("\t", ' ', str_replace("\n", '', $sql));
		
		return $data;
	}
}