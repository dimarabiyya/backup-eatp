<div class="box"> 
  <div class="title">
    <h5><?php echo lang('text_heading_title_tp_kandidat_list') ?></h5>
  </div>
  
  <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="records">                        
  <thead>
  <tr>
		<th style="width:5%">Site ID</th>
		<th style="width:10%">Site Name</th>
		<th style="width:10%">Site ID TP</th>
		<th style="width:10%">TP Name</th>
		<th style="width:17%">Lat</th>
		<th style="width:10%">Long</th>
		<th style="width:8%">Address</th>
		<th style="width:8%">Status</th>
		<th style="width:8%">Action</th>
  </tr>
  </thead>
  <tbody></tbody>
  </table>
</div>

<script type="text/template" id="tplKandidat">
  <tr id="${site_id_text}">
    <td>${site_id_text}</td>
    <td>${site_name}</td>
    <td>${site_id}</td>
    <td>${tp_name}</td>
    <td>${lattitude}</td>
    <td>${longitude}</td>
    <td>${address}</td>
    <td>${status}</td>
    <td width="110px"><a href="<?php echo site_url('/referensi/map/detil_tp');?>?tp_id=${no}">[Detil]</a>&nbsp;&nbsp;
	<a href="<?php echo site_url('/referensi/map/pilih_pemenang');?>?tp_id=${no}&nomsite_id=${nomsite_id}" onclick="return confirm(\'Are you sure?\');">[Pemenang]</a></td>
  </tr>
</script>

<script>
var param = 0;
<?php if (isset($_POST['nomsite'])) { ?>
param = <?php echo $_POST['nomsite'] ?>;
<?php } ?>

getKandidatList(param);
	
function getKandidatList(param) {
	$.ajax({
	  url: '<?php echo site_url('/referensi/map/kandidat_site_list') ?>',
	  data: { cmd: 'GET_KANDIDAT', nomsite: param },
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
		$('#tplKandidat').render( response ).appendTo( "#records > tbody" );
		
		//apply dataTable to #records table and save its object in dataTable variable
		if( typeof dataTable == 'undefined' )
		  dataTable = $( '#records' ).dataTable({"bJQueryUI": true});
		
		//hide ajax loader animation here...
		//$( '#ajaxLoadAni' ).fadeOut( 'slow' );
	  }
	});
}
</script>