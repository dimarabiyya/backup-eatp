<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/easyui.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/icon.css">-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>

<script src="<?php echo  base_url() ?>assets/js/overlay.js"></script>
<script src="<?php echo  base_url('assets/js/hehehe.js') ?>"></script>
<style type="text/css">
    .datagrid-body .datagrid-cell{
        word-wrap: break-word;
        white-space: normal;
    }
    .modal {
        left:50%;
        width:800px;
    }
    .datagrid-header-rownumber { width:50px; }

    .datagrid-cell-rownumber { width:50px; }
</style>

<script>
    $(function() {
        $("#q").keydown(function(e) {
            var code = e.which; // recommended to use e.which, it's normalized across browsers
            if (code == 13) {
                doSearch();
                e.preventDefault();
            }

        });
    });
    function doSearch() {
        $('#dg').datagrid('load', {
            q: $('#q').val(),
        });
    }
// masukkan kode javascript berikutnya disini
    function popupwindow(url, title, w, h) {
        wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }

    $(document).ready(function() {
        $("#menu_164").parent().addClass('active').find('.collapse').css('height', 'auto');

    });

    var Document = {
        deleteData: function(id, name) {
            coolConfirmation('Are you sure want to delete<br> "'+name+'" ?', function (){
                $.ajax({
                    url: '<?php echo  base_url() ?>referensi/notif_mail/deleteData',
                    type: 'POST',
                    dataType: 'json',
                    data: {'id':id},
                    beforeSend : function () {
                        block.show('Deleting data');
                    },
                    success: function(result) {
                        coolNotification(result.message, result.status, function (){
                            $('#dg').datagrid('load', {});
                        });
                    },
                    error : function (xhr, error, message) {
                        coolNotification(error.toUpperCase()+' : '+message+', please try again.', 'error');
                    },
                    complete : function () {
                        block.close();
                    }
                });
            });
        },
        addData: function() {
            popupwindow("<?php echo  base_url() ?>referensi/notif_email/set_template", "Template Header", 850, 400);
            return false;
        },
        editData: function(id) {
            popupwindow("<?php echo  base_url() ?>referensi/notif_email/addeditdata/" + id, "Template Menu", 850, 675);

            return false;
        },
        editRemark: function(id) {
            popupwindow("<?php echo  base_url() ?>referensi/notif_email/addeditremark/" + id, "Template Remark", 800, 400);

            return false;
        }
    }

</script>

<style>
    .modal {
        left:40%;
        width:800px;
    }
</style>

<div class="innerLR">
    <div class="widget">
        <div class="widget-head heading-outer">
            <h4 class="heading glyphicons list"><i></i><?php echo  $judul ?></h4>
            <div class="btn-heading-group">
                <a href="#" onclick="return Document.addData();" class="btn btn-mini btn-primary btn-heading btn-icon glyphicons message_flag pull-right"><i></i> Set Notification Template</a>&nbsp;&nbsp;
            </div>
        </div>
        <div class="widget-body">
            <div id="tb" class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
                    <div class="input-append">
                        <input name="q" id="q" type="text" placeholder="Type text to search .." >
                        <button  onclick="doSearch()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
                    </div>  
                </div>  
            </div>
            <br>

            <table  class="easyui-datagrid jgrid" id="dg"
                    url="<?php echo base_url() . 'referensi/notif_email/load_data'; ?>" 
                    title="" nowrap='false'
                    iconCls="icon-save" 
                    rownumbers="true" pagination="true"
                    singleSelect="true" fitColumns="true" pageList="[10,15,20,30,100,200,500]" pageSize="15">

                <thead>
                    <tr>
                        <?php
                        $arr = array(
                            'menu_id' => 'Menu',
                            'template_name' => 'Template Name',
                            'action_desc' => 'Description',
                            'subject' => 'Subject',
//                                            'parameter'=>'Parameter',
                            'notif_type' => 'Type', //added notif_type by andedi (20141015)
                            'mail_status' => 'Status', //added notif_type by andedi (20141015)
                            'action' => 'Action'
                        );
                        $width = array('70', '50', '100', '100', '50', '40', '50');
                        $align = array('left', 'left', 'left', 'left', 'left', 'center', 'center');
                        $i = 0;
                        foreach ($arr as $key => $value) {
                            echo "<th field='{$key}' width='{$width[$i]}' align='{$align[$i]}'>{$value}</th>";
                            $i++;
                        }
                        ?>

                    </tr>
                </thead>
            </table>   
            <!--<hr>-->
        </div>
    </div>
</div>