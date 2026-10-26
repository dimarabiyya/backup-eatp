<div id="pesan" class="pesan"> </div>

<table class="item-list" id="journey-cycle">
  <thead>
    <tr>
      <th>#</th>
      <th>Outlet Id</th>
      <th>Outlet Name</th>
      <th>Address</th>
      <th>Add</th>
    </tr>
  </thead>
  <tbody class="the_jc">
    <?php 
      $x = 1;
      foreach ($journey as $key) {
        echo '<tr class="'.$key->outlet_id.'">';
        echo '<td>'.$x++.'</td>';
        echo '<td>'.$key->outlet_id.'</td>';
        echo '<td>'.$key->outlet_name.'</td>';
        echo '<td>'.$key->address.'</td>';
       
        // if($key->jc9 == 1 || $key->jc8 == 1 || $key->jc31 == 1)
        //   { echo '<td style="background: #ACE600">Ada</td>'; }
        // else
        //   { echo '<td>Tidak</td>'; }
        if($key->jc == 1)
          { echo '<td> <input type="checkbox" name="journey" class="jc" id="'.$key->outlet_id.'_'.$key->territory_id.'_'.$date[0].$date[1].'_d'.$date[2].'" onchange="simpan(\''.$key->outlet_id.'_'.$key->territory_id.'_'.$date[0].$date[1].'_d'.$date[2].'\')" checked="checked" /></td>';}
        else
          { echo '<td> <input type="checkbox" name="journey" class="jc" id="'.$key->outlet_id.'_'.$key->territory_id.'_'.$date[0].$date[1].'_d'.$date[2].'" onchange="simpan(\''.$key->outlet_id.'_'.$key->territory_id.'_'.$date[0].$date[1].'_d'.$date[2].'\')" /></td>';}
        echo '</tr>';
      }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="7">
        <a id="loadmore" style="display:block; text-align:center">Load More Outlets</a>
      </td>
    </tr>
  </tfoot>
</table>

<script type="text/javascript">
$(document).ready(function() {
  $('.pesan').hide();
  
  var uri      = $('#sales_group_leader').val();
  var spv_id   = $('input.spv_id').val();
  var sales_id = $('select#sales-name').val();

  var jc_type  = $('#jc-type').val();
  var mobsel   = $('input[checked]:radio').val();
  var date     = $('.datepicker').val();
  var keyword  = $('.keyword').val();

  $('#loadmore').click(function(){
  var row = $('tbody.the_jc').children().length ;

      $.post(
        '<?php echo base_url(); ?>index.php/jc/journey_merchan',
        {sales_id:sales_id, spv_id:spv_id, date:date, jc_type:jc_type, keyword:keyword, mobsel:mobsel, uri:uri, _s1:true, _s2:row, _s3:10},
        function(data) {
          var new_data = JSON.parse(data);
          $('tbody.the_jc').append(new_data.journey_summary);
          if (new_data.journey_count < 10  ) 
            { $('table#journey-cycle tfoot').hide(); }
        }
      );
  });

});
</script>