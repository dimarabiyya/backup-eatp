<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of engineering_approval
 *
 * @author tumbas
 */
class Isr_bts extends MX_Controller {
  //  ISR Status
  //Link status

  const ON_PROCESS = 2;

//Engineering Approval
  const ENGIN_APPROV_NEW = '1161';
  const ENGIN_APPROV_RENEWAL = '1261';
  const ENGIN_APPROV_DISC_DISMANTLE = '1361';
  const ENGIN_APPROV_DISC_ISR_CHANGE = '1661';
  //M2M
  const M2M_NEW = '1171';
  const M2M_RENEWAL = '1271';
  const M2M_DISC_DISMANTLE = '1371';
  const M2M_DISC_ISR_CHANGE = '1671';
  //SPP to Finance
  const SPP_FIN_NEW = '1121';
  const SPP_FIN_RENEWAL = '1211';
  //SPP payment
  const SPP_PAY_NEW = '1131';
  const SPP_PAY_RENEWAL = '1221';
  //DGPT Released
  const DGPT_NEW = '1141';
  const DGPT_RENEWAL = '1231';
  const DGPT_RELEASED_NEW = '1151';
  const DGPT_RELEASED_RENEWAL = '1241';

  protected $_user_now;
  protected $_date_now;

  public function __construct() {
    parent::__construct();
    $this->load->model('isr_bts_model');
    $this->mglobal->is_logged_in();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function index() {
    $data['content_id'] = 'isr_bts_list';
    $this->_generate_view($data);
  }

  protected function _generate_view($data, $template = 'template/smashing') {
    $this->load->view($template, $data);
  }

  public function load_isr_bts_active() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('isr_bts_active_list');
    } else {
      die('Forbidden access');
    }
  }

  public function load_isr_bts_expired() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('isr_bts_expired_list');
    } else {
      die('Forbidden access');
    }
  }

  public function load_isr_bts_history() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('isr_bts_history_list');
    } else {
      die('Forbidden access');
    }
  }

  public function load_isr_bts_check_double() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('isr_bts_check_double_list');
    } else {
      die('Forbidden access');
    }
  }

  public function get_isr_bts_active_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = $post_data['page'];
      $limit = $post_data['rows'];
      $offset = ($page - 1) * $limit;
      $q = isset($post_data['q']) ? $post_data['q'] : '';

      $result = $this->isr_bts_model->get_isr_bts_active_list($q, $limit, $offset);

      $data['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->doc = "<a download='' target='_blank' href='" . base_url() . "file/upload/isr_bts/" . $row->doc . "'>" . $this->core->setFileNameIcon($row->doc) . '</a>';

        $x = explode('#', $row->status);
        if ($x[1] == '0')
          $row->status = 'No link on this app.';
        elseif ($x[0] == '0')
          $row->status = '<span title="All document already complete.">All document already complete.</span>';
        else
          $row->status = '<span style="color:blue">' . $x[0] . ' of ' . $x[1] . ' links is waiting completion.</span>';

        $WarningDay = $this->mglobal->showdata('lookup_value', 't_mtr_lookup', array('lookup_key' => 'ISR_AlertDay'));
        $datetime1 = new DateTime(date('Y-m-d'));
        $datetime2 = new DateTime(date('Y-m-d', strtotime($row->app_expired)));
        $interval = $datetime1->diff($datetime2);
        $diff = $interval->format('%R%a');
        if ($row->isComplete == 1) {
          if ($diff < $WarningDay && isset($row->app_expired))
            $row->status = '<span style="color:blue">Expired in ' . intval($diff) . ' days</span>';
        } else {
//          if (strtotime($row->spp_due_date) < strtotime(date('Y-m-d'))) {
//            $row->status = '<span class="text-error">Payment overdue.</span>';
//          } else {
            $row->status = '<span style="color:blue">Waiting document completion</span>';
//          }
        }

        $menu = $this->mmenu->getMenuId("isr");
        $row->app_id = $this->core->encode($row->app_id);
        $row->action = $this->core->setButton('more_windows', 'viewDocs("' . $row->app_id . '")', 'Download Docs');
//        if ($row->isComplete == 1) {
//          if ($this->mmenu->cekPermission($menu, 'menu_add'))
//            $row->action .= $this->core->setButton('circle_plus', 'doAddLink("' . $row->app_id . '")', 'Add Link');
//          if ($this->mmenu->cekPermission($menu, 'menu_edit'))
//            $row->action .=$this->core->setButton('pencil', 'doEditApp("' . $row->app_id . '")', 'Edit');
//        } else {
//          if ($this->mmenu->cekPermission($menu, 'menu_delete'))
//            $row->action .= $this->core->setButton('remove_2', 'doDeleteApp("' . $row->app_id . '")', 'Delete');
//        }
        
        if ($row->app_expired) $row->app_expired = date('d/m/Y', strtotime($row->app_expired));

        array_push($data['rows'], $row);
      }
      $data['total'] = $result['total'];

      echo json_encode($data);
    } else {
      die('Forbidden access');
    }
  }

  public function get_isr_bts_expired_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = $post_data['page'];
      $limit = $post_data['rows'];
      $offset = ($page - 1) * $limit;
      $q = isset($post_data['q']) ? $post_data['q'] : '';

      $result = $this->isr_bts_model->get_isr_bts_expired_list($q, $limit, $offset);

      $data['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->doc = "<a download='' target='_blank' href='" . base_url() . "file/upload/isr_bts/" . $row->doc . "'>" . $this->core->setFileNameIcon($row->doc) . '</a>';

        $x = explode('#', $row->status);
        if ($x[1] == '0')
          $row->status = 'No link on this app.';
        elseif ($x[0] == '0')
          $row->status = '<span title="All document already complete.">All document already complete.</span>';
        else
          $row->status = '<span style="color:blue">' . $x[0] . ' of ' . $x[1] . ' links is waiting completion.</span>';

        $WarningDay = $this->mglobal->showdata('lookup_value', 't_mtr_lookup', array('lookup_key' => 'ISR_AlertDay'));
        $datetime1 = new DateTime(date('Y-m-d'));
        $datetime2 = new DateTime(date('Y-m-d', strtotime($row->app_expired)));
        $interval = $datetime1->diff($datetime2);
        $diff = $interval->format('%R%a');
        if ($row->isComplete == 1) {
          if ($diff < $WarningDay && isset($row->app_expired))
            $row->status = '<span style="color:blue">Expired in ' . intval($diff) . ' days</span>';
        } else {
          if (strtotime($row->spp_due_date) < strtotime(date('Y-m-d'))) {
            $row->status = '<span class="text-error">Payment overdue.</span>';
          } else {
            $row->status = '<span style="color:blue">Waiting document completion</span>';
          }
        }
        $row->app_id = $this->core->encode($row->app_id);
        $row->action = $this->core->setButton('more_windows', 'viewDocs("' . $row->app_id . '")', 'Download Doc');
        $row->app_expired = date('d/m/Y', strtotime($row->app_expired));

        array_push($data['rows'], $row);
      }
      $data['total'] = $result['total'];

      echo json_encode($data);
    } else {
      echo 'Forbidden access.';
    }
  }

  public function get_isr_bts_history_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = $post_data['page'];
      $limit = $post_data['rows'];
      $offset = ($page - 1) * $limit;
      $q = isset($post_data['q']) ? $post_data['q'] : '';

      $result = $this->isr_bts_model->get_isr_bts_history_list($q, $limit, $offset);

      $data['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->doc = "<a download='' target='_blank' href='" . base_url() . "file/upload/isr_bts/" . $row->doc . "'>" . $this->core->setFileNameIcon($row->doc) . '</a>';

        $x = explode('#', $row->status);
        if ($x[1] == '0')
          $row->status = 'No link on this app.';
        elseif ($x[0] == '0')
          $row->status = '<span title="All document already complete.">All document already complete.</span>';
        else
          $row->status = '<span style="color:blue">' . $x[0] . ' of ' . $x[1] . ' links is waiting completion.</span>';

        $WarningDay = $this->mglobal->showdata('lookup_value', 't_mtr_lookup', array('lookup_key' => 'ISR_AlertDay'));
        $datetime1 = new DateTime(date('Y-m-d'));
        $datetime2 = new DateTime(date('Y-m-d', strtotime($row->app_expired)));
        $interval = $datetime1->diff($datetime2);
        $diff = $interval->format('%R%a');
        if ($row->isComplete == 1) {
          if ($diff < $WarningDay && isset($row->app_expired))
            $row->status = '<span style="color:blue">Expired in ' . intval($diff) . ' days</span>';
        } else {
          if (strtotime($row->spp_due_date) < strtotime(date('Y-m-d'))) {
            $row->status = '<span class="text-error">Payment overdue.</span>';
          } else {
            $row->status = '<span style="color:blue">Waiting document completion</span>';
          }
        }
        $row->app_id = $this->core->encode($row->app_id);
        $row->action = $this->core->setButton('more_windows', 'viewDocs("' . $row->app_id . '")', 'Download Doc');
        $row->app_expired = date('d/m/Y', strtotime($row->app_expired));
        

        array_push($data['rows'], $row);
      }
      $data['total'] = $result['total'];

      echo json_encode($data);
    } else {
      echo 'Forbidden access.';
    }
  }

  public function get_isr_bts_detail_list() {
    if ($this->input->is_ajax_request()) {
      $app_id = $this->core->decode($this->input->get('app_id'));
      $result = $this->isr_bts_model->get_isr_bts_detail_list($app_id);

      $list = array();
      foreach ($result as $row) {
        if ($row->status_active == 1)
          $row->status_active = "<img src='" . base_url() . "assets/images/ok.png' title='Active'/>";
        elseif ($row->status_active == 2)
          $row->status_active = "<img src='" . base_url() . "assets/images/proccess.png' title='On proccess'/>";
        else
          $row->status_active = "<img src='" . base_url() . "assets/images/s_error.png' title='Not Active'/>";

        array_push($list, $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function add_new_isr_bts() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $spp_pay_id = $this->core->decode($this->uri->segment(4));
      $data['header'] = $this->isr_bts_model->get_isr_bts_header($spp_pay_id);
      $data['header']->doc_release_date = date('d-M-Y');
      $data['details'] = $this->isr_bts_model->get_isr_bts_detail_list($spp_pay_id);

      $this->load->view("isr_bts_add_new", $data);
    } else { //SAVING DATA HERE
      $this->_set_isr_bts_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
        $spp_pay_id = $data_post['spp_pay_id'];
        $app_id = $data_post['app_id'];
        $this->db->trans_begin();

        //Upload document first
        $docId = 0;
        $path = $this->_get_isr_bts_path();
        if (!empty($_FILES['doc']['name'])) {
          $config['upload_path'] = $path;
          $config['allowed_types'] = '*';
          $config['max_size'] = '500000';
          $config['file_name'] = 'Release-DGPT_' . $data_post['app_no'] . '_' . date('dMY', strtotime($data_post['doc_issued']));
          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if (!$this->upload->do_upload('doc')) {
            $this->db->trans_rollback();
            $response = array(
                'status' => 'warning',
                'hasil' => $this->upload->display_errors()
            );
            echo json_encode($response);
            exit();
          } else {
            $pic = $this->upload->data();
            $doc = $pic['file_name'];

            $docData['app_id'] = $app_id;
            $docData['doc_no'] = $pic['file_name'];
            $docData['file_name'] = $pic['file_name'];
            $docData['doc_type'] = 1142;
            $docData['app_type'] = 2;

            $docId = $this->mglobal->saveData('t_trx_isr_bts_doc', $docData);
          }
        }

        //Update App
        $data_app = array(
            'app_id' => $app_id,
            'isComplete' => 1,
            'doc' => $doc
        );
        $this->mglobal->saveData('t_trx_isr_bts_app', $data_app, 'app_id');

        //Update App link
        $data_app_link = array(
            'prev_completed_milestone' => self::DGPT_NEW,
            'milestone_status' => self::DGPT_RELEASED_NEW,
            'app_id' => $app_id,
            'status_active' => 1
        );
        $this->mglobal->saveData('t_trx_isr_bts_app_link', $data_app_link, 'app_id');

        //Create Release DGPT
        $data_isr_bts = array(
            'app_id' => $app_id,
            'doc_id' => $docId,
            'doc_issued' => MakeUpDate($data_post['doc_issued']),
        );
        $isr_bts_id = $this->mglobal->saveData('t_trx_isr_bts_isr_bts', $data_isr_bts);

        //Create Release DGPT Detail
        $total_link = 0;
        $total_bhp = 0;
        foreach ($data_post['link_id'] as $key => $value) {
          $data_isr_bts_detail = array(
              'isr_bts_id' => $isr_bts_id,
              'link_id' => $data_post['link_id'][$key],
              'doc_id' => $docId,
          );
          $this->mglobal->saveData('t_trx_isr_bts_isr_bts_detail', $data_isr_bts_detail);
          $total_link++;
//          $total_bhp += $data_post['bhp'][$key];
        }

        //Update SPP Payment
        $this->mglobal->saveData('t_trx_isr_bts_spp_pay', array('spp_pay_id' => $spp_pay_id, 'isr_bts_id' => $isr_bts_id), 'spp_pay_id');

        //Save log
        $logData['userPost'] = $data_post;
        $logData['header'] = $data_isr_bts;
        $logData['detail'] = $data_isr_bts_detail;
        $this->dblog->AuditTrail('isr_bts-isr_bts-create', $logData);

        //CREATE NOTIFICATION
//        $templateNo = 'ISR-E021';
//        $mail_param = array(
//            '[created_by]' => $this->session->userdata('user_id'),
//            '[created_on]' => date('Y-m-d H:i:s'),
//            '[app_no]' => $data_post['app_no'],
//            '[bpv_no]' => $data_post['bpv_no'],
//            '[isr_bts_date]' => MakeUpDate($data_post['payment_date'], 'Y-m-d', 'd M Y'),
//            '[remark]' => $data_post['remark'],
//            '[total_links]' => $i,
//            '[total_bhp]' => $totalBhp
//        );
//        $schedule_param = array('date' => date('Y-m-d'), 'time' => date('G:i:s'), 'foreign_id' => $spp_pay_id);
//        $this->mglobal->pushMail($templateNo, array_unique($region), $mail_param, $schedule_param, '', $linksDetail);

        if ($this->db->trans_status() === TRUE) {
          $this->db->trans_commit();
          echo json_encode(array(
              'status' => 'OK',
              'hasil' => 'Application has been saved successfully.'
          ));
        } else {
          $this->db->trans_rollback();
          echo json_encode(array(
              'status' => 'error',
              'hasil' => 'Saving Application failed.'
          ));
        }
      } else {
        $response = array(
            'status' => 'warning',
            'hasil' => validation_errors()
        );
        echo json_encode($response);
      }
    }
  }

  public function view_isr_bts_docs($app_id=0) {
    $data['app_id'] = $app_id;
    $result = $this->isr_bts_model->get_doc_type();
    $data['doc_type_list'] = array(''=>' - All Document - ');
    foreach ($result as $row) {
      $data['doc_type_list'][$row->ref_key] = $row->name;
    }
    
    $this->load->view("isr_bts_view_doc", $data);
  }
  
  public function get_isr_bts_docs() {
    if ($this->input->is_ajax_request()) {
      $app_id = $this->core->decode($this->uri->segment(3));
      $param = $this->input->post();
      $result = $this->isr_bts_model->get_isr_bts_docs($app_id, $param);

      $list = array();
      foreach ($result as $row) {
        $row->file_name = "<a download='' target='_blank' href='" . base_url() . "file/upload/isr_bts/$row->doc_type/$row->file_name'>" . $this->core->setFileNameIcon($row->file_name) . '</a>';
        
        array_push($list, $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  private function _set_isr_bts_validation() {
    $this->form_validation->set_rules('doc_issued', 'Doc Issued Date', 'trim|required');
  }

  private function _get_isr_bts_path() {
    $path_bts = 'file/upload/isr_bts';
    $path_resi = 'isr_bts';
    $path = $path_bts . '/' . $path_resi;

    if (!file_exists($path)) {
      $make_dir = mkdir($path, 0777, true);
      if ($make_dir == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to make directory "SPP Finance"'
        ));
        exit();
      }
    }

    return $path;
  }

}

?>
