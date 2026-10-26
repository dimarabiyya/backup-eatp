<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mgrid extends CI_Model {
	
	public function getCluster($region_id){
		if($region_id !=''){
		$this->db->where('region_id',$region_id);
		}
		$this->db->where('status',1);
	  	$q = $this->db->get('t_mtr_cluster_ops');
		return $q->result_array();
	  }

  public function getPicMap($keyword, $region_id, $limit, $offset, $cluster_ops_id){
    
    if(trim($keyword) != '') {
      $ks = strtolower(trim($keyword));

      $swhere_1 = " and (lower(uu.user_id) like '%$ks%' or lower(uu.user_name) like '%$ks%')";
      $swhere_2 = " or ( lower(rr.region_name) like '%$ks%' or lower(cc.cluster_name) like '%$ks%')";
    }
    else {
      $swhere_1 = "";
      $swhere_2 = "";
    }

// SELECT uu.user_id, uu.user_name, hh.imei, hh.msisdn, f_grouped_user_region(uu.user_id) user_region,
//           f_grouped_user_role(uu.user_id) user_roles, rc.region_cluster from t_mtr_user uu
//           join t_mtr_handset hh on hh.user_id = uu.user_id
//           left join (
//             SELECT r.user_id, array_to_string(array_agg(r.region_cluster), ', ') region_cluster FROM (
//               select DISTINCT concat_ws(' - ',rr.region_name, cc.cluster_name) region_cluster, tt.user_id
//               from t_mtr_user_territory tt join t_mtr_user uu on uu.user_id = tt.user_id 
//               join t_mtr_region rr on rr.region_id = tt.region_id
//               join t_mtr_cluster_ops cc on cc.cluster_ops_id = tt.cluster_ops_id
//               where rr.status=1) r GROUP BY r.user_id
//           ) rc on rc.user_id = uu.user_id where f_user_content_roles(uu.user_id, 55) is true
//           and f_user_content_region(uu.user_id, 5) is true;

    /*
    $s = "select distinct ss.pic_smart, initcap(uu.user_name) username,hh.imei, hh.msisdn, f_grouped_user_region(uu.user_id) user_region,
              f_grouped_user_role(uu.user_id) user_roles, rc.region_cluster from t_mtr_site ss
              join t_mtr_user uu on uu.user_id = ss.pic_smart left join t_mtr_handset hh on hh.user_id = uu.user_id
              left join (
                SELECT r.user_id, array_to_string(array_agg(r.region_cluster), ', ') region_cluster FROM (
                  select DISTINCT concat_ws(' - ',rr.region_name, cc.cluster_name) region_cluster, tt.user_id
                  from t_mtr_user_territory tt join t_mtr_user uu on uu.user_id = tt.user_id 
                  join t_mtr_region rr on rr.region_id = tt.region_id
                  join t_mtr_cluster_ops cc on cc.cluster_ops_id = tt.cluster_ops_id
                  where rr.status=1 $swhere_2) r GROUP BY r.user_id
              ) rc on rc.user_id = uu.user_id where uu.status = 1 and f_user_content_roles(uu.user_id, 55) is true";*/
    

/*
        $s =  
        "SELECT DISTINCT
	ss.pic_smart,
	initcap(uu.user_name) username,
	hh.imei,
	hh.msisdn,
	f_grouped_user_region (uu.user_id) user_region,
	f_grouped_user_role (uu.user_id) user_roles,
	t_mtr_cluster_ops.cluster_name
FROM
	t_mtr_site ss
JOIN t_mtr_user uu ON uu.user_id = ss.pic_smart
LEFT JOIN t_mtr_handset hh ON hh.user_id = uu.user_id
LEFT JOIN t_mtr_region ON t_mtr_region.region_id = ss.region_id
LEFT JOIN t_mtr_cluster_ops ON t_mtr_cluster_ops.cluster_ops_id = ss.cluster_ops_id 
where uu.status = 1 and f_user_content_roles(uu.user_id, 55) is true";*/




/*
        $s =  
        "SELECT DISTINCT
	ss.pic_smart,
	initcap(uu.user_name) username,
	hh.imei,
	hh.msisdn,
	f_grouped_user_region (t_mtr_user_multi_role.user_id) user_region,
	f_grouped_user_role (t_mtr_user_multi_role.user_id) user_roles,
	t_mtr_cluster_ops.cluster_name
FROM
	t_mtr_site ss
JOIN t_mtr_user uu ON uu.user_id = ss.pic_smart
JOIN t_mtr_user_multi_role ON t_mtr_user_multi_role.user_id = uu.user_id
LEFT JOIN t_mtr_handset hh ON hh.user_id = uu.user_id
LEFT JOIN t_mtr_region ON t_mtr_region.region_id = ss.region_id
LEFT JOIN t_mtr_cluster_ops ON t_mtr_cluster_ops.cluster_ops_id = ss.cluster_ops_id 
WHERE
	uu.status = 1
AND f_user_content_roles (uu.user_id, 55) IS TRUE";*/

     $s =  
        "SELECT DISTINCT
	ss.pic_smart,
	initcap(uu.user_name) username,
	hh.imei,
	hh.msisdn,
	f_grouped_user_region (t_mtr_user_multi_role.user_id) user_region,
	f_grouped_user_role (t_mtr_user_multi_role.user_id) user_roles,
	asl_cluster.cluster_name
FROM
	t_mtr_site ss
JOIN t_mtr_user uu ON uu.user_id = ss.pic_smart
JOIN t_mtr_user_multi_role ON t_mtr_user_multi_role.user_id = uu.user_id
LEFT JOIN t_mtr_handset hh ON hh.user_id = uu.user_id
LEFT JOIN t_mtr_region ON t_mtr_region.region_id = ss.region_id
LEFT JOIN t_mtr_cluster_ops ON t_mtr_cluster_ops.cluster_ops_id = ss.cluster_ops_id 

LEFT JOIN (
SELECT  ss.pic_smart,
array_to_string(array_agg(DISTINCT t_mtr_cluster_ops.cluster_name), ', ') cluster_name
from t_mtr_site ss
JOIN t_mtr_user uu ON uu.user_id = ss.pic_smart
LEFT JOIN t_mtr_region ON t_mtr_region.region_id = ss.region_id
LEFT JOIN t_mtr_cluster_ops ON t_mtr_cluster_ops.cluster_ops_id = ss.cluster_ops_id 
GROUP BY ss.pic_smart
) asl_cluster ON asl_cluster.pic_smart = ss.pic_smart
WHERE
	uu.status = 1
AND f_user_content_roles (uu.user_id, 55) IS TRUE 
        ";
		  
		if($region_id !=''){
		// $s .= " and ss.region_id = $region_id"; 
		$s .= " AND f_user_content_region(uu.user_id, $region_id) is true";
		}
		if($cluster_ops_id !=''){
		$s .= " and t_mtr_cluster_ops.cluster_ops_id = $cluster_ops_id"; 
		}
		$s .= " $swhere_1 ORDER BY  initcap(uu.user_name) ASC";
        // $s .= " $swhere_1 GROUP BY uu.user_id ORDER BY  initcap(uu.user_name) ASC";
         // die($s);

    $q_1 = $this->db->query($s);
    // echo $this->db->last_query();
    $q_2 = $this->db->query($s." limit $limit offset $offset");
    foreach ($q_2->result() as $k) {
      $user_id = $this->core->encode($k->pic_smart);
      $k->buttons = '<a href="'.base_url().'preventive/assignment_pic/edit/'.$user_id.'" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>
      <a href="javascript:deleteSiteAssignmentPic(\''.$k->pic_smart.'\', \''.$k->username.'\')" class="btn-action glyphicons remove_2 btn-danger" title="delete"><i></i></a>';
    }

    $r['total'] = $q_1->num_rows();
    $r['rows']  = $q_2->result();

    return $r;
  }
  public function getPicSiteMap($q, $t, $keyword, $limit, $offset){
    if(trim($keyword) != '') {
      $ks = strtolower(trim($keyword));
      $swhere_1 = " and (lower(site_code) like '%$ks%' or lower(vendor_id) like '%$ks%' or lower(site_name) like '%$ks%')";
    }
    else {
      $swhere_1 = "";
    }

    $s = "SELECT ss.site_id, rr.region_name, cc.cluster_name, ss.site_code, ss.site_name, ss.vendor_id, ss.site_address, ss.latitude, ss.longitude from t_mtr_site ss 
          join t_mtr_region rr on rr.region_id = ss.region_id 
          left join t_mtr_cluster_ops cc on cc.cluster_ops_id = ss.cluster_ops_id where ss.pic_smart = '$q' $swhere_1";
    $q_1 = $this->db->query($s);
    $q_2 = $this->db->query($s." limit $limit offset $offset");
    foreach ($q_2->result() as $x) {
      $x->button = '<button type="button" class="btn" onClick="DeleteMapping('.$x->site_id.', \''.$q.'\', \''.$t.'\')">Delete</button>';
    }
    $r['total'] = $q_1->num_rows();
    $r['rows']  = $q_2->result();

// echo $this->db->last_query();
   return $r;
  }

/*
  public function getClusterCoordinator($keyword, $region_id, $cluster_id, $limit, $offset){
    if($cluster_id) {
      $where_1 = " and cc.cluster_ops_id = $cluster_id";
    }
    if($keyword){
      $key = trim(strtolower($keyword));
      $where_2 = " and (lower(tt.user_id) like '%$key%' or lower(uu.user_name) like '%$key%' or lower(rc.region_cluster) like '%$key%' )";
    }
    // $q = "select DISTINCT tt.user_id, initcap(uu.user_name) username, f_grouped_user_role(tt.user_id) user_group_role, 
    //       f_grouped_user_region(tt.user_id) user_region, rc.region_cluster from t_mtr_user_territory tt
    //       join t_mtr_user uu on uu.user_id = tt.user_id
    //       join (
    //         SELECT r.user_id, array_to_string(array_agg(r.region_cluster), ', ') region_cluster FROM (
    //           select DISTINCT concat_ws(' - ',rr.region_name, cc.cluster_name) region_cluster, cm.user_id
    //           from t_mtr_cluster_mapping cm join t_mtr_user uu on uu.user_id = cm.user_id
    //           join t_mtr_user_multi_role mr on mr.user_id = uu.user_id
    //           join t_mtr_user_group gg on gg.user_group_id = mr.user_group_id and gg.area_restriction = 'cluster_assignment'
    //           join t_mtr_cluster_ops cc on cc.cluster_ops_id = cm.cluster_ops_id
    //           join t_mtr_region rr on rr.region_id = cc.region_id 
    //           where rr.status=1 and f_user_content_region(uu.user_id, $region_id) is true $where_1) r GROUP BY r.user_id
    //         ) rc on rc.user_id = uu.user_id where f_user_content_region(tt.user_id, $region_id) is true $where_1 $where_2";
    $q = "select DISTINCT tt.user_id, initcap(uu.user_name) username, f_grouped_user_role(tt.user_id) user_group_role, 
          f_grouped_user_region(tt.user_id) user_region, rc.region_cluster, rc.cluster_ops_id from t_mtr_user_territory tt
          join t_mtr_user uu on uu.user_id = tt.user_id
          join (
              select DISTINCT concat_ws(' - ',rr.region_name, cc.cluster_name) region_cluster, cm.user_id, cm.cluster_ops_id
              from t_mtr_cluster_mapping cm join t_mtr_user uu on uu.user_id = cm.user_id
              join t_mtr_user_multi_role mr on mr.user_id = uu.user_id
              join t_mtr_user_group gg on gg.user_group_id = mr.user_group_id and gg.area_restriction = 'cluster_assignment'
              join t_mtr_cluster_ops cc on cc.cluster_ops_id = cm.cluster_ops_id
              join t_mtr_region rr on rr.region_id = cc.region_id 
              where rr.status=1 and uu.status = 1 and f_user_content_region(uu.user_id, $region_id) is true $where_1
            ) rc on rc.user_id = uu.user_id where f_user_content_region(tt.user_id, $region_id) is true $where_2
          order by tt.user_id";
    $r = $this->db->query($q);
    $d['total'] = $r->num_rows();
    $r = $this->db->query($q." limit $limit offset $offset")->result();
    // echo $this->db->last_query();
    foreach ($r as $dg) {
      $dg->buttons = '<button type="button" class="btn btn-small" onClick="DeleteMapping(\''.$dg->user_id.'\','.$dg->cluster_ops_id.')">Delete</button>';
    }

    $d['rows'] = $r;
    return $d;
  }*/

  
    public function getClusterCoordinator($keyword, $region_id, $cluster_id, $limit, $offset){
    $where_1='';
	$where_2='';
	if($cluster_id>0) {
      //$where_1 = " and cc.cluster_ops_id = $cluster_id";
		      $where_1 = " and t_mtr_cluster_ops.cluster_ops_id = $cluster_id";
		
    }
    if($keyword){
      $key = trim(strtolower($keyword));
      //$where_2 = " and (lower(tt.user_id) like '%$key%' or lower(uu.user_name) like '%$key%' or lower(rc.region_cluster) like '%$key%' )";
	        $where_2 = " and (lower(t_mtr_user.user_id) like '%$key%' or lower(t_mtr_user.user_name) like '%$key%' or lower(t_mtr_cluster_ops.cluster_name) like '%$key%' or lower(t_mtr_region.region_name) like '%$key%' )";
    }

$q = "SELECT DISTINCT t_mtr_cluster_mapping.user_id, initcap(t_mtr_user.user_name) username, t_mtr_user.mobile_number,f_grouped_user_role(t_mtr_user.user_id) user_group_role, t_mtr_cluster_ops.region_id
 from t_mtr_user
 JOIN t_mtr_cluster_mapping ON t_mtr_cluster_mapping.user_id = t_mtr_user.user_id
 JOIN t_mtr_cluster_ops ON t_mtr_cluster_ops.cluster_ops_id = t_mtr_cluster_mapping.cluster_ops_id
 JOIN t_mtr_region ON t_mtr_region.region_id = t_mtr_cluster_ops.region_id
 where t_mtr_region.status=1 and f_user_content_region(t_mtr_user.user_id, $region_id) is true
 and t_mtr_cluster_ops.region_id = $region_id
 $where_1
 $where_2
 ORDER BY user_id ASC"; 


          
    $r = $this->db->query($q);
    $d['total'] = $r->num_rows();
    $r = $this->db->query($q." limit $limit offset $offset")->result();
    // echo $this->db->last_query();

    foreach ($r as $dg) {
      //$dg->buttons = '<button type="button" class="btn btn-small" onClick="deleteAllSiteAssignment(\''.$dg->user_id.'\')">Delete</button>';
	  //$dg->buttons = '<button type="button" class="btn btn-small" onClick="deleteAllSiteAssignment(\''.$dg->user_id.'\',\''.$dg->username.'\')">Delete</button>';
	  $user_id = $this->core->encode($dg->user_id);
	  $dg->buttons = '<a href="'.base_url().'preventive/assignment_ssh/edit/'.$user_id.'" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>
      <a href="javascript:deleteAllSiteAssignment(\''.$dg->user_id.'\',\''.$dg->username.'\')" class="btn-action glyphicons remove_2 btn-danger" title="delete"><i></i></a>';
    }

    $d['rows'] = $r;
    return $d;
  }

  // public function getClusterCoordinatorMap($q, $region_id, $t, $keyword, $limit, $offset){
  public function getClusterCoordinatorMap($q, $t, $keyword, $limit, $offset){
    if(trim($keyword) != '') {
      $ks = strtolower(trim($keyword));
      //$swhere_1 = " and (lower(site_code) like '%$ks%' or lower(vendor_id) like '%$ks%' or lower(site_name) like '%$ks%')";
	  $swhere_1 = " and (lower(t_mtr_region.region_name) like '%$ks%' or lower(t_mtr_cluster_ops.cluster_name) like '%$ks%')";
	  
    }
    else {
      $swhere_1 = "";
    }

    $s = "SELECT
	t_mtr_region.region_name,
	t_mtr_cluster_ops.cluster_name,
	t_mtr_cluster_ops.cluster_ops_id,
	t_mtr_cluster_mapping.user_id
FROM
	t_mtr_cluster_ops
JOIN t_mtr_region ON t_mtr_region.region_id = t_mtr_cluster_ops.region_id
JOIN t_mtr_cluster_mapping ON t_mtr_cluster_mapping.cluster_ops_id = t_mtr_cluster_ops.cluster_ops_id 
WHERE t_mtr_cluster_mapping.user_id = '$q'
$swhere_1
";
// -- and t_mtr_cluster_ops.region_id = '$region_id'

    $q_1 = $this->db->query($s);
    $q_2 = $this->db->query($s." limit $limit offset $offset");
    foreach ($q_2->result() as $x) {
     //$x->button = '<button type="button" class="btn" onClick="DeleteMapping('.$x->site_id.', \''.$q.'\', \''.$t.'\')">Delete</button>';
	 $x->button = '<button type="button" class="btn" onClick="DeleteMapping(\''.$x->user_id.'\',\''.$x->cluster_ops_id.'\',\''.$t.'\')">Delete</button>';
	
    }
    $r['total'] = $q_1->num_rows();
    $r['rows']  = $q_2->result();

//echo $this->db->last_query();

   return $r;
  }
}

/* End of file mgrid.php */
/* Location: ./application/modules/preventive/models/mgrid.php */