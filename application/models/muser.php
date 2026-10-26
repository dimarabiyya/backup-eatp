<?php

class Muser extends CI_Model {
  
  const MOB_USR_PREV_MAIN = 1;
  const MOB_USR_SITE_INSP = 2;


  public function Muser() {
    parent::__construct();
  }

  public function getDetailUser($id) {
    $dblokal = $this->load->database("default", TRUE);
    $q = "SELECT * FROM t_mtr_user WHERE user_id = '$id'";
    $query = $dblokal->query($q);
    return $query->result();
  }

  ############################################

  public function saveData() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('id', 'user_id', 'nama', 'status', 'usergroup', 'reportingto', 'hape', 'email', 'mcpass', 'region', 'cluster', 'cluster_ops', 'cluster_net', 'cluster_com', 'dummyuser', 'internaluser', 'userdiv', 'imei', 'handset_model', 'handset_man', 'mobile_type', 'main_division_id', 'imageSignature');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    if ($id <> "")
      $user_id = $id;
    
	$user_id = str_replace(" ", "", $user_id);
	//revise by hadi, 20180124
    $user_id = strtolower($user_id);
	//
    $email = str_replace(" ", "", $email);
    
	$data = array(
		'user_id' => $user_id,
        'user_name' => $nama,
        'must_change_password' => $mcpass,
        'logon_attempt' => 0,
        'reporting_to' => $reportingto,
        'mobile_number' => $hape,
        'email' => $email,
        'dummy_user' => $dummyuser,
        'internal_user' => $internaluser,
        'status' => $status,
        'main_division_id' => $main_division_id,
		'image_signature' => str_replace(array(' '), '+', $imageSignature)
	);

    $dblokal->trans_begin();

    if ($id == "") {
      $def_pwd = $this->mglobal->showdata("lookup_value", "t_mtr_lookup", array("lookup_key" => 'default_password'), "dblokal");
      $data['user_password'] = md5($def_pwd);
      $data['created_on'] = date("Y-m-d H:i:s");
      $data['created_by'] = $this->session->userdata('user_id');
      $dblokal->insert('t_mtr_user', $data);
      $data['password'] = $def_pwd;
      $this->_email_user($data);
    } else {
      $data['updated_on'] = date("Y-m-d H:i:s");
      $data['updated_by'] = $this->session->userdata('user_id');

      $dblokal->where(array('user_id' => $id));
      $dblokal->update('t_mtr_user', $data);
    }


    if ($mobile_type != 0) {
      $dblokal->delete("t_mtr_user_multi_mobile", array("user_id" => $user_id));


      $mobile['user_id'] = $user_id;
      $mobile['active'] = 1;
      $mobile['created_on'] = date("Y-m-d H:i:s");
      $mobile['created_by'] = $this->session->userdata('user_id');

      if ($mobile_type != 3) {
        $mobile['mobile_app_type'] = $mobile_type;
        $dblokal->insert('t_mtr_user_multi_mobile', $mobile);
      } else {
        $mobile['mobile_app_type'] = 1;
        $dblokal->insert('t_mtr_user_multi_mobile', $mobile);

        $mobile['mobile_app_type'] = 2;
        $dblokal->insert('t_mtr_user_multi_mobile', $mobile);
      }

      $dblokal->where(array('user_id' => $user_id));
      $dblokal->update('t_mtr_user', array("is_mobile_user" => 1));



      $dblokal->delete("t_mtr_handset", array("user_id" => $user_id));
      $handset = array("user_id" => $user_id,
          "handset_model" => $handset_model,
          "handset_man" => $handset_man,
          //"imei"=>$imei,
          "imei" => strtoupper($imei),
          "imsi" => 000000000,
          "msisdn" => $hape,
          "istatus" => 1,
          "created_by" => $this->session->userdata('user_id'),
          "created_on" => date("Y-m-d G:i:s"),
      );
      $dblokal->insert('t_mtr_handset', $handset);
    }



    // Data User Group/Role------------------------------------------------------------------
    $dblokal->delete("t_mtr_user_multi_role", array("user_id" => $user_id));
    foreach ($usergroup as $index => $value) {
      $dblokal->insert("t_mtr_user_multi_role", array("user_id" => $user_id, "user_group_id" => $index, 'created_by' => $this->session->userdata('user_id')));
    }

    // Data Divisi/Group------------------------------------------------------------------
    $dblokal->delete("t_mtr_user_multi_division", array("user_id" => $user_id));
    foreach ($userdiv as $index => $value) {
      $dblokal->insert("t_mtr_user_multi_division", array("user_id" => $user_id, "user_div_id" => $index, 'created_by' => $this->session->userdata('user_id')));
    }

    // Data Teritori------------------------------------------------------------------
    $dblokal->delete("t_mtr_user_territory", array("user_id" => $user_id));
    foreach ($region as $index => $value) {
      $data_region = array('user_id' => $user_id,
          'nation_id' => 1,
          'region_id' => $index,
          'created_by' => $this->session->userdata('user_id'),
          'created_on' => date("Y-m-d H:i:s"));
      $dblokal->insert("t_mtr_user_territory", $data_region);
    }

    if ($dblokal->trans_status() === FALSE) {
      $dblokal->trans_rollback();
    } else {
      $dblokal->trans_commit();
    }
  }

  function _email_user($data) {
    if (isset($data['internal_user']) && $data['internal_user'] == 1) {
      $getMail = $this->mglobal->getEmailDefaultValue('USR_REG_IN');
    } else {
      $getMail = $this->mglobal->getEmailDefaultValue('USR_REG_EX');
    }
    $mail = json_decode($getMail);
    $arr_parameter = explode(',', $mail->parameter);
    $first = array();
    $replace = array();
    $content = $mail->content;
    foreach ($arr_parameter as $key => $value) {
      $val = str_replace('[', '', $value);
      $val = str_replace(']', '', $val);
      if (isset($data[trim($val)])) {
        $content = str_replace(trim($value), $data[trim($val)], $content);
      }
    }


    $content = str_replace("'", "''", $content);
    $this->raa->addSingleRecipient('to', $data['email']);
    $this->mglobal->setEmailSchedule(1, date("Y-m-d"), date("G:i:s"), $mail->subject, $content, 1);
  }

  public function deleteData() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('id', 'curpage');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    //revise by hadi, 20180124
	$dblokal->delete("t_mtr_user", array("user_id" => $id));
	//
	$dblokal->trans_begin();

    //$dblokal->where(array('user_id' => $id));
    //$dblokal->update('t_mtr_user', array('status' => -1));

    $dblokal->where(array('pic_smart' => $id));
    $dblokal->update('t_mtr_site', array('pic_smart' => ''));

    if ($dblokal->trans_status() === FALSE) {
      $dblokal->trans_rollback();
    } else {
      $dblokal->trans_commit();
    }
  }

  function bounceUser() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('id', 'curpage');
    $user_id = $this->session->userdata("user_id");
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $dblokal->trans_begin();

    $dblokal->where(array('user_id' => $id));
    $dblokal->update('t_mtr_user', array('is_login' => 0));

    $dblokal->where(array('user_id' => $id));
    $dblokal->update('t_trx_last_activity', array('elapsedTime' => 0));

    if ($dblokal->trans_status() === FALSE) {
      $dblokal->trans_rollback();
    } else {
      $dblokal->trans_commit();
      $this->dblog->AuditTrail('user_logout', "force logout by $user_id", "web", $id);
    }
  }

  /* Kolom grid User Management yang boleh dipakai untuk sort => kolom t_mtr_user */
  private $user_list_sortable = array(
      'user_name' => 'user_name',
      'user_id' => 'user_id',
      'mobile_number' => 'mobile_number',
      'email' => 'email',
      'status_name' => 'status',
      'date_created' => 'created_on',
      'login_status' => 'is_login',
  );

  /*
   * Kolom t_mtr_user untuk daftar. user_password, mobile_token dan image_signature
   * (gambar tanda tangan) sengaja tidak ikut: tidak dipakai grid dan tidak boleh sampai ke browser.
   */
  private $user_list_columns = array('user_id', 'user_name', 'user_group_id', 'must_change_password', 'logon_attempt',
      'reporting_to', 'mobile_number', 'email', 'status', 'created_by', 'created_on', 'updated_by', 'updated_on',
      'is_allowed_time', 'last_time_change_password', 'is_login', 'join_date', 'resign_date', 'dummy_user',
      'internal_user', 'is_mobile_user', 'main_division_id');

  /*
   * Daftar User Management. Tahap 1 (page): saring + urutkan + ambil satu halaman dari t_mtr_user.
   * Tahap 2: role, region, divisi, mobile dan last login dihitung hanya untuk user di halaman itu,
   * bukan untuk semua user seperti sebelumnya.
   */
  public function loadData($d = NULL, $sort = 'user_name', $order = 'asc', $limit = 0, $offset = 0) {
    $dblokal = $this->load->database("default", TRUE);

    $sort = isset($this->user_list_sortable[$sort]) ? $this->user_list_sortable[$sort] : 'user_name';
    $order = strtolower($order) == 'desc' ? 'DESC' : 'ASC';
    $paging = ((int) $limit > 0) ? " LIMIT " . (int) $limit . " OFFSET " . max(0, (int) $offset) : "";
    $in_page = "IN (SELECT user_id FROM page)";

    if ($this->_hasLogUserIndex($dblokal)) {
      $last_login_select = "(SELECT to_char(date_trunc('second', MAX(l.created_on)), 'DD/MM/YYYY HH24:MI:SS') FROM t_log l WHERE l.user_id = page.user_id) AS last_login";
      $last_login_join = "";
    } else {
      $last_login_select = "(to_char(date_trunc('second', login.last_login), 'DD/MM/YYYY HH24:MI:SS')) AS last_login";
      $last_login_join = "LEFT JOIN (SELECT user_id, MAX(created_on) AS last_login FROM t_log WHERE user_id $in_page GROUP BY user_id) as login ON login.user_id=page.user_id";
    }

    $q = "WITH page AS (
				SELECT usr." . implode(', usr.', $this->user_list_columns) . "
				" . $this->_userListFrom($dblokal, $d) . "
				ORDER BY usr.$sort $order, usr.user_id $order $paging
			)
			SELECT page.*,(to_char(page.created_on, 'DD/MM/YYYY')) AS date_created, $last_login_select, CASE WHEN mobile_name IS NOT NULL THEN 'Yes' ELSE 'No' END AS mobile_name, th.imei, grp.user_group_name, grp.user_group_id_all, stt.status_name, region.region_ids, cluster.cluster_id, divisi.div_id
			FROM page
			LEFT JOIN (SELECT user_id, array_to_string(array_agg(user_group_name), ', ') AS user_group_name
				, array_to_string(array_agg(t_mtr_user_group.user_group_id), ', ') AS user_group_id_all
					FROM t_mtr_user_multi_role, t_mtr_user_group
					WHERE t_mtr_user_multi_role.user_group_id=t_mtr_user_group.user_group_id
						AND t_mtr_user_multi_role.user_id $in_page
					GROUP BY t_mtr_user_multi_role.user_id) as grp ON page.user_id = grp.user_id
			LEFT JOIN (select user_id, array_to_string(array_agg(region_id), ', ') AS region_ids from t_mtr_user_territory WHERE user_id $in_page GROUP BY user_id) as region ON region.user_id=page.user_id
			LEFT JOIN (select user_id, array_to_string(array_agg(cluster_ops_id), ', ') AS cluster_id from t_mtr_user_territory WHERE cluster_ops_id IS NOT NULL AND user_id $in_page GROUP BY user_id) as cluster ON cluster.user_id=page.user_id
			LEFT JOIN (select user_id, array_to_string(array_agg(user_div_id), ', ') AS div_id from t_mtr_user_multi_division WHERE user_id $in_page GROUP BY user_id) as divisi ON divisi.user_id=page.user_id
			LEFT JOIN t_mtr_user_status as stt ON page.status = stt.status_id
			$last_login_join
			LEFT JOIN (SELECT user_id, array_to_string(array_agg(lookup_desc), ', ') AS mobile_name
				, array_to_string(array_agg(lookup_value), ', ') AS mobile_id
					FROM t_mtr_user_multi_mobile, t_mtr_lookup
					WHERE t_mtr_user_multi_mobile.mobile_app_type=t_mtr_lookup.lookup_value::SMALLINT
						and lookup_key = 'mobile_app_type'
						AND t_mtr_user_multi_mobile.user_id $in_page
					GROUP BY t_mtr_user_multi_mobile.user_id) as mob ON page.user_id = mob.user_id
			LEFT JOIN (select * from t_mtr_handset where istatus=1) as th on th.user_id = page.user_id
			ORDER BY page.$sort $order, page.user_id $order ";

    $query = $dblokal->query($q);
    return $query;
  }

  /* Jumlah baris loadData(): cukup dari t_mtr_user + tabel yang dipakai filter */
  public function countData($d = NULL, $sort = 'user_name', $order = 'asc') {
    $dblokal = $this->load->database("default", TRUE);
    return (int) $dblokal->query("SELECT COUNT(*) AS total " . $this->_userListFrom($dblokal, $d))->row()->total;
  }

  /*
   * t_log (log audit) sangat besar. "Last login" diambil per user lewat indeks
   * idx_log_user_created (sql/optimasi_index_postgres.sql). Selama indeks itu belum ada
   * atau belum selesai dibuat, t_log dibaca sekali hanya untuk user di halaman yang tampil.
   */
  private function _hasLogUserIndex($dblokal) {
    static $has_index = NULL;
    if ($has_index === NULL) {
      $has_index = $dblokal->query("SELECT 1 FROM pg_class c JOIN pg_index i ON i.indexrelid = c.oid
        WHERE c.relname = 'idx_log_user_created' AND i.indisvalid AND i.indisready")->num_rows() > 0;
    }
    return $has_index;
  }

  /* FROM + WHERE daftar user (alias usr) sesuai filter q / region / role dari grid */
  private function _userListFrom($dblokal, $d) {
    $join = "";
    $where = "";
    if (isset($d['q']) && trim($d['q']) !== '') {
      $q = trim($d['q']);
      if ($q == 'online') {
        $where = " AND usr.is_login = 1";
      } elseif ($q == 'offline') {
        $where = " AND usr.is_login = 0";
      } else {
        /* % dan _ yang diketik dicari apa adanya (backslash = karakter escape LIKE bawaan PostgreSQL) */
        $like = "upper('%" . $dblokal->escape_str(str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $q)) . "%')";
        /* Role & status dicari di tabelnya sendiri lalu dicocokkan lewat ID (1 baris per user) */
        $join = "
			LEFT JOIN (SELECT DISTINCT mr.user_id FROM t_mtr_user_multi_role mr
					JOIN t_mtr_user_group ug ON ug.user_group_id = mr.user_group_id
					WHERE upper(ug.user_group_name) LIKE $like) as q_grp ON q_grp.user_id = usr.user_id
			LEFT JOIN (SELECT DISTINCT status_id FROM t_mtr_user_status WHERE upper(status_name) LIKE $like) as q_stt ON q_stt.status_id = usr.status";
        $where = " AND (upper(usr.user_id) LIKE $like
					OR upper(usr.user_name) LIKE $like
					OR upper(usr.mobile_number) LIKE $like
					OR upper(usr.email) LIKE $like
					OR q_grp.user_id IS NOT NULL
					OR q_stt.status_id IS NOT NULL
				)";
      }
    }

    //* UPDATED 20-10-23 (Filter region milik user yang login dinonaktifkan agar semua user tampil)
    // Bila diaktifkan lagi: batasi dengan t_mtr_user_territory.region_id IN (region user yang login)
    //*

    /* Filter region / role: cocok persis dengan ID-nya (bukan potongan teks, mis. role 1 tidak ikut 10-19) */
    if (isset($d['region']) && $d['region'] != 'ALL') {
      $where .= ctype_digit((string) $d['region'])
        ? " AND usr.user_id IN (SELECT user_id FROM t_mtr_user_territory WHERE region_id = " . (int) $d['region'] . ")"
        : " AND FALSE";
    }
    if (isset($d['role']) && $d['role'] != 'ALL') {
      $where .= ctype_digit((string) $d['role'])
        ? " AND usr.user_id IN (SELECT user_id FROM t_mtr_user_multi_role WHERE user_group_id = " . (int) $d['role'] . ")"
        : " AND FALSE";
    }

    return "FROM t_mtr_user as usr $join
			WHERE usr.status IN(0,1,2)
			$where";
  }

  public function loadDataUserByID($id) {
    $dblokal = $this->load->database("default", TRUE);

    $query = $dblokal->query("
		SELECT
			usr.*,
			grp.user_group_name,
			grp.user_group_id_all,
			stt.status_name,
			region.region_id,
			cluster.cluster_id,
			divisi.div_id,
			multi_mobile.mobile_app_type,
			th.handset_model,
			th.handset_man,
			th.imei
		FROM
			t_mtr_user AS usr
		LEFT JOIN (
			SELECT
				user_id,
				array_to_string(
					ARRAY_AGG (user_group_name),
					', '
				) AS user_group_name,
				array_to_string(
					ARRAY_AGG (
						t_mtr_user_group.user_group_id
					),
					', '
				) AS user_group_id_all
			FROM
				t_mtr_user_multi_role,
				t_mtr_user_group
			WHERE
				t_mtr_user_multi_role.user_group_id = t_mtr_user_group.user_group_id
			GROUP BY
				t_mtr_user_multi_role.user_id
		) AS grp ON usr.user_id = grp.user_id
		LEFT JOIN (
			SELECT
				user_id,
				array_to_string(ARRAY_AGG(region_id), ', ') AS region_id
			FROM
				t_mtr_user_territory
			WHERE
				cluster_ops_id IS NULL
			GROUP BY
				user_id
		) AS region ON region.user_id = usr.user_id
		LEFT JOIN (
			SELECT
				user_id,
				array_to_string(
					ARRAY_AGG (cluster_ops_id),
					', '
				) AS cluster_id
			FROM
				t_mtr_user_territory
			WHERE
				cluster_ops_id IS NOT NULL
			GROUP BY
				user_id
		) AS cluster ON cluster.user_id = usr.user_id
		LEFT JOIN (
			SELECT
				user_id,
				array_to_string(
					ARRAY_AGG (user_div_id),
					', '
				) AS div_id
			FROM
				t_mtr_user_multi_division
			GROUP BY
				user_id
		) AS divisi ON divisi.user_id = usr.user_id
		LEFT JOIN t_mtr_user_status AS stt ON usr.status = stt.status_id
		LEFT JOIN (
			SELECT
				user_id,
				array_to_string(
					ARRAY_AGG (mobile_app_type),
					', '
				) AS mobile_app_type
			FROM
				t_mtr_user_multi_mobile
			GROUP BY
				user_id
		) AS multi_mobile ON multi_mobile.user_id = usr.user_id
		LEFT JOIN (
			SELECT
				*
			FROM
				t_mtr_handset
			WHERE
				istatus = 1
		) AS th ON th.user_id = usr.user_id
		WHERE
			usr.user_id = '{$id}';
	");

    $fields = $query->list_fields();
    $q = $query->result();
    
	$hasil = array();
    foreach ($q as $r)
	{
		$r->image_signature = pg_unescape_bytea ($r->image_signature);
		
		foreach ($fields as $field)
		{
			$hasil['hasil'][$field] = $r->$field;
		}
    }
	
    $hasil['status'] = 'OK';
    
	echo json_encode($hasil, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
  }

  ############################################

  public function updateAttemp($at = 0, $user = "") {
    $dblokal = $this->load->database("default", TRUE);
    if ($user <> "")
      $this->session->userdata('user_id');

    $dblokal->trans_begin();

    $dblokal->where(array('user_id' => $user));
    $dblokal->update('t_mtr_user', array('logon_attempt' => $at));

    if ($dblokal->trans_status() === FALSE) {
      $dblokal->trans_rollback();
    } else {
      $dblokal->trans_commit();
    }
  }

  public function resetPassword() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('id', 'curpages');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $def_pwd = $this->mglobal->showdata("lookup_value", "t_mtr_lookup", array("lookup_key" => 'default_password'), "dblokal");

    $dblokal->trans_begin();

    $dblokal->where(array('user_id' => $id));
    $dblokal->update('t_mtr_user', array('logon_attempt' => 0, 'must_change_password' => 1, 'user_password' => md5($def_pwd)));

    if ($dblokal->trans_status() === FALSE) {
      $dblokal->trans_rollback();
    } else {
      $dblokal->trans_commit();
    }
  }

  public function savePassword() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('new_password');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $user = $this->session->userdata('user_id');
    $cp = 0;
    if ($user == "") {
      $user = $this->session->userdata('forgot_step_2');
      $cp = 1;
    }

    $dblokal->trans_begin();

    $dblokal->where(array('user_id' => $user));
    $dblokal->update('t_mtr_user', array('must_change_password' => $cp, 'user_password' => md5($new_password)));

    if ($dblokal->trans_status() === FALSE) {
      $dblokal->trans_rollback();
    } else {
      $dblokal->trans_commit();
    }
  }

  ########################################################################

  function getUserGroup($id = "") {
    $dblokal = $this->load->database("default", TRUE);
    if ($id <> "") {
      $dblokal->where(array("user_id" => $id));
    }
    $dblokal->order_by('user_group_name');
    $query = $dblokal->get("t_mtr_user_group");
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return array();
    }
  }

  function getUserDivision() {
    $dblokal = $this->load->database("default", TRUE);

    $dblokal->where('status', 1);
    $dblokal->order_by('user_div_name', 'asc');
    $query = $dblokal->get("t_mtr_user_division");
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return array();
    }
  }

  function getUserReportingTo($id = 1) {
    //input: usergroupid parentid dari groupid
    //diproses mendapatkan  parentid dari groupid

    $dblokal = $this->load->database("default", TRUE);
    $pid = $this->mglobal->showdata("parent_id", "t_mtr_user_group", array('user_group_id' => $id), "dblokal");
    $sql = "SELECT * from t_mtr_user
                        WHERE user_group_id = '$pid' AND status = 1
                        ORDER BY user_name ASC";
    $query = $dblokal->query($sql);
    $hasil = "";
    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $hasil['data'][] = array(
            'user_id' => $row->user_id,
            'user_name' => $row->user_name
        );
      }
      echo json_encode($hasil, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    } else {
      echo"";
    }
  }

  function getUserComboStatus() {
    return $this->db->get('v_combo_user_status')->result_array();
  }

  ########################################################################
  //Sec.Question

  function getSecurityQuestion() {
    $dblokal = $this->load->database("default", TRUE);
    $q = "SELECT * FROM t_mtr_security_question WHERE user_id = '" . $this->session->userdata('user_id') . "'";
    $query = $dblokal->query($q);
    return $query->result();
  }

  public function saveSecurityQuestion() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('question1', 'question2', 'answer1', 'answer2');
    foreach ($input as $val)
      $data[$val] = $this->input->post($val);

    $dblokal->trans_begin();
    $id = $this->mglobal->showdata('user_id', 't_mtr_security_question', array('user_id' => $this->session->userdata('user_id')));
    if ($id == "") {
      $data['created_on'] = date("Y-m-d H:i:s");
      $data['created_by'] = $this->session->userdata('user_id');
      $data['user_id'] = $this->session->userdata('user_id');
      $dblokal->insert('t_mtr_security_question', $data);
    } else {
      $data['updated_on'] = date("Y-m-d H:i:s");
      $data['updated_by'] = $this->session->userdata('user_id');

      $dblokal->where(array('user_id' => $id));
      $dblokal->update('t_mtr_security_question', $data);
    }

    if ($dblokal->trans_status() === FALSE) {
      $dblokal->trans_rollback();
    } else {
      $dblokal->trans_commit();
    }
  }

  ########################################################################

  function update_last_activity($user_id, $et = 20, $current_url = '') {
    date_default_timezone_set('Asia/Jakarta');
    $query = $this->db->get_where('t_trx_last_activity', array('user_id' => $user_id));

    if ($current_url != '')
      $url = str_replace(base_url(), "", $current_url);
    else
      $url = base_url();

    if ($query->num_rows() == 1) {
      //update
      if ($query->row()->elapsedTime > $et) {
        $et = $query->row()->elapsedTime;
      }

      $et = $et - 3;
      $is_login = $this->mglobal->showdata("is_login", "t_mtr_user", array('user_id' => $user_id), "dblokal");
      if ($is_login == 0)
        $et = 0;

      $this->db->where('user_id', $user_id);
      $this->db->update('t_trx_last_activity', array('elapsedTime' => $et, 'ip_address' => $this->input->ip_address(), 'lastActivity' => date('H:i:s'), 'lastActivityDate' => date('Y-m-d'), 'lastUrl' => $url));
    }
    else {
      $this->db->insert('t_trx_last_activity', array('user_id' => $user_id, 'ip_address' => $this->input->ip_address(), 'elapsedTime' => $et, 'lastActivity' => date('H:i:s'), 'lastActivityDate' => date('Y-m-d'), 'lastUrl' => $url));
    }

    if ($et < 0)
      $et = 0;
    echo json_encode(array(
        'loggedin' => 'true',
        'last_restricted' => $this->getLastLoginDoubleUser(),
        'last_time' => $et
    ));
    return;
  }

  function setLastLoginDoubleUser($user_id) {
    $this->db->insert('t_trx_last_login_restricted', array('user_id' => $user_id, 'ip_address' => $this->input->ip_address(), 'istatus' => 1, 'ts' => date('Y-m-d H:i:s')));
  }

  function getLastLoginDoubleUser() {
    $user_id = $this->session->userdata('user_id');
    $query = $this->db->get_where('t_trx_last_login_restricted', array('user_id' => $user_id, 'istatus' => 1));
    $hasil = "";
    if ($query->num_rows > 0) {
      $hasil = "<ul>";
      foreach ($query->result() as $r) {
        $hasil .= "<li>Tanggal: $r->ts dengan IP Address: $r->ip_address </li>";
      }
      $hasil .= "</ul>";
      $this->db->query("UPDATE t_trx_last_login_restricted SET istatus=0 WHERE user_id = '$user_id'");
    }
    return $hasil;
  }

  function getUserRegion($id) {
    $dbLK = $this->load->database("default", TRUE);
    $query = $dbLK->query("select a.user_id, b.region_id, reg.region_name from t_mtr_user a
					    left join t_mtr_user_territory b on a.user_id=b.user_id
					    left join t_mtr_region reg on b.region_id=reg.region_id
					    where a.user_id='$id'");

    $data = array('region_id' => '', 'region_name' => '');
    if ($query->num_rows > 0) {
      $data['region_id'] = $query->row()->region_id;
      $data['region_name'] = $query->row()->region_name;
    }
    return $data;
  }

  function getUserMultiRegion($id) {
    $dbLK = $this->load->database("default", TRUE);
    $query = $dbLK->query("select distinct b.region_id, reg.region_name, reg.order_no  from t_mtr_user a
					    join t_mtr_user_territory b on a.user_id=b.user_id
					    join t_mtr_region_xl reg on b.region_id=reg.region_id
					    where a.user_id='$id' and reg.is_deleted = 0 order by reg.order_no ASC");

    $data = array();
    $i = 0;
    if ($query->num_rows > 0) {
      foreach ($query->result() as $r) {
        $data[$i]['region_id'] = $r->region_id;
        $data[$i]['region_name'] = $r->region_name;
        $user_cluster = $this->muser->get_cluster_region($id, $r->region_id); //add by iwa
        $data[$i]['cluster'] = $user_cluster;
        $i++;
      }
    }
    return $data;
  }

  function get_cluster_region($user_id, $region_id) {
    $dbLK = $this->load->database("default", TRUE);
    $query = $dbLK->query("select a.user_id, b.cluster_ops_id, cop.cluster_name from t_mtr_user a
					    left join t_mtr_user_territory b on a.user_id=b.user_id
					    left join t_mtr_cluster_ops cop on b.cluster_ops_id=cop.cluster_ops_id
					    where a.user_id='$user_id' AND cop.region_id='$region_id'");

    $data = array();
    $i = 0;
    if ($query->num_rows > 0) {
      foreach ($query->result() as $r) {
        $data[$i]['cluster_ops_id'] = $r->cluster_ops_id;
        $data[$i]['cluster_name'] = $r->cluster_name;
        $i++;
      }
    }
    return $data;
  }

  function getUserMultiClusterOps($id) {
    $dbLK = $this->load->database("default", TRUE);
    $query = $dbLK->query("select a.user_id, b.cluster_ops_id, cop.cluster_name from t_mtr_user a
					    left join t_mtr_user_territory b on a.user_id=b.user_id
					    left join t_mtr_cluster_ops cop on b.cluster_ops_id=cop.cluster_ops_id
					    where a.user_id='$id'");

    $data = array();
    $i = 0;
    if ($query->num_rows > 0) {
      foreach ($query->result() as $r) {
        $data[$i]['cluster_ops_id'] = $r->cluster_ops_id;
        $data[$i]['cluster_name'] = $r->cluster_name;
        $i++;
      }
    }
    return $data;
  }

  function getUserMultiGroup($id) {
    $dbLK = $this->load->database("default", TRUE);
    $query = $dbLK->query("select a.user_id, b.user_group_id, reg.user_group_name from t_mtr_user a
					    left join t_mtr_user_multi_role b on a.user_id=b.user_id
					    left join t_mtr_user_group reg on b.user_group_id=reg.user_group_id
					    where a.user_id='$id'");

    $data = array();
    $i = 0;
    if ($query->num_rows > 0) {
      foreach ($query->result() as $r) {
        $data[$i]['user_group_id'] = $r->user_group_id;
        $data[$i]['user_group_name'] = $r->user_group_name;
        $i++;
      }
    }
    return $data;
  }
  
  function getUserMultiGroupx($id) {
    $dbLK = $this->load->database("default", TRUE);
    $query = $dbLK->query("select a.user_id, b.user_group_id, reg.user_group_name from t_mtr_user a
					    left join t_mtr_user_multi_role b on a.user_id=b.user_id
					    left join t_mtr_user_group reg on b.user_group_id=reg.user_group_id
					    where a.user_id='$id'")->row();
 
    return $query;
  }

  function getUserMultiDivision($id) {
    $dbLK = $this->load->database("default", TRUE);
    $query = $dbLK->query("select a.user_id, b.user_div_id, reg.user_div_name from t_mtr_user a
					    left join t_mtr_user_multi_division b on a.user_id=b.user_id
					    left join t_mtr_user_division reg on b.user_div_id=reg.user_div_id
					    where a.user_id='$id'");

    $data = array();
    $i = 0;
    if ($query->num_rows > 0) {
      foreach ($query->result() as $r) {
        $data[$i]['user_div_id'] = $r->user_div_id;
        $data[$i]['user_div_name'] = $r->user_div_name;
        $i++;
      }
    }
    return $data;
  }

  function getUserCluster($id) {
    $dblokal = $this->load->database("default", TRUE);
    $query = $dblokal->query("select a.user_id, b.region_id, b.cluster_ops_id, b.cluster_net_id, b.cluster_com_id,
						nt.cluster_name as cluster_net_name, cm.cluster_name as cluster_com_name, op.cluster_name as cluster_ops_name
					from t_mtr_user a
					left join t_mtr_user_territory b on a.user_id=b.user_id
					left join t_mtr_cluster_net nt on b.cluster_net_id=nt.cluster_net_id 
					left join t_mtr_cluster_com cm on b.cluster_com_id=cm.cluster_com_id 
					left join t_mtr_cluster_ops op on b.cluster_ops_id=op.cluster_ops_id 
					where a.user_id='$id'");

    $data = array('cluster_net_id' => '', 'cluster_net_name' => '', 'cluster_ops_id' => '', 'cluster_ops_name' => '', 'cluster_com_id' => '', 'cluster_com_name' => '');

    if ($query->num_rows > 0) {
      $data = array('cluster_net_id' => $query->row()->cluster_net_id,
          'cluster_net_name' => $query->row()->cluster_net_name,
          'cluster_ops_id' => $query->row()->cluster_ops_id,
          'cluster_ops_name' => $query->row()->cluster_ops_name,
          'cluster_com_id' => $query->row()->cluster_com_id,
          'cluster_com_name' => $query->row()->cluster_com_name);
    }
    return $data;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getRegionByID($idRegion = "") {
    $dblokal = $this->load->database("default", TRUE);
    $query = $dblokal->query("select * from t_mtr_region WHERE status=1 " . ($idRegion == "" ? "" : " AND region_id = '$idRegion' ") . " ORDER BY region_id");
    return $query->result();
  }

  function getRegionByUserRegion() {
    //$region = array();
    //foreach ($this->session->userdata('user_region') as $row) {
    //	$region[]=(object)array('region_id'=>$row['region_id'],'region_name'=>$row['region_name']);
    //}		
    //$region=(object)$region;
    //return $region;
    //edited by Andedi 12-08-2014 17:00
    $dblokal = $this->load->database("default", TRUE);

    //$dblokal->order_by('user_group_name');
    $dblokal->order_by('region_name', 'asc');
    $dblokal->where('is_deleted !=', 1);
    $query = $dblokal->get("t_mtr_region_xl");
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return array();
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getClusterOpsByRegion($idRegion = "") {
    $dblokal = $this->load->database("default", TRUE);
    $query = $dblokal->query("select * from t_mtr_cluster_ops WHERE status=1 " . ($idRegion == "" ? "" : " AND region_id = '$idRegion' "));
    return $query->result();
  }

  function getClusterOpsMultiRegion($idRegion = array()) {
    $dblokal = $this->load->database("default", TRUE);
    $where = "";
    if (count($idRegion) > 0) {
      $where = " AND t_mtr_region.region_id IN('" . implode("','", $idRegion) . "') ";
    }
    $query = $dblokal->query("select t_mtr_cluster_ops.*, t_mtr_region.region_name from t_mtr_cluster_ops, t_mtr_region
					where t_mtr_cluster_ops.region_id = t_mtr_region.region_id
						AND t_mtr_cluster_ops.status=1 
						$where ");
    return $query->result();
  }

  function getClusterComByRegion($idRegion = "") {
    $dblokal = $this->load->database("default", TRUE);
    $query = $dblokal->query("select * from t_mtr_cluster_com WHERE status=1 " . ($idRegion == "" ? "" : " AND region_id = '$idRegion' "));
    return $query->result();
  }

/* di disable by iwa 20170123
  function getClusterNetByRegion($idRegion = "") {
    //$dblokal = $this->load->database("default", TRUE);
    //$query = $dblokal->query("select * from t_mtr_cluster_net WHERE status=1 " . ($idRegion == "" ? "" : " AND region_id = '$idRegion' "));
    //return $query->result();
	return null;
  }
*/
  function get_user_territory_type_id($usergroup) {
    $this->db->select('territory_type_id');
    $query = $this->db->get_where('t_mtr_user_group', array('user_group_id' => $usergroup));
    if ($query->num_rows > 0) {
      return $query->row()->territory_type_id;
    }
    return;
  }

  ##########################################################

  public function saveEditProfile() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('nama', 'email', 'hape');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $data = array('user_name' => $nama,
        'mobile_number' => $hape,
        'email' => $email);

    $data['updated_on'] = date("Y-m-d H:i:s");
    $data['updated_by'] = $this->session->userdata('user_id');

    $dblokal->where(array('user_id' => $this->session->userdata('user_id')));
    $dblokal->update('t_mtr_user', $data);

    if ($dblokal->trans_status() === FALSE) {
      $dblokal->trans_rollback();
    } else {
      $dblokal->trans_commit();
    }
  }

  function updatePasswordCode($id) {
    $dblokal = $this->load->database("default", TRUE);

    $digits = 6;
    $kode = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

    $data = array('password_code' => $kode);

    $dblokal->where(array('user_id' => $id));
    $dblokal->update('t_mtr_user', $data);

    if ($dblokal->trans_status() === FALSE) {
      $dblokal->trans_rollback();
    } else {
      $dblokal->trans_commit();
    }
  }

  //////////////mobile user
  function getMobileType() {
    $query = $this->db->query("SELECT lookup_value, lookup_desc
					    FROM t_mtr_lookup
					    WHERE lookup_key = 'mobile_app_type';");
    return $query->result();
  }

  function saveDataMobileUser() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('mobile_type', 'user_id');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $dblokal->trans_begin();
    $user = explode(",", $user_id);
    //delete semua dulu
    //$dblokal->delete("t_mtr_user_multi_mobile",array("mobile_app_type"=>$mobile_type));
    //$dblokal->query("UPDATE t_mtr_user SET is_mobile_user = 0 ");
    for ($i = 0; $i <= count($user) - 2; $i++) {

      $dblokal->query("INSERT INTO t_mtr_user_multi_mobile
						(user_id, mobile_app_type, created_by, created_on, active)
					    SELECT '" . $user[$i] . "', '$mobile_type', '" . $this->session->userdata('user_id') . "','" . date('Y-m-d H:i:s') . "','1'
					    WHERE
						NOT EXISTS (
						    SELECT user_id FROM t_mtr_user_multi_mobile
						    WHERE user_id = '" . $user[$i] . "' and mobile_app_type='$mobile_type'
						);");
    }
    $dblokal->query("UPDATE t_mtr_user SET is_mobile_user = 1
				WHERE user_id IN ( select user_id from t_mtr_user_multi_mobile ) ");

    if ($dblokal->trans_status() === FALSE) {
      $dblokal->trans_rollback();
    } else {
      $dblokal->trans_commit();
    }
  }

  function saveMobileUserManagement($post) {
    $this->db->trans_start();

    foreach ($post['user_id'] as $key => $value) {
      //Delete from multi mobile first
      $this->db->where('user_id', $value);
      $this->db->delete('t_mtr_user_multi_mobile');

      $this->db->where('user_id', $value);
      $this->db->delete('t_mtr_handset');

      $multimobile = array(
          'user_id' => $value,
          "active" => 1,
          'created_by' => $this->session->userdata('user_id'),
          'created_on' => date('Y-m-d H:i:s')
      );

      //insert to t_mtr_user_multi_mobile
      if ($post['mobile_type'] == 3) {
        $multimobile["mobile_app_type"] = self::MOB_USR_PREV_MAIN;
        $this->db->insert('t_mtr_user_multi_mobile', $multimobile);
        $multimobile["mobile_app_type"] = self::MOB_USR_SITE_INSP;
        $this->db->insert('t_mtr_user_multi_mobile', $multimobile);
      } else {
        $multimobile["mobile_app_type"] = $post['mobile_type'];
        $this->db->insert('t_mtr_user_multi_mobile', $multimobile);
      }

      // insert t_mtr_handset
      $handset = array('user_id' => $value,
          "istatus" => 1,
          "imsi" => "",
          "msisdn" => $post['mdn'][$key],
          "imei" => strtoupper($post['meid'][$key]),
          "handset_model" => $post['model'][$key],
          "handset_man" => $post['manufacture'][$key],
      );
      $this->mglobal->saveData('t_mtr_handset', $handset);

      // Update user
      $user = array("user_id" => $value, "is_mobile_user" => 1, "mobile_number" => $post['mdn'][$key]);
      $this->mglobal->saveData("t_mtr_user", $user, 'user_id');
    }

    $this->dblog->AuditTrail('usermobile-create', $post, "web");
    $this->db->trans_complete();

    return $this->db->trans_status();
  }

  function saveMobileUserSingle() {
    $d = $this->input->post();

    $this->db->trans_begin();

    $this->db->where('user_id', $d['user_id']);
    $this->db->delete('t_mtr_user_multi_mobile');

    $this->db->where('user_id', $d['user_id']);
    $this->db->delete('t_mtr_handset');

    $multimobile = array('user_id' => $d['user_id'], "active" => 1);
    $multimobile['created_by'] = $this->session->userdata('user_id');
    $multimobile['created_on'] = date('Y-m-d H:i:s');

    //insert to t_mtr_user_multi_mobile
    if ($d['mobile_type'] == 3) {
      $multimobile["mobile_app_type"] = self::MOB_USR_PREV_MAIN;
      $this->db->insert('t_mtr_user_multi_mobile', $multimobile);

      $multimobile["mobile_app_type"] = self::MOB_USR_SITE_INSP;
      $this->db->insert('t_mtr_user_multi_mobile', $multimobile);
    } else {

      $multimobile["mobile_app_type"] = $d['mobile_type'];
      $this->db->insert('t_mtr_user_multi_mobile', $multimobile);
    }
    // insert t_mtr_handset
    $handset = array('user_id' => $d['user_id'],
        "istatus" => 1,
        "imsi" => "",
        "msisdn" => $d['msisdn'],
        // "imei"=>$d['imei'],
        "imei" => strtoupper($d['imei']),
        "handset_model" => $d['handset_model'],
        "handset_man" => $d['handset_man'],
        "created_by" => $this->session->userdata('user_id'),
        "created_on" => date('Y-m-d H:i:s'),
    );

    $this->db->insert('t_mtr_handset', $handset);

//    $this->mglobal->setData("t_mtr_user", array("is_mobile_user" => 1, "mobile_number" => $d['msisdn']), array("user_id" => $d['user_id'])
//    );
    // Update user
    $user = array("user_id" => $d['user_id'], "is_mobile_user" => 1, "mobile_number" => $d['msisdn']);
    $this->mglobal->saveData("t_mtr_user", $user, 'user_id');

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      echo json_encode(array(
          'status' => 'ERROR',
          'hasil' => 'Unable to save data'));
    } else {
      $this->db->trans_commit();
      echo json_encode(array(
          'status' => 'OK',
          'hasil' => 'Data successfully edited.'
      ));
      $this->dblog->AuditTrail('usermobile-edit', $handset, "web");
    }
  }

  public function loadDataMobileUser($d = NULL, $sort, $order, $limit = 0, $offset = 0) {
    $dblokal = $this->load->database("default", TRUE);

    $where = "";
    if (isset($d['q'])) {
      $query = $dblokal->escape_like_str($d['q']);
      $where = " AND (upper(usr.user_id) LIKE upper('%$query%')
					OR upper(user_name) LIKE upper('%$query%')
				)";
    }
    $q = "select user_name, mob.* from t_mtr_user usr
			JOIN (SELECT user_id, array_to_string(array_agg(lookup_desc), ', ') AS mobile_name
				, array_to_string(array_agg(lookup_value), ', ') AS mobile_id
					FROM t_mtr_user_multi_mobile, t_mtr_lookup  
					WHERE t_mtr_user_multi_mobile.mobile_app_type=t_mtr_lookup.lookup_value::SMALLINT
						and lookup_key = 'mobile_app_type'
					GROUP BY t_mtr_user_multi_mobile.user_id) as mob ON usr.user_id = mob.user_id 			
			where usr.status IN(0,1,2)  and is_mobile_user=1
			$where
			ORDER BY $sort $order ";
    if ($limit > 0)
      $q .=" LIMIT $limit OFFSET $offset ";

    $query = $dblokal->query($q);
    return $query;
  }

  public function loadDataMobileUserSingle($user_id) {
    $q = "select mob.*,h.imei,h.handset_man,h.msisdn,h.handset_model from 
					(SELECT user_id, array_to_string(array_agg(lookup_desc), ', ') AS mobile_name
				, array_to_string(array_agg(lookup_value), ', ') AS mobile_id
					FROM t_mtr_user_multi_mobile, t_mtr_lookup  
					WHERE t_mtr_user_multi_mobile.mobile_app_type=t_mtr_lookup.lookup_value::SMALLINT
						and lookup_key = 'mobile_app_type'
					GROUP BY t_mtr_user_multi_mobile.user_id) as mob
					left join t_mtr_handset h on h.user_id=mob.user_id where mob.user_id='$user_id'";
    $query = $this->db->query($q);
    return $query;
  }

  public function loadDataMobileUserManagement($d = NULL, $sort, $order, $limit = 0, $offset = 0) {
    $dblokal = $this->load->database("default", TRUE);

    $where = "";
    if (isset($d['q'])) {
      $query = $dblokal->escape_like_str($d['q']);
      $where = " AND (usr.user_id ILIKE '%$query%'
					OR user_name ILIKE '%$query%'
					OR h.msisdn ILIKE '%$query%'
					OR h.imei ILIKE '%$query%'
					OR h.handset_model ILIKE '%$query%'
					OR ver.version_number ILIKE '%$query%'
				)";
    }

    //Filter user region
    $region = array();
    foreach ($this->session->userdata('user_region') as $row) {
      $region[] = $row['region_id'];
    }
    $region = implode(',', $region);
    $where.= " AND reg.region_id in ($region)";
    //End filter

    $q = "select DISTINCT user_name, mob.* ,h.handset_man,h.handset_model,h.imei,h.msisdn, ver.version_number, ver.active, upd.updated_on AS last_update
			from t_mtr_user usr
			JOIN (SELECT user_id, array_to_string(array_agg(lookup_desc), ', ') AS mobile_name
				, array_to_string(array_agg(lookup_value), ', ') AS mobile_id
					FROM t_mtr_user_multi_mobile, t_mtr_lookup  
					WHERE t_mtr_user_multi_mobile.mobile_app_type=t_mtr_lookup.lookup_value::SMALLINT
						and lookup_key = 'mobile_app_type'
					GROUP BY t_mtr_user_multi_mobile.user_id) as mob ON usr.user_id = mob.user_id 			
			LEFT JOIN t_mtr_handset h on h.user_id = usr.user_id
			LEFT JOIN t_mtr_user_territory reg on reg.user_id=usr.user_id
            LEFT JOIN t_trx_mobile_user_update_app upd ON upd.user_id = usr.user_id
            LEFT JOIN t_trx_mobile_app_version ver ON ver.version_id = upd.version_id
			where usr.status IN(0,1,2)  and is_mobile_user=1 and h.istatus=1
			$where
			ORDER BY $sort $order ";
    if ($limit > 0)
      $q .=" LIMIT $limit OFFSET $offset ";

    $query = $dblokal->query($q);
    return $query;
  }

  public function loadDataMobileUser2() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('dataperpage', 'query', 'curpage');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $query = $dblokal->escape_like_str($query);
    $where = " AND (upper(usr.user_id) LIKE upper('%$query%')
					OR upper(user_name) LIKE upper('%$query%')
				)";

    $q = "select user_name, mob.* from t_mtr_user usr
			JOIN (SELECT user_id, array_to_string(array_agg(lookup_desc), ', ') AS mobile_name
				, array_to_string(array_agg(lookup_value), ', ') AS mobile_id
					FROM t_mtr_user_multi_mobile, t_mtr_lookup  
					WHERE t_mtr_user_multi_mobile.mobile_app_type=t_mtr_lookup.lookup_value::SMALLINT
						and lookup_key = 'mobile_app_type'
					GROUP BY t_mtr_user_multi_mobile.user_id) as mob ON usr.user_id = mob.user_id 			
			where usr.status IN(0,1,2)  and is_mobile_user=1
			$where ";

    $query = $dblokal->query($q);
    $total = $query->num_rows;
    $npage = ceil($total / $dataperpage);

    $start = $curpage * $dataperpage;
    $end = $start + $dataperpage;

    $q .= " ORDER BY user_id
			LIMIT $dataperpage OFFSET $start";
    $query = $dblokal->query($q);

    $hasil = array(
        'data' => array(),
        'pagination' => '',
        'curpage' => $curpage,
        'numpage' => $npage - 1,
    );
    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $hasil['data'][] = array(
            'user_id' => $row->user_id,
            'user_name' => $row->user_name,
            'mobile_name' => $row->mobile_name,
            'mobile_id' => $row->mobile_id
        );
      }
    }


    $hasil['pagination'] .= '<ul>
			<li class="' . ($curpage == 0 ? 'disabled' : '') . '" onclick="return Document.prevPage()"><a href>&laquo;</li>';
    $nmulai = 1;
    //if($curpage >= 5) $nmulai = $curpage - 5;
    for ($i = 1; $i <= $npage; $i++) {
      if ($i >= $curpage - 4 && $i <= $curpage + 4) {
        $hasil['pagination'] .= '<li class="' . ($curpage == ($i - 1) ? 'active' : '') . '" onclick="return Document.setPage(' . ($i - 1) . ')"><a href>' . $i . '</a></li>';
      }
    }
    $hasil['pagination'] .= '<li class="' . ($curpage == $npage - 1 ? 'disabled' : '') . '" onclick="return Document.nextPage()"><a href>&raquo;</a></li>
		  </ul>';

    echo json_encode($hasil, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
  }

//  public function deleteDataMobileUser() {
//    $dblokal = $this->load->database("default", TRUE);
//    $input = array('id', 'curpage');
//    foreach ($input as $val)
//      $$val = $this->input->post($val);
//
//    $dblokal->trans_begin();
//
//    $dblokal->where(array('user_id' => $id));
//    $dblokal->update('t_mtr_user', array('is_mobile_user' => 0));
//
//    $dblokal->delete("t_mtr_user_multi_mobile", array("user_id" => $user_id));
//    $dblokal->delete("t_mtr_handset", array("user_id" => $user_id));
//
//    if ($dblokal->trans_status() === FALSE) {
//      $dblokal->trans_rollback();
//    } else {
//      $dblokal->trans_commit();
//    }
//  }

  public function deleteDataMobileUser($id) {
    $this->db->trans_start();

    $user = array(
        'user_id' => $id,
        'is_mobile_user' => 0
    );
    $this->mglobal->saveData('t_mtr_user', $user, 'user_id');

    $this->db->delete("t_mtr_user_multi_mobile", array("user_id" => $id));
    $this->db->delete("t_mtr_handset", array("user_id" => $id));

    $this->dblog->AuditTrail('usermobile-delete', $this->input->post(), "web");
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  //for mobile user popup
  function getEscUser($d = NULL, $rows = 0, $offset = 0, $id = 0) {
    $dblokal = $this->load->database("default", TRUE);
    $userx = $this->session->userdata('user_id');
    $qrs = "";
    $id = $dblokal->escape_str($id);
    if (isset($d)) {
      $d = $dblokal->escape_str($d);
    }

    $qr = "select u.user_id, u.user_name from t_mtr_user u left join t_mtr_user_multi_division mr on mr.user_id = u.user_id
				where u.status=1 and mr.user_div_id='" . $id . "' and u.user_id != '" . $userx . "'";

    if (isset($d)) {

      $q = $d;
      $or_arr = array('u.user_id', 'u.user_name');
      $or_str = "";

      foreach ($or_arr as $val) {
        if ($or_str == "")
          $or_str.="($val ilike '%" . $q . "%'";
        else
          $or_str.=" or $val ilike '%" . $q . "%'";
      }

      $or_str.=")";
      $qr .= "AND " . $or_str;
    }

    if ($rows != 0) {
      $qrs .= $qr . " LIMIT " . $rows . " OFFSET " . $offset . " ";
    }

    $queryc = $dblokal->query($qr);
    $total = $queryc->num_rows;
    $query = $dblokal->query($qrs);
    $result["total"] = $total;
    $items = array();
    foreach ($query->result() as $row) {
      array_push($items, $row);
    }

    $result["rows"] = $items;

    echo json_encode($result);
  }

  function getEscNew($d = NULL, $rows = 0, $offset = 0) {

    $dblokal = $this->load->database("default", TRUE);
    $qrs = "";
    $qr = "select user_div_id, user_div_name from t_mtr_user_division where 
			status=1 ";

    if (isset($d)) {

      $q = $d;
      $or_arr = array('user_div_name');
      $or_str = "";

      foreach ($or_arr as $val) {
        if ($or_str == "")
          $or_str.="($val ilike '%" . $q . "%'";
        else
          $or_str.=" or $val ilike '%" . $q . "%'";
      }

      $or_str.=")";
      $qr .= "AND " . $or_str;
    }

    if ($rows != 0) {
      $qrs .= $qr . " LIMIT " . $rows . " OFFSET " . $offset . " ";
    }

    $query = $dblokal->query($qr);
    $total = $query->num_rows;
    $result["total"] = $total;
    $items = array();

    foreach ($query->result() as $row) {
      array_push($items, $row);
    }

    $result["rows"] = $items;
    echo json_encode($result);
  }

//        get all user n their division (Andedi 20141014)
  function getAllUser($user = '', $group = '', $rows = 0, $offset = 0) {
    $dblokal = $this->load->database("default", TRUE);
    $user = $dblokal->escape_str($user);
    $group = ($group != '') ? (int) $group : '';
    $sql = "
                    SELECT A.user_id, A.user_name, string_agg(C.user_group_name, ', ') AS group
                    FROM t_mtr_user A
                    LEFT JOIN t_mtr_user_multi_role B
                    ON B.user_id = A.user_id
                    LEFT JOIN t_mtr_user_group C
                    ON C.user_group_id = B.user_group_id
                    WHERE (A.user_name LIKE '%$user%' OR A.user_id LIKE '%$user%')
                    ";

    if ($group != '') {
      $sql = $sql . " AND C.user_group_id= $group ";
    }

    $sql = $sql . " GROUP BY A.user_id, A.user_name ORDER BY A.user_name ";

    if ($rows != 0) {
      $sql = $sql . " LIMIT " . $rows . " OFFSET " . $offset . " ";
    }

    $query = $dblokal->query($sql);
    return $query->result();
  }

//        get all user n their division (Andedi 20141014)
  function getAllUserCount($user = '', $group = '') {
    $dblokal = $this->load->database("default", TRUE);
    $user = $dblokal->escape_str($user);
    $group = ($group != '') ? (int) $group : '';
    $sql = "
                    SELECT A.user_id, A.user_name, string_agg(C.user_group_name, ', ') AS group
                    FROM t_mtr_user A
                    LEFT JOIN t_mtr_user_multi_role B
                    ON B.user_id = A.user_id
                    LEFT JOIN t_mtr_user_group C
                    ON C.user_group_id = B.user_group_id
                    WHERE (A.user_name LIKE '%$user%' OR A.user_id LIKE '%$user%')
                    ";

    if ($group != '') {
      $sql = $sql . " AND C.user_group_id = $group ";
    }

    $sql = $sql . " GROUP BY A.user_id, A.user_name ORDER BY A.user_name ";

    $query = $dblokal->query($sql);
    return $query->num_rows();
  }

//        get user division (Andedi 20141014)
  function getAllUserGroup() {
    $dblokal = $this->load->database("default", TRUE);
    $sql = " SELECT user_group_id, user_group_name FROM t_mtr_user_group ORDER BY user_group_name";
    $query = $dblokal->query($sql);
    return $query->result();
  }

}

?>