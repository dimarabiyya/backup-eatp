<?php //error_reporting(0); ?>
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
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form-app" enctype="multipart/form-data" action="<?php echo base_url('isr_bts/release_dgpt/add_renewal_release_dgpt') ?>" >
  <div class="widget">
    <div class="widget-head"><h4 class="heading">Upload Release DGPT Doc</h4></div>
    <div class="widget-body">
      
      <input type="hidden" name="spp_pay_id" value="<?php echo $header->spp_pay_id ?>" />
      <input type="hidden" name="app_id" value="<?php echo $header->app_id; ?>" />
      <input type="hidden" name="app_no" value="<?php echo $header->app_no; ?>" />
      <input type="hidden" name="ref_doc_id" value="<?php echo $header->ref_doc_id; ?>" />

      <div class="control-group column-left" id="form_app_no">
        <label class="control-label" for="app_no">Application Number</label>
        <div class="controls" style="padding-top: 5px;width:200px">
          <span class="lbl"><?php echo $header->app_no; ?></span>
        </div>
      </div>
      <div class="control-group column-right" id-"form_doc_issued">
           <label class="control-label" for="doc_issued">Doc Released Date <span class="text-error">*</span></label>
        <div class="controls">
          <div class="input-append">
            <input class="datepicker" id="doc_issued" name="doc_issued" type="text" value="<?php echo $header->doc_release_date; ?>" readonly="" />
            <span class="add-on glyphicons calendar"><i></i></span>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="control-group column-left" id="form_app_issued">
        <label class="control-label" for="app_issued">App Issued Date</label>
        <div class="controls" style="padding-top: 5px;width:200px">
          <span class="lbl"><?php echo $header->app_issued; ?></span>
        </div>
      </div>
      <div class="control-group column-right" id="form_document">
        <label class="control-label" for="doc">Document <span class="text-error">*</span></label>
        <div class="controls">
          <input  style="width:220px" id="doc" name="doc" type="file"/>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="control-group column-left" id="form_app_expired">
        <label class="control-label" for="app_expired">App Expired Date</label>
        <div class="controls" style="padding-top: 5px;">
          <span class="lbl"><?php echo $header->app_expired; ?></span>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="clearfix"></div> 

      <?php
      $arr = array(
          'station_no' => array("Station ID", 75),
          'zone_id' => array("Zona", 50),
          'region' => array("Region", 150),
          'site_code_ne' => array("NE ID", 100, "center"),
          'vendor_ne' => array("NE Vendor", 100),
          'nearendsite' => array('NE Name', 200),
          'site_code_fe' => array("FE ID", 100, "center"),
          'vendor_fe' => array("FE Vendor", 100),
          'farendsite' => array('FE Name', 200),
          'equipment' => array('Equipment', 100),
          'freq_tx' => array('Tx Freq', 70),
          'freq_rx' => array('Rx Freq', 70)
      );

      $strpermit = '<select id="jenis_izin" name="jenis_izin[]">
	                            <option>--Choose One--</option>
	                            <option value="fixed">Fixed</option>
	                            <option value="temporary">Temporary</option>
                            </select>';
      ?>

      <div style="margin-top:10px">
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
      </div>				
      <div class="control-group" style="display:none">
        <label class="control-label"></label>
        <div class="controls" id="dState">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok submit"><i></i>Save</button>
        <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Cancel</button>
        <script>
          $(".btn-close").click(function() {
            $.fancybox.close();
          });
        </script>
      </div>         				


    </div>
  </div>
</form>
<script language="javascript">
  var dateFormat = {
    monthsName: {'01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr', '05': 'May', '06': 'Jun', '07': 'Jul', '08': 'Aug', '09': 'Sep', '10': 'Oct', '11': 'Nov', '12': 'Dec'},
    monthsCode: {'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06', 'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12'},
    fields: document.getElementsByClassName('datepicker'),
    init: function() {
      $('.datepicker').datepicker({
        dateFormat: 'dd-M-yy',
        changeMonth: true,
        changeYear: true,
        onSelect: function(dateText, instance) {
          if ($(this).attr('id') == 'isr_issued') {
            date = $.datepicker.parseDate(instance.settings.dateFormat, dateText, instance.settings);
            date.setMonth(date.getMonth() + 12);
            date.setDate(date.getDate() - 1);
            $("#isr_expired").datepicker("setDate", date);
          }
        }

      }).css({"z-index": 8300});
      this.defaultMode();

      for (var i = 0; i < this.fields.length; i++) {
        (function(idx) {
          dateFormat.fields[idx].setAttribute("placeholder", "DD-MM-YYYY");



          dateFormat.fields[idx].onfocus = function() {
            var day = this.value.split('-')[0];
            var month = this.value.split('-')[1];
            var year = this.value.split('-')[2];

            if (typeof dateFormat.monthsCode[month] != 'undefined') {
              this.value = day + '-' + dateFormat.monthsCode[month] + '-' + year;
            }
          };
          dateFormat.fields[idx].onblur = function(e) {
            if (this.value != '') {
              if (!dateFormat.checkValidDate(this.value)) {
                setTimeout(function() {
                  e.target.select();
                });
              }
            }

            var day = this.value.split('-')[0];
            var month = this.value.split('-')[1];
            var year = this.value.split('-')[2];

            if (typeof dateFormat.monthsName[month] != 'undefined') {
              this.value = day + '-' + dateFormat.monthsName[month] + '-' + year;
            }
          };
          dateFormat.fields[idx].onkeypress = function(e) {
            var restrictionFormat = /[0-9\-]/g; // cuma angka dan dash

            if (!e)
              var e = window.event
            if (e.keyCode)
              code = e.keyCode;
            else if (e.which)
              code = e.which;
            var character = String.fromCharCode(code);

            // if they pressed esc... remove focus from field...
            if (code == 27) {
              this.blur();
              return false;
            }

            // ignore if they are press other keys
            // strange because code: 39 is the down key AND ' key...
            // and DEL also equals .
            if (!e.ctrlKey && code != 9 && code != 8 && code != 36 && code != 37 && code != 38 && (code != 39 || (code == 39 && character == "'")) && code != 40) {
              if (character.match(restrictionFormat)) {
                return true;
              } else {
                return false;
              }

            }
          };

        })(i);
      }
    },
    defaultMode: function() { // Call this after call 'saveMode'
      for (var i = 0; i < this.fields.length; i++) {
        if (this.fields[i].value != '') {
          var day = this.fields[i].value.split('-')[2];
          var month = this.fields[i].value.split('-')[1];
          var year = this.fields[i].value.split('-')[0];

          if (typeof this.monthsName[month] != 'undefined') {
            this.fields[i].value = day + '-' + this.monthsName[month] + '-' + year;
          }
        }
      }
    },
    saveMode: function() { // Call this after call 'defaultMode'
      for (var i = 0; i < this.fields.length; i++) {
        if (this.fields[i].value != '') {
          var day = this.fields[i].value.split('-')[0];
          var month = this.fields[i].value.split('-')[1];
          var year = this.fields[i].value.split('-')[2];

          if (typeof this.monthsCode[month] != 'undefined') {
            this.fields[i].value = year + '-' + this.monthsCode[month] + '-' + day;
          }
        }
      }
    },
    checkValidDate: function(strDate) {
      var validformat = /^\d{2}-\d{2}-\d{4}$/; // Basic check for format validity
      var returnval = false;

      var monthfield = strDate.split('-')[1];
      if (monthfield.length == 3) {
        returnval = true
      } else {
        if (!validformat.test(strDate)) {
          alert('Invalid date format.');
        } else { // Detailed check for valid date ranges
          var monthfield = strDate.split('-')[1];
          var dayfield = strDate.split('-')[0];
          var yearfield = strDate.split('-')[2];
          var dayobj = new Date(yearfield, monthfield - 1, dayfield);
          if ((dayobj.getMonth() + 1) != monthfield || dayobj.getDate() != dayfield || dayobj.getFullYear() != yearfield) {
            alert('Invalid day, month or year range.');
          } else {
            returnval = true;
          }
        }
      }
      return returnval;
    }
  };
  $(document).ready(function() {
    dateFormat.init();

    $('.fancybox-wrap').draggable({handle: ".widget-head"});

//    $(".dynamicTable").datagrid({
//      fitColumns: false,
//      pageSize: 2,
//      nowrap: true
//    });


    $("#add-form-app").ajaxForm({dataType: 'json', success: processJson, beforeSubmit: validate});
    $("#add-form-app").submit(function(event) {
    });
    function validate() {
      if (confirm("Are you sure want to submit form?")) {
        $('.submit').attr('disabled', true);
        $('#dState').html('<span class="popnotif loading">Saving data, please wait...</span>').parent().slideDown();

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

    $("#add-form-app").validate({
      rules: {
        doc_issued: "required",
        doc: "required",
      },
      messages: {
        doc_issued: "Please enter doc released date",
        doc: "Please select a file",
      }
    });

  });
</script>