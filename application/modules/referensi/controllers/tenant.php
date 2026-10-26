<?php

class Tenant extends MX_Controller {

  private $_partials = array();
  protected $validation_rules = array(
      array(
          'field' => 'name',
          'label' => 'Name',
          'rules' => 'trim|required|callback__check_name'
      ),
  );

  public function __construct() {
    parent::__construct();

    $this->form_validation->CI = & $this;

    self::_check_valid_loggedin();

    $this->load->model('mtenant');

    $uri = $this->router->fetch_module() . '/' . $this->router->class;
    $this->_partials['menu'] = $this->mmenu->getMenuId($uri);
  }

  public function index() {
    $data = $this->_partials;

    if ($this->input->is_ajax_request()) {

      $rows = $this->mtenant->get_list();

      echo json_encode($rows);
      exit;
    }

    $data['content_id'] = 'tenant/index';

    $this->load->view('template/smashing', $data);
  }

  public function add() {
    $data = $this->_partials;

    if ($this->input->is_ajax_request()) {

      $json = array();

      $this->form_validation->set_rules($this->validation_rules);
      $this->form_validation->set_error_delimiters('', '');

      if ($this->form_validation->run()) {

        $data = array(
            'tenant_name' => trim($this->input->post('name')),
            'status' => 1,
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('user_id')
        );

        $this->mtenant->add($data);

        $json = array('error' => FALSE, 'message' => 'Tenant added.');
        echo json_encode($json);
        exit;
      } else {
        $errors = array();
        foreach ($this->validation_rules as $v) {
          if (form_error($v['field'])) {
            $errors[$v['field']] = form_error($v['field']);
          }
        }

        $json = array('error' => TRUE, 'message' => $errors);

        echo json_encode($json);
        exit;
      }
    }

    $tenant = new stdClass();
    $tenant->tenant_name = '';

    $data['tenant'] = $tenant;
    $data['sub_title'] = 'Add Tenant';
    $data['content_id'] = 'tenant/form';

    $this->load->view('template/smashing', $data);
  }

  public function edit($id = '') {
    $data = $this->_partials;

    $id or redirect('referensi/tenant');

    $id = (int) $this->core->decode($id);

    $tenant = $this->mtenant->get_tenant($id);

    if (!$tenant) {
      show_404();
    }

    if ($this->input->is_ajax_request()) {

      $json = array();

      $this->form_validation->set_rules($this->validation_rules);
      $this->form_validation->set_error_delimiters('', '');

      if ($this->form_validation->run()) {

        $data = array(
            'tenant_name' => trim($this->input->post('name')),
            'updated_on' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        );

        $this->mtenant->edit($data, $id);

        $json = array('error' => FALSE, 'message' => 'Tenant edited.');
        echo json_encode($json);
        exit;
      } else {
        $errors = array();
        foreach ($this->validation_rules as $v) {
          if (form_error($v['field'])) {
            $errors[$v['field']] = form_error($v['field']);
          }
        }

        $json = array('error' => TRUE, 'message' => $errors);

        echo json_encode($json);
        exit;
      }
    }

    $data['tenant'] = $tenant;
    $data['sub_title'] = 'Edit Tenant';
    $data['content_id'] = 'tenant/form';

    $this->load->view('template/smashing', $data);
  }

  public function delete() {
    if ($this->input->is_ajax_request()) {

      $json = array();

      $id = $this->input->get('id') OR show_404();

      $id = (int) $this->core->decode($id);

      $tenant = $this->mtenant->get_tenant($id);

      if (!$tenant) {
        show_404();
      }

      $this->mtenant->delete($id);

      $json = array('error' => FALSE, 'message' => 'Tenant deleted.');
      echo json_encode($json);
      exit;
    }
  }

  public function _check_name($name) {
    $tenant = $this->mtenant->get_tenants(array('lower(tenant_name)' => strtolower(trim($name))));

    if (!$tenant || $this->router->method == 'edit') {
      return TRUE;
    }

    $this->form_validation->set_message('_check_name', 'Name is already exist.');
    return FALSE;
  }

  private function _check_valid_loggedin() {
    if (!$this->session->userdata('is_login')) {

      if ($this->core->is_ajax()) {

        echo json_encode(array(
            'loggedin' => FALSE,
            'message' => 'You are not logged in. You will be redirected to login page.',
            'redirect_url' => site_url('/login')
        ));
        exit;
      } else {
        redirect('/login');
      }
    }
  }

}