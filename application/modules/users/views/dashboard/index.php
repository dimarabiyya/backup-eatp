<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>

<script src="<?php echo base_url('assets/js/highcharts/Highcharts-4.1.3/js/highcharts.js') ?>"></script>
<script src="<?php echo base_url('assets/js/highcharts/Highcharts-4.1.3/js/modules/exporting.js') ?>"></script>
<script src="<?php echo base_url('assets/js/highcharts/Highcharts-4.1.3/js/modules/no-data-to-display.js') ?>"></script>

<script src="<?php echo base_url('assets/js/hehehe.js'); ?>"></script>

<style type="text/css">
  .datagrid-body .datagrid-cell{
    /*word-wrap: break-word;*/
    /*white-space: normal;*/
  }

  .modal {
    left:50%;
    width:800px;
  }

  .datagrid-header-rownumber { width:50px; }

  .datagrid-cell-rownumber { width:50px; }

  select { width:auto; }
  .datagrid-row-selected {
    background: #FBD1D1;
    color: #000;
  }
</style>

<div class="heading-buttons">
  <h3>Dashboard</h3>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
  <div class="widget">
    <div class="widget-body">
      <div id="equipmentTab" class="easyui-tabs">
        <div title="Summary" data-options="href:'<?php echo base_url('users/dashboard/load_main_dashboard'); ?>'"></div>
        <div title="Detail" data-options="href:'<?php echo base_url('users/dashboard/load_detail_dashboard'); ?>'"></div>
      </div>
    </div>
  </div>
</div>