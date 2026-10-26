<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>

<style>
.btn_ { padding:4px 10px; }
.table-primary thead th_ { font-size:12px; }
.tabs_ { height:36px; }
.tabs-panels_ { padding:10px; background:#ffffff; }
.tabs li a.tabs-inner_ { border-radius:0px; height:35px; line-height:35px; }
.tabs-title { font-size:13px; }
input.number, input.currency, input.just-number { text-align:right; }
.widget .widget-body_{padding:20px 15px;}
.widget .widget-body form{margin:0;}
.table th, .table td { vertical-align:middle; }
</style>

<!--
<div class="heading-buttons">
	<h3>Equipment</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/equipment') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">
	
	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Site</h4>
			
			<a href="<?php echo site_url('/site_info/equipment') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body form-horizontal">
		
			<div class="form-horizontal">
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_site_id">
							<label class="control-label" for="siteId">Site ID:</label>
							<div class="controls">
								<span class="input-label"><?php echo $equipment['site_code'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6">
						<div class="control-group" id="form_site_id_ref">
							<label class="control-label" for="siteIdRef">Site ID Ref.:</label>
							<div class="controls">
								<span id="siteIdRef" class="input-label"><?php echo @$equipment['site_code_old'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_site_name">
							<label class="control-label" for="siteName">Site Name:</label>
							<div class="controls">
								<span id="siteName" class="input-label"><?php echo @$equipment['site_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6">
						<div class="control-group" id="form_vendor_id">
							<label class="control-label" for="vendorId">Vendor ID:</label>
							<div class="controls">
								<span id="vendorId" class="input-label"><?php echo @$equipment['vendor_id'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="control-group" id="form_region">
					<label class="control-label" for="region">Region:</label>
					<div class="controls">
						<span id="region" class="input-label"><?php echo @$equipment['region_name'] ?></span>
					</div>
				</div>
			</div>
		
		</div>
	
	</div>
	
	<div id="equipmentTab" class="easyui-tabs">
		<div title="BTS Info" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=BTS_INFO_VIEW'"></div>
		<div title="RF Antenna" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=RF_VIEW'"></div>
		<div title="Transmission" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=TRANSMISSION_VIEW'"></div>
		<div title="GPS Antenna" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=GPS_VIEW'"></div>
	</div>
	
</div>

<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/serialize-0.2.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<script src="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/jquery.notyfy.js') ?>"></script>
<link href="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/themes/default.css') ?>" rel="stylesheet" />

<script>
var loadToUpperCase = function() {
	var uppercaseElement = document.getElementsByClassName('uppercase');
	for (var i = 0; i < uppercaseElement.length; i++) {
		uppercaseElement[i].onkeyup = function() {
			this.value = this.value.toUpperCase();
		};
	};
};

var numberFormat = {	
	fields: document.getElementsByClassName('number'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			// load default
			this.fields[i].value = number_format(this.fields[i].value, 2, '.', '');
			
			// onfocus
			this.fields[i].onfocus = function(e) {
				if (!isNumeric(this.value)) {
					setTimeout(function() { e.target.select() });
					return false;
				} else {
					var prec = this.value.split('.');
					if (prec[1] == '00') {
						this.value = number_format(this.value, '', '', '');
					} else {
						this.value = number_format(this.value, 2, '.', '');
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
					this.value = number_format(this.value, 2, '.', '');
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
			}
		}
	},
	removeFormat: function() {
		for (var i = 0; i < this.fields.length; i++) {
			var prec = this.fields[i].value.split('.');
			if (prec[1] == '00') {
				this.fields[i].value = number_format(this.fields[i].value, '', '', '');
			} else {
				this.fields[i].value = number_format(this.fields[i].value, 2, '.', '');
			}
		}
	}
};

var justNumberFormat = {	
	fields: document.getElementsByClassName('just-number'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			this.fields[i].value = number_format(this.fields[i].value, 0, '', '');
			
			this.fields[i].onblur = function(e) {
				if (!justNumberFormat.isNumber(this.value)) {
					alert('Please enter only numbers.');
					setTimeout(function() { e.target.select() });
					return false;
				} else {
					this.value = number_format(this.value, 0, '', '');
				}
			};
			
			this.fields[i].onkeyup = function(e) {
				var code = e.keyCode;
				if (!justNumberFormat.isNumber(this.value)) {
					if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
						this.blur();
					}
				}
			};
			
			this.fields[i].onkeypress = function(e) {
				var code = e.keyCode;
				if (!justNumberFormat.isNumber(this.value)) {
					if (code == 13) {
						setTimeout(function() { e.target.select() });
						return false;
					}
				}
			}
		}
	},
	removeFormat: function() {},
	isNumber: function(sText) {
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
	}
};

var textUpperCase = {	
	fields: document.getElementsByClassName('uppercase'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			this.fields[i].onkeyup = function() {
				this.value = this.value.toUpperCase();
			};
		};
	}
};

var isNumeric = function(sText) {
	var validChars = "0123456789.-";
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

var clearAutocomplete = function() {
	var input = document.getElementsByTagName('input');
	for (var i = 0; i < input.length; i++) {
		if (input[i].type == 'text') {
			input[i].setAttribute('autocomplete', 'off');
		}
	}
};

var uneditable = {
	fields: document.getElementsByClassName('uneditable'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			this.fields[i].onkeypress = function() {
				return false;
			};
		}
	}
};

var IPAddress = {	
	fields: document.getElementsByClassName('ipaddress'),
	init: function() {
		var format = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
		
		for (var i = 0; i < this.fields.length; i++) {
			this.fields[i].onblur = function(e) {
				if (this.value != '') {
					if (format.test(this.value)) {
						return true;
					} else {
						alert('You have entered an invalid IP address.');
						setTimeout(function() { e.target.select() });
						return false;
					}
				}
			}
		}
	}
}

var coolNotification = function(message, type, functionAfterOKClicked) {
	notyfy({
		layout: 'center',
		text: message,
		type: type, // alert, success, error, warning, information, confirm
		dismissQueue: false,
		modal: true,
		showEffect: false,
		hideEffect: false,
		buttons: [{
			addClass: 'btn btn-notyfy-ok',
			text: '<i class="icon-ok"></i> OK',
			onClick: function(notyfy) {
				notyfy.close();
				
				if (typeof functionAfterOKClicked != 'undefined' && typeof functionAfterOKClicked == 'function') {
					functionAfterOKClicked();
				}
			}
		}],
		events: {
			shown: function() {
				document.getElementsByClassName('notyfy_buttons')[0].style.textAlign = 'center';
				document.getElementsByClassName('btn-notyfy-ok')[0].focus();
			}
		}
	});
};

var coolConfirmation = function(message, functionAfterYesClicked) {
	notyfy({
		layout: 'center',
		text: message,
		type: 'confirm', // alert, success, error, warning, information, confirm
		dismissQueue: false,
		modal: true,
		showEffect: false,
		hideEffect: false,
		buttons: [{
			addClass: 'btn btn-notyfy-yes',
			text: '<i class="icon-ok"></i> Yes',
			onClick: function(notyfy) {
				notyfy.close();
				
				if (typeof functionAfterYesClicked != 'undefined' && typeof functionAfterYesClicked == 'function') {
					functionAfterYesClicked();
				}
			}
		}, {
			addClass: 'btn',
			text: '<i class="icon-remove"></i> No',
			onClick: function(notyfy) {
				notyfy.close();
			}
		}],
		events: {
			shown: function() {
				document.getElementsByClassName('notyfy_buttons')[0].style.textAlign = 'center';
				document.getElementsByClassName('btn-notyfy-yes')[0].focus();				
			}
		}
	});
};

var doLookupSite = function(elem1, elem2) { // elem1: shown element, elem2: hidden element
	var url = '<?php echo site_url('/site_info') ?>';
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'GET_LOOKUP_SITE', e: elem2 } }, // e: it used for selected element
		width: 600,
		scrolling: false,
		autoSize: true,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade',
		afterShow: function() {
			document.getElementById('btnSiteSelectedOK').onclick = function() {
				var row = $('#dg-sites').datagrid('getSelected');
			
				if (row != null) {
					document.getElementsByName(elem1)[0].value = row.site_code;
					document.getElementsByName(elem2)[0].value = row.site_id;
					
					document.getElementById('siteIdRef').innerHTML = row.site_name;
					document.getElementById('siteName').innerHTML = row.site_name;
					document.getElementById('vendorId').innerHTML = row.vendor_id;
					document.getElementById('region').innerHTML = row.region_name;
				}
				
				$.fancybox.close();
			};
		}
	});
};

clearAutocomplete();
uneditable.init();
mainMenu();
</script>