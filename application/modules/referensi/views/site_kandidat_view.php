

  <?php $role = get_instance()->session->userdata('user_group'); ?>
<script>

function savedata(site_id, nomsite_id,status) {
        
        var remark = $("#remark").attr('value');
        $.post("<?php echo base_url()?>referensi/site_kandidat/chooseKandidat",
          {site_id:site_id, nomsite_id:nomsite_id, status:status, remark:remark },
          function(data) {
            if (data.status_update == true) {
              //proses loop di sini
              var tStatus = "";
              var tPilih  = "";
              var newSiteID = "";
              var newStatusVery = "";
              
              $(".table").find("td[nomsite_id='"+nomsite_id+"']").each(function() {
                //var nim = $(this).val();
                newSiteID = $(this).attr('site_id');
                
                if (status == 'choose') {
                  if (newSiteID == site_id) {
                    tStatus = "<font color=red><b><img src='<?php echo base_url("/assets/images/icon-checked.gif")?>' >selected candidate</b></font>";
                    tPilih  = '<span onClick="verify('+site_id+'\',\''+ nomsite_id+'\',\'release\')" class="btnChoose btn btn-icon btn-danger glyphicons remove" nomsite_id="'+ nomsite_id +'" site_id="'+ newSiteID +'" status="release" ><i></i>Release</span>';
                    
                    $(this).attr( 'svery', 3);
                  }else{
                    tStatus = "<b>verified</b>";
                    tPilih  = '';
                  }
                  
                }else{
                  tStatus = "<b>verified</b>";
                  tPilih  = '<span onClick="verify(\''+site_id+'\',\''+nomsite_id+'\',\'choose\')" class="btnChoose btn btn-icon btn-success glyphicons ok" nomsite_id="'+ nomsite_id +'" site_id="'+ site_id +'" status="choose" ><i></i>Choose</span> &nbsp; ';
                  if($(this).attr('svery') >0 ) $(this).attr( 'svery', 2);
                }
                
                if ( $(this).attr('svery') == 2 || $(this).attr('svery') == 3 ) {
                  $("#status"+nomsite_id+newSiteID).html( tStatus );
                  $("#pilih"+nomsite_id+newSiteID).html( tPilih );
                }
                
              });
              
              $.fancybox.close();
              
              n=notyfy({
                layout: 'center',
                text:  'Saved', 
                type: 'success',
                modal: true
                });
              setTimeout(function(){ n.close(); }, 3000);
            }
          },
          "json"
        ).error(function() {
          alert("-");
        });
      }

// masukkan kode javascript berikutnya disini
var Document = {
        param: {
                dataperpage: 200, // jumlah data per halaman
                query: '',
                site_id: '',
                curpage: 0,
                numpage: 0,
                type_id: 0
        },
        url   : '<?php echo base_url()?>referensi/site_kandidat/loadData?site_id_map=<?php echo $site_id_map?>',
        search: function() {
                this.param.query = $('#query').val();
                this.param.curpage = 0;
                this.loadData();
                return false;
        },
	searchSiteID: function( siteid ) {
                this.param.site_id = siteid;
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
        deleteData: function(c,site_id) {
                var checkstr =  confirm('Data yang telah dihapus tidak dapat dikembalikan lagi. \n Anda yakin ingin menghapus data ini ?');
                if(checkstr == true){
                        this.param.site_id = site_id;
                        this.param.curpage = c;
                        
                        $.ajax({
                                url: '<?php echo base_url()?>referensi/site_kandidat/deleteData',
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
        editData: function( site_id ){
                $.post("<?php echo base_url()?>referensi/site_kandidat/addeditdata",{site_id:site_id},
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
										// var nomsite_id_encr = '<?php echo base64_encode('+dt.nomsite_id+');?>';
										// var site_id_tp_encr = '<?php echo base64_encode('+dt.no+');?>';
										var status_desc="";
										var action_button="";
										document.cookie = 'nomsite_id='+dt.no ;  
										
										if( (i+1) % 2 == 0) bgcolor = "#000000";
										if (dt.status==1){
											status_desc="candidate";
											// Jika user group = NSF(7)

											<?php if(get_instance()->map_model->in_multiarray( '7',$role, 'user_div_id' )){?>
											action_button ='<span onClick="verify(\''+dt.no+'\',\''+ dt.nomsite_id+'\',\'verify\')" class="btnProses btn btn-icon btn-success glyphicons ok" nomsite_id="'+ dt.nomsite_id +'" site_id="'+ dt.no +'" status="verify" ><i></i>Verify</span> &nbsp; ';
											action_button +='<span onClick="verify(\''+dt.no+'\',\''+ dt.nomsite_id+'\',\'failed\')" class="btnProses btn btn-icon btn-danger glyphicons remove" nomsite_id="'+ dt.nomsite_id +'" site_id="'+ dt.no +'" status="failed" ><i></i>Failed</span>';
											
											<?php } else{?>
											action_button='waiting for nsf verified';
											<?php } ?>
										}else if (dt.status==2){
											status_desc="<font color='geen'><b>verified</b></font>";
											status_desc="<b>verified</b>";
											// Jika user group = NPE(6)
											<?php if(get_instance()->map_model->in_multiarray( '6',$role, 'user_div_id' )){?>
												if (!dt.adasite > 0  ) {
													action_button ='<span onClick="verify(\''+dt.no+'\',\''+ dt.nomsite_id+'\',\'choose\')" class="btnChoose btn btn-icon btn-success glyphicons ok" nomsite_id="'+ dt.nomsite_id +'" site_id="'+ dt.no +'" status="choose" ><i></i>Choose</span> &nbsp; ';
												}
											<?php } else{?>
											// Jika user group = NSF(7) atau lainnya
											action_button='waiting for npe selection';
											<?php } ?>										
										}else if(dt.status==3){
											status_desc="<font color=red><b><img src='<?php echo base_url("/assets/images/icon-checked.gif")?>' >selected candidate</b></font>";
											<?php if(get_instance()->map_model->in_multiarray( '6',$role, 'user_div_id' )){?>
												if (dt.adasite == dt.no) {
													action_button ='<span onClick="verify(\''+dt.no+'\',\''+ dt.nomsite_id+'\',\'release\')" class="btnChoose btn btn-icon btn-danger glyphicons remove" nomsite_id="'+ dt.nomsite_id +'" site_id="'+ dt.no +'" status="release" ><i></i>Release</span>';
													bgcolor = "#000000";
												}
											<?php } else{?>
											// Jika user group = NSF(7) atau lainnya
											action_button='selected candidate';
											<?php } ?>
										}else if(dt.status==0){
											status_desc="<del>failed</del>";
											// Jika user group = NSF(7)
											action_button='';
											
										}
//nomsite_id,b.site_code, b.site_name, c.site_id, c.tp_name, c.lattitude, c.longitude, c.address, a.status, c.no
					t += '<tr class="selectable" style="background-color:'+bgcolor+' !important ">' +
                                                '<td class="grid_id">'+ ((i+1) ) +'</td>' +
                                                '<td class="grid_alias">'+ dt.site_code +'</td>' +
                                                '<td class="grid_alias">'+ dt.site_name +'</td>' +
                                                '<td class="grid_alias">'+ dt.site_id +'</td>' +
                                                '<td class="grid_alias">'+ dt.tp_name +'</td>' +
                                                '<td class="grid_alias">'+ dt.lattitude +'</td>' +
                                                '<td class="grid_alias">'+ dt.longitude +'</td>' +
                                              //  '<td class="grid_alias">'+ dt.address +'</td>' +
                                                '<td class="grid_alias">'+ parseFloat(dt.distance).toFixed(2) +'</td>' +
                                                '<td class="grid_alias">'+ dt.rank +'</td>' +
                                                '<td class="grid_alias" id="status'+dt.nomsite_id+dt.no+'">'+ status_desc +'</td>' +
						'<td class="grid_alias" svery="'+dt.status+'" nomsite_id="'+dt.nomsite_id+'" site_id="'+dt.no+'" id="pilih'+dt.nomsite_id+dt.no+'">'+action_button+'</td>' +
                                             '</tr>';
                                }
                                $('#document-data').html(t); // id dari tbody tabel data
                        }
                });
        }
}
           
$(document).ready(function() {
        Document.search();
       	
	var site_id = '';
	var nomsite_id = '';
	var status = '';
		
	// $('.btnProses').live("click",function()
	// {
	// 	site_id 	= $( this ).attr('site_id');
	// 	nomsite_id 	= $( this ).attr('nomsite_id');
	// 	status 		= $( this ).attr('status');
		
	// 	 $.post("<?php echo base_url()?>referensi/site_kandidat/dialogKandidat",
	// 			{site_id:site_id, nomsite_id:nomsite_id, status:status},
	// 		function(data){
	// 		  $('#dialog-proses').html(data);
	// 		  $('#dialog-proses').dialog('open');
	// 	 }, "html");
	// });
	
	$("#dialog-proses").dialog({
		resizable: false,
		autoOpen: false,
		title: 'Selected Candidate ?',
		show: "blind",
		height: 210,
		width: 320,
		modal: true,
		buttons: {
			'Save': function () {
				
				var remark = $("#remark").attr('value');
				$.post("<?php echo base_url()?>referensi/site_kandidat/saveKandidat",
					{site_id:site_id, nomsite_id:nomsite_id, status:status, remark:remark },
					function(data) {
						if(data.status_update == true ){
							var tStatus = "<del>failed</del>";
							var tPilih  = "";
							if (status == 'verify') {
								tStatus = '<b>verified</b>';
								tPilih  = 'waiting for nsf verified';
							}
							$("#dialog-proses").dialog("close");
							$("#status"+nomsite_id+site_id).html( tStatus );
							$("#pilih"+nomsite_id+site_id).html( tPilih );
							
							
							n= notyfy({
								layout: 'center',
								text:  'Saved', 
								type: 'success',
								modal: true
								});
							setTimeout(function(){ n.close(); }, 3000);
						}
					},
					"json"
				).error(function() {
					alert("-");
				});
			},
		Cancel: function() {
			$(this).dialog('close');
			
			}
		}
	});
	
	
	$('.btnChooseold').live("click",function()
	{
		site_id 	= $( this ).attr('site_id');
		nomsite_id 	= $( this ).attr('nomsite_id');
		status 		= $( this ).attr('status');
		
		$.post("<?php echo base_url()?>referensi/site_kandidat/chooseKandidat",
				{site_id:site_id, nomsite_id:nomsite_id, status:status},
			function(data){
				if (data.status_update == true) {
					//proses loop di sini
					var tStatus = "";
					var tPilih  = "";
					var newSiteID = "";
					var newStatusVery = "";
					
					$(".table").find("td[nomsite_id='"+nomsite_id+"']").each(function() {
						//var nim = $(this).val();
						newSiteID = $(this).attr('site_id');
						
						if (status == 'choose') {
							if (newSiteID == site_id) {
								tStatus = "<font color=red><b><img src='<?php echo base_url("/assets/images/icon-checked.gif")?>' >selected candidate</b></font>";
								tPilih  = '<span onClick="verify(\''+dt.no+'\',\''+ dt.nomsite_id+'\',\'release\')" class="btnChoose btn btn-icon btn-danger glyphicons remove" nomsite_id="'+ nomsite_id +'" site_id="'+ newSiteID +'" status="release" ><i></i>Release</span>';
								
								$(this).attr( 'svery', 3);
							}else{
								tStatus = "<b>verified</b>";
								tPilih  = '';
							}
							
						}else{
							tStatus = "<b>verified</b>";
							tPilih  = '<span onClick="verify(\''+dt.no+'\',\''+ dt.nomsite_id+'\',\'choose\')" class="btnChoose btn btn-icon btn-success glyphicons ok" nomsite_id="'+ nomsite_id +'" site_id="'+ site_id +'" status="choose" ><i></i>Choose</span> &nbsp; ';
							if($(this).attr('svery') >0 ) $(this).attr( 'svery', 2);
						}
						
						if ( $(this).attr('svery') == 2 || $(this).attr('svery') == 3 ) {
							$("#status"+nomsite_id+newSiteID).html( tStatus );
							$("#pilih"+nomsite_id+newSiteID).html( tPilih );
						}
						
					});
					
					n=notyfy({
						layout: 'center',
						text:  'Saved', 
						type: 'success',
						modal: true
						});
					setTimeout(function(){ n.close(); }, 3000);
				}
			 }, "json");
	});
	
	
	$("#search_site").live('submit', function () {
		
		var row = $('#dg').datagrid('getSelected');
		if (row){
			$("#site_idx").val(row.site_code);
			Document.searchSiteID( row.site_id );	
		}
		$.fancybox.close();
		return false;
    });

});

function verify(site_id, nomsite, status){
    $.fancybox({
      href:"<?php echo base_url()?>referensi/site_kandidat/dialogKandidatNSF?status="+status+"&site_id="+site_id+"&nomsite="+nomsite,type:'ajax',
      minWidth  : 500,
      scrolling : false,
      autoSize  :true,
      fitToView : true,
      closeClick  : false,
      openEffect  : 'fade',
      closeEffect : 'fade'
    }); 
}
</script>


<div class="innerLR">
	<div class="widget">
		<div class="widget-head  heading-outer">
			<h4 class="heading"><i></i> Site Candidate List</h4>            
             <a id="btn_export" href="<?php echo site_url('/export/site_info/site_candidate') ?>" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
          
		</div>
		<div class="widget-body">
			
			<div class="filter-bar" style="margin-bottom: 0px;">
				<div class="row-fluid">
					<div class="span9">
						<form id="tampilkanTopologi" method="post">
							<div>
								<label>Site ID:</label>
								<div class="input-append">
									<input name="site_code" id="site_idx" value="" readonly>
									<button id="doSearch" onClick="doSearchFancy()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
									<input type='hidden' name="site_code" id="site_code" value="">		

									<button id="doSearch" onClick="doReset()" style="margin-left:10px" class="btn">Reset</button>
								</div>
							</div>
						</form>
					</div>
				</div>	
			</div>
			
			
                        <table class="table table-bordered table-primary table-vertical-center ">
                        <thead>
                                <tr>
                                        <th style="width: 1%;">No.</th>
                                        <th>SF ID</th>
                                        <th>SF Site Name</th>
                                        <th>Site ID TP</th>
                                        <th>TP Name</th>
                                        <th>Lat</th>
                                        <th>Long</th>
<!--                                         <th style="width:150px">Address</th>
 -->                                        <th>Distance(m)</th>
                                        <th>Rank</th>
					<th>Status</th>
					<th>Selected Candidate</th>
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
<div id='dialog-proses'></div>
<div id='dialog-proses-nsf'></div>

<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script>
siteInfoMenu();
function doReset(){
	Document.param.curpage = 0;
    Document.loadData();
}
function doSearchFancy() {
    $.fancybox({
      href:"<?php echo base_url()?>referensi/site_kandidat/searchSite",type:'ajax',
      minWidth  :100,
      scrolling : false,
      autoSize  :true,
      fitToView : true,
      closeClick  : false,
      openEffect  : 'fade',
      closeEffect : 'fade',
      afterShow:function(){$('#app_no').focus();}
    }); 
}
</script>