<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
select { width:auto; }
</style>

<!--
<div class="heading-buttons">
	<?php if ($this->uri->segment(3) == 'add') { ?>
	<h3>Add <span>| Tower Provider</span></h3>
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	<h3>Edit <span>| Tower Provider</span></h3>
	<?php } ?>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/tower_provider') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">

	<?php echo form_open('', 'id="frmTP" class="form-horizontal"') ?>
	
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Tower Provider Data</h4>
			
			<a href="<?php echo site_url('/site_info/tower_provider') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
		
			<div class="control-group" id="form_tower_site_category">
				<label class="control-label" for="siteCategory">Site Category:</label>
				<div class="controls">
					<?php
					$lookup = $this->core->get_lookup('tp_category');
					$options = array(0 => '- Choose one -');
					foreach ($lookup as $lo) {
						$options[$lo['id']] = $lo['desc'];
					}
					echo @form_dropdown('siteCategory', $options, $tp['site_category'], 'id="siteCategory"');
					?>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span4">
					<div class="control-group" id="form_tp_name">
						<label class="control-label" for="tpName">TP Name:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_rows(array('company_id', 'company_name'), 't_mtr_tp_company', NULL, 'company_name');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['company_id']] = $lo['company_name'];
							}
							echo @form_dropdown('tpName', $options, $tp['company_id'], 'id="tpName"');
							?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span8">
					<div class="control-group" id="form_tp_site_name">
						<label class="control-label" for="tpSiteName">TP Site Name:</label>
						<div class="controls">
							<?php echo @form_input('tpSiteName', $tp['tp_site_name'], 'id="tpSiteName"') ?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span4">
					<div class="control-group" id="form_tp_code">
						<label class="control-label" for="tpCode">TP ID:</label>
						<div class="controls">
							<?php echo @form_input('tpCode', $tp['tp_code'], 'id="tpCode" class="uppercase"') ?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span8">
					<div class="control-group" id="form_latitude">
						<label class="control-label" for="latitude">Lat - Long:</label>
						<div class="controls">
							<div class="input-append">
								<?php echo @form_input('latitude', $tp['latitude'], 'id="latitude" class="input-small latlng-number"') ?>
								<?php echo @form_input('longitude', $tp['longitude'], 'id="longitude" class="input-small latlng-number"') ?>
								<a href="javascript:void(0)" class="btn" onclick="return showMap()">Map</a>
							</div>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span4">
					<div class="control-group" id="form_status_tp">
						<label class="control-label" for="statusTP">Status TP:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup('tower_status');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('statusTP', $options, $tp['status_tp_id'], 'id="statusTP"');
							?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span8">
					<div class="control-group" id="form_site_type">
						<label class="control-label" for="siteType">Site Type:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup('site_type');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('siteType', $options, $tp['site_type_id'], 'id="siteType"');
							?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group" id="form_tower_type">
						<label class="control-label" for="towerType">Tower Type:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup('site_type');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('towerType', $options, $tp['tower_type_id'], 'id="towerType"');
							?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_building_height">
				<label class="control-label" for="buildingHeight">Building Height:</label>
				<div class="controls">
					<div class="input-append">
						<?php echo @form_input('buildingHeight', $tp['building_height_mtr'], 'id="buildingHeight" class="input-small number"') ?>
						<span class="add-on">M</span>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_tower_heigth">
				<label class="control-label" for="towerHeight">Tower Height:</label>
				<div class="controls">
					<div class="input-append">
						<?php echo @form_input('towerHeight', $tp['tower_height_mtr'], 'id="towerHeight" class="input-small number"') ?>
						<span class="add-on">M</span>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span4">
					<div class="control-group" id="form_propinsi">
						<label class="control-label" for="propinsi">Province:</label>
						<div class="controls">
							<?php
							$propinsi = array('0' => '- Choose one -');
							$dataPropinsi = $this->core->view_all('t_mtr_lokasi', array('jenis' => 'P'), 'nama');
							foreach ($dataPropinsi as $prop) {
								$propinsi[$prop['lokasi_id']] = $prop['nama'];
							}
							echo @form_dropdown('propinsi', $propinsi, set_value('propinsi', $tp['propinsi_id']), 'id="propinsi"');
							?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span8">
					<div class="control-group" id="form_city">
						<label class="control-label" for="city">City:</label>
						<div class="controls">
							<?php
							$kabupaten = array('0' => '- Choose one -');
							$dataKabupaten = @$this->core->view_all('t_mtr_lokasi', array('jenis' => 'KB', 'parent_id' => $tp['propinsi_id']), 'nama');
							foreach ($dataKabupaten as $kab) {
								$kabupaten[$kab['lokasi_id']] = $kab['nama'];
							}
							echo @form_dropdown('city', $kabupaten, $tp['city_id'], 'id="city"');
							?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_address">
				<label class="control-label" for="address">Address:</label>
				<div class="controls">
					<?php echo @form_textarea('address', $tp['address'], 'id="address" style="width:206px; height:50px;"') ?>
				</div>
			</div>
			
			<div class="row-fluid">
				<!--
				<div class="span4">
					<div class="control-group" id="form_region">
						<label class="control-label" for="region">Region:</label>
						<div class="controls">
							<?php
							$regions = array(0 => '- Choose one -');
							$dataRegions = $this->core->view_all('t_mtr_region', array('region_id !=' => 0), 'region_name');
							foreach ($dataRegions as $region) {
								$regions[$region['region_id']] = $region['region_name'];
							}
							echo @form_dropdown('region', $regions, $tp['region_id'], 'id="region"');
							?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				-->
				
				<div class="span12">
					<div class="control-group" id="form_sharable_status">
						<label class="control-label" for="shareableStatus">Shareable Status:</label>
						<div class="controls">
							<?php echo @form_input('shareableStatus', $tp['shareable_status'], 'id="shareableStatus"') ?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<!--
				<div class="span4">
					<div class="control-group" id="form_site_code">
						<label class="control-label" for="siteCode">Site Code:</label>
						<div class="controls">
							<?php echo @form_input('siteCode', $tp['site_code'], 'id="siteCode"') ?>
						</div>
					</div>
				</div>
				-->
				<!--
				<div class="span12">
					<div class="control-group" id="form_cme_partner">
						<label class="control-label" for="cmePartner">Partner:</label>
						<div class="controls">                            
							<?php
							//$dataCmePartner = get_instance()->core->view_all('t_mtr_partner', array('status' => 1), 'partner_name');
							//$cmePartner = array(0 => '- Choose one -');
							//foreach ($dataCmePartner as $partner) {
							//	$cmePartner[$partner['partner_id']] = $partner['partner_name'];
							//}
							//echo @form_dropdown('partner', $cmePartner, $tp['partner_id'], 'id="partner"');
							?>
						</div>
					</div>
				</div>
				-->
			</div>
			
			<div class="control-group" id="form_remarks">
				<label class="control-label" for="remarks">Remarks:</label>
				<div class="controls">
					<?php echo @form_textarea('remarks', $tp['remark'], 'id="remarks" class="span4" style="height:50px;"') ?>
				</div>
			</div>
		
		</div>
	</div>
	
	<div class="form-actions">
		<button class="btn btn-primary btn-icon glyphicons circle_ok" id="btnSave"><i></i> Save</button>
		<button class="btn btn-primary btn-icon glyphicons circle_ok" id="btnSaveExit"><i></i> Save &amp; Exit</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/tower_provider') ?>'"><i></i>Cancel</button>
	</div>
	
	<?php echo form_close() ?>

</div>

<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script>
var btnAction = '';
document.getElementById('btnSave').onclick = function() {
	btnAction = this.id;
};
document.getElementById('btnSaveExit').onclick = function() {
	btnAction = this.id;
};

document.getElementById('frmTP').onsubmit = function() {

	var data = $(this).serialize();
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_TP';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=EDIT_TP';
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
					if (btnAction == 'btnSave') {
						window.location.href = '<?php echo site_url('/site_info/tower_provider/edit') ?>/' + json.id;
					} else if (btnAction == 'btnSaveExit') {
						window.location.href = '<?php echo site_url('/site_info/tower_provider') ?>';
					}
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

document.getElementById('propinsi').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info/tower_provider') ?>',
		type: 'GET',
		data: { cmd: 'GET_CITY', propinsi_id: this.value },
		dataType: 'json',
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.length; i++) {
				html += '<option value="' + json[i]['id'] + '">' + json[i]['name'] + '</option>';
			}
			document.getElementById('city').innerHTML = html;
		}
	});	
};

document.getElementById('siteCategory').onchange = function() {
	//console.log(this.options);
};

var showMap = function() {
	var x = parseInt((window.screen.availWidth - 850) / 2);
    var y = parseInt(((window.screen.availHeight - 560) / 2) - 50);
	
	var w = window.open('<?php echo site_url('/site_info') ?>?cmd=GET_MAP&draggable=true&latlngElement=latitude,longitude', 'map', 'height=560,width=850,left=' + x + ',top=' + y + ',scrollbars=no');
	w.focus();
	
	return false;
};

numberFormat.init();
latLngFormat.init();
TEXT.clearAutoComplete();
TEXT.toUpperCase();
mainMenu();

var txt = $('.elastic'),
    hiddenDiv = $(document.createElement('div')),
    content = null;

//txt.addClass('txtstuff');
//hiddenDiv.addClass('hiddendiv common');

txt.css('resize', 'none'); /* remove this if you want the user to be able to resize it in modern browsers */
txt.css('overflow', 'hidden');//txt.css('height', txt[0].scrollHeight);
hiddenDiv.css('display', 'none');
hiddenDiv.css('white-space', 'pre-wrap');
hiddenDiv.css('word-wrap', 'break-word');
hiddenDiv.css('overflow-wrap', 'break-word');
hiddenDiv.css('min-height', txt.height());
hiddenDiv.css('overflow', 'hidden');

//console.log(txt[0].scrollHeight);//document.getElementsByClassName('elastic')[0].style.height = '100px';

//hiddenDiv.css('height', '50px');
//console.log(txt.height());


$('body').append(hiddenDiv);

//$(txt).each(function(i, elem) {
	//console.log($(this).val());
//});

$(txt).each(function(i, elem) {
	//content = txt.val();
	//content = $(this).val();

	//content = content.replace(/\n/g, '<br>');
	//hiddenDiv.html(content + '<br class="lbr">');
	//var height = hiddenDiv.height() > $(this).height() ? hiddenDiv.height() : $(this).height;
	var height = hiddenDiv.height() > elem.scrollHeight ? hiddenDiv.height() : elem.scrollHeight;
	console.log(hiddenDiv.height() +'>'+ elem.scrollHeight);
	$(this).css('height', height);
});

txt.on('keyup', function() {

    //content = $(this).val();

    //content = content.replace(/\n/g, '<br>');
    //hiddenDiv.html(content + '<br class="lbr">');
	var height = hiddenDiv.height() > $(this).scrollHeight ? hiddenDiv.height() : $(this).scrollHeight;

    //$(this).css('height', hiddenDiv.height());
	$(this).css('height', height);
});
</script>

<style>
textarea_ {
    color: #444;
    padding: 5px;
}

.txtstuff_ {
    resize: none; /* remove this if you want the user to be able to resize it in modern browsers */
    overflow: hidden;
}

.hiddendiv_ {
    display: none;
    white-space: pre-wrap;
    word-wrap: break-word;
    overflow-wrap: break-word; /* future version of deprecated 'word-wrap' */
}

/* the styles for 'commmon' are applied to both the textarea and the hidden clone */
/* these must be the same for both */
.common_ {
    width: 500px;
    min-height: 50px;
    font-family: Arial, sans-serif;
    font-size: 13px;
    overflow: hidden;
}

.lbr {
    line-height: 3px;
}
</style>