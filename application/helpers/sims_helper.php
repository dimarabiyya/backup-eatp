<?php


if (!defined('BASEPATH'))
  exit('No direct script access allowed');

if (!function_exists('print_rr')) {

  function print_rr($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
  }

}

if(!function_exists('tanggalan')){
    function tanggalan($tanggal) {
    $getyear = substr($tanggal, 0, 4);
    $getmonth = substr($tanggal, 5, 2);
    $getdate = substr($tanggal, 8, 2);

    switch ($getmonth) {
        case "01":
            $bulan = "Januari";
            break;

        case "02":
            $bulan = "Februari";
            break;

        case "03":
            $bulan = "Maret";
            break;

        case "04":
            $bulan = "April";
            break;

        case "05":
            $bulan = "Mei";
            break;

        case "06":
            $bulan = "Juni";
            break;

        case "07":
            $bulan = "Juli";
            break;

        case "08":
            $bulan = "Agustus";
            break;

        case "09":
            $bulan = "September";
            break;

        case "10":
            $bulan = "Oktober";
            break;

        case "11":
            $bulan = "November";
            break;

        case "12":
            $bulan = "Desember";
            break;

        default:
            $bulan = "Bulan tidak diketahui";
            break;
    }

        $hasil = $getdate . ' ' . $bulan . ' ' . $getyear;

        return $hasil;
    }
}
        
if (!function_exists('nbsp')) {

  function nbsp($str) {
    if (empty($str) || trim($str) == '') {
      return '&nbsp;';
    } else {
      return $str;
    }
  }

}

/**
 * Check if a string is serialized
 * @param string $string
 */
if (!function_exists('is_serial')) {

  function is_serial($string) {
    return (@unserialize($string) !== false);
  }

}

if (!function_exists('null_empty')) {

  function null_empty($param) {
    if (trim($param) == '') {
      return NULL;
    } else {
      return $param;
    }
  }

}
if (!function_exists('MakeUpDate')) {

  function MakeUpDate($date, $from = 'd M Y', $to = 'Y-m-d') {

    $year = date('Y', strtotime($date));
    $month = date('m', strtotime($date));
    $day = date('d', strtotime($date));
    $date = date($from, mktime(0, 0, 0, $month, $day, $year));
    $date = DateTime::createFromFormat($from, $date);
    return $date->format($to);
  }

}

if (!function_exists('MakeUpDate2')) { //

  function MakeUpDate2($date_old) {
    $arr = explode('-', $date_old);
    $date_new = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
    return $date_new;
  }

}

if (!function_exists('dms_format')) {

  /**
   * Change string to DMS format
   * 
   * {^_^) andedi 20151002
   */
  function dms_format($param = '') {
    $arr = explode(' ', $param);
    $lat_deg = $arr[0];
    $lat_min = $arr[1];
    $lat_sec = number_format($arr[2], 2);
    $lat_dir = strtoupper($arr[3]);

    return "{$lat_deg}&deg{$lat_min}'{$lat_sec}\"{$lat_dir}";
  }

}
if (!function_exists('is_safe_upload_name')) {

  /**
   * Tolak nama file upload yang bisa dieksekusi server / memicu XSS,
   * termasuk ekstensi ganda (shell.php.jpg) dan path traversal.
   */
  function is_safe_upload_name($filename) {
    $filename = (string) $filename;
    if ($filename === '' || $filename !== basename($filename) || strpbrk($filename, "\0/\\") !== FALSE) {
      return FALSE;
    }

    $blocked = array(
      'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'pht', 'phps', 'phar', 'inc',
      'cgi', 'pl', 'py', 'sh', 'bash', 'haxor', 'asp', 'aspx', 'jsp', 'exe', 'bat', 'cmd',
      'htaccess', 'htpasswd', 'user', 'ini', 'shtml', 'html', 'htm', 'svg', 'js',
    );
    $parts = explode('.', strtolower($filename));
    array_shift($parts);

    return count(array_intersect($parts, $blocked)) === 0;
  }

}
