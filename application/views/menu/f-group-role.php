<style type="text/css">
        .expanded-group{
                /*background: url("<?php echo base_url()?>file/css/media/images/minus.jpg") no-repeat scroll left center transparent;*/
                background-color: #ddd;
                color: #fff;
                padding-left: 20px !important
        }
        .expanded-group td{
                /*background: url("<?php echo base_url()?>file/css/media/images/minus.jpg") no-repeat scroll left center transparent;*/
        
                padding-left: 20px !important
        }
        .collapsed-group{
                background-color: #eee;
                /*background: url("<?php echo base_url()?>file/css/media/images/plus.jpg") no-repeat scroll left center transparent;*/
                padding-left: 25px !important
        }
        .form_element_btn{ cursor: pointer}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo  base_url(); ?>file/css/jquery.treeTable.css">
<script type="text/javascript" src="<?php echo  base_url(); ?>file/js/plugins/jquery.treeTable.js"></script>
<script type="text/javascript">

function redirect(url){
        window.location= '<?php echo base_url()?>menu/GroupRole/'+url;
}

$(document).ready(function(){
	// $('#group').change(function(){
	// 	var id =$(this).val();
	// 	window.location.href = '<?php echo base_url()?>grouprole/view'+id;

	// });
	$('#dyntable').treeTable();	

	 
                $('.checkall').click(function () {
                $(this).parents('td:eq(0)').find(':checkbox').attr('checked', this.checked);
        });
        
	
	$('.reload_element').click (function(){
		var form_id = $(this).attr('fid');
		var alias = $(this).attr('alias');
		
		$.ajax({
	  		url : '<?php echo base_url()?>menu/reloadFormElement',
	  		type : 'POST',
	  		dataType : 'json',
	  		data :{form_id : form_id,
                                alias : alias,
	  			group_id : '<?php echo $group_id?>'},
	  		success: function(res){
	  			alert(res.pesan);
				if (res.status == "OK") {
					$("#form_element_"+ form_id).html( res.hasil );
				}
                                return false;

	  		}
	  	})
	});
	
	$('.simpan').click (function(){

	  	var sp = $(this).parent('td').parent('tr').attr('id').split('-');
	  	// var id = sp[1];
	  	var id = $(this).attr('id');
	  	// var test = $(this).parent('td').parent('tr').find("input[@name='checkbox[]']:checked").attr('id');
	  	// alert(test);
	  	var selectedGroups  = new Array();
                var selectedFormRole = new Array();
		$(this).parent('td').parent('tr').find("input[name='checkbox[]']:checked").each(function() {
                        selectedGroups.push( $(this).attr('class')  );
		});
                
                $(this).parent('td').parent('tr').find("select[name='selectbox[]']").each(function() {
                        selectedFormRole.push( $(this).attr('idv')  +'::'+ $(this).val()  );
		});
	  	//alert(selectedFormRole);
	  	// var id = new array()
	  	$.ajax({
	  		url : '<?php echo base_url()?>menu/saveGroupRole',
	  		type : 'POST',
	  		dataType : 'json',
	  		data :{id:id,
	  			role : selectedGroups,
                                FormRole : selectedFormRole,
	  			group : '<?php echo $group_id?>'},
	  		success: function(res){
	  			var msg = res.pesan;
                                var position = "center";
                                
                                alert(msg);
                                //var scrollpos = jQuery(document).scrollTop();
                                //if(scrollpos < 50) position = "customtop-right";
                                //jQuery.jGrowl(msg, { life: 5000, position: position});
                                
                                return false;

	  		}
	  	})
	});
	
	$('.manage_element').click (function(){
		var form_id = $(this).attr('fid');
		var alias = $(this).attr('alias');
		
		$.post("<?php echo base_url()?>menu/manage_element_form",
					{form_id : form_id, alias : alias, group_id : '<?php echo $group_id?>'},
				function(data){
				  $('#dialog-confirm').html(data);
				  $('#dialog-confirm').dialog('open');
			 }, "html");
	});
	
	$('.delete_element').live("click",function(){
		var checkstr =  confirm('Data yang telah dihapus tidak dapat dikembalikan lagi. \n Anda yakin ingin menghapus data ini ?');
                if(checkstr == true){
                        var form_id = $(this).attr('fid');
			var menu_id = $(this).attr('menu_id');
			var alias = "";
			
			$.post("<?php echo base_url()?>menu/delete_element_form",
						{form_id : form_id, menu_id:menu_id, alias : alias, group_id : '<?php echo $group_id?>'},
					function(data){
						if(data.status == "OK"){
							$("#form_element_"+ menu_id).html( data.hasil );
						}
						alert( data.pesan );
				 }, "json");
                }
                return false;
	});
	$(".form_element_btn").live("click",function(){
		text = $(this).html();
		if (text=="[Show form element]"){
			$(this).html("[Hide form element]");
			$(this).parent().find(".form_element_content").slideDown();
		}else{
			$(this).html("[Show form element]");			
			$(this).parent().find(".form_element_content").slideUp();
		}
	});
	$("#dialog-confirm").dialog({
		resizable: false,
		autoOpen: false,
		title:'Manage Element Form',
		show: "blind",
		height: 240,
		width: 450,
		modal: true,
		buttons: {
			'Simpan': function () {
				$("#form-manage").submit();
				$(this).dialog('close');
			},
			Cancel: function() {
					$(this).dialog('close');
				}
			}
	});
	
	$('#form-manage').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>menu/save_manage_element_form",
                        data: $(e.target).serialize(),
                        dataType: "json",
                        beforeSend:function(){
                                //$('.alert-error,.alert-success').hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                                alert(errorThrown);
                        },
                        success: function(data){
				if(data.status == "OK"){
                                        $("#form_element_"+ data.form_id).html( data.hasil );
                                }
				alert( data.pesan );
                        }
                });
                return false;
	});
});
</script>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i> <?php echo $judul?></h4>
		</div>
		<div class="widget-body">
			<div class="filter-bar">
				<form>
<!--					<div style="margin-left:33%; @media (min-width: 850px) and (max-width: 979px) { margin-left:30% };@media (max-width: 480px) { margin:left:20%; };@media (min-width: 280px) and (max-width:480px) { margin:left:0 auto; }">-->
                                        <div style="margin-left:33%;">
						<label>	User Role:</label>
						<div class="input-append">
							<select name="grup" id="grup" onChange="redirect(this.value)">
                                                                <option value="" >Pilih User Group</option>
                                                                <?php foreach ($group as $k ) { ?>
                                                                    <option value="<?php echo $k->user_group_id?>" <?php echo $select = $k->user_group_id==$group_id?"selected":"";?> >
                                                                     <?php echo $k->user_group_name?>
                                                                    </option>
                                                               <?php } ?>
                                                        </select>
						</div>
					</div>
					<div class="clearfix"></div>
				</form>
			</div>
			
                        <table class="table table-bordered table-primary table-vertical-center " id="dyntable">
                        <thead>
                                <tr>
                                        <th style="width: 30%;">Nama Menu</th>
                                        <th class="col_dragd">Hak Akses</th>
                                        <th style="width: 110px;">&nbsp;</th>
                                </tr>
                        </thead>
                        <tbody>
                        
                        <?php foreach ($this->mmenu->getGridMenuOld(0, $group_id,"show") as $k) { ?>
                                <tr id="node-<?php echo $k->id?>"class="expanded-group parent">
                                        <td><?php echo $k->menu_name;?></td>
                                        <td><div id="format_<?php echo $k->id?>">
                                                
                                                <div class="row-fluid">
                                                        <label class="checkbox span2"><input type="checkbox" id="check1" class="menu_view" name="checkbox[]" <?php echo $k->menu_view   == 1 ?'checked' :''?> /> View</label>
                                                        <label class="checkbox span2"><input type="checkbox" id="check2" class="menu_add" name="checkbox[]" <?php echo $k->menu_add    == 1 ?'checked' :''?> /> Add</label>
                                                        <label class="checkbox span2"><input type="checkbox" id="check3" class="menu_edit" name="checkbox[]" <?php echo $k->menu_edit   == 1 ?'checked' :''?> />Edit</label>
                                                        <label class="checkbox span2"><input type="checkbox" id="check4" class="menu_delete" name="checkbox[]" <?php echo $k->menu_delete == 1 ?'checked' :''?> /> Delete</label>
                                                        <label class="checkbox span2"><input type="checkbox" id="check5" class="menu_approve" name="checkbox[]" <?php echo $k->menu_approve   == 1 ?'checked' :''?> /> Approve</label>
                                                        <label class="checkbox span1"><input type="checkbox" id="check6" class="menu_reject" name="checkbox[]" <?php echo $k->menu_reject   == 1 ?'checked' :''?> /> Reject</label>
                                                        <label class="checkbox span1"><input class="checkall" type="checkbox" id="check5"  /> All</label>
                                                </div>
                                                
						<div id="form_element_<?php echo $k->id?>">
						<?php 
							$elements = $this->mmenu->getElementForm($k->id, $group_id);
							if (count($elements)>0){
						?>
							<a class="form_element_btn">[Show form element]</a>
							<div class="form_element_content" style="margin-top: 10px;display:none">  
                                                <?php $this->load->view('menu/f-form-role',array('element'=>$elements)); ?>
                            </div>
						<?php
							}
						?>		
						</div>
                                        </div>
                                        </td>
                                        <td>
						<span fid="<?php echo $k->id?>" gid="<?php echo $group_id?>" alias="<?php echo $k->alias?>"  class="manage_element btn-action glyphicons circle_plus btn-success" style="cursor: pointer;"><i></i></span>
						<button id="<?php echo $k->id?>" class="simpan stdbtn btn_black" >simpan</button>
					</td>
                                </tr>
                        
                        <?php   foreach ($this->mmenu->getGridMenuOld($k->id,$group_id,"show") as $c) {?>
                                <tr id="node-<?php echo $k->id?>-<?php echo $c->id?>" class='collapsed-group chld child-of-node-<?php echo $k->id?>'>
					<td><?php echo $c->menu_name;?></td>
					<td><div class="format">
						
                                                <div class="row-fluid">
                                                        <label class="checkbox span2"><input  type="checkbox" id="check1"  class="menu_view" name="checkbox[]" <?php echo $c->menu_view   == 1 ?'checked' :''?> /> View</label>
                                                        <label class="checkbox span2"><input  type="checkbox" id="check2"  class="menu_add" name="checkbox[]" <?php echo $c->menu_add    == 1 ?'checked' :''?> /> Add</label>
                                                        <label class="checkbox span2"><input  type="checkbox" id="check3"  class="menu_edit" name="checkbox[]" <?php echo $c->menu_edit   == 1 ?'checked' :''?> />Edit</label>
                                                        <label class="checkbox span2"><input  type="checkbox" id="check4"  class="menu_delete" name="checkbox[]" <?php echo $c->menu_delete == 1 ?'checked' :''?> /> Delete</label>
                                                        <label class="checkbox span2"><input  type="checkbox" id="check5"  class="menu_approve" name="checkbox[]" <?php echo $c->menu_approve   == 1 ?'checked' :''?> /> Approve</label>
                                                        <label class="checkbox span1"><input  type="checkbox" id="check6"  class="menu_reject" name="checkbox[]" <?php echo $c->menu_reject   == 1 ?'checked' :''?> /> Reject</label>
                                                        <label class="checkbox span1"><input class="checkall" type="checkbox" id="check5"  /> All</label>
                                                </div>
						
						<div id="form_element_<?php echo $c->id?>">
						<?php 
							$elements = $this->mmenu->getElementForm($c->id, $group_id);
							if (count($elements)>0){
						?>
							<a class="form_element_btn">[Show form element]</a>
							<div class="form_element_content" style="margin-top: 10px;display:none">  
                                                <?php $this->load->view('menu/f-form-role',array('element'=>$elements)); ?>

                            </div>
						<?php } ?>
						</div>
					</div></td>
					<td>
						<span fid="<?php echo $c->id?>" gid="<?php echo $group_id?>" alias="<?php echo $c->alias?>"  class="manage_element btn-action glyphicons circle_plus btn-success" style="cursor: pointer;"><i></i></span>
						<button id="<?php echo $c->id?>" class='simpan stdbtn btn_black' >simpan</button>
					</td>
                                </tr>
			
                        <?php   foreach ($this->mmenu->getGridMenuOld($c->id, $group_id,"show") as $S) {?>
                                <tr id="node-<?php echo $k->id?>-<?php echo $c->id?>-<?php echo $S->id?>" class='child-of-node-<?php echo $k->id?>-<?php echo $c->id?>'>
                                        <td><?php echo $S->menu_name;?></td>
                                        <td><div class="format">
                                                
                                                <div class="row-fluid">
                                                        <label class="checkbox span2"><input  type="checkbox" id="check1"  class="menu_view" name="checkbox[]" <?php echo $S->menu_view   == 1 ?'checked' :''?> /> View</label>
                                                        <label class="checkbox span2"><input  type="checkbox" id="check2"  class="menu_add" name="checkbox[]" <?php echo $S->menu_add    == 1 ?'checked' :''?> /> Add</label>
                                                        <label class="checkbox span2"><input  type="checkbox" id="check3"  class="menu_edit" name="checkbox[]" <?php echo $S->menu_edit   == 1 ?'checked' :''?> />Edit</label>
                                                        <label class="checkbox span2"><input  type="checkbox" id="check4"  class="menu_delete" name="checkbox[]" <?php echo $S->menu_delete == 1 ?'checked' :''?> /> Delete</label>
                                                        <label class="checkbox span2"><input  type="checkbox" id="check5"  class="menu_approve" name="checkbox[]" <?php echo $S->menu_approve   == 1 ?'checked' :''?> /> Approve</label>
                                                        <label class="checkbox span1"><input  type="checkbox" id="check6"  class="menu_reject" name="checkbox[]" <?php echo $S->menu_reject   == 1 ?'checked' :''?> /> Reject</label>
                                                        <label class="checkbox span1"><input class="checkall" type="checkbox" id="check5"  /> All</label>
                                                </div>
                                                
						<div id="form_element_<?php echo $S->id?>">
						<?php 
							$elements = $this->mmenu->getElementForm($S->id, $group_id);
							if (count($elements)>0){
						?>
							<a class="form_element_btn">[Show form element]</a>
							<div class="form_element_content" style="margin-top: 10px;display:none">  
                                                <?php $this->load->view('menu/f-form-role',array('element'=>$elements)); ?>
                            </div>
						<?php } ?>
						</div>
					</div></td>
					<td>
						<span fid="<?php echo $S->id?>" gid="<?php echo $group_id?>" alias="<?php echo $S->alias?>"  class="manage_element btn-action glyphicons circle_plus btn-success" style="cursor: pointer;"><i></i></span>
						<button id="<?php echo $S->id?>" class='simpan stdbtn btn_black' >simpan</button>
					</td>
                                </tr>
                        <?php }}} ?>
                        </tbody>
                        </table>
                        
                        <div class="separator top form-inline small">
                        
                            <div class="pagination pagination-small pull-right" id="pagination" style="margin: 0;">
                                    
                            </div>
                            <div class="clearfix"></div>
                        </div>
			
		</div>
	</div>
</div>
<div id="dialog-confirm"></div>