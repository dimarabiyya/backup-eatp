<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/jquery.noty.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/layouts/center.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/themes/default.js"></script>
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('assets/js/number_format.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css"/>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/themes/default.css"/>
<link href="<?php echo base_url('assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>

<style type="text/css">
.widget-body{
  border-bottom: solid 1px #c1c1c1;
}
.widget-body:last-child{
  border-bottom: none;
}
.btn-group>.btn:hover, .btn-group>.btn:focus, .btn-group>.btn:active, .btn-group>.btn.active {
  z-index: 0;
}
</style>
<script type="text/javascript">
$.blockUI({
  message: '<img src="<?php echo base_url('file/images/loading-snake.GIF'); ?>" width="16px"> Processing, please wait...',
  css: {
    border: 'none',
    padding: '15px',
    backgroundColor: 'black',
    color: 'white',
    opacity: .5,
    //      '-webkit-border-radius': '10px',
    //      '-moz-border-radius': '10px'
  }
});
</script>

<div class="innerLR">
  <div class="row-fluid">
    <div class="span12">
      <form class="form-horizontal" method="post" id="schedule_form">
        <div class="widget">
          <div class="widget-head heading-outer">
            <h4 class="heading glyphicons list"><i></i> <?php echo $title; ?></h4>
          </div>
          <div class="widget-body">

            <div class="row-fluid" id="choose-po">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="site">Choose PO No.</label>
                  <div class="controls">
                    <input type="text" name="po" value="<?php echo (($schedule)?$schedule->po_no:''); ?>" id="po" class="input-medium uneditable" onfocus="lookUpPO()">
                    <input type="hidden" id="input_po_id" name="input_po_id"  value="<?php echo (($schedule)?$schedule->po_id:''); ?>">
                    <button type="button" class="btn" onclick="lookUpPO()">...</button>
                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid" id="choose-site">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="site">Choose Site Link</label>
                  <div class="controls">
                    <select name="site_link" id="site_link" onChange="showDetailSite()">
                      <?php if($schedule):?>
                        <option value="<?php echo $schedule->link_id?>"><?php echo $schedule->link ;?></option>
                      <?php endif;?>
                    </select>
                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid" id="site-detail" style="display: none;">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="site">Site Detail</label>
                  <div class="controls" id="content-site">

                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="pic">PIC</label>
                  <div class="controls">
                    <input type="text" name="user" id="user"  value="<?php echo (($schedule)?$schedule->pic_name:'');?>" class="input-medium uneditable" onfocus="lookUpUser()">
                    <input type="text" id="input_user_id" name="pic"  value="<?php echo (($schedule)?$schedule->pic_id:'');?>">
                    <button type="button" class="btn" onclick="lookUpUser()">...</button>
                  </div>
                  <div class="separator bottom"></div>
                </div>
              </div>
            </div>

            <div class="row-fluid">
              <div class="span12">
                <div class="control-group">
                  <label class="control-label" for="sow">SOW</label>
                  <div class="controls">
                    <select name="pm-visit-type" id="pm-visit-type">
                      <option value=""></option>
                      <?php foreach($sow as $pkey):?>
                        <option value="<?php echo $pkey->option_id ?>" data-group="<?php echo $pkey->option_value ?>" locked="<?php echo $pkey->locked; ?>" <?php echo (($schedule && $schedule->visit_type == $pkey->option_id)?'selected="selected"':'')?>>
                          <?php echo $pkey->option_label ?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="priority">Priority</label>
                <div class="controls">
                  <div class="btn-group" data-toggle="buttons-radio" id="pm-priority">
                    <?php foreach($priority as $key=>$val):?>
                      <button type="button" class="btn <?php echo ($schedule && ($schedule->priority == $val)?'active btn-primary':'')?>"><?php echo $val?></button>
                    <?php endforeach;?>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="remarks">Remark</label>
                    <div class="controls">
                      <textarea name="remark" id="remark" style="width:90%;height: 50px;"><?php echo (($schedule)?$schedule->remark:'')?></textarea>
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                </div>
              </div>

              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="poNo">Schedule Date</label>
                    <div class="controls">
                      <input type="text" name="schedule_date" id="scheduleDate" class="input-small datepicker" placeholder="DD-MMM-YYYY" value="<?php echo (($schedule)?(date('d-M-Y',strtotime($schedule->visit_date))):'')?>"/>
                    </div>
                    <div class="separator bottom"></div>
                  </div>
                </div>
              </div>

              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="sow">Approval Template</label>

                    <div class="controls">
                      <?php $js = 'id="approval_template" onChange=""'; ?>

                      <?php echo form_dropdown('approval', $approval,'', $js); ?>
                        <button id="get_recp" type="button" class="btn btn-primary"> Get Recipient </button>
                    </div>

                    <div class="separator bottom"></div> 
                  </div>
                </div>
              </div>

              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="sow">List Receiver Email Notification</label>
                    
                    <div class="span6">
                        <div id="divku" style="float:left,margin-left:200px;"> </div>
                    </div>
                   
                    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <tbody>
                    </tbody>
                    </table>
                            
                    <div class="separator bottom"></div> 
                  </div>
                </div>
              </div>

              <div class="row-fluid">
                <div class="control-group">
                  <label class="control-label">
                    <img rel="tooltip" data-placement="right" src="<?php echo base_url().'assets/images/exclamation.png' ?>" title="At least a checklist item must be selected" style="display:none" id="checklist-item-exclamation">
                    Checklist Items
                  </label>
                  <div class="controls">
                    <!-- <ul id="tt" class="easyui-tree" data-options="url:'<?php echo base_url() ?>pm/schedule/generateTree/',method:'get',checkbox:true,onlyLeafCheck:true "></ul> -->
                    <div class="control-group">
                      <button type="button" class="btn" id="btn-custom">Custom </button>
                      <button type="button" class="btn disabled" id="btn-checkall" disabled="disabled">Check All / Uncheck All</button>
                      <div style="margin-bottom: 10px;"></div>

                      <div id="content_checklist">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-actions">
                  <div class="pull-left" id="jumlah-checklist">No checklist item is selected, please pick some</div>
                  <button type="button" class="btn btn-primary btn-icon glyphicons circle_ok" id="save_schedule"><i></i> Save</button>
                  <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo base_url("/schedule")?>'"><i></i>Cancel</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script>
    var dateToday = new Date();
    $('.datepicker').datepicker({
      minDate: dateToday
    });

    //dateFormat.init();
    /*Config if Edit*/
    var sch_id = "<?php echo (($schedule)?$schedule->visit_id:0);?>";
    /*Config if Edit*/
    if(sch_id == 0){
      $('#choose-site').hide();
    }
    
    //if button save is click
    $('#save_schedule').click(function(){
      saveSchedule();
      
       var str = $( "form" ).serialize();
       console.log(str);
       /*
          var users = $('input[name="rec_info"]').serialize();
            $.ajax({
                url:"<?php echo base_url('schedule/cek_aja');?>",
                type:"POST",
                data:{users:users},
                success:function(konten){
                    console.log(konten);
                }
            });
        
        */
      
        
         //console.log(users);
        
    });

    /*sample Check*/
    </script>

    <script>
 

    $("#get_recp").on("click",function(){
        //alert('ok');
        var isi = $("#approval_template").val();
        var pos = isi.toLowerCase();
        var res = pos.replace(" ","_");
         
        var user_id = $("#input_user_id").val();
        //alert(res);
        
        $.getJSON("<?php echo base_url('schedule/get_recipient_notif'); ?>/" + res, function (data) {
            //console.log(data);    
           
           
            
            var items = [];
            $('#example tbody').empty();
          
            var no = 0;
            $.each(data.data, function (key, val) {
                no++;
                console.log(val);
                items.push("<tr>");
                items.push("<td><input type='checkbox' name='rec_info[]' class='rec_info' value='"+val.email+"'>" + val.user_id + "</td>");
                items.push("</tr>");
            });
            $("<tbody/>", {html: items.join("")}).appendTo("#example");
            
            
        });
       
 
    });
    function showDetailSite(){
      var link_id_int = $("#site_link").val();
      var url = "<?php echo site_url('/schedule/getInfoLink?link=') ?>";
      var url = url+link_id_int;
      console.log(url);
      $.get(url,function(data){
        var obj_site = jQuery.parseJSON(data);
        console.log(obj_site);
        var content = "Address Site FE : "+obj_site.address_fe+"<br/>"+
        "Coordinate : (<a href='#'>"+ obj_site.latitude_fe+","+obj_site.longitude_fe+"</a>)<br/>"+
        "Address Site NE : "+obj_site.address_ne+"<br/>"+
        "Coordinate : (<a href='#'>"+ obj_site.latitude_ne+","+obj_site.longitude_ne+"</a>)";

        $("#content-site").empty();
        $("#content-site").append(content);
        $("#site-detail").show();
      });
    }
    </script>
    <script>
    function load_checklist(sch_id){
      /*General Type Empty Default*/

      var url = "<?php echo site_url('/schedule/checklist?sch=') ?>";
      var url = url+""+sch_id;
      $.get(url,function(data){
        $("#content_checklist").empty();
        $("#content_checklist").append(data);
        $.unblockUI();
      });
    }
    /*call default when add*/
    /*If Edit Mode When Sign Checklist appropriate with*/
    load_checklist(sch_id);

    </script>

    <script>
    /*Function to Show PIC List*/
    function lookUpUser(){
      var url = "<?php echo site_url('/schedule/lookup_pic') ?>";

      $.fancybox({
        href: url,
        type: 'ajax',
        ajax: { data: { cmd: 'getData' } }, // e: it used for selected element
        width: 600,
        scrolling: false,
        autoSize: true,
        fitToView: true,
        closeClick: false,
        openEffect: 'fade',
        closeEffect: 'fade',
        afterShow: function() {
          $('.fancybox-wrap').draggable({handle:".widget-head"});

          document.getElementById('btnDataSelectedOK').onclick = function() {
            var row = $('#gridUserPIC').datagrid('getSelected');

            if (row != null) {
              console.log("User Id :"+row.user_id);
              $('#user').val(row.user_name);
              $('#input_user_id').val(row.user_id);
              $("#divku").html("<table class='table table-striped table-bordered' cellspacing='0' width='100%'> <tbody> <tr> <td> <input type='checkbox' class='rec_info' name='rec_info[]' value='"+row.email+"'> "+row.user_id+"</td> </tr></tbody> </table>");
            }

            $.fancybox.close();
          };
        }
      });
    }
    </script>
    <script>
    /*Function to Show Site Link*/
    function lookUpLink(){
      var url = "<?php echo site_url('/schedule/lookup_site_link') ?>";

      $.fancybox({
        href: url,
        type: 'ajax',
        ajax: { data: { cmd: 'getData' } }, // e: it used for selected element
        width: 600,
        scrolling: false,
        autoSize: true,
        fitToView: true,
        closeClick: false,
        openEffect: 'fade',
        closeEffect: 'fade',
        afterShow: function() {
          $('.fancybox-wrap').draggable({handle:".widget-head"});

          document.getElementById('btnDataSelectedOK').onclick = function() {
            var row = $('#gridSiteLink').datagrid('getSelected');

            if (row != null) {
              console.log("link :"+row.link_id_int);
              $('#sitelink').val(row.link_id);
              $('#input_link_id').val(row.link_id_int);
            }

            $.fancybox.close();
          };
        }
      });
    }
    </script>
    <script>
    function loadDataLink(po){
      var url = "<?php echo site_url('/schedule/lookup_site_link/getlinkpo?po=') ?>";
      url = url +""+po;

      /*Set Empty*/
      $('#site_link').empty();
      $.get(url,function(data){
        var obj_link = jQuery.parseJSON(data);
        /*Choose Site LInk*/
        $('#site_link').append($('<option>', {
          value: "",
          text : "- Choose Link -"
        }));
        $.each( obj_link, function( key, item ) {

          $('#site_link').append($('<option>', {
            value: item.link_id_int,
            text : item.link_id
          }));
        });
      });
      /*Show Site Link*/
      $('#choose-site').show();
    }
    </script>
    <script>



    /*Function to Show Site Link*/
    function lookUpPO(){
      var url = "<?php echo site_url('/schedule/lookup_po') ?>";

      $.fancybox({
        href: url,
        type: 'ajax',
        ajax: { data: { cmd: 'getData' } }, // e: it used for selected element
        width: 600,
        scrolling: false,
        autoSize: true,
        fitToView: true,
        closeClick: false,
        openEffect: 'fade',
        closeEffect: 'fade',
        afterShow: function() {
          $('.fancybox-wrap').draggable({handle:".widget-head"});

          document.getElementById('btnDataSelectedOK').onclick = function() {
            var row = $('#gridPo').datagrid('getSelected');

            if (row != null) {
              console.log("po_id :"+row.po_id);
              $('#po').val(row.po_no);
              $('#input_po_id').val(row.po_id);
              loadDataLink(row.po_id);
            }

            $.fancybox.close();
          };
        }
      });
    }
    /*Save Form Schedule*/
    function saveSchedule(){
      var save_url = "<?php echo $action_url?>";
      console.log(save_url);
      
       

    
      var checkValues = [];
      $('input[name=checklist_item]:checked').each(function() {
        checkValues.push($(this).val());
      });
      /*Uploading Process Save Form*/
      $.blockUI({
        message: '<img src="<?php echo base_url('file/images/loading-snake.GIF'); ?>" width="16px"> Uploading Data, please wait...',
        css: {
          border: 'none',
          padding: '15px',
          backgroundColor: 'black',
          color: 'white',
          opacity: .5,
        }
      });
      /*End Process*/
      $.post(save_url,{
        "po_id":$("#input_po_id").val(),
        "link_id":$("#site_link").val(),
        "pic_id":$("#input_user_id").val(),
        "visit_date":$("#scheduleDate").val(),
        "visit_type":$("#pm-visit-type").val(),
        "approval":$("#approval_template").val(),
        "remark":$("#remark").val(),
        "priority":$('#pm-priority > button.active').html(),
        "checklist_item":checkValues
      },function(data){
          console.log(data);
                  /*var obj_msg = jQuery.parseJSON(data);
        var redirect_url = "<?php echo base_url("/schedule")?>";
        if(obj_msg.status == "success"){
          window.location.href =  redirect_url ;
        }else{
          coolNotification('<ul style="text-align:left">' + obj_msg.message + '</ul>', 'error');
        }
        $.unblockUI();*/
      });
    }


    </script>
