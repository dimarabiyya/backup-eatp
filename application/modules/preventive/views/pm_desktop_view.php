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
$(document).ready(function() {
	$("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
	Document.search();
	$('.real-image').fancybox({
		type: 'image',
		transitionIn : 'elastic',
		transitionOut: 'elastic',
		closeBtn: true
	});
});
$(function(){
			$('#dg').datagrid({
				pageSize:10,
				view: detailview,
				fitColumns:true,
				nowrap:false,
				singleSelect:true,
				height:'auto',

				detailFormatter:function(index,row){
					return '<div style="padding:2px">'+
							'<table rownumbers="true" pagination="true" id="ddv-' + index + '">'+	
							'</table>'+
							'</div>';
				},
				onExpandRow: function(index,row){
				/* $("a.group").fancybox({
					'transitionIn'	:	'elastic',
					'transitionOut'	:	'elastic',
					'speedIn'		:	600, 
					'speedOut'		:	200, 
					'overlayShow'	:	false
				}); */
					$('#ddv-'+index).datagrid({
						url:'<?php echo base_url();?>preventive/pm_desktop/getListVisitDetail?visit_id='+row.visit_id,
						fitColumns:true,
						rownumbers:true,
						height:'auto',
						loadMsg:'',
						singleSelect:true,
						columns:[[
							{field:'interface',title:'Checklist Interface',width:50},
							{field:'checklist_name',title:'Description',width:100},
							// {field:'pm_result',title:'Answer',width:100}
							{field:'pm_result',title:'Answer',width:100,sortable: false, formatter: function(value, row, index) {
								if (row.answer != undefined) return row.answer.join(', ');
							}},
							{field:'photo_name',title:'Photo(s)',width:100, class: 'real-image',sortable: false, formatter: function(value, row, index) {
								if (row.photo != undefined) return row.photo.join(', ');
							}}
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
		});	
				
				/* function doLoadTable(tableid){
					
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
				}); */
</script>
<script>
function doSearch(){
	$('#dg').datagrid('load',{
		q: $('#q').val(),
		opr_year: $('#tb1OprYear').val(),
		opr_month  : $('#tb3OprMonth').val(),
		opr_cluster: $('#tb1OprCluster').val()
	});		
}
var Document = {
        param: {
                dataperpage: 10, // jumlah data per halaman
                query: '',
                curpage: 0,
                numpage: 0,
                id: 0
        },
        url: '<?php echo base_url()?>referensi/pm_checklist/ListCkDesk',
        search: function() {
                this.param.query = $('#query').val();
                this.param.curpage = 0;
                this.loadData();
                return false;
        },
        setPage: function(n) {
                this.param.curpage = n;
                this.loadData();
                return false;
        },
        prevPage: function() {
                if (this.param.curpage > 0) {
                        this.param.curpage--;
                        this.loadData();
                }
                return false;
        },
        nextPage: function() {
                if (this.param.curpage < this.param.numpage) {
                        this.param.curpage++;
                        this.loadData();
                }
                return false;
        },
		loadData: function() {
			$('#dg').datagrid('load',{
				opr_year: $('#tb1OprYear').val(),
				opr_month  : $('#tb3OprMonth').val(),
				opr_cluster: $('#tb1OprCluster').val()
			});
        }
	}
</script>
<!--<script>
function toggleShowImage (visit_id, trx_id) {
    var ssAvailabele = $('#ss-checklist-item-'+trx_id+'-'+visit_id).length;
    if(ssAvailabele == 0) {
      $('label.li_'+trx_id+'_'+visit_id).show();
      $('button#ssAppender_'+trx_id+'_'+visit_id).hide();
      $.post(
          '<?php echo base_url()."preventive/pm_desktop/getVisitImage" ?>',
          {visit_id: visit_id, checklist_id: trx_id},
          function(data){
            $('label.li_'+trx_id+'_'+visit_id).hide();
            $('button#ssAppender_'+trx_id+'_'+visit_id).show();
            if(data) {
              var uRzPRp = data;
            }
            else {
              var uRzPRp = 'Photo not available';
            }
            var ssAppender = 
              '    <table class="table table-bordered table-condensed" id="ss-checklist-item-'+trx_id+'-'+visit_id+'">'+
              '      <thead>'+
              '        <tr>'+
              '          <th>Screen Capture Gallery</th>'+
              '        </tr>'+
              '      </thead>'+
              '      <tbody>'+
              '      <tr><td>'+uRzPRp+'</td></tr>'
              '      </tbody>'+
              '    </table>';
            /* $('#pm-checklist-item-'+trx_id).after(ssAppender); */
            $('button#ssAppender_'+trx_id+'_'+visit_id).html('<i></i>Hide');
            $('button#ssAppender_'+trx_id+'_'+visit_id).removeClass('expand');
            $('button#ssAppender_'+trx_id+'_'+visit_id).addClass('collapse_top');
          }
        );
    } else { 
      /* $('#pm-checklist-item-'+trx_id).next().remove(); */
      $('button#ssAppender_'+trx_id+'_'+visit_id).html('<i></i>Show');
      $('button#ssAppender_'+trx_id+'_'+visit_id).removeClass('collapse_top');
      $('button#ssAppender_'+trx_id+'_'+visit_id).addClass('expand');
    }
  }
</script>-->
<div class="innerLR">
<div class="heading-buttons">
	<h3><?php echo $judul?></h3>
	
</div>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget widget-tabs">
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i>PM Checklist Desktop</h4>
		</div>
		<div class="widget-body">
		<div class="tab-content">
			<div class="tab-pane active" id="tab-1">
			<table  class="easyui-datagrid" id="dg"
							url="<?php echo base_url().'preventive/pm_desktop/ListCkDesk';?>" 
							title="" nowrap='false'
							iconCls="icon-save" toolbar="#tb"
				        	rownumbers="true" pagination="true" height="auto"
							singleSelect="true" fitColumns="true">
			
                        <thead>
						<tr>
								<?php
									$arr=array('site_code'=>'Site ID','site_name'=>'Site Name','vendor_id'=>'Vendor ID','user_name'=>'PIC Name','visit_type'=>'Visit Type','visit_date'=>'Visit Plan','status'=>'Status','action'=>'Action');
									$width=array('150','150','100','100','100','100','150','30');
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
            <div class="input-prepend">
              <span class="add-on">Cluster</span>
              <select name="operational_cluster" id="tb1OprCluster" style="width: 130px;">
              <?php 
                foreach ($territory as $tt) {
                  echo '<optgroup label="'.$tt["region_name"].'">';
                  foreach ($tt['cluster'] as $cc) 
                    { echo '<option value="'.$cc['cluster_ops_id'].'">'.$cc["cluster_name"].'</option>'; }
                  echo '</optgroup>';
                }
              ?>
              </select>
            </div>
            <div class="input-prepend">
              <span class="add-on">Opr. Month</span>
              <select name="operational_month" id="tb3OprMonth" style="width: 100px;">
                <?php 
                  for ($i=1; $i <= 12; $i++) { 
                    if(date('m') ==  $i )
                      { echo '<option value="'.$i.'" selected="selected">'.date("F", mktime(0,0,0,$i,1,date('Y'))).'</option>'; }
                    else
                      { echo '<option value="'.$i.'" >'.date("F", mktime(0,0,0,$i,1,date('Y'))).'</option>'; }
                  }
                ?>
              </select>
            </div>
            <div class="input-prepend">
              <span class="add-on">Opr. Year</span>
              <select name="operational_year" id="tb1OprYear" style="width: 100px;">
                <option value="<?php echo date('Y')-1; ?>"><?php echo date('Y')-1; ?></option>
                <option value="<?php echo date('Y'); ?>" selected="selected"><?php echo date('Y'); ?></option>
                <option value="<?php echo date('Y')+1; ?>"><?php echo date('Y')+1; ?></option>
              </select> &nbsp;
            </div>
            <button class="btn" onclick="doSearch()" style="">Apply</button>

            <div class="input-append pull-right">
              <input class="" id="q" type="text" placeholder="Site ID" onkeyup="if(event.keyCode == 13){ doSearch();}"/> 
              <button class="btn" onclick="doSearch()" >Search</button>
			</div>
			</div>
		</div>
		</div>
	</div>
</div>
</div>