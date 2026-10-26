<?php
//dev by nutech integrasi
class visitsummary extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("reference_model");
        $this->load->model("summary_model");
        //start edited by Hadi 2018-03-19
        $this->load->model("summary_model_forced");
        //end
    }

    /* Function Index */

    public function index()
    {
        $this->_check_access();
        if ($this->core->is_ajax()) {
            if ($this->input->get('cmd') == 'getJson') {
                $sch = $this->input->get('sch_id');
                $rows = $this->summary_model->getList($sch);
                echo json_encode($rows);
                exit;
            }
        }

        /* Get All The Region Data */

        $row_regions = $this->reference_model->regionList();
        $userTerritory = $this->mglobal->userTerritory($this->session->userdata('user_id'));


        //echo $this->session->userdata('ugrup');

        $territory = array();
        $x = 0;
        foreach ($userTerritory['region'] as $rr) {
            $territory[$x] = $rr;
            foreach ($userTerritory['cluster'] as $cc) {
                if ($cc['region_id'] == $rr['region_id']) {
                    $territory[$x]['cluster'][] = $cc;
                }
            }

            $x++;
        }
        /* Get Months */
        $row_months = array();
        for ($i = 1; $i <= 12; $i++) {
            $row_months[$i] = date("F", mktime(0, 0, 0, $i, 1, date('Y')));
        }
        /* Get Year */
        $row_year = array();
        $row_rs_year = $this->db->select('extract(YEAR from min(created_on)) AS op_year_start')->get('t_trx_checklist_result')->row();
        if ($row_rs_year->op_year_start == 0) {
            $row_year[date('Y')] = date('Y');
        } else {
            for ($y = $row_rs_year->op_year_start; $y <= date('Y'); $y++)
                $row_year[$y] = $y;
        }


        $data["territory"] = $territory;
        $data['regions'] = json_encode($row_regions);
        $data['months'] = json_encode($row_months);
        $data['years'] = json_encode($row_year);
        $data['content_id'] = 'index';

        $this->load->view('template/smashing', $data);
    }

    public function list_upload()
    {
        $id = $this->uri->segment(3);
        $getid = $this->core->decode($id);
        $this->db->where('visit_id', $getid);
        return $sql = $this->db->get('t_trx_docs_summary')->result();
    }
    public function upload_doc()
    {
        $id = $this->uri->segment(3);
        $getid = $this->core->decode($id);

        $datatype = $this->input->post('category');

        if ($datatype == 'simf_instalasi') {
        } else if ($datatype == 'simf_dismantle') {
            # code...
        } else if ($datatype == 'ims_antenna') {
            # code...
        } else if ($datatype == 'ims_idu') {
            # code...
        } else if ($datatype == 'ims_odu') {
            # code...
        } else if ($datatype == 'ims_connection') {
            # code...
        }

        $data['listupload'] = $this->summary_model->list_upload($getid);

        $this->db->where('visit_id', $getid);
        $sql = $this->db->get('t_trx_docs_summary')->result();

        $sqldata = $this->db->where('visit_id', $getid)->get('t_trx_docs_summary');
        if ($sqldata->num_rows() > 0) {
            $getdatafile = $sqldata->row();
            $data['files'] = "<a target='_blank' href=" . base_url('/file/upload/' . $getdatafile->fdocs) . "> View Your File</a>";
        } else {
            $data['files'] = "";
        }
        $data['not_dec_id'] = $id;
        $data['id'] = $getid;
        $data['content_id'] = 'upload_doc';

        $this->load->view('template/smashing', $data);
    }

    public function print_all_excel()
    {

        $params = (int) $this->uri->segment(3);

        /*
        3 Visited
        0 Unvisited
        if ($status == 3) {
            $where .= "AND a.status = {$status}";
        } else {
            $where .= "AND a.status != 3";
        }*/
        header("Content-Type: application/force-download");
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 2010 05:00:00 GMT");
        header("content-disposition: attachment;filename=visitsummary" . date('y-m-d') . ".xls");

        /* Approval per visit digabung di database (ap), bukan 1 query per baris */
        $sqldata = $this->db->query("select DISTINCT on (c.po_no,a.created_on) a.visit_id,a.visit_type,a.is_code_show,a.security_code_checkin,a.security_code_checkout,a.po_id,c.
po_no,b.ewo_no,b.po_description,d.link_id,e.site_name as site_fe_name ,e.site_address address_fe,
f.site_name as site_ne_name ,g.user_name as field_eng,h.option_label as visit_type_label,
				g.user_id as field_eng_id,f.site_address as address_ne,g.user_name,a.fop_hs_name,a.visit_date as visit_plan,h.option_label,i.region_name as region,a.last_visited_date as actual_date,
                                                            CASE
                                                            WHEN a.status = 0 then 'Not Confirmed'
                                                            WHEN a.status = 1 then 'Confirmed'
                                                            WHEN a.status = 2 then 'Declined'
                                                            WHEN a.status = 3 then 'Visited'
                                                            WHEN a.status = 4 then 'Unschedule'
                                                            WHEN a.status = 5 then 'Re-Schedule' end as desc_status,
                    ap.approval_order
                    from t_trx_schedule a
                    left join t_trx_po_link b on b.po_id = a.po_id
                    left join t_trx_po_xl c on c.po_id = a.po_id
                    left join t_mtr_site_link d on d.link_id_int = a.link_id
                    left JOIN t_mtr_site_xl e on d.site_id_fe = e.site_id
                    left JOIN t_mtr_site_xl f on d.site_id_ne = f.site_id
                    left join t_mtr_user g on g.user_id = a.pic_id
                    left join t_mtr_checklist_options h on h.option_id = a.visit_type::integer
                    left join t_mtr_region_xl i on i.region_id = a.region_id
                    LEFT JOIN (SELECT s.visit_id,
                            string_agg(coalesce(s.user_id, '') || '<b>(' ||
                                CASE WHEN s.acceptance_status = 1 THEN 'Pass' WHEN s.acceptance_status = 2 THEN 'Reject' ELSE 'Not Yet' END
                                || ')</b><br>', '' ORDER BY s.sum_appr_id) AS approval_order
                        FROM t_trx_schedule_result_approval_sum s GROUP BY s.visit_id) ap on ap.visit_id = a.visit_id
where a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = {$params}
 ORDER BY a.created_on DESC ")->result();

        echo "<table border='1' cellpadding='1' cellspacing='0'>
                <tr style='font-weight:bold;' >
                    <td> PO No </td>
                    <td> EWO No </td>
									  <td> Project Name </td>
										<td> L ink ID </td>
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
        foreach ($sqldata as $rows) {
            echo "<tr>
                    <td> " . $rows->po_no . " </td>
                    <td> " . $rows->ewo_no . "  </td>
										<td> " . $rows->po_description . "  </td>
                    <td> " . $rows->link_id . " </td>
                    <td> " . $rows->site_ne_name . "  </td>
                    <td> " . $rows->address_ne . "  </td>
                    <td> " . $rows->site_fe_name . "  </td>
                    <td> " . $rows->address_fe . "  </td>
                    <td> " . $rows->field_eng . "  </td>
                    <td> " . $rows->fop_hs_name . "  </td>
										<td> " . $rows->visit_type_label . " </td>
                    <td> " . $rows->region . " </td>
										<td> " . $rows->visit_plan . " </td>
										<td> " . $rows->actual_date . " </td>
										<td> " . $rows->desc_status . " </td>
										<td>";
            echo $rows->approval_order;

            echo "</td></tr>";
        }

        echo "</table>";
    }

    public function pro_upload_doc()
    {
        $dateinsert = date('Y-m-d H:i:s');
        $userinsert = $this->session->userdata("user_id");

        $datapos = array(
            'not_dec_id' => $this->input->post('not_dec_id'),
            'idvisit' => $this->input->post('idvisit'),
            'fdocs' => str_replace(" ", "_", $this->input->post('fdocs')),
            'category' => str_replace(" ", "_", $this->input->post('category')),
            'date_insert' => $dateinsert,
            'user_insert' => $userinsert
        );

        $get_curr_file = $this->db->query("select * from t_trx_docs_summary where visit_id = '" . $datapos['idvisit'] . "' ")->row();
        //cek kalo sama
        $file_curr = $get_curr_file->fdocs;
        $file_ups = $_FILES['docs']['name'];
        if ($file_curr == $file_ups) {
            unlink('file/upload/' . $file_curr);
        }

        $config['upload_path'] = "file/upload/";
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 1000;
        $config['remove_spaces'] = TRUE;

        $this->load->library('upload');
        $this->upload->initialize($config);
        if ($datapos['fdocs'] != '') {
            $this->upload->do_upload('docs');
        }


        $datatype = $this->input->post('category');

        if ($datatype == 'simf_instalasi') {

            //buat simf instalasi
            $config['upload_path'] = "E:/EATP/SIMF/";
            $config['allowed_types'] = 'xls|xlsx';
            $config['max_size'] = 2000;
            $config['remove_spaces'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);
            $this->upload->do_upload('docs');
        } else if ($datatype == 'simf_dismantle') {

            //buat simf dismantle
            $config['upload_path'] = "E:/EATP/SIMF/";
            $config['allowed_types'] = 'xls|xlsx';
            $config['max_size'] = 2000;
            $config['remove_spaces'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);
            $this->upload->do_upload('docs');
        } else if ($datatype == 'ims_antenna') {

            //buat ims antenna
            $config['upload_path'] = "E:/EATP/IMS/";
            $config['allowed_types'] = 'xls|xlsx';
            $config['max_size'] = 2000;
            $config['remove_spaces'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);
            $this->upload->do_upload('docs');
        } else if ($datatype == 'ims_idu') {

            //buat ims idu
            $config['upload_path'] = "E:/EATP/IMS/";
            $config['allowed_types'] = 'xls|xlsx';
            $config['max_size'] = 2000;
            $config['remove_spaces'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);
            $this->upload->do_upload('docs');
        } else if ($datatype == 'ims_odu') {

            //buat ims antenna
            $config['upload_path'] = "E:/EATP/IMS/";
            $config['allowed_types'] = 'xls|xlsx';
            $config['max_size'] = 2000;
            $config['remove_spaces'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);
            $this->upload->do_upload('docs');
        } else if ($datatype == 'ims_connection') {
            //buat ims connection
            $config['upload_path'] = "E:/EATP/IMS/";
            $config['allowed_types'] = 'xls|xlsx';
            $config['max_size'] = 2000;
            $config['remove_spaces'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);
            $this->upload->do_upload('docs');
        }


        /* //cek data
        $ceks = $this->db->query("select * from t_trx_docs_summary where visit_id = '$datapos[idvisit]' ");
        $gceks = $ceks->num_rows();
        if ($gceks > 0) {
            //update
            $store = $this->db->query("update t_trx_docs_summary set fdocs = '$datapos[fdocs]' where visit_id = '$datapos[idvisit]' ");
        } else {
            //insert
            $store = $this->db->query("insert into t_trx_docs_summary (visit_id,fdocs,date_insert,user_insert) VALUES ('$datapos[idvisit]','$datapos[fdocs]','$datapos[dateinsert]','$datapos[userinsert]')");
        }*/
        /* echo $gceks;
          exit(); */
        /*
insert into t_trx_docs_summary (visit_id,fdocs,user_insert,date_insert,user_update,date_update)
values ('22','dd.png','udin','2017-01-01','','');         */

        if ($file_curr == $file_ups) {
            $store = $this->db->query("update t_trx_docs_summary set user_update = '$datapos[user_insert]', date_update = '$datapos[date_insert]' where id = $get_curr_file->id");
        } else {
            $store = $this->db->query("insert into t_trx_docs_summary (visit_id,fdocs,user_insert,date_insert,category) VALUES ('$datapos[idvisit]','$datapos[fdocs]','$datapos[user_insert]','$datapos[date_insert]','$datapos[category]')");
        }
        if ($store) {
            redirect(base_url('visitsummary/upload_doc/' . $datapos[not_dec_id]));
        } else {
            redirect(base_url('visitsummary/upload_doc/' . $datapos[not_dec_id]));
        }
    }

    /* Public function show detail */

    public function del_upload_list()
    {
        $id = $this->uri->segment(3);
        $ids = $this->uri->segment(4);



        $this->db->where('id', $id);
        $sqlf = $this->db->get('t_trx_docs_summary')->row();

        if ($sqlf->fdocs != '') {
            unlink('file/upload/' . $sqlf->fdocs);
        }

        $this->db->where('id', $id);
        $sqldel = $this->db->delete('t_trx_docs_summary');

        if ($sqldel) {
            echo "<script language=javascript>
		  alert('Hapus Data Berhasil');
		  window.location='" . base_url('visitsummary/upload_doc/' . $ids) . "';
		  </script>";
        } else {
            echo "<script language=javascript>
		  alert('Perubahan Data Gagal');
		  window.location='" . base_url('visitsummary/upload_doc/' . $ids) . "';
		  </script>";
        }
    }
    public function detail($id)
    {

        $data['user_gac'] = $this->session->userdata("user_id");
        $this->_check_access();
        // Diagnosa kecepatan (khusus System Admin): buka detail dengan ?profiler=1
        if ($this->input->get('profiler') && strtoupper($this->session->userdata('ugrup')) == 'SYSTEM ADMIN') {
            $this->output->enable_profiler(TRUE);
        }
        $schedule_id = $this->core->decode($id);
        //echo $schedule_id;
        //exit();
        //start edited by Hadi 2018-03-27
        $this->session->set_userdata(array('firstUserLayer' => $this->summary_model_forced->getApprovalOrderFirst($schedule_id)));
        //end
        $user_id = $this->session->userdata("user_id");

        //get data history Visit
        $this->benchmark->mark('detail_tracking_start');
        $data['h_visit'] = $this->db->query("select a.*,c.user_name,d.site_code from t_trx_tracking a
left join t_trx_schedule b on b.visit_id = a.jc_id
left join t_mtr_user c on c.user_id = b.pic_id
left join t_mtr_site d on d.site_id = a.jc_id where a.jc_id = '$schedule_id' order by updated_on DESC LIMIT 1 ")->result();
        $this->benchmark->mark('detail_tracking_end');

        //var_dump($data['h_visit']);
        //exit();

        $data['content_id'] = 'detail';
        $data['title'] = 'Summary Visit ';
        $data['siteinfo'] = $this->summary_model->getSchedule($schedule_id); /* 0 as Default Schedule */

        //var_dump($data['siteinfo']);
        $data['user_id'] = $user_id;
        $data['title'] = 'Summary Visit Site Link ' . $data['siteinfo']->site_link;
        $data['action_url'] = base_url("/atpdesktop/saveAnswer");
        /* List Users for Approval */
        $approval_user = $this->summary_model->getApprovalOrder($schedule_id);

        $is_enable_approval = "disabled";
        $flag = $this->summary_model->is_eligible_approval($schedule_id, $user_id);
        // if ($flag) {
        //     $flag = $this->summary_model->is_user_before_approval_done($schedule_id, $user_id);
        //     if ($flag) {
        //         $is_enable_approval = "";
        //         //echo "finished";
        //     } else {
        //         //echo "not yet";
        //     }
        // }
        if ($flag) {
            $sql = "SELECT a1.order_no from t_trx_schedule_result_approval a1
				where a1.visit_id={$schedule_id} 
                and a1.user_id = '{$user_id}'
				and a1.visit_id = {$schedule_id}
				order by a1.order_no DESC
				LIMIT 1";
            //echo $sql."<br/>";
            $no_order_apr = $this->db->query($sql)->row();

            if ($no_order_apr->order_no > 3) {
                $flag = $this->summary_model->is_user_layer_3_approval_done($schedule_id, $user_id);
                if ($flag) {
                    $is_enable_approval = "";
                    //echo "finished";
                } else {
                    //echo "not yet";
                }
            } else {
                $flag = $this->summary_model->is_user_before_approval_done($schedule_id, $user_id);

                if ($flag) {
                    $is_enable_approval = "";
                    //echo "finished";
                } else {
                    //echo "not yet";
                }
            }
        }

        /* start edit by hadi 2019-07-24 */
        $this->benchmark->mark('detail_gac_mysql_start');
        $db2 = $this->load->database('mysql', TRUE);
        $sql = "SELECT purch_doc,item,material_code,short_text,po_qty,region,ewo,link FROM tbl_gac_data WHERE link='" . $data['siteinfo']->site_link . "'";
        $res = $db2->query($sql)->result();
        $data['data_gac'] = $res;
        $this->benchmark->mark('detail_gac_mysql_end');
        /*foreach ($res as $row) {
            echo $row->purch_doc . '<br/>';
            echo $row->item . '<br/>';
            echo $row->material_code . '<br/>';
            echo $row->short_text . '<br/>';
            echo $row->po_qty . '<br/>';
            echo $row->region . '<br/>';
            echo $row->ewo . '<br/>';
            echo $row->link . '<br/>';
			//$data['_purch_doc'] = $row->purch_doc;
        }*/
        /* end edit by hadi 2019-07-24 */

        // die;
        /* List of Checklist */
        $this->benchmark->mark('detail_checklist_start');
        $visitinfo = $this->summary_model->getDetailSummary($schedule_id);
        $this->benchmark->mark('detail_checklist_end');

        $properties = $this->summary_model->siteProperties();
        /* Get Data Approval Summary */
        $user_id = $this->session->userdata('user_id');
        $approval_summary = $this->summary_model->getApprovalSummary($user_id, $schedule_id);
        $data["approval_summary"] = $approval_summary;
        /* Get Data Pending Item with this visit_id */
        $pending_items = $this->summary_model->getPendingItem($schedule_id);
        $data["pending_items"] = $pending_items;
        /* Render data Approval List to View */
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
        $x = 0;
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
        foreach ($siteProperties as $a) {
            $new[$a['parent_id']][] = $a;
        }
        $data['checklistsgrouping'] = $this->treeGenerator($new, $new[598]);
        // print_r($data);die();
        /* End List of Checklist */

        $this->load->view('template/smashing', $data);
    }

    public function get_appr_list()
    {
        $datapos = $this->input->get('datapos');
        $approval_ecrf = $this->db->from('t_trx_ecrf_approval')->where('visit_id', $datapos)->get()->row();
        if (!empty($approval_ecrf)) {
            $text_approval = '<h4 align="center">  ECRF Approval </h4>
            <table class="table table-bordered table-condensed" width="800" style="margin-bottom:8px">
                    <tbody>
                        <tr>
                        <td> Visit ID </td>
                        <td>Status </td>
                        <td> Date  </td>
                        <td>Remarks </td>
                        </tr>
                        <tr>
                        <td>
                        ' . $approval_ecrf->visit_id . '
                        </td>
                        <td>
                        ' . $approval_ecrf->status_approve . '
                        </td>
                        <td>
                        ' . date('Y-m-d H:i', strtotime($approval_ecrf->created_at)) . '
                        </td>
                        <td>
                        ' . $approval_ecrf->remarks . '
                        </td>
                        </tr>
                        </tbody>
                    </table>';
        } else {
            $text_approval = '<h4 align="center">  ECRF Approval </h4>
            <table class="table table-bordered table-condensed" width="800" style="margin-bottom:8px">
                    <tbody>
                        <tr>
                        <td> Visit ID </td>
                        <td>Status </td>
                        <td> Date  </td>
                        <td>Remarks </td>
                        </tr>
                        <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        </tr>
                        </tbody>
                    </table>';
        }
        $sql = "SELECT a.user_id, a.updated_on, a.remark,
		CASE
			WHEN a.status = 1 THEN 'Pass'
			WHEN a.status = 0 THEN 'Reject'
			ELSE 'Not Yet'
		END as \"desc_status\"
		FROM t_trx_schedule_result_approval a
		--left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id AND b.user_id = a.user_id
		where a.visit_id={$datapos} order by a.order_no ASC ";
        $res = $this->db->query($sql)->result();

        echo '<div class="widget"><div class="widget-body"><h3 align="center">  Approval List </h3>
     <table class="table table-bordered table-condensed" width="800" style="margin-bottom:8px">
              <tbody>
                <tr>
                <td> Username </td>
                <td> Date </td>
                <td> Status </td>
                <td> Remark </td>
                </tr>';

        $qa = $res[0];
        foreach ($res as $key => $row) {
            $return = '
            <tr>
            <td>' . $row->user_id . ' </td>
            <td>' . $row->updated_on . ' </td>
            <td>' . $row->desc_status . ' </td>
            <td>' . $row->remark . ' </td>

            </tr>';

            if ($key == 1 && date('Y-m-d H:i', strtotime($row->updated_on)) == date('Y-m-d H:i', strtotime($qa->updated_on))) {
                $return = '
                        <tr>
                        <td>' . $row->user_id . ' </td>
                        <td> </td>
                        <td> Not Yet </td>
                        <td> </td>
                        </tr>';
            }

            echo $return;
        }
        echo '
            </tbody>
            </table>
            ';
        echo $text_approval . '</div></div>';
        //var_dump($res);
        //return $res->result();
    }

    public function getcheck()
    {
        $data1 = $this->input->get('data1');
        $schedule_id = $this->core->decode($data1);
        $data2 = $this->input->get('data2');
        $data3 = $this->input->get('data3');
        //echo $data3;
        //exit();

        if ($data3 != 'Not Yet') {
            $sqlap = $this->db->query("select a.*,b.checklist_name from t_trx_checklist_result_approval a
inner join t_mtr_checklist b on b.checklist_id = a.checklist_id
where a.visit_id = '$schedule_id' and a.status = 1")->result();

            $sqlrj = $this->db->query("select a.*,b.checklist_name from t_trx_checklist_result_approval a
inner join t_mtr_checklist b on b.checklist_id = a.checklist_id
where a.visit_id = '$schedule_id' and a.status = 0")->result();

            echo "
<h4 align='center'> LIST APPROVE </h4>
<br>

<table border='1' cellpadding='3' cellspacing='0'>
    <tr>
        <td> Module </td>
        <td> Approved By </td>
        <td> Approved On </td>
    </tr>
    ";
            foreach ($sqlap as $rowap) {

                echo "
    <tr>
        <td> " . $rowap->checklist_name . " </td>
        <td> " . $rowap->created_by . " </td>
        <td> " . $rowap->created_on . " </td>
    </tr>
    ";
            }
            echo "
</table>
<br>
&nbsp;

<h4 align='center'> LIST REJECT </h4>
<br>

<table border='1' cellpadding='3' cellspacing='0'>
    <tr>
        <td> Module </td>
        <td> Approved By </td>
        <td> Approved On </td>
    </tr>
    ";
            foreach ($sqlrj as $rowrj) {
                echo "
    <tr>
        <td> " . $rowrj->checklist_name . " </td>
        <td> " . $rowrj->created_by . " </td>
        <td> " . $rowrj->created_on . " </td>
    </tr>
    ";
            }
            echo "
</table>
<br>
&nbsp;
";
        } else {
            echo "No Data!";
        }

        /* if($data3 == 'Pass'){
          $sql = $this->db->query("select a.*,b.checklist_name from t_trx_checklist_result_approval a
          inner join t_mtr_checklist b on b.checklist_id = a.checklist_id
          where a.visit_id = '$schedule_id' and a.status = 1")->row();
          var_dump($sql);
          //echo "yang di aprove : ".$sql->checklist_name;
          //echo "lu di aprove";
          }else if($data3 == 'Reject'){
          $sql = $this->db->query("select a.*,b.checklist_name from t_trx_checklist_result_approval a
          inner join t_mtr_checklist b on b.checklist_id = a.checklist_id
          where a.visit_id = '$schedule_id' and a.status = 0")->row();
          var_dump($sql);
          //echo "yang di reject : ".$sql->checklist_name;
          //echo "lu di reject";
          }else{
          var_dump(0);
          //echo "Belum ada aproval dari PIC Aproval!";
          }
          //echo "your data is : " .$data1. " " .$data2. " ".$data3;
          /*select a.*,b.checklist_name from t_trx_checklist_result_approval a
          inner join t_mtr_checklist b on b.checklist_id = a.checklist_id
          where a.visit_id = $schedule_id and b.status = '$data3'

         */
    }

    /* Public function show detailreject */

    public function detailreject($id)
    {
        $this->_check_access();
        $schedule_id = $this->core->decode($id);

        $user_id = $this->session->userdata("user_id");
        // if($flag) echo "True";
        // else echo "False";
        // die;

        $data['content_id'] = 'detail';
        $data['title'] = 'Summary Visit ';
        $data['siteinfo'] = $this->summary_model->getSchedule($schedule_id); /* 0 as Default Schedule */

        $data['title'] = 'Summary Visit Site Link ' . $data['siteinfo']->site_link;
        $data['action_url'] = base_url("/atpdesktop/saveAnswer");
        /* List Users for Approval */
        $approval_user = $this->summary_model->getApprovalOrder($schedule_id);

        $is_enable_approval = "disabled";
        $flag = $this->summary_model->is_eligible_approval($schedule_id, $user_id);
        if ($flag) {
            $flag = $this->summary_model->is_user_before_approval_done($schedule_id, $user_id);
            if ($flag) {
                $is_enable_approval = "";
                //echo "finished";
            } else {
                //echo "not yet";
            }
        }
        // die;
        /* List of Checklist */
        $visitinfo = $this->summary_model->getDetailSummary($schedule_id);

        $properties = $this->summary_model->siteProperties();
        /* Get Data Approval Summary */
        $user_id = $this->session->userdata('user_id');
        $approval_summary = $this->summary_model->getApprovalSummary($user_id, $schedule_id);
        $data["approval_summary"] = $approval_summary;
        /* Get Data Pending Item with this visit_id */
        $pending_items = $this->summary_model->getPendingItem($schedule_id);
        $data["pending_items"] = $pending_items;
        /* Render data Approval List to View */
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
        $x = 0;
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
        foreach ($siteProperties as $a) {
            $new[$a['parent_id']][] = $a;
        }
        $data['checklistsgrouping'] = $this->treeGenerator($new, $new[598]);
        /* End List of Checklist */

        $this->load->view('template/smashing', $data);
    }

    /* Function that get Approval massed Data */

    public function savemassedapproval()
    {
        // var_dump($this->input->post("fop_selfie"));die();
        log_message('DEBUG', "Start savemassedapproval : " . date('Y-m-d H:i:s'));
        $this->load->library('email');
        $user_id = $this->session->userdata('user_id');
        $now = date('Y-m-d');
        $approval_checklist = array_map('intval', json_decode($this->input->post("approval_list")));
        $rejected_checklist = array_map('intval', json_decode($this->input->post("rejected_list")));
        $remark = $this->input->post("remark");
        $visit_id = $this->input->post("visit_id");
        $accpt_status = $this->input->post("acceptance");

        $total_approval = 0;
        $total_rejected = 0;
        if ($approval_checklist) {
            /* Save Approval Checklist Item */
            foreach ($approval_checklist as $item) {
                $data = new stdClass;
                $data->visit_id = $visit_id;
                $data->checklist_id = $item;
                $data->status = (int) 1; /* Approve Answer Checklist */
                $data->created_on = date('Y-m-d H:i:s');
                $data->remark = $remark;
                $data->created_by = $this->session->userdata('user_id');
                //$this->summary_model->saveApprovalAnswer($data, true);
                $total_approval++;
            }
            $this->summary_model->saveApprovalAnswer($approval_checklist, $data, true);
        }

        if ($rejected_checklist) {
            /* Save Rejected Checklist Item */
            foreach ($rejected_checklist as $item) {
                $data = new stdClass;
                $data->visit_id = $visit_id;
                $data->checklist_id = $item;
                $data->status = (int) 0; /* Reject Answer Checklist */
                $data->created_on = date('Y-m-d H:i:s');
                $data->remark = $remark;
                $data->created_by = $this->session->userdata('user_id');
                //$this->summary_model->saveApprovalAnswer($data, true);
                $total_rejected++;
            }
            $this->summary_model->saveApprovalAnswer($rejected_checklist, $data, true);
        }

        $pending_items = array_map('intval', json_decode($this->input->post("pending_items")));
        $pending_completion_date = array_map('intval', json_decode($this->input->post("pending_completiondate")));
        $pending_pending_pic = array_map('intval', json_decode($this->input->post("pending_pic")));
        $pending_contact = array_map('intval', json_decode($this->input->post("pending_contact")));
        $pending_ids = array_map('intval', json_decode($this->input->post("pending_ids")));

        if (
            count($pending_items) == count($pending_completion_date) &&
            count($pending_pending_pic) == count($pending_contact) &&
            count($pending_items) == count($pending_contact) && count($pending_items) > 0 && $pending_items[0] != ""
        ) {
            /* save to t_trx_schedule_pending_item if exist */
            for ($i = 0; $i < count($pending_items); $i++) {
                $dt_pending = new stdClass;
                $dt_pending->item_no = (int) 1;
                $dt_pending->item_descr = $pending_items[$i];
                $dt_pending->target_completion = $pending_completion_date[$i];
                $dt_pending->pic = $pending_pending_pic[$i];
                $dt_pending->contact_no = $pending_contact[$i];
                $dt_pending->visit_id = $visit_id;

                if ($pending_ids[$i] == "none") {
                    $dt_pending->created_on = date('Y-m-d H:i:s');
                    $dt_pending->created_by = $this->session->userdata('user_id');
                    $this->summary_model->savePendingItem($dt_pending);
                } else {
                    $dt_pending->updated_on = date('Y-m-d H:i:s');
                    $dt_pending->updated_by = $this->session->userdata('user_id');
                    $pending_id = $this->core->decode($pending_ids[$i]);
                    $this->summary_model->updatePendingItem($dt_pending, $pending_id);
                }
            }
        }
        // else{
        // 	$json = array('status' => 'error', 'message' => 'Missing Form in pending item');
        // 	echo json_encode($json);
        // 	exit;
        // }

        $final_status_approval = $this->summary_model->is_approved_all($visit_id, $this->session->userdata('user_id'));

        //echo $final_status_approval."-".$visit_id;die;
        /* update t_trx_schedule_result_approval */
        $dt_res_approval = new stdClass;
        // $dt_res_approval->status = $final_status_approval;
        $dt_res_approval->status = $accpt_status;
        $dt_res_approval->qty_approved = $total_approval;
        $dt_res_approval->qty_rejected = $total_rejected;
        $dt_res_approval->remark = $remark;
        $dt_res_approval->updated_on = date('Y-m-d H:i:s');
        $dt_res_approval->updated_by = $this->session->userdata('user_id');
        $this->summary_model->updateResultApproval($dt_res_approval, $visit_id, $this->session->userdata('user_id'));
        /* save  acceptance status t_trx_schedule_result_approval_sum */

        /**INI NIH*/
        $dt_sum_approval = new stdClass;
        $dt_sum_approval->visit_id = $visit_id;
        $dt_sum_approval->acceptance_status = $accpt_status;
        $dt_sum_approval->remark = $remark;
        $dt_sum_approval->user_id = $this->session->userdata('user_id');
        $dt_sum_approval->created_on = date('Y-m-d H:i:s');
        $dt_sum_approval->created_by = $this->session->userdata('user_id');
        $dt_sum_approval->updated_on = date('Y-m-d H:i:s');
        $dt_sum_approval->updated_by = $this->session->userdata('user_id');
        $this->summary_model->saveApprovalSummary($dt_sum_approval);

        /* Check if Current user is the Last User to Approve and then set progress po_link with 5 closed */
        $flag_last_approve = $this->summary_model->is_last_approval($this->session->userdata('user_id'), $visit_id);
        if ($flag_last_approve == 1 && $final_status_approval == 1) { /* This PO LInk should be Closed */

            $rs_sch = $this->reference_model->getSchedule($visit_id);
            if ($rs_sch) {
                $data = new stdClass;
                $data->progress_status = 5; /* Closed */
                $this->summary_model->updatePOLIProgressStatus($rs_sch->po_id, $rs_sch->link_id, $data);
            }
        }
        /* Return Result */

        //get fe
        $sql_fe_mail = $this->db->query("select a.*,b.email as email_fe from t_trx_schedule a
            left join t_mtr_user b on b.user_id = a.pic_id
            where a.visit_id = '" . $visit_id . "'")->row();

        $sql_apr_mail = $this->db->query("select a.*,b.user_id as aprovalnya,b.order_no ,b.status as approval_status, c.email as email_aprrovalnya from t_trx_schedule a
            left join t_trx_schedule_result_approval b on b.visit_id = a.visit_id
            left join t_mtr_user c on c.user_id = b.user_id
            where a.visit_id = '" . $visit_id . "' ORDER BY b.order_no ASC")->result();

        $schedule = $this->summary_model->getScheduleData($visit_id);
        $schedule = array_merge($schedule, array('approved_by' => $this->session->userdata('user_id'), 'rejected_by' => $this->session->userdata('user_id')));

        $mail_template = array();
        $mail_template['apr'] = $this->db->get_where('t_ref_mail_notif', array('template_name' => 'APPRSCHED', 'is_active' => 1))->row();
        $mail_template['rej'] = $this->db->get_where('t_ref_mail_notif', array('template_name' => 'REJSCHED', 'is_active' => 1))->row();

        $mail_content_apr = self::inject_data($schedule, $mail_template['apr']->parameter, $mail_template['apr']->content);
        $mail_content_rej = self::inject_data($schedule, $mail_template['rej']->parameter, $mail_template['rej']->content);


        //send mail to FE and aproval if pass
        if ($accpt_status == 1) { // aprove
            $stat = "Approved";

            foreach ($sql_apr_mail as $rs_apr_yes) {
                if ($rs_apr_yes->approval_status == null) {
                    self::push_notif_email(
                        array(
                            'subject'            => $mail_template['apr']->subject,
                            'content'            => $mail_content_apr,
                            'recipient_address'    => $rs_apr_yes->email_aprrovalnya
                        )
                    );
                    $smailfe = '-';
                    $smailat = 'mail apr sent to apr team';
                    $json = array('status' => 'success', 'message' => 'Data Approval is successfully inserted ', 'acceptance' => $stat, 'to_fe' => $smailfe, 'to_apr_team' => $smailat);
                    echo json_encode($json);
                    exit;
                }
            }

            foreach ($sql_apr_mail as $rs_apr_yes) {
                self::push_notif_email(
                    array(
                        'subject'            => $mail_template['apr']->subject,
                        'content'            => $mail_content_apr,
                        'recipient_address'    => $rs_apr_yes->email_aprrovalnya
                    )
                );
            }
            self::push_notif_email(
                array(
                    'subject'            => $mail_template['apr']->subject,
                    'content'            => $mail_content_apr,
                    'recipient_address'    => $sql_fe_mail->email_fe
                )
            );

            $smailfe = 'mail apr sent to fe';
            $smailat = 'mail apr sent to apr team';
            $json = array('status' => 'success', 'message' => 'Data Approval is successfully inserted ', 'acceptance' => $stat, 'to_fe' => $smailfe, 'to_apr_team' => $smailat);
            echo json_encode($json);
        }

        if ($accpt_status == 2) {
            $stat = "Rejected";

            // //Field Engineering
            self::push_notif_email(
                array(
                    'subject'            => $mail_template['rej']->subject,
                    'content'            => $mail_content_rej,
                    'recipient_address'    => $sql_fe_mail->email_fe
                )
            );

            $smailfe = 'mail rjt sent to fe';
            $smailat = '-';
            $json = array('status' => 'success', 'message' => 'Data Approval is successfully inserted ', 'acceptance' => $stat, 'to_fe' => $smailfe, 'to_apr_team' => $smailat);
            echo json_encode($json);
        }
        log_message('DEBUG', "End savemassedapproval : " . date('Y-m-d H:i:s'));
    }

    //start edited by Hadi 2018-03-19
    public function savemassedapprovalforcedtofop()
    {
        log_message('DEBUG', "Start savemassedapprovalforcedtofop : " . date('Y-m-d H:i:s'));
        $this->load->library('email');
        //$user_id = $this->session->userdata('user_id');
        $now = date('Y-m-d');
        $approval_checklist = $this->input->post("approval_list");
        $rejected_checklist = $this->input->post("rejected_list");
        $remark = $this->input->post("remark");
        $visit_id = $this->input->post("visit_id");
        $accpt_status = $this->input->post("acceptance");

        $user_id = $this->summary_model_forced->getApprovalOrderForce($visit_id);

        $total_approval = 0;
        $total_rejected = 0;
        if ($approval_checklist) {
            /* Save Approval Checklist Item */
            foreach ($approval_checklist as $item) {
                $data = new stdClass;
                $data->visit_id = $visit_id;
                $data->checklist_id = $item;
                $data->status = 1; /* Approve Answer Checklist */
                $data->created_on = date('Y-m-d H:i:s');
                $data->remark = $remark;
                //$data->created_by = $this->session->userdata('user_id');
                $data->created_by = $user_id;
                //$this->summary_model_forced->saveApprovalAnswer($user_id, $data, true);
                $total_approval++;
            }
            $this->summary_model_forced->saveApprovalAnswer($approval_checklist, $user_id, $data, true);
        }

        if ($rejected_checklist) {
            /* Save Rejected Checklist Item */
            foreach ($rejected_checklist as $item) {
                $data = new stdClass;
                $data->visit_id = $visit_id;
                $data->checklist_id = $item;
                $data->status = 0; /* Reject Answer Checklist */
                $data->created_on = date('Y-m-d H:i:s');
                $data->remark = $remark;
                //$data->created_by = $this->session->userdata('user_id');
                $data->created_by = $user_id;
                //$this->summary_model_forced->saveApprovalAnswer($user_id, $data, true);
                $total_rejected++;
            }
            $this->summary_model_forced->saveApprovalAnswer($rejected_checklist, $user_id, $data, true);
        }

        $pending_items = $this->input->post("pending_items");
        $pending_completion_date = $this->input->post("pending_completiondate");
        $pending_pending_pic = $this->input->post("pending_pic");
        $pending_contact = $this->input->post("pending_contact");
        $pending_ids = $this->input->post("pending_ids");

        if (
            count($pending_items) == count($pending_completion_date) &&
            count($pending_pending_pic) == count($pending_contact) &&
            count($pending_items) == count($pending_contact) && count($pending_items) > 0 && $pending_items[0] != ""
        ) {
            /* save to t_trx_schedule_pending_item if exist */
            for ($i = 0; $i < count($pending_items); $i++) {
                $dt_pending = new stdClass;
                $dt_pending->item_no = 1;
                $dt_pending->item_descr = $pending_items[$i];
                $dt_pending->target_completion = $pending_completion_date[$i];
                $dt_pending->pic = $pending_pending_pic[$i];
                $dt_pending->contact_no = $pending_contact[$i];
                $dt_pending->visit_id = $visit_id;

                if ($pending_ids[$i] == "none") {
                    $dt_pending->created_on = date('Y-m-d H:i:s');
                    //$dt_pending->created_by = $this->session->userdata('user_id');
                    $dt_pending->created_by = $user_id;
                    $this->summary_model_forced->savePendingItem($dt_pending);
                } else {
                    $dt_pending->updated_on = date('Y-m-d H:i:s');
                    //$dt_pending->updated_by = $this->session->userdata('user_id');
                    $dt_pending->updated_by = $user_id;
                    $pending_id = $this->core->decode($pending_ids[$i]);
                    $this->summary_model_forced->updatePendingItem($dt_pending, $pending_id);
                }
            }
        }
        // else{
        // 	$json = array('status' => 'error', 'message' => 'Missing Form in pending item');
        // 	echo json_encode($json);
        // 	exit;
        // }

        //$final_status_approval = $this->summary_model->is_approved_all($visit_id, $this->session->userdata('user_id'));
        $final_status_approval = $this->summary_model_forced->is_approved_all($visit_id, $user_id);

        //echo $final_status_approval."-".$visit_id;die;
        /* update t_trx_schedule_result_approval */
        $dt_res_approval = new stdClass;
        // $dt_res_approval->status = $final_status_approval;
        $dt_res_approval->status = $accpt_status;
        $dt_res_approval->qty_approved = $total_approval;
        $dt_res_approval->qty_rejected = $total_rejected;
        $dt_res_approval->remark = $remark;
        $dt_res_approval->updated_on = date('Y-m-d H:i:s');
        //$dt_res_approval->updated_by = $this->session->userdata('user_id');
        $dt_res_approval->updated_by = $user_id;
        //$this->summary_model->updateResultApproval($dt_res_approval, $visit_id, $this->session->userdata('user_id'));
        $this->summary_model_forced->updateResultApproval($dt_res_approval, $visit_id, $user_id);
        /* save  acceptance status t_trx_schedule_result_approval_sum */

        /**INI NIH*/
        $dt_sum_approval = new stdClass;
        $dt_sum_approval->visit_id = $visit_id;
        $dt_sum_approval->acceptance_status = $accpt_status;
        $dt_sum_approval->remark = $remark;
        //$dt_sum_approval->user_id = $this->session->userdata('user_id');
        $dt_sum_approval->user_id = $user_id;
        $dt_sum_approval->created_on = date('Y-m-d H:i:s');
        //$dt_sum_approval->created_by = $this->session->userdata('user_id');
        $dt_sum_approval->created_by = $user_id;
        $dt_sum_approval->updated_on = date('Y-m-d H:i:s');
        //$dt_sum_approval->updated_by = $this->session->userdata('user_id');
        $dt_sum_approval->updated_by = $user_id;
        $this->summary_model_forced->saveApprovalSummary($dt_sum_approval);

        /* Check if Current user is the Last User to Approve and then set progress po_link with 5 closed */
        //$flag_last_approve = $this->summary_model->is_last_approval($this->session->userdata('user_id'), $visit_id);
        $flag_last_approve = $this->summary_model_forced->is_last_approval($user_id, $visit_id);
        if ($flag_last_approve == 1 && $final_status_approval == 1) { /* This PO LInk should be Closed */

            $rs_sch = $this->reference_model->getSchedule($visit_id);
            if ($rs_sch) {
                $data = new stdClass;
                $data->progress_status = 5; /* Closed */
                $this->summary_model_forced->updatePOLIProgressStatus($rs_sch->po_id, $rs_sch->link_id, $data);
            }
        }
        /* Return Result */

        //get fe
        $sql_fe_mail = $this->db->query("select a.*,b.email as email_fe from t_trx_schedule a
            left join t_mtr_user b on b.user_id = a.pic_id
            where a.visit_id = '" . $visit_id . "'")->row();

        $sql_apr_mail = $this->db->query("select a.*,b.user_id as aprovalnya,b.order_no ,b.status as approval_status, c.email as email_aprrovalnya from t_trx_schedule a
            left join t_trx_schedule_result_approval b on b.visit_id = a.visit_id
            left join t_mtr_user c on c.user_id = b.user_id
            where a.visit_id = '" . $visit_id . "' ORDER BY b.order_no ASC")->result();

        $schedule = $this->summary_model_forced->getScheduleData($visit_id);
        //$schedule = array_merge($schedule, array('approved_by'=>$this->session->userdata('user_id'), 'rejected_by'=>$this->session->userdata('user_id')));
        $schedule = array_merge($schedule, array('approved_by' => $user_id, 'rejected_by' => $user_id));
        $mail_template = array();
        $mail_template['apr'] = $this->db->get_where('t_ref_mail_notif', array('template_name' => 'APPRSCHED', 'is_active' => 1))->row();
        $mail_template['rej'] = $this->db->get_where('t_ref_mail_notif', array('template_name' => 'REJSCHED', 'is_active' => 1))->row();

        $mail_content_apr = self::inject_data($schedule, $mail_template['apr']->parameter, $mail_template['apr']->content);
        $mail_content_rej = self::inject_data($schedule, $mail_template['rej']->parameter, $mail_template['rej']->content);


        //send mail to FE and aproval if pass
        if ($accpt_status == 1) { // aprove
            $stat = "Approved";

            foreach ($sql_apr_mail as $rs_apr_yes) {
                if ($rs_apr_yes->approval_status == null) {
                    self::push_notif_email(
                        array(
                            'subject'            => $mail_template['apr']->subject,
                            'content'            => $mail_content_apr,
                            'recipient_address'    => $rs_apr_yes->email_aprrovalnya
                        )
                    );
                    $smailfe = '-';
                    $smailat = 'mail apr sent to apr team';
                    $json = array('status' => 'success', 'message' => 'Data Approval is successfully inserted ', 'acceptance' => $stat, 'to_fe' => $smailfe, 'to_apr_team' => $smailat);
                    echo json_encode($json);
                    exit;
                }
            }

            foreach ($sql_apr_mail as $rs_apr_yes) {
                self::push_notif_email(
                    array(
                        'subject'            => $mail_template['apr']->subject,
                        'content'            => $mail_content_apr,
                        'recipient_address'    => $rs_apr_yes->email_aprrovalnya
                    )
                );
            }
            self::push_notif_email(
                array(
                    'subject'            => $mail_template['apr']->subject,
                    'content'            => $mail_content_apr,
                    'recipient_address'    => $sql_fe_mail->email_fe
                )
            );

            $smailfe = 'mail apr sent to fe';
            $smailat = 'mail apr sent to apr team';
            $json = array('status' => 'success', 'message' => 'Data Approval is successfully inserted ', 'acceptance' => $stat, 'to_fe' => $smailfe, 'to_apr_team' => $smailat);
            echo json_encode($json);
        }

        if ($accpt_status == 2) {
            $stat = "Rejected";

            // //Field Engineering
            self::push_notif_email(
                array(
                    'subject'            => $mail_template['rej']->subject,
                    'content'            => $mail_content_rej,
                    'recipient_address'    => $sql_fe_mail->email_fe
                )
            );

            $smailfe = 'mail rjt sent to fe';
            $smailat = '-';
            $json = array('status' => 'success', 'message' => 'Data Approval is successfully inserted ', 'acceptance' => $stat, 'to_fe' => $smailfe, 'to_apr_team' => $smailat);
            echo json_encode($json);
        }
        log_message('DEBUG', "End savemassedapprovalforcedtofop : " . date('Y-m-d H:i:s'));
    }
    //end

    /* Function return Image of Checklist */

    public function getimage()
    {
        $visit_id = $this->input->post("visit_id");
        $chk_id = $this->input->post("checklist_id");

        $res = $this->summary_model->getImageChecklist($visit_id, $chk_id);
        if ($res) {
            $json = array('status' => 'success', 'message' => 'Image is exist', "src" => $res->photo_name);
            echo json_encode($json);
            exit;
        } else {
            $json = array('status' => 'error', 'message' => 'Image is not exist');
            echo json_encode($json);
            exit;
        }
    }

    function treeGenerator(&$list, $parent)
    {
        $tree = array();
        foreach ($parent as $k => $l) {
            if (isset($list[$l['group_id']])) {
                $l['sub_group'] = $this->treeGenerator($list, $list[$l['group_id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    private function generate_view($data, $answer)
    {
        return "<a href='#'>" . $data["checklist_id"] . "</a>";
    }

    private function _check_access()
    {
        if (!$this->session->userdata('is_login')) {
            if ($this->core->is_ajax()) {
                echo json_encode(array(
                    'loggedin' => FALSE,
                    'message' => 'You are not logged in. You will be redirected to login page.',
                    'redirect_url' => site_url('login')
                ));
                exit;
            } else {
                redirect('login');
            }
        }
    }

    public function view($view)
    {
        $this->load->view($view);
    }

    public function test()
    {
        $rs = $this->summary_model->getPendingItem(8);
        echo json_encode($rs);
    }

    public function deletepending($id)
    {
        $pending_id = $this->core->decode($id);
        $data = new stdClass;
        $data->updated_on = date('Y-m-d H:i:s');
        $data->updated_by = $this->session->userdata('user_id');
        $data->is_deleted = 1;
        $this->summary_model->delete($pending_id, $data);
        $json = array('status' => 'success', 'message' => 'Pending items is removed');
        echo json_encode($json);
    }

    public function test_distance()
    {

        $lat1 = -6.290221;
        $long1 = 106.843988;
        $lat2 = -6.3595241;
        $long2 = 106.7341521;

        echo $this->getDistanceFromLatLonInMeter($lat1, $long1, $lat2, $long2);
    }

    function getDistanceFromLatLonInMeter($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; /* Radius Earth in Km */
        $dLat = deg2rad($lat2 - $lat1);  // deg2rad below
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c; // Distance in km
        $res = $d * 1000; /* convert to m */
        $distance = number_format((float) $res, 2, ',', '.') . " m";
        return $distance;
    }

    private function push_notif_email($data = array())
    {
        $header = array(
            'email_type_id'                => 1,
            'email_date'                => date('Y-m-d'),
            'email_reply_to_address'    => 'eatp-alita@alita.id',
            'email_subject'                => $data['subject'],
            'email_content'                => $data['content'],
            'foreign_id'                => 1,
            'created_by'                => $this->session->userdata('user_name'),
            'created_on'                => date('Y-m-d H:i:s')
        );
        $this->db->insert('t_msg_email_header', $header);
        $header_id = $this->db->insert_id();

        $detail = array(
            'email_id'            => $header_id,
            'recipient_type'    => 'to',
            'recipient_address'    => $data['recipient_address'],
            'created_by'        => $this->session->userdata('user_name'),
            'created_on'        => date('Y-m-d H:i:s')
        );
        $this->db->insert('t_msg_email_detail', $detail);
    }

    private function inject_data($data = array(), $params = '', $template = '')
    {

        $param = str_replace(array("[", "]"), "", $params);
        $param = explode(",", $param);
        $params = explode(",", $params);

        $values = array();
        foreach ($param as $x => $value) {
            if (array_key_exists($value, $data)) {
                $values[$x] = $data[$value];
            } else {
                $value[$x] = "-";
            }
        }

        return str_replace($params, $values, $template);
    }

    public function fop_attach()
    {
        $id = $this->uri->segment(3);
        $data['id'] = $id;

        $this->_check_access();

        $data['content_id'] = 'fop_attach';
        $this->load->view('template/smashing', $data);
    }

    public function pro_fop_attach()
    {
        $id = $this->uri->segment(3);
        $getid = $this->core->decode($id);
        $removedUserId = str_replace(".", "", $this->session->userdata('user_id'));

        $this->_check_access();

        if ($this->input->post('is_upload_image') == 'uploaded') {
            $path = 'file/upload/fop/' . date('Y/m/d');
            $imsPath = date('Y/m/d');

            if (!is_dir(FCPATH . $path)) {
                mkdir(FCPATH . $path, 0777, TRUE);
            }

            $fileName = 'FOP_' . $removedUserId . '_visitId_' . $getid . '_' . date('Ymd_His') . '.jpg';

            $config['upload_path']          = $path;
            $config['allowed_types']        = 'jpg';
            $config['file_name']            = $fileName;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image_fop')) {
                // redirect
                redirect(base_url('/visitsummary/fop_attach') . '/' . $id);
            } else {
                $photoPath = $path . '/' . $fileName;
                $imgData = file_get_contents(base_url($photoPath));
                $f = finfo_open();
                $mimeType = finfo_buffer($f, $imgData, FILEINFO_MIME_TYPE);
                $data = base64_encode($imgData);

                file_put_contents(FCPATH . $path . '/' . $fileName, $imgData);

                chmod(FCPATH . $path . '/' . $fileName, 0777);

                try {
                    $headers = array("Content-Type:multipart/form-data");
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://10.0.5.85:5050/upload.php',
                        CURLOPT_HEADER => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => $headers,
                        CURLOPT_ENCODING => "",
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_POST => 1,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => [
                            'path'     => 'file/upload/fop/' . date('Y/m/d') . '/',
                            'image' => 'data:' . $mimeType . ';base64,' . $data,
                            'image_name' => $fileName
                        ],
                    ));

                    $response = curl_exec($curl);

                    if (is_dir('E:/EATP/Foto')) {
                        file_put_contents('E:/EATP/Foto/' . $imsPath . '/' . $fileName, $imgData); // IMS

                        chmod('E:/EATP/Foto/' . $imsPath . '/' . $fileName, 0777);
                    }

                    chmod(FCPATH . $path . '/' . $fileName, 0777);

                    chmod($photoPath, 0777);
                    unlink($photoPath);
                } catch (Exception $e) {
                    // redirect
                    redirect(base_url("/visitsummary/fop_attach/$id"));
                }

                $inserted_data = array(
                    'visit_id' => $getid,
                    'fop_photo' => $photoPath,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_on' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('user_id'),
                    'updated_on' => date('Y-m-d H:i:s'),
                );

                if ($this->db->insert('t_trx_schedule_fop_photo', $inserted_data)) {
                    // success & redirect
                    redirect(base_url('/visitsummary'));
                } else {
                    // redirect
                    redirect(base_url("/visitsummary/fop_attach/$id"));
                }
            }
        }

        if ($this->input->post('is_upload_video') == 'uploaded') {
            $path = 'file/upload/fop/' . date('Y/m/d');
            $fileName = 'FOP_' . $removedUserId . '_visitId_' . $getid . '_' . date('Ymd_His') . '.mp4';

            $config['upload_path']          = $path;
            $config['allowed_types']        = 'mp4';
            $config['file_name']            = $fileName;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('video_fop')) {
                echo "error upload";
                return;
                // redirect
                redirect(base_url("/visitsummary/fop_attach/$id"));
            } else {
                $videoPath = $path . '/' . $fileName;

                $inserted_data = array(
                    'visit_id' => $getid,
                    'fop_video' => $videoPath,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_on' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('user_id'),
                    'updated_on' => date('Y-m-d H:i:s'),
                );

                if ($this->db->insert('t_trx_schedule_fop_video', $inserted_data)) {
                    // success & redirect
                    redirect(base_url('/visitsummary'));
                } else {
                    echo "error inserted";
                    return;
                    // redirect
                    redirect(base_url("/visitsummary/fop_attach/$id"));
                }
            }
        }

        // redirect
        redirect(base_url("/visitsummary"));
        return;
    }

    // Add By Hary
    public function get_ECRF_approval()
    {
        $this->load->view('lookUpApprovalECRF');
    }
}
