<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class pm_checklist_template extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->mglobal->is_logged_in();
    $this->load->model('mpm_checklist_template');
  }

  //Manag.pm_checklist_template
  public function index() {
    $data['judul'] = "Preventive Checklist Template";
    $data['content_id'] = "pm_checklist_template_view";
    $data['getOption'] = $this->mpm_checklist_template->getOption();
    $data['combo_option'] = $this->mpm_checklist_template->getComboOption();
    
    $this->theme->view("pm_checklist_template_view", $data);
  }

  public function checklisGroup() {
    $id = $this->input->post('id');
    $getAllChecklisGroup = $this->mpm_checklist_template->getAllChecklistGroup($id);
    $checklisGroupSelected = array_unique($this->checklisGroupSelected($id));

    foreach ($getAllChecklisGroup as $row) {
      foreach ($checklisGroupSelected as $row2) {
        if ($row['tree_id'] == $row2) {
          $checklist_group[] = $row;
        }
      }
    }

    $table = '';
    foreach ($checklist_group as $key => $value) {
      $table .='<tr id="' . $value['tree_id'] . '" class="' . $value['class'] . '">';
      if ($value['group_id'] != '') {
        $table .='<td><a style="text-decoration: none; color: black;" onclick="showHidden(' . $value['tree_id'] . ',' . $value['group_id'] . ')">' . str_repeat('&nbsp;', $value['level'] * 5) . '<img style="margin: -4px 0 0 4px" src="' . base_url() . '/assets/images/tree_open_folder_icons.png">&nbsp;' . $value['tree_name'] . '</a></td><td>' . $value['tree_type'] . '</td>';
      } else {
        if ($value['interface'] == 'android') {
          $table .='<td>' . str_repeat('&nbsp;', $value['level'] * 5) . '<img style="margin: -4px 0 0 4px" src="' . base_url() . '/assets/images/interface-android.png">&nbsp;' . $value['tree_name'] . '</td><td>' . $value['tree_type'] . '</td>';
        } else {
          $table .='<td>' . str_repeat('&nbsp;', $value['level'] * 5) . '<img style="margin: -4px 0 0 4px" src="' . base_url() . '/assets/images/interface-desktop.png">&nbsp;' . $value['tree_name'] . '</td><td>' . $value['tree_type'] . '</td>';
        }
      }

      $table .='<td></td>';
      $table .='</tr>';
    }

    echo $table;
  }

  public function checklisGroupAddLevel($getAllGroup, $getAllChecklist, $id) {

    foreach ($getAllGroup as $Array3) {

      $group_id = $Array3['group_id'];
      $level = $Array3['level'];
      $class = 'parent' . $id . ' ' . $Array3['class'];
      $Array3['class'] = $class;
      $group_name_concat = $Array3['group_name_concat'];
      $cmb[] = $Array3;

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

  public function checklisGroupSelected($id) {

    $sql = "SELECT	t_mtr_checklist_group.checklist_group_id,t_mtr_checklist_group.parent_id,t_mtr_checklist.checklist_id
                FROM t_mtr_checklist_options
                LEFT JOIN t_mtr_checklist_template ON t_mtr_checklist_template.option_id = t_mtr_checklist_options.option_id
                LEFT JOIN t_mtr_checklist ON t_mtr_checklist.checklist_id = t_mtr_checklist_template.checklist_id
                LEFT JOIN t_mtr_checklist_group ON t_mtr_checklist_group.checklist_group_id = t_mtr_checklist.checklist_group
                WHERE t_mtr_checklist_options.option_key = 'pm_visit_type' AND t_mtr_checklist_options.status = '1' 
                AND t_mtr_checklist_options.option_id = '$id'";

    $result = $this->db->query($sql);
    foreach ($result->result_array() as $row) {
      $checklist_group[] = $row['checklist_group_id'];
      $checklist_id[] = $row['checklist_id'];
    }

    $checklist_group_unique = array_unique($checklist_group);
    $data = $this->checklisGroupSelectedDetail($checklist_group_unique);
    $datax = array_unique($data);
    $new_array = array_diff($datax, array(""));

    $xxx = array_merge($checklist_group_unique, $new_array);
    $yyy = array_diff($xxx, array("0"));
    $zzz = array_diff($yyy, array("1"));

    foreach ($zzz as $value) {
      $result_zzz[] = '888' . $value;
    }

    foreach ($checklist_id as $value) {
      $result_checklist_id[] = '999' . $value;
    }

    $final_result = array_merge($result_zzz, $result_checklist_id);

    return $final_result;
  }

  public function checklisGroupSelectedDetail($checklist_group_id_array = '', &$cmb = array()) {

    $checklist_group_id_array = array_diff($checklist_group_id_array, array(""));

    foreach ($checklist_group_id_array as $row) {
      $sqlx = "SELECT t_mtr_checklist_group.parent_id 
                    FROM t_mtr_checklist_group 
                    WHERE t_mtr_checklist_group.group_key = 'prev.maintenance' AND t_mtr_checklist_group.checklist_group_id = $row 
                    ORDER BY checklist_group_id";
      $resultx = $this->db->query($sqlx);
      if ($resultx->num_rows() > 0) {
        $rowx = $resultx->row_array();
        $cmb[] = $rowx['parent_id'];
        $cmbx[] = $rowx['parent_id'];
      }
    };

    $checklist_group_unique = array_unique($cmbx);
    $new_array = array_diff($checklist_group_unique, array(""));

    if (count($new_array) > 1) {
      $this->checklisGroupSelectedDetail($new_array, $cmb);
    }

    return $cmb;
  }

  public function addEditData() {
    $data['content_id'] = "r-pm_checklist_template_edit";
    $option_id = $this->uri->segment(4);

    if ($option_id != '') {
      $data['addEditData'] = 'edit';
      $option = $this->mpm_checklist_template->getOptionValue($option_id);
      $data['option_id'] = $option_id;
      $data['class'] = $option->option_value;
      $data['title'] = "Preventive Maintenance Checklist Template";
      $data['option_label'] = $option->option_label;
    } else {
      $data['addEditData'] = 'add';
    }

    $combo_option = $this->mpm_checklist_template->getComboOption();
    $data['combo_option'] = $combo_option;
    $properties = $this->mpm_checklist_template->siteProperties($option_id); //edited andedi 2015-04-17

    $siteProperties = array();
    $optionClass = array();
    $x = 0;
    foreach ($properties['group'] as $gg) {
      $siteProperties[$x] = $gg;
      $ci = 0;
      foreach ($properties['item'] as $ii) {
        if ($ii['group_id'] == $gg['group_id']) {
          $siteProperties[$x]['checklists'][$ci] = $ii;
          $siteProperties[$x]['checklists'][$ci]['class'] = '';
          $siteProperties[$x]['checklists'][$ci]['mandatory'] = 0; //edited andedi 20141208
          $siteProperties[$x]['checklists'][$ci]['has_photo'] = 0; //edited andedi 20141208
          foreach ($properties['options'] as $oo) {
//            die(print_r($properties['options']));
//                        $siteProperties[$x]['checklists'][$ci]['mandatory'] = 0; //edited andedi 20141205
//                        $siteProperties[$x]['checklists'][$ci]['has_photo'] = 0; //edited andedi 20141205
            if ($oo['checklist_id'] == $ii['checklist_id']) {
              $siteProperties[$x]['checklists'][$ci]['class'] .= $oo['option_value'] . ' ';
//              $siteProperties[$x]['checklists'][$ci]['mandatory'] = $oo['mandatory']; //edited andedi 20141112
//              $siteProperties[$x]['checklists'][$ci]['has_photo'] = $oo['has_photo']; //edited andedi 20141112
              if ($oo['mandatory'] != NULL) {
                $siteProperties[$x]['checklists'][$ci]['mandatory'] = $oo['mandatory']; //edited andedi 20141112
                $siteProperties[$x]['checklists'][$ci]['has_photo'] = $oo['has_photo']; //edited andedi 20141112
              }
            }
          }
          $ci++;
        }
      }
      $x++;
    }

    $new = array();
    foreach ($siteProperties as $a) {
      $new[$a['parent_id']][] = $a;
    }

    $checklistsGrouping = $this->treeGenerator($new, $new[598]);
    $data["properties"] = $checklistsGrouping;
    $this->load->view("template/smashing", $data);
  }

  function treeGenerator(&$list, $parent) {
    $tree = array();
    foreach ($parent as $k => $l) {
      if (isset($list[$l['group_id']])) {
        $l['sub_group'] = $this->treeGenerator($list, $list[$l['group_id']]);
      }
      $tree[] = $l;
    }
    return $tree;
  }

  public function saveData() {
    $input = array('option_id', 'checklist_item');
    foreach ($input as $val) {
      $$val = $this->input->post($val);
    }
    $save = $this->mpm_checklist_template->saveData($option_id, $checklist_item);
    if ($save)
      echo 'ok';
    else
      echo 'failed';
  }

  public function searchDataForm() {

    $data['judul'] = "Search Checklist / Group";
    $data['combo_option'] = $this->mpm_checklist_template->getComboOption();
    $data['q'] = $this->input->get('q');
    $data['option_id'] = $this->input->get('option_id');

    $this->load->view('r-pm_search_template', $data);
  }

  public function searchData() {
    $q = $this->input->post('q');
    $option_id = $this->input->post('option_id');
    $parent = 598;
    $checklisGroupSelected = array_unique($this->checklisGroupSelected($option_id));
    $getAllChecklisGroup = $this->mpm_checklist_template->getAllChecklistGroup($option_id);

    foreach ($getAllChecklisGroup as $value33) {
      foreach ($checklisGroupSelected as $value44) {
        if ($value33['tree_id'] == $value44) {
          $getAllChecklisGroupSelected[] = $value33;
        }
      }
    }

    $lower_q = $this->db->escape_str(strtolower($q));
    $sql = "SELECT '888' || checklist_group_id tree_id, checklist_group_id, NULL AS checklist_id, 'grouping' tree_type
                FROM t_mtr_checklist_group 
		WHERE  LOWER(group_name) LIKE '%$lower_q%'
		UNION 
		SELECT '999' || checklist_id tree_id, NULL AS checklist_group_id, checklist_id, 'checklist' tree_type
                FROM t_mtr_checklist
		WHERE  LOWER(checklist_name) LIKE '%$lower_q%'
		";
    $searchDb = $this->db->query($sql);
    $rsSearchDb = $searchDb->result_array();

    foreach ($rsSearchDb as $value11) {
      foreach ($getAllChecklisGroupSelected as $value22) {
        if ($value11['tree_id'] == $value22['tree_id']) {
          $resultSearch[] = $value22;
        }
      }
    }

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
        $table .='<td><button type="button" class="btn-action glyphicons eye_open btn-info"  onclick="lookupSearchResult(' . $value['tree_id'] . ',\'' . $value['class'] . '\')"><i></i></button></td>';
      } else {
        $table .='<td><button type="button" class="btn-action glyphicons eye_open btn-info"  onclick="lookupSearchResult(' . $value['tree_id'] . ',\'' . $value['class'] . '\')"><i></i></button></td>';
      }

      $table .='</tr>';
    }

    echo $table;
  }

  public function deleteData() {
    $option_id = $this->input->post('option_id');
    $delete = $this->mpm_checklist_template->deleteData($option_id);
    $status = '';
    $message = '';
    if ($delete) {
      $status = 'success';
      $message = 'Data deleted.';
    } else {
      $status = 'error';
      $message = 'Saving data failed.';
    }
    echo json_encode(array('status' => $status, 'message' => $message));
  }

  public function addedittype() {
    $option_id = $this->input->get('option_id');
    $data['hasil'] = '';
    $data['error'] = '';
    $data['judul'] = "Add Checklist Template Type";
    $order_list = array();
    $max = $this->mpm_checklist_template->getMaxOrderNumber();

    if ($option_id == '') { //add new
      $max = $max + 1;
      $data['checklist_template'] = $this->mpm_checklist_template->getChecklistTemplate(0);
      $data['checklist_template']['order_number'] = $max;
    } else { //edit
      $data['checklist_template'] = $this->mpm_checklist_template->getChecklistTemplate($option_id);
      $data['judul'] = "Edit Checklist Template Type";
    }

    for ($i = 1; $i <= $max; $i++) {
      $order_list[$i] = $i;
    }

    $data['order_list'] = $order_list;
    
    $this->load->view("r-pm_checklist_template_type", $data);
  }

  public function saveDatatype() {
    //lakukan validasi di sini
    $name = $this->input->post('name');
    $value = $this->input->post('value');
    $order_number = $this->input->post('order_number');
    $status = "ERROR";
    $hasil = "";

    if ($name == "" || $value == "" || $order_number == "" || $order_number == "0") {
      $hasil = " <ul> ";
      if ($name == "") {
        $hasil .= "<li> Template Name cannot be empty </li>";
      }
      if ($value == "") {
        $hasil .= "<li> Template Value cannot be empty </li>";
      }
      if ($order_number == "") {
        $hasil .= "<li> Order Number cannot be empty </li>";
      }
      if ($order_number == "0") {
        $hasil .= "<li> Order Number cannot filled zero </li>";
      }
      $hasil .= '</ul>';
    } else {
      $save = $this->mpm_checklist_template->saveDataType(); //simpan data ke sini
      if ($save) {
        $status = "OK";
      }
    }
    echo json_encode(array(
        'status' => $status,
        'hasil' => $hasil
    ));
    return;
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */