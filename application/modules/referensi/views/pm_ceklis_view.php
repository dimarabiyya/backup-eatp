<div class="box"> 
  <div class="title">
    <h5><?php echo lang('text_heading_title_pm_ceklis_list') ?></h5>
	<ul class="links">
		<li>
			<a href="http://localhost/pmts/index.php/referensi/referensi/pm_ceklis">List Data</a>
		</li>
		<li>
			<a href="http://localhost/pmts/index.php/referensi/referensi/add_pm_ceklis">Add Data</a>
		</li>
	</ul>
  </div>
  <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="records">                        
  <thead>
  <tr>
    <th style="width:10%">Ceklis ID</th>
    <th style="width:10%">Group</th>
    <th style="width:10%">Descr</th>
    <th style="width:10%">Order</th>
    <th style="width:16%">Action</th>
  </tr>
  </thead>
  <tbody></tbody>
  </table>
</div>

<script type="text/template" id="tplPmCeklis">
  <tr id="${ceklis_id}">
    <td>${ceklis_id}</td>
    <td>${namagroup}</td>
    <td>${descr}</td>
    <td>${order}</td>
    <td width="110px"><a href="<?php echo site_url('referensi/referensi/pm_ceklis_property');?>/${ceklis_id}">PropertyForm</a> |
	<a class="updateBtn" href="<?php echo site_url('referensi/referensi/edit_pm_ceklis');?>/${ceklis_id}">Edit</a> | <a class="deleteBtn" href="<?php echo site_url('referensi/referensi/delete_pm_ceklis');?>/${ceklis_id}">Delete</a></td>
  </tr>
</script>

<script>
$.ajax({
  url: '<?php echo site_url('/referensi/referensi/pm_ceklis') ?>',
  data: { cmd: 'GET_PM_CEKLIS' },
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
    $('#tplPmCeklis').render( response ).appendTo( "#records > tbody" );
    
    //apply dataTable to #records table and save its object in dataTable variable
    if( typeof dataTable == 'undefined' )
      dataTable = $( '#records' ).dataTable({"bJQueryUI": true});
    
    //hide ajax loader animation here...
    //$( '#ajaxLoadAni' ).fadeOut( 'slow' );
  }
});
</script>