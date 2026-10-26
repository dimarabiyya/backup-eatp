<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class region extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->mglobal->is_logged_in();
		
		$this->load->model('mregion');
	}
	
	########################################################################
	//Manag.region
	public function index()
	{
		$data['judul'] = "Region";
		$data['content_id'] = "region_view";
		$this->theme->view("region_view", $data);
	}
	
	public function deleteData()
	{
		$this->mregion->deleteData();
	}
	
	public function loadData()
	{
		$this->mregion->loadData();
	}
	
	public function listVisit()
	{
		$dt = $this->input->post();
		$dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
		$dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
		$dt['offset'] = ($dt['page']-1)*$dt['rows'];
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
		$data['q'] = strtolower($q);
		
		$d=$this->input->get();
		$qs=$this->mregion->getListVisits($data);
			$result["total"] = $qs->num_rows();  

		$q=$this->mregion->getListVisit($dt['offset'],$dt['rows'],$data);		
		
		$items = array();
		foreach ($q->result() as $row)
		{
			
			$row->action='
				<a onclick="Document.editData('.$row->region_id.')" title="Edit" class="btn-action glyphicons pencil btn-info" >
				<i></i></a><a onclick="Document.deleteData('.$row->region_id.')" title="Delete" class="btn-action glyphicons remove_2 btn-danger" ><i></i></a>
			';
			
			array_push($items, $row);
		}    
		
		$result["rows"] = $items;
		
		echo json_encode($result);
    }
	
	public function addeditdata()
	{
		$input = array('region_id','region_name');
		
		foreach($input as $val)
		{
			$data[$val] = "";
		}
		
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Region";
		
		if ($data['id'] != "")
		{
			$data['judul'] = "Edit Region";
			$getDetail = $this->mregion->getregionDetail( $data['id'] );
			
			foreach($getDetail as $r)
			{
				foreach($input as $val)
				{
					$data[$val] = $r->$val;
				}
			}
		}
		
		echo $this->load->view("r-region", $data);
	}
	
	public function saveData()
	{
		//lakukan validasi di sini
		$input = array('id','region_name');
		
		foreach ($input as $val)
		{
			$$val = $this->input->post($val);
		}
		
		$status = "ERROR";
		$hasil = "";
		
		if ($region_name == "")
		{
			$hasil = "Found Error: \n";
			
			if( $cluster_code == "" ){
				$hasil .= "Please Input Cluster Code \n";
			}
			
			if ( $cluster_name == "" )
			{
				$hasil .= "Please Input Cluster Name \n";
			}
			
		}
		elseif ($region_name != '' && $this->core->view_row('t_mtr_region_xl', array('UPPER(region_name)' => strtoupper($region_name), 'is_deleted' => 0, 'region_id !='=> (int) $id)))
		{
			$hasil .= "Region Name has already in used \n";
				
		}
		else
		{
			//simpan data ke sini
			$status = "OK";
			$this->mregion->saveData();
		}
		
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		
		return;
	}
	
	########################################################################
	//Manag.GroupRole
	public function GroupRole( $group_id="" )
	{
		$data['judul'] = "Group Role";
		$data['content_id'] = "region/f-group-role";
		
		$data['group'] = $this->mregion->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole()
	{
		$this->mregion->saveGroupRole();
	}
	########################################################################
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */