<!--
<style>
.modal {
left:40%;
width:800px;
}
</style>

<style type="text/css">
		div.innerDetails { display: none }
        #report div.arrow { background:transparent url(<?php echo base_url()?>assets/images/arrows.png) no-repeat scroll 0px -16px; width:16px; height:16px; display:block;}
        #report div.up { background-position:0px 0px;}
</style>
  
	<!-- DataTables --
	<link rel="stylesheet" media="screen" href="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/css/DT_bootstrap.css" />
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/DT_bootstrap.js"></script>
-->

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/icon.css">
	<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/datagrid-detailview.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/js/fancybox/jquery.fancybox-1.3.4.css">
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	
	<script src="<?php echo base_url()?>assets/js/overlay.js"></script>
	<style type="text/css">
	.datagrid-body .datagrid-cell{
   		word-wrap: break-word;
   		white-space: normal;
	}
	</style>
<script>

		
	
		function doSearch(i){
			if (i==2){
				$('#dg2').datagrid('load',{
					q: $('#q2').val(),
				});
			}else{
				$('#dg').datagrid('load',{
					q: $('#q1').val(),
				});
			}
		}
$(function(){
			$("#menu_353").parent().addClass('active').find('.collapse').css('height','auto');
			$('#dg').datagrid({
				pageSize:10,
				view: detailview,
				fitColumns:true,
				nowrap:false,
				singleSelect:true,

				detailFormatter:function(index,row){
					return '<div style="padding:2px">'+
							'<table rownumbers="true" pagination="true" id="ddv-' + index + '">'+	
							'</table>'+
							'</div>';
				},
				onExpandRow: function(index,row){
				$("a.group").fancybox({
					'transitionIn'	:	'elastic',
					'transitionOut'	:	'elastic',
					'speedIn'		:	600, 
					'speedOut'		:	200, 
					'overlayShow'	:	false
				});
					$('#ddv-'+index).datagrid({
						url:'<?php echo base_url();?>referensi/checklist_result/ListVisitDetail?pm_id='+row.pm_id,
						fitColumns:true,
						rownumbers:true,
						height:350,
						loadMsg:'',
						singleSelect:true,
						columns:[[
							{field:'interface',title:'Site ID',width:100},
							{field:'descr',title:'Description',width:100},
							{field:'pm_result',title:'Result',width:100},
							{field:'photo_before',title:'Photo Before',width:100},
							{field:'photo_after',title:'Photo After',width:100}
						]],
						onResize:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},
						onLoadSuccess:function(){
							setTimeout(function(){
								$('#dg').datagrid('fixDetailRowHeight',index);
							},0);
						}
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				}
			});

			$('#dg2').datagrid({
				pageSize:10,
				view: detailview,
				fitColumns:true,
				nowrap:false,
				singleSelect:true,

				detailFormatter:function(index,row){
					return '<div style="padding:2px">'+
							'<table rownumbers="true" pagination="true" id="ddv-' + index + '">'+	
							'</table>'+
							'</div>';
				},
				onExpandRow: function(index,row){
				$("a.group").fancybox({
					'transitionIn'	:	'elastic',
					'transitionOut'	:	'elastic',
					'speedIn'		:	600, 
					'speedOut'		:	200, 
					'overlayShow'	:	false
				});
					$('#ddv-'+index).datagrid({
						url:'<?php echo base_url();?>referensi/checklist_result/ListVisitDetail?pm_id='+row.pm_id,
						fitColumns:true,
						pageSize:20,
						height:350,
						nowrap:false,
						singleSelect:true,
						columns:[[
							{field:'interface',title:'Site ID',width:100},
							{field:'descr',title:'Description',width:100},
							{field:'pm_result',title:'Result',width:100},
							{field:'photo_before',title:'Photo Before',width:100},
							{field:'photo_after',title:'Photo After',width:100}
						]],
						onResize:function(){
							$('#dg2').datagrid('fixDetailRowHeight',index);
						},
						onLoadSuccess:function(){
							setTimeout(function(){
								$('#dg2').datagrid('fixDetailRowHeight',index);
							},0);
						}
					});
					$('#dg2').datagrid('fixDetailRowHeight',index);
				}
			});
				});	
				
				function doLoadTable(tableid){
					if (tableid==1)
							$('#dg').datagrid('load');
					else
							$('#dg2').datagrid('load');
				}
				$(function() {
					$(".image").click(function() {
						var image = $(this).attr("rel");
							$('#image').hide();
							$('#image').fadeIn('slow');
							$('#image').html('<img src="' + image + '"/>');
						return false;
					});
				});
</script>

<div class="heading-buttons">
	<h3><?php echo $judul?></h3>
	
</div>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget widget-tabs">
		<div class="widget-head">
			<ul>
				<li class="active"><a class="glyphicons list" href="#tab-1" data-toggle="tab" onclick="doLoadTable(1)"><i></i>Visited Summary</a></li>
				<li><a class="glyphicons share_alt" href="#tab-2" data-toggle="tab" onclick="doLoadTable(2)"><i></i>Unvisited Summary</a></li>
			</ul>
		</div>
		<div class="widget-body">
		<div class="tab-content">
			<div class="tab-pane active" id="tab-1">
			<table  class="easyui-datagrid" id="dg"
							url="<?php echo base_url().'referensi/checklist_result/listVisit/3';?>" 
							title="" nowrap='false'
							iconCls="icon-save" toolbar="#tb"
				        	rownumbers="true" pagination="true"
							singleSelect="true" fitColumns="true">
			
                        <thead>
						<tr>
								<?php
									$arr=array('site_code'=>'Site ID','site_name'=>'Site Name','user_name'=>'User Name','visit_type'=>'Visit Type','visit_date'=>'Visit Date');
									$width=array('150','70','100','100','100');
									$i=0;
									foreach ($arr as $key=>$value) {
										if ($key=='mstatus')
											echo '<th class ='.$key.' field="'.$key.'" width="'.$width[$i].'">'.$value.'</th>';
										else
											echo '<th field="'.$key.'" width="'.$width[$i].'">'.$value.'</th>';
										$i++;
									}
								?>

							</tr>
                        </thead>
                        </table>
                       
					<div id="tb" class="form-horizontal">
							<div class="control-group">
								<label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
									<div class="input-append">
									  	<input name="q" id="q1" type="text" placeholder="Type text to search ..">
									  	<button  onclick="doSearch(1)" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
									</div>	
							</div>	
					</div>
			</div>
			<div class="tab-pane" id="tab-2">
				
                        <table id="dg2"
							url="<?php echo base_url().'referensi/checklist_result/listVisit/0';?>" 
							title="" nowrap='false'
							iconCls="icon-save" toolbar="#tb2"
				        	rownumbers="true" pagination="true"
							singleSelect="true" fitColumns="true">
			
                        <thead>
						<tr>
								<?php
									$arr=array('site_code'=>'Site ID','site_name'=>'Site Name','user_name'=>'User Name','visit_type'=>'Visit Type','visit_date'=>'Visit Date');
									$width=array('150','70','100','100','100');
									$i=0;
									foreach ($arr as $key=>$value) {
										if ($key=='mstatus')
											echo '<th class ='.$key.' field="'.$key.'" width="'.$width[$i].'">'.$value.'</th>';
										else
											echo '<th field="'.$key.'" width="'.$width[$i].'">'.$value.'</th>';
										$i++;
									}
								?>

							</tr>
                        </thead>
                        </table>
					<div id="tb2" class="form-horizontal">
							<div class="control-group">
								<label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
									<div class="input-append">
									  	<input name="q" id="q2" type="text" placeholder="Type text to search ..">
									  	<button  onclick="doSearch(2)" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
									</div>	
							</div>	
					</div>
			</div>
		</div>
			
		</div>
	</div>
</div>