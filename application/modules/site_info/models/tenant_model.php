<?php

/*
  Document   : tenant_model
  Created on : May 29, 2015, 2:39:25 PM
  Author     : tumbas
  Description:
  Purpose of the Class follows.
 */

/**
 * Description of tenant_model
 *
 * @author tumbas
 */
class Tenant_model extends CI_Model {
  
  public function get_tenant_list($search, $limit, $offset) {
    $sql = " SELECT * FROM t_mtr_tenant WHERE status=1 AND tenant_name ILIKE '%{$search}%' ORDER BY tenant_name LIMIT {$limit} OFFSET {$offset} ";
    $query = $this->db->query($sql);
    
    return $query->result();
  }
  
  public function get_tenant_count($search) {
    $sql = " SELECT COUNT(tenant_id) AS count FROM t_mtr_tenant WHERE status=1 AND tenant_name ILIKE '%{$search}%' ";
    $query = $this->db->query($sql);
    
    return $query->row()->count;
  }
  
}

?>