<div class="separator bottom"></div>
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="form_report" action="">
        <div class="innerLR">
            <div class="widget">
                <div class="widget-head"><h2 class="heading glyphicons charts"><i></i><?php echo  $subtitle ?></h2></div>
                <div class="widget-body">
                    <div class="row-fluid">
                        <input type="hidden" id="menu_number" value="<?php echo $menu_number ?>" />
                        <div class="control-group column-left">
                            <span class="span4">
                                <label class="control-label" for="year1">From</label>&nbsp;
                                <select name="year_from" id="year_from">
                                    <?php
                                        foreach ($years as $year) {
                                            echo "<option value='{$year['value']}'>{$year['label']}</option>";
                                        }
                                    ?>
                                </select>
                            </span>
                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to &nbsp;
                             <select name="year_to" id="year_to">
                                <?php
                                    foreach ($years as $year) {
                                        echo "<option value='{$year['value']}'>{$year['label']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="control-group column-left">
                            <span class="span4"></span>
                            Month&nbsp;
                            <select name="month">
                                <?php
                                    foreach ($months as $month) {
                                        echo "<option value='{$month['value']}'>{$month['label']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>  
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok generate"><i></i>Generate</button>
                    </div>
                </div>
            </div>
            <div class="widget report">
                <div class="widget-body">
                    <div class="row-fluid">
                        <div class="row-fluid canvas"></div>                            
                    </div>
                </div>
            </div>
        </div>
    </form>

<script language="javascript" src="<?php echo  base_url(); ?>assets/js/jquery.form.js"></script>
<script language="javascript" src="<?php echo  base_url(); ?>assets/js/hehehe.js"></script>
<script>
    $(document).ready(function (){
        $('.report').hide();
        changeYearTo($('#year_from').val());
        
        $('#form_report').submit(function (event){
            event.preventDefault();
            $.ajax({
                url     : $(this).attr('action'),
                type    : 'post',
                data    : $(this).serialize(),
                dataType: 'json',
                beforeSend : function () {
                    block.show('Generating report');
                },
                success : function (response) {
                    if (response.status=='success') {
                        var html = "<iframe id='ireport' src='" + response.result + "' height='1200px' width='100%' align='center' frameborder='0'></iframe>";
                        $('.report').show();
                        $(".canvas").html(html);
                    } else {
                        coolNotification(response.message, response.status);
                    }
                },
                error : function (xhr, status, message) {
                    coolNotification('Generating report failed. <br>'+ status.toUpperCase()+' : '+message, 'error');
                },
                complete : function () {
                    $('iframe#ireport').load(function (){
                        block.close();
                    });
                } 
            });
        });
        
        $('#year_from').change(function (){
            changeYearTo($(this).val());
        });
        
    });
    
    function changeYearTo(startYear) {
        $('#year_to').html('');
        var d = new Date().getFullYear();
        for (var i=d; i>=startYear; i--) {
            $('#year_to').append('<option value="'+i+'">'+i+'</option>');
        }
    }
    
//        var url = "https://sims.smartfren.com/report/mydbr/report.php?";
//
//        function generateUrl(r, m, h, param) {
//            return this.url + 'r=' + r + '&m=' + m + '&h=' + h + '&hdr=0' + param;
//        }
//
//        $('.generate').live("click", function(e) {
//            e.preventDefault();
//            var reportId, hash, group, param, html;
//            var year = $('#u1').val();
//            var month = $('#u2').val();
//            var user = '<?php echo $user_id; ?>';
//
//            if (month == 0) {
//                group = 8;
//                reportId = 122;
//                hash = '1c06f813ad63fbf1c70300a4e3501213a5df034f';
//                param = '&u1=' + year + '&u2=' + user;
//            } else {
//                group = 8;
//                reportId = 121;
//                hash = '14cc0a0437b836756213eb25cd4e3c66d165b1f8';
//                param = '&u1=' + year + '&u2=' + month + '&u3=' + user;
//            }
//            html = "<iframe src='" + generateUrl(reportId, group, hash, param) + "' height='1200px' width='100%' align='center' frameborder='0'></iframe>";
//            $('.report').show();
//            $(".canvas").html(html);
//        });
//
//        jQuery(document).ready(function() {
//            $($("#menu_number").val()).parent().addClass('active').find('.collapse').css('height', 'auto');
//            $('.report').hide();
//        });

</script>