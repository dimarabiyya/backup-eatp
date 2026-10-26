<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >

					<div class="control-group" id="form_company_name">
						<label class="control-label" for="company_name">TP Company Name</label>
						<div class="controls"><input class="span12" id="company_name" name="company_name" type="text" value="<?php echo $company_name?>" /></div>
					</div>
				
					<div class="control-group" id="form_tpgroup_id">
						<label class="control-label" for="tpgroup_id">TP Group Name</label>
						<div class="controls">
						<!--<input class="span12" id="tpgroup_id" name="tpgroup_id" type="text" value="<?//=$tpgroup_id?>" />-->
						<select style="text-transform:capitalize;" class="span12" name="tpgroup_id" id="tpgroup_id">
							<option value="">--choose--</option>
							<?php
								$getTPGroup = $this->mcompany->getTPGroup();
								foreach($getTPGroup as $tp){
									$select = ($tp->tpgroup_id == $tpgroup_id ? " SELECTED ":"");
									echo"<option value='$tp->tpgroup_id' $select>$tp->tpname</option>";
								}
							?>
						</select>
						</div>
					</div>
					
					<div class="control-group" id="form_contact_name">
						<label class="control-label" for="contact_name">Contact Name</label>
						<div class="controls"><input class="span12" maxlength="100" id="contact_name" name="contact_name" type="text" value="<?php echo $contact_name?>" /></div>
					</div>
					
					<div class="control-group" id="form_contact_phone">
						<label class="control-label" for="contact_phone">Contact Phone</label>
						<div class="controls"><input class="span12" maxlength="30" id="contact_phone" name="contact_phone" type="text" value="<?php echo $contact_phone?>" /></div>
					</div>
					
					<div class="control-group" id="form_commercial_rank">
						<label class="control-label" for="commercial_rank">Commercial Rank</label>
						<div class="controls"><input class="input-mini just-number" maxlength="16" id="commercial_rank" name="commercial_rank" type="text" value="<?php echo $commercial_rank?>" /></div>
					</div>
					
					<div class="control-group" id="form_support_rank">
						<label class="control-label" for="support_rank">Support Rank</label>
						<div class="controls"><input class="input-mini just-number" maxlength="16" id="support_rank" name="support_rank" type="text" value="<?php echo $support_rank?>" /></div>
					</div>
					
					<div class="control-group" id="form_technical_rank">
						<label class="control-label" for="technical_rank">Technical Rank</label>
						<div class="controls"><input class="input-mini just-number" maxlength="16" id="technical_rank" name="technical_rank" type="text" value="<?php echo $technical_rank?>" /></div>
					</div>
					
					<div class="control-group" id="form_remark">
						<label class="control-label" for="remark">Remark</label>
						<div class="controls"><input class="span12" maxlength="250" id="remark" name="remark" type="text" value="<?php echo $remark?>" /></div>
					</div>
                                        
				</div><hr>
			</div>
			
		</div>
		
	</div>
	
</form>