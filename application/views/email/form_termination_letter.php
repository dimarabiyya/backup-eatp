<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
  <head>
    <title>Email - Smartfren Network</title>

    <!-- Meta -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">




    <!-- JQuery v1.8.2 -->
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
    <!-- JQueryUI v1.9.2 -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>



    <!-- Bootstrap -->
    <link href="<?php echo base_url() ?>file/template/smashing/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>file/template/smashing/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />

    <!-- Bootstrap Extended -->
    <link href="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap-responsive.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">

    <!-- JQueryUI v1.9.2 -->
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.min.css" />

    <!-- Glyphicons -->
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/css/glyphicons.css" />

    <!-- Bootstrap Extended -->
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />


    <!-- Modernizr -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/modernizr.custom.76094.js"></script>

    <!-- MiniColors -->
    <link rel="stylesheet" media="screen" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery-miniColors/jquery.miniColors.css" />

    <!-- Notyfy -->
    <script type="text/javascript" src="<?php echo base_url() ?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.js"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css"/>
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/notyfy/themes/default.css"/>

    <!-- Gritter -->
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/Gritter/css/jquery.gritter.css"/>
    <script type="text/javascript" src="<?php echo base_url() ?>file/template/smashing/theme/scripts/Gritter/js/jquery.gritter.min.js"></script>


    <!-- google-code-prettify -->
    <link href="<?php echo base_url() ?>file/template/smashing/theme/scripts/google-code-prettify/prettify.css" type="text/css" rel="stylesheet" />

    <!-- Theme -->
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/css/style.min.css" />
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/css/style.red.css" />

    <!-- LESS 2 CSS -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/less-1.3.3.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/scripts/jeasyui/icon.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/jeasyui/datagrid-detailview.js"></script>



    <style type="text/css">
      .datagrid-body .datagrid-cell{
        word-wrap: break-word;
        white-space: normal;
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
        height: 30px;
        margin: 5px 5px;
        min-width: 75px;
      }
    </style>


  </head>
  <body>
    <script language="javascript" src="<?php echo base_url(); ?>assets/js/jquery.form.js"></script>
    <script src="<?php echo base_url() ?>assets/js/overlay.js"></script>

    <form class="form-horizontal" method="post" action="<?php echo base_url(); ?>email/send_termination_letter">
      <input name="link_id_code" type="hidden" value="<?php echo $link_id_code; ?>">
      <div class="widget">
        <div class="widget-head" style="height:40px">
          <h4 class="heading">Send Termination Letter</h4>
          <button type="submit" class="btn-email submit pull-right"><i></i>Send</button>
        </div>
        <div class="widget-body" style="padding:0px">
          <div class="row-fluid">
            <div class="control-group">
              <label class="control-label open" txt="txt_to"
                     style="text-align: left;width:auto" 
                     title="Click to add user">To :</label>
              <div class="controls" style="margin-left:0px">
                <input name="to" class="txt_email" type="text" userid="" id="txt_to" autocomplete="off" value="" >
              </div>
            </div>	
            <div class="control-group">
              <label class="control-label open"  txt="txt_cc"
                     style="text-align: left;width:auto" 
                     title="Click to add user">Cc :</label>
              <div class="controls" style="margin-left:0px">
                <input name="cc" class="txt_email" type="text" userid="" id="txt_cc" autocomplete="off" value="<?php echo $this->session->userdata('user_id')?>," >
              </div>
            </div>	
            <div class="control-group">
              <label class="control-label" style="text-align: left;width:auto">Subject :</label>
              <div class="controls" style="margin-left:0px">
                <input class="btnclose" name="subject" type="text" id="txt_subject" value="<?php echo $subject; ?>" >
              </div>
            </div>	


          </div>
          <div class="row-fluid">
            <textarea name="content" id="mustHaveId" class="btnclose wysihtml5" style="height:300px;width:98%;border:0px">
              Mohon mereview beberapa double link berikut ini : <br>
            <ul>
              <li>Link ID : <?php echo $header->link_id_code; ?></li>
              <li>Site ID NE : <?php echo $header->ne_site_code; ?></li>
              <li>Site ID FE : <?php echo $header->fe_site_code; ?></li>  
              <li>Vendor NE : <?php echo $header->ne_vendor; ?></li>
              <li>Vendor FE : <?php echo $header->fe_vendor; ?></li>
              <li>Region : <?php echo $header->region; ?></li>
            </ul>
            <ol>
              <?php 
                foreach ($detail as $value) {
                  
                  if ($value->status_active == 1)
                    $value->status_active = "Active";
                  elseif ($value->status_active == 2)
                    $value->status_active = "On proccess";
                  else
                    $value->status_active = "Not Active";
                  
                  echo "<li>
                    <ul>
                    <li>App No : {$value->app_no}</li>
                    <li>Station No : {$value->station_no}</li>
                    <li>Status : {$value->status_active}</li>
                    <li>App Issued : {$value->app_issued}</li>
                    <li>App Expired : {$value->app_expired}</li>
                    <li>Remark : {$value->remark}</li>
                    </ul>
                    </li>";
                }
              ?>  
            </ol>  
            </textarea>
          </div>

        </div>
      </div>
    </form>

    <div class="popbox" style="position:absolute">
      <div class='collapsed'>
        <div class='box'>
          <div class='arrow'></div>
          <div class='arrow-border'></div>
          <table id="dg2" class="easyui-datagrid" style="width:700px;height:300px"
                 title= "Please select user to email"
                 url = "<?php echo base_url(); ?>problem/problem/getAllEsc"
                 fitColumns = "true"	
                 toolbar = "#tb2"
                 idField = "user_div_id"
                 textField = "user_div_name"
                 rownumbers = "true"
                 mode = "remote"
                 fitColumns="true">							
            <thead>
              <tr>								
                <th field="user_div_name" width="80">Group Name</th>	
              </tr>
            </thead>

          </table>
        </div>
      </div>
    </div>


    <!-- jQuery Validate -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/form_validator.js"></script>
    <!-- Bootstrap Script -->
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/js/bootstrap.min.js"></script>

    <!-- Bootstrap Extended -->
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-select/bootstrap-select.js"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootbox.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js" type="text/javascript"></script>


    <!-- google-code-prettify -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/google-code-prettify/prettify.js"></script>
    <script src="<?php echo base_url('assets/js/hehehe.js'); ?>"></script>
    <script>
      var mode = "#txt_to";
      var prevCtrl;
      $(function() {

        $(".form-horizontal").ajaxForm({dataType: 'json', success: processJson, beforeSubmit: validate});
        $(".form-horizontal").submit(function(event) {
        });

        function validate() {
          if (confirm('Are you sure want to send email ?')) {
            siteOverlay.top = '200px';
            siteOverlay.create();
          } else {
            return false;
          }
        }
        function processJson(data) {
          siteOverlay.remove();
          if (data != null && data.status == 'OK') {
            window.opener.doSearch(3);
            alert("Email sent!");
            window.close();
          } else {
            alert(data.hasil);
          }
        }


        if ($('.prettyprint').length)
          prettyPrint();

        if ($('textarea.wysihtml5').size() > 0)
          $('textarea.wysihtml5').wysihtml5();

        $('.wysihtml5-toolbar').find('.icon-white').each(function() {
          $(this).removeClass('icon-white').addClass('icon-black');
        })

        $('.popbox').popbox();

        $('.open').click(function() {
          mode = '#' + $(this).attr('txt');
        })
        $('.txt_email').focusin(function() {
          mode = '#' + $(this).attr('id');
        })

      });


      (function() {

        $.fn.popbox = function(options) {
          var settings = $.extend({
            selector: this.selector,
            open: '.open',
            txt_email: '.txt_email',
            box: '.box',
            arrow: '.arrow',
            arrow_border: '.arrow-border',
            popbox: '.popbox',
            close: '.btnclose'
          }, options);

          var methods = {
            open: function(event) {
              event.preventDefault();

              var pop = $(this);
              var box = $(document).find(settings['box']);
              var popbox = $(document).find(settings['popbox']);

              // box.find(settings['arrow']).css({'left': box.width()/2 - 10});
              // box.find(settings['arrow_border']).css({'left': box.width()/2 - 10});

              box.find(settings['arrow']).css({'left': pop.position().left});
              box.find(settings['arrow_border']).css({'left': pop.position().left});

              if (box.css('display') == 'block' && pop == prevCtrl) {
                methods.close();
              } else {
                box.css({'display': 'block'});
                popbox.css({'top': $(this).position().top + 35, 'left': $(this).position().left});
                prevCtrl = pop;
                $('#dg2').datagrid('load');

              }
            },
            close: function() {
              $(settings['box']).fadeOut("fast");
            }
          };

          $(document).bind('keyup', function(event) {
            if (event.keyCode == 27) {
              methods.close();
            }
          });

          $(document).bind('click', function(event) {
            // if(!$(event.target).closest(settings['selector']).length){
            //   methods.close();
            // }

          });

          return this.each(function() {
            $(this).css({'width': $(settings['box']).width()}); // Width needs to be set otherwise popbox will not move when window resized.
            $(document).find(settings['open']).bind('click', methods.open);
            $(document).find(settings['txt_email']).bind('focusin', methods.open);
            $(document).find(settings['close']).bind('focusin', methods.close);
            $(document).bind('hover', methods.close);
            $(document).find('iframe').bind('hover', methods.close);
            // $(settings['open'], this).parent().find(settings['close']).bind('click', methods.close);
          });
        }

      }).call(this);



      var idCurrent = $('.select_site_id').last().attr('value');

      function searching2(idx) {

        var y = window.scrollY;

        $('#ddv-' + idx).datagrid('load', {
          q: $('#srcSite2-' + idx).val(),
        });

        $("html, body").animate({scrollTop: y}, "slow");
      }
      $(function() {
        $('#dg2').datagrid({
          view: detailview,
          detailFormatter: function(index, row) {
            return '<div id="tb2-' + index + '" style="padding:2px"> <span>Search :</span><input id="srcSite2-' + index + '" placeholder="Type text to search...." style="line-height:26px;border:1px solid #ccc"><a href="#" class="easyui-linkbutton" plain="true" onclick="searching2(' + index + ')"><i class="icon-cari" style="background-position: -48px 0;"></i></a></div><table id="ddv-' + index + '"></table>';
          },
          onExpandRow: function(index, row) {
            $('#ddv-' + index).datagrid({
              url: "<?php echo base_url(); ?>problem/problem/getEscUser/" + row.user_div_id,
              pagination: true,
              pageSize: 10,
              fitColumns: true,
              checkOnSelect: true,
              selectOnCheck: true,
              checkAll: false,
              //	toolbar = "#tb2-"+index,
              idField: "user_id",
              textField: 'user_name',
              rownumbers: true,
              loadMsg: '',
              height: 'auto',
              columns: [[
                  {field: 'ck', checkbox: true},
                  {field: 'user_id', title: 'User ID', width: 100},
                  {field: 'user_name', title: 'User Name', width: 100}
                ]],
              checkOnSelect : function(data) {
                $.each(data, function(i, item) {
                });
              },
                      onResize: function() {
                $('#dg2').datagrid('fixDetailRowHeight', index);
              },
              onLoadSuccess: function() {
                setTimeout(function() {
                  $('#dg2').datagrid('fixDetailRowHeight', index);
                }, 0);
              },
              onSelect: function(index, row) {
                var userID = row.user_id;
                var user = $(mode).val();
                user = user.replace(userID + ',', '');
                $(mode).val(user + userID + ',');

              },
              onUnselect: function(index, row) {
                var userID = row.user_id;
                var user = $(mode).val();
                user = user.replace(userID + ',', '');
                $(mode).val(user);
              },
              onSelectAll: function(data) {
                $.each(data, function(index, row) {
                  var userID = row.user_id;
                  var user = $(mode).val();
                  user = user.replace(userID + ',', '');
                  $(mode).val(user + userID + ',');
                });
              },
              onUnselectAll: function(data) {
                $.each(data, function(index, row) {
                  var userID = row.user_id;
                  var user = $(mode).val();
                  user = user.replace(userID + ',', '');
                  $(mode).val(user);

                });
              },
            });

            $('#dg2').datagrid('fixDetailRowHeight', index);

          }
        });
      });
    </script>
  </body>
</html>