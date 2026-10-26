<?php // error_reporting(0);      ?>
<script language="javascript" src="<?php echo base_url(); ?>assets/js/jquery.form.js"></script>
<form class="form-horizontal" style="margin-bottom: 0; width: 1200px;" method="POST" id="add-form-app" enctype="multipart/form-data" action="<?php echo base_url('isr_bts/engineering_approval/add_engineering_approval_discontinued'); ?>">
  <input type="hidden" name="app_id" id="app_id" value="<?php echo $app->app_id; ?>">
  <input type="hidden" name="milestone_status" id="app_id" value="<?php echo $app->milestone_status; ?>">
  <div class="widget" style="margin:0px">
    <div class="widget-head"><h4 class="heading">Engineering Approval Discontinued Form</h4></div>
    <div class="widget-body">
      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_bpv_no">
            <label class="control-label">App No : </label>
            <div class="controls">
              <input  id="app_no" name="app_no" type="text" value="<?php echo $app->app_no; ?>" readonly="">
            </div>
          </div>
        </div>
        <div class="span6">
          <div class="control-group" id="form_document">
            <label class="control-label" for="userfile">Document : </label>
            <div class="controls">
              <input id="doc" name="doc" type="file">
            </div>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <hr class="separator">
      <table id="ddv"></table>

    </div>				    
    <div class="control-group" style="display:none">
      <label class="control-label"></label>
      <div class="controls" id="dState"></div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok submit"><i></i>Submit</button>
      <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Close</button>
      <script>
        $(".btn-close").click(function() {
          $.fancybox.close();
        });

      </script>
    </div>
  </div>
</form>
<script language="javascript">

  $(document).ready(function() {

    $("#add-form-app").ajaxForm({dataType: 'json', success: processJson, beforeSubmit: validate, error: error, complete: complete});
    $("#add-form-app").submit(function(event) {
    });

    function validate() {
      var msg = '';
      if ($('#doc').val() == '') {
        msg = ' without document';
      }

      if (confirm("Are you sure want to submit form " + msg + "?")) {
        $('.submit').attr('disabled', true);
        $('#dState').html('<span class="popnotif loading">Saving data, please wait ..</span>').parent().slideDown();

        if ($("#add-form-app").valid() == false) {
          $('#dState').html('<span class="popnotif warning">Please complete form data above.</span>').parent().slideDown();
          return false;
        }

      } else {
        return false;
      }
    }

    function processJson(data) {
      if (data.status == 'success') {
//        $('#dg_discontinued').datagrid('reload');

        $.fancybox.close();

        $.gritter.add({
          image: '<?php echo base_url(); ?>file/images/logosmartfren.png',
          title: 'Successfully',
          text: data.message
        });
      } else {
        $('#dState').html('<span class="popnotif warning">' + data.message + '</span>');
        //coolNotification('<h3>Warning</h3>' + data.message, 'warning');
      }
    }

    function error(xhr, status, message) {
      $('#dState').html('<span class="popnotif warning">An error occured. Please call the administrator.</span>');
    }

    function complete() {
      $('.submit').removeAttr('disabled');
      $('#dg_discontinued').datagrid('reload');
    }

    $('.fancybox-wrap').draggable({handle: ".widget-head"});

    $('#ddv').datagrid({
      url: '<?php echo base_url("isr_bts/engineering_approval/get_engineering_approval_detail_discontinued_list/{$app->app_id}"); ?>',
      fitColumns: false,
      pageSize: 10,
      pagination: false,
      singleSelect: true,
      height: 'auto',
      nowrap: false,
      columns: [[
          {field: 'action', title: 'Action', width: '70', align: 'center'},
          {field: 'engineering_approval_status', title: 'Approval Status', width: '100', align: 'center'},
          {field: 'station_identifier', title: 'Station ID', width: '80', align: 'center'},
          {field: 'station_name', title: 'Station Name', width: '150'},
          {field: 'vendor_id', title: 'Vendor ID', width: '100'},
          {field: 'height_asl', title: 'Height ASL', width: '80', align: 'right'},
          {field: 'latitude', title: 'Latitude', width: '100', align: 'right'},
          {field: 'longitude', title: 'Longitude', width: '100', align: 'right'},
          {field: 'radius_of_operation', title: 'Radius of Operation', width: '125', align: 'right'},
          {field: 'site_province', title: 'Site Province', width: '125'},
          {field: 'site_city', title: 'Site City', width: '125'},
          {field: 'site_district', title: 'Site District', width: '125'},
          {field: 'area_of_service', title: 'Area of Service', width: '200'},
          {field: 'equipment_type', title: 'Equipment Type', width: '100'},
          {field: 'equipment_output_power', title: 'Equipment Output Power', width: '150', align: 'right'},
          {field: 'bandwidth', title: 'Bandwidth', width: '100', align: 'right'},
          {field: 'transmitter_freq', title: 'Tx Freq', width: '100', align: 'right'},
          {field: 'receiver_freq', title: 'Rx Freq', width: '100', align: 'right'},
          {field: 'antenna_type', title: 'Antenna Type', width: '100'},
          {field: 'feeding_loss', title: 'Feeding Loss', width: '100', align: 'right'},
          {field: 'connector_loss', title: 'Connector Loss', width: '100', align: 'right'},
          {field: 'branch_loss', title: 'Branch Loss', width: '100', align: 'right'},
          {field: 'antenna_height_agl', title: 'Antenna Height AGL', width: '125', align: 'right'},
          {field: 'transmitter_polarization_desc', title: 'Transmitter Polatization', width: '125'},
        ]],
      onLoadSuccess: function() {
        var dg = $(this);
        var rows = dg.datagrid('getRows');
        dg.datagrid('mergeCells', {
          index: 0,
          field: 'action',
          rowspan: rows.length
        });
      }
    });


  });

  function approveLink(appId) {
    coolConfirmation('<h3>Confirmation</h3> Are you sure want to approve this link?', function() {
      $.ajax({
        url: '<?php echo base_url('isr_bts/engineering_approval/approve_link2'); ?>',
        data: {app_id: appId},
        type: 'post',
        dataType: 'json',
        success: function(data) {
          $('#ddv').datagrid('reload');
          coolNotification('<h3>Success</h3>' + data.message, 'success');
        },
        error: function(xhr, status, message) {
          coolNotification('<h3>Notification</h3> An error occured, please contact the administrator.', 'error');
        }
      });
    });
  }

  function validateLink(appId) {
    popupwindow("<?php echo base_url(); ?>email/write_validation_letter_isr_bts2?subject=ISR BTS data link validation by engineering&app_id=" + appId, "ISR BTS data link validation by engineering", 850, 600);
  }

  function popupwindow(url, title, w, h) {
    wLeft = window.screenLeft ? window.screenLeft : window.screenX;
    wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, appzable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
  }

  /*
   * Just for reload datagrid
   */
  function reloadDatagrid() {
    $('#ddv').datagrid('reload');
  }
</script>