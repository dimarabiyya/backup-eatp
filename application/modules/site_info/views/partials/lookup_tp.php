<style>
.form-actions { padding:10px 0 10px !important; }
</style>

<div class="widget">
	<div class="widget-head"><h4 class="heading">Tower Provider List</h4></div>
	
	<div class="widget-body">
	
		<div id="tb-tp" class="form-search">
			<div class="input-append">
				<?php echo form_input('q', '', 'id="q" class="input-medium search-query" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" onclick="return lookupTP.search()"><i class="icon-search"></i></button>
			</div>
		</div>
		
		<table id="dg-tp"></table>
	
		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnDataSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	
	</div>
	
</div>

<script>
var lookupTP = {
	params: {
		cmd: 'get_lookup_tower_provider',
		act: 'get_data',
		query: ''
	},
	loadData: function() {
		$('#dg-tp').datagrid({
			url: '<?php echo site_url('/site_info') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			frozenColumns: [[{ field: 'chkData', checkbox: true }]],
			columns:[[
				{ field: 'tp_id', hidden: true },
				{ field: 'tp_code', title: 'TP Site ID', width: 120, sortable: true },
				{ field: 'company_name', title: 'TP Name', width: 120, sortable: true },
				{ field: 'tpgroup_name', title: 'TP Group Name', width: 200, sortable: true },
				{ field: 'site_category', title: 'Site Category', width: 100, sortable: true },
				{ field: 'address', title: 'Address', width: 500, sortable: true },
				{ field: 'province', title: 'Province', width: 100, sortable: true },
				{ field: 'city', title: 'City', width: 100, sortable: true },
				{ field: 'region', title: 'Region', width: 120, sortable: true },
			]],
			iconCls: 'icon-save',
			toolbar: '#tb-tp',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: false,
			rownumbers: true,
			width: 740,
			height: 'auto',
			onLoadSuccess: function(data) {
				for (var i = 0; i < data.rows.length; i++) {
					if (data.rows[i].tp_id == document.getElementsByName('<?php echo $this->input->get('e') ?>')[0].value) {
						$(this).datagrid('checkRow', i);
					}
				}
			},
			onDblClickCell: function(index, field, value) {
				document.getElementById('btnDataSelectedOK').click();
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
				lookupTP.search();
			}
		};
	}
};

$(function() {
	lookupTP.init();
});
</script>