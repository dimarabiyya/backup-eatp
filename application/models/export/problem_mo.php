<?php
class Problem_mo extends CI_Model {
	
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
    }
    
    function problem_mo(){
      $sql  = 'SELECT
                B.vendor_id AS "Vendor ID",
                B.site_code AS "Site ID",
                B.site_name AS "Site Name",
                CASE 
                WHEN (A.statuss=1 OR A.statuss=2 )THEN \'Open\'
                WHEN A.statuss=3 THEN \'Cancelled\' 
                WHEN A.statuss=0 THEN \'Closed\'
                END AS "Last Status",
                C.region_name AS "Region",
                D.group_name AS "Problem Group",
                REPLACE (A.problem_descr, \'<br>\', \' \') AS "Problem Description",
                REPLACE (E.problem_detail_desc, \' <br>\', \' \') AS "Last Comment",
                to_char(A.date_raised, \'DD/MM/YYYY\') AS "Problem Raise Date",
                CASE 
                WHEN (A.statuss=1 OR A.statuss=2 )THEN \'Open\'
                WHEN A.statuss=3 THEN \'Cancelled\' 
                WHEN A.statuss=0 THEN \'Closed\'
                END AS "Status (Open / Close)",
                age(A.date_raised::date) AS "Ageing (Day)",
                F.user_name AS "Escalation To",
                to_char(A.sla, \'DD/MM/YYYY\') AS "Estimated Finish Date"
                FROM t_trx_problem A
                LEFT JOIN t_mtr_site B
                ON B.site_id = A.site_id
                LEFT JOIN t_mtr_region C
                ON C.region_id = B.region_id
                LEFT JOIN t_mtr_problem_group D
                ON D.group_id = A.problem_group
                LEFT JOIN (SELECT problem_id, problem_detail_desc FROM t_trx_problem_detail WHERE problem_detail_id IN (SELECT max(problem_detail_id) FROM t_trx_problem_detail GROUP BY (problem_id))) E
                ON E.problem_id=A.problem_id
                LEFT JOIN ( SELECT string_agg(bb.user_name, \',\') AS user_name, aa.problem_id FROM t_trx_problem_recipe aa INNER JOIN t_mtr_user bb ON  aa.recipe=bb.user_id GROUP BY aa.problem_id) F
                ON F.problem_id=A.problem_id
                ORDER BY A.date_raised DESC';  
      $data = $this->db->query($sql); 
      //print_r(nl2br($sql));
      return $data;
      //die (print_r($data));
    }
	
}