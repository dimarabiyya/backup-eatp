<?php

/**
 * This controller handle Resi at ISR BTS
 *
 * @author tumbas
 */
class Resi extends MX_Controller {

  protected $_user_now;
  protected $_date_now;

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
  const SPP_PAY_NEW = '1131';
  const SPP_PAY_RENEWAL = '1221';
  //DGPT
  const DGPT_NEW = '1141';
  const DGPT_RENEWAL = '1231';
  const DGPT_RELEASED_NEW = '1151';
  const DGPT_RELEASED_RENEWAL = '1241';

  public function __construct() {
    parent::__construct();
    $this->load->model('resi_model');
    $this->mglobal->is_logged_in();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function index() {
    $data['content_id'] = 'resi_list';
    $this->_generate_view($data);
  }

  public function load_proposed_new() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('resi_list_new');
    } else {
      die('Forbidden access');
    }
  }

  public function get_proposed_new_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->resi_model->get_proposed_new_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/resi");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $app_resi_id = $this->core->encode($row->app_resi_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($row->isUsed == 1) {
          $row->action .= 'This Resi already in use.';
        } else {
          if ($this->mmenu->cekPermission($menu, 'menu_view')) {
            $row->action .= '<a title="Detail Resi" href="' . base_url("isr_bts/resi/view_proposed_new/{$app_resi_id}") . '" class="btn btn-action glyphicons eye_open btn-success"><i></i></a> &nbsp;';
          }

          if ($this->mmenu->cekPermission($menu, 'menu_edit')) {
            $row->action .= '<a title="Edit Resi" href="' . base_url("isr_bts/resi/edit_proposed_new/{$app_resi_id}") . '" class="btn btn-action glyphicons pencil btn-success"><i></i></a> &nbsp;';
          }

          if ($this->mmenu->cekPermission($menu, 'menu_delete')) {
            $row->action.= '<button title="Delete Resi" class="btn btn-action glyphicons remove_2 btn-success"  onclick="deleteData(' . $row->app_resi_id . ')"><i></i></button> &nbsp;';
          }
        }

        $row->app_resi_id = $app_resi_id;

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_resi_detail_list() {
    if ($this->input->is_ajax_request()) {
      $app_resi_id = $this->core->decode($this->input->get('app_resi_id'));
      $result = $this->resi_model->get_resi_detail_list($app_resi_id);
      
      foreach ($result as $key => $vale) {
        $result[$key]->latitude = dms_format($result[$key]->latitude);
        $result[$key]->longitude = dms_format($result[$key]->longitude);
      }

      echo json_encode($result);
    } else {
      die('Forbidden access');
    }
  }

  public function add_propose_new() {
    if ($this->input->is_ajax_request() == FALSE && $this->input->post() == FALSE) {
      $data['content_id'] = 'resi_add_propose_new';

      $this->_generate_view($data);
    } else { //SAVING DATA HERE
      $this->form_validation->set_rules('app_no', 'Resi No', 'trim|required|is_unique[t_trx_isr_bts_app_resi.app_no]');
      $this->_set_proposed_new_validation();
      if ($this->form_validation->run() == TRUE) {
        $this->db->trans_begin();
        $data_post = $this->input->post();

        $data_resi = array(
            'app_no' => $data_post['app_no'],
            'app_issued' => $data_post['app_issued'],
            'remark' => $data_post['remark'],
        );
//UPLOAD DOCUMENT
        $path = $this->get_resi_path();
        $upload = FALSE;
        if (!empty($_FILES['doc']['name'])) {

          $config['upload_path'] = $path;
          $config['allowed_types'] = '*';
          $config['max_size'] = '100000';
          $config['file_name'] = 'Resi_' . $data_post['app_no'] . '_' . date('dMY');

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
            $doc = "1102/{$pic['file_name']}";
            $data_resi['doc'] = $doc;
            $upload = TRUE;
          }
        }

        $resi_id = $this->mglobal->saveData('t_trx_isr_bts_app_resi', $data_resi);

        if ($upload == TRUE) {
          $docData['app_id'] = $resi_id;
          $docData['doc_no'] = $pic['file_name'];
          $docData['file_name'] = $pic['file_name'];
          $docData['doc_type'] = 1102;
          $docData['app_type'] = 2;

          $this->mglobal->saveData('t_trx_isr_bts_doc', $docData);
        }

        foreach ($data_post['station_identifier'] as $key => $value) {
          $data_resi_link = array(
              'app_resi_id' => $resi_id,
              'milestone_status' => self::ENGIN_APPROV_NEW,
              'site_id' => $data_post['site_id'][$key],
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
              'transmitter_freq' => $data_post['tx_freq'][$key],
              'receiver_freq' => $data_post['rx_freq'][$key],
              'antenna_type' => $data_post['antenna_type'][$key],
              'feeding_loss' => $data_post['feeding_loss'][$key],
              'connector_loss' => $data_post['connector_loss'][$key],
              'branch_loss' => $data_post['branch_loss'][$key],
              'antenna_height_agl' => $data_post['antenna_height_agl'][$key],
              'transmitter_polarization' => $data_post['transmitter_polarization'][$key],
              'vendor_id' => $data_post['vendor_id'][$key],
              'bts_id' => $data_post['bts_id'][$key],
          );
          $this->mglobal->saveData('t_trx_isr_bts_app_resi_link', $data_resi_link);
        }

        if ($this->db->trans_status() == TRUE) {
          $this->db->trans_commit();
          $response = array(
              'status' => 'success',
              'message' => 'Resi has been saved successfully.'
          );
        } else {
          $response = array(
              'status' => 'success',
              'message' => 'Resi failed to save.'
          );
        }
      } else {
        $response = array(
            'status' => 'warning',
            'message' => validation_errors()
        );
      }
      echo json_encode($response);
    }
  }

  public function edit_proposed_new() {
    $app_resi_id = $this->core->decode($this->uri->segment(4));
    if ($this->input->is_ajax_request() == FALSE && $this->input->post() == FALSE) {
      $data['content_id'] = 'resi_edit_propose_new';
      $data['resi'] = $this->db->get_where('t_trx_isr_bts_app_resi', array('app_resi_id' => $app_resi_id))->row();
      $data['resi']->app_issued = date('d M Y', strtotime($data['resi']->app_issued));
      $data['resi']->doc_old = $data['resi']->doc;
      $data['resi']->download_doc = "<a download='' target='_blank' href='" . base_url($this->get_resi_path()) . "/{$data['resi']->doc}'>{$this->core->setFileNameIcon($data['resi']->doc)}</a>";

      $data['transmitter_polarization_opt'] = array();
      foreach ($this->mglobal->lookup('bts_polarization') as $value) {
        $data['transmitter_polarization_opt'][$value->lookup_value] = $value->lookup_desc;
      }

      $data['resi_detail'] = array();
      $this->db->select('*, bandwidth::INT AS bandwidth', false);
      $detail = $this->db->get_where('t_trx_isr_bts_app_resi_link', array('app_resi_id' => $app_resi_id))->result();
//      die(print_r($detail));
      foreach ($detail as $row) {
        //Latitude
        if (is_numeric($row->latitude)) {
          $this->decimal_to_dms($row->latitude, $deg1, $min1, $sec1, $dir1, true);
        } else if ($row->latitude == NULL) {
          $deg1 = 0;
          $min1 = 0;
          $sec1 = 0;
          $dir1 = '';
        } else {
          $lats = explode(' ', $row->latitude);
          $deg1 = $lats[0];
          $min1 = $lats[1];
          $sec1 = $lats[2];
          $dir1 = $lats[3];
        }
        $row->lat_deg = $deg1;
        $row->lat_min = $min1;
        $row->lat_sec = number_format($sec1, 2);
        $row->lat_dir = strtoupper($dir1);
//Longitude
        if (is_numeric($row->longitude)) {
          $this->decimal_to_dms($row->longitude, $deg2, $min2, $sec2, $dir2, false);
        } else if ($row->latitude == NULL) {
          $deg2 = 0;
          $min2 = 0;
          $sec2 = 0;
          $dir2 = '';
        } else {
          $longs = explode(' ', $row->longitude);
          $deg2 = $longs[0];
          $min2 = $longs[1];
          $sec2 = $longs[2];
          $dir2 = $longs[3];
        }
        $row->long_deg = $deg2;
        $row->long_min = $min2;
        $row->long_sec = number_format($sec2, 2);
        $row->long_dir = strtoupper($dir2);

        array_push($data['resi_detail'], $row);
      }
//      die(print_r($row));
      $this->_generate_view($data);
    } else { //SAVING DATA HERE
      //Cek unique application
      $cek_app = $this->db->get_where('t_trx_isr_bts_app_resi', array('app_resi_id <> ' => $app_resi_id, 'app_no' => $this->input->post('app_no')))->row();
      if (!empty($cek_app)) {
        $response = array(
            'status' => 'warning',
            'message' => 'Resi No already exist, please change it.'
        );
        echo json_encode($response);
        exit();
      }

      $this->_set_proposed_new_validation();
      if ($this->form_validation->run() == TRUE) {
        $this->db->trans_begin();
        $data_post = $this->input->post();

        $data_resi = array(
            'app_resi_id' => $app_resi_id,
            'app_no' => $data_post['app_no'],
            'app_issued' => $data_post['app_issued'],
            'remark' => $data_post['remark'],
        );
//UPLOAD DOCUMENT
        $path = $this->get_resi_path();
        if (!empty($_FILES['doc']['name'])) {

          $config['upload_path'] = $path;
          $config['allowed_types'] = '*';
          $config['max_size'] = '100000';
          $config['file_name'] = 'Resi_' . $data_post['app_no'] . '_' . date('dMY');

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
            $doc = "1102/{$pic['file_name']}";

            $docData['app_id'] = $app_resi_id;
            $docData['doc_no'] = $pic['file_name'];
            $docData['file_name'] = $pic['file_name'];
            $docData['doc_type'] = 1102;
            $docData['app_type'] = 2;

            $this->mglobal->saveData('t_trx_isr_bts_doc', $docData);

            $data_resi['doc'] = $doc;
          }
        }

        $this->mglobal->saveData('t_trx_isr_bts_app_resi', $data_resi, 'app_resi_id');

        $this->db->delete('t_trx_isr_bts_app_resi_link', array('app_resi_id' => $app_resi_id));
        foreach ($data_post['station_identifier'] as $key => $value) {
          $data_resi_link = array(
              'app_resi_id' => $app_resi_id,
              'milestone_status' => self::ENGIN_APPROV_NEW,
              'site_id' => $data_post['site_id'][$key],
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
              'transmitter_freq' => $data_post['tx_freq'][$key],
              'receiver_freq' => $data_post['rx_freq'][$key],
              'antenna_type' => $data_post['antenna_type'][$key],
              'feeding_loss' => $data_post['feeding_loss'][$key],
              'connector_loss' => $data_post['connector_loss'][$key],
              'branch_loss' => $data_post['branch_loss'][$key],
              'antenna_height_agl' => $data_post['antenna_height_agl'][$key],
              'transmitter_polarization' => $data_post['transmitter_polarization'][$key],
              'vendor_id' => $data_post['vendor_id'][$key],
              'bts_id' => $data_post['bts_id'][$key],
          );
          $this->mglobal->saveData('t_trx_isr_bts_app_resi_link', $data_resi_link);
        }

        if ($this->db->trans_status() == TRUE) {
          $this->db->trans_commit();
          $response = array(
              'status' => 'success',
              'message' => 'Resi has been saved successfully.'
          );
        } else {
          $response = array(
              'status' => 'success',
              'message' => 'Resi failed to save.'
          );
        }
      } else {
        $response = array(
            'status' => 'warning',
            'message' => validation_errors()
        );
      }
      echo json_encode($response);
    }
  }

  public function view_proposed_new() {
    $app_resi_id = $this->core->decode($this->uri->segment(4));
    $data['content_id'] = 'resi_view_propose_new';
    $data['resi'] = $this->db->get_where('t_trx_isr_bts_app_resi', array('app_resi_id' => $app_resi_id))->row();
    $data['resi']->app_issued = date('d M Y', strtotime($data['resi']->app_issued));
    $data['resi']->doc_old = $data['resi']->doc;
    $data['resi']->download_doc = "<a download='' target='_blank' href='" . base_url($this->get_resi_path()) . "/{$data['resi']->doc}'>{$this->core->setFileNameIcon($data['resi']->doc)}</a>";
    ;

    $data['transmitter_polarization_opt'] = array();
    foreach ($this->mglobal->lookup('bts_polarization') as $value) {
      $data['transmitter_polarization_opt'][$value->lookup_value] = $value->lookup_desc;
    }

    $data['resi_detail'] = array();
    $detail = $this->db->get_where('t_trx_isr_bts_app_resi_link', array('app_resi_id' => $app_resi_id))->result();
    foreach ($detail as $row) {
      //Latitude
      if (is_numeric($row->latitude)) {
        $this->decimal_to_dms($row->latitude, $deg1, $min1, $sec1, $dir1, true);
      } else if ($row->latitude == NULL) {
        $deg1 = 0;
        $min1 = 0;
        $sec1 = 0;
        $dir1 = '';
      } else {
        $lats = explode(' ', $row->latitude);
        $deg1 = $lats[0];
        $min1 = $lats[1];
        $sec1 = $lats[2];
        $dir1 = $lats[3];
      }
      $row->lat_deg = $deg1;
      $row->lat_min = $min1;
      $row->lat_sec = number_format($sec1, 2);
      $row->lat_dir = strtoupper($dir1);
//Longitude
      if (is_numeric($row->longitude)) {
        $this->decimal_to_dms($row->longitude, $deg2, $min2, $sec2, $dir2, false);
      } else if ($row->latitude == NULL) {
        $deg2 = 0;
        $min2 = 0;
        $sec2 = 0;
        $dir2 = '';
      } else {
        $longs = explode(' ', $row->longitude);
        $deg2 = $longs[0];
        $min2 = $longs[1];
        $sec2 = $longs[2];
        $dir2 = $longs[3];
      }
      $row->long_deg = $deg2;
      $row->long_min = $min2;
      $row->long_sec = number_format($sec2, 2);
      $row->long_dir = strtoupper($dir2);

      array_push($data['resi_detail'], $row);
    }

    $this->_generate_view($data);
  }

  public function lookup_bts() {
    if ($this->input->is_ajax_request()) {
//      $data['morphology_opt'] = $this->mglobal->lookup('bts_morphology');
//      $data['bts_mode_opt'] = $this->mglobal->lookup('bts_mode');
//      $data['bts_type_opt'] = $this->mglobal->lookup('bts_type');
//      $data['bts_brand_opt'] = $this->mglobal->lookup('bts_brand');
      $data['transmitter_polarization_opt'] = $this->mglobal->lookup('bts_polarization');

      $this->load->view('resi_lookup_bts', $data);
    } else {
      echo 'Forbidden access.';
    }
  }

  public function get_bts_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->resi_model->get_bts_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $data['total'] = $result['total'];
      $data['rows'] = array();
      foreach ($result['rows'] as $row) {
        //$row->station_name = "{$row->vendor_id}_{$row->site_name}";
        $row->station_name = "{$row->site_name}";
//Latitude
        if (is_numeric($row->latitude)) {
          $this->decimal_to_dms($row->latitude, $deg1, $min1, $sec1, $dir1, true);
        } else if ($row->latitude == NULL) {
          $deg1 = 0;
          $min1 = 0;
          $sec1 = 0;
          $dir1 = '';
        } else {
          $lats = explode(' ', $row->latitude);
          $deg1 = $lats[0];
          $min1 = $lats[1];
          $sec1 = $lats[2];
          $dir1 = $lats[3];
        }
        $row->lat_deg = $deg1;
        $row->lat_min = $min1;
        $row->lat_sec = number_format($sec1, 2);
        $row->lat_dir = strtoupper($dir1);
//Longitude
        if (is_numeric($row->longitude)) {
          $this->decimal_to_dms($row->longitude, $deg2, $min2, $sec2, $dir2, false);
        } else if ($row->latitude == NULL) {
          $deg2 = 0;
          $min2 = 0;
          $sec2 = 0;
          $dir2 = '';
        } else {
          $longs = explode(' ', $row->longitude);
          $deg2 = $longs[0];
          $min2 = $longs[1];
          $sec2 = $longs[2];
          $dir2 = $longs[3];
        }
        $row->long_deg = $deg2;
        $row->long_min = $min2;
        $row->long_sec = number_format($sec2, 2);
        $row->long_dir = strtoupper($dir2);

        array_push($data['rows'], $row);
      }

      echo json_encode($data, JSON_NUMERIC_CHECK);
    } else {
      echo 'Forbidden access.';
    }
  }

  public function delete_proposed_new($app_resi_id = 0) {
    if ($this->input->is_ajax_request() && is_numeric($app_resi_id)) {
      $this->db->trans_start();

      $this->db->start_cache();
      $this->db->where('app_resi_id', $app_resi_id);
      $this->db->stop_cache();
      $this->db->delete('t_trx_isr_bts_app_resi');
      $this->db->delete('t_trx_isr_bts_app_resi_link');

      $this->db->trans_complete();
      if ($this->db->trans_status() == TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Resi has been deleted successfully.'
        );
      } else {
        $response = array(
            'status' => 'error',
            'message' => 'Resi failed to delete.'
        );
      }

      echo json_encode($response);
    } else {
      echo 'Forbidden access.';
    }
  }

  public function load_proposed_discontinued() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('resi_list_discontinued');
    } else {
      die('Forbidden access');
    }
  }

  public function get_proposed_discontinue_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->resi_model->get_proposed_discontinue_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      echo json_encode($result);
    } else {
      die('Forbidden access');
    }
  }

  public function get_resi_path() {
    $path_bts = 'file/upload/isr_bts';
    $path_resi = '1102';
    $path = $path_bts . '/' . $path_resi;

    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }

    return $path;
  }

  private function _set_proposed_new_validation() {
    $this->form_validation->set_rules('app_issued', 'App Issued', 'trim|required');
    $this->form_validation->set_rules('station_identifier[]', 'Station ID', 'trim|required');
    $this->form_validation->set_rules('height_asl[]', 'Height ASL', 'trim|required|numeric');
    $this->form_validation->set_rules('radius_of_operation[]', 'Radius of Operation', 'trim|required|numeric');
    $this->form_validation->set_rules('equipment_type[]', 'Equipment Type', 'trim|required');
    $this->form_validation->set_rules('equipment_output_power[]', 'Equipment Output Power', 'trim|required|numeric');
    $this->form_validation->set_rules('bandwidth[]', 'Bandwidth', 'trim|required|numeric');
    $this->form_validation->set_rules('tx_freq[]', 'Tx Freq', 'trim|required|numeric');
    $this->form_validation->set_rules('rx_freq[]', 'Rx Freq', 'trim|required|numeric');
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

  function decimal_to_dms($decimal, &$degrees, &$minutes, &$seconds, &$direction, $type = true) {
//set default values for variables passed by reference
    $degrees = 0;
    $minutes = 0;
    $seconds = 0;
    $direction = 'X';

//decimal must be integer or float no larger than 180;
//type must be Boolean
    if (!is_numeric($decimal) || abs($decimal) > 180 || !is_bool($type)) {
      return false;
    }

//inputs OK, proceed
//type is latitude when true, longitude when false
//set direction; north assumed
    if ($type && $decimal < 0) {
      $direction = 'S';
    } elseif (!$type && $decimal < 0) {
      $direction = 'W';
    } elseif (!$type) {
      $direction = 'E';
    } else {
      $direction = 'N';
    }

//get absolute value of decimal
    $d = abs($decimal);

//get degrees
    $degrees = floor($d);

//get seconds
    $seconds = ($d - $degrees) * 3600;

//get minutes
    $minutes = floor($seconds / 60);

//reset seconds
    $seconds = ($seconds - ($minutes * 60));

//return $degrees . '&deg;' . $minutes . '\\\'' . number_format($seconds, 6) . '\\\'\\\'' . $direction;
  }

  protected function _generate_view($data, $template = 'template/smashing') {
    $this->load->view($template, $data);
  }

}

?>
