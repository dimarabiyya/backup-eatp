<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/jquery.noty.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/layouts/center.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/themes/default.js"></script>
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('assets/js/number_format.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css"/>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/themes/default.css"/>
<link href="<?php echo base_url('assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>


<div class="innerLR">
  <div class="row-fluid">
    <div class="span12">
		<div class="widget">
			<div class="widget-head heading-outer">
				<h4 class="heading glyphicons list"><i></i> <?php echo $title;?></h4>
			</div>

			<div class="widget-body">
				<form class="form-horizontal" method="post" id="po_form">
					<div class="row-fluid">
						<div class="span12">
							<div class="control-group">
								<input type="hidden" value="<?php echo $po_id?>" name="po_id" />
								<label class="control-label" for="poNo">PO No.</label>
								<div class="controls">
									<input type="text" id="poNo" class="input-medium" name="po_no" />
								</div>
								<div class="separator bottom"></div>
							</div>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span12">
							<div class="control-group">
								<label class="control-label" for="poDate">PO Date</label>
								<div class="controls">
									<div class="input-append">
										<input type="text" id="poDate" class="input-small date" placeholder="DD-MMM-YYYY" name="po_date" />
										<span class="add-on"><i class="icon-calendar"></i></span>
									</div>
								</div>
								<div class="separator bottom"></div>
							</div>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span12">
							<div class="control-group">
								<label class="control-label" for="region">Region</label>
								<div class="controls">
									 <?php $js = 'id="region" onChange=""'; ?>
		                        	<?php echo form_dropdown('region', $region, '', $js); ?>
								</div>
								<div class="separator bottom"></div>
							</div>
						</div>
					</div>

					<div class="row-fluid" style="margin-bottom: 15px;">
						<div class="span12">
							<div class="pull-right">
								<button type="button" class="btn btn-default" onclick="addOption()"><i class="icon-plus-sign"></i>&nbsp;&nbsp;Add PO LINK</button>

							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<table id="tbl-po_link" class="table table-bordered" style="width:100%">
				              <thead>
				                <tr>
				                  <th style="width:30px" class="center">&nbsp;</th>
				                  <th style="width:100px">SITE NE <span class="text-error">*</span></th>
				                  <th style="width:100px">SITE FE <span class="text-error">*</span></th>
				                  <th style="width:100px">Item <span class="text-error">*</span></th>
				                  <th style="width:100px">Order Type <span class="text-error">*</span></th>
				                  <th class="center">EWO/NBWO NO. <span class="text-error">*</span></th>
				                  <th class="center">Type</th>
				                  <th style="width:300px">Description <span class="text-error">*</span></th>
				                </tr>
				              </thead>
				              <tbody id="tbl-user-body">
				              <tr class="row-1">
				              	<td >
				              		<a class="btn btn-danger btn-small btn-remove-row" id="1" href="javascript:void(0)" title="Remove row" onClick="removeRow(this)">
				              			<i class="icon-minus-sign icon-white"></i>
			              			</a>
				              	</td>
				              	<td>
				              		<button class="btn btn-default" type="button" onclick="lookUpSite('site_ne1',1)">Site NE&nbsp;<i class="icon-search"></i></button><i id="txt_site_ne1"></i>
				              		<input type="hidden" id="site_ne1" name="sitene[]"/>
				              	</td>
				              	<td>
				              		<button class="btn btn-default" type="button" onclick="lookUpSite('site_fe1',1)">Site FE&nbsp;<i class="icon-search"></i></button><i id="txt_site_fe1"></i>
				              		<input type="hidden" id="site_fe1" name="sitefe[]"/>
				              	</td>
				              	<td >
				              		<input  style="width:100px" type="number" name="itemtype[]" />
				              	</td>
				              	<td>
				              		<select style="width:150px" name="order_type[]">
				              			<option>- Choose -</option>
				              			<option value="1">NBWO</option>
				              			<option value="2">EWO</option>
				              		</select>
				              	</td>
				              	<td>
				              		<!-- <input style="width:100px" type="number" name="ewo[]"> -->
				              		<input style="width:150px" type="text" name="ewo[]">
				              	</td>
				              	<td>
				              		<input style="width:150px" type="text" name="type[]">
				              	</td>
				              	<td>
				              		<textarea style="width: 94%;"  name="description[]"  placeholder="Fill Description" ></textarea>
				              	</td>
				              </tr>

				              </tbody>
				            </table>
						</div>
					</div>

					<div class="form-actions">
						<button class="btn btn-primary btn-icon glyphicons circle_ok" type="button" onclick="saveForm()"><i></i> Save</button>
						<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo base_url('/po')?>'"><i></i>Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
<script>
  dateFormat.init();
</script>
<script>
var current_add = 1;
	function addOption(){
		current_add ++;
		var content = '<tr class="row-'+current_add+'">'+
		              	'<td >'+
		              		'<a class="btn btn-danger btn-small btn-remove-row" id="'+current_add+'" href="javascript:void(0)" onClick="removeRow(this)" title="Remove row">'+
		              			'<i class="icon-minus-sign icon-white"></i>'+
	              			'</a>'+
		              	'</td>'+
		              	'<td>'+
			              		'<button class="btn btn-default" type="button" onclick="lookUpSite(\'site_ne'+current_add+'\','+current_add+')">Site NE&nbsp;<i class="icon-search"></i></button><i id="txt_site_ne'+current_add+'"></i>'+
				              		'<input type="hidden" id="site_ne'+current_add+'" name="sitene[]"/>'+
				              	'</td>'+
				              	'<td>'+
				              		'<button class="btn btn-default" type="button" onclick="lookUpSite(\'site_fe'+current_add+'\','+current_add+')">Site FE&nbsp;<i class="icon-search"></i></button><i id="txt_site_fe'+current_add+'"></i>'+
				              		'<input type="hidden" id="site_fe'+current_add+'" name="sitefe[]"/>'+
				              	'</td>'+
		              	'<td>'+
		              		'<input  style="width:100px" type="number" name="itemtype[]" />'+
		              	'</td>'+
		              	'<td>'+
		              		'<select style="width:150px" name="order_type[]">'+
		              			'<option>- Choose -</option>'+
		              			'<option value="1">NBWO</option>'+
		              			'<option value="2">EWO</option>'+
		              		'</select>'+
		              	'</td>'+
		              	'<td>'+
		              		'<input style="width:100px" type="number" name="ewo[]">'+
		              	'</td>'+
		              	'<td>'+
		              		'<input style="width:150px" type="text" name="type[]">'+
		              	'</td>'+
		              	'<td>'+
		              		'<textarea style="width: 94%;"  name="description[]"  placeholder="Fill Description" ></textarea>'+
		              	'</td>'+
		              '</tr>';

		$("#tbl-po_link tbody").append(content);
	}

	function removeRow(param){
		var row_selected = $(param).attr("id");
		console.log("test remove "+row_selected);
		$(".row-"+row_selected).remove();
	}

</script>

<script>

  /*Function to Show Site Link*/
  function lookUpSite(id,no){
  	 var region_selected = $("#region").val();

     var url = "<?php echo site_url('/po/lookup_site') ?>";
      $.fancybox({
        href: url,
        type: 'ajax',
        ajax: { data: { cmd: 'getData',region:region_selected } }, // e: it used for selected element
        width: 600,
        scrolling: false,
        autoSize: true,
        fitToView: true,
        closeClick: false,
        openEffect: 'fade',
        closeEffect: 'fade',
        afterShow: function() {
                        $('.fancybox-wrap').draggable({handle:".widget-head"});

          				document.getElementById('btnDataSelectedOK').onclick = function() {
                        var row = $('#gridSite').datagrid('getSelected');
                        if (row != null) {
                          console.log("link :"+row.site_id+"For "+id);
                          $("#"+id).val(row.site_id);
                          $("#txt_"+id).html(row.site_id);
                          var current_site_ne = $("#site_ne"+no).val();
                          var current_site_fe = $("#site_fe"+no).val();
                          if(current_site_ne == current_site_fe){
								$("#"+id).empty();
                          		$("#txt_"+id).empty();
                          		var message = "Oops, You choose same site in NE and FE, choose others";
                          		coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
                          }
                        }

                        $.fancybox.close();
                    };
        }
      });
  }
</script>
<script>
function saveForm(){
	/*Progress Loading*/
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

	var form_url = "<?php echo $form_url;?>";
	var sites_ne = [];
	var sites_fe = [];
	var sites_itemtype = [];
	var sites_order_type = [];
	var sites_ewo = [];
	var sites_type = [];
	var sites_description = [];

	$.each(($('input[name^=sitene]', '#po_form')),function function_name(){
        var item_val =  $(this).val();
        sites_ne.push(item_val);
    });
    $.each(($('input[name^=sitefe]', '#po_form')),function function_name(){
        var item_val =  $(this).val();
        sites_fe.push(item_val);
    });
    $.each(($('input[name^=itemtype]', '#po_form')),function function_name(){
        var item_val =  $(this).val();
        sites_itemtype.push(item_val);
    });
    $.each(($('select[name^=order_type]', '#po_form')),function function_name(){
        var item_val =  $(this).val();
        console.log("order type :"+item_val);
        sites_order_type.push(item_val);
    });
    $.each(($('input[name^=ewo]', '#po_form')),function function_name(){
        var item_val =  $(this).val();
        sites_ewo.push(item_val);
    });
    $.each(($('input[name^=type]', '#po_form')),function function_name(){
        var item_val =  $(this).val();
        sites_type.push(item_val);
    });
    $.each(($('textarea[name^=description]', '#po_form')),function function_name(){
        var item_val =  $(this).val();
        console.log("dsc :"+item_val);
        sites_description.push(item_val);
    });

	$.post(form_url,{
		po_no:$("#poNo").val(),
		po_date:$("#poDate").val(),
		region:$("#region").val(),
		sitene:sites_ne,
		sitefe:sites_fe,
		itemtype:sites_itemtype,
		order_type:sites_order_type,
		type:sites_type,
		ewo:sites_ewo,
		description:sites_description,
	},function(data){
		var obj_res = jQuery.parseJSON(data);
		if(obj_res.status == "success"){
            var base_url = "<?php echo base_url("/po")?>";
            coolNotification('<ul style="text-align:left">' + obj_res.message + '</ul>', 'success');
            window.location.href = base_url;
        }else{
            coolNotification('<ul style="text-align:left">' + obj_res.message + '</ul>', 'error');
        }
        $.unblockUI();
	});
}
</script>
