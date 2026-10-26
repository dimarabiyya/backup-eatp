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
	<!-- DataTables 
	<link rel="stylesheet" media="screen" href="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/css/DT_bootstrap.css" />
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/DT_bootstrap.js"></script>-->
<script>
    $(function(){
        $("#q").keydown(function(e) {
             var code = e.which; // recommended to use e.which, it's normalized across browsers
                if(code==13){
                    doSearch();
                    e.preventDefault();
                }

        });
    });
    function doSearch(){
		$('#dg').datagrid('load',{
		q: $('#q').val(),
		});
    }
// masukkan kode javascript berikutnya disini
var Document = {
        param: {
                dataperpage: 10, // jumlah data per halaman
                query: '',
                curpage: 0,
                numpage: 0,
                id: 0
        },
        url: '"<?php echo base_url().'referensi/preparation_item/loadDataUT';?>',
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
        deleteData: function(id) {
                var checkstr =  confirm('Are you sure want to delete this data ?');
                if(checkstr == true){
                        this.param.id = id;
                        
                        $.ajax({
                                url: '<?php echo base_url()?>referensi/preparation_item/deleteData',
                                type: 'POST',
                                dataType: 'json',
                                data: jQuery.param(Document.param),
                                success: function() {                                        
                                        $('#dg').datagrid('reload');
                                }
                        });
                                
                        
                }
                return false;
        },
        editData: function( id ){
                $.post("<?php echo base_url()?>referensi/preparation_item/addeditdata",{id:id},
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
			$('#dg').datagrid('load',{});
        }
}
           
$(document).ready(function() {
		$("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
        Document.search();
        $( "#create-user" )
                .button()
                .click(function() {
                        
                        $.post("<?php echo base_url()?>referensi/preparation_item/addeditdata",{},
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
                        url: "<?php echo base_url()?>referensi/preparation_item/saveData",
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
                                        $('#dg').datagrid('reload');
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
                                        alert( data.pesan );
                                }
                        }
                });
                return false;
	});
});
</script>

<script>

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
						url:'<?php echo base_url();?>referensi/preparation_item/loaddata?type='+row.preparation_type_id,
						fitColumns:true,
						rownumbers:true,
						height:'auto',
						loadMsg:'',
						singleSelect:true,
						columns:[[
							{field:'descr',title:'Preparation Item Name',width:100},
							{field:'action',title:'Action',width:100}
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

<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i><?php echo $judul ?></h4>
            <div class="buttons pull-right">
              <a href="<?php echo base_url()?>preventive/references" class="btn btn-primary btn-heading btn-icon glyphicons unshare"><i></i> Back to Preventive Ref</a>
              <button class="btn btn-primary btn-heading btn-icon glyphicons circle_plus" id="create-user"><i></i> Add Tools Item</button>
            </div>
		</div>
		<div class="widget-body">
			<table  class="easyui-datagrid" id="dg"
                        				url="<?php echo base_url().'referensi/preparation_item/loadDataUT';?>" 
							title="" nowrap='false'
							iconCls="icon-save" toolbar="#tb"
				        	rownumbers="true" pagination="true"
							singleSelect="true" fitColumns="true">
			
                        <thead>
						<tr>
								<?php
									$arr=array('tdescr'=>'Type Name');
									$width=array('150');
									$sorting=array('true');
									$i=0;
									foreach ($arr as $key=>$value) {
											echo '<th field="'.$key.'" sortable="'.$sorting[$i].'" width="'.$width[$i].'">'.$value.'</th>';
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
									  	<input name="q" id="q" type="text" placeholder="Type text to search .." onkeyup="if(event.keyCode == 13){ doSearch();}">
									  	<button  onclick="doSearch()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
									</div>	
							</div>	
					</div>
                        <hr>
			
		</div>
	</div>
</div>