<?php
error_reporting(0);
//$this->load->model('map_model');
?>	

<script>
  //prevent conflict with another jquery easyui
  if (typeof $.fn.datagrid != 'function')
  {
    var script = document.createElement('script');
    script.type = "text/javascript";
    script.src = "<?php echo base_url('assets/js/easyui/jquery.easyui.min.js') ?>";
    document.getElementsByTagName('head')[0].appendChild(script);

    var script = document.createElement('link');
    script.type = "text/css";
    script.rel = 'stylesheet';
    script.href = "<?php echo base_url('assets/js/easyui/themes/default/easyui.css') ?>";
    document.getElementsByTagName('head')[0].appendChild(script);

    var script = document.createElement('link');
    script.type = "text/css";
    script.rel = 'stylesheet';
    script.href = "<?php echo base_url('assets/scripts/jeasyui/easyui.css') ?>";
    document.getElementsByTagName('head')[0].appendChild(script);

    var script = document.createElement('link');
    script.type = "text/css";
    script.rel = 'stylesheet';
    script.href = "<?php echo base_url('assets/scripts/jeasyui/icon.css') ?>";
    document.getElementsByTagName('head')[0].appendChild(script);

  }
</script>

<!--<script src="<?php echo base_url('/assets/js/easyui/jquery.easyui.min.js') ?>"></script>
<link href="<?php echo base_url('/assets/js/easyui/themes/default/easyui.css') ?>" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/icon.css">-->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/datagrid-detailview.js"></script>
<style type="text/css">
  .datagrid-body .datagrid-cell{
    word-wrap: break-word;
    white-space: normal;
  }

  .form-horizontal input {
    font-size: 12px;
  }

</style>

<script language="javascript" src="<?php echo base_url(); ?>assets/js/jquery.form.js"></script>
<style type="text/css">
  .datagrid-body .datagrid-cell{
    word-wrap: break-word;
    white-space: normal;
  }
</style>

<form action="#" class="form-horizontal" style="margin-bottom: 0;" method="POST" id="search_user">

  <div class="widget">

    <div class="widget-head"><h4 class="heading"><?php echo $judul ?></h4></div>
    <input type="hidden" name="select_pic_id">

    <div class="widget-body">

      <div class="row-fluid">

        <div>

          <!--					<div id="tb" style="padding:3px">
                                                          <div>
                                                              Area: <select name="territory" id="territory" style="width:150px;margin-bottom: 0px;line-height: 28px;">
          <?php //echo $this->map_model->getTerritoryTreeNewOpt(); ?>
                                                                  </select>
                                                              Search: <input id="srcSite" placeholder="Type text to search...." style="height:26px;border:1px solid #ccc" />
                                                              <a href="#" class="easyui-linkbutton" plain="true"
                                                                      onclick="searching()"><i class="icon-cari" style="background-position: -48px 0;"></i></a>
                                                          </div>
                              
                              </div>-->

          <div id="tb" style="padding:3px">
            <!--<div id="tb" style="padding:3px">-->
            <span id="division_span">
              &nbsp;Division : 
              <select name="division" id="division" style="width:170px;margin-bottom: 0px;line-height: 28px;">
                <option value=''>- All Division -</option>;
                <?php
                foreach ($division as $data) {
                  echo "<option value='$data->user_group_id'>$data->user_group_name</option>";
                }
                ?>
              </select>
            </span>
            <span id="search">
              &nbsp;Search : <input id="user" placeholder="Type text to search...." style="height:26px;border:1px solid #ccc" />
              <a href="#" class="easyui-linkbutton" plain="true" onclick="searching()"><i class="icon-cari" style="background-position: -48px 0;"></i></a>
            </span>
          </div>    

          <table id="dg"></table>

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

  </div>

</form>

<script type="text/javascript">

  var idCurrent = $('.select_site_id').last().attr('value');

  function searching() {
    $('#dg').datagrid('load', {
      user: $('#user').val(),
      group: $('#division').val()
    });
  }

  jQuery(document).ready(function() {

    $("#user").keypress(function(e) {

      if (e.which == 13) {
        searching();
        e.preventDefault();
      }
    });


    $('#dg').datagrid({
      pagination: true,
      pageSize: 10,
      url: "<?php echo base_url() . 'user/getAllUser'; ?>",
      fitColumns: true,
      checkOnSelect: true,
      selectOnCheck: true,
      toolbar: "#tb",
      idField: "user_id",
      textField: 'user_name',
      rownumbers: true,
      height: 350,
      mode: 'remote',
      width: 800,
      columns: [[
          {field: 'ck', checkbox: true, align: 'center'},
          {field: 'user_id', title: 'User ID', width: 30, sortable: true},
          {field: 'user_name', title: 'User Name', width: 30, sortable: true},
          {field: 'group', title: 'User Division', width: 30, sortable: true},
        ]],
      onCheck: function(rowIndex, rowData) {
      },
      onCheckAll: function(data) {
        $.each(data, function(i, item) {
        });
      },
      checkOnSelect : function(data) {
        $.each(data, function(i, item) {
        });
      }

    });

    $('.fancybox-wrap').draggable({handle: ".widget-head"});



  });

  $(document).on('change', '#division', function() {
    $('#dg').datagrid('load', {
      user: $('#user').val(),
      group: $('#division').val()
    });
  });

</script>