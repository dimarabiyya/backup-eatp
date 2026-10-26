<?php
/*
  Document   : add
  Created on : Feb 1, 2016 12:01:07 PM
  Author     : tumbas
  Description: Purpose of the PHP File follows.
 */

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
?>
<form class="form-horizontal" style="margin-bottom: 0; width: 600px;" method="POST" id="ff" enctype="multipart/form-data" action="<?php echo base_url('isr_bts/engineering_approval/add_engineering_approval_new'); ?>">
  <input type="hidden" name="version_id" value="<?php echo $row->version_id; ?>">
  <div class="widget" style="margin:0px">
    <div class="widget-head"><h4 class="heading">Edit APK</h4></div>
    <div class="widget-body">
      <div class="row-fluid">
        <div class="span10">
          <div class="control-group" id="form_bpv_no">
            <label class="control-label">Version Number <span class="text-error">*</span> : </label>
            <div class="controls">
              <input name="version_number" type="text" class="span12" autofocus="" value="<?php echo $row->version_number; ?>">
            </div>
          </div>
          <div class="control-group" id="form_bpv_no">
            <label class="control-label" for="bpv_no">Release Date <span class="text-error">*</span> : </label>
            <div class="controls">
              <input  id="release_date" name="release_date" type="text" value="<?php echo $row->release_date; ?>" class="datepicker span12" readonly="" value="<?php echo $row->release_date; ?>">
            </div>
          </div>
          <div class="control-group" id="form_bpv_no">
            <label class="control-label" for="bpv_no">File APK <span class="text-error">*</span> : </label>
            <div class="controls">
              <input type="file" name="userfile" accept=".apk" class="span12">
              <?php echo anchor(base_url('download/'.$row->app_url), $row->app_url); ?>
            </div>
          </div>
          <div class="control-group" id="form_bpv_no">
            <label class="control-label" for="bpv_no">Active : </label>
            <div class="controls checkbox">
              <label>
                <input type="checkbox" name="active" value="1" <?php echo ($row->active == 1) ? 'checked' : ''; ?>>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>				    
    <div class="form-actions">
      <button type="button" class="btn btn-icon btn-primary glyphicons circle_ok submit"><i></i>Submit</button>
      <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Close</button>
    </div>
  </div>
</form>

<script>
  $(".btn-close").click(function() {
    $.fancybox.close();
  });

  $(".datepicker").datepicker({
    dateFormat: "dd M yy",
    changeMonth: true,
    numberOfMonths: 1,
    changeYear: true
  });

  $(document).ready(function() {
    $('.submit').click(function() {
      $.ajax({
        url: '<?php echo site_url('referensi/mobile_app/edit'); ?>',
        data: new FormData($('#ff')[0]),
        type: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function() {
          block.show('Saving data');
        },
        success: function(data) {
          if (data.status == 'success') {
            coolNotification(data.message, data.status);
            $('#dg').datagrid('reload');
            $.fancybox.close();
          } else {
            coolNotification(data.message, data.status);
          }
        },
        error: function() {
          coolNotification('An error occured, pleas try again or contact the administrator.', 'error');
        },
        complete: function() {
          block.close();
        }
      });
    });
  });

</script>