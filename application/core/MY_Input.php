<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Parameter paging/sorting grid (EasyUI & DataTables) dari query string dipakai langsung
 * di ORDER BY / LIMIT pada puluhan query. Nilai yang tidak valid dibuang agar controller
 * kembali memakai nilai default-nya, sehingga tidak bisa dipakai untuk SQL injection.
 *
 * $_POST hanya disaring bila request berciri grid (ada "sort", atau "page" + "rows"),
 * karena form referensi memakai field POST "order" sebagai nomor urut.
 *
 * Selain itu, karena banyak query menyisipkan ID langsung ke SQL:
 * - segmen URL dan parameter ID (id, xxx_id, xxxId) yang berisi pola SQL injection ditolak;
 * - POST dari domain lain (header Origin/Referer) ditolak sebagai proteksi CSRF,
 *   kecuali rute API mobile yang tidak memakai sesi browser.
 */
class MY_Input extends CI_Input {

	private $_grid_rules = array(
		'sort'           => '/^[A-Za-z_][A-Za-z0-9_.]*(\s*,\s*[A-Za-z_][A-Za-z0-9_.]*)*$/',
		'order'          => '/^(asc|desc)(\s*,\s*(asc|desc))*$/i',
		'rows'           => '/^\d+$/',
		'page'           => '/^\d+$/',
		'iDisplayStart'  => '/^\d+$/',
		'iDisplayLength' => '/^-?\d+$/',
		'iSortingCols'   => '/^\d+$/',
	);

	private $_injection_pattern = '/[\'"\\\\;]|--|\/\*|\b(union|select|insert|update|delete|drop|alter|truncate|exec|pg_sleep|sleep|benchmark|information_schema|pg_catalog)\b\s|\b(or|and)\s+(not\s+)?(true|false|null|\d|\(|\'|")/i';

	private $_csrf_exempt_segments = array('mobile', 'mobile_ins', 'mobile_ins2', 'mobile_api', 'mobile_pm', 'pm_android_api');

	public function __construct()
	{
		parent::__construct();

		$uri = load_class('URI', 'core');

		$this->_block_injected_uri($uri);
		$this->_block_injected_ids($_GET);
		$this->_block_injected_ids($_POST);
		$this->_block_cross_origin_post($uri);

		$_GET = $this->_sanitize_grid_params($_GET);

		if (isset($_POST['sort']) OR (isset($_POST['page']) && isset($_POST['rows'])))
		{
			$_POST = $this->_sanitize_grid_params($_POST);
		}
	}

	private function _sanitize_grid_params($data)
	{
		foreach ($data as $key => $value)
		{
			if (isset($this->_grid_rules[$key]))
			{
				$pattern = $this->_grid_rules[$key];
			}
			elseif (preg_match('/^iSortCol_\d+$/', $key))
			{
				$pattern = '/^\d+$/';
			}
			elseif (preg_match('/^sSortDir_\d+$/', $key))
			{
				$pattern = '/^(asc|desc)$/i';
			}
			else
			{
				continue;
			}

			if ( ! is_string($value) OR ! preg_match($pattern, trim($value)))
			{
				unset($data[$key]);
			}
		}

		return $data;
	}

	private function _reject($reason)
	{
		log_message('error', 'Request ditolak ('.$reason.'): '.(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''));
		set_status_header($reason === 'csrf' ? 403 : 400);
		exit($reason === 'csrf' ? 'Forbidden' : 'Bad Request');
	}

	private function _is_id_key($key)
	{
		return preg_match('/(^|_)id(_|$)/i', $key) OR preg_match('/[a-z]Id$/', $key);
	}

	private function _block_injected_uri($uri)
	{
		foreach ($uri->segment_array() as $segment)
		{
			if (preg_match($this->_injection_pattern, urldecode($segment)))
			{
				$this->_reject('uri');
			}
		}
	}

	private function _block_injected_ids($data, $parent_is_id = FALSE)
	{
		foreach ((array) $data as $key => $value)
		{
			$is_id = ($parent_is_id || $this->_is_id_key((string) $key));

			if (is_array($value))
			{
				$this->_block_injected_ids($value, $is_id);
			}
			elseif ($is_id && preg_match($this->_injection_pattern, (string) $value))
			{
				$this->_reject('id');
			}
		}
	}

	private function _block_cross_origin_post($uri)
	{
		if ( ! isset($_SERVER['REQUEST_METHOD']) OR strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST'
			OR in_array($uri->segment(1), $this->_csrf_exempt_segments)
			OR ($uri->segment(1) === 'visitsummary' && $uri->segment(2) === 'api'))
		{
			return;
		}

		if ( ! empty($_SERVER['HTTP_ORIGIN']))
		{
			$source = $_SERVER['HTTP_ORIGIN'];
		}
		elseif ( ! empty($_SERVER['HTTP_REFERER']))
		{
			$source = $_SERVER['HTTP_REFERER'];
		}
		else
		{
			return;
		}

		$source_host = strtolower((string) parse_url($source, PHP_URL_HOST));
		$allowed = array();
		foreach (array('HTTP_HOST', 'HTTP_X_FORWARDED_HOST', 'SERVER_NAME') as $key)
		{
			if ( ! empty($_SERVER[$key]))
			{
				foreach (explode(',', $_SERVER[$key]) as $host)
				{
					$allowed[] = strtolower(preg_replace('/:\d+$/', '', trim($host)));
				}
			}
		}

		if ($source_host === '' OR ! in_array($source_host, $allowed))
		{
			$this->_reject('csrf');
		}
	}
}
