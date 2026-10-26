<?php //error_reporting(0);      ?>
<style type="text/css">
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
  td.remark{
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 113px;		
  }	
  .controls .lbl{font-weight: bold;}
  p.info{
    font-size: 11px;
    margin-left: 18px;
    color: #808080;		
  }
</style>
<script language="javascript" src="<?php echo base_url(); ?>assets/js/jquery.form.js"></script>

<form action="<?php echo base_url('isr_bts/validation_ireg/add_validation_ireg') ?>" class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form-app" enctype="multipart/form-data">
  <input type="hidden" name="app_id" value="<?php echo $header->app_id; ?>"/>
  <input type="hidden" name="app_no" value="<?php echo $header->app_no; ?>"/>
<!--  <input type="hidden" name="issued_date" value="<?php echo $header->app_id; ?>"/>
 <input type="hidden" name="expired_date" value="<?php echo $header->app_id; ?>"/>-->
  <div class="widget">
    <div class="widget-head"><h4 class="heading">Validation Application Link</h4></div>
    <div class="widget-body">
      <div class="row-fluid">
        <div>
          <div class="control-group column-left">
            <label class="control-label" for="app_no">Application Number</label>
            <div class="controls" style="padding-top: 5px;width:170px">
              <span class="lbl"><?php echo $header->app_no ?></span>
            </div>
          </div>					
          <div class="control-group column-right">
            <label class="control-label" for="doc">Doc IOM </label>
            <div class="controls">
              <div class="input-control">
                <input id="doc" name="doc" type="file" />
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="control-group column-left" id="form_app_issued">
            <label class="control-label" for="app_issued">Issued Date</label>
            <div class="controls" style="padding-top: 5px;width:170px">
              <span class="lbl"><?php echo $header->app_issued ?></span>
            </div>
          </div>
          <div class="control-group column-right">
            <label class="control-label" for="description">Description<span class="text-error">*</span></label>
            <div class="controls">
              <textarea name="description" id="description" style="position:absolute;height:55px;width:350px"></textarea>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="control-group column-left" id="form_app_expired">
            <label class="control-label" for="app_expired">Expired Date</label>
            <div class="controls" style="padding-top: 5px;">
              <span class="lbl"><?php echo $header->app_expired ?></span>
            </div>
          </div>
          <div class="clearfix"></div>

          <div style="max-height: 350px;width:100%;margin-top:20px;overflow: auto">
            <table class="dynamicTable table table-striped table-bordered" style="width: 1200px;">
              <thead>
                <tr>
                  <th><input type='checkbox' id='chkall'/><span>Station ID</span></th>
                  <th>Region</th>
                  <th>Station Name</th>
                  <th>Equipment</th>
                  <th>Tx Freq</th>
                  <th>Rx Freq</th>
                  <th>Validate</th>
<!--                  <th>Remark</th>
                  <th>Doc IOM</th>-->
                </tr>
              </thead>
              <tbody>

                <?php
                foreach ($details as $key => $row) {

                  if ($key == 0) {
                    $strstatus = '<select style="width:200px" class="cmbStatus" id="myid" name="milestone_status[]">';
                    $arrReason = array('Discontinued (Dismantle)' => 1361, 'Discontinued (ISR Changed)' => 1661);
                    foreach ($arrReason as $key => $value) {
                      $strstatus.= "<option value='$value'>$key</option>";
                    }
                    $strstatus.='</select>';

                    $remark = "<textarea style='height:30px;z-index:9999' id='remark' name='reason' class='iremark span12'></textarea>";
                    $file = "<input type='file' id='doc' name='doc' class='iDoc span12'/>";
                  } else {
                    $remark = '';
                    $file = '';
                    $strstatus = "<span class='remark'>Discontinued (Dismantle)</span>";
                  }

                  echo "
                              <tr>
                                <td><input type='checkbox' class='link' name='link[]' value='{$row->app_link_id}'/>{$row->station_identifier}</td>
                                <td>{$row->region}</td>
                                <td>{$row->station_name}</td>
                                <td>{$row->equipment_type}</td>
                                <td>{$row->transmitter_freq}</td>
                                <td>{$row->receiver_freq}</td>
                                <td>{$strstatus}</td>
                              </tr>
                              ";
                }

//                $i = 0;
//                $pair_id = 0;
//                foreach ($detail as $row) {
//                  $i++;
//
//                  $datetime1 = new DateTime(date('Y-m-d'));
//                  $datetime2 = new DateTime(date('Y-m-d', strtotime($row->isr_expired)))
//                  $interval = $datetime1->diff($datetime2);
//                  $diff = $interval->format('%R%a');
//
//                  echo '<tr>';
//                  foreach ($arr as $key => $val) {
//                    //if ($key=='link_id_code'){
//                    if ($key == 'station_no') {
//                      $strval = "<input type='checkbox' pair='" . $row['pair_id'] . "' class='link' name='link[$i]' value='" . $row['link_id'] . "'/><span>$row[$key]</span>";
//                      if (!is_null($row->isr_expired))
//                        $strval.="<p class='info'>Expired in $diff days</p>";
//                    }elseif ($key == 'status') {
//                      if ($pair_id != $row['pair_id']) {
//                        $tmpstatus = $strstatus;
//                        $strval = str_replace('milestone_status[]', "milestone_status[$i]", $tmpstatus);
//                        $strval = str_replace('myid', $row['pair_id'], $strval);
//                      } else {
//                        if ($data['param'] == 'Validation Iriregular') {
//                          $strval = '<input type="hidden" id="pair_' . $row['pair_id'] . '" name="milestone_status[' . $i . ']" value="' . $arrReason['Discontinued (ISR Changed)'] . '"/><span>Discontinued (ISR Changed)</span>';
//                        } else {
//                          $strval = '<input type="hidden" id="pair_' . $row['pair_id'] . '" name="milestone_status[' . $i . ']" value="' . $arrReason['Renewal'] . '"/><span>Renewal</span>';
//                        }
//                      }
//                    } elseif ($key == 'remark') {
//                      if ($pair_id != $row['pair_id']) {
//                        //	$strval="<input type='text' id='remark".$row['pair_id']."' name='reason[$i]' class='iremark span12'/>";
//                        $strval = "<textarea style='height:30px;z-index:9999' id='remark" . $row['pair_id'] . "' name='reason[$i]' class='iremark span12'></textarea>";
//                      }
//                      else
//                        $strval = "<input id='tremark" . $row['pair_id'] . "' type='hidden' name='reason[$i]'><span id='sremark" . $row['pair_id'] . "'></span>";
//                    }elseif ($key == 'document') {
//                      if ($pair_id != $row['pair_id']) {
//                        $strval = "<input type='file' id='doc$i' name='doc[$i]' class='iDoc span12'/>";
//                        $pair_id = $row['pair_id'];
//                      }
//                      else
//                        $strval = '';
//                      // }elseif($key=='freq_tx'){
//                      // 	$strval="$row[$key] (".$row["freq_tx_polar"].")";
//                      // }elseif($key=='freq_rx'){
//                      // 	$strval="$row[$key] (".$row["freq_rx_polar"].")";
//                    }
//                    else
//                      $strval = $row[$key];
//
//                    echo "<td class='$key'>$strval</td>";
//                  }
//
//                  echo '</tr>';
//                }
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

    </div>

  </div>

</form>

<script language="javascript">


  $('#chkall').click(function() {
    $('.dynamicTable').find('input[type="checkbox"]').attr('checked', $(this).is(':checked'));
  });

  $(document).ready(function() {
    //Initiate check all on load
    $('.dynamicTable').find('input[type="checkbox"]').attr('checked', 'checked');

    $('.cmbStatus').change(function() {
      var text = $("option:selected", this).text();
      $('.remark').html(text);
    });
    $('.iremark').keyup(function() {
      id = $(this).attr('id');

      $('#s' + id).html($(this).val());
      $('#t' + id).val($(this).val());
    });
    $('.fancybox-wrap').draggable({handle: ".widget-head"});

    $('.link').change(function() {
      var thislink = $(this);
      var checked = $(this).is(':checked');
      var pair_id = thislink.attr('pair');
      var link_code_id = thislink.parent().find('span').html();

      $('.dynamicTable').find('.link').each(function() {
        var link = $(this);
        var pair_id2 = link.attr('pair');
        var parent = link.parent().parent();
        var link_code_id2 = link.parent().find('span').html();

        if (link_code_id != link_code_id2 && pair_id == pair_id2) {
          link.attr('checked', checked);
          if (checked == true) {
            parent.css('color', 'gray');
            //	link.removeAttr('name');						
            //	link.attr('disabled','disabled');
          } else {
            parent.css('color', 'black');
            //	link.attr('name','link[]');
            //	link.removeAttr('disabled');						
          }
        }
      });
    });

    $('.iremark').focus(function() {
      pos = $(this).position();
      $(this).css('position', 'fixed')
              .css('height', '130px')
              .css('width', '350px')
              .css('left', pos.left - 50);
    });
    $('.iremark').blur(function() {
      pos = $(this).position();
      $(this).css('position', 'static')
              .css('height', '30px')
              .css('width', '116')
              .css('left', pos.left + 50);
    });


    $("#add-form-app").ajaxForm({dataType: 'json', success: processJson, error: errorMessage, beforeSubmit: validate});
    $("#add-form-app").submit(function(event) {
    });

    function validate() {
      $('.submit').attr('disabled', true);
      $('#dState').html('<span class="popnotif loading">Loading, saving data..</span>').parent().slideDown();
      if ($("#add-form-app").valid() == false) {
        $('.submit').removeAttr('disabled');
        $('#dState').html('<span class="popnotif warning">Please complete document form data</span>');
        return false;
      }
    }
    function errorMessage(data) {
      var msg = 'Document failed to save, please try again.';
      $('.submit').removeAttr('disabled');
      $('#dState').html('<span class="popnotif warning">' + msg + '</span>');
      alert(msg);
    }
    function processJson(data) {
      if (data.status == 'OK') {

        $('#dg').datagrid('load');
        $.fancybox.close();

        $.gritter.add({
          image: '<?php echo base_url(); ?>file/images/logosmartfren.png',
          title: 'Save Success',
          text: data.hasil
        });

      } else {
        $('.submit').removeAttr('disabled');
        $('#dState').html('<span class="popnotif warning">' + data.hasil + '</span>');
      }
    }


    $("#add-form-app").validate({
      rules: {
//                userfile: "required",
        description: "required"
      },
      messages: {
//                userfile: "Please input iOM document",
        description: "Please enter description.",
      }
    });

  });


</script>

