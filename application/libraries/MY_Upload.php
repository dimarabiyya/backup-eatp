<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pengaman global untuk semua $this->upload->do_upload():
 * file dengan ekstensi berbahaya selalu ditolak, termasuk saat allowed_types = '*'.
 */
class MY_Upload extends CI_Upload {

	public function is_allowed_filetype($ignore_mime = FALSE)
	{
		if ( ! function_exists('is_safe_upload_name'))
		{
			get_instance()->load->helper('sims');
		}

		if ( ! is_safe_upload_name($this->file_name))
		{
			return FALSE;
		}

		return parent::is_allowed_filetype($ignore_mime);
	}
}
