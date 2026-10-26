<?php

class Download extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->_checkLoggedin();
		
		$file = $this->input->get('f');
		
		if ( ! $file)
		{
			show_404();
		}
		
		$paths = array(
			'uploads',
			'uploads/references',
			'software'
		);
		
		$filePath = '';
		
		foreach ($paths as $path)
		{
			if (file_exists($path.'/'.$file) && is_file($path.'/'.$file))
			{
				$filePath = $path.'/'.$file;
				break;
			}
		}
		
		// make sure it's a file before doing anything!
		if (is_file($filePath))
		{
			// required for IE
			if (ini_get('zlib.output_compression'))
			{
				ini_set('zlib.output_compression', 'Off');
			}

			// get the file mime type using the file extension
			$this->load->helper('file');

			$mime = get_mime_by_extension($filePath);

			// Build the headers to push out the file properly.
			header('Pragma: public');     // required
			header('Expires: 0');         // no cache
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($filePath)).' GMT');
			header('Cache-Control: private', false);
			header('Content-Type: '.$mime);  // Add the mime type from Code igniter.
			header('Content-Disposition: attachment; filename="'.basename($filePath).'"');  // Add the file name
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.filesize($filePath)); // provide file size
			header('Connection: close');
			readfile($filePath); // push it out
			exit();
		}
	}
	
	private function _checkLoggedin()
	{
		if ( ! $this->session->userdata('is_login'))
		{
			if ($this->core->is_ajax())
			{
				echo json_encode(array(
					'loggedin' => FALSE,
					'message' => 'You are not logged in. You will be redirected to login page.',
					'redirect_url' => site_url('login')
				));
				exit;
			}
			else
			{
				redirect('login');
			}
		}
	}
}
