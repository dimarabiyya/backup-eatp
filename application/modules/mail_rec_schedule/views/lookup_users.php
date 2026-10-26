<div class="widget">
	<div class="widget-head"><h4 class="heading">User List</h4>

	</div>
	
	<div class="widget-body">	
		<div>
			
			<div class="input-append pull-right">
				<?php echo form_input('q', '', 'id="q" class="input-medium" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" type="button" onclick="return lookupUsers.search()">Search</button>
			</div>
		</div>
		
		<table id="gridUserApproval"></table>
		
		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnDataSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	
	</div>
	
</div>

<script>
var lookupUsers = {
	params: {
		cmd: 'getJson',
		query : ''
	},
	loadData: function() {
		$('#gridUserApproval').datagrid({
			url: '<?php echo site_url('approval/lookup_user') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'user_id', title: 'User ID', width: 200, sortable: true },
				{ field: 'user_name', title: 'Name', width: 200, sortable: true, align: 'center' },				
				{ field: 'email', title: 'E-mail', width: 250, sortable: true, align: 'center' },
				{ field: 'mobile_number', title: 'Telephone No', width: 250, sortable: true, align: 'center'}				
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
				lookupUsers.search();
			}
		};
	}
	
};
$(function() {
	lookupUsers.init();
});
</script>