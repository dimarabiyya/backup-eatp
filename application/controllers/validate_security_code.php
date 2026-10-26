<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validate_security_code extends CI_Controller {
/*
        To activate this controller, please modify your is_logged_in function in mglobal.php
        And add t_ref_security_code in your database
*/
	public function index()
	{
	 //die('a');
	}

    // Allow Login
	function cek_allow_step_1(){

		$input = array('username', 'ractha');
		foreach ($input as $val)
			$$val = $this->input->post($val);

		$username = trim(strtolower($this->input->post('username')));

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
			$username = $this->mglobal->showdata("user_id", "t_mtr_user", array("lower(user_id)"=>$username),"dblokal");
			if($username == ""){
				$status = "ERROR";
				$hasil = 'Username tidak ditemukan.';
			}else{

            	$email = $this->get_mail($username);

               // $this->mglobal->showdata("email", "t_mtr_user", array("lower(user_id)"=>strtolower()),"dblokal");
				if($username == ""){
    				$status = "ERROR";
    				$hasil = 'Email Tidak ditemukan';
                }else{
                    $status = "OK";

                    $sc_data['securityCode']=substr(md5(date('YmdHis')),0,6);
                    $sc_data['usageStatus']=0;
                    $sc_data['createdBy']=$username;
                    $sc_data['createdOn']=date('Y-m-d H:i:s');
                    $sc_data['email']=$email;
                    $this->db->insert('t_ref_security_code',$sc_data);

                    $mailfeed['email_type_id']=1;
                    $mailfeed['email_date']=date('Ymd');
                    $mailfeed['email_time']=date('His');
                    $mailfeed['email_subject']='.::E-ATP - Security Code::.';
                    $mailfeed['email_content']='Dear '.$username.', <br />
                                            Your security code is : <b>'.$sc_data['securityCode'].'</b><br />
                                            or <br />
                                            You can click <a href=\''.base_url().'validate_security_code/automatic_validator?sc='.md5($email.$sc_data['securityCode']).'\'>this link</a>';
                    $mailfeed['email_reply_to_address']='do-not-reply@alita.id';
                    $mailfeed['foreign_id']=1;
                    $mailfeed['created_by']=$username;

                    $this->db->insert('t_msg_email_header',$mailfeed);

                    $mailfeed='';

                    $mailfeed['email_id']=$this->db->insert_id();
                    $mailfeed['recipient_type']='to';
                    $mailfeed['recipient_id']=$username;
                    $mailfeed['recipient_address']=$email;
                    $mailfeed['created_by']=$username;

                     $this->db->insert('t_msg_email_detail',$mailfeed);

    				$this->session->set_userdata( array('allow_step_2'=>$username) );
                }

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
		$input = array('sc');
        //$email = $this->mglobal->showdata("email", "t_mtr_user", array("lower(user_id)"=>strtolower($this->session->userdata('allow_step_2'))),"dblokal");
		$email = $this->get_mail($this->session->userdata('allow_step_2'));
		foreach ($input as $val)
			$$val = $this->input->post($val);

		$status = "ERROR";
		$hasil = "";
		if($sc == "" ){
			$status = "ERROR";
			$hasil = 'Security Code Harus Diisi!';
		}else{
		  $status=$this->input_code(md5($email.$sc));
          if($status=="OK")
            $this->autologin($this->session->userdata('allow_step_2'));
          else
            $hasil="Security Code doesn't exist!";
		}
		if($status=="ERROR")
			echo json_encode(array(
				'status' => $status,
				'hasil'  => $hasil
			));
        else{
       	echo json_encode(array(
				'status' => $status,
				'hasil'  => base_url()
			));
        }
			return;

	}

    private function autologin($username){
        //proses login di sini
			##############################################################################################################
			$data['hasil'] = base_url();
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

    private function input_code($security_code){
        //cek ada username/tdk
		$interval = $this->mglobal->showdata("lookup_value", "t_mtr_lookup",
            array("lookup_key"=>'security_code_expiration'),"dblokal");
        $userid = $this->mglobal->showdata("createdBy", "t_ref_security_code",
            array("md5(email||\"securityCode\")"=>$security_code),"dblokal");

		$this->db->where("\"createdOn\" < \"createdOn\" + interval '$interval minutes'");
        $this->db->where("md5(email||\"securityCode\")",$security_code);
        $this->db->where("\"usageStatus",0);
        $this->db->from('t_ref_security_code');

        if($this->db->count_all_results()){

    		$this->db->where("\"createdOn\" < \"createdOn\" + interval '$interval minutes'");
            $this->db->where("md5(email||\"securityCode\")",$security_code);
            $this->db->update('t_ref_security_code',array("\"usageStatus\""=>1,"\"updatedOn\""=>date('Ymd His')));

            $this->db->where("user_id",$userid);
            $this->db->update('t_mtr_user',array("\"is_login\""=>0,"\"updated_on\""=>date('Ymd His')));

            return "OK";
		}else{
		   return "ERROR";
			//$username = $this->session->userdata('allow_step_2');
		//	$hasil = 'Jawaban Anda Salah.';
            ##############################################################################################################
        }
    }

    public function automatic_validator(){
            $return=$this->input_code($this->input->get('sc'));
            if($return=="ERROR"){

			echo  "Security Code doesn't exist!";

	       }else{
	       	redirect(base_url());
        }
            	return;

            //echo $this->input->get('sc');
    }


    private function get_mail($userid){
    	$userid = strtolower($userid);
        $is_internal = $this->mglobal->showdata("internal_user", "t_mtr_user",
            array("user_id"=>$userid),"dblokal");
            if($is_internal)
                return $userid."@alita.id";
            else
                return $this->mglobal->showdata("email", "t_mtr_user",
            array("user_id"=>$userid),"dblokal");
    }
}
