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

app.controller('ListController', function($scope, $http) {
	
	$scope.title = 'PO';
	$scope.regions = <?php echo $regions ?>;
	
	var params = { cmd: 'getPoList' };
	
	$scope.listPo = function() {
		$('#gridPo').datagrid({
			url: '<?php echo site_url('po') ?>',
			queryParams: params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'po_no', title: 'PO No.', width: 90, sortable: true },
				{ field: 'po_date_format', title: 'PO Date', width: 100, sortable: true, align: 'left' },
				{ field: 'link_id', title: 'LINK', width: 100, sortable: true, align: 'left' },
				{ field: 'item', title: 'Item', width: 50, sortable: true, align: 'right' },
				{ field: 'description', title: 'Description', width: 250, sortable: true },
				{ field: 'order_type', title: 'Order Type', width: 100, sortable: true },
				{ field: 'ewo_no', title: 'NBWO/EWO', width: 150, sortable: true },
				{ field: 'region_name', title: 'Region', width: 150, sortable: true },
				{ field: 'progress_badge', title: 'Status', width: 100, sortable: true },
				{ field: 'action', title: 'Action', width: 100, sortable: false, align: 'left' }
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
	
	$scope.delete = function(poId) {
		coolConfirmation('Are you sure delete this PO?', function() {
			$http.delete(baseUrl + 'po?poId=' + poId).then(function(response) {
				if (response.data.status == 'success') {
					$scope.listPo();
				}
			});
		});
	};
	
	var search = function() {
		dateFormat.saveMode();
		
		params.poDateFrom = document.getElementById('poDateFrom').value;
		params.poDateTo = document.getElementById('poDateTo').value;
		params.query = $scope.searchText;
		
		$scope.listPo();
	};
	
	$scope.search = function() {
		search();
	};
	
	document.getElementById('query').onkeypress = function() {
		if (event.keyCode == 13 || event.which == 13) {
			search();
		}
	};
	
	document.getElementById('regions').onchange = function() {
		params.region = this.value;
		$scope.listPo();
	};
	/*Scope Function Filter Date*/
	$scope.filterDate = function(){
		/*Check First Date From and Date To is Filled*/
		var flag = true;
		if(($("#poDateFrom").val()).length == 0){
			var message = "Please Fill Date From";
			coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
			flag = false;
		}
		if(flag && ($("#poDateTo").val()).length == 0){
			var message = "Please Fill Date To";
			coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
			flag = false;
		}
		if(flag){
			/*Append Param Date From and Date To*/
			console.log($("#poDateFrom").val());
			console.log($("#poDateTo").val());
			params.poDateFrom = $("#poDateFrom").val();
			params.poDateTo = $("#poDateTo").val();
			$scope.listPo();
		}

	};

	$scope.exportPO = baseUrl + 'po/exportPo';
	
	$scope.listPo();
});

app.controller('AddController', function($scope, $http, $location) {
	$scope.title = 'Create PO';
	$scope.regions = <?php echo $regions ?>;
	
	dateFormat.init();
	justNumberFormat.init();
	
	$scope.dataPo = {};
	
	$scope.save = function() {
		dateFormat.saveMode();
		
		$scope.dataPo.poDate = document.getElementById('poDate').value;
		
		$http.post(baseUrl + 'po/add', $scope.dataPo).then(function(response) {
			if (response.data.status == 'success') {
				$location.path('/');
			} else {
				coolNotification('<ul style="text-align:left">' + response.data.message + '</ul>', 'error');
			}
		});
	}
	
});

app.controller('EditController', function($scope, $http, $location, $routeParams) {
	$scope.title = 'Edit PO';
	$scope.regions = <?php echo $regions ?>;
	
	var poId = $routeParams.poId;
	
	$http.get(baseUrl + 'po/edit/' + poId).then(function(data) {
		$scope.dataPo = {
			poNo: data.data.po_no,
			poDate: data.data.po_date,
			item: data.data.item,
			region: data.data.region_id,
			orderType: data.data.order_type,
			description: data.data.description
		};
		
		setTimeout(function() {
			dateFormat.init();
			justNumberFormat.init();
		});
	});
	
	$scope.save = function() {
		dateFormat.saveMode();
		$scope.dataPo.poDate = document.getElementById('poDate').value;
		
		$http.post(baseUrl + 'po/edit/' + poId, $scope.dataPo).then(function(response) {
			if (response.data.status == 'success') {
				$location.path('/');
			} else {
				coolNotification('<ul style="text-align:left">' + response.data.message + '</ul>', 'error');
			}
		});
	}
});
/*Route for Detail*/
app.controller('DetailController',function($scope,$http,$location,$routeParams,multipartForm) {
	$scope.title = 'Detail PO Data';

	var poID = $routeParams.poID;
	$http.get(baseUrl + 'po/detail/' + poID).then(function(data) {
		$scope.dataPo = {
			po_no: data.data.message.detail_po.po_no,
			po_date: data.data.message.detail_po.po_date,
			region : data.data.message.detail_po.region_name,
			links : data.data.message.po_link
		};		
	});

});

/*Function Import*/
app.controller('ImportController',function($scope,$http,$location,multipartForm) {
	$scope.title = 'Import File PO Data';
	/*Assigned value Regions, Areas and Sites from Controller*/	
	dateFormat.init();
	
	
	$scope.dataPo = {};	
	$scope.save = function() {		
		var uploadUrl = baseUrl + "po/import/upload";	
		var check_po = baseUrl + "po/import/checkPO";	
		/*CHECK DATA PO if Exist*/
		var data = $scope.dataPo;
		var fd = new FormData();
		fd.append('post-form',1);
		for(var key in data){
			console.log(key+":"+data[key]);
			fd.append(key, data[key]);
		}
		$http.post(check_po, fd, {
			transformRequest: angular.indentity,
			headers: { 'Content-Type': undefined }
		}).then(function(response) {
			if (response.data.status == 'data-found') {
				coolConfirmation(''+response.data.message, function() {
					var flag = multipartForm.post(uploadUrl, $scope.dataPo);
				});
				
			}else if (response.data.status == 'error'){				
				
					coolNotification('<ul style="text-align:left">' + response.data.message + '</ul>', 'error');								
			}else{
				var flag = multipartForm.post(uploadUrl, $scope.dataPo);
			}
		});
		/*Save the Form*/	
		/*Call Function Post from MultipartForm*/
		
	};
      
});

/*Config Route*/
app.config(['$routeProvider', '$locationProvider',
    function($routeProvider, $locationProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'po/view/list.html',
                controller: 'ListController'
            }).
			when('/detail/:poID', {
                templateUrl: 'po/view/detail.html',
                controller: 'DetailController'
			}).
			when('/import/', {
                templateUrl: 'po/view/import.html',
                controller: 'ImportController'
			});
			//.otherwise({
			//	redirectTo: '/'
			//});
		
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