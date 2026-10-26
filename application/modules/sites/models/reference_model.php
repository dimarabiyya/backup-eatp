<?php

/*
@Rony Tampubolon*/
/**
* This Model is a Reference Model that return some Reference
* Date in Module Site Such as Region, Area and Site Type
*/
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
        foreach ($res as $v) {
            $data[$v->region_id] = $v->region_name;
        }
        return $data;
    }
    /*Get Area list from table t_mtr_cluster_ops*/
    public function areaList()
    {
        $this->db->select('area.area_id, area.area_name');
        $this->db->from('t_mtr_area area');
        $res = $this->db->get()->result();
        /*Declare temporary array variable*/
        /*Push each key and value to array*/
        $data = array();
        foreach ($res as $v) {
            $data[$v->area_id] = $v->area_name;
        }
        return $data;
    }
    /*Get Area list from table t_mtr_cluster_ops*/
    public function areaListByRegion($region_id)
    {
        $this->db->where("region_id", $region_id);
        $this->db->select('area.area_id, area.area_name');
        $this->db->from('t_mtr_area area');
        $res = $this->db->get()->result();
        /*Declare temporary array variable*/
        /*Push each key and value to array*/
        $data = array();
        foreach ($res as $v) {
            $data[$v->area_id] = $v->area_name;
        }
        return $data;
    }
    /*Get Site Type from table t_mtr_lookup where lookup_key = site_type*/
    public function siteTypeList()
    {
        $this->db->select('lookup.lookup_id, lookup.lookup_desc');
        $this->db->where("lookup.lookup_key ilike '%site_type%'");
        $this->db->from('t_mtr_lookup lookup');
        $res = $this->db->get()->result();
        /*Declare temporary array variable*/
        /*Push each key and value to array*/
        $data = array();
        foreach ($res as $v) {
            $data[$v->lookup_id] = $v->lookup_desc;
        }
        return $data;
    }
    /*Function to Find which Region with area_id*/
    public function getRegionByArea($area_id)
    {
        $this->db->select('area.*');
        $this->db->from('t_mtr_area area');
        $this->db->where("area.area_id = ".$area_id."");
        $query = $this->db->get()->row();
        return $query;
    }
    /*Function to Find which Region with area_name*/
    public function getAreaByName($area_name)
    {
        $this->db->select('area.*');
        $this->db->from('t_mtr_area area');
        $this->db->where("area.area_name ilike '".$area_name."'");
        $query = $this->db->get()->row();
        return $query;
    }

    /*Look Up for Site Type*/
    public function getSiteType($str)
    {
        $this->db->select('lookup.*');
        $this->db->from('t_mtr_lookup lookup');
        $this->db->where("lookup.lookup_desc ilike '".$str."'");
        $query = $this->db->get()->row();
        return $query;
    }
    /*Get Location Id list from table t_mtr_site_xl*/
    public function locationIdList()
    {
        $this->db->select('loc_id.location_id');
        $this->db->from('t_mtr_site_xl loc_id');
        $res = $this->db->get()->result();
        /*Declare temporary array variable*/
        /*Push each key and value to array*/
        $data = array();
        foreach ($res as $v) {
            $data[$v->location_id] = $v->location_id;
        }
        return $data;
    }
}
