<div class="filter-bar">
	<form class="form-search">
		<div class="input-append">
			<input type="text" style="margin:0px; padding:4px;" class="search-query input-medium" id="query" name="query" placeholder="Search..." autocomplete="off" />
			<button onclick="return objCommand.search()" class="btn">Search</button>
		</div>
	</form>
</div>

<table class="table table-bordered table-primary table-vertical-center ">
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
	<tbody id="document-data"></tbody>
</table>

<div class="separator top form-inline small">			
	<div class="pagination pagination-small pull-right" id="pagination" style="margin: 0;"></div>
	<div class="clearfix"></div>
</div>

<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script>
var objCommand = {
	params: {
		cmd: '',
		per_page: 10,
		query: '',
		site_id: <?php echo $site_id?>
	},
	url: '<?php echo site_url('/site_info') ?>',
	loadData: function() {
		this.params.cmd = 'LOAD_DATA_SITE_CANDIDATE';
		
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
					document.getElementById('document-data').innerHTML = html;
					document.getElementById('pagination').innerHTML = json.pagination;
				
				} else {
					var html = '\
						<tr>\
							<td colspan="9"><div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">×</button>Candidate not found.</div></td>\
						</tr>\
					';
					$('#document-data').html(html);
				}
			},
			complete: function() {
				siteOverlay.remove();
			}
		});
	},
	search: function() {
		this.params.cmd = 'LOAD_DATA_SITE_CANDIDATE';
		this.params.query = document.getElementById('query').value;
		this.url = '<?php echo site_url('/site_info') ?>';
		this.loadData();
		
		return false;
	},
	delete: function(id) {
		if (confirm('Are you sure you want to delete?')) {
			this.params.cmd = 'DELETE_SITE_CANDIDATE';
			this.params.id = id;
			this.url = '<?php echo site_url('/site_info') ?>';
			$.ajax({
				url: this.url,
				data: this.params,
				dataType: 'json',
				success: function(json) {
					objCommand.loadData();
				}
			});
		}
	}
};

objCommand.loadData();

$('#pagination a').live('click', function() {
	objCommand.params.cmd = 'LOAD_DATA_SITE_CANDIDATE';
	objCommand.url = this.href;
	objCommand.loadData();
	
	return false;
});
</script>