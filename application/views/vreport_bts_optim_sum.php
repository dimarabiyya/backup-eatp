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
//                                    foreach ($years as $year) {
//                                        echo "<option value='{$year['value']}'>{$year['label']}</option>";
//                                    }
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
</script>