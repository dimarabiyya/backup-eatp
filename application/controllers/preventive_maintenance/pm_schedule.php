<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pm_schedule extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('mglobal');
    $this->load->model('schedule/mschedule');
    $this->load->model('schedule/mpm', 'pm');
  }

  public function index()
  {
    $data['title']      = "Schedule"; 
    $data['content_id'] = "preventive_maintenance/f_pm_schedule";
    $data["priority"]   = $this->mglobal->lookup('pm_priority');
    $data["visit_type"] = $this->pm->lookupOption('pm_visit_type');

    // ubah lagi, untuk cluster tree jangan ambil dari session. tapi dari tabel t_mtr_cluster_mapping
    // $data["territory"]  = $this->session->userdata('user_region');
    $userTerritory      = $this->mglobal->userTerritory($this->session->userdata('user_id'));
    $territory = array();
    $x   = 0;
    foreach ($userTerritory['region'] as $rr) {
      $territory[$x] = $rr;
      foreach ($userTerritory['cluster'] as $cc) {
        if ($cc['region_id'] == $rr['region_id'])
          {
            $territory[$x]['cluster'][] = $cc;
          }
      }
      $x++;
    }
    $data["territory"]  = $territory;
    $data["region"]     = $this->session->userdata('user_region');
    $this->load->view("template/smashing", $data);
  }

  // yang ini untuk ditampilkan dalam format datatable
  public function getSites()
  {
    $this->load->model('mmenu');
    // $url     = $this->uri->segment(2) != '' ? $url = $this->uri->segment(1).'/'.$this->uri->segment(2) : $this->uri->segment(1) ;
    // $url       = $this->uri->uri_string();
    $menu_id   = $this->mmenu->getMenuId('pm/schedule');
    $allow_create_schedule = $this->mmenu->cekPermission($menu_id, 'menu_add' );

    $page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
    $rows = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 10;
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'site_id';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

    $opr_year      = $this->input->get_post('opr_year');
    $opr_cluster   = $this->input->get_post('opr_cluster');
    $opr_region    = $this->input->get_post('opr_region');
    $territoryType = $this->input->get_post('territoryType');
    $search        = $this->input->get_post('otherParam');

    if(!$opr_year)
      { $opr_year = date('Y'); }
    if(!$opr_cluster)
      { $opr_cluster = $this->session->userdata('cluster_ops_id'); }

    $data = $this->pm->getSiteList($opr_cluster, $opr_year, $search, $page, $rows, $allow_create_schedule, $opr_region, $territoryType, $sort, $order);
    echo json_encode($data);
  }

  public function getSiteSchedule($type = "pm")
  {
    $data = $this->input->post();
    $d = explode('-', $data['visit_date']);
    $data['visit_week']    = (int)date('W', mktime(0, 0, 0, $d[1], $d[2], $d[0]));

    $site_id = $data['site_id'];
    if($type == 'pm') {
      $r['pm'] = $this->mschedule->getSiteSchedule('t_trx_pm_schedule', $data);
      unset($data['site_id']);
      $r['si'] = $this->mschedule->crossSchedule('t_trx_site_inspection_schedule', $data, $site_id);
    } 
    else {
      $r['si'] = $this->mschedule->getSiteSchedule('t_trx_site_inspection_schedule', $data);
      unset($data['site_id']);
      $r['pm'] = $this->mschedule->crossSchedule('t_trx_pm_schedule', $data, $site_id);
    }

    echo json_encode($r);
  }

  public function getUserSchedule($value='')
  {
    $page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
    $rows = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 10;
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'user_id';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

    $opr_year    = $this->input->get_post('opr_year');
    $opr_cluster = $this->input->get_post('opr_cluster');
    $opr_month   = $this->input->get_post('opr_month');
    $search      = $this->input->get_post('otherParam');
    $opr_region    = $this->input->get_post('opr_region');
    $territoryType = $this->input->get_post('territoryType');

    if(!$opr_year)
      { $opr_year = date('Y'); }
    if(!$opr_cluster)
      { $opr_cluster = $this->session->userdata('cluster_ops_id'); }

    $data = $this->pm->getUserSchedule($opr_cluster, $opr_year, $opr_month, $search, $page, $rows, $opr_region, $territoryType, $sort, $order);
    echo json_encode($data);
  }

  public function revList()
  {
    $page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
    $rows = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 10;

    $opr_year    = $this->input->get_post('opr_year');
    $opr_month   = $this->input->get_post('opr_month');
    $opr_cluster = $this->input->get_post('opr_cluster');
    $search      = $this->input->get_post('otherParam');
    $opr_region    = $this->input->get_post('opr_region');
    $territoryType = $this->input->get_post('territoryType');

    if(!$opr_year)
      { $opr_year = date('Y'); }
    if(!$opr_cluster)
      { $opr_cluster = $this->session->userdata('cluster_ops_id'); }

    $data = $this->pm->revList($opr_year, $opr_month, $opr_cluster, $search, $page, $rows, $opr_region, $territoryType);
    echo json_encode($data);
  }

  public function create()
  {
    $data['title']       = "Schedule"; 
    $data['subTitle']    = "Schedule"; 
    $data['content_id']  = "preventive_maintenance/f_pm_schedule_create";
    if(!$this->input->post()) {
      redirect(base_url('pm/schedule'));
    }

    $data['year']        = $this->input->post('year');
    $data['site_id']     = $this->input->post('site_id');
    $data['site']        = $this->pm->getSitePic($this->input->post('site_id'));
    $data['pic']         = $this->pm->getRegionPic($this->input->post('site_id'));
    $data["priority"]    = $this->mglobal->lookup('pm_priority');
    $data["visit_type"]  = $this->pm->lookupOption('pm_visit_type');
    $data['calendar']    = $this->noJsonCalc($this->input->post('site_id'), $this->input->post('year'), date('m'), 'calendar_generated');
    // $data["priorityGroup"] = $this->pm->lookupOptionGroup('pm_visit_type');
    // $userTerritory       = $this->mglobal->userTerritory($this->session->userdata('user_id'));
    $properties          = $this->pm->siteProperties();
    
    // print_r($properties['group']);
    // bagian ini untuk ngurutin site properties berdasarkan group propertiesnya
    $siteProperties = array();
    $optionClass    = array();
    $x   = 0;
    foreach ($properties['group'] as $gg) {
      $siteProperties[$x] = $gg;
      $ci = 0;
      foreach ($properties['item'] as $ii) {
        if ($ii['group_id'] == $gg['group_id'])
          {
            $siteProperties[$x]['checklists'][$ci] = $ii;
            $siteProperties[$x]['checklists'][$ci]['class'] = '';
            foreach ($properties['options'] as $oo) {
              if($oo['checklist_id'] == $ii['checklist_id']) {
                  $siteProperties[$x]['checklists'][$ci]['class'] .= $oo['option_value']. ' ';
              }
            }
              $ci++;
          }
      }
      $x++;
    }

    // print_r($siteProperties);
    // bagian ini untuk ngurutin region dan cluster usernya
    // $territory = array();
    // $x   = 0;
    // foreach ($userTerritory['region'] as $rr) {
    //   $territory[$x] = $rr;
    //   foreach ($userTerritory['cluster'] as $cc) {
    //     if ($cc['region_id'] == $rr['region_id'])
    //       {
    //         $territory[$x]['cluster'][] = $cc;
    //       }
    //   }
    //   $x++;
    // }

    $new = array();
    foreach ($siteProperties as $a){
      $new[$a['parent_id']][] = $a;
    }
    $checklistsGrouping = $this->treeGenerator($new, $new[598]);

    $data["properties"] = $checklistsGrouping;
    // $data["territory"]  = $territory;
    $this->load->view("template/smashing", $data);
  } // end of created()

  public function edit()
  {
    
    $visit_id = $this->input->get_post('visit_id');
    if(!$this->input->post()) {
      redirect(base_url('pm/schedule'));
    }

    $lastvisit  = $this->pm->reschedule_data_prep($visit_id);
    $site_id    = $lastvisit['visit_info']['site_id']; 
    $visit_plan = explode('-', $lastvisit['visit_info']['visit_date']); 
    $year_ops   = $visit_plan[0]; 
    $month_ops  = $visit_plan[1]; 
    foreach ($lastvisit['visit_item'] as $key) {
      $lastchecklists[] = $key['checklist_id'];
    }
    if($this->input->post('reschedule') == 'true') {
      $data['content_id'] = "preventive_maintenance/f_pm_reschedule";
      $data['title']      = "Reschedule"; 
      $data['subTitle']   = "Reschedule"; 
      $data['calendar']   = $this->noJsonCalc($site_id, $year_ops, $month_ops, 'calendar_generated' );
    }
    else {
      $data['content_id'] = "preventive_maintenance/f_pm_edit";
      $data['title']      = "Edit Schedule"; 
      $data['subTitle']   = "Edit Schedule"; 
      $data['calendar']   = $this->noJsonCalc($site_id, $year_ops, $month_ops, 'calendar_freeze' );
    }

    $data['reschedule_reason'] = $this->mschedule->get_reasons_group('reason_pm_reschedule');
    $data['month_ops']         = $month_ops ;
    $data['year_ops']          = $year_ops ;
    $data['site']              = $this->pm->getSitePic($site_id);
    $data['pic']               = $this->pm->getRegionPic($site_id);
    $data["priority"]          = $this->mglobal->lookup('pm_priority');
    $data["visit_type"]        = $this->pm->lookupOption('pm_visit_type');
    $properties                = $this->pm->siteProperties();
    $data['lastchecklists']    = json_encode($lastchecklists) ;
    $data['visits']            = $lastvisit['visit_info'] ;

    $siteProperties = array();
    $optionClass    = array();
    $x   = 0;
    foreach ($properties['group'] as $gg) {
      $siteProperties[$x] = $gg;
      $ci = 0;
      foreach ($properties['item'] as $ii) {
        if ($ii['group_id'] == $gg['group_id'])
          {
            $siteProperties[$x]['checklists'][$ci] = $ii;
            $siteProperties[$x]['checklists'][$ci]['class'] = '';
            foreach ($properties['options'] as $oo) {
              if($oo['checklist_id'] == $ii['checklist_id']) {
                  $siteProperties[$x]['checklists'][$ci]['class'] .= $oo['option_value']. ' ';
              }
            }
              $ci++;
          }
      }
      $x++;
    }

    $new = array();
    foreach ($siteProperties as $a){
      $new[$a['parent_id']][] = $a;
    }
    $checklistsGrouping = $this->treeGenerator($new, $new[598]);

    $data["properties"] = $checklistsGrouping;
    $this->load->view("template/smashing", $data);
  }

  public function popUpSchedule()
  {
    $data['title']       = "Schedule"; 
    $data['content_id']  = "preventive_maintenance/pop_up_schedule";
    $data["priority"]    = $this->mglobal->lookup('pm_priority');
    $data["visit_type"]  = $this->pm->lookupOption('pm_visit_type');
    $userTerritory       = $this->mglobal->userTerritory($this->session->userdata('user_id'));

    // bagian ini untuk ngurutin region dan cluster usernya
    $territory = array();
    $x   = 0;
    foreach ($userTerritory['region'] as $rr) {
      $territory[$x] = $rr;
      foreach ($userTerritory['cluster'] as $cc) {
        if ($cc['region_id'] == $rr['region_id'])
          {
            $territory[$x]['cluster'][] = $cc;
          }
      }
      $x++;
    }
    // $data["properties"] = $siteProperties;
    $data["territory"]  = $territory;
    $this->load->view("template/smashing", $data);
  }

  public function getDataSchedule()
  {
    $page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
    $rows = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 10;

  $month = $this->input->get_post('month_opr');
  $year  = $this->input->get_post('year_opr');
  $visit_id = $this->input->get_post('visit_id');
  $param['site_id']   = $this->input->get_post('site_id');
  $param['cluster_ops_id']  = $this->input->get_post('cluster_ops_id');
  $search      = $this->input->get_post('otherParam');

  /*  if(!$opr_year)
      { $opr_year = date('Y'); }
    if(!$opr_cluster)
      { $opr_cluster = $this->session->userdata('cluster_ops_id'); }*/

    $data = $this->mschedule->getSchedule('t_trx_pm_schedule', $param, $month, $year, $visit_id, $search, $page, $rows);
    echo json_encode($data);
  }


  public function detail()
  {
    $data['title']       = "Schedule"; 
    $data['subTitle']    = "Schedule"; 
    

    $month = $this->input->get_post('month_opr');
    $year  = $this->input->get_post('year_opr');
    $user_id = $this->input->get_post('user_id');
    $datatype = $this->input->post('datatype');
    if($datatype == 'user') { $data['content_id']  = "preventive_maintenance/f_pm_schedule_detail_peruser"; }
    else 
    $param['cluster_ops_id']  = $this->input->get_post('cluster_ops_id');

    if(!$this->input->get_post('user_id')) {
      redirect(base_url('pm/schedule'));
    }

    $visit_details = $this->mschedule->getUserVisit('t_trx_pm_schedule', $param, $month, $year, $user_id);

    // $data['v151td3t41L'] = array() ;
    $_i1 = 0;
    $data['v151td3t41L']['user'] = $visit_details['user'];
    $data['v151td3t41L']['type'] = 't_trx_pm_schedule';
    foreach ($visit_details['visit'] as $vv) {
      $data['v151td3t41L']['visit'][$_i1] = $vv;
      // print_r($vv);
      $_l0 = 0;
      foreach ($visit_details['checklist'] as $iterasi) {
        // echo json_encode($iterasi);
        $in3bs[$_l0] = array();
        $_i2=0;
        foreach ($visit_details['group'] as $gg) {
          $in3bs[$_l0]['group'][$_i2] = $gg;
          foreach ($iterasi as $ii) {
            if($ii['group_id'] == $gg['group_id'])
              $in3bs[$_l0]['group'][$_i2]['checklist'][] = $ii;
          }
          $_i2++;
        }
          $new = array();
          foreach ($in3bs[$_l0]['group'] as $a){
            $new[$a['parent_id']][] = $a;
          }
          // print_r($in3bs[$_l0]);

        // $erghn[$_l0] = $this->treeGenerator($new, $new[1]);
        $_l0++;
      }

      $data['v151td3t41L']['visit'][$_i1]['checklist'] = $erghn[$_i1];

      $_i1++;
    }

    $this->load->view("template/smashing", $data);
  }

  function treeGenerator(&$list, $parent){
    $tree = array();
    foreach ($parent as $k=>$l) {
      if(isset($list[$l['group_id']])){
        $l['sub_group'] = $this->treeGenerator($list, $list[$l['group_id']]);
      }
      $tree[] = $l;
    } 
    return $tree;
  }

  public function visitdetail()
  {
    $data['title']       = "Schedule"; 
    $data['subTitle']    = "Schedule"; 
    $data['content_id']  = "preventive_maintenance/f_pm_schedule_detail_peruser";
    $data['cancellation'] = $this->mschedule->get_reasons_group('pm_dec_reason') ;
    $visit_id = $this->input->post('visit_id');
    $datatype = $this->input->post('datatype');

    if(!$this->input->get_post('visit_id')) {
      redirect(base_url('pm/schedule'));
    }

    $visit_details = $this->mschedule->detailPerVisit('t_trx_pm_schedule', $visit_id);
    // print_r($visit_details);
    // die();
    $_i1 = 0;
    $data['v151td3t41L']['user'] = $visit_details['user'];
    $data['v151td3t41L']['type'] = 't_trx_pm_schedule';
    foreach ($visit_details['visit'] as $vv) {
      $data['v151td3t41L']['visit'][$_i1] = $vv;
      $_l0 = 0;
      foreach ($visit_details['checklist'] as $iterasi) {
        $in3bs[$_l0] = array();
        $_i2=0;
        foreach ($visit_details['group'] as $gg) {
          $in3bs[$_l0]['group'][$_i2] = $gg;
          foreach ($iterasi as $ii) {
            if($ii['group_id'] == $gg['group_id'])
              $in3bs[$_l0]['group'][$_i2]['checklist'][] = $ii;
          }
          $_i2++;
        }
          $new = array();
          foreach ($in3bs[$_l0]['group'] as $a){
            $new[$a['parent_id']][] = $a;
          }

        // $erghn[$_l0] = $this->treeGenerator($new, $new[598]);
        $erghn[$_l0] = $new;
        $_l0++;
      }

      // $data['v151td3t41L']['visit'][$_i1]['checklist'] = $erghn[$_i1];
      $data['v151td3t41L']['visit'][$_i1]['checklist'] = $this->treeGenerator($erghn[$_i1], $erghn[$_i1][598]);

      $_i1++;
    }

    $this->load->view("template/smashing", $data);
  }

  public function noJsonCalc($site_id, $year_ops, $month_ops, $calendar_type, $type="pm")
  {
    $prefs['template'] = '
      {table_open}
        <div id="calendar">
        <table width="100%" class="table table-striped table-bordered">
      {/table_open}

      {heading_row_start}<tr>{/heading_row_start}

      {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
      {heading_title_cell}<th colspan="{colspan}" style="text-align:center; font-size: 120%">{heading}</th>{/heading_title_cell}
      {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

      {heading_row_end}</tr>{/heading_row_end}

      {week_row_start}<tr>{/week_row_start}
      {week_day_cell}<td class="center" width="100/7%">{week_day}</td>{/week_day_cell}
      {week_row_end}</tr>{/week_row_end}

      {cal_row_start}<tr>{/cal_row_start}
      {cal_cell_start}<td class="center">{/cal_cell_start}

      {cal_cell_content}
        <div class="notes selectable">{day}</div>
      {/cal_cell_content}
      {cal_cell_content_today}
        <div class="today notes selectable">{day}</div>
      {/cal_cell_content_today}

      {cal_cell_no_content}
        <div class="selectable">{day}</div>
      {/cal_cell_no_content}
      {cal_cell_no_content_today}
        <div class="today selectable">{day}</div>
      {/cal_cell_no_content_today}

      {cal_cell_blank}<div></div>{/cal_cell_blank}

      {cal_cell_end}</td>{/cal_cell_end}
      {cal_row_end}</tr>{/cal_row_end}

      {table_close}</table></div> <div class="separator bottom"></div>{/table_close}
    ';
    $this->load->library('calendar', $prefs);

    if($type == 'pm')
      { $result    = $this->pm->getVisitDate('t_trx_pm_schedule', $site_id, $month_ops, $year_ops); }
    else
      { $result    = $this->pm->getVisitDate('t_trx_site_inspection_schedule', $site_id, $month_ops, $year_ops); }
    
    $calendar_source = 'preventive_maintenance/'.$calendar_type;
    $cal = $this->load->view($calendar_source, array('type' => $type, 'year' => $year_ops, 'month'=> $month_ops, 'date' => $result), true);
    return $cal;
  }

  public function getVisitCal($type="pm")
  {
    $prefs['template'] = '
      {table_open}
        <div id="calendar">
        <table width="100%" class="table table-striped table-bordered">
      {/table_open}

      {heading_row_start}<tr>{/heading_row_start}

      {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
      {heading_title_cell}<th colspan="{colspan}" style="text-align:center; font-size: 120%">{heading}</th>{/heading_title_cell}
      {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

      {heading_row_end}</tr>{/heading_row_end}

      {week_row_start}<tr>{/week_row_start}
      {week_day_cell}<td class="center" width="100/7%">{week_day}</td>{/week_day_cell}
      {week_row_end}</tr>{/week_row_end}

      {cal_row_start}<tr>{/cal_row_start}
      {cal_cell_start}<td class="center">{/cal_cell_start}

      {cal_cell_content}
        <div class="notes selectable">{day}</div>
      {/cal_cell_content}
      {cal_cell_content_today}
        <div class="today notes selectable">{day}</div>
      {/cal_cell_content_today}

      {cal_cell_no_content}
        <div class="selectable">{day}</div>
      {/cal_cell_no_content}
      {cal_cell_no_content_today}
        <div class="today selectable">{day}</div>
      {/cal_cell_no_content_today}

      {cal_cell_blank}<div></div>{/cal_cell_blank}

      {cal_cell_end}</td>{/cal_cell_end}
      {cal_row_end}</tr>{/cal_row_end}

      {table_close}</table></div> <div class="separator bottom"></div>{/table_close}
    ';
    $this->load->library('calendar', $prefs);

    $year_ops  = $this->input->post('year_ops');
    $month_ops = $this->input->post('month_ops');
    $site_id   = $this->input->post('site_id');
    if($type == 'pm')
      { $result    = $this->pm->getVisitDate('t_trx_pm_schedule', $site_id, $month_ops, $year_ops); }
    else
      { $result    = $this->pm->getVisitDate('t_trx_site_inspection_schedule', $site_id, $month_ops, $year_ops); }
    
    $cal = $this->load->view('preventive_maintenance/calendar_generated', array('type' => $type, 'year' => $year_ops, 'month'=> $month_ops, 'date' => $result), true);
    echo json_encode($cal);
  }

  public function havingSchedule()
  {
    $this->load->model('schedule/mschedule');
    $data = $this->input->post();
    $d = explode('-', $data['visit_date']);
    $data['visit_week'] = (int)date('W', mktime(0, 0, 0, $d[1], $d[2], $d[0]));
    foreach ($data as $key => $val) {
      if($val == '') {
        $data[$key] = null;
      }
    }
    $r = $this->mschedule->hasSchedule($data['site_id'], $data['visit_week'], $data, 't_trx_pm_schedule');
    echo json_encode($r);
  }

  public function save()
  {
    $this->load->model('schedule/mschedule');
    $data = $this->input->post();
    $d = explode('-', $data['visit_date']);
    $data['visit_week'] = (int)date('W', mktime(0, 0, 0, $d[1], $d[2], $d[0]));
    foreach ($data as $key => $val) {
      if($val == '' or $val == 'null') {
        $data[$key] = null;
      }
    }
    $r = $this->mschedule->savePm($data);
    echo json_encode($r);
  }

  public function update()
  {
    $this->load->model('schedule/mschedule');
    $data = $this->input->post();

    foreach ($data as $key => $val) {
      if($val == '' OR $val == 'null') {
        $data[$key] = null;
        unset($data[$key]);
      }
    }

    $r = $this->mschedule->pm_edit_schedule($data);

    echo json_encode($r);
  }

  public function reschedule()
  {
    $this->load->model('schedule/mschedule');
    $visit_id = $this->input->post('visit_id');
    $visit_date = $this->input->post('visit_date');
    $remark = $this->input->post('remark');
    $reason_reschedule = $this->input->post('reason_reschedule');

    $r = $this->mschedule->pm_reschedule($visit_id, $visit_date, $remark, $reason_reschedule);

    echo json_encode($r);
  }

  public function securitycode()
  {
    $visit_id = $this->input->post('visit_id');    
    $remark = $this->input->post('remark');

    $r = $this->mschedule->generate_sc('t_trx_pm_schedule', $visit_id, $remark);
    echo json_encode($r);
  }

  public function delete_schedule()
  {
    $visit_id   = $this->input->post('visit_id');
    $table_name = $this->input->post('type');
    $canceling_remark = $this->input->post('canceling_remark');
    $canceling_reason = $this->input->post('canceling_reason');

    $r = $this->mschedule->delete_schedule($visit_id, $table_name, $canceling_remark, $canceling_reason, 'pm_dec_reason');

    if($r['status']) {
      $log = array(
        'visit_id' => $visit_id, 
        'deleted_by' => $this->session->userdata('user_id'),
        'deleted_on' => date('Y-m-d H:i:s')
      );
      $this->dblog->AuditTrail('delete-pm-schedule', $log);
    }
    echo json_encode($r);
  }

  /**
   * You should learn how to refactor dudes, go back to elementary school
   */
  public function cKqAWE($type, $year, $month, $cluster_id, $param_1,$territoryType)
  {
    $view = 'preventive_maintenance/pop_up_schedule_group';

    $sMonth = '';
    switch (trim($month)) {
      case '1': $sMonth = 'January'; break;
      case '2': $sMonth = 'February'; break;
      case '3': $sMonth = 'March'; break;
      case '4': $sMonth = 'April'; break;
      case '5': $sMonth = 'May'; break;
      case '6': $sMonth = 'June'; break;
      case '7': $sMonth = 'July'; break;
      case '8': $sMonth = 'August'; break;
      case '9': $sMonth = 'September'; break;
      case '10': $sMonth = 'October'; break;
      case '11': $sMonth = 'November'; break;
      case '12': $sMonth = 'December'; break;
    }
    if($type == 'user') {
      $param_2 = array('vv.user_id' => $param_1);
      $data['user_info'] = $this->mglobal->getUserInfo($param_1,$territoryType);
    }
    else {
      $param_2 = array('vv.site_id' => $param_1);
      $data['site_info'] = $this->mglobal->getSiteInfo($param_1,$territoryType);
    }

    $data['territoryType'] = $territoryType ;
    $data['type']          = $type ;
    $data['list_schedule'] = $this->pm->visit_group($type, $year, $month, $cluster_id, $param_2, $territoryType) ;
    $data['month_year']    = $sMonth." ".$year;
    $data['year']          = $year;
    $data['month']         = $month;
    $data['cluster_id']    = $cluster_id;
    $data['param']         = $param_1;

    $this->load->view($view, $data, FALSE);
    // print_r($data);
  }

  public function rdszef($type, $year, $month, $cluster_id, $param_1,$territoryType)
  {
    if($type == 'user') {
      $param_2 = array('vv.user_id' => $param_1);
    }
    else {
      $param_2 = array('vv.site_id' => $param_1);
    }
    // $page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
    // $rows = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 10;
    $result = $this->pm->visit_group_dg($type, $year, $month, $cluster_id, $param_2,$territoryType) ;
    echo json_encode($result);

  }

  public function subres($visit_id, $type)
  {
    $r = $this->pm->reschedule_visits($visit_id, $type) ;
    echo json_encode($r);
  }

  public function checkDate()
  {
    $visit_id   = $this->input->post('visit_id');
    $year = $this->input->post('year');
    $month = substr("0".$this->input->post('month'), -2);
    $date = substr("0".$this->input->post('date'), -2);
    $visit_date = $year.'-'.$month.'-'.$date;

    $r = $this->pm->checkDate($visit_id, $visit_date);
    echo json_encode($r);
  }

}

/* End of file pm_schedule.php */
/* Location: ./application/controllers/preventive_maintenance/pm_schedule.php */