<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class equipment_antenna extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mequipment_antenna');
	}
	
	########################################################################
	//Manag.equipment_antenna
	public function index(){
		$data['judul'] = "Equipment Antenna";
		$data['content_id'] = "equipment_antenna_view";
		$this->theme->view("equipment_antenna_view", $data);
	}
	
	public function deleteData(){
		$this->mequipment_antenna->deleteData();
	}
	
	public function loadData(){
		$this->mequipment_antenna->loadData();
	}
	
	public function viewdata(){
		$input = array('antenna_id','brand','model','band','hbwd','vbwd','etilt','polarization','vswr','impedance','gain','max_input_power','isolations','weight','dimension_long','dimension_height','dimension_width');
		foreach($input as $val){
			$data[$val] = "";
		}
		 $id = $this->input->post('id');
		 $results =  $this->mequipment_antenna->getequipment_antennaView($id);
		 foreach($results as $r){
			foreach($input as $val){	 
				$data[$val] = $r->$val;
			}
		}
		 
		 echo $this->load->view("equipment_antenna_view_view", $data);
	}
	
	public function addeditdata(){
		$input = array('antenna_id','brand','model','band','hbwd','vbwd','etilt','polarization','vswr','impedance','gain','max_input_power','isolations','weight','dimension_long','dimension_height','dimension_width');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Equipment Antenna";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Equipment Antenna";
			$getDetail = $this->mequipment_antenna->getequipment_antennaDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){	 
					$data[$val] = $r->$val;
				}
			}//print_r($getDetail);
		}	
		echo $this->load->view("r-equipment_antenna", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','brand','model','band','hbwd','vbwd','etilt','polarization','vswr','impedance','gain','max_input_power','isolations','weight','dimension_long','dimension_height','dimension_width');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		
		if($brand == "" || $model == "" || $band == "" || $hbwd == "" || $vbwd == "" || $etilt == "" || $polarization == "" || $vswr == "" || $impedance == "" || $gain == "" || $max_input_power == "" || $isolations == "" || $weight == "" || $dimension_long == "" || $dimension_height == "" || $dimension_width == "" ){
			$hasil = "Error: \n";
			if( $brand == "" ){
				$hasil = "Please Input Brand \n";
			}
			
			if( $model == "" ){
				$hasil = "Please Input Model \n";
			}
			
			if( $band == "" ){
				$hasil = "Please Input Band \n";
			}
			
			if( $hbwd == "" ){
				$hasil = "Please Input  \n";
			}
			
			if( $vbwd == "" ){
				$hasil = "Please Input  \n";
			}
			
			if( $etilt == "" ){
				$hasil = "Please Input  \n";
			}
			
			if( $polarization == "" ){
				$hasil = "Please Input Polarization \n";
			}
			
			if( $vswr == "" ){
				$hasil = "Please Input  \n";
			}
			
			if( $impedance == "" ){
				$hasil = "Please Input Group Name \n";
			}
			
			if( $gain == "" ){
				$hasil = "Please Input Gain \n";
			}
			
			if( $max_input_power == "" ){
				$hasil = "Please Input Max Input Power \n";
			}
			
			if( $isolations == "" ){
				$hasil = "Please Input Isolations \n";
			}
			if( $weight == "" ){
				$hasil = "Please Input Weight \n";
			}
			
			if( $dimension_long == "" ){
				$hasil = "Please Input Dimension Long \n";
			}
			
			if( $dimension_height == "" ){
				$hasil = "Please Input Dimension Height \n";
			}
			
			if( $dimension_width == "" ){
				$hasil = "Please Input Dimension Width \n";
			}
			
		// } elseif ( $brand != '') {
			// if ($id != '') {
				// if ($row = $this->core->view_row('t_mtr_site_l_equipment_rf_antenna_brand', array('UPPER(brand)' => strtoupper($brand), 'active' => 1, 'antenna_id !='=> (int) $id)) ) {
					// $hasil .= "Brand has already in used \n".$row['antenna_id'].'|'.$id;
				// }
			// } else {
				// if ($this->core->view_row('t_mtr_site_l_equipment_rf_antenna_brand', array('UPPER(brand)' => strtoupper($brand), 'active' => 1)) ) {
					// $hasil .= "Brand has already in used \n";
				// }
			// }
		// } elseif ( $model != '' && $this->core->view_row('t_mtr_site_l_equipment_rf_antenna_brand', array('UPPER(model)' => strtoupper($model), 'active' => 1, 'antenna_id !='=> (int) $id)) ) {
			// $hasil .= "Model has already in used \n";
			
			
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mequipment_antenna->saveData();
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
		$data['content_id'] = "equipment_antenna/f-group-role";
		
		$data['group'] = $this->mequipment_antenna->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mequipment_antenna->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */