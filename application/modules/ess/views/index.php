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
	
	$scope.title = 'ESS';
	$scope.regions = <?php echo $regions ?>;
	/*define variable region with data from controller*/
	$scope.regions = <?php echo $regions ?>;
	
	var params = { cmd: 'getEssList' };
	
	$scope.listEss = function() {
		$('#gridEss').datagrid({
			url: '<?php echo site_url('ess') ?>',
			queryParams: params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'po_no', title: 'PO No.', width: 150, sortable: true },
				{ field: 'region_name', title: 'Region', width: 160, sortable: true },
				{ field: 'ess_no', title: 'ESS No.', width: 200, sortable: true },
				{ field: 'download_file', title: 'File', width: 250, sortable: true },
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
	
	$scope.delete = function(essId) {
		coolConfirmation('Are you sure delete this data?', function() {
			$http.delete(baseUrl + 'ess?essId=' + essId).then(function(response) {
				if (response.data.status == 'success') {
					$scope.listEss();
				}
			});
		});
	};
	
	var search = function() {
		dateFormat.saveMode();
		params.query = $scope.searchText;
		
		$scope.listEss();
	};
	
	$scope.search = function() {
		search();
	};
	
	document.getElementById('query').onkeypress = function() {
		if (event.keyCode == 13 || event.which == 13) {
			search();
		}
	};
	
	
	/*Define Function Sort by Region*/
	document.getElementById('regions').onchange = function() {
		params.region = this.value;
		/*Call function listDin to Load Data*/
		$scope.listEss();
	};
	
	$scope.exportESS = baseUrl + 'ess/exportEss';
	
	$scope.listEss();

});

app.controller('AddController', function($scope, $http, $location, multipartForm) {
	$scope.title = 'Create Ess';
	$scope.po_options = <?php echo $po_no ?>;
	dateFormat.init();
	
	$scope.dataEss = {};
	
	$scope.save = function() {
		var uploadUrl = baseUrl + 'ess/add';
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(uploadUrl, $scope.dataEss);
	}

	/*On Focus PO Look UP*/
	document.getElementById('po').onfocus = function() {
		$scope.lookUpPO();
	};
	/*Look Up PO*/
	$scope.lookUpPO = function(){
		var url = "<?php echo site_url('/po/lookup_po') ?>";

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
			            var row = $('#gridPo').datagrid('getSelected');

			            if (row != null) {
			              console.log("po_id :"+row.po_id);   
			              $('#po').val(row.po_no);
			              $('#input_po_id').val(row.po_id);  
			              $scope.dataEss.po_id = row.po_id;                           
			            }
			            
			            $.fancybox.close();
			        };
			    }
		});
	}
});

app.controller('EditController', function($scope, $http, $location, $routeParams, multipartForm) {
	$scope.title = 'Edit ESS';
	
	var essId = $routeParams.essId;
	$scope.po_options = <?php echo $po_no ?>;
	
	$http.get(baseUrl + 'ess/edit/' + essId).then(function(data) {
		$scope.dataEss = {
			ess_no: data.data.ess_no,
			po_id: data.data.po_id,
			po_no: data.data.po_no,
			file: data.data.ess_file
		};
		
		setTimeout(function() {
			dateFormat.init();
			
		});
	});
	
	$scope.save = function() {
		dateFormat.saveMode();
		var updateUrl = baseUrl + "ess/edit/"+essId;	
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(updateUrl, $scope.dataEss);
	}

	/*On Focus PO Look UP*/
	document.getElementById('po').onfocus = function() {
		$scope.lookUpPO();
	};
	/*Look Up PO*/
	$scope.lookUpPO = function(){
		var url = "<?php echo site_url('/po/lookup_po') ?>";

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
			            var row = $('#gridPo').datagrid('getSelected');

			            if (row != null) {
			              console.log("po_id :"+row.po_id);   
			              $('#po').val(row.po_no);
			              $('#input_po_id').val(row.po_id);  
			              $scope.dataEss.po_id = row.po_id;                           
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
                templateUrl: 'ess/view/list.html',
                controller: 'ListController'
            }).
            when('/add', {
                templateUrl: 'ess/view/form.html',
                controller: 'AddController'
            }).
			when('/edit/:essId', {
                templateUrl: 'ess/view/form.html',
                controller: 'EditController'
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
			fd.append(key, data[key]);
		}
		$http.post(uploadUrl, fd, {
			transformRequest: angular.indentity,
			headers: { 'Content-Type': undefined }
		}).then(function(response) {
			if (response.data.status == 'success') {
				var redirect_url = "<?php echo base_url("/ess")?>";
				coolNotification('<ul style="text-align:left">' + response.data.message + '</ul>', 'success',function(){
					
				});					
				$location.path('/');
							
			} else {			
				
				coolNotification('<ul style="text-align:left">' + response.data.message + '</ul>', 'error');
			}
		});
	}
})

</script>