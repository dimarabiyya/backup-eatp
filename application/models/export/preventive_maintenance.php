<?php
class Preventive_maintenance extends CI_Model {
	
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
    }
    
    function checklist_template($template_id){ //andedi 20141210
//      $sql = "SELECT grup_checklist AS \"Checklist Group\", checklist_name AS \"Checklist Name\", checklist_type AS \"Checklist Type\",  interface AS \"Interface\", order_number AS \"Order Number\", mandatory_answer AS \"Mandatory Answer\", mandatory_photo AS \"Mandatory Photo\"
//            FROM (
//                SELECT CASE WHEN gr.parent_id = 598 THEN cek.checklist_group ELSE gr.root_id END as checklist_group,
//                f_get_checklistgroup_by_level (cek.checklist_group, 1) AS grup_checklist, gr.group_name,
//                CASE WHEN ty.type_name NOT IN ('checkbox','radiobutton','textbox') THEN 'textbox' ELSE ty.type_name END AS checklist_type,
//                cek.checklist_name, cek.interface, cek.order_number, CASE WHEN cek.has_photo = 1 THEN 'Yes' ELSE 'No'END AS mandatory_photo,
//                CASE WHEN cek.mandatory = 1 THEN 'Yes' ELSE 'No'END AS mandatory_answer, cek.total_photo,
//                CASE WHEN gr3. LEVEL = 0 THEN gr.order_number WHEN gr3. LEVEL = 1 THEN gr2.order_number WHEN gr3. LEVEL = 2 THEN gr3.order_number END AS order_baru_1,
//                CASE WHEN gr2. LEVEL = 1 THEN gr3.order_number + 2 WHEN gr2. LEVEL = 2 THEN gr.order_number WHEN gr2. LEVEL = 3 THEN gr2.order_number END AS order_baru_2,
//                CASE WHEN gr. LEVEL = 2 THEN gr2.order_number WHEN gr. LEVEL = 3 THEN gr3.order_number WHEN gr. LEVEL = 4 THEN gr.order_number END AS order_baru_3
//                FROM t_mtr_checklist_template tpl
//                JOIN t_mtr_checklist cek ON cek.checklist_id = tpl.checklist_id
//                JOIN t_mtr_checklist_group gr ON gr.checklist_group_id = cek.checklist_group
//                JOIN t_mtr_checklist_group gr2 ON gr2.checklist_group_id = gr.parent_id
//                JOIN t_mtr_checklist_group gr3 ON gr3.checklist_group_id = gr2.parent_id
//                LEFT JOIN t_mtr_checklist_type ty ON ty.checklist_type_id = cek.checklist_type
//                WHERE cek.status = 1 AND gr.status = 1 AND ty.status = 1 AND gr.group_key = 'prev.maintenance' AND tpl.option_id = ?
//                ORDER BY cek.order_number, checklist_group, order_baru_1, order_baru_2, order_baru_3
//            ) AS tbl";  
      
      $sql = "SELECT f_get_checklistgroup_by_level (cek.checklist_group, 1) AS \"Checklist Group\", checklist_name AS \"Checklist Name\",
                COALESCE(ty.type_name, 'textbox') AS \"Checklist Type\", cek.interface AS \"Interface\", COALESCE (cek.order_number, 0) AS \"Order Number\",
                f_get_checklist_question_status(tpl.mandatory) AS \"Mandatory Answer\", f_get_checklist_photo_status(tpl.has_photo) AS \"Photo\",
                f_get_checklist_order_number (cek.checklist_group, 1) + COALESCE (cek.order_number, 0) AS temp_order
              FROM t_mtr_checklist_template tpl
               JOIN t_mtr_checklist cek ON cek.checklist_id = tpl.checklist_id
               JOIN t_mtr_checklist_type ty ON ty.checklist_type_id = cek.checklist_type
              WHERE option_id = ?
              ORDER BY temp_order ASC";
      
      $data = $this->db->query($sql, array($template_id)); 
      return $data;
    }
	
}