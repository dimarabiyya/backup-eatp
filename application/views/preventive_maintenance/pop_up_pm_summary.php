
<div class="innerLR">
  <h4 style="margin-top: 20px;">Preventive Maintenance Visit Summary</h4>

  <div class="row-fluid">
    <div class="span6">
      <p>Site ID: <strong><?php echo $sites['site_code']; ?></strong></p>
      <p>Site Name: <strong><?php echo $sites['site_name']; ?></strong></p>
      <p>Vendor ID: <strong><?php echo $sites['vendor_id']; ?></strong></p>
    </div>
    <div class="span6">
      <p style="text-align: right">Visit Date: <strong><?php echo date('d-M-Y', strtotime($sites['visit_date'])); ?></strong></p>
    </div>
  </div>

  <?php if (!$sites['is_code_show']) { ?>
  <div class="row-fluid">
    <div id="justification" class="span12" style="display:none;">
      
    </div>
    <?php
      if (!isset($sites['security_code_checkin'])) {
        echo "<button class='btn btn-small btn-success btn-icon glyphicons rotation_lock' onclick='generate_sc(\'checkin\', ".$sites['visit_id'].")\'><i></i>Checkin Code</button> "; 
      }
      else {
        echo "<button id='set_sc_remark_button' class='btn btn-small btn-success btn-icon glyphicons eye_open' onclick='set_sc_remark()'><i></i>Generate Security Code</button> "; 
      }
    ?>
  </div>
  <?php } ?>

  <div class="row-fluid" id="scs">
    <?php if ($sites['is_code_show']) { ?>
    <div class="span6" id="security-code-checkin">
      <span class="code-checkin">
        <?php if(isset($sites['security_code_checkin'])) : ?>
          <div>
            <div class="widget">
              <div class="widget-head">
                <h4 class="heading glyphicons rotation_lock"><i></i>Security Code Checkin</h4>
              </div>
              <div class="widget-body">
                <div class="widget-body large dashboard" style="font-size: 26pt; font-weight:bold; text-align: center; ">
                  <?php echo $sites['security_code_checkin'] ?>
                </div>
              </div>
            </div>
          </div>
        <?php endif;?>
      </span>
    </div>

    <div class="span6" id="security-code-checkout">
      <span class="code-checkout">
        <?php if(isset($sites['security_code_checkout'])) : ?>
          <!-- { echo 'Security code checkout: <strong>'.$sites['security_code_checkout'].'</strong>'; } -->
          <div>
            <div class="widget">
              <div class="widget-head">
                <h4 class="heading glyphicons rotation_lock"><i></i>Security Code Checkout</h4>
              </div>
              <div class="widget-body">
                <div class="widget-body large dashboard" style="font-size: 26pt; font-weight:bold; text-align: center; ">
                  <?php echo $sites['security_code_checkout'] ?>
                </div>
              </div>
            </div>
          </div>

        <?php endif; ?>
      </span>
    </div>
    <?php } ?>
  </div> <!-- end of row fluid untuk security code -->


  <div id="accordion" style="margin-top: 5px">
  <?php 
    function render($properties) {
      function sub_render($parent, $iterasi) {
        echo '<div class="accordion-group checklist-group-header">';
        echo '<div class="accordion-heading">';
        if($iterasi) 
          { echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#group_id_'.$parent['checklist_group_id'].'">'; }
        else
          { echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#" href="#group_id_'.$parent['checklist_group_id'].'">'; }
        echo  $parent['group_name'];
        echo '</a>';
        echo '</div>';
        echo '<div id="group_id_'.$parent['checklist_group_id'].'" class="accordion-body collapse">';
        if($iterasi) 
          { echo '<div class="accordion-inner" id="accordion_level_'.$parent['checklist_group_id'].'">'; }
        else 
          { echo '<div class="accordion-inner">'; }

        if( isset($parent['sub_group']) ) {
          $_2nd = 1;
          foreach ($parent['sub_group'] as $gg) {
            if(isset($gg['sub_group']) OR count($gg['item_checklist']) != 0)
              { sub_render($gg, false); }
          }
        }
        if($parent['item_checklist']) { ?>
          <table class="table table-bordered table-condensed checklist-items">
            <thead>
              <tr>
                <th width="25px">No</th>
                <th>Checklist Item</th>
                <th width="250px">Answer</th>
                <th width="100px">Photo</th>
              </tr>
            </thead>
            <tbody>
        <?php 
          $x = 1;
          foreach ($parent['item_checklist'] as $cc) {
            item_render($cc, $x++);
          }
        ?>
          </tbody>
        </table>

        <?php 
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
      }

      function item_render($checklist, $x) {
        echo '<tr class="pm-checklist-item" id="pm-checklist-item-'.$checklist['checklist_id'].'">';
        echo '<td>'.$x.'</td>';
        echo '<td>'.$checklist['checklist_name'].' <img src="'.base_url().'assets/images/interface-'.$checklist["interface"].'.png" style="margin: -4px 0 0 4px"></td>';
        echo '<td>'.$checklist['pm_result'].'</td>';
        echo '<td> <label style="font-weight: bold; display: none;" class="li_'.$checklist['checklist_id'].'_'.$checklist['visit_id'].'">Loading...</label><button id="ssAppender_'.$checklist['checklist_id'].'_'.$checklist['visit_id'].'" onclick="toggleShowImage('.$checklist['checklist_id'].','.$checklist['visit_id'].')" type="button" class="btn btn-small btn-icon btn-default glyphicons expand"><i></i>Show</button></td>';
        echo '</tr>';
      }

      $_1st = 1;
      foreach ($properties as $gg) {
        sub_render($gg, $_1st++);
      }
    }

    render($checklist);
  ?>
  </div>

</div>

  <div class="form-actions">
    <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Close</button>
    <script>
      $(".btn-close").click(function(){$.fancybox.close();});
    </script>
  </div>

<script type="text/javascript">

  $(document).ready(function(){
    $.each($('.checklist-group-header'), function(){
      if(!$(this).find('.checklist-items').length) {$(this).hide();}
    });
  });

  function toggleShowImage (trx_id, visit_id) {
    var ssAvailabele = $('#ss-checklist-item-'+trx_id+'-'+visit_id).length;
    if(ssAvailabele == 0) {
      $('label.li_'+trx_id+'_'+visit_id).show();
      $('button#ssAppender_'+trx_id+'_'+visit_id).hide();
      $.post(
          '<?php echo base_url()."pm/summary/getVisitImage" ?>',
          {visit_id: visit_id, checklist_id: trx_id},
          function(data){
            $('label.li_'+trx_id+'_'+visit_id).hide();
            $('button#ssAppender_'+trx_id+'_'+visit_id).show();
            if(data) {
              var uRzPRp = data;
            }
            else {
              var uRzPRp = 'Photo not available';
            }
            var ssAppender = 
              '<tr><td></td>'+
              '  <td colspan="4">'+
              '    <table class="table table-bordered table-condensed" id="ss-checklist-item-'+trx_id+'-'+visit_id+'">'+
              '      <thead>'+
              '        <tr>'+
              '          <th>Screen Capture Gallery</th>'+
              '        </tr>'+
              '      </thead>'+
              '      <tbody>'+
              '      <tr><td>'+uRzPRp+'</td></tr>'
              '      </tbody>'+
              '    </table>'+
              '  </td>'+
              '</tr>';
            $('#pm-checklist-item-'+trx_id).after(ssAppender);
            $('button#ssAppender_'+trx_id+'_'+visit_id).html('<i></i>Hide');
            $('button#ssAppender_'+trx_id+'_'+visit_id).removeClass('expand');
            $('button#ssAppender_'+trx_id+'_'+visit_id).addClass('collapse_top');
          }
        );
    } else { 
      $('#pm-checklist-item-'+trx_id).next().remove();
      $('button#ssAppender_'+trx_id+'_'+visit_id).html('<i></i>Show');
      $('button#ssAppender_'+trx_id+'_'+visit_id).removeClass('collapse_top');
      $('button#ssAppender_'+trx_id+'_'+visit_id).addClass('expand');
    }
  }
  
  function set_sc_remark(){
    $('#set_sc_remark_button').hide();
    var sc_ = '<div class="widget">'+
              '  <div class="widget-head">'+
              '    <h4 class="heading glyphicons cardio"><i></i>Justification</h4>'+
              '    <button class="pull-right btn btn-heading btn-warning btn-icon glyphicons remove_2" onclick="get_sc(false)"><i></i>Cancel</button> &nbsp;'+
              '    <button class="pull-right btn btn-heading btn-success btn-icon glyphicons rotation_lock" onclick="get_sc(true)"><i></i>Get Security Code</button>  &nbsp;'+
              '  </div>'+
              '  <div class="widget-body">'+
              '     <textarea id="remark_justification" style="width: 98.5%; resize:none"></textarea>'+
              '  </div>'+
              '</div>';
    $('#justification').html(sc_);
    $('#justification').show();
  }
  
  function get_sc(state){
    if(state == true){
      if($('textarea#remark_justification').val()) {
        if (confirm('Are You Sure Unlocking Security Code?')) {
          generate_sc(<?php echo $sites['visit_id'] ?>, $('#remark_justification').val());
        }
      }
      else {
        alert('What is your justification to create Security Code?')
      }
    } else {
      $('#justification').hide();
      $('#set_sc_remark_button').show();
    }
  }
  
  function generate_sc(visit_id,remark) {
    $.post(
      "<?php echo base_url().'pm/schedule/securitycode' ?>",
      {visit_id:visit_id,remark:remark},
      function(data) {
        var datax = jQuery.parseJSON(data);
        var _dx_1 = '<div class="span6" id="security-code-checkin">'+
                    '  <span class="code-checkin">'+
                    '      <div>'+
                    '        <div class="widget">'+
                    '          <div class="widget-head">'+
                    '            <h4 class="heading glyphicons rotation_lock"><i></i>Security Code Checkin</h4>'+
                    '          </div>'+
                    '          <div class="widget-body">'+
                    '            <div class="widget-body large dashboard" style="font-size: 26pt; font-weight:bold; text-align: center; ">'+
                                  datax.checkin+
                    '            </div>'+
                    '          </div>'+
                    '        </div>'+
                    '      </div>'+
                    '  </span>'+
                    '</div>';
        var _dx_2 = '<div class="span6" id="security-code-checkout">'+
                    '  <span class="code-checkin">'+
                    '      <div>'+
                    '        <div class="widget">'+
                    '          <div class="widget-head">'+
                    '            <h4 class="heading glyphicons rotation_lock"><i></i>Security Code Checkout</h4>'+
                    '          </div>'+
                    '          <div class="widget-body">'+
                    '            <div class="widget-body large dashboard" style="font-size: 26pt; font-weight:bold; text-align: center; ">'+
                                  datax.checkout+
                    '            </div>'+
                    '          </div>'+
                    '        </div>'+
                    '      </div>'+
                    '  </span>'+
                    '</div>';
        $('#scs').html(_dx_1).append(_dx_2);
        $('#justification').remove();
      }
    );
  }

  function popUpVisitedImage (url, caption) {
    wLeft = window.screenLeft ? window.screenLeft : window.screenX;
    wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (850 / 2);
    var top = wTop + (window.innerHeight / 2) - (510 / 2);
    return window.open(url, caption, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width="850", height="510", top=' + top + ', left=' + left);
  }

</script>