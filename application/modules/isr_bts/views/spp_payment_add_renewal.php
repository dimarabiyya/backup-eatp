<?php //error_reporting(0);  ?>
<style type="text/css">
  select{width:auto;}
  table input[type="checkbox"]{
    margin: -3px 5px 0 0;
  }
  .widget { border:none; }
  .form-horizontal .control-label { text-align:left; }
  fieldset { margin-bottom:20px; border-bottom:1px solid #CCCCCC; padding:0px; }
  legend { border-bottom:1px solid #CCCCCC; margin:0 0 4px; }
  legend + .control-group { margin-top:0px; }
  .form-horizontal .column-left { float:left; }
  .form-horizontal .column-right { float:left; margin-left:80px; }
  .form-horizontal .control-group { margin-bottom:4px; }
  .form-horizontal .control-group:last-child { margin:0 0 4px; }
  .btn { padding:4px 10px; }
  .table-primary thead th { font-size:12px; }
  .panel { background:#FFFFFF; }
  .controls .lbl{font-weight: bold;}

</style>
<script language="javascript" src="<?php echo base_url(); ?>assets/js/jquery.form.js"></script>
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form-app" enctype="multipart/form-data" action="<?php echo base_url('isr_bts/spp_payment/add_renewal_spp_payment') ?>" >
  <input type="hidden" name="spp_fin_id" id="spp_fin_id" value="<?php echo $header->spp_fin_id; ?>"/>
  <input type="hidden" name="app_id" id="app_id" value="<?php echo $header->app_id; ?>">
  <div class="widget" style="margin:0px">
    <div class="widget-head"><h4 class="heading">Upload SPP Payment Doc</h4></div>
    <div class="widget-body">

      <div class="control-group column-left" id="form_app_no">
        <label class="control-label" for="app_no">Application Number</label>
        <div class="controls">
          <input  id="app_no" name="app_no" type="text" value="<?php echo $header->app_no; ?>" readonly="readonly">
        </div>
      </div>
      <div class="control-group column-right" id="form_spp_payment_date">
        <label class="control-label" for="payment_date">SPP Payment Date <span class="text-error">*</span> </label>
        <div class="controls">
          <div class="input-append">
            <input class="datepicker" id="payment_date" name="payment_date" type="text" value="<?php echo $header->payment_date; ?>" readonly=""/>
            <span class="add-on glyphicons calendar"><i></i></span>
          </div>
        </div>
      </div>

      <div class="clearfix"></div>

      <div class="control-group column-left" id="form_bpv_no">
        <label class="control-label" for="bpv_no">BPV Number <span class="text-error">*</span> </label>
        <div class="controls">
          <input  id="bpv_no" name="bpv_no" type="text" value="">
        </div>
      </div>
      <div class="control-group column-right" id="form_remark">
        <label class="control-label" for="remark">Remark</label>
        <div class="controls">
          <textarea style="position:absolute;height:45px;width: 233px;max-width:233px;max-height:45px" id="remark" name="remark"></textarea>
        </div>
      </div>

      <div class="clearfix"></div>

      <div class="control-group column-left" id="form_document">
        <label class="control-label" for="doc">Document</label>
        <div class="controls">
          <input id="doc" name="doc" type="file">
        </div>
      </div>
      <div class="clearfix"></div>
      <?php
      $arr = array(
          'station_no' => array("Station ID", 100),
          'zone_id' => array("Zona", 100),
          'region' => array("Region", 150),
          'site_code_ne' => array("NE ID", 100, "center"),
          'vendor_ne' => array("NE Vendor", 100),
          'nearendsite' => array('NE Name', 200),
          'site_code_fe' => array("FE ID", 100, "center"),
          'vendor_fe' => array("FE Vendor", 100),
          'farendsite' => array('FE Name', 200),
          'equipment' => array('Equipment', 100),
          'freq_tx' => array('Tx Freq', 100),
          'freq_rx' => array('Rx Freq', 100),
          'bhp' => array('BHP (Rp.)', 150, "right")
      );
      ?>

      <div style="margin-top:20px">
        <table class="dynamicTable table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th>Station ID</th>
              <th>Region</th>
              <th>Site ID</th>
              <th>Vendor ID</th>
              <th>Equipment</th>
              <th>Frequency</th>
              <th>Polarization</th>
              <th>BHP (Rp.)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 0;
            $bhp = 0;
            foreach ($details as $row) {
              echo "
                <tr>
                <input type='hidden' name='link_id[]' value='{$row->app_link_id}'>
                  <td>{$row->station_identifier}</td>
                  <td>{$row->region}</td>
                  <td>{$row->site_code}</td>
                  <td>{$row->vendor_id}</td>
                  <td>{$row->equipment_type}</td>
                  <td>TX : {$row->transmitter_freq} <br> RX : {$row->receiver_freq}</td>
                  <td>{$row->transmitter_polarization_desc}</td>
                  <td style='text-align:right'>" . number_format($row->bhp, 0, ',', '.') . "</td>
                </tr>
                ";
              $bhp += $row->bhp;
              $i++;
            }
            ?>								
          </tbody>
        </table>
        <div style="
             text-align: right;
             margin-top: 5px;">
          <span style="font-size: larger;
                font-weight: bold;
                margin: 20px 10px 0 0;">Total BHP : <?php echo number_format($bhp, 0, ',', '.'); ?></span>
        </div>
      </div>				    
      <div class="control-group" style="display:none">
        <label class="control-label"></label>
        <div class="controls" id="dState"></div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok submit"><i></i>Save</button>
        <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Cancel</button>
        <script>
          $(".btn-close").click(function() {
            if (confirm('Are you sure want to close ?'))
              $.fancybox.close();
          });

        </script>
      </div>
    </div>
  </div>
</form>
<script language="javascript">

  jQuery(document).ready(function() {
    dateFormat.init();

    $('.fancybox-wrap').draggable({handle: ".widget-head"});
    $("#add-form-app").ajaxForm({dataType: 'json', success: processJson, beforeSubmit: validate});
    $("#add-form-app").submit(function(event) {
    });
    function validate() {
      if (confirm("Are you sure want to submit form?")) {
        $('.submit').attr('disabled', true);
        $('#dState').html('<span class="popnotif loading">Saving data, please wait ..</span>').parent().slideDown();

        if ($("#add-form-app").valid() == false) {
          $('#dState').html('<span class="popnotif warning">Please complete form data above.</span>').parent().slideDown();
          $('.submit').removeAttr('disabled');
          return false;
        }

      } else {
        return false;
      }
    }
    function processJson(data) {
      if (data.status == 'OK') {
        $('#dg_renewal').datagrid('reload');

        $.fancybox.close();

        $.gritter.add({
          image: '<?php echo base_url(); ?>file/images/logosmartfren.png',
          title: 'Successfully',
          text: data.hasil
        });

      } else {
        $('.submit').removeAttr('disabled');
        $('#dState').html('<span class="popnotif warning">' + data.hasil + '</span>');
      }
    }

    $(".datepicker").datepicker({
      dateFormat: "dd-M-yy",
      changeMonth: true,
      numberOfMonths: 1,
      changeYear: true
    }).css({"z-index": 8300});

    $("#add-form-app").validate({
      rules: {
        payment_date: "required",
        bpv_no: "required"

      },
      messages: {
        payment_date: "Please enter payment date",
        bpv_no: "Please enter BPV Number"
      }
    });

  });
</script>