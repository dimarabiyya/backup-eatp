<?php
/**
 * Menampilkan rencana eksekusi (EXPLAIN) query halaman Detail visit & daftar visitsummary,
 * untuk melihat query mana yang masih menyapu seluruh tabel (Seq Scan). Hanya membaca.
 *
 *   C:\xampp\php\php.exe sql\explain_detail.php <visit_id> [user_id]            -> rencana saja (cepat)
 *   C:\xampp\php\php.exe sql\explain_detail.php <visit_id> [user_id] waktu      -> + waktu nyata (maks 60 dtk/query)
 *
 * visit_id = angka hasil decode URL detail, mis. .../visitsummary/detail/NTAzMTg -> 50318
 */

if (PHP_SAPI !== 'cli')
{
	exit('Jalankan dari command line.');
}

$visit_id = isset($argv[1]) ? (int) $argv[1] : 0;
if ($visit_id <= 0)
{
	exit("Pakai: C:\\xampp\\php\\php.exe sql\\explain_detail.php <visit_id> [user_id] [waktu]\n");
}
$user_id = (isset($argv[2]) && $argv[2] !== 'waktu') ? $argv[2] : 'admin';
$with_time = in_array('waktu', $argv);

define('BASEPATH', TRUE);
define('APPPATH', dirname(__DIR__).DIRECTORY_SEPARATOR);
require APPPATH.'config'.DIRECTORY_SEPARATOR.'database.php';

$c = $db['default'];
$conn = @pg_connect("host={$c['hostname']} port={$c['port']} dbname={$c['database']} user={$c['username']} password={$c['password']} connect_timeout=10");
if ( ! $conn)
{
	exit("GAGAL konek ke PostgreSQL {$c['hostname']}.\n");
}
pg_query($conn, "SET statement_timeout = '60s'");
$u = pg_escape_string($conn, $user_id);

$queries = array(
	'1. Checklist + foto + approval (inti halaman Detail)' => "
		SELECT cc.checklist_id, pr.checklist_result, rsp.photo_name,
		  (SELECT ap2.status FROM t_trx_checklist_result_approval ap2
		    WHERE visit_id = {$visit_id} AND ap2.user_id = '{$u}' LIMIT 1) AS status,
		  gp.group_path
		FROM t_trx_schedule_checklist sc
		JOIN t_mtr_checklist cc ON cc.checklist_id = sc.checklist_id
		LEFT JOIN (SELECT g.checklist_group, f_get_checklistgroup(g.checklist_group) group_path
		             FROM (SELECT DISTINCT c2.checklist_group FROM t_trx_schedule_checklist s2
		                   JOIN t_mtr_checklist c2 ON c2.checklist_id = s2.checklist_id
		                   WHERE s2.visit_id = {$visit_id}) g
		          ) gp ON gp.checklist_group IS NOT DISTINCT FROM cc.checklist_group
		LEFT JOIN t_trx_checklist_result pr ON pr.checklist_id = sc.checklist_id AND sc.visit_id = pr.visit_id
		LEFT JOIN t_trx_checklist_result_photo rsp ON rsp.checklist_id = sc.checklist_id AND sc.visit_id = rsp.visit_id
		LEFT JOIN (SELECT visit_id, image_sign FROM t_trx_schedule_sign
		            WHERE sign_type = 2 AND visit_id = {$visit_id} ORDER BY created_on DESC LIMIT 1) ttd ON sc.visit_id = ttd.visit_id
		LEFT JOIN (SELECT visit_id, fop_photo FROM t_trx_schedule_fop_photo
		            WHERE visit_id = {$visit_id} ORDER BY created_on DESC LIMIT 1) fop ON sc.visit_id = fop.visit_id
		LEFT JOIN (SELECT visit_id, fop_video FROM t_trx_schedule_fop_video
		            WHERE visit_id = {$visit_id} ORDER BY created_on DESC LIMIT 1) fv ON sc.visit_id = fv.visit_id
		WHERE sc.visit_id = {$visit_id}
		ORDER BY cc.checklist_group, cc.order_number",

	'2. Pilihan jawaban checklist' => "
		SELECT sc.checklist_id, ca.option_id FROM t_trx_schedule_checklist sc
		JOIN t_mtr_checklist_answer ca ON ca.checklist_id = sc.checklist_id
		WHERE ca.active = 1 AND sc.visit_id = {$visit_id}",

	'3. Riwayat tracking' => "
		SELECT a.*, c.user_name, d.site_code FROM t_trx_tracking a
		LEFT JOIN t_trx_schedule b ON b.visit_id = a.jc_id
		LEFT JOIN t_mtr_user c ON c.user_id = b.pic_id
		LEFT JOIN t_mtr_site d ON d.site_id = a.jc_id
		WHERE a.jc_id = '{$visit_id}' ORDER BY updated_on DESC LIMIT 1",

	'4. Cek hak approval user' => "
		SELECT * FROM t_trx_schedule_result_approval WHERE visit_id = {$visit_id} AND user_id = '{$u}'",

	'5. Hitung approval checklist user sebelumnya' => "
		SELECT COUNT(*) FROM (SELECT DISTINCT checklist_id FROM t_trx_checklist_result_approval
		 WHERE visit_id = {$visit_id} AND user_id = '{$u}' AND status = 1) t",

	'6. Info schedule' => "
		SELECT sch.*, lk.link_id FROM t_trx_schedule sch
		LEFT JOIN t_trx_po_xl po ON sch.po_id = po.po_id
		LEFT JOIN t_mtr_site_link lk ON sch.link_id = lk.link_id_int
		LEFT JOIN t_trx_po_link poli ON sch.po_id = poli.po_id AND sch.link_id = poli.link_id_int
		WHERE sch.is_deleted = 0 AND sch.visit_id = {$visit_id}",

	'7. Daftar visitsummary (halaman setelah login, tanpa filter)' => "
		SELECT DISTINCT ON (c.po_no, a.created_on) a.visit_id, c.po_no, b.ewo_no, d.link_id, g.user_name, i.region_name
		FROM t_trx_schedule a
		LEFT JOIN t_trx_po_link b ON b.po_id = a.po_id
		LEFT JOIN t_trx_po_xl c ON c.po_id = a.po_id
		LEFT JOIN t_mtr_site_link d ON d.link_id_int = a.link_id
		LEFT JOIN t_mtr_site_xl e ON d.site_id_fe = e.site_id
		LEFT JOIN t_mtr_site_xl f ON d.site_id_ne = f.site_id
		LEFT JOIN t_mtr_user g ON g.user_id = a.pic_id
		LEFT JOIN t_mtr_checklist_options h ON h.option_id = a.visit_type::integer
		LEFT JOIN t_mtr_region_xl i ON i.region_id = a.region_id
		WHERE (a.is_deleted IS NULL OR a.is_deleted = 0 AND c.is_deleted = 0) AND a.status != 3
		ORDER BY c.po_no, a.created_on DESC LIMIT 10",
);

$big_tables = array('t_trx_checklist_result_approval', 't_trx_schedule_checklist', 't_trx_checklist_result',
	't_trx_checklist_result_photo', 't_trx_tracking');

foreach ($queries as $title => $sql)
{
	echo "\n==================== {$title}\n";
	$explain = $with_time ? 'EXPLAIN (ANALYZE, BUFFERS) ' : 'EXPLAIN ';
	$start = microtime(TRUE);
	$res = @pg_query($conn, $explain.$sql);
	$time = number_format(microtime(TRUE) - $start, 1);
	if ( ! $res)
	{
		echo "GAGAL ({$time}s): ".trim(pg_last_error($conn))."\n";
		continue;
	}

	while ($row = pg_fetch_row($res))
	{
		$line = $row[0];
		$flag = '';
		foreach ($big_tables as $t)
		{
			if (stripos($line, 'Seq Scan on '.$t) !== FALSE)
			{
				$flag = '   <<<< LAMBAT: tabel besar dibaca penuh';
			}
		}
		echo $line.$flag."\n";
	}
	if ($with_time)
	{
		echo "-- waktu: {$time}s\n";
	}
}

echo "\nSelesai.\n";
