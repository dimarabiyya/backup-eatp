<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('uploadDocument'))
{
    function uploadDocument($uploadFile,$filetype,$folder,$fileName='')
	{
		$CI =& get_instance();
		$resultArr = array();
		$config['max_size'] = '1024000';
		if($filetype == 'image')  $config['allowed_types'] = 'png|jpeg|jpg';
		   
		    if (!file_exists(''.$folder)) {
			    mkdir(''.$folder, 0777, true);			    
			}
		    $config['upload_path'] = ''.$folder.'/';

		if($fileName != "")
		    $config['file_name'] = $fileName;

		$CI->load->library('upload', $config);
		$CI->upload->initialize($config);

		if(!$CI->upload->do_upload($uploadFile))
		{
		    $resultArr['success'] = false;
		    $resultArr['error'] = $CI->upload->display_errors();
		}   else    {
		    $resArr = $CI->upload->data();
		    $resultArr['success'] = true;
		    $resultArr['path'] = "/".$folder."/".$resArr['file_name'];
		}
		return $resultArr;
	 }  
}