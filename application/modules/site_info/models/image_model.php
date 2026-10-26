<?php

class Image_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Resize
	 * @param $file
	 * @param $width
	 * @param $height
	 * @param $option (auto, crop, portrait, landscape, exact)
	 */
	public function resize($file, $width, $height, $option = "auto")
	{
		if (!file_exists($file) || !is_file($file)) {
			return;
		}
		
		$info = pathinfo($file);
		$extension = $info['extension'];
		
		$old_image = $file;
		$new_image = str_replace('upload/', 'upload/cache', $info['dirname'] . '/') . substr(basename($file), 0, strrpos(basename($file), '.')) . '-' . $width . 'x' . $height . '.' . $extension;
		
		$new_image = explode('upload/cache', $new_image);
		
		$cache_dir = FCPATH . 'file/upload/cache';
		
		if (!file_exists($cache_dir . '/' . $new_image[1]) || (filemtime($old_image) > filemtime($cache_dir . '/' . $new_image[1]))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image[1])));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
			
				if (!file_exists($cache_dir . $path)) {
					mkdir($cache_dir . $path, 0777);
				}
			}
			
			$image = new Image($old_image);
			$image->resizeImage($width, $height, $option);
			$image->saveImage($cache_dir . '/' . $new_image[1], 80);
		}
		
		$image_url = $this->config->base_url('file/upload/cache') . '/' . $new_image[1];

		return $image_url;
	}
}