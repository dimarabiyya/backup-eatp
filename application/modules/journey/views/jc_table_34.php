<div id="pesan" class="pesan"> </div>

<table class="item-list" id="journey-cycle">
  <thead>
    <tr>
      <th>#</th>
      <th>Channel ID</th>
      <th>Channel Name</th>
      <th>Maintaned By</th>
      <th>Channel Type</th>
      <th>Ada JC?</th>
      <th>Add</th>
    </tr>
  </thead>
  <tbody class="the_jc">
    <?php 
      $x = 1;
      foreach ($journey as $key) {
        echo '<tr class="'.$key->user_id.'">';
        echo '<td>'.$x++.'</td>';
        echo '<td>'.$key->channel_id.'</td>';
        echo '<td>'.$key->channel_name.'</td>';
        if($key->user_id)
          { echo '<td>'.$key->user_id.' | '.$key->user_name.' | '.$key->user_group.'</td>'; }
        elseif($key->lokasi == "Mobsell")
          { echo '<td> Maintaned by Cluster </td>'; }
        else
          { echo '<td style="background: #ff0"> Unmaintaned </td>'; }
        if($key->lokasi)
          { echo '<td>'.$key->lokasi.'</td>'; }
        else 
          { echo '<td>Outlet</td>'; }

        if($key->jc9 == 1 || $key->jc8 == 1 || $key->jc31 == 1)
          { echo '<td style="background: #ACE600">Ada</td>'; }
        else
          { echo '<td>Tidak</td>'; }
        if($key->jc == 1)
          { echo '<td> <input type="checkbox" name="journey" class="jc" id="'.$key->channel_id.'_'.$key->territory_id.'_'.$date[0].$date[1].'_d'.$date[2].'" onchange="simpan(\''.$key->channel_id.'_'.$key->territory_id.'_'.$date[0].$date[1].'_d'.$date[2].'\')" checked="checked" /></td>';}
        else
          { echo '<td> <input type="checkbox" name="journey" class="jc" id="'.$key->channel_id.'_'.$key->territory_id.'_'.$date[0].$date[1].'_d'.$date[2].'" onchange="simpan(\''.$key->channel_id.'_'.$key->territory_id.'_'.$date[0].$date[1].'_d'.$date[2].'\')" /></td>';}
        echo '</tr>';
      }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="7">
        <a id="loadmore" style="display:block; text-align:center">Load More Agents</a>
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
        '<?php echo base_url(); ?>index.php/jc/journey_spv',
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