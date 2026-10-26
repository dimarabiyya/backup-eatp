<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mschedule extends CI_Model {

  public function getPicRegionCluster($user_id)
  {
    $this->db->select('uu.user_group_id, tt.region_id, tt.cluster_ops_id');
    $this->db->join('t_mtr_user_territory tt', 'tt.user_id = uu.user_id', 'left');
    $this->db->where('uu.user_id', $user_id);
    $q = $this->db->get('t_mtr_user uu');
    // echo $this->db->last_query();
    return $q->row_array();
  }

  public function savePm($data)
  {
    $checklist_id = array();
    foreach ($data as $key => $val) {
      if(is_array($val))
        { 
          $checklist_id = $val; 
          unset($data[$key ]);
        }
    }
    // die(print_r($data));
    $tmp  = $this->getPicRegionCluster($data['user_id']);
    $data = array_merge($data, $tmp);
    $data['status']= 0;

    $this->db->trans_begin();
    
		$msg = $this->new_visit_schedule($data, $checklist_id);
      // $exist = $this->hasSchedule($data['site_id'], $data['visit_week'], $data,'t_trx_pm_schedule');
      // if($exist['status']) {
        // $msg = $this->update_visit_schedule($exist['visit_id'], $data, $checklist_id);
      // }
      // else {
      // }
    
    if ($this->db->trans_status() === FALSE) { 
      $this->db->trans_rollback(); 
    }
    else { 
      $this->db->trans_commit(); 
    }
    return $msg;

  }

  public function updatePm($data)
  {
    $checklist_id = array();
    foreach ($data as $key => $val) {
      if(is_array($val))
        { 
          $checklist_id = $val; 
          unset($data[$key ]);
        }
    }
    // print_r($data);
    $tmp  = $this->getPicRegionCluster($data['user_id']);
    $data = array_merge($data, $tmp);
    $data['status'] = 0;

    $this->db->trans_begin();
	
	$msg = $this->update_visit_schedule($data['visit_id'], $data, $checklist_id); //dg ctt visit_id dikirim POST dari view edit.

    if ($this->db->trans_status() === FALSE) { 
      $this->db->trans_rollback(); 
    }
    else { 
      $this->db->trans_commit(); 
    }
    return $msg;
  }

  public function hasSchedule($site_id, $visit_week, $array, $table_name)
  {
    // echo "aye";
    // $this->db->where('visit_week', $visit_week);
    $this->db->where('site_id', $site_id);
    // if($table_name == 't_trx_site_inspection_schedule') 
    // { 
      $this->db->where($array);
    // }
    $this->db->where_in('status', array(0) );
    $exist = $this->db->count_all_results($table_name);


    if($exist > 0) {
      $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
      // $this->db->where('vv.visit_week', $visit_week);
      $this->db->where('vv.site_id', $site_id);
      $this->db->where_in('vv.status', array(0) );
      $q = $this->db->get("$table_name vv")->row_array();
      return array('status' => true, 'detail' => $q, 'message' => "Site already has schedule for selected date. "."\n\r"."Please select another date?"); 
    }
    else { return array( 'status' => false); }
  }

  public function new_visit_schedule($data, $checklist_id)
  {
    $data['created_by']    = $this->session->userdata('user_id');
    $created_on= date('Y-m-d H:i:s');
    $data['security_code_checkin']  = $this->randomString();  // Security Code generate by request of PIC
    $data['security_code_checkout'] = $this->randomString();  // Security Code generate by request of PIC
    $this->db->insert('t_trx_pm_schedule', $data);
    $trx_id = $this->db->insert_id();
    $i = 0;
    $detail = array();
    // print_r($checklist_id);
    if(count($checklist_id) > 0) {
      foreach ($checklist_id as $key) {
        $detail[$i]['visit_id'] = $trx_id;
        $detail[$i++]['checklist_id'] = $key;
        // print($detail);
      }
      $data['checklist'] = $detail;
      $this->db->insert_batch('t_trx_pm_schedule_checklist', $detail);
      $log = $this->dblog->AuditTrail('pm-create', $data);
    }

    if($this->db->affected_rows() > 0) 
    { 
		/**
		*added by:iwa, 3 Jan 2014 11.00 AM , updated by: iwa, 11 Apr 2014 11.30 AM
		*send email&sms notification new schedule from ssh to pic
		*/
			$qr = "SELECT ms.site_code, ms.vendor_id, ms.site_name, ms.latitude, ms.longitude, ms.site_address, ms.kecamatan, ms.kabupaten, ms.propinsi_id, mr.region_name, mc.cluster_name, ms.region_id
						FROM t_mtr_site ms LEFT JOIN t_mtr_region mr on mr.region_id=ms.region_id
						LEFT JOIN t_mtr_cluster_ops mc on mc.cluster_ops_id=ms.cluster_ops_id
						WHERE ms.site_id=".$data['site_id'];
			$qr_site        = $this->db->query($qr);
			$arr_site       = $qr_site->result();
			$created_on = date('Y-m-d G:i:d');
			$region_param[] = $arr_site[0]->region_id;
			//[site_id], [vendor_id], [site_name],[latitude], [longitude],[site_address],[kecamatan],[kabupaten],[propinsi],[region_name]
			//[cluster_ops_name],[visit_date],[visit_type],[priority],[remark], [user_name],[created_by]
			
			$mail_param     = array('[site_id]'=>$arr_site[0]->site_code,
												'[vendor_id]'=>$arr_site[0]->vendor_id,
												'[site_name]'=>$arr_site[0]->site_name,
												'[latitude]'=>$arr_site[0]->latitude,
												'[longitude]'=>$arr_site[0]->longitude,
												'[site_address]'=>$arr_site[0]->site_address,
												'[kecamatan]'=>$arr_site[0]->kecamatan,
												'[kabupaten]'=>$arr_site[0]->kabupaten,
												// '[propinsi]'=>null,
												'[region_name]'=>$arr_site[0]->region_name,
												'[cluster_name]'=>$arr_site[0]->cluster_name,
												'[visit_date]'=>$data['visit_date'],
												'[visit_type]'=>$data['visit_type'],
												'[priority]'=>$data['priority'],
												'[remark]'=>$data['remark'],
                        '[user_id]'=>$data['user_id'],
												'[user_name]'=>$data['user_id'],
												'[created_by]'=>$data['created_by'],
												'[created_on]'=>$created_on);
												
			$sms_param     = array('[site_id]'=>$arr_site[0]->site_code,'[vendor_id]'=>$arr_site[0]->vendor_id,'[user_id]'=>$data['user_id'],'[visit_date]'=>$data['visit_date'],'[created_by]'=>$data['created_by'],'[created_on]'=>$created_on);
			$pic_mail   		= array(
						array('name'=>$data['user_id'],'email'=>'','recipient_type'=> 'to')
					);

			$pic_sms   		= array(
						array('name'=>$data['user_id'],'email'=>'','recipient_type'=> 'to')
					);

// [user_name],[created_by],[site_id], [vendor_id], [site_name],[latitude], [longitude],[site_address],[kecamatan],[kabupaten],[propinsi],[region_name]
// ,[cluster_ops_name],[visit_date],[visit_type],[priority],[remark]
			//$schedule_param = $email_type_id,$email_date,$email_time;
			$schedule_param = array('date'=>date('Y-m-d'),'time'=>date('G:i:s'),'foreign_id'=>$data['site_id']);
			$this->mglobal->pushMail('PM001',array_unique($region_param),$mail_param,$schedule_param,$pic_mail);
			$this->mglobal->pushSms('PM001',array_unique($region_param),$sms_param,$schedule_param,$pic_sms);

		/*end of notification*/
		return array('status' => true, 'message' => 'Schedule has been created'); 
	}
    else 
      { return array('status' => false, 'message' => 'Failed to create schedule'); }
  }


  public function insert_checklist($table_name, $checklist_id, $visit_id)
  {
    $i=0;
    foreach ($checklist_id as $key) {
      $data[$i]['visit_id'] = $visit_id;
      $data[$i++]['checklist_id'] = $key['checklist_id'];
    }
    $this->db->insert_batch($table_name, $data);
    if($this->db->affected_rows() > 0) {
      return true;
    }
    else {
      return false;
    }
  }

  public function pm_edit_schedule($data)
  {
    $this->db->trans_begin();

      $this->db->where('visit_id', $data['visit_id']);
      $this->db->update('t_trx_pm_schedule', array('remark_reschedule' => $data['remark_reschedule'], 'reschedule_reason' => $data['reschedule_reason'], 'updated_on'=>date('Y-m-d H:i:s.u'), 'updated_by' => $this->session->userdata('user_id')) );

      $this->db->where('visit_id', $data['visit_id']);
      $q = $this->db->get('t_trx_pm_schedule_checklist');
      $items = $q->result_array();

      $this->db->where('visit_id', $data['visit_id']);
      $q = $this->db->get('t_trx_pm_schedule');
      $visit = $q->row_array();

      $visit['status'] = 10;
      $this->db->insert('t_trx_pm_schedule_history', $visit);
      $r = $this->db->insert_batch('t_trx_pm_schedule_checklist_history', $items);

      $decline = array(
          'visit_id'     => $data['visit_id'], 
          'confirm'      => 10,
          'reason_group' => $data['reschedule_reason'],
          'reason_desc'  => $data['remark_reschedule'],
          'lookup_key'  => 'reason_pm_reschedule',
          'created_on'   => 'now()',
          'created_by'   => $this->session->userdata('user_id')
        );
      $this->db->insert('t_trx_pm_schedule_confirm', $decline);


      foreach ($data as $key => $val) {
        $visit[$key] = $val;
      }
      $visit['security_code_checkin'] = $this->randomString();
      $visit['security_code_checkout'] = $this->randomString();
      unset($visit['visit_id']);
      unset($visit['checklist_item']);
      if(!$visit['parent_id']) {
        $visit['parent_id']  = $data['visit_id'];
      }
      
      $this->db->where('visit_id', $data['visit_id']);
      $this->db->delete('t_trx_pm_schedule_checklist');

      $this->db->where('visit_id', $data['visit_id']);
      $this->db->delete('t_trx_pm_schedule');

      $visit['status'] = 0;
      $this->db->insert('t_trx_pm_schedule', $visit);
      $trx_id = $this->db->insert_id();
      $i=0;
      if(count($data['checklist_item']) > 0) {
        foreach ($data['checklist_item'] as $key) {
          $detail[$i]['visit_id'] = $trx_id;
          $detail[$i++]['checklist_id'] = $key;
        }
        $this->db->insert_batch('t_trx_pm_schedule_checklist', $detail);
      }

      $this->dblog->AuditTrail('update-pm-schedule', $visit);

    if ($this->db->trans_status() === FALSE) { 
      $this->db->trans_rollback(); 
      return array('status' => false, 'message' => 'Schedule not updated');
    }
    else { 
      $this->db->trans_commit(); 
      return array('status' => true, 'message' => 'Schedule has been updated');
    }
  }

  /**
   * Updating schedule
   * - Update the remark_reschedule
   * - Copy a record to t_trx_pm_reschdule and fill the remark
   * - Delete the original record
   * - Insert a new record with parent_id is equal to the $visit_id (if parent_id is null)
   * @param  [type] $visit_id     [description]
   * @param  [type] $data         [description]
   * @param  [type] $checklist_id [description]
   * @return [type]               [description]
   */
  public function pm_reschedule($visit_id, $visit_date, $remark, $reason_reschedule)
  {
    $this->db->trans_begin();

      $this->db->where('visit_id', $visit_id);
      $this->db->update('t_trx_pm_schedule', array('remark_reschedule' => $remark, 'reschedule_reason' => $reason_reschedule, 'updated_on'=>date('Y-m-d H:i:s.u'), 'updated_by' => $this->session->userdata('user_id')) );

      $this->db->where('visit_id', $visit_id);
      $q = $this->db->get('t_trx_pm_schedule_checklist');
      $items = $q->result_array();

      $this->db->where('visit_id', $visit_id);
      $q = $this->db->get('t_trx_pm_schedule');
      $visit = $q->row_array();
      $visit['status'] = 11;

      $log['visit_id']          = $visit_id;
      $log['remark_reschedule'] = $remark;
      $log['visit_date_before'] = $visit['visit_date'];
      $log['visit_date_after']  = $visit_date;
      $log['checklist']         = $items;

      $decline = array(
          'visit_id'     => $visit_id, 
          'confirm'      => 11,
          'reason_group' => $reason_reschedule,
          'reason_desc'  => $remark,
          'lookup_key'   => 'reason_pm_reschedule',
          'created_on'   => 'now()',
          'created_by'   => $this->session->userdata('user_id')
        );
      $this->db->insert('t_trx_pm_schedule_confirm', $decline);

      $this->db->insert('t_trx_pm_schedule_history', $visit);
      $r = $this->db->insert_batch('t_trx_pm_schedule_checklist_history', $items);

      unset($visit['visit_id']);
      $visit['visit_date'] = $visit_date;
      $visit['security_code_checkin'] = $this->randomString();
      $visit['security_code_checkout'] = $this->randomString();
      if(!$visit['parent_id']) {
        $visit['parent_id']  = $visit_id;
      }
      
      $this->db->where('visit_id', $visit_id);
      $this->db->delete('t_trx_pm_schedule_checklist');

      $this->db->where('visit_id', $visit_id);
      $this->db->delete('t_trx_pm_schedule');

      $visit['status'] = 0;
      $this->db->insert('t_trx_pm_schedule', $visit);
      $trx_id = $this->db->insert_id();
      $m = $this->insert_checklist('t_trx_pm_schedule_checklist', $items, $trx_id);
      if(!$m) {
        $this->db->trans_rollback(); 
        return array('status' => false, 'message' => 'Schedule not updated');
      }
      $log['new_visit_id'] = $trx_id;
      $log['checklist']    = $items;
      $this->dblog->AuditTrail('update-pm-schedule', $log);

    if ($this->db->trans_status() === FALSE) { 
      $this->db->trans_rollback(); 
      return array('status' => false, 'message' => 'Schedule not updated');
    }
    else { 
      $this->db->trans_commit(); 
      return array('status' => true, 'message' => 'Schedule has been updated');
    }

  }

  public function randomString()
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randstring = '';
    for ($i = 0; $i < 5; $i++) 
    { $randstring .= $characters[rand(0, (strlen($characters)-1) )];}
    return $randstring;
  }

  public function generate_sc($table_name, $visit_id,$remark)
  {
    $ci = $this->mglobal->showdata("security_code_checkin", $table_name, array("visit_id"=> $visit_id), "dblokal");
    $co = $this->mglobal->showdata("security_code_checkout", $table_name, array("visit_id"=> $visit_id), "dblokal");
    $this->db->trans_begin();
      $data['security_code_remark'] = $remark;          
      $data['security_code_generate_date'] = date('Y-m-d H:i:s');
      $data['security_code_generate_by']=$this->session->userdata('user_id');
      $data['is_code_show']=1;

      $this->mglobal->setData($table_name, $data, array('visit_id' => $visit_id));
      // $this->db->where('visit_id', $visit_id);
      // $this->db->update($table_name, $data);
      
      $log = $data;
      $log['table_name'] = $table_name;
      $log['visit_id'] = $visit_id;
      $log['security_code_checkin'] = $ci;
      $log['security_code_checkout'] = $co;

    $this->dblog->AuditTrail('show_security_code', $log);
    if ($this->db->trans_status() === FALSE) { 
      $this->db->trans_rollback(); 
      return array('status' => true, 'message' => 'Failed to generate the code');
    }
    else { 
      $this->db->trans_commit(); 
      return array('status' => true, 'message' => 'Code generate succesfully', 'checkin' => $ci,'checkout'=>$co );
    }
  }

  public function saveInspection($data)
  {
    // print_r($data);
    $checklist_id = array();
    foreach ($data as $key => $val) {
      if(is_array($val))
        { 
          $checklist_id = $val; 
          unset($data[$key]);
        }
    }
    $tmp            = $this->getPicRegionCluster($data['user_id']);
    $data           = array_merge($data, $tmp);
    $data['status'] = 0;

    $this->db->trans_begin();
      
      $msg = $this->new_site_inspection($data, $checklist_id);
      // Put your magic here
      // $exist = $this->hasSchedule($data['site_id'], $data['visit_week'], $data, 't_trx_site_inspection_schedule');
      // if($exist['status']) {
      //   // $msg = $this->update_site_inspection($exist['visit_id'], $data, $checklist_id);
      //   $msg = array('status' => false, 'message' => 'Site already has schedule for selected date \r \n Do you want to create a new schedule?', 'has_schedule' => true );
      // }
      // else {
      //   $msg = $this->new_site_inspection($data, $checklist_id);
      // }
    
    if ($this->db->trans_status() === FALSE)
      { $this->db->trans_rollback(); }
    else
      { $this->db->trans_commit(); }
    return $msg;
  }


  public function si_edit_schedule($data)
  {
    $this->db->trans_begin();

      $this->db->where('visit_id', $data['visit_id']);
      $this->db->update('t_trx_site_inspection_schedule', array('remark_reschedule' => $data['remark_reschedule'], 'reschedule_reason' => $data['reschedule_reason'], 'updated_on'=>date('Y-m-d H:i:s.u'), 'updated_by' => $this->session->userdata('user_id')) );

      $this->db->where('visit_id', $data['visit_id']);
      $q = $this->db->get('t_trx_site_inspection_schedule_checklist');
      $items = $q->result_array();

      $this->db->where('visit_id', $data['visit_id']);
      $q = $this->db->get('t_trx_site_inspection_schedule');
      $visit = $q->row_array();

      $visit['status'] = 10;
      $this->db->insert('t_trx_site_inspection_schedule_history', $visit);
      $r = $this->db->insert_batch('t_trx_site_inspection_schedule_checklist_history', $items);

      $decline = array(
          'visit_id'     => $visit['visit_id'], 
          'confirm'      => 10,
          'reason_group' => $data['reschedule_reason'],
          'reason_desc'  => $data['remark_reschedule'],
          'lookup_key'   => 'reason_si_reschedule',
          'created_on'   => 'now()',
          'created_by'   => $this->session->userdata('user_id')
        );
      $this->db->insert('t_trx_site_inspection_schedule_confirm', $decline);
      
      foreach ($data as $key => $val) {
        $visit[$key] = $val;
      }
      $visit['security_code_checkin'] = $this->randomString();
      $visit['security_code_checkout'] = $this->randomString();
      unset($visit['visit_id']);
      unset($visit['checklist_item']);
      if(!$visit['parent_id']) {
        $visit['parent_id']  = $data['visit_id'];
      }

      $this->db->where('visit_id', $data['visit_id']);
      $this->db->delete('t_trx_site_inspection_schedule_checklist');

      $this->db->where('visit_id', $data['visit_id']);
      $this->db->delete('t_trx_site_inspection_schedule');

      $visit['status'] = 0;
      $this->db->insert('t_trx_site_inspection_schedule', $visit);
      $trx_id = $this->db->insert_id();
      $i=0;
      if(count($data['checklist_item']) > 0) {
        foreach ($data['checklist_item'] as $key) {
          $detail[$i]['visit_id'] = $trx_id;
          $detail[$i++]['checklist_id'] = $key;
        }
        $this->db->insert_batch('t_trx_site_inspection_schedule_checklist', $detail);
      }

    if ($this->db->trans_status() === FALSE) { 
      $this->db->trans_rollback(); 
      return array('status' => false, 'message' => 'Schedule not updated');
    }
    else { 
      $this->db->trans_commit(); 
      return array('status' => true, 'message' => 'Schedule has been updated');
    }
  }
 
  public function si_reschedule($visit_id, $visit_date, $remark, $reason_reschedule)
  {
    $this->db->trans_begin();

      $this->db->where('visit_id', $visit_id);
      $this->db->update('t_trx_site_inspection_schedule', array('remark_reschedule' => $remark, 'updated_on'=>'now()', 'updated_by' => $this->session->userdata('user_id')) );

      $this->db->where('visit_id', $visit_id);
      $q = $this->db->get('t_trx_site_inspection_schedule_checklist');
      $items = $q->result_array();

      $this->db->where('visit_id', $visit_id);
      $q = $this->db->get('t_trx_site_inspection_schedule');
      $visit = $q->row_array();
      $visit['status'] = 11;

      $decline = array(
          'visit_id'     => $visit_id, 
          'confirm'      => 11,
          'reason_group' => $reason_reschedule,
          'reason_desc'  => $remark,
          'lookup_key'   => 'reason_si_reschedule',
          'created_on'   => 'now()',
          'created_by'   => $this->session->userdata('user_id')
        );
      $this->db->insert('t_trx_site_inspection_schedule_confirm', $decline);

      $log['remark_reschedule'] = $remark;
      $log['visit_date_before'] = $visit['visit_date'];
      $log['visit_date_after']  = $visit_date;

      $this->db->insert('t_trx_site_inspection_schedule_history', $visit);
      $r = $this->db->insert_batch('t_trx_site_inspection_schedule_checklist_history', $items);

      unset($visit['visit_id']);
      $visit['visit_date'] = $visit_date;
      $visit['security_code_checkin'] = $this->randomString();
      $visit['security_code_checkout'] = $this->randomString();
      if(!$visit['parent_id']) {
        $visit['parent_id']  = $visit_id;
      }
      
      $this->db->where('visit_id', $visit_id);
      $this->db->delete('t_trx_site_inspection_schedule_checklist');

      $this->db->where('visit_id', $visit_id);
      $this->db->delete('t_trx_site_inspection_schedule');

      $visit['status'] = 0;
      $this->db->insert('t_trx_site_inspection_schedule', $visit);
      $trx_id = $this->db->insert_id();
      $m = $this->insert_checklist('t_trx_site_inspection_schedule_checklist', $items, $trx_id);
      if(!$m) {
        $this->db->trans_rollback(); 
        return array('status' => false, 'message' => 'Schedule not updated');
      }

      $this->dblog->AuditTrail('update-pm-schedule', $log);

    if ($this->db->trans_status() === FALSE) { 
      $this->db->trans_rollback(); 
      return array('status' => false, 'message' => 'Schedule not updated');
    }
    else { 
      $this->db->trans_commit(); 
      return array('status' => true, 'message' => 'Schedule has been updated');
    }

  }

  public function new_site_inspection($data, $checklist_id)
  {
    $data['created_by'] = $this->session->userdata('user_id');
    $data['created_on'] = date('Y-m-d H:i:s.u') ;
    $data['security_code_checkin']  = $this->randomString();  // security code generate by request of PIC
    $data['security_code_checkout'] = $this->randomString();  // security code generate by request of PIC
    
    $this->db->insert('t_trx_site_inspection_schedule', $data, false);
    $trx_id = $this->db->insert_id();
    $i = 0;
    $detail = array();
    if(count($checklist_id) > 0) {
      foreach ($checklist_id as $key) {
        $detail[$i]['visit_id'] = $trx_id;
        $detail[$i++]['checklist_id'] = $key;
      }
      $this->db->insert_batch('t_trx_site_inspection_schedule_checklist', $detail);
    }
      $log = $this->dblog->AuditTrail('site-inspection-create', $data);
    if($this->db->affected_rows() > 0) 
    { 
		/**
		*added by:iwa, 3 Jan 2014 11.00 AM , updated by: iwa, 11 Apr 2014 11.30 AM
		*send email&sms notification new schedule from RPL to NPI PIC
		*/
			$qr = "SELECT ms.site_code, ms.vendor_id, ms.site_name, ms.latitude, ms.longitude, ms.site_address, ms.kecamatan, ms.kabupaten, ms.propinsi_id, mr.region_name, mc.cluster_name, ms.region_id
						FROM t_mtr_site ms LEFT JOIN t_mtr_region mr on mr.region_id=ms.region_id
						LEFT JOIN t_mtr_cluster_ops mc on mc.cluster_ops_id=ms.cluster_ops_id
						WHERE ms.site_id=".$data['site_id'];
			$qr_site        = $this->db->query($qr);
			$arr_site       = $qr_site->result();
			$created_on = date('Y-m-d G:i:d');
			$region_param[] = $arr_site[0]->region_id;
			//param PM mail
			//[site_id], [vendor_id], [site_name],[latitude], [longitude],[site_address],[kecamatan],[kabupaten],[propinsi],[region_name]
			//[cluster_ops_name],[visit_date],[visit_type],[priority],[remark], [user_name],[created_by]
			
			//param SI mail
			//[user_name],[created_by_name],[site_id],[vendor_id],[site_name],[candidate_id],[candidate_name],[latitude], [longitude],[tp_name],
			//[site_address],[kecamatan],[kabupaten],[propinsi],[region],[visit_date],[visit_type],[priority],[remark]
			$mail_param     = array('[site_id]'=>$arr_site[0]->site_code,
												'[vendor_id]'=>$arr_site[0]->vendor_id,
												'[site_name]'=>$arr_site[0]->site_name,
												'[candidate_id]'=>1,
												'[candidate_name]'=>1,
												'[tp_name]'=>1,
												'[latitude]'=>$arr_site[0]->latitude,
												'[longitude]'=>$arr_site[0]->longitude,
												'[site_address]'=>$arr_site[0]->site_address,
												'[kecamatan]'=>$arr_site[0]->kecamatan,
												'[kabupaten]'=>$arr_site[0]->kabupaten,
												'[propinsi]'=>$arr_site[0]->propinsi,
												'[region_name]'=>$arr_site[0]->region_name,
												'[cluster_name]'=>$arr_site[0]->cluster_name,
												'[visit_date]'=>$data['visit_date'],
												'[visit_type]'=>$data['visit_type'],
												'[priority]'=>$data['priority'],
												'[remark]'=>$data['remark'],
												'[user_name]'=>$data['user_id'],
												'[created_by]'=>$data['created_by'],
												'[created_on]'=>$created_on);

			$pic_mail   		= array(
						array('name'=>$data['user_id'],'email'=>'','recipient_type'=> 'to')
					);

			$pic_sms   		= array(
						array('name'=>$data['user_id'],'email'=>'','recipient_type'=> 'to')
					);


			//$schedule_param = $email_type_id,$email_date,$email_time;
			$schedule_param = array('date'=>date('Y-m-d'),'time'=>date('G:i:s'),'foreign_id'=>$data['site_id']);
			// $this->mglobal->pushMail('INS001',array_unique($region_param),$mail_param,$schedule_param,$pic_mail);
			// $this->mglobal->pushSms('INS001',array_unique($region_param),$sms_param,$schedule_param,$pic_sms);

		/*end of notification*/
	  
		return array('status' => true, 'message' => 'Schedule has been created'); 
	}
    else 
      { return array('status' => flase, 'message' => 'Failed to create schedule'); }
  }

  public function getSiteSchedule($table_name, $data)
  {
    // print_r($data);
    $this->db->where($data);
    $this->db->where("status ='0'");
    $q = $this->db->get($table_name);
    $r['head'] = $q->row_array();
    $r['list'] = array();
    if($q->num_rows() > 0 ) {
      $this->db->where('visit_id', $r['head']['visit_id']);
      $q = $this->db->get($table_name.'_checklist');
      $list = $q->result_array();

      foreach ($list as $key) {
        $r['list'][] = $key['checklist_id'];
      }
      $r['status'] = true;
    }
    else {
      $r['status'] = false;
    }

    return $r;
  }

  public function crossSchedule($table_name, $data, $site_id) {
    $w = "ss.region_id = (select region_id from t_mtr_site where site_id = $site_id)";
    $this->db->select('uu.user_name, vv.user_id, ss.site_id, ss.site_code, ss.site_name, ss.site_address, vv.created_by, f_get_user(vv.created_by) spv_name');
    $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->where($data);
    $this->db->where($w);
    $this->db->where('vv.status', '0');

    
    $q = $this->db->get("$table_name vv");
    $r['head'] = $q->result_array();
    if($q->num_rows() > 0 ) {
      $r['status'] = true;
    }
    else {
      $r['status'] = false;
    }

    return $r;
  }

  public function getVisitImage($table_name, $param)
  {
    $this->db->where($param);
    $q = $this->db->get($table_name);
    return $q->result();
  }

  function getSiteProfile($visit_id){
		$this->db->select('lo.lookup_id, lo.lookup_key, lo.lookup_value, lo.lookup_desc');
		$this->db->select("ss.site_id, ss.site_code, ss.site_name, lo.lookup_desc AS site_profile");
		$this->db->join("t_mtr_lookup lo", "lo.lookup_value = CAST(ss.site_profile AS VARCHAR) AND lo.lookup_key = 'site_profile'", 'left');
		$q = $this->db->get('t_mtr_site ss');
		return $q->row();
	}
  
  //detail saat klik jumlah visit per bulan
  public function getSiteVisit($table_name, $param, $month, $year, $visit_id = null)
  {
    // $this->db->select('');
    if($visit_id) {
      $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
      $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
      $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
	  $this->db->where('vv.visit_id', $visit_id);
      $q = $this->db->get("$table_name vv");
      $data['site'] = $q->row_array();
    }
    else {
      $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
      $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');	
	  $this->db->where('ss.site_id', $param['site_id']);
      $q = $this->db->get('t_mtr_site ss');
      $data['site'] = $q->row_array();
    }
    // echo $this->db->last_query();

    
    if($table_name != 't_trx_pm_schedule'){
		$this->db->select('vv.visit_id, vv.site_id, vv.user_id, vv.visit_date, vv.visit_type, vv.priority, vv.remark, tr.tp_id, tr.address, tr.longitude, tr.latitude');
		$this->db->select('vv.security_code_checkin, vv.security_code_checkout, vv.status, vv.pic_non_default_remark, vv.created_on, uu.user_name');
		$this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
		$this->db->join('t_mtr_tower_provider tr', 'tr.tp_id = vv.tp_id');
	}else{
		$this->db->select('vv.visit_id, vv.site_id, vv.user_id, vv.visit_date, vv.visit_type, vv.priority, vv.remark');
		$this->db->select('vv.security_code_checkin, vv.security_code_checkout, vv.status, vv.pic_non_default_remark, vv.created_on, uu.user_name');
	    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
		//$this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
		//$this->db->join("t_mtr_lookup lo", "lo.lookup_value = CAST(ss.site_profile AS VARCHAR) AND lo.lookup_key = 'site_profile'", 'left');
	}
	
	if($visit_id)
      { $this->db->where('vv.visit_id', $visit_id); }
    else
      { $this->db->where($param); }
    $this->db->where("date_part('year',vv.visit_date) = $year");
    $this->db->where("date_part('month',vv.visit_date) = $month");
    $q = $this->db->get("$table_name vv");
    $data['visits'] = $q->result_array();

    foreach ($data['visits'] as $visit) {
      // $visit_id[] = $visit->visit_id;
      $this->db->select('cc.checklist_id, cc.checklist_name, cc.checklist_group group_id, cc.interface');
      $this->db->join('t_mtr_checklist cc', 'cc.checklist_id = vc.checklist_id');
      $this->db->where('vc.visit_id', $visit['visit_id']);
      $q = $this->db->get($table_name.'_checklist vc');
      $data['checklist'][] = $q->result_array();
    }

    $this->db->select('checklist_group_id group_id, group_name, parent_id');
    $this->db->where('status', 1);
    $this->db->order_by('checklist_group_id');
    $q = $this->db->get('t_mtr_checklist_group');
    $data["group"] = $q->result_array();

    return $data ;
  }

  public function getSchedule($table_name, $param, $month, $year, $visit_id = null, $search, $page, $rows)
  {
    // $this->db->select('');
    if($visit_id) {
      $this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
      $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
      $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');
	  $this->db->where('vv.visit_id', $visit_id);
      $q = $this->db->get("$table_name vv");
      $data['site'] = $q->row_array();
    }
    else {
      $this->db->join('t_mtr_cluster_ops cc', 'cc.cluster_ops_id = ss.cluster_ops_id');
      $this->db->join('t_mtr_region rr', 'rr.region_id = cc.region_id');	
      $this->db->where('ss.site_id', $param['site_id']);
      $q = $this->db->get('t_mtr_site ss');
      $data['site'] = $q->row_array();
    }
    // echo $this->db->last_query();
    
    if($table_name != 't_trx_pm_schedule'){
		$this->db->select('vv.visit_id, vv.site_id, vv.user_id, vv.visit_date, vv.visit_type, vv.priority, vv.remark, tr.tp_id, tr.address, tr.longitude, tr.latitude');
		$this->db->select('vv.security_code_checkin, vv.security_code_checkout, vv.status, vv.pic_non_default_remark, vv.created_on, uu.user_name');
		$this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
		$this->db->join('t_mtr_tower_provider tr', 'tr.tp_id = vv.tp_id');
	}else{
		$this->db->select('vv.visit_id, vv.site_id, vv.user_id, vv.visit_date, vv.visit_type, vv.priority, vv.remark');
		$this->db->select('vv.security_code_checkin, vv.security_code_checkout, vv.status, vv.pic_non_default_remark, vv.created_on, uu.user_name');
	    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
		//$this->db->join('t_mtr_site ss', 'ss.site_id = vv.site_id');
		//$this->db->join("t_mtr_lookup lo", "lo.lookup_value = CAST(ss.site_profile AS VARCHAR) AND lo.lookup_key = 'site_profile'", 'left');
	}
	
	if($visit_id)
      { $this->db->where('vv.visit_id', $visit_id); }
    else
      { $this->db->where($param); }

    $this->db->where("date_part('year',vv.visit_date) = $year");
    $this->db->where("date_part('month',vv.visit_date) = $month");
    $q = $this->db->get("$table_name vv");

   // $data['visits'] = $q->result_array();
	$offset = ($page-1) * $rows;
    
	$data['total'] = $q->num_rows();

	$x = $this->db->get("$table_name vv", $rows, $offset);
    
    $data['rows'] = $x->result();

	

  /*  foreach ($data['visits'] as $visit) {
      // $visit_id[] = $visit->visit_id;
      $this->db->select('cc.checklist_id, cc.checklist_name, cc.checklist_group group_id, cc.interface');
      $this->db->join('t_mtr_checklist cc', 'cc.checklist_id = vc.checklist_id');
      $this->db->where('vc.visit_id', $visit['visit_id']);
      $q = $this->db->get($table_name.'_checklist vc');
      $data['checklist'][] = $q->result_array();
    }

    $this->db->select('checklist_group_id group_id, group_name, parent_id');
    $this->db->where('status', 1);
    $this->db->order_by('checklist_group_id');
    $q = $this->db->get('t_mtr_checklist_group');
    $data["group"] = $q->result_array(); */

    return $data ;
  }
  
  public function getUserVisit($table_name, $param, $month, $year, $user_id = null)
  {
    if($user_id) {
      $this->db->where('vv.user_id', $user_id);
      $q = $this->db->get("t_mtr_user vv");
      $data['user'] = $q->row_array();
    }
    // echo $this->db->last_query();

    $this->db->select('vv.visit_id, vv.site_id,  vv.visit_date, vv.visit_type, vv.priority, vv.remark,st.site_name,st.vendor_id');
    $this->db->select('vv.security_code_checkin, vv.security_code_checkout, vv.status, vv.created_on, uu.user_name');
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_site st', 'st.site_id = vv.site_id');
    if($user_id)
      { $this->db->where('uu.user_id', $user_id); }
    else
      { $this->db->where($param); }
    $this->db->where("date_part('year',vv.visit_date) = $year");
    $this->db->where("date_part('month',vv.visit_date) = $month");
    $q = $this->db->get("$table_name vv");
    $data['visit'] = $q->result_array();

    foreach ($data['visit'] as $visit) {
      // $visit_id[] = $visit->visit_id;
      $this->db->select('cc.checklist_id, cc.checklist_name, cc.checklist_group group_id, cc.interface');
      $this->db->join('t_mtr_checklist cc', 'cc.checklist_id = vc.checklist_id');
      $this->db->where('vc.visit_id', $visit['visit_id']);
      $q = $this->db->get($table_name.'_checklist vc');
      $data['checklist'][] = $q->result_array();
    }

    $this->db->select('checklist_group_id group_id, group_name, parent_id');
    $this->db->where('status', 1);
    $this->db->order_by('checklist_group_id');
    $q = $this->db->get('t_mtr_checklist_group');
    $data["group"] = $q->result_array();

    return $data ;
  }

  public function detailPerVisit($table_name, $visit_id)
  {
    // echo $this->db->last_query();

    $this->db->select('vv.visit_id, vv.site_id,  vv.visit_date, vv.visit_type, vv.priority, vv.remark, st.site_code, st.site_name, st.vendor_id');
    $this->db->select('vv.security_code_checkin, vv.security_code_checkout, vv.status, vv.created_on, uu.user_name');
    $this->db->select('cf.reason_group, cf.reason_desc');
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->join('t_mtr_site st', 'st.site_id = vv.site_id');
    $this->db->join($table_name."_confirm cf", 'cf.visit_id = vv.visit_id', 'left');
    $this->db->where('vv.visit_id', $visit_id);
    $q = $this->db->get("$table_name vv");
    $data['visit'] = $q->result_array();

    $this->db->select('vv.user_id, uu.user_name');
    $this->db->join('t_mtr_user uu', 'uu.user_id = vv.user_id');
    $this->db->where('vv.visit_id', $visit_id);
    $q = $this->db->get("$table_name vv");
    $data['user'] = $q->row_array();

    foreach ($data['visit'] as $visit) {
      // $visit_id[] = $visit->visit_id;
      $this->db->select('cc.checklist_id, cc.checklist_name, cc.checklist_group group_id, cc.interface');
      $this->db->join('t_mtr_checklist cc', 'cc.checklist_id = vc.checklist_id');
      $this->db->where('vc.visit_id', $visit['visit_id']);
      $this->db->order_by('cc.order_number');
      $q = $this->db->get($table_name.'_checklist vc');
      $data['checklist'][] = $q->result_array();
    }

    $this->db->select('checklist_group_id group_id, group_name, parent_id');
    $this->db->where('status', 1);
    $this->db->order_by('order_number, checklist_group_id');
    $q = $this->db->get('t_mtr_checklist_group');
    $data["group"] = $q->result_array();

    return $data ;
  }

  public function delete_schedule($visit_id, $table_name, $canceling_remark, $canceling_reason, $lookup_key)
  {
    $this->db->trans_begin();

      $visit_data = array(
          'status'       => -5, 
          'updated_on'   => 'now()',
          'updated_by'   => $this->session->userdata('user_id'),
          // 'cancellation_reason' => $canceling_reason,
          // 'cancellation_remark'  => $canceling_remark
        );
      $this->db->where('visit_id', $visit_id);
      $this->db->update($table_name, $visit_data );

      // simpan juga ke tabel *_confirm
      $decline = array(
          'visit_id'     => $visit_id, 
          'confirm'      => -5,
          'reason_group' => $canceling_reason,
          'reason_desc'  => $canceling_remark,
          'lookup_key'   => $lookup_key,
          'created_on'   => 'now()',
          'created_by'   => $this->session->userdata('user_id')
        );
      $this->db->insert($table_name.'_confirm', $decline);

    if ($this->db->trans_status() === FALSE) { 
      $this->db->trans_rollback(); 
      return array('status'=> false, 'message' => 'Deleting schedule failed');
    }
    else { 
      $this->db->trans_commit(); 
      return array('status'=> true, 'message' => 'Deleting schedule successful');
    }
  }

  public function reschedule($visit_id, $table_name, $target_table_name)
  {

    $this->db->where("vc.visit_id", $visit_id);
    $q = $this->db->get("$table_name as vc ");
    $GbFeHY = $q->row_array();

    $this->db->where("tc.visit_id", $visit_id);
    $q = $this->db->get($table_name.'_checklist as tc');
    $FlnJO4 = $q->result_array();

    $this->db->trans_begin();

      $this->db->where("tc.visit_id", $visit_id);
      $q = $this->db->delete($table_name.'_checklist as tc');

      $this->db->where("vc.visit_id", $visit_id);
      $q = $this->db->delete("$table_name vc");

      $GbFeHY['updated_on'] = date('Y-m-d H:i:s.u');
      $GbFeHY['updated_by'] = $this->session->userdata('user_id');
      
      $this->db->insert($target_table_name, $GbFeHY);
      $this->db->insert_batch($target_table_name.'_checklist', $FlnJO4);

      if($GbFeHY['parent_id']) {
        $parent_id = $GbFeHY['parent_id'];
      }
      else {
        $parent_id = $visit_id;
      }

    if ($this->db->trans_status() === FALSE) { 
      $this->db->trans_rollback(); 
      return array('status'=> false, 'message' => 'Deleting schedule failed');
    }
    else { 
      $this->db->trans_commit(); 
      return array('status'=> true, 'message' => 'Deleting schedule successful', 'parent_id' => $parent_id);
    }
  }

  public function get_reasons_group($reason_key)
  {
    $this->db->select('lookup_key, lookup_value, lookup_desc');
    $this->db->where('lookup_key', $reason_key);
    $q = $this->db->get('t_mtr_lookup');
    return $q->result();
  }

}

/* End of file mschedule.php */
/* Location: ./application/models/pm/mschedule.php */