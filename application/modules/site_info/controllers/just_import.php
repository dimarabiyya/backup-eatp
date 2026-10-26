<?php

class Just_import extends MX_Controller {
	
	private $_upload_dir;
	
	public function __construct()
	{
		$this->_upload_dir = FCPATH.'uploads';
	}
	
	public function index()
	{}
	
	public function site2()
	{
		$sql = "
			SELECT gen.*, reg.region_id, lo1.lookup_value AS site_profile2, lo2.lookup_value AS site_type2, lo3.lookup_value AS site_specification2, loc.lokasi_id AS province2,
			loc2.lokasi_id AS city2, lo4.lookup_value AS contract_under2, loc3.lokasi_id AS kecamatan2, tp.company_id AS tp_name
			FROM importer.site_general_info gen
			LEFT JOIN t_mtr_region reg ON UPPER(reg.region_name) = UPPER(gen.region)
			LEFT JOIN t_mtr_lookup lo1 ON UPPER(lo1.lookup_desc) = trim(UPPER(gen.site_profile)) AND lo1.lookup_key = 'site_profile' AND lo1.active = 1
			LEFT JOIN t_mtr_lookup lo2 ON UPPER(lo2.lookup_desc) = trim(UPPER(gen.site_type)) AND lo2.lookup_key = 'site_type' AND lo2.active = 1
			LEFT JOIN t_mtr_lookup lo3 ON UPPER(lo3.lookup_desc) = trim(UPPER(gen.site_specification)) AND lo3.lookup_key = 'site_specification' AND lo3.active = 1
			LEFT JOIN t_mtr_lookup lo4 ON UPPER(lo4.lookup_desc) = UPPER(gen.contract_under) AND lo4.lookup_key = 'company' AND lo4.active = 1
			LEFT JOIN t_mtr_lokasi loc ON UPPER(loc.nama) = UPPER(gen.province) AND loc.jenis = 'P' AND loc.status = 1
			LEFT JOIN t_mtr_lokasi loc2 ON UPPER(loc2.nama) = UPPER(gen.city) AND loc2.jenis = 'KB' AND loc2.status = 1
			LEFT JOIN t_mtr_lokasi loc3 ON UPPER(loc3.nama) = UPPER(gen.kecamatan) AND loc3.jenis = 'KC' AND loc3.status = 1
			LEFT JOIN t_mtr_tp_company tp ON upper(tp.company_name) = trim(upper(gen.tp_name))
		";
		
		$res = $this->db->query($sql)->result_array();
		
		$date = date('Y-m-d H:i:s');
		$by = $this->session->userdata('user_id');
		
		$update=0;
		$insert=0;
		foreach ($res as $r) {
			$data['site_code'] = $r['site_code'];
			$data['site_code_old'] = $r['site_code_old'];
			$data['site_name'] = $r['site_name'];
			$data['vendor_id'] = $r['vendor_id'];
			$data['vendor_name'] = $r['vendor_name'];
			$data['latitude'] = (float) $r['Latitude'];
			$data['longitude'] = (float) $r['Longitude'];
			$data['site_address'] = $r['site_address'];
			$data['site_profile'] = $r['site_profile2'];
			$data['site_specification'] = $r['site_specification2'];
			$data['site_type'] = $r['site_type2'];
			
			$data['tp_name'] = $r['tp_name'];
			$data['tp_name_group'] = $r['tp_name_group'];
			$data['tp_grouping'] = $r['site_category'];
			$data['region_id'] = $r['region_id'];
			$data['kode_pos'] = $r['kode_pos'];
			$data['cost_center'] = $r['cost_center'];
			
			$start_date = @explode('/', $r['lcd_start_date']);
			$lcd_start_date = substr(@$start_date[2],0,4).'-'.@$start_date[0].'-'.@$start_date[1];
			$end_date = @explode('/', $r['lcd_end_date']);
			$lcd_end_date = substr(@$end_date[2],0,4).'-'.@$end_date[0].'-'.@$end_date[1];
			
		//	$data['lcd_start_date'] = $r['lcd_start_date'] != '' ? $lcd_start_date : NULL;
		//	$data['lcd_end_date'] = $r['lcd_end_date'] != '' ? $lcd_end_date : NULL;
			$data['status'] = 1;
			$data['active'] = 1;

			if ( $this->core->view_row('t_mtr_site', array('upper(vendor_id)' => trim(strtoupper($r['vendor_id'])), 'active' => 1)) ) {
				$data['updated_on'] = $date;
				$data['updated_by'] = $by;
				$update++;
				$this->db->update('t_mtr_site', $data, array('vendor_id' => $r['vendor_id']));
			} else {
				$data['created_on'] = $date;
				$data['created_by'] = $by;
				$data['updated_on'] = $date;
				$data['updated_by'] = $by;
				$insert++;
				$this->db->insert('t_mtr_site', $data);
			}
		}
		echo "Update: $update<br>Insert:$insert";
	}
	
	public function site()
	{
		$file = $this->_upload_dir.'/sites.xlsx';
		
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		
		$objPHPExcel = new PHPExcel();
		
		try {
			$objReader = IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load($file);
		} catch (Exception $e) {
			$objReader = IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($file);
		}
		
		$date = date('Y-m-d H:i:s');
		$by = $this->session->userdata('user_id');
		
		$rows = array();
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$i = 0;
			foreach ($worksheet->getRowIterator() as $row) {
				if ($i != 0) {
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
					$j = 0;
					foreach ($cellIterator as $cell) {
						if ( ! is_null($cell)) {
							//$rows[$i][$j]=$cell->getFormattedValue();
							//$rows[$i][$j] = $cell->getValue();
							
							if ($j == 0) $data['site_code'] = $cell->getValue();
							if ($j == 1) $data['site_code_old'] = $cell->getValue();
							if ($j == 2) $data['site_name'] = $cell->getValue();
							if ($j == 3) $data['vendor_id'] = $cell->getValue();
							if ($j == 4) $data['vendor_name'] = $cell->getValue();
							
							if ($j == 0 && $this->core->view_row('t_mtr_site2', array('vendor_id' => trim($cell->getValue()))) ) {
								$data['updated_on'] = $date;
								$data['updated_by'] = $by;
								
								//$this->db->update('t_mtr_site2', $data, array('vendor_id' => trim($cell->getValue())));
							} else {
								$data['created_on'] = $date;
								$data['created_by'] = $by;
								$data['updated_on'] = $date;
								$data['updated_by'] = $by;
								
								//$this->db->insert('t_mtr_site2', $data);
							}
							
						} else {
							$rows[$i][$j] = 0;
						}
						$j++;
					} if ($i == 1) return;
				}
				$i++;
			}
		}
		print_rr($row);exit;
	}
	
	public function site3()
	{
		$sql = "
			SELECT
				site.site_id,
				gen.vendor_id,
				(CASE upper(gen.site_status)
					WHEN 'ACTIVE' THEN 1
					WHEN 'NOM' THEN 0
				END) AS site_status,
				lo1.lookup_value AS site_class_transm,
				lo2.lookup_value AS site_last_status,
				gen.onair_date,
				gen.offair_date,
				gen.site_last_status_remarks
			FROM importer.site_general_info gen
			JOIN t_mtr_site site ON upper(site.vendor_id) = trim(upper(gen.vendor_id)) AND site.active = 1
			LEFT JOIN t_mtr_lookup lo1 ON trim(upper(lo1.lookup_desc)) = trim(upper(gen.site_class_trans)) AND lo1.lookup_key = 'site_class_transm' AND lo1.active = 1
			LEFT JOIN t_mtr_lookup lo2 ON trim(upper(lo2.lookup_desc)) = trim(upper(gen.site_last_status)) AND lo2.lookup_key = 'site_last_status' AND lo2.active = 1
		";
		
		$results = $this->db->query($sql)->result_array();
		
		$date = date('Y-m-d H:i:s');
		$by = $this->session->userdata('user_id');
		
		$updated = 0;
		foreach ($results as $res) {
			$data['status'] = $res['site_status'];
			$data['site_class_transm'] = $res['site_class_transm'];
			
			$this->db->update('t_mtr_site', $data, array('site_id' => $res['site_id']));
			unset($data);
			
			// t_mtr_site_l_last_status
			$this->db->delete('t_mtr_site_l_last_status', array('site_id' => $res['site_id']));
			
			if ($res['site_last_status'] == 1) {
				$data['site_id'] = $res['site_id'];
				
				$date_status = NULL;
				if (trim($res['onair_date']) != '') {
					$dateSt = explode('/', $res['onair_date']);
					$date_status = $dateSt[2].'-'.$dateSt[0].'-'.$dateSt[1];
				}
				
				$data['date_status'] = $date_status;
				$data['status'] = 1;
				$data['remarks'] = $res['site_last_status_remarks'];
				$data['active'] = 1;
				$data['created_on'] = $date;
				$data['created_by'] = $by;
				
				$this->db->insert('t_mtr_site_l_last_status', $data);
				unset($data);
			}
			
			// off air
			if ($res['site_last_status'] == 2 && trim($res['offair_date']) != '') {
				$data['site_id'] = $res['site_id'];
				
				$date_status = NULL;
				if (trim($res['offair_date']) != '') {
					$dateSt = explode('/', $res['offair_date']);
					$date_status = $dateSt[2].'-'.$dateSt[0].'-'.$dateSt[1];
				}
				
				$data['date_status'] = $date_status;
				$data['status'] = 2;
				$data['remarks'] = $res['site_last_status_remarks'];
				$data['active'] = 1;
				$data['created_on'] = $date;
				$data['created_by'] = $by;
				
				$this->db->insert('t_mtr_site_l_last_status', $data);
				unset($data);
			}
			
			$updated++;
		}
		
		echo 'Updated: '.$updated;
	}
	
	public function site_cme()
	{
		$sql = "
SELECT
	cme.vendor_id,
	site.site_id,
	
	/* Tower */
	lo1.lookup_value AS tower_ownership,
	cme.name_of_tower_owner,
	cme.phone_of_tower_owner,
	lo2.lookup_value AS tower_type,
	lo3.lookup_value AS tower_leg_no,
	cme.tower_height,
	cme.tower_fabricator,
	
	/* Shelter */
	CASE cme.shelter_needed WHEN 'Yes' THEN 1 WHEN 'No' THEN 0 END AS shelter_needed,
	lo4.lookup_value AS shelter_ownership,
	cme.name_of_shelter_owner,
	cme.phone_of_shelter_owner,
	lo5.lookup_value AS shelter_type,
	cme.shelter_size_l_w_h,
	cme.shelter_fabricator,
	
	/* PLN */
	cme.handover_project,
	cme.customer_id,
	cme.customer_name,
	cme.pln_connected_date,
	cme.pln_disconnected_date,
	cme.nama_upj_pln,
	cme.wo_dismantle_date,
	CASE cme.trafo_blast WHEN 'Yes' THEN 1 WHEN 'No' THEN 0 END AS trafo_blast,
	lo6.lookup_value AS trafo_capacity,
	lo7.lookup_value AS trafo_maintened_by,
	lo8.lookup_value AS kwh_type,
	lo9.lookup_value AS pln_phase,
	cme.pln_capacity,
	lo10.lookup_value AS tariff_type,
	lo11.lookup_value AS tariff_name,
	lo12.lookup_value AS payment_method,
	lo13.lookup_value AS contract_under,
	cme.company_code,
	cme.cost_center,

	/* Genset */
	CASE cme.permanent_genset WHEN 'Yes' THEN 1 WHEN 'No' THEN 0 END AS permanent_genset,
	cme.genset_brand,
	cme.genset_capacity,
	lo20.lookup_value AS genset_ownership,
	cme.tank_capacity_daily,
	cme.tank_capacity_monthly,

	/* AC */
	lo14.lookup_value AS ac_brand_1,
	cme.ac_model_1,
	lo15.lookup_value AS ac_capacity_1,
	cme.ac_serial_number_1,
	lo16.lookup_value AS ac_brand_2,
	cme.ac_model_2,
	lo17.lookup_value AS ac_capacity_2,
	cme.ac_serial_number_2,
	lo18.lookup_value AS ac_brand_3,
	cme.ac_model_3,
	lo19.lookup_value AS ac_capacity_3,
	cme.ac_serial_number_3
	
FROM importer.site_cme cme
JOIN t_mtr_site site ON upper(trim(site.vendor_id)) = upper(trim(cme.vendor_id)) AND site.active = 1
LEFT JOIN t_mtr_lookup lo1 ON upper(lo1.lookup_desc)  = upper(cme.tower_ownership) AND lo1.lookup_key = 'tower_ownership' AND lo1.active = 1
LEFT JOIN t_mtr_lookup lo2 ON upper(lo2.lookup_desc)  = upper(cme.tower_type) AND lo2.lookup_key = 'tower_type' AND lo2.active = 1
LEFT JOIN t_mtr_lookup lo3 ON upper(lo3.lookup_desc)  = upper(cme.tower_leg_no) AND lo3.lookup_key = 'tower_leg_number' AND lo3.active = 1
LEFT JOIN t_mtr_lookup lo4 ON upper(lo4.lookup_desc)  = upper(cme.shelter_ownership) AND lo4.lookup_key = 'shelter_ownership' AND lo4.active = 1
LEFT JOIN t_mtr_lookup lo5 ON upper(lo5.lookup_desc)  = upper(cme.shelter_type) AND lo5.lookup_key = 'shelter_type' AND lo5.active = 1
LEFT JOIN t_mtr_lookup lo6 ON upper(lo6.lookup_desc)  = upper(cme.trafo_capacity) AND lo6.lookup_key = 'pln_trafo_capacity' AND lo6.active = 1
LEFT JOIN t_mtr_lookup lo7 ON upper(lo7.lookup_desc)  = upper(cme.trafo_maintened_by) AND lo7.lookup_key = 'company' AND lo7.active = 1
LEFT JOIN t_mtr_lookup lo8 ON upper(lo8.lookup_desc)  = upper(cme.kwh_type) AND lo8.lookup_key = 'pln_kwh_type' AND lo8.active = 1
LEFT JOIN t_mtr_lookup lo9 ON upper(lo9.lookup_desc)  = upper(cme.pln_phase) AND lo9.lookup_key = 'pln_phase' AND lo9.active = 1
LEFT JOIN t_mtr_lookup lo10 ON upper(lo10.lookup_desc)  = upper(cme.tariff_type) AND lo10.lookup_key = 'tariff_type' AND lo10.active = 1
LEFT JOIN t_mtr_lookup lo11 ON upper(lo11.lookup_desc)  = upper(cme.tariff_name) AND lo11.lookup_key = 'pln_tariff_name' AND lo11.active = 1
LEFT JOIN t_mtr_lookup lo12 ON upper(lo12.lookup_desc)  = upper(cme.payment_method) AND lo12.lookup_key = 'payment_method' AND lo12.active = 1
LEFT JOIN t_mtr_lookup lo13 ON upper(lo13.lookup_desc)  = upper(cme.contract_under) AND lo13.lookup_key = 'contract_under' AND lo13.active = 1
LEFT JOIN t_mtr_lookup lo14 ON upper(lo14.lookup_desc)  = upper(cme.ac_brand_1) AND lo14.lookup_key = 'ac_brand' AND lo14.active = 1
LEFT JOIN t_mtr_lookup lo15 ON upper(lo15.lookup_desc)  = upper(cme.ac_capacity_1) AND lo15.lookup_key = 'ac_capacity' AND lo15.active = 1
LEFT JOIN t_mtr_lookup lo16 ON upper(lo16.lookup_desc)  = upper(cme.ac_brand_2) AND lo16.lookup_key = 'ac_brand' AND lo16.active = 1
LEFT JOIN t_mtr_lookup lo17 ON upper(lo17.lookup_desc)  = upper(cme.ac_capacity_2) AND lo17.lookup_key = 'ac_capacity' AND lo17.active = 1
LEFT JOIN t_mtr_lookup lo18 ON upper(lo18.lookup_desc)  = upper(cme.ac_brand_3) AND lo18.lookup_key = 'ac_brand' AND lo18.active = 1
LEFT JOIN t_mtr_lookup lo19 ON upper(lo19.lookup_desc)  = upper(cme.ac_capacity_3) AND lo19.lookup_key = 'ac_capacity' AND lo19.active = 1
LEFT JOIN t_mtr_lookup lo20 ON upper(lo20.lookup_desc)  = upper(cme.genset_ownership) AND lo20.lookup_key = 'genset_ownership' AND lo20.active = 1
		";
		
		$res = $this->db->query($sql)->result_array();
		
		$date = date('Y-m-d H:i:s');
		$by = $this->session->userdata('user_id');
		
		$updated_tower = 0;
		$inserted_tower = 0;
		$updated_shelter = 0;
		$inserted_shelter = 0;
		$updated_pln = 0;
		$inserted_pln = 0;
		$updated_ac = 0;
		$inserted_ac = 0;
		foreach ($res as $r) {
			// Tower
			if ($this->core->view_row('t_mtr_site_l_tower', array('site_id' => $r['site_id'], 'active' => 1))) {
				//$data['site_id'] = $r['site_id'];
				$data['kepemilikan_tower'] = $r['tower_ownership'];
				$data['nama_pemilik_tower'] = $r['name_of_tower_owner'];
				$data['no_telp_pemilik_tower'] = $r['phone_of_tower_owner'];
				$data['tower_type'] = $r['tower_type'];
				$data['tower_leg_no'] = $r['tower_leg_no'];
				$data['tower_height'] = (float) $r['tower_height'];
				$data['tower_fabricator'] = $r['tower_fabricator'];
				$data['active'] = 1;
				$data['updated_date'] = $date;
				$data['updated_by'] = $by;
				
				$this->db->update('t_mtr_site_l_tower', $data, array('site_id' => $r['site_id'], 'active' => 1));
				unset($data);
				
				$updated_tower++;
			} else {
				$data['site_id'] = $r['site_id'];
				$data['kepemilikan_tower'] = $r['tower_ownership'];
				$data['nama_pemilik_tower'] = $r['name_of_tower_owner'];
				$data['no_telp_pemilik_tower'] = $r['phone_of_tower_owner'];
				$data['tower_type'] = $r['tower_type'];
				$data['tower_leg_no'] = $r['tower_leg_no'];
				$data['tower_height'] = (float) $r['tower_height'];
				$data['tower_fabricator'] = $r['tower_fabricator'];
				$data['active'] = 1;
				$data['created_date'] = $date;
				$data['created_by'] = $by;
				$data['updated_date'] = $date;
				$data['updated_by'] = $by;
				
				$this->db->insert('t_mtr_site_l_tower', $data);
				unset($data);
				
				$inserted_tower++;
			}
			
			// Shelter
			if ($this->core->view_row('t_mtr_site_l_shelter', array('site_id' => $r['site_id'], 'active' => 1))) {
				//$data['site_id'] = $r['site_id'];
				$data['kepemilikan_shelter'] = $r['shelter_ownership'];
				$data['nama_pemilik_shelter'] = $r['name_of_shelter_owner'];
				$data['no_telp_pemilik_shelter'] = $r['phone_of_shelter_owner'];
				$data['shelter_type'] = $r['shelter_type'];
				$data['shelter_size'] = $r['shelter_size_l_w_h'];
				$data['shelter_fabricator'] = $r['shelter_fabricator'];
				$data['shelter_needed'] = $r['shelter_needed'];
				$data['active'] = 1;
				$data['updated_date'] = $date;
				$data['updated_by'] = $by;
				
				$this->db->update('t_mtr_site_l_shelter', $data, array('site_id' => $r['site_id'], 'active' => 1));
				unset($data);
				
				$updated_shelter++;
			} else {
				$data['site_id'] = $r['site_id'];
				$data['kepemilikan_shelter'] = $r['shelter_ownership'];
				$data['nama_pemilik_shelter'] = $r['name_of_shelter_owner'];
				$data['no_telp_pemilik_shelter'] = $r['phone_of_shelter_owner'];
				$data['shelter_type'] = $r['shelter_type'];
				$data['shelter_size'] = $r['shelter_size_l_w_h'];
				$data['shelter_fabricator'] = $r['shelter_fabricator'];
				$data['shelter_needed'] = $r['shelter_needed'];
				$data['active'] = 1;
				$data['created_date'] = $date;
				$data['created_by'] = $by;
				$data['updated_date'] = $date;
				$data['updated_by'] = $by;
				
				$this->db->insert('t_mtr_site_l_shelter', $data);
				unset($data);
				
				$inserted_shelter++;
			}
			
			// PLN
			if ($this->core->view_row('t_mtr_site_l_pln', array('site_id' => $r['site_id'], 'active' => 1))) {
				$data['handover_project'] = null_empty($r['handover_project']);
				$data['id_pelanggan_pln'] = $r['customer_id'];
				$data['atas_nama_pelanggan'] = $r['customer_name'];
				
				$pln_connected_date = NULL;
				if (trim($r['pln_connected_date']) != '') {
					$_pln_connected_date = explode('/', $r['pln_connected_date']);
					$pln_connected_date = $_pln_connected_date[2].'-'.$_pln_connected_date[0].'-'.$_pln_connected_date[1];
				}
				$data['pln_connected_date'] = $pln_connected_date;
				$data['nama_upj_pln'] = $r['nama_upj_pln'];
				$data['wo_dismantle_date'] = null_empty($r['wo_dismantle_date']);
				$data['pln_disconnect_date'] = null_empty($r['pln_disconnected_date']);
				$data['trafo_blast'] = $r['trafo_blast'];
				$data['trafo_capacity'] = $r['trafo_capacity'];
				$data['trafo_maintained_by'] = $r['trafo_maintened_by'];
				$data['kwh_type'] = $r['kwh_type'];
				$data['pln_capacity'] = $r['pln_capacity'];
				$data['pln_phase'] = $r['pln_phase'];
				$data['tarif_type'] = $r['tariff_type'];
				$data['tarif_name'] = $r['tariff_name'];
				$data['cara_pembayaran'] = $r['payment_method'];
				$data['contract_under'] = $r['contract_under'];
				$data['company_code'] = $r['company_code'];
				$data['cost_center'] = $r['cost_center'];
				
				// Genset
				$data['permanent_genset'] = $r['permanent_genset'];
				$data['genset_brand'] = $r['genset_brand'];
				$data['genset_capacity'] = str_replace('KVA', '', $r['genset_capacity']);
				$data['genset_ownership'] = $r['genset_ownership'];
				$data['tank_capacity_daily'] = (float) $r['tank_capacity_daily'];
				$data['tank_capacity_monthly'] = (float) $r['tank_capacity_monthly'];
				
				$data['active'] = 1;
				$data['updated_date'] = $date;
				$data['updated_by'] = $by;
				
				$this->db->update('t_mtr_site_l_pln', $data, array('site_id' => $r['site_id'], 'active' => 1));
				unset($data);
				
				$updated_pln++;
			} else {
				$data['site_id'] = $r['site_id'];
				$data['handover_project'] = null_empty($r['handover_project']);
				$data['id_pelanggan_pln'] = $r['customer_id'];
				$data['atas_nama_pelanggan'] = $r['customer_name'];
				
				$pln_connected_date = NULL;
				if (trim($r['pln_connected_date']) != '') {
					$_pln_connected_date = explode('/', $r['pln_connected_date']);
					$pln_connected_date = $_pln_connected_date[2].'-'.$_pln_connected_date[0].'-'.$_pln_connected_date[1];
				}
				$data['pln_connected_date'] = $pln_connected_date;
				$data['nama_upj_pln'] = $r['nama_upj_pln'];
				$data['wo_dismantle_date'] = null_empty($r['wo_dismantle_date']);
				$data['pln_disconnect_date'] = null_empty($r['pln_disconnected_date']);
				$data['trafo_blast'] = $r['trafo_blast'];
				$data['trafo_capacity'] = $r['trafo_capacity'];
				$data['trafo_maintained_by'] = $r['trafo_maintened_by'];
				$data['kwh_type'] = $r['kwh_type'];
				$data['pln_capacity'] = $r['pln_capacity'];
				$data['pln_phase'] = $r['pln_phase'];
				$data['tarif_type'] = $r['tariff_type'];
				$data['tarif_name'] = $r['tariff_name'];
				$data['cara_pembayaran'] = $r['payment_method'];
				$data['contract_under'] = $r['contract_under'];
				$data['company_code'] = $r['company_code'];
				$data['cost_center'] = $r['cost_center'];
				
				// Genset
				$data['permanent_genset'] = $r['permanent_genset'];
				$data['genset_brand'] = $r['genset_brand'];
				$data['genset_capacity'] = str_replace('KVA', '', $r['genset_capacity']);
				$data['genset_ownership'] = $r['genset_ownership'];
				$data['tank_capacity_daily'] = (float) $r['tank_capacity_daily'];
				$data['tank_capacity_monthly'] = (float) $r['tank_capacity_monthly'];
				
				$data['active'] = 1;
				$data['created_date'] = $date;
				$data['created_by'] = $by;
				$data['updated_date'] = $date;
				$data['updated_by'] = $by;
				
				$this->db->insert('t_mtr_site_l_pln', $data);
				unset($data);
				
				$inserted_pln++;
			}
			
			// AC
			if ($ac = $this->core->view_row('t_mtr_site_l_ac', array('site_id' => $r['site_id']))) {
				$data['updated_on'] = $date;
				$data['updated_by'] = $by;
				
				$this->db->update('t_mtr_site_l_ac', $data, array('site_id' => $r['site_id']));
				unset($data);
				
				// detail ac #1
				if (trim($r['ac_brand_1']) != '' || trim($r['ac_model_1']) != '' || trim($r['ac_capacity_1']) != '' || trim($r['ac_serial_number_1']) != '') {
					$data['ac_brand_id'] = $r['ac_brand_1'];
					$data['model'] = $r['ac_model_1'];
					$data['ac_capacity_id'] = $r['ac_capacity_1'];
					$data['sn_indoor_part'] = $r['ac_serial_number_1'];
					
					$this->db->update('t_mtr_site_l_ac_detail', $data, array('ac_id' => $ac['ac_id']));
					unset($data);
				}
				
				// detail ac #2
				if (trim($r['ac_brand_2']) != '' || trim($r['ac_model_2']) != '' || trim($r['ac_capacity_2']) != '' || trim($r['ac_serial_number_2']) != '') {
					$data['ac_brand_id'] = $r['ac_brand_2'];
					$data['model'] = $r['ac_model_2'];
					$data['ac_capacity_id'] = $r['ac_capacity_2'];
					$data['sn_indoor_part'] = $r['ac_serial_number_2'];
					
					$this->db->update('t_mtr_site_l_ac_detail', $data, array('ac_id' => $ac['ac_id']));
					unset($data);
				}
				
				// detail ac #3
				if (trim($r['ac_brand_3']) != '' || trim($r['ac_model_3']) != '' || trim($r['ac_capacity_3']) != '' || trim($r['ac_serial_number_3']) != '') {
					$data['ac_brand_id'] = $r['ac_brand_3'];
					$data['model'] = $r['ac_model_3'];
					$data['ac_capacity_id'] = $r['ac_capacity_3'];
					$data['sn_indoor_part'] = $r['ac_serial_number_3'];
					
					$this->db->update('t_mtr_site_l_ac_detail', $data, array('ac_id' => $ac['ac_id']));
					unset($data);
				}
				
				$updated_ac++;
			} else {
				$data['site_id'] = $r['site_id'];
				$data['created_on'] = $date;
				$data['created_by'] = $by;
				$data['updated_on'] = $date;
				$data['updated_by'] = $by;
				
				$this->db->insert('t_mtr_site_l_ac', $data);
				$ac_id = $this->db->insert_id();
				unset($data);
				
				// detail ac #1
				if (trim($r['ac_brand_1']) != '' || trim($r['ac_model_1']) != '' || trim($r['ac_capacity_1']) != '' || trim($r['ac_serial_number_1']) != '') {
					$data['ac_id'] = $ac_id;
					$data['ac_brand_id'] = $r['ac_brand_1'];
					$data['model'] = $r['ac_model_1'];
					$data['ac_capacity_id'] = $r['ac_capacity_1'];
					$data['sn_indoor_part'] = $r['ac_serial_number_1'];
					
					$this->db->insert('t_mtr_site_l_ac_detail', $data);
					unset($data);
				}
				
				// detail ac #2
				if (trim($r['ac_brand_2']) != '' || trim($r['ac_model_2']) != '' || trim($r['ac_capacity_2']) != '' || trim($r['ac_serial_number_2']) != '') {
					$data['ac_id'] = $ac_id;
					$data['ac_brand_id'] = $r['ac_brand_2'];
					$data['model'] = $r['ac_model_2'];
					$data['ac_capacity_id'] = $r['ac_capacity_2'];
					$data['sn_indoor_part'] = $r['ac_serial_number_2'];
					
					$this->db->insert('t_mtr_site_l_ac_detail', $data);
					unset($data);
				}
				
				// detail ac #3
				if (trim($r['ac_brand_3']) != '' || trim($r['ac_model_3']) != '' || trim($r['ac_capacity_3']) != '' || trim($r['ac_serial_number_3']) != '') {
					$data['ac_id'] = $ac_id;
					$data['ac_brand_id'] = $r['ac_brand_3'];
					$data['model'] = $r['ac_model_3'];
					$data['ac_capacity_id'] = $r['ac_capacity_3'];
					$data['sn_indoor_part'] = $r['ac_serial_number_3'];
					
					$this->db->insert('t_mtr_site_l_ac_detail', $data);
					unset($data);
				}
				
				$inserted_ac++;
			}
		}
		
		echo '<ul>';
		echo '<li>Inserted Tower: '.$inserted_tower.' | Updated Tower: '.$updated_tower.'</li>';
		echo '<li>Inserted Shelter: '.$inserted_shelter.' | Updated Shelter: '.$updated_shelter.'</li>';
		echo '<li>Inserted PLN: '.$inserted_pln.' | Updated PLN: '.$updated_pln.'</li>';
		echo '<li>Inserted AC: '.$inserted_ac.' | Updated AC: '.$updated_ac.'</li>';
		echo '</ul>';
	}
	
	public function rf()
	{
		$this->db->query("DELETE FROM t_mtr_site_l_equipment_rf");
		$this->db->query("ALTER SEQUENCE t_mtr_site_l_equipment_bts_equipment_bts_id_seq RESTART WITH 1");
		
		$sql = "
			SELECT
				site.site_id,
				rf.vendor_id,
				lo1.lookup_value AS morphology,
				lo2.lookup_value AS bts_brand,
				lo3.lookup_value AS bts_mode,
				lo4.lookup_value AS bts_type,
				rf.ant_model,
				ant.antenna_id AS antenna_id,
				lo5.lookup_value AS cell,
				lo6.lookup_value AS ant_band,
				rf.ant_height,
				rf.electrical_tilt,
				rf.mechanical_tilt,
				rf.azimuth
			FROM importer.antenna_rf rf
			JOIN t_mtr_site site ON upper(site.vendor_id) = trim(upper(rf.vendor_id)) AND site.active = 1
			LEFT JOIN t_mtr_lookup lo1 ON upper(lo1.lookup_desc) = trim(upper(rf.morphology)) AND lo1.lookup_key = 'bts_morphology' AND lo1.active = 1
			LEFT JOIN t_mtr_lookup lo2 ON upper(lo2.lookup_desc) = trim(upper(rf.bts_brand)) AND lo2.lookup_key = 'bts_brand' AND lo2.active = 1
			LEFT JOIN t_mtr_lookup lo3 ON upper(lo3.lookup_desc) = trim(upper(rf.bts_mode)) AND lo3.lookup_key = 'bts_mode' AND lo3.active = 1
			LEFT JOIN t_mtr_lookup lo4 ON upper(lo4.lookup_desc) = trim(upper(rf.bts_type)) AND lo4.lookup_key = 'bts_type' AND lo4.active = 1
			LEFT JOIN t_mtr_lookup lo5 ON upper(lo5.lookup_desc) = trim(upper(rf.cell)) AND lo5.lookup_key = 'antenna_cell' AND lo5.active = 1
			LEFT JOIN t_mtr_lookup lo6 ON upper(lo6.lookup_desc) = trim(upper(rf.ant_band)) AND lo6.lookup_key = 'antenna_band' AND lo6.active = 1
			LEFT JOIN t_mtr_site_l_equipment_rf_antenna_brand ant ON trim(upper(ant.model)) = trim(upper(rf.ant_model)) AND ant.active = 1
		";
		
		$results = $this->db->query($sql)->result_array();
		
		$date = date('Y-m-d H:i:s');
		$by = $this->session->userdata('user_id');
		
		$inserted = 0;
		foreach ($results as $res) {
			$data['site_id'] = $res['site_id'];
			$data['type'] = 1;
			$data['cell'] = $res['cell'];
			$data['ant_model'] = (int) $res['ant_band'];
			$data['height'] = (float) $res['ant_height'];
			$data['electrical_tilt'] = (float) $res['electrical_tilt'];
			$data['mechanical_tilt'] = (float) $res['mechanical_tilt'];
			$data['azimuth'] = (float) $res['azimuth'];
			$data['model'] = $res['antenna_id'];
			$data['active'] = 1;
			$data['created_on'] = $date;
			$data['created_by'] = $by;
			$data['updated_on'] = $date;
			$data['updated_by'] = $by;
			
			$this->db->insert('t_mtr_site_l_equipment_rf', $data);
			unset($data);
			
			$inserted++;
		}
		
		echo "Inserted: {$inserted}";
	}
	
	public function rf_()
	{
		$file = $this->_upload_dir.'/SDB_RNO_FOR_SIMS_Maret.xls';
		
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		
		$objPHPExcel = new PHPExcel();
		
		try {
			$objReader = IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load($file);
		} catch (Exception $e) {
			$objReader = IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($file);
		}
		
		$rows = array();
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$i = 0;
			foreach ($worksheet->getRowIterator() as $row) {
				if ($i != 0) {
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
					$j = 0;
					foreach ($cellIterator as $cell) {
						if ( ! is_null($cell)) {
							//$rows[$i][$j]=$cell->getFormattedValue();
							$rows[$i][$j] = $cell->getValue();
						} else {
							$rows[$i][$j] = 0;
						}
						$j++;
					}
				}
				$i++;
			}
		}
		//print_rr($rows);
		//exit;
		
		$error = array();
		
		$date = date('Y-m-d H:i:s');
		$by = $this->session->userdata('user_id');
		
		$this->core->db_debug = TRUE;
		
		echo '<ul>';
		$i = 1;
		foreach ($rows as $row) {
			
			// cek vendor id, apakah udah ada di master site
			$res = $this->
				db->query("
					SELECT site.*
					FROM t_mtr_site site
					WHERE site.vendor_id = '".trim($row[0])."'
				")
				->row_array();
			
			if ($res) {
			
				if ($res['site_id'] != '') {
					
					// add new yg tidak ada, yg sudah ada abaikan
					//if ( ! $this->core->view_all('t_mtr_site_l_equipment_rf', array('site_id' => $res['site_id'])) ) {
					
						# 1900 -----------------------------------------------------------
						$data['site_id'] = $res['site_id'];
						
						$cell = 0;
						if ($row[3] == 0) {
							$cell = 1;
						} elseif ($row[3] == 1) {
							$cell = 2;
						} elseif ($row[3] == 2) {
							$cell = 3;
						}
						$data['cell'] = $cell;
						$data['type'] = 1;
						
						$band = 0;
						if (trim(strtoupper($row[19])) == 'DUAL BAND') {
							$band = 2;
						} elseif (trim(strtoupper($row[19])) == 'TWO SINGLE BAND') {
							$band = 1;
						} elseif (trim(strtoupper($row[19])) == 'DUALBAND ANTENNA') {
							$band = 2;
						} elseif (trim(strtoupper($row[19])) == 'DUAL BAND ANTENNA') {
							$band = 2;
						} elseif (trim(strtoupper($row[19])) == 'SINGLE BAND') {
							$band = 1;
						}
						$data['ant_model'] = $band;// band
						$data['height'] = (float) $row[15];
						$data['azimuth'] = (float) $row[16];
						$data['mechanical_tilt'] = (float) $row[17];
						$data['electrical_tilt'] = (float) $row[18];
						
						$model = 0;
						if (trim(strtoupper($row[19])) == 'DUAL BAND') {
							$model = 4;
						} elseif (trim(strtoupper($row[19])) == 'TWO SINGLE BAND') {
							$model = 2;
						} elseif (trim(strtoupper($row[19])) == 'DUAL BAND') {
							$model = 2;
						} elseif (trim(strtoupper($row[19])) == 'SINGLE BAND') {
							$model = 2;
						}
						$data['model'] = $model;
						
						$data['created_on'] = $date;
						$data['created_by'] = $by;
						$data['updated_on'] = $date;
						$data['updated_by'] = $by;
						$data['active'] = 1;
						
						//$this->core->save('t_mtr_site_l_equipment_rf');
						$this->db->insert('t_mtr_site_l_equipment_rf', $data);
						//unset($data);
						
						# 800 ----------------------------------------------------------------
						$data['site_id'] = $res['site_id'];
						
						$cell = 0;
						if ($row[3] == 0) {
							$cell = 4;
						} elseif ($row[3] == 1) {
							$cell = 5;
						} elseif ($row[3] == 2) {
							$cell = 6;
						}
						$data['cell'] = $cell;
						$data['type'] = 1;
						
						$band = 0;
						if (trim(strtoupper($row[19])) == 'DUAL BAND') {
							$band = 2;
						} elseif (trim(strtoupper($row[19])) == 'TWO SINGLE BAND') {
							$band = 1;
						} elseif (trim(strtoupper($row[19])) == 'DUALBAND ANTENNA') {
							$band = 2;
						} elseif (trim(strtoupper($row[19])) == 'DUAL BAND ANTENNA') {
							$band = 2;
						} elseif (trim(strtoupper($row[19])) == 'SINGLE BAND') {
							$band = 1;
						}
						$data['ant_model'] = $band;// band
						$data['height'] = (float) $row[11];
						$data['azimuth'] = (float) $row[12];
						$data['mechanical_tilt'] = (float) $row[13];
						$data['electrical_tilt'] = (float) $row[14];
						
						$model = 0;
						if (trim(strtoupper($row[19])) == 'DUAL BAND') {
							$model = 4;
						} elseif (trim(strtoupper($row[19])) == 'TWO SINGLE BAND') {
							$model = 2;
						} elseif (trim(strtoupper($row[19])) == 'DUAL BAND') {
							$model = 2;
						} elseif (trim(strtoupper($row[19])) == 'SINGLE BAND') {
							$model = 2;
						}
						$data['model'] = $model;
						
						$data['created_on'] = $date;
						$data['created_by'] = $by;
						$data['updated_on'] = $date;
						$data['updated_by'] = $by;
						$data['active'] = 1;
						
						//$this->core->save('t_mtr_site_l_equipment_rf');
						$this->db->insert('t_mtr_site_l_equipment_rf', $data);
						//unset($data);
						
						//echo 'Wookeh!';
						//echo '<li>'.$row[0].'</li>';
					//}
				}
				
				echo 'Wookeh!';
			
			} else {
				
			}
			
			$i++;
		}
		echo '</ul>';
		
	}
	
	public function carrier_config()
	{
		$sql = "
			SELECT
				site.site_id,
				car.vendor_id,
				car.\"system\",
				--car.carrier_config_1x,
				lo1.lookup_value AS carrier_config_1x,
				--car.carrier_config_evdo,
				lo2.lookup_value AS carrier_config_evdo
			FROM importer.antenna_rf_carrier_config car
			JOIN t_mtr_site site ON upper(site.vendor_id) = trim(upper(car.vendor_id)) AND site.active = 1
			LEFT JOIN t_mtr_lookup lo1 ON upper(lo1.lookup_desc) = trim(upper(car.carrier_config_1x)) AND lo1.lookup_key = 'carrier_configuration' AND lo1.active = 1
			LEFT JOIN t_mtr_lookup lo2 ON upper(lo2.lookup_desc) = trim(upper(car.carrier_config_evdo)) AND lo2.lookup_key = 'carrier_configuration' AND lo2.active = 1
		";
		
		$results = $this->db->query($sql)->result_array();
		
		$date = date('Y-m-d H:i:s');
		$by = $this->session->userdata('user_id');
		
		$inserted = 0;
		foreach ($results as $res) {
			if ($res['system'] == '1900') {
				if (trim($res['carrier_config_1x']) != '') {
					$data['site_id'] = $res['site_id'];
					$data['system'] = 3; // 1900 1x = 3
					$data['configuration'] = $res['carrier_config_1x'];
					$data['active'] = 1;
					
					$this->db->insert('t_mtr_site_l_equipment_carrier', $data);
					unset($data);
				}
				if (trim($res['carrier_config_evdo']) != '') {
					$data['site_id'] = $res['site_id'];
					$data['system'] = 4; // 1900 evdo = 4
					$data['configuration'] = $res['carrier_config_evdo'];
					$data['active'] = 1;
					
					$this->db->insert('t_mtr_site_l_equipment_carrier', $data);
					unset($data);
				}
			}
			
			if ($res['system'] == '800') {
				if (trim($res['carrier_config_1x']) != '') {
					$data['site_id'] = $res['site_id'];
					$data['system'] = 1; // 800 1x = 1
					$data['configuration'] = $res['carrier_config_1x'];
					$data['active'] = 1;
					
					$this->db->insert('t_mtr_site_l_equipment_carrier', $data);
					unset($data);
				}
				if (trim($res['carrier_config_evdo']) != '') {
					$data['site_id'] = $res['site_id'];
					$data['system'] = 2; // 800 evdo = 2
					$data['configuration'] = $res['carrier_config_evdo'];
					$data['active'] = 1;
					
					$this->db->insert('t_mtr_site_l_equipment_carrier', $data);
					unset($data);
				}
			}
			
			$inserted++;
		}
		
		echo "Inserted: {$inserted}";
	}
	
	public function bts_info()
	{
		$results = $this->db
			->query("
				SELECT DISTINCT site.site_id
				FROM importer.antenna_rf rf
				JOIN t_mtr_site site ON upper(site.vendor_id) = trim(upper(rf.vendor_id)) AND site.active = 1")
			->result_array();
			
		$date = date('Y-m-d H:i:s');
		$by = $this->session->userdata('user_id');
	
		$inserted = 0;
		foreach ($results as $res) {
			$r = $this->db
				->query("
					SELECT
						site.site_id,
						rf.vendor_id,
						lo1.lookup_value AS morphology,
						lo2.lookup_value AS bts_brand,
						lo3.lookup_value AS bts_mode,
						lo4.lookup_value AS bts_type
					FROM importer.antenna_rf rf
					JOIN t_mtr_site site ON upper(site.vendor_id) = trim(upper(rf.vendor_id)) AND site.active = 1
					LEFT JOIN t_mtr_lookup lo1 ON upper(lo1.lookup_desc) = trim(upper(rf.morphology)) AND lo1.lookup_key = 'bts_morphology' AND lo1.active = 1
					LEFT JOIN t_mtr_lookup lo2 ON upper(lo2.lookup_desc) = trim(upper(rf.bts_brand)) AND lo2.lookup_key = 'bts_brand' AND lo2.active = 1
					LEFT JOIN t_mtr_lookup lo3 ON upper(lo3.lookup_desc) = trim(upper(rf.bts_mode)) AND lo3.lookup_key = 'bts_mode' AND lo3.active = 1
					LEFT JOIN t_mtr_lookup lo4 ON upper(lo4.lookup_desc) = trim(upper(rf.bts_type)) AND lo4.lookup_key = 'bts_type' AND lo4.active = 1
					WHERE site.site_id = ".$res['site_id']."
					LIMIT 1
				")
				->row_array();
			
			$data['site_id'] = $r['site_id'];
			$data['morphology'] = (int) $r['morphology'];
			$data['bts_mode'] = (int) $r['bts_mode'];
			$data['bts_brand'] = (int) $r['bts_brand'];
			$data['bts_type'] = (int) $r['bts_type'];
			$data['active'] = 1;
			
			$data['created_on'] = $date;
			$data['created_by'] = $by;
			$data['updated_on'] = $date;
			$data['updated_by'] = $by;
			
			$this->db->insert('t_mtr_site_l_equipment_btsinfo', $data);
			unset($data);
			
			$inserted++;
		}
		
		echo "Inserted: {$inserted}";
	}
	
	public function fix_mw()
	{
		$q = $this->db->get("importer.tmp_wrong_mw");
		foreach($q->result() as $row){
			$this->db->where("vendor_id_ne",$row->wrong_vendor_id);
			$this->db->update("importer.mw",array("vendor_id_ne"=>$row->vendor_id));

			$this->db->where("vendor_id_fe",$row->wrong_vendor_id);
			$this->db->update("importer.mw",array("vendor_id_fe"=>$row->vendor_id));
		}
	}
	
	public function mw()
	{
		//$this->db->query("DELETE FROM t_mtr_site_l_equipment_mw");
		//$this->db->query("ALTER SEQUENCE t_mtr_site_l_equipment_mw_equipment_mw_id_seq RESTART WITH 1");
		
		$sql = "
SELECT
	mw.vendor_id_ne,
	mw.vendor_id_fe,
	site_ne.site_id AS site_id_ne_int,
	site_fe.site_id AS site_id_fe_int,
	mw.link_id_ne,
	mw.link_id_fe,
	mw.link_type,
	lo1.lookup_value AS link_type_2,
	mw.path_length,
	mw.main_ant_model_ne,
	mw.main_ant_model_fe,
	mw.sd_ant_model_ne,
	mw.sd_ant_model_fe,
	mw.sd_ant_diameter_ne,
	lo2.lookup_value AS sd_ant_diameter_ne_2,
	mw.sd_ant_diameter_fe,
	lo3.lookup_value AS sd_ant_diameter_fe_2,
	mw.req_capacity,
	mw.inst_capacity,
	mw.config,
	lo4.lookup_value as config_2,
	mw.eq_model,
	lo5.lookup_value AS eq_model_2,
	mw.azimuth_ne,
	mw.azimuth_fe,
	mw.polarization,
	lo6.lookup_value AS polarization_2,
	mw.tx_power,
	mw.treshold,
	mw.plan_rsl,
	mw.actual_rsl,
	mw.fm,
	mw.availability
FROM importer.mw
JOIN t_mtr_site site_ne ON upper(site_ne.vendor_id) = upper(mw.vendor_id_ne)
JOIN t_mtr_site site_fe ON upper(site_fe.vendor_id) = upper(mw.vendor_id_fe)
LEFT JOIN t_mtr_lookup lo1 ON UPPER(lo1.lookup_desc) = trim(UPPER(mw.link_type)) AND lo1.lookup_key = 'transmission_link_type' AND lo1.active = 1
LEFT JOIN t_mtr_lookup lo2 ON UPPER(lo2.lookup_desc) = trim(UPPER(mw.sd_ant_diameter_ne)) AND lo2.lookup_key = 'antenna_diameter' AND lo2.active = 1
LEFT JOIN t_mtr_lookup lo3 ON UPPER(lo3.lookup_desc) = trim(UPPER(mw.sd_ant_diameter_fe)) AND lo3.lookup_key = 'antenna_diameter' AND lo3.active = 1
LEFT JOIN t_mtr_lookup lo4 ON UPPER(lo4.lookup_desc) = trim(UPPER(mw.config)) AND lo4.lookup_key = 'transmission_config_hop' AND lo4.active = 1
LEFT JOIN t_mtr_lookup lo5 ON UPPER(lo5.lookup_desc) = trim(UPPER(mw.eq_model)) AND lo5.lookup_key = 'equipment_model' AND lo5.active = 1
LEFT JOIN t_mtr_lookup lo6 ON UPPER(lo6.lookup_desc) = trim(UPPER(mw.polarization)) AND lo6.lookup_key = 'transmission_polarization' AND lo6.active = 1
		";
		
		$res = $this->db->query($sql)->result_array();
		
		$date = date('Y-m-d H:i:s');
		$by = $this->session->userdata('user_id');
		
		$update=0;
		$insert=0;
		foreach ($res as $r) {
			// NE
			$data['site_id_ne'] = $r['site_id_ne_int'];
			$data['site_id_fe'] = $r['site_id_fe_int'];
			$data['link_code_id'] = $r['link_id_ne'];
			$data['link_type'] = $r['link_type_2'];
			$data['path_length'] = (float)$r['path_length'];
			$data['ant_model'] = $r['main_ant_model_ne'];
			$data['sd_ant_model'] = $r['sd_ant_model_ne'];
			$data['sd_ant_diameter'] = $r['sd_ant_diameter_ne_2'];
			$data['req_capacity'] = $r['req_capacity'];
			$data['inst_capacity'] = $r['inst_capacity'];
			$data['config'] = $r['config_2'];
			$data['equipment_model'] = $r['eq_model_2'];
			$data['azimuth'] =(float) $r['azimuth_ne'];
			$data['polarization'] = $r['polarization_2'];
			$data['treshold'] = (float)$r['treshold'];
			$data['plan_rsl'] =(float)$r['plan_rsl'];
			$data['actual_rsl'] =(float) $r['actual_rsl'];
			$data['fm'] =(float) $r['fm'];
			$data['availability'] =(float)  $r['availability'];
			$data['active']=1;
			$data['created_on'] = $date;
			$data['created_by'] = $by;
			$data['updated_on'] = $date;
			$data['updated_by'] = $by;
			
			$this->db->insert('t_mtr_site_l_equipment_mw', $data);
			
			$id = $this->db->insert_id();
			$this->db->update('t_mtr_site_l_equipment_mw', array('pair_id' => $id), array('equipment_mw_id' => $id));
			
			// FE
			$data['active']=1;
			$data['site_id_ne'] = $r['site_id_fe_int'];
			$data['site_id_fe'] = $r['site_id_ne_int'];
			$data['link_code_id'] = $r['link_id_fe'];
			$data['link_type'] = $r['link_type_2'];
			$data['path_length'] =(float)$r['path_length'];
			$data['ant_model'] = $r['main_ant_model_fe'];
			$data['sd_ant_model'] = $r['sd_ant_model_fe'];
			$data['sd_ant_diameter'] = $r['sd_ant_diameter_fe_2'];
			$data['req_capacity'] = $r['req_capacity'];
			$data['inst_capacity'] = $r['inst_capacity'];
			$data['config'] = $r['config_2'];
			$data['equipment_model'] = $r['eq_model_2'];
			$data['azimuth'] =(float) $r['azimuth_fe'];
			$data['polarization'] = $r['polarization_2'];
			$data['treshold'] = (float)$r['treshold'];
			$data['plan_rsl'] = (float)$r['plan_rsl'];
			$data['actual_rsl'] = (float) $r['actual_rsl'];
			$data['fm'] = (float) $r['fm'];
			$data['availability'] =(float)  $r['availability'];
			$data['pair_id'] = $id;
			
			$data['created_on'] = $date;
			$data['created_by'] = $by;
			$data['updated_on'] = $date;
			$data['updated_by'] = $by;
			
			$this->db->insert('t_mtr_site_l_equipment_mw', $data);
			
			$insert++;
		}
		
		echo "Update: $update<br>Insert:$insert";
	}
	
	public function pln_usage($tahun, $bulan)
	{
		$sql = "
SELECT
	site.site_id,
	pln.customer_id,
	pln.vendor_id,
	pln.site_name,
	pln.customer_name,
	pln.pln_capacity,
	pln.gol_tariff,
	lo6.lookup_value AS gol_tariff_2,
	
	pln.kwh_awal_dec2013,
	pln.kwh_akhir_dec2013,
	pln.pemakaian_kwh_dec2013,
	pln.amount_dec2013,
	pln.payment_method_dec2013,
	lo1.lookup_value AS payment_method_dec2013_2,

	pln.kwh_awal_jan2014,
	pln.kwh_akhir_jan2014,
	pln.pemakain_kwh_jan2014,
	pln.amount_jan2014,
	pln.payment_method_jan2014,
	lo2.lookup_value AS payment_method_jan2014_2,

	pln.kwh_awal_feb2014,
	pln.kwh_akhir_feb2014,
	pln.pemakaian_kwh_feb2014,
	pln.amount_feb2014,
	pln.payment_method_feb2014,
	lo3.lookup_value AS payment_method_feb2014_2,

	pln.kwh_awal_mar2014,
	pln.kwh_akhir_mar2014,
	pln.pemakain_kwh_mar2014,
	pln.amount_mar2014,
	pln.payment_method_mar2014,
	lo4.lookup_value AS payment_method_mar2014_2,

	pln.kwh_awal_apr2014,
	pln.kwh_akhir_apr2014,
	pln.pemakaian_kwh_apr2014,
	pln.amount_apr2014,
	pln.payment_method_apr2014,
	lo5.lookup_value AS payment_method_apr2014_2
	
FROM importer.pln_usage pln
JOIN t_mtr_site site ON upper(site.vendor_id) = trim(upper(pln.vendor_id))
LEFT JOIN t_mtr_lookup lo1 ON upper(lo1.lookup_desc) = trim(upper(pln.payment_method_dec2013)) AND lo1.lookup_key = 'payment_method' AND lo1.active = 1
LEFT JOIN t_mtr_lookup lo2 ON upper(lo2.lookup_desc) = trim(upper(pln.payment_method_jan2014)) AND lo2.lookup_key = 'payment_method' AND lo2.active = 1
LEFT JOIN t_mtr_lookup lo3 ON upper(lo3.lookup_desc) = trim(upper(pln.payment_method_feb2014)) AND lo3.lookup_key = 'payment_method' AND lo3.active = 1
LEFT JOIN t_mtr_lookup lo4 ON upper(lo4.lookup_desc) = trim(upper(pln.payment_method_mar2014)) AND lo4.lookup_key = 'payment_method' AND lo4.active = 1
LEFT JOIN t_mtr_lookup lo5 ON upper(lo5.lookup_desc) = trim(upper(pln.payment_method_apr2014)) AND lo5.lookup_key = 'payment_method' AND lo5.active = 1
LEFT JOIN t_mtr_lookup lo6 ON upper(lo6.lookup_desc) = trim(upper(pln.gol_tariff)) AND lo6.lookup_key = 'pln_tariff_name' AND lo6.active = 1
		";
		
		$res = $this->db->query($sql)->result_array();
		
		$date = date('Y-m-d H:i:s');
		$by = $this->session->userdata('user_id');
		$updated = 0;
		$inserted = 0;
		
		foreach ($res as $r) {
			// Dec 2013
			if ($tahun == 2013 && $bulan == 12) {
				$data['site_id'] = $r['site_id'];
				$data['customer_id'] = $r['customer_id'];
				$data['tahun'] = 2013;
				$data['bulan'] = 12;
				$data['kwh_awal'] = (float) $r['kwh_awal_dec2013'];
				$data['kwh_akhir'] = (float) $r['kwh_akhir_dec2013'];
				$data['pemakaian_kwh'] = (float) $r['pemakaian_kwh_dec2013'];
				$data['biaya'] = (float) $r['amount_dec2013'];
				$data['payment_method_id'] = (int) $r['payment_method_dec2013_2'];
				$data['active'] = 1;
				
				if ($this->core->view_row('t_trx_site_pln_usage', array('site_id' => $r['site_id'], 'customer_id ' => $r['customer_id'], 'tahun' => 2013, 'bulan' => 12))) {
					$data['updated_on'] = $date;
					$data['updated_by'] = $by;
					
					$this->db->update('t_trx_site_pln_usage', $data, array('site_id' => $r['site_id'], 'customer_id ' => $r['customer_id'], 'tahun' => 2013, 'bulan' => 12));
					
					$updated++;
				} else {
					$data['created_on'] = $date;
					$data['created_by'] = $by;
					$data['updated_on'] = $date;
					$data['updated_by'] = $by;
				
					$this->db->insert('t_trx_site_pln_usage', $data);
					
					$inserted++;
				}
			}
			
			// Jan 2014
			if ($tahun == 2014 && $bulan == 1) {
				$data['site_id'] = $r['site_id'];
				$data['customer_id'] = $r['customer_id'];
				$data['tahun'] = 2014;
				$data['bulan'] = 1;
				$data['kwh_awal'] = (float) $r['kwh_awal_jan2014'];
				$data['kwh_akhir'] = (float) $r['kwh_akhir_jan2014'];
				$data['pemakaian_kwh'] = (float) $r['pemakain_kwh_jan2014'];
				$data['biaya'] = (float) $r['amount_jan2014'];
				$data['payment_method_id'] = (int) $r['payment_method_jan2014_2'];
				$data['active'] = 1;
				
				if ($this->core->view_row('t_trx_site_pln_usage', array('site_id' => $r['site_id'], 'customer_id ' => $r['customer_id'], 'tahun' => 2014, 'bulan' => 1))) {
					$data['updated_on'] = $date;
					$data['updated_by'] = $by;
					
					$this->db->update('t_trx_site_pln_usage', $data, array('site_id' => $r['site_id'], 'customer_id ' => $r['customer_id'], 'tahun' => 2014, 'bulan' => 1));
					
					$updated++;
				} else {
					$data['created_on'] = $date;
					$data['created_by'] = $by;
					$data['updated_on'] = $date;
					$data['updated_by'] = $by;
				
					$this->db->insert('t_trx_site_pln_usage', $data);
					
					$inserted++;
				}
			}
			
			// Feb 2014
			if ($tahun == 2014 && $bulan == 2) {
				$data['site_id'] = $r['site_id'];
				$data['customer_id'] = $r['customer_id'];
				$data['tahun'] = 2014;
				$data['bulan'] = 2;
				$data['kwh_awal'] = (float) $r['kwh_awal_feb2014'];
				$data['kwh_akhir'] = (float) $r['kwh_akhir_feb2014'];
				$data['pemakaian_kwh'] = (float) $r['pemakaian_kwh_feb2014'];
				$data['biaya'] = (float) $r['amount_feb2014'];
				$data['payment_method_id'] = (int) $r['payment_method_feb2014_2'];
				$data['active'] = 1;
				
				if ($this->core->view_row('t_trx_site_pln_usage', array('site_id' => $r['site_id'], 'customer_id ' => $r['customer_id'], 'tahun' => 2014, 'bulan' => 2))) {
					$data['updated_on'] = $date;
					$data['updated_by'] = $by;
					
					$this->db->update('t_trx_site_pln_usage', $data, array('site_id' => $r['site_id'], 'customer_id ' => $r['customer_id'], 'tahun' => 2014, 'bulan' => 2));
					
					$updated++;
				} else {
					$data['created_on'] = $date;
					$data['created_by'] = $by;
					$data['updated_on'] = $date;
					$data['updated_by'] = $by;
				
					$this->db->insert('t_trx_site_pln_usage', $data);
					
					$inserted++;
				}
			}
			
			// Mar 2014
			if ($tahun == 2014 && $bulan == 3) {
				$data['site_id'] = $r['site_id'];
				$data['customer_id'] = $r['customer_id'];
				$data['tahun'] = 2014;
				$data['bulan'] = 3;
				$data['kwh_awal'] = (float) $r['kwh_awal_mar2014'];
				$data['kwh_akhir'] = (float) $r['kwh_akhir_mar2014'];
				$data['pemakaian_kwh'] = (float) $r['pemakain_kwh_mar2014'];
				$data['biaya'] = (float) $r['amount_mar2014'];
				$data['payment_method_id'] = (int) $r['payment_method_mar2014_2'];
				$data['active'] = 1;
				
				if ($this->core->view_row('t_trx_site_pln_usage', array('site_id' => $r['site_id'], 'customer_id ' => $r['customer_id'], 'tahun' => 2014, 'bulan' => 3))) {
					$data['updated_on'] = $date;
					$data['updated_by'] = $by;
					
					$this->db->update('t_trx_site_pln_usage', $data, array('site_id' => $r['site_id'], 'customer_id ' => $r['customer_id'], 'tahun' => 2014, 'bulan' => 3));
					
					$updated++;
				} else {
					$data['created_on'] = $date;
					$data['created_by'] = $by;
					$data['updated_on'] = $date;
					$data['updated_by'] = $by;
				
					$this->db->insert('t_trx_site_pln_usage', $data);
					
					$inserted++;
				}
			}
			
			// Apr 2014
			if ($tahun == 2014 && $bulan == 4) {
				$data['site_id'] = $r['site_id'];
				$data['customer_id'] = $r['customer_id'];
				$data['tahun'] = 2014;
				$data['bulan'] = 4;
				$data['kwh_awal'] = (float) $r['kwh_awal_apr2014'];
				$data['kwh_akhir'] = (float) $r['kwh_akhir_apr2014'];
				$data['pemakaian_kwh'] = (float) $r['pemakaian_kwh_apr2014'];
				$data['biaya'] = (float) $r['amount_apr2014'];
				$data['payment_method_id'] = (int) $r['payment_method_apr2014_2'];
				$data['active'] = 1;
				
				if ($this->core->view_row('t_trx_site_pln_usage', array('site_id' => $r['site_id'], 'customer_id ' => $r['customer_id'], 'tahun' => 2014, 'bulan' => 4))) {
					$data['updated_on'] = $date;
					$data['updated_by'] = $by;
					
					$this->db->update('t_trx_site_pln_usage', $data, array('site_id' => $r['site_id'], 'customer_id ' => $r['customer_id'], 'tahun' => 2014, 'bulan' => 4));
					
					$updated++;
				} else {
					$data['created_on'] = $date;
					$data['created_by'] = $by;
					$data['updated_on'] = $date;
					$data['updated_by'] = $by;
				
					$this->db->insert('t_trx_site_pln_usage', $data);
					
					$inserted++;
				}
			}
		}
		
		echo 'Inserted: '.$inserted.'<br />Updated: '.$updated;
	}
	
	public function site_tp()
	{
		$results = $this->db->query("select distinct tp_name from t_mtr_site")->result_array();
		foreach ($results as $res) {
			$r=$this->db->query("select company_id from t_mtr_tp_company where company_name='".$res['tp_name']."'")->row_array();
			
			if ($r) {
				$data['tp_name'] = $r['company_id'];
			
				$this->db->update('t_mtr_site', $data, array('tp_name' => $res['tp_name']));
				unset($data);
			}
		}
	}
	
}