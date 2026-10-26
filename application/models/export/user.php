<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author tumbas
 */
class User extends CI_Model {

  function get_user_history($type = 'attempt') {
    $begin_date = MakeUpDate($this->input->post('date_from2'));
    $end_date = MakeUpDate($this->input->post('date_to2'));

    if ($type == 'active') {
      $sql = "SELECT DISTINCT user_id, date_part('year', date_trunc('week', visit)) AS years, week, office, region_access, user_group_name, div_name, main_division,
            SUM(login_count) AS login_count
            FROM \"View user history\" 
            WHERE visit BETWEEN '{$begin_date}' AND '{$end_date}'
            GROUP BY 1,2,3,4,5,6,7,8  
            ORDER BY 2,3,1";
    } else {
      $sql = " SELECT * FROM \"View user history\" WHERE visit BETWEEN '$begin_date' AND '$end_date' ";
    }

    return $this->db->query($sql);
  }

//  function get_user_active() {
//    $begin_date = MakeUpDate($this->input->post('date_from2'));
//    $end_date = MakeUpDate($this->input->post('date_to2'));
//    $sql = "SELECT DISTINCT user_id, date_part('year', date_trunc('week', visit)) AS years, week, office, region_access, user_group_name, div_name, main_division 
//            FROM \"View user history\" 
//            WHERE visit BETWEEN '2015-11-01' AND '2016-01-21' AND week = 'W45'
//            ORDER BY 2,3,1";
//    return $this->db->query($sql);
//  }
}

?>