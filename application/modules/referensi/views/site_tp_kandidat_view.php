<div class="box"> 
  <div class="title">
    <h5><?php echo lang('text_heading_title_site_tp_kandidat_list') ?></h5>
	<ul class="links">
		<li>
			<a href="http://localhost/pmts/index.php/referensi/referensi/site_tp_kandidat">List Data</a>
		</li>
		<li>
			<a href="http://localhost/pmts/index.php/referensi/referensi/add_site_tp_kandidat">Add Data</a>
		</li>
	</ul>
  </div>
  <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="records">                        
  <thead>
  <tr>
    <th style="width:10%">Site ID</th>
    <th style="width:10%">Site ID TP</th>
    <th style="width:10%">Status</th>
    <th style="width:16%">Action</th>
  </tr>
  </thead>
  <tbody></tbody>
  </table>
</div>

<script type="text/template" id="tplSiteTPKandidat">
  <tr id="${site_id}">
    <td>${site_id}</td>
	<td>${site_id_tp}</td>
    <td>${status}</td>
    <td width="110px"><a class="updateBtn" href="<?php echo site_url('referensi/referensi/edit_site_tp_kandidat');?>/${site_id}">Edit</a> | <a class="deleteBtn" href="<?php echo site_url('referensi/referensi/delete_site_tp_kandidat');?>/${site_id}">Delete</a></td>
  </tr>
</script>

<script>
$.ajax({
  url: '<?php echo site_url('/referensi/referensi/site_tp_kandidat') ?>',
  data: { cmd: 'GET_SITE_TP_KANDIDAT' },
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
    $('#tplSiteTPKandidat').render( response ).appendTo( "#records > tbody" );
    
    //apply dataTable to #records table and save its object in dataTable variable
    if( typeof dataTable == 'undefined' )
      dataTable = $( '#records' ).dataTable({"bJQueryUI": true});
    
    //hide ajax loader animation here...
    //$( '#ajaxLoadAni' ).fadeOut( 'slow' );
  }
});
</script>