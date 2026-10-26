<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class pm_checklist extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->mglobal->is_logged_in();
    $this->load->model('mpm_checklist');
  }

  public function index() {
    $data['judul'] = "PM Checklist / Groups";
    $data['content_id'] = "pm_checklist_view";

    // $getAllChecklistGroup = $this->mpm_checklist->getAllChecklistGroup(598);	
    // echo "<pre>";
    // print_r($getAllChecklistGroup);
    // echo "</pre>";
    // die();

    $data['treeGrido'] = $this->mpm_checklist->treeGrido();
    $this->theme->view("pm_checklist_view", $data);
  }

  public function checklisGroup() {
    $parent = $this->input->post('id');
    $parent_top = 598;
    // $getAllGroup = $this->mpm_checklist->getAllGroup($parent,1);		
    // $getAllChecklist = $this->mpm_checklist->getAllChecklist();
    // $getAllChecklistGroup = $this->checklisGroupAddLevel($getAllGroup,$getAllChecklist,$parent);

    $getAllChecklistGroup = $this->mpm_checklist->getAllChecklistGroup($parent, $parent_top);


    // echo "<pre>";
    // print_r($getAllChecklisGroup);
    // echo "</pre>";
    // die();
//die($parent);
    // echo "<pre>";
    // print_r($getAllChecklisGroup);
    // echo "</pre>";
    // die();

    $table = '';

    foreach ($getAllChecklistGroup as $key => $value) {
      $table .='<tr id="' . $value['tree_id'] . '" class="' . $value['class'] . '">';

      if ($value['group_id'] != '') {
        $table .='<td><a style="text-decoration: none; color: black;" onclick="showHidden(' . $value['tree_id'] . ',' . $value['group_id'] . ')">' . str_repeat('&nbsp;', $value['level'] * 5) . '<img style="margin: -4px 0 0 4px" src="' . base_url() . '/assets/images/tree_open_folder_icons.png">&nbsp;' . $value['tree_name'] . '</a></td><td>' . $value['tree_type'] . '</td>';
      } else {
        if ($value['interface'] == 'android') {
          $table .='<td>' . str_repeat('&nbsp;', $value['level'] * 5) . '<img style="margin: -4px 0 0 4px" src="' . base_url() . '/assets/images/interface-android.png"> ' . $value['tree_name'] . '</td><td>' . $value['tree_type'] . '</td>';
        } else {
          $table .='<td>' . str_repeat('&nbsp;', $value['level'] * 5) . '<img style="margin: -4px 0 0 4px" src="' . base_url() . '/assets/images/interface-desktop.png"> ' . $value['tree_name'] . '</td><td>' . $value['tree_type'] . '</td>';
        }
      }

      if ($value['group_id'] != '') {
        $table .='<td style="text-align:center;"><button type="button" class="btn-action glyphicons pencil btn-success"  onclick="addChecklistGroup(' . $value['group_id'] . ',\'' . $value['tree_type'] . '\')"><i></i></button> <button type="button" class="btn-action glyphicons btn-success plus"  onclick="addChecklistGroup2(' . $value['group_id'] . ')"><i class="icon-plus"></i></button> <button type="button" class="btn-action glyphicons remove_2 btn-danger"  onclick="return Document.deleteData(' . $value['group_id'] . ',\'' . $value['tree_type'] . '\',\'' . $value['tree_name'] . '\')"><i></i></button></td>';
      } else {
        $table .='<td style="text-align:center;"><button type="button" class="btn-action glyphicons pencil btn-success"  onclick="addChecklistGroup(' . $value['checklist_id'] . ',\'' . $value['tree_type'] . '\')"><i></i></button> </button><button type="button" class="btn-action glyphicons remove_2 btn-danger"  onclick="return Document.deleteData(' . $value['checklist_id'] . ',\'' . $value['tree_type'] . '\',\'' . $value['tree_name'] . '\')"><i></i></button></td>';
      }

      $table .='</tr>';
    }

    echo $table;
    // return $table;
  }

  /*
    public function checklisGroupAddLevel($getAllGroup,$getAllChecklist,$parent)
    {

    foreach ($getAllGroup as $Array3) {

    $group_id	= $Array3['group_id'];
    $level 		= $Array3['level'];
    $class 		= $Array3['class'];
    $group_name_concat = $Array3['group_name_concat'];
    $cmb[] 		= $Array3;

    if($group_id != $parent){
    $cmb[] 		= $Array3;
    }

    foreach ($getAllChecklist as $Array2) {

    if ($Array2['parent_id'] == $group_id) {

    $Array2['level'] = $level+1;
    $Array2['class'] = $class.' '.$group_id;

    $Array2['group_name_concat'] = $group_name_concat.' > '.$Array2['tree_name'];

    $cmb[] = $Array2;
    }
    }
    }

    return $cmb;
    }
   */

  public function checklisGroupAddLevel($getAllGroup, $getAllChecklist, $parent) {

    foreach ($getAllGroup as $Array3) {

      $group_id = $Array3['group_id'];
      $level = $Array3['level'];
      $class = $Array3['class'];
      $group_name_concat = $Array3['group_name_concat'];
      //$cmb[] 		= $Array3;

      if ($group_id != $parent) {
        $cmb[] = $Array3;
      }

      foreach ($getAllChecklist as $Array2) {

        if ($Array2['parent_id'] == $group_id) {

          $Array2['level'] = $level + 1;
          $Array2['class'] = $class . ' ' . $group_id;

          $Array2['group_name_concat'] = $group_name_concat . ' > ' . $Array2['tree_name'];

          $cmb[] = $Array2;
        }
      }
    }

    return $cmb;
  }

  public function addeditdata() {
    $data['parent_top'] = 598;
    $tree_type = $this->input->get('tree_type');

    if ($tree_type == 'grouping') {

      $input = array('parent_id', 'group_name', 'order_number', 'is_last_child', 'description', 'level');
      foreach ($input as $val) {
        $data[$val] = "";
      }
      $data['id'] = $this->input->get('id');

      $data['judul'] = "Add Checklist Group";
      if ($data['id'] <> "") {
        $data['judul'] = "Edit Checklist Group";

        $getDetail = $this->mpm_checklist->getpm_checklist_groupDetail($data['id']);
        foreach ($getDetail as $r) {
          foreach ($input as $val) {
            $data[$val] = $r->$val;
          }
        }
      }

      $parentdisable = $data['id'];
      $data['parentx'] = $this->showCombo($parentdisable);

      // echo "<pre>";
      // print_r($data['parentx']);
      // echo "</pre>";
      // die();

      $this->load->view('r-pm_edit_checklist_group', $data);
    } else { //checklist
      $input = array('checklist_id', 'checklist_group_id', 'checklist_name', 'option_id', 'order_number', 'status', 'has_photo', 'dedicated_photo', 'total_photo', 'interface', 'property_id', 'checklist_type', 'mandatory');
      foreach ($input as $val) {
        $data[$val] = "";
      }
      $data['id'] = $this->input->get('id');
      $data['judul'] = "Add PM Checklist";
      if ($data['id'] <> "") {
        $data['judul'] = "Edit PM Checklist";
        $getDetail = $this->mpm_checklist->getpm_checklistDetail($data['id']);
        $getDetail2 = $this->mpm_checklist->getpm_checklistDetail2($data['id']);
        if (empty($getDetail2)) {
          $data['option'][] = array(
              'option' => '',
              'is_critical' => ''
          );
        } else {
          foreach ($getDetail2 as $row) {
            $data['option'][] = array(
                'option' => $row['option'],
                'is_critical' => $row['is_critical']
            );
          }
        }
        foreach ($getDetail as $r) {
          foreach ($input as $val) {
            @$data[$val] = $r->$val;
          }
        }//print_r($getDetail);
        // }	

        $parentdisable = $data['id'];
        $data['parentx'] = $this->showCombo($parentdisable);

        $this->load->view("r-pm_edit_checklist", $data);
      }
    }
  }

  public function showCombo($parentdisable = '', $parent = 598, $level = 0, $group_name_concat = '', &$cmb = array()) {
    if (!empty($parentdisable)) {
      $sql = "SELECT checklist_group_id group_id, group_name, parent_id, root_id FROM t_mtr_checklist_group WHERE  group_key = 'prev.maintenance' AND status = 1 AND parent_id = $parent AND parent_id !=$parentdisable AND checklist_group_id !=$parentdisable ORDER BY order_number"; //change order by andedi 20141203
    } else {
      $sql = "SELECT checklist_group_id group_id, group_name, parent_id, root_id FROM t_mtr_checklist_group WHERE  group_key = 'prev.maintenance' AND status = 1 AND parent_id = $parent ORDER BY order_number"; //change order by andedi 20141203
    }

    $result = $this->db->query($sql);
    foreach ($result->result_array() as $row) {

      if ($group_name_concat == '') {
        $row['group_name_concat'] = $row['group_name'];
      } else {
        $row['group_name_concat'] = $group_name_concat . ' > ' . $row['group_name'];
      }

      if ($row['root_id'] == null) {
        $row['root_id'] = $row['group_id'];
      }

      $row['level'] = $level;
      $cmb[] = $row;
      $sqlx = "SELECT checklist_group_id group_id FROM t_mtr_checklist_group WHERE group_key = 'prev.maintenance' AND status = 1 AND parent_id = " . $row['group_id'] . " ORDER BY order_number"; //change order by andedi 20141203
      $resultx = $this->db->query($sqlx);

      if ($resultx->num_rows() > 0) {
        $resultx->free_result();
        $this->showCombo($parentdisable, $row['group_id'], $level + 1, $row['group_name_concat'], $cmb);
      }
      else
        $resultx->free_result();
    }
    $result->free_result();

    return $cmb;
  }

  public function addData() {
    $data['parent_top'] = 598;
    $input = array('parent_id', 'group_name', 'order_number', 'is_last_child', 'description');

    foreach ($input as $val) {
      $data[$val] = "";
    }

    $data['parent_id'] = $this->input->get('id');
    $data['judul'] = "Add Checklist / Group";
    $data['parentx'] = $this->showCombo();

    $this->load->view('r-pm_add_checklist_or_group', $data);
  }

  public function searchDataForm() {
    $data['judul'] = "Search Checklist / Group";
    $data['q'] = $this->input->get('q');
    // die($data['q']);
    $this->load->view('r-pm_search_checklist_or_group', $data);
  }

  public function searchData() {

    $q = $this->input->post('q');

    $parent = 598;
    // $getAllGroup = $this->mpm_checklist->getAllGroup($parent);
    // $getAllChecklist = $this->mpm_checklist->getAllChecklist();
    // $getAllChecklistGroup = $this->checklisGroupAddLevel($getAllGroup,$getAllChecklist);

    $getAllChecklistGroup = $this->mpm_checklist->getAllChecklistGroup($parent);


    // echo "<pre>";
    // print_r($getAllChecklistGroup);
    // echo "</pre>";
    // die();

    $lower_q = $this->db->escape_str(strtolower($q));
    $sql = "
		SELECT '888' || checklist_group_id tree_id, checklist_group_id, NULL AS checklist_id, 'grouping' tree_type  FROM t_mtr_checklist_group 
		WHERE  LOWER(group_name) LIKE '%$lower_q%'
		UNION 
		SELECT
		'999' || checklist_id tree_id, NULL AS checklist_group_id, checklist_id, 'checklist' tree_type FROM t_mtr_checklist
		WHERE  LOWER(checklist_name) LIKE '%$lower_q%'
		";
    $searchDb = $this->db->query($sql);
    $rsSearchDb = $searchDb->result_array();

    // echo "<pre>";
    // print_r($rsSearchDb);
    // echo "</pre>";
    // die();

    foreach ($rsSearchDb as $value11) {

      foreach ($getAllChecklistGroup as $value22) {
        if ($value11['tree_id'] == $value22['tree_id']) {
          $resultSearch[] = $value22;
        }
      }
    }
    // echo "<pre>";
    // print_r($resultSearch);
    // echo "</pre>";
    // die();


    $table = '';

    foreach ($resultSearch as $key => $value) {

      $table .='<tr class="' . $value['class'] . '">';

      $table .='<td><b>' . $value['group_name_concat'] . '</b></td>';

      if ($value['group_id'] != '') {
        $table .='<td><a style="text-decoration: none; color: black;" onclick="showHidden(' . $value['tree_id'] . ',' . $value['group_id'] . ')"><img style="margin: -4px 0 0 4px" src="' . base_url() . '/assets/images/tree_open_folder_icons.png">&nbsp;' . $value['tree_name'] . '</a></td><td>' . $value['tree_type'] . '</td>';
      } else {
        if ($value['interface'] == 'android') {
          $table .='<td><img style="margin: -4px 0 0 4px" src="' . base_url() . '/assets/images/interface-android.png">' . $value['tree_name'] . '</td><td>' . $value['tree_type'] . '</td>';
        } else {
          $table .='<td><img style="margin: -4px 0 0 4px" src="' . base_url() . '/assets/images/interface-desktop.png">' . $value['tree_name'] . '</td><td>' . $value['tree_type'] . '</td>';
        }
      }

      if ($value['group_id'] != '') {
        $table .='<td><button type="button" class="btn-action glyphicons pencil btn-success"  onclick="addChecklistGroup(' . $value['group_id'] . ',\'' . $value['tree_type'] . '\')"><i></i></button><button type="button" class="btn-action glyphicons btn-success plus"  onclick="addChecklistGroup2(' . $value['group_id'] . ')"><i class="icon-plus"></i></button><button type="button" class="btn-action glyphicons remove_2 btn-danger"  onclick="return Document.deleteData(' . $value['group_id'] . ',\'' . $value['tree_type'] . '\',\'' . $value['tree_name'] . '\')"><i></i></button><button type="button" class="btn-action glyphicons eye_open btn-info"  onclick="lookupSearchResult(' . $value['tree_id'] . ',\'' . $value['class'] . '\')"><i></i></button></td>';
      } else {
        $table .='<td><button type="button" class="btn-action glyphicons pencil btn-success"  onclick="addChecklistGroup(' . $value['checklist_id'] . ',\'' . $value['tree_type'] . '\')"><i></i></button></button><button type="button" class="btn-action glyphicons remove_2 btn-danger"  onclick="return Document.deleteData(' . $value['checklist_id'] . ',\'' . $value['tree_type'] . '\',\'' . $value['tree_name'] . '\')"><i></i></button><button type="button" class="btn-action glyphicons eye_open btn-info"  onclick="lookupSearchResult(' . $value['tree_id'] . ',\'' . $value['class'] . '\')"><i></i></button></td>';
      }

      $table .='</tr>';
    }

    echo $table;
  }

  public function saveData() {
    //lakukan validasi di sini
    $input = array('id', 'parent', 'group', 'order', 'desc', 'last', 'group_key');
    foreach ($input as $val)
      $$val = $this->input->post($val);
    $status = "ERROR";
    $hasil = "";
    if ($parent == "" || $group == "" || $order == "") {
      $hasil = "Found Error: \n";

      if ($parent == "") {
        $hasil = "Parent Must Select \n";
      }
      if ($group == "") {
        $hasil = "Group empty \n";
      }
      if ($order == "") {
        $hasil = "Order empty \n";
      }
    } else {
      //simpan data ke sini
      $status = "OK";
      $this->mpm_checklist->saveData();
    }
    echo json_encode(array(
        'status' => $status,
        'hasil' => $hasil
    ));
    return;
  }

  public function saveDataChecklist() {

    //lakukan validasi di sini
    $input = array('id', 'checklist_group_id', 'descr', 'property_id', 'urutan', 'group_name', 'interface', 'option', 'is_critical', 'has_photo', 'dedicated', 'totphoto', 'name', 'mandatory');
    foreach ($input as $val)
      $$val = $this->input->post($val);
    $isChild = $this->mpm_checklist->checkChild($name);
    $status = "ERROR";
    $hasil = "";

    if ($name == "") {
      $hasil = "Please Choose Checklist Group";
    } elseif ($descr == "") {
      $hasil = "Please Input Description";
    } elseif ($urutan == "") {
      $hasil = "Please Input Order";
    } elseif ($urutan == 0) {
      $hasil = "Order Cannot Be Filled zero";
      // }elseif(!isset($has_photo) or !isset($dedicated) ){
      // $hasil = "Total Photo Cannot Be Filled";
      //}elseif($totphoto ==""){
      //$hasil = "Total Photo Must Be Filled";
    } elseif ($interface == "") {
      $hasil = "Please Choose Interface";
    } elseif ($property_id == "") {
      //$hasil = "Please Choose Property";
      $hasil = "Please Choose Answer Type";
    } else {
      //simpan data ke sini
      $sama = false;
      for ($i = 0; $i < count($option); $i++) {

        for ($z = $i + 1; $z < count($option); $z++) {
          $option[$i] == $option[$z] ? $sama = true : $sama = false;
          if ($sama)
            break;
        }
        if ($sama)
          break;
      }
      if (!$sama) {
        $status = "OK";
        $this->mpm_checklist->saveDataChecklist();
      } else {
        $hasil = "Option Must Be Different";
      }
    }
    echo json_encode(array(
        'status' => $status,
        'hasil' => $hasil
    ));
    return;
  }

  public function deleteData() {
    $tree_type = $this->input->post('tree_type');
    $id = $this->input->post('id');

    if ($tree_type == 'grouping') {
      // $delete_target = $this->getChecklist_group_idLevel($id);
      // print_r($delete_target);
      //$this->mpm_checklist->deleteData($delete_target); 
      $this->mpm_checklist->deleteData();
    } else {
      $this->mpm_checklist->deleteDataChecklist();
    }
  }

  public function getChecklist_group_idLevel($parent = 0, $level = 0, &$cmb = array()) {
    $sql = "SELECT 
			checklist_group_id group_id
			FROM t_mtr_checklist_group 
			WHERE  group_key = 'prev.maintenance' 
			AND status = 1 
			AND parent_id = $parent 
			ORDER BY checklist_group_id";

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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */