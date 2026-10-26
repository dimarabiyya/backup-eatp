<?php
//$element = $this->mmenu->getElementForm($form_id, $group_id);
foreach($element as $rw){
	echo'<div class="row-fluid">
			<label class="span3">'.$rw->element_name.' </label>
			<select name="selectbox[]" idv="'.$rw->form_id.'">';
				$getCardinality = $this->mmenu->getCardinality();
				foreach($getCardinality as $r){
					$select = ($rw->element_role == $r->cardinality_id?" SELECTED ":"");
					echo"<option value='$r->cardinality_id' $select >$r->cardinality_name</option>";
				}
			echo'</select>
			
			<span style="margin-top: -10px;" class="delete_element btn-action glyphicons remove btn-success" style="cursor: pointer;" menu_id="'.$rw->menu_id.'" fid="'.$rw->form_id.'"><i></i></span>
		</div>';
}
?>