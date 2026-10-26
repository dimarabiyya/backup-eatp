<?php
 
//$conn = pg_connect("host=localhost port=5432 dbname=db_eatp user=postgres password=P05tgr#5");
$conn = pg_connect("host=localhost port=5432 dbname=db_eatp user=eatp1 password=qeirot101");

if (!$conn) {
  echo "An error occurred.\n";
  exit;
}

$result = pg_query($conn, "SELECT cc.checklist_id, cc.checklist_name,'http://eatp.alita.id/' || rsp.photo_name as photo_name,'http://eatp.alita.id/' ||rsp.photo_name FROM t_trx_schedule_checklist sc join t_mtr_checklist cc on cc.checklist_id = sc.checklist_id left join t_trx_checklist_result pr on pr.checklist_id = sc.checklist_id and sc.visit_id = pr.visit_id left join t_trx_checklist_result_photo rsp on rsp.checklist_id = sc.checklist_id and sc.visit_id = rsp.visit_id where sc.visit_id = ".$_GET["visit_id"]." and rsp.photo_name is not null;");
if (!$result) {
  echo "An error occurred.\n";
  exit;
}

while ($row = pg_fetch_row($result)) {
 echo "$row[1]";
 $ch = curl_init($row[2]);

 curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
 curl_setopt($ch, CURLOPT_HEADER, TRUE);
 curl_setopt($ch, CURLOPT_NOBODY, TRUE);

 $data = curl_exec($ch);
 $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

 curl_close($ch);
 //echo $size/1000000;
 echo "#$row[3]";
 if($size/1000000 > 1){
	 echo "<font size=\"5\" color=\"red\">RED</font><br /><br />";
 }
 echo "<br />\n";
}
 
?>