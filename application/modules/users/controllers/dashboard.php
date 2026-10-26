<?php

class Dashboard extends MX_Controller {

  protected $_debug = true;

  public function __construct() {
    parent::__construct();
    $this->load->model('dashboard_model');
    $this->_debug = false; //comment if production mode
  }

  public function index() {
    $this->_check_valid_loggedin();

    $date_from = MakeUpDate($this->input->get('date_from'));
    $date_to = MakeUpDate($this->input->get('date_to'));
    $cross_year = $this->_is_cross_year($date_from, $date_to);

    if ($this->core->is_ajax()) {
      if ($this->input->get('report_type') == 'load_data_chart__active_user_monitoring_by_region') {
        $res = $this->dashboard_model->get_active_user_monitoring_by_region();
//        die($this->db->last_query());/
        $json = array();
//        $json['q'] = $this->db->last_query();

        $json['categories'][] = 'HQ';
        foreach ($this->dashboard_model->get_regions() as $reg) {
          $json['categories'][] = $reg->region_name;
        }

        $weeks = array();
        foreach ($this->dashboard_model->get_weeks() as $week) {
          $weeks[] = $week->week . '\'' . $week->years;
        }

        $reg_user = $this->dashboard_model->get_user_registered_by_region();
        $data_reg_user = array();
        foreach ($json['categories'] as $cat) {
          $found = false;
          foreach ($reg_user as $reg) {
            if ($cat == $reg->region) {
              $data_reg_user[] = (int) $reg->count;
              $found = true;
              break;
            }
          }
          if ($found == false) {
            $data_reg_user[] = (int) $reg->count;
          }
        }

        $a = array();
        foreach ($weeks as $week) {
          foreach ($json['categories'] as $cat) {
            $cnt = 0;
            foreach ($res as $value) {
              if ($value->week . '\'' . $value->years == $week && $value->office == $cat) {
                $cnt = $value->cnt;
              }
            }
            $a[$week][] = (int) $cnt;
          }
        }
//        die(print_r($a));
        $json['html'] = $this->load->view('dashboard/_summary_active_by_region', array('active' => $a, 'register' => $data_reg_user, 'category' => $json['categories']), true);

//        echo $html;
//        exit();
//        foreach ($json['categories'] as $cat) {
//            $cnt = 0;
//            $ii = (isset($b[$week])) ? count($b[$week]) : 0;
//            foreach ($res as $value) {
//              if ($value->week . '\'' . $value->years == $week && $value->div_name == $cat) {
//                if ($this->input->get('data_label') == 'numeric') {
//                  $cnt = $value->count;
//                } else {
//                  $cnt = ceil(((int) $value->count / (int) $data_reg_user[$ii]) * 100);
////                  echo $cnt;
////                   echo $ii.'. '.$value->count.' | '.$data_reg_user[$ii].' => '.$cnt.'<br>';
//                  $ii++;
//                }
//              }
//            }
//            $b[$week][] = (int) $cnt;
//          }
//        
//        $a = array();
        $b = array();
        foreach ($weeks as $week) {
          foreach ($json['categories'] as $cat) {
            $cnt = 0;
            $ii = (isset($b[$week])) ? count($b[$week]) : 0;
            foreach ($res as $value) {
              if ($value->week . '\'' . $value->years == $week && $value->office == $cat) {
                if ($this->input->get('data_label') == 'numeric') {
                  $cnt = $value->cnt;
                } else {
                  $cnt = ceil(((int) $value->cnt / (int) $data_reg_user[$ii]) * 100);
//                  echo $cnt;
//                   echo $ii.'. '.$value->cnt.' | '.$data_reg_user[$ii].' => '.$cnt.'<br>';
                  $ii++;
                }
              }
            }
            $b[$week][] = (int) $cnt;
          }
        }

//        $a = array();
//        $b = array();
//        foreach ($res as $val) {
////          $a[] = $val->office;
//          $b[$val->week][] = (int) $val->cnt;
//        }
//
////        foreach (array_unique($a) as $k => $v) {
////          $json['categories'][] = str_replace(', ', '|', $v);
////        }
        $json['series'][] = array(
            'name' => 'User Registered',
            'data' => $data_reg_user,
        );

        foreach ($b as $k => $v) {
          $json['series'][] = array(
              'name' => $k,
              'data' => $v,
          );
        }

        $length = count($b["{$weeks[0]}"]);
        for ($i = 0; $i < $length; $i++) {
          $total = 0;
          foreach ($weeks as $value) {
            $total += $b["{$value}"][$i];
          }
          $json['nasional'][] = $total;
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('report_type') == 'load_data_chart__users_attempt_monitoring') {
        $res = $this->dashboard_model->get_users_attempt_monitoring();

        $json = array();
        $json['q'] = $this->db->last_query();
//        $categories = array();
        $json['categories'] = array();
        foreach ($this->dashboard_model->get_weeks() as $cat) {
          $json['categories'][] = $cat->week . '\'' . $cat->years;
        }
//        $json['q2'] = $this->db->last_query();
        $series = array();
        foreach ($res as $val) {
          $series['Threshold'][] = (int) $val->threshold;
          $series[$val->series_name][] = (int) $val->series_data;
        }
        $json['series'] = array();
        foreach ($series as $k => $v) {
          $json['series'][] = array(
              'name' => $k,
              'data' => $v,
          );
        }
        $json['tdata'] = $json['series'];

        $series = array();
        if ($this->input->get('data_label') == 'numeric') {
          foreach ($res as $val) {
//          $json['categories'][] = $val->categories;
            $series[$val->series_name][] = (int) $val->series_data;
//            $procent = ceil(((int) $val->series_data / (int) $val->threshold) * 100);
//            $series[$val->series_name][] = (int) number_format($procent, '2');
            $series['Threshold'][] = (int) $val->threshold;
          }
        } else {
          foreach ($res as $val) {
//          $json['categories'][] = $val->categories;
//          $series[$val->series_name][] = (int) $val->series_data;
            $procent = ceil(((int) $val->series_data / (int) $val->threshold) * 100);
            $series[$val->series_name][] = (int) number_format($procent, '2');
            $series['Threshold'][] = (int) $val->threshold;
          }
        }

//        foreach (array_unique($categories) as $cat) {
//          $json['categories'][] = $cat;
//        }
        $json['series'] = array();
        foreach ($series as $k => $v) {
          $json['series'][] = array(
              'name' => $k,
              'data' => $v,
          );
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('report_type') == 'load_data_chart__users_active_monitoring') {
        $res = $this->dashboard_model->get_users_active_monitoring();

        $json = array();
        if ($this->_debug)
          $json['q'] = $this->db->last_query();

        $json['categories'] = array();
        foreach ($this->dashboard_model->get_weeks() as $cat) {
          $json['categories'][] = $cat->week . '\'' . $cat->years;
        }

        if ($this->_debug)
          $json['q2'] = $this->db->last_query();

        $series = array();
        foreach ($res as $val) {
          $series['Registered Users'][] = (int) $val->threshold;
          $series[$val->series_name][] = (int) $val->series_data;
        }

        foreach ($series as $k => $v) {
          $json['series'][] = array(
              'name' => $k,
              'data' => $v
          );
        }
        $json['tdata'] = $json['series'];

        $series = array();
        if ($this->input->get('data_label') == 'numeric') {
          foreach ($res as $val) {
            $series[$val->series_name][] = (int) $val->series_data;
            $series['Registered Users'][] = (int) $val->threshold;
          }
        } else {
          foreach ($res as $val) {
            $procent = ceil(((int) $val->series_data / (int) $val->threshold) * 100);
            $series[$val->series_name][] = (int) number_format($procent, '2');
            $series['Registered Users'][] = (int) $val->threshold;
          }
        }
        $json['series'] = array();
        foreach ($series as $k => $v) {
          $json['series'][] = array(
              'name' => $k,
              'data' => $v,
          );
        }

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('report_type') == 'load_data_chart__users_active_by_group_division') {
        $res = $this->dashboard_model->get_users_active_by_group_division();

        $json = array();
//        $json['q1'] = $this->db->last_query();

        $json['categories'] = array();
        foreach ($res as $val) {
          if (!in_array($val->div_name, $json['categories'])) {
            $json['categories'][] = $val->div_name;
          }
        }
//        $json['categories'] = array_unique($json['categories'], SORT_STRING);
//        foreach ($this->dashboard_model->get_divisions() as $cat) {
//          $json['categories'][] = $cat->user_div_name;
//        }

        $weeks = array();
        foreach ($this->dashboard_model->get_weeks() as $week) {
          $weeks[] = $week->week . '\'' . $week->years;
        }
        
        //        Registered User By Division
        $reg_user = $this->dashboard_model->get_user_registered_by_division();
//        $json['q3'] = $this->db->last_query();
        $data_reg_user = array();
        foreach ($json['categories'] as $cat) {
          $found = false;
          foreach ($reg_user as $reg) {
            if ($cat == $reg->division) {
              $data_reg_user[] = (int) $reg->count;
              $found = true;
              break;
            }
          }
          if ($found == false) {
            $data_reg_user[] = 0;
          }
        }

        $a = array();
        foreach ($weeks as $week) {
          foreach ($json['categories'] as $cat) {
            $cnt = 0;
            foreach ($res as $value) {
              if ($value->week . '\'' . $value->years == $week && $value->div_name == $cat) {
                $cnt = $value->count;
              }
            }
            $a[$week][] = (int) $cnt;
          }
        }
//        die(print_r($a));
        $json['html'] = $this->load->view('dashboard/_summary_active_by_division', array('active' => $a, 'register' => $data_reg_user, 'category' => $json['categories']), true);

//        $json['q2'] = $this->db->last_query();
//        die(print_r($data_reg_user));
//        Active user by division
//        $a = array();
        $b = array();
        foreach ($weeks as $week) {
          foreach ($json['categories'] as $cat) {
            $cnt = 0;
            $ii = (isset($b[$week])) ? count($b[$week]) : 0;
            foreach ($res as $value) {
              if ($value->week . '\'' . $value->years == $week && $value->div_name == $cat) {
                if ($this->input->get('data_label') == 'numeric') {
                  $cnt = $value->count;
                } else {
                  $cnt = ceil(((int) $value->count / (int) $data_reg_user[$ii]) * 100);
//                  echo $cnt;
//                   echo $ii.'. '.$value->count.' | '.$data_reg_user[$ii].' => '.$cnt.'<br>';
                  $ii++;
                }
              }
            }
            $b[$week][] = (int) $cnt;
          }
        }

//        foreach (array_unique($a) as $k => $v) {
//          $json['categories'][] = str_replace(', ', '|', $v);
//        }

        $json['series'][] = array(
            'name' => 'User Registered',
            'data' => $data_reg_user,
        );

        foreach ($b as $k => $v) {
          $json['series'][] = array(
              'name' => $k,
              'data' => $v
          );
        }

        echo json_encode($json);
        exit;
      }


      if ($this->input->get('report_type') == 'load_data_chart__users_current_login') {
        $res = $this->dashboard_model->get_user_current_login();

        $json = array();
//        $json['q1'] = $this->db->last_query();

        $json['categories'] = array();
        foreach ($res as $val) {
          if (!in_array($val->div_name, $json['categories'])) {
            $json['categories'][] = $val->div_name;
          }
        }

//        $json['q2'] = $this->db->last_query();
//        Registered User By Division
        $reg_user = $this->dashboard_model->get_user_registered_by_division();
//        $json['q3'] = $this->db->last_query();
        $data_reg_user = array();
        foreach ($json['categories'] as $cat) {
          $found = false;
          foreach ($reg_user as $reg) {
            if ($cat == $reg->division) {
              $data_reg_user[] = (int) $reg->count;
              $found = true;
              break;
            }
          }
          if ($found == false) {
            $data_reg_user[] = 0;
          }
        }
        $json['series'][] = array(
            'name' => 'User Registered',
            'data' => $data_reg_user,
        );

//        Login user by division
        $data_login_user = array();
        foreach ($json['categories'] as $cat) {
          $found = false;
          foreach ($res as $val) {
            if ($cat == $val->div_name) {
              $data_login_user[] = (int) $val->count;
              $found = true;
              break;
            }
          }
          if ($found == false) {
            $data_login_user[] = 0;
          }
        }

        $json['series'][] = array(
            'name' => 'User Login',
            'data' => $data_login_user
        );

        $json['user_list'] = $this->dashboard_model->get_user_current_login_list();

        echo json_encode($json);
        exit;
      }

      if ($this->input->get('report_type') == 'load_data_chart__procentage_users_active_by_group_division') {
        $res = $this->dashboard_model->get_procentage_users_active_by_group_division();

        $json = array();

        $a = array();
        $b = array();
        foreach ($res as $val) {
          $a[] = $val->region_access;
          $b[$val->week][] = (int) $val->cnt;
        }

        foreach (array_unique($a) as $k => $v) {
          $json['categories'][] = str_replace(', ', '|', $v);
        }

        foreach ($b as $k => $v) {
          $json['series'][] = array(
              'name' => $k,
              'data' => $v
          );
        }
//        $json['q'] = $this->db->last_query();

        echo json_encode($json);
        exit;
      }
    }

    $data['content_id'] = 'dashboard/index';
    $data['date_from'] = date('01 M Y');
    $data['date_to'] = date('d M Y');

    $this->load->view('template/smashing', $data);
  }

  private function _is_cross_year($start_date, $end_date) {
    if (date('Y', strtotime($start_date)) != date('Y', strtotime($end_date))) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  private function _check_valid_loggedin() {
    if (!$this->session->userdata('is_login')) {

      if ($this->core->is_ajax()) {

        echo json_encode(array(
            'loggedin' => FALSE,
            'message' => 'You are not logged in. You will be redirected to login page.',
            'redirect_url' => site_url('login')
        ));
        exit;
      } else {
        redirect('login');
      }
    }
  }

  public function load_main_dashboard() {
    if ($this->input->is_ajax_request()) {
      $data['date_from'] = date('01 M Y');
      $data['date_to'] = date('d M Y');

      $this->load->view('dashboard/main', $data);
    } else {
      show_404();
    }
  }

  public function load_detail_dashboard() {
    if ($this->input->is_ajax_request()) {
      $data['date_from'] = date('01 M Y');
      $data['date_to'] = date('d M Y');

      $rows = $this->mglobal->getAllRegions();
      $data['region_opt'] = array('' => '- All -', 'hq' => 'HQ');
      foreach ($rows as $value) {
        $data['region_opt'][$value->region_name] = $value->region_name;
      }
      
      $rows = $this->dashboard_model->get_main_division_list();
      $data['main_division_opt'] = array('' => '- All -');
      foreach ($rows as $value) {
        $data['main_division_opt'][$value->main_div_id] = $value->main_div_name;
      }
      
      $this->load->view('dashboard/detail', $data);
    } else {
      show_404();
    }
  }

}
