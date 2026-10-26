<?php if ($this->uri->segment(3) == 'edit') { ?>
<div class="row-fluid">
	<div class="span12">
		<div class="widget">				
			<div class="widget-body list">
				<ul>
					<li><?php echo $last_updated ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<div id="frame-acpdb-form"></div>

<div class="widget" id="frame-acpdb-list">
	<div class="widget-head heading-outer">
		<h4 class="heading"><i></i> ACPDB</h4>
		
		<a href="javascript:void(0)" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right" onclick="GRID_ACPDB.add()"><i></i> Add New ACPDB</a>
	</div>
	
	<div class="widget-body">
	
		<div id="tb-acpdb">
			<div class="form-search">
				<div class="input-append">
					<?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?>
					<button id="btnSearch" class="btn" onclick="GRID_ACPDB.search()"><i class="icon-search"></i></button>
				</div>
			</div>
		</div>

		<table style="height:450px" id="grid-acpdb" class="jgrid"></table>
		
	</div>
</div>

<script>
var GRID_ACPDB = {
	params: {
		cmd: 'GET_DATAGRID_ACPDB',
		site_id: document.getElementsByName('siteId')[0].value,
		query: ''
	},
	loadData: function() {
		
		<?php
		$lookup = $this->core->get_lookup('power_acpdb_photo_type');
		?>
		
		$('#grid-acpdb').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			pageList: [10,20,30,40,50,100],
			columns:[[
				{ field: 'acpdb_id', hidden: true },
				{ title: 'Actual Load', colspan: 3 },
				{ field: 'mcb_capacity_tp', title: 'MCB Capacity of TP / kwh', width: 160, fixed: true, sortable: true, align: 'right', rowspan: 2, formatter: function(value, row, index) {
					return (value != null ? value + ' Amp(AC)' : 0) + (row.mcb_capacity != null ? ' ' + row.mcb_capacity : '');
				} },
				{ field: 'mcb_capacity_acpdb', title: 'MCB Capacity of ACPDB', width: 160, fixed: true, sortable: true, align: 'right', rowspan: 2, formatter: function(value, row, index) {
					return (value != null ? value + ' Amp(AC)' : 0) + (row.acpdb_mcb_capacity != null ? ' ' + row.acpdb_mcb_capacity : '');
				} },
				
				{ title: 'Photo(s)', colspan: <?php echo count($lookup) ?> },
				
				{ field: 'actions', title: 'Actions', width: 80, align: 'center', rowspan: 2, fixed: true, formatter: function(value, row, index) {
					return '\
						<a href="javascript:GRID_ACPDB.edit(\'' + row.acpdb_id + '\')" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<a href="javascript:GRID_ACPDB.delete(\'' + row.acpdb_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
					';
				} }
				
			], [
				{ field: 'capacity_r', title: 'R', width: 100, fixed: true, sortable: true, align: 'right', formatter: function(value, row, index) {
					return value + ' Amp(AC)';
				} },
				{ field: 'capacity_s', title: 'S', width: 100, fixed: true, sortable: true, align: 'right', formatter: function(value, row, index) {
					return value + ' Amp(AC)';
				} },
				{ field: 'capacity_t', title: 'T', width: 100, fixed: true, sortable: true, align: 'right', formatter: function(value, row, index) {
					return value + ' Amp(AC)';
				} },
				
				<?php foreach ($lookup as $lo) { ?>
				{ field: 'photo-<?php echo $lo['id'] ?>', title: '<?php echo $lo['desc'] ?>', width: 120, align: 'center', rowspan: 2, formatter: function(value, row, index) {
					return ( row.photo['<?php echo $lo['id'] ?>'] != null ? '<a href="' + row.photo.large['<?php echo $lo['id'] ?>'] + '" class="photobox"><img src="' + row.photo['<?php echo $lo['id'] ?>'] + '" width="30" border="0" class="img-polaroid" /></a>' : '<img src="<?php echo base_url('/file/upload/site-info/no_image-60x60.jpg') ?>" width="30" class="img-polaroid" />');
				} },
				<?php } ?>
			]],
			toolbar: '#tb-acpdb',
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			striped: true,
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
				return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';
			},
			onExpandRow: function(index, row) {
				$('#ddv-' + index).datagrid({
					url: '<?php echo site_url('/site_info/power') ?>',
					method: 'GET',
					queryParams: { cmd: 'GET_DATAGRID_ACPDB_DETAIL', acpdb_id: row.acpdb_id },
					fitColumns: true,
					pageSize: 10,
					singleSelect: true,
					height: 'auto',
					columns:[[
						{ field: 'item', title: 'Item(s)', width: 100, sortable: true },
						{ field: 'capacity', title: 'Capacity', width: 100, sortable: true, align: 'right' },
						{ field: 'capacity_actual', title: 'Actual Capacity', width: 100, sortable: true, align: 'right' },
						{ field: 'phase_used', title: 'Phase Used', width: 100, sortable: true },
						{ field: 'allocation', title: 'Allocation', width: 100, sortable: true },
						{ field: 'linked_to_rectifier_no', title: 'Linked to Rectifier No.', width: 100, sortable: true, align: 'right' }
					]],
					onResize: function() {
						$('#grid-acpdb').datagrid('fixDetailRowHeight', index);
					},
					onLoadSuccess: function() {
						setTimeout(function() {
							$('#grid-acpdb').datagrid('fixDetailRowHeight', index);
						}, 0);
					}
				});
				$('#grid-acpdb').datagrid('fixDetailRowHeight',index);
			}
		});
	},
	search: function() {
		this.params.query = document.getElementById('query').value;
		this.loadData();
		document.getElementById('query').select();
	},
	add: function() {
		<?php if ($this->uri->segment(3) == 'edit') { ?>
		var cmd = 'EDIT_ACPDB';
		<?php } else { ?>
		var cmd = 'ADD_ACPDB';
		<?php } ?>
		
		$.ajax({
			url: document.URL,
			type: 'POST',
			data: { cmd: cmd, act: 'add' },
			dataType: 'html',
			success: function(html) {
				$('#frame-acpdb-form').html(html);
				$('#frame-acpdb-list').hide();
			}
		});
	},
	edit: function(id) {
		<?php if ($this->uri->segment(3) == 'edit') { ?>
		var cmd = 'EDIT_ACPDB';
		<?php } else { ?>
		var cmd = 'ADD_ACPDB';
		<?php } ?>
		
		$.ajax({
			url: document.URL,
			type: 'POST',
			data: { cmd: cmd, act: 'edit', acpdb_id: id },
			dataType: 'html',
			success: function(html) {
				$('#frame-acpdb-form').html(html);
				$('#frame-acpdb-list').hide();
			}
		});
	},
	delete: function(id) {
		coolConfirmation('Are you sure you want to delete?', function() {
			GRID_ACPDB.params.cmd = 'DELETE_ACPDB';
			GRID_ACPDB.params.id = id;
			$.ajax({
				url: '<?php echo site_url('/site_info/power') ?>',
				type: 'GET',
				data: GRID_ACPDB.params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_ACPDB.params.cmd = 'GET_DATAGRID_ACPDB';
						GRID_ACPDB.loadData();
					}
				}
			});
		});
	},
	init: function() {
		this.params.query = document.getElementById('query').value;
		this.loadData();
		document.getElementById('query').select();
		
		document.getElementById('query').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_ACPDB.search();
				this.focus();
			}
		};
	}
};

GRID_ACPDB.init();
</script>