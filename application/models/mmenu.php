<?php

class mmenu extends CI_Model {

    function Mmenu() {
        parent::__construct();
        $this->setVars();
    }

    function setVars() {
        $this->result = '';
        $this->idnya = 0;
    }

    function general() {
        $this->load->library('session');
        $data = array();
        $username = $this->session->userdata('username');
        $data['webtitle'] = ""; //$this->getNamaSistem2();
        $data['getMenuSistem'] = ""; //$this->getMenuSistem();
        //$data['template']          = "shell/admin_template";

        $data['getJudul'] = ""; //$this->mjadwal->getJudul($this->uri->segment(2));
        return $data;
    }

    /* function ambil menu, dimana id sama dengan 0 */
    // Menu per parent_id, disimpan per request: template memanggil getMenu() sekali per menu/submenu,
    // jadi semua menu milik user cukup diambil dengan satu query.
    private $_menu_by_parent = NULL;

    function getMenu($id = 0, $group = "") {
        if ($this->_menu_by_parent === NULL) {
            $user_id = $this->session->userdata('user_id');
            $dblokal = $this->load->database("default", TRUE); // load db lokal

            $q = "SELECT mn.\"id\" AS menu_id,mn.name, mn.alias, mn.parent_id, mn.icon
			FROM t_mtr_menu mn
			JOIN t_mtr_user_role rl ON mn.\"id\"=rl.menu_id and rl.menu_view=1
				AND rl.user_group_id IN (SELECT user_group_id FROM t_mtr_user_multi_role WHERE user_id=" . $dblokal->escape($user_id) . ")
			JOIN t_mtr_user_group grp ON grp.user_group_id = rl.user_group_id
			WHERE mn.published = 1
			GROUP BY mn.\"id\"
			ORDER BY ordering";

            $this->_menu_by_parent = array();
            foreach ($dblokal->query($q)->result() as $row) {
                $this->_menu_by_parent[(string) $row->parent_id][] = $row;
            }
        }

        $id = (string) $id;
        return isset($this->_menu_by_parent[$id]) ? $this->_menu_by_parent[$id] : array();
    }

    public function loadDataMenuByID($id) {
        $dblokal = $this->load->database("default", TRUE);

        $query = $dblokal->query("SELECT * FROM t_mtr_menu
						WHERE id='$id'");

        $fields = $query->list_fields();
        $q = $query->result();
        $hasil = array();
        foreach ($q as $r) {
            foreach ($fields as $field) {
                $hasil['hasil'][$field] = $r->$field;
            }
        }
        $hasil['status'] = 'OK';
        echo json_encode($hasil, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    function getMenuTree($id = 0) {
        $dblokal = $this->load->database("default", TRUE);
        //Get user group role
        $group_id = 1;
        $k = 0;
        //-----------------------------------------------------//
        $query = $this->getgridmenu(0, $group_id);
        $jum = $query->num_rows();
        $this->idnya++;
        if ($query->num_rows() > 0) {
            $this->result .='[';
            foreach ($query->result() as $r) {

                $r->published = ($r->published == 1) ? '<font color=\"green\">Yes</font>' : '<font color=\"red\">No</font>';
                $r->action = "<a title='Edit' href='#' class='btn-action glyphicons pencil btn-success' onclick='doEdit(" . $r->id . ")'><i></i></a>" .
                        "<a title='Delete' href='#' class='btn-action glyphicons remove_2 btn-success' onclick='doDelete(" . $r->id . ")'><i></i></a>";
                $this->result .='{"id"		: ' . $r->id . ',
						"menu_name"	: "' . $r->menu_name . '",
						"alias"		: "' . $r->alias . '",
						"ordering"	: "' . $r->ordering . '",
						"published"	: "' . $r->published . '",
						"parent_id"	: "' . $r->parent_id . '",
						"action"	: "' . $r->action . '",
						"iconCls"	: "icon-level-1"';

                #cek punya children gak ?
                $query2 = $this->getgridmenu($r->id, $group_id);
                $k++;
                $jum2 = $query2->num_rows();
                if ($query2->num_rows() > 0) {

                    $this->result .=',"state":"closed"';
                    $this->result .=',"children":';

                    //-------------------------
                    $this->result .='[';
                    $l = 0;
                    foreach ($query2->result() as $s) {

                        $s->published = ($s->published == 1) ? '<font color=\"green\">Yes</font>' : '<font color=\"red\">No</font>';
                        $s->action = "<a title='Edit' href='#' class='btn-action glyphicons pencil btn-success' onclick='doEdit(" . $s->id . ")'><i></i></a>" .
                                "<a title='Delete' href='#' class='btn-action glyphicons remove_2 btn-success' onclick='doDelete(" . $s->id . ")'><i></i></a>";

                        $this->result .='{"id"		: ' . $s->id . ',
								"menu_name"	: "' . $s->menu_name . '",
								"alias"		: "' . $s->alias . '",
								"ordering"	: "' . $s->ordering . '",
								"published"	: "' . $s->published . '",
								"parent_id"	: "' . $s->parent_id . '",
								"action"	: "' . $s->action . '",
								"iconCls"	: "icon-level-2"';

                        #cek punya children gak ?
                        $query3 = $this->getgridmenu($s->id, $group_id);
                        $l++;
                        $jum3 = $query3->num_rows();
                        if ($query3->num_rows() > 0) {
                            $this->result .=',"state":"closed"';
                            $this->result .=',"children":';

                            $this->result .='[';
                            $m = 0;

                            foreach ($query3->result() as $t) {
                                $m++;
                                $t->published = ($t->published == 1) ? '<font color=\"green\">Yes</font>' : '<font color=\"red\">No</font>';
                                $t->action = "<a title='Edit' href='#' class='btn-action glyphicons pencil btn-success' onclick='doEdit(" . $t->id . ")'><i></i></a>" .
                                        "<a title='Delete' href='#' class='btn-action glyphicons remove_2 btn-success' onclick='doDelete(" . $t->id . ")'><i></i></a>";

                                $this->result .='{"id"		: ' . $t->id . ',
										"menu_name"	: "' . $t->menu_name . '",
										"alias"		: "' . $t->alias . '",
										"ordering"	: "' . $t->ordering . '",
										"published"	: "' . $t->published . '",
										"parent_id"	: "' . $t->parent_id . '",
										"action"	: "' . $t->action . '",
										"iconCls"	: "icon-level-3"}';
                                if ($m != $jum3)
                                    $this->result .=',';
                            }
                            $this->result .="]";
                        }

                        $this->result .='}';
                        if ($l != $jum2)
                            $this->result .=',';
                    }
                    $this->result .="]";
                    //-----------------------------
                    //$this->result .= '}';
                }
                $this->result .='}';
                if ($k != $jum)
                    $this->result .=',';
            }
            $this->result .="]";
        }
        return $this->result;
    }

    function cekPermission($menu_id, $menu_role) {
        $user_id = $this->session->userdata('user_id');
        $dblokal = $this->load->database("default", TRUE); // load db lokal

        $q = "SELECT mn.\"id\" AS menu_id,mn.name, mn.alias, mn.parent_id, rl.menu_view,
				grp.user_group_name,grp.user_group_id
			FROM t_mtr_menu mn
			JOIN t_mtr_user_role rl ON mn.\"id\"=rl.menu_id AND rl.$menu_role=1
				AND rl.user_group_id IN (SELECT user_group_id FROM t_mtr_user_multi_role WHERE user_id='$user_id')
			JOIN t_mtr_user_group grp ON grp.user_group_id = rl.user_group_id
			WHERE mn.published = 1 AND mn.\"id\" = '$menu_id'
			ORDER BY ordering";
        $query = $dblokal->query($q);
        if ($query->num_rows > 0) {
            return true;
        }
        return false;
    }

    ############################################

    function getMenuDetail($id) {
        $dblokal = $this->load->database("default", TRUE);
        $q = "SELECT * FROM t_mtr_menu WHERE id = $id ";
        $query = $dblokal->query($q);
        return $query->result();
    }

    public function saveData() {
        $dblokal = $this->load->database("default", TRUE);
        $input = array('id', 'parent', 'nama', 'url', 'order', 'status');
        foreach ($input as $val)
            $$val = $this->input->post($val);

        $data = array('parent_id' => $parent,
            'name' => $nama,
            'alias' => $url,
            'ordering' => $order,
            'published' => ($status == "on" ? 1 : 0));

        if ($id == "") {
            $data['created'] = date("Y-m-d H:i:s");
            $data['created_by'] = $this->session->userdata('user_id');

            $dblokal->trans_begin();

            //update ordering
            $dblokal->query("UPDATE t_mtr_menu SET ordering = ordering+1 WHERE ordering >= $order AND parent_id=$parent");

            $dblokal->insert('t_mtr_menu', $data);


            if ($dblokal->trans_status() === FALSE) {
                $dblokal->trans_rollback();
            } else {
                $dblokal->trans_commit();
            }
        } else {
            $data['updated'] = date("Y-m-d H:i:s");
            $data['updated_by'] = $this->session->userdata('user_id');


            $dblokal->trans_begin();

            //update ordering
            $dblokal->query("UPDATE t_mtr_menu SET ordering = ordering+1 WHERE ordering >= $order AND parent_id=$parent");

            $dblokal->where(array('id' => $id));
            $dblokal->update('t_mtr_menu', $data);

            if ($dblokal->trans_status() === FALSE) {
                $dblokal->trans_rollback();
            } else {
                $dblokal->trans_commit();
            }
        }
    }

    public function deleteData() {
        $dblokal = $this->load->database("default", TRUE);
        $input = array('id', 'curpage');
        foreach ($input as $val)
            $$val = $this->input->post($val);

        $dblokal->trans_begin();

        $dblokal->where(array('id' => $id));
        $dblokal->update('t_mtr_menu', array('published' => -1));
        $this->dblog->AuditTrail('menu-delete', "Menu ID: $id");
        if ($dblokal->trans_status() === FALSE) {
            $dblokal->trans_rollback();
            $result = array('status' => 'ERROR', 'hasil' => 'Delete is unsuccessful. <br>Data is being used.');
        } else {
            $dblokal->trans_commit();
            $result = array('status' => 'OK', 'hasil' => 'Menu Deleted sucessfully.');
        }
        echo json_encode($result);
        return;
    }

    ############################################

    function getIdSistem() {
        $dblokal = $this->load->database("default", TRUE);
        $hasil = 0;
        $sistem = $this->uri->segment(1);

      /*  if ($sistem == "")
            $sistem = "main";*/
        #$nmsistem = explode("_",$url);
        #$sistem   = $nmsistem[0];

        $sql = "SELECT * FROM t_mtr_menu WHERE alias = '" . $sistem . "' AND parent_id=0 ";
        $query = $dblokal->query($sql);

        $r = $query->result();

        if (count($r) > 0) {
            foreach ($r as $d) {
                $hasil = $d->id;
            }
        }

        return $hasil;
    }

    function getGridMenu($parent_id, $group_id = '', $published = "") {
        $dblokal = $this->load->database("default", TRUE);
        if ($group_id == '') {
            $group_id = 0;
        }
        $isql = " and mn.published IN (0,1)  ";
        if ($published <> "") {
            $isql = " and mn.published IN (1)  ";
        }
        $sql = "select mn.id, mn.name as menu_name,mn.alias,mn.parent_id, mn.ordering, mn.published, rl.*
			from t_mtr_menu mn
			left join t_mtr_user_role rl on mn.id = rl.menu_id and rl.user_group_id = $group_id
			LEFT JOIN t_mtr_user_group grp on grp.user_group_id = rl.user_group_id
			where mn.parent_id = $parent_id  $isql
			order by mn.ordering";

        return $dblokal->query($sql);
    }

    function getGridMenuOld($parent_id, $group_id = '', $published = "") {
        $dblokal = $this->load->database("default", TRUE);
        if ($group_id == '') {
            $group_id = 0;
        }
        $isql = " and mn.published IN (0,1)  ";
        if ($published <> "") {
            $isql = " and mn.published IN (1)  ";
        }
        $sql = "select mn.id, mn.name as menu_name,mn.alias,mn.parent_id, mn.ordering, mn.published, rl.*
			from t_mtr_menu mn
			left join t_mtr_user_role rl on mn.id = rl.menu_id and rl.user_group_id = $group_id
			LEFT JOIN t_mtr_user_group grp on grp.user_group_id = rl.user_group_id
			where mn.parent_id = $parent_id  $isql
			order by mn.ordering";

        return $dblokal->query($sql)->result();
    }

    function getMenuId($sistem = '') {
        $dblokal = $this->load->database("default", TRUE);
        $hasil = "";
        $sql = "SELECT * FROM t_mtr_menu WHERE alias = '" . $sistem . "' and not parent_id=0 ";
        $query = $dblokal->query($sql);
        $r = $query->result();
        if (count($r) > 0) {
            foreach ($r as $d) {
                $hasil = $d->id;
            }
        } else {
            $hasil = '';
        }
        return $hasil;
    }

    // Dipanggil get_prev_kolom() dan getBreadcrumb() di setiap halaman; hasil disimpan per request
    private $_current_menu_id = FALSE;

    function getMenuIdCurrentURL() {
        if ($this->_current_menu_id === FALSE) {
            $this->_current_menu_id = $this->_findMenuIdCurrentURL();
        }
        return $this->_current_menu_id;
    }

    private function _findMenuIdCurrentURL() {

        $dblokal = $this->load->database("default", TRUE);
        $hasil = "";
        $curl = "";
        $url = explode("/", $this->uri->uri_string());
        for ($i = count($url); $i >= 0; $i--) {
            $data = array();
            for ($k = 0; $k < $i; $k++) {
                $data[] = $url[$k];
            }
            $curl = implode("/", $data);
            $hasil = $this->mglobal->showdata('id', 't_mtr_menu', array('alias' => $curl, 'published' => 1));
            if ($hasil <> "") {
                return $hasil;
            }
        }
    }

    // Baris t_mtr_menu per id, disimpan per request (sama dengan mglobal->showdata per kolom)
    private $_menu_rows = array();

    private function _menu_field($field, $id) {
        $key = (string) $id;
        if ( ! array_key_exists($key, $this->_menu_rows)) {
            $dblokal = $this->load->database("default", TRUE);
            $dblokal->where(array('id' => $id));
            $query = $dblokal->get('t_mtr_menu');
            $this->_menu_rows[$key] = $query->num_rows() > 0 ? $query->row() : NULL;
        }
        return $this->_menu_rows[$key] ? $this->_menu_rows[$key]->{$field} : "";
    }

    function getBreadcrumb() {

//		$dblokal  = $this->load->database("default", TRUE);
//		return "";
        $hasil1 = "";
        $hasil2 = "";
        $hasil3 = "";
        $hasil4 = "";
        $id = $this->getMenuIdCurrentURL();
        if ($id > 0 && $id <> "") {
            $nm_menu = $this->_menu_field('name', $id);
            $url_menu = $this->_menu_field('alias', $id);
            $pid = $this->_menu_field('parent_id', $id);

            if ($nm_menu <> "") {
                $hasil4 = '<li class="divider"></li>
					<li><a style="font-weight:normal" href="' . base_url() . $url_menu . '"><i></i> ' . $nm_menu . '</a></li>';
            }
        }
        if (isset($pid)) {
            if ($pid > 0 && $pid <> "") {
                $id = $this->_menu_field('id', $pid);
                $nm_menu = $this->_menu_field('name', $id);
                $url_menu = $this->_menu_field('alias', $id);
                $pid = $this->_menu_field('parent_id', $id);

                if ($nm_menu <> "") {
                    $hasil3 = '<li class="divider"></li>
					<li><a style="font-weight:normal" href="' . base_url() . $url_menu . '"><i></i> ' . $nm_menu . '</a></li>';
                }
            }

            if ($pid > 0 && $pid <> "") {
//			echo "3";
                $id = $this->_menu_field('id', $pid);
                $nm_menu = $this->_menu_field('name', $id);
                $url_menu = $this->_menu_field('alias', $id);
                $pid = $this->_menu_field('parent_id', $id);

                if ($nm_menu <> "") {
                    $hasil2 = '<li class="divider"></li>
					<li><a style="font-weight:normal" href="' . base_url() . $url_menu . '"><i></i> ' . $nm_menu . '</a></li>';
                }
            }
            if ($pid > 0 && $pid <> "") {
//			echo "4";
                $id = $this->_menu_field('id', $pid);
                $nm_menu = $this->_menu_field('name', $id);
                $url_menu = $this->_menu_field('alias', $id);
                $pid = $this->_menu_field('parent_id', $id);

                if ($nm_menu <> "") {
                    $hasil1 = '<li class="divider"></li>
					<li><a style="font-weight:normal" href="' . base_url() . $url_menu . '"><i></i> ' . $nm_menu . '</a></li>';
                }
            }
        }
        $hasil = '<ul class="breadcrumb">
				<li><a href="' . base_url() . '" class="glyphicons home"><i></i> Dashboard</a></li>
				' . $hasil1 . $hasil2 . $hasil3 . $hasil4 . '
			</ul>';

        return $hasil;
    }

    function getParentMenu() {

        $dblokal = $this->load->database("default", TRUE);

        $getMenuAktif = uri_string();
        if ($getMenuAktif == "" || $getMenuAktif == "#") {
            return;
        }

        $pid = $this->mglobal->showdata('parent_id', 't_mtr_menu', array('alias' => $getMenuAktif));
        return $pid;
    }

    function saveRoles($sql, $act = '') {
        // print_r($sql);die;
        $dblokal = $this->load->database("default", TRUE);
        if ($dblokal->query($sql)) {
            // $this->mglobal->saveLog('Grouproles',$act,$this->session->userdata('username'), '1');
            echo json_encode(array(
                'pesan' => 'Save Konfigurasi group role suksess'
            ));
        } else {
            echo json_encode(array(
                'pesan' => 'error Save data '
            ));
        }
    }

    function addMenu($data) {
        $key = $data['key'];
        $val = $data['val'];

        $dblokal = $this->load->database("default", TRUE);
        $sql = 'insert into t_mtr_menu (' . $key . ') values (' . $val . ')';
        if ($dblokal->query($sql)) {
            echo json_encode(array(
                'pesan' => 'Save Menu Baru suksess'
            ));
        } else {
            echo json_encode(array(
                'pesan' => 'error Save Menu Baru '
            ));
        }
    }

    ###get nama group

    function getGroup() {
        $dblokal = $this->load->database("default", TRUE);
        $group = $dblokal->order_by('user_group_name', 'asc')->get('t_mtr_user_group')->result();
        return $group;
    }

    ########################################################################
    #save group role

    function saveGroupRole() {
        $dblokal = $this->load->database("default", TRUE);

        $data['menu_view'] = 0;
        $data['menu_add'] = 0;
        $data['menu_edit'] = 0;
        $data['menu_delete'] = 0;
        $data['menu_approve'] = 0;
        $data['menu_reject'] = 0;

        $data['menu_id'] = $this->input->post('id');
        $data['user_group_id'] = $this->input->post('group');
        $test = $dblokal->escape($this->input->post('id'));

        $role = $this->input->post('role');
        $FormRole = $this->input->post('FormRole');

        if ($data['user_group_id'] == "") {
            echo json_encode(array('pesan' => 'Group Role belum dipilih'));
            return;
        }

        //echo count($FormRole);
        //echo $FormRole;
        if ($FormRole <> "") {
            for ($i = 0; $i < count($FormRole); $i++) {
                //update role
                $rlf = explode("::", $FormRole[$i]);
                $data['created_by'] = $this->session->userdata('user_id');
                $data['created_on'] = date('Y-m-d H:i:s');

                $dblokal->trans_begin();

                $dblokal->where(array('form_id' => $rlf[0], 'user_group_id' => $data['user_group_id']))
                        ->update('t_mtr_user_form_role', array('element_role' => $rlf[1],
                            'updated_by' => $this->session->userdata('user_id'),
                            'updated_on' => date('Y-m-d H:i:s')));

                if ($dblokal->trans_status() === FALSE) {
                    $dblokal->trans_rollback();
                } else {
                    $dblokal->trans_commit();
                }
            }
        }

        if (count($role) > 0) {
            $post1 = array_diff($role, array('checkall'));
            if (in_array('menu_view', $post1)) {
                $data['menu_view'] = 1;
            }
            if (in_array('menu_add', $post1)) {
                $data['menu_add'] = 1;
            }
            if (in_array('menu_delete', $post1)) {
                $data['menu_delete'] = 1;
            }
            if (in_array('menu_edit', $post1)) {
                $data['menu_edit'] = 1;
            }
            if (in_array('menu_approve', $post1)) {
                $data['menu_approve'] = 1;
            }
            if (in_array('menu_reject', $post1)) {
                $data['menu_reject'] = 1;
            }
        }

        $update = $dblokal->query('select * from t_mtr_user_role where menu_id =' . $data['menu_id'] . ' and  user_group_id = ' . $data['user_group_id']);
        $get = $update->row();
        //print_r($update->num_rows);die;
        $hasil = "";
        if ($update->num_rows == 0) {
            $data['created_by'] = $this->session->userdata('user_id');
            $data['created_on'] = date('Y-m-d H:i:s');

            $act = 'Insert-new-role';
            if ($dblokal->insert('t_mtr_user_role', $data)) {
                $hasil = "OK";
            }
        } else {
            $data['updated_by'] = $this->session->userdata('user_id');
            $data['updated_on'] = date('Y-m-d H:i:s');

            $act = 'update_role';
            if ($dblokal->where(array('menu_id' => $data['menu_id'], 'user_group_id' => $data['user_group_id']))->update('t_mtr_user_role', $data)) {
                $hasil = "OK";
            }
        }

        if ($hasil == "OK") {
            echo json_encode(array('pesan' => 'Save Konfigurasi group role suksess'));
        } else {
            echo json_encode(array('pesan' => 'error Save data'));
        }
    }

    ########################################################################
    #cek url tersedia/tidak

    function urlOk($url) {
        $headers = @get_headers($url);
        //print_r( $headers);
        if ($headers[0] == 'HTTP/1.1 200 OK')
            return true;
        else
            return false;

        /*
          if (@file_get_contents($url)): return TRUE;
          else: return FALSE;
          endif;
         */
    }

    #get element dalam form

    public function getElementFromURL($url = "", $menu_id = "", $group = "") {
        $this->load->library('simple_html_dom');
        $url = base_url() . $url;
        $i = 0;
        $url_form = "";
        if ($this->urlOk($url)) {
            $html = file_get_html($url);
            /* foreach($html->find('div') as $element){
              if( $element->id == "url_add_edit" ){
              $url_form = $element->url;
              }
              //echo $url.$element->class."<br>";//"aa".$url_form;
              } */

            foreach ($html->find('th') as $element) {
                $elm = $element->class;
                if (substr($elm, 0, 4) == "form" || substr($elm, 0, 4) == "grid") {
                    $this->saveElementForm($menu_id, $elm);
                    $i++;
                }
            }
        }
        /*
          //get form-nya
          $url = $url_form;
          if( $this->urlOk($url) ){
          $html = file_get_html($url);
          foreach($html->find('div') as $element){
          $elm = $element->id;
          if(substr($elm,0,4) == "form" || substr($elm,0,4) == "grid"){
          $this->saveElementForm($menu_id, $elm);
          }
          }
          } */
        return $i;
    }

    #simpan ke database, nama element yg telah ditemukan

    function saveElementForm($menu_id, $element) {
        //lakukan penyimpanan element form secara otomatis
        $dblokal = $this->load->database("default", TRUE);

        $q = $dblokal->query("SELECT MAX(form_id) as fid FROM t_mtr_user_form");
        $form_id = 1;
        if ($q->num_rows > 0) {
            $form_id = $q->row()->fid + 1;
        }

        $data['form_id'] = $form_id;
        $data['menu_id'] = $menu_id;
        $data['element_name'] = $element;
        $data['element_type'] = substr($element, 0, 4);
        $data['status'] = 1;

        $cek = $dblokal->where($data)->get('t_mtr_user_form');
        if ($cek->num_rows == 0) {
            //insert data
            $data['created_by'] = $this->session->userdata('user_id');
            $data['created_on'] = date('Y-m-d H:i:s');

            $dblokal->insert('t_mtr_user_form', $data);
        }
    }

    function saveElementFormLangsung($menu_id, $element, $group, $role) {
        //lakukan penyimpanan element form secara otomatis
        $dblokal = $this->load->database("default", TRUE);

        $q = $dblokal->query("SELECT MAX(form_id) as fid FROM t_mtr_user_form");
        $form_id = 1;
        if ($q->num_rows > 0) {
            $form_id = $q->row()->fid + 1;
        }

        $data['form_id'] = $form_id;
        $data['menu_id'] = $menu_id;
        $data['element_name'] = $element;
        $data['element_type'] = substr($element, 0, 4);
        $data['status'] = 1;

        if (substr($element, 0, 4) <> "form" && substr($element, 0, 4) <> "grid" && substr($element, 0, 4) <> "tabs") {
            $data['element_type'] = "form";
        }

        $cek = $dblokal->where($data)->get('t_mtr_user_form');
        if ($cek->num_rows == 0) {
            //insert data
            $data['created_by'] = $this->session->userdata('user_id');
            $data['created_on'] = date('Y-m-d H:i:s');

            $dblokal->insert('t_mtr_user_form', $data);

            $data2['form_id'] = $form_id;
            $data2['user_group_id'] = $group;
            $data2['element_role'] = $role;
            $data2['created_by'] = $this->session->userdata('user_id');
            $data2['created_on'] = date('Y-m-d H:i:s');

            $dblokal->insert('t_mtr_user_form_role', $data2);
        }
    }

    #get Element dalam Form

    function getElementForm($menu_id = "", $group = "") {
        //lakukan penyimpanan element form secara otomatis
        $dblokal = $this->load->database("default", TRUE);

        $data['menu_id'] = $menu_id;
        $data['status'] = 1;
        $q = "";

        if ($group == "") {
            return array();
        }

        $qform = $dblokal->where($data)->get('t_mtr_user_form')->result();
        foreach ($qform as $r) {
            //insert ke db, tp cek dl
            $where['form_id'] = $r->form_id;
            $where['user_group_id'] = $group;
            $cek = $dblokal->where($where)->get('t_mtr_user_form_role');
            if ($cek->num_rows == 0) {
                //insert data
                if (strstr($r->element_name, 'ref'))
                    $where['element_role'] = 0;
                else
                    $where['element_role'] = 2;
                $where['created_by'] = $this->session->userdata('user_id');
                $where['created_on'] = date('Y-m-d H:i:s');

                $dblokal->insert('t_mtr_user_form_role', $where);
            }
        }
        $q = $dblokal->query("SELECT a.*, b.user_group_id, b.element_role
				     FROM t_mtr_user_form as a, t_mtr_user_form_role as b
				     WHERE a.form_id = b.form_id AND a.status=1
					AND a.menu_id='$menu_id' AND b.user_group_id = '$group' ORDER BY element_name");
        return $q->result();
    }

    #save group role

    function saveFormRole() {
        $dblokal = $this->load->database("default", TRUE);

        $where['menu_id'] = $this->input->post('id');
        $where['user_group_id'] = $this->input->post('group');
        $where['element_name'] = $this->input->post('role');

        $data['element_role'] = $this->input->post('role');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_on'] = date('Y-m-d H:i:s');

        if ($data['user_group_id'] == "") {
            echo json_encode(array('pesan' => 'Group Role belum dipilih'));
            return;
        }

        $act = 'update_form_role';
        if ($dblokal->where($where)->update('t_mtr_user_role', $data)) {
            $hasil = "OK";
        }

        if ($hasil == "OK") {
            echo json_encode(array('pesan' => 'Save Konfigurasi Form role suksess'));
        } else {
            echo json_encode(array('pesan' => 'error Save data'));
        }
    }

    function deleteElementForm() {
        $dblokal = $this->load->database("default", TRUE);
        $input = array('form_id');
        foreach ($input as $val)
            $$val = $this->input->post($val);

        $dblokal->trans_begin();

        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_on'] = date('Y-m-d H:i:s');
        $data['status'] = -1;

        $dblokal->where(array('form_id' => $form_id));
        $dblokal->update('t_mtr_user_form', $data);

        if ($dblokal->trans_status() === FALSE) {
            $dblokal->trans_rollback();
        } else {
            $dblokal->trans_commit();
        }
    }

    function getCardinality() {
        $dblokal = $this->load->database("default", TRUE); // load db lokal
        $query = $dblokal->query("SELECT * FROM t_mtr_cardinality ORDER BY cardinality_id DESC");
        return $query->result();
    }

    function getSupportMenu($parent_id = 0) {
        return $this->db
                        ->query("SELECT id, title, uri FROM " . $this->db->dbprefix('t_mtr_menu_support') . " WHERE parent_id = {$parent_id} ORDER BY \"order\"")
                        ->result_array();
    }

}

?>
