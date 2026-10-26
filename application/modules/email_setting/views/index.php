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
	
	$scope.title = 'Email Recipient Setting';
	
	
	var params = { cmd: 'getApprovalList' };
	
	$scope.listApproval = function() {
		$('#gridApproval').datagrid({
			url: '<?php echo site_url('email_setting') ?>',
			queryParams: params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'template_name', title: 'Template Name', width: 200, sortable: true },
				 			
				{ field: 'status', title: 'Active', width: 250, sortable: true, align: 'center' },
				 
				{ field: 'action', title: 'Action', width: 150, sortable: false, align: 'center' }
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
	
	$scope.delete = function(approvalId) {
		coolConfirmation('Are you sure delete this Approval Data?', function() {
			$http.delete(baseUrl + 'approval?approvalId=' + approvalId).then(function(response) {
				if (response.data.status == 'success') {
					$scope.listApproval();
				}
			});
		});
	};
	
	var search = function() {
		dateFormat.saveMode();
		params.query = $scope.searchText;
		
		$scope.listApproval();
	};
	
	$scope.search = function() {
		search();
	};
	
	document.getElementById('query').onkeypress = function() {
		if (event.keyCode == 13 || event.which == 13) {
			search();
		}
	};

	
	
	$scope.listApproval();
});

app.controller('AddController',function($scope,$http,$location,multipartForm) {
	$scope.title = 'Create Approval';
	
	dateFormat.init();
	
	
	$scope.dataApproval = {};	

	$scope.save = function() {		
		var uploadUrl = baseUrl + "approval/add";
		$scope.dataApproval.user_id = $("#input_user_id").val();	
		/*Save the Form*/	
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(uploadUrl, $scope.dataApproval);
		
	};
      
});

app.controller('EditController',function($scope, $http, $location, $routeParams,multipartForm) {
	$scope.title = 'Edit Approval Data';	
	/*Route Param approvalID have to be same with varible name in Edit Config line: 149*/	
	var approvalId = $routeParams.approvalId;
	

	$http.get(baseUrl + 'approval/edit/' + approvalId).then(function(data) {
		/*Check if is_mandatory returned data is True
		Check the Is Mandatory Checkbox
		*/
		if(data.data.is_mandatory=="1"){
			document.getElementById("is_mandatory").checked = true;
		}

		$scope.dataApproval = {
			template_name: data.data.template_name,
			user_id: data.data.user_id,
			user_name : data.data.user_name,
			is_mandatory : data.data.is_mandatory,
			order_no: parseInt(data.data.order_no),
			remark: data.data.remark,
		};
		
		setTimeout(function() {
			dateFormat.init();
			justNumberFormat.init();
		});
	});
	document.getElementById("is_mandatory").onclick = function(){
		var flag_click = 0;
		if(this.checked) flag_click =1;
		
		$scope.dataApproval.is_mandatory = flag_click;	
	}
	$scope.save = function() {		
		var updateUrl = baseUrl + "approval/edit/"+approvalId;	
		/*Call Function Post from MultipartForm*/
		if($("#input_user_id").val().length!=0){
			$scope.dataApproval.user_id = $("#input_user_id").val();
		}
		var flag = multipartForm.post(updateUrl, $scope.dataApproval);
		
	}
});

app.config(['$routeProvider', '$locationProvider',
    function($routeProvider, $locationProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'approval/view/list.html',
                controller: 'ListController'
            }).
            when('/add', {
                templateUrl: 'approval/view/form.html',
                controller: 'AddController'
            }).
			when('/edit/:approvalId', {
                templateUrl: 'approval/view/form.html',
                controller: 'EditController'
			});
			
		// use the HTML5 History API
        $locationProvider.html5Mode(false);
	}
]);

app.service('multipartForm', function($http, $location, $routeParams){
	this.post = function(uploadUrl, data){
		var fd = new FormData();
		fd.append('post-form',1);
		for(var key in data){
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