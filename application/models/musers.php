<?php

class mUsers extends CI_Model 
{
    function mUsers(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mglobal');
        $this->setVars();
    }
    
    function setVars()
    {
        $this->result = '';
        $this->idnya = 0;
    }
        
    public function create() {
        $data = array(
            'user_name'  => $this->input->post( 'cName', true ),
            'email' => $this->input->post( 'cEmail', true )
        );
        
        $this->db->insert( 't_mtr_user', $data );
		
		$ref_id = $this->db->insert_id();
		//$this->mglobal->saveLog('user','create',$ref_id);
        return $ref_id;
    }
    
    public function getById( $id ) {
        #$id = intval( $id );
        
        $query = $this->db->where( 'user_id', $id )->limit( 1 )->get( 't_mtr_user' );
        
        if( $query->num_rows() > 0 ) {
            return $query->row();
        } else {
            return array();
        }
    }

/////////////////////////////////////////////////////////////////
////////////////////Begin-Model User////////////////////////////
////////////////////////////////////////////////////////////////

//added by   : ilham
//fungsi     : Get all user from table user
//parameter  : $data
    function get_all_user() { 
        
        $this->db->select("user_name, user_id, usr.email, usr.mobile, grp.user_group_name, lkp.lookup_des as status");
        $this->db->from("t_mtr_user usr");
        $this->db->join("t_mtr_user_group grp", "usr.user_group_id = grp.user_group_id");
        $this->db->join("t_mtr_lookup lkp", "usr.status = cast(lkp.lookup_value as SMALLINT) and lkp.lookup_key = 'mtr_user_status' ");
        
        return $this->db->get();
    } 

//added by   : ilham
//fungsi     : Get user berdasarkan id
//parameter  : $id
    function get_by_id($id)
    {

        $this->db->select("user_name, user_id, email, mobile_number, user_password, must_change_password,
                            logon_attempt, job_title, is_allowed_time, last_time_change_password, is_login,user_picture, 
                            concat_ws(' | ', ptr.partner_code, ptr.partner_name) as partner_name, ptr.partner_id as partner_id,
                            grp.user_group_name, grp.user_group_id, lkp.lookup_des as status", false);
        $this->db->from("t_mtr_user usr");
        $this->db->join("t_mtr_user_group grp", "usr.user_group_id = grp.user_group_id");
        $this->db->join("t_mtr_partner ptr", "usr.partner_id = ptr.partner_id", 'left');
        $this->db->join("t_mtr_lookup lkp", "usr.status = cast(lkp.lookup_value as SMALLINT) and lkp.lookup_key = 'mtr_user_status' ");
        $this->db->where("user_id = '$id' ");

        return $this->db->get();

        
    }

//added by   : ilham
//fungsi     : insert user into table user
//parameter  : $data
    function insert_data_user($data)
    {
        $this->db->insert("t_mtr_user", $data);
    }

//added by   : ilham
//fungsi     : Update data user 
//parameter  : $data, $id
    function update_data_user($data,$id) 
    {
        
        $this->db->where("user_id = '$id' ");
        $this->db->update("t_mtr_user", $data);
    }

//added by   : ilham
//fungsi     : Delete user 
//parameter  : $id
    function delete_user($id) 
    {
        
        $this->db->update( 't_mtr_user', array('status'=>-5), array( 'user_id' => $id ) );
        
    }

function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
    }

//added by   : ilham
//fungsi     : reset password 
//parameter  : $user_id
    function reset_password_user($user_id,$mobile) 
    {
        //Get datetime & date
        $datetime = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $password1 = $this->generateRandomString();
        $password2 = md5($password1);
        
        $this->db->update('t_mtr_user', 
            array('must_change_password'    => 1,
            'user_password'                 => $password2),
            array('user_id'                 => $user_id ) 
            );

        $query = $this->db->insert('t_msg_sms', 
            array('sms_type_id' => 1,
            'sms_date'          => $date,
            'sms_from'          => 8818,
            'sms_to'            => $mobile,
            'sms_content'       => 'Password Login Untuk User '.$user_id.' Adalah '.$password1.' ',
            'status'            => 0,
            'created_on'        => $datetime)
            );

        if($query){
            echo json_encode(array(
                'pesan'=>  'Password telah direset'

            ));

          }
          else {
            echo json_encode(array(
                'pesan'=>  'Error reset password'

            ));
          }
        
    }

//added by   : ilham
//fungsi     : Get user status
//parameter  : $id
    function get_user_status($id) 
    {
        $this->db->select("user_id, status");
        $this->db->where("user_id = '$id' ");
        $this->db->from("t_mtr_user");
        return $this->db->get();
    }

//added by   : ilham
//fungsi     : Get user cluster
//parameter  : -
    function get_user_cluster() 
    {
        $this->db->select("cluster_id, cluster_name");
        $this->db->from("t_mtr_cluster");
        return $this->db->get();
    }

//added by   : ilham
//fungsi     : Get user region
//parameter  : -
    function get_user_region() 
    {
        $this->db->select("region_id, region_name");
        $this->db->from("t_mtr_region");
        return $this->db->get();
    }

//added by   : ilham
//fungsi     : Get user group
//parameter  : -
    function get_user_group() 
    {
        $this->db->select("user_group_id, user_group_name");
        $this->db->from("t_mtr_user_group");
        $this->db->order_by("user_group_name", "ASC");
        return $this->db->get();
    }

//added by   : ilham
//fungsi     : Get view_partner from database
//parameter  : -
    function get_partner(){

        $sIndexColumn = "partner_id"; //index column untuk kecepatan akses data
        $defaultOrder = "0"; //default order berdasarkan field yg ada pada $aColumns
        $userlogin ='';
        $flag = '';
        $aColumns = array("partner_id", "partner_name"); //field2 yg akan ditampilkan pada dataTables
        $inputQuery = "SELECT partner_id, concat_ws(' | ',partner_code,partner_name) as partner_name from t_mtr_partner ptr";

        return $this->serversidedatatables->executeServerSideProcess($defaultOrder, $flag, $userlogin, $inputQuery, $aColumns, $sIndexColumn, 'desc');
    }

//added by   : ilham
//fungsi     : Get view_user from database
//parameter  : -
    function get_user(){

        $sIndexColumn = "user_id"; //index column untuk kecepatan akses data
        $defaultOrder = "0"; //default order berdasarkan field yg ada pada $aColumns
        $userlogin ='';
        $flag = '';
        $aColumns = array("user_id", "user_name", "user_group_name", "mobile", "email", "status"); //field2 yg akan ditampilkan pada dataTables
        $inputQuery = " SELECT " . implode(',' , array_values($aColumns))." from v_get_user";

        return $this->serversidedatatables->executeServerSideProcess($defaultOrder, $flag, $userlogin, $inputQuery, $aColumns, $sIndexColumn, 'desc');
    }

//added by   : ilham
//fungsi     : get user cluster
//parameter  : $id
    // function get_user_cluster($id) 
    // {
    //     $dbLK = $this->load->database("default", TRUE);

    //     $query = $dbLK->query("SELECT f_get_cluster('$id')");
        
    //     return $query->get();
    // }


     function getUserRegion($id) 
     {
         $dbLK = $this->load->database("default", TRUE);
         $query = $dbLK->query("select a.user_id, b.region_id from t_mtr_user a
								left join t_mtr_user_territory b on a.user_id=b.user_id
								where a.user_id='$id'");
         return $query->row()->region_id;
     }

/////////////////////////////////////////////////////////////////
////////////////////End-Model User//////////////////////////////
////////////////////////////////////////////////////////////////

    public function getAll() {
        //get all records from users table
        
        $this->db->where(array('status'=>1));
        $query = $this->db->get( 't_mtr_user' );
        
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return array();
        }
    } //end getAll
    
    public function update() {
        $data = array(
            'user_name' => $this->input->post( 'name', true ),
            'email' => $this->input->post( 'email', true )
        );
        
        $this->db->update( 't_mtr_user', $data, array( 'user_id' => $this->input->post( 'id', true ) ) );
		
		//$this->mglobal->saveLog('user','update',$this->input->post('id'));
    }
    
       public function updatePropic($userfile) {
        $data = array(
            'user_picture' => $userfile
        );
        
        return $this->db->update( 't_mtr_user', $data, array( 'user_id' => $this->session->userdata('username')) );
        
        //$this->mglobal->saveLog('user','update',$this->input->post('id'));
    }
    public function delete( $id ) {
        /*
        * Any non-digit character will be excluded after passing $id
        * from intval function. This is done for security reason.
        */
        $id = intval( $id );
        
        //$this->db->delete( 't_mtr_user', array( 'user_id' => $id ) );
		$this->db->update( 't_mtr_user', array('status'=>-5), array( 'user_id' => $id ) );
		
		//$this->mglobal->saveLog('user','delete',$id);
    } //end delete
    
    
        ########################################################################
        ####### DATA USER ##############
        ########################################################################
        function saveUser($edituser="")
        {
                $dblokal  = $this->load->database("default", TRUE);
				$name = $this->input->post("name");
				$email = $this->input->post("email");
				$username = $this->input->post("username");
                $olduser = $this->input->post("olduser");
				$nik = $this->input->post("nik");
                $usergroup = $this->input->post("usergroup");
                $reportingto = $this->input->post("reportingto");
				$hape = $this->input->post("hape");
                $status= $this->input->post("status");
                $must_change_password = $this->input->post("must_change_password");
                
				$data = array("user_name"=>$name,
							"email"=>$email,
							"nik"=>$nik,
							"user_password"=>md5("12345"),
							"user_group_id"=>$usergroup,
							"updated_by"=>$this->session->userdata('username'),
							"mobile_number"=>$hape,
                            "status"=>$status,
                            "must_change_pwd"=> $must_change_password
                            );

				if($reportingto<>""){
					$data["reporting_to"]=$reportingto;
				}else{
					$data["reporting_to"]=$username;
				}
                if($edituser<>""){ 
                        $un = $edituser;
						// $un = $this->uri->segment(4);
                        $data2 = array( "user_id" =>$username,
                                        "user_name"=>$name,
                                        "email"=>$email,
                                        "nik"=>$nik,
                                        "user_group_id"=>$usergroup,
										"updated_on"=>date("Y-m-d H:i:s"),
										"updated_by"=>$this->session->userdata('username'),
                                        "mobile_number"=>$hape,
                                        "status"=>$status,
                                        "must_change_pwd"=> $must_change_password,
                                        "last_time_change_password" => date("Y-m-d")
                                        );
                        if($edituser<>""){ $data2['created_by'] = $this->session->userdata('username');}

						if($reportingto<>""){
							$data2["reporting_to"]=$reportingto;
						}
                        $pwd = $this->input->post("password");
                        if($pwd<>""){
                            $data2['user_password'] = md5($pwd);
                        }
                        
                        $repto_sama = $this->mglobal->showdata("user_id","t_mtr_user",array('user_id'=>$edituser,'reporting_to'=>$reportingto),"dblokal");
						
						$dblokal->where(array("user_id"=>$un));
						$dblokal->update("t_mtr_user",$data2);
						//$this->mglobal->saveLog('user', "update; reportingto=$reportingto" ,$edituser);
						
                        if($reportingto<>"" && $repto_sama == ""){
							#update t_mtr_territory, hal ini boleh dilakukan karena pengecekan terr ada bawahan sudah dilakukan
							#yang diupdate: parent_id berdasarkan user_id
							//kl update user, cek dia maintenance terr/gak $cek_maintenance
							//kl iya, parent_id-nya diganti jadi p_id reportingto-nya $p_id
							//sesuai dengan user_id yang diedit..
							
							$cek_maintenance = $this->mglobal->showdata("territory_id","t_mtr_territory",array('user_id'=>$edituser),"dblokal");
							if($cek_maintenance <> ""){ // dia maintenance terr
								//ambil p_id reportingto
								#$p_id = $this->mglobal->showdata("territory_id","t_mtr_territory",array('user_id'=>$reportingto),"dblokal");
								//$dblokal->where(array("user_id"=>$edituser));
								$dblokal->query("UPDATE t_mtr_territory SET user_id = NULL WHERE user_id='$edituser'");
							}
						}
				}else{
					//$data["created_by"]=$this->session->userdata('username');
					$data["user_id"]=$username;
					
					$dblokal->insert("t_mtr_user",$data);
					$ref_id = $username;
					//$this->mglobal->saveLog('user','create',$ref_id);
				}
        }
        
    function getUserComboStatus() {
        return $this->db->get('v_combo_user_status')->result_array();
    }

    function getUser($id = "", $pid="")
    {    
            $dblokal  = $this->load->database("default", TRUE);
            // $this->db->select('field1, field2');
            // $this->db->select('user_id, user_name, reporting_to, user_group_id');
            if($id<>""){
                if($pid<>""){
                    #if($pid = "pida") $dblokal->where(array("resporting_to"=>$id));
                    if($id== "-"){
                        $dblokal->where("reporting_to = user_id");
                    }else{
                        $dblokal->where("reporting_to != user_id");
                        $dblokal->where(array("reporting_to"=>$id));
                    }
                }else{
                    $dblokal->where(array("user_id"=>$id));
                }
            }
            else{
				$dblokal->where("reporting_to != user_id");
			}
			$dblokal->where("status = 1");
			$dblokal->order_by('user_name','ASC');
            $query = $dblokal->get("t_mtr_user");
            if($pid<>""){
                    return $query;
            }else{
                    if( $query->num_rows() > 0 ) {
                            return $query->result();
                    } else {
                            return array();
                    }
            }
	}
        


        function getUserReportingTo($id)
        {
			//input: usergroupid parentid dari groupid
			//diproses mendapatkan  parentid dari groupid
                
				$dblokal  = $this->load->database("default", TRUE);
				
                $pid = $this->mglobal->showdata("parent_id","t_mtr_user_group",array('user_group_id'=>$id),"dblokal");
                #if($pid==0) $pid=1;
                // $sql = "SELECT * from t_mtr_user
                //         WHERE user_group_id = '$pid' AND status = 1
                //         UNION 
                //         SELECT * from t_mtr_user
                //         WHERE user_id = '$id' AND status = 1
                //         ORDER BY user_names ASC";
                $sql = "SELECT * from t_mtr_user
                        WHERE user_group_id = '$pid' AND status = 1
                        ORDER BY user_name ASC";
    //             $dblokal->where(array("user_group_id"=>$pid));
				// $dblokal->order_by("user_name","ASC");
    //             $query = $dblokal->get("t_mtr_user");
                
				//$query = $dblokal->query("SELECT * FROM f_get_reporting_to('$userId') A (usrId VARCHAR(30), usrName TEXT);");
                $query = $dblokal->query($sql);

                if( $query->num_rows() > 0 ) {
                        return $query->result();
                } else {
                        return array();
                }
	}


    // public function getUserReportingTo($user_group_id)
    // {
    //     $this->db->select('user_id, upper(user_name) as "user_name", user_group_id');
    //     $this->db->where('user_group_id', $user_group_id);
    //     $this->db->where('status', 1);
    //     $this->db->order_by('user_name', 'asc');
    //     $query = $this->db->get('t_mtr_user');
    //     if( $query->num_rows() > 0 ) {
    //         return $query->result();
    //     } else {
    //         return array();
    //     }
    // }
        
        function deleteUser($id){
			$dblokal  = $this->load->database("default", TRUE);
			$this->db->update( 't_mtr_user', array('status'=>0), array( 'user_id' => $id ) );
		
			//$this->mglobal->saveLog('user','delete',$id);
		}
    
        function getUserTree($id = "-",$p=0)
        {
                $query = $this->getUser($id,"pid");
                $k=0;  $p++;
                $jum = $query->num_rows();
                $this->idnya ++;
		
				if($query->num_rows()>0){
                        $this->result .='[';
                        foreach($query->result() as $r){
                                $unencoded=$r->user_id;
                                $r->user_id = $this->enc->fogit($r->user_id); // ini diedit cuyyyyy..aseeekkk

								$link="";
								if($r->created_by <> 'ois') {
									$link="<a href='". base_url() ."index.php/user_management/index/update/". $r->user_id ."'>update</a>";
								}
								
								$usergroup=$this->mglobal->showdata('user_group_name','t_mtr_user_group',array('user_group_id'=>$r->user_group_id),'dblokal');
								$this->result .='{"id":"'. $r->user_id .'",
                                                "name":"'. $r->user_name .'",
                                                "size":"'. $unencoded .'",
                                                "nik":"'. $r->user_id .'",
                                                "emails":"'. $r->email .'",
												"namagroup":"'. $usergroup .'",
												"status":"'. ($r->status==1?"Aktif":"Non Aktif") .'",
                                                "hapes":"'. $r->mobile_number .'",
                                                "date":"'. $link .'",
												"p":"'. $p .'",
                                                "iconCls":"icon-user-'. $p .'"';
												
								$cek = $this->getUser($r->user_id,"pida");
								if($cek->num_rows() >0){
									$this->result .= ',"state":"closed"';
								}
                                $k++;
								$this->result .='}';
                                if($k!=$jum) $this->result .=',';
                        }
                        $this->result .="]";
                }
                return $this->result;
	}
        
        
        function change_password($id="")
        {
                $dblokal  = $this->load->database("default", TRUE);
		$pwd = $this->input->post("new-password");
		
		$data = array("user_password"=>md5($pwd),"must_change_password"=>0, "last_time_change_password"=>date("Y-m-d"));
		$dblokal->where(array("user_id"=>$id));
		$dblokal->update("t_mtr_user",$data);
        }
        
        ##update login_attemp
        function update_login_attemp($user_id,$jumlah){
                $dblokal  = $this->load->database("default", TRUE);
		
				$data = array("logon_attempt"=>$jumlah);
				$dblokal->where(array("user_id"=>$user_id));
				$dblokal->update("t_mtr_user",$data);
				
				//$this->mglobal->saveLog('password','login_attemp',$user_id);
        }
		
		function searchUser($id = "", $name="", $nik="", $group="",$reporting="",$hape="",$email="")
        {    
                $dblokal  = $this->load->database("default", TRUE);
                if($id<>""){
                    $dblokal->like("UPPER(user_id)", strtoupper($id));
				}
				
				if($name<>""){
                    $dblokal->like("UPPER(user_name)", strtoupper($name));
				}
				
				if($nik<>""){
                    $dblokal->like("UPPER(nik)", strtoupper($nik));
				}
				if($group<>""){
                    $dblokal->where("user_group_id", $group);
				}
				if($reporting<>""){
                    $dblokal->where("reporting_to", $reporting);
				}
				if($hape<>""){
                    $dblokal->like("mobile_number", $hape);
				}
				if($email<>""){
                    $dblokal->like("UPPER(email)", strtoupper($email));
				}
				
                $query = $dblokal->get("t_mtr_user");
                
				if( $query->num_rows() > 0 ) {
						return $query->result();
				} else {
						return array();
				}
                
		}
    function mustChangePwd($user_id) {
        $dblokal  = $this->load->database("default", TRUE);
        $this->db->SELECT("must_change_pwd");
        $query = $this->db->get_where("t_mtr_user", array("user_id"=>$user_id));
        return $query->row()->must_change_pwd;
    }

    public function treeGroups($group_id) {
        $dblokal  = $this->load->database("default", TRUE);
        // if ($group_id =='') {
        //     $where = '"user_group_id" = "parent_id"';
        // }
        // else {
        //     $where = '"parent_id" = '.$group_id;
        // }
        // $sql = "SELECT * from t_mtr_user_group where $where ORDER BY user_group_id ASC";
        $sql = "SELECT user_group_id, user_group_name, parent_id from t_mtr_user_group ";

        $query = $dblokal->query($sql)->result_array();

        if ( (is_array ( $query )) && ( count($query) > 0)) {
            foreach ( $query as $object ) {
            $id = $object ['user_group_id'];
            $newData [$id] ['parent_id'] = self::treeGroups($id);
            }
            return $newData;
            }
        }

    public function reset_password($user_id) {
        $data = array(
            "logon_attempt" => 0,
            "user_password" => md5('12345'), 
            "last_time_change_password" => date("Y-m-d"),
            "must_change_password" => 1
            );
        $this->db->where("user_id", $user_id);
        $this->db->update('t_mtr_user', $data); 
        return "12345";
    }

    public function getTerritory($channel_id, $status = null,$table) {
    $dbsf= $this->load->database('smartfren', TRUE);
    $dbsf->where("territory_id", "f_get_territory_id_by_channel_id('$channel_id')", false);
    // $this->db->where('status', $status);
    $query = $dbsf->get($table);
   
    
    return $query->row_array();

  }
    public function cekOutletUnapprove($channel_id){
    $dbsf= $this->load->database('smartfren', TRUE);
    $dbsf->where('outlet_id', $channel_id);
    $query = $dbsf->get("t_mtr_outlet_unapproved");
    return $query;

    }
    public function complements($table, $channel_id) {
    $dbsf= $this->load->database('smartfren', TRUE);
    $dbsf->where('outlet_id', $channel_id);
    $query = $dbsf->get($table);
    // echo '<p>'.$this->db->last_query().'</p>';
    // echo '<p>'.$query->num_rows().'</p>';d

     
      if($table == 't_mtr_outlet_frontliner' || $table == 't_mtr_bank_account' || $table == 't_mtr_outlet_eload' ||
        $table == 't_mtr_outlet_frontliner_unapproved' || $table == 't_mtr_bank_account_unapproved' || $table == 't_mtr_outlet_eload_unapproved')
        { return $query->result_array(); }
      else 
        { return $query->row_array(); }
    
  }
  function channelActivePrograms($channel_id) {
    $dbsf= $this->load->database('smartfren', TRUE);
    $dbsf->select("program_id");
    $query = $dbsf->get_where("t_mtr_channel_program",array("channel_id" => $channel_id));
    return $query->row_array();
  }
public function outletCtegory($outlet_id)
  {
     $dbsf= $this->load->database('smartfren', TRUE);
    $dbsf->select('category_name');
    $query = $dbsf->get_where('t_mtr_channel_category', array('channel_id' => $outlet_id));
    return $query->row();
  }

  public function getPrograms($cluster_id, $program_type, $outlet_id) {
    $dbsf= $this->load->database('smartfren', TRUE);
    $dbsf->select("program_id, caption, description, program_type_id, territory_id");
    $dbsf->where_in("program_type_id", $program_type);
    $dbsf->where("status", 1);
    $dbsf->where_in("territory_id", array( $cluster_id, 1 ) );
    $active = $this->channelActivePrograms($outlet_id);
    // print_r($active);die;
    if (!empty($active)){
    $dbsf->where_in("program_id",  $active  );
    }else {
        $dbsf->where("program_id", 0 );
        
    }

    $query = $dbsf->get("t_mtr_program");
    return $query->result_array();
  }

  public function complement_mfino($channel_id)
  {
    $dbsf = $this->load->database('smartfren', TRUE);

      $dbsf->select('ee.eload_number, ce.user_mfino');
      $dbsf->join("t_mtr_channel_eload ce", "ce.eload_number = ee.eload_number and ce.channel_id = '$channel_id'" ,"left");
      $dbsf->where('ee.outlet_id', $channel_id);
      $query = $dbsf->get("t_mtr_outlet_eload ee");

    return $query->row_array();
  }

  public function get_province() {
    $dbsf= $this->load->database('smartfren', TRUE);
    
    $dbsf->order_by('province_name', 'asc');
    $query = $dbsf->get('t_mtr_province');
    return $query->result();
  }

  public function getUserSts($id){
    $dbsf= $this->load->database('smartfren', TRUE);
    return $dbsf->get_where('t_mtr_user', array('user_id' => $id))->row();
  }
  public function initVars($foo) {
    $dbsf= $this->load->database('smartfren', TRUE);
    $dbsf->select('lookup_key, lookup_value, lookup_desc');
    $dbsf->where_in('lookup_key', $foo);
    $dbsf->order_by('lookup_key, order', 'asc');
    return $dbsf->get('t_mtr_lookup')->result();
  }

  public function cekpassword(){
    $user_id = $this->session->userdata('username');
    $password = md5($this->input->post('password'));
    $query = $this->db->get_where('t_mtr_user', array('user_id'   => $user_id,
                                              'user_password' => $password));
    if ($query->num_rows > 0 ){
        echo json_encode(array(
                    'pesan'=>  'Password Valid',
                    'msg_error' => 1

            ));
    }
    else{
        {
        echo json_encode(array(
                    'pesan'=>  'Password inValid',
                    'msg_error' => 0
            ));
    }
    }
  }

  public function updatePassword(){
    $user_id = $this->session->userdata('username');
    $password = md5($this->input->post('password'));
    $this->db->where('user_id'  , $user_id);
    $query = $this->db->update('t_mtr_user', array('user_password' => $password,
                                                    'must_change_password' => 0,
                                                    'last_time_change_password' => 'now()'));
    if ($query){
        // $this->mglobal->saveLog('password','update',$user_id, '1');
        echo json_encode(array(
                    'pesan'=>  'Update Password Success',
                    'msg_error' => 1

            ));
    }
    else{
        {
        echo json_encode(array(
                    'pesan'=>  'Update Password error',
                    'msg_error' => 0
            ));
    }
    }
  }
} //end class