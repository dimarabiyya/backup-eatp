<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/icon.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/datagrid-detailview.js"></script>
<script src="<?php echo base_url() ?>assets/js/overlay.js"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>
<style type="text/css">
  .datagrid-body .datagrid-cell{
    /*word-wrap: break-word;*/
    /*white-space: normal;*/
  }
  .datagrid-header-rownumber { width:50px; }
  .datagrid-cell-rownumber { width:50px; }

  #gritter-notice-wrapper {
    position:fixed;		
    top: 38%;
    left: 38%;
    width:301px;
    z-index:9999;
  }
  .mobile_form {display: none}
  .ck_all{
    margin-left: 50px;
    border-bottom: 1px solid #C9C9C9;
    margin-bottom: 10px    
  }
  .input-prepend .add-on {
    background: #efefef !important;
  }

  .uppercase {text-transform:uppercase;}
</style>

<script>
// masukkan kode javascript berikutnya disini
  var Document = {
    param: {
      dataperpage: 10, // jumlah data per halaman
      query: '',
      curpage: 0,
      numpage: 0,
      id: 0,
      cops: 0,
      ccom: 0,
      cnet: 0
    },
    url: '<?php echo base_url() ?>user/loadData',
    urlRepTo: '<?php echo base_url() ?>user/user_repto',
    loadReportingTo: function(grp, idnya) {
      $.ajax({
        url: Document.urlRepTo,
        type: 'POST',
        dataType: 'json',
        data: {"group_id": grp},
        success: function(d) {
          if (d == null) {
            $('#reportingto').empty();
          } else {
            var t = '', dt = {};
            for (var i = 0; i < d.data.length; i++) {
              dt = d.data[i];
              var select = "";
              if (idnya == dt.user_id)
                select = " SELECTED ";
              t += '<option value="' + dt.user_id + '" ' + select + '>' + dt.user_name + '</option>';
            }
            $('#reportingto').html(t);
          }
        }
      });
    }
  }
  
  $(document).ready(function() {
	//Document.search();
    //Document.loadReportingTo(1,1);

    //validasi input user_id
    $('.keyup-characters').keyup(function() {
      //$('span.error-keyup-2').remove();
      name = $(this).val();
      characterReg = /^[a-zA-Z0-9.]*$/;
      n = name.search(/\./i);
      if (!characterReg.test(name)) {
        alert('No special characters allowed.');
        //$(this).after('<span class="error error-keyup-2"> No special characters allowed.</span>');
        name = name.replace(/[^a-zA-Z 0-9.]+/g, '');
        name = name.replace(' ', '');
      } else if (n === 0) {
        alert('Dot character in front not allowed.');
        //$(this).after('<span class="error error-keyup-2"> Dot character in front not allowed.</span>');
        name = name.replace(/\./i, '');
      }
      $(this).val(name);
//            $('span.error-keyup-2').remove();
//            var inputVal = $(this).val();
//            var n = inputVal.search(/\./i); 
//            var characterReg = /^[a-zA-Z0-9.]*$/;
//            if(!characterReg.test(inputVal)) {
//                $(this).after('<span class="error error-keyup-2"> No special characters allowed.</span>');
//            } else if (n===0) {
//                $(this).after('<span class="error error-keyup-2"> Dot character in front not allowed.</span>');
//            }
    });

    $('#dg').datagrid({
      pageSize: 15,
      pageList: [10, 15, 20, 30, 100, 200, 500],
//      fitColumns: true,
//      nowrap: false,
//      singleSelect: true
    });

    $("#user_id").focusout(function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>user/cekUserID",
        data: {"user_id": $(this).val()},
        dataType: "json",
        beforeSend: function() {
          //$('.alert-error,.alert-success').hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
          //alert(errorThrown);
        },
        success: function(data) {
          if (data.status == "ERROR") {
            $('.muted').html('User ID is Exist');
          } else {
            $('.muted').html('');
          }
        }
      });
      if ($("#internaluser").attr('checked') == 'checked') {
        $("#email").val($(this).val() + "@alita.id");
      }
    });

    $("#internaluser").change(function() {
      if ($(this).attr('checked') == 'checked') {
        $("#email").val($("#user_id").val() + "@alita.id");
        // $("#mcpass").prop("checked", false);
        // $("#mcpass").attr("disabled", "disabled");
      } else {
        $("#mcpass").removeAttr('disabled')
      }
    });

    $("#email").focusout(function() {
      user_id_check = $('#user_id').val() + "@alita.id";
      email_check = $(this).val();
      if ($("#internaluser").attr('checked') == 'checked') {
        if (user_id_check != email_check) {
          //if (confirm("If you are an internal user, email and user_id must be the same. Do you want to use your user_id as email?")) {
            //$("#email").val($("#user_id").val() + "@alita.id");
          //}
        }
      }

    });

    $("#hape").focusout(function() {
      hp = $(this).val();
      if (hp.substring(0, 2) == '08')
        format_hp = '628' + hp.substring(2);
      else if (hp.substring(0, 2) == '88')
        format_hp = '62' + hp;
      else
        format_hp = hp;

      format_hp = format_hp.replace(/\s/g, "");

      $(this).val(format_hp);
    });

    $("#mobile_type").change(function() {
      mobile_app = $(this).val();
      if (mobile_app != "-1") {
        $(".mobile_form").css("display", "block");
      } else {
        $(".mobile_form").css("display", "none");
      }
    })

    $("#btnAddData").live("click", function() {
      $("#divForm").show();
      $("#h4judul").html("Add New User");
      // $("#mobile_tabs").attr("class","");
      // $("#region_tabs").attr("class","active");
      // $("#tab-1").attr("class","active");

      $('#add-form')[0].reset();
      $("#user_id").removeAttr('disabled');
      $("#internaluser").prop('checked', true);
      $('body,html').animate({scrollTop: 0}, 800);

      $("#status").val("1");
      $("#id").val("");

      //loop group id--------------------------------------
      $("#tblGroup input[type=checkbox]").each(function() {
        $(this).attr('checked', false);
      });
      //loop div--------------------------------------
      $("#tblDiv input[type=checkbox]").each(function() {
        $(this).attr('checked', false);
      });

      //loop region--------------------------------------
      $("#tblRegion input[type=checkbox]").each(function() {
        $(this).attr('checked', false);
      });

      $('select[name=main_division_id] option').removeAttr('selected');
	  
	  var image = '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAYEBAQFBAYFBQYJBgUGCQsIBgYICwwKCgsKCgwQDAwMDAwMEAwODxAPDgwTExQUExMcGxsbHCAgICAgICAgICD/2wBDAQcHBw0MDRgQEBgaFREVGiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICD/wAARCABkAGQDAREAAhEBAxEB/8QAGwABAAMBAQEBAAAAAAAAAAAAAAMEBQIBBgj/xAA0EAABAwIDBgQEBQUAAAAAAAABAAIDBBESITEFEyJBUXEUMmGBM0JSoRUjJMHwQ2KCkbH/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8A/QiAgICAgICAgICAgICAgICAgICAgIOxDKRiDHEdbFBwgICAgICCeCkkmaXAhrG6uKCTc0TPiTYz0YgVkELYYpYRZrtboKiAgsUgY17XvGMfQMz3sg0zNM8ERQnPQu4UFCTZdS1uIWd1AQU0BAQEBBqsjhjc+K+GKWMOGI80FVtPSNNnSGQ/TGEE74pZYBDHCWMGYc8oPI9kH+o/2CC1Hs6lZ8uI+qCw2NjRZrQOyDpAQYm0o8FSTycLoKqAgIO4YnSyCNupQbDKCAAY7yEc3H9kFhrGNFmgAeiD1BWNTJIS2nZitrIfKgYdoa42drFB6yqc14ZUN3bjo75T7oLCD1BnbXj4GSdDY+6DLQEBBd2c3DIyT6iWfa6DYQEFWrLnYIGmxlPEf7RqghrarwwEEIsba9EGf4qpvfeOv3QaNLO2sidFKOIfy6Ceje4xlj/PGcJP/EFhBXro8dK8c9R7IMJAQEFyld+mf1icJB+6DYBuLjmg9QVZcq2EnQtcB3QZ20mkVbidHWIQVUF7ZLTvy7kBmgu0mc9Q4aF1v9ILSDwi4t1QfOyswSOZ0NkHKAgs7PP5xjOkjS1Bq0bsVO2+reE+yCdBBUwmWPhye04mH1QQHcVbN3LwTN5c/ZBF+D55ycPZBNijgbuKYYpXfy5QWKeEQxBmp5nqUEqDxBhVpaaqQt0uggQEHUT8EjXdDdBtUxtNMzkSHt/yQWUBBFLTxS+dt/Xmgi8BF9T7dMRQTRwxRCzGhoQcvqqdmReL9NT9kHHi3u+FE53qeEfdBUrKudrcOJrSflbmbd0GcgICAg1IZcLIag5sw7uT06FBfD2kXBuOqCN9VAzJzxfoMyg48U93w4XEdXcI+6CJ9RKPPNHF6N4igrPqqbmZJj6mwQR+Pc34UbY+wzQQvqJ3+Z5KCNAQEBAQWaSsMF2kYo3atQSuqqDVsJudRewQR+Pe3KJjY+wQQvqJ5PO8lBGgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICD/2Q==';
	 
      document.querySelector('img[id=imageSignature]').src = 'data:image/jpeg;base64,' + image;
    });

    $("#btnCancelAddData").live("click", function() {
      $("#divForm").hide();
      $("#btnAddData").show();
      $('#divForm').find('form')[0].reset();
    });
	
    $('#add-form').live("submit", function(e) {
      var data = $(e.target).serialize();
	  
	  var image = document.querySelector('img[id=imageSignature]').src;
	  
	  image = image.replace("data:image/jpeg;base64,","");
	  image = image.replace("data:image/png;base64,", "");
	  image = image.replace("data:image/gif;base64,", "");
	  image = image.replace(" ", "");
	  
	  data += '&imageSignature=' + image;
	  
	  $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>user/saveData",
        data: data,
        dataType: "json",
        beforeSend: function() {
          $.blockUI({message: 'Processing, please wait...', css: {
              border: 'none',
              padding: '15px',
              backgroundColor: '#000',
              '-webkit-border-radius': '10px',
              '-moz-border-radius': '10px',
              opacity: .5,
              color: '#fff'
            }});
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
          $.unblockUI();
        },
        success: function(data) {
          $.unblockUI();
          if (data.status == "OK") {
            $('#dg').datagrid('load');
            $("#divForm").hide();
            $("#btnAddData").show();
            $('#divForm').find('form')[0].reset();
            alert("Data saved successfully.");
          } else {
            alert(data.hasil);
          }
        }
      });
      return false;
    });




    $("#allMCE").live("click", function() {
      var cek = this.checked;
      $("#tblDiv input[type=checkbox]").each(function() {
        $(this).attr('checked', cek);
      });
    });

    $("#allGroup").live("click", function() {
      var cek = this.checked;
      $("#tblGroup input[type=checkbox]").each(function() {
        $(this).attr('checked', cek);
      });
    });

    $("#allRegion").live("click", function() {
      var cek = this.checked;
      $("#tblRegion input[type=checkbox]").each(function() {
        $(this).attr('checked', cek);
      });
    });

  });

  function doSearch() {
    $('#dg').datagrid('load', {
      q: $('#q').val(),
      region: $("#cmb_region").val(),
      role: $("#cmb_role").val(),
    });
  }
  function doSearchEnter(event) {
    if (event.which == 13 || event.keyCode == 13) {
      $('#dg').datagrid('load', {
        q: $('#q').val(),
        region: $("#cmb_region").val(),
        role: $("#cmb_role").val(),
      });
    }
    return true;
  }
  
  //#############################################################################
  function doEdit(user_id) {

    $("#divForm").show();
    $("#h4judul").html("Edit Data User");
    $('body,html').animate({scrollTop: 0}, 800);

    $("#btnAddData").hide();
    $('select[name=main_division_id] option').removeAttr('selected');

    // $("#mobile_tabs").attr("class","disabled");
    //  $("#region_tabs").attr("class","active");
    //  $("#tab-1").attr("class","active");

    $.ajax({
      type: "POST",
      url: "<?php echo base_url() ?>user/EditUser",
      data: {'id': user_id},
      dataType: "json",
      beforeSend: function() {
        $.blockUI({message: 'Processing, please wait...', css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
          }});
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
        $.unblockUI();
      },
      success: function(data) {
        $.unblockUI();
        if (data.status == "OK") {

          $("#id").val(data.hasil.user_id);
          $("#user_id").val(data.hasil.user_id);
          $("#user_id").attr('disabled', 'disabled');
          $("#nama").val(data.hasil.user_name);
          $("#status").val(data.hasil.status);
          $("#reportingto").val(data.hasil.reporting_to);
          $("#hape").val(data.hasil.mobile_number);
          $("#email").val(data.hasil.email);
          $('select[name=main_division_id] option[value=' + data.hasil.main_division_id + ']').attr('selected', 'selected');

          $("#handset_man").val(data.hasil.handset_man);
          $("#handset_model").val(data.hasil.handset_model);
          $("#imei").val(data.hasil.imei);
		  
		  var image = '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAYEBAQFBAYFBQYJBgUGCQsIBgYICwwKCgsKCgwQDAwMDAwMEAwODxAPDgwTExQUExMcGxsbHCAgICAgICAgICD/2wBDAQcHBw0MDRgQEBgaFREVGiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICD/wAARCABkAGQDAREAAhEBAxEB/8QAGwABAAMBAQEBAAAAAAAAAAAAAAMEBQIBBgj/xAA0EAABAwIDBgQEBQUAAAAAAAABAAIDBBESITEFEyJBUXEUMmGBM0JSoRUjJMHwQ2KCkbH/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8A/QiAgICAgICAgICAgICAgICAgICAgIOxDKRiDHEdbFBwgICAgICCeCkkmaXAhrG6uKCTc0TPiTYz0YgVkELYYpYRZrtboKiAgsUgY17XvGMfQMz3sg0zNM8ERQnPQu4UFCTZdS1uIWd1AQU0BAQEBBqsjhjc+K+GKWMOGI80FVtPSNNnSGQ/TGEE74pZYBDHCWMGYc8oPI9kH+o/2CC1Hs6lZ8uI+qCw2NjRZrQOyDpAQYm0o8FSTycLoKqAgIO4YnSyCNupQbDKCAAY7yEc3H9kFhrGNFmgAeiD1BWNTJIS2nZitrIfKgYdoa42drFB6yqc14ZUN3bjo75T7oLCD1BnbXj4GSdDY+6DLQEBBd2c3DIyT6iWfa6DYQEFWrLnYIGmxlPEf7RqghrarwwEEIsba9EGf4qpvfeOv3QaNLO2sidFKOIfy6Ceje4xlj/PGcJP/EFhBXro8dK8c9R7IMJAQEFyld+mf1icJB+6DYBuLjmg9QVZcq2EnQtcB3QZ20mkVbidHWIQVUF7ZLTvy7kBmgu0mc9Q4aF1v9ILSDwi4t1QfOyswSOZ0NkHKAgs7PP5xjOkjS1Bq0bsVO2+reE+yCdBBUwmWPhye04mH1QQHcVbN3LwTN5c/ZBF+D55ycPZBNijgbuKYYpXfy5QWKeEQxBmp5nqUEqDxBhVpaaqQt0uggQEHUT8EjXdDdBtUxtNMzkSHt/yQWUBBFLTxS+dt/Xmgi8BF9T7dMRQTRwxRCzGhoQcvqqdmReL9NT9kHHi3u+FE53qeEfdBUrKudrcOJrSflbmbd0GcgICAg1IZcLIag5sw7uT06FBfD2kXBuOqCN9VAzJzxfoMyg48U93w4XEdXcI+6CJ9RKPPNHF6N4igrPqqbmZJj6mwQR+Pc34UbY+wzQQvqJ3+Z5KCNAQEBAQWaSsMF2kYo3atQSuqqDVsJudRewQR+Pe3KJjY+wQQvqJ5PO8lBGgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICD/2Q==';
		  
		  if (data.hasil.image_signature != '') {
			image = data.hasil.image_signature;
		  }
		  
		  $("#imageSignature").attr('src', 'data:image/jpeg;base64,' + image);

          if (data.hasil.mobile_app_type == "2, 1" || data.hasil.mobile_app_type == "1, 2")
            $("#mobile_type").val("3");
          else if (data.hasil.mobile_app_type == null)
            $("#mobile_type").val("-1");
          else
            $("#mobile_type").val(data.hasil.mobile_app_type);

          mobile_app = $("#mobile_type").val();
          if (mobile_app != "-1") {
            $(".mobile_form").css("display", "block");
          } else {
            $(".mobile_form").css("display", "none");
          }


          //loop group id--------------------------------------
          $("#tblGroup input[type=checkbox]").each(function() {
            $(this).attr('checked', false);
          });
          //loop div--------------------------------------
          $("#tblDiv input[type=checkbox]").each(function() {
            $(this).attr('checked', false);
          });

          //loop region--------------------------------------
          $("#tblRegion input[type=checkbox]").each(function() {
            $(this).attr('checked', false);
          });


          $("#mcpass").prop('checked', (data.hasil.must_change_password == "1" ? true : false));

          $("#dummyuser").prop('checked', (data.hasil.dummy_user == "1" ? true : false));
          $("#internaluser").prop('checked', (data.hasil.internal_user == "1" ? true : false));

          //loop group id--------------------------------------
          var string = data.hasil.user_group_id_all, arr = string.split(', '), i;
          $("#tblGroup input[type=checkbox]").each(function() {
            if ($.inArray($(this).attr('gid'), arr) != -1) {
              $(this).attr('checked', true);
            } else {
              $(this).attr('checked', false);
            }
          });
          //loop div--------------------------------------
          var string = data.hasil.div_id, arr = string.split(', '), i;
          $("#tblDiv input[type=checkbox]").each(function() {
            if ($.inArray($(this).attr('did'), arr) != -1) {
              $(this).attr('checked', true);
            } else {
              $(this).attr('checked', false);
            }
          });

          //loop region--------------------------------------
          var string = data.hasil.region_id, arr = string.split(', '), i;
          $("#tblRegion input[type=checkbox]").each(function() {
            if ($.inArray($(this).attr('rid'), arr) != -1) {
              $(this).attr('checked', true);
            } else {
              $(this).attr('checked', false);
            }
          });
        }
      }
    });

  }

  function doDelete(user_id) {
    var checkstr = confirm('This action cannot be undone. Are you sure you want to delete?');
    if (checkstr == true) {

      $.ajax({
        url: '<?php echo base_url() ?>user/deleteData',
        type: 'POST',
        dataType: 'json',
        data: {'id': user_id},
        success: function() {
          $('#dg').datagrid('load');
        }
      });


    }
    return false;
  }

  function doBounceUser(user_id) {
    var checkstr = confirm('User ' + user_id + ' akan dipaksa LogOut. \n Anda yakin ingin melanjutkan ?');
    if (checkstr == true) {

      $.ajax({
        url: '<?php echo base_url() ?>user/bounceUser',
        type: 'POST',
        dataType: 'json',
        data: {'id': user_id},
        success: function() {
          alert("Dalam jangka waktu 3 menit User " + user_id + " akan dipaksa LogOut.");
        }
      });

    }
    return false;
  }

  function doResetPassword(user_id) {
    var checkstr = confirm('Password akan di-reset kembali ke awal. \n Anda yakin ingin me-reset password ?');
    if (checkstr == true) {

      $.ajax({
        url: '<?php echo base_url() ?>user/resetPassword',
        type: 'POST',
        dataType: 'json',
        data: {'id': user_id},
        success: function() {
          alert("Password berhasil di-reset");
          $('#dg').datagrid('load');
        }
      });
    }
    return false;

  }
  //#############################################################################

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
  
  function previewFile() {
	var preview = document.querySelector('img[id=imageSignature]');
	var file    = document.querySelector('input[type=file]').files[0];
	var reader  = new FileReader();

	reader.addEventListener("load", function () {
		preview.src = reader.result;console.log(reader.result);
	}, false);

	if (file) {
		reader.readAsDataURL(file);
	}
 }
 </script>

<div class="separator bottom"></div>

<div class="innerLR">
  <div class="widget" id="divForm" style="display:none;">

    <div class="widget-head">
      <?php
      if ($this->mmenu->cekPermission($menu, 'menu_add')) {
        echo'<h4 class="heading" id="h4judul">Add New User</h4>';
      }
      ?>
    </div>
    <div class="widget-body">
      <form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form">
        <div class="row-fluid">
          <div class="row-fluid">
            <div class="span12">
              <input type='hidden' name='id' id='id' />
              <div class="control-group" id="form_user_id">
                <label class="control-label" for="user_id">User ID</label>
                <div class="controls"><input style="text-transform: lowercase;"
                                             type="text" class="span6 keyup-characters" id="user_id" name="user_id" />
                  <p class="muted" style="color:red;"></p>
                </div>

              </div>
              <div class="control-group" id="form_nama">
                <label class="control-label" for="nama">Name</label>
                <div class="controls"><input type="text" class="span6" id="nama" name="nama" /></div>
              </div>


              <div class="control-group" id="form_status">
                <label class="control-label" for="status">Status</label>
                <div class="controls">
                  <select name="status" id="status">
                    <?php
                    $user_status = $this->muser->getUserComboStatus();
                    foreach ($user_status as $key => $value) {
                      echo '<option value="' . $value['member_value'] . '">' . $value['member_display'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="control-group" id="form_hape">
                <label class="control-label" for="hape">Mobile Number</label>
                <div class="controls"><input type="text" class="span6" id="hape" name="hape" /></div>
              </div>
              <div class="control-group" id="form_email">
                <label class="control-label" for="email">E-Mail</label>
                <div class="controls"><input type="text" class="span6" id="email" name="email" /></div>
              </div>
              <div class="control-group" id="form_main_division_id">
                <label class="control-label" for="main_division_id">Main Group</label>
                <div class="controls"><?php echo form_dropdown('main_division_id', $main_division_opt, '', 'class="span6"') ?></div>
              </div>
              <!-- <div class="control-group" id="form_dummyuser">
                <label class="control-label span3">&nbsp;</label>
                <label class="checkbox" for="dummyuser" style='padding-left:170px;'>
                  <input type="checkbox" name="dummyuser" id="dummyuser" value="1" />
                  Dummy User
                </label>
              </div>
              <div class="control-group" id="form_internaluser">
                <label class="control-label span3">&nbsp;</label>
                <label class="checkbox" for="internaluser" style='padding-left:170px;'>
                  <input type="checkbox" name="internaluser" id="internaluser" value="1" />
                  Internal User
                </label>
              </div> -->
              <div class="control-group" id="form_mcpass">
                <label class="control-label span3">&nbsp;</label>
                <label class="checkbox" for="mcpass" style="padding-left:155px;">
                  <!-- <input disabled="disabled" type="checkbox" name="mcpass" id="mcpass" value="1" /> -->
                  <input type="checkbox" name="mcpass" id="mcpass" value="1" />
                  Change password on the next logon & Create Security Question
                </label>
              </div>
			  
			  <div class="control-group">
                <label class="control-label">Signature</label>
                <div class="controls">
					<input type="file" name="imageSignature" onchange="previewFile()" /> <br />
					<img id="imageSignature" src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAYEBAQFBAYFBQYJBgUGCQsIBgYICwwKCgsKCgwQDAwMDAwMEAwODxAPDgwTExQUExMcGxsbHCAgICAgICAgICD/2wBDAQcHBw0MDRgQEBgaFREVGiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICD/wAARCABkAGQDAREAAhEBAxEB/8QAGwABAAMBAQEBAAAAAAAAAAAAAAMEBQIBBgj/xAA0EAABAwIDBgQEBQUAAAAAAAABAAIDBBESITEFEyJBUXEUMmGBM0JSoRUjJMHwQ2KCkbH/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8A/QiAgICAgICAgICAgICAgICAgICAgIOxDKRiDHEdbFBwgICAgICCeCkkmaXAhrG6uKCTc0TPiTYz0YgVkELYYpYRZrtboKiAgsUgY17XvGMfQMz3sg0zNM8ERQnPQu4UFCTZdS1uIWd1AQU0BAQEBBqsjhjc+K+GKWMOGI80FVtPSNNnSGQ/TGEE74pZYBDHCWMGYc8oPI9kH+o/2CC1Hs6lZ8uI+qCw2NjRZrQOyDpAQYm0o8FSTycLoKqAgIO4YnSyCNupQbDKCAAY7yEc3H9kFhrGNFmgAeiD1BWNTJIS2nZitrIfKgYdoa42drFB6yqc14ZUN3bjo75T7oLCD1BnbXj4GSdDY+6DLQEBBd2c3DIyT6iWfa6DYQEFWrLnYIGmxlPEf7RqghrarwwEEIsba9EGf4qpvfeOv3QaNLO2sidFKOIfy6Ceje4xlj/PGcJP/EFhBXro8dK8c9R7IMJAQEFyld+mf1icJB+6DYBuLjmg9QVZcq2EnQtcB3QZ20mkVbidHWIQVUF7ZLTvy7kBmgu0mc9Q4aF1v9ILSDwi4t1QfOyswSOZ0NkHKAgs7PP5xjOkjS1Bq0bsVO2+reE+yCdBBUwmWPhye04mH1QQHcVbN3LwTN5c/ZBF+D55ycPZBNijgbuKYYpXfy5QWKeEQxBmp5nqUEqDxBhVpaaqQt0uggQEHUT8EjXdDdBtUxtNMzkSHt/yQWUBBFLTxS+dt/Xmgi8BF9T7dMRQTRwxRCzGhoQcvqqdmReL9NT9kHHi3u+FE53qeEfdBUrKudrcOJrSflbmbd0GcgICAg1IZcLIag5sw7uT06FBfD2kXBuOqCN9VAzJzxfoMyg48U93w4XEdXcI+6CJ9RKPPNHF6N4igrPqqbmZJj6mwQR+Pc34UbY+wzQQvqJ3+Z5KCNAQEBAQWaSsMF2kYo3atQSuqqDVsJudRewQR+Pe3KJjY+wQQvqJ5PO8lBGgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICD/2Q==" class="img-polaroid" style="width:200px; margin:5px 0 0" />
				</div>
              </div>
			  
              <div class="widget widget-tabs">
                <div class="widget-head">
                  <ul>
                    <li  class="active"  id="region_tabs"><a class="glyphicons file" href="#tab-1" data-toggle="tab"><i></i>Region</a></li>
                    <li><a class="glyphicons group" href="#tab-2" data-toggle="tab"><i></i>User Group</a></li>
                    <li><a class="glyphicons keys" href="#tab-3" data-toggle="tab"><i></i>User Role</a></li>
                    <li id="mobile_tabs"><a class="glyphicons iphone" href="#tab-4" data-toggle="tab"><i></i>Mobile Access</a></li>
                  </ul>
                </div>
                <div class="widget-body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab-1">                                                
                      <div class="control-group" id="form_region">
                        <!--<label class="control-label" for="user_id2">Region</label>-->
                        <div class="ck_all">
                          <label class="checkbox" for="allRegion">
                            <input type="checkbox" id="allRegion" name="cMCE" value="1" />
                            <b>Select All</b>
                          </label>
                        </div>                                                            
                        <div class="controls" id="tblRegion" style="margin-left: 50px;">

                          <?php
                          //User Role mengambil data di group 
                          $region = $this->muser->getRegionByUserRegion();
                          $klp = ceil(count($region) / 3);
                          ?>   
                          <div class="span4">
                            <?php
                            // $k=2;
                            $k = 1;
                            foreach ($region as $r) {
                              ?>
                              <label class="checkbox" for="region[<?php echo $r->region_id ?>]">
                                <input type="checkbox" name="region[<?php echo $r->region_id ?>]" id="region[<?php echo $r->region_id ?>]" rid="<?php echo $r->region_id ?>" class="ids" value="1" />
                                <?php echo $r->region_name ?> 
                              </label>
                              <?php
                              if (($k % $klp) == 0) {
                                echo'</div><div class="span4">';
                              }
                              $k++;
                            }
                            ?>

                          </div>

                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="tab-2">                                                
                      <div class="control-group" id="form_usergroup">
                        <div class="ck_all">
                          <label class="checkbox" for="allMCE">
                            <input type="checkbox" id="allMCE" name="cMCE" value="1" />
                            <b>Select All</b>
                          </label>
                        </div>                                                            
                        <div class="controls" id="tblDiv" style="margin-left: 50px;">
                          <?php
                          //User Role mengambil data di group
                          $getUserGroup = $this->muser->getUserDivision();
                          $klp = ceil(count($getUserGroup) / 3);
                          ?>
                          <div class="span4">
                            <?php
                            $k = 1;
                            foreach ($getUserGroup as $r) {
                              ?>
                              <label class="checkbox" for="userdiv[<?php echo $r->user_div_id ?>]">
                                <input type="checkbox" name="userdiv[<?php echo $r->user_div_id ?>]" id="userdiv[<?php echo $r->user_div_id ?>]" did="<?php echo $r->user_div_id ?>" value="1" />
                                <?php echo $r->user_div_name ?> 
                              </label>
                              <?php
                              if (($k % $klp) == 0) {
                                echo'</div><div class="span4">';
                              }
                              $k++;
                            }
                            ?>

                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="tab-3">
                      <div class="control-group" id="form_userrole">
                        <div class="ck_all">
                          <label class="checkbox" for="allGroup">
                            <input type="checkbox" id="allGroup" name="cMCE" value="1" />
                            <b>Select All</b>
                          </label>
                        </div>

                        <div class="controls" id="tblGroup"  style="margin-left: 50px;">

                          <?php
                          //User Role mengambil data di group 
                          $getUserRole = $this->muser->getUserGroup();
                          $klp = ceil(count($getUserRole) / 3);
                          ?>

                          <div class="span4">
                            <?php
                            $k = 1;
                            foreach ($getUserRole as $r) {
                              ?>
                              <label class="checkbox" for="usergroup[<?php echo $r->user_group_id ?>]">
                                <input type="checkbox" class="namagroup" name="usergroup[<?php echo $r->user_group_id ?>]" id="usergroup[<?php echo $r->user_group_id ?>]" gid="<?php echo $r->user_group_id ?>" value="1" />
                                <?php echo $r->user_group_name ?> 
                              </label>
                              <?php
                              if (($k % $klp) == 0) {
                                echo'</div><div class="span4">';
                              }
                              $k++;
                            }
                            ?>

                          </div>

                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="tab-4">

                      <div class="control-group" id="form_mobileaccess">
                        <label class="control-label" for="mobile_type">Mobile User?</label>
                        <div class="controls">
                          <select id="mobile_type" name="mobile_type">
                            <option value="-1">No</option>
                            <option value="1">Yes</option>
                            <?php
                            /*
                            $getMobileType = $this->muser->getMobileType();
                            foreach ($getMobileType as $r) {
                              echo '<option value="' . $r->lookup_value . '">' . $r->lookup_desc . '</option>';
                            }
                            */
                            ?>
                          </select>
                        </div>
                      </div>      
                      <div class="control-group mobile_form" id="form_imei">
                        <label class="control-label" for="imei">MEID</label>
                        <div class="controls"><input class="span6 uppercase" id="imei" maxlength="50" name="imei" type="text" value="" /></div>
                      </div>

                      <div class="control-group mobile_form" id="form_handset_model">
                        <label class="control-label" for="handset_model">Handset Model</label>
                        <div class="controls"><input class="span6" id="handset_model" maxlength="50" name="handset_model" type="text" value="" /></div>
                      </div>

                      <div class="control-group mobile_form" id="form_handset_man">
                        <label class="control-label" for="handset_man">Handset Manufacture</label>
                        <div class="controls"><input class="span6" id="handset_man" maxlength="50" name="handset_man" type="text" value="" /></div>
                      </div>




                    </div>                                        
                  </div>
                </div>
              </div>
              <!--
              <div class="control-group" id="form_reportingto">
                      <label class="control-label" for="reportingto">Reporting To</label>
                      <div class="controls">
                              <select name="reportingto" id="reportingto"></select>
                      </div>
              </div>
              -->





              <div class="form-actions">
                <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
                <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" id="btnCancelAddData"><i></i>Cancel</button>
              </div>  
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>


  <div class="widget">
    <div class="widget-head heading-outer">
      <h4 class="heading"><i></i> <?php echo $judul ?></h4>
      <?php
      if ($this->mmenu->cekPermission($menu, 'menu_add')) {
        echo'<button class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right" id="btnAddData"><i></i> Add New User</button>';
      }
      ?>

    </div>
    <div class="widget-body">
      <div id="tb" style="padding: 10px 10px 0px 10px; height:auto">
        <div class="input-prepend">
          <span class="add-on">Region</span>
          <select name="region" id="cmb_region" style="width: 130px;">
            <option value="ALL">ALL</option>
            <?php
            foreach ($region as $tt) {
              echo '<option value="' . $tt->region_id . '">' . $tt->region_name . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="input-prepend">
          <span class="add-on">User Role</span>
          <select name="role" id="cmb_role" style="width: 130px;">
            <option value="ALL">ALL</option>
            <?php
            foreach ($getUserRole as $tt) {
              echo '<option value="' . $tt->user_group_id . '">' . $tt->user_group_name . '</option>';
            }
            ?>
          </select>
        </div>
        <button class="btn" onclick="doSearch()" style="margin-bottom: 10px">Apply</button>

        <div class="input-append pull-right">
          <input class="" name="q" id="q" type="text" placeholder="Type text to search .." onKeyPress="return doSearchEnter(event)"/> 
          <button class="btn" onclick="doSearch()" >Search</button>
        </div>

      </div>
      <table style="height:auto" id="dg"
             url="<?php echo site_url('user/loadData'); ?>" 
             title="" nowrap='true'
             iconCls="icon-save"   toolbar="#tb" striped="true"
             rownumbers="true" pagination="true"
             singleSelect="true" fitColumns="false">
        <thead>
          <tr>
            <?php
            echo "
              <th field='user_name'>User Name</th>
              <th field='user_id'>User ID</th>
              <th field='user_group_name' width='200px'>User Role</th>
              <th field='mobile_number'>Mobile Number</th>
              <th field='mobile_name'>Mobile User</th>
              <th field='imei'>MEID</th>
              <th field='email'>Email</th>
              <th field='status_name'>Status</th>
              <th field='date_created'>Date Created</th>
              <th field='login_status' align='center'>Status Login</th>
              <th field='last_login'>Last Login</th>
              <th field='action'>Action</th>
              ";
//            $arr = array('user_name' => 'User Name',
//                'user_id' => 'User ID',
//                'user_group_name' => 'User Role',
//                'mobile_number' => 'Mobile Number',
//                'mobile_name' => 'Mobile App.',
//                'imei' => 'MEID',
//                'email' => 'Email',
//                'status_name' => 'Status',
//                'date_created' => 'Date Created',
//                'last_login' => 'Last Login',
//                'action' => 'Action');
//            //$width=array('100','90','100','70','90','35','75');
//            $i = 0;
//            foreach ($arr as $key => $value) {
//              if ($key == 'mstatus')
//              //echo '<th class ='.$key.' field="'.$key.'" width="'.$width[$i].'">'.$value.'</th>';
//                echo '<th class =' . $key . ' field="' . $key . '">' . $value . '</th>';
//              else
//              //echo '<th sortable="true" field="'.$key.'" width="'.$width[$i].'">'.$value.'</th>';
//              if ($key == 'date_created') {
//                echo '<th class =' . $key . ' field="' . $key . '">Date Created</th>'; //date_format(field, 'd-M-Y H:i');
//              } else {
//                echo '<th sortable="true" field="' . $key . '">' . $value . '</th>';
//              }
//              $i++;
//            }
            ?>
          </tr>
        </thead>
      </table>

    </div>
  </div>
</div>