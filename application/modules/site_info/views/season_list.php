<div class="innerLR">
	
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Site Season Data</h4>
			
			<div class="btn-heading-group">
				<?php if ($this->mmenu->cekPermission($menu, 'menu_add')) { ?>
				<a href="<?php echo site_url('/site_info/season/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
				<?php } ?>
				
				<a href="<?php echo site_url('/site_info/season/season_name') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons list pull-right"><i></i> Season Name</a>
    <a id="btn_export" href="<?php echo site_url('/export/site_info/season_list') ?>" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
          
        
        	</div>
		</div>
		
		<div class="widget-body">
		
			<div id="tb-site-season">
				<div class="form-search">
					<div class="input-append">
						<?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?>
						<button id="btnSearch" class="btn" onclick="GRID_SITE_SEASON.search()"><i class="icon-search"></i></button>
					</div>
				</div>
			</div>
			
			<table id="dg-site-season" class="jgrid"></table>
		
		</div>
	</div>
	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<style>
.datagrid-header-rownumber { width:50px; }
.datagrid-cell-rownumber { width:50px; }
</style>

<script>
var GRID_SITE_SEASON = {
	params: {
		cmd: 'get_site_season',
		query: ''
	},
	loadData: function() {
		$('#dg-site-season').datagrid({
			url: '<?php echo site_url('/site_info/season') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			pageList: [10,20,30,40,50,100],
			columns:[[
				{ field: 'site_code', title: 'Site ID', width: 150, sortable: true },
				<?php
				foreach ($fields as $field) {
					if ($field != 'site_id') {
						?>
						{ field: '<?php echo $field ?>', title: '<?php echo ucwords(str_replace('_', ' ', $field)) ?>', width: 150, sortable: true },
						<?php
					}
				}
				?>
				
				{ field: 'actions', title: 'Actions', width: 100, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_view' )) { ?>\
						<a href="<?php echo site_url('/site_info/season/view') ?>/' + row.site_id + '" class="btn-action glyphicons eye_open btn-info" title="View"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_edit' )) { ?>\
						<a href="<?php echo site_url('/site_info/season/edit') ?>/' + row.site_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_delete' )) { ?>\
						<a href="javascript:GRID_SITE_SEASON.delete(\'' + row.site_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
						<?php } ?>\
					';
				} }
			]],
			toolbar: '#tb-site-season',
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto',
		});
	},
	search: function() {
		this.params.query = document.getElementById('query').value;
		this.loadData();
		document.getElementById('query').select();
	},
	delete: function(id) {
		coolConfirmation('Are you sure you want to delete?', function() {
			GRID_SITE_SEASON.params.cmd = 'delete_site_season';
			GRID_SITE_SEASON.params.id = id;
			$.ajax({
				url: '<?php echo site_url('/site_info/season') ?>',
				type: 'GET',
				data: GRID_SITE_SEASON.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_SITE_SEASON.params.cmd = 'get_site_season';
						GRID_SITE_SEASON.loadData();
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
				GRID_SITE_SEASON.search();
				this.focus();
			}
		};
	}
};

$(function() {
	GRID_SITE_SEASON.init();
});
</script>