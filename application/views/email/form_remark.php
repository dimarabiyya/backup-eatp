<?php error_reporting(0); ?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
    <head>
        <title>Mail Notification Remark - Smartfren Network</title>

        <!-- Meta -->
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- JQuery v1.8.2 -->
        <script type="text/javascript" src="<?php echo  base_url() ?>assets/js/jquery.js"></script>
        <!-- JQueryUI v1.9.2 -->
        <script src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>

        <!-- FANCY BOX-->
        <link rel="stylesheet" href="<?php echo base_url('assets/scripts/fancybox/jquery.fancybox.css') ?>" type="text/css" media="screen">
        <script src="<?php echo base_url('assets/scripts/fancybox/jquery.fancybox.js') ?>"></script>

        <!-- Bootstrap -->
        <link href="<?php echo  base_url() ?>file/template/smashing/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo  base_url() ?>file/template/smashing/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />

        <!-- Bootstrap Extended -->
        <link href="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap-responsive.min.css" rel="stylesheet">
        <link href="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">

        <!-- JQueryUI v1.9.2 -->
        <link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.min.css" />

        <!-- Glyphicons -->
        <link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/theme/css/glyphicons.css" />

        <!-- Bootstrap Extended -->
        <link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-select/bootstrap-select.css" />
        <link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />


        <!-- Modernizr -->
        <script src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/modernizr.custom.76094.js"></script>

        <!-- MiniColors -->
        <link rel="stylesheet" media="screen" href="<?php echo  base_url() ?>file/template/smashing/theme/scripts/jquery-miniColors/jquery.miniColors.css" />

        <!-- Notyfy -->
        <script type="text/javascript" src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.js"></script>
        <link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css"/>
        <link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/theme/scripts/notyfy/themes/default.css"/>

        <!-- Gritter -->
        <link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/theme/scripts/Gritter/css/jquery.gritter.css"/>
        <script type="text/javascript" src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/Gritter/js/jquery.gritter.min.js"></script>


        <!-- google-code-prettify -->
        <link href="<?php echo  base_url() ?>file/template/smashing/theme/scripts/google-code-prettify/prettify.css" type="text/css" rel="stylesheet" />

        <!-- Theme -->
        <link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/theme/css/style.min.css" />
        <link rel="stylesheet" href="<?php echo  base_url() ?>file/template/smashing/theme/css/style.red.css" />

        <!-- LESS 2 CSS -->
        <script src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/less-1.3.3.min.js"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/easyui.css">
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/icon.css">-->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/datagrid-detailview.js"></script>



        <style type="text/css">
            .datagrid-body .datagrid-cell{
                word-wrap: break-word;
                white-space: normal;
            }
            .placeholder{
                float: left;
                position:relative;
                display: block;
                margin: 5px;
                background: #DDD6D6;
                padding: 3px;
                border-radius: 4px;
                cursor:pointer;
                font-size: 12px;
            }
        </style>	

        <style>
            body{
                -webkit-font-smoothing: antialiased;
                /*font-family: "Helvetica Neue",sans-serif;*/ 
                font-weight: 400;
                font-size: 12px;
                line-height: 1.2;
            }
            .control-group{
                border-bottom: solid 1px #D6D6D6;
            }
            .control-label{
                font-size: 15px;
                color: #999;
            }
            .email_input{
                border:0px;
                box-shadow: none;
                -webkit-box-shadow:none;
                -webkit-transition:none;
                font-family:"Helvetica Neue",sans-serif;
                font-size: 15px;
                width:80%;
            }
            .email_input[type="text"]:focus{
                box-shadow: none;
                -webkit-box-shadow:none;
                -webkit-transition:none;
            }
            .form-horizontal input{
                border:0px;
                box-shadow: none;
                -webkit-box-shadow:none;
                -webkit-transition:none;
                font-family:"Helvetica Neue",sans-serif;
                /*			font-size: 15px;
                                        width:80%;*/
            }
            .form-horizontal input[type="text"]:focus{
                box-shadow: none;
                -webkit-box-shadow:none;
                -webkit-transition:none;
            }
            .form-horizontal .control-label {
                margin-left: 10px;
            }
            .form-horizontal .control-group {
                padding: 2px 0 2px !important;
                margin-bottom: 2px;
            }
            .btn-primary {
                text-shadow:none;
                color:black;
                border-color: #FFF;
                background: #E4E4E4;
            }
            .btn-primary:hover{
                background: #C9C9C9;
            }
            .btn-primary .caret {
                border-top-color: black;
                border-bottom-color:black;
            }
            .wysihtml5-toolbar{margin:5px 0 0 5px;}
            .wysihtml5:focus{border:0px;}
            .heading{
                font-size: 15px;
                color: #999;
                font-weight: 300;			
            }
            .btn-email {
                height:30px;
                margin: 5px 5px;
                min-width: 75px;
            }
        </style>

        <?php
        #tentukan mana yg boleh tampil& tdk
        echo $this->mglobal->get_prev_kolom();
        ?>
    </head>
    <body>
        <script language="javascript" src="<?php echo  base_url(); ?>assets/js/jquery.form.js"></script>
        <script src="<?php echo  base_url() ?>assets/js/overlay.js"></script>
        <form class="form-horizontal" method="post" action="<?php echo base_url(); ?>referensi/notif_email/saveRemark">
            <input type="hidden" name="id" value="<?php echo $notif_id; ?>">
            <div class="widget">
                <div class="widget-head" style="height:40px">
                    <h4 class="heading" style="line-height: 40px; vertical-align: middle; font-size: 16px; font-weight: bold;">Mail Notification Remark</h4>
                    <button onclick="window.close(); return;" type="button" class="btn-email submit pull-right"><i></i>Cancel</button>&nbsp;
                    <button type="submit" class="btn-email submit pull-right"><i></i>Save</button>
                </div>
                <div class="widget-body" style="padding:0px">
                    <div class="row-fluid">
                        <div class="control-group">
                            <label class="control-label" style="text-align: left;width:auto">Enable : </label>
                            <div class="controls" style="margin-left:70px; line-height: 25px; vertical-align: middle">
                                <?php
                                $checked = true;
                                ($mail_status == 1) ? $checked = true : $checked = false;
                                echo form_checkbox('mail_status', $mail_status, $checked, 'style="line-height:20px; height:20px"');
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label search" txt="txt_to" style="text-align: left;width:auto" title="Click to add user">Remark :</label>
                            <div class="controls" style="margin-left:0px">
                                <input name="remark" class="txt_email email_input" type="text" userid="" id="remark" autocomplete="off" value="<?php echo $remark; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label search" txt="txt_to" style="text-align: left;width:auto" title="Click to add user">Recipient List :</label>
                        </div>
                        <div style="width: 100%">
                            <div style="margin-left: 10px; width: 15%; float: left">
                                <?php
                                $data = array(
                                    'name' => 'select-all',
                                    'value' => 'Select All',
                                    'id' => 'select-all',
                                    'checked' => false
                                );
                                echo form_checkbox($data) . ' Select All';
                                ?>
                            </div>
                            <div style="width: 25%; float: left">to</div>
                            <div style="width: 25%; float: left">cc</div>
                            <div style="width: 25%; float: left">bbc</div>
                        </div>
                        <div class="clearfix"></div>
                        <div style="margin-bottom: 50px; overflow: auto; max-height: 450px;">
                            <div style="margin-left: 10px; min-width: 15%; float: left">
                                &nbsp;
                            </div>
                            <div style="width: 25%; float: left">
                                <?php
                                foreach ($rec_to as $data) {
                                    $user_to = $data['user_id'];
                                    $is_active = $data['is_active'];
                                    echo form_checkbox('user_id[]', $user_to, $is_active, 'class="cb-user"') . " $user_to <br>";
                                }
                                ?>
                            </div>
                            <div style="width: 25%; float: left">
                                <?php
                                foreach ($rec_cc as $data) {
                                    $user_cc = $data['user_id'];
                                    $is_active = $data['is_active'];
                                    echo form_checkbox('user_id[]', $user_cc, $is_active, 'class="cb-user"') . " $user_cc <br>";
                                }
                                ?>
                            </div>
                            <div style="width: 25%; float: left">
                                <?php
                                foreach ($rec_bcc as $data) {
                                    $user_bcc = $data['user_id'];
                                    $is_active = $data['is_active'];
                                    echo form_checkbox('user_id[]', $user_bcc, $is_active, 'class="cb-user"') . " $user_bcc <br>";
                                }
                                ?>
                            </div>
                            <?php
//                                foreach ($notif as $data) {
//                                    if ($data->is_active==1)
//                                        $checked='checked';
//                                    else 
//                                        $checked='';
//                                    echo "<span style='width:30%; float:left'><input type='checkbox' name='user_id[]' value='$data->user_id' $checked> $data->user_id</span>";
//                                }
                            ?>
                        </div>
                    </div>
                    <div class="control-group" style="position: fixed;width: 100%;bottom: 0;background: #F1EEEE;">
                        <div class="pull-right">
                            
                        </div>
                    </div>	

                </div>
            </div>
        </form>

        <!-- jQuery Validate -->
        <script src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/form_validator.js"></script>
        <!-- Bootstrap Script -->
        <script src="<?php echo  base_url() ?>file/template/smashing/bootstrap/js/bootstrap.min.js"></script>

        <!-- Bootstrap Extended -->
        <script src="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-select/bootstrap-select.js"></script>
        <script src="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
        <script src="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"></script>
        <script src="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootbox.js" type="text/javascript"></script>
        <script src="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js" type="text/javascript"></script>
        <script src="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js" type="text/javascript"></script>
        <!-- Block UI Ttg -->
        <script src="<?php echo base_url('assets/js/jquery.blockUI.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>


        <!-- google-code-prettify -->
        <script src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/google-code-prettify/prettify.js"></script>
        <script type="text/javascript">
                                var mode = "#txt_to";
                                var prevCtrl;
                                baseURL = '<?php echo base_url(); ?>';
                                var opener = window.opener;

                                $(function() {
                                    $('.search').live("click", function() { //added by andedi (20141014), begin
                                        mode = '#' + $(this).attr('txt');
                                        console.log(mode);
                                        $.fancybox({
                                            href: "<?php echo  base_url() ?>user/searchAllUser", type: 'ajax',
                                            minWidth: 100,
                                            scrolling: false,
                                            autoSize: true,
                                            fitToView: true,
                                            closeClick: false,
                                            openEffect: 'fade',
                                            closeEffect: 'fade',
                                            afterShow: function() {
                                                $('#app_no').focus();
                                            }
                                        });
                                    });

                                    $("#search_user").live('submit', function() {
                                        var ss = [];
                                        var mode_val = $(mode).val();
                                        var arr_mode = mode_val.split(",");
                                        var rows = $('#dg').datagrid('getSelections');
                                        for (var i = 0; i < rows.length; i++) {
                                            var row = rows[i];
                                            if (arr_mode.indexOf(row.user_id) == -1)
                                                ss.push(row.user_id + ',');
                                        }
                                        $(mode).val(mode_val + '' + ss.join(''));
                                        $.fancybox.close();
                                        return false;
                                    }); //end

                                    $(".placeholder").click(function() {
                                        $('#main_text').html($('#main_text').val() + ' ' + $(this).text());

                                    });

                                    $(".form-horizontal").ajaxForm({dataType: 'json', success: processJson, beforeSubmit: validate, error: errorProcess, complete: completeProcess});
                                    $(".form-horizontal").submit(function(event) {
                                    });

                                    function validate() {
                                        block.show('Saving data');
                                    }

                                    function processJson(data) {
                                        if (data.status == 'OK') {
                                            coolNotification('<h3>Success!</h3>Data saved successfully.', 'success', function() {
                                                window.close();
                                                opener.$('#dg').datagrid('reload');
                                            });
                                        } else {
                                            coolNotification(data.hasil, 'error');
                                        }
                                    }

                                    function errorProcess(xhr, status, message) {
                                        coolNotification('<h3>Error!</h3> An error occured, please try again.', 'error');
                                    }

                                    function completeProcess() {
                                        block.close();
                                    }

                                    if ($('.prettyprint').length)
                                        prettyPrint();

                                    if ($('textarea.wysihtml5').size() > 0)
                                        $('textarea.wysihtml5').wysihtml5();

                                    $('.wysihtml5-toolbar').find('.icon-white').each(function() {
                                        $(this).removeClass('icon-white').addClass('icon-black');
                                    })

                                    $('.open').click(function() {
                                        mode = '#' + $(this).attr('txt');
                                    })
                                    $('.txt_email').focusin(function() {
                                        mode = '#' + $(this).attr('id');
                                    })

                                    $('#select-all').click(function() {
                                        var isChecked = $('#select-all').attr('checked');
                                        if (isChecked == 'checked')
                                            $('.cb-user').attr('checked', 'checked');
                                        else
                                            $('.cb-user').removeAttr('checked');
                                    });

                                });


                                var idCurrent = $('.select_site_id').last().attr('value');

                                function searching2(idx) {

                                    var y = window.scrollY;

                                    $('#ddv-' + idx).datagrid('load', {
                                        q: $('#srcSite2-' + idx).val(),
                                    });

                                    $("html, body").animate({scrollTop: y}, "slow");
                                }

        </script>
    </body>
</html>