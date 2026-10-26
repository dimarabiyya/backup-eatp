<?php
/*Model for ATP Desktop*/
class atpdesktop_model extends CI_Model {
	
	public function schedulelist(){
		$data = array();
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;	
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'sch.visit_id';	
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));		
		$region = $this->input->get('region');
		
		/*Date From*/
		$schDateFrom = $this->input->get('schDateFrom');
		/*Date To*/
		$schDateTo = $this->input->get('schDateTo');

		$where = "
			WHERE
				(sch.is_deleted is Null OR sch.is_deleted = 0 and po.is_deleted =0 )
		";
		
		if ( ! empty($search))
		{
			$where .= "
				AND (lk.link_id ILIKE '%".$search."%' 
				OR usr.user_name ILIKE '%".$search."%'
				OR po.po_no ILIKE '%".$search."%'
				OR chop.option_label ILIKE '%".$search."%'
				OR usr_d.user_name ILIKE '%".$search."%')
			";
		}
		/*check if region is not empty*/
		if ( ! empty($region))
		{
			$where .= "
				AND (sch.region_id = {$region})
			";
		}
		
		if ( ! empty($schDateFrom) && ! empty($schDateTo))
		{
			$schDateFrom = date("Y-m-d",strtotime($schDateFrom));
			$schDateTo = date("Y-m-d",strtotime($schDateTo));
			$where .= " AND (sch.visit_date BETWEEN '{$schDateFrom}' AND '{$schDateTo}')";
		}

		if ($this->session->userdata['ugrup'] == 'FOP ALITA') {
			$where .= " AND sch.pic_id = '".$this->session->userdata['user_id']."'";
		}

		$sql = "
			SELECT sch.*, lk.link_id as \"site_link\" ,usr.user_name as \"pic\",
			usr_d.user_name as \"usr_pm_desktop_by\",po.po_no as \"po_no\",
			CASE
				WHEN sch.status = 0 then 'Not Confirmed'
				WHEN sch.status = 1 then 'Confirmed'
				WHEN sch.status = 2 then 'Declined'
				WHEN sch.status = 3 then 'Visited'
				WHEN sch.status = 4 then 'Unschedule'
				WHEN sch.status = 5 then 'Re-Schedule'
			END as \"desc_status\",			
			chop.option_label as \"visit_type_desc\"
			FROM t_trx_schedule sch
			LEFT JOIN t_trx_po_xl po ON sch.po_id = po.po_id
			LEFT JOIN t_mtr_site_link lk on sch.link_id = lk.link_id_int
			LEFT JOIN t_mtr_user usr on sch.pic_id = usr.user_id
			LEFT JOIN t_mtr_user usr_d on sch.pm_desktop_by = usr_d.user_id
			LEFT JOIN t_mtr_checklist_options chop on chop.option_id = sch.visit_type::integer
			{$where}
			ORDER BY
			{$sort} {$order}			
		";
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");
		$total_rows = (int) $query->row()->total;
		
		$sql .= " LIMIT {$rows} OFFSET {$offset}";

		$query = $this->db->query($sql);		
		$rows = array();
		foreach ($query->result_array() as $r)
		{			
			$r['action'] = '<a href="'.base_url('/atpdesktop/detail')."/".$this->core->encode($r['visit_id']).'" class="btn btn-default btn-small btn-icon glyphicons pen " title="Detail"><i></i>Detail</a>';
			$rows[] = $r;
		}

		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		return $data;
	}

	/*Select by ID*/
	public function getSchedule($id){
		$where =" where (sch.is_deleted =0 AND sch.visit_id = ".$id.")";
		$sql ="
				SELECT sch.*, lk.link_id as \"site_link\",usr.user_name as \"pic\",
				usr_d.user_name as \"usr_pm_desktop_by\",po.po_no as \"po_no\",reg.region_name,
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

	public function pmdesktopdata($visit_id)
	{	    

	    $sql2 = "SELECT cc.checklist_id, cc.checklist_name, ct.type_name checklist_type, cc.checklist_group, cc.has_photo, cc.total_photo,
	             cc.mandatory, pr.checklist_result_id, pr.checklist_result pm_result, rsp.photo_thumbnail, cc.interface, f_get_checklistgroup(cc.checklist_group) group_path
				FROM t_trx_schedule_checklist sc
				join t_mtr_checklist cc on cc.checklist_id = sc.checklist_id
				join t_mtr_checklist_type ct on ct.checklist_type_id = cc.checklist_type
				left join t_trx_checklist_result pr on pr.checklist_id = sc.checklist_id
				and sc.visit_id = pr.visit_id
				left join t_trx_checklist_result_photo rsp on rsp.checklist_id = sc.checklist_id 	
				and sc.visit_id = rsp.visit_id
				where cc.interface = 'desktop' and
	             sc.visit_id = $visit_id ";
	    $x['checklists'] = $this->db->query($sql2)->result_array();

	    $sql3 = "SELECT sc.checklist_id, ca.option_id, ca.option, ca.is_critical
	             FROM t_trx_schedule_checklist sc
	             join t_mtr_checklist_answer ca on ca.checklist_id = sc.checklist_id
	             where  ca.active = 1 and
	             sc.visit_id = $visit_id ";
	    $x['answer'] = $this->db->query($sql3)->result_array();

	    return $x;
	}
	/*Site Properties*/
	public function siteProperties()
	{
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
	/*Function that save answer each checklist to table t_trx_checklist_result*/
	public function saveAnswer($data){
		/*check if the answer is exist*/
		$this->db->where("checklist_id",$data->checklist_id);
		$this->db->where("visit_id",$data->visit_id);
		$res_row = $this->db->get("t_trx_checklist_result")->row();
		/*if Exist -> update*/
		if($res_row){
			$data_update = new stdClass;
			$data_update->checklist_result = $data->checklist_result;
			$data_update->updated_on = date('Y-m-d H:i:s');
			$data_update->updated_by = $this->session->userdata('user_id');

			$this->db->trans_begin();
			$this->db
				->where('visit_id', $data->visit_id)
				->where('checklist_id', $data->checklist_id)
				->update('t_trx_checklist_result', $data_update);

			if ($this->db->trans_status() === FALSE)
			{
		    	$this->db->trans_rollback();
		    }
			else
			{
		    	$this->db->trans_commit();
		    }

		}else{
			/*Else Insert*/
			/**Block Insert Data**/
			$this->db->trans_begin();

			$this->db->insert('t_trx_checklist_result', $data);

			if ($this->db->trans_status() === FALSE)
			{
		    	$this->db->trans_rollback();
		    }
			else
			{
		    	$this->db->trans_commit();
		    }
		}
		
	}
	/*Function save Photo of Checklist*/
	public function saveAnswerPhoto($data){
		/*check if the answer is exist*/
		$this->db->where("checklist_id",$data->checklist_id);
		$this->db->where("visit_id",$data->visit_id);
		$res_row = $this->db->get("t_trx_checklist_result_photo")->row();
		/*if Exist -> update*/
		if($res_row){
			$data_update = new stdClass;
			$data_update->photo_name = $data->photo_name;
			$data_update->photo_thumbnail = $data->photo_thumbnail;
			$data_update->updated_on = date('Y-m-d H:i:s');
			$data_update->updated_by = $this->session->userdata('user_id');

			$this->db->trans_begin();
			$this->db
				->where('visit_id', $data->visit_id)
				->where('checklist_id', $data->checklist_id)
				->update('t_trx_checklist_result_photo', $data_update);

			if ($this->db->trans_status() === FALSE)
			{
		    	$this->db->trans_rollback();
		    }
			else
			{
		    	$this->db->trans_commit();
		    }

		}else{
			/*Else Insert*/
			/**Block Insert Data**/
			$this->db->trans_begin();

			$this->db->insert('t_trx_checklist_result_photo', $data);

			if ($this->db->trans_status() === FALSE)
			{
		    	$this->db->trans_rollback();
		    }
			else
			{
		    	$this->db->trans_commit();
		    }
		}
		
	}
	/*function to update schedule*/
	public function updateSchedule($data,$sch_id){
		$this->db->trans_begin();
			$this->db
				->where('visit_id', $sch_id)
				->update('t_trx_schedule', $data);

			if ($this->db->trans_status() === FALSE)
			{
		    	$this->db->trans_rollback();
		    }
			else
			{
		    	$this->db->trans_commit();
		    }
	}

}
