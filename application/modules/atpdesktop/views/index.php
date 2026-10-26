<div class="innerLR" ng-app="mainApp">	
	<ng-view></ng-view>
</div>

<script src="<?php echo base_url('assets/js/angular-1.5.8/angular.js') ?>"></script>
<script src="<?php echo base_url('assets/js/angular-1.5.8/angular-route.min.js') ?>"></script>
<link href="<?php echo base_url('assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('assets/js/number_format.js') ?>"></script>

<style>
.datagrid-header-rownumber { width:50px; }
.datagrid-cell-rownumber { width:50px; }
select { width:auto; }
</style>

<script>
var baseUrl = '<?php echo base_url() ?>';


var app =  angular.module('mainApp', ['ngRoute']);

app.controller('ListController', function($scope, $http,multipartForm) {
	
	$scope.title = 'ATP Desktop';
	/*Assigned value Regions, Areas and Sites from Controller*/
	$scope.regions = <?php echo $regions ?>;
	
	var params = { cmd: 'getList' };
	
	$scope.listAtpDesktop = function() {
		$('#gridatpdesktop').datagrid({
			url: '<?php echo site_url('atpdesktop') ?>',
			queryParams: params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'po_no', title: 'PO No.', width: 100, sortable: true },
				{ field: 'site_link', title: 'Site Link', width: 150, sortable: true, align: 'left' },				
				{ field: 'visit_date', title: 'Visit Plan', width: 180, sortable: true, align: 'left' },
				{ field: 'pic', title: 'Assigned PIC', width: 150, sortable: true, align: 'left' },
				{ field: 'visit_type_desc', title: 'SOW (Visit Type)', width: 200, sortable: true, align: 'left' },			
				{ field: 'usr_pm_desktop_by', title: 'PM Desktop By', width: 150, sortable: true, align: 'left' },
				{ field: 'pm_desktop_on', title: 'PM Desktop On', width: 130, sortable: true, align: 'left'},	
				{ field: 'desc_status', title: 'Status', width: 150, sortable: true, align: 'left'},		
				{ field: 'action', title: 'Action', width: 100, sortable: false, align: 'center' }
			]],
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
	
		
	var search = function() {
		dateFormat.saveMode();
		params.query = $scope.searchText;
		
		$scope.listAtpDesktop();
	};
	
	$scope.search = function() {
		search();
	};
	
	document.getElementById('query').onkeypress = function() {
		if (event.keyCode == 13 || event.which == 13) {
			search();
		}
	};
	/*Action to Filter by Region*/
	/*Define Function Sort by Region*/
	document.getElementById('regions').onchange = function() {
		params.region = this.value;
		/*Call function listSite to Load Data*/
		$scope.listAtpDesktop();
	};
	/*Scope Function Filter Date*/
	$scope.filterDate = function(){
		/*Check First Date From and Date To is Filled*/
		var flag = true;
		if(($("#schDateFrom").val()).length == 0){
			var message = "Please Fill Date From";
			coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
			flag = false;
		}
		if(flag && ($("#schDateTo").val()).length == 0){
			var message = "Please Fill Date To";
			coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
			flag = false;
		}
		if(flag){
			/*Append Param Date From and Date To*/			
			params.schDateFrom = $("#schDateFrom").val();
			params.schDateTo = $("#schDateTo").val();
			$scope.listAtpDesktop();
		}

	};
	/*End Define Sort by Area*/
	/*Call the Function listSite to Load the Data*/
	$scope.listAtpDesktop();
});

app.controller('DetailController',function($scope,$http,$location,multipartForm) {
	$scope.title = 'ATP Desktop';
	      
});

app.config(['$routeProvider', '$locationProvider',
    function($routeProvider, $locationProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'atpdesktop/view/list.html',
                controller: 'ListController'
            }).
			when('/detail/:siteID', {
                templateUrl: 'atpdesktop/view/detail.html',
                controller: 'DetailController'
			});
			
		// use the HTML5 History API
        $locationProvider.html5Mode(false);
	}
]);

app.directive('fileModel', ['$parse', function($parse){
	return {
		restrict: 'A',
		link: function(scope, element, attrs){
			var model = $parse(attrs.fileModel);
			var modelSetter = model.assign;

			element.bind('change', function(){
				scope.$apply(function(){
					modelSetter(scope, element[0].files[0]);
					console.log("Test 123");
				})
			})
		}
	}
}])

app.service('multipartForm', function($http, $location, $routeParams){
	this.post = function(uploadUrl, data){
		var fd = new FormData();
		fd.append('post-form',1);
		for(var key in data){
			console.log(key+":"+data[key]);
			fd.append(key, data[key]);
		}
		$http.post(uploadUrl, fd, {
			transformRequest: angular.indentity,
			headers: { 'Content-Type': undefined }
		}).then(function(response) {
			if (response.data.status == 'success') {
				$location.path('/');
			} else {			
				
				coolNotification('<ul style="text-align:left">' + response.data.message + '</ul>', 'error');
			}
		});
	}
})

</script>
