<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('muser');
		$this->load->model('mdashboardv02');
		$this->load->library('parser');
		$this->mglobal->is_logged_in();

		$this->load->model('schedule/schedule_model','sch_model');
		$this->load->model('visitsummary/summary_model','vst_model');
	}
	
	public function index()
	{
		ini_set('memory_limit', '-1');
    	set_time_limit(0);

		$username = $this->session->userdata('user_id');
		$territory_type_id = $this->muser->get_user_territory_type_id($this->session->userdata('user_group_id'));

		$data['$territory_type_id'] = $territory_type_id;
		$data['data_region']= '<div class="controls" style="padding-top: 6px;font-weight:bold;">'.$this->session->userdata('region_name').'</div>';
		$data['data_cluster_ops']= '<div class="controls" style="padding-top: 6px;font-weight:bold;">'.$this->session->userdata('cluster_ops_name').'</div>';
		$data['data_cluster_net']= '<div class="controls" style="padding-top: 6px;font-weight:bold;">'.$this->session->userdata('cluster_net_name').'</div>';
		$data['data_cluster_com']= '<div class="controls" style="padding-top: 6px;font-weight:bold;">'.$this->session->userdata('cluster_com_name').'</div>';

		$data['tombol_simpan'] ="";
		$data['pilih_region'] ="";
		$data['pilih_cluster'] ="";
		//jika territory_type_id = 3/5/6
		if($territory_type_id == '3' || $territory_type_id == '5' || $territory_type_id == '6')
		{
			//no action
			$data['logic'] = '1'; // jika territory_type_id = 3/5/6
			$a = array('logic' => $data['logic']);
			$this->session->set_userdata($a);
		}else if($territory_type_id == '2')
		{
			//pilih cluster
			$data['logic'] = '2'; // //jika territory_type_id = 2
			$data['pilih_cluster'] ="ya";
		}else{
			$data['pilih_region'] ="ya";
			$data['pilih_cluster'] ="ya";
		}

		//echo $territory_type_id."ss";
/* di disable by iwa 20170123
		if($data['pilih_cluster'] == 'ya' )
		{
			//pilih cluster
			$data['tombol_simpan'] ="ya";

			///-------------------------------------------------------------------------------------------------------------------
			$data['data_cluster_ops']= '<div class="controls"><select id="cluster_ops" name="cluster_ops" class="span4">';
			$cluster = $this->muser->getClusterOpsByRegion($this->session->userdata('region_id'));
			foreach($cluster as $r){
				$data['data_cluster_ops'] .='<option value="'.$r->cluster_ops_id.'" '. ($this->session->userdata('cluster_ops_id') == $r->cluster_ops_id?"SELECTED":"") .'>'. $r->cluster_name .'</option>';
			}
			$data['data_cluster_ops'] .='</select></div>';
			///-------------------------------------------------------------------------------------------------------------------
			/*
			$data['data_cluster_net']= '<div class="controls"><select id="cluster_net" name="cluster_net" class="span4">';

			$cluster = $this->muser->getClusterNetByRegion($this->session->userdata('region_id'));
			foreach($cluster as $r){
				$data['data_cluster_net'] .='<option value="'.$r->cluster_net_id.'" '. ($this->session->userdata('cluster_net_id') == $r->cluster_net_id?"SELECTED":"") .'>'. $r->cluster_name .'</option>';
			}
			$data['data_cluster_net'] .='</select></div>';
			*/
			///-------------------------------------------------------------------------------------------------------------------
/*
			$data['data_cluster_com']= '<div class="controls"><select id="cluster_com" name="cluster_com" class="span4">';
			$cluster = $this->muser->getClusterComByRegion($this->session->userdata('region_id'));
			foreach($cluster as $r){
				$data['data_cluster_com'] .='<option value="'.$r->cluster_com_id.'" '. ($this->session->userdata('cluster_com_id') == $r->cluster_com_id?"SELECTED":"") .'>'. $r->cluster_name .'</option>';
			}
			$data['data_cluster_com'] .='</select></div>';
			///-------------------------------------------------------------------------------------------------------------------
		}
*/
		if($data['pilih_region'] == 'ya' ){
			$data['tombol_simpan'] ="ya";
			///-------------------------------------------------------------------------------------------------------------------
			$data['data_region']= '<div class="controls"><select id="region" name="region" class="span4">';
			$region = $this->muser->getRegionByID();
			foreach($region as $r){
				$data['data_region'] .='<option value="'.$r->region_id.'" '. ($this->session->userdata('region_id') == $r->region_id?"SELECTED":"") .'>'. $r->region_name .'</option>';
			}
			$data['data_region'] .='</select></div>';
			///-------------------------------------------------------------------------------------------------------------------
		}


		/*GET DATA DASHBORD FROM Model mdashboardv02*/
		$regions = $this->mdashboardv02->getRegions();
		/*Default Year*/
		$year = date("Y");
		if(preg_match('/^\d{4}(,\d{4})*$/', (string) $this->input->get("year"))){
			$year = $this->input->get("year");
		}
		/*Options Year*/
		$max_year = $this->mdashboardv02->getMaxYear();
		$min_year = $this->mdashboardv02->getMinYear();
		$years = array();
		for($i=$min_year;$i<=$max_year;$i++){
			$years[$i] = $i;
		}

		$array_summary = array();
		// $grand_total = new stdClass;
		// $grand_total->po=0;$grand_total->din=0;$grand_total->atp=0;$grand_total->ess=0;
		// $grand_total->done=0;$grand_total->ongoing=0;$grand_total->hold=0;$grand_total->notstart=0;

		// $region_chart = array();
		// $data_series_line_chart = array();

		// $line_chart_data_po = new stdClass;
		// $line_chart_data_po->name = "PO";
		// $line_chart_data_din = new stdClass;
		// $line_chart_data_din->name = "DIN";
		// $line_chart_data_atp = new stdClass;
		// $line_chart_data_atp->name = "ATP";
		// $line_chart_data_ess = new stdClass;
		// $line_chart_data_ess->name = "ESS";
		// $line_chart_data_done = new stdClass;
		// $line_chart_data_done->name = "DONE";
		// $line_chart_data_ongoing = new stdClass;
		// $line_chart_data_ongoing->name = "On Going";
		// $line_chart_data_hold = new stdClass;
		// $line_chart_data_hold->name = "Hold";
		// $line_chart_data_notstart = new stdClass;
		// $line_chart_data_notstart->name = "Not Start";


		// foreach($regions as $key=>$value){
		// 	/*Region for Chart*/
		// 	$region_chart[] = $value;

		// 	$std_sum = new stdClass;
		// 	$std_sum->region = $value;
		// 	/*Init Var for count po,din,atp,ess,done,ongoing,hold,notstart*/
		// 	$std_sum->total_po =0;$std_sum->total_din=0;$std_sum->total_ess=0;$std_sum->total_done=0;
		// 	$std_sum->total_atp=0;$std_sum->total_ongoing=0;$std_sum->total_hold=0;$std_sum->total_notstart=0;
		// 	/*Result Count PO, DIN, ESS*/
		// 	$rs_01 = $this->mdashboardv02->getPolink($key,$year);

		// 	$installation_type = new stdClass;
		// 	/*init value*/
		// 	$installation_type->po =0;
		// 	$installation_type->din =0;
		// 	$installation_type->atp =0;
		// 	$installation_type->ess =0;
		// 	$installation_type->pi_closed =0;
		// 	$installation_type->pi_done =0;
		// 	$installation_type->pi_ongoing =0;
		// 	$installation_type->pi_hold =0;
		// 	$installation_type->pi_notstart =0;
		// 	$addservice_type = new stdClass;
		// 	/*init value*/
		// 	$addservice_type->po =0;
		// 	$addservice_type->din =0;
		// 	$addservice_type->atp =0;
		// 	$addservice_type->ess =0;
		// 	$addservice_type->pi_closed =0;
		// 	$addservice_type->pi_done =0;
		// 	$addservice_type->pi_ongoing =0;
		// 	$addservice_type->pi_hold =0;
		// 	$addservice_type->pi_notstart =0;

		// 	/*Set Title Type*/
		// 	$installation_type->title = "Installation";
		// 	/*Set Title Type*/
		// 	$addservice_type->title = "Add Service";
		// 	/*Count PoLink Installation and Add Service*/
		// 	$installation_po = $this->mdashboardv02->getCountPoli(true,$key,$year);
		// 	$installation_type->po = $installation_po;
		// 	$std_sum->total_po += $installation_po;
		// 	$addservice_po = $this->mdashboardv02->getCountPoli(false,$key,$year);
		// 	$addservice_type->po = $addservice_po;
		// 	$std_sum->total_po += $addservice_po;
		// 	/*Count DIN*/
		// 	$din_count_inst = $this->mdashboardv02->getCountDin(true,$key,$year);
		// 	$installation_type->din = $din_count_inst;
		// 	$std_sum->total_din += $din_count_inst;
		// 	$din_count_add = $this->mdashboardv02->getCountDin(false,$key,$year);
		// 	$addservice_type->din = $din_count_add;
		// 	$std_sum->total_din += $din_count_add;
		// 	/*Count ESS*/
		// 	$ess_count_inst = $this->mdashboardv02->getCountESS(true,$key,$year);
		// 	$installation_type->ess = $ess_count_inst;
		// 	$std_sum->total_ess += $ess_count_inst;
		// 	$ess_count_add = $this->mdashboardv02->getCountESS(false,$key,$year);
		// 	$addservice_type->ess = $ess_count_add;
		// 	$std_sum->total_ess += $ess_count_add;

		// 	foreach($rs_01 as $item_01){
		// 		if($item_01->tipe == 1){/*Installation*/
		// 			/*Get Count Based on Progress Status*/
		// 			/*Status Closed = 5 *//*$po_id,$link,$is_installation,$progress_flag*/
		// 			$rs_closed = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,true,5);
		// 			$installation_type->pi_closed += $rs_closed->count;
		// 			$std_sum->total_atp += $rs_closed->count;
		// 			/*Status DONE = 4 *//*$po_id,$link,$is_installation,$progress_flag*/
		// 			$rs_done = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,true,4);
		// 			$installation_type->pi_done += $rs_done->count;
		// 			$std_sum->total_done += $rs_done->count;
		// 			/*Status ON Going = 3 *//*$po_id,$link,$is_installation,$progress_flag*/
		// 			$rs_ongoing = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,true,3);
		// 			$installation_type->pi_ongoing += $rs_ongoing->count;
		// 			$std_sum->total_ongoing += $rs_ongoing->count;
		// 			/*Status Hold = 2 *//*$po_id,$link,$is_installation,$progress_flag*/
		// 			$rs_hold = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,true,2);
		// 			$installation_type->pi_hold += $rs_hold->count;
		// 			$std_sum->total_hold += $rs_hold->count;
		// 			/*Status No Start = 1 *//*$po_id,$link,$is_installation,$progress_flag*/
		// 			$rs_not_start = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,true,1);
		// 			$installation_type->pi_notstart += $rs_not_start->count;
		// 			$std_sum->total_notstart += $rs_not_start->count;
		// 		}else{/*Add Service*/

		// 			/*Get Count Based on Progress Status*/
		// 			/*Status Closed = 5 *//*$po_id,$link,$is_installation,$progress_flag*/
		// 			$rs_closed = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,false,5);
		// 			$addservice_type->pi_closed += $rs_closed->count;
		// 			$std_sum->total_atp += $rs_closed->count;
		// 			/*Status DONE = 4 *//*$po_id,$link,$is_installation,$progress_flag*/
		// 			$rs_done = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,false,4);
		// 			$addservice_type->pi_done += $rs_done->count;
		// 			$std_sum->total_done += $rs_done->count;
		// 			/*Status ON Going = 3 *//*$po_id,$link,$is_installation,$progress_flag*/
		// 			$rs_ongoing = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,false,3);
		// 			$addservice_type->pi_ongoing += $rs_ongoing->count;
		// 			$std_sum->total_ongoing += $rs_ongoing->count;
		// 			/*Status Hold = 2 *//*$po_id,$link,$is_installation,$progress_flag*/
		// 			$rs_hold = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,false,2);
		// 			$addservice_type->pi_hold += $rs_hold->count;
		// 			$std_sum->total_hold += $rs_hold->count;
		// 			/*Status No Start = 1 *//*$po_id,$link,$is_installation,$progress_flag*/
		// 			$rs_not_start = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,false,1);
		// 			$addservice_type->pi_notstart += $rs_not_start->count;
		// 			$std_sum->total_notstart += $rs_not_start->count;
		// 		}
		// 	}
		// 	/*assign installation info*/
		// 	$std_sum->installation = $installation_type;
		// 	$std_sum->addservice = $addservice_type;
		// 	array_push($array_summary, $std_sum);
		// 	/*Add Value to Grand_total*/
		// 	$grand_total->po += $std_sum->total_po;
		// 	$grand_total->din += $std_sum->total_din;
		// 	$grand_total->atp += $std_sum->total_atp;
		// 	$grand_total->ess += $std_sum->total_ess;
		// 	$grand_total->done += $std_sum->total_done;
		// 	$grand_total->ongoing += $std_sum->total_ongoing;
		// 	$grand_total->hold += $std_sum->total_hold;
		// 	$grand_total->notstart += $std_sum->total_notstart;

		// 	/*value for line chart data*/
		// 	$line_chart_data_po->data[]= $std_sum->total_po;
		// 	$line_chart_data_din->data[]= $std_sum->total_din;
		// 	$line_chart_data_atp->data[]= $std_sum->total_atp;
		// 	$line_chart_data_ess->data[]= $std_sum->total_ess;
		// 	$line_chart_data_done->data[]= $std_sum->total_done;
		// 	$line_chart_data_ongoing->data[]= $std_sum->total_ongoing;
		// 	$line_chart_data_hold->data[]= $std_sum->total_hold;
		// 	$line_chart_data_notstart->data[]= $std_sum->total_notstart;
		// 	/*end*/
		// }
		// /*Add All Line Chart Data to Array Line series*/
		// array_push($data_series_line_chart, $line_chart_data_po);
		// array_push($data_series_line_chart, $line_chart_data_din);
		// array_push($data_series_line_chart, $line_chart_data_atp);
		// array_push($data_series_line_chart, $line_chart_data_ess);
		// array_push($data_series_line_chart, $line_chart_data_done);
		// array_push($data_series_line_chart, $line_chart_data_ongoing);
		// array_push($data_series_line_chart, $line_chart_data_hold);
		// array_push($data_series_line_chart, $line_chart_data_notstart);

		// //echo json_encode($data_series_line_chart);die;


		//echo "<pre/>";
		//print_r($array_summary);die;
		// if($this->input->get("cmd") && $this->input->get("cmd") =="getJson"){
		// 	$json = array('status' => 'success', 'message' => $array_summary);
		// 	echo json_encode($json);
		// 	exit;
		// }

		//start commented by Hadi 2018-05-02
		//sum schedule assign
		//$data['sum_s_a'] =  $this->sch_model->count_schedule();
		//end commented by Hadi 2018-05-02

		//start edited by ilham 2022-05-30
		$yearFilter = "";
		if (isset($_GET['year']) && preg_match('/^\d{4}(,\d{4})*$/', (string) $_GET['year'])) {
			$yearFilter = " AND EXTRACT(YEAR FROM a.created_on) in (".$_GET['year'].")";
		}
		
		//start edited by Nopi 2021-04-014
		$data['datatables'] = "";
		//sum schedule assign by layer 1 & 2 (quality & fop)
		$query = $this->db->query("SELECT COUNT(DISTINCT  (c.po_no,a.created_on)) from t_trx_schedule a left join t_trx_po_xl c on c.po_id = a.po_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no=3");
		$data['sum_s_a'] = $query->result()[0]->count;
		// print_r($data); die();
		if(isset($_GET['id']) && $_GET['id']=='sum_s_a'){
			$sum_s_a = $this->db->query("SELECT DISTINCT on (c.po_no,a.created_on) a.visit_id,a.tpd_id,a.visit_type,a.is_code_show,a.security_code_checkin,a.security_code_checkout,a.po_id,c.po_no,b.ewo_no,b.po_description,d.link_id,e.site_name as site_fe_name ,e.site_address as address_fe,f.site_name as site_ne_name ,g.user_name as \"field_eng\",h.option_label as \"visit_type_label\",g.user_id as \"field_eng_id\",f.site_address as address_ne,g.user_name,m.updated_on as \"qc_date\",o.user_id as \"fop_hs_name\",n.updated_on as \"fop_date\",k.user_id as \"spv_hs_name\",k.updated_on as \"spv_date\",l.user_id as \"som_hs_name\",l.updated_on as \"som_date\",a.visit_date as visit_plan,h.option_label,i.region_name as region,a.last_visited_date as actual_date,CASE WHEN a.status = 0 then 'Not Confirmed' WHEN a.status = 1 then 'Confirmed' WHEN a.status = 2 then 'Declined' WHEN a.status = 3 then 'Visited' WHEN a.status = 4 then 'Unschedule' WHEN a.status = 5 then 'Re-Schedule' end as desc_status from t_trx_schedule a left join t_trx_po_link b on b.po_id = a.po_id left join t_trx_po_xl c on c.po_id = a.po_id left join t_mtr_site_link d on d.link_id_int = a.link_id left JOIN t_mtr_site_xl e on d.site_id_fe = e.site_id left JOIN t_mtr_site_xl f on d.site_id_ne = f.site_id left join t_mtr_user g on g.user_id = a.pic_id left join t_mtr_checklist_options h on h.option_id = a.visit_type::integer left join t_mtr_region_xl i on i.region_id = a.region_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id LEFT JOIN t_trx_schedule_result_approval k on k.visit_id = a.visit_id AND k.order_no = '3' LEFT JOIN t_trx_schedule_result_approval l on l.visit_id = a.visit_id AND l.order_no = '4' LEFT JOIN t_trx_schedule_result_approval o on o.visit_id = a.visit_id AND o.order_no = '2' LEFT JOIN t_trx_schedule_result_approval m on (m.visit_id = a.visit_id and m.order_no = 1) LEFT JOIN t_trx_schedule_result_approval n on (n.visit_id = a.visit_id and n.order_no = 2) WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no=3".$yearFilter." ORDER BY a.created_on DESC");
			
			$arr_sum_s_a = $sum_s_a->result();
			foreach ($arr_sum_s_a as $rows) {
				if ($rows->option_label == "UMPT") {
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . $rows->qc_date . "</td>
					<td>" . '-' . "</td>
					<td>" . '-' . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->fop_date . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->spv_date . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}else{
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . $rows->qc_date . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->fop_date . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->spv_date . "</td>
					<td>" . $rows->som_hs_name . "</td>
					<td>" . $rows->spv_date . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}
			}
			$data['datatables_is_sum_s_a'] = 1;
		}
		//<td>" . $rows->address_ne . "</td>
		//<td>" . $rows->address_fe . "</td>
		
		//log_message('debug',$data['data_sum_s_a']);
		//end edited by Nopi 2021-04-014

                //sum schedule aprove

		$data['sum_s_apr'] = $this->vst_model->count_atp_accepted();

        //start commented by Hadi 2018-05-02
		//sum schedule reject
		//$data['sum_s_rjt'] = $this->vst_model->count_atp_rejected();
		//end commented by Hadi 2018-05-02
		
		//start edited by Hadi 2018-05-02
		//sum schedule reject by layer 2 & 3 (fop & spv)
		$query = $this->db->query("SELECT COUNT(DISTINCT (c.po_no,a.created_on)) from t_trx_schedule a left join t_trx_po_xl c on c.po_id = a.po_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.qty_rejected != '0' AND j.order_no=3");
		$data['sum_s_rjt'] = $query->result()[0]->count;
		if(isset($_GET['id']) && $_GET['id']=='sum_s_rjt'){
			$sum_s_rjt = $this->db->query("SELECT DISTINCT on (c.po_no,a.created_on) a.visit_id,a.tpd_id,a.visit_type,a.is_code_show,a.security_code_checkin,a.security_code_checkout,a.po_id,c.po_no,b.ewo_no,b.po_description,d.link_id,e.site_name as site_fe_name ,e.site_address address_fe,f.site_name as site_ne_name ,g.user_name as \"field_eng\",h.option_label as \"visit_type_label\",g.user_id as \"field_eng_id\",f.site_address as address_ne,g.user_name,o.user_id as \"fop_hs_name\",k.user_id as \"spv_hs_name\",l.user_id as \"som_hs_name\",a.visit_date as visit_plan,h.option_label,i.region_name as region,a.last_visited_date as actual_date,CASE WHEN a.status = 0 then 'Not Confirmed' WHEN a.status = 1 then 'Confirmed' WHEN a.status = 2 then 'Declined' WHEN a.status = 3 then 'Visited' WHEN a.status = 4 then 'Unschedule' WHEN a.status = 5 then 'Re-Schedule' end as desc_status from t_trx_schedule a left join t_trx_po_link b on b.po_id = a.po_id left join t_trx_po_xl c on c.po_id = a.po_id left join t_mtr_site_link d on d.link_id_int = a.link_id left JOIN t_mtr_site_xl e on d.site_id_fe = e.site_id left JOIN t_mtr_site_xl f on d.site_id_ne = f.site_id left join t_mtr_user g on g.user_id = a.pic_id left join t_mtr_checklist_options h on h.option_id = a.visit_type::integer left join t_mtr_region_xl i on i.region_id = a.region_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id LEFT JOIN t_trx_schedule_result_approval k on k.visit_id = a.visit_id AND k.order_no = '3' LEFT JOIN t_trx_schedule_result_approval l on l.visit_id = a.visit_id AND l.order_no = '4' LEFT JOIN t_trx_schedule_result_approval o on o.visit_id = a.visit_id AND o.order_no = '2' WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.qty_rejected != '0' AND j.order_no=3".$yearFilter." ORDER BY a.created_on DESC");
			
			$arr_sum_s_rjt = $sum_s_rjt->result();
			
			foreach ($arr_sum_s_rjt as $rows) {
				if($rows->option_label == "UMPT"){
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . '-' . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}else{
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->som_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}
			}
		}
		//end edited by Hadi 2018-05-02

                //sum schedule visit
		// $sum_s_vst = $this->db->query("select * from t_trx_schedule where status = 3 and is_deleted = 0");
		$data['sum_s_vst'] = $this->vst_model->count_atp_visited();

        //start commented by Hadi 2018-05-02
		//sum schedule fop hs
		/*$sum_s_fophs = $this->db->query("select DISTINCT on (c.po_no,a.created_on) a.*,b.user_id,b.acceptance_status,e.user_group_name from t_trx_schedule a
left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id
left join t_trx_po_xl c on c.po_id = a.po_id
left join t_mtr_user d on d.user_id = b.user_id
left join t_mtr_user_group e on e.user_group_id = d.user_group_id
 WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3 and b.acceptance_status = 1 and e.user_group_id = 3");
		$data['sum_s_fophs'] = $sum_s_fophs->num_rows;

        //sum schedule spv hs
		$sum_s_spvhs = $this->db->query("select DISTINCT on (c.po_no,a.created_on) a.*,b.user_id,b.acceptance_status,e.user_group_name from t_trx_schedule a
left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id
left join t_trx_po_xl c on c.po_id = a.po_id
left join t_mtr_user d on d.user_id = b.user_id
left join t_mtr_user_group e on e.user_group_id = d.user_group_id
 WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3 and b.acceptance_status = 1 and e.user_group_id = 4");
		$data['sum_s_spvhs'] = $sum_s_spvhs->num_rows;*/
		//end commented by Hadi 2018-05-02
		
		//start edited by Hadi 2018-05-02
		//sum schedule before approve by layer 1 (quality)
		$query = $this->db->query("SELECT COUNT(DISTINCT (c.po_no,a.created_on)) from t_trx_schedule a left join t_trx_po_xl c on c.po_id = a.po_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no = '1' AND j.status IS NULL");
		$data['sum_before_aprv_quality'] = $query->result()[0]->count;
		if(isset($_GET['id']) && $_GET['id']=='sum_before_aprv_quality'){
			$sum_before_aprv_quality = $this->db->query("SELECT DISTINCT on (c.po_no,a.created_on) a.visit_id,a.tpd_id,a.visit_type,a.is_code_show,a.security_code_checkin,a.security_code_checkout,a.po_id,c.po_no,b.ewo_no,b.po_description,d.link_id,e.site_name as site_fe_name ,e.site_address address_fe,f.site_name as site_ne_name ,g.user_name as \"field_eng\",h.option_label as \"visit_type_label\",g.user_id as \"field_eng_id\",f.site_address as address_ne,g.user_name,o.user_id as \"fop_hs_name\",j.user_id as \"spv_hs_name\",l.user_id as \"som_hs_name\",a.visit_date as visit_plan,h.option_label,i.region_name as region,a.last_visited_date as actual_date,CASE WHEN a.status = 0 then 'Not Confirmed' WHEN a.status = 1 then 'Confirmed' WHEN a.status = 2 then 'Declined' WHEN a.status = 3 then 'Visited' WHEN a.status = 4 then 'Unschedule' WHEN a.status = 5 then 'Re-Schedule' end as desc_status from t_trx_schedule a left join t_trx_po_link b on b.po_id = a.po_id left join t_trx_po_xl c on c.po_id = a.po_id left join t_mtr_site_link d on d.link_id_int = a.link_id left JOIN t_mtr_site_xl e on d.site_id_fe = e.site_id left JOIN t_mtr_site_xl f on d.site_id_ne = f.site_id left join t_mtr_user g on g.user_id = a.pic_id left join t_mtr_checklist_options h on h.option_id = a.visit_type::integer left join t_mtr_region_xl i on i.region_id = a.region_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id LEFT JOIN t_trx_schedule_result_approval k on k.visit_id = a.visit_id AND k.order_no = '3' LEFT JOIN t_trx_schedule_result_approval l on l.visit_id = a.visit_id AND l.order_no = '4' LEFT JOIN t_trx_schedule_result_approval o on o.visit_id = a.visit_id AND o.order_no = '2' WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no = '1' AND j.status IS NULL".$yearFilter." ORDER BY a.created_on DESC");
			
			$arr_sum_before_aprv_quality = $sum_before_aprv_quality->result();
			foreach ($arr_sum_before_aprv_quality as $rows) {
				if($rows->option_label == "UMPT"){
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . '-' . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}else{
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->som_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}
			}
		}

		$query = $this->db->query("SELECT COUNT(DISTINCT (c.po_no,a.created_on)) from t_trx_schedule a left join t_trx_po_xl c on c.po_id = a.po_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no = '1' AND j.status = '1' AND j.qty_rejected = '0'");
		$data['sum_aprv_quality'] = $query->result()[0]->count;
		//sum schedule approve by layer 1 (quality)
		if(isset($_GET['id']) && $_GET['id']=='sum_aprv_quality'){
			$sum_aprv_quality = $this->db->query("SELECT DISTINCT on (c.po_no,a.created_on) a.visit_id,a.tpd_id,a.visit_type,a.is_code_show,a.security_code_checkin,a.security_code_checkout,a.po_id,c.po_no,b.ewo_no,b.po_description,d.link_id,e.site_name as site_fe_name ,e.site_address address_fe,f.site_name as site_ne_name ,g.user_name as \"field_eng\",h.option_label as \"visit_type_label\",g.user_id as \"field_eng_id\",f.site_address as address_ne,g.user_name,o.user_id as \"fop_hs_name\",j.user_id as \"spv_hs_name\",l.user_id as \"som_hs_name\",a.visit_date as visit_plan,h.option_label,i.region_name as region,a.last_visited_date as actual_date,CASE WHEN a.status = 0 then 'Not Confirmed' WHEN a.status = 1 then 'Confirmed' WHEN a.status = 2 then 'Declined' WHEN a.status = 3 then 'Visited' WHEN a.status = 4 then 'Unschedule' WHEN a.status = 5 then 'Re-Schedule' end as desc_status from t_trx_schedule a left join t_trx_po_link b on b.po_id = a.po_id left join t_trx_po_xl c on c.po_id = a.po_id left join t_mtr_site_link d on d.link_id_int = a.link_id left JOIN t_mtr_site_xl e on d.site_id_fe = e.site_id left JOIN t_mtr_site_xl f on d.site_id_ne = f.site_id left join t_mtr_user g on g.user_id = a.pic_id left join t_mtr_checklist_options h on h.option_id = a.visit_type::integer left join t_mtr_region_xl i on i.region_id = a.region_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id LEFT JOIN t_trx_schedule_result_approval k on k.visit_id = a.visit_id AND k.order_no = '3' LEFT JOIN t_trx_schedule_result_approval l on l.visit_id = a.visit_id AND l.order_no = '4' LEFT JOIN t_trx_schedule_result_approval o on o.visit_id = a.visit_id AND o.order_no = '2' WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no = '1' AND j.status = '1' AND j.qty_rejected = '0'".$yearFilter." ORDER BY a.created_on DESC");
			
			$arr_sum_aprv_quality = $sum_aprv_quality->result();
			foreach ($arr_sum_aprv_quality as $rows) {
				if($rows->option_label == "UMPT"){
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . '-' . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}else{
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->som_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}
			}
		}

		//sum schedule approve by fop hs
		$query = $this->db->query("SELECT COUNT(DISTINCT (c.po_no,a.created_on)) from t_trx_schedule a left join t_trx_po_xl c on c.po_id = a.po_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no = '2' AND j.status = '1' AND j.qty_rejected = '0'");
		$data['sum_s_fophs'] = $query->result()[0]->count;
		if(isset($_GET['id']) && $_GET['id']=='sum_s_fophs'){
			$sum_s_fophs = $this->db->query("SELECT DISTINCT on (c.po_no,a.created_on) a.visit_id,a.tpd_id,a.visit_type,a.is_code_show,a.security_code_checkin,a.security_code_checkout,a.po_id,c.po_no,b.ewo_no,b.po_description,d.link_id,e.site_name as site_fe_name ,e.site_address address_fe,f.site_name as site_ne_name ,g.user_name as \"field_eng\",h.option_label as \"visit_type_label\",g.user_id as \"field_eng_id\",f.site_address as address_ne,g.user_name,o.user_id as \"fop_hs_name\",j.user_id as \"spv_hs_name\",l.user_id as \"som_hs_name\",a.visit_date as visit_plan,h.option_label,i.region_name as region,a.last_visited_date as actual_date,CASE WHEN a.status = 0 then 'Not Confirmed' WHEN a.status = 1 then 'Confirmed' WHEN a.status = 2 then 'Declined' WHEN a.status = 3 then 'Visited' WHEN a.status = 4 then 'Unschedule' WHEN a.status = 5 then 'Re-Schedule' end as desc_status from t_trx_schedule a left join t_trx_po_link b on b.po_id = a.po_id left join t_trx_po_xl c on c.po_id = a.po_id left join t_mtr_site_link d on d.link_id_int = a.link_id left JOIN t_mtr_site_xl e on d.site_id_fe = e.site_id left JOIN t_mtr_site_xl f on d.site_id_ne = f.site_id left join t_mtr_user g on g.user_id = a.pic_id left join t_mtr_checklist_options h on h.option_id = a.visit_type::integer left join t_mtr_region_xl i on i.region_id = a.region_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id LEFT JOIN t_trx_schedule_result_approval k on k.visit_id = a.visit_id AND k.order_no = '3' LEFT JOIN t_trx_schedule_result_approval l on l.visit_id = a.visit_id AND l.order_no = '4' LEFT JOIN t_trx_schedule_result_approval o on o.visit_id = a.visit_id AND o.order_no = '2' WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no = '2' AND j.status = '1' AND j.qty_rejected = '0'".$yearFilter." ORDER BY a.created_on DESC");
			
			$arr_sum_s_fophs = $sum_s_fophs->result();

			foreach ($arr_sum_s_fophs as $rows) {
				if($rows->option_label == "UMPT"){
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . '-' . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}else{
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->som_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}
			}
		}

		//sum schedule approve by spv hs
		$query = $this->db->query("SELECT COUNT(DISTINCT (c.po_no,a.created_on)) from t_trx_schedule a left join t_trx_po_xl c on c.po_id = a.po_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no = '3' AND j.status = '1' AND j.qty_rejected = '0'");
		$data['sum_s_spvhs'] = $query->result()[0]->count;
		if(isset($_GET['id']) && $_GET['id']=='sum_s_spvhs'){
			$sum_s_spvhs = $this->db->query("SELECT DISTINCT on (c.po_no,a.created_on) a.visit_id,a.tpd_id,a.visit_type,a.is_code_show,a.security_code_checkin,a.security_code_checkout,a.po_id,c.po_no,b.ewo_no,b.po_description,d.link_id,e.site_name as site_fe_name ,e.site_address address_fe,f.site_name as site_ne_name ,g.user_name as \"field_eng\",h.option_label as \"visit_type_label\",g.user_id as \"field_eng_id\",f.site_address as address_ne,g.user_name,o.user_id as \"fop_hs_name\",j.user_id as \"spv_hs_name\",l.user_id as \"som_hs_name\",a.visit_date as visit_plan,h.option_label,i.region_name as region,a.last_visited_date as actual_date,CASE WHEN a.status = 0 then 'Not Confirmed' WHEN a.status = 1 then 'Confirmed' WHEN a.status = 2 then 'Declined' WHEN a.status = 3 then 'Visited' WHEN a.status = 4 then 'Unschedule' WHEN a.status = 5 then 'Re-Schedule' end as desc_status from t_trx_schedule a left join t_trx_po_link b on b.po_id = a.po_id left join t_trx_po_xl c on c.po_id = a.po_id left join t_mtr_site_link d on d.link_id_int = a.link_id left JOIN t_mtr_site_xl e on d.site_id_fe = e.site_id left JOIN t_mtr_site_xl f on d.site_id_ne = f.site_id left join t_mtr_user g on g.user_id = a.pic_id left join t_mtr_checklist_options h on h.option_id = a.visit_type::integer left join t_mtr_region_xl i on i.region_id = a.region_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id LEFT JOIN t_trx_schedule_result_approval k on k.visit_id = a.visit_id AND k.order_no = '3' LEFT JOIN t_trx_schedule_result_approval l on l.visit_id = a.visit_id AND l.order_no = '4' LEFT JOIN t_trx_schedule_result_approval o on o.visit_id = a.visit_id AND o.order_no = '2' WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no = '3' AND j.status = '1' AND j.qty_rejected = '0'".$yearFilter." ORDER BY a.created_on DESC");
			
			$arr_sum_s_spvhs = $sum_s_spvhs->result();
			foreach ($arr_sum_s_spvhs as $rows) {
				if($rows->option_label == "UMPT"){
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . '-' . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}else{
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->som_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}
			}
		}

		//Amanah - 28/03/2022 (add SOM)
		//sum schedule approve by som hs
		$query = $this->db->query("SELECT COUNT(DISTINCT (c.po_no,a.created_on)) from t_trx_schedule a left join t_trx_po_xl c on c.po_id = a.po_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no = '4' AND j.status = '1' AND j.qty_rejected = '0'");
		$data['sum_s_somhs'] = $query->result()[0]->count;
		if(isset($_GET['id']) && $_GET['id']=='sum_s_somhs'){
			$sum_s_somhs = $this->db->query("SELECT DISTINCT on (c.po_no,a.created_on) a.visit_id,a.tpd_id,a.visit_type,a.is_code_show,a.security_code_checkin,a.security_code_checkout,a.po_id,c.po_no,b.ewo_no,b.po_description,d.link_id,e.site_name as site_fe_name ,e.site_address address_fe,f.site_name as site_ne_name ,g.user_name as \"field_eng\",h.option_label as \"visit_type_label\",g.user_id as \"field_eng_id\",f.site_address as address_ne,g.user_name,o.user_id as \"fop_hs_name\",j.user_id as \"spv_hs_name\",l.user_id as \"som_hs_name\",a.visit_date as visit_plan,h.option_label,i.region_name as region,a.last_visited_date as actual_date,CASE WHEN a.status = 0 then 'Not Confirmed' WHEN a.status = 1 then 'Confirmed' WHEN a.status = 2 then 'Declined' WHEN a.status = 3 then 'Visited' WHEN a.status = 4 then 'Unschedule' WHEN a.status = 5 then 'Re-Schedule' end as desc_status from t_trx_schedule a left join t_trx_po_link b on b.po_id = a.po_id left join t_trx_po_xl c on c.po_id = a.po_id left join t_mtr_site_link d on d.link_id_int = a.link_id left JOIN t_mtr_site_xl e on d.site_id_fe = e.site_id left JOIN t_mtr_site_xl f on d.site_id_ne = f.site_id left join t_mtr_user g on g.user_id = a.pic_id left join t_mtr_checklist_options h on h.option_id = a.visit_type::integer left join t_mtr_region_xl i on i.region_id = a.region_id LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id LEFT JOIN t_trx_schedule_result_approval k on k.visit_id = a.visit_id AND k.order_no = '3' LEFT JOIN t_trx_schedule_result_approval l on l.visit_id = a.visit_id AND l.order_no = '4' LEFT JOIN t_trx_schedule_result_approval o on o.visit_id = a.visit_id AND o.order_no = '2' WHERE (a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0) AND j.order_no = '4' AND j.status = '1' AND j.qty_rejected = '0'".$yearFilter." ORDER BY a.created_on DESC");
			
			$arr_sum_s_somhs = $sum_s_somhs->result();
			foreach ($arr_sum_s_somhs as $rows) {
				if($rows->option_label == "UMPT"){
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . '-' . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}else{
					$data['datatables'] .= "<tr>
					<td>" . $rows->visit_id . "</td>
					<td>" . $rows->tpd_id . "</td>
					<td>" . $rows->po_no . "</td>
					<td>" . $rows->ewo_no . "</td>
					<td>" . $rows->po_description . "</td>
					<td>" . $rows->link_id . "</td>
					<td>" . $rows->site_ne_name . "</td>
					<td>" . $rows->site_fe_name . "</td>
					<td>" . $rows->field_eng_id . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					<td>" . $rows->spv_hs_name . "</td>
					<td>" . $rows->som_hs_name . "</td>
					<td>" . $rows->option_label . "</td>
					<td>" . $rows->region . "</td>
					<td>" . $rows->visit_plan . "</td>
					<td>" . $rows->actual_date . "</td>
					<td>" . $rows->desc_status . "</td>
					<td>" . $rows->fop_hs_name . "</td>
					</tr>";
				}
			}
		}
		
		//end edited by Hadi 2018-05-02

                //sum schedule spv xl/pmo xl
// 		$sum_s_spvxl = $this->db->query("select DISTINCT on (c.po_no,a.created_on) a.*,b.user_id,b.acceptance_status,e.user_group_name from t_trx_schedule a
// left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id
// left join t_trx_po_xl c on c.po_id = a.po_id
// left join t_mtr_user d on d.user_id = b.user_id
// left join t_mtr_user_group e on e.user_group_id = d.user_group_id
//  WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3 and b.acceptance_status = 1 and e.user_group_id = 2");
// 		$data['sum_s_spvxl'] = $sum_s_spvxl->num_rows;

		$sum_s_spvxl = $this->db->query("select COUNT(DISTINCT (c.po_no,a.created_on)) from t_trx_schedule a
		left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id
		left join t_trx_po_xl c on c.po_id = a.po_id
		left join t_mtr_user d on d.user_id = b.user_id
		left join t_mtr_user_group e on e.user_group_id = d.user_group_id
		 WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3 and b.acceptance_status = 1 and e.user_group_id = 2");
		$data['sum_s_spvxl'] = $query->result()[0]->count;


		$data['judul'] = "Dashboard";
		$data['content_id'] = "dashboard";
		$data["title"] = "PO XL ".$year;
		// $data["summary"] = $array_summary;
		$data["selected_year"] = $year;
		$data["option_years"] = $years;
		// $data["grand_total"] = $grand_total;
		$data['data_header'] = array("Region","PO Type","PO","DIN","ATP","ESS","Done","On Going","Hold","Not Start");

		/*data for line chart*/
		$data['subtitle_line_chart'] = "";
		// $data['title_line_chart'] = "Grafik Progress PO XL ".$year;
		// $data['region_line_chart'] = json_encode($region_chart);
		// $data['data_series_line_chart'] = json_encode($data_series_line_chart);

		if($this->input->get("cmd") && $this->input->get("cmd") =="filter"){
			$data["grand_total"] = json_encode($grand_total);
			echo $this->parser->parse('dashboard/summarytable', $data);
			exit;
		}
		$this->load->view("template/smashing", $data);


	}

	public function dashboard()
	{
		ini_set('memory_limit', '-1');
    	set_time_limit(0);

		$username = $this->session->userdata('user_id');
		$territory_type_id = $this->muser->get_user_territory_type_id($this->session->userdata('user_group_id'));

		$data['$territory_type_id'] = $territory_type_id;
		$data['data_region']= '<div class="controls" style="padding-top: 6px;font-weight:bold;">'.$this->session->userdata('region_name').'</div>';
		$data['data_cluster_ops']= '<div class="controls" style="padding-top: 6px;font-weight:bold;">'.$this->session->userdata('cluster_ops_name').'</div>';
		$data['data_cluster_net']= '<div class="controls" style="padding-top: 6px;font-weight:bold;">'.$this->session->userdata('cluster_net_name').'</div>';
		$data['data_cluster_com']= '<div class="controls" style="padding-top: 6px;font-weight:bold;">'.$this->session->userdata('cluster_com_name').'</div>';

		$data['tombol_simpan'] ="";
		$data['pilih_region'] ="";
		$data['pilih_cluster'] ="";
		//jika territory_type_id = 3/5/6
		if($territory_type_id == '3' || $territory_type_id == '5' || $territory_type_id == '6')
		{
			//no action
			$data['logic'] = '1'; // jika territory_type_id = 3/5/6
			$a = array('logic' => $data['logic']);
			$this->session->set_userdata($a);
		}else if($territory_type_id == '2')
		{
			//pilih cluster
			$data['logic'] = '2'; // //jika territory_type_id = 2
			$data['pilih_cluster'] ="ya";
		}else{
			$data['pilih_region'] ="ya";
			$data['pilih_cluster'] ="ya";
		}

		//echo $territory_type_id."ss";
		/* di disable by iwa 20170123
		if($data['pilih_cluster'] == 'ya' )
		{
			//pilih cluster
			$data['tombol_simpan'] ="ya";

			///-------------------------------------------------------------------------------------------------------------------
			$data['data_cluster_ops']= '<div class="controls"><select id="cluster_ops" name="cluster_ops" class="span4">';
			$cluster = $this->muser->getClusterOpsByRegion($this->session->userdata('region_id'));
			foreach($cluster as $r){
				$data['data_cluster_ops'] .='<option value="'.$r->cluster_ops_id.'" '. ($this->session->userdata('cluster_ops_id') == $r->cluster_ops_id?"SELECTED":"") .'>'. $r->cluster_name .'</option>';
			}
			$data['data_cluster_ops'] .='</select></div>';
			///-------------------------------------------------------------------------------------------------------------------
			/*
			$data['data_cluster_net']= '<div class="controls"><select id="cluster_net" name="cluster_net" class="span4">';

			$cluster = $this->muser->getClusterNetByRegion($this->session->userdata('region_id'));
			foreach($cluster as $r){
				$data['data_cluster_net'] .='<option value="'.$r->cluster_net_id.'" '. ($this->session->userdata('cluster_net_id') == $r->cluster_net_id?"SELECTED":"") .'>'. $r->cluster_name .'</option>';
			}
			$data['data_cluster_net'] .='</select></div>';
			*/
			///-------------------------------------------------------------------------------------------------------------------
		/*
			$data['data_cluster_com']= '<div class="controls"><select id="cluster_com" name="cluster_com" class="span4">';
			$cluster = $this->muser->getClusterComByRegion($this->session->userdata('region_id'));
			foreach($cluster as $r){
				$data['data_cluster_com'] .='<option value="'.$r->cluster_com_id.'" '. ($this->session->userdata('cluster_com_id') == $r->cluster_com_id?"SELECTED":"") .'>'. $r->cluster_name .'</option>';
			}
			$data['data_cluster_com'] .='</select></div>';
			///-------------------------------------------------------------------------------------------------------------------
		}
		*/
		if($data['pilih_region'] == 'ya' ){
			$data['tombol_simpan'] ="ya";
			///-------------------------------------------------------------------------------------------------------------------
			$data['data_region']= '<div class="controls"><select id="region" name="region" class="span4">';
			$region = $this->muser->getRegionByID();
			foreach($region as $r){
				$data['data_region'] .='<option value="'.$r->region_id.'" '. ($this->session->userdata('region_id') == $r->region_id?"SELECTED":"") .'>'. $r->region_name .'</option>';
			}
			$data['data_region'] .='</select></div>';
			///-------------------------------------------------------------------------------------------------------------------
		}


		/*GET DATA DASHBORD FROM Model mdashboardv02*/
		$regions = $this->mdashboardv02->getRegions();
		/*Default Year*/
		$year = date("Y");
		if(preg_match('/^\d{4}(,\d{4})*$/', (string) $this->input->get("year"))){
			$year = $this->input->get("year");
		}
		/*Options Year*/
		$max_year = $this->mdashboardv02->getMaxYear();
		$min_year = $this->mdashboardv02->getMinYear();
		$years = array();
		for($i=$min_year;$i<=$max_year;$i++){
			$years[$i] = $i;
		}

		$array_summary = array();
		$grand_total = new stdClass;
		$grand_total->po=0;$grand_total->din=0;$grand_total->atp=0;$grand_total->ess=0;
		$grand_total->done=0;$grand_total->ongoing=0;$grand_total->hold=0;$grand_total->notstart=0;

		$region_chart = array();
		$data_series_line_chart = array();

		$line_chart_data_po = new stdClass;
		$line_chart_data_po->name = "PO";
		$line_chart_data_din = new stdClass;
		$line_chart_data_din->name = "DIN";
		$line_chart_data_atp = new stdClass;
		$line_chart_data_atp->name = "ATP";
		$line_chart_data_ess = new stdClass;
		$line_chart_data_ess->name = "ESS";
		$line_chart_data_done = new stdClass;
		$line_chart_data_done->name = "DONE";
		$line_chart_data_ongoing = new stdClass;
		$line_chart_data_ongoing->name = "On Going";
		$line_chart_data_hold = new stdClass;
		$line_chart_data_hold->name = "Hold";
		$line_chart_data_notstart = new stdClass;
		$line_chart_data_notstart->name = "Not Start";


		foreach($regions as $key=>$value){
			/*Region for Chart*/
			$region_chart[] = $value;

			$std_sum = new stdClass;
			$std_sum->region = $value;
			/*Init Var for count po,din,atp,ess,done,ongoing,hold,notstart*/
			$std_sum->total_po =0;$std_sum->total_din=0;$std_sum->total_ess=0;$std_sum->total_done=0;
			$std_sum->total_atp=0;$std_sum->total_ongoing=0;$std_sum->total_hold=0;$std_sum->total_notstart=0;
			/*Result Count PO, DIN, ESS*/
			$rs_01 = $this->mdashboardv02->getPolink($key,$year);

			$installation_type = new stdClass;
			/*init value*/
			$installation_type->po =0;
			$installation_type->din =0;
			$installation_type->atp =0;
			$installation_type->ess =0;
			$installation_type->pi_closed =0;
			$installation_type->pi_done =0;
			$installation_type->pi_ongoing =0;
			$installation_type->pi_hold =0;
			$installation_type->pi_notstart =0;
			$addservice_type = new stdClass;
			/*init value*/
			$addservice_type->po =0;
			$addservice_type->din =0;
			$addservice_type->atp =0;
			$addservice_type->ess =0;
			$addservice_type->pi_closed =0;
			$addservice_type->pi_done =0;
			$addservice_type->pi_ongoing =0;
			$addservice_type->pi_hold =0;
			$addservice_type->pi_notstart =0;

			/*Set Title Type*/
			$installation_type->title = "Installation";
			/*Set Title Type*/
			$addservice_type->title = "Add Service";
			/*Count PoLink Installation and Add Service*/
			$installation_po = $this->mdashboardv02->getCountPoli(true,$key,$year);
			$installation_type->po = $installation_po;
			$std_sum->total_po += $installation_po;
			$addservice_po = $this->mdashboardv02->getCountPoli(false,$key,$year);
			$addservice_type->po = $addservice_po;
			$std_sum->total_po += $addservice_po;
			/*Count DIN*/
			$din_count_inst = $this->mdashboardv02->getCountDin(true,$key,$year);
			$installation_type->din = $din_count_inst;
			$std_sum->total_din += $din_count_inst;
			$din_count_add = $this->mdashboardv02->getCountDin(false,$key,$year);
			$addservice_type->din = $din_count_add;
			$std_sum->total_din += $din_count_add;
			/*Count ESS*/
			$ess_count_inst = $this->mdashboardv02->getCountESS(true,$key,$year);
			$installation_type->ess = $ess_count_inst;
			$std_sum->total_ess += $ess_count_inst;
			$ess_count_add = $this->mdashboardv02->getCountESS(false,$key,$year);
			$addservice_type->ess = $ess_count_add;
			$std_sum->total_ess += $ess_count_add;

			foreach($rs_01 as $item_01){
				if($item_01->tipe == 1){/*Installation*/
					/*Get Count Based on Progress Status*/
					/*Status Closed = 5 *//*$po_id,$link,$is_installation,$progress_flag*/
					$rs_closed = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,true,5);
					$installation_type->pi_closed += $rs_closed->count;
					$std_sum->total_atp += $rs_closed->count;
					/*Status DONE = 4 *//*$po_id,$link,$is_installation,$progress_flag*/
					$rs_done = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,true,4);
					$installation_type->pi_done += $rs_done->count;
					$std_sum->total_done += $rs_done->count;
					/*Status ON Going = 3 *//*$po_id,$link,$is_installation,$progress_flag*/
					$rs_ongoing = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,true,3);
					$installation_type->pi_ongoing += $rs_ongoing->count;
					$std_sum->total_ongoing += $rs_ongoing->count;
					/*Status Hold = 2 *//*$po_id,$link,$is_installation,$progress_flag*/
					$rs_hold = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,true,2);
					$installation_type->pi_hold += $rs_hold->count;
					$std_sum->total_hold += $rs_hold->count;
					/*Status No Start = 1 *//*$po_id,$link,$is_installation,$progress_flag*/
					$rs_not_start = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,true,1);
					$installation_type->pi_notstart += $rs_not_start->count;
					$std_sum->total_notstart += $rs_not_start->count;
				}else{/*Add Service*/

					/*Get Count Based on Progress Status*/
					/*Status Closed = 5 *//*$po_id,$link,$is_installation,$progress_flag*/
					$rs_closed = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,false,5);
					$addservice_type->pi_closed += $rs_closed->count;
					$std_sum->total_atp += $rs_closed->count;
					/*Status DONE = 4 *//*$po_id,$link,$is_installation,$progress_flag*/
					$rs_done = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,false,4);
					$addservice_type->pi_done += $rs_done->count;
					$std_sum->total_done += $rs_done->count;
					/*Status ON Going = 3 *//*$po_id,$link,$is_installation,$progress_flag*/
					$rs_ongoing = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,false,3);
					$addservice_type->pi_ongoing += $rs_ongoing->count;
					$std_sum->total_ongoing += $rs_ongoing->count;
					/*Status Hold = 2 *//*$po_id,$link,$is_installation,$progress_flag*/
					$rs_hold = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,false,2);
					$addservice_type->pi_hold += $rs_hold->count;
					$std_sum->total_hold += $rs_hold->count;
					/*Status No Start = 1 *//*$po_id,$link,$is_installation,$progress_flag*/
					$rs_not_start = $this->mdashboardv02->getSummaryPolinkProgress($item_01->po_id,$item_01->link_id_int,false,1);
					$addservice_type->pi_notstart += $rs_not_start->count;
					$std_sum->total_notstart += $rs_not_start->count;
				}
			}
			/*assign installation info*/
			$std_sum->installation = $installation_type;
			$std_sum->addservice = $addservice_type;
			array_push($array_summary, $std_sum);
			/*Add Value to Grand_total*/
			$grand_total->po += $std_sum->total_po;
			$grand_total->din += $std_sum->total_din;
			$grand_total->atp += $std_sum->total_atp;
			$grand_total->ess += $std_sum->total_ess;
			$grand_total->done += $std_sum->total_done;
			$grand_total->ongoing += $std_sum->total_ongoing;
			$grand_total->hold += $std_sum->total_hold;
			$grand_total->notstart += $std_sum->total_notstart;

			/*value for line chart data*/
			$line_chart_data_po->data[]= $std_sum->total_po;
			$line_chart_data_din->data[]= $std_sum->total_din;
			$line_chart_data_atp->data[]= $std_sum->total_atp;
			$line_chart_data_ess->data[]= $std_sum->total_ess;
			$line_chart_data_done->data[]= $std_sum->total_done;
			$line_chart_data_ongoing->data[]= $std_sum->total_ongoing;
			$line_chart_data_hold->data[]= $std_sum->total_hold;
			$line_chart_data_notstart->data[]= $std_sum->total_notstart;
			/*end*/
		}
		/*Add All Line Chart Data to Array Line series*/
		array_push($data_series_line_chart, $line_chart_data_po);
		array_push($data_series_line_chart, $line_chart_data_din);
		array_push($data_series_line_chart, $line_chart_data_atp);
		array_push($data_series_line_chart, $line_chart_data_ess);
		array_push($data_series_line_chart, $line_chart_data_done);
		array_push($data_series_line_chart, $line_chart_data_ongoing);
		array_push($data_series_line_chart, $line_chart_data_hold);
		array_push($data_series_line_chart, $line_chart_data_notstart);

		//echo json_encode($data_series_line_chart);die;


		//echo "<pre/>";
		//print_r($array_summary);die;
		if($this->input->get("cmd") && $this->input->get("cmd") =="getJson"){
			$json = array('status' => 'success', 'message' => $array_summary);
			echo json_encode($json);
			exit;
		}

		//sum schedule assign

		$data['sum_s_a'] =  $this->sch_model->count_schedule();

                //sum schedule aprove

		$data['sum_s_apr'] = $this->vst_model->count_atp_accepted();

                 //sum schedule reject

		$data['sum_s_rjt'] = $this->vst_model->count_atp_rejected();

                //sum schedule visit
		$sum_s_vst = $this->db->query("select * from t_trx_schedule where status = 3 and is_deleted = 0");
		$data['sum_s_vst'] = $this->vst_model->count_atp_visited();

                //sum schedule fop hs
		$sum_s_fophs = $this->db->query("select DISTINCT on (c.po_no,a.created_on) a.*,b.user_id,b.acceptance_status,e.user_group_name from t_trx_schedule a
left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id
left join t_trx_po_xl c on c.po_id = a.po_id
left join t_mtr_user d on d.user_id = b.user_id
left join t_mtr_user_group e on e.user_group_id = d.user_group_id
 WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3 and b.acceptance_status = 1 and e.user_group_id = 3");
		$data['sum_s_fophs'] = $sum_s_fophs->num_rows;

                //sum schedule spv hs
		$sum_s_spvhs = $this->db->query("select DISTINCT on (c.po_no,a.created_on) a.*,b.user_id,b.acceptance_status,e.user_group_name from t_trx_schedule a
left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id
left join t_trx_po_xl c on c.po_id = a.po_id
left join t_mtr_user d on d.user_id = b.user_id
left join t_mtr_user_group e on e.user_group_id = d.user_group_id
 WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3 and b.acceptance_status = 1 and e.user_group_id = 4");
		$data['sum_s_spvhs'] = $sum_s_spvhs->num_rows;

                //sum schedule spv xl/pmo xl
		$sum_s_spvxl = $this->db->query("select DISTINCT on (c.po_no,a.created_on) a.*,b.user_id,b.acceptance_status,e.user_group_name from t_trx_schedule a
left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id
left join t_trx_po_xl c on c.po_id = a.po_id
left join t_mtr_user d on d.user_id = b.user_id
left join t_mtr_user_group e on e.user_group_id = d.user_group_id
 WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3 and b.acceptance_status = 1 and e.user_group_id = 2");
		$data['sum_s_spvxl'] = $sum_s_spvxl->num_rows;


		$data['judul'] = "Dashboard";
		$data['content_id'] = "dashboard";
		$data["title"] = "PO XL ".$year;
		$data["summary"] = $array_summary;
		$data["selected_year"] = $year;
		$data["option_years"] = $years;
		$data["grand_total"] = $grand_total;
		$data['data_header'] = array("Region","PO Type","PO","DIN","ATP","ESS","Done","On Going","Hold","Not Start");

		/*data for line chart*/
		$data['subtitle_line_chart'] = "";
		$data['title_line_chart'] = "Grafik Progress PO XL ".$year;
		$data['region_line_chart'] = json_encode($region_chart);
		$data['data_series_line_chart'] = json_encode($data_series_line_chart);

		if($this->input->get("cmd") && $this->input->get("cmd") =="filter"){
			$data["grand_total"] = json_encode($grand_total);
			echo $this->parser->parse('dashboard/summarytable', $data);
			exit;
		}
		$this->load->view("template/smashing", $data);


	}

/* di disable by iwa 20170123
	function tampilkanCluster(){

		$region_id = $this->input->post('region_id');
		$cluster_ops = array(); $cluster_net = array(); $cluster_com = array();

		$cluster = $this->muser->getClusterOpsByRegion($region_id);
		foreach($cluster as $r){
			$cluster_ops[] = array('cluster_id'=>$r->cluster_ops_id,'cluster_name'=>$r->cluster_name);
		}

		$cluster = $this->muser->getClusterNetByRegion($region_id);
		foreach($cluster as $r){
			$cluster_net[] = array('cluster_id'=>$r->cluster_net_id,'cluster_name'=>$r->cluster_name);
		}

		$cluster = $this->muser->getClusterComByRegion($region_id);
		foreach($cluster as $r){
			$cluster_com[] = array('cluster_id'=>$r->cluster_com_id,'cluster_name'=>$r->cluster_name);
		}

		echo json_encode(array(
			'cluster_ops' => $cluster_ops,
			'cluster_net' => $cluster_net,
			'cluster_com' => $cluster_com
		));
		return;
	}
	*/
	function prosesSettingSession(){
		$region = $this->input->post('region');
		$cluster_ops = $this->input->post('cluster_ops');
		$cluster_net = $this->input->post('cluster_net');
		$cluster_com = $this->input->post('cluster_com');

		$data = array('region_id'		=>$region,
			      'region_name'		=>$this->mglobal->showdata("region_name", "t_mtr_region", array('region_id'=>$region),"dblokal"),
			      'cluster_net_id'		=>$cluster_net,
			      'cluster_net_name'	=>$this->mglobal->showdata("cluster_name", "t_mtr_cluster_net", array('cluster_net_id'=>$cluster_net),"dblokal"),
			      'cluster_ops_id'		=>$cluster_ops,
			      'cluster_ops_name'	=>$this->mglobal->showdata("cluster_name", "t_mtr_cluster_ops", array('cluster_ops_id'=>$cluster_ops),"dblokal"),
			      'cluster_com_id'		=>$cluster_com,
			      'cluster_com_name'	=>$this->mglobal->showdata("cluster_name", "t_mtr_cluster_com", array('cluster_com_id'=>$cluster_com),"dblokal")
			      );
		$this->session->set_userdata( $data );
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
