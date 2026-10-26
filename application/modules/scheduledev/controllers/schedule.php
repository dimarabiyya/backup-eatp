<?php

class schedule extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('schedule_model');
		$this->load->model('reference_model');
		$this->load->model('schedule_checklist_model');
		$this->load->model('schedule_approval_model');
		$this->load->helper('form');

	}
	public function index(){
		$this->_check_access();
		if ($this->core->is_ajax())
		{
			if ($this->input->get('cmd') == 'getScheduleList')
			{
				$rows = $this->schedule_model->scheduleList();
				echo json_encode($rows);
				exit;
			}
		}

		if ($this->input->server('REQUEST_METHOD') == 'DELETE')
		{
			$id = (int) $this->core->decode($this->input->get('visitID'));

			$this->schedule_model->delete($id);

			$json = array('status' => 'success', 'message' => 'Data deleted successfully.');
			echo json_encode($json);
			exit;
		}
		/*Get All The Region Data*/
		$row_regions = $this->reference_model->regionList();

		$data['regions'] = json_encode($row_regions);


		$data['content_id'] = 'index';

		$this->load->view('template/smashing', $data);

	}
        
        public function cek_aja(){
            $users = $this->input->post('users');
            echo count($users);
             
        }

        public function get_recipient_notif(){
	$get_isi = $this->uri->segment(3);
        $filtera = str_replace("_"," ",$get_isi);
        //$filterb = str_replace("_"," ",$filtera);
        
	//echo json_encode(ucwords($filtera));

        
	$sql = $this->db->query("select a.*,b.email from t_mtr_approval a left join t_mtr_user b on b.user_id = a.user_id where a.template_name LIKE '%".ucwords($filtera)."%' and is_deleted = 0 ")->result();

	$arpush = array();
		foreach ($sql as $list) {
				$arrpushx = array(

						'user_id' => $list->user_id,
						'email' => $list->email,

				);
				array_push($arpush, $arrpushx);
		}
		$list = json_encode($arpush);
		echo "{\"data\":" . $list . "}";  
                 
                    
	}

	public function tesmail(){

		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'okkisetyawan@gmail.com',
			'smtp_pass' => 'Kutoarjo_93'
		);

		$this->load->library('email',$config);
		$this->email->set_newline("\r\n");
		$this->email->from('okkisetyawan@gmail.com');
		$this->email->to('okkisetyawan@gmail.com');
		$this->email->subject('Coba coba');
		$this->email->message('Trust me, its works!');

		if($this->email->send()){
			echo "bisa";
			exit();
		}else{
			echo "gabisa";
			exit();
		}
	}

	public function create(){
		$this->_check_access();
		/*Get All The Region Data*/
		$row_regions = $this->reference_model->regionList();
		/*Get All Site*/
		$row_sites = $this->reference_model->siteList();
		/*Get all PIC Data*/
		$row_pic = $this->reference_model->getPICList();
		/*Get All PO*/
		$row_po = $this->reference_model->getListPo();
		/*Get All SOW Type*/
		$row_sow = $this->reference_model->getListSOW();
		/*Get All Approval Type*/
		$row_approval = $this->reference_model->getListApproval();

		/*Get Priority Data*/
		$rs_prio =  $this->mglobal->lookup('pm_priority');
		$ar_prio = array();
		foreach($rs_prio as $item){
			$ar_prio[$item->lookup_value] = $item->lookup_desc;
		}

		$data["title"]    = "Create New Schedule";
		$data["priority"]    = ($ar_prio);
		$data['regions'] = ($row_regions);
		$data['sites'] = ($row_sites);
		$data['pics'] = ($row_pic);
		$data['po'] = ($row_po);
		$data['sow'] = ($row_sow);
		$data['approval'] = ($row_approval);
		$data['content_id'] = 'scheduleform';
		$data['schedule'] = $this->schedule_model->getSchedule(0);/*0 as Default Schedule*/
		$data['action_url'] = base_url("/schedule/save");
		$this->load->view('template/smashing', $data);
	}

	/*public function Edit schedule*/
	public function edit($id){
		$sch_id =  $this->core->decode($id);
		/*Get Reference ID*/
		$this->_check_access();
		/*Get All The Region Data*/
		$row_regions = $this->reference_model->regionList();
		/*Get All Site*/
		$row_sites = $this->reference_model->siteList();
		/*Get all PIC Data*/
		$row_pic = $this->reference_model->getPICList();
		/*Get All PO*/
		$row_po = $this->reference_model->getListPo();
		/*Get All SOW Type*/
		$row_sow = $this->reference_model->getListSOW();
		/*Get All Approval Type*/
		$row_approval = $this->reference_model->getListApproval();

		/*Get Priority Data*/
		$rs_prio =  $this->mglobal->lookup('pm_priority');
		$ar_prio = array();
		foreach($rs_prio as $item){
			$ar_prio[$item->lookup_value] = $item->lookup_desc;
		}

		$data["title"]    = "Edit Schedule";
		$data["priority"]    = ($ar_prio);
		$data['regions'] = ($row_regions);
		$data['sites'] = ($row_sites);
		$data['pics'] = ($row_pic);
		$data['po'] = ($row_po);
		$data['sow'] = ($row_sow);
		$data['approval'] = ($row_approval);
		$data['content_id'] = 'scheduleform';
		/*Get Detail Schedule with visit_id $schid*/
		$rs_sch = $this->schedule_model->getSchedule($sch_id);
		$data['schedule'] = $rs_sch;
		$data['action_url'] = base_url("/schedule/update")."/".$id;
		$this->load->view('template/smashing', $data);

	}
	public function save(){
		$postdata = $this->input->post();
                var_dump($postdata);
                exit();
		$param_keys = array('po_id', 'link_id','pic_id','visit_date','visit_type',
		'approval','priority','remark');


		if (is_array($postdata))
		{
			$data = array();
			foreach ($param_keys as $key)
			{
				if (array_key_exists($key, $postdata))
				{
					$data[$key] = $postdata[$key];

					if (trim($data[$key]) == '')
					{
						$data[$key] = NULL;
					}
				}
				else
				{
					$data[$key] = NULL;
				}
			}

			$validation_rules = array(
				array(
					'field' => 'po_id',
					'label' => 'PO NO.',
					'rules' => 'required'
				),
				array(
					'field' => 'link_id',
					'label' => 'Site Link',
					'rules' => 'required'
				),
				array(
					'field' => 'pic_id',
					'label' => 'Choose PIC',
					'rules' => 'required'
				),
				array(
					'field' => 'visit_date',
					'label' => 'Schedule Date',
					'rules' => 'required'
				),
				array(
					'field' => 'approval',
					'label' => 'Approval Template',
					'rules' => 'required'
				),
				array(
					'field' => 'checklist_item',
					'label' => 'Please choose Checklist Item',
					'rules' => 'required'
				)
			);

			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<li>', '</li>');

			if ($this->form_validation->run())
			{
				/*(1) Save data to table t_trx_schedule*/
				$post_sch = new stdClass;
				$post_sch->po_id = $data["po_id"];
				$post_sch->link_id = $data["link_id"];
				$post_sch->pic_id = $data["pic_id"];

				//get query for mail
				$smail = $this->db->query("select * from t_mtr_user where user_id = '$data[pic_id]' ")->row();
				$receiv = $smail->email;
								$config = array(
									'protocol' => 'smtp',
									'smtp_host' => 'ssl://smtp.gmail.com',
									'smtp_port' => 465,
									'smtp_user' => 'okkisetyawan@gmail.com',
									'smtp_pass' => 'Kutoarjo_93'
								);

								$this->load->library('email',$config);
								$this->email->set_newline("\r\n");
								$this->email->from('okkisetyawan@gmail.com');
								$this->email->to($receiv);
								$this->email->subject('TESTER FROM E-ATP (MAIL TESTING)');
								$this->email->message('Trust me, its works!');
								$this->email->send();
								/*
								if($this->email->send()){
									echo "bisa";
									exit();
								}else{
									echo "gabisa";
									exit();
								}
								*/


				$post_sch->visit_date = date("Y-m-d",strtotime($data["visit_date"]));
				$post_sch->visit_type = $data["visit_type"];
				$post_sch->remark = $data["remark"];
				/*If this Field is needed, uncomment this line below*/
				// $post_sch->template_approval = $data["approval"];

				$priority = "-";
				if($data["priority"]){
					$priority = $data["priority"];
				}
				/*Loook Up Region id from */
				$region = $this->reference_model->getDetailPO($data["po_id"]);
				if($region){
					$post_sch->region_id = $region->region_id;
				}else{
					$post_sch->region_id = 0;
				}
				/*end for look up region*/
				/*Default Status =0 Belum Di kunjungi*/
				$post_sch->status = 0;
				/*End Default Status*/
				$post_sch->priority = $priority;
				$post_sch->scheduled = 1; /*Scheduled yes*/
				$post_sch->fop_hs_name = "Not Defined yet";
				$post_sch->fop_hs_contact = "Not Defined yet";
				/*Default Security Code*/
				$security_check_in_code =  $this->randomString();
				$security_check_out_code =  $this->randomString();
				$post_sch->security_code_checkin = $security_check_in_code;
				$post_sch->security_code_checkout = $security_check_out_code;
				/*End Generate Security Code*/
				$post_sch->created_on = date('Y-m-d H:i:s');
				$post_sch->created_by = $this->session->userdata('user_id');
				$sch_id = $this->schedule_model->insert($post_sch);

				/*(2) Save data checklist to table t_trx_schedule_checklist*/
				foreach($this->input->post("checklist_item") as $val){
					$post_check = new stdClass;
					$post_check->visit_id = $sch_id;
					$post_check->checklist_id = $val;
					$this->schedule_checklist_model->insert($post_check);
				}
				/*(3) Iterate all user from Template choosen and save each of them in t_trx_schedule_result_approval*/
				$this->schedule_approval_model->remove($sch_id);
				$rs_approval_users = $this->reference_model->getuserapproval($data["approval"]);
				foreach($rs_approval_users as $item){
					/*(4) Save Each User Approval to t_trx_schedule_result_approval*/
					$post_approval_user = new stdClass;
					$post_approval_user->visit_id = $sch_id;
					$post_approval_user->order_no = $item->order_no;
					$post_approval_user->user_id = $item->user_id;
					$this->schedule_approval_model->insert($post_approval_user);
				}

				$json = array('status' => 'success', 'message' => 'Schedule Data is inserted successfully.');
				echo json_encode($json);
				exit;
			}else{
				$json = array('status' => 'error', 'message' => validation_errors());

				echo json_encode($json);
				exit;
			}
		}
	}
	/*Function Update Schedule*/
	public function update($id){
		$sch_id = $this->core->decode($id);
		$schedule = $this->schedule_model->getSchedule($sch_id);
		$postdata = $this->input->post();

		$param_keys = array('po_id', 'link_id','pic_id','visit_date','visit_type',
		'approval','priority','remark');

		if (is_array($postdata))
		{
			$data = array();
			foreach ($param_keys as $key)
			{
				if (array_key_exists($key, $postdata))
				{
					$data[$key] = $postdata[$key];

					if (trim($data[$key]) == '')
					{
						$data[$key] = NULL;
					}
				}
				else
				{
					$data[$key] = NULL;
				}
			}

			$validation_rules = array(
				array(
					'field' => 'po_id',
					'label' => 'PO NO.',
					'rules' => 'required'
				),
				array(
					'field' => 'link_id',
					'label' => 'Site Link',
					'rules' => 'required'
				),
				array(
					'field' => 'pic_id',
					'label' => 'Choose PIC',
					'rules' => 'required'
				),
				array(
					'field' => 'visit_date',
					'label' => 'Schedule Date',
					'rules' => 'required'
				),
				array(
					'field' => 'approval',
					'label' => 'Approval Template',
					'rules' => 'required'
				),
				array(
					'field' => 'checklist_item',
					'label' => 'Please choose Checklist Item',
					'rules' => 'required'
				)
			);

			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<li>', '</li>');

			if ($this->form_validation->run())
			{
				/*(1) Save data to table t_trx_schedule*/
				$post_sch = new stdClass;
				$post_sch->po_id = $data["po_id"];
				$post_sch->link_id = $data["link_id"];
				$post_sch->pic_id = $data["pic_id"];
				$post_sch->visit_date = date("Y-m-d",strtotime($data["visit_date"]));
				$post_sch->visit_type = $data["visit_type"];
				$post_sch->remark = $data["remark"];
				/*If this field is needed, uncomment this line below*/
				//$post_sch->template_approval = $data["approval"];
				$priority = "-";
				if($data["priority"]){
					$priority = $data["priority"];
				}
				/*Loook Up Region id from */
				$region = $this->reference_model->getDetailPO($data["po_id"]);
				if($region){
					$post_sch->region_id = $region->region_id;
				}else{
					$post_sch->region_id = 0;
				}
				/*end for look up region*/
				$post_sch->priority = $priority;
				$post_sch->scheduled = 1; /*Scheduled yes*/
				$post_sch->fop_hs_name = "Not Defined yet";
				$post_sch->fop_hs_contact = "Not Defined yet";
				$post_sch->updated_on = date('Y-m-d H:i:s');
				$post_sch->updated_by = $this->session->userdata('user_id');
				$this->schedule_model->update($post_sch,$sch_id);

				/*(2) Save data checklist to table t_trx_schedule_checklist*/
				/*Clear First checklist schedule before*/
				$this->schedule_checklist_model->remove($sch_id);
				foreach($this->input->post("checklist_item") as $val){
					$post_check = new stdClass;
					$post_check->visit_id = $sch_id;
					$post_check->checklist_id = $val;
					$this->schedule_checklist_model->insert($post_check);
				}
				/*(3) Iterate all user from Template choosen and save each of them in t_trx_schedule_result_approval*/
				$rs_approval_users = $this->reference_model->getuserapproval($data["approval"]);
				/*Check Fisrt is $data_approval == $sch_template_approval */
				//if(!($schedule->template_approval == $data["approval"]))
				//{
				/*IF NO Clear Approval by visit_id then insert new user template*/
				$this->schedule_approval_model->remove($sch_id);
				/*Insert new Approval user*/
				foreach($rs_approval_users as $item){
					/*(4) Save Each User Approval to t_trx_schedule_result_approval*/
					$post_approval_user = new stdClass;
					$post_approval_user->visit_id = $sch_id;
					$post_approval_user->order_no = $item->order_no;
					$post_approval_user->user_id = $item->user_id;
					$this->schedule_approval_model->insert($post_approval_user);
				}
				//}
				$json = array('status' => 'success', 'message' => 'Schedule Data is updated successfully.');
				echo json_encode($json);
				exit;
			}else{
				$json = array('status' => 'error', 'message' => validation_errors());

				echo json_encode($json);
				exit;
			}
		}
	}
	/*Function to Save Approval*/
        /*SAVE REJECT OR APROVE*/
	public function approval($visit_id){
		$visit_id = $this->core->decode($visit_id);
		$param_keys = array('status', 'remarks');
		$postdata = $this->input->post();
		if (is_array($postdata))
		{
			$data = array();
			foreach ($param_keys as $key)
			{
				if (array_key_exists($key, $postdata))
				{
					$data[$key] = $postdata[$key];

					if (trim($data[$key]) == '')
					{
						$data[$key] = NULL;
					}
				}
				else
				{
					$data[$key] = NULL;
				}
			}

			$validation_rules = array(
				array(
					'field' => 'status',
					'label' => 'State Approve or Reject',
					'rules' => 'required'
				),
				array(
					'field' => 'remarks',
					'label' => 'Some Notes (Please fill Remarks)',
					'rules' => 'required'
				)
			);

			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<li>', '</li>');

			if ($this->form_validation->run())
			{
				/*If Status Formulir = 1 Approve*/
				if($data["status"] == 1){
					$fop_name = $this->input->post("fop_name");
					$fop_contact = $this->input->post("fop_contact");

					if(empty($fop_name) || empty($fop_contact)){
						$json = array('status' => 'error', 'message' => "Fill FOP Name and Contact");
						echo json_encode($json);
						exit;
					}else{
						$post_approval_schedule = new stdClass;
						$post_approval_schedule->approval = 1;
						$post_approval_schedule->approval_remarks = $this->input->post("remarks");
						$post_approval_schedule->fop_hs_name = $fop_name;
						$post_approval_schedule->fop_hs_contact = $fop_contact;
						/* Status 1 Confirmed Schedule*/
						$post_approval_schedule->status = 1;
						/*End Default Status*/
						$post_approval_schedule->updated_on = date('Y-m-d H:i:s');
						$post_approval_schedule->updated_by = $this->session->userdata('user_id');
						$this->schedule_model->update($post_approval_schedule,$visit_id);
					}
				}else if($data["status"] == 2){
					$cancellation_reason = $this->input->post("cancellationRemark");
					if(empty($cancellation_reason)){
						$json = array('status' => 'error', 'message' => "Fill Cancellation Reason");
						echo json_encode($json);
						exit;
					}else{
						$post_cancel_schedule = new stdClass;
						$post_reject_schedule->updated_on = date('Y-m-d H:i:s');
						/* Status 2 Decline*/
						$post_reject_schedule->status = 2;
						$post_reject_schedule->cancellation_remark = $this->input->post("remarks");
						$post_reject_schedule->cancellation_reason = $this->input->post("reschedule_reason");
						$post_reject_schedule->updated_by = $this->session->userdata('user_id');
						$this->schedule_model->update($post_reject_schedule,$visit_id);
					}
				}
				else{/*If Status Formulir = 0 or others Reject*/
					$proposed_date = $this->input->post("proposed_date");
					if(empty($proposed_date)){
						$json = array('status' => 'error', 'message' => "Fill Proposed Date");
						echo json_encode($json);
						exit;
					}else{
						$post_reject_schedule = new stdClass;
						$post_reject_schedule->approval = 0;/*Reject*/
						$post_reject_schedule->proposed_date = date("Y-m-d",strtotime($proposed_date));/*Reject*/
						$post_reject_schedule->updated_on = date('Y-m-d H:i:s');
						/* Status 5 Re-Schedule*/
						$post_reject_schedule->status = 5;
						$post_reject_schedule->remark_reschedule = $this->input->post("remarks");
						$post_reject_schedule->reschedule_reason = $this->input->post("reschedule_reason");
						$post_reject_schedule->updated_by = $this->session->userdata('user_id');
						$this->schedule_model->update($post_reject_schedule,$visit_id);
					}
				}
				$json = array('status' => 'success', 'message' => 'Schedule has been updated with successfully.');
				echo json_encode($json);
				exit;
			}else{
				$json = array('status' => 'error', 'message' => validation_errors());

				echo json_encode($json);
				exit;
			}
		}
	}
	/*Function Generate Security Code*/
	public function securitycode(){
		$remark = $this->input->post("remark");
		$visit_id = $this->input->post("visit_id");
		if($remark){
			// $security_check_in_code =  $this->randomString();
			// $security_check_out_code =  $this->randomString();
			$post_sec_code = new stdClass;
			// $post_sec_code->security_code_checkin = $security_check_in_code;
			// $post_sec_code->security_code_checkout = $security_check_out_code;
			$post_sec_code->security_code_remark = $remark;
			$post_sec_code->is_code_show = 1;/*Show code*/
			$post_sec_code->security_code_generate_date = date('Y-m-d');
			$post_sec_code->security_code_generate_by = $this->session->userdata('user_id');
			$this->schedule_model->update($post_sec_code,$visit_id);
			/*Get Security Code*/
			$rs_sch = $this->schedule_model->getSchedule($visit_id);
			$security_check_in_code =  $rs_sch->security_code_checkin;
			$security_check_out_code =  $rs_sch->security_code_checkout;

			$json = array('status' => 'success', 'message' => 'Code Generated Succesfully',
			'code_chk_in'=>$security_check_in_code,
			'code_chk_out'=>$security_check_out_code);
			echo json_encode($json);
			exit;

		}else{
			$json = array('status' => 'success', 'message' => 'Please Fill Remark when Generate Security Code');
			echo json_encode($json);
			exit;
		}


	}
	public function randomString()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$randstring = '';
		for ($i = 0; $i < 5; $i++)
		{ $randstring .= $characters[rand(0, (strlen($characters)-1) )];}
		return $randstring;
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

	public function getDetailSite(){
		$site_id = $this->input->get("site_id");
		$res = $this->reference_model->getDetailSite($site_id);
		echo json_encode($res);
	}
	public function getInfoLink(){
		$link_id = $this->input->get("link");
		echo json_encode($this->reference_model->getSiteInfo($link_id));
	}
	/*Function get All Option Checklist from schedule_id*/
	public function listchecklist($schedule_id){
		/*Decode first ID*/
		echo json_encode($this->schedule_checklist_model->getChecklistOption($schedule_id));
	}

	public function detailSchedule($visit_id){
		$sch_id = $this->core->decode($visit_id);
		$rs_sch = $this->schedule_model->getSchedule($sch_id);
		echo json_encode($rs_sch);
	}

}
