<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/easyui.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/icon.css">-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/datagrid-detailview.js"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<!--<script src="<?php echo base_url() ?>assets/js/overlay.js"></script>-->
<!--<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>-->
<style type="text/css">
  /*  .datagrid-body .datagrid-cell{
      word-wrap: break-word;
      white-space: normal;
    }*/
  #gritter-notice-wrapper {
    position:fixed;		
    top: 38%;
    left: 38%;
    width:301px;
    z-index:9999;
  }
  .uppercase {text-transform:uppercase;}

  .datagrid-header-rownumber { width:50px; }

  .datagrid-cell-rownumber { width:50px; }

  select { width:auto; }
  .datagrid-row-selected {
    background: #FBD1D1;
    color: #000;
  }
</style>

<script>

  $(document).ready(function() {
    uindex = 0;


    $('#dg').datagrid({
      pageSize: 10,
//      fitColumns: true,
      nowrap: false,
      singleSelect: true,
      pageSize: 15,
              pageList: [10, 15, 20, 30, 100, 200, 500],
    });

    $("#btnAddData").live("click", function() {
      $("#divForm").show();
      $("#h4judul").html("Add New");
      $('body,html').animate({scrollTop: 0}, 800);

      $('#add-form')[0].reset();
      $('#status').attr('checked', false);
      $("#group_id").val("");
    });

    $("#btnCancelAddData").live("click", function() {
      $("#divForm").hide();
      $('#divForm').find('form')[0].reset();
      $("#tbl-user-body").html("");
    });
    $(".btn-remove-row").live("click", function() {
      $(this).parent().parent().remove();
    });
    $(".btnEditData").live("click", function() {
      $("#divForm").show();
      $("#h4judul").html("Edit Data Menu");
      $('body,html').animate({scrollTop: 0}, 800);

      $("#group_id").val($(this).attr('group_id'));
      $("#nama").val($("td:eq(1)", $(this).parent().parent()).text());
      // $("#parent")	.val( $(this).attr('p_id') );

      $('#status').prop('checked', ($("td:eq(2)", $(this).parent().parent()).text() == "Aktif" ? true : false));

    });
    $(".mdn").live("focusout", function() {
      hp = $(this).val();
      if (hp.substring(0, 2) == '08')
        format_hp = '628' + hp.substring(2);
      else
        format_hp = hp;

      format_hp = format_hp.replace(/\s/g, "");

      $(this).val(format_hp);
    });


    $('#add-form').live("submit", function(e) {
      $.ajax({
        type: "POST",
        url: "<?php echo site_url('user/saveMobileUserManagement') ?>",
        data: $(e.target).serialize(),
        dataType: "json",
        beforeSend: function() {
          block.show('Saving data');
        },
        error: function() {
          coolNotification('An error occured, please try again or contact the administrator.', 'error');
        },
        success: function(data) {
          coolNotification(data.message, data.status);
          if (data.status == "success") {
            $('#dg').datagrid('reload');
            $('#divForm').find('form')[0].reset();
            $("#tbl-user-body").html("");
          }
        },
        complete: function() {
          block.close();
        }
      });
      return false;
    });
  });

  function doSearchEsc() {
    $.fancybox({
      href: "<?php echo site_url('user/formLookupUser') ?>", type: 'ajax',
      minWidth: 700, autoCenter: false, scrolling: false, autoSize: true, fitToView: true, closeClick: false, openEffect: 'fade', closeEffect: 'fade', afterShow: function() {
        $('#app_no').focus();
      }
    });
  }

  function doSearch() {
    $('#dg').datagrid('load', {
      q: $('#q').val(),
    });
  }
  function doSearchEnter(event) {
    if (event.which == 13 || event.keyCode == 13) {
      $('#dg').datagrid('load', {
        q: $('#q').val(),
      });
    }
    return true;
  }

  function doDelete(group_id) {
    coolConfirmation('Are you sure delete mobile user ?', function() {
      $.ajax({
        url: "<?php echo site_url('user/deleteDataMobileUser') ?>",
        dataType: "json",
        type: 'POST',
        data: {'id': group_id},
        beforeSend: function() {
          block.show('Deleting data');
        },
        success: function(data) {
          if (data.status == "success") {
            $('#dg').datagrid('reload');
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          coolNotification('An error occured, please try again or contact the administrator.', 'error');
        },
        complete: function() {
          block.close();
        }
      });
    });
//    if (confirm('Delete Mobile User?')) {
//    }
  }

  function doEdit(user_id) {
    $.fancybox({
      href: "<?php echo base_url() ?>user/MobileUserManagement/edit/" + user_id, type: 'ajax',
      minWidth: 700, autoCenter: false, scrolling: false, autoSize: true, fitToView: true, closeClick: false, openEffect: 'fade', closeEffect: 'fade', afterShow: function() {
        $('#app_no').focus();
      }
    });
  }
  function notif(data, type_notif) {
    notyfy({
      layout: 'center',
      text: data.hasil,
      type: type_notif, // alert, success, error, warning, information, confirm
      dismissQueue: false,
      modal: true,
      showEffect: false,
      hideEffect: false,
      buttons: [{
          addClass: 'btn btn-primary',
          text: 'OK',
          onClick: function(notyfy) {
            notyfy.close();

            if (typeof functionAfterOKClicked != 'undefined') {
              functionAfterOKClicked();
            }
          }
        }]
    });
  }
  function doAddUsertoTable(rowData) {
    console.log(rowData);
    user_id = rowData.user_id.replace(".", "");
    if ($('.row-' + user_id).length > 0) {
      return false;
    }
    mdn = rowData.mobile_number.replace("+", "");
    mdn = mdn.replace(" ", "");
    mdn = mdn.replace("-", "");

    if (mdn.substring(0, 2) == '08')
      mdn = '628' + mdn.substring(2);



    uindex++;
    rowstr = "<tr class='row-" + user_id + "'>";
    rowstr = rowstr + '<td><a class="btn btn-danger btn-small btn-remove-row" href="javascript:void(0)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>';
    rowstr = rowstr + "<td><input class='sel_user' type='hidden' name='user_id[]' value='" + rowData.user_id + "'>" + rowData.user_id + "</td>";
    rowstr = rowstr + "<td>" + rowData.user_name + "</td>";
    rowstr = rowstr + "<td>" + '<input class="mdn" type="text" name="mdn[]" value="' + mdn + '" class="just-number mdn input-small" autocomplete="off">' + "</td>";
    rowstr = rowstr + "<td>" + '<input class="meid uppercase" type="text" name="meid[]" value="" class="meid input-small" autocomplete="off">' + "</td>";
    rowstr = rowstr + "<td>" + '<input type="text" name="model[]" value="" class="model input-small" autocomplete="off">' + "</td>";
    rowstr = rowstr + "<td>" + '<input type="text" name="manufacture[]" value="" class="manufacture input-small" autocomplete="off">' + "</td>";
    rowstr = rowstr + "</tr>";

    $("#tbl-user-body").append(rowstr);
  }
  function doRemoveUserFromTable(rowData) {
    user_id = rowData.user_id.replace(".", "");
    $(".row-" + user_id).remove();
  }

</script>

<div class="innerLR">

  <div class="widget" id="divForm" style="display:none;">
    <form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
      <div class="widget-head">
        <h4 class="heading glyphicons list" id="h4judul">Mobile User</h4>
      </div>
      <div class="widget-body">

        <div class="row-fluid">
          <div>
            <div class="control-group" id="form_parent">
              <label class="control-label span3" for="mobile_type">Mobile App Type</label>
              <div class="controls">
                <select id="mobile_type" name="mobile_type">
                  <?php
                  $getMobileType = $this->muser->getMobileType();
                  foreach ($getMobileType as $r) {
                    echo '<option value="' . $r->lookup_value . '">' . $r->lookup_desc . '</option>';
                  }
                  ?>
                  <option value="3">PM Mobile + SI Mobile</option>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label span12">User</label>
              <div>
                <div class="controls" id="esc">
                  <button onclick="doSearchEsc();" class="btn" type="button"><i class="icon-plus" style="background-position: -48px 0;"></i> Add user</button>	
                </div>
              </div>
            </div>

            <table id="tbl-user" class="table table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th class="center">&nbsp;</th>
                  <th style="width:180px">User ID <span class="text-error">*</span></th>
                  <th style="width:200px">Name</th>
                  <th class="center">MDN <span class="text-error">*</span></th>
                  <th class="center">MEID <span class="text-error">*</span></th>
                  <th class="center">Handset Model</th>
                  <th class="center">Handset Manufacture</th>
                </tr>
              </thead>
              <tbody id="tbl-user-body">

              </tbody>
            </table>

          </div>


          <div class="form-actions">
            <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
            <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" id="btnCancelAddData"><i></i>Cancel</button>
          </div>
        </div>
      </div>
    </form>
  </div>



  <div class="widget">
    <div class="widget-head">
      <h4 class="heading"><i></i> <?php echo $judul ?></h4>
      <button class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right" id="btnAddData"><i></i> Add New</button>
    </div>

    <div class="widget-body">
      <div id="tb" class="form-horizontal" style="padding: 5px">
        <div class="control-group">
          <label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
          <div class="input-append">
            <input name="q" id="q" type="text" onKeyPress="return doSearchEnter(event)" placeholder="Type text to search ..">
            <button  onclick="doSearch()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
          </div>	
        </div>	
      </div>
      <table style="height:auto" id="dg"
             url="<?php echo base_url() . 'user/loadDataMobileUserManagement'; ?>" 
             title="" nowrap='false' class="jgrid"
             iconCls="icon-save"   toolbar="#tb" striped="true"
             rownumbers="true" pagination="true"
             singleSelect="true" pageSize="15">
        <thead>
          <tr>
            <?php
            $arr = array('user_id' => 'User ID', 'user_name' => 'User Name', 'mobile_name' => 'Mobile App.', 'msisdn' => 'Phone', 'imei' => 'MEID', 'handset_model' => 'Handset Model', 'handset_man' => 'Manufacture', 'version_number' => 'APK Version', 'last_update' => 'Last Update', 'action' => 'Action');
            $width = array('150', '150', '120', '110', '110', '100', '100', '100', '125', '75');
            $i = 0;
            foreach ($arr as $key => $value) {
              if ($key == 'mstatus')
                echo '<th class =' . $key . ' field="' . $key . '" width="' . $width[$i] . '">' . $value . '</th>';
              else
                echo '<th sortable="true" field="' . $key . '" width="' . $width[$i] . '">' . $value . '</th>';
              $i++;
            }
            ?>
          </tr>
        </thead>
      </table>
      <hr>

    </div>
  </div>
</div>