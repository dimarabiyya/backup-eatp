<div class="widget">
	<div class="widget-head"><h4 class="heading">Choose Site</h4>

	</div>
	
	<div class="widget-body">	
		<div>
			
			<div class="input-append pull-right">
				<?php echo form_input('q', '', 'id="q" class="input-medium" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" type="button" onclick="return lookupSite.search()">Search</button>
			</div>
		</div>
		<table id="gridSite"></table>
		
		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnDataSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	
	</div>
	
</div>

<script>
var lookupSite = {
	params: {
		cmd: 'getJson',
		region : <?php echo $region; ?>,
		query : ''
	},
	loadData: function() {
		$('#gridSite').datagrid({
			url: '<?php echo site_url('po/lookup_site') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'site_id', title: 'ID', width: 100, sortable: true },
				{ field: 'site_name', title: 'Site Name', width: 200, sortable: true },
				{ field: 'site_address', title: 'Address', width: 200, sortable: true, align: 'center' },				
				{ field: 'region_name', title: 'Region', width: 250, sortable: true, align: 'center' },
				{ field: 'remarks', title: 'Remarks', width: 250, sortable: true, align: 'center'}				
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
				lookupSite.search();
			}
		};
	}
	
};
$(function() {
	lookupSite.init();
});
</script>