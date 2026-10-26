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
class Release_dgpt extends MX_Controller {
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
  const DGPT_DISC_DISMANTLE = '1331';
  const DGPT_DISC_ISR_CHANGE = '1631';
  const DGPT_RELEASED_NEW = '1151';
  const DGPT_RELEASED_RENEWAL = '1241';
  const DGPT_RELEASED_DISC_DISMANTLE = '1341';
  const DGPT_RELEASED_DISC_ISR_CHANGE = '1641';

  protected $_user_now;
  protected $_date_now;

  public function __construct() {
    parent::__construct();
    $this->load->model('release_dgpt_model');
    $this->mglobal->is_logged_in();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function index() {
    $data['content_id'] = 'release_dgpt_list';
    $this->_generate_view($data);
  }

  protected function _generate_view($data, $template = 'template/smashing') {
    $this->load->view($template, $data);
  }

  public function load_release_dgpt_new() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('release_dgpt_new_list');
    } else {
      die('Forbidden access');
    }
  }

  public function get_release_dgpt_new_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->release_dgpt_model->get_release_dgpt_new_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/release_dgpt");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $spp_pay_id = $this->core->encode($row->spp_pay_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
          $row->action.= '<button title="Upload Doc" class="btn btn-action glyphicons upload btn-success"  onclick="uploadDoc(\'' . $spp_pay_id . '\')"><i></i></button> &nbsp;';
        }
        $row->spp_pay_id = $spp_pay_id;

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function load_release_dgpt_renewal() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('release_dgpt_renewal_list');
    } else {
      die('Forbidden access');
    }
  }

  public function get_release_dgpt_renewal_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->release_dgpt_model->get_release_dgpt_renewal_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/release_dgpt");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $spp_pay_id = $this->core->encode($row->spp_pay_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
          $row->action.= '<button title="Upload Doc" class="btn btn-action glyphicons upload btn-success"  onclick="uploadDoc(\'' . $spp_pay_id . '\')"><i></i></button> &nbsp;';
        }
        $row->spp_pay_id = $spp_pay_id;

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_release_dgpt_discontinued_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->release_dgpt_model->get_release_dgpt_discontinued_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/release_dgpt");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $spp_pay_id = $this->core->encode($row->spp_pay_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
          $row->action.= '<button title="Upload Doc" class="btn btn-action glyphicons upload btn-success"  onclick="uploadDoc(\'' . $spp_pay_id . '\')"><i></i></button> &nbsp;';
        }
        $row->spp_pay_id = $spp_pay_id;

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function load_release_dgpt_discontinued() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('release_dgpt_discontinued_list');
    } else {
      die('Forbidden access');
    }
  }

  public function load_release_dgpt_history() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('release_dgpt_history_list');
    } else {
      die('Forbidden access');
    }
  }

  public function get_release_dgpt_detail_list() {
    if ($this->input->is_ajax_request()) {
      $spp_pay_id = $this->core->decode($this->input->get('spp_pay_id'));
      $result = $this->release_dgpt_model->get_release_dgpt_detail_list($spp_pay_id);

      $list = array();
      foreach ($result as $row) {
        $row->bhp = number_format($row->bhp, 0, ',', '.');
        $row->latitude = $this->_dms_format($row->latitude);
        $row->longitude = $this->_dms_format($row->longitude);

        array_push($list, $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_release_dgpt_history_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->release_dgpt_model->get_release_dgpt_history_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->release_dgpt_id = $this->core->encode($row->release_dgpt_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc_type}/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        //$row->total_bhp = number_format($row->total_bhp, 0, ',', '.');
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_release_dgpt_history_detail_list() {
    if ($this->input->is_ajax_request()) {
      $release_dgpt_id = $this->core->decode($this->input->get('release_dgpt_id'));
      $result = $this->release_dgpt_model->get_release_dgpt_history_detail_list($release_dgpt_id);

      $list = array();
      foreach ($result as $row) {
        $row->bhp = number_format($row->bhp, 0, ',', '.');
        $row->latitude = $this->_dms_format($row->latitude);
        $row->longitude = $this->_dms_format($row->longitude);
        
        array_push($list, $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function add_new_release_dgpt() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $spp_pay_id = $this->core->decode($this->uri->segment(4));
      $data['header'] = $this->release_dgpt_model->get_release_dgpt_header($spp_pay_id);
      $data['header']->doc_release_date = date('d-M-Y');
      $data['details'] = $this->release_dgpt_model->get_release_dgpt_detail_list($spp_pay_id);

      $this->load->view("release_dgpt_add_new", $data);
    } else { //SAVING DATA HERE
      $this->_set_release_dgpt_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
        $spp_pay_id = $data_post['spp_pay_id'];
        $app_id = $data_post['app_id'];
        $this->db->trans_begin();

        //Upload document first
        $docId = 0;
        $doc = '';
        $path = $this->_get_release_dgpt_path();
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
            $doc = '1142/' . $pic['file_name'];

            $docData['app_id'] = $app_id;
            $docData['doc_no'] = $pic['file_name'];
            $docData['file_name'] = $pic['file_name'];
            $docData['doc_type'] = 1142;
            $docData['app_type'] = 4;

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
        $data_release_dgpt = array(
            'app_id' => $app_id,
            'doc_id' => $docId,
            'doc_issued' => MakeUpDate($data_post['doc_issued']),
        );
        $release_dgpt_id = $this->mglobal->saveData('t_trx_isr_bts_release_dgpt', $data_release_dgpt);

        //Create Release DGPT Detail
        $total_link = 0;
        $total_bhp = 0;
        foreach ($data_post['link_id'] as $key => $value) {
          $data_release_dgpt_detail = array(
              'release_dgpt_id' => $release_dgpt_id,
              'link_id' => $data_post['link_id'][$key],
              'doc_id' => $docId,
          );
          $this->mglobal->saveData('t_trx_isr_bts_release_dgpt_detail', $data_release_dgpt_detail);
          $total_link++;
//          $total_bhp += $data_post['bhp'][$key];
        }

        //Update SPP Payment
        $this->mglobal->saveData('t_trx_isr_bts_spp_pay', array('spp_pay_id' => $spp_pay_id, 'release_dgpt_id' => $release_dgpt_id), 'spp_pay_id');

        //Save log
        $logData['userPost'] = $data_post;
        $logData['header'] = $data_release_dgpt;
        $logData['detail'] = $data_release_dgpt_detail;
        $this->dblog->AuditTrail('isr_bts-release_dgpt-create', $logData);

        //CREATE NOTIFICATION
//        $templateNo = 'ISR-E021';
//        $mail_param = array(
//            '[created_by]' => $this->session->userdata('user_id'),
//            '[created_on]' => date('Y-m-d H:i:s'),
//            '[app_no]' => $data_post['app_no'],
//            '[bpv_no]' => $data_post['bpv_no'],
//            '[release_dgpt_date]' => MakeUpDate($data_post['payment_date'], 'Y-m-d', 'd M Y'),
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

  public function add_renewal_release_dgpt() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $spp_pay_id = $this->core->decode($this->uri->segment(4));
      $data['header'] = $this->release_dgpt_model->get_release_dgpt_header($spp_pay_id);
      $data['header']->doc_release_date = date('d-M-Y');
      $data['details'] = $this->release_dgpt_model->get_release_dgpt_detail_list($spp_pay_id);

      $this->load->view("release_dgpt_add_renewal", $data);
    } else { //SAVING DATA HERE
      $this->_set_release_dgpt_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
        $spp_pay_id = $data_post['spp_pay_id'];
        $app_id = $data_post['app_id'];
        $this->db->trans_begin();

        //Upload document first
        $docId = 0;
        $doc = '';
        $path = $this->_get_renewal_release_dgpt_path();
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
            $doc = '1232/' . $pic['file_name'];

            $docData['app_id'] = $app_id;
            $docData['doc_no'] = $pic['file_name'];
            $docData['file_name'] = $pic['file_name'];
            $docData['doc_type'] = 1232;
            $docData['app_type'] = 4;

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
            'prev_completed_milestone' => self::DGPT_RENEWAL,
            'milestone_status' => self::DGPT_RELEASED_RENEWAL,
            'app_id' => $app_id,
            'status_active' => 1
        );
        $this->mglobal->saveData('t_trx_isr_bts_app_link', $data_app_link, 'app_id');

        //Deactive Previous link
        $data_app_link2 = array(
            'app_id' => $data_post['ref_doc_id'],
            'status_active' => 0
        );
        $this->mglobal->saveData('t_trx_isr_bts_app_link', $data_app_link2, 'app_id');

        //Create Release DGPT
        $data_release_dgpt = array(
            'app_id' => $app_id,
            'doc_id' => $docId,
            'doc_issued' => MakeUpDate($data_post['doc_issued']),
        );
        $release_dgpt_id = $this->mglobal->saveData('t_trx_isr_bts_release_dgpt', $data_release_dgpt);

        //Create Release DGPT Detail
        $total_link = 0;
        $total_bhp = 0;
        foreach ($data_post['link_id'] as $key => $value) {
          $data_release_dgpt_detail = array(
              'release_dgpt_id' => $release_dgpt_id,
              'link_id' => $data_post['link_id'][$key],
              'doc_id' => $docId,
          );
          $this->mglobal->saveData('t_trx_isr_bts_release_dgpt_detail', $data_release_dgpt_detail);
          $total_link++;
//          $total_bhp += $data_post['bhp'][$key];
        }

        //Update SPP Payment
        $this->mglobal->saveData('t_trx_isr_bts_spp_pay', array('spp_pay_id' => $spp_pay_id, 'release_dgpt_id' => $release_dgpt_id), 'spp_pay_id');

        //Save log
        $logData['userPost'] = $data_post;
        $logData['header'] = $data_release_dgpt;
        $logData['detail'] = $data_release_dgpt_detail;
        $this->dblog->AuditTrail('isr_bts-release_dgpt-create', $logData);

        //CREATE NOTIFICATION
//        $templateNo = 'ISR-E021';
//        $mail_param = array(
//            '[created_by]' => $this->session->userdata('user_id'),
//            '[created_on]' => date('Y-m-d H:i:s'),
//            '[app_no]' => $data_post['app_no'],
//            '[bpv_no]' => $data_post['bpv_no'],
//            '[release_dgpt_date]' => MakeUpDate($data_post['payment_date'], 'Y-m-d', 'd M Y'),
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

  public function add_discontinued_release_dgpt() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $spp_pay_id = $this->core->decode($this->uri->segment(4));
      $data['header'] = $this->release_dgpt_model->get_release_dgpt_header($spp_pay_id);
      $data['header']->doc_release_date = date('d-M-Y');
      $data['details'] = $this->release_dgpt_model->get_release_dgpt_detail_list($spp_pay_id);

      $this->load->view("release_dgpt_add_discontinued", $data);
    } else { //SAVING DATA HERE
      $this->_set_release_dgpt_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
//        $spp_pay_id = $data_post['spp_pay_id'];
        $app_id = $data_post['app_id'];
        $this->db->trans_begin();

        //Upload document first
        $docId = 0;
        $doc = '';
        $doc_type = intval($data_post['milestone_status']) + 1;
        $path = $this->_get_discontinued_release_dgpt_path($doc_type);
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
            $doc = "{$doc_type}/{$pic['file_name']}";

            $docData['app_id'] = $app_id;
            $docData['doc_no'] = $pic['file_name'];
            $docData['file_name'] = $pic['file_name'];
            $docData['doc_type'] = $doc_type;
            $docData['app_type'] = 4;

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
            'prev_completed_milestone' => $data_post['milestone_status'],
            'milestone_status' => intval($data_post['milestone_status']) + 10,
            'app_id' => $app_id,
            'status_active' => 0
        );
        $this->mglobal->saveData('t_trx_isr_bts_app_link', $data_app_link, 'app_id');

        //Create Release DGPT
        $data_release_dgpt = array(
            'app_id' => $app_id,
            'doc_id' => $docId,
            'doc_issued' => MakeUpDate($data_post['doc_issued']),
        );
        $release_dgpt_id = $this->mglobal->saveData('t_trx_isr_bts_release_dgpt', $data_release_dgpt);

        //Create Release DGPT Detail
        $total_link = 0;
        $total_bhp = 0;
        foreach ($data_post['link_id'] as $key => $value) {
          $data_release_dgpt_detail = array(
              'release_dgpt_id' => $release_dgpt_id,
              'link_id' => $data_post['link_id'][$key],
              'doc_id' => $docId,
          );
          $this->mglobal->saveData('t_trx_isr_bts_release_dgpt_detail', $data_release_dgpt_detail);
          $total_link++;
//          $total_bhp += $data_post['bhp'][$key];
        }

        //Save log
        $logData['userPost'] = $data_post;
        $logData['header'] = $data_release_dgpt;
        $logData['detail'] = $data_release_dgpt_detail;
        $this->dblog->AuditTrail('isr_bts-release_dgpt-create', $logData);

        //CREATE NOTIFICATION
//        $templateNo = 'ISR-E021';
//        $mail_param = array(
//            '[created_by]' => $this->session->userdata('user_id'),
//            '[created_on]' => date('Y-m-d H:i:s'),
//            '[app_no]' => $data_post['app_no'],
//            '[bpv_no]' => $data_post['bpv_no'],
//            '[release_dgpt_date]' => MakeUpDate($data_post['payment_date'], 'Y-m-d', 'd M Y'),
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

  private function _set_release_dgpt_validation() {
    $this->form_validation->set_rules('doc_issued', 'Doc Issued Date', 'trim|required');
  }

  private function _get_release_dgpt_path() {
    $path_bts = 'file/upload/isr_bts';
    $path_resi = '1142';
    $path = $path_bts . '/' . $path_resi;

    if (!file_exists($path)) {
      $make_dir = mkdir($path, 0777, true);
      if ($make_dir == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to make directory "Release DGPT"'
        ));
        exit();
      }
    }

    return $path;
  }

  private function _get_renewal_release_dgpt_path() {
    $path_bts = 'file/upload/isr_bts';
    $path_resi = '1232';
    $path = $path_bts . '/' . $path_resi;

    if (!file_exists($path)) {
      $make_dir = mkdir($path, 0777, true);
      if ($make_dir == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to make directory "Release DGPT"'
        ));
        exit();
      }
    }

    return $path;
  }

  private function _get_discontinued_release_dgpt_path($doc_type=1332) {
    $path_bts = 'file/upload/isr_bts';
    $path = $path_bts . '/' . $doc_type;

    if (!file_exists($path)) {
      $make_dir = mkdir($path, 0777, true);
      if ($make_dir == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to make directory "Release DGPT"'
        ));
        exit();
      }
    }

    return $path;
  }

  //Change string to DMS format
  private function _dms_format($param = '') {
    $arr = explode(' ', $param);
    $lat_deg = $arr[0];
    $lat_min = $arr[1];
    $lat_sec = number_format($arr[2], 2);
    $lat_dir = strtoupper($arr[3]);

    return "{$lat_deg}&deg{$lat_min}'{$lat_sec}\"{$lat_dir}";
  }
  
}

?>
