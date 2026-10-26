<!--
<div class="heading-buttons">
	<h3><?php echo $judul?><span class="hidden-phone">|<em> <?php echo $juduldetail?></em></span></h3>
</div>
<div class="separator bottom"></div>
-->

<div class="innerLR">
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid" id="link">
				<?php
				$getMenu = get_instance()->msite_info_ref->getMenu(334);
				foreach($getMenu as $z){
					$x = explode("/",$z->url);
					$form = $x[count($x)-1];
					echo "<div style='margin:0 10px 10px 0px' class='span2' id='ref_$form'>
						<a href='".base_url().$z->url."' class='widget-stats'>
							<span class='glyphicons ".$z->icon."'><i></i></span>
							<span class='txt'>".$z->name."</span>
							<div class='clearfix'></div>
						</a>
					</div>";
				} ?> 
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script>
mainMenu();
</script>