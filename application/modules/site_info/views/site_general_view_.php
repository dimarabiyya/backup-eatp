<h1>Site Info</h1>

<table id="tblSiteInfo"></table>

<script src="<?php echo base_url('/assets/js/jquery-1.7.1.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/easyui/jquery.easyui.min.js') ?>"></script>
<link href="<?php echo base_url('/assets/js/easyui/themes/default/easyui.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('/assets/js/easyui/themes/icon.css') ?>" rel="stylesheet" />

<script><!--
$(function() {

	$('#tblSiteInfo').datagrid({
		title: 'Site Info',
		width: '100%',
		height: 400,
		url: '<?php echo base_url('/assets/js/easyui/demo/datagrid_data.json') ?>',
		sortOrder: 'desc',
		columns: [[
			{ field: 'siteId', title: 'Site ID', width: 120 },
			{ field: 'oldSiteId', title: 'Old Site ID', width: 120 },
			{ field: 'siteName', title: 'Site Name', width: 200 },
			{ field: 'siteAddress', title: 'Site Address', width: 300 },
			{ field: 'latitude', title: 'Latitude', width: 200 },
			{ field: 'longitude', title: 'Longitude', width: 200 },
			{ field: 'action', title: 'Action', width: 800 }
		]],
		pagination: true
	});

});
//--></script>