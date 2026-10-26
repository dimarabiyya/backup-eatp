	<style type="text/css">
		select{width:auto;}
	 	table input[type="checkbox"]{
	 		margin: -3px 5px 0 0;
	 	}
	.widget { border:none; }
	.form-horizontal .control-label { text-align:left; }
	fieldset { margin-bottom:20px; border-bottom:1px solid #CCCCCC; padding:0px; }
	legend { border-bottom:1px solid #CCCCCC; margin:0 0 4px; }
	legend + .control-group { margin-top:0px; }
	.form-horizontal .column-left { float:left; }
	.form-horizontal .column-right { float:left; margin-left:80px; }
	.form-horizontal .control-group { margin-bottom:4px; }
	.form-horizontal .control-group:last-child { margin:0 0 4px; }
	.btn { padding:4px 10px; }
	.table-primary thead th { font-size:12px; }
	.panel { background:#FFFFFF; }
	fieldset{margin-top:0px;margin-bottom:none;border-bottom: none;}
	legend{margin-bottom:10px;}
	.equipment{display: none;}
	.sinfo{font-size:10px;color:gray;}
	</style>
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
					

<form class="form-horizontal" method="POST" id="search-form" >
	<div class="widget">
		<div class="widget-body">
			<div class="row-fluid">
					<div class="control-group" id="form_order">
						<div class="controls">
							
						<select class="span3" name="option_id" id ="option_id">
								<option value="">--Choose Template Option--</option>
										<?php
										foreach($combo_option as $value){
											if(($value['option_id'])==$option_id){
												$selected='selected';
													}else{
												$selected='';
											}	
											echo'<option value='.$value['option_id'].' '.$selected.'>'.$value['option_label'].'</option>';
										}
								?>
						</select>
						<input class="span6" id="q" name="q" type="text" value="<?php echo $q;?>" />
						&nbsp;<button type="button" onclick="search()"><i class="icon-search"></i>Search</button>
						&nbsp;<button type="button" class="" onclick="closeFancybox()"><i></i>Close</button>
						</div>
					</div>
			</div>
			
			
			
					        <table id="dyntable" class="table table-bordered responsive">
                    <colgroup>
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                    </colgroup>
                    <thead>
                        <tr>
                        	<th class="head0">Hierarchy</th>
                            <th class="head1">Description</th>
                            <th class="head2" width="50px">Type</th>
                            <th class="head4" width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbodySearch">
     
                    </tbody> 
				</table>						
                        <hr>
                      
		</div>	
	</div>
</form>					
			
				
		
			</div></div></div></div></div>	
<script>

$( document ).ready(function() {
	var x = $('#search-form').serialize();
	// console.log(x);
	$.ajax({
		type: "POST",
		// url: "<?php echo base_url()?>referensi/pm_checklist/searchData",
		url: "<?php echo base_url()?>preventive/pm_checklist_template/searchData",

		data: $('#search-form').serialize(),
		// dataType: "json",
		beforeSend:function(){
		},
		error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
		},
		success: function(data){
			// console.log(data);
			//$('tbody').append(data);
			$('#tbodySearch').append(data);

		}
	});
});


function search() {
	var x = $('#search-form').serialize();

	$.ajax({
		type: "POST",
		//url: "<?php echo base_url()?>referensi/pm_checklist/searchData",
		url: "<?php echo base_url()?>preventive/pm_checklist_template/searchData",

		data: $('#search-form').serialize(),
		// dataType: "json",
		beforeSend:function(){
		},
		error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
		},
		success: function(data){
			// console.log(data);
			$('#tbodySearch').children().remove();
			$('#tbodySearch').append(data);
		}
	});
}

</script>