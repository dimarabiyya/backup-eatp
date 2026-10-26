<?php

class MY_Form_validation extends CI_Form_validation {
	
	public $CI;

	public function __construct()
	{
		parent::__construct();
	}
	
	public function set_error($error = '')
	{
		if (empty($error))
		{
			return FALSE;
		}
		else
		{
			$CI =& get_instance();

			$CI->form_validation->_error_array['custom_error'] = $error;

			return TRUE;
		}
	}
}