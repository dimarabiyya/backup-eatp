<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gis extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('encrypt');
        // $this->load->model('musers');
        $this->load->model(array('mmaster', 'mglobal'));
        $this->load->model('mgis');
        $this->mglobal->is_logged_in();
        #$this->mjadwal->saveLog();
    }

    // function is_logged_in()
    // {
    // $is_logged_in = $this->session->userdata('is_login');
    // if(!isset($is_logged_in) || $is_logged_in != true)
    // {
    // redirect(base_url() .'index.php/login');
    // }		
    // }
    function save_boundary() {

        $territory = $this->input->post('territory');
        $dataCoord = $this->input->post('dataCoord');
        if ($territory == '' || $dataCoord == '') {
            echo json_encode(array(
                'status' => 'ERROR'
            ));
            return;
        }

        $tr = explode('-', $territory);
        $tipe = 1;
        if ($tr[1] == 'cls')
            $tipe = 2;

        $data = array();

        $str = $dataCoord;
        // die("ini=".$str);
        $str2 = str_replace(array('('), array(''), $str);
        $geo = explode(")", $str2);
        $sum_array = count($geo) - 1;
        // die("ini=".$sum_array);
        $x = 0;
        $polygon = array();
        $u = 0;
        if ($sum_array > 0) {
            $this->db->trans_start();
            $this->db->where(array('territory_id' => $tr[0], 'territory_type' => $tipe));
            $this->db->delete('t_mtr_territory_border');
            foreach ($geo as $v => $x) {
                if ($x <> "") {
                    $latlng     = explode(", ", $x);
                    $polygon[]  = "$latlng[0] $latlng[1]";
                    $data[$x]   = array(
                        "territory_id"  => $tr[0],
                        "territory_type"=> $tipe,
                        "lat"           => $latlng[0],
                        "lon"           => $latlng[1],
                        "created_by"    => $this->session->userdata('user_id'),
                        "created_on"    => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('t_mtr_territory_border', $data[$x]);
                    $x++;
                }
            }
            
            //update region_id 6dec@dody //{^_^) updated andedi 20141212
            //---------------------------------
            $this->load->library('pointlocation');
            $hasil = '';
            $getSiteAll = $this->mgis->getSiteAll();
            foreach ($getSiteAll as $r) {
                $point = "$r->latitude $r->longitude";
                $position = $this->pointlocation->pointInPolygon($point, $polygon);
                if ($position == 'inside') {
                    //$hasil .="point $r->site_id :: $r->site_name :: $r->site_address ($point): " . $this->pointlocation->pointInPolygon($point, $polygon) . "<br>";
                    //update data
                    $this->mgis->updateSiteRegion($r->site_id, $tr[0], $tipe);
                    $u++;
                }
            }
            $this->db->trans_complete();
        }

        echo json_encode(array(
            'status' => 'OK', "hasil" => "$u site updated", "territory" => $territory
        ));
        return;
    }

    function boundary_manage() {
        $this->load->model('referensi/map_model');
        $data['gambar_marker'] = $this->map_model->get_marker();
        $this->theme->view('map_boundary_manage', $data);
    }

    function map_one() {
        $data['lat'] = -6.234077;
        $data['long'] = 106.7211914;
        $this->theme->view('map_one', $data);
    }

    function gis_tracking() {
        // $user='pic_1_1';
        $user = isset($_POST['user']) ? $_POST['user'] : 'pic_1_1';
        // $date='2013-11-11';
        $date = isset($_POST['date']) ? $_POST['date'] : '2013-11-11';
        // die($user);
        $data['user'] = $user;
        $data['date'] = $date;
        $data['track'] = $this->mgis->getMapTrackingIwa($user, $date);
        $data['pic_list'] = $this->mgis->getPic();
        // die($data['track'][0]['latitude']);
        $this->theme->view('gis_tracking', $data);
    }

    function gis_tracking_si() {
        // $user='pic_1_1';
        $user = isset($_POST['user']) ? $_POST['user'] : 'npi_02';
        // $date='2013-11-11';
        $date = isset($_POST['date']) ? $_POST['date'] : '2013-11-11';
        // die($user);
        $data['user'] = $user;
        $data['date'] = $date;
        $data['track'] = $this->mgis->getMapTrackingIwaSI($user, $date);
        $data['pic_list'] = $this->mgis->getPic();
        // die($data['track'][0]['latitude']);
        $this->theme->view('gis_tracking_si', $data);
    }

    function getmap() {
        $this->theme->view('getmap');
    }

    function tes() {
        $aa = $this->mgis->getTerritory();
        print_r($aa);
    }

    function tracking($proses = "", $id = "") {
        $data['template'] = "shell/smooth";
        $data['main_view'] = 'map_tracking';
        $data['proses'] = $proses;
        $data['id'] = $id;
        $data['hasil'] = "";

        #inisialisasi elemen form yang harus diisi
        $this->form_validation->set_rules('user', 'User ID', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');

        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->form_validation->set_message('required', 'Kolom <b>%s</b> harus diisi.');

        if ($this->form_validation->run() != FALSE) {
            // masukkan data di sini
            $data['hasil'] = "DO IT";

            //$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
            //redirect(base_url()."index.php/master/usermanagement");
        }

        $this->load->view($data['template'], $data);
    }

    function outlet($proses = "", $id = "") {
        $data['template'] = "shell/smooth";
        $data['main_view'] = 'map_outlet';
        $data['proses'] = $proses;
        $data['id'] = $id;
        $data['hasil'] = "";
        $data['bound'] = "";

        #inisialisasi elemen form yang harus diisi
        //$this->form_validation->set_rules('territory', 'Territory ID', 'required');		
        //$this->form_validation->set_error_delimiters('<li>','</li>');
        //$this->form_validation->set_message('required', 'Kolom <b>%s</b> harus diisi.');
        //if ($this->form_validation->run() != FALSE){
        // masukkan data di sini

        $tid = $this->db->query('select f_get_territory_id(\'' . $this->session->userdata('user_id') . '\')')->row()->f_get_territory_id;
        //echo $tid;
        $data['hasil'] = $this->mgis->getOutletByTerritory($tid, $this->input->post("outlet_id"), $this->input->post("outlet_name"));
        //$data['hasil'] = $this->mgis->getOutletByTerritory($this->session->userdata('user_id'),$this->input->post("outlet_id"),$this->input->post("outlet_name"));
        //echo var_dump($data['hasil']);return;
        $data['bound'] = $this->mgis->getTerritoryMember($tid);
        //$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
        //redirect(base_url()."index.php/master/usermanagement");
        //echo print_r($data['hasil']);return;
        //}	

        $this->load->view($data['template'], $data);
    }

    function agent($proses = "", $id = "") {
        $data['template'] = "shell/smooth";
        $data['main_view'] = 'map_agent';
        $data['proses'] = $proses;
        $data['id'] = $id;
        $data['hasil'] = "";
        $data['bound'] = "";

        #inisialisasi elemen form yang harus diisi
        $this->form_validation->set_rules('territory', 'Territory ID', 'required');

        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->form_validation->set_message('required', 'Kolom <b>%s</b> harus diisi.');

        if ($this->form_validation->run() != FALSE) {
            // masukkan data di sini
            $data['hasil'] = $this->mgis->getAgentByTerritory($this->input->post("territory"), $this->input->post("agent_id"), $this->input->post("agent_name"));

            $data['bound'] = $this->mgis->getTerritoryMember($this->input->post("territory"));
            //$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
            //redirect(base_url()."index.php/master/usermanagement");
        }

        $this->load->view($data['template'], $data);
    }

    function boundary($proses = "", $id = "") {
        // $data['template'] = "shell/smooth";
        $this->load->model('referensi/map_model');
        $this->load->model('mgis');
        $data['main_view']      = 'map_boundary';
        $data['proses']         = $proses;
        $data['id']             = $id;
        $data['hasil']          = 1;
        $data['gambar_marker']  = $this->map_model->get_marker();
        $data['territory']      = "1-nas";
        $data['ter2']           = "";
        $data['regions']        = $this->mglobal->getAllRegions();
        #inisialisasi elemen form yang harus diisi
        $this->form_validation->set_rules('territory', 'Territory ID', 'required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->form_validation->set_message('required', 'Kolom <b>%s</b> harus diisi.');
        if (isset($_POST['territory'])) {
            $data['hasil']      = 1;
            $tr                 = explode('-', $this->input->post("territory"));
            $data['territory']  = $tr[0];
            $data['ter2']       = $this->input->post("territory");
        }
        if ($this->form_validation->run() != FALSE) {
            // masukkan data di sini
            $data['hasil'] = 1;
            //$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
            //redirect(base_url()."index.php/master/usermanagement");
        }

        // $this->load->view($data['template'],$data);
        $this->theme->view('map_boundary', $data);
    }

    function territory_json() {
        echo($this->mgis->getTerritoryTree($this->mgis->getParentIdByUser()));
    }

    function territory_json_new() {
        echo($this->mgis->getTerritoryTreeNew());
    }

    function territory_json0() {
        echo($this->mgis->getTerritoryTree(0));
    }
    
    function territory_border() { // {^_^) andedi 20141212
        $data['regions'] = array();
        $data['clusters'] = array();
        
        if ($this->input->post()!=FALSE) {
            $region_id  = $this->input->post('region_id');
            $cluster_id = $this->input->post('cluster_id');
            
            if ($region_id!=0 && $cluster_id==0) {
                $data['1']=1;
                $type           = 1;
                $data['regions'] = $this->mgis->getRegionBoundary($region_id, $type);
                $data['clusters'] = $this->mgis->getClusterBoundary($region_id, $type);
            } else if ($region_id!=0 && $cluster_id!=0){
                $data['2']=2;
                $type           = 2;
                $data['clusters'] = $this->mgis->getClusterBoundary($cluster_id, $type);
            } else {
                $data['3']=3;
                $data['regions'] = $this->mgis->getRegionBoundary(0, 0);
                $data['clusters'] = $this->mgis->getClusterBoundary(0, 0);
            }
        }
        
        echo json_encode($data);
    }

    function user_json() {
        echo($this->mgis->getUser_json());
    }

    function manage($proses = "", $id = "") {
        $data['template'] = "shell/smooth";
        $data['main_view'] = 'map_boundary_manage';
        $data['proses'] = $proses;
        $data['id'] = $id;
        $data['hasil'] = "";

        #inisialisasi elemen form yang harus diisi
        $this->form_validation->set_rules('border', 'border', 'required');

        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->form_validation->set_message('required', 'Kolom <b>%s</b> harus diisi.');

//            if($this->input->post('view')) {
//                // masukkan data di sini
//                $data['hasil'] = "DO IT";
//                                
//            }

        $data['hasil'] = "DO IT";
        if ($this->form_validation->run() != FALSE) {
            // masukkan data di sini
            $this->mgis->putBoundary();
            $this->mgis->updateColor2();

            //$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
            //redirect(base_url()."index.php/gis/boundary");
        }

        $this->load->view($data['template'], $data);
    }

    function check($proses = "", $id = "") {
        $data['template'] = "shell/smooth";
        $data['main_view'] = 'map_check';
        $data['proses'] = $proses;
        $data['id'] = $id;
        $data['hasil'] = "";
        $data['bound'] = "";

        #inisialisasi elemen form yang harus diisi
        $this->form_validation->set_rules('territory', 'Territory ID', 'required');

        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->form_validation->set_message('required', 'Kolom <b>%s</b> harus diisi.');

        if ($this->form_validation->run() != FALSE) {
            // masukkan data di sini
            $data['hasil'] = $this->mgis->getOutletByTerritory($this->input->post("territory"), $this->input->post("outlet_id"), $this->input->post("outlet_name"));

            $data['bound'] = $this->mgis->getTerritoryMember($this->input->post("territory"));
            //$this->session->set_flashdata('message', 'Data Berhasil dimasukkan');
            //redirect(base_url()."index.php/master/usermanagement");
        }

        $this->load->view($data['template'], $data);
    }

    function updateoutletlonlat($outlet = "", $lon = "", $lat = "") {
        if ($this->mgis->updateoutletlonlat($outlet, $lon, $lat)) {
            echo 'Update Lokasi Partner Berhasil';
        } else {
            echo 'Update Lokasi Partner Gagal';
        }
    }

    function showBoundary() {
        $territory = $this->input->post('terr_id');
        $tr = explode('-', $territory);
        $tipe = 1;
        if ($tr[1] == 'cls')
            $tipe = 2;
        $bound = get_instance()->mgis->getBoundaryTerritory($tr[0], $tipe);
        $status = "KOSONG";
        if (count($bound) > 0) {
            $status = "ISI";
        }
        echo json_encode(array(
            'status' => $status,
            'bound' => $bound
        ));
        return;
    }
    
    function cluster_json() { // {^_^) andedi 20141212
        if ($this->input->post()!=FALSE) {
            $region_id  = $this->input->post('region_id');
            $data['clusters']  = $this->mglobal->getAllCluster($region_id);
            
            echo json_encode($data);
        }
    }

}