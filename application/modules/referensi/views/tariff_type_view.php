
<!-- DataTables -->
	<link rel="stylesheet" media="screen" href="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/css/DT_bootstrap.css" />
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/DT_bootstrap.js"></script>

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
        url: '<?php echo base_url()?>referensi/tariff_type/loadData',
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
        deleteData: function(id,order) {
                var checkstr =  confirm('Are you sure want to delete this data ?');
                if(checkstr == true){
                        this.param.id = id;
						this.param.order = order;
                        
                        $.ajax({
                                url: '<?php echo base_url()?>referensi/tariff_type/deleteData',
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
                $.post("<?php echo base_url()?>referensi/tariff_type/addeditdata",{id:id},
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
			var oTable = $('#table').dataTable( {
				"oLanguage": {
							"sProcessing": "<img src='<?php echo base_url()?>assets/images/loading2.gif'>"
						},			
				"bDestroy": true ,
				"bProcessing": true,
				"sAjaxSource": "<?php echo base_url()?>referensi/tariff_type/loadData",
				"aoColumns": [
					{ "mData": "no" },
					{ "mData": "lookup_desc" },
					{ "mData": "order" },
					{ "bSearchable": false,
						"bSortable": false,
						"aTargets": [ 4 ],
						"mData": "lookup_id",
						"mRender": function ( data, type, full ) {
											return '<a href="#" class="btn-action glyphicons pencil btn-success" onclick="return Document.editData('+ data +')"><i></i></a><a href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData('+ data +','+full.order+')"><i></i></a>';
										  }}
				]
			} );
        }
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
                                                '<td class="grid_alias">'+ dt.lookup_key +'</td>' +
												'<td class="grid_alias">'+ dt.lookup_desc +'</td>' +
												'<td class="grid_alias">'+ dt.order +'</td>' +
                                                '<td class="grid_aksi">' +
                                                        '<a href="#" class="btn-action glyphicons pencil btn-success" onclick="return Document.editData('+ dt.lookup_id +')"><i></i></a>' +
                                                        '<a href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData('+ d.curpage +','+ dt.lookup_id +')"><i></i></a>' +
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
                        
                        $.post("<?php echo base_url()?>referensi/tariff_type/addeditdata",{},
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
                        url: "<?php echo base_url()?>referensi/tariff_type/saveData",
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
});
</script>

<div class="heading-buttons">
	<h3><?php echo $judul?></h3>
	<div class="buttons pull-right">
	<a href="<?php echo base_url()?>site_info/site_info_ref" ><button class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back to Site Info Ref</button></a>
		<button class="btn btn-primary btn-icon glyphicons circle_plus" id="create-user"><i></i> Add Tarif Type</button>
	</div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i> List</h4>
		</div>
		<div class="widget-body">
			
			
                        <table id="table" class="table table-bordered table-primary table-vertical-center ">
                        <thead>
                                <tr>
                                        <th style="width: 1%;">No.</th>
										<th>Lookup Desc</th>
										<th>Order</th>
                                        <th class="center" style="width: 60px;">Actions</th>
                                </tr>
                        </thead>
                        
                        </table>
                       
			
		</div>
	</div>
</div>