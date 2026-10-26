<div class="box"> 
  <div class="title">
    <h5><?php echo lang('text_heading_title_perangkat_site_ceklis_list') ?></h5>
  </div>
  <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="records">                        
  <thead>
  <tr>
    <th style="width:10%">Ceklis ID</th>
    <th style="width:10%">Descr</th>
    <th style="width:10%">Group ID</th>
    <th style="width:10%">Status</th>
    <th style="width:16%">Action</th>
  </tr>
  </thead>
  <tbody></tbody>
  </table>
</div>

<script type="text/template" id="tplPerangkatSiteCeklis">
  <tr id="${ceklis_id}">
    <td>${ceklis_id}</td>
    <td>${descr}</td>
	<td>${group_id}</td>
    <td>${status}</td>
    <td width="110px"><a class="updateBtn" href="<?php echo site_url('referensi/referensi/edit_perangkat_site_ceklis');?>/${ceklis_id}">Edit</a> | <a class="deleteBtn" href="<?php echo site_url('referensi/referensi/delete_perangkat_site_ceklis');?>/${ceklis_id}">Delete</a></td>
  </tr>
</script>

<script>
$.ajax({
  url: '<?php echo site_url('/referensi/referensi/perangkat_site_ceklis') ?>',
  data: { cmd: 'GET_PERANGKAT_SITE_CEKLIS' },
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
    $('#tplPerangkatSiteCeklis').render( response ).appendTo( "#records > tbody" );
    
    //apply dataTable to #records table and save its object in dataTable variable
    if( typeof dataTable == 'undefined' )
      dataTable = $( '#records' ).dataTable({"bJQueryUI": true});
    
    //hide ajax loader animation here...
    //$( '#ajaxLoadAni' ).fadeOut( 'slow' );
  }
});
</script>