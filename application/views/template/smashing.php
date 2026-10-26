<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
  <head>
    <title>E-ATP</title>

    <!-- Meta -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- JQuery v1.8.2 -->
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
    <?php if ($this->uri->segment(1) != 'isr') { ?>
    <!-- JQueryUI v1.9.2 -->
    <script src="<?php echo base_url('file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.min.css" />
    <?php } ?>
    <!-- FANCY BOX-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/scripts/fancybox/jquery.fancybox.css" type="text/css" media="screen">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/fancybox/jquery.fancybox.js" ></script>

    <!-- Bootstrap -->
    <link href="<?php echo base_url() ?>file/template/smashing/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>file/template/smashing/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />

    <!-- Bootstrap Extended -->
    <link href="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap-responsive.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">

    <!-- Glyphicons -->
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/css/glyphicons.css" />

    <!-- Bootstrap Extended -->
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />

    <!-- Uniform -->
    <link rel="stylesheet" media="screen" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/pixelmatrix-uniform/css/uniform.default.css" />

    <style>
      #footers {
        max-width: 100%;
        height:150px;
        position:fixed;
        bottom:0;
        right:0;

      }

      #footers-wrapper {
        margin: 0 auto;
        max-width: 960px;
      }
    </style>

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

    <script src="<?php echo base_url('/assets/js/menu.js') ?>"></script>

    <!-- Block UI -->
    <script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>

    <?php
    #tentukan mana yg boleh tampil& tdk
    echo $this->mglobal->get_prev_kolom();
    ?>
    <?php $this->load->view('template/partials/eatp_theme'); ?>
  </head>
  <body class="eatp">

    <!-- Start Content -->
    <div class="container-fluid">

      <div class="navbar main">
        <a class="appbrand" href="<?php echo base_url() ?>">
			<span>
				<h3 style="font-family:Impact; color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;T&nbsp;&nbsp;&nbsp;P</h3>
			</span>
		</a>
        <button type="button" class="btn btn-navbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
        </button>

        <ul class="topnav pull-right">
          <!--
          <li class="visible-desktop">
              <ul class="notif">
                  <li><a href="" class="glyphicons envelope" data-toggle="tooltip" data-placement="bottom" data-original-title="5 new messages"><i></i> 5</a></li>
                  <li><a href="" class="glyphicons shopping_cart" data-toggle="tooltip" data-placement="bottom" data-original-title="1 new orders"><i></i> 1</a></li>
                  <li><a href="" class="glyphicons log_book" data-toggle="tooltip" data-placement="bottom" data-original-title="3 new activities"><i></i> 3</a></li>
              </ul>
          </li>
          <li class="dropdown visible-desktop">
              <a href="" data-toggle="dropdown" class="glyphicons cogwheel"><i></i>Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu pull-right">
                  <li><a href="">Some option</a></li>
                  <li><a href="">Some other option</a></li>
                  <li><a href="">Other option</a></li>
              </ul>
          </li>

          <li class="dropdown visible-desktop">
              <a href="" data-toggle="dropdown" class="glyphicons google_maps"><i></i>Region: <?php echo $this->session->userdata('region_name') ?> <span class="caret"></span></a>
              <ul class="dropdown-menu pull-right">
                  <li><a href="#">Cluster OPS: <b><?php echo $this->session->userdata('cluster_ops_name') ?> </a></b></li>
                  <li><a href="#">Cluster COM: <b><?php echo $this->session->userdata('cluster_com_name') ?> </a></b></li>
                  <li><a href="#">Cluster NET: <b><?php echo $this->session->userdata('cluster_net_name') ?> </a></b></li>
          <?php
          $ter = array('2', '3', '5', '6');
          if (in_array(2, $ter)) {
            echo'<li><a href="' . base_url() . '">[Pilih Region/Cluster] </a></li>';
          }
          ?>
              </ul>
          </li>
          -->

          <?php
          $support_menus = $this->mmenu->getSupportMenu();
          if (count($support_menus) > 0) {
            ?>
            <li class="dropdown visible-desktop">
              <a href="" data-toggle="dropdown" class="glyphicons lightbulb"><i></i>Support <span class="caret"></span></a>
              <ul class="dropdown-menu pull-right">
                <?php foreach ($support_menus as $menu) { ?>
                <li><a href="<?php echo base_url($menu['uri']); ?>"><?php echo $menu['title'] ?></a></li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>

          <li class="account">
            <a data-toggle="dropdown" href="#" class="glyphicons logout user"><span class="hidden-phone text"><?php echo $this->session->userdata('user_name') ?></span><i></i></a>
            <ul class="dropdown-menu pull-right">

              <li><a href="<?php echo base_url() ?>user/ChangePassword" class="glyphicons lock">Change Password<i></i></a></li>
              <li><a href="<?php echo base_url() ?>user/ChangeSecurityQuestion" class="glyphicons circle_question_mark">Change Security Question<i></i></a></li>
              <li class="highlight profile">
                <span>
                  <span class="heading">Profile <a href="<?php echo base_url() ?>user/EditProfile" class="pull-right">edit</a></span>
                  <span class="img"></span>
                  <span class="details">
                    <a href="#"><?php echo $this->session->userdata('user_name') ?></a>
                    <?php echo $this->session->userdata('email') ?>
                  </span>
                  <span class="clearfix"></span>
                </span>
              </li>
              <li>
                <span>
                  <a class="btn btn-default btn-small pull-right" style="padding: 2px 10px; background: #fff;" href="<?php echo base_url() ?>login/logout">Sign Out</a>
                </span>
              </li>
            </ul>
          </li>
        </ul>
      </div>

      <div id="wrapper">
        <div id="menu" class="hidden-phone">
          <span class="profile">
            <a style="width:100px;margin-bottom:10px;display:block;margin-left:50px;" href="<?php echo base_url() ?>"><img src="<?php echo base_url() ?>file/images/logoeatp2.png" height="90" width="100" alt="Logo Eatp" /></a>
          </span>
          <div class="clearfix" style="clear: both"></div>
          <ul>
            <?php if($this->session->userdata('ugrup') == 'System Admin'){
            ?>
            <li class="glyphicons home">
               <a href="<?php echo base_url() ?>"> <i></i><span>Dashboard</span></a>
             </li>
            <?php
            }
            ?>



           <!--<li class="hasSubmenu glyphicons home">
              <a href="<?php echo base_url() ?>"><i></i><span>Dashboard</span></a>
            </li>-->
            <?php
            $getIdSistem = $this->mmenu->getIdSistem();
            $getMenuSamping = $this->mmenu->getMenu($getIdSistem);
            //print_r($getMenuSamping);
            foreach ($getMenuSamping as $r) {
              $getSubMenu = $this->mmenu->getMenu($r->menu_id);
              echo"<li class='hasSubmenu'>
						<a data-toggle='collapse' class='glyphicons " . $r->icon . "' href='#menu_" . $r->menu_id . "'><i></i><span>" . $r->name . "</span></a>";
              if (count($getSubMenu) > 0) {
                echo"<ul class='collapse' id='menu_" . $r->menu_id . "'>";
                foreach ($getSubMenu as $s) {
                  $getSubSubMenu = $this->mmenu->getMenu($s->menu_id);

                  if (count($getSubSubMenu) > 0) {
                    echo"<li class='hasSubmenu'>";
                    //ISR has submenu in submenu
                    if ($s->alias == "isr") {
                      if ($s->alias == $this->uri->uri_string())
                        echo "<a class='active active-menu' href='" . base_url() . $s->alias . "'><span>$s->name</span></a>";
                      else
                        echo "<a  href='" . base_url() . $s->alias . "'><span>$s->name</span></a>";
                    }else {
                      echo "<a data-toggle='collapse' class='glyphicons  " . $r->icon . "' href='#menu_$s->menu_id'><i></i><span>$s->name</span><span class='count'>" . count($getSubSubMenu) . "</span></a>";
                    }
                    echo "<ul class='collapse' id='menu_$s->menu_id'>";
                    foreach ($getSubSubMenu as $t) {
                      if ($t->alias == $this->uri->uri_string()) {
                        echo"<li class='active active-menu'><a href='" . base_url() . $t->alias . "'><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$t->name</span></a></li>";
                      } else {
                        echo"<li class=''><a href='" . base_url() . $t->alias . "'><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$t->name</span></a></li>";
                      }
                    }
                    echo"</ul></li>";
                  } else {
                    if ($s->alias == $this->uri->uri_string()) {
                      echo"<li class='active active-menu'><a href='" . base_url() . $s->alias . "'><span>" . $s->name . "</span></a></li>";
                    } else {
                      echo"<li class=''><a href='" . base_url() . $s->alias . "'><span>" . $s->name . "</span></a></li>";
                    }
                  }
                }
                echo"</ul>";
              }
              echo"<span class='count'>" . count($getSubMenu) . "</span>";
              echo"</li>";
            }
            ?>
            
          </ul>
          <div class="clearfix" style="clear: both"></div>
        </div>
        <div id="content">
          <?php echo $this->mmenu->getBreadcrumb() ?>


          <?php
          $this->load->view($content_id);
          ?>


          <!-- End Content -->
        </div>
        <div id="footers">
        <!-- <img src="<?php echo base_url() ?>file/images/footer.png"> -->
        </div>
        <!-- End Wrapper -->
      </div>

    </div>


    <!-- JQueryUI Touch Punch -->
    <!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- MiniColors -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery-miniColors/jquery.miniColors.js"></script>

    <!-- Themer -->
    <script>
      var themerPrimaryColor = '#47759e';
    </script>
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery.cookie.js"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/themer.js"></script>

    <!-- jQuery Validate -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/form_validator.js"></script>

    <!-- Resize Script
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery.ba-resize.js"></script>
    -->
    <!-- Uniform -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/pixelmatrix-uniform/jquery.uniform.min.js"></script>

    <!-- Bootstrap Script -->
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/js/bootstrap.min.js"></script>

    <!-- Bootstrap Extended -->
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-select/bootstrap-select.js"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/jasny-bootstrap/js/jasny-bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/jasny-bootstrap/js/bootstrap-fileupload.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootbox.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>file/template/smashing/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js" type="text/javascript"></script>

    <!-- Custom Onload Script -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/load.js"></script>

    <!-- google-code-prettify -->
    <script src="<?php echo base_url() ?>file/template/smashing/theme/scripts/google-code-prettify/prettify.js"></script>

    <?php if ($this->uri->segment(1) == 'isr') { ?>
    <!-- JQueryUI v1.9.2 -->
    <script src="<?php echo base_url('file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.min.css" />
    <?php } ?>

    <script>
      var baseURL = '<?php echo base_url(); ?>';
      $(function() {
        if ($('.prettyprint').length)
          prettyPrint();
      });

      mainMenu();
    </script>
  </body>
</html>
