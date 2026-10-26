<div class="widget">
	<div class="widget-head"><h4 class="heading">Cluster Ops. List</h4></div>
	
	<div class="widget-body">

		<div id="tb-cluster-ops" class="form-search" style="padding: 4px; background-color: #efefef">
			<div class="input-prepend">
				<span class="add-on">Region</span>
				<select name="region" id="regions" style="width: 120px" onchange="return lookupClusterOps.init();">
					<option value=""></option>
					<?php foreach ($regions as $rg) {
						echo '<option value="'.$rg->region_id.'">'.$rg->region_name.'</option>'; 
					} ?>
				</select>
			</div>
			<div class="input-append pull-right">
				<?php echo form_input('q', '', 'id="q" class="input-medium" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" onclick="return lookupClusterOps.search()"><i class="icon-search"></i></button>
			</div>
		</div>
		
		<table id="dg-cluster-ops"></table>
	
		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnDataSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	
	</div>
	
</div>

<script>
var lookupClusterOps = {
	params: {
		cmd: 'get_lookup_cluster_ops',
		act: 'get_data',
		user_id : '<?php echo $user_id; ?>',
		query: {}
	},
	loadData: function() {
		$('#dg-cluster-ops').datagrid({
			url: '<?php echo site_url('/preventive/assignment_ssh') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			frozenColumns: [[{ field: 'chkData', checkbox: true }]],
			columns:[[
				{ field: 'cluster_ops_id', hidden: true },
				{ field: 'region_name', title: 'Region Name', width: 120, sortable: true },
				{ field: 'cluster_name', title: 'Cluster Name', width: 120, sortable: true }
			]],
			iconCls: 'icon-save',
			toolbar: '#tb-cluster-ops',
			striped: true,
			pagination: true,
			singleSelect: false,
			fitColumns: true,
			rownumbers: true,
			width: 740,
			height: 'auto',
			onLoadSuccess: function(data) {
				var s = [];
				$.each($('#cluster-ops tbody tr'), function(){ 
				  s.push( $(this).children().find('input').val() );
				});
				// console.log(s);
				for (var i = 0; i < data.rows.length; i++) {
					data.rows[i];
					for (var j = 0; j < s.length; j++) {
						if(data.rows[i].cluster_ops_id == s[j]) {
							$(this).datagrid('checkRow', i);
						}
					};
				};
			},
			onDblClickCell: function(index, field, value) {
				document.getElementById('btnDataSelectedOK').click();
			},
			onSelect: function(index,row){
				var cluster_id = row.cluster_ops_id;
				var cluster_name = row.cluster_name;
				addCluster(cluster_id, cluster_name);
			}, 
			onUnselect: function(index, row){
				console.log(row);
				var cluster_id = row.cluster_ops_id;
				var cluster_name = row.cluster_name;
				remove_cluster(cluster_id, cluster_name);
			}
		});
	},
	search: function() {
		this.params.query['keyword'] = document.getElementById('q').value;
		this.params.query['region'] = document.getElementById('regions').value;
		this.loadData();
		document.getElementById('q').select();
		
		return false;
	},
	init: function() {
		this.params.query['keyword'] = document.getElementById('q').value;
		this.params.query['region'] = document.getElementById('regions').value;
		this.loadData();
		
		document.getElementById('q').onkeydown = function(e) {
			if (e.keyCode == 13) {
				lookupClusterOps.search();
			}
		};
	}
};

$(function() {
	lookupClusterOps.init();
});
</script>