                                <div class="field">
                                        <div class="label">
                                                <label for="user">Dikelola Oleh:</label>
                                        </div>
                                        <div class="input">
                                                <select name="user" id="muser">
                                                       <option value="">-PILIH-</option>
                                                <?php
                                                $where = array();
                                                if($type == "Cluster"){
                                                        $where = array(4,5);
                                                }elseif($type == "Regional"){
                                                        $where = array(2);
                                                }
                                                $dt = $this->mservices->getValueFromTableIN("t_mtr_user",$where);
                                                foreach($dt as $row){
                                                        echo'<option value="'. $row->user_id .'" '. ($data['user'] == $row->user_id ?"SELECTED":"") .'>'. $row->user_name .'</option>';
                                                }
                                                ?>
                                                </select>
                                        </div>
                                </div>
                                <div class="field">
                                        <div class="label">
                                                <label for="territory_type">Tipe Teritori:</label>
                                        </div>
                                        <div class="input">
                                                <b><?php echo $type?>&nbsp;</b>
                                        </div>
                                </div>
                                
                        <?php if($search <> 1){ 
                                     if($code == "1"){ ?>
                                        
                                        <div class="field">
                                                <div class="label">
                                                        <label for="territory_code">Kode Teritori:</label>
                                                </div>
                                                <div class="input">
                                                        <input type=hidden name="territory_type" id="territory_type" value="<?php echo $idtype?>">
                                                        <input type="text" id="territory_code" name="code" value="<?php echo $codes?>" class="medium" />
                                                </div>
                                        </div>
                                        
                                        <div class="field">
                                                <div class="label">
                                                        <label for="territory_code">Kode Teritori 2:</label>
                                                </div>
                                                <div class="input">
                                                        <input type="text" id="territory_code2" name="code" value="<?php echo $codes2?>" class="medium" />
                                                </div>
                                        </div>
                                <?php }?>
                                
                                        <div class="field">
                                                <div class="label">
                                                        <label for="territory_type_icon">Ikon Tipe Teritori:</label>
                                                </div>
                                                <div class="input"><?php echo $icon?> 
                                                        <input type="file" id="territory_type_icon" name="icon" class="medium" />
                                                </div>
                                        </div>
                        <?php } ?>