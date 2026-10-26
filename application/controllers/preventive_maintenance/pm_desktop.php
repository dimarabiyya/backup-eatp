<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pm_desktop extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('mglobal');
    $this->load->model('schedule/mschedule');
    $this->load->model('schedule/mpm', 'pm');
  }

  public function index()
  {
    $data['title']      = "Desktop"; 
    $data['content_id'] = "preventive_maintenance/f_pm_desktop";
    $userTerritory      = $this->mglobal->userTerritory($this->session->userdata('user_id'));
    $territory = array();
    $x   = 0;
    foreach ($userTerritory['region'] as $rr) {
      $territory[$x] = $rr;
      foreach ($userTerritory['cluster'] as $cc) {
        if ($cc['region_id'] == $rr['region_id'])
          {
            $territory[$x]['cluster'][] = $cc;
          }
      }
      $x++;
    }
    $data["territory"]  = $territory;
    $data["region"]     = $this->session->userdata('user_region');
    $this->load->view("template/smashing", $data);
  }

  public function grid()
  {
    $page = isset($_POST['page']) ? intval( $_POST['page'] ) : 1;
    $rows = isset($_POST['rows']) ? intval( $_POST['rows'] ) : 10;
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'vv.visit_date';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';

    $region_id  = $this->input->get_post('region_id');
    $cluster_id = $this->input->get_post('cluster_id');
    $datefrom   = $this->input->get_post('datefrom');
    $dateto     = $this->input->get_post('dateto');
    $keyword    = $this->input->get_post('keywords');

    $r = $this->pm->pmdesktopgrid($region_id, $cluster_id, $datefrom, $dateto, $keyword, $page, $rows, $sort, $order);
    echo json_encode($r);
  }

  public function detail()
  {
    $visit_id = $this->input->post('visit_id');
    if(!$visit_id){
      redirect(base_url().'pm/desktop');
    }
    $data['title']      = "ATP Desktop"; 
    $data['content_id'] = "preventive_maintenance/i_pm_desktop";
    $visitinfo  = $this->pm->pmdesktopdata($visit_id);
    $properties = $this->pm->siteProperties();

    $data['siteinfo'] = $visitinfo['header'];
    $x = 0;

    $answers_by_checklist = array();
    foreach ($visitinfo['answer'] as $answer) {
        $answers_by_checklist[$answer['checklist_id']][] = $answer;
    }
    foreach ($visitinfo['checklists'] as $checklistitem) {
      $data['checklist_item'][$x] = $checklistitem;
      $data['checklist_item'][$x]['answer'] = array();
      if (isset($answers_by_checklist[$checklistitem['checklist_id']])) {
          $data['checklist_item'][$x]['answer'] = $answers_by_checklist[$checklistitem['checklist_id']];
      }
      if ( ! empty($visitinfo['answer'])) {
          $data['checklist_item'][$x]['view'] = $this->generate_view($checklistitem, $data['checklist_item'][$x]['answer']);
      }
      $x++;
    }
    // print_r($data['checklist_item']); 

    $siteProperties = array();
    $x   = 0;
    $items_by_group = array();
    foreach ($data['checklist_item'] as $ii) {
        $items_by_group[$ii['checklist_group']][] = $ii;
    }
    foreach ($properties['group'] as $gg) {
      $siteProperties[$x] = $gg;
      if (isset($items_by_group[$gg['group_id']])) {
          $siteProperties[$x]['checklists'] = $items_by_group[$gg['group_id']];
      }
      $x++;
    }

    $new = array();
    foreach ($siteProperties as $a){
      $new[$a['parent_id']][] = $a;
    }
    $data['checklistsgrouping'] = $this->treeGenerator($new, $new[598]);

    // $data['checklist'] = $checklistsGrouping;

    $this->load->view("template/smashing", $data);

  }

  function treeGenerator(&$list, $parent){
    $tree = array();
    foreach ($parent as $k=>$l) {
      if(isset($list[$l['group_id']])){
        $l['sub_group'] = $this->treeGenerator($list, $list[$l['group_id']]);
      }
      $tree[] = $l;
    } 
    return $tree;
  }

  private function generate_view($data, $answer)
  {
    $vclass = '';
    if($data['mandatory'] == 1){
      $vclass = "required";
    }
    $d = '';
    switch ($data['checklist_type']) {
      case 'textbox': $d = $this->_gen_textbox($data, $answer, $vclass); break;
      case 'textarea': $d = $this->_gen_textarea($data, $answer, $vclass); break;
      case 'datepicker': $d = $this->_gen_datepicker($data, $answer, $vclass); break;
      case 'radiobutton': $d = $this->_gen_radiobutton($data, $answer, $vclass); break;
    }
    if($data['has_photo'] == 1) {
      $d .= '<div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="input-append">
                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
              </div>
            </div>';
    }
    return $d;
  }

  private function _gen_textarea($data, $answer, $vclass) {
    $s = '<textarea name="'.$data["checklist_id"].'" id="checklist_id_'.$data["checklist_id"].'" '.$vclass.' >'.$data["pm_result"].'</textarea>';
    return $s;
  }
  private function _gen_textbox($data, $answer, $vclass) {
    $s = '<input type="text" name="'.$data["checklist_id"].'" id="checklist_id_'.$data["checklist_id"].'" value="'.$data["pm_result"].'" '.$vclass.' />';
    return $s;
  }
  private function _gen_datepicker($data, $answer, $vclass) {
    $s = '<input name="'.$data["checklist_id"].'" id="checklist_id_'.$data["checklist_id"].'" class="datepicker" '.$vclass.' />'.$data["pm_result"];
    return $s;
  }
  private function _gen_checkbox($data, $answer, $vclass) {
    foreach ($answer as $k) {
      if($k['option'] == $data['pm_result']) {
        $s .= '<input type="radio" name="'.$data["checklist_id"].'" value="'.$k["option"].'" id="option_id_'.$k["option_id"].'" checked '.$vclass.' /> 
             <label for="option_id_'.$k["option_id"].'">'.$k["option"].'</labe>';
      }
      else {
        $s .= '<input type="radio" name="'.$data["checklist_id"].'" value="'.$k["option"].'" id="option_id_'.$k["option_id"].'" /> 
             <label for="option_id_'.$k["option_id"].'" '.$vclass.'>'.$k["option"].'</labe>';
      }
    }
    return $s;
  }

  private function _gen_radiobutton($data, $answer, $vclass) {
    $s = '';
    foreach ($answer as $k) {
      if($k['option'] == $data['pm_result']) {
        $s .= '<label for="option_id_'.$k["option_id"].'">
              <input type="radio" name="'.$data["checklist_id"].'" value="'.$k["option"].'" id="option_id_'.$k["option_id"].'" checked '.$vclass.' /> 
             '.$k["option"].'</label>';
      }
      else {
        $s .= '<label for="option_id_'.$k["option_id"].'">
              <input type="radio" name="'.$data["checklist_id"].'" value="'.$k["option"].'" id="option_id_'.$k["option_id"].'" '.$vclass.' /> 
             '.$k["option"].'</label>';
      }
    }
    return $s;
  }
  private function _gen_photo($data, $answer, $vclass)
  {
    # code...
  }

  public function save()
  {
    $visit_id = $this->input->post('visit_id');
    $checklist = $this->input->post();
    unset($checklist['visit_id']);

    $s = false;
    foreach ($checklist as $key) {
      if(!empty($key)) {
        $s = true;
      }
    }

    if($s){
      foreach ($checklist as $key) {
        if(empty($key))
          unset($key);
        }
      $r = $this->pm->savepmDesktop($visit_id, $checklist);

      // $this->session->set_flashdata('name', 'value');
    }
    redirect(base_url().'pm/desktop');
  }

}

/* End of file pm_desktop.php */
/* Location: ./application/controllers/preventive_maintenance/pm_desktop.php */