<script src="<?php echo base_url('assets/js/hehehe.js'); ?>"></script>
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
  .equipment{display: none;}
  .sinfo{font-size:10px;color:gray;}
</style>
<div class="widget">
  <div class="widget-head"><h4 class="heading"><?php echo $judul ?></h4></div>
  <div class="widget-body">
    <div class="row-fluid">
      <div>

        <div class="control-group" id="form_order">
          <div class="controls">
            <b>Type &nbsp; : &nbsp; </b><input type="radio" name="tree_type" onClick="hideShow('grouping')"/>&nbsp;Grouping 
            &nbsp;&nbsp;
            <input type="radio" name="tree_type" onClick="hideShow('checklist')"/>&nbsp;Checklist
          </div>
          <div class="form-actions" id="close_button">
            <button type="button" class="btn btn-icon btn-primary glyphicons circle_remove" onclick="closeFancybox()"><i></i>Close</button>
          </div> 
        </div>


        <div class="cls-group">	
          <form class="form-horizontal" method="POST" id="add-form" >
            <div class="widget">
              <div class="widget-body">
                <div class="row-fluid">
                  <input type='hidden' name='group_key' value='prev.maintenance' >

                  <div class="control-group" id="form_group_name_concat">
                    <div class="controls">
                      <b id="group_name_concat"></b>
                    </div>
                  </div>

                  <div class="control-group" id="form_site_id">
                    <label class="control-label" for="site_id">Checklist Group</label>
                    <div class="controls">

                      <select class="span12" name="parent" id="parent">
                        <option level="1" value='<?php echo $parent_top; ?>'>- Checklist Group -</option>
                        <?php
                        if (count($parentx) > 0) {
                          foreach ($parentx as $parent)
// echo '<option value="'.$parent['group_id'].'"'.($parent['group_id'] == $parent_id ? ' selected' : '').'>'.str_repeat('&nbsp;',$parent['level']*3).$parent['group_name'].'</option>';
// echo '<option level="'.$parent['level'].'" value="'.$parent['group_id'].'"'.($parent['group_id'] == $parent_id ? ' selected' : '').'>'.str_repeat('&nbsp;',$parent['level']*3).$parent['group_name'].'</option>';
                            echo '<option data-root-id="' . $parent['root_id'] . '" level="' . $parent['level'] . '" group_name_concat="' . $parent['group_name_concat'] . '" value="' . $parent['group_id'] . '"' . ($parent['group_id'] == $parent_id ? ' selected' : '') . '>' . str_repeat('&nbsp;', $parent['level'] * 3) . $parent['group_name'] . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                  </div> 

                  <div class="control-group" id="form_group">
                    <label class="control-label" for="group">Group Name</label>
                    <div class="controls"><input class="span12" id="group" name="group" type="text" value="" /></div>
                  </div>


                  <div class="control-group" id="form_order">
                    <label class="control-label" for="order">Order</label>
                    <div class="controls"><input class="span12" onKeyUp="this.value = this.value.replace(/\D/, '')" id="order" name="order" type="text" value="" /></div>
                  </div>



                  <div class="form-actions">
                    <button type="button" class="btn btn-icon btn-primary glyphicons circle_ok" onclick="saveFormGroup()"><i></i>Save</button>
                    <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" onclick="closeFancybox()"><i></i>Close</button>
                  </div>
                </div>


              </div>	
            </div>
          </form>					
        </div>					

        <div class="cls-checklist">
          <form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form2" >

            <div class="widget">
              <div class="widget-body">
                <div class="row-fluid">
                  <div>

                    <div class="control-group" id="form_group_name_concat">
                      <div class="controls">
                        <b id="group_name_concat2"></b>
                      </div>
                    </div>

                    <div class="control-group" id="form_name">
                      <label class="control-label" for="checklist_group_id">Parent</label>
                      <div class="controls">

                        <select class="span12" name="name" id="name">
                          <option level="1" value='<?php echo $parent_top; ?>'>- Checklist Group -</option>
                          <?php
                          if (count($parentx) > 0) {
                            foreach ($parentx as $parent)
//echo '<option value="'.$parent['group_id'].'"'.($parent['group_id'] == $parent_id ? ' selected' : '').'>'.str_repeat('&nbsp;',$parent['level']*3).$parent['group_name'].'</option>';
                              echo '<option level="' . $parent['level'] . '" group_name_concat="' . $parent['group_name_concat'] . '" value="' . $parent['group_id'] . '"' . ($parent['group_id'] == $parent_id ? ' selected' : '') . '>' . str_repeat('&nbsp;', $parent['level'] * 3) . $parent['group_name'] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                    </div> 

                    <div class="control-group" id="form_descr">
                      <label class="control-label" for="descr">Checklist Name</label>
                      <div class="controls"><input placeholder="Description" class="span12" id="descr" name="descr" type="text" value="" /></div>
                    </div>

                    <div class="control-group" id="form_urutan">
                      <label class="control-label" for="urutan">Order</label>
                      <div class="controls"><input class="input-small number" placeholder="" maxlength="5" id="urutan" name="urutan" type="text" value="" /></div>
                    </div>

                    <div class="control-group" id="form_interface">
                      <label class="control-label" for="interface">Interface</label>
                      <div class="controls">
                        <input type="radio" class="radio" name="interface" value="android" checked />
                        Android&nbsp;&nbsp;
                        <input type="radio" class="radio" name="interface" value="desktop"/>
                        Desktop&nbsp;&nbsp;
                      </div>
                    </div>

                    <div class="control-group" id="form_descr">					
                      <label class="control-label" for="descr">Photo</label>
                      <div class="controls">
                        <input onclick="showTotalPhoto(true)" type="radio" class="checkbox" name="has_photo" value="1" /> Mandatory &nbsp;&nbsp;&nbsp;
                        <input onclick="showTotalPhoto(true)" type="radio" class="checkbox" name="has_photo" value="0" /> Not Mandatory &nbsp;&nbsp;&nbsp;
                        <input onclick="showTotalPhoto(false)" type="radio" class="checkbox" name="has_photo" value="2" /> No Photo &nbsp;&nbsp;&nbsp;
                      </div>
                    </div>

                    <div class="control-group" id="form_total_photo">
                      <label class="control-label">Total Photo</label>
                      <div class="controls">
                        <input class="input-mini just-number" onKeyUp="this.value = this.value.replace(/\D/, '')" id="totphoto" placeholder="123" name="totphoto" type="text" value="" />
                      </div>
                    </div>

                    <div class="control-group" id="form_mandatory">					
                      <label class="control-label" for="mandatory">Mandatory Answer</label>
                      <div class="controls">
                        <input type="checkbox" class="checkbox" name="mandatory" value="1"/>
                      </div>
                    </div>


                    <div class="control-group" id="form_property_id">
                      <label class="control-label" for="property_id">Answer Type</label>
                      <div class="controls">
                        <select style="text-transform:capitalize;" class="span4" name="property_id" id="property_id">
                          <option value="">- Choose -</option>
                          <?php
                          $getProperty = $this->mpm_checklist->getProperty();
                          foreach ($getProperty as $r) {
                            echo"<option value='$r->checklist_type_id'>$r->tnama</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="control-group" id="form_option">
                      <label class="control-label" for="option">Option</label>
                      <div class="controls">
                        <table class="table table-bordered table-striped">
                          <thead class="text-center"><th>Option</th><th>Follow Up</th><th>Action</th></thead>
                          <tbody id="tambah">
                            <tr id="control1" class="controls ctl-option">
                              <td><input class="span12" id="option1" name="option[]" type="text" /></td>
                              <td>
                                <?php
                                if (empty($rfCounter)) {
                                  $rfCounter = 1;
                                };
                                ?>
                                <input id="is_critical2_<?php echo $rfCounter; ?>" onclick="setIsCritical(<?php echo $rfCounter; ?>)" class="checkbox" type="checkbox" name="is_critical2[]" value="1"/>
                                <input id="is_critical_<?php echo $rfCounter; ?>" type='hidden' name='is_critical[]' value='<?php echo $rf['is_critical'] ?>' >	
                              </td> 
                              <td><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="removeBTSRow(1)"><i></i> Remove</a></td>


                          </tbody>
                          <table>
                            <div  style="padding-top : 5px">
                              <a class="btn btn-icon glyphicons circle_plus" href="javascript:void(0)" onclick="addBTSRow()"><i></i> Add Option</a>
                            </div>
                            </div>	
                            </div>

                            <div class="form-actions">
                              <button type="button" class="btn btn-icon btn-primary glyphicons circle_ok" onclick="saveForm()"><i></i>Save</button>
                              <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" onclick="closeFancybox()"><i></i>Close</button>
                            </div>                                        
                            </div>
                            </div>

                            </div>

                            </div>

                            </form>	
                            </div>					

                            </div></div></div></div></div>	
                            <!-- </form> -->
                            <script>

              $("#parent").mousemove(function() {
                var group_name_concat = $('#parent option:selected').attr('group_name_concat');
                $("#group_name_concat").html(group_name_concat);
              });

              $("#name").mousemove(function() {
                var group_name_concat2 = $('#name option:selected').attr('group_name_concat');
                $("#group_name_concat2").html(group_name_concat2);
              });

              $(document).ready(function() {
                $('.cls-checklist').hide();
                $('.cls-group').hide();
                $('#form_total_photo').hide();
              });

              function showTotalPhoto(param) {
                if (param == true) {
                  $('#form_total_photo').slideDown('slow');
                } else {
                  $('#form_total_photo').slideUp('slow');
                  $('#totphoto').val(0);
                }
              }
              function hideShow(param) {
                $('#close_button').hide();
                if (param == 'grouping') {
                  $('.cls-checklist').hide();
                  $('.cls-group').show();
                  var group_name_concat = $('#parent option:selected').attr('group_name_concat');
                  $("#group_name_concat").html(group_name_concat);
                } else {
                  $('.cls-group').hide();
                  $('.cls-checklist').show();
                  var group_name_concat2 = $('#name option:selected').attr('group_name_concat');
                  $("#group_name_concat2").html(group_name_concat2);
                }
              }
              ;
                            </script>
                            <script>
                              function saveFormGroup() {
                                var level = $('#parent option:selected').attr('level');
                                var root_id = $('#parent option:selected').attr('data-root-id');
                                var level_child = parseInt(level) + 2; //edited by andedi 20141111

                                if ($('#order').val() == '0') {
                                  notyfy({layout: 'center', text: 'Order cannot filled zero', type: 'error'});
                                } else {
                                  $.ajax({
                                    type: "POST",
                                    url: "<?php echo base_url('referensi/pm_checklist/saveData') ?>",
                                    data: $('#add-form').serialize() + "&level=" + level_child + "&root_id=" + root_id,
                                    dataType: "json",
                                    beforeSend: function() {
                                      //$('.alert-error,.alert-success').hide();
                                      $.blockUI({message: '<img width="16px" src="<?php echo base_url('file/images/loading-snake.GIF'); ?>"> Saving data, please wait...', css: {
                                          border: 'none',
                                          padding: '15px',
                                          backgroundColor: '#000',
                                          '-webkit-border-radius': '10px',
                                          '-moz-border-radius': '10px',
                                          opacity: .5,
                                          color: '#fff',
                                          'z-index': 99999
                                        }});
                                    },
                                    success: function(data) {
                                      if (data.status == "OK") {
                                        coolNotification('Data has been added successfully.', 'success', function() {
                                          window.location.href = "<?php echo base_url('referensi/pm_checklist'); ?>";
                                        });
                                      } else if (data.status == "ERROR") {
                                      	 coolNotification(data.hasil, 'error');
                                      } else {
                                        coolNotification('An error occured, please call the Administrator.', 'error');
                                      }
                                    },
                                    error: function(xhr, status, message) {
                                      coolNotification('An error occured, please call the Administrator.', 'error');
                                    },
                                    complete: function() {
                                      $.unblockUI();
                                    }
                                  });
                                }

                                return false;
                              }
                              ;
                            </script>

                            <script>
<?php if (!empty($id)) { ?>
                                var btsCounter = <?php echo $rfCounter ?>;
<?php } else { ?>
                                var btsCounter = 2;
<?php } ?>

                              var addBTSRow = function() {
                                var html = '<tr id="control' + btsCounter + '" class="controls ctl-option"><td><input placeholder="option' + btsCounter + '" class="span12" id="option' + btsCounter + '" name="option[]" type="text"  />&#32</td><td><input id=is_critical2_' + btsCounter + ' onclick="setIsCritical(' + btsCounter + ')" type="checkbox" class="checkbox" name="is_critical2[]" value="1"/><input id="is_critical_' + btsCounter + '" type="hidden" name="is_critical[]" value="" ></td><td><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="removeBTSRow(' + btsCounter + ')"><i></i> Remove</a></td>';

                                if (checkOptions()) {
                                  $('#tambah').append(html);
                                }

                                btsCounter++;
                              };

                              var removeBTSRow = function(counter) {
                                var btsRowElem = document.getElementById('control' + counter);
                                btsRowElem.parentNode.removeChild(btsRowElem);
                              };

                              var checkOptions = function() {
                                var opt = document.getElementsByName('option[]');
                                var a = [];
                                for (var i = 0; i < opt.length; i++) {
                                  a[i] = opt[i].value;
                                }

                                var sorted_arr = a.sort();
                                var results = [];
                                for (var i = 0; i < a.length - 1; i++) {
                                  if (sorted_arr[i + 1] == sorted_arr[i]) {
                                    results[i] = sorted_arr[i];
                                  }
                                }

                                if (results.length > 0) {
                                  alert('Option can not be duplicate value.');
                                  var ctlOption = document.getElementsByClassName('ctl-option');
                                  //ctlOption[ctlOption.length - 1].parentNode.removeChild(ctlOption[ctlOption.length - 1]);
                                  opt[opt.length - 1].select();
                                  return false;
                                } else {
                                  return true;
                                }
                              };

                              var isNumeric = function(sText) {
                                var validChars = "0123456789";
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
                            </script>
                            <script>
                              function saveForm() {
                                var property_id = $('#property_id').val();
                                var opt = document.getElementsByName('option[]');
                                var a = [];

                                for (var i = 0; i < opt.length; i++) {
                                  if (opt[i].value != '') {
                                    a[i] = opt[i].value;
                                  }
                                }

                                var okayz = true;
                                if (a.length == 0 && property_id == 2) {
                                  okayz = false;
                                  alert('Option required.');
                                } else {
                                  okayz = true;
                                }

                                if (okayz) {
                                  $.ajax({
                                    type: "POST",
                                    url: "<?php echo base_url() ?>referensi/pm_checklist/saveDataChecklist",
                                    data: $('#add-form2').serialize(),
                                    dataType: "json",
                                    beforeSend: function() {
                                      $.blockUI({message: '<img width="16px" src="<?php echo base_url('file/images/loading-snake.GIF'); ?>"> Saving data, please wait...', css: {
                                          border: 'none',
                                          padding: '15px',
                                          backgroundColor: '#000',
                                          '-webkit-border-radius': '10px',
                                          '-moz-border-radius': '10px',
                                          opacity: .5,
                                          color: '#fff',
                                          'z-index': 99999
                                        }});
                                    },
                                    success: function(data) {
                                      if (data.status == "OK") {
                                        coolNotification('Data has been added successfully.', 'success', function() {
                                          window.location.href = "<?php echo base_url('referensi/pm_checklist'); ?>";
                                        });
                                      } else if (data.status == "ERROR") {
                                      	coolNotification(data.hasil, 'error');
                                      } else {
                                        coolNotification('An error occured, please call the Administrator.', 'error');
                                      }
                                    },
                                    error: function(xhr, status, message) {
                                      coolNotification('An error occured, please call the Administrator.', 'error');
                                    },
                                    complete: function() {
                                      $.unblockUI();
                                    }
                                  });
                                  return false;
                                }

                              }

                              function setIsCritical(id) {
                                if ($('#is_critical2_' + id).is(':checked'))
                                {
                                  $('#is_critical_' + id).val(1);
                                } else {
                                  $('#is_critical_' + id).val(0);
                                }
                              }
                            </script>