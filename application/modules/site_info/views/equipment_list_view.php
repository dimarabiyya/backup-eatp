<style>
  .table-primary thead th { font-size:12px; }
  th a { padding:0 20px 0 0; }
  th a, th a:focus { color:#FFFFFF; text-decoration:underline; }
  th a.sort-asc { background:url('<?php echo base_url('/themes/sims/images/asc.png') ?>') no-repeat right center; }
  th a.sort-desc { background:url('<?php echo base_url('/themes/sims/images/desc.png') ?>') no-repeat right center; }

</style>

<!--
<div class="heading-buttons">
        <h3>Equipment</h3>
        
        <div class="buttons pull-right">
                <a href="<?php echo site_url('/site_info/equipment/add') ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add New</a>
        </div>
</div>
<div class="separator top"></div>
-->

<form enctype="multipart/form-data" id="file_form" name="file_form" method="post" style="display: none">
<!--  <input name="file_app" id="file_app" type="file" accept=".xls, .xlsx">-->
</form>

<div class="innerLR">
  <div class="widget">

    <div class="widget-head heading-outer">
      <h4 class="heading"><i></i> Equipments Data</h4>
      <div class="btn-heading-group">
        <?php if ($this->mmenu->cekPermission($menu, 'menu_add')) { ?>
          <a href="<?php echo base_url('/site_info/equipment/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
        <?php } ?>
        <a id="btn_export" href="<?php echo base_url('/export/site_info/equipment') ?>" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
        <a title="Import BTS Info from Excel" href="#" id="btn_import" onclick="loadBtsImportForm();//$('#file_app').click()" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons file_import pull-right"><i></i> Import</a>
      </div>
    </div>

    <div class="widget-body">

      <div id="tb-equipment"  style="padding: 10px 10px 0px 10px; height:auto">
        <div class="input-append">
          <?php echo form_input('query-equipment', '', 'id="query-equipment" class="input-medium search-query" placeholder="Search..."') ?>
          <button id="btnSearch" class="btn" onclick="GRID_EQUIPMENT.search()">Search</button>
        </div>
      </div>

      <table id="grid-equipment" class="jgrid"></table>

    </div>
  </div>	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<style>
  .datagrid-header-rownumber { width:50px; }
  .datagrid-cell-rownumber { width:50px; }
</style>

<script>
          var GRID_EQUIPMENT = {
            params: {
              cmd: 'GET_DATAGRID_EQUIPMENT',
              query: ''
            },
            loadData: function() {
              $('#grid-equipment').datagrid({
                url: '<?php echo site_url('/site_info/equipment') ?>',
                queryParams: this.params,
                method: 'GET',
                pageSize: 15,
                pageList: [10, 15, 20, 30, 100, 200, 500],
                columns: [[
                    {field: 'site_id', hidden: true},
                    {field: 'site_code', title: 'Site ID', width: 150, sortable: true},
                    {field: 'site_name', title: 'Site Name', width: 150, sortable: true},
                    {field: 'vendor_id', title: 'Vendor ID', width: 150, sortable: true},
                    {field: 'region_name', title: 'Region', width: 150, sortable: true},
                    {field: 'actions', title: 'Actions', width: 100, align: 'center', fixed: true, formatter: function(value, row, index) {
                        return '\
<?php if ($this->mmenu->cekPermission($menu, 'menu_view')) { ?>\
                                                              <a href="<?php echo site_url('/site_info/equipment/view') ?>/' + row.site_id + '" class="btn-action glyphicons eye_open btn-info" title="View"><i></i></a>\
<?php } ?>\
                                                \
<?php if ($this->mmenu->cekPermission($menu, 'menu_edit')) { ?>\
                                                              <a href="<?php echo site_url('/site_info/equipment/edit') ?>/' + row.site_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
<?php } ?>\
                                                \
<?php if ($this->mmenu->cekPermission($menu, 'menu_delete')) { ?>\
                                                              <a href="javascript:GRID_EQUIPMENT.delete(\'' + row.site_id + '\')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
<?php } ?>
                                ';
                      }}
                  ]],
                toolbar: '#tb-equipment',
                striped: true,
                pagination: true,
                singleSelect: true,
                fitColumns: true,
                rownumbers: true,
                height: 'auto'
              });
            },
            search: function() {
              this.params.query = document.getElementById('query-equipment').value;
              this.loadData();
              document.getElementById('query-equipment').select();
            },
            delete: function(id) {
              coolConfirmation('Are you sure you want to delete?', function() {
                GRID_EQUIPMENT.params.cmd = 'DELETE_EQUIPMENT';
                GRID_EQUIPMENT.params.id = id;
                $.ajax({
                  url: '<?php echo site_url('/site_info/equipment') ?>',
                  type: 'GET',
                  data: GRID_EQUIPMENT.params,
                  dataType: 'json',
                  success: function(json) {
                    if (!json.error) {
                      GRID_EQUIPMENT.params.cmd = 'GET_DATAGRID_EQUIPMENT';
                      GRID_EQUIPMENT.loadData();
                    }
                  }
                });
              });
            },
            init: function() {
              this.params.query = document.getElementById('query-equipment').value;
              this.loadData();
              document.getElementById('query-equipment').select();

              document.getElementById('query-equipment').onkeydown = function(e) {
                if (e.keyCode == 13) {
                  GRID_EQUIPMENT.search();
                }
              };
            }
          };

          $(function() {
            GRID_EQUIPMENT.init();

            $('#file_app').change(function() {
              var formId = document.getElementById("file_form");
              var formData = new FormData(formId);
              $.ajax({
                url: '<?php echo base_url('site_info/equipment/bts_read_import_file_multisite'); ?>',
                data: formData,
                type: 'post',
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                  block.show('Processing data');
                },
                success: function(data) {
//                  if (data.status == 'success') {
//                    coolNotification('<h3>' + (data.status).toUpperCase() + '</h3>' + data.message + '', 'success');
//                  } else {
//                    coolNotification('<h3 style="color:white">Error!</h3>' + data.message, 'error', function() {
//                      window.location.href = '<?php echo base_url('site_info/equipment'); ?>';
//                    });
//                  }
                },
                error: function(xhr, status, message) {
                  coolNotification('<h3 style="color:white">Error!</h3> Import file failed, please contact your Administrator.', 'error', function() {
                    window.location.href = '<?php echo base_url('site_info/equipment'); ?>';
                  });
                },
                complete: function() {
                  block.close();
                  // refresh content tab
                  $('#grid-equipment').datagrid('reload');
                }
              });
            });

          });

          mainMenu();

          function loadBtsImportForm() {
            $.fancybox({
              href: "<?php echo base_url('site_info/equipment/bts_load_import_form'); ?>",
              type: 'ajax',
              minWidth: 700,
              autoCenter: false,
              scrolling: false,
              autoSize: true,
              fitToView: true,
              closeClick: false,
              openEffect: 'fade',
              closeEffect: 'fade',
              afterShow: function() {
                $('#app_no').focus();
              }
            });
          }
</script>