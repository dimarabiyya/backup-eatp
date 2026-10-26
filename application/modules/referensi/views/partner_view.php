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
        url: '<?php echo base_url()?>referensi/partner/loadData',
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
                                url: '<?php echo base_url()?>referensi/partner/deleteData',
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
                $.post("<?php echo base_url()?>referensi/partner/addeditdata",{id:id},
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
			$('#dg').datagrid('load',{
			});
        }
		/* loadData: function() {
			var oTable = $('#table').dataTable( {
				"oLanguage": {
							"sProcessing": "<img src='<?php echo base_url()?>assets/images/loading2.gif'>"
						},			
				"bDestroy": true ,
				"bProcessing": true,
				"sAjaxSource": "<?php echo base_url()?>referensi/partner/loadData",
				"aoColumns": [
					{ "mData": "no" },
					{ "mData": "partner_code" },
					{ "mData": "partner_name" },
					{ "mData": "partner_type" },
					{ "mData": "gis_marker" },
					{ "bSearchable": false,
						"bSortable": false,
						"aTargets": [ 5 ],
						"mData": "partner_id",
						"mRender": function ( data, type, full ) {
											return '<a href="#" class="btn-action glyphicons pencil btn-success" onclick="return Document.editData('+ data +')"><i></i></a><a href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData('+ data +')"><i></i></a>';
										  }}
				]
			} );
        } */
        /*loadData: function() {
                $.ajax({
                        url: Document.url,
                        type: 'POST',
                        dataType: 'json',
                        data: jQuery.param(Document.param),
                        success: function(d) {
                                $('#pagination').html(d.pagination);
                                Document.param.numpage = d.numpage;
                                var t = '', dt = {};
                                for (var i = 0; i < d.data.length; i++) {
                                        dt = d.data[i];
                                        var bgcolor = "";
                                        if( (i+1) % 2 == 0) bgcolor = "#eeeeee";
                                        t += '<tr class="selectable">' +
                                                '<td class="grid_id">'+ ((i+1) ) +'</td>' +
                                                '<td class="grid_alias">'+ dt.partner_code +'</td>' +
                                                '<td class="grid_alias">'+ dt.partner_name +'</td>' +
                                                '<td class="grid_alias">'+ dt.partner_type +'</td>' +
                                                '<td class="grid_alias">'+ dt.gis_marker +'</td>' +
                                                '<td class="grid_aksi">' +
                                                        '<a href="#" class="btn-action glyphicons pencil btn-success" onclick="return Document.editData('+ dt.partner_id +')"><i></i></a>' +
                                                        '<a href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData('+ d.curpage +','+ dt.partner_id +')"><i></i></a>' +
                                                '</td>' +
                                             '</tr>';
                                }
                                $('#document-data').html(t); // id dari tbody tabel data
                        }
                });
        }*/
}
           
$(document).ready(function() {
        Document.search();
        $( "#create-user" )
                .button()
                .click(function() {
                        
                        $.post("<?php echo base_url()?>referensi/partner/addeditdata",{},
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
                        url: "<?php echo base_url()?>referensi/partner/saveData",
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
                                        alert( data.pesan );
                                }
                        }
                });
                return false;
	});
	$('#province_id').live("change",function(e){
	        $.post("<?php echo base_url(); ?>index.php/referensi/partner/getCityy/"+$('#province_id').val(),{},function(obj){
                $('#city_id').html(obj);
            });
		
	});
});
</script>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading glyphicons list"><i></i><?php echo $judul?></h4>
			<div class="buttons pull-right">
				<button class="btn btn-primary pull-right btn-heading btn-icon glyphicons circle_plus" id="create-user"><i></i> Add Partner</button>
			</div>
		</div>
		<div class="widget-body">
		<div class="tab-content">
        <div class="tab-pane active" id="tab-1">
		<div id="tb" class="form-horizontal">
            <div id="toolBar1" class="right">
				<div id="tb" class="form-horizontal">
					<div class="input-append">
						  	<input name="q" id="q" type="text" placeholder="Type to seacrh.." onkeyup="if(event.keyCode == 13){ doSearch();}">
						  	<button  onclick="doSearch()" class="btn" type="button">search</button>
					</div>
				</div>
            </div>
        </div>
			<table  class="easyui-datagrid" id="dg"
							url="<?php echo base_url().'referensi/partner/loadData';?>" 
							title="" nowrap='false'
							iconCls="icon-save" toolbar="#tb"
				        	rownumbers="true" pagination="true"
							singleSelect="true" fitColumns="true">
			    <thead>
					<tr>
						<?php
							$arr=array('partner_code'=>'Code','partner_name'=>'Name','partner_type'=>'Partner Type','gis_marker'=>'Marker','action'=>'Action');
							$width=array('150','100','100','100','70');
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
			</div>
		</div>
		</div>
	</div>
</div>