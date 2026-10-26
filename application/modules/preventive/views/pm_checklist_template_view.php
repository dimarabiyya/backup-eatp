<script src="<?php echo base_url('assets/js/hehehe.js'); ?>"></script>
<style>
  .modal {
    display:    none;
    z-index:    1000;
    top:        0%;
    left:       0%;
    margin-left: 0%;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
      url('<?php echo base_url(); ?>assets/images/loading4.gif') 
      50% 50% 
      no-repeat;
  }

  body.loading {
    overflow: hidden;   
  }

  body.loading 	.modal {
    display: block;
  }

  html.loading {
    overflow: hidden;   
  }

  html.loading 	.modal {
    display: block;
  }
</style>

<style>
  .modal {
    /*left:40%;
    width:800px;*/
  }
  .folder {
    background: url('<?php echo base_url(); ?>assets/scripts/jeasyui/images/tree_icons.png') no-repeat -240px 0;
  }
</style>

<style type="text/css">
  .datagrid-body .datagrid-cell{
    word-wrap: break-word;
    white-space: normal;
  }
</style>
<style type="text/css">
  #report div.arrow { background:transparent url(<?php echo base_url() ?>assets/images/arrows.png) no-repeat scroll 0px -16px; width:16px; height:16px; display:block;}
  #report div.up { background-position:0px 0px;}
</style>

<script>

</script>

<div class="innerLR">
  <div class="widget">
    <div class="widget-head heading-outer">
      <h4 class="heading glyphicons list"><i></i>Checklist Template</h4>
      <div class="btn-heading-group">
        <a href="#" class="pull-right btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus" onclick="addEditTypeForm()"><i></i>Add New</a> &nbsp;
        <a href="<?php echo base_url('preventive/references') ?>" class="pull-right btn btn-icon btn-mini btn-primary btn-heading unshare glyphicons"><i></i>Back</a>
      </div>
    </div>
    <div class="widget-body">

      <div class="control-group" id="form_name">
        <div class="pull-right" style="margin: 1px">
          <?php echo form_input('q', '', 'id="q" class="input-medium search-query" placeholder="Search..." autocomplete="off"') ?>
          <button class="btn" onclick="searchDataForm()"><i class="icon-search"></i></button>
        </div>

        <div class="pull-right">
          <select class="span3" name="option_id" id ="option_id">
            <option value="">- Choose Template Option -</option>
            <?php
            foreach ($combo_option as $value) {

              echo'<option value=' . $value['option_id'] . '>' . $value['option_label'] . '</option>';
            }
            ?>
          </select>
        </div>

      </div>
    </div>

    <table id="dyntable" class="table table-bordered responsive table-hover">
      <colgroup>
        <col class="con0" />
        <col class="con1" />
        <col class="con0" />
      </colgroup>
      <thead>
        <tr>
          <th class="head0">Description</th>
          <th class="head1" width="100px">Type</th>
          <th class="head0" width="150px" style="text-align:center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 0;
        foreach ($getOption as $row) {
          $i++;
          ?>
          <tr id="<?php echo 'parent' . $row->option_id; ?>"  class="parent">
            <td><a style="text-decoration: none; color: black;" onclick="getChild(<?php echo $row->option_id . ',\'' . $row->count . '\''; ?>)"><img style="margin: -4px 0 0 4px" src="<?php echo base_url(); ?>/assets/images/tree_arrow_icons.png"><b><?php echo $row->option_label . ' (' . $row->count . ')'; ?></b></a></td>
            <td>Template</td>
            <td style="text-align: center;">
              <button title="Edit Checklist Template" type="button" class="btn-action glyphicons pencil btn-success" onclick="addEditTypeForm(<?php echo $row->option_id; ?>)"><i></i></button>
              <button title="Edit Checklist Items" type="button" class="btn-action glyphicons check btn-success" onclick="Document.editData(<?php echo $row->option_id; ?>)"><i></i></button>
              <a title="Download Checklist Template" href="<?php $row->option_label = rawurlencode($row->option_label); echo base_url("export/pm_checklist_template/{$row->option_id}/{$row->option_label}"); ?>" class="btn-action glyphicons download btn-success"><i></i></a>
              <button title="Delete Checklist Template" type="button" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData(<?php echo $row->option_id . ',\'' . $row->option_label . '\''; ?>)"><i></i></button></td>
          </tr>  
          <?php
        }
        ?> 
      </tbody> 
    </table>						
    <hr>

  </div>
</div>
</div>
<div class="modal"></div>

<script>
  function getChild(parent, count) {
    if (count == 0) {
      coolNotification('Sorry, no checklist item(s).', 'error', function() {
        return false;
      });
    } else {
      var url = '<?php echo base_url('preventive/pm_checklist_template/checklisGroup'); ?>';
      var n = $(".parent" + parent).length;

      if (n == 0) {
        $.ajax({
          type: "POST",
          url: url,
          data: {id: parent},
          beforeSend: function() {
            $("html").addClass("loading");
          },
          success: function(res) {
            $("html").removeClass("loading");
            $(res).insertAfter('#parent' + parent);
          }
        });

      } else {
        $('.parent' + parent).remove();
      }
    }
  }

  function showHidden(parent, group_id) {
    if ($("." + group_id).is(':visible')) {
      $('.' + group_id).hide();

    } else {
      $('.' + group_id).show();
    }
  }

  function closeFancybox() {
    $.fancybox.close();
  }

  var searchDataForm = function() {
    var q = $('#q').val();
    var option_id = $('#option_id').val();
    var url = '<?php echo base_url('preventive/pm_checklist_template/searchDataForm/') ?>';
    if (option_id != '') {
      $.fancybox({
        href: url,
        type: 'ajax',
        ajax: {
          data:
                  {q: q,
                    option_id: option_id
                  }},
        width: 1280,
        height: 495,
        scrolling: false,
        autoSize: false,
        fitToView: true,
        closeClick: false,
        openEffect: 'fade',
        closeEffect: 'fade'
      });
    } else {
      alert('Template Option Required');
    }
  };

  var addEditTypeForm = function(id) {
    var url = '<?php echo base_url('preventive/pm_checklist_template/addedittype') ?>';
    $.fancybox({
      href: url,
      type: 'ajax',
      ajax: {data: {option_id: id}},
      width: 640,
      height: 240,
      scrolling: false,
      autoSize: false,
      fitToView: true,
      closeClick: false,
      openEffect: 'fade',
      closeEffect: 'fade'
    });

  };


  function lookupSearchResult(tree_id, classx) {
    var myarr = classx.split(" ");
    var parent = myarr[0];
    var group_id = myarr[0].substring(6);
    var url = '<?php echo base_url('preventive/pm_checklist_template/checklisGroup'); ?>';
    var n = $("." + group_id).length;

    jQuery.ajax({
      type: "POST",
      url: url,
      data: {id: group_id},
      success: function(res) {

        $('tbody > tr:not(.parent)').remove();
        $(res).insertAfter('#' + parent);

        $('tbody > tr:not(.parent)').hide();

        var arrLength = myarr.length - 1;
        var text = '';
        for (i = 0; i < myarr.length; i++) {
          text += myarr[i] + ' ';
          text_str = text.substring(0, text.length - 1);
          $('tr[class="' + text_str + '"]').show();
        }


        $("tr[id='" + tree_id + "']").css("background", "red");

      }
    });
    closeFancybox();
  }

  var Document = {
    param: {
      option_id: 0
    },
    editData: function(option_id) {
      window.location.href = "<?php echo base_url('preventive/pm_checklist_template/addEditData'); ?>/" + option_id;
    },
    deleteData: function(option_id, option_label) {
      //andedi 20141126
      this.param.option_id = option_id;
      coolConfirmation('<h3>Confirmation</h3>Are you sure delete "' + option_label + '" ?', function() {
        $.ajax({
          url: '<?php echo base_url('preventive/pm_checklist_template/deleteData') ?>',
          type: 'POST',
          dataType: 'json',
          data: jQuery.param(Document.param),
          beforeSend: function() {
            $.blockUI({message: '<img width="16px" src="<?php echo base_url('file/images/loading-snake.GIF'); ?>"> Deleting data, please wait...', css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
              }});
          },
          success: function(data) {
            if (data.status == 'success') {
              coolNotification('<h3>Success</h3>Data has been deleted successfully.', data.status, function() {
                window.location.href = '<?php echo base_url('preventive/pm_checklist_template'); ?>';
              });
            } else {
              coolNotification('<h3>Error</h3>An error occured : ' + data.hasil, 'error');
            }
          },
          error: function(xhr, status, msg) {
            coolNotification('<h3>Error</h3>An error occured, please call the administrator.', 'error');
          },
          complete: function() {
            $.unblockUI();
          }
        });
      });

      return false;
    }
  }

</script>