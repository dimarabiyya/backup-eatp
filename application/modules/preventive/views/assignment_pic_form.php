<?php error_reporting(E_ALL ^ E_NOTICE) ?>
<div class="innerLR">

	<?php echo form_open('', 'id="frmAssignmentPic" class="form-horizontal"') ?>
	
	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Assignment PIC Site</h4>
			
			<a href="<?php echo site_url('/preventive/assignment_pic') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
	
			<div class="control-group">
				<label class="control-label" for="pic">PIC:</label>
				<div class="controls">
					<div class="input-append">
						<?php if ($this->uri->segment(3) == 'edit') { ?>
						<?php echo form_input('pic', $assignment['user_name'], 'id="pic" class="input-medium uneditable" readonly="readonly"') ?>
						<?php echo form_hidden('picId', $assignment['user_id']) ?>
						<?php } else { ?>
						<?php echo form_input('pic', '', 'id="pic" class="input-medium uneditable" onfocus="doLookupAssignmentPIC(\'pic\', \'picId\')"') ?>
						<?php echo form_hidden('picId', '') ?>
						<button type="button" class="btn" onclick="doLookupAssignmentPIC('pic', 'picId')">...</button>
						<?php } ?>
					</div>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="site">Site:</label>
				<div class="controls">
                                    <button type="button" class="btn" onclick="doLookupSite()">Choose Site</button>
                                    <button type="button" class="btn" onclick="removeAllSite()">Remove All Site</button>
				</div>
			</div>
			
			<div class="control-group">
				<div class="controls" style="width:800px; height:400px; overflow-y:scroll; border:1px solid #d8d9da;">
					<?php if ($this->uri->segment(3) == 'edit' && count($assignment['sites']) > 0) { ?>
					<table id="sites-pic" class="table table-striped">
						
<thead><th>Site ID</th><th>Site ID Ref.</th><th>Vendor ID</th><th>Site Name </th><th>Opr. Cluster</th><th>Region</th></thead>

<tbody>
							<?php foreach ($assignment['sites'] as $s) { ?>
							<tr id="<?php echo $s['site_id'] ?>">
								<td style="border-top:none">
									<?php echo $s['site_code'] ?>
								</td>
								<td style="border-top:none">
									<?php echo $s['site_code_old'] ?>
								</td>
								<td style="border-top:none">
									<?php echo $s['vendor_id'] ?>
								</td>
								<td style="border-top:none">
									<?php echo $s['site_name'] ?>
								</td>
								<td style="border-top:none">
									<?php echo $s['cluster_name'] ?>
								</td>
								<td style="border-top:none">
									<?php echo $s['region_name'] ?>
								</td>
								<td style="border-top:none">
									<button type="button" class="btn btn-mini btn-danger pull-right" onclick="removeSite(this)" title="Delete"><i class="icon-minus-sign icon-red"></i></button>
									<input type="hidden" name="sites[]" value="<?php echo $this->core->encode($s['site_id']) ?>" />
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php } else { ?>
					<table id="sites-pic" class="table table-striped">
						
<thead><th>Site ID</th><th>Site ID Ref.</th><th>Vendor ID</th><th>Site Name </th><th>Opr. Cluster</th><th>Region</th></thead>						

<tbody></tbody></table>
					<?php } ?>
				</div>
			</div>
		
		</div>
	</div>
	
	<div class="form-actions">
		<button id="btnSave" class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/preventive/assignment_pic') ?>'"><i></i>Cancel</button>
	</div>
		
	<?php echo form_close() ?>
	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script>
document.getElementById('frmAssignmentPic').onsubmit = function() {

	var data = $(this).serialize();
	//console.log(data);
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=add_assignment_pic';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=edit_assignment_pic';
	<?php } ?>
	
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
					window.location.href = '<?php echo site_url('/preventive/assignment_pic') ?>';
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

var doLookupAssignmentPIC = function(elem1, elem2) { // elem1: shown element, elem2: hidden element
	
	$('#sites-pic tbody tr').remove();
	var url = '<?php echo site_url('/preventive/assignment_pic') ?>';

	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'get_lookup_users', e: elem2 } }, // e: it used for selected element
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
                        var row = $('#dg-users').datagrid('getSelected');

                        if (row != null) {
                            document.getElementsByName(elem1)[0].value = row.user_name;
                            document.getElementsByName(elem2)[0].value = row.user_id;
                        }
                        autoLookupSite(row.user_id);
                        $.fancybox.close();
                    };
		}
	});
};

var doLookupSite = function() { // elem1: shown element, elem2: hidden element
	var url = '<?php echo site_url('/preventive/assignment_pic') ?>';
	var sites = document.getElementsByName('sites[]');
	var user_id = $('input[type=hidden][name=picId]').val();
	var e = [];
	for (var i = 0; i < sites.length; i++) {
		e.push(sites[i].value);
	}
	//console.log(e);
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'get_lookup_sites', e: e.join(','), user_id: user_id } }, // e: it used for selected element
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
				var rows = $('#dg-sites').datagrid('getSelections');
				if (rows.length > 0) {
					for (var i = 0; i < rows.length; i++) {
						for (var j = 0; j < sites.length; j++) {
							if (sites[j].value == rows[i].site_id) {
								removeSite(sites[j]);
							}
						}
				
						addSite(rows[i]);

					}
				}
				
				$.fancybox.close();
			};
		}
	});
};

var addSite = function(row) {
//console.log(row);
					var siteId = row.site_id;
					var siteCode = row.site_code;
					var siteCodeOld = row.site_code_old;
					var vendorId = row.vendor_id;
					var siteName = row.site_name;
					// var tpName = row.tp_name;
					var regionName = row.region_name;
					if(row.cluster_name != null){
						var clusterName = row.cluster_name;
					}else{
						var clusterName = '';
					}
					

	var s = document.getElementById('sites-pic');
	var d = $('#sites-pic').children().find('tr');
	//var d = $('#sites-pic').children().find('tbody tr');
	//console.log(d.length);
	if(d.length == 1 ) {
			s.children[1].innerHTML += '\
				<tr id="'+siteId+'">\
					<td style="border-top:none">\
					' + siteCode+ '\
					</td>\
						<td style="border-top:none">\
					' + siteCodeOld+ '\
					</td>\
					<td style="border-top:none">\
					' + vendorId+ '\
					</td>\
						<td style="border-top:none">\
					' + siteName+ '\
					</td>\
					<td style="border-top:none">\
					' + clusterName+ '\
					</td>\
					<td style="border-top:none">\
					' + regionName+ '\
					</td>\
						<td style="border-top:none">\
						<button type="button" class="btn btn-mini btn-danger pull-right" onclick="removeSite(this)" title="Delete"><i class="icon-minus-sign icon-red"></i></button>\
						<input type="hidden" name="sites[]" value="' + siteId + '" />\
					</td>\
				</tr>\
			';
	}
	else {
		
		var addnode = true;
		//console.log(d);
		$.each(d, function(){
			var iD = $(this).attr('id');
			//if(iD == siteCode) {
			if(iD == siteId) {
				return addnode = false;
			}
		});
		if(addnode) {
			s.children[1].innerHTML += '\
				<tr id="'+siteId+'">\
						<td style="border-top:none">\
					' + siteCode+ '\
					</td>\
						<td style="border-top:none">\
					' + siteCodeOld+ '\
					</td>\
					<td style="border-top:none">\
					' + vendorId+ '\
					</td>\
						<td style="border-top:none">\
					' + siteName+ '\
					</td>\
					<td style="border-top:none">\
					' + clusterName+ '\
					</td>\
						<td style="border-top:none">\
					' + regionName+ '\
					</td>\
						<td style="border-top:none">\
						<button type="button" class="btn btn-mini btn-danger pull-right" onclick="removeSite(this)" title="Delete"><i class="icon-minus-sign icon-red"></i></button>\
						<input type="hidden" name="sites[]" value="' + siteId + '" />\
					</td>\
				</tr>\
			';
		}
	}

};

var removeSite = function(el) {
	var el = el.parentNode.parentNode;
	el.parentNode.removeChild(el);
	//console.log(el);
};

function removeAllSite () {
  $('#sites-pic > tbody >tr').remove();
}

TEXT.uneditable();

function autoLookupSite (user_id) {
	$.post(
		'<?php echo site_url('/preventive/assignment_pic/get_sites') ?>',
		{user_id: user_id},
		function(data){
			var dx = jQuery.parseJSON(data);
			$.each(dx, function(k,v){
				//console.log(v);
				addSite(v)
			});
		}
	)
}

function remove_site(site_id, site_code) {
	//$('#sites-pic tbody tr#'+site_code).remove();
	$('#sites-pic tbody tr#'+site_id).remove();

}
</script>