<?php error_reporting(0);?>
<script src="<?php echo base_url('assets/js/hehehe.js'); ?>"></script>

<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
  <div class="widget">
    <div class="widget-head"><h4 class="heading"><?php echo $judul ?></h4></div>
    <div class="widget-body">
      <div class="row-fluid">
        <div>
          <input type='hidden' name='option_key' value='pm_visit_type' >
          <input type='hidden' name='option_id' value='<?php echo $checklist_template['option_id']; ?>' >
          <div class="control-group" id="form_name">
            <label class="control-label" for="name">Template Name</label>
            <div class="controls"><input class="span12" id="name" name="name" type="text" value="<?php echo $checklist_template['option_label']; ?>"/></div>
          </div>	

          <div class="control-group" id="form_value">
            <label class="control-label" for="value">Template Value</label>
            <div class="controls"><input class="span12" id="value" name="value" type="text" value="<?php echo $checklist_template['option_value']; ?>"/></div>
          </div>	

          <div class="control-group" id="form_order">
            <label class="control-label" for="order_number">Order Number</label>
            <div class="controls"><?php echo form_dropdown('order_number', $order_list, $checklist_template['order_number'], array('class="span12"')); ?></div>
            <!--<div class="controls"><input class="span12" id="order_number" maxlength="5" name="order_number" onKeyUp="this.value = this.value.replace(/\D/, '')" type="text"  /></div>-->
          </div>	

          <div class="form-actions">
            <button id="save-btn" type="button" class="btn btn-icon btn-primary glyphicons circle_ok" onclick="saveFormType()"><i></i>Save</button>
            <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" onclick="closeFancybox()"><i></i>Close</button>
          </div>                
        </div>
      </div>
    </div>
  </div>
</form>

<script>
              $(document).ready(function() {
                $("#menu_164").parent().addClass('active').find('.collapse').css('height', 'auto');
              });

              function saveFormType() {
                $.ajax({
                  type: "POST",
                  url: "<?php echo base_url() ?>preventive/pm_checklist_template/saveDatatype",
                  data: $('#add-form').serialize(),
                  dataType: "json",
                  beforeSend: function() {
                    $('#save-btn').attr('disabled', 'disable');
                    $.blockUI({
                      message: '<img src="<?php echo base_url('file/images/loading-snake.GIF'); ?>" width="16px"> Saving data, please wait...',
                      css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: 'black',
                        color: 'white',
                        opacity: .5,
                        'z-index': 9999,
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px'
                      }
                    });
                  },
                  success: function(data) {
                    if (data.status == 'OK') {
                      coolNotification('<h3>Success</h3>Data has been saved successfully.', 'success', function() {
                        window.location.href = "<?php echo base_url('preventive/pm_checklist_template'); ?>";
                      });
                    } else {
                      coolNotification('<h3>Error</h3>An error occured : ' + data.hasil, 'error');
                    }
                  },
                  error: function(xhr, status, message) {
                    coolNotification('<h3>Error</h3>An error occured, please call the administrator.', 'error');
                  },
                  complete: function() {
                    $('#save-btn').removeAttr('disabled');
                    $.unblockUI();
                  }
                });
                return false;
              }
</script>