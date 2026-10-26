<div class="innerLR">
	
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Season Name Data</h4>
			
			<div class="btn-heading-group">
				<a href="<?php echo site_url('/site_info/season/add_season_name') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
				<a href="<?php echo site_url('/site_info/season') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
			</div>
		</div>
		
		<div class="widget-body">
		
			<div id="tb-season-name">
				<div class="form-search">
					<div class="input-append">
						<?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?>
						<button id="btnSearch" class="btn" onclick="GRID_SEASON_NAME.search()"><i class="icon-search"></i></button>
					</div>
				</div>
			</div>
			
			<table id="dg-season-name" class="jgrid"></table>
		
		</div>
	</div>
	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<script>
var GRID_SEASON_NAME = {
	params: {
		cmd: 'get_season_name',
		query: ''
	},
	loadData: function() {
		$('#dg-season-name').datagrid({
			url: '<?php echo site_url('/site_info/season/season_name') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			pageList: [10,20,30,40,50,100],
			columns:[[
				{ field: 'column_name', title: 'Season Name', width: 150, sortable: true, formatter: function(value, row, index) {
					return ucwords(value.replace('_', ' '));
				} },
				{ field: 'actions', title: 'Actions', width: 100, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<a href="<?php echo site_url('/site_info/season/edit_season_name') ?>/' + row.column_name + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<a href="javascript:GRID_SEASON_NAME.delete(\'' + row.column_name + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
					';
				} }
			]],
			toolbar: '#tb-season_name',
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
			GRID_SEASON_NAME.params.cmd = 'delete_season_name';
			GRID_SEASON_NAME.params.id = id;
			$.ajax({
				url: '<?php echo site_url('/site_info/season/season_name') ?>',
				type: 'GET',
				data: GRID_SEASON_NAME.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_SEASON_NAME.params.cmd = 'get_season_name';
						GRID_SEASON_NAME.loadData();
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
				GRID_SEASON_NAME.search();
				this.focus();
			}
		};
	}
};

function ucwords(str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

$(function() {
	GRID_SEASON_NAME.init();
});
</script>