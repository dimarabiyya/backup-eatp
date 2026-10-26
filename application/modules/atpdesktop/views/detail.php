<script src="<?php echo base_url(); ?>assets/js/overlay.js"></script>
<!-- <script src="<?php echo base_url(); ?>assets/js/jquery/jquery-1.10.2.js"></script> -->
<script src="<?php echo base_url(); ?>assets/js/jquery/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jqueryvalidation/jquery.validate.js"></script>

<style>
  label > input[type="radio"] {
    margin: -4px 0 0 0;
  }
</style>


<!-- <div class="heading-buttons">
  <h3>
    <?php echo $title;
      if(isset($subTitle) ) 
        { echo '<span class="hidden-phone">| '.$subTitle.'</span>'; } 
    ?>
  </h3>
</div> -->

<div class="separator bottom"></div>
<div class="innerLR">
  <div class="widget">
    <div class="widget-head"><h4 class="heading"><?php echo $title; ?></h4></div>
    <div class="widget-body">
      <div class="row-fluid">
        <div class="span6">
          <table class="table table-bordered table-condensed">
            <tbody>
              <tr>
                <td style="background: #f5f5f5" width="130px">Site LINK</td>
                <td><?php echo $siteinfo->site_link; ?></td>
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
                <td><?php echo $siteinfo->status != 0 ? date("d-M-Y",strtotime($siteinfo->pm_desktop_on)) : 'Not Yet'; ?></td>
              </tr>
            </tbody>
          </table>            
        </div>
      </div>
    </div>
  </div>
  <div class="widget">
    <div class="widget-body">
      <form action="<?php echo base_url() ?>atpdesktop/saveAnswer" method="post" enctype="multipart/form-data">
        <input type="hidden" name="visit_id" value="<?php echo $this->core->encode($siteinfo->visit_id); ?>">
        
      <?php 
        // foreach ($checklist_item as $cc) {
        //   echo generate_view($cc);
        // }
      ?>
                <div class="accordion pm-visit-type-options" id="accordion">

                <?php 
                  function render($properties) {
                    function sub_render($parent, $iterasi) {
                      echo '<div class="accordion-group checklist-group-header">';
                      echo '<div class="accordion-heading">';
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
                          sub_render($gg, false);
                        }
                      }
                      if(isset($parent['checklists'])) {
                        foreach ($parent['checklists'] as $cc) {
                          item_render($cc);
                        }
                      }
                      echo '</div>';
                      echo '</div>';
                      echo '</div>';
                    }

                    function item_render($checklist) {
                      /*Generate View Check List*/
                      echo generate_view($checklist);
                    }

                    $_1st = 1;
                    foreach ($properties as $gg) {
                      sub_render($gg, $_1st++);
                    }
                  }

                  render($checklistsgrouping);
                ?>

                </div>
      <div class="form-actions">
        <a><strong>*Note : Pastikan file yang diupload adalah format foto JPG, Bukan format lain seperti .xls, .pdf, dan lainnya</strong></a>
        <a href="<?php echo base_url('/atpdesktop')?>" class="pull-right btn btn-error" style="margin-right: 10px;margin-left: 10px;">Go Back</a>
        <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
        
      </div>
      </form>
    </div>
  </div>
</div>
</div>


<script>
$("#pmdesktop").validate();
  $(document).ready(function(){
    $.each($('.checklist-group-header'), function(){
      if(!$(this).find('.checklist-items').length) {$(this).hide();}
    });
  });
</script>

<?php 
  function generate_view($data)
  {
    $r = '';
    if($data['mandatory'] == 1){
      $r = '<span class="label label-important">Important</span>';
    }
    $s = '<div class="widget checklist-items">
      <div class="widget-head"><h4 class="heading">'.$data["group_path"].'</h4></div>
      <div class="widget-body">
        <p><label for="checklist_id_'.$data["checklist_id"].'"><strong>'.$data["checklist_name"].'</strong></label> '.$r.'</p>
        <p>'.$data["view"].'</p>
        
      </div>
    </div>';
    return $s;
  }
?>