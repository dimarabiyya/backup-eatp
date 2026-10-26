<?php

class Power_model extends CI_Model {

	public function load_rectifier()
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$search = $this->input->get('query');
		$order = $this->input->get('order');
		$sort = $this->input->get('sort');
		
		$where = "1 = 1";// active = 1 AND (UPPER(site_code) LIKE '%".strtoupper($search)."%' OR UPPER(site_name) LIKE '%".strtoupper($search)."%')";
		$sql = "
			SELECT *
			FROM t_mtr_site_l_rectifier_detail WHERE {$where}			
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		if ($order) {
			$sql .= " ORDER BY {$order} {$sort}";
		} else {
			$sql .= " ORDER BY rectifier_id DESC";
		}
		
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/power');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 3;		
		
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
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
	
	public function load_acpdb()
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$search = $this->input->get('query');
		$order = $this->input->get('order');
		$sort = $this->input->get('sort');
		
		$where = "1 = 1";// active = 1 AND (UPPER(site_code) LIKE '%".strtoupper($search)."%' OR UPPER(site_name) LIKE '%".strtoupper($search)."%')";
		$sql = "
			SELECT *
			FROM t_mtr_site_l_acpdb_detail WHERE {$where}			
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		if ($order) {
			$sql .= " ORDER BY {$order} {$sort}";
		} else {
			$sql .= " ORDER BY acpdb_id DESC";
		}
		
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/power');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 3;		
		
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
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
	
	public function load_dcpdb()
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$search = $this->input->get('query');
		$order = $this->input->get('order');
		$sort = $this->input->get('sort');
		
		$where = "1 = 1";// active = 1 AND (UPPER(site_code) LIKE '%".strtoupper($search)."%' OR UPPER(site_name) LIKE '%".strtoupper($search)."%')";
		$sql = "
			SELECT *
			FROM t_mtr_site_l_dcpdb_detail WHERE {$where}			
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		if ($order) {
			$sql .= " ORDER BY {$order} {$sort}";
		} else {
			$sql .= " ORDER BY dcpdb_id DESC";
		}
		
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/power');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 3;		
		
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
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
	
	public function load_battery()
	{
		$per_page = $this->input->get('per_page');
		$offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$search = $this->input->get('query');
		$order = $this->input->get('order');
		$sort = $this->input->get('sort');
		
		$where = "1 = 1";// active = 1 AND (UPPER(site_code) LIKE '%".strtoupper($search)."%' OR UPPER(site_name) LIKE '%".strtoupper($search)."%')";
		$sql = "
			SELECT *
			FROM t_mtr_site_l_battery WHERE {$where}			
		";
		$query = $this->db->query($sql);
		
		$total_rows = $query->num_rows();
		
		if ($order) {
			$sql .= " ORDER BY {$order} {$sort}";
		} else {
			$sql .= " ORDER BY battery_id DESC";
		}
		
		$sql .= " LIMIT {$per_page} OFFSET {$offset}";
		$query = $this->db->query($sql);
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('/site_info/power');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 3;		
		
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
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