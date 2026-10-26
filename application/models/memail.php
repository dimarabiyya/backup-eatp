<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Memail extends CI_Model {

    function __construct(){
        parent::__construct();
    }

	public function load_data($offset=0,$rows=0,$data=''){
		$this->db->select('email_to,email_cc,email_subject,created_on');
		if ($data['q'] != ''){
			$q 		=$data['q'];
			$or_arr =array('email_to','email_cc','email_subject','to_char(created_osn, \'DD Mon YYYY\')');
			$or_str ="";$q=strtolower($q);
			foreach ($or_arr as $val) {
				if ($or_str=="")
					$or_str.="(lower($val) like '%$q%'";
				else
					$or_str.=" or lower($val) like '%$q%'";
			}
			$or_str.=")";
			$this->db->where($or_str);
			unset($data['q']);
		}
		if ($rows==0)
			$q = $this->db->get(' t_msg_email_broadcast ');
		else
			$q = $this->db->get(' t_msg_email_broadcast ',$rows,$offset);
        return $q;
    }
}
?>