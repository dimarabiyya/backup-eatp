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
		$psql = "select mr.*,concat(mu.user_name,' (',mr.pic_id,')') as pic from t_mtr_region mr 
			    left join t_mtr_user mu on mr.pic_id=mu.user_id order by region_id";
				
		$query = $this->db->query($psql);
		//$query = $this->db->get('t_mtr_region');
		
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
		$this->db->order_by('ceklis_id', 'ASC');
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
	
	public function get_pm_ceklis_all()
	{
		$this->db->select('t_mtr_pm_checklist.checklist_id, t_mtr_pm_checklist.descr, t_mtr_pm_checklist.urutan, t_mtr_pm_checklist_group.name as namagroup,t_mtr_pm_checklist_group.id as group_id');
		$this->db->join('t_mtr_pm_checklist_group', 't_mtr_pm_checklist_group.id = t_mtr_pm_checklist.group_id', 'left');
		$this->db->order_by('checklist_id', 'ASC');
		$query = $this->db->get('t_mtr_pm_checklist');
				
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_pm_ceklis_property($id){
	$query = $this->db->query('
								select b.checklist_id, b.descr, a.*, c.name
								from t_mtr_pm_checklist_property_option a
								LEFT JOIN t_mtr_pm_checklist b on a.checklist_id=b.checklist_id
								LEFT JOIN t_m_ceklis_property_form c on c.property_id=b.property_id
								where  b.checklist_id = '.$id.'
								order by a.urutan asc
								');
		if ($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	
	}
	
	public function getPartnerStatus() {
		$options = array(
                  	1  => 'Active',
				  	0  => 'Non Active',
                	);

		return $options;			
	}
	
	public function getStatus() {
		$options = array(
                  	1  => 'Active',
				  	0  => 'Non Active',
                	);

		return $options;			
	}
	
	function getCmbCity($parent_id='99999')
	{
		$query = $this->db->query('select lokasi_id, nama from t_mtr_lokasi where parent_id = ' . $parent_id . ' order by nama');
		$dropdowns = $query->result();
	
		$dropDownList[''] = '--select city--';
		
		foreach($dropdowns as $dropdown)
		{
			$dropDownList[$dropdown->lokasi_id] = $dropdown->nama;
		}
		//$dropDownOptions = array('' => '--select city--');
		//$finalDropDown = $dropDownOptions + $dropDownList;
		$finalDropDown = $dropDownList;
		return $finalDropDown;
	}
	
	
	function getDataLokasi($parent_id=0,$InitJudul='')
	{
		$query = $this->db->query('select lokasi_id, nama from t_mtr_lokasi where parent_id = ' . $parent_id . ' order by nama');
		$dropdowns = $query->result();
	
		$dropDownList[''] = '--select ' . $InitJudul. '--';
		foreach($dropdowns as $dropdown)
		{
			$dropDownList[$dropdown->lokasi_id] = $dropdown->nama;
		}
		//$dropDownOptions = array('' => '--select city--');
		//$finalDropDown = $dropDownOptions + $dropDownList;
		$finalDropDown = $dropDownList;
		return $finalDropDown;
	}
	
	function getCmbPartnerType()
	{
		$query = $this->db->query('select lookup_value, lookup_desc from t_mtr_lookup 
								  where lookup_key=\'partner_type\' order by lookup_desc');
		$dropdowns = $query->result();
	
		//$dropDownList[0] = '--select partnerType--';
		foreach($dropdowns as $dropdown)
		{
			$dropDownList[$dropdown->lookup_value] = $dropdown->lookup_desc;
		}
		$dropDownOptions = array('' => '--select partnerType--'	);
		$finalDropDown = $dropDownOptions + $dropDownList;
		//$finalDropDown = $dropDownList;
		return $finalDropDown;
	}
	
	
	function getCmbPIC()
	{
		$query = $this->db->query('select user_id,user_name,istatus from t_mtr_user where istatus=1');
		$dropdowns = $query->result();
	
		//$dropDownList[0] = '--select partnerType--';
		foreach($dropdowns as $dropdown)
		{
			$dropDownList[$dropdown->user_id] = $dropdown->user_name;
		}
		$dropDownOptions = array('' => '--select pic--'	);
		$finalDropDown = $dropDownOptions + $dropDownList;
		//$finalDropDown = $dropDownList;
		return $finalDropDown;
	}
	
	function getCmbCityarsipsaja()
	{
		$query = $this->db->query('select city_id, city_name from t_mtr_city order by city_name');
		$dropdowns = $query->result();
	
		$dropDownList[0] = '--select city--';
		foreach($dropdowns as $dropdown)
		{
			$dropDownList[$dropdown->city_id] = $dropdown->city_name;
		}
		//$dropDownOptions = array('' => 'SELECT', '0' => 'None');
		//$finalDropDown = $dropDownOptions + $dropDownList;
		$finalDropDown = $dropDownList;
		return $finalDropDown;
	}
	/* arsip saja

			// Fill Category Dropdown Box
			$data["cat"] = $this->category_model->fillCombo();
			
			// View
			form_dropdown('Category_id', $cat, $results->Category_id);
			
			// Model
			function fillCombo()
			 {
			  $query = $this->db->query('select distinct Category_id, Category_desc from shopcategories order by Category_desc');
			  $dropdowns = $query->result();
			            foreach($dropdowns as $dropdown)
			            {
			             $dropDownList[$dropdown->Category_id] = $dropdown->Category_desc;
			             }
			   //$dropDownOptions = array('' => 'SELECT', '0' => 'None');
			            //$finalDropDown = $dropDownOptions + $dropDownList;
			    $finalDropDown = $dropDownList;
			             return $finalDropDown;
			 } 
	
	 */
} // end model