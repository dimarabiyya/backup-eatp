<link rel="stylesheet" media="screen" href="<?php echo base_url()?>file/template/smashing/theme/scripts/fullcalendar/fullcalendar/fullcalendar.css" />
<style type="text/css">
  #calendar .highlight{
    background: #ff0000;
    color: #ffffff;
    font-weight: bold;
    cursor: pointer;
  }
  #calendar td:hover {
    background: #ffff00;
    cursor: pointer;
    color: #000;
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
  $('#calendar td .selectable').click(function(){
    window.year  = $('#opr_year').val();
    window.month = $('#opr_month').val();
    window.date  = null;
    if( $(this).hasClass('selected') ) {
      $(this).removeClass('selected');
      window.date = null;
      window.date_alert = "Pick a date";
    }
    else {
      $('#calendar td .selected').removeClass('selected');
      $(this).addClass('selected');
        window.date = $(this).html();
        window.visitdate = new Date(year, month-1, date);
    }
    checkDate();
  });
</script>