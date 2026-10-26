<!--
<div class="heading-buttons">
	<h3>Tower Provider</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/tower_provider/add') ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add New</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">
	
	<div class="widget">	
		
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Tower Provider Data</h4>
			<div class="btn-heading-group">
            	<?php if ($this->mmenu->cekPermission($menu, 'menu_add')) { ?>
                <a href="<?php echo site_url('/site_info/tower_provider/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
			    <a href="<?php echo site_url('/site_info/tower_provider/import') ?>" class="btn btn-success btn-mini btn-heading btn-icon glyphicons inbox_in pull-right"><i></i> Import</a>
				<?php } ?>
                <a id="btn_export" href="<?php echo site_url('/export/site_info/tower_provider') ?>" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
            </div>
		</div>
		
		<div class="widget-body">
			<div id="tb-tp" style="padding: 10px 10px 0px 10px; height:auto">
                <div class="input-prepend">
					<span class="add-on">TP</span>
					<?php
					$options = array('' => 'All');
					$lookup = $this->core->get_rows(array('company_name', 'company_id'), 't_mtr_tp_company', NULL, 'company_name');
					foreach ($lookup as $lo) {
						$options[trim($lo['company_id'])] = $lo['company_name'];
					}
					echo form_dropdown('tpName', $options,"","style='width:100px'");
					?>
                </div>
                <div class="input-prepend">
					<span class="add-on">Status</span>
					<?php
					$options = array('' => 'All');
					$lookup = $this->core->get_lookup('tower_status');
					foreach ($lookup as $lo) {
						$options[$lo['id']] = $lo['desc'];
					}
					echo form_dropdown('tpStatus', $options,"","style='width:140px'");
					?>
                </div>
				<!--
                <div class="input-prepend">
					<span class="add-on">Region</span>
					<?php
					$options = array('' => 'All');
					$results = $this->core->get_rows(array('region_id', 'region_name'), 't_mtr_region', array('status' => 1), 'region_name');
					foreach ($results as $res) {
						$options[$res['region_id']] = $res['region_name'];
					}
					echo form_dropdown('tpRegion', $options);
					?>
                </div>
				-->
	            <div class="input-control pull-right">
					<div class="form-inline" style="padding-left:10px; text-align:right;"><span id="totalTP" class="input-label"></span></div>
	            </div>
	            <div class="input-append pull-right">
					<?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?>
					<button id="btnSearch" class="btn" onclick="GRID_TP.search()">Search</button>
	            </div>

			</div>		

			
			<table id="grid-tp" class="jgrid"></table>
		
		</div>
		
	</div>

</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<style>
/* .widget .widget-body { padding:20px 15px; } */
.datagrid-header-rownumber { width:50px; }
.datagrid-cell-rownumber { width:50px; }
select { width:auto; }
</style>

<script>
var GRID_TP = {
	params: {
		cmd: 'GET_DATAGRID_TP',
		query: ''
	},
	loadData: function() {
		$('#grid-tp').datagrid({
			url: '<?php echo site_url('/site_info/tower_provider') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 15,
			pageList: [15, 20, 30, 40, 50, 100, 200, 300, 400, 500, 1000],
			columns:[[
				{ field: 'tp_id', hidden: true },
				{ field: 'site_category', title: 'Site Category', width: 100, sortable: true },
				{ field: 'company_name', title: 'TP Name', width: 200, sortable: true },
				{ field: 'tp_site_name', title: 'TP Site Name', width: 200, sortable: true },
				{ field: 'tp_code', title: 'TP ID', width: 150, sortable: true },
				{ field: 'status', title: 'Status', width: 100, sortable: true, formatter: function(value, row, index) { return row.tp_status != null ? row.tp_status : value; } },				
				//{ field: 'region_name', title: 'Region', width: 150, sortable: true, formatter: function(value, row, index) { return row.region_name != null ? row.region_name : value; } },
				{ field: 'province', title: 'Province', width: 150, sortable: true, formatter: function(value, row, index) { return row.province_name != null ? row.province_name : value; } },
				{ field: 'city', title: 'City', width: 150, sortable: true, formatter: function(value, row, index) { return row.city_name != null ? row.city_name : value; } },
				{ field: 'latitude', title: 'Latitude', width: 120, sortable: true, align: 'right' },
				{ field: 'longitude', title: 'Longitude', width: 120, sortable: true, align: 'right' },
				{ field: 'site_type', title: 'Site Type', width: 100, sortable: true, formatter: function(value, row, index) { return row.site_type_name != null ? row.site_type_name : value; } },
				{ field: 'tower_type', title: 'Tower Type', width: 100, sortable: true, formatter: function(value, row, index) { return row.tower_type_name != null ? row.tower_type_name : value; } },
				{ field: 'building_height_mtr', title: 'Bulding Height (M)', width: 120, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2); } },
				{ field: 'tower_height_mtr', title: 'Tower Height (M)', width: 120, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2); } },
				{ field: 'shareable_status', title: 'Shareable Status', width: 150, sortable: true },
				//{ field: 'partner_name', title: 'Partner', width: 150, sortable: true },
				{ field: 'candidate_status', title: 'Candidate Status', width: 130, sortable: true, align: 'center', formatter: function(value, row, index) {
					if (value == 'Candidate') {
						return '<span class="badge">' + value + '</span>';
					} else if (value == 'Verified') {
						return '<span class="badge badge-warning">' + value + '</span>';
					} else if (value == 'Selected Candidate') {
						return '<span class="badge badge-success">' + value + '</span>';
					}
				} },
				{ field: 'actions', title: 'Actions', width: 100, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_view')) { ?>\
						<a href="<?php echo site_url('/site_info/tower_provider/view') ?>/' + row.tp_id + '" class="btn-action glyphicons eye_open btn-info" title="View"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_edit')) { ?>\
						<a href="<?php echo site_url('/site_info/tower_provider/edit') ?>/' + row.tp_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_delete')) { ?>\
						<a href="javascript:GRID_TP.delete(\'' + row.tp_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
						<?php } ?>
					';
				} }
			]],
			toolbar: '#tb-tp',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: false,
			nowrap: true,
			rownumbers: true,
			height: 'auto',
			onLoadSuccess: function(data) {
				document.getElementById('totalTP').innerHTML = 'Total TP: <span class="badge badge-success">' + data.total_tp + '</span>';
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
			GRID_TP.params.cmd = 'DELETE_TP';
			GRID_TP.params.id = id;
			$.ajax({
				url: '<?php echo site_url('/site_info/tower_provider') ?>',
				type: 'GET',
				data: GRID_TP.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_TP.params.cmd = 'GET_DATAGRID_TP';
						GRID_TP.loadData();
					}
				}
			});
		});
	},
	init: function() {
		this.params.query = document.getElementById('query').value;
		this.loadData();
		document.getElementById('query').select();
		
		document.getElementById('query').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_TP.search();
			}
		};
		
		document.getElementsByName('tpName')[0].onchange = function() {
			GRID_TP.params.tp = this.value;
			GRID_TP.loadData();
		};
		
		document.getElementsByName('tpStatus')[0].onchange = function() {
			GRID_TP.params.status = this.value;
			GRID_TP.loadData();
		};
		
		/*
		document.getElementsByName('tpRegion')[0].onchange = function() {
			GRID_TP.params.region = this.value;
			GRID_TP.loadData();
		};
		*/
	}
};

$(function() {
	GRID_TP.init();
});

TEXT.clearAutoComplete();
mainMenu();
</script>