<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Territory extends CI_Controller {

	function territory()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('encrypt');
		$this->load->model('mmaster');
        $this->load->model('mservices');
		$this->load->model('mterritory');
		$this->is_logged_in();
		#$this->mjadwal->saveLog();
	}
        
    function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_login');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			redirect(base_url() .'index.php/login');
		}		
	}
        
        
    function index()
	{
		$this->tree();		
	}
	
        function tree($proses="",$id=""){
                $data['template']       = "template/smashing";
				//$data['main_view']      = "f-territory";
				$data['content_id'] = "f-territory";
				$data['jenis']          = "tree";
                $data['proses']         = $proses;
                $data['id']             = $id;
		
                #tangani proses save data
                $data['data'] = array("name"=>"","user"=>"","territory_type"=>"","parent_id"=>"");
                $data['type'] = ""; $data['code'] = "";$data['codes'] = ""; $data['codes2'] = ""; $data['icon'] = "";
		if($proses=="update" && $id<>""){
		    $getdata = $this->mterritory->getTerritoryById($id);
		    foreach($getdata as $r){
						$data['data']['name'] 		= $r->territory_name;
						$data['data']['user'] 	        = $r->user_id;
						$data['data']['territory_type'] = $r->territory_type_id;
                        $data['data']['parent_id'] 	= $r->parent_id;
                        $data['codes'] = $this->mglobal->showdata("territory_code","t_mtr_territory_code",array("territory_id"=>$id),"dblokal");
						$data['codes2'] = $this->mglobal->showdata("territory_code_v2","t_mtr_territory_code",array("territory_id"=>$id),"dblokal");
                        
                        $getTerritoryType = $this->mterritory->getTerritoryType($r->territory_type_id);
                        foreach($getTerritoryType as $s){
                                $data['idtype'] = $r->territory_type_id;
                                $data['type'] = $s->territory_type_name;
                                $data['code'] = $s->use_territory_code;
                                $data['icon'] = "<img src='". base_url() ."file/images/territory_icon/".$s->territory_type_icon."'>";
                        }
		    }
		}elseif($proses=="add" && $id<>""){
                        #proses u/ add territory, munculkan data 
                        $getdata = $this->mterritory->getTerritoryById($id);
                        foreach($getdata as $r){
                                $data['data']['user'] 	        = $r->user_id;
                                $data['data']['territory_type'] = $r->territory_type_id;
                                $data['data']['parent_id'] 	= $id;
                                $data['codes'] = $this->mglobal->showdata("territory_code","t_mtr_territory_code",array("territory_id"=>$id),"dblokal");
                                $data['codes2'] = $this->mglobal->showdata("territory_code_v2","t_mtr_territory_code",array("territory_id"=>$id),"dblokal");
                                $getTerritoryType = $this->mterritory->getTerritoryType($r->territory_type_id+1);
                                foreach($getTerritoryType as $s){
                                        $data['idtype'] = $r->territory_type_id;
                                        $data['type'] = $s->territory_type_name;
                                        $data['code'] = $s->use_territory_code;
                                        $data['icon'] = "<img src='". base_url() ."file/images/territory_icon/".$s->territory_type_icon."'>";
                                }
                        }
		}
		
		#inisialisasi elemen form yang harus diisi
		$this->form_validation->set_rules('name', 'Territory Name', 'required');
		$this->form_validation->set_rules('user', 'Maintenance By', 'required|callback_vmaintenance_check');
                #$this->form_validation->set_rules('territory_type', 'Territory Type', 'required');
		
		$this->form_validation->set_error_delimiters('<li>','</li>');
		$this->form_validation->set_message('required', 'Kolom <b>%s</b> harus diisi.');
                
		if ($this->form_validation->run() != FALSE){
			// masukkan data di sini
                        $this->mterritory->saveTerritory($proses,$id,$_FILES['icon']['name']);
                        if($_FILES['icon']['name'] <> "" ){
                                $config['upload_path'] = './file/images/territory_icon/';
                                //$config['file_name'] = $_FILES['foto']['name'];
                                $config['allowed_types'] = 'jpg|jpeg|JPG|JPEG|png|PNG' ;
                                $config['max_size'] = '500';
                                $config['overwrite'] = TRUE;
                                
                                $this->load->library('upload', $config);
                                $this->upload->initialize($config);
                                if(!$this->upload->do_upload("icon")){
                                    $data['error'] = $this->upload->display_errors();
                                }
                        }
			$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
			redirect(base_url()."index.php/territory/tree");
		}			
                $hasil = "";
                
                $this->load->view($data['template'],$data);
        }
        
        function chart(){
                $data['template']   = "shell/smooth";
		$data['main_view']  = "f-territory";
                $data['jenis']  = "chart";
		$data['proses']  = "";
                
                $this->load->view($data['template'],$data);
        }
        
		function search($proses="",$id=""){
				$data['template']       = "shell/smooth";
				$data['main_view']      = "f-territory-search";
				$data['jenis']          = "tree";
                $data['proses']         = $proses;
                $data['id']             = $id;
		
                #tangani proses save data
                $data['data'] = array("name"=>"","user"=>"","territory_type"=>"","parent_id"=>1);
                $data['type'] = ""; $data['code'] = "";$data['codes'] = ""; $data['icon'] = "";
				$data['search'] = 1;
				
				$data['komponen'] = "";
                
                $this->load->view($data['template'],$data);
        }
		
		function search_result(){
			
			$name 		 = $this->input->post('name');
			$parentid 	 = $this->input->post('parentid');
			$maintenance = $this->input->post('maintenance');
			
			$data['getData'] = $this->mterritory->searchTerritory($name,$parentid,$maintenance);
			$hasil = $this->load->view('f-territory-search-hasil',$data,TRUE);
			$gd = $data['getData'];
			echo json_encode(array(
					'status'=>'OK',
					'pesan'=>$hasil,
					'jumlah'=>$gd->num_rows()
			));
			return;
		}
		
	function territory_iframe()
	{
		$data['template'] = "f-territory-chart";
		$this->load->view($data['template'],$data);
	}
	
	function territory_json($id=0,$tipe='',$limit=0){
		echo($this->mterritory->getTerritoryTree($id,$tipe,$limit));
	}
        
        function territory_edit($ter_id=""){
                if($ter_id=="") $ter_id = $this->input->post('ter_id');
                $type = $this->mglobal->showdata("territory_type_id","t_mtr_territory",array('territory_id'=>$ter_id),"dblokal");
                #get territory type, code, & icon
                
                $data['idtype'] = ""; $data['type'] = ""; $data['code'] = ""; $data['codes'] = ""; $data['icon'] = ""; $data['user'] = "";
                $getTerritoryType = $this->mterritory->getTerritoryType($type+1);
                foreach($getTerritoryType as $r){
                        $data['idtype'] = $type;
                        $data['type'] = $r->territory_type_name;
                        $data['code'] = $r->use_territory_code;
                        $data['codes'] = $this->mglobal->showdata("territory_code","t_mtr_territory_code",array("territory_id"=>$ter_id),"dblokal");
						$data['codes2'] = $this->mglobal->showdata("territory_code_v2","t_mtr_territory_code",array("territory_id"=>$ter_id),"dblokal");
                        $data['icon'] = "<img src='". base_url() ."file/images/territory_icon/".$r->territory_type_icon."'>";
                        $data['user'] = "";
						$data['search'] = $this->input->post('search');
				}
                $isi = $this->load->view('f-territory-add',$data,TRUE);
                if($this->input->post('ter_id')==""){
                        return $isi;
                }else{
                        echo json_encode(array(
                                'status'=>'OK',
                                'pesan'=>$isi
                        ));
                        return;
                }
        }
        
        function territory_delete(){
                $id = $this->input->post('ter_id');
                $this->mterritory->deleteTerritory($id);
                
                echo json_encode(array(
                        'status'=>'OK'
                ));
                return;
        }
        
        function vmaintenance_check($str)
	{
                $v = "";
                if($str<>"admin"){
                        if($this->uri->segment(4) == ""){
                                $where = array("user_id"=>$str);
                        }else{
                                $where = array("user_id"=>$str,"territory_id !="=>$this->uri->segment(4));
                        }
                        $v = $this->mglobal->showdata('territory_id','t_mtr_territory',$where,'dblokal');
                }
                
		if ($v <> "")
		{
			$this->form_validation->set_message('vmaintenance_check', 'User <b>'. $str .'</b> sudah memiliki territory.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function save_territory(){
		$id 	 = $this->input->post("id");
		$name 	 = $this->input->post("name");
		$parent  = $this->input->post("parent_id");
		$user 	 = $this->input->post("user");
		$territory_type = $this->input->post("territory_type");
		$territory_code = $this->input->post("territory_code");
		$territory_code2 = $this->input->post("territory_code2");
		$proses = $this->input->post("proses");
		
		$status='ERROR'; $pesan='';
		$v="";
		if($user<>"admin"){
			if($id == ""){
					$where = array("user_id"=>$user);
			}else{
					$where = array("user_id"=>$user,"territory_id !="=>$id);
			}
			$v = $this->mglobal->showdata('territory_id','t_mtr_territory',$where,'dblokal');
		}
		
		
		if($name=="" || ($user<>"" && $v <> "")){
			$pesan.="Terdapat kesalahan:\n";
			if($name==""){
				$pesan.="=> Kolom Nama harus diisi\n";
			}
			
			if($user<>""&&$v<>""){
				$pesan.="=> Pengguna telah memilihi territory \n";
			}
		}else{
			$status='OK'; $pesan='Data Berhasil dimasukkan';
			$this->mterritory->saveTerritory($proses,$id,"");
			$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
		}
		//}elseif($proses == "update"){
			
		//}
		
		echo json_encode(array(
				'status'=>$status,
				'pesan'=>$pesan
		));
		return;
		
	}

}