<!--<div class="heading-buttons">
	<h3><?php echo lang('heading_title:'.$key) ? lang('heading_title:'.$key): 'Unknown title' ?></h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/preventive/references') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back to Preventive Ref</a>
		<a href="<?php echo $add_url ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add <?php echo lang('heading_title:'.$key) ?></a>
	</div>
</div>
<div class="separator top"></div>-->

<div class="innerLR">
	
	<div class="widget">	
		
		<div class="widget-head">
			<h4 class="heading"><i></i> <?php echo lang('heading_data_title:'.$key) ? lang('heading_data_title:'.$key) : 'Unknown title' ?></h4>
			<div class="buttons pull-right">
				<a href="<?php echo site_url('/preventive/references') ?>" class="btn btn-primary btn-heading btn-icon glyphicons unshare"><i></i> Back to Preventive Ref</a>
				<a href="<?php echo $add_url ?>" class="btn btn-primary btn-heading btn-icon glyphicons circle_plus"><i></i> Add <?php echo lang('heading_title:'.$key) ?></a>
			</div>
		</div>
		
		<div class="widget-body">
		
			<div id="tb-reference" class="form-search">
				<div class="input-append">
					<?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?>
					<button id="btnSearch" class="btn" onclick="GRID_REFERENCE.search()"><i class="icon-search"></i></button>
				</div>
			</div>
			
			<table id="grid-reference"></table>
		
		</div>
		
	</div>

</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script>
var GRID_REFERENCE = {
	params: {
		cmd: 'GET_DATAGRID_REFERENCE',
		query: ''
	},
	loadData: function() {
		$('#grid-reference').datagrid({
			url: document.URL,
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'lookup_id', hidden: true },
				{ field: 'lookup_desc', title: 'Name', width: 100, sortable: true },
				{ field: 'lookup_value', title: 'Value', width: 100, sortable: true, hidden: true },
				{ field: 'order', title: 'Order', width: 100, sortable: true },
				{ field: 'actions', title: 'Actions', width: 100, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<a href="<?php echo site_url('/preventive/reference/index/'.$key.'/edit') ?>/' + row.lookup_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<a href="javascript:GRID_REFERENCE.delete(\'' + row.lookup_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
					';
				} }
			]],
			toolbar: '#tb-reference',
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
		if (confirm('Are you sure you want to delete?')) {
			this.params.cmd = 'DELETE_REFERENCE';
			this.params.id = id;
			$.ajax({
				url: document.URL,
				type: 'GET',
				data: this.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_REFERENCE.params.cmd = 'GET_DATAGRID_REFERENCE';
						GRID_REFERENCE.loadData();
					}
				}
			});
		}
	},
	init: function() {
		this.params.query = document.getElementById('query').value;
		this.loadData();
		document.getElementById('query').select();
		
		document.getElementById('query').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_REFERENCE.search();
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
	GRID_REFERENCE.init();
});

//TEXT.clearAutoComplete();
//siteInfoMenu();
clearAutoComplete.init();
</script>