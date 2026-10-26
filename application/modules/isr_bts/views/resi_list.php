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
  <h3>Resi List</h3>
  <div class="buttons pull-right">
    <?php
    $url = $this->uri->segment(2) != '' ? $url = $this->uri->segment(1) . '/' . $this->uri->segment(2) : $this->uri->segment(1);
    $menu = $this->mmenu->getMenuId($url);
    ?>

    <?php if ($this->mmenu->cekPermission($menu, 'menu_add')) { ?>
    <a href="<?php echo base_url('isr_bts/resi/add_propose_new'); ?>" class="btn btn-small btn-primary btn-icon glyphicons circle_plus"><i></i>Add New</a>
<!--      <div class="btn-group dropdown">
        <button class="btn dropdown-toggle" data-toggle="dropdown">
          Add Resi <span class="caret"></span>
        </button>	
        <ul class="dropdown-menu pull-right">
          <li style="text-align:left"><a href="<?php echo base_url('isr_bts/resi/add_propose_new'); ?>">From Propose New</a></li>
          <li style="text-align:left"><a href="<?php echo base_url('isr_bts/resi/add_propose_discontinue'); ?>">From Propose Discontinue</a></li>
        </ul>			
      </div>				-->
    <?php } ?>
  </div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
  <div class="widget">
    <div class="widget-body">
      <div id="equipmentTab" class="easyui-tabs">
        <div title="Proposed New" data-options="href:'<?php echo base_url('isr_bts/resi/load_proposed_new'); ?>'"></div>
        <!--<div title="Proposed Discontinued" data-options="href:'<?php echo base_url('isr_bts/resi/load_proposed_discontinued'); ?>'"></div>-->
      </div>
    </div>
  </div>
</div>