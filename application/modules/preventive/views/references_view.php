
<div class="heading-buttons">
	<h3><?php echo $judul?><span class="hidden-phone">|<em> <?php echo $juduldetail?></em></span></h3>
</div>
<div class="separator bottom"></div>
<div class="innerLR">
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid" id="link">
				<?php
							$getMenu = get_instance()->mreferences->getMenu(164);
							foreach($getMenu as $z){
								echo"<div style='margin:0 0 10px 10px' class='span2'>
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
<script>
$(document).ready(function(){
	$("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
});
</script>