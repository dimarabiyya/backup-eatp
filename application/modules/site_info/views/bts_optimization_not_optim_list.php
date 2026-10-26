<div class="innerLR">
	
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> BTS Not Optim Data</h4>
			
			<div class="btn-heading-group">
				<a href="<?php echo site_url('/site_info/bts_optimization') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
				<a id="btn_export" href="javascript:void(0)" onclick="GRID_BTS_OPTIMIZATION.export()" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
			</div>
		</div>
		
		<div class="widget-body">
		
			<div id="tb-bts-optimization">
				<table>
					<tr>
						<td>
							<div class="form-search">
								<div class="input-append">
									<?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?>
									<button id="btnSearch" class="btn" onclick="GRID_BTS_OPTIMIZATION.search()"><i class="icon-search"></i></button>
								</div>
							</div>
						</td>
						<!--
						<td>
							<div class="form-inline" style="padding-left:20px">
								<a href="javascript:void(0)" class="btn btn-icon glyphicons inbox_out btn-warning" onclick="GRID_BTS_OPTIMIZATION.export()"><i></i> Export</a>
							</div>
						</td>
						-->
					</tr>
				</table>
			</div>
			
			<table id="dg-bts-optimization" class="jgrid"></table>
			
			<div class="separator top"></div>
			<div class="alert" style="margin:0px">This data list shown only has RF Antenna.</div>
		
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
		cmd: 'GET_BTS_NOT_OPTIMIZ',
		query: ''
	},
	loadData: function() {
		$('#dg-bts-optimization').datagrid({
			url: '<?php echo site_url('/site_info/bts_optimization') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			pageList: [10,20,30,40,50,100,200,300,400,500,1000],
			columns:[[
				{ field: 'site_id', checkbox: true },
				{ field: 'site_code', title: 'Site ID', width: 150, fixed: true, sortable: true },
				{ field: 'site_code_old', title: 'Site ID Ref.', width: 150, fixed: true, sortable: true },
				{ field: 'vendor_id', title: 'Vendor ID', width: 150, fixed: true, sortable: true, sortable: true },
				{ field: 'site_name', title: 'Site Name', width: 200, sortable: true },
				{ field: 'region_name', title: 'Region', width: 150, sortable: true }
			]],
			toolbar: '#tb-bts-optimization',
			pagination: true,
			singleSelect: false,
			fitColumns: true,
			rownumbers: true,
			striped: true,
			height: 347,
			view: detailview,
			detailFormatter: function(index, row) {
				return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';
			},
			onExpandRow: function(index, row) {
				$('#ddv-' + index).datagrid({
					url: '<?php echo site_url('/site_info/bts_optimization') ?>',
					method: 'GET',
					queryParams: { cmd: 'GET_BTS_NOT_OPTIMIZ_DETAIL', site_id: row.site_id },
					fitColumns: false,
					rownumbers: true,
					pageSize: 10,
					singleSelect: true,
					striped: true,
					height: 'auto',
					columns:[[
						{ field: 'type', title: 'Type', width: 100, sortable: true, align: 'left' },
						{ field: 'model', title: 'Model', width: 200, sortable: true, align: 'left' },
						{ field: 'brand', title: 'Brand', width: 120, sortable: true, align: 'left' },
						{ field: 'cell', title: 'Cell', width: 100, sortable: true, align: 'left' },
						{ field: 'band', title: 'Band', width: 100, sortable: true, align: 'left' },
						{ field: 'hbwd', title: 'HBWD', width: 100, sortable: true, align: 'center' },
						{ field: 'vbwd', title: 'VBWD', width: 100, sortable: true, align: 'center' },
						{ field: 'dimension', title: 'Dimension (LxWxH)', width: 150, sortable: true, align: 'center', formatter: function(value, row, index) {
							return number_format(row.dimension_long, 0) + 'x' + number_format(row.dimension_width, 0) + 'x' + number_format(row.dimension_height, 0) + ' mm';
						} },
						{ field: 'weight', title: 'Weight', width: 100, sortable: true, align: 'center' },
						{ field: 'height', title: 'Height', width: 100, sortable: true, align: 'center' },
						{ field: 'azimuth', title: 'Azimuth', width: 100, sortable: true, align: 'center' },
						{ field: 'electrical_tilt', title: 'Electrical Tilt', width: 100, sortable: true, align: 'center' },
						{ field: 'mechanical_tilt', title: 'Mechanical Tilt', width: 100, sortable: true, align: 'center' }
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
	export: function() {
		var rows = $('#dg-bts-optimization').datagrid('getSelections');
		var ids = [];
		for (var i = 0; i < rows.length; i++) {
			ids[i] = rows[i].site_id;
		}
		if (rows.length > 0) {
			window.location.href = '<?php echo site_url('/export/site_info/summary_list_bts_not_optimization') ?>?site_ids=' + ids;
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
	}
};

$(function() {
	GRID_BTS_OPTIMIZATION.init();
});
</script>