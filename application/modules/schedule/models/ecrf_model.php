<?php

class ecrf_model extends CI_Model
{

    public function insert($data)
    {
        $this->db->trans_begin();

        $this->db->insert('t_trx_ecrf', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $this->db->insert_id();
    }

    public function insert_photo($data)
    {
        $this->db->trans_begin();

        $this->db->insert('t_trx_ecrf_photo', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function update_photo($id, $data)
    {
        $this->db->trans_begin();

        $this->db->insert('t_trx_ecrf_photo', $data);

        $this->db
            ->where('id', $id)
            ->update('t_trx_ecrf_photo', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function get_ecrf_edit($id, $site_type)
    {
        $data = $this->db->where('visit_id', $id)->where('site_type', $site_type)->from('t_trx_ecrf')->get()->result_array();
        return $data;
    }

    public function delete_ecrf_by_visit_id($visit_id)
    {
        $this->db->trans_begin();

        $this->db->where('visit_id', $visit_id)->delete('t_trx_ecrf');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function delete_ecrf_photo_by_visit_id($visit_id)
    {
        $this->db->trans_begin();

        $this->db->where('visit_id', $visit_id)->delete('t_trx_ecrf_photo');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function insert_approval($visit_id, $data)
    {
        $this->db->trans_begin();

        $query = $this->db->from('t_trx_ecrf_approval')->where('visit_id', $visit_id)->get();
        if ($query->num_rows() > 0) {
            $this->db->where('visit_id', $visit_id);
            $this->db->update('t_trx_ecrf_approval', $data);
        } else {
            $this->db->insert('t_trx_ecrf_approval', $data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
};
