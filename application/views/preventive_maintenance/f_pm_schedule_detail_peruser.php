
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/jquery.noty.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/layouts/center.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/themes/default.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css"/>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/themes/default.css"/>

<div class="innerLR">
  <!-- <h3><?php echo $v151td3t41L['user']['user_name'].' ('.$v151td3t41L['user']['user_id'].')' ; ?></h3> -->

<?php $x = 0; foreach ($v151td3t41L['visit'] as $visits) : ?>

<div class="widget">
  <div class="widget-head">
    <h4 class="heading">Visit Plan: <?php echo date('d M Y', strtotime($visits['visit_date'])); ?></h4>
    <div class="pull-right">
      <?php if ($visits['status'] == 0) { ?>
        <span class="btn btn-primary btn-small" id="delete_<?php echo $visits['visit_id'] ?>" class="button_pm_delete_schedule" onclick="deleteSchedule(<?php echo $visits['visit_id'].', \''.$v151td3t41L['type'].'\'' ?>)">Cancel Schedule</span>
        <span class="btn btn-inverse btn-small" id="reschedule_<?php echo $visits['visit_id'] ?>" class="button_pm_edit_schedule" onclick="editSchedule(<?php echo $visits['visit_id'].', \''.$v151td3t41L['type'].'\'' ?>)">Edit Schedule</span>
        <span class="btn btn-inverse btn-small" id="reschedule_<?php echo $visits['visit_id'] ?>" class="button_pm_reschedule" onclick="reSchedule(<?php echo $visits['visit_id'].', \''.$v151td3t41L['type'].'\'' ?>)">Reschedule</span>
      <?php } ?>
    </div>
  </div>
  <div class="widget-body">
    <table class="table table-bordered table-condensed">
      <tr>
        <td width="20%">Assigned PIC</td>
        <td id="user_id_<?php echo $visits['visit_id']; ?>"><strong><?php echo $v151td3t41L['user']['user_name'].' ('.$v151td3t41L['user']['user_id'].')' ; ?></strong></td>
      </tr>
      <tr>
        <td width="20%">Site ID</td>
        <td id="user_id_<?php echo $visits['visit_id']; ?>"><?php echo $visits['site_code']; ?></td>
      </tr>
      <tr>
        <td width="20%">Vendor ID</td>
        <td id="user_id_<?php echo $visits['visit_id']; ?>"><?php echo $visits['vendor_id']; ?></td>
      </tr>
      <tr>
        <td width="20%">Site Name</td>
        <td id="user_id_<?php echo $visits['visit_id']; ?>"><?php echo $visits['site_name']; ?></td>
      </tr>
      <tr>
        <td>Visit Type</td>
        <td id="visit_type_<?php echo $visits['visit_id']; ?>"><?php echo $visits['visit_type']; ?></td>
      </tr>
      <tr>
        <td>Priority</td>
        <td id="priority_<?php echo $visits['visit_id']; ?>"><?php echo $visits['priority']; ?></td>
      </tr>
      
      <tr>
        <td>Status</td>
        <td id="status_<?php echo $visits['visit_id']; ?>">
          <?php
            $satus =  $visits['status']; 
            switch ($satus) {
              case 0: echo "New schedule, not yet confirm"; break;
              case 1: echo "Confirmed Schedule"; break;
              case 2: echo "Declined Schedule (<strong>".$visits['reason_group']."</strong>: ".$visits['reason_desc'].")"; break;
              case 3: echo "Visited Schedule"; break;
              case 4: echo "Unschedule"; break;
              case 5: echo "Rescheduled (<strong>".$visits['reason_group']."</strong>: ".$visits['reason_desc'].")"; break;
              case -5: echo "Deleted Schedule (<strong>".$visits['reason_group']."</strong>: ".$visits['reason_desc'].")"; break;
            }
          ?>
        </td>
      </tr>
      <tr>
        <td>Remark</td>
        <td id="remark_<?php echo $visits['visit_id']; ?>"><?php echo $visits['remark']; ?></td>
      </tr>
    </table>
    <div class="separator bottom"></div>
    <div id="accordion_<?php echo $visits['visit_id']; ?>">
      <?php 
        $_1st = 1;
        foreach ($visits['checklist'] as $cc) {
          sub_render($cc, $_1st++, $visits['visit_id']);
        }
        // sub_render($visits['checklist']);
      ?>

    </div>
    <div class="separator bottom"></div>

  </div>
</div>
<?php endforeach; ?>

<!-- </div> -->
<?php 
  // function render($properties) {
    function sub_render($parent, $iterasi, $visit_id) {
      echo '<div class="accordion-group checklist-group-header" id="checklist-group-header-'.$parent['group_id'].'">';
      echo '<div class="accordion-heading">';
      if($iterasi) 
        { echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_'.$visit_id.'" href="#group_id_'.$parent['group_id'].'_'.$visit_id.'">'; }
      else
        { echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="" href="#group_id_'.$parent['group_id'].'_'.$visit_id.'">'; }
      echo  $parent['group_name'];
      echo '</a>';
      echo '</div>';
      echo '<div id="group_id_'.$parent['group_id'].'_'.$visit_id.'" class="accordion-body in collapse">';
      if($iterasi) 
        { echo '<div class="accordion-inner" id="">'; }
      else 
        { echo '<div class="accordion-inner">'; }

      if( isset($parent['sub_group']) ) {
        $_2nd = 1;
        foreach ($parent['sub_group'] as $gg) {
          if(isset($gg['sub_group']) OR isset($gg['checklist']) )
            { sub_render($gg, false, $visit_id); }
        }
      }

      if(isset($parent['checklist']) ) { ?>
        <table class="table table-bordered table-condensed checklist-items" id="checklist-items-<?php echo $parent['group_id']; ?>">
          <thead>
            <tr>
              <th width="25px">No</th>
              <th>Checklist Item</th>
            </tr>
          </thead>
          <tbody>
      <?php 
        $x = 1;
        foreach ($parent['checklist'] as $cc) {
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
      echo '</tr>';
    }

    // $_1st = 1;
    // foreach ($properties as $gg) {
    //   sub_render($gg, $_1st++);
    // }
  // }

?>

  </div>

<div class="modal" id="canceling_schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Fill the form to canceling this schedule !</h3>
  </div>
  <div class="modal-body">
    <p id="mm"></p>
    <table class="table table-bordered table-condensed">
      <tr>
        <td width="35%">Canceling Reason</td>
        <td id="canceling_reasons">
          <select name="canceling_reason" id="canceling_reasons_dropdown">
            <option value=""></option>
            <?php 
              foreach ($cancellation as $cc) {
                echo '<option value="'.$cc->lookup_value.'">'.$cc->lookup_desc.'</option>';
              }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td width="35%">Remark</td>
        <td id="canceling_remark">
          <textarea name="canceling_remark" id="canceling_remark_textarea" rows="3" style="resize:none; margin: 0px; width: 360px"></textarea>
        </td>
      </tr>
    </table>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
    <button class="btn" onclick="cancelingSchedule(<?php echo $visits['visit_id'].', \''.$v151td3t41L['type'].'\'' ?>)">Proceed</button>
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function(){
    $.each($('.checklist-group-header'), function(){
      if(!$(this).find('.checklist-items').length) {$(this).hide();}
    });
  });

  var post_url = '';
  function deleteSchedule(visit_id, tbl_type) {
    $('#canceling_schedule').modal('show');
    if(tbl_type == 'inspection') { post_url = '<?php echo site_url("/site/inspection/schedule/delete_schedule") ?>';}
    else { post_url = '<?php echo site_url("/pm/schedule/delete_schedule") ?>';}

    // cancelingSchedule(visit_id, tbl_type, post_url);
    // var n = noty({
    //   text: 'Do you want to continue?',
    //   type: 'alert',
    //   modal: true,
    //   dismissQueue: true,
    //   layout: 'center',
    //   theme: 'defaultTheme',
    //   buttons: [
    //     {
    //       addClass: 'btn btn-primary', text: 'Ok', onClick: function($noty) {
    //         $noty.close();
    //         $.post(
    //           post_url,
    //           {visit_id: visit_id, type: tbl_type},
    //           function(data){
    //             var datax = jQuery.parseJSON(data);
    //             if(datax.status == true) {
    //               noty({
    //                 dismissQueue: false, 
    //                 force: true, 
    //                 modal: true, 
    //                 layout: 'center', 
    //                 theme: 'defaultTheme', 
    //                 text: datax.message, 
    //                 type: 'success', 
    //                 timeout: 500,
    //                 callback     : {
    //                   afterClose : function() { $('#status_'+visit_id).html('Deleted Schedule'); $('#delete_'+visit_id).remove(); window.location.href = "<?php echo base_url();?>pm/schedule";}
    //                 }
    //               });
    //             }
    //             else {
    //               noty({dismissQueue: false, force: true, modal: true, layout: 'center', theme: 'defaultTheme', text: datax.message, type: 'warning', timeout: 500});
    //             }
    //           }
    //         )
    //       }
    //     },
    //     {
    //       addClass: 'btn btn-danger', text: 'Cancel', onClick: function($noty) {
    //         $noty.close();
    //         noty({dismissQueue: false, force: true, modal: true, layout: 'center', theme: 'defaultTheme', text: 'Canceled', type: 'warning', timeout: 500});
    //       }
    //     }
    //   ]
    // });
	}

  function cancelingSchedule(visit_id, tbl_type) {
    $('#canceling_schedule').modal('hide');
    if(tbl_type == 'inspection') { post_url = '<?php echo site_url("/site/inspection/schedule/delete_schedule") ?>';}
    else { post_url = '<?php echo site_url("/pm/schedule/delete_schedule") ?>';}
    $.post(
      post_url,
      {visit_id: visit_id, type: tbl_type, canceling_remark: $('#canceling_remark_textarea').val(), canceling_reason: $('#canceling_reasons_dropdown').val()},
      function(data){
        var datax = jQuery.parseJSON(data);
        if(datax.status == true) {
          noty({
            dismissQueue: false, 
            force: true, 
            modal: true, 
            layout: 'center', 
            theme: 'defaultTheme', 
            text: datax.message, 
            type: 'success', 
            timeout: 500,
            callback     : {
              afterClose : function() { $('#status_'+visit_id).html('Deleted Schedule'); $('#delete_'+visit_id).remove(); window.location.href = "<?php echo base_url();?>pm/schedule";}
            }
          });
        }
        else {
          noty({dismissQueue: false, force: true, modal: true, layout: 'center', theme: 'defaultTheme', text: datax.message, type: 'warning', timeout: 500});
        }
      }
    );
  }

  function reSchedule(visit_id) {
    var nForm   = document.createElement("form");
        input_1 = document.createElement("input");
        input_2 = document.createElement("input");

    nForm.action = "<?php echo base_url('pm/schedule/edit'); ?>";
    nForm.method = "post";
    
    input_1.name = "visit_id";
    input_1.setAttribute("value", visit_id);
    input_2.name = "reschedule";
    input_2.setAttribute("value", true);

    nForm.appendChild(input_1);
    nForm.appendChild(input_2);
    document.body.appendChild(nForm);

    nForm.submit();
  }

  function editSchedule(visit_id) {
    var nForm   = document.createElement("form");
        input_1 = document.createElement("input");
        input_2 = document.createElement("input");

    nForm.action = "<?php echo base_url('pm/schedule/edit'); ?>";
    nForm.method = "post";
    
    input_1.name = "visit_id";
    input_1.setAttribute("value", visit_id);
    input_2.name = "reschedule";
    input_2.setAttribute("value", false);

    nForm.appendChild(input_1);
    nForm.appendChild(input_2);
    document.body.appendChild(nForm);

    nForm.submit();
  }
</script>