<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Candidates Data</h4>
	</div>
	
	<div class="widget-body">
		<div class="dataTables_wrapper form-inline">

			<div class="row-fluid" style="padding:0 0 15px">
				<div class="span6">
					<div class="dataTables_length">
						<label>Show 
							<select name="perPage" id="perPage" style="width:80px" onchange="listCandidate.changePerPage()">
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
							<input type="text" style="margin:0px; padding:4px;" class="search-query input-medium" id="query-candidate" name="query-candidate" placeholder="Search..." autocomplete="off" />
							<button onclick="return listCandidate.search()" class="btn">Search</button>
						</div>
					</form>
				</div>
			</div>
			
			<div style="overflow:auto">
				<table class="table table-bordered table-primary table-vertical-center">
					<thead>
						<tr>
							<th>Site ID</th>
							<th>Site Name</th>						
							<th>TP Name</th>
							<th>Lat.</th>
							<th>Long.</th>
							<th>Address</th>
							<th>Distance</th>
							<th>Rank</th>
						</tr>
					</thead>
					<tbody id="document-data-candidate"></tbody>
				</table>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="dataTables_info" id="recinfoAreaCandidate" style="padding:20px 0"></div>
				</div>
				<div class="span6">
					<div class="dataTables_paginate paging_bootstrap pagination" id="pagination-candidate"></div>
				</div>
			</div>
			
		</div>
		
	</div>
</div>

<script>
var listCandidate = {
	params: {
		cmd: '',
		per_page: 10,
		query: '',
		site_id: <?php echo (int) $this->core->decode($this->uri->segment(3)) ?>
	
	},
	url: '<?php echo site_url('/site_info') ?>',
	loadData: function() {
		this.params.cmd = 'LOAD_DATA_SITE_CANDIDATE';
		this.params.per_page = document.getElementById('perPage').value;
		
		$.ajax({
			url: this.url,
			data: this.params,
			dataType: 'json',
			beforeSend: function() {},
			success: function(json) {
				if (typeof json.loggedin != 'undefined' && !json.loggedin) {
					alert(json.message);
					window.location.href = json.redirect_url;
				}
				
				if (typeof json.data != 'undefined' && json.data.length > 0) {
					var html = '';
					for (var i = 0; i < json.data.length; i++) {
						var siteId = json.data[i].site_id != null ? json.data[i].site_id :'&nbsp;';
						var siteName = json.data[i].site_name != null ? json.data[i].site_name :'&nbsp;';
						var tpName = json.data[i].tp_name != null ? json.data[i].tp_name :'&nbsp;';
						var latitude = json.data[i].lattitude != null ? json.data[i].lattitude :'&nbsp;';
						var longitude = json.data[i].longitude != null ? json.data[i].longitude :'&nbsp;';
						var address = json.data[i].address != null ? json.data[i].address :'&nbsp;';
						
						html += '\
							<tr>\
								<td>' + siteId + '</td>\
								<td>' + siteName + '</td>\
								<td>' + tpName + '</td>\
								<td>' + latitude + '</td>\
								<td>' + longitude + '</td>\
								<td>' + address + '</td>\
								<td>' + parseFloat(json.data[i].distance).toFixed(2) + '</td>\
								<td>' + json.data[i].rank + '</td>\
							</tr>\
						';
					}
					$('#document-data-candidate').html(html);
					$('#recinfoAreaCandidate').html(json.rec_info);
					$('#pagination-candidate').html(json.pagination);
				
				} else {
					var html = '\
						<tr>\
							<td colspan="9"><div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">×</button>Candidate not found.</div></td>\
						</tr>\
					';
					$('#document-data-candidate').html(html);
					$('#recinfoAreaCandidate').html(json.rec_info);
				}
			},
			complete: function() {}
		});
	},
	search: function() {
		this.params.cmd = 'LOAD_DATA_SITE_CANDIDATE';
		this.params.query = document.getElementById('query-candidate').value;
		this.url = '<?php echo site_url('/site_info') ?>';
		this.loadData();
		
		return false;
	},
	order: function() {
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
			
			listCandidate.params.cmd = 'LOAD_DATA_SITE_CANDIDATE';
			listCandidate.params.order = this.getAttribute('data-order');
			listCandidate.params.sort = sort;
			listCandidate.loadData();
		});
	},
	pagination: function() {
		$('#pagination a').live('click', function() {
			listCandidate.params.cmd = 'LOAD_DATA_SITE_CANDIDATE';
			listCandidate.url = this.href;
			listCandidate.loadData();
			
			return false;
		});
	},
	init: function() {
		this.loadData();
		this.order();
		this.pagination();
	}
};

listCandidate.init();
</script>