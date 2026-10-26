<?php

?>
<style>
.pointer{
	cursor: pointer;
}
</style>
<div class="innerLR">
	<div class="widget widget-tabs">
		<B>REPORT EXCEL</B>
		<br>
		<?php if($type == 109 || $type == 110){ //SOW 1 atau SOW 2 ?>
			<ul>Form Material Dismantle
				<li><a href="<?php echo base_url('report/export/return_export').'/'.$id.'/'.$type ?>">Return From</a></li>
				<li><a href="<?php echo base_url('report/export/reloc_export').'/'.$id.'/'.$type ?>">Reloc From</a></li>
				<li><a href="<?php echo base_url('report/export/scrap_export').'/'.$id.'/'.$type ?>">Scrap From</a></li>
			</ul>

			<ul>Form Template ARF_NVAL Dismantle
				<li><a href="<?php echo base_url('report/export/data').'/'.$id.'/'.$type ?>">DATA</a></li>
				<li><a href="<?php echo base_url('report/export/list_mltn').'/'.$id.'/'.$type ?>">List Material MLTN</a></li>
				<li><a href="<?php echo base_url('report/export/list_mle').'/'.$id.'/'.$type ?>">List Material MLE & MLHC</a></li>
			</ul>

			<ul>SIM-T_IMS
				<li><a href="<?php echo base_url('report/export/simf_instalasi').'/'.$id.'/'.$type ?>">SIMF (Instalasi)</a></li>
				<li><a href="<?php echo base_url('report/export/simf_dismantle').'/'.$id.'/'.$type ?>">SIMF (Dismantle)</a></li>
				<li><a href="<?php echo base_url('report/export/simf_antenna').'/'.$id.'/'.$type ?>">IMS (Antenna)</a></li>
				<li><a href="<?php echo base_url('report/export/ims_idu').'/'.$id.'/'.$type ?>">IMS (IDU)</a></li>
				<li><a href="<?php echo base_url('report/export/ims_odu').'/'.$id.'/'.$type ?>">IMS (ODU)</a></li>
				<li><a href="<?php echo base_url('report/export/ims_connection').'/'.$id.'/'.$type ?>">IMS (Connection)</a></li>
			</ul>
		<?php }elseif($type == 108){ //SOW 5 ?>
			<ul>ATP LOS SURVEY
				<li><a href="<?php echo base_url('report/export/los_survey').'/'.$id.'/'.$type ?>">Los Survey</a></li>
			</ul>
		<?php }elseif($type == 116){ //SOW 9 ?>
			<ul>ATP SITE SURVEY
				<li><a href="<?php echo base_url('report/export/pre_survey').'/'.$id.'/'.$type ?>">Pre Survey</a></li>
			</ul>
		<?php }?>
	</div>
</div>