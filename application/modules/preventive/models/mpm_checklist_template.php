<?php

class mpm_checklist_template extends CI_Model {
    private $_date_now;
    private $_user_now;
    const OPTION_KEY = 'pm_visit_type';

    function mpm_checklist_template() {
        parent::__construct();
        $this->_date_now = date('Y-m-d H:i:s');
        $this->_user_now = $this->session->userdata('user_id');
    }

    function getAllGroup($parent = 598, $class = '', $group_name_concat = '', &$cmb = array()) {

        $sql = "SELECT 
		'888' || checklist_group_id tree_id,
		checklist_group_id group_id,
		NULL AS checklist_id,
		group_name tree_name,
		order_number,
		NULL AS interface,
		status,
		'grouping' AS tree_type,
		parent_id parent_id,
		level 
		FROM t_mtr_checklist_group WHERE  group_key = 'prev.maintenance'  AND status = 1 AND parent_id = $parent ORDER BY order_number ASC";


        $result = $this->db->query($sql);

        foreach ($result->result_array() as $row) {

            //$row['level'] = $level;
            if ($class == '') {
                $row['class'] = $row['parent_id'];
            } else {
                $row['class'] = $class . ' ' . $row['parent_id'];
            }

            if ($group_name_concat == '') {
                $row['group_name_concat'] = $row['tree_name'];
            } else {
                $row['group_name_concat'] = $group_name_concat . ' > ' . $row['tree_name'];
            }

            $cmb[] = $row;

            $sqlx = "SELECT checklist_group_id group_id FROM t_mtr_checklist_group WHERE group_key = 'prev.maintenance' AND status = 1 AND parent_id = " . $row['group_id'] . " ORDER BY order_number ASC";
            //die($sqlx);

            $resultx = $this->db->query($sqlx);
            if ($resultx->num_rows() > 0) {
                $resultx->free_result();
                $this->getAllGroup($row['group_id'], $row['class'], $row['group_name_concat'], $cmb);
            }
            else
                $resultx->free_result();
        }

        $result->free_result();

        return $cmb;
    }

    public function getAllChecklist() {
        $dblokal = $this->load->database("default", TRUE);
        $q = "SELECT
		'999' || t_mtr_checklist.checklist_id tree_id,
		NULL AS group_id,
		t_mtr_checklist.checklist_id,
		t_mtr_checklist.checklist_name tree_name,
		t_mtr_checklist.order_number,
		t_mtr_checklist.interface,
		t_mtr_checklist.status,
		'checklist' AS tree_type,
		t_mtr_checklist.checklist_group parent_id
	FROM
		t_mtr_checklist 
	LEFT JOIN t_mtr_checklist_group ON t_mtr_checklist_group.checklist_group_id = t_mtr_checklist.checklist_group
	WHERE
	t_mtr_checklist.status = 1
	AND t_mtr_checklist_group.group_key = 'prev.maintenance'
		";

        $query = $dblokal->query($q);
        return $query->result_array();
    }

    function getAllChecklistGroup($id, $parent = 598, $class = '', $group_name_concat = '', $level = '', &$cmb = array()) {
        $parent_top = 'parent' . $id;

        $sql = "(SELECT 
		'888' || checklist_group_id tree_id,
		checklist_group_id group_id,
		NULL AS checklist_id,
		group_name tree_name,
		order_number,
		NULL AS interface,
		status,
		'grouping' AS tree_type,
		parent_id parent_id,
		level 
		FROM t_mtr_checklist_group 
		WHERE  group_key = 'prev.maintenance'  
		AND status = 1
		AND parent_id = $parent
		ORDER BY  order_number ASC)";

        $sql .= " UNION ALL (SELECT
		'999' || t_mtr_checklist.checklist_id tree_id,
		NULL AS group_id,
		t_mtr_checklist.checklist_id,
		t_mtr_checklist.checklist_name tree_name,
		t_mtr_checklist.order_number,
		t_mtr_checklist.interface,
		t_mtr_checklist.status,
		'checklist' AS tree_type,
		t_mtr_checklist.checklist_group parent_id,
		NULL AS LEVEL
	FROM
		t_mtr_checklist 
	LEFT JOIN t_mtr_checklist_group ON t_mtr_checklist_group.checklist_group_id = t_mtr_checklist.checklist_group
	WHERE
	t_mtr_checklist.status = 1
	AND 
	group_key = 'prev.maintenance'  
	AND t_mtr_checklist.checklist_group = $parent
		ORDER BY order_number ASC)";

        //die($sql);

        $result = $this->db->query($sql);

        foreach ($result->result_array() as $row) {

            if ($class == '') {
                $row['class'] = $parent_top . ' ' . $row['parent_id'];
            } else {
                $row['class'] = $class . ' ' . $row['parent_id'];
            }

            if ($group_name_concat == '') {
                $row['group_name_concat'] = $row['tree_name'];
            } else {
                $row['group_name_concat'] = $group_name_concat . ' > ' . $row['tree_name'];
            }

            if ($row['level'] == '') {
                $row['level'] = $level + 1;
            }

            $cmb[] = $row;

            if ($row['group_id'] != '') {

                $sqlx = "SELECT checklist_group_id tree_id
			FROM t_mtr_checklist_group 
			WHERE group_key = 'prev.maintenance' 
			AND status = 1 
			AND parent_id = " . $row['group_id'] . " 
			 UNION
				SELECT t_mtr_checklist.checklist_id tree_id
				FROM t_mtr_checklist 
				LEFT JOIN t_mtr_checklist_group ON t_mtr_checklist_group.checklist_group_id = t_mtr_checklist.checklist_group
				WHERE
				t_mtr_checklist.status = 1
				AND 
				group_key = 'prev.maintenance'  
				AND t_mtr_checklist.checklist_group = " . $row['group_id'];

                $resultx = $this->db->query($sqlx);
                if ($resultx->num_rows() > 0) {
                    $resultx->free_result();
                    $this->getAllChecklistGroup($id, $row['group_id'], $row['class'], $row['group_name_concat'], $row['level'], $cmb);
                } else {
                    $resultx->free_result();
                }
            }
        }

        $result->free_result();

        return $cmb;
    }

    public function siteProperties($option_id=0) {
        $this->db->select('cc.checklist_id, cc.checklist_group group_id, cc.checklist_name, cc.interface, cc.has_photo AS has_photo_def, cc.mandatory AS mandatory_def'); //edited andedi 20141112
        $this->db->join('t_mtr_checklist_group gg', 'gg.checklist_group_id = cc.checklist_group');
        $this->db->where('gg.parent_id !=', 0);
        $this->db->where('gg.group_key', 'prev.maintenance');
        $this->db->where('gg.status', 1);
        $this->db->where('cc.status', 1);
        $this->db->order_by('cc.checklist_group, cc.order_number');
        $q = $this->db->get('t_mtr_checklist cc');
        $data["item"] = $q->result_array();

        $this->db->select('checklist_group_id group_id, group_name, parent_id');
        //$this->db->where('checklist_group_id !=', 1);
        $this->db->where('group_key', 'prev.maintenance');
        $this->db->where('status', 1);
//	    $this->db->order_by('checklist_group_id');
        $this->db->order_by('order_number');
        $q = $this->db->get('t_mtr_checklist_group');
        $data["group"] = $q->result_array();

        $this->db->select('rr.checklist_id, oo.option_id, oo.option_value, oo.locked, rr.mandatory, rr.has_photo'); //edited andedi 20141105
        $this->db->join('t_mtr_checklist_template rr', 'rr.option_id = oo.option_id');
        $this->db->where('oo.option_key', 'pm_visit_type');
        $this->db->where('oo.status', 1);
        if ($option_id!=0) $this->db->where('oo.option_id', $option_id);  //edited andedi 2015-04-17
        $q = $this->db->get('t_mtr_checklist_options oo');
        $data["options"] = $q->result_array();
//        die($this->db->last_query());
        return $data;
    }

    function getOptionValue($id) {
        $this->db->select('*');
        //$this->db->select('option_value');
        $this->db->where('status', 1);
        $this->db->where("option_id = '$id'");
        $q = $this->db->get('t_mtr_checklist_options');
        return $q->row();
    }

    function getComboOption() {
        $this->db->select('*');
        $this->db->where('option_key', 'pm_visit_type');
        $this->db->where('status', 1);
        $q = $this->db->get('t_mtr_checklist_options');
        return $q->result_array();
    }

    public function saveData($option_id, $checklist_item) {
        $query = false;
        $dblokal = $this->load->database("default", TRUE);
        $queryDelete = "DELETE FROM t_mtr_checklist_template WHERE option_id='" . $option_id . "'";
        $dblokal->query($queryDelete);

        foreach ($checklist_item as $key => $value):
            $val_arr = explode('|', $value);
            $data = array(
                'checklist_id' => $val_arr[0],
                'option_id' => $option_id,
                'status' => 1,
                'mandatory' => $val_arr[1],
                'has_photo' => $val_arr[2]
            );
            $data['created_on'] = date("Y-m-d H:i:s");
            $data['created_by'] = $this->session->userdata('user_id');
            $query = $dblokal->insert('t_mtr_checklist_template', $data);
        endforeach;
        return $query;
    }

    public function deleteData($option_id) { //edited by andedi 20141208
        $data = array('status' => 0);
        $this->db->trans_start();
        
        //get order_number data deleted
        $this->db->select('order_number');
        $condition = array('status'=>1, 'option_id'=>$option_id);
        $this->db->where($condition);
        $order_number = $this->db->get('t_mtr_checklist_options')->row()->order_number;
        
        //soft delete (master)
        $this->db->where('option_id', $option_id);
        $this->db->update('t_mtr_checklist_options', $data);
        
        //Re-order
        $max = $this->getMaxOrderNumber();
        for ($i=$order_number+1; $i<=$max; $i++) {
            $condition = array(
                'order_number'  => $i,
                'option_key'    => self::OPTION_KEY,
                'status'        => 1
            );
            $this->db->where($condition);
            $this->db->set('order_number', 'order_number-1', FALSE);
            $this->db->update('t_mtr_checklist_options');
        }
        
        //soft delete (detail)
        $this->db->where('option_id', $option_id);
        $this->db->update('t_mtr_checklist_template', $data);
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    function getOption($id = 0, $group = "") {
        $group = 1; //$this->session->userdata('group');
        $dblokal = $this->load->database("default", TRUE);
        //$q = "select * FROM t_mtr_checklist_options WHERE status='1' And option_key='pm_visit_type' order by order_number ASC";

        $q = "
                    SELECT
                    t_mtr_checklist_options.option_id,t_mtr_checklist_options.option_label,count(t_mtr_checklist_template.option_id) count
                    FROM
                    t_mtr_checklist_options
                    LEFT JOIN t_mtr_checklist_template ON t_mtr_checklist_template.option_id = t_mtr_checklist_options.option_id
                    WHERE
                    t_mtr_checklist_options.option_key = 'pm_visit_type'
                    AND t_mtr_checklist_options.status = '1'
                    -- AND t_mtr_checklist_template.status = '1'
                    GROUP BY t_mtr_checklist_options.option_id
                    ORDER BY
                    order_number ASC
		";

        $query = $dblokal->query($q);
        return $query->result();
    }

    public function saveDataType() {
        $this->db->trans_start();
        $input = array('option_id', 'option_key', 'name', 'value', 'order_number');
        
        foreach ($input as $val)
            $$val = $this->input->post($val);
        
        $data = array(
            'option_key'    => $option_key,
            'option_value'  => $value,
            'option_label'  => $name,
            'order_number'  => $order_number,
            'status'        => 1
        );
        
        if ($option_id=='') { //new
            //Re-order
            $max = $this->getMaxOrderNumber();
            for ($i=$max; $i>=$order_number; $i--) {
                $condition = array(
                    'order_number'  => $i,
                    'option_key'    => self::OPTION_KEY,
                    'status'        => 1
                );
                $this->db->where($condition);
                $this->db->set('order_number', 'order_number+1', FALSE);
                $this->db->update('t_mtr_checklist_options');
            }
            //insert new data
            $data['created_on'] = $this->_date_now;
            $data['created_by'] = $this->_user_now;
            $this->db->insert('t_mtr_checklist_options', $data);
            
        } else { //edit
            //get order_no before update
            $this->db->select('order_number');
            $condition = array('status'=>1, 'option_id'=>$option_id);
            $this->db->where($condition);
            $order_number_old = $this->db->get('t_mtr_checklist_options')->row()->order_number;

            if ($order_number_old < $order_number) { //Re-order from low to high number
                for ($i=$order_number_old+1; $i<=$order_number; $i++) {
                    $condition = array(
                        'order_number'  => $i,
                        'option_key'    => self::OPTION_KEY,
                        'status'        => 1
                    );
                    $this->db->where($condition);
                    $this->db->set('order_number', 'order_number-1', FALSE);
                    $this->db->update('t_mtr_checklist_options');
                }
            } elseif ($order_number_old > $order_number) { //Re-order from high to low number
                for ($i=$order_number_old-1; $i>=$order_number; $i--) {
                    $condition = array(
                        'order_number'  => $i,
                        'option_key'    => self::OPTION_KEY,
                        'status'        => 1
                    );
                    $this->db->where($condition);
                    $this->db->set('order_number', 'order_number+1', FALSE);
                    $this->db->update('t_mtr_checklist_options');
                }
            }

            //update data
            $data['updated_on'] = $this->_date_now;
            $data['updated_by'] = $this->_user_now;
            $this->db->where(array('option_id' => $option_id));
            $this->db->update('t_mtr_checklist_options', $data);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
//        $dblokal = $this->load->database("default", TRUE);
//        $input = array('id', 'option_key', 'name', 'value', 'order_number');
//        foreach ($input as $val)
//            $$val = $this->input->post($val);
//
//        $this->db->select('option_id')->select('order_number')
//                ->where('option_key', $option_key)
//                ->where('order_number >= ', $order_number);
//        $obsolete_order = $this->db->get('t_mtr_checklist_options')->result_array();
//
//        foreach ($obsolete_order as $key => $val) {
//            $this->db->update('t_mtr_checklist_options', array('order_number' => $val['order_number'] + 1), array('option_id' => $val['option_id']));
//        }
//
//        $data = array(
//            'option_key'    => $option_key,
//            'option_value'  => $value,
//            'option_label'  => $name,
//            'order_number'  => $order_number,
//            'status'        => 1
//            );
//
//        if ($id == "") {
//            $data['created_on'] = date("Y-m-d H:i:s");
//            $data['created_by'] = $this->session->userdata('user_id');
//
//            $dblokal->insert('t_mtr_checklist_options', $data);
//        } else {
//            $data['updated_on'] = date("Y-m-d H:i:s");
//            $data['updated_by'] = $this->session->userdata('user_id');
//
//            $dblokal->where(array('option_id' => $id));
//            $dblokal->update('t_mtr_checklist_options', $data);
//        }
    }
    
    public function getMaxOrderNumber() { //andedi 20141208
        $condition = array(
            'status'        => 1,
            'option_key'    => self::OPTION_KEY
        );
        $this->db->where($condition);
        $max = $this->db->select_max('order_number', 'max_order')->get('t_mtr_checklist_options')->row()->max_order;
        return $max;
    }
    
    public function getChecklistTemplate($option_id) { //andedi 20141208
        $this->db->select('option_id, option_value, option_label, order_number');
        $condition = array(
            'option_id' =>$option_id,
            'status'    => 1
        );
        $this->db->where($condition);
        $query = $this->db->get('t_mtr_checklist_options');
        return $query->row_array();
    }

}

?>