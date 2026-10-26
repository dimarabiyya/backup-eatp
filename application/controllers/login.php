<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
		$data['judul'] = "Login";
		$data['content_id'] = "f-test-form";
		$d = $this->input->get();
		if (isset($d['session_expired']))
			$data['session_expired'] = true;
		else
			$data['session_expired'] = '';

		$this->load->view("template/smashing_login", $data);
	}


	public function capcay()
	{
		ob_clean();
		$this->load->view("ratcha/ratcha_image");
	}

	public function proses_login()
	{
		//start update by hadi 20181015
		$this->mglobal->forced_set_is_login(0,strtolower($this->input->post('username')));
		//end update by hadi 20181015
		
		$this->load->model('muser');

		$data = $this->auth();

		if ($data['status'] == 'OK') {
			// Setelah login langsung ke tabel Summary; dashboard tetap ada di base_url() (menu Dashboard)
			$data['hasil'] = base_url('visitsummary');
			$username = strtolower($this->input->post('username'));

			$this->mglobal->set_is_login(1, $username);

			$input = array(
				'user_name',
				'must_change_password',
				'reporting_to',
				'status',
				'is_allowed_time',
				'email',
				'mobile_number',
				'last_time_change_password'
			);

			foreach($input as $val)
				$$val = "";

			$getUser = $this->muser->getDetailUser( $username );
			foreach($getUser as $r){
				foreach($input as $val){
					$$val = $r->$val;
				}
			}

			$user_group = $this->muser->getUserMultiGroup( $username );
			$user_div = $this->muser->getUserMultiDivision( $username );
			$region = $this->muser->getUserMultiRegion( $username );
			$ugrup = $this->muser->getUserMultiGroupx( $username );
			// print_r($cluster); die();

			$userdata = array (
				'user_id'                   => $username,
				'user_name'                 => $user_name,
				'must_change_password'      => $must_change_password,
				'reporting_to'              => $reporting_to,
				'status'                    => $status,
				'is_allowed_time'           => $is_allowed_time,
				'email'                     => $email,
				'mobile_number'             => $mobile_number,
				'is_login'                  => TRUE,
				'last_time_change_password' => $last_time_change_password,
				'ip_address'                => $this->input->ip_address(),
				'global_ip_address'         => $this->input->ip_address(),
				'login_date'                => date("Y-m-d H:i:s"),
				'mdn'                       => '',
				'meid'                      => '',
				'min'                       => '',
				'user_region'               => $region,
				'user_role'                 => $user_group,
				'user_group'           	    => $user_div,
				'ugrup'						=> $ugrup->user_group_name
			);

			$this->session->set_userdata($userdata);
		}

		$this->session->set_flashdata('trial_message','1'); 

		echo json_encode(array(
			'status' => $data['status'],
			'hasil'  => $data['hasil']
		));

		return;

	}

	private function auth(){
		$this->load->model('mmaster');
		$input = array('username', 'password', 'ractha');
		foreach ($input as $val)
			$$val = $this->input->post($val);

		$username = strtolower($username);
		$status = "ERROR";
		$hasil = "";
		if($username == "" ){
			$status = "ERROR";
			//$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Username harus diisi.</div>';
			$hasil = 'Userid harus diisi.';
		}elseif( $password == "" ){
			$status = "ERROR";
			//$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Password harus diisi.</div>';
			$hasil = 'Password harus diisi.';
		}elseif( $ractha == "" ){
			$status = "ERROR";
			//$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Kode Validasi harus diisi.</div>';
			$hasil = 'Kode Validasi harus diisi.';
		}elseif( $ractha <> $this->session->userdata('ratcha') ){
			$status = "ERROR";
			//$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Kode Validasi salah.</div>';
			$hasil = 'Kode Validasi salah.';
		}else{
			//cek ada username/tdk
			$username = $this->mglobal->showdata("user_id", "t_mtr_user", array("lower(user_id)"=>strtolower($this->input->post('username'))),"dblokal");
			$allow = $this->mglobal->showdata("is_allowed_time","t_mtr_user",array("user_id"=>$username),"dblokal");
			$data = array("user_id"=>$username,
				      "user_password"=>md5($this->input->post('password')));

			$istatus = $this->mglobal->showdata('status','t_mtr_user',array("user_id"=>$username),'dblokal');
			$internal = $this->mglobal->showdata('internal_user','t_mtr_user',array("user_id"=>$username),'dblokal');
			//periksa batas kesalahan
			$jumlah_salah = $this->mglobal->showdata('logon_attempt','t_mtr_user',array('user_id'=>$username),'dblokal');
			$batas_salah = $this->mglobal->showdata('lookup_value','t_mtr_lookup',array('lower(lookup_key)'=>'account_max_attemp'),'dblokal');


			if($istatus <> 1){
				$status = "ERROR";
				//$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Status akun Anda tidak aktif/belum terdaftar, silakan hubungi Administrator.</div>';
				$hasil = 'Status akun Anda tidak aktif/belum terdaftar, silakan hubungi Administrator.';
			}
			elseif($jumlah_salah >=$batas_salah){
				$status = "ERROR";
				//$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Maaf, akun Anda telah di-blok karena telah melakukan kesalahan '.$batas_salah.' kali, silakan hubungi Administrator.</div>';
				$hasil = 'Maaf, akun Anda telah di-blok karena telah melakukan kesalahan '.$batas_salah.' kali, silakan hubungi Administrator.';
			}elseif($allow <> 1){
				$status = "ERROR";
				//$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Anda tidak diizinkan login pada waktu ini.</div>';
				$hasil = 'Anda tidak diizinkan login pada waktu ini.';
			}elseif($internal==1 && !$this->radius_auth($username, $this->input->post('password'))){
				$status = "ERROR";
				//$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Status akun Anda tidak aktif/belum terdaftar di LDAP, silakan hubungi Administrator.</div>';
				$hasil = 'Status akun Anda tidak aktif/belum terdaftar di RADIUS / Password RADIUS Anda Salah, silakan hubungi Administrator.';
			}
			elseif ( ($internal<>1 || $internal=="") && $this->mglobal->showdata("user_id", "t_mtr_user", $data,"dblokal") == ""){
				$status = "ERROR";
				//$hasil = '<div class="alert alert-primary"><strong>Error! </strong>Password Anda Salah.</div>';
				$hasil = 'Password Anda Salah.';
			}elseif ($this->mglobal->showdata("is_login", "t_mtr_user", array("user_id"=>$username),"dblokal") == 1){

				date_default_timezone_set('Asia/Jakarta');
				$last = $this->mmaster->check_last_activity($username,"yes");

				if(count($last) == 0){
					$status = "OK";
				}else{

					// $last_activity = strtotime($last_activity[0]->lastActivity);
					// print_r($last);
					$last_activity = strtotime($last['lastActivity']);//strtotime("09:09:12");//
					//echo $last_activity."ss";
					//check current time

					$times = explode(':', $last['lastActivity']);
					// echo $times[0];
					$unixtime = mktime($times[0], $times[1], $times[2]);
					// echo "<br>";
					$current_time = date('H:i:s');
					// echo "<br>";
					$current_time = strtotime($current_time);

					// calculate the time interval between last activity and current time
					$interval = ($current_time - $last_activity)/60;
					// die();

					$delay = $this->mglobal->showdata("lookup_value", "t_mtr_lookup", array("lookup_key"=>'login_delay_time'),"dblokal");
					$tdelay = $delay - $interval;
					#echo "$interval :: $delay :: $interval";
					if(($interval > $delay) || ($interval < 0)){
						$status = "OK";
					}else{
						$status = "ERROR";
						//$hasil = "<div class='alert alert-primary'><strong>Error! </strong>Anda lupa logout pada sesi terakhir atau User dengan ID ini sedang login ditempat lain dengan IP Address: ". $last['ip_address'] .". <br>Anda hanya bisa login setelah menunggu $delay menit</div>";
						$hasil = $last['lastActivity']."Anda lupa logout pada sesi terakhir atau User dengan ID ini sedang login ditempat lain dengan IP Address: ". $last['ip_address'] .". <br>Jika anda lupa logout pada sesi terakhir, silahkan login melalui link ini: <a href='".base_url()."login/AllowMeToLogin'>Allow Me To Login</a>";
						$this->muser->setLastLoginDoubleUser( $username );
					}
				}
			}else{
				$status = "OK";
			}
		}

		if($status == 'OK')
		{
		 $this->mmaster->update_last_activity($username);
		 $user_id= $this->input->post('username');
		 $datas['user_id'] =$user_id;
		 $datas['captcha'] =$this->input->post('ractha');
  		 $this->dblog->AuditTrail('user_login', $datas, "web",$user_id);
		}
		return array('status'=>$status, 'hasil'=>$hasil);
	}

	private function radius_auth($user, $pwd){

		require_once('radius.class.php');
		//$user	= "bayu.prasetiyo";
		//$pwd	= "Bayu2014";

		$host	= $this->config->item('radius_host');
		$nas	= "10.17.71.71";
		$secret = $this->config->item('radius_secret');
		$radius = new Radius($host, $secret);

		//$radius->SetNasIpAddress( $nas ); // Needed for some devices, and not auto_detected if PHP not runned through a web server
		// Enable Debug Mode for the demonstration
		//$radius->SetDebugMode(TRUE);

		if ($radius->AccessRequest($user, $pwd))
		{
			//echo "<strong>Authentication accepted.</strong>";
			return TRUE;
		}
		else
		{
			//echo "<strong>Authentication rejected.</strong>";
			return FALSE;
		}
	}

	public function ForgotPassword()
	{
		$data['judul'] = "Forgot Password";

		//cek session
		$data['content_id'] = "login/f-forgot-password-step-1";
		$data['tahap']=1;
		if($this->session->userdata('forgot_step_3')<>""){
			$data['content_id'] = "login/f-forgot-password-step-3";
			$data['tahap']=3;

			$un = $this->session->userdata('forgot_step_2');
			$q1 = $this->mglobal->showdata("question1", "t_mtr_security_question", array("lower(user_id)"=>strtolower( $un )),"dblokal");
			$q2 = $this->mglobal->showdata("question2", "t_mtr_security_question", array("lower(user_id)"=>strtolower( $un )),"dblokal");

			$data["user_id"] 	= $un;
			$data["pertanyaan1"]  	= $q1;
			$data["pertanyaan2"]  	= $q2;
		}elseif($this->session->userdata('forgot_step_2')<>""){
			$data['content_id'] = "login/f-forgot-password-step-2";
			$data['tahap']=2;

			$un = $this->session->userdata('forgot_step_2');
			$q1 = $this->mglobal->showdata("question1", "t_mtr_security_question", array("lower(user_id)"=>strtolower( $un )),"dblokal");
			$q2 = $this->mglobal->showdata("question2", "t_mtr_security_question", array("lower(user_id)"=>strtolower( $un )),"dblokal");

			$data["user_id"] 	= $un;
			$data["pertanyaan1"]  	= $q1;
			$data["pertanyaan2"]  	= $q2;
		}
		$this->load->view("template/smashing_wizard", $data);
	}

	function cek_forgot_step_1(){
		$input = array('username', 'ractha');
		foreach ($input as $val)
			$$val = $this->input->post($val);

		$status = "ERROR";
		$hasil = "";
		if($username == "" ){
			$status = "ERROR";
			$hasil = 'Username harus diisi';
		}elseif( $ractha == "" ){
			$status = "ERROR";
			$hasil = 'Kode Validasi harus diisi';
		}elseif( $ractha <> $this->session->userdata('ratcha') ){
			$status = "ERROR";
			$hasil = 'Kode Validasi salah';
		}else{
			//cek ada username/tdk
			$username = $this->mglobal->showdata("user_id", "t_mtr_security_question", array("lower(user_id)"=>strtolower($this->input->post('username'))),"dblokal");
			if($username == ""){
				$status = "ERROR";
				$hasil = 'Username atau Security Question tidak ditemukan.';
			}else{
				$status = "OK";

				$this->session->set_userdata( array('forgot_step_2'=>$this->input->post('username')) );

			}
		}
		echo json_encode(array(
			'status' => $status,
			'hasil'  => $hasil
		));
		return;
	}

	function cek_forgot_step_2(){
		$this->load->model('muser');
		$input = array('jawaban1', 'jawaban2');
		foreach ($input as $val)
			$$val = $this->input->post($val);

		$status = "ERROR";
		$hasil = "";
		if($jawaban1 == "" ){
			$status = "ERROR";
			$hasil = 'Jawaban 1 harus diisi';
		}elseif( $jawaban2 == "" ){
			$status = "ERROR";
			$hasil = 'Jawaban 2 harus diisi';
		}else{
			//cek ada username/tdk
			$username = $this->mglobal->showdata("user_id", "t_mtr_security_question",
							     array("lower(user_id)"=>strtolower($this->session->userdata('forgot_step_2')),
								   "lower(answer1)"=>strtolower($jawaban1),
								   "lower(answer2)"=>strtolower($jawaban2)
								   ),"dblokal");
			if($username == ""){
				$status = "ERROR";
				$hasil = 'Jawaban Anda Salah.';
			}else{
				$status = "OK";
				//simpan password code
				$this->muser->updatePasswordCode($username);
				$this->session->set_userdata( array('forgot_step_3'=>$username) );

			}
		}
		echo json_encode(array(
			'status' => $status,
			'hasil'  => $hasil
		));
		return;
	}

	function cek_forgot_step_3(){
		$input = array('new_password', 'cnew_password','password_code');
		foreach ($input as $val)
			$$val = $this->input->post($val);

		$status = "ERROR";
		$hasil = "";
		if($new_password == "" ){
			$status = "ERROR";
			$hasil = 'New Password is required.';
		}elseif($cnew_password == "" ){
			$status = "ERROR";
			$hasil = 'Confirm New Password is required.';
		}elseif($new_password <> $cnew_password ){
			$status = "ERROR";
			$hasil = 'Confirm New Password is not same with New Password.';
		}elseif($password_code == "" ){
			$status = "ERROR";
			$hasil = 'Password Code is required.';
		}elseif( ! $this->session->userdata('forgot_step_3')){
			$status = "ERROR";
			$hasil = 'Please answer the security questions first.';
		}else{
			$pass_code = $this->mglobal->showdata("password_code", "t_mtr_user", array("lower(user_id)"=>strtolower($this->session->userdata('forgot_step_2'))),"dblokal");
			if($password_code == $pass_code){
				$status = "OK";
				$this->load->model('muser');
				$this->muser->savePassword();
				$this->session->sess_destroy();
				$hasil = base_url()."login";
			}else{
				$status = "ERROR";
				$hasil = 'Password Code is wrong.';
			}
		}
		echo json_encode(array(
			'status' => $status,
			'hasil'  => $hasil
		));
		return;
	}

	##########################################################################
	## untuk Allow Me To Login
	public function AllowMeToLogin()
	{
		$data['judul'] = "Allow Me To Login";

		//cek session
		$data['content_id'] = "login/f-allow-login-step-1";
		$data['tahap']=1;
		if($this->session->userdata('allow_step_3')<>""){
			$data['content_id'] = "login/f-allow-login-step-3";
			$data['tahap']=3;

			$un = $this->session->userdata('allow_step_2');
			$q1 = $this->mglobal->showdata("question1", "t_mtr_security_question", array("lower(user_id)"=>strtolower( $un )),"dblokal");
			$q2 = $this->mglobal->showdata("question2", "t_mtr_security_question", array("lower(user_id)"=>strtolower( $un )),"dblokal");

			$data["user_id"] 	= $un;
			$data["pertanyaan1"]  	= $q1;
			$data["pertanyaan2"]  	= $q2;
		}elseif($this->session->userdata('allow_step_2')<>""){
			$data['content_id'] = "login/f-allow-login-step-2";
			$data['tahap']=2;

			$un = $this->session->userdata('allow_step_2');
			$q1 = $this->mglobal->showdata("question1", "t_mtr_security_question", array("lower(user_id)"=>strtolower( $un )),"dblokal");
			$q2 = $this->mglobal->showdata("question2", "t_mtr_security_question", array("lower(user_id)"=>strtolower( $un )),"dblokal");

			$data["user_id"] 	= $un;
			$data["pertanyaan1"]  	= $q1;
			$data["pertanyaan2"]  	= $q2;
		}
		$this->load->view("template/smashing_wizard", $data);
	}

	function cek_allow_step_1(){
		$input = array('username', 'ractha');
		foreach ($input as $val)
			$$val = $this->input->post($val);

		$status = "ERROR";
		$hasil = "";
		if($username == "" ){
			$status = "ERROR";
			$hasil = 'Username harus diisi';
		}elseif( $ractha == "" ){
			$status = "ERROR";
			$hasil = 'Kode Validasi harus diisi';
		}elseif( $ractha <> $this->session->userdata('ratcha') ){
			$status = "ERROR";
			$hasil = 'Kode Validasi salah';
		}else{
			//cek ada username/tdk
			$username = $this->mglobal->showdata("user_id", "t_mtr_security_question", array("lower(user_id)"=>strtolower($this->input->post('username'))),"dblokal");
			if($username == ""){
				$status = "ERROR";
				$hasil = 'Username atau Security Question tidak ditemukan.';
			}else{
				$status = "OK";
				$this->session->set_userdata( array('allow_step_2'=>$this->input->post('username')) );

			}
		}
		echo json_encode(array(
			'status' => $status,
			'hasil'  => $hasil
		));
		return;
	}

	function cek_allow_step_2(){
		$this->load->model('muser');
		$input = array('jawaban1', 'jawaban2');
		foreach ($input as $val)
			$$val = $this->input->post($val);

		$status = "ERROR";
		$hasil = "";
		if($jawaban1 == "" ){
			$status = "ERROR";
			$hasil = 'Jawaban 1 harus diisi';
		}elseif( $jawaban2 == "" ){
			$status = "ERROR";
			$hasil = 'Jawaban 2 harus diisi';
		}else{
			//cek ada username/tdk
			$username = $this->mglobal->showdata("user_id", "t_mtr_security_question",
							     array("lower(user_id)"=>strtolower($this->session->userdata('allow_step_2')),
								   "lower(answer1)"=>strtolower($jawaban1),
								   "lower(answer2)"=>strtolower($jawaban2)
								   ),"dblokal");
			if($username == ""){
				$status = "ERROR";
				$hasil = 'Jawaban Anda Salah.';
			}else{
				$status = "OK";
				$username = $this->session->userdata('allow_step_2');

				//proses login di sini
				##############################################################################################################
				$data['hasil'] = base_url('visitsummary');
				$this->mglobal->set_is_login(1,$username);
				$input = array('user_group_id','user_name','must_change_password','reporting_to',
						'status','is_allowed_time','email','mobile_number','last_time_change_password');
				foreach($input as $val)
					$$val = "";

				$getUser = $this->muser->getDetailUser( $username );
				foreach($getUser as $r){
					foreach($input as $val){
						$$val = $r->$val;
					}
				}
				$user_group = $this->muser->getUserMultiGroup( $username );
				$user_div = $this->muser->getUserMultiDivision( $username );
				$region = $this->muser->getUserMultiRegion( $username );
				// print_r($cluster); die();

				$userdata = array (
					'user_id'                   => $username,
					'user_name'                 => $user_name,

					'must_change_password'      => $must_change_password,
					'reporting_to'              => $reporting_to,
					'status'                    => $status,
					'is_allowed_time'           => $is_allowed_time,
					'email'                     => $email,
					'mobile_number'             => $mobile_number,
					'is_login'                  => TRUE,
					'last_time_change_password' => $last_time_change_password,
					'ip_address'                => $this->input->ip_address(),
					'global_ip_address'         => $this->input->ip_address(),
					'login_date'                => date("Y-m-d H:i:s"),
					'mdn'                       => '',
					'meid'                      => '',
					'min'                       => '',

					'user_region'               => $region,
					'user_role'                 => $user_group,
					'user_group'           	    => $user_div
				);
				$this->session->set_userdata($userdata);

				echo json_encode(array(
					'status' => 'OK',
					'hasil'  => $data['hasil']
				));
				return;
				##############################################################################################################

			}
		}

		if($status == "ERROR"){
			echo json_encode(array(
				'status' => $status,
				'hasil'  => $hasil
			));
			return;
		}
	}
	##########################################################################
	function logout($id = 1)
	{
		$user_id = $this->session->userdata("user_id");

        $this->session->unset_userdata();
		#$this->mglobal->set_is_login(0);
		#$this->mteritori->clean_table();

		$this->mglobal->set_is_login(0);
		$this->session->sess_destroy();
		$d = $this->input->get();
		if (isset($d['session_expired'])){
	  		$this->dblog->AuditTrail('user_logout', "session expired", "web",$user_id);
			redirect(base_url()."login?session_expired=true");
		}else{
	  		$this->dblog->AuditTrail('user_logout', "logout by user", "web",$user_id);
			redirect(base_url()."login");
		}

    }

}
