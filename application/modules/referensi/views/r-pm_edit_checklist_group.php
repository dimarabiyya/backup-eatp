<style type="text/css">
    select{width:auto;}
    table input[type="checkbox"]{
        margin: -3px 5px 0 0;
    }
    .widget { border:none; }
    .form-horizontal .control-label { text-align:left; }
    fieldset { margin-bottom:20px; border-bottom:1px solid #CCCCCC; padding:0px; }
    legend { border-bottom:1px solid #CCCCCC; margin:0 0 4px; }
    legend + .control-group { margin-top:0px; }
    .form-horizontal .column-left { float:left; }
    .form-horizontal .column-right { float:left; margin-left:80px; }
    .form-horizontal .control-group { margin-bottom:4px; }
    .form-horizontal .control-group:last-child { margin:0 0 4px; }
    .btn { padding:4px 10px; }
    .table-primary thead th { font-size:12px; }
    .panel { background:#FFFFFF; }
    fieldset{margin-top:0px;margin-bottom:none;border-bottom: none;}
    legend{margin-bottom:10px;}
    .equipment{display: none;}
    .sinfo{font-size:10px;color:gray;}
</style>

<form class="form-horizontal" method="POST" id="add-form" >

    <div class="widget">
        <div class="widget-head"><h4 class="heading"><?php echo $judul ?></h4></div>
        <div class="widget-body">
            <div class="row-fluid">
                <input type='hidden' name='id' value='<?php echo $id ?>' >
                <input type='hidden' name='group_key' value='prev.maintenance' >
                <input type='hidden' name='old_level' value='<?php echo $level ?>' >

                <div class="control-group" id="form_group_name_concat">
                    <div class="controls">
                        <b id="group_name_concat"></b>
                    </div>
                </div>

                <div class="control-group" id="form_site_id">
                    <label class="control-label" for="site_id">Checklist Group</label>
                    <div class="controls">


                        <select class="span12" name="parent" id="parent">
                            <option level='0' value='<?php echo $parent_top; ?>'>- Checklist Group -</option>
                            <?php
                            if (count($parentx) > 0) {
                                foreach ($parentx as $parent)
                                //echo '<option value="'.$parent['group_id'].'"'.($parent['group_id'] == $parent_id ? ' selected' : '').'>'.str_repeat('&nbsp;',$parent['level']*3).$parent['group_name'].'</option>';
                                //echo '<option  level="'.$parent['level'].'" value="'.$parent['group_id'].'"'.($parent['group_id'] == $parent_id ? ' selected' : '').'>'.str_repeat('&nbsp;',$parent['level']*3).$parent['group_name'].'</option>';
                                    echo '<option data-root-id="'.$parent['root_id'].'" level="' . $parent['level'] . '" group_name_concat="' . $parent['group_name_concat'] . '" value="' . $parent['group_id'] . '"' . ($parent['group_id'] == $parent_id ? ' selected' : '') . '>' . str_repeat('&nbsp;', $parent['level'] * 3) . $parent['group_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div> 

                <div class="control-group" id="form_group">
                    <label class="control-label" for="group">Group Name</label>
                    <div class="controls"><input class="span12" id="group" name="group" type="text" value="<?php echo $group_name ?>" /></div>
                </div>


                <div class="control-group" id="form_order">
                    <label class="control-label" for="order">Order</label>
                    <div class="controls"><input class="span12" onKeyUp="this.value = this.value.replace(/\D/, '')" id="order" name="order" type="text" value="<?php echo $order_number ?>" /></div>
                </div>


                <div class="form-actions">
                    <button type="button" class="btn btn-icon btn-primary glyphicons circle_ok" onclick="saveFormGroup()"><i></i>Save</button>
                    <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" onclick="closeFancybox()"><i></i>Close</button>
                </div>
            </div>


        </div>

    </div>

</form>


<script>
                        var group_name_concat = $('#parent option:selected').attr('group_name_concat');
                        $("#group_name_concat").html(group_name_concat);
                        $("#parent").mousemove(function() {
                            var group_name_concat = $('#parent option:selected').attr('group_name_concat');
                            $("#group_name_concat").html(group_name_concat);
                        });

                        function saveFormGroup() {

                            var level = $('#parent option:selected').attr('level');
                            var root_id = $('#parent option:selected').attr('data-root-id');
                            var level_child = parseInt(level) + 2;

                            if ($('#order').val() == '0') {
                                notyfy({layout: 'center', text: 'Order cannot filled zero', type: 'error'});
                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo base_url() ?>referensi/pm_checklist/saveData",
                                    // data: $('#add-form').serialize(),
                                    data: $('#add-form').serialize() + "&level=" + level_child +"&root_id="+root_id,
                                    dataType: "json",
                                    beforeSend: function() {
                                        $.blockUI({ message: '<img width="16px" src="<?php echo base_url('file/images/loading-snake.GIF');?>"> Saving data, please wait...', css: {
                                                border: 'none',
                                                padding: '15px',
                                                backgroundColor: '#000',
                                                '-webkit-border-radius': '10px',
                                                '-moz-border-radius': '10px',
                                                opacity: .5,
                                                color: '#fff',
                                                'z-index':99999
                                        } });
                                    },
                                    success: function(data) {
                                        if (data.status == "OK") {
                                            coolNotification('Data saved.', 'success', function () {
                                                window.location.href = "<?php echo base_url(); ?>referensi/pm_checklist/index";
                                            });
                                        } else {
                                            coolNotification(data.hasil, 'error', function () {

                                            });
                                        }
                                    },
                                    error: function(xhr, status, message) {
                                        coolNotification('Saving data failed.<br>'+status.toUpperCase()+' : '+message, 'error', function (){

                                        });
                                    },
                                    complete: function () {
                                        $.unblockUI();
                                    }    
                                });
                            }
                            return false;
                        }
</script>