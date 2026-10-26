<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class pm_desktop extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpm_desktop');
	}
	
	########################################################################
	//Manag.ins_desktop
	public function index(){
		$data['judul'] = "Prev. Maintenance Checklist Desktop";
		$data['content_id'] = "pm_desktop_view";
		$userTerritory      = $this->mglobal->userTerritory($this->session->userdata('user_id'));
		$territory = array();
		$x   = 0;
		foreach ($userTerritory['region'] as $rr) {
		  $territory[$x] = $rr;
		  foreach ($userTerritory['cluster'] as $cc) {
			if ($cc['region_id'] == $rr['region_id'])
			  {
				$territory[$x]['cluster'][] = $cc;
			  }
		  }
		  $x++;
		}
		$data["territory"]  = $territory;
		$this->theme->view("pm_desktop_view", $data);
		
	}
	//add and edit data
	public function addeditdata(){
		$data['ids'] = $this->uri->segment(4);
		if(!$data['ids']){
			redirect('preventive/pm_desktop');
		}
		$data['judul'] = "Prev. Maintenance Checklist Desktop";
		$data['hasil'] = '';
		$data['error'] = '';
		$data['site'] = $this->mpm_desktop->getSite($data['ids']);
		$this->theme->view("r-pm_desktop", $data);
	}
	public function addeditdatas(){	
	$data['id'] = $this->uri->segment(4);		
	$dt = $this->input->post();
    $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
    $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
    $dt['offset'] = ($dt['page']-1)*$dt['rows'];
	$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
    $data['q'] = strtolower($q);
	
    $d=$this->input->get();
    $q=$this->mpm_desktop->addeditdata($data['id'],$data);
		$result["total"] = $q->num_rows();    
		$items = array();
			foreach ($q->result() as $row) {
			
				$row->textbox='<input type=text name=result>';
				$row->upload='<input type="file" />';
				$row->checklist='<div><input type="checkbox" checked="checked" /></div>';
	            array_push($items, $row);
			}    
			$result["rows"] = $items;
	      echo json_encode($result);
	}
	public function loadData(){
		$this->mpm_desktop->loadData();
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('cid','vid','sid','answer','userfile','rid','file');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($answer == ""){
			$hasil = "Error: \n";
			if($answer == "" ){
				$hasil .= "Please Insert Answer \n";
			}
		}else{
			foreach($_FILES as $field => $file){
				$userid = $this->session->userdata('user_id');
				$timestamp = date("YmdHis");
				
				$image_upload_folder = FCPATH . $this->upload_path();
				$year = date('Y'); $month = date('m'); $date = date('d');
				$dir 						= '/file/upload/preventive_maintenance/'.$year.'/'.$month.'/'.$date.'/';
				$file_namesUpload 			= $vid."_".$cid."_".$sid."_".$userid."_".$timestamp.$field;
				$config['file_name'] 		= $file_namesUpload;
				$config['upload_path'] 		= $image_upload_folder;
				$config['allowed_types'] 	= 'gif|jpg|png|jpeg';
				$config['max_size']    		= '144400';
				$config['max_width']   		= '1524';
				$config['max_height']  		= '1024';
				$this->load->library('upload', $config);
				
				if ($file['error'] == 0) {
					if (!$this->upload->do_upload($field)) {				
						$status = "ERROR";
						$hasil = $this->upload->display_errors();
					} else {					
						$file_info = $this->upload->data();
						$photo_name = $dir.$file_info['file_name'];
						$this->mpm_desktop->saveDatas($photo_name);
					}
				}
			}			
			$this->mpm_desktop->saveData();
		}
		$id = $this->input->post('vid');
		$data['judul'] = "Prev. Maintenance Checklist Desktop";
		/* echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		)); *//* 
		return; */
		redirect('preventive/pm_desktop/addeditdata/'.$id, $data);
	}
	
	function upload_path() {
		$year = date('Y'); $month = date('m'); $date = date('d');
		if (!is_dir('file/upload/preventive_maintenance/'.$year.'/'.$month.'/'.$date)) {
			mkdir('./file/upload/preventive_maintenance/' .$year.'/'.$month.'/'.$date, 0777, TRUE);
			return '/file/upload/preventive_maintenance/'.$year.'/'.$month.'/'.$date.'/';
		}
		else {
			return '/file/upload/preventive_maintenance/'.$year.'/'.$month.'/'.$date.'/';
		}
	}
	
	function ListCkDesk(){
		$dt = $this->input->post();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
		$data['q'] = strtolower($q);
		$opr_year    = $this->input->get_post('opr_year');
		$opr_month   = $this->input->get_post('opr_month');
		$opr_cluster = $this->input->get_post('opr_cluster');
		
		if(!$opr_year)
		{ $opr_year = date('Y'); }
		if(!$opr_cluster)
		{ $opr_cluster = $this->session->userdata('cluster_ops_id'); }

    
	$qr=$this->mpm_desktop->getListVisit($data,$q,$opr_cluster, $opr_year, $opr_month);
		$result["total"] = $qr->num_rows();
	$q=$this->mpm_desktop->getListVisits($offset,$rows,$q,$opr_cluster, $opr_year, $opr_month);	
	
		$items = array();
			foreach ($q->result() as $row) {
				if($row->status == 1){
					$row->status = "Confirmed - Unvisited";
				}elseif($row->status == 3){
					$row->status = "Visited Schedule";
				}
				$row->action='<a href="'.base_url().'preventive/pm_desktop/addeditdata/'.$row->visit_id.'" title="Edit" class="btn-action glyphicons pencil btn-success" ><i></i></a>';
	            array_push($items, $row);
			}    
			$result["rows"] = $items;//$result['a'] = $qr->num_rows();$result['b'] = $q->num_rows();
	      echo json_encode($result);
    }	
	function ListVisitDetail(){
		$dt = $this->input->post();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'visit_id';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

		$offset = ($page-1)*$rows;
		$d=$this->input->get();
	
		$qs=$this->mpm_desktop->getListDetails($this->input->get('visit_id'));
		$result["total"] = $qs->num_rows();    
		$q=$this->mpm_desktop->getListDetails($this->input->get('visit_id'),$offset,$rows);
	// $filenotfound = base_url().'file/upload/not-found.png';	
	// $dataempty = base_url().'file/upload/dataempty.jpg';	
		$items = array();
		$r->answer = array();
			foreach ($q->result() as $r) {
				$r->answer[] = $r->pm_result;
				if($r->interface == 'desktop'){
					$r->interface = '<img src="'.base_url().'assets/images/interface-'.$r->interface.'.png" style="margin: -4px 0 0 4px"> Desktop';
				}
				
	            array_push($items, $r);
			}    
			$result["rows"] = $items;
	      echo json_encode($result);
		
    }
	
	function getListVisitDetail(){
	$json = array();
				$vid = $this->input->get('visit_id');
				$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
				$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
				$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'visit_id';
				$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

				$offset = ($page-1)*$rows;
				
				$sql = "SELECT aa.visit_id,cc.checklist_id,cc.checklist_name,cc.interface,cc.has_photo,cc.total_photo
						from t_trx_pm_schedule aa
						left join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
						left join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
						where aa.visit_id ='$vid' AND cc.interface='desktop' AND cc.status='1' 
						GROUP BY aa.visit_id, cc.checklist_id
						ORDER BY {$sort} {$order}
						";
				
				$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
				
				$total_rows = (int) $query->row()->total;
				
				$sql .= " LIMIT {$rows} OFFSET {$offset}";
				
				$query = $this->db->query($sql);
				
				$json['total'] = $total_rows;
				$json['rows'] = array();
				foreach ($query->result_array() as $r) {
					$sqls = $this->db
						->query("
							SELECT dd.pm_result
							from t_trx_pm_schedule aa
							left join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
							left join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
							left join t_trx_pm_checklist_result dd on cc.checklist_id = dd.checklist_id and dd.visit_id = aa.visit_id
							where cc.checklist_id = '".$r['checklist_id']."' AND aa.visit_id = '".$r['visit_id']."'
						")
						->result_array();
					$sqlr = $this->db
						->query("
							SELECT ee.photo_name
							from t_trx_pm_schedule aa
							left join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
							left join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
							left join t_trx_pm_checklist_result_photo ee on cc.checklist_id = ee.checklist_id and ee.visit_id = aa.visit_id
							where cc.checklist_id = '".$r['checklist_id']."' AND aa.visit_id = '".$r['visit_id']."'
						")
						->result_array();
					$r['answer'] = array();
					$r['photo'] = array();
					foreach ($sqls as $q) {
						$r['answer'][] = $q['pm_result'];
						if($r['interface'] == 'desktop'){
							$r['interface'] = '<img src="'.base_url().'assets/images/interface-'.$r['interface'].'.png" style="margin: -4px 0 0 4px"> Desktop';
						}
					}
					$i=1;
					foreach ($sqlr as $s) {
						if($r['has_photo'] == 0){
							$r['photo'][] = "Photo Not Available";
						}else{
							if(count($s['photo_name']) != 0){
								$r['photo'][] = '<a href="'.base_url().$s['photo_name'].'" class="real-image" target="_blank">Photo['.$i.']</a>';
								$i++;
							}else{
								$r['photo'][] = "Photo Not Available";
							}
						}
					}
					$r['visit_id'] = $this->core->encode($r['visit_id']);
					$json['rows'][] = $r;
				}
				
				$json['sql'] = $sql;
				
				echo json_encode($json);
				exit;
	}
	
	public function getVisitImage(){
		$this->load->helper('file');
		// $config['new_image'] = base_url().'file/upload/thumb';

		$param = $this->input->post();
		$r = $this->mpm_desktop->getVisitImage('t_trx_pm_checklist_result_photo', $param);
		$img = '';
		foreach ($r as $k) {
		  $img_probe = $this->get_thumbnail($k->photo_name);
		  $x = file_exists($img_probe['thumbnail']);
		  if(!$x) {
			$this->generate_thumb($img_probe);
		  }

		  $popUpImage = base_url().$k->photo_name;
		  $thumbImage = base_url().$img_probe['thumbnail'];
		  $img .= '<a src="'.$popUpImage.'" class="real-image">
					<img src="'.$thumbImage.'" style="background: #efefef; border: solid 1px #ddd; padding: 5px; border-radius: 4px; margin-right: 8px" class="img-polaroid"/></a> ';
		}
		echo $img;
	}

	function get_thumbnail($image_source = null) {
		$i = explode('/', $image_source);
		$file_name  = $i[count($i)-1];
		unset($i[count($i)-1]);
		$file_path  = implode('/', $i);
		$thumb_path = implode('/', $i).'/thumb';
		$thumbnail  = implode('/', $i).'/thumb/'.$file_name;

		if (!is_dir('.'.$thumb_path)) {
		  mkdir('.'.$thumb_path, 0777, TRUE); 
		}
		return array('file_name'  => $file_name,
					 'file_path'  => substr($file_path, 1),
					 'thumb_path' => substr($thumb_path, 1),
					 'thumbnail'  => substr($thumbnail, 1)
					 );
	}

	function generate_thumb($image_source) {
		$this->load->library('image_lib'); 
		$config['image_library'] = 'gd2';
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width']  = 128;
		$config['height'] = 128;
		$config['new_image'] = './'.$image_source['thumbnail'];
		$config['source_image'] = base_url().$image_source['file_path'].'/'.$image_source['file_name'];
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		$this->image_lib->clear();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */