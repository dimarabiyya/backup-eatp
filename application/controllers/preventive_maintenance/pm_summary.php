<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pm_summary extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
    
		$this->load->model('mglobal');
		$this->load->model('schedule/mpm', 'pm');
	}

	public function index()
	{
		$this->_check_valid_loggedin();
		
		$data['title'] = "Visit Summary"; 
		$data['content_id']  = "preventive_maintenance/f_pm_schedule_summary";

		$userTerritory      = $this->mglobal->userTerritory($this->session->userdata('user_id'));
		
		$territory = array();
		$x   = 0;
		foreach ($userTerritory['region'] as $rr)
		{
			$territory[$x] = $rr;
			foreach ($userTerritory['cluster'] as $cc)
			{
				if ($cc['region_id'] == $rr['region_id'])
				{
					$territory[$x]['cluster'][] = $cc;
				}
			}
			
			$x++;
		}
		
		$data["territory"]  = $territory;
		$data["region"]     = $this->session->userdata('user_region');
		
		$this->load->view("template/smashing", $data);
	}

	// yang ini untuk ditampilkan dalam format datatable
	public function getSiteSummary()
	{
		$page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
		$rows = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 10;

		$sch_cond    = $this->input->get_post('sch_cond');
		$opr_year    = $this->input->get_post('opr_year');
		$opr_month   = $this->input->get_post('opr_month');
		$opr_cluster = $this->input->get_post('opr_cluster');
		$opr_region  = $this->input->get_post('opr_region');
		$territorytype = $this->input->get_post('territorytype');
		$keyword     = $this->input->get_post('keyword');

		// if($sch_cond == 1) 
		  // { $sch_cond = 3; }
		// else
		//   { $status = 0; }

		if ( ! $opr_year)
		{
			$opr_year = date('Y');
		}
		
		if ( ! $opr_month)
		{
			$opr_month = date('m');
		}

		// $data = $this->pm->getSiteVisitedSummary($opr_year,$opr_month,$opr_cluster,$status,$page,$rows);
		// $data = $this->pm->getSiteUnvisitedSummary($opr_year,$opr_month,$opr_cluster,$status,$page,$rows);
		$data = $this->pm->getSiteSummary($opr_year,$opr_month,$opr_cluster,$sch_cond,$keyword,$page,$rows, $opr_region, $territorytype);
		
		echo json_encode($data);
	}

	public function canceledList()
	{
		$page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
		$rows = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 10;

		$opr_year    = $this->input->get_post('opr_year');
		$opr_month   = $this->input->get_post('opr_month');
		$opr_cluster = $this->input->get_post('opr_cluster');
		$search      = $this->input->get_post('otherParam');

		if ( ! $opr_year)
		{
			$opr_year = date('Y');
		}
		
		if ( ! $opr_cluster)
		{
			$opr_cluster = $this->session->userdata('cluster_ops_id');
		}

		$data = $this->pm->canceledList($opr_year, $opr_month, $opr_cluster, $search, $page, $rows);
		
		echo json_encode($data);
	}


	public function detailSummary($visit_id)
	{
		$rawChecklist = $this->pm->getChecklist($visit_id, 598, 'prev.maintenance');

		$checklistForm = array();
		$x = 0;
		foreach ($rawChecklist['group'] as $gg)
		{
			$checklistForm[$x] = $gg;
			$checklistForm[$x]['item_checklist'] = array();
			foreach ($rawChecklist['checklist'] as $cc)
			{
				if ($gg['checklist_group_id'] == $cc['checklist_group'])
				{
					$checklistForm[$x]['item_checklist'][] = $cc;
				}
			}
			
			$x++;
		}

		$new = array();
		foreach ($checklistForm as $a){
		  $new[$a['parent_id']][] = $a;
		}

		$checklistsGrouping = $this->treeGenerator($new, $new[598]);
		$data['visit_id'] = $visit_id ;
		$data['checklist'] = $checklistsGrouping;
		$data['sites']     = $rawChecklist['site'];
		// $data['rawChecklist'] = $checklistsGrouping;
		$this->load->view('preventive_maintenance/pop_up_pm_summary', $data);
	}

	public function treeGenerator(&$list, $parent)
	{
		$tree = array();
		foreach ($parent as $k=>$l)
		{
			if (isset($list[$l['checklist_group_id']]))
			{
				$l['sub_group'] = $this->treeGenerator($list, $list[$l['checklist_group_id']]);
			}
		  
			$tree[] = $l;
		}
		
		return $tree;
	}

	public function getVisitImage()
	{
		$this->load->model('schedule/mschedule', 'mschedule');
		$this->load->helper('file');
		// $config['new_image'] = base_url().'file/upload/thumb';

		$param = $this->input->post();
		$r = $this->mschedule->getVisitImage('t_trx_pm_checklist_result_photo', $param);
		
		$img = '';
		foreach ($r as $k)
		{
			$img_probe = $this->get_thumbnail($k->photo_name);
			$x = file_exists($img_probe['thumbnail']);

			if( ! $x)
			{
				$this->generate_thumb($img_probe);
			}

			$popUpImage = base_url().$k->photo_name;
			$thumbImage = base_url().$img_probe['thumbnail'];
			$img .= '<a target="_blank" onclick="popUpVisitedImage(\''.$popUpImage.'\', \''.$k->photo_remark.'\')">
				<img src="'.$thumbImage.'" style="background: #efefef; border: solid 1px #ddd; padding: 5px; border-radius: 4px; margin-right: 8px" />
				<br>'.$k->photo_remark.'</a>
			';
		}
		
		echo $img;
	}

	public function get_thumbnail($image_source = null)
	{
		$i = explode('/', $image_source);
		$file_name  = $i[count($i)-1];
		
		unset($i[count($i)-1]);
		
		$file_path  = implode('/', $i);
		$thumb_path = implode('/', $i).'/thumb';
		$thumbnail  = implode('/', $i).'/thumb/'.$file_name;

		if ( ! is_dir('.'.$thumb_path))
		{
		  mkdir('.'.$thumb_path, 0777, TRUE); 
		}
		
		return array(
			'file_name'  => $file_name,
			'file_path'  => substr($file_path, 1),
			'thumb_path' => substr($thumb_path, 1),
			'thumbnail'  => substr($thumbnail, 1)
		);
	}

	public function generate_thumb($image_source)
	{
		$this->load->library('image_lib'); 

		$config['image_library'] = 'gd2';
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width']  = 128;
		$config['height'] = 128;
		$config['new_image'] = $image_source['thumbnail'];
		$config['source_image'] = realpath($image_source['file_path'].'/'.$image_source['file_name']);
		
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		$this->image_lib->clear();
	}
  
	private function _check_valid_loggedin()
	{
		if ( ! $this->session->userdata('is_login'))
		{
			if ($this->core->is_ajax())
			{
				echo json_encode(array(
					'loggedin' => FALSE,
					'message' => 'You are not logged in. You will be redirected to login page.',
					'redirect_url' => site_url('login')
				));
				exit;
			}
			else
			{
				redirect('login');
			}
		}
	}

}

/* End of file pm_summary.php */
/* Location: ./application/controllers/preventive_maintenance/pm_summary.php */