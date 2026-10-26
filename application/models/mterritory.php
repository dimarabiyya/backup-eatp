<?php
class Mterritory extends CI_Model {

	function Mterritory(){
		parent::__construct();
                $this->setVars();
        }
        
        function setVars()
        {
            $this->result = '';
            $this->idnya = 0;
        }
	
	function saveTerritory($proses,$id,$icon)
    {
        $dblokal = $this->load->database("default", TRUE); 
		$name 	 = $this->input->post("name");
		$parent  = $this->input->post("parent");
		$user 	 = $this->input->post("user");
		$territory_type = $this->input->post("territory_type");
		$territory_code = $this->input->post("territory_code");
		$territory_code2= $this->input->post("territory_code2");
		
		$data = array("territory_name" 	=>$name,
			      "parent_id" 	=>$parent,
			      'updated_by'=>$this->session->userdata('username'));
		if($user<>"") $data["user_id"]=$user;
		if($proses == "update"){
			
			//ambil repto lama & yg baru
			$rlama = $this->mglobal->showdata("user_id","t_mtr_territory",array('territory_id'=>$id),"dblokal");
			$rbaru = $user;
			
			$data["territory_type_id"]=$territory_type;
			$dblokal->where(array("territory_id"=>$id));
			$dblokal->update("t_mtr_territory",$data);
			
			if($icon<>""){
				$dblokal->where(array("territory_type_id"=>$territory_type));
				$dblokal->update("t_mtr_territory_type",array("territory_type_icon"=>$icon));
			}
			
			$dblokal->where(array("territory_id"=>$id));
			$dblokal->update("t_mtr_territory_code",array("territory_code"=>$territory_code,
														  "territory_code_v2"=>$territory_code2,
														  "updated_by"=>$this->session->userdata('username'),
														  "updated_on"=>date('Y-m-d H:i:s')
														  )
							 );
			
			/*
				Kalo maintenance_by terr di-update,pengaruh ke user,
				user yang ada di bawahnya diupdate reportingto-nya
			*/
			if($rlama<>"" && $rlama<>$rbaru){
				$dblokal->where(array("reporting_to"=>$rlama));
				$dblokal->update("t_mtr_user",array("reporting_to"=>$rbaru));
			}
			$territory = $this->db->get("f_get_territory_path('$id')")->row()->f_get_territory_path;
			$this->mglobal->saveLog('territory',"update ($territory)",$id);
		}else{
			$data["territory_type_id"]=$territory_type+1;
			$data['created_by']=$this->session->userdata('username');
			
			$dblokal->insert("t_mtr_territory",$data);
			$dblokal->insert("t_mtr_territory_code",array('territory_code'=>$territory_code,
								      'territory_id'=>$dblokal->insert_id(),
								      'istatus'=>1,
								      'created_by'=>$this->session->userdata('username')));
			$ref_id = $this->db->insert_id();
			$territory = $this->db->get("f_get_territory_path('$id')")->row()->f_get_territory_path;
			$this->mglobal->saveLog('territory',"insert ($territory)",$ref_id);
			#update di t_mtr_user
			$report = $this->mglobal->showdata("user_id","t_mtr_territory",array('territory_id'=>$parent),"dblokal");
			if($report<>""){
				$dblokal->where(array("user_id"=>$user));
				$dblokal->update("t_mtr_user",array("reporting_to"=>$report));
			}
		}
    }

    // ganti order by territory type
	   function getTerritory($pid = 0, $type="", $limit=0)//$limit u/ menentukan ter_id mulai dr mana
        {    
        	$dblokal  = $this->load->database("default", TRUE);
        	$and = "";
        	$order = 'territory_name ASC' ;
              if ($type <>  ""){
              	$and = 'and territory_type_id = '.$type.' and territory_id > '.$limit.' ' ;
              }

              $cast = "cast(SUBSTRING(territory_name, 'Y*([0-9]{1,3})' ) as INTEGER)";
              $row = $this->getTerritoryPid($pid);
              if ($pid <>""){
            	$row = $this->getTerritoryPid($pid);
            	// $r = echo $row->territory_type_id ;
            	if($row == 2){																		
                   $order = $cast;
            	}else{
            		$order = 'territory_name asc' ;
            	}
               }
            $sql = 'select * from t_mtr_territory where parent_id = '.$pid.'
            		AND istatus = 1
            		'.$and.' 
            		ORDER BY '.$order.' limit 50' ;
           	$query = $dblokal->query($sql);

           	return $query;

	    }

 
	function getTerritoryPid($pid){
			if ($pid <>""){
			$dblokal  = $this->load->database("default", TRUE);
            	
					$r = $dblokal->query("select territory_type_id from t_mtr_territory where territory_id = ".$pid." ");
            		return  $r->row()->territory_type_id;
            	}
            		// return $row;
	}
		function searchTerritory($name="", $pid = 0, $maintenance="", $limit=50)//$limit u/ menentukan ter_id mulai dr mana
        {    
                $dblokal  = $this->load->database("default", TRUE);
                $dblokal->order_by("territory_name", "ASC");
                
				if($name <> "") {
					$dblokal->like("territory_name", $maintenance);
				}
				if($maintenance <> "") {
					$dblokal->where(array("user_id"=> $maintenance));
				}
				if($pid <> "") {
					$dblokal->where("parent_id", $pid);
				}
				
				#$dblokal->where(array("territory_id >" => $limit));
				$dblokal->limit(50);
				
                $query = $dblokal->get("t_mtr_territory");
                return $query;
	}
	
	function deleteTerritory($id)
	{    
		//$dblokal = $this->load->database("default", TRUE);
		
		//$dblokal->update("t_mtr_territory", array("territory_id"=>$id, 'istatus'=>0));
		
		// $dblokal->where("istatus",1);
		// $dblokal->update("t_mtr_territory",array('istatus'=>0));
		
		$this->db->set('istatus', 0);
		$this->db->where('territory_id', $id);
		$this->db->update('t_mtr_territory');
		
		$territory = $this->db->get("f_get_territory_path('$id')")->row()->f_get_territory_path;
		$this->mglobal->saveLog('territory',"delete ($territory)",$id);
	}
	
	function getTerritoryByType($type)
	{    
		$dblokal  = $this->load->database("default", TRUE);
		$dblokal->where("territory_type_id", $type);
		$query = $dblokal->get("t_mtr_territory");
		return $query;
	}
	
	function getTerritoryById($id)
	{    
		$dblokal  = $this->load->database("default", TRUE);
		$dblokal->where("territory_id", $id);
		$query = $dblokal->get("t_mtr_territory");
		return $query->result();
	}
        
        function getTerritoryChart($id = 0,$p=0)
        {
		$p++;
                $query = $this->getTerritory($id);
                if($query->num_rows()>0){
                        $this->result .="<ul>";
                        foreach($query->result() as $r){
                                $this->result .="<li><b>". $r->territory_name ."</b><br>". $r->user_id ;
				
				if($p == 3){
					#cek punya outlet ? 
					$cek_o = $this->getTerritory($r->territory_id,5)->num_rows();
					$cek_a = $this->getTerritory($r->territory_id,6)->num_rows();
					
					if($cek_o > 0){
						$this->result .="<ul><li><b>Outlet</b>" ;
						$this->getTerritoryChartLast($r->territory_id,5);
						$this->result .="</li></ul>";
					}
					
					if($cek_a >0){
						$this->result .="<ul><li><b>Outlet</b>" ;
						$this->getTerritoryChartLast($r->territory_id,6);
						$this->result .="</li></ul>";
					}
				}else{
					$this->getTerritoryChart($r->territory_id,$p);
				}
				$this->result .="</li>";
                        }
                        $this->result .="</ul>";
                }
                return $this->result;
	}
	
	function getTerritoryChartLast($id,$type)
        {
                $query = $this->getTerritory($id,$type);
                if($query->num_rows()>0){
                        $this->result .="<ul>";
                        foreach($query->result() as $r){
                                $this->result .="<li><b>". $r->territory_name ."</b><br>". $r->user_id ;
                                $this->result .="</li>";
                        }
                        $this->result .="</ul>";
                }
                return $this->result;
	}
        
	function getTerritoryTree($id = 0, $tipe = '', $limit = 0)
        {   
        	//Get user group role 
        	$user_group_id = $this->session->userdata('group');
    		$row  = $this->getButtons(8, $user_group_id);
    		//-----------------------------------------------------//

                $query = $this->getTerritory($id,$tipe,$limit);
                $k=0;
		$jenis = $this->mglobal->showdata('territory_type_id','t_mtr_territory',array('territory_id'=>$id),'dblokal');
		if($jenis == 3 && $tipe == ''){
			$cek_o = $this->getTerritory($id,5)->num_rows();
			$cek_a = $this->getTerritory($id,6)->num_rows();
			
			$this->result .= '[{"id"	: "o'.$id.'",
					   "tipe"	: 5,
					   "ter_id" 	: '. $id .',
					   "limit"	: "",
					   "name"	: "Network",
					   "size"	: "", "date":"",
					   "iconCls"	: "icon-territory-outlet"';
			if($cek_o >0){
				$this->result .= ',"state":"closed"';
			}
			$this->result .='},';
			$this->result .= '{"id"		: "a'.$id.'",
					   "tipe"	: 6,
					   "ter_id"	: '. $id .',
					   "limit"	: "",
					   "name"	: "Operational",
					   "size"	: "", "date":"",
					   "iconCls"	: "icon-territory-agent"';
			if($cek_a >0){
				$this->result .= ',"state":"closed"';
			}
			$this->result .='}]';
			
		}else{
			$jum = $query->num_rows();
			$this->idnya ++; $lm=''; $tp='';
			if($query->num_rows()>0){
				$this->result .='[';
				foreach($query->result() as $r){
					
					#cek punya children gak ?
					$cek = $this->getTerritory($r->territory_id);$k++;
					$jml = $cek->num_rows();
					
					$link="";
					if($r->territory_type_id==1){

						$link= ($row['menu_add'] == 1) ? "<a href='".site_url("/territory/tree/add/". $r->territory_id) ."'>Add</a>" : "";
					}
					elseif($r->territory_type_id == 2){
					
						$link = ($row['menu_add'] == 1)     ? "<a href='".site_url("/territory/tree/add/". $r->territory_id) ."'>Add</a> | " : "";
						$link .= ($row['menu_edit'] == 1)   ? "  <a href='".site_url("/territory/tree/update/". $r->territory_id) ."'>Edit</a> | " :"";
						$link .= ($row['menu_delete'] == 1) ? "  <a href='#' onclick=deleterow('". $this->idnya ."','". $r->territory_id ."','". $jml ."') >Delete</a>" : "";
					
					}
					elseif($r->territory_type_id == 3){
						$link .= ($row['menu_edit'] == 1)   ? "  <a href='".site_url("/territory/tree/update/". $r->territory_id) ."'>Edit</a> | " :"";
						$link .= ($row['menu_delete'] == 1) ? "  <a href='#' onclick=deleterow('". $this->idnya ."','". $r->territory_id ."','". $jml ."') >Delete</a>" : "";
					}
					
					$tpe = "";
					if($jenis>=3){
						$tpe = $r->territory_id ;
					}
					$this->result .='{"id"		: '. $r->territory_id .',
							"name"		: "'. $r->territory_name .'",
							"size"		: "'. $this->mglobal->showdata('user_name','t_mtr_user',array('user_id'=>$r->user_id),'dblokal') .'",
							"date"		: "'. $link .'",
							"ter_id"	: '. $r->territory_id .',
							"tipe"		: "'. $tpe .'",
							"limit"		: "",
							"iconCls"	: "icon-territory-'. $r->territory_type_id .'"';
					if($cek->num_rows() >0){
						$this->result .= ',"state":"closed"';
					}
					$this->result .='}';
					if($k!=$jum) $this->result .=',';
					$lm = $r->territory_id;
					$tp = $r->territory_type_id;
				}
				
				if($jenis >= 3){
					$ada_data = $this->mglobal->countData('territory_type_id','t_mtr_territory',array('parent_id'=>$id,'territory_id >'=>$limit,'territory_type_id'=>$tipe),'dblokal');
					if($ada_data > 50){
						$this->result .=',{"id"	: "showdata",
							"name"	: "<font color=red align=center onclick=javascript:append(\'showdata\','.$id.','. $tp .','. ($lm) .')><u>--- Show More Data ---</u></font>",
							"ter_id": '. $id .',
							"size"  : "",
							"date"  : "",
							"tipe"  : "",
							"iconCls": "icon-territory-more"}';
					}
				}
				$this->result .="]";
			}
		}
                return $this->result;
	}
	
        function getTerritoryTree_back($id = 0,$p=0)
        {    
                $query = $this->getTerritory($id);
                $k=0;  $p =$p+1;
                $jum = $query->num_rows();
                $this->idnya ++;
		
		if($query->num_rows()>0){
                        $this->result .='[';
                        foreach($query->result() as $r){
                                #cek punya children gak ?
				$cek = $this->getTerritory($r->territory_id);$k++;
				$jml = $cek->num_rows();
				
				$link="<a href='".site_url("/territory/tree/add/". $r->territory_id) ."'>Add</a> | <a href='".site_url("/territory/tree/update/". $r->territory_id) ."'>Update</a> | <a href='#' onclick=deleterow('". $this->idnya ."','". $r->territory_id ."','". $jml ."') >Delete</a>";
				if($p==1){
					$link="<a href='".site_url("/territory/tree/add/". $r->territory_id) ."'>Add</a> ";
				}elseif($p == 3){
					$link="<a href='".site_url("/territory/tree/update/". $r->territory_id) ."'>Update</a> | <a href='#' onclick=deleterow('". $this->idnya ."','". $r->territory_id ."','". $jml ."') >Delete</a>";
				}
				$this->result .='{"id":'. $this->idnya .',
                                                "name":"'. $r->territory_name .'",
                                                "size":"'. $r->user_id .'",
                                                "date":"'. $link .'",
						"iconCls":"icon-territory-'. $p .'"';
                                if($r->territory_name == "JABODETABEK"){
					$this->result .= ',"state":"closed"';
				}
				if($cek->num_rows()>0){
					
					if($p == 3){
						#cek punya outlet ? 
						
						$cek_o = $this->getTerritory($r->territory_id,5)->num_rows();
						$cek_a = $this->getTerritory($r->territory_id,6)->num_rows();
						
						$this->result .=',"children":';
						$this->result .= '[';
						
						if($cek_o > 0){
							$this->result .= '{"id":400, "name":"Outlet", "size":"", "date":"","iconCls":"icon-territory-4","children":';
							$this->getTerritoryTreeLast($r->territory_id,5);
							$this->result .= '}';
						}
						
						if($cek_o > 0 && $cek_a > 0){
							$this->result .= ',';
						}
						
						if($cek_a >0){
							$this->result .= '{"id":600, "name":"Agent", "size":"", "date":"","iconCls":"icon-territory-4","children":';
							$this->getTerritoryTreeLast($r->territory_id,6);
							$this->result .= '}';
						}
						
						$this->result .= ']';
						$this->result .='}';
						if($k!=$jum) $this->result .=',';
					}else{
						$this->result .=',"children":';
						$this->getTerritoryTree($r->territory_id,$p);
						$this->result .='}';
						if($k!=$jum) $this->result .=',';
					}
					
                                }else{
                                        $this->result .='}';
                                        if($k!=$jum) $this->result .=',';
                                        $this->getTerritoryTree($r->territory_id,$p);
                                }
                                
                        }
                        $this->result .="]";
                        
                }
		
                return $this->result;
	}
	
	
	function getTerritoryTreeLast($id,$type="")
        {    
                $query = $this->getTerritory($id,$type);
                $k=0;  
                $jum = $query->num_rows();
                $this->idnya ++;
		
		if($query->num_rows()>0){
                        $this->result .='[';
                        foreach($query->result() as $r){
                                $link="- - -";
				$this->result .='{"id":'. $type . $k .$this->idnya .',
                                                "name":"'. $r->territory_name .'",
                                                "size":"'. $r->user_id .'",
                                                "date":"'. $link .'",
						"iconCls":"icon-territory-4"';
                                $k++;
                                $this->result .='}';
				if($k!=$jum) $this->result .=',';
                        }
                        $this->result .="]";
                }
                return $this->result;
	}
	
	function getTerritoryType($id){
		$dblokal  = $this->load->database("default", TRUE);
                $dblokal->where("territory_type_id", $id);
                $query = $dblokal->get("t_mtr_territory_type");
                return $query->result();
	}
	////////////////////////////////////////////////////////////
	//                    get button privilage                 /
	////////////////////////////////////////////////////////////
	public function getButtons($menu_id, $user_group_id) {
    $this->db->select("COALESCE (menu_add, 0) AS menu_add, COALESCE (menu_view, 0) AS menu_view, COALESCE (menu_edit, 0) AS menu_edit", false);
    $this->db->select("COALESCE (menu_delete, 0) AS menu_delete, COALESCE (menu_approve, 0) AS menu_approve, COALESCE (menu_reject, 0) AS menu_reject", false);
    $this->db->where("user_group_id", $user_group_id);
    $this->db->where("menu_id", $menu_id);
    $query = $this->db->get("t_mtr_user_role");

    if($query->num_rows() > 0) {
      $result = $query->row_array();
    }
    else {
      $result['menu_add']     = 0;
      $result['menu_view']    = 0;
      $result['menu_edit']    = 0;
      $result['menu_delete']  = 0;
      $result['menu_reject']  = 0;
      $result['menu_approve'] = 0;
    }
    return  $result;
  }
}