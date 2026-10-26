<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of historical_data
 *
 * @author tumbas
 */
class Historical extends MX_Controller {

  public function __construct() {
    parent::__construct();
    $this->mglobal->is_logged_in();
  }

  public function index() {
    $data['content_id'] = 'historical/index';
    $data['date_from'] = date('01 M Y');
    $data['date_to'] = date('d M Y');

    $this->load->view('template/smashing', $data);
  }

  public function get_list() {
    if ($this->input->is_ajax_request() && $this->input->get()) {
      $this->load->model(array('historical_model'));
      $data = $this->historical_model->get_list();

      $response = array('total' => $data['total'], 'rows'=>array());
      foreach ($data['rows'] as $row) {
//        $row->{'Criteria'} = "<a href='' onclick='viewDetail(\"{$row->{'Table Name'}}\", \"{$row->pk_field}\", {$row->{'Primary Key'}}); return false;'>{$row->pk_field} = {$row->{'Primary Key'}}</a>";
        $row->{'Primary Key'} = "<a href='' onclick='viewDetail(\"{$row->{'Table Name'}}\", \"{$row->pk_field}\", {$row->{'Primary Key'}}); return false;'>{$row->{'Primary Key'}}</a>";
        array_push($response['rows'], $row);
      }

      echo json_encode($response);
    } else {
      show_404();
    }
  }

  function lookup_table() {
    if ($this->input->is_ajax_request()) {
      $data['judul'] = "Table List";
      $this->load->view("historical/table_list", $data);
    } else {
      show_404();
    }
  }

  public function get_table_list() {
    if ($this->input->is_ajax_request()) {
      $this->load->model(array('historical_model'));
      $result = $this->historical_model->get_table_list();
      echo json_encode($result);
    } else {
      show_404();
    }
  }

  public function get_detail() {
    $table_name = $this->input->get('tbl');
    $field = $this->input->get('fld');
    $primary_key = $this->input->get('pk');
    if ($this->input->is_ajax_request() && $table_name && $field && $primary_key) {
      $this->load->model(array('historical_model'));
      $data['row'] = $this->historical_model->get_detail($table_name, $field, $primary_key);
      $data['table_name'] = $table_name;
      
      $this->load->view('historical/detail', $data);
    } else {
      show_404();
    }
  }

}

?>
