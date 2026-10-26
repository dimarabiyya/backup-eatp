<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mpm_model extends CI_Model {

 
  public function siteProperties()
  {
    $this->db->select('cc.checklist_id, cc.checklist_group group_id, cc.checklist_name, cc.interface');
    $this->db->join('t_mtr_checklist_group gg', 'gg.checklist_group_id = cc.checklist_group');
    $this->db->where('gg.parent_id !=', 0);
    $this->db->where('gg.group_key', 'prev.maintenance');
    $this->db->where('gg.status', 1);
    $this->db->where('cc.status', 1);
    $this->db->order_by('cc.checklist_group, cc.checklist_group, gg.order_number, cc.order_number');
    $q = $this->db->get('t_mtr_checklist cc');
    $data["item"] = $q->result_array();

    $this->db->select('checklist_group_id group_id, group_name, parent_id');
    $this->db->where('checklist_group_id !=', 1);
    $this->db->where('group_key', 'prev.maintenance');
    $this->db->where('status', 1);
    $this->db->order_by('order_number, parent_id, checklist_group_id');
    $q = $this->db->get('t_mtr_checklist_group');
    $data["group"] = $q->result_array();

    $this->db->select('rr.checklist_id, oo.option_id, oo.option_value, oo.locked');
    $this->db->join('t_mtr_checklist_template rr', 'rr.option_id = oo.option_id');
    $this->db->where('oo.option_key', 'pm_visit_type');
    $this->db->where('oo.status', 1);
    $q = $this->db->get('t_mtr_checklist_options oo');
    $data["options"] = $q->result_array();

    return $data;
  }

}

/* End of file pm.php */
/* Location: ./application/models/pm/pm.php */