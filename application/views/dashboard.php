<style>
  .hi {
    width: 150px;
    height: 150px;
    background-image: url("<?php echo base_url() ?>assets/images/tower.png");
  }
  #container {
    min-width: 310px;
    max-width: 100%;
    height: 400px;
    margin: 0 auto
  }

</style>

<script src="<?php echo base_url() ?>/assets/plugins/highcarts/highcharts.js"></script>
<script src="<?php echo base_url() ?>/assets/plugins/highcarts/exporting.js"></script>
<script>
  $(document).ready(function() {
    $('#form-region').live("submit", function(e) {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>main/prosesSettingSession",
        data: $(e.target).serialize(),
        dataType: "json",
        beforeSend: function() {
          //$('.alert-error,.alert-success').hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
        },
        success: function(data) {
          //if(data.status == "OK"){
          alert("Data Session berhasil disimpan");
          location.reload();
          //}
        }
      });
      return false;
    });
  });
</script>

<!-- start edited by Hadi 2018-05-02 -->
<!-- sourde: https://datatables.net/extensions/buttons/examples/html5/simple.html -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>

<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" />

<!-- Bootstrap datepicker -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>

<script>
$(document).ready(function() {
    $('#example').DataTable( {
		"scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
} );
</script>
<!-- end edited by Hadi 2018-05-02 -->

<style>
  tbody .merge-center{
    vertical-align: inherit;
    text-align: center;
  }
  tbody .grand-total{
    vertical-align: inherit;
    text-align: center;
    font-weight: 700;
    background: #20a99a;
    color: #fff;
    font-size: 10pt;
  }
  tbody .sub-total{
    vertical-align: inherit;
    text-align: center;
    font-weight: 600;
    background: #fddeb2;
    font-size: 9pt;
  }
</style>

<!-- Info blocks -->


			<!-- /info blocks -->


<div class="innerLR" style="width:100%;">
  <?php if(strtoupper($this->session->userdata('ugrup')) == 'SYSTEM ADMIN' || strtoupper($this->session->userdata('ugrup')) == 'ADMIN' || strtoupper($this->session->userdata('ugrup')) == 'SCHEDULING' || strtoupper($this->session->userdata('ugrup')) == 'QUALITY ALITA' || strtoupper($this->session->userdata('ugrup')) == 'QUALITY CHECKER'){
  ?>

  <div class="widget">
    <div class="widget-head">
      <h4 class="heading glyphicons list"><i></i> Summary Results</h4>
    </div>

    <div class="widget-body" style="min-height:160px">

      <div style='margin:0 0 5px 5px' class='span2'>
        <!-- <a href='<?php echo base_url() ?>?id=sum_s_a' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> Schedule Assignment</span>
          <div class='clearfix'> <?php echo $sum_s_a; ?> </div>
        </a> -->
        <div onClick="filterModalOpen('<?php echo base_url() ?>?id=sum_s_a')" class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'>Schedule Assignment</span>
          <div class='clearfix'> <?php echo $sum_s_a; ?> </div>
        </div>
      </div>

      <!-- start edited by Hadi 2018-05-02 -->
	  <div style='margin:0 0 5px 5px' class='span2'>
        <!-- <a href='<?php echo base_url() ?>?id=sum_before_aprv_quality' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'>Before Approved by Alita</span>
          <div class='clearfix'> <?php echo $sum_before_aprv_quality; ?> </div>
        </a> -->
        <div onClick="filterModalOpen('<?php echo base_url() ?>?id=sum_before_aprv_quality')" class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'>Before Approved by Alita</span>
          <div class='clearfix'> <?php echo $sum_before_aprv_quality; ?> </div>
        </div>
      </div>

      <div style='margin:0 0 5px 5px' class='span2'>
        <!-- <a href='<?php echo base_url() ?>?id=sum_aprv_quality' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'>Approved by Alita</span>
          <div class='clearfix'> <?php echo $sum_aprv_quality; ?> </div>
        </a> -->
        <div onClick="filterModalOpen('<?php echo base_url() ?>?id=sum_aprv_quality')" class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'>Approved by Alita</span>
          <div class='clearfix'> <?php echo $sum_aprv_quality; ?> </div>
        </div>
      </div>
	  <!-- end edited by Hadi 2018-05-02 -->
	  
	 

      <div style='margin:0 0 5px 5px' class='span2'>
        <!-- <a href='<?php echo base_url() ?>?id=sum_s_fophs' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Approved By FOP HS </span>
          <div class='clearfix'> <?php echo $sum_s_fophs; ?> </div>
        </a> -->
        <div onClick="filterModalOpen('<?php echo base_url() ?>?id=sum_s_fophs')" class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Approved By FOP HS </span>
          <div class='clearfix'> <?php echo $sum_s_fophs; ?> </div>
        </div>
      </div>

      <div style='margin:0 0 5px 5px' class='span2'>
        <!-- <a href='<?php echo base_url() ?>?id=sum_s_spvhs' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Approved By SPV HS </span>
          <div class='clearfix'> <?php echo $sum_s_spvhs; ?></div>
        </a> -->
        <div onClick="filterModalOpen('<?php echo base_url() ?>?id=sum_s_spvhs')" class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Approved By SPV HS </span>
          <div class='clearfix'> <?php echo $sum_s_spvhs; ?> </div>
        </div>
      </div>

      <div style='margin:0 0 5px 5px' class='span2'>
        <!-- <a href='<?php echo base_url() ?>?id=sum_s_somhs' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Approved By SOM HS </span>
          <div class='clearfix'> <?php echo $sum_s_somhs; ?></div>
        </a> -->
        <div onClick="filterModalOpen('<?php echo base_url() ?>?id=sum_s_somhs')" class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Approved By SOM HS </span>
          <div class='clearfix'> <?php echo $sum_s_somhs; ?> </div>
        </div>
      </div>

      <div style='margin:0 0 5px 5px' class='span2'>
        <!-- <a href='<?php echo base_url() ?>?id=sum_s_rjt' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Rejected </span>
          <div class='clearfix'> <?php echo $sum_s_rjt; ?></div>
        </a> -->
        <div onClick="filterModalOpen('<?php echo base_url() ?>?id=sum_s_rjt')" class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Rejected </span>
          <div class='clearfix'> <?php echo $sum_s_rjt; ?> </div>
        </div>
      </div>

      <div class="clearfix"></div>

    </div>
  </div>

  <div class="widget">
    <div class="widget-head">
      <h4 class="heading glyphicons list"><i></i> Detail</h4>
    </div>

    <div class="widget-body" style="min-height:300px; width:100%">
	<!-- start edited by Hadi 2018-05-02 -->
	<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Visit ID</th>
                <th>TPD ID</th>
				<th>PO No</th>
				<th>EWO No</th>
				<th>Project Name</th>
				<th>Link ID</th>
				<th>Site NE Name</th>
				<th>Site FE Name</th>
				<!--th>Address NE</th>
				<th>Address FE</th-->
				<th>Field Eng</th>
        <?php if(isset($datatables_is_sum_s_a) && $datatables_is_sum_s_a == 1) { ?>
				<th>QC Date</th>
				<th>FOP HS Name</th>
				<th>FOP Date</th>
				<th>ROH HS Name</th>
				<th>ROH Date</th>
        <th>SOM HS Name</th>
				<th>SOM Date</th>
        <?php } else { ?>
				<th>FOP HS Name</th>
				<th>ROH HS Name</th>
        <th>SOM HS Name</th>
        <?php }  ?>
				<th>Visit Type</th>
				<th>Region</th>
				<th>Visit Plan</th>
				<th>Actual Visit Date</th>
				<th>Desc Status</th>
				<th>Aproval Status Order</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $datatables; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Visit ID</th>
                <th>TPD ID</th>
				<th>PO No</th>
				<th>EWO No</th>
				<th>Project Name</th>
				<th>Link ID</th>
				<th>Site NE Name</th>
				<th>Site FE Name</th>
				<!--th>Address NE</th>
				<th>Address FE</th-->
				<th>Field Eng</th>
        <?php if(isset($datatables_is_sum_s_a) && $datatables_is_sum_s_a == 1) { ?>
				<th>QC Date</th>
				<th>FOP HS Name</th>
				<th>FOP Date</th>
				<th>ROH HS Name</th>
				<th>ROH Date</th>
        <th>SOM HS Name</th>
				<th>SOM Date</th>
        <?php } else { ?>
				<th>FOP HS Name</th>
				<th>ROH HS Name</th>
        <th>SOM HS Name</th>
        <?php }  ?>
				<th>Visit Type</th>
				<th>Region</th>
				<th>Visit Plan</th>
				<th>Actual Visit Date</th>
				<th>Desc Status</th>
				<th>Aproval Status Order</th>
            </tr>
        </tfoot>
    </table>
	
    </div>
  </div>


  <?php
  }else{
  ?>


  <div class="widget">
    <div class="widget-head">
      <h4 class="heading glyphicons list"><i></i> Welcome Page</h4>
    </div>

    <div class="widget-body" style="min-height:160px">

       <p>  WELCOME TO EATP SYSTEM </p>

    </div>
  </div>


  <?php
  }
  ?>


</div>

<div class="modal fade" id="modalFilter">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <form id="formModal">
        <div class="form-group">
          <label>Pilih tahun</label>
        </div>
          <?php for ($i=2017; $i <= date('Y'); $i++) { ?> 
            <div class="row-fluid">
              <div class="span6">
                <div class="checkbox">
                  <label>
                        <input type="checkbox" value="<?php echo $i ?>" checked><?php echo $i ?>
                  </label>
                </div>
              </div>
              <?php $i++ ?>
              <div class="span6">
                <div class="checkbox">
                  <label>
                        <input type="checkbox" value="<?php echo $i ?>" checked><?php echo $i ?>
                  </label>
                </div>
              </div> 
            </div>
          <?php } ?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
  
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>

<script>
  let routeLink = "";
  
  $(document).ready(function() {
    $("#yearpicker").datepicker({
      format: "yyyy",
      viewMode: "years", 
      minViewMode: "years"
    });
  })

  function filterModalOpen(route) {
    routeLink = route;
    $('#modalFilter').modal('show');
  }

  $('#btnSubmit').on('click', function () {
    var year = "";
    $("#formModal input[type='checkbox']:checked").each(function (i) {
      if (i == 0) {
        year += this.value;
      } else {
        year += ","+this.value;
      }
    })
    // var year_format = "&year=" + year;
    // if (year.substring(0,1) = ","){
    //   year_format = "&year=" + year.substring(1);
    // }else{
    //   year_format = "&year=" + year;
    // }

    window.location.href = routeLink + "&year=" + year;
    // window.location.href = routeLink + year_format;
  })
</script>

<!-- ================================================================
     Tampilan dashboard modern (hanya tampilan & interaksi; onClick, form,
     dan data dari controller tidak diubah)
     ================================================================ -->
<style>
  /* View ini memakai width:100% inline; padding tema tidak boleh membuatnya melebar */
  .eatp .innerLR, .eatp .innerLR .widget-body { box-sizing: border-box; }

  /* ---------- Grid kartu KPI ---------- */
  .eatp .widget-body:has(> .span2 > .widget-stats) {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 16px;
    min-height: 0 !important;
  }
  .eatp .widget-body:has(> .span2 > .widget-stats) > .span2 { float: none; width: auto; margin: 0 !important; min-height: 0; }
  .eatp .widget-body:has(> .span2 > .widget-stats) > .clearfix { display: none; }

  /* ---------- Kartu KPI ---------- */
  .eatp .widget-stats {
    --accent: #c00505; --accent-bg: #fff1f1; --accent-2: #fa3131;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    height: auto;
    min-height: 132px;
    margin: 0;
    padding: 18px 18px 16px;
    border: 1px solid var(--eatp-line, #e8eaef);
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(16, 24, 40, .05), 0 12px 28px -18px rgba(16, 24, 40, .25);
    text-align: left;
    cursor: pointer;
    overflow: hidden;
    outline: 0;
    transition: transform .35s cubic-bezier(.2, .8, .2, 1), box-shadow .35s ease, border-color .25s ease;
    animation: eatp-kpi-in .6s cubic-bezier(.2, .8, .2, 1) backwards;
  }
  .eatp .widget-stats:after {
    content: "";
    position: absolute;
    top: -46px;
    right: -46px;
    width: 130px;
    height: 130px;
    border-radius: 50%;
    background: radial-gradient(circle, var(--accent-bg) 0%, rgba(255, 255, 255, 0) 70%);
    transition: transform .5s cubic-bezier(.2, .8, .2, 1);
  }
  .eatp .widget-stats:before {
    content: "";
    position: absolute;
    left: 0; right: 0; bottom: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--accent), var(--accent-2));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .45s cubic-bezier(.2, .8, .2, 1);
  }
  .eatp .widget-stats:hover, .eatp .widget-stats:focus {
    transform: translateY(-4px);
    border-color: var(--accent);
    box-shadow: 0 22px 40px -22px rgba(16, 24, 40, .45);
  }
  .eatp .widget-stats:hover:after { transform: scale(1.35); }
  .eatp .widget-stats:hover:before, .eatp .widget-stats.is-active:before { transform: scaleX(1); }
  .eatp .widget-stats.is-active { border-color: var(--accent); background: linear-gradient(180deg, var(--accent-bg), #fff 70%); box-shadow: 0 0 0 3px var(--accent-bg), 0 22px 40px -22px rgba(16, 24, 40, .45); }

  .eatp .widget-stats .glyphicons { position: relative; z-index: 1; order: 0; display: block; padding: 0; }
  .eatp .widget-stats .glyphicons i { display: flex; align-items: center; justify-content: center; width: 46px; height: 46px; border-radius: 14px; background: var(--accent-bg); }
  .eatp .widget-stats .glyphicons i:before { position: relative; top: auto; left: auto; color: var(--accent); font-size: 20px; line-height: 1; transition: transform .35s cubic-bezier(.2, .8, .2, 1); }
  .eatp .widget-stats:hover .glyphicons i:before { transform: scale(1.12) rotate(-6deg); }
  .eatp .widget-stats > .clearfix {
    position: relative; z-index: 1; order: 1;
    margin-top: 14px;
    color: #171b26;
    font-size: 30px;
    font-weight: 800;
    letter-spacing: -.03em;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
  }
  .eatp .widget-stats .txt { position: relative; z-index: 1; order: 2; display: block; padding: 4px 0 0; color: #6b7280; font-size: 12.5px; font-weight: 600; line-height: 1.4; }

  /* Warna & ikon per status (dipilih dari id pada onClick, markup tidak diubah) */
  .eatp .widget-stats[onclick*="id=sum_s_a'"] { --accent: #2563eb; --accent-bg: #e8effe; --accent-2: #60a5fa; }
  .eatp .widget-stats[onclick*="id=sum_s_a'"] .glyphicons i:before { content: "\e046"; }
  .eatp .widget-stats[onclick*="id=sum_before_aprv_quality'"] { --accent: #d97706; --accent-bg: #fef4e6; --accent-2: #fbbf24; }
  .eatp .widget-stats[onclick*="id=sum_before_aprv_quality'"] .glyphicons i:before { content: "\e056"; }
  .eatp .widget-stats[onclick*="id=sum_aprv_quality'"] { --accent: #16a34a; --accent-bg: #e8f7ee; --accent-2: #4ade80; }
  .eatp .widget-stats[onclick*="id=sum_aprv_quality'"] .glyphicons i:before { content: "\e194"; }
  .eatp .widget-stats[onclick*="id=sum_s_fophs'"] { --accent: #0891b2; --accent-bg: #e3f6fa; --accent-2: #22d3ee; }
  .eatp .widget-stats[onclick*="id=sum_s_fophs'"] .glyphicons i:before { content: "\e004"; }
  .eatp .widget-stats[onclick*="id=sum_s_spvhs'"] { --accent: #7c3aed; --accent-bg: #f1eafe; --accent-2: #a78bfa; }
  .eatp .widget-stats[onclick*="id=sum_s_spvhs'"] .glyphicons i:before { content: "\e044"; }
  .eatp .widget-stats[onclick*="id=sum_s_somhs'"] { --accent: #0f766e; --accent-bg: #e4f5f2; --accent-2: #2dd4bf; }
  .eatp .widget-stats[onclick*="id=sum_s_somhs'"] .glyphicons i:before { content: "\e333"; }
  .eatp .widget-stats[onclick*="id=sum_s_rjt'"] { --accent: #c00505; --accent-bg: #fff1f1; --accent-2: #fa3131; }
  .eatp .widget-stats[onclick*="id=sum_s_rjt'"] .glyphicons i:before { content: "\e193"; }

  .eatp .span2:nth-child(1) > .widget-stats { animation-delay: .05s; }
  .eatp .span2:nth-child(2) > .widget-stats { animation-delay: .1s; }
  .eatp .span2:nth-child(3) > .widget-stats { animation-delay: .15s; }
  .eatp .span2:nth-child(4) > .widget-stats { animation-delay: .2s; }
  .eatp .span2:nth-child(5) > .widget-stats { animation-delay: .25s; }
  .eatp .span2:nth-child(6) > .widget-stats { animation-delay: .3s; }
  .eatp .span2:nth-child(7) > .widget-stats { animation-delay: .35s; }
  @keyframes eatp-kpi-in { from { opacity: 0; transform: translateY(14px) scale(.98); } to { opacity: 1; transform: none; } }

  /* ---------- Detail (DataTables) ---------- */
  .eatp .eatp-dash-sub { margin-left: 6px; color: #c00505; font-weight: 700; }
  .eatp .dataTables_wrapper { font-size: 13px; color: #171b26; }
  .eatp .dataTables_wrapper .dt-buttons { margin-bottom: 14px; }
  .eatp .dataTables_wrapper .dt-button {
    margin: 0 6px 0 0 !important;
    padding: 7px 14px !important;
    border: 1px solid #d7dae1 !important;
    border-radius: 10px !important;
    background: #fff !important;
    color: #394050 !important;
    font-family: inherit;
    font-size: 12.5px !important;
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(16, 24, 40, .05);
    transition: transform .15s ease, border-color .15s ease, background-color .15s ease, color .15s ease;
  }
  .eatp .dataTables_wrapper .dt-button:hover { border-color: #c00505 !important; background: #fff1f1 !important; color: #c00505 !important; transform: translateY(-1px); }
  .eatp .dataTables_wrapper .dataTables_filter { margin-bottom: 14px; color: #6b7280; font-weight: 600; }
  .eatp .dataTables_wrapper .dataTables_filter input { width: 220px; margin-left: 8px; }
  .eatp table.dataTable { border-collapse: separate; border-spacing: 0; }
  .eatp table.dataTable thead th, .eatp table.dataTable tfoot th {
    padding: 12px 14px !important;
    border-color: #e8eaef !important;
    background-color: #f8f9fb;
    color: #6b7280;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    white-space: nowrap;
  }
  .eatp table.dataTable tfoot th { background: #fff; color: #9aa0ad; }
  /* Baris header/footer tersembunyi milik DataTables (scrollX) harus tetap setinggi 0 */
  .eatp .dataTables_scrollBody thead th, .eatp .dataTables_scrollBody tfoot th,
  .eatp .dataTables_scrollBody thead td, .eatp .dataTables_scrollBody tfoot td {
    height: 0 !important; padding-top: 0 !important; padding-bottom: 0 !important; line-height: 0 !important; border-top: 0 !important; border-bottom: 0 !important; background: transparent !important;
  }
  .eatp table.dataTable tbody td { padding: 11px 14px; border-top: 1px solid #eef0f3; white-space: nowrap; }
  .eatp table.dataTable.display tbody tr:hover > td, .eatp table.dataTable.display tbody tr:hover > .sorting_1 { background: #fff6f6 !important; }
  .eatp table.dataTable.display tbody tr.odd > td { background: #fbfbfd; }
  .eatp table.dataTable.display tbody tr > .sorting_1 { background: rgba(192, 5, 5, .03); }
  .eatp .dataTables_scrollHead, .eatp .dataTables_scrollBody { border-color: #e8eaef !important; }
  .eatp .dataTables_scrollBody { border-radius: 0 0 12px 12px; }
  .eatp .dataTables_wrapper .dataTables_info { padding-top: 16px; color: #6b7280; }
  .eatp .dataTables_wrapper .dataTables_paginate { padding-top: 12px; }
  .eatp .dataTables_wrapper .dataTables_paginate .paginate_button {
    margin: 0 2px;
    padding: 6px 12px !important;
    border: 1px solid #e8eaef !important;
    border-radius: 9px;
    background: #fff !important;
    color: #394050 !important;
    font-weight: 600;
  }
  .eatp .dataTables_wrapper .dataTables_paginate .paginate_button:hover { border-color: #fbc5c5 !important; background: #fff1f1 !important; color: #c00505 !important; }
  .eatp .dataTables_wrapper .dataTables_paginate .paginate_button.current,
  .eatp .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { border-color: #c00505 !important; background: #c00505 !important; color: #fff !important; }
  .eatp .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: .45; }
  .eatp table.dataTable td.dataTables_empty { padding: 40px 16px !important; background: #fff !important; text-align: left; white-space: normal; }
  /* Tabel sangat lebar (scrollX): pesan dibuat menempel di sisi kiri area yang terlihat */
  .eatp .eatp-empty { position: -webkit-sticky; position: sticky; left: 16px; display: flex; flex-direction: column; align-items: flex-start; gap: 6px; max-width: 520px; color: #6b7280; }
  .eatp .eatp-empty:before {
    content: "";
    width: 54px; height: 54px; margin-bottom: 6px;
    border-radius: 16px;
    background: #fff1f1 radial-gradient(circle at 50% 50%, #c00505 0 5px, transparent 6px);
    box-shadow: inset 0 0 0 10px #fff1f1, inset 0 0 0 12px rgba(192, 5, 5, .25);
    animation: eatp-empty-pulse 2s ease-in-out infinite;
  }
  .eatp .eatp-empty strong { color: #171b26; font-size: 15px; }
  .eatp .eatp-empty span { max-width: 460px; font-size: 13px; line-height: 1.6; }
  @keyframes eatp-empty-pulse { 50% { transform: scale(1.08); } }

  /* ---------- Pop-up pilih tahun ---------- */
  .eatp #modalFilter .modal-body { max-height: 60vh; }
  .eatp #modalFilter .checkbox { margin: 0 0 8px; padding: 0; }
  .eatp #modalFilter .checkbox label {
    display: flex; align-items: center; gap: 10px;
    margin: 0; padding: 10px 14px;
    border: 1px solid #e8eaef; border-radius: 12px;
    font-weight: 600; cursor: pointer;
    transition: border-color .15s ease, background-color .15s ease;
  }
  .eatp #modalFilter .checkbox label:hover { border-color: #fbc5c5; background: #fff6f6; }
  .eatp #modalFilter .checkbox label:has(input:checked) { border-color: #c00505; background: #fff1f1; color: #8e0404; }
  .eatp #modalFilter .checkbox input { margin: 0; }

  @media (max-width: 767px) {
    .eatp .widget-body:has(> .span2 > .widget-stats) { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
    .eatp .widget-stats { min-height: 118px; padding: 14px; }
    .eatp .widget-stats > .clearfix { font-size: 24px; }
    .eatp .dataTables_wrapper .dataTables_filter { float: none; text-align: left; }
    .eatp .dataTables_wrapper .dataTables_filter input { width: 100%; margin: 6px 0 0; }
  }
  @media (prefers-reduced-motion: reduce) {
    .eatp .widget-stats, .eatp .eatp-empty:before { animation: none; }
  }
</style>

<script>
(function ($) {
  if (!$) { return; }
  $(function () {
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var raf = window.requestAnimationFrame || function (fn) { return setTimeout(function () { fn(+new Date()); }, 16); };

    /* Angka KPI menghitung naik; teks akhir dikembalikan persis seperti aslinya */
    $('.widget-stats > .clearfix').each(function () {
      var el = this, original = $.trim($(el).text()), target = parseInt(original.replace(/[^\d]/g, ''), 10);
      if (reduce || isNaN(target) || target <= 0) { return; }
      var begin = null, duration = 1100;
      el.textContent = '0';
      raf(function step(now) {
        if (begin === null) { begin = now; }
        var p = Math.min((now - begin) / duration, 1);
        el.textContent = String(Math.round(target * (1 - Math.pow(1 - p, 3))));
        if (p < 1) { raf(step); } else { el.textContent = original; }
      });
    });

    /* Kartu bisa dipakai dengan keyboard */
    $('.widget-stats[onclick]').attr({ tabindex: 0, role: 'button' }).on('keydown', function (e) {
      if (e.keyCode === 13 || e.keyCode === 32) { e.preventDefault(); $(this).click(); }
    });

    var id = (window.location.search.match(/[?&]id=([a-z_]+)/) || [])[1];
    var years = (window.location.search.match(/[?&]year=([\d,]+)/) || [])[1];
    var $detail = $('#example').closest('.widget');

    function emptyHint() {
      if (id) { return; }
      $('#example td.dataTables_empty').html(
        '<div class="eatp-empty"><strong>Belum ada data yang dipilih</strong>' +
        '<span>Klik salah satu kartu di atas, pilih tahun, lalu tekan Submit untuk menampilkan detail datanya.</span></div>'
      );
    }
    $('#example').on('draw.dt', emptyHint);
    emptyHint();

    if (id) {
      var $card = $('.widget-stats[onclick*="id=' + id + '\'"]').addClass('is-active');
      var label = $.trim($card.find('.txt').text());
      if (label) {
        var list = years ? years.split(',') : [];
        var yearText = list.length > 2 ? list[0] + '–' + list[list.length - 1] : list.join(', ');
        $('<span class="eatp-dash-sub"></span>')
          .text('— ' + label + (yearText ? ' · ' + yearText : ''))
          .appendTo($detail.find('.widget-head .heading').first());
      }
      if ($detail.length) {
        setTimeout(function () {
          $('html, body').animate({ scrollTop: Math.max(0, $detail.offset().top - 80) }, reduce ? 0 : 600);
        }, 350);
      }
    }
  });
})(window.jQuery);
</script>
