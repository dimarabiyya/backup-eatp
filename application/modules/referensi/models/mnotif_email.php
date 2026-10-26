<?php

class mnotif_email extends CI_Model {

    function mnotif_email() {
        parent::__construct();
        //$this->raa->addTooltips($value);
    }

    function general() {
        $this->load->library('session');
        $data = array();
        $username = $this->session->userdata('username');
        $data['webtitle'] = "";
        $data['getMenuSistem'] = "";
        $data['getJudul'] = "";
        return $data;
    }

    function getMenu($id = 0, $group = "") {
        $group = 1; //$this->session->userdata('group');
        $dblokal = $this->load->database("default", TRUE);
        $q = "SELECT mn.\"id\" AS menu_id,mn.name, mn.alias, mn.parent_id, mail.menu_id
			FROM t_mtr_menu mn
			JOIN t_ref_mail_notif mail ON mn.\"id\" = mail.menu_id
			WHERE mn.parent_id = $id 
				AND mn.published = 1 AND mail.is_active = 1";
        $query = $dblokal->query($q);
        return $query->result();
    }

    function getUser($q) {
        $group = 1;
        $dblokal = $this->load->database("default", TRUE);
        $q = "select * FROM t_ref_mail_notif WHERE subject like '$q%' AND action_desc like '$q%' AND template_name like '$q%'";
        $query = $dblokal->query($q);
        echo json_encode($query->result());
    }

    ############################################

    function getMailDetail($id) {
        $dblokal = $this->load->database("default", TRUE);
        $q = "SELECT * FROM t_ref_mail_notif WHERE id = $id ";
        $query = $dblokal->query($q);
        return $query->result();
    }

    public function saveData() {
        $dblokal = $this->load->database("default", TRUE);
        $input = array('id', 'template_name', 'action_desc', 'subject', 'parameter', 'recipient_division', 'recipient_user');
        foreach ($input as $val)
            $$val = $this->input->post($val);

        $data = array('template_name' => $template_name,
            'action_desc' => $action_desc,
            'subject' => $subject,
            'parameter' => $parameter,
            'is_active' => 1
                //'recipient_division' => $recipient_division,
                //'recipient_user' => $recipient_user);
        );
        if ($id == "") {
            $data['created_on'] = date("Y-m-d H:i:s");
            $data['created_by'] = $this->session->userdata('user_id');

            $dblokal->insert('t_ref_mail_notif', $data);
        } else {
            $data['updated_on'] = date("Y-m-d H:i:s");
            $data['updated_by'] = $this->session->userdata('user_id');

            $dblokal->where(array('id' => $id));
            $dblokal->update('t_ref_mail_notif', $data);
        }
    }

    public function deleteData() {
        $dblokal = $this->load->database("default", TRUE);
        $input = array('id', 'template_name', 'action_desc', 'subject', 'parameter' /* ,'recepient_division','recepient_user' */);
        foreach ($input as $val)
            $$val = $this->input->post($val);

        $dblokal->where(array('id' => $id));
        $dblokal->update('t_ref_mail_notif', array('is_active' => 0));
    }

    public function load_data($offset = 0, $rows = 0, $data = '') {
        $this->db->select('mn.id AS mail_notif_id, mn.template_name, mn.content, mn.action_desc, mn.subject, mn.parameter, mm.name AS menu_id');
        $this->db->select(" (CASE WHEN ms.template_name!='' THEN 'Reminder' ELSE 'User Action' END) AS notif_type "); //added  by andedi (20141015)
        $this->db->select(" (CASE WHEN mn.is_active=1 THEN 'Enable' ELSE 'Disable' END) AS mail_status "); //added  by andedi (20141030)
        if ($data['q'] != '') {
            $q = $data['q'];
            $or_arr = array('mn.template_name', 'mn.action_desc', 'mn.subject', 'mm.name');
            $or_str = "";
            $q = strtolower($q);
            foreach ($or_arr as $val) {
                if ($or_str == "")
                    $or_str.="(lower($val) like '%$q%'";
                else
                    $or_str.=" or lower($val) like '%$q%'";
            }
            $or_str.=")";
            $this->db->where($or_str);
            unset($data['q']);
        }

        //$this->db->where('mn.is_active <>', 0); //commented by andedi 20141030
        $this->db->order_by('mm.name', 'asc');
        $this->db->order_by('mn.template_name', 'asc'); //added by andedi (20141015)
        $this->db->join('t_mtr_menu mm', 'mn.menu_id = mm.id');
        $this->db->join('t_ref_notif_scheduler ms', 'ms.template_name = mn.template_name', 'left'); //added by andedi (20141015)

        if ($rows == 0)
            $q = $this->db->get(' t_ref_mail_notif mn');
        else
            $q = $this->db->get(' t_ref_mail_notif mn', $rows, $offset);
//            die($this->db->last_query());
        return $q;
    }

    ############################################

    function getIdSistem() {
        $dblokal = $this->load->database("default", TRUE);
        $hasil = 0;
        $sistem = $this->uri->segment(1);

        if ($sistem == "")
            $sistem = "main";
        #$nmsistem = explode("_",$url);
        #$sistem = $nmsistem[0];
        $sql = "SELECT * FROM t_mtr_handset WHERE alias = '" . $sistem . "' AND parent_id=0 ";
        $query = $dblokal->query($sql);
        $r = $query->result();
        if (count($r) > 0) {
            foreach ($r as $d) {
                $hasil = $d->menu_id;
            }
        }
        return $hasil;
    }

    function getGridMenu($parent_id, $group_id = '') {
        $dblokal = $this->load->database("default", TRUE);
        // return $this->db->query("SELECT * from t_mtr_handset where parent_id = $parent_id")->result();
        if ($group_id == '') {
            $group_id = 0;
        }
        $sql = "select mn.id, mn.name as menu_name,rl.*  from t_mtr_handset mn
			left join t_mtr_user_role rl on mn.id = rl.menu_id and rl.user_group_id = $group_id
			LEFT JOIN t_mtr_user_group grp on grp.user_group_id = rl.user_group_id 
			where mn.parent_id = $parent_id and mn.published = 1
			order by mn.ordering";

        return $dblokal->query($sql)->result();
    }

    function get_template_detail($id) {
        
        $q = $this->db->get_where('t_ref_mail_notif', array('id' => $id));
        if ($q->num_rows() == 0)
            return false;
        else {
            $data = $q->row_array();
            $data['recipient'] = array();
            
            $single = $this->db->get_where('t_ref_mail_notif_recipient_user', array('mail_notif_id' => $id, 'is_active' => 1));
            foreach ($single->result() as $row) {
                $data['recipient']['single'][] = array('recipient_type' => $row->recipient_type, 'name' => $row->user_id);
            }
            $this->db->select('d.user_div_name');
            $this->db->join('t_mtr_user_division d', 'd.user_div_id = r.user_div_id');
            $div = $this->db->get_where('t_ref_mail_notif_recipient_division r', array('r.mail_notif_id' => $id, 'r.is_active' => 1));
            foreach ($div->result() as $row) {
                $data['recipient']['division'][] = array('recipient_type' => @$row->recipient_type, 'name' => $row->user_div_name);
            }
            return $data;
        }
    }

    function get_template_remark($id) { //added by andedi 20141016
        $dblokal = $this->load->database("default", TRUE);
        $sql = "
                          SELECT B.id mail_notif_id, A.user_id, template_name, B.action_desc, A.is_active, A.recipient_type, B.is_active AS mail_status
                          FROM t_ref_mail_notif B 
                          LEFT JOIN t_ref_mail_notif_recipient_user A ON  B.id = A.mail_notif_id
                          WHERE B.id = ?
                          ORDER BY A.user_id
                          ";
        $query = $dblokal->query($sql, array($id));
        return $query->result();
    }

    function clear_user_status($notif_id) { //added by andedi 20141016
        $dblokal = $this->load->database("default", TRUE);
        $sql = "UPDATE t_ref_mail_notif_recipient_user SET is_active=0 WHERE mail_notif_id=?";
        $query = $dblokal->query($sql, array($notif_id));
        return $query;
    }

    function update_user_status($notif_id = 0, $user_id = 0) { //added by andedi 20141016
        $dblokal = $this->load->database("default", TRUE);
        $sql = "UPDATE t_ref_mail_notif_recipient_user SET is_active=1 WHERE mail_notif_id=? AND user_id=?";
        $query = $dblokal->query($sql, array($notif_id, $user_id));
        return $query;
    }

    function update_desc($data = null) { //added by andedi 20141016
        $dblokal = $this->load->database("default", TRUE);
        $sql = "UPDATE t_ref_mail_notif SET action_desc=?, is_active=? WHERE id=?";
        $query = $dblokal->query($sql, array(
            $data['remark'],
            $data['mail_status'],
            $data['notif_id']
        ));
        return $query;
    }

    function getMenuId($sistem = '') {
        $dblokal = $this->load->database("default", TRUE);
        $hasil = "";
        $sql = "SELECT * FROM t_mtr_handset WHERE menu_path = '" . $sistem . "' and not parent_id=0 ";
        $query = $dblokal->query($sql);
        $r = $query->result();
        if (count($r) > 0) {
            foreach ($r as $d) {
                $hasil = $d->menu_code;
            }
        } else {
            $hasil = '';
        }
        return $hasil;
    }

}

?>