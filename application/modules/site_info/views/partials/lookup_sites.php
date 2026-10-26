<style>
.form-actions { padding:10px 0 10px !important; }
</style>

<div class="widget">
	<div class="widget-head"><h4 class="heading">Sites List</h4></div>
	
	<div class="widget-body">
	
		<div id="tb-sites">
			<div class="input-append">
				<?php echo form_input('q', '', 'id="q" class="input-medium search-query" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" onclick="return lookupSites.search()">Search</button>
			</div>
		</div>
		
		<table id="dg-sites"></table>
	
		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnSiteSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	
	</div>
	
</div>

<script>
var lookupSites = {
	params: {
		cmd: 'GET_LOOKUP_SITE_DATA',
		query: '',
		not_appear_siteid: '<?php echo $this->input->get('not_appear_siteid') ?>'
	},
	loadData: function() {
		$('#dg-sites').datagrid({
			url: '<?php echo site_url('/site_info') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			frozenColumns: [[{ field: 'chkData', checkbox: true }]],
			columns:[[
				{ field: 'site_id', hidden: true },
				{ field: 'site_code', title: 'Site ID', width: 120, sortable: true },
				{ field: 'site_code_old', title: 'Site ID Ref.', width: 120, sortable: true },
				{ field: 'site_name', title: 'Site Name', width: 200, sortable: true },
				{ field: 'vendor_id', title: 'Vendor ID', width: 120, sortable: true },
				{ field: 'region_name', title: 'Region', width: 200, sortable: true }
			]],
			iconCls: 'icon-save',
			toolbar: '#tb-sites',
			striped: true,
			pagination: true,
			singleSelect: true,
			//fitColumns: true,
			rownumbers: true,
			width: 740,
			height: 'auto',
			onLoadSuccess: function(data) {
				for (var i = 0; i < data.rows.length; i++) {
					if (data.rows[i].site_id == document.getElementsByName('<?php echo $this->input->get('e') ?>')[0].value) {
						$(this).datagrid('checkRow', i);
					}
				}
			},
			onDblClickCell: function(index, field, value) {
				document.getElementById('btnSiteSelectedOK').click();
			}
		});
	},
	search: function() {
		this.params.query = document.getElementById('q').value;
		this.loadData();
		document.getElementById('q').select();
		
		return false;
	},
	init: function() {
		this.params.query = document.getElementById('q').value;
		this.loadData();
		
		document.getElementById('q').onkeydown = function(e) {
			if (e.keyCode == 13) {
				lookupSites.search();
			}
		};
	}
};

$(function() {
	lookupSites.init();
});
</script>