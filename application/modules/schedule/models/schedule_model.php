<?php

class schedule_model extends CI_Model
{

	/*Function that return the llist of Site and Handle the Search and Filter of Site Grid*/
	public function scheduleList()
	{
		$data = array();
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'sch.created_on';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));

		/*Date From*/
		$schDateFrom = $this->input->get('schDateFrom');
		/*Date To*/
		$schDateTo = $this->input->get('schDateTo');
		/*Filter by Region, Area and Site Type*/
		$region = $this->input->get('region');

		$where = "
			WHERE
				(sch.is_deleted is NULL or sch.is_deleted =0 and po.is_deleted = 0)
		";

		if (!empty($search)) {
			$where .= "
				AND (usr.user_name ILIKE '%" . $search . "%'
				OR link.link_id ILIKE '%" . $search . "%'
				OR sch.fop_hs_name ILIKE '%" . $search . "%'
				OR  po.po_no ILIKE '%" . $search . "%'
				OR  cop.option_label ILIKE '%" . $search . "%'
				OR  reg.region_name ILIKE '%" . $search . "%'
				OR sch.fop_hs_contact ILIKE '%" . $search . "%')
			";
		}
		/*check if region is not empty*/
		if (!empty($region)) {
			$where .= "
				AND (sch.region_id = {$region})
			";
		}
		if (!empty($schDateFrom) && !empty($schDateTo)) {
			$schDateFrom = date("Y-m-d", strtotime($schDateFrom));
			$schDateTo = date("Y-m-d", strtotime($schDateTo));
			$where .= " AND (sch.visit_date BETWEEN '{$schDateFrom}' AND '{$schDateTo}')";
		}

		$sql = "
			SELECT sch.visit_id, sch.visit_date,sch.fop_hs_name,
				sch.visit_type as \"SOW\", cop.option_label as \"SOW_DESC\", sch.fop_hs_contact,
				reg.region_name as \"region\",
				link.link_id as \"link_id\",
				(
					SELECT ewo_no from t_trx_po_link poli where poli.po_id = sch.po_id
					AND poli.link_id_int = sch.link_id LIMIT 1
				) as \"ewo_nbwo\",
				po.po_id, po.po_no,
				usr.user_name as \"field_eng\",
				usr.user_id as \"field_eng_id\"
				From (((t_trx_schedule sch
				LEFT JOIN t_mtr_region_xl reg ON (sch.region_id = reg.region_id))
				LEFT JOIN t_trx_po_xl po ON (sch.po_id = po.po_id))
				LEFT JOIN t_mtr_user usr on (sch.pic_id = usr.user_id))
				LEFT JOIN t_mtr_site_link link on (sch.link_id = link.link_id_int)
				LEFT JOIN t_mtr_checklist_options cop on sch.visit_type::integer = cop.option_id
			{$where}
			ORDER BY
			{$sort} {$order}
		";


		$sql .= " LIMIT {$rows} OFFSET {$offset}";
		$query = $this->db->query($sql);
		$total_rows = $query->num_rows();
		$rows = array();
		foreach ($query->result_array() as $r) {


			$r['action'] = '<a href="' . base_url('/schedule/edit') . "/" . $this->core->encode($r['visit_id']) . '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>';
			$r['action'] .= '<a href="javascript:;" onclick="angular.element(document.getElementById(\'scheduleList\')).scope().delete(\'' . $this->core->encode($r['visit_id']) . '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>';
			$r['action'] .= '<a href="#/approve/' . $this->core->encode($r['visit_id']) . '" class="btn-action glyphicons check btn-success" title="Approve"><i></i></a>';

			$rows[] = $r;
		}

		$data['total'] = $total_rows;
		$data['rows'] = $rows;

		return $data;
	}



	public function count_schedule()
	{

		$sql = "
			SELECT sch.visit_id, sch.visit_date,sch.fop_hs_name,
				sch.visit_type as \"SOW\", cop.option_label as \"SOW_DESC\", sch.fop_hs_contact,
				reg.region_name as \"region\",
				link.link_id as \"link_id\",
				(
					SELECT ewo_no from t_trx_po_link poli where poli.po_id = sch.po_id
					AND poli.link_id_int = sch.link_id
				) as \"ewo_nbwo\",
				po.po_id, po.po_no,
				usr.user_name as \"field_eng\",
				usr.user_id as \"field_eng_id\"
				From (((t_trx_schedule sch
				LEFT JOIN t_mtr_region_xl reg ON (sch.region_id = reg.region_id))
				LEFT JOIN t_trx_po_xl po ON (sch.po_id = po.po_id))
				LEFT JOIN t_mtr_user usr on (sch.pic_id = usr.user_id))
				LEFT JOIN t_mtr_site_link link on (sch.link_id = link.link_id_int)
				LEFT JOIN t_mtr_checklist_options cop on sch.visit_type::integer = cop.option_id
			where sch.is_deleted is NULL or sch.is_deleted =0 and po.is_deleted = 0

		";


		$query = $this->db->query($sql);

		$total_rows = $query->num_rows();
		return $total_rows;
	}
	/*Select by ID*/
	public function getSchedule($id)
	{
		/*
		$where =" where sch.visit_id = ".$id;
		$sql ="
				SELECT sch.*,po.po_no,lk.link_id as \"link\",usr.user_name as \"pic_name\"
				FROM t_trx_schedule sch
				LEFT JOIN t_trx_po_xl po ON sch.po_id = po.po_id
				LEFT JOIN t_mtr_site_link lk ON sch.link_id = lk.link_id_int
				LEFT JOIN t_mtr_user usr ON sch.pic_id = usr.user_id
				{$where}
				";
		*/
		$sql = "
			SELECT
				sch.*,
				po.po_no,
				lk.link_id AS \"link\",
				usr.user_name AS \"pic_name\"
			FROM t_trx_schedule sch
			LEFT JOIN t_trx_po_xl po ON sch.po_id = po.po_id AND po.is_deleted != 1
			JOIN t_mtr_site_link lk ON sch.link_id = lk.link_id_int AND lk.is_deleted != 1
			LEFT JOIN t_mtr_user usr ON sch.pic_id = usr.user_id AND usr.status = 1
			WHERE
				sch.visit_id = {$id}
			AND sch.is_deleted != 1;
		";

		$res = $this->db->query($sql)->row();
		return $res;
	}
	/*Insert - $data as Parameter*/
	public function insert($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_trx_schedule', $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
		return $this->db->insert_id();
	}
	/*Insert - $data and $id as Parameter*/
	public function update($data, $id)
	{
		$this->db->trans_begin();

		$this->db
			->where('visit_id', $id)
			->update('t_trx_schedule', $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}

	public function delete($id)
	{
		$data = new stdClass;
		$data->is_deleted = 1;

		$this->db->trans_begin();

		//start edit by hadi 20190304
		/*
		//original
		$this->db
			->where('visit_id', $id)
			->update('t_trx_schedule', $data);
		*/

		$this->db->where("visit_id", $id);
		$this->db->delete("t_trx_schedule");

		//end edit by hadi 20190304

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}

	public function getScheduleData($visit_id)
	{
		$query = "SELECT
			schedule.visit_id,
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
			schedule.visit_id = " . $visit_id . "
			and schedule.is_deleted = 0
	        and po.is_deleted = 0
	        and site_ne.is_deleted = 0
	        and site_fe.is_deleted = 0
	        and link.is_deleted = 0";

		$res = $this->db->query($query)->row_array();
		return $res;
	}
}
