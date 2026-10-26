<?php

class my_todolist_model extends CI_Model {
	
	/* Kolom grid yang boleh dipakai untuk sort => array(ekspresi tahap 1, ekspresi tahap 2, join tambahan tahap 1) */
	private $list_sortable = array(
		'terakhir_dirubah' => array('b.updated_on', 'k.updated_on', ''),
		'po_no'            => array('d.po_no', 'k.po_no', ''),
		'link_id'          => array('e.link_id', 'e.link_id', 'e'),
		'site_fe_name'     => array('h.site_name', 'h.site_name', 'h'),
		'site_ne_name'     => array('i.site_name', 'i.site_name', 'i'),
	);

	public function my_todolistList($userdata){
		$data = array();
		$page = max(1, (int) $this->input->get('page'));
		$rows = (int) $this->input->get('rows');
		if ($rows < 1) {
			$rows = 10;
		} elseif ($rows > 500) {
			$rows = 500;
		}
		$offset = ($page - 1) * $rows;
		$fsp = $this->input->get('fsp');
		$search = trim((string) $this->input->get('query'));
		$SearchDateFrom = $this->input->get('SearchDateFrom');
		$SearchDateTo = $this->input->get('SearchDateTo');

		/* Sort hanya dari daftar kolom grid; selain itu pakai urutan bawaan (terakhir diubah dulu) */
		$sort_field = (string) $this->input->get('sort');
		$order = strtoupper((string) $this->input->get('order')) == 'ASC' ? 'ASC' : 'DESC';
		if ( ! isset($this->list_sortable[$sort_field])) {
			$sort_field = 'terakhir_dirubah';
			$order = 'DESC';
		}
		list($sort_inner, $sort_outer, $sort_join) = $this->list_sortable[$sort_field];

		$where = "
			WHERE
				(a.is_deleted is NULL or a.is_deleted =0 and b.user_id LIKE '%".$this->db->escape_str($userdata)."%')
		";

		/*
		 * Tabel yang bisa lebih dari 1 baris per schedule (PO link, BoM) dan tabel site dicari
		 * sendiri-sendiri lalu dicocokkan lewat ID-nya, jadi tidak menggandakan baris approval.
		 */
		$filter_joins = "";
		$like = "";
		if ($search !== '')
		{
			/* % dan _ yang diketik dicari apa adanya (backslash = karakter escape LIKE bawaan PostgreSQL) */
			$like = "'%" . $this->db->escape_str(str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $search)) . "%'";
			//e.link_id,h.site_name as site_fe_name,i.site_name as site_ne_name
			$filter_joins = "
				LEFT JOIN (SELECT DISTINCT c.po_id FROM t_trx_po_link c WHERE c.ewo_no ILIKE {$like} OR c.po_description ILIKE {$like}) sc ON sc.po_id = a.po_id
				LEFT JOIN (SELECT DISTINCT g.po_id FROM t_trx_bom g WHERE g.config ILIKE {$like}) sg ON sg.po_id = a.po_id
				LEFT JOIN (SELECT e.link_id_int FROM t_mtr_site_link e
						LEFT JOIN t_mtr_site_xl h on e.site_id_fe = h.site_id
						LEFT JOIN t_mtr_site_xl i on e.site_id_ne = i.site_id
						WHERE e.link_id ILIKE {$like} OR h.site_name ILIKE {$like} OR i.site_name ILIKE {$like}) se ON se.link_id_int = a.link_id";
			$where .= "
				AND (d.po_no ILIKE {$like} OR sc.po_id IS NOT NULL OR sg.po_id IS NOT NULL OR se.link_id_int IS NOT NULL)
			";
		}

		if(!empty($SearchDateFrom) && !empty($SearchDateTo)){
			$scDateFrom = date("Y-m-d",strtotime($SearchDateFrom));
			$scDateTo = date("Y-m-d",strtotime($SearchDateTo));
			$where.= "AND (DATE(b.updated_on) BETWEEN '".$scDateFrom."' and '".$scDateTo."')";
		}

		if ( ! empty($fsp))
		{
			if($fsp == 'not_yet'){
				$where .= "AND (b.status is NULL)";
			}else if($fsp == 'pass'){
				$where .= "AND (b.status = 1)";
			}else if($fsp == 'reject'){
				$where .= "AND (b.status = 0)";
			}
		}

		$site_joins = array(
			'e' => "left join t_mtr_site_link e on e.link_id_int = a.link_id",
			'h' => "left join t_mtr_site_link e on e.link_id_int = a.link_id left JOIN t_mtr_site_xl h on e.site_id_fe = h.site_id",
			'i' => "left join t_mtr_site_link e on e.link_id_int = a.link_id left JOIN t_mtr_site_xl i on e.site_id_ne = i.site_id",
		);
		$sort_join_sql = $sort_join !== '' ? $site_joins[$sort_join] : "";
		$sort_col = $sort_join !== '' ? ", {$sort_inner} AS sort_val" : "";
		if ($sort_join !== '') {
			$sort_outer = 'k.sort_val';
		}

		/* PO link & BoM untuk tampilan: 1 baris per PO, utamakan yang cocok dengan pencarian / link schedule */
		$c_match = $like !== '' ? "(pl.ewo_no ILIKE {$like} OR pl.po_description ILIKE {$like}) DESC NULLS LAST," : "";
		$g_match = $like !== '' ? "ORDER BY (bm.config ILIKE {$like}) DESC NULLS LAST" : "";

		/*
		 * Tahap 1 (k): satu baris per (po_no, updated_on) seperti sebelumnya, hanya dari tabel yang
		 * dibutuhkan filter; lalu urutkan, hitung total dan ambil satu halaman.
		 * Tahap 2: lengkapi kolom tampilan hanya untuk baris halaman itu.
		 */
		//start edit by hadi 2018-09-14
		$sql = "
			select a.*,b.user_id,b.status,c.ewo_no,c.po_description,d.po_no,e.link_id,h.site_name as site_fe_name,i.site_name as site_ne_name,b.updated_on as terakhir_dirubah,g.config,
                                                        CASE when b.status = '1' then 'Pass'
                                                        when b.status = '0' then 'Rejected'
                                                        when b.status is NULL then 'Not Yet'
                                                        else 'Not Defined' end as status_todolist,
                                                        k.total_rows
                                                        from (
                                                            select k.*, COUNT(*) OVER() AS total_rows
                                                            from (
                                                                select distinct on (d.po_no,b.updated_on) a.visit_id, b.sch_appr_id, d.po_no, b.updated_on{$sort_col}
                                                                from t_trx_schedule a
                                                                left join t_trx_schedule_result_approval b on b.visit_id =a.visit_id
                                                                left join t_trx_po_xl d on d.po_id = a.po_id
                                                                {$sort_join_sql}
                                                                {$filter_joins}
                                                                {$where}
                                                            ) k
                                                            ORDER BY {$sort_outer} {$order}, k.po_no, k.updated_on
                                                            LIMIT {$rows} OFFSET {$offset}
                                                        ) k
                                                        join t_trx_schedule a on a.visit_id = k.visit_id
                                                        left join t_trx_schedule_result_approval b on b.sch_appr_id = k.sch_appr_id
                                                        left join lateral (select pl.ewo_no, pl.po_description from t_trx_po_link pl
                                                            where pl.po_id = a.po_id
                                                            order by {$c_match} (pl.link_id_int = a.link_id) DESC NULLS LAST, pl.link_id_int limit 1) c on true
                                                        left join t_trx_po_xl d on d.po_id = a.po_id
                                                        left join t_mtr_site_link e on e.link_id_int = a.link_id
                                                        left join lateral (select bm.config from t_trx_bom bm where bm.po_id = a.po_id {$g_match} limit 1) g on true
														left JOIN t_mtr_site_xl h on e.site_id_fe = h.site_id
														left JOIN t_mtr_site_xl i on e.site_id_ne = i.site_id
			ORDER BY {$sort_outer} {$order}, k.po_no, k.updated_on
		";
		//end edit by hadi 2018-09-14

		$result = $this->db->query($sql)->result_array();

		if ($result) {
			$total_rows = (int) $result[0]['total_rows'];
		} elseif ($offset > 0) {
			/* Halaman di luar jangkauan: total tetap dihitung agar pager grid benar */
			$total_rows = (int) $this->db->query("SELECT COUNT(*) AS total FROM (
					select distinct d.po_no, b.updated_on
					from t_trx_schedule a
					left join t_trx_schedule_result_approval b on b.visit_id =a.visit_id
					left join t_trx_po_xl d on d.po_id = a.po_id
					{$filter_joins}
					{$where}) AS count_src")->row()->total;
		} else {
			$total_rows = 0;
		}

		$rows = array();
		foreach ($result as $r)
		{
			unset($r['total_rows']);

			$r['action'] = '<a href="'.base_url('visitsummary/detail/'.$this->core->encode($r['visit_id'])).'" class="btn btn-primary" title="Detail"><i class="icon-eye-open"></i></a>';
			//$r['action'] .= '<a href="javascript:;" onclick="angular.element(document.getElementById(\'my_todolistList\')).scope().delete(\''.$this->core->encode($r['visit_id']).'\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>';

			$rows[] = $r;
		}

		$data['total'] = $total_rows;
		$data['rows'] = $rows;

		return $data;
	}
	public function getApproval($id)
	{
		$this->db->select('apr.*,usr.user_name');
		$this->db->where('apr.my_todolist_id',$id);
		$this->db->from('t_mtr_my_todolist apr');
		$this->db->join('t_mtr_user usr', 'apr.user_id = usr.user_id');
		$query = $this->db->get()->row();
		return $query;
	}
	
	public function insert($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_mtr_my_todolist', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}
	
	public function update($data, $id)
	{
		$this->db->trans_begin();

		$this->db
			->where('my_todolist_id', $id)
			->update('t_mtr_my_todolist', $data);

		if ($this->db->trans_status() === FALSE)
		{
	    	$this->db->trans_rollback();
	    }
		else
		{
	    	$this->db->trans_commit();
	    }
	}
	
	public function delete($id)
	{
		$data = new stdClass;
		$data->is_deleted = 1;
		
		$this->db->trans_begin();

		$this->db
			->where('my_todolist_id', $id)
			->update('t_mtr_my_todolist', $data);

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

		