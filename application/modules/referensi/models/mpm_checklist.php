<?php

class mpm_checklist extends CI_Model {

  function mpm_checklist() {
    parent::__construct();
  }

  public function treeGrido() {
    $dblokal = $this->load->database("default", TRUE);
    $q = "
		(SELECT
			'888' || checklist_group_id tree_id,
			checklist_group_id group_id,
			NULL AS checklist_id,
			group_name tree_name,
			order_number,
			NULL AS interface,
			status,
			'grouping' AS tree_type,
			parent_id parent_id
		FROM
			t_mtr_checklist_group
		WHERE
		parent_id = '598'
		AND status = '1'
		ORDER BY order_number asc)
		
			UNION ALL

		(SELECT
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
			LEFT JOIN t_mtr_checklist_group ON t_mtr_checklist.checklist_group = t_mtr_checklist_group.checklist_group_id
			WHERE
				t_mtr_checklist_group.status = 1
			AND t_mtr_checklist.checklist_group = '598'
			AND t_mtr_checklist.status = 1
			ORDER BY order_number asc)
		";

    // die($q);

    $query = $dblokal->query($q);
    return $query->result_array();
  }

  /*
    function getAllChecklistGroup($parent=598,$class='',$level='',&$cmb=array())	{

    $sql = "
    SELECT
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
    FROM t_mtr_checklist_group WHERE  group_key = 'prev.maintenance'  AND status = 1 AND parent_id = $parent

    UNION
    SELECT
    '999' || checklist_id tree_id,
    NULL AS group_id,
    checklist_id AS checklist_id,
    checklist_name tree_name,
    order_number,
    interface,
    status,
    'checklist' AS tree_type,
    checklist_group parent_id,
    NULL AS level
    FROM t_mtr_checklist WHERE status = 1 AND checklist_group = $parent

    ORDER BY order_number ASC";


    $result = $this->db->query($sql);


    foreach ($result->result_array() as $row) {

    if ($class ==''){
    $row['class'] = $row['parent_id'];
    } else {
    $row['class'] = $class.' '.$row['parent_id'];
    }


    if ($row['group_id'] !=''){


    $sqlx = "SELECT checklist_group_id group_id FROM t_mtr_checklist_group WHERE group_key = 'prev.maintenance' AND status = 1 AND parent_id = ".$row['group_id']." ORDER BY order_number ASC";
    } else {

    $sql_level = "SELECT level FROM t_mtr_checklist_group WHERE checklist_group_id = ".$row['parent_id'];
    $result_level = $this->db->query($sql_level);
    $level = $result_level->row_array();
    $row['level'] = $level['level']+1;
    }

    $cmb[] = $row;

    if ($row['group_id'] !=''){
    $group_id = $row['group_id'] ;
    } else {
    $sqlx = "SELECT checklist_id group_id FROM t_mtr_checklist WHERE status = 1 AND checklist_group = ".$row['parent_id'];
    $group_id = $row['parent_id'];
    }

    $resultx = $this->db->query($sqlx);
    if ($resultx->num_rows() > 0) {
    $resultx->free_result();
    $this->getAllChecklistGroup($group_id,$row['class'],$row['level'],$cmb);

    } else
    $resultx->free_result();
    }

    $result->free_result();

    return $cmb;
    } */

  //function getAllGroup($parent=598,$class='',$group_name_concat='',&$cmb=array())	{


  function getAllChecklistGroup($parent = 0, $parent_top = '', $class = '', $group_name_concat = '', $level = '', &$cmb = array()) {
    //$parent_top = 598;

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
        if ($parent_top != '') {
          $row['class'] = $parent_top . ' ' . $row['parent_id'];
        } else {
          $row['class'] = $row['parent_id'];
        }
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
        // $sqlx = "SELECT checklist_group_id group_id 
        // FROM t_mtr_checklist_group 
        // WHERE group_key = 'prev.maintenance' 
        // AND status = 1 
        // AND parent_id = ".$row['group_id']." 
        // ORDER BY order_number ASC";

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
				AND t_mtr_checklist.checklist_group = " . $row['group_id'] . " ";


        // die($sqlx);


        $resultx = $this->db->query($sqlx);
        if ($resultx->num_rows() > 0) {
          $resultx->free_result();
          //$this->getAllChecklistGroup($row['group_id'], $row['class'],$row['group_name_concat'],$cmb);
          $this->getAllChecklistGroup($row['group_id'], $parent_top, $row['class'], $row['group_name_concat'], $row['level'], $cmb);

          // if($first == 1){
          // $this->getAllGroup($parent, 0, $row['class'],$row['group_name_concat'],$cmb);
          // } else {
          // $this->getAllGroup($row['group_id'], 0, $row['class'],$row['group_name_concat'],$cmb);
          // }
        } else {
          $resultx->free_result();
        }
      }
    }

    $result->free_result();

    return $cmb;
  }

  function getAllGroup($parent = 0, $first = 0, $class = '', $group_name_concat = '', &$cmb = array()) {


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
		FROM t_mtr_checklist_group 
		WHERE  group_key = 'prev.maintenance'  
		AND status = 1";
    if ($first == 1) {
      $sql .= "AND checklist_group_id = $parent";
    } else {
      $sql .= "AND parent_id = $parent";
    }
    $sql .= "
		ORDER BY order_number ASC";

    $result = $this->db->query($sql);

    foreach ($result->result_array() as $row) {

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
        //$this->getAllGroup($row['group_id'], $row['class'],$row['group_name_concat'],$cmb);
        if ($first == 1) {
          $this->getAllGroup($parent, 0, $row['class'], $row['group_name_concat'], $cmb);
        } else {
          $this->getAllGroup($row['group_id'], 0, $row['class'], $row['group_name_concat'], $cmb);
        }
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
	AND group_key = 'prev.maintenance'  
		";

    $query = $dblokal->query($q);
    return $query->result_array();
  }

  function getComboOption() {
    $this->db->select('*');
    $this->db->where('option_key', 'pm_visit_type');
    $q = $this->db->get('t_mtr_checklist_options');
    return $q->result_array();
  }

  function getpm_checklistDetail($id) {
    $dblokal = $this->load->database("default", TRUE);
    $q = "SELECT *, aa.order_number FROM t_mtr_checklist aa 
                    JOIN t_mtr_checklist_group cc ON aa.checklist_group=cc.checklist_group_id
                    WHERE checklist_id = $id ";
    $query = $dblokal->query($q);

    return $query->result();
  }

  function getpm_checklistDetail2($id) {
    $dblokal = $this->load->database("default", TRUE);
    $q = "SELECT checklist_id, option_id, option, is_critical FROM t_mtr_checklist_answer WHERE checklist_id = $id ";
    $query = $dblokal->query($q);
    return $query->result_array();
  }

  function getpm_checklist_groupDetail($id) {
    $dblokal = $this->load->database("default", TRUE);
    $q = "SELECT * FROM t_mtr_checklist_group WHERE checklist_group_id = $id ";
    $query = $dblokal->query($q);
    return $query->result();
  }

  public function saveData() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('id', 'parent', 'group', 'order', 'desc', 'last', 'group_key', 'old_level', 'level', 'root_id');

    foreach ($input as $val)
      $$val = $this->input->post($val);

    $this->db->select('checklist_group_id')->select('order_number')
            ->where('parent_id', $parent)
            ->where('order_number >= ', $order);

    $obsolete_order = $this->db->get('t_mtr_checklist_group')->result_array();
    //echo json_encode(($obsolete_order));
    foreach ($obsolete_order as $key => $val) {
      $this->db->update('t_mtr_checklist_group', array('order_number' => $val['order_number'] + 1), array('checklist_group_id' => $val['checklist_group_id']));
    }

    if ($last == 'on') {
      $lasts = 1;
    } else {
      $lasts = 0;
    }

    $data = array(
        'parent_id' => $parent,
        'group_name' => $group,
        'order_number' => $order,
        //'description' => $desc,
        'is_last_child' => $lasts,
        'group_key' => $group_key,
        'status' => 1,
        'level' => $level,
        'root_id' => ($root_id != 'undefined') ? $root_id : NULL
    );

    if ($id == "") {
      $data['created_on'] = date("Y-m-d H:i:s");
      $data['created_by'] = $this->session->userdata('user_id');

      $dblokal->insert('t_mtr_checklist_group', $data);
    } else {
      $data['updated_on'] = date("Y-m-d H:i:s");
      $data['updated_by'] = $this->session->userdata('user_id');

      $dblokal->where(array('checklist_group_id' => $id));
      $dblokal->update('t_mtr_checklist_group', $data);

      if ($old_level != $level) {
        $update_level_target = $this->getChecklist_group_idLevel($id, $level);

        foreach ($update_level_target as $value) {
          if ($value['group_id'] != '') {
            $this->db->where('checklist_group_id', $value['group_id']);
            $this->db->update('t_mtr_checklist_group', array('level' => $value['level']));
          }
        }
      }
    }
  }

  public function checkChild($id) {
    $dblokal = $this->load->database("default", TRUE);
    $q = " SELECT count (parent_id) as jumlah from t_mtr_checklist_group where parent_id='" . (int) $id . "' and status='1'";
    $jumlah = 0;
    $query = $dblokal->query($q);
    foreach ($query->result_array() as $v) {
      $jumlah = $v['jumlah'];
    }
    return $jumlah;
  }

  public function saveDataChecklist() {
//    $this->db = $this->load->database("default", TRUE);
    $this->db->trans_start();
    $input = array('id', 'group_id', 'descr', 'property_id', 'urutan', 'name', 'interface', 'has_photo', 'dedicated', 'totphoto', 'option', 'mandatory');

    foreach ($input as $val)
      $$val = $this->input->post($val);

    $this->db->select('checklist_id')->select('order_number')
            ->where('checklist_group', $name)
            ->where('order_number >= ', $urutan);
    $obsolete_order = $this->db->get('t_mtr_checklist')->result_array();
    //echo json_encode(($obsolete_order));
    foreach ($obsolete_order as $key => $val) {
      $this->db->update('t_mtr_checklist', array('order_number' => $val['order_number'] + 1), array('checklist_id' => $val['checklist_id']));
    }
    //$total_photo =($totphoto == "") ? "0" : $totphoto;

    $data = array(
        'checklist_group' => $name,
        'checklist_name' => $descr,
        'checklist_type' => $property_id,
        'order_number' => $urutan,
        'has_photo' => $has_photo,
        'dedicated_photo' => $dedicated,
        'total_photo' => (int) $totphoto,
        'interface' => $interface,
        'mandatory' => $mandatory,
        'status' => 1
    );

    if ($id == "") {
      $is_critical = $this->input->post('is_critical');
      $data['created_on'] = date("Y-m-d H:i:s");
      $data['created_by'] = $this->session->userdata('user_id');
      $this->db->insert('t_mtr_checklist', $data);
      $this->db->insert_id();
      $idku = $this->db->insert_id();

      for ($i = 0; $i < count($option); $i++) {
        if ($option[$i] != "") {
          if ($is_critical[$i] == 1) {
            $is_critical[$i] = $is_critical[$i];
          } else {
            $is_critical[$i] = 0;
          }
          $queryInsert = "INSERT INTO t_mtr_checklist_answer (option,checklist_id,is_critical) Values ('" . $option[$i] . "','" . $idku . "','" . $is_critical[$i] . "')";
          $this->db->query($queryInsert);
        }
      }
    } else {
      $option = $this->input->post('option');
      $is_critical = $this->input->post('is_critical');
      $data['updated_on'] = date("Y-m-d H:i:s");
      $data['updated_by'] = $this->session->userdata('user_id');

      for ($i = 0; $i < count($option); $i++) {
        $queryInsert = "DELETE FROM t_mtr_checklist_answer WHERE checklist_id='" . $id . "'";
        $this->db->query($queryInsert);
      }

      $this->db->where(array('checklist_id' => $id));
      $this->db->update('t_mtr_checklist', $data);
      for ($i = 0; $i < count($option); $i++) {
        if ($is_critical[$i] == 1) {
          $is_critical[$i] = $is_critical[$i];
        } else {
          $is_critical[$i] = 0;
        }
        $queryInsert = "INSERT INTO t_mtr_checklist_answer (option,checklist_id,is_critical) Values ('" . $option[$i] . "','" . $id . "','" . $is_critical[$i] . "')";
        $this->db->query($queryInsert);
      }
    }
    $this->db->trans_complete();
    
    return $this->db->trans_status();
  }

  public function deleteData() {

    $dblokal = $this->load->database("default", TRUE);
    $input = array('id', 'lookup_key', 'lookup_desc', 'order', 'parent', 'group', 'order', 'desc', 'last', 'group_key');

    foreach ($input as $val) {
      $$val = $this->input->post($val);
    }


    $parent = $this->mglobal->showData('parent_id', 't_mtr_checklist_group', array('checklist_group_id' => $id));
    $order = $this->mglobal->showData('order_number', 't_mtr_checklist_group', array('checklist_group_id' => $id));

    $this->db->select('checklist_group_id')->select('order_number')
            ->where('parent_id', $parent)
            ->where('order_number >= ', $order);
    $dblokal->where(array('checklist_group_id' => $id));
    $dblokal->update('t_mtr_checklist_group', array('status' => 0));
    $obsolete_order = $this->db->get('t_mtr_checklist_group')->result_array();

    foreach ($obsolete_order as $key => $val) {
      $this->db->update('t_mtr_checklist_group', array('order_number' => $val['order_number'] - 1), array('checklist_group_id' => $val['checklist_group_id']));
    }

    $delete_target = $this->getChecklist_group_idLevel($id);

    foreach ($delete_target as $value) {
      if ($value['group_id'] != '') {
        $this->db->where('checklist_group_id', $value['group_id']);
        $this->db->update('t_mtr_checklist_group', array('status' => 0));
      } else {
        $this->db->where('checklist_id', $value['checklist_id']);
        $this->db->update('t_mtr_checklist', array('status' => 0));
      }
    }
  }

  function getChecklist_group_idLevel($parent = '', $level = '', &$cmb = array()) {

    $sql = "(SELECT 
		checklist_group_id group_id,
		NULL AS checklist_id
		FROM t_mtr_checklist_group 
		WHERE  group_key = 'prev.maintenance'  
		AND status = 1
		AND parent_id = $parent)";

    $sql .= " UNION ALL (SELECT
		NULL AS group_id,
		t_mtr_checklist.checklist_id
	FROM
		t_mtr_checklist 
	LEFT JOIN t_mtr_checklist_group ON t_mtr_checklist_group.checklist_group_id = t_mtr_checklist.checklist_group
	WHERE
	t_mtr_checklist.status = 1
	AND 
	group_key = 'prev.maintenance'  
	AND t_mtr_checklist.checklist_group = $parent)";

    $result = $this->db->query($sql);

    foreach ($result->result_array() as $row) {

      $row['level'] = $level + 1;

      $cmb[] = $row;

      if ($row['group_id'] != '') {

        $sqlx = "SELECT 
			checklist_group_id tree_id
			FROM t_mtr_checklist_group 
			WHERE group_key = 'prev.maintenance' 
			AND status = 1 
			AND parent_id = " . $row['group_id'] . " 
			UNION
			SELECT 
			t_mtr_checklist.checklist_id tree_id
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
          $this->getChecklist_group_idLevel($row['group_id'], $row['level'], $cmb);
        } else {
          $resultx->free_result();
        }
      }
    }

    $result->free_result();

    return $cmb;
  }

  public function getChecklist_group_idLevel222($parent = 0, $level = 0, &$cmb = array()) {
    $sql = "SELECT 
			checklist_group_id group_id,
			NULL AS checklist_id
			FROM t_mtr_checklist_group 
			WHERE  group_key = 'prev.maintenance' 
			AND status = 1 
			AND parent_id = $parent";

    $sql .= " UNION ALL 
			SELECT
				NULL AS group_id,
				t_mtr_checklist.checklist_id
			FROM
				t_mtr_checklist 
			LEFT JOIN t_mtr_checklist_group ON t_mtr_checklist_group.checklist_group_id = t_mtr_checklist.checklist_group
			WHERE
			t_mtr_checklist.status = 1
			AND 
			group_key = 'prev.maintenance'  
			AND t_mtr_checklist.checklist_group = $parent";

    $result = $this->db->query($sql);
    foreach ($result->result_array() as $row) {

      $row['level'] = $level;
      $cmb[] = $row;

      $sqlx = "SELECT checklist_group_id group_id FROM t_mtr_checklist_group WHERE group_key = 'prev.maintenance' AND status = 1 AND parent_id = " . $row['group_id'];

      $resultx = $this->db->query($sqlx);
      if ($resultx->num_rows() > 0) {
        $resultx->free_result();
        $this->getChecklist_group_idLevel($row['group_id'], $level + 1, $cmb);
      }
      else
        $resultx->free_result();
    }
    $result->free_result();
    return $cmb;
  }

  public function deleteDataChecklist() {
    $dblokal = $this->load->database("default", TRUE);
    $input = array('id', 'name', 'descr', 'urutan', 'property_id', 'urutan', 'interface');
    foreach ($input as $val)
      $$val = $this->input->post($val);

    $name = $this->mglobal->showData('checklist_group', 't_mtr_checklist', array('checklist_id' => $id));
    $order = $this->mglobal->showData('order_number', 't_mtr_checklist', array('checklist_id' => $id));


    $this->db->select('checklist_id')->select('order_number')
            ->where('checklist_group', $name)
            ->where('order_number >= ', $order);

    $dblokal->where(array('checklist_id' => $id));
    $dblokal->update('t_mtr_checklist', array('status' => 0));

    $obsolete_order = $this->db->get('t_mtr_checklist')->result_array();
    foreach ($obsolete_order as $key => $val) {

      $this->db->update('t_mtr_checklist', array('order_number' => $val['order_number'] - 1), array('checklist_id' => $val['checklist_id']));
    }
  }

  function getProperty() {
    $dblokal = $this->load->database("default", TRUE);
    $q = "SELECT checklist_type_id, type_name as tnama FROM t_mtr_checklist_type where status='1' order by tnama ASC";
    $query = $dblokal->query($q);
    return $query->result();
  }

}

?>