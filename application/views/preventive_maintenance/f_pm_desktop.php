<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/icon.css">

<script src="<?php echo base_url(); ?>assets/js/overlay.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery/jquery-1.10.2.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery/jquery-ui.js"></script>

 <script>
  $(function() {
    $( "#dateRangeFrom1" ).datepicker({
      showOn: "button",
      buttonImage: "<?php echo base_url() ?>assets/images/calendar-select-days-span.png",
      buttonImageOnly: true,
      defaultDate: "+1w",
      changeMonth: true,
      dateFormat: "d M yy",
      altField: "#dateFrom1",
      altFormat: "yy-mm-d",
      onClose: function( selectedDate ) {
        $( "#dateRangeTo1" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#dateRangeTo1" ).datepicker({
      showOn: "button",
      buttonImage: "<?php echo base_url() ?>assets/images/calendar-select-days-span.png",
      buttonImageOnly: true,
      defaultDate: "+1w",
      changeMonth: true,
      dateFormat: "d M yy",
      altField: "#dateTo1",
      altFormat: "yy-mm-d",
      onClose: function( selectedDate ) {
        $( "#dateRangeFrom1" ).datepicker( "option", "maxDate", selectedDate );
        if($("#dateFrom1").val() == ''){
          $("#dateTo1").val('');
          $(this).val('');
          alert('Must select "Date From"');
        }
      }
    });
  });
  </script>

<style type="text/css">
  .input-prepend .add-on {
    background: #efefef !important;
  }
  .ui-datepicker-trigger{
    padding: 6px;
    border-top: 1px #ddd solid;
    border-bottom: 1px #ddd solid;
    border-right: 1px #ddd solid;
    background: #efefef !important;
    border-radius: 0 4px 4px 0;
  }
</style>

<!-- <div class="heading-buttons">
  <h3>
    <?php echo $title;
      if(isset($subTitle) ) 
        { echo '<span class="hidden-phone">| '.$subTitle.'</span>'; } 
    ?>
  </h3>
</div> -->

<div class="separator bottom"></div>
<div class="innerLR">
  <div class="widget">
    <div class="widget-head">
      <h4 class="heading"><?php echo $title; ?></h4>
    </div>

    <div class="widget-body">
      <div class="tab-content">
        <!-- <div class="tab-pane active" id="tab-1"> -->
          <div class="toolbar-1">
            <?php if(count($territory)) { ?>
              <div class="input-prepend">
                <span class="add-on">Cluster</span>
                <select name="operational_cluster" id="cluster1" style="width: 130px;">
                <?php 
                  foreach ($territory as $tt) {
                    echo '<optgroup label="'.$tt["region_name"].'">';
                    foreach ($tt['cluster'] as $cc) 
                      { echo '<option value="'.$cc['cluster_ops_id'].'">'.$cc["cluster_name"].'</option>'; }
                    echo '</optgroup>';
                  }
                ?>
                </select>
                <input type="hidden" value="cluster" id="territorytype1">
              </div> &nbsp;
              <?php } else { ?>
              <div class="input-prepend">
                <span class="add-on">Region</span>
                <select name="operational_region" id="region1" style="width: 130px;">
                <?php 
                  foreach ($region as $tt) {
                    echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
                  }
                ?>
                </select>
                <input type="hidden" value="region" id="territorytype1">
              </div> &nbsp;
            <?php } ?>

            <div class="input-prepend input-append">
              <span class="add-on">Date From</span>
              <input type="text" name="from" id="dateRangeFrom1" readonly class="input-mini" style="width: 120px">
              <input type="hidden" id="dateFrom1">
              <span class="add-on glyphicons calendar"><i></i></span>
            </div> &nbsp;
            <div class="input-prepend input-append">
              <span class="add-on">Date To</span>
              <input type="text" name="to" id="dateRangeTo1" readonly class="input-mini" style="width: 120px">
              <input type="hidden" id="dateTo1">
              <span class="add-on glyphicons calendar"><i></i></span>
            </div>

            <div class="input-append pull-right">
              <input class="" id="keyword1" type="text" placeholder="Site ID" onkeyup="if(event.keyCode == 13){loadgrid(1)}"/> 
              <button class="btn" onclick="loadgrid(1)" >Search</button>
              <!-- <button class="btn" onclick="" >Export</button> -->
            </div>
          </div> <!-- end of toolbar -->

          <table id="dg1" width="100%" class="easyui-datagrid" fitColumns="true" singleSelect="true" striped="true" pagination="true">
            <thead>
              <tr>
                <th field="site_code" sortable="true" width="100px">Site ID</th>
                <th field="site_name" sortable="true" width="250px">Site Name</th>
                <th field="vendor_id" sortable="true" width="100px">Vendor ID</th>
                <th field="visit_plan" sortable="true" width="100px">Visit Plan</th>
                <th field="assigned_pic" sortable="true">Assigned PIC</th>
                <!-- <th rowspan="2" field="" sortable="true">PIC Name</th> -->
                <th field="visit_status">Status</th>
                <th field="pm_desktop_on" sortable="true" width="180px">PM Desktop Date</th>
                <th field="pm_desktop_by" sortable="true" width="180px">PM Desktop By</th>
                <th field="btnAction">Detail</th>
              </tr>
            </thead>
          </table>
        <!-- </div> -->

        <!-- <div class="tab-pane" id="tab-2">
          cbdrgdd
        </div> --> <!-- tab-pane-2 -->
      
        <!-- <div class="tab-pane" id="tab-3">
          ukjmbnbm
        </div> --> <!-- tab-pane-3 -->
    </div>
  </div>
</div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/datagrid-detailview.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
    loadgrid(1);
  });

  function loadgrid(idx) {
    console.log(idx);
    $('#dg'+idx).datagrid({
      url : "<?php echo base_url() ?>index.php/pm/desktop/grid",
      mothod : 'POST',
      queryParams: {
        region_id  : $('#region'+idx).val(),
        cluster_id : $('#cluster'+idx).val(),
        datefrom   : $('#dateFrom'+idx).val(),
        dateto     : $('#dateTo'+idx).val(),
        keywords   : $('#keyword'+idx).val()
      }
    });
  }

  function pmdesktopdetail (visit_id) {
    var nForm   = document.createElement("form");
        input_1 = document.createElement("input");

    nForm.action = "<?php echo base_url('pm/desktop/detail'); ?>";
    nForm.method = "post";
    
    input_1.name = "visit_id"; input_1.setAttribute("value", visit_id);

    nForm.appendChild(input_1);
    document.body.appendChild(nForm);

    nForm.submit();
  }
</script>
