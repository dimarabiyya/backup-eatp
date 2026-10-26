<div class="widget">
	<div class="widget-head"><h4 class="heading">PIC List</h4></div>
	
	<div class="widget-body">
	
		<div id="tb-users" style="padding: 4px; background-color: #efefef">
			<?php 
			//	$regions = $this->mglobal->getAllRegions();
				$roles   = $this->mglobal->getAllRoles();
				$region = $this->session->userdata('user_region');
			?>
			<div class="input-prepend">
				<span class="add-on">Region</span>
				<select name="region" id="regions" style="width: 120px" onchange="return lookupUsers.init()">
					<option value=""></option>
					<?php 
					// foreach ($regions as $rg) {
					// 	echo '<option value="'.$rg->region_id.'">'.$rg->region_name.'</option>'; 
					// } 
		            foreach ($region as $tt) {
		              echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
		            }

					?>
				</select>
			</div>
			<div class="input-prepend">
				<span class="add-on">User Role</span>
				<select name="role" id="roles" onchange="return lookupUsers.init()">
					<option value=""></option>
					<?php foreach ($roles as $rl) {
						echo '<option value="'.$rl->user_group_id.'">'.$rl->user_group_caption.'</option>'; 
					} ?>
				</select>
			</div>
			<div class="input-append pull-right">
				<?php echo form_input('q', '', 'id="q" class="input-medium" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" type="button" onclick="return lookupUsers.search()">Search</button>
			</div>
		</div>
		
		<table id="dg-users"></table>
		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnDataSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	
	</div>
	
</div>

<script>
var lookupUsers = {
	params: {
		cmd: 'get_lookup_users',
		act: 'get_data',
		query: {}
	},
	loadData: function() {
		$('#dg-users').datagrid({
			url: '<?php echo site_url('/preventive/assignment_pic') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			frozenColumns: [[{ field: 'chkData', checkbox: true }]],
			columns:[[
				// { field: 'user_id', hidden: true },
				{ field: 'user_name', title: 'User Name', width: 120, sortable: true },
				{ field: 'user_id', title: 'User Id', width: 120, sortable: true},
				{ field: 'user_region', title: 'User Region', width: 120, sortable: true },
				{ field: 'group_caption', title: 'User Role', width: 120, sortable: true }
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
				for (var i = 0; i < data.rows.length; i++) {
					if (data.rows[i].user_id == document.getElementsByName('<?php echo $this->input->get('e') ?>')[0].value) {
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
		this.params.query['keyword'] = document.getElementById('q').value;
		this.params.query['region'] = document.getElementById('regions').value;
		this.params.query['roles'] = document.getElementById('roles').value;
		this.loadData();
		document.getElementById('q').select();
		
		return false;
	},
	init: function() {
		this.params.query['keyword'] = document.getElementById('q').value;
		this.params.query['region'] = document.getElementById('regions').value;
		this.params.query['roles'] = document.getElementById('roles').value;
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