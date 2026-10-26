<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui-1.8.16/jquery-ui-1.8.16.custom.min.js"></script>

<script src="<?php echo base_url('/assets/js/easyui/jquery.easyui.min.js') ?>"></script>
<link href="<?php echo base_url('/assets/js/easyui/themes/default/easyui.css') ?>" rel="stylesheet" />



<!-- DataTables -->
	<link rel="stylesheet" media="screen" href="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/css/DT_bootstrap.css" />
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/DT_bootstrap.js"></script>

<script>
// masukkan kode javascript berikutnya disini
function doExecCombo(){
    $('#user_id').combogrid({
        delay: 500,
        panelWidth: 500,
        mode: 'remote',
        url: '<?php echo base_url()?>referensi/handset/searchUser',
        idField: 'user_id',
        textField: 'user_name',
        columns: [[ 
            {field:'user_id',title:'user_id',width:120,sortable:true},
            {field:'user_name',title:'user_name',width:400,sortable:true}
        ]],
        fitColumns: true
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
        url: '<?php echo base_url()?>referensi/handset/loadData',
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
                                url: '<?php echo base_url()?>referensi/handset/deleteData',
                                type: 'POST',
                                dataType: 'json',
                                data: jQuery.param(Document.param),
                                success: function() {
                                        // Document.loadData();
										window.location.href = "<?php echo base_url();?>referensi/handset";
                                }
                        });
                                
                        
                }
                return false;
        },
        addData: function(){
                $.post("<?php echo base_url()?>referensi/handset/addeditdata",{method:'add'},
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
        editData: function( id ){
                $.post("<?php echo base_url()?>referensi/handset/addeditdata",{id:id},
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
				"sAjaxSource": "<?php echo base_url()?>referensi/handset/loadData",
				"aoColumns": [
					{ "mData": "no" },
					{ "mData": "handset_model" },
					{ "mData": "handset_man" },
					{ "mData": "user_id" },
					{ "mData": "imei" },
					{ "mData": "imsi" },
					{ "mData": "msisdn" },
					{ "bSearchable": false,
						"bSortable": false,
						"aTargets": [ 7 ],
						"mData": "handset_id",
						"mRender": function ( data, type, full ) {
											return '<a href="#" class="btn-action glyphicons pencil btn-success" onclick="return Document.editData('+ data +')"><i></i></a><a href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData('+ data +')"><i></i></a>';
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
                                                '<td class="grid_alias">'+ dt.handset_model +'</td>' +
												'<td class="grid_alias">'+ dt.handset_man +'</td>' +
												'<td class="grid_alias">'+ dt.user_id +'</td>' +
												'<td class="grid_alias">'+ dt.imei +'</td>' +
												'<td class="grid_alias">'+ dt.imsi +'</td>' +
												'<td class="grid_alias">'+ dt.msisdn +'</td>' +
                                                '<td class="grid_aksi">' +
                                                        '<a href="#" class="btn-action glyphicons pencil btn-success" onclick="return Document.editData('+ dt.handset_id +')"><i></i></a>' +
                                                        '<a href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData('+ d.curpage +','+ dt.handset_id +')"><i></i></a>' +
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
                        
                        $.post("<?php echo base_url()?>referensi/handset/addeditdata",{},
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
                        doExecCombo();

                         }, "html");

        });
                
        $('#add-form').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/handset/saveData",
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
										window.location.href = "<?php echo base_url();?>referensi/handset";
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

<!--<div class="heading-buttons">
	<h3><?php echo $judul?></h3>
	<div class="buttons pull-right">
		<a href="#" onclick="return Document.addData();"><button class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add Handset</button></a>
	</div>
</div>-->
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading glyphicons list"><i></i><?php echo $judul?></h4>
			<div class="buttons pull-right">
				<a href="#"><button class="btn btn-primary btn-mini pull-right btn-heading btn-icon glyphicons circle_plus" id="create-user"><i></i> Add Handset</button></a>
			</div>	
		</div>
		<div class="widget-body">
			
			
                        <table id="table" class="table table-bordered table-primary table-vertical-center ">
                        <thead>
                                <tr>
                                        <th style="width: 1%;">No.</th>
                                        <th>Handset Model</th>
										<th>Handset Man</th>
										<th>User ID</th>
										<th>IMEI</th>
										<th>IMSI</th>
										<th>MSISDN</th>
                                        <th class="center" style="width: 60px;">Actions</th>
                                </tr>
                        </thead>
                        
                        </table>
                        
			
		</div>
	</div>
</div>