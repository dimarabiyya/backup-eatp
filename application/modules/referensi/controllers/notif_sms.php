<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class notif_sms extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->mglobal->is_logged_in();
        $this->load->model('mnotif_sms');
        //$this->raa->addTooltips($value);
    }

    ########################################################################

    public function index() {
        $data['judul'] = "SMS Notification";
        $data['content_id'] = "notif_sms_view";
        $this->theme->view("notif_sms_view", $data);
    }

    public function debug() {
        $d = $this->input->post();
        print_r($d);
    }

    public function deleteData() {
        if ($this->input->post()!=FALSE && $this->input->is_ajax_request()) {
            $id     = $this->input->post('id');
            $query  = $this->mnotif_sms->deleteData($id);
            if ($query==TRUE) {
                $result['status'] = 'success';
                $result['message'] = 'Data deleted.';
            } else {
                $result['status'] = 'error';
                $result['message'] = 'Deleting data error, please try again.';
            }
            echo json_encode($result);
        } else {
            redirect('referensi/notif_sms');
        }
    }

    public function searchUser() {

        $data['q'] = $this->input->post('q');
        $this->mnotif_email->getUser($data['q']);
    }

    public function load_data() {
        $dt = $this->input->post();
        $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
        $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
        $dt['offset'] = ($dt['page'] - 1) * $dt['rows'];
        $q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : '';
        $data['q'] = strtolower($q);
        $d = $this->input->get();
        $q = $this->mnotif_sms->load_data("0", "0", $data);
        $result["total"] = $q->num_rows();

        $q = $this->mnotif_sms->load_data($dt['offset'], $dt['rows'], $data);
        $items = array();
        foreach ($q->result() as $row) {
            $row->sms_status = ($row->sms_status=='Enable')?"<span class='badge badge-success'>{$row->sms_status}</span>":"<span class='badge badge-default'>{$row->sms_status}</span>";
            $row->action = '
                    <a onclick="Document.editData(' . $row->sms_notif_id . ')" title="Edit" class="btn-action glyphicons pencil btn-info" ><i></i></a>
                    <a onclick="Document.editRemark(' . $row->sms_notif_id . ')" title="Edit Remark" class="btn-action glyphicons notes_2 btn-info" ><i></i></a>
                    <a onclick="Document.deleteData(' . $row->sms_notif_id . ',\' '.$row->menu_id.' - '.$row->template_name.' \')" title="Delete" class="btn-action glyphicons remove_2 btn-danger" ><i></i></a>
                    ';
            array_push($items, $row);
        }
        $result["rows"] = $items;
        echo json_encode($result);
    }

    public function addeditdata($id) {

        $datas = $this->mnotif_sms->get_template_detail($id);

        if ($datas == false) {
            $this->load->view('sms/form_template', '', FALSE);
            return;
        }

        $data["id"] = $id;
        $data['content'] = $datas['content'];
        $data['parameter'] = $datas['parameter'];
        $recipients = $datas['recipient'];

        $user = array();
        $data['single'] = '';
        $data['division'] = '';
        
        if (isset($recipients['single'])) {
            foreach ($recipients['single'] as $row) {
                $user[] = $row['name'];
            }
            $data['single'] = implode(',', $user);
        }
        if (isset($recipients['division'])) {
            foreach ($recipients['division'] as $row) {
                $data['division'].="DIV:" . $row['name'] . ',';
            }
        }

        //header footer
        $q = $this->db->get('t_ref_sms_notif_config');
        foreach ($q->result() as $row) {
            $data[$row->notif_key] = $row->notif_value;
        }
        
        $this->load->view('sms/form_template', $data, FALSE);
    }

    public function addeditremark($id) { //added by andedi 20141017
        $notif      = $this->mnotif_sms->get_template_remark($id);
        $remark     = '';
        $notif_id   = '';
        $sms_status= '';
        $rec_to     = array();
        foreach ($notif as $row) {
            $remark         = $row->action_desc;
            $notif_id       = $row->sms_notif_id;
            $sms_status    = $row->sms_status;
            $rec_to[] = array('user_id'=>$row->user_id, 'is_active'=>$row->is_active);
        }
        $data['notif_id']   = $notif_id;
        $data['notif']      = $notif;
        $data['remark']     = $remark;
        $data['sms_status'] = $sms_status;
        $data['rec_to']     = $rec_to;
        $this->load->view('sms/form_remark', $data, FALSE);
    }

    public function set_template($param = "") {
        $data = "";
        if ($param == "save") {
            $d = $this->input->post();
            foreach ($d as $key => $value) {
                $rows = $this->db->get_where('t_ref_sms_notif_config', array("notif_key" => $key));
                if ($rows->num_rows() > 0) {
                    $q = $this
                            ->mglobal
                            ->setData("t_ref_sms_notif_config", array("notif_key" => $key, "notif_value" => $value), array("notif_key" => $key));
                } else {
                    $q = $this
                            ->mglobal
                            ->setData("t_ref_sms_notif_config", array("notif_key" => $key, "notif_value" => $value));
                }
            }
            if ($q) {
                echo json_encode(array(
                    'status' => 'OK',
                    'hasil' => 'Save success!'
                ));
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'hasil' => 'Unable to save data!'
                ));
            }
            return;
        }
        $q = $this->db->get('t_ref_sms_notif_config');
        foreach ($q->result() as $row) {
            $data[$row->notif_key] = $row->notif_value;
        }
        $this->load->view('sms/form_header', $data, FALSE);
    }

    public function saveData() {
        $d = $this->input->post();
        $val = array("id", "to", "content");
        $hasil = "";

        foreach ($val as $value) {
            if ($d[$value] == "")
                $hasil.=" - Field $value required" . "\r\n";
        }

        if ($hasil != "") {
            $hasil = "Error found :" . "\r\n" . $hasil;
            echo json_encode(array(
                'status' => 'error',
                'hasil' => $hasil
            ));
        } else {
            $q = $this
                    ->mglobal
                    ->setData("t_ref_sms_notif", array("content" => $d["content"]), array("id" => $d["id"]));

            $this->db->where(array("sms_notif_id" => $d["id"]));
            $this->db->delete('t_ref_sms_notif_recipient_user');

            $this->db->where(array("sms_notif_id" => $d["id"]));
            $this->db->delete('t_ref_sms_notif_recipient_division');

            $mail_types = array("to");
            foreach ($mail_types as $types) {

                if ($d[$types] != "") {
                    $recipients = explode(",", $d[$types]);
                    foreach ($recipients as $value) {
                        if (strlen($value) > 1) {
                            if (strstr($value, "DIV:")) {
                                $div_name = explode("DIV:", $value);
                                $div_name = $div_name[1];

                                $q = $this->db->get_where('t_mtr_user_division', array("user_div_name" => $div_name));
                                if ($q->num_rows() > 0) {
                                    $q = $this
                                            ->mglobal
                                            ->setData("t_ref_sms_notif_recipient_division", array("is_active" => 1, "sms_notif_id" => $d["id"], 
                                        "user_div_id" => $q->row()->user_div_id));
                                }
                            } else {
                                $q = $this
                                        ->mglobal
                                        ->setData("t_ref_sms_notif_recipient_user", array("is_active" => 1, "sms_notif_id" => $d["id"], "user_id" => $value));
                            }
                        }
                    }
                }
            }
            if ($q) {
                echo json_encode(array(
                    'status' => 'OK',
                    'hasil' => 'Save success!'
                ));
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'hasil' => 'Unable to save data!'
                ));
            }
        }
    }

    public function saveRemark() { //added by andedi 20141016
        $this->db->trans_start();
        $result             = '';
        $data['notif_id']   = $this->input->post('id');
        $data['remark']     = $this->input->post('remark');
        $data['sms_status'] = isset($_POST['sms_status'])?'1':'-1';
        $this->mnotif_sms->update_desc($data);
        $this->mnotif_sms->clear_user_status($data['notif_id']);
        
        if (isset($_POST['user_id'])) {
            foreach ($_POST['user_id'] as $user) {
                $this->mnotif_sms->update_user_status($data['notif_id'], $user);
            }
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            $result = ' Update Success ';
            $status = 'OK';
        } else {
            $this->db->trans_rollback();
            $result = ' Update Failed ';
            $status = 'Error';
        }
        
        echo json_encode(array('result'=>$result, 'status'=>$status));
    }

    ########################################################################
    //Manag.GroupRole
    // public function GroupRole( $group_id="" ){
    // $data['judul'] = "Group Role";
    // $data['content_id'] = "handset/f-group-role";
    // $data['group'] = $this->mnotif_email->getGroup();
    // $data['group_id'] = $group_id;
    // $this->load->view("template/smashing", $data);
    // }
    // public function saveGroupRole(){
    // $this->mnotif_email->saveGroupRole();
    // }
    ########################################################################
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */