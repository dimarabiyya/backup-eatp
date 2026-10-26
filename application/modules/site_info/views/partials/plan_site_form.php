<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<?php echo form_open('', 'id="frmSite" class="form-horizontal"') ?>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> General</h4>
	</div>
	
	<div class="widget-body">
	
		<?php if ($this->uri->segment(3) == 'add') { ?>
		<div class="control-group" id="form_area_code">
			<label class="control-label" for="areaCode">Area Code:</label>
			<div class="controls">
				<div class="input-append">
					<?php echo form_input('areaCode', '', 'id="areaCode" onfocus="doLookupAreaCode(\'areaCode\', \'areaCode2\')" class="input-small uneditable"') ?>
					<?php echo form_hidden('areaCode2', '') ?>
					<button type="button" class="btn" onclick="doLookupAreaCode('areaCode', 'areaCode2')">...</button>
				</div>
			</div>
		</div>
		<?php } ?>
		
		<div class="row-fluid">
			<div class="span6">
				<div class="control-group" id="form_plan_site_id">
					<label class="control-label" for="siteCode">Site ID:</label>
					<div class="controls">
						<?php if ($this->uri->segment(3) == 'edit' || $this->core->view_row('t_mtr_site_plan', array('nom_site_id' => (int) $this->input->get('site_id'))) ) { ?>
						<span class="input-label"><?php echo $site['nom_site_code'] ?></span>
						<?php echo @form_hidden('siteCode', $site['nom_site_code']) ?>
						<?php } else { ?>
						<?php echo @form_input('siteCode', $site['nom_site_code'], 'id="siteCode" class="input-medium uppercase"') ?>
						<?php } ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group" id="form_plan_site_name">
					<label class="control-label" for="siteName">Site Name:</label>
					<div class="controls">
						<?php echo @form_input('siteName', $site['nom_site_name'], 'id="siteName" class="uppercase"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span6">
				<div class="control-group" id="form_plan_latlong">
					<label class="control-label" for="nominalLatitude">Lat - Long:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('nominalLatitude', $site['nom_latitude'], 'id="nominalLatitude" class="input-small latlng-number"') ?>
							<?php echo @form_input('nominalLongitude', $site['nom_longitude'], 'id="nominalLongitude" class="input-small latlng-number"') ?>
							<button type="button" class="btn" onclick="showMap()">Map</button>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group" id="form_plan_search_radius">
					<label class="control-label" for="searchRadius">Search Radius:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('searchRadius', $site['search_radius'], 'id="searchRadius" class="input-mini number"') ?>
							<span class="add-on">M</span>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_plan_bts_type">
			<label class="control-label" for="btsType">BTS Type:</label>
			<div class="controls">
				<?php
				$lookup = $this->core->get_lookup('bts_type');
				$options = array(0 => '- Choose one -');
				foreach ($lookup as $lo) {
					$options[$lo['id']] = $lo['desc'];
				}
				echo @form_dropdown('btsType', $options, $site['bts_type'], 'id="btsType" class="input-medium"');
				?>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span6">
				<div class="control-group" id="form_plan_antenna_height">
					<label class="control-label" for="RFHeight">RF Antenna Height:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('RFHeight', $site['bts_ant_height'], 'id="RFHeight" class="input-mini number"') ?>
							<span class="add-on">M</span>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group" id="form_plan_antenna_dimension">
					<label class="control-label" for="RFDimensionL">RF Antenna Dimension (LxWxH):</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('RFDimensionL', $site['bts_dimension_l'], 'id="RFDimensionL" class="input-mini number"') ?>
							<?php echo @form_input('RFDimensionW', $site['bts_dimension_w'], 'id="RFDimensionW" class="input-mini number"') ?>
							<?php echo @form_input('RFDimensionH', $site['bts_dimension_h'], 'id="RFDimensionH" class="input-mini number"') ?>
							<span class="add-on">M</span>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_plan_power_requirement">
			<label class="control-label" for="powerRequirement">Power (PLN) Requirement:</label>
			<div class="controls">
				<div class="input-append">
					<?php echo @form_input('powerRequirement', $site['power_requirement'], 'id="powerRequirement" class="input-mini number"') ?>
					<span class="add-on">kVA</span>
				</div>
			</div>
		</div>
	
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> Address</h4>
	</div>
	
	<div class="widget-body">
	
		<div class="control-group" id="form_plan_address">
			<label class="control-label" for="address">Address:</label>
			<div class="controls">
				<?php echo @form_textarea('address', $site['nom_address'], 'id="address" style="height:80px"') ?>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span6">
				<div class="control-group" id="form_plan_province">						   
					<label class="control-label" for="propinsi">Province:</label>
					<div class="controls">
						<?php
						$propinsi = array('0' => '- Choose one -');
						$dataPropinsi = $this->core->view_all('t_mtr_lokasi', array('jenis' => 'P'), 'nama');
						foreach ($dataPropinsi as $prop) {
							$propinsi[$prop['lokasi_id']] = $prop['nama'];
						}
						echo @form_dropdown('propinsi', $propinsi, $site['propinsi_id'], 'id="propinsi"');
						?>
					</div>							
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group" id="form_plan_kabupaten">
					<label class="control-label" for="kabupaten">Kabupaten:</label>
					<div class="controls">
						<?php
						$kabupaten = array('0' => '- Choose one -');
						$dataKabupaten = @$this->core->view_all('t_mtr_lokasi', array('jenis' => 'KB', 'parent_id' => $site['propinsi_id']), 'nama');
						foreach ($dataKabupaten as $kab) {
							$kabupaten[$kab['lokasi_id']] = $kab['nama'];
						}
						echo @form_dropdown('kabupaten', $kabupaten, $site['kabupaten'], 'id="kabupaten"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span6">
				<div class="control-group" id="form_plan_kecamatan">
					<label class="control-label" for="kecamatan">Kecamatan:</label>
					<div class="controls">
						<?php
						$kecamatan = array(0 => '- Choose one -');
						$dataKecamatan = @get_instance()->core->view_all('t_mtr_lokasi', array('jenis' => 'KC', 'parent_id' => $site['kabupaten']), 'nama');
						foreach ($dataKecamatan as $kec) {
							$kecamatan[$kec['lokasi_id']] = $kec['nama'];
						}
						echo @form_dropdown('kecamatan', $kecamatan, $site['kecamatan'], 'id="kecamatan"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group" id="form_plan_kelurahan">						   
					<label class="control-label" for="kelurahan">Kelurahan:</label>
					<div class="controls">
						<?php echo @form_input('kelurahan', $site['kelurahan'], 'id="kelurahan" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="control-group" id="form_plan_kode_pos">
			<label class="control-label" for="kodePos">Kode Pos:</label>
			<div class="controls">
				<?php echo @form_input('kodePos', $site['kode_pos'], 'maxlength="6" id="kodePos" class="input-mini just-number" style="text-align:left"') ?>
			</div>
		</div>
	
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Territory</h4>
	</div>
	
	<div class="widget-body">
		<div class="control-group" id="form_plan_region">
			<label class="control-label" for="region">Region:</label>
			<div class="controls">
				<?php
				$my_region = array();
				foreach ($this->session->userdata['user_region'] as $reg) {
					if ($reg['region_id'] != '') {
						$my_region[] = $reg['region_id'];
					}
				}
				
				$regions = array('0' => '- Choose one -');
				$dataRegions = $this->db
					->query("SELECT region_id, region_name FROM t_mtr_region WHERE status = 1 AND region_id IN (".implode(', ', $my_region).") ORDER BY region_name ASC")
					->result_array();
				foreach ($dataRegions as $region) {
					$regions[$region['region_id']] = $region['region_name'];
				}
				echo @form_dropdown('region', $regions, $site['region_id'], 'id="region" class="medium"');
				?>
			</div>
		</div>

		<div class="control-group" id="form_plan_cluster_ops">
			<label class="control-label" for="clusterOpr">Operational Cluster:</label>
			<div class="controls">
				<?php
				$region_id = @$this->core->view_row('t_mtr_cluster_ops', array('cluster_ops_id' => $site['cluster_ops_id']));
				
				$clusters = array('0' => '- Choose one -');
				$dataCluster = @$this->core->view_all('t_mtr_cluster_ops', array('region_id' => $region_id['region_id'], 'cluster_ops_id !=' => 0), 'cluster_name');
				foreach ($dataCluster as $cluster) {
					$clusters[$cluster['cluster_ops_id']] = $cluster['cluster_name'];
				}
				echo @form_dropdown('clusterOpr', $clusters, $site['cluster_ops_id'], 'id="clusterOpr" class="medium"');
				?>
			</div>
		</div>

		<div class="control-group" id="form_plan_cluster_net">
			<label class="control-label" for="cluster">Network Cluster:</label>
			<div class="controls">
				<?php
				$region_id = @$this->core->view_row('t_mtr_cluster_net', array('cluster_net_id' => $site['cluster_net_id']));
				
				$clusters = array('0' => '- Choose one -');
				$dataCluster = @$this->core->view_all('t_mtr_cluster_net', array('region_id' => $region_id['region_id'], 'cluster_net_id !=' => 0), 'cluster_name');
				foreach ($dataCluster as $cluster) {
					$clusters[$cluster['cluster_net_id']] = $cluster['cluster_name'];
				}
				echo @form_dropdown('clusterNet', $clusters, $site['cluster_net_id'], 'id="clusterNet" class="medium"');
				?>
			</div>
		</div>

		<div class="control-group" id="form_plan_cluster_com">
			<label class="control-label" for="cluster">Commercial Cluster:</label>
			<div class="controls">
				<?php
				$region_id = @$this->core->view_row('t_mtr_cluster_com', array('cluster_com_id' => $site['cluster_com_id']));
				
				$clusters = array('0' => '- Choose one -');
				$dataCluster = @$this->core->view_all('t_mtr_cluster_com', array('region_id' => $region_id['region_id'], 'cluster_com_id !=' => 0), 'cluster_name');
				foreach ($dataCluster as $cluster) {
					$clusters[$cluster['cluster_com_id']] = $cluster['cluster_name'];
				}
				echo @form_dropdown('clusterCom', $clusters, $site['cluster_com_id'], 'id="clusterCom" class="medium"');
				?>
			</div>
		</div>
	</div>
</div>

<div class="form-actions">
	<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
	<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/plan_info') ?>'"><i></i>Cancel</button>
</div>

<?php echo form_close() ?>

<script>
document.getElementById('frmSite').onsubmit = function() {

	var data = $(this).serialize();
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_SITE';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=EDIT_SITE';
	<?php } ?>
	data += '&siteId=' + document.getElementsByName('siteId')[0].value;
	
	$.ajax({
		url: document.URL,
		type: 'POST',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			$.blockUI({ message: 'Loading, please wait...', css: {
				border: 'none',
				padding: '15px',
				backgroundColor: '#000',
				'-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
				opacity: .5,
				color: '#fff'
			} });
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
					// refresh content tab
					var tab = $('#planTab').tabs('getSelected');
					$('#planTab').tabs('update', {
						tab: tab,
						options: {
							href: document.URL + '?load_content=SITE&site_id=' + json.site_id
						}
					});
					
					//document.getElementsByName('siteId')[0].value = json.site_id;
					window.location.href = planURL;
				});
			}
			
			window.scroll(0, 0);
			
			$.unblockUI();
		},
		complete: function() {}
	});
	
	return false;
};

var doLookupAreaCode = function(elem1, elem2) { // elem1: shown element, elem2: hidden element
	var url = '<?php echo site_url('/site_info/plan_info') ?>';
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'get_lookup_area_code', e: elem2 } }, // e: it used for selected element
		width: 600,
		scrolling: false,
		autoSize: true,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade',
		afterShow: function() {
			document.getElementById('btnDataSelectedOK').onclick = function() {
				var row = $('#dg-area-code').datagrid('getSelected');
			
				if (row != null) {
					document.getElementsByName(elem1)[0].value = row.area_code;
					document.getElementsByName(elem2)[0].value = row.area_id;
					
					$.ajax({
						url: '<?php echo site_url('/site_info/plan_info') ?>',
						type: 'GET',
						data: { cmd: 'get_area_code', code: row.area_code },
						dataType: 'json',
						beforeSend: function() {
							$.blockUI({ message: 'Generating Site ID, please wait...', css: {
								border: 'none',
								padding: '15px',
								backgroundColor: '#000',
								'-webkit-border-radius': '10px',
								'-moz-border-radius': '10px',
								opacity: .5,
								color: '#fff'
							} });
						},
						success: function(json) {
							document.getElementById('siteCode').value = json.site_code;
							
							$.unblockUI();
						}
					});
				}
				
				$.fancybox.close();
			};
		}
	});
};

document.getElementById('propinsi').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info/add') ?>',
		type: 'GET',
		data: { cmd: 'GET_KABUPATEN', propinsi_id: this.value },
		dataType: 'json',
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.province.length; i++) {
				html += '<option value="' + json.province[i]['id'] + '">' + json.province[i]['name'] + '</option>';
			}
			document.getElementById('kabupaten').innerHTML = html;
			document.getElementById('kecamatan').innerHTML = '<option value="0">- Choose one -</option>';
		}
	});	
};

document.getElementById('kabupaten').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info/add') ?>',
		type: 'GET',
		data: { cmd: 'GET_KECAMATAN', kabupaten_id: this.value },
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
			document.getElementById('kecamatan').innerHTML = html;
		}
	});
};

document.getElementById('region').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info/add') ?>',
		type: 'GET',
		data: { cmd: 'GET_CLUSTER', region_id: this.value },
		dataType: 'json',
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.ops.length; i++) {
				html += '<option value="' + json.ops[i]['id'] + '">' + json.ops[i]['name'] + '</option>';
			}
			document.getElementById('clusterOpr').innerHTML = html;
			
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.net.length; i++) {
				html += '<option value="' + json.net[i]['id'] + '">' + json.net[i]['name'] + '</option>';
			}
			document.getElementById('clusterNet').innerHTML = html;
			
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.com.length; i++) {
				html += '<option value="' + json.com[i]['id'] + '">' + json.com[i]['name'] + '</option>';
			}
			document.getElementById('clusterCom').innerHTML = html;
		}
	});	
};

var showMap = function() {
	var w = 850;
	var h = 560;
	var x = parseInt((window.screen.availWidth - w) / 2);
    var y = parseInt(((window.screen.availHeight - h) / 2) - 50);
	
	var w = window.open('<?php echo site_url('/site_info') ?>?cmd=GET_MAP&draggable=true&latlngElement=nominalLatitude,nominalLongitude', 'map', 'height=' + h + ',width=' + w + ',left=' + x + ',top=' + y + ',scrollbars=no');
	w.focus();
	
	return false;
};

var latLngFormat = {
	fields: document.getElementsByClassName('latlng-number'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			// load default
			var decimals = this.fields[i].value.indexOf('.') != -1 ? this.fields[i].value.split('.')[1].length : 0;
			decimals = decimals < 6 ? 6 : decimals;
			var defaultValue = this.fields[i].value.length > 0 ? number_format(this.fields[i].value, decimals, '.', '') : '';
			
			this.fields[i].value = defaultValue;
			this.fields[i].style.textAlign = 'right';
			
			// onfocus
			this.fields[i].onfocus = function(e) {
				if (!isNumeric(this.value)) {
					setTimeout(function() { e.target.select() });
					return false;
				} else {
					var prec = this.value.split('.');
					if (prec[1] == '00') {
						this.value = number_format(this.value, 0, '', '');
					} else {
						var decimals = this.value.indexOf('.') != -1 ? this.value.split('.')[1].length : 0;
						var defaultValue = this.value.length > 0 ? number_format(this.value, decimals, '.', '') : '';
						this.value = defaultValue;
					}
				}
			};
			
			// onblur
			this.fields[i].onblur = function(e) {
				if (!isNumeric(this.value)) {					
					alert('Please enter only numbers or decimal points.');
					setTimeout(function() { e.target.select() });
					return false;
				} else {
					var decimals = this.value.indexOf('.') != -1 ? this.value.split('.')[1].length : 0;
					decimals = decimals < 6 ? 6 : decimals;
					var defaultValue = this.value.length > 0 ? number_format(this.value, decimals, '.', '') : '';
					this.value = defaultValue;
				}
			};
			
			// onkeyup
			this.fields[i].onkeyup = function(e) {
				var code = e.keyCode;
				if (!isNumeric(this.value)) {
					if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
						this.blur();
					}
				}
			}; 
			
			// onkeypress
			this.fields[i].onkeypress = function(e) {
				var code = e.keyCode;
				if (!isNumeric(this.value)) {
					if (code == 13) {
						setTimeout(function() { e.target.select() });
						return false;
					}
				}
			};
		}
	},
	removeFormat: function() {
		for (var i = 0; i < this.fields.length; i++) {
			var prec = this.fields[i].value.split('.');
			if (prec[1] == '00') {
				this.fields[i].value = number_format(this.fields[i].value, 0, '', '');
			} else {
				this.fields[i].value = number_format(this.fields[i].value, 2, '.', '');
			}
		}
	}
};

latLngFormat.init();
numberFormat.init();
justNumberFormat.init();
TEXT.toUpperCase();
TEXT.clearAutoComplete();
TEXT.uneditable();
doPreviledgePerfield();
</script>