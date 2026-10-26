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
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<!-- Bootstrap -->
	<link href="<?php echo base_url()?>file/template/smashing/bootstrap/css/bootstrap.min.css" rel="stylesheet" />


	<!-- JQueryUI v1.9.2 -->
	<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.min.css" />

	<!-- Glyphicons -->
	<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/css/glyphicons.css" />

	<!-- JQuery v1.8.2 -->
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/jquery-1.8.2.min.js"></script>

	<!-- Theme -->
	<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/css/style.min.css?62656687" />
	<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/css/style.red.css?62656687" />

	<?php $this->load->view('template/partials/eatp_theme'); ?>
</head>
<body class="eatp eatp-auth">

	<!-- Start Content -->
	<div class="container-fluid login">

		<div class="navbar main">
			<a href="#" class="appbrand"><span>E-ATP Network</span></a>
		</div>

		<div class="separator bottom"></div>
		<div class="innerLR">
			<?php
			$this->load->view( $content_id );
			?>
		</div>
	</div>

	<!-- JQueryUI v1.9.2 -->
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>

	<!-- JQueryUI Touch Punch -->
	<!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>


	<!-- Themer -->
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/jquery.cookie.js"></script>

	<!-- Uniform -->
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/pixelmatrix-uniform/jquery.uniform.min.js"></script>

	<!-- Bootstrap Script -->
	<script src="<?php echo base_url()?>file/template/smashing/bootstrap/js/bootstrap.min.js"></script>

	<!-- Bootstrap Extended -->
	<script src="<?php echo base_url()?>file/template/smashing/bootstrap/extend/bootstrap-select/bootstrap-select.js"></script>
	<script src="<?php echo base_url()?>file/template/smashing/bootstrap/extend/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>





	<!-- Custom Onload Script -->
	<script src="<?php echo base_url()?>file/template/smashing/theme/scripts/load.js"></script>




	<!-- Bootstrap Form Wizard -->
	<script src="<?php echo base_url()?>file/template/smashing/bootstrap/extend/twitter-bootstrap-wizard/jquery.bootstrap.wizard.js"></script>
	<script type="text/javascript">
	$(function()
	{
		var bWizardTabClass = '';
		$('.wizard').each(function()
		{
			if ($(this).is('#rootwizard'))
				bWizardTabClass = 'bwizard-steps';
			else
				bWizardTabClass = '';

			var wiz = $(this);

			$(this).bootstrapWizard(
			{
				onTabShow: function(tab, navigation, index)
				{
					var $total = navigation.find('li').length;
					var $current = <?php echo $tahap?>;
					var $percent = ($current/$total) * 100;
					wiz.find('.bar').css({width:$percent+'%'});

					wiz.find('.bar')
						.find('.step-current').html($current)
						.parent().find('.steps-total').html($total)
						.parent().find('.steps-percent').html(Math.round($percent) + "%");

					// If it's the last tab then hide the last button and show the finish instead

				},
				tabClass: bWizardTabClass,
				nextSelector: '.next',
				previousSelector: '.previous',
				firstSelector: '.first',
				lastSelector: '.last'
			});


		});
	});
	</script>

</body>
</html>
