<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "main";
$route['404_override'] = '';

/*
 * Start preventive maintainance android API
 */

$route['mobile_api/login'] = "mobile_api/login";
$route['pm_android_api/login'] = "pm_android_api/login";

/*
 * End preventive maintainance android API
 */


$route["pm"]                                     = "preventive_maintenance/pm_schedule";
$route["pm/schedule"]                            = "preventive_maintenance/pm_schedule";
$route["pm/schedule/(:any)"]                     = "preventive_maintenance/pm_schedule/$1";
$route["pm/schedule/(:any)/(:any)"]              = "preventive_maintenance/pm_schedule/$1/$2";
$route["pm/summary"]                             = "preventive_maintenance/pm_summary";
$route["pm/summary/(:any)"]                      = "preventive_maintenance/pm_summary/$1";
$route["pm/desktop"]                             = "preventive_maintenance/pm_desktop";
$route["pm/desktop/(:any)"]                      = "preventive_maintenance/pm_desktop/$1";

$route["site/inspection"]                        = "si/si_schedule";
$route["site/inspection/schedule"]               = "si/si_schedule";
$route["site/inspection/schedule/(:any)"]        = "si/si_schedule/$1";
$route["site/inspection/schedule/(:any)/(:any)"] = "si/si_schedule/$1/$2";
$route["site/inspection/summary"]                = "si/si_summary";
$route["site/inspection/summary/(:any)"]         = "si/si_summary/$1";


$handle = opendir(APPPATH.'modules');
if ($handle) {
	while ( false !== ($module = readdir($handle)) ) {
		// make sure we don't map silly dirs like .svn, or . or ..
		
		if (substr($module, 0, 1) != ".") {
			if ( file_exists(APPPATH.'modules/'.$module.'/config/routes.php') ) {
				include(APPPATH.'modules/'.$module.'/config/routes.php');
			}
			if ( file_exists(APPPATH.'modules/'.$module.'/controllers/'.$module.'.php') ) {
				$route[$module] = $module;
				$route[$module.'/(.*)'] = $module.'/$1';
			}
		}
	}
}

/* End of file routes.php */
/* Location: ./application/config/routes.php */