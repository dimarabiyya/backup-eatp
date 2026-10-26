
    <!-- //DataTables -->
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
    	.modal {
    		left:50%;
    		width:800px;
    		}
    	</style>

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
            url: '<?php echo base_url()?>referensi/company/loadData',
            search: function() {
                    this.param.query = $('#query').val();
                    this.param.curpage = 0;
                    //this.loadData();
                    return false;
            },
            setPage: function(n) {
                    this.param.curpage = n;
                    //this.loadData();
                    return false;
            },
            prevPage: function() {
                    if (this.param.curpage > 0) {
                            this.param.curpage--;
                            //this.loadData();
                    }
                    return false;
            },
            nextPage: function() {
                    if (this.param.curpage < this.param.numpage) {
                            this.param.curpage++;
                            //this.loadData();
                    }
                    return false;
            },
            deleteData: function(id) {
                    var checkstr =  confirm('Are you sure want to delete this data ?');
                    if(checkstr == true){
                            this.param.id = id;
                            
                            $.ajax({
                                    url: '<?php echo base_url()?>referensi/company/deleteData',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: jQuery.param(Document.param),
                                    success: function() {
                                            //Document.loadData();
    										$('#dg').datagrid('load',{});
                                    }
                            });
                                    
                            
                    }
                    return false;
            },
            editData: function( id ){
                    $.post("<?php echo base_url()?>referensi/company/addeditdata",{id:id},
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
    																$('#dg').datagrid('load',{});
                                                                    return false;
                                                            }
                                                    }]);
                            
								justNumberFormat.init();
							}, "html");
            
                    return false;
            },
    		/*loadData: function() {
    			var oTable = $('#table').dataTable( {
    				"oLanguage": {
    							"sProcessing": "<img src='<?php echo base_url()?>assets/images/loading2.gif'>"
    						},			
    				"bDestroy": true ,
    				"bProcessing": true,
    				"sAjaxSource": "<?php echo base_url()?>referensi/company/loadData",
    				"aoColumns": [
    					{ "mData": "no" },
    					{ "mData": "company_name" },
    					{ "mData": "tpgroup_id" },
						{ "mData": "contact_name" },
						{ "mData": "contact_phone" },
						{ "mData": "commercial_rank" },
						{ "mData": "technical_rank" },
						{ "mData": "support_rank" },
						{ "mData": "created_by" },
    					{ "bSearchable": false,
    						"bSortable": false,
    						"aTargets": [ 9 ],
    						"mData": "company_id",
    						"mRender": function ( data, type, full ) {
    											return '<a href="#" class="btn-action glyphicons pencil btn-success" title="Edit" onclick="return Document.editData('+ data +')"><i></i></a><a href="#" class="btn-action glyphicons remove_2 btn-danger" title="Delete" onclick="return Document.deleteData('+ data +')"><i></i></a>';
    										  }}
    				]
    			} );
            }*/
    }
               
    $(document).ready(function() {
    		$("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
            Document.search();
            $( "#create-user" )
                    .button()
                    .click(function() {
                            
                            $.post("<?php echo base_url()?>referensi/company/addeditdata",{},
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
									
                            
							
								justNumberFormat.init();
							}, "html");
            });
                    
            $('#add-form').live("submit",function(e){
                    $.ajax({
                            type: "POST",
                            url: "<?php echo base_url()?>referensi/company/saveData",
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
                                            //Document.loadData();
    										$('#dg').datagrid('load',{});
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
    </script>

     <style>
    .modal {
    left:40%;
    width:800px;
    }
    </style>
      

    <div class="separator bottom"></div>

    <div class="innerLR">
    	<div class="widget">
    		<div class="widget-head">
    			<h4 class="heading glyphicons list"><i></i><?php echo $judul?></h4>
    			<div class="buttons pull-right">
    			  <button class="btn btn-small btn-primary" id="create-user"><i class="icon-plus icon-white"></i> Add TP Company</button>
    			</div>
    			
    	  <div>
    	  </div>
    		</div>
    		<div class="widget-body">
    		
    			<div id="tb" class="form-horizontal">
    							<div class="control-group">
    								<label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
    									<div class="input-append">
    									  	<input name="q" id="q" type="text" placeholder="Type text to search .." >
    									  	<button  onclick="doSearch()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
    									</div>	
    							</div>	
    			</div>
    			<br>
    		
    			<table  class="easyui-datagrid" id="dg"
    							url="<?php echo base_url().'referensi/company/listVisit';?>" 
    							title="" nowrap='false'
    							iconCls="icon-save" 
    				        	rownumbers="true" pagination="true"
    							singleSelect="true" fitColumns="true">
    			
                            <thead>
    						<tr>
    								<?php
    									$arr=array('company_name'=>'Company Name','tpgroup_name'=>'TP Group Name','contact_name'=>'Contact Name','contact_phone'=>'Contact Phone','commercial_rank'=>'Commercial Rank','technical_rank'=>'Technical Rank','support_rank'=>'Support Rank','created_by'=>'Created BY','action'=>'Action');
    									$width=array('120','120','100','90','70','60','60','50','50');
    									$i=0;
    									foreach ($arr as $key=>$value) {
    										if ($key=='mcompany')
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
    		</div>
    	</div>
    </div>
	
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>