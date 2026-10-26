<style>
.table-primary thead th { font-size:12px; }
th a { padding:0 20px 0 0; }
th a, th a:focus { color:#FFFFFF; text-decoration:underline; }
th a.sort-asc { background:url('<?php echo base_url('/themes/sims/images/asc.png') ?>') no-repeat right center; }
th a.sort-desc { background:url('<?php echo base_url('/themes/sims/images/desc.png') ?>') no-repeat right center; }
/* .widget .widget-body{ padding:20px 15px; }. */
.widget .widget-body form{ margin:0; }
.form-actions { padding:10px 0 10px !important; }
.number { text-align:right; }
</style>

<!--
<div class="heading-buttons">
	<h3>Power Equipment</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/power/add') ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add New</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">

	<div class="widget">
		
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Power Equipment Data</h4>
			<div class="btn-heading-group">
			<?php if ($this->mmenu->cekPermission($menu, 'menu_add')) { ?>
			<a href="<?php echo site_url('/site_info/power/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
			<?php } ?>
            <a id="btn_export" href="<?php echo site_url('/export/site_info/power_equipment') ?>" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
            </div>
		</div>
		
		<div class="widget-body">
			<div id="tb-power"  style="padding: 10px 10px 0px 10px; height:auto">
				<div class="input-append">
					<?php echo form_input('query-power', '', 'id="query-power" class="input-medium search-query" placeholder="Search..."') ?>
					<button id="btnSearch" class="btn" onclick="GRID_POWER.search()">Search</button>
				</div>
			</div>		

			
			<table id="grid-power" class="jgrid"></table>
		
		</div>
	</div>
	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>

<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<script>
var GRID_POWER = {
	params: {
		cmd: 'GET_DATAGRID_POWER',
		query: ''
	},
	loadData: function() {
		$('#grid-power').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 100, 200, 500],
			columns:[[
				{ field: 'site_id', hidden: true },
				{ field: 'site_code', title: 'Site ID', width: 150, sortable: true },
				{ field: 'site_name', title: 'Site Name', width: 150, sortable: true },
				{ field: 'vendor_id', title: 'Vendor ID', width: 150, sortable: true },
				{ field: 'region_name', title: 'Region', width: 150, sortable: true },
				{ field: 'city', title: 'City', width: 150, sortable: true },
				{ field: 'actions', title: 'Actions', width: 100, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_view')) { ?>\
						<a href="<?php echo site_url('/site_info/power/view') ?>/' + row.site_id + '" class="btn-action glyphicons eye_open btn-info" title="View"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_edit')) { ?>\
						<a href="<?php echo site_url('/site_info/power/edit') ?>/' + row.site_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_delete')) { ?>\
						<a href="javascript:GRID_POWER.delete(\'' + row.site_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
						<?php } ?>
					';
				} }
			]],
			//iconCls: 'icon-save',
			toolbar: '#tb-power',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-power').value;
		this.loadData();
		document.getElementById('query-power').select();
	},
	delete: function(id) {
		this.params.cmd = 'DELETE_POWER';
		this.params.id = id;
		var params = this.params;
		
		coolConfirmation('Are you sure you want to delete?', function() {			
			$.ajax({
				url: '<?php echo site_url('/site_info/power') ?>',
				type: 'GET',
				data: params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_POWER.params.cmd = 'GET_DATAGRID_POWER';
						GRID_POWER.loadData();
					}
				}
			});
		});
	},
	init: function() {
		this.params.query = document.getElementById('query-power').value;
		this.loadData();
		document.getElementById('query-power').select();
		
		document.getElementById('query-power').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_POWER.search();
			}
		};
	}
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

var clearAutoComplete = {
	fields: document.getElementsByTagName('input'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			if (this.fields[i].type == 'text') {
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

$(function() {
	GRID_POWER.init();
});

mainMenu();
</script>