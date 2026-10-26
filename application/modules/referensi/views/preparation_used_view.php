<!--
<style type="text/css">
       
        #report div.arrow { background:transparent url(<?php echo base_url()?>assets/images/arrows.png) no-repeat scroll 0px -16px; width:16px; height:16px; display:block;}
        #report div.up { background-position:0px 0px;}
</style>
  
<script>
// masukkan kode javascript berikutnya disini
var Document = {
        param: {
                dataperpage: 10, // jumlah data per halaman
                query: '',
                curpage: 0,
                numpage: 0,
                id: 0
        },
        url: '<?php echo base_url()?>referensi/preparation_used/loadData',
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
        deleteData: function(c,id) {
                var checkstr =  confirm('Data yang telah dihapus tidak dapat dikembalikan lagi. \n Anda yakin ingin menghapus data ini ?');
                if(checkstr == true){
                        this.param.id = id;
                        this.param.curpage = c;
                        
                        $.ajax({
                                url: '<?php echo base_url()?>referensi/preparation_used/deleteData',
                                type: 'POST',
                                dataType: 'json',
                                data: jQuery.param(Document.param),
                                success: function() {
                                        Document.loadData();
                                }
                        });
                                
                        
                }
                return false;
        },
        editData: function( id ){
                $.post("<?php echo base_url()?>referensi/preparation_used/addeditdata",{id:id},
                        function(dt){
                            bootbox.dialog( dt ,
                                                [{
                                                        "label" : "Close",
                                                        "class" : "btn-primary"
                                                },{
                                                        "label" : "Save",
                                                        "class" : "btn-primary",
                                                        "callback": function( e ) {
                                                                
                                                                //simpan data ke db
                                                                $('#add-form').submit();
                                                                return false;
                                                        }
                                                }]);
                        }, "html");
        
                return false;
        },
        loadData: function() {	
					
                $.ajax({
                        url: Document.url,
                        type: 'POST',
                        dataType: 'json',
                        data: jQuery.param(Document.param),
                        success: function(d) {
                                $('#pagination').html(d.pagination);
                                Document.param.numpage = d.numpage;
                                var t = '<tr><td></td></tr>', dt = {};
                                for (var i = 0; i < d.data.length; i++) {
                                        dt = d.data[i];
                                        var bgcolor = "";
                                        if( (i+1) % 2 == 0) bgcolor = "#eeeeee";
										
												
                                        t += '<tr>' +
                                                '<td class="grid_id">'+ ((i+1) ) +'</td>' +
                                                '<td class="grid_alias">PM for User <strong>: '+ dt.user_name +' </strong> On '+dt.dates+
                                                '<td class="grid_alias">'+ dt.descr +'</td>' +
                                                '<td class="grid_alias">'+ dt.jumlah +'</td>' +
                                             '</tr>';
										/*t += '<tr><td></td><td colspan="5">' +
												'<table class="table table-bordered table-primary table-vertical-center">'+
												'<tbody  id="expand'+dt.pm_id+'" ></tbody></table>'+
                                             '</td></tr>';
												$.ajax({
													url: '<?php echo base_url()?>referensi/preparation_used/expand',
													type: 'POST',
													data: {pmid:dt.pm_id},
													dataType: 'json',
													error: function(jqXHR, textStatus, errorThrown){
															alert(errorThrown);
													},
													success: function(x) {
													 var td = '', dts = {};
													for (var z = 0; z < x.data.length; z++) {
															dts = x.data[z];
															td += '<tr><td  class="grid_alias">'+ ((z+1) ) +'</td><td  class="grid_alias">'+dts.descr+'</td><td  class="grid_alias"> Quantity :'+dts.jumlah+'</td></tr>';
														 }
														$('#expand'+dts.pm_id).append(td);
													 }
												}); */
										 
                                }
                                $('#document-data').html(t); // id dari tbody tabel data
								//console.log($("#report tbody tr:odd"));
								/*$("#report tbody tr:odd").addClass("odd");
								$("#report tbody tr:not(.odd)").hide();
								$("#report tbody tr.odd").click(function(){
									$(this).next("tr").toggle();
									$(this).find(".arrow").toggleClass("up");
									
								}); */
								
                        }
                });
        }
}
           
$(document).ready(function() {
        Document.search();
        $( "#create-user" )
                .button()
                .click(function() {
                        
                        $.post("<?php echo base_url()?>referensi/preparation_used/addeditdata",{},
                            function(dt){
                                bootbox.dialog( dt ,
                                               [
                                                {
                                                        "label" : "Close",
                                                        "class" : "btn-primary"
                                                },
                                                {
                                                        "label" : "Save",
                                                        "class" : "btn-primary",
                                                        "callback": function( e ) {
                                                                //simpan data ke db
                                                                $('#add-form').submit();
                                                                return false;
                                                        }
                                                }
                                            ]);
                         }, "html");
        });
                
        $('#add-form').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/preparation_used/saveData",
                        data: $(e.target).serialize(),
                        dataType: "json",
                        beforeSend:function(){
                                //$('.alert-error,.alert-success').hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                                alert(errorThrown);
                        },
                        success: function(data){
                                if(data.status == "OK"){
                                        $('#add-form').trigger('reset');
                                        Document.loadData();
                                        bootbox.hideAll();
                                        alert( "Data berhasil disimpan" );
                                        /*
                                        $.gritter.add({
                                                                // (string | mandatory) the heading of the notification
                                                                title: 'Confirmation',
                                                                // (string | mandatory) the text inside the notification
                                                                text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                                                                // (string | optional) the image to display on the left
                                                                image: 'http://a0.twimg.com/profile_images/59268975/jquery_avatar_bigger.png',
                                                                // (bool | optional) if you want it to fade out on its own or just sit there
                                                                sticky: false,
                                                                // (int | optional) the time you want it to be alive for before fading out
                                                                time: ''
                                                        });
                                        */
                                }else{
                                        alert( data.hasil );
                                }
                        }
                });
                return false;
	});
				
});
</script>-->
 <style>
.modal {
left:40%;
width:800px;
}
</style>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/icon.css">
	<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/datagrid-detailview.js"></script>
	
	<script src="<?php echo base_url()?>assets/js/overlay.js"></script>
	<style type="text/css">
	.datagrid-body .datagrid-cell{
   		word-wrap: break-word;
   		white-space: normal;
	}
	</style>
<style type="text/css">
       
        #report div.arrow { background:transparent url(<?php echo base_url()?>assets/images/arrows.png) no-repeat scroll 0px -16px; width:16px; height:16px; display:block;}
        #report div.up { background-position:0px 0px;}
</style>
  
<script>
$(document).ready(function(){
		$("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
		Document.search();
	});
$(function(){
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
				
					$('#ddv-'+index).datagrid({
						url:'<?php echo base_url();?>referensi/preparation_used/ListVisitDetail?user='+row.created_by+'&date='+row.created_on,
						fitColumns:true,
						rownumbers:true,
						height:'auto',
						loadMsg:'',
						singleSelect:true,
						columns:[[
							//{field:'user_name',title:'PIC Name',width:100},
							//{field:'created_by',title:'PIC ID',width:100},
							{field:'descr',title:'Preparation Item',width:100},
							//{field:'created_on',title:'Preparation On',width:100},
							{field:'jumlah',title:'Quantity',width:100}
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
        url: '<?php echo base_url()?>referensi/preparation_used/listVisit',
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


<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i><?php echo $judul?></h4>
		</div>
		<div class="widget-body">
			      <div class="tab-content">
					<div class="tab-pane active" id="tab-1">
					  <div id="toolBar1" style="padding: 10px 10px 0 10px; height:auto">
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
						<button class="btn" onclick="doSearch()()" style="margin-bottom: 10px">Apply</button>
						
						<!--<div class="input-append pull-right">
						  <input class="" id="q" type="text" placeholder="PIC" onkeyup="if(event.keyCode == 13){ doSearch();}"/> 
						  <button class="btn" onclick="doSearch()" >Search</button>
						</div>-->
					</div>
			<table  class="easyui-datagrid" id="dg"
							url="<?php echo base_url().'referensi/preparation_used/listVisit';?>" 
							title="" nowrap='false'
							iconCls="icon-save" 
				        	rownumbers="true" pagination="true"
							singleSelect="true" fitColumns="true">
			
                        <thead>
						<tr>
								<?php
									$arr=array('user_name'=>'PIC','created_on'=>'Preparation Date');
									$width=array('150','100');
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
				<hr>
			<!--
                        <table id="report" class="table table-bordered table-primary table-vertical-center">
                        <thead>
                                <tr>
                                        <th style="width: 1%;">No.</th>
										<th>PIC</th>
                                        <th>Item</th>
										<th>Quantity</th>
                                </tr>
                        </thead>
                        <tbody id="document-data">
                            
                        </tbody>
                        </table>
                        <div class="separator top form-inline small">
                        
                            <div class="pagination pagination-small pull-right" id="pagination" style="margin: 0;">
                                    
                            </div>
                            <div class="clearfix"></div>
                        </div>-->
		</div>
	</div>
</div>