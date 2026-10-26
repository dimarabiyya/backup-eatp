
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
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
<link href="<?php echo base_url(); ?>assets/scripts/jeasyui/easyui.css" type="text/css" rel="stylesheet"></link>
<script src="<?php echo base_url(); ?>assets/scripts/jeasyui/jquery.easyui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/scripts/jeasyui/datagrid-detailview.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/hehehe.js" type="text/javascript"></script>

<div class="innerLR" id="form123">
  <div class="widget">
    <div class="widget-head heading-outer">
      <h4 class="heading glyphicons list"><i></i><?php echo $judul ?></h4>
      <div class="btn-heading-group">
        <a href="#" type="button" class="pull-right btn btn-primary btn-heading btn-icon btn-mini glyphicons circle_plus"  onclick="addChecklistGroup2()"><i></i>Add New</a>&nbsp;
        <a href="<?php echo base_url() ?>preventive/references" class="pull-right btn btn-icon btn-mini btn-heading btn-primary glyphicons unshare"><i></i> Back</a>
      </div>
    </div>
    <div class="widget-body">
      <div id="tb-search" class="form-search">
        <div class="pull-right" style="margin-bottom: 6px">
          <?php echo form_input('q', '', 'id="q" class="input-medium search-query" placeholder="Search..." autocomplete="off"') ?>
          <button class="btn" onclick="searchDataForm()"><i class="icon-search"></i></button>
        </div>
      </div>


      <table id="dyntable" class="table table-bordered table-hover responsive">
        <colgroup>
          <col class="con0" />
          <col class="con1" />
          <col class="con0" />
        </colgroup>
        <thead>
          <tr>
            <th class="head0" width="850px">Description</th>
            <th class="head1" width="50px">Type</th>
            <th class="head0" width="100px" style="text-align:center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 0;
          foreach ($treeGrido as $row) {
            $i++;
            ?>
            <tr id="<?php echo $row['tree_id']; ?>"  class="parent">
              <?php if ($row['tree_type'] == 'grouping') { ?>
                <td><a style="text-decoration: none; color: black;" onclick="getChild(<?php echo $row['tree_id']; ?>,<?php echo $row['group_id']; ?>)"><img style="margin: -4px 0 0 4px" src="<?php echo base_url(); ?>/assets/images/tree_arrow_icons.png"><b><?php echo $row['tree_name']; ?></b></a></td>
              <?php } else { ?>
                <td><a style="text-decoration: none; color: black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $row['tree_name']; ?></b></a></td>
              <?php } ?>
              <td><?php echo $row['tree_type']; ?></td>
              <?php if ($row['tree_type'] == 'grouping') { ?>
                <td style="text-align:center" ><button type="button" class="btn-action glyphicons pencil btn-success" onclick="addChecklistGroup(<?php echo $row['group_id'] . ',\'' . $row['tree_type'] . '\''; ?>)"><i></i></button>&nbsp;<button type="button" class="btn-action glyphicons btn-success plus" onclick="addChecklistGroup2(<?php echo $row['group_id']; ?>)"><i class="icon-plus"></i></button>&nbsp;<button type="button" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData(<?php echo $row['group_id'] . ',\'' . $row['tree_type'] . '\',\'' . $row['tree_name'] . '\''; ?>)"><i></i></button></td>
              <?php } else { ?>
                <td style="text-align:center"><button type="button" class="btn-action glyphicons pencil btn-success" onclick="addChecklistGroup(<?php echo $row['checklist_id'] . ',\'' . $row['tree_type'] . '\''; ?>)"><i></i></button>&nbsp;<button type="button" class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData(<?php echo $row['checklist_id'] . ',\'' . $row['tree_type'] . '\',\'' . $row['tree_name'] . '\''; ?>)"><i></i></button></td>
              <?php } ?>
            </tr>  
            <?php
          }
          ?> 
        </tbody> 
      </table>						
    </div>
  </div>
</div>
<div class="modal"></div>

<script>
          function getChild(parent, group_id) {
            var url = '<?php echo base_url('referensi/pm_checklist/checklisGroup'); ?>';
            var n = $("." + group_id).length;

            if (n == 0) {
              jQuery.ajax({
                type: "POST",
                url: url,
                data: {id: group_id},
                beforeSend: function() {
                  $("html").addClass("loading");
                },
                success: function(res) {
                  $(res).insertAfter('#' + parent);
                },
                error : function (xhr, status, message) {
                  coolNotification('An error occured : '+ message, 'error');
                },
                complete: function() {
                  $("html").removeClass("loading");
                }
              });
            } else {
              $('.' + group_id).remove();
            }
          }

          function lookupSearchResult(tree_id, classx) {
//console.log(classx);
            $('tbody > tr').css("background", "none");
            var myarr = classx.split(" ");
            var parent = '888' + myarr[1];
            var group_id = myarr[1];

//console.log(myarr.length);


// var parent = '888'+myarr[0];
// var group_id = myarr[0];
            if (myarr.length > 1) {

              var url = '<?php echo base_url('referensi/pm_checklist/checklisGroup'); ?>';
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
            } else {
              $("tr[id='" + tree_id + "']").css("background", "red");
            }
            closeFancybox();
          }

          function showHidden(parent, group_id) {

            if ($("." + group_id).is(':visible')) {
              $('.' + group_id).hide();
            } else {
              $('.' + group_id).show();
            }
          }
</script>
<script>
  var Document = {
    param: {
      id: 0,
      tree_type: '',
      tree_name: ''
    },
    deleteData: function(id, tree_type, tree_name) { //edited by andedi 20141111
      coolConfirmation('Are you sure delete "' + tree_name + '" ?', function() {
        Document.param.id = id;
        Document.param.tree_type = tree_type;
        $.ajax({
          url: '<?php echo base_url('referensi/pm_checklist/deleteData') ?>',
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
                color: '#fff',
                'z-index': 99999
              }});
          },
          success: function(data) {
            coolNotification('Data has been deleted successfully.', 'success', function() {
              window.location.href = "<?php echo base_url('referensi/pm_checklist'); ?>";
            });
          },
          error: function(xhr, status, message) {
            coolNotification('An error occured, please call the Administrator.', 'error', function() {

            });
          },
          complete: function() {
            $.unblockUI();
          }
        });
      });
      return false;
//                var checkstr =  confirm('Are you sure want to delete (' + tree_name + ') ?'); 
//                if(checkstr == true){
//                        this.param.id = id; 
//                        this.param.tree_type = tree_type; 
//                        $.ajax({
//                                url: '<?php echo base_url() ?>referensi/pm_checklist/deleteData',
//                                type: 'POST',
//                                dataType: 'json',
//                                data: jQuery.param(Document.param),
//                                success: function() {
//                                    window.location.href = "<?php echo base_url(); ?>referensi/pm_checklist/index";
//                                }
//                        });
//                                
//                }
//                return false;
    }

  }


  function closeFancybox() {
    $.fancybox.close();
  }

  var addChecklistGroup = function(elem1, elem2) { // elem1: shown element, elem2: hidden element
    var url = '<?php echo base_url('referensi/pm_checklist/addeditdata/') ?>';
    $.fancybox({
      href: url,
      type: 'ajax',
      ajax: {data: {id: elem1, tree_type: elem2}}, // e: it used for selected element
      width: 880,
      height: 495,
      scrolling: false,
      autoSize: false,
      fitToView: true,
      closeClick: false,
      openEffect: 'fade',
      closeEffect: 'fade'
    });
  };

  var addChecklistGroup2 = function(elem1) {
    var url = '<?php echo base_url('referensi/pm_checklist/addData/') ?>';
    $.fancybox({
      href: url,
      type: 'ajax',
      ajax: {data: {id: elem1}}, // e: it used for selected element
      width: 880,
      height: 495,
      scrolling: false,
      autoSize: false,
      fitToView: false,
      closeClick: false,
      openEffect: 'fade',
      closeEffect: 'fade'
    });
  };

  var searchDataForm = function() {
    var q = $('#q').val();
    var url = '<?php echo base_url('referensi/pm_checklist/searchDataForm/') ?>';
    $.fancybox({
      href: url,
      type: 'ajax',
      ajax: {data: {q: q}},
      width: 1280,
      height: 495,
      scrolling: false,
      autoSize: false,
      fitToView: true,
      closeClick: false,
      openEffect: 'fade',
      closeEffect: 'fade'
    });
  };
</script>