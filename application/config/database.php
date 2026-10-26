<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$active_group = 'default';
$active_record = TRUE;
$db['default']['hostname'] = "10.0.5.32";
$db['default']['username'] = "eatp1";
$db['default']['password'] = "qeirot909";
$db['default']['database'] = "db_eatp";
$db['default']['dbdriver'] = "postgre";
$db['default']['dbprefix'] = "";
$db['default']['port']     = "5432";
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = "";
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/* start edit by hadi 2019-07-24 */
$db['mysql']['hostname'] = "10.0.5.32";
$db['mysql']['username'] = "root";
$db['mysql']['password'] = "al1ta";
$db['mysql']['database'] = "import_excel";
$db['mysql']['dbdriver'] = "mysqli";
$db['mysql']['dbprefix'] = "";
$db['mysql']['port']     = "3306";
$db['mysql']['pconnect'] = TRUE;
$db['mysql']['db_debug'] = TRUE;
$db['mysql']['cache_on'] = FALSE;
$db['mysql']['cachedir'] = "";
$db['mysql']['char_set'] = "utf8";
$db['mysql']['dbcollat'] = 'utf8_general_ci';
$db['mysql']['swap_pre'] = "";
$db['mysql']['autoinit'] = TRUE;
$db['mysql']['stricton'] = FALSE;
/* end edit by hadi 2019-07-24 */

/* End of file database.php */
/* Location: ./application/config/database.php */