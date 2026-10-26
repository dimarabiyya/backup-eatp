<h1><?php echo lang('text_heading_title_group_role') ?></h1>

<table>
	<tr>
		<td><?php echo lang('text_user_group') ?>:</td>
		<td><?php echo @form_dropdown('userGroup', $user_groups, set_value('userGroup', $group), 'id="userGroup"') ?></td>
	</tr>
</table>

<style>
label { display:inline; margin: 0 10px; }
</style>

<?php if (get_instance()->uri->segment(3)) { ?>

<table width="100%" border="1">
	<thead>
		<tr height="30px">
			<th width="30" class="t_header"></th>
			<th width="30" class="t_header">No</th>
			<th class="t_header">Page</th>
			<th width="40%" class="t_header">Action</th>
		</tr>
	</thead>
	<tbody>			
		<?php
			$i=1;
            $menus = get_instance()->users_model->getMenuSistem(0);
			foreach($menus->result() as $r){
                                $CEK = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$r->id),"dblokal");
                                $CHECK = $CEK == ""?"":" CHECKED ";
                                echo'<tr height="30px">
                                        <td class="t_isi" align="center"><input type="checkbox" groupid="'.$group.'" menuid="'. $r->id .'" class="d_menu" '. $CHECK .'></td>
                                        <td class="t_isi" align=center>'. $i++ .'</td>
                                        <td class="t_isi">'. $r->name .'</td>
                                        <td class="t_isi"></td>
                                </tr>';
                                $smenu1 = get_instance()->users_model->getMenuSistem($r->id);
                                if($smenu1->num_rows()>0){
                                        foreach($smenu1->result() as $s){
                                                $CEK =get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$s->id),"dblokal");
												$CHECK = $CEK == ""?"":" CHECKED ";
												
												$CEK1 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$s->id,"menu_add"=>1),"dblokal");
												$CEK2 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$s->id,"menu_view"=>1),"dblokal");
												$CEK3 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$s->id,"menu_edit"=>1),"dblokal");
												$CEK4 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$s->id,"menu_delete"=>1),"dblokal");
												$CEK5 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$s->id,"menu_approve"=>1),"dblokal");
												$CEK6 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$s->id,"menu_reject"=>1),"dblokal");
												
												$CHECK1 = $CEK1 == ""?"":" CHECKED ";
												$CHECK2 = $CEK2 == ""?"":" CHECKED ";
												$CHECK3 = $CEK3 == ""?"":" CHECKED ";
												$CHECK4 = $CEK4 == ""?"":" CHECKED ";
												$CHECK5 = $CEK5 == ""?"":" CHECKED ";
												$CHECK6 = $CEK6 == ""?"":" CHECKED ";
												
                                                echo'<tr height="30px">
                                                        <td class="t_isi" align="center"><input type="checkbox" groupid="'.$group.'" menuid="'. $s->id .'" class="d_menu" '. $CHECK .'></td>
                                                        <td class="t_isi" align=center>'. $i++ .'</td>
                                                        <td class="t_isi">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $s->name .'</td>
                                                        <td class="t_isi"id="formats'.$i.'" align=center>
																<input type="checkbox" id="check1'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $s->id .'" actid="menu_add" '.$CHECK1.' /><label for="check1'.$i.'">Add</label>
																<input type="checkbox" id="check2'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $s->id .'" actid="menu_view" '.$CHECK2.' /><label for="check2'.$i.'">View</label>
																<input type="checkbox" id="check3'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $s->id .'" actid="menu_edit" '.$CHECK3.' /><label for="check3'.$i.'">Edit</label>
																<input type="checkbox" id="check4'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $s->id .'" actid="menu_delete" '.$CHECK4.' /><label for="check4'.$i.'">Delete</label>
																<input type="checkbox" id="check5'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $s->id .'" actid="menu_approve" '.$CHECK5.' /><label for="check5'.$i.'">Approve</label>
																<input type="checkbox" id="check6'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $s->id .'" actid="menu_reject" '.$CHECK6.' /><label for="check6'.$i.'">Reject</label>
														</td>
                                                </tr>';
                                                $smenu2 = get_instance()->users_model->getMenuSistem($s->id);
                                                if($smenu2->num_rows()>0){
                                                        foreach($smenu2->result() as $t){
                                                                $CEK = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$t->id),"dblokal");
                                                                $CHECK = $CEK == ""?"":" CHECKED ";
																
																$CEK1 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$t->id,"menu_add"=>1),"dblokal");
																$CEK2 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$t->id,"menu_view"=>1),"dblokal");
																$CEK3 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$t->id,"menu_edit"=>1),"dblokal");
																$CEK4 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$t->id,"menu_delete"=>1),"dblokal");
																$CEK5 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$t->id,"menu_approve"=>1),"dblokal");
																$CEK6 = get_instance()->users_model->showdata("menu_id","t_mtr_user_role",array("user_group_id"=>$group,"menu_id"=>$t->id,"menu_reject"=>1),"dblokal");
																
																$CHECK1 = $CEK1 == ""?"":" CHECKED ";
																$CHECK2 = $CEK2 == ""?"":" CHECKED ";
																$CHECK3 = $CEK3 == ""?"":" CHECKED ";
																$CHECK4 = $CEK4 == ""?"":" CHECKED ";
																$CHECK5 = $CEK5 == ""?"":" CHECKED ";
																$CHECK6 = $CEK6 == ""?"":" CHECKED ";
																
                                                                echo'<tr height="30px">
                                                                        <td class="t_isi" align="center"><input type="checkbox" groupid="'.$group.'" menuid="'. $t->id .'" class="d_menu" '. $CHECK .'></td>
                                                                        <td class="t_isi" align=center>'. $i++ .'</td>
                                                                        <td class="t_isi">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $t->name .'</td>
                                                                        <td class="t_isi"id="formats'.$i.'" align=center>
																				<input type="checkbox" id="check1'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $t->id .'" actid="menu_add" '.$CHECK1.' /><label for="check1'.$i.'">Add</label>
																				<input type="checkbox" id="check2'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $t->id .'" actid="menu_view" '.$CHECK2.' /><label for="check2'.$i.'">View</label>
																				<input type="checkbox" id="check3'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $t->id .'" actid="menu_edit" '.$CHECK3.' /><label for="check3'.$i.'">Edit</label>
																				<input type="checkbox" id="check4'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $t->id .'" actid="menu_delete" '.$CHECK4.' /><label for="check4'.$i.'">Delete</label>
																				<input type="checkbox" id="check5'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $t->id .'" actid="menu_approve" '.$CHECK5.' /><label for="check5'.$i.'">Approve</label>
																				<input type="checkbox" id="check6'.$i.'" class="saveAction" groupid="'.$group.'" menuid="'. $t->id .'" actid="menu_reject" '.$CHECK6.' /><label for="check6'.$i.'">Reject</label>
																		</td>
                                                                </tr>';
                                                        }
                                                }
                                        }
                                }
                        }
                        ?>                
	</tbody>
</table>

<?php } ?>

<script><!--
document.getElementById('userGroup').onchange = function() {
	if (this.value != '') {
		window.location.href = '<?php echo base_url('/users/group_role') ?>' + '/' + this.value;
	} else {
		window.location.href = '<?php echo base_url('/users/group_role') ?>';
	}
};
//--></script>

<script><!--
$(function() {
	$('.saveAction').click(function() {
        var groupid        = $(this).attr('groupid');
        var menuid          = $(this).attr('menuid');
		var actid          = $(this).attr('actid');
        var nilai      = $(this).is(':checked');
        
        $.post(
            '<?php echo base_url('/users/group_role') ?>', 
            {cmd:'SAVE_USER_ROLE_ACTION',groupid:groupid,menuid:menuid,nilai:nilai,actid:actid},
            function(data) {
                if(data.status == 'OK') {
                    //$("#messageBox").removeClass().addClass("errorbox").html(data.pesan).fadeIn(1000).fadeOut(1000);
                }
            },
            "json"
        ).error(function() {
            //alert("-");
        });
    });
	
	$('.d_menu').click(function() {
        var groupid        = $(this).attr('groupid');
        var menuid          = $(this).attr('menuid');
        var nilai      = $(this).is(':checked');
        
        $.post(
            '<?php echo base_url('/users/group_role') ?>', 
            {cmd:'SAVE_USER_ROLE',groupid:groupid,menuid:menuid,nilai:nilai},
            function(data) {
                if(data.status == 'OK') {
                    //$("#messageBox").removeClass().addClass("errorbox").html(data.pesan).fadeIn(1000).fadeOut(1000);
                }
            },
            "json"
        ).error(function() {
            //alert("-");
        });
    });
});
//--></script>