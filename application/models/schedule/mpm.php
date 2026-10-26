<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mpm extends CI_Model {

  public function getSiteList($cluster_ops_id, $year_ops, $search, $page, $rows, $allow_create_schedule, $opr_region, $territoryType,$sort, $order)
  {
    $offset = ($page-1) * $rows;
    $sWhere = 'ss.status = 1 and ss.active = 1 and';
    $sSearch = '';
    if($territoryType == 'region') {
      $sWhere .= " rr.region_id = $opr_region and";
    }
    else {
      $sWhere .= " ss.cluster_ops_id = $cluster_ops_id and";
    }
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere .= "(lower(ss.site_name) like '%".$keyword."%' or lower(ss.site_code) like '%".$keyword."%' or lower(ss.vendor_id) like '%".$keyword."%'
                  or lower(uu.user_name) like '%".$keyword."%' or lower(ss.pic_smart) like '%".$keyword."%') and";
    }
    $sWhere = substr($sWhere, 0, -4);
    $sql = "select rr.region_id, ss.vendor_id, rr.region_code, rr.region_name, ss.site_id, ss.site_code, ss.site_name,
            initcap(uu.user_name) as site_pic_name,
            count(case when (date_part('month', visit_date) = 1) then 1 end) as m_01, 
            count(case when (date_part('month', visit_date) = 2) then 1 end) as m_02, 
            count(case when (date_part('month', visit_date) = 3) then 1 end) as m_03, 
            count(case when (date_part('month', visit_date) = 4) then 1 end) as m_04, 
            count(case when (date_part('month', visit_date) = 5) then 1 end) as m_05, 
            count(case when (date_part('month', visit_date) = 6) then 1 end) as m_06, 
            count(case when (date_part('month', visit_date) = 7) then 1 end) as m_07, 
            count(case when (date_part('month', visit_date) = 8) then 1 end) as m_08, 
            count(case when (date_part('month', visit_date) = 9) then 1 end) as m_09, 
            count(case when (date_part('month', visit_date) = 10) then 1 end) as m_10, 
            count(case when (date_part('month', visit_date) = 11) then 1 end) as m_11, 
            count(case when (date_part('month', visit_date) = 12) then 1 end) as m_12
            from t_mtr_site ss
            left join t_mtr_user uu on uu.user_id = ss.pic_smart
            left join t_trx_pm_schedule ll on ll.site_id = ss.site_id  and date_part('year', ll.visit_date) = '$year_ops' AND ll.status IN (0, 1, 2, 3, 4)
            join t_mtr_cluster_ops cc on cc.cluster_ops_id = ss.cluster_ops_id
            join t_mtr_region rr on rr.region_id = cc.region_id
            where $sWhere $sSearch
            group by ss.vendor_id,ss.site_id, ss.site_code, ss.site_name, site_pic_name, rr.region_id, rr.region_code, rr.region_name
            order by $sort $order";

    $data['total'] = $this->db->query($sql)->num_rows();

    $q = $this->db->query($sql." limit $rows offset $offset");
    
    $data['rows'] = $q->result();    
    
    foreach ($data['rows'] as $row) {
      $row->m_01 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.',  1, '.$row->site_id.', '.$row->m_01.')" data="'.$row->site_id.'_01" >'.$row->m_01.'</a>';
      $row->m_02 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.',  2, '.$row->site_id.', '.$row->m_02.')" data="'.$row->site_id.'_02" >'.$row->m_02.'</a>';
      $row->m_03 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.',  3, '.$row->site_id.', '.$row->m_03.')" data="'.$row->site_id.'_03" >'.$row->m_03.'</a>';
      $row->m_04 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.',  4, '.$row->site_id.', '.$row->m_04.')" data="'.$row->site_id.'_04" >'.$row->m_04.'</a>';
      $row->m_05 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.',  5, '.$row->site_id.', '.$row->m_05.')" data="'.$row->site_id.'_05" >'.$row->m_05.'</a>';
      $row->m_06 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.',  6, '.$row->site_id.', '.$row->m_06.')" data="'.$row->site_id.'_06" >'.$row->m_06.'</a>';
      $row->m_07 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.',  7, '.$row->site_id.', '.$row->m_07.')" data="'.$row->site_id.'_07" >'.$row->m_07.'</a>';
      $row->m_08 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.',  8, '.$row->site_id.', '.$row->m_08.')" data="'.$row->site_id.'_08" >'.$row->m_08.'</a>';
      $row->m_09 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.',  9, '.$row->site_id.', '.$row->m_09.')" data="'.$row->site_id.'_09" >'.$row->m_09.'</a>';
      $row->m_10 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.', 10, '.$row->site_id.', '.$row->m_10.')" data="'.$row->site_id.'_10" >'.$row->m_10.'</a>';
      $row->m_11 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.', 11, '.$row->site_id.', '.$row->m_11.')" data="'.$row->site_id.'_11" >'.$row->m_11.'</a>';
      $row->m_12 = '<a href="#" class="pop_up" onclick="siteDetail('.$year_ops.', 12, '.$row->site_id.', '.$row->m_12.')" data="'.$row->site_id.'_12" >'.$row->m_12.'</a>';
      if($allow_create_schedule) {
        $strAction='<span class="button_add_schedule btn btn-default btn-small btn-icon glyphicons circle_plus" onclick="addSchedule('.$row->site_id.', '.$year_ops.')" ><i></i>Schedule</span>';
        $row->btnAction = $strAction;
      }
    }
    return $data;
  }  

  public function getUserSchedule($cluster_ops_id, $year_ops, $month_ops, $search, $page, $rows, $opr_region, $territoryType, $sort, $order)
  {
    $offset = ($page-1) * $rows;
    $sWhere = '';
    if($territoryType == 'region') {
      $sWhere .= "rr.region_id = $opr_region and ";
    }
    else {
      $sWhere .= "ss.cluster_ops_id = $cluster_ops_id and ";
    }
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere .= "(lower(uu.user_id) like '%".$keyword."%' or lower(uu.user_name) like '%".$keyword."%') and";
    }    
    $sWhere = substr($sWhere, 0, -4);
    $sql = "SELECT uu.user_name, uu.user_id,
            count(case when (date_part('month', visit_date) = 1) then 1 end) as m_01, 
            count(case when (date_part('month', visit_date) = 2) then 1 end) as m_02, 
            count(case when (date_part('month', visit_date) = 3) then 1 end) as m_03, 
            count(case when (date_part('month', visit_date) = 4) then 1 end) as m_04, 
            count(case when (date_part('month', visit_date) = 5) then 1 end) as m_05, 
            count(case when (date_part('month', visit_date) = 6) then 1 end) as m_06, 
            count(case when (date_part('month', visit_date) = 7) then 1 end) as m_07, 
            count(case when (date_part('month', visit_date) = 8) then 1 end) as m_08, 
            count(case when (date_part('month', visit_date) = 9) then 1 end) as m_09, 
            count(case when (date_part('month', visit_date) = 10) then 1 end) as m_10, 
            count(case when (date_part('month', visit_date) = 11) then 1 end) as m_11, 
            count(case when (date_part('month', visit_date) = 12) then 1 end) as m_12
            FROM t_mtr_site ss
            LEFT JOIN t_trx_pm_schedule ll ON ll.site_id = ss.site_id and date_part('year', ll.visit_date) = '$year_ops' 
            JOIN t_mtr_user uu ON uu.user_id = ll.user_id
            JOIN t_mtr_cluster_ops cc ON cc.cluster_ops_id = ss.cluster_ops_id
            JOIN t_mtr_region rr ON rr.region_id = cc.region_id
            WHERE date_part('year',ll.visit_date) = '$year_ops' AND ll.status IN (0, 1, 2, 3, 4) AND ss.status = 1
            AND ss.active = 1 and $sWhere
            GROUP BY uu.user_name, uu.user_id
            ORDER BY $sort $order
            ";
    $data['total'] = $this->db->query($sql)->num_rows();

    $q = $this->db->query($sql." limit $rows offset $offset");
    $data['rows'] = $q->result();   

    foreach ($data['rows'] as $row) {
      $row->m_01 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.',  1, \''.$row->user_id.'\', '.$row->m_01.')" data="'.$row->user_id.'_01" >'.$row->m_01.'</a>';
      $row->m_02 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.',  2, \''.$row->user_id.'\', '.$row->m_02.')" data="'.$row->user_id.'_02" >'.$row->m_02.'</a>';
      $row->m_03 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.',  3, \''.$row->user_id.'\', '.$row->m_03.')" data="'.$row->user_id.'_03" >'.$row->m_03.'</a>';
      $row->m_04 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.',  4, \''.$row->user_id.'\', '.$row->m_04.')" data="'.$row->user_id.'_04" >'.$row->m_04.'</a>';
      $row->m_05 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.',  5, \''.$row->user_id.'\', '.$row->m_05.')" data="'.$row->user_id.'_05" >'.$row->m_05.'</a>';
      $row->m_06 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.',  6, \''.$row->user_id.'\', '.$row->m_06.')" data="'.$row->user_id.'_06" >'.$row->m_06.'</a>';
      $row->m_07 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.',  7, \''.$row->user_id.'\', '.$row->m_07.')" data="'.$row->user_id.'_07" >'.$row->m_07.'</a>';
      $row->m_08 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.',  8, \''.$row->user_id.'\', '.$row->m_08.')" data="'.$row->user_id.'_08" >'.$row->m_08.'</a>';
      $row->m_09 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.',  9, \''.$row->user_id.'\', '.$row->m_09.')" data="'.$row->user_id.'_09" >'.$row->m_09.'</a>';
      $row->m_10 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.', 10, \''.$row->user_id.'\', '.$row->m_10.')" data="'.$row->user_id.'_10" >'.$row->m_10.'</a>';
      $row->m_11 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.', 11, \''.$row->user_id.'\', '.$row->m_11.')" data="'.$row->user_id.'_11" >'.$row->m_11.'</a>';
      $row->m_12 = '<a href="#" class="pop_up" onclick="userDetail('.$year_ops.', 12, \''.$row->user_id.'\', '.$row->m_12.')" data="'.$row->user_id.'_12" >'.$row->m_12.'</a>';
     // $strAction='<span class="btn btn-default btn-small btn-icon glyphicons circle_plus" onclick="addSchedule('.$row->site_id.', '.$year_ops.')" ><i></i>Add Schedule</span>';
      $row->btnAction = '';
    }
    return $data;
  }

  public function revList($opr_year, $opr_month, $opr_cluster, $search, $page, $rows, $opr_region, $territoryType)
  {
    $offset = ($page-1) * $rows;
    $sWhere = '';
    if($territoryType == 'region') {
      $sWhere = "AND rr.region_id = $opr_region ";
    }
    else {
      $sWhere = "AND cc.cluster_ops_id = $opr_cluster ";
    }
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere .= "AND (lower(uu.user_id) like '%$keyword%' or lower(uu.user_name) like '%$keyword%' 
        or lower(ss.site_code) like '%$keyword%' or lower(ss.site_name) like '%$keyword%')";
    }
    $q = "SELECT count(*) FROM t_trx_pm_schedule vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_pm_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          JOIN t_mtr_cluster_ops cc on cc.cluster_ops_id = ss.cluster_ops_id
          JOIN t_mtr_region rr on rr.region_id = cc.region_id
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere
          UNION
          SELECT count(*) FROM t_trx_pm_schedule_history vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_pm_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          JOIN t_mtr_cluster_ops cc on cc.cluster_ops_id = ss.cluster_ops_id
          JOIN t_mtr_region rr on rr.region_id = cc.region_id
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere";

    $tt = $this->db->query($q)->result_array();
    $d  = 0;
    foreach ($tt as $k) {
      $d = $d + $k['count'];
    }
    $data['total'] = $d ;

    $r = "SELECT vv.visit_id, vv.parent_id, ss.site_id, ss.site_code, ss.site_name, ss.vendor_id, vv.user_id, uu.user_name, vv.visit_type, to_char(vv.visit_date, 'DD MON YYYY') as visit_plan, 
          CASE WHEN vv.status = -5 THEN 'Canceled Schedule' WHEN vv.status = 10 THEN 'Edited Schedule' WHEN vv.status = 11 THEN 'Reschedule' ELSE 'Declined' END AS visit_status,
            cf.reason_desc remarks, ll.lookup_desc AS reason_group, to_char(vv.updated_on, 'DD MON YYYY HH24:MI:SS.SSSS') created_on
          FROM t_trx_pm_schedule vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_pm_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          JOIN t_mtr_cluster_ops cc on cc.cluster_ops_id = ss.cluster_ops_id
          JOIN t_mtr_region rr on rr.region_id = cc.region_id
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere
          UNION
          SELECT vv.visit_id, vv.parent_id, ss.site_id, ss.site_code, ss.site_name, ss.vendor_id, vv.user_id, uu.user_name, vv.visit_type, to_char(vv.visit_date, 'DD MON YYYY') as visit_plan, 
          CASE WHEN vv.status = -5 THEN 'Canceled Schedule' WHEN vv.status = 10 THEN 'Edited Schedule' WHEN vv.status = 11 THEN 'Reschedule' ELSE 'Declined' END AS visit_status,
            cf.reason_desc remarks, ll.lookup_desc AS reason_group, to_char(vv.updated_on, 'DD MON YYYY HH24:MI:SS.SSSS') created_on
          FROM t_trx_pm_schedule_history vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_pm_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          JOIN t_mtr_cluster_ops cc on cc.cluster_ops_id = ss.cluster_ops_id
          JOIN t_mtr_region rr on rr.region_id = cc.region_id
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere
          ORDER BY site_id, created_on DESC LIMIT $rows OFFSET $offset";
    $data['rows'] = $this->db->query($r)->result_array();
    $data['sql']  = $r;
    return $data;

  }

  public function canceledList($opr_year, $opr_month, $opr_cluster, $search, $page, $rows)
  {
    $offset = ($page-1) * $rows;
    $sWhere = '';
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere = "AND (lower(uu.user_id) like '%$keyword%' or lower(uu.user_name) like '%$keyword%' 
        or lower(ss.site_code) like '%$keyword%' or lower(ss.site_name) like '%$keyword%')";
    }
    $q = "SELECT count(*) FROM  t_trx_pm_schedule vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_pm_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' and vv.status in (2, -5) $sWhere";

    $tt = $this->db->query($q)->result_array();
    $d  = 0;
    foreach ($tt as $k) {
      $d = $d + $k['count'];
    }
    $data['total'] = $d ;

    $r = "SELECT vv.visit_id, vv.parent_id, ss.site_id, ss.site_code, ss.site_name, ss.vendor_id, vv.user_id, uu.user_name, vv.visit_type, to_char(vv.visit_date, 'DD MON YYYY') as visit_plan, 
          CASE WHEN vv.status = -5 THEN 'Canceled Schedule' WHEN vv.status = 10 THEN 'Edited Schedule' WHEN vv.status = 11 THEN 'Reschedule' ELSE 'Declined' END AS visit_status,
          cf.reason_desc remarks, ll.lookup_desc AS reason_group, to_char(vv.updated_on, 'DD MON YYYY HH24:MI:SS.SSSS') created_on
          FROM  t_trx_pm_schedule vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_pm_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' and vv.status in (2, -5) $sWhere
          ORDER BY site_id, created_on DESC LIMIT $rows OFFSET $offset";
    $data['rows'] = $this->db->query($r)->result_array();
    return $data;
  }

  public function export_bysites($param)
  {
    $year_ops  = $param['opr_year'];
    $this->db->select("rr.region_name, ss.vendor_id, ss.site_code site_id, ss.site_name");
    $this->db->select("count(case when (date_part('month', visit_date) = 1) then 1 end) as Jan", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 2) then 1 end) as Feb", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 3) then 1 end) as Mar", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 4) then 1 end) as Apr", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 5) then 1 end) as May", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 6) then 1 end) as Jun", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 7) then 1 end) as Jul", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 8) then 1 end) as Aug", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 9) then 1 end) as Sept", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 10) then 1 end) as Oct", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 11) then 1 end) as Nov", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 12) then 1 end) as Dec", false);
    $this->db->join("t_trx_pm_schedule ll", "ll.site_id = ss.site_id  and date_part('year', ll.visit_date) = '$year_ops' AND ll.status IN (0, 1, 2, 3, 4) ", 'left');
    $this->db->join("t_mtr_lookup lo", "lo.lookup_value = CAST(ss.site_profile AS VARCHAR) AND lo.lookup_key = 'site_profile'", 'left');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    if($param['opr_cluster']) {
      $this->db->where('ss.cluster_ops_id', $param['opr_cluster']);
    }
    else {
      $this->db->where('rr.region_id', $param['opr_region']);
    }
    if($param['opr_month'] != '') {
      $this->db->where("date_part('month', ll.visit_date) = $month_ops");
    }

    //$this->db->where('lo.lookup_key', 'site_profile');
    $this->db->where('ss.status', 1);
    $this->db->where('ss.active', 1);
    $this->db->group_by('ss.vendor_id,ss.site_id, ss.site_code, ss.site_name, rr.region_id, rr.region_code, rr.region_name');
    $this->db->order_by('ss.site_id');
    if($param['keyword'] != '') {
      $keyword = strtolower($param['keyword']);
      $sWhere = "(lower(ss.site_name) like '%$keyword%' or lower(ss.site_code) like '%$keyword%' or lower(ss.vendor_id) like '%$keyword%')";
      $this->db->where($sWhere);
    }
    $q = $this->db->get('t_mtr_site ss');

    return $q;
  }

  public function export_bypic($param, $status)
  {
    $year_ops  = $param['opr_year'];
    $this->db->select('uu.user_id,uu.user_name');
    $this->db->select("count(case when (date_part('month', visit_date) = 1) then 1 end) as Jan", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 2) then 1 end) as Feb", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 3) then 1 end) as Mar", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 4) then 1 end) as Apr", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 5) then 1 end) as May", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 6) then 1 end) as Jun", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 7) then 1 end) as Jul", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 8) then 1 end) as Aug", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 9) then 1 end) as Sept", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 10) then 1 end) as Oct", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 11) then 1 end) as Nov", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 12) then 1 end) as Dec", false);
    $this->db->join("t_trx_pm_schedule ll", "ll.site_id = ss.site_id  and date_part('year', ll.visit_date) = '$year_ops' ", 'left');
    $this->db->join('t_mtr_user uu', 'uu.user_id = ll.user_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('ss.cluster_ops_id', $param['opr_cluster']);
    $this->db->where("date_part('year',ll.visit_date)", $year_ops);
    if($status == 0) {
      $this->db->where_in('ll.status', array(0,1,2,3,4));
    }
    else {
      $this->db->where('ll.status', $status);
    }
    $this->db->group_by('uu.user_name,uu.user_id');
    $this->db->order_by('uu.user_id', 'asc');
    if($param['keyword'] != '' AND isset($paramp['keyword'])) {
      $keyword = strtolower($paramp['keyword']);
      $sWhere = "(lower(uu.user_id) = '$keyword' or lower(uu.user_name) like '%$keyword%' )";
      $this->db->where($sWhere);
    }
    $q = $this->db->get('t_mtr_site ss');
    return $q;
  }

  public function export_revision($param)
  {
    $opr_year = $param['opr_year'];
    $opr_month = $param['opr_month'];
    $region_id = $param['opr_cluster'];
    $sWhere = " AND ss.cluster_ops_id = $region_id ";

    if($param['keyword'] != '') {
      $keyword = strtolower($param['keyword']);
      $sWhere .= "AND (lower(uu.user_id) like '%$keyword%' or lower(uu.user_name) like '%$keyword%' 
        or lower(ss.site_code) like '%$keyword%' or lower(ss.site_name) like '%$keyword%')";
    }
    $r = "SELECT ss.site_code, ss.site_name, ss.vendor_id, vv.user_id, uu.user_name, vv.visit_type, to_char(vv.visit_date, 'DD MON YYYY') as visit_plan, 
          CASE WHEN vv.status = -5 THEN 'Canceled Schedule' WHEN vv.status = 10 THEN 'Edited Schedule' WHEN vv.status = 11 THEN 'Reschedule' END AS visit_status,
            cf.reason_desc remarks, ll.lookup_desc AS reason_group, to_char(vv.updated_on, 'DD MON YYYY HH24:MI:SS.SSSS') created_on
          FROM t_trx_pm_schedule vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_pm_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere
          UNION
          SELECT ss.site_code, ss.site_name, ss.vendor_id, vv.user_id, uu.user_name, vv.visit_type, to_char(vv.visit_date, 'DD MON YYYY') as visit_plan, 
          CASE WHEN vv.status = -5 THEN 'Canceled Schedule' WHEN vv.status = 10 THEN 'Edited Schedule' WHEN vv.status = 11 THEN 'Reschedule' END AS visit_status,
            cf.reason_desc remarks, ll.lookup_desc AS reason_group, to_char(vv.updated_on, 'DD MON YYYY HH24:MI:SS.SSSS') created_on
          FROM t_trx_pm_schedule_history vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_pm_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere
          ORDER BY site_code, created_on ";
    $q = $this->db->query($r);
    return $q;
  }

  public function export_summary($param, $status)
  {
    $opr_year  = $param['opr_year'];
    $opr_month = $param['opr_month'];
    $opr_cluster = $param['opr_cluster'];
    $opr_region = $param['opr_region'];

    $this->db->select('rr.region_name, cc.cluster_name');
    $this->db->select('st.site_code site_id, st.vendor_id, st.site_name, pm.user_id, ');
    $this->db->select('uu.user_name, pm.priority, pm.visit_type, to_char(pm.visit_date, \'DD MON YYYY\') visit_plan, pm.last_visited_date visit_date', false);
    $this->db->select("CASE WHEN pm.status = 0 THEN 'Unconfirmed' WHEN pm.status = 1 THEN 'Confirmed - Unvisited' WHEN pm.status = 2 THEN 'Declined - Unvisited' WHEN pm.status = 3 THEN 'Visited' WHEN pm.status = 4 THEN 'Unschedule' WHEN pm.status = 5 THEN 'Rescheduled' END AS pm_status", false);
    $this->db->join('t_mtr_user uu', 'uu.user_id = pm.user_id');
    $this->db->join('t_mtr_site st', 'st.site_id = pm.site_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = st.cluster_ops_id');
    // $this->db->join('t_mtr_region rr', 'rr.region_id = st.region_id', 'left');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    if($param['territorytype'] == 'region') {
      $this->db->where('rr.region_id', $opr_region);
    }
    else {
      $this->db->where('cc.cluster_ops_id', $opr_cluster);
    }
    $this->db->where("date_part('month', pm.visit_date) =", $opr_month);
    $this->db->where("date_part('year', pm.visit_date) =", $opr_year);
    // $this->db->where_in('pm.status', array(0,1,2,3,4));
    
    if($status == 0)
      { $this->db->where_in('pm.status', array(0,1)); }
    else
      { $this->db->where('pm.status', $status); }
    $t = $this->db->get('t_trx_pm_schedule pm');
    return $t;
  }

  public function siteProperties()
  {
    $this->db->select('cc.checklist_id, cc.checklist_group group_id, cc.checklist_name, cc.interface');
    $this->db->join('t_mtr_checklist_group gg', 'gg.checklist_group_id = cc.checklist_group');
    $this->db->where('gg.parent_id !=', 0);
    $this->db->where('gg.group_key', 'prev.maintenance');
    $this->db->where('gg.status', 1);
    $this->db->where('cc.status', 1);
    $this->db->order_by('cc.checklist_group, cc.checklist_group, gg.order_number, cc.order_number');
    $q = $this->db->get('t_mtr_checklist cc');
    $data["item"] = $q->result_array();

    $this->db->select('checklist_group_id group_id, group_name, parent_id');
    $this->db->where('checklist_group_id !=', 1);
    $this->db->where('group_key', 'prev.maintenance');
    $this->db->where('status', 1);
    $this->db->order_by('order_number, parent_id, checklist_group_id');
    $q = $this->db->get('t_mtr_checklist_group');
    $data["group"] = $q->result_array();

    $this->db->select('rr.checklist_id, oo.option_id, oo.option_value, oo.locked');
    $this->db->join('t_mtr_checklist_template rr', 'rr.option_id = oo.option_id');
    $this->db->where('oo.option_key', 'pm_visit_type');
    $this->db->where('oo.status', 1);
    $q = $this->db->get('t_mtr_checklist_options oo');
    $data["options"] = $q->result_array();

    return $data;
  }

  public function getSitePic($site_id)
  {
    $this->db->select('ss.site_address, ss.latitude, ss.longitude, rr.region_id, rr.region_code, rr.region_name');
    $this->db->select('ss.site_id, ss.site_code, ss.vendor_id, ss.site_name, ss.pic_smart, f_get_user(ss.pic_smart) pic_name');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('ss.site_id', $site_id);
    $q = $this->db->get('t_mtr_site ss');
    return $q->row();
  }

  public function reschedule_data_prep($visit_id)
  {
    // $data = $this->siteProperties();

    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('vv.visit_id', $visit_id);
    $q = $this->db->get('t_trx_pm_schedule vv');
    $data['visit_info'] = $q->row_array();

    $this->db->select('sc.checklist_id');
    $this->db->where('sc.visit_id', $visit_id);
    $q = $this->db->get('t_trx_pm_schedule_checklist sc');
    $data['visit_item'] = $q->result_array();

    return $data;
  }

  public function getRegionPic($site_id)
  {
    $sql = "
      SELECT ss.pic_smart user_id, f_get_user(ss.pic_smart) user_name, concat_ws(' | ', ss.pic_smart, f_get_user(ss.pic_smart)) user_label,
      cc.cluster_ops_id FROM t_mtr_site ss 
      JOIN t_mtr_cluster_ops cc ON cc.cluster_ops_id = ss.cluster_ops_id
      JOIN t_mtr_region rr ON rr.region_id = cc.region_id
      WHERE ss.site_id = $site_id
      UNION
      SELECT DISTINCT uu.user_id,  uu.user_name, concat_ws(' | ', uu.user_id, uu.user_name) user_label, null::int as cluster_ops_id
      FROM t_mtr_user_multi_mobile mm
      JOIN t_mtr_user uu ON uu.user_id = mm.user_id and uu.status=1
      JOIN t_mtr_user_territory tt on tt.user_id = mm.user_id
      WHERE mm.mobile_app_type = 1 and tt.region_id = (SELECT rr.region_id from t_mtr_site ss
        JOIN t_mtr_cluster_ops cc ON cc.cluster_ops_id = ss.cluster_ops_id
        JOIN t_mtr_region rr ON rr.region_id = cc.region_id WHERE ss.site_id = $site_id) 
      order by user_id";

    $q = $this->db->query($sql);
    return $q->result();
  }

  public function getVisitDate($table, $site_id, $month_ops, $year_ops)
  {
    // $this->db->select("date_part('day', visit_date) date_visit", false);
    $this->db->select("visit_date", false);
    $this->db->where('site_id', $site_id);
    $this->db->where("date_part('month', visit_date) =", $month_ops, false);
    $this->db->where("date_part('year', visit_date) =", $year_ops, false);
    $this->db->where_in("status", array(0,1,2,3,4));
    $q = $this->db->get($table);
    // echo $this->db->last_query();
    return $q->result();
  }

  public function getPicRegionCluster($user_id)
  {
    $this->db->select('uu.user_group_id, tt.region_id, tt.cluster_ops_id');
    $this->db->join('t_mtr_user_territory tt', 'tt.user_id = uu.user_id');
    $this->db->where('uu.user_id', $user_id);
    $q = $this->db->get('t_mtr_user uu');
    return $q->row_array();
  }

  public function lookupOption($lookup_key)
  {
    $this->db->select('option_id, option_key, option_value, option_label, status, locked');
    $this->db->where('option_key', $lookup_key);
    $this->db->where('status', 1);
    $this->db->order_by('order_number', 'asc');
    $q = $this->db->get('t_mtr_checklist_options');
    return $q->result();
  }

  public function userVisitSummary($data)
  {
    $year_ops = $data['opr_year'];
    $cluster_ops = $data['opr_cluster'];
    $keyword = $data['q'];
    if($keyword != '') {
      $sWhere = " ms.site_id = '$keyword' or uu.user_id = '$keyword' or lower(uu.user_name) like '%keyword%' ";
    }
    else {
      $sWhere = false;
    }

    $this->db->select('uu.user_id, uu.user_name, ms.site_code, ms.site_name, ss.visit_date, ss.status status_code, ss.scheduled scheduled_code');
    $this->db->select("case when ss.status = 0 then 'Initial'
                       when ss.status = 1 then 'Approve'
                       else 'Visited' end as status
                      ", false);
    // $this->db->select('rr.region_id, rr.region_code, rr.region_name');
    $this->db->select("case when ss.scheduled = 1 then 'Scheduled' else 'Unscheduled' end as scheduled", false);
    $this->db->join('t_mtr_user_territory tt', 'uu.user_id = tt.user_id ');
    $this->db->join("t_trx_pm_schedule ss", "ss.user_id = uu.user_id ", "left");
    $this->db->join('t_mtr_site ms', 'ms.site_id = ss.site_id');
    // $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    // $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('uu.user_group_id', 11);
    if($cluster_ops != '' )
      { $this->db->where('tt.cluster_ops_id', "$cluster_ops", false ); }
    if($sWhere) 
      { $this->db->where($sWhere); }
    $this->db->order_by('uu.user_id');
    $q = $this->db->get('t_mtr_user uu ', $data["rows"], (($data["page"] - 1) * $data["rows"]) );

    // baris berikut khusus mengitung total record saja untuk menghasilkan pagination
    $this->db->select('uu.user_id');
    $this->db->join('t_mtr_user_territory tt', 'uu.user_id = tt.user_id ');
    $this->db->join("t_trx_pm_schedule ss", "ss.user_id = uu.user_id ", "left");
    $this->db->join('t_mtr_site ms', 'ms.site_id = ss.site_id');
    $this->db->where('uu.user_group_id', 11);
    if($cluster_ops != '' )
      { $this->db->where('tt.cluster_ops_id', "$cluster_ops", false ); }
    $t = $this->db->get('t_mtr_user uu');

    $x = $q->result_array();
    for ($i=0; $i < count($x); $i++) { 
      if($x[$i]['scheduled_code'] != 0 && $x[$i]['status_code'] != 3)
      $x[$i]['action']='<a href="#" class="btn btn-small glyphicons circle_ok btn-danger validate" ><i></i>Reschedule</a>';
      # code...
    }

    $r['total'] = $t->num_rows();
    $r['rows'] = $x;

    return $r;
  }

  public function getChecklistGroup($group)
  {
    $this->db->where('status', 1);
    $this->db->where('parent_id', 1);
    $this->db->order_by('order_number');
    $q = $this->db->get('t_mtr_checklist_group');
    return $q->result();
  }

  public function getSiteSummary($opr_year,$opr_month,$opr_cluster,$sch_cond,$search,$page,$rows,$opr_region,$territorytype)
  {
    $this->db->select('rr.region_id, rr.region_code, rr.region_name');
    $this->db->select('cc.cluster_ops_id, cc.cluster_name, st.vendor_id');
    $this->db->select('pm.visit_id, pm.site_id, st.site_code, st.site_name, pm.user_id, ');
    $this->db->select('uu.user_name, pm.priority, pm.visit_type, to_char(pm.visit_date, \'DD MON YYYY\') visit_plan, pm.last_visited_date visit_date', false);
    $this->db->select("CASE WHEN pm.status = 0 THEN 'Unconfirmed' WHEN pm.status = 1 THEN 'Confirmed - Unvisited' WHEN pm.status = 2 THEN 'Declined - Unvisited' WHEN pm.status = 3 THEN 'Visited' WHEN pm.status = 4 THEN 'Unschedule' WHEN pm.status = 5 THEN 'Rescheduled' END AS pm_status", false);
    $this->db->select('pm.status');
    $this->db->join('t_mtr_user uu', 'uu.user_id = pm.user_id');
    $this->db->join('t_mtr_site st', 'st.site_id = pm.site_id');
    $this->db->select('case when is_code_show = 1 then pm.security_code_checkin else null end as security_code_checkin
                        , case when is_code_show = 1 then pm.security_code_checkout else null end as security_code_checkout ',false);
    
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = st.cluster_ops_id');
    // $this->db->join('t_mtr_region rr', 'rr.region_id = st.region_id', 'left');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    if($territorytype == 'region'){
      $this->db->where('rr.region_id', $opr_region);
    }
    else {
      $this->db->where('cc.cluster_ops_id', $opr_cluster);
    }
    $this->db->where("date_part('month', pm.visit_date) =", $opr_month);
    $this->db->where("date_part('year', pm.visit_date) =", $opr_year);
    $this->db->where_in('pm.status', array(0,1,2,3,4));
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere = "(lower(st.site_name) like '%$keyword%' or uu.user_id = '$search' or lower(uu.user_name) like '%$keyword%' or lower(st.site_code) like '%$keyword%' or lower(st.vendor_id) like '%$keyword%')";
      $this->db->where($sWhere);
    }
    if($sch_cond == 1)
      { $this->db->where_in('pm.status', array(0,1)); }
    else
      { $this->db->where('pm.status', $sch_cond); }
    $this->db->order_by('pm.visit_id', 'desc');
    $q = $this->db->get('t_trx_pm_schedule pm', $rows, (($page - 1) * $rows));
    // echo $this->db->last_query();

    $this->db->join('t_mtr_user uu', 'uu.user_id = pm.user_id');
    $this->db->join('t_mtr_site st', 'st.site_id = pm.site_id');
    
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = st.cluster_ops_id');
    // $this->db->join('t_mtr_region rr', 'rr.region_id = st.region_id', 'left');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    if($territorytype == 'region'){
      $this->db->where('rr.region_id', $opr_region);
    }
    else {
      $this->db->where('cc.cluster_ops_id', $opr_cluster);
    }
    $this->db->where("date_part('month', pm.visit_date) =", $opr_month);
    $this->db->where("date_part('year', pm.visit_date) =", $opr_year);
    $this->db->where_in('pm.status', array(0,1,2,3,4));
    
    if($sch_cond == 1)
      { $this->db->where_in('pm.status', array(0,1)); }
    else
      { $this->db->where('pm.status', $sch_cond); }
    $t = $this->db->get('t_trx_pm_schedule pm');
    $x = $q->result_array();

    for ($i=0; $i < count($x); $i++) { 
      $x[$i]['action']='<a href="#" class="button_pm_summary btn btn-small glyphicons circle_ok btn-danger validate" onclick="detailVisit('.$x[$i]['visit_id'].')" ><i></i>Detail Info</a>';
    }

    $r["rows"]  = $x;
    $r["total"] = $t->num_rows();
    return $r;
  }

  public function getChecklist($visit_id, $checklist_group, $group_key)
  {
    $this->db->select('checklist_group_id, parent_id, group_name ');
    $this->db->where('status', 1);
    $this->db->where('group_key', $group_key);
    $this->db->where('parent_id !=', 0);
    $this->db->order_by('order_number');
    // $this->db->where('parent_id', $checklist_group);
    $q = $this->db->get('t_mtr_checklist_group');
    $data['group'] = $q->result_array();

    $this->db->select('pc.visit_id, pc.checklist_id, cc.checklist_name, cc.checklist_group, cr.pm_result, cr.pm_score, cc.interface');
    $this->db->select('ps.status pm_status, cr.approved');
    $this->db->join('t_mtr_checklist cc', 'cc.checklist_id = pc.checklist_id');
    $this->db->join('t_trx_pm_checklist_result cr', "cr.checklist_id = cc.checklist_id and cr.visit_id = $visit_id", 'left');
    $this->db->join('t_trx_pm_schedule ps', 'ps.visit_id = pc.visit_id ');
    $this->db->where('pc.visit_id', $visit_id);
    $this->db->order_by('cc.order_number');
    $q = $this->db->get('t_trx_pm_schedule_checklist pc');
    $data['checklist'] = $q->result_array();

    $this->db->select('vv.is_code_show,vv.security_code_remark,vv.security_code_generate_date,vv.security_code_generate_by,ss.site_code, ss.site_id, ss.site_name, ss.vendor_id, ss.site_address, ss.longitude, ss.latitude');
    $this->db->select('vv.security_code_checkin, vv.security_code_checkout, vv.visit_date, vv.visit_id');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->where('vv.visit_id', $visit_id);
    $q = $this->db->get('t_trx_pm_schedule vv');
    $data['site'] = $q->row_array();

    return $data;
  }

  public function visit_group($type, $year, $month, $cluster_id, $param, $territoryType)
  {
    $this->db->select('rr.region_name, cc.cluster_name, ss.site_id, ss.site_code, ss.site_name, vv.visit_id, uu.user_name, vv.visit_date as visit_plan, vv.last_visited_date, vv.status, vv.last_visited_date as visit_date, vv.remark');
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    if($territoryType == 'cluster') {
        $this->db->where('ss.cluster_ops_id', $cluster_id);
    }
    else {
        $this->db->where('ss.region_id', $cluster_id);
    }
    $this->db->where($param);
    $this->db->where("date_part('year', visit_date) =", $year);
    $this->db->where("date_part('month', visit_date) =", $month);
    $this->db->where_in('vv.status', array(0,1,2,3,4));
    $this->db->order_by('vv.visit_date', 'desc');
    $q = $this->db->get('t_trx_pm_schedule vv');
    return $q->result();
  }

  public function visit_group_dg($type, $year, $month, $cluster_id, $param,$territoryType)
  {
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');

    if($territoryType == 'cluster') {
        $this->db->where('ss.cluster_ops_id', $cluster_id);
    }
    else {
        $this->db->where('ss.region_id', $cluster_id);
    }

//    $this->db->where('ss.cluster_ops_id', $cluster_id);
    $this->db->where($param);
    $this->db->where("date_part('year', visit_date) =", $year);
    $this->db->where("date_part('month', visit_date) =", $month);
    $this->db->where_in('vv.status', array(0,1,2,3,4));
    $this->db->order_by('vv.visit_date', 'desc');
    $q = $this->db->get('t_trx_pm_schedule vv' );
    $data['total'] = $q->num_rows();

    $this->db->select('rr.region_name, cc.cluster_name, ss.site_id, ss.vendor_id, ss.site_code, ss.site_name, vv.visit_id, vv.parent_id, uu.user_name, to_char(vv.visit_date, \'DD MON YYYY\') as visit_plan, vv.last_visited_date, to_char(vv.last_visited_date, \'DD MON YYYY\') visited_date, vv.status, vv.remark', false);
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    if($territoryType == 'cluster') {
        $this->db->where('ss.cluster_ops_id', $cluster_id);
    }
    else {
        $this->db->where('ss.region_id', $cluster_id);
    }
    $this->db->where($param);
    $this->db->where("date_part('year', visit_date) =", $year);
    $this->db->where("date_part('month', visit_date) =", $month);
    $this->db->where_in('vv.status', array(0,1,2,3,4));
    $this->db->order_by('vv.visit_date', 'desc');
    $q = $this->db->get('t_trx_pm_schedule vv' );
    foreach ($q->result() as $key) {
      $key->visit_status = '';
      switch ($key->status) {
        case 0:  $key->visit_status = "<span style='color:blue'>Not yet confirmed</span>"; break;
        case 1:  $key->visit_status = "<span style='color:green'>Confirmed Schedule</span>"; break;
        case 2:  $key->visit_status = "<span style='color:orange'>Declined Schedule</span>"; break;
        case 3:  $key->visit_status = "<span style='color:black'>Visited Schedule</span>"; break;
        case 4:  $key->visit_status = "<span style='color:brown'>Unschedule</span>"; break;
        case 5:  $key->visit_status = "<span style='color:#CC0000'>Rescheduled</span>"; break;
        case -5: $key->visit_status = "<span style='color:red'>Canceled Schedule</span>"; break;
       } 
       $key->action_button = '<button class="button_pm_by_user btn btn-small btn-icon glyphicons eye_open" onclick="visitDetail('.$key->visit_id.', \''.$type.'\')"><i></i>Detail</button>';
    }
    $data['rows'] = $q->result();

    return $data;
  }

  public function reschedule_visits($visit_id, $type)
  {
    $this->db->select('rr.region_name, cc.cluster_name, ss.site_id, ss.vendor_id, vv.created_on, to_char(vv.updated_on, \'DD MON YYYY HH24:MI:SS\') updated_on, ss.site_code, ss.site_name, vv.visit_id, uu.user_name, to_char(vv.visit_date, \'DD MON YYYY\') as visit_plan, vv.last_visited_date, vv.status, vv.last_visited_date as visit_date, vv.remark', false);
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('vv.visit_id', $visit_id);
    $this->db->or_where('vv.parent_id', $visit_id);
    $this->db->order_by('vv.created_on', 'asc');
    $q = $this->db->get('t_trx_pm_schedule_history vv' );

    $data['total'] = $q->num_rows();
    foreach ($q->result() as $key) {
      $key->visit_status = '';
      switch ($key->status) {
        case 0:  $key->visit_status = "<span style='color:blue'>Not yet confirmed</span>"; break;
        case 1:  $key->visit_status = "<span style='color:green'>Confirmed Schedule</span>"; break;
        case 2:  $key->visit_status = "<span style='color:orange'>Declined Schedule</span>"; break;
        case 3:  $key->visit_status = "<span style='color:black'>Visited Schedule</span>"; break;
        case 4:  $key->visit_status = "<span style='color:brown'>Unschedule</span>"; break;
        case 5:  $key->visit_status = "<span style='color:#CC0000'>Rescheduled</span>"; break;
        case -5: $key->visit_status = "<span style='color:red'>Canceled Schedule</span>"; break;
        case 10: $key->visit_status = "<span style='color:red'>Edited</span>"; break;
        case 11: $key->visit_status = "<span style='color:red'>Rescheduled</span>"; break;
       } 
       $key->action_button = '<button class="btn btn-small btn-icon glyphicons eye_open" onclick="visitDetail('.$key->visit_id.', \''.$type.'\')"><i></i>Detail</button>';
    }
    $data['rows'] = $q->result();
    return $data;
  }

  public function checkDate($visit_id, $visit_date)
  {
    $this->db->select('visit_date');
    $this->db->where('visit_id', $visit_id);
    $q = $this->db->get('t_trx_pm_schedule')->row()->visit_date;

    if($q == $visit_date) {
      $r = array('status_code' => false, 'status_message' => 'You pick the same date as scheduled before' );
    }
    else {
      $r = array('status_code' => true, 'status_message' => 'Please proceed' );
    }
    return $r;
  }

  public function pmdesktopgrid($region_id, $cluster_id, $datefrom, $dateto, $search, $page, $rows, $sort, $order)
  {
    $offset = ($page-1) * $rows;
    $sWhere1 = 'where ';
    $sWhere2 = 'where ';
    $orderby = "order by vv.visit_date desc";
    if($cluster_id) {
      $sWhere1 .= "cc.cluster_ops_id = $cluster_ops_id and ";
      $sWhere2 .= "cc.cluster_ops_id = $cluster_ops_id and ";
    }
    else {
      $sWhere1 .= "rr.region_id = $region_id and ";
      $sWhere2 .= "rr.region_id = $region_id and ";
    }
    if($datefrom && $dateto) {
      $sWhere1 .= "vv.visit_date between date('$datefrom') and date('$dateto') and ";
      $sWhere2 .= "v1.visit_date between date('$datefrom') and date('$dateto') and ";
    }
    else if((!$dateto OR $dateto == '') && $datefrom) {
      $sWhere1 .= "vv.visit_date = date('$datefrom') and ";
      $sWhere2 .= "v1.visit_date = date('$datefrom') and ";
    }

    if($sort){
      if($sort == 'visit_plan'){
        $sort = 'vv.visit_date';
      }
      $orderby = "order by $sort $order";
    }
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere1 .= "(lower(ss.site_name) like '%".$keyword."%' or lower(ss.site_code) like '%".$keyword."%' or lower(ss.vendor_id) like '%".$keyword."%'
                  or lower(f_get_user(vv.user_id)) like '%".$keyword."%' or lower(vv.user_id) like '%".$keyword."%') and";
      $sWhere2 .= "(lower(ss.site_name) like '%".$keyword."%' or lower(ss.site_code) like '%".$keyword."%' or lower(ss.vendor_id) like '%".$keyword."%'
                  or lower(f_get_user(v1.user_id)) like '%".$keyword."%' or lower(v1.user_id) like '%".$keyword."%') and";
    }
    $sWhere1 = substr($sWhere1, 0, -4);
    $sWhere2 = substr($sWhere2, 0, -4);
    $sql = "select vv.visit_id, rr.region_id, rr.region_name, co.cluster_ops_id, co.cluster_name, 
            ss.site_id, ss.site_code, ss.vendor_id, ss.site_name, vv.user_id, initcap(f_get_user(vv.user_id)) assigned_pic,
            vv.visit_date, to_char(vv.visit_date, 'DD MON YYYY') visit_plan, xd.checklist_desktop_count, xm.checklist_mobile_count,
            vv.status, f_get_user(vv.pm_desktop_by) pm_desktop_by, to_char(vv.pm_desktop_on, 'DD MON YYYY HH24:MI:SS') pm_desktop_on
            from t_trx_pm_schedule vv
            join t_mtr_site ss on ss.site_id = vv.site_id
            join t_mtr_region rr on rr.region_id = ss.region_id
            left join t_mtr_cluster_ops co on co.cluster_ops_id = ss.cluster_ops_id
            join (
              select vc.visit_id, count(vc.checklist_id) checklist_desktop_count
              from t_trx_pm_schedule v1 
              join t_trx_pm_schedule_checklist vc on vc.visit_id = v1.visit_id
              join t_mtr_checklist cc on cc.checklist_id = vc.checklist_id and cc.interface = 'desktop'
              join t_mtr_site ss on ss.site_id = v1.site_id
              join t_mtr_region rr on rr.region_id = ss.region_id
              left join t_mtr_cluster_ops co on co.cluster_ops_id = ss.cluster_ops_id
              $sWhere2
              group by vc.visit_id
            ) xd on xd.visit_id = vv.visit_id
            join (
              select vc.visit_id, count(vc.checklist_id) checklist_mobile_count
              from t_trx_pm_schedule v1 
              join t_trx_pm_schedule_checklist vc on vc.visit_id = v1.visit_id
              join t_mtr_checklist cc on cc.checklist_id = vc.checklist_id and cc.interface = 'android'
              join t_mtr_site ss on ss.site_id = v1.site_id
              join t_mtr_region rr on rr.region_id = ss.region_id
              left join t_mtr_cluster_ops co on co.cluster_ops_id = ss.cluster_ops_id
              $sWhere2
              group by vc.visit_id
            ) xm on xm.visit_id = vv.visit_id
            $sWhere1 $orderby";

    $data['total'] = $this->db->query($sql)->num_rows();
    $q = $this->db->query($sql." limit $rows offset $offset");
    $data['rows'] = $q->result(); 
    // $data['sql'] = $sql; 

    foreach ($data['rows'] as $key) {
        $strAction='<span class="btn btn-default btn-small btn-icon glyphicons pen" onclick="pmdesktopdetail('.$key->visit_id.')" ><i></i>Detail</span>';
        $key->btnAction = $strAction;
        $key->d = $key->checklist_desktop_count;
        $key->m = $key->checklist_mobile_count;
        switch ($key->status) {
          case 0:  $key->visit_status = "<span style='color:blue'>Not yet confirmed</span>"; break;
          case 1:  $key->visit_status = "<span style='color:green'>Confirmed Schedule</span>"; break;
          case 2:  $key->visit_status = "<span style='color:orange'>Declined Schedule</span>"; break;
          case 3:  $key->visit_status = "<span style='color:black'>Visited Schedule</span>"; break;
          case 4:  $key->visit_status = "<span style='color:brown'>Unschedule</span>"; break;
          case -5: $key->visit_status = "<span style='color:red'>Canceled Schedule</span>"; break;
         } 
    }
    return $data; 
  }

  public function pmdesktopdata($visit_id)
  {
    $sql1 = "select vv.visit_id, vv.last_visited_date, to_char(vv.last_visited_date, 'DD MON YYYY') last_visit,
             vv.status, case vv.status when 0 then 'Not yet confirmed' when 1 then 'Confirmed Schedule' when 2 then 'Declined Schedule'
             when 3 then 'Visited Schedule' when 4 then 'Unschedule' else 'Canceled Schedule' end as visit_status, ss.site_address,
             rr.region_id, rr.region_name, co.cluster_ops_id, co.cluster_name, initcap(f_get_user(vv.user_id)) assigned_pic, vv.user_id,
             ss.pic_smart, initcap(f_get_user(ss.pic_smart)) site_pic, ss.site_code, ss.site_id, ss.site_name, ss.vendor_id,
             vv.visit_date, to_char(vv.visit_date, 'DD MON YYYY') visit_date_plan, f_get_user(vv.pm_desktop_by) pm_desktop_by, to_char(vv.pm_desktop_on, 'DD MON YYYY HH24:MI:SS') pm_desktop_on
             from t_trx_pm_schedule vv
             join t_mtr_site ss on ss.site_id = vv.site_id
             join t_mtr_region rr on rr.region_id = ss.region_id
             left join t_mtr_cluster_ops co on co.cluster_ops_id = ss.cluster_ops_id
             where vv.visit_id = $visit_id";
    $x['header'] = $this->db->query($sql1)->row();

    $sql2 = "select cc.checklist_id, cc.checklist_name, ct.type_name checklist_type, cc.checklist_group, cc.has_photo, cc.total_photo,
             cc.mandatory, pr.checklist_result_id, pr.pm_result, cc.interface, f_get_checklistgroup(cc.checklist_group) group_path
             from t_trx_pm_schedule_checklist sc
             join t_mtr_checklist cc on cc.checklist_id = sc.checklist_id
             join t_mtr_checklist_type ct on ct.checklist_type_id = cc.checklist_type
             left join t_trx_pm_checklist_result pr on pr.checklist_id = sc.checklist_id
             and sc.visit_id = pr.visit_id
             where sc.visit_id = $visit_id and cc.interface = 'desktop'";
    $x['checklists'] = $this->db->query($sql2)->result_array();

    $sql3 = "select sc.checklist_id, ca.option_id, ca.option, ca.is_critical
             from t_trx_pm_schedule_checklist sc
             join t_mtr_checklist_answer ca on ca.checklist_id = sc.checklist_id
             where sc.visit_id = $visit_id and ca.active = 1";
    $x['answer'] = $this->db->query($sql3)->result_array();

    return $x;

  }

  public function savepmDesktop($visit_id, $data)
  {

    foreach ($data as $c => $v) {
      $checklist_id[] = $c;
      $x[] = array(
        'visit_id' => $visit_id,
        'checklist_id' => $c,
        'pm_result' => $v,
        'created_on' => date('Y-m-d H:i:s'),
        'created_by' => $this->session->userdata('user_id'),
      );
    }

    $this->db->where('visit_id', $visit_id);
    $this->db->where_in('checklist_id', $checklist_id);
    $d = $this->db->count_all_results('t_trx_pm_checklist_result');

    if($d) {
      $this->db->where('visit_id', $visit_id);
      $this->db->where_in('checklist_id', $checklist_id);
      $this->db->delete('t_trx_pm_checklist_result');
    }
    $this->db->insert_batch('t_trx_pm_checklist_result', $x);
    $ar = $this->db->affected_rows();
    if($ar){
      $pm_desktop = array('pm_desktop_by' => $this->session->userdata('user_id'),
        'pm_desktop_on' => date('Y-m-d H:i:s'));
      $this->db->where('visit_id', $visit_id);
      $this->db->update('t_trx_pm_schedule', $pm_desktop);
    }
    return $ar;
  }

}

/* End of file pm.php */
/* Location: ./application/models/pm/pm.php */