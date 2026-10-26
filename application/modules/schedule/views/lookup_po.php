<div class="widget">
	<div class="widget-head"><h4 class="heading">Choose PO No.</h4>

	</div>
	
	<div class="widget-body">	
		<div>
			
			<div class="input-append pull-right">
				<?php echo form_input('q', '', 'id="q" class="input-medium" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" type="button" onclick="return lookupPO.search()">Search</button>
			</div>
		</div>
		
		<table id="gridPo"></table>
		
		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnDataSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	
	</div>
	
</div>

<script>
var lookupPO = {
	params: {
		cmd: 'getJson',
		query : ''
	},
	loadData: function() {
		$('#gridPo').datagrid({
			url: '<?php echo site_url('schedule/lookup_po') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'po_id', title: 'ID', hidden:true,width: 100, sortable: true },
				{ field: 'po_no', title: 'PO NO.', width: 200, sortable: true },
				{ field: 'region_name', title: 'Region', width: 200, sortable: true, align: 'center' },				
				{ field: 'po_date', title: 'PO Date', width: 250, sortable: true, align: 'center' }				
			]],
			iconCls: 'icon-save',
			toolbar: '#tb-users',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			width: 740,
			height: 'auto',
			onLoadSuccess: function(data) {
				dateFormat.init();
			},
			onDblClickCell: function(index, field, value) {
				document.getElementById('btnDataSelectedOK').click();
			}
		});
	},
	search: function(){
		this.params.query = document.getElementById('q').value;
		this.loadData();
		document.getElementById('q').select();
		return false;
	},
	init:function(){
		this.params.query = document.getElementById('q').value;
		
		this.loadData();
		
		document.getElementById('q').onkeydown = function(e) {
			if (e.keyCode == 13) {
				lookupPO.search();
			}
		};
	}
	
};
$(function() {
	lookupPO.init();
});
</script>