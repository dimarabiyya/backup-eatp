<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="innerLR">

	<?php echo form_open('', 'id="frmAssignmentClusterOps" class="form-horizontal"') ?>
	
	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Assignment SSH - Cluster</h4>
			
			<a href="<?php echo site_url('/preventive/assignment_ssh') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
		
			<div class="control-group">
				<label class="control-label" for="pic">SSH:</label>
				<div class="controls">
					<div class="input-append">
						<?php if ($this->uri->segment(3) == 'edit') { ?>
						<?php echo form_input('ssh', $assignment['user_name'], 'id="ssh" class="input-medium uneditable" readonly="readonly"') ?>
						<?php echo form_hidden('sshId', $assignment['user_id']) ?>						
						<?php } else { ?>
						<?php echo form_input('ssh', '', 'id="ssh" class="input-medium uneditable" onfocus="doLookupAssignmentSSH(\'ssh\', \'sshId\')"') ?>
						<?php echo form_hidden('sshId', '') ?>
						<button type="button" class="btn" onclick="doLookupAssignmentSSH('ssh', 'sshId')">...</button>
						<?php } ?>
					</div>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="site">Cluster(s):</label>
				<div class="controls">
					<button type="button" class="btn" onclick="doLookupClusterOps()">Choose Cluster(s)</button>
				</div>
			</div>
			
			<div class="control-group">
				<div class="controls" style="width:400px; height:400px; overflow-y:scroll; border:1px solid #d8d9da;">
					<?php if ($this->uri->segment(3) == 'edit' && count($assignment['clusters']) > 0) { ?>
					<table id="cluster-ops" class="table table-striped">
						<thead><th>Region Name </th><th>Cluster Name</th></thead>
						<tbody>
							<?php foreach ($assignment['clusters'] as $s) { ?>
							<tr id="<?php echo $this->core->encode($s['cluster_ops_id']) ?>">
								<td style="border-top:none">
								<?php echo $s['region_name'] ?>
								</td>
								<td style="border-top:none">
									<?php echo $s['cluster_name'] ?>
								</td>
								<td style="border-top:none">
									<button type="button" class="btn btn-mini btn-danger pull-right" onclick="removeCluster(this)" title="Delete"><i class="icon-minus-sign icon-red"></i></button>
									<input type="hidden" name="clusterOps[]" value="<?php echo $this->core->encode($s['cluster_ops_id']) ?>" />
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php } else { ?>
					<table id="cluster-ops" class="table table-striped">
					<thead><th>Region Name </th><th>Cluster Name</th></thead>		
				<tbody></tbody>
				</table>
					<?php } ?>
				</div>
			</div>
		
		</div>
	</div>
	
	<div class="form-actions">
		<button id="btnSave" class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/preventive/assignment_ssh') ?>'"><i></i>Cancel</button>
	</div>
		
	<?php echo form_close() ?>
	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script>
document.getElementById('frmAssignmentClusterOps').onsubmit = function() {

	var data = $(this).serialize();
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=add_assignment_ssh';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=edit_assignment_ssh';
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
					window.location.href = '<?php echo site_url('/preventive/assignment_ssh') ?>';
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

var doLookupAssignmentSSH = function(elem1, elem2) { // elem1: shown element, elem2: hidden element
	
	$('#cluster-ops tbody tr').remove();
	var url = '<?php echo site_url('/preventive/assignment_ssh') ?>';
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'get_lookup_ssh', e: elem2 } }, // e: it used for selected element
		width: 900,
		scrolling: false,
		autoSize: true,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade',
		afterShow: function() {
			$('.fancybox-wrap').draggable({handle:".widget-head"});
			document.getElementById('btnDataSelectedOK').onclick=function() {
				var row = $('#dg-ssh').datagrid('getSelected');
				
				if (row != null) {
					document.getElementsByName(elem1)[0].value = row.user_name;
					document.getElementsByName(elem2)[0].value = row.user_id;
				}
				autoLookupCluster(row.user_id);
				$.fancybox.close();
			};
		}
	});
};

var doLookupClusterOps = function() { // elem1: shown element, elem2: hidden element
	
	var url = '<?php echo site_url('/preventive/assignment_ssh') ?>';
	var clusterOps = document.getElementsByName('clusterOps[]');
	var user_id = $('input[type=hidden][name=sshId]').val();
	var e = [];
	for (var i = 0; i < clusterOps.length; i++) {
		e.push(clusterOps[i].value);
	}
	//console.log(e);
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'get_lookup_cluster_ops', e: e.join(','), user_id :user_id } }, // e: it used for selected element
		width: 700,
		scrolling: false,
		autoSize: true,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade',
		afterShow: function() {
			$('.fancybox-wrap').draggable({handle:".widget-head"});
			document.getElementById('btnDataSelectedOK').onclick = function() {
				var rows = $('#dg-cluster-ops').datagrid('getSelections');
				//console.log(rows.length);
				if (rows.length > 0) {
					for (var i = 0; i < rows.length; i++) {
						for (var j = 0; j < clusterOps.length; j++) {
							if (clusterOps[j].value == rows[i].cluster_ops_id) {
								removeCluster(clusterOps[j]);
							}
						}
						//addCluster(rows[i].cluster_ops_id, rows[i].cluster_name);
						addCluster(rows[i].cluster_ops_id, rows[i].cluster_name, rows[i].region_name);

					}
				}
				
				$.fancybox.close();
			};
		}
	});
};

/*
var addCluster = function(clusterId, clusterName) {
	var s = document.getElementById('cluster-ops');
	
	s.children[0].innerHTML += '\
		<tr id="'+clusterId+'">\
			<td style="border-top:none">\
				' + clusterName + '\
				<button type="button" class="btn btn-mini btn-danger pull-right" onclick="removeCluster(this)" title="Delete"><i class="icon-minus-sign icon-red"></i></button>\
				<input type="hidden" name="clusterOps[]" value="' + clusterId + '" />\
			</td>\
		</tr>\
	';
};
*/

var addCluster = function(clusterId, clusterName, regionName) {
	var s = document.getElementById('cluster-ops');
	var d = $('#cluster-ops').children().find('tr');
	//var d = $('#sites-pic').children().find('tbody tr');
	//console.log(d.length);
	
	if(d.length == 1 ) {
	s.children[1].innerHTML += '\
		<tr id="'+clusterId+'">\
			<td style="border-top:none">\
				' + clusterName + '\
			</td>\
			<td style="border-top:none">\
				' + regionName + '\
			</td>\
			<td style="border-top:none">\
				<button type="button" class="btn btn-mini btn-danger pull-right" onclick="removeCluster(this)" title="Delete"><i class="icon-minus-sign icon-red"></i></button>\
				<input type="hidden" name="clusterOps[]" value="' + clusterId + '" />\
			</td>\
		</tr>\
	';
	} else {
		var addnode = true;
		$.each(d, function(){
			var iD = $(this).attr('id');
			if(iD == clusterId) {
				return addnode = false;
			}
		});
		if(addnode) {
	s.children[1].innerHTML += '\
		<tr id="'+clusterId+'">\
			<td style="border-top:none">\
				' + clusterName + '\
			</td>\
			<td style="border-top:none">\
				' + regionName + '\
			</td>\
			<td style="border-top:none">\
				<button type="button" class="btn btn-mini btn-danger pull-right" onclick="removeCluster(this)" title="Delete"><i class="icon-minus-sign icon-red"></i></button>\
				<input type="hidden" name="clusterOps[]" value="' + clusterId + '" />\
			</td>\
		</tr>\
	';
		}
	}
};

var removeCluster = function(el) {
	var el = el.parentNode.parentNode;
	el.parentNode.removeChild(el);
};

TEXT.uneditable();

function autoLookupCluster(user_id) {
	$.post(
		'<?php echo site_url('/preventive/assignment_ssh/get_clusters') ?>',
		{user_id: user_id},
		function(data){
			var dx = jQuery.parseJSON(data);
			$.each(dx, function(k,v){
				//console.log(v);
				addCluster(v.cluster_ops_id, v.cluster_name, v.region_name)
			});
		}
	);
}

function remove_cluster(cluster_id, cluster_code) {
	$('#cluster-ops tbody tr#'+cluster_id).remove();
}
</script>