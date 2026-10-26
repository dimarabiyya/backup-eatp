<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>
<script src="<?php echo base_url('assets/js/hehehe.js'); ?>"></script>

<style type="text/css">
  .datagrid-body .datagrid-cell{
    word-wrap: break-word;
    white-space: normal;
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
      <!--      <div class="buttons pull-right">
              <button class="btn btn-small btn-primary" onclick="addData();"><i class="icon-plus icon-white"></i> Add New</button>
            </div>-->
      <div>
      </div>
    </div>
    <div class="widget-body">
      <div id="tb" style="padding: 5px 10px">
        <div class="row">
          <div class="control-group span2">
            <label class="control-label">Start Date</label>
            <div class="input-append">
              <input type="text" name="start_date" id="start_date" class="datepicker" style="width: 100px" value="" required="">
              <span class="add-on glyphicons calendar"><i></i></span>
            </div>
          </div>
          <div class="control-group span2">
            <label class="control-label">End Date</label>
            <div class="input-append">
              <input type="text" name="end_date" id="end_date" class="datepicker" style="width: 100px" value="" required="">
              <span class="add-on glyphicons calendar"><i></i></span>
            </div>
          </div>
          <div class="control-group span2">
            <label class="control-label">Search</label>
            <div class="input-append">
              <input name="q" id="q" type="text" placeholder="Type text to search .." autofocus="">
              <button  onclick="searchData()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
            </div>	
          </div>
        </div>	
      </div>

      <table id="dg"></table>
    </div>
  </div>
</div>

<script>
              $(document).ready(function() {

                $("#start_date").datepicker({
                  dateFormat: "dd M yy",
                  changeMonth: true,
                  numberOfMonths: 1,
                  changeYear: true,
                  maxDate: '0d',
                  onSelect: function(selectedDate, el) {
                    $("#end_date").datepicker("option", "minDate", selectedDate);
                    if ($('#end_date').val() == '') {
                      $("#end_date").datepicker("setDate", "0d");
                      $("#start_date").datepicker("option", "maxDate", '0d');
                    }
                  }
                });

                $('#end_date').datepicker({
                  dateFormat: "dd M yy",
                  changeMonth: true,
                  numberOfMonths: 1,
                  changeYear: true,
                  maxDate: '0d',
                  onSelect: function(selectedDate, el) {
                    $("#start_date").datepicker("option", "maxDate", selectedDate);
                  }
                });

                $('#dg').datagrid({
                  url: '<?php echo site_url("users/mobile_user_log/get_list"); ?>',
                  toolbar: '#tb',
                  rownumbers: true,
                  pagination: true,
                  singleSelect: true,
                  fitColumns: true,
                  sortName: 'created_on',
                  sortOrder: 'DESC',
                  pageSize: 15,
                  pageList: [10, 15, 20, 30, 100, 200, 500],
                  columns: [[
                      {field: 'log_key', title: 'Log Access', width: 100, sortable: true},
                      {field: 'created_on', title: 'Log Date', width: 100, sortable: true},
                      {field: 'creator', title: 'User ID', width: 100, sortable: true},
                      {field: 'mdn', title: 'MDN', width: 100, align: 'center'},
                      {field: 'iccid', title: 'ICCID', width: 100, align: 'center'},
                      {field: 'meid', title: 'MEID', width: 100, align: 'center'},
                      {field: 'app_version', width: 100, title: 'App Version', align: 'center'},
                    ]]
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
                  start_date: $('#start_date').val(),
                  end_date: $('#end_date').val(),
                });
              }
</script>
