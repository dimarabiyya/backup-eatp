<?php

class Referensi_model extends CI_Model {
		
	public function get_site_type_all()
	{
		$this->db->order_by('type_id', 'ASC');
		$query = $this->db->get('t_mtr_site_type');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_site_type_rows()
	{
		return $this->db->count_all('mtr_site_type');
	}
	
	function add_site_type($data) 
	{  
		$this->db->insert('mtr_site_type', $data);  
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  
	}  

	 function update_site_type($data, $type_id) 
	 {  
		$this->db->where('type_id', $type_id);
		$this->db->update('mtr_site_type', $data);//array('order_code' => $ordercode)); 
		if($this->db->affected_rows() > 0){  
		   return true;  
		} else {  
		   return false;  
		}  
	 }

	public function get_status_tp_all()
	{
		$this->db->order_by('status_tp_id', 'ASC');
		$query = $this->db->get('t_mtr_status_tp');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_site_specification_all()
	{
		$this->db->order_by('specification_id', 'ASC');
		$query = $this->db->get('t_mtr_site_specification');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_region_all()
	{
		$this->db->order_by('region_id', 'ASC');
		$query = $this->db->get('t_mtr_region');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_shelter_type_all()
	{
		$this->db->order_by('shelter_type_id', 'ASC');
		$query = $this->db->get('t_mtr_shelter_type');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_tipe_perangkat_site_all()
	{
		$this->db->order_by('perangkat_id', 'ASC');
		$query = $this->db->get('t_m_tipe_perangkat_site');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_site_tp_kandidat_all()
	{
		$this->db->order_by('site_id', 'ASC');
		$query = $this->db->get('t_site_tp_kandidat');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_tipe_perangkat_ceklis_group_all()
	{
		$this->db->select('t_m_tipe_perangkat_ceklis_group.group_id, t_m_tipe_perangkat_ceklis_group.nama, t_m_tipe_perangkat_site.nama as namatipe');
		$this->db->join('t_m_tipe_perangkat_site', 't_m_tipe_perangkat_site.perangkat_id = t_m_tipe_perangkat_ceklis_group.tipe_perangkat_id', 'left');
		$this->db->order_by('group_id', 'ASC');
		$query = $this->db->get('t_m_tipe_perangkat_ceklis_group');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_ceklis_property_form_all()
	{
		$this->db->order_by('property_id', 'ASC');
		$query = $this->db->get('t_m_ceklis_property_form');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_perangkat_site_ceklis_all()
	{
		$this->db->order_by('checklist_id', 'ASC');
		$query = $this->db->get('t_m_perangkat_site_ceklis');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_partner_all()
	{
		$this->db->order_by('partner_id', 'ASC');
		$query = $this->db->get('t_mtr_partner');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_pm_checklist_all()
	{
		$this->db->select('t_mtr_pm_checklist.checklist_id, t_mtr_pm_checklist.descr, t_mtr_pm_checklist.order, t_m_tipe_perangkat_ceklis_group.nama as namagroup');
		$this->db->join('t_m_tipe_perangkat_ceklis_group', 't_m_tipe_perangkat_ceklis_group.group_id = t_mtr_pm_checklist.group_id', 'left');
		$this->db->order_by('checklist_id', 'ASC');
		$query = $this->db->get('t_mtr_pm_checklist');
		
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_pm_checklist_property_option($id){
	$query = $this->db->query('
								select a.*, c.name
								from t_mtr_pm_checklist_property_option_option_line a
								left join t_mtr_pm_checklist_property_option b on a.ceklis_property_id=b.ceklis_property_id
								left join t_m_ceklis_property_form c on c.property_id=b.property_id
								where  b.checklist_id = '.$id.'
								order by a.urutan asc
								');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	
	}
}