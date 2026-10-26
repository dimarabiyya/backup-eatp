<?php
class Mjourney extends CI_Model {

	function Mjourney(){
		parent::__construct();
                $this->setVars();
        }
        
        function setVars()
        {
            $this->result = '';
            $this->idnya = 0;
        }
        
        function getJourney($sfa, $bulan, $tahun, $page=1, $cari="")
        {
                $dblokal = $this->load->database("default", TRUE);
		        $keyword = strtoupper( str_replace("'", "''", strtoupper($cari) ) );

                $this->_patch($sfa, $bulan, $tahun);
            // yaddis@nutech --- 09/05/2012

                $dblokal->join('t_mtr_outlet ot', 'jc.channel_id = ot.outlet_id',false);
                $dblokal->join('t_mtr_territory AS tt', 'tt.territory_id = ot.territory_id', false);
                $dblokal->where( 'jc.yyyymm', $tahun.$bulan );
                $dblokal->where('tt.user_id', $sfa);
				
				if($cari <> "")
                    {
                        $dblokal->where("(UPPER(tt.territory_name) LIKE '%".$keyword."%' OR UPPER(tt.territory_name) LIKE '%".$keyword."%' OR UPPER(ot.outlet_id) LIKE '%".$keyword."%' )"); 
                    }
				
                $dblokal->order_by("tt.territory_name", "asc"); 
                
                $dblokal->limit(15, 15*($page-1));
                $query = $dblokal->get("t_jc_".$sfa." AS jc");

                // $query = $dblokal->get();

                                
                if( $query->num_rows() > 0 ) {
                        //Patched By RAA
                        $result=$query->result();
                    if($page==="count"){
                        $year=date('Y');
                        $month=date('m');
                        if($year.$month==$tahun.$bulan){
 
                          $existOutlet=$this->db->query("select channel_id from t_jc_".$sfa." where yyyymm='".$tahun.$bulan."'")->result_array();
                                                 
  		
                          $getOutlet = $this->getOutlet($sfa,$existOutlet);
                        if(count($getOutlet)>0){
                          foreach($getOutlet as $r){
                                  $data = array("parent_id"=>$r->territory_id,
                                                "yyyymm"=>$tahun.$bulan,
                                                "channel_id"=>$r->outlet_id,
                                                "created_by"=>$this->session->userdata("username").$page,
                                                "created_on"=>date("Y-m-d H:i:s"));
                                  $dblokal->insert("t_jc_".$sfa,$data);
          						
          						$ref_id = $this->db->insert_id();
          						$this->mglobal->saveLog('JC_SFA_UPDATE_JOURNEY_'."t_jc_".$sfa,'update',$ref_id);
                          }
                      } 
                    
                        //End Of Patch                
                    }
                    }

                    return $query->result();
                } else {
                        return array();
                }
        }
		
        public function sfa_outlets($sales_id, $yyyymm = "")
        {
            $this->db->distinct();
            $this->db->select("count(tt.territory_id)");
            $this->db->join("t_mtr_territory AS tt","tt.territory_id = oo.territory_id");
            $this->db->where("tt.user_id", $sales_id );
            $query = $this->db->get("t_mtr_outlet AS oo");
            return $query->row_array();
        }
		function getJourneySGS($sfa, $bulan, $tahun, $page=1, $cari="")
        {
                $dblokal = $this->load->database("default", TRUE);
		        $keyword = str_replace("'", "''", strtoupper($cari) );
                $ada = $this->mglobal->showdata("territory_id","t_jc_31",array("user_id"=>$sfa,"yyyymm"=>$tahun.$bulan),"dblokal");
				if($ada <> ""){	
					$sql = "SELECT outlet_id,agent_id, outlet_name,agent_name,
								t_mtr_outlet.territory_id as terr_out, t_mtr_agent.territory_id as terr_agn, sfa.*
							FROM t_jc_31 as sfa
								LEFT JOIN t_mtr_outlet ON t_mtr_outlet.territory_id = sfa.territory_id
								LEFT JOIN t_mtr_agent ON t_mtr_agent.territory_id = sfa.territory_id
							WHERE yyyymm = '".$tahun.$bulan."' AND user_id='$sfa'";
					
					if($cari <> ""){
						$sql .= " AND (
									UPPER(t_mtr_outlet.territory_id) LIKE '%".$keyword."%'
									OR UPPER(outlet_name) LIKE '%".$keyword."%'
									OR UPPER(t_mtr_agent.territory_id) LIKE '%".$keyword."%'
									OR UPPER(agent_name) LIKE '%".$keyword."%'
									)";
					}
					
					$sql.=" ORDER BY outlet_name,agent_name";
					if($page <>""){
							$dblokal->limit(15, 15*($page-1));
					}
					$query = $dblokal->query($sql);
					
					if( $query->num_rows() > 0 ) {
							return $query->result();
					} else {
							return array();
					}
				}else{
					return array();
				}
        }
		
		function getTotalKunjungan($sfa, $tanggal, $bulan, $tahun)
        {
                $dblokal = $this->load->database("default", TRUE);
		
                $dblokal->select('*');
                $dblokal->from("t_jc_".$sfa);
                $dblokal->join("t_mtr_outlet", "t_mtr_outlet.outlet_id = t_jc_".$sfa.".channel_id ");
                $dblokal->where('yyyymm', $tahun.$bulan);
				$dblokal->where('d'.$tanggal, '1');
                $dblokal->order_by("outlet_name", "asc");
                $query = $dblokal->get();
                
                return $query->num_rows();
        }
		
		function getTotalKunjunganSGS($user, $tanggal, $bulan, $tahun)
        {
                $dblokal = $this->load->database("default", TRUE);
				
				$dblokal->select('*');
                $dblokal->from("t_jc_31");
                $dblokal->where('user_id', $user);
				$dblokal->where('yyyymm', $tahun.$bulan);
				$dblokal->where('d'.$tanggal, '1');
                $query = $dblokal->get();
                
                return $query->num_rows();
        }
        
        function getJourney_ds($sfa, $bulan, $tahun)
        {
            $dblokal = $this->load->database("default", TRUE);

            ## UPDATE AGENT CODE BEFORE POPULATE JOURNEY CYCLE
            ## yaddis@nutech-integrasi.com
            // $this->db->from("t_mtr_agent");
            // $this->db->where("replace(t_jc_$sfa.channel_id, 'A', 'M') = t_mtr_agent_id.agent_id" );
            // $this->db->where("t_mtr_agent.agent_selling_type", 2 );
            // $this->db->update("t_jc_$sfa", array('channel_id' => "replace(channel_id, 'A', 'M' )" ));


            $sql = "UPDATE t_jc_$sfa
                    SET channel_id = replace(channel_id,'A','M')
                    FROM t_mtr_agent
                    WHERE replace(t_jc_$sfa.channel_id,'A','M') = t_mtr_agent.agent_id
                    AND t_mtr_agent.agent_selling_type = 2 ";
            $dblokal->query($sql);

            $dblokal->select('*');
            $dblokal->from("t_jc_".$sfa);
            $dblokal->join("t_mtr_agent", "t_mtr_agent.agent_id = t_jc_".$sfa.".channel_id ");
            $dblokal->where('yyyymm', $tahun.$bulan);
            $dblokal->order_by("agent_name", "asc"); 
            $query = $dblokal->get();
            
            if( $query->num_rows() > 0 ) {
                return $query->result();
            } else {
                return array();
            }
        }
        
        function copyJourney($sfa, $bulan_asal, $tahun_asal, $bulan, $tahun)
        {
                $dblokal = $this->load->database("default", TRUE);
		
                $num = 31;//cal_days_in_month(CAL_GREGORIAN,$bulan,$tahun);
				$dblokal->query("DELETE FROM t_jc_".$sfa." WHERE yyyymm='".$tahun.$bulan."'");
                $getJourney = $this->getJourney($sfa, $bulan_asal, $tahun_asal,"count");
                foreach($getJourney as $r){
                        $data = array("parent_id"=>$r->parent_id,
                                      "yyyymm"=>$tahun.$bulan,
                                      "channel_id"=>$r->channel_id,
                                      "created_by"=>$this->session->userdata("username"),
                                      "created_on"=>date("Y-m-d H:i:s"));
                        for($i=1;$i<=$num;$i++){
                                $nama = "d".$i;
                                $data[$nama] = $r->{$nama};
                        }
                        $dblokal->insert("t_jc_".$sfa,$data);
						
						$ref_id = $this->db->insert_id();
						$this->mglobal->saveLog('JC_SFA_'."t_jc_".$sfa,'insert',$ref_id);
                }
        }
		
		function copyJourneySGS($sfa, $bulan_asal, $tahun_asal, $bulan, $tahun)
        {
                $dblokal = $this->load->database("default", TRUE);
				
                $dblokal->query("DELETE FROM t_jc_31 WHERE user_id='$sfa' AND yyyymm='".$tahun.$bulan."'");
				$num = 31;//cal_days_in_month(CAL_GREGORIAN,$bulan,$tahun);
                $getJourney = $this->getJourneySGS($sfa, $bulan_asal, $tahun_asal);
                foreach($getJourney as $r){
                        $data = array("user_id"=>$sfa,
										"yyyymm"=>$tahun.$bulan,
										"territory_id"=>$r->territory_id,
										"created_by"=>$this->session->userdata("username"),
										"created_on"=>date("Y-m-d H:i:s"));
								
						for($i=1;$i<=$num;$i++){
                                $nama = "d".$i;
                                $data[$nama] = $r->{$nama};
                        }
                        $dblokal->insert("t_jc_31",$data);
						
						$ref_id = $this->db->insert_id();
						$this->mglobal->saveLog('JC_SGS_'."t_jc_31",'insert',$ref_id);
                }
        }
        
        function cekAdaJourney($sfa,$bulan,$tahun){
                $dblokal  = $this->load->database("default", TRUE);
				$dblokal->where(array("yyyymm"=>$tahun.$bulan));
				$query = $dblokal->get("t_jc_".$sfa);
				
				$ada = "";
				foreach($query->result() as $r){
					for($i=1;$i<=31;$i++){
						$nf = "d".$i;
						if($r->{$nf} == 1){
							$ada .="a";
						}
					}
				}
				
				if($ada == ""){
					return TRUE;
				}else{
					return FALSE;
				}
		}
		
		function deleteJourney($sfa,$bulan,$tahun){
                $dblokal  = $this->load->database("default", TRUE);
				$dblokal->delete("t_jc_".$sfa,array("yyyymm"=>$tahun.$bulan));
				
				$this->mglobal->saveLog('JC_SFA_'."t_jc_".$sfa,'delete',$tahun.$bulan);
		}
        
        function getOutlet($sfa,$pair=""){
                $dblokal = $this->load->database("default", TRUE);
		        
                $dblokal->select('*');
                $dblokal->from("t_mtr_territory");
                $dblokal->join("t_mtr_outlet", "t_mtr_outlet.territory_id = t_mtr_territory.territory_id ");
                $dblokal->where(array('user_id'=> $sfa,'t_mtr_territory.istatus'=>1));
                //Patched By RAA 
                if ($pair<>""){
                    foreach($pair as $key=>$val){
                        $dblokal->where_not_in('t_mtr_outlet.outlet_id',$val['channel_id']);
                    }
                }
                //End Of Patch
                $dblokal->order_by("outlet_name", "asc"); 
                
                $query = $dblokal->get();
                
                if( $query->num_rows() > 0 ) {
                        return $query->result();
                } else {
                        return array();
                }
        }
        
        function getHoliday($bulan,$tahun){
                $dblokal = $this->load->database("default", TRUE);
		
                $dblokal->select('*');
                $dblokal->from("t_mtr_holiday");
                $dblokal->like('tanggal', $tahun.$bulan, 'after'); 
                $query = $dblokal->get();
                
                if( $query->num_rows() > 0 ) {
                        return $query->result();
                } else {
                        return array();
                }
        }
	
        function insertJourney($sfa, $bulan, $tahun)
        {
                $dblokal = $this->load->database("default", TRUE);
		
                $getOutlet = $this->getOutlet($sfa);
                foreach($getOutlet as $r){
                        $data = array("parent_id"=>$r->territory_id,
                                      "yyyymm"=>$tahun.$bulan,
                                      "channel_id"=>$r->outlet_id,
                                      "created_by"=>$this->session->userdata("username"),
                                      "created_on"=>date("Y-m-d H:i:s"));
                        $dblokal->insert("t_jc_".$sfa,$data);
						
						$ref_id = $this->db->insert_id();
						$this->mglobal->saveLog('JC_SFA_ADD_JOURNEY_'."t_jc_".$sfa,'insert',$ref_id);
                }
        }
        
        function insertJourney_ds($agent, $sfa, $minggu, $tahun)
        {
                $dblokal = $this->load->database("default", TRUE);
				for($i=0;$i<count($agent);$i++){
                        #periksa dulu, data sudah ada ?
                        $ada = $this->mglobal->showdata("channel_id","t_jc_".$sfa,array("channel_id"=>$agent[$i],"yyyymm"=>$tahun.$minggu),"dblokal");
                        if($ada==""){
                                $data = array("parent_id"=>$this->mglobal->showdata("territory_id","t_mtr_agent",array("agent_id"=>$agent[$i]),"dblokal"),
                                              "yyyymm"=>$tahun.$minggu,
                                              "channel_id"=>$agent[$i],
                                              "created_by"=>$this->session->userdata("username"),
                                              "created_on"=>date("Y-m-d H:i:s"));
                                $dblokal->insert("t_jc_".$sfa,$data);
								
								$ref_id = $this->db->insert_id();
								$this->mglobal->saveLog('JC_DS_JOURNEY_'."t_jc_".$sfa,'insert',$ref_id);
                        }
                }
        }
		
		function getTerId($id){
			$dblokal = $this->load->database("default", TRUE);
			$query = $dblokal->query("select f_get_territory_id_by_channel_id('$id') as hasil ");
			if($query->num_rows()>0){
				foreach($query->result() as $r){
					return $r->hasil;
				}
			}
		}
		function insertJourney_sgs($agent, $sfa, $bulan, $tahun)
        {
                $dblokal = $this->load->database("default", TRUE);
				$usergroup = $this->mglobal->getGroupId();;
				$nmtbl = "t_jc_31";//.$usergroup; // ambil group 
				for($i=0;$i<count($agent);$i++){
					
						$ter_id= $this->getTerId( $agent[$i] );
						#periksa dulu, data sudah ada ?
                        $ada = $this->mglobal->showdata("territory_id",$nmtbl,array("user_id"=>$sfa, "territory_id"=>$ter_id,"yyyymm"=>$tahun.$bulan),"dblokal");
                        if($ada==""){
								$data = array("user_id"=>$sfa,
                                              "yyyymm"=>$tahun.$bulan,
                                              "territory_id"=>$ter_id,
                                              "created_by"=>$this->session->userdata("username"),
                                              "created_on"=>date("Y-m-d H:i:s"));
                                $dblokal->insert($nmtbl,$data);
								
								$ref_id = $this->db->insert_id();
								$this->mglobal->saveLog('JC_SGS_JOURNEY_'.$nmtbl,'insert',$ref_id);
                        }
                }
        }
        
        function getJumlahKunjungan($sfa,$outlet,$bulan){
                $dblokal = $this->load->database("default", TRUE);
                $dblokal->where(array("channel_id"=>$outlet,"yyyymm"=>$bulan));
				$query = $dblokal->get("t_jc_".$sfa);
                
                $kunjungan = 0;
                foreach($query->result() as $r){
                        $kunjungan = $r->d1 + $r->d2 + $r->d3 + $r->d4 + $r->d5 + $r->d6 + $r->d7 + $r->d8 + $r->d9 + $r->d10
                                + $r->d11 + $r->d12 + $r->d13 + $r->d14 + $r->d15 + $r->d16 + $r->d17 + $r->d18 + $r->d19 + $r->d20
                                + $r->d21 + $r->d22 + $r->d23 + $r->d24 + $r->d25 + $r->d26 + $r->d27 + $r->d28 + $r->d29 + $r->d30 + $r->d31;
                }
                return $kunjungan;
        }
		
		function getJumlahKunjunganSGS($sfa,$bulan){
                $dblokal = $this->load->database("default", TRUE);
                $dblokal->where(array("user_id"=>$sfa,"yyyymm"=>$bulan));
		$query = $dblokal->get("t_jc_31");
                
                $kunjungan = 0;
                foreach($query->result() as $r){
                        $kunjungan += $r->d1 + $r->d2 + $r->d3 + $r->d4 + $r->d5 + $r->d6 + $r->d7 + $r->d8 + $r->d9 + $r->d10
                                + $r->d11 + $r->d12 + $r->d13 + $r->d14 + $r->d15 + $r->d16 + $r->d17 + $r->d18 + $r->d19 + $r->d20
                                + $r->d21 + $r->d22 + $r->d23 + $r->d24 + $r->d25 + $r->d26 + $r->d27 + $r->d28 + $r->d29 + $r->d30 + $r->d31;
                }
                return $kunjungan;
        }
        
        function saveJourney($sfa, $outlet, $tgl, $bulan, $nilai)
        {
                $dblokal = $this->load->database("default", TRUE);
                $data = array("d".$tgl=>$nilai);
		
				$dblokal->where(array("channel_id"=>$outlet,"yyyymm"=>$bulan));
				$dblokal->update("t_jc_".$sfa,$data);
				
				//$ref_id = $this->db->insert_id();
				//$this->mglobal->saveLog('JC_DS_JOURNEY_'."t_jc_".$sfa,'update',$outlet);
        }
        
        function saveJourney_ds($sfa, $outlet, $tgl, $minggu, $nilai)
        {
                $dblokal = $this->load->database("default", TRUE);
                $data = array("d".$tgl=>$nilai);
				
				$minggusama = $this->input->post('minggusama');
				if($minggusama <> 'sama'){
					$minggu = $minggusama;
				}
				
				//cek data ada/ tdk
				$ada = $this->mglobal->showdata('channel_id',"t_jc_".$sfa,array("channel_id"=>$outlet,"yyyymm"=>$minggu),"dblokal");
				if($ada == ""){ //data tidak ada, harus diinput dulu
					$data["channel_id"]=	$outlet;
					$data["yyyymm"]=		$minggu;
					$data["created_by"]=	$this->session->userdata('username');
					$data['parent_id'] = 	$this->mglobal->showdata("territory_id","t_mtr_agent",array('agent_id'=>$outlet),"dblokal");
					$dblokal->insert("t_jc_".$sfa,$data);
				}else{
					$data["updated_by"]=$this->session->userdata('username');
					$dblokal->where(array("channel_id"=>$outlet,"yyyymm"=>$minggu));
					$dblokal->update("t_jc_".$sfa,$data);
				}
				//$ref_id = $this->db->insert_id();
				//$this->mglobal->saveLog('JC_DS_JOURNEY_'."t_jc_".$sfa,'insert',$ref_id);
        }
		
		function saveJourney_sgs($sfa, $outlet, $tgl, $bulan, $nilai)
        {
                $dblokal = $this->load->database("default", TRUE);
                $data = array("d".$tgl=>$nilai);
				
				$terid = $this->getTerId($outlet);
				$dblokal->where(array("territory_id"=>$terid,"yyyymm"=>$bulan, "user_id" => $sfa));
				$dblokal->update("t_jc_31",$data);
				
				//$ref_id = $this->db->insert_id();
				//$this->mglobal->saveLog('JC_DS_JOURNEY_'."t_jc_".$sfa,'update',$outlet);
        }
        
        public function getSFA()
	{
                $dbconn  = $this->load->database("default", TRUE);
		$userlogin = $this->session->userdata('username');
		
                $sql = ("SELECT * from t_mtr_user WHERE istatus=1 AND user_id IN(
                        SELECT user_id FROM t_mtr_territory
                        WHERE parent_id= f_get_cluster('$userlogin')
                        AND territory_type_id = 5)
                        ORDER BY user_name");
				
				//$sql = ("SELECT * from t_mtr_user WHERE istatus=1 AND user_name='Smart Fren Ambasador 2' ORDER BY user_name");
		$query = $dbconn->query($sql);
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return array();
		}
	}
   
	public function getDS($status="")
	{
		$dbconn  = $this->load->database("default", TRUE);
		$userlogin = $this->session->userdata('username');
		$sql = ("SELECT * from t_mtr_user WHERE istatus=1 AND user_id IN(
                        
                        SELECT user_id FROM t_mtr_territory, t_mtr_agent
                                         WHERE t_mtr_territory.territory_id = t_mtr_agent.territory_id
										 AND t_mtr_territory.parent_id= f_get_cluster('$userlogin')
                                         AND t_mtr_territory.territory_type_id = 6
										
										 
										 
                        )
                        ORDER BY user_name");
     //AND t_mtr_agent.agent_status = '".$status."'
	 // AND t_mtr_agent.agent_status = '".$status."'
		$query = $dbconn->query($sql);
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return array();
		}
	}
        
		function getAgentBawah($id){
			#fungdi u/mengambil ter_id
			$dblokal = $this->load->database("default", TRUE);
			
			$pid = $this->mglobal->showdata("territory_id","t_mtr_territory",array('user_id'=>$id),"dblokal");
			$query = $dblokal->query("SELECT territory_id FROM t_mtr_territory,t_mtr_agent
									 WHERE t_mtr_territory.territory_id = t_mtr_agent.territory_id
									 AND t_mtr_territory.parent_id = '". $pid ."'");
			$hasil = array();
			if($query->num_rows()>0){
				foreach($query as $r){
					$hasil[] = $r->territory_id;
				}
			}
			return $hasil;
		}
        function getAgent($id="",$tipe_agent="", $sfa=""){
                $dblokal = $this->load->database("default", TRUE);
		
                $dblokal->select('*');
                $dblokal->from("t_mtr_agent")->where('istatus',1);
				
				$sql = "SELECT t_mtr_agent.* FROM t_mtr_agent,t_mtr_territory
						WHERE t_mtr_agent.territory_id = t_mtr_territory.territory_id
							AND t_mtr_agent.istatus=1";
				
				//kalo id=1 => yg dimaintenance oleh DS
				if($id == 1){
					$sql .=" AND t_mtr_territory.parent_id = f_get_cluster('$sfa') AND t_mtr_territory.user_id='$sfa'";
				}else{
					$sql .=" AND t_mtr_territory.parent_id = f_get_cluster('".$this->session->userdata('username')."')";
				}
				if($id > 0){
					///filter berdasarkan tipe penjualan
					$sql .=" AND agent_selling_type = ".$id ;
				}
				
				if($tipe_agent<>""){
					///filter berdasarkan tipe agent
					$sql .=" AND agent_status = '$tipe_agent'" ;
				}
				$query = $dblokal->query( $sql );
				
                if( $query->num_rows() > 0 ) {
                        return $query;
                } else {
                        return array();
                }
        }
	
        function createTabel($nmTabel){
                
                $dblokal = $this->load->database("default", TRUE);
                if(!$dblokal->table_exists($nmTabel)){
                        $this->load->dbforge();
                        $fields = array(
                                        'jc_id' => array(
                                                        'type' => 'INT',
                                                        'constraint' => 32, 
                                                        'unsigned' => TRUE,
                                                        'auto_increment' => TRUE
                                                ),
                                        'parent_id' => array(
                                                        'type' => 'INT',
                                                        'constraint' => 32
                                                ),
                                        'yyyymm' => array(
                                                        'type' => 'varchar',
                                                        'constraint' => 6
                                                ),
                                        'channel_id' => array(
                                                        'type' => 'varchar',
                                                        'constraint' => 20
                                                ),
                                        'd1' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd2' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd3' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd4' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd5' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd6' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd7' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd8' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd9' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd10' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd11' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd12' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd13' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd14' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd15' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd16' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd17' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd18' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd19' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd20' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd21' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd22' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd23' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd24' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd25' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd26' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd27' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd28' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd29' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd30' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd31' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'created_by' => array(
                                                        'type' => 'varchar',
                                                        'constraint' => 30,
                                                        'NULL' => TRUE
                                                ),
                                        'created_on' => array(
                                                        'type' => 'timestamp', 'default' => 'now()'
                                                ),
                                        'updated_by' => array(
                                                        'type' => 'varchar',
                                                        'constraint' => 30, 'NULL' => TRUE
                                                ),
                                        'updated_on' => array(
                                                        'type' => 'timestamp', 'default' => 'now()'
                                                )
                                );
                        $this->dbforge->add_field($fields);
                        $this->dbforge->add_key('jc_id', TRUE);
                        $this->dbforge->create_table($nmTabel);
                }
        }
		
		function createTabelDS($nmTabel){
                
                $dblokal = $this->load->database("default", TRUE);
				//anggwa@gmail.com 20120611 tabel standarisasi sampai 31
				//$query = $dblokal->query("DROP TABLE $nmTabel");
				$this->createTabel($nmTabel);
				
                // if(!$dblokal->table_exists($nmTabel)){
                        // $this->load->dbforge();
                        // $fields = array(
                                        // 'jc_id' => array(
                                                        // 'type' => 'INT',
                                                        // 'constraint' => 32, 
                                                        // 'unsigned' => TRUE,
                                                        // 'auto_increment' => TRUE
                                                // ),
                                        // 'parent_id' => array(
                                                        // 'type' => 'INT',
                                                        // 'constraint' => 32
                                                // ),
                                        // 'yyyymm' => array(
                                                        // 'type' => 'varchar',
                                                        // 'constraint' => 6
                                                // ),
                                        // 'channel_id' => array(
                                                        // 'type' => 'varchar',
                                                        // 'constraint' => 20
                                                // ),
                                        // 'd1' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        // 'd2' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        // 'd3' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        // 'd4' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        // 'd5' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        // 'd6' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        // 'd7' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        
                                        // 'created_by' => array(
                                                        // 'type' => 'varchar',
                                                        // 'constraint' => 30,
                                                        // 'NULL' => TRUE
                                                // ),
                                        // 'created_on' => array(
                                                        // 'type' => 'timestamp', 'default' => 'now()'
                                                // ),
                                        // 'updated_by' => array(
                                                        // 'type' => 'varchar',
                                                        // 'constraint' => 30, 'NULL' => TRUE
                                                // ),
                                        // 'updated_on' => array(
                                                        // 'type' => 'timestamp', 'default' => 'now()'
                                                // )
                                // );
                        // $this->dbforge->add_field($fields);
                        // $this->dbforge->add_key('jc_id', TRUE);
                        // $this->dbforge->create_table($nmTabel);
                //}
        }
        
        ######################
        ### Get JSON DATA
        
        function getWeek_json()
        {    
                $k=0;  
                $this->idnya ++;
		
		$this->result .='[';
		for($b=1;$b<=54;$b++){
			$link = base_url() ."index.php/journey_cycle/direct_sales/". $b;
			
			$this->result .='{"week":"Week '. $b .'",
                                                "mulai":"'. $this->mglobal->getDays($b,2012) .'",
                                                "selesai":"2",
						"link":"'. $link .'"}';
			if($b!=54) $this->result .=',';
		}
		$this->result .="]";
                return $this->result;
	}
        
	function getAgent_json( $id="", $agentstatus="", $sfa="" )
    {    
		$k=0;  
		$this->idnya ++;
		$getAgent = $this->getAgent($id,$agentstatus,$sfa);
		$this->result .='[';
		if(count($getAgent) >0){
			
			foreach($getAgent->result() as $r){
				$k++;
                //edited by: Alfin Akhret
                //escaping newline character
				$this->result .='{"tid":"'. preg_replace("#\n|\r|\r\n#", "", $r->territory_id) .'",
									"id":"'. preg_replace("#\n|\r|\r\n#", "", $r->agent_id) .'",
									"name":"'. preg_replace("#\n|\r|\r\n#", "", $r->agent_name) .'",
									"city":"'. preg_replace("#\n|\r|\r\n#", "", $r->agent_city) .'"}';
				if($k!=$getAgent->num_rows) $this->result .=',';
			}
		}
		$this->result .="]";
                return $this->result;
	}
	
	function getToko_json( $jenis=1 )
    {
		$dblokal = $this->load->database("default", TRUE);
		$k=0;  
		$this->idnya ++;
		$userLogin = $this->session->userdata('username');
		$sql="";
        //$territory_id = $this->db->select('f_get_cluster('.$userLogin.')');
        //$parent_id =$territory_id->row();
		if($jenis==1){
			// tt.parent_id = f_get_cluster('$userLogin') AND
			// $sql="SELECT outlet_id as idnya, upper(outlet_name) as nama, CONCAT_WS(' ',address,city) as alamat
			// 		FROM t_mtr_outlet oot
			// 		JOIN t_mtr_territory tt
			// 			ON oot.territory_id = tt.territory_id
			// 		WHERE oot.istatus = 1 and tt.parent_id = f_get_cluster('$userLogin') 
			// 		ORDER BY outlet_name ASC LIMIT 30;";

            //edited : Alfin akhret
            $sql="SELECT outlet_id as idnya, upper(outlet_name) as nama, CONCAT_WS(' ',address,city) as alamat
                    FROM t_mtr_outlet oot
                    JOIN t_mtr_territory tt
                        ON oot.territory_id = tt.territory_id
                    JOIN t_mtr_channel_program tcp
                        ON oot.outlet_id = tcp.channel_id 
                    WHERE oot.istatus = 1 and tt.parent_id = f_get_cluster('$userLogin')
                    AND tcp.program_id = '27'
                    -- ORDER BY outlet_name ASC LIMIT 30
                    ORDER BY outlet_name ASC ;";

		}else{
			$sql="SELECT agent_id as idnya, upper(agent_name) as nama, CONCAT_WS(' ',agent_address,agent_city) as alamat
					FROM t_mtr_agent agn
					JOIN t_mtr_territory tt
						ON agn.territory_id = tt.territory_id
					WHERE agn.istatus = 1
						AND agn.agent_selling_type = 2
                    and tt.parent_id = f_get_cluster('$userLogin') 
                    -- ORDER BY agent_name ASC LIMIT 30
					ORDER BY agent_name ASC ;";
		}
		$query = $dblokal->query($sql);
		
		$this->result .='[';
		if($query->num_rows() >0){
			
			foreach($query->result() as $r){
				$k++;
                // edited by: alfin akhret
                // filter newline character
				$this->result .='{"tid":"'. preg_replace("#\n|\r|\r\n#", "", $r->idnya) .'",
									"id":"'. preg_replace("#\n|\r|\r\n#", "", $r->idnya) .'",
									"name":"'. preg_replace("#\n|\r|\r\n#", "", $r->nama) .'",
									"city":"'. preg_replace("#\n|\r|\r\n#", "", $r->alamat) .'"}';
				if($k!=$query->num_rows) $this->result .=',';
			}
		}
		$this->result .="]";
        return $this->result;
	}
	
	function createTabelJCManual($nmTabel="log_jc_manual"){
                
                $dblokal = $this->load->database("default", TRUE);
                if(!$dblokal->table_exists($nmTabel)){
                        $this->load->dbforge();
                        $fields = array(
                                        'jc_id' => array(
                                                        'type' => 'INT',
                                                        'constraint' => 32, 
                                                        'unsigned' => TRUE,
                                                        'auto_increment' => TRUE
                                                ),
                                        'parent_id' => array(
                                                        'type' => 'INT',
                                                        'constraint' => 32
                                                ),
                                        'yyyymm' => array(
                                                        'type' => 'varchar',
                                                        'constraint' => 6
                                                ),
                                        'channel_id' => array(
                                                        'type' => 'varchar',
                                                        'constraint' => 20
                                                ),
                                        'd1' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd2' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd3' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd4' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd5' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd6' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd7' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd8' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd9' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd10' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd11' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd12' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd13' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd14' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd15' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd16' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd17' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd18' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd19' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd20' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd21' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd22' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd23' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd24' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd25' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd26' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd27' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd28' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd29' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd30' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'd31' => array('type' => 'INT', 'constraint' => 16, 'default' => 0),
                                        'jenis' => array(
                                                        'type' => 'varchar',
                                                        'constraint' => 30, 'NULL' => TRUE
                                                ),
										'data_valid' => array(
                                                        'type' => 'varchar',
                                                        'constraint' => 30, 'NULL' => TRUE
                                                ),
										'data_dobel' => array(
                                                        'type' => 'varchar',
                                                        'constraint' => 30, 'NULL' => TRUE
                                                ),
										'created_on' => array(
                                                        'type' => 'timestamp', 'default' => 'now()'
                                                )
                                );
                        $this->dbforge->add_field($fields);
                        $this->dbforge->add_key('jc_id', TRUE);
                        $this->dbforge->create_table($nmTabel);
                }
        }
		
		function cekNIK($data){
			$dblokal = $this->load->database("default", TRUE);
			$q = $dblokal->query("select * from t_mtr_user where nik='$data'");
			return $q->num_rows();
		}
		
		function getNIK($data){
			$dblokal = $this->load->database("default", TRUE);
			$q = $dblokal->query("select * from t_mtr_user where nik='$data'");
			if($q->num_rows()>0){
				foreach($q->result() as $r){
					return $r->reporting_to;
				}
			}else{
				return "";
			}
		}
		
		function addjcmanual($data){
			$dblokal = $this->load->database("default", TRUE);
			$dblokal->insert("log_jc_manual",$data);
		}
		
		function addjcmanualsfa($data,$tabel){
		
			$dblokal = $this->load->database("default", TRUE);
			
			//periksa ada tabel?
			//periksa ada data?
			
			//20120611 anggwa@gmail.com | periksa metadata (postgres compatible only)
			$namatabel = "t_jc_".$tabel;
					//20120611 anggwa@gmail.com | periksa tabel exist ngga
			$q1 = $dblokal->query("SELECT * FROM information_schema.tables WHERE table_name = '$namatabel'");
			if ($q1->num_rows() > 0) {
					//20120611 anggwa@gmail.com | periksa kolom d8
				$q2 = $dblokal->query("SELECT * FROM information_schema.columns WHERE table_name = '$namatabel' AND column_name = 'd8'");
					if ($q2->num_rows() == 0) {
					//20120611 anggwa@gmail.com | drop table agar create table semua seragam d31, avoid DS pindah ke SFA
						$dblokal->query("DROP TABLE $namatabel;");
					}
			}
			$this->createTabel("t_jc_".$tabel);
			
			//delete data jika ada
			$dblokal->delete("t_jc_".$tabel,array('yyyymm' => $data['yyyymm'], 'channel_id' => $data['channel_id']));
			
			$data['created_by'] = $this->session->userdata('username');
			$dblokal->insert('t_jc_'.$tabel,$data);
			
			/*if($ada <> ""){
				$data['data_dobel'] = "ya";
				//$this->addjcmanual($data);
			}
			//else{
				$data['created_by'] = $this->session->userdata('username');
				//$dblokal->insert('0t_jc_'.$tabel,$data);
			//}*/
		}
		
		/////////////////////////////////////////////
		function getTipePenjualanSGS(){
            $dblokal=$this->load->database("default",true);
			$query=$dblokal->query("SELECT lookup_value as mvalue, lookup_desc as mdisplay from t_mtr_lookup where lookup_key = 'sgs_selling_type';");
			return $query->result();
		}
		
		function deletedobel(){
			$dblokal=$this->load->database("default",true);
			$tables = $dblokal->list_tables();
			foreach ($tables as $table)
			{
				if($table <> "t_jc_31"){
					$mystring = 'abc';
					$findme   = 't_jc_';
					$pos = strpos($table, $findme);
					
					if ($pos === false) {
						
					} else {
						//cek data di bulan agustus, group by id, kalo valuenya 2, delete yang kosong
						$query = $dblokal->query("SELECT channel_id, count(channel_id) as jml FROM $table WHERE yyyymm='201208' GROUP BY channel_id");
						foreach($query->result() as $r){
							//cek nilainya 2
							if($r->jml >1){
								//get jc_id yang masih kosong, kemudian delete
								$idkosong = $this->getKunjKosong($table, $r->channel_id);
								echo $table ." :: ". $idkosong."<br>";
								$dblokal->query("DELETE FROM $table WHERE jc_id='$idkosong'");
							}
						}
					}
				}
			}
		}
		
		function getKunjKosong($sfa,$outlet){
                $dblokal = $this->load->database("default", TRUE);
                $dblokal->where(array("channel_id"=>$outlet,
									  "yyyymm"=>"201208",
									  "d1 !="=>"1","d2 !="=>"1","d3 !="=>"1","d4 !="=>"1","d5 !="=>"1","d6 !="=>"1","d7 !="=>"1","d8 !="=>"1","d9 !="=>"1","d10 !="=>"1",
									  "d11 !="=>"1","d12 !="=>"1","d13 !="=>"1","d14 !="=>"1","d15 !="=>"1","d16 !="=>"1","d17 !="=>"1","d18 !="=>"1","d19 !="=>"1","d20 !="=>"1",
									  "d21 !="=>"1","d22 !="=>"1","d23 !="=>"1","d24 !="=>"1","d25 !="=>"1","d26 !="=>"1","d27 !="=>"1","d28 !="=>"1","d29 !="=>"1","d30 !="=>"1","d31 !="=>"1"
									  ));
				$query = $dblokal->get($sfa);
                
                $hasil = "";
                foreach($query->result() as $r){
                    $hasil = $r->jc_id;
                }
                return $hasil;
        }

    ## patch pemindahan penglola partner ###########################
    ## masukkan outlet yang sudah dipindahkan ke journey cycle #####    
    ## yaddis@nutech-integrasi.com #################################
    ## 09/10/2012 ##################################################
    private function _patch($sfa, $bulan, $tahun)
    {
        // select oo.outlet_id, tt.territory_id
        // from t_mtr_outlet oo
        // join t_mtr_territory tt on oo.territory_id = tt.territory_id
        // where  tt.user_id = '90001715' and oo.outlet_id not in (select channel_id from t_jc_90001715 where  yyyymm = '201209' );
        $this->db->select('channel_id');
        $record = $this->db->get_where("t_jc_$sfa",array("yyyymm" => $tahun.$bulan))->result_array();

        foreach ($record as $key => $value) {
            $jc_outlet_id[] = $value["channel_id"];
        }

        $this->db->select('oo.outlet_id, tt.territory_id');
        $this->db->join("t_mtr_territory tt","oo.territory_id = tt.territory_id");
        $this->db->where("tt.user_id", $sfa );
        $this->db->where_not_in("oo.outlet_id", $jc_outlet_id );
        $query = $this->db->get("t_mtr_outlet oo")->result_array();

        if (count($query) == 0 OR is_null($query)) {
            return;
        }

        foreach ($query as $key) {
            $value = array(
                    'parent_id' => $key["territory_id"],
                    'channel_id' => $key["outlet_id"],
                    'yyyymm' => $tahun.$bulan,
                    'created_by' => $this->session->userdata("username"),
                );
            for ($i=1; $i <= 31; $i++) { 
                $value['d'.$i] = 0;
            }
            // print_r($value); die();
            $this->db->insert("t_jc_$sfa", $value);
        }
        
        $this->mglobal->saveLog('journey cycle t_jc_'.$sfa, 'yaddi-patch '.count($query).' outlets', $sfa);
    }
}
