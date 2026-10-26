<?php

/*
  @Rony Tampubolon */

/**
 * This Model is a Summary Model that return data summary schedule
 *
 */
class summary_model extends CI_Model
{
    /* Kolom grid yang boleh dipakai untuk sort => array(ekspresi SQL, alias tabel yang dibutuhkan) */
    private $list_sortable = array(
        'visit_id'         => array('a.visit_id', array()),
        'tpd_id'           => array('a.tpd_id', array()),
        'po_no'            => array('c.po_no', array()),
        'ewo_no'           => array('b.ewo_no', array('b')),
        'po_description'   => array('b.po_description', array('b')),
        'link_id'          => array('d.link_id', array('d')),
        'site_ne_name'     => array('f.site_name', array('d', 'f')),
        'address_ne'       => array('f.site_address', array('d', 'f')),
        'site_fe_name'     => array('e.site_name', array('d', 'e')),
        'address_fe'       => array('e.site_address', array('d', 'e')),
        'field_eng'        => array('g.user_name', array('g')),
        'fop_hs_name'      => array('a.fop_hs_name', array()),
        'priority'         => array('a.priority', array()),
        'visit_type_label' => array('h.option_label', array('h')),
        'region'           => array('i.region_name', array('i')),
        'visit_plan'       => array('a.visit_date', array()),
        'desc_status'      => array('a.status', array()),
    );

    /*
     * Tabel pelengkap daftar summary. Semuanya paling banyak 1 baris per schedule, sehingga
     * tidak perlu DISTINCT. PO link (b): utamakan baris yang link-nya sama dengan schedule.
     */
    private $list_joins = array(
        'b' => "LEFT JOIN LATERAL (SELECT pl.ewo_no, pl.po_description FROM t_trx_po_link pl
                        WHERE pl.po_id = a.po_id
                        ORDER BY (pl.link_id_int = a.link_id) DESC NULLS LAST, pl.link_id_int LIMIT 1) b ON TRUE",
        'd' => "LEFT JOIN t_mtr_site_link d ON d.link_id_int = a.link_id",
        'e' => "LEFT JOIN t_mtr_site_xl e ON e.site_id = d.site_id_fe",
        'f' => "LEFT JOIN t_mtr_site_xl f ON f.site_id = d.site_id_ne",
        'g' => "LEFT JOIN t_mtr_user g ON g.user_id = a.pic_id",
        'h' => "LEFT JOIN t_mtr_checklist_options h ON h.option_id = a.visit_type::integer",
        'i' => "LEFT JOIN t_mtr_region_xl i ON i.region_id = a.region_id",
    );

    /* Function -> Get Summary based on Status */

    public function getList($status)
    {
        $data = array();
        $page = max(1, (int) $this->input->get('page'));
        $rows = (int) $this->input->get('rows');
        if ($rows < 1) {
            $rows = 10;
        } elseif ($rows > 500) {
            $rows = 500;
        }
        $offset = ($page - 1) * $rows;

        /* Sort hanya dari daftar kolom grid; selain itu pakai urutan bawaan (terbaru dulu) */
        $sort_field = (string) $this->input->get('sort');
        $order = strtoupper((string) $this->input->get('order')) == 'ASC' ? 'ASC' : 'DESC';
        $sort_joins = array();
        if (isset($this->list_sortable[$sort_field])) {
            $sort_expr = $this->list_sortable[$sort_field][0];
            $sort_joins = $this->list_sortable[$sort_field][1];
        } else {
            $sort_expr = 'a.created_on';
            $order = 'DESC';
        }
        /* visit_id sebagai penentu akhir supaya halaman tidak tumpang tindih saat nilai sort sama */
        $order_by = "ORDER BY {$sort_expr} {$order}, a.visit_id {$order}";

        /* Date From */
        $schDateFrom = $this->input->get('schDateFrom');
        /* Date To */
        $schDateTo = $this->input->get('schDateTo');
        /* Filter by Region, Area and Site Type */
        $region = $this->input->get('region');

        $fsp = $this->input->get('fsp');

        $search_b = trim((string) $this->input->get('search_b'));

        $where = "
			WHERE
				(a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0)
		";

        if ($status == 3) {
            $where .= " AND a.status = 3";
        } else {
            $where .= " AND a.status != 3";
        }

        /* check if region is not empty */
        if (!empty($region) && is_numeric($region)) {
            $where .= " AND (a.region_id = " . (int) $region . ")";
        }

        /*
         * Filter yang melihat tabel lain dibuat sebagai LEFT JOIN ke subquery ber-kunci unik
         * (paling banyak 1 baris per schedule): tabelnya dibaca sekali, hasilnya di-hash join.
         * Sengaja tidak memakai "OR x IN (SELECT ..)": dengan work_mem kecil PostgreSQL
         * mengeksekusi subquery itu ulang untuk setiap baris schedule.
         */
        $filter_joins = "";
        $approval = "t_trx_schedule_result_approval";

        /* Status PIC: ada baris approval dengan status tsb (not_yet: belum diisi / belum ada approval) */
        $fsp_status = "";
        if ($fsp == 'not_yet') {
            $fsp_status = " AND j.status IS NULL";
            $filter_joins .= " LEFT JOIN (SELECT j.visit_id, bool_or(j.status IS NULL) AS has_null FROM {$approval} j GROUP BY j.visit_id) fj ON fj.visit_id = a.visit_id";
            $where .= " AND (fj.visit_id IS NULL OR fj.has_null)";
        } else if ($fsp == 'pass' || $fsp == 'reject') {
            $fsp_status = " AND j.status = " . ($fsp == 'pass' ? 1 : 0);
            $where .= " AND a.visit_id IN (SELECT j.visit_id FROM {$approval} j WHERE TRUE{$fsp_status})";
        }

        /*
         * Search PIC/Site/Visit ID. Tiap tabel dicari sendiri lalu dicocokkan lewat ID-nya,
         * jadi ILIKE hanya berjalan sekali per baris tabel (bukan per baris hasil join) dan
         * bisa memakai indeks trigram bila tersedia (sql/optimasi_index_postgres.sql bagian 6).
         */
        if ($search_b !== '') {
            /* % dan _ yang diketik dicari apa adanya (backslash = karakter escape LIKE bawaan PostgreSQL) */
            $like = "'%" . $this->db->escape_str(str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $search_b)) . "%'";
            $filter_joins .= "
                LEFT JOIN (SELECT d.link_id_int FROM t_mtr_site_link d WHERE d.link_id ILIKE {$like}) sd ON sd.link_id_int = a.link_id
                LEFT JOIN (SELECT g.user_id FROM t_mtr_user g WHERE g.user_name ILIKE {$like}) sg ON sg.user_id = a.pic_id
                LEFT JOIN (SELECT h.option_id FROM t_mtr_checklist_options h WHERE h.option_label ILIKE {$like}) sh ON sh.option_id = a.visit_type::integer
                LEFT JOIN (SELECT DISTINCT j.visit_id FROM {$approval} j WHERE j.user_id ILIKE {$like}{$fsp_status}) sj ON sj.visit_id = a.visit_id";
            $search = array(
                "a.fop_hs_name ILIKE {$like}",
                "a.tpd_id ILIKE {$like}",
                "a.fop_hs_contact ILIKE {$like}",
                "c.po_no ILIKE {$like}",
                "sd.link_id_int IS NOT NULL",
                "sg.user_id IS NOT NULL",
                "sh.option_id IS NOT NULL",
                "sj.visit_id IS NOT NULL",
            );
            /* Visit ID berupa angka: hanya mungkin cocok bila yang dicari juga angka */
            if (ctype_digit($search_b)) {
                $search[] = "CAST(a.visit_id AS TEXT) LIKE {$like}";
            }
            $where .= " AND (" . implode(" OR ", $search) . ")";
        }

        if (!empty($schDateFrom) && !empty($schDateTo)) {
            $schDateFrom = date("Y-m-d", strtotime($schDateFrom));
            $schDateTo = date("Y-m-d", strtotime($schDateTo));
            $where .= " AND (a.visit_date BETWEEN '{$schDateFrom}' AND '{$schDateTo}')";
        }

        $from = "FROM t_trx_schedule a LEFT JOIN t_trx_po_xl c ON c.po_id = a.po_id{$filter_joins}";

        $sort_join_sql = "";
        foreach ($sort_joins as $alias) {
            $sort_join_sql .= " " . $this->list_joins[$alias];
        }

        /*
         * Tahap 1 (subquery p): saring + urutkan + ambil ID satu halaman saja, sekaligus total baris.
         * Tahap 2: lengkapi kolom tampilan hanya untuk baris halaman itu.
         */
        $sql = "SELECT a.visit_id,a.tpd_id,a.visit_type,a.is_code_show,a.security_code_checkin,a.security_code_checkout,a.po_id,a.priority,c.po_no,b.ewo_no,b.po_description,d.link_id,e.site_name as site_fe_name ,e.site_address address_fe,
                    f.site_name as site_ne_name ,g.user_name as \"field_eng\",h.option_label as \"visit_type_label\",
				g.user_id as \"field_eng_id\",f.site_address as address_ne,g.user_name,a.fop_hs_name,a.visit_date as visit_plan,h.option_label,i.region_name as region,a.last_visited_date as actual_date,
                                                            CASE
                                                            WHEN a.status = 0 then 'Not Confirmed'
                                                            WHEN a.status = 1 then 'Confirmed'
                                                            WHEN a.status = 2 then 'Declined'
                                                            WHEN a.status = 3 then 'Visited'
                                                            WHEN a.status = 4 then 'Unschedule'
                                                            WHEN a.status = 5 then 'Re-Schedule' end as desc_status,
                    p.total_rows
                    FROM (SELECT a.visit_id, COUNT(*) OVER() AS total_rows
                            {$from}{$sort_join_sql}
                            {$where}
                            {$order_by}
                            LIMIT {$rows} OFFSET {$offset}) p
                    JOIN t_trx_schedule a ON a.visit_id = p.visit_id
                    LEFT JOIN t_trx_po_xl c ON c.po_id = a.po_id
                    " . implode("\n                    ", $this->list_joins) . "
			{$order_by}";

        $query = $this->db->query($sql);
        $result = $query->result_array();

        if ($result) {
            $total_rows = (int) $result[0]['total_rows'];
        } elseif ($offset > 0) {
            /* Halaman di luar jangkauan: total tetap dihitung agar pager grid benar */
            $total_rows = (int) $this->db->query("SELECT COUNT(*) AS total {$from} {$where}")->row()->total;
        } else {
            $total_rows = 0;
        }

        $ugrup = strtoupper($this->session->userdata('ugrup'));
        $can_atp_pdf = in_array($ugrup, array('ADMIN', 'SYSTEM ADMIN', 'FOP HS', 'SPV HS', 'MS BO PTHS', 'SOM XL'));

        $rows = array();
        foreach ($result as $r) {
            unset($r['total_rows']);

            //* EDITED 25-10-2023 (DISABLED ECRF IF BELOW 43369)
            $disabled = "";
            if ($r['visit_id'] < 43369){
                $disabled = "disabled";
            }

            $r['action'] = '<a href="' . base_url('/visitsummary/detail') . "/" . $this->core->encode($r['visit_id']) . '"
            class="btn btn-default btn-small btn-icon glyphicons pen"> <i></i> Detail</a> <a href="javascript:void(0);" onClick="getECRFappr(' . $r['visit_id'] . ')" class="btn btn-default btn-small btn-icon glyphicons upload '.$disabled.'"> <i></i> ECRF</a>';
            //*
            // $r['action'] = '<a href="' . base_url('/visitsummary/detail') . "/" . $this->core->encode($r['visit_id']) . '"
			// class="btn btn-default btn-small btn-icon glyphicons pen"> <i></i> Detail</a> <a href="javascript:void(0);" onClick="getECRFappr(' . $r['visit_id'] . ')" class="btn btn-default btn-small btn-icon glyphicons upload"> <i></i> ECRF</a>';

            /* Check If Progress Status ==5 , When Prog Status =5, User Enable To Generate Document */
            //if($r['progress_status'] == 5 || $r['progress_status'] == "5"){
            $r['action'] .= '<a href="javascript:void(0);" onClick="Getuserappr(' . $r['visit_id'] . ');"  class="btn-action glyphicons user btn-success" title="Aproval User & Status"><i></i></a>';


            if ($can_atp_pdf) {
                $r['action'] .=
                '<a href="' . base_url('/report/index') . "/" . $this->core->encode($r['visit_id']) . "/" . $this->core->encode($r['visit_type']) . '" target="_blank" class="btn-action glyphicons file btn-success" title="ATP PDF"><i></i></a>
                <a href="' . base_url('/report/report_new/index') . "/" . $this->core->encode($r['visit_id']) . "/" . $this->core->encode($r['visit_type']) . '" target="_blank" class="btn-action glyphicons book btn-warning" title="TEST ATP PDF NEW"><i></i></a>';
            }

            // if ($r['visit_type'] == 109 || $r['visit_type'] == 110 || $r['visit_type'] == 113 || $r['visit_type'] == 114 || $r['visit_type'] == 112 || $r['visit_type'] == 120) {
            $r['action'] .= '<a href="' . base_url('report/export/index') . "/" . $this->core->encode($r['visit_id']) . "/" . $this->core->encode($r['visit_type']) . '" target="_blank" class="btn-action glyphicons file btn-info" title="IMS Data"><i></i></a>';

            // Remark by Ado Ganteng
            // if ($r['visit_type'] == 110) {
            //     $r['action'] .= 'oke';
            // }
            // }

            /* If Code Show is 1 then view security code else hide */

            $r['action'] .= '<a href="' . base_url('/visitsummary/upload_doc') . "/" . $this->core->encode($r['visit_id']) . '" title="Upload Your Data" class="btn-action glyphicons upload btn-info" title="Upload Data"><i></i></a>';
            if ($r['is_code_show'] == 1) {
                $r['code_checkin'] = $r['security_code_checkin'];
                $r['code_checkout'] = $r['security_code_checkout'];
            } else {
                $r['code_checkin'] = '-';
                $r['code_checkout'] = '-';
            }
            /* Kode asli tidak ikut dikirim ke browser; yang boleh tampil sudah ada di code_checkin/out */
            unset($r['security_code_checkin'], $r['security_code_checkout']);
            /* Get Status Approval Order & Status */

            $r['action'] .= '<a href="' . "http://eatp.alita.id:8080/import-generator/public/excel/" . $r['visit_id'] . '" target="_blank" class="btn-action glyphicons file btn-default" title="Export Excel PCQC"><i></i></a>';

            // FOP Attach :: Ilham Sihabudin - 09/10/2022
            $r['action'] .= '<a href="' . base_url('/visitsummary/fop_attach') . "/" . $this->core->encode($r['visit_id']) . '"
			    class="btn-action glyphicons pen btn-default" title="FOP Attach"> <i></i> </a>';

            $rows[] = $r;
        }

        $data['total'] = $total_rows;
        $data['rows'] = $rows;

        return $data;
    }



    public function count_atp_visited()
    {



        $sql = "SELECT COUNT(*) AS total FROM (SELECT DISTINCT ON (c.po_no,a.created_on) 1
                    from t_trx_schedule a
                    left join t_trx_po_xl c on c.po_id = a.po_id
                    WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3) AS t";


        //echo $sql;die;
        $query = $this->db->query($sql);

        $total_rows = (int) $query->row()->total;

        return $total_rows;
    }

    public function count_atp_accepted()
    {



        $sql = "SELECT COUNT(*) AS total FROM (SELECT DISTINCT ON (c.po_no,a.created_on) 1
                    from t_trx_schedule a
                    left join t_trx_po_xl c on c.po_id = a.po_id
                    left join t_trx_schedule_result_approval_sum k on k.visit_id = a.visit_id
                    WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3  and k.acceptance_status = 1) AS t";


        //echo $sql;die;
        $query = $this->db->query($sql);

        $total_rows = (int) $query->row()->total;

        return $total_rows;
    }

    public function count_atp_rejected()
    {



        $sql = "SELECT COUNT(*) AS total FROM (SELECT DISTINCT ON (c.po_no,a.created_on) 1
                    from t_trx_schedule a
                    left join t_trx_po_xl c on c.po_id = a.po_id
                    left join t_trx_schedule_result_approval_sum k on k.visit_id = a.visit_id
                    WHERE a.is_deleted is NULL or a.is_deleted =0 and c.is_deleted = 0 and a.status = 3  and k.acceptance_status = 2) AS t";


        //echo $sql;die;
        $query = $this->db->query($sql);

        $total_rows = (int) $query->row()->total;

        return $total_rows;
    }

    /* Select by ID */
    public function list_upload($id)
    {
        $this->db->where('visit_id', $id);
        return $this->db->get('t_trx_docs_summary')->result();
    }
    public function getSchedule($id)
    {
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

    public function getDetailSummary($visit_id)
    {

        $user_id = $this->session->userdata('user_id');
        //start edited by Hadi 2018-03-29
        //* UPDATED 2023-12-12 (Penambahan rsp.updated_on)
        $sql2 = "SELECT cc.checklist_id, cc.checklist_name, cc.checklist_group, cc.has_photo, cc.total_photo, pr.latitude as lat_chk, pr.longitude as lon_chk,
        cc.mandatory, pr.checklist_result_id, pr.checklist_result pm_result,rsp.photo_name,rsp.created_on, rsp.photo_thumbnail, rsp.updated_on, ttd.image_sign, ttd.latitude as ttdlat, ttd.longitude as ttdlong, fop.fop_photo, fop.latitude as foplat, fop.longitude as foplong, fopvideo.fop_video as fopvideo,
        (
           SELECT ap2.status from t_trx_checklist_result_approval ap2
           where visit_id = {$visit_id} and ap2.user_id = '{$user_id}'
         LIMIT 1
       )as status,
        cc.interface, gp.group_path
       FROM t_trx_schedule_checklist sc
       join t_mtr_checklist cc on cc.checklist_id = sc.checklist_id
       left join (
           select g.checklist_group, f_get_checklistgroup(g.checklist_group) group_path
           from (select distinct c2.checklist_group from t_trx_schedule_checklist s2
                 join t_mtr_checklist c2 on c2.checklist_id = s2.checklist_id
                 where s2.visit_id = $visit_id) g
       ) gp on gp.checklist_group IS NOT DISTINCT FROM cc.checklist_group
       left join t_trx_checklist_result pr on pr.checklist_id = sc.checklist_id
       and sc.visit_id = pr.visit_id
       left join t_trx_checklist_result_photo rsp on rsp.checklist_id = sc.checklist_id
       and sc.visit_id = rsp.visit_id
       left join (
                            select visit_id,
                            sign_type,
                            image_sign,
                            latitude,
                            longitude,
                            created_on
                            from t_trx_schedule_sign
                            where sign_type = 2 and visit_id = $visit_id
                            order by created_on desc
                            limit 1
       ) ttd on sc.visit_id = ttd.visit_id
       left join (
                            SELECT
                                visit_id,
                                fop_photo,
                                latitude,
                                longitude,
                                created_on
                            FROM t_trx_schedule_fop_photo 
                            WHERE visit_id = $visit_id
                            ORDER BY created_on DESC
                            LIMIT 1
                            ) fop on sc.visit_id = fop.visit_id
        left join (
                            SELECT
                                visit_id,
                                fop_video,
                                created_on
                            FROM t_trx_schedule_fop_video 
                            WHERE visit_id = $visit_id
                            ORDER BY created_on DESC
                            LIMIT 1
                            ) fopvideo on sc.visit_id = fopvideo.visit_id
                                            where sc.visit_id = $visit_id 
                                            ORDER BY cc.checklist_group, cc.order_number";
        //end
        /*$sql2 = "SELECT cc.checklist_id, cc.checklist_name, cc.checklist_group, cc.has_photo, cc.total_photo, pr.latitude as lat_chk, pr.longitude as lon_chk,
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
				where sc.visit_id = $visit_id ";*/
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

    public function getApprovalInfo($visit_id)
    {
        $sql = "
				Select * from t_trx_schedule_result_approval
				where visit_id = {$visit_id}
				";
        $res = $this->db->query($sql)->result_array();
        return $res;
    }

    /* Site Properties */

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

    /* Get Image from t_trx_result_photo */

    public function getImageChecklist($visit_id, $chk_id)
    {
        $this->db->where("visit_id", $visit_id);
        $this->db->where("checklist_id", $chk_id);
        $rs = $this->db->get("t_trx_checklist_result_photo")->row();
        return $rs;
    }

    /* Save Reject & Approval Answer */

    //public function saveApprovalAnswer($data, $is_mass) {
    public function saveApprovalAnswer($checklist_id, $data, $is_mass)
    {
        $sql = "";
        $items = "";
        foreach ($checklist_id as $item) {
            $items .= $item . ",";
        }
        $items = substr_replace($items, "", -1);
        //log_message('DEBUG', "items=".$items);

        $user_id = $this->session->userdata("user_id");

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

    public function savePendingItem($data)
    {
        $this->db->trans_begin();

        $this->db->insert('t_trx_schedule_pending_item', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /* Update Pending Item */

    public function updatePendingItem($data, $pending_id)
    {
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

    public function updateResultApproval($data, $visit_id, $user_id)
    {
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

    public function saveApprovalSummary($data)
    {



        $this->db->trans_begin();


        $this->db->insert('t_trx_schedule_result_approval_sum', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /* Function that return count approve with total checklist */

    public function is_approved_all($visit_id, $user_id)
    {
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

    public function getApprovalSummary($user_id, $visit_id)
    {
        $this->db->where("visit_id", $visit_id);
        $this->db->where("user_id", $user_id);
        $this->db->order_by("sum_appr_id", "desc");
        $this->db->limit(1);
        $rs = $this->db->get("t_trx_schedule_result_approval_sum");
        return $rs->row();
    }

    /* Function get all pending item visit & user approval */

    public function getPendingItem($visit_id)
    {
        $this->db->where("visit_id", $visit_id);
        $this->db->where("is_deleted", 0);
        $this->db->order_by("created_on", "DESC");
        $rs = $this->db->get("t_trx_schedule_pending_item");
        return $rs->result();
    }

    /* Soft Delete Pending Items */

    public function delete($pending_id, $data)
    {

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

    public function getApprovalOrder($sch_id)
    {
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

    public function get_appr_list($sch_id)
    {
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

    public function is_eligible_approval($sch_id, $user_id)
    {
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

    public function is_user_before_approval_done($sch_id, $user_id)
    {
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
        $total_chk = $this->db->where("visit_id", $sch_id)->count_all_results("t_trx_schedule_checklist");
        //echo $total_chk."|";
        //echo $user_before_approval->user_id."|";
        if ($user_before_approval) {
            /*$total_approval = $this->db->where("visit_id", $sch_id)
                            ->where("user_id", $user_before_approval->user_id)
                            ->where("status", 1)
                            ->get("t_trx_checklist_result_approval")->num_rows();*/
            //start edited by Hadi 2018-03-21
            $total_approval = (int) $this->db->query(
                "SELECT COUNT(*) AS total FROM (SELECT DISTINCT checklist_id FROM t_trx_checklist_result_approval
                 WHERE visit_id = ? AND user_id = ? AND status = 1) AS t",
                array($sch_id, $user_before_approval->user_id)
            )->row()->total;

            // print_r($total_approval);die();
            //end

            if ($total_chk > $total_approval) {
                $flag_done = false;
            }
            //$flag_done = true;
        }
        /* Else Means Current User, Approval should be Un-Locked */
        return $flag_done;
    }

    public function is_user_layer_3_approval_done($sch_id, $user_id)
    {
        /* default for current user */
        $flag_done = true;
        /* 1. Get User With Order NO-1 from Current User */
        $sql = "SELECT a1.* from t_trx_schedule_result_approval a1
				where a1.order_no = 3
				and a1.visit_id = {$sch_id}
				order by a1.order_no DESC
				LIMIT 1";
        //echo $sql."<br/>";
        $user_before_approval = $this->db->query($sql)->row();

        /* 1. Select Total Checklist fot this Visit */
        $total_chk = $this->db->where("visit_id", $sch_id)->count_all_results("t_trx_schedule_checklist");
        //echo $total_chk."|";
        //echo $user_before_approval->user_id."|";
        if ($user_before_approval) {
            /*$total_approval = $this->db->where("visit_id", $sch_id)
                            ->where("user_id", $user_before_approval->user_id)
                            ->where("status", 1)
                            ->get("t_trx_checklist_result_approval")->num_rows();*/
            //start edited by Hadi 2018-03-21
            $total_approval = (int) $this->db->query(
                "SELECT COUNT(*) AS total FROM (SELECT DISTINCT checklist_id FROM t_trx_checklist_result_approval
                 WHERE visit_id = ? AND user_id = ? AND status = 1) AS t",
                array($sch_id, $user_before_approval->user_id)
            )->row()->total;
            //end
            if ($total_chk != $total_approval) {
                $flag_done = false;
            }
            //$flag_done = true;
        }
        /* Else Means Current User, Approval should be Un-Locked */
        return $flag_done;
    }

    public function countChk($sch_id)
    {
        return $this->db->where("visit_id", $sch_id)->get("t_trx_schedule_checklist")->num_rows();
    }

    /* Public function if current user is the last approval person in visit_id */

    public function is_last_approval($user_id, $sch_id)
    {
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

    public function updatePOLIProgressStatus($po_id, $link_id, $data)
    {

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

    public function countApprovalStatus($visit_id, $user_id, $flag)
    {
        $sql2 = "SELECT * from t_trx_checklist_result_approval where visit_id = {$visit_id}
				and user_id = '{$user_id}' and status ={$flag}";
        $query2 = $this->db->query($sql2);
        return $query2->num_rows();
    }

    public function getScheduleData($visit_id)
    {
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
