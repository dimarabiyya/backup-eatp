<div class="heading-buttons">
	<?php if ($action == 'add') { ?>
	<h3>Add <?php echo lang('heading_title:'.$key) ?> <span>| <?php echo lang('heading_title:'.$key) ? lang('heading_title:'.$key) : 'Unknown title' ?></span></h3>
	<?php } elseif ($action == 'edit') { ?>
	<h3>Edit <?php echo lang('heading_title:'.$key) ?> <span>| <?php echo lang('heading_title:'.$key) ? lang('heading_title:'.$key) : 'Unknown title' ?></span></h3>
	<?php } ?>
	
	<div class="buttons pull-right">
		<a href="<?php echo $list_url ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>

<div class="innerLR">
	
	<?php echo form_open('', 'id="frmInput" class="form-horizontal"') ?>
	
	<div class="widget">	
		
		<div class="widget-head heading-outer"">
			<h4 class="heading"><i></i> <?php echo lang('heading_data_title:'.$key) ? lang('heading_data_title:'.$key) : 'Unknown title' ?></h4>
		</div>
		
		<div class="widget-body">
		
			<div class="control-group">
				<label type="hidden" class="control-label" for="name">Name:</label>
				<div class="controls">
					<?php echo @form_input('name', set_value('name', $reference['lookup_desc']), 'id="name" autofocus="true"') ?>
				</div>
				<div class="separator bottom"></div>
			</div>
			
			
					<?php echo @form_hidden('value', set_value('value', $reference['lookup_value'])) ?>
				
			
			<div class="control-group">
				<label class="control-label" for="order">Order:</label>
				<div class="controls">
					<?php echo @form_dropdown('order', $order_options, set_value('order', $reference['order']), 'id="order" class="input-mini"') ?>
				</div>
				<div class="separator bottom"></div>
			</div>
			
			<div class="separator top"></div>
		
		</div>
		
	</div>
	
	<div class="form-actions" style="padding:19px 0px 20px 20px">
		<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/preventive/reference/index/'.$key) ?>'"><i></i>Cancel</button>
	</div>

	<?php echo form_close() ?>

</div>

<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script>
var ord = document.getElementById('order');
var ordFrom = ord.options[ord.selectedIndex].value != 0 ? (ord.options[ord.selectedIndex].value - 1) : 0;

document.getElementById('frmInput').onsubmit = function() {

	var ordTo = (ord.options[ord.selectedIndex].value - 1);
	var order = [];
	<?php
	$i = 0;
	foreach ($current_order as $ord) {
	?>
		order[<?php echo $i ?>] = <?php echo $ord ?>;
	<?php
		$i++;
	}
	?>
	
	var arrOrder = arrayMove(order, ordFrom, ordTo);
	var data = $(this).serialize();
	for (var i = 0; i < arrOrder.length; i++) {
		data += '&myorder[]=' + arrOrder[i];
	}
	
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
			
			if (typeof json['error'] != 'undefined' && json['error']) {
				var message = '\
					<ul style="text-align:left">';
					for (var i = 0; i < json['message'].length; i++) {
						message += '<li>' + json['message'][i] + '</li>';
					}
					message += '</ul>';
				
				coolNotification(message, 'error');
			} else {
				coolNotification(json['message'], 'success', function() {
					window.location.href = '<?php echo $list_url ?>';
				});
			}
			
			window.scroll(0, 0);
		},
		complete: function() {
			siteOverlay.remove();
		}
	});
	
	return false;
};

var arrayMove = function(arr, fromIndex, toIndex) {
    var element = arr[fromIndex];
    arr.splice(fromIndex, 1);
    arr.splice(toIndex, 0, element);
	return arr;
}

//TEXT.clearAutoComplete();
justNumberFormat.init();
siteInfoMenu();
</script>