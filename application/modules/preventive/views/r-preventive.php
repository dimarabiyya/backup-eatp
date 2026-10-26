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

		
	
		function doSearch(){
			
				$('#dg').datagrid('load',{
					q: $('#q1').val(),
				});
			
		}
$(function(){
			$("#menu_353").parent().addClass('active').find('.collapse').css('height','auto');
			

			
				});	
				
				function doLoadTable(tableid){
					
							$('#dg').datagrid('load');
					
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

<div class="innerLR">
<form name ="form3" class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
				
					<table  class="easyui-datagrid" id="dg"
							url="<?php echo base_url().'preventive/preventive/addeditdatas/'.$id;?>" 
							title="" nowrap='false'
							iconCls="icon-save" toolbar="#tb"
				        	rownumbers="true" pagination="true"
							singleSelect="true" fitColumns="true">
			
                        <thead>
						<tr>
								<?php
									$arr=array('checklist_name'=>'Checklist Name','textbox'=>'PM Result','upload'=>'Upload','checklist'=>'Checklist');
									$width=array('100','100','100','50');
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
									  	<input name="q" id="q1" type="text" placeholder="Type text to search .." onkeyup="doSearch()">
									  	<button  onclick="doSearch()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
									</div>	
							</div>	
					</div>
					
					<div class="form-actions">
						<a href="<?php echo base_url()?>preventive/preventive"><input TYPE="button" class="btn btn-icon btn-primary glyphicons circle_ok" VALUE="Back" ></a>
						<input type="submit" class="btn btn-icon btn-primary glyphicons circle_ok" value="Save" >
					</div>
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>
</div>


