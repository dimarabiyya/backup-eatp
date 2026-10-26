<div class="control-group">
        <label class="control-label" for="username">Reporting To</label>
        <div class="controls">
                <?php
                // if($usergroup = 1) {
                //         $usergroup = 2;
                // }
                $getUser	= $this->musers->getUserReportingTo($usergroup);
                echo '<select name="reportingto" id="reportingto">';
                foreach($getUser as $r){
                        $SELECTED = $r->user_id==$reportingto?"SELECTED":"";
                        echo'<option value="'. $r->user_id .'" '. $SELECTED .'>'. $r->user_name .'</option>';
                }
                echo'</select>';
                ?>
        </div>
</div>