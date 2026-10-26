<?php
/**
 * Melihat apa yang sedang berjalan / menghalangi di PostgreSQL (hanya membaca, tidak mengubah apa pun).
 * Dipakai bila sql/jalankan_index.php terlihat diam lama.
 *
 *   C:\xampp\php\php.exe sql\cek_proses.php
 */

if (PHP_SAPI !== 'cli')
{
	exit('Jalankan dari command line.');
}

define('BASEPATH', TRUE);
define('APPPATH', dirname(__DIR__).DIRECTORY_SEPARATOR);
require APPPATH.'config'.DIRECTORY_SEPARATOR.'database.php';

$c = $db['default'];
$conn = @pg_connect("host={$c['hostname']} port={$c['port']} dbname={$c['database']} user={$c['username']} password={$c['password']} connect_timeout=10");
if ( ! $conn)
{
	exit("GAGAL konek ke PostgreSQL {$c['hostname']}.\n");
}

function show($conn, $title, $sql)
{
	echo "\n== {$title}\n";
	$res = @pg_query($conn, $sql);
	if ( ! $res)
	{
		echo "   (tidak bisa dibaca: ".trim(pg_last_error($conn)).")\n";
		return;
	}
	if (pg_num_rows($res) == 0)
	{
		echo "   (kosong)\n";
		return;
	}
	while ($row = pg_fetch_assoc($res))
	{
		echo "   ".implode(' | ', $row)."\n";
	}
}

$version = pg_fetch_row(pg_query($conn, 'SHOW server_version'));
echo "PostgreSQL versi {$version[0]}\n";

show($conn, 'Pembuatan indeks yang sedang berjalan', "
	SELECT pid, now() - query_start AS durasi, state, left(regexp_replace(query, '\\s+', ' ', 'g'), 90) AS query
	  FROM pg_stat_activity
	 WHERE query ILIKE 'CREATE INDEX%' AND pid <> pg_backend_pid()");

show($conn, "Koneksi 'idle in transaction' (bisa menahan CREATE INDEX CONCURRENTLY)", "
	SELECT pid, usename, client_addr, now() - xact_start AS lama_transaksi, left(regexp_replace(query, '\\s+', ' ', 'g'), 70) AS query_terakhir
	  FROM pg_stat_activity
	 WHERE state LIKE 'idle in transaction%'
	 ORDER BY xact_start");

show($conn, 'Transaksi aktif paling lama', "
	SELECT pid, usename, state, now() - xact_start AS lama_transaksi, left(regexp_replace(query, '\\s+', ' ', 'g'), 70) AS query
	  FROM pg_stat_activity
	 WHERE xact_start IS NOT NULL AND pid <> pg_backend_pid()
	 ORDER BY xact_start
	 LIMIT 10");

show($conn, 'Indeks rusak (INVALID) sisa proses yang terhenti', "
	SELECT c.relname AS indeks, t.relname AS tabel
	  FROM pg_index i
	  JOIN pg_class c ON c.oid = i.indexrelid
	  JOIN pg_class t ON t.oid = i.indrelid
	 WHERE NOT i.indisvalid");

show($conn, 'Ukuran tabel yang diberi indeks', "
	SELECT relname AS tabel, n_live_tup AS perkiraan_baris, pg_size_pretty(pg_total_relation_size(relid)) AS ukuran
	  FROM pg_stat_user_tables
	 WHERE relname IN ('t_trx_schedule_checklist','t_trx_checklist_result','t_trx_checklist_result_photo',
	                   't_trx_tracking','t_trx_schedule','t_trx_schedule_result_approval',
	                   't_trx_checklist_result_approval','ci_sessions')
	 ORDER BY pg_total_relation_size(relid) DESC");

show($conn, 'Setting pemeliharaan otomatis (autovacuum harus on, track_counts harus on)', "
	SELECT name, setting FROM pg_settings WHERE name IN ('autovacuum', 'track_counts', 'shared_buffers', 'work_mem', 'effective_cache_size')");

show($conn, 'Kapan terakhir statistik tabel diperbarui', "
	SELECT relname AS tabel, last_analyze, last_autoanalyze, last_autovacuum
	  FROM pg_stat_user_tables
	 WHERE relname IN ('t_trx_schedule_checklist','t_trx_checklist_result','t_trx_checklist_result_photo',
	                   't_trx_checklist_result_approval','t_trx_tracking','t_trx_schedule')
	 ORDER BY relname");

echo "\nSelesai.\n";
