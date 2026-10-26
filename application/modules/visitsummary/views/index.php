<?php
/*
 * Daftar ditampilkan langsung di halaman (tanpa ngRoute/ng-view), sehingga URL tetap
 * /visitsummary tanpa tambahan "#/" dan template tidak perlu diambil lewat request kedua.
 */
?>
<div class="innerLR" ng-app="mainApp" ng-controller="ListController">
	<?php $this->load->view('list.html'); ?>
</div>

<script src="<?php echo base_url('assets/js/angular-1.5.8/angular.js') ?>"></script>
<link href="<?php echo base_url('assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('assets/js/number_format.js') ?>"></script>

<style>
	.datagrid-header-rownumber {
		width: 50px;
	}

	.datagrid-cell-rownumber {
		width: 50px;
	}

	select {
		width: auto;
	}
</style>

<script>
	function Getuserappr(val) {
		//alert(val);
		//alert(id+id2);

		//var region_selected = $("#region").val();
		var url = "<?php echo site_url('/visitsummary/get_appr_list') ?>";
		$.fancybox({
			href: url,
			type: 'ajax',

			//ajax: { data: { cmd: 'getData' ,region:region_selected } }, // e: it used for selected element
			ajax: {
				data: {
					datapos: val
				}
			},
			width: 800,
			scrolling: true,
			autoSize: true,
			fitToView: true,
			closeClick: true,
			openEffect: 'fade',
			closeEffect: 'fade',
			afterShow: function() {
				$('.fancybox-wrap').draggable({
					handle: ".widget-head"
				});

			}
		});
	};

	function getECRFappr(visit_id) {
		//* EDITED 25-10-2023 (DISABLED ECRF IF BELOW 43369)
			if (visit_id < 43369){
				return ;
			}
		//*
		var url = "<?php echo site_url('/visitsummary/get_ECRF_approval') ?>";
		$.fancybox({
			href: url,
			type: 'ajax',
			ajax: {
				data: {
					visit_id: visit_id
				}
			},
			width: 1000,
			scrolling: true,
			autoSize: true,
			fitToView: true,
			openEffect: 'fade',
			closeEffect: 'fade',
			afterShow: function() {
				$('.fancybox-wrap').draggable({
					handle: ".widget-head"
				});

			}
		});
	};


	var baseUrl = '<?php echo base_url() ?>';


	var app = angular.module('mainApp', []);

	app.controller('ListController', function($scope, $http) {

		$scope.title = 'Visit Summary';
		/*Assigned value Regions, Areas and Sites from Controller*/
		$scope.regions = <?php echo $regions ?>;
		$scope.months = <?php echo $months ?>;
		$scope.years = <?php echo $years ?>;

		var params_visited = {
			cmd: 'getJson',
			sch_id: 3
		};

		/*For Visited Scheduled*/
		$scope.listGridVisited = function() {
			$('#gridVisitedSchedule').datagrid({
				url: '<?php echo site_url('visitsummary') ?>',
				queryParams: params_visited,
				method: 'GET',
				pageSize: 10,
				pageList: [5, 10, 15, 20, 30, 40, 50, 100],
				columns: [
					[{
							field: 'visit_id',
							title: 'Visit ID',
							width: 100,
							sortable: true
						},
						{
							field: 'tpd_id',
							title: 'TPD ID',
							width: 100,
							sortable: true
						},
						{
							field: 'po_no',
							title: 'PO No.',
							width: 100,
							sortable: true
						},
						{
							field: 'ewo_no',
							title: 'EWO NO',
							width: 100,
							sortable: true,
							align: 'left'
						},
						{
							field: 'po_description',
							title: 'Project Name',
							width: 100,
							sortable: true,
							align: 'left'
						},
						{
							field: 'link_id',
							title: 'Site LINK',
							width: 100,
							sortable: true,
							align: 'left'
						},
						{
							field: 'site_ne_name',
							title: 'Site NE',
							width: 100,
							sortable: true,
							align: 'left'
						},
						{
							field: 'address_ne',
							title: 'Address NE',
							width: 200,
							sortable: true,
							align: 'left'
						},
						{
							field: 'site_fe_name',
							title: 'Site FE',
							width: 100,
							sortable: true,
							align: 'left'
						},
						{
							field: 'address_fe',
							title: 'Address FE',
							width: 200,
							sortable: true,
							align: 'left'
						},
						{
							field: 'field_eng',
							title: 'PIC Name',
							width: 200,
							sortable: true,
							align: 'left'
						},
						{
							field: 'fop_hs_name',
							title: 'FOP Hs',
							width: 200,
							sortable: true,
							align: 'left'
						},
						{
							field: 'visit_type_label',
							title: 'Visit Type',
							width: 200,
							sortable: true,
							align: 'left'
						},
						{
							field: 'region',
							title: 'Region',
							width: 150,
							sortable: true,
							align: 'left'
						},
						//start edited by Hadi 2018-03-08
						// {
						// 	field: 'code_checkin',
						// 	title: 'Check In Code',
						// 	width: 150,
						// 	sortable: true,
						// 	align: 'left'
						// },
						// {
						// 	field: 'code_checkout',
						// 	title: 'Check Out Code',
						// 	width: 150,
						// 	sortable: true,
						// 	align: 'left'
						// },
						//end
						{
							field: 'visit_plan',
							title: 'Visit Plan',
							width: 100,
							sortable: true,
							align: 'left'
						},
						//{ field: 'actual_visit_date', title: 'Actual Visit Date', width: 100, sortable: true, align: 'left' },
						{
							field: 'desc_status',
							title: 'Status',
							width: 100,
							sortable: true,
							align: 'left'
						},
						// { field: 'stat_approve', title: 'Status Approve', width: 100, sortable: true, align: 'center' },
						//{ field: 'approval_status_order', title: 'Approval Status', width: 200, sortable: true, align: 'left' },
						{
							field: 'action',
							title: 'Action',
							width: 350,
							sortable: true,
							align: 'left'
						},
					]
				],
				//toolbar: '#tbPo',
				striped: true,
				pagination: true,
				singleSelect: true,
				fitColumns: false,
				rownumbers: true,
				height: 'auto',
				onLoadSuccess: function(data) {
					dateFormat.init();
				}
			});
		};

		$scope.filterVisitSummary = function() {
			var region = $('#widget-visited select[name="filter_region"]').val();
			var dt_from = $('#widget-visited input[name="date_from"]').val();
			var dt_to = $('#widget-visited input[name="date_to"]').val();
			var fsp = $('#widget-visited select[name="filter_fsp"]').val();
			var search_b = $('#widget-visited input[name="search_b"]').val();
			params_visited.region = region;
			params_visited.schDateFrom = dt_from;
			params_visited.schDateTo = dt_to;
			params_visited.fsp = fsp;
			params_visited.search_b = search_b;
			$scope.listGridVisited();
		}

		/*
		$scope.searchVisitSummary = function (){
		var query = $('#widget-visited input[name="searchbox"]').val();
		params_visited.query = query;
		$scope.listGridVisited();
		}

		document.querySelector('#widget-visited input[name="searchbox"]').onkeypress = function(){
		if (event.keyCode == 13 || event.which == 13) {
		var query = $('#widget-visited input[name="searchbox"]').val();
		params_visited.query = query;
		$scope.listGridVisited();
		}

		}
		*/
		document.querySelector('#widget-visited select[name="filter_region"]').onchange = function() {
			params_visited.region = this.value;
			$scope.listGridVisited();
		}


		/*====================================================================================================================*/

		var params_unvisited = {
			cmd: 'getJson',
			sch_id: 1
		};

		/*For Visited Scheduled*/
		$scope.listGridUnVisited = function() {
			$('#gridUnVisitedSchedule').datagrid({
				url: '<?php echo site_url('visitsummary') ?>',
				queryParams: params_unvisited,
				method: 'GET',
				pageSize: 10,
				pageList: [5, 10, 15, 20, 30, 40, 50, 100],
				columns: [
					[{
							field: 'visit_id',
							title: 'Visit ID',
							width: 100,
							sortable: true
						},
						{
							field: 'tpd_id',
							title: 'TPD ID',
							width: 100,
							sortable: true
						},
						{
							field: 'po_no',
							title: 'PO No.',
							width: 100,
							sortable: true
						},
						{
							field: 'ewo_no',
							title: 'EWO NO',
							width: 100,
							sortable: true,
							align: 'left'
						},
						{
							field: 'po_description',
							title: 'Project Name',
							width: 100,
							sortable: true,
							align: 'left'
						},
						{
							field: 'link_id',
							title: 'LINK',
							width: 200,
							sortable: true,
							align: 'left'
						},
						//start edited by Hadi 2018-03-08
						{
							field: 'site_ne_name',
							title: 'Site NE',
							width: 100,
							sortable: true,
							align: 'left'
						},
						{
							field: 'address_ne',
							title: 'Address NE',
							width: 200,
							sortable: true,
							align: 'left'
						},
						{
							field: 'site_fe_name',
							title: 'Site FE',
							width: 100,
							sortable: true,
							align: 'left'
						},
						{
							field: 'address_fe',
							title: 'Address FE',
							width: 200,
							sortable: true,
							align: 'left'
						},
						//end
						{
							field: 'field_eng',
							title: 'PIC Name',
							width: 200,
							sortable: true,
							align: 'left'
						},
						{
							field: 'priority',
							title: 'Priority',
							width: 200,
							sortable: true,
							align: 'left'
						},
						{
							field: 'visit_type_label',
							title: 'Visit Type',
							width: 200,
							sortable: true,
							align: 'left'
						},
						{
							field: 'region',
							title: 'Region',
							width: 200,
							sortable: true,
							align: 'left'
						},
						// {
						// 	field: 'code_checkin',
						// 	title: 'Check In Code',
						// 	width: 150,
						// 	sortable: true,
						// 	align: 'left'
						// },
						// {
						// 	field: 'code_checkout',
						// 	title: 'Check Out Code',
						// 	width: 150,
						// 	sortable: true,
						// 	align: 'left'
						// },
						{
							field: 'visit_plan',
							title: 'Visit Plan',
							width: 100,
							sortable: true,
							align: 'left'
						},
						//{ field: 'actual_visit_date', title: 'Actual Visit Date', width: 100, sortable: true, align: 'left' },
						{
							field: 'desc_status',
							title: 'Status',
							width: 100,
							sortable: true,
							align: 'left'
						},
						//{ field: 'approval_status_order', title: 'Approval Status', width: 200, sortable: true, align: 'left' },
						{
							field: 'action',
							title: 'Action',
							width: 350,
							sortable: true,
							align: 'left'
						},
					]
				],
				//toolbar: '#tbPo',
				striped: true,
				pagination: true,
				singleSelect: true,
				fitColumns: false,
				rownumbers: true,
				height: 'auto',
				onLoadSuccess: function(data) {
					dateFormat.init();
				}
			});
		};

		/*Search Block Function*/
		$scope.filterUnVisitSummary = function() {
			var region = $('#widget-unvisited select[name="filter_region"]').val();
			var dt_from = $('#widget-unvisited input[name="date_from"]').val();
			var dt_to = $('#widget-unvisited input[name="date_to"]').val();
			var fsp = $('#widget-unvisited select[name="filter_fsp"]').val();
			var search_b = $('#widget-unvisited input[name="search_b"]').val();
			//console.log("Test "+dt_to);
			params_unvisited.region = region;
			params_unvisited.schDateFrom = dt_from;
			params_unvisited.schDateTo = dt_to;
			params_unvisited.fsp = fsp;
			params_unvisited.search_b = search_b;
			$scope.listGridUnVisited();
		}

		/*
		$scope.searchUnVisitSummary = function (){
		var query = $('#widget-unvisited input[name="searchbox"]').val();
		params_unvisited.query = query;
		$scope.listGridUnVisited();
		}

		document.querySelector('#widget-unvisited input[name="searchbox"]').onkeypress = function(){
		if (event.keyCode == 13 || event.which == 13) {
		var query = $('#widget-unvisited input[name="searchbox"]').val();
		params_unvisited.query = query;
		$scope.listGridUnVisited();
		}

		}
		*/
		document.querySelector('#widget-unvisited select[name="filter_region"]').onchange = function() {
			params_unvisited.region = this.value;
			$scope.listGridUnVisited();
		}
		/*End Block Function*/

		/*Function Get Visited Summary*/
		$scope.getVisitedSummary = function() {
			$scope.listGridVisited();
			$("#tab-visited").addClass("active");
			$("#widget-visited").addClass("active");
			$("#tab-unvisited").removeClass("active");
			$("#widget-unvisited").removeClass("active");

		};
		/*Function Get Unvisited Summary*/
		$scope.getUnVisitedSummary = function() {
			$scope.listGridUnVisited();
			$("#tab-unvisited").addClass("active");
			$("#widget-unvisited").addClass("active");
			$("#tab-visited").removeClass("active");
			$("#widget-visited").removeClass("active");
		}

		$scope.listGridVisited();
	});
</script>