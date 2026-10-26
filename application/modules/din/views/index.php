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
	
	$scope.title = 'Delivery Instruction Note';
	$scope.po_options = <?php echo $po_no ?>;
	/*define variable region with data from controller*/
	$scope.regions = <?php echo $regions ?>;
	
	var params = { cmd: 'getDinList' };
	
	$scope.listDin = function() {
		$('#gridDin').datagrid({
			url: '<?php echo site_url('din') ?>',
			queryParams: params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'po_no', title: 'PO No.', width: 100, sortable: true },
				{ field: 'region', title: 'Region', width: 250, sortable: true, align: 'left'},	
				{ field: 'din_no', title: 'DIN No.', width: 250, sortable: true, align: 'left'},	
				{ field: 'download_file', title: 'File', width: 130, sortable: false, align: 'left' },			
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
	
	$scope.delete = function(dinId) {
		coolConfirmation('Are you sure delete this DIN Data?', function() {
			$http.delete(baseUrl + 'din?dinId=' + dinId).then(function(response) {
				if (response.data.status == 'success') {
					$scope.listDin();
				}
			});
		});
	};
	
	var search = function() {
		dateFormat.saveMode();
		params.query = $scope.searchText;
		
		$scope.listDin();
	};
	
	$scope.search = function() {
		search();
	};
	
	/*Define Function Sort by Region*/
	document.getElementById('regions').onchange = function() {
		params.region = this.value;
		/*Call function listDin to Load Data*/
		$scope.listDin();
	};
	/*End Define Sort by Region*/
	document.getElementById('query').onkeypress = function() {		
		if (event.keyCode == 13 || event.which == 13) {
			search();
		}
	};
	
	$scope.exportDIN = baseUrl + 'din/exportDin';
	
	$scope.listDin();
});

app.controller('AddController', function($scope,$http,$location, multipartForm) {
	$scope.title = 'Create DIN';
	$scope.po_options = <?php echo $po_no ?>;
	dateFormat.init();
	
	
	$scope.dataDin = {};	
	$scope.din_types = <?php echo $din_type ?>;

	$scope.save = function() {		
		var uploadUrl = baseUrl + "din/add";	
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(uploadUrl, $scope.dataDin);
		
	};
      
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
			              $scope.dataDin.po_id = row.po_id;                           
			            }
			            
			            $.fancybox.close();
			        };
			    }
		});
	}
});

app.controller('EditController',function($scope, $http, $location, $routeParams,multipartForm) {
	$scope.title = 'Edit DIN';		
	var dinId = $routeParams.dinId;
	$scope.po_options = <?php echo $po_no ?>;
	$scope.din_types = <?php echo $din_type ?>;

	$http.get(baseUrl + 'din/edit/' + dinId).then(function(data) {
		$scope.dataDin = {
			din_no: data.data.din_no,
			po_id: data.data.po_id,
			po_no: data.data.po_no,
			file: data.data.din_file,
			din_type:data.data.din_type
		};
		
		setTimeout(function() {
			dateFormat.init();
			justNumberFormat.init();
		});
	});
	
	$scope.save = function() {		
		var updateUrl = baseUrl + "din/edit/"+dinId;	
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(updateUrl, $scope.dataDin);
		
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
			              $scope.dataDin.po_id = row.po_id;                           
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
                templateUrl: 'din/view/list.html',
                controller: 'ListController'
            }).
            when('/add', {
                templateUrl: 'din/view/form.html',
                controller: 'AddController'
            }).
			when('/edit/:dinId', {
                templateUrl: 'din/view/form.html',
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
				coolNotification('<ul style="text-align:left">' + response.data.message + '</ul>', 'success');
				$location.path('/');	
				
			} else {			
				
				coolNotification('<ul style="text-align:left">' + response.data.message + '</ul>', 'error');
			}
		});
	}
})

</script>