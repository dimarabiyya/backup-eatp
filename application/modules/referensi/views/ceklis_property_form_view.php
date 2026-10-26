<div class="box"> 
  <div class="title">
    <h5><?php echo lang('text_heading_title_ceklis_property_form_list') ?></h5>
	<ul class="links">
			<li>
				<a href="http://localhost/pmts/index.php/referensi/referensi/ceklis_property_form">List Data</a>
			</li>
			<li>
				<a href="http://localhost/pmts/index.php/referensi/referensi/add_ceklis_property_form">Add Data</a>
			</li>
	</ul>
  </div>
  <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="records">                        
  <thead>
  <tr>
    <th style="width:10%">Property ID</th>
    <th style="width:10%">Name</th>
    <th style="width:10%">Descr</th>
    <th style="width:16%">Action</th>
  </tr>
  </thead>
  <tbody></tbody>
  </table>
</div>

<script type="text/template" id="tplCeklisPropertyForm">
  <tr id="${property_id}">
    <td>${property_id}</td>
    <td>${name}</td>
    <td>${descr}</td>
    <td width="110px"><a class="updateBtn" href="<?php echo site_url('referensi/referensi/edit_ceklis_property_form');?>/${property_id}">Edit</a> | <a class="deleteBtn" href="{deleteLink}">Delete</a></td>
  </tr>
</script>

<script>
$.ajax({
  url: '<?php echo site_url('/referensi/referensi/ceklis_property_form') ?>',
  data: { cmd: 'GET_CEKLIS_PROPERTY_FORM' },
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
    $('#tplCeklisPropertyForm').render( response ).appendTo( "#records > tbody" );
    
    //apply dataTable to #records table and save its object in dataTable variable
    if( typeof dataTable == 'undefined' )
      dataTable = $( '#records' ).dataTable({"bJQueryUI": true});
    
    //hide ajax loader animation here...
    //$( '#ajaxLoadAni' ).fadeOut( 'slow' );
  }
});
</script>