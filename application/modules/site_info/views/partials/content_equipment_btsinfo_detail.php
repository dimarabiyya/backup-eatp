<?php
$sub_sub_table_counter = 1;
$sub_sub_row_counter = 1;
$sub_row_counter = 1;
?>

<div class="widget">
  <div class="widget-head heading-outer">
    <h4 class="heading"><i></i> BTS Detail # <?php echo $bts_info->vendor_id; ?></h4>
  </div>
  <div class="widget-body">
    <form id="ff" class="form-horizontal">
      <input type="hidden" name="bts_id" value="<?php echo $bts_info->bts_id; ?>">
      <table class="table table-bordered table-condensed">
        <tbody id="sub_table">
          <?php
          if (count($bts_tdd) == 0) {
            ?>
            <tr id="sub_row_<?php echo $sub_row_counter; ?>">
              <td rowspan="2" style="max-width: 200px"><button type="button" class="btn btn-danger btn-small" onclick="BTS_DETAIL.removeSubRow(<?php echo $sub_row_counter; ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>
              <td style="max-width: 200px"><input type="text" name="tdd_no[<?php echo $sub_row_counter?>]" placeholder="#" class=" input-small" value=""></td>
              <td style="max-width: 200px"><input type="text" name="freq[<?php echo $sub_row_counter?>]" placeholder="TX Freq" class="input-small" value=""></td>
              <td style="max-width: 200px">
                <?php
                $lookup = $this->core->get_lookup('bts_dd');
                $options = array(0 => '- Choose one -');
                foreach ($lookup as $lo) {
                  $options[$lo['id']] = $lo['desc'];
                }
                echo @form_dropdown("type[{$sub_row_counter}]", $options, '', 'id="type" class="input-medium"');
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="3">
                <table class="table table-bordered table-condensed">
                  <tbody id="sub_sub_table_<?php echo $sub_sub_table_counter; ?>">
                    <tr id="sub_sub_row_<?php echo $sub_sub_row_counter; ?>">
                      <td><button type="button" class="btn btn-danger btn-small" onclick="BTS_DETAIL.removeSubSubRow(<?php echo $sub_sub_row_counter; ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>
                      <td style="max-width: 200px"><input type="text" name="sector_type[<?php echo $sub_row_counter?>][]" placeholder="Sector" class=" input-small" value=""></td>
                      <td style="max-width: 200px"><input type="text" name="tilt[<?php echo $sub_row_counter?>][]" placeholder="Tilt" class=" input-small" value=""></td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr><td><button type="button" class="btn btn-success btn-small" onclick="BTS_DETAIL.addSubSubRow(<?php echo $sub_sub_table_counter; ?>)" title="Add row"><i class="icon-plus-sign icon-white"></i></button></td></tr>
                  </tfoot>
                </table>
              </td>
            </tr>
            <?php
          } else {
            $lookup = $this->core->get_lookup('bts_dd');
            $options = array(0 => '- Choose one -');
            foreach ($lookup as $lo) {
              $options[$lo['id']] = $lo['desc'];
            }

            foreach ($bts_tdd as $value) {
              $type = @form_dropdown("type[{$sub_sub_table_counter}]", $options, $value->type, 'id="type" class="input-medium"');
              echo "
                <tr id='sub_row_{$sub_row_counter}'>
              <td rowspan='2' style='max-width: 200px'>
              <button type='button' class='btn btn-danger btn-small' onclick='BTS_DETAIL.removeSubRow({$sub_row_counter})' title='Remove row'><i class='icon-minus-sign icon-white'></i>
              </button>
              <input type='hidden' name='tdd_id[{$sub_sub_table_counter}]' value='{$value->tdd_id}'>
              </td>
              <td style='max-width: 200px'><input type='text' name='tdd_no[{$sub_sub_table_counter}]' placeholder='#' class='input-small' value='{$value->tdd_no}'></td>
              <td style='max-width: 200px'><input type='text' name='freq[{$sub_sub_table_counter}]' placeholder='TX Freq' class='input-small' value='{$value->freq}'></td>
              <td style='max-width: 200px'>{$type}</td>
            </tr>
                ";

              echo "<tr>
              <td colspan='3'>
                <table class='table table-bordered table-condensed'>
                  <tbody id='sub_sub_table_{$sub_sub_table_counter}'>";

              $bts_sector = $this->equipment_model->get_bts_sector(array('tdd_id' => $value->tdd_id));
              foreach ($bts_sector as $value) {
                echo "
                    <tr id='sub_sub_row_{$sub_sub_row_counter}'>
                      <td>
                      <button type='button' class='btn btn-danger btn-small' onclick='BTS_DETAIL.removeSubSubRow($sub_sub_row_counter)' title='Remove row'><i class='icon-minus-sign icon-white'></i>
                        </button>
                        <input type='hidden' name='sector_id[{$sub_sub_table_counter}][]' value='{$value->sector_id}'>
                        </td>
                      <td style='max-width: 200px'><input type='text' name='sector_type[{$sub_sub_table_counter}][]' placeholder='Sector' class=' input-small' value='{$value->sector_type}'></td>
                      <td style='max-width: 200px'><input type='text' name='tilt[{$sub_sub_table_counter}][]' placeholder='Tilt' class=' input-small' value='{$value->tilt}'></td>
                    </tr>
                  ";
                $sub_sub_row_counter++;
              }
              echo "</tbody>
                  <tfoot>
                    <tr><td><button type='button' class='btn btn-success btn-small' onclick='BTS_DETAIL.addSubSubRow({$sub_sub_table_counter})' title='Add row'><i class='icon-plus-sign icon-white'></i></button></td></tr>
                  </tfoot>
                </table>
              </td>
            </tr>";
              $sub_row_counter++;
              $sub_sub_table_counter++;
            }
          }
          ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4">
              <button type="button" class="btn btn-success btn-small" onclick="BTS_DETAIL.addSubRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></button>
            </td>
          </tr>
        </tfoot>
      </table>
      <div class="form-actions">
        <button class="btn btn-primary btn-icon glyphicons circle_ok" type="submit"><i></i> Save</button>
        <button class="btn btn-default btn-icon glyphicons circle_remove" type="button" onclick="$.fancybox.close()"><i></i> Close</button>
      </div>
    </form>
  </div>
</div>

<script>
                $(document).ready(function() {
                  $('#ff').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                      url: '<?php echo base_url('site_info/equipment/save_bts_detail'); ?>',
                      data: $(this).serialize(),
                      type: 'post',
                      dataType: 'json',
                      beforeSend: function() {
                        siteOverlay.top = '200px';
                        siteOverlay.create();
                      },
                      success: function(data) {
                        coolNotification('<h3>'+data.status.toUpperCase()+'</h3>'+data.message, data.status);
                      },
                      error: function() {
                        coolNotification('<h3>Error!</h3> An error occured, please try again or contact the administrator.', 'error');
                      },
                      complete: function() {
                        siteOverlay.remove();
                      },
                    });
                  });
                });
                var subSubTableCounter = <?php echo (count($bts_tdd) == 0) ? 2 : $sub_sub_table_counter; ?>;
                var subSubRow = <?php echo (count($bts_tdd) == 0) ? 2 : $sub_sub_row_counter; ?>;
                var subRowCounter = <?php echo (count($bts_tdd) == 0) ? 2 : $sub_row_counter; ?>;
                var BTS_DETAIL = {
                  addSubRow: function() {
                    var html = '<tr id="sub_row_' + subRowCounter + '">';
                    html += '<td rowspan="2" style="max-width: 200px"><button type="button" class="btn btn-danger btn-small" onclick="BTS_DETAIL.removeSubRow(' + subRowCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>';
                    html += '<td style="max-width: 200px"><input type="text" name="tdd_no[' + subSubTableCounter + ']" placeholder="#" class=" input-small" value="<?php echo $bts['#']; ?>"></td>';
                    html += '<td style="max-width: 200px"><input type="text" name="freq[' + subSubTableCounter + ']" placeholder="TX Freq" class="input-small" value="<?php echo $bts['tx_freq']; ?>"></td>';
                    html += '<td><select name="type[' + subSubTableCounter + ']" class="input-medium"><option value="0">- Choose one -</option><?php $lookup = $this->core->get_lookup('bts_dd');
          foreach ($lookup as $lo) {
            ?><option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\<?php } ?></select></td>';
                    html += '</tr>';
                    html += '<tr>';
                    html += '<td colspan="3">';
                    html += '<table class="table table-bordered table-condensed">';
                    html += '<tbody id="sub_sub_table_' + subSubTableCounter + '">';
                    html += '<tr>';
                    html += '<td><button type="button" class="btn btn-danger btn-small" onclick="BTS_DETAIL.removeSubSubRow()" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>';
                    html += '<td style="max-width: 200px"><input type="text" name="sector_type[' + subSubTableCounter + '][]" placeholder="Sector" class=" input-small" value="<?php echo $bts['sector']; ?>"></td>';
                    html += '<td style="max-width: 200px"><input type="text" name="tilt[' + subSubTableCounter + '][]" placeholder="Tilt" class=" input-small" value="<?php echo $bts['tilt']; ?>"></td>';
                    html += '</tr>';
                    html += '</tbody>';
                    html += '<tfoot>';
                    html += '<tr><td><button type="button" class="btn btn-success btn-small" onclick="BTS_DETAIL.addSubSubRow(' + subSubTableCounter + ')" title="Add row"><i class="icon-plus-sign icon-white"></i></button></td></tr>';
                    html += '</tfoot>';
                    html += '</table>';
                    html += '</td>';
                    html += '</tr>';
                    $('#sub_table').append(html);
                    subSubTableCounter++;
                    subRowCounter++;
                    console.log('addSubRow');
                  },
                  removeSubRow: function(row) {
                    coolConfirmation('<h3>Confirmation</h3>Are you sure delete this row?', function() {
                      console.log(row);
//                      $(this).parent().parent().remove();
                      $('#sub_row_' + row).next().remove();
                      $('#sub_row_' + row).remove();
                    });
                  },
                  addSubSubRow: function(subSubtable) {
                    console.log(':-D');
                    var html = '<tr id="sub_sub_row_' + subSubRow + '">';
                    html += '<td><button type="button" class="btn btn-danger btn-small" onclick="BTS_DETAIL.removeSubSubRow(' + subSubRow + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></button></td>';
                    html += '<td style="max-width: 200px"><input type="text" name="sector_type[' + subSubtable + '][]" placeholder="Sector" class=" input-small" value="<?php echo $bts['sector']; ?>"></td>';
                    html += '<td style="max-width: 200px"><input type="text" name="tilt[' + subSubtable + '][]" placeholder="Tilt" class=" input-small" value="<?php echo $bts['tilt']; ?>"></td>';
                    html += '</tr>';

                    $('#sub_sub_table_' + subSubtable).append(html);
                    subSubRow++;
                  },
                  removeSubSubRow: function(row) {
                    coolConfirmation('<h3>Confirmation</h3>Are you sure delete this row?', function() {
                      $('#sub_sub_row_' + row).remove();
                    });
                  },
                };
</script>