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
class Validation_reg extends MX_Controller {
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
  //DOC
  const IOM_DOC = '1142';

  protected $_user_now;
  protected $_date_now;

  public function __construct() {
    parent::__construct();
    $this->load->model('validation_reg_model');
    $this->mglobal->is_logged_in();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function index() {
    $data['content_id'] = 'validation_reg_list';
    $this->_generate_view($data);
  }

  protected function _generate_view($data, $template = 'template/smashing') {
    $this->load->view($template, $data);
  }

  public function get_validation_reg_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = $post_data['page'];
      $limit = $post_data['rows'];
      $offset = ($page - 1) * $limit;
      $q = isset($post_data['q']) ? $post_data['q'] : '';

      $WarningDay = $this->mglobal->showdata('lookup_value', 't_mtr_lookup', array('lookup_key' => 'ISR_AlertDay'));
      $result = $this->validation_reg_model->get_validation_reg_list($q, $limit, $offset, $WarningDay);

      $data['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->app_id = $this->core->encode($row->app_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url() . "file/upload/isr_bts/" . $row->doc . "'>" . $this->core->setFileNameIcon($row->doc) . '</a>';

        $datetime1 = new DateTime(date('Y-m-d'));
        $datetime2 = new DateTime(date('Y-m-d', strtotime($row->app_expired)));
        $interval = $datetime1->diff($datetime2);
        $diff = $interval->format('%R%a');

        if ($diff > 0) {
          $row->status = '<span style="color:blue">Expired in ' . intval($diff) . ' days</span>';
        } else {
          $row->status = '<span style="color:orange">This application already expired</span>';
        }

        $menu = $this->mmenu->getMenuId("isr_bts");
        if ($this->mmenu->cekPermission($menu, 'menu_edit'))
          $row->action = $this->core->setButton('circle_ok', 'validateLink("' . $row->app_id . '")', 'Validate');

        $row->app_expired = date('d/m/Y', strtotime($row->app_expired));

        array_push($data['rows'], $row);
      }
      $data['total'] = $result['total'];

      echo json_encode($data);
    } else {
      die('Forbidden access');
    }
  }

  public function get_validation_reg_detail_list() {
    if ($this->input->is_ajax_request()) {
      $app_id = $this->core->decode($this->input->get('app_id'));
      $result = $this->validation_reg_model->get_validation_reg_detail_list($app_id);

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

  public function add_validation_reg() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $app_id = $this->core->decode($this->uri->segment(4));
      $data['header'] = $this->validation_reg_model->get_validation_reg_header($app_id);
      $data['details'] = $this->validation_reg_model->get_validation_reg_detail_list($app_id);

      $this->load->view("validation_reg_add", $data);
    } else { //SAVING DATA HERE
      $this->_set_validation_reg_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
        $app_no = $data_post['app_no'];
        $app_id = $data_post['app_id'];
        $this->db->trans_begin();

        //Upload document first
        $docId = 0;
        $doc = '';
        $path = $this->_get_validation_reg_path();
        if (!empty($_FILES['doc']['name'])) {
          $config['upload_path'] = $path;
          $config['allowed_types'] = '*';
          $config['max_size'] = '500000';
          $config['file_name'] = 'iOM_' . $app_no . '_' . date('dMY');
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
            $doc = self::IOM_DOC . '/' . $pic['file_name'];

            $docData['app_id'] = $app_id;
            $docData['doc_no'] = $pic['file_name'];
            $docData['file_name'] = $pic['file_name'];
            $docData['doc_type'] = self::IOM_DOC;
            $docData['app_type'] = 4;

            $docId = $this->mglobal->saveData('t_trx_isr_bts_doc', $docData);
          }
        }

        //Create or Update App
        if ($data_post['milestone_status'][0] == 1261) { // Renew Application
          $app_old = $this->db->get_where('t_trx_isr_bts_app', array('app_id' => $app_id))->row();
          $data_app_renew = array(
              'app_no' => $app_old->app_no,
              'license_number' => $app_old->license_number,
              'ref_doc_id' => $app_old->app_id,
              'doc' => $doc,
              'remark' => $data_post['description']
          );
          $app_id_renew = $this->mglobal->saveData('t_trx_isr_bts_app', $data_app_renew);
          $app_id = $app_id_renew;
        } else { //Discontinued Application
          $data_app = array(
              'app_id' => $app_id,
              'doc' => $doc,
              'remark' => $data_post['description'],
          );
          $this->mglobal->saveData('t_trx_isr_bts_app', $data_app, 'app_id');
        }

        foreach ($data_post['link'] as $key => $value) {
          //$p['reason'] = $d['reason'][0];
          $milestone_status = $data_post['milestone_status'][0];

          if ($data_post['milestone_status'][0] == 1261) { //Insert Renewal Application Link
            $app_link_old = $this->db->get_where('t_trx_isr_bts_app_link', array('app_link_id' => $value))->row_array();
            unset($app_link_old['app_link_id']);
            $app_link_renew = array(
                'app_id' => $app_id,
                'milestone_status' => $milestone_status,
                'status_active' => 2,
                'remark' => $data_post['description'],
                'prev_completed_milestone' => $app_link_old['milestone_status'],
                'updated_by' => NULL,
                'updated_on' => NULL,
                'email_reminder' => NULL,
                'engineering_approval_status' => NULL,
                'approved_by' => NULL,
                'approved_on' => NULL,
                'rejected_by' => NULL,
                'rejected_on' => NULL,
                'email_rejected' => NULL
            );
            $data_app_link = array_replace($app_link_old, $app_link_renew);
            $link_id = $this->mglobal->saveData('t_trx_isr_bts_app_link', $data_app_link);
          } else {
            $data_app_link = array(
                'app_link_id' => $value,
                'status_active' => 2,
                'milestone_status' => $milestone_status,
                'remark' => $data_post['description'],
                'engineering_approval_status' => NULL,
                'approved_by' => NULL,
                'approved_on' => NULL,
                'rejected_by' => NULL,
                'rejected_on' => NULL,
                'email_rejected' => NULL
            );
            $this->mglobal->saveData('t_trx_isr_bts_app_link', $data_app_link, 'app_link_id');
            $link_id = $value;
          }

          //Save ISR Validation
          $p['description'] = $data_post['description'];
          if ($milestone_status == '1361')
            $p['milestone_status'] = 'Discontinued (Dismantle)';
          elseif ($milestone_status == '1261')
            $p['milestone_status'] = 'Renewal';
          else
            $p['milestone_status'] = 'Discontinued (ISR Changed)';
          $p['link_id'] = $link_id;
          $q = $this->mglobal->saveData('t_trx_isr_bts_link_validation', $p);

//          if (isset($files['doc']['name'][$key])) {
//            $_FILES['userfile']['name'] = $files['doc']['name'][$key];
//            $_FILES['userfile']['type'] = $files['doc']['type'][$key];
//            $_FILES['userfile']['tmp_name'] = $files['doc']['tmp_name'][$key];
//            $_FILES['userfile']['error'] = $files['doc']['error'][$key];
//            $_FILES['userfile']['size'] = $files['doc']['size'][$key];
//            $config['upload_path'] = 'file/upload/isr/1412';
//            $config['allowed_types'] = '*';
//            $config['max_size'] = '500000';
//            $config['file_name'] = 'iOM_Link_' . $app_no . '_' . date('dMY') . '_' . $value;
//            $this->load->library('upload', $config);
//            $this->upload->initialize($config);
//
//            if ($this->upload->do_upload()) {
//              $pic = $this->upload->data();
//              $doc['doc_id'] = '';
//              $doc['app_id'] = $app_id;
//              $doc['doc_no'] = 'iOM_' . $app_id . '_' . $value . '_' . date('dMY');
//              $doc['doc_type'] = '1412';
//              $doc['link_id'] = $value;
//              $doc['file_name'] = $pic['file_name'];
//              $q = $this->mglobal->saveData('t_trx_isr_doc', $doc, 'doc_id');
//            }
//          }
        }

//        $templateNo = 'ISR-VAL'; //andedi
//        $q = $this->misr->getLinksByRef('t_trx_isr_app_link', array('master.app_id' => $app_id));
//
//        $arr_status = array(
//            1261 => 'Renewal',
//            1361 => 'Discontinued',
//            1661 => 'Discontinued - ISR Changed'
//        );
//
//        $linksDetail = array();
//        $i = 0;
//        foreach ($q->result() as $row) {
//          $linksDetail[$i]['station_id'] = $row->station_no;
//          $linksDetail[$i]['zona'] = $row->zone_id;
//          $linksDetail[$i]['region'] = $row->region;
//          $linksDetail[$i]['ne_id'] = $row->site_code_ne;
//          $linksDetail[$i]['ne_name'] = $row->nearendsite;
//          $linksDetail[$i]['fe_id'] = $row->site_code_fe;
//          $linksDetail[$i]['fe_name'] = $row->farendsite;
//          $linksDetail[$i]['equipment'] = $row->equipment;
//          $linksDetail[$i]['status'] = $arr_status[$row->milestone_status];
//          $linksDetail[$i]['remark'] = $row->remark;
////                $linksDetail[$i]['bhp'] = $row->bhp;
//          $linksDetail[$i]['freq'] = "Tx : " . $row->freq_tx . "<br /> Rx:" . $row->freq_rx;
//          $region[] = $row->region;
////                    $totalBhp+=$row->bhp;
//          $i++;
//        }
//
//        $mail_param = array(
//            '[created_by]' => $this->mglobal->getUserFullName($this->_user_now),
//            '[created_on]' => $this->_date_now,
//            '[app_no]' => $app_no,
//            '[start_date]' => date('d-m-Y', strtotime($d['issued_date'])),
//            '[expired_date]' => date('d-m-Y', strtotime($d['expired_date'])),
//            '[remark]' => $d['description'],
//        );
//
//        $schedule_param = array('date' => date('Y-m-d'), 'time' => date('G:i:s'), 'foreign_id' => $app_id);
//
//        $this->mglobal->pushMail($templateNo, array_unique($region), $mail_param, $schedule_param, '', $linksDetail);

        if ($this->db->trans_status() === TRUE) {
          $this->db->trans_commit();
          echo json_encode(array(
              'status' => 'OK',
              'hasil' => 'Application has been validated successfully.'
          ));
        } else {
          $this->db->trans_rollback();
          echo json_encode(array(
              'status' => 'error',
              'hasil' => 'Validate Application failed.'
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

  private function _set_validation_reg_validation() {
    $this->form_validation->set_rules('app_id', 'App ID', 'trim|required');
    $this->form_validation->set_rules('app_no', 'App No', 'trim|required');
    $this->form_validation->set_rules('description', 'Description', 'trim|required');
  }

  private function _get_validation_reg_path() {
    $path = 'file/upload/isr_bts/' . self::IOM_DOC;

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
