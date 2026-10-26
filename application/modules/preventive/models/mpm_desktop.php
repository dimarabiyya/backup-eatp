<?php
class mpm_desktop extends CI_Model {
	function mpm_desktop(){
		parent::__construct();
	}
	
	function general()
	{
		$this->load->library('session');
		$data = array();
		$username           		= $this->session->userdata('username');
		$data['webtitle'] 	        = "";//$this->getNamaSistem2();
		$data['getMenuSistem']      = "";//$this->getMenuSistem();
		//$data['template']         = "shell/admin_template";
	    
		$data['getJudul']       = "";//$this->mjadwal->getJudul($this->uri->segment(2));
		return $data;
	}
	
	function getGridParent($ids){
		$dblokal  = $this->load->database("default", TRUE);
		/* $sql = "SELECT dd.group_name,dd.checklist_group_id, dd.order_number
				from t_trx_pm_schedule aa
				join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
				join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
				join t_mtr_checklist_group dd on cc.checklist_group=dd.checklist_group_id
				where aa.visit_id ='$ids' AND cc.interface='desktop' GROUP BY dd.group_name,dd.checklist_group_id";

		return $dblokal->query($sql)->result(); */
		$this->db->select('dd.group_name, dd.checklist_group_id, dd.order_number');
		$this->db->join('t_trx_pm_schedule_checklist bb', 'aa.visit_id = bb.visit_id', "left");
		$this->db->join('t_mtr_checklist cc', 'cc.checklist_id = bb.checklist_id', "left");
		$this->db->join('t_mtr_checklist_group dd', 'cc.checklist_group = dd.checklist_group_id',"left");
		$this->db->where('aa.visit_id',$ids);
		$this->db->where('cc.interface','desktop') ;
		$this->db->where('cc.status',1);
		$this->db->where('dd.status',1);
		$this->db->where('dd.group_key','pm');
		$this->db->group_by('dd.group_name, dd.checklist_group_id');
		$q = $this->db->get('t_trx_pm_schedule aa');
		return $q->result();
	}
	function getOrder($ids){
		$dblokal  = $this->load->database("default", TRUE);
		/* $sql = "SELECT dd.order_number
				from t_trx_pm_schedule aa
				join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
				join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
				join t_mtr_checklist_group dd on cc.checklist_group=dd.checklist_group_id
				where aa.visit_id =cast($ids as int4) AND cc.interface='desktop' GROUP BY dd.order_number order by dd.order_number";

		return $dblokal->query($sql)->row(); */
		$this->db->select('dd.order_number');
		$this->db->join('t_trx_pm_schedule_checklist bb', 'aa.visit_id = bb.visit_id', "left");
		$this->db->join('t_mtr_checklist cc', 'cc.checklist_id = bb.checklist_id', "left");
		$this->db->join('t_mtr_checklist_group dd', 'cc.checklist_group = dd.checklist_group_id',"left");
		$this->db->where('aa.visit_id',$ids);
		$this->db->where('cc.interface','desktop') ;
		$this->db->where('cc.status',1);
		$this->db->where('dd.status',1);
		$this->db->group_by('dd.order_number');
		$this->db->order_by('dd.order_number');
		$q = $this->db->get('t_trx_pm_schedule aa');
		return $q->result();
	}
	function getGridDesktop($ids,$ckid){
		$dblokal  = $this->load->database("default", TRUE);
		 /*$sql = "SELECT aa.*, cc.*, bb.*, dd.*,  ff.*
					from t_trx_pm_schedule aa
					join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
					join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
					join t_mtr_checklist_group dd on cc.checklist_group=dd.checklist_group_id
					join t_mtr_checklist_type ff on cc.checklist_type=ff.checklist_type_id
					where aa.visit_id ='$ids' AND dd.checklist_group_id=$ckid AND cc.interface='desktop'";*/
					//left join t_trx_pm_checklist_result ee on cc.checklist_id = ee.checklist_id and aa.visit_id = ee.visit_id
					//ee.pm_result, ee.checklist_result_id,
			/* $sql = "SELECT aa.*, cc.*, bb.*, dd.*, ee.pm_result, ee.checklist_result_id, ff.*
					from t_trx_pm_schedule aa
					join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
					join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
					join t_mtr_checklist_group dd on cc.checklist_group=dd.checklist_group_id
					join t_mtr_checklist_type ff on cc.checklist_type=ff.checklist_type_id
					left join t_trx_pm_checklist_result ee on cc.checklist_id = ee.checklist_id and aa.visit_id = ee.visit_id
					where aa.visit_id ='$ids' AND dd.checklist_group_id=$ckid AND cc.interface='desktop'";	 	
		return $dblokal->query($sql)->result();*/	
		$this->db->select('aa.site_id, aa.visit_id, cc.*, dd.checklist_group_id');
		$this->db->join('t_trx_pm_schedule_checklist bb', 'aa.visit_id = bb.visit_id', "left");
		$this->db->join('t_mtr_checklist cc', 'cc.checklist_id = bb.checklist_id', "left");
		$this->db->join('t_mtr_checklist_group dd', 'cc.checklist_group = dd.checklist_group_id',"left");
		$this->db->where('aa.visit_id',$ids);
		$this->db->where('dd.checklist_group_id',$ckid);
		$this->db->where('cc.interface','desktop') ;
		$this->db->where('cc.status',1);
		$this->db->where('dd.status',1);
		$this->db->group_by('aa.site_id, aa.visit_id, cc.checklist_id, cc.checklist_name, dd.checklist_group_id');
		$q = $this->db->get('t_trx_pm_schedule aa');
		return $q->result();
	}
	function getAttribute($ids,$ckid,$kid){
		$dblokal  = $this->load->database("default", TRUE);
		$this->db->select('cc.checklist_id, cc.has_photo, cc.total_photo, dd.type_name, ff.checklist_group_id, aa.visit_id, aa.site_id, ee.pm_result, ee.checklist_result_id');
		$this->db->join('t_trx_pm_schedule_checklist bb', 'aa.visit_id = bb.visit_id', "left");
		$this->db->join('t_mtr_checklist cc', 'cc.checklist_id = bb.checklist_id', "left");
		$this->db->join('t_mtr_checklist_type dd', 'cc.checklist_type = dd.checklist_type_id',"left");
		$this->db->join('t_trx_pm_checklist_result ee','ee.checklist_id = bb.checklist_id AND aa.visit_id = ee.visit_id',"left");
		$this->db->join('t_mtr_checklist_group ff', 'cc.checklist_group = ff.checklist_group_id',"left");
		$this->db->where('aa.visit_id',$ids);
		$this->db->where('ff.checklist_group_id',$ckid);
		$this->db->where('cc.checklist_id',$kid);
		$this->db->where('cc.status',1);
		$this->db->where('ff.status',1);
		$this->db->where('dd.status',1);
		$this->db->where('cc.interface','desktop');
		$q = $this->db->get('t_trx_pm_schedule aa');
		//echo $this->db->last_query();die();
		/* print_r($q); */
		return $q->result();
	}
	function getAttribute2($ids,$ckid,$kid){
		$dblokal  = $this->load->database("default", TRUE);
		$this->db->select('cc.checklist_id, cc.has_photo, cc.total_photo, dd.type_name, ff.checklist_group_id, aa.visit_id, aa.site_id');
		$this->db->join('t_trx_pm_schedule_checklist bb', 'aa.visit_id = bb.visit_id', "left");
		$this->db->join('t_mtr_checklist cc', 'cc.checklist_id = bb.checklist_id', "left");
		$this->db->join('t_mtr_checklist_type dd', 'cc.checklist_type = dd.checklist_type_id',"left");
		/* $this->db->join('t_trx_pm_checklist_result ee','ee.checklist_id = bb.checklist_id AND aa.visit_id = ee.visit_id',"left"); */
		$this->db->join('t_mtr_checklist_group ff', 'cc.checklist_group = ff.checklist_group_id',"left");
		$this->db->where('aa.visit_id',$ids);
		$this->db->where('ff.checklist_group_id',$ckid);
		$this->db->where('cc.checklist_id',$kid);
		$this->db->where('cc.status',1);
		$this->db->where('ff.status',1);
		$this->db->where('dd.status',1);
		$this->db->where('cc.interface','desktop');
		$q = $this->db->get('t_trx_pm_schedule aa');
		//echo $this->db->last_query();die();
		/* print_r($q); */
		return $q->result();
	}
	function getAttribute4($ids,$ckid,$kid){
		$dblokal  = $this->load->database("default", TRUE);
		$this->db->select('cc.checklist_id, cc.has_photo, cc.total_photo, dd.type_name, ff.checklist_group_id, aa.visit_id, aa.site_id, ee.option');
		$this->db->join('t_trx_pm_schedule_checklist bb', 'aa.visit_id = bb.visit_id', "left");
		$this->db->join('t_mtr_checklist cc', 'cc.checklist_id = bb.checklist_id', "left");
		$this->db->join('t_mtr_checklist_type dd', 'cc.checklist_type = dd.checklist_type_id',"left");
		$this->db->join('t_mtr_checklist_answer ee','ee.checklist_id = bb.checklist_id',"left");
		$this->db->join('t_mtr_checklist_group ff', 'cc.checklist_group = ff.checklist_group_id',"left");
		$this->db->where('aa.visit_id',$ids);
		$this->db->where('ff.checklist_group_id',$ckid);
		$this->db->where('cc.checklist_id',$kid);
		$this->db->where('cc.status',1);
		$this->db->where('ff.status',1);
		$this->db->where('dd.status',1);
		$this->db->where('cc.interface','desktop');
		$q = $this->db->get('t_trx_pm_schedule aa');
		//echo $this->db->last_query();die();
		/* print_r($q); */
		return $q->result();
	}
	function getAttribute3($ids,$ckid,$kid){
		$dblokal  = $this->load->database("default", TRUE);
		$sql = "SELECT cc.checklist_id, cc.has_photo, cc.total_photo, dd.type_name, ff.checklist_group_id, aa.visit_id, aa.site_id, ee.option
				FROM t_trx_pm_schedule aa
				LEFT JOIN t_trx_pm_schedule_checklist bb ON aa.visit_id = bb.visit_id
				LEFT JOIN t_mtr_checklist cc ON cc.checklist_id = bb.checklist_id
				LEFT JOIN t_mtr_checklist_type dd ON cc.checklist_type = dd.checklist_type_id
				LEFT JOIN t_mtr_checklist_answer ee ON ee.checklist_id = bb.checklist_id
				LEFT JOIN t_mtr_checklist_group ff ON cc.checklist_group = ff.checklist_group_id
				WHERE aa.visit_id='$ids' AND ff.checklist_group_id='$ckid' AND cc.checklist_id='$kid' AND cc.status='1' AND ff.status='1' AND dd.status='1' AND cc.interface='desktop'
				AND ee.checklist_id NOT IN (select checklist_id FROM t_trx_pm_checklist_result WHERE visit_id='$ids')";
		return $dblokal->query($sql)->result();
	}
	function getValue($ids){
		$dblokal  = $this->load->database("default", TRUE);
		
		$this->db->select('pm_result');
		$this->db->where('visit_id',$ids);
		/* $this->db->where('aa.checklist_id',$ckid); */
		$q = $this->db->get('t_trx_pm_checklist_result');
		return $q->result();
	}
	function getValue2($ids,$ckid){
		$dblokal  = $this->load->database("default", TRUE);
		
		$sql="SELECT aa.option, bb.pm_result, bb.checklist_result_id, bb.visit_id
				FROM t_mtr_checklist_answer aa
				LEFT JOIN t_trx_pm_checklist_result bb on aa.checklist_id = bb.checklist_id AND aa.option=bb.pm_result
				WHERE aa.checklist_id = $ids AND bb.visit_id ='$ckid' AND aa.active='1'
				ORDER BY order_no";
		return $dblokal->query($sql)->result();
	}
	function getPhoto($ids,$ckid){
		$dblokal  = $this->load->database("default", TRUE);
		
		 /*$sql="select *
			from t_mtr_checklist_group 
			where parent_id = $parent_id AND status='1'
			order by order_number";*/
			/* $sql = "SELECT aa.*, cc.*, bb.*, dd.*, ee.photo_name, ee.result_photo_id, ff.*
									from t_trx_pm_schedule aa
									join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
									join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
									join t_mtr_checklist_group dd on cc.checklist_group=dd.checklist_group_id
									join t_mtr_checklist_type ff on cc.checklist_type=ff.checklist_type_id
									left join t_trx_pm_checklist_result_photo ee on cc.checklist_id = ee.checklist_id and aa.visit_id = ee.visit_id
									where aa.visit_id ='$ids' AND ee.checklist_id=$ckid AND cc.interface='desktop'
									AND bb.checklist_id IN (select checklist_id FROM t_trx_pm_checklist_result_photo WHERE visit_id='$ids')"; */
		$this->db->select('aa.result_photo_id, aa.photo_name, aa.checklist_id, aa.visit_id');
		$this->db->where('aa.visit_id',$ids);
		$this->db->where('aa.checklist_id',$ckid);
		$q = $this->db->get('t_trx_pm_checklist_result_photo aa');
		return $q->result();
			
		/* return $dblokal->query($sql)->result(); */
	}
	function getGridDesktopValue($ids){
		$dblokal  = $this->load->database("default", TRUE);
		
		 /*$sql="select *
			from t_mtr_checklist_group 
			where parent_id = $parent_id AND status='1'
			order by order_number";
			$sql = "SELECT *
									from t_trx_pm_schedule aa
									join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
									join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
									join t_mtr_checklist_answer ee on ee.checklist_id = bb.checklist_id
									join t_mtr_checklist_group dd on cc.checklist_group=dd.checklist_group_id
									join t_mtr_checklist_type ff on cc.checklist_type=ff.checklist_type_id
									where aa.visit_id ='$ids' AND cc.interface='desktop'
									AND ee.checklist_id NOT IN (select checklist_id FROM t_trx_pm_checklist_result WHERE visit_id='$ids' AND option=pm_result)";*/
									
			$sql = "SELECT *
									from t_trx_pm_schedule aa
									join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
									join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
									join t_mtr_checklist_group dd on cc.checklist_group=dd.checklist_group_id
									join t_mtr_checklist_type ff on cc.checklist_type=ff.checklist_type_id
									where aa.visit_id ='$ids' AND cc.interface='desktop'
									AND bb.checklist_id NOT IN (select checklist_id FROM t_trx_site_inspection_checklist_result WHERE visit_id='$ids')";
									//left join t_trx_site_inspection_checklist_result gg on cc.checklist_id = gg.checklist_id AND aa.visit_id = gg.visit_id

		return $dblokal->query($sql)->num_rows();
	}
	public function status($id){
		$dblokal = $this->load->database("default", TRUE);
		
		$data['last_visited_date'] = date("Y-m-d");		
		$data['updated_on'] = date("Y-m-d H:i:s");
		$data['updated_by'] = $this->session->userdata('user_id');
		$data['status'] = '3';
		
		$dblokal->where( array('visit_id'=>$id) );
		$dblokal->update('t_trx_pm_schedule', $data);
	}
	function getAnswer($ids,$ckid){
		$dblokal  = $this->load->database("default", TRUE);
		
		/* $sql="SELECT aa.option, bb.pm_result, bb.checklist_result_id, bb.visit_id
				FROM t_mtr_checklist_answer aa
				LEFT JOIN t_trx_pm_checklist_result bb on aa.checklist_id = bb.checklist_id AND aa.option=bb.pm_result
				WHERE aa.checklist_id = $ids AND aa.active='1'
				ORDER BY order_no"; */
		$sql="SELECT *
				FROM t_mtr_checklist_answer aa
				WHERE aa.checklist_id='$ckid' 
				AND aa.checklist_id NOT IN (SELECT checklist_id FROM t_trx_pm_checklist_result WHERE visit_id='$ids' AND aa.option=pm_result)
				ORDER BY order_no";
 
		return $dblokal->query($sql)->result();
	}
	function getAnswerCheck($ids,$ckid){
		$dblokal  = $this->load->database("default", TRUE);
		
		$sql="SELECT aa.option, bb.pm_result, bb.checklist_result_id, bb.visit_id
				FROM t_mtr_checklist_answer aa
				LEFT JOIN t_trx_pm_checklist_result bb on aa.checklist_id = bb.checklist_id AND aa.option=bb.pm_result
				WHERE bb.visit_id='$ids' AND aa.checklist_id = '$ckid' AND aa.active='1'
				ORDER BY order_no";
		/* $sql="select *
			from t_mtr_checklist_answer
			where checklist_id = $ids AND active='1'"; */
		/* echo $sql;	 */
		return $dblokal->query($sql)->result();
	}
	function getAnswerRadio($ids){
		$dblokal  = $this->load->database("default", TRUE);
		
		$sql="select *
			from t_mtr_checklist_answer
			where checklist_id = $ids AND active='1'";
		return $dblokal->query($sql)->result();
	}
	function getSite($ids){
		$this->db->select('ss.visit_id, ss.visit_date, ss.visit_type, ss.last_visited_date, ss.priority, ms.site_id, ms.site_code, ms.site_name, mu.user_id, mu.user_name, mr.region_name, co.cluster_name');
		$this->db->join('t_trx_pm_schedule ss', 'ss.site_id = ms.site_id', "left");
		$this->db->join('t_mtr_user mu', 'mu.user_id = ss.user_id', "left");
		$this->db->join('t_mtr_region mr', 'mr.region_id = ms.region_id',"left");
		$this->db->join('t_mtr_cluster_ops co', 'co.cluster_ops_id = ms.cluster_ops_id',"left");
		$this->db->where('ss.visit_id',$ids);
		$q = $this->db->get('t_mtr_site ms');
		return $q->row();
	}
	public function getVisitImage($table_name, $param){
		$this->db->where($param);
		$q = $this->db->get($table_name);
		return $q->result();
	}
	public function saveData(){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('cid','vid','sid','answer','userfile','rid');
		foreach ($input as $val)
			$$val = $this->input->post($val);			
		
			foreach ( $answer as $key=>$value ):
				$data = array(				
					   'pm_result'=>$answer[$key],
					   'visit_id'=>$vid,
					   'pm_score'=>0,
					   'checklist_id'=> $cid
					   //'site_id'=> $sid
					   );
			$id = count($rid);
			if(empty($rid[0])){
				$data['created_on'] = date("Y-m-d H:i:s");
				$data['created_by'] = $this->session->userdata('user_id');
				$dblokal->insert('t_trx_pm_checklist_result', $data);					   
			}else{
				// $q = "SELECT COUNT(checklist_result_id) FROM t_trx_pm_checklist_result where checklist_id='$cid' AND visit_id='$vid'";
				// $id = $this->db->query($q)->result_array();
				//print_rr($id);
				for($i=0; $i<$id; $i++){
					$dblokal->delete('t_trx_pm_checklist_result',array('checklist_result_id'=>$rid[$i]));
				}
				$data['created_on'] = date("Y-m-d H:i:s");
				$data['created_by'] = $this->session->userdata('user_id');
				$dblokal->insert('t_trx_pm_checklist_result', $data); 
				/* $data['created_on'] = date("Y-m-d H:i:s");
				$data['created_by'] = $this->session->userdata('user_id');
				$dblokal->insert('t_trx_pm_checklist_result', $data); */
			}
			endforeach;
		
		
	}
	public function saveDatas($name){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('cid','vid','sid','answer','pid','userfile','file');
		foreach ($input as $val)
			$$val = $this->input->post($val);			
				$data = array(				
					   'photo_name'=>$name,
					   'visit_id'=>$vid,
					   'status'=>1,
					   'checklist_id'=> $cid
					   );	   
			if(empty($pid[0])){
				$data['created_on'] = date("Y-m-d H:i:s");
				$data['created_by'] = $this->session->userdata('user_id');
				$dblokal->insert('t_trx_pm_checklist_result_photo', $data);
			}else{
				// $data['updated_on'] = date("Y-m-d H:i:s");
				// $data['updated_by'] = $this->session->userdata('user_id');
				// $dblokal->where("result_photo_id = $pid AND checklist_id=$cid AND visit_id=$vid");
				// $dblokal->update('t_trx_pm_checklist_result_photo', array('photo_name'=>$name, 'visit_id'=>$vid, 'status'=>1, 'checklist_id'=>$cid, 'updated_on'=>$data['updated_on'],'updated_by'=>$data['updated_by']));
				// $dblokal->delete('t_trx_pm_checklist_result_photo');
				$id = count($pid);
				for($i=0; $i<$id; $i++){
					$dblokal->delete('t_trx_pm_checklist_result_photo',array('result_photo_id'=>$pid[$i]));
				}
				$data['created_on'] = date("Y-m-d H:i:s");
				$data['created_by'] = $this->session->userdata('user_id');
				$dblokal->insert('t_trx_pm_checklist_result_photo', $data);
			}
		
	}
	#######################################datagrid jeasyui
	public function getListVisit($d=NULL,$q,$cluster_ops_id, $year_ops, $opr_month){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');


		foreach ($input as $val)
			$$val = $this->input->post($val);       
            $or_arr=array('aa.site_code','bb.visit_type','aa.site_name','cc.user_name','aa.vendor_id');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="AND (lower($val) like lower('%$q%')";
                else
                    $or_str.="or lower($val) like lower('%$q%')";
            }
            $or_str.=")";   


		if (is_numeric($cluster_ops_id))
			$where_cluster_ops="AND aa.cluster_ops_id='$cluster_ops_id'";
		else{
					$region=array();
					foreach ($this->session->userdata('user_region') as $row) {
						$region[]=$row['region_id'];
					}
					$region = implode(',', $region);
			$where_cluster_ops="AND re.region_id in ($region)";
		}

		 $q = "SELECT aa.site_id, aa.site_code_old, aa.site_code, aa.site_name, aa.vendor_id, aa.region_id, aa.cluster_ops_id,
					bb.visit_id, cc.user_name, cc.user_id, bb.visit_date, bb.status, bb.visit_type
					FROM t_mtr_site aa, t_trx_pm_schedule bb, t_mtr_user cc, t_mtr_cluster_ops ops, t_mtr_region re
					WHERE aa.site_id=bb.site_id AND aa.status='1' AND cc.user_id=bb.user_id AND ops.cluster_ops_id=aa.cluster_ops_id AND re.region_id = ops.region_id $or_str 
					AND bb.visit_id IN (SELECT dd.visit_id FROM t_trx_pm_schedule_checklist dd, t_mtr_checklist ee
					WHERE ee.checklist_id=dd.checklist_id AND ee.interface='desktop')
					$where_cluster_ops
					AND date_part('year', bb.visit_date) = '$year_ops' AND date_part('month', bb.visit_date) = '$opr_month' AND bb.status IN (1, 3)
					ORDER BY bb.visit_date DESC";
		$query = $dblokal->query( $q );
        return $query;
    }
	 public function getListVisits($offset,$rows,$q,$cluster_ops_id, $year_ops, $opr_month){
		$dblokal = $this->load->database("default", TRUE);
		$input = array('dataperpage', 'query', 'curpage');
		foreach ($input as $val)
			$$val = $this->input->post($val);       
            $or_arr=array('aa.site_code','bb.visit_type','aa.site_name','cc.user_name','aa.vendor_id');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="AND (lower($val) like lower('%$q%')";
                else
                    $or_str.="or lower($val) like lower('%$q%')";
            }
            $or_str.=")";   
		/* $q = "SELECT *
					FROM t_mtr_site aa, t_trx_pm_schedule bb, t_mtr_user cc
					WHERE aa.site_id=bb.site_id $or_str
					AND bb.visit_id IN (SELECT dd.visit_id FROM t_trx_pm_schedule_checklist dd, t_mtr_checklist ee
					WHERE ee.checklist_id=dd.checklist_id AND ee.interface='desktop')
					AND cc.user_id=bb.user_id 
					AND (bb.status='1' or bb.status='0' or bb.status='5' or bb.status='3')
					ORDER BY bb.visit_date DESC
					LIMIT $rows OFFSET $offset"; */ 
		if (is_numeric($cluster_ops_id))
			$where_cluster_ops="AND aa.cluster_ops_id='$cluster_ops_id'";
		else{
					$region=array();
					foreach ($this->session->userdata('user_region') as $row) {
						$region[]=$row['region_id'];
					}
					$region = implode(',', $region);
			$where_cluster_ops="AND re.region_id in ($region)";
		}


				$q="SELECT aa.site_id, aa.site_code_old, aa.site_code, aa.site_name, aa.vendor_id, aa.region_id, aa.cluster_ops_id,
					bb.visit_id, cc.user_name, cc.user_id, bb.visit_date, bb.status, bb.visit_type
					FROM t_mtr_site aa, t_trx_pm_schedule bb, t_mtr_user cc , t_mtr_cluster_ops ops, t_mtr_region re
					WHERE aa.site_id=bb.site_id AND aa.status='1' AND cc.user_id=bb.user_id AND ops.cluster_ops_id=aa.cluster_ops_id AND re.region_id = ops.region_id $or_str 
					AND bb.visit_id IN (SELECT dd.visit_id FROM t_trx_pm_schedule_checklist dd, t_mtr_checklist ee
					WHERE ee.checklist_id=dd.checklist_id AND ee.interface='desktop')
					$where_cluster_ops
					AND date_part('year', bb.visit_date) = '$year_ops' AND date_part('month', bb.visit_date) = '$opr_month' AND bb.status IN (1, 3)
					ORDER BY bb.visit_date DESC
					LIMIT $rows OFFSET $offset";
		
		$query = $dblokal->query( $q );
        return $query;
    }
	public function getListDetails($id,$offset=0,$rows=0){
		$dblokal = $this->load->database("default", TRUE);
		
		 $q = "SELECT aa.visit_id, cc.checklist_name,cc.interface,dd.pm_result
									from t_trx_pm_schedule aa
									join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
									join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
									left join t_trx_pm_checklist_result dd on cc.checklist_id = dd.checklist_id and dd.visit_id = aa.visit_id
									where aa.visit_id ='$id' AND cc.interface='desktop' AND cc.status='1'";
									
		if ($rows!=0){
            $q .= "LIMIT ".$rows." OFFSET ".$offset." ";
        }
		$query = $dblokal->query( $q );
        return $query;
    }
	public function addeditdata($id,$d=NULL){
		$dblokal = $this->load->database("default", TRUE);
		if (isset($d['q'])){
            $q=$d['q'];
            $or_arr=array('cc.checklist_name');
            $or_str="";
            foreach ($or_arr as $val) {
                if ($or_str=="")
                    $or_str.="(lower($val) like lower('%$q%')";
                else
                    $or_str.=" or lower($val) like lower('%$q%')";
            }
            $or_str.=")";
        }
		
		 $q = "SELECT aa.visit_id, cc.checklist_name, cc.interface
									from t_trx_pm_schedule aa
									join t_trx_pm_schedule_checklist bb on aa.visit_id = bb.visit_id
									join t_mtr_checklist cc on cc.checklist_id = bb.checklist_id
									where aa.visit_id ='$id' AND cc.interface='desktop' AND $or_str";
									
				
		$query = $dblokal->query( $q );
        return $query;
    }
	#####################################################
}
?>