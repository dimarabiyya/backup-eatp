<div class="innerLR">
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading glyphicons list"><i></i> Tenant</h4>
			
			<div class="btn-heading-group">
				<?php if ($this->mmenu->cekPermission($menu, 'menu_add' )) { ?>
				<a href="<?php echo site_url('/referensi/tenant/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
				<?php } ?>
			</div>
		</div>
		
		<div class="widget-body">
		
			<div id="tb-tenant" style="padding: 10px 10px 0px 10px;">
				<div class="input-append pull-right">
					<?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?>
					<button id="btnSearch" class="btn" onclick="dataList.search()"><i class="icon-search"></i></button>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<table class="jgrid" id="grid-tenant"></table>
		
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
var dataList = {
	
	params: {
		query: ''
	},
	
	loadData: function() {
		$('#grid-tenant').datagrid({
			url: '<?php echo site_url('/referensi/tenant') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'tenant_name', title: 'Name', width: 100, sortable: true },
				{ field: 'actions', title: 'Actions', width: 80, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_edit' )) { ?>\
						<a href="<?php echo site_url('/referensi/tenant/edit') ?>/' + row.tenant_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<?php } ?>\
						\
						<?php if ($this->mmenu->cekPermission($menu, 'menu_delete' )) { ?>\
						<a href="javascript:dataList.delete(\'' + row.tenant_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
						<?php } ?>\
					';
				} }
			]],
			toolbar: '#tb-tenant',
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
		this.params.id = id;
		
		coolConfirmation('Are you sure you want to delete?', function() {
			$.ajax({
				url: '<?php echo site_url('/referensi/tenant/delete') ?>',
				type: 'GET',
				data: dataList.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						coolNotification(json.message, 'success', function() {
							dataList.loadData();
						});
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
				dataList.search();
			}
		};
	}
};

$(function() {
	dataList.init();
});
</script>