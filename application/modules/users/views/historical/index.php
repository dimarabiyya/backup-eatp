<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>
<script src="<?php echo base_url('assets/js/hehehe.js'); ?>"></script>

<style type="text/css">
  .datagrid-body .datagrid-cell{
    /*word-wrap: break-word;*/
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

<div class="innerLR">
  <div class="widg-histet">
    <div class="widg-histet-head heading-outer">
      <h4 class="heading glyphicons list"><i></i> Historical Data</h4>
      <!--      <div class="btn-heading-group">
              <a href="http://localhost/sims/index.php/site_info/add" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
              <a id="btn-export" href="http://localhost/sims/index.php/export/site_info/sites" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
            </div>-->
    </div>
    <div class="widg-histet-body">
      <!--      <div class="filter-bar" id="tb-hist" style="padding: 0 10px;">
              <form id="filter-data">
                <div>
                  <label>From</label>
                  <div class="input-append">
                    <input type="text" name="date_from" id="date_from" class="datepicker" style="width: 100px" value="<?php echo $date_from; ?>" required="">
                    <span class="add-on glyphicons calendar"><i></i></span>
                  </div>
                </div>
                <div>
                  <label>To </label>
                  <div class="input-append">
                    <input type="text" name="date_to" id="date_to" class="datepicker" style="width: 100px" value="<?php echo $date_to; ?>" required="">
                    <span class="add-on glyphicons calendar"><i></i></span>
                  </div>
                </div>
                <div>
                  <label>Search</label>
                  <div class="input-append">
                    <input name="q" id="q" type="text" placeholder="Type text to search .." autofocus="">
                  </div>
                </div>
                <div>
                  <label>
                    <button type="button" class="btn btn-small" onclick="searchData()">Filter</button>
                  </label>
                </div>
                <div class="clearfix"></div>
              </form>
            </div>-->
      <div id="tb-hist" class="form-horizontal" style="padding: 5px; width: auto">
        <div class="row">
          <div class="control-group">
            <div class="span2"><label class="control-label">Date Range</label></div>
            <div class="span8">
              : From
              <div class="input-append">
                <input type="text" name="date_from" id="date_from" class="datepicker" style="width: 100px" value="<?php echo $date_from; ?>" required="">
                <span class="add-on glyphicons calendar"><i></i></span>
              </div>
              to 
              <div class="input-append">
                <input type="text" name="date_to" id="date_to" class="datepicker" style="width: 100px" value="<?php echo $date_to; ?>" required="">
                <span class="add-on glyphicons calendar"><i></i></span>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="control-group">
            <div class="span2"><label class="control-label">User</label></div>
            <div class="span8">: 
              <div class="input-append">
                <input type="hidden" name="userid" id="userid">
                <input type="text" name="username" id="username" style="width: 280px">
                <button class="btn search-user" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="control-group">
            <div class="span2"><label class="control-label">Table Name</label></div>
            <div class="span8">:
              <div class="input-append">
                <input type="text" name="table_name" id="table_name" style="width: 280px">
                <button class="btn search-table" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="control-group">
            <div class="span2"><label class="control-label">Keywords</label></div>
            <div class="span8">:
              <input type="text" name="keyword1" id="keyword1" class="input-medium" />
			  <select name="operand" id="operand">
				<option value="OR">OR</option>
				<option value="AND">AND</option>
			  </select>
			  <input type="text" name="keyword2" id="keyword2" class="input-medium" />
            </div>
          </div>
        </div>
		<div class="row">
			<div class="control-group">
				<div class="span2"></div>
				<div class="span8">&nbsp;
					<button onclick="searchData()" class="btn" type="button">Apply</button>
				</div>
			</div>
		</div>
		
        <!--        <div class="control-group">
                  <label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
                  <div class="input-append">
                    <input name="q" id="q" type="text" placeholder="Type text to search .." autofocus="">
                    <button onclick="searchData()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
                  </div>	
                </div>-->
      </div>
      <!--<div class="clearfix"></div>-->
      <table id="dg-hist"></table>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {

  $('#dg-hist').datagrid({
	rownumbers: true,
	method: 'get',
	toolbar: "#tb-hist",
	fitColumns: false,
	striped: true,
	pagination: true,
	singleSelect: true,
	nowrap: true,
	pageSize: '15',
	pageList: [10, 15, 20, 50, 100],
	url: '<?php echo site_url('users/historical/get_list'); ?>',
	columns: [[
		{field: 'Table Name', title: 'Table Name', width: 200},
		{field: 'pk_field', title: 'PK Field', width: 200},
		{field: 'Primary Key', title: 'PK Value', width: 200},
//                        {field: 'Criteria', title: 'Criteria', width: 200},
		{field: 'Field Name', title: 'Changed Field', width: 200},
		{field: 'Value Before', title: 'Value Before', width: 150},
		{field: 'Value After', title: 'Value After', width: 150},
		{field: 'date2', title: 'Date', width: 150},
		{field: 'User Name', title: 'User Name', width: 200},
	  ]],
  });

  $("#q").keydown(function(e) {
	var code = e.which || e.keyCode; // recommended to use e.which, it's normalized across browsers
	if (code == 13) {
	  searchData();
	  e.preventDefault();
	}
  });

  $("#date_from").datepicker({
	dateFormat: "dd M yy",
	changeMonth: true,
	numberOfMonths: 1,
	changeYear: true,
	onSelect: function(selectedDate, el) {
	  $("#date_to").datepicker("option", "minDate", selectedDate);
	  if ($('#date_to').val() == '') {
		$("#date_to").datepicker("setDate", "0d");
		$("#date_from").datepicker("option", "maxDate", '0d');
	  }
	}
  });

  $('#date_to').datepicker({
	dateFormat: "dd M yy",
	changeMonth: true,
	numberOfMonths: 1,
	changeYear: true,
	maxDate: '0d',
	onSelect: function(selectedDate, el) {
	  $("#date_from").datepicker("option", "maxDate", selectedDate);
	}
  });

  //added by andedi (20141014), begin
  $('.search-user').live("click", function() {
	$.fancybox({
	  href: "<?php echo site_url() ?>user/searchAllUser", type: 'ajax',
	  minWidth: 100,
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
  });

  $("#search_user").live('submit', function() {
	var rows = $('#dg').datagrid('getSelections');
	var username = [];
	var userid = [];
	for (var i = 0; i < rows.length; i++) {
	  username.push(rows[i].user_name);
	  userid.push(rows[i].user_id);
	}
	$('#username').val(username.toString());
	$('#userid').val(userid.toString());
	$.fancybox.close();
	return false;
  });
  //end

  //added by andedi (20141014), begin
  $('.search-table').live("click", function() {
	$.fancybox({
	  href: "<?php echo site_url('users/historical/lookup_table') ?>", type: 'ajax',
	  minWidth: 100,
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
  });

  $("#search_table").live('submit', function() {
	var rows = $('#dg-tablelist').datagrid('getSelections');
	var tableName = [];
	for (var i = 0; i < rows.length; i++) {
	  tableName.push(rows[i].table_name);
	}
	$('#table_name').val(tableName.toString());
	$.fancybox.close();
	return false;
  });
  //end

});

function searchData() {
  $('#dg-hist').datagrid('load', {
	q1: $('#keyword1').val(),
	q2: $('#keyword2').val(),
	operand: $('#operand').val(),
	date_from: $('#date_from').val(),
	date_to: $('#date_to').val(),
	username: $('#username').val(),
	table_name: $('#table_name').val(),
  });
}

function viewDetail(tableName, field, primaryKey) {
  $.fancybox({
	href: "<?php echo site_url('users/historical/get_detail'); ?>?tbl=" + tableName + '&fld=' + field + '&pk=' + primaryKey, type: 'ajax',
	width: 800, autoCenter: false, scrolling: false, autoSize: true, fitToView: true, closeClick: false, openEffect: 'fade', closeEffect: 'fade', afterShow: function() {
	  //$('#app_no').focus();
	}
  });
}
</script>