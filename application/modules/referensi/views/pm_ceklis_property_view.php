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
    <th style="width:10%">ID</th>
    <th style="width:10%">Name</th>
    <th style="width:10%">Option</th>
    <th style="width:10%">Bobot</th>
    <th style="width:10%">Order</th>
    <th style="width:16%">Remarks</th>
  </tr>
  </thead>
  <tbody></tbody>
  </table>
</div>

<script type="text/template" id="tplPmCeklis">
  <tr id="${list_option_id}">
    <td>${list_option_id}</td>
    <td>${name}</td>
    <td>${option}</td>
    <td>${bobot}</td>
    <td>${urutan}</td>
    <td>${remarks}</td>
  </tr>
</script>

<script>
$.ajax({
  url: '<?php echo site_url('/referensi/referensi/pm_ceklis_property/'.get_instance()->uri->segment(4)) ?>',
  data: { cmd: 'GET_PM_CEKLIS_PROPERTY' },
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