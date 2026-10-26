<script type="text/javascript">
$(document).ready(function(){
  $("#formku").hide();
});
</script>
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


<button type="button" id="toggleforms"> Open/Close Form</button>

<div id="formku">
<form action="<?php echo base_url('visitsummary/pro_upload_doc'); ?>" method="POST" enctype="multipart/form-data">
<h3 align="center"> Upload Your Document Here... </h3>


<input type="hidden" name="not_dec_id" value="<?php echo $not_dec_id;?>">
<input type="hidden" name="idvisit" value="<?php echo $id;?>">
<table class="table table-bordered table-condensed">
<tr>
<td> Category File :  </td>
<td> : </td>
<td> <select name="category">
	 <option value=""> - </option>
	 <option value="simf_instalasi"> SIMF (Instalasi) </option>
	 <option value="simf_dismantle"> SIMF (Dismantle) </option>
	 <option value="ims_antenna"> IMS (Antenna) </option>
	 <option value="ims_idu"> IMS (IDU) </option>
	 <option value="ims_odu"> IMS (ODU) </option>
	 <option value="ims_connection"> IMS (Connection) </option>
	 </select>  </td>
</tr>
<tr>
<td> Upload File (Just XLS or XLSX format can be upload!) :  </td>
<td> : </td>
<td> <input type="file" id="docs" name="docs" > <input type="hidden" name="fdocs" id="fdocs" >      </td>
</tr>
<tr>
<td colspan="3"> <button id="submit" type="submit" name="submit" class="btn btn-block btn-primary"> Upload </button> </td>
</tr>
</table>
</form>
<br>
</div>
<h3 align="center"> List File Uploads </h3>
<br>
<table class="table table-bordered table-condensed">
    <tr>
        <td> No </td>
        <td> Filename </td>
		<td> Category </td>
        <td> Dateupload </td>
        <td> Opsi </td>
    </tr>
    <?php
    $no=1;
    foreach($listupload as $row){
    ?>
    <tr>
        <td> <?php echo $no; ?> </td>
        <td> <?php echo "<a target='_blank' href=" . base_url('/file/upload/' . $row->fdocs) . "> ".$row->fdocs." </a>"; ?> </td>
        <td> <?php echo ucwords(str_replace("_"," ",$row->category)); ?> </td>
		<td> <?php echo $row->date_update == null ? $row->date_insert : $row->date_update; ?> </td>
        <td> <a href="<?php echo base_url('visitsummary/del_upload_list/'.$row->id.'/'.$this->uri->segment(3))?>"> Delete </a>  </td>
    </tr>
    <?php
    $no++;}
    ?>
</table>



<script type="text/javascript">

                $("#toggleforms").on("click",function(){
                   $("#formku").toggle("slow");
                });
		$("#docs").on("change",function(){
			 var filename = $('#docs').val().replace(/C:\\fakepath\\/i, '')
			$("#fdocs").val(filename);
		})

</script>
