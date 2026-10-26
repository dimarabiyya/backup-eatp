<form id="ff" action="<?php echo base_url('isr_bts/m2m/add_new_m2m'); ?>" method="post" class="form-horizontal" style="margin-bottom: 0; width: 1200px"> 
  <input type="hidden" name="app_resi_id" value="<?php echo $resi->app_resi_id; ?>">
  <div class="widget">
    <div class="widget-head">
      <h1 class="heading glyphicons notes_2"><i></i> Add New M2M</h1>
    </div>
    <div class="widget-body">
      <div class="row-fluid">
        <div class="span5">
          <div class="control-group">
            <label class="control-label">Resi No <span class="text-error">*</span> : </label>
            <div class="controls"><input class="span12" id="app_no" name="app_no" type="text" value="<?php echo $resi->app_no; ?>" autofocus=""></div>
          </div>
          <div class="control-group">
            <label class="control-label">Resi Issued <span class="text-error">*</span> : </label>
            <div class="controls">
              <div class="input-append">
                <input class="datepicker" id="app_issued" name="app_issued" type="text" value="<?php echo $resi->app_issued; ?>" readonly="">
                <span class="add-on glyphicons calendar"><i></i></span>
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Document : </label>
            <div class="controls"><input class="span12" type="file" id="doc" name="doc"><?php //echo $resi->download_doc;      ?></div>
          </div>
          <div class="control-group">
            <label class="control-label">Remark : </label>
            <div class="controls"><input class="span12" type="text" id="remark" name="remark" value="<?php echo $resi->remark; ?>"></div>
          </div>
        </div>
        <!--        <div class="span2">
                  <button type="button" class="btn" onclick="lookupLink()">Add Station</button>
                </div>-->
      </div>
      <div class="row-fluid" style="margin-top: 10px">
        <div style="width: 100%; overflow: auto">
          <table id="bts_table" class="table table-striped table-bordered table-condensed" style="width: 4000px">
            <thead>
              <tr>
                <th>Service ID</th>
                <th>Subservice ID</th>
                <th>Client ID</th>
                <th>Application ID</th>
                <th>Station ID</th>
                <th>Link ID</th>
                <th>Station Name</th>
                <th>Height ASL</th>
                <th>Lat / Long</th>
                <th>Radius of Operation</th>
                <th>Site Province</th>
                <th>Site City</th>
                <th>Site District</th>
                <th>Area of Service</th>
                <th>Equipment Type</th>
                <th>Equipment Output Power</th>
                <th>Bandwidth</th>
                <th>Tx Freq</th>
                <th>Rx Freq</th>
                <th>Antenna Type</th>
                <th>Feeding Loss</th>
                <th>Connector Loss</th>
                <th>Branch Loss</th>
                <th>Antenna Height AGL</th>
                <th>Transmitter Polatization</th>
              </tr>
            </thead>
            <tbody id="table_data">
              <?php
              foreach ($resi_detail as $row) {
                $lat = "<div style='height:25px'><label style='float:left;width:30px'>Lat :</label> <input style='width:20px;text-align:right' type='text' class='lat_deg' name='lat_deg[]' value='{$row->lat_deg}'/><input style='width:20px;text-align:right' type='text' class='lat_min' name='lat_min[]' value='{$row->lat_min}'/><input style='width:40px;text-align:right' type='text' class='lat_sec' name='lat_sec[]' value='{$row->lat_sec}'/><input style='width:20px;text-align:center' type='text' class='lat_dir_ind_n_s' name='lat_dir_ind_n_s[]' value='{$row->lat_dir}'/></div>";
                $long = "<div  style='height:25px'><label style='float:left;width:30px'>Lng :</label> <input style='width:20px;text-align:right' type='text' class='long_deg' name='long_deg[]' value='{$row->long_deg}'/><input style='width:20px;text-align:right' type='text' class='long_min' name='long_min[]' value='{$row->long_deg}'/><input style='width:40px;text-align:right' type='text' class='long_sec' name='long_sec[]' value='{$row->long_sec}'/><input style='width:20px;text-align:center' type='text' class='long_dir_ind_e_w' name='long_dir_ind_e_w[]' value='{$row->long_dir}'/></div>";
                $polar = form_dropdown('transmitter_polarization[]', $transmitter_polarization_opt, $row->transmitter_polarization, 'class="input-medium"');
                echo "
                    <tr class='row_data' id='row_data_{$row->bts_id}'>
                      <input type='hidden' name='resi_link_id[]' value='{$row->resi_link_id}'>
                      <input type='hidden' name='site_id[]' value='{$row->site_id}'>
                      <input type='hidden' name='vendor_id[]' value='{$row->vendor_id}'>
                      <input type='hidden' name='bts_id[]' value='{$row->bts_id}'>
                      <td><input class='input-mini text-right' type='text' name='service_id[]' value='{$row->service_id}'></td>
                      <td><input class='input-mini text-right' type='text' name='subservice_id[]' value='{$row->subservice_id}'></td>
                      <td><input class='input-mini text-right' type='text' name='client_id[]' value='{$row->client_id}'></td>
                      <td><input class='input-mini text-right' type='text' name='application_identifier[]' value='{$row->application_identifier}'></td>
                      <td><input class='input-mini text-right station_identifier' type='text' name='station_identifier[]' value='{$row->station_identifier}'></td>
                      <td><input class='input-mini text-right' type='text' name='link_id[]' value='{$row->link_id}'></td>
                      <td><input class='input-medium' type='text' name='station_name[]' value='{$row->station_name}'></td>
                      <td><input class='input-mini text-right' type='text' name='height_asl[]' value='{$row->height_asl}'></td>
                      <td>{$lat} {$long}</td>  
                      <td><input class='input-small text-right' type='text' name='radius_of_operation[]' value='{$row->radius_of_operation}'></td>
                        <td><input class='input-small' type='text' name='site_province[]' value='{$row->site_province}'></td>
                      <td><input class='input-small' type='text' name='site_city[]' value='{$row->site_city}'></td>
                      <td><input class='input-small' type='text' name='site_district[]' value='{$row->site_district}'></td>
                      <td><input class='input-large' type='text' name='area_of_service[]' value='{$row->area_of_service}'></td>
                      <td><input class='input-small' type='text' name='equipment_type[]' value='{$row->equipment_type}'></td>
                      <td><input class='input-medium text-right' type='text' name='equipment_output_power[]' value='{$row->equipment_output_power}'></td>
                      <td><input class='input-small text-right' type='text' name='bandwidth[]' value='{$row->bandwidth}'></td>
                      <td><input class='input-mini text-right' type='text' name='transmitter_freq[]' value='{$row->transmitter_freq}'></td>
                      <td><input class='input-mini text-right' type='text' name='receiver_freq[]' value='{$row->receiver_freq}'></td>
                      <td><input class='input-small' type='text' name='antenna_type[]' value='{$row->antenna_type}'></td>
                      <td><input class='input-mini text-right' type='text' name='feeding_loss[]' value='{$row->feeding_loss}'></td>
                      <td><input class='input-mini text-right' type='text' name='connector_loss[]' value='{$row->connector_loss}'></td>
                      <td><input class='input-mini text-right' type='text' name='branch_loss[]' value='{$row->branch_loss}'></td>
                      <td><input class='input-medium text-right' type='text' name='antenna_height_agl[]' value='{$row->antenna_height_agl}'></td>
                      <td>{$polar}</td> 
                    </tr>
                    ";
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-icon btn-primary glyphicons ok_2"><i></i>Submit</button>
          <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Cancel</button>
          <script>
            $(".btn-close").click(function() {
              $.fancybox.close();
            });
          </script>
        </div>
      </div>
    </div>

  </div>
</form>

<script src="<?php echo base_url('assets/js/hehehe.js'); ?>"></script>
<script>
//            function lookupLink() {
//              $.fancybox({
//                href: "<?php echo base_url('isr_bts/resi/lookup_bts'); ?>",
//                type: 'ajax',
//                minWidth: 1200,
//                scrolling: false,
//                autoSize: true,
//                fitToView: true,
//                closeClick: false,
//                topRatio: 0,
//                afterShow: function() {
//                  $('#app_no').focus();
//                }
//              });
//            }

  $(document).ready(function() {
//              $(".datepicker").datepicker({
//                dateFormat: "dd M yy",
//                changeMonth: true,
//                numberOfMonths: 1,
//                changeYear: true
//              });

    $("#ff").validate({
      rules: {
        app_no: "required",
        app_issued: "required",
      },
      messages: {
        app_no: "Please enter Resi number",
        app_issued: "Please enter issued date",
      },
      submitHandler: function(form) {
        coolConfirmation('<h3>Confirmation</h3> Are you sure submit this application to SDPPI?', function() {
          var formData = new FormData($('#ff')[0]);
          $.ajax({
            url: $('#ff').attr('action'),
            data: formData,
            type: 'post',
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function() {
              block.show('Saving');
            },
            success: function(response) {
              if (response.status == 'success') {
                $('#dg_new').datagrid('reload');

                $.fancybox.close();

                $.gritter.add({
                  image: '<?php echo base_url(); ?>file/images/logosmartfren.png',
                  title: 'Successfully',
                  text: response.message
                });
              }
//              coolNotification('<h3>' + response.status.toUpperCase() + '</h3>' + response.message, response.status, function() {
//                if (response.status == 'success') {
//                  //window.location.href = '<?php echo base_url('isr_bts/m2m'); ?>';
//                  $.fancybox.close();
//                  $('#dg_new').datagrid('reload');
//                }
//              });
            },
            error: function(xhr, status, message) {
              coolNotification('<h3 style="color:white">Error!</h3> An error occured, please contact the Administrator.', 'error');
            },
            complete: function() {
              block.close()
            }
          });
        });
      }
    });
  });
</script>