<style>
.table-primary thead th { font-size:12px; }
th a.sort-asc:focus, th a.sort-desc:focus { padding:0 20px 0 0; }
th a.sort-asc:not(:focus), th a.sort-desc:not(:focus) { background:none; }
th a, th a:focus { color:#FFFFFF; text-decoration:underline; }
th a.sort-asc { background:url('<?php echo base_url('/themes/sims/images/asc.png') ?>') no-repeat right center; }
th a.sort-desc { background:url('<?php echo base_url('/themes/sims/images/desc.png') ?>') no-repeat right center; }
select { width:auto; }
</style>

<!--
<div class="heading-buttons">
	<h3>PLN Usage</h3>
	
	<div class="buttons pull-right">		
		<a href="<?php echo site_url('/site_info/pln/add') ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add New</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading glyphicons list"><i></i> PLN Usages Data</h4>
			
			<div class="btn-heading-group">
				<?php if ($this->mmenu->cekPermission($menu, 'menu_add' )) { ?>
				<a href="<?php echo site_url('/site_info/pln/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
				<a href="<?php echo site_url('/site_info/pln/import') ?>" class="btn btn-success btn-mini btn-heading btn-icon glyphicons inbox_in pull-right"><i></i> Import</a>
				<a id="btn_export" href="javascript:void(0)" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
				<?php } ?>
			</div>
		</div>
		
		<div class="widget-body">
			<div id="tb-pln-usage" style="padding: 10px 10px 0px 10px; height:auto">
                <div class="input-prepend">
                  <span class="add-on">Month</span>
								<?php
								$month = array(0 => 'All', 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
								echo @form_dropdown('month',  $month,  set_value('month', $pln['bulan']), 'id="month"');
								?>
                </div>
                <div class="input-prepend">
                  <span class="add-on">Year</span>
								<?php
								$year = array(0 => 'All');
								for ($y = date('Y'); $y > (date('Y') - 10); $y--) {
									$year[$y] = $y;
								}
								echo @form_dropdown('year',  $year,  set_value('year', $pln['tahun']), 'id="year"');
								?>
                </div>

            <div class="input-append pull-right">
              <?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?> 
              <button class="btn" onclick="GRID_PLN_USAGE.search()" >Search</button>
            </div>

			</div>



			<table class="jgrid" id="grid-pln-usage"></table>
		
		</div>
	</div>
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<style>
.datagrid-header-rownumber { width:50px; }
.datagrid-cell-rownumber { width:50px; }
select { width:auto; }
</style>

<script>
var GRID_PLN_USAGE = {
	params: {
		cmd: 'GET_DATAGRID_PLN_USAGE',
		query: ''
	},
	loadData: function() {
		$('#grid-pln-usage').datagrid({
			url: '<?php echo site_url('/site_info/pln') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'site_code', title: 'Site ID', width: 100, sortable: true },
				{ field: 'site_name', title: 'Site Name', width: 200, sortable: true },
				{ field: 'vendor_id', title: 'Vendor ID', width: 100, sortable: true },
				{ field: 'customer_id', title: 'Customer ID', width: 150, sortable: true },
				{ field: 'bulan', title: 'Month', width: 100, sortable: true, align: 'left', formatter: function(value, row, index) { return getStringMonth(value); } },
				{ field: 'tahun', title: 'Year', width: 80, sortable: true, align: 'center' },
				{ field: 'payment_method', title: 'Payment Method', width: 150, sortable: true },
				{ field: 'company_code', title: 'Company Code', width: 100, sortable: true },
				{ field: 'cost_center', title: 'Cost Center', width: 100, sortable: true },
				{ field: 'biaya', title: 'Amount (IDR)', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2); } },
				// { field: 'kwh_awal', title: 'KWH before', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2, '.', ''); } },
				// { field: 'kwh_akhir', title: 'KWH After', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2, '.', ''); } },
				{ field: 'pemakaian_kwh', title: 'KWH Usage', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 0, '.', ''); } },
				{ field: 'actions', title: 'Actions', width: 100, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_view' )) { ?>\
						<a href="<?php echo site_url('/site_info/pln/view') ?>/' + row.id + '" class="btn-action glyphicons eye_open btn-info" title="View"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_edit' )) { ?>\
						<a href="<?php echo site_url('/site_info/pln/edit') ?>/' + row.id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_delete' )) { ?>\
						<a href="javascript:GRID_PLN_USAGE.delete(\'' + row.id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
						<?php } ?>\
					';
				} }
			]],
			toolbar: '#tb-pln-usage',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: false,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query').value;
		this.loadData();
		document.getElementById('query').select();
	},
	delete: function(id) {
		this.params.cmd = 'DELETE';
		this.params.id = id;
		
		coolConfirmation('Are you sure you want to delete?', function() {
			$.ajax({
				url: '<?php echo site_url('/site_info/pln') ?>',
				type: 'GET',
				data: GRID_PLN_USAGE.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_PLN_USAGE.params.cmd = 'GET_DATAGRID_PLN_USAGE';
						GRID_PLN_USAGE.loadData();
					}
				}
			});
		});
	},
	init: function() {
		this.params.query = document.getElementById('query').value;
		
		this.params.query = document.getElementById('month').value;
		this.loadData();
		document.getElementById('query').select();
		
		document.getElementById('query').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_PLN_USAGE.search();
			}
		};
		
		document.getElementById('year').onchange = function() {
			GRID_PLN_USAGE.params.year = this.value;
			GRID_PLN_USAGE.loadData();
		};
		
		document.getElementById('month').onchange = function() {
			GRID_PLN_USAGE.params.month = this.value;
			GRID_PLN_USAGE.loadData();
		};
	}
};

var getStringMonth = function(monthNumber) {
	var month = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	
	return month[monthNumber];
};

document.getElementById('btn_export').onclick = function() {
	//var month = document.getElementById('month').value;
	//var year = document.getElementById('year').value;
	
	//window.location.href = '<?php echo site_url('/site_info/pln/export') ?>?month=' + month + '&year=' + year;
	
	lookupExport();
};

var lookupExport = function() {
	var url = '<?php echo site_url('/site_info/pln/export') ?>';
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'show_form_export' } },
		width: 600,
		scrolling: false,
		autoSize: true,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade',
		afterShow: function() {
			//$.fancybox.close();
		}
	});
};

$(function() {
	GRID_PLN_USAGE.init();
});

TEXT.clearAutoComplete();
mainMenu();
</script>