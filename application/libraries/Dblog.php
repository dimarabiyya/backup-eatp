<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Helper untuk membuat audit trail
 * yaddis@nutech-integrasi.com
 */

class Dblog
{
  protected   $ci;

  public function __construct()
  {
        $this->ci =& get_instance();
  }

  public function AuditTrail($log_key, $log_data, $interface = "web", $user_id = "")
  {
    // if(!$this->json_is($log_data)) 
      // { }
        $log_data = json_encode($log_data); 

    if(!$user_id) 
      { $user_id  = $this->ci->session->userdata('user_id'); }

    $log = array('log_key' => $log_key, 'log_action' => $log_data, 'interface' => $interface, 'user_id' => $user_id);
    $this->ci->db->insert('t_log', $log);
    if($this->ci->db->affected_rows() > 0) 
      { return array('log_status' => true, 'log_message' => 'log created'); }
    else
      { return array('log_status' => false, 'log_message' => 'log failed'); }

  }

}

/* End of file Dblog.php */
/* Location: ./application/libraries/Dblog.php */
