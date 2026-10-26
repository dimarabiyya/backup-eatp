<div class="innerLR">

	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Assignment SSH - Cluster</h4>
			
			<a href="<?php echo site_url('/preventive/assignment_ssh/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add</a>
		</div>
		
		<div class="widget-body">
		
			<div id="tb-assignment-ssh" class="form-search">

				<div class="input-append">
					<?php echo form_input('q', '', 'id="q" class="input-medium search-query" placeholder="Search..." autocomplete="off"') ?>
					<button class="btn" onclick="return ASSIGNMENT_SSH.search()"><i class="icon-search"></i></button>
				</div>
			</div>
			
			<table id="dg-assignment-ssh"></table>
		
		</div>
	</div>
	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script>
var ASSIGNMENT_SSH = {
	params: {
		cmd: 'get_assignment_ssh_list',
		query: ''
	},
	loadData: function() {
		$('#dg-assignment-ssh').datagrid({
			url: '<?php echo site_url('/preventive/assignment_ssh') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			//frozenColumns: [[{ field: 'chkData', checkbox: true }]],
			columns:[[
				{ field: 'user_id', hidden: true },
				{ field: 'user_name', title: 'User Name', width: 100, sortable: true },
				{ field: 'sites', title: 'Cluster(s)', width: 150, sortable: false, formatter: function(value, row, index) {
					if (row.clusters != undefined) return row.clusters.join(', ');
				} },
				{ field: 'actions', title: 'Actions', width: 80, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<a href="<?php echo site_url('/preventive/assignment_ssh/edit') ?>/' + row.user_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<a href="javascript:ASSIGNMENT_SSH.delete(\'' + row.user_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
					';
				} }
			]],
			iconCls: 'icon-save',
			toolbar: '#tb-assignment-ssh',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			nowrap: false,
			width: 'auto',
			height: 'auto',
			onLoadSuccess: function(data) {}
		});
	},
	search: function() {
		this.params.query = document.getElementById('q').value;
		this.loadData();
		document.getElementById('q').select();
		
		return false;
	},
	delete: function(id) {
		coolConfirmation('Are you sure you want to delete?', function() {
			ASSIGNMENT_SSH.params.cmd = 'delete_assignment';
			ASSIGNMENT_SSH.params.id = id;
			$.ajax({
				url: '<?php echo site_url('/preventive/assignment_ssh') ?>',
				type: 'GET',
				data: ASSIGNMENT_SSH.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						ASSIGNMENT_SSH.params.cmd = 'get_assignment_ssh_list';
						ASSIGNMENT_SSH.loadData();
					}
				}
			});
		});
	},
	init: function() {
		this.params.query = document.getElementById('q').value;
		this.loadData();
		document.getElementById('q').select();
		
		document.getElementById('q').onkeydown = function(e) {
			if (e.keyCode == 13) {
				ASSIGNMENT_SSH.search();
			}
		};
	}
};

$(function() {
	ASSIGNMENT_SSH.init();
});
</script>