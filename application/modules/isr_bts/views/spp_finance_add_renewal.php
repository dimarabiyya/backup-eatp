<?php //error_reporting(0);     ?>
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
  fieldset{margin-top:0px;margin-bottom:none;border-bottom: none;}
  legend{margin-bottom:10px;}
  /*	.freq_tx,.freq_rx,.vendor_ne,.vendor_fe,.equipment{display: none;}*/
  .equipment{display: none;}
  .sinfo{font-size:10px;color:gray;}
</style>
<script language="javascript" src="<?php echo base_url(); ?>assets/js/jquery.form.js"></script>

<form action="<?php echo base_url() ?>isr_bts/spp_finance/add_renewal_spp_finance" class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form-app" enctype="multipart/form-data">
  <input type="hidden" name="app_id" id="app_id" value="<?php echo $app->app_id; ?>"/>	
  <div class="widget">
    <div class="widget-head"><h4 class="heading">Create App and Upload SPP to Finance</h4></div>
    <div class="widget-body">
      <?php
      $str_app_no = 'id="app_no" name="app_no"';
      $str_license_number = 'id="license_number" name="license_number"';
      $str_app_issued = 'id="app_issued" name="app_issued"';
      $str_app_expired = 'id="app_expired" name="app_expired"';
//      if (isset($data["app_no"]) && !isset($data["spp_fin_id"])) {
//        $str_app_no.=' readonly="readonly"';
//      }
      ?>
      <fieldset>
        <legend>Application</legend>
        <div class="control-group column-left" id="form_app_no">
          <label class="control-label" for="app_no">App Number <span class="text-error">*</span> </label>
          <div class="controls">
            <input  style="width:233px" <?php echo $str_app_no; ?> type="text" value="<?php echo $app->app_no; ?>"/>
          </div>
        </div>
        <div class="control-group column-right" id="form_app_issued_date">
          <label class="control-label" for="app_issued">App Issued Date </label>
          <div class="controls">
            <div class="input-append">
              <input class="datepicker" <?php echo $str_app_issued; ?> type="text" value="<?php echo $app->app_issued; ?>" />
              <span class="add-on glyphicons calendar"><i></i></span>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>

        <div class="control-group column-left" id="form_license_no">
          <label class="control-label" for="license_no">License Number </label>
          <div class="controls">
            <input  style="width:233px" <?php echo $str_license_number; ?> type="text" value="<?php echo $app->license_number; ?>" />
          </div>
        </div>

        <div class="control-group column-right" id="form_app_expired_date">
          <label class="control-label" for="app_expired">App Expired Date </label>
          <div class="controls">
            <div class="input-append">
              <input class="datepicker" <?php echo $str_app_expired; ?> type="text" value="<?php echo $app->app_expired; ?>" />
              <span class="add-on glyphicons calendar"><i></i></span>
            </div>
          </div>
        </div>

        <div class="clearfix"></div>

        <div class="control-group column-left" id="form_no">
<!--          <label class="control-label" for="resi_no">Resi Number </label>
          <div class="controls">
            <input name="resi_no" style="width:233px" readonly="readonly" type="text" value="<?php echo $app->resi_no; ?>" />
          </div>-->
        </div>

        <div style='margin-left: 470px;' class="control-group column-right" id="form_links_count">
          <label class="control-label" for="total_links">Total Station </label>
          <div class="controls">
            <h4 id="total_links"><?php echo count($app_detail); ?> station(s)</h4>
          </div>
        </div>

        <div class="clearfix"></div>

<!--        <div class="control-group column-left" id="form_app_issued">
          <label class="control-label" for="resi_issued">Resi Issued </label>
          <div class="controls">
            <input name="resi_issued" style="width:233px" readonly="readonly" type="text" value="<?php echo $app->resi_issued; ?>" />
          </div>
        </div>-->
      </fieldset>

      <fieldset>
        <legend>SPP</legend>
        <div class="control-group column-left" id="form_spp_no">
          <label class="control-label" for="spp_no">SPP Number <span class="text-error">*</span> </label>
          <div class="controls">
            <input id="spp_no" style="width:233px" name="spp_no" type="text" value="" />
          </div>
        </div>
        <div class="control-group column-right" id="form_give_to_fin_date">
          <label class="control-label" for="give_to_fin_date">Submitted to finance<span class="text-error">*</span></label>
          <div class="controls">
            <div class="input-append">
              <input class="datepicker" id="give_to_fin_date" name="give_to_fin_date" type="text" value="<?php echo $app->give_to_fin_date; ?>" />
              <span class="add-on glyphicons calendar"><i></i></span>
            </div>
          </div>
        </div>

        <div class="clearfix"></div>
        <div class="control-group column-left" id="form_spp_issued_date">
          <label class="control-label" for="spp_issued_date">SPP Issued Date <span class="text-error">*</span> </label>
          <div class="controls">
            <div class="input-append">
              <input class="datepicker" id="spp_issued_date" name="spp_start_date" type="text" value="<?php echo $app->spp_start_date; ?>" />
              <span class="add-on glyphicons calendar"><i></i></span>
            </div>
          </div>
        </div>
        <div class="control-group column-right" id="form_remark">
          <label class="control-label" for="remark">Remark</label>
          <div class="controls">
            <textarea style="position:absolute;height:45px;width: 233px;max-width:233px;max-height:45px" id="remark" name="remark"></textarea>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="control-group column-left"  id="form_spp_issued_date">
          <label class="control-label" for="spp_due_date">SPP Due Date <span class="text-error">*</span> </label>
          <div class="controls">
            <div class="input-append">
              <input class="datepicker" id="spp_due_date" name="spp_due_date" type="text" value="<?php echo $app->spp_due_date; ?>" />
              <span class="add-on glyphicons calendar"><i></i></span>
            </div>
          </div>

        </div>

        <div class="clearfix"></div>
        <div class="control-group column-left" id="form_document">
          <label class="control-label" for="userfile">Document </label>
          <div class="controls">
            <div class="input-control">
              <?php
              if (isset($data['file_name'])) {
                echo '<a target="_blank" href="' . base_url() . "file/upload/isr/" . $data['file_name'] . '">' .
                $this->core->setFileNameIcon($data['file_name']) . '</a>';
              }
              ?>
              <input  style="width:248px" id="doc" name="doc" type="file" />
            </div>
          </div>
        </div>
        <div class="control-group column-right" id="form_total_bhp">
          <label class="control-label" for="total_bhp">Total Bhp</label>
          <div class="controls">
            <h4 id="total_bhp">Rp. 0,-</h4>
          </div>
        </div>

        <div class="clearfix"></div>
        <div class="clearfix"></div>
      </fieldset>
      <?php
      $arr = array(
          //'no'=>array("No",30),
          'station_no' => array("Station ID", 60),
          'region' => array("Region", 95),
          'site_code' => array("Site ID", 40, "center"),
          'vendor_id' => array("Vendor ID", 75),
          //		'nearendsite'=>array('NE Name',120),
          'equipment' => array('Equipment', 80),
          'freq' => array('Freq', 70),
          //		'freq_tx'=>array('Tx Freq',70),
          //		'freq_rx'=>array('Rx Freq',70),
          'bhp' => array('BHP (Rp.)', 90)
              //'document'=>array('Document',120)
      );
      ?>

      <div class="row-fluid" style="max-height: 200px;overflow: auto;margin-top:20px">
        [<a href="#" id="ShowLinks" title="show">Show Full Links</a>]
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
            $pair_id = null;
            foreach ($app_detail as $row) {
              echo "
                <tr>
                  <input type='hidden' name='app_link_id[$i]' value='{$row->app_link_id}'/>
                  <td><input type='text' id='station_no$i' value='{$row->station_identifier}'  style='width:40px;text-align:center' name='station_id[$i]'/></td>
                  <td>{$row->region}</td>
                  <td>{$row->site_code}</td>
                  <td>{$row->vendor_id}</td>
                  <td>{$row->equipment_type}</td>
                  <td>TX : {$row->transmitter_freq} <br> RX : {$row->receiver_freq}</td>
                  <td>{$row->transmitter_polarization_desc}</td>
                  <td><input type='text' id='bhp$i' value='{$row->bhp}' class='input-medium iBhp currency span12 cBhp' style='text-align:right;' maxlength='14' autocomplete='off' name='bhp[$i]'/></td>
                </tr>
                ";
              $i++;
//              echo '<tr>';
//              echo "<input type='hidden' name='resi_link[$i]' value='" . $row->resi_link_id . "'/>";
//              $row->no = $i;
//              foreach ($arr as $key => $val) {
//                ($row['bhp'] == 0) ? $row['bhp'] = $row['bhp_migrasi'] : '';
//                if ($key == 'bhp') {
//                  if ($pair_id == $row['pair_id']) {
//                    $strval = "<input style='text-align:right' 
//												 type='text' id='bhp$i' name='bhp[$i]'  maxlength='14' autocomplete='off'
//												 class='iBhp currency span12 cBhp" . $row['pair_id'] . "' value='" . $row['bhp'] . "'/>";
//                  } else {
//                    $pair_id = $row['pair_id'];
//                    $strval = "<input style='text-align:right;' maxlength='14' autocomplete='off'
//												 type='text' id='bhp$i' name='bhp[$i]' pair='" . $pair_id . "'
//												 class='iBhp currency span12 cBhp' value='" . $row['bhp'] . "'/>";
//                  }
//                } elseif ($key == 'site_code_ne') {
//                  $strval = $row[$key] . "<br><span class='sinfo'>" . $row["nearendsite"] . "</span>";
//                } elseif ($key == 'site_code_fe') {
//                  $strval = $row[$key] . "<br><span class='sinfo'>" . $row["farendsite"] . "</span>";
//                } elseif ($key == 'freq') {
//                  $strval = "TX : " . $row->transmiiter_freq . "<br>";
//                  $strval.= "RX : " . $row->receiver_freq;
//                } elseif ($key == 'station_no') {
//                  $strval = "<input type='text' id='station_no$i' value='" . $row[$key] . "'  style='width:40px;text-align:center' name='station_id[$i]'/>" . "\r\n";
//
//                  //		}elseif($key=='freq_rx'){
//                  //			$strval=$row[$key]. "(".$row["freq_rx_polar"].")";
//                  //		elseif($key=='document')
//                  //			$strval="<input type='file' id='doc$i' name='doc[$i]' class='iDoc span12'/>"."\r\n";
//                }
//                else
//                  $strval = $row[$key];
//
//                echo "<td class='$key'>$strval</td>" . "\r\n";
//              }
//              echo '</tr>';
            }
            ?>								
          </tbody>
        </table>
      </div>
      <div class="row-fluid">
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
              if (confirm('Are you sure want to close ?'))
                $.fancybox.close();
            });</script>
        </div>       

      </div>
    </div>

  </div>

</div>

</form>
<script type="text/javascript">
  function sumBhp() {
    total_bhp = 0;
    $('.iBhp').each(function() {
      val = $(this).val();
      val = val.split(".")[0];
      val = val.replace(/,/g, "");
      total_bhp += parseInt(val);
    });
    $('#total_bhp').html('Rp. ' + number_format(total_bhp, 2, ',', '.'));
  }
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
          if ($(this).attr('id') == 'app_issued') {
            date = $.datepicker.parseDate(instance.settings.dateFormat, dateText, instance.settings);
            date.setMonth(date.getMonth() + 12);
            date.setDate(date.getDate() - 1);
            $("#app_expired").datepicker("setDate", date);
          } else if ($(this).attr('id') == 'spp_issued_date') {
            date = $.datepicker.parseDate(instance.settings.dateFormat, dateText, instance.settings);
            date.setDate(date.getDate() + 60);
            $("#spp_due_date").datepicker("setDate", date);
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
  var currencyFormat = {
    fields: document.getElementsByClassName('currency'),
    init: function() {
      for (var i = 0; i < this.fields.length; i++) {
        // load default
        this.fields[i].value = number_format(this.fields[i].value, 2);
        // onfocus
        this.fields[i].onfocus = function() {
          var prec = this.value.split('.');
          if (prec[1] == '00') {
            this.value = number_format(this.value, '', '', '');
          } else {
            this.value = number_format(this.value, 2, '.', '');
          }
        };
        // onblur
        this.fields[i].onblur = function(e) {
          this.value = number_format(this.value, 2);
        };
        // onkeyup
        this.fields[i].onkeyup = function(e) {

          pair = $(this).attr('pair');
          $('.cBhp' + pair).val(number_format($(this).val(), 2));
          if ((e.keyCode >= 48 && e.keyCode <= 57) || e.keyCode == 8)
            sumBhp();
          if (!isNumeric(this.value)) {
            alert('Please enter only numbers or decimal points.');
            setTimeout(function() {
              e.target.focus()
            });
            return false;
          }
        };
      }
    },
    removeFormat: function() {
      for (var i = 0; i < this.fields.length; i++) {
        var prec = this.fields[i].value.split('.');
        if (prec[1] == '00') {
          this.fields[i].value = number_format(this.fields[i].value, '', '', '');
        } else {
          this.fields[i].value = number_format(this.fields[i].value, 2, '.', '');
        }
      }
    }
  };
  var isNumeric = function(sText) {
    var validChars = "0123456789.-";
    var isNumber = true;
    var character;
    for (var i = 0; i < sText.length && isNumber == true; i++) {
      character = sText.charAt(i);
      if (validChars.indexOf(character) == -1) {
        isNumber = false;
      }
    }

    return isNumber;
  };
  function number_format(number, decimals, dec_point, thousands_sep) {

    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  }


  jQuery(document).ready(function() {
    dateFormat.init();
    currencyFormat.init();
    sumBhp();
    $('.fancybox-wrap').draggable({handle: ".widget-head"});
    $("#add-form-app").ajaxForm({dataType: 'json', success: processJson, error: errorMessage, beforeSubmit: validate});
    $("#add-form-app").submit(function(event) {
    });
    $('.submit').click(function() {
      currencyFormat.removeFormat();
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
        if (checkContent() == false) {
          alert('Invalid BHP number or Empty BHP number,please check your BHP number.');
          $('#dState').html('<span class="popnotif warning">Invalid BHP number or Empty BHP number,please check your BHP number.</span>').parent().slideDown();
          $('.submit').removeAttr('disabled');
          return false;
        }
      } else {
        currencyFormat.init();
        return false;
      }
    }

    function checkContent() {
      isOK = true;
      //  	val = $(this).val();
      // 	val=val.split(".")[0];
      // 	val=val.replace(/,/g, "");

      // $('.dynamicTable').find('.iBhp').each(function(){
      // 	if (val==='' || isNaN(parseFloat(val)) || isNaN(val) ){
      // 	//	alert('Invalid BHP data : '+$(this).val())
      // 		isOK= false;
      // 	}
      // });

      return isOK;
    }

    function errorMessage(data) {
      var msg = 'Document failed to save,please try again.';
      $('.submit').removeAttr('disabled');
      $('#dState').html('<span class="popnotif warning">' + msg + '</span>');
      alert(msg);
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

    // $('.cBhp').keyup(function(event){
    // 	pair=$(this).attr('pair');
    // 	$('.cBhp'+pair).val($(this).val());
    // });
    // $('.iBhp').keyup(function(event) {
    // 	sumBhp();
    // });
    // function sumBhp(){
    // 	total_bhp=0;			
    // 	$('.iBhp').each(function(){
    // 		val = $(this).val();
    // 		if (val=='' || isNaN(parseFloat(val)))
    // 			val="0";

    // 		total_bhp+=parseInt(val);
    // 	});
    // 	$('#total_bhp').html('Rp. ' + number_format(total_bhp,0,',','.') +',-');
    // }


    $('#ShowLinks').click(function() {
      title = $(this).html();
      parent = $(this).parent();
      if (title == 'Show Full Links') {
        $('fieldset').slideUp();
        parent.css('max-height', '450px').css('margin-top', '0px');
        // $('.freq_tx').css('display','table-cell');
        // $('.freq_rx').css('display','table-cell');
        // $('.vendor_ne').css('display','table-cell');
        // $('.vendor_fe').css('display','table-cell');
        $('.equipment').css('display', 'table-cell');
        $(this).html('Show with Data');
      } else {
        $('fieldset').slideDown();
        parent.css('max-height', '200px').css('margin-top', '20px');
        // $('.freq_tx').css('display','none');
        // $('.freq_rx').css('display','none');
        // $('.vendor_ne').css('display','none');
        // $('.vendor_fe').css('display','none');
        $('.equipment').css('display', 'none');
        $(this).html('Show Full Links');
      }
    })

    $("#add-form-app").validate({
      rules: {
        app_no: "required",
        spp_no: "required",
        spp_start_date: "required",
        spp_due_date: "required",
        give_to_fin_date: "required",
      },
      messages: {
        app_no: "Please enter App No",
        spp_no: "Please enter SPP No",
        spp_start_date: "Please enter issued date",
        spp_due_date: "Please enter due date",
        give_to_fin_date: "Please enter submitted to finance date",
      }
    });

  });

</script>
