<?php error_reporting(0);?>	
	
<script src="<?php echo base_url('/assets/js/easyui/jquery.easyui.min.js') ?>"></script>
<link href="<?php echo base_url('/assets/js/easyui/themes/default/easyui.css') ?>" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/icon.css">

<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/datagrid-detailview.js"></script>
<style type="text/css">
.datagrid-body .datagrid-cell{
	word-wrap: break-word;
	white-space: normal;
}
</style>

<script language="javascript" src="<?php echo base_url();?>assets/js/jquery.form.js"></script>
<style type="text/css">
	.datagrid-body .datagrid-cell{
   		word-wrap: break-word;
   		white-space: normal;
	}
</style>

<form action="#" class="form-horizontal" style="margin-bottom: 0;" method="POST" id="search_site">
	
	<div class="widget">
		
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		
		<input type="hidden" name="select_site_id">
		
		<div class="widget-body">
			
			<div class="row-fluid">
				
				<div>
					
					<div id="tb" style="padding:3px">
					    
						<span>Search :</span>
					    
						<input id="srcSite" placeholder="Type text to search...." style="line-height:26px;border:1px solid #ccc">
					    
						<a href="#" class="easyui-linkbutton" plain="true"
                                                   onclick="searching()"><i class="icon-cari" style="background-position: -48px 0;"></i></a>
					
					</div>

					<table id="dg"></table>

                    <div class="clearfix"></div>

					<div class="form-actions">
						<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Confirm</button>
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

function searching(){

    $('#dg').datagrid('load',{
        q: $('#srcSite').val(),
    });
}

jQuery(document).ready(function() {
	$('#dg').datagrid({
		pagination:true,
		pageSize:10,
		url:"<?php echo base_url().'referensi/site_kandidat/getAllSite' ?>",
		fitColumns:true,
		singleSelect:true,
		checkOnSelect:true,
		selectOnCheck:true,
		toolbar:"#tb",
		idField:"site_id",
		textField:'site_code',
		rownumbers:true,
		height:400,
		mode:'remote',
		width:600,
		columns:[[
			{field:'ck',checkbox:true},
			{field:'site_code',title:'Site ID',width:30,sortable:true},			
			{field:'vendor_id',title:'Vendor ID',width:30,sortable:true},	
			{field:'site_name',title:'Site Name',width:30,sortable:true},
			{field:'territory_name',title:'Region Name',width:30,sortable:true}
		]],		
		onCheck : function(rowIndex,rowData){
		},
		onCheckAll : function(data){
			$.each(data, function(i, item) {
			});
		},
		checkOnSelect : function(data){
			$.each(data, function(i, item) {
			});
		}
	});

	$('.fancybox-wrap').draggable({ handle: ".widget-head" });

	

});
</script>