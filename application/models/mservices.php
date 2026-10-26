<?php
class Mservices extends CI_Model {

	function mservices(){
		parent::__construct();
                $this->load->model("mglobal");
	}
	
        ########################################################################
        ####### SERVICES ##############
        ########################################################################
        function saveService($edituser="")
        {
                $dblokal  = $this->load->database("default", TRUE);
				$name = $this->input->post("name");
				$sess['web'] = $this->input->post("web");
				$sess['mobile'] = $this->input->post("mobile");
				
				#setting session dulu u/ web & mobile
				$this->session->set_userdata($sess);
				
				$table_name = "t_serv_".strtolower(str_replace(" ","_",$name));
				$data = array("services_name"=>$name,
							"services_tabel"=>$table_name,
                              "created_by"=>$this->session->userdata("username"),
                              "created_on"=>date("Y-m-d H:i:s"));
				$ref_id = "";
				if($edituser<>""){ 
					$dblokal->where(array("services_id"=>$edituser));
					$dblokal->update("t_trx_service",$data);
				}else{
					$dblokal->insert("t_trx_service",$data);
					$this->load->dbforge();
					$fields = array(
									'id' => array(
											'type' => 'INT',
											'constraint' => 5, 
											'unsigned' => TRUE,
											'auto_increment' => TRUE
									)
					);
					$this->dbforge->add_field($fields);
					$this->dbforge->add_key('id', TRUE);
					$this->dbforge->create_table($table_name);
					$ref_id = $this->db->insert_id();
					$this->mglobal->saveLog('service','create',$ref_id);
				}
                redirect(base_url()."index.php/services/manage_field/".$ref_id);
        }
        
        function getService($id = "")
        {    
                $dblokal  = $this->load->database("default", TRUE);
                if($id<>""){
                        $dblokal->where(array("services_id"=>$id));
                }
                $query = $dblokal->get("t_trx_service");
                if( $query->num_rows() > 0 ) {
                        return $query->result();
                } else {
                        return array();
                }
	}
        
        function deleteService($id){
                $this->load->dbforge();
				$dblokal  = $this->load->database("default", TRUE);
		
                $table_name = $this->mglobal->showdata("services_tabel","t_trx_service",array("services_id"=>$id),"dblokal");
                $dblokal->delete("t_trx_service",array("services_id"=>$id));
                $dblokal->delete("t_trx_service_property",array("services_id"=>$id));
                $this->dbforge->drop_table($table_name);
				$this->mglobal->saveLog('service','delete',$id);
	}
        
        function getComponent($id)
        {    
                $dblokal  = $this->load->database("default", TRUE);
                $dblokal->where(array("services_id"=>$id));
                $query = $dblokal->get("t_trx_service_property");
                if( $query->num_rows() > 0 ) {
                        return $query->result();
                } else {
                        return array();
                }
	}
        
        function saveComponent($servid,$edit="")
        {
                $dblokal  = $this->load->database("default", TRUE);
		$name = $this->input->post("nama");
		$jenis = $this->input->post("jenis");
                $data_source = $this->input->post("data_source");
                
                $display = "";
                $value="";
                if($data_source<>""){
                        $display = $this->mglobal->showdata("field_display","t_mtr_table_list",array("table_name"=>$data_source),"dblokal");
                        $value   = $this->mglobal->showdata("field_value","t_mtr_table_list",array("table_name"=>$data_source),"dblokal");
                }
		
                $nmfield = strtolower(str_replace(" ","_",$name));
                
		$data = array("services_id"             =>$servid,
			      "t_services_column"       =>$nmfield,
                              "t_elm_type"              =>$jenis,
                              "t_elm_label"             =>$name,
                              "t_source_name"           =>$data_source,
                              "t_source_column_display" =>$display,
                              "t_source_column_value"   =>$value,
                              "enabled_web"   =>$this->session->userdata('web'),
                              "enabled_mobile"   =>$this->session->userdata('mobile'),
                              "created_by"              =>$this->session->userdata("username"),
                              "created_on"              =>date("Y-m-d H:i:s"));
		
		if($edit<>""){ 
			$un = $edit;
			$dblokal->where(array("property_id"=>$edit));
			$dblokal->update("t_trx_service_property",$data);
			$this->mglobal->saveLog('service_property','update',$edit);
		}else{
			$dblokal->insert("t_trx_service_property",$data);
			$ref_id = $this->db->insert_id();
			$this->mglobal->saveLog('service_property','create',$ref_id);
                        $table_name = $this->mglobal->showdata("services_tabel","t_trx_service",array("services_id"=>$servid),"dblokal");
                        
                        $this->load->dbforge();
                        $field = array(
                                        $nmfield => array(
                                                        'type' => 'varchar',
                                                        'constraint' => 75
                                                )
                                );
                        $this->dbforge->add_column($table_name, $field);
		}
        }
        
        function deleteComponent($idc){
		$dblokal  = $this->load->database("default", TRUE);
		
                $servid = $this->mglobal->showdata("services_id","t_trx_service_property",array("property_id"=>$idc),"dblokal");
                $table_name = $this->mglobal->showdata("services_tabel","t_trx_service",array("services_id"=>$servid),"dblokal");
                $field_name = $this->mglobal->showdata("t_services_column","t_trx_service_property",array("property_id"=>$idc),"dblokal");
                
                $dblokal->delete("t_trx_service_property",array("property_id"=>$idc));        
                $this->load->dbforge();
                $this->dbforge->drop_column($table_name, $field_name,TRUE);
				$this->mglobal->saveLog('service_property','delete',$idc);
	}
        
        function getValueFromTable( $tbl, $where=array(), $db="dblokal" )
        {    
                $dblokal  = $this->load->database("default", TRUE);
                
                ${$db}->where($where);
                $query = ${$db}->get($tbl);
                if($query->num_rows() > 0) {
                        return $query->result();
                } else {
                        return array();
                }       
	}
        
        function getValueFromTableIN( $tbl, $where=array(), $db="dblokal" )
        {    
                $dblokal  = $this->load->database("default", TRUE);
                
                ${$db}->where_in('user_group_id',$where);
				${$db}->order_by('user_name','ASC');
                $query = ${$db}->get($tbl);
                if($query->num_rows() > 0) {
                        return $query->result();
                } else {
                        return array();
                }       
	}
        
        function getTable( )
        {    
                $dblokal  = $this->load->database("default", TRUE);
                
                $query = $dblokal->get("t_mtr_table_list");
                if($query->num_rows() > 0) {
                        return $query->result();
                } else {
                        return array();
                }       
	}
        
        
        ###########################
        function getDataByTable($tabel, $id="") {
                $dblokal  = $this->load->database("default", TRUE);
                if($id<>""){
                        $dblokal->where(array("id"=>$id));
                }
                $query = $dblokal->get($tabel);
                if( $query->num_rows() > 0 ) {
                        #cek punya relation dengan yang lain?
                        return $query->result();
                } else {
                        return array();
                }
        }
        
        ###########################################
        ##Manage data hasil service yang sudah dibuat
        function saveServiceData($table_name,$edituser="")
        {
                $dblokal  = $this->load->database("default", TRUE);
				$id = $this->mglobal->showdata("services_id","t_trx_service",array('services_tabel'=>$table_name),"dblokal");
                $getComponent   = $this->mservices->getComponent($id);
                
                $data = array();
                foreach($getComponent as $r){
                        $data[$r->t_services_column] = $this->input->post($r->t_services_column);
                }
                
                if($edituser<>""){ 
					$dblokal->where(array("id"=>$edituser));
					$dblokal->update($table_name,$data);
					$this->mglobal->saveLog('service_data_'.$table_name,'update',$edituser);
				}else{
					$dblokal->insert($table_name,$data);
					$ref_id = $this->db->insert_id();
					$this->mglobal->saveLog('service_data_'.$table_name,'create',$ref_id);
				}
				redirect(base_url()."index.php/services/show_data/".$table_name,"/new");
		}
		
		function getOtoritas($id){
			$dblokal  = $this->load->database("default", TRUE);
			$query= $dblokal->query("SELECT * FROM t_mtr_user_group,t_trx_service_authority
									WHERE t_mtr_user_group.user_group_id = t_trx_service_authority.group_id
										AND service_id='".$id."'");
			$hasil = "-belum ada-";
			$isi = array();
			if($query->num_rows()>0){
				foreach($query->result() as $r){
					$isi[] = $r->user_group_name;
				}
				$hasil = implode(", ",$isi);
			}
			
			return $hasil;
		}
		
		function saveOtoritas(){
			$dblokal  = $this->load->database("default", TRUE);
			
			$id = $this->input->post('id');
			$group = $this->input->post('group');
			
			$dblokal->where(array('service_id'=>$id));
			$dblokal->delete('t_trx_service_authority');
			for($i=0;$i<count($group);$i++){
				$isi = array('group_id'=>$group[$i],
							 'service_type'=>'',
							 'service_id'=>$id,
							 'service_name'=>'',
							 'created_by'=>$this->session->userdata('username'));
				$dblokal->insert('t_trx_service_authority',$isi);
				$ref_id = $this->db->insert_id();
				$this->mglobal->saveLog('service_otoritas','create',$ref_id);
			}
			return;
		}
}