<div class="innerLR" ng-app="mainApp">
	<ng-view></ng-view>
</div>

<script src="<?php echo base_url('assets/js/angular-1.5.8/angular.js') ?>"></script>
<script src="<?php echo base_url('assets/js/angular-1.5.8/angular-route.min.js') ?>"></script>
<link href="<?php echo base_url('assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('assets/js/number_format.js') ?>"></script>

<style>
.datagrid-header-rownumber { width:50px; }
.datagrid-cell-rownumber { width:50px; }
select { width:auto; }
</style>
 <form action="<?php echo base_url('visitsummary/pro_upload_doc'); ?>" method="POST" enctype="multipart/form-data">
<h3 align="center"> Upload Your Document Here... </h3>


<input type="hidden" name="not_dec_id" value="<?php echo $not_dec_id;?>">
<input type="hidden" name="idvisit" value="<?php echo $id;?>">
<table class="table table-bordered table-condensed">
<tr>
<td> Upload File :  </td>
<td> : </td>
<td> <input type="file" id="docs" name="docs" > <input type="text" name="fdocs" id="fdocs" >  <?php echo $files; ?>  </td>
</tr>
<tr>
<td colspan="3"> <button id="submit" type="submit" name="submit" class="btn btn-block btn-primary"> Upload </button> </td>
</tr>
</table>
</form>

<script type="text/javascript">

		$("#docs").on("change",function(){
			 var filename = $('#docs').val().replace(/C:\\fakepath\\/i, '')
			$("#fdocs").val(filename);
		})

</script>
