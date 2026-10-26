<?php

class checklistapproval extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();			
		$this->load->library('parser');
		$this->load->model("summary_model");
	}
	
	public function index(){
		$data = array();
		$reject = "Rejected Survey Result";
		if($this->input->get("flag_approval") && $this->input->get("flag_approval")==1)
		{
			$reject = "Approve Survey Result";
		}
		$data["approval_title"] = $reject;
		echo $this->parser->parse('remark_approval', $data);
		exit;	
	}
	public function save(){
		$data = $this->input->post();
		//print_r($data);die;
		$dt_chk_answer = new stdClass;
		$dt_chk_answer->visit_id = $this->input->post("visit_id");
		$dt_chk_answer->checklist_id = $this->input->post("chk_id");
		$dt_chk_answer->status = $this->input->post("type");
		$dt_chk_answer->remark = $this->input->post("remark");
		$dt_chk_answer->user_id = $this->session->userdata('user_id');
		$dt_chk_answer->created_on = date('Y-m-d H:i:s');
		$dt_chk_answer->created_by = $this->session->userdata('user_id');
		//var_dump($dt_chk_answer);die;
		$this->summary_model->saveApprovalAnswer($dt_chk_answer,false);
		/*Update Schedule*/
		$visit_id = $this->input->post("visit_id");

		$final_status_approval = $this->summary_model->is_approved_all($visit_id,$this->session->userdata('user_id'));
		$is_reject = $this->input->post("type");
		$qty_approval = $this->summary_model->countApprovalStatus($visit_id,$this->session->userdata('user_id'),1);/*Approval*/
		$qty_rejected = $this->summary_model->countApprovalStatus($visit_id,$this->session->userdata('user_id'),0);/*Rejected*/
		//echo $final_status_approval."-".$visit_id;die;
		/*update t_trx_schedule_result_approval*/
		$dt_res_approval = new stdClass;
		$dt_res_approval->status = $final_status_approval;
		$dt_res_approval->qty_approved = $qty_approval;
		$dt_res_approval->qty_rejected = $qty_rejected;
		$dt_res_approval->updated_on = date('Y-m-d H:i:s');
		$dt_res_approval->updated_by = $this->session->userdata('user_id');
		$this->summary_model->updateResultApproval($dt_res_approval,$visit_id,$this->session->userdata('user_id'));


		$json = array('status' => 'success', 'message' => 'Approval data is successfully inserted');
		echo json_encode($json);
		exit;
	}
}
