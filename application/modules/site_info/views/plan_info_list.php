<!--
<div class="heading-buttons">
	<h3>Plan Info</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/plan_info/add') ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add New</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">
	
	<div class="widget">	
		
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Plan Data</h4>
			<div class="btn-heading-group">
    			<?php if ($this->mmenu->cekPermission($menu, 'menu_add')) { ?>
    			<a href="<?php echo site_url('/site_info/plan_info/add') ?>" class="btn btn-primary btn-mini btn-icon btn-heading glyphicons circle_plus pull-right"><i></i> Add New</a>
    			<?php } ?>
              <a id="btn_export" href="<?php echo site_url('/export/site_info/plan') ?>" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
            </div>
		</div>
		
		<div class="widget-body">
		
			<div id="tb-plan"  style="padding: 10px 10px 0px 10px; height:auto">
				<div class="input-append">
					<?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?>
					<button id="btnSearch" class="btn" onclick="GRID_PLAN.search()">Search</button>
				</div>
			</div>
			
			<table id="grid-plan" class="jgrid"></table>
		
		</div>
		
	</div>

</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script>
var GRID_PLAN = {
	params: {
		cmd: 'GET_DATAGRID_PLAN',
		query: ''
	},
	loadData: function() {
		$('#grid-plan').datagrid({
			url: '<?php echo site_url('/site_info/plan_info') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 100, 200, 500],
			columns:[[
				{ field: 'nom_site_id', hidden: true },
				{ field: 'nom_site_code', title: 'Site ID', width: 150, sortable: true },
				{ field: 'nom_site_name', title: 'Site Name', width: 150, sortable: true },
				{ field: 'nom_latitude', title: 'Latitude', width: 150, sortable: true },
				{ field: 'nom_longitude', title: 'Longitude', width: 150, sortable: true },
				{ field: 'region_name', title: 'Region', width: 150, sortable: true },
				{ field: 'actions', title: 'Actions', width: 100, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_view' )) { ?>\
						<a href="<?php echo site_url('/site_info/plan_info/view') ?>/' + row.nom_site_id + '" class="btn-action glyphicons eye_open btn-info" title="View"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_edit' )) { ?>\
						<a href="<?php echo site_url('/site_info/plan_info/edit') ?>/' + row.nom_site_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_delete' )) { ?>\
						<a href="javascript:GRID_PLAN.delete(\'' + row.nom_site_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
						<?php } ?>\
					';
				} }
			]],
			toolbar: '#tb-plan',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
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
		this.params.cmd = 'DELETE_PLAN';
		this.params.id = id;
		var params = this.params;
			
		coolConfirmation('Are you sure you want to delete?', function() {
			$.ajax({
				url: '<?php echo site_url('/site_info/plan_info') ?>',
				type: 'GET',
				data: params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_PLAN.params.cmd = 'GET_DATAGRID_PLAN';
						GRID_PLAN.loadData();
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
				GRID_PLAN.search();
			}
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

$(function() {
	GRID_PLAN.init();
});

clearAutoComplete.init();
mainMenu();
</script>