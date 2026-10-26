<?php

class Journey_models extends CI_Model {
    // table name
    private $t_mtr_site_general= 't_mtr_site_general';
 
    function Person(){
        parent::Model();
    }
    // get number of persons in database
    function count_all(){
        return $this->db->count_all($this->t_mtr_site_general);
    }
    // get persons with paging
    function get_paged_list($limit = 10, $offset = 0){
        $this->db->order_by('site_id','asc');
        return $this->db->get($this->t_mtr_site_general, $limit, $offset);
    }
    // get person by id
    function get_by_id($id){
        $this->db->where('site_id', $id);
        return $this->db->get($this->t_mtr_site_general);
    }
    // add new person
    function save($person){
        $this->db->insert($this->t_mtr_site_general, $person);
        return $this->db->insert_id();
    }
    // update person by id
    function update($id, $person){
        $this->db->where('site_id', $id);
        $this->db->update($this->t_mtr_site_general, $person);
    }
    // delete person by id
    function delete($id){
        $this->db->where('site_id', $id);
        $this->db->delete($this->t_mtr_site_general);
    }
}