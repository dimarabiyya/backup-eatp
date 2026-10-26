<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
?>
<script src="<?php echo base_url('assets/js/hehehe.js'); ?>"></script>
<script>
  $(document).ready(function() {
    // Submit form
    $('#submit').click(function() {
      $.ajax({
        url: '<?php echo site_url('referensi/setting/save'); ?>',
        data: $('#ff').serialize(),
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          block.show('Saving data');
        },
        success: function(resp) {
          if (typeof resp != 'undefined' && typeof resp.status != null) {
            coolNotification(resp.message, resp.status);
          } else {
            showError();
          }
        },
        error: function() {
          showError();
        },
        complete: function() {
          block.close();
        }
      });
    });

    // Test Email Connection
    $('#test_mail_connection').click(function() {
      $.ajax({
        url: '<?php echo site_url('referensi/setting/test_connection'); ?>',
        data: {host: $('#email_host').val(), port: $('#email_port').val(), test_type: 'Email'},
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          block.show('Testing email connection');
        },
        success: function(resp) {
          if (typeof resp != 'undefined' && typeof resp.status != null) {
            coolNotification(resp.message, resp.status);
          } else {
            showError();
          }
        },
        error: function() {
          showError();
        },
        complete: function() {
          block.close();
        }
      });
    });

    // Test SMS Connection
    $('#test_sms_connection').click(function() {
      $.ajax({
        url: '<?php echo site_url('referensi/setting/test_connection'); ?>',
        data: {host: $('#sms_host').val(), port: $('#sms_port').val(), test_type: 'SMS'},
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          block.show('Testing sms connection');
        },
        success: function(resp) {
          if (typeof resp != 'undefined' && typeof resp.status != null) {
            coolNotification(resp.message, resp.status);
          } else {
            showError();
          }
        },
        error: function() {
          showError();
        },
        complete: function() {
          block.close();
        }
      });
    });

    function showError() {
      coolNotification('An error occured, please try again or contact the administrator.', 'error');
    }
  });

  function loadEmailReceipt() {
    $.fancybox({
      href: '<?php echo site_url('referensi/setting/load_email_receipt'); ?>', type: 'ajax',
      minWidth: 500, autoCenter: false, scrolling: false, autoSize: true, fitToView: true, closeClick: false, openEffect: 'fade', closeEffect: 'fade', afterShow: function() {
        $('#receipt').focus();
      }
    });
  }
</script>

<div class="separator bottom"></div>
<div class="innerLR">
  <div class="widget">
    <div class="widget-head">
      <h4 class="heading glyphicons list"><i></i><?php echo $judul ?></h4>
    </div>
    <div class="widget-body">
      <form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="ff" >
        <fieldset>
          <legend>General</legend>
          <div class="row-fluid">
            <div class="control-group" id="form_user_div_name">
              <label class="control-label" for="descr">Default Password</label>
              <div class="controls"><input class="span12" id="default_password" name="default_password" type="text" value="<?php echo $default_password['Password1234']; ?>" autofocus=""/></div>
            </div>
          </div>
        </fieldset>
        <hr>
        <fieldset>
          <legend>Email</legend>
          <div class="row-fluid">
            <div class="control-group" id="form_user_div_name">
              <label class="control-label" for="descr">Host</label>
              <div class="controls"><input class="span12" name="email_configuration[host]" id="email_host" type="text" value="<?php echo $email_configuration['host']; ?>"/></div>
            </div>
          </div>
          <div class="row-fluid">
            <div class="control-group" id="form_user_div_name">
              <label class="control-label" for="descr">Port</label>
              <div class="controls"><input class="span12" name="email_configuration[port]" id="email_port" type="number" value="<?php echo $email_configuration['port']; ?>" min="0"/></div>
            </div>
          </div>
          <div class="row-fluid">
            <div class="control-group" id="form_user_div_name">
              <label class="control-label" for="descr">SSL</label>
              <div class="controls"><input class="span12" name="email_configuration[opsi_ssl]" id="email_ssl" type="number" value="<?php echo $email_configuration['opsi_ssl']; ?>" min="0"/></div>
            </div>
          </div>
          <div class="row-fluid">
            <div class="control-group" id="form_user_div_name">
              <label class="control-label" for="descr">Email Address</label>
              <div class="controls"><input class="span12" name="email_configuration[email_address]" id="email_address" type="text" value="<?php echo $email_configuration['email_address']; ?>"/></div>
            </div>
          </div>
          <div class="row-fluid">
            <div class="control-group" id="form_user_div_name">
              <label class="control-label" for="descr">Password</label>
              <div class="controls"><input class="span12" name="email_configuration[password]" id="email_password" type="password" value="<?php echo $email_configuration['password']; ?>"/></div>
            </div>
          </div>
          <div class="row-fluid">
            <div class="control-group" id="form_user_div_name">
              <label class="control-label" for="descr"></label>
              <div class="controls">
                <button class="btn" id="test_mail_connection" type="button">Test Connection</button>
                <button class="btn" id="test_sending_mail" type="button" onclick="loadEmailReceipt()">Test Sending Email</button>
              </div>
            </div>
          </div>
        </fieldset>
        <hr>
        <fieldset>
          <legend>SMS</legend>
          <div class="row-fluid">
            <div class="control-group" id="form_user_div_name">
              <label class="control-label" for="descr">IP Address</label>
              <div class="controls"><input class="span12" name="sms_configuration[ip_address]" id="sms_host" type="text" value="<?php echo $sms_configuration['ip_address']; ?>"/></div>
            </div>
          </div>
          <div class="row-fluid">
            <div class="control-group" id="form_user_div_name">
              <label class="control-label" for="descr">Port</label>
              <div class="controls"><input class="span12" name="sms_configuration[port]" id="sms_port" type="number" value="<?php echo $sms_configuration['port']; ?>" min="0"/></div>
            </div>
          </div>
          <div class="row-fluid">
            <div class="control-group" id="form_user_div_name">
              <label class="control-label" for="descr"></label>
              <div class="controls">
                <button class="btn" id="test_sms_connection" type="button">Test Connection</button>
                <button class="btn" id="test_sending_sms" type="button">Test Sending SMS</button>
              </div>
            </div>
          </div>
        </fieldset>
        <div class="form-actions">
          <button type="button" class="btn btn-icon btn-primary glyphicons circle_ok" id="submit"><i></i>Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
