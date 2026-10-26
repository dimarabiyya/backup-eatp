    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/icon.css">
    <script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/overlay.js"></script>

    <script type="text/javascript">
        $(function(){
            $("#q").keydown(function(e) {
                var code = e.which; // recommended to use e.which, it's normalized across browsers
                if(code==13){
                    doSearch();
                    e.preventDefault();
                }
            });
        });
        function doSearch(){
            $('#dg').datagrid('load',{
            q: $('#q').val(),
            });
        }
        function popupwindow(url, title, w, h) {
            wLeft = window.screenLeft ? window.screenLeft : window.screenX;
            wTop = window.screenTop ? window.screenTop : window.screenY;

            var left = wLeft + (window.innerWidth / 2) - (w / 2);
            var top = wTop + (window.innerHeight / 2) - (h / 2);
            return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        }
        var Document = {
            addData: function( id ){
                popupwindow("<?php echo base_url()?>email/broadcast/add","Template Menu",850,550);
                return false;
            }
        }
    </script>
    
    <div class="separator bottom"></div>
    <div class="innerLR">
        <div class="widget">
            <div class="widget-head">
                <h4 class="heading glyphicons list"><i></i><?php echo $judul?></h4>     
                <a href="#" onclick="return Document.addData();"><button class="btn btn-primary pull-right btn-heading btn-icon glyphicons message_flag"><i></i>Add Email Broadcast</button></a>           
            </div>
            <div class="widget-body">
                <div id="tb" class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
                        <div class="input-append">
                            <input name="q" id="q" type="text" placeholder="Type text to search .." >
                                <button  onclick="doSearch()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
                        </div>  
                    </div>  
                </div>
                <br>
                <table  class="easyui-datagrid jgrid" id="dg"
                                url="<?php echo base_url().'email/broadcast_data';?>" 
                                title="" nowrap='false'
                                iconCls="icon-save" 
                                rownumbers="true" pagination="true"
                                singleSelect="true" fitColumns="true">
                
                    <thead>
                        <tr>
                            <?php
                            
                                $arr=array('email_to'=>'To','email_cc'=>'CC','email_subject'=>'Subject','created_on'=>'Created Date');
                                $width=array('70','100','100','80','50');
                                $i=0;
                                foreach ($arr as $key=>$value) {
                                    echo '<th field="'.$key.'" width="'.$width[$i++].'">'.$value.'</th>';
                                }
                            ?>

                        </tr>
                    </thead>
                </table>   
                <hr>
            </div>
        </div>
    </div>