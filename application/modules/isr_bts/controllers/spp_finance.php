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
class Spp_finance extends MX_Controller {
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

  protected $_user_now;
  protected $_date_now;

  public function __construct() {
    parent::__construct();
    $this->load->model('spp_finance_model');
    $this->mglobal->is_logged_in();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function index() {
    $data['content_id'] = 'spp_finance_list';
    $this->_generate_view($data);
  }

  protected function _generate_view($data, $template = 'template/smashing') {
    $this->load->view($template, $data);
  }

  public function load_spp_finance_new() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('spp_finance_new_list');
    } else {
      die('Forbidden access');
    }
  }

  public function load_spp_finance_renewal() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('spp_finance_renewal_list');
    } else {
      die('Forbidden access');
    }
  }

  public function load_spp_finance_submitted() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('spp_finance_submitted_list');
    } else {
      die('Forbidden access');
    }
  }

  public function get_spp_finance_new_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->spp_finance_model->get_spp_finance_new_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/spp_finance");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $app_resi_id = $this->core->encode($row->app_resi_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
          $row->action.= '<button title="Upload Doc" class="btn btn-action glyphicons upload btn-success"  onclick="uploadNew(\'' . $app_resi_id . '\')"><i></i></button> &nbsp;';
        }
        $row->app_resi_id = $app_resi_id;

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_spp_finance_renewal_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->spp_finance_model->get_spp_finance_renewal_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/spp_finance");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $app_id = $this->core->encode($row->app_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
          $row->action.= '<button title="Upload Doc" class="btn btn-action glyphicons upload btn-success"  onclick="uploadRenewal(\'' . $app_id . '\')"><i></i></button> &nbsp;';
        }
        $row->app_id = $app_id;

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_spp_finance_detail_list() {
    if ($this->input->is_ajax_request()) {
      $app_resi_id = $this->core->decode($this->input->get('app_resi_id'));
      
      $result = $this->spp_finance_model->get_spp_finance_detail_list($app_resi_id);
      foreach ($result as $key => $value) {
        $result[$key]->latitude = $this->_dms_format($result[$key]->latitude);
        $result[$key]->longitude = $this->_dms_format($result[$key]->longitude);
      }

      echo json_encode($result);
    } else {
      die('Forbidden access');
    }
  }

  public function get_spp_finance_detail_list2() {
    if ($this->input->is_ajax_request()) {
      $app_id = $this->core->decode($this->input->get('app_id'));
      
      $result = $this->spp_finance_model->get_spp_finance_detail_list2($app_id);
      foreach ($result as $key => $value) {
        $result[$key]->latitude = $this->_dms_format($result[$key]->latitude);
        $result[$key]->longitude = $this->_dms_format($result[$key]->longitude);
      }

      echo json_encode($result);
    } else {
      die('Forbidden access');
    }
  }

  public function get_spp_finance_submitted_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->spp_finance_model->get_spp_finance_submitted_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->spp_fin_id = $this->core->encode($row->spp_fin_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc_type}/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->total_bhp = number_format($row->total_bhp, 0, ',', '.');
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_spp_finance_submitted_detail_list() {
    if ($this->input->is_ajax_request()) {
      $spp_fin_id = $this->core->decode($this->input->get('spp_fin_id'));
      $result = $this->spp_finance_model->get_spp_finance_submitted_detail_list($spp_fin_id);

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

  public function add_new_spp_finance() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $app_resi_id = $this->core->decode($this->uri->segment(4));
      $data['resi'] = $this->spp_finance_model->get_response_by_resi($app_resi_id);
      $data['resi']->app_resi_id = $this->core->encode($data['resi']->app_resi_id);
      $data['resi']->resi_issued = date('d M Y', strtotime($data['resi']->resi_issued));


      $data['resi_detail'] = $this->spp_finance_model->get_spp_finance_detail_list($app_resi_id);
      $this->load->view("spp_finance_add_new", $data);
    } else { //SAVING DATA HERE
      $this->_set_spp_finance_new_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
        $app_resi_id = $this->core->decode($data_post['app_resi_id']);
        $this->db->trans_begin();

        //Upload document first
        $docId = 0;
        $path = $this->_get_spp_finance_path();
        $upload = FALSE;
        $doc= '';
        if (!empty($_FILES['doc']['name'])) {

          $config['upload_path'] = $path;
          $config['allowed_types'] = '*';
          $config['max_size'] = '100000';
          $config['file_name'] = 'SPP-Finance_' . $data_post['app_no'] . '_' . date('dMY');

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
            $doc = '1122/' . $pic['file_name'];
            $upload = TRUE;
          }
        }

        //Create App
        $data_app = array(
            'app_no' => $data_post['app_no'],
            'app_issued' => ($data_post['app_issued'] != '') ? MakeUpDate($data_post['app_issued']) : NULL,
            'app_expired' => ($data_post['app_expired'] != '') ? MakeUpDate($data_post['app_expired']) : NULL,
            'doc' => $doc,
            'remark' => $data_post['remark'],
            'ref_doc_id' => $app_resi_id,
            'isUsed' => 1,
            'license_number' => $data_post['license_number'],
        );
        $app_id = $this->mglobal->saveData('t_trx_isr_bts_app', $data_app);

        if ($upload == TRUE) {
          $docData['app_id'] = $app_id;
          $docData['doc_no'] = $pic['file_name'];
          $docData['file_name'] = $pic['file_name'];
          $docData['doc_type'] = 1122;
          $docData['app_type'] = 4;

          $docId = $this->mglobal->saveData('t_trx_isr_bts_doc', $docData);
        }

        //Create App Link from resi link
        $links = array();
        $this->db->order_by('station_identifier', 'asc');
        $resi_links = $this->db->get_where('t_trx_isr_bts_app_resi_link', array('app_resi_id' => $app_resi_id))->result_array();
        foreach ($resi_links as $row) {
          unset($row['resi_link_id'], $row['app_resi_id'], $row['created_by'], $row['created_on'], $row['updated_by'], $row['updated_on']);
          $row['app_id'] = $app_id;
          $row['status_active'] = self::ON_PROCESS;
          $row['prev_completed_milestone'] = self::SPP_FIN_NEW;
          $row['milestone_status'] = self::SPP_PAY_NEW;
          $links[] = $this->mglobal->saveData('t_trx_isr_bts_app_link', $row);
        }

        //Update milestone app resi link
        $this->db->where('app_resi_id', $app_resi_id);
        $this->db->update('t_trx_isr_bts_app_resi_link', array('prev_completed_milestone' => self::SPP_FIN_NEW, 'milestone_status' => self::SPP_PAY_NEW));

        //Create Spp Finance
        $data_spp_fin = array(
            'app_id' => $app_id,
            'spp_no' => $data_post['spp_no'],
            'spp_start_date' => MakeUpDate($data_post['spp_start_date']),
            'spp_due_date' => MakeUpDate($data_post['spp_due_date']),
            'give_to_fin_date' => MakeUpDate($data_post['give_to_fin_date']),
            'remark' => $data_post['remark'],
            'doc_id' => $docId,
        );
        $spp_fin_id = $this->mglobal->saveData('t_trx_isr_bts_spp_fin', $data_spp_fin);

        //Create Spp Finance Detail
        $total_link = 0;
        $total_bhp = 0;
        foreach ($data_post['station_id'] as $key => $value) {
          $data_spp_fin_detail = array(
              'spp_fin_id' => $spp_fin_id,
              'link_id' => $links[$key],
              'bhp' => $data_post['bhp'][$key],
              'doc_id' => $docId,
          );
          $this->mglobal->saveData('t_trx_isr_bts_spp_fin_detail', $data_spp_fin_detail);
          $total_link++;
          $total_bhp += $data_post['bhp'][$key];
        }

        //Save log
        $logData['userPost'] = $data_post;
        $logData['header'] = $data_spp_fin;
        $logData['detail'] = $data_spp_fin_detail;
        $this->dblog->AuditTrail('isr_bts-spp_finance-create', $logData);

        //Create notification
//        $mail_param = array(
//            '[created_by]' => $this->session->userdata('user_id'),
//            '[created_on]' => date('Y-m-d H:i:s'),
//            '[app_no]' => $data_post['app_no'],
//            '[resi_no]' => $data_post['resi_no'],
//            '[resi_issued]' => $data_post['resi_issued'],
//            '[app_issued_date]' => $data_post['app_issued'],
//            '[app_expired_date]' => $data_post['app_expired'],
//            '[spp_no]' => $data_post['spp_no'],
//            '[spp_issued_date]' => $data_post['spp_start_date'],
//            '[spp_due_date]' => $data_post['spp_due_date'],
//            '[submitted_to_finance]' => $data_post['give_to_fin_date'],
//            '[spp_remark]' => $data_post['remark'],
//            '[total_links]' => $total_link,
//            '[total_bhp]' => $total_bhp
//        );
//        $schedule_param = array('date' => date('Y-m-d'), 'time' => date('G:i:s'), 'foreign_id' => $app_id);
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

  public function add_renewal_spp_finance() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $app_id = $this->core->decode($this->uri->segment(4));
      $data['app'] = new stdClass();
      $data['app']->app_id = $this->uri->segment(4);

      //get previous application
      $previous_app = $this->spp_finance_model->get_previous_application($app_id);
      $data['app']->app_no = $previous_app->app_no;
      $data['app']->license_number = $previous_app->license_number;

      $app_issued = new DateTime($previous_app->app_expired);
      $app_issued->add(new DateInterval('P1D')); // + 1 day
      $data['app']->app_issued = $app_issued->format('d-M-Y');

      $app_expired = new DateTime($previous_app->app_expired);
      $app_expired->add(new DateInterval('P1Y')); // + 1 year
      $data['app']->app_expired = $app_expired->format('d-M-Y');

      $spp_start_date = new DateTime($previous_app->app_expired);
      $interval_date = new DateInterval('P60D');
      $interval_date->invert = 1; //Make it negative. 
      $spp_start_date->add($interval_date); // + 60 days
      $data['app']->spp_start_date = $spp_start_date->format('d-M-Y');

      $data['app']->spp_due_date = date('d-M-Y', strtotime($previous_app->app_expired));
      $data['app']->give_to_fin_date = date('d-M-Y');

      $bhp = $this->spp_finance_model->get_last_bhp($previous_app->app_id_2);
      $data['app_detail'] = $this->spp_finance_model->get_spp_finance_detail_list2($app_id);
      foreach ($data['app_detail'] as $key => $value) {
        $data['app_detail'][$key]->bhp = $bhp[$key]->bhp;
      }

      $this->load->view("spp_finance_add_renewal", $data);
    } else { //SAVING DATA HERE
      $this->_set_spp_finance_new_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
        $app_id = $this->core->decode($data_post['app_id']);
        $this->db->trans_begin();

        //Upload document first
        $docId = 0;
        $doc = '';
        $path = $this->_get_spp_finance_renewal_path();
        if (!empty($_FILES['doc']['name'])) {

          $config['upload_path'] = $path;
          $config['allowed_types'] = '*';
          $config['max_size'] = '100000';
          $config['file_name'] = 'SPP-Finance_' . $data_post['app_no'] . '_' . date('dMY');

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
            $doc = '1212/' . $pic['file_name'];

            $docData['app_id'] = $app_id;
            $docData['doc_no'] = $pic['file_name'];
            $docData['file_name'] = $pic['file_name'];
            $docData['doc_type'] = 1212;
            $docData['app_type'] = 4;

            $docId = $this->mglobal->saveData('t_trx_isr_bts_doc', $docData);
          }
        }

        //Update App
        $data_app = array(
            'app_id' => $app_id,
            'app_no' => $data_post['app_no'],
            'app_issued' => ($data_post['app_issued'] != '') ? MakeUpDate($data_post['app_issued']) : NULL,
            'app_expired' => ($data_post['app_expired'] != '') ? MakeUpDate($data_post['app_expired']) : NULL,
            'doc' => $doc,
            'remark' => $data_post['remark'],
            'isUsed' => 1,
            'license_number' => $data_post['license_number'],
        );
        $this->mglobal->saveData('t_trx_isr_bts_app', $data_app, 'app_id');

        //Update milestone app link
        $this->db->where('app_id', $app_id);
        $this->db->update('t_trx_isr_bts_app_link', array('prev_completed_milestone' => self::SPP_FIN_RENEWAL, 'milestone_status' => self::SPP_PAY_RENEWAL));

        //Create Spp Finance
        $data_spp_fin = array(
            'app_id' => $app_id,
            'spp_no' => $data_post['spp_no'],
            'spp_start_date' => MakeUpDate($data_post['spp_start_date']),
            'spp_due_date' => MakeUpDate($data_post['spp_due_date']),
            'give_to_fin_date' => MakeUpDate($data_post['give_to_fin_date']),
            'remark' => $data_post['remark'],
            'doc_id' => $docId,
        );
        $spp_fin_id = $this->mglobal->saveData('t_trx_isr_bts_spp_fin', $data_spp_fin);

        //Create Spp Finance Detail
        $total_link = 0;
        $total_bhp = 0;
        foreach ($data_post['station_id'] as $key => $value) {
          $data_spp_fin_detail = array(
              'spp_fin_id' => $spp_fin_id,
              'link_id' => $data_post['app_link_id'][$key],
              'bhp' => $data_post['bhp'][$key],
              'doc_id' => $docId,
          );
          $this->mglobal->saveData('t_trx_isr_bts_spp_fin_detail', $data_spp_fin_detail);
          $total_link++;
          $total_bhp += $data_post['bhp'][$key];
        }

        //Save log
        $logData['userPost'] = $data_post;
        $logData['header'] = $data_spp_fin;
        $logData['detail'] = $data_spp_fin_detail;
        $this->dblog->AuditTrail('isr_bts-spp_finance-create', $logData);

        //Create notification
//        $mail_param = array(
//            '[created_by]' => $this->session->userdata('user_id'),
//            '[created_on]' => date('Y-m-d H:i:s'),
//            '[app_no]' => $data_post['app_no'],
//            '[resi_no]' => $data_post['resi_no'],
//            '[resi_issued]' => $data_post['resi_issued'],
//            '[app_issued_date]' => $data_post['app_issued'],
//            '[app_expired_date]' => $data_post['app_expired'],
//            '[spp_no]' => $data_post['spp_no'],
//            '[spp_issued_date]' => $data_post['spp_start_date'],
//            '[spp_due_date]' => $data_post['spp_due_date'],
//            '[submitted_to_finance]' => $data_post['give_to_fin_date'],
//            '[spp_remark]' => $data_post['remark'],
//            '[total_links]' => $total_link,
//            '[total_bhp]' => $total_bhp
//        );
//        $schedule_param = array('date' => date('Y-m-d'), 'time' => date('G:i:s'), 'foreign_id' => $app_id);
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

  private function _set_spp_finance_new_validation() {
    $this->form_validation->set_rules('app_no', 'App No', 'trim|required');
    $this->form_validation->set_rules('spp_no', 'SPP No', 'trim|required');
    $this->form_validation->set_rules('spp_start_date', 'SPP Issued Date', 'trim|required');
    $this->form_validation->set_rules('spp_due_date', 'SPP Due Date', 'trim|required');
    $this->form_validation->set_rules('give_to_fin_date', 'Submit to Finance Date', 'trim|required');
    $this->form_validation->set_rules('station_id[]', 'Station ID', 'trim|required');
    $this->form_validation->set_rules('bhp[]', 'BHP', 'trim|required');
  }

  private function _get_spp_finance_path() {
    $path_bts = 'file/upload/isr_bts';
    $path_resi = '1122';
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

  private function _get_spp_finance_renewal_path() {
    $path_bts = 'file/upload/isr_bts';
    $path_resi = '1212';
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
