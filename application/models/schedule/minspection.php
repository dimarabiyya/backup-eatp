<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Minspection extends CI_Model {

  public function getSiteList($cluster_ops_id, $year_ops, $search, $page, $rows)
  {
    $this->db->select("ss.site_id, ss.site_code, ss.site_name");
    $this->db->join("t_trx_site_inspection_schedule ll", "ll.site_id = ss.site_id and date_part('year', ll.visit_date) = '$year_ops' AND ll.status IN (0, 1, 2, 3, 4)  " , 'left');
    $this->db->where('ss.region_id', $cluster_ops_id);
    // $this->db->where('ss.status', 0);
    // $this->db->where_in('ll.status', array(0,1,2,3,4));
    $this->db->where('ss.active', 1);
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere = "(lower(ss.site_name) like '%$keyword%' or lower(ss.site_code) = '$keyword' or lower(ss.vendor_id) like '%$keyword%')";
      $this->db->where($sWhere);
    }
    $this->db->group_by('ss.site_id, ss.site_code, ss.site_name');
    $data['total'] = $this->db->get('t_mtr_site ss')->num_rows();

    $offset = ($page-1) * $rows;
    $this->db->select("rr.region_id, rr.region_code, rr.region_name, ss.site_id, ss.site_code, ss.site_name, ss.vendor_id");
    $this->db->select("count(case when (date_part('month', visit_date) = 1) then 1 end) as m_01", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 2) then 1 end) as m_02", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 3) then 1 end) as m_03", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 4) then 1 end) as m_04", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 5) then 1 end) as m_05", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 6) then 1 end) as m_06", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 7) then 1 end) as m_07", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 8) then 1 end) as m_08", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 9) then 1 end) as m_09", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 10) then 1 end) as m_10", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 11) then 1 end) as m_11", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 12) then 1 end) as m_12", false);
    $this->db->join("t_trx_site_inspection_schedule ll", "ll.site_id = ss.site_id  and date_part('year', ll.visit_date) = '$year_ops' AND ll.status IN (0, 1, 2, 3, 4) ", 'left');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    // $this->db->where_in('ll.status', array(0,1,2,3,4));
    $this->db->where('ss.region_id', $cluster_ops_id);
    // $this->db->where('ss.status', 0);
    $this->db->where('ss.active', 1);
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere = "(lower(ss.site_name) like '%$keyword%' or lower(ss.site_code) = '$keyword' or lower(ss.vendor_id) like '%$keyword%')";
      $this->db->where($sWhere);
    }
    $this->db->group_by('ss.site_id, ss.vendor_id, ss.site_code, ss.site_name, rr.region_id, rr.region_code, rr.region_name');
    $q = $this->db->get('t_mtr_site ss', $rows, $offset);
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
      $strAction='<span class="btn btn-default btn-small btn-icon glyphicons circle_plus" onclick="addSchedule('.$row->site_id.', '.$cluster_ops_id.', '.$year_ops.')" ><i></i>Schedule</span>';
      $row->btnAction = $strAction;
    }
    return $data;
  }
  
  public function getUserList($cluster_ops_id, $year_ops, $search, $page, $rows)
  {
   
    $this->db->select('uu.user_name,uu.user_id');
    $this->db->join("t_trx_site_inspection_schedule ll", "ll.site_id = ss.site_id  and date_part('year', ll.visit_date) = '$year_ops' ", 'left');
    $this->db->join('t_mtr_user uu', 'uu.user_id = ll.user_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('ss.region_id', $cluster_ops_id);
    $this->db->where("date_part('year',ll.visit_date)", $year_ops);
    // $this->db->where('ss.status', 0);
    $this->db->where('ss.active', 1);
    $this->db->where_in('ll.status', array(0,1,2,3,4));
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere = "(lower(uu.user_id) like '%$keyword%' or lower(uu.user_name) like '%$keyword%')";
      
      $this->db->where($sWhere);
    }
    $this->db->group_by('uu.user_name,uu.user_id');
    $data['total'] = $this->db->get('t_mtr_site ss')->num_rows();

    $offset = ($page-1) * $rows;
    $this->db->select("count(case when (date_part('month', visit_date) = 1) then 1 end) as m_01", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 2) then 1 end) as m_02", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 3) then 1 end) as m_03", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 4) then 1 end) as m_04", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 5) then 1 end) as m_05", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 6) then 1 end) as m_06", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 7) then 1 end) as m_07", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 8) then 1 end) as m_08", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 9) then 1 end) as m_09", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 10) then 1 end) as m_10", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 11) then 1 end) as m_11", false);
    $this->db->select("count(case when (date_part('month', visit_date) = 12) then 1 end) as m_12", false);

    $this->db->select('uu.user_name,uu.user_id');
    $this->db->join("t_trx_site_inspection_schedule ll", "ll.site_id = ss.site_id  and date_part('year', ll.visit_date) = '$year_ops' ", 'left');
    $this->db->join('t_mtr_user uu', 'uu.user_id = ll.user_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('ss.region_id', $cluster_ops_id);
    $this->db->where("date_part('year',ll.visit_date)", $year_ops);
    $this->db->where_in('ll.status', array(0,1,2,3,4));
    // $this->db->where('ss.status', 0);
    $this->db->where('ss.active', 1);
    $this->db->group_by('uu.user_name,uu.user_id');
    
    $this->db->order_by('uu.user_id', 'asc');
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere = "(lower(uu.user_id) like '%$keyword%' or lower(uu.user_name) like '%$keyword%' )";
      $this->db->where($sWhere);
    }
    $q = $this->db->get('t_mtr_site ss', $rows, $offset);
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
      $row->btnAction = '';$strAction;
    }
    return $data;
  }

  public function export_bysite($param)
  {
    $year_ops = $param['opr_year'];
    $this->db->select("rr.region_name, ss.site_code site_id, ss.site_name, ss.vendor_id");
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
    $this->db->join("t_trx_site_inspection_schedule ll", "ll.site_id = ss.site_id  and date_part('year', ll.visit_date) = '$year_ops' AND ll.status IN (0, 1, 2, 3, 4) ", 'left');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    // $this->db->where_in('ll.status', array(0,1,2,3,4));
    $this->db->where('ss.region_id', $param['opr_cluster']);
    // $this->db->where('ss.status', 0);
    $this->db->where('ss.active', 1);
    if($param['keyword'] != '') {
      $keyword = strtolower($param['keyword']);
      $sWhere = "(lower(ss.site_name) like '%$keyword%' or lower(ss.site_code) = '$keyword' or lower(ss.vendor_id) like '%$keyword%')";
      $this->db->where($sWhere);
    }
    $this->db->group_by('ss.site_id, ss.site_code, ss.site_name, rr.region_id, rr.region_code, rr.region_name');
    $q = $this->db->get('t_mtr_site ss');
    return $q;
  }

  public function export_bypic($param)
  {
    $year_ops = $param['opr_year'];
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

    $this->db->join("t_trx_site_inspection_schedule ll", "ll.site_id = ss.site_id  and date_part('year', ll.visit_date) = '$year_ops' ", 'left');
    $this->db->join('t_mtr_user uu', 'uu.user_id = ll.user_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('ss.region_id', $param['opr_cluster']);
    $this->db->where("date_part('year',ll.visit_date)", $year_ops);
    //$this->db->where("date_part('month',ll.visit_date)", $month_ops);
    $this->db->where_in('ll.status', array(0,1,2,3,4));
    // $this->db->where('ss.status', 0);
    $this->db->where('ss.active', 1);
    
    $this->db->group_by('uu.user_name,uu.user_id');
    
    $this->db->order_by('uu.user_id', 'asc');
    if($param['keyword'] != '') {
      $keyword = strtolower($param['keyword']);
      $sWhere = "(lower(uu.user_id) = '$keyword' or lower(uu.user_name) like '%$keyword%' )";
      $this->db->where($sWhere);
    }
    $q = $this->db->get('t_mtr_site ss');
    return $q;
  }

  public function export_byrev($param)
  {
    $opr_year = $param['opr_year'];
    $opr_month = $param['opr_month'];
    $region_id = $param['opr_cluster'];
    $sWhere = '';
    if($param['keyword'] != '') {
      $keyword = strtolower($param['keyword']);
      $sWhere = "AND (lower(uu.user_id) like '%$keyword%' or lower(uu.user_name) like '%$keyword%' 
        or lower(ss.site_code) like '%$keyword%' or lower(ss.site_name) like '%$keyword%')";
    }
    $r = "SELECT ss.site_code, ss.site_name, ss.vendor_id, vv.user_id, uu.user_name, vv.visit_type, to_char(vv.visit_date, 'DD MON YYYY') as visit_plan, 
          CASE WHEN vv.status = -5 THEN 'Canceled Schedule' WHEN vv.status = 10 THEN 'Edited Schedule' WHEN vv.status = 11 THEN 'Reschedule' ELSE 'Declined' END AS visit_status,
            cf.reason_desc remarks, ll.lookup_desc AS reason_group, to_char(vv.updated_on, 'DD MON YYYY HH24:MI:SS.SSSS') created_on
          FROM t_trx_site_inspection_schedule vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_site_inspection_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE ss.region_id = $region_id and date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere
          UNION
          SELECT ss.site_code, ss.site_name, ss.vendor_id, vv.user_id, uu.user_name, vv.visit_type, to_char(vv.visit_date, 'DD MON YYYY') as visit_plan, 
          CASE WHEN vv.status = -5 THEN 'Canceled Schedule' WHEN vv.status = 10 THEN 'Edited Schedule' WHEN vv.status = 11 THEN 'Reschedule' ELSE 'Declined' END AS visit_status,
            cf.reason_desc remarks, ll.lookup_desc AS reason_group, to_char(vv.updated_on, 'DD MON YYYY HH24:MI:SS.SSSS') created_on
          FROM t_trx_site_inspection_schedule_history vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_site_inspection_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE ss.region_id = $region_id and date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere
          ORDER BY site_id, created_on DESC";
    $q = $this->db->query($r);
    return $q;
  }

  public function revList($opr_year, $opr_month, $opr_cluster, $search, $page, $rows)
  {
    $offset = ($page-1) * $rows;
    $sWhere = '';
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere = "AND (lower(uu.user_id) like '%$keyword%' or lower(uu.user_name) like '%$keyword%' 
        or lower(ss.site_code) like '%$keyword%' or lower(ss.site_name) like '%$keyword%')";
    }
    $q = "SELECT count(*) FROM t_trx_site_inspection_schedule vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_site_inspection_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE ss.region_id = $opr_cluster and date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere
          UNION
          SELECT count(*) FROM t_trx_site_inspection_schedule_history vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_site_inspection_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE ss.region_id = $opr_cluster and date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere";

    $tt = $this->db->query($q)->result_array();
    $d  = 0;
    foreach ($tt as $k) {
      $d = $d + $k['count'];
    }
    $data['total'] = $d ;

    $r = "SELECT vv.visit_id, vv.parent_id, ss.site_id, ss.site_code, ss.site_name, ss.vendor_id, vv.user_id, uu.user_name, vv.visit_type, to_char(vv.visit_date, 'DD MON YYYY') as visit_plan, 
          CASE WHEN vv.status = -5 THEN 'Canceled Schedule' WHEN vv.status = 10 THEN 'Edited Schedule' WHEN vv.status = 11 THEN 'Reschedule' ELSE 'Declined' END AS visit_status,
            cf.reason_desc remarks, ll.lookup_desc AS reason_group, to_char(vv.updated_on, 'DD MON YYYY HH24:MI:SS.SSSS') created_on
          FROM t_trx_site_inspection_schedule vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_site_inspection_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere
          UNION
          SELECT vv.visit_id, vv.parent_id, ss.site_id, ss.site_code, ss.site_name, ss.vendor_id, vv.user_id, uu.user_name, vv.visit_type, to_char(vv.visit_date, 'DD MON YYYY') as visit_plan, 
          CASE WHEN vv.status = -5 THEN 'Canceled Schedule' WHEN vv.status = 10 THEN 'Edited Schedule' WHEN vv.status = 11 THEN 'Reschedule' ELSE 'Declined' END AS visit_status,
            cf.reason_desc remarks, ll.lookup_desc AS reason_group, to_char(vv.updated_on, 'DD MON YYYY HH24:MI:SS.SSSS') created_on
          FROM t_trx_site_inspection_schedule_history vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_site_inspection_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' $sWhere
          ORDER BY site_id, created_on DESC LIMIT $rows OFFSET $offset";
    $data['rows'] = $this->db->query($r)->result_array();
    $data['q'] = $r;
    return $data;
  }

  public function canceledList($opr_year, $opr_month, $opr_region, $search, $page, $rows)
  {
    $offset = ($page-1) * $rows;
    $sWhere = '';
    if($search != '') {
      $keyword = strtolower($search);
      $sWhere = "AND (lower(uu.user_id) like '%$keyword%' or lower(uu.user_name) like '%$keyword%' 
        or lower(ss.site_code) like '%$keyword%' or lower(ss.site_name) like '%$keyword%')";
    }
    $q = "SELECT count(*) FROM  t_trx_site_inspection_schedule vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_site_inspection_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' 
          and ss.region_id = $opr_region and vv.status in (2, -5) $sWhere";

    $tt = $this->db->query($q)->result_array();
    $d  = 0;
    foreach ($tt as $k) {
      $d = $d + $k['count'];
    }
    $data['total'] = $d ;

    $r = "SELECT vv.visit_id, vv.parent_id, ss.site_id, ss.site_code, ss.site_name, ss.vendor_id, vv.user_id, uu.user_name, vv.visit_type, to_char(vv.visit_date, 'DD MON YYYY') as visit_plan, 
          CASE WHEN vv.status = -5 THEN 'Canceled Schedule' WHEN vv.status = 10 THEN 'Edited Schedule' WHEN vv.status = 11 THEN 'Reschedule' ELSE 'Declined' END AS visit_status,
          cf.reason_desc remarks, ll.lookup_desc AS reason_group, to_char(vv.updated_on, 'DD MON YYYY HH24:MI:SS.SSSS') created_on
          FROM  t_trx_site_inspection_schedule vv
          JOIN t_mtr_site ss on ss.site_id = vv.site_id JOIN t_mtr_user uu on uu.user_id = vv.user_id
          JOIN t_trx_site_inspection_schedule_confirm cf on cf.visit_id = vv.visit_id and cf.confirm = vv.status
          JOIN t_mtr_lookup ll on ll.lookup_key = cf.lookup_key and ll.lookup_value = cf.reason_group
          WHERE date_part('year', vv.visit_date) = '$opr_year' and date_part('month', vv.visit_date) = '$opr_month' 
          and ss.region_id = $opr_region and vv.status in (2, -5) $sWhere
          ORDER BY site_id, created_on DESC LIMIT $rows OFFSET $offset";
    $data['rows'] = $this->db->query($r)->result_array();
    return $data;
  }

  public function inspectionProperties()
  {
    $this->db->select('cc.checklist_id, cc.checklist_group group_id, cc.checklist_name, cc.interface');
    $this->db->join('t_mtr_checklist_group gg', 'gg.checklist_group_id = cc.checklist_group');
    $this->db->where('group_key', 'si');
    $this->db->where('gg.status', 1);
    $this->db->where('cc.status', 1);
    $this->db->order_by('cc.checklist_group, cc.order_number');
    $q = $this->db->get('t_mtr_checklist cc');
    $data["item"] = $q->result_array();

    $this->db->select('checklist_group_id group_id, group_name, parent_id');
    // $this->db->where('checklist_group_id !=', 9);
    $this->db->where('group_key', 'si');
    $this->db->where('status', 1);
    $this->db->order_by('checklist_group_id');
    $q = $this->db->get('t_mtr_checklist_group');
    $data["group"] = $q->result_array();

    $this->db->select('rr.checklist_id, oo.option_id, oo.option_value');
    $this->db->join('t_mtr_checklist_template rr', 'rr.option_id = oo.option_id');
    $this->db->where('oo.option_key', 'site_inspection');
    $this->db->where('oo.status', 1);
    $q = $this->db->get('t_mtr_checklist_options oo');
    $data["options"] = $q->result_array();

    return $data;
  }

  public function siteInpectors($region_id)
  {
    $this->db->select('uu.user_id, uu.user_name');
    $this->db->join('t_mtr_user uu', 'uu.user_id = tt.user_id');
    $this->db->join('t_mtr_user_multi_mobile um', 'um.user_id = uu.user_id');
    $this->db->where('uu.is_mobile_user', 1);
    $this->db->where('um.mobile_app_type', 2);
    $this->db->where('tt.region_id', $region_id);
    $this->db->where('uu.status', 1);
    $q = $this->db->get('t_mtr_user_territory tt');
    // echo $this->db->last_query();
    return $q->result();
  }

  public function reschedule_data_prep($visit_id)
  {
    // $data = $this->siteProperties();
    $this->db->select('vv.visit_id, vv.site_id, vv.user_id, uu.user_name, vv.user_group_id, vv.cluster_ops_id, vv.region_id, vv.visit_date, vv.visit_type, vv.priority, vv.remark, tp.tp_code, ss.*, tp.tp_id, tp.address tp_address, tp.latitude tp_latitude, tp.longitude tp_longitude, cc.cluster_name, rr.region_name');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_tower_provider tp', 'tp.tp_id = vv.tp_id', 'left');
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('vv.visit_id', $visit_id);
    $q = $this->db->get('t_trx_site_inspection_schedule vv');
    $data['visit_info'] = $q->row_array();

    $this->db->select('sc.checklist_id');
    $this->db->where('sc.visit_id', $visit_id);
    $q = $this->db->get('t_trx_site_inspection_schedule_checklist sc');
    $data['visit_item'] = $q->result_array();

    return $data;
  }

  public function siteCandidate($site_id)
  {
    $this->db->select('tp.tp_code, tc.company_name, cd.tp_id, cd.site_id, cd.status, tp.longitude, tp.latitude, tp.address, si.vendor_id');
    $this->db->join('t_mtr_tower_provider tp', 'tp.tp_id = cd.tp_id');
    $this->db->join('t_mtr_tp_company tc', 'tc.company_id = tp.company_id');
    $this->db->join('t_mtr_site si', 'si.site_id = cd.site_id');
    $this->db->where('cd.site_id', $site_id);
    $q = $this->db->get('t_site_tp_kandidat cd');
    return $q->result();
  }

  public function getVisitDate($site_id, $month_ops, $year_ops)
  {
    $this->db->select("visit_date", false);
    $this->db->where('site_id', $site_id);
    $this->db->where("date_part('month', visit_date) =", $month_ops, false);
    $this->db->where("date_part('year', visit_date) =", $year_ops, false);
    $q = $this->db->get('t_trx_site_inspection_schedule');
    return $q->result();
  }

  public function getSiteSummary($opr_year,$opr_month,$opr_cluster,$sch_cond,$keyword,$page,$rows)
  {
    $this->db->select('rr.region_name, rr.region_id, rr.region_code, st.vendor_id');
    $this->db->select('pm.visit_id, pm.site_id, st.site_code, st.site_name, pm.user_id, uu.user_name, pm.priority, pm.visit_type');
    $this->db->select("case when pm.status = 0 then 'Unconfirmed' when pm.status = 1 then 'Confirmed - Unvisited' when pm.status = 2 then 'Declined - Unvisited' end as pm_status", false);
    $this->db->select("pm.status, to_char(pm.visit_date, 'DD Mon YYYY') visit_plan, to_char(pm.last_visited_date, 'DD Mon YYYY') visit_date", false);
    $this->db->select('case when is_code_show = 1 then pm.security_code_checkin else null end as security_code_checkin
                        , case when is_code_show = 1 then pm.security_code_checkout else null end as security_code_checkout ',false);
    $this->db->join('t_mtr_user uu', 'uu.user_id = pm.user_id');
    $this->db->join('t_mtr_site st', 'st.site_id = pm.site_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = st.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('rr.region_id', $opr_cluster);
    $this->db->where("date_part('month', pm.visit_date) =", $opr_month);
    $this->db->where("date_part('year', pm.visit_date) =", $opr_year);
    $this->db->order_by('pm.visit_id', 'desc');

    if($sch_cond != 3) 
      { $this->db->where_in('pm.status', array(0,1)); }
    else 
      { $this->db->where('pm.status', $sch_cond);}
    if($keyword){ 
      $keyword = strtolower($keyword);
      $where = "(lower(uu.user_name) like '%$keyword%' or lower(st.site_code) = '$keyword' OR lower(st.site_name) like '%$keyword%' OR lower(st.vendor_id) like '%$keyword%')"; 
      $this->db->where($where);
    }
    $q = $this->db->get('t_trx_site_inspection_schedule pm', $rows, (($page - 1) * $rows));
    // echo $this->db->last_query();

    $this->db->join('t_mtr_user uu', 'uu.user_id = pm.user_id');
    $this->db->join('t_mtr_site st', 'st.site_id = pm.site_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = st.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('rr.region_id', $opr_cluster);
    $this->db->where('pm.status', $sch_cond);
    $this->db->where("date_part('month', pm.visit_date) =", $opr_month);
    $this->db->where("date_part('year', pm.visit_date) =", $opr_year);
    if($keyword){ 
      $keyword = strtolower($keyword);
      $where = "(lower(uu.user_name) like '%$keyword%' or lower(st.site_code) = '$keyword' OR lower(st.site_name) like '%$keyword%' OR lower(st.vendor_id) like '%$keyword%')"; 
      $this->db->where($where);
    }
    $t = $this->db->get('t_trx_site_inspection_schedule pm');
    $x = $q->result_array();    

    for ($i=0; $i < count($x); $i++) { 
      $x[$i]['action']='<a href="#" class="btn btn-small btn-success btn-icon glyphicons eye_open" onclick="detailVisit('.$x[$i]['visit_id'].')" ><i></i>Detail Info</a>';
    }

    $r["rows"]  = $x;
    $r["total"] = $t->num_rows();
    return $r;
  }

  public function siteVisitInfo($data)
  {
    $this->db->where('site_id', $data['site_id']);
    $this->db->where('visit_week', $data['visit_week']);
    $this->db->where('visit_date', $data['visit_date']);
    $q = $this->db->get('t_trx_site_inspection_schedule');
    $r['head'] = $q->row_array();
    $r['list'] = array();
    if($q->num_rows() > 0 ) {
      $this->db->where('visit_id', $r['head']['visit_id']);
      $q = $this->db->get('t_trx_site_inspection_checklist');
      $list = $q->result_array();

      foreach ($list as $key) {
        $r['list'][] = $key['checklist_id'];
      }
      $r['status'] = true;
    }
    else {
      $r['status'] = false;
    }

    return $r;
  }


  public function getChecklist($visit_id, $checklist_group, $group_key)
  {
    $this->db->select('checklist_group_id, parent_id, group_name ');
    $this->db->where('status', 1);
    $this->db->where('group_key', 'si');
    $this->db->where('parent_id !=', 0);
    // $this->db->where('parent_id', $checklist_group);
    $q = $this->db->get('t_mtr_checklist_group');
    $data['group'] = $q->result_array();
    // echo $this->db->last_query();

    $this->db->select('sic.visit_id, sic.checklist_id, cc.checklist_name, cc.checklist_group, cr.result, cr.bobot, cc.interface');
    $this->db->select('si.status');
    $this->db->join('t_mtr_checklist cc', 'cc.checklist_id = sic.checklist_id');
    $this->db->join('t_trx_site_inspection_checklist_result cr', "cr.checklist_id = cc.checklist_id and cr.visit_id = $visit_id", 'left');
    $this->db->join('t_trx_site_inspection_schedule si', 'si.visit_id = sic.visit_id ');
    $this->db->where('si.visit_id', $visit_id);
    $q = $this->db->get('t_trx_site_inspection_schedule_checklist sic');
    $data['checklist'] = $q->result_array();
    // echo $this->db->last_query();

    $this->db->select('vv.is_code_show,vv.security_code_remark,vv.security_code_generate_date,vv.security_code_generate_by,vv.status, ss.site_code, ss.site_id, ss.site_name, ss.vendor_id, ss.site_address, ss.longitude, ss.latitude');
    $this->db->select('vv.security_code_checkin, vv.security_code_checkout, vv.visit_date, vv.visit_id');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->where('vv.visit_id', $visit_id);
    $q = $this->db->get('t_trx_site_inspection_schedule vv');
    $data['site'] = $q->row_array();

    // $this->db->select('pc.pm_id, pc.checklist_id, cc.checklist_name');
    // $this->db->join('t_mtr_pm_checklist_answer ca');
    // $this->db->join('t_trx_pm_checklist_result cr', 'cr.pm_id = table.column', 'left');
    // $this->db->join('t_trx_pm_checklist_result_photo cp', 'table.column = table.column', 'left');
    // $this->db->where_in('checklist_id', $data['checklist']->checklist_id);
    // $q = $this->db->get('t_mtr_pm_checklist cc');
    // $data['options'] = $q->result();

    return $data;
  }

  public function visit_group($type, $year, $month, $region_id, $param)
  {
    $this->db->select('rr.region_name, cc.cluster_name, ss.site_id, ss.site_code, ss.site_name, vv.visit_id, uu.user_name, vv.visit_date as visit_plan, vv.last_visited_date, vv.status, vv.last_visited_date as visit_date, vv.remark');
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('ss.region_id', $region_id);
    $this->db->where($param);
    $this->db->where("date_part('year', visit_date) =", $year);
    $this->db->where("date_part('month', visit_date) =", $month);
    $this->db->where('ss.status = ', 0);
    $this->db->order_by('vv.visit_date', 'desc');
    $q = $this->db->get('t_trx_site_inspection_schedule vv');
    return $q->result();
  }

  public function visit_group_dg($type, $year, $month, $region_id, $param)
  {
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('ss.region_id', $region_id);
    $this->db->where($param);
    $this->db->where("date_part('year', visit_date) =", $year);
    $this->db->where("date_part('month', visit_date) =", $month);
    $this->db->where_in('vv.status', array(0,1,2,3,4));
    $this->db->order_by('vv.visit_date', 'desc');
    $q = $this->db->get('t_trx_site_inspection_schedule vv' );
    $data['total'] = $q->num_rows();

    $this->db->select('rr.region_name, cc.cluster_name, ss.site_id, ss.site_code, ss.site_name, vv.visit_id, vv.status, vv.remark');
    $this->db->select('vv.last_visited_date as visit_date, vv.parent_id, uu.user_name', false);
    $this->db->select("concat_ws(' | ',tp.tp_code, tp.tp_company_name) as tp_name", false);
    $this->db->select("to_char(vv.visit_date, 'DD Mon YYYY' ) as visit_plan", false);
    $this->db->select("to_char(vv.last_visited_date, 'DD Mon YYYY') last_visited_date", false);
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->join('t_mtr_tower_provider tp', 'tp.tp_id = vv.tp_id', 'left');
    $this->db->where('ss.region_id', $region_id);
    $this->db->where($param);
    $this->db->where("date_part('year', visit_date) =", $year);
    $this->db->where("date_part('month', visit_date) =", $month);
    $this->db->where_in('vv.status', array(0,1,2,3,4));
    $this->db->order_by('vv.visit_date', 'desc');
    $q = $this->db->get('t_trx_site_inspection_schedule vv' );
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
       $key->action_button = '<button class="btn btn-small btn-icon glyphicons eye_open" onclick="visitDetail('.$key->visit_id.', \''.$type.'\')"><i></i>Detail</button>';
    }
    $data['rows'] = $q->result();

    return $data;
  }

  public function reschedule_visits($visit_id, $type)
  {
    $this->db->select('rr.region_name, cc.cluster_name, ss.site_id, vv.created_on, to_char(vv.updated_on, \'DD Mon YYYY HH24:MI:SS\') updated_on , ss.site_code, ss.site_name, vv.visit_id, uu.user_name, to_char(vv.visit_date, \'DD Mon YYYY\') as visit_plan, vv.last_visited_date, vv.status, vv.last_visited_date as visit_date, vv.remark', false);
    $this->db->select("concat_ws(' | ',tp.tp_code, tp.tp_company_name) as tp_name", false);
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_tower_provider tp', 'tp.tp_id = vv.tp_id', 'left');
    $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
    $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
    $this->db->where('vv.visit_id', $visit_id);
    $this->db->or_where('vv.parent_id', $visit_id);
    $this->db->order_by('vv.created_on', 'asc');
    $q = $this->db->get('t_trx_site_inspection_schedule_history vv' );

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

}

/* End of file minspection.php */
/* Location: ./application/models/pm/minspection.php */