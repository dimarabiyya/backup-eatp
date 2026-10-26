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
<div class="separator bottom"></div>
<div class="innerLR">
  <div class="widget">
    <div class="widget-head">
      <h4 class="heading glyphicons list"><i></i><?php echo $judul ?></h4>
      <div class="buttons pull-right">
        <button class="btn btn-small btn-primary" onclick="addData();"><i class="icon-plus icon-white"></i> Add New</button>
      </div>
      <div>
      </div>
    </div>
    <div class="widget-body">
      <div id="tb" class="form-horizontal" style="padding: 10px">
        <div class="control-group">
          <label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
          <div class="input-append">
            <input name="q" id="q" type="text" placeholder="Type text to search .." >
            <button  onclick="searchData()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
          </div>	
        </div>	
      </div>
      <table id="dg" style="height: 500px"></table>
    </div>
  </div>
</div>

<script>
          $(document).ready(function() {

            $('#dg').datagrid({
              url: '<?php echo site_url("referensi/mobile_app/get_list"); ?>',
              toolbar: '#tb',
              rownumbers: true,
              pagination: true,
              singleSelect: true,
              fitColumns: true,
              sortName : 'release_date',
              sortOrder : 'DESC',
              columns: [[
                  {field: 'version_number', title: 'Version Number', width: 100, sortable : true},
                  {field: 'app_url', title: 'File APK', width: 200, sortable : true},
                  {field: 'release_date', title: 'Release Date', width: 100, align: 'center', sortable : true},
                  {field: 'active', title: 'Status', align: 'center', width: 100, sortable : true},
                  {field: 'creator', title: 'Creator', width: 100, sortable : true},
                  {field: 'action', title: 'Action', width: 50, align: 'center'},
                ]],
            });

            $("#q").keydown(function(e) {
              var code = e.which; // recommended to use e.which, it's normalized across browsers
              if (code == 13) {
                searchData();
                e.preventDefault();
              }
            });

          });

          function searchData() {
            $('#dg').datagrid('load', {
              q: $('#q').val(),
            });
          }

          function addData() {
            $.fancybox({
              href: '<?php echo site_url('referensi/mobile_app/add'); ?>', type: 'ajax',
              minWidth: 600, autoCenter: false, scrolling: false, autoSize: true, fitToView: true, closeClick: false, openEffect: 'fade', closeEffect: 'fade', afterShow: function() {
                $('#version_number').focus();
              }
            });
          }

          function editData(id) {
            $.fancybox({
              href: '<?php echo site_url('referensi/mobile_app/edit'); ?>/' + id, type: 'ajax',
              minWidth: 600, autoCenter: false, scrolling: false, autoSize: true, fitToView: true, closeClick: false, openEffect: 'fade', closeEffect: 'fade', afterShow: function() {
                $('#version_number').focus();
              }
            });
          }

          function deleteData(id) {
            coolConfirmation('Are you sure delete this data ?', function() {
              $.ajax({
                url: '<?php echo site_url('referensi/mobile_app/delete'); ?>',
                data: {version_id : id},
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                  block.show('Deleting data');
                },
                success: function(data) {
                  if (data.status == 'success') {
                    coolNotification(data.message, data.status);
                    $('#dg').datagrid('reload');
                  } else {
                    coolNotification(data.message, data.status);
                  }
                },
                error: function() {
                  coolNotification('An error occured, pleas try again or contact the administrator.', 'error');
                },
                complete: function() {
                  block.close();
                }
              });
            });
          }

</script>
