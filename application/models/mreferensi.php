<?php
class mreferensi extends CI_Model {
	function Mmenu(){
		parent::__construct();
	}
	
	############################################
	function getDataDetail( $id ){
		$dblokal = $this->load->database("default", TRUE);
		$q = "SELECT * FROM t_mtr_region WHERE region_id = $id ";
		$query = $dblokal->query($q);
		return $query->result();
	}
	
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id','region', 'pic', 'status');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$data = array('region_name' => $region);
		
		if($id == "") {
			$data['created_on'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->userdata('user_id');
			
			$dblokal->insert('t_mtr_menu', $data);
		}else{
			$data['updated_on'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('user_id');
			
			$dblokal->where(array('region_id'=>$id));
			$dblokal->update('t_mtr_region', $data);
		}
	}
	
	public function deleteData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('id', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		
		$dblokal->where( array('region_id'=>$id) );
		$dblokal->update('t_mtr_region',array('status'=>0));
	}
	
	public function loadData() {
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		 
		$query = $dblokal->escape_like_str($query);
		$where = " AND (region_name LIKE '%$query%')";
		
		$q = "select * from t_mtr_region 
			WHERE status=1 $where";
			
		$query = $dblokal->query( $q );
		$total = $query->num_rows;
		$npage = ceil($total / $dataperpage);
		 
		$start = $curpage * $dataperpage;
		$end = $start + $dataperpage;
		
		$q .= " ORDER BY region_id
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
		      'region_id' => $row->region_id,
		      'region_name' => $row->region_name,
		      'pic' => $row->region_id_text,
		      'status' => $row->status
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
	
	function getCmbPIC(){
		$dblokal = $this->load->database("default", TRUE);
		$query = $dblokal->query('select user_id,user_name,istatus from t_mtr_user where istatus=1');
		return $query->result();
	}
}
?>