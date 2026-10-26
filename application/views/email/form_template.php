<?php error_reporting(0); ?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
    <head>
        <title>Email Notification - Smartfren Network</title>

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
        <link rel="stylesheet" href="<?php echo  base_url() ?>assets/scripts/fancybox/jquery.fancybox.css" type="text/css" media="screen">
        <script type="text/javascript" src="<?php echo  base_url() ?>assets/scripts/fancybox/jquery.fancybox.js" ></script>

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

        <!-- Block UI Ttg -->
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.blockUI.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>

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
                font-family: "Helvetica Neue",sans-serif;
                font-weight: 300;
                font-size: 15px;
                line-height: 1.2;
            }
            .control-group{
                border-bottom: solid 1px #D6D6D6;
            }
            .control-label{
                font-size: 15px;
                /*color: #999;*/
            }
            .email_input{
                border:0px;
                box-shadow: none;
                -webkit-box-shadow:none;
                -webkit-transition:none;
                font-family:"Helvetica Neue",sans-serif;
                font-size: 12px;
                color: black;
                font-weight: 400;
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
        <form class="form-horizontal" method="post" action="<?php echo base_url(); ?>referensi/notif_email/saveData">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="widget">
                <div class="widget-head" style="height:40px">
                    <h4 class="heading" style="line-height: 40px; vertical-align: middle; font-size: 16px; font-weight: bold;">Email Notification Data</h4>
                    <button onclick="window.close(); return;" type="button" class="btn-email submit pull-right"><i></i>Cancel</button>
                    <button type="submit" class="btn-email submit pull-right"><i></i>Save</button>
                </div>
                <div class="widget-body" style="padding:0px">
                    <div class="row-fluid">
                        <div class="control-group">
                            <label class="control-label" style="text-align: left;width:auto">Recipient Mode : </label>
                            <div class="controls">
                                <input type="radio" name="recipient_mode" value="1" checked="checked"> Manual (Default)
                                <input type="radio" name="recipient_mode" value="2"> Automatic
                            </div>
                        </div>
                        <div id="recipient_container">
                            <div class="control-group">
                                <label class="control-label search" txt="txt_to"
                                       style="text-align: left;width:auto" 
                                       title="Click to add user">To :</label>
                                <div class="controls" style="margin-left:0px">
                                    <textarea txt="txt_to" name="to" id="txt_to" class="email_input search" style="height: 20px; width: 90%"><?php echo $single['to'] . $division['to']; ?></textarea>
                                </div>
                            </div>	
                            <div class="control-group">
                                <label class="control-label" txt="txt_to_ext" style="text-align: left;width:auto" >To External :</label>
                                <div class="controls" style="margin-left:0px">
                                    <textarea placeholder="For multiuser, separated by comma." txt="txt_to_ext" name="to_external" id="txt_to_ext" class="email_input" style="height: 20px; width: 83%"><?php echo $single['to_ext']; ?></textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label search"  txt="txt_cc"
                                       style="text-align: left;width:auto" 
                                       title="Click to add user">Cc :</label>
                                <div class="controls" style="margin-left:0px">
                                    <textarea txt="txt_cc" name="cc" id="txt_cc" class="email_input search" style="height: 20px; width: 90%"><?php echo $single['cc'] . $division['cc']; ?></textarea>
                                </div>
                            </div>	
                            <div class="control-group">
                                <label class="control-label"  txt="txt_cc_ext" style="text-align: left;width:auto">Cc External:</label>
                                <div class="controls" style="margin-left:0px">
                                    <textarea placeholder="For multiuser, separated by comma." txt="txt_cc_ext" name="cc_external" id="txt_cc_ext" class="email_input" style="height: 20px; width: 83%"><?php echo $single['cc_ext']; ?></textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label search"  txt="txt_bcc"
                                       style="text-align: left;width:auto" 
                                       title="Click to add user">Bcc :</label>
                                <div class="controls" style="margin-left:0px">
                                    <textarea txt="txt_bcc" name="bcc" id="txt_bcc" class="email_input search" style="height: 20px; width: 89%"><?php echo $single['bcc'] . $division['bcc']; ?></textarea>

                                </div>
                            </div>	
                        </div>
                        <div class="control-group">
                            <label class="control-label" style="text-align: left;width:auto">Subject :</label>
                            <div class="controls" style="margin-left:0px;">
                                <input style="width: 85%" class="txt_email email_input" name="subject" type="text" id="txt_subject" value="<?php echo $subject; ?>" >
                            </div>
                        </div>	
                    </div>

                    <div class="row-fluid">
                        <div name="header" id="header_text" style="height:100px;width:98%;border:0px; padding: 5px" class="email_input">
                            <?php echo $header; ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <textarea name="content" id="main_text" class="wysihtml5" style="height:210px;width:98%;border:0px">
                            <?php echo $content; ?>
                        </textarea>
                    </div>

                    <div class="row-fluid">
                        <div name="footer" id="footer_text" style="height:100px;width:98%;border:0px; padding: 5px" class="email_input">
                            <?php echo $footer; ?>
                        </div>
                    </div>

                    <div class="control-group" style="position: fixed;width: 100%;bottom: 0;background: #F1EEEE;">
                        <label class="control-label" style="text-align: left;width:auto">Parameters :</label>
                        <div class="controls" style="margin-left:0px">
                            <?php
                            $x = explode(',', $parameter);
                            foreach ($x as $value) {
                                echo '<span class="placeholder">' . $value . '</span>';
                            }
                            ?>
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


        <!-- google-code-prettify -->
        <script src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/google-code-prettify/prettify.js"></script>
        <!--textarea autosize-->
        <script src="<?php echo  base_url() ?>assets/js/jquery.autosize.js"></script>
        <script type="text/javascript">
                                            var mode = "#txt_to";
                                            var prevCtrl;
                                            var opener = window.opener;
                                            baseURL = '<?php echo base_url();?>';

                                            $(function() {
                                                //added by andedi (20141014), begin
                                                $('.search').live("click", function() {
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
                                                    var newVal = mode_val + '' + ss.join('');
                                                    $(mode).val(newVal);
                                                    $(mode).val(newVal).trigger('autosize.resize');
                                                    $.fancybox.close();
                                                    return false;
                                                });
                                                //end

                                                $(".placeholder").click(function() {
                                                    $('#main_text').html($('#main_text').val() + ' ' + $(this).text());

                                                });

                                                $(".form-horizontal").ajaxForm({dataType: 'json', success: processJson, beforeSubmit: validate, error:errorProcess, complete:completeProcess});
                                                $(".form-horizontal").submit(function(event) {
                                                });

                                                function validate() {
                                                    block.show('Saving data');
                                                }

                                                function processJson(data) {
                                                    if (data.status == 'OK') {
                                                        coolNotification('<h3>Success!</h3>Data saved successfully.', 'success', function () {
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

                                                $('input[name=recipient_mode]:radio').click(function (){
                                                    console.log('tes');
                                                    selectedMode = $(this).val();
                                                    if (selectedMode=='2') {
                                                        $('#recipient_container').slideUp();
                                                    } else {
                                                        $('#recipient_container').slideDown();
                                                    }
                                                });
                                                
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

                                                $('textarea[name="to"]').autosize();
                                                $('textarea[name="to_ext"]').autosize();
                                                $('textarea[name="cc"]').autosize();
                                                $('textarea[name="cc_ext"]').autosize();
                                                $('textarea[name="bcc"]').autosize();

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