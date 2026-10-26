
	<!-- DataTables -->
	<link rel="stylesheet" media="screen" href="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/css/DT_bootstrap.css" />
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/DataTables/media/js/DT_bootstrap.js"></script>
<script>
// masukkan kode javascript berikutnya disini
$(document).ready(function() {
		$("#menu_334").parent().addClass('active').find('.collapse').css('height','auto');
});
var Document = {
        param: {
                dataperpage: 10, // jumlah data per halaman
                query: '',
                curpage: 0,
                numpage: 0,
                id: 0
        },
        url: '<?php echo base_url()?>referensi/equipment_antenna/loadData',
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
                var checkstr =  confirm('Are you sure want to delete this data?');
                if(checkstr == true){
                        this.param.id = id;
						//this.param.order_no = order_no;
                        
                        $.ajax({
                                url: '<?php echo base_url()?>referensi/equipment_antenna/deleteData',
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
                $.post("<?php echo base_url()?>referensi/equipment_antenna/addeditdata",{id:id},
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
												
							justNumberFormat.init();
						}, "html");
        
                return false;
        },
		
		viewData: function( id ){
                $.post("<?php echo base_url()?>referensi/equipment_antenna/viewdata",{id:id},
                        function(dt){
                            bootbox.dialog( dt ,
                                                [{
                                                        "label" : "Close",
                                                        "class" : "btn-primary"
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
				"sAjaxSource": "<?php echo base_url()?>referensi/equipment_antenna/loadData",
				"aoColumns": [
					{ "mData": "no" },
					{ "mData": "brand" },
					{ "mData": "model" },
					{ "mData": "band" },
					{ "mData": "hbwd" },
					{ "mData": "vbwd" },
					{ "mData": "etilt" },
					{ "mData": "polarization" },
					{ "mData": "vswr" },
					{ "mData": "impedance" },
					{ "mData": "gain" },
					//{ "mData": "max_input_power" },
					// { "mData": "isolations" },
					// { "mData": "weight" },
					// { "mData": "dimension_long" },
					// { "mData": "dimension_height" },
					// { "mData": "dimension_width" },
					{ "bSearchable": false,
						"bSortable": false,
						"aTargets": [ 17 ],
						"mData": "antenna_id",
						"mRender": function ( data, type, full ) {
											return '\
												<a href="#" class="btn-action glyphicons eye_open btn-success" title="View" onclick="return Document.viewData('+ data +')"><i></i></a>\
												<a href="#" class="btn-action glyphicons pencil btn-success" title="Edit" onclick="return Document.editData('+ data +')"><i></i></a>\
												<a href="#" class="btn-action glyphicons remove_2 btn-danger" title="Delete" onclick="return Document.deleteData('+ data +')"><i></i></a>';
										  }}
				]
			} );
        }
}
           
$(document).ready(function() {
        Document.search();
        $( "#create-user" )
                .button()
                .click(function() {
                        
                        $.post("<?php echo base_url()?>referensi/equipment_antenna/addeditdata",{},
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
                        url: "<?php echo base_url()?>referensi/equipment_antenna/saveData",
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


</script>

<div class="heading-buttons">
	<h3><?php echo $judul?></h3>
	<div class="buttons pull-right">
	<a href="<?php echo base_url()?>site_info/site_info_ref" ><button class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back to Site Info Ref</button></a>
		<button class="btn btn-primary btn-icon glyphicons circle_plus" id="create-user"><i></i> Add Equipment Antenna</button>
	</div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i> List</h4>
		</div>
		<div class="widget-body">
			
                        <table  id="table" class="table table-bordered table-primary table-vertical-centers">
                        <thead>
                                <tr>
                                        <th style="width: 1%;">No.</th>
                                        <th>Brand</th>
										<th style="width: 20%;">Model</th>
										<th>Band</th>
										<th style="width: 7%;">HBWD</th>
										<th style="width: 7%;">VBWD</th>
										<th style="width: 7%;">ETILT</th>
										<th style="width: 5%;">Polarization</th>
										<th style="width: 5%;">VSWR</th>
										<th style="width: 5%;">Impedance</th>
										<th>Gain</th>
										<!--<th>Max Input Power</th>
										<th>Isolations</th>
										<th>Weight</th>
										<th>Dimension Long</th>
										<th>Dimension Height</th>
										<th>Dimension Width</th>-->
                                        <th class="center" style="width: 100px;">Actions</th>
                                </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                        </table>
			
		</div>
	</div>
</div>

<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script>
var justNumberFormat = {
	fields: document.getElementsByClassName('just-number'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			this.fields[i].value = number_format(this.fields[i].value, 0, '', '');
			
			this.fields[i].onblur = function(e) {				
				if (!justNumberFormat.isNumber(this.value)) {					
					alert('Please enter only numbers.');
					setTimeout(function() { e.target.select() });
					return false;
				} else {
					this.value = number_format(this.value, 0, '', '');
				}
			};
			
			this.fields[i].onkeyup = function(e) {
				var code = e.keyCode;
				if (!justNumberFormat.isNumber(this.value)) {
					if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
						this.blur();
					}
				}
			};
			
			this.fields[i].onkeypress = function(e) {
				var code = e.keyCode;
				if (!justNumberFormat.isNumber(this.value)) {
					if (code == 13) {
						setTimeout(function() { e.target.select() });
						return false;
					}
				}
			}
		}
	},
	removeFormat: function() {},
	isNumber: function(sText) {
		var validChars = "0123456789";
		var isNumber = true;
		var character;
	 
		for (var i = 0; i < sText.length && isNumber == true; i++) {
			character = sText.charAt(i);
			if (validChars.indexOf(character) == -1) {
				isNumber = false;
			}
		}
		
		return isNumber;
	}
};
</script>