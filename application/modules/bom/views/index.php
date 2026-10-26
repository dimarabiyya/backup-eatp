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
	
	$scope.title = 'BOM';
	$scope.regions = <?php echo $regions ?>;
	$scope.po_options = <?php echo $po_no ?>;
	$scope.status_boq_options = <?php echo $status_boq ?>;
	
	var params = { cmd: 'getBomList' };
	
	$scope.listBom = function() {
		$('#gridBom').datagrid({
			url: '<?php echo site_url('bom') ?>',
			queryParams: params,
			method: 'GET',
			pageSize: 15,
			pageList: [10,15, 20, 30, 40, 50, 100],
			columns:[[
				{ field: 'po_no', title: 'PO No.', width: 100, sortable: true },
				{ field: 'config', title: 'Config', width: 150, sortable: true },
				{ field: 'code', title: 'Code.', width: 100, sortable: true },
				{ field: 'material_description', title: 'Material Des', width: 150, sortable: true },
				{ field: 'qty_requirement', title: 'Qty Reqirement', width: 100, sortable: true },
				{ field: 'qty_po', title: 'Qty PO', width: 100, sortable: true },
				{ field: 'qty_actual', title: 'Qty Actual', width: 100, sortable: true },
				{ field: 'status_desc', title: 'Status Boq', width: 100, sortable: true },
				{ field: 'remarks', title: 'Remarks', width: 150, sortable: true },
				{ field: 'uom', title: 'Uom', width: 150, sortable: true },
				{ field: 'link_id', title: 'Link ID', width: 150, sortable: true },
				{ field: 'region_name', title: 'Region', width: 150, sortable: true },
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
	
	$scope.delete = function(bomId) {
		coolConfirmation('Are you sure delete this BOM data?', function() {
			$http.delete(baseUrl + 'bom?bomId=' + bomId).then(function(response) {
				if (response.data.status == 'success') {
					$scope.listBom();
				}
			});
		});
	};
	
	var search = function() {
		dateFormat.saveMode();

		params.query = $scope.searchText;
		
		$scope.listBom();
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
		$scope.listBom();
	};
	
	$scope.exportBOM = baseUrl + 'bom/exportBom';
	
	$scope.listBom();

});

app.controller('AddController', function($scope, $http, $location, multipartForm) {
	$scope.title = 'Create BOM';
	$scope.po_options = <?php echo $po_no ?>;
	$scope.status_boq_options = <?php echo $status_boq ?>;
	dateFormat.init();
	
	$scope.dataBom = {
		qty_requirement 	: 0,
		qty_po 				: 0,
		qty_actual 			: 0,
	};
	
	
	$scope.save = function() {
		var uploadUrl = baseUrl + 'bom/add';
		/*Call Function Post from MultipartForm*/
		$scope.dataBom.link_id_int = $("#link_id_int").val();
		var flag = multipartForm.post(uploadUrl, $scope.dataBom);
	}

	/*Function when po is changed*/
	$scope.loadSiteLink = function(po_id) {
		var url = "<?php echo base_url("/bom/linkidBypo")?>"+"/"+po_id;
		console.log("===>"+url);
		$('#link_id_int').empty();
		$.get(url,function(data){
			var obj_link = jQuery.parseJSON(data);
			$.each( obj_link, function( key, item ) {         
          
	          $('#link_id_int').append($('<option>', { 
	              value: item.link_id_int,
	              text : item.link_id
	          }));
	        });
		});
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
			              $scope.dataBom.po_id = row.po_id;
			              $scope.loadSiteLink(row.po_id);                           
			            }
			            
			            $.fancybox.close();
			        };
			    }
		});
	}
});

app.controller('EditController', function($scope, $http, $location, $routeParams, multipartForm) {
	$scope.title = 'Edit BOM';
	
	var bomId = $routeParams.bomId;
	$scope.po_options = <?php echo $po_no ?>;
	$scope.status_boq_options = <?php echo $status_boq ?>;
	
	
	$http.get(baseUrl + 'bom/edit/' + bomId).then(function(data) {
		$('#link_id_int').empty();
		/*Add Option in Link ID*/
		$('#link_id_int').append($('<option>', { 
	              value: data.data.link_id_int,
	              text : data.data.link_id,
	              selected : 'selected'
	    }));
		$scope.dataBom = {
			po_id 				: data.data.po_id,
			po_no 				: data.data.po_no,
			config 				: data.data.config,
			code 				: data.data.code,
			material_description: data.data.material_description,
			qty_requirement 	: parseInt(data.data.qty_requirement),
			qty_po 				: parseInt(data.data.qty_po),
			qty_actual 			: parseInt(data.data.qty_actual),
			status_boq 			: data.data.status_boq,
			remarks 			: data.data.remarks,
			uom 				: data.data.uom,
		};
		
		setTimeout(function() {
			dateFormat.init();
			justNumberFormat.init();
		});
	});
	
	/*Function when po is changed*/
	document.getElementById('po_id').onchange = function() {
		var url = "<?php echo base_url("/bom/linkidBypo")?>"+"/"+this.value;
		console.log("===>"+url);
		$('#link_id_int').empty();
		$.get(url,function(data){
			var obj_link = jQuery.parseJSON(data);
			$.each( obj_link, function( key, item ) {         
          
	          $('#link_id_int').append($('<option>', { 
	              value: item.link_id_int,
	              text : item.link_id
	          }));
	        });
		});
	};
	

	$scope.save = function() {
		dateFormat.saveMode();
		var updateUrl = baseUrl + "bom/edit/"+bomId;
		$scope.dataBom.link_id_int = $("#link_id_int").val();
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(updateUrl, $scope.dataBom);
	}

	/*Function when po is changed*/
	$scope.loadSiteLink = function(po_id) {
		var url = "<?php echo base_url("/bom/linkidBypo")?>"+"/"+po_id;
		console.log("===>"+url);
		$('#link_id_int').empty();
		$.get(url,function(data){
			var obj_link = jQuery.parseJSON(data);
			$.each( obj_link, function( key, item ) {         
          
	          $('#link_id_int').append($('<option>', { 
	              value: item.link_id_int,
	              text : item.link_id
	          }));
	        });
		});
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
			              $scope.dataBom.po_id = row.po_id;
			              $scope.loadSiteLink(row.po_id);                           
			            }
			            
			            $.fancybox.close();
			        };
			    }
		});
	}

});

app.controller('ImportController',function($scope,$http,$location,multipartForm) {
	$scope.title = 'Import File BOM Data';
	/*Assigned value Regions, Areas and Sites from Controller*/	
	dateFormat.init();
	
	
	$scope.dataBom = {};	

	$scope.save = function() {		
		var uploadUrl = baseUrl + "bom/import/upload";	
		/*Save the Form*/	
		/*Call Function Post from MultipartForm*/
		var flag = multipartForm.post(uploadUrl, $scope.dataBom);
		
	};
      
});

app.config(['$routeProvider', '$locationProvider',
    function($routeProvider, $locationProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'bom/view/list.html',
                controller: 'ListController'
            }).
            when('/add', {
                templateUrl: 'bom/view/form.html',
                controller: 'AddController'
            }).
			when('/import/', {
                templateUrl: 'bom/view/import.html',
                controller: 'ImportController'
			}).
			when('/edit/:bomId', {
                templateUrl: 'bom/view/form.html',
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
		fd.append('form_post',1); //untuk validasi
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