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
	
	$scope.title = 'List Schedule';
	/*Assigned value Regions from Controller*/
	$scope.regions = <?php echo $regions ?>;
	
	
	var params = { cmd: 'getScheduleList' };
	
	$scope.listSchedule = function() {
		$('#gridSchedule').datagrid({
			url: '<?php echo site_url('schedule') ?>',
			queryParams: params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'po_no', title: 'PO No.', width: 150, sortable: true },
				{ field: 'ewo_nbwo', title: 'EWO/NBWO', width: 150, sortable: true, align: 'left' },				
				{ field: 'link_id', title: 'Hop (LINK ID)', width: 200, sortable: true, align: 'left' },
				{ field: 'region', title: 'Region', width: 80, sortable: true, align: 'left' },
				{ field: 'SOW_DESC', title: 'SOW', width: 250, sortable: true, align: 'left' },
				{ field: 'visit_date', title: 'Tanggal', width: 100, sortable: true, align: 'left'},	
				{ field: 'field_eng', title: 'Field Eng', width: 100, sortable: true, align: 'left'},	
				{ field: 'fop_hs_name', title: 'FOPS HS', width: 100, sortable: true, align: 'left'},	
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
	
	$scope.delete = function(visitID) {
		coolConfirmation('Are you sure delete this Schedule Data?', function() {
			$http.delete(baseUrl + 'schedule?visitID=' + visitID).then(function(response) {
				if (response.data.status == 'success') {
					$scope.listSchedule();
				}
			});
		});
	};
	
	var search = function() {
		dateFormat.saveMode();
		params.query = $scope.searchText;
		
		$scope.listSchedule();
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
		$scope.listSchedule();
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
			$scope.listSchedule();
		}

	};
	/*End Define Sort by Area*/
	/*Call the Function listSite to Load the Data*/
	$scope.listSchedule();
});

app.controller('AddController',function($scope,$http,$location,multipartForm) {
	$scope.title = "Input New Schedule Data";
    
    dateFormat.init(); 
});

app.controller('ApprovalController',function($scope, $http, $location, $routeParams,multipartForm) {
	$scope.title = "Form Approval Schedule";
 	dateFormat.init();
	$scope.dataApproval = {};
	var visit_id = $routeParams.visitID;

	/*Get Detail Schedule*/
	var detailUrl = "<?php echo base_url('')?>schedule/detailSchedule/"+visit_id;
	$http.post(detailUrl,{
			transformRequest: angular.indentity,
			headers: { 'Content-Type': undefined }
		}).then(function(response) {
			console.log(response.data);	
			if(response.data && response.data.approval){
				$("#fop_choosen").empty();
				$("#fop_choosen").html("This Schedule has been approved with FOP Hs Name :"+response.data.fop_hs_name);
			}
		});

	$scope.save = function() {		
		var post_url = baseUrl + "schedule/approval/"+visit_id;	
		//console.log("Status Approval :"+$("#status").val());
		$scope.dataApproval.status = $("#status").val();
		$scope.dataApproval.fop_name = $("#input_user_id").val();
		//console.log("==>"+$scope.dataApproval.fop_name);
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(post_url, $scope.dataApproval);
		
	}
	 /*On Focus PO Look UP*/
	document.getElementById('user').onfocus = function() {
		$scope.lookup_fop();
	};
	/*Lookup FOP User*/
	$scope.lookup_fop = function(){
		var url = "<?php echo site_url('/schedule/lookup_fop') ?>";

	      $.fancybox({
	        href: url,
	        type: 'ajax',
	        ajax: { data: { cmd: 'getData' } }, // e: it used for selected element
	        width: 600,
	        scrolling: false,
	        autoSize: true,
	        fitToView: true,
	        closeClick: false,
	        openEffect: 'fade',
	        closeEffect: 'fade',
	        afterShow: function() {
	                        $('.fancybox-wrap').draggable({handle:".widget-head"});
	                        
	          document.getElementById('btnDataSelectedOK').onclick = function() {
	                        var row = $('#gridUserPIC').datagrid('getSelected');

	                        if (row != null) {
	                          console.log("User Id :"+row.user_id);   
	                          $('#user').val(row.user_name);
	                          $('#input_user_id').val(row.user_id);                         
	                        }
	                        
	                        $.fancybox.close();
	                    };
	        }
	      });
	}
});



app.config(['$routeProvider', '$locationProvider',
    function($routeProvider, $locationProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'schedule/view/list.html',
                controller: 'ListController'
            }).
			when('/approve/:visitID', {
                templateUrl: 'schedule/view/approval.html',
                controller: 'ApprovalController'
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
