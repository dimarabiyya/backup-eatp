<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class tower_provider extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->load->model('mtower_provider');
	}
	
	########################################################################
	//Manag.tower_provider
	public function index(){
		$data['judul'] = "Tower Provider";
		$data['content_id'] = "tower_provider_view";
		$this->theme->view("tower_provider_view", $data);
	}
	
	public function deleteData(){
		$this->mtower_provider->deleteData();
	}
	
	public function loadData(){
		$this->mtower_provider->loadData();
	}
	
	public function addeditdata(){
		$input = array('no','tp_name','site_id','site_name','province','region','city','status','lattitude','longitude','site_type','tower_type','building_height_mtr','tower_height_mtr','address','shareable_status','site_code','remark','partner_id','kandidat');
		foreach($input as $val){
			$data[$val] = "";
		}
		$data['id'] = $this->input->post('id');
		$data['judul'] = "Add Page";
		if($data['id'] <> "") {
			$data['judul'] = "Edit Page";
			$getDetail = $this->mtower_provider->gettower_providerDetail( $data['id'] );
			foreach($getDetail as $r){
				foreach($input as $val){
					$data[$val] = @$r->$val;
				}
			}
		}	
		echo $this->load->view("r-tower_provider", $data);
	}
	
	public function saveData(){
		//lakukan validasi di sini
		$input = array('id','tp_name','site_id','site_name','province','region','city','status','lattitude','longitude','site_type','tower_type','building_height_mtr','tower_height_mtr','address','shareable_status','site_code','remark','partner_id','kandidat');
		foreach ($input as $val)
			$$val = $this->input->post($val);
		$status = "ERROR";
		$hasil = "";
		if($tp_name == "" ){
			if( $tp_name == "" ){
				$hasil = "Please Input Tower Provider Name.";
			}
		} elseif ($site_id == "") {
			$hasil = "Please select Site ID.";
		}else{
			//simpan data ke sini
			$status = "OK";
			$this->mtower_provider->saveData();
		}
		echo json_encode(array(
			'status'=> $status,
			'hasil'=>	$hasil
		));
		return;
	}
	function getCityy($id){
		 $tmp    = '';
        $data   = $this->mtower_provider->get_city_by_state($id);
        if(!empty($data)){
            $tmp .= "<option value=''>Choose City </option>";
            foreach($data as $row) {   
                $tmp .= "<option value='".$row->lokasi_id."'>".$row->nama."</option>";
            }
        } else {
            $tmp .= "<option value=''>Choose City </option>";
        }
        die($tmp);
	}
	function getSiteName($id){
		 $tmp    = '';
        $data   = $this->mtower_provider->getSiteNamebyID($id);
        if(!empty($data)){
            $tmp .= "";
            foreach($data as $row) {   
                $tmp .= "<input class='span12' name='site_name' type='text' value='".$row->site_name."' readonly/> ";
            }
        } else {
            $tmp .= "";
        }
        die($tmp);
	}
	########################################################################
	//Manag.GroupRole
	public function GroupRole( $group_id="" ){
		$data['judul'] = "Group Role";
		$data['content_id'] = "tower_provider/f-group-role";
		
		$data['group'] = $this->mtower_provider->getGroup();
		$data['group_id'] = $group_id;
		
		$this->load->view("template/smashing", $data);
	}
	
	public function saveGroupRole(){
		$this->mtower_provider->saveGroupRole();
	}
	########################################################################
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */