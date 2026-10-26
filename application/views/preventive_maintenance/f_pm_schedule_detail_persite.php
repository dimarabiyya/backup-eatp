<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/jquery.noty.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/layouts/center.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/themes/default.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css"/>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/themes/default.css"/>

<div class="innerLR">
  <h4><?php echo $v151td3t41L['site']['site_code'].'('.$v151td3t41L['site']['site_name'].') '; ?></h4>
  <p><?php echo $v151td3t41L['site']['site_address']; ?></p>
  <p><?php echo $v151td3t41L['site']['region_name']; ?></p>
  <!--<p><?php echo $v151td3t41L['site']['site_id']; ?></p>
  <p><?php echo $site_id   = $this->input->get_post('site_id'); ?></p>-->

<?php $x = 0; foreach ($v151td3t41L['visit'] as $visits) : ?>

<div class="widget">
  <div class="widget-head">
    <h4 class="heading">Visit Plan: <?php echo $visits['visit_date']; ?></h4>
    <div class="pull-right">
      <?php if ($visits['status'] == 0 or $visits['status'] == 2) { ?>
        <!--<span class="btn btn-primary btn-small " id="update_<?php echo $visits['visit_id'] ?>" onclick="updateSchedule(<?php echo $visits['visit_id'].', \''.$v151td3t41L['type'].'\'' ?>)">Update</span>-->
        <span class="btn btn-primary btn-small " id="delete_<?php echo $visits['visit_id'] ?>" onclick="deleteSchedule(<?php echo $visits['visit_id'].', \''.$v151td3t41L['type'].'\'' ?>)">Delete</span>
		<!-- <span class="btn btn-inverse btn-small " id="reschedule_<?php echo $visits['visit_id'] ?>" onclick="reSchedule(<?php echo $visits['visit_id'].', \''.$v151td3t41L['type'].'\'' ?>)">Reschedule</span> -->
      <?php } ?>
    </div>
  </div>
  <div class="widget-body">
    <table class="table table-bordered table-condensed">
	<?php if ($v151td3t41L['type'] == 'inspection'){  ?>
	<tr>
        <td width="20%">User ID</td>
        <td id="user_id_<?php echo $visits['site_id']; ?>&visit_id_<?php  echo $visits['visit_id']; ?>"><?php echo '('.$visits['user_id'].') '.$visits['user_name']; ?></td>
      </tr>
	<?php }else{ ?>	
	<?php  if ($v151td3t41L['site']['site_id'] == $site_id){  ?>
      <?php if ($v151td3t41L['site']['pic_smart'] == $visits['user_id']){  ?>
      <tr>
        <td width="20%">User ID</td>
        <td id="user_id_<?php echo $visits['site_id']; ?>&visit_id_<?php  echo $visits['visit_id']; ?>"><?php echo '('.$visits['user_id'].') '.$visits['user_name']; ?></td>
      </tr>
	  <?php }else{ ?>
	  <tr>
        <td width="20%">User ID</td>
        <td id="user_id_<?php echo $visits['site_id']; ?>&visit_id_<?php  echo $visits['visit_id']; ?>">PIC Non-Default | Reason : <?php echo $visits['pic_non_default_remark']; ?></td>
      </tr>
	  <?php } } }?>
	  <tr>
        <td width="20%">Site ID</td>
        <td id="site_id_<?php echo $v151td3t41L['site']['site_code']; ?>"><?php echo $v151td3t41L['site']['site_code']; ?></td>
      </tr>
	  <tr>
        <td width="20%">Vendor ID</td>
        <td id="vendor_id_<?php echo $v151td3t41L['site']['vendor_id']; ?>"><?php echo $v151td3t41L['site']['vendor_id']; ?></td>
      </tr>
	  <?php if ($v151td3t41L['type'] != 'inspection'){  ?>
	  <tr>
        <td width="20%">Site Profile sfdf sdfsdf sdfsds df</td>
        <td id="site_profile_<?php  echo $visits['visit_id']; ?>">
		<?php 
    echo 'Profile '. $profile->lookup_desc;
			if($v151td3t41L['site']['site_profile'] == $profile->lookup_value){
				echo $profile->lookup_desc;
			}
		
		?>
		</td>
      </tr>
	  <?php }else{ ?>
	  <tr>
        <td width="20%">Candidat ID</td>
        <td id="candidat_id_<?php  echo $visits['tp_id']; ?>"><?php echo $visits['tp_id']; ?></td>
      </tr>
	  <tr>
        <td width="20%">Candidat Address</td>
        <td id="candidat_id<?php  echo $visits['address']; ?>"><?php echo $visits['address']; ?></td>
      </tr>
	  <tr>
        <td width="20%">Candidat Coordinate</td>
        <td id="candidat_coordinate<?php  echo $visits['latitude']; $visits['longitude'] ?>"><?php echo $visits['latitude']; ?>, <?php echo $visits['longitude']; ?></td>
      </tr>
	  <?php } ?>
      <tr>
        <td>Visit Type</td>
        <td id="visit_type_<?php echo $visits['visit_id']; ?>"><?php echo $visits['visit_type']; ?></td>
      </tr>
      <tr>
        <td>Priority</td>
        <td id="priority_<?php echo $visits['visit_id']; ?>"><?php echo $visits['priority']; ?></td>
      </tr>
<!--
      <tr>
        <td>Security Code Check In</td>
        <td id="checkin_<?php echo $visits['visit_id']; ?>"><?php echo $visits['security_code_checkin']; ?></td>
      </tr>
      <tr>
        <td>Security Code Check Out</td>
        <td id="checkout_<?php echo $visits['visit_id']; ?>"><?php echo $visits['security_code_checkout']; ?></td>
      </tr>
      <tr>
	  -->
        <td>Status</td>
        <td id="status_<?php echo $visits['visit_id']; ?>">
          <?php
            $satus =  $visits['status']; 
            switch ($satus) {
              case 0: echo "New schedule, not yet confirm"; break;
              case 1: echo "Confirmed Schedule"; break;
              case 2: echo "Declined Schedule"; break;
              case 3: echo "Visited Schedule"; break;
              case 4: echo "Unschedule"; break;
              case 5: echo "Rescheduled"; break;
              case -5: echo "Deleted Schedule"; break;
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
      echo '<div class="accordion-group">';
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
        <table class="table table-bordered table-condensed">
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

<script type="text/javascript">
	$(document).ready(function(){
		
	});

  function deleteSchedule(visit_id, tbl_type) {
    if(tbl_type == 'inspection') { post_url = '<?php echo site_url('/site/inspection/schedule/delete_schedule') ?>';}
    else { post_url = '<?php echo site_url('/pm/schedule/delete_schedule') ?>';}
    if (confirm('Are you sure you want to delete?')) {
		$.post(
		  post_url,
		  {visit_id: visit_id, type: tbl_type},
		  function (data) {
			var datax = jQuery.parseJSON(data);
			if(datax.status) { 
				noty_type = 'success';
			}else { 
				noty_type = 'error'; 
			}
				var n = noty({
				layout       : 'center',
				theme        : 'defaultTheme',
				type         : noty_type,
				text         : datax.message,
				dismissQueue : true,
				modal        : true,
				timeout      : 5000,
				callback: {
				  onClose: function() {
					$('#status_'+visit_id).html('Deleted Schedule');
					$('#delete_'+visit_id).remove();
				  }
			  },
			});
		   }
		);
	}
  };  
</script>