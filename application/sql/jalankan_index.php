<?php
/**
 * Menjalankan optimasi_index_postgres.sql dan optimasi_index_mysql.sql
 * memakai PHP (tanpa perlu psql / mysql client). Kredensial diambil dari config/database.php.
 *
 * Cara pakai (dari folder application, contoh di XAMPP Windows):
 *   C:\xampp\php\php.exe sql\jalankan_index.php            -> Postgres + MySQL
 *   C:\xampp\php\php.exe sql\jalankan_index.php postgres   -> hanya Postgres
 *   C:\xampp\php\php.exe sql\jalankan_index.php mysql      -> hanya MySQL
 *   C:\xampp\php\php.exe sql\jalankan_index.php analyze    -> hanya perbarui statistik Postgres (cepat)
 *
 * Aman dijalankan ulang: indeks yang sudah ada dilewati / dilaporkan, data tidak diubah.
 */

if (PHP_SAPI !== 'cli')
{
	exit('Jalankan dari command line.');
}

set_time_limit(0);
define('BASEPATH', TRUE);
define('APPPATH', dirname(__DIR__).DIRECTORY_SEPARATOR);
require APPPATH.'config'.DIRECTORY_SEPARATOR.'database.php';

$target = isset($argv[1]) ? strtolower($argv[1]) : 'all';

function read_statements($file)
{
	$lines = array();
	foreach (file($file) as $line)
	{
		if (substr(ltrim($line), 0, 2) !== '--')
		{
			$lines[] = $line;
		}
	}

	$statements = array();
	foreach (explode(';', implode('', $lines)) as $sql)
	{
		$sql = trim($sql);
		if ($sql !== '')
		{
			$statements[] = $sql;
		}
	}

	return $statements;
}

function short($sql)
{
	return preg_replace('/\s+/', ' ', substr($sql, 0, 110));
}

$failed = 0;

if ($target === 'all' OR $target === 'postgres' OR $target === 'analyze')
{
	$c = $db['default'];
	echo "== PostgreSQL {$c['hostname']}/{$c['database']}\n";

	if ( ! function_exists('pg_connect'))
	{
		echo "GAGAL: extension pgsql belum aktif di php.ini yang dipakai CLI.\n";
		$failed++;
	}
	else
	{
		$conn = @pg_connect("host={$c['hostname']} port={$c['port']} dbname={$c['database']} user={$c['username']} password={$c['password']} connect_timeout=10");
		if ( ! $conn)
		{
			echo "GAGAL konek ke PostgreSQL.\n";
			$failed++;
		}
		else
		{
			echo "Terhubung. Indeks di tabel besar bisa butuh beberapa menit; bila satu baris diam lama,\n";
			echo "cek dari terminal lain: C:\\xampp\\php\\php.exe sql\\cek_proses.php\n\n";

			$statements = read_statements(__DIR__.'/optimasi_index_postgres.sql');

			// Mode "analyze": hanya perbarui statistik sekarang; tabel yang sedang dikunci
			// pembuatan indeks dilewati setelah 10 detik, bukan ditunggu
			if ($target === 'analyze')
			{
				$statements = array_values(array_filter($statements, function ($sql) {
					return stripos($sql, 'ANALYZE') === 0;
				}));
				pg_query($conn, "SET lock_timeout = '10s'");
			}

			foreach ($statements as $sql)
			{
				// Buang sisa indeks INVALID (proses sebelumnya terhenti) agar bisa dibuat ulang
				if (preg_match('/CREATE INDEX CONCURRENTLY (?:IF NOT EXISTS )?(\w+)/', $sql, $m))
				{
					$invalid = @pg_query_params($conn, 'SELECT 1 FROM pg_class c JOIN pg_index i ON i.indexrelid = c.oid WHERE c.relname = $1 AND NOT i.indisvalid', array($m[1]));
					if ($invalid && pg_num_rows($invalid) > 0)
					{
						echo "...   membuang indeks rusak {$m[1]}\n";
						@pg_query($conn, 'DROP INDEX '.$m[1]);
					}
					echo "...   membuat {$m[1]}\n";
				}

				$start = microtime(TRUE);
				$ok = @pg_query($conn, $sql);

				// PostgreSQL < 9.5 belum mendukung "IF NOT EXISTS" pada CREATE INDEX
				if ( ! $ok && strpos($sql, 'IF NOT EXISTS') !== FALSE && stripos(pg_last_error($conn), 'syntax error') !== FALSE)
				{
					$ok = @pg_query($conn, str_replace('IF NOT EXISTS ', '', $sql));
					if ( ! $ok && stripos(pg_last_error($conn), 'already exists') !== FALSE)
					{
						echo "SKIP  indeks sudah ada: ".short($sql)."\n";
						continue;
					}
				}

				$time = number_format(microtime(TRUE) - $start, 1);
				if ($ok)
				{
					echo "OK    ({$time}s) ".short($sql)."\n";
				}
				else
				{
					echo "GAGAL ({$time}s) ".short($sql)."\n      -> ".trim(pg_last_error($conn))."\n";
					$failed++;
				}
			}
			pg_close($conn);
		}
	}
}

if ($target === 'all' OR $target === 'mysql')
{
	$c = $db['mysql'];
	echo "\n== MySQL {$c['hostname']}/{$c['database']}\n";

	if ( ! function_exists('mysqli_connect'))
	{
		echo "GAGAL: extension mysqli belum aktif di php.ini yang dipakai CLI.\n";
		$failed++;
	}
	else
	{
		$conn = @mysqli_connect($c['hostname'], $c['username'], $c['password'], $c['database'], (int) $c['port']);
		if ( ! $conn)
		{
			echo "GAGAL konek ke MySQL: ".mysqli_connect_error()."\n";
			$failed++;
		}
		else
		{
			foreach (read_statements(__DIR__.'/optimasi_index_mysql.sql') as $sql)
			{
				$start = microtime(TRUE);
				$ok = mysqli_query($conn, $sql);
				$time = number_format(microtime(TRUE) - $start, 1);
				if ($ok)
				{
					echo "OK    ({$time}s) ".short($sql)."\n";
				}
				elseif (mysqli_errno($conn) == 1061)
				{
					echo "SKIP  indeks sudah ada: ".short($sql)."\n";
				}
				else
				{
					echo "GAGAL ({$time}s) ".short($sql)."\n      -> ".mysqli_error($conn)."\n";
					$failed++;
				}
			}
			mysqli_close($conn);
		}
	}
}

echo "\nSelesai. Gagal: {$failed}\n";
