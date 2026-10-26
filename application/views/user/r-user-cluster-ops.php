<label class="control-label">Cluster OPS</label>
<div class="controls" id="tblCluster">
        
        <?php
        //User Role mengambil data di group
        $idregion=array();$i=0;
        foreach ($region as $key => $value) {
                //echo $value . " in " . $key . ", ";
                $idregion[$i] =$key;
                $i++;
        }
        $cluster = $this->muser->getClusterOpsMultiRegion($idregion);
        $klp = ceil(count($cluster)/3);
        ?>   
        <div class="span4" id="cluster_idd">
                <label class="checkbox" for="allCluster">
                        <input type="checkbox" id="allCluster" name="cMCE" value="1" />
                        <b>Select All</b>
                </label>
                <?php
                $k=2;
                foreach($cluster as $r){
                ?>
                <label class="checkbox" for="cluster[<?php echo $r->cluster_ops_id?>]">
                        <input type="checkbox" name="cluster[<?php echo $r->cluster_ops_id?>]" id="cluster[<?php echo $r->cluster_ops_id?>]" cid="<?php echo $r->cluster_ops_id?>" value="1" />
                        <?php echo $r->region_name?> > <?php echo $r->cluster_name?> 
                </label>
                <?php                                                                         
                        if( ($k % $klp) ==0){
                                echo'</div><div class="span4">';
                        }
                $k++;
                } ?>
                
        </div>
</div>
<?php if($icluster<>""){?>
        <script>
       var cluster = "<?php echo $icluster?>";
        var string = cluster, arr = string.split(', '), i;
        $("#tblCluster input[type=checkbox]").each(function(){
                if($.inArray($(this).attr('cid'),arr) != -1){
                        $(this).attr('checked', true);
                }else{
                        $(this).attr('checked', false);
                }
        });      
        </script>
<?php } ?>