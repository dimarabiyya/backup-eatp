<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobile_model extends CI_Model {

	public $tokenProfile;
	
	private $logActivity;
	
	public function __construct()
    {
        parent::__construct();
    }
  
	public function check_user_login($user_id, $pass, $user_group_id, $is_login)
	{
		$this->db->select('*');
		$this->db->from('t_mtr_user');
		$this->db->where('t_mtr_user.user_id', $user_id);
		$this->db->where('t_mtr_user.user_password', $pass);
		$this->db->where('t_mtr_user.is_login', $is_login);
		$this->db->join('t_mtr_user_territory', 't_mtr_user.user_id = t_mtr_user_territory.user_id');

		$query = $this->db->get();

		return $query->result_array();
	}
   
	public function checkUser($user_id)
	{
		$this->db->select('*');
		$this->db->from('t_mtr_user');
		$this->db->where('t_mtr_user.user_id', $user_id);
		$this->db->where('t_mtr_user.status', 1);

		$query = $this->db->get();

		return $query->result_array();
	}
   
	public function check_user_internal($user_id)
	{
		$this->db->select('*');
		$this->db->from('t_mtr_user');
		$this->db->where('t_mtr_user.user_id', $user_id);
		$this->db->where('t_mtr_user.internal_user', 1);
		$this->db->where('t_mtr_user.status', 1);

		$query = $this->db->get();

		return $query->result_array();
	}
   
	public function checkPassword($user_id, $pass)
	{
		$this->db->select('*');
		$this->db->from('t_mtr_user');
		$this->db->where('t_mtr_user.user_id', $user_id);
		$this->db->where('t_mtr_user.user_password', $pass);
		$this->db->where('status', 1);

		$query = $this->db->get();

		return $query->row_array();
	}
   
	public function check_user_is_login($user_id)
	{
		$this->db->select('*');
		$this->db->from('t_mtr_user');
		$this->db->where('user_id', $user_id);
		$this->db->where('status', 1);
		$this->db->where('is_login', 1);

		$query = $this->db->get();

		return $query->result_array();
	}
   
	public function checkMobileUser($user_id, $passs)
	{
		$this->db->select('*');
		$this->db->from('t_mtr_user');
		$this->db->where('user_id', $user_id);
		$this->db->where('is_mobile_user', 1);
		$this->db->where('status', 1);

		$query = $this->db->get();

		return $query->result_array();
	}
   
	public function mobileUserLoggedin($user_id)
	{
		$this->db->select('mus.user_name, mus.user_group_id, grp.user_group_name, terr.region_id, reg.region_name, mus.user_id, mus.is_mobile_user, mus.is_login, umm.mobile_app_type, mus.mobile_number, mus.image_signature', false);
		$this->db->from('t_mtr_user mus');
		$this->db->join('t_mtr_user_multi_role mrole', 'mrole.user_id = mus.user_id');
		$this->db->join('t_mtr_user_group grp', 'grp.user_group_id = mrole.user_group_id');
		$this->db->join('t_mtr_user_territory terr','terr.user_id = mus.user_id');
		$this->db->join('t_mtr_user_multi_mobile umm','umm.user_id = mus.user_id');
		$this->db->join('t_mtr_region_xl reg', 'reg.region_id = terr.region_id', 'left');
		$this->db->where('mus.user_id', $user_id);
		$this->db->where('mus.is_mobile_user', 1);
		$this->db->where('mus.status', 1);
		$this->db->where('umm.mobile_app_type', 1);
		$this->db->where('umm.active', 1);
		$this->db->where('reg.is_deleted !=', 1);
	  
		return $this->db
			->get()
			->row();
	}

	public function checkUserHandset($user_id) // take out ($imsi, $mdn ) by iwa 10 mar 2016, take out, $imei by iwa 22 nov 2016
	{
		$this->db->select('*');
		$this->db->from('t_mtr_handset');
		$this->db->where('t_mtr_handset.user_id', $user_id);
		$this->db->where('t_mtr_handset.istatus', 1);
		
		$query = $this->db->get();

		return $query->result_array();
	}
    
    public function check_user_logout($user_id, $mobile_token)
	{
		$this->db->select('*');
		$this->db->from('t_mtr_user');
		$this->db->where('user_id', $user_id);
		
		$query = $this->db->get();

		return $query->result_array();
	}

	public function update_user_logout_status($user_id, $mobile_token)
	{
		$this->db->trans_begin();

		$this->updateData('t_mtr_user', $data, array('user_id', $user_id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}

    public function updateUserLoginStatus($data, $userId)
	{
		$this->db->trans_begin();
		
		$this->updateData('t_mtr_user', $data, array('user_id' => $userId, 'status' => 1));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
    }

	public function selectUserAndJourneyCycleOld($user_id, $date)
	{
		$this->db->select(  't_trx_pm_schedule.site_id
                , t_mtr_site.site_code
              , t_mtr_site.site_name
              , t_mtr_user.user_name
              , t_mtr_site.latitude
              , t_mtr_site.longitude
              , t_trx_pm_schedule.visit_id
              , t_mtr_site.site_address
              ,t_trx_pm_schedule.visit_date
              ,t_trx_pm_schedule.visit_type
              ,t_trx_pm_schedule.status
              ,t_trx_pm_schedule.remark
              ,t_trx_pm_schedule.priority
              ,t_trx_pm_schedule.security_code_checkin
              ,t_trx_pm_schedule.security_code_checkout
              ,t_trx_pm_schedule.visit_week
              ,t_trx_pm_schedule.last_visited_date
              ');
		$this->db->from('t_trx_pm_schedule');
		$this->db->where('t_trx_pm_schedule.user_id', $user_id);
		// $this->db->where('t_trx_pm_schedule.visit_week', $date);
		$this->db->where_in('t_trx_pm_schedule.status', array(0,1,4,11));
		$this->db->join('t_mtr_site','t_trx_pm_schedule.site_id = t_mtr_site.site_id');
		$this->db->join('t_mtr_user','t_trx_pm_schedule.user_id = t_mtr_user.user_id');

		$query = $this->db->get();

		return $query->result_array(); 
	}

    public function selectUserAndJourneyCycle($userId)
	{
		$query = "
			SELECT DISTINCT
				sched.link_id AS link_id_int
				, link.link_id 
				, sitene.site_id AS ne_site_id
				, sitene.site_name AS ne_site_name
				, sitene.latitude AS ne_latitude
				, sitene.longitude AS ne_longitude
				, sitene.site_address AS ne_site_address
				, sitefe.site_id AS fe_site_id
				, sitefe.site_name AS fe_site_name
				, sitefe.latitude AS fe_latitude
				, sitefe.longitude AS fe_longitude
				, sitefe.site_address AS fe_site_address
				, us.user_name
				, sched.visit_id
				, sched.visit_date
				, sched.visit_type
				, sch_opt.option_label AS visit_type_desc
				, sched.status
				, CASE
					WHEN sched.status = 0 THEN 'Unvisited'
					WHEN sched.status = 1 THEN 'Confirmed'
					WHEN sched.status = 2 THEN 'Declined'
					WHEN sched.status = 3 THEN 'Visited'
				END AS status_desc
				, sched.remark
				, sched.priority
				, sched.security_code_checkin
				, sched.security_code_checkout
				, sched.visit_week
				, sched.last_visited_date
				, po_link.progress_status
				, po_link.order_type
				, CASE
					WHEN po_link.order_type = 1 THEN 'NBWO'
					WHEN po_link.order_type = 2 THEN 'EWO'
				END AS order_type_desc
			FROM
				t_trx_schedule sched
			JOIN t_mtr_site_link link ON sched.link_id = link.link_id_int AND link.is_deleted != 1
			JOIN t_mtr_site_xl sitene ON sitene.site_id = link.site_id_ne AND sitene.is_deleted != 1
			JOIN t_mtr_site_xl sitefe ON sitefe.site_id = link.site_id_fe AND sitefe.is_deleted != 1
			JOIN t_mtr_user us ON us.user_id = sched.pic_id AND us.status = 1
			LEFT JOIN t_mtr_checklist_options sch_opt ON sch_opt.option_id :: VARCHAR = sched.visit_type
			AND sch_opt.option_key = 'pm_visit_type'
			AND sch_opt.status = 1
			JOIN t_trx_po_link po_link ON po_link.link_id_int = link.link_id_int
			WHERE
				sched.pic_id = '{$userId}'
			AND sched.status IN (0, 1, 3, 4, 11)
			AND sched.is_deleted != 1;
		";
	
		return $this->db
			->query($query)
			->result_array();
	}
	
	public function selectUserAndJourneyCyclePerVisit($user_id, $visit_id)
	{
		$query = "
			SELECT
				sched.link_id AS link_id_int
				, link.link_id 
				, sitene.site_id AS ne_site_id
				, sitene.site_name AS ne_site_name
				, sitene.latitude AS ne_latitude
				, sitene.longitude AS ne_longitude
				, sitene.site_address AS ne_site_address
				, sitefe.site_id AS fe_site_id
				, sitefe.site_name AS fe_site_name
				, sitefe.latitude AS fe_latitude
				, sitefe.longitude AS fe_longitude
				, sitefe.site_address AS fe_site_address
				, us.user_name
				, sched.visit_id
				, sched.visit_date
				, sched.visit_type
				, sch_opt.option_label AS visit_type_desc
				, sched.status
				, CASE
					WHEN sched.status = 0 THEN 'Unvisited'
					WHEN sched.status = 1 THEN 'Confirmed'
					WHEN sched.status = 2 THEN 'Declined'
					WHEN sched.status = 3 THEN 'Visited'
				END AS status_desc
				, sched.remark
				, sched.priority
				, sched.security_code_checkin
				, sched.security_code_checkout
				, sched.visit_week
				, sched.last_visited_date
			FROM
				t_trx_schedule sched
			JOIN t_mtr_site_link link ON sched.link_id = link.link_id_int AND link.is_deleted != 1
			JOIN t_mtr_site_xl sitene ON sitene.site_id = link.site_id_ne AND sitene.is_deleted != 1
			JOIN t_mtr_site_xl sitefe ON sitefe.site_id = link.site_id_fe AND sitefe.is_deleted != 1
			JOIN t_mtr_user us ON us.user_id = sched.pic_id AND us.status = 1
			LEFT JOIN t_mtr_checklist_options sch_opt ON sch_opt.option_value = sched.visit_type :: VARCHAR
			AND sch_opt.option_key = 'pm_visit_type'
			AND sch_opt.status = 1
			WHERE
				sched.pic_id = '".$user_id."'
			AND sched.visit_id = ".$visit_id."
			AND sched.status IN (0, 1, 3, 4, 11)
			AND sched.is_deleted != 1;
		";
	
		return $this->db
			->query($query)
			->result_array();
	}

	public function selectLinkBom($visit_id)
	{
		$query = "
			SELECT
				sched.visit_id,
				bom_id,
				bom.po_id,
				code,
				material_description,
				qty_po,
				qty_actual,
				uom
			FROM
				t_trx_schedule sched
			LEFT JOIN t_trx_bom bom on bom.link_id_int = sched.link_id AND bom.is_deleted != 1
			WHERE
				sched.visit_id = ".$visit_id."
			AND sched.is_deleted != 1;
		";

		return $this->db->query($query)->result_array();
	}

    public function selectUserAndJourneyCycleUnscheduleAvailable($site_id, $user_id, $visit_date, $visit_week)
	{
		$this->db->select('
			t_trx_pm_schedule.site_id
			, t_mtr_site.site_code
			, t_mtr_site.site_name
			, t_mtr_user.user_name
			, t_mtr_site.latitude
			, t_mtr_site.longitude
			, t_trx_pm_schedule.visit_id
			, t_mtr_site.site_address
			, t_trx_pm_schedule.visit_date
			, t_trx_pm_schedule.visit_type
			, t_trx_pm_schedule.status
			, t_trx_pm_schedule.remark
			, t_trx_pm_schedule.priority
			, t_trx_pm_schedule.security_code_checkin
			, t_trx_pm_schedule.security_code_checkout
			, t_trx_pm_schedule.visit_week
			, t_trx_pm_schedule.last_visited_date
		');
		
		$this->db->from('t_trx_pm_schedule');
		$this->db->where('t_trx_pm_schedule.user_id', $user_id);
		$this->db->where('t_trx_pm_schedule.visit_date', $visit_date);
		$this->db->where('t_trx_pm_schedule.visit_week', $visit_week);
		$this->db->where('t_trx_pm_schedule.site_id', $site_id);
		$this->db->where_in('t_trx_pm_schedule.status', array(0,4));
		$this->db->join('t_mtr_site','t_trx_pm_schedule.site_id = t_mtr_site.site_id');
		$this->db->join('t_mtr_user','t_trx_pm_schedule.user_id = t_mtr_user.user_id');
		
		$query = $this->db->get();

		return $query->result_array();
	}

    public function selectUserAndJourneyCycleUnscheduleAvailable2($site_code, $user_id, $visit_date, $visit_week)
	{
		$this->db->select('
			t_trx_pm_schedule.site_id
			, t_mtr_site.site_code
			, t_mtr_site.site_name
			, t_mtr_user.user_name
			, t_mtr_site.latitude
			, t_mtr_site.longitude
			, t_trx_pm_schedule.visit_id
			, t_mtr_site.site_address
			, t_trx_pm_schedule.visit_date
			, t_trx_pm_schedule.visit_type
			, t_trx_pm_schedule.status
			, t_trx_pm_schedule.remark
			, t_trx_pm_schedule.priority
			, t_trx_pm_schedule.security_code_checkin
			, t_trx_pm_schedule.security_code_checkout
			, t_trx_pm_schedule.visit_week
			, t_trx_pm_schedule.last_visited_date
		');
		
		$this->db->from('t_trx_pm_schedule');
		$this->db->where('t_trx_pm_schedule.user_id', $user_id);
		$this->db->where('t_trx_pm_schedule.visit_date', $visit_date);
		$this->db->where('t_trx_pm_schedule.visit_week', $visit_week);
		$this->db->where_in('t_trx_pm_schedule.status', array(0,4));
		$this->db->join('t_mtr_site',"t_trx_pm_schedule.site_id = t_mtr_site.site_id AND t_mtr_site.site_code = '$site_code'");
		$this->db->join('t_mtr_user','t_trx_pm_schedule.user_id = t_mtr_user.user_id');
      
		$query = $this->db->get();
		
		return $query->result_array();
	}

    public function selectUserAndJourneyCycleUnscheduleAvailableNext($site_id, $user_id, $visit_date, $visit_week)
	{
		$this->db->select('
			t_trx_pm_schedule.site_id
			, t_mtr_site.site_code
			, t_mtr_site.site_name
			, t_mtr_user.user_name
			, t_mtr_site.latitude
			, t_mtr_site.longitude
			, t_trx_pm_schedule.visit_id
			, t_mtr_site.site_address
			, t_trx_pm_schedule.visit_date
			, t_trx_pm_schedule.visit_type
			, t_trx_pm_schedule.status
			, t_trx_pm_schedule.remark
			, t_trx_pm_schedule.priority
			, t_trx_pm_schedule.security_code_checkin
			, t_trx_pm_schedule.security_code_checkout
			, t_trx_pm_schedule.visit_week
			, t_trx_pm_schedule.last_visited_date
		');
		
		$this->db->from('t_trx_pm_schedule');
		$this->db->where('t_trx_pm_schedule.user_id', $user_id);
		$this->db->where('t_trx_pm_schedule.visit_date', $visit_date);
		$this->db->where('t_trx_pm_schedule.visit_week', $visit_week);
		$this->db->where('t_trx_pm_schedule.site_id', $site_id);
		$this->db->where('t_trx_pm_schedule.status', 3);
		$this->db->join('t_mtr_site','t_trx_pm_schedule.site_id = t_mtr_site.site_id');
		$this->db->join('t_mtr_user','t_trx_pm_schedule.user_id = t_mtr_user.user_id');
		
		$query = $this->db->get();
		
		return $query->result_array();
	}

    public function selectUserAndJourneyCycleUnscheduleAvailableNext2($site_code, $user_id, $visit_date, $visit_week)
	{
		$this->db->select('
			t_trx_pm_schedule.site_id
			, t_mtr_site.site_code
			, t_mtr_site.site_name
			, t_mtr_user.user_name
			, t_mtr_site.latitude
			, t_mtr_site.longitude
			, t_trx_pm_schedule.visit_id
			, t_mtr_site.site_address
			, t_trx_pm_schedule.visit_date
			, t_trx_pm_schedule.visit_type
			, t_trx_pm_schedule.status
			, t_trx_pm_schedule.remark
			, t_trx_pm_schedule.priority
			, t_trx_pm_schedule.security_code_checkin
			, t_trx_pm_schedule.security_code_checkout
			, t_trx_pm_schedule.visit_week
			, t_trx_pm_schedule.last_visited_date
		');
		
		$this->db->from('t_trx_pm_schedule');
		$this->db->where('t_trx_pm_schedule.user_id', $user_id);
		$this->db->where('t_trx_pm_schedule.visit_date', $visit_date);
		$this->db->where('t_trx_pm_schedule.visit_week', $visit_week);
		$this->db->where('t_trx_pm_schedule.status', 3);
		$this->db->join('t_mtr_site',"t_trx_pm_schedule.site_id = t_mtr_site.site_id AND t_mtr_site.site_code='$site_code'");
		$this->db->join('t_mtr_user','t_trx_pm_schedule.user_id = t_mtr_user.user_id');
		
		$query = $this->db->get();
		
		return $query->result_array();
	}

    public function selectUserAndJourneyCycle2($site_id, $user_id, $visit_date, $visit_week)
	{
		$this->db->select('
			t_trx_pm_schedule.site_id
			, t_mtr_site.site_code
			, t_mtr_site.site_name
			, t_mtr_user.user_name
			, t_mtr_site.latitude
			, t_mtr_site.longitude
			, t_trx_pm_schedule.visit_id
			, t_mtr_site.site_address
			, t_trx_pm_schedule.visit_date
			, t_trx_pm_schedule.visit_type
			, t_trx_pm_schedule.status
			, t_trx_pm_schedule.remark
			, t_trx_pm_schedule.priority
			, t_trx_pm_schedule.security_code_checkin
			, t_trx_pm_schedule.security_code_checkout
			, t_trx_pm_schedule.visit_week
			, t_trx_pm_schedule.last_visited_date
		');
		
		$this->db->from('t_trx_pm_schedule');
		$this->db->where('t_trx_pm_schedule.user_id', $user_id);
		$this->db->where('t_trx_pm_schedule.visit_date', $visit_date);
		$this->db->where('t_trx_pm_schedule.visit_week', $visit_week);
		$this->db->where('t_trx_pm_schedule.site_id', $site_id);
		$this->db->where('t_trx_pm_schedule.status', 0);
		$this->db->join('t_mtr_site','t_trx_pm_schedule.site_id = t_mtr_site.site_id');
		$this->db->join('t_mtr_user','t_trx_pm_schedule.user_id = t_mtr_user.user_id');
		
		$query = $this->db->get();

		return $query->result_array();
	}

    public function selectUserAndJourneyCycleUnshdedule2($site_code, $user_id, $visit_date, $visit_week)
	{
		$this->db->select('
			t_trx_pm_schedule.site_id
			, t_mtr_site.site_code
			, t_mtr_site.site_name
			, t_mtr_user.user_name
			, t_mtr_site.latitude
			, t_mtr_site.longitude
			, t_trx_pm_schedule.visit_id
			, t_mtr_site.site_address
			, t_trx_pm_schedule.visit_date
			, t_trx_pm_schedule.visit_type
			, t_trx_pm_schedule.status
			, t_trx_pm_schedule.remark
			, t_trx_pm_schedule.priority
			, t_trx_pm_schedule.security_code_checkin
			, t_trx_pm_schedule.security_code_checkout
			, t_trx_pm_schedule.visit_week
			, t_trx_pm_schedule.last_visited_date
		');
		
		$this->db->from('t_trx_pm_schedule');
		$this->db->where('t_trx_pm_schedule.user_id', $user_id);
		$this->db->where('t_trx_pm_schedule.visit_date', $visit_date);
		$this->db->where('t_trx_pm_schedule.visit_week', $visit_week);
		$this->db->where('t_trx_pm_schedule.status', 0);
		$this->db->join('t_mtr_site',"t_trx_pm_schedule.site_id = t_mtr_site.site_id AND t_mtr_site.site_code='$site_code'");
		$this->db->join('t_mtr_user','t_trx_pm_schedule.user_id = t_mtr_user.user_id');
		
		$query = $this->db->get();

		return $query->result_array();
	}

    public function selectUserAndJourneyCycle2_2($site_id, $user_id, $visit_date, $visit_week)
	{
		$this->db->select('
			t_trx_pm_schedule.site_id
			, t_mtr_site.site_code
			, t_mtr_site.site_name
			, t_mtr_user.user_name
			, t_mtr_site.latitude
			, t_mtr_site.longitude
			, t_trx_pm_schedule.visit_id
			, t_mtr_site.site_address
			, t_trx_pm_schedule.visit_date
			, t_trx_pm_schedule.visit_type
			, t_trx_pm_schedule.status
			, t_trx_pm_schedule.remark
			, t_trx_pm_schedule.priority
			, t_trx_pm_schedule.security_code_checkin
			, t_trx_pm_schedule.security_code_checkout
			, t_trx_pm_schedule.visit_week
			, t_trx_pm_schedule.last_visited_date
		');
		
		$this->db->from('t_trx_pm_schedule');
		$this->db->where('t_trx_pm_schedule.user_id', $user_id);
		$this->db->where('t_trx_pm_schedule.visit_date <>', $visit_date);
		$this->db->where('t_trx_pm_schedule.visit_week', $visit_week);
		$this->db->where('t_trx_pm_schedule.site_id', $site_id);
		$this->db->where('t_trx_pm_schedule.status', 0);
		$this->db->join('t_mtr_site','t_trx_pm_schedule.site_id = t_mtr_site.site_id');
		$this->db->join('t_mtr_user','t_trx_pm_schedule.user_id = t_mtr_user.user_id');
		
		$query = $this->db->get();

		return $query->result_array();
	}

    public function selectUserAndJourneyCycleUnschedule2_2($site_code, $user_id, $visit_date, $visit_week)
	{
		$this->db->select('
			t_trx_pm_schedule.site_id
			, t_mtr_site.site_code
			, t_mtr_site.site_name
			, t_mtr_user.user_name
			, t_mtr_site.latitude
			, t_mtr_site.longitude
			, t_trx_pm_schedule.visit_id
			, t_mtr_site.site_address
			, t_trx_pm_schedule.visit_date
			, t_trx_pm_schedule.visit_type
			, t_trx_pm_schedule.status
			, t_trx_pm_schedule.remark
			, t_trx_pm_schedule.priority
			, t_trx_pm_schedule.security_code_checkin
			, t_trx_pm_schedule.security_code_checkout
			, t_trx_pm_schedule.visit_week
			, t_trx_pm_schedule.last_visited_date
		');
		
		$this->db->from('t_trx_pm_schedule');
		$this->db->where('t_trx_pm_schedule.user_id', $user_id);
		$this->db->where('t_trx_pm_schedule.visit_date !=', $visit_date);
		$this->db->where('t_trx_pm_schedule.visit_week', $visit_week);
		$this->db->where('t_trx_pm_schedule.status', 0);
		$this->db->join('t_mtr_site',"t_trx_pm_schedule.site_id = t_mtr_site.site_id AND t_mtr_site.site_code = '$site_code'");
		$this->db->join('t_mtr_user','t_trx_pm_schedule.user_id = t_mtr_user.user_id');
		
		$query = $this->db->get();

		return $query->result_array();
	}

    public function selectUserAndJourneyCycle3($site_id, $user_id, $visit_date, $visit_week)
	{
		$this->db->select('
			t_trx_pm_schedule.site_id
			, t_mtr_site.site_name
			, t_mtr_user.user_name
			, t_mtr_site.latitude
			, t_mtr_site.longitude
			, t_trx_pm_schedule.visit_id
			, t_mtr_site.site_address
			, t_trx_pm_schedule.visit_date
			, t_trx_pm_schedule.visit_type
			, t_trx_pm_schedule.status
			, t_trx_pm_schedule.remark
			, t_trx_pm_schedule.priority
			, t_trx_pm_schedule.security_code_checkin
			, t_trx_pm_schedule.security_code_checkout
			, t_trx_pm_schedule.visit_week
			, t_trx_pm_schedule.last_visited_date
		');
		
		$this->db->from('t_trx_pm_schedule');
		$this->db->where('t_trx_pm_schedule.user_id', $user_id);
		$this->db->where('t_trx_pm_schedule.visit_date', $visit_date);
		$this->db->where('t_trx_pm_schedule.visit_week', $visit_week);
		$this->db->where('t_trx_pm_schedule.site_id', $site_id);
		$this->db->where('t_trx_pm_schedule.status', 3);
		$this->db->join('t_mtr_site','t_trx_pm_schedule.site_id = t_mtr_site.site_id');
		$this->db->join('t_mtr_user','t_trx_pm_schedule.user_id = t_mtr_user.user_id');
		
		$query = $this->db->get();

		return $query->result_array();
	}

    public function selectUserAndJourneyCycleUnschedule($site_id)
	{
		$this->db->select('
			t_mtr_site.site_id
			, t_mtr_site.site_code
			, t_mtr_site.site_name
			, t_mtr_site.latitude
			, t_mtr_site.longitude
			, t_mtr_site.site_address
		');
		
		$this->db->from('t_mtr_site');
		$this->db->where('t_mtr_site.site_id', $site_id);
      
		$query = $this->db->get();

		return $query->result_array();
	}

    public function get_t_trx_pm_schedule_checklist($visit_id)
	{
		$this->db->select('*');
		$this->db->from('t_trx_pm_schedule_checklist');
		$this->db->where('visit_id',$visit_id);

		$query = $this->db->get();

		return $query->result_array();
    }

	public function get_preparation_type()
	{
		$query = $this->db
			->query('
                select *
                from t_mtr_pm_preparation_type 
                where status = 1
			');
    
		return $query->result_array();
	}

	public function get_preparation_item($type_id)
	{
		$query = $this->db
			->query('
				SELECT *
                FROM t_mtr_pm_preparation_item
				WHERE type_id = '.$type_id.'
                AND status = 1
			');
    
		return $query->result_array();
	}
  
	public function get_preparation_item1()
	{
		$query = $this->db
			->query('
				select
					a.*,
					b.preparation_type_id,
					b.descr as type_name
                FROM t_mtr_pm_preparation_item a
                LEFT JOIN t_mtr_pm_preparation_type b ON b.preparation_type_id = a.type_id
			');
    
		return $query->result_array();
	}

	public function get_pm_checklist()
	{
		$query = $this->db
			->query('
                SELECT
					a.checklist_id,
					a.group_id,b.name,
					a.descr,
					a.property_id,
					a.urutan
                FROM
					t_mtr_pm_checklist a
                LEFT JOIN t_mtr_pm_checklist_group b ON b.id = a.group_id
                ORDER BY
					a.urutan ASC
			');
    
		return $query->result_array();
	}

	public function get_pm_checklist_option($checklist_id,$property_id)
	{
		$query = $this->db
			->query('
                SELECT
					option,
					bobot,
					urutan
                FROM
					t_mtr_pm_checklist_property_option
                WHERE
					active = 1
				AND checklist_id = '.$checklist_id.'
				AND property_id = '.$property_id.' 
                ORDER BY
					urutan ASC
              ');
    
		return $query->result_array();
	}
  
	public function get_pm_checklist_option2($checklist_id,$checklist_type)
	{
		$query = $this->db
			->query('
                SELECT
					option_id,
					checklist_id,
					option,
					score,
					is_critical,
					create_notification,
					order_no
                FROM
					t_mtr_checklist_answer
                WHERE
					active = 1
				AND checklist_id = '.$checklist_id.'
                ORDER BY
					order_no ASC;
			');

		return $query->result_array();
	}

	public function get_pm_checklist_by_group($group_id,$visit_id)
	{
		$query = $this->db
			->query('
				SELECT
					b.checklist_id,
					b.descr,
					b.property_id,
					d.name
				FROM
					t_trx_pm_schedule_checklist a
				LEFT JOIN t_mtr_pm_checklist b ON b.checklist_id = a.checklist_id 
				LEFT JOIN t_mtr_pm_checklist_group c ON c.id = b.group_id
				LEFT JOIN t_m_ceklis_property_form d ON d.property_id = b.property_id
				WHERE
					a.visit_id = '.$visit_id.'
				AND b.group_id = '.$group_id.'
				ORDER BY
					b.urutan ASC
			');
  
			return $query->result_array();
	}
  
	public function get_pm_checklist_by_group2($group_id,$visit_id)
	{
		$query = $this->db
			->query('
				SELECT
					sch.visit_id
					,cek.checklist_id
					,cek.checklist_group
					,gr.group_name
					,cek.checklist_type
					,ty.type_name     
					,cek.checklist_name
					,cek.order_number
					,cek.has_photo
					,cek.total_photo
					,cek.dedicated_photo
				FROM
					t_trx_pm_schedule_checklist sch
				JOIN t_mtr_checklist cek ON cek.checklist_id = sch.checklist_id
				AND cek.status = 1
				JOIN t_mtr_checklist_group gr ON gr.checklist_group_id = cek.checklist_group
				LEFT JOIN t_mtr_checklist_type ty ON ty.checklist_type_id = cek.checklist_type
				AND ty.status = 1
				WHERE
					sch.visit_id = '.$visit_id.' 
				AND gr.checklist_group_id = '.$group_id.'
				ORDER BY cek.order_number;
			');
  
		return $query->result_array();
	}

	public function get_pm_checklist_group() //updated on 17 okt 2013 by iwa
	{
		$query = $this->db
			->query('
				select * from t_mtr_pm_checklist_group
			');
		
		return $query->result_array();
	}

	public function get_pm_checklist_group_level2() //updated on 19 okt 2013 by iwa
	{
		$query_level1 = $this->db
			->query('
				select
					checklist_group_id,
					group_name
				from t_mtr_checklist_group
				where group_key=\'pm\'
				and parent_id=0
				and status=1
			');
		
		$level1_id = 0;
		
		if ($query_level1->num_rows() > 0)
		{
		   $row = $query_level1->row();
		   $level1_id = $row->checklist_group_id;
		   $level1_name = $row->group_name;
		}
		
		$query_level2 = $this->db
			->query('
				select
					checklist_group_id,
					group_name
				from t_mtr_checklist_group
				where group_key=\'pm\'
				and parent_id='.$level1_id.'
				and status=1
			');
			
		return $query_level2->result_array();
	}

	public function get_pm_checklist_group_level3() //updated on 19 okt 2013 by iwa
	{
		$query_level1 = $this->db
			->query('
                select checklist_group_id, group_name from t_mtr_checklist_group where group_key=\'pm\' and parent_id=0 and status=1
			');
		
		$level1_id=0;
		
		if ($query_level1->num_rows() > 0)
		{
		   $row = $query_level1->row();
		   $level1_id= $row->checklist_group_id;
		   $level1_name= $row->group_name;
		}
		
		$query_level2 = $this->db->query('
			select checklist_group_id, group_name from t_mtr_checklist_group where group_key=\'pm\' and parent_id='.$level1_id.' and status=1
		');
    
		return $query_level2->result_array();
	}

	public function get_pm_checklist_group_level4() //updated on 25 okt 2013 by iwa
	{
		$query_level1 = $this->db->query('
			select checklist_group_id, group_name from t_mtr_checklist_group where group_key=\'pm\' and parent_id=0 and status=1
		');
		
		$level1_id = 0;
		
		if ($query_level1->num_rows() > 0)
		{
			$row = $query_level1->row();
			$level1_id= $row->checklist_group_id;
			$level1_name= $row->group_name;
		}
		
		$query_level2 = $this->db->query('
			select checklist_group_id, group_name from t_mtr_checklist_group where group_key=\'pm\' and parent_id='.$level1_id.' and status=1
		');

		$level2_id = 0;
		$array_result_level2 = array();
		$i = 0;
		
		if ($query_level2->num_rows() > 0)
		{
			foreach ($query_level2->result_array() as $key)
			{
				$level2_id = $key['checklist_group_id'];
				$array_result_level2[$i] = $level2_id;
				$i++;
			}
		}

		return $query_level2->result_array();
	}

	public function get_tm_mtr_checklist_group_level_2($array_data_input)
	{
		$array_data_output = array();

		$i=0;
		foreach ($array_data_input as $key)
		{
			$array_data_output[$i]=$key['checklist_group_id'];
			$i++;
		}

		$this->db->select('*');
		$this->db->from('t_mtr_checklist_group');
		$this->db->where_in('parent_id',$array_data_output);
		$this->db->where('group_key','pm');
		$this->db->where('status',1);
		$this->db->where('level ISNULL',null);  

		$query = $this->db->get();

		return $query->result_array();  
	}

	public function get_tm_mtr_checklist_group_level_3($array_data_input)
	{
		$array_data_output = array();

		$i=0;
		foreach ($array_data_input as $key)
		{
			$array_data_output[$i]=$key['checklist_group_id'];
			$i++;
		}

		$this->db->select('*');
		$this->db->from('t_mtr_checklist_group');
		$this->db->where_in('parent_id',$array_data_output);
		$this->db->where('group_key','pm');
		$this->db->where('status',1); 

		$query = $this->db->get();

		return $query->result_array();  
	}

	public function get_tm_mtr_checklist_group_level_4($array_data_input)
	{
		$array_data_output = array();

		$i=0;
		foreach ($array_data_input as $key)
		{
			$array_data_output[$i]=$key['checklist_group_id'];
			$i++;
		}

		$this->db->select('*');
		$this->db->from('t_mtr_checklist_group');
		$this->db->where_in('parent_id',$array_data_output);
		$this->db->where('group_key','pm');
		$this->db->where('status',1); 

		$query = $this->db->get();

		return $query->result_array();  
	}

	public function get_tm_mtr_checklist_group_level_5($array_data_input)
	{
		$array_data_output = array();

		$i=0;
		foreach ($array_data_input as $key)
		{
			$array_data_output[$i]=$key['checklist_group_id'];
			$i++;
		}

		$this->db->select('*');
		$this->db->from('t_mtr_checklist_group');
		$this->db->where_in('parent_id',$array_data_output);
		$this->db->where('group_key','pm');
		$this->db->where('status',1); 

		$query = $this->db->get();

		return $query->result_array();  
	}

	public function get_pm_checklist_group2()
	{
		$this->db->select("*");
		$this->db->from("t_mtr_pm_checklist_group");

		$query = $this->db->get();

		return $query->result_array();
	}
  
	public function get_pm_checklist_group3()
	{
		$this->db->select("*");
		$this->db->from("t_mtr_checklist_group");
		$this->db->where("parent_id",1);
		$this->db->where("status",1);
		$query = $this->db->get();

		return $query->result_array();
	}
  
	public function get_pm_checklist_level2($checklist_group)
	{
		$this->db->select("*");
		$this->db->from("t_mtr_checklist_group");
		$this->db->where("parent_id",$checklist_group);
		$this->db->where("status",1);
		$query = $this->db->get();

		return $query->result_array();
	}

	public function get_pm_jc($user_id, $date)
	{
		$this->db->select("*");
		$this->db->from("t_trx_pm_schedule");

		$query = $this->db->get();

		return $query->result_array();
	}

	public function insert_jc_preparation($data)
	{
		$this->db->trans_begin();
		
		$this->db->insert_batch('t_trx_pm_preparation_used',$data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
  
	public function insertScheduleConfirm($data)
	{
		$this->db->trans_begin();
		
		$this->insertData('t_trx_schedule_confirm', $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
  
	public function updateScheduleStatus($data, $visitId)
    {
		$this->db->trans_begin();
		
		$this->updateData('t_trx_schedule', $data, array('visit_id' => $visitId));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
    }
  
	//start edited by Hadi 2018-03-13
	public function updateLogOutFOP($userId)
    {
		$data->is_login = 0;
		$this->db->trans_begin();
        $this->db
                ->where('user_id', $userId)
				->where_in('main_division_id', array(63))
                ->update('t_mtr_user', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
	//end
  
	public function insert_coordinat_checkin($data)
	{
		$this->db->trans_begin();
		
		$this->db->insert('t_trx_tracking', $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
    }

	public function insert_jc_sitechecklist($data)
	{
		$this->db->trans_begin();

		$this->db->insert('t_trx_pm_checklist_result', $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
  
	public function insertChecklist($data, $data_photo)
	{
		ini_set("memory_limit", "-1");
		set_time_limit(0);
		$this->db->trans_begin();
		
		$this->insertData("t_trx_checklist_result", $data);
		// $this->insertData('t_trx_checklist_result_photo', $data_photo);

		$dbQuery = $this->db->get_where('t_trx_checklist_result_photo', $data_photo);
        $dbResult = $dbQuery->result();

		if ( ! $dbQuery->num_rows() )
		{
			$insertQuery = $this->db->insert('t_trx_checklist_result_photo', $data_photo);

            $pkInsert = $this->db->insert_id();

            $this->db
				->set('log_data_table', $table)
				->set('log_data_pkvalue', (int) $pkInsert)
				->set('created_by', $this->tokenProfile->userId)
				->set('created_on', date('Y-m-d H:i:s'))
				->set('log_activity_id', $this->logActivity->id);
				
            $this->db->insert('t_trx_log_data');
			
            $id = $this->db->insert_id();
        }
		else
		{
            $this->rest->sendError(406, 'Can Not Insert Existing Data '.$table);
        }
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
  
	public function insert_jc_sitechecklist3($data)
	{
		$this->db->trans_begin();
		
		$this->db->insert("t_trx_pm_checklist_result",$data);   
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
    }

    public function updateChecklist($visit_id, $checklist_id, $data_result, $data_result_photo)
	{
		ini_set("memory_limit", "-1");
		set_time_limit(0);
		$this->db->trans_begin();

        $this->updateData('t_trx_checklist_result', $data_result, array('visit_id' => $visit_id, 'checklist_id' => $checklist_id));

        // $this->updateData('t_trx_checklist_result_photo', $data_result_photo, array('visit_id' => $visit_id, 'checklist_id' => $checklist_id));

		$this->db->where(array('visit_id' => $visit_id, 'checklist_id' => $checklist_id));
		$this->db->update('t_trx_checklist_result_photo', $data_result_photo);

        if ($this->db->trans_status() === FALSE)
		{
            $this->db->trans_rollback();
            return FALSE;
        }
		else
		{
            $this->db->trans_commit();
            return TRUE;
        }
	}
  
	public function update_jc_sitechecklist3($visit_id, $checklist_id, $data_result)
	{
		$this->db->trans_begin();

        $this->updateData('t_trx_checklist_result', $data_result, array('visit_id' => $visit_id, 'checklist_id' => $checklist_id));

        if ($this->db->trans_status() === FALSE)
		{
            $this->db->trans_rollback();
            return FALSE;
        }
		else
		{
            $this->db->trans_commit();
            return TRUE;
        }
	}

    public function getChecklistResult($visitId, $checklistId)
	{
        $sql = "
        	SELECT
				chk_result.checklist_result_id,
				chk_result.visit_id,
				chk_result.checklist_id,
				chk_result.checklist_result,
				chk_result.latitude,
				chk_result.longitude,
				res_photo.photo_name,
				res_photo.photo_remark
			FROM
				t_trx_checklist_result chk_result
			LEFT JOIN t_trx_checklist_result_photo res_photo ON res_photo.checklist_id = chk_result.checklist_id
			AND res_photo.visit_id = chk_result.visit_id
			WHERE
				chk_result.visit_id = {$visitId}
			AND chk_result.checklist_id = {$checklistId}
        ";

        return $this->db
        	->query($sql)
        	->row_array();
    }

	public function insert_t_trx_pm_tracking($jc_id, $waktu, $latitude, $longitude, $created_by, $created_on, $updated_by, $updated_on)
	{
		$this->db->trans_begin();

		$data = array(
			'jc_id'     => $jc_id 
			, 'waktu'   => $waktu
			, 'latitude'  => $latitude
			, 'longitude'   => $longitude
			, 'created_by'  => $created_by
			, 'created_on'  => $created_on
			, 'updated_by'  => $updated_by
			, 'updated_on'  => $updated_on
		  );

		$this->db->insert('t_trx_tracking', $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	public function insert_t_trx_pm_schedule(
		$site_id, $pic_id, $user_id, $user_group_id, $cluster_ops_id, $region_id, $visit_date
		, $visit_type, $status, $created_by, $created_on, $updated_by, $updated_on
		, $remark, $priority, $security_code_check_in, $security_code_check_out , $visit_week, $scheduled, $parent_id
		, $last_visited_date, $approval, $approval_remarks
    )
	{
		$this->db->trans_begin();

		$data = array(
			'site_id'       => $site_id
			, 'pic_id'        => $pic_id
			, 'user_id'       => $user_id
			, 'user_group_id'     => $user_group_id
			, 'cluster_ops_id'    => $cluster_ops_id
			, 'region_id'     => $region_id
			, 'visit_date'      => $visit_date
			, 'visit_type'      => $visit_type
			, 'status'        => $status
			, 'created_by'      => $created_by
			, 'created_on'      => $created_on
			, 'updated_by'      => $updated_by
			, 'updated_on'      => $updated_on
			, 'remark'        => $remark
			, 'priority'      => $priority
			, 'security_code_checkin'   => $security_code_check_in
			, 'security_code_checkout'    => $security_code_check_out
			, 'visit_week'      => $visit_week
			, 'scheduled'     => $scheduled
			, 'parent_id'     => $parent_id
			, 'last_visited_date' => $approval
			, 'approval_remarks'  => $approval_remarks
		  );

		$this->db->insert('t_trx_pm_schedule',$data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return array('code' => FALSE);
		}
		else
		{
			$this->db->trans_commit();
			return array('code' => TRUE, 'trx' => $this->db->insert_id());
		}
    }

	public function insert_t_trx_pm_schedule2
	(
		$site_id, $pic_id, $user_id, $user_group_id, $cluster_ops_id, $region_id, $visit_date
		, $visit_type, $status, $created_by, $created_on, $updated_by, $updated_on
		, $remark, $priority, $security_code_check_in, $security_code_check_out ,$visit_week, $scheduled, $parent_id
		, $last_visited_date, $approval, $approval_remarks
    )
	{
		$this->db->trans_begin();

		$data = array(
			'site_id'       => $site_id
			, 'pic_id'        => $pic_id
			, 'user_id'       => $user_id
			, 'user_group_id'     => $user_group_id
			, 'cluster_ops_id'    => $cluster_ops_id
			, 'region_id'     => $region_id
			, 'visit_date'      => $visit_date
			, 'visit_type'      => $visit_type
			, 'status'        => $status
			, 'created_by'      => $created_by
			, 'created_on'      => $created_on
			, 'updated_by'      => $updated_by
			, 'updated_on'      => $updated_on
			, 'remark'        => $remark
			, 'priority'      => $priority
			, 'security_code_checkin'   => $security_code_check_in
			, 'security_code_checkout'    => $security_code_check_out
			, 'visit_week'      => $visit_week
			, 'scheduled'     => $scheduled
			, 'parent_id'     => $parent_id
			, 'last_visited_date' => $approval
			, 'approval_remarks'  => $approval_remarks
		  );

		$this->db->insert('t_trx_pm_schedule',$data);

		echo $this->db->insert_id();

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
    }

	public function update_t_trx_pm_schedule2($visit_id,$user_id)
	{
		$this->db->trans_begin();

		$data = array(
			'status' => 3,
			'last_visited_date' => date("Y-m-d"),
			'updated_by' => $user_id,
			'updated_on' => date("Y-m-d H:i:s")
		);

		$this->db->where('visit_id',$visit_id);
		$this->db->update('t_trx_pm_schedule',$data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	public function update_t_trx_pm_schedule(
		$visit_id, $site_id, $pic_id, $user_id, $user_group_id, $cluster_ops_id, $region_id, $visit_date
		, $visit_type, $status, $created_by, $created_on, $updated_by, $updated_on
		, $remark, $priority, $security_code_check_in, $security_code_check_out ,$visit_week, $scheduled, $parent_id
		, $last_visited_date, $approval, $approval_remarks
    )
	{
		$this->db->trans_begin();

		$data = array(
			'site_id'       => $site_id
			, 'pic_id'        => $pic_id
			, 'user_id'       => $user_id
			, 'user_group_id'     => $user_group_id
			, 'cluster_ops_id'    => $cluster_ops_id
			, 'region_id'     => $region_id
			, 'visit_date'      => $visit_date
			, 'visit_type'      => $visit_type
			, 'status'        => $status
			, 'created_by'      => $created_by
			, 'created_on'      => $created_on
			, 'updated_by'      => $updated_by
			, 'updated_on'      => $updated_on
			, 'remark'        => $remark
			, 'priority'      => $priority
			, 'security_code_checkin'   => $security_code_check_in
			, 'security_code_checkout'    => $security_code_check_out
			, 'visit_week'      => $visit_week
			, 'scheduled'     => $scheduled
			, 'parent_id'     => $parent_id
			, 'last_visited_date' => $approval
			, 'approval_remarks'  => $approval_remarks
		  );

		$this->db->where('visit_id', $visit_id);
		$this->db->update('t_trx_pm_schedule', $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
    }
  
  
	public function insert_t_trx_pm_tracking2($data)
	{
		$this->db->trans_begin();

		$this->db->insert_batch('t_trx_tracking',$data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	public function getLookup($key)
	{
		$sql = "
			SELECT
				lookup_value AS \"id\",
				lookup_desc AS \"name\"
			FROM
				t_mtr_lookup
			WHERE
				lookup_key = '{$key}'
			AND active = 1;
		";
		
		return $this->db
			->query($sql)
			->result_array();
	}

	public function getChecklistOption($optionKey)
	{
		$sql = "
			SELECT DISTINCT
				-- opt.option_id,
				opt.option_value as visit_type_value,
				opt.option_label as visit_type_label
			FROM
				t_mtr_checklist_options opt
			JOIN t_mtr_checklist_template tmp ON tmp.option_id = opt.option_id
			WHERE
				opt.option_key IN ('{$optionKey}')
			AND opt.status = 1;
		";

		return $this->db
			->query($sql)
			->result_array();
	}

	public function get_mtr_site($region_id)
	{
		$this->db->select('site_id, site_code, vendor_id');
		$this->db->from('t_mtr_site');
		$this->db->where('region_id', $region_id);

		$query = $this->db->get();

		return $query->result_array();
	}
  
	public function get_mtr_site_new($user_id,$region_id)
	{
		$this->db->select('st.site_id, st.site_code, st.vendor_id');
		$this->db->from('t_mtr_site st');
		$this->db->join('t_trx_pm_schedule sch','sch.site_id = st.site_id');    
		$this->db->where('sch.user_id',$user_id);
		$this->db->where_in('sch.status', array(0,1,4,5));

		$query = $this->db->get();

		return $query->result_array();
	}
  
	public function get_mtr_site_new_2($user_id,$region_id)
	{
		$this->db->select('st.site_id, st.site_code, st.vendor_id');
		$this->db->from('t_mtr_site st');
		$this->db->join('t_trx_pm_schedule sch','sch.site_id = st.site_id and sch.status in (0,1,4,5)','left');
		$this->db->where('st.pic_smart',$user_id);
		$this->db->or_where('sch.user_id', $user_id);     

		$query = $this->db->get();

		return $query->result_array();
	}
  
	public function get_search_site($input_keyword,$the_user_id)
	{
		$q = $this->db->get_where('t_mtr_user_territory',array('user_id'=>$the_user_id))->result();
		
		$region=array();
		foreach($q  as $row)
		{
			$region[] = $row->region_id;
		}
		
		$where_2 = "
			(site_code ILIKE replace('%$input_keyword%', '_', '\_')
			or site_name ILIKE replace('%$input_keyword%', '_', '\_')
			or vendor_id ILIKE replace('%$input_keyword%', '_', '\_'))
		";
		
		$this->db->select('site_id, site_code, site_name, vendor_id, longitude, latitude, site_address');
		$this->db->from('t_mtr_site');
		$this->db->where_in('region_id',$region);
		$this->db->where($where_2); 
		$this->db->order_by("site_code", "asc"); 
		
		$query = $this->db->get();
		
		return $query->result_array();
    }

	public function get_t_mtr_pm_options()
	{
		$this->db->select('*');
		$this->db->from('t_mtr_pm_options');

		$query = $this->db->get();

		return $query->result_array();
	}

	public function get_option_id_from_t_mtr_checklist_options($visit_type)
	{
		$this->db->select('option_id');
		$this->db->from('t_mtr_checklist_options');
		$this->db->where('option_label',$visit_type);
		$this->db->where('option_key','pm_visit_type');

		$query = $this->db->get();

		return $query->result_array();
	}

	public function get_checklist_id_from_t_ref_checklist_option($option_id)
	{
		$this->db->select('checklist_id');
		$this->db->from('t_mtr_checklist_template');
		$this->db->where('option_id',$option_id);

		$query = $this->db->get();

		return $query->result_array();
	}

	public function insert_t_trx_pm_schedule_checklist($data)
	{
		$this->db->trans_begin();
		
		$this->db->insert_batch('t_trx_pm_schedule_checklist',$data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
    }

	public function get_site_id_from_t_mtr_site_where_site_code($site_code)
	{
		$this->db->select('site_id');
		$this->db->from('t_mtr_site');
		$this->db->where('site_code',$site_code);

		$query = $this->db->get();

		return $query->result_array();
	}

	public function get_site_from_t_mtr_site_where_pic_smart($pic_smart)
	{
        $this->db->select('site_id, site_code');
        $this->db->from('t_mtr_site');
        $this->db->where('pic_smart',$pic_smart);

        $query = $this->db->get();

        return $query->result_array();
    }
  
	public function inspectionProperties($user_id, $visit_id, $visit_type)
	{
		$this->db->select("
			DISTINCT sch.visit_id,
			cek.checklist_id,
			CASE
				WHEN gr.parent_id = 598 THEN cek.checklist_group
				ELSE gr.root_id
			END as checklist_group,
			f_get_checklistgroup_by_level(cek.checklist_group, 1) AS grup_level_1,
			f_get_checklistgroup_by_level(cek.checklist_group,2) AS grup_level_2,
			gr.group_name,
			CASE
				WHEN cek.checklist_type NOT IN (2,3,5) THEN 5
				ELSE cek.checklist_type
			END AS checklist_type
		", FALSE);
		
		$this->db->select("
			CASE
				WHEN ty.type_name NOT IN ('checkbox','radiobutton','textbox') THEN 'textbox'
				ELSE ty.type_name
			END AS type_name,
			cek.checklist_name,
			cek.order_number,
			ctm.has_photo,
			cek.total_photo,
			cek.dedicated_photo,
			0 AS mandatory,
			CASE
				WHEN gr3.level = 0 THEN gr.order_number
				WHEN gr3.level = 1 THEN gr2.order_number
				WHEN gr3.level = 2 then gr3.order_number
			END AS order_baru_1,
			CASE
				WHEN gr2.level = 1 THEN gr3.order_number + 2
				WHEN gr2.level = 2 THEN gr.order_number
				WHEN gr2.level = 3 THEN gr2.order_number
			END AS order_baru_2,
			CASE
				WHEN gr. level = 2 THEN gr2.order_number
				WHEN gr. level = 3 THEN gr3.order_number
				WHEN gr. level = 4 THEN gr.order_number
			END AS order_baru_3
		", FALSE);
		
		$this->db->join('t_mtr_checklist cek', 'cek.checklist_id = sch.checklist_id');
		$this->db->join('t_mtr_checklist_group gr', 'gr.checklist_group_id = cek.checklist_group');
	  	$this->db->join('t_mtr_checklist_group gr2', 'gr2.checklist_group_id = gr.parent_id');
	  	$this->db->join('t_mtr_checklist_group gr3', 'gr3.checklist_group_id = gr2.parent_id');
		$this->db->join('t_mtr_checklist_type ty', 'ty.checklist_type_id = cek.checklist_type', 'LEFT');
		$this->db->join('t_trx_schedule vv', 'vv.visit_id = sch.visit_id');
	  	$this->db->join('t_mtr_checklist_template ctm', 'ctm.checklist_id = sch.checklist_id');
		//$this->db->where('vv.visit_date', date('Y-m-d') );
		$this->db->where('cek.status', 1);
		$this->db->where('gr.status', 1);
		$this->db->where('ty.status', 1);
		$this->db->where('vv.pic_id', $user_id);
		$this->db->where('gr.group_key', 'prev.maintenance');
	  	$this->db->where('cek.interface', 'android');
	  	$this->db->where('vv.visit_id', $visit_id);
		  $this->db->where('ctm.option_id', $visit_type);
		//$this->db->order_by('cek.checklist_group, cek.checklist_id, cek.order_number');
		//$this->db->order_by('cek.checklist_group, cek.checklist_group, gr.order_number, cek.order_number');
		//$this->db->order_by('checklist_group, gr.order_number, cek.order_number');//edited by iwa 28 okt 2014
		//$this->db->order_by('checklist_group, order_number_ordering, order_number_ordering_2, order_number_ordering_3, gr3.order_number, gr2.order_number, grup_level_2, gr.order_number, cek.order_number');
	  	$this->db->order_by('checklist_group, order_baru_1, order_baru_2, order_baru_3, cek.order_number');
		$q = $this->db->get('t_trx_schedule_checklist sch');
		$data["item"] = $q->result_array();

		$this->db->select('vv.visit_id, aa.option_id, vc.checklist_id, aa.option, aa.score, aa.is_critical, aa.create_notification, aa.order_no');
		$this->db->join('t_mtr_checklist cc', 'cc.checklist_id = aa.checklist_id');
		$this->db->join('t_trx_schedule_checklist vc', 'vc.checklist_id = cc.checklist_id');
		$this->db->join('t_trx_schedule vv', 'vv.visit_id = vc.visit_id');
		$this->db->where('vv.pic_id', $user_id);
	  	$this->db->where('vv.visit_id', $visit_id);
		//$this->db->where('vv.visit_date', date('Y-m-d'));
	  	$this->db->order_by('aa.order_no');
		$q = $this->db->get('t_mtr_checklist_answer aa');
		
		$data['options'] = $q->result_array();

		$this->db->select('checklist_group_id, checklist_group_id parent_id, parent_id parent_id2, group_name, order_number, status, created_on, created_by, updated_on, updated_by, group_key, description, is_last_child, level');
	  	//$this->db->select('checklist_group_id, parent_id, group_name');
		$this->db->where('group_key', 'prev.maintenance');
		$this->db->where('status', 1);
		//$this->db->order_by('checklist_group_id');
	  	$this->db->order_by('order_number, parent_id, checklist_group_id');
		$q = $this->db->get('t_mtr_checklist_group');
		
		$data["group"] = $q->result_array();

		return $data;
	}
  
	public function getVisit($visitId)
	{
		//$this->db->select('site_id, visit_date');
		$this->db->select('visit_date');
		$this->db->from('t_trx_schedule');
		$this->db->where('visit_id', $visitId);
		$this->db->where('is_deleted !=', 1);
      
		$query = $this->db->get();

		return $query->result_array();
	}

	public function getVisitById($visitId)
	{
		$sql = "
			SELECT
				sch.visit_id,
				sch.pic_id,
				sch.fop_hs_name,
				sch.fop_hs_contact,
				sch.visit_date,
				sch.last_visited_date,
				sch.visit_type,
				sch.region_id,
				sch.status
			FROM
				t_trx_schedule sch
			WHERE
				sch.visit_id = {$visitId}
			AND sch.is_deleted != 1;
		";

		return $this->db
			->query($sql)
			->row_array();
	}
  
	public function get_user_grup_id($user_id)
	{
		$this->db->select('user_group_id');
		$this->db->from('t_mtr_user');
		$this->db->where('user_id', $user_id);

		$query = $this->db->get();

		return $query->result_array();
	}
  
	public function get_pic_id($site_code)
	{
		$this->db->select('pic_smart');
		$this->db->from('t_mtr_site');
		$this->db->where('site_code', $site_code);

		$query = $this->db->get();

		return $query->result_array();
	}
  
	public function get_cluster_ops($user_id)
	{
		$this->db->select('cluster_ops_id');
		$this->db->from('t_mtr_user_territory');
		$this->db->where('user_id', $user_id);

		$query = $this->db->get();

		return $query->result_array();
	}
  
	public function cek_password_admin_pointing($inputPassword)
	{
		$sql = "
			select lookup_value from t_mtr_lookup where lookup_key = 'mobile_password_setting_url' and lookup_value = '".$inputPassword."' and active = 1;
		";
	
		$result = $this->db->query($sql);  
		
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
        }
		else
		{
			return null;
        } 
	}
  
	public function cek_url_pointing($inputUrl)
	{
		$sql = "
			select lookup_value from t_mtr_lookup where lookup_key = 'url_pointing_mobile_pm' and lookup_value = '".$inputUrl."' and active = 1;
		";
		
		$result = $this->db->query($sql);  
		if ($result->num_rows()>0)
		{
			return $result->result_array();
        }
		else
		{
			return null;
        }
	}
  
	public function change_setting_connection_timeout($globalConnectionTimeout)
	{
		$sql = "
			select lookup_value from t_mtr_lookup where lookup_key = 'mobile_setting_connection_timeout' and active = 1;        
		";
		
		$result = $this->db->query($sql);  
		
		if ($result->num_rows() > 0)
		{
			return $result->row()->lookup_value;
        }
		else
		{
			return null;
        }
    }
  
	public function change_setting_socket_timeout($globalConnectionTimeout)
	{
		$sql = "
			select lookup_value from t_mtr_lookup where lookup_key = 'mobile_setting_socket_timeout' and active = 1;        
		";
		
		$result = $this->db->query($sql);  
		
		if ($result->num_rows() > 0)
		{
			return $result->row()->lookup_value;
        }
		else
		{
			return null;
        }
    }

	public function get_apk_active()
	{
		$this->db->where('active', 1);
		
		return $this->db
			->get('t_trx_mobile_app_version')
			->row();
	}

	public function save_apk_update($handset, $apk)
	{
		$this->db->trans_begin();
		
		$user_id = $handset->user_id;
		$row = $this->db->get_where('t_trx_mobile_user_update_app upd', array('user_id' => $user_id))->row();
		
		$data = array(
			'user_id' => $user_id,
			'version_id' => $apk->version_id,
			'active' => 1
		);

		if ($row == NULL)
		{
			$this->mglobal->saveData('t_trx_mobile_user_update_app', $data);
			$this->dblog->AuditTrail('update_apk', $data, "mobile", $user_id);
		}
		else
		{
			if ($row->version_id != $apk->version_id)
			{
				$this->mglobal->saveData('t_trx_mobile_user_update_app', $data, 'user_id');
				$this->dblog->AuditTrail('update_apk', $data, "mobile", $user_id);
			}
		}

		$this->db->trans_commit();
		
		return $this->db->trans_status();
	}
  
	public function get_handset($param = array())
	{
		$this->db->where($param);
		$this->db->where('istatus', 1);
		
		return $this->db
			->get('t_mtr_handset')
			->row();
	}
  
	public function get_apk_version($param)
	{
		$this->db->where('version_number', $param);
		return $this->db
			->get('t_trx_mobile_app_version')
			->row();
	}

	public function getSummary($arrWhere)
	{
		return $this->db
			->where($arrWhere)
			->get('t_trx_schedule_result_approval_sum')
			->row();
	}

	public function insertSummary($data)
	{
		$this->db->trans_begin();
		
		$this->insertData('t_trx_schedule_result_approval_sum', $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			log_message('debug','insertSummary by hadi with mobile#false#'.$data['visit_id'].'#'.$data['user_id']);
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			log_message('debug','insertSummary by hadi with mobile#true#'.$data['visit_id'].'#'.$data['user_id']);
			return TRUE;
		}
	}

	public function updateSummary($data, $arrWhere)
	{
		$this->db->trans_begin();
		
		$this->updateData('t_trx_schedule_result_approval_sum', $data, $arrWhere);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	public function updateScheduleApproval($data, $arrWhere)
	{
		$this->db->trans_begin();
		//Disini mas riski selalu update status
		$this->updateData('t_trx_schedule_result_approval', $data, $arrWhere);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			log_message('debug','updateScheduleApproval by hadi with mobile#false#'.$data['visit_id'].'#'.$data['user_id']);
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			log_message('debug','updateScheduleApproval by hadi with mobile#true#'.$data['visit_id'].'#'.$data['user_id']);
			return TRUE;
		}
	}

	public function updatePoLinkStatus($status, $visitId)
	{
		$this->db->trans_begin();
		
		$this->db->query("UPDATE t_trx_po_link SET progress_status = {$status} WHERE po_id = (SELECT po_id FROM t_trx_schedule WHERE visit_id = {$visitId});");

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
    
	public function insert_pending_item($data)
	{
		$this->db->trans_begin();
		
		$this->insertData('t_trx_schedule_pending_item', $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	public function update_pending_item($data, $arrWhere)
	{
		$this->db->trans_begin();
		
		$this->updateData('t_trx_schedule_pending_item', $data, $arrWhere);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
    
	public function insert_sign($data)
	{
		$this->db->trans_begin();
		
		$this->insertData('t_trx_schedule_sign', $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	public function update_sign($data, $arrWhere)
	{
		$this->db->trans_begin();
		
		$this->updateData('t_trx_schedule_sign', $data, $arrWhere);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
    
	public function insert_fop_photo($data)
	{
		$this->db->trans_begin();
		
		$this->insertData('t_trx_schedule_fop_photo', $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
    }
	
	public function updateQuatityActual($data, $bomId)
	{
		$this->db->trans_begin();
		
		$this->updateData('t_trx_bom', $data, array('bom_id' => $bomId));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
    }
	
	public function tokenValidator($token)
    {
        $dbQuery = $this->db->get_where('t_mtr_user', array('mobile_token' => $token, 'status' => 1, 'is_mobile_user' => 1));
        
		$dbResult = $dbQuery->result();
		
		$this->tokenProfile = new stdClass();
        
		if ($dbResult)
		{
			$this->tokenProfile->userId = $dbQuery->row()->user_id;

            return TRUE;
        }
		else
		{
			$this->tokenProfile->userId = NULL;

            $this->rest->sendError(401, 'Unidentified Session');
			
            return FALSE;
        }
    }
	
	public function getUser($userId)
	{
		$sql = "
			SELECT
				*
			FROM
				t_mtr_user
			WHERE
				user_id = '{$userId}'
			AND status = 1;
		";
		
		return $this->db
			->query($sql)
			->row();
	}
	
	public function getUserByToken($token)
	{
		$sql = "
			SELECT
				*
			FROM
				t_mtr_user
			WHERE
				mobile_token = '{$token}';
		";
		
		return $this->db
			->query($sql)
			->row();
	}
	
	public function startLogActivity($activityName)
    {
        $sql = '
			CREATE TABLE IF NOT EXISTS t_trx_log_activity (
				"id" bigserial,
				"log_activity_action" varchar COLLATE "default",
				"log_activity_param" text COLLATE "default",
				"log_activity_response" text COLLATE "default",
				"created_by" varchar(30) default NULL,
				"created_on" timestamp(6),
				"updated_by" varchar(30) default NULL,
				"updated_on" timestamp(6),
				CONSTRAINT "t_trx_log_activity_pkey" PRIMARY KEY ("id")
			);
		';
		
		$this->db->query($sql);
		
		if ( ! isset($this->tokenProfile->userId))
		{
			$this->tokenProfile = new stdClass();
			
			$this->tokenProfile->userId = NULL;
		}
		
		$this->db
			->set('log_activity_action', $activityName)
			->set('log_activity_param', $this->input->get_post('data'))
			->set('created_by', $this->tokenProfile->userId)
			->set('created_on', date('Y-m-d H:i:s'))
			->insert('t_trx_log_activity');
		
		$this->logActivity = new stdClass();
		
        $this->logActivity->id = $this->db->insert_id();
	}
	
	public function endLogActivity()
    {
		$this->db->update('t_trx_log_activity', array(
            'log_activity_response' => $this->rest->response,
            'created_by' => $this->tokenProfile->userId,
            'updated_by' => $this->tokenProfile->userId,
            'updated_on' => date('Y-m-d H:i:s')), array('id' => $this->logActivity->id)
		);
	}
	
	private function _createTableLog()
	{
		$sql = '
			CREATE TABLE IF NOT EXISTS t_trx_log_data (
				id bigserial NOT NULL,
				log_activity_id int4,
				log_data_table varchar COLLATE "default",
				log_data_pkvalue int4,
				created_by varchar(30),
				created_on timestamp(6),
				CONSTRAINT t_trx_log_data_pkey PRIMARY KEY ("id")
			);
			
			COMMENT ON COLUMN t_trx_log_data.log_activity_id IS \'Reference to table t_trx_log_activity field id\';
			
			CREATE TABLE IF NOT EXISTS t_trx_log_data_detail (
				id bigserial NOT NULL,
				log_data_id int4,
				log_data_detail_field varchar COLLATE "default",
				log_data_detail_value_before text COLLATE "default",
				log_data_detail_value_after text COLLATE "default",
				created_by varchar(30),
				created_on timestamp(6),
				CONSTRAINT t_trx_log_data_detail_pkey PRIMARY KEY ("id"),
				CONSTRAINT t_trx_log_data_detail_log_data_id_fkey FOREIGN KEY ("log_data_id") REFERENCES t_trx_log_data ("id") ON DELETE RESTRICT ON UPDATE CASCADE
			);
		';
		
		$this->db->query($sql);
	}
	
	private function _getPrimaryKey($table)
    {
        $this->db
			->select('pg_attribute.attname')
			->where("pg_class.oid = '$table'::regclass AND
				indrelid = pg_class.oid AND
				pg_attribute.attrelid = pg_class.oid AND 
				pg_attribute.attnum = any(pg_index.indkey)
				AND indisprimary");
        
		$pk = $this->db
			->get('pg_index, pg_class, pg_attribute')
			->row()
			->attname;
        
		return $pk;
    }
	
	public function insertData($table, $dataInserted, $checkValidity = true)
    {
        $this->_createTableLog();
		
		$insertedData = 0;
        $dbQuery = $this->db->get_where($table, $dataInserted);
        $dbResult = $dbQuery->result();
		
        if ( ! $dbQuery->num_rows() || ! $checkValidity)
		{
            $dataInserted = array_merge($dataInserted, array('created_by' => $this->tokenProfile->userId, 'created_on' => date('Y-m-d H:i:s')));
            
			$insertQuery = $this->db->insert($table, $dataInserted);

            $pkInsert = $this->db->insert_id();

            $this->db
				->set('log_data_table', $table)
				->set('log_data_pkvalue', (int) $pkInsert)
				->set('created_by', $this->tokenProfile->userId)
				->set('created_on', date('Y-m-d H:i:s'))
				->set('log_activity_id', $this->logActivity->id);
				
            $this->db->insert('t_trx_log_data');
			
            $id = $this->db->insert_id();
            
			foreach ($dataInserted as $k => $v)
			{
                $this->db
					->set('log_data_id', $id)
					->set('log_data_detail_field', $k)
					->set('log_data_detail_value_after', $v)
					->set('created_by', $this->tokenProfile->userId)
					->set('created_on', date('Y-m-d H:i:s'));
				
				$this->db->insert('t_trx_log_data_detail');
			}
            
			$insertedData++;
        }
		else
		{
            $this->rest->sendError(406, 'Can Not Insert Existing Data '.$table);
            $pkInsert = 0;
        }
        
		return $pkInsert;
    }
	
	public function updateData($table, $data, $arrWhere = '')
    {
        $this->_createTableLog();
		
		if (is_array($arrWhere))
		{
            $this->db->start_cache();
            $this->db->where($arrWhere);
            $this->db->stop_cache();
            
			$history = $this->db->get($table)->row();

            $metadata = $this->db->field_data($table);
			
			foreach ($metadata as $k => $value)
			{
                $meta[$value->name] = $value->type;
            }

            $edit = array();
			foreach ($history as $k => $value)
			{
				if (isset($data[$k]))
				{
					if ($data[$k] != $value)
                    {
						$edit[$k] = $value;
					}
                }

                if ($k == 'updated_by')
				{
                    if ($value != '')
					{
                        $edit[$k] = $value;
                    }
					else
					{
                        $edit[$k] = $history->created_by;
                    }
                }
				
				if ($k == 'updated_on')
				{
                    if ($value != '')
					{
                        $edit[$k] = $value;
                    }
					else
					{
                        $edit[$k] = $history->created_on;
                    }
                }
            }

            //$data['updated_by'] = isset($edit['updated_by']) ? $edit['updated_by'] : NULL;
          	//$data['updated_on'] = date('Y-m-d H:i:s');

          	if ( ! isset($data['updated_by']))
          	{
          		$data['updated_by'] = $edit['updated_by'];
          	}

          	if ( ! isset($data['updated_on']))
          	{
          		$data['updated_on'] = date('Y-m-d H:i:s');
          	}

            $q = $this->db->update($table, $data);
			/*
			log_message('DEBUG', "Table updateData by Hadi : " . $table);
			foreach ($data as $value) { 
				log_message('DEBUG', "Data updateData by Hadi : " . $value);
			}
			*/
			//log_message('DEBUG', "Data updateData by Hadi : " . $data);
            $return = $this->db->affected_rows();
            $this->db->flush_cache();
			
            $pk = $this->_getPrimaryKey($table);
			
            if (isset($edit))
			{
                if (count($edit))
				{
                    $this->db
						->set('log_data_table', $table)
						->set('log_data_pkvalue', (int) $history->$pk)
						->set('created_by', $edit['updated_by'])
						->set('created_on', $edit['updated_on'])
						->set('log_activity_id', $this->logActivity->id);

                    $this->db->insert('t_trx_log_data');

                    $id = $this->db->insert_id();
					
                    foreach ($edit as $k => $v)
					{
						$this->db
							->set('log_data_id', $id)
							->set('log_data_detail_field', $k)
							->set('log_data_detail_value_before', $v)
							->set('log_data_detail_value_after', $data[$k])
							->set('created_by', $edit['updated_by'])
							->set('created_on', date('Y-m-d H:i:s'));
							
                        $this->db->insert('t_trx_log_data_detail');
                    }
                }
            }
			
            return $return;
        }
		else
		{
            return 0;
        }
	}

	public function getChecklistLink($visitId, $siteId, $checklistId)
	{
		$sql = "
			SELECT
				sch_chk.*,
				polink.ewo_no,
				polink.po_description,
				site.location_id,
				sitelink.link_id,now()::DATE AS created_on
			FROM t_trx_schedule_checklist sch_chk
			JOIN t_trx_schedule sch ON sch.visit_id = sch_chk.visit_id and sch.is_deleted = 0
			JOIN t_trx_po_link polink ON polink.po_id = sch.po_id
			JOIN t_mtr_site_link sitelink ON  sitelink.link_id_int = polink.link_id_int AND sitelink.is_deleted = 0
			JOIN t_mtr_site_xl site ON site.site_id = '{$siteId}' AND site.is_deleted = 0
			WHERE
				sch_chk.visit_id = {$visitId}
			AND sch_chk.checklist_id = {$checklistId};
		";

		return $this->db
			->query($sql)
			->row();
	}

	public function pushNotifEmail($data)
	{
		$header = array(
			'email_type_id'				=> 1,
			'email_date'				=> date('Y-m-d'),
			'email_reply_to_address'	=> 'eatp-alita@alita.id',
			'email_subject'				=> $data['subject'],
			'email_content'				=> $data['content'],
			'foreign_id'				=> 1,
			'created_by'				=> $this->tokenProfile->userId,
			'created_on'				=> date('Y-m-d H:i:s')
		);

		$this->db->insert('t_msg_email_header', $header);

		$header_id = $this->db->insert_id();

		$detail = array(
			'email_id'			=> $header_id,
			'recipient_type'	=> 'to',
			'recipient_address'	=> $data['recipient_address'],
			'created_by'		=> $this->tokenProfile->userId,
			'created_on'		=> date('Y-m-d H:i:s')
		);
		
		$this->db->insert('t_msg_email_detail', $detail);
	}

	public function saveApprovalAnswer($checklist_id, $data, $is_mass, $visit_id) {
		$sql = "";
		$items = "";
		foreach ($checklist_id as $item) {
			$items .= $item.",";
		}
		$items = substr_replace($items ,"",-1);
		//log_message('DEBUG', "items=".$items);
		
		$user_id = $this->tokenProfile->userId;
		
		$this->db->where("visit_id", $visit_id);
        $this->db->where_in("checklist_id", $checklist_id);
        $this->db->where("user_id", $user_id);
        $this->db->delete("t_trx_checklist_result_approval");
        
			foreach ($checklist_id as $item) {
				/* Not Exist then insert */
				$data->user_id = $user_id;
				$data->checklist_id = $item;
				$this->db->trans_begin();
				$this->db->insert('t_trx_checklist_result_approval', $data);

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
				} else {
					$this->db->trans_commit();
				}
			}
    }
}
