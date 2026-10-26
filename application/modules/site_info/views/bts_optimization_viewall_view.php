<style>
.table-primary thead th { font-size:12px; }
th a { padding:0 20px 0 0; }
th a, th a:focus { color:#FFFFFF; text-decoration:underline; }
th a.sort-asc { background:url('<?php echo base_url('/themes/sims/images/asc.png') ?>') no-repeat right center; }
th a.sort-desc { background:url('<?php echo base_url('/themes/sims/images/desc.png') ?>') no-repeat right center; }
</style>

<div class="heading-buttons">
	<h3>BTS Optimization</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/bts_optimization') ?>" class="btn btn-primary btn-icon glyphicons list"><i></i> Optimization List</a>
	</div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
	
	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading"><i></i> BTS Optimization Data</h4>
		</div>
		
		<div class="widget-body">
			
			<div class="row-fluid">
				<div class="span2">Site:</div>
				<div class="span10"><?php echo $bts_optimization['site_code'] ?></div>
			</div>
			<div class="row-fluid">
				<div class="span2">Visit Date:</div>
				<div class="span10"><?php echo $bts_optimization['visit_date'] ?></div>
			</div>
			<div class="row-fluid">
				<div class="span2">Vendor Engineer:</div>
				<div class="span10"><?php echo $bts_optimization['vendor_engineer'] ?></div>
			</div>
			<div class="row-fluid">
				<div class="span2">Vendor Rigger:</div>
				<div class="span10"><?php echo $bts_optimization['vendor_rigger'] ?></div>
			</div>
			<div class="row-fluid">
				<div class="span2">SF Engineer:</div>
				<div class="span10"><?php echo $bts_optimization['sf_engineer'] ?></div>
			</div>
			<div class="row-fluid">
				<div class="span2">SF Rigger:</div>
				<div class="span10"><?php echo $bts_optimization['sf_rigger'] ?></div>
			</div>
			<div class="row-fluid">
				<div class="span2">Remarks:</div>
				<div class="span10"><?php echo $bts_optimization['remarks'] ?></div>
			</div>
			
		</div>
		
	</div>	
	
	<div class="widget">
		
		<div class="widget-head">
			<h4 class="heading"><i></i> BTS Data</h4>
		</div>
		
		<div class="widget-body">
		
			<div class="dataTables_wrapper form-inline">
				<div class="row-fluid" style="padding:0 0 15px">
					<div class="span6">
						<div class="dataTables_length">
							<label>Show 
								<select name="perPage" id="perPage" style="width:80px" onchange="objCommand.changePerPage()">
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
								<input type="text" style="margin:0px; padding:4px;" class="search-query input-medium" id="query" name="query" placeholder="Search..." autocomplete="off" />
								<button onclick="return objCommand.search()" class="btn">Search</button>
							</div>
						</form>
					</div>
				</div>
				
				<div style="overflow:auto">
					<table class="table table-bordered table-primary table-vertical-center">
						<thead>
							<tr>
								<th><a href="javascript:void(0)" data-order="azimuth_after">Azimuth</a></th>
								<th><a href="javascript:void(0)" data-order="electrical_tilt_after">Electrical Tilt</a></th>
								<th><a href="javascript:void(0)" data-order="mechanical_tilt_after">Mechanical Tilt</a></th>
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
</div>

<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/fancybox/jquery.fancybox-1.3.4.js') ?>"></script>
<link href="<?php echo base_url('/assets/js/fancybox/jquery.fancybox-1.3.4.css') ?>" rel="stylesheet" />

<script>
var objCommand = {
	params: {
		cmd: '',
		per_page: 10,
		query: '',
		visit_id: <?php echo $bts_optimization['visit_id'] ?>
	},
	url: '<?php echo site_url('/site_info/bts_optimization') ?>',
	loadData: function() {
		this.params.cmd = 'LOAD_DATA_BTS';
		this.params.per_page = document.getElementById('perPage').value;
		
		$.ajax({
			url: this.url,
			data: this.params,
			dataType: 'json',
			beforeSend: function() {
				siteOverlay.top = '200px';
				siteOverlay.create();
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
							<td>' + json.data[i].azimuth + '</td>\
							<td>' + json.data[i].electrical_tilt + '</td>\
							<td>' + json.data[i].mechanical_tilt + '</td>\
						</tr>\
					';
				}
				document.getElementById('document-data').innerHTML = html;
				document.getElementById('recinfoArea').innerHTML = json.rec_info;
				document.getElementById('pagination').innerHTML = json.pagination;
				
				$('.photo').fancybox({
					transitionIn: 'elastic',
					transitionOut: 'elastic',
				});
			},
			complete: function() {
				siteOverlay.remove();
			}
		});
	},
	search: function() {
		this.params.cmd = 'LOAD_DATA_BTS';
		this.params.query = document.getElementById('query').value;
		this.url = '<?php echo site_url('/site_info/bts_optimization') ?>';
		this.loadData();
		
		return false;
	},
	delete: function(id) {
		if (confirm('Are you sure you want to delete?')) {
			this.params.cmd = 'DELETE';
			this.params.id = id;
			this.url = '<?php echo site_url('/site_info/bts_optimization') ?>';
			$.ajax({
				url: this.url,
				data: this.params,
				dataType: 'json',
				success: function(json) {
					if (typeof json.loggedin != 'undefined' && !json.loggedin) {
						alert(json.message);
						window.location.href = json.redirect_url;
					}
					
					objCommand.loadData();
				}
			});
		}
	},
	changePerPage: function() {
		this.url = '<?php echo site_url('/site_info/bts_optimization') ?>';
		this.loadData();
	}
};

objCommand.loadData();

$('#pagination a').live('click', function() {
	objCommand.params.cmd = 'LOAD_DATA_BTS';
	objCommand.url = this.href;
	objCommand.loadData();
	
	return false;
});

var sort = 'DESC';
var sortClass = 'sort-desc';

$('th a').live('click', function() {
	if (sort == 'DESC') {
		sort = 'ASC';
		sortClass = 'sort-asc';
	} else {
		sort = 'DESC';
		sortClass = 'sort-desc';
	}
	
	$('th a').removeClass('sort-asc');
	$('th a').removeClass('sort-desc');
	
	$(this).addClass(sortClass);
	
	objCommand.params.cmd = 'LOAD_DATA_BTS';
	objCommand.params.order = this.getAttribute('data-order');
	objCommand.params.sort = sort;
	objCommand.loadData();
});
</script>