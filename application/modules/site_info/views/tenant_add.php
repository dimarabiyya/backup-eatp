 
<!--    
    Document   : tenant_add
    Created on : May 29, 2015, 4:06:21 PM
    Author     : tumbas
    Description:
        Purpose of the PHP Web Page follows.
-->
<div class="widget">
  <div class="widget-head">
    <h4 class="heading"><?php echo $title; ?></h4>
  </div>
  <div class="widget-body">
    <form id="form" class="form-horizontal">
      <div class="">
        <div class="control-group column-left">
          <label class="control-label">Tenant ID : </label>
          <div class="controls">
            <input  name="tenant_id_text" type="text" value="">
          </div>
        </div>
      </div>
      <div clas>
        <div class="control-group column-left">
          <label class="control-label">Tenant Code : </label>
          <div class="controls">
            <input  name="tenant_code" type="text" value="">
          </div>
        </div>
      </div>
      <div class="">
        <div class="control-group column-left">
          <label class="control-label">Tenant Name : </label>
          <div class="controls">
            <input  name="tenant_name" type="text" value="">
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="widget-footer" style="text-align: right; padding: 10px">
    <button type="submit" class="btn" id="btn-save">Save</button>
    <button type="button" class="btn" id="btn-close">Cancel</button>
  </div>
</div>

<script>
  $("#btn-close").click(function() {
    $.fancybox.close();
  });
  
  $('#btn-save').click(function(){
    $.ajax({
      url : '<?php echo base_url('site_info/reference/tenant_add'); ?>',
      type : 'post',
      data : $('#form').serialize(),
      dataType : 'json',
      beforeSend : function () {
        block.show('Saving data');
      },
      success : function (data) {
        if (data.status == 'success') {
          $.fancybox.close();
          $('#dg').datagrid('reload');
        }
        coolNotification(data.message, data.status);
      },
      error : function (xhr, status, message) {
        coolNotification('<b>Error!</b><br>An error occured : '+ message, 'error');
      },
      complete : function () {
        block.close();
      }
    });
  });
  
</script>