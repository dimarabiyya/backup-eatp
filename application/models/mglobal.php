<?php

class Mglobal extends CI_Model {

    function __construct() {
        parent::__construct();
//		$this->is_logged_in();
        $this->load->helper('url');
    }

    function generate_counter($field, $length, $prefix) {

        $this->db->trans_start();

        $this->db->query("SELECT counter FROM t_counter WHERE countername='$field' FOR UPDATE");
        $this->db->query("UPDATE t_counter SET counter = counter+1 WHERE countername='$field'");
        $lastid = $this->db->query("SELECT counter FROM t_counter WHERE countername='$field'")->result();
        $this->db->trans_complete();

        $autonumber = sprintf('%0' . $length . 'd', $lastid[0]->counter);
        $item_code = $prefix . '-' . $autonumber;

        return $item_code;
    }

    function is_logged_in() {
        //$tendang = $this->showdata('lookup_value','t_mtr_lookup',array('lower(lookup_key)'=>'restricted_time_start'),'dblokal');
        $is_logged_in = $this->session->userdata('is_login');
        //echo "aaa ". $this->uri->segment(1).$is_logged_in.isset($is_logged_in);
        if ($this->uri->segment(1) <> "login" && !in_array($this->uri->segment(1), array('mobile', 'mobile_ins', 'mobile_ins2', 'validate_security_code')) && $this->uri->segment(2) <> "tesrGetElement" &&
                (!isset($is_logged_in) || ($is_logged_in != TRUE && $is_logged_in != 1))) {
            if ($this->core->is_ajax()) {
                echo json_encode(array(
                    'loggedin' => FALSE,
                    'message' => 'You are not logged in. You will be redirected to login page.',
                    'redirect_url' => site_url('/login')
                ));
                exit;
            } else {
                redirect('login');
            }
        } else {
            if ($is_logged_in == true && $this->session->userdata('must_change_password') == 1 && $this->uri->segment(2) <> "ChangePassword" && $this->uri->segment(2) <> "savePassword" && $this->uri->segment(1) <> "login" && $this->uri->segment(1) <> "mobile") {
                redirect(base_url() . 'user/ChangePassword');
            }
        }
    }

    function get_prev_kolom() {
        $dblokal = $this->load->database("default", TRUE);
        $menu_id = $this->mmenu->getMenuIdCurrentURL();
        $user_id = $this->session->userdata('user_id');

        if ($menu_id == "") {
            return;
        }
        $query = $dblokal->query("SELECT a.*, max(b.element_role) as element_role
				     FROM t_mtr_user_form as a, t_mtr_user_form_role as b
				     WHERE a.form_id = b.form_id
				     AND a.status=1
					AND a.menu_id='$menu_id' AND b.user_group_id IN (SELECT user_group_id FROM t_mtr_user_multi_role WHERE user_id='$user_id')
				     GROUP BY a.form_id");
        $hide = array();
        $disabled = array();
        $jgrid = array();
        $tabs = array();

        foreach ($query->result() as $r) {
            if ($r->element_type == "form") {
                //menggunakan id
                if ($r->element_role == 0) { //hide
                    $hide[] = "#" . $r->element_name;
                } elseif ($r->element_role == 1) { //disabled element
                    $disabled[] = "#" . $r->element_name;
                }
            } elseif ($r->element_type == "tabs") {
                //u/grid
                if ($r->element_role == 0) { //hide
                    $tabs[] = str_replace("tabs_", "", $r->element_name);
                }
            } else {
                //u/grid
                if ($r->element_role == 0) { //hide
                    $hide[] = "." . $r->element_name;
                    //$jgrid[] = $r->element_name;
                    $jgrid[] = str_replace("grid_", "", $r->element_name);
                }
            }
        }
        $hasil = "";
        $hdisable = "";
        $tabdisable = "";
        $gridhidden = "";
        if (count($hide) > 0) {
            $hasil .="<style>
				" . implode(",", $hide) . "{ display: none; }
				</style>";
            //$hdisable = " $('". implode(",",$hide) ."').remove(); ";
        }

        if (count($disabled) > 0) {

            $hdisable = "
						$('" . implode(",", $disabled) . ",#foo').find('input,select,textarea').each(function() {
							$(this).attr('disabled', 'disabled');
							
						}); 

			";
        }

        if (count($tabs) > 0) {
            foreach ($tabs as $value) {
                $tabdisable.="$('.easyui-tabs').tabs('disableTab', '$value');";
            }
        }
        if (count($jgrid) > 0) {
            foreach ($jgrid as $value) {
                $gridhidden.= '
				if ($(".jgrid").length>0)
				if ($(".jgrid").datagrid("getColumnOption","' . $value . '")!=null){$(".jgrid").datagrid("hideColumn","' . $value . '");}

				if ($(".jgrid2").length>0)
				if ($(".jgrid2").datagrid("getColumnOption","' . $value . '")!=null){$(".jgrid2").datagrid("hideColumn","' . $value . '");}

				if ($(".jgridpop").length>0)
				if ($(".jgridpop").datagrid("getColumnOption","' . $value . '")!=null){$(".jgridpop").datagrid("hideColumn","' . $value . '");}

				if (subgrid != undefined && subgrid !="first")
				if ($("#"+subgrid).datagrid("getColumnOption","' . $value . '")!=null){$("#"+subgrid).datagrid("hideColumn","' . $value . '");}

				';
            }
        }

        $strPreviledgeFnc = "
					
					function doPreviledgePerfield(subgrid){
						console.log('Previledge per field generated');
						$hdisable
						if (subgrid !='first'){
							$gridhidden
							$tabdisable
						}
					}
					";


        $x = $this->db->get_where('t_mtr_lookup', array('lookup_key' => 'global', 'lookup_desc' => 'max_idle_time'));
        if ($x->num_rows() > 0)
            $max_idle_time = $x->row()->lookup_value;
        else
            $max_idle_time = 20;

        $max_idle_time = intval($max_idle_time + 3);

        $hasil .= "<script>
				idleTime = $max_idle_time;
				$(document).ready(function() {
					
				var idleInterval = setInterval('timerIncrement()', 180000); // 3 minute
					$(this).mousemove(function (e) {
						idleTime = $max_idle_time;  //durasi 20 menit +itersi
					});
					$(this).keypress(function (e) {
						idleTime = $max_idle_time;
					});
					doPreviledgePerfield('first');
					
					$hdisable
					
				});
				function timerIncrement() {
					console.log('Updating user last activity..');
					$.ajax({
						type: 'post',
						url:'" . site_url('/user/update_last_activity') . "',
						data: {et:idleTime, user_id: '" . $this->session->userdata("user_id") . "',current_url:'" . current_url() . "'},
						dataType: 'json',
						success: function(dt) {
						
							if (dt.loggedin == false ) { 
								window.location.replace('" . base_url() . "login/logout');
							}
							
							idleTime = dt.last_time;
							if(dt.last_restricted != ''){
								//console(dt.last_restricted);
								
								var unique_id = $.gritter.add({
								// (string | mandatory) the heading of the notification
								title: 'Ada yang mencoba akses akun Anda!',
								// (string | mandatory) the text inside the notification
								text: dt.last_restricted,
								// (string | optional) the image to display on the left
								image: '" . base_url() . "/file/images/logosmartfren.png',
								// (bool | optional) if you want it to fade out on its own or just sit there
								sticky: true,
								// (int | optional) the time you want it to be alive for before fading out
								time: '',
								// (string | optional) the class name you want to apply to that specific message
								class_name: 'my-sticky-class'
							});
				
							// You can have it return a unique id, this can be used to manually remove it later using
							
				
							return false;
								
							}
						}
					});
					var namaurl = '" . $this->uri->segment(1) . "';
					// sudah habis waktunya
					if (idleTime <= 0 && (namaurl != 'login' && namaurl != 'mobile') ) { 
						window.location.replace('" . base_url() . "login/logout?session_expired=true');
					}
				}
					$strPreviledgeFnc;

				</script>";

        #############
        # element Form
        /*
          1 => element dibuat disabled => jquery
          0 => hide element
         */
        # element Grid
        /*
          1/2 => show element
          0 => hide element
         */
        return $hasil;
    }

    function punya_hak_akses() {
        /* Proses pemeriksaan URL, boleh diakses/tidak?
          1. List URL yang gak boleh diakses
          2. Kalo ternyata ada, maka REDIRECT ke main
         */
        $dblokal = $this->load->database("default", TRUE);
        $userLogin = $this->session->userdata('username');

        $boleh = "";
        $url = $this->uri->uri_string();

        $query = $dblokal->query("SELECT t_menu.alias
					FROM t_menu
				        WHERE published=1 AND id NOT IN (
					       
					       SELECT t_mtr_user_role.menu_id
					       FROM t_mtr_user_role, t_mtr_user
					       WHERE t_mtr_user_role.user_group_id = t_mtr_user.user_group_id
						       AND t_mtr_user.user_id='$userLogin'
					       
				       ) ");
        foreach ($query->result() as $r) {

            $mystring = $url;
            $findme = $r->alias;
            if ($findme <> "") {
                $pos = strpos($mystring, $findme);
                if ($pos === false) {
                    $boleh .= "";
                } else {
                    $boleh .= "ada"; //ada, berarti ada URL yg dipaksa dilihat
                }
            }
        }

        if ($this->uri->segment(1) <> "main" && $this->uri->segment(1) <> "login" && $this->uri->segment(1) <> "mobile" && $boleh <> "") {
            //redirect(base_url() .'login/dashboard');
            redirect(base_url() . 'teritori');
        }
    }

    // Siakan gunakan AuditTrail()
    function saveLog($key, $act, $id, $client = "web", $user_id = "", $menuid, $date, $time) {
        $dblokal = $this->load->database("default", TRUE);
        $ip = $this->input->ip_address();
        if ($client == "web") {
            $log = Array(
                "log_key" => $key,
                "log_action" => "action=$act; id=$id; ipAddr=$ip",
                "client_id" => 2,
                "menu_code" => "$menuid",
                "created_date" => "$date",
                "created_time" => "$time",
                "user_id" => $this->session->userdata('username'));
        } else {
            $log = Array(
                "log_key" => $key,
                "log_action" => "action=$act; ipAddr=$ip",
                "client_id" => 2,
                "menu_code" => "$menuid",
                "created_date" => "$date",
                "created_time" => "$time",
                "user_id" => $id);
        }
        $dblokal->insert("t_log", $log);
    }

    function showData($data, $tbl, $where, $db = "dblokal", $like = "") {
        $dblokal = $this->load->database("default", TRUE);
        //$dbsmartfren  = $this->load->database("smartfren", TRUE);

        ${$db}->where($where);
        if ($like <> "")
            ${$db}->like($like);
        $query = ${$db}->get($tbl);
        $val = 1;
        if ($val == 0) {
            return "";
        } else {
            if ($query->num_rows() > 0) {
                $r = $query->row();
                return $r->{$data};
            } else {
                return "";
            }
        }
    }

    function countData($data, $tbl, $where, $db, $like = "") {
        $dblokal = $this->load->database("default", TRUE);

        ${$db}->where($where);
        if ($like <> "")
            ${$db}->like($like);
        $query = ${$db}->get($tbl);
        return $query->num_rows();
    }

    /**
     * @param [string] $log_key     Gunakan huruf kecil saja dan underscore
     * @param [JSON]   $log_action  Untuk keseragaman, format harus dalam array yang sudah di json_encode()
     * @param [string] $created_by  user_id
     * @param [string] $menu_id     $menu_code yang terdapat di t_mtr_menu
     */
    public function AuditTrail($log_key, $log_action, $created_by, $menu_id = null) {
        $log = array(
            'log_key' => $log_key,
            'log_action' => $log_action,
            'user_id' => $created_by,
            'client_id' => 1, // web
            'menu_code' => $menu_id,
            'created_date' => date('Y-m-d'),
            'created_time' => date('H:i:s')
        );
        $this->db->insert('t_log', $log);
    }

    ////fungsi UMUM
    //20130223 anggwa mendefinisikan fungsi umum - start
    function getUserFullName($userLogin) {
        $dblokal = $this->load->database("default", TRUE);
        return $dblokal->query("SELECT user_name FROM t_mtr_user WHERE user_id = '$userLogin' LIMIT 1;")->row()->user_name;
    }

    function set_is_login($val = 0, $id = "") {
        $dblokal = $this->load->database("default", TRUE);
        if ($id == "")
            $id = $this->session->userdata('user_id');
        $dblokal->query("UPDATE t_mtr_user SET is_login=$val WHERE user_id='" . $id . "'");
    }
	
	//start update by hadi 20181015
	function forced_set_is_login($val = 0, $id) {
        $dblokal = $this->load->database("default", TRUE);
        $dblokal->query("UPDATE t_mtr_user SET is_login=$val WHERE user_id='" . $id . "'");
    }
	//end update by hadi 20181015

    /*
      function getDbLinkSF(){
      return "ZGJuYW1lPXNtYXJ0ZnJlbiB1c2VyPWRicG1vMSBwYXNzd29yZD1OdXRlY2hEYjIwMTM=";
      }
     */

    /*     * *****************************************************************************
     * General Function
     * yaddis@nutech-integrasi.com
     * @param  [type] $site_code [description]
     * @return [type]            [description]
     * ****************************************************************************** */

    public function siteCluster($site_code) {
        $this->db->select('cluster_id_ops as cluster_id');
        $this->db->where('site_code', $site_code);
        $q = $this->db->get('t_mtr_site');
        return $q->row()->cluster_id;
    }

    // Depracated, due tue additional tabel t_mtr_cluster_mapping
    // 2014-29-04
    // public function userTerritory($user_id)
    // {
    // 	$this->db->select('ut.region_id, rr.region_code, rr.region_name');
    // 	$this->db->join('t_mtr_region rr', 'rr.region_id = ut.region_id');
    // 	$this->db->where ('ut.user_id', $user_id);
    // 	$q = $this->db->get('t_mtr_user_territory ut');
    // 	$data["region"] = $q->result_array();
    // 	$this->db->select('ut.region_id, rr.region_code, rr.region_name, ut.cluster_ops_id, co.cluster_code, co.cluster_name');
    // 	$this->db->join('t_mtr_region rr', 'rr.region_id = ut.region_id');
    // 	$this->db->join('t_mtr_cluster_ops co', 'co.cluster_ops_id = ut.cluster_ops_id');
    // 	$this->db->where ('ut.user_id', $user_id);
    // 	$q = $this->db->get('t_mtr_user_territory ut');
    // 	$data["cluster"] = $q->result_array();
    // 	return $data;
    // }


    public function userTerritory($user_id) {
        $this->db->distinct();
        $this->db->select('ur.region_id, ur.region_code, ur.region_name');
        $this->db->join('t_mtr_cluster_ops co', 'co.cluster_ops_id = cm.cluster_ops_id');
        $this->db->join('t_mtr_region ur', 'ur.region_id = co.region_id');
        $this->db->where('cm.user_id', (string) $user_id);
        $this->db->where('cm.active = 1');
        $q = $this->db->get('t_mtr_cluster_mapping cm');
        $data["region"] = $q->result_array();

        $this->db->select('ur.region_id, ur.region_code, ur.region_name, cm.cluster_ops_id, co.cluster_code, co.cluster_name');
        $this->db->join('t_mtr_cluster_ops co', 'co.cluster_ops_id = cm.cluster_ops_id');
        $this->db->join('t_mtr_region ur', 'ur.region_id = co.region_id');
        $this->db->where('cm.user_id', (string) $user_id);
        $this->db->where('cm.active = 1');
        $q = $this->db->get('t_mtr_cluster_mapping cm');
        $data["cluster"] = $q->result_array();

        return $data;
    }

    public function lookup($lookup_key) {
        $this->db->select('lookup_value, lookup_key, lookup_desc');
        $this->db->where('lookup_key', $lookup_key);
        $this->db->where('active', 1);
        $this->db->order_by('order', 'asc');
        $q = $this->db->get('t_mtr_lookup');
        return $q->result();
    }

    /* Author: alen boby
      Email : alenboby@gmail.com
      Note  : i dont't really care if my code is annoy you
     */

    function saveData($tableName, $data, $key = '') {
        if (empty($data[$key])) {
            unset($data[$key]);
            $data['created_by'] = $this->session->userdata('user_id');
            $data['created_on'] = date('Y-m-d H:i:s');
            $q = $this->db->insert($tableName, $data);
            if ($q)
                $q = $this->db->insert_id();
        }else {
            $this->db->start_cache();
            $this->db->where($key, $data[$key]);
            $this->db->stop_cache();
            $history = $this->db->get($tableName)->row();
            $metadata = $this->db->field_data($tableName);
            foreach ($metadata as $k => $value) {
                $meta[$value->name] = $value->type;
            }
            foreach ($history as $k => $value) {
                if (isset($data[$k])) {
                    if ($meta[$k] == 'timestamp')
                        $data[$k] = MakeUpDate($data[$k], 'Ymd', 'Y-m-d 00:00:00');
                    if ($data[$k] != $value)
                        $edit[$k] = $value;
                }

                if ($k == 'updated_by') {
                    if ($value != '') {
                        $edit[$k] = $value;
                    } else {
                        $edit[$k] = $history->created_by;
                    }
                }
                if ($k == 'updated_on') {
                    if ($value != '') {
                        $edit[$k] = $value;
                    } else {
                        $edit[$k] = $history->created_on;
                    }
                }
            }
            $data['updated_by'] = $this->session->userdata('user_id');
            $data['updated_on'] = date('Y-m-d H:i:s');
            unset($data[$key]);
            $q = $this->db->update($tableName, $data);
//            die($this->db->last_query());
            $this->db->flush_cache();
            if (isset($edit)) {
                if (count($edit)) {
                    $this->db->set('table_name', $tableName)
                            ->set('pk_table', $key)
                            ->set('pk_value', $history->$key)
                            ->set('created_by', $edit['updated_by'])
                            ->set('created_on', $edit['updated_on'])
                    ;

                    $this->db->insert('t_trx_history_head');
                    $id = $this->db->insert_id();
                    foreach ($edit as $k => $v) {
                        $this->db->set('parent_id', $id)
                                ->set('field_name', $k)
                                ->set('value_before', $v)
                                ->set('value_after', $data[$k]);
                        $this->db->insert('t_trx_history_detail');
                    }
                    //$this->db->insert_batch('t_trx_history_detail',$edit);
                }
            }
        }

        return $q;
    }

    /*

     */

    function ISRGetMilestone($milestone) {
        $next['next_phase'] = $milestone + 10;
        $next['doc_type'] = $milestone + 1;
        /* switch($milestone){
          case 1321:
          case 1231:
          case 1141:
          $next['next_phase']=1400;

          break;
          } */
        return $next;
    }

    function DateMakeup($date, $format = 'Y-m-d') {
        $date = str_replace('/', '-', $date);
        return date($format, strtotime($date));
    }

    /*
      My Name Is RAA,
      And I'm not Terrorist
     */

    function setData($table, $data, $arr_where = '') {
        if (!is_array($arr_where)) {
            $data['created_by'] = $this->session->userdata('user_id');
            $data['created_on'] = date('Y-m-d G:i:s');
            $this->db->insert($table, $data);
            $return = $this->db->insert_id();
        } else {
            $this->db->start_cache();
            $this->db->where($arr_where);
            $this->db->stop_cache();
            $history = $this->db->get($table)->row();

            $metadata = $this->db->field_data($table);
            foreach ($metadata as $k => $value) {
                $meta[$value->name] = $value->type;
            }
            foreach ($history as $k => $value) {
                if (isset($data[$k])) {
                    if ($meta[$k] == 'timestamp')
                        $data[$k] = MakeUpDate($data[$k], 'Ymd', 'Y-m-d 00:00:00');
                    if ($data[$k] != $value)
                        $edit[$k] = $value;
                }

                if ($k == 'updated_by') {
                    if ($value != '') {
                        $edit[$k] = $value;
                    } else {
                        $edit[$k] = $history->created_by;
                    }
                }
                if ($k == 'updated_on') {
                    if ($value != '') {
                        $edit[$k] = $value;
                    } else {
                        $edit[$k] = $history->created_on;
                    }
                }
            }
            $data['updated_by'] = $this->session->userdata('user_id');
            $data['updated_on'] = date('Y-m-d H:i:s');

            $q = $this->db->update($table, $data);
            $return = $this->db->affected_rows();
            $this->db->flush_cache();
            $this->db->select('pg_attribute.attname')
                    ->where("pg_class.oid = '$table'::regclass AND
                                  indrelid = pg_class.oid AND
                                  pg_attribute.attrelid = pg_class.oid AND 
                                  pg_attribute.attnum = any(pg_index.indkey)
                                  AND indisprimary");
            $pk = $this->db->get('pg_index, pg_class, pg_attribute')->row()->attname;
            if (isset($edit)) {
                if (count($edit)) {
                    $this->db->set('table_name', $table)
                            ->set('pk_table', $pk)
                            ->set('pk_value', $history->$pk)
                            ->set('created_by', $edit['updated_by'])
                            ->set('created_on', $edit['updated_on'])
                    ;

                    $this->db->insert('t_trx_history_head');
                    $id = $this->db->insert_id();
                    unset($edit['updated_by']);
                    unset($edit['updated_on']);
                    foreach ($edit as $k => $v) {
                        $this->db->set('parent_id', $id)
                                ->set('field_name', $k)
                                ->set('value_before', $v)
                                ->set('value_after', $data[$k]);
                        $this->db->insert('t_trx_history_detail');
                    }
                }
            }
        }
        return $return;
    }

    function checkData($table, $arr_where = '', $err_label) {
        if (is_array($arr_where)) {
            $this->db->where($arr_where);
            $this->db->from($table);
            $return = $this->db->count_all_results();
            if ($return) {
                exit(json_encode(array(
                    'status' => 'ERROR!',
                    'hasil' => "Unable to save,$err_label already exist."
                )));
            }
        }
        return false;
    }

    function checkData2($table, $arr_where = '', $err_label) {
        if (is_array($arr_where)) {
            $this->db->where($arr_where);
            $this->db->from($table);
            $return = $this->db->count_all_results();
            if ($return) {
                return true;
            }
        }
        return false;
    }

    public function updateOrder($d = NULL) {
        /* sample data
          $d['table']         = 'test';
          $d['id_table']      = 'id';
          $d['id_exception']  = 2;
          $d['column_order']  = 'tiga';
          $d['no_order']      = 3;
          $d['action']        = delete;
          $d['condition']     = array('satu'=>1,'dua'=>2); */

        if ($d != null) {
            $this->db->select($d['id_table']);
            $this->db->where($d['condition']);
            $this->db->where($d['id_table'] . ' <> ', $d['id_exception']);
            $this->db->where($d['column_order'] . ' >= ', $d['no_order']);
            $this->db->order_by($d['column_order']);
            $res_select = $this->db->get($d['table']);
            $result = $res_select->result();

            //handle when delete
            if (isset($d['action'])) {
                $d['no_order'] = $d['no_order'] - 1;
            }

            foreach ($result as $key => $value) {
                foreach ($value as $k => $v) {
                    $d['no_order'] += 1;
                    $qr = 'update ' . $d['table'] . ' set "' . $d['column_order'] . '"=' . $d['no_order'] . ' where ' . $d['id_table'] . '=' . $v;
                    $this->db->query($qr);
                }
            }
        } else {
            return;
        }
    }

    public function getEmailDefaultValue($template_name) {
        $funct = $this->db->query("select f_get_mail_notif_by_template_name('$template_name') as result");

        $result = $funct->row()->result;

        if ($result != 'false') {
            $arr_result = explode('||', $result);
            $return['subject'] = $arr_result[0];
            $return['content'] = $arr_result[1];
            $return['parameter'] = $arr_result[2];
            $arr_result[3] = str_replace('{', '', $arr_result[3]);
            $arr_result[3] = str_replace('}', '', $arr_result[3]);

            $recipient = null;
            if ($arr_result[3] != '')
                $recipient = explode(',', $arr_result[3]);
            $n = 0;

            if (count($recipient)) {
                foreach ($recipient as $key) {
                    $value = explode(';', $key);
                    $return['recipient']['single'][$n]['name'] = $value[0];
                    if (isset($value[1]))
                        $return['recipient']['single'][$n]['email'] = $value[1];
                    else
                        $return['recipient']['single'][$n]['email'] = '';
                    $return['recipient']['single'][$n++]['recipient_type'] = $value[2];
                }
            }


            $arr_result[4] = str_replace('{', '', $arr_result[4]);
            $arr_result[4] = str_replace('}', '', $arr_result[4]);
            $recipient = null;
            if ($arr_result[4] != '')
                $recipient = explode(',', $arr_result[4]);
            $n = 0;
            if (count($recipient)) {
                foreach ($recipient as $key) {
                    $value = explode(';', $key);
                    $return['recipient']['division'][$n]['name'] = $value[0];
                    $return['recipient']['division'][$n++]['recipient_type'] = $value[1];
                }
            }
//            die($return['recipient']);
            if (isset($return['recipient']))
                return json_encode($return);
            else
                return 'false';
        }else {
            return 'false';
        }
    }

    public function pushMail($template_name = '', $region_param = null, $mail_param = null, $schedule_param = null, $pic_mail = null, $tabledata = null) {
        /* $template_name   	= 'BATCH001';
          $region_param 		= array('JABODETABEK','CENTRAL JAVA');
          $mail_param 		= array('[created_on]'=>'2014-02-01','[batch_no]'=>'1','[created_by]'=>'waw','[detail]'=>'test');
          $schedule_param 	= array('date'=>date('Y-m-d'),'time'=>date('G:i:s'),'foreign_id'=>1);
          $pic_mail   		= array(
          array('name'=>'ayu','email'=>'waw@email.com','recipient_type'=> 'to'),
          //-----for registered user
          array('name'=>'ayu','email'=>'','recipient_type'=> 'to'),
          //-----for un-registered user
          array('name'=>'','email'=>'wiw@email.com','recipient_type'=> 'to')
          ); */

        $first = array();
        $replace = array();
        $getMail = $this->getEmailDefaultValue($template_name);
        if ($getMail == 'false') {
            return 0;
        }
        $mail = json_decode($getMail);
        $arr_parameter = explode(',', $mail->parameter);

        if (is_array($pic_mail)) {
            foreach ($pic_mail as $v) {
                if ($v['name'] != '') {
                    $this->raa->addSingleRecipient($v['recipient_type'], $v['name']);
                } else {
                    $this->raa->addSingleRecipient($v['recipient_type'], $v['email']);
                }
            }
        }

        foreach ($mail->recipient as $key => $value) {
            foreach ($value as $k => $v) {
                if ($key == 'single') {
                    $this->raa->addSingleRecipient($v->recipient_type, $v->name);
                } elseif ($key == 'division') {
                    foreach ($region_param as $k_region => $v_region) {
                        $this->raa->addDivisionRecipient($v->recipient_type, $v->name, $v_region);
                    }
                }
            }
        }
        
        foreach ($arr_parameter as $key => $value) {
            if (isset($mail_param[trim($value)])) {
                array_push($first, trim($value));
                array_push($replace, $mail_param[trim($value)]);
            }
        }

        $content = str_replace($first, $replace, $mail->content);
        /* $content = htmlspecialchars($content, ENT_QUOTES); */
        if ($tabledata) {
            $content = $this->MailNotifTableGenerator($content, $tabledata);
        }
        $content = str_replace("'", "''", $content);
        $this->mglobal->setEmailSchedule(1, $schedule_param['date'], $schedule_param['time'], $mail->subject, $content, $schedule_param['foreign_id']);
    }

    public function pushSMS($template_name = '', $region_param = null, $sms_param = null, $schedule_param = null, $pic_sms = null) {
        /* $template_name    = 'BATCH001';
          $region_param       = array('JABODETABEK','CENTRAL JAVA');
          $sms_param          = array('[batch_no]'=>'1','[created_by]'=>'waw');
          $schedule_param 	= array('date'=>date('Y-m-d'),'time'=>date('G:i:s'),'foreign_id'=>1);
          $pic_sms   = array(
          array('name'=>'wow','phone'=> '098765432476'),
          array('name'=>'wew','phone'=> '098765432365')); */
        $first = array();
        $replace = array();
        $getSMS = $this->mglobal->getSMSDefaultValue($template_name);
        if ($getSMS == 'false') {
            return 0;
        }
        $sms = json_decode($getSMS);
        $arr_parameter = explode(',', $sms->parameter);

        if (is_array($pic_sms)) {
            foreach ($pic_sms as $v) {
                $this->raa->addSMSSingleRecipient($v['name']);
            }
        }

        if (isset($sms->recipient)) {
			foreach ($sms->recipient as $key => $value) {
				foreach ($value as $k => $v) {
					if ($key == 'single') {
						$this->raa->addSMSSingleRecipient($v->name);
					} elseif ($key == 'division') {
						foreach ($region_param as $k_region => $v_region) {
							$this->raa->addSMSDivisionRecipient($v->name, $v_region);
						}
					}
				}
			}
		}
		
        foreach ($arr_parameter as $key => $value) {
            if (isset($sms_param[trim($value)])) {
                array_push($first, trim($value));
                array_push($replace, $sms_param[trim($value)]);
            }
        }
        $content = str_replace($first, $replace, $sms->content);
        /* $content = htmlspecialchars($content, ENT_QUOTES); */
        $content = str_replace("'", "''", $content);
        $this->mglobal->setSMSSchedule(1, $schedule_param['date'], $schedule_param['time'], $content, $schedule_param['foreign_id']);
    }

    public function getSMSDefaultValue($template_name) {
        $funct = $this->db->query("select f_get_sms_notif_by_template_name('$template_name') as result");

        $result = $funct->row()->result;
        if ($result != 'false') {
            $arr_result = explode('||', $result);
            $return['content'] = $arr_result[0];
            $return['parameter'] = $arr_result[1];
            $arr_result[2] = str_replace('{', '', $arr_result[2]);
            $arr_result[2] = str_replace('}', '', $arr_result[2]);
            $recipient = null;
            if ($arr_result[2] != '')
                $recipient = explode(',', $arr_result[2]);
            $n = 0;
            if (count($recipient)) {
                foreach ($recipient as $key) {
                    $value = explode(';', $key);
                    $return['recipient']['single'][$n]['name'] = $value[0];
                    if (isset($value[1]))
                        $return['recipient']['single'][$n++]['phone'] = $value[1];
                    else
                        $return['recipient']['single'][$n++]['phone'] = '';
                }
            }

            $arr_result[3] = str_replace('{', '', $arr_result[3]);
            $arr_result[3] = str_replace('}', '', $arr_result[3]);
            $recipient = null;
            if ($arr_result[3] != '')
                $recipient = explode(',', $arr_result[3]);
            $n = 0;
            if (count($recipient)) {
                foreach ($recipient as $key) {
                    $value = explode(';', $key);
                    $return['recipient']['division'][$n++]['name'] = $value[0];
                }
            }

            return json_encode($return);
        } else {
            return 'false';
        }
    }

    public function setEmailSchedule($email_type_id, $email_date, $email_time, $subject, $content, $foreign_id) {
        $a = $this->db->query("select f_set_notif_mail($email_type_id,'$email_date','$email_time','$subject','$content',$foreign_id,'" . $this->raa->getMailRecipient() . "','" . $this->session->userdata('user_id') . "') AS email_id");
        /* print_r("<pre>select f_set_notif_mail($email_type_id,'$email_date','$email_time','$subject','$content',$foreign_id,'".$this->raa->getMailRecipient()."','".$this->session->userdata('user_id')."')</pre>");
          die(); */
        //$a->result();
        return $a->row()->email_id;
    }

    public function setSMSSchedule($sms_type_id, $sms_date, $sms_time, $content, $foreign_id) {
        $a = $this->db->query("select f_set_notif_sms($sms_type_id,'$sms_date','$sms_time','$content',$foreign_id,'" . $this->raa->getSMSRecipient() . "','" . $this->session->userdata('user_id') . "')");
        //echo "select f_set_notif_mail($email_type_id,'$email_date','$email_time','$subject','$content',$foreign_id,'".$this->raa->getMailRecipient()."','".$this->session->userdata('user_id')."')";
        $a->result();
        $this->raa->clearSMSRecipient();
    }

    public function getSiteInfo($site_id) {
        $this->db->select('ss.site_id, ss.site_code, ss.site_name, ss.vendor_id, ss.site_address, rr.region_id, rr.region_name, cc.cluster_ops_id, cc.cluster_name');
        $this->db->join('t_mtr_region rr', 'rr.region_id = ss.region_id');
        $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
        $this->db->where('ss.site_id', $site_id);
        $r = $this->db->get('t_mtr_site ss');
        return $r->row_array();
    }

    public function getUserInfo($user_id) {
        $this->db->select('uu.user_id, uu.user_name');
        $this->db->where('uu.user_id', $user_id);
        $r = $this->db->get('t_mtr_user uu');
        return $r->row_array();
    }

    /*
      Another RAA footprints left here
     */

    public function MailNotifTableGenerator($template, $data) {
        $table = strstr($template, '{table}');
        $template = substr($template, 0, strpos($template, '{table}'));
        $header = strstr($table, '{header}');
        $header = substr($header, 8, strpos($header, '{/header}') - 8);

        $row = strstr($table, '{content}');
        $row = substr($row, 9, strpos($row, '{/content}') - 9);

        $header_arr = explode(';', $header);
        $header_str = '';
        $row_str = '';
        $row_arr = explode(';', $row);
        foreach ($header_arr as $k => $v) {
            $header_str.= "<th>$v</th>";
        }
        $row = '<tr>';
        foreach ($row_arr as $k => $v) {
            $row.="<td>" . $v . "</td>";
        }
        $row.='</tr>';
        foreach ($data as $k => $v) {
            $row_tmp = $row;
            foreach ($v as $k2 => $v2) {
                $row_tmp = str_replace($k2, $v2, $row_tmp);
            }
            $row_str.=$row_tmp;
        }
        return $template . '<table border=1>' . $header_str . $row_str . '</table>';
    }

    public function getAllRegions() {
        $this->db->where('is_deleted', 0);
        $this->db->order_by('order_no', 'asc');
        $q = $this->db->get('t_mtr_region_xl');
		
        return $q->result();
    }

    public function getAllRoles() {
        $this->db->where('status', 1);
        $this->db->order_by('user_group_name', 'asc');
        $q = $this->db->get('t_mtr_user_group');
        return $q->result();
    }

    public function getAllCluster($region_id) { //{^_^) andedi 20141212
        $this->db->where('region_id', $region_id);
        $this->db->where('status', 1);
        $this->db->order_by('cluster_name', 'asc');
        $q = $this->db->get('t_mtr_cluster_ops');
        return $q->result();
    } 

    /*Get PO Number for DIN*/
    public function getAllPo() {
        $this->db->where('is_deleted', 0);
        $this->db->or_where('is_deleted', NULL);        
        $this->db->order_by('po_date', 'desc');
        $q = $this->db->get('t_trx_po_xl');
        
        return $q->result();
    }
}
