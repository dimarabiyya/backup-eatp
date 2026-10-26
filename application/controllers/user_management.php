<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_management extends CI_Controller {

	function User_management()
	
{		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('encrypt');
		$this->load->library('enc');
        $this->load->model('muserrole');
		$this->load->model('mmaster');
        $this->load->model('musers');
		$this->load->model('mglobal');
		$this->is_logged_in();
		#$this->mjadwal->saveLog();
		
		
	}
        
	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_login');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			redirect(base_url() .'index.php/login');
		}		
	}
	
    function index($proses="",$id="")
	{

		$data['template'] = "template/smashing";
                $data['users']    = $this->musers->getUser();
                $data['content_id']      = 'f-users';
		$data['proses']		= $proses;
		$data['id']	= $id;
		$data['user_status'] = $this->musers->getUserComboStatus();
		$data['data'] = array("name"=>"","username"=>"","nik"=>"","usergroup"=>"","reportingto"=>"","hape"=>"","email"=>"");
		if($proses<>"" && $id<>"") {
			//decode
			$id = $this->enc->defog($id);
			
		    $getdata = $this->musers->getUser($id);
		    foreach($getdata as $r){
			$data['data']['name'] 			= $r->user_name;
			$data['data']['email'] 			= $r->email;
			$data['data']['username'] 		= $r->user_id;
			$data['data']['nik'] 			= $r->user_id;
			$data['data']['usergroup'] 		= $r->user_group_id;
			$data['data']['reportingto'] 	= $r->reporting_to;
			$data['data']['hape'] 			= $r->mobile_number;
			$data['status']				= $r->status;
			$data['last_time_change_password'] = $r->last_time_change_password;
			$data['must_change_password'] = $r->must_change_password;
		    }
		}
	
		if ($data['data']['username']) {
			$data['created_by']	= $r->created_by;
		}
		else {
			$data['created_by'] = $this->session->userdata('userrname');
		}
		
                if($proses <> "update"){
                        #inisialisasi elemen form yang harus diisi
                        $this->form_validation->set_rules('name', 'Name', 'required');
                        $this->form_validation->set_rules('username', 'Username', 'required|callback_vuserid_check');
                        $this->form_validation->set_rules('nik', 'NIK', 'required|callback_vnik_check');
                        //$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_vemail_check');
                        // $this->form_validation->set_rules('hape', 'Mobile Number', 'required');
                        
                }else{
                        //$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_vemail_check');
                        //$this->form_validation->set_rules('hape', 'Mobile Number', 'required');
                }
                
                $this->form_validation->set_error_delimiters('<li>','</li>');
                $this->form_validation->set_message('required', 'Kolom <b>%s</b> harus diisi.');
                $this->form_validation->set_message('valid_email', 'Kolom <b>%s</b> harus diisi dengan alamat email yang benar.');
		if ($this->form_validation->run() != FALSE){
			// masukkan data di sini
			$this->musers->saveUser($id);
			$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
			redirect(base_url()."index.php/user_management");
		}	
		
                $hasil = "";
                $this->load->view($data['template'],$data);
	}
        
        
        function change_password()
	{
		$data['template'] = "shell/smooth";
                $data['users']    = $this->musers->getUser();
                $data['main_view']      = 'f-user-change-password';
		
                #inisialisasi elemen form yang harus diisi
                $this->form_validation->set_rules('old-password', 'Old Password', 'required|callback_voldpwd_check');
                $this->form_validation->set_rules('new-password', 'New Password', 'required');
                $this->form_validation->set_rules('re-new-password', 'Retype New Password', 'required|callback_vpassword_check');
                $this->form_validation->set_error_delimiters('<li>','</li>');
                $this->form_validation->set_message('required', 'Kolom <b>%s</b> harus diisi.');
		if ($this->form_validation->run() != FALSE){
			// masukkan data di sini
			$this->musers->change_password($this->session->userdata('username'));
			$this->session->set_flashdata('message', 'Password Anda Berhasil diganti. Dalam 3 detik, Anda akan Logout secara otomatis.');
			redirect(base_url()."index.php/user_management/change_password");
		}
                $this->load->view($data['template'],$data);
	}
        
        
        ########################################################################
	########################################################################
	public function getById( $id ) {
		if( isset( $id ) )
			echo json_encode( $this->musers->getUser( $id ) );
	}
	
	public function read() {
		echo json_encode( $this->musers->getUser() );

		/*$result = $this->musers->getUser();

        //encode
        for($i=0;$i<count($result);$i++){
    		$result[$i]->agent_id = $this->enc->fogit($result[$i]->agent_id);
    	}
        
        echo json_encode($result);*/
	}

	function defoger()
    {
        $result = $this->enc->defog($this->input->post('str'));
        echo $result;  
    }

	
	public function delete( $id = null ) {
		if( is_null( $id ) ) {
			echo 'ERROR: Id not provided.';
			return;
		}
		$this->musers->deleteUser( $id );
		echo 'Records deleted successfully';
	}
        
        
        ##################################################
        #################################################
        function vemail_check($str)
	{
		$where = array("email"=>$str);
		if($this->uri->segment(4) <> "") $where = array("email"=>$str,"user_id !="=>$this->uri->segment(4));
		$v = $this->mglobal->showdata('user_id','t_mtr_user',$where,'dblokal');
		if ($v <> "")
		{
			$this->form_validation->set_message('vemail_check', 'Email <b>'. $str .'</b> sudah digunakan oleh akun lain.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
        
        function vuserid_check($str)
	{
		$where = array("user_id"=>$str);
                if($this->uri->segment(4) <> "") $where = array("email"=>$str,"user_id !="=>$this->uri->segment(4));
		$v = $this->mglobal->showdata('user_id','t_mtr_user',$where,'dblokal');
		if ($v <> "")
		{
			$this->form_validation->set_message('vuserid_check', 'Username <b>'. $str .'</b> sudah digunakan oleh akun lain.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
        
        function vnik_check($str)
	{
		$where = array("nik"=>$str);
		$v = $this->mglobal->showdata('nik','t_mtr_user',$where,'dblokal');
		if ($v <> "")
		{
			$this->form_validation->set_message('vnik_check', 'NIK <b>'. $str .'</b> sudah digunakan oleh akun lain.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
        
        function vpassword_check()
		{
		
				if ($this->input->post('new-password') <> $this->input->post('re-new-password'))
				{
					$this->form_validation->set_message('vpassword_check', '<b>New Password</b> dan <b>Retype New Password</b> harus sama.');
					return FALSE;
				}
				else
				{
					return TRUE;
				}
		}
        
        function voldpwd_check()
		{
			$where = array("user_id"=>$this->session->userdata('username'),"user_password"=>md5($this->input->post('old-password')));
			$v = $this->mglobal->showdata('user_id','t_mtr_user',$where,'dblokal');
			
			$nik = $this->mglobal->showdata('nik','t_mtr_user',array("user_id"=>$this->session->userdata('username')),'dblokal');
			$min_pass = $this->mglobal->showdata('lookup_value','t_mtr_lookup',array('lower(lookup_key)'=>'account_min_length'),'dblokal');
			
			if ($v == ""){
				$this->form_validation->set_message('voldpwd_check', 'Password Lama Anda salah.');
				return FALSE;
			}elseif( strlen($this->input->post('new-password')) < $min_pass){
				$this->form_validation->set_message('voldpwd_check', '<b>New Password</b> minimal terdiri dari '.$min_pass.' karakter.');
				return FALSE;
			}elseif( $this->input->post('old-password') == $this->input->post('new-password')){
				$this->form_validation->set_message('voldpwd_check', '<b>New Password</b> dan <b>Old Password</b> tidak boleh sama.');
				return FALSE;
			}elseif( $this->input->post('new-password') == $nik || strtoupper($this->input->post('new-password')) == strtoupper($this->session->userdata('username'))){
				$this->form_validation->set_message('voldpwd_check', '<b>New Password</b> tidak boleh sama dengan NIK atau Username.');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
        
        function user_json($id=0,$p=0){
				echo($this->musers->getUserTree($id,$p));
		}
        
        function user_repto(){
                $data['usergroup'] = $this->input->post('usergroup');
                $data['reportingto'] = $this->input->post('reportingto');
                
                echo json_encode(array(
                        'status'=>'OK',
                        'pesan' => $this->load->view('f-user-reporting-to',$data,TRUE)
                ));
                return;
        }
		
		function search(){
				$data['template']       = "template/smashing";
				$data['content_id']      = "f-user-search";
                $this->load->view($data['template'],$data);
        }
		
		function search_result(){
			
			$uid = $this->input->post('uid');
			$name= $this->input->post('name');
			$nik= $this->input->post('nik');
			$group= $this->input->post('group');
			$report= $this->input->post('report');
			$hape= $this->input->post('hape');
			$email= $this->input->post('email');
			
			$data['getData'] = $this->musers->searchUser($uid, $name,$nik,$group, $report,$hape,$email);
			for ($i=0; $i < count($data['getData']); $i++) { 
				$data['getData'][$i]->id_encoded = $this->enc->fogit($data['getData'][$i]->user_id); //edited: alfin.akhret@gmail.com
			}

			$hasil = $this->load->view('f-user-search-hasil',$data,TRUE);
			echo json_encode(array(
					'status'=>'OK',
					'pesan'=>$hasil,
					'jumlah'=>count($data['getData'])
			));
			return;
		}
		
		function keluarkan_user(){
				$id= $this->input->post('id');
				$this->mglobal->set_is_login(0,$id);
				echo json_encode(array(
						'status'=>'OK'
				));
				return;
		}
		
		function save_user(){
				$name = $this->input->post("name");
				$email = $this->input->post("email");
				$username = $this->input->post("username");
				$olduser = $this->input->post("olduser");
				$nik = $this->input->post("nik");
                $usergroup = $this->input->post("usergroup");
                $reportingto = $this->input->post("reportingto");
				$hape = $this->input->post("hape");
				$proses = $this->input->post("proses");
				if($proses == "update"){
					$edituser = $username;
				}
				
				$pesan = ""; $status="ERROR";
				if($proses != 'update'){
                        //var_dump($olduser);

                        //cek username & nik sudah ada/blm
						
						$cek_nik = "";//$this->mglobal->showdata('nik','t_mtr_user',array("nik"=>$nik),'dblokal');
						$cek_user = $this->mglobal->showdata('user_id','t_mtr_user',array("user_id"=>$username),'dblokal');
						$cek_email = $this->mglobal->showdata('user_id','t_mtr_user',array("email"=>$email),'dblokal');
						
						$vemail="";
						if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$vemail="salah";
						}
						if($name=="" || $username=="" || $nik=="" || $hape=="" || $reportingto=="" || $reportingto=="null"
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
						}else{
								//proses data
								$this->musers->saveUser();
								$status= "OK";
								$pesan = "Data berhasil diinput";
								$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
						}
                        
                }else{
                        // var_dump($olduser);
                        
						#kalo di territory ada tree di bawahnya, gak boleh diupdate (&kalo groupnya ganti loh,,,)
						#kalo gak ada, boleh diupdate & update juga t_mtr_territory
						
						//get group yg sekarang
						$groupsama = $this->mglobal->showdata('user_id','t_mtr_user',array("user_id"=>$username,"user_group_id"=>$usergroup),'dblokal');
						
						$terid = $this->mglobal->showdata('territory_id','t_mtr_territory',array("user_id"=>$username),'dblokal');
						$cek_ter = "";
						if($terid<>"") $this->mglobal->showdata('territory_id','t_mtr_territory',array("parent_id"=>$terid),'dblokal');
						
						$cek_email = $this->mglobal->showdata('user_id','t_mtr_user',array("email"=>$email, "user_id !="=>$username),'dblokal');
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
								
						}else{
								//proses data
								$this->musers->saveUser($olduser);
								$status="OK";
								$pesan = "Data berhasil diupdate";
								$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
						}
                }
				echo json_encode(array('status'=>$status, 'pesan'=>$pesan));
				return;
		}

	public function group($proses='', $group_id='') {

		$data['template']   = "shell/smooth";
		
		if ($proses == 'edit') {
			# code...
		}

		else {
			$data['main_view']	= "f-users-group";
			// $data['user_group'] = json_encode($this->musers->treeGroups($group_id));
			$data['user_group'] = $this->musers->treeGroups($group_id);
		}

        $this->load->view($data['template'],$data);
	}

	public function groupTree($group_id = ''){
		$data = $this->musers->treeGroups($group_id);
		echo json_encode($data);
	}

	public function active_user() {
		$data['template'] = "shell/smooth";
		$data['main_view'] = "f-user-login";
		$data['login_user'] = $this->musers->get_active_user();
		$this->load->view($data['template'] , $data);
	}

	public function reset_password($user_id) {
		$data = $this->musers->reset_password($user_id);
		echo json_encode($data);
	}
}