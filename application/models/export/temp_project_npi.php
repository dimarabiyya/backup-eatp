<?php
class Temp_project_npi extends CI_Model {
	
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
    }
    
    function template_npi($param){
     // generate view v_pmo_export   
      $query_view = '
          CREATE OR REPLACE VIEW v_pmo_export AS
            SELECT 
            A.pmo_name,
            A.site_id AS site_id, 
            C.milestone_name AS milestone_name, 
            B.actual_end_date AS end_date,
            C.order_no AS milestone_order,
            D.phase_name AS grup_name,
            D.order_no AS grup_order
            FROM
            t_trx_pmo_header A 
            JOIN t_trx_pmo_line B ON B.pmo_id = A.pmo_id
            JOIN t_mtr_pmo_milestone C ON C.milestone_id = B.milestone_id 
            JOIN t_mtr_pmo_phase D ON D.phase_id = C.phase_id 
            WHERE A.template_id='.$param.'
            ORDER BY A.site_id, D.order_no, C.order_no
          ';  
      $this->db->query($query_view);  
      
      //generate string crosstab query 
      $query_pivot = 'SELECT pivotcode(\'v_pmo_export\', \'site_id\', \'milestone_name\', \'end_date\', \'date\', \'milestone_order\', \'grup_name\', \'grup_order\')';
      $pivot = $this->db->query($query_pivot)->row();
      
      //generate template query
      $query_string = '
          SELECT 
          C.vendor_name AS "Vendor Name ", 
          C.vendor_id AS "Vendor ID",
          C.site_code AS "Site ID", 
          C.site_name AS "Site Name",
          A.status_project AS "Last Status",
          S.prob_group AS "Problem Group",
          S.prob_desc AS "Problem Description",
          S.prob_comn AS "Last Comment",
          S.prob_stat AS "Status",
          D.nama AS "City", 
          E.region_name AS "Region", 
          C.longitude AS "Actual Longitude", 
          C.latitude AS "Actual Latitude", 
          \'-\' AS "BSC ID", 
          G.lookup_desc AS "Morphology", 
          I.company_name AS "Tower Provider", 
          J.lookup_desc AS "Tower Type", 
          K.lookup_desc AS "Structure Type", 
          H.tower_height_mtr AS "Tower Height", 
          C.site_address AS "Site Address", 
          L.*, 
          M.model AS "BTS Model", 
          N.lookup_desc AS "BTS Mode", 
          O.lookup_desc AS "BTS Type", 
          M.bts_height AS "BTS Antenna Height", 
          \'Microwave\' AS "TX Type", 
          P.tx_height AS "TX Antenna Height", 
          P.tx_config AS "TX Equipment Config", 
          P.tx_freq AS "TX Frequency", 
          P.ant_diam AS "TX Ant. Diameter", 
          M.num_sec AS "# of Sector", 
          P.azimuth AS "Azimuth", 
          Q.carrier_conf AS "Carrier Config", 
          R.site_code_fe AS "Far End Site ID"
FROM t_trx_pmo_header A
JOIN t_tmp_pmo_header B
ON B.pmo_id = A.template_id
JOIN t_mtr_site C
ON C.site_id = A.site_id
LEFT JOIN t_mtr_lokasi D
ON D.lokasi_id = C.kabupaten
JOIN t_mtr_region E
ON E.region_id = C.region_id
LEFT JOIN t_mtr_site_l_equipment_btsinfo F
ON F.site_id = C.site_id
LEFT JOIN (SELECT * FROM t_mtr_lookup WHERE lookup_key=\'bts_morphology\') AS G
ON G.lookup_value = F.morphology::VARCHAR
LEFT JOIN t_mtr_tower_provider H
ON H.tp_id::VARCHAR = C.tp_id
LEFT JOIN t_mtr_tp_company I
ON I.company_id = H.company_id
LEFT JOIN (SELECT * FROM t_mtr_lookup WHERE lookup_key=\'tower_type\') AS J
ON J.lookup_value = H.tower_type_id::VARCHAR
LEFT JOIN (SELECT * FROM t_mtr_lookup WHERE lookup_key=\'site_type\') AS K
ON K.lookup_value = H.site_type_id::VARCHAR
JOIN ('.$pivot->pivotcode.') AS L
 ON L.site_id=A.site_id::VARCHAR
 LEFT JOIN (SELECT AA.site_id, string_agg(BB.model, \', \') AS model, string_agg(AA.height::VARCHAR, \', \') AS bts_height, COUNT(AA.site_id) AS num_sec FROM t_mtr_site_l_equipment_rf AA INNER JOIN t_mtr_site_l_equipment_rf_antenna_brand BB ON AA.model = BB.antenna_id GROUP BY AA.site_id) AS M
 ON M.site_id = A.site_id
 LEFT JOIN (SELECT * FROM t_mtr_lookup WHERE lookup_key=\'bts_mode\') AS N
 ON N.lookup_value = F.bts_mode::VARCHAR
 LEFT JOIN (SELECT * FROM t_mtr_lookup WHERE lookup_key=\'bts_type\') AS O
 ON O.lookup_value = F.bts_type::VARCHAR
 LEFT JOIN (SELECT AA.site_id, string_agg(BB.ketinggian::VARCHAR, \', \')  AS tx_height, string_agg(CC.lookup_desc, \', \')  AS tx_config, string_agg(BB.tx_frequency::VARCHAR, \', \') AS tx_freq, string_agg(BB.ant_diameter::VARCHAR, \', \') AS ant_diam, string_agg(BB.azimuth::VARCHAR, \', \') AS azimuth FROM t_mtr_site AA INNER JOIN t_mtr_site_l_equipment_mw BB ON AA.site_id = BB.site_id_ne INNER JOIN (SELECT * FROM t_mtr_lookup WHERE lookup_key=\'transmission_config_hop\') CC ON CC.lookup_value = BB.config::VARCHAR GROUP BY AA.site_id) AS P
 ON P.site_id = A.site_id
 LEFT JOIN (SELECT AA.site_id, string_agg(CC.lookup_desc, \', \') AS carrier_conf FROM t_mtr_site_l_equipment_carrier BB LEFT JOIN t_mtr_site AA ON BB.site_id = AA.site_id JOIN (SELECT * FROM t_mtr_lookup WHERE lookup_key=\'carrier_configuration\') AS CC ON CC.lookup_value = BB.configuration::VARCHAR GROUP BY AA.site_id) AS Q
 ON Q.site_id = A.site_id
 LEFT JOIN (SELECT BB.site_id_ne, string_agg(CC.site_code, \', \') AS site_code_fe FROM t_mtr_site_l_equipment_mw BB LEFT JOIN t_mtr_site AA ON AA.site_id = BB.site_id_ne JOIN t_mtr_site CC ON CC.site_id = BB.site_id_fe GROUP BY BB.site_id_ne) AS R
 ON R.site_id_ne = A.site_id
 LEFT JOIN (
SELECT       	A.site_id,
                CASE 
                WHEN (A.statuss=1 OR A.statuss=2 )THEN \'Open\'
                WHEN A.statuss=3 THEN \'Cancelled\' 
                WHEN A.statuss=0 THEN \'Closed\'
                END AS "prob_stat",    
                D.group_name AS "prob_group",
                replace (A.problem_descr, \'<br>\', \' \') AS "prob_desc",
                E.problem_detail_desc AS "prob_comn" 
                FROM t_trx_problem A
                LEFT JOIN t_mtr_site B
                ON B.site_id = A.site_id    
                LEFT JOIN t_mtr_problem_group D
                ON D.group_id = A.problem_group
                LEFT JOIN (SELECT problem_id, problem_detail_desc FROM t_trx_problem_detail WHERE problem_detail_id IN (SELECT max(problem_detail_id) FROM t_trx_problem_detail GROUP BY (problem_id))) E
                ON E.problem_id=A.problem_id
                LEFT JOIN ( SELECT string_agg(bb.user_name, \',\') AS user_name, aa.problem_id FROM t_trx_problem_recipe aa INNER JOIN t_mtr_user bb ON  aa.recipe=bb.user_id GROUP BY aa.problem_id) F
                ON F.problem_id=A.problem_id
                INNER JOIN (SELECT DISTINCT(site_id) AS site_id, max(problem_id) AS problem_id FROM t_trx_problem GROUP BY site_id) AS G
				ON G.problem_id = A.problem_id
                ORDER BY A.date_raised DESC
) AS S
ON S.site_id = A.site_id
WHERE A.template_id ='.$param;
      $data = $this->db->query($query_string); 
     // print_r(nl2br($query_string));
      return $data;
      //die (print_r($data));
    }
	
}