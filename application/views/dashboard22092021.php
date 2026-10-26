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
    //Comment by Reza 22092021
    // $("#region").live("change", function() {
    //   var region_id = $(this).val();
    //   $.ajax({
    //     url: '<?php //echo base_url() ?>main/tampilkanCluster',
    //     type: 'POST',
    //     dataType: 'json',
    //     data: {"region_id": region_id},
    //     success: function(d) {
    //       if (d.cluster_ops.length == 0) {
    //         $('#cluster_ops').empty();
    //       } else {
    //         var t = '', dt = {};
    //         for (var i = 0; i < d.cluster_ops.length; i++) {
    //           dt = d.cluster_ops[i];
    //           var select = "";
    //           t += '<option value="' + dt.cluster_id + '" ' + select + '>' + dt.cluster_name + '</option>';
    //         }
    //         $('#cluster_ops').html(t);
    //       }
    //       //----------------------------------------
    //       if (d.cluster_net.length == 0) {
    //         $('#cluster_net').empty();
    //       } else {
    //         var t = '', dt = {};
    //         for (var i = 0; i < d.cluster_net.length; i++) {
    //           dt = d.cluster_net[i];
    //           var select = "";
    //           t += '<option value="' + dt.cluster_id + '" ' + select + '>' + dt.cluster_name + '</option>';
    //         }
    //         $('#cluster_net').html(t);
    //       }
    //       //----------------------------------------
    //       if (d.cluster_com.length == 0) {
    //         $('#cluster_com').empty();
    //       } else {
    //         var t = '', dt = {};
    //         for (var i = 0; i < d.cluster_com.length; i++) {
    //           dt = d.cluster_com[i];
    //           var select = "";
    //           t += '<option value="' + dt.cluster_id + '" ' + select + '>' + dt.cluster_name + '</option>';
    //         }
    //         $('#cluster_com').html(t);
    //       }
    //     }
    //   });
    // });

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
        <a href='<?php echo base_url() ?>?id=sum_s_a' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> Schedule Assignment</span>
          <div class='clearfix'> <?php echo $sum_s_a; ?> </div>
        </a>
      </div>

      <!-- start edited by Hadi 2018-05-02 -->
	  <div style='margin:0 0 5px 5px' class='span2'>
        <a href='<?php echo base_url() ?>?id=sum_before_aprv_quality' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'>Before Approved by Alita</span>
          <div class='clearfix'> <?php echo $sum_before_aprv_quality; ?> </div>
        </a>
      </div>

      <div style='margin:0 0 5px 5px' class='span2'>
        <a href='<?php echo base_url() ?>?id=sum_aprv_quality' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'>Approved by Alita</span>
          <div class='clearfix'> <?php echo $sum_aprv_quality; ?> </div>
        </a>
      </div>
	  <!-- end edited by Hadi 2018-05-02 -->
	  
	  <!-- start commented by Hadi 2018-05-02 -->
	  <!--div style='margin:0 0 5px 5px' class='span2'>
        <a href='javascript::void(0);' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Approved </span>
          <div class='clearfix'> <!?php echo $sum_s_apr; ?> </div>
        </a>
      </div>
	  <!-- end commented by Hadi 2018-05-02 -->

      <!-- start commented by Hadi 2018-05-02 -->
	  <!--div style='margin:0 0 5px 5px' class='span2'>
        <a href='javascript::void(0);' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Visited </span>
          <div class='clearfix'> <!?php echo $sum_s_vst; ?></div>
        </a>
      </div>
	  <!-- end commented by Hadi 2018-05-02 -->

      <div style='margin:0 0 5px 5px' class='span2'>
        <a href='<?php echo base_url() ?>?id=sum_s_fophs' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Approved By FOP HS </span>
          <div class='clearfix'> <?php echo $sum_s_fophs; ?> </div>
        </a>
      </div>

      <div style='margin:0 0 5px 5px' class='span2'>
        <a href='<?php echo base_url() ?>?id=sum_s_spvhs' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Approved By SPV HS </span>
          <div class='clearfix'> <?php echo $sum_s_spvhs; ?></div>
        </a>
      </div>

      <!-- start commented by Hadi 2018-05-02 -->
	  <!--div style='margin:0 0 5px 5px' class='span2'>
        <a href='javascript::void(0);' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Approved By PMO XL </span>
          <div class='clearfix'> <!?php echo $sum_s_spvxl; ?></div>
        </a>
      </div>
	  <!-- end commented by Hadi 2018-05-02 -->

      <div style='margin:0 0 5px 5px' class='span2'>
        <a href='<?php echo base_url() ?>?id=sum_s_rjt' class='widget-stats'>
          <span class='glyphicons coins'><i></i></span>
          <span class='txt'> eATP Rejected </span>
          <div class='clearfix'> <?php echo $sum_s_rjt; ?></div>
        </a>
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
        <?php } else { ?>
				<th>FOP HS Name</th>
				<th>ROH HS Name</th>
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
        <?php } else { ?>
				<th>FOP HS Name</th>
				<th>ROH HS Name</th>
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
	<!-- end edited by Hadi 2018-05-02 -->
<!--
      <form class="form-horizontal" id="form-region" style="margin-bottom: 0;" method="POST" >
        <div class="row-fluid">
          <div class="row-fluid">
            <div class="span12">
              <div style="margin-bottom: 10px;">
                <div class="input-prepend">
                  <span class="add-on">Filter By Year</span>

                   <?php $js = 'id="filter_region" onChange=""'; ?>
                    <?php echo form_dropdown('filter_region', $option_years ,$selected_year, $js); ?>
                </div>
              </div>
              <div id="table_dashboard">
                <table width="85%" class="table table-bordered"  border="0" align="right">
                  <tr>
                    <th colspan="6"><?php echo $title?></th>
                    <th colspan="4" style="text-align:center;">Project Implementation</th>
                  </tr>
                  <tr>
                    <?php foreach($data_header as $item):?>
                        <th class="merge-center"><?php echo $item; ?></th>
                    <?php endforeach;?>
                  </tr>
                  <tbody>
                      <?php foreach($summary as $item):?>
                      <tr>
                        <td rowspan="2" class="merge-center"><?php echo $item->region;?></td>
                        <td>Installation</td>
                        <td style="text-align: right;"><?php echo @$item->installation->po;?></td>
                        <td style="text-align: right;"><?php echo @$item->installation->din;?></td>
                        <td style="text-align: right;"><?php echo @$item->installation->pi_closed;?></td>
                        <td style="text-align: right;"><?php echo @$item->installation->ess;?></td>
                        <td style="text-align: right;"><?php echo @$item->installation->pi_done;?></td>
                        <td style="text-align: right;"><?php echo @$item->installation->pi_ongoing;?></td>
                        <td style="text-align: right;"><?php echo @$item->installation->pi_hold;?></td>
                        <td style="text-align: right;"><?php echo @$item->installation->pi_notstart;?></td>
                      </tr>
                      <tr>
                        <td>Add Service</td>
                        <td style="text-align: right;"><?php echo @$item->addservice->po;?></td>
                        <td style="text-align: right;"><?php echo @$item->addservice->din;?></td>
                        <td style="text-align: right;"><?php echo @$item->addservice->pi_closed;?></td>
                        <td style="text-align: right;"><?php echo @$item->addservice->ess;?></td>
                        <td style="text-align: right;"><?php echo @$item->addservice->pi_done;?></td>
                        <td style="text-align: right;"><?php echo @$item->addservice->pi_ongoing;?></td>
                        <td style="text-align: right;"><?php echo @$item->addservice->pi_hold;?></td>
                        <td style="text-align: right;"><?php echo @$item->addservice->pi_notstart;?></td>
                      </tr>
                      <tr>
                        <td colspan="2" class="sub-total">Total</td>
                        <td class="sub-total"><?php echo @$item->total_po;?></td>
                        <td class="sub-total"><?php echo @$item->total_din;?></td>
                        <td class="sub-total"><?php echo @$item->total_atp;?></td>
                        <td class="sub-total"><?php echo @$item->total_ess;?></td>
                        <td class="sub-total"><?php echo @$item->total_done;?></td>
                        <td class="sub-total"><?php echo @$item->total_ongoing;?></td>
                        <td class="sub-total"><?php echo @$item->total_hold;?></td>
                        <td class="sub-total"><?php echo @$item->total_notstart;?></td>
                      </tr>
                    <?php endforeach;?>
                    <tr>
                      <td colspan="2" class="grand-total">Grand Total</td>
                      <td class="grand-total"><?php echo @$grand_total->po;?></td>
                      <td class="grand-total"><?php echo @$grand_total->din;?></td>
                      <td class="grand-total"><?php echo @$grand_total->atp;?></td>
                      <td class="grand-total"><?php echo @$grand_total->ess;?></td>
                      <td class="grand-total"><?php echo @$grand_total->done;?></td>
                      <td class="grand-total"><?php echo @$grand_total->ongoing;?></td>
                      <td class="grand-total"><?php echo @$grand_total->hold;?></td>
                      <td class="grand-total"><?php echo @$grand_total->notstart;?></td>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td class="sub-total">
                      <?php
                      if($grand_total->din ==0 || $grand_total->po ==0 ){
                          echo "0";
                      }else{
                          echo round((@$grand_total->din/@$grand_total->po)*100);
                      }
                      ?>%</td>
                      <td class="sub-total">
                       <?php
                      if($grand_total->atp ==0 || $grand_total->po ==0 ){
                          echo "0";
                      }else{
                          echo round((@$grand_total->atp/@$grand_total->po)*100);
                      }
                      ?>
                      %</td>
                      <td class="sub-total">
                      <?php
                      if($grand_total->ess ==0 || $grand_total->po ==0 ){
                          echo "0";
                      }else{
                          echo round((@$grand_total->ess/@$grand_total->po)*100);
                      }
                      ?>
                      %</td>
                      <td colspan="3"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </form>
	  -->
    </div>
  </div>
<!--
  <div class="widget">
    <div class="widget-head">
      <h4 class="heading glyphicons list"><i></i>Chart</h4>
    </div>
    <div class="widget-body">
      <div id="container"></div>
    </div>
  </div>
-->

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

<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<!--
<?php //if ($this->session->flashdata('trial_message') != '') { ?>
  <script>
    //coolNotification('Welcome to SiMS live version. Data that you have updated here will be recorded and no longer been ignored as previously in trial version. Enjoy work with SiMS!', 'info');
  </script>
  <?php
  //$this->session->set_flashdata('trial_message', '');
//}
?>
-->
<!-- <script>
  /*When year is changed test -reza 22092021*/
  document.getElementById('filter_region').onchange = function (){
      var year_selected = this.value;
      /*get data summary*/
      var url = "<?php// echo base_url('') ?>";
      /*Progress Loading*/
      $.blockUI({
          message: '<img src="<?php //echo base_url('file/images/loading-snake.GIF'); ?>" width="16px"> Uploading Data, please wait...',
          css: {
            border: 'none',
            padding: '15px',
            backgroundColor: 'black',
            color: 'white',
            opacity: .5,
          }
      });

      $.get(url,{
        cmd: 'filter',
        year :year_selected
      },function(data){
          if(data!=""){
            $("#table_dashboard").empty();
            $("#table_dashboard").append(data);
            $.unblockUI();
          }
      });
  }
</script> -->

<!-- <script>
  /*Generate Graph updated Reza 22092021*/
  var chart = Highcharts.chart('container', {
    title: {
        text: '<?php //echo $title_line_chart?>'
    },

    subtitle: {
        text: '<?php //echo $subtitle_line_chart; ?>'
    },

    yAxis: {
        title: {
            text: 'Amount'
        }
    },
    xAxis: {
        categories: <?php //echo $region_line_chart;?>
    },
    plotOptions: {
        series: {
            allowPointSelect: true
        }
    },
    series: <?php //echo $data_series_line_chart;?>
});


// the button action
$('#button').click(function () {
    var selectedPoints = chart.getSelectedPoints();

    if (chart.lbl) {
        chart.lbl.destroy();
    }
    chart.lbl = chart.renderer.label('You selected ' + selectedPoints.length + ' points', 100, 60)
        .attr({
            padding: 10,
            r: 5,
            fill: Highcharts.getOptions().colors[1],
            zIndex: 5
        })
        .css({
            color: 'white'
        })
        .add();
});

</script> -->
