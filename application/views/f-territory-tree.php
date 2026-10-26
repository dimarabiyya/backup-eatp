<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo base_url()?>file/js/easyui/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>file/js/easyui/jquery.easyui.min.js"></script>
<script>
	$(function(){
		$('#records').treegrid({
			title:'Territory Tree',
			//iconCls:'icon-save',
			width: '100%',
			height: 650,
			nowrap: false,
			rownumbers: true,
			animate:true,
			//collapsible:true,
			url:'<?php echo base_url()?>index.php/territory/territory_json',
			idField:'id',
			treeField:'name',
			onBeforeLoad:function(row,param){
				if (row){
					$(this).treegrid('options').url = '<?php echo base_url()?>index.php/territory/territory_json/'+row.ter_id+'/'+row.tipe;					
				}
				
				$('.panel-header').attr('style', 'width:auto');
				$('.datagrid-wrap').attr('style', 'width:auto; height:400px;');
				$('.datagrid-td-rownumber').css('width', '100px');
			}
		});
	});
	
	var codeIndex = 1000;
	function append(id,ter_id,tipe,limit){
		var items = [];
		$.getJSON('<?php echo base_url()?>index.php/territory/territory_json/'+ter_id+'/'+tipe+'/'+limit, function(data) {	
			$.each(data, function(key, val) {
			  items.push(  
					{ 'id': val.id,
					  'name': val.name,
					  'size': val.size,
					  'date': val.date,
					  'tipe': val.tipe,
					  'iconCls': val.iconCls
				});
			});
			var prev = 'o';
			if( tipe == 6) prev = 'a';
			$('#records').treegrid('remove', id);
			$('#records').treegrid('append', {				
				parent: prev + ter_id,
				data: items
			});
			
		});
	}
	function updateActions(){
		var rowcount = $('#tt').datagrid('getRows').length;
		for(var i=0; i<rowcount; i++){
			$('#tt').datagrid('updateRow',{
				index:i,
				row:{action:''}
			});
		}
	}
		
	function deleterow(index,ter_id,jml){
		if(jml == 0){
			$.messager.confirm('Confirm','Are you sure to delete this data ?',function(r){
				if (r){
					$.post(
						'<?php echo base_url()?>index.php/territory/territory_delete', 
						{ter_id:ter_id},
						function(data) {
						    if(data.status == 'OK') {
							$('#tt').datagrid('deleteRow', index);
							//updateActions();
							setTimeout("location.reload(true);",100);
						    }
						},
						"json"
					    ).error(function() {
						alert("-");
					    });
				}
			});
		}else {
			$.messager.alert('Failed to delete data',"You cannot delete this data, because data have a node",'warning');
		}
	}
	
</script>
<style>
.icon-territory-more{
	background:url('<?php echo base_url()?>file/images/down_16.png') no-repeat 0px 0px;
}

<?php
$dt = $this->mservices->getValueFromTable("t_mtr_territory_type");
foreach($dt as $row){
echo".icon-territory-". $row->territory_type_id ."{
	background:url('". base_url() ."file/images/territory_icon/". $row->territory_type_icon ."') no-repeat 0px 0px;
}";
}
?>
.icon-territory-outlet{
	background:url('<?php echo base_url()?>file/images/territory_icon/Letter O.png') no-repeat 0px 0px;
}
.icon-territory-agent{
	background:url('<?php echo base_url()?>file/images/territory_icon/Letter A.png') no-repeat 0px 0px;
}
.treegrid-tr-tree td, .datagrid-td-rownumber { width:100px; }
</style>



<table  id="records">
	<thead>
		<tr>
			<th field="name" width="700">Territory</th>
			<th field="size" width="200">Maintained By</th>
			<th field="date" width="230">Action</th>
		</tr>
	</thead>
</table>

<script type="text/javascript">
function formatActionw(value,row,index){
	
	var e = '<a href="#" onclick="editrow('+index+')">Edit</a> ';
	var d = '<a href="#" onclick="deleterow('+index+')">Delete</a>';
	return e+d;
	
}
</script>