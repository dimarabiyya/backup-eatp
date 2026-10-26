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
class M2m extends MX_Controller {
  //  ISR Status
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

  protected $_user_now;
  protected $_date_now;

  public function __construct() {
    parent::__construct();
    $this->load->model('m2m_model');
    $this->mglobal->is_logged_in();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function index() {
    $data['content_id'] = 'm2m_list';
    $this->_generate_view($data);
  }

  protected function _generate_view($data, $template = 'template/smashing') {
    $this->load->view($template, $data);
  }

  public function load_m2m_new() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('m2m_list_new');
    } else {
      die('Forbidden access');
    }
  }

  public function load_m2m_discontinued() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('m2m_list_discontinued');
    } else {
      die('Forbidden access');
    }
  }

  public function load_m2m_history_new() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('m2m_history_new_list');
    } else {
      die('Forbidden access');
    }
  }

  public function load_m2m_history_discontinued() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('m2m_history_discontinued_list');
    } else {
      die('Forbidden access');
    }
  }

  public function get_m2m_new_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->m2m_model->get_m2m_new_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/engineering_approval");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $app_resi_id = $this->core->encode($row->app_resi_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/engineering_approval/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
          $row->action = '<a title="Detail M2M" href="#" onclick="viewNewM2m(\'' . $app_resi_id . '\')" class="btn-action glyphicons eye_open btn-success" ><i></i></a> &nbsp;';
          $row->action .= '<a title="Send to SDPPI" href="#" class="btn btn-action glyphicons iphone_exchange btn-success"  onclick="addNewM2m(\'' . $app_resi_id . '\')"><i></i></a> &nbsp;';
          $row->action .= '<a title="Non M2M" href="#" class="btn btn-action glyphicons imac btn-success"  onclick="addNewNonM2m(\'' . $app_resi_id . '\')"><i></i></a> &nbsp;';
        }
        $row->app_resi_id = $app_resi_id;

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_m2m_discontinued_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->m2m_model->get_m2m_discontinued_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/engineering_approval");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $app_id = $this->core->encode($row->app_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
          $row->action = '<a title="Detail M2M" href="#" onclick="viewDiscontinuedM2m(\'' . $app_id . '\')" class="btn-action glyphicons eye_open btn-success" ><i></i></a> &nbsp;';
          $row->action .= '<a title="Send to SDPPI" href="#" class="btn btn-action glyphicons iphone_exchange btn-success"  onclick="addDiscontinuedM2m(\'' . $app_id . '\')"><i></i></a> &nbsp;';
          $row->action .= '<a title="Non M2M" href="#" class="btn btn-action glyphicons imac btn-success"  onclick="addDiscontinuedNonM2m(\'' . $app_id . '\')"><i></i></a> &nbsp;';
        }
        $row->app_id = $app_id;
        
        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_m2m_history_new_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->m2m_model->get_m2m_history_new_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->app_resi_id = $this->core->encode($row->app_resi_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";
//
//        $row->action = '';
//        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
//          $row->action = '<a title="Detail M2M" href="#" onclick="viewNewM2m(\'' . $app_resi_id . '\')" class="btn-action glyphicons eye_open btn-success" ><i></i></a> &nbsp;';
//          $row->action .= '<a title="Send to SDPPI" href="#" class="btn btn-action glyphicons iphone_exchange btn-success"  onclick="addNewM2m(\'' . $app_resi_id . '\')"><i></i></a> &nbsp;';
//          $row->action .= '<a title="Non M2M" href="#" class="btn btn-action glyphicons imac btn-success"  onclick="addNewNonM2m(\'' . $app_resi_id . '\')"><i></i></a> &nbsp;';
//        }
        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_m2m_history_discontinued_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->m2m_model->get_m2m_history_discontinued_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->app_id = $this->core->encode($row->app_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url($this->_get_discontinued_m2m_path() . "/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";
//
//        $row->action = '';
//        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
//          $row->action = '<a title="Detail M2M" href="#" onclick="viewNewM2m(\'' . $app_resi_id . '\')" class="btn-action glyphicons eye_open btn-success" ><i></i></a> &nbsp;';
//          $row->action .= '<a title="Send to SDPPI" href="#" class="btn btn-action glyphicons iphone_exchange btn-success"  onclick="addNewM2m(\'' . $app_resi_id . '\')"><i></i></a> &nbsp;';
//          $row->action .= '<a title="Non M2M" href="#" class="btn btn-action glyphicons imac btn-success"  onclick="addNewNonM2m(\'' . $app_resi_id . '\')"><i></i></a> &nbsp;';
//        }
        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_m2m_detail_list() {
    if ($this->input->is_ajax_request()) {
      $app_resi_id = $this->core->decode($this->input->get('app_resi_id'));
      $result = $this->m2m_model->get_m2m_detail_list($app_resi_id);
      
      foreach ($result as $key => $value) {
        $result[$key]->latitude = dms_format($result[$key]->latitude);
        $result[$key]->longitude = dms_format($result[$key]->longitude);
      }

      echo json_encode($result);
    } else {
      die('Forbidden access');
    }
  }

  public function get_m2m_detail_list2() {
    if ($this->input->is_ajax_request()) {
      $app_id = $this->core->decode($this->input->get('app_id'));
      $result = $this->m2m_model->get_m2m_detail_list2($app_id);
      
      foreach ($result as $key => $value) {
        $result[$key]->latitude = dms_format($result[$key]->latitude);
        $result[$key]->longitude = dms_format($result[$key]->longitude);
      }
      
      echo json_encode($result);
    } else {
      die('Forbidden access');
    }
  }

  public function add_new_m2m() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $app_resi_id = $this->core->decode($this->uri->segment(4));
      $data['resi'] = $this->db->get_where('t_trx_isr_bts_app_resi', array('app_resi_id' => $app_resi_id))->row();
      $data['resi']->app_resi_id = $this->core->encode($data['resi']->app_resi_id);
      $data['resi']->app_issued = date('d M Y', strtotime($data['resi']->app_issued));
//    $data['resi']->doc_old = $data['resi']->doc;
//    $data['resi']->download_doc = "<a download='' target='_blank' href='" . base_url($this->get_resi_path()) . "/{$data['resi']->doc}'>{$this->core->setFileNameIcon($data['resi']->doc)}</a>";

      $data['transmitter_polarization_opt'] = array();
      foreach ($this->mglobal->lookup('bts_polarization') as $value) {
        $data['transmitter_polarization_opt'][$value->lookup_value] = $value->lookup_desc;
      }

      $data['resi_detail'] = array();
      $this->db->select('*, bandwidth::INT AS bandwidth', false);
      $detail = $this->db->get_where('t_trx_isr_bts_app_resi_link', array('app_resi_id' => $app_resi_id))->result();
      foreach ($detail as $row) {
        //Latitude
        $lats = explode(' ', $row->latitude);
        $deg1 = $lats[0];
        $min1 = $lats[1];
        $sec1 = $lats[2];
        $dir1 = $lats[3];
        $row->lat_deg = $deg1;
        $row->lat_min = $min1;
        $row->lat_sec = number_format($sec1, 2);
        $row->lat_dir = strtoupper($dir1);
//Longitude
        $longs = explode(' ', $row->longitude);
        $deg2 = $longs[0];
        $min2 = $longs[1];
        $sec2 = $longs[2];
        $dir2 = $longs[3];
        $row->long_deg = $deg2;
        $row->long_min = $min2;
        $row->long_sec = number_format($sec2, 2);
        $row->long_dir = strtoupper($dir2);

        array_push($data['resi_detail'], $row);
      }

      $this->load->view("m2m_add_new", $data);
    } else { //SAVING DATA
      $this->_set_m2m_new_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
        $this->db->trans_begin();

        $app_resi_id = $this->core->decode($data_post['app_resi_id']);
        $data_resi = array(
            'app_resi_id' => $app_resi_id,
//            'app_no' => $data_post['app_no'],
            'app_issued' => $data_post['app_issued'],
            'remark' => $data_post['remark'],
        );
//UPLOAD DOCUMENT
        $path = $this->_get_m2m_path();
        if (!empty($_FILES['doc']['name'])) {

          $config['upload_path'] = $path;
          $config['allowed_types'] = '*';
          $config['max_size'] = '100000';
          $config['file_name'] = 'M2M_' . $data_post['app_no'] . '_' . date('dMY');

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if (!$this->upload->do_upload('doc')) {
            $this->db->trans_rollback();
            $response = array(
                'status' => 'warning',
                'message' => $this->upload->display_errors()
            );
            echo json_encode($response);
            exit();
          } else {
            $pic = $this->upload->data();
            $doc = $pic['file_name'];

            $docData['app_id'] = $app_resi_id;
            $docData['doc_no'] = $pic['file_name'];
            $docData['file_name'] = $pic['file_name'];
            $docData['doc_type'] = 1172;
            $docData['app_type'] = 2;

            $docId = $this->mglobal->setData('t_trx_isr_bts_doc', $docData);

            //$data_resi['doc'] = $doc;
          }
        }

        $this->mglobal->saveData('t_trx_isr_bts_app_resi', $data_resi, 'app_resi_id');

        foreach ($data_post['station_identifier'] as $key => $value) {
          $data_resi_link = array(
              'resi_link_id' => $data_post['resi_link_id'][$key],
              'app_resi_id' => $app_resi_id,
//              'milestone_status' => self::SPP_FIN_NEW,
              'site_id' => $data_post['site_id'][$key],
              'vendor_id' => $data_post['vendor_id'][$key],
              'bts_id' => $data_post['bts_id'][$key],
              'latitude' => "{$data_post['lat_deg'][$key]} {$data_post['lat_min'][$key]} {$data_post['lat_sec'][$key]} {$data_post['lat_dir_ind_n_s'][$key]} ",
              'longitude' => "{$data_post['long_deg'][$key]} {$data_post['long_min'][$key]} {$data_post['long_sec'][$key]} {$data_post['long_dir_ind_e_w'][$key]}",
              'site_address' => $data_post['area_of_service'][$key],
              'service_id' => $data_post['service_id'][$key],
              'subservice_id' => $data_post['subservice_id'][$key],
              'isr_company_code' => $data_post['client_id'][$key],
              'client_id' => $data_post['client_id'][$key],
              'application_identifier' => $data_post['application_identifier'][$key],
              'station_identifier' => $data_post['station_identifier'][$key],
              'link_id' => $data_post['link_id'][$key],
              'station_name' => $data_post['station_name'][$key],
              'height_asl' => $data_post['height_asl'][$key],
              'radius_of_operation' => $data_post['radius_of_operation'][$key],
              'site_province' => $data_post['site_province'][$key],
              'site_city' => $data_post['site_city'][$key],
              'site_district' => $data_post['site_district'][$key],
              'area_of_service' => $data_post['area_of_service'][$key],
              'equipment_type' => $data_post['equipment_type'][$key],
              'equipment_output_power' => $data_post['equipment_output_power'][$key],
              'bandwidth' => $data_post['bandwidth'][$key],
              'transmitter_freq' => $data_post['transmitter_freq'][$key],
              'receiver_freq' => $data_post['receiver_freq'][$key],
              'antenna_type' => $data_post['antenna_type'][$key],
              'feeding_loss' => $data_post['feeding_loss'][$key],
              'connector_loss' => $data_post['connector_loss'][$key],
              'branch_loss' => $data_post['branch_loss'][$key],
              'antenna_height_agl' => $data_post['antenna_height_agl'][$key],
              'transmitter_polarization' => $data_post['transmitter_polarization'][$key],
          );
          $this->mglobal->saveData('t_trx_isr_bts_app_resi_link', $data_resi_link, 'resi_link_id');
        }

        //save new application
        $headers = array('service_id', 'subservice_id', 'client_id', 'application_identifier', 'station_identifier', 'link_id', 'station_name', 'height_asl', 'lat_deg', 'lat_min', 'lat_sec', 'lat_dir_ind_n_s',
            'long_deg', 'long_min', 'long_sec', 'long_dir_ind_e_w', 'radius_of_operation', 'site_province', 'site_city', 'site_district', 'area_of_service', 'equipment_type', 'equipment_output_power',
            'bandwidth', 'transmitter_freq', 'receiver_freq', 'antenna_type', 'feeding_loss', 'connector_loss', 'branch_loss', 'antenna_height_agl', 'transmitter_polarization');

        foreach ($headers as $value) {
          $$value = $this->input->post($value);
        }

        $log_id = 0;

        if ($_FILES['doc']['name'] != '') {
          $data_post['f1_name'] = $_FILES['doc']['name'];
          $data_post['f1_desc'] = "Surat Permohonan {$station_name[0]}";
          $data_post['f1_blob'] = base64_encode(bin2hex(file_get_contents($_FILES['doc']['tmp_name'])));
          $data_post['f2_name'] = 'Testing.pdf';
          $data_post['f2_desc'] = "Surat Pendukung Lainnya";
          $data_post['f2_blob'] = 'dGVzdA==';
        } else {
//          $doc_name = explode('/', $d['doc_old']);
//          $data_post['f1_name'] = $doc_name[9];
          $data_post['f1_name'] = "Surat Permohonan {$station_name[0]}";
          $data_post['f1_desc'] = "Surat Permohonan {$station_name[0]}";
          //$data_post['f1_blob'] = base64_encode(bin2hex(file_get_contents($d['doc_old'])));
          $data_post['f1_blob'] = 'dGVzdA==';
          $data_post['f2_name'] = 'Testing.pdf';
          $data_post['f2_desc'] = "Surat Pendukung Lainnya";
          $data_post['f2_blob'] = 'dGVzdA==';
        }

        $upload_header = array(
            'm2m_log_new_id' => $log_id,
//            'near_end_v_id' => $near_end_v_id[0],
//            'far_end_v_id' => $far_end_v_id[0],
            'att_file_name_1' => $data_post['f1_name'],
            'att_file_description_1' => $data_post['f1_desc'],
            'att_file_blob_1' => $data_post['f1_blob'],
            'att_file_name_2' => $data_post['f2_name'],
            'att_file_description_2' => $data_post['f2_desc'],
            'att_file_blob_2' => $data_post['f2_blob'],
            'app_id' => $app_resi_id,
        );

        $header_id = $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_new_header', $upload_header);

        $i = 0;
        $j = 1;

        foreach ($station_identifier as $row) {
          foreach ($headers as $header) {
            if ($_POST[$header][$i] != '')
              $upload_detail[$header] = $_POST[$header][$i];
          }
          $upload_detail['isr_upload_header_new_id'] = $header_id;
          $upload_detail['m2m_log_new_id'] = $log_id;

          $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_new_detail', $upload_detail);

          $i++;
          $j++;
        }

        //Update all link where document approved status
        if ($this->db->trans_status() === TRUE) {
          $this->db->trans_commit();
          //send to sdppi aka postel
          $sdppi = $this->m2m_model->m2m_save_new_application($data_post, $app_resi_id, $header_id);
          if ($sdppi['type'] == 'success') {
            $this->db->where('app_resi_id', $app_resi_id);
            $this->db->update('t_trx_isr_bts_app_resi_link', array('milestone_status' => self::SPP_FIN_NEW, 'prev_completed_milestone' => self::M2M_NEW));
          }

          echo json_encode(array(
              'status' => 'success',
              'message' => 'Application has been saved.' . "{$sdppi['message']}"
          ));
        } else {
          $this->db->trans_rollback();
          echo json_encode(array(
              'status' => 'error',
              'message' => 'Unable to save current receipt.'
          ));
        }
      } else {
        $response = array(
            'status' => 'warning',
            'message' => validation_errors()
        );
        echo json_encode($response);
      }
    }
  }

  public function add_new_non_m2m() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $app_resi_id = $this->core->decode($this->uri->segment(4));
      $data['resi'] = $this->db->get_where('t_trx_isr_bts_app_resi', array('app_resi_id' => $app_resi_id))->row();
      $data['resi']->app_resi_id = $this->core->encode($data['resi']->app_resi_id);
      $data['resi']->app_issued = date('d M Y', strtotime($data['resi']->app_issued));
//    $data['resi']->doc_old = $data['resi']->doc;
//    $data['resi']->download_doc = "<a download='' target='_blank' href='" . base_url($this->get_resi_path()) . "/{$data['resi']->doc}'>{$this->core->setFileNameIcon($data['resi']->doc)}</a>";

      $data['transmitter_polarization_opt'] = array();
      foreach ($this->mglobal->lookup('bts_polarization') as $value) {
        $data['transmitter_polarization_opt'][$value->lookup_value] = $value->lookup_desc;
      }

      $data['resi_detail'] = array();
      $this->db->select('*, bandwidth::INT AS bandwidth', false);
      $detail = $this->db->get_where('t_trx_isr_bts_app_resi_link', array('app_resi_id' => $app_resi_id))->result();
      foreach ($detail as $row) {
        //Latitude
        $lats = explode(' ', $row->latitude);
        $deg1 = $lats[0];
        $min1 = $lats[1];
        $sec1 = $lats[2];
        $dir1 = $lats[3];
        $row->lat_deg = $deg1;
        $row->lat_min = $min1;
        $row->lat_sec = number_format($sec1, 2);
        $row->lat_dir = strtoupper($dir1);
//Longitude
        $longs = explode(' ', $row->longitude);
        $deg2 = $longs[0];
        $min2 = $longs[1];
        $sec2 = $longs[2];
        $dir2 = $longs[3];
        $row->long_deg = $deg2;
        $row->long_min = $min2;
        $row->long_sec = number_format($sec2, 2);
        $row->long_dir = strtoupper($dir2);

        array_push($data['resi_detail'], $row);
      }

      $this->load->view("non_m2m_add_new", $data);
    } else { //SAVING DATA
      $this->_set_m2m_new_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
        $this->db->trans_begin();

        $app_resi_id = $this->core->decode($data_post['app_resi_id']);
        $data_resi = array(
            'app_resi_id' => $app_resi_id,
//            'app_no' => $data_post['app_no'],
            'app_issued' => $data_post['app_issued'],
            'remark' => $data_post['remark'],
        );
//UPLOAD DOCUMENT
        $path = $this->_get_m2m_path();
        if (!empty($_FILES['doc']['name'])) {

          $config['upload_path'] = $path;
          $config['allowed_types'] = '*';
          $config['max_size'] = '100000';
          $config['file_name'] = 'M2M_' . $data_post['app_no'] . '_' . date('dMY');

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if (!$this->upload->do_upload('doc')) {
            $this->db->trans_rollback();
            $response = array(
                'status' => 'warning',
                'message' => $this->upload->display_errors()
            );
            echo json_encode($response);
            exit();
          } else {
            $pic = $this->upload->data();
            $doc = $pic['file_name'];

            $docData['app_id'] = $app_resi_id;
            $docData['doc_no'] = $pic['file_name'];
            $docData['file_name'] = $pic['file_name'];
            $docData['doc_type'] = 1172;
            $docData['app_type'] = 2;

            $docId = $this->mglobal->setData('t_trx_isr_bts_doc', $docData);

            //$data_resi['doc'] = $doc;
          }
        }

        $this->mglobal->saveData('t_trx_isr_bts_app_resi', $data_resi, 'app_resi_id');

        foreach ($data_post['station_identifier'] as $key => $value) {
          $data_resi_link = array(
              'resi_link_id' => $data_post['resi_link_id'][$key],
              'app_resi_id' => $app_resi_id,
//              'milestone_status' => self::SPP_FIN_NEW,
              'site_id' => $data_post['site_id'][$key],
              'vendor_id' => $data_post['vendor_id'][$key],
              'bts_id' => $data_post['bts_id'][$key],
              'latitude' => "{$data_post['lat_deg'][$key]} {$data_post['lat_min'][$key]} {$data_post['lat_sec'][$key]} {$data_post['lat_dir_ind_n_s'][$key]} ",
              'longitude' => "{$data_post['long_deg'][$key]} {$data_post['long_min'][$key]} {$data_post['long_sec'][$key]} {$data_post['long_dir_ind_e_w'][$key]}",
              'site_address' => $data_post['area_of_service'][$key],
              'service_id' => $data_post['service_id'][$key],
              'subservice_id' => $data_post['subservice_id'][$key],
              'isr_company_code' => $data_post['client_id'][$key],
              'client_id' => $data_post['client_id'][$key],
              'application_identifier' => $data_post['application_identifier'][$key],
              'station_identifier' => $data_post['station_identifier'][$key],
              'link_id' => $data_post['link_id'][$key],
              'station_name' => $data_post['station_name'][$key],
              'height_asl' => $data_post['height_asl'][$key],
              'radius_of_operation' => $data_post['radius_of_operation'][$key],
              'site_province' => $data_post['site_province'][$key],
              'site_city' => $data_post['site_city'][$key],
              'site_district' => $data_post['site_district'][$key],
              'area_of_service' => $data_post['area_of_service'][$key],
              'equipment_type' => $data_post['equipment_type'][$key],
              'equipment_output_power' => $data_post['equipment_output_power'][$key],
              'bandwidth' => $data_post['bandwidth'][$key],
              'transmitter_freq' => $data_post['transmitter_freq'][$key],
              'receiver_freq' => $data_post['receiver_freq'][$key],
              'antenna_type' => $data_post['antenna_type'][$key],
              'feeding_loss' => $data_post['feeding_loss'][$key],
              'connector_loss' => $data_post['connector_loss'][$key],
              'branch_loss' => $data_post['branch_loss'][$key],
              'antenna_height_agl' => $data_post['antenna_height_agl'][$key],
              'transmitter_polarization' => $data_post['transmitter_polarization'][$key],
              'registered_via' => $data_post['registered_via'],
          );
          $this->mglobal->saveData('t_trx_isr_bts_app_resi_link', $data_resi_link, 'resi_link_id');
        }

        //save new application
        $headers = array('service_id', 'subservice_id', 'client_id', 'application_identifier', 'station_identifier', 'link_id', 'station_name', 'height_asl', 'lat_deg', 'lat_min', 'lat_sec', 'lat_dir_ind_n_s',
            'long_deg', 'long_min', 'long_sec', 'long_dir_ind_e_w', 'radius_of_operation', 'site_province', 'site_city', 'site_district', 'area_of_service', 'equipment_type', 'equipment_output_power',
            'bandwidth', 'transmitter_freq', 'receiver_freq', 'antenna_type', 'feeding_loss', 'connector_loss', 'branch_loss', 'antenna_height_agl', 'transmitter_polarization');

        foreach ($headers as $value) {
          $$value = $this->input->post($value);
        }

        $log_id = 0;

        if ($_FILES['doc']['name'] != '') {
          $data_post['f1_name'] = $_FILES['doc']['name'];
          $data_post['f1_desc'] = "Surat Permohonan {$station_name[0]}";
          $data_post['f1_blob'] = base64_encode(bin2hex(file_get_contents($_FILES['doc']['tmp_name'])));
          $data_post['f2_name'] = 'Testing.pdf';
          $data_post['f2_desc'] = "Surat Pendukung Lainnya";
          $data_post['f2_blob'] = 'dGVzdA==';
        } else {
//          $doc_name = explode('/', $d['doc_old']);
//          $data_post['f1_name'] = $doc_name[9];
          $data_post['f1_name'] = "Surat Permohonan {$station_name[0]}";
          $data_post['f1_desc'] = "Surat Permohonan {$station_name[0]}";
          //$data_post['f1_blob'] = base64_encode(bin2hex(file_get_contents($d['doc_old'])));
          $data_post['f1_blob'] = 'dGVzdA==';
          $data_post['f2_name'] = 'Testing.pdf';
          $data_post['f2_desc'] = "Surat Pendukung Lainnya";
          $data_post['f2_blob'] = 'dGVzdA==';
        }

        $upload_header = array(
            'm2m_log_new_id' => $log_id,
//            'near_end_v_id' => $near_end_v_id[0],
//            'far_end_v_id' => $far_end_v_id[0],
            'att_file_name_1' => $data_post['f1_name'],
            'att_file_description_1' => $data_post['f1_desc'],
            'att_file_blob_1' => $data_post['f1_blob'],
            'att_file_name_2' => $data_post['f2_name'],
            'att_file_description_2' => $data_post['f2_desc'],
            'att_file_blob_2' => $data_post['f2_blob'],
            'app_id' => $app_resi_id
        );

        $header_id = $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_new_header', $upload_header);

        $i = 0;
        $j = 1;

        foreach ($station_identifier as $row) {
          foreach ($headers as $header) {
            if ($_POST[$header][$i] != '')
              $upload_detail[$header] = $_POST[$header][$i];
          }
          $upload_detail['isr_upload_header_new_id'] = $header_id;
          $upload_detail['m2m_log_new_id'] = $log_id;

          $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_new_detail', $upload_detail);

          $i++;
          $j++;
        }

        //Update all link where document approved status
        if ($this->db->trans_status() === TRUE) {
          $this->db->where('app_resi_id', $app_resi_id);
          $this->db->update('t_trx_isr_bts_app_resi_link', array('milestone_status' => self::SPP_FIN_NEW, 'prev_completed_milestone' => self::M2M_NEW));

          $this->db->trans_commit();

          echo json_encode(array(
              'status' => 'success',
              'message' => 'Application has been saved sucessfully.'
          ));
        } else {
          $this->db->trans_rollback();
          echo json_encode(array(
              'status' => 'error',
              'message' => 'Saving application failed, please contact the admistrator.'
          ));
        }
      } else {
        $response = array(
            'status' => 'warning',
            'message' => validation_errors()
        );
        echo json_encode($response);
      }
    }
  }

  public function view_new_m2m() {
    $this->load->helper('inflector');
    $app_resi_id = $this->core->decode($this->uri->segment(4));
    $data['judul'] = 'M2M Detail';

    $data['resi_header'] = $this->m2m_model->m2m_new_header($app_resi_id);

    $data['resi_detail'] = array();
    $this->db->select("*, bandwidth::INT AS bandwidth, f_get_lookup_desc('bts_polarization', transmitter_polarization) AS transmitter_polarization", false);
    $detail = $this->db->get_where('t_trx_isr_bts_app_resi_link', array('app_resi_id' => $app_resi_id))->result();
    foreach ($detail as $row) {
      //Latitude
      $lats = explode(' ', $row->latitude);
      $deg1 = $lats[0];
      $min1 = $lats[1];
      $sec1 = $lats[2];
      $dir1 = $lats[3];
      $row->lat_deg = $deg1;
      $row->lat_min = $min1;
      $row->lat_sec = number_format($sec1, 2);
      $row->lat_dir = strtoupper($dir1);
//Longitude
      $longs = explode(' ', $row->longitude);
      $deg2 = $longs[0];
      $min2 = $longs[1];
      $sec2 = $longs[2];
      $dir2 = $longs[3];
      $row->long_deg = $deg2;
      $row->long_min = $min2;
      $row->long_sec = number_format($sec2, 2);
      $row->long_dir = strtoupper($dir2);

      array_push($data['resi_detail'], $row);
    }

    $data['resi_history'] = $this->m2m_model->m2m_new_detail_history($app_resi_id);

    $this->load->view('m2m_view_new', $data);
  }

  public function view_discontinued_m2m() {
    $this->load->helper('inflector');
    $app_id = $this->core->decode($this->uri->segment(4));
    $data['judul'] = 'M2M Detail';

    $data['app_header'] = $this->m2m_model->get_m2m_discontinued_header($app_id);
//    $data['app_header'] = $this->db->get_where('t_trx_isr_bts_app', array('app_id' => $app_id))->row();

    $data['app_detail'] = array();
    $this->db->select("*, f_get_lookup_desc('bts_polarization', transmitter_polarization) AS transmitter_polarization", false);

    $this->db->order_by('station_identifier', 'asc');
    $detail = $this->db->get_where('t_trx_isr_bts_app_link', array('app_id' => $app_id))->result();
    foreach ($detail as $row) {
      //Latitude
      $lats = explode(' ', $row->latitude);
      $deg1 = $lats[0];
      $min1 = $lats[1];
      $sec1 = $lats[2];
      $dir1 = $lats[3];
      $row->lat_deg = $deg1;
      $row->lat_min = $min1;
      $row->lat_sec = number_format($sec1, 2);
      $row->lat_dir = strtoupper($dir1);
//Longitude
      $longs = explode(' ', $row->longitude);
      $deg2 = $longs[0];
      $min2 = $longs[1];
      $sec2 = $longs[2];
      $dir2 = $longs[3];
      $row->long_deg = $deg2;
      $row->long_min = $min2;
      $row->long_sec = number_format($sec2, 2);
      $row->long_dir = strtoupper($dir2);

      array_push($data['app_detail'], $row);
    }

    $data['app_history'] = $this->m2m_model->m2m_discontinued_detail_history($app_id);

    $this->load->view('m2m_view_discontinued', $data);
  }

  public function add_discontinued_m2m() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $app_id = $this->core->decode($this->uri->segment(4));
      $data['app_header'] = $this->m2m_model->get_m2m_discontinued_header($app_id);
      $data['app_detail'] = $this->m2m_model->get_m2m_detail_list2($app_id);

      $this->load->view("m2m_add_discontinued", $data);
    } else { //SAVING DATA
      $this->_set_m2m_discontinued_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
        $this->db->trans_begin();

        $app_id = $data_post['app_id'];
//UPLOAD DOCUMENT
        $doc = '';
        $docId = 0;
        $doc_type = intval($data_post['milestone_status']) + 1;
        $path = $this->_get_discontinued_m2m_path($doc_type);
        if (!empty($_FILES['doc_1']['name'])) {

          $config['upload_path'] = $path;
          $config['allowed_types'] = '*';
          $config['max_size'] = '100000';
          $config['file_name'] = 'M2M_' . $data_post['app_no'] . '_' . date('dMY');

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if (!$this->upload->do_upload('doc_1')) {
            $this->db->trans_rollback();
            $response = array(
                'status' => 'warning',
                'message' => $this->upload->display_errors()
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

            $docId = $this->mglobal->setData('t_trx_isr_bts_doc', $docData);
          }
        }

        if ($_FILES['doc_1']['name'] != '') {
          //$data_post['f1_name'] = $_FILES['doc_1']['name'];
          $data_post['f1_name'] = $pic['file_name'];
          $data_post['f1_desc'] = "Surat Permohonan {$data_post['station_name'][0]}";
          $data_post['f1_blob'] = base64_encode(bin2hex(file_get_contents($_FILES['doc_1']['tmp_name'])));
          $data_post['f2_name'] = 'Testing.pdf';
          $data_post['f2_desc'] = "Surat Pendukung Lainnya";
          $data_post['f2_blob'] = 'dGVzdA==';
        } else {
          $doc = '';
//          $doc_name = explode('/', $d['doc_old']);
//          $data_post['f1_name'] = $doc_name[9];
          $data_post['f1_name'] = "Surat Permohonan {$data_post['station_name'][0]}";
          $data_post['f1_desc'] = "Surat Permohonan {$data_post['station_name'][0]}";
          //$data_post['f1_blob'] = base64_encode(bin2hex(file_get_contents($d['doc_old'])));
          $data_post['f1_blob'] = 'dGVzdA==';
          $data_post['f2_name'] = 'Testing.pdf';
          $data_post['f2_desc'] = "Surat Pendukung Lainnya";
          $data_post['f2_blob'] = 'dGVzdA==';
        }

        //Update App
        $data_app = array(
            'app_id' => $app_id,
            'remark' => $data_post['remark'],
            'doc' => $doc
        );
        $this->mglobal->saveData('t_trx_isr_bts_app', $data_app, 'app_id');

        $sdppi = $this->m2m_model->m2m_cancel_application($data_post);

        if ($this->db->trans_status() == TRUE) {
          $this->db->trans_commit();
          echo json_encode(array(
              'status' => 'success',
              'message' => 'Application has been saved.' . "{$sdppi['message']}"
          ));
        } else {
          $this->db->trans_rollback();
          echo json_encode(array(
              'status' => 'error',
              'message' => 'Unable to save current receipt.'
          ));
        }
      } else {
        $response = array(
            'status' => 'warning',
            'message' => validation_errors()
        );
        echo json_encode($response);
      }
    }
  }

  public function add_discontinued_non_m2m() {
    if ($this->input->is_ajax_request() && $this->input->post() == FALSE) {
      $app_id = $this->core->decode($this->uri->segment(4));
      $data['app_header'] = $this->m2m_model->get_m2m_discontinued_header($app_id);
      $data['app_detail'] = $this->m2m_model->get_m2m_detail_list2($app_id);

      $this->load->view("non_m2m_add_discontinued", $data);
    } else { //SAVING DATA
      $this->_set_m2m_discontinued_validation();
      if ($this->form_validation->run() == TRUE) {
        $data_post = $this->input->post();
        $this->db->trans_begin();

        $app_id = $data_post['app_id'];
//UPLOAD DOCUMENT
        $doc = '';
        $docId = 0;
        $path = $this->_get_discontinued_m2m_path();
        if (!empty($_FILES['doc_1']['name'])) {

          $config['upload_path'] = $path;
          $config['allowed_types'] = '*';
          $config['max_size'] = '100000';
          $config['file_name'] = 'M2M_' . $data_post['app_no'] . '_' . date('dMY');

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if (!$this->upload->do_upload('doc_1')) {
            $this->db->trans_rollback();
            $response = array(
                'status' => 'warning',
                'message' => $this->upload->display_errors()
            );
            echo json_encode($response);
            exit();
          } else {
            $pic = $this->upload->data();
            $doc = '1372/' . $pic['file_name'];

            $docData['app_id'] = $app_id;
            $docData['doc_no'] = $pic['file_name'];
            $docData['file_name'] = $pic['file_name'];
            $docData['doc_type'] = 1372;
            $docData['app_type'] = 4;

            $docId = $this->mglobal->setData('t_trx_isr_bts_doc', $docData);
          }
        }

        //Create upload cancel, may be just t_trx_isr_bts_m2m_cancel only :-p
        $data_cancel = array(
            'm2m_log_cancel_id' => 0,
            'service_id' => $data_post['service_id'][0],
            'subservice_id' => $data_post['subservice_id'][0],
            'client_id' => $data_post['client_id'],
            'ap_name' => $data_post['license_number'],
            'ap_name_reference' => $data_post['app_id'],
            'action_type' => 'C',
        );

        if ($_FILES['doc_1']['name'] != '') {
          //$data_cancel['f1_name'] = $_FILES['doc_1']['name'];
          $data_cancel['att_file_name_1'] = $pic['file_name'];
          $data_cancel['att_file_description_1'] = "Surat Permohonan {$data_post['station_name'][0]}";
          $data_cancel['att_file_blob_1'] = base64_encode(bin2hex(file_get_contents($_FILES['doc_1']['tmp_name'])));
          $data_cancel['att_file_name_2'] = 'Testing.pdf';
          $data_cancel['att_file_description_2'] = "Surat Pendukung Lainnya";
          $data_cancel['att_file_blob_2'] = 'dGVzdA==';
        } else {
          $doc = '';
          $data_cancel['att_file_name_1'] = "Surat Permohonan {$data_post['station_name']}";
          $data_cancel['att_file_description_1'] = "Surat Permohonan {$data_post['station_name']}";
          $data_cancel['att_file_blob_1'] = 'dGVzdA==';
          $data_cancel['att_file_name_2'] = 'Testing.pdf';
          $data_cancel['att_file_description_2'] = "Surat Pendukung Lainnya";
          $data_cancel['att_file_blob_2'] = 'dGVzdA==';
        }
        $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_cancel', $data_cancel);

        //Update App
        $data_app = array(
            'app_id' => $app_id,
            'remark' => $data_post['remark'],
            'doc' => $doc
        );
        $this->mglobal->saveData('t_trx_isr_bts_app', $data_app, 'app_id');
        
        //Change milestone link
        $data_link = array(
            'app_id' => $data_post['app_id'],
            'prev_completed_milestone' => $data_post['milestone_status'],
            'milestone_status' => intval($data_post['milestone_status']) - 40
        );
        $this->mglobal->saveData('t_trx_isr_bts_app_link', $data_link, 'app_id');

        //$sdppi = $this->m2m_model->m2m_cancel_application($data_post);

        if ($this->db->trans_status() == TRUE) {
          $this->db->trans_commit();
          echo json_encode(array(
              'status' => 'success',
              'message' => 'Application has been saved.' //"{$sdppi['message']}"
          ));
        } else {
          $this->db->trans_rollback();
          echo json_encode(array(
              'status' => 'error',
              'message' => 'Unable to save current receipt.'
          ));
        }
      } else {
        $response = array(
            'status' => 'warning',
            'message' => validation_errors()
        );
        echo json_encode($response);
      }
    }
  }

  private function _set_m2m_new_validation() {
    $this->form_validation->set_rules('app_issued', 'App Issued', 'trim|required');
    $this->form_validation->set_rules('station_identifier[]', 'Station ID', 'trim|required');
    $this->form_validation->set_rules('height_asl[]', 'Height ASL', 'trim|required|numeric');
    $this->form_validation->set_rules('radius_of_operation[]', 'Radius of Operation', 'trim|required|numeric');
    $this->form_validation->set_rules('equipment_type[]', 'Equipment Type', 'trim|required');
    $this->form_validation->set_rules('equipment_output_power[]', 'Equipment Output Power', 'trim|required|numeric');
    $this->form_validation->set_rules('bandwidth[]', 'Bandwidth', 'trim|required|numeric');
    $this->form_validation->set_rules('transmitter_freq[]', 'Tx Freq', 'trim|required|numeric');
    $this->form_validation->set_rules('receiver_freq[]', 'Rx Freq', 'trim|required|numeric');
    $this->form_validation->set_rules('antenna_type[]', 'Antenna Type', 'trim|required');
    $this->form_validation->set_rules('feeding_loss[]', 'Feeding Loss', 'trim|required|numeric');
    $this->form_validation->set_rules('connector_loss[]', 'Connector Loss', 'trim|required|numeric');
    $this->form_validation->set_rules('branch_loss[]', 'Branch Loss', 'trim|required|numeric');
    $this->form_validation->set_rules('antenna_height_agl[]', 'Antenna Height AGL', 'trim|required|numeric');
    $this->form_validation->set_rules('transmitter_polarization[]', 'Transmitter Polatization', 'trim|required');
    $this->form_validation->set_rules('site_id[]', 'Site ID', 'trim|required|integere');
    $this->form_validation->set_rules('service_id[]', 'Service ID', 'trim|required');
    $this->form_validation->set_rules('subservice_id[]', 'Subservice ID', 'trim|required');
    $this->form_validation->set_rules('client_id[]', 'Client ID', 'trim|required');
    $this->form_validation->set_rules('application_identifier[]', 'Application ID', 'trim|required');
    $this->form_validation->set_rules('link_id[]', 'Link ID', 'trim|required');
    $this->form_validation->set_rules('lat_deg[]', 'Latitude Degree', 'trim|required|numeric');
    $this->form_validation->set_rules('lat_min[]', 'Latitude Minute', 'trim|required|numeric');
    $this->form_validation->set_rules('lat_sec[]', 'Latitude Second', 'trim|required|numeric');
    $this->form_validation->set_rules('lat_dir_ind_n_s[]', 'Latitude Direction', 'trim|required');
    $this->form_validation->set_rules('long_deg[]', 'Longitude Degree', 'trim|required|numeric');
    $this->form_validation->set_rules('long_min[]', 'Longitude Minute', 'trim|required|numeric');
    $this->form_validation->set_rules('long_sec[]', 'Longitude Sec', 'trim|required|numeric');
    $this->form_validation->set_rules('long_dir_ind_e_w[]', 'Longitude Direction', 'trim|required');
    $this->form_validation->set_rules('site_province[]', 'Site Province', 'trim|required');
    $this->form_validation->set_rules('site_city[]', 'Site City', 'trim|required');
    $this->form_validation->set_rules('site_district[]', 'Site District', 'trim|required');
    $this->form_validation->set_rules('area_of_service[]', 'Area of Service', 'trim|required');
  }

  private function _set_m2m_discontinued_validation() {
    $this->form_validation->set_rules('app_id', 'App ID', 'trim|required');
    $this->form_validation->set_rules('app_no', 'App No', 'trim|required');
    $this->form_validation->set_rules('license_number', 'License Number', 'trim|required');
    $this->form_validation->set_rules('client_id', 'Client ID', 'trim|required');
    $this->form_validation->set_rules('link_id[]', 'Client ID', 'trim|required');
    $this->form_validation->set_rules('service_id[]', 'Service ID', 'trim|required');
    $this->form_validation->set_rules('subservice_id[]', 'Subservice ID', 'trim|required');
  }

  private function _get_m2m_path() {
    $path_bts = 'file/upload/isr_bts';
    $path_resi = '1172';
    $path = $path_bts . '/' . $path_resi;

    if (!file_exists($path)) {
      $make_dir = mkdir($path, 0777, true);
      if ($make_dir == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to make directory "M2M"'
        ));
        exit();
      }
    }

    return $path;
  }

  private function _get_discontinued_m2m_path($doc_type=1372) {
    $path_bts = 'file/upload/isr_bts';
    $path = $path_bts . '/' . $doc_type;

    if (!file_exists($path)) {
      $make_dir = mkdir($path, 0777, true);
      if ($make_dir == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to make directory "M2M"'
        ));
        exit();
      }
    }

    return $path;
  }

}

?>
