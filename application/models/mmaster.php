<?php

class Mmaster extends CI_Model {

    function Mmaster() {
        parent::__construct();
        $this->load->library('calendar');
        $this->load->library('serversidedatatables');
    }

    ########################################################################
    ####### DATA USER ##############
    ########################################################################

    function saveUser($edituser = "") {
        $dblokal = $this->load->database("default", TRUE);
        $name = $this->input->post("name");
        $email = $this->input->post("email");
        $username = $this->input->post("username");
        $password = $this->input->post("password");

        $data = array("user_name" => $name,
            "email" => $email,
            "user_password" => $password);

        if ($edituser <> "") {
            $un = $edituser;
            $dblokal->where(array("user_id" => $edituser));
            $dblokal->update("t_mtr_user", $data);
        } else {
            $insert = array("user_id" => $username);
            $dblokal->insert("t_mtr_user", array_merge($data, $insert));
        }
    }

    function getUser($id = "") {
        $dblokal = $this->load->database("default", TRUE);
        if ($id <> "") {
            $dblokal->where(array("user_id" => $id));
        }
        $query = $dblokal->get("t_mtr_user");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }

    function getUserGroup($id = "") {
        $dblokal = $this->load->database("default", TRUE);
        if ($id <> "") {
            $dblokal->where(array("user_id" => $id));
        }
        $query = $dblokal->get("t_mtr_user");
        if ($query->num_rows() > 0) {
            return $query->row()->user_group_id;
        } else {
            return array();
        }
    }

    function deleteUser($username) {
        $dblokal = $this->load->database("default", TRUE);
        $dblokal->delete("t_mtr_user", array("user_id" => $username));
    }

    function getPage($id = "") {
        $dblokal = $this->load->database("default", TRUE);
        $dblokal->order_by("id", "ASC");
        $dblokal->order_by("parent_id", "ASC");
        $dblokal->order_by("ordering", "ASC");
        $query = $dblokal->get("t_menu");
        return $query;
    }

    function getMenuSistem($pid = 0, $group = "") {
        $dblokal = $this->load->database("default", TRUE);

        $dblokal->select('*');
        $dblokal->from("t_mtr_menu");
        if ($group <> "") {
            $dblokal->join("t_mtr_user_role", "t_mtr_user_role.menu_id = t_mtr_menu.id ");
            $dblokal->where("t_mtr_user_role.user_group_id", $group);
        }
        $dblokal->where("parent_id", $pid);
        $dblokal->where("published", 1);
        $dblokal->order_by("ordering", "ASC");
        $query = $dblokal->get();

        return $query;
        /*
          $dblokal  = $this->load->database("default", TRUE);
          $dblokal->order_by("ordering", "ASC");
          $dblokal->where("parent_id", $pid);
          $dblokal->where("published", 1);
          $query = $dblokal->get("t_menu");
          return $query;
         */
    }

    ########################################################################
    ####### DATA DISTRIBUTOR ##############
    ########################################################################

    function saveDistributor($edituser = "") {
        $dblokal = $this->load->database("default", TRUE);
        $name = $this->input->post("name");
        $distributor_add = $this->input->post("distributor_add");

        $data = array("distributor_name" => $name,
            "distributor_add" => $distributor_add);

        if ($edituser <> "") {
            $un = $edituser;
            $dblokal->where(array("distributor_id" => $edituser));
            $dblokal->update("t_mtr_distributor", $data);
        } else {
            $dblokal->insert("t_mtr_distributor", $data);
        }
    }

    function getDistributor($id = "") {
        $dblokal = $this->load->database("default", TRUE);
        if ($id <> "") {
            $dblokal->where(array("distributor_id" => $id));
        }
        $query = $dblokal->get("t_mtr_distributor");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }

    function deleteDistributor($id) {
        $dblokal = $this->load->database("default", TRUE);
        $dblokal->delete("t_mtr_distributor", array("distributor_id" => $id));
    }

    function saveTerritory($name = "", $tr_name) {
        $data = array();
        $data['territory_type_id'] = $this->getTerritoryTypeName($name);
        $data['territory_name'] = $tr_name;
        $data['parent_id'] = $this->getTerritory($this->session->userdata('username'), 'cluster')->row()->territory_id;
        if($this->input->post('maintain_name')!=""){
        $data['user_id'] = $this->input->post('maintain_name');
        }$data['istatus'] = '1';
        $data['created_by'] = $this->session->userdata('username');
        $data['created_on'] = date("Y-m-d H:i:s");
        $data['updated_by'] = $this->session->userdata('username');
        $data['updated_on'] = date("Y-m-d H:i:s");

        $this->db->insert('t_mtr_territory', $data);
    }

    function getProvince($id = "") {
        return $this->db->get_where('t_mtr_province', array('province_id' => $id))->row();
    }

    ########################################################################
    ####### DATA AGENTS ##############
    ########################################################################
    /* function saveAgents($edituser="")
      {
      $dblokal  = $this->load->database("default", TRUE);
      $name = $this->input->post("name");
      $address = $this->input->post("address");
      $teritory_id = $this->input->post("teritory_id");

      $data = array("agent_name"=>$name,"address"=>$address,"teritory_id"=>$teritory_id);

      if($edituser<>""){
      $un = $edituser;
      $dblokal->where(array("agent_id"=>$edituser));
      $dblokal->update("t_mtr_agent",$data);
      }else{
      $dblokal->insert("t_mtr_agent",$data);
      }
      }


      function deleteAgents($id){
      $dblokal  = $this->load->database("default", TRUE);
      $dblokal->delete("t_mtr_agent",array("agent_id"=>$id));
      } */

    /*     * ***************************** TAMBAHAN *************************************** */

    function getTerritoryPath($id = "", $type = "cluster") {

        $tr_type_id = $this->db->get_where('t_mtr_territory_type', array('lower(territory_type_name)' => $type))->row()->territory_type_id;
        $sign = 1;
        $result = '';

        $query = $this->getTerritory($id);
        $row = $query->row();
        $territory_id = $row->territory_id;

        $hasil['territory_id'] = $territory_id;
        $parent_id = $row->parent_id;

        if ($tr_type_id == $row->territory_type_id) {
            $sign = 2;
            $result = $row->territory_name;
        }

        //while( $parent_id != 0 )
        while ($parent_id > 1) {
            $query = $this->getTerritory($parent_id);
            $row = $query->row();
            $parent_id = $row->parent_id;

            if ($sign == 2) {
                $result = $row->territory_name . ' -> ' . $result;
            } else if ($tr_type_id == $row->territory_type_id) {
                $sign = 2;
                $result = $row->territory_name;
            }


            $territory_id = $row->territory_id;
        }

        $hasil['path'] = $result;

        return $hasil;
    }

    function getTerritory($id) {
        //$record = $this->db->get_where( 't_mtr_user_territory', array( f_get_cluster('$user_id')'user_id' => $id ) )->row();
        
        /** @edited Alfin Akhret <alfin.akhret@gmail.com>
        /* optimasi query
        */
        $cluster = $this->db->query("select * from f_get_cluster('".$id."')");
        $cluster = $cluster->result();
        $cluster = $cluster[0]->f_get_cluster;

        $record = $this->db->query("SELECT territory_id, parent_id from t_mtr_territory where territory_id = '" . $cluster . "'")->row();
        
        //print_r ($record);
        if ($record != null)
            $id = $record->territory_id;

        return $this->db->get_where('t_mtr_territory', array('territory_id' => $id));
    }

    function getMaintainName($id = "", $type = "smart fren ambassador") {

        if ($id == "") {
            $login_user = $this->session->userdata('username');
            $this->db->select("* from t_mtr_user_group WHERE lower(user_group_name) = '$type'");
            $query = $this->db->get()->row();

            if ($query != null) {
                $group_id = $query->user_group_id;
                $usr = $this->db->query("SELECT user_group_id,reporting_to from t_mtr_user where user_id='" . $login_user . "'")->result_array();
                if ($usr[0]['user_group_id'] == 4) {
                    $query = $this->db->get_where('t_mtr_user', array('user_group_id' => $group_id, 'reporting_to' => $login_user,'istatus' =>1));
                } elseif ($usr[0]['user_group_id'] == 5) {
                    $query = $this->db->get_where('t_mtr_user', array('user_group_id' => $group_id, 'reporting_to' => $usr[0]['reporting_to'],'istatus'=>1));
                }
            }
            else
                $query = null;
            return $query;
        }
        else {
            $usr_id = $this->db->get_where('t_mtr_territory', array('territory_id' => $id))->row()->user_id;
            if($usr_id != '' and $usr_id!=null){
            if($user_name=$this->db->get_where('t_mtr_user', array('user_id' => $usr_id))->row()->user_name)
            {
                return $user_name;}
            else
                return "Unmaintained";}
                else
                return "Unmaintained";
        }
                

        /* if( $id == "sdsdad" )
          {
          $login_user = $this->session->userdata( 'username' );
          $this->db->select( "* from t_mtr_user_group WHERE lower(user_group_name) = '$type'" );
          $query = $this->db->get()->row();

          if( $query != null )
          {
          $group_id = $query->user_group_id;
          $query = $this->db->get_where( 't_mtr_user', array( 'user_group_id' => $group_id, 'reporting_to' => $login_user ) );
          if($query==null){
          $usr=$this->db->get_where('t_mtr_user',array('user_id'=>$login_user))->row()->reporting_to;

          $query = $this->db->get_where( 't_mtr_user', array( 'user_group_id' => $group_id, 'reporting_to' => $usr ) );

          }
          }
          else
          $query = null;

          return $query;
          }
          else
          {
          $territory=$this->db->query("SELECT f_get_cluster('".$this->session->userdata('username')."')")->result_array();
          $terr_id="";
          foreach($territory as $key=>$value){
          $terr_id=$value['f_get_cluster'];
          }
          $usr_id = $this->db->get_where( 't_mtr_territory', array( 'territory_id' => $terr_id ) )->row()->user_id;
          return $this->db->get_where( 't_mtr_user', array( 'user_id' => $usr_id ) )->row()->user_name;
          } */
    }

    function getUnapprovedMaintainName($id = "", $type = "smart fren ambassador") {

            $usr_id = $this->db->get_where('t_mtr_territory_unapproved', array('territory_id' => $id))->row()->user_id;
            if($usr_id != '' and $usr_id!=null){
            if($user_name=$this->db->get_where('t_mtr_user', array('user_id' => $usr_id))->row()->user_name)
            {
                return $user_name;}
            else
                return "Unmaintained";}
                else
                return "Unmaintained";
    
    }

    function getPosition($id = "") {
        $this->db->select("user_group_name FROM t_mtr_user_group grp JOIN t_mtr_user usr ON usr.user_group_id = grp.user_group_id AND usr.user_id = '" . $id . "'");
        $query = $this->db->get();
        return $query;
    }

    function getTerritoryTypeName($name = "") {
        $this->db->select("* FROM t_mtr_territory_type WHERE lower(territory_type_name) = '" . $name . "'");
        return $this->db->get()->row()->territory_type_id;
    }

    function getOutbondCallerName($id) {
        $this->db->get_where('t_mtr_user', array('user_id' => $id));
    }

    function saveBank($id, $proses, $type) {
        $data = array();

        // ambil data dari form
        $data['bank_name'] = $this->input->post('bank_name');
        $data['bank_branch'] = $this->input->post('bank_branch');
        $data['bank_account_name'] = $this->input->post('bank_account_name');
        $data['bank_account_number'] = $this->input->post('bank_account_number');
        $data['updated_by'] = $this->session->userdata('username');
        $data['updated_on'] = date("Y-m-d H:i:s");

        if ($proses == 'new') {
            $query = $this->db->get_where('t_mtr_' . $type, array($type . '_id' => $id))->row();

            $data['territory_id'] = $query->territory_id;
            $data['created_by'] = $this->session->userdata('username');
            $data['created_on'] = date("Y-m-d H:i:s");
            $data['istatus'] = 0;
            $this->db->insert('t_mtr_bank_account_unapproved', $data);
            $this->db->update('t_mtr_' . $type, array('istatus' => 2), array('territory_id' => $query->territory_id));
        } else {
            $data['istatus'] = 2;
            $query = $this->db->get_where('t_mtr_bank_account', array('bank_account_id' => $id))->row();
            $this->db->insert('t_mtr_bank_account_unapproved', $query);
            $this->db->update('t_mtr_bank_account_unapproved', $data, array('bank_account_id' => $id));
            $tr_id = $query->territory_id;
            $this->db->update('t_mtr_' . $type, array('istatus' => 2), array('territory_id' => $tr_id));
        }
    }

    function getBanks($id) {
        $query = $this->db->get_where('t_mtr_outlet', array('outlet_id' => $id))->row();
        if ($query == null)
            $query = $this->db->get_where('t_mtr_agent', array('agent_id' => $id))->row();

        //kalau $id = id bank 
        if ($query == null) {
            $query = $this->db->get_where('t_mtr_bank_account', array('bank_account_id' => $id))->row();

            return $query;
        } else { //kalau $id = id agent/outlet
            $i = 0;
            $result = array();
            $tr_id = $query->territory_id;

            // ambil dari tabel primer yang sudah disetujui
            //echo $tr_id;
            $query = $this->db->get_where('t_mtr_bank_account', array('territory_id' => $tr_id, 'istatus' => 1));

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $result[$i]->bank_account_id = $row->bank_account_id;
                    $result[$i]->name = $row->bank_name;
                    $result[$i]->branch = $row->bank_branch;
                    $result[$i]->acc_name = $row->bank_account_name;
                    $result[$i]->acc_number = $row->bank_account_number;
                    $i++;
                }
            }


            // ambil dari tabel unapproved yang istatusnya 0 atau 2 
            $this->db->where('territory_id = ', $tr_id);
            $this->db->or_where('istatus > ', '-1');
            $query = $this->db->get('t_mtr_bank_account_unapproved');
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $result[$i]->bank_account_id = $row->bank_account_id;
                    $result[$i]->name = $row->bank_name;
                    $result[$i]->branch = $row->bank_branch;
                    $result[$i]->acc_name = $row->bank_account_name;
                    $result[$i]->acc_number = $row->bank_account_number;
                    $i++;
                }
            }

            if ($i > 0)
                return $result;
            else
                return array();
        }
    }

    ########################################################################
    ########################################################################

    function getRecord($name) {
        return $this->db->get_where($name);
    }

    function getComboData($field) {
        if ($field == 'building')
            return $this->db->get('v_combo_outlet_type');
        if ($field == 'gender')
            return $this->db->get('v_combo_gender');
        if ($field == 'religion')
            return $this->db->get('v_combo_religion');
        if ($field == 'identity')
            return $this->db->get('v_combo_identity_type');
        if ($field == 'o_status')
            return $this->db->get('v_combo_outlet_status');
        if ($field == 'o_type')
            return $this->db->get('v_combo_tipe_outlet');
        if ($field == 'o_size')
            return $this->db->get('v_combo_outlet_size');
        if ($field == 'o_location')
            return $this->db->get('v_combo_outlet_location');
        if ($field == 'o_ownership')
            return $this->db->get('v_combo_outlet_ownership');
        if ($field == 'b_structure')
            return $this->db->get('v_combo_business_structure');
        if ($field == 'b_focus')
            return $this->db->get('v_combo_business_focus');
        if ($field == 'b_size')
            return $this->db->get('v_combo_size_of_business');
        if ($field == 'c_type')
            return $this->db->get('v_combo_customer_type');
        if ($field == 'l_package')
            return $this->db->get('v_combo_lajang_list');
        if ($field == 'e_package')
            return $this->db->get('v_combo_expansion_list');
        if ($field == 'position')
            return $this->db->get('v_combo_position');
        if ($field == 'full_branding')
            return $this->db->get('v_combo_full_branding_by');
        if ($field == 'outbond') {
            $this->db->select("user_id as member_value, user_name as member_display
					FROM t_mtr_user usr
					JOIN t_mtr_user_group grp
					ON usr.user_group_id = grp.user_group_id
					AND lower(grp.user_group_name) = 'outbound caller'
					ORDER BY usr.user_name ASC", FALSE);

            /* array( 'usr.user_group_id' => 'grp.user_group_id', 'lower(grp.user_group_name)' => 'outbond caller'

              $this->db->select( 'user_id as member_value, user_name as member_display' );
              $this->db->from( 't_mtr_user' );
              $this->db->join( 't_mtr_user_group', array( 't_mtr_user.user_group_id' => 't_mtr_user_group.user_group_id', 'lower(t_mtr_user_group.user_group_name)' => 'outbound caller');
              $this->db->order_by( 't_mtr_user_group.user_group_name', 'asc' );
             */

            $query = $this->db->get();
            return $query;
        }

        if ($field == 'a_status'){
        $this->db->order_by('member_value');
            return $this->db->get('v_combo_agent_selling_type');}
        if ($field == 'a_type')
            return $this->db->get('v_combo_agent_type');
        if ($field == 'front_type')
            return $this->db->get('v_combo_frontliner_type');
        if ($field == 'marital')
            return $this->db->get('v_combo_marital_status');
    }

    ########################################################################
    ####### DATA GALLERY ##############
    ####### Edited by Rizky Ramadhan ##############
    ####### Edited on 24 Maret 2012 ##############
    ########################################################################

    public function getCluster() {
        $this->db->select('*');
        $this->db->from('t_mtr_territory');
        $this->db->where('territory_type_id', 3);
        $query = $this->db->get();

        return $query->result();
    }

    function saveGallery($editgallery = "", $result_array) {
        $dblokal = $this->load->database("default", true);
        $gallery_name = $this->input->post("gallery_name");
        $gallery_address = $this->input->post("gallery_address");

        $data = array("gallery_name" => $gallery_name, "gallery_address" => $gallery_address, "created_by" => $this->session->userdata('username'), "updated_by" => $this->session->userdata('username'), "image_path" => $result_array[0]['orig_name']);

        $this->db->trans_begin();
        if ($editgallery <> "") {
            $dblokal->where(array("gallery_id" => $editgallery));
            $dblokal->update("t_mtr_gallery", $data);
        } else {
            $dblokal->insert("t_mtr_gallery", $data);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    function getFreshGallery() {
        $this->db->select('*')->from('t_mtr_gallery');
        $this->db->order_by('gallery_id', 'desc');
        $query = $this->db->get();

        return $query->row();
    }

    function saveGalleryDetail($gallery_id, $i) {
        $dblokal = $this->load->database("default", true);
        $territory_id = $this->input->post('territory_id');

        $data = array("gallery_id" => $gallery_id, "istatus" => 1, "territory_id" => $territory_id[$i], "created_by" => $this->session->userdata('username'), "updated_by" => $this->session->userdata('username'));

        $this->db->trans_begin();
        $dblokal->insert("t_mtr_gallery_outlet", $data);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    function getGallery($id = "") {
        $dblokal = $this->load->database("default", true);
        if ($id <> "") {
            $dblokal->where(array("gallery_id" => $id));
        }
        $query = $dblokal->get("t_mtr_gallery");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }

    function getParent($parent_id = "") {
        $dblokal = $this->load->database("default", true);
        if ($parent_id <> "") {
            $dblokal->where(array("territory_id" => $parent_id));
        }
        $query = $dblokal->get("t_mtr_territory");
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return array();
        }
    }

    function getGalleryDetail($gallery_id = "") {
        $dblokal = $this->load->database("default", true);
        if ($gallery_id <> "") {
            $dblokal->where(array("gallery_id" => $gallery_id));
        }
        $query = $dblokal->get("t_mtr_gallery_outlet");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }

    function deleteGallery($id) {
        $dblokal = $this->load->database("default", true);
        $this->db->trans_begin();
        $dblokal->delete("t_mtr_gallery", array("gallery_id" => $id));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    function deleteGalleryDetail($id) {
        $dblokal = $this->load->database("default", true);
        $this->db->trans_begin();
        $dblokal->delete("t_mtr_gallery_outlet", array("gallery_id" => $id));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    ########################################################################
    ########################################################################
    ########################################################################
    ####### DATA HANDSET ###################################################
    ########################################################################

    function saveHandset($edituser = "") {
        $dblokal = $this->load->database("default", TRUE);
        $model = $this->input->post("model");
        $man = $this->input->post("man");
        $salesId = $this->input->post("salesId");
        $imei = $this->input->post("imei");
        $imsi = $this->input->post("imsi");
        $msisdn = $this->input->post("msisdn");
        $handset_id = $this->input->post("handset_id");

        $data = array("handset_model" => $model,
            "handset_man" => $man,
            "user_id" => $salesId,
            "imei" => $imei,
            "imsi" => $imsi,
            "msisdn" => $msisdn);
        if ($edituser <> "") {
            $un = $edituser;
            $dblokal->where(array("handset_id" => $edituser));
            $dblokal->update("t_mtr_handset", $data);
        } else {
            $dblokal->insert("t_mtr_handset", $data);
        }
    }

    // edited by : alfin akhret
    // fungsi ini dimodifikasi sesuai kebutuhan SSP dataTables
    function getHandset($id = "") {
        //$dblokal = $this->load->database("default", TRUE);
        // $sql = "";
        //$inputQuery = "";

        //tentukan parameter utk fungsi serversidedatatables()
        $sIndexColumn = "user_name"; 
        $sTable="t_mtr_handset"; 
        $sTable2 = "t_mtr_user"; 
        $status="";
        $defaultOrder = "2";
        $userlogin = $this->session->userdata('username');
        
        //tentukan field2 yg akan di tampilkan oleh dataTables.
        //field tdk bisa berbentuk alias . ex: t_mtr_user.user_id
        $aColumns = array("handset_id", "handset_model", "handset_man", "user_name", "imei", "imsi", "msisdn");

        //jika isset($id), execute kode dibawah
        //ini berguna utk fitur "update handset"
        if ($id <> "") {
            array_push($aColumns, "t_mtr_handset.user_id");
            // $inputQuery = "SELECT h.*, u.user_name FROM t_mtr_handset h, t_mtr_user u WHERE handset_id = ? AND h.user_id = u.user_id AND f_get_cluster(h.user_id) = f_get_cluster('$userogin') AND u.istatus = 1 ORDER BY u.user_name ASC";

            $str1 = array(" , ", ", ' '");
            $str2 = array(" ", " ");
            $inputQuery = $this->db->query("select ".str_replace($str1, $str2, implode(", ", $aColumns))."
                            from $sTable join $sTable2
                            on handset_id = $id 
                            and ".$sTable.".user_id = ".$sTable2.".user_id 
                            and f_get_cluster(".$sTable.".user_id) = f_get_cluster('$userlogin') 
                            and ".$sTable2.".istatus = 1
                            order by ".$sTable2.".user_name ASC" 
                            );
            
            return $inputQuery->result();

        } else { 
            // jika id = "" , execute fungsi serversidedatatables->executeServerSideProcess()
            // ini berguna utuk menampilkan dataTables handset

            // $sql = "SELECT h.*, u.user_name FROM t_mtr_handset h, t_mtr_user u WHERE h.user_id = u.user_id AND f_get_cluster(h.user_id) = f_get_cluster('$userLogin') AND u.istatus = 1 ORDER BY u.user_name ASC";

            $str1 = array(" , ", ", ' '");
            $str2 = array(" ", " ");
            $inputQuery = "select ".str_replace($str1, $str2, implode(", ", $aColumns))."
                            FROM $sTable join $sTable2
                            on ".$sTable.".user_id = ".$sTable2.".user_id
                            and f_get_cluster(".$sTable.".user_id) = f_get_cluster('$userlogin')
                            and ".$sTable2.".istatus = 1"
                            ;
            return $this->serversidedatatables->executeServerSideProcess($defaultOrder, $status, $userlogin, $inputQuery, $aColumns, $sIndexColumn);  
        }
        // order by ".$sTable2.".user_name ASC"
        //$query = $dblokal->get("t_mtr_handset");
        // $query = $dblokal->query($sql, array($id));
        // if ($query->num_rows() > 0) {
        //     return $query->result();
        // } else {
        //     return array();
        // }

        // var_dump($inputQuery);die();
        //execute datatables serversideprocess
            
    }

    function deleteHandset($id) {
        $dblokal = $this->load->database("default", TRUE);
        $dblokal->delete("t_mtr_handset", array("handset_id" => $id));
    }

    ########################################################################
    ####### DATA USER LIST ##############
    ########################################################################

    function getUserList($id = "") {
        $userLogin = $this->session->userdata('username');
        $dblokal = $this->load->database("default", TRUE);
//        $sql = "SELECT usr.user_id, usr.user_name, grp.user_group_name AS position 
//			FROM t_mtr_user usr 
//			JOIN t_mtr_user_group grp ON usr.user_group_id = grp.user_group_id
//			AND lower(grp.user_group_name) IN ('smart fren ambassador', 'direct sales') AND usr.istatus = 1 
//			ORDER BY usr.user_name";
//			AND grp.user_group_id IN (8,9) AND usr.istatus = 1
        $query = $dblokal->query("SELECT usr.user_id, usr.user_name, grp.user_group_name AS position 
			FROM t_mtr_user usr 
			JOIN t_mtr_user_group grp ON usr.user_group_id = grp.user_group_id
                        AND grp.is_use_handset = 1 AND usr.istatus = 1
                        AND f_get_cluster(usr.user_id) = f_get_cluster('$userLogin')
			ORDER BY usr.user_name;");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }

    ########################################################################
    ########################################################################

    function setReleaseAllUsers() {
        $dblokal = $this->load->database("default", TRUE);
        $dblokal->query("UPDATE t_mtr_user SET is_login=0");
    }
    
    function checkUserRole($menu_id){   
        $user_group=$this->db->Query("SELECT user_group_id from t_mtr_user where user_id='".$this->session->userdata('username')."'")->row()->user_group_id;
        $query=$this->db->query("SELECT coalesce(menu_add,0) as menu_add,coalesce(menu_view,0) as menu_view,coalesce(menu_edit,0) as menu_edit, coalesce(menu_delete,0) as menu_delete,coalesce(menu_approve,0) as menu_approve,coalesce(menu_reject,0) as menu_reject from t_mtr_user_role where user_group_id='".$user_group."' and menu_id=$menu_id");       
       
       if($query->row()){
            $result=$query->result_array();
            return  $result[0]; 
        }     
        else{
            $result['menu_add']=0;
            $result['menu_view']=0;
            $result['menu_edit']=0;
            $result['menu_delete']=0;
            $result['menu_approve']=0;
            $result['menu_reject']=0;}
           return $result;
    }
    

    #############################################################################################

    function checkType( $id )
    {
        $query = $this->db->get_where( "t_mtr_outlet", array( "outlet_id" => $id ) )->row();
        if( $query == null )
            return array(2,4);
        else 
            return array(1,3);
    }
    
    // @param -> outlet_id atau agent_id
    function getPrograms($id) {
        $userLogin = $this->session->userdata('username');
        $program_type = $this->checkType($id);

        $cluster = $this->db->get("f_get_cluster('$userLogin')")->row()->f_get_cluster;
        $this->db->select("program_id, caption, description, program_type_id, territory_id");
        $this->db->where_in("program_type_id", $program_type);
        $this->db->where("istatus", 1);
        $this->db->where_in("territory_id", array( $cluster, 1 ) );

        $query = $this->db->get("t_mtr_program");
        return $query->result();
    }

    function getActivePrograms($outlet_id) {
        $this->db->select("program_id");
        $query = $this->db->get_where("t_mtr_channel_program",array("channel_id" => $outlet_id));
        return $query->result();
    }


    //update last activity
    function update_last_activity($user_id, $et=20)
	{
		date_default_timezone_set('Asia/Jakarta');
		$query = $this->db->get_where('t_trx_last_activity', array('user_id' => $user_id));
		
		if($query->num_rows == 1)
		{
		    //update
		    if($query->row()->elapsedTime > $et){
			    $et = $query->row()->elapsedTime;
		    }
		    
		    $et = $et-3;
		    
		    $is_login = $this->mglobal->showdata("is_login","t_mtr_user",array('user_id'=>$user_id),"dblokal");
		    if($is_login == 0) $et = 0;
		    
		    $this->db->where('user_id', $user_id);
		    $this->db->update('t_trx_last_activity', array('elapsedTime'=>$et,'ip_address'=>$this->input->ip_address(), 'lastActivity' => date('H:i:s')));
		}
		else
		{
		    $this->db->insert('t_trx_last_activity', array('user_id' => $user_id,'ip_address'=>$this->input->ip_address(), 'elapsedTime'=>$et, 'lastActivity' => date('H:i:s')));
		}
	}

    //check last activity
    function check_last_activity($user_id, $for_login = "")
    {
        //check last activity here
        if($for_login == ""){ $this->update_last_activity($user_id); }
        $this->db->select('lastActivity,ip_address');
        $last_activity = $this->db->get_where('t_trx_last_activity', array('user_id' => $user_id));
        return $last_activity->last_row('array');
    }
    
    function get_last_activity($user_id)
    {
        //check last activity here
        $this->db->select('lastActivity');
        $last_activity = $this->db->get_where('t_trx_last_activity', array('user_id' => $user_id));
        return $last_activity->last_row('array');
    }
    
    
}