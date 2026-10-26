<?php

/*@ Class for Select PIC User group ID = 11*/
class user_pic_model extends CI_Model {

	/*Get List User PIC*/
	public function getUserList($group){
		$data = array();
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$rows = $this->input->get('rows') ? $this->input->get('rows') : 10;
		$offset = ($page - 1) * $rows;
		$sort = $this->input->get('sort') ? $this->input->get('sort') : 'user_id';
		$order = $this->input->get('order') ? strtoupper($this->input->get('order')) : 'DESC';
		$search = $this->db->escape_str($this->input->get('query'));

		/*ID = 11 di table tr_user_group untuk PIC Site*/
		$where = "
			WHERE
				(usr.status = 1 AND mrole.user_group_id = ".$group.")
		";
		//echo $where;die;
		if ( ! empty($search))
		{
			$where .= "
				AND (usr.user_name ILIKE '%".$search."%'
				OR usr.email ILIKE '%".$search."%')
			";
		}

		$sql = "
			SELECT usr.*
			FROM t_mtr_user usr
			JOIN t_mtr_user_multi_role mrole ON mrole.user_id = usr.user_id
			{$where}
			ORDER BY
			{$sort} {$order}
		";

		$query = $this->db->query("SELECT COUNT(*) AS total FROM (".$sql.") AS count_src");

		$total_rows = (int) $query->row()->total;

		$sql .= " LIMIT {$rows} OFFSET {$offset}";

		$query = $this->db->query($sql);
		$rows = $query->result_array();
		$data['total'] = $total_rows;
		$data['rows'] = $rows;
		return $data;
	}
}
