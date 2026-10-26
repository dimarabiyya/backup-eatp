<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
select { width:auto; }
</style>

<div class="innerLR">
	
	<?php echo form_open('', 'id="frmSiteSeason" class="form-horizontal"') ?>
	
	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Site Season</h4>
			
			<a href="<?php echo site_url('/site_info/season') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
		
			<div class="control-group" id="form_season_name">
				<label class="control-label" for="seasonName">Season Name:</label>
				<div class="controls">
					<?php
					$options = array(0 => '- Choose one -');
					foreach ($fields as $field) {
						if ($field != 'site_id') {
							$options[$field] = ucwords(str_replace('_', ' ', $field));
						}
					}
					
					$site_code = $season['site_code'];
					
					unset($season['site_id']);
					unset($season['site_code']);
					
					$season_key = @array_keys($season);
					
					echo form_dropdown('seasonName',  $options, $season_key[0], 'id="seasonName"');
					?>
				</div>
			</div>
			
			<div class="control-group" id="form_season_value">
				<label class="control-label" for="seasonValue">Season Value:</label>
				<div class="controls"><?php echo form_input('seasonValue',  $season[$season_key[0]], 'id="seasonValue"') ?></div>
			</div>
			
			<div id="form_sites">
				<div class="control-group">
					<label class="control-label" for="site">Site:</label>
					<div class="controls">
						<?php if ($this->uri->segment(3) == 'edit') { ?>
						<span class="input-label"><?php echo $site_code ?></span>
						<?php } else { ?>
						<button type="button" class="btn" onclick="doLookupSite()">Choose Site</button>
						<?php } ?>
					</div>
				</div>
				
				<?php if ($this->uri->segment(3) == 'add') { ?>
				<div class="control-group">
					<div class="controls" style="width:200px; height:100px; overflow-y:scroll; border:1px solid #d8d9da;">
						<table id="sites-season" class="table table-striped"><tbody></tbody></table>
					</div>
				</div>
				<?php } ?>
			</div>
		
		</div>
	</div>
	
	<div class="form-actions">
		<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/season') ?>'"><i></i>Cancel</button>
	</div>
	
	<?php echo form_close() ?>
	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>

<script>
document.getElementById('frmSiteSeason').onsubmit = function() {

	var data = $(this).serialize();
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=add_site_season';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=edit_site_season';
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
					window.location.href = '<?php echo site_url('/site_info/season') ?>';
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

var doLookupSite = function() { // elem1: shown element, elem2: hidden element
	var url = '<?php echo site_url('/site_info/season') ?>';
	var sites = document.getElementsByName('sites[]');
	
	var e = [];
	for (var i = 0; i < sites.length; i++) {
		e.push(sites[i].value);
	}
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'get_lookup_sites', e: e.join(',') } }, // e: it used for selected element
		width: 600,
		scrolling: false,
		autoSize: true,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade',
		afterShow: function() {
			document.getElementById('btnDataSelectedOK').onclick = function() {
				var rows = $('#dg-sites').datagrid('getSelections');
				
				if (rows.length > 0) {
					for (var i = 0; i < rows.length; i++) {
						for (var j = 0; j < sites.length; j++) {console.log((sites[j].value +'=='+ rows[i].site_id));
							if (sites[j].value == rows[i].site_id) {
								removeSite(sites[j]);
							}
						}
						addSite(rows[i].site_id, rows[i].site_code);
					}
				}
				
				$.fancybox.close();
			};
		}
	});
};

var addSite = function(siteId, siteCode) {
	var s = document.getElementById('sites-season');
	
	s.children[0].innerHTML += '\
		<tr>\
			<td style="border-top:none">\
				' + siteCode + '\
				<button type="button" class="btn btn-mini btn-danger pull-right" onclick="removeSite(this)" title="Delete"><i class="icon-minus-sign icon-red"></i></button>\
				<input type="hidden" name="sites[]" value="' + siteId + '" />\
			</td>\
		</tr>\
	';
};

var removeSite = function(el) {
	var el = el.parentNode.parentNode;
	el.parentNode.removeChild(el);
};
</script>