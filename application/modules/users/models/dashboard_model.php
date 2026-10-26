<?php

class Dashboard_model extends CI_Model {

  public function get_active_user_monitoring_by_region() {
    $date_from = MakeUpDate($this->input->get('date_from'));
    $date_to = MakeUpDate($this->input->get('date_to'));
    $sql = "
      SELECT his.week, office,count(DISTINCT his.user_id) AS cnt, date_part('year', visit) AS years
      FROM \"View user history\" his
      WHERE office is NOT NULL and visit BETWEEN '{$date_from}' AND '{$date_to}'
      GROUP BY his.week, office, years
      ORDER BY years, week, office";
    return $this->db->query($sql)->result();
//    $where = 'office is not null';
//    if ($this->input->get('date_from') == '' && $this->input->get('date_to') == '') {
//      $where .= " AND extract('year' FROM \"View user history\".visit) = extract('year' FROM CURRENT_DATE) AND extract('month' FROM \"View user history\".visit) = extract('month' FROM CURRENT_DATE)";
//      $start = 'CURRENT_DATE';
//      $end = 'CURRENT_DATE';
//    } else {
//      $date_from = MakeUpDate($this->input->get('date_from'));
//      $date_to = MakeUpDate($this->input->get('date_to'));
//      $where .= " AND visit BETWEEN '{$date_from}' AND '{$date_to}'";
//      $start = " TIMESTAMP '$date_from' ";
//      $end = " TIMESTAMP '$date_to' ";
//    }
//
//    return $this->db->query("WITH weeks AS (
//SELECT CONCAT('W', generate_series(EXTRACT(WEEK FROM date_trunc('week',  {$start}))::INT, EXTRACT(WEEK FROM {$end})::INT) )as week
//)
//SELECT his.week, office, count(user_id) AS cnt FROM weeks w LEFT JOIN  \"View user history\" his ON his.week = w.week WHERE office is NOT NULL GROUP BY his.week, office ORDER BY week, office;")->result();
//    return $this->db->query("SELECT office, week,  count(*) as cnt FROM  \"View user history\" WHERE {$where} GROUP BY office, week ORDER BY office ASC, substr(week, 2)::INTEGER ASC")->result();
  }

  public function get_users_attempt_monitoring() {

    $date_from = MakeUpDate($this->input->get('date_from'));
    $date_to = MakeUpDate($this->input->get('date_to'));

    $where = '';
    $region = $this->input->get('region');
    $main_division = $this->input->get('main_division');

    if ($region && $main_division) {
      $region = $this->db->escape_like_str(strtolower($region));
      $threshold = " f_get_user_attemp_threshold_by_reg_div(tw.years::INT, tw.week::INT,'{$region}', {$main_division}) ";
      $where .= " AND LOWER(office) = '{$region}' AND main_division_id = {$main_division}";
    } else if ($region || $main_division) {
      if ($region) {
        $region = $this->db->escape_like_str(strtolower($region));
        $where .= " AND LOWER(office) = '{$region}'";
        $threshold = " f_get_user_attemp_threshold_by_region(tw.years::INT, tw.week::INT, '{$region}') ";
      }

      if ($main_division) {
        $main_division = $this->db->escape_like_str($main_division);
        $where .= " AND main_division_id = {$main_division}";
        $threshold = " f_get_user_attemp_threshold_by_division(tw.years::INT, tw.week::INT, {$main_division}) ";
      }
    } else {
      $threshold = ' f_get_user_attemp_threshold(tw.years::INT, tw.week::INT) ';
    }

    $sql = "SELECT tw.*, 'Attempt' AS series_name, sum(login_count) AS series_data,
             {$threshold} AS threshold
             FROM (
             SELECT date_part('week', date_trunc('week', dates.d)) AS week, date_part('year', date_trunc('week', dates.d)) AS years
              from generate_series('{$date_from}'::date, '{$date_to}', '1 day') as dates(d)
              group by 2,1
             ) as tw
             LEFT JOIN \"View user history\" his ON his.week = 'W'||tw.week AND visit BETWEEN '{$date_from}' AND '{$date_to}' {$where}
             GROUP BY tw.week, tw.years, threshold
             ORDER BY tw.years ASC, tw.week ASC";
    return $this->db->query($sql)->result();

//    return $this->db
//                    ->query("
//				SELECT
//					'Attempt' AS series_name,
//					week AS categories,
//					sum(login_count) AS series_data,
//                    date_part('year', visit) AS period,
//                    date_part('month', visit) AS months,
//                    f_get_user_attemp_threshold(date_part('year', visit)::INT, date_part('month', visit)::INT) AS threshold
//				FROM \"View user history\"
//				WHERE {$where}
//				GROUP BY week, period, months
//				/*UNION
//				SELECT 'Threshold' AS series_name, 'W9' AS categories, 321 AS series_data
//				UNION
//				SELECT 'Threshold' AS series_name, 'W10' AS categories, 421 AS series_data
//				UNION
//				SELECT 'Threshold' AS series_name, 'W11' AS categories, 521 AS series_data
//				ORDER BY categories ASC*/
//				ORDER BY period ASC, substr(week, 2)::INTEGER ASC
//			")
//                    ->result();
  }

  public function get_users_active_monitoring() {
//    $where = '';
//    if ($this->input->get('date_from') == '' && $this->input->get('date_to') == '') {
//      $where = "extract('year' FROM \"View user history\".visit) = extract('year' FROM CURRENT_DATE) AND extract('month' FROM \"View user history\".visit) = extract('month' FROM CURRENT_DATE)";
//    } else {
    $date_from = MakeUpDate($this->input->get('date_from'));
    $date_to = MakeUpDate($this->input->get('date_to'));
//      $where = "visit BETWEEN '{$date_from}' AND '{$date_to}'";
//    }

    $sql = "SELECT tw.*, 'Active (Logged In) Users' AS series_name, count(DISTINCT user_id) AS series_data,  f_get_user_active_threshold(tw.years::INT, tw.week::INT) AS threshold
            FROM (
           SELECT date_part('week', date_trunc('week', dates.d)) AS week, date_part('year', date_trunc('week', dates.d)) AS years
           from generate_series('{$date_from}'::date, '{$date_to}', '1 day') as dates(d)
           group by 2,1
            ) as tw
            LEFT JOIN \"View user history\" his ON his.week = 'W'||tw.week AND visit BETWEEN '{$date_from}' AND '{$date_to}'
            GROUP BY tw.week, tw.years, threshold
            ORDER BY tw.years ASC, tw.week ASC";
    return $this->db->query($sql)->result();
//    return $this->db
//                    ->query("
//				SELECT
//					'Active (Logged In) Users' AS series_name,
//					week AS categories,
//					count(DISTINCT user_id) AS series_data,
//                    date_part('year', visit) AS period,
//                    date_part('month', visit) AS months,
//                    f_get_user_active_threshold(date_part('year', visit)::INT, date_part('month', visit)::INT) AS threshold
//				FROM \"View user history\"
//				WHERE {$where}
//				GROUP BY week, period, months
//				ORDER BY substr(week, 2)::INTEGER ASC
//			")
//                    ->result();
  }

  public function get_users_active_by_group_division() {
    $date_from = MakeUpDate($this->input->get('date_from'));
    $date_to = MakeUpDate($this->input->get('date_to'));

    $sql = "SELECT COUNT(DISTINCT(his.user_id)), week, date_part('year', visit) AS years, user_div_name AS div_name --COALESCE(user_div_name, 'Sys Admin') AS div_name
            FROM \"View user history\" his
            JOIN t_mtr_user us ON us.user_id = his.user_id -- AND us.internal_user = 1 AND us.status=1
            JOIN t_mtr_user_division divi ON divi.user_div_id = us.main_division_id
            WHERE visit BETWEEN '{$date_from}' AND '{$date_to}'
            GROUP BY years, week, user_div_name
            ORDER BY div_name, years ASC, week ASC";
    return $this->db->query($sql)->result();
//    return $this->db
//                    /* ->query("
//                      SELECT
//                      div_name AS categories,
//                      week AS series_name,
//                      count(*) AS series_data
//                      FROM \"View user history\"
//                      WHERE
//                      extract('year' FROM visit) = extract('year' FROM CURRENT_DATE)
//                      AND
//                      extract('month' FROM visit) = extract('month' FROM CURRENT_DATE)
//                      GROUP BY div_name, week
//                      ORDER BY substr(week, 2)::INTEGER ASC
//                      ") */
//                    ->query("
//				SELECT
//					div_name AS categories,
//					week AS series_name,
//					count(*) AS series_data
//				FROM \"View user history\"
//				WHERE
//					extract('year' FROM visit) = extract('year' FROM CURRENT_DATE)
//					AND
//					extract('month' FROM visit) = extract('month' FROM CURRENT_DATE)
//				GROUP BY div_name, week
//				--ORDER BY substr(week, 2)::INTEGER;
//
//				/*UNION
//
//				SELECT user_group AS categories, 'User Registered' AS series_name, count(*) AS series_data
//				FROM v_user_register
//				WHERE
//					extract('year' FROM date) = 2014--extract('year' FROM CURRENT_DATE)
//					--AND
//					--extract('month' FROM date) = extract('month' FROM CURRENT_DATE)
//				GROUP BY user_group, extract('week' from date)
//				--ORDER BY extract('week' from date) ASC*/
//			")
//                    ->result();
  }

  public function get_procentage_users_active_by_group_division() {
    return $this->db
                    ->query("
				SELECT
					\"View user history\".region_access,
					\"View user history\".week,
					count(*) AS cnt
				FROM
					\"View user history\"
				WHERE
					extract('year' FROM \"View user history\".visit) = extract('year' FROM CURRENT_DATE)
					AND
					extract('month' FROM \"View user history\".visit) = extract('month' FROM CURRENT_DATE)
				GROUP BY
					\"View user history\".region_access,
					\"View user history\".week
				ORDER BY
					\"View user history\".region_access,
					substr(\"View user history\".week, 2)::INTEGER ASC
			")
                    ->result();
  }

  public function get_regions() {
    $this->db->select('region_name');
    $this->db->where('status', 1);
    $this->db->order_by('order_no', 'asc');
    $query = $this->db->get('t_mtr_region');

    return $query->result();
  }

  public function get_divisions() {
    $this->db->select('user_div_name');
    $this->db->where('status', 1);
    $this->db->order_by('user_div_name', 'asc');
    $query = $this->db->get('t_mtr_user_division');
    return $query->result();
//    $sql = "SELECT DISTINCT user_div_name 
//            FROM t_mtr_user_division divi
//            JOIN t_mtr_user us ON us.main_division_id = divi.user_div_id
//            ORDER BY user_div_name ASC";
//    return $this->db->query($sql)->result();
  }

  public function get_weeks() {
    $date_from = MakeUpDate($this->input->get('date_from'));
    $date_to = MakeUpDate($this->input->get('date_to'));
    $sql = "select CONCAT('W',date_part('week', date_trunc('week', dates.d))) AS week, date_trunc('week', dates.d)::DATE AS dt,
            date_part('year', date_trunc('week', dates.d)) AS years,   date_part('week', date_trunc('week', dates.d)) AS week2
            from generate_series('{$date_from}'::date, '{$date_to}', '1 day') as dates(d)
            group by 1,2,3,4
            order by dt ASC, years ASC";
    $query = $this->db->query($sql);

    return $query->result();
  }

  public function get_user_registered_by_region() {
    $date = MakeUpDate($this->input->get('date_to'));
    $sql = "SELECT COUNT(user_id), f_grouped_user_region(user_id) AS region
            FROM t_mtr_user
            WHERE internal_user = 1 AND status=1 AND created_on <= '{$date}'
            GROUP BY region";
    $query = $this->db->query($sql);

    return $query->result();
  }

  public function get_user_registered_by_division() {
    $date = MakeUpDate($this->input->get('date_to'));
    $sql = "SELECT COUNT(user_id), f_get_user_main_division(user_id) AS division
            FROM t_mtr_user
            WHERE internal_user = 1 AND status=1 AND created_on <= '{$date}'
            GROUP BY division";
    $query = $this->db->query($sql);

    return $query->result();
  }

  public function get_user_current_login() {
    $sql = "SELECT COUNT(DISTINCT lg.user_id) AS count, dd.user_div_name AS div_name 
            FROM t_mtr_user uu
            JOIN t_mtr_user_division dd ON dd.user_div_id = uu.main_division_id
            JOIN t_log lg ON lg.user_id = uu.user_id AND log_key = 'user_login' AND lg.created_on::DATE = CURRENT_DATE
            WHERE is_login = 1 AND uu.status = 1 -- AND internal_user = 1
            GROUP BY dd.user_div_name";

    return $this->db->query($sql)->result();
  }

  public function get_user_current_login_list() {
    $sql = "SELECT uu.user_id, user_name, user_div_name AS div_name, to_char(MAX(lg.created_on), 'DD FMMonth YYYY HH24:MI:SS')AS last_login
            FROM t_mtr_user uu
            JOIN t_log lg ON lg.user_id = uu.user_id AND log_key = 'user_login' AND lg.created_on::DATE = CURRENT_DATE
            JOIN t_mtr_user_division dd ON dd.user_div_id = uu.main_division_id
            WHERE is_login = 1 AND uu.status = 1
            GROUP BY uu.user_id, user_name, user_div_name -- AND internal_user = 1
            ORDER BY last_login DESC";
    return $this->db->query($sql)->result();
  }

  public function get_main_division_list() {
    $sql = "SELECT * FROM t_mtr_user_main_division ORDER BY main_div_name ASC";
    return $this->db->query($sql)->result();
  }

}