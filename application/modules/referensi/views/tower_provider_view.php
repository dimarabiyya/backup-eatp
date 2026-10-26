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
        url: '<?php echo base_url()?>referensi/tower_provider/loadData',
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
                var checkstr =  confirm('Are you sure want to delete this data ?');
                if(checkstr == true){
                        this.param.id = id;
                        this.param.curpage = c;
                        
                        $.ajax({
                                url: '<?php echo base_url()?>referensi/tower_provider/deleteData',
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
                $.post("<?php echo base_url()?>referensi/tower_provider/addeditdata",{id:id},
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
                                var t = '', dt = {};
                                for (var i = 0; i < d.data.length; i++) {
                                        dt = d.data[i];
                                        var bgcolor = "";
                                        if( (i+1) % 2 == 0) bgcolor = "#eeeeee";
                                        t += '<tr class="selectable">' +
                                                '<td class="grid_id">'+ ((i+1) ) +'</td>' +
                                                '<td class="grid_alias">'+ dt.tp_company_name +'</td>' +
                                                '<td class="grid_alias">'+ dt.tp_code +'</td>' +
                                                '<td class="grid_alias">'+ dt.tp_site_name +'</td>' +
                                                '<td class="grid_alias">'+ dt.province +'</td>' +
                                                '<td class="grid_alias">'+ dt.region +'</td>' +
                                                '<td class="grid_alias">'+ dt.city +'</td>' +
                                                '<td class="grid_alias">'+ dt.status +'</td>' +
                                                '<td class="grid_alias">'+ dt.latitude +'</td>' +
                                                '<td class="grid_alias">'+ dt.longitude +'</td>' +
                                                '<td class="grid_alias">'+ dt.site_type +'</td>' +
                                                '<td class="grid_alias">'+ dt.tower_type +'</td>' +
                                                '<td class="grid_alias">'+ dt.building_height_mtr +'</td>' +
                                                '<td class="grid_alias">'+ dt.tower_height_mtr +'</td>' +
                                                '<td class="grid_alias">'+ dt.shareable_status +'</td>' +
                                                '<td class="grid_alias">'+ dt.site_code +'</td>' +
                                                '<td class="grid_alias">'+ dt.remark +'</td>' +
                                                '<td class="grid_alias">'+ dt.partner_id +'</td>' +
                                                '<td class="grid_alias">'+ dt.candidate_status +'</td>' +
                                                '<td class="grid_aksi">' +
                                                        '<a href="#" class="btn-action glyphicons pencil btn-success" onclick="return Document.editData('+ dt.no +')"><i></i></a>' +
                                                        '<a href="#" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData('+ d.curpage +','+ dt.no +')"><i></i></a>' +
                                                '</td>' +
                                             '</tr>';
                                }
                                $('#document-data').html(t); // id dari tbody tabel data
                        }
                });
        }
}
           
$(document).ready(function() {
        Document.search();
        $( "#create-user" )
                .button()
                .click(function() {
                        
                        $.post("<?php echo base_url()?>referensi/tower_provider/addeditdata",{},
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
                        url: "<?php echo base_url()?>referensi/tower_provider/saveData",
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
	$('#province').live("change",function(e){
	        $.post("<?php echo base_url(); ?>index.php/referensi/tower_provider/getCityy/"+$('#province').val(),{},function(obj){
                $('#city').html(obj);
            });
		
	});
	$('#site_id').live("change",function(e){
	        $.post("<?php echo base_url(); ?>index.php/referensi/tower_provider/getSiteName/"+$('#site_id').val(),{},function(obj){
                $('#site_name').html(obj);
            });
		
	});
});
</script>

<div class="heading-buttons">
	<h3><?php echo $judul?></h3>
	<div class="buttons pull-right">
		<button class="btn btn-primary btn-icon glyphicons circle_plus" id="create-user"><i></i> Add Tower Provider</button>
	</div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i>List</h4>
		</div>
		<div class="widget-body" style='overflow:auto'>
			<div class="filter-bar">
				<form>
					<div>
						<label>Searching:</label>
						<div class="input-append">
							<input type="text" name="query" id="query" class="input-mini" style="width: 153px;" />
                                                        <button class="btn" onclick="return Document.search()"><i class="icon-search"></i>Search</button>
						</div>
					</div>
					<div class="clearfix"></div>
				</form>
			</div>
			
                        <table style='width:100%' class="table table-bordered table-primary table-vertical-center ">
                        <thead>
                                <tr>
                                        <th style="width: 1%;">No.</th>
                                        <th>Tp Name</th>
										<th>Site ID</th>
										<th>Site Name</th>
										<th>Province</th>
										<th>Region</th>
										<th>City</th>
										<th>Status</th>
										<th>Latitude</th>
										<th>Longtitude</th>
										<th>Site Type</th>
										<th>Tower Type</th>
										<th>Building Height</th>
										<th>Tower Height</th>
										<th>Shareable Status</th>
										<th>Site Code</th>
										<th>Remark</th>
										<th>Partner ID</th>
										<th>Candidate</th>
                                        <th class="center" style="width: 60px;">Actions</th>
                                </tr>
                        </thead>
                        <tbody id="document-data">
                            
                        </tbody>
                        </table>
                        <div class="separator top form-inline small">
                        
                            <div class="pagination pagination-small pull-right" id="pagination" style="margin: 0;">
                                    
                            </div>
                            <div class="clearfix"></div>
                        </div>
			
		</div>
	</div>
</div>