<?php

/*
 * Andedi Tarmadi
 * 
 * 
 * ----------------
 * Tumbas Wiralodra
 * Email : andedi.foss@gmail.com
 * 087829986468
 * 15-09-2014
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Site_relocation extends MX_Controller {

	private $_partials = array();
        private $user_now = '';
        private $date_now = '';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->library('parser');
		
		$this->lang->load('site_info/site_info', 'indonesia');
		
		$this->load->helper('cookie');
		$this->load->helper('language');
		$this->load->helper('text');
		
		$this->load->model('site_info_model');
		$this->load->model('image_model');
		$this->load->model('mmaster');
		
		$uri = $this->uri->segment(1);
		$this->_partials['menu'] = $this->mmenu->getMenuId($uri);
                $this->user_now = $this->session->userdata('user_id');
                $this->date_now = date('Y-m-d H:i:s');
	}
	
        public function index() {
            $data['content_id'] = 'site_relocation_list';
            $this->load->view('template/smashing', $data);
        }

	private function _check_valid_loggedin()
	{
            if ( ! $this->session->userdata('is_login')) {
                if ($this->core->is_ajax()) {
                    echo json_encode(array(
                        'loggedin' => FALSE,
                        'message' => 'You are not logged in. You will be redirected to login page.',
                        'redirect_url' => site_url('/login/'.$this->core->encode($this->uri->uri_string()))
                    ));
                    exit;
                } else {
                    redirect('/login/'.$this->core->encode($this->uri->uri_string()));
                }
            }
	}
        
        public function add()
	{
            $this->_check_valid_loggedin();
            
            if (isset($_POST['save'])) {
                if ($this->validate()==true) {
                    $data = array (
                        'active'                => 1,
                        'cause'                 => $this->input->post('cause'),
                        'created_by'            => $this->user_now,
                        'created_on'            => $this->date_now,
                        'dismantle_start_date'  => $this->make_up_date($this->input->post('dismantle_start_date')),
                        'dismantle_end_date'    => $this->make_up_date($this->input->post('dismantle_end_date')),
                        'relocation_type'       => $this->input->post('relocation_type'),
                        'remark'                => $this->input->post('remark'),
                        'site_destination'      => $this->input->post('site_destination'),
                        'vendor_id'             => $this->input->post('vendor_id')
                    );

                    $file_name                  = 'Reloc-'.$data['vendor_id'].'-'. date('YmdHis');
                    $config['upload_path']      = './file/upload/site_relocation/';
                    $config['allowed_types']    = 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
                    $config['file_name']        = $file_name;
                    $this->load->library('upload', $config);
                    
                    if (!empty($_FILES['doc_name']['tmp_name'])) {
                        if ($this->upload->do_upload('doc_name')) { //cek upload file
                            $data_upload        = $this->upload->data();
                            $data['doc_name']   = $data_upload['file_name'];
                            
                            $save_data = $this->site_info_model->insert_relocation($data);
                            if ($save_data) { //cek save data
                                $result['type']     = 'ok';
                                $result['message']  = 'Data saved';
                            } else {
                                $result['type']     = 'error';
                                $result['message']  = 'Save data failed';
                            }
                        } else {
                            $result['type']     = 'error';
                            $result['message']  = $this->upload->display_errors();
                        }
                    } else {
                        $save_data = $this->site_info_model->insert_relocation($data);
                        if ($save_data) { //cek save data
                            $result['type']     = 'ok';
                            $result['message']  = 'Data saved';
                        } else {
                            $result['type']     = 'error';
                            $result['message']  = 'Save data failed';
                        }
                    }

                } else {
                    $result['type'] = 'error';
                    $result['message']  = validation_errors();
                }

                echo json_encode($result);
                
            } else {
                $data['content_id'] = 'site_relocation_form';
                $data['list_url'] = base_url('/site_info/site_relocation');
                $this->load->view('template/smashing', $data);
            }
            
	}
        
        function make_up_date($date_old){
            $arr = explode('-', $date_old);
            $date_new = $arr[2].'-'.$arr[1].'-'.$arr[0];
            return $date_new;
        }
        
        public function get_data() {
            $param['limit']     = $_GET['rows'];
            $param['offset']    = ($_GET['page']-1)*$param['limit'];
            $data['rows']       = $this->site_info_model->get_data_reloc($param);
            $data['total']      = $this->site_info_model->get_data_reloc_count();
            echo json_encode($data);
        }
        
        public function edit($id) {
            $this->_check_valid_loggedin();
            
            if (isset($_POST['save'])) {
                if ($this->validate()==true) {
                    $data = array (
                        'active'                => 1,
                        'cause'                 => $this->input->post('cause'),
                        'dismantle_start_date'  => $this->make_up_date($this->input->post('dismantle_start_date')),
                        'dismantle_end_date'    => $this->make_up_date($this->input->post('dismantle_end_date')),
                        'relocation_type'       => $this->input->post('relocation_type'),
                        'remark'                => $this->input->post('remark'),
                        'site_destination'      => $this->input->post('site_destination'),
                        'site_relocation_id'    => $this->input->post('site_relocation_id'),
                        'updated_by'            => $this->user_now,
                        'updated_on'            => $this->date_now,
                        'vendor_id'             => $this->input->post('vendor_id')
                    );

                    if (!empty($_FILES['doc_name']['tmp_name'])) {
                        $file_name                  = 'Reloc-'.$data['vendor_id'].'-'. date('YmdHis');
                        $config['upload_path']      = './file/upload/site_relocation/';
                        $config['allowed_types']    = 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
                        $config['file_name']        = $file_name;
                        $current_file               = $this->input->post('current_file');
                        $this->load->library('upload', $config);

                        if ($this->upload->do_upload('doc_name')) { //cek upload file
                            unlink($config['upload_path'].$current_file);
                            $data_upload        = $this->upload->data();
                            $data['doc_name']   = $data_upload['file_name'];
                            $save_data = $this->site_info_model->update_relocation($data);
                            if ($save_data) { //cek save data
                                $result['type']     = 'ok';
                                $result['message']  = 'Data saved';
                            } else {
                                unlink($config['upload_path'].$data['doc_name']);
                                $result['type']     = 'error';
                                $result['message']  = 'Save data failed';
                            }
                        } else {
                            $result['type']     = 'error';
                            $result['message']  = $this->upload->display_errors();
                        }
                    } else {
                        $data['doc_name']   = $this->input->post('current_file');
                        $save_data          = $this->site_info_model->update_relocation($data);
                        if ($save_data) { //cek save data
                            $result['type']     = 'ok';
                            $result['message']  = 'Data saved';
                        } else {
                            $result['type']     = 'error';
                            $result['message']  = 'Save data failed';
                        }
                    }
                } else {
                    $result['type'] = 'error';
                    $result['message']  = validation_errors();
                }

                echo json_encode($result);
            } else {
                $data['row']        = $this->site_info_model->get_data_reloc_by_id($id);
                $data['content_id'] = 'site_relocation_form';
                $data['list_url']   = base_url('/site_info/site_relocation');
                $this->load->view('template/smashing', $data);
            }
        }
        
        public function validate() {
            $this->form_validation->set_rules('vendor_id', 'Vendor ID', 'required');
            $this->form_validation->set_rules('relocation_type', 'Relocation Type', 'required');
            $this->form_validation->set_rules('dismantle_start_date', 'Start date', 'required');
            $this->form_validation->set_rules('dismantle_end_date', 'Finish date', 'required');
            $this->form_validation->set_rules('site_destination', 'Relocate to', 'required');
            $this->form_validation->set_rules('cause', 'Cause', 'required');
            return $this->form_validation->run();
        }
        
}