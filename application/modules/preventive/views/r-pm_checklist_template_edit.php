<!-- <script type="text/javascript" src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.js"></script> -->
<!--<script type="text/javascript" src="<?php echo  base_url() ?>assets/js/notty/noty/jquery.noty.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ?>assets/js/notty/noty/layouts/center.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ?>assets/js/notty/noty/themes/default.js"></script>
<link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css"/>
<link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/theme/scripts/notyfy/themes/default.css"/>-->
<!--commented andedi 20141126-->
<?php
//die(print_r($properties));
?>
<script type="text/javascript">
  $.blockUI({
    message: '<img src="<?php echo base_url('file/images/loading-snake.GIF'); ?>" width="16px"> Processing, please wait...',
    css: {
      border: 'none',
      padding: '15px',
      backgroundColor: 'black',
      color: 'white',
      opacity: .5,
//      '-webkit-border-radius': '10px',
//      '-moz-border-radius': '10px'
    }
  });
</script>

<script src="<?php echo base_url('assets/js/hehehe.js'); ?>"></script>

<style type="text/css">
  .widget-body{
    border-bottom: solid 1px #c1c1c1;
  }
  .widget-body:last-child{
    border-bottom: none;
  }
  .icon-red {
    font-size: 10px;
    color: red;
  }
</style>



<div class="innerLR">
  <div class="row-fluid">
    <div class="span12">
      <div class="widget">
        <div class="widget-head heading-outer">
          <h4 class="heading glyphicons calendar"><i></i> <?php echo $title; ?></h4>
          <div class="btn-heading-group ">
            <a href="<?php echo  base_url('preventive/pm_checklist_template') ?>" class="pull-right btn btn-icon btn-mini btn-primary btn-heading unshare glyphicons"><i></i>Back</a>
          </div>
        </div>

        <div class="widget-body">
          <div class="form-horizontal">

            <?php if ($addEditData == 'add') { ?>
              <div class="control-group" id="form_name">
                <label class="control-label" for="name">Template Option</label>
                <div class="controls">
                  <select class="span12" name="name" id ="option_id">
                    <option value="">- Choose -</option>
                    <?php
                    foreach ($combo_option as $value) {
                      echo'<option value=' . $value['option_id'] . '>' . $value['option_label'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div> 
            <?php }; ?>

            <div class="control-group">
              <label class="control-label" for="name">Template Option</label>
              <div class="controls">
                <input type="text" name="siteName" class="span12" value="<?php echo $option_label; ?>" readonly="readonly" />
              </div>
            </div> 


            <div class="control-group">
              <label class="control-label">
                <img rel="tooltip" data-placement="right" src="<?php echo base_url() . 'assets/images/exclamation.png' ?>" title="At least a checklist item must be selected" style="display:none" id="checklist-item-exclamation">
                Checklist Items
              </label>
              <div class="controls">
                <div class="accordion pm-visit-type-options" id="accordion">

                  <?php

                  function render($properties) {

                    function sub_render($parent, $iterasi) {
                      echo '<div class="accordion-group checklist-group-header">';
                      echo '<div class="accordion-heading">';

                      if ($iterasi) {
                        echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#group_id_' . $parent['group_id'] . '">';
                      } else {
                        echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="" href="#group_id_' . $parent['group_id'] . '">';
                      }

                      echo $parent['group_name'];
                      echo '</a>';
                      echo '</div>';
                      echo '<div id="group_id_' . $parent['group_id'] . '" class="accordion-body in collapse">';

                      if ($iterasi) {
                        echo '<div class="accordion-inner" id="accordion_level_' . $parent['group_id'] . '">';
                      } else {
                        echo '<div class="accordion-inner">';
                      }

                      if (isset($parent['sub_group'])) {
                        $_2nd = 1;
                        foreach ($parent['sub_group'] as $gg) {
                          sub_render($gg, false);
                        }
                      }

                      if (isset($parent['checklists'])) {
                        foreach ($parent['checklists'] as $cc) {
                          item_render($cc);
                        }
                      }

                      echo '</div>';
                      echo '</div>';
                      echo '</div>';
                    }

                    function item_render($checklist) {
//                      echo '<label class="checkbox checklist-items">';
//                      echo '<input type="checkbox" onchange="count_item()" value="'.$checklist['checklist_id'].'" class="pm-checklist-item checkbox checklist_items '.$checklist['class'].'" name="checklist_item">';
//                      echo $checklist["checklist_name"];
//                      if ($checklist["interface"]) 
//                        { echo '<img src="'.base_url().'assets/images/interface-'.$checklist["interface"].'.png" style="margin: -4px 0 0 4px">'; }
//                      echo '</label>';
                      //andedi 20141105
                      echo '
                            <table width="100%">
                            <tr>
                                <td>
                            <label style="display:inline!important" class="checklist-items">
                            ';
                      echo '<input data-photo-def="' . $checklist['has_photo_def'] . '" data-mandatory-def="' . $checklist['mandatory_def'] . '" type="checkbox" onchange="count_item()" value="' . $checklist['checklist_id'] . '" class="pm-checklist-item checklist_items ' . $checklist['class'] . '" name="checklist_item"> &nbsp;';
                      if ($checklist["interface"]) {
                        echo '<img src="' . base_url() . 'assets/images/interface-' . $checklist["interface"] . '.png" style="margin: 0 0 0 4px"></label>';
                      }
                      echo ' <span style="padding-top:4px;">' . $checklist["checklist_name"] . '</span>';
                      echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                            <td width="60px"><input class="mandatory" data-photo="' . $checklist['has_photo'] . '" id="mandatory' . $checklist['checklist_id'] . '" type="checkbox" value="' . $checklist['mandatory'] . '" name="mandatory" title="Mandatory Checklist"><img title="Mandatory Checklist" src="' . base_url() . 'assets/images/icon/star.png" style="margin: 0 0 0 4px; height:16px"></td>
                            <td width="60px"><input class="photo" id="mand_photo' . $checklist['checklist_id'] . '" type="radio" value="1" name="has_photo' . $checklist['checklist_id'] . '" title="Mandatory Photo"><img title="Mandatory Photo" src="' . base_url() . 'assets/images/icon/camera-red.png" style="margin: 0 0 0 4px; height:16px"></td>
                            <td width="60px"><input class="photo" id="not_mand_photo' . $checklist['checklist_id'] . '" type="radio" value="0" name="has_photo' . $checklist['checklist_id'] . '" title="Not Mandatory Photo"><img title="No Mandatory Photo" src="' . base_url() . 'assets/images/icon/camera-black.png" style="margin: 0 0 0 4px; height:16px"></td>
                            <td width="60px"><input class="photo" id="no_photo' . $checklist['checklist_id'] . '" type="radio" value="2" name="has_photo' . $checklist['checklist_id'] . '" title="No Photo"><img title="No Photo" src="' . base_url() . 'assets/images/icon/camera-cross.png" style="margin: 0 0 0 4px; height:16px"></td>
                            </tr>
                            </table>
                            ';
                    }

                    $_1st = 1;
                    foreach ($properties as $gg) {
                      sub_render($gg, $_1st++);
                    }
                  }

                  render($properties);
                  ?>

                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="form-actions">
          <div class="pull-left" id="jumlah-checklist"></div>
          <button type="submit" id="submit-pm-schedule" class="btn btn-icon btn-primary glyphicons circle_ok" onclick="save()"><i></i>Save</button>
          <button type="reset" id="reset-pm-schedule" class="btn btn-icon btn-reset glyphicons repeat" onclick="resetall()"><i></i>Reset</button>
        </div>
      </div> <!-- end of widget -->
    </div>
  </div>
</div>

<script type="text/javascript">

<?php if ($addEditData == 'add') { ?>
    var option_idx = $('#option_id').val();
<?php }; ?>
<?php if ($addEditData == 'edit') { ?>
    var option_idx = <?php echo $option_id; ?>
<?php }; ?>

  $(document).ready(function() {
    // console.log(option_idx);
<?php if ($class != '') { ?>
      var c = '<?php echo $class; ?>';

      $('.pm-visit-type-options :checkbox.' + c).attr('checked', true);
<?php }; ?>

    $.each($('.checklist-group-header'), function() {
      if (!$(this).find('.checklist-items').length) {
        $(this).hide();
      }
    });

    $("#menu_164").parent().addClass('active').find('.collapse').css('height', 'auto');

    $('.pm-checklist-item').each(function(index, element) { //edited andedi 20141106
      //inisialisasi variabel
      var checked = $(this).attr('checked');
      var checklistVal = $(this).val();
      var mandatoryVal = 0;
      var hasPhotoVal = 0;
      var mandatory = $('#mandatory' + checklistVal);
      var mandPhoto = $('#mand_photo' + checklistVal);
      var notMandPhoto = $('#not_mand_photo' + checklistVal);
      var noPhoto = $('#no_photo' + checklistVal);


      if (checked != 'checked') {
        mandatoryVal = $(this).attr('data-mandatory-def');
        hasPhotoVal = $(this).attr('data-photo-def');
        mandatory.attr('disabled', 'disabled');
        mandPhoto.attr('disabled', 'disabled');
        notMandPhoto.attr('disabled', 'disabled');
        noPhoto.attr('disabled', 'disabled');
      } else {
        mandatoryVal = mandatory.val();
        hasPhotoVal = mandatory.attr('data-photo');
      }

      if (mandatoryVal == 1) {
        mandatory.attr('checked', 'checked');
      }

      if (hasPhotoVal == 0) {
        notMandPhoto.attr('checked', 'checked');
        console.log(checklistVal+' : ' + 0);
      } else if (hasPhotoVal == 1) {
        mandPhoto.attr('checked', 'checked');
        console.log(checklistVal+' : ' +1);
      } else if (hasPhotoVal == 2) {
        noPhoto.attr('checked', 'checked');
        console.log(checklistVal+' : ' + 2);
      }

    });

    count_item();
    $.unblockUI();
  });

  function count_item() {
    var item = $('input[name=checklist_item]:checked').length;
    if (item == 0) {
      var msg = 'No checklist item is selected, please pick some';
    }
    else if (item == 1) {
      var msg = 'One checklist item is selected';
    }
    else {
      var msg = item + ' checklist items are selected';
    }
    $('#jumlah-checklist').html(msg);
  }
  // option_id : '<?php //echo $option_id; ?>',

  function save() {
//console.log('save');
    var checkValues = [];

    $('input[name=checklist_item]:checked').each(function() {
      var mandatoryVal = 0;
      var hasPhotoVal = 0;
      var mandatory = $('#mandatory' + $(this).val());
      var mandPhoto = $('#mand_photo' + $(this).val());
      var notMandPhoto = $('#not_mand_photo' + $(this).val());
      var noPhoto = $('#no_photo' + $(this).val());

      if (mandatory.attr('checked') == 'checked') {
        mandatoryVal = 1;
      }

      if (mandPhoto.attr('checked') == 'checked') {
        hasPhotoVal = 1;
      } else if (notMandPhoto.attr('checked') == 'checked') {
        hasPhotoVal = 0;
      } else if (noPhoto.attr('checked') == 'checked') {
        hasPhotoVal = 2;
      }

      checkValues.push($(this).val() + '|' + mandatoryVal + '|' + hasPhotoVal);
    });

//  $('input[name=mandatory]:checked').each(function() {
//    mandatoryValues.push($(this).val());
//  });
//  
//  $('input[name=has_photo]:checked').each(function() {
//    hasPhotoValues.push($(this).val());
//  });

    $.ajax({//andedi 20141029
      url: '<?php echo base_url('preventive/pm_checklist_template/saveData'); ?>',
      type: 'post',
      data: {option_id: option_idx, checklist_item: checkValues, },
      beforeSend: function() {
        $.blockUI({
          message: '<img src="<?php echo base_url('file/images/loading-snake.GIF'); ?>" width="16px"> Saving data, please wait...',
          css: {
            border: 'none',
            padding: '15px',
            backgroundColor: 'black',
            color: 'white',
            opacity: .5,
//            '-webkit-border-radius': '10px',
//            '-moz-border-radius': '10px'
          }
        });
      },
      success: function(res) {
        $.unblockUI();
        if (res === 'ok') {
          coolNotification('Data saved.', 'success', function() {
            window.location.href = '<?php echo base_url('preventive/pm_checklist_template'); ?>';
          });
        } else {
          coolNotification('Deleting data error.', 'error', function() {
          });
        }
      },
      error: function(xhr, status, msg) {
        $.unblockUI();
        coolNotification('Saving data error.<br>' + msg, 'error', function() {
        });
      }
    });
//  $.post( //saya ganti pake ajax ya ^_^
//    "<?php echo base_url('preventive/pm_checklist_template/saveData'); ?>",
//    {
//    option_id : option_idx,
//    checklist_item : checkValues
//    },
//    function(data) {
//        notyfy({layout: 'center',text: 'Data Saved', type: 'success'});
//        window.location.href = "<?php echo base_url(); ?>preventive/pm_checklist_template";
//    }
//  ); 
  }

  function reload_page() {
    location.reload();
  }

  function resetall() {
    $('#submit-pm-schedule').addClass('disabled');
    $('.photo').attr('disabled', 'disabled');
    $('.photo').removeAttr('checked');
    $('.mandatory').attr('disabled', 'disabled');
    $('.mandatory').removeAttr('checked');
    $('input.pm-checklist-item').removeAttr('checked');
    $('#pm-priority button').removeClass('active');
    $('textarea#remark').val('');
    $('textarea#pic_non_default_remark ').val('');
    $('select#pm-visit-type').val('');
    $('#calendar td > div').removeClass('selected');
    window.date = false;
    count_item();
  }

  $('.pm-checklist-item').click(function() { //andedi 20141105
    var checked = $(this).attr('checked');
    var checklistVal = $(this).val();
    if (checked != 'checked') {
      $('#mandatory' + checklistVal).attr('disabled', 'disabled');
      $('#mand_photo' + checklistVal).attr('disabled', 'disabled');
      $('#not_mand_photo' + checklistVal).attr('disabled', 'disabled');
      $('#no_photo' + checklistVal).attr('disabled', 'disabled');
    } else {
      $('#mandatory' + checklistVal).removeAttr('disabled');
      $('#mand_photo' + checklistVal).removeAttr('disabled');
      $('#not_mand_photo' + checklistVal).removeAttr('disabled');
      $('#no_photo' + checklistVal).removeAttr('disabled');
    }
  });

</script>