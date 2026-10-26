<?php

/*
 * Andedi Tarmadi
 * 
 * 
 * ----------------
 * Tumbas Wiralodra
 * Email : andedi.foss@gmail.com
 * 087829986468
 * 15-09-2014
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

echo form_open_multipart('', 'id="form-site-reloc" class="form-horizontal"'); 
?>
<div class="innerLR">
<div class="widget">
	<div class="widget-head">
            <h4 class="heading glyphicons circle_plus"> <i></i> Site Relocation </h4>
                <a class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right" href="<?php echo $list_url; ?>"><i></i> Back</a>
	</div>
	
	<div class="widget-body">
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                            <label class="control-label">Vendor ID </label>
                            <div class="controls"> 
                                <input name="vendor_id" id="vendor_id" value="<?php echo  $row->vendor_id; ?>" readonly>
                                <input type='hidden' name="site_relocation_id" value="<?php echo  $row->site_relocation_id; ?>">
                                <button onclick="doSearch();" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
                            </div>
                        </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span4">
                    <div class="control-group form-inline">
                        <label class="control-label">Type </label>
                        <div class="controls">
                            <?php 
                                $type_option = array(
                                    ''=>'- Choose One -',
                                    1=>'Dismantle',
                                    2=>'Pindah Site',
                                );
                                echo form_dropdown('relocation_type', $type_option, $row->relocation_type); 
                            ?>
                        </div>
                        <div class="separator bottom"></div>
                    </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span12">
                    <div class="control-group">
                            <label class="control-label">Date from </label>
                            <div class="controls">
                                <input name="dismantle_start_date" id="start_date" value="<?php echo  $row->dismantle_start_date; ?>" readonly> to 
                                <input name="dismantle_end_date" id="finish_date" value="<?php echo  $row->dismantle_end_date; ?>" readonly>
                            </div>
                        </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                            <label class="control-label">Relocate to </label>
                            <div class="controls"> 
                                <input name="site_destination_code" id="site_destination_code" value="<?php echo  $row->site_destination_code; ?>" readonly>
                                <input type='hidden' name="site_destination" id="site_destination" value="<?php echo  $row->site_destination ?>">
                                <button onclick="lookupSite();" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
                            </div>
                        </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span8">
                    <div class="control-group">
                            <label class="control-label">Address </label>
                            <div class="controls"> 
                                <input name="site_destination_address" id="site_destination_address" value="<?php echo  $row->site_destination_address; ?>" readonly style="width: 435px;">
                            </div>
                        </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span4">
                    <div class="control-group form-inline">
                        <label class="control-label">Cause </label>
                        <div class="controls">
                            <?php 
                                $cause_option = array(
                                    ''=>'- Choose One -',
                                    1=>'Cause A',
                                    2=>'Cause B',
                                    3=>'Cause C',
                                    4=>'Cause D',
                                    5=>'Cause E',
                                    6=>'Cause F',
                                );
                                echo form_dropdown('cause', $cause_option, $row->cause); 
                            ?>
                        </div>
                        <div class="separator bottom"></div>
                    </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span4">
                    <div class="control-group form-inline">
                        <label class="control-label">Doc. Attachment </label>
                        <div class="controls">
                            <?php 
                                echo form_upload('doc_name'); 
                                echo form_hidden('current_file', $row->doc_name);
                            ?>
                        </div>
                        <div class="separator bottom"></div>
                    </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span12">
                    <div class="control-group">
                            <label class="control-label">Remark </label>
                            <div class="controls"> 
                                <textarea name="remark" style="width: 430px; height: 150px;"><? echo $row->remark; ?></textarea>
                            </div>
                        </div>
                </div>
            </div>
            
                <div style="padding:19px 0px 20px 20px" class="form-actions">
                    <input type="hidden" value="save" name="save">
                    <button class="btn btn-primary btn-icon glyphicons circle_ok" type="submit" name="save"><i></i> Save</button>
                    <!--<button onclick="window.location.href='<?php echo $list_url; ?>'" class="btn btn-icon btn-close btn-default glyphicons circle_remove" type="button"><i></i>Cancel</button>-->
                </div>
		
	</div>
</div>
</div>

<script>
    
    function doSearch() { //site lookup
        $.fancybox({
            href        : "<?php echo  base_url() ?>problem/problem/searchSite", type: 'ajax',
            minWidth    : 100, 
            scrolling   : false, 
            autoSize    : true, 
            fitToView   : true, 
            closeClick  : false, 
            openEffect  : 'fade', 
            closeEffect : 'fade', 
            afterShow   : function() {
                $('#app_no').focus();
            }
        });
    }
    
    function lookupSite() { //site relocation lookup
        $.fancybox({
            href        : "<?php echo  base_url() ?>site_info/site_info/lookup_site", type: 'ajax',
            minWidth    : 100, 
            scrolling   : false, 
            autoSize    : true, 
            fitToView   : true, 
            closeClick  : false, 
            openEffect  : 'fade', 
            closeEffect : 'fade'
        });
    }
    
    $(document).ready(function (){
        
        //init datepicker
        $('#start_date').datepicker({
            dateFormat:'dd-mm-yy'
        });
        $('#finish_date').datepicker({
            dateFormat:'dd-mm-yy',
            minDate:$('#start_date').val()
        });
        
        $('#start_date').change(function (){
            $('#finish_date').datepicker('option', 'minDate', $('#start_date').val());
        });
        
    });
    
    $('form#form-site-reloc').submit(function (e){
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        var url_form = $('#form-site-reloc').attr('action');
        $.ajax({
            url         : url_form,
            type        : 'post',
            data        : formData,
            dataType    : 'json',
            cache       : false,
            contentType : false,
            processData : false,
            beforeSend  : function () {
                notyfy({layout: 'center',text: 'Saving data..', type: 'success', timeout:1000});
            },
            success     : function (response) {
                if (response.type=='ok') {
//                    $('#form-site-reloc')[0].reset();
                    setTimeout(function (){
                       notyfy({layout: 'center',text: response.message, type: 'success', timeout:3000}); 
                    }, 1500);
                    setTimeout(function (){
                        window.location.href= '<?php echo base_url('site_info/site_relocation');?>';
                    }, 3000);
                } else {
                    setTimeout(function (){
                        notyfy({
                            layout: 'center',
                            text: response.message, 
                            type: 'error',
                            buttons: [{
                                addClass: 'btn',
                                text: 'Ok',
                                onClick: function($notyfy) {
                                    $notyfy.close();
                                }
                              }]
                        });
                    }, 1500);
                }
//                notyfy({layout: 'center',text: 'Data saved', type: 'success', timeout:3000});
//                $('#form-site-reloc')[0].reset();
//                window.location.href= '<?php echo base_url('site_info/site_relocation');?>';
            },
            complete    : function () {
        
            },
            error       : function () {
                notyfy({layout: 'center',text: ' Saving data error! ', type: 'error', timeout: 3000});
                $('#form-site-reloc')[0].reset();
            }
        });
    });
    
</script>