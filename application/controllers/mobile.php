<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobile extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('mobile_model');
		
		$this->load->library('rest');
	}

	public function index()
	{
		$this->rest->sendError(404, 'Service not found');
	}

    private function generateHash()
	{
        $result = "";
        $charPool = '0123456789abcdefghijklmnopqrstuvwxyz';
		
		for ($p = 0; $p < 15; $p++)
		{
			$result .= $charPool[mt_rand(0,strlen($charPool)-1)];
		}
		
        return sha1(md5(sha1($result)));
    }
	
    public function login_eatp()
	{
		$this->mobile_model->startLogActivity('login');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('usri', 'usrp');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			$fixData = $dataReceived;

			// check handsetValidity
			$checkUser = $this->mobile_model->checkUser($fixData->usri);
			$fixData->usri = trim($fixData->usri);    
			$fixData->usri= strtolower($fixData->usri);
			$userHandset = $this->mobile_model->checkUserHandset($fixData->usri); // take out (,$fixData->imsi,$fixData->mdn) by iwa 10 mar 2016 , take out ,$fixData->imei by iwa 22 nov 2016

			$user_data = array("user_id" => $fixData->usri); // take out ,"imei"=>$fixData->imei,"mdn"=>$fixData->mdn by iwa 22 nov 2016
			$user_id   = $fixData->usri;

			if ($checkUser == NULL)
			{
				$this->rest->response(array(
					'req' => 'login',
					'res' => 'not ok',
					'msg' => 'User is not registered.'
				), 401);
			}
			else
			{
				if ($userHandset == NULL) 
				{
					$this->rest->response(array(
						'errorCode' => 401,
						'req' => 'login',
						'res' => 'not ok',
						'msg' => 'You are using invalid handset. Please check IMEI.'
					), 401);
				}
				else 
				{
					$checkPassword = $this->mobile_model->checkPassword($fixData->usri, md5($fixData->usrp));
					$isMobileUser = $this->mobile_model->checkMobileUser($fixData->usri, md5($fixData->usrp));

					$user = $this->mobile_model->mobileUserLoggedin($fixData->usri);
				  
					if ($checkPassword == NULL)
					{
						$this->rest->response(array(
							'req' => 'login',
							'res' => 'not ok',
							'msg' => 'Incorrect Password.'
						), 401);
					}
					else
					{
						if ($isMobileUser == NULL)
						{
							$this->rest->response(array(
								'req' => 'login',
								'res' => 'not ok',
								'msg' => 'You have not an authorization to use mobile application.'
							), 401);
						}
						else
						{
							if ($user == NULL)
							{
								$this->rest->response(array(
									'req' => 'login',
									'res' => 'not ok',
									'msg' => 'You are not mobile user.'
								), 401);
							}
							elseif ($user->is_login)
							{
								$this->rest->response(array(
									'req' => 'login',
									'res' => 'not ok',
									'msg' => 'You already logged in on other device. Please logout first.'
								), 401);
							}
							else
							{
								$mobileToken = $this->generateHash();

								$data = array(
									'is_login' => 1,
									'mobile_token' => $mobileToken
								);
								
								if ($this->mobile_model->updateUserLoginStatus($data, $fixData->usri))
								{
									$this->rest->response(array(
										'req' => 'login',
										'res' => 'ok',
										'msg' => 'Logged in successfully.',
										'token' => $mobileToken,
										'usri'=> $fixData->usri,
										'user_name' => $user->user_name,
										'user_group_id' => $user->user_group_id,
										'user_group_name' => $user->user_group_name,
										'region_id' => $user->region_id,
										'region_name' => $user->region_name,
										'contact' => $user->mobile_number,
										//'image_signature' => pg_unescape_bytea($user->image_signature)
									), 200);
									
									//$this->dblog->AuditTrail('user_login', $user_data, "mobile", $user_id);
								}
								else 
								{
									$this->rest->response(array(
										'req' => 'login',
										'res' => 'not ok',
										'msg' => 'Sistem was not available right now.'
									), 404);
								}
							}
							
						}
					}
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}
	
	public function hsAuth()
	{
		$this->mobile_model->startLogActivity('hsAuth');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('userId', 'password');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			$fixData = $dataReceived;

			// check handsetValidity
			$checkUser = $this->mobile_model->checkUser($dataReceived->userId);
			
			$dataReceived->userId = trim($dataReceived->userId);    
			$dataReceived->userId = strtolower($dataReceived->userId);
			
			$userHandset = $this->mobile_model->checkUserHandset($dataReceived->userId);
			
			$user_id = $dataReceived->userId;
			$password = $dataReceived->password;

			if ($checkUser == NULL)
			{
				$this->rest->response(array(
					'req' => 'hsAuth',
					'res' => 'NOT_OK',
					'msg' => 'HS user is not registered.'
				), 401);
			}
			else
			{
				$user = $this->mobile_model->checkPassword($user_id, md5($password));
				
				if ( ! $user)
				{
					$this->rest->response(array(
						'req' => 'hsAuth',
						'res' => 'NOT_OK',
						'msg' => 'Incorrect Password.'
					), 401);
				}
				elseif ($user && $user['is_mobile_user'] != 1)
				{
					$this->rest->response(array(
						'req' => 'hsAuth',
						'res' => 'NOT_OK',
						'msg' => 'You have not an authorization to use mobile application.'
					), 401);
				}
				else
				{
					$mobileToken = $this->generateHash();

					$data = array(
						'is_login' => 1,
						'mobile_token' => $mobileToken
					);
					
					if ($this->mobile_model->updateUserLoginStatus($data, $user_id))
					{
						$this->rest->response(array(
							'req' => 'hsAuth',
							'res' => 'OK',
							'msg' => 'Logged in successfully.',
							'token' => $mobileToken,
							'userId' => $user_id,
							'userName' => $user['user_name'],
							'contact' => $user['mobile_number']
						), 200);
						
						//$user_data = array("user_id" => $user_id);
						
						//$this->dblog->AuditTrail('user_login', $user_data, "mobile", $user_id);
					}
					else 
					{
						$this->rest->response(array(
							'req' => 'hsAuth',
							'res' => 'NOT_OK',
							'msg' => 'Sistem was not available right now.'
						), 404);
					}
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	public function logout_eatp()
	{
		$this->mobile_model->startLogActivity('logout');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('usri');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->checkUser($dataReceived->usri))
			{
				$data = array(
					'is_login' => 0,
					'mobile_token' => NULL
				);
				
				$logout = $this->mobile_model->updateUserLoginStatus($data, $dataReceived->usri);
				
				if ($logout)
				{
					$this->rest->response(array(
						'req' => 'logout',
						'res' => 'ok',
						'msg' => 'Logged out successfully.'
					), 200);
					
					//$this->dblog->AuditTrail('user_logout', "logout by user", "mobile", $dataReceived->usri);
				}
			}
			else
			{
				$this->rest->response(array(
					'req' => 'logout',
					'res' => 'not ok',
					'msg' => 'Logged out failed. User not found.'
				), 401);
			}
		}
		
		$this->mobile_model->endLogActivity();
    }

	public function getJourneyCycle()
	{
		print_r($this->mobile_model->selectUserAndJourneyCycle('pic_1_1','2013-08-16'));

		$userId = $this->input->get('usrid');
		$date = date('Y-m-d');

		return $this->mobile_model->getJourneyCycle();
	}
  
	// get all data to mobile sqlite
	public function get_schedule()
	{
		$this->mobile_model->startLogActivity('getSchedule');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$thisWeek = date('W', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
				
				$decReason = $this->mobile_model->getLookup('dec_reason');

				$site = $this->mobile_model->selectUserAndJourneyCycle($this->mobile_model->tokenProfile->userId);
				
				$visitType = $this->mobile_model->getChecklistOption('eatp_visit_type');
				
				$radius = $this->mobile_model->getLookup('mobile_radius_checkin_out');

				$this->rest->response(array(
					'req' => 'getSchedule',
					'res' => 'ok',
					'dec_reason' => $decReason,
					//'order_type' => array(
					//	array('type' => 1, 'type_desc' => 'NBWO'),
					//	array('type' => 2, 'type_desc' => 'EWO')
					//),
					'this_week' => (int) $thisWeek,
					'radius' => array(
						'distance' => $radius ? (int) $radius[0]['id'] : 0,
						'uom' => $radius ? $radius[0]['name'] : ''
					),
					'data_site' => $site,
					//'visit_type' => $visitType
				), 200);
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	function treeGenerator(&$list, $parent, $visit_id)
	{
		$tree = array();
		foreach ($parent as $k=>$l)
		{
			if (isset($list[$l['checklist_group_id']]))
			{
				$l['sub_group'] = $this->treeGenerator($list, $list[$l['checklist_group_id']], $l['visit_id']);
			}
			
			$tree[] = $l;
		}
		
		return $tree;
	}

	function deflate_v2(&$array, $parent)
	{
		$flat_array = array();
		$k = 0;
		foreach ($array as $k=>$l)
		{
			if (isset($l['checklist_group_id']))
			{
				$flat_array[$k]['checklist_group_id'] = $l['checklist_group_id']; 
			}
		
			if (isset($l['parent_id']))
			{
				$flat_array[$k]['parent_id'] = $l['parent_id']; 
			}
			
			if (isset($l['parent_id2']))
			{
				$flat_array[$k]['parent_id2'] = $l['parent_id2']; 
			}
			
			if (isset($l['group_name']))
			{
				$flat_array[$k]['group_name'] = $l['group_name']; 
			}
			
			$flat_array[$k]['order_number'] = $l['order_number']; 
			$flat_array[$k]['status'] = $l['status']; 
			$flat_array[$k]['created_on'] = $l['created_on']; 
			$flat_array[$k]['created_by'] = $l['created_by']; 
			$flat_array[$k]['updated_on'] = $l['updated_on']; 
			$flat_array[$k]['updated_by'] = $l['updated_by']; 
			$flat_array[$k]['group_key'] = $l['group_key']; 
			$flat_array[$k]['description'] = $l['description']; 
			$flat_array[$k]['is_last_child'] = $l['is_last_child']; 
			$flat_array[$k]['level'] = $l['level']; 
			
			if (isset($l['detail']))
			{
				$flat_array[$k]['detail'] = $l['detail']; 
			}
			else
			{
				$flat_array[$k]['detail'] = array(); 
			}
			
			if (isset($l['visit_id']))
			{
				$flat_array[$k]['visit_id'] = $l['visit_id']; 
			}
			
			if (isset($array[$k]['sub_group']))
			{
				foreach ($array[$k]['sub_group'] as $sk => $sv)
				{
					$cheklist = $this->deflate2_v2($sv, $sv['parent_id2']);
					$flat_array[$k]['detail'] = array_merge($flat_array[$k]['detail'], $cheklist);
				}
			}
		}
		
		return $flat_array;
	}

	function deflate2_v2(&$array, $parent)
	{
		$r = array();

		if (isset($array['detail']))
		{
			$r = $array['detail'];
		}
		
		return $r;
	}
  	
	public function addjcsitepreparation()
	{
		$this->load->model('mobile_model');

		$data = $this->input->get_post('data');

		$fixData = json_decode($data);

		$final_inserted_data = array();
		$user_id = '';
		foreach ($fixData as $key)
		{
			$user_id = $key->created_by;

			$inserted_data = array( 
				'pt_id'   =>$key->pt_id,
				'created_by'=>$key->created_by,
				'jumlah'  =>$key->jumlah,
				'created_on'=>date('Y-m-d H:i:s')
			);
			
			array_push($final_inserted_data, $inserted_data);
		}

		if ($this->mobile_model->insert_jc_preparation($final_inserted_data))
		{
			$json = json_encode(array(
				"req" => "addjcsitepreparation",
				"res" => "ok"
			));
			
			$this->output
				->set_status_header(200)
				->set_output($json);
			
			$this->dblog->AuditTrail('pm_mobile_preparation', $final_inserted_data, "mobile",$user_id);       
		}
		else
		{
			$json = json_encode(array(
				"req" => "addjcsitepreparation",
				"res" => "nok"
			));
			
			$this->output
				->set_status_header(401)
				->set_output($json);
		}
    }

	public function addsiteconfirmno()
	{
		$this->mobile_model->startLogActivity('scheduleDeclination');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('jc_id', 'group_value', 'desc', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$fixData = $dataReceived;
				
				$visit_id = $params->jc_id;
				
				$dataConfirm = array(
					'visit_id' => $visit_id,
					'confirm' => 2,
					'reason_group' => $params->group_value,
					'reason_desc' => $params->desc,
					'created_by' => $this->mobile_model->tokenProfile->userId,
					'created_on' => date('Y-m-d H:i:s')
				);
				
				$data = $this->mobile_model->getVisit($visit_id);
				
				foreach ($data as $data2)
				{
					//$site_id = $data2['site_id'];
					$visit_date = $data2['visit_date'];
				}
				
				$dataStatus = array(
					'status' => 2,
					'updated_by' => $this->mobile_model->tokenProfile->userId,
					'updated_on' => date("Y-m-d H:i:s")            
				);
				
				if ($this->mobile_model->insertScheduleConfirm($dataConfirm) && $this->mobile_model->updateScheduleStatus($dataStatus, $visit_id))
				{
					$this->rest->response(array(
						"req" => "insertscheduledecline",
						"res" => "ok",
						"msg" => "Schedule declined successfully."
					), 200);
					
					//$this->dblog->AuditTrail('schedule_decline', $dataConfirm, 'mobile', $this->mobile_model->tokenProfile->userId);
				}
				else
				{
					$this->rest->response(array(
						"req" => "insertscheduledecline",
						"res" => "not ok",
						"msg" => "Schedule declined failed."
					), 401);
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}
  
	public function addsiteconfirmyes()
	{
		$this->mobile_model->startLogActivity('scheduleConfirmation');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('jc_id', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$fixData = $dataReceived;
				
				$visit_id = $fixData ->jc_id;

				$visit = $this->mobile_model->getVisitById($visit_id);

				$status = 1;

				if ($visit && $visit['status'] == 3)
				{
					$status = $visit['status'];
				}
				
				$dataConfirm = array(
					'visit_id' => $visit_id,
					'confirm' => 1, // 0 = decline, 1 = confirm/accept
					'created_by' => $this->mobile_model->tokenProfile->userId,
					'created_on' => date('Y-m-d H:i:s')
				);
				
				$checklist = $this->get_checklist($this->mobile_model->tokenProfile->userId, $visit_id, $visit['visit_type']);
				
				$dataStatus = array(
					'status' => $status, // 0 = not visited yet, 1 = confirm, 2 = decline, 3 = visited, 4 = unschedule, 5 = reschedule
					'updated_by' => $this->mobile_model->tokenProfile->userId,
					'updated_on' => date("Y-m-d H:i:s")            
				);
				
				if ($this->mobile_model->insertScheduleConfirm($dataConfirm) && $this->mobile_model->updateScheduleStatus($dataStatus, $visit_id))
				{
					$this->rest->response(array(
						"req" => "insertscheduleconfirm",
						"res" => "ok",
						"msg" => "Schedule confirmation successfully.",
						"checklist" => $checklist
					), 200);
					
					//$this->dblog->AuditTrail('schedule_confirm', $dataConfirm, 'mobile', $this->mobile_model->tokenProfile->userId);
				}
				else
				{
					$this->rest->response(array(
						"req" => "insertscheduleconfirm",
						"res" => "nok",
						"msg" => "Schedule confirmation failed.",
						"checklist" => $checklist
					), 401);
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}
  
	public function addcoordinatcheckin()
	{
		$this->mobile_model->startLogActivity('checkIn');

		$data = $this->input->get_post('data');

		$dataReceived = json_decode($data);
		$paramKeys = array('jc_id', 'site_id', 'lat', 'lon', 'distance', 'user_id', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($params->token))
			{
				$data = array( 
					'jc_id' => $params->jc_id,
					'waktu' => date('Y-m-d H:i:s'),
					'latitude' => $params->lat,
					'longitude' => $params->lon,
					'distance' => $params->distance,
					'created_by' => $params->user_id,
					'created_on' => date('Y-m-d H:i:s'),
					'updated_by' => $params->user_id,
					'updated_on' => date('Y-m-d H:i:s'),
					'type_id' => 2,
					'site_id' => $params->site_id
				);

				if ($this->mobile_model->insert_coordinat_checkin($data))
				{
					$this->rest->response(array(
						"req" => "sendcoordinatcheckin",
						"res" => "ok",
						"msg" => "Checked in successfully."
					), 200);
					
					//$this->dblog->AuditTrail('pm_checkin', $data, "mobile", $params->user_id);
				}
				else
				{
					$this->rest->response(array(
						"req" => "sendcoordinatcheckin",
						"res" => "nok",
						"msg" => "Checked in failed."
					), 401);
				}
			}
		}

		$this->mobile_model->endLogActivity();
	}
  
	public function addcoordinatcheckout()
	{
		$this->mobile_model->startLogActivity('checkOut');

		$data = $this->input->get_post('data');

		$dataReceived = json_decode($data);
		$paramKeys = array('jc_id', 'site_id', 'lat', 'lon', 'distance', 'user_id', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($params->token))
			{
				// update status to Visited (status = 3)
				$dataStatus = array(
					'status' => 3,
					'updated_by' => $this->mobile_model->tokenProfile->userId,
					'updated_on' => date("Y-m-d H:i:s")            
				);
				
				$this->mobile_model->updateScheduleStatus($dataStatus, $params->jc_id);

				$dataCoordinate = array(
					'jc_id' => $params->jc_id,
					'waktu' => date('Y-m-d H:i:s'),
					'latitude' => $params->lat,
					'longitude' => $params->lon,
					'distance' => $params->distance,
					'created_by' => $params->user_id,
					'created_on' => date('Y-m-d H:i:s'),
					'updated_by' => $params->user_id,
					'updated_on' => date('Y-m-d H:i:s'),
					'type_id' => 3,
					'site_id' => $params->site_id,
				);

				if ($this->mobile_model->insert_coordinat_checkin($dataCoordinate))
				{
					$this->rest->response(array(
						"req" => "sendcoordinatcheckout",
						"res" => "ok",
						"msg" => "Checked out successfully."
					), 200);
					
					//$this->dblog->AuditTrail('pm_checkout', $dataCoordinate, "mobile", $params->user_id);
				}
				else
				{
					$this->rest->response(array(
						"req" => "sendcoordinatcheckout",
						"res" => "nok",
						"msg" => "Checked out failed."
					), 401);
				}
			}
		}

		$this->mobile_model->endLogActivity();
	}
  
	public function site_tracking()
	{
		$data = $this->input->get_post('data');

		$fix_data = json_decode($data);

		$jc_id    = $this->input->get('jc_id');
		$waktu    = date('Y-m-d H:i:s');
		$latitude   = $this->input->get('latitude');
		$longitude  = $this->input->get('longitude');
		$created_by = $this->input->get('created_by');
		$created_on = date('Y-m-d H:i:s');
		$updated_by = $this->input->get('updated_by');
		$updated_on = date('Y-m-d H:i:s');

		$final_inserted_data = array();

		$inserted_data = array(
			'jc_id'     => $fix_data->jc_id 
			, 'waktu'   => date('Y-m-d H:i:s')
			, 'latitude'  => $fix_data->latitude
			, 'longitude'   => $fix_data->longitude
			, 'created_by'  => $fix_data->usri
			, 'created_on'  => date('Y-m-d H:i:s')
			, 'updated_by'  => $fix_data->usri
			, 'updated_on'  => date('Y-m-d H:i:s')
			, 'type_id'   => 1
		);

		array_push($final_inserted_data, $inserted_data);

		if ($this->mobile_model->insert_t_trx_pm_tracking2($final_inserted_data))
		{
			$json = json_encode(array(
				"req" => "site_tracking",
				"res" => "ok"
			));
			
			$this->output
				->set_status_header(200)
				->set_output($json);
		}
		else
		{
			$json = json_encode(array(
				"req" => "site_tracking",
				"res" => "nok"
			));
			
			$this->output
				->set_status_header(401)
				->set_output($json);
		}
    }

	public function addjcsitechecklist()
	{
		$data = $this->input->get_post('data');

		$key = json_decode($data);

		/*
		* Upload start
		*/
		
		$config['upload_path'] = './file/upload/';
		$config['allowed_types'] = 'gif|jpg|png';

		$this->load->library('upload', $config);
		
		$i = 0;
		foreach ($_FILES as $key => $value)
		{
			if ( ! empty($key['name']))
			{
				if ( ! $this->upload->do_upload($key))
				{
					$error = array('error' => $this->upload->display_errors());
				}
				else
				{
					$data = array('upload_data' => $this->upload->data());
					$i++;
				}
			}
		}

		/*
		* Upload finish
		*/

		if ($i == 2)
		{
			$data = $this->input->get_post('data');

			$key = json_decode($data);

			$inserted_data = array( 
				'visit_id'=>(int)$key->visit_id,
				// 'site_id'=>(int)$key->site_id,
				'checklist_id'=>(int)$key->checklist_id,
				'pm_result'=>$key->result,
				'pm_score'=>(int)$key->bobot,
				'created_by'=>$key->created_by,
				'created_on'=>date('Y-m-d H:i:s'),
				'photo_before'=>$key->photo_before,
				'photo_after'=>$key->photo_after,
				'used_sparepart'=>$key->used_sparepart,
				'used_comsumpart'=>$key->used_consumpart,
				'req_sparepart'=>$key->req_sparepart,
				'req_comsumpart'=>$key->req_consumpart
			);

			if ($this->mobile_model->insert_jc_sitechecklist($inserted_data))
			{
				$json = json_encode(array(
					"req" => "addjcsitechecklist",
					"res" => "ok"
				));
				
				$this->output
					->set_status_header(200)
					->set_output($json);
			}
			else
			{
				$json = json_encode(array(
					"req" => "addjcsitechecklist",
					"res" => "nok"
				));
				
				$this->output
					->set_status_header(401)
					->set_output($json);
			}
		  
		}
		else
		{
			$json = json_encode(array(
				"req" => "addjcsitechecklist",
				"res" => "nok"
			));
			
			$this->output->set_output($json);
		}
    }

	public function saveChecklist_old()
	{
		ini_set("memory_limit", "-1");
		set_time_limit(0);
		$this->mobile_model->startLogActivity('saveChecklist');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'checklist_id', 'result', 'latitude', 'longitude', 'photo_remark', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($params->token))
			{
				$dataChecklist = $this->mobile_model->getChecklistResult((int) $params->visit_id, (int) $params->checklist_id);

				$photoPath = '';
				
				if ($_FILES)
				{
					$path = 'file/upload/atpmobile/'.date('Y/m/d');

					$fileName = 'IMG_'.$this->mobile_model->tokenProfile->userId.'_visitId-'.$params->visit_id.'checklistId-'.$params->checklist_id.'_'.date('Ymd_His');

					if ($dataChecklist && file_exists(FCPATH.$dataChecklist['photo_name']) && is_file(FCPATH.$dataChecklist['photo_name']))
					{
						//$filePath = FCPATH.ltrim($dataChecklist['photo_name'], '/');
						//$recentPath = str_replace('/'.basename($filePath), '', $filePath);

						//$pathInfo = pathinfo($filePath);
						//$fileName = $pathInfo['filename'];
						//$extension = $pathInfo['extension'];

						//@unlink($recentPath.'/'.$fileName.'.'.$extension);exit($recentPath.'/'.$fileName.'.'.$extension);
						@unlink(FCPATH.$dataChecklist['photo_name']);
					}
					
					if ( ! is_dir(FCPATH.$path))
					{
						mkdir(FCPATH.$path, 0777, TRUE);
					}
					
					$config = array();
					$config['upload_path'] = FCPATH.$path;
					$config['allowed_types'] = 'jpg|gif|png';
					$config['file_name'] = $fileName;
					$config['overwrite'] = TRUE;

					$this->load->library('upload', $config);

					$uploadKey = array_keys($_FILES);
					
					if ($this->upload->do_upload($uploadKey[0]))
					{
						$data = array('upload_data' => $this->upload->data());
						$configFileName = $data['upload_data']['file_name'];
						
						chmod(FCPATH.$path.'/'.$configFileName, 0777);
						
						$photoPath = $path.'/'.$configFileName;
						
						//Start::ftp upload - 22/05/2022 (ilham sihabudin)
						// $this->load->library('ftp');

						// $config['hostname'] = '10.0.5.85';
						// $config['username'] = 'msdev';
						// $config['password'] = '@4l1t4devms';
						// $config['debug'] = TRUE;
				
						// $this->ftp->connect($config);
				
						// if ($this->ftp->list_files('/Backup_eatpTRM2/file/upload/atpmobile/'.date('Y/m/d').'/') == FALSE) {
						// 	$this->ftp->mkdir('/Backup_eatpTRM2/file/upload/atpmobile/'.date('Y/m/d').'/', DIR_WRITE_MODE);
						// }

						// $destination = '/Backup_eatpTRM2/file/upload/atpmobile/'.date('Y/m/d').'/'.$fileName;

						// $this->ftp->upload($photoPath, $destination);
				
						// $this->ftp->close();

						// @unlink($source);

						$headers = array("Content-Type:multipart/form-data");
						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_URL => 'http://10.0.5.85:5050/upload.php',
							CURLOPT_HEADER => true,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_HTTPHEADER => $headers,
							CURLOPT_ENCODING => "",
							CURLOPT_TIMEOUT => 30,
							CURLOPT_POST => 1,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => "POST",
							CURLOPT_POSTFIELDS => [
								'path' 	=> 'file/upload/atpmobile/'.date('Y/m/d').'/',
								'image' => new CURLFile($_FILES[$uploadKey[0]]['tmp_name'], $_FILES[$uploadKey[0]]['type'], $configFileName),
							],
						));
						$response = curl_exec($curl);

						//End::ftp upload - 22/05/2022 (ilham sihabudin)

						
						//$fp = fopen(FCPATH.$path.'/'.$configFileName.'_'.time().'_bak', 'w');
						//fwrite($fp, file_get_contents($_FILES[$uploadKey[0]]['tmp_name']));
						//fclose($fp);
						
						/*
						if ($dataChecklist)
						{
							$photoPath = $dataChecklist['photo_name'];
						}
						*/
					}
					else
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "NOT_OK",
							"msg" => $this->upload->display_errors('', ''),
							"path" => FCPATH.$path
						), 406);
						
						$this->mobile_model->endLogActivity();
						return;
					}
				}
				
				if ( ! $dataChecklist)
				{
					$inserted_data = array(
						'visit_id' => (int) $params->visit_id,        
						'checklist_id '=> (int) $params->checklist_id,
						'checklist_result' => $params->result,
						//'score' => (int) $params->bobot,
						'created_by' => $this->mobile_model->tokenProfile->userId,
						'created_on' => date('Y-m-d H:i:s'),
						'latitude' => $params->latitude,
						'longitude' => $params->longitude,
					);
			
					$inserted_photo = array(
						'checklist_id' => (int) $params->checklist_id,
						'photo_name' => $photoPath,
						//'photo_remark' => $params->photo_remark,
						'created_by' => $this->mobile_model->tokenProfile->userId,
						'created_on' => date('Y-m-d H:i:s'),
						'visit_id' => (int) $params->visit_id
					);

					if ($this->mobile_model->insertChecklist($inserted_data, $inserted_photo))
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "OK",
							"msg" => "Checklist saved successfully.",
							"data" => array(
								'sent' => $params,
								'inserted_1' => $inserted_data,
								'inserted_2' => $inserted_photo
							)
						), 200);
					}
					else 
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "NOT_OK",
							"msg" => "Checklist save failed."
						), 401);
					}
				}
				else
				{
					$inserted_data = array(
						'visit_id' => (int) $params->visit_id,
						'checklist_id' => (int) $params->checklist_id,
						'checklist_result' => $params->result,
						//'score' => (int) $params->bobot,
						'updated_by' => $this->mobile_model->tokenProfile->userId,
						'updated_on' => date('Y-m-d H:i:s'),
						'latitude' => $params->latitude,
						'longitude' => $params->longitude,
					);
			
					$inserted_photo = array(
						'checklist_id' => (int) $params->checklist_id,
						'photo_name' => $photoPath,
						//'photo_remark' => $params->photo_remark,
						'updated_by' => $this->mobile_model->tokenProfile->userId,
						'updated_on' => date('Y-m-d H:i:s'),
						'visit_id' => (int) $params->visit_id
					);

					if ($this->mobile_model->update_jc_sitechecklist2((int) $params->visit_id, (int) $params->checklist_id, $inserted_data, $inserted_photo))
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "OK",
							"msg" => "Checklist saved successfully.",
							"data" => array(
								'sent' => $params,
								'updated_1' => $inserted_data,
								'updated_2' => $inserted_photo
							)
						), 200);
					}
					else
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "NOT_OK",
							"msg" => "Checklist save failed."
						), 401);
					}
				}
			}
		}
	
		$this->mobile_model->endLogActivity();
	}

	//* UPDATE 02-10-2023 (Compress gambar pas upload)
	public function compress($source, $destination, $quality) {

		$info = getimagesize($source);
	
		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);
	
		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);
	
		elseif ($info['mime'] == 'image/png') 
			$image = @imagecreatefrompng($source);
	
		imagejpeg($image, $destination, $quality);
	
		return $destination;
		}
	//*

	public function saveChecklist()
	{
		ini_set("memory_limit", "-1");
		set_time_limit(0);
		//$this->mobile_model->startLogActivity('saveChecklist');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'checklist_id', 'site_id', 'result', 'latitude', 'longitude', 'photo_remark', 'group_name', 'image_checklist', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			$params->result = str_replace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $params->result); // Replaces all spaces with hyphens.
			$params->photo_remark = str_replace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $params->photo_remark); // Replaces all spaces with hyphens.
			if ($this->mobile_model->tokenValidator($params->token))
			{
				$dataChecklist = $this->mobile_model->getChecklistResult((int) $params->visit_id, (int) $params->checklist_id);
				$checklistLink = $this->mobile_model->getChecklistLink((int) $params->visit_id, $params->site_id, (int) $params->checklist_id);

				$photoPath = '';

				if ( ! empty($params->image_checklist) && $checklistLink)
				{
					$path = 'file/upload/atpmobile/'.date('Y/m/d');
					$imsPath = date('Y/m/d');

					if ( ! is_dir(FCPATH.$path))
					{
						mkdir(FCPATH.$path, 0777, TRUE);
					}

					if (is_dir('E:/EATP/Foto'))
					{
						if ( ! is_dir('E:/EATP/Foto/'.$imsPath))
						{
							mkdir('E:/EATP/Foto/'.$imsPath, 0777, TRUE);
						}
					}

					$imgData = base64_decode($params->image_checklist);
					$f = finfo_open();
					$mimeType = finfo_buffer($f, $imgData, FILEINFO_MIME_TYPE);
					
					$exts = array(
						'image/jpeg' => '.jpg',
						'image/png' => '.png',
						'image/gif' => '.gif'
					);
					
					$extAllowed = array(
						'.jpg',
						'.jpeg',
						'.gif',
						'.png'
					);

					if ( ! in_array($exts[$mimeType], $extAllowed))
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "NOT_OK",
							"msg" => "Invalid file type. Please upload file extension .jpg, .gif, .png only."
						), 401);

						//$this->mobile_model->endLogActivity();
						return;
					}
					else
					{
						if ($dataChecklist && file_exists(FCPATH.$dataChecklist['photo_name']) && is_file(FCPATH.$dataChecklist['photo_name']))
						{
							@unlink(FCPATH.$dataChecklist['photo_name']);
							@unlink('E:/EATP/Foto/'.$imsPath.str_replace($path, '', $dataChecklist['photo_name']));
						}			
					
						$fileName = url_title($checklistLink->location_id).'_'.str_replace('|', '-', $params->site_id).'_Alita_'.url_title($params->group_name).'_'.str_replace(array('/','\\','<>'), '-', $checklistLink->ewo_no).'_'.str_replace(array('<>','|'), '-', $checklistLink->link_id).'_'.date('Ymd').'_'.$params->visit_id.'_'.$params->checklist_id.$exts[$mimeType];
							
						try
						{
							// file_put_contents(FCPATH.$path.'/'.$fileName, $imgData);
							//* UPDATE 02-10-2023 (Compress gambar pas upload)
							file_put_contents(FCPATH.$path.'/'.$fileName, $imgData);
							$this->compress(FCPATH.$path.'/'.$fileName, FCPATH.$path.'/'.$fileName, 33);
							$img_data_compressed = file_get_contents(FCPATH.$path.'/'.$fileName);
							$params->image_checklist = base64_encode($img_data_compressed);
							unlink(FCPATH.$path.'/'.$fileName);
							//*
							# Start Upload to Nas :: 17/06/2022
							$headers = array("Content-Type:multipart/form-data");
							$curl = curl_init();
							curl_setopt_array($curl, array(
								CURLOPT_URL => 'http://10.0.5.85:5050/upload.php',
								CURLOPT_HEADER => true,
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_HTTPHEADER => $headers,
								CURLOPT_ENCODING => "",
								CURLOPT_TIMEOUT => 30,
								CURLOPT_POST => 1,
								CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
								CURLOPT_CUSTOMREQUEST => "POST",
								CURLOPT_POSTFIELDS => [
									'path' 	=> 'file/upload/atpmobile/'.date('Y/m/d').'/',
									'image' => 'data:'.$mimeType.';base64,'.$params->image_checklist,
									'image_name' => $fileName
								],
							));
							$response = curl_exec($curl);
							
							if (is_dir('E:/EATP/Foto'))
							{
								file_put_contents('E:/EATP/Foto/'.$imsPath.'/'.$fileName, $imgData); // IMS

								chmod('E:/EATP/Foto/'.$imsPath.'/'.$fileName, 0777);
							}

							chmod(FCPATH.$path.'/'.$fileName, 0777);
						}
						catch(Exception $e)
						{
							$this->rest->response(array(
								"req" => "saveChecklist",
								"res" => "NOT_OK",
								"msg" => "Occurred error when write file into the disk. Trace: ".$e->getTraceAsString()
							), 401);

							//$this->mobile_model->endLogActivity();
							return;
						}
						
						$photoPath = $path.'/'.$fileName;
					}
				}
				
				
				if ( ! $dataChecklist)
				{
					$inserted_data = array(
						'visit_id' => (int) $params->visit_id,        
						'checklist_id '=> (int) $params->checklist_id,
						'site_id '=> $params->site_id,
						'checklist_result' => $params->result,
						//'score' => (int) $params->bobot,
						'created_by' => $this->mobile_model->tokenProfile->userId,
						'created_on' => date('Y-m-d H:i:s'),
						'latitude' => $params->latitude,
						'longitude' => $params->longitude,
					);
			
					$inserted_photo = array(
						'checklist_id' => (int) $params->checklist_id,
						'photo_name' => $photoPath,
						'photo_remark' => $params->photo_remark,
						'created_by' => $this->mobile_model->tokenProfile->userId,
						'created_on' => date('Y-m-d H:i:s'),
						'visit_id' => (int) $params->visit_id
					);

					if ($this->mobile_model->insertChecklist($inserted_data, $inserted_photo))
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "OK",
							"msg" => "Checklist insert successfully."
						), 200);
					}
					else 
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "NOT_OK",
							"msg" => "Checklist save failed."
						), 401);
					}
				}
				else
				{
					$inserted_data = array(
						'visit_id' => (int) $params->visit_id,
						'checklist_id' => (int) $params->checklist_id,
						'checklist_result' => $params->result,
						//'score' => (int) $params->bobot,
						'updated_by' => $this->mobile_model->tokenProfile->userId,
						'updated_on' => date('Y-m-d H:i:s'),
						'latitude' => $params->latitude,
						'longitude' => $params->longitude,
					);
			
					$inserted_photo = array(
						'checklist_id' => (int) $params->checklist_id,
						'photo_name' => $photoPath,
						'photo_remark' => $params->photo_remark,
						'updated_by' => $this->mobile_model->tokenProfile->userId,
						'updated_on' => date('Y-m-d H:i:s'),
						'visit_id' => (int) $params->visit_id
					);

					if ($this->mobile_model->updateChecklist((int) $params->visit_id, (int) $params->checklist_id, $inserted_data, $inserted_photo))
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "OK",
							"msg" => "Checklist updated successfully."
						), 200);
					}
					else
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "NOT_OK",
							"msg" => "Checklist save failed."
						), 401);
					}
				}
			}
		}
	
		//$this->mobile_model->endLogActivity();
	}
	
	/*
	public function saveChecklistTest()
	{
		if ($_FILES)
		{
			// Upload photo
			$path = 'file/upload/atpmobile-test/'.date('Y/m/d');
			
			if ( ! is_dir(FCPATH.$path))
			{
				mkdir(FCPATH.$path, 0777, TRUE);
			}
			
			$allowed = array(
				'.jpg',
				'.jpeg',
				'.gif',
				'.png'
			);
			
			$photoPath = NULL;
			
			$uploadKey = array_keys($_FILES);
			
			if ( ! empty($_FILES[$uploadKey[0]]['name']) && $_FILES[$uploadKey[0]]['error'] == UPLOAD_ERR_OK)
			{
				$tmpName = $_FILES[$uploadKey[0]]['tmp_name'];
				$ext = strtolower(strrchr($_FILES[$uploadKey[0]]['name'], '.'));
				$fileName = 'IMG_'.date('Ymd_His').'_'.time().$ext;

				if ( ! in_array($ext, $allowed))
				{
					echo 'Invalid file type. Please upload file extension .jpg, .gif, .png only.';
				}
				else
				{
					if (is_safe_upload_name($fileName) && @move_uploaded_file($tmpName, FCPATH.$path.'/'.$fileName))
					{
						chmod(FCPATH.$path.'/'.$fileName, 0777);
					}
				}
			}
		}
	}
  	*/
	
	public function addjcsitechecklist3()
	{
		$data = $this->input->get_post('data');

		$key = json_decode($data);

		$is_data_exist = $this->mobile_model->getChecklistResult((int) $key->visit_id, (int)$key->checklist_id);
		
		if ($is_data_exist == NULL) 
		{
			$inserted_data = array( 
				'visit_id'=>(int)$key->visit_id,        
				'checklist_id'=>(int)$key->checklist_id,
				'pm_result'=>$key->result,
				'pm_score'=>(int)$key->bobot,
				'created_by'=>$key->created_by,
				'created_on'=>date('Y-m-d H:i:s'),
				'used_sparepart'=>$key->used_sparepart,
				'used_comsumpart'=>$key->used_consumpart,
				'req_sparepart'=>$key->req_sparepart,
				'req_comsumpart'=>$key->req_consumpart
			);
			
			if ($this->mobile_model->insert_jc_sitechecklist3($inserted_data)) 
			{
				$this->output
					->set_status_header(200)
					->set_output(json_encode(array(
						"req" => "addjcsitechecklist",
						"res" => "ok"
					)));
			}
			else 
			{
				$this->output
					->set_status_header(401)
					->set_output(json_encode(array(
						"req" => "addjcsitechecklist",
						"res" => "nok"
					)));
			}
		}
		else 
		{
			$inserted_data = array( 
				'visit_id'=>(int)$key->visit_id,        
				'checklist_id'=>(int)$key->checklist_id,
				'pm_result'=>$key->result,
				'pm_score'=>(int)$key->bobot,
				'created_by'=>$key->created_by,
				'created_on'=>date('Y-m-d H:i:s'),
				'used_sparepart'=>$key->used_sparepart,
				'used_comsumpart'=>$key->used_consumpart,
				'req_sparepart'=>$key->req_sparepart,
				'req_comsumpart'=>$key->req_consumpart
			);
			
			if ($this->mobile_model->update_jc_sitechecklist3((int)$key->visit_id ,(int)$key->checklist_id ,$inserted_data)) 
			{
				$this->output
					->set_status_header(200)
					->set_output(json_encode(array(
						"req" => "addjcsitechecklist",
						"res" => "ok"
					)));
			}
			else 
			{
				$this->output
					->set_status_header(401)
					->set_output(json_encode(array(
						"req" => "addjcsitechecklist",
						"res" => "nok"
					)));
			}
		}
	}

	public function getSite()
	{
		$this->output
			->set_status_header(200)
			->set_output(json_encode($this->mobile_model->get_mtr_site()));
	}

	public function getVisitOptions()
	{
		$this->output
			->set_status_header(200)
			->set_output(json_encode($this->mobile_model->get_t_mtr_pm_options()));
	}

	public function coba_coba()
	{
		$site = $this->mobile_model->selectUserAndJourneyCycle2_2('58','pic_1_1','2013-10-27','43');
		print_r($site);
	}

	//set schedule visited by Rizki NS
	public function set_visited()
	{
		$data = $this->input->get_post('data');
		
		$fixData = json_decode($data);

		$visited_id = $fixData->jc_id;
		$user_id = $fixData->user_id;

		if ($this->mobile_model->update_t_trx_pm_schedule2($visited_id, $user_id))
		{
			$result = array(
				"req"=>'set_visited'
				,"res"=>'ok'
			);

			$this->output
				->set_status_header(200)
				->set_output(json_encode($result));
			
			$this->dblog->AuditTrail('pm_schedule_visited', $fixData, "mobile",$user_id);
		}
		else
		{
			$result = array(
				"req"=>'set_visited'
				,"res"=>'not ok'
			);

			$this->output
				->set_status_header(401)
				->set_output(json_encode($result));
		}
	}
  
	public function cek_password_pointing()
	{
		$inputPassword = file_get_contents("php://input");
    
		$password=$this->mobile_model->cek_password_admin_pointing($inputPassword);   
		
		if ($password == null)
		{
		  $notification = "false";
		  $message = "password salah";
		}
		else
		{
		  $notification = "true";
		  $message = "password benar";
		}
		
		$result = array(
			"success" => $notification, 
			"message" => $message
		);
		
		$this->output
			->set_status_header(200)
			->set_output(json_encode($result));
    }
  
	public function change_url_pointing()
    {
		$inputUrl = file_get_contents("php://input");
    
		$url = $this->mobile_model->cek_url_pointing($inputUrl);    
		
		if ($url == null)
		{
			$notification = "false";
			$message = "URL not valid";
		}
		else
		{
			$notification = "true";
			$message = "Change URL pointing success";
		}
		
		$result = array(
			"success"       => $notification, 
			"message"       => $message
		);
		
		$this->output
			->set_status_header(200)
			->set_output(json_encode($result));
    }
  
	public function change_connection_timeout()
    {
		$globalConnectionTimeout = file_get_contents("php://input");
		
		$connection_timeout=$this->mobile_model->change_setting_connection_timeout($globalConnectionTimeout);   
		$socket_timeout=$this->mobile_model->change_setting_socket_timeout($globalConnectionTimeout);
		
		if ($connection_timeout == null || $socket_timeout == null)
		{
			$notification = "false";
			$message = "Get timeout failed";
			$connection_timeout = 600000;
			$socket_timeout = 600000;
		}
		else
		{
			$notification = "true";
			$message = "Get timeout success";
		}
		
		$result = array(
			"success"       => $notification, 
			"message"       => $message,
			"connection_timeout"  => $connection_timeout,
			"socket_timeout"    => $socket_timeout
		);
		
		$this->output
			->set_status_header(200)
			->set_output(json_encode($result));
	}
  
	public function get_checklist($the_user_id, $visit_id, $visit_type)
	{
		// Di sini dimulai koding generate semua ceklist
		$the_date_today = date('W', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
		//$site = $this->mobile_model->selectUserAndJourneyCycle($the_user_id,$the_date_today); 
		$site = $this->mobile_model->selectUserAndJourneyCyclePerVisit($the_user_id, $visit_id); 
		$properties = $this->mobile_model->inspectionProperties($the_user_id, $visit_id, $visit_type);
		
		
		$checklist_item = array();
		$cci = 0;
		foreach ($properties['item'] as $checklist)
		{
			$checklist_item[$cci] = $checklist;
			
			$checklist_item[$cci]['c_option'] = array();
			foreach ($properties['options'] as $option)
			{
				if ($option['visit_id'] == $checklist['visit_id'] && $option['checklist_id'] == $checklist['checklist_id'])
				{
					$checklist_item[$cci]['c_option'][] = $option;
				}
			}
			
			$cci++;
		}
		
		$_tmpRawTree = array();
		$vgi = 0;
		foreach ($site as $sch)
		{
			$_tmpRawTree[$vgi] = $sch;
			
			$tgi = 0;
			foreach ($properties['group'] as $treegroup)
			{
				$_tmpRawTree[$vgi]['cgroup'][$tgi] = $treegroup;
				$_tmpRawTree[$vgi]['cgroup'][$tgi]['visit_id'] = $sch['visit_id'];

				foreach ($checklist_item as $key => $val)
				{
					if ($val['checklist_group'] == $treegroup['checklist_group_id'] &&  $sch['visit_id'] == $val['visit_id'])
					{
						$_tmpRawTree[$vgi]['cgroup'][$tgi]['detail'][] = $val;
					}
				}
				$tgi++;
			}
			
			$vgi++;
		}
		
		$ptr = 0;
		foreach ($_tmpRawTree as $tryout)
		{
			$new = array();
			$_tmpRawTree[$ptr] = $tryout;
			
			foreach ($tryout['cgroup'] as $a)
			{
				$new[$a['parent_id2']][] = $a;
			}
			
			$treeGroup = $this->treeGenerator($new, $new[598], $tryout['visit_id']);
			$_tmpRawTree[$ptr++]['cgroup'] = $this->deflate_v2($treeGroup, 598);
		}
		
		return $_tmpRawTree;
	}

	public function get_checklist_old($the_user_id,$visit_id)
	{
		// Di sini dimulai koding generate semua ceklist
		$the_date_today = date('W', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
		
		$site = $this->mobile_model->selectUserAndJourneyCycleOld($the_user_id,$the_date_today); 
		$properties = $this->mobile_model->inspectionProperties($the_user_id,$visit_id);
		
		$checklist_item = array();
		$cci = 0;
		foreach ($properties['item'] as $checklist)
		{
			$checklist_item[$cci] = $checklist;
			
			$checklist_item[$cci]['c_option'] = array();
			foreach ($properties['options'] as $option)
			{
				if ($option['visit_id'] == $checklist['visit_id'] && $option['checklist_id'] == $checklist['checklist_id'])
				{
					$checklist_item[$cci]['c_option'][] = $option;
				}
			}
			
			$cci++;
		}
		
		$_tmpRawTree = array();
		$vgi = 0;
		foreach ($site as $sch)
		{
			$_tmpRawTree[$vgi] = $sch;
			
			$tgi = 0;
			foreach ($properties['group'] as $treegroup)
			{
				$_tmpRawTree[$vgi]['cgroup'][$tgi] = $treegroup;
				$_tmpRawTree[$vgi]['cgroup'][$tgi]['visit_id'] = $sch['visit_id'];

				foreach ($checklist_item as $key => $val)
				{
					if ($val['checklist_group'] == $treegroup['checklist_group_id'] &&  $sch['visit_id'] == $val['visit_id'])
					{
						$_tmpRawTree[$vgi]['cgroup'][$tgi]['detail'][] = $val;
					}
				}
				
				$tgi++;
			}
			
			$vgi++;
		}
		
		$ptr = 0;
		foreach ($_tmpRawTree as $tryout)
		{
			$new = array();
			
			$_tmpRawTree[$ptr] = $tryout;
		  
			foreach ($tryout['cgroup'] as $a)
			{
				$new[$a['parent_id2']][] = $a;
			}
			
			$treeGroup = $this->treeGenerator($new, $new[598], $tryout['visit_id']);
			$_tmpRawTree[$ptr++]['cgroup'] = $this->deflate_v2($treeGroup, 598);
		}
		print_rr($_tmpRawTree);
		return $_tmpRawTree;
	}

	/*
	 * Versioning Module
	 * 
	 * 
	 * Andedi 
	 * 2016-01-29
	 */
	public function check_apk_update()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('imei', 'imei', 'trim|required');
		$this->form_validation->set_rules('version', 'version', 'trim|required');

		$version = $this->input->post('version');
		$imei = $this->input->post('imei');
		
		$response = array();

		if ($this->form_validation->run())
		{
			$handset = $this->_check_handset(array('imei' => $imei));
			$apk = $this->_check_apk_version($version);
			$response = $this->_save_apk_update($handset, $apk);
			$row = $this->mobile_model->get_apk_active();
			
			if ($this->_get_fix_version($version) < $this->_get_fix_version($row->version_number))
			{
				$response['url'] = base_url("download/{$row->app_url}");
			}
			else
			{
				$response['url'] = '';
			}
		}
		else
		{
			$this->output->set_status_header(400);
			
			$response = array(
				'status' => 'warning',
				'message' => validation_errors()
			);
		}

		$this->output
			->set_output(json_encode($response));
	}

	private function _check_handset($param)
	{
		$row = $this->mobile_model->get_handset($param);
		
		if ($row != NULL)
		{
			return $row;
		}
		else
		{
			$this->output->set_status_header('400');
			
			$response = array(
				'status' => 'warning',
				'message' => 'The handset is not registered'
			);
			
			$this->output
				->set_status_header(401)
				->set_output(json_encode($response));
		}
	}

	private function _check_apk_version($param)
	{
		$row = $this->mobile_model->get_apk_version($param);
		
		if ($row != NULL)
		{
			return $row;
		}
		else
		{
			$this->output->set_status_header('400');
			
			$response = array(
				'status' => 'warning',
				'message' => 'The APK Version is doesn\'t exist'
			);
			
			$this->output
				->set_status_header(401)
				->set_output(json_encode($response));
		}
	}

	private function _save_apk_update($handset, $apk)
	{
		$response = array();
		
		$query = $this->mobile_model->save_apk_update($handset, $apk);
		
		if ($query)
		{
			$response = array(
				'status' => 'success',
				'message' => 'Update APK data succesfully.'
			);
		}
		else
		{
			$this->output->set_status_header('400');
			$response = array(
				'status' => 'error',
				'message' => 'Update APK data could not be saved.'
			);
		}

		return $response;
	}

	private function _get_fix_version($param)
	{
		$param = str_replace('.', '', $param);

		return intval($param);
	}

	private function _is_mobile()
	{
		$this->load->library('user_agent');
		
		if (!$this->agent->is_mobile())
		{
		  show_404();
		}
	}

	//get all data bom to mobile sqlite
	public function get_link_bom()
	{
		$this->mobile_model->startLogActivity('getLinkBom');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$visit_id = $dataReceived->visit_id;
	 
				$i = 0;
				$bom = $this->mobile_model->selectLinkBom($visit_id);
				
				$this->rest->response(array(
					'data_bom' => $bom
				), 200);
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	public function add_summary_obsolete()
	{
		$this->mobile_model->startLogActivity('addSummary');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'acceptance_status', 'remark', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$dataSummary = array(); 
				$dataSummary['visit_id'] = $params->visit_id;
				$dataSummary['acceptance_status'] = $params->acceptance_status;
				$dataSummary['user_id'] = $this->mobile_model->tokenProfile->userId;
				$dataSummary['remark'] = $params->remark;

				if ($this->mobile_model->getSummary(array('visit_id' => $params->visit_id, 'user_id' => $this->mobile_model->tokenProfile->userId)))
				{
					$dataSummary['updated_by'] = $this->mobile_model->tokenProfile->userId;
					$dataSummary['updated_on'] = date('Y-m-d H:i:s');

					if ($this->mobile_model->updateSummary($dataSummary, array('visit_id' => $params->visit_id, 'user_id' => $this->mobile_model->tokenProfile->userId)))
					{
						$this->rest->response(array(
							'req' => 'sendsummary',
							'res' => 'ok',
							'msg' => 'Summary saved successfully.'
						), 200);
						
						//$this->dblog->AuditTrail('sendsummary', $dataSummary, 'mobile', $this->mobile_model->tokenProfile->userId);
					}
					else
					{
						$this->rest->response(array(
							'req' => 'sendsummary',
							'res' => 'not ok',
							'msg' => 'Summary save failed.'
						), 401);
					}
				}
				else
				{
					$dataSummary['created_by'] = $this->mobile_model->tokenProfile->userId;
					$dataSummary['created_on'] = date('Y-m-d H:i:s');

					if ($this->mobile_model->insertSummary($dataSummary))
					{
						$this->rest->response(array(
							'req' => 'sendsummary',
							'res' => 'ok',
							'msg' => 'Summary saved successfully.'
						), 200);
						
						//$this->dblog->AuditTrail('sendsummary', $dataSummary, 'mobile', $this->mobile_model->tokenProfile->userId);
					}
					else
					{
						$this->rest->response(array(
							'req' => 'sendsummary',
							'res' => 'not ok',
							'msg' => 'Summary save failed.'
						), 401);
					}
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	public function add_summary()
	{
		$this->mobile_model->startLogActivity('addSummary');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'acceptance_status', 'remark', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$dataSummary = array(); 
				$dataSummary['visit_id'] = $params->visit_id;
				$dataSummary['acceptance_status'] = $params->acceptance_status;
				$dataSummary['user_id'] = $this->mobile_model->tokenProfile->userId;
				$dataSummary['remark'] = $params->remark;

				$dataSummary['created_by'] = $this->mobile_model->tokenProfile->userId;
				$dataSummary['created_on'] = date('Y-m-d H:i:s');

				if ($this->mobile_model->insertSummary($dataSummary))
				{
					$this->load->model('visitsummary/summary_model');

					// if close approval, pengecekan status user approval terakhir di summary_model->si_last_approval
					// kemudian update table t_trx_schedule_result_approval kolom status dengan params acceptance_status
					$sql = "SELECT checklist_id FROM t_trx_schedule_checklist WHERE visit_id = ".$params->visit_id;
					$res = $this->db->query($sql)->result_array();


					$qty_approved = 0;
					$qty_rejected = 0;
					if ($params->acceptance_status == 1) {
						$qty_approved = count($res);
					}else{
						$qty_rejected = count($res);
					}

					$data = array(
						'visit_id' => $params->visit_id,
						'user_id' => $this->mobile_model->tokenProfile->userId,
						'status' => $params->acceptance_status,
						'qty_approved' => $qty_approved,
						'qty_rejected' => $qty_rejected,
						'remark' => $params->remark,
						'updated_by' => $this->mobile_model->tokenProfile->userId,
						'updated_on' => date('Y-m-d H:i:s')
					);

					$this->mobile_model->updateScheduleApproval($data, array('visit_id' => $params->visit_id, 'user_id' => $this->mobile_model->tokenProfile->userId));
					
					if ($this->summary_model->is_last_approval($this->mobile_model->tokenProfile->userId, $params->visit_id))
					{
						// Update PO Link status
						$this->mobile_model->updatePoLinkStatus(5, $params->visit_id);
					}

					// Send email
					$sql_fe_mail = $this->db
						->query("
							SELECT a.*,b.email AS email_fe
							FROM t_trx_schedule a
	            			LEFT JOIN t_mtr_user b ON b.user_id = a.pic_id
	            			WHERE a.visit_id = " . $params->visit_id . "
	            		")
            			->row();

        			$sql_apr_mail = $this->db
        				->query("
        					SELECT a.*,b.user_id AS aprovalnya, b.order_no, b.status AS approval_status, c.email AS email_aprrovalnya
        					FROM t_trx_schedule a
            				LEFT JOIN t_trx_schedule_result_approval b ON b.visit_id = a.visit_id
            				LEFT JOIN t_mtr_user c ON c.user_id = b.user_id
            				WHERE a.visit_id = " . $params->visit_id . "
            				ORDER BY b.order_no ASC
            			")
            			->result();

        			$schedule = $this->summary_model->getScheduleData($params->visit_id);
        			$schedule = array_merge($schedule, array('approved_by' => $this->mobile_model->tokenProfile->userId, 'rejected_by' => $this->mobile_model->tokenProfile->userId));

			        $mail_template = array();
					$mail_template['apr'] = $this->db->get_where('t_ref_mail_notif', array('template_name'=>'APPRSCHED', 'is_active'=>1))->row();
			        $mail_template['rej'] = $this->db->get_where('t_ref_mail_notif', array('template_name'=>'REJSCHED', 'is_active'=>1))->row();

			        $mail_content_apr = self::_injectData($schedule, $mail_template['apr']->parameter, $mail_template['apr']->content);
			        $mail_content_rej = self::_injectData($schedule, $mail_template['rej']->parameter, $mail_template['rej']->content);

					if ($params->acceptance_status == 1) // Approved
					{
						// ilham 14/10/2022
						$sql = "SELECT checklist_id FROM t_trx_schedule_checklist WHERE visit_id = ".$params->visit_id;
						$res = $this->db->query($sql)->result_array();
						$checklists = array_map (function($value){
							return intval($value['checklist_id']);
						} , $res);

						foreach ($checklists as $item) {
							$data_checklist = new stdClass;
							$data_checklist->visit_id = $params->visit_id;
							$data_checklist->checklist_id = $item['checklist_id'];
							$data_checklist->status = 1; /* Approve Answer Checklist */
							$data_checklist->created_on = date('Y-m-d H:i:s');
							$data_checklist->remark = $params->remark;
							$data_checklist->created_by = $this->mobile_model->tokenProfile->userId;
						}
						$this->mobile_model->saveApprovalAnswer($checklists, $data_checklist, true, $params->visit_id);
						// ilham 14/10/2022

						foreach ($sql_apr_mail as $rs_apr_yes)
						{
			                self::_pushNotifEmail(
			                    array(
			                        'subject'			=> $mail_template['apr']->subject,
			                        'content'			=> $mail_content_apr,
			                        'recipient_address'	=> $rs_apr_yes->email_aprrovalnya,
			                        'created_on'		=> date('Y-m-d H:i:s'),
			                        'created_by'		=> $this->mobile_model->tokenProfile->userId
			                    )
			                );
			            }
			            
			            self::_pushNotifEmail(
			                array(
			                    'subject'			=> $mail_template['apr']->subject,
			                    'content'			=> $mail_content_apr,
			                    'recipient_address'	=> $sql_fe_mail->email_fe,
			                    'created_on'		=> date('Y-m-d H:i:s'),
			                    'created_by'		=> $this->mobile_model->tokenProfile->userId
			                )
			            );
					}
					elseif ($params->acceptance_status == 2) // Rejected
					{
						// ilham 14/10/2022
						$sql = "SELECT checklist_id FROM t_trx_schedule_checklist WHERE visit_id = ".$params->visit_id;
						$res = $this->db->query($sql)->result_array();
						$checklists = array_map (function($value){
							return intval($value['checklist_id']);
						} , $res);

						foreach ($checklists as $item) {
							$data_checklist = new stdClass;
							$data_checklist->visit_id = $params->visit_id;
							$data_checklist->checklist_id = $item['checklist_id'];
							$data_checklist->status = 0; /* Approve Answer Checklist */
							$data_checklist->created_on = date('Y-m-d H:i:s');
							$data_checklist->remark = $params->remark;
							$data_checklist->created_by = $this->mobile_model->tokenProfile->userId;
						}
						$this->mobile_model->saveApprovalAnswer($checklists, $data_checklist, true, $params->visit_id);
						// ilham 14/10/2022

						self::_pushNotifEmail(
			                array(
			                    'subject'			=> $mail_template['rej']->subject,
			                    'content'			=> $mail_content_rej,
			                    'recipient_address'	=> $sql_fe_mail->email_fe,
			                    'created_on'		=> date('Y-m-d H:i:s'),
			                    'created_by'		=> $this->mobile_model->tokenProfile->userId
			                )
			            );
					}

					$this->rest->response(array(
						'req' => 'sendsummary',
						'res' => 'ok',
						'msg' => 'Summary saved successfully.'
					), 200);
					
					//$this->dblog->AuditTrail('sendsummary', $dataSummary, 'mobile', $this->mobile_model->tokenProfile->userId);
				}
				else
				{
					$this->rest->response(array(
						'req' => 'sendsummary',
						'res' => 'not ok',
						'msg' => 'Summary save failed.'
					), 401);
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	public function add_pending_item()
	{
		$this->mobile_model->startLogActivity('addPendingItem');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'visit_id', 'item_no', 'item_descr', 'target_completion', 'pic', 'contact_no', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$fixData = $dataReceived;
				
				$data = array( 
					'visit_id' => $fixData->visit_id,
					'item_no' => $fixData->item_no,
					'item_descr' => $fixData->item_descr,
					'target_completion' => $fixData->target_completion,
					'pic' => $fixData->pic,
					'contact_no' => $fixData->contact_no,
					'created_by' => $this->mobile_model->tokenProfile->userId,
					'created_on' => date('Y-m-d H:i:s'),
					'updated_by' => $this->mobile_model->tokenProfile->userId,
					'updated_on' => date('Y-m-d H:i:s')
				);
				
				$dataPending = $this->db
					->where('visit_id', $fixData->visit_id)
					->where('item_no', $fixData->item_no)
					->where('pic', $fixData->pic)
					->where('is_deleted !=', 1)
					->get('t_trx_schedule_pending_item')
					->row();

				if ($dataPending)
				{
					$arrWhere = array(
						'visit_id' => $fixData->visit_id,
						'item_no' => $fixData->item_no,
						'pic' => $fixData->pic,
						'is_deleted !=' => 1
					);

					if ($this->mobile_model->update_pending_item($data, $arrWhere))
					{
						$this->rest->response(array(
							'req' => 'sendpendingitem',
							'res' => 'ok',
							'msg' => 'Send pending item successfully.'
						), 200);
						
						//$this->dblog->AuditTrail('sendpendingitem', $data, "mobile", $this->mobile_model->tokenProfile->userId);
					}
					else
					{
						$this->rest->response(array(
							'req' => 'sendpendingitem',
							'res' => 'not ok',
							'msg' => 'Send pending item failed.'
						), 401);
					}
				}
				else
				{
					if ($this->mobile_model->insert_pending_item($data))
					{
						$this->rest->response(array(
							'req' => 'sendpendingitem',
							'res' => 'ok',
							'msg' => 'Send pending item successfully.'
						), 200);
						
						//$this->dblog->AuditTrail('sendpendingitem', $data, "mobile", $this->mobile_model->tokenProfile->userId);
					}
					else
					{
						$this->rest->response(array(
							'req' => 'sendpendingitem',
							'res' => 'not ok',
							'msg' => 'Send pending item failed.'
						), 401);
					}
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	public function add_sign_old()
	{
		$this->mobile_model->startLogActivity('addFopSignature');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'contact_no', 'sign_type', 'lat', 'lon', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$dataSign = $this->db
					->where('visit_id', $params->visit_id)
					->where('sign_type', $params->sign_type)
					->where('user_id', $this->mobile_model->tokenProfile->userId)
					->where('is_deleted !=', 1)
					->get('t_trx_schedule_sign')
					->row();

				if (count($_FILES) > 0)
				{
					if ($dataSign && file_exists(FCPATH.$dataSign->image_sign) && is_file(FCPATH.$dataSign->image_sign))
					{
						@unlink(FCPATH.$dataSign->image_sign);
					}

					// Upload photo
					$path = FCPATH.'file/upload/fop/'.date('Y/m/d');
					
					if ( ! is_dir($path))
					{
						mkdir($path, 0777, TRUE);
					}
					
					$config = array();
					$config['upload_path'] = $path;
					$config['allowed_types'] = 'jpg|gif|png';
					//$config['file_name'] = 'FOPSIGN_'.date('Ymd_His');
					$config['file_name'] = 'FOPSIGN_'.$this->mobile_model->tokenProfile->userId.'_visitId-'.$params->visit_id.'_'.date('Ymd_His');
					$config['overwrite'] = TRUE;

					$this->load->library('upload', $config);
					
					$photoPath = NULL;
					
					$upload_key = array_keys($_FILES);
					
					if ($this->upload->do_upload($upload_key[0]))
					{
						$data = array('upload_data' => $this->upload->data());
						$fileName = $data['upload_data']['file_name'];
						
						chmod($path.'/'.$fileName, 0777);
						
						$photoPath = 'file/upload/fop/'.date('Y/m/d').'/'.$fileName;
					}
					else
					{
						$this->rest->response(array(
							'req' => 'sendfopsign',
							'res' => 'not ok',
							'msg' => $this->upload->display_errors('', '')
						), 406);
						
						$this->mobile_model->endLogActivity();
						return;
					}
				}
				else
				{
					$this->rest->response(array(
						'req' => 'sendfopsign',
						'res' => 'not ok',
						'msg' => 'File not found.'
					), 400);
					
					$this->mobile_model->endLogActivity();
					return;
				}
				
				if ($dataSign)
				{
					$data = array( 
						'visit_id' => $params->visit_id
						, 'image_sign' => $photoPath
						, 'user_id' => $this->mobile_model->tokenProfile->userId
						, 'contact_no' => $params->contact_no
						, 'sign_type' => $params->sign_type
						, 'latitude' => $params->lat
						, 'longitude' => $params->lon
						, 'updated_by' => $this->mobile_model->tokenProfile->userId
						, 'updated_on' => date('Y-m-d H:i:s')
					);

					$arrWhere = array(
						'visit_id' => $params->visit_id,
						'sign_type' => $params->sign_type,
						'user_id' => $this->mobile_model->tokenProfile->userId,
						'is_deleted !=' => 1
					);

					if ($this->mobile_model->update_sign($data, $arrWhere))
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "ok",
							"msg" => "Send signature successfully.",
							"data" => array(
								'sent' => $params,
								'updated' => $data
							)
						), 200);
						
						//$this->dblog->AuditTrail('sendfopsign', $data, "mobile", $this->mobile_model->tokenProfile->userId);
					}
					else
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "not ok",
							"msg" => "Send signature failed."
						), 401);
					}
				}
				else
				{
					$data = array( 
						'visit_id' => $params->visit_id
						, 'image_sign' => $photoPath
						, 'user_id' => $this->mobile_model->tokenProfile->userId
						, 'contact_no' => $params->contact_no
						, 'sign_type' => $params->sign_type
						, 'latitude' => $params->lat
						, 'longitude' => $params->lon
						, 'created_by' => $this->mobile_model->tokenProfile->userId
						, 'created_on' => date('Y-m-d H:i:s')
					);

					if ($this->mobile_model->insert_sign($data))
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "ok",
							"msg" => "Send signature successfully.",
							"data" => array(
								'sent' => $params,
								'inserted' => $data
							)
						), 200);
						
						//$this->dblog->AuditTrail('sendfopsign', $data, "mobile", $this->mobile_model->tokenProfile->userId);
					}
					else
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "not ok",
							"msg" => "Send signature failed."
						), 401);
					}
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	public function add_sign2()
	{
		$this->mobile_model->startLogActivity('addFopSignature');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'contact_no', 'sign_type', 'lat', 'lon', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$dataSign = $this->db
					->where('visit_id', $params->visit_id)
					->where('sign_type', $params->sign_type)
					->where('user_id', $this->mobile_model->tokenProfile->userId)
					->where('is_deleted !=', 1)
					->get('t_trx_schedule_sign')
					->row();

				if (count($_FILES) > 0)
				{
					// Upload photo
					$path = 'file/upload/fop/'.date('Y/m/d');
					
					if ( ! is_dir(FCPATH.$path))
					{
						mkdir(FCPATH.$path, 0777, TRUE);
					}
					
					$allowed = array(
						'.jpg',
						'.jpeg',
						'.gif',
						'.png'
					);
					
					$photoPath = NULL;
					
					$uploadKey = array_keys($_FILES);
					
					if ( ! empty($_FILES[$uploadKey[0]]['name']) && $_FILES[$uploadKey[0]]['error'] == UPLOAD_ERR_OK)
					{
						$tmpName = $_FILES[$uploadKey[0]]['tmp_name'];
						$ext = strtolower(strrchr($_FILES[$uploadKey[0]]['name'], '.'));
						$fileName = 'FOPSIGN_'.$this->mobile_model->tokenProfile->userId.'_'.date('Ymd_His').$ext;

						if ( ! in_array($ext, $allowed))
						{
							$this->rest->response(array(
								'req' => 'sendfopsign',
								'res' => 'not ok',
								'msg' => 'Invalid file type. Please upload file extension .jpg, .gif, .png only.'
							), 406);

							$this->mobile_model->endLogActivity();
							return;
						}
						else
						{
							if (is_safe_upload_name($fileName) && @move_uploaded_file($tmpName, FCPATH.$path.'/'.$fileName))
							{
								$fp = fopen(FCPATH.$path.'/'.$fileName.'_'.time().'_bak', 'w');
								fwrite($fp, file_get_contents($tmpName));
								fclose($fp);

								if ($dataSign && file_exists(FCPATH.$dataSign->image_sign) && is_file(FCPATH.$dataSign->image_sign))
								{
									@unlink(FCPATH.$dataSign->image_sign);
								}

								chmod(FCPATH.$path.'/'.$fileName, 0777);
						
								$photoPath = $path.'/'.$fileName;
							}
						}
					}
				}
				else
				{
					$this->rest->response(array(
						'req' => 'sendfopsign',
						'res' => 'not ok',
						'msg' => 'File not found.'
					), 400);
					
					$this->mobile_model->endLogActivity();
					return;
				}
				
				$data = array( 
					'visit_id' => $params->visit_id
					, 'image_sign' => $photoPath
					, 'user_id' => $this->mobile_model->tokenProfile->userId
					, 'contact_no' => $params->contact_no
					, 'sign_type' => $params->sign_type
					, 'latitude' => $params->lat
					, 'longitude' => $params->lon
					, 'created_by' => $this->mobile_model->tokenProfile->userId
					, 'created_on' => date('Y-m-d H:i:s')
					, 'updated_by' => $this->mobile_model->tokenProfile->userId
					, 'updated_on' => date('Y-m-d H:i:s')
				);

				if ($dataSign)
				{
					$arrWhere = array(
						'visit_id' => $params->visit_id,
						'sign_type' => $params->sign_type,
						'user_id' => $this->mobile_model->tokenProfile->userId,
						'is_deleted !=' => 1
					);

					if ($this->mobile_model->update_sign($data, $arrWhere))
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "ok",
							"msg" => "Send signature successfully.",
							"data" => array(
								'sent' => $params,
								'updated' => $data
							)
						), 200);
						
						//$this->dblog->AuditTrail('sendfopsign', $data, "mobile", $this->mobile_model->tokenProfile->userId);
					}
					else
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "not ok",
							"msg" => "Send signature failed."
						), 401);
					}
				}
				else
				{
					if ($this->mobile_model->insert_sign($data))
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "ok",
							"msg" => "Send signature successfully.",
							"data" => array(
								'sent' => $params,
								'inserted' => $data
							)
						), 200);
						
						//$this->dblog->AuditTrail('sendfopsign', $data, "mobile", $this->mobile_model->tokenProfile->userId);
					}
					else
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "not ok",
							"msg" => "Send signature failed."
						), 401);
					}
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	public function add_sign()
	{
		$this->mobile_model->startLogActivity('addFopSignature');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'contact_no', 'sign_type', 'lat', 'lon', 'image_sign', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$dataSign = $this->db
					->where('visit_id', $params->visit_id)
					->where('sign_type', $params->sign_type)
					->where('user_id', $this->mobile_model->tokenProfile->userId)
					->where('is_deleted !=', 1)
					->get('t_trx_schedule_sign')
					->row();

				if ( ! empty($params->image_sign))
				{
					$path = 'file/upload/fop/'.date('Y/m/d');

					if ( ! is_dir(FCPATH.$path))
					{
						mkdir(FCPATH.$path, 0777, TRUE);
					}

					$imgData = base64_decode($params->image_sign);
					$f = finfo_open();
					$mimeType = finfo_buffer($f, $imgData, FILEINFO_MIME_TYPE);
					
					$exts = array(
						'image/jpeg' => '.jpg',
						'image/png' => '.png',
						'image/gif' => '.gif'
					);
					
					$extAllowed = array(
						'.jpg',
						'.jpeg',
						'.gif',
						'.png'
					);

					if ( ! in_array($exts[$mimeType], $extAllowed))
					{
						$this->rest->response(array(
							"req" => "saveChecklist",
							"res" => "NOT_OK",
							"msg" => "Invalid file type. Please upload file extension .jpg, .gif, .png only."
						), 401);

						$this->mobile_model->endLogActivity();
						return;
					}
					else
					{
						if ($dataSign && file_exists(FCPATH.$dataSign->image_sign) && is_file(FCPATH.$dataSign->image_sign))
						{
							@unlink(FCPATH.$dataSign->image_sign);
						}

						$fileName = 'FOPSIGN_'.$this->mobile_model->tokenProfile->userId.'_visitId-'.$params->visit_id.'_'.date('Ymd_His').$exts[$mimeType];
						
						file_put_contents(FCPATH.$path.'/'.$fileName, $imgData);

						chmod(FCPATH.$path.'/'.$fileName, 0777);
						
						$photoPath = $path.'/'.$fileName;
						try
						{
							// file_put_contents(FCPATH.$path.'/'.$fileName, $imgData);

							# Start Upload to Nas :: 17/06/2022
							$headers = array("Content-Type:multipart/form-data");
							$curl = curl_init();
							curl_setopt_array($curl, array(
								CURLOPT_URL => 'http://10.0.5.85:5050/upload.php',
								CURLOPT_HEADER => true,
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_HTTPHEADER => $headers,
								CURLOPT_ENCODING => "",
								CURLOPT_TIMEOUT => 30,
								CURLOPT_POST => 1,
								CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
								CURLOPT_CUSTOMREQUEST => "POST",
								CURLOPT_POSTFIELDS => [
									'path' 	=> 'file/upload/fop/'.date('Y/m/d').'/',
									'image' => 'data:'.$mimeType.';base64,'.$params->image_sign,
									'image_name' => $fileName
								],
							));
							$response = curl_exec($curl);
							
							if (is_dir('E:/EATP/Foto'))
							{
								file_put_contents('E:/EATP/Foto/'.$imsPath.'/'.$fileName, $imgData); // IMS

								chmod('E:/EATP/Foto/'.$imsPath.'/'.$fileName, 0777);
							}

							chmod(FCPATH.$path.'/'.$fileName, 0777);
						}
						catch(Exception $e)
						{
							$this->rest->response(array(
								"req" => "saveChecklist",
								"res" => "NOT_OK",
								"msg" => "Occurred error when write file into the disk. Trace: ".$e->getTraceAsString()
							), 401);

							//$this->mobile_model->endLogActivity();
							return;
						}
						
						$photoPath = $path.'/'.$fileName;
					}
				}
				else
				{
					$this->rest->response(array(
						'req' => 'sendfopsign',
						'res' => 'not ok',
						'msg' => 'Image FOP sign not found.'
					), 400);
					
					$this->mobile_model->endLogActivity();
					return;
				}
				
				if ($dataSign)
				{
					$data = array( 
						'visit_id' => $params->visit_id
						, 'image_sign' => $photoPath
						, 'user_id' => $this->mobile_model->tokenProfile->userId
						, 'contact_no' => $params->contact_no
						, 'sign_type' => $params->sign_type
						, 'latitude' => $params->lat
						, 'longitude' => $params->lon
						, 'updated_by' => $this->mobile_model->tokenProfile->userId
						, 'updated_on' => date('Y-m-d H:i:s')
					);

					$arrWhere = array(
						'visit_id' => $params->visit_id,
						'sign_type' => $params->sign_type,
						'user_id' => $this->mobile_model->tokenProfile->userId,
						'is_deleted !=' => 1
					);

					if ($this->mobile_model->update_sign($data, $arrWhere))
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "ok",
							"msg" => "Send signature successfully.",
							//"data" => array(
							//	'sent' => $params,
							//	'updated' => $data
							//)
						), 200);
						//start edited by Hadi 2018-03-13
						$this->mobile_model->updateLogOutFOP($this->mobile_model->tokenProfile->userId);
						//end
						//$this->dblog->AuditTrail('sendfopsign', $data, "mobile", $this->mobile_model->tokenProfile->userId);
					}
					else
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "not ok",
							"msg" => "Send signature failed."
						), 401);
					}
				}
				else
				{
					$data = array( 
						'visit_id' => $params->visit_id
						, 'image_sign' => $photoPath
						, 'user_id' => $this->mobile_model->tokenProfile->userId
						, 'contact_no' => $params->contact_no
						, 'sign_type' => $params->sign_type
						, 'latitude' => $params->lat
						, 'longitude' => $params->lon
						, 'created_by' => $this->mobile_model->tokenProfile->userId
						, 'created_on' => date('Y-m-d H:i:s')
					);

					if ($this->mobile_model->insert_sign($data))
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "ok",
							"msg" => "Send signature successfully.",
							//"data" => array(
							//	'sent' => $params,
							//	'inserted' => $data
							//)
						), 200);
						//start edited by Hadi 2018-03-13
						$this->mobile_model->updateLogOutFOP($this->mobile_model->tokenProfile->userId);
						//end
						//$this->dblog->AuditTrail('sendfopsign', $data, "mobile", $this->mobile_model->tokenProfile->userId);
					}
					else
					{
						$this->rest->response(array(
							"req" => "sendfopsign",
							"res" => "not ok",
							"msg" => "Send signature failed."
						), 401);
					}
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	public function add_fop_photo_old()
	{
		$this->mobile_model->startLogActivity('addFopPhoto');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'lat', 'lon', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				if (count($_FILES) > 0)
				{
					// Upload photo
					$path = FCPATH.'file/upload/fop/'.date('Y/m/d');
					
					if ( ! is_dir($path))
					{
						mkdir($path, 0777, TRUE);
					}
					
					$config['upload_path'] = $path;
					$config['allowed_types'] = 'jpg|gif|png';
					$config['file_name'] = 'FOP_'.date('Ymd_His');
					$config['overwrite'] = TRUE;

					$this->load->library('upload', $config);
					
					$photoPath = NULL;
					
					foreach ($_FILES as $key => $value)
					{
						if ( ! $this->upload->do_upload($key))
						{
							$this->rest->response(array(
								'req' => 'sendfopphoto',
								'res' => 'not ok',
								'msg' => $this->upload->display_errors('', '')
							), 406);
							
							$this->mobile_model->endLogActivity();
							return;
						}
						else
						{
							$data = array('upload_data' => $this->upload->data());
							$fileName = $data['upload_data']['file_name'];
							
							chmod($path.'/'.$fileName, 0777);
							
							$photoPath = '/file/upload/fop/'.date('Y/m/d').'/'.$fileName;
						}
					}
				}
				else
				{
					$this->rest->response(array(
						'req' => 'sendfopphoto',
						'res' => 'not ok',
						'msg' => 'File not found.'
					), 400);
					
					$this->mobile_model->endLogActivity();
					return;
				}
				
				$fixData = $dataReceived;
				
				$inserted_data = array(
					'visit_id' => $fixData->visit_id,
					'fop_photo' => $photoPath,
					'latitude' => $fixData->lat,
					'longitude' => $fixData->lon,
					'created_by' => $this->mobile_model->tokenProfile->userId,
					'created_on' => date('Y-m-d H:i:s'),
					'updated_by' => $this->mobile_model->tokenProfile->userId,
					'updated_on' => date('Y-m-d H:i:s')
				);

				if ($this->mobile_model->insert_fop_photo($inserted_data))
				{
					$this->rest->response(array(
						"req" => "sendfopphoto",
						"res" => "ok"
					), 200);
					
					//$this->dblog->AuditTrail('sendfopphoto', $inserted_data, "mobile", $this->mobile_model->tokenProfile->userId);
				}
				else
				{
					$this->rest->response(array(
						"req" => "sendfopphoto",
						"res" => "not ok"
					), 401);
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	public function add_fop_photo()
	{
		$this->mobile_model->startLogActivity('addFopPhoto');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('visit_id', 'lat', 'lon', 'image_fop', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$photoPath = NULL;

				if ( ! empty($params->image_fop))
				{
					$path = 'file/upload/fop/'.date('Y/m/d');
					
					if ( ! is_dir(FCPATH.$path))
					{
						mkdir(FCPATH.$path, 0777, TRUE);
					}

					$imgData = base64_decode($params->image_fop);
					$f = finfo_open();
					$mimeType = finfo_buffer($f, $imgData, FILEINFO_MIME_TYPE);
					
					$exts = array(
						'image/jpeg' => '.jpg',
						'image/png' => '.png',
						'image/gif' => '.gif'
					);
					
					$extAllowed = array(
						'.jpg',
						'.jpeg',
						'.gif',
						'.png'
					);

					if ( ! in_array($exts[$mimeType], $extAllowed))
					{
						$this->rest->response(array(
							"req" => "sendfopphoto",
							"res" => "not ok",
							"msg" => "Invalid file type. Please upload file extension .jpg, .gif, .png only."
						), 401);

						$this->mobile_model->endLogActivity();
						return;
					}
					else
					{
						$fileName = 'FOP_'.$this->mobile_model->tokenProfile->userId.'_visitId-'.$params->visit_id.'_'.date('Ymd_His').$exts[$mimeType];
						
						file_put_contents(FCPATH.$path.'/'.$fileName, $imgData);
						
						chmod(FCPATH.$path.'/'.$fileName, 0777);
						
						$photoPath = $path.'/'.$fileName;
						try
						{
							// file_put_contents(FCPATH.$path.'/'.$fileName, $imgData);

							# Start Upload to Nas :: 17/06/2022
							$headers = array("Content-Type:multipart/form-data");
							$curl = curl_init();
							curl_setopt_array($curl, array(
								CURLOPT_URL => 'http://10.0.5.85:5050/upload.php',
								CURLOPT_HEADER => true,
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_HTTPHEADER => $headers,
								CURLOPT_ENCODING => "",
								CURLOPT_TIMEOUT => 30,
								CURLOPT_POST => 1,
								CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
								CURLOPT_CUSTOMREQUEST => "POST",
								CURLOPT_POSTFIELDS => [
									'path' 	=> 'file/upload/fop/'.date('Y/m/d').'/',
									'image' => 'data:'.$mimeType.';base64,'.$params->image_fop,
									'image_name' => $fileName
								],
							));
							$response = curl_exec($curl);
							
							if (is_dir('E:/EATP/Foto'))
							{
								file_put_contents('E:/EATP/Foto/'.$imsPath.'/'.$fileName, $imgData); // IMS

								chmod('E:/EATP/Foto/'.$imsPath.'/'.$fileName, 0777);
							}

							chmod(FCPATH.$path.'/'.$fileName, 0777);
						}
						catch(Exception $e)
						{
							$this->rest->response(array(
								"req" => "saveChecklist",
								"res" => "NOT_OK",
								"msg" => "Occurred error when write file into the disk. Trace: ".$e->getTraceAsString()
							), 401);

							//$this->mobile_model->endLogActivity();
							return;
						}
						
						$photoPath = $path.'/'.$fileName;




					}
				}
				else
				{
					$this->rest->response(array(
						'req' => 'sendfopphoto',
						'res' => 'not ok',
						'msg' => 'File not found.'
					), 400);
					
					$this->mobile_model->endLogActivity();
					return;
				}

				$inserted_data = array(
					'visit_id' => $params->visit_id,
					'fop_photo' => $photoPath,
					'latitude' => $params->lat,
					'longitude' => $params->lon,
					//'created_by' => $this->mobile_model->tokenProfile->userId,
					//'created_on' => date('Y-m-d H:i:s'),
					'updated_by' => $this->mobile_model->tokenProfile->userId,
					'updated_on' => date('Y-m-d H:i:s')
				);

				if ($this->mobile_model->insert_fop_photo($inserted_data))
				{
					$this->rest->response(array(
						"req" => "sendfopphoto",
						"res" => "ok",
						"msg" => "Send FOP photo successfully."
					), 200);
					
					//$this->dblog->AuditTrail('sendfopphoto', $inserted_data, "mobile", $this->mobile_model->tokenProfile->userId);
				}
				else
				{
					$this->rest->response(array(
						"req" => "sendfopphoto",
						"res" => "not ok",
						"msg" => "Send FOP photo failed."
					), 401);
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}
	
	public function updateQuatityActual()
	{
		$this->mobile_model->startLogActivity('updateQuantityActual');
		
		$data = $this->input->get_post('data');
		
		$dataReceived = json_decode($data);
		$paramKeys = array('bomId', 'qtyActual', 'token');
		$params = array();
		
		if ($this->rest->dispatchParam($paramKeys, $dataReceived, $params))
		{
			if ($this->mobile_model->tokenValidator($dataReceived->token))
			{
				$data = array(
					'qty_actual' => (int) $dataReceived->qtyActual,
					'updated_by' => $this->mobile_model->tokenProfile->userId,
					'updated_on' => date('Y-m-d H:i:s')
				);
				
				if ($this->mobile_model->updateQuatityActual($data, $dataReceived->bomId))
				{
					$this->rest->response(array(
						"req" => "updateQuantityActual",
						"res" => "ok",
						"msg" => "Update quantity actual successfully."
					), 200);
				}
				else
				{
					$this->rest->response(array(
						"req" => "updateQuantityActual",
						"res" => "not ok",
						"msg" => "Update quantity actual failed."
					), 401);
				}
			}
		}
		
		$this->mobile_model->endLogActivity();
	}

	private function _pushNotifEmail($data = array())
	{
		$header = array(
			'email_type_id'				=> 1,
			'email_date'				=> date('Y-m-d'),
			'email_reply_to_address'	=> 'eatp-alita@alita.id',
			'email_subject'				=> $data['subject'],
			'email_content'				=> $data['content'],
			'foreign_id'				=> 1,
			'created_by'				=> $data['created_by'],
			'created_on'				=> $data['created_on']
		);

		$this->db->insert('t_msg_email_header', $header);
		$header_id = $this->db->insert_id();

		$detail = array(
			'email_id'			=> $header_id,
			'recipient_type'	=> 'to',
			'recipient_address'	=> $data['recipient_address'],
			'created_by'		=> $data['created_by'],
			'created_on'		=> $data['created_on']
		);
		
		$this->db->insert('t_msg_email_detail', $detail);
	}

	private function _injectData($data = array(), $params = '', $template = '')
	{
		$param = str_replace(array("[", "]"), "", $params);
        $param = explode(",", $param);
        $params = explode(",", $params);

        $values = array();
        foreach ($param as $x => $value)
        {
        	if (array_key_exists($value,$data))
        	{
            	$values[$x] = $data[$value];
            }
            else
            {
            	$value[$x] = "-";
            }
        }

        return str_replace($params, $values, $template);
    }

}
