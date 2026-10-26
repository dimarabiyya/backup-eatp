<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
  .sub_table {margin-left: 25px;}
</style>
<form enctype="multipart/form-data" id="file_form" name="file_form" method="post" style="display: none">
  <div class="row-fluid" style="margin-bottom: 10px;">
    <b>Application File ( xls / xlsx ) : </b> <input name="file_app" id="file_app" type="file" accept=".xls, .xlsx">
    <!--<button name="validate" class="btn btn-primary btn-icon glyphicons circle_ok" type="submit"><i></i>Validate</button>-->
  </div>
</form>

<?php echo form_open('', 'id="frmBTS" class="form-horizontal'); ?>

<div class="widget">
  <div class="widget-head heading-outer">
    <h4 class="heading"><i></i> BTS Info</h4>
    <div class="heading-buttons pull-right">
      <a type="button" onclick="$('#file_app').click()//loadImportForm()" href="#" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons file_export"><i></i> Import</a>
      <a href="<?php echo base_url("file/upload/isr/template bts import.xls"); ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons download"><i></i> Download Template</a>
    </div>
  </div>

  <div class="widget-body">

    <div class="control-group">
      <label class="control-label" style="text-align:left">Morphology:</label>
      <div class="controls">
        <?php
        $lookup = $this->core->get_lookup('bts_morphology');
        $options = array(0 => '- Choose one -');
        foreach ($lookup as $lo) {
          $options[$lo['id']] = $lo['desc'];
        }
        echo @form_dropdown('morphology', $options, $bts_info[0]['morphology'], 'id="morphology"');
        ?>
      </div>
      <div class="separator bottom"></div>
    </div>

    <div style="width: 100%; overflow: auto">
      <table id="tblBTS" class="table table-bordered table-condensed" style="width:2000px;">
        <thead>
          <tr>
            <th style="width: 30px">&nbsp;</th>
            <th style="min-width: 80px">&nbsp;</th>
            <th style="text-align:center; width: 100px">Vendor ID</th>
            <th style="text-align:center; width: 100px">CDMA ID</th>
            <th style="text-align:center; width: 100px">System</th>
            <th style="text-align:center; width: 100px">BTS Brand</th>
            <th style="text-align:center; width: 100px">BTS Mode</th>
            <th style="text-align:center; width: 100px">BTS Type</th>
            <?php
            $headers = array(
                'height_asl' => 100,
                'radius_of_operation' => 100,
                'equipment_type' => 100,
                'equipment_output_power' => 150,
                'bandwidth' => 100,
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
          <?php
          if ($this->uri->segment(3) == 'edit' && count($bts_info)) {
            $btsinfo_counter = 1;
            $sub_table_counter = 1;
            $sub_row_counter = 1;
            $sub_sub_table_counter = 1;
            $sub_sub_row_counter = 1;

            foreach ($bts_info as $bts) {
              ?>
              <tr id="btsinfo-row-<?php echo $btsinfo_counter ?>">
                <td>
                  <button type="button" class="btn btn-danger btn-small" onclick="BTS_INFO.removeRow(<?php echo $btsinfo_counter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></button>
                </td>
                <td>
                  <button type="button" class="btn btn-small" onclick="addBtsDetail(<?php echo $bts['bts_id']; ?>)" title="Add Detail">Add Detail</button>
                </td>
                <td>
                  <?php
                  echo @form_hidden('btsId[]', $bts['bts_id']);

                  echo @form_input('vendorId[]', $bts['vendor_id'], 'class="input-small"');
                  ?>
                </td>
                <td>
                  <input type="text" name="cdma_id[]" value="<?php echo $bts['cdma_id']; ?>" class="input-small">
                </td>
                <td>
                  <?php
                  $lookup = $this->core->get_lookup('bts_system');
                  $options = array(0 => '- Choose one -');
                  foreach ($lookup as $lo) {
                    $options[$lo['id']] = $lo['desc'];
                  }
                  echo @form_dropdown('bts_system[]', $options, $bts['bts_system'], 'id="bts_system" class="input-medium"');
                  ?>
                </td>
                <td>
                  <?php
                  $lookup = $this->core->get_lookup('bts_brand');
                  $options = array(0 => '- Choose one -');
                  foreach ($lookup as $lo) {
                    $options[$lo['id']] = $lo['desc'];
                  }
                  echo @form_dropdown('brand[]', $options, $bts['bts_brand'], 'id="brand" class="input-medium"');
                  ?>
                </td>
                <td>
                  <?php
                  $lookup = $this->core->get_lookup('bts_mode');
                  $options = array(0 => '- Choose one -');
                  foreach ($lookup as $lo) {
                    $options[$lo['id']] = $lo['desc'];
                  }
                  echo @form_dropdown('mode[]', $options, $bts['bts_mode'], 'id="mode" class="input-medium"');
                  ?>
                </td>
                <td>
                  <?php
                  $lookup = $this->core->get_lookup('bts_type');
                  $options = array(0 => '- Choose one -');
                  foreach ($lookup as $lo) {
                    $options[$lo['id']] = $lo['desc'];
                  }
                  echo @form_dropdown('type[]', $options, $bts['bts_type'], 'id="type" class="input-medium"');
                  ?>
                </td>
                <td><input type="text" name="height_asl[]" class="input-medium" value="<?php echo $bts['height_asl']; ?>"></td>
                <td><input type="text" name="radius_of_operation[]" class="input-medium" value="<?php echo $bts['radius_of_operation']; ?>"></td>
                <td><input type="text" name="equipment_type[]" class="input-medium" value="<?php echo $bts['equipment_type']; ?>"></td>
                <td><input type="text" name="equipment_output_power[]" class="input-medium" value="<?php echo $bts['equipment_output_power']; ?>"></td>
                <td><input type="text" name="bandwidth[]" class="input-medium" value="<?php echo $bts['bandwidth']; ?>"></td>
                <td><input type="text" name="rx_freq[]" class="input-medium" value="<?php echo $bts['rx_freq']; ?>"></td>
                <td><input type="text" name="antenna_type[]" class="input-medium" value="<?php echo $bts['antenna_type']; ?>"></td>
                <td><input type="text" name="feeding_loss[]" class="input-medium" value="<?php echo $bts['feeding_loss']; ?>"></td>
                <td><input type="text" name="connector_loss[]" class="input-medium" value="<?php echo $bts['connector_loss']; ?>"></td>
                <td><input type="text" name="branch_loss[]" class="input-medium" value="<?php echo $bts['branch_loss']; ?>"></td>
                <td><input type="text" name="antenna_height_agl[]" class="input-medium" value="<?php echo $bts['antenna_height_agl']; ?>"></td>
                <td>
                  <?php
                  $lookup = $this->core->get_lookup('transmission_polarization');
                  $options = array(0 => '- Choose one -');
                  foreach ($lookup as $lo) {
                    $options[$lo['id']] = $lo['desc'];
                  }
                  echo @form_dropdown('transmitter_polarization[]', $options, $bts['transmitter_polarization'], 'id="transmitter_polarization" class="input-medium"');
                  ?>
                </td>
                  <!--<td><input type="text" name="vendor_id[]"></td>-->
              </tr>
    <!--              <tr>
                <td></td>
                <td colspan="4">
                  <table class="table table-bordered table-condensed">
                    <tbody id="sub_table_<?php echo $sub_table_counter; ?>">
                      <tr id="sub_row_<?php echo $sub_row_counter; ?>">
                        <td rowspan="2" style="max-width: 200px"><button type="button" class="btn btn-danger btn-small" onclick="BTS_INFO.removeSubRow(<?php echo $btsinfo_counter; ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>
                        <td style="max-width: 200px"><input type="text" name="#" placeholder="#" class=" input-small" value="<?php echo $bts['#']; ?>"></td>
                        <td style="max-width: 200px"><input type="text" name="tx_freq[]" placeholder="TX Freq" class="input-small" value="<?php echo $bts['tx_freq']; ?>"></td>
                        <td style="max-width: 200px">
              <?php
              $dd_opt = array(
                  '' => '- Choose One -',
                  '1' => 'TDD',
                  '2' => 'FDD',
              );
              echo form_dropdown('dd[]', $dd_opt, '', 'class="input-medium"');
              ?>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">
                          <table class="table table-bordered table-condensed">
                            <tbody id="sub_sub_table_<?php echo $sub_sub_table_counter; ?>">
                              <tr id="sub_sub_row_<?php echo $sub_sub_row_counter; ?>">
                                <td><button type="button" class="btn btn-danger btn-small" onclick="BTS_INFO.removeSubSubRow(<?php echo $sub_sub_row_counter; ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>
                                <td style="max-width: 200px"><input type="text" name="sector" placeholder="Sector" class=" input-small" value="<?php echo $bts['sector']; ?>"></td>
                                <td style="max-width: 200px"><input type="text" name="tilt" placeholder="Tilt" class=" input-small" value="<?php echo $bts['tilt']; ?>"></td>
                              </tr>
                            </tbody>
                            <tfoot>
                              <tr><td><button type="button" class="btn btn-success btn-small" onclick="BTS_INFO.addSubSubRow(<?php echo $sub_table_counter; ?>)" title="Add row"><i class="icon-plus-sign icon-white"></i></button></td></tr>
                            </tfoot>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="4">
                          <button type="button" class="btn btn-success btn-small" onclick="BTS_INFO.addSubRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </td>
              </tr>-->
              <?php
              $btsinfo_counter++;
            }
          } else {
            ?>

            <tr id="btsinfo-row-1">
              <td>
                <button type="button" class="btn btn-danger btn-small" onclick="BTS_INFO.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></button>
              </td>
              <td>
                <?php
                echo @form_hidden('btsId[]');

                echo @form_input('vendorId[]', $bts['vendor_id'], 'class="input-medium"');
                ?>
              </td>
              <td>
                <?php
                $lookup = $this->core->get_lookup('bts_brand');
                $options = array(0 => '- Choose one -');
                foreach ($lookup as $lo) {
                  $options[$lo['id']] = $lo['desc'];
                }
                echo @form_dropdown('brand[]', $options, '', 'id="brand" class="input-medium"');
                ?>
              </td>
              <td>
                <?php
                $lookup = $this->core->get_lookup('bts_mode');
                $options = array(0 => '- Choose one -');
                foreach ($lookup as $lo) {
                  $options[$lo['id']] = $lo['desc'];
                }
                echo @form_dropdown('mode[]', $options, '', 'id="mode" class="input-medium"');
                ?>
              </td>
              <td>
                <?php
                $lookup = $this->core->get_lookup('bts_type');
                $options = array(0 => '- Choose one -');
                foreach ($lookup as $lo) {
                  $options[$lo['id']] = $lo['desc'];
                }
                echo @form_dropdown('type[]', $options, '', 'id="type" class="input-medium"');
                ?>
              </td>
              <td><input type="text" name="height_asl[]" class="input-medium"></td>
              <td><input type="text" name="radius_of_operation[]" class="input-medium"></td>
              <td><input type="text" name="equipment_type[]" class="input-medium"></td>
              <td><input type="text" name="equipment_output_power[]" class="input-medium"></td>
              <td><input type="text" name="bandwidth[]" class="input-medium"></td>
              <td><input type="text" name="tx_freq[]" class="input-medium"></td>
              <td><input type="text" name="rx_freq[]" class="input-medium"></td>
              <td><input type="text" name="antenna_type[]" class="input-medium"></td>
              <td><input type="text" name="feeding_loss[]" class="input-medium"></td>
              <td><input type="text" name="connector_loss[]" class="input-medium"></td>
              <td><input type="text" name="branch_loss[]" class="input-medium"></td>
              <td><input type="text" name="antenna_height_agl[]" class="input-medium"></td>
              <td>
                <?php
                $lookup = $this->core->get_lookup('transmission_polarization');
                $options = array(0 => '- Choose one -');
                foreach ($lookup as $lo) {
                  $options[$lo['id']] = $lo['desc'];
                }
                echo @form_dropdown('transmitter_polarization[]', $options, $bts['transmitter_polarization'], 'id="transmitter_polarization" class="input-medium"');
                ?>
              </td>
            </tr>
            <?php
          }
          ?>

        </tbody>
        <tfoot>
          <tr>
            <td colspan="19">
              <button type="button" class="btn btn-success btn-small" onclick="BTS_INFO.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></button>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>

  </div>
</div>

<div class="form-actions">
  <button class="btn btn-primary btn-icon glyphicons circle_ok" id="btnSave"><i></i> Save</button>
  <button class="btn btn-primary btn-icon glyphicons circle_ok" id="btnSaveExit"><i></i> Save &amp; Exit</button>
  <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href = '<?php echo site_url('/site_info/equipment') ?>'"><i></i>Cancel</button>
</div>

<?php echo form_close() ?>

<script>
        var btnAction = '';
        document.getElementById('btnSave').onclick = function() {
          btnAction = this.id;
        };
        document.getElementById('btnSaveExit').onclick = function() {
          btnAction = this.id;
        };

        document.getElementById('frmBTS').onsubmit = function() {

          var data = serialize(this);
<?php if ($this->uri->segment(3) == 'add') { ?>
            data += '&cmd=ADD_BTS_INFO';
<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
            data += '&cmd=EDIT_BTS_INFO';
<?php } ?>
          data += '&act=SAVE';
          data += '&siteId=' + document.getElementsByName('siteId')[0].value;
          data += '&siteCode=' + document.getElementById('siteCode').getAttribute('data-sitecode');

          $.ajax({
            url: document.URL,
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: function() {
              siteOverlay.top = '200px';
              siteOverlay.create();
            },
            success: function(json) {
              if (typeof json.loggedin != 'undefined' && !json.loggedin) {
                alert(json.message);
                window.location.href = json.redirect_url;
              }

              var alertMessage = document.getElementsByClassName('alert');
              for (var i = 0; i < alertMessage.length; i++) {
                alertMessage[i].parentNode.removeChild(alertMessage[i]);
              }

              /* var siteId = document.getElementsByName('siteId');
               for (var i = 0; i < siteId.length; i++) {
               siteId[i].value = json['site_id'];
               } */

              if (typeof json['error'] != 'undefined' && json['error']) {
                var message = '\
                    <ul style="text-align:left">';
                for (var i = 0; i < json['message'].length; i++) {
                  message += '<li>' + json['message'][i] + '</li>';
                }
                message += '</ul>';

                coolNotification(message, 'error');
              } else {
                coolNotification('<h3>Success</h3>' + json['message'], 'success', function() {
<?php if ($this->uri->segment(3) == 'add') { ?>

                    if (btnAction == 'btnSave') {
                      window.location.href = '<?php echo site_url('site_info/equipment/edit') ?>/' + json.site_id;
                    } else if (btnAction == 'btnSaveExit') {
                      window.location.href = '<?php echo site_url('site_info/equipment') ?>';
                    }

<?php } else { ?>

                    if (btnAction == 'btnSave') {
                      // refresh content tab
                      var tab = $('#equipmentTab').tabs('getSelected');
                      $('#equipmentTab').tabs('update', {
                        tab: tab,
                        options: {
                          href: document.URL + '?load_content=BTS_INFO&site_id=' + document.getElementsByName('siteId')[0].value
                        }
                      });

                    } else if (btnAction == 'btnSaveExit') {
                      window.location.href = '<?php echo site_url('site_info/equipment') ?>';
                    }

<?php } ?>
                });
              }

              window.scroll(0, 0);
            },
            complete: function() {
              siteOverlay.remove();
            },
            error: function() {
              coolNotification('<h3>Error!</h3> An error occured, please try again or contact the administrator.', 'error');
            }
          });

          return false;
        };

<?php if ($this->uri->segment(3) == 'edit' && $bts_info) { ?>
          var btsCounter = <?php echo $btsinfo_counter ?>;
          var subTableCounter = <?php echo $sub_table_counter ?>;
          var subRowCounter = <?php echo $sub_row_counter; ?>;
          var subSubTableCounter = <?php echo $sub_sub_table_counter ?>;
          var subSubRow = <?php echo $sub_sub_row_counter; ?>;
<?php } else { ?>
          var btsCounter = 2;
<?php } ?>

        var BTS_INFO = {
          addRow: function() {

            var html = '\
            <tr id="btsinfo-row-' + btsCounter + '">\
                <td class="left"><button type="button" class="btn btn-danger btn-small" onclick="BTS_INFO.removeRow(' + btsCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>\\n\
                  <td></td>\
                <td>\
                    <input type="hidden" name="btsId[]">\
                    \
                    <input type="text" name="vendorId[]" class="input-small">\
                </td>';
            html += '<td><input type="text" name="cdma_id[]" class="input-small"></td>';
//          html += '<td><input type="text" name="system[]" class="input-medium"></td>';
            html += '\<td>\
                    <select name="bts_system[]" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_system');
foreach ($lookup as $lo) {
  ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                </td>\
                <td>\
                    <select name="brand[]" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_brand');
foreach ($lookup as $lo) {
  ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                </td>\
                <td>\
                    <select name="mode[]" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_mode');
foreach ($lookup as $lo) {
  ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                </td>\
                <td>\
                    <select name="type[]" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_type');
foreach ($lookup as $lo) {
  ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                </td>\
        ';

            html += '<td><input type="text" name="height_asl[]" class="input-medium"></td>';
            html += '<td><input type="text" name="radius_of_operation[]" class="input-medium"></td>';
            html += '<td><input type="text" name="equipment_type[]" class="input-medium"></td>';
            html += '<td><input type="text" name="equipment_output_power[]" class="input-medium"></td>';
            html += '<td><input type="text" name="bandwidth[]" class="input-medium"></td>';
            html += '<td><input type="text" name="rx_freq[]" class="input-medium"></td>';
            html += '<td><input type="text" name="antenna_type[]" class="input-medium"></td>';
            html += '<td><input type="text" name="feeding_loss[]" class="input-medium"></td>';
            html += '<td><input type="text" name="connector_loss[]" class="input-medium"></td>';
            html += '<td><input type="text" name="branch_loss[]" class="input-medium"></td>';
            html += '<td><input type="text" name="antenna_height_agl[]" class="input-medium"></td>';
            html += '<td> <select name="transmitter_polarization[]" class="input-medium"> <option value="0">- Choose one -</option><?php
$lookup = $this->core->get_lookup('transmission_polarization');
foreach ($lookup as $lo) {
  ?><option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option><?php } ?></select></td>';
            html += '</tr>';
            $('#tblBTS>tbody').append(html);

            validateVendorId();

            btsCounter++;
          },
          removeRow: function(row) {
            var row = document.getElementById('btsinfo-row-' + row);

            coolConfirmation('<h3>Confirmation</h3>Are you sure delete this row?', function() {
              row.parentNode.removeChild(row);
            });
          },
          addSubRow: function() {
            subSubTableCounter++;
            subRowCounter++;
            var html = '<tr id="sub_row_' + subRowCounter + '">';
            html += '<td rowspan="2" style="max-width: 200px"><button type="button" class="btn btn-danger btn-small" onclick="BTS_INFO.removeSubRow(' + subRowCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>';
            html += '<td style="max-width: 200px"><input type="text" name="#" placeholder="#" class=" input-small" value="<?php echo $bts['#']; ?>"></td>';
            html += '<td style="max-width: 200px"><input type="text" name="tx_freq[]" placeholder="TX Freq" class="input-small" value="<?php echo $bts['tx_freq']; ?>"></td>';
            html += '<td style="max-width: 200px"><input type="text" name="system[]" placeholder="System" class="input-small" value="<?php echo $bts['system']; ?>"></td>';
            html += '</tr>';
            html += '<tr>';
            html += '<td colspan="3">';
            html += '<table class="table table-bordered table-condensed">';
            html += '<tbody id="sub_sub_table_' + subSubTableCounter + '">';
            html += '<tr>';
            html += '<td><button type="button" class="btn btn-danger btn-small" onclick="BTS_INFO.removeSubSubRow()" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>';
            html += '<td style="max-width: 200px"><input type="text" name="sector" placeholder="Sector" class=" input-small" value="<?php echo $bts['sector']; ?>"></td>';
            html += '<td style="max-width: 200px"><input type="text" name="tilt" placeholder="Tilt" class=" input-small" value="<?php echo $bts['tilt']; ?>"></td>';
            html += '</tr>';
            html += '</tbody>';
            html += '<tfoot>';
            html += '<tr><td><button type="button" class="btn btn-success btn-small" onclick="BTS_INFO.addSubSubRow(' + subSubTableCounter + ')" title="Add row"><i class="icon-plus-sign icon-white"></i></button></td></tr>';
            html += '</tfoot>';
            html += '</table>';
            html += '</td>';
            html += '</tr>';
            $('#sub_table_' + subTableCounter).append(html);
            console.log('addSubRow');
          },
          removeSubRow: function(row) {
            coolConfirmation('<h3>Confirmation</h3>Are you sure delete this row?', function() {
              $('#sub_row_' + row).next().remove();
              $('#sub_row_' + row).remove();
            });
          },
          addSubSubRow: function(subSubtable) {
            subSubRow++;
            var html = '<tr id="sub_sub_row_' + subSubRow + '">';
            html += '<td><button type="button" class="btn btn-danger btn-small" onclick="BTS_INFO.removeSubSubRow(' + subSubRow + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>';
            html += '<td style="max-width: 200px"><input type="text" name="sector" placeholder="Sector" class=" input-small" value="<?php echo $bts['sector']; ?>"></td>';
            html += '<td style="max-width: 200px"><input type="text" name="tilt" placeholder="Tilt" class=" input-small" value="<?php echo $bts['tilt']; ?>"></td>';
            html += '</tr>';

            $('#sub_sub_table_' + subSubtable).append(html);
            console.log(':-D');
          },
          removeSubSubRow: function(row) {
            coolConfirmation('<h3>Confirmation</h3>Are you sure delete this row?', function() {
              $('#sub_sub_row_' + row).remove();
            });
          },
        };

        var validateVendorId = function() {
          for (var i = 0; i < document.getElementsByName('vendorId[]').length; i++) {
            document.getElementsByName('vendorId[]')[i].onkeydown = function(e) {
              //console.log(String.fromCharCode(59));
              if (e.keyCode == 59 || e.keyCode == 186) {
                return false;
              }
            };
          }
        };

        validateVendorId();
        doPreviledgePerfield();

        var viewWidth = $(document).width() - 100;
        var viewHeight = $(document).height() - 100;
        function loadImportForm() {
          $.fancybox({
            href: "<?php echo  base_url('site_info/equipment/bts_load_import_form') ?>",
            type: 'ajax',
            minWidth: viewWidth,
            maxHeight: viewHeight,
            autoSize: true,
            autocenter: true,
            scrolling: true,
            fitToView: true,
            closeClick: false,
            openEffect: 'fade',
            closeEffect: 'fade',
            topRatio: 0,
            afterShow: function() {

            }
          });
        }

        $(document).ready(function() {

          $('#file_app').change(function() {
            var formId = document.getElementById("file_form");
            var formData = new FormData(formId);
            $.ajax({
              url: '<?php echo base_url('site_info/equipment/bts_read_import_file'); ?>',
              data: formData,
              type: 'post',
              dataType: 'json',
              contentType: false,
              processData: false,
              beforeSend: function() {
                block.show('Processing data');
              },
              success: function(data) {
                console.log(data);

                $.each(data, function(index, element) {
                  if ($('#vendor-id-' + element.vendor_id).val() == undefined) {
                    var html = '\
            <tr id="btsinfo-row-' + btsCounter + '">\
                <td class="left"><button type="button" class="btn btn-danger btn-small" onclick="BTS_INFO.removeRow(' + btsCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>\\n\
                  <td></td>\
                <td>\
                    <input type="hidden" name="btsId[]">\
                    \
                    <input type="text" name="vendorId[]" id="vendor-id-' + element.vendor_id + '" class="input-small"  value="' + element.vendor_id + '">\
                </td>';
                    html += '<td><input type="text" name="cdma_id[]" class="input-small"></td>';
//          html += '<td><input type="text" name="system[]" class="input-medium"></td>';
                    html += '\<td>\
                    <select name="bts_system[]" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_system');
foreach ($lookup as $lo) {
  ?>\
                                                                                    <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                </td>\
                <td>\
                    <select name="brand[]" id="bts-brand-' + btsCounter + '" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_brand');
foreach ($lookup as $lo) {
  ?>\
                                                                                    <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                </td>\
                <td>\
                    <select name="mode[]" class="input-medium" id="bts-mode-' + btsCounter + '">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_mode');
foreach ($lookup as $lo) {
  ?>\
                                                                                    <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                </td>\
                <td>\
                    <select name="type[]" id="bts-type-' + btsCounter + '" class="input-medium">\
                        <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('bts_type');
foreach ($lookup as $lo) {
  ?>\
                                                                                    <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                    </select>\
                </td>\
        ';

                    html += '<td><input type="text" name="height_asl[]" class="input-medium" value="' + element.height_asl + '"></td>';
                    html += '<td><input type="text" name="radius_of_operation[]" class="input-medium" value="' + element.radius_of_operation + '"></td>';
                    html += '<td><input type="text" name="equipment_type[]" class="input-medium" value="' + element.equipment_type + '"></td>';
                    html += '<td><input type="text" name="equipment_output_power[]" class="input-medium" value="' + element.equipment_output_power + '"></td>';
                    html += '<td><input type="text" name="bandwidth[]" class="input-medium" value="' + element.bandwidth + '"></td>';
                    html += '<td><input type="text" name="rx_freq[]" class="input-medium" value="' + element.rx_freq + '"></td>';
                    html += '<td><input type="text" name="antenna_type[]" class="input-medium" value="' + element.antenna_type + '"></td>';
                    html += '<td><input type="text" name="feeding_loss[]" class="input-medium" value="' + element.feeding_loss + '"></td>';
                    html += '<td><input type="text" name="connector_loss[]" class="input-medium" value="' + element.connector_loss + '"></td>';
                    html += '<td><input type="text" name="branch_loss[]" class="input-medium" value="' + element.branch_loss + '"></td>';
                    html += '<td><input type="text" name="antenna_height_agl[]" class="input-medium" value="' + element.antenna_height_agl + '"></td>';
                    html += '<td> <select name="transmitter_polarization[]" id="transmitter-polarization-' + btsCounter + '" class="input-medium"> <option value="0">- Choose one -</option><?php
$lookup = $this->core->get_lookup('transmission_polarization');
foreach ($lookup as $lo) {
  ?><option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option><?php } ?></select></td>';
                    html += '</tr>';
                    $('#tblBTS>tbody').append(html);

                    $('#bts-mode-' + btsCounter + ' option[value=' + element.bts_mode + ']').attr('selected', 'selected');
                    $('#bts-brand-' + btsCounter + ' option[value=' + element.bts_brand + ']').attr('selected', 'selected');
                    $('#bts-type-' + btsCounter + ' option[value=' + element.bts_type + ']').attr('selected', 'selected');
                    $('#transmitter-polarization-' + btsCounter + ' option[value=' + element.transmitter_polarization + ']').attr('selected', 'selected');

                    validateVendorId();

                    btsCounter++;
                  }
                });

//              if (data.status == 'success') {
//                coolNotification('<h3>' + (data.status).toUpperCase() + '</h3>' + data.message + '', 'success');
//              } else {
//                coolNotification('<h3 style="color:white">' + (data.status).toUpperCase() + '!</h3> ' + data.message + ' ', 'error', function() {
//                  window.location.href = '<?php echo base_url("site_info/equipment/edit/{$this->uri->segment(4)}"); ?>';
//                });
//              }
              },
              error: function(xhr, status, message) {
                coolNotification('<h3 style="color:white">Error!</h3> Import file failed, please contact your Administrator.', 'error', function() {
                  window.location.href = '<?php echo base_url("site_info/equipment/edit/{$this->uri->segment(4)}"); ?>';
                });
              },
              complete: function() {
                block.close();
                // refresh content tab
//              var tab = $('#equipmentTab').tabs('getSelected');
//              $('#equipmentTab').tabs('update', {
//                tab: tab,
//                options: {
//                  href: document.URL + '?load_content=BTS_INFO&site_id=' + document.getElementsByName('siteId')[0].value
//                }
//              });
              }
            });
          });

        });

        function addBtsDetail(btsId) {
          $.fancybox({
            href: "<?php echo base_url('site_info/equipment/get_bts_detail') ?>/" + btsId,
            type: 'ajax',
            minWidth: 700,
            autoCenter: false,
            scrolling: false,
            autoSize: true,
            fitToView: true,
            closeClick: false,
            openEffect: 'fade',
            closeEffect: 'fade',
            afterShow: function() {
              $('#app_no').focus();
            }
          });
        }

</script>