<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>
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
  <h3>ISR BTS List</h3>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
  <div class="widget">
    <div class="widget-body">
      <div id="equipmentTab" class="easyui-tabs">
        <div title="Data" data-options="href:'<?php echo base_url('isr_bts/isr_bts/load_isr_bts_active'); ?>'"></div>
        <div title="Expired" data-options="href:'<?php echo base_url('isr_bts/isr_bts/load_isr_bts_expired'); ?>'"></div>
        <div title="History" data-options="href:'<?php echo base_url('isr_bts/isr_bts/load_isr_bts_history'); ?>'"></div>
        <!--<div title="Check Double ISR" data-options="href:'<?php echo base_url('isr_bts/isr_bts/load_isr_bts_check_double'); ?>'"></div>-->
      </div>
    </div>
  </div>
</div>

<script>
  function viewDocs(app_id) {
    $.fancybox({
      href: "<?php echo base_url() ?>isr_bts/view_isr_bts_docs/" + app_id, type: 'ajax',
      minWidth: 1000, openEffect: 'elastic', closeEffect: 'elastic'
    });
  }
</script>