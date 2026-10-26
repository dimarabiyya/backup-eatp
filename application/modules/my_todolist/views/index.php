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
	
	$scope.title = 'My Todo List';
	
	
	var params = { cmd: 'getApprovalList' };
 
	$scope.listApproval = function() {
		$('#gridApproval').datagrid({
			url: '<?php echo site_url('my_todolist') ?>',
			queryParams: params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'po_no', title: 'PO No', width: 200, sortable: true },
				{ field: 'ewo_no', title: 'EWO NO', width: 200, sortable: true, align: 'center' },
				//start edit by hadi 2018-09-14
				{ field: 'link_id', title: 'Site Link', width: 200, sortable: true },
				{ field: 'site_ne_name', title: 'Site NE Name', width: 200, sortable: true },
				{ field: 'site_fe_name', title: 'Site FE Name', width: 200, sortable: true },
				//end edit by hadi 2018-09-14
				{ field: 'terakhir_dirubah', title: 'Tanggal Perubahan', width: 250, sortable: true, align: 'center' },
				{ field: 'config', title: 'Konfigurasi', width: 250, sortable: true, align: 'center'},	
				{ field: 'status_todolist', title: 'Status', width: 150, sortable: false, align: 'center' },
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
        
        
      
	$scope.filterDate = function(){
		/*Check First Date From and Date To is Filled*/
		var flag = true;
		if(($("#SearchDateFrom").val()).length == 0){
			var message = "Please Fill Date From";
			coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
			flag = false;
		}
		if(flag && ($("#SearchDateTo").val()).length == 0){
			var message = "Please Fill Date To";
			coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
			flag = false;
		}
		if(flag){
			/*Append Param Date From and Date To*/
			console.log($("#SearchDateFrom").val());
			console.log($("#SearchDateTo").val());
			params.SearchDateFrom = $("#SearchDateFrom").val();
			params.SearchDateTo = $("#SearchDateTo").val();
			$scope.listApproval();
		}

	};

        var search = function() {
		dateFormat.saveMode();
                
		params.query = $scope.searchText;
                params.fsp = $scope.searchStatus;
        
                   params.SearchDateFrom = document.getElementById('SearchDateFrom').value;
		params.SearchDateTo = document.getElementById('SearchDateTo').value;
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

  
app.config(['$routeProvider', '$locationProvider',
    function($routeProvider, $locationProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'my_todolist/view/list.html',
                controller: 'ListController'
            });
			
		// use the HTML5 History API
        $locationProvider.html5Mode(false);
	}
]);

 

</script>