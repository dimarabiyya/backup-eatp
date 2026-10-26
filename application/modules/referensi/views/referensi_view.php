                

        <?php 
        
        if (get_instance()->session->userdata('group')!='4'){?>
		<div class="heading-buttons">
	<h3>Reference Table</h3>
	<div class="clearfix" style="clear: both;"></div>
</div><div class="separator bottom"></div>
		<div class="innerLR">
				<div class="row-fluid">
				<div class="span12">
				<div class="row-fluid">
				<div class="separator bottom"></div>

						<?php
							$menus = get_instance()->mmaster->getMenuSistem(68,"");
							foreach($menus->result() as $r) {
								echo'<div class="span2"><a class="widget-stats" href="'.site_url($r->alias).'"><span class="glyphicons database_plus"><i></i></span><span class="txt">'. $r->name .'</span><div class="clearfix"></div></a></div>';
							}
						?>
					
				
						</div>
			</div>	</div>
			</div>
				<?php } ?>
		
		
		
<!--nm-->