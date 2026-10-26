 
<?php

class reference_model extends CI_Model
{

	/*Get Region list from table t_mtr_region_xl*/
	public function regionList()
	{
		$this->db->where("is_deleted", 0);
		$this->db->select('region.region_id, region.region_name');
		$this->db->from('t_mtr_region_xl region');
		$this->db->order_By("region.order_no", "ASC");
		$res = $this->db->get()->result();
		/*Declare temporary array variable*/
		/*Push each key and value to array*/
		$data = array();
		$data[''] = "- Choose -";
		foreach ($res as $v) {
			$data[$v->region_id] = $v->region_name;
		}
		return $data;
	}

	/*Get Region Data from Region Name*/
	public function getRegion($region_name)
	{
		$this->db->select('mreg.*');
		$this->db->from('t_mtr_region_xl mreg');
		$this->db->where("mreg.region_name ilike '" . $region_name . "%'");
		$query = $this->db->get()->row();
		return $query;
	}

	/*Get Area Data from Area Name*/
	public function getArea($area_name)
	{
		$this->db->select('area.*');
		$this->db->from('t_mtr_area area');
		$this->db->where("area.area_name ilike '" . $area_name . "%'");
		$query = $this->db->get()->row();
		return $query;
	}
	/*Get PO By PO_NO*/
	public function getPO($po_no)
	{
		// echo "select * from t_trx_po_xl where '".$po_no."'";
		$this->db->select('po.*');
		$this->db->from('t_trx_po_xl po');
		$this->db->where("po.po_no = '" . $po_no . "'");
		$query = $this->db->get()->row();
		return $query;
	}
	/*Get Site By Site_id*/
	public function getSite($site_id)
	{
		$this->db->select('site.*');
		$this->db->from('t_mtr_site_xl site');
		$this->db->where("site.site_id = '" . $site_id . "'");
		$query = $this->db->get()->row();
		return $query;
	}
	/*Get site_link from link_id*/
	public function getLink($link)
	{
		$this->db->select('link.*');
		$this->db->from('t_mtr_site_link link');
		$this->db->where("link.link_id = '" . $link . "'");
		$query = $this->db->get()->row();
		return $query;
	}
	/*Get PO Link with po_id and link_id_int*/
	public function getLinkPO($po_id, $link_id_int)
	{
		$this->db->select('poli.*');
		$this->db->from('t_trx_po_link poli');
		$this->db->where("poli.po_id", $po_id);
		$this->db->where("poli.link_id_int", $link_id_int);
		$query = $this->db->get()->row();
		return $query;
	}
	/*Insert site*/
	public function insert_site($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_mtr_site_xl', $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
		return $this->db->insert_id();
	}
	/*Insert PO Link */
	public function insert_po_link($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_trx_po_link', $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}
	/*Insert PO Link */
	public function update_po_link($data, $po_id, $link_id_int)
	{
		$this->db->trans_begin();
		$this->db
			->where('po_id', $po_id)
			->where('link_id_int', $link_id_int)
			->update('t_trx_po_link', $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}
	/*Insert Site Link*/
	public function insert_site_link($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_mtr_site_link', $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
		return $this->db->insert_id();
	}
	/*Update Site Link*/
	public function update_site_link($data, $id)
	{
		$this->db->trans_begin();
		$this->db
			->where('link_id_int', $id)
			->update('t_mtr_site_link', $data);


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
		// return $this->db->insert_id();
	}
}
