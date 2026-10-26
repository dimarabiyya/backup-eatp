<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Email extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->mglobal->is_logged_in();
    $this->load->model(array('misr', 'memail'));
    //$this->load->model('memail');
  }

  function index() {
    redirect(base_url('email/write'));
  }

  function write() {
    $data = $this->input->get();

    if ($data['id']) {
      $q = $this->misr->getEquipmentLink(0, 0, null, $data['id']);
      $content = 'Mohon menon-aktifkan link berikut ini : <br>';

      $columns = array('app' => 'Application', 'app_issued' => 'App issued', 'app_expired' => 'App expired',
          'region' => 'Region', 'link_code_id' => 'Link ID', 'site_code_ne' => 'NE ID', 'nearendsite' => 'NE Name',
          'site_code_fe' => 'FE ID', 'farendsite' => 'FE Name', 'freq_tx' => 'Tx Freq', 'freq_rx' => 'Rx Freq');

      $content.="<ol>";
      foreach ($q->result_array() as $row) {
        $x = explode('++', $row['appname']);

        $row['app'] = $x[0];
        $row['app_issued'] = date('d M Y', strtotime($x[1]));
        $row['app_expired'] = date('d M Y', strtotime($x[2]));

        $content.="<li><ul>";
        foreach ($columns as $key => $value) {
          $content.="<li>" . $value . " = " . $row[$key] . "</li>";
        }
        $content.="</ul></li><br>";
      }
      $content.="</ol>";
      $data['content'] = $content;
    }
    $this->load->view('email/form_write', $data, FALSE);
  }

  function send() {
    $d = $this->input->post();

    $arr_to = array();
    $arr_cc = array();
    $subject = array();
    $content = array();

    if ($d['to'] != '' && $d['content'] != '') {

      $tos = explode(',', $d['to']);
      $ccs = explode(',', $d['cc']);

      foreach ($tos as $user_id) {
        if (strstr($user_id, '@')) {
          $arr_to[] = $user_id;
        } else {
          $q = $this->db->get_where('t_mtr_user', array('user_id' => trim($user_id)));
          if ($q->num_rows() > 0) {
            $email = $q->row()->email;

            if ($email != '')
              $arr_to[] = $email;
          }
        }
      }

      foreach ($ccs as $user_id) {
        if (strstr($user_id, '@')) {
          $arr_cc[] = $user_id;
        } else {
          $q = $this->db->get_where('t_mtr_user', array('user_id' => trim($user_id)));
          if ($q->num_rows() > 0) {
            $email = $q->row()->email;

            if ($email != '')
              $arr_cc[] = $email;
          }
        }
      }
      $subject = $d['subject'];
      $content = $d['content'];
      $fromEmail = $this->session->userdata('email');
      $fromUsername = $this->session->userdata('user_name');

      // $this->load->library('email');
      // $config['protocol'] = 'sendmail';
      // $config['mailpath'] = '/usr/sbin/sendmail';
      // $config['charset']  = 'iso-8859-1';
      // $config['mailtype'] = 'html';
      // $config['wordwrap'] = TRUE;
      // $this->email->initialize($config);
      // $this->email->from($fromEmail, $fromUsername);
      // $this->email->to($arr_to);
      // $this->email->cc($arr_cc); 
      // $this->email->subject($subject);
      // $this->email->message($content);  
      // $this->email->send();

      foreach ($arr_to as $key => $value) {
        $this->raa->addSingleRecipient('to', $value);
      }
      foreach ($arr_cc as $key => $value) {
        $this->raa->addSingleRecipient('cc', $value);
      }

      $this->mglobal->setEmailSchedule(1, date('Y-m-d'), date('G:i:s'), $subject, $content, 1);



      echo json_encode(array(
          'status' => 'OK',
          'hasil' => 'Email sent!',
      ));
    } else {
      echo json_encode(array(
          'status' => 'failed',
          'hasil' => 'Please complete the document.'
      ));
    }
  }

  function write_termination_letter() {
    $data = $this->input->get();

    if (isset($data['link_id_code'])) {
      $data['header'] = $this->misr->get_double_isr_header_email($data['link_id_code']);
      $data['detail'] = $this->misr->get_double_isr_detail_email($data['link_id_code']);
    }
    $this->load->view('email/form_termination_letter', $data);
  }

  function send_termination_letter() {
    $d = $this->input->post();

    $arr_to = array();
    $arr_cc = array();
    $subject = array();
    $content = array();

    if ($d['to'] != '' && $d['content'] != '') {

      $this->db->trans_start();
      $tos = explode(',', $d['to']);
      $ccs = explode(',', $d['cc']);

      foreach ($tos as $user_id) {
        if (strstr($user_id, '@')) {
          $arr_to[] = $user_id;
        } else {
          $q = $this->db->get_where('t_mtr_user', array('user_id' => trim($user_id)));
          if ($q->num_rows() > 0) {
            $email = $q->row()->email;

            if ($email != '')
              $arr_to[] = $email;
          }
        }
      }

      foreach ($ccs as $user_id) {
        if (strstr($user_id, '@')) {
          $arr_cc[] = $user_id;
        } else {
          $q = $this->db->get_where('t_mtr_user', array('user_id' => trim($user_id)));
          if ($q->num_rows() > 0) {
            $email = $q->row()->email;

            if ($email != '')
              $arr_cc[] = $email;
          }
        }
      }
      $subject = $d['subject'];
      $content = $d['content'];

      foreach ($arr_to as $key => $value) {
        $this->raa->addSingleRecipient('to', $value);
      }
      foreach ($arr_cc as $key => $value) {
        $this->raa->addSingleRecipient('cc', $value);
      }

      $email = $this->mglobal->setEmailSchedule(1, date('Y-m-d'), date('G:i:s'), $subject, $content, 1);
      $data_app_link = array(
          'email_reminder' => "{$email}"
      );
      $this->misr->update_app_link($data_app_link, $d['link_id_code']);
      $this->db->trans_complete();

      echo json_encode(array(
          'status' => 'OK',
          'hasil' => 'Email sent!'
      ));
    } else {
      echo json_encode(array(
          'status' => 'failed',
          'hasil' => 'Please complete the document.'
      ));
    }
  }

  function write_validation_letter() {
    $data = $this->input->get();
    
    $data['app'] = $this->db->get_where('t_trx_isr_app', array('app_id'=>$data['app_id']))->row();
    $data['app']->app_issued = date('d M Y', strtotime($data['app']->app_issued));
    $data['app']->app_expired = date('d M Y', strtotime($data['app']->app_expired));
    
    if (isset($data['pair_id'])) {
      $data['links'] = $this->misr->get_app_link_by_id($data['pair_id'], $data['app_id']);
    }

    $this->load->view('email/form_validation_letter', $data);
  }

  function send_validation_letter() {
    $d = $this->input->post();

    $arr_to = array();
    $arr_cc = array();
    $subject = array();
    $content = array();

    if ($d['to'] != '' && $d['content'] != '') {

      $this->db->trans_start();
      $tos = explode(',', $d['to']);
      $ccs = explode(',', $d['cc']);

      foreach ($tos as $user_id) {
        if (strstr($user_id, '@')) {
          $arr_to[] = $user_id;
        } else {
          $q = $this->db->get_where('t_mtr_user', array('user_id' => trim($user_id)));
          if ($q->num_rows() > 0) {
            $email = $q->row()->email;

            if ($email != '')
              $arr_to[] = $email;
          }
        }
      }

      foreach ($ccs as $user_id) {
        if (strstr($user_id, '@')) {
          $arr_cc[] = $user_id;
        } else {
          $q = $this->db->get_where('t_mtr_user', array('user_id' => trim($user_id)));
          if ($q->num_rows() > 0) {
            $email = $q->row()->email;

            if ($email != '')
              $arr_cc[] = $email;
          }
        }
      }
      $subject = $d['subject'];
      $content = $d['content'];

      foreach ($arr_to as $key => $value) {
        $this->raa->addSingleRecipient('to', $value);
      }
      foreach ($arr_cc as $key => $value) {
        $this->raa->addSingleRecipient('cc', $value);
      }
      
//      $milestone = 0;
//      if ($d['milestone_status'] == '1261') {
//      } else if ($d['milestone_status'] == '1361' || $d['milestone_status'] == '1661') {
//        
//      }
      
      $data_app = array(
          'app_id' => $d['app_id'],
          'locked' => 0
      );
      $this->mglobal->saveData('t_trx_isr_app', $data_app, 'app_id');
      
      $email = $this->mglobal->setEmailSchedule(1, date('Y-m-d'), date('G:i:s'), $subject, $content, 1);
      $data_app_link = array(
          'email_rejected' => "{$email}",
          'milestone_status' => $d['prev_milestone']
      );
      $this->misr->update_email_rejected_link($data_app_link, $d['pair_id'], $d['app_id']);
      $this->db->trans_complete();

      echo json_encode(array(
          'status' => 'OK',
          'hasil' => 'Email sent!',
          'engin_status' => '<span class="badge badge-danger">Rejected</span>'
      ));
    } else {
      echo json_encode(array(
          'status' => 'failed',
          'hasil' => 'Please complete the document.'
      ));
    }
  }

  function write_validation_letter_isr_bts() {
    $this->load->model('isr_bts/engineering_approval_model', 'approval_model');
    $data = $this->input->get();
    $rows = $this->approval_model->get_app_resi_link(array('app_resi_id' => $data['resi_id']));
    
    $data['links'] = array();
    foreach ($rows as $row) {
//      //Latitude
//      $lats = explode(' ', $row->latitude);
//      $deg1 = $lats[0];
//      $min1 = $lats[1];
//      $sec1 = $lats[2];
//      $dir1 = $lats[3];
//
//      $row->lat_deg = $deg1;
//      $row->lat_min = $min1;
//      $row->lat_sec = number_format($sec1, 2);
//      $row->lat_dir = strtoupper($dir1);
////Longitude
//      $longs = explode(' ', $row->longitude);
//      $deg2 = $longs[0];
//      $min2 = $longs[1];
//      $sec2 = $longs[2];
//      $dir2 = $longs[3];
//
//      $row->long_deg = $deg2;
//      $row->long_min = $min2;
//      $row->long_sec = number_format($sec2, 2);
//      $row->long_dir = strtoupper($dir2);
//
//      $row->latitude = "{$row->lat_deg}&deg{$row->lat_min}'{$row->lat_sec}\"{$row->lat_dir}";
//      $row->longitude = "{$row->long_deg}&deg{$row->long_min}'{$row->long_sec}\"{$row->long_dir}";
      
      array_push($data['links'], $row);
    }


    $this->load->view('email/form_validation_letter_isr_bts', $data);
  }

  function send_validation_letter_isr_bts() {
    $this->load->model('isr_bts/engineering_approval_model', 'approval_model');
    $d = $this->input->post();

    $arr_to = array();
    $arr_cc = array();
    $subject = array();
    $content = array();

    if ($d['to'] != '' && $d['content'] != '') {

      $this->db->trans_start();
      $tos = explode(',', $d['to']);
      $ccs = explode(',', $d['cc']);

      foreach ($tos as $user_id) {
        if (strstr($user_id, '@')) {
          $arr_to[] = $user_id;
        } else {
          $q = $this->db->get_where('t_mtr_user', array('user_id' => trim($user_id)));
          if ($q->num_rows() > 0) {
            $email = $q->row()->email;

            if ($email != '')
              $arr_to[] = $email;
          }
        }
      }

      foreach ($ccs as $user_id) {
        if (strstr($user_id, '@')) {
          $arr_cc[] = $user_id;
        } else {
          $q = $this->db->get_where('t_mtr_user', array('user_id' => trim($user_id)));
          if ($q->num_rows() > 0) {
            $email = $q->row()->email;

            if ($email != '')
              $arr_cc[] = $email;
          }
        }
      }
      $subject = $d['subject'];
      $content = $d['content'];

      foreach ($arr_to as $key => $value) {
        $this->raa->addSingleRecipient('to', $value);
      }
      foreach ($arr_cc as $key => $value) {
        $this->raa->addSingleRecipient('cc', $value);
      }

      $email = $this->mglobal->setEmailSchedule(1, date('Y-m-d'), date('G:i:s'), $subject, $content, 1);
      $data_app_link = array(
          'email_rejected' => "{$email}"
      );
      $this->approval_model->update_email_rejected_link($data_app_link, $d['app_id']);
      
      //save to isr approval for log
      $query = $this->db->get_where('t_trx_isr_bts_app_resi_link', array('app_resi_id'=>$d['app_id']));

      foreach ($query->result() as $value) {
        $data_approval = array(
            'app_id' => $d['app_id'],
            'link_id' => $value->resi_link_id,
            'status_approval' => 2,
            'email_id' => $email,
            'app_type' => 2
        );
        $this->mglobal->saveData('t_trx_isr_bts_approval', $data_approval);
      }
      
      $this->db->trans_complete();

      echo json_encode(array(
          'status' => 'OK',
          'hasil' => 'Email sent!'
      ));
    } else {
      echo json_encode(array(
          'status' => 'failed',
          'hasil' => 'Please complete the document.'
      ));
    }
  }
  
  function write_validation_letter_isr_bts2() {
    $this->load->model('isr_bts/engineering_approval_model', 'approval_model');
    $data = $this->input->get();
    $data['links'] = $this->approval_model->get_app_link(array('app_id' => $data['app_id']));
    
    $this->load->view('email/form_validation_letter_isr_bts2', $data);
  }

  function send_validation_letter_isr_bts2() {
    $this->load->model('isr_bts/engineering_approval_model', 'approval_model');
    $d = $this->input->post();

    $arr_to = array();
    $arr_cc = array();
    $subject = array();
    $content = array();

    if ($d['to'] != '' && $d['content'] != '') {

      $this->db->trans_start();
      $tos = explode(',', $d['to']);
      $ccs = explode(',', $d['cc']);

      foreach ($tos as $user_id) {
        if (strstr($user_id, '@')) {
          $arr_to[] = $user_id;
        } else {
          $q = $this->db->get_where('t_mtr_user', array('user_id' => trim($user_id)));
          if ($q->num_rows() > 0) {
            $email = $q->row()->email;

            if ($email != '')
              $arr_to[] = $email;
          }
        }
      }

      foreach ($ccs as $user_id) {
        if (strstr($user_id, '@')) {
          $arr_cc[] = $user_id;
        } else {
          $q = $this->db->get_where('t_mtr_user', array('user_id' => trim($user_id)));
          if ($q->num_rows() > 0) {
            $email = $q->row()->email;

            if ($email != '')
              $arr_cc[] = $email;
          }
        }
      }
      $subject = $d['subject'];
      $content = $d['content'];

      foreach ($arr_to as $key => $value) {
        $this->raa->addSingleRecipient('to', $value);
      }
      foreach ($arr_cc as $key => $value) {
        $this->raa->addSingleRecipient('cc', $value);
      }

      $email = $this->mglobal->setEmailSchedule(1, date('Y-m-d'), date('G:i:s'), $subject, $content, 1);
      $data_app_link = array(
          'email_rejected' => "{$email}"
      );
      $this->approval_model->update_email_rejected_link2($data_app_link, $d['app_id']);
      
      //save to isr approval for log
      $query = $this->db->get_where('t_trx_isr_bts_app_link', array('app_id'=>$d['app_id']));

      foreach ($query->result() as $value) {
        $data_approval = array(
            'app_id' => $d['app_id'],
            'link_id' => $value->app_link_id,
            'status_approval' => 2,
            'email_id' => $email,
            'app_type' => 4
        );
        $this->mglobal->saveData('t_trx_isr_bts_approval', $data_approval);
      }
      
      $this->db->trans_complete();

      echo json_encode(array(
          'status' => 'OK',
          'hasil' => 'Email sent!'
      ));
    } else {
      echo json_encode(array(
          'status' => 'failed',
          'hasil' => 'Please complete the document.'
      ));
    }
  }

//begin syst.admin broadcast email
  function broadcast($param = '') {
    if ($param == 'add') {
      $data['judul'] = 'Email Broadcast Data';
      $this->load->view('email/form_email_broadcast', $data, FALSE);
      return;
    }
    if ($param == 'save') {
      $d = $this->input->post();
      $val = array("to", "subject", "content");
      $hasil = "";

      foreach ($val as $value) {
        if ($d[$value] == "")
          $hasil.=" - Field $value required" . "\r\n";
      }
      if ($hasil != "") {
        $hasil = "Error found :" . "\r\n" . $hasil;
        echo json_encode(array(
            'status' => 'error',
            'hasil' => $hasil
        ));
      } else {
        $mail_types = array("to", "cc", "bcc");
        $mail_data = array();

        foreach ($mail_types as $types) {
          if ($d[$types] != "") {
            $recipients = explode(",", $d[$types]);
            foreach ($recipients as $value) {
              if (strlen($value) > 1) {
                if (strstr($value, "DIV:")) {
                  $div_name = explode("DIV:", $value);
                  $div_name = $div_name[1];

                  //get division id
                  $this->db->select('user_div_id');
                  $q = $this->db->get_where('t_mtr_user_division', array("user_div_name" => $div_name, 'status' => 1));

                  if ($q->num_rows() > 0) {
                    //get user id
                    $this->db->select('user_id');
                    $qr = $this->db->get_where('t_mtr_user_multi_division', array("user_div_id" => $q->row()->user_div_id));
                    if ($qr->num_rows() > 0) {

                      $res = $qr->result();
                      foreach ($res as $k_user => $v_user) {
                        //get email
                        $this->db->select('email');
                        $q = $this->db->get_where('t_mtr_user', array('user_id' => $v_user->user_id, 'status' => 1));
                        if ($q->num_rows() > 0) {

                          $res_mail = $q->result();
                          foreach ($res_mail as $k_mail => $v_mail) {
                            if (strpos($v_mail->email, '@') !== false) {
                              $mail_data[$types][] = $v_mail->email;
                            }
                          }
                        }
                      }
                    }
                  }
                } elseif (strpos($value, '@') !== false) {
                  $mail_data[$types][] = $value;
                } else {
                  $this->db->select('email');
                  $q = $this->db->get_where('t_mtr_user', array('user_id' => $value, 'status' => 1));
                  if (strpos($q->row()->email, '@') !== false) {
                    $mail_data[$types][] = $q->row()->email;
                  }
                }
              }
            }
          }
        }

        // //send email
        // $this->load->library('email');
        // //port sendmail
        // $config['protocol'] = 'sendmail';
        // $config['mailpath'] = '/usr/sbin/sendmail';
        // $config['charset']  = 'iso-8859-1';
        // $config['mailtype'] = 'html';
        // $config['wordwrap'] = TRUE;

        $header = array("email_type_id" => 1,
            "email_date" => date("Y-m-d"),
            "email_reply_to_address" => "sims@smartfren.com",
            "email_subject" => $d['subject'],
            "email_content" => $d['content'],
            "foreign_id" => 1,
            "created_on" => date("Y-m-d G:i:s"),
            "created_by" => $this->session->userdata("user_id"),
            "email_time" => date("G:i:s")
        );
        $this->db->insert("t_msg_email_header", $header);
        $header_id = $this->db->insert_id();

        foreach ($mail_types as $value) {
          foreach (array_unique($mail_data[$value]) as $email) {
            $detail = array("email_id" => $header_id,
                "recipient_type" => $value,
                "recipient_address" => $email,
                "status" => 0,
                "created_on" => date("Y-m-d G:i:s"),
                "created_by" => $this->session->userdata("user_id")
            );
            $this->db->insert("t_msg_email_detail", $detail);
          }
        }

        //port mail
        // $this->email->initialize($config);
        // $this->email->from('sims@smartfren.com', 'SiMS');
        // $this->email->to(array_unique($mail_data['to'])); 
        // if(isset($mail_data['cc'][0])){
        //     $this->email->cc(array_unique($mail_data['cc'])); 
        // }
        // if(isset($mail_data['bcc'][0])){
        //     $this->email->bcc(array_unique($mail_data['bcc'])); 
        // }
        // $this->email->subject($d['subject']);
        // $this->email->message($d['content']);
        // $this->email->send();
        //save to database
        $data_push = array(
            'email_to' => $d['to'],
            'email_cc' => $d['cc'],
            'email_bcc' => $d['bcc'],
            'email_subject' => $d['subject'],
            'email_content' => $d['content']
        );
        $q = $this->mglobal->setData("t_msg_email_broadcast", $data_push);
        echo json_encode(array(
            'status' => 'OK',
            'hasil' => ""
        ));
      }
      return;
      /* $this->_save_broadcast(); */
    }

    //load_view
    $data['judul'] = "Email Broadcast";
    $data['content_id'] = 'email/list_email_broadcast';
    $this->load->view('template/smashing', $data);
  }

  function broadcast_data() {
    $dt = $this->input->post();
    $dt['page'] = isset($dt['page']) ? intval($dt['page']) : 1;
    $dt['rows'] = isset($dt['rows']) ? intval($dt['rows']) : 10;
    $dt['offset'] = ($dt['page'] - 1) * $dt['rows'];
    $q = isset($_POST['q']) ? $this->db->escape_str($_POST['q']) : '';
    $data['q'] = strtolower($q);

    $d = $this->input->get();
    $q = $this->memail->load_data("0", "0", $data);
    $result["total"] = $q->num_rows();

    $q = $this->memail->load_data($dt['offset'], $dt['rows'], $data);
    $items = array();

    foreach ($q->result() as $row) {
      $row->created_on = date('d M Y H:i:s', strtotime($row->created_on));
      array_push($items, $row);
    }
    $result["rows"] = $items;
    echo json_encode($result);
  }

  function _save_broadcast() {
    $this->email->from($fromEmail, $fromUsername);
    $this->email->to($arr_to);
    $this->email->cc($arr_cc);

    $this->email->subject($subject);

    $this->email->message($content);

    $this->email->send();
  }

//end syst.admin broadcast email
}