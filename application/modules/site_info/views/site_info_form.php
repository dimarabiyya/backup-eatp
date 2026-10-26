<!--
<div class="heading-buttons">
	<?php if (get_instance()->uri->segment(2) == 'add') { ?>
		<h3>Add <span>| Sites</span></h3>
	<?php } else { ?>
		<h3>Edit <span>| Sites</span></h3>
	<?php } ?>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>
-->
<div class="innerLR">
	
	<?php if (get_instance()->uri->segment(2) == 'edit') { ?>
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading glyphicons edit"><i></i> Site</h4>
			<a href="<?php echo site_url('/site_info') ?>" class="btn btn-primary btn-mini pull-right btn-heading btn-icon glyphicons unshare"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
			<div class="row-fluid">
				<div class="span4">
					<table>
						<tr id="form_site_id">
							<td width="138">Site ID:</td>
							<td><span id="field-site-id-top"><strong><?php echo $general_info['site_code']; ?></strong></span></td>
						</tr>
						<tr id="form_site_name">
							<td>Site Name:</td>
							<td><span id="field-site-name-top"><strong><?php echo $general_info['site_name'] ?></strong></span></td>
						</tr>
					</table>
				</div>
				
				<div class="span8">
					<table>
						<tr id="form_vendor_id">
							<td width="138">Vendor ID:</td>
							<td><strong><?php echo $general_info['vendor_id'] ?></strong></td>
						</tr>
						<tr id="form_site_profile">
							<td>Site Profile:</td>
							<td>
								<span id="field-site-profile-top">
									<?php
									if ($lookup = $this->core->view_row('t_mtr_lookup', array('lookup_key' => 'site_profile', 'lookup_value' => $general_info['site_profile'])) ) {
										echo '<strong>'.$lookup['lookup_desc'].'</strong>';
									}
									?>
								</span>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<div class="widget" style="border:none; position:relative;">
		<div id="siteInfoTab" class="easyui-tabs" style="width:auto; height:auto;">
			<div title="General Info" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=GENERAL_INFO'"></div>
			<div title="SITAC" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=SITAC'"></div>
			<div title="CME" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=CME'"></div>
			<div title="Operational" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=OPERATIONAL'"></div>
			
			<?php if (get_instance()->uri->segment(2) == 'edit') { ?>
			<div title="Equipment" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=EQUIPMENT'"></div>
			<?php } ?>
			
			<?php if (get_instance()->uri->segment(2) == 'edit') { ?>
			<?php $tab_selected = get_instance()->input->get('src') == 'cnd' ? 'selected:true' : 'selected:false'; ?>
			<div title="Candidate List" data-options="<?php echo $tab_selected ?>,href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=CANDIDATE'"></div>
			<?php } ?>
			
			<?php if (get_instance()->uri->segment(2) == 'edit') { ?>
			<div title="PLan Info" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=PLAN_INFO'"></div>
			<?php } ?>
			
			<?php $tab_selected = get_instance()->session->flashdata('input_photo_ref_succ') ? 'selected:true' : 'selected:false'; ?>
			<div title="File / Photo Reference" data-options="<?php echo $tab_selected ?>,href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=PHOTO_REFERENCE'"></div>
			
		</div>
		
		<?php if ($this->uri->segment(2) == 'add') { ?>
		<div class="buttons" style="position:absolute; top:0px; right:7px;">
			<a href="<?php echo site_url('/site_info') ?>" class="btn btn-primary btn-mini pull-right btn-heading btn-icon glyphicons unshare"><i></i> Back</a>
		</div>
		<?php } ?>
		
	</div>
</div>

<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/serialize-0.2.js') ?>"></script>

<script src="<?php echo base_url('/assets/js/timepicker/jquery.timepicker.js') ?>"></script>
<link href="<?php echo base_url('/assets/js/timepicker/jquery.timepicker.css') ?>" rel="stylesheet" />

<script src="<?php echo base_url('/assets/js/ajaxupload.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>

<script src="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/jquery.notyfy.js') ?>"></script>
<link href="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/themes/default.css') ?>" rel="stylesheet" />

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>

<style>
.tabs-header_ { padding-top:0px; }
.tabs_ li_ { margin:-1px -1px -1px 0; }
.tabs_ li_ a.tabs-inner { border-radius:0px; -webkit-border-radius:0px; -moz-border-radius:0px; }

.btn_ { padding:4px 10px; }
.table-primary thead th_ { font-size:12px; }
.tabs_ { height:36px; }
.tabs-panels_ { padding:7px; }
.tabs_ li a.tabs-inner { border-radius:0px; height:35px; line-height:35px; }
.tabs-title { font-size:13px; }
input.number, input.currency, input.just-number { text-align:right; }
.widget .widget-body{padding:10px 10px;}
.widget .widget-body form{margin:0;}
.table th, .table td { vertical-align:middle; }
select { width:auto; }
</style>

<script>
var siteListURL = '<?php echo site_url('/site_info') ?>';

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

var currencyFormat = {
	fields: document.getElementsByClassName('currency'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			// load default
			this.fields[i].value = number_format(this.fields[i].value, 2);
			
			// onfocus
			this.fields[i].onfocus = function() {
				if (currencyFormat.isCurrency(this.value)) {
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
				//this.value = number_format(this.value, 2);
				if (!isNumeric(this.value)) {
					alert('Please enter only numbers or decimal points.');
					setTimeout(function() { e.target.select() });
					return false;
				} else {
					this.value = number_format(this.value, 2);
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
	},
	isCurrency: function(sText) {
		var validChars = "0123456789.-,";
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

var latLngFormat = {
	fields: document.getElementsByClassName('latlng-number'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			// load default
			var decimals = this.fields[i].value.indexOf('.') != -1 ? this.fields[i].value.split('.')[1].length : 0;
			//decimals = decimals < 6 ? 6 : decimals;
			decimals = 6;
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
					//decimals = decimals < 6 ? 6 : decimals;
					decimals = 6;
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
			};
			
			this.fields[i].onfocus = function(e) {
				if (this.value == 0) {
					this.value = '';
				}
			};
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
			this.fields[i].onkeyup = function(e) {
				if (e.keyCode != 37 && e.keyCode != 39) {
					this.value = this.value.toUpperCase();
				}
			};
		};
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

var timeFormat = {
	fields: document.getElementsByClassName('time'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			this.fields[i].onblur = function(e) {
				if (this.value != '' && !formatTime(this.value)) {
					this.value = '00:00';
					return false; 
				}
			};
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

var formatTime = function(time) {
    var result = false, m;
    var re = /^\s*([01]?\d|2[0-3]):?([0-5]\d)\s*$/;
    if ((m = time.match(re))) {
        result = (m[1].length == 2 ? "" : "0") + m[1] + ":" + m[2];
    }
    return result;
};

var clearField = function(field) {
	document.getElementById(field).value = '';
};

var dateFormat = {
	
	monthsName: { '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr', '05': 'May', '06': 'Jun', '07': 'Jul', '08': 'Aug', '09': 'Sep', '10': 'Oct', '11': 'Nov', '12': 'Dec' },
	monthsCode: { 'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06', 'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12' },
	fields: document.getElementsByClassName('date'),
	
	init: function() {
		$('.date').datepicker({
			dateFormat: 'dd-M-yy',
			changeMonth: true,
			changeYear: true
		});
		
		this.defaultMode();
		
		for (var i = 0; i < this.fields.length; i++) {
			(function(idx) {
			
				dateFormat.fields[idx].onfocus = function() {
					var day = this.value.split('-')[0];
					var month = this.value.split('-')[1];
					var year = this.value.split('-')[2];
					
					if (typeof dateFormat.monthsCode[month] != 'undefined') {						
						this.value = day + '-' + dateFormat.monthsCode[month] + '-' + year;
					}
				};
			
				dateFormat.fields[idx].onblur = function(e) {
					if (this.value != '') {
						if (!dateFormat.checkValidDate(this.value)) {
							setTimeout(function() {
								e.target.select();
							});
						}
					}
					
					var day = this.value.split('-')[0];
					var month = this.value.split('-')[1];
					var year = this.value.split('-')[2];
					
					if (typeof dateFormat.monthsName[month] != 'undefined') {
						this.value = day + '-' + dateFormat.monthsName[month] + '-' + year;
					}
				};
				
				dateFormat.fields[idx].onkeypress = function(e) {
					var restrictionFormat = /[0-9\-]/g; // cuma angka dan dash
					
					if (!e) var e = window.event
					if (e.keyCode) code = e.keyCode;
					else if (e.which) code = e.which;
					var character = String.fromCharCode(code);

					// if they pressed esc... remove focus from field...
					if (code == 27) { this.blur(); return false; }
					
					// ignore if they are press other keys
					// strange because code: 39 is the down key AND ' key...
					// and DEL also equals .
					if (!e.ctrlKey && code != 9 && code != 8 && code != 36 && code != 37 && code != 38 && (code != 39 || (code == 39 && character == "'")) && code != 40) {
						if (character.match(restrictionFormat)) {
							return true;
						} else {
							return false;
						}
						
					}
				};
			
			})(i);
		}
	},
	
	defaultMode: function() { // Call this after call 'saveMode'
		for (var i = 0; i < this.fields.length; i++) {
			if (this.fields[i].value != '') {
				var day = this.fields[i].value.split('-')[2];
				var month = this.fields[i].value.split('-')[1];
				var year = this.fields[i].value.split('-')[0];
			
				if (typeof this.monthsName[month] != 'undefined') {
					this.fields[i].value = day + '-' + this.monthsName[month] + '-' + year;
				}
			}
		}
	},
	saveMode: function() { // Call this after call 'defaultMode'
		for (var i = 0; i < this.fields.length; i++) {
			if (this.fields[i].value != '') {
				var day = this.fields[i].value.split('-')[0];
				var month = this.fields[i].value.split('-')[1];
				var year = this.fields[i].value.split('-')[2];
			
				if (typeof this.monthsCode[month] != 'undefined') {
					this.fields[i].value = year + '-' + this.monthsCode[month] + '-' + day;
				}
			}
		}
	},
	
	checkValidDate: function(strDate) {
		var validformat = /^\d{2}-\d{2}-\d{4}$/; // Basic check for format validity
		var returnval = false;
		
		if (!validformat.test(strDate)) {
			alert('Invalid date format.');
		} else { // Detailed check for valid date ranges
			var monthfield = strDate.split('-')[1];
			var dayfield = strDate.split('-')[0];
			var yearfield = strDate.split('-')[2];
			var dayobj = new Date(yearfield, monthfield - 1, dayfield);
			if ((dayobj.getMonth() + 1) != monthfield || dayobj.getDate() != dayfield || dayobj.getFullYear() != yearfield) {
				alert('Invalid day, month or year range.');
			} else {
				returnval = true;
			}
		}
		
		//if (returnval == false) {
		//	input.select();
		//}
		
		return returnval;
	}
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

var createCookie = function(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
};

var getCookie = function(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
};

eraseCookie = function(name) {
    createCookie(name, "", -1);
};

var siteInfoMenu = function() {
	var menu = document.getElementById('menu');
	var menuParent = menu.children[2].children[1]; // 2 = ul, 1 = site info
	var menuChild = menuParent.children[1].children; // 1 = ul
	var idxSelectedMenu = getCookie('idxSelectedMenu');
	var lastURL = getCookie('lastURL');
	
	menuParent.setAttribute('class', 'hasSubmenu active');
	menuParent.children[1].setAttribute('class', 'in collapse'); // site info selected & collapsed

	for (var i = 0; i < menuChild.length; i++) {
		menuChild[i].removeAttribute('class');
		
		if (idxSelectedMenu != '') {
			menuChild[idxSelectedMenu].setAttribute('class', 'active'); // child menu active and selected
		}
		
		(function(idx) {
			menuChild[idx].onclick = function() {
				for (var j = 0; j < menuChild.length; j++) {
					menuChild[j].removeAttribute('class');
				}
				
				eraseCookie('idxSelectedMenu');
				eraseCookie('lastURL');
				
				createCookie('idxSelectedMenu', idx, 1);
				createCookie('lastURL', menuChild[idx].children[0].href, 1);
			};
		})(i);
	}
};

mainMenu();
</script>