<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class preventive extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->mglobal->is_logged_in();
        $this->load->model('mpreventive');
    }

    ########################################################################
    //Manag.preventive

    public function index() {
        $data['judul'] = "Checklist Desktop";
        $data['content_id'] = "preventive_view";
        $this->theme->view("preventive_view", $data);
    }

    //add and edit data
    public function addeditdata() {
        $data['id'] = $this->uri->segment(4);
        $data['judul'] = "Checklist Desktop";
        $data['hasil'] = '';
        $data['error'] = '';
        $this->theme->view("r-preventive", $data);
    }

    public function addeditdatas() {
        $data['id'] = $this->uri->segment(4);
        $dt = $this->input->post();
        $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
        $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
        $dt['offset'] = ($dt['page'] - 1) * $dt['rows'];
        $q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : '';
        $data['q'] = strtolower($q);

        $d = $this->input->get();
        $q = $this->mpreventive->addeditdata($data['id'], $data);
        $result["total"] = $q->num_rows();
        $items = array();
        foreach ($q->result() as $row) {

            $row->textbox = '<input type=text name=result>';
            $row->upload = '<input type="file" />';
            $row->checklist = '<div><input type="checkbox" checked="checked" /></div>';
            array_push($items, $row);
        }
        $result["rows"] = $items;
        echo json_encode($result);
    }

    public function loadData() {
        $this->mpreventive->loadData();
    }

    //load data
    function ListCkDesk($status) {
        $dt = $this->input->post();
        $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
        $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
        $dt['offset'] = ($dt['page'] - 1) * $dt['rows'];
        $q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : '';
        $data['q'] = strtolower($q);

        $d = $this->input->get();
        $q = $this->mpreventive->getListVisit($status, $data);
        $result["total"] = $q->num_rows();
        $items = array();
        foreach ($q->result() as $row) {

            $row->action = '<a href="' . base_url() . 'preventive/preventive/addeditdata/' . $row->pm_id . '" class="btn-action glyphicons pencil btn-success" ><i></i></a>';
            array_push($items, $row);
        }
        $result["rows"] = $items;
        echo json_encode($result);
    }

    function ListVisitDetail() {
        $dt = $this->input->post();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'pm_id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

        $offset = ($page - 1) * $rows;
        $d = $this->input->get();

        $qs = $this->mpreventive->getListDetails($this->input->get('pm_id'));
        $result["total"] = $qs->num_rows();
        $q = $this->mpreventive->getListDetails($this->input->get('pm_id'), $offset, $rows);

        $filenotfound = base_url() . 'file/upload/not-found.png';
        $dataempty = base_url() . 'file/upload/dataempty.jpg';
        $items = array();
        foreach ($q->result() as $row) {

            array_push($items, $row);
        }
        $result["rows"] = $items;
        echo json_encode($result);
    }

    /* function ListVisitDetail(){
      $dt = $this->input->post();
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
      $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'pm_id';
      $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

      $offset = ($page-1)*$rows;
      $d=$this->input->get();

      $qs=$this->mpreventive->getListDetails($this->input->get('pm_id'));
      $result["total"] = $qs->num_rows();
      $q=$this->mpreventive->getListDetails($this->input->get('pm_id'),$offset,$rows);

      $filenotfound = base_url().'file/upload/not-found.png';
      $dataempty = base_url().'file/upload/dataempty.jpg';
      $items = array();
      foreach ($q->result() as $row) {
      if($row->photo_before==""){
      $row->photo_before="<center><img width='50px' src='".$dataempty."' alt='Album'/></center>";
      }else{
      $filebefore= base_url().'file/upload/'.$row->photo_before;
      $patch = FCPATH.'file/upload/'.$row->photo_before;
      if (file_exists($patch)) {
      $row->photo_before="<center><a class='group' href='".$filebefore."'><img width='50px' src='".$filebefore."' alt='Album'/></a></center>";
      }else{
      $row->photo_before="<center><img width='50px' src='".$filenotfound."' alt='Album'/></center>";
      }
      }
      if($row->photo_after==""){
      $row->photo_after="<center><img width='50px' src='".$dataempty."' alt='Album'/></center>";
      }else{
      $fileafter= base_url().'file/upload/'.$row->photo_after;
      $patchafter = FCPATH.'file/upload/'.$row->photo_after;
      if (file_exists($patchafter)) {
      $row->photo_after="<center><img width='50px' src='".$fileafter."' alt='Album'/></center>";
      }else{
      $row->photo_after="<center><img width='50px' src='".$filenotfound."' alt='Album'/></center>";
      }
      }
      array_push($items, $row);
      }
      $result["rows"] = $items;
      echo json_encode($result);

      } */
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */