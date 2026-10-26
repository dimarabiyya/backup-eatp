<?php
class Temp_project extends CI_Model {
	
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
    }
    
    function template($param){
        
      $data = $this->db->query('SELECT
	C .pmo_name AS "Project Template",
	B.phase_name AS "Group",
	A .milestone_name AS "Milestone",
	D .milestone_name AS "Predecessor",
	A .sla_days AS "SLA Days",
	A .user_role_name AS "User Role",
	E.status_name AS "Project Status" 
        FROM
                t_tmp_pmo_line A
        LEFT JOIN t_mtr_pmo_phase B ON A .phase_id = B.phase_id
        LEFT JOIN t_tmp_pmo_header C ON A .pmo_id = C .pmo_id
        LEFT JOIN t_mtr_pmo_milestone D ON A.predescessor=D.milestone_id
        LEFT JOIN t_mtr_pmo_status_project E ON A.project_status=E.status_id
        WHERE
                A .pmo_id ='.$param.'
        ORDER BY
                A .tmp_pmo_line_id,
                B.order_no,
                A .rowno ASC'); 
      return $data;
      //die (print_r($data));
    }
	
}