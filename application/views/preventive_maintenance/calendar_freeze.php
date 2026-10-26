<link rel="stylesheet" media="screen" href="<?php echo base_url()?>file/template/smashing/theme/scripts/fullcalendar/fullcalendar/fullcalendar.css" />
<style type="text/css">
  #calendar .highlight{
    background: #ff0000;
    color: #ffffff;
    font-weight: bold;
    cursor: pointer;
  }
  #calendar td:hover {
    cursor: pointer;
  }
  #calendar .selected {
    font-weight: bold;
    color: #000000;
    background-color: #00ffc0;
  }

</style>

<?php
  $ddx = array();
  $x   = 0;
  // print_r($date);
  foreach ($date as $key => $val) {
    // print_r($key->visit_date);
    $datex = explode('-', $val->visit_date);
    if($datex[2][0] == 0) {
      $datex[2] = ltrim($datex[2], '0');
    }

    $ddx[$datex[2]] = '#'; // Ambil tanggalnya aja
  } 
  // print_r(array(3=> 'dfd', 9 => 'dfdf'));
  // print_r($ddx);

echo $this->calendar->generate($year, $month, $ddx);

?>
<script type="text/javascript">
  $('#calendar .notes').parent().addClass('highlight');
</script>