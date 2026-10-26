<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
error_reporting(0);

class Scheduler extends CI_Controller {

    protected $_user_now;
    protected $_date_now;

    const SCHEDULER_TOKEN = '51mst0k3n';
    const EXPIRED_UPCOMING= 1;
    const EXPIRED_OVERDUE= 2;

    function __construct() {
        parent::__construct();
        $this->load->model('mscheduler');
        $this->_user_id = $this->session->userdata('user_id');
        $this->_date_now = date('Y-m-d H:i:s');
    }

    function test() {
        echo round(3.2, 0);
    }

    function _write_log($stext) {
        if (file_exists('/home/crontab_log/cron_log_' . date('Ymd', strtotime('-1 days')) . '.txt'))
            unlink('/home/crontab_log/cron_log_' . date('Ymd', strtotime('-1 days')) . '.txt');
        $strlog = '[' . date('Y-m-d G:i:s') . '] : ' . $stext . " \r\n";
        $fp = fopen('/home/crontab_log/cron_log_' . date('Ymd') . '.txt', "a");

        fwrite($fp, $strlog);
        fclose($fp);
        echo $strlog;
    }

    function write_log_test() {
        $fp = fopen('/home/crontab_log/cron_log_test.txt', "a");
        $proc_id = rand();
        $strlog = '[' . date('Y-m-d G:i:s') . '] : ' . $proc_id . " \r\n";

        fwrite($fp, $strlog);
        fclose($fp);
        echo $strlog;
    }

    function fetch_report() {
        $pg = $this->load->database('default', TRUE);
        $mysql = $this->load->database('reportdb', TRUE);
        echo '[' . date('Y-m-d G:i:s') . '] : Starting cron job' . "\r\n";
        $this->db->order_by('id', 'asc');
        $qreport = $this->db->get_where('t_ref_feed_scheduler', array("status" => 1));
        $qresult = $qreport->result();
        $proc_id = rand();

        if (file_exists('/home/crontab_log/cron_log_' . date('Ymd', strtotime('-1 days')) . '.txt'))
            unlink('/home/crontab_log/cron_log_' . date('Ymd', strtotime('-1 days')) . '.txt');
        $fp = fopen('/home/crontab_log/cron_log_' . date('Ymd') . '.txt', "a");

        foreach ($qresult as $row) {
            $from_time = strtotime($row->last_execute);
            $to_time = strtotime(date("Y-m-d H:i:s"));

            $diff = round(abs($to_time - $from_time) / 60, 0);
            $minute = intval($row->per_minutes_exec);

            if ($diff >= $minute) {

                $filehandle = fopen("/home/crontab_log/lock.txt", "c+");

                if (flock($filehandle, LOCK_EX | LOCK_NB)) {

                    $pg->where("id", $row->id);
                    $pg->update("t_ref_feed_scheduler", array("status" => 2));

                    $this->_write_log('#' . $proc_id . ' Fetching data for ' . $row->template_name . " #" . rand());

                    if ($row->is_to_mysql == 1) {
                        if ($row->is_preprocess == 1)
                            $mysql->query($row->preprocess_query);
                        $res2 = $pg->query($row->query_source);

                        foreach ($res2->result() as $row2) {
                            $vals = array();
                            $cols = array();
                            foreach ($row2 as $key => $value) {
                                $vals[] = "'" . str_replace("'", "''", str_replace('\\', '', $value)) . "'";
                                $cols[] = str_replace("'", "''", $key);
                            }

                            $q = $mysql->query("insert into " . $row->table_destination . " (" . implode(',', $cols) . ") 
		 									values (" . implode(',', $vals) . ")");
                            if (!$q) {
                                $this->_write_log($this->db->_error_message());
                            }
                        }
                    } else {
                        if ($row->is_preprocess == 1)
                            $pg->query($row->preprocess_query);

                        $pg->query($row->query_source);
                    }


                    $pg->where("id", $row->id);
                    $pg->update("t_ref_feed_scheduler", array("last_execute" => date("Y-m-d H:i:s"), "status" => 1));
                    flock($filehandle, LOCK_UN);
                }else {
                    // $this->_write_log('#'.$proc_id.' Duplicate data for '.$row->template_name." #".rand());					
                }
                fclose($filehandle);
            }
        }
    }

    function fetch_report2() {
        $pg = $this->load->database('default', TRUE);
        $mysql = $this->load->database('reportdb', TRUE);
        echo '[' . date('Y-m-d G:i:s') . '] : Starting cron job' . "\r\n";
        $this->db->order_by('id', 'asc');
        $qreport = $this->db->get_where('t_ref_feed_scheduler', array("status" => 3));
        $qresult = $qreport->result();
        $proc_id = rand();

        foreach ($qresult as $row) {
            $from_time = strtotime($row->last_execute);
            $to_time = strtotime(date("Y-m-d H:i:s"));

            $diff = round(abs($to_time - $from_time) / 60, 0);
            $minute = intval($row->per_minutes_exec);

            if ($diff >= $minute) {

                $pg->where("id", $row->id);
                $pg->update("t_ref_feed_scheduler", array("status" => 2));

                $this->_write_log('#' . $proc_id . ' Fetching data for ' . $row->template_name . " #" . rand());

                if ($row->is_to_mysql == 1) {
                    if ($row->is_preprocess == 1)
                        $mysql->query($row->preprocess_query);
                    $res2 = $pg->query($row->query_source);

                    foreach ($res2->result() as $row2) {
                        $vals = array();
                        $cols = array();
                        foreach ($row2 as $key => $value) {
                            $vals[] = "'" . str_replace("'", "''", str_replace('\\', '', $value)) . "'";
                            $cols[] = str_replace("'", "''", $key);
                        }

                        $q = $mysql->query("insert into " . $row->table_destination . " (" . implode(',', $cols) . ") 
		 									values (" . implode(',', $vals) . ")");
                        if (!$q) {
                            $this->_write_log($this->db->_error_message());
                        }
                    }
                } else {
                    if ($row->is_preprocess == 1)
                        $pg->query($row->preprocess_query);

                    $pg->query($row->query_source);
                }


                $pg->where("id", $row->id);
                $pg->update("t_ref_feed_scheduler", array("last_execute" => date("Y-m-d H:i:s"), "status" => 1));
            }
        }
    }

    // ------- E M A I L ---------

    function remind_tp_company($param = '') { //andedi 2015-03-12
//        if (1==1) {
        if ($this->input->is_cli_request() && $param == self::SCHEDULER_TOKEN) {
            $scheduler = $this->check_scheduler(41);
            $data_scheduler = $scheduler['data'];
            if ($scheduler['is_executed'] == FALSE) {
                if ($scheduler['data'] != NULL) {
                    $data['last_executed'] = $this->_date_now;
                    $update_scheduler = $this->mscheduler->update_scheduler($data_scheduler->id, $data); //TES

                    if ($scheduler->is_preprocess != '') {
                        $this->db->query($scheduler->preprocess);
                    }

                    //Recepient Company
                    $companies = $this->mscheduler->get_tp_company();
                    echo " GENERATING EMAIL... \n ------------------- \n";
                    $i = 1;
                    foreach ($companies as $company) {
                        $recepients = array();
                        $this->db->trans_start();
                        $sites = $this->mscheduler->get_tp_company_sites($company->company_id);
                        $subject = 'SiMS: Lease Expired Date Reminder';
                        $content = $this->generate_content($data_scheduler->content, $sites);
                        
                        $emails = explode(',', $company->email);
                        foreach ($emails as $recepient) {
                            $recepients[] = array(
                                'type' => 'to',
                                'email' => trim($recepient),
                                'id' => NULL
                            );
                        }
                        
                        $this->mscheduler->set_email_notif($subject, $content, $recepients);
                        $this->db->trans_complete();

                        if ($this->db->trans_status() == TRUE) {
                            echo "{$i}. {$company->company_name} success \n";
                        } else {
                            echo "{$i}. {$company->company_name} failed! \n";
                        }

                        $i++;
                    }
                } else {
                    die("Oops, the scheduler is not exist or not active. \n");
                }
            } else {
                die("{$scheduler['data']} \n");
            }
        } else {
            die("Access denied! \n");
        }
    }

    private function check_scheduler($scheduler_id = 0) {
        $result = array();
        $scheduler = $this->mscheduler->get_scheduler("notif.id = {$scheduler_id}");
        if (!empty($scheduler)) {
            $last_exec_time = strtotime($scheduler->last_executed);
            $last_exec_date = date('Y-m-d', $last_exec_time);
            $to_time = strtotime(date("Y-m-d H:i:s"));
            $diff = round(abs($last_exec_time - $to_time) / 60, 0);

            if ($last_exec_date < date("Y-m-d") && $diff > 100) {
                $result = array(
                    'is_executed' => 0,
                    'data' => $scheduler
                );
            } else {
                $result = array(
                    'is_executed' => 1,
                    'data' => "The Email Notif ID {$scheduler_id} has been executed."
                );
            }
        } else {
            $result = array(
                'is_executed' => 0,
                'data' => NULL
            );
        }
        return $result;
    }

    private function generate_content($str = '', $data = NULL) {
        $content = $str;
        $table = strstr($content, '{table}');
        $footer = substr($content, strpos($content, '{/table}') + 8);
        $content = substr($content, 0, strpos($content, '{table}'));
        $header = strstr($table, '{header}');
        $header = substr($header, 8, strpos($header, '{/header}') - 8);

        $row = strstr($table, '{content}');
        $row = substr($row, 9, strpos($row, '{/content}') - 9);

        $header_arr = explode(';', $header);
        $header_str = '';
        $row_str = '';
        $row_arr = explode(';', $row);
        $header_str .= "<thead style=\"background-color:#c00000; color:white\"><th>No</th>";
        foreach ($header_arr as $k => $v) {
            $header_str .= "<th>$v</th>";
        }
        $header_str .= "</thead>";
        $i = 1;
        foreach ($data as $key => $value) {
            $style='';
             if ($value->ageingdays > 0 ) {   
                $style = ' style="color:red" ';
            }
            
            $row_tmp = "<tr{$style}><td align=\"center\">{$i}</td>";
            foreach ($row_arr as $k => $v) {
                $row_tmp .= "<td>" . $v . "</td>";
            }
            $row_tmp .= '</tr>';

            foreach ($value as $key => $val) {
                (!is_numeric($val)) ? $row_tmp = str_replace($key,$val, $row_tmp):$row_tmp = str_replace('<td>'.$key, '<td align="right">'.sprintf("%+d", $val), $row_tmp);
            }

            $row_str .= $row_tmp;
            $i++;
        }

        $content = "{$content}
                <table border=1 cellspacing=0 cellpadding=3 style=\"border-collapse:collapse\">
                {$header_str}
                {$row_str} 
                </table>
                {$footer}";
        $content = str_replace("'", "''", $content);

        return $content;
    }

    // ---------------- S M S ----------------------- 

    function remind_tp_company_sms($param = '') {
//        if (1==1) {
        if ($this->input->is_cli_request() && $param == self::SCHEDULER_TOKEN) {
            $scheduler = $this->check_sms_scheduler(15);
            $data_scheduler = $scheduler['data'];
            if ($scheduler['is_executed'] == FALSE) {
                if ($scheduler['data'] != NULL) {
                    $data['last_executed'] = $this->_date_now;
                    $update_scheduler = $this->mscheduler->update_scheduler($data_scheduler->id, $data);

                    if ($scheduler->is_preprocess != '') {
                        $this->db->query($scheduler->preprocess);
                    }

                    //Recepient Company - Upcoming Expired
                    $companies = $this->mscheduler->get_tp_company(self::EXPIRED_UPCOMING);
                    echo " Generating SMS for Upcoming Expired... \n ------------------------ \n";
                    $i = 1;
                    foreach ($companies as $company) {
                        $recepients = array();
                        $this->db->trans_start();
                        $sites = $this->mscheduler->get_tp_company_sites_sms($company->company_id, self::EXPIRED_UPCOMING);
                        $subject = 'SiMS: Lease Expired Date Reminder';
                        $content = $this->generate_sms_content($data_scheduler->content, $sites);
                        
                        $phones = explode(',', $company->contact_phone);
                        foreach ($phones as $recepient) {
                            $recepients[] = array(
                                'phone' => trim($recepient),
                                'id' => NULL
                            );
                        }
                        
                        $this->mscheduler->set_sms_notif($content, $recepients);
                        $this->db->trans_complete();

                        if ($this->db->trans_status() == TRUE) {
                            echo "{$i}. {$company->company_name} success. \n ";
                        } else {
                            echo "{$i}. {$company->company_name} failed! \n";
                        }

                        $i++;
                    }
                    
                    //Recepient Company - Overdue
                    $companies = $this->mscheduler->get_tp_company(self::EXPIRED_OVERDUE);
                    echo " Generating SMS for Overdue... \n ------------------------ \n";
                    $i = 1;
                    foreach ($companies as $company) {
                        $recepients = array();
                        $this->db->trans_start();
                        $sites = $this->mscheduler->get_tp_company_sites_sms($company->company_id, self::EXPIRED_OVERDUE);
//                        die($this->db->last_query());
                        $subject = 'SiMS: Lease Expired Date Reminder';
                        $content = $this->generate_sms_content($data_scheduler->content, $sites);
                        
                        $phones = explode(',', $company->contact_phone);
                        foreach ($phones as $recepient) {
                            $recepients[] = array(
                                'phone' => trim($recepient),
                                'id' => NULL
                            );
                        }
                        
                        $this->mscheduler->set_sms_notif($content, $recepients);
                        $this->db->trans_complete();

                        if ($this->db->trans_status() == TRUE) {
                            echo "{$i}. {$company->company_name} success. \n ";
                        } else {
                            echo "{$i}. {$company->company_name} failed! \n";
                        }

                        $i++;
                    }
                } else {
                    die("Oops, the scheduler is not exist or not active. \n");
                }
            } else {
                die("{$scheduler['data']} \n");
            }
        } else {
            die("Access denied! \n");
        }
    }

    private function check_sms_scheduler($notif_id = 0) {
        $result = array();
        $scheduler = $this->mscheduler->get_sms_scheduler("notif.id = {$notif_id}");
        
        if (!empty($scheduler)) {
            $last_exec_time = strtotime($scheduler->last_executed);
            $last_exec_date = date('Y-m-d', $last_exec_time);
            $to_time = strtotime(date("Y-m-d H:i:s"));
            $diff = round(abs($last_exec_time - $to_time) / 60, 0);

            if ($last_exec_date < date("Y-m-d") && $diff > 100) {
                $result = array(
                    'is_executed' => 0,
                    'data' => $scheduler
                );
            } else {
                $result = array(
                    'is_executed' => 1,
                    'data' => "The SMS Notif ID {$notif_id} has been executed."
                );
            }
        } else {
            $result = array(
                'is_executed' => 0,
                'data' => NULL
            );
        }
        return $result;
    }

    private function generate_sms_content($str = '', $data = NULL) {
        $content = $str;

        $col_arr = array('NS', 'SS', 'WJ', 'JBO', 'CJ', 'EJ', 'SULTAN');
        $header = strstr($content, '{data}', true) . '[br]';
        $row_data = array();
        $sign = '';
        foreach ($data as $key => $value) {
            $val_arr = array();
            $expired = '';
            foreach ($value as $key2 => $value2) {
                if (in_array($key2, $col_arr)) {
                    if ($value2 != 0) $val_arr[] = $key2 . ' ' . $value2;
                } else {
                    ($value2 < 0 ) ? $sign = '<': $sign = '>';
                    if (abs($value2) == 365)
                        $expired = '1yr';
                    elseif (abs($value2) == 180)
                        $expired = '6mo';
                    elseif (abs($value2) == 90)
                        $expired = '3mo';
                    elseif (abs($value2) == 30)
                        $expired = '1mo';
                    elseif (abs($value2) == 21)
                        $expired = '3w';
                    elseif (abs($value2) == 14)
                        $expired = '2w';
                    elseif (abs($value2) == 7)
                        $expired = '1w';
                    else
                        $expired = 'Not Available';
                }
            }
            $row_data[] = "{$sign} {$expired} : " . implode(', ', $val_arr);
        }
        $footer = '[br]' . substr($content, strpos($content, '{data}') + 6);
        $content = $header . implode('[br]', $row_data) . $footer;

        return $content;
    }

}