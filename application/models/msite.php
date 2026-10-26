<?php
class msite extends CI_Model {
	function Msite(){
		parent::__construct();
	}
	
	############################################
	function getMenuDetail( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT * FROM t_mtr_menu WHERE id = $id ";
		$query = $dblokal->query($q);
		return $query->result();
	}
	
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','parent', 'nama', 'url');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$data = array('parent_id' => $parent,
			      'name' => $nama,
			      'alias' => $url,
			      'published'=>1 );
		
		if($id == "") {
			$data['created'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			
			$dblokal->insert('t_mtr_menu', $data);
		}else{
			$data['updated'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			
			$dblokal->where(array('id'=>$id));
			$dblokal->update('t_mtr_menu', $data);
		}
	}
	
	public function deleteData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$dblokal->where( array('site_id'=>$id) );
		$dblokal->update('t_mtr_site_general',array('status'=>0));
	}
	
	public function loadData() {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND site_name LIKE '%$query%' ";
		
		$q = "SELECT * FROM t_mtr_site_general WHERE status=1 $where";
			
		$query = $dblokal->query( $q );
		$total = $query->num_rows;
		$npage = ceil($total / $dataperpage);
		 
		$start = $curpage * $dataperpage;
		$end = $start + $dataperpage;
		
		$q .= " ORDER BY site_id
			LIMIT $dataperpage OFFSET $start";
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
		      'site_id' => $row->site_id,
		      'site_name' => $row->site_name,
		      'old_site_id' => $row->old_site_id,
                      'latitude' => $row->latitude,
                      'longitude' => $row->longitude
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
	############################################
	
}
?>