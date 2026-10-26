<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
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
</style>

<?php
/*
  Document   : tenant_list
  Created on : May 29, 2015, 2:26:05 PM
  Author     : tumbas
  Description:
  Purpose of the PHP File follows.
 */
?>

<div class="innerLR">

  <div class="widget">	

    <div class="widget-head heading-outer">
      <h4 class="heading"><i></i> Tenant List</h4>
      <div class="btn-heading-group">
        <a href="#" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right" onclick="doAdd();"><i></i> Add New</a>
        <a href="<?php echo base_url('site_info/site_info_ref'); ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons list pull-right"><i></i> References List</a>
      </div>
    </div>

    <div class="widget-body">

      <div id="tb-reference" class="form-search" style="padding: 10px">
        <div class="input-append">
          <?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search..."') ?>
          <button id="btnSearch" class="btn" onclick="doSearch()"><i class="icon-search"></i></button>
        </div>
      </div>

      <table id="dg"></table>

    </div>

  </div>

</div>

<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<script>
          $(document).ready(function() {
            $('#dg').datagrid({
              url: '<?php echo base_url('site_info/reference/tenant_list'); ?>',
              method: 'post',
              pageSize: 15,
              pageList: [10, 15, 20, 30, 100, 200, 500],
              toolbar: '#tb-reference',
              striped: true,
              pagination: true,
              singleSelect: true,
//                  fitColumns: true,
              rownumbers: true,
              height: 'auto',
              columns: [[
                  {field: 'tenant_name', title: 'Tenant Name', width: 550},
                  {field: 'tenant_id_text', title: 'Tenant ID', width: 200},
                  {field: 'tenant_code', title: 'Tenant Code', width: 200, },
                  {field: 'action', title: 'Action', width: 100, align: 'center'},
                ]],
            });
          });

          function doSearch() {
            $('#dg').datagrid('load', {
              q: $('#query').val(),
            });
          }

          $('#query').on('keypress', function (e){
            if (e.which == 13) {
              doSearch();
            }
          });

          function doAdd() {
            $.fancybox({
              href: "<?php echo base_url('site_info/reference/tenant_add') ?>",
              type: 'ajax',
              minWidth: 450, scrolling: false, autoSize: true, fitToView: true, closeClick: false, openEffect: 'fade', closeEffect: 'fade', afterShow: function() {

              }
            });
          }

          function doEdit(id) {
            $.fancybox({
              href: "<?php echo base_url('site_info/reference/tenant_edit') ?>/" + id,
              type: 'ajax',
              minWidth: 450, scrolling: false, autoSize: true, fitToView: true, closeClick: false, openEffect: 'fade', closeEffect: 'fade', afterShow: function() {

              }
            });
          }

          function doDelete(id) {
            coolConfirmation('<b>Confirmation!</b><br>Are you sure to continue?', function() {
              $.ajax({
                url: '<?php echo base_url('site_info/reference/tenant_delete'); ?>',
                type: 'post',
                data: {id: id},
                dataType: 'json',
                beforeSend: function() {
                  block.show('Deleting data');
                },
                success: function(data) {
                  if (data.status == 'success') {
                    $.fancybox.close();
                    $('#dg').datagrid('reload');
                  }
                  coolNotification(data.message, data.status);
                },
                error: function(xhr, status, message) {
                  coolNotification('<b>Error!</b><br>An error occured : ' + message, 'error');
                },
                complete: function() {
                  block.close();
                }
              });
            });
          }

</script>