<?php

class schedule extends MX_Controller
{

    // development: https://gateway.staging-egw.xl.co.id/
    private $api_key;
    private $url = "https://gateway.egw.xl.co.id/";
    private $auth;
    private $token;
    private $token_type;

    public function __construct()
    {
        parent::__construct();
        $this->api_key = $this->config->item('ecrf_api_key');
        $this->load->model('ecrf_model');
        $this->load->model('schedule_model');
        $this->load->model('reference_model');
        $this->load->model('schedule_checklist_model');
        $this->load->model('schedule_approval_model');
        $this->load->helper('uuid');
        $this->load->helper('form');
    }
    public function index()
    {
        $this->_check_access();
        if ($this->core->is_ajax()) {
            if ($this->input->get('cmd') == 'getScheduleList') {
                $rows = $this->schedule_model->scheduleList();
                echo json_encode($rows);
                exit;
            }
        }

        if ($this->input->server('REQUEST_METHOD') == 'DELETE') {
            $id = (int) $this->core->decode($this->input->get('visitID'));

            $this->schedule_model->delete($id);

            $json = array('status' => 'success', 'message' => 'Data deleted successfully.');
            echo json_encode($json);
            exit;
        }
        /*Get All The Region Data*/
        $row_regions = $this->reference_model->regionList();

        $data['regions'] = json_encode($row_regions);


        $data['content_id'] = 'index';

        $this->load->view('template/smashing', $data);
    }

    public function cek_aja()
    {
        $users = $this->input->post('users');
        echo count($users);
    }

    public function get_recipient_notif()
    {
        $get_isi = $this->uri->segment(3);
        $filtera = str_replace("_", " ", $get_isi);
        //$filterb = str_replace("_"," ",$filtera);

        //echo json_encode(ucwords($filtera));


        $sql = $this->db->query("select a.*,b.email from t_mtr_approval a left join t_mtr_user b on b.user_id = a.user_id where a.template_name LIKE '%" . ucwords($filtera) . "%' and is_deleted = 0 ")->result();

        $arpush = array();
        foreach ($sql as $list) {
            $arrpushx = array(

                'user_id' => $list->user_id,
                'email' => $list->email,

            );
            array_push($arpush, $arrpushx);
        }
        $list = json_encode($arpush);
        echo "{\"data\":" . $list . "}";
    }

    public function tesmail()
    {

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'okkisetyawan@gmail.com',
            'smtp_pass' => 'Kutoarjo_93'
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('okkisetyawan@gmail.com');
        $this->email->to('okkisetyawan@gmail.com');
        $this->email->subject('Coba coba');
        $this->email->message('Trust me, its works!');

        if ($this->email->send()) {
            echo "bisa";
            exit();
        } else {
            echo "gabisa";
            exit();
        }
    }

    public function create()
    {
        $this->_check_access();
        /*Get All The Region Data*/
        $row_regions = $this->reference_model->regionList();
        /*Get All Site*/
        $row_sites = $this->reference_model->siteList();
        /*Get all PIC Data*/
        $row_pic = $this->reference_model->getPICList();
        /*Get All PO*/
        $row_po = $this->reference_model->getListPo();
        /*Get All SOW Type*/
        $row_sow = $this->reference_model->getListSOW();
        /*Get All Approval Type*/
        $row_approval = $this->reference_model->getListApproval();

        /*Get Priority Data*/
        $rs_prio =  $this->mglobal->lookup('pm_priority');
        $ar_prio = array();
        foreach ($rs_prio as $item) {
            $ar_prio[$item->lookup_value] = $item->lookup_desc;
        }

        // Get ECRF Token Auth by Hary
        $temp_auth = $this->get_auth_token();
        if ($temp_auth) {
            $this->auth = $temp_auth;
            $this->token = $this->auth->data->accessToken;
            $this->token_type = $this->auth->data->tokenType;
            $data['status_token'] = "";
        } else {
            $this->auth = '';
            $this->token = '';
            $this->token_type = '';
            $data['status_token'] = "<p>Koneksi API XL gagal!</p>";
        }
        $token = $this->token;
        $token_type = $this->token_type;

        $data['token_ecrf'] = $token;
        $data['token_type_ecrf'] = $token_type;
        $data["title"]    = "Create New Schedule";
        $data["priority"]    = ($ar_prio);
        $data['regions'] = ($row_regions);
        $data['sites'] = ($row_sites);
        $data['pics'] = ($row_pic);
        $data['po'] = ($row_po);
        $data['sow'] = ($row_sow);
        $data['ecrf_ne'] = '';
        $data['ecrf_fe_existing'] = '';
        $data['ecrf_fe_after_reroute'] = '';
        $data['approval'] = ($row_approval);
        $data['content_id'] = 'scheduleform';
        $data['schedule'] = $this->schedule_model->getSchedule(0);/*0 as Default Schedule*/
        $data['action_url'] = base_url("/schedule/save");
        $this->load->view('template/smashing', $data);
    }

    /*public function Edit schedule*/
    public function edit($id)
    {
        $sch_id =  $this->core->decode($id);
        /*Get Reference ID*/
        $this->_check_access();
        /*Get All The Region Data*/
        $row_regions = $this->reference_model->regionList();
        // /*Get All Site*/
        $row_sites = $this->reference_model->siteList();
        // /*Get all PIC Data*/
        $row_pic = $this->reference_model->getPICList();
        // /*Get All PO*/
        $row_po = $this->reference_model->getListPo();
        // /*Get All SOW Type*/
        $row_sow = $this->reference_model->getListSOW();
        // /*Get All Approval Type*/
        $row_approval = $this->reference_model->getListApproval();

        $row_ecrf_ne = $this->ecrf_model->get_ecrf_edit($sch_id, 'ne');
        $row_ecrf_fe_existing = $this->ecrf_model->get_ecrf_edit($sch_id, 'fe_existing');
        $row_ecrf_fe_after_reroute = $this->ecrf_model->get_ecrf_edit($sch_id, 'fe_after');

        /*Get Priority Data*/
        $rs_prio =  $this->mglobal->lookup('pm_priority');
        $ar_prio = array();
        foreach ($rs_prio as $item) {
            $ar_prio[$item->lookup_value] = $item->lookup_desc;
        }

        // Get ECRF Token Auth by Hary
        $temp_auth = $this->get_auth_token();
        if ($temp_auth) {
            $this->auth = $temp_auth;
            $this->token = $this->auth->data->accessToken;
            $this->token_type = $this->auth->data->tokenType;
            $data['status_token'] = "";
        } else {
            $this->auth = '';
            $this->token = '';
            $this->token_type = '';
            $data['status_token'] = "<p>Koneksi API XL gagal!</p>";
        }
        $token = $this->token;
        $token_type = $this->token_type;

        $data['token_ecrf'] = $token;
        $data['token_type_ecrf'] = $token_type;
        $data["title"]    = "Edit Schedule";
        $data["priority"]    = ($ar_prio);
        $data['regions'] = ($row_regions);
        $data['sites'] = ($row_sites);
        $data['pics'] = ($row_pic);
        $data['po'] = ($row_po);
        $data['sow'] = ($row_sow);
        $data['approval'] = ($row_approval);
        $data['ecrf_ne'] = ($row_ecrf_ne);
        $data['ecrf_fe_existing'] = ($row_ecrf_fe_existing);
        $data['ecrf_fe_after_reroute'] = ($row_ecrf_fe_after_reroute);
        $data['content_id'] = 'scheduleform';
        /*Get Detail Schedule with visit_id $schid*/
        $rs_sch = $this->schedule_model->getSchedule($sch_id);
        $data['schedule'] = $rs_sch;
        $data['action_url'] = base_url("/schedule/update") . "/" . $id;
        $this->load->view('template/smashing', $data);
    }
    public function save()
    {
        // $this->load->library('email');

        $postdata = $this->input->post();
        //var_dump($postdata['approval_template']);
        //var_dump($postdata);
        //exit();

        $param_keys = array(
            'po_id', 'link_id', 'pic_id', 'visit_date', 'visit_type', 'approval_template', 'tpd_id', 'priority', 'remark'
        );

        //echo json_encode($param_keys['approval_template']);

        //exit();
        if (is_array($postdata)) {
            $data = array();
            foreach ($param_keys as $key) {
                if (array_key_exists($key, $postdata)) {
                    $data[$key] = $postdata[$key];

                    if (trim($data[$key]) == '') {
                        $data[$key] = NULL;
                    }
                } else {
                    $data[$key] = NULL;
                }
            }

            $validation_rules = array(
                array(
                    'field' => 'po_id',
                    'label' => 'PO NO.',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'link_id',
                    'label' => 'Site Link',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pic_id',
                    'label' => 'Choose PIC',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'visit_date',
                    'label' => 'Schedule Date',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'approval_template',
                    'label' => 'Approval Template',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'checklist_item',
                    'label' => 'Please choose Checklist Item',
                    'rules' => 'required'
                )
            );

            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<li>', '</li>');

            if ($this->form_validation->run()) {
                /*(1) Save data to table t_trx_schedule*/
                $post_sch = new stdClass;
                $post_sch->po_id = $data["po_id"];
                $post_sch->link_id = $data["link_id"];
                $post_sch->pic_id = $data["pic_id"];

                $post_sch->visit_date = date("Y-m-d", strtotime($data["visit_date"]));
                $post_sch->visit_type = $data["visit_type"];
                $post_sch->tpd_id = $data["tpd_id"];
                $post_sch->remark = $data["remark"];
                /*If this Field is needed, uncomment this line below*/
                // $post_sch->template_approval = $data["approval"];

                $priority = "-";
                if ($data["priority"]) {
                    $priority = $data["priority"];
                }
                /*Loook Up Region id from */
                $region = $this->reference_model->getDetailPO($data["po_id"]);
                if ($region) {
                    $post_sch->region_id = $region->region_id;
                } else {
                    $post_sch->region_id = 0;
                }
                /*end for look up region*/
                /*Default Status =0 Belum Di kunjungi*/
                $post_sch->status = 0;
                /*End Default Status*/
                $post_sch->priority = $priority;
                $post_sch->scheduled = 1; /*Scheduled yes*/
                $post_sch->fop_hs_name = "Not Defined yet";
                $post_sch->fop_hs_contact = "Not Defined yet";
                /*Default Security Code*/
                $security_check_in_code =  $this->randomString();
                $security_check_out_code =  $this->randomString();
                $post_sch->security_code_checkin = $security_check_in_code;
                $post_sch->security_code_checkout = $security_check_out_code;
                /*End Generate Security Code*/
                $post_sch->created_on = date('Y-m-d H:i:s');
                $post_sch->created_by = $this->session->userdata('user_id');
                $sch_id = $this->schedule_model->insert($post_sch);

                // Save to table t_trx_ecrf by Hary
                $trx_ecrf = new stdClass;
                $datas = array(
                    'ecrf_number' => $this->input->post("ecrf_number"),
                    'ecrf_id' => $this->input->post("ecrf_id"),
                    'ecrf_type' => $this->input->post('ecrf_type'),
                    'tower_index' => $this->input->post('tower_index'),
                    'quantity_dismantle' => $this->input->post('quantity_dismantle'),
                    'diameter_dismantle'  => $this->input->post('diameter_dismantle'),
                    'height_dismantle' => $this->input->post('height_dismantle'),
                    'quantity_add' => $this->input->post('quantity_add'),
                    'diameter_add' => $this->input->post('diameter_add'),
                    'height_add' => $this->input->post('height_add'),
                );
                if (!empty($datas['ecrf_number'])) {
                    foreach ($datas["ecrf_number"] as $key => $value) {
                        switch ($datas['ecrf_type'][$key]) {
                            case 'dismantle':
                                $trx_ecrf->site_type = $this->check_site($key);
                                $trx_ecrf->visit_id = $sch_id;
                                $trx_ecrf->ecrf_number = $datas["ecrf_number"][$key];
                                $trx_ecrf->tower_index = $datas["tower_index"][$key];
                                $trx_ecrf->ecrf_id = $datas['ecrf_id'][$key];
                                $trx_ecrf->ecrf_type = $datas['ecrf_type'][$key];
                                $trx_ecrf->quantity = $datas['quantity_dismantle'][$key];
                                $trx_ecrf->diameter = $datas['diameter_dismantle'][$key];
                                $trx_ecrf->height = $datas['height_dismantle'][$key];
                                $trx_ecrf->condition = 'dismantle';
                                $trx_ecrf->created_at = date('Y-m-d H:i:s');
                                $trx_ecrf_id = $this->ecrf_model->insert($trx_ecrf);

                                $photo_dismantle = new stdClass;
                                $photo_dismantle->visit_id = $sch_id;
                                $photo_dismantle->ecrf_id = $datas['ecrf_id'][$key];
                                $photo_dismantle->t_trx_ecrf_id = $trx_ecrf_id;
                                $photo_dismantle->image_type = 'jpeg';
                                $photo_dismantle->photo_id = generate_uuid();
                                for ($i = 1; $i <= 3; $i++) {
                                    $photo_dismantle->site_type = $this->check_site($key);
                                    $photo_dismantle->ecrf_type = $datas['ecrf_type'][$key];
                                    $photo_dismantle->photo_type = 'photo' . $i;
                                    // $resp_api_photo = $this->get_photo_url_ecrf($photo_dismantle->ecrf_id, $photo_dismantle->photo_id, $photo_dismantle->image_type, $photo_dismantle->photo_type, $this->token, $this->token_type);
                                    // $photo_dismantle->url = $resp_api_photo->data->url;
                                    // $photo_dismantle->file_name = $resp_api_photo->data->filename;
                                    $photo_dismantle->created_at = date('Y-m-d H:i:s');
                                    $this->ecrf_model->insert_photo($photo_dismantle);
                                }
                                break;
                            case 'add':
                                $trx_ecrf->site_type = $this->check_site($key);
                                $trx_ecrf->visit_id = $sch_id;
                                $trx_ecrf->ecrf_number = $datas["ecrf_number"][$key];
                                $trx_ecrf->tower_index = $datas["tower_index"][$key];
                                $trx_ecrf->ecrf_id = $datas['ecrf_id'][$key];
                                $trx_ecrf->ecrf_type = $datas['ecrf_type'][$key];
                                $trx_ecrf->quantity = $datas['quantity_add'][$key];
                                $trx_ecrf->diameter = $datas['diameter_add'][$key];
                                $trx_ecrf->height = $datas['height_add'][$key];
                                $trx_ecrf->condition = 'add';
                                $trx_ecrf->created_at = date('Y-m-d H:i:s');
                                $trx_ecrf_id = $this->ecrf_model->insert($trx_ecrf);

                                $photo_dismantle = new stdClass;
                                $photo_dismantle->site_type = $this->check_site($key);
                                $photo_dismantle->ecrf_type = $datas['ecrf_type'][$key];
                                $photo_dismantle->visit_id = $sch_id;
                                $photo_dismantle->ecrf_id = $datas['ecrf_id'][$key];
                                $photo_dismantle->t_trx_ecrf_id = $trx_ecrf_id;
                                $photo_dismantle->image_type = 'jpeg';
                                $photo_dismantle->photo_type = 'photo1';
                                $photo_dismantle->photo_id = generate_uuid();
                                // $resp_api_photo = $this->get_photo_url_ecrf($photo_dismantle->ecrf_id, $photo_dismantle->photo_id, $photo_dismantle->image_type, $photo_dismantle->photo_type, $this->token, $this->token_type);
                                // $photo_dismantle->url = $resp_api_photo->data->url;
                                // $photo_dismantle->file_name = $resp_api_photo->data->filename;
                                $photo_dismantle->created_at = date('Y-m-d H:i:s');
                                $this->ecrf_model->insert_photo($photo_dismantle);
                                break;
                            case 'swap':
                                $trx_ecrf->site_type = $this->check_site($key);
                                $trx_ecrf->visit_id = $sch_id;
                                $trx_ecrf->ecrf_number = $datas["ecrf_number"][$key];
                                $trx_ecrf->tower_index = $datas["tower_index"][$key];
                                $trx_ecrf->ecrf_id = $datas['ecrf_id'][$key];
                                $trx_ecrf->ecrf_type = $datas['ecrf_type'][$key];
                                for ($i = 0; $i < 2; $i++) {
                                    if ($i == 0) {
                                        $trx_ecrf->quantity = $datas['quantity_dismantle'][$key];
                                        $trx_ecrf->diameter = $datas['diameter_dismantle'][$key];
                                        $trx_ecrf->height = $datas['height_dismantle'][$key];
                                        $trx_ecrf->condition = 'dismantle';
                                        $trx_ecrf->created_at = date('Y-m-d H:i:s');
                                        $trx_ecrf_id = $this->ecrf_model->insert($trx_ecrf);

                                        $photo_dismantle = new stdClass;
                                        $photo_dismantle->visit_id = $sch_id;
                                        $photo_dismantle->ecrf_id = $datas['ecrf_id'][$key];
                                        $photo_dismantle->t_trx_ecrf_id = $trx_ecrf_id;
                                        $photo_dismantle->image_type = 'jpeg';
                                        $photo_dismantle->photo_id = generate_uuid();
                                        for ($k = 1; $k <= 3; $k++) {
                                            $photo_dismantle->site_type = $this->check_site($key);
                                            $photo_dismantle->ecrf_type = $datas['ecrf_type'][$key];
                                            $photo_dismantle->photo_type = 'photo' . $k;
                                            // $resp_api_photo = $this->get_photo_url_ecrf($photo_dismantle->ecrf_id, $photo_dismantle->photo_id, $photo_dismantle->image_type, $photo_dismantle->photo_type, $this->token, $this->token_type);
                                            // $photo_dismantle->url = $resp_api_photo->data->url;
                                            // $photo_dismantle->file_name = $resp_api_photo->data->filename;
                                            $photo_dismantle->created_at = date('Y-m-d H:i:s');
                                            $this->ecrf_model->insert_photo($photo_dismantle);
                                        }
                                    } else {
                                        $trx_ecrf->quantity = $datas['quantity_add'][$key];
                                        $trx_ecrf->diameter = $datas['diameter_add'][$key];
                                        $trx_ecrf->height = $datas['height_add'][$key];
                                        $trx_ecrf->condition = 'add';
                                        $trx_ecrf->created_at = date('Y-m-d H:i:s');
                                        $trx_ecrf_id = $this->ecrf_model->insert($trx_ecrf);

                                        $photo_add = new stdClass;
                                        $photo_add->visit_id = $sch_id;
                                        $photo_add->site_type = $this->check_site($key);
                                        $photo_add->ecrf_type = $datas['ecrf_type'][$key];
                                        $photo_add->ecrf_id = $datas['ecrf_id'][$key];
                                        $photo_add->t_trx_ecrf_id = $trx_ecrf_id;
                                        $photo_add->image_type = 'jpeg';
                                        $photo_add->photo_type = 'photo1';
                                        $photo_add->photo_id = generate_uuid();
                                        // $resp_api_photo = $this->get_photo_url_ecrf($photo_add->ecrf_id, $photo_add->photo_id, $photo_add->image_type, $photo_add->photo_type, $this->token, $this->token_type);
                                        // $photo_add->url = $resp_api_photo->data->url;
                                        // $photo_add->file_name = $resp_api_photo->data->filename;
                                        $photo_add->created_at = date('Y-m-d H:i:s');
                                        $this->ecrf_model->insert_photo($photo_add);
                                    }
                                }
                                break;
                            default:

                                break;
                        }
                    }
                }

                // save to table t_trx_ecrf_approval by hary
                $data_apprv_ecrf = new stdClass();
                $data_apprv_ecrf->visit_id = $sch_id;
                $data_apprv_ecrf->remarks = '';
                $data_apprv_ecrf->created_at = date('Y-m-d H:i:s');
                $data_apprv_ecrf->status_approve = '';
                $data_apprv_ecrf->status_send_api = 0;
                $this->ecrf_model->insert_approval($sch_id, $data_apprv_ecrf);

                /*(2) Save data checklist to table t_trx_schedule_checklist*/
                foreach ($this->input->post("checklist_item") as $val) {
                    $post_check = new stdClass;
                    $post_check->visit_id = $sch_id;
                    $post_check->checklist_id = $val;
                    $this->schedule_checklist_model->insert($post_check);
                }
                /*(3) Iterate all user from Template choosen and save each of them in t_trx_schedule_result_approval*/
                $this->schedule_approval_model->remove($sch_id);
                $rs_approval_users = $this->reference_model->getuserapproval($data["approval_template"]);
                foreach ($rs_approval_users as $item) {
                    /*(4) Save Each User Approval to t_trx_schedule_result_approval*/
                    $post_approval_user = new stdClass;
                    $post_approval_user->visit_id = $sch_id;
                    $post_approval_user->order_no = $item->order_no;
                    $post_approval_user->user_id = $item->user_id;
                    $this->schedule_approval_model->insert($post_approval_user);
                }

                //send to fe
                $sqlfe = $this->db->query("select * from t_mtr_user where user_id = '" . $postdata['pic_id'] . "' ")->row();
                //send to aproval
                $sql = $this->db->query("select a.*,b.email from t_mtr_approval a
		                                 left join t_mtr_user b on b.user_id = a.user_id
		                                 WHERE a.template_name ilike '" . $postdata['approval_template'] . "' and a.order_no = 1 and a.is_deleted = 0")->result();

                $mail_template = $this->db->get_where('t_ref_mail_notif', array('template_name' => 'NEWSCHED', 'is_active' => 1))->row();

                $schedule = $this->schedule_model->getScheduleData($sch_id);

                $mail_content = self::inject_data($schedule, $mail_template->parameter, $mail_template->content);

                foreach ($sql as $rs) {
                    self::push_notif_email(
                        array(
                            'subject'            => $mail_template->subject,
                            'content'            => $mail_content,
                            'recipient_address'    => $rs->email
                        )
                    );
                }

                self::push_notif_email(
                    array(
                        'subject'            => $mail_template->subject,
                        'content'            => $mail_content,
                        'recipient_address'    => $sqlfe->email
                    )
                );

                $json = array('status' => 'success', 'message' => 'Schedule Data is inserted successfully.');
                echo json_encode($json);
                exit;
            } else {
                $json = array('status' => 'error', 'message' => validation_errors());
                echo json_encode($json);
                exit;
            }
        }
    }
    /*Function Update Schedule*/
    public function update($id)
    {
        $sch_id = $this->core->decode($id);
        $schedule = $this->schedule_model->getSchedule($sch_id);
        $postdata = $this->input->post();

        $param_keys = array(
            'po_id', 'link_id', 'pic_id', 'visit_date', 'visit_type',
            'approval_template', 'priority', 'tpd_id', 'remark'
        );

        if (is_array($postdata)) {
            $data = array();
            foreach ($param_keys as $key) {
                if (array_key_exists($key, $postdata)) {
                    $data[$key] = $postdata[$key];

                    if (trim($data[$key]) == '') {
                        $data[$key] = NULL;
                    }
                } else {
                    $data[$key] = NULL;
                }
            }

            $validation_rules = array(
                array(
                    'field' => 'po_id',
                    'label' => 'PO NO.',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'link_id',
                    'label' => 'Site Link',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pic_id',
                    'label' => 'Choose PIC',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'visit_date',
                    'label' => 'Schedule Date',
                    'rules' => 'required'
                ),
                // array(
                // 	'field' => 'approval_template',
                // 	'label' => 'Approval Template',
                // 	'rules' => 'required'
                // ),
                array(
                    'field' => 'checklist_item',
                    'label' => 'Please choose Checklist Item',
                    'rules' => 'required'
                )
            );

            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<li>', '</li>');

            if ($this->form_validation->run()) {
                /*(1) Save data to table t_trx_schedule*/
                $post_sch = new stdClass;
                $post_sch->po_id = $data["po_id"];
                $post_sch->link_id = $data["link_id"];
                $post_sch->pic_id = $data["pic_id"];
                $post_sch->visit_date = date("Y-m-d", strtotime($data["visit_date"]));
                $post_sch->visit_type = $data["visit_type"];
                $post_sch->tpd_id = $data["tpd_id"];
                $post_sch->remark = $data["remark"];
                /*If this field is needed, uncomment this line below*/
                //$post_sch->template_approval = $data["approval"];
                $priority = "-";
                if ($data["priority"]) {
                    $priority = $data["priority"];
                }
                /*Loook Up Region id from */
                $region = $this->reference_model->getDetailPO($data["po_id"]);
                if ($region) {
                    $post_sch->region_id = $region->region_id;
                } else {
                    $post_sch->region_id = 0;
                }
                /*end for look up region*/
                $post_sch->priority = $priority;
                $post_sch->scheduled = 1; /*Scheduled yes*/
                $post_sch->fop_hs_name = "Not Defined yet";
                $post_sch->fop_hs_contact = "Not Defined yet";
                $post_sch->updated_on = date('Y-m-d H:i:s');
                $post_sch->updated_by = $this->session->userdata('user_id');
                $this->schedule_model->update($post_sch, $sch_id);

                // Save to table t_trx_ecrf by Hary
                $this->ecrf_model->delete_ecrf_by_visit_id($sch_id);
                $this->ecrf_model->delete_ecrf_photo_by_visit_id($sch_id);
                $trx_ecrf = new stdClass;
                $datas = array(
                    'ecrf_number' => $this->input->post("ecrf_number"),
                    'ecrf_id' => $this->input->post("ecrf_id"),
                    'ecrf_type' => $this->input->post('ecrf_type'),
                    'tower_index' => $this->input->post('tower_index'),
                    'quantity_dismantle' => $this->input->post('quantity_dismantle'),
                    'diameter_dismantle'  => $this->input->post('diameter_dismantle'),
                    'height_dismantle' => $this->input->post('height_dismantle'),
                    'quantity_add' => $this->input->post('quantity_add'),
                    'diameter_add' => $this->input->post('diameter_add'),
                    'height_add' => $this->input->post('height_add'),
                );
                if (!empty($datas['ecrf_number'])) {
                    foreach ($datas["ecrf_number"] as $key => $value) {
                        switch ($datas['ecrf_type'][$key]) {
                            case 'dismantle':
                                $trx_ecrf->site_type = $this->check_site($key);
                                $trx_ecrf->visit_id = $sch_id;
                                $trx_ecrf->ecrf_number = $datas["ecrf_number"][$key];
                                $trx_ecrf->tower_index = $datas["tower_index"][$key];
                                $trx_ecrf->ecrf_id = $datas['ecrf_id'][$key];
                                $trx_ecrf->ecrf_type = $datas['ecrf_type'][$key];
                                $trx_ecrf->quantity = $datas['quantity_dismantle'][$key];
                                $trx_ecrf->diameter = $datas['diameter_dismantle'][$key];
                                $trx_ecrf->height = $datas['height_dismantle'][$key];
                                $trx_ecrf->condition = 'dismantle';
                                $trx_ecrf->created_at = date('Y-m-d H:i:s');
                                $trx_ecrf_id = $this->ecrf_model->insert($trx_ecrf);

                                $photo_dismantle = new stdClass;
                                $photo_dismantle->visit_id = $sch_id;
                                $photo_dismantle->ecrf_id = $datas['ecrf_id'][$key];
                                $photo_dismantle->t_trx_ecrf_id = $trx_ecrf_id;
                                $photo_dismantle->image_type = 'jpeg';
                                $photo_dismantle->photo_id = generate_uuid();
                                for ($i = 1; $i <= 3; $i++) {
                                    $photo_dismantle->site_type = $this->check_site($key);
                                    $photo_dismantle->ecrf_type = $datas['ecrf_type'][$key];
                                    $photo_dismantle->photo_type = 'photo' . $i;
                                    // $resp_api_photo = $this->get_photo_url_ecrf($photo_dismantle->ecrf_id, $photo_dismantle->photo_id, $photo_dismantle->image_type, $photo_dismantle->photo_type, $this->token, $this->token_type);
                                    // $photo_dismantle->url = $resp_api_photo->data->url;
                                    // $photo_dismantle->file_name = $resp_api_photo->data->filename;
                                    $photo_dismantle->created_at = date('Y-m-d H:i:s');
                                    $this->ecrf_model->insert_photo($photo_dismantle);
                                }
                                break;
                            case 'add':
                                $trx_ecrf->site_type = $this->check_site($key);
                                $trx_ecrf->visit_id = $sch_id;
                                $trx_ecrf->ecrf_number = $datas["ecrf_number"][$key];
                                $trx_ecrf->tower_index = $datas["tower_index"][$key];
                                $trx_ecrf->ecrf_id = $datas['ecrf_id'][$key];
                                $trx_ecrf->ecrf_type = $datas['ecrf_type'][$key];
                                $trx_ecrf->quantity = $datas['quantity_add'][$key];
                                $trx_ecrf->diameter = $datas['diameter_add'][$key];
                                $trx_ecrf->height = $datas['height_add'][$key];
                                $trx_ecrf->condition = 'add';
                                $trx_ecrf->created_at = date('Y-m-d H:i:s');
                                $trx_ecrf_id = $this->ecrf_model->insert($trx_ecrf);

                                $photo_add = new stdClass;
                                $photo_add->site_type = $this->check_site($key);
                                $photo_add->ecrf_type = $datas['ecrf_type'][$key];
                                $photo_add->visit_id = $sch_id;
                                $photo_add->ecrf_id = $datas['ecrf_id'][$key];
                                $photo_add->t_trx_ecrf_id = $trx_ecrf_id;
                                $photo_add->image_type = 'jpeg';
                                $photo_add->photo_type = 'photo1';
                                $photo_add->photo_id = generate_uuid();
                                // $resp_api_photo = $this->get_photo_url_ecrf($photo_add->ecrf_id, $photo_add->photo_id, $photo_add->image_type, $photo_add->photo_type, $this->token, $this->token_type);
                                // $photo_add->url = $resp_api_photo->data->url;
                                // $photo_add->file_name = $resp_api_photo->data->filename;
                                $photo_add->created_at = date('Y-m-d H:i:s');
                                $this->ecrf_model->insert_photo($photo_add);
                                break;
                            case 'swap':
                                $trx_ecrf->site_type = $this->check_site($key);
                                $trx_ecrf->visit_id = $sch_id;
                                $trx_ecrf->ecrf_number = $datas["ecrf_number"][$key];
                                $trx_ecrf->tower_index = $datas["tower_index"][$key];
                                $trx_ecrf->ecrf_id = $datas['ecrf_id'][$key];
                                $trx_ecrf->ecrf_type = $datas['ecrf_type'][$key];
                                for ($i = 0; $i < 2; $i++) {
                                    if ($i == 0) {
                                        $trx_ecrf->quantity = $datas['quantity_dismantle'][$key];
                                        $trx_ecrf->diameter = $datas['diameter_dismantle'][$key];
                                        $trx_ecrf->height = $datas['height_dismantle'][$key];
                                        $trx_ecrf->condition = 'dismantle';
                                        $trx_ecrf->created_at = date('Y-m-d H:i:s');
                                        $trx_ecrf_id = $this->ecrf_model->insert($trx_ecrf);

                                        $photo_dismantle = new stdClass;
                                        $photo_dismantle->visit_id = $sch_id;
                                        $photo_dismantle->ecrf_id = $datas['ecrf_id'][$key];
                                        $photo_dismantle->t_trx_ecrf_id = $trx_ecrf_id;
                                        $photo_dismantle->image_type = 'jpeg';
                                        $photo_dismantle->photo_id = generate_uuid();
                                        for ($k = 1; $k <= 3; $k++) {
                                            $photo_dismantle->site_type = $this->check_site($key);
                                            $photo_dismantle->ecrf_type = $datas['ecrf_type'][$key];
                                            $photo_dismantle->photo_type = 'photo' . $k;
                                            // $resp_api_photo = $this->get_photo_url_ecrf($photo_dismantle->ecrf_id, $photo_dismantle->photo_id, $photo_dismantle->image_type, $photo_dismantle->photo_type, $this->token, $this->token_type);
                                            // $photo_dismantle->url = $resp_api_photo->data->url;
                                            // $photo_dismantle->file_name = $resp_api_photo->data->filename;
                                            $photo_dismantle->created_at = date('Y-m-d H:i:s');
                                            $this->ecrf_model->insert_photo($photo_dismantle);
                                        }
                                    } else {
                                        $trx_ecrf->quantity = $datas['quantity_add'][$key];
                                        $trx_ecrf->diameter = $datas['diameter_add'][$key];
                                        $trx_ecrf->height = $datas['height_add'][$key];
                                        $trx_ecrf->condition = 'add';
                                        $trx_ecrf->created_at = date('Y-m-d H:i:s');
                                        $trx_ecrf_id = $this->ecrf_model->insert($trx_ecrf);

                                        $photo_add = new stdClass;
                                        $photo_add->visit_id = $sch_id;
                                        $photo_add->site_type = $this->check_site($key);
                                        $photo_add->ecrf_type = $datas['ecrf_type'][$key];
                                        $photo_add->ecrf_id = $datas['ecrf_id'][$key];
                                        $photo_add->t_trx_ecrf_id = $trx_ecrf_id;
                                        $photo_add->image_type = 'jpeg';
                                        $photo_add->photo_type = 'photo1';
                                        $photo_add->photo_id = generate_uuid();
                                        // $resp_api_photo = $this->get_photo_url_ecrf($photo_add->ecrf_id, $photo_add->photo_id, $photo_add->image_type, $photo_add->photo_type, $this->token, $this->token_type);
                                        // $photo_add->url = $resp_api_photo->data->url;
                                        // $photo_add->file_name = $resp_api_photo->data->filename;
                                        $photo_add->created_at = date('Y-m-d H:i:s');
                                        $this->ecrf_model->insert_photo($photo_add);
                                    }
                                }
                                break;
                            default:

                                break;
                        }
                    }
                }

                // check if approval alreadyor not and save to table t_trx_ecrf_approval by hary
                $check_approval_ecrf = $this->db->from('t_trx_ecrf_approval')->where('visit_id', $sch_id)->get()->row();
                if (empty($check_approval_ecrf)) {
                    $data_apprv_ecrf = new stdClass();
                    $data_apprv_ecrf->visit_id = $sch_id;
                    $data_apprv_ecrf->remarks = '';
                    $data_apprv_ecrf->created_at = date('Y-m-d H:i:s');
                    $data_apprv_ecrf->status_approve = '';
                    $data_apprv_ecrf->status_send_api = 0;
                    $this->ecrf_model->insert_approval($sch_id, $data_apprv_ecrf);
                }

                /*(2) Save data checklist to table t_trx_schedule_checklist*/
                /*Clear First checklist schedule before*/
                $this->schedule_checklist_model->remove($sch_id);
                foreach ($this->input->post("checklist_item") as $val) {
                    $post_check = new stdClass;
                    $post_check->visit_id = $sch_id;
                    $post_check->checklist_id = $val;
                    $this->schedule_checklist_model->insert($post_check);
                }
                /*(3) Iterate all user from Template choosen and save each of them in t_trx_schedule_result_approval*/
                if (!empty($data["approval_template"])) {
                    $rs_approval_users = $this->reference_model->getuserapproval($data["approval_template"]);
                    /*Check Fisrt is $data_approval == $sch_template_approval */
                    //if(!($schedule->template_approval == $data["approval"]))
                    //{
                    /*IF NO Clear Approval by visit_id then insert new user template*/
                    $this->schedule_approval_model->remove($sch_id);
                    /*Insert new Approval user*/
                    foreach ($rs_approval_users as $item) {
                        /*(4) Save Each User Approval to t_trx_schedule_result_approval*/
                        $post_approval_user = new stdClass;
                        $post_approval_user->visit_id = $sch_id;
                        $post_approval_user->order_no = $item->order_no;
                        $post_approval_user->user_id = $item->user_id;
                        $this->schedule_approval_model->insert($post_approval_user);
                    }
                }
                //}
                $json = array('status' => 'success', 'message' => 'Schedule Data is updated successfully.');
                echo json_encode($json);
                exit;
            } else {
                $json = array('status' => 'error', 'message' => validation_errors());

                echo json_encode($json);
                exit;
            }
        }
    }
    /*Function to Save Approval*/
    /*SAVE REJECT OR APROVE*/
    public function approval($visit_id)
    {
        $visit_id = $this->core->decode($visit_id);
        $param_keys = array('status', 'remarks');
        $postdata = $this->input->post();
        if (is_array($postdata)) {
            $data = array();
            foreach ($param_keys as $key) {
                if (array_key_exists($key, $postdata)) {
                    $data[$key] = $postdata[$key];

                    if (trim($data[$key]) == '') {
                        $data[$key] = NULL;
                    }
                } else {
                    $data[$key] = NULL;
                }
            }

            $validation_rules = array(
                array(
                    'field' => 'status',
                    'label' => 'State Approve or Reject',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'remarks',
                    'label' => 'Some Notes (Please fill Remarks)',
                    'rules' => 'required'
                )
            );

            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<li>', '</li>');

            if ($this->form_validation->run()) {
                /*If Status Formulir = 1 Approve*/
                if ($data["status"] == 1) {
                    $fop_name = $this->input->post("fop_name");
                    $fop_contact = $this->input->post("fop_contact");

                    if (empty($fop_name) || empty($fop_contact)) {
                        $json = array('status' => 'error', 'message' => "Fill FOP Name and Contact");
                        echo json_encode($json);
                        exit;
                    } else {
                        $post_approval_schedule = new stdClass;
                        $post_approval_schedule->approval = 1;
                        $post_approval_schedule->approval_remarks = $this->input->post("remarks");
                        $post_approval_schedule->fop_hs_name = $fop_name;
                        $post_approval_schedule->fop_hs_contact = $fop_contact;
                        /* Status 1 Confirmed Schedule*/
                        $post_approval_schedule->status = 1;
                        /*End Default Status*/
                        $post_approval_schedule->updated_on = date('Y-m-d H:i:s');
                        $post_approval_schedule->updated_by = $this->session->userdata('user_id');
                        $this->schedule_model->update($post_approval_schedule, $visit_id);
                    }
                } else if ($data["status"] == 2) {
                    $cancellation_reason = $this->input->post("cancellationRemark");
                    if (empty($cancellation_reason)) {
                        $json = array('status' => 'error', 'message' => "Fill Cancellation Reason");
                        echo json_encode($json);
                        exit;
                    } else {
                        $post_cancel_schedule = new stdClass;
                        $post_reject_schedule->updated_on = date('Y-m-d H:i:s');
                        /* Status 2 Decline*/
                        $post_reject_schedule->status = 2;
                        $post_reject_schedule->cancellation_remark = $this->input->post("remarks");
                        $post_reject_schedule->cancellation_reason = $this->input->post("reschedule_reason");
                        $post_reject_schedule->updated_by = $this->session->userdata('user_id');
                        $this->schedule_model->update($post_reject_schedule, $visit_id);
                    }
                } else {/*If Status Formulir = 0 or others Reject*/
                    $proposed_date = $this->input->post("proposed_date");
                    if (empty($proposed_date)) {
                        $json = array('status' => 'error', 'message' => "Fill Proposed Date");
                        echo json_encode($json);
                        exit;
                    } else {
                        $post_reject_schedule = new stdClass;
                        $post_reject_schedule->approval = 0;/*Reject*/
                        $post_reject_schedule->proposed_date = date("Y-m-d", strtotime($proposed_date));/*Reject*/
                        $post_reject_schedule->updated_on = date('Y-m-d H:i:s');
                        /* Status 5 Re-Schedule*/
                        $post_reject_schedule->status = 5;
                        $post_reject_schedule->remark_reschedule = $this->input->post("remarks");
                        $post_reject_schedule->reschedule_reason = $this->input->post("reschedule_reason");
                        $post_reject_schedule->updated_by = $this->session->userdata('user_id');
                        $this->schedule_model->update($post_reject_schedule, $visit_id);
                    }
                }
                $json = array('status' => 'success', 'message' => 'Schedule has been updated with successfully.');
                echo json_encode($json);
                exit;
            } else {
                $json = array('status' => 'error', 'message' => validation_errors());

                echo json_encode($json);
                exit;
            }
        }
    }
    /*Function Generate Security Code*/
    public function securitycode()
    {
        $remark = $this->input->post("remark");
        $visit_id = $this->input->post("visit_id");
        if ($remark) {
            // $security_check_in_code =  $this->randomString();
            // $security_check_out_code =  $this->randomString();
            $post_sec_code = new stdClass;
            // $post_sec_code->security_code_checkin = $security_check_in_code;
            // $post_sec_code->security_code_checkout = $security_check_out_code;
            $post_sec_code->security_code_remark = $remark;
            $post_sec_code->is_code_show = 1;/*Show code*/
            $post_sec_code->security_code_generate_date = date('Y-m-d');
            $post_sec_code->security_code_generate_by = $this->session->userdata('user_id');
            $this->schedule_model->update($post_sec_code, $visit_id);
            /*Get Security Code*/
            $rs_sch = $this->schedule_model->getSchedule($visit_id);
            $security_check_in_code =  $rs_sch->security_code_checkin;
            $security_check_out_code =  $rs_sch->security_code_checkout;

            $json = array(
                'status' => 'success', 'message' => 'Code Generated Succesfully',
                'code_chk_in' => $security_check_in_code,
                'code_chk_out' => $security_check_out_code
            );
            echo json_encode($json);
            exit;
        } else {
            $json = array('status' => 'success', 'message' => 'Please Fill Remark when Generate Security Code');
            echo json_encode($json);
            exit;
        }
    }
    public function randomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $randstring = '';
        for ($i = 0; $i < 5; $i++) {
            $randstring .= $characters[rand(0, (strlen($characters) - 1))];
        }
        return $randstring;
    }
    private function _check_access()
    {
        if (!$this->session->userdata('is_login')) {
            if ($this->core->is_ajax()) {
                echo json_encode(array(
                    'loggedin' => FALSE,
                    'message' => 'You are not logged in. You will be redirected to login page.',
                    'redirect_url' => site_url('login')
                ));
                exit;
            } else {
                redirect('login');
            }
        }
    }

    public function view($view)
    {
        $this->load->view($view);
    }

    public function getDetailSite()
    {
        $site_id = $this->input->get("site_id");
        $res = $this->reference_model->getDetailSite($site_id);
        echo json_encode($res);
    }
    public function getInfoLink()
    {
        $link_id = $this->input->get("link");
        echo json_encode($this->reference_model->getSiteInfo($link_id));
    }
    /*Function get All Option Checklist from schedule_id*/
    public function listchecklist($schedule_id)
    {
        /*Decode first ID*/
        echo json_encode($this->schedule_checklist_model->getChecklistOption($schedule_id));
    }

    public function detailSchedule($visit_id)
    {
        $sch_id = $this->core->decode($visit_id);
        $rs_sch = $this->schedule_model->getSchedule($sch_id);
        echo json_encode($rs_sch);
    }


    private function push_notif_email($data = array())
    {
        $header = array(
            'email_type_id'                => 1,
            'email_date'                => date('Y-m-d'),
            'email_reply_to_address'    => 'eatp-alita@alita.id',
            'email_subject'                => $data['subject'],
            'email_content'                => $data['content'],
            'foreign_id'                => 1,
            'created_by'                => $this->session->userdata('user_name'),
            'created_on'                => date('Y-m-d H:i:s')
        );
        $this->db->insert('t_msg_email_header', $header);
        $header_id = $this->db->insert_id();

        $detail = array(
            'email_id'            => $header_id,
            'recipient_type'    => 'to',
            'recipient_address'    => $data['recipient_address'],
            'created_by'        => $this->session->userdata('user_name'),
            'created_on'        => date('Y-m-d H:i:s')
        );
        $this->db->insert('t_msg_email_detail', $detail);
    }

    private function inject_data($data = array(), $params = '', $template = '')
    {

        $param = str_replace(array("[", "]"), "", $params);
        $param = explode(",", $param);
        $params = explode(",", $params);

        $values = array();
        foreach ($param as $x => $value) {
            if (array_key_exists($value, $data)) {
                $values[$x] = $data[$value];
            } else {
                $value[$x] = "-";
            }
        }

        return str_replace($params, $values, $template);
    }

    // add by Hary
    private function get_auth_token()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . 'proxy/lms/v1/s/post-generatetoken?API_KEY=' . $this->api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                'userId'   => $this->config->item('ecrf_user_id'),
                'password' => $this->config->item('ecrf_password'),
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    private function get_photo_url_ecrf($ecrf_id, $photo_id, $image_type, $photo_type, $token, $token_type)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . 'proxy/lms/ecrf/v1/s/post-submit-install-document-photo?API_KEY=' . $this->api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
				"ecrfId": "' . $ecrf_id . '",
				"photoId": "' . $photo_id . '",
				"imageType": "' . $image_type . '",
				"photoType": "' . $photo_type . '"
			}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token_type . ' ' . $token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    private function check_site($key)
    {
        $result = '';
        switch ($key) {
            case 0:
                $result = 'ne';
                break;
            case 1:
                $result = 'fe_existing';
                break;
            case 2:
                $result = 'fe_after';
                break;
            default:
                break;
        }

        return $result;
    }

    public function send_ECRF_img()
    {
        $visit_id = $this->input->get('visit_id');
        $data = $this->db->from('t_trx_ecrf')->where('visit_id', $visit_id)->get()->result();
        $temp_auth = $this->get_auth_token();
        if ($temp_auth) {
            $this->auth = $temp_auth;
            $this->token = $this->auth->data->accessToken;
            $this->token_type = $this->auth->data->tokenType;
        } else {
            $this->auth = '';
            $this->token = '';
            $this->token_type = '';
        }
        $result = array();

        foreach ($data as $value) {
            $photos = $this->db->from('t_trx_ecrf_photo')->where('visit_id', $visit_id)->where('ecrf_id', $value->ecrf_id)->get()->result();
            $data_photo = array();

            switch ($value->condition) {
                case 'dismantle':
                    if ($value->site_type == 'ne') {
                        $data_photo = $this->db->from('t_trx_checklist_result_photo')->where('visit_id', $visit_id)->where_in('checklist_id', ['489', '496', '500'])->get()->result();
                    } else {
                        $data_photo = $this->db->from('t_trx_checklist_result_photo')->where('visit_id', $visit_id)->where_in('checklist_id', ['510', '512', '516'])->get()->result();
                    }
                    break;
                case 'add':
                    if ($value->site_type == 'ne') {
                        $data_photo = $this->db->from('t_trx_checklist_result_photo')->where('visit_id', $visit_id)->where('checklist_id', ' 286')->get()->result();
                    } else {
                        $data_photo = $this->db->from('t_trx_checklist_result_photo')->where('visit_id', $visit_id)->where('checklist_id', '471')->get()->result();
                    }
                    break;
                default:
                    break;
            }

            $equipmentType = "MW";
            $condition = $value->condition;
            $quantity = $value->quantity;
            $height = $value->height;
            $diameter = $value->diameter;

            foreach ($data_photo as $show) {

                $result['' . $value->ecrf_id . '']['id'] = $value->ecrf_id;
                foreach ($photos as $cek) {
                    if (($show->checklist_id == '489' || $show->checklist_id == '510' || $show->checklist_id == ' 286' || $show->checklist_id == '471') && $cek->photo_type == 'photo1') {
                        $result['' . $value->ecrf_id . '']['photo1'] = (object) array_merge((array) $show, (array) $cek, compact('equipmentType', 'condition', 'quantity', 'height', 'diameter'));
                    }
                    if (($show->checklist_id == '496' || $show->checklist_id == '512') && $cek->photo_type == 'photo2') {
                        $result['' . $value->ecrf_id . '']['photo2'] = (object) array_merge((array) $show, (array) $cek, compact('equipmentType', 'condition', 'quantity', 'height', 'diameter'));
                    }
                    if (($show->checklist_id == '500' || $show->checklist_id == '516') && $cek->photo_type == 'photo3') {
                        $result['' . $value->ecrf_id . '']['photo3'] = (object) array_merge((array) $show, (array) $cek, compact('equipmentType', 'condition', 'quantity', 'height', 'diameter'));
                    }
                }
            }
        }
        $send = array();
        $multiple = array();
        foreach ($result as $api) {
            $list = array();
            $send['ecrfId'] = $api['id'];
            $send['remark'] = '';

            $number = 0;
            foreach ($api as $key => $ress) {
                if ($key !== 'id') {
                    if ($number < 1 && $ress->condition == "dismantle") {
                        $list[] = array(
                            'id' => $ress->photo_id,
                            'equipmentType' => 'MW',
                            'condition' => ucfirst($ress->condition),
                            'quantity' => $ress->quantity,
                            'height' => $ress->height,
                            'diameter' => $ress->diameter,
                            'type' => ''
                        );
                        $number++;
                    } elseif ($ress->condition == "add") {
                        $list[] = array(
                            'id' => $ress->photo_id,
                            'equipmentType' => 'MW',
                            'condition' => ucfirst($ress->condition),
                            'quantity' => $ress->quantity,
                            'height' => $ress->height,
                            'diameter' => $ress->diameter,
                            'type' => ''
                        );
                    }
                }
            }
            $listes[] = $list[0];
            $send['list'] = $list;
            $multiple[] = $send;
        }
        $json = array();
        foreach ($multiple as $key => $value) {
            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->url . 'proxy/lms/ecrf/v1/s/post-submit-install-document?API_KEY=' . $this->api_key,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($value),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: ' . $this->token_type . ' ' . $this->token,
                        'Content-Type: application/json',
                    ),
                ));
                $response['send_document'] = curl_exec($curl);
                curl_close($curl);
                $json[] = $response;
            } catch (\Throwable $th) {
                $json[] = $th;
            }
        }
        echo json_encode($json);
    }

    public function send_unit_ECRF_img()
    {
        $visit_id = $this->input->get('visit_id');
        $photo_id = $this->input->get('photo_id');
        $trx_ecrf_id = $this->input->get('trx_ecrf_id');
        $index_photo = $this->input->get('index_photo');
        $temp_auth = $this->get_auth_token();
        if ($temp_auth) {
            $this->auth = $temp_auth;
            $this->token = $this->auth->data->accessToken;
            $this->token_type = $this->auth->data->tokenType;
        } else {
            $this->auth = '';
            $this->token = '';
            $this->token_type = '';
        }
        // find photo
        $findPhoto = $this->db->select('t_trx_checklist_result.visit_id, t_trx_checklist_result_photo.created_on, t_trx_checklist_result.checklist_id, t_trx_checklist_result.latitude, t_trx_checklist_result.longitude, t_trx_checklist_result_photo.photo_name, t_trx_checklist_result_photo.result_photo_id')
            ->from('t_trx_checklist_result_photo')
            ->join('t_trx_checklist_result', 't_trx_checklist_result.visit_id = t_trx_checklist_result_photo.visit_id AND t_trx_checklist_result.checklist_id = t_trx_checklist_result_photo.checklist_id')
            ->where('result_photo_id', $photo_id)->get()->row();
        // find url post api 
        $findUrl = $this->db->from('t_trx_ecrf_photo')->where('t_trx_ecrf_id', $trx_ecrf_id)->get()->result();
        $resp_api_photo = $this->get_photo_url_ecrf($findUrl[$index_photo]->ecrf_id, $findUrl[$index_photo]->photo_id, $findUrl[$index_photo]->image_type, $findUrl[$index_photo]->photo_type, $this->token, $this->token_type);
        // $dataArr = array(
        //     'url' => $resp_api_photo->data->url,
        //     'file_name' => $resp_api_photo->data->filename
        // );
        // // var_dump($resp_api_photo);
        // $this->db->where('id', $findUrl[$index_photo]->id)->update('t_trx_ecrf_photo', $dataArr);

        // save image and add longitude latitude before send
        $url = 'http://10.0.5.85:5050/' . $findPhoto->photo_name;
        $filename = $resp_api_photo->data->filename;
        file_put_contents('upload_ecrf/' . $filename, file_get_contents($url));
        $this->generate_imageLatLong('upload_ecrf/' . $filename, $findPhoto->latitude, $findPhoto->longitude, $findPhoto->created_on, 'upload_ecrf/' . $filename);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $resp_api_photo->data->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS =>  file_get_contents(site_url('upload_ecrf/' . $filename)),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/plain'
            ),
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpcode == 403) {
            $response['send_document'] = 'failed auth';
            echo json_encode($response);
            unlink('upload_ecrf/' . $filename);
        } else {
            $response['send_document'] = 'success';
            echo json_encode($response);
            unlink('upload_ecrf/' . $filename);
        }
    }

    private function generate_imageLatLong($filename, $lat, $lng, $date, $save_path)
    {
        $img = imagecreatefromjpeg($filename);
        $img = imagecreatefromjpeg($filename);
        list($width, $height, $type, $attr) = getimagesize($filename);


        if ($width >= 2000) {
            $fontSize = 50;
        } elseif ($width >= 1000 and $width < 2000) {
            $fontSize = 30;
        } else {
            $fontSize = 14;
        }

        $txt = "latitude: " . $lat . ", 
" . "longitude: " . $lng . ",
" . "date: " . $date;
        $fontFile = "C:\Windows\Fonts\mvboli.ttf";
        $fontColor = imagecolorallocate($img, 255, 51, 0);
        $glow_color     = imagecolorallocate($img,  255, 255, 255);
        $posX = 5;
        $posY = 24;
        $angle = 0;

        $bbox = imagettfbbox(10, 45, $fontFile, $txt);

        $x = $bbox[0] + (imagesx($img) / 2) - ($bbox[4] / 2) - 25;
        $y = $bbox[1] + (imagesy($img) / 2) - ($bbox[5] / 2) - 5;

        $this->imagettftextblur($img, $fontSize, $angle, $posX + 1, $posY + 1, $glow_color, $fontFile, $txt, 1);
        $this->imagettftextblur($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $txt);
        // header("Content-type: image/jpeg");
        imagejpeg($img, $save_path);
    }

    private function imagettftextblur(
        &$image,
        $size,
        $angle,
        $x,
        $y,
        $color,
        $fontfile,
        $text,
        $blur_intensity = 0,
        $blur_filter = IMG_FILTER_GAUSSIAN_BLUR
    ) {
        // $blur_intensity needs to be an integer greater than zero; if it is not we
        // treat this function call identically to imagettftext
        if (is_int($blur_intensity) && $blur_intensity > 0) {
            // $return_array will be returned once all calculations are complete
            $return_array = [
                imagesx($image), // lower left, x coordinate
                -1,              // lower left, y coordinate
                -1,              // lower right, x coordinate
                -1,              // lower right, y coordinate
                -1,              // upper right, x coordinate
                imagesy($image), // upper right, y coordinate
                imagesx($image), // upper left, x coordinate
                imagesy($image)  // upper left, y coordinate
            ];

            // $temporary_image is a GD image that is the same size as our
            // original GD image
            $temporary_image = imagecreatetruecolor(
                imagesx($image),
                imagesy($image)
            );
            // fill $temporary_image with a black background
            imagefill(
                $temporary_image,
                0,
                0,
                imagecolorallocate($temporary_image, 0x00, 0x00, 0x00)
            );
            // add white text to $temporary_image with the function call's
            // parameters
            imagettftext(
                $temporary_image,
                $size,
                $angle,
                $x,
                $y,
                imagecolorallocate($temporary_image, 0xFF, 0xFF, 0xFF),
                $fontfile,
                $text
            );

            // execute the blur filters
            for ($blur = 1; $blur <= $blur_intensity; $blur++) {
                imagefilter($temporary_image, $blur_filter);
            }

            // set $color_opacity based on $color's transparency
            $color_opacity = imagecolorsforindex($image, $color)['alpha'];
            $color_opacity = (127 - $color_opacity) / 127;

            // loop through each pixel in $temporary_image
            for ($_x = 0; $_x < imagesx($temporary_image); $_x++) {
                for ($_y = 0; $_y < imagesy($temporary_image); $_y++) {
                    // $visibility is the grayscale of the current pixel multiplied
                    // by $color_opacity
                    $visibility = (imagecolorat(
                        $temporary_image,
                        $_x,
                        $_y
                    ) & 0xFF) / 255 * $color_opacity;
                    // if the current pixel would not be invisible then add it to
                    // $image
                    if ($visibility > 0) {
                        // we know we are on an affected pixel so ensure
                        // $return_array is updated accordingly
                        $return_array[0] = min($return_array[0], $_x);
                        $return_array[1] = max($return_array[1], $_y);
                        $return_array[2] = max($return_array[2], $_x);
                        $return_array[3] = max($return_array[3], $_y);
                        $return_array[4] = max($return_array[4], $_x);
                        $return_array[5] = min($return_array[5], $_y);
                        $return_array[6] = min($return_array[6], $_x);
                        $return_array[7] = min($return_array[7], $_y);

                        // set the current pixel in $image
                        imagesetpixel(
                            $image,
                            $_x,
                            $_y,
                            imagecolorallocatealpha(
                                $image,
                                ($color >> 16) & 0xFF,
                                ($color >> 8) & 0xFF,
                                $color & 0xFF,
                                (1 - $visibility) * 127
                            )
                        );
                    }
                }
            }

            // destroy our $temporary_image
            imagedestroy($temporary_image);

            // return $return_array;
        } else {
            // return
            imagettftext(
                $image,
                $size,
                $angle,
                $x,
                $y,
                $color,
                $fontfile,
                $text
            );
        }
    }

    public function ecrf_approval_reject()
    {
        $remarks = $this->input->post("remarks");
        $visit_id = $this->input->post("visit_id");
        if (!empty($remarks)) {
            try {
                $data = new stdClass();
                $data->visit_id = $visit_id;
                $data->remarks = $remarks;
                $data->created_at = date('Y-m-d H:i:s');
                $data->status_approve = 'reject';
                $data->status_send_api = 0;
                $this->ecrf_model->insert_approval($visit_id, $data);

                $response['message'] = 'success';
                echo json_encode($response);
            } catch (\Throwable $th) {
                $response['message'] = 'error';
                $response['status'] = $th;
                echo json_encode($response);
            }
        }
    }
    public function ecrf_approval_approve()
    {
        $visit_id = $this->input->post("visit_id");
        try {
            $data = new stdClass();
            $data->visit_id = $visit_id;
            $data->remarks = 'Approve';
            $data->created_at = date('Y-m-d H:i:s');
            $data->status_approve = 'approve';
            $data->status_send_api = 0;
            $this->ecrf_model->insert_approval($visit_id, $data);

            $response['message'] = 'success';
            echo json_encode($response);
        } catch (\Throwable $th) {
            $response['message'] = 'error';
            $response['status'] = $th;
            echo json_encode($response);
        }
    }

    public function send_status_api()
    {
        $visit_id = $this->input->get("visit_id");
        try {
            $data = array(
                'status_send_api' => 1
            );
            $this->db->where('visit_id', $visit_id);
            $this->db->update('t_trx_ecrf_approval', $data);
            $response['status'] = 'success';
            echo json_encode($response);
        } catch (\Throwable $th) {
            $response['status'] = $th;
            echo json_encode($response);
        }
    }
}
