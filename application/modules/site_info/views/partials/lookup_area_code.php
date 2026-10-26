<style>
.form-actions { padding:10px 0 10px !important; }
</style>

<div class="widget">
	<div class="widget-head"><h4 class="heading">Area Code</h4></div>
	
	<div class="widget-body">
	
		<div id="tb-area-code" class="form-search">
			<div class="input-append">
				<?php echo form_input('q', '', 'id="q" class="input-medium search-query" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" onclick="return AREA_CODE.search()"><i class="icon-search"></i></button>
			</div>
		</div>
		
		<table id="dg-area-code"></table>
	
		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnDataSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	
	</div>
	
</div>

<script>
var AREA_CODE = {
	params: {
		cmd: 'get_lookup_area_code',
		act: 'get_data',
		query: ''
	},
	loadData: function() {
		$('#dg-area-code').datagrid({
			url: '<?php echo site_url('/site_info/plan_info') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			frozenColumns: [[{ field: 'chkData', checkbox: true }]],
			columns:[[
				{ field: 'area_id', hidden: true },
				{ field: 'area_code', title: 'Area Code', width: 120, sortable: true },
				{ field: 'description', title: 'Area Name', width: 120, sortable: true }
			]],
			iconCls: 'icon-save',
			toolbar: '#tb-area-code',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			width: 740,
			height: 'auto',
			onLoadSuccess: function(data) {
				for (var i = 0; i < data.rows.length; i++) {
					if (data.rows[i].area_id == document.getElementsByName('<?php echo $this->input->get('e') ?>')[0].value) {
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
		document.getElementById('q').select();
		
		document.getElementById('q').onkeydown = function(e) {
			if (e.keyCode == 13) {
				AREA_CODE.search();
			}
		};
	}
};

$(function() {
	AREA_CODE.init();
});
</script>