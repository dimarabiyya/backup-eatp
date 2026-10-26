<!--
<div class="heading-buttons">
	<h3>Report <span>| <?php echo $title ?></span></h3>
</div>
-->

<div class="innerLR">	
	<div class="widget">
	
		<div class="widget-head">
			<h4 class="heading"><i></i> <?php echo $subtitle ?></h4>
		</div>
		
		<div class="widget-body">
		
			<div class="row-fluid">
				<div id="canvas" class="row-fluid"></div>                            
			</div>
		
		</div>
		
	</div>
</div>

<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script>
var showReport = function(src, height) {
	var html = '<iframe src="' + src + '" width="100%" height="' + height + '" align="center" frameborder="0"></iframe>';
	document.getElementById('canvas').innerHTML = html;
};

showReport('<?php echo $url ?>', '<?php echo $height ?>');

mainMenu();
/* var idxSelectedMenu = getCookie('idxSelectedMenu');
var menu = document.getElementById('menu');
var menuParent = menu.children[2].children[1].children[1];
menuParent.children[idxSelectedMenu].children[1].setAttribute('class', 'in collapse'); */
</script>