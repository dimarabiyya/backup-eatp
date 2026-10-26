 
<!--    
    Document   : lookup_spp_finance
    Created on : Jul 1, 2015, 1:44:27 PM
    Author     : tumbas
    Description:
        Purpose of the PHP Web Page follows.
-->

<script src="<?php echo base_url(); ?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/easyui.css">

<style type="text/css">
  .datagrid-body .datagrid-cell{
    /*word-wrap: break-word;*/
    /*white-space: normal;*/
  }

  .modal {
    left:50%;
    width:800px;
  }

  .datagrid-header-rownumber { width:50px; }

  .datagrid-cell-rownumber { width:50px; }

  select { width:auto; }
</style>

<div class="widget">
  <div class="widget-head"><h1 class="heading glyphicons list"><i></i>Lookup Station</h1></div>
  <div class="widget-body">
    <div id="tb_lookup" class="form-horizontal" style="padding: 5px; width: auto">
      <div class="control-group">
        <label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
        <div class="input-append">
          <input name="q" id="q" type="text" placeholder="Type text to search ..">
          <button  onclick="doSearch('')" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
        </div>	
        <!--<a href="<?php echo base_url('isr/spp_to_finance/cover_letter_form'); ?>" class="btn">Create Cover Letter</a>-->
      </div>
    </div>
    <div id="dg" style="width: 1200px"></div>
    <div class="form-actions">
      <button type="button" class="btn btn-icon btn-close btn-primary glyphicons ok"><i></i>OK</button>
    </div>

  </div>

</div>

<script src="<?php echo base_url() ?>assets/js/number_format.js"></script>
<script src="<?php echo base_url() ?>assets/js/hehehe.js"></script>

<script type="text/javascript">

            function doSearch(i) {
              $('#dg' + i).datagrid('load', {
                q: $('#q' + i).val(),
              });
            }

            var currencyFormat = {
              fields: document.getElementsByClassName('currency'),
              init: function() {
                for (var i = 0; i < this.fields.length; i++) {
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

            function isNumeric(sText) {
              var validChars = "0123456789.-";
              var isNumber = true;
              var character;
              if (sText == "") {
                isNumber = false;
              } else {
                for (var i = 0; i < sText.length && isNumber == true; i++) {
                  character = sText.charAt(i);
                  if (validChars.indexOf(character) == -1) {
                    isNumber = false;
                  }
                }
              }
              return isNumber;
            }

            function generateStationId() {
              $('.station_identifier').each(function(index, element) {
                $(element).val(index + 1);
              });
            }

            function clearNull() {
              $('input').each(function(index, element) {
                if ($(element).val() == 'null') {
                  $(element).val('');
                }
              });
            }

            $(document).ready(function() {
              $("#q").keydown(function(e) {
                var code = e.which; // recommended to use e.which, it's normalized across browsers
                if (code == 13) {
                  doSearch('');
                  e.preventDefault();
                }
              });
              var stationId = 1;
              $('#dg').datagrid({
                idField: "bts_id",
//      textField: 'spp_no',
                rownumbers: true,
                method: 'post',
                toolbar: "#tb_lookup",
                fitColumns: false,
                striped: true,
                pagination: true,
                singleSelect: false,
                nowrap: false,
                pageSize: '10',
                pageList: [10, 15, 20, 50, 100],
                url: '<?php echo base_url('isr_bts/resi/get_bts_list'); ?>',
                columns: [[
                    {field: 'ck', title: '', width: '80', checkbox: true},
                    {field: 'vendor_id', title: 'Vendor ID', width: '100'},
                    {field: 'morphology_desc', title: 'Morphology', width: '100'},
                    {field: 'bts_mode_desc', title: 'BTS Mode', width: '100'},
                    {field: 'bts_brand_desc', title: 'BTS Brand', width: '100'},
                    {field: 'bts_type_desc', title: 'BTS Type', width: '100'},
                    {field: 'height_asl', title: 'Height ASL', width: '100'},
                    {field: 'radius_of_operation', title: 'Radius of Operation', width: '100'},
                    {field: 'equipment_type', title: 'Equipment Type', width: '100'},
                    {field: 'equipment_output_power', title: 'Equipment Output Power', width: '75', align: 'center'},
                    {field: 'bandwidth', title: 'Bandwidth', width: '100', align: 'right'},
                    {field: 'tx_freq', title: 'Tx Freq', width: '150'},
                    {field: 'rx_freq', title: 'Rx Freq', width: '150'},
                    {field: 'antenna_type', title: 'Antenna Type', width: '150'},
                    {field: 'feeding_loss', title: 'Feeding Loss', width: '150'},
                    {field: 'connector_loss', title: 'Connector Loss', width: '150'},
                    {field: 'branch_loss', title: 'Branch Loss', width: '150'},
                    {field: 'antenna_height_agl', title: 'Antenna Height AGL', width: '150'},
                    {field: 'transmitter_polarization_desc', title: 'Transmitter Polatization', width: '150'},
                  ]],
                onCheck: function(index, row) {
                  var rowContent = $('#row_data_' + row.bts_id).html()
                  if (rowContent == null) {
//                    var morphologyId = 'morphology_' + row.bts_id;
//                    var btsModeId = 'bts_mode_' + row.bts_id;
//                    var btsBrandId = 'bts_brand_' + row.bts_id;
//                    var btsTypeId = 'bty_type_' + row.bts_id;
                    var transPolarId = 'trans_polar_' + row.bts_id;
                    var rowData = '<tr class="row_data" id="row_data_' + row.bts_id + '">';
                    rowData += '<input type="hidden" name="site_id[]" value="' + row.site_id + '">';
                    rowData += '<input type="hidden" name="vendor_id[]" value="' + row.vendor_id + '">';
                    rowData += '<input type="hidden" name="bts_id[]" value="' + row.bts_id + '">';
                    rowData += '<td><input class="input-mini text-right" type="text" name="service_id[]" value="10"></td>';
                    rowData += '<td><input class="input-mini text-right" type="text" name="subservice_id[]" value="355"></td>';
                    rowData += '<td><input class="input-mini text-right" type="text" name="client_id[]" value="100604"></td>';
                    rowData += '<td><input class="input-mini text-right" type="text" name="application_identifier[]"  value="1"></td>';
                    rowData += '<td><input class="input-mini text-right station_identifier" type="text" name="station_identifier[]"></td>';
                    rowData += '<td><input class="input-mini text-right" type="text" name="link_id[]" value="1"></td>';
                    rowData += '<td><input class="input-medium" type="text" name="station_name[]" value="' + row.station_name + '"></td>';
                    rowData += '<td><input class="input-mini text-right" type="text" name="height_asl[]" value="' + row.height_asl + '"></td>';
                    var lat = "<div style='height:25px'><label style='float:left;width:30px'>Lat :</label> <input style='width:20px;text-align:right' type='text' class='lat_deg' name='lat_deg[]' value='" + row.lat_deg + "'/><input style='width:20px;text-align:right' type='text' class='lat_min' name='lat_min[]' value='" + row.lat_min + "'/><input style='width:40px;text-align:right' type='text' class='lat_sec' name='lat_sec[]' value='" + row.lat_sec + "'/><input style='width:20px;text-align:center' type='text' class='lat_dir_ind_n_s' name='lat_dir_ind_n_s[]' value='" + row.lat_dir + "'/></div>";
                    var long = "<div  style='height:25px'><label style='float:left;width:30px'>Lng :</label> <input style='width:20px;text-align:right' type='text' class='long_deg' name='long_deg[]' value='" + row.long_deg + "'/><input style='width:20px;text-align:right' type='text' class='long_min' name='long_min[]' value='" + row.long_min + "'/><input style='width:40px;text-align:right' type='text' class='long_sec' name='long_sec[]' value='" + row.long_sec + "'/><input style='width:20px;text-align:center' type='text' class='long_dir_ind_e_w' name='long_dir_ind_e_w[]' value='" + row.long_dir + "'/></div>";
                    rowData += "<td>" + lat + long + "</td>";
                    rowData += '<td><input class="input-small text-right" type="text" name="radius_of_operation[]" value="' + row.radius_of_operation + '"></td>';
                    rowData += '<td><input class="input-small" type="text" name="site_province[]" value="' + row.site_province + '"></td>';
                    rowData += '<td><input class="input-small" type="text" name="site_city[]" value="' + row.site_city + '"></td>';
                    rowData += '<td><input class="input-small" type="text" name="site_district[]" value="' + row.site_district + '"></td>';
                    rowData += '<td><input class="input-large" type="text" name="area_of_service[]" value="' + row.site_address + '"></td>';
                    rowData += '<td><input class="input-small" type="text" name="equipment_type[]" value="' + row.equipment_type + '"></td>';
                    rowData += '<td><input class="input-medium text-right" type="text" name="equipment_output_power[]" value="' + row.equipment_output_power + '"></td>';
                    rowData += '<td><input class="input-small text-right" type="text" name="bandwidth[]" value="' + row.bandwidth + '"></td>';
                    rowData += '<td><input class="input-mini text-right" type="text" name="tx_freq[]" value="' + row.tx_freq + '"></td>';
                    rowData += '<td><input class="input-mini text-right" type="text" name="rx_freq[]" value="' + row.rx_freq + '"></td>';
                    rowData += '<td><input class="input-small" type="text" name="antenna_type[]" value="' + row.antenna_type + '"></td>';
                    rowData += '<td><input class="input-mini text-right" type="text" name="feeding_loss[]" value="' + row.feeding_loss + '"></td>';
                    rowData += '<td><input class="input-mini text-right" type="text" name="connector_loss[]" value="' + row.connector_loss + '"></td>';
                    rowData += '<td><input class="input-mini text-right" type="text" name="branch_loss[]" value="' + row.branch_loss + '"></td>';
                    rowData += '<td><input class="input-medium text-right" type="text" name="antenna_height_agl[]" value="' + row.antenna_height_agl + '"></td>';
                    rowData += '<td><select class="input-medium" id="' + transPolarId + '" name="transmitter_polarization[]"><?php
foreach ($transmitter_polarization_opt as $value) {
  echo "<option value=\'{$value->lookup_value}\'>{$value->lookup_desc}</option>";
}
?></select></td>';
                    rowData += '</tr>';
                    $('#table_data').append(rowData);
                    generateStationId();
                    clearNull()
                    $('#' + transPolarId + ' option[value=' + row.transmitter_polarization + ']').attr('selected', 'selected');
                  }
                },
                onUncheck: function(index, row) {
                  $('#row_data_' + row.bts_id).remove();
                  generateStationId();
                },
                onLoadSuccess: function(data) {
                  $('.row_data').each(function(index) {
                    var bts_id = $(this).attr('id');
                    for (var i = 0; i < data.rows.length; i++) {
                      if ('row_data_' + data.rows[i].bts_id == bts_id) {
                        console.log(data.rows[i].bts_id);
                        console.log(bts_id);
                        $('#dg').datagrid('checkRow', i);
                      }
                    }
                  });
                },
              });

              $(".btn-close").click(function(e) {
                $.fancybox.close();

              });

            });
</script>