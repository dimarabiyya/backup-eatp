<?php
/*
  Document   : add
  Created on : Feb 1, 2016 12:01:07 PM
  Author     : tumbas
  Description: Purpose of the PHP File follows.
 */

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
error_reporting(0);
?>
<form class="form-horizontal" style="margin-bottom: 0; width: 600px;" method="POST" id="ff" enctype="multipart/form-data" action="<?php echo base_url('isr_bts/engineering_approval/add_engineering_approval_new'); ?>">
  <div class="widget" style="margin:0px">
    <div class="widget-head"><h4 class="heading">Add New APK</h4></div>
    <div class="widget-body">
      <div class="row-fluid">
        <div class="span10">
          <div class="control-group" id="form_bpv_no">
            <label class="control-label">Version Number <span class="text-error">*</span> : </label>
            <div class="controls">
              <input id="version_number" name="version_number" type="text" class="span12" autofocus="" placeholder="Example : 1.0.7">
            </div>
          </div>
          <div class="control-group" id="form_bpv_no">
            <label class="control-label" for="bpv_no">Release Date <span class="text-error">*</span> : </label>
            <div class="controls">
              <input  id="release_date" name="release_date" type="text" value="<?php echo $resi->app_issued; ?>" class="datepicker span12" readonly="">
            </div>
          </div>
          <div class="control-group" id="form_bpv_no">
            <label class="control-label" for="bpv_no">File APK <span class="text-error">*</span> : </label>
            <div class="controls">
              <input type="file" name="userfile" accept=".apk" class="span12">
            </div>
          </div>
          <div class="control-group" id="form_bpv_no">
            <label class="control-label" for="bpv_no">Active : </label>
            <div class="controls checkbox">
              <label>
                <input type="checkbox" name="active" value="1" checked="">
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
        url: '<?php echo site_url('referensi/mobile_app/add'); ?>',
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
            $('#version_number').focus();
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