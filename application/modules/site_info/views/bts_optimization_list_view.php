<!--
<div class="heading-buttons">
	<h3>BTS Optimization</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/bts_optimization/add') ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add New</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">
	
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> BTS Optimization Data</h4>
			
			<div class="btn-heading-group">
				<?php if ($this->mmenu->cekPermission($menu, 'menu_add' )) { ?>
				<a href="<?php echo site_url('/site_info/bts_optimization/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
				<?php } ?>
				
				<a href="<?php echo site_url('/site_info/bts_optimization/not_optim') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons list pull-right"><i></i> Data Not Optim</a>
			
                <a id="btn_export" href="javascript:void(0)" onclick="GRID_BTS_OPTIMIZATION.export()" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
			</div>
		</div>
		
		<div class="widget-body">
		
			<?php if (get_instance()->session->flashdata('success')) { ?>
			<div class="alert alert-success"><?php echo get_instance()->session->flashdata('success') ?></div>
			<?php } ?>

			<div id="tb-bts-optimization"  style="padding: 10px 10px 0px 10px; height:auto">
				<div class="input-append">
					<?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?>
					<button id="btnSearch" class="btn" onclick="GRID_BTS_OPTIMIZATION.search()">Search</button>
				</div>
				<div class="input-prepend">
					<span class="add-on">Month</span>
					<?php
					$month = array(0 => 'All', 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
					echo @form_dropdown('month', $month, '', 'id="month"');
					?>
                </div>
                <div class="input-prepend">
					<span class="add-on">Year</span>
					<?php
					$year = array(0 => 'All');
					for ($y = date('Y'); $y > (date('Y') - 10); $y--) {
						$year[$y] = $y;
					}
					echo @form_dropdown('year', $year, '', 'id="year"');
					?>
                </div>
				
				<!--
				<div class="input-control pull-right">
					<a href="javascript:void(0)" class="btn btn-icon glyphicons inbox_out btn-warning" onclick="GRID_BTS_OPTIMIZATION.export()"><i></i> Export</a>
				</div>
				-->

			</div>					

			
			<table id="dg-bts-optimization" class="jgrid"></table>
		
		</div>
	</div>
	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<style>
.datagrid-header-rownumber { width:50px; }
.datagrid-cell-rownumber { width:50px; }
select { width:auto; }
</style>

<script>
var GRID_BTS_OPTIMIZATION = {
	params: {
		cmd: 'GET_BTS_OPTIMIZ',
		query: ''
	},
	loadData: function() {
		$('#dg-bts-optimization').datagrid({
			url: '<?php echo site_url('/site_info/bts_optimization') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 15,
			pageList: [15,30,40,50,100,200,300,400,500,1000],
			columns:[[
				{ field: 'site_id', checkbox: true },
				{ field: 'site_code', title: 'Site ID', width: 100, fixed: true, sortable: true },
				{ field: 'site_code_old', title: 'Site ID Ref.', width: 150, fixed: true, sortable: true },
				{ field: 'vendor_id', title: 'Vendor ID', width: 100, fixed: true, sortable: true, sortable: true },
				{ field: 'site_name', title: 'Site Name', width: 200, sortable: true },
				{ field: 'region_name', title: 'Region', width: 150, sortable: true },
				{ field: 'number_of_visit', title: 'Number of Visit', width: 100, align: 'center', fixed: true, sortable: true },
				{ field: 'last_visit', title: 'Last Visit', width: 100, align: 'center', fixed: true, sortable: true },
				{ field: 'actions', title: 'Action', width: 100, align: 'center', formatter: function(value, row, index) {
					return '\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_add' )) { ?>\
						<a href="<?php echo site_url('/site_info/bts_optimization/new_visit') ?>/' + row.site_id + '" class="btn btn-success btn-mini" title="New Visit"><i class="icon-plane icon-white"></i> New Visit</a>\
						<?php } ?>\
					';
				} }
			]],
			toolbar: '#tb-bts-optimization',
			pagination: true,
			singleSelect: false,
			fitColumns: true,
			rownumbers: true,
			striped: true,
			height: 490,
			view: detailview,
			detailFormatter: function(index, row) {
				return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';
			},
			onExpandRow: function(index, row) {
				$('#ddv-' + index).datagrid({
					url: '<?php echo site_url('/site_info/bts_optimization') ?>',
					method: 'GET',
					queryParams: { cmd: 'GET_BTS_OPTIMIZ_DETAIL', site_id: row.site_id },
					fitColumns: true,
					rownumbers: true,
					pageSize: 15,
					pagination: true,
					pageList: [15,30,40,50,100,200,300,400,500,1000],
					singleSelect: true,
					striped: true,
					height: 'auto',
					columns:[[
						{ field: 'visit_date', title: 'Visit Date', width: 100, sortable: true, align: 'center' },
						{ field: 'reason', title: 'Reason', width: 100, sortable: true },
						{ field: 'vendor_engineer', title: 'Vendor Engineer', width: 100, sortable: true },
						{ field: 'vendor_rigger', title: 'Vendor Rigger', width: 100, sortable: true },
						{ field: 'sf_engineer', title: 'SF Engineer', width: 100, sortable: true },
						{ field: 'sf_rigger', title: 'SF Rigger', width: 100, sortable: true },
						{ field: 'actions', title: 'Actions', width: 100, align: 'center', formatter: function(value, row, index) {
							return '\
								<a href="javascript:void(0)" onclick="GRID_BTS_OPTIMIZATION.print(\'' + row.visit_id + '\')" class="btn-action glyphicons print btn-success" title="Print"><i></i></a>\
								<?php if ($this->mmenu->cekPermission($menu, 'menu_view' )) { ?>\
								<a href="<?php echo site_url('/site_info/bts_optimization/view') ?>/' + row.visit_id + '" class="btn-action glyphicons eye_open btn-info" title="View"><i></i></a>\
								<?php } ?>\
								\
								<?php if ($this->mmenu->cekPermission($menu, 'menu_edit' )) { ?>\
								<a href="<?php echo site_url('/site_info/bts_optimization/edit') ?>/' + row.visit_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
								<?php } ?>\
								\
								<?php if ($this->mmenu->cekPermission($menu, 'menu_delete' )) { ?>\
								<a href="javascript:GRID_BTS_OPTIMIZATION.delete(\'' + row.visit_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
								<?php } ?>
							';
						} }
					]],
					onResize: function() {
						$('#dg-bts-optimization').datagrid('fixDetailRowHeight', index);
					},
					onLoadSuccess: function() {
						setTimeout(function() {
							$('#dg-bts-optimization').datagrid('fixDetailRowHeight', index);
						}, 0);
					}
				});
				
				$('#dg-bts-optimization').datagrid('fixDetailRowHeight', index);
			}
		});
	},
	search: function() {
		this.params.query = document.getElementById('query').value;
		this.loadData();
		document.getElementById('query').select();
	},
	delete: function(id) {
		coolConfirmation('Are you sure you want to delete?', function() {
			GRID_BTS_OPTIMIZATION.params.cmd = 'DELETE_VISIT';
			GRID_BTS_OPTIMIZATION.params.visit_id = id;
			$.ajax({
				url: '<?php echo site_url('/site_info/bts_optimization') ?>',
				type: 'GET',
				data: GRID_BTS_OPTIMIZATION.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_BTS_OPTIMIZATION.params.cmd = 'GET_BTS_OPTIMIZ';
						GRID_BTS_OPTIMIZATION.loadData();
					}
				}
			});
		});
	},
	print: function(visitId) {
		var w = 850;
		var h = 560;
		var x = parseInt((window.screen.availWidth - w) / 2);
		var y = parseInt(((window.screen.availHeight - h) / 2) - 50);
		
		var w = window.open('<?php echo site_url('/site_info/bts_optimization') ?>?cmd=PRINT&visitId=' + visitId, 'map', 'height=' + h + ',width=' + w + ',left=' + x + ',top=' + y + ',scrollbars=no');
		w.focus();
		
		return false;
	},
	export: function() {
		var rows = $('#dg-bts-optimization').datagrid('getSelections');
		var ids = [];
		for (var i = 0; i < rows.length; i++) {
			ids[i] = rows[i].site_id;
		}
		if (rows.length > 0) {
			window.location.href = '<?php echo site_url('/export/site_info/summary_list_bts_optimization') ?>?site_ids=' + ids;
		} else {
			coolNotification('Please select row you want to export.', 'error');
		}
	},
	init: function() {
		this.params.query = document.getElementById('query').value;
		this.loadData();
		document.getElementById('query').select();
		
		document.getElementById('query').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_BTS_OPTIMIZATION.search();
				this.focus();
			}
		};
		
		document.getElementById('year').onchange = function() {
			GRID_BTS_OPTIMIZATION.params.year = this.value;
			GRID_BTS_OPTIMIZATION.loadData();
		};
		
		document.getElementById('month').onchange = function() {
			GRID_BTS_OPTIMIZATION.params.month = this.value;
			GRID_BTS_OPTIMIZATION.loadData();
		};
	}
};

/* Initialize */

$(function() {
	GRID_BTS_OPTIMIZATION.init();
	
	/* $('#bts-optim-export').fancybox({
		type: 'ajax',
		ajax: { data: { cmd: 'export' } },
		closeBtn: true
	}); */
});

TEXT.clearAutoComplete();
mainMenu();
</script>