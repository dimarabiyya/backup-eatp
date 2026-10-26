<?php

class Site_info extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    function plan($param) {
        $this->db->where('site_id', $param['site_id']);
        return $this->db->get('t_mtr_site');
    }

    function sites($param) {
        $region = array();
		foreach ($this->session->userdata['user_region'] as $reg) {
			if ($reg['region_id'] != '') {
				$region[] = 'f_regionidtoregionname('.$reg['region_id'].')';
			}
		}
		
		$sql = "select * from v_site_info_sites_list where v_site_info_sites_list.region in (".implode(',', $region).")";
		
		return $this->db
			->query($sql);
    }

    function pln_usage($param) {
        $where = '';

        $year = $param['year'];

        //if ($param['month'] != 0) {
        //	$where .= " AND pln.bulan = ".$param['month'];
        //}

        return $this->db->query("
			SELECT
				site.site_code AS \"Site ID\",
				site.vendor_id AS \"Vendor ID\",
				site.site_name AS \"Site Name\",
				a.customer_id AS \"Customer ID\",
				reg.region_name AS \"Regional\",
				f_get_lookup_name('site_profile', site.site_profile) AS \"Site Profile\",
				f_get_lookup_name('site_type', site.site_type) AS \"Site Type\",
				f_get_lookup_name('site_specification', site.site_specification) AS \"Site Specification\",
				site_pln.company_code AS \"Company Code\",
				CASE WHEN site_pln.company_code != '' THEN f_get_lookup_name('company', site_pln.company_code::INTEGER) ELSE NULL END AS \"Company\",
				site_pln.cost_center AS \"Cost Center\",
				site_pln.pln_capacity AS \"Capacity\",
				f_get_lookup_name('tariff_type', site_pln.tarif_type) AS \"Tariff Type\",
				f_get_lookup_name('pln_tariff_name', site_pln.tarif_name) AS \"Golongan Tariff\",
				b.kwh_awal as \"Jan-KWH Before\", b.kwh_akhir as \"Jan-KWH After\", b.pemakaian_kwh as \"Jan-KWH Usage\", b.biaya as \"Jan-Amount\", f_get_lookup_name('payment_method', b.payment_method_id) as \"Jan-Payment Method\", f_get_lookup_name('payment_type', b.payment_type) as \"Jan-Payment Type\", b.tanggal_bayar as \"Jan-Payment Date\"
				,c.kwh_awal as \"Feb-KWH Before\", c.kwh_akhir as \"Feb-KWH After\", c.pemakaian_kwh as \"Feb-KWH Usage\", c.biaya as \"Feb-Amount\", f_get_lookup_name('payment_method', c.payment_method_id) as \"Feb-Payment Method\", f_get_lookup_name('payment_type', c.payment_type) as \"Feb-Payment Type\", c.tanggal_bayar as \"Feb-Payment Date\"
				,d.kwh_awal as \"Mar-KWH Before\", d.kwh_akhir as \"Mar-KWH After\", d.pemakaian_kwh as \"Mar-KWH Usage\", d.biaya as \"Mar-Amount\", f_get_lookup_name('payment_method', d.payment_method_id) as \"Mar-Payment Method\", f_get_lookup_name('payment_type', d.payment_type) as \"Mar-Payment Type\", d.tanggal_bayar as \"Mar-Payment Date\"
				,e.kwh_awal as \"Apr-KWH Before\", e.kwh_akhir as \"Apr-KWH After\", e.pemakaian_kwh as \"Apr-KWH Usage\", e.biaya as \"Apr-Amount\", f_get_lookup_name('payment_method', e.payment_method_id) as \"Apr-Payment Method\", f_get_lookup_name('payment_type', e.payment_type) as \"Apr-Payment Type\", e.tanggal_bayar as \"Apr-Payment Date\"
				,f.kwh_awal as \"May-KWH Before\", f.kwh_akhir as \"May-KWH After\", f.pemakaian_kwh as \"May-KWH Usage\", f.biaya as \"May-Amount\", f_get_lookup_name('payment_method', f.payment_method_id) as \"May-Payment Method\", f_get_lookup_name('payment_type', f.payment_type) as \"May-Payment Type\", f.tanggal_bayar as \"May-Payment Date\"
				,g.kwh_awal as \"Jun-KWH Before\", g.kwh_akhir as \"Jun-KWH After\", g.pemakaian_kwh as \"Jun-KWH Usage\", g.biaya as \"Jun-Amount\", f_get_lookup_name('payment_method', g.payment_method_id) as \"Jun-Payment Method\", f_get_lookup_name('payment_type', g.payment_type) as \"Jun-Payment Type\", g.tanggal_bayar as \"Jun-Payment Date\"
				,h.kwh_awal as \"Jul-KWH Before\", h.kwh_akhir as \"Jul-KWH After\", h.pemakaian_kwh as \"Jul-KWH Usage\", h.biaya as \"Jul-Amount\", f_get_lookup_name('payment_method', h.payment_method_id) as \"Jul-Payment Method\", f_get_lookup_name('payment_type', h.payment_type) as \"Jul-Payment Type\", h.tanggal_bayar as \"Jul-Payment Date\"
				,i.kwh_awal as \"Aug-KWH Before\", i.kwh_akhir as \"Aug-KWH After\", i.pemakaian_kwh as \"Aug-KWH Usage\", i.biaya as \"Aug-Amount\", f_get_lookup_name('payment_method', i.payment_method_id) as \"Aug-Payment Method\", f_get_lookup_name('payment_type', i.payment_type) as \"Aug-Payment Type\", i.tanggal_bayar as \"Aug-Payment Date\"
				,j.kwh_awal as \"Sep-KWH Before\", j.kwh_akhir as \"Sep-KWH After\", j.pemakaian_kwh as \"Sep-KWH Usage\", j.biaya as \"Sep-Amount\", f_get_lookup_name('payment_method', j.payment_method_id) as \"Sep-Payment Method\", f_get_lookup_name('payment_type', j.payment_type) as \"Sep-Payment Type\", j.tanggal_bayar as \"Sep-Payment Date\"
				,k.kwh_awal as \"Oct-KWH Before\", k.kwh_akhir as \"Oct-KWH After\", k.pemakaian_kwh as \"Oct-KWH Usage\", k.biaya as \"Oct-Amount\", f_get_lookup_name('payment_method', k.payment_method_id) as \"Oct-Payment Method\", f_get_lookup_name('payment_type', k.payment_type) as \"Oct-Payment Type\", k.tanggal_bayar as \"Oct-Payment Date\"
				,l.kwh_awal as \"Nov-KWH Before\", l.kwh_akhir as \"Nov-KWH After\", l.pemakaian_kwh as \"Nov-KWH Usage\", l.biaya as \"Nov-Amount\", f_get_lookup_name('payment_method', l.payment_method_id) as \"Nov-Payment Method\", f_get_lookup_name('payment_type', l.payment_type) as \"Nov-Payment Type\", l.tanggal_bayar as \"Nov-Payment Date\"
				,m.kwh_awal as \"Dec-KWH Before\", m.kwh_akhir as \"Dec-KWH After\", m.pemakaian_kwh as \"Dec-KWH Usage\", m.biaya as \"Dec-Amount\", f_get_lookup_name('payment_method', m.payment_method_id) as \"Dec-Payment Method\", f_get_lookup_name('payment_type', m.payment_type) as \"Dec-Payment Type\", m.tanggal_bayar as \"Dec-Payment Date\"
			FROM
			(SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ORDER BY site_id asc) as a
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=1 and trx.active=1 ) as b on b.site_id=a.site_id and b.customer_id=a.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=2 and trx.active=1 ) as c on c.site_id=b.site_id and c.customer_id=b.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=3 and trx.active=1 ) as d on d.site_id=c.site_id and d.customer_id=c.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=4 and trx.active=1 ) as e on e.site_id=d.site_id and e.customer_id=d.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=5 and trx.active=1 ) as f on f.site_id=e.site_id and f.customer_id=e.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=6 and trx.active=1 ) as g on g.site_id=f.site_id and g.customer_id=f.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=7 and trx.active=1 ) as h on h.site_id=g.site_id and h.customer_id=g.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=8 and trx.active=1 ) as i on i.site_id=h.site_id and i.customer_id=h.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=9 and trx.active=1 ) as j on j.site_id=i.site_id and j.customer_id=i.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=10 and trx.active=1 ) as k on k.site_id=j.site_id and k.customer_id=j.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=11 and trx.active=1 ) as l on l.site_id=k.site_id and l.customer_id=k.customer_id
			LEFT JOIN
			(
				SELECT mtr.site_id, mtr.customer_id, trx.kwh_awal, trx.kwh_akhir, trx.pemakaian_kwh, trx.biaya, trx.payment_method_id, trx.tanggal_bayar, trx.payment_type
				FROM (SELECT DISTINCT site_id,customer_id from t_trx_site_pln_usage where tahun={$year} and active=1 ) as mtr
				LEFT JOIN t_trx_site_pln_usage trx on mtr.site_id=trx.site_id and mtr.customer_id=trx.customer_id and trx.tahun={$year} and trx.bulan=12 and trx.active=1 ) as m on m.site_id=l.site_id and m.customer_id=l.customer_id

			LEFT JOIN t_mtr_site site ON site.site_id = a.site_id AND site.active = 1
			LEFT JOIN t_mtr_site_l_pln site_pln ON site_pln.site_id = a.site_id AND site_pln.active = 1
			LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id AND reg.status = 1
			ORDER BY a.site_id ASC
		");
    }

    function equipment($param) {
        $sql = "
			select
				mw.\"Site ID NE\",
				mw.\"Site ID FE\",
				mw.\"Link ID\",
				lookup1.lookup_desc as \"Link Type\",
				mw.\"Azimuth\",
				lookup2.lookup_desc as \"Eq. Model\",
				mw.\"Power\",
				lookup3.lookup_desc as \"Main Ant. Diameter\",
				mw.ip_address as \"IP Address\",
				mw.path_length as \"Path Length (km)\",
				mw.\"TX Frequency\",
				mw.\"RX Frequency\",
				mw.\"SD Antenna Model\",
				mw.\"SD Antenna Height\",
				mw.\"SD Antenna Diameter\",
				mw.req_capacity as \"Req. Capacity (MB/E1)\",
				mw.inst_capacity as \"Inst. Capacity (MB/E1)\",
				mw.used_capacity as \"Used capacity (MB/E1)\",
				mw.treshold as \"Treshold\",
				mw.plan_rsl as \"Plan RSL (dBm)\",
				mw.actual_rsl as \"Actual RSL (dBm)\",
				mw.fm as \"FM\",
				lookup4.lookup_desc as \"Config\",
				mw.availability as \"Availability (%)\"
			from v_equipment_mw mw
			left join t_mtr_lookup lookup1 on lookup1.lookup_value = mw.link_type::VARCHAR and trim(lookup1.lookup_key) = 'transmission_link_type' and lookup1.active = 1
			left join t_mtr_lookup lookup2 on lookup2.lookup_value = mw.equipment_model::VARCHAR and trim(lookup2.lookup_key) = 'equipment_model' and lookup2.active = 1
			left join t_mtr_lookup lookup3 on lookup3.lookup_value = mw.ant_diameter::VARCHAR and trim(lookup3.lookup_key) = 'antenna_diameter' and lookup3.active = 1
			left join t_mtr_lookup lookup4 on lookup4.lookup_value = mw.config::VARCHAR and trim(lookup4.lookup_key) = 'transmission_config_hop' and lookup4.active = 1
		";
		
		return $this->db
			->query($sql);
    }

    function power_equipment($param) {
        die('Under Construction');
    }

    function summary_list_bts_optimization($param) {
        $site_ids = explode(',', $this->input->get('site_ids'));
        foreach ($site_ids as $idx => $id) {
            $site_ids[$idx] = (int) $this->core->decode($id);
        }

        return $this->db->query("
			SELECT
				site.vendor_id AS \"Vendor ID\",
				site.site_name AS \"Site Name\",
				lo1.lookup_desc AS \"Cell\",
				site.latitude AS \"Latitude\",
				site.longitude AS \"Longitude\",
				reg.region_name AS \"Region\",
				lok.nama AS \"City\",
				cnet.cluster_name AS \"RF Cluster\",
				br.hbwd AS \"HBWD\",
				br.vbwd AS \"VBWD\",
				adj.height_before AS \"Ant. Height Plan\",
				adj.height_after AS \"Ant. Height Actual\",
				adj.azimuth_before AS \"Azimuth Before\",
				adj.azimuth_after AS \"Azimuth After\",
				adj.mechanical_tilt_before AS \"M. Tilt\",
				adj.mechanical_tilt_after AS \"M.Tilt After\",
				adj.electrical_tilt_before AS \"E. Tilt Before\",
				adj.electrical_tilt_after AS \"E. Tilt After\",
				opt.visit_date AS \"Optimization Date\",
				lo2.lookup_desc AS \"Reason for Change\",
				opt.sf_engineer AS \"Smartfren Engineer\",
				opt.vendor_engineer AS \"Vendor Engineer\",
				opt.sf_rigger AS \"Smartfren Rigger\",
				opt.vendor_rigger AS \"Vendor Rigger\",
				lo3.lookup_desc AS \"Ant. Model\",
				opt.remarks AS \"Remarks\"
			FROM t_trx_bts_optimization_adjust adj
			LEFT JOIN t_trx_bts_optimization opt ON opt.visit_id = adj.visit_id
			LEFT JOIN t_mtr_site site ON site.site_id = opt.site_id
			LEFT JOIN t_mtr_site_l_equipment_rf rf ON rf.equipment_rf_id = adj.equipment_rf_id
			LEFT JOIN t_mtr_region reg ON reg.region_id = site.region_id AND reg.status = 1
			LEFT JOIN t_mtr_cluster_net cnet ON cnet.cluster_net_id = site.cluster_net_id AND cnet.status = 1
			LEFT JOIN t_mtr_lokasi lok ON lok.lokasi_id = site.kabupaten AND lok.jenis = 'KB' AND lok.status = 1
			LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(rf.cell AS VARCHAR) AND lo1.lookup_key = 'antenna_cell' AND lo1.active = 1
			LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(opt.reason AS VARCHAR) AND lo2.lookup_key = 'bts_optimization_reason' AND lo2.active = 1
			LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = CAST(rf.ant_model AS VARCHAR) AND lo3.lookup_key = 'antenna_band' AND lo3.active = 1
			LEFT JOIN t_mtr_site_l_equipment_rf_antenna_brand br ON br.antenna_id = rf.model
			WHERE adj.active = 1 AND opt.active = 1 AND site.site_code IS NOT NULL AND site.site_id IN (" . implode(', ', $site_ids) . ")
			ORDER BY site.vendor_id, adj.visit_id, lo1.\"order\" ASC
		");
    }

    function summary_list_bts_not_optimization($param) {
        $site_ids = explode(',', $this->input->get('site_ids'));
        foreach ($site_ids as $idx => $id) {
            $site_ids[$idx] = (int) $this->core->decode($id);
        }

        return $this->db->query("
			SELECT
				site.site_code AS \"Site ID\",
				site.site_name AS \"Site Name\",
				site.vendor_id AS \"Vendor ID\",
				lo2.lookup_desc AS \"Type\",
				brand.model AS \"Model\",
				brand.brand AS \"Brand\",
				lo1.lookup_desc AS \"Cell\",
				lo3.lookup_desc AS \"Band\",
				brand.hbwd AS \"HBWD\",
				brand.vbwd AS \"VBWD\",
				CONCAT(brand.dimension_long, ' x ', brand.dimension_width, ' x ', brand.dimension_height) AS \"Dimension (mm)\",
				brand.weight AS \"Weight\",
				rf.height AS \"Height\",
				rf.azimuth AS \"Azimuth\",
				rf.electrical_tilt AS \"Electrical Tilt\",
				rf.mechanical_tilt AS \"Mechanical Tilt\"
			FROM t_mtr_site_l_equipment_rf rf
			LEFT JOIN t_mtr_site_l_equipment_rf_antenna_brand brand ON brand.antenna_id = rf.model
			LEFT JOIN t_mtr_site site ON site.site_id = rf.site_id
			LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = rf.cell::VARCHAR AND lo1.active = 1 AND lo1.lookup_key = 'antenna_cell'
			LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = rf.\"type\"::VARCHAR AND lo2.active = 1 AND lo2.lookup_key = 'antenna_rf_type'
			LEFT JOIN t_mtr_lookup lo3 ON lo3.lookup_value = rf.ant_model::VARCHAR AND lo3.active = 1 AND lo3.lookup_key = 'antenna_band'
			WHERE
				rf.site_id IN (" . implode(', ', $site_ids) . ")
				AND rf.active = 1
			ORDER BY site.vendor_id, lo1.\"order\" ASC
		");
    }

    function tower_provider($param) {
        return $this->db->get("v_site_info_tower_provider");
    }

    function site_candidate($param) {
        die('Under Construction');
    }

    function gis_candidate_selection($param) {
        die('Under Construction');
    }

    function gis_network_topology($param) {
        die('Under Construction');
    }

    function gis_boundary($param) {
        die('Under Construction');
    }

    function site_season($param) {
        die('Under Construction');
    }

}