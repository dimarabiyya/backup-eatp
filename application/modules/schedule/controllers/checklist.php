<?php

class checklist extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpm_model');
	}
	public function index(){
		$sch_id = 0;
		if($this->input->get("sch")){
			$sch_id = $this->input->get("sch");
		}
		$properties = $this->mpm_model->siteProperties();
		// bagian ini untuk ngurutin site properties berdasarkan group propertiesnya
	    $siteProperties = array();
	    $optionClass    = array();
	    $x   = 0;
	    foreach ($properties['group'] as $gg) {
	      $siteProperties[$x] = $gg;
	      $ci = 0;
	      foreach ($properties['item'] as $ii) {
	        if ($ii['group_id'] == $gg['group_id'])
	          {
	            $siteProperties[$x]['checklists'][$ci] = $ii;
	            $siteProperties[$x]['checklists'][$ci]['class'] = '';
	            foreach ($properties['options'] as $oo) {
	              if($oo['checklist_id'] == $ii['checklist_id']) {
	                  $siteProperties[$x]['checklists'][$ci]['class'] .= $oo['option_value']. ' ';
	              }
	            }
	              $ci++;
	          }
	      }
	      $x++;
	    }

	    $new = array();
	    foreach ($siteProperties as $a){
	      $new[$a['parent_id']][] = $a;
	    }
	    $checklistsGrouping = $this->treeGenerator($new, $new[598]);

	    $data["properties"] = $checklistsGrouping;
	    /*Pass Param Schedule ID for Edit Checlist*/
	    $data["schedule_id"] = $sch_id;
		$this->load->view('checklist', $data);
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

}