<?php
$pilih = array('Hidden','Read Only','Read + Edit');
$form_id = $this->input->post('form_id');
$group_id = $this->input->post('group_id');
?>
<form id="form-manage" class="form-horizontal" method="post">
    <input type='hidden' name='fform' value='<?php echo $form_id?>'>
    <input type='hidden' name='fgroup' value='<?php echo $group_id?>'>
    
    <div class="form">
        <div class="widget">
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span12">
                            <div class="control-group" style="margin-bottom: 8px;">
                                    <label class="control-label" for="fname">Nama Element</label>
                                    <div class="controls"><input type="text" class="span12" id="fname" name="fname" value="" class="medium" /></div>
                            </div>
                            <div class="control-group" style="margin-bottom: 8px;">
                                    <label class="control-label" for="frole">Role</label>
                                    <div class="controls">
                                        <select class="selectpicker span12" name="frole">
                                                <?php
                                                $getCardinality = $this->mmenu->getCardinality();
                                                foreach($getCardinality as $r){
                                                        echo"<option value='$r->cardinality_id'>$r->cardinality_name</option>";
                                                }
                                                ?>
                                        </select>
                                    </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>