<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>

<style>
.btn_ { padding:4px 10px; }
.table-primary thead th_ { font-size:12px; }
.tabs_ { height:36px; }
.tabs-panels_ { padding:10px; }
.tabs li a.tabs-inner_ { border-radius:0px; height:35px; line-height:35px; }
.tabs-title_ { font-size:13px; }
input.number, input.just-number { text-align:right; }
.widget .widget-body_{ padding:20px 15px; }
.widget .widget-body form{ margin:0; }
.combo .combo-text { padding:2px 0 2px 2px !important; }
.form-actions { padding:10px 0 10px !important; }
.glyphicons.btn-action.edit-detail_ { width:100px; height:23px; padding:2px 0 0; color:#586E54; text-decoration:none; }
select { width:auto; }
</style>

<!--
<div class="heading-buttons">
	<?php if (get_instance()->uri->segment(3) == 'add') { ?>
	<h3>Add <span>| Power Equipment</span></h3>
	<?php } else { ?>
	<h3>Edit <span>| Power Equipment</span></h3>
	<?php } ?>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/power') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">

	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Site</h4>
			
			<a href="<?php echo site_url('/site_info/power') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
			
			<div class="form-horizontal">
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_site_id">
							<label class="control-label" for="siteId">Site ID:</label>
							<div class="controls">
								<?php if ($this->uri->segment(3) == 'edit') { ?>
								
								<span class="input-label"><?php echo $power['site_code'] ?></span>
								<?php echo @form_hidden('siteId', set_value('siteId', $power['site_id'])) ?>
								
								<?php } else { ?>
								
								<div class="input-append">
									<?php echo @form_input('siteCode', set_value('siteCode', $power['site_code']), 'id="siteCode" class="input-medium uneditable" onfocus="doLookupSite(\'siteCode\', \'siteId\')"') ?>
									<?php echo @form_hidden('siteId', set_value('siteId', $power['site_id'])) ?>
									<button type="button" class="btn" onclick="doLookupSite('siteCode', 'siteId')"><i class="icon-search"></i></button>
								</div>
								<?php } ?>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_site_id_ref">
							<label class="control-label" for="siteIdRef">Site ID Ref.:</label>
							<div class="controls">
								<span id="siteIdRef" class="input-label"><?php echo @$power['site_code_old'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_site_name">
							<label class="control-label" for="siteName">Site Name:</label>
							<div class="controls">
								<span id="siteName" class="input-label"><?php echo @$power['site_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_vendor_id">
							<label class="control-label" for="vendorId">Vendor ID:</label>
							<div class="controls">
								<span id="vendorId" class="input-label"><?php echo @$power['vendor_id'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="control-group" id="form_region">
					<label class="control-label" for="region">Region:</label>
					<div class="controls">
						<span id="region" class="input-label"><?php echo @$power['region_name'] ?></span>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	
	<div class="widget" style="border:none">
		
		<!-- <?php if (get_instance()->session->flashdata('input_power_succ')) { ?>
		<div class="alert alert-success"><?php echo get_instance()->session->flashdata('input_power_succ') ?></div>
		<?php } ?> -->
		
		<div id="powerTab" class="easyui-tabs" style="width:auto; height:auto;">
			<div title="ACPDB" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=ACPDB'"></div>
			<div title="Rectifier" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=RECTIFIER'"></div>
			<div title="DCPDB" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=DCPDB'"></div>
			<div title="Battery" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=BATTERY'"></div>
		</div>
	</div>

</div>

<script src="<?php echo base_url('/assets/js/serialize-0.2.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/ajaxupload.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<script>
var powerURL = '<?php echo site_url('/site_info/power') ?>';

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
						this.value = number_format(this.value, 0, '', '');
					} else {
						this.value = number_format(this.value, 2, '.', '');
					}
					
					if (this.value == 0) {
						this.value = '';
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
			
			this.fields[i].onfocus = function(e) {
				if (this.value == 0) {
					this.value = '';
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

var clearAutoComplete = {
	fields: document.getElementsByTagName('input'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			if (this.fields[i].type.toLowerCase() == 'text') {
				this.fields[i].setAttribute('autocomplete', 'off');
			}
		}
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

var formField = {
	enable: function(fields) {
		for (var i = 0; i < fields.length; i++) {
			var elem = document.getElementById(fields[i]);
			if (elem != null) {
				if (elem.type == 'select-one') {
					elem.removeAttribute('disabled');
				} else {
					elem.removeAttribute('readonly');
				}
			}
		}
	},
	disable: function(fields) {
		for (var i = 0; i < fields.length; i++) {
			var elem = document.getElementById(fields[i]);
			if (elem != null) {
				if (elem.type == 'select-one') {
					elem.setAttribute('disabled', 'disabled');
				} else {
					elem.setAttribute('readonly', 'readonly');
				}
			}
		}
	},
	clearForm: function(formElement) {
		var elements = formElement.elements;

		formElement.reset();

		for (var i = 0; i < elements.length; i++) {
			field_type = elements[i].type.toLowerCase();

			switch (field_type) {
				case "text":
				case "password":
				case "textarea":
				case "hidden":
					elements[i].value = "";
					break;

				case "radio":
				case "checkbox":
					if (elements[i].checked) {
						elements[i].checked = false;
					}
					break;

				case "select-one":
				case "select-multi":
					elements[i].selectedIndex = 0;
					break;

				default:
					break;
			}
		}
	}
};

var doLookupSite = function(elem1, elem2) { // elem1: shown element, elem2: hidden element
	var url = '<?php echo site_url('/site_info') ?>';
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'GET_LOOKUP_SITE', e: elem2, not_appear_siteid: '<?php echo $not_appear_siteid ?>' } }, // e: it used for selected element
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
					
					document.getElementById('siteIdRef').innerHTML = row.site_code_old;
					document.getElementById('siteName').innerHTML = row.site_name;
					document.getElementById('vendorId').innerHTML = row.vendor_id;
					document.getElementById('region').innerHTML = row.region_name;
				}
				
				$.fancybox.close();
			};
		}
	});
};

uneditable.init();
mainMenu();
</script>