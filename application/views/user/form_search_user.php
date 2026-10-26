<?php error_reporting(0);?>	


<style type="text/css">
	.datagrid-body .datagrid-cell{
   		word-wrap: break-word;
   		white-space: normal;
	}
</style>


<style type="text/css">
	.datagrid-body .datagrid-cell{
   		word-wrap: break-word;
   		white-space: normal;
	}
</style>

<form action="#" class="form-horizontal" style="margin-bottom: 0;" method="POST" id="search_site">
	
	<div class="widget">		
		
		<input type="hidden" name="select_site_id">
		
		<div class="widget-body">
			
			<div class="row-fluid">
				
				<div>					
					
					<table id="dg2" class="easyui-datagrid" style="width:600px;height:400px"
							
							title= "List User"
							url = "<?php echo base_url()?>user/getAllEsc"
							fitColumns = "true"	
							toolbar = "#tb2"
							idField = "user_div_id"
							textField = "user_div_name"
							rownumbers = "true"
							height = "400"
							mode = "remote"
							width = "600"							
							fitColumns="true">
							
							<thead>
								<tr>								
								<th field="user_div_name" width="80">Group Name</th>	
								</tr>
							</thead>

					</table>

                    <div class="clearfix"></div>

					<div class="form-actions">					
						<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Close</button>
						<script>
							$(".btn-close").click(function(){$.fancybox.close();});
						</script>
					</div>

				</div>

			</div>

		</div>

	</div>

</form>
<script type="text/javascript">

var idCurrent = $('.select_site_id').last().attr('value');

function searching2(idx){
	
    var y = window.scrollY;	
    
	$('#ddv-'+idx).datagrid('load',{
        q: $('#srcSite2-'+idx).val(),
    });
		
	$("html, body").animate({ scrollTop: y }, "slow");
}

$('#dg2').datagrid({
	view: detailview,
	detailFormatter:function(index,row){
		return '<div id="tb2-' + index + '" style="padding:2px"> <span>Search :</span><input id="srcSite2-' + index + '" placeholder="Type text to search...." style="line-height:26px;border:1px solid #ccc"><a href="#" class="easyui-linkbutton" plain="true" onclick="searching2('+index+')"><i class="icon-cari" style="background-position: -48px 0;"></i></a></div><table id="ddv-' + index + '"></table>';
	},
		
	onExpandRow: function(index,row){
		$('#ddv-'+index).datagrid({
			url:"<?php echo base_url()?>user/getEscUser/"+row.user_div_id,
			pagination:true,
			pageSize:10,
			fitColumns:true,
			checkOnSelect:true,
			selectOnCheck:true,
			checkAll:false,
		//	toolbar = "#tb2-"+index,
			idField:"user_id",
			textField:'user_name',
			rownumbers:true,
			loadMsg:'',
			height:'auto',
			columns:[[
				{field:'ck',checkbox:true},
				{field:'user_id',title:'User ID',width:100},
				{field:'user_name',title:'User Name',width:100}				
			]],

			checkOnSelect : function(data){
				$.each(data, function(i, item) {					
				});
			},

			onResize:function(){
				$('#dg2').datagrid('fixDetailRowHeight',index);
			},

			onLoadSuccess:function(){
				setTimeout(function(){
					$('#dg2').datagrid('fixDetailRowHeight',index);
				},0);
			},

			onSelect : function(index,row){
				var userID = row.user_id;				
				var user = parent.$('#user_id').val();				
				user = user.replace(userID+',','');
				parent.$('#user_id').val(user+userID+',');

				var userName = row.user_name;				
				var userN = parent.$('#escnull').val();
				userN = userN.replace(userName+',','');
				parent.$('#escnull').val(userN+userName+',');
				//alert($('#user_id').val());
			},

			onUnselect : function(index,row){
				var userID = row.user_id;
				var user = parent.$('#user_id').val();
				user = user.replace(userID+',','');
				parent.$('#user_id').val(user);				
				//alert($('#user_id').val());

				var userName = row.user_name;				
				var userN = parent.$('#escnull').val();
				userN = userN.replace(userName+',','');
				parent.$('#escnull').val(userN);
			},

			onSelectAll : function(data){
				$.each(data, function(index, row) {
					var userID = row.user_id;				
					var user = parent.$('#user_id').val();
					user = user.replace(userID+',','');
					parent.$('#user_id').val(user+userID+',');				
					//alert($('#user_id').val());

					var userName = row.user_name;				
					var userN = parent.$('#escnull').val();
					userN = userN.replace(userName+',','');
					parent.$('#escnull').val(userN+userName+',');
					});
			},

			onUnselectAll : function(data){
				$.each(data, function(index, row) {
					var userID = row.user_id;
					var user = parent.$('#user_id').val();
					user = user.replace(userID+',','');
					parent.$('#user_id').val(user);				
					//alert($('#user_id').val());

					var userName = row.user_name;				
					var userN = parent.$('#escnull').val();
					userN = userN.replace(userName+',','');
					parent.$('#escnull').val(userN);
					});
			},

		});

		$('#dg2').datagrid('fixDetailRowHeight',index);

	}

});

</script>
