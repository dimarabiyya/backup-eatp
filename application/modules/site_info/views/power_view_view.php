<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<!--
<div class="heading-buttons">
	<h3>Power Equipment</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/power') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">

	<?php
	$site_id = (int) $this->core->decode($this->uri->segment(4));
	
	$power = $this->db
		->query("
			SELECT si.site_code, si.site_code_old, si.site_name, si.vendor_id, reg.region_name
			FROM t_mtr_site si
			LEFT JOIN t_mtr_region reg ON reg.region_id = si.region_id
			WHERE si.site_id = {$site_id}
		")
		->row_array();
	?>
	
	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Site</h4>
			
			<a href="<?php echo site_url('/site_info/power') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
			
			<div class="form-horizontal">
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_site_id">
							<label class="control-label" for="siteId">Site ID:</label>
							<div class="controls">
								<span class="input-label"><?php echo $power['site_code'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6">
						<div class="control-group" id="form_site_id_ref">
							<label class="control-label" for="siteIdRef">Site ID Ref.:</label>
							<div class="controls">
								<span id="siteIdRef" class="input-label"><?php echo @$power['site_code_old'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_site_name">
							<label class="control-label" for="siteName">Site Name:</label>
							<div class="controls">
								<span id="siteName" class="input-label"><?php echo @$power['site_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6">
						<div class="control-group" id="form_vendor_id">
							<label class="control-label" for="vendorId">Vendor ID:</label>
							<div class="controls">
								<span id="vendorId" class="input-label"><?php echo @$power['vendor_id'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="control-group" id="form_region">
					<label class="control-label" for="region">Region:</label>
					<div class="controls">
						<span id="region" class="input-label"><?php echo @$power['region_name'] ?></span>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	
	<div class="widget" style="border:none">
		
		<div class="easyui-tabs">
			
			<div title="ACPDB">
			
				<?php
				$acpdb = $this->db
					->query("
						SELECT acpdb.acpdb_id, acpdb.capacity_r, acpdb.capacity_s, acpdb.capacity_t, lo1.lookup_desc AS mcb_capacity_tp, lo2.lookup_desc AS mcb_capacity, acpdb.mcb_capacity_acpdb, acpdb.acpdb_mcb_capacity
						FROM t_mtr_site_l_acpdb acpdb
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(acpdb.mcb_capacity_tp AS VARCHAR) AND lo1.lookup_key = 'power_capacity' AND lo1.active = 1
						LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(acpdb.mcb_capacity AS VARCHAR) AND lo2.lookup_key = 'power_phase' AND lo2.active = 1
						WHERE acpdb.site_id = {$site_id}
					")
					->row_array();
				?>
				
				<div class="widget">	
					<div class="widget-head heading-outer">
						<h4 class="heading"><i></i> ACPDB Data</h4>
					</div>
					
					<div class="widget-body form-horizontal">
					
						<div class="control-group">
							<label class="control-label" for="capacityR">Capacity R:</label>
							<div class="controls">
								<span class="input-label"><?php echo number_format(@$acpdb['capacity_r'], 2, '.', '') ?> Amp(AC)</span>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="capacityS">Capacity S:</label>
							<div class="controls">
								<span class="input-label"><?php echo number_format(@$acpdb['capacity_s'], 2, '.', '') ?> Amp(AC)</span>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="capacityT">Capacity T:</label>
							<div class="controls">
								<span class="input-label"><?php echo number_format(@$acpdb['capacity_t'], 2, '.', '') ?> Amp(AC)</span>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="capacityT">MCB Capacity of TP / kwh:</label>
							<div class="controls">
								<span class="input-label"><?php echo @$acpdb['mcb_capacity_tp'] ?> Amp(AC) <?php echo @$acpdb['mcb_capacity'] ?></span>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="capacityT">MCB Capacity of ACPDB:</label>
							<div class="controls">
								<?php
								$mcbCapacity = array(0 => '');
								$lookup = $this->core->get_lookup('power_capacity');
								foreach ($lookup as $lo) {
									$mcbCapacity[$lo['id']] = $lo['desc'];
								}
								
								$mcbPhase = array(0 => '');
								$lookup = $this->core->get_lookup('power_phase');
								foreach ($lookup as $lo) {
									$mcbPhase[$lo['id']] = $lo['desc'];
								}
								?>
								<span class="input-label"><?php echo $mcbCapacity[$acpdb['mcb_capacity_acpdb']] ?> Amp(AC) <?php echo $mcbPhase[$acpdb['acpdb_mcb_capacity']] ?></span>
							</div>
						</div>
						
					</div>
				</div>
			
				<!-- start: photo(s) ACPDB -->
				<div class="widget">	
					<div class="widget-head">
						<h4 class="heading"><i></i> Photo(s)</h4>
					</div>
					
					<div class="widget-body">
					
						<table class="table table-bordered table-striped table-condensed" style="width:auto">
							<thead>
								<tr>
									<th width="200" class="center">Type</th>
									<th class="center">Photo</th>
								</tr>
							</thead>
							<tbody>				
								<?php
								$power_dir = FCPATH.'file/upload/site-info/power-equipment';
								$power_url = base_url('/file/upload/site-info/power-equipment');
								
								$lookup = $this->core->get_lookup('power_acpdb_photo_type');
								$photos = array();
								foreach ($lookup as $lo) {
									$thumb = base_url('/file/upload/site-info/no_image-60x60.jpg');
									$image = 'javascript:void(0)';
									
									if ($this->uri->segment(3) == 'view' && isset($acpdb['acpdb_id'])) {
										if ( $photo = $this->core->view_row('t_mtr_site_l_acpdb_photo', array('acpdb_id' => $acpdb['acpdb_id'], 'type_id' => $lo['id'])) ) {
											if (file_exists($power_dir.'/'.$photo['photo_name']) && is_file($power_dir.'/'.$photo['photo_name'])) {
												$thumb = $this->image_model->resize($power_dir.'/'.$photo['photo_name'], 60, 60, 'crop');
												$image = $power_url.'/'.$photo['photo_name'];
											}
										}
									}
									
									$photos[] = array(
										'title' => $lo['desc'],
										'image' => '<a id="photo-acpdb-'.$lo['id'].'-box" href="'.$image.'" class="photobox"><img id="photo-acpdb-'.$lo['id'].'-thumb" src="'.$thumb.'" class="img-polaroid" /></a>',
									);
								}
								
								foreach ($photos as $photo) {
								?>
								<tr>
									<td><?php echo $photo['title'] ?></td>
									<td><?php echo $photo['image'] ?></td>
								</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					
					</div>
				</div>
				<!-- end: photo(s) ACPDB -->
				
				<!-- start: Grid Detail ACPDB -->
				<div class="widget">

					<div class="widget-head">
						<h4 class="heading"><i></i> Detail ACPDB</h4>
					</div>
					
					<div class="widget-body">
					
						<div id="tb-power-acpdb" class="form-search">
							<div class="input-append">
								<?php echo form_input('query-power-acpdb', '', 'id="query-power-acpdb" class="input-medium search-query" placeholder="Search..."') ?>
								<button class="btn" onclick="GRID_POWER_ACPDB.search()"><i class="icon-search"></i></button>
							</div>
						</div>
						
						<table id="grid-power-acpdb"></table>
					
					</div>
				</div>
				<!-- end: Grid Detail ACPDB -->
			
			</div>

			<div title="Rectifier">
			
				<!-- start: datagrid Rectifier -->
				<div class="widget">
					<div class="widget-head">
						<h4 class="heading"><i></i> Rectifier(s)</h4>
					</div>
					
					<div class="widget-body">
						
						<div id="tb-power-rectifier" class="form-search">
							<div class="input-append">
								<?php echo form_input('query-power-rectifier', '', 'id="query-power-rectifier" class="input-medium search-query" placeholder="Search..."') ?>
								<button class="btn" onclick="GRID_POWER_RECTIFIER.search()"><i class="icon-search"></i></button>
							</div>
						</div>
						
						<table id="grid-power-rectifier"></table>
					
					</div>
				</div>
				<!-- end: datagrid Rectifier -->
			
			</div>

			<div title="DCPDB">
			
				<!-- start: datagrid DCPDB -->
				<div class="widget">
					<div class="widget-head">
						<h4 class="heading"><i></i> DCPDB(s)</h4>
					</div>
					
					<div class="widget-body">
						
						<div id="tb-power-dcpdb" class="form-search">
							<div class="input-append">
								<?php echo form_input('query-power-dcpdb', '', 'id="query-power-dcpdb" class="input-medium search-query" placeholder="Search..."') ?>
								<button class="btn" onclick="GRID_POWER_DCPDB.search()"><i class="icon-search"></i></button>
							</div>
						</div>
						
						<table id="grid-power-dcpdb"></table>
					
					</div>
				</div>
				<!-- end: datagrid DCPDB -->
			
			</div>

			<div title="Battery">
			
				<!-- start: Grid Battery -->
				<div class="widget">

					<div class="widget-head">
						<h4 class="heading"><i></i> Battery Data</h4>
					</div>
					
					<div class="widget-body">
					
						<div id="tb-power-battery" class="form-search">
							<div class="input-append">
								<?php echo form_input('query-power-battery', '', 'id="query-power-battery" class="input-medium search-query" placeholder="Search..."') ?>
								<button class="btn" onclick="GRID_POWER_BATTERY.search()"><i class="icon-search"></i></button>
							</div>
						</div>
						
						<table id="grid-power-battery"></table>
					
					</div>
				</div>
				<!-- end: Grid Battery -->
			
			</div>
			
		</div>
	</div>

</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<style>
.widget .widget-body_ { padding:20px 15px; }
.tabs_ { height:36px; }
.tabs-panels_ { padding:10px; }
.tabs li a.tabs-inner_ { border-radius:0px; height:35px; line-height:35px; }
.tabs-title_ { font-size:13px; }
</style>

<script>
var GRID_POWER_ACPDB = {
	params: {
		cmd: 'GET_DATAGRID_SITEINFO_POWER_ACPDB',
		acpdb_id: <?php echo (int) @$acpdb['acpdb_id'] ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-power-acpdb').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'item', title: 'Item(s)', width: 150, sortable: true },
				{ field: 'capacity', title: 'Capacity', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' Amp(AC)'; } },
				{ field: 'capacity_actual', title: 'Actual Capacity', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2, '.', '') + ' Amp(AC)'; } },
				{ field: 'phase_used', title: 'Phase Used', width: 150, sortable: true },
				{ field: 'allocation', title: 'Allocation', width: 150, sortable: true }
			]],
			toolbar: '#tb-power-acpdb',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-power-acpdb').value;
		this.loadData();
		document.getElementById('query-power-acpdb').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-power-acpdb').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_POWER_ACPDB.search();
			}
		};
	}
};

var GRID_POWER_RECTIFIER = {
	params: {
		cmd: 'GET_DATAGRID_SITEINFO_POWER_RECTIFIER',
		site_id: <?php echo $site_id ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-power-rectifier').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'rectifier_no', title: 'Rectifier No.', width: 100, sortable: true },
				{ field: 'slot_used', title: 'Slot used for Rectifier module', width: 180, sortable: true },
				{ field: 'dc_power', title: 'DC Power', width: 100, sortable: true, align: 'right' },
				{ field: 'dcdc_power_capacity', title: 'DC DC Power Capacity', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Amp'; } },
				{ field: 'mcb_capacity_tp', title: 'MCB Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(AC) ' + (row.mcb_capacity != null ? row.mcb_capacity : ''); } },
				{ field: 'capacity_r', title: 'Capacity R', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Amp(DC)'; } },
				{ field: 'capacity_s', title: 'Capacity S', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Amp(DC)'; } },
				{ field: 'capacity_t', title: 'Capacity T', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Amp(DC)'; } },
				
				<?php
				$lookup = $this->core->get_lookup('power_rectifier_photo_type');
				foreach ($lookup as $lo) {
				?>
				{ field: 'photo-<?php echo $lo['id'] ?>', title: '<?php echo $lo['desc'] ?>', width: 120, align: 'center', formatter: function(value, row, index) {
					return ( row.photo['<?php echo $lo['id'] ?>'] != null ? '<a href="' + row.photo.large['<?php echo $lo['id'] ?>'] + '" class="photobox"><img src="' + row.photo['<?php echo $lo['id'] ?>'] + '" width="30" border="0" class="img-polaroid" /></a>' : '');
				} },
				<?php
				}
				?>
			]],
			toolbar: '#tb-power-rectifier',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: false,
			rownumbers: true,
			height: 'auto',
			onLoadSuccess: function() {
				for (var i = 0; i < document.getElementsByClassName('photobox').length; i++) {
					document.getElementsByClassName('photobox')[i].onclick = function() {
						$.fancybox({
							type:'image',
							href: this.href,
							openEffect: 'elastic',
							closeEffect: 'elastic',
							closeBtn: true
						});
						
						return false;
					};
				}
			},
			view: detailview,
			detailFormatter: function(index, row) {
				return '<div style="padding:2px 0"><table class="ddv-rectifier"></table></div>';
			},
			onExpandRow: function(index, row) {
				var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv-rectifier');
				
				ddv.datagrid({
					url: '<?php echo site_url('/site_info/power') ?>',
					queryParams: { cmd: 'GET_DATAGRID_RECTIFIER_DETAIL', rectifier_id: row.rectifier_id },
					method: 'GET',
					fitColumns: true,
					singleSelect: true,
					rownumbers: true,
					loadMsg: '',
					height: 'auto',
					pagination: true,
					fitColumns: true,
					striped: true,
					columns: [[
						{ field: 'item', title: 'Item(s)', width: 100, sortable: true },
						{ field: 'capacity', title: 'Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(DC)'; } },
						{ field: 'capacity_actual', title: 'Actual Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2, '.', '') + ' Amp(DC)'; } },
						{ field: 'llvd_info', title: 'LLVD Info', width: 100, sortable: true },
						{ field: 'allocation', title: 'Allocation', width: 120, sortable: true },
						{ field: 'site_code', title: 'FE', width: 120, sortable: true },
						{ field: 'system', title: 'System', width: 120, sortable: true }
					]],
					onResize: function() {
						$('#grid-power-rectifier').datagrid('fixDetailRowHeight', index);
					},
					onLoadSuccess: function() {
						setTimeout(function() {
							$('#grid-power-rectifier').datagrid('fixDetailRowHeight', index);
						}, 0);
					}
				});
				
				$('#grid-power-rectifier').datagrid('fixDetailRowHeight', index);
			}
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-power-rectifier').value;
		this.loadData();
		document.getElementById('query-power-rectifier').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-power-rectifier').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_POWER_RECTIFIER.search();
			}
		};
	}
};

var GRID_POWER_DCPDB = {
	params: {
		cmd: 'GET_DATAGRID_SITEINFO_POWER_DCPDB',
		site_id: <?php echo $site_id ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-power-dcpdb').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'dcpdb_id', hidden: true },
				{ field: 'rectifier_no', title: 'Linked to Rectifier No.', width: 120, sortable: true },
				{ field: 'main_mcb_capacity', title: 'Main MCB Capacity', width: 120, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Amp'; } },
				{ field: 'cable_diameter', title: 'Cable Diameter', width: 120, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Mm<sup>2</sup>'; } },
				
				<?php
				$lookup = $this->core->get_lookup('power_dcpdb_photo_type');
				foreach ($lookup as $lo) {
				?>
				{ field: 'photo-<?php echo $lo['id'] ?>', title: '<?php echo $lo['desc'] ?>', width: 120, align: 'center', formatter: function(value, row, index) {
					return ( row.photo['<?php echo $lo['id'] ?>'] != null ? '<a href="' + row.photo.large['<?php echo $lo['id'] ?>'] + '" class="photobox"><img src="' + row.photo['<?php echo $lo['id'] ?>'] + '" width="30" border="0" class="img-polaroid" /></a>' : '');
				} },
				<?php
				}
				?>
			]],
			toolbar: '#tb-power-dcpdb',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto',
			onLoadSuccess: function() {
				for (var i = 0; i < document.getElementsByClassName('photobox').length; i++) {
					document.getElementsByClassName('photobox')[i].onclick = function() {
						$.fancybox({
							type:'image',
							href: this.href,
							openEffect: 'elastic',
							closeEffect: 'elastic',
							closeBtn: true
						});
						
						return false;
					};
				}
			},
			view: detailview,
			detailFormatter: function(index, row) {
				return '<div style="padding:2px 0"><table class="ddv-dcpdb"></table></div>';
			},
			onExpandRow: function(index, row) {
				var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv-dcpdb');
				
				ddv.datagrid({
					url: '<?php echo site_url('/site_info/power') ?>',
					queryParams: { cmd: 'GET_DATAGRID_DCPDB_DETAIL', dcpdb_id: row.dcpdb_id },
					method: 'GET',
					fitColumns: true,
					singleSelect: true,
					rownumbers: true,
					loadMsg: '',
					height: 'auto',
					pagination: true,
					fitColumns: true,
					striped: true,
					columns: [[
						{ field: 'item', title: 'Item(s)', width: 100, sortable: true },
						{ field: 'capacity', title: 'Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(DC)'; } },
						{ field: 'actual_capacity', title: 'Actual Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2, '.', '') + ' Amp(DC)'; } },
						{ field: 'allocation', title: 'Allocation', width: 120, sortable: true },
						{ field: 'site_code', title: 'FE ID', width: 120, sortable: true },
						{ field: 'system', title: 'System', width: 120, sortable: true }
					]],
					onResize: function() {
						$('#grid-power-dcpdb').datagrid('fixDetailRowHeight', index);
					},
					onLoadSuccess: function() {
						setTimeout(function() {
							$('#grid-power-dcpdb').datagrid('fixDetailRowHeight', index);
						}, 0);
					}
				});
				
				$('#grid-power-dcpdb').datagrid('fixDetailRowHeight', index);
			}
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-power-dcpdb').value;
		this.loadData();
		document.getElementById('query-power-dcpdb').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-power-dcpdb').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_POWER_DCPDB.search();
			}
		};
	}
};

var GRID_POWER_BATTERY = {
	params: {
		cmd: 'GET_DATAGRID_SITEINFO_POWER_BATTERY',
		site_id: <?php echo $site_id ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-power-battery').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'mcb_item', title: 'Item(s)', width: 150, sortable: true },
				{ field: 'brand', title: 'Brand / Model', width: 250, sortable: true },
				{ field: 'capacity', title: 'Capacity per Cell', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' Ah'; } },
				{ field: 'total_bank', title: 'Total Bank', width: 150, sortable: true, align: 'right' },
				{ field: 'total_shunt', title: 'Total Shunt', width: 150, sortable: true, align: 'right' },
				{ field: 'weight_per_cell', title: 'Weight per Cell', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' kg'; } },
				{ field: 'voltage_per_cell', title: 'Voltage per Cell', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' Volt'; } }
			]],
			toolbar: '#tb-power-battery',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-power-battery').value;
		this.loadData();
		document.getElementById('query-power-battery').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-power-battery').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_POWER_BATTERY.search();
			}
		};
	}
};

$(function() {
	GRID_POWER_ACPDB.init();
	GRID_POWER_RECTIFIER.init();
	GRID_POWER_DCPDB.init();
	GRID_POWER_BATTERY.init();
	
	$('.photobox').fancybox({
		openEffect: 'elastic',
		closeEffect: 'elastic',
		closeBtn: true
	});
});

mainMenu();
</script>