<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class pm_checklist_group extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mpm_checklist_group');
		$this->load->model('mpm_checklist');
		
	}
		
		
	########################################################################
	//Manag.pm_checklist_group
	public function index(){
		$data['judul'] = "PM Checklist Group";
		$data['content_id'] = "pm_checklist_group_view";
		$this->theme->view("pm_checklist_group_view", $data);
	}
	
	public function deleteData(){
		$tree_type = $this->input->post('tree_type');
		if ($tree_type == 'grouping' ){
		$this->mpm_checklist_group->deleteData();
		} else {
		$this->mpm_checklist_group->deleteDataChecklist();	
		}
	}
	
	public function loadData(){
		$this->mpm_checklist_group->loadData();
	}
	
	public function addeditdata(){
		$tree_type = $this->input->get('tree_type');
		
		if ($tree_type == 'grouping' ){
		
		$input = array('parent_id','group_name','order_number','is_last_child','description');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->get('id');
	
		$data['judul'] = "Add Checklist Group";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Checklist Group";
			$getDetail = $this->mpm_checklist_group->getpm_checklist_groupDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}
		}	
		
		$parentdisable = $data['id'];
		$data['parentx']= $this->showCombo($parentdisable);
		$this->load->view('r-pm_checklist_group', $data);
		
		} else {
		$input = array('checklist_id','checklist_group_id','checklist_name','option_id','order_number','status','has_photo','dedicated_photo','total_photo','interface','property_id','checklist_type','mandatory');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->get('id');
		$data['judul'] = "Add PM Checklist";
		if($data['id'] <> "") {
			$data['judul'] = "Edit PM Checklist";
			$getDetail = $this->mpm_checklist_group->getpm_checklistDetail( $data['id'] );
			$getDetail2 = $this->mpm_checklist_group->getpm_checklistDetail2( $data['id'] );
			if(empty($getDetail2)){
						$data['option'][] = array(
						  'option' => '',
						  'is_critical' => ''
						);
			}else{
				foreach ($getDetail2 as $row) {
							$data['option'][] = array(
							  'option' => $row['option'],
							  'is_critical' => $row['is_critical']
							);
				}
			}
			foreach($getDetail as $r){	
				foreach($input as $val){	 
					@$data[$val] = $r->$val;
				}
				
			}//print_r($getDetail);
			
		// }	
		
		$parentdisable = $data['id'];
		$data['parentx']= $this->showCombo($parentdisable);
		
		$this->load->view("r-pm_checklist", $data);
		}
		
	}}

public function addData(){
	
		$input = array('parent_id','group_name','order_number','is_last_child','description');
		foreach($input as $val){
			$data[$val] = "";
		}
	
	$data['parent_id'] = $this->input->get('id');
	
	$data['judul'] = "Add Checklist / Group";
	$data['parentx']= $this->showCombo($parentdisable);
		
	$this->load->view('r-pm_add_checklist_group', $data);
}
	
	function treeGrid(){
	
		$dt = $this->input->post();
		$dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
		$dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
		$dt['offset'] = ($dt['page']-1)*$dt['rows'];
		$q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : ''; 
		$data['q'] = strtolower($q);
	
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$result = array();
		
		if ($id == 0){
			$rss = $this->mpm_checklist_group->treeGrid($id,$this->input->get('group'));
			$result["total"] = $rss->num_rows();    			
		
			$rs = $this->mpm_checklist_group->treeGrido($dt['offset'],$dt['rows'],$this->input->get('group')); 
			$items = array();
			foreach ($rs->result() as $row) {
			if ($row->group_id != '') {
			$row->state = $this->mpm_checklist_group->has_child($row->group_id) ? 'closed' : 'open';
			$row->action = '<button type="button" class="btn-action glyphicons pencil btn-success"  onclick="addChecklistGroup('.$row->group_id.',\'grouping\')"><i></i></button><button type="button" class="btn-action glyphicons btn-success plus"  onclick="addChecklistGroup2('.$row->group_id.')"><i class="icon-plus"></i></button><button type="button" class="btn-action glyphicons remove_2 btn-danger"  onclick="return Document.deleteData('.$row->group_id.',\''.$row->tree_type.'\',\''.$row->tree_name.'\')"><i></i></button>';
			} else {
			$row->state =  'open';
			$row->iconCls ='tree-file2';
			$row->action = '<button type="button" class="btn-action glyphicons pencil btn-success"  onclick="addChecklistGroup('.$row->checklist_id.',\''.$row->tree_type.'\')"><i></i></button></button><button type="button" class="btn-action glyphicons remove_2 btn-danger"  onclick="return Document.deleteData('.$row->checklist_id.',\''.$row->tree_type.'\',\''.$row->tree_name.'\')"><i></i></button>';
			//$row->action = '<button type="button" class="btn-action glyphicons pencil btn-success"  onclick="addChecklistGroup('.$row->group_id.',\'grouping\')"><i></i></button><button type="button" class="btn-action glyphicons remove_2 btn-danger"  onclick="return Document.deleteData('.$row->group_id.',\''.$row->tree_type.'\',\''.$row->tree_name.'\')"><i></i></button><button type="button" class="btn-action glyphicons btn-success plus"  onclick="addChecklistGroup2('.$row->group_id.')"><i class="icon-plus"></i></button>';
			}

			array_push($items, $row);
			}
			$result["rows"] = $items;
		} else {
			$rs = $this->mpm_checklist_group->treeDetail($id); 	
		
			foreach ($rs->result() as $row) {
				
				if($row->tree_type =='checklist'){
					if($row->interface == 'android' ){
						// $row->iconCls ='tree-file-android';
						$row->iconCls ='tree-file2';
					} else {
						$row->iconCls ='tree-file3';
						
						// $row->iconCls ='tree-file-desktop';
					}
					//$row->iconCls ='tree-file2';
				} 
				
				if($row->group_id !=''){
					$row->state = $this->mpm_checklist_group->has_child($row->group_id) ? 'closed' : 'open';
				} else {
					$row->state = 'open' ;
				}
				if($row->group_id != ''){
				$row->action = '<button type="button" class="btn-action glyphicons pencil btn-success"  onclick="addChecklistGroup('.$row->group_id.',\''.$row->tree_type.'\')"><i></i></button><button type="button" class="btn-action glyphicons btn-success plus"  onclick="addChecklistGroup2('.$row->group_id.')"><i class="icon-plus"></i></button><button type="button" class="btn-action glyphicons remove_2 btn-danger"  onclick="return Document.deleteData('.$row->group_id.',\''.$row->tree_type.'\',\''.$row->tree_name.'\')"><i></i></button>';
				} else {
				$row->action = '<button type="button" class="btn-action glyphicons pencil btn-success"  onclick="addChecklistGroup('.$row->checklist_id.',\''.$row->tree_type.'\')"><i></i></button></button><button type="button" class="btn-action glyphicons remove_2 btn-danger"  onclick="return Document.deleteData('.$row->checklist_id.',\''.$row->tree_type.'\',\''.$row->tree_name.'\')"><i></i></button>';
				}
				array_push($result, $row);
			}
		}
	      echo json_encode($result);
    }

	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','parent','group','order','desc','last','group_key');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($parent == "" || $group == "" || $order == ""){
			$hasil = "Found Error: \n";
			
			if($parent == "" ){
				$hasil = "Parent Must Select \n";
			}
			if($group == "" ){
				$hasil = "Group empty \n";
			}
			if($order == "" ){
				$hasil = "Order empty \n";
			}
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mpm_checklist_group->saveData();
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		return;
	}
	########################################################################
	
	
	//Manag.GroupRole
	public function GroupRole( $group_id="" ){
		$data['judul'] = "Group Role";
		$data['content_id'] = "pm_checklist_group/f-group-role";
		
		$data['group'] = $this->mpm_checklist_group->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mpm_checklist_group->saveGroupRole();
	}
	
	
	########################################################################
	//dipindah dari controller pm_checklist
	
	public function saveDataChecklist(){

		//lakukan validasi di sini
		$input = array('id', 'checklist_group_id','descr','property_id','urutan','group_name','interface','option','is_critical','has_photo','dedicated','totphoto','name','mandatory');
		foreach ($input as $val)
			$$val = $this->input->post($val);		
			$isChild = $this->mpm_checklist_group->checkChild($name);
		$status = "ERROR";
		$hasil = "";
		
		if($name == "" ){
			$hasil = "Please Choose Checklist Group";
		}elseif($descr == ""){
			$hasil = "Please Input Description";
		}elseif($urutan == ""){
			$hasil = "Please Input Order";
		}elseif($urutan == 0){
			$hasil = "Order Cannot Be Filled zero";
		// }elseif(!isset($has_photo) or !isset($dedicated) ){
			// $hasil = "Total Photo Cannot Be Filled";
		 //}elseif($totphoto ==""){
			//$hasil = "Total Photo Must Be Filled";
		}elseif($interface == ""){
			$hasil = "Please Choose Interface";
		}elseif($property_id == ""){
			//$hasil = "Please Choose Property";
			$hasil = "Please Choose Answer Type";
		}
		
		
		else{
			//simpan data ke sini
            $sama=false;
            for($i=0;$i<count($option);$i++){
                
                for($z=$i+1;$z<count($option);$z++){
                        $option[$i]==$option[$z]?$sama=true:$sama=false;
                        if($sama)
                            break;
                }  
                if($sama)
                        break;
            }
            if(!$sama){
			     $status = "OK";
			     $this->mpm_checklist_group->saveDataChecklist();
            }else{
                $hasil="Option Must Be Different";
            }
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		return;
	}
	########################################################################

		public function showCombo($parentdisable,$parent=1,$level=0,&$cmb=array())
	{
		//$sql = "SELECT checklist_group_id group_id, group_name, parent_id FROM t_mtr_checklist_group WHERE checklist_group_id != 1 AND group_key = 'pm' AND status = 1 AND parent_id = $parent ORDER BY checklist_group_id";
		//$sql = "SELECT checklist_group_id group_id, group_name, parent_id FROM t_mtr_checklist_group WHERE  group_key = 'pm' AND status = 1 AND parent_id = $parent ORDER BY checklist_group_id";
		if(!empty($parentdisable)){
			$sql = "SELECT checklist_group_id group_id, group_name, parent_id FROM t_mtr_checklist_group WHERE  group_key = 'pm' AND status = 1 AND parent_id = $parent AND parent_id !=$parentdisable AND checklist_group_id !=$parentdisable ORDER BY checklist_group_id";
		} else {
			$sql = "SELECT checklist_group_id group_id, group_name, parent_id FROM t_mtr_checklist_group WHERE  group_key = 'pm' AND status = 1 AND parent_id = $parent ORDER BY checklist_group_id";
		}
		
        $result = $this->db->query($sql);
        foreach ($result->result_array() as $row) {
            $row['level'] = $level;
            $cmb[] = $row;
            
			//$sqlx = "SELECT checklist_group_id group_id FROM t_mtr_checklist_group WHERE checklist_group_id != 1 AND group_key = 'pm' AND status = 1 AND parent_id = ".$row['group_id']." ORDER BY checklist_group_id";
			$sqlx = "SELECT checklist_group_id group_id FROM t_mtr_checklist_group WHERE group_key = 'pm' AND status = 1 AND parent_id = ".$row['group_id']." ORDER BY checklist_group_id";
			
            $resultx = $this->db->query($sqlx);
            if ($resultx->num_rows() > 0) {
                $resultx->free_result();
                $this->showCombo($parentdisable,$row['group_id'],$level+1,$cmb);
            } else
                $resultx->free_result();
        }
        $result->free_result();
        
		return $cmb;
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */