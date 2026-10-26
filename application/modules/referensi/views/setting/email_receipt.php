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
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="ff" enctype="multipart/form-data" action="<?php echo base_url('isr_bts/engineering_approval/add_engineering_approval_new'); ?>">
  <div class="widget" style="margin:0px">
    <div class="widget-head"><h4 class="heading">Test Sending Email</h4></div>
    <div class="widget-body">
      <div class="row-fluid">
        <div class="span10">
          <div class="control-group" id="form_bpv_no">
            <label class="control-label">Email Receipt <span class="text-error">*</span> : </label>
            <div class="controls">
              <input id="email_receipt" name="email_receipt" type="email" class="span12" autofocus="" placeholder="Input valid email">
            </div>
          </div>
        </div>
      </div>				    
      <div class="form-actions">
        <button type="button" class="btn btn-icon btn-primary glyphicons circle_ok submit2"><i></i>Submit</button>
        <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Close</button>
      </div>
    </div>
</form>

<script>
  $(".btn-close").click(function() {
    $.fancybox.close();
  });

  $(document).ready(function() {
    $('.submit2').click(function() {
      $.ajax({
        url: '<?php echo site_url('referensi/setting/send_mail'); ?>',
        data: {
          host: $('#email_host').val(),
          port: $('#email_port').val(),
          email_address: $('#email_address').val(),
          email_password: $('#email_password').val(),
          email_receipt : $('#email_receipt').val()
        },
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          block.show('Sending email');
        },
        success: function(data) {
          if (data.status == 'success') {
            $.fancybox.close();
          }
          coolNotification(data.message, data.status);
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