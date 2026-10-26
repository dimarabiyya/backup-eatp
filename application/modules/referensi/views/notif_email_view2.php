
<!-- DataTables -->
	<link rel="stylesheet" media="screen" href="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/css/DT_bootstrap.css" />
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/DT_bootstrap.js"></script>

<script>
// masukkan kode javascript berikutnya disini
    function popupwindow(url, title, w, h) {
        wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }

var Document = {
        param: {
                dataperpage: 10, // jumlah data per halaman
                query: '',
                curpage: 0,
                numpage: 0,
                id: 0
        },
        url: '<?php echo base_url()?>referensi/notif_email/loadData',
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
                                url: '<?php echo base_url()?>referensi/notif_email/deleteData',
                                type: 'POST',
                                dataType: 'json',
                                data: jQuery.param(Document.param),
                                success: function() {
                                        // Document.loadData();
										window.location.href = "<?php echo base_url();?>referensi/notif_email";
                                }
                        });
                }
                return false;
        },
        addData: function(){
              popupwindow("<?php echo base_url()?>referensi/notif_email/set_template","Template Header",850,440);
                return false;
        },
        editData: function( id ){
              popupwindow("<?php echo base_url()?>referensi/notif_email/addeditdata/"+id,"Template Menu",850,510);
        
                return false;
        },
		loadData: function() {
			var oTable = $('#table').dataTable( {
				"oLanguage": {
							"sProcessing": "<img src='<?php echo base_url()?>assets/images/loading2.gif'>"
						},			
				"bDestroy": true ,
				"bProcessing": true,
				"sAjaxSource": "<?php echo base_url()?>referensi/notif_email/loadData",
				"aoColumns": [
					{ "mData": "no" },
					{ "mData": "menu_id" },
					{ "mData": "template_name" },
					{ "mData": "action_desc" },
					{ "mData": "subject" },
					{ "mData": "parameter" },
					{ "bSearchable": false,
						"bSortable": false,
						"aTargets": [ 7 ],
						"mData": "id",
						"mRender": function ( data, type, full ) {
											return '<a href="#" class="btn-action glyphicons pencil btn-success" onclick="return Document.editData('+ data +')"><i></i></a>';
										  }}
				]
			} );
        }
}
           
$(document).ready(function() {
        Document.search();
   
                
        $('#add-form').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/notif_email/saveData",
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
                                        bootbox.hideAll();
                                        alert( "Data berhasil disimpan" );
										window.location.href = "<?php echo base_url();?>referensi/notif_email";
                                }else{
                                        alert( data.pesan );
                                }
                        }
                });
                return false;
	});
});
</script>

<!--<div class="heading-buttons">
	<h3><?php echo $judul?></h3>
	<div class="buttons pull-right">
		<a href="#" onclick="return Document.addData();"><button class="btn btn-primary btn-icon glyphicons message_flag"><i></i> Set Notification Template</button></a>
	</div>
</div>-->
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i><?php echo $judul?></h4>
			<div class="buttons pull-right">
				<a href="#" onclick="return Document.addData();"><button class="btn btn-primary pull-right btn-heading btn-icon glyphicons message_flag"><i></i> Set Notification Template</button></a>
			</div>
		</div>
		<div class="widget-body">
			
			
                        <table id="table" class="table table-bordered table-primary table-vertical-center ">
                        <thead>
                                <tr>
                                        <th style="width: 1%;">No.</th>
                                        <th>Menu</th>
                                        <th>Template Name</th>
										<th>Template Desc</th>
										<th>Subject</th>
										<th>Parameter</th>
										<th class="center" style="width: 60px;">Actions</th>
                                </tr>
                        </thead>
                        
                        </table>
                        
			
		</div>
	</div>
</div>