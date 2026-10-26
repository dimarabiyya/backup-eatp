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

	$scope.title = 'Site Data Master';
	/*Assigned value Regions, Areas and Sites from Controller*/
	$scope.regions = <?php echo $regions ?>;
	$scope.areas = <?php echo $areas ?>;
	$scope.site_types = <?php echo $site_types ?>;

	var params = { cmd: 'getSiteList' };

	$scope.listSite = function() {
		$('#gridSite').datagrid({
			url: '<?php echo site_url('sites') ?>',
			queryParams: params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'site_id', title: 'Site ID', width: 100, sortable: true },
				{ field: 'site_name', title: 'Site Name', width: 200, sortable: true, align: 'left' },
				{ field: 'site_address', title: 'Site Address', width: 350, sortable: true, align: 'left' },
				{ field: 'latitude', title: 'Latitude', width: 80, sortable: true, align: 'left' },
				{ field: 'longitude', title: 'Longitude', width: 80, sortable: true, align: 'left' },
				{ field: 'site_type_name', title: 'Site Type', width: 100, sortable: true, align: 'left'},
				{ field: 'area_name', title: 'Area', width: 100, sortable: true, align: 'left'},
				{ field: 'region_name', title: 'Region', width: 100, sortable: true, align: 'left'},
				{ field: 'location_id', title: 'Location Id', width: 100, sortable: true, align: 'left'},
				{ field: 'action', title: 'Action', width: 150, sortable: false, align: 'left' }
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

	$scope.delete = function(siteID) {
		coolConfirmation('Are you sure delete this Approval Data?', function() {
			$http.delete(baseUrl + 'sites?siteID=' + siteID).then(function(response) {
				if (response.data.status == 'success') {
					$scope.listSite();
				}
			});
		});
	};

	var search = function() {
		dateFormat.saveMode();
		params.query = $scope.searchText;

		$scope.listSite();
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
		$scope.listSite();
		/*update list area*/
		var url_filter_area = "<?php echo base_url()?>sites/filterareabyregion/"+this.value;
		$.get(url_filter_area,function(data){
			$("#areas").empty();
			$.each(jQuery.parseJSON(data), function(i, item) {
			    $('#areas').append($('<option>', {
		              value: i,
		              text : item
	          	}));
		  	});
		})
	};
	/*End Define Sort by Region*/
	/*Action to Filter by Area*/
	/*Define Function Sort by Area*/
	document.getElementById('areas').onchange = function() {
		params.area = this.value;
		/*Call function listSite to Load Data*/
		$scope.listSite();
	};
	/*End Define Sort by Area*/

	/*Action to Filter by Site Type*/
	/*Action to Filter by Site Type*/
	/*Define Function Sort by Area*/
	document.getElementById('site_types').onchange = function() {
		params.site_type = this.value;
		/*Call function listSite to Load Data*/
		$scope.listSite();
	};
	/*End Define Sort by Area*/

	$scope.exportSITE = baseUrl + 'sites/exportSite';

	/*Call the Function listSite to Load the Data*/
	$scope.listSite();
});

app.controller('AddController',function($scope,$http,$location,multipartForm) {
	$scope.title = 'Create Site Data';
	/*Assigned value Regions, Areas and Sites from Controller*/
	$scope.regions = <?php echo $regions ?>;
	$scope.areas = <?php echo $areas ?>;
	$scope.site_types = <?php echo $site_types ?>;

	dateFormat.init();


	$scope.dataSite = {};

	$scope.save = function() {
		var uploadUrl = baseUrl + "sites/add";
		/*Save the Form*/
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(uploadUrl, $scope.dataSite);

	};

});

app.controller('EditController',function($scope, $http, $location, $routeParams,multipartForm) {
	$scope.title = 'Edit Site Data';
	/*Route Param approvalID have to be same with varible name in Edit Config line: 149*/
	var siteID = $routeParams.siteID;
	/*Assigned value Regions, Areas and Sites from Controller*/
	$scope.regions = <?php echo $regions ?>;
	$scope.areas = <?php echo $areas ?>;
	$scope.site_types = <?php echo $site_types ?>;


	$http.get(baseUrl + 'sites/edit/' + siteID).then(function(data) {

		$scope.dataSite = {
			site_id: data.data.site_id,
			site_name: data.data.site_name,
			latitude : data.data.latitude,
			site_address : data.data.site_address,
			longitude: data.data.longitude,
			area_id: data.data.area_id,
			region_id: data.data.region_id,
			site_type: data.data.site_type,
			location_id: data.data.location_id,
			remark: data.data.remarks,
		};

		setTimeout(function() {
			dateFormat.init();
			justNumberFormat.init();
		});
	});

	$scope.save = function() {
		var updateUrl = baseUrl + "sites/edit/"+siteID;
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(updateUrl, $scope.dataSite);

	}
});


app.controller('DetailController',function($scope, $http, $location, $routeParams,multipartForm) {
	/*Route Param approvalID have to be same with varible name in Edit Config line: 149*/
	var siteID = $routeParams.siteID;
	 $http.get(baseUrl + 'sites/edit/' + siteID).then(function(data) {

	 	var type = data.data.site_type_name;
	 	if(data.data.site_type_name == null){
	 		type= "-";
	 	}
		$scope.dataSite = {
			site_id: data.data.site_id,
			site_name: data.data.site_name,
			latitude : data.data.latitude,
			site_address : data.data.site_address,
			longitude: data.data.longitude,
			area_id: data.data.area_id,
			region_id: data.data.region_id,
			site_type: data.data.site_type,
			remark: data.data.remarks,
			region:data.data.region_name,
			area:data.data.area_name,
			location_id:data.data.location_id,
			s_type:type
		};

		setTimeout(function() {
			dateFormat.init();
			justNumberFormat.init();
		});
	});

});

app.controller('ImportController',function($scope,$http,$location,multipartForm) {
	$scope.title = 'Import File Site Data';
	/*Assigned value Regions, Areas and Sites from Controller*/
	dateFormat.init();


	$scope.dataSite = {};

	$scope.save = function() {
		var uploadUrl = baseUrl + "sites/import/upload";
		/*Save the Form*/
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(uploadUrl, $scope.dataSite);

	};

});

app.config(['$routeProvider', '$locationProvider',
    function($routeProvider, $locationProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'sites/view/list.html',
                controller: 'ListController'
            }).
            when('/add', {
                templateUrl: 'sites/view/form.html',
                controller: 'AddController'
            }).
			when('/import/', {
                templateUrl: 'sites/view/import.html',
                controller: 'ImportController'
			}).
			when('/detail/:siteID', {
                templateUrl: 'sites/view/detail.html',
                controller: 'DetailController'
			}).
			when('/edit/:siteID', {
                templateUrl: 'sites/view/form.html',
                controller: 'EditController'
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
				coolNotification('<ul style="text-align:left">' + response.data.message + '</ul>', 'success');
				$location.path('/');
			} else {

				coolNotification('<ul style="text-align:left">' + response.data.message + '</ul>', 'error');
			}
		});
	}
})

</script>
