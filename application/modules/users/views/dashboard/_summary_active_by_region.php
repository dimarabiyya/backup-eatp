<?php

/*
  Document   : _summary_active_by_region
  Created on : Jan 25, 2016 4:35:38 PM
  Author     : tumbas
  Description: Purpose of the PHP File follows.
 */

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

$thead = "<thead style='background-color:#f8f8f8'><tr><th class='center'>Region</th>
    <th class='center'>User Registered</th>";

$active_val = array();
$active_count = 0;
foreach ($active as $key => $value) {
  $thead .= "<th class='center'>{$key}</th>";
  $active_val[] = $value;
  $active_count++;
}
$thead .= "</thead>";

//die(print_r($active_val));
$sum = array();
$tbody = "<tbody>";
foreach ($category as $key => $value) {
  $tbody .= "<tr><td>{$value}</td>";
  $tbody .= "<td class='right'>{$register[$key]}</td>";
  $sum[$key][0] = $register[$key];
  $sum_i = 1;
  for ($i = 0; $i < $active_count; $i++) {
    $tbody.= "<td class='right'>" . ceil(($active_val[$i][$key] / $register[$key]) * 100) . "%</td>";
    $sum[$key][$sum_i] = $active_val[$i][$key];
    $sum_i++;
  }
//  foreach ($active_val[$key] as $value1) {
//    $tbody.= "<td>{$value1}</td>";
//  }
  $tbody .= "</tr>";
}
$tbody .= "</tbody>";
//print_r($sum);

$columns = count($sum[0]);
$sum_array = array();
for ($i = 0; $i < $columns; $i++) {
  $sum_col = 0;
  foreach ($sum as $key => $value) {
    $sum_col += $value[$i];
  }
  $sum_array[] = $sum_col;
}

$tfoot = '<tfoot style="background-color:#f8f8f8"><tr><th>Nasional</th>';
foreach ($sum_array as $key => $value) {
  if ($key == 0) {
    $tfoot .= "<th class='right'>" . $value  . "</th>";
  } else {
    $tfoot .= "<th class='right'>" . ceil(($value / $sum_array[0]) * 100) . "%</th>";
  }
}
//print_r($sum_array);
//exit();

$tfoot .= '</tr></tfoot>';

echo $thead;
echo $tbody;
echo $tfoot;
?>