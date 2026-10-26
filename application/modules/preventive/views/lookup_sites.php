<div class="widget">
	<div class="widget-head"><h4 class="heading">Sites List</h4></div>
	
	<div class="widget-body">
	
		<div id="tb-sites" style="padding: 4px; background-color: #efefef">
			<div class="input-prepend">
				<span class="add-on">Region</span>
				<select name="region" id="regions" style="width: 120px" onchange="return lookupSites.search(1);">
					<option value="">All</option>
					<?php foreach ($regions as $rg) {
						echo '<option value="'.$rg->region_id.'">'.$rg->region_name.'</option>'; 
					} ?>
				</select>
				         <span class="add-on">Cluster</span>
          <select name="cluster_ops_id" id="cluster_ops_id" style="width: 130px;" onchange="return lookupSites.search();">
<!--           			<option value="">All</option> -->
				<!--
					<?php foreach ($cluster as $val) {
										echo '<option value="'.$val['cluster_ops_id'].'">'.$val['cluster_name'].'</option>'; 
									} ?>-->
				
          </select>
			</div>
			<div class="input-append pull-right">
				<?php echo form_input('q', '', 'id="q" class="input-medium" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" onclick="return lookupSites.search()">Search</button>
			</div>
		</div>
		
		<table id="dg-sites"></table>
	
		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnDataSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	
	</div>
	
</div>

<script>
$(document).on('change', '#cluster_ops_id', function() {
	// reloadDg(0);
	lookupSites.search(0);
})
$('#regions').change(function() {
	//console.log('test');
	$('#cluster_ops_id > option').remove();
	cluster();
})
cluster();
function cluster () {
	var region_idx = $('#regions').val();
	$.post(
		'<?php echo site_url('/preventive/grid/getCluster') ?>',
		{region_id: region_idx},
		function(data){
			// console.log(data);
			// var x = jQuery.parseJSON(data);		
			// console.log(data);
			// $('#cluster_ops_id > option').remove();
			$('#cluster_ops_id').append(data);
		}
	)
}


var lookupSites = {
	params: {
		cmd: 'get_lookup_sites',
		act: 'get_data',
		query: {},
		user_id: '<?php echo $user_id ?>'
	},
	loadData: function() {
		$('#dg-sites').datagrid({
			url: '<?php echo site_url('/preventive/assignment_pic') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			checkOnSelect:true, selectOnCheck:true,
			// frozenColumns: [[{ field: 'chkData', checkbox: true }]],
			columns:[[
				{field: 'chkData', checkbox: true},
				{ field: 'site_id', hidden: true },
				{ field: 'site_code', title: 'Site ID', width: 120, sortable: true },
				{ field: 'site_code_old', title: 'Site ID Ref.', width: 120, sortable: true },
				{ field: 'site_name', title: 'Site Name', width: 200, sortable: true },
				{ field: 'vendor_id', title: 'Vendor ID', width: 120, sortable: true },
				{ field: 'region_name', title: 'Region', width: 180, sortable: true },
				{ field: 'cluster_name', title: 'Cluster', width: 180, sortable: false }
			]],	
			iconCls: 'icon-save',
			toolbar: '#tb-sites',
			striped: true,
			pagination: true,
			singleSelect: false,
			fitColumns: false,
			rownumbers: true,
			width: 740,
			height: 'auto',
			onLoadSuccess: function(data) {
	
				var s = [];
				//$.each($('#sites-pic tbody tr'), function(){ 
				$.each($('#sites-pic tr'), function(){ 
				 s.push( $(this).children().find('input').val() );
				});
		
				for (var i = 0; i < data.rows.length; i++) {
					data.rows[i];
					for (var j = 0; j < s.length; j++) {
						if(data.rows[i].site_id == s[j]) {
							$(this).datagrid('checkRow', i);
						}
					};
				};

			},
			onDblClickCell: function(index, field, value) {
				document.getElementById('btnDataSelectedOK').click();
			},
/*
			onSelect: function(index,row){
				var site_id = row.site_id;
				var site_code = row.site_code;
				addSite(site_id, site_code);
			}, 
*/
			onSelect: function(index,row){
				// console.log('test');
			//console.log(row);
				addSite(row);
			}, 
			onUnselect: function(index, row){
				// console.log(row);
				var site_id = row.site_id;
				var site_code = row.site_code;
				remove_site(site_id, site_code);
			},
			onSelectAll : function(index,row){
				//addSite(index);
				//console.log(index);
			},
			onUnselectAll : function(index,row){
				//console.log('test');
				removeAllSite();
			}
			
	
			
		});
	},
	search: function(xyz) {
	// console.log(xyz);
		this.params.query['keyword'] = document.getElementById('q').value;
		this.params.query['region'] = document.getElementById('regions').value;
	
	if (xyz == 1){
  		// var cluster_ops_id ='';
  		this.params.query['cluster_ops_id'] = '';
  	} else {
  		// var cluster_ops_id = $('#cluster_ops_id').val();
  		this.params.query['cluster_ops_id'] = document.getElementById('cluster_ops_id').value;
  	}
  	
		// this.params.query['cluster_ops_id'] = document.getElementById('cluster_ops_id').value;
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
				lookupSites.search();
			}
		};
	}
};

$(function() {
	lookupSites.init();
});
</script>