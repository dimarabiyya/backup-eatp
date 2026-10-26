<?php
//error_reporting(0);
//$this->load->model('map_model');
?>	

<!--<script src="<?php echo base_url('/assets/js/easyui/jquery.easyui.min.js') ?>"></script>
<link href="<?php echo base_url('/assets/js/easyui/themes/default/easyui.css') ?>" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/icon.css">-->

<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/datagrid-detailview.js"></script>-->
<style type="text/css">
  .datagrid-body .datagrid-cell{
    word-wrap: break-word;
    white-space: normal;
  }

  .form-horizontal input {
    font-size: 12px;
  }

</style>

<!--<script language="javascript" src="<?php echo base_url(); ?>assets/js/jquery.form.js"></script>-->
<style type="text/css">
  .datagrid-body .datagrid-cell{
    word-wrap: break-word;
    white-space: normal;
  }
</style>

<form action="#" class="form-horizontal" style="margin-bottom: 0;" method="POST" id="search_table">
  <div class="widget">
    <div class="widget-head"><h4 class="heading"><?php echo $judul ?></h4></div>
    <input type="hidden" name="select_pic_id">
    <div class="widget-body">
      <div class="row-fluid">
        <div id="tb-tablelist" style="padding:3px">
          Search : 
          <div class="input-append">
            <input type="text" name="q" id="q" style="width: 280px">
            <button onclick="searching()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
          </div>
<!--          <span id="search">
        &nbsp;Search : <input id="q" placeholder="Type text to search...." style="height:26px;border:1px solid #ccc" />
        <a href="#" class="easyui-linkbutton" plain="true" onclick="searching()"><i class="icon-cari" style="background-position: -48px 0;"></i></a>
      </span>-->
        </div>    
        <table id="dg-tablelist"></table>
        <div class="clearfix"></div>
        <div class="form-actions">
          <button type="submit" class="btn btn-icon btn-primary glyphicons btn-small circle_ok"><i></i>Confirm</button>
          <button type="button" class="btn btn-icon btn-close btn-default glyphicons btn-small circle_remove"><i></i>Close</button>
          <script>
              $(".btn-close").click(function() {
                $.fancybox.close();
              });
          </script>
        </div>
      </div>
    </div>
  </div>
</form>

<script type="text/javascript">

  function searching() {
    $('#dg-tablelist').datagrid('load', {
      q: $('#q').val()
    });
  }

  jQuery(document).ready(function() {

    $("#q").keypress(function(e) {
      if (e.which == 13) {
        searching();
        e.preventDefault();
      }
    });


    $('#dg-tablelist').datagrid({
      pagination: true,
      pageSize: 10,
      method: 'get',
      url: "<?php echo base_url('users/historical/get_table_list'); ?>",
      fitColumns: true,
      checkOnSelect: true,
      selectOnCheck: true,
      toolbar: "#tb-tablelist",
//      autoCenter : true,
//      idField: "user_id",
//      textField: 'user_name',
      rownumbers: true,
      height: 400,
      mode: 'remote',
      width: 400,
      columns: [[
          {field: 'ck', checkbox: true, align: 'center'},
          {field: 'table_name', title: 'Table Name', width:100},
        ]],
    });

    $('.fancybox-wrap').draggable({handle: ".widget-head"});
  });

</script>