<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<style>
.table-primary thead th { font-size:12px; }
th a { padding:0 20px 0 0; }
th a, th a:focus { color:#FFFFFF; text-decoration:underline; }
th a.sort-asc { background:url('<?php echo base_url('/themes/sims/images/asc.png') ?>') no-repeat right center; }
th a.sort-desc { background:url('<?php echo base_url('/themes/sims/images/desc.png') ?>') no-repeat right center; }
</style>

<div class="heading-buttons">
	<h3>Equipment</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/equipment') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>

<div class="innerLR">
	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading"><i></i> Site</h4>
		</div>
		
		<div class="widget-body">
			
			<div class="row-fluid">
				<div class="span2">Site:</div>
				<div class="span10"><strong><?php echo $equipment['site_code'] ?></strong></div>
			</div>
			<div class="row-fluid">
				<div class="span2">Site Name:</div>
				<div class="span10"><strong><?php echo $equipment['site_name'] ?></strong></div>
			</div>
			<div class="row-fluid">
				<div class="span2">Vendor ID:</div>
				<div class="span10"><strong><?php echo $equipment['vendor_id'] ?></strong></div>
			</div>
			<div class="row-fluid">
				<div class="span2">Vendor Name:</div>
				<div class="span10"><strong><?php echo $equipment['vendor_name'] ?></strong></div>
			</div>
			
		</div>
		
	</div>
	
	<!-- start: BTS Antenna (RF) -->
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> BTS Antenna (RF)</h4>
		</div>
		
		<div class="widget-body">
			
			<div class="dataTables_wrapper form-inline">
				<div class="row-fluid" style="padding:0 0 15px">
					<div class="span6">
						<div class="dataTables_length">
							<label>Show 
								<select name="perPage" id="perPage" style="width:80px" onchange="objCommandRF.changePerPage()">
									<option value="10">10</option>
									<option value="25">25</option>
									<option value="50">50</option>
									<option value="100">100</option>
								</select> entries
							</label>
						</div>
					</div>
					<div class="span6">
						<form class="form-search">
							<div class="input-append pull-right">
								<input type="text" style="margin:0px; padding:4px;" class="search-query input-medium" id="queryRF" name="queryRF" placeholder="Search..." autocomplete="off" />
								<button onclick="return objCommandRF.search()" class="btn">Search</button>
							</div>
						</form>
					</div>
				</div>
				
				<div style="overflow:auto">
					<table id="tblRF" class="table table-bordered table-primary table-vertical-center">
						<thead>
							<tr>
								<th><a href="javascript:void(0)" data-order="cell">Cell</a></th>
								<th><a href="javascript:void(0)" data-order="frequency">Frequency</a></th>
								<th><a href="javascript:void(0)" data-order="ant_model">Model</a></th>
								<th>Azimuth</th>
								<th>Electrical Tilt</th>
								<th>Mechanical Tilt</th>
							</tr>
						</thead>
						<tbody id="document-data-rf"></tbody>
					</table>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="dataTables_info" id="recinfoAreaRF" style="padding:20px 0"></div>
					</div>
					<div class="span6">
						<div class="dataTables_paginate paging_bootstrap pagination" id="paginationRF"></div>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	<!-- end: BTS Antenna (RF) -->
	
	<!-- start: MW Antenna -->
	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading"><i></i> Transmission Info</h4>
		</div>
		
		<div class="widget-body">
			
			<div class="dataTables_wrapper form-inline">
				<div class="row-fluid" style="padding:0 0 15px">
					<div class="span6">
						<div class="dataTables_length">
							<label>Show 
								<select name="perPage" id="perPage" style="width:80px" onchange="objCommandMW.changePerPage()">
									<option value="10">10</option>
									<option value="25">25</option>
									<option value="50">50</option>
									<option value="100">100</option>
								</select> entries
							</label>
						</div>
					</div>
					<div class="span6">
						<form class="form-search">
							<div class="input-append pull-right">
								<input type="text" style="margin:0px; padding:4px;" class="search-query input-medium" id="queryMW" name="queryMW" placeholder="Search..." autocomplete="off" />
								<button onclick="return objCommandMW.search()" class="btn">Search</button>
							</div>
						</form>
					</div>
				</div>
				
				<div style="overflow:auto">
					<table id="tblMW" class="table table-bordered table-primary table-vertical-center">
						<thead>
							<tr>
								<th><a href="javascript:void(0)" data-order="near_end">Near End</a></th>
								<th><a href="javascript:void(0)" data-order="far_end">Far End</a></th>
								<th><a href="javascript:void(0)" data-order="link_code_id">Link ID</a></th>
								<th><a href="javascript:void(0)" data-order="ant_model">Ant Model</a></th>
								<th>Frequency</th>
								<th>Capacity (MB)</th>
							</tr>
						</thead>
						<tbody id="document-data"></tbody>
					</table>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="dataTables_info" id="recinfoArea" style="padding:20px 0"></div>
					</div>
					<div class="span6">
						<div class="dataTables_paginate paging_bootstrap pagination" id="pagination"></div>
					</div>
				</div>
			
			</div>
			
		</div>
	</div>
	<!-- end: MW Antenna -->
	
</div>

<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script>
var objCommandMW = {
	params: {
		cmd: '',
		per_page: 10,
		query: ''
	},
	url: '<?php echo site_url('/site_info/equipment') ?>',
	loadData: function() {
		this.params.cmd = 'LOAD_DATA_MW';
		this.params.equipment_id = <?php echo $equipment['equipment_id'] ?>;
		this.params.site_id_ne = <?php echo $equipment['site_id'] ?>;
		this.params.per_page = document.getElementById('perPage').value;
		
		$.ajax({
			url: this.url,
			data: this.params,
			dataType: 'json',
			beforeSend: function() {
				//siteOverlay.top = '200px';
				//siteOverlay.create();
			},
			success: function(json) {
				if (typeof json.loggedin != 'undefined' && !json.loggedin) {
					siteOverlay.remove();
					alert(json.message);
					window.location.href = json.redirect_url;
				}
				
				var html = '';
				for (var i = 0; i < json.data.length; i++) {
					html += '\
						<tr>\
							<td>' + json.data[i].near_end + '</td>\
							<td>' + json.data[i].far_end + '</td>\
							<td>' + json.data[i].link_code_id + '</td>\
							<td>' + json.data[i].ant_model + '</td>\
							<td>' + json.data[i].frequency + '</td>\
							<td>' + json.data[i].capacity + '</td>\
						</tr>\
					';
				}
				document.getElementById('document-data').innerHTML = html;
				document.getElementById('recinfoArea').innerHTML = json.rec_info;
				document.getElementById('pagination').innerHTML = json.pagination;
			},
			complete: function() {
				//siteOverlay.remove();
			}
		});
	},
	search: function() {
		this.params.cmd = 'LOAD_DATA_MW';
		this.params.query = document.getElementById('queryMW').value;
		this.url = '<?php echo site_url('/site_info/equipment') ?>';
		this.loadData();
		
		return false;
	},
	delete: function(id) {
		if (confirm('Are you sure you want to delete?')) {
			this.params.cmd = 'DELETE';
			this.params.id = id;
			this.url = '<?php echo site_url('/site_info/equipment') ?>';
			$.ajax({
				url: this.url,
				data: this.params,
				dataType: 'json',
				success: function(json) {
					if (typeof json.loggedin != 'undefined' && !json.loggedin) {
						alert(json.message);
						window.location.href = json.redirect_url;
					}
					
					objCommandMW.loadData();
				}
			});
		}
	},
	changePerPage: function() {
		this.url = '<?php echo site_url('/site_info/equipment') ?>';
		this.loadData();
	}
};

objCommandMW.loadData();

$('#pagination a').live('click', function() {
	objCommandMW.params.cmd = 'LOAD_DATA_MW';
	objCommandMW.url = this.href;
	objCommandMW.loadData();
	
	return false;
});

var sort = 'DESC';
var sortClass = 'sort-desc';

$('#tblMW th a').live('click', function() {
	if (sort == 'DESC') {
		sort = 'ASC';
		sortClass = 'sort-asc';
	} else {
		sort = 'DESC';
		sortClass = 'sort-desc';
	}
	
	$('#tblMW th a').removeClass('sort-asc');
	$('#tblMW th a').removeClass('sort-desc');
	
	$(this).addClass(sortClass);
	
	objCommandMW.params.cmd = 'LOAD_DATA_MW';
	objCommandMW.params.order = this.getAttribute('data-order');
	objCommandMW.params.sort = sort;
	objCommandMW.loadData();
});

var objCommandRF = {
	params: {
		cmd: '',
		per_page: 10,
		query: ''
	},
	url: '<?php echo site_url('/site_info/equipment') ?>',
	loadData: function() {
		this.params.cmd = 'LOAD_DATA_RF';
		this.params.equipment_id = <?php echo $equipment['equipment_id'] ?>;
		this.params.per_page = document.getElementById('perPage').value;
		
		$.ajax({
			url: this.url,
			data: this.params,
			dataType: 'json',
			beforeSend: function() {
				//siteOverlay.top = '200px';
				//siteOverlay.create();
			},
			success: function(json) {
				if (typeof json.loggedin != 'undefined' && !json.loggedin) {
					siteOverlay.remove();
					alert(json.message);
					window.location.href = json.redirect_url;
				}
				
				var html = '';
				for (var i = 0; i < json.data.length; i++) {
					html += '\
						<tr>\
							<td>' + json.data[i].cell + '</td>\
							<td>' + json.data[i].frequency + '</td>\
							<td>' + json.data[i].ant_model + '</td>\
							<td>' + json.data[i].azimuth + '</td>\
							<td>' + json.data[i].electrical_tilt + '</td>\
							<td>' + json.data[i].mechanical_tilt + '</td>\
						</tr>\
					';
				}
				document.getElementById('document-data-rf').innerHTML = html;
				document.getElementById('recinfoAreaRF').innerHTML = json.rec_info;
				document.getElementById('paginationRF').innerHTML = json.pagination;
			},
			complete: function() {
				//siteOverlay.remove();
			}
		});
	},
	search: function() {
		this.params.cmd = 'LOAD_DATA_MW';
		this.params.query = document.getElementById('queryRF').value;
		this.url = '<?php echo site_url('/site_info/equipment') ?>';
		this.loadData();
		
		return false;
	},
	delete: function(id) {
		if (confirm('Are you sure you want to delete?')) {
			this.params.cmd = 'DELETE';
			this.params.id = id;
			this.url = '<?php echo site_url('/site_info/equipment') ?>';
			$.ajax({
				url: this.url,
				data: this.params,
				dataType: 'json',
				success: function(json) {
					if (typeof json.loggedin != 'undefined' && !json.loggedin) {
						alert(json.message);
						window.location.href = json.redirect_url;
					}
					
					objCommandRF.loadData();
				}
			});
		}
	},
	changePerPage: function() {
		this.url = '<?php echo site_url('/site_info/equipment') ?>';
		this.loadData();
	}
};

objCommandRF.loadData();

$('#paginationRF a').live('click', function() {
	objCommandRF.params.cmd = 'LOAD_DATA_RF';
	objCommandRF.url = this.href;
	objCommandRF.loadData();
	
	return false;
});

var sort = 'DESC';
var sortClass = 'sort-desc';

$('#tblRF th a').live('click', function() {
	if (sort == 'DESC') {
		sort = 'ASC';
		sortClass = 'sort-asc';
	} else {
		sort = 'DESC';
		sortClass = 'sort-desc';
	}
	
	$('#tblRF a').removeClass('sort-asc');
	$('#tblRF a').removeClass('sort-desc');
	
	$(this).addClass(sortClass);
	
	objCommandRF.params.cmd = 'LOAD_DATA_RF';
	objCommandRF.params.order = this.getAttribute('data-order');
	objCommandRF.params.sort = sort;
	objCommandRF.loadData();
});
</script>