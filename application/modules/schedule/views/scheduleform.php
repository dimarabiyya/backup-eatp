<script type="text/javascript" src="<?php echo base_url() ?>assets/js/notty/noty/jquery.noty.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/notty/noty/layouts/center.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/notty/noty/themes/default.js"></script>
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('assets/js/number_format.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/notyfy/themes/default.css" />
<link href="<?php echo base_url('assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style type="text/css">
  .widget-body {
    border-bottom: solid 1px #c1c1c1;
  }

  .widget-body:last-child {
    border-bottom: none;
  }

  .btn-group>.btn:hover,
  .btn-group>.btn:focus,
  .btn-group>.btn:active,
  .btn-group>.btn.active {
    z-index: 0;
  }
</style>
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

<div class="innerLR">
  <div class="row-fluid">
    <div class="span12">
      <form class="form-horizontal" method="post" id="schedule_form">
        <div class="widget">
          <div class="widget-head heading-outer">
            <h4 class="heading glyphicons list"><i></i> <?php echo $title; ?></h4>
          </div>
          <div class="widget-body">

            <div class="row-fluid" id="choose-po">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="site">Choose PO No.</label>
                  <div class="controls">
                    <input type="text" name="po" value="<?php echo (($schedule) ? $schedule->po_no : ''); ?>" id="po" class="input-medium uneditable" onfocus="lookUpPO()">
                    <input type="hidden" id="input_po_id" name="input_po_id" value="<?php echo (($schedule) ? $schedule->po_id : ''); ?>">
                    <button type="button" class="btn" onclick="lookUpPO()">...</button>
                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid" id="choose-site">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="site">Choose Site Link</label>
                  <div class="controls">
                    <select name="site_link" id="site_link" onChange="showDetailSite()">
                      <?php if ($schedule) : ?>
                        <option value="<?php echo $schedule->link_id ?>"><?php echo $schedule->link; ?></option>
                      <?php endif; ?>
                    </select>
                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid" id="site-detail" style="display: none;">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="site">Site Detail</label>
                  <div class="controls" id="content-site">

                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="pic">PIC</label>
                  <div class="controls">
                    <input type="text" name="user" id="user" value="<?php echo (($schedule) ? $schedule->pic_name : ''); ?>" class="input-medium uneditable" onfocus="lookUpUser()">
                    <input type="hidden" id="input_user_id" name="pic" value="<?php echo (($schedule) ? $schedule->pic_id : ''); ?>">
                    <button type="button" class="btn" onclick="lookUpUser()">...</button>
                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="sow">SOW</label>
                  <div class="controls">
                    <select name="pm-visit-type" id="pm-visit-type">
                      <option value=""></option>
                      <?php foreach ($sow as $pkey) : ?>
                        <option value="<?php echo $pkey->option_id ?>" data-group="<?php echo $pkey->option_value ?>" locked="<?php echo $pkey->locked; ?>" <?php echo (($schedule && $schedule->visit_type == $pkey->option_id) ? 'selected="selected"' : '') ?>>
                          <?php echo $pkey->option_label ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="tpd_id">TPD ID</label>
                  <div class="controls">
                    <input type="text" name="tpd_id" id="tpd_id" class="input-medium uneditable" value="<?php echo (($schedule) ? $schedule->tpd_id : '') ?>">
                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="priority">Priority</label>
              <div class="controls">
                <div class="btn-group" data-toggle="buttons-radio" id="pm-priority">
                  <?php foreach ($priority as $key => $val) : ?>
                    <button type="button" class="btn <?php echo ($schedule && ($schedule->priority == $val) ? 'active btn-primary' : '') ?>"><?php echo $val ?></button>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="remarks">Remark</label>
                  <div class="controls">
                    <textarea name="remark" id="remark" style="width:90%;height: 50px;"><?php echo (($schedule) ? $schedule->remark : '') ?></textarea>
                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="poNo">Schedule Date</label>
                  <div class="controls">
                    <input type="text" name="schedule_date" id="scheduleDate" class="input-small datepicker" placeholder="DD-MMM-YYYY" value="<?php echo (($schedule) ? (date('d-M-Y', strtotime($schedule->visit_date))) : '') ?>" />
                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="sow">Approval Template</label>

                  <div class="controls">
                    <?php $js = 'id="approval_template" onChange=""'; ?>

                    <?php echo form_dropdown('approval', $approval, '', $js); ?>
                    <!--<button id="get_recp" type="button" class="btn btn-primary"> Get Recipient </button>-->
                  </div>

                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="ecrf_ne">ECRF NE</label>
                  <div class="controls">
                    <input type="text" id="ecrf_ne" name="ecrf_number[]" class="input-medium uneditable" onfocus="lookUpECRF('ne')">
                    <input type="hidden" id="input_ecrf_ne" name="ecrf_id[]" onchange="showFormECRF('ne')">
                    <button type="button" class="btn" onclick="lookUpECRF('ne')">...</button>
                    <button type="button" class="btn btn-primary" onclick="clearECRF('ne')" id="clear_ne">Clear</button>
                    <select name="ecrf_type[]" id="ecrf_ne_type">
                      <option value="" disabled selected>-Choose-</option>
                      <option value="dismantle">Dismantle</option>
                      <option value="add">Add</option>
                      <option value="swap">Swap</option>
                    </select>
                  </div>
                  <div class="separator bottom"></div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="ecrf_ne_tower_index">Tower Index NE</label>
                  <div class="controls">
                    <input type="text" id="ecrf_ne_tower_index" name="tower_index[]" class="input-medium uneditable">
                  </div>
                </div>
              </div>
              <div id="dismantle_ecrf_ne" style="display: none;">
                <div class="span12">
                  <div class="control-group">
                    <h4 class="control-label">Dismantle</h4>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="ecrf_ne_quantity_before">Quantity Before</label>
                    <div class="controls">
                      <!-- UPDATE 02-10-2023 (Quantity hanya boleh 1) -->
                      <input type="text" id="ecrf_ne_quantity_before" name="quantity_dismantle[]" class="input-medium uneditable" disabled>
                      <!--  -->
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="ecrf_ne_height_before">Height Before</label>
                    <div class="controls">
                      <input type="text" id="ecrf_ne_height_before" name="height_dismantle[]" class="input-medium uneditable">
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="ecrf_ne_diameter_before">Diameter Before</label>
                    <div class="controls">
                      <!-- UPDATE 02-10-2023 (diameter menjadi selected) -->
                      <!-- <input type="text" id="ecrf_ne_diameter_before" name="diameter_dismantle[]" class="input-medium uneditable"> -->
                      <select name="diameter_dismantle[]" id="ecrf_ne_diameter_before">
                        <option value="" disabled selected>-Choose-</option>
                        <option value="0.3">0.3</option>
                        <option value="0.6">0.6</option>
                        <option value="1.2">1.2</option>
                        <option value="1.8">1.8</option>
                        <option value="2.4">2.4</option>
                        <option value="3.0">3.0</option>
                      </select>
                      <!--  -->
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="ecrf_ne_condition_before">Condition Before</label>
                    <div class="controls">
                      <input type="text" id="ecrf_ne_condition_before" name="condition_dismantle[]" class="input-medium uneditable">
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                </div>
              </div>
              <div id="add_ecrf_ne" style="display: none;">
                <div class="span12">
                  <div class="control-group">
                    <h4 class="control-label">Add</h4>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="ecrf_ne_quantity_after">Quantity after</label>
                    <div class="controls">
                      <!-- UPDATE 02-10-2023 (Quantity hanya boleh 1) -->
                      <input type="text" id="ecrf_ne_quantity_after" name="quantity_add[]" class="input-medium uneditable" disabled>
                      <!--  -->
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="ecrf_ne_height_after">Height after</label>
                    <div class="controls">
                      <input type="text" id="ecrf_ne_height_after" name="height_add[]" class="input-medium uneditable">
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="ecrf_ne_diameter_after">Diameter after</label>
                    <div class="controls">
                      <!-- UPDATE 02-10-2023 (diameter menjadi selected) -->
                      <!-- <input type="text" id="ecrf_ne_diameter_after" name="diameter_add[]" class="input-medium uneditable"> -->
                      <select name="diameter_add[]" id="ecrf_ne_diameter_after">
                        <option value="" disabled selected>-Choose-</option>
                        <option value="0.3">0.3</option>
                        <option value="0.6">0.6</option>
                        <option value="1.2">1.2</option>
                        <option value="1.8">1.8</option>
                        <option value="2.4">2.4</option>
                        <option value="3.0">3.0</option>
                      </select>
                      <!--  -->
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="ecrf_ne_condition_after">Condition after</label>
                    <div class="controls">
                      <input type="text" id="ecrf_ne_condition_after" name="condition_add[]" class="input-medium uneditable">
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="ecrf_fe_existing">ECRF FE Existing</label>
                  <div class="controls">
                    <input type="text" id="ecrf_fe_existing" name="ecrf_number[]" class="input-medium uneditable" onfocus="lookUpECRF('fe_existing')">
                    <input type="hidden" id="input_ecrf_fe_existing" name="ecrf_id[]" onchange="showFormECRF('fe_existing')">
                    <button type="button" class="btn" onclick="lookUpECRF('fe_existing')">...</button>
                    <button type="button" class="btn btn-primary" onclick="clearECRF('fe_existing')" id="clear_fe_existing">Clear</button>
                    <select name="ecrf_type[]" id="ecrf_fe_existing_type">
                      <option value="" disabled selected>-Choose-</option>
                      <option value="dismantle">Dismantle</option>
                      <option value="swap">Swap</option>
                    </select>
                  </div>
                  <div class="separator bottom"></div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="ecrf_fe_existing_tower_index">Tower Index FE Existing</label>
                  <div class="controls">
                    <input type="text" id="ecrf_fe_existing_tower_index" name="tower_index[]" class="input-medium uneditable">
                  </div>
                </div>
                <div id="dismantle_ecrf_fe_existing" style="display: none;">
                  <div class="span12">
                    <div class="control-group">
                      <h4 class="control-label">Dismantle</h4>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_existing_quantity_before">Quantity Before</label>
                      <div class="controls">
                        <!-- UPDATE 02-10-2023 (Quantity hanya boleh 1) -->
                        <input type="text" id="ecrf_fe_existing_quantity_before" name="quantity_dismantle[]" class="input-medium uneditable" disabled>
                        <!--  -->
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_existing_height_before">Height Before</label>
                      <div class="controls">
                        <input type="text" id="ecrf_fe_existing_height_before" name="height_dismantle[]" class="input-medium uneditable">
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_existing_diameter_before">Diameter Before</label>
                      <div class="controls">
                        <!-- UPDATE 02-10-2023 (diameter menjadi selected) -->
                        <!-- <input type="text" id="ecrf_fe_existing_diameter_before" name="diameter_dismantle[]" class="input-medium uneditable"> -->
                        <select name="diameter_dismantle[]" id="ecrf_fe_existing_diameter_before">
                          <option value="" disabled selected>-Choose-</option>
                          <option value="0.3">0.3</option>
                          <option value="0.6">0.6</option>
                          <option value="1.2">1.2</option>
                          <option value="1.8">1.8</option>
                          <option value="2.4">2.4</option>
                          <option value="3.0">3.0</option>
                        </select>
                        <!--  -->
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_existing_condition_before">Condition Before</label>
                      <div class="controls">
                        <input type="text" id="ecrf_fe_existing_condition_before" name="condition_dismantle[]" class="input-medium uneditable">
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                  </div>
                </div>
                <div id="add_ecrf_fe_existing" style="display: none;">
                  <div class="span12">
                    <div class="control-group">
                      <h4 class="control-label">Add</h4>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_existing_quantity_after">Quantity after</label>
                      <div class="controls">
                        <!-- UPDATE 02-10-2023 (Quantity hanya boleh 1) -->
                        <input type="text" id="ecrf_fe_existing_quantity_after" name="quantity_add[]" class="input-medium uneditable" disabled>
                        <!--  -->
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_existing_height_after">Height after</label>
                      <div class="controls">
                        <input type="text" id="ecrf_fe_existing_height_after" name="height_add[]" class="input-medium uneditable">
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_existing_diameter_after">Diameter after</label>
                      <div class="controls">
                        <!-- UPDATE 02-10-2023 (diameter menjadi selected) -->
                        <!-- <input type="text" id="ecrf_fe_existing_diameter_after" name="diameter_add[]" class="input-medium uneditable"> -->
                        <select name="diameter_add[]" id="ecrf_fe_existing_diameter_after">
                          <option value="" disabled selected>-Choose-</option>
                          <option value="0.3">0.3</option>
                          <option value="0.6">0.6</option>
                          <option value="1.2">1.2</option>
                          <option value="1.8">1.8</option>
                          <option value="2.4">2.4</option>
                          <option value="3.0">3.0</option>
                        </select>
                        <!--  -->
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_existing_condition_after">Condition after</label>
                      <div class="controls">
                        <input type="text" id="ecrf_fe_existing_condition_after" name="condition_add[]" class="input-medium uneditable">
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="ecrf_fe_after_reroute">ECRF FE After Reroute</label>
                  <div class="controls">
                    <input type="text" id="ecrf_fe_after_reroute" name="ecrf_number[]" class="input-medium uneditable" onfocus="lookUpECRF('fe_after_reroute')">
                    <input type="hidden" id="input_ecrf_fe_after_reroute" name="ecrf_id[]" onchange="showFormECRF('fe_after_reroute')">
                    <button type="button" class="btn" onclick="lookUpECRF('fe_after_reroute')">...</button>
                    <button type="button" class="btn btn-primary" onclick="clearECRF('fe_after_reroute')" id="clear_fe_after_reroute">Clear</button>
                    <select name="ecrf_type[]" id="ecrf_fe_after_reroute_type">
                      <option value="" disabled selected>-Choose-</option>
                      <option value="add">Add</option>
                    </select>
                  </div>
                  <div class="separator bottom"></div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="ecrf_fe_after_reroute_tower_index">Tower Index FE After Reroute</label>
                  <div class="controls">
                    <input type="text" id="ecrf_fe_after_reroute_tower_index" name="tower_index[]" class="input-medium uneditable">
                  </div>
                </div>
                <div id="dismantle_ecrf_fe_after_reroute" style="display: none;">
                  <div class="span12">
                    <div class="control-group">
                      <h4 class="control-label">Dismantle</h4>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_after_reroute_quantity_before">Quantity Before</label>
                      <div class="controls">
                        <!-- UPDATE 02-10-2023 (Quantity hanya boleh 1) -->
                        <input type="text" id="ecrf_fe_after_reroute_quantity_before" name="quantity_dismantle[]" class="input-medium uneditable" disabled>
                        <!--  -->
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_after_reroute_height_before">Height Before</label>
                      <div class="controls">
                        <input type="text" id="ecrf_fe_after_reroute_height_before" name="height_dismantle[]" class="input-medium uneditable">
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_after_reroute_diameter_before">Diameter Before</label>
                      <div class="controls">
                        <!-- UPDATE 02-10-2023 (diameter menjadi selected) -->
                        <!-- <input type="text" id="ecrf_fe_after_reroute_diameter_before" name="diameter_dismantle[]" class="input-medium uneditable"> -->
                        <select name="diameter_dismantle[]" id="ecrf_fe_after_reroute_diameter_before">
                          <option value="" disabled selected>-Choose-</option>
                          <option value="0.3">0.3</option>
                          <option value="0.6">0.6</option>
                          <option value="1.2">1.2</option>
                          <option value="1.8">1.8</option>
                          <option value="2.4">2.4</option>
                          <option value="3.0">3.0</option>
                        </select>
                        <!--  -->
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_after_reroute_condition_before">Condition Before</label>
                      <div class="controls">
                        <input type="text" id="ecrf_fe_after_reroute_condition_before" name="condition_dismantle[]" class="input-medium uneditable">
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                  </div>
                </div>
                <div id="add_ecrf_fe_after_reroute" style="display: none;">
                  <div class="span12">
                    <div class="control-group">
                      <h4 class="control-label">Add</h4>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_after_reroute_quantity_after">Quantity after</label>
                      <div class="controls">
                        <!-- UPDATE 02-10-2023 (Quantity hanya boleh 1) -->
                        <input type="text" id="ecrf_fe_after_reroute_quantity_after" name="quantity_add[]" class="input-medium uneditable" disabled>
                        <!--  -->
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_after_reroute_height_after">Height after</label>
                      <div class="controls">
                        <input type="text" id="ecrf_fe_after_reroute_height_after" name="height_add[]" class="input-medium uneditable">
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_after_reroute_diameter_after">Diameter after</label>
                      <div class="controls">
                        <!-- <input type="text" id="ecrf_fe_after_reroute_diameter_after" name="diameter_add[]" class="input-medium uneditable"> -->
                        <!-- UPDATE 02-10-2023 (diameter menjadi selected) -->
                        <select name="diameter_add[]" id="ecrf_fe_after_reroute_diameter_after">
                          <option value="" disabled selected>-Choose-</option>
                          <option value="0.3">0.3</option>
                          <option value="0.6">0.6</option>
                          <option value="1.2">1.2</option>
                          <option value="1.8">1.8</option>
                          <option value="2.4">2.4</option>
                          <option value="3.0">3.0</option>
                        </select>
                        <!--  -->
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="ecrf_fe_after_reroute_condition_after">Condition after</label>
                      <div class="controls">
                        <input type="text" id="ecrf_fe_after_reroute_condition_after" name="condition_add[]" class="input-medium uneditable">
                      </div>
                      <div class="separator bottom"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!--
              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="sow">List Receiver Email Notification</label>

                    <div class="span6">
                        <div id="divku" style="float:left,margin-left:200px;"> </div>
                    </div>

                    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <tbody>
                    </tbody>
                    </table>

                    <div class="separator bottom"></div>
                  </div>
                </div>
              </div>
                -->
            <div class="row-fluid">
              <div class="control-group">
                <label class="control-label">
                  <img rel="tooltip" data-placement="right" src="<?php echo base_url() . 'assets/images/exclamation.png' ?>" title="At least a checklist item must be selected" style="display:none" id="checklist-item-exclamation">
                  Checklist Items
                </label>
                <div class="controls">
                  <!-- <ul id="tt" class="easyui-tree" data-options="url:'<?php echo base_url() ?>pm/schedule/generateTree/',method:'get',checkbox:true,onlyLeafCheck:true "></ul> -->
                  <div class="control-group">
                    <button type="button" class="btn" id="btn-custom">Custom </button>
                    <button type="button" class="btn disabled" id="btn-checkall" disabled="disabled">Check All / Uncheck All</button>
                    <div style="margin-bottom: 10px;"></div>

                    <div id="content_checklist">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-actions">
                <div class="pull-left" id="jumlah-checklist">No checklist item is selected, please pick some</div>
                <button type="button" class="btn btn-primary btn-icon glyphicons circle_ok" id="save_schedule"><i></i> Save</button>
                <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo base_url("/schedule") ?>'"><i></i>Cancel</button>
              </div>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#pm-visit-type').select2();
    $('#approval_template').select2();
  });

  var dateToday = new Date();
  $('.datepicker').datepicker({
    minDate: dateToday
  });

  //dateFormat.init();
  /*Config if Edit*/
  var sch_id = "<?php echo (($schedule) ? $schedule->visit_id : 0); ?>";
  /*Config if Edit*/
  if (sch_id == 0) {
    $('#choose-site').hide();
  }

  //if button save is click
  $('#save_schedule').click(function() {
    //alert('1');
    saveSchedule();

  });

  /*sample Check*/
</script>

<script>
  function parse_rec(data) {
    var pos = data.toLowerCase();
    var res = pos.replace(" ", "_");
    return res;
  }
  $("#get_recp").on("click", function() {
    //alert('ok');
    var isi = $("#approval_template").val();
    var pos = isi.toLowerCase();
    var res = pos.replace(" ", "_");

    var user_id = $("#input_user_id").val();
    //alert(res);

    $.getJSON("<?php echo base_url('schedule/get_recipient_notif'); ?>/" + res, function(data) {
      //console.log(data);



      var items = [];
      $('#example tbody').empty();

      var no = 0;
      $.each(data.data, function(key, val) {
        no++;
        console.log(val);
        items.push("<tr>");
        items.push("<td><input type='checkbox' name='rec_info[]' class='rec_info' value='" + val.email + "'>" + val.user_id + "</td>");
        items.push("</tr>");
      });
      $("<tbody/>", {
        html: items.join("")
      }).appendTo("#example");


    });


  });

  function showDetailSite() {
    var link_id_int = $("#site_link").val();
    var url = "<?php echo site_url('/schedule/getInfoLink?link=') ?>";
    var url = url + link_id_int;
    console.log(url);
    $.get(url, function(data) {
      var obj_site = jQuery.parseJSON(data);
      console.log(obj_site);
      var content = "Address Site FE : " + obj_site.address_fe + "<br/>" +
        "Coordinate : (<a href='#'>" + obj_site.latitude_fe + "," + obj_site.longitude_fe + "</a>)<br/>" +
        "Address Site NE : " + obj_site.address_ne + "<br/>" +
        "Coordinate : (<a href='#'>" + obj_site.latitude_ne + "," + obj_site.longitude_ne + "</a>)";

      $("#content-site").empty();
      $("#content-site").append(content);
      $("#site-detail").show();
    });
  }
</script>
<script>
  function load_checklist(sch_id) {
    /*General Type Empty Default*/

    var url = "<?php echo site_url('/schedule/checklist?sch=') ?>";
    var url = url + "" + sch_id;
    $.get(url, function(data) {
      $("#content_checklist").empty();
      $("#content_checklist").append(data);
      $.unblockUI();
    });
  }
  /*call default when add*/
  /*If Edit Mode When Sign Checklist appropriate with*/
  load_checklist(sch_id);
</script>

<script>
  /*Function to Show PIC List*/
  function lookUpUser() {
    var url = "<?php echo site_url('/schedule/lookup_pic') ?>";

    $.fancybox({
      href: url,
      type: 'ajax',
      ajax: {
        data: {
          cmd: 'getData'
        }
      }, // e: it used for selected element
      width: 600,
      scrolling: false,
      autoSize: true,
      fitToView: true,
      closeClick: false,
      openEffect: 'fade',
      closeEffect: 'fade',
      afterShow: function() {
        $('.fancybox-wrap').draggable({
          handle: ".widget-head"
        });

        document.getElementById('btnDataSelectedOK').onclick = function() {
          var row = $('#gridUserPIC').datagrid('getSelected');

          if (row != null) {
            console.log("User Id :" + row.user_id);
            $('#user').val(row.user_name);
            $('#input_user_id').val(row.user_id);
            $("#divku").html("<table class='table table-striped table-bordered' cellspacing='0' width='100%'> <tbody> <tr> <td> <input type='checkbox' class='rec_info' name='rec_info[]' value='" + row.email + "'> " + row.user_id + "</td> </tr></tbody> </table>");
          }

          $.fancybox.close();
        };
      }
    });
  }
</script>
<script>
  /*Function to Show Site Link*/
  function lookUpLink() {
    var url = "<?php echo site_url('/schedule/lookup_site_link') ?>";

    $.fancybox({
      href: url,
      type: 'ajax',
      ajax: {
        data: {
          cmd: 'getData'
        }
      }, // e: it used for selected element
      width: 600,
      scrolling: false,
      autoSize: true,
      fitToView: true,
      closeClick: false,
      openEffect: 'fade',
      closeEffect: 'fade',
      afterShow: function() {
        $('.fancybox-wrap').draggable({
          handle: ".widget-head"
        });

        document.getElementById('btnDataSelectedOK').onclick = function() {
          var row = $('#gridSiteLink').datagrid('getSelected');

          if (row != null) {
            console.log("link :" + row.link_id_int);
            $('#sitelink').val(row.link_id);
            $('#input_link_id').val(row.link_id_int);
          }

          $.fancybox.close();
        };
      }
    });
  }
</script>
<script>
  function loadDataLink(po) {
    var url = "<?php echo site_url('/schedule/lookup_site_link/getlinkpo?po=') ?>";
    url = url + "" + po;

    /*Set Empty*/
    $('#site_link').empty();
    $.get(url, function(data) {
      var obj_link = jQuery.parseJSON(data);
      /*Choose Site LInk*/
      $('#site_link').append($('<option>', {
        value: "",
        text: "- Choose Link -"
      }));
      $.each(obj_link, function(key, item) {

        $('#site_link').append($('<option>', {
          value: item.link_id_int,
          text: item.link_id
        }));
      });
    });
    /*Show Site Link*/
    $('#choose-site').show();
  }
</script>
<script>
  /*Function to Show Site Link*/
  function lookUpPO() {
    var url = "<?php echo site_url('/schedule/lookup_po') ?>";

    $.fancybox({
      href: url,
      type: 'ajax',
      ajax: {
        data: {
          cmd: 'getData'
        }
      }, // e: it used for selected element
      width: 600,
      scrolling: false,
      autoSize: true,
      fitToView: true,
      closeClick: false,
      openEffect: 'fade',
      closeEffect: 'fade',
      afterShow: function() {
        $('.fancybox-wrap').draggable({
          handle: ".widget-head"
        });

        document.getElementById('btnDataSelectedOK').onclick = function() {
          var row = $('#gridPo').datagrid('getSelected');

          if (row != null) {
            console.log("po_id :" + row.po_id);
            $('#po').val(row.po_no);
            $('#input_po_id').val(row.po_id);
            loadDataLink(row.po_id);
          }

          $.fancybox.close();
        };
      }
    });
  }
  /*Save Form Schedule*/
  function saveSchedule() {
    var save_url = "<?php echo $action_url ?>";

    var checkValues = [];
    $('input[name=checklist_item]:checked').each(function() {
      checkValues.push($(this).val());
    });

    var ecrf_number = [];
    $('input[name="ecrf_number[]"]').each(function() {
      ecrf_number.push($(this).val());
    });

    var tower_index = [];
    $('input[name="tower_index[]"]').each(function() {
      tower_index.push($(this).val());
    });

    var ecrf_id = [];
    $('input[name="ecrf_id[]"]').each(function() {
      ecrf_id.push($(this).val());
    });

    var ecrf_type = [];
    $('select[name="ecrf_type[]"]').each(function() {
      ecrf_type.push($(this).val());
    });

    var quantity_dismantle = [];
    $('input[name="quantity_dismantle[]"]').each(function() {
      quantity_dismantle.push($(this).val());
    });

    var quantity_add = [];
    $('input[name="quantity_add[]"]').each(function() {
      quantity_add.push($(this).val());
    });

    var height_dismantle = [];
    $('input[name="height_dismantle[]"]').each(function() {
      height_dismantle.push($(this).val());
    });

    var height_add = [];
    $('input[name="height_add[]"]').each(function() {
      height_add.push($(this).val());
    });
    //* EDITED 03-10-2023 (Merubah input jadi select pada diameter)
    // var diameter_dismantle = [];
    // $('input[name="diameter_dismantle[]"]').each(function() {
    //   diameter_dismantle.push($(this).val());
    // });
    // var diameter_add = [];
    // $('input[name="diameter_add[]"]').each(function() {
    //   diameter_add.push($(this).val());
    // });
    var diameter_dismantle = [];
    $('select[name="diameter_dismantle[]"]').each(function() {
      diameter_dismantle.push($(this).val());
    });
    var diameter_add = [];
    $('select[name="diameter_add[]"]').each(function() {
      diameter_add.push($(this).val());
    });
    //*

    var condition_dismantle = [];
    $('input[name="condition_dismantle[]"]').each(function() {
      condition_dismantle.push($(this).val());
    });

    var condition_add = [];
    $('input[name="condition_add[]"]').each(function() {
      condition_add.push($(this).val());
    });

    $.blockUI({
      message: '<img src="<?php echo base_url('file/images/loading-snake.GIF'); ?>" width="16px"> Uploading Data, please wait...',
      css: {
        border: 'none',
        padding: '15px',
        backgroundColor: 'black',
        color: 'white',
        opacity: .5,
      }
    });

    $.post(save_url, {
      "po_id": $("#input_po_id").val(),
      "link_id": $("#site_link").val(),
      "pic_id": $("#input_user_id").val(),
      "visit_date": $("#scheduleDate").val(),
      "visit_type": $("#pm-visit-type").val(),
      "approval_template": $("#approval_template").val(),
      "remark": $("#remark").val(),
      "tpd_id": $("#tpd_id").val(),
      "priority": $('#pm-priority > button.active').html(),
      "checklist_item": checkValues,
      "ecrf_number": ecrf_number,
      "tower_index": tower_index,
      "ecrf_id": ecrf_id,
      "ecrf_type": ecrf_type,
      "quantity_dismantle": quantity_dismantle,
      "quantity_add": quantity_add,
      "height_dismantle": height_dismantle,
      "height_add": height_add,
      "diameter_dismantle": diameter_dismantle,
      "diameter_add": diameter_add,
      "condition_dismantle": condition_dismantle,
      "condition_add": condition_add
    }, function(data) {
      console.log(data);
      var obj_msg = JSON.parse(data);
      var redirect_url = "<?php echo base_url("/schedule") ?>";
      if (obj_msg.status == "success") {
        coolNotification('<ul style="text-align:left">' + obj_msg.message + '</ul>', 'success');
        window.location.href = redirect_url;
        // $location.path('/schedule');

      } else {
        coolNotification('<ul style="text-align:left">' + obj_msg.message + '</ul>', 'error');
      }
      $.unblockUI();
    });

  }
</script>

<!-- Add By Hary -->
<script>
  $(document).ready(function() {
    var status_token = "<?php echo $status_token ?>";
    if (status_token) {
      coolNotification('<ul style="text-align:left">' + status_token + '</ul>', 'error');
    }

    $('#ecrf_ne_type').change(function(e) {
      e.preventDefault();
      var ecrf = 'ne';
      var type = $('#ecrf_' + ecrf + "_type").val();
      switch (type) {
        case "swap":
          $('#dismantle_ecrf_' + ecrf).show();
          $('#add_ecrf_' + ecrf).show();
          $('#ecrf_' + ecrf + '_condition_before').val('dismantle');
          $('#ecrf_' + ecrf + '_quantity_before').val('1');
          $('#ecrf_' + ecrf + '_condition_after').val('add');
          $('#ecrf_' + ecrf + '_quantity_after').val('1');
          console.log('swap');
          break;

        case "dismantle":
          $('#dismantle_ecrf_' + ecrf).show();
          $('#add_ecrf_' + ecrf).hide();
          $('#ecrf_' + ecrf + '_condition_before').val(type);
          $('#ecrf_' + ecrf + '_quantity_before').val('1');
          console.log('dismantle');
          break;

        case "add":
          $('#dismantle_ecrf_' + ecrf).hide();
          $('#add_ecrf_' + ecrf).show();
          $('#ecrf_' + ecrf + '_condition_after').val(type);
          $('#ecrf_' + ecrf + '_quantity_after').val('1');
          console.log('add');
          break;

        default:
          break;
      }
      console.log(ecrf, type);
    });

    $('#ecrf_fe_existing_type').change(function(e) {
      e.preventDefault();
      var ecrf = 'fe_existing';
      var type = $('#ecrf_' + ecrf + "_type").val();
      switch (type) {
        case "swap":
          $('#dismantle_ecrf_' + ecrf).show();
          $('#add_ecrf_' + ecrf).show();
          $('#ecrf_' + ecrf + '_condition_before').val('dismantle');
          $('#ecrf_' + ecrf + '_quantity_before').val('1');
          $('#ecrf_' + ecrf + '_condition_after').val('add');
          $('#ecrf_' + ecrf + '_quantity_after').val('1');
          console.log('swap');
          break;

        case "dismantle":
          $('#dismantle_ecrf_' + ecrf).show();
          $('#add_ecrf_' + ecrf).hide();
          $('#ecrf_' + ecrf + '_condition_before').val(type);
          $('#ecrf_' + ecrf + '_quantity_before').val('1');
          console.log('dismantle');
          break;

        case "add":
          $('#dismantle_ecrf_' + ecrf).hide();
          $('#add_ecrf_' + ecrf).show();
          $('#ecrf_' + ecrf + '_condition_after').val(type);
          $('#ecrf_' + ecrf + '_quantity_after').val('1');
          console.log('add');
          break;

        default:
          break;
      }
      console.log(ecrf, type);
    });

    $('#ecrf_fe_after_reroute_type').change(function(e) {
      e.preventDefault();
      var ecrf = 'fe_after_reroute';
      var type = $('#ecrf_' + ecrf + "_type").val();
      switch (type) {
        case "swap":
          $('#dismantle_ecrf_' + ecrf).show();
          $('#add_ecrf_' + ecrf).show();
          $('#ecrf_' + ecrf + '_condition_before').val('dismantle');
          $('#ecrf_' + ecrf + '_quantity_before').val('1');
          $('#ecrf_' + ecrf + '_condition_after').val('add');
          $('#ecrf_' + ecrf + '_quantity_after').val('1');
          console.log('swap');
          break;

        case "dismantle":
          $('#dismantle_ecrf_' + ecrf).show();
          $('#add_ecrf_' + ecrf).hide();
          $('#ecrf_' + ecrf + '_condition_before').val(type);
          $('#ecrf_' + ecrf + '_quantity_before').val('1');
          console.log('dismantle');
          break;

        case "add":
          $('#dismantle_ecrf_' + ecrf).hide();
          $('#add_ecrf_' + ecrf).show();
          $('#ecrf_' + ecrf + '_condition_after').val(type);
          $('#ecrf_' + ecrf + '_quantity_after').val('1');
          console.log('add');
          break;

        default:
          break;
      }
      console.log(ecrf, type);
    });
  });

  function lookUpECRF(ecrf) {
    var url = "<?php echo site_url('/schedule/lookup_ecrf') ?>";
    $.fancybox({
      href: url,
      type: 'ajax',
      ajax: {
        data: {
          cmd: 'getData',
          token: '<?php echo $token_ecrf ?>',
          token_type: '<?php echo $token_type_ecrf ?>',
        }
      }, // e: it used for selected element
      width: 600,
      scrolling: false,
      autoSize: true,
      fitToView: true,
      closeClick: false,
      openEffect: 'fade',
      closeEffect: 'fade',
      afterShow: function() {
        $('.fancybox-wrap').draggable({
          handle: ".widget-head"
        });

        document.getElementById('btnDataSelectedOK').onclick = function() {
          var row = $('#gridECRFApproval').datagrid('getSelected');
          if (row != null) {
            $('#ecrf_' + ecrf + '').val(row.ecrfNumber);
            $('#input_ecrf_' + ecrf + '').val(row.id);
            var type = row.ecrfType.toLowerCase();
            switch (type) {
              case "swap":
                $("#ecrf_" + ecrf + "_type option:contains('Swap')").prop("selected", true);
                break;
              case "dismantle only":
                $("#ecrf_" + ecrf + "_type option:contains('Dismantle')").prop("selected", true);
                break;

              case "add":
                $("#ecrf_" + ecrf + "_type option:contains('Add')").prop("selected", true);
                break;

              default:
                break;
            }
            $("#ecrf_" + ecrf + "_type").trigger("change");
            console.log(type, row.ecrfType)
          }

          $.fancybox.close();
        };
      }
    });
  }

  function clearECRF(ecrf) {
    $('#dismantle_ecrf_' + ecrf + ' input').val('');
    $('#add_ecrf_' + ecrf + ' input').val('');
    $('#dismantle_ecrf_' + ecrf + '').hide();
    $('#add_ecrf_' + ecrf + '').hide();

    $('#ecrf_' + ecrf + '').val('');
    $('#ecrf_' + ecrf + '_type option:selected').prop("selected", false);
    $('#ecrf_' + ecrf + '_type').val('');
    $('#ecrf_' + ecrf + '_tower_index').val('');
    $('#input_ecrf_' + ecrf + '').val('');
    console.log("clear", ecrf)
  }

  function template_form_ecrf(ecrf, type, hide) {
    var stat = "";
    switch (type) {
      case "add":
        stat = "after"
        break;
      case "dismantle":
        stat = "before"
        break;
      default:
        break;
    }
    return `
            <div class="span12" ${hide}>
                  <div class="control-group">
                    <h4 class="control-label">${type}</h4>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="${ecrf}_quantity_before">Quantity ${stat}</label>
                    <div class="controls">
                      <input type="text" id="${ecrf}_quantity_before" name="quantity_${type}[]" class="input-medium uneditable" value="1">
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="${ecrf}_height_before">Height ${stat}</label>
                    <div class="controls">
                      <input type="text" id="${ecrf}_height_before" name="height_${type}[]" class="input-medium uneditable">
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="${ecrf}_diameter_before">Diameter ${stat}</label>
                    <div class="controls">
                      <input type="text" id="${ecrf}_diameter_before" name="diameter_${type}[]" class="input-medium uneditable">
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="${ecrf}_condition_before">Condition ${stat}</label>
                    <div class="controls">
                      <input type="text" id="${ecrf}_condition_before" name="condition_${type}[]" class="input-medium uneditable" value="${type}">
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                </div>`;
  }

  // add By Hary
  $(document).ready(function() {
    var ecrf_ne = <?php echo json_encode($ecrf_ne) ?>;
    var ecrf_fe_existing = <?php echo json_encode($ecrf_fe_existing) ?>;
    var ecrf_fe_after_reroute = <?php echo json_encode($ecrf_fe_after_reroute) ?>;
    console.log(ecrf_ne, ecrf_fe_existing, ecrf_fe_after_reroute);

    if (ecrf_ne.length > 0) {
      $.each(ecrf_ne, function(index, value) {
        var type = value.ecrf_type;
        $('#ecrf_ne').val(value.ecrf_number);
        $('#input_ecrf_ne').val(value.ecrf_id);
        $('#ecrf_ne_tower_index').val(value.tower_index);
        switch (type) {
          case "swap":
            $("#ecrf_ne_type option:contains('Swap')").prop("selected", true);
            $("#ecrf_ne_type").trigger("change");
            if (value.condition == "dismantle") {
              $('#ecrf_ne_quantity_before').val(value.quantity);
              $('#ecrf_ne_height_before').val(value.height);
              $('#ecrf_ne_diameter_before').val(value.diameter);
              $('#ecrf_ne_condition_before').val(value.condition);
            } else {
              $('#ecrf_ne_quantity_after').val(value.quantity);
              $('#ecrf_ne_height_after').val(value.height);
              $('#ecrf_ne_diameter_after').val(value.diameter);
              $('#ecrf_ne_condition_after').val(value.condition);
            }
            break;
          case "dismantle":
            $("#ecrf_ne_type option:contains('Dismantle')").prop("selected", true);
            $("#ecrf_ne_type").trigger("change");
            $('#ecrf_ne_quantity_before').val(value.quantity);
            $('#ecrf_ne_height_before').val(value.height);
            $('#ecrf_ne_diameter_before').val(value.diameter);
            $('#ecrf_ne_condition_before').val(value.condition);
            break;

          case "add":
            $("#ecrf_ne_type option:contains('Add')").prop("selected", true);
            $("#ecrf_ne_type").trigger("change");
            $('#ecrf_ne_quantity_after').val(value.quantity);
            $('#ecrf_ne_height_after').val(value.height);
            $('#ecrf_ne_diameter_after').val(value.diameter);
            $('#ecrf_ne_condition_after').val(value.condition);
            break;

          default:
            break;
        }
      });
    }
    if (ecrf_fe_existing.length > 0) {
      $.each(ecrf_fe_existing, function(index, value) {
        var type = value.ecrf_type;
        $('#ecrf_fe_existing').val(value.ecrf_number);
        $('#input_ecrf_fe_existing').val(value.ecrf_id);
        $('#ecrf_fe_existing_tower_index').val(value.tower_index);
        switch (type) {
          case "swap":
            $("#ecrf_fe_existing_type option:contains('Swap')").prop("selected", true);
            $("#ecrf_fe_existing_type").trigger("change");
            if (value.condition == "dismantle") {
              $('#ecrf_fe_existing_quantity_before').val(value.quantity);
              $('#ecrf_fe_existing_height_before').val(value.height);
              $('#ecrf_fe_existing_diameter_before').val(value.diameter);
              $('#ecrf_fe_existing_condition_before').val(value.condition);
            } else {
              $('#ecrf_fe_existing_quantity_after').val(value.quantity);
              $('#ecrf_fe_existing_height_after').val(value.height);
              $('#ecrf_fe_existing_diameter_after').val(value.diameter);
              $('#ecrf_fe_existing_condition_after').val(value.condition);
            }
            break;
          case "dismantle":
            $("#ecrf_fe_existing_type option:contains('Dismantle')").prop("selected", true);
            $("#ecrf_fe_existing_type").trigger("change");
            $('#ecrf_fe_existing_quantity_before').val(value.quantity);
            $('#ecrf_fe_existing_height_before').val(value.height);
            $('#ecrf_fe_existing_diameter_before').val(value.diameter);
            $('#ecrf_fe_existing_condition_before').val(value.condition);
            break;

          case "add":
            $("#ecrf_fe_existing_type option:contains('Add')").prop("selected", true);
            $("#ecrf_fe_existing_type").trigger("change");
            $('#ecrf_fe_existing_quantity_after').val(value.quantity);
            $('#ecrf_fe_existing_height_after').val(value.height);
            $('#ecrf_fe_existing_diameter_after').val(value.diameter);
            $('#ecrf_fe_existing_condition_after').val(value.condition);
            break;

          default:
            break;
        }
      });
    }
    if (ecrf_fe_after_reroute.length > 0) {
      $.each(ecrf_fe_after_reroute, function(index, value) {
        var type = value.ecrf_type;
        $('#ecrf_fe_after_reroute').val(value.ecrf_number);
        $('#input_ecrf_fe_after_reroute').val(value.ecrf_id);
        $('#ecrf_fe_after_reroute_tower_index').val(value.tower_index);
        switch (type) {
          case "swap":
            $("#ecrf_fe_after_reroute_type option:contains('Swap')").prop("selected", true);
            $("#ecrf_fe_after_reroute_type").trigger("change");
            if (value.condition == "dismantle") {
              $('#ecrf_fe_after_reroute_quantity_before').val(value.quantity);
              $('#ecrf_fe_after_reroute_height_before').val(value.height);
              $('#ecrf_fe_after_reroute_diameter_before').val(value.diameter);
              $('#ecrf_fe_after_reroute_condition_before').val(value.condition);
            } else {
              $('#ecrf_fe_after_reroute_quantity_after').val(value.quantity);
              $('#ecrf_fe_after_reroute_height_after').val(value.height);
              $('#ecrf_fe_after_reroute_diameter_after').val(value.diameter);
              $('#ecrf_fe_after_reroute_condition_after').val(value.condition);
            }
            break;
          case "dismantle":
            $("#ecrf_fe_after_reroute_type option:contains('Dismantle')").prop("selected", true);
            $("#ecrf_fe_after_reroute_type").trigger("change");
            $('#ecrf_fe_after_reroute_quantity_before').val(value.quantity);
            $('#ecrf_fe_after_reroute_height_before').val(value.height);
            $('#ecrf_fe_after_reroute_diameter_before').val(value.diameter);
            $('#ecrf_fe_after_reroute_condition_before').val(value.condition);
            break;

          case "add":
            $("#ecrf_fe_after_reroute_type option:contains('Add')").prop("selected", true);
            $("#ecrf_fe_after_reroute_type").trigger("change");
            $('#ecrf_fe_after_reroute_quantity_after').val(value.quantity);
            $('#ecrf_fe_after_reroute_height_after').val(value.height);
            $('#ecrf_fe_after_reroute_diameter_after').val(value.diameter);
            $('#ecrf_fe_after_reroute_condition_after').val(value.condition);
            break;

          default:
            break;
        }
      });
    }
  });
</script>