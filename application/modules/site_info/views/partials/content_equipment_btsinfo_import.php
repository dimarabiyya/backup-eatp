<div class="widget" style="width: 1200px">
  <div class="widget-head"><h4 class="heading"><?php echo $title ?></h4></div>
  <div class="widget-body">
    <div class="row-fluid">
      <form enctype="multipart/form-data" id="file_form1" name="file_form1" method="post">
        <div class="row-fluid" style="margin-bottom: 10px;">
          <b>BTS File ( xls / xlsx ) : </b> <input name="file_app" id="file_app" type="file" accept=".xls, .xlsx">
          <!--<button name="validate" class="btn btn-primary btn-icon glyphicons circle_ok" type="submit"><i></i>Validate</button>-->
        </div>
      </form>     
      <div class="alert alert-error" style="max-height: 250px; overflow: auto;">
        <span id="alert-message"></span>
      </div>
      <br/>
      <div id='container_table' class="row-fluid" style="width: 100%; overflow: auto;">
        <form id="app_form" name="app_form" method="post">
          <table id="app_table" class="table table-striped table-bordered table-condensed" style="width: 2000px;">
            <thead>
              <tr>
                <?php
                $headers = array(
                    'site_id' => 100,
                    'vendor_id' => 100,
                    'morphology' => 100,
                    'bts_mode' => 100,
                    'bts_brand' => 100,
                    'bts_type' => 100,
                    'height_asl' => 100,
                    'radius_of_operation' => 100,
                    'equipment_type' => 100,
                    'equipment_output_power' => 150,
                    'bandwidth' => 100,
                    'transmitter_freq' => 100,
                    'receiver_freq' => 100,
                    'antenna_type' => 100,
                    'feeding_loss' => 100,
                    'connector_loss' => 100,
                    'branch_loss' => 100,
                    'antenna_height_agl' => 100,
                    'transmitter_polarization' => 100,
                );

                foreach ($headers as $header => $width) {
                  $header = humanize($header);
//                            $header = str_replace('_', ' ', $header);
                  echo "<th style='min-width:{$width}px;'>{$header}</th>";
                }
                ?>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="<?php echo count($headers); ?>">No data</td></tr>
            </tbody>
          </table>
        </form>    
      </div>
      <div class="form-actions">
        <button id="save_btn" type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
        <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Cancel</button>
      </div>
    </div>
  </div>
</div>

<style>
  .input-transparent {
    border: none!important;
    background-color: transparent!important;
    box-shadow: none!important;
    margin-bottom: 0px!important;
    width : 100%!important;
  }
</style>

<script language="javascript" src="<?php echo base_url(); ?>assets/js/hehehe.js"></script>

<script>

  $(document).ready(function() {
    $('#container_table').attr('style', 'overflow: auto; min-height: 100px; width:' + parent.viewWidth + 'px');
    $('.alert').hide();

    $('#file_app').change(function() {
      var formId = document.getElementById("file_form1");
      var formData = new FormData(formId);
      $.ajax({
        url: '<?php echo base_url('site_info/equipment/bts_read_import_file_multisite'); ?>',
        data: formData,
        type: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function() {
          $('.alert').hide();
          block.show('Processing data');
          $('#app_table tbody').html('');
        },
        success: function(data) {
          $('#app_table tbody').html('');
          $.each(data, function(index, element) {
            var content = '<tr>\n\
                    <td><input name="site_id[]" value="' + element.site_id + '" type="text" class="input-medium"></td>\n\
                    <td><input name="vendor_id[]" value="' + element.vendor_id + '" type="text" class="input-medium"></td>\n\
                    <td>\n\
                      <select name="morphology[]" id="morphology-' + index + '" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_morphology');
foreach ($lookup as $lo) {
  ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                    </td>\n\
                    <td>\n\
                      <select name="bts_mode[]" class="input-medium" id="bts-mode-' + index + '">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_mode');
foreach ($lookup as $lo) {
  ?>\
                              <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                    </td>\n\
                    <td>\n\
                                <select name="bts_brand[]" id="bts-brand-' + index + '" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_brand');
foreach ($lookup as $lo) {
  ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                    </td>\n\
                    <td>\n\
                                <select name="type[]" id="bts-type-' + index + '" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_type');
foreach ($lookup as $lo) {
  ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
</td>\n\
                    <td><input name="height_asl[]" value="' + element.height_asl + '" type="text" class="input-medium"></td>\n\
                    <td><input name="radius_of_operation[]" value="' + element.radius_of_operation + '" type="text" class="input-medium"></td>\n\
                    <td><input name="equipment_type[]" value="' + element.equipment_type + '" type="text" class="input-medium"></td>\n\
                    <td><input name="equipment_output_power[]" value="' + element.equipment_output_power + '" type="text" class="input-medium"></td>\n\
                    <td><input name="bandwidth[]" value="' + element.bandwidth + '" type="text" class="input-medium"></td>\n\
                    <td><input name="tx_freq[]" value="' + element.tx_freq + '" type="text" class="input-medium"</td>\n\
                    <td><input name="rx_freq[]" value="' + element.rx_freq + '" type="text" class="input-medium"></td>\n\
                    <td><input name="antenna_type[]" value="' + element.antenna_type + '" type="text" class="input-medium"></td>\n\
                    <td><input name="feeding_loss[]" value="' + element.feeding_loss + '" type="text" class="input-medium"></td>\n\
                    <td><input name="connector_loss[]" value="' + element.connector_loss + '" type="text" class="input-medium"></td>\n\
                    <td><input name="branch_loss[]" value="' + element.branch_loss + '" type="text" class="input-medium"></td>\n\
                    <td><input name="antenna_height_agl[]" value="' + element.antenna_height_agl + '" type="text" class="input-medium"></td>\n\
                    <td>\n\
                      <select name="transmitter_polarization[]" id="transmitter-polarization-' + index + '" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('transmission_polarization');
foreach ($lookup as $lo) {
  ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
</td>\n\
                    </tr>';
            $('#app_table tbody').append(content);
            //set selected to dropdown
            $('#morphology-' + index + ' option[value=' + element.morphology + ']').attr('selected', 'selected');
            $('#bts-mode-' + index + ' option[value=' + element.bts_mode + ']').attr('selected', 'selected');
            $('#bts-brand-' + index + ' option[value=' + element.bts_brand + ']').attr('selected', 'selected');
            $('#bts-type-' + index + ' option[value=' + element.bts_type + ']').attr('selected', 'selected');
            $('#transmitter-polarization-' + index + ' option[value=' + element.transmitter_polarization + ']').attr('selected', 'selected');
          });

        },
        error: function(xhr, status, message) {
          coolNotification(status.toUpperCase() + ' : ' + message, 'error');
        },
        complete: function() {
          block.close();
        }
      });
    });

    $('#save_btn').click(function(e) {
      e.preventDefault();
      $.ajax({
        url: '<?php echo base_url('isr/isr_m2m_v1/save'); ?>',
        data: $('#app_form').serialize(),
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          block.show('Saving data');
        },
        success: function(data) {
          if (data.type == 'success') {
            coolNotification(data.message, data.type, function() {
              $.fancybox.close();
            });
          } else {
            coolNotification(data.message, data.type);
          }
        },
        error: function(xhr, status, message) {
          coolNotification(status.toUpperCase() + ' : ' + message, 'error');
        },
        complete: function() {
          block.close();
          $('#dg').datagrid('load');
        }
      });
    });

  });

  $(".btn-close").click(function() {
    $.fancybox.close();
  });
</script>