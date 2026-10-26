<div class="box"> 
  <div class="title">
    <h5><?php echo lang('text_heading_title_status_tp_list') ?></h5>
	<ul class="links">
		<li>
			<a href="http://localhost/pmts/index.php/referensi/referensi/status_tp">List Data</a>
		</li>
		<li>
			<a href="http://localhost/pmts/index.php/referensi/referensi/add_status_tp">Add Data</a>
		</li>
	</ul>
  </div>
  <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="records">                        
  <thead>
  <tr>
    <th style="width:10%">Type ID</th>
    <th style="width:10%">Name</th>
    <th style="width:10%">ID Text</th>
    <th style="width:16%">Action</th>
  </tr>
  </thead>
  <tbody></tbody>
  </table>
</div>

<script type="text/template" id="tplStatusTp">
  <tr id="${status_tp_id}">
    <td>${status_tp_id}</td>
    <td>${status_tp_name}</td>
    <td>${status_tp_id_text}</td>
    <td width="110px"><a class="updateBtn" href="<?php echo site_url('referensi/referensi/edit_status_tp');?>/${status_tp_id}">Edit</a> | <a class="deleteBtn" href="<?php echo site_url('referensi/referensi/delete_status_tp');?>/${status_tp_id}">Delete</a></td>
  </tr>
</script>

<script>
$.ajax({
  url: '<?php echo site_url('/referensi/referensi/status_tp') ?>',
  data: { cmd: 'GET_STATUS_TP' },
  dataType: 'json',
  success: function( response ) {
    var j=1;
    for( var i in response ) {
      response[ i ].nomor = j;
      j++;
    }
    //clear old rows
    $( '#records > tbody' ).html( '' );
    
    //append new rows
    $('#tplStatusTp').render( response ).appendTo( "#records > tbody" );
    
    //apply dataTable to #records table and save its object in dataTable variable
    if( typeof dataTable == 'undefined' )
      dataTable = $( '#records' ).dataTable({"bJQueryUI": true});
    
    //hide ajax loader animation here...
    //$( '#ajaxLoadAni' ).fadeOut( 'slow' );
  }
});
</script>