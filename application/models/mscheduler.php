<?php

/*
 * mscheduler.php, Mar 12, 2015 5:49:36 PM    
 * 
 * Andedi Tarmadi
 * 
 * 
 * ----------------
 * Tumbas Wiralodra
 * Email : andedi.foss@gmail.com
 * 087829986468
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mscheduler extends CI_Model {

    protected $_user_now;
    protected $_date_now;
    const EXPIRED_UPCOMING= 1;
    const EXPIRED_OVERDUE= 2;

    public function __construct() {
        parent::__construct();
        $this->_user_now  = $this->session->userdata('user_id');
        $this->_date_now = date('Y-m-d H:i:s');
    }
    
    // ---------------- E M A I L -------------------
    
    function get_scheduler($where='') {
        $where .= " AND notif.is_active = 1";
        $query = $this->db
                ->where($where)
                ->join('t_ref_notif_scheduler sch', ' sch.template_name = notif.template_name', 'left')
                ->get('t_ref_mail_notif notif');
        return $query->row();
    }
    
    function update_scheduler($scheduler_id=0, $data=NULL) {
        $data['updated_by'] = 'system';
        $data['updated_on'] = $this->_date_now;
        $query = $this->db
                ->where('id', $scheduler_id)
                ->update('t_ref_notif_scheduler', $data);
        return $query;
    }
    
    function get_tp_company($type='') {
        $where = '';
        if ($type == self::EXPIRED_UPCOMING) {
            $where = 'AND (CURRENT_DATE-C.lease_finish_date)>=-365 AND (CURRENT_DATE-C.lease_finish_date)<=-1';
        } elseif ($type == self::EXPIRED_OVERDUE) {
            $where = 'AND (CURRENT_DATE-C.lease_finish_date) >=1';
        } else { //upcoming & expired
            $where = 'AND (CURRENT_DATE-C.lease_finish_date)>=-365';
        }
        
        $sql = "SELECT DISTINCT D.company_id, E.company_name, E.contact_phone, E.email
                FROM t_mtr_site A
                LEFT JOIN t_mtr_site_l_lahan C ON C.site_id=A.site_id
                JOIN t_mtr_tower_provider D ON D.tp_id=A.tp_id::int
                LEFT JOIN t_mtr_tp_company E ON E.company_id = D.company_id
                WHERE C.lease_finish_date IS NOT NULL {$where}
                AND (lcd_renewal_status IS NULL OR lcd_renewal_status=1) 
                AND E.email IS NOT NULL AND E.contact_phone IS NOT NULL
                ORDER BY D.company_id ASC";
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    function get_tp_company_sites($company_id = 0) {
        $sql = "SELECT B.region_name AS region, site_code AS siteid, vendor_id AS vendorid, site_name AS sitename,
                f_get_tp_company_name(A.tp_id::int) AS tpname, to_char(C.lease_finish_date, 'DD-Mon-YYYY') AS leaseenddate,
                CASE
                WHEN (CURRENT_DATE-C.lease_finish_date)<0 THEN 'Upcoming Expired'
                ELSE 'Overdue'
                END AS expiredstatus,
                CASE
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-1 AND (CURRENT_DATE-C.lease_finish_date)>=-7 THEN '< 1 week'
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-8 AND (CURRENT_DATE-C.lease_finish_date)>=-14 THEN '< 2 weeks'
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-15 AND (CURRENT_DATE-C.lease_finish_date)>=-21 THEN '< 3 weeks'
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-22 AND (CURRENT_DATE-C.lease_finish_date)>=-30 THEN '< 1 month'
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-31 AND (CURRENT_DATE-C.lease_finish_date)>=-90 THEN '< 3 months'
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-91 AND (CURRENT_DATE-C.lease_finish_date)>=-180 THEN '< 6 months'
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-181 AND (CURRENT_DATE-C.lease_finish_date)>=-365 THEN '< 1 year'
                WHEN (CURRENT_DATE-C.lease_finish_date)>=1 AND (CURRENT_DATE-C.lease_finish_date)<=14 THEN '> 1 week'
                WHEN (CURRENT_DATE-C.lease_finish_date)>=15 AND (CURRENT_DATE-C.lease_finish_date)<=21 THEN '> 2 weeks'
                WHEN (CURRENT_DATE-C.lease_finish_date)>=22 AND (CURRENT_DATE-C.lease_finish_date)<=30 THEN '> 3 weeks'
                WHEN (CURRENT_DATE-C.lease_finish_date)>=31 AND (CURRENT_DATE-C.lease_finish_date)<=90 THEN '> 1 month'
                WHEN (CURRENT_DATE-C.lease_finish_date)>=91 AND (CURRENT_DATE-C.lease_finish_date)<=180 THEN '> 3 months'
                WHEN (CURRENT_DATE-C.lease_finish_date)>=181 AND (CURRENT_DATE-C.lease_finish_date)<=365 THEN '> 6 months'
                WHEN (CURRENT_DATE-C.lease_finish_date)>=366 THEN '> 1 year'
                END AS ageingcategory,
                CURRENT_DATE-C.lease_finish_date AS ageingdays
                FROM t_mtr_site A
                LEFT JOIN t_mtr_region B ON B.region_id=A.region_id
                LEFT JOIN t_mtr_site_l_lahan C ON C.site_id=A.site_id
                JOIN t_mtr_tower_provider D ON D.tp_id=A.tp_id::int
                WHERE C.lease_finish_date IS NOT NULL AND (CURRENT_DATE-C.lease_finish_date)>=-365
                AND (lcd_renewal_status IS NULL OR lcd_renewal_status=1) AND D.company_id={$company_id}
                ORDER BY B.order_no ASC, ageingdays DESC";
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    function set_email_notif($subject=NULL, $content=NULL, $recepients=array(), $type=1, $created_by='system', $sender=NULL, $reply_to=NULL, $foreign_id=1) {
        $data_header = array(
            'email_type_id' => $type,
            'email_date' => date('Y-m-d'),
            'email_time' => date('H:i:s'),
            'email_from' => $sender,
            'email_reply_to_address' => $reply_to,
            'email_subject' => $subject,
            'email_content' => $content,
            'foreign_id' => $foreign_id,
            'created_by' => $created_by
        );
        $this->db->insert('t_msg_email_header', $data_header);
        $email_id = $this->db->insert_id();
        
        if (is_array($recepients)) {
            foreach ($recepients as $recepient) {
                $data_detail = array(
                    'email_id' => $email_id,
                    'recipient_type' => $recepient['type'],
                    'recipient_address' => $recepient['email'],
                    'created_by' => $created_by,
                    'recipient_id' => $recepient['id']
                );
                $this->db->insert('t_msg_email_detail', $data_detail);
            }
        }
        
    }
    
    // ---------------- S M S ----------------------- 
    
    function get_sms_scheduler($where='') {
        $where .= " AND notif.is_active = 1";
        $query = $this->db
                ->where($where)
                ->join('t_ref_notif_scheduler sch', ' sch.template_name = notif.template_name', 'left')
                ->get('t_ref_sms_notif notif');
        return $query->row();
    }
    
    function get_tp_company_sites_sms($company_id = 0, $type = 0) {
        $select = '';
        $where = '';
        $order = '';
        if ($type == self::EXPIRED_UPCOMING) {
            $select = "
                CASE
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-1 AND (CURRENT_DATE-C.lease_finish_date)>=-7 THEN -7
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-8 AND (CURRENT_DATE-C.lease_finish_date)>=-14 THEN -14
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-15 AND (CURRENT_DATE-C.lease_finish_date)>=-21 THEN -21
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-22 AND (CURRENT_DATE-C.lease_finish_date)>=-30 THEN -30
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-31 AND (CURRENT_DATE-C.lease_finish_date)>=-90 THEN -90
                WHEN (CURRENT_DATE-C.lease_finish_date)<=-91 AND (CURRENT_DATE-C.lease_finish_date)>=-180 THEN -180
                END AS \"ageingcategory\",";
            $where = "AND (CURRENT_DATE-C.lease_finish_date)>=-180 AND (CURRENT_DATE-C.lease_finish_date) < 1 ";
            $order = ' ASC';
        } elseif ($type == self::EXPIRED_OVERDUE) {
            $select = "CASE
                WHEN (CURRENT_DATE-C.lease_finish_date)>=1 AND (CURRENT_DATE-C.lease_finish_date)<=14 THEN 7
                WHEN (CURRENT_DATE-C.lease_finish_date)>=15 AND (CURRENT_DATE-C.lease_finish_date)<=21 THEN 14
                WHEN (CURRENT_DATE-C.lease_finish_date)>=22 AND (CURRENT_DATE-C.lease_finish_date)<=30 THEN 21
                WHEN (CURRENT_DATE-C.lease_finish_date)>=31 AND (CURRENT_DATE-C.lease_finish_date)<=90 THEN 30
                WHEN (CURRENT_DATE-C.lease_finish_date)>=91 AND (CURRENT_DATE-C.lease_finish_date)<=180 THEN 90
                WHEN (CURRENT_DATE-C.lease_finish_date)>=181 AND (CURRENT_DATE-C.lease_finish_date)<=365 THEN 180
                WHEN (CURRENT_DATE-C.lease_finish_date)>=366 THEN 365
                END AS \"ageingcategory\",";
            $where = " AND (CURRENT_DATE-C.lease_finish_date) >= 1 ";
            $order = ' DESC';
        }
        
        $sql = "SELECT
                {$select}
                count (case when B.region_name='SUMBAGUT' then 'NS' end) as \"NS\",
                count (case when B.region_name='SUMBAGSEL' then 'SS' end) as \"SS\",
                count (case when B.region_name='WEST JAVA' then 'WJ' end) as \"WJ\",
                count (case when B.region_name='JABODETABEK' then 'JBO' end) as \"JBO\",
                count (case when B.region_name='CENTRAL JAVA' then 'CJ' end) as \"CJ\",
                count (case when B.region_name='EAST JAVA' then 'EJ' end) as \"EJ\",
                count (case when B.region_name='SUL-TAN' then 'SULTAN' end) as \"SULTAN\"
                FROM t_mtr_site A
                LEFT JOIN t_mtr_region B
                ON B.region_id=A.region_id
                LEFT JOIN t_mtr_site_l_lahan C ON C.site_id=A.site_id
                JOIN t_mtr_tower_provider D ON D.tp_id=A.tp_id::int
                WHERE  C.lease_finish_date IS NOT NULL {$where}
                AND (lcd_renewal_status IS NULL OR lcd_renewal_status=1)
                AND D.company_id={$company_id}
                GROUP BY ageingcategory
                ORDER BY ageingcategory {$order}";
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    function set_sms_notif($content=NULL, $recepients=array(), $type=1, $created_by='system', $sender=NULL, $reply_to=NULL, $foreign_id=1) {
        $data_header = array(
            'sms_type_id' => $type,
            'sms_date' => date('Y-m-d'),
            'sms_time' => date('H:i:s'),
            'sms_from' => $sender,
            'sms_content' => $content,
            'foreign_id' => $foreign_id,
            'created_by' => $created_by
        );
        $this->db->insert('t_msg_sms_header', $data_header);
        $sms_id = $this->db->insert_id();
        
        if (is_array($recepients)) {
            foreach ($recepients as $recepient) {
                $data_detail = array(
                    'sms_id' => $sms_id,
                    'recipient_phone' => $recepient['phone'],
                    'created_by' => $created_by,
                    'recipient_user_id' => $recepient['id']
                );
                $this->db->insert('t_msg_sms_detail', $data_detail);
            }
        }
        
    }
    
}