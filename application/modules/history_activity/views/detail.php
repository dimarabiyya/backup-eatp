<link href="<?php echo base_url('assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/jqueryvalidation/jquery.validate.js"></script>



<style>
  label > input[type="radio"] {
    margin: -4px 0 0 0;
  }
  table.list-checklist{
    font-size: 10pt;
  }
  
</style>


<div class="separator bottom"></div>
 
<div class="innerLR">
<div align="right"> 
<a href="javascript::void(0);" class="btn btn-primary" onclick="gethistory();"> History  </a>
<br>
&nbsp;
</div>
</div>
 
<div id="views_history">
<div class="innerLR">
<!--history-->
   <div class="widget">
    <div class="widget-head"><h4 class="heading">History</h4> </div>
    <div class="widget-body">
        <div class="row-fluid">
        <br>
        <button id="submits" class="btn btn-primary"> Close </button> 
        <br>
        &nbsp;
    <h3 align="center"> History Visit </h3>
     <table class="table table-bordered table-condensed">
              <tbody>
                <tr>
                <td> Field Engineering </td>
                <td> Site Type </td>
                <td> Date </td>
                <td> Lattitude </td>
                <td> Longitude </td>
                <td> Distance </td>
                <td> Remark </td>
                </tr>
                
              </tbody>
            </table> 

             &nbsp;
    <h3 align="center"> History Status Approval </h3>
     <table class="table table-bordered table-condensed">
              <tbody>
                <tr>
                <td> Username </td>
                <td> Date </td>
                <td> Status </td>
                <td> Remark </td>
                </tr>
               
                <?php foreach($approval_users as $item):?>
                <tr>
                <td> <?php echo $item->user_id?> </td>
                <td> <?php echo $item->updated_on?> </td>
                <td> 
                
                <!--<a href="javascript::void(0);" onClick='lookupreject("<?php echo base_url('visitsummary/detailreject/'.$this->uri->segment(3).'/'.$item->user_id); ?>");'>
                  <?php echo $item->desc_status?> 
                </a>-->
                <a href="javascript::void(0);" onclick="lookupreject('<?php echo $this->uri->segment(3); ?>','<?php echo $item->user_id; ?>');">
                  <?php echo $item->desc_status?> 
                </a>

               </td>
                <td> <?php echo $item->remark?> </td>
                </tr>
                 <?php endforeach;?>
              </tbody>
            </table> 
        </div>
    </div>
  </div>
  <!--history-->
</div>
</div>

<div id="views_detail">
<div class="innerLR">

<div id="cekdetail"></div>
  <div class="widget">
    <div class="widget-head"><h4 class="heading"><?php echo $title; ?></h4></div>
    <div class="widget-body">

        <div class="row-fluid">
          <div class="span6">
            <table class="table table-bordered table-condensed">
              <tbody>
                <tr>
                  <td style="background: #f5f5f5" width="130px">Site LINK</td>
                  <td><?php echo $siteinfo->site_link; ?>|<?php echo $siteinfo->visit_id;?></td>
                </tr>
                <tr>
                  <td style="background: #f5f5f5" width="130px">PO NO.</td>
                  <td><?php echo $siteinfo->po_no; ?></td>
                </tr>
                <tr>
                  <td style="background: #f5f5f5">Region</td>
                  <td><?php echo $siteinfo->region_name; ?></td>
                </tr>             
                <tr>
                  <td style="background: #f5f5f5">SOW</td>
                  <td><?php echo $siteinfo->visit_type_desc; ?></td>
                </tr>
                <tr>
                  <td style="background: #f5f5f5">EWO/NBWO No.</td>
                  <td><?php echo $siteinfo->ewo_nbwo_no; ?></td>
                </tr>
                 <tr>
                  <td style="background: #f5f5f5">Priority</td>
                  <td>
                  <?php $flag ="default" ;?>
                  <?php if(strtolower($siteinfo->priority) == "urgent" ){ $flag = "important";}?>
                  <?php if(strtolower($siteinfo->priority) == "normal" ){ $flag = "warning";}?>
                    <span class="label label-<?php echo $flag;?>">
                      <?php echo $siteinfo->priority; ?>  
                    </span>
                  </td>
                </tr>
                <?php 
                /*if(count($approval_users)):?>
                <tr>
                  <td style="background: #f5f5f5">List User Will Approval</td>
                  <td>
                    <ol>
                      <?php foreach($approval_users as $item):?>
                          <li>
                            <?php echo $item->user_id?>
                            <span class="">|&nbsp;  <?php echo $item->desc_status?></span>
                          </li>
                      <?php endforeach;?>
                    </ol>
                  </td>
                </tr>
              <?php endif; */?>
              </tbody>
            </table>            
          </div>
          <div class="span6">
            <table class="table table-bordered table-condensed">
              <tbody>
                <tr>
                  <td style="background: #f5f5f5">Assigned PIC</td>
                  <td><?php echo $siteinfo->pic ?></td>
                </tr>
                <tr>
                  <td style="background: #f5f5f5">FOP Name/Contact</td>
                  <td><?php echo $siteinfo->fop_hs_name; ?>&nbsp;/&nbsp;<?php echo $siteinfo->fop_hs_contact; ?></td>
                </tr>
                <tr>
                  <td style="background: #f5f5f5" width="130px">Visit Plan</td>
                  <td><?php echo date("d-M-Y",strtotime($siteinfo->visit_date)); ?></td>
                </tr>
                <tr>
                  <td style="background: #f5f5f5">Visit Status</td>
                  <td><?php echo $siteinfo->desc_status ?></td>
                </tr>
                <tr>
                  <td style="background: #f5f5f5">ATP Desktop by</td>
                  <td><?php echo $siteinfo->usr_pm_desktop_by ?></td>
                </tr>
                <tr>
                  <td style="background: #f5f5f5">ATP Desktop complete date</td>
                  <td><?php echo date("d-M-Y",strtotime($siteinfo->pm_desktop_on)); ?></td>
                </tr>
              </tbody>
            </table>            
          </div>
        </div>
    </div>
  </div>

  


  <?php $sc_code = 0;?>
  <?php if($siteinfo->is_code_show):?>
      <?php $sc_code = 1; ?>
  <?php else:?>
  <button class="btn btn-success" style="margin-bottom: 10px;" id="btn_show_request_code"><i class="icon-eye-close"></i>&nbsp;Generate Security Code</button>
  <div class="widget" id="widget_request_code" style="display:none">
    <div class="widget-head"><h4 class="heading "><i class="icon-fire"></i>&nbsp;Justification</h4>  
    <button class="pull-right btn btn-sucess" id="btn_cancel_generate_code"><i class="icon-remove-sign"></i>&nbsp;Cancel</button>
    <button class="pull-right btn btn-sucess" id="btn_generate_code"><i class="icon-lock"></i>&nbsp;Get Security Code</button>
    </div>
    <div class="widget-body">
        <textarea class="form-control" name="remark" id="request_remark" style="width:95%;height: 60px;" placeholder="write some reason when you generate this security code"></textarea>
    </div>
  </div>
<?php endif;?>
  <div class="widget" id="widget_security_code" style="display:<?php echo (($sc_code)?'':'none');?>;">
    <div class="widget-head"><h4 class="heading">Generate Security Code</h4></div>
    <div class="widget-body">
        <table class="table table-bordered">
          <tr>
              <th style="text-align: center;">Security Code Check IN</th>
              <th style="text-align: center;">Security Code Check Out</th>
          </tr>
          <tr>
            <td style="text-align: center;"><h1 style="padding-top: 10px;" id="check_in_code"><?php echo $siteinfo->security_code_checkin?></h1></td>
            <td style="text-align: center;"><h1 style="padding-top: 10px;" id="check_out_code"><?php echo $siteinfo->security_code_checkout?></h1></td>
          </tr>
        </table>

    </div>
  </div>

  <div class="widget">
    <div class="widget-body">
      <form class="form-horizontal">          
                <div class="widget">
                 
                  <div class="widget-body">
                    <div class="row-fluid"> 
                        <div class="span12">
                            <div class="pull-right">                            
                             <button class="pull-right btn btn-error <?php echo $is_enable_approval;?>" type="button" onclick="rejectAll()" style="margin-right: 10px;margin-left: 10px;">Reject All</button>
                             <button class="pull-right btn btn-error <?php echo $is_enable_approval;?>" type="button" onclick="approveAll()" style="margin-right: 10px;margin-left: 10px;">Approve All</button>
                            </div>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="accordion pm-visit-type-options" id="accordion">

                <?php 
                //var_dump($checklistsgrouping);
                  function render($properties,$is_enable_approval="",$user_id="") {
                    function sub_render($parent, $iterasi,$is_enable_approval="",$user_id="") {
                      echo '<div class="accordion-group checklist-group-header">';
                      echo '<div class="accordion-heading">';
                     
                      echo '<a class="pull-right accordion-toggle checklist-total" onclick="check_approve('.$parent['group_id'].')" data-toggle="tooltip" title="" data-placement= bottom data-original-title="Click to Approve/Reject All ">Approve / Reject</a>';
                      if($iterasi) 
                        { echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#group_id_'.$parent['group_id'].'">'; }
                      else
                        { echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="" href="#group_id_'.$parent['group_id'].'">'; }
                      echo  $parent['group_name'];                      
                       echo '</a>';
                      echo '</div>';
                      echo '<div id="group_id_'.$parent['group_id'].'" class="accordion-body in collapse">';
                      if($iterasi) 
                        { echo '<div class="accordion-inner" id="accordion_level_'.$parent['group_id'].'">'; }
                      else 
                        { echo '<div class="accordion-inner">'; }

                      if( isset($parent['sub_group']) ) {
                        $_2nd = 1;
                        foreach ($parent['sub_group'] as $gg) {
                          sub_render($gg, false,$is_enable_approval,$user_id);
                        }
                      }
                      if(isset($parent['checklists'])) {
                        echo generate_header_table();
                        $i=1;
                        foreach ($parent['checklists'] as $cc) {
                          //echo $cc['checklist_name']."<br/>";
                          echo generate_view($cc,$i,$is_enable_approval,$user_id);
                          $i++;
                        }
                        echo generate_footer_table();
                      }
                      echo '</div>';
                      echo '</div>';
                      echo '</div>';
                    }

                    

                    $_1st = 1;
                    foreach ($properties as $gg) {
                      sub_render($gg, $_1st++,$is_enable_approval,$user_id);
                    }
                  }

                  render($checklistsgrouping,$is_enable_approval,$this->session->userdata('user_id'));

                ?>

                </div>            
              <div class="widget" id="widget_summary">
                <div class="widget-head"><h4 class="heading">Summary</h4></div>
                <div class="widget-body">
                    <div class="row-fluid" style="margin-bottom: 10px;"> 
                      <div class="span12">
                          <div class="span2">Acceptance Status:</div>
                          <!--<div class="span10">                           
                                <input type="radio" name="acceptance-status" value="1" <?php echo (($approval_summary && $approval_summary->acceptance_status==1 ? 'checked="checked"':''))?> > Pass<br/>
                                <input type="radio" name="acceptance-status" value="2" <?php echo (($approval_summary && $approval_summary->acceptance_status==2 ? 'checked="checked"':''))?>> Pass with pending item<br/>
                                <input type="radio" name="acceptance-status" value="3" <?php echo (($approval_summary && $approval_summary->acceptance_status==3 ? 'checked="checked"':''))?>> Reject<br/>
                          </div>-->
                          <div class="span10">                           
                                <input type="radio" name="acceptance-status" id="get_check_approve" value="1" <?php echo (($approval_summary && $approval_summary->acceptance_status==1 ? 'checked="checked"':''))?> > Pass <br/>
                                <input type="radio" name="acceptance-status" id="get_check_cancel" value="0" <?php echo (($approval_summary && $approval_summary->acceptance_status==0 ? 'checked="checked"':''))?>> Reject<br/>
                          </div>

                      </div>
                    </div>
                    <div class="row-fluid" style="margin-bottom: 10px;"> 
                      <div class="span12">
                          <p>Pending Items</p>
                          <p>Please List down all pending items and its timeline to be completed</p>

                          <div class="pull-right" style="margin-bottom: 10px;">
                            <button type="button" class="btn btn-default" onclick="addPendingItem()" <?php echo $is_enable_approval;?> ><i class="icon-plus-sign"></i>&nbsp;&nbsp;Add Pending Item</button>
                          </div>
       
                          <table id="tbl-pending-item" class="table table-bordered" style="width:100%">
                            <thead>
                              <tr>
                                <th style="width:40px" class="center">&nbsp;</th>
                                <th >Item<span class="text-error">*</span></th>
                                <th >Tanggal Completion</th>
                                <th >PIC<span class="text-error">*</span></th>
                                <th class="center">Contact No.</th>                                
                              </tr>
                            </thead>
                            <tbody id="tbl_body">
                            <?php $current_row=1;?>
                            <?php foreach($pending_items as $item):?>
                            <tr class="row-<?php echo $current_row?>">
                              <td >
                                <a class="btn btn-danger btn-small btn-remove-row" id="<?php echo $current_row?>" href="javascript:void(0)" title="Remove row" <?php echo (($item->created_by == $this->session->userdata("user_id"))?'':'disabled');?> data-pending="<?php echo $this->core->encode($item->pending_item_id)?>" onClick="removeRow(this)">
                                  <i class="icon-minus-sign icon-white"></i>
                                </a>
                              </td>
                              <td>
                                <input name="pending_id[]" type="hidden" value="<?php echo $this->core->encode($item->pending_item_id)?>" />
                                <input  style="width:94%" class="form-control" type="text" name="itemdesc[]" value="<?php echo $item->item_descr?>" <?php echo (($item->created_by == $this->session->userdata("user_id"))?'':'disabled');?> />
                              </td>
                               <td>
                                <input type="text" name="date_completion[]" class="input-small date" placeholder="DD-MMM-YYYY" value="<?php echo date('d-M-Y',strtotime($item->target_completion))?>" <?php echo (($item->created_by == $this->session->userdata("user_id"))?'':'disabled');?>/>
                              </td>
                              <td>
                                <input  class="form-control" style="width:94%" type="text" name="pic[]" value="<?php echo $item->pic?> " <?php echo (($item->created_by == $this->session->userdata("user_id"))?'':'disabled');?>/>
                              </td>   
                              <td>
                                <input  class="form-control" style="width:94%" type="text" name="contactno[]" value="<?php echo $item->contact_no?> " <?php echo (($item->created_by == $this->session->userdata("user_id"))?'':'disabled');?>/>
                              </td>                              
                            </tr>
                            <?php $current_row++; ?>
                            <?php endforeach;?>
                            </tbody>
                          </table>
                      </div>
                    </div>                            
                </div>
            </div>
            <div class="widget">
              <div class="widget-head"><h4 class="heading">Notes</h4></div>
              <div class="widget-body">
                <div class="row-fluid"> 
                    <div class="span12">
                      <div class="control-group">
                           <textarea class="form-control" id="remark_mass_approval" placeholder="fill remark for approval all checklist answer" style="width: 98%;height: 60px;"><?php echo (($approval_summary)?$approval_summary->remark:'')?></textarea> <br/>
                           <span class="badge" style="margin-top: 10px;" id="count_approval"></span>
                        <div class="separator bottom"></div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          <div class="form-actions">                      
              <button class="pull-right btn btn-success" <?php echo $is_enable_approval;?> type="button" onclick="saveMassApproval()">Save Changes</button> 
              <a href="<?php echo base_url('/visitsummary')?>" class="pull-right btn btn-error" style="margin-right: 10px;margin-left: 10px;">Go Back</a>         
          </div>
        </form>        
    </div>
  </div>
  
</div>
</div>
    

<script>

//$("#pmdesktop").validate();
  $(document).ready(function(){
    /*
    $("#get_check_cancel").on("click",function(){
        alert('lalalayeyeye');
    });
    
    $("input[name='tbl_apr']").on("click",function(){
        alert('cinta diterima');
    });

    $("input[name='tbl_apr']").on("click",function(){
        alert('cinta ditolak');
    });
    */
    
    $("#views_detail").show();
    $("#views_history").hide();
    $.each($('.checklist-group-header'), function(){
      //console.log($(this).find('.list-checklist').length);
      if(!$(this).find('.list-checklist').length) {$(this).hide();}
      
    });
  });

  $("#submits").on("click",function(){
     $("#views_history").hide();
     $("#views_detail").show();
  })
  function gethistory(){
     $("#views_detail").hide();
     $("#views_history").show();
  }
  function lookupreject(id,id2){
    //alert(id+id2);
    
    //var region_selected = $("#region").val();
    var url = "<?php echo site_url('/visitsummary/getcheck') ?>";
      $.fancybox({
        href: url,
        type: 'ajax',

        //ajax: { data: { cmd: 'getData' ,region:region_selected } }, // e: it used for selected element
        ajax:{data : {data1:id , data2:id2} },
        width: 600,
        scrolling: false,
        autoSize: true,
        fitToView: true,
        closeClick: true,
        openEffect: 'fade',
        closeEffect: 'fade',
        afterShow: function() {
                     $('.fancybox-wrap').draggable({handle:".widget-head"});
                         
        }
      });
  } 
</script>
<script>
  dateFormat.init();
</script>


<?php 

  function generate_view($data,$no,$is_enable_approval = "",$user_id="")                  
  {
     
    $r = '';
    if($data['photo_name']){
      //$r = '<div style="text-align:center;font-size:12pt;" id="img-'.$data["checklist_id"].'"><a href="javascript:void(0)" onClick="loadImage('.$data["checklist_id"].')" class="btn btn-block btn-default" title="Click to Load image"><i class="icon-picture"></i>Load Image</a></div>';
      $r='<a href="'.base_url('').$data['photo_name'].'" class="fancy"><img loading="lazy" decoding="async" src="'.base_url('').$data['photo_name'].'" height="80"></a>';
    }else{
      $r = '<span class="badge">No Photo</span>';
    }
    $option = '<div class="btn-group" data-toggle="buttons-radio" >';
    if($data['status'] ==1 ){
        $option .= '<button type="button" id="approve_'.$data["checklist_id"].'" name="tbl_apr" onClick="appprovalSurvey('.$data["checklist_id"].',1)" class="approve btn active btn-primary" '.$is_enable_approval.'>Approve</button>';
        $option .= '<button type="button" id="reject_'.$data["checklist_id"].'" name="tbl_capr" onClick="appprovalSurvey('.$data["checklist_id"].',0)" class="reject btn" '.$is_enable_approval.'>Reject</button>';
    }else if($data["status"] != null && $data["status"] == 0 )
    {
         $option .= '<button type="button" id="approve_'.$data["checklist_id"].'" name="tbl_apr" onClick="appprovalSurvey('.$data["checklist_id"].',1)" class="approve btn" '.$is_enable_approval.'>Approve</button>';
        $option .= '<button type="button" id="reject_'.$data["checklist_id"].'" name="tbl_capr" onClick="appprovalSurvey('.$data["checklist_id"].',0)" class="reject btn active btn-primary" '.$is_enable_approval.'>Reject</button>'; 
    }else{
        $option .= '<button type="button" id="approve_'.$data["checklist_id"].'" name="tbl_apr" onClick="appprovalSurvey('.$data["checklist_id"].',1)" class="approve btn" '.$is_enable_approval.'>Approve</button>';
        $option .= '<button type="button" id="reject_'.$data["checklist_id"].'" name="tbl_capr" onClick="appprovalSurvey('.$data["checklist_id"].',0)" class="reject btn" '.$is_enable_approval.'>Reject</button>';
    }
    
    $option .= '</div>';
    $temp ="-";
    $distance_ne = "-";
    $distance_fe = "-";
    if ($data["lat_chk"] && $data["lon_chk"])
	{
        $ne_lat = isset($siteinfo->ne_lat) ? $siteinfo->ne_lat : 0;
		$ne_long = isset($siteinfo->ne_long) ? $siteinfo->ne_long : 0;
		$fe_lat = isset($siteinfo->fe_lat) ? $siteinfo->fe_lat : 0;
		$fe_long = isset($siteinfo->fe_long) ? $siteinfo->fe_long : 0;
		
		$distance_ne = getDistanceFromLatLonInMeter($data["lat_chk"], $data["lon_chk"], $ne_lat, $ne_long);
        $distance_fe = getDistanceFromLatLonInMeter($data["lat_chk"],$data["lon_chk"], $fe_lat, $fe_long);
    }
    $s = '<tr>
            <td>'.$no.'</td>
            <td>'.$data["checklist_name"].'</td>
            <td>'.$data["pm_result"].'</td>                      
            <td>'.$data["lat_chk"] .' , '. $data["lon_chk"].'</td>
            <td>NE :'.$distance_ne.'<br/>FE :'.$distance_fe.'</td>
            <td>'.$r.'</td>
            <td>'.$option.'</td>
          </tr>';
    return $s;
  }
  function generate_header_table(){
    $r = '<table class="table table-striped table-bordered list-checklist">
          <tr>
              <th style="width:5%">No</th>
              <th style="width:25%">Checklist Item</th>
              <th style="width:25%">Answer</th>
              <th style="width:5%">Coordinate</th>
              <th style="width:10%">Distance</th>
              <th style="width:15%">Photo</th>
              <th style="width:15%">Action</th>
          </tr>';
    return $r;
  }
  function generate_footer_table(){
    $r = '</table>';
    return $r;
  }
  function getDistanceFromLatLonInMeter($lat1,$lon1,$lat2,$lon2){
      $R = 6371 ; /*Radius Earth in Km*/
      $dLat = deg2rad($lat2-$lat1);  // deg2rad below
      $dLon = deg2rad($lon2-$lon1); 
      $a = 
        sin($dLat/2) * sin($dLat/2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
        sin($dLon/2) * sin($dLon/2)
        ; 
      $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
      $d = $R * $c; // Distance in km
      $res = $d * 1000;/*convert to m*/
      $distance = number_format((float)$res, 2, ',', '.')." m";
      return $distance;
  }
 
?>
<script>
  var url = "<?php echo base_url("/schedule/securitycode")?>";
  $("#btn_show_request_code").click(function(){
      $("#widget_request_code").show();
      $(this).hide();
  });

   $("#btn_cancel_generate_code").click(function(){
      $("#widget_request_code").hide();
      $("#btn_show_request_code").show();
  });

  $("#btn_generate_code").click(function(){
    var t_remark = $("#request_remark").val();
    var visit_id = <?php echo $siteinfo->visit_id?>;
    if(t_remark.length >2){
        $.post(url,{
          visit_id : visit_id,
          remark : t_remark
        },function(data){
          var obj = jQuery.parseJSON(data);
          if(obj.status == "success"){
            $("#widget_request_code").hide();
            $("#check_in_code").text(obj.code_chk_in);
            $("#check_out_code").text(obj.code_chk_out);
            $("#widget_security_code").show();
          }else{
              coolNotification('<ul style="text-align:left">' + obj.message + '</ul>', 'error');
          }
        });
    }else{
       var message = "Please fill remark";
      coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
    }
  });

  /*Function Load Image*/
  function loadImage(chk_id){
    //var $= jQuery.noConflict();
    var load_img_url = "<?php echo base_url("/visitsummary/getimage/")?>";
    var visit_id = <?php echo $siteinfo->visit_id?>;
    $.post(load_img_url,{
        visit_id : visit_id,
        checklist_id : chk_id
    },function(data){
        var obj_res = jQuery.parseJSON(data);
        if(obj_res.status == "success"){
            $("#img-"+chk_id).empty();
            var img_loaded = '<a href="http://localhost:8181/eatp/file/upload/atpdesktop/2017/03/13/desk-pic_3526-1489418171.PNG" class="fancy" id="fancy'+chk_id+'"><img src="http://farm9.staticflickr.com/8140/30366640446_eb359921c5_m.jpg"></a>';
            //var img_loaded = '<img src="<?php echo base_url('')?>'+""+obj_res.src+'" class="img-responsive"  width="100"/>';
            $("#img-"+chk_id).empty();
            $("#img-"+chk_id).append(img_loaded);
            
                       
            /*End Zoom Image*/
        }else{
          var message = "Image is not exist";
          coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
        }
    }); 
  }
  /*Functio that Count Approval & Rejected Answer*/
  function countNumberApproval(){
     //var $= jQuery.noConflict();
      var approve_btn = $('#accordion :button[class="approve btn active btn-primary"]'); 
      var reject_btn = $('#accordion :button[class="reject btn active btn-primary"]');
      $("#count_approval").empty();
      var data_msg = "Answer approved : "+approve_btn.length+" & Answer Rejected  :"+reject_btn.length;
      $("#count_approval").append(data_msg);

  }

  /*Check Approve*/
  function check_approve(grp_id){
    //var $= jQuery.noConflict();
    var all_btn = $('#group_id_'+grp_id+' :button'); 
    var btn_app = $('#group_id_'+grp_id+' :button[class="approve btn"]'); 
    var btn_rej = $('#group_id_'+grp_id+' :button[class="reject btn"]'); 
    var btn_app_active = $('#group_id_'+grp_id+' :button[class="approve btn active btn-primary"]'); 
    var btn_rej_active = $('#group_id_'+grp_id+' :button[class="reject btn active btn-primary"]'); 
    
    if((btn_app_active.length*2) == all_btn.length){
        /*Do Reject All*/      
         $.each(btn_app_active,  function function_name () {
            $(this).attr('class','approve btn');
        });
         $.each(btn_rej,  function function_name () {
            $(this).attr('class','reject btn active btn-primary');
        });
         $.each(btn_app,  function function_name () {
            $(this).attr('class','reject btn');
        });
        
    }else{
      /*Do Approval all*/
      $.each(btn_app,  function function_name () {
          $(this).attr('class','approve btn active btn-primary');
      });
       $.each(btn_rej,  function function_name () {
            $(this).attr('class','reject btn');
        });
       $.each(btn_rej_active,  function function_name () {
            $(this).attr('class','reject btn');
        });
    }

    /*Count Number Approval*/
    countNumberApproval();
  }
  
  function saveMassApproval(){
     //var $= jQuery.noConflict();
      var remark = $("#remark_mass_approval").val();
      var approve_btn = $('#accordion :button[class="approve btn active btn-primary"]'); 
      var reject_btn = $('#accordion :button[class="reject btn active btn-primary"]'); 
      var visit_id = <?php echo $siteinfo->visit_id?>;
      /*Value Summary*/
      var acpt_status =  $('input[name=acceptance-status]:checked', '#widget_summary').val();
      

      /*End value Summary*/
      if(remark.length>2 && (approve_btn.length>0 || reject_btn.length>0) && acpt_status){
        /*If not empty*/
       
        /*Pending Item*/
        var pending_items =[];
        var pending_completiondate = [];
        var pending_pic = [];
        var pending_contact = [];
        var pending_ids = [];
         /*Collect as Checklist Answer which is approved*/
        var check_approves = [];
        var check_rejected = [];
        //
        $.each(approve_btn,  function function_name () {
          var checklist = $(this).attr('id');
          var chck_id = checklist.substr(8);
          check_approves.push(chck_id);
          // console.log("Approve Button");
          // console.log(chck_id);
       });
        /*Collect as Checklist Answer which is Rejected*/
        $.each(reject_btn,  function function_name () {
          var checklist = $(this).attr('id');
          var chck_id = checklist.substr(8);
          check_rejected.push(chck_id);
          // console.log("Reject Button");
          // console.log(chck_id);
       });
        /*Get All Data Pending Item [name] */
        $.each(($('input[name^=itemdesc]', '#tbl-pending-item')),function function_name(){
            var item_val =  $(this).val();
            pending_items.push(item_val);
            console.log("Pending Item:");
        });

        /*Get All Data Pending Item [completion date] */
        $.each(($('input[name^=date_completion]', '#tbl-pending-item')),function function_name(){
            var item_val =  $(this).val();
            pending_completiondate.push(item_val);
        });

         /*Get All Data Pending Item [pic] */
        $.each(($('input[name^=pic]', '#tbl-pending-item')),function function_name(){
            var item_val =  $(this).val();
            pending_pic.push(item_val);
        });
        /*Get All Data Pending Item [contact no] */
        $.each(($('input[name^=contactno]', '#tbl-pending-item')),function function_name(){
            var item_val =  $(this).val();
            pending_contact.push(item_val);
        });
        /*Get All Data Pending Item [pending-id] */
        $.each(($('input[name^=pending_id]', '#tbl-pending-item')),function function_name(){
            var item_val =  $(this).val();
            pending_ids.push(item_val);
            console.log(item_val);
        });

        /*Show Progressing Layout*/
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
        /*Call API For Save Approval Mass*/
        var url = "<?php echo base_url("/visitsummary/savemassedapproval")?>";
        var base_url = "<?php echo site_url('/visitsummary') ?>";
        $.post(url,
          {
            approval_list           : check_approves,
            rejected_list           : check_rejected,
            remark                  : remark,
            visit_id                : visit_id,
            pending_items           : pending_items,
            pending_completiondate  : pending_completiondate,
            pending_pic             : pending_pic,
            pending_contact         : pending_contact,
            pending_ids             : pending_ids,
            acceptance              : acpt_status
          },
          function(data){
            var obj_res = jQuery.parseJSON(data);
            if(obj_res.status == "success"){                
                coolNotification('<ul style="text-align:left">' + obj_res.message + '</ul>', 'success'); 
                window.location.href = base_url;
            }else{
                coolNotification('<ul style="text-align:left">' + obj_res.message + '</ul>', 'error'); 
            }
            $.unblockUI();
        }); 

      }else if(!acpt_status){ 
          var message = "Please Choose Accaptance Status";
          coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error'); 
      }else if(remark.length<2){
          var message = "Please fill remark field";
          coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error'); 
      }
      else{
          var message = "Please Select Answer for Approval";
          coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
      }

  }

</script>
<script>
var current_add = <?php echo $current_row?>;
  function addPendingItem(){
    current_add ++;
    var content = '<tr class="row-'+current_add+'">'+
                    '<td >'+
                      '<a class="btn btn-danger btn-small btn-remove-row" id="'+current_add+'" href="javascript:void(0)" title="Remove row" data-pending="0" onClick="removeRow(this)">'+
                        '<i class="icon-minus-sign icon-white"></i>'+
                      '</a>'+
                    '</td>'+
                    '<td>'+
                      '<input name="pending_id[]" type="hidden" value="none"/>'+
                      '<input  style="width:94%" class="form-control" type="text" name="itemdesc[]" />'+
                    '</td>'+
                     '<td>'+
                      '<input type="text" name="date_completion[]" class="input-small date" placeholder="DD-MMM-YYYY" />'+
                    '</td>'+
                    '<td>'+
                      '<input  class="form-control" style="width:94%" type="text" name="pic[]" />'+
                    '</td> '+  
                    '<td>'+
                      '<input  class="form-control" style="width:94%" type="text" name="contactno[]" />'+
                    '</td>'+                              
                  '</tr>';
    
    //var $= jQuery.noConflict();
    $("#tbl-pending-item").append(content);
    dateFormat.init();
  }
  

</script>

<script type="text/javascript">
  /*Function approve & reject each checklist answer*/
 
   
  function appprovalSurvey(chck_id,approval_type){      
     
    if(approval_type == 1){
      console.log('aprove');
      $("#get_check_approve").prop("checked",true);
    }

    if(approval_type == 0){
      console.log('reject');
      $("#get_check_cancel").prop("checked",true);
    }

     
     var base_url = "<?php echo site_url('/visitsummary') ?>";
     var url = base_url+"/checklistapproval";
     var visit_id = <?php echo $siteinfo->visit_id?>; 
     
     //var $ = jQuery.noConflict();
      $.fancybox({
        href: url,
        type: 'ajax',
        ajax: { data: { 
                        cmd: 'getData',
                        flag_approval : approval_type  
                      } 
              }, // e: it used for selected element
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
              var remark_approval = $("#remark_each_approval").val();
              $.fancybox.close();
                                                        
                $.post(url+"/save",{
                    chk_id : chck_id,
                    visit_id : visit_id,
                    remark   : remark_approval,
                    type     : approval_type
                },function(data){
                    
                    var obj_res = jQuery.parseJSON(data);
                    if(obj_res.status == "success"){
                                                
                        coolNotification('<ul style="text-align:left">' + obj_res.message + '</ul>', 'success');
                     
                    }else{
                        var message="Approval is not success";
                        coolNotification('<ul style="text-align:left">' + message + '</ul>', 'error');
                    }
                });
              
            };
             document.getElementById('btnCancel').onclick = function() {                                                
              $.fancybox.close();
            };
        }
      });
       
  } 
</script>
<script>
  function removeRow(param){
    //var $= jQuery.noConflict();
    var row_selected = $(param).attr("id");
    var pending_id = $(param).attr("data-pending");
    if(pending_id != "0"){
      console.log(pending_id);
      coolConfirmation('Are you sure remove this pending item?', function() {
          var url = "<?php echo base_url("/visitsummary/deletepending") ?>"+"/"+pending_id;
          /*Soft Delete Pending Item*/
          $.get(url,function(data){
              $(".row-"+row_selected).remove();
          });   
      });
      
    }else{
       $(".row-"+row_selected).remove(); 
    }    
    
  }
</script>
<script>
$(function() {
  $("a.fancy").fancybox({
    'zoomSpeedIn': 300,
    'zoomSpeedOut': 300,
    'overlayShow': false
  }); 
});
</script>
<script>

  function approveAll(){        
    var all_btn = $('#accordion :button'); 
    var btn_app = $('#accordion :button[class="approve btn"]'); 
    var btn_rej = $('#accordion :button[class="reject btn"]'); 
    var btn_app_active = $('#accordion :button[class="approve btn active btn-primary"]'); 
    var btn_rej_active = $('#accordion :button[class="reject btn active btn-primary"]'); 
    /*Do Approval all*/
    $.each(btn_app,  function function_name () {
        $(this).attr('class','approve btn active btn-primary');
    });
    $.each(btn_rej,  function function_name () {
          $(this).attr('class','reject btn');
      });
    $.each(btn_rej_active,  function function_name () {
          $(this).attr('class','reject btn');
    });

  }
  function rejectAll(){
    var all_btn = $('#accordion :button'); 
    var btn_app = $('#accordion :button[class="approve btn"]'); 
    var btn_rej = $('#accordion :button[class="reject btn"]'); 
    var btn_app_active = $('#accordion :button[class="approve btn active btn-primary"]'); 
    var btn_rej_active = $('#accordion :button[class="reject btn active btn-primary"]'); 
     /*Do Reject All*/      
     $.each(btn_app_active,  function function_name () {
        $(this).attr('class','approve btn');
    });
     $.each(btn_rej,  function function_name () {
        $(this).attr('class','reject btn active btn-primary');
    });
     $.each(btn_app,  function function_name () {
        $(this).attr('class','reject btn');
    });

  }
</script>