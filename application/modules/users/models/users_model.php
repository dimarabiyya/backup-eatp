<?php

class Users_model extends CI_Model {
	
	public function authGranted($username, $password)
	{
		$this->db->where('user_id', $username);
		$this->db->where('user_password', $password);
		//$this->db->where('user_status', 1);
		$query = $this->db->get('t_mtr_user');
		
		if ($query->num_rows()) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_user_group()
	{
		$this->db->order_by('user_group_id', 'ASC');
		$query = $this->db->get('mtr_user_group');
		
		return $query->result_array();
	}
	
	public function get_users()
	{
		$this->db->select('mtr_user.*, mtr_user_group.user_group_name');
		$this->db->join('mtr_user_group', 'mtr_user_group.user_group_id = mtr_user.user_group_id', 'left');
		$this->db->order_by('user_name', 'ASC');
		$query = $this->db->get('mtr_user');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_users_all($limit = 20, $offset = 0, $search = '')
	{
		//$query = $this->db->select()->from($this->table)->limit($limit, $offset)->order_by('name', 'ASC');
		//if ($search != '') {
			// ...
		//}
		
		$this->db->select('mtr_user.*, mtr_user_group.user_group_name');
		$this->db->join('mtr_user_group', 'mtr_user_group.user_group_id = mtr_user.user_group_id', 'left');
		$this->db->order_by('user_name', 'ASC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get('mtr_user');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_total_rows()
	{
		return $this->db->count_all('mtr_user');
	}
	
	function getMenuSistem($pid = 0, $group = "") {
        $dblokal = $this->load->database("default", TRUE);

        $dblokal->select('*');
        $dblokal->from("t_menu");
        if ($group <> "") {
            $dblokal->join("t_mtr_user_role", "t_mtr_user_role.menu_id = t_menu.id ");
            $dblokal->where("t_mtr_user_role.user_group_id", $group);
        }
        $dblokal->where("parent_id", $pid);
        $dblokal->where("published", 1);
        $dblokal->order_by("ordering", "ASC");
        $query = $dblokal->get();

        return $query;
    }
	
	function showData( $data, $tbl, $where, $db, $like = "" )
	{    
		$dblokal  = $this->load->database("default", TRUE);
		
		${$db}->where($where);
		if($like <> "") ${$db}->like($like);
		$query = ${$db}->get($tbl);
		$val=1;
		if($val == 0){
				return "";
		}else{
				if($query->num_rows() > 0) {
						$r = $query->row();
						return $r->{$data};
				} else {
						return "";
				}       
		}
	}
	
	function saveUserRoleAction($group,$menu,$nilai,$actid)
	{
		$dblokal  = $this->load->database("default", TRUE);
		$where = array("user_group_id"=>$group,"menu_id"=>$menu);
		$data[ $actid ]=0;
		if($nilai == "true"){
				$data[ $actid ]=1;
		}
		$dblokal->where($where);
		$dblokal->update("t_mtr_user_role",$data);
		
		return TRUE;
	}
	
	function saveUserRole($group,$menu,$nilai)
	{
		$dblokal  = $this->load->database("default", TRUE);
		$data = array("user_group_id"=>$group,"menu_id"=>$menu);
		if($nilai == "true"){
				$dblokal->insert("t_mtr_user_role",$data);
		}elseif($nilai == "false"){
				$dblokal->delete("t_mtr_user_role",$data);
		}
	}
	
	public function getUserComboStatus()
	{
        $query = $this->db->query("SELECT * FROM v_combo_user_status ORDER BY member_value");
		return $query->result_array();
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
		$dblokal->where("istatus = 1");
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
			
			$pid = $this->users_model->showdata("parent_id","t_mtr_user_group",array('user_group_id'=>$id),"dblokal");
			#if($pid==0) $pid=1;
			// $sql = "SELECT * from t_mtr_user
			//         WHERE user_group_id = '$pid' AND istatus = 1
			//         UNION 
			//         SELECT * from t_mtr_user
			//         WHERE user_id = '$id' AND istatus = 1
			//         ORDER BY user_names ASC";
			$sql = "SELECT * from t_mtr_user
					WHERE user_group_id = '".(int) $pid."' AND istatus = 1
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
		$istatus= $this->input->post("istatus");
		$must_change_password = $this->input->post("must_change_password");
		
		$data = array("user_name"=>$name,
					"email"=>$email,
					"nik"=>$nik,
					"user_password"=>md5("12345"),
					"user_group_id"=>$usergroup,
					"updated_by"=>$this->session->userdata('username'),
					"mobile_number"=>$hape,
					"istatus"=>$istatus,
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
								"istatus"=>$istatus,
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
					
					$cek_maintenance = $this->users_model->showdata("territory_id","t_mtr_territory",array('user_id'=>$edituser),"dblokal");
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
	
}