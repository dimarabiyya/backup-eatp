<?php

if ( ! function_exists('valid_email'))
{
	function valid_email($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : NULL;
	}
}