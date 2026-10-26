<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Menyisipkan lapisan tema E-ATP (views/template/partials/eatp_theme.php) ke SETIAP halaman
 * HTML penuh yang dikirim ke browser: template utama, halaman yang membuat HTML sendiri
 * (form email, peta, dsb.), dan layout library Theme. Dengan begitu tampilan konsisten
 * tanpa mengedit view satu per satu.
 *
 * Tidak disentuh: respons non-HTML (Excel, PDF, JSON), unduhan file (Content-Disposition),
 * fragmen AJAX (tanpa </head>), halaman yang sudah memuat tema, dan halaman bertanda
 * "eatp-no-theme" (mis. halaman cetak).
 */
class MY_Output extends CI_Output {

	function _display($output = '')
	{
		if ($output == '')
		{
			$output = $this->final_output;
		}

		return parent::_display($this->_inject_eatp_theme($output));
	}

	private function _inject_eatp_theme($html)
	{
		if ( ! is_string($html) OR strpos($html, 'eatp-theme-layer') !== FALSE OR strpos($html, 'eatp-no-theme') !== FALSE)
		{
			return $html;
		}

		$head_end = stripos($html, '</head>');
		if ($head_end === FALSE OR ! class_exists('CI_Controller') OR ! function_exists('get_instance'))
		{
			return $html;
		}

		$headers = function_exists('headers_list') ? headers_list() : array();
		foreach ($this->headers as $header)
		{
			$headers[] = $header[0];
		}
		foreach ($headers as $header)
		{
			if ((stripos($header, 'content-type:') === 0 && stripos($header, 'html') === FALSE)
				OR stripos($header, 'content-disposition:') === 0)
			{
				return $html;
			}
		}

		$CI =& get_instance();
		if ( ! is_object($CI) OR ! isset($CI->load))
		{
			return $html;
		}

		$layer = $CI->load->view('template/partials/eatp_theme', array(), TRUE);
		$html = substr_replace($html, $layer, $head_end, 0);

		if (preg_match('/<body\b[^>]*\bclass\s*=\s*["\']/i', $html))
		{
			return preg_replace('/(<body\b[^>]*\bclass\s*=\s*["\'])/i', '$1eatp ', $html, 1);
		}
		return preg_replace('/<body\b/i', '<body class="eatp"', $html, 1);
	}
}
