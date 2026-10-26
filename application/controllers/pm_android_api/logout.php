<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	 //error code normal -> misal json 100
	 //error code keyword salah -> 200
	 // item ada ga valid -> 300

	public function __construct()
	{
	    // parent::__construct();
    	// $this->load->model('mobile/login', 'mobile');
	}

	public function index()
	{
		echo "Hi sup man..";
		
	}

    /*
    *
    * PMTS Start here
    *
    */

    public function login_pmts2(){
    }

	public function mobile_pm(){
        $this->load->model('Mobile_model');
		$site_id='BSD_01';
		$pic='Andi';
		$lat= -6.2946456;
		$lon=106.6665635;
		$jc_id=1;
		$tgl_jc='05/08/2013';
		
		$site1=array("site_id" => $site_id,"pic" => $pic,"lat" => $lat,"lon" => $lon,"jc_id" => $jc_id,"tgl_jc" => $tgl_jc);
		$site2=array("site_id" => $site_id,"pic" => $pic,"lat" => $lat,"lon" => $lon,"jc_id" => $jc_id,"tgl_jc" => $tgl_jc);
		$site= array($site1,$site2);
		
		$preparation=$this->Mobile_model->get_preparation_item();
		$pm_checklist=$this->Mobile_model->get_pm_checklist();
		$result = array("data_site"=>$site,"preparation" => $preparation,"pm_checklist" => $pm_checklist);
		echo json_encode($result);
	
	}

	public function getJourneyCycle(){
		$this->load->model('Mobile_model');
		print_r($this->Mobile_model->selectUserAndJourneyCycle('pic_1_1','2013-08-16'));

		$userId = $this->input->get('usrid');
		$date = date('Y-m-d');

		return $this->Mobile_model->getJourneyCycle();
	}

	public function mobile_pm2(){

		$data = $this->input->post('data');

		$fixData = json_decode($data);;

		$the_user_id = $fixData->usri;
		$the_date_today = date('Y-m-d');
		
		// $the_user_id = 'pic_1_1';
		// $the_date_today = '2013-09-02';

        $this->load->model('Mobile_model');
		$data_pm = array();
		$i=0;
		$site = $this->Mobile_model->selectUserAndJourneyCycle($the_user_id,$the_date_today);
		// foreach($site as $s){
			// $data_pm[$i]['pm_id']=($s['pm_id']);
			// $i++;
		// }
		// $preparation=$this->Mobile_model->get_preparation_item();
		//********************AMBIL DATA TOOLS**********************
		$prep_type=$this->Mobile_model->get_preparation_type();
		$p=0;
		if($prep_type!=null){
			foreach($prep_type as $type){
				$prep_item= $this->Mobile_model->get_preparation_item($type['preparation_type_id']);
				$prep_type[$p]['item']= $prep_item;
				$p++;
			}
		}
		//********************AMBIL DATA TOOLS-end**********************
		// die(print_r($prep_type));
		
		$pm_checklist_group = $this->Mobile_model->get_pm_checklist_group();  
		
		$detail_1=array();
		$c_group=array();
		$c_option=array();
		if($pm_checklist_group!=null && $site!=null)
		{
			$h = 0;
			foreach($site as $pm){
				$data_pm[$h]['pm_id']=($pm['pm_id']);
				$i=0;
				foreach($pm_checklist_group as $row){
					$detail_1=$this->Mobile_model->get_pm_checklist_by_group($row['id'],$pm['pm_id']);
					$j = 0;
					foreach($detail_1 as $opt){
						$c_option=$this->Mobile_model->get_pm_checklist_option($opt['checklist_id'],$opt['property_id']);
						$detail_1[$j]['c_option']=$c_option;
						$j++;
					}

					$pm_checklist_group[$i]['detail'] = $detail_1;
					$i++;
				}
				$data_pm[$h]['cgroup']= $pm_checklist_group;
				$h++;
			}
			// die(print_r($data_pm));

		}

		
		$result = array("data_site"=>$site,"preparation" => $prep_type,"pm_checklist" => $data_pm);
		echo json_encode($result);
	
	}


	public function mobile_pm3(){

		// $data = $this->input->post('data');

		// $fixData = json_decode($data);;

		// $the_user_id = $fixData->usri;
		$the_date_today = date('Y-m-d');
		$the_user_id = 'pic_1_1';
		// $the_date_today = '2013-10-24';

        $this->load->model('Mobile_model');
		$data_pm = array();
		$i=0;
		$site = $this->Mobile_model->selectUserAndJourneyCycle($the_user_id,$the_date_today);
		foreach($site as $s){
			$data_pm[$i]['pm_id']=($s['pm_id']);
			$i++;
		}
		$preparation=$this->Mobile_model->get_preparation_item();
		$pm_checklist_group = $this->Mobile_model->get_pm_checklist_group();  
		$i = 0;
		
		$detail_1=array();
		$detail=array();
		$c_option=array();
		if($pm_checklist_group!=null && $data_pm!=null)
		{
			foreach($data_pm as $pm){
			
				foreach($pm_checklist_group as $row)
				{
					$detail_1=$this->Mobile_model->get_pm_checklist_by_group($row['id'],$pm['pm_id']);
					$j = 0;
					foreach($detail_1 as $opt)
					{
						$c_option=$this->Mobile_model->get_pm_checklist_option($opt['checklist_id'],$opt['property_id']);
						$detail_1[$j]['c_option']=$c_option;
						$j++;
					}

					$pm_checklist_group[$i]['detail'] = $detail_1;
					$i++;
				}
			}
			// die(print_r($pm_checklist_group));

		}

		
		$result = array("data_site"=>$site,"preparation" => $preparation,"pm_checklist" => $pm_checklist_group);
		print_r($result);
		echo json_encode($result);
	
	}
	
	public function addjcsitepreparation()
	{
		$this->load->model('Mobile_model');

		$data = $this->input->post('data');

        $fixData = json_decode($data);

        // print_r($fixData);

        $final_inserted_data = array();

		foreach ($fixData as $key) {
			$inserted_data = array(	
				'jc_id'=>$key->jc_id,
				'site_id'=>$key->site_id,
				'pt_id'=>$key->pt_id,
				'jumlah'=>$key->jumlah
				);
			array_push($final_inserted_data, $inserted_data);

		}



		if ($this->Mobile_model->insert_jc_preparation($final_inserted_data)) {
			echo json_encode(
    			array(
	    			"req" => "addjcsitepreparation",
	    			"res" => "ok"
    			)
    		);
		} else {
			echo json_encode(
    			array(
	    			"req" => "addjcsitepreparation",
	    			"res" => "nok"
    			)
    		);
		}
		

	}

	public function site_tracking(){
		$this->load->model('Mobile_model');

		$data = $this->input->post('data');

		$fix_data = json_decode($data);

		$jc_id 		= $this->input->get('jc_id');
		$waktu 		= date('Y-m-d H:i:s');
		$latitude 	= $this->input->get('latitude');
		$longitude 	= $this->input->get('longitude');
		$created_by 	= $this->input->get('created_by');
		$created_on = date('Y-m-d H:i:s');;
		$updated_by = $this->input->get('updated_by');
		$updated_on = date('Y-m-d H:i:s');

		print_r($fix_data);

		$final_inserted_data = array();

		$inserted_data = array(
			'jc_id' 		=> $fix_data->jc_id 
			, 'waktu'		=> date('Y-m-d H:i:s')
			, 'latitude'	=> $fix_data->latitude
			, 'longitude' 	=> $fix_data->longitude
			, 'created_by'	=> $fix_data->usri
			, 'created_on'	=> date('Y-m-d H:i:s')
			, 'updated_by'	=> $fix_data->usri
			, 'updated_on'	=> date('Y-m-d H:i:s')
		);

		array_push($final_inserted_data, $inserted_data);

		$this->Mobile_model->insert_t_trx_pm_tracking2($final_inserted_data);
		
	}

	public function addjcsitechecklist()
	{

		// $this->load->model('Mobile_model');

		// $data = $this->input->post('data');

        // $key = json_decode($data);

		/*
		* Upload start
		*/
		$i=0;

		$config['upload_path'] = './file/upload/';
		$config['allowed_types'] = 'gif|jpg|png';

		$this->load->library('upload', $config);

		foreach ($_FILES as $key => $value) {

			if (!empty($key['name'])) {
				
				if ( ! $this->upload->do_upload($key))
				{
					$error = array('error' => $this->upload->display_errors());

					// print_r($error);
					// echo "gagal";
				}
				else
				{
					$data = array('upload_data' => $this->upload->data());

					// print_r($data);
					// echo "success";
					$i++;
				}

			}

		}

		/*
		* Upload finish
		*/

		// echo "nilai i : ".$i;

		if ($i == 2) {

			$this->load->model('Mobile_model');

			$data = $this->input->post('data');

	        $key = json_decode($data);

			$inserted_data = array(	
				'pm_id'=>(int)$key->pm_id,
				'site_id'=>(int)$key->site_id,
				'checklist_id'=>(int)$key->checklist_id,
				'result'=>$key->result,
				'bobot'=>(int)$key->bobot,
				'created_by'=>$key->created_by,
				'created_on'=>date('Y-m-d H:i:s'),
				'photo_before'=>$key->photo_before,
				'photo_after'=>$key->photo_after,
				'used_sparepart'=>$key->used_sparepart,
				'used_comsumpart'=>$key->used_consumpart,
				'req_sparepart'=>$key->req_sparepart,
				'req_comsumpart'=>$key->req_consumpart
				);

			if ($this->Mobile_model->insert_jc_sitechecklist($inserted_data)) {
				echo json_encode(
	    			array(
		    			"req" => "addjcsitechecklist",
		    			"res" => "ok"
	    			)
    			);
			} else {
				echo json_encode(
	    			array(
		    			"req" => "addjcsitechecklist",
		    			"res" => "nok"
	    			)
    			);
			}
			
		} else {
			echo json_encode(
    			array(
	    			"req" => "addjcsitechecklist",
	    			"res" => "nok"
    			)
    		);
		}
		
	}

	public function testJC(){
		$this->load->model('Mobile_model');
		
		if ($this->Mobile_model->get_pm_jc('pic_1_1','2013-08-15') != null) {
			# code...
			echo "OK";
		} else {
			# code...
			echo "NOT OK";
		}
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
/* Because  sublimetext is too mainstream*/