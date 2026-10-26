<?php

class Wilayah_model extends CI_Model {
	
	public function get_wilayah_all($limit = 20, $offset = 0, $search = '')
	{
		$this->db->order_by('child_id', 'ASC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get('t_mtr_lokasi');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_total_rows()
	{
		return $this->db->count_all('t_mtr_lokasi');
	}
	
	public function get_propinsi()
	{
		$this->db->where('status', 1);
		$this->db->order_by('propinsi_id', 'ASC');
		$query = $this->db->get('t_mtr_propinsi');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
			
	public function get_kabupaten()
	{
		$this->db->where('status', 1);
		$this->db->order_by('kabupaten_id', 'ASC');
		$query = $this->db->get('t_mtr_kabupaten');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
		
	public function get_rows_kabupaten($parent_id = 7)
	{
		$this->db->where('jenis', 'KB');
		$this->db->where('parent_id', $parent_id);
		$this->db->from('t_mtr_lokasi');	
		return $this->db->count_all_results();
	}
	
	
	public function get_kecamatan()
	{
		$this->db->where('jenis', 'KC');
		$this->db->order_by('child_id', 'ASC');
		$query = $this->db->get('t_mtr_lokasi');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
		
	public function get_rows_kecamatan($parent_id = 7)
	{
		$this->db->where('jenis', 'KC');
		$this->db->where('parent_id', $parent_id);
		$this->db->from('t_mtr_lokasi');	
		return $this->db->count_all_results();
	}
	
	public function loadDataPropinsi() {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND descr LIKE '%$query%'";
		
		$q = "SELECT * FROM t_mtr_propinsi WHERE status=1 $where";
			
		$query = $dblokal->query( $q );
		$total = $query->num_rows;
		$npage = ceil($total / $dataperpage);
		 
		$start = $curpage * $dataperpage;
		$end = $start + $dataperpage;
		
		$q .= " ORDER BY propinsi_id
			LIMIT 5 OFFSET $start";
		$query = $dblokal->query( $q );
		
		$hasil = array(
			'data' => array(),
			'pagination' => '',
			'curpage' => $curpage,
			'numpage' => $npage - 1,
		);
		if ($query->num_rows() > 0) {
		  foreach ($query->result() as $row) {
		    $hasil['data'][] = array(
		      'propinsi_id' => $row->propinsi_id,
		      'descr' => $row->descr
		    );
		  }
		}
		
				    
		$hasil['pagination'] .= '<ul>
			<li class="'. ($curpage == 0 ? 'disabled' : '') .'" onclick="return Document.prevPage()"><a href>&laquo;</li>';
			$nmulai = 1;
			//if($curpage >= 5) $nmulai = $curpage - 5;
			for ($i = 1; $i <= $npage; $i++) {
				$hasil['pagination'] .= '<li class="' . ($curpage == ($i - 1) ? 'active' : '') . '" onclick="return Document.setPage(' . ($i - 1) .')"><a href>' . $i . '</a></li>';
			}
		$hasil['pagination'] .= '<li class="' . ($curpage == $npage - 1 ? 'disabled' : '') . '" onclick="return Document.nextPage()"><a href>&raquo;</a></li>
		  </ul>';
		 
		echo json_encode($hasil, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
	}
	
	
}