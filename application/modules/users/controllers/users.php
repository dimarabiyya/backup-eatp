<?php

class Users extends MX_Controller {

	private $_redirect = '/dashboard';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library(array('form_validation', 'core'));
		
		$this->lang->load('users/users', 'indonesia');
		
		$this->load->helper('cookie');
		$this->load->helper('language');
		
		$this->load->model('users_model');
		$this->load->model('mglobal');
		$this->load->model('musers');
		$this->load->model('mcluster');
		$this->load->model('mmaster');
	}
	
	public function index()
	{
		redirect('dashboard');
	}
	
	public function dashboard()
	{
		if (!$this->session->userdata('session_loggedin')) {
			redirect('users/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->theme->set('page_title', 'PMTS - Dashboard');
		$this->theme->view('dashboard');
	}
	
	public function login($redirect = '')
	{
		if ($redirect == '') {
			$redirect = $this->_redirect;
		} else {
			$redirect = $this->core->decode($redirect);
			// hanya izinkan redirect ke halaman internal (cegah open redirect)
			if (preg_match('#^([a-z][a-z0-9+.-]*:|//|\\\\)#i', $redirect)) {
				$redirect = $this->_redirect;
			}
		}
		
		$data['heading_title'] = 'Login';
		
		if ( !$this->session->userdata('session_loggedin') ) {
			if ($this->input->post('cmd') == 'login') {
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				$code = $this->input->post('code');
				
				require_once FCPATH.'securimage/securimage.php';
				$securimage = new Securimage();

				if ($securimage->check($code) == FALSE) {
					$this->form_validation->set_error('Security code you entered is incorrect.');
				
				} elseif ($users = $this->users_model->authGranted($username, md5($password))) {
					$this->session->set_userdata('session_loggedin', TRUE);
					$this->session->set_userdata('session_user', 'session_user');
					$this->session->set_userdata($users);
					
					// nyomod dari ES-TEH-ES (bodo dah ah!)
					$userdata = array (
						'username'     => $username,
						'pwd'          => $this->input->post('password'),
						'group'        => $this->mglobal->showdata("user_group_id", "t_mtr_user", array("user_id"=>$username),"dblokal"),
						'jabatan'      => 'admin', //edited : alfin akhret
						'is_logged_in' => true,
						'cluster_id'   => $this->mglobal->user_cluster($username),
						'region_id'    => $this->mglobal->user_region($this->mglobal->user_cluster($username)),
					);
					$this->session->set_userdata($userdata);
					 //is_self_service //edited by Alfin Akhret
					$data2 = $this->mglobal->get_is_self_service($this->session->userdata('group'));
					$this->session->set_userdata(array('is_self_service' => $data2));  
					//------------------------------------------------------------------------------// 

					$this->mglobal->set_is_login(1);
					$this->musers->update_login_attemp($username,0);
					// redirect(base_url() . "index.php/main/welcome");

					//by : alfin akhret
					//check password expiration date
					$last_update_pwd = $this->mglobal->showdata('last_time_change_password','t_mtr_user',array('user_id'=>$this->input->post('username')),'dblokal');
					$rentang = $this->mglobal->showdata('lookup_value','t_mtr_lookup',array('lower(lookup_key)'=>'account_expired'),'dblokal');
					// end nyomod
					
					// unset data sesi dangerz
					$this->session->unset_userdata('user_password');
					$this->session->unset_userdata('pwd');
					
					redirect($redirect);
				} else {
					$this->form_validation->set_error('<p>Username or password is invalid.</p>');
				}
			}
			
		} else {
			if ($redirect != '') {
				redirect($redirect);
			}
		}
		
		$this->theme->set('page_title', 'PMTS - LOGIN');
		$this->theme->view('login', $data);
	}
	
	public function logout($redirect = '')
	{
		if ($redirect == '') {
			$redirect = '';
		} else {
			$redirect = $redirect;
		}
		
		$this->load->helper('cookie');
		
		// destroy any cookies
		delete_cookie('user');
		
		$this->mglobal->set_is_login(0);
		$this->mcluster->clean_table();
		
		// destroy session
		$this->session->sess_destroy();
			
		redirect('users/login/' . $redirect);
	}
	
	public function security_code()
	{
		require_once FCPATH . 'securimage/securimage.php';
		
		$img = new Securimage();
		
		$img->num_lines = 0;
		//$img->ttf_file = FCPATH . 'securimage/Verdana.ttf';
		$img->image_width = 120;
		$img->image_height = 40;
		$img->background_directory = FCPATH . 'securimage/backgrounds';
		
		$img->show();
	}
	
	public function group_role($group_id = '')
	{
		if (!$this->session->userdata('session_loggedin')) {
			redirect('users/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$data['group'] = $group_id;
		
		$groups = $this->users_model->get_user_group();
		
		$data['user_groups'] = array('' => 'Select');
		foreach ($groups as $grp) {
			$data['user_groups'][$grp['user_group_id']] = $grp['user_group_name'];
		}
		
		if ($this->core->is_ajax()) {
			if ($this->input->post('cmd') == 'SAVE_USER_ROLE_ACTION') {
				$group = $this->input->post("groupid");
                $menu  = $this->input->post("menuid");
				$actid = $this->input->post("actid");
                $nilai = $this->input->post("nilai");
                $this->users_model->saveUserRoleAction($group,$menu,$nilai,$actid);
				
				echo json_encode(array(
					"status"=>"OK",
					"pesan" =>"Success"
				));
				exit;
			}
			
			if ($this->input->post('cmd') == 'SAVE_USER_ROLE') {
				$group = $this->input->post("groupid");
                $menu  = $this->input->post("menuid");
                $nilai  = $this->input->post("nilai");
                $this->users_model->saveUserRole($group,$menu,$nilai);
				
				echo json_encode(array(
					"status"=>"OK",
					"pesan" =>"Success"
				));
				return;
				exit;
			}
		}
		
		$this->theme->set('page_title', lang('text_page_title'));
		$this->theme->view('group_role', $data);
	}
	
	public function user_management()
	{
		if (!$this->session->userdata('session_loggedin')) {
			redirect('users/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$this->load->library('pagination');
		
		$per_page = 20;
		$offset = $this->uri->segment(3);
		$data['users'] = $this->users_model->get_users_all($per_page, $offset);
		
		$config = array(
			'base_url'		=> base_url('/users/user_management'),
			'total_rows'	=> $this->users_model->get_total_rows(),
			'per_page'		=> $per_page,
			'uri_segment'	=> 3
		);
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->theme->set('page_title', lang('text_page_title'));
		$this->theme->view('user_management', $data);
	}
	
	public function create_user()
	{
		if (!$this->session->userdata('session_loggedin')) {
			redirect('users/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$data['user_status'] = $this->users_model->getUserComboStatus();
		foreach ($this->users_model->getUserComboStatus() as $user_status) {
			$data['user_status'][$user_status['member_value']] = $user_status['member_display'];
		}
		
		$data['users_group'] = array();
		foreach ($this->users_model->get_user_group() as $group) {
			$data['users_group'][$group['user_group_id']] = $group['user_group_name'];
		}
		
		$data['reporting_to'] = array();
		foreach ($this->users_model->getUserReportingTo(1) as $report_to) {
			$data['reporting_to'][$report_to->user_id] = $report_to->user_name;
		}
		
		$this->form_validation->set_error_delimiters('<li>','</li>');
		$this->form_validation->set_message('required', 'Kolom <b>%s</b> harus diisi.');
		$this->form_validation->set_message('valid_email', 'Kolom <b>%s</b> harus diisi dengan alamat email yang benar.');
		
		if ($this->form_validation->run() != FALSE){
			// masukkan data di sini
			$this->users_model->saveUser($id);
			$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
			redirect('users/user_management');
		}
		
		if ($this->core->is_ajax()) {
			
			if ($this->input->post('cmd') == 'USER_REPORTING_TO') {
				echo json_encode(array(
					'status' => 'OK',
					'users_to' => $this->users_model->getUserReportingTo($this->input->post('usergroup'))
                ));
				exit;
			}
			
			if ($this->input->post('cmd') == 'SAVE_USER') {
				$name = $this->input->post("name");
				$email = $this->input->post("email");
				$username = $this->input->post("username");
				$olduser = $this->input->post("olduser");
				$nik = $this->input->post("nik");
                $usergroup = $this->input->post("usergroup");
                $reportingto = $this->input->post("reportingto");
				$hape = $this->input->post("hape");
				$proses = $this->input->post("proses");
				
				$cek_nik = $this->users_model->showdata('nik','t_mtr_user',array("nik"=>$nik),'dblokal');
				$cek_user = $this->users_model->showdata('user_id','t_mtr_user',array("user_id"=>$username),'dblokal');
				$cek_email = $this->users_model->showdata('user_id','t_mtr_user',array("email"=>$email),'dblokal');
				
				$vemail="";
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$vemail="salah";
				}
						
				if ($name=="" || $username=="" || $nik=="" || $hape=="" || $reportingto=="" || $reportingto=="null"
				   || ($nik<>""&&$cek_nik<>"") || ($username<>""&&$cek_user<>"")
				   || ($email<>""&&$cek_email<>"") || ($email<>""&&$vemail<>"") ){
						
						$pesan = "Data kurang lengkap:\n";
						if($name==""){
								$pesan .="- Kolom Nama harus diisi\n";
						}
						if($username==""){
								$pesan .="- Kolom Username harus diisi\n";
						}else{
								if($cek_user<>""){
										$pesan.="- Username sudah digunakan\n";
								}
						}
						if($nik==""){
								$pesan .="- Kolom NIK harus diisi\n";
						}else{
								if($cek_nik<>""){
										$pesan.="- NIK sudah digunakan\n";
								}
						}
						if($reportingto==""){
								$pesan .="- Kolom Reporting To harus diisi\n";
						}
						
						if($hape==""){
								$pesan .="- Kolom Handphone harus diisi\n";
						}
						if($email==""){
								$pesan .="- Kolom Email harus diisi\n";
						}else{
								if($vemail<>""){
										$pesan.="- Format Email Salah\n";
								}elseif($cek_email<>""){
										$pesan.="- Email sudah digunakan\n";
								}
						}
						
						$status = 'ERROR';
				} else {
					$this->users_model->saveUser();
					$status= "OK";
					$pesan = "Data berhasil diinput";
				}
				
				echo json_encode(array('status'=>$status, 'pesan'=>$pesan));
				exit;
			}
		}
		
		$this->theme->set('page_title', lang('text_page_title'));
		$this->theme->view('create_user', $data);
	}
	
	public function edit_user($user_id = '')
	{
		if (!$this->session->userdata('session_loggedin')) {
			redirect('users/login/' . $this->core->encode($this->uri->uri_string()));
		}
		
		$data['user_status'] = $this->users_model->getUserComboStatus();
		foreach ($this->users_model->getUserComboStatus() as $user_status) {
			$data['user_status'][$user_status['member_value']] = $user_status['member_display'];
		}
		
		$data['users_group'] = array();
		foreach ($this->users_model->get_user_group() as $group) {
			$data['users_group'][$group['user_group_id']] = $group['user_group_name'];
		}
		
		$data['reporting_to'] = array();
		foreach ($this->users_model->getUserReportingTo(1) as $report_to) {
			$data['reporting_to'][$report_to->user_id] = $report_to->user_name;
		}
		
		$user_id = $this->core->decode($user_id);
		
		$user = $this->users_model->getUser($user_id);
		
		$data['user'] = $user;
		
		if ($this->core->is_ajax()) {
		
			if ($this->input->post('cmd') == 'EDIT_USER') {
				$name = $this->input->post("name");
				$email = $this->input->post("email");
				$username = $this->input->post("username");
				$olduser = $this->input->post("olduser");
				$nik = $this->input->post("nik");
                $usergroup = $this->input->post("usergroup");
                $reportingto = $this->input->post("reportingto");
				$hape = $this->input->post("hape");
				$proses = $this->input->post("proses");
				
				$pesan = "";
				
				$groupsama = $this->users_model->showdata('user_id','t_mtr_user',array("user_id"=>$username,"user_group_id"=>$usergroup),'dblokal');
				
				//$terid = $this->users_model->showdata('territory_id','t_mtr_territory',array("user_id"=>$username),'dblokal');
				$cek_ter = "";
				//if($terid<>"") $this->users_model->showdata('territory_id','t_mtr_territory',array("parent_id"=>$terid),'dblokal');
				
				$cek_email = $this->users_model->showdata('user_id','t_mtr_user',array("email"=>$email, "user_id !="=>$username),'dblokal');
				$vemail="";
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$vemail="salah";
				}
				
				if($reportingto=="" || $reportingto=="null" || ($groupsama=="" && $cek_ter <> "")
				   ||  ($email<>""&&$vemail<>"") ){
						$pesan = "Terdapat kesalahan:\n";
						if($hape==""){
								$pesan .="=> Kolom Handphone harus diisi\n";
						}
						if($reportingto=="" || $reportingto=="null"){
								$pesan .="=> Kolom Reporting To harus diisi\n";
						}
						if($groupsama=="" && $cek_ter <> ""){
								$pesan .="=> Terdapat territori yang dimaintenance oleh user ybs, sehingga tidak bisa diupdate.\n";
						}
						// if($email==""){
						// 		$pesan .="=> Kolom Email harus diisi\n";
						// }else{
								if($vemail<>""){
										$pesan.="=> Format Email Salah\n";
								}elseif($cek_email<>""){
										$pesan.="=> Email sudah digunakan\n";
								}
						// }
					$status="ERROR";
				}else{
					//proses data
					$this->users_model->saveUser($this->input->post('olduser'));
					$status="OK";
					$pesan = "Data berhasil diupdate";
				}
				echo json_encode(array('status'=>$status, 'pesan'=>$pesan));
				exit;
			}
		}
		
		$this->theme->set('page_title', lang('text_page_title'));
		$this->theme->view('edit_user', $data);
	}
}
