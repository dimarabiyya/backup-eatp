<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class User extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('muser');
    $this->mglobal->is_logged_in();
  }

  //Manag.Menu
  public function index() {
    $this->UserManagement();
  }

  public function UserManagement() {
    $url = $this->uri->segment(2) != '' ? $url = $this->uri->segment(1) . '/' . $this->uri->segment(2) : $this->uri->segment(1);
    $menu = $this->mmenu->getMenuId($url);
    if ($this->mmenu->cekPermission($menu, 'menu_view') == false) {
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }

    $data['menu'] = $menu;
    $data['judul'] = "User Management";
    $data['content_id'] = "user/f-user";

    $data['main_division_opt'] = array();
    $rows = $this->muser->getUserDivision();
    foreach ($rows as $row) {
      $data['main_division_opt'][$row->user_div_id] = $row->user_div_name;
    }


    $this->load->view("template/smashing", $data);
  }

  public function EditUser() {

    $getData = $this->muser->loadDataUserByID($this->input->post('id'));
  }

  public function deleteData() {
    $this->muser->deleteData();
  }

  public function loadData() {
    $items = array();
    $d = $this->input->post();

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'user_name';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset = ($page - 1) * $rows;

    $result["total"] = $this->muser->countData($d, $sort, $order);
    $qr = $this->muser->loadData($d, $sort, $order, $rows, $offset);

    //$edit = $this->mmenu->cekPermission($menu, 'menu_edit' );
    //$del  = $this->mmenu->cekPermission($menu, 'menu_delete' );

    foreach ($qr->result() as $row) {
      $row->status = ($row->status == 1) ? 'Active' : 'Not Active';
      $modulEdit = "doEdit('" . $row->user_id . "')";
      $modulDelete = "doDelete('" . $row->user_id . "')";
      $modulReset = "doResetPassword('" . $row->user_id . "')";
      $modulBounce = "doBounceUser('" . $row->user_id . "')";

      $row->login_status = ($row->is_login == 1) ? '<span class="badge badge-success">Online</span>' : '<span class="badge badge-default">Offline</span>';

      $row->action =
              '<a title="Edit" href="#" class="btn-action glyphicons pencil btn-success" onclick="' . $modulEdit . '"><i></i></a>
				 <a title="Delete" href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="' . $modulDelete . '"><i></i></a>
				 <a title="Reset Password" href="#" class="btn-action glyphicons lock btn-danger" onclick="' . $modulReset . '"><i></i></a>
				 <a title="Bounce User" href="#" class="btn-action glyphicons warning_sign btn-danger" onclick="' . $modulBounce . '"><i></i></a>
				 ';
      array_push($items, $row);
    }
    $result["rows"] = $items;
    echo json_encode($result);
  }

  public function addeditdata() {
    $input = array('id', 'name', 'parent_id', 'alias');
    foreach ($input as $val) {
      $data[$val] = "";
    }
    $data['id'] = $this->input->post('id');
    $data['judul'] = "Add Page";
    if ($data['id'] <> "") {
      $data['judul'] = "Edit User";
      $getDetail = $this->muser->getDetailUser($data['id']);
      foreach ($getDetail as $r) {
        foreach ($input as $val) {
          $data[$val] = $r->$val;
        }
      }
    }
    echo $this->load->view("menu/r-menu", $data, TRUE);
  }

  public function saveData()
  {
    //lakukan validasi di sini
    $input = array('id', 'user_id', 'nama', 'status', 'usergroup', 'reportingto', 'hape', 'email', 'mcpass', 'region', 'cluster', 'cluster_net', 'cluster_com', 'dummyuser', 'internaluser', 'userdiv', 'mobile_type', 'imei', 'main_division_id');
    foreach ($input as $val)
      $$val = $this->input->post($val);
    $status = "ERROR";
    $hasil = "";
    $edit = "";

    if ($id != "")
    {
      	$user_id = $id;
      	$edit = "ya";
    }

    if ($user_id == "" || ($edit == '' && $user_id != "" && $this->mglobal->showdata("user_id", "t_mtr_user", array("user_id" => $user_id), "dblokal") != "") || $nama == "" || $hape == "" || $email == "" || $usergroup == "" || count($usergroup) == 0 || $userdiv == "" || count($userdiv) == 0 || $region == "" || count($region) == 0 || ($mobile_type != -1 && $imei == "") || $main_division_id == ""
    )
    {
      	$hasil = "";
      	
      	if ($user_id == "")
      	{
        	$hasil .= "=> User ID is required. \n";
      	}
      
      	if ($edit == '' && $user_id != "" && $this->mglobal->showdata("user_id", "t_mtr_user", array("user_id" => $user_id), "dblokal") != "")
      	{
        	$hasil .= "=> User ID already used. \n";
      	}
      
      	if ($nama == "")
      	{
        	$hasil .= "=> Name is required. \n";
      	}
      	
      	if ($usergroup == "" || count($usergroup) == 0)
      	{
        	$hasil .= "=> User Role is required. \n";
      	}
      	
      	if ($userdiv == "" || count($userdiv) == 0)
      	{
        	$hasil .= "=> User Group is required. \n";
      	}

      	if ($region == "" || count($region) == 0)
      	{
        	$hasil .= "=> Region is required. \n";
      	}
      	
      	if ($hape == "")
      	{
        	$hasil .= "=> Mobile Number is required. \n";
      	}
      	
      	if ($email == "")
      	{
        	$hasil .= "=> Email is required. \n";
      	}
      	
      	if ($mobile_type != -1 && $imei == "")
      	{
        	$hasil .= "=> MEID is required. \n";
      	}
      	
      	if ($main_division_id == "")
      	{
        	$hasil .= "=> Main Division is required. \n";
      	}
      
      	/*
      	if ($mcpass == "")
      	{
        	$hasil .= "=> Change Password harus dipilih.";
      	}
      	*/
    }
    else
    {
    	$error = array();

    	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    	{
      		$error[] = "=> Email address [{$email}] is considered invalid.";
      	}

      	if (count($error) > 0)
      	{
      		echo json_encode(array(
      			'status' => 'ERROR',
      			'hasil' => implode("\n", $error)
      		));
      		return;
      	}
      	else
      	{
	      	//simpan data ke sini
	      	$status = "OK";
	      	$this->muser->saveData();
	    }
    }

    echo json_encode(array(
        'status' => $status,
        'hasil' => $hasil
    ));
    
    return;
  }

  ########################################################################

  function user_repto() {
    $group = $this->input->post('group_id');
    $this->muser->getUserReportingTo($group);
  }

  ########################################################################

  public function resetPassword() {
    $this->muser->resetPassword();
  }

  public function ChangePassword() {
    $data['judul'] = "Change Password";
    $data['content_id'] = "user/f-change-password";
    $data['getSQ'] = $this->muser->getSecurityQuestion($this->session->userdata('user_id'));
    $this->load->view("template/smashing", $data);
  }

  function savePassword() {
    $this->load->model('muser');
    $input = array('old_password', 'new_password', 'cnew_password', 'question1', 'answer1', 'question2', 'answer2');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $status = "ERROR";
    $hasil = base_url() . "main";

    $data['getSQ'] = $this->muser->getSecurityQuestion($this->session->userdata('user_id'));
    if ($old_password == "") {
      $status = "ERROR";
      $hasil = 'Old Password is required.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Old Password is required.</div>';
    } elseif ($old_password != "" && $this->mglobal->showdata("user_id", "t_mtr_user", array("user_id" => $this->session->userdata('user_id'),
                "user_password" => md5($this->input->post('old_password'))), "dblokal") == "") {
      $status = "ERROR";
      $hasil = 'Incorrect Password.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Old Password is required.</div>';
    } elseif ($new_password == "") {
      $status = "ERROR";
      $hasil = 'New Password is required.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>New Password is required.</div>';
    } elseif ($cnew_password == "") {
      $status = "ERROR";
      $hasil = 'Confirm New Password is required.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Confirm New Password is required.</div>';
    } elseif ($new_password <> $cnew_password) {
      $status = "ERROR";
      $hasil = 'Confirm New Password is not same with New Password.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Confirm New Password is not same with New Password.</div>';
    } elseif (strlen($new_password) < 8) {
      $status = "ERROR";
      $hasil = 'New Password must contain minimum 8 character.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>New Password must contain minimum 8 character.</div>';
    } elseif (!(preg_match('/[A-Za-z]/', $new_password) && preg_match('/[0-9]/', $new_password))) {
      $status = "ERROR";
      $hasil = 'New Password must contain alphanumeric.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>New Password must contain alphanumeric.</div>';
    } else {

      if (count($data['getSQ']) == 0) {
        if ($question1 == "") {
          $status = "ERROR";
          $hasil = 'Question 1 is required.';
          //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Question 1 is required.</div>';
        } elseif ($answer1 == "") {
          $status = "ERROR";
          $hasil = 'Answer 1 is required.';
          //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Answer 1 is required.</div>';
        } elseif ($question2 == "") {
          $status = "ERROR";
          $hasil = 'Question 2 is required.';
          //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Question 2 is required.</div>';
        } elseif ($answer2 == "") {
          $status = "ERROR";
          $hasil = 'Answer 2 is required.';
          //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Answer 2 is required.</div>';
        } else {
          $this->muser->saveSecurityQuestion();
        }
      }


      $status = "OK";
      $hasil = base_url() . "main";
      $this->muser->savePassword();

      $userdata = array('must_change_password' => 0);
      $this->session->set_userdata($userdata);
    }

    echo json_encode(array(
        'status' => $status,
        'hasil' => $hasil
    ));
    return;
  }

  ########################################################################
  //Security Question

  public function ChangeSecurityQuestion() {
    $input = array('question1', 'answer1', 'question2', 'answer2');
    foreach ($input as $val) {
      $data[$val] = "";
    }

    $getDetail = $this->muser->getSecurityQuestion($this->session->userdata('user_id'));
    foreach ($getDetail as $r) {
      foreach ($input as $val) {
        $data[$val] = $r->$val;
      }
    }

    $data['judul'] = "Change Security Question";
    $data['content_id'] = "user/f-change-security-question";

    $this->load->view("template/smashing", $data);
  }

  function saveSecurityQuestion() {
    $this->load->model('muser');
    $input = array('question1', 'question2', 'answer1', 'answer2');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $status = "ERROR";
    $hasil = base_url() . "main";

    if ($question1 == "") {
      $status = "ERROR";
      $hasil = 'Question 1 is required.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Question 1 is required.</div>';
    } elseif ($answer1 == "") {
      $status = "ERROR";
      $hasil = 'Answer 1 is required.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Answer 1 is required.</div>';
    } elseif ($question2 == "") {
      $status = "ERROR";
      $hasil = 'Question 2 is required.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Question 2 is required.</div>';
    } elseif ($answer2 == "") {
      $status = "ERROR";
      $hasil = 'Answer 2 is required.';
      //$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Answer 2 is required.</div>';
    } else {
      $this->muser->saveSecurityQuestion();
      $status = "OK";
    }

    echo json_encode(array(
        'status' => $status,
        'hasil' => $hasil
    ));
    return;
  }

  function cekUserID() {
    $user_id = $this->input->post('user_id');
    $status = "ERROR";

    if ($this->mglobal->showdata("user_id", "t_mtr_user", array("user_id" => $user_id), "dblokal") == "") {
      $status = "OK";
    } else {
      $status = "ERROR";
    }

    echo json_encode(array(
        'status' => $status
    ));
    return;
  }

  ########################################################################

  function bounceUser() {
    //set is_login=0
    //set trx_last_activity = 0
    $this->muser->bounceUser();
  }

  ########################################################################

  function update_last_activity() {
    $et = $this->input->post('et');
    if ($et < 0)
      $et = 0;
    echo $this->muser->update_last_activity($this->input->post('user_id'), $et, $this->input->post('current_url'));
  }

  function get_last_activity() {
    echo $this->muser->update_last_activity($this->input->post('user_id'));
  }

  ########################################
  #dody@3jan

  public function EditProfile() {
    $input = array('question1', 'answer1', 'question2', 'answer2');
    foreach ($input as $val) {
      $data[$val] = "";
    }

    $getDetail = $this->muser->getSecurityQuestion($this->session->userdata('user_id'));
    foreach ($getDetail as $r) {
      foreach ($input as $val) {
        $data[$val] = $r->$val;
      }
    }

    $data['judul'] = "Edit Profile";
    $data['content_id'] = "user/f-user-profile";

    $this->load->view("template/smashing", $data);
  }

  public function saveEditProfile() {
    //lakukan validasi di sini
    $input = array('nama', 'email', 'hape');
    foreach ($input as $val)
      $$val = $this->input->post($val);
    $user_id = $this->session->userdata('user_id');

    $status = "ERROR";
    $hasil = "";
    $edit = "";
    if ($nama == "" || $hape == "" || $email == "") {
      $hasil = "Terdapat kesalahan: \n";
      if ($nama == "") {
        $hasil .= "=> Name harus diisi \n";
      }
      if ($hape == "") {
        $hasil .= "=> Mobile Number harus diisi \n";
      }
      if ($email == "") {
        $hasil .= "=> Email harus diisi \n";
      }
    } else {
      //simpan data ke sini
      $status = "OK";
      $this->muser->saveEditProfile();

      $userdata = array(
          'user_name' => $nama,
          'email' => $email,
          'mobile_number' => $hape
      );
      $this->session->set_userdata($userdata);
    }
    echo json_encode(array(
        'status' => $status,
        'hasil' => $hasil
    ));
    return;
  }

  public function showPilihCluster() {
    $data['region'] = $this->input->post('region');
    $data['icluster'] = $this->input->post('cluster');
    echo $this->load->view("user/r-user-cluster-ops", $data, TRUE);
  }

  //////////////////////////////////////////////////////////////////////////////
  //mobile user
  public function MobileUser() {
    $url = $this->uri->segment(2) != '' ? $url = $this->uri->segment(1) . '/' . $this->uri->segment(2) : $this->uri->segment(1);
    $menu = $this->mmenu->getMenuId($url);
    if ($this->mmenu->cekPermission($menu, 'menu_view') == false) {
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }

    $data['judul'] = "Mobile User";
    $data['content_id'] = "user/f-mobile-user";

    $this->load->view("template/smashing", $data);
  }

  public function saveDataMobileUser() {
    //validasi kolom harus diisi
    $input = array('user_id', 'mobile_type');
    foreach ($input as $val)
      $$val = $this->input->post($val);
    $status = "ERROR";
    $hasil = "";
    if ($user_id == "") {
      $hasil = "User ID Is Required";
    } else {
      $status = "OK";
      $this->muser->saveDataMobileUser();
    }
    echo json_encode(array(
        'status' => $status,
        'hasil' => $hasil
    ));
    return;
  }

  public function loadDataMobileUser2() {
    $this->muser->loadDataMobileUser();
  }

  public function loadDataMobileUser() {
    $items = array();
    $d = $this->input->post();

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'user_name';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset = ($page - 1) * $rows;

    $q = $this->muser->loadDataMobileUser($d, $sort, $order);
    $result["total"] = $q->num_rows();
    $qr = $this->muser->loadDataMobileUser($d, $sort, $order, $rows, $offset);

    foreach ($qr->result() as $row) {
      //$row->status = ($row->status==1) ? 'Active' : 'Not Active' ;
      $modulDelete = "doDelete('" . $row->user_id . "')";
      $row->action =
              '<a title="Delete" href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="' . $modulDelete . '"><i></i></a>
			   ';

      array_push($items, $row);
    }
    $result["rows"] = $items;
    echo json_encode($result);
  }

  public function deleteDataMobileUser() {
    $this->_is_ajax();
    
    $resp = array();
    $id = $this->input->post('id');
    if ($id) {
      $query = $this->muser->deleteDataMobileUser($id);
    } else {
      $query = FALSE;
    }
    
    if ($query) {
      $resp = array(
          'status' => 'success',
          'message' => 'Mobile user successfully deleted.'
      );
    } else {
      $resp = array(
          'status' => 'error',
          'message' => 'Mobile user could not be deleted.'
      );
    }
    
    echo json_encode($resp);
  }

  public function searchEsc() {

    $data['judul'] = "List User";
    $data['seq'] = $this->input->get('seq');
    $this->load->view("user/form_search_user", $data);

    return;
  }

  public function getAllEsc() {

    $d = $this->input->post();
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $q = isset($_POST['q']) ? $_POST['q'] : '';

    $offset = ($page - 1) * $rows;

    $qr = $this->muser->getEscNew($q, $rows, $offset);
  }

  public function getEscUser($id = "") {

    //$id =  $this->uri->segment(4);			
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $q = isset($_POST['q']) ? $_POST['q'] : '';
    $offset = ($page - 1) * $rows;

    $qr = $this->muser->getEscUser($q, $rows, $offset, $id);
  }

  //get all user (Andedi 20141014)
  public function getAllUser() {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $user = isset($_POST['user']) ? $_POST['user'] : '';
    $group = isset($_POST['group']) ? $_POST['group'] : '';
    $offset = ($page - 1) * $rows;
    $data['rows'] = $this->muser->getAllUser($user, $group, $rows, $offset);
    $data['total'] = $this->muser->getAllUserCount($user, $group);
    echo json_encode($data);
  }

  //form search all user (Andedi 20141014)
  public function searchAllUser() {
    $data['judul'] = "User List";
    $data['seq'] = $this->input->get('seq');
    $data['division'] = $this->muser->getAllUserGroup();
    $this->load->view("user/form_search_all_user", $data);
    return;
  }

  /* ADDED BY ALEN */
  /* MOBILE USER MANAGEMENT */

  //mobile user
  public function MobileUserManagement($param = '', $user_id = '') {
    $url = $this->uri->segment(2) != '' ? $url = $this->uri->segment(1) . '/' . $this->uri->segment(2) : $this->uri->segment(1);
    $menu = $this->mmenu->getMenuId($url);
    if ($this->mmenu->cekPermission($menu, 'menu_view') == false) {
      $data['content_id'] = "error/forbidden";
      $this->load->view('template/smashing', $data);
      return;
    }
    if ($param == 'edit' && $user_id != '') {
      $data['data'] = $this->muser->loadDataMobileUserSingle($user_id)->row();
      $this->load->view('user/f-mobile-user-edit', $data, FALSE);
      return;
    } elseif ($param == 'save') {
      $this->muser->saveMobileUserSingle();
      return;
    }

    $data['judul'] = "Mobile User Management";
    $data['content_id'] = "user/f-mobile-user-management";

    $this->load->view("template/smashing", $data);
  }

  public function saveMobileUserManagement() {
    $this->_is_ajax();
    
    $resp = array();
    if ($this->_validate_mobile_user() == TRUE) {
      $query = $this->muser->saveMobileUserManagement($this->input->post());
      if ($query) {
        $resp = array(
            'status' => 'success',
            'message' => 'Mobile user successfully saved.'
        );
      } else {
        $resp = array(
            'status' => 'error',
            'message' => 'Mobile user could not be saved.'
        );
      }
    } else {
      $resp = array(
            'status' => 'warning',
            'message' => validation_errors()
        );
    }
    
    echo json_encode($resp);
  }
  
  private function _validate_mobile_user() {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<div>* ', '</div>');
    $this->form_validation->set_rules('user_id[]', 'User ID', 'trim|required');
    $this->form_validation->set_rules('meid[]', 'MEID', 'trim|required');
    $this->form_validation->set_rules('mdn[]', 'MDN', 'trim|required');
    
    return $this->form_validation->run();
  }


  public function loadDataMobileUserManagement() {
    $items = array();
    $d = $this->input->post();
    $menu = $this->mmenu->getMenuId("user/MobileUserManagement");

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'user_name';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset = ($page - 1) * $rows;

    $q = $this->muser->loadDataMobileUserManagement($d, $sort, $order);
    $result["total"] = $q->num_rows();
    $qr = $this->muser->loadDataMobileUserManagement($d, $sort, $order, $rows, $offset);

    foreach ($qr->result() as $row) {
      //$row->status = ($row->status==1) ? 'Active' : 'Not Active' ;
      $modulDelete = "doDelete('" . $row->user_id . "')";
      $modulEdit = "doEdit('" . $row->user_id . "')";
      $row->action = "";
      if ($this->mmenu->cekPermission($menu, 'menu_edit')) {
        $row->action .= '<a href="#" title="Edit" class="btn-action glyphicons pencil btn-success"  onclick="' . $modulEdit . '"><i></i></a>';
      }
      if ($this->mmenu->cekPermission($menu, 'menu_delete')) {
        $row->action .=
                ' <a title="Delete" href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="' . $modulDelete . '"><i></i></a>
			   ';
      }

      $row->version_number = $this->_get_status($row->active, $row->version_number);

      array_push($items, $row);
    }
    $result["rows"] = $items;
    echo json_encode($result);
  }

  private function _get_status($param, $label) {
    $status = '';
    if ($param == 1) {
      $status = '<span title="APK Version is up to date." class="badge badge-success"><i></i>' . $label . '</span>';
    } else if ($param === '0') {
      $status = '<span title="APK Version is expired." class="badge badge-default">' . $label . '</span>';
    } else {
      $status = '<span title="APK Version is expired." class="badge badge-important">Not Record</span>';
    }
    return $status;
  }

  public function formLookupUser() {

    $data['judul'] = "Select User to Add";
    $data['seq'] = $this->input->get('seq');
    $this->load->view("user/look_up_user_complete", $data);

    return;
  }
  
  private function _is_ajax() {
    if (!$this->input->is_ajax_request()) {
      show_404();
    }
  }

  /* END MOBILE  USER MANAGEMENT */
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */