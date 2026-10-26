<?php error_reporting(0);
$this->load->model('map_model');
?>	
	
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

<form action="#" class="form-horizontal" style="margin-bottom: 0;" method="POST" id="search_pic">
	
	<div class="widget">
		
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<input type="hidden" name="select_pic_id">
		
		<div class="widget-body">
			
			<div class="row-fluid">
				
				<div>
					
<!--					<div id="tb" style="padding:3px">
                                                <div>
                                                    Area: <select name="territory" id="territory" style="width:150px;margin-bottom: 0px;line-height: 28px;">
                                                            <?php //echo $this->map_model->getTerritoryTreeNewOpt();?>
                                                        </select>
                                                    Search: <input id="srcSite" placeholder="Type text to search...." style="height:26px;border:1px solid #ccc" />
                                                    <a href="#" class="easyui-linkbutton" plain="true"
                                                            onclick="searching()"><i class="icon-cari" style="background-position: -48px 0;"></i></a>
                                                </div>
					
					</div>-->
                                            
                                        <div id="tb" style="padding:3px">
                                             <!--<div id="tb" style="padding:3px">-->
                                                    <span id="region">
                                                        &nbsp;Region : 
                                                        <select name="territory" id="territory" style="width:170px;margin-bottom: 0px;line-height: 28px;">
                                                                <?php echo $this->map_model->getTerritoryTreeNewOpt();?>
                                                        </select>
                                                    </span>
                                                    <span id="cluster">
                                                      
                                                        &nbsp;Cluster :
                                                        <?php
                                                            echo form_dropdown("cluster_ops_id",array('- Choose Cluster -'=>'- Choose Cluster -'),'');
                                                        ?>
                                                    </span>
                                                    <span id="search">
                                                        &nbsp;Search : <input id="srcSite" placeholder="Type text to search...." style="height:26px;border:1px solid #ccc" />
                                                        <a href="#" class="easyui-linkbutton" plain="true" onclick="searching()"><i class="icon-cari" style="background-position: -48px 0;"></i></a>
                                                    </span>
					</div>    

					<table id="dg"></table>

                    <div class="clearfix"></div>

					<div class="form-actions">
						<button type="submit" class="btn btn-icon btn-primary glyphicons btn-small circle_ok"><i></i>Confirm</button>
						<button type="button" class="btn btn-icon btn-close btn-default glyphicons btn-small circle_remove"><i></i>Close</button>
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
    var ac;
    var cls = $('#cluster_ops_id').val();
    if (cls=='' || cls==0)
        ac = $('#territory').val();
    else
        ac = cls;
    $('#dg').datagrid('load',{
        q: $('#srcSite').val(),
        a: ac,
//        c: $('#cluster_ops_id').val()
    });
}

jQuery(document).ready(function() {
    
    $("#srcSite").keypress(function (e) {
        
        if ( e.which == 13 ) {
            searching();
            e.preventDefault();
        }
    });
    
    
	$('#dg').datagrid({
		pagination:true,
		pageSize:10,
		url:"<?php echo base_url().'referensi/map/getAllPIC/'.$role ?>",
		fitColumns:true,
		
		checkOnSelect:true,
		selectOnCheck:true,
		toolbar:"#tb",
		idField:"user_id",
		textField:'user_name',
		rownumbers:true,
		height:350,
		mode:'remote',
		width:800,
		columns:[[
			{field:'ck',checkbox:true,align:'center'},
			{field:'user_id',title:'PIC User ID',width:30,sortable:true},
			{field:'user_name',title:'PIC Name',width:30,sortable:true},
                        {field:'region_name',title:'Region',width:30,sortable:true},
                        {field:'cluster_name',title:'Cluster',width:30,sortable:true}
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

$("#territory").change(function(){
                var region_id = {region_id:$("#territory").val()};
                $.ajax({
                        type: "POST",
                        url : "<?php echo base_url('referensi/map/getClusterByReg')?>",
                        data: region_id,
                        success: function(msg){
                            $("#cluster").html(msg);
                        }
                    });
//               searching();
                $('#dg').datagrid('load',{
                    q: $('#srcSite').val(),
                    a: $('#territory').val()
                });
        });
        
        $(document).on('change', '#cluster_ops_id', function () {
  //          console.log('Tessss');
//            searching();
            $('#dg').datagrid('load',{
                q: $('#srcSite').val(),
                a: $('#cluster_ops_id').val()
            });
        });
        
</script>