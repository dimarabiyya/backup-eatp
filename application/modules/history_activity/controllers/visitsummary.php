<?php

class visitsummary extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("reference_model");
		$this->load->model("summary_model");
	}
	/*Function Index*/
	public function index(){

		$this->_check_access();
		if ($this->core->is_ajax())
		{
			if ($this->input->get('cmd') == 'getJson')
			{
				$sch = $this->input->get('sch_id');
				$rows = $this->summary_model->getList($sch);
				echo json_encode($rows);
				exit;
			}
		}

		/*Get All The Region Data*/
		$row_regions = $this->reference_model->regionList();
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
		/*Get Months*/
		$row_months = array();
		for ($i = 1; $i <= 12; $i++)
		{
			$row_months[$i] = date("F", mktime(0,0,0,$i,1,date('Y')));
		}
		/*Get Year*/
		$row_year = array();
		$row_rs_year = $this->db->select('extract(YEAR from min(created_on)) AS op_year_start')->get('t_trx_checklist_result')->row();
		if($row_rs_year->op_year_start == 0){
			$row_year[date('Y')] = date('Y');
		}else{
			for ($y = $row_rs_year->op_year_start; $y <= date('Y'); $y++)
				$row_year[$y] = $y;
		}


		$data["territory"]  = $territory;
		$data['regions'] = json_encode($row_regions);
		$data['months'] = json_encode($row_months);
		$data['years'] = json_encode($row_year);
		$data['content_id'] = 'index';

		$this->load->view('template/smashing', $data);

	}
	public function upload_doc(){
		$id = $this->uri->segment(3);
		$getid = $this->core->decode($id);

	  $sqldata = $this->db->where('visit_id',$getid)->get('t_trx_docs_summary');
		if($sqldata->num_rows() > 0){
			$getdatafile = $sqldata->row();
			$data['files'] ="<a target='_blank' href=".base_url('/file/upload/'.$getdatafile->fdocs)."> View Your File</a>";
		}else{
			$data['files'] ="";
		}
		$data['not_dec_id'] = $id;
		$data['id'] = $getid;
		$data['content_id'] = 'upload_doc';

		$this->load->view('template/smashing', $data);
	}

	public function print_all_excel(){
		/*		header("Content-Type: application/force-download");
					 header("Cache-Control: no-cache, must-revalidate");
					 header("Expires: Sat, 26 Jul 2010 05:00:00 GMT");
					 header("content-disposition: attachment;filename=visitsummary" . date('y-m-d') . ".xls");*/
			 $sqldata = $this->db->query('SELECT sch.visit_id, sch.visit_type, polik.ewo_no, sch.visit_date as visit_plan,
sch.last_visited_date as actual_visit_date, sch.fop_hs_name,sch.priority ,
sch.security_code_checkin, sch.security_code_checkout,sch.is_code_show,
sch.visit_type as SOW,cop.option_label as SOW_DESC, sch.fop_hs_contact,
chopt.option_label as visit_type_label, polink.progress_status,
st_xl_fe.site_name as site_fe_name,st_xl_fe.site_address as address_fe,
st_xl_ne.site_name as site_ne_name,st_xl_ne.site_address as address_ne,
CASE WHEN sch.status = 0 then "Not Confirmed"
WHEN sch.status = 1 then "Confirmed"
WHEN sch.status = 2 then "Declined"
WHEN sch.status = 3 then "Visited"
WHEN sch.status = 4 then "Unschedule"
WHEN sch.status = 5 then "Re-Schedule"
END as desc_status, reg.region_name as region,
link.link_id as link_id,
( SELECT ewo_no from t_trx_po_link poli where poli.po_id = sch.po_id AND poli.link_id_int = sch.link_id ) as ewo_nbwo,
po.po_id, po.po_no, usr.user_name as field_eng, usr.user_id as field_eng_id
From
(((t_trx_schedule sch
LEFT JOIN t_trx_schedule_result_approval cap on cap.visit_id = sch.visit_id
LEFT JOIN t_trx_po_link polik ON polik.po_id = sch.po_id
LEFT JOIN t_mtr_region_xl reg ON (sch.region_id = reg.region_id))
LEFT JOIN t_trx_po_xl po ON (sch.po_id = po.po_id))
LEFT JOIN t_mtr_user usr on (sch.pic_id = usr.user_id))
LEFT JOIN t_mtr_site_link link on (sch.link_id = link.link_id_int)
LEFT JOIN t_mtr_checklist_options chopt on sch.visit_type::integer = chopt.option_id
LEFT JOIN t_mtr_site_xl st_xl_fe on link.site_id_fe = st_xl_fe.site_id
LEFT JOIN t_trx_po_link polink on po.po_id = polink.po_id
LEFT JOIN t_mtr_site_xl st_xl_ne on link.site_id_ne = st_xl_ne.site_id
LEFT JOIN t_mtr_checklist_options cop on sch.visit_type::integer = cop.option_id
WHERE (sch.is_deleted is NULL or sch.is_deleted =0 and po.is_deleted = 0)
AND sch.status = 3 ORDER BY sch.created_on DESC')->result();

			 echo "<table border='1' cellpadding='1' cellspacing='0'>
                <tr style='font-weight:bold;' >
                    <td> PO No </td>
                    <td> EWO No </td>
                    <td> Link ID </td>
                    <td> Site NE Name </td>
                    <td> Address NE </td>
                    <td> Site FE Name </td>
                    <td> Address FE </td>
                    <td> Field Eng </td>
                    <td> FOP HS Name </td>
										<td> Visit Type </td>
                    <td> Region </td>
										<td> Visit Plan </td>
										<td> Actual Visit Date </td>
										<td> Desc Status </td>
										<td> Aproval Status Order </td>
                </tr>";
								foreach($sqldata as $rows){
								echo "<tr>
                    <td> ".$rows->po_no." </td>
                    <td> EWO No </td>
                    <td> Link ID </td>
                    <td> Site NE Name </td>
                    <td> Address NE </td>
                    <td> Site FE Name </td>
                    <td> Address FE </td>
                    <td> Field Eng </td>
                    <td> FOP HS Name </td>
										<td> Visit Type </td>
                    <td> Region </td>
										<td> Visit Plan </td>
										<td> Actual Visit Date </td>
										<td> Desc Status </td>
										<td> Aproval Status Order </td>
                </tr>";
							}

								echo "</table>";


	}

	public function pro_upload_doc(){

		$datapos = array('not_dec_id'=>$this->input->post('not_dec_id'),
										'idvisit'=>$this->input->post('idvisit'),
						 				'fdocs'=>str_replace(" ","_", $this->input->post('fdocs')));

        $config['upload_path']	= "file/upload/";
        $config['allowed_types'] = 'docx|doc|xls|xlsx|pdf|gif|bmp|jpg|jpeg|png';
        $config['max_size']  = 1000;
        $config['remove_spaces'] = TRUE;

				$this->load->library('upload');
				$this->upload->initialize($config);

				if($datapos['fdocs'] != ''){
					 $this->upload->do_upload('docs');
				}
				//cek data
				$ceks = $this->db->query("select * from t_trx_docs_summary where visit_id = '$datapos[idvisit]' ");
				$gceks = $ceks->num_rows();
				if($gceks > 0){
					//update
					    $store = $this->db->query("update t_trx_docs_summary set fdocs = '$datapos[fdocs]' where visit_id = '$datapos[idvisit]' ");
				}else{
					//insert
					    $store = $this->db->query("insert into t_trx_docs_summary (visit_id,fdocs) VALUES ('$datapos[idvisit]','$datapos[fdocs]')");
				}
				/*echo $gceks;
				exit();*/

				if($store){
	    	  redirect (base_url('visitsummary/upload_doc/'.$datapos[not_dec_id]));
	    	}else{
	    		redirect (base_url('visitsummary/upload_doc/'.$datapos[not_dec_id]));
	    	}





	}
	/*Public function show detail */
	public function detail($id){
		$this->_check_access();
		$schedule_id = $this->core->decode($id);

		$user_id = $this->session->userdata("user_id");
		 $flag = $this->summary_model->is_eligible_approval($schedule_id,$user_id);
		 // if($flag) echo "True";
		 // else echo "False";
		// die;

		$data['content_id'] = 'detail';
		$data['title'] = 'Summary Visit ';
		$data['siteinfo'] = $this->summary_model->getSchedule($schedule_id);/*0 as Default Schedule*/
		$data['user_id'] = $user_id;
		$data['title'] = 'Summary Visit Site Link '.$data['siteinfo']->site_link;
		$data['action_url'] = base_url("/atpdesktop/saveAnswer");
		/*List Users for Approval*/
		$approval_user = $this->summary_model->getApprovalOrder($schedule_id);

		$is_enable_approval ="disabled";
		$flag = $this->summary_model->is_eligible_approval($schedule_id,$user_id);
		if($flag){
			$flag = $this->summary_model->is_user_before_approval_done($schedule_id,$user_id);
			if($flag){
				$is_enable_approval = "";
				//echo "finished";
			}else{
				//echo "not yet";
			}
		}
		// die;
		/*List of Checklist*/
		$visitinfo  = $this->summary_model->getDetailSummary($schedule_id);

	    $properties = $this->summary_model->siteProperties();
	    /*Get Data Approval Summary*/
	    $user_id = $this->session->userdata('user_id');
	    $approval_summary = $this->summary_model->getApprovalSummary($user_id,$schedule_id);
	   	$data["approval_summary"] = $approval_summary;
	   	/*Get Data Pending Item with this visit_id*/
	   	$pending_items = $this->summary_model->getPendingItem($schedule_id);
	   	$data["pending_items"] = $pending_items;
	   	/*Render data Approval List to View*/
	   	$data["approval_users"] = $approval_user;
	   	$data["is_enable_approval"] = $is_enable_approval;
	    $x = 0;
	    $data['checklist_item'] = array();
	    $answers_by_checklist = array();
	    foreach ($visitinfo['answer'] as $answer) {
	        $answers_by_checklist[$answer['checklist_id']][] = $answer;
	    }
	    foreach ($visitinfo['checklists'] as $checklistitem) {
	      $data['checklist_item'][$x] = $checklistitem;
	      $data['checklist_item'][$x]['answer'] = array();
	      if (isset($answers_by_checklist[$checklistitem['checklist_id']])) {
	          $data['checklist_item'][$x]['answer'] = $answers_by_checklist[$checklistitem['checklist_id']];
	      }
	      if ( ! empty($visitinfo['answer'])) {
	          $data['checklist_item'][$x]['view'] = $this->generate_view($checklistitem, $data['checklist_item'][$x]['answer']);
	      }
	      $x++;
	    }

	   	//die;
	    $siteProperties = array();
	    $x   = 0;
	    $items_by_group = array();
	    foreach ($data['checklist_item'] as $ii) {
	        $items_by_group[$ii['checklist_group']][] = $ii;
	    }
	    foreach ($properties['group'] as $gg) {
	      $siteProperties[$x] = $gg;
	      if (isset($items_by_group[$gg['group_id']])) {
	          $siteProperties[$x]['checklists'] = $items_by_group[$gg['group_id']];
	      }
	      $x++;
	    }

	    $new = array();
	    foreach ($siteProperties as $a){
	      $new[$a['parent_id']][] = $a;
	    }
	    $data['checklistsgrouping'] = $this->treeGenerator($new, $new[598]);
		/*End List of Checklist*/

		$this->load->view('template/smashing', $data);
	}

	public function getcheck(){
		$data1 = $this->input->get('data1');
		$data2 = $this->input->get('data2');
		echo "your data is : " .$data1. " " .$data2;
	}

	/*Public function show detailreject */
	public function detailreject($id){
		$this->_check_access();
		$schedule_id = $this->core->decode($id);

		$user_id = $this->session->userdata("user_id");
		 $flag = $this->summary_model->is_eligible_approval($schedule_id,$user_id);
		 // if($flag) echo "True";
		 // else echo "False";
		// die;

		$data['content_id'] = 'detail';
		$data['title'] = 'Summary Visit ';
		$data['siteinfo'] = $this->summary_model->getSchedule($schedule_id);/*0 as Default Schedule*/

		$data['title'] = 'Summary Visit Site Link '.$data['siteinfo']->site_link;
		$data['action_url'] = base_url("/atpdesktop/saveAnswer");
		/*List Users for Approval*/
		$approval_user = $this->summary_model->getApprovalOrder($schedule_id);

		$is_enable_approval ="disabled";
		$flag = $this->summary_model->is_eligible_approval($schedule_id,$user_id);
		if($flag){
			$flag = $this->summary_model->is_user_before_approval_done($schedule_id,$user_id);
			if($flag){
				$is_enable_approval = "";
				//echo "finished";
			}else{
				//echo "not yet";
			}
		}
		// die;
		/*List of Checklist*/
		$visitinfo  = $this->summary_model->getDetailSummary($schedule_id);

	    $properties = $this->summary_model->siteProperties();
	    /*Get Data Approval Summary*/
	    $user_id = $this->session->userdata('user_id');
	    $approval_summary = $this->summary_model->getApprovalSummary($user_id,$schedule_id);
	   	$data["approval_summary"] = $approval_summary;
	   	/*Get Data Pending Item with this visit_id*/
	   	$pending_items = $this->summary_model->getPendingItem($schedule_id);
	   	$data["pending_items"] = $pending_items;
	   	/*Render data Approval List to View*/
	   	$data["approval_users"] = $approval_user;
	   	$data["is_enable_approval"] = $is_enable_approval;
	    $x = 0;
	    $data['checklist_item'] = array();
	    $answers_by_checklist = array();
	    foreach ($visitinfo['answer'] as $answer) {
	        $answers_by_checklist[$answer['checklist_id']][] = $answer;
	    }
	    foreach ($visitinfo['checklists'] as $checklistitem) {
	      $data['checklist_item'][$x] = $checklistitem;
	      $data['checklist_item'][$x]['answer'] = array();
	      if (isset($answers_by_checklist[$checklistitem['checklist_id']])) {
	          $data['checklist_item'][$x]['answer'] = $answers_by_checklist[$checklistitem['checklist_id']];
	      }
	      if ( ! empty($visitinfo['answer'])) {
	          $data['checklist_item'][$x]['view'] = $this->generate_view($checklistitem, $data['checklist_item'][$x]['answer']);
	      }
	      $x++;
	    }

	   	//die;
	    $siteProperties = array();
	    $x   = 0;
	    $items_by_group = array();
	    foreach ($data['checklist_item'] as $ii) {
	        $items_by_group[$ii['checklist_group']][] = $ii;
	    }
	    foreach ($properties['group'] as $gg) {
	      $siteProperties[$x] = $gg;
	      if (isset($items_by_group[$gg['group_id']])) {
	          $siteProperties[$x]['checklists'] = $items_by_group[$gg['group_id']];
	      }
	      $x++;
	    }

	    $new = array();
	    foreach ($siteProperties as $a){
	      $new[$a['parent_id']][] = $a;
	    }
	    $data['checklistsgrouping'] = $this->treeGenerator($new, $new[598]);
		/*End List of Checklist*/

		$this->load->view('template/smashing', $data);
	}

	/*Function that get Approval massed Data*/
	public function savemassedapproval(){
		$approval_checklist = $this->input->post("approval_list");
		$rejected_checklist = $this->input->post("rejected_list");
		$remark = $this->input->post("remark");
		$visit_id = $this->input->post("visit_id");
		// $all_data = $this->input->post();
		// echo "<pre/>";
		// print_r($all_data);die;
		// print_r($approval_checklist);
		// echo "=================";
		// print_r($rejected_checklist);
		// echo "=================";
		// if(!$rejected_checklist){
		// 	echo "no data";
		// }
		// echo $remark;
		// die;
		$total_approval = 0;
		$total_rejected = 0;
		if($approval_checklist){
			/*Save Approval Checklist Item*/
			foreach($approval_checklist as $item){
				$data = new stdClass;
				$data->visit_id = $visit_id;
				$data->checklist_id = $item;
				$data->status = 1;/*Approve Answer Checklist*/
				$data->created_on = date('Y-m-d H:i:s');
				$data->created_by = $this->session->userdata('user_id');
				$this->summary_model->saveApprovalAnswer($data,true);
				$total_approval ++;
			}
		}

		if($rejected_checklist){
			/*Save Rejected Checklist Item*/
			foreach($rejected_checklist as $item){
				$data = new stdClass;
				$data->visit_id = $visit_id;
				$data->checklist_id = $item;
				$data->status = 0;/*Reject Answer Checklist*/
				$data->created_on = date('Y-m-d H:i:s');
				$data->created_by = $this->session->userdata('user_id');
				$this->summary_model->saveApprovalAnswer($data,true);
				$total_rejected++;
			}
		}

		$pending_items = $this->input->post("pending_items");
		$pending_completion_date = $this->input->post("pending_completiondate");
		$pending_pending_pic = $this->input->post("pending_pic");
		$pending_contact = $this->input->post("pending_contact");
		$pending_ids = $this->input->post("pending_ids");

		if(count($pending_items) == count($pending_completion_date) &&
		    count($pending_pending_pic) == count($pending_contact) &&
		    count($pending_items) == count($pending_contact) && count($pending_items)>0 && $pending_items[0]!="" ){
			/*save to t_trx_schedule_pending_item if exist*/
			for($i=0;$i<count($pending_items);$i++){
				$dt_pending = new stdClass;
				$dt_pending->item_no = 1;
				$dt_pending->item_descr = $pending_items[$i];
				$dt_pending->target_completion = $pending_completion_date[$i];
				$dt_pending->pic = $pending_pending_pic[$i];
				$dt_pending->contact_no = $pending_contact[$i];
				$dt_pending->visit_id = $visit_id;

				if($pending_ids[$i] == "none" ){
					$dt_pending->created_on = date('Y-m-d H:i:s');
					$dt_pending->created_by = $this->session->userdata('user_id');
					$this->summary_model->savePendingItem($dt_pending);

				}else{
					$dt_pending->updated_on = date('Y-m-d H:i:s');
					$dt_pending->updated_by = $this->session->userdata('user_id');
					$pending_id = $this->core->decode($pending_ids[$i]);
					$this->summary_model->updatePendingItem($dt_pending,$pending_id);
				}

			}

		}
		// else{
		// 	$json = array('status' => 'error', 'message' => 'Missing Form in pending item');
		// 	echo json_encode($json);
		// 	exit;
		// }
		$accpt_status = $this->input->post("acceptance");
		$final_status_approval = $this->summary_model->is_approved_all($visit_id,$this->session->userdata('user_id'));

		//echo $final_status_approval."-".$visit_id;die;
		/*update t_trx_schedule_result_approval*/
		$dt_res_approval = new stdClass;
		$dt_res_approval->status = $final_status_approval;
		$dt_res_approval->qty_approved = $total_approval;
		$dt_res_approval->qty_rejected = $total_rejected;
		$dt_res_approval->updated_on = date('Y-m-d H:i:s');
		$dt_res_approval->updated_by = $this->session->userdata('user_id');
		$this->summary_model->updateResultApproval($dt_res_approval,$visit_id,$this->session->userdata('user_id'));
		/*save  acceptance status t_trx_schedule_result_approval_sum*/
		$dt_sum_approval = new stdClass;
		$dt_sum_approval->visit_id = $visit_id;
		$dt_sum_approval->acceptance_status = $accpt_status;
		$dt_sum_approval->remark = $remark;
		$dt_sum_approval->user_id = $this->session->userdata('user_id');
		$dt_sum_approval->created_on = date('Y-m-d H:i:s');
		$dt_sum_approval->created_by = $this->session->userdata('user_id');
		$this->summary_model->saveApprovalSummary($dt_sum_approval);

		/*Check if Current user is the Last User to Approve and then set progress po_link with 5 closed*/
		$flag_last_approve = $this->summary_model->is_last_approval($this->session->userdata('user_id'),$visit_id);
		if($flag_last_approve==1 && $final_status_approval ==1){ /*This PO LInk should be Closed*/

			$rs_sch = $this->reference_model->getSchedule($visit_id);
			if($rs_sch){
				$data = new stdClass;
				$data->progress_status = 5;/*Closed*/
				$this->summary_model->updatePOLIProgressStatus($rs_sch->po_id,$rs_sch->link_id,$data);
			}
		}
		/*Return Result*/
		$json = array('status' => 'success', 'message' => 'Data Approval is successfully inserted');
		echo json_encode($json);
		exit;

	}
	/*Function return Image of Checklist*/
	public function getimage(){
		$visit_id = $this->input->post("visit_id");
		$chk_id = $this->input->post("checklist_id");

		$res = $this->summary_model->getImageChecklist($visit_id,$chk_id);
		if($res){
			$json = array('status' => 'success', 'message' => 'Image is exist',"src"=>$res->photo_name);
			echo json_encode($json);
			exit;
		}else{
			$json = array('status' => 'error', 'message' => 'Image is not exist');
			echo json_encode($json);
			exit;
		}
	}
	function treeGenerator(&$list, $parent){
	    $tree = array();
	    foreach ($parent as $k=>$l) {
	      if(isset($list[$l['group_id']])){
	        $l['sub_group'] = $this->treeGenerator($list, $list[$l['group_id']]);
	      }
	      $tree[] = $l;
	    }
	    return $tree;
	}

	private function generate_view($data, $answer)
  	{
  		return "<a href='#'>".$data["checklist_id"]."</a>";
  	}

	private function _check_access()
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

	public function view($view)
	{
		$this->load->view($view);
	}

	public function test(){
		$rs = $this->summary_model->getPendingItem(8);
		echo json_encode($rs);
	}
	public function deletepending($id){
		$pending_id = $this->core->decode($id);
		$data = new stdClass;
		$data->updated_on = date('Y-m-d H:i:s');
		$data->updated_by = $this->session->userdata('user_id');
		$data->is_deleted  = 1;
		$this->summary_model->delete($pending_id,$data);
		$json = array('status' => 'success', 'message' => 'Pending items is removed');
		echo json_encode($json);
	}

	public function test_distance(){

		$lat1 = -6.290221;
		$long1 = 106.843988;
		$lat2 = -6.3595241;
		$long2 = 106.7341521;

		echo $this->getDistanceFromLatLonInMeter($lat1,$long1,$lat2,$long2);
	}
	function getDistanceFromLatLonInMeter($lat1,$lon1,$lat2,$lon2){
		$R = 6371 ; /*Radius Earth in Km*/
		$dLat = deg2rad($lat2-$lat1);  // deg2rad below
		$dLon = deg2rad($lon2-$lon1);
		$a =
		sin($dLat/2) * sin($dLat/2) +
		cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
		sin($dLon/2) * sin($dLon/2)
		;
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));
		$d = $R * $c; // Distance in km
		$res = $d * 1000;/*convert to m*/
		$distance = number_format((float)$res, 2, ',', '.')." m";
		return $distance;
  }
}
