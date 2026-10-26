<?php
/**
 * Membatalkan SATU query SELECT yang macet (mis. halaman Detail yang berjalan bermenit-menit
 * dan menahan CREATE INDEX CONCURRENTLY). Hanya query SELECT yang boleh dibatalkan; data aman.
 *
 *   C:\xampp\php\php.exe sql\batal_query.php <pid>
 *
 * PID dilihat dari output sql\cek_proses.php (kolom pertama).
 */

if (PHP_SAPI !== 'cli')
{
	exit('Jalankan dari command line.');
}

$pid = isset($argv[1]) ? (int) $argv[1] : 0;
if ($pid <= 0)
{
	exit("Pakai: C:\\xampp\\php\\php.exe sql\\batal_query.php <pid>\n");
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

$res = pg_query_params($conn, 'SELECT state, now() - query_start AS durasi, query FROM pg_stat_activity WHERE pid = $1', array($pid));
$row = pg_fetch_assoc($res);
if ( ! $row)
{
	exit("PID {$pid} tidak ditemukan (mungkin sudah selesai).\n");
}

if ( ! preg_match('/^\s*SELECT\b/i', $row['query']))
{
	exit("Dibatalkan: PID {$pid} bukan query SELECT, tidak disentuh.\n".substr($row['query'], 0, 120)."\n");
}

echo "PID {$pid} | {$row['state']} | {$row['durasi']}\n".preg_replace('/\s+/', ' ', substr($row['query'], 0, 120))."\n";
$ok = pg_fetch_row(pg_query_params($conn, 'SELECT pg_cancel_backend($1)', array($pid)));
echo ($ok[0] === 't') ? "Query dibatalkan.\n" : "Gagal membatalkan.\n";
