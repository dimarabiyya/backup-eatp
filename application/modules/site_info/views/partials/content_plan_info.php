<style>
.table-primary thead th { font-size:12px; }
th a { padding:0 20px 0 0; }
th a, th a:focus { color:#FFFFFF; text-decoration:underline; }
th a.sort-asc { background:url('<?php echo base_url('/themes/sims/images/asc.png') ?>') no-repeat right center; }
th a.sort-desc { background:url('<?php echo base_url('/themes/sims/images/desc.png') ?>') no-repeat right center; }
</style>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Plan Info</h4>
	</div>
	
	<div class="widget-body form-horizontal">
	
		<div class="control-group" id="form_plan_info_nom_latlong">
			<label class="control-label" for="nominalLatitude">Nom. Lat - Long:</label>
			<div class="controls">
				<div class="input-append">
					<?php echo @form_input('nominalLatitude', set_value('nominalLatitude', $plan['nom_latitude']), 'id="nominalLatitude" readonly="readonly" class="input-small latlng-number"') ?>
					<?php echo @form_input('nominalLongitude', set_value('nominalLongitude', $plan['nom_longitude']), 'id="nominalLongitude" readonly="readonly" class="input-small latlng-number"') ?>
					<a href="javascript:void(0)" class="btn" onclick="return showPlanMap()">Map</a>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span6">
				<div class="control-group" id="form_plan_info_search_radius">						   
					<label class="control-label" for="radius">Search Ring/Radius:</label>
					<div class="controls">
						<div class="input-append">
							<div class="input-append">
								<?php echo @form_input('radius', set_value('radius', $plan['search_radius']), 'class="input-mini number" id="radius" readonly="readonly" ') ?>
								<span class="add-on">M</span>
							</div>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group" id="form_plan_info_bts_type">
					<label class="control-label" for="btsType">BTS Type:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('btsType', set_value('btsType', $plan['bts_type_name']), 'id="btsType" readonly="readonly" ') ?>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span6">
				<div class="control-group" id="form_plan_info_antenna_height">
					<label class="control-label" for="btsAntennaHeight">BTS (RF) Antenna Height:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('btsAntennaHeight', set_value('btsAntennaHeight', $plan['bts_ant_height']), 'id="btsAntennaHeight" class="input-mini number" readonly="readonly" ') ?>
							<span class="add-on">M</span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group" id="form_plan_info_dimension">
					<label class="control-label" for="btsAntennaDimensionL">BTS (RF) Dimension:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('btsAntennaDimensionL', $plan['bts_dimension_l'], 'id="btsAntennaDimensionL" class="input-mini number" readonly="readonly" ') ?>
							<?php echo @form_input('btsAntennaDimensionW', $plan['bts_dimension_w'], 'id="btsAntennaDimensionW" class="input-mini number" readonly="readonly" ') ?>
							<?php echo @form_input('btsAntennaDimensionH', $plan['bts_dimension_h'], 'id="btsAntennaDimensionH" class="input-mini number" readonly="readonly" ') ?>
							<span class="add-on">M</span>
						</div>
					</div>
					</div>
			</div>
		</div>
		
				<div class="control-group" id="form_plan_info_power_requirement">
					<label class="control-label" for="btsPowerRequirement">Power (PLN) Requirement:</label>
					<div class="controls">
						<div class="input-append">
							<div class="input-append">
								<?php echo @form_input('btsPowerRequirement', set_value('btsPowerRequirement', $plan['power_requirement']), 'class="input-mini number" id="btsPowerRequirement" readonly="readonly" ') ?>
								<span class="add-on">kVA</span>
							</div>
						</div>
					</div>
				</div>


<div class="widget">

	<div class="widget-head">
		<h4 class="heading"><i></i> RF Antenna</h4>
	</div>
	
	<div class="widget-body">
		
		<div id="tb-plan-rf" class="form-search">
			<div class="input-append">
				<?php echo form_input('query-plan-rf', '', 'id="query-plan-rf" class="input-medium search-query" placeholder="Search..."') ?>
				<button id="btnSearch" class="btn" onclick="GRID_PLAN_RF.search()"><i class="icon-search"></i></button>
			</div>
		</div>
		
		<table id="grid-plan-rf" class="jgrid"></table>
		
	</div>
</div>
	
<div class="widget">

	<div class="widget-head">
		<h4 class="heading"><i></i> Transmission Info</h4>
	</div>
	
	<div class="widget-body">
	
		<div id="tb-plan-transm" class="form-search">
			<div class="input-append">
				<?php echo form_input('query-plan-transm', '', 'id="query-plan-transm" class="input-medium search-query" placeholder="Search..."') ?>
				<button id="btnSearch" class="btn" onclick="GRID_PLAN_TRANSM.search()"><i class="icon-search"></i></button>
			</div>
		</div>
		
		<table id="grid-plan-transm" class="jgrid"></table>
	
	</div>
</div>
<div class="widget">

	<div class="widget-head">
		<h4 class="heading"><i></i> Address</h4>
	</div>
	
	
	<div class="widget-body form-horizontal">
	
		<div class="control-group" id="form_plan_info_nominal_site_address">
			<label class="control-label" for="siteAddress">Nominal Site Address:</label>
			<div class="controls">
				<?php echo @form_textarea('siteAddress', set_value('siteAddress', trim($plan['nom_address'])), 'id="siteAddress" style="width:206px; height:80px;" readonly="readonly" ') ?>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span6">
				<div class="control-group" id="form_plan_info_kelurahan">
					<label class="control-label" for="kelurahan">Kelurahan:</label>
					<div class="controls">
						<?php echo @form_input('kelurahan', set_value('kelurahan', trim($plan['kelurahan'])), 'id="kelurahan" readonly="readonly" ') ?>
					</div>							
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group" id="form_plan_info_kecamatan">
					<label class="control-label" for="kecamatan">Kecamatan:</label>
						<div class="controls">
							<?php echo @form_input('kecamatan', set_value('kecamatan', trim($plan['plan_kecamatan'])), 'id="kecamatan" readonly="readonly" ') ?>
						</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span6">
				<div class="control-group" id="form_plan_info_kabupaten">
					<label class="control-label" for="kabupaten">Kabupaten:</label>
					<div class="controls">
						<?php echo @form_input('kabupaten', set_value('kabupaten', $plan['plan_kabupaten']), 'id="kabupaten" class="medium" readonly="readonly" ') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group" id="form_plan_info_propinsi">
					<label class="control-label" for="propinsi">Propinsi:</label>
					<div class="controls">
						<?php echo @form_input('propinsi', set_value('propinsi', $plan['plan_propinsi']), 'id="propinsi" class="medium" readonly="readonly" ') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="control-group" id="form_plan_info_kode_pos">
			<label class="control-label" for="kodePos">Kode Pos:</label>
			<div class="controls">
				<?php echo @form_input('kodePos', set_value('kodePos', $plan['kode_pos']), 'id="kodePos" class="medium" readonly="readonly" ') ?>
			</div>
		</div>
	</div>
</div>
</div>
	
	</div>
</div>

<!--
<div class="form-actions">
	<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
	<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info') ?>'"><i></i>Cancel</button>
</div>
-->

<script>
var GRID_PLAN_RF = {
	params: {
		cmd: 'GET_DATAGRID_PLAN_RF',
		site_id: '<?php echo $this->uri->segment(3) ?>',
		query: ''
	},
	loadData: function() {
		$('#grid-plan-rf').datagrid({
			url: '<?php echo site_url('/site_info') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'site_plan_rf_id', hidden: true },
				{ field: 'antenna', title: 'Antenna', width: 150, sortable: true },
				{ field: 'height', title: 'Height', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' M'; } },
				{ field: 'dimension', title: 'Dimension', width: 150, sortable: false, formatter: function(value, row, index) { return row.dimension_l + ' x ' + row.dimension_w + ' x ' + row.dimension_h + ' CM'; } },
				{ field: 'weight', title: 'Weight', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' kg'; } }
			]],
			toolbar: '#tb-plan-rf',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-plan-rf').value;
		this.loadData();
		document.getElementById('query-plan-rf').focus();
	},
	/* delete: function(id) {
		if (confirm('Are you sure you want to delete?')) {
			this.params.cmd = 'DELETE_PLAN_RF';
			this.params.id = id;
			$.ajax({
				url: '<?php echo site_url('/site_info') ?>',
				type: 'GET',
				data: this.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_PLAN.params.cmd = 'GET_DATAGRID_PLAN_RF';
						GRID_PLAN.loadData();
					}
				}
			});
		}
	}, */
	init: function() {
		this.loadData();
		
		document.getElementById('query-plan-rf').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_PLAN_RF.search();
				this.focus();
			}
		};
	}
};

var GRID_PLAN_TRANSM = {
	params: {
		cmd: 'GET_DATAGRID_PLAN_TRANSM',
		site_id: '<?php echo $this->uri->segment(3) ?>',
		query: ''
	},
	loadData: function() {
		$('#grid-plan-transm').datagrid({
			url: '<?php echo site_url('/site_info') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'site_plan_mw_id', hidden: true },
				{ field: 'ne', title: 'NE', width: 150, sortable: true },
				{ field: 'fe', title: 'FE', width: 150, sortable: true },
				{ field: 'link_type', title: 'Link Type', width: 150, sortable: true },
				{ field: 'ant_height', title: 'Main Ant. Height', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' M'; } },
				{ field: 'ant_diameter', title: 'Main Ant. Diameter', width: 180, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' M'; } },
				{ field: 'frequency', title: 'Frequency', width: 150, sortable: true, align: 'right' },
				{ field: 'equip_model', title: 'Equipment Model', width: 180, sortable: true },
				{ field: 'capacity', title: 'Capacity', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' MB'; } },
				{ field: 'owner', title: 'Owner', width: 150, sortable: true }
			]],
			toolbar: '#tb-plan-transm',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-plan-transm').value;
		this.loadData();
		document.getElementById('query-plan-transm').focus();
	},
	/* delete: function(id) {
		if (confirm('Are you sure you want to delete?')) {
			this.params.cmd = 'DELETE_PLAN_RF';
			this.params.id = id;
			$.ajax({
				url: '<?php echo site_url('/site_info') ?>',
				type: 'GET',
				data: this.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_PLAN.params.cmd = 'GET_DATAGRID_PLAN_RF';
						GRID_PLAN.loadData();
					}
				}
			});
		}
	}, */
	init: function() {
		this.loadData();
		
		document.getElementById('query-plan-transm').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_PLAN_TRANSM.search();
				this.focus();
			}
		};
	}
};

var showPlanMap = function() {
	var x = parseInt((window.screen.availWidth - 850) / 2);
    var y = parseInt(((window.screen.availHeight - 560) / 2) - 50);
	
	var w = window.open('<?php echo site_url('/site_info') ?>?cmd=GET_MAP&draggable=false&latlngElement=nominalLatitude,nominalLongitude', 'map', 'height=560,width=850,left=' + x + ',top=' + y + ',scrollbars=no');
	w.focus();
	
	return w;
};

$(function() {
	GRID_PLAN_RF.init();
	GRID_PLAN_TRANSM.init();
});

numberFormat.init();
</script>