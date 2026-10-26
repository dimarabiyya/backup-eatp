<!-- <script type="text/javascript" src="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.js"></script> -->
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/jquery.noty.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/layouts/center.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/themes/default.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css"/>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/themes/default.css"/>

<style type="text/css">
  .widget-body{
    border-bottom: solid 1px #c1c1c1;
  }
  .widget-body:last-child{
    border-bottom: none;
  }
</style>

<!-- <div class="heading-buttons">
  <h3>
    <?php echo $title;
      if(isset($subTitle) OR trim($subTitle) != '' ) 
        { echo '<span class="hidden-phone">| '.$subTitle.'</span>'; } 
    ?>
  </h3>
</div>
<div class="separator bottom"></div> -->

<div class="innerLR">
  <div class="row-fluid">
    <div class="span9">
      <div class="widget">
        <div class="widget-head">
          <h4 class="heading glyphicons calendar"><i></i> <?php echo $title; ?></h4>
        </div>

        <div class="widget-body">
          <div class="form-horizontal">
            <div class="control-group">
              <label class="control-label">Site Address</label>
              <div class="controls">
                <p style="margin-top:5px"><?php echo $site->site_address ?></p>
                <p><?php 
                  echo '<img src="'.base_url().'assets/images/marker.png" style="margin-top: -5px"/>' ;
                  echo '<a href="#" onclick="showMap('.$site->latitude.', '.$site->longitude.')">'.$site->latitude.', '.$site->longitude.'</a>';
                ?></p>
                <p><?php echo $site->region_name ?></p>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label">Site ID</label>
              <div class="controls">
                <input type="text" id="siteCode" name="siteCode" class="span6" value="<?php echo $site->site_code; ?>" readonly="readonly" />
                <input type="hidden" id="siteID" name="siteId" value="<?php echo $site->site_id; ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Vendor ID</label>
              <div class="controls">
                <label class="vendor-id" id="vendor-id" style="float:left; margin-top:5px;"><?php echo $site->vendor_id; ?></label>
                <!-- <input type="text" id="vendorId" name="vendorId" class="span6" value="<?php echo $site->vendor_id; ?>" readonly="readonly" /> -->
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Site Name</label>
              <div class="controls">
                <input type="text" name="siteName" class="span6" value="<?php echo $site->site_name; ?>" readonly="readonly" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">PIC</label>
              <div class="controls">
                <select name="picID" id="picID">
                  <?php foreach ($pic as $user) { 
                    if($user->user_id == $site->pic_smart) 
                      { echo '<option value="'.$user->user_id.'" data-group="default" selected="selected">'.$user->user_label.'</option>'; }
                    else
                      { echo '<option value="'.$user->user_id.'" data-group="no-default">'.$user->user_label.'</option>'; }
                  } ?>
                </select> 
				        <input type="hidden" value="<?php echo $site->pic_smart ?>" id="pic-smart">
				
					<label id="remind-reason" style="color:red;"></label>
                
				<!-- <input type="text" id="picName" name="picName" class="span6" value="<?php echo $site->pic_name; ?>" readonly="readonly" /> -->
                <!-- <input type="hidden" id="picID" name="picId" value="<?php echo $site->pic_smart; ?>" /> -->
                <img rel="tooltip" data-placement="right" src="<?php echo base_url().'assets/images/exclamation.png' ?>" title="Can not be empty" style="display:none" id="pic-exclamation">
              </div>
            </div>
			<div id="Reason" style="display:none;">
			<div class="control-group">
				<label class="control-label" id="label-reason"  style="color:red;">Reason *</label>
			  <div class="controls">
				<textarea rows="3" class="span12" id="pic_non_default_remark"></textarea>
			  </div>
			</div> &nbsp;
			</div>
            <div class="control-group">
              <label class="control-label">Visit Type</label>
              <div class="controls">
                <div class="btn-group">
                <select name="pm-visit-type" id="pm-visit-type">
                    <option value=""></option>
                  <?php foreach ($visit_type as $pkey) {if($pkey->status == 1){?>
                    <option value="<?php echo $pkey->option_id ?>" data-group="<?php echo $pkey->option_value ?>" locked="<?php echo $pkey->locked; ?>"><?php echo $pkey->option_label ?></option>
                  <?php } } ?>
                </select>
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="priority">Priority</label>
              <div class="controls">
                <div class="btn-group" data-toggle="buttons-radio" id="pm-priority">
                  <?php foreach ($priority as $pkey) : ?>
                  <button type="button" class="btn" ><?php echo $pkey->lookup_desc; ?></button>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Remark</label>
              <div class="controls">
			  <textarea rows="3" class="span12" id="remark"></textarea>
              </div>
			</div>
            <div class="control-group">
              <label class="control-label">
                <img rel="tooltip" data-placement="right" src="<?php echo base_url().'assets/images/exclamation.png' ?>" title="At least a checklist item must be selected" style="display:none" id="checklist-item-exclamation">
                Checklist Items
              </label>
              <div class="controls">
                <!-- <ul id="tt" class="easyui-tree" data-options="url:'<?php echo base_url() ?>pm/schedule/generateTree/',method:'get',checkbox:true,onlyLeafCheck:true "></ul> -->
                <div class="control-group">
          <button type="button" class="btn" id="btn-custom">Custom</button>
					<button type="button" class="btn disabled" id="btn-checkall" disabled="disabled">Check All / Uncheck All</button>
				</div>
				&nbsp;
                <div class="accordion pm-visit-type-options" id="accordion">

                <?php 
                  function render($properties) {
                    function sub_render($parent, $iterasi, $pgi) {
                      // print_r($parent); die();
                      if($iterasi) 
                        { echo '<div class="accordion-group pgi_div_'.$pgi.' checklist-group-header checklist_group_level_0"  id="group_heading_'.$parent['group_id'].'">'; }
                      else 
                        { echo '<div class="accordion-group pgi_div_'.$pgi.' checklist-group-header"  id="group_heading_'.$parent['group_id'].'">'; }

                      echo '<div class="accordion-heading">'; 

                      if($iterasi) 
                        { echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#group_id_'.$parent['group_id'].'">'; }
                      else
                        { echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="" href="#group_id_'.$parent['group_id'].'">'; }

                      echo  $parent['group_name'];
                      echo '</a>';
                      echo '</div>';
                      echo '<div id="group_id_'.$parent['group_id'].'" class="accordion-body in collapse">';
                      if($iterasi) 
                        { echo '<div class="accordion-inner" id="accordion_level_'.$parent['group_id'].'">'; }
                      else 
                        { echo '<div class="accordion-inner">'; }

                      if( isset($parent['sub_group']) ) {
                        $_2nd = 1;
                        foreach ($parent['sub_group'] as $gg) {
                          sub_render($gg, false, $pgi);
                        }
                      }
                      if(isset($parent['checklists'])) {
                        foreach ($parent['checklists'] as $cc) {
                          item_render($cc, $parent['group_id'], $pgi);
                        }
                      }
                      echo '</div>';
                      echo '</div>';
                      echo '</div>';
                    }

                    function item_render($checklist, $parent_group_id, $pgi) {
                      echo '<label class="checkbox checklist-items">';
                      echo '<input type="checkbox" onchange="sub_pgi(\'pgi_'.$pgi.'\')" value="'.$checklist['checklist_id'].'" class="pm-checklist-item checkbox checklist_items pgi_'.$pgi.' '.$checklist['class'].'" name="checklist_item" disabled="disabled">';
                      echo $checklist["checklist_name"];
                      if ($checklist["interface"]) 
                        { echo '<img src="'.base_url().'assets/images/interface-'.$checklist["interface"].'.png" style="margin: -4px 0 0 4px">'; }
                      echo '</label>';
                    }

                    $_1st = 1;
                    foreach ($properties as $gg) {
                      sub_render($gg, $_1st, $_1st++);
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
          <button type="submit" id="submit-pm-schedule" class="btn disabled btn-success">Save</button>
          <button type="reset" id="reset-pm-schedule" class="btn btn-reset" onclick="resetall()">Reset</button>
        </div>
      </div> <!-- end of widget -->
    </div>

    <div class="span3">
      <div class="widget">
        <div class="widget-head">
          <h4 class="heading glyphicons calendar"><i></i> Pick Date</h4>
        </div>

        <div class="widget-body">

          <label>Opr. Year:</label>
          <select name="operational_year" id="opr_year">
            <option value="<?php echo date('Y')-1; ?>" <?php if(date('Y')-1 == $year) {echo 'selected="selected"'; } ?> > <?php echo date('Y')-1; ?></option>
            <option value="<?php echo date('Y'); ?>" <?php if(date('Y') == $year) {echo 'selected="selected"'; } ?> > <?php echo date('Y'); ?></option>
            <option value="<?php echo date('Y')+1; ?>" <?php if(date('Y')+1 == $year) {echo 'selected="selected"'; } ?> > <?php echo date('Y')+1; ?></option>
          </select> &nbsp;

          <label>Opr. Month:</label>
            <select name="operational_month" id="opr_month">
              <?php 
                for ($mm=1; $mm <= 12; $mm++) { 
                  if(date('m') ==  $mm )
                    { echo '<option value="'.$mm.'" selected="selected">'.date("F", mktime(0,0,0,$mm,1,date('Y'))).'</option>'; }
                  else
                    { echo '<option value="'.$mm.'" >'.date("F", mktime(0,0,0,$mm,1,date('Y'))).'</option>'; }
                }
              ?>
            </select>
          <div id="calendar-inline">
            <?php print_r($calendar); ?>
          </div>
        </div>
      </div> <!-- end of widget -->

      <!--
	<div class="widget" id="cross-reff-schedule">
        <div class="widget-head">
          <h4 class="heading glypicons calendar"><i></i> Site Inspecion Cross Schedule</h4>
        </div>

      </div>
	-->
    </div>
  </div>
</div>


<div class="modal" id="doubleVisit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Confirmation Needed !</h3>
  </div>
  <div class="modal-body">
    <p id="mm"></p>
    <table class="table table-bordered table-condensed">
      <tr>
        <td width="25%">Visit Plan</td>
        <td id="mdlVisitPlan"> </td>
      </tr>
      <tr>
        <td width="25%">User ID</td>
        <td id="mdlUserId"> </td>
      </tr>
      <tr>
        <td width="25%">Visit Type</td>
        <td id="mdlVisitType"> </td>
      </tr>
      <tr>
        <td width="25%">Priority</td>
        <td id="mdlPriority"> </td>
      </tr>
      <tr>
        <td width="25%">Remark</td>
        <td id="mdlRemark"> </td>
      </tr>
    </table>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <!-- <button class="btn" onclick="save_schedule()">Save Schedule</button> -->
  </div>
</div>

<script type="text/javascript">
  var year       = null;
  var site_id    = null;
  var site_code  = null;
  var site_name  = null;
  var month      = null;
  var date       = null;
  var date_alert = "Pick a date";
  var pic_non_default_remark 	 = null;
  checkDate();
  var today      = new Date();
      today.setHours(0);
      today.setMinutes(0);
      today.setSeconds(0);
      today.setMilliseconds(0);
  var visitdate  = new Date();  
  
$(document).ready(function(){
  $.each($('.checklist-group-header'), function(){
    if(!$(this).find('.checklist-items').length) {$(this).hide();}
  });

  count_item();
  group_number();
  
  $("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
  
  $('#pm-visit-type').change(function(){
    var d = $('#pm-visit-type').val();

    var c = $('#pm-visit-type option[value='+d+']').attr('data-group');
    
    $('.pm-visit-type-options :checkbox').removeAttr('checked');
    $('.pm-visit-type-options :checkbox.'+c).attr('checked', true);
    $('.pm-checklist-item').attr('disabled', true);
    count_item();
    group_number();
  });

  $('#opr_year, #opr_month').click(function(){
    $.post(
      "<?php echo base_url('pm/schedule/getVisitCal'); ?>",
      {year_ops: $('#opr_year').val(), month_ops: $('#opr_month').val(), site_id: "<?php echo $site->site_id; ?>"},
      function(data) {
        $('#calendar-inline').html(jQuery.parseJSON(data));
      }
    );
  });

  $('#submit-pm-schedule').click(function () {
		var pic = document.getElementById('picID').options[document.getElementById('picID').selectedIndex].getAttribute('data-group');
		if (pic == 'no-default' && document.getElementById('pic_non_default_remark').value == '') {
			var n = noty({
				  layout       : 'center',
				  theme        : 'defaultTheme',
				  type         : 'error',
				  text         : 'Fill The Reason',
				  dismissQueue : true,
				  modal        : true,
				  timeout      : 5000
				});
			//document.getElementById('pm-reason').focus();
			return false;
			document.getElementById('pic_non_default_remark').focus();
		} else {
		
			  if(!date ) {
				var n = noty({
				  layout       : 'center',
				  theme        : 'defaultTheme',
				  type         : 'error',
				  text         : date_alert,
				  dismissQueue : true,
				  modal        : true,
				  timeout      : 5000
				});
			  } 
			  else if ($('#picID').val() == '') {
				var n = noty({
				  layout       : 'center',
				  theme        : 'defaultTheme',
				  type         : 'error',
				  text         : 'PIC is not defined',
				  dismissQueue : true,
				  modal        : true,
				  timeout      : 5000
				});
				$('#pic-exclamation').tooltip();
				$('#pic-exclamation').show();
			  }
			  else if ($('input[name=checklist_item]:checked').length == 0) {
				var n = noty({
				  layout       : 'center',
				  theme        : 'defaultTheme',
				  type         : 'error',
				  text         : 'At least one checklist item must be selected',
				  dismissQueue : true,
				  modal        : true,
				  timeout      : 5000
				});
				$("#checklist-item-exclamation").tooltip();
				$("#checklist-item-exclamation").show();
			  }
			  else {
				// save pm schedule
				$.post(
				  "<?php echo base_url('pm/schedule/havingSchedule'); ?>",
				  {
					site_id     : $('input#siteID').val(),
					// pic_id      : $('#picID').val(),
					user_id     : $('#picID').val(),
					visit_date  : year+'-'+month+'-'+date,
					priority    : $('#pm-priority > button.active').html(),
					visit_type  : $('#pm-visit-type option:selected').html(),
					// remark      : $('#remark').val(),
					// pic_non_default_remark    : $('#pic_non_default_remark ').val(),
				  },
				  function(data) {
					var datax = jQuery.parseJSON(data);
					if(visitdate >= today) {
					  if(datax.status == true)
					  {
						$('#mm').html(datax.message);
						$('#mdlVisitPlan').html(datax.detail.visit_date);
						$('#mdlUserId').html('('+datax.detail.user_id+') '+datax.detail.user_name);
						$('#mdlTpId').html(datax.detail.tp_id);
						$('#mdlVisitType').html(datax.detail.visit_type);
						$('#mdlPriority').html(datax.detail.priority);
						$('#mdlRemark').html(datax.detail.remark);
						$('#doubleVisit').modal('show');
					  }
					  else
						{ save_schedule(); }
					} 
					else {
					  var n = noty({
						layout       : 'center',
						theme        : 'defaultTheme',
						type         : 'error',
						text         : 'Back date schedule is not allowed',
						dismissQueue : true,
						modal        : true,
						timeout      : 5000
					  });
					  // alert('Back date schedule not allowed!');
					} // end of back date comptibility check

				  }

				);
			  }
			  }
  });

});

function count_item () {
  var item = $('#accordion :checked').length;
  if(item == 0) {var msg = 'No checklist item is selected, please pick some';}
  else if(item == 1) {var msg = 'One checklist item is selected';}
  else {var msg = item + ' checklist items are selected';}
  $('#jumlah-checklist').html(msg);
}

function group_number(elId) {
  if(elId) {
    var cl = $('#'+elId).attr('class').split(' ');
    var c  = cl[1];
    $.each($('.'+c), function(x,y){
      $($(this).find('.checklist-total')).remove();
      var r = $(this).children().after().find(':checkbox');
      var u = $(this).children().after().find(':checked');
      var x = '<a class="pull-right accordion-toggle checklist-total" onclick="cua(\''+$(this).attr('id')+'\')" data-toggle="tooltip" title="Click to select/unselect All Checklist">'+u.length+'/'+r.length+'</a>';
      $(x).prependTo($(this).find('.accordion-heading'));
    }); 
  }
  else {

    $('.checklist-total').remove();
    $.each($('.accordion-group'), function(x,y){
      var p = $(this).attr('id');
      // console.log(p);
      var r = $(this).children().after().find(':checkbox');
      var u = $(this).children().after().find(':checked');
      var x = '<a class="pull-right accordion-toggle checklist-total" onclick="cua(\''+p+'\')" data-toggle="tooltip" title="Click to select/unselect All Checklist">'+u.length+'/'+r.length+'</a>';
      $(x).prependTo('div#'+p+' > .accordion-heading');
    }); 
  }
  $('.checklist-total').tooltip({'placement':'left'});
}

function cua (elId) {
  var lock = $('#pm-visit-type option:selected').attr('locked');
  var dx = elId.split('_');
  var group_id = 'group_id_'+dx[2].trim();
  // console.log(group_id);

  var cl = $('#'+group_id+' :checkbox'); // jumlah checklist (tercentang/tidak)
  var cc = $('#'+group_id+' :checkbox:checked'); // jumlah checklist yang dicentang
  if(lock != 1) {
    if(cl.length != cc.length) {
      $.each(cl, function function_name () {
        if($(this).attr('disabled') != 'disabled' ) {
          $(this).attr('checked', true);
        }
      })
    }
    else {
      $.each(cl, function function_name () {
        if($(this).attr('disabled') != 'disabled' ) {
          // $(this).attr('checked', true);
          $(this).removeAttr('checked');
        }
      })
    }
    count_item();
    group_number(elId);
  }
}

function sub_pgi (pgi_id) {
  count_item();
  var dpgi = pgi_id.split('_');
  var ghid = $('.pgi_div_'+dpgi[1]).attr('id');
  group_number(ghid);
}

function save_schedule() {
  var checkValues = [];
  $('input[name=checklist_item]:checked').each(function() {
    checkValues.push($(this).val());
  });

  $.post(
    "<?php echo base_url('pm/schedule/save'); ?>",
    {
      site_id       			: $('input#siteID').val(),
      pic_id         			: $('#pic-smart').val(),
      user_id        			: $('#picID').val(),
      visit_date     			: year+'-'+month+'-'+date,
      priority       			: $('#pm-priority > button.active').html(),
      visit_type     			: $('#pm-visit-type option:selected').html(),
      remark         			: $('#remark').val(),
      pic_non_default_remark    : $('#pic_non_default_remark ').val(),
      checklist_item : checkValues,
    },
    function(data) {
      var datax = jQuery.parseJSON(data);
      $('#doubleVisit').modal('hide');
      if(datax.status)
        { var grit_icon = "<?php echo base_url('assets/images/emotion_smile.png'); ?>"; }
      else
        { var grit_icon = "<?php echo base_url('assets/images/emotion_sad.png'); ?>"; }

      var n = noty({
        layout       : 'center',
        theme        : 'defaultTheme',
        type         : 'success',
        text         : datax.message,
        dismissQueue : true,
        modal        : true,
        timeout      : 5000,
        callback     : { 
          onShow: function()     { console.log('open'); },
          afterShow: function()  { console.log('opened'); },
          onClose: function()    { console.log('close'); },
          afterClose: function() { console.log('closed'); 
          window.location.href = "<?php echo base_url();?>pm/schedule";
          }//location.reload();}
        }
      });

      // $.gritter.add({
      //   title: 'Notice',
      //   text: datax.message,
      //   image: grit_icon,
      //   sticky: false,
      //   time: '1000',
      //   after_close: function()
      //     { location.reload(); }
      // });
    }
  ); 
}



function reload_page() {
  location.reload();
}

function hasdate() {
  if($('#calendar td > div.selected').length > 0) 
    { return true; }
  else { false; }
}

function resetall() {
  $('#submit-pm-schedule').addClass('disabled');
  $('input.pm-checklist-item').removeAttr('checked');
  $('#pm-priority button').removeClass('active');
  $('textarea#remark').val('');
  $('textarea#pic_non_default_remark ').val('');
  $('select#pm-visit-type').val('');
  $('#calendar td > div').removeClass('selected');
  window.date = false;
}
function checkDate () {
  if(!date) { 
    if(!$('#submit-pm-schedule').hasClass('disabled')) {
      $('#submit-pm-schedule').addClass('disabled');
    }
  } 
  else {
    $('#submit-pm-schedule').removeClass('disabled');
    $.post(
      "<?php echo base_url('pm/schedule/getSiteSchedule') ?>",
      { visit_date: year+'-'+month+'-'+date, site_id: $('input#siteID').val() },
      function(cfData) {
        var cfDatax = jQuery.parseJSON(cfData);
        if(cfDatax.pm.status) {

          $('input.pm-checklist-item').removeAttr('checked');
          $('#pm-priority button').removeClass('active');
          $('textarea#remark').val('');
          $('textarea#pic_non_default_remark').val('');
          $('select#pm-visit-type').val('');

          $.each(cfDatax.pm.head, function(key, val){
            // console.log(key+': '+val);
          })

          $('textarea#remark').val(cfDatax.pm.head.remark);
          $.each($('#pm-priority button'), function() {
            if($(this).html() == cfDatax.pm.head.priority) {
              $(this).addClass('active');
            }
          });
          $.each($('select#pm-visit-type option'),function(){
            if($(this).html() == cfDatax.pm.head.visit_type) {
              $(this).attr('selected', true);
            }
          });
          $.each(cfDatax.pm.list, function(key, val) {
            // console.log(val);
            var chxBox = $('input.pm-checklist-item');
            $.each(chxBox, function() {
              if($(this).val() == val) {
                $(this).attr('checked', true);
              }
            })
          }); count_item();
        } // end of preventive maintenace schedule

        if (cfDatax.si.status == true) {
          $('#cross-reff-schedule .widget-body').remove();
          $.each(cfDatax.si.head, function(k, v){
            var QeESLC = '<div class="widget-body"><h6 style="margin-bottom: 8px">'+v.site_code+' | '+v.site_name+'</h6>'+v.site_address+'<br><strong>NPI: </strong>'+v.user_id+' | '+v.user_name+'<br><strong>SPV: </strong>'+v.created_by+' | '+v.spv_name+'</div>';
            $('#cross-reff-schedule').append(QeESLC);
          });

          // console.log('Ada jadwal SI '+cfDatax.si.status);
        } else {
          $('#cross-reff-schedule .widget-body').remove();
          $('#cross-reff-schedule').append('<div class="widget-body">Tidak ada jadwal</div>');
        } // end of site inspection cross schedule
      }
    );
  }
}

  function showMap(m_lat, m_long) {
    // console.log(m_lat);
    // console.log(m_long);
  }
  
  document.getElementById('btn-custom').onclick = function() {
    if($('#pm-visit-type option:selected').attr('locked') != 1) {
      var items = document.getElementsByName('checklist_item');
      for (var i = 0; i < items.length; i++) {
        items[i].disabled = false;
      }
    }
    else {
      $('.pm-checklist-item').attr('disabled','disabled');
    }
    
    $('#btn-checkall').removeAttr('disabled');
    $('#btn-checkall').removeClass('disabled');

  }
  
  document.getElementById('btn-checkall').onclick = function() {
    // alert('EUREAKA !!!');
    var lock = $('#pm-visit-type option:selected').attr('locked');

    var cl = $('#accordion :checkbox'); // jumlah checklist (tercentang/tidak)
    var cc = $('#accordion :checkbox:checked'); // jumlah checklist yang dicentang
    if(lock != 1) {
      if(cl.length != cc.length) {
        $.each(cl, function function_name () {
          if($(this).attr('disabled') != 'disabled' ) {
            $(this).attr('checked', true);
          }
        })
      }
      else {
        $.each(cl, function function_name () {
          if($(this).attr('disabled') != 'disabled' ) {
            // $(this).attr('checked', true);
            $(this).removeAttr('checked');
          }
        })
      }
  
      count_item();
      group_number();
      
    }
  }

  document.getElementById('picID').onchange = function(){
  	var pic = this.options[this.selectedIndex].getAttribute('data-group');
  	if(pic != "default"){
  		document.getElementById('remind-reason').innerHTML = "Person you assign is not the site's PIC. Fill the column below to continue.";
  		$('#Reason').show();
  	}else{
  		document.getElementById('remind-reason').innerHTML = "";
  		$('#Reason').hide();
  	}
  }
  
</script>