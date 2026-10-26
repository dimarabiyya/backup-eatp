<link href="<?php echo base_url('assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/jqueryvalidation/jquery.validate.js"></script>

<style>
    label>input[type="radio"] {
        margin: -4px 0 0 0;
    }

    table.list-checklist {
        font-size: 10pt;
    }
</style>

<div id="views_detail">
    <div class="innerLR">

        <form action="<?php echo base_url('/visitsummary/pro_fop_attach'); ?>/<?php echo $id;?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="test" value="Hello world"/>
            <div class="widget">
                <div class="widget-head">
                    <h4 class="heading">FOP Upload Selfie & Video</h4>
                </div>
                <div class="widget-body">
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <td> Selfie (.jpg) :  </td>
                            <td> : </td>
                            <td> <input type="file" id="image_fop" name="image_fop"> <input type="hidden" name="is_upload_image" id="is_upload_image" > </td>
                        </tr>
                        <tr>
                            <td> Video (.mp4) :  </td>
                            <td> : </td>
                            <td> <input type="file" id="video_fop" name="video_fop"> <input type="hidden" name="is_upload_video" id="is_upload_video" > </td>
                        </tr>
                    </table>
                    <button id="submit" type="submit" name="submit" class="btn btn-block btn-primary"> Upload </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script type="text/javascript">
    $("#image_fop").on("change",function(){
        $("#is_upload_image").val("uploaded");
    });
    
    $("#video_fop").on("change",function(){
        $("#is_upload_video").val("uploaded");
    });
</script>