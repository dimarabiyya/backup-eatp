	<!--
<div class="heading-buttons">
	<h3>Sites</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/add') ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add New</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">
	
	<div class="widget">

		<div class="widget-head heading-outer">
			<h4 class="heading glyphicons list"><i></i> Sites Data</h4>
			<div class="btn-heading-group">
                            <?php if ($this->mmenu->cekPermission($menu, 'menu_add')) { ?>
                                <a href="<?php echo site_url('/site_info/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
                                <?php } ?>
                            <a id="btn-export" href="<?php echo site_url('/export/site_info/sites') ?>" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
                        </div>
		</div>
		
		<div class="widget-body">


			<div id="tb-sites" style="padding: 10px 10px 0px 10px; height:auto">
                <div class="input-prepend">
                  <span class="add-on">Region</span>
								<?php
								$region = array();
								foreach ($this->session->userdata['user_region'] as $reg) {
									if ($reg['region_id'] != '') {
										$region[] = $reg['region_id'];
									}
								}
								
								$options = array('' => 'All');
								$results = $this->db->query("SELECT region_id, region_name FROM t_mtr_region WHERE status = 1 AND region_id IN (".implode(', ', $region).")")->result_array();
								foreach ($results as $res) {
									$options[$res['region_id']] = $res['region_name'];
								}
								echo form_dropdown('siteRegion', $options);
								?>
                </div>
                <div class="input-prepend">
                  <span class="add-on">Status</span>
								<?php
								$options = array('' => 'All', 1 => 'Active', 0 => 'Inactive');
								echo form_dropdown('siteStatus', $options);
								?>
                </div>

            <div class="input-append pull-right">
              <?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?> 
              <button class="btn" onclick="GRID_SITES.search()" >Search</button>
            </div>

			</div>


			
			<table id="grid-sites" class="jgrid"></table>
		
		</div>
	</div>
	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<style>
.datagrid-header-rownumber { width:50px; }
.datagrid-cell-rownumber { width:50px; }
select { width:auto; }
</style>

<script>
var GRID_SITES = {
	params: {
		cmd: 'GET_DATAGRID_SITES',
		query: ''
	},
	loadData: function() {
		$('#grid-sites').datagrid({
			url: '<?php echo site_url('/site_info') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 100, 200, 500],
			columns:[[
				{ field: 'site_code', title: 'Site ID', width: 150, sortable: true },
				{ field: 'site_code_old', title: 'Site ID Ref.', width: 150, sortable: true },
				{ field: 'site_name', title: 'Site Name', width: 150, sortable: true },
				{ field: 'vendor_id', title: 'Vendor ID', width: 150, sortable: true },
				{ field: 'region_name', title: 'Region', width: 150, sortable: true },
				{ field: 'tp_name', title: 'TP Name', width: 150, sortable: true },
				{ field: 'latitude', title: 'Latitude', width: 150, sortable: true, align: 'right' },
				{ field: 'longitude', title: 'Longitude', width: 150, sortable: true, align: 'right' },
				{ field: 'site_last_status', title: 'On Air / Off Air', width: 100, sortable: true, align: 'center' },
				/*
				{ field: 'status', title: 'Status', width: 100, sortable: true, align: 'center', formatter: function(value, row, index) {
					if (value != null) {
						if (value.toUpperCase() == 'ACTIVE') {
							return '<span class="badge badge-success">' + value + '</span>';
						} else if (value.toUpperCase() == 'NOM') {
							return '<span class="badge badge-warning">' + value + '</span>';
						}
					}
				} },
				*/
				{ field: 'site_status', title: 'Status', width: 100, sortable: true, align: 'center', formatter: function(value, row, index) {
					if (value != null) {
						if (value == 'Active') {
							return '<span class="badge badge-success">Active</span>';
						} else if (value == 'Inactive') {
							return '<span class="badge badge-default">Inactive</span>';
						}
					}
				} },
				{ field: 'actions', title: 'Actions', width: 100, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_view' )) { ?>\
						<a href="<?php echo site_url('/site_info/view') ?>/' + row.site_id + '" class="btn-action glyphicons eye_open btn-info" title="View"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_edit' )) { ?>\
						<a href="<?php echo site_url('/site_info/edit') ?>/' + row.site_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_delete' )) { ?>\
						<a href="javascript:GRID_SITES.delete(\'' + row.site_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
						<?php } ?>\
					';
				} }
			]],
			toolbar: '#tb-sites',
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
		this.params.cmd = 'DELETE_SITE';
		this.params.id = id;
		var params = this.params;
		
		coolConfirmation('Are you sure you want to delete?', function() {
			$.ajax({
				url: '<?php echo site_url('/site_info') ?>',
				type: 'GET',
				data: params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_SITES.params.cmd = 'GET_DATAGRID_SITES';
						GRID_SITES.loadData();
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
				GRID_SITES.search();
			}
		};
		
		document.getElementsByName('siteRegion')[0].onchange = function() {
			GRID_SITES.params.region = this.value;
			GRID_SITES.loadData();
		};
		
		document.getElementsByName('siteStatus')[0].onchange = function() {
			GRID_SITES.params.status = this.value;
			GRID_SITES.loadData();
		};
	}
};

$(function() {
	GRID_SITES.init();
});

TEXT.clearAutoComplete();
mainMenu();
</script>