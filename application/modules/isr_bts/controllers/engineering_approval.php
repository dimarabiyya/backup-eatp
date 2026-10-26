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
class Engineering_approval extends MX_Controller {

  const ENGIN_APPROV_APPROVED = 1;
  const ENGIN_APPROV_REJECTED = 2;

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
  const SPP_PAY_NEW = '1131';
  const SPP_PAY_RENEWAL = '1221';
  const DGPT_NEW = '1141';
  const DGPT_RENEWAL = '1231';
  const DGPT_RELEASED_NEW = '1151';
  const DGPT_RELEASED_RENEWAL = '1241';

  protected $_user_now;
  protected $_date_now;

  public function __construct() {
    parent::__construct();
    $this->load->model('engineering_approval_model', 'approval_model');
    $this->mglobal->is_logged_in();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function index() {
    $data['content_id'] = 'engineering_approval_list';
    $this->_generate_view($data);
  }

  public function load_engineering_approval_new() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('engineering_approval_list_new');
    } else {
      die('Forbidden access');
    }
  }

  public function load_engineering_approval_renewal() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('engineering_approval_renewal_list');
    } else {
      die('Forbidden access');
    }
  }

  public function load_engineering_approval_discontinued() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('engineering_approval_discontinued_list');
    } else {
      die('Forbidden access');
    }
  }

  public function get_engineering_approval_new_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->approval_model->get_engineering_approval_new_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/engineering_approval");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $app_resi_id = $this->core->encode($row->app_resi_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
          $row->action.= '<button title="Approval" class="btn btn-action glyphicons upload btn-success"  onclick="uploadNew(\'' . $app_resi_id . '\')"><i></i></button> &nbsp;';
        }
        $row->app_resi_id = $app_resi_id;

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_engineering_approval_renewal_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->approval_model->get_engineering_approval_renewal_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/engineering_approval");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $app_id = $this->core->encode($row->app_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
          $row->action.= '<button title="Approval" class="btn btn-action glyphicons upload btn-success"  onclick="uploadRenewal(\'' . $app_id . '\')"><i></i></button> &nbsp;';
        }
        $row->app_id = $app_id;

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_engineering_approval_discontinued_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->approval_model->get_engineering_approval_discontinued_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/engineering_approval");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $app_id = $this->core->encode($row->app_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url("file/upload/isr_bts/{$row->doc}") . "'>{$this->core->setFileNameIcon($row->doc)}</a>";
        $row->created_by = "{$row->created_by}<br><span class='gray'>{$row->created_on}</span>";

        $row->action = '';
        if ($this->mmenu->cekPermission($menu, 'menu_add')) {
          $row->action.= '<button title="Approval" class="btn btn-action glyphicons upload btn-success"  onclick="uploadDiscontinued(\'' . $app_id . '\')"><i></i></button> &nbsp;';
        }
        $row->app_id = $app_id;

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function load_engineering_approval_approved_new() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('engineering_approval_approved_new_list');
    } else {
      die('Forbidden access');
    }
  }

  public function load_engineering_approval_approved_renewal_discontinued() {
    if ($this->input->is_ajax_request()) {
      $this->load->view('engineering_approval_approved_renewal_discontinued_list');
    } else {
      die('Forbidden access');
    }
  }

  public function get_engineering_approval_approved_new_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->approval_model->get_engineering_approval_approved_header_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/engineering_approval");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $app_resi_id = $this->core->encode($row->app_resi_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url($this->_get_engineering_approval_path()) . "/{$row->doc}'>{$this->core->setFileNameIcon($row->doc)}</a>";
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

  public function get_engineering_approval_approved_renewal_discontinued_list() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post_data = $this->input->post();
      $page = intval($post_data['page']);
      $limit = intval($post_data['rows']);
      $offset = ($page - 1) * $limit;
      $result = $this->approval_model->get_engineering_approval_approved_renewal_discontinued_list($limit, $offset, $this->db->escape_str($this->input->post('q')));

      $menu = $this->mmenu->getMenuId("isr_bts/engineering_approval");
      $list['total'] = $result['total'];
      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $app_id = $this->core->encode($row->app_id);
        $row->doc = "<a download='' target='_blank' href='" . base_url($this->_get_engineering_approval_path()) . "/{$row->doc}'>{$this->core->setFileNameIcon($row->doc)}</a>";
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

  public function get_engineering_approval_detail_list() {
    if ($this->input->is_ajax_request()) {
      $app_resi_id = $this->core->decode($this->input->get('app_resi_id'));
      $result = $this->approval_model->get_engineering_approval_detail_list($app_resi_id);
      foreach ($result as $key => $value) {
        $result[$key]->latitude = dms_format($result[$key]->latitude);
        $result[$key]->longitude = dms_format($result[$key]->longitude);
      }
      
      echo json_encode($result);
    } else {
      die('Forbidden access');
    }
  }

  public function get_engineering_approval_detail_list2() {
    if ($this->input->is_ajax_request()) {
      $app_id = $this->core->decode($this->input->get('app_id'));
      
      $result = $this->approval_model->get_engineering_approval_detail_list2($app_id);
      foreach ($result as $key => $value) {
        $result[$key]->latitude = dms_format($result[$key]->latitude);
        $result[$key]->longitude = dms_format($result[$key]->longitude);
      }
      
      echo json_encode($result);
    } else {
      die('Forbidden access');
    }
  }

  public function get_engineering_approval_detail_new_list($param) {
    if ($this->input->is_ajax_request()) {
      $app_resi_id = $this->core->decode($this->uri->segment(4));
//      $post_data = $this->input->post();
//      $page = intval($post_data['page']);
//      $limit = intval($post_data['rows']);
//      $offset = ($page - 1) * $limit;
      //$result = $this->approval_model->get_engineering_approval_detail_new_list($limit, $offset, $this->input->post('q'));
      $result = $this->approval_model->get_engineering_approval_detail_new_list($this->db->escape_str($this->input->post('q')), $app_resi_id);
      $list['total'] = $result['total'];
      $menu = $this->mmenu->getMenuId("isr_bts/engineering_approval");

      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->station_identifier = "{$row->station_identifier} <input type='hidden' name='resi_link_id[]' value='{$row->resi_link_id}'>";
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

        $row->latitude = "{$row->lat_deg}&deg{$row->lat_min}'{$row->lat_sec}\"{$row->lat_dir}";
        $row->longitude = "{$row->long_deg}&deg{$row->long_min}'{$row->long_sec}\"{$row->long_dir}";

        $row->action = '';
        if ($row->engineering_approval_status != 1) {
          $row->action .= "<button type='button' title='Approve' class='btn-action btn-success glyphicons ok_2' onclick='approveLink({$app_resi_id})'><i></i></button> ";
          $row->action .= "<button type='button' title='Reject' class='btn-action btn-danger glyphicons remove_2' onclick='validateLink({$app_resi_id})'><i></i></button>";
        }

        if ($row->engineering_approval_status == '1') {
          $row->engineering_approval_status = '<span class="badge badge-success">Approved</span>';
        } elseif ($row->engineering_approval_status == '2') {
          $row->engineering_approval_status = '<span class="badge badge-danger">Rejected</span>';
        } else {
          $row->engineering_approval_status = ' - ';
        }

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function load_engineering_approval_add_new() {
    if ($this->input->is_ajax_request()) {
      $app_resi_id = $this->core->decode($this->uri->segment(4));
      $data['resi'] = $this->db->get_where('t_trx_isr_bts_app_resi', array('app_resi_id' => $app_resi_id))->row();
      $data['resi']->app_resi_id = $this->uri->segment(4);
      $data['resi']->app_issued = date('d M Y', strtotime($data['resi']->app_issued));

      $this->load->view('engineering_approval_add_new', $data);
    }
  }

  public function load_engineering_approval_add_renewal() {
    if ($this->input->is_ajax_request()) {
      $app_id = $this->core->decode($this->uri->segment(4));
      $data['app'] = $this->db->get_where('t_trx_isr_bts_app', array('app_id' => $app_id))->row();
      $data['app']->app_id = $this->uri->segment(4);
      $data['app']->app_issued = date('d M Y', strtotime($data['app']->app_issued));

      $this->load->view('engineering_approval_add_renewal', $data);
    }
  }

  public function load_engineering_approval_add_discontinued() {
    if ($this->input->is_ajax_request()) {
      $app_id = $this->core->decode($this->uri->segment(4));
      $data['app'] = $this->db->get_where('t_trx_isr_bts_app', array('app_id' => $app_id))->row();
      $data['app']->app_id = $this->uri->segment(4);
      $data['app']->app_issued = date('d M Y', strtotime($data['app']->app_issued));
      $data['app']->milestone_status = $this->db->get_where('t_trx_isr_bts_app_link',  array('app_id' => $app_id))->row()->milestone_status;

      $this->load->view('engineering_approval_add_discontinued', $data);
    }
  }

  public function get_engineering_approval_detail_renewal_list($param) {
    if ($this->input->is_ajax_request()) {
      $app_resi_id = $this->core->decode($this->uri->segment(4));
//      $post_data = $this->input->post();
//      $page = intval($post_data['page']);
//      $limit = intval($post_data['rows']);
//      $offset = ($page - 1) * $limit;
      //$result = $this->approval_model->get_engineering_approval_detail_new_list($limit, $offset, $this->input->post('q'));
      $result = $this->approval_model->get_engineering_approval_detail_renewal_list($this->db->escape_str($this->input->post('q')), $app_resi_id);
      $list['total'] = $result['total'];
      $menu = $this->mmenu->getMenuId("isr_bts/engineering_approval");

      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->station_identifier = "{$row->station_identifier} <input type='hidden' name='app_link_id[]' value='{$row->app_link_id}'>";
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

        $row->latitude = "{$row->lat_deg}&deg{$row->lat_min}'{$row->lat_sec}\"{$row->lat_dir}";
        $row->longitude = "{$row->long_deg}&deg{$row->long_min}'{$row->long_sec}\"{$row->long_dir}";

        $row->action = '';
        if ($row->engineering_approval_status != 1) {
          $row->action .= "<button type='button' title='Approve' class='btn-action btn-success glyphicons ok_2' onclick='approveLink({$app_resi_id})'><i></i></button> ";
          $row->action .= "<button type='button' title='Reject' class='btn-action btn-danger glyphicons remove_2' onclick='validateLink({$app_resi_id})'><i></i></button>";
        }

        if ($row->engineering_approval_status == '1') {
          $row->engineering_approval_status = '<span class="badge badge-success">Approved</span>';
        } elseif ($row->engineering_approval_status == '2') {
          $row->engineering_approval_status = '<span class="badge badge-danger">Rejected</span>';
        } else {
          $row->engineering_approval_status = ' - ';
        }

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function get_engineering_approval_detail_discontinued_list($param) {
    if ($this->input->is_ajax_request()) {
      $app_resi_id = $this->core->decode($this->uri->segment(4));
//      $post_data = $this->input->post();
//      $page = intval($post_data['page']);
//      $limit = intval($post_data['rows']);
//      $offset = ($page - 1) * $limit;
      //$result = $this->approval_model->get_engineering_approval_detail_new_list($limit, $offset, $this->input->post('q'));
      $result = $this->approval_model->get_engineering_approval_detail_discontinued_list($this->db->escape_str($this->input->post('q')), $app_resi_id);
      $list['total'] = $result['total'];
      $menu = $this->mmenu->getMenuId("isr_bts/engineering_approval");

      $list['rows'] = array();
      foreach ($result['rows'] as $row) {
        $row->station_identifier = "{$row->station_identifier} <input type='hidden' name='app_link_id[]' value='{$row->app_link_id}'>";
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

        $row->latitude = "{$row->lat_deg}&deg{$row->lat_min}'{$row->lat_sec}\"{$row->lat_dir}";
        $row->longitude = "{$row->long_deg}&deg{$row->long_min}'{$row->long_sec}\"{$row->long_dir}";

        $row->action = '';
        if ($row->engineering_approval_status != 1) {
          $row->action .= "<button type='button' title='Approve' class='btn-action btn-success glyphicons ok_2' onclick='approveLink({$app_resi_id})'><i></i></button> ";
          $row->action .= "<button type='button' title='Reject' class='btn-action btn-danger glyphicons remove_2' onclick='validateLink({$app_resi_id})'><i></i></button>";
        }

        if ($row->engineering_approval_status == '1') {
          $row->engineering_approval_status = '<span class="badge badge-success">Approved</span>';
        } elseif ($row->engineering_approval_status == '2') {
          $row->engineering_approval_status = '<span class="badge badge-danger">Rejected</span>';
        } else {
          $row->engineering_approval_status = ' - ';
        }

        array_push($list['rows'], $row);
      }

      echo json_encode($list);
    } else {
      die('Forbidden access');
    }
  }

  public function approve_link() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $this->db->trans_start();

      $data_post = $this->input->post();
      //update to app link approval status
      $this->db->where('app_resi_id', $data_post['app_resi_id']);
      $data = array(
          'engineering_approval_status' => self::ENGIN_APPROV_APPROVED,
      );
      $this->db->update('t_trx_isr_bts_app_resi_link', $data);

      //save to isr approval for log
//      $query = $this->db->get_where('t_trx_isr_bts_app_resi_link', array('app_resi_id' => $data_post['app_resi_id']));
//
//      foreach ($query->result() as $value) {
//        $data_approval = array(
//            'app_id' => $data_post['app_resi_id'],
//            'link_id' => $value->resi_link_id,
//            'status_approval' => self::ENGIN_APPROV_APPROVED,
//        );
//        $this->mglobal->saveData('t_trx_isr_bts_approval', $data_approval);
//      }

      $this->db->trans_complete();

      $response = array();
      if ($this->db->trans_status() == TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Link has been approved successfully.',
        );
      } else {
        $response = array(
            'status' => 'error',
            'message' => 'Link failed to approve.'
        );
      }

      echo json_encode($response);
    } else {
      show_404();
    }
  }

  public function approve_link2() {
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $this->db->trans_start();

      $data_post = $this->input->post();
      //update to app link approval status
      $this->db->where('app_id', $data_post['app_id']);
      $data = array(
          'engineering_approval_status' => self::ENGIN_APPROV_APPROVED,
      );
      $this->db->update('t_trx_isr_bts_app_link', $data);

      //save to isr approval for log
//      $query = $this->db->get_where('t_trx_isr_bts_app_link', array('app_id' => $data_post['app_id']));
//
//      foreach ($query->result() as $value) {
//        $data_approval = array(
//            'app_id' => $data_post['app_id'],
//            'link_id' => $value->app_link_id,
//            'status_approval' => self::ENGIN_APPROV_APPROVED,
//        );
//        $this->mglobal->saveData('t_trx_isr_bts_approval', $data_approval);
//      }

      $this->db->trans_complete();

      $response = array();
      if ($this->db->trans_status() == TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Link has been approved successfully.',
        );
      } else {
        $response = array(
            'status' => 'error',
            'message' => 'Link failed to approve.'
        );
      }

      echo json_encode($response);
    } else {
      show_404();
    }
  }

  public function add_engineering_approval_new() {
    if ($this->input->is_ajax_request()) {
      $data_post = $this->input->post();
      $resi_id = $this->core->decode($data_post['resi_id']);
      $isApproveAll = $this->approval_model->check_app_link_status($resi_id);
      //$isApproveAll = TRUE;
      if ($isApproveAll == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Data cannot be save, please approve all links.'
        ));
        exit();
      }

      $this->db->trans_start();

      $config['upload_path'] = $this->_get_engineering_approval_path();
      $config['allowed_types'] = '*';
      $config['max_size'] = '500000';
      $config['file_name'] = 'Engin_Approv_' . $data_post['app_no'] . '_' . date('Ymd');
      $this->load->library('upload', $config);
      $this->upload->initialize($config);

      $docId = NULL;
      if (!empty($_FILES['doc']['name'])) {
        if (!$this->upload->do_upload('doc')) {
          exit(json_encode(array(
              'status' => 'ERROR',
              'hasil' => $this->upload->display_errors()
          )));
        } else {
          $pic = $this->upload->data();
          $docData['app_id'] = $resi_id;
          $docData['doc_no'] = $pic['file_name'];
          $docData['file_name'] = $pic['file_name'];
          $docData['doc_type'] = 1162;
          $docData['app_type'] = 2;

          $docId = $this->mglobal->setData('t_trx_isr_bts_doc', $docData);

          //$data_app_resi['doc'] = '1162/'.$pic['file_name'];
        }
      }

      $data_app_resi['app_resi_id'] = $resi_id;
      $data_app_resi['isUsed'] = 1;
      $this->mglobal->saveData('t_trx_isr_bts_app_resi', $data_app_resi, 'app_resi_id');

      foreach ($data_post['resi_link_id'] as $key => $value) {
        $data_approval = array(
            'app_id' => $resi_id,
            'link_id' => $data_post['resi_link_id'][$key],
            'status_approval' => 1,
            'doc_id' => $docId,
            'app_type' => 2
        );
        $this->mglobal->saveData('t_trx_isr_bts_approval', $data_approval);

        $data_app_link = array(
            'resi_link_id' => $data_post['resi_link_id'][$key],
            'prev_completed_milestone' => self::ENGIN_APPROV_NEW,
            'milestone_status' => self::M2M_NEW,
        );
        $this->mglobal->saveData('t_trx_isr_bts_app_resi_link', $data_app_link, 'resi_link_id');
      }

      $this->db->trans_complete();

      $response = array();
      if ($this->db->trans_status() == TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Application has been approved.'
        );
      } else {
        $response = array(
            'status' => 'error',
            'message' => 'Approved the application has failed.'
        );
      }

      echo json_encode($response);
    } else {
      echo 'Forbidden Access.';
    }
  }

  public function add_engineering_approval_renewal() {
    if ($this->input->is_ajax_request()) {
      $data_post = $this->input->post();
      $app_id = $this->core->decode($data_post['app_id']);
      $isApproveAll = $this->approval_model->check_app_link_status2($app_id);
      //$isApproveAll = TRUE;
      if ($isApproveAll == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Data cannot be save, please approve all links.'
        ));
        exit();
      }

      $this->db->trans_start();

      $config['upload_path'] = $this->_get_renewal_engineering_approval_path();
      $config['allowed_types'] = '*';
      $config['max_size'] = '500000';
      $config['file_name'] = 'Engin_Approv_' . $data_post['app_no'] . '_' . date('Ymd');
      $this->load->library('upload', $config);
      $this->upload->initialize($config);

      $docId = NULL;
      if (!empty($_FILES['doc']['name'])) {
        if (!$this->upload->do_upload('doc')) {
          exit(json_encode(array(
              'status' => 'ERROR',
              'hasil' => $this->upload->display_errors()
          )));
        } else {
          $pic = $this->upload->data();
          $docData['app_id'] = $app_id;
          $docData['doc_no'] = $pic['file_name'];
          $docData['file_name'] = $pic['file_name'];
          $docData['doc_type'] = 1262;
          $docData['app_type'] = 4;

          $docId = $this->mglobal->setData('t_trx_isr_bts_doc', $docData);

          $data_app['doc'] = '1262/' . $pic['file_name'];
        }
      }

      $data_app['app_id'] = $app_id;
      $data_app['isUsed'] = 1;
      $this->mglobal->saveData('t_trx_isr_bts_app', $data_app, 'app_id');

      foreach ($data_post['app_link_id'] as $key => $value) {
        $data_approval = array(
            'app_id' => $app_id,
            'link_id' => $data_post['app_link_id'][$key],
            'status_approval' => 1,
            'doc_id' => $docId,
            'app_type' => 4
        );
        $this->mglobal->saveData('t_trx_isr_bts_approval', $data_approval);

        $data_app_link = array(
            'app_link_id' => $data_post['app_link_id'][$key],
            'prev_completed_milestone' => self::ENGIN_APPROV_RENEWAL,
            'milestone_status' => self::SPP_FIN_RENEWAL,
        );
        $this->mglobal->saveData('t_trx_isr_bts_app_link', $data_app_link, 'app_link_id');
      }

      $this->db->trans_complete();

      $response = array();
      if ($this->db->trans_status() == TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Application has been approved.'
        );
      } else {
        $response = array(
            'status' => 'error',
            'message' => 'Approved the application has failed.'
        );
      }

      echo json_encode($response);
    } else {
      echo 'Forbidden Access.';
    }
  }

  public function add_engineering_approval_discontinued() {
    if ($this->input->is_ajax_request()) {
      $data_post = $this->input->post();
      $app_id = $this->core->decode($data_post['app_id']);
      $isApproveAll = $this->approval_model->check_app_link_status2($app_id);
      //$isApproveAll = TRUE;
      if ($isApproveAll == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Data cannot be save, please approve all links.'
        ));
        exit();
      }

      $this->db->trans_start();

      $doc_type = intval($data_post['milestone_status']) + 1;
      $config['upload_path'] = $this->_get_discontinued_engineering_approval_path($doc_type);
      $config['allowed_types'] = '*';
      $config['max_size'] = '500000';
      $config['file_name'] = 'Engin_Approv_' . $data_post['app_no'] . '_' . date('Ymd');
      $this->load->library('upload', $config);
      $this->upload->initialize($config);

      $docId = NULL;
      if (!empty($_FILES['doc']['name'])) {
        if (!$this->upload->do_upload('doc')) {
          exit(json_encode(array(
              'status' => 'ERROR',
              'hasil' => $this->upload->display_errors()
          )));
        } else {
          $pic = $this->upload->data();
          $docData['app_id'] = $app_id;
          $docData['doc_no'] = $pic['file_name'];
          $docData['file_name'] = $pic['file_name'];
          $docData['doc_type'] = $doc_type;
          $docData['app_type'] = 4;

          $docId = $this->mglobal->setData('t_trx_isr_bts_doc', $docData);

          $data_app['doc'] = "{$doc_type}/{$pic['file_name']}";
        }
      }

      $data_app['app_id'] = $app_id;
      $data_app['isUsed'] = 1;
      $this->mglobal->saveData('t_trx_isr_bts_app', $data_app, 'app_id');

      foreach ($data_post['app_link_id'] as $key => $value) {
        $data_approval = array(
            'app_id' => $app_id,
            'link_id' => $data_post['app_link_id'][$key],
            'status_approval' => self::ENGIN_APPROV_APPROVED,
            'doc_id' => $docId,
            'app_type' => 4
        );
        $this->mglobal->saveData('t_trx_isr_bts_approval', $data_approval);

        $data_app_link = array(
            'app_link_id' => $data_post['app_link_id'][$key],
            'prev_completed_milestone' => $data_post['milestone_status'],
            'milestone_status' => intval($data_post['milestone_status']) + 10,
        );
        $this->mglobal->saveData('t_trx_isr_bts_app_link', $data_app_link, 'app_link_id');
      }

      $this->db->trans_complete();

      $response = array();
      if ($this->db->trans_status() == TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Application has been approved.'
        );
      } else {
        $response = array(
            'status' => 'error',
            'message' => 'Approved the application has failed.'
        );
      }

      echo json_encode($response);
    } else {
      echo 'Forbidden Access.';
    }
  }

  protected function _generate_view($data, $template = 'template/smashing') {
    $this->load->view($template, $data);
  }

  private function _get_engineering_approval_path() {
    $path_bts = 'file/upload/isr_bts';
    $path_resi = '1162';
    $path = $path_bts . '/' . $path_resi;

    if (!file_exists($path)) {
      $make_dir = mkdir($path, 0777, true);
      if ($make_dir == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to make directory "Engineering Approval"'
        ));
        exit();
      }
    }

    return $path;
  }

  private function _get_renewal_engineering_approval_path() {
    $path_bts = 'file/upload/isr_bts';
    $path_resi = '1262';
    $path = $path_bts . '/' . $path_resi;

    if (!file_exists($path)) {
      $make_dir = mkdir($path, 0777, true);
      if ($make_dir == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to make directory "Engineering Approval"'
        ));
        exit();
      }
    }

    return $path;
  }

  private function _get_discontinued_engineering_approval_path($doc_type=1362) {
    $path_bts = 'file/upload/isr_bts';
    $path = $path_bts . '/' . $doc_type;

    if (!file_exists($path)) {
      $make_dir = mkdir($path, 0777, true);
      if ($make_dir == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to make directory "Engineering Approval"'
        ));
        exit();
      }
    }

    return $path;
  }

}

?>
