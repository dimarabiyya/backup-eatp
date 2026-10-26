
<?php

/*
  @Muhammad Abdul Hadi */

/**
 * This Model is a Summary Model that return data summary schedule
 *
 */
class summary_model_forced extends CI_Model {
    /* Function -> Get Summary based on Status */

    public function getList($status) {
        $data = array();
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
        $offset = ($page - 1) * $rows;
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'a.created_on';
        $order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
        //$search = $this->input->get('query');

        /* Date From */
        $schDateFrom = $this->input->get('schDateFrom');
        /* Date To */
        $schDateTo = $this->input->get('schDateTo');
        /* Filter by Region, Area and Site Type */
        $region = $this->input->get('region');

        $fsp = $this->input->get('fsp');

        $search_b = $this->db->escape_str($this->input->get('search_b'));

        $where = "
			WHERE
				(a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0)
		";

        $tes = "";

        if ($status == 3) {
            $where .= "AND a.status = {$status}";
        } else {
            $where .= "AND a.status != 3";
        }
        /*
          if ( ! empty($search))
          {
          $where .= "
          AND (usr.user_name ILIKE '%".$search."%'
          OR link.link_id ILIKE '%".$search."%'
          OR sch.fop_hs_name ILIKE '%".$search."%'
          OR sch.fop_hs_contact ILIKE '%".$search."%'
          OR usr.user_name ILIKE '%".$search."%'
          OR chopt.option_label ILIKE '%".$search."%'
          OR po.po_no ILIKE '%".$search."%')
          ";
          }
         */
        /* check if region is not empty */
        if (!empty($region)) {
            $where .= "
				AND (a.region_id = {$region})
			";
        }

        if (!empty($search_b)) {
            $where .= "
				AND (d.link_id ILIKE '%" . $search_b . "%'
				OR a.fop_hs_name ILIKE '%" . $search_b . "%'
				OR a.fop_hs_contact ILIKE '%" . $search_b . "%'
				OR g.user_name ILIKE '%" . $search_b . "%'
				OR h.option_label ILIKE '%" . $search_b . "%'
				OR c.po_no ILIKE '%" . $search_b . "%'
				OR j.user_id ILIKE '%" . $search_b . "%')
			";
            $tes .= "
				AND (a.user_id ILIKE '%" . $search_b . "%')";
        }

        if (!empty($fsp)) {
            if ($fsp == 'not_yet') {
                $where .= "AND (j.status is NULL)";
                //where a.visit_id={$sch_id} order by a.order_no ASC
                $tes .= "AND (j.status is NULL)";
            } else if ($fsp == 'pass') {
                $where .= "AND (j.status = 1)";
                $tes .= "AND (j.status = 1)";
            } else if ($fsp == 'reject') {
                $where .= "AND (j.status = 0)";
                $tes .= "AND (j.status = 0)";
            }
            //$where .= "AND(cap.status = {$fsp})";
        } else {
            $where .= "";
        }
        if (!empty($schDateFrom) && !empty($schDateTo)) {
            $schDateFrom = date("Y-m-d", strtotime($schDateFrom));
            $schDateTo = date("Y-m-d", strtotime($schDateTo));
            $where .= " AND (a.visit_date BETWEEN '{$schDateFrom}' AND '{$schDateTo}')";
        }


        $sql = "select DISTINCT on (c.po_no,a.created_on) a.visit_id,a.visit_type,a.is_code_show,a.security_code_checkin,a.security_code_checkout,a.po_id,c.po_no,b.ewo_no,b.po_description,d.link_id,e.site_name as site_fe_name ,e.site_address address_fe,
                    f.site_name as site_ne_name ,g.user_name as \"field_eng\",h.option_label as \"visit_type_label\",
				g.user_id as \"field_eng_id\",f.site_address as address_ne,g.user_name,a.fop_hs_name,a.visit_date as visit_plan,h.option_label,i.region_name as region,a.last_visited_date as actual_date,
                                                            CASE
                                                            WHEN a.status = 0 then 'Not Confirmed'
                                                            WHEN a.status = 1 then 'Confirmed'
                                                            WHEN a.status = 2 then 'Declined'
                                                            WHEN a.status = 3 then 'Visited'
                                                            WHEN a.status = 4 then 'Unschedule'
                                                            WHEN a.status = 5 then 'Re-Schedule' end as desc_status
                    from t_trx_schedule a
                    left join t_trx_po_link b on b.po_id = a.po_id
                    left join t_trx_po_xl c on c.po_id = a.po_id
                    left join t_mtr_site_link d on d.link_id_int = a.link_id
                    left JOIN t_mtr_site_xl e on d.site_id_fe = e.site_id
                    left JOIN t_mtr_site_xl f on d.site_id_ne = f.site_id
                    left join t_mtr_user g on g.user_id = a.pic_id
                    left join t_mtr_checklist_options h on h.option_id = a.visit_type::integer
                    left join t_mtr_region_xl i on i.region_id = a.region_id
                    LEFT JOIN t_trx_schedule_result_approval j on j.visit_id = a.visit_id
                    {$where}
			ORDER BY
			{$sort} {$order}";


        //echo $sql;die;
        $query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

        $total_rows = (int) $query->row()->total;

        $sql .= " LIMIT {$rows} OFFSET {$offset}";

        $query = $this->db->query($sql);
        $sql = "";
        $rows = array();
        foreach ($query->result_array() as $r) {


            $r['action'] = '<a href="' . base_url('/visitsummary/detail') . "/" . $this->core->encode($r['visit_id']) . '"
			class="btn btn-default btn-small btn-icon glyphicons pen"> <i></i> Detail</a>';

            /* Check If Progress Status ==5 , When Prog Status =5, User Enable To Generate Document */
            //if($r['progress_status'] == 5 || $r['progress_status'] == "5"){
            $r['action'] .= '<a href="javascript:void(0);" onClick="Getuserappr('.$r['visit_id'].');"  class="btn-action glyphicons user btn-success" title="Aproval User & Status"><i></i></a>';


			if($this->session->userdata('ugrup') == 'System Admin'){
			$r['action'] .= '<a href="' . base_url('/report/index') . "/" . $this->core->encode($r['visit_id']) . "/" . $this->core->encode($r['visit_type']) . '" target="_blank" class="btn-action glyphicons file btn-success" title="ATP PDF"><i></i></a>';
			}else{
			$r['action'] .='';
			}

            if ($r['visit_type'] == 109 || $r['visit_type'] == 110 || $r['visit_type'] == 113 || $r['visit_type'] == 114) {
                $r['action'] .= '<a href="' . base_url('report/export/index') . "/" . $this->core->encode($r['visit_id']) . "/" . $this->core->encode($r['visit_type']) . '" target="_blank" class="btn-action glyphicons file btn-info" title="IMS Data"><i></i></a>';

                // Remark by Ado Ganteng
                // if ($r['visit_type'] == 110) {
                //     $r['action'] .= 'oke';
                // }
            }

            /* If Code Show is 1 then view security code else hide */

            $r['action'] .= '<a href="' . base_url('/visitsummary/upload_doc') . "/" . $this->core->encode($r['visit_id']) . '" title="Upload Your Data" class="btn-action glyphicons upload btn-info" title="Upload Data"><i></i></a>';
            if ($r['is_code_show'] == 1) {
                $r['code_checkin'] = $r['security_code_checkin'];
                $r['code_checkout'] = $r['security_code_checkout'];
            } else {
                $r['code_checkin'] = '-';
                $r['code_checkout'] = '-';
            }
            /* Get Status Approval Order & Status */

            $rows[] = $r;
        }

        $data['total'] = $total_rows;
        $data['rows'] = $rows;

        return $data;
    }



    public function count_atp_visited() {



        $sql = "SELECT COUNT(*) AS total FROM (SELECT DISTINCT ON (c.po_no,a.created_on) 1
                    from t_trx_schedule a
                    left join t_trx_po_xl c on c.po_id = a.po_id
                    WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3) AS t";


        //echo $sql;die;
		echo "eATP Visited=".$sql."<br/>";
        $query = $this->db->query($sql);

        $total_rows = (int) $query->row()->total;
		echo "total_rows=".$total_rows."<br/><br/>";

        return $total_rows;
    }

    public function count_atp_accepted() {



        $sql = "SELECT COUNT(*) AS total FROM (SELECT DISTINCT ON (c.po_no,a.created_on) 1
                    from t_trx_schedule a
                    left join t_trx_po_xl c on c.po_id = a.po_id
                    left join t_trx_schedule_result_approval_sum k on k.visit_id = a.visit_id
                    WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3  and k.acceptance_status = 1) AS t";


        //echo $sql;die;
		echo "SQL eATP Approved=".$sql."<br/>";
        $query = $this->db->query($sql);

        $total_rows = (int) $query->row()->total;
		echo "total_rows=".$total_rows."<br/><br/>";

        return $total_rows;
    }

    public function count_atp_rejected() {



        $sql = "SELECT COUNT(*) AS total FROM (SELECT DISTINCT ON (c.po_no,a.created_on) 1
                    from t_trx_schedule a
                    left join t_trx_po_xl c on c.po_id = a.po_id
                    left join t_trx_schedule_result_approval_sum k on k.visit_id = a.visit_id
                    WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3  and k.acceptance_status = 2) AS t";


        //echo $sql;die;
		echo "eATP Rejected=".$sql."<br/>";
        $query = $this->db->query($sql);

        $total_rows = (int) $query->row()->total;
		echo "total_rows=".$total_rows."<br/><br/>";

        return $total_rows;
    }

    /* Select by ID */
    public function list_upload($id){
        $this->db->where('visit_id',$id);
        return $this->db->get('t_trx_docs_summary')->result();
    }
    public function getSchedule($id) {
        $where = " where (sch.is_deleted =0 AND sch.visit_id = " . $id . ")";
        $sql = "
				SELECT sch.*, lk.link_id as \"site_link\",usr.user_name as \"pic\",
				usr_d.user_name as \"usr_pm_desktop_by\",po.po_no as \"po_no\",reg.region_name,
				st_ne.latitude as ne_lat,
				st_ne.longitude as ne_long,
				st_fe.latitude as fe_lat,
				st_fe.longitude as fe_long,
				CASE
					WHEN sch.status = 0 then 'Not Confirmed'
					WHEN sch.status = 1 then 'Confirmed'
					WHEN sch.status = 2 then 'Declined'
					WHEN sch.status = 3 then 'Visited'
					WHEN sch.status = 4 then 'Unschedule'
					WHEN sch.status = 5 then 'Re-Schedule'
				END as \"desc_status\",
				poli.ewo_no as \"ewo_nbwo_no\",
				chop.option_label as \"visit_type_desc\"
				FROM t_trx_schedule sch
				LEFT JOIN t_trx_po_xl po ON sch.po_id = po.po_id
				LEFT JOIN t_mtr_site_link lk on sch.link_id = lk.link_id_int
				LEFT JOIN t_mtr_site_xl st_ne on st_ne.site_id = lk.site_id_ne
				LEFT JOIN t_mtr_site_xl st_fe on st_fe.site_id = lk.site_id_fe
				LEFT JOIN t_mtr_user usr on sch.pic_id = usr.user_id
				LEFT JOIN t_mtr_user usr_d on sch.pm_desktop_by = usr_d.user_id
				LEFT JOIN t_mtr_region_xl reg on sch.region_id = reg.region_id
				LEFT JOIN t_trx_po_link poli on sch.po_id = poli.po_id and sch.link_id = poli.link_id_int
				LEFT JOIN t_mtr_checklist_options chop on chop.option_id = sch.visit_type::integer
				{$where}
				";
        $res = $this->db->query($sql)->row();
        return $res;
    }

    /* Function Get Detail Summary based_on Schedule ID */

    public function getDetailSummary($visit_id) {

        $user_id = $this->session->userdata('user_id');
        $sql2 = "SELECT cc.checklist_id, cc.checklist_name, cc.checklist_group, cc.has_photo, cc.total_photo, pr.latitude as lat_chk, pr.longitude as lon_chk,
	             cc.mandatory, pr.checklist_result_id, pr.checklist_result pm_result,rsp.photo_name, rsp.photo_thumbnail,
	             (
					SELECT ap2.status from t_trx_checklist_result_approval ap2
					where visit_id = {$visit_id} and ap2.checklist_id = sc.checklist_id and ap2.user_id = '{$user_id}'
				  LIMIT 1
				)as status,
	             cc.interface, f_get_checklistgroup(cc.checklist_group) group_path
				FROM t_trx_schedule_checklist sc
				join t_mtr_checklist cc on cc.checklist_id = sc.checklist_id
				left join t_trx_checklist_result pr on pr.checklist_id = sc.checklist_id
				and sc.visit_id = pr.visit_id
				left join t_trx_checklist_result_photo rsp on rsp.checklist_id = sc.checklist_id
				and sc.visit_id = rsp.visit_id
				where sc.visit_id = $visit_id ";
        //echo $sql2;die;
        $x['checklists'] = $this->db->query($sql2)->result_array();

        $sql3 = "SELECT sc.checklist_id, ca.option_id, ca.option, ca.is_critical
	             FROM t_trx_schedule_checklist sc
	             join t_mtr_checklist_answer ca on ca.checklist_id = sc.checklist_id
	             where  ca.active = 1 and
	             sc.visit_id = $visit_id ";
        $x['answer'] = $this->db->query($sql3)->result_array();

        return $x;
    }

    /* public function getApprovalInfo per visit_id */

    public function getApprovalInfo($visit_id) {
        $sql = "
				Select * from t_trx_schedule_result_approval
				where visit_id = {$visit_id}
				";
        $res = $this->db->query($sql)->result_array();
        return $res;
    }

    /* Site Properties */

    public function siteProperties() {
        $this->db->select('cc.checklist_id, cc.checklist_group group_id, cc.checklist_name, cc.interface');
        $this->db->join('t_mtr_checklist_group gg', 'gg.checklist_group_id = cc.checklist_group');
        $this->db->where('gg.parent_id !=', 0);
        $this->db->where('gg.group_key', 'prev.maintenance');
        $this->db->where('gg.status', 1);
        $this->db->where('cc.status', 1);
        $this->db->order_by('cc.checklist_group, cc.checklist_group, gg.order_number, cc.order_number');
        $q = $this->db->get('t_mtr_checklist cc');
        $data["item"] = $q->result_array();

        $this->db->select('checklist_group_id group_id, group_name, parent_id');
        $this->db->where('checklist_group_id !=', 1);
        $this->db->where('group_key', 'prev.maintenance');
        $this->db->where('status', 1);
        $this->db->order_by('order_number, parent_id, checklist_group_id');
        $q = $this->db->get('t_mtr_checklist_group');
        $data["group"] = $q->result_array();

        $this->db->select('rr.checklist_id, oo.option_id, oo.option_value, oo.locked');
        $this->db->join('t_mtr_checklist_template rr', 'rr.option_id = oo.option_id');
        $this->db->where('oo.option_key', 'pm_visit_type');
        $this->db->where('oo.status', 1);
        $q = $this->db->get('t_mtr_checklist_options oo');
        $data["options"] = $q->result_array();

        return $data;
    }

    /* Get Image from t_trx_result_photo */

    public function getImageChecklist($visit_id, $chk_id) {
        $this->db->where("visit_id", $visit_id);
        $this->db->where("checklist_id", $chk_id);
        $rs = $this->db->get("t_trx_checklist_result_photo")->row();
        return $rs;
    }

    /* Save Reject & Approval Answer */

    //public function saveApprovalAnswer($user_id, $data, $is_mass) {
	public function saveApprovalAnswer($checklist_id, $user_id, $data, $is_mass) {
		
		$sql = "";
		$items = "";
		foreach ($checklist_id as $item) {
			$items .= $item.",";
		}
		$items = substr_replace($items ,"",-1);
		//log_message('DEBUG', "items=".$items);
		
		$this->db->where("visit_id", $data->visit_id);
        $this->db->where_in("checklist_id", $checklist_id);
        $this->db->where("user_id", $user_id);
        $this->db->delete("t_trx_checklist_result_approval");
		
			foreach ($checklist_id as $item) {
				/* Not Exist then insert */
				$data->user_id = $user_id;
				$data->checklist_id = $item;
				$this->db->trans_begin();
				$this->db->insert('t_trx_checklist_result_approval', $data);

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
				} else {
					$this->db->trans_commit();
				}
			}
    }

    /* Save Pending Item */

    public function savePendingItem($data) {
        $this->db->trans_begin();

        $this->db->insert('t_trx_schedule_pending_item', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /* Update Pending Item */

    public function updatePendingItem($data, $pending_id) {
        $this->db->trans_begin();
        $this->db
                ->where('pending_item_id', $pending_id)
                ->update('t_trx_schedule_pending_item', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /* Update Result Approval */

    public function updateResultApproval($data, $visit_id, $user_id) {
        $this->db->trans_begin();
        $this->db
                ->where('visit_id', $visit_id)
                ->where('user_id', $user_id)
                ->update('t_trx_schedule_result_approval', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /* Save Approval Summary */

    public function saveApprovalSummary($data) {



      $this->db->trans_begin();


      $this->db->insert('t_trx_schedule_result_approval_sum', $data);

      if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
      } else {
          $this->db->trans_commit();
      }

    }

    /* Function that return count approve with total checklist */

    public function is_approved_all($visit_id, $user_id) {
        $sql1 = "SELECT * from t_trx_schedule_checklist where visit_id = {$visit_id}";
        $query1 = $this->db->query($sql1);
        $total_rows_checklist = $query1->num_rows();
        /* For all answer approved */
        $sql2 = "SELECT * from t_trx_checklist_result_approval where visit_id = {$visit_id} and user_id = '{$user_id}' and status =1";
        $query2 = $this->db->query($sql2);
        $total_rows_checklist_approved = $query2->num_rows();
        if ($total_rows_checklist == $total_rows_checklist_approved && $total_rows_checklist != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /* Get Summary Approval Summary from user login */

    public function getApprovalSummary($user_id, $visit_id) {
        $this->db->where("visit_id", $visit_id);
        $this->db->where("user_id", $user_id);
		$this->db->order_by("sum_appr_id", "desc");
		$this->db->limit(1);
        $rs = $this->db->get("t_trx_schedule_result_approval_sum");
        return $rs->row();
    }

    /* Function get all pending item visit & user approval */

    public function getPendingItem($visit_id) {
        $this->db->where("visit_id", $visit_id);
        $this->db->where("is_deleted", 0);
        $this->db->order_by("created_on", "DESC");
        $rs = $this->db->get("t_trx_schedule_pending_item");
        return $rs->result();
    }

    /* Soft Delete Pending Items */

    public function delete($pending_id, $data) {

        $this->db->trans_begin();
        $this->db
                ->where('pending_item_id', $pending_id)
                ->update('t_trx_schedule_pending_item', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /* Get Approval user based on this schedule */

    public function getApprovalOrder($sch_id) {
        /* status = 0 Progress, & NUll not Touch yet, status 1 = Complete */
        $sql = "select *,CASE
    			WHEN acceptance_status  = 1 THEN 'Pass'
    			WHEN acceptance_status = 2 THEN 'Reject'
    			ELSE 'Not Yet'
    		END as \"desc_status\"  from t_trx_schedule_result_approval_sum where visit_id = {$sch_id} order by sum_appr_id asc";

        /*$sql = "SELECT a.*,b.remark,
		CASE
			WHEN a.status = 1 THEN 'Pass'
			WHEN a.status = 0 THEN 'Reject'
			ELSE 'Not Yet'
		END as \"desc_status\"
		FROM t_trx_schedule_result_approval a
		left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id AND b.user_id = a.user_id
		where a.visit_id={$sch_id} order by a.order_no ASC ";
        $res = $this->db->query($sql);*/
        $res = $this->db->query($sql);
        return $res->result();




        /*
          $sql = "SELECT a.*,b.remark,
          CASE
          WHEN a.status = 1 THEN 'Pass'
          WHEN a.status = 0 THEN 'Reject'
          ELSE 'Not Yet'
          END as \"desc_status\"
          FROM t_trx_schedule_result_approval a
          left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id AND b.user_id = a.user_id
          where a.visit_id={$sch_id} {$tes} order by a.order_no ASC ";
          $res = $this->db->query($sql);
          return $res->result();
         */
    }

    //start edited by Hadi 2018-03-27
    public function getApprovalOrderFirst($visit_id) {
        $sql = "SELECT user_id FROM t_trx_schedule_result_approval WHERE visit_id = {$visit_id} AND order_no = 1";
        $res = $this->db->query($sql)->row();
        return $res->user_id;
    }
	//end
	
    public function getApprovalOrderForce($visit_id) {
        $sql = "SELECT user_id FROM t_trx_schedule_result_approval WHERE visit_id = {$visit_id} AND order_no = 2";
        $res = $this->db->query($sql)->row();
        return $res->user_id;
    }

    public function get_appr_list($sch_id) {
        /* status = 0 Progress, & NUll not Touch yet, status 1 = Complete */


        $sql = "SELECT a.*,b.remark,
		CASE
			WHEN a.status = 1 THEN 'Pass'
			WHEN a.status = 0 THEN 'Reject'
			ELSE 'Not Yet'
		END as \"desc_status\"
		FROM t_trx_schedule_result_approval a
		left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id AND b.user_id = a.user_id
		where a.visit_id={$sch_id} order by a.order_no ASC ";
        $res = $this->db->query($sql);
        return $res->result();




        /*
          $sql = "SELECT a.*,b.remark,
          CASE
          WHEN a.status = 1 THEN 'Pass'
          WHEN a.status = 0 THEN 'Reject'
          ELSE 'Not Yet'
          END as \"desc_status\"
          FROM t_trx_schedule_result_approval a
          left join t_trx_schedule_result_approval_sum b on b.visit_id = a.visit_id AND b.user_id = a.user_id
          where a.visit_id={$sch_id} {$tes} order by a.order_no ASC ";
          $res = $this->db->query($sql);
          return $res->result();
         */
    }

    /* Public function Check if User Register in Approval with Schedule Selected */

    public function is_eligible_approval($sch_id, $user_id) {
        $sql = "SELECT * from t_trx_schedule_result_approval
				WHERE visit_id={$sch_id} and user_id = '{$user_id}'";
        $rs = $this->db->query($sql)->row();
        if ($rs) { /* Exist in Approval Template */
            return true;
        } else {
            return false;
        }
    }

    /* Function Get Status Approval Checklist User with Order < Current User */

    public function is_user_before_approval_done($sch_id, $user_id) {
        /* default for current user */
        $flag_done = true;
        /* 1. Get User With Order NO-1 from Current User */
        $sql = "SELECT a1.* from t_trx_schedule_result_approval a1
				where a1.order_no < (
				Select a2.order_no from t_trx_schedule_result_approval a2
				where a2.visit_id={$sch_id} and a2.user_id = '{$user_id}'
				)
				and a1.visit_id = {$sch_id}
				order by a1.order_no DESC
				LIMIT 1";
        //echo $sql."<br/>";
        $user_before_approval = $this->db->query($sql)->row();

        /* 1. Select Total Checklist fot this Visit */
        $total_chk = $this->db->where("visit_id", $sch_id)->get("t_trx_schedule_checklist")->num_rows();
        //echo $total_chk."|";
        //echo $user_before_approval->user_id."|";
        if ($user_before_approval) {
            $total_approval = $this->db->where("visit_id", $sch_id)
                            ->where("user_id", $user_before_approval->user_id)
                            ->where("status", 1)
                            ->get("t_trx_checklist_result_approval")->num_rows();

            if ($total_chk != $total_approval) {
                $flag_done = false;
            }
        }
        /* Else Means Current User, Approval should be Un-Locked */
        return $flag_done;
    }

    public function countChk($sch_id) {
        return $this->db->where("visit_id", $sch_id)->get("t_trx_schedule_checklist")->num_rows();
    }

    /* Public function if current user is the last approval person in visit_id */

    public function is_last_approval($user_id, $sch_id) {
		$sql = "
			SELECT
				sra.user_id,
				sra.order_no,
				app.is_mandatory
			FROM
				t_trx_schedule_result_approval sra
			join t_mtr_approval app on app.user_id = sra.user_id
			WHERE
				sra.visit_id = {$sch_id}
				and app.is_mandatory = 1
			ORDER BY
				sra.order_no DESC
			limit 1;
			";
        $res = $this->db->query($sql)->row();

		$sql2 = "
			SELECT
				sra.user_id,
				sra.order_no,
				app.is_mandatory,
				sra.status
			FROM
				t_trx_schedule_result_approval sra
			JOIN t_mtr_approval app ON app.user_id = sra.user_id
			WHERE
				sra.visit_id = {$sch_id}
			AND app.is_mandatory = 1
			ORDER BY
				sra.order_no ASC;
			";
		$res2 = $this->db->query($sql2)->result();
		$flag_all_approve = true;
		foreach ($res2 as $row) {
			if ($row->status != 1) {
				$flag_all_approve = false;
			}
		}

        if ($res) {
            if ($res->user_id == $user_id && $flag_all_approve) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /* Change Progress Status POLINK */

    public function updatePOLIProgressStatus($po_id, $link_id, $data) {

        $this->db
                ->where('link_id_int', $link_id)
                ->where('po_id', $po_id)
                ->update('t_trx_po_link', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /* Get Count Approval & Rejected */

    public function countApprovalStatus($visit_id, $user_id, $flag) {
        $sql2 = "SELECT * from t_trx_checklist_result_approval where visit_id = {$visit_id}
				and user_id = '{$user_id}' and status ={$flag}";
        $query2 = $this->db->query($sql2);
        return $query2->num_rows();
    }

    public function getScheduleData($visit_id){
        $query = "SELECT
            schedule.visit_id,
            schedule.pic_id,
            schedule.fop_hs_name,
            schedule.fop_hs_contact,
            schedule.proposed_date,
            schedule.approval_remarks,
            po.po_no,
            link.link_id,
			site_ne.site_id as site_id_ne,
			site_ne.latitude as latitude_ne,
			site_ne.longitude as longitude_ne,
			site_ne.site_address as site_address_ne,
			site_fe.site_id as site_id_fe,
			site_fe.latitude as latitude_fe,
			site_fe.longitude as longitude_fe,
			site_fe.site_address as site_address_fe,
            chop.option_label as visit_type_desc,
            schedule.visit_date,
            schedule.priority,
            schedule.remark,
            schedule.created_by
		FROM
			t_trx_schedule schedule
		LEFT JOIN
		    t_trx_po_xl po on schedule.po_id = po.po_id
		LEFT JOIN
		    t_mtr_site_link link on schedule.link_id = link.link_id_int
		JOIN
		    t_mtr_site_xl site_ne on site_ne.site_id = link.site_id_ne
		JOIN
		    t_mtr_site_xl site_fe on site_fe.site_id = link.site_id_fe
		LEFT JOIN
		    t_mtr_checklist_options chop on schedule.visit_type::integer = chop.option_id
		WHERE
			schedule.visit_id = ".$visit_id."
			and schedule.is_deleted = 0
	        and po.is_deleted = 0
	        and site_ne.is_deleted = 0
	        and site_fe.is_deleted = 0
	        and link.is_deleted = 0";

		$res = $this->db->query($query)->row_array();
		return $res;

	}

}
