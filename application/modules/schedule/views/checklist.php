<div class="accordion pm-visit-type-options" id="accordion">

<?php 
  function render($properties) {
    function sub_render($parent, $iterasi, $pgi) {
      // print_r($parent); die();
      if($iterasi) 
        { echo '<div class="accordion-group pgi_div_'.$pgi.' checklist-group-header checklist_group_level_0"  id="group_heading_'.$parent['group_id'].'">'; }
      else 
        { echo '<div class="accordion-group pgi_div_'.$pgi.' checklist-group-header"  id="group_heading_'.$parent['group_id'].'">'; }

      echo '<div class="accordion-heading">'; 

      if($iterasi) 
        { echo '<a class="pull-right" title="Select All"></a><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#group_id_'.$parent['group_id'].'">'; }
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
          sub_render($gg, false, $pgi);
        }
      }
      if(isset($parent['checklists'])) {
        foreach ($parent['checklists'] as $cc) {
          //echo "PGI: ".$pgi;die;
          item_render($cc, $parent['group_id'], $pgi);
          //item_render($cc, $parent['group_id'], $pgi);
        }
      }
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }

    function item_render($checklist, $parent_group_id, $pgi) {
      //var_dump($checklist);die;
      echo '<label class="checkbox checklist-items">';
      echo '<input type="checkbox" onchange="sub_pgi(\'pgi_'.$pgi.'\')" value="'.$checklist['checklist_id'].'" class="pm-checklist-item checkbox checklist_items pgi_'.$pgi.' '.$checklist['class'].'" name="checklist_item" disabled="disabled" parentid='.$parent_group_id.'>';
      echo $checklist["checklist_name"];
      if ($checklist["interface"]) 
        { echo '<img src="'.base_url().'assets/images/interface-'.$checklist["interface"].'.png" style="margin: -4px 0 0 4px">'; }
      echo '</label>';
    }

    $_1st = 1;
    foreach ($properties as $gg) {
      sub_render($gg, $_1st, $_1st++);
    }
  }

  render($properties);

?>

</div>

<script>
  /*Function Configure Checklist when Edit*/
  function updateChecklist(sch_id){
    var update_checklist_url = "<?php echo base_url("/schedule/listchecklist")?>/"+sch_id;
    /*Get Checklist ID from selected Schedule ID*/
    $.get(update_checklist_url,function(data){
        var obj_link = jQuery.parseJSON(data);
        /*Iterate Checklist Options*/
        $.each( obj_link, function( key, item ) {                    
            $('input[name=checklist_item][value='+item.checklist_id+']').attr('checked', true);           
        });
        count_item();
        group_number();
    });
  }  
</script>

<script >
var schedule_id = "<?php echo $schedule_id; ?>";
/*State in Edit Mode*/
if(schedule_id !=0){
  updateChecklist(schedule_id);
}else{
  count_item();
  group_number();
}
/*When Visit Type is Changed*/
$('#pm-visit-type').change(function(){
    var d = $('#pm-visit-type').val();
    console.log("Visit Type:"+d);
    var c = $('#pm-visit-type option[value='+d+']').attr('data-group');
    
    $('.pm-visit-type-options :checkbox').removeAttr('checked');
    $('.pm-visit-type-options :checkbox.'+c).attr('checked', true);
    $('.pm-checklist-item').attr('disabled', true);
    count_item();
    group_number();
  });

function count_item () {
  var item = $('#accordion :checked').length;
  if(item == 0) {var msg = 'No checklist item is selected, please pick some';}
  else if(item == 1) {var msg = 'One checklist item is selected';}
  else {var msg = item + ' checklist items are selected';}
  $('#jumlah-checklist').html(msg);
}

function group_number(elId) {
  if(elId) {
    var cl = $('#'+elId).attr('class').split(' ');
    var c  = cl[1];
    $.each($('.'+c), function(x,y){
      $($(this).find('.checklist-total')).remove();
      var r = $(this).children().after().find(':checkbox');
      var u = $(this).children().after().find(':checked');
      var x = '<a class="pull-right accordion-toggle checklist-total" onclick="cua(\''+$(this).attr('id')+'\')" data-toggle="tooltip" title="Click to select/unselect All Checklist">'+u.length+'/'+r.length+'</a>';
      $(x).prependTo($(this).find('.accordion-heading'));
    }); 
  }
  else {

    $('.checklist-total').remove();
    $.each($('.accordion-group'), function(x,y){
      var p = $(this).attr('id');
      // console.log(p);
      var r = $(this).children().after().find(':checkbox');
      var u = $(this).children().after().find(':checked');
      var x = '<a class="pull-right accordion-toggle checklist-total" onclick="cua(\''+p+'\')" data-toggle="tooltip" title="Click to select/unselect All Checklist">'+u.length+'/'+r.length+'</a>';
      $(x).prependTo('div#'+p+' > .accordion-heading');
    }); 
  }
  $('.checklist-total').tooltip({'placement':'left'});
}

function cua (elId) {
  var lock = $('#pm-visit-type option:selected').attr('locked');
  var dx = elId.split('_');
  var group_id = 'group_id_'+dx[2].trim();
  // console.log(group_id);

  var cl = $('#'+group_id+' :checkbox'); // jumlah checklist (tercentang/tidak)
  var cc = $('#'+group_id+' :checkbox:checked'); // jumlah checklist yang dicentang
  if(lock != 1) {
    if(cl.length != cc.length) {
      $.each(cl, function function_name () {
        if($(this).attr('disabled') != 'disabled' ) {
          $(this).attr('checked', true);
        }
      })
    }
    else {
      $.each(cl, function function_name () {
        if($(this).attr('disabled') != 'disabled' ) {
          // $(this).attr('checked', true);
          $(this).removeAttr('checked');
        }
      })
    }
    count_item();
    group_number(elId);
  }
}

function sub_pgi (pgi_id) {
  count_item();
  var dpgi = pgi_id.split('_');
  var ghid = $('.pgi_div_'+dpgi[1]).attr('id');
  group_number(ghid);
}

function save_schedule() {
  var checkValues = [];
  $('input[name=checklist_item]:checked').each(function() {
    checkValues.push($(this).val());
  });

  $.post(
    "<?php echo base_url('pm/schedule/save'); ?>",
    {
      site_id             : $('input#siteID').val(),
      pic_id              : $('#pic-smart').val(),
      user_id             : $('#picID').val(),
      visit_date          : year+'-'+month+'-'+date,
      priority            : $('#pm-priority > button.active').html(),
      visit_type          : $('#pm-visit-type option:selected').html(),
      remark              : $('#remark').val(),
      pic_non_default_remark    : $('#pic_non_default_remark ').val(),
      checklist_item : checkValues,
    },
    function(data) {
      var datax = jQuery.parseJSON(data);
      $('#doubleVisit').modal('hide');
      if(datax.status)
        { var grit_icon = "<?php echo base_url('assets/images/emotion_smile.png'); ?>"; }
      else
        { var grit_icon = "<?php echo base_url('assets/images/emotion_sad.png'); ?>"; }

      var n = noty({
        layout       : 'center',
        theme        : 'defaultTheme',
        type         : 'success',
        text         : datax.message,
        dismissQueue : true,
        modal        : true,
        timeout      : 5000,
        callback     : { 
          onShow: function()     { console.log('open'); },
          afterShow: function()  { console.log('opened'); },
          onClose: function()    { console.log('close'); },
          afterClose: function() { console.log('closed'); 
          window.location.href = "<?php echo base_url();?>pm/schedule";
          }//location.reload();}
        }
      });

      // $.gritter.add({
      //   title: 'Notice',
      //   text: datax.message,
      //   image: grit_icon,
      //   sticky: false,
      //   time: '1000',
      //   after_close: function()
      //     { location.reload(); }
      // });
    }
  ); 
}

document.getElementById('btn-custom').onclick = function() {
    if($('#pm-visit-type option:selected').attr('locked') != 1) {
      var items = document.getElementsByName('checklist_item');
      for (var i = 0; i < items.length; i++) {
        items[i].disabled = false;
      }
    }
    else {
      $('.pm-checklist-item').attr('disabled','disabled');
    }
    
    $('#btn-checkall').removeAttr('disabled');
    $('#btn-checkall').removeClass('disabled');

  }
  
  document.getElementById('btn-checkall').onclick = function() {
    // alert('EUREAKA !!!');
    var lock = $('#pm-visit-type option:selected').attr('locked');

    var cl = $('#accordion :checkbox'); // jumlah checklist (tercentang/tidak)
    var cc = $('#accordion :checkbox:checked'); // jumlah checklist yang dicentang
    if(lock != 1) {
      if(cl.length != cc.length) {
        $.each(cl, function function_name () {
          if($(this).attr('disabled') != 'disabled' ) {
            $(this).attr('checked', true);
          }
        })
      }
      else {
        $.each(cl, function function_name () {
          if($(this).attr('disabled') != 'disabled' ) {
            // $(this).attr('checked', true);
            $(this).removeAttr('checked');
          }
        })
      }
  
      count_item();
      group_number();
      
    }
  }

</script>

