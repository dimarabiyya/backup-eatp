
<!-- DataTables -->
<link rel="stylesheet" media="screen" href="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/css/DT_bootstrap.css" />
<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

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
        url: '<?php echo base_url()?>referensi/kecamatan/loadData',
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
                        //this.param.curpage = c;
                        
                        $.ajax({
                                url: '<?php echo base_url()?>referensi/kecamatan/deleteData',
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
                $.post("<?php echo base_url()?>referensi/kecamatan/addeditdata",{id:id},
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
												
							document.getElementById('province').onchange = function() {
								$.ajax({
									url: '<?php echo site_url('/site_info/add') ?>',
									type: 'GET',
									data: { cmd: 'GET_KABUPATEN', propinsi_id: this.value },
									dataType: 'json',
									success: function(json) {
										if (typeof json.loggedin != 'undefined' && !json.loggedin) {
											alert(json.message);
											window.location.href = json.redirect_url;
										}
										
										var html = '<option value="0">- Choose one -</option>';
										for (var i = 0; i < json.province.length; i++) {
											html += '<option value="' + json.province[i]['id'] + '">' + json.province[i]['name'] + '</option>';
										}
										document.getElementById('kabupaten').innerHTML = html;
										document.getElementById('kecamatan').innerHTML = '';
									}
								});	
							};
												
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
				"sAjaxSource": "<?php echo base_url()?>referensi/kecamatan/loadData",
				"aoColumns": [
					{ "mData": "no" },
					{ "mData": "kecamatan" },
					{ "mData": "kabupaten" },
					{ "bSearchable": false,
						"bSortable": false,
						"aTargets": [ 3 ],
						"mData": "lokasi_id",
						"mRender": function ( data, type, full ) {
							return '<a href="#" class="btn-action glyphicons pencil btn-success" onclick="return Document.editData('+ data +')"><i></i></a><a href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData('+ data +')"><i></i></a>';
						}
					}
				]
			} );
        }
}
           
$(document).ready(function() {
        Document.search();
        $( "#create-user" )
                .button()
                .click(function() {
                        
                        $.post("<?php echo base_url()?>referensi/kecamatan/addeditdata",{},
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
											
						document.getElementById('province').onchange = function() {	
							$.ajax({
								url: '<?php echo site_url('/site_info/add') ?>',
								type: 'GET',
								data: { cmd: 'GET_KABUPATEN', propinsi_id: this.value },
								dataType: 'json',
								success: function(json) {
									if (typeof json.loggedin != 'undefined' && !json.loggedin) {
										alert(json.message);
										window.location.href = json.redirect_url;
									}
									
									var html = '<option value="0">- Choose one -</option>';
									for (var i = 0; i < json.province.length; i++) {
										html += '<option value="' + json.province[i]['id'] + '">' + json.province[i]['name'] + '</option>';
									}
									document.getElementById('kabupaten').innerHTML = html;
									document.getElementById('kecamatan').innerHTML = '';
								}
							});	
						};
											
                         }, "html");
        });
                
        $('#add-form').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/kecamatan/saveData",
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
                                        //alert("Data berhasil disimpan");
										coolNotification(data.hasil, 'success');
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
                                    //alert(data.hasil);
									var message = '<ul style="text-align:left">'
									for (var i in data.hasil) {
										message += '<li>' + data.hasil[i] + '</li>';
									}
									message += '</ul>';
									
									coolNotification(message, 'error');
                                }
                        }
                });
                return false;
	});
});
</script>

<!--div class="heading-buttons">
	<h3><?php echo $judul?></h3>
	<div class="buttons pull-right">
		<button class="btn btn-primary btn-icon glyphicons circle_plus" id="create-user"><i></i> Add City</button>
	</div>
</div>-->
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading glyphicons list"><i></i><?php echo $judul?></h4>
				<div class="buttons pull-right">
					<button class="btn btn-primary pull-right btn-heading btn-icon glyphicons circle_plus" id="create-user"><i></i> Add Kecamatan</button>
				</div>
		</div>
		<div class="widget-body">
			
			
                        <table id="table" class="table table-bordered table-primary table-vertical-center ">
                        <thead>
                                <tr>
                                        <th style="width: 1%;">No.</th>
										<th>Kecamatan</th>
                                        <th>Kabupaten</th>
                                        <th class="center" style="width: 60px;">Actions</th>
                                </tr>
                        </thead>
                       
                        </table>
                        
			
		</div>
	</div>
</div>

<script>
/* document.getElementById('province').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info/add') ?>',
		type: 'GET',
		data: { cmd: 'GET_KABUPATEN', propinsi_id: this.value },
		dataType: 'json',
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.province.length; i++) {
				html += '<option value="' + json.province[i]['id'] + '">' + json.province[i]['name'] + '</option>';
			}
			document.getElementById('kabupaten').innerHTML = html;
			document.getElementById('kecamatan').innerHTML = '<option value="0">- Choose one -</option>';
		}
	});	
};

document.getElementById('kabupaten').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info/add') ?>',
		type: 'GET',
		data: { cmd: 'GET_KECAMATAN', kabupaten_id: this.value },
		dataType: 'json',
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.length; i++) {
				html += '<option value="' + json[i]['id'] + '">' + json[i]['name'] + '</option>';
			}
			document.getElementById('kecamatan').innerHTML = html;
		}
	});
}; */
</script>