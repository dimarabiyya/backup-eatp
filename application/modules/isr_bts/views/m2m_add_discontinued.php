<?php //error_reporting(0);?>
<form action="<?php echo base_url('isr_bts/m2m/add_discontinued_m2m'); ?>" id="ff" class="form-horizontal" style="width: 1000px; overflow: auto;" enctype="multipart/form-data">
  <input type="hidden" name="app_id" value="<?php echo $app_header->app_id; ?>">
  <input type="hidden" name="milestone_status" value="<?php  echo $app_header->milestone_status; ?>">
  <div class="widget">
    <div class="widget-head"><h4 class="heading">Add M2M Discontinued</h4></div>
    <div class="widget-body">
      <div class="row-fluid">
        <div class="span6">
          <div class="control-group">
            <label class="control-label" for="firstname">Application No <span class="text-error">*</span> : </label>
            <div class="controls"><input class="span12" id="app_no" name="app_no" type="text" value="<?php echo $app_header->app_no; ?>" autofocus=""></div>
          </div>
          <div class="control-group">
            <label class="control-label" for="lastname">License Number <span class="text-error">*</span> : </label>
            <div class="controls"><input class="span12" id="license_number" name="license_number" type="text" value="<?php echo $app_header->license_number; ?>"></div>
          </div><div class="control-group">
            <label class="control-label" for="lastname">Client ID <span class="text-error">*</span> : </label>
            <div class="controls"><input class="span12" id="client_id" name="client_id" type="text" value="<?php echo $app_header->client_id; ?>"></div>
          </div>
        </div>
        <div class="span6">
          <div class="control-group">
            <label class="control-label" for="password">Document 1 : </label>
            <div class="controls">
              <input class="span12" id="doc_1" name="doc_1" type="file">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="confirm_password">Document 2 : </label>
            <div class="controls">
              <input class="span12" id="doc_2" name="doc_2" type="file">
            </div>
          </div>
          <div class="control-group" id="form_remark">
            <label class="control-label" for="remark">Remark : </label>
              <div class="controls"><input class="span12" id="remark" name="remark" type="text" value=""></div>
              <!--<textarea style="height:40px;" id="remark" name="remark" class="span10"></textarea>-->
          </div>
        </div>
      </div>
      <hr class="separator">
        <table class="table table-bordered table-condensed table-hover" style="width: 100%">
          <thead>
            <tr>
              <th style="width: 50px;">Station ID</th>
              <th style="width: 100px;">Region</th>
              <th style="width: 50px;">Station Name</th>
              <th style="width: 50px;">Site ID</th>
              <th style="width: 100px;">Vendor ID</th>
               <th style="width: 80px;">Equipment</th>
              <th style="width: 70px;">Freq Tx</th>
              <th style="width: 70px;">Freq Rx</th>
              <th style="width: 90px;">Polarization</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($app_detail as $value) {
              echo "
                <tr>
                <input type='hidden' name='link_id[]' value='{$value->app_link_id}'>
                <input type='hidden' name='service_id[]' value='{$value->service_id}'>
                <input type='hidden' name='subservice_id[]' value='{$value->subservice_id}'>
                <input type='hidden' name='station_name[]' value='{$value->station_name}'>
                <td>{$value->station_identifier}</td>
                <td>{$value->region}</td>
                <td>{$value->station_name}</td>
                <td>{$value->site_code}</td>
                <td>{$value->vendor_id}</td>
                <td>{$value->equipment_type}</td>
                <td>{$value->transmitter_freq}</td>
                <td>{$value->receiver_freq}</td>
               <td>{$value->transmitter_polarization_desc}</td>
                </tr>
                ";
            }
            ?>
          </tbody>
        </table>
      <div class="form-actions">
        <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
        <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" id="btn_close"><i></i>Cancel</button>
      </div>
    </div>
  </div>
</form>

<script>
  $('#btn_close').click(function() {
    $.fancybox.close();
  });

  $('#ff').submit(function(e) {
    e.preventDefault();
    var formData = new FormData($('#ff')[0]);
    $.ajax({
      url: $(this).attr('action'),
      type: 'post',
      data: formData,
      dataType: 'json',
      contentType: false,
      processData: false,
      beforeSend: function() {
        block.show('Cancelling application to SDPPI');
      },
      success: function(data) {
        if (typeof  data.status != 'undefined') {
          if (data.status == 'success') {
            $('#dg_discontinued').datagrid('reload');
            $.fancybox.close();
            $.gritter.add({
              image: '<?php echo base_url(); ?>file/images/logosmartfren.png',
              title: 'Successfully',
              text: data.message,
              time: 10000,
            });
          } else {
            $.gritter.add({
              image: '<?php echo base_url(); ?>file/images/logosmartfren.png',
              title: 'Error!',
              text: data.message,
              time: 10000,
            });
          }
        } else {
          $.gritter.add({
            image: '<?php echo base_url(); ?>file/images/logosmartfren.png',
            title: 'Error!',
            text: 'An error occured',
            time: 10000,
          });
        }
      },
      error: function(xhr, status, message) {
        coolNotification('<h3 style="color:white">Error!</h3> An error occured, please contact the administrator.', 'error');
      },
      complete: function() {
        block.close();
      }
    });

  });
</script>