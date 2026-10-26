<?php error_reporting(0); ?>
<html>
    <head>
        <title>Mail Notification Template - Smartfren Network</title>

        <!-- Meta -->
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- JQuery v1.8.2 -->
        <script type="text/javascript" src="<?php echo  base_url() ?>assets/js/jquery.js"></script>
        <!-- JQueryUI v1.9.2 -->
        <script src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>

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
                color: #999;
            }
            .form-horizontal input{
                border:0px;
                box-shadow: none;
                -webkit-box-shadow:none;
                -webkit-transition:none;
                font-family:"Helvetica Neue",sans-serif;
                font-size: 15px;
                width:80%;
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


    </head>
    <body>
        <script language="javascript" src="<?php echo  base_url(); ?>assets/js/jquery.form.js"></script>
        <script src="<?php echo  base_url() ?>assets/js/overlay.js"></script>
        <form class="form-horizontal" method="post" action="<?php echo base_url(); ?>referensi/notif_email/set_template/save">
            <div class="widget">
                <div class="widget-head" style="height:40px">
                    <h4 class="heading"  style="line-height: 40px; vertical-align: middle; font-size: 16px; font-weight: bold;">Email Notification Template</h4>
                    <button onclick="window.close();
                            return;" type="button" class="btn-email submit pull-right"><i></i>Cancel</button>
                    <button type="submit" class="btn-email submit pull-right"><i></i>Save</button>
                </div>
                <div class="widget-body" style="padding:0px">
                    <h5 style="margin:5px">Header</h5>
                    <div class="row-fluid">
                        <textarea name="header" id="header_text" class="wysihtml5" style="height:150px;width:98%;border:0px">
<?php echo $header; ?>
                        </textarea>
                    </div>
                    <h5 style="margin:5px">Footer</h5>
                    <div class="row-fluid">
                        <textarea name="footer" id="footer_text" class="wysihtml5" style="height:40px;width:98%;border:0px">
<?php echo $footer; ?>
                        </textarea>
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
        <script src="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js"></script>
        <script src="<?php echo  base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script>
        <!-- Block UI Ttg -->
        <script src="<?php echo  base_url('assets/js/jquery.blockUI.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>

        <!-- google-code-prettify -->
        <script src="<?php echo  base_url() ?>file/template/smashing/theme/scripts/google-code-prettify/prettify.js"></script>
        <script>
                        var mode = "#txt_to";
                        var prevCtrl;
                        baseURL = '<?php echo base_url();?>';

                        $(function() {

                            $(".form-horizontal").ajaxForm({dataType: 'json', success: processJson, beforeSubmit: validate, error: errorProcess, complete: completeProcess});
                            $(".form-horizontal").submit(function(event) {
                            });

                            function validate() {
                                block.show('Saving data');
                            }
                            
                            function processJson(data) {
                                if (data.status == 'OK') {
                                    coolNotification('Data saved.', 'success', function () {
                                        window.close();
                                    });
                                } else {
                                    coolNotification(data.hasil, 'error');
                                }
                            }
                            
                            function errorProcess(xhr, status, message) {
                                coolNotification(status.toUpperCase()+' : '+message+', please try again.', 'error');
                            }

                            function completeProcess() {
                                block.close();
                            }

                            $('#header_text').wysihtml5();
                            $('#footer_text').wysihtml5();

                            $('.wysihtml5-toolbar').find('.icon-white').each(function() {
                                $(this).removeClass('icon-white').addClass('icon-black');
                            })


                        });

        </script>
    </body>
</html>