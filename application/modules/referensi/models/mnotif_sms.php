<?php

class mnotif_sms extends CI_Model {

    function mnotif_sms() {
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

    public function deleteData($id) {
        $this->db->where(array('id' => $id));
        $query = $this->db->update('t_ref_sms_notif', array('is_active' => 0));
        
        return $query;
    }

    public function load_data($offset = 0, $rows = 0, $data = '') {
        $this->db->select('sn.id AS sms_notif_id, sn.template_name, sn.content, sn.action_desc, sn.parameter, sm.name AS menu_id');
        $this->db->select(" (CASE WHEN ss.template_name!='' THEN 'Reminder' ELSE 'User Action' END) AS notif_type "); //added  by andedi (20141015)
        $this->db->select(" (CASE WHEN sn.is_active=1 THEN 'Enable' ELSE 'Disable' END) AS sms_status "); //added  by andedi (20141030)
        if ($data['q'] != '') {
            $q = $data['q'];
            $or_arr = array('sn.template_name', 'sn.action_desc', 'sm.name');
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

        $this->db->where('sn.is_active <>', 0); //commented by andedi 20141030
        $this->db->order_by('sm.name', 'asc');
        $this->db->order_by('sn.template_name', 'asc'); //added by andedi (20141015)
        $this->db->join('t_mtr_menu sm', 'sn.menu_id = sm.id');
        $this->db->join('t_ref_notif_scheduler ss', 'ss.template_name = sn.template_name', 'left'); //added by andedi (20141015)

        if ($rows == 0)
            $q = $this->db->get(' t_ref_sms_notif sn');
        else
            $q = $this->db->get(' t_ref_sms_notif sn', $rows, $offset);
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
        $q = $this->db->get_where('t_ref_sms_notif', array('id' => $id));
        if ($q->num_rows() == 0)
            return false;
        else {
            $data = $q->row_array();

            $single = $this->db->get_where('t_ref_sms_notif_recipient_user', array('sms_notif_id' => $id));
            foreach ($single->result() as $row) {
                $data['recipient']['single'][] = array('name' => $row->user_id);
            }
            $this->db->select('d.user_div_name');
            $this->db->join('t_mtr_user_division d', 'd.user_div_id = r.user_div_id');
            $div = $this->db->get_where('t_ref_sms_notif_recipient_division r', array('r.sms_notif_id' => $id));
            foreach ($div->result() as $row) {
                $data['recipient']['division'][] = array('name' => $row->user_div_name);
            }
            return $data;
        }
    }

    function get_template_remark($id) { //added by andedi 20141016
        $dblokal = $this->load->database("default", TRUE);
        $sql = "
                          SELECT A.sms_notif_id, A.user_id, template_name, B.action_desc, A.is_active, B.is_active AS \"sms_status\" 
                          FROM t_ref_sms_notif_recipient_user A
                          LEFT JOIN t_ref_sms_notif B ON  B.id = A.sms_notif_id
                          WHERE sms_notif_id = ?
                          ORDER BY A.user_id
                          ";
        $query = $dblokal->query($sql, array($id));
        return $query->result();
    }

    function clear_user_status($notif_id) { //added by andedi 20141016
        $dblokal = $this->load->database("default", TRUE);
        $sql = "UPDATE t_ref_sms_notif_recipient_user SET is_active=0 WHERE sms_notif_id=?";
        $query = $dblokal->query($sql, array($notif_id));
        return $query;
    }

    function update_user_status($notif_id = 0, $user_id = 0) { //added by andedi 20141016
        $dblokal = $this->load->database("default", TRUE);
        $sql = "UPDATE t_ref_sms_notif_recipient_user SET is_active=1 WHERE sms_notif_id=? AND user_id=?";
        $query = $dblokal->query($sql, array($notif_id, $user_id));
        return $query;
    }

    function update_desc($data = null) { //added by andedi 20141016
        $dblokal = $this->load->database("default", TRUE);
        $sql = "UPDATE t_ref_sms_notif SET action_desc=?, is_active=? WHERE id=?";
        $query = $dblokal->query($sql, array(
            $data['remark'],
            $data['sms_status'],
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