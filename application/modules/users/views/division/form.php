<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
  <div class="widget">
    <div class="widget-head"><h4 class="heading"><?php echo $judul ?></h4></div>
    <div class="widget-body">
      <div class="row-fluid">
        <div>
          <input type='hidden' name='user_div_id' value='<?php echo $user_div_id ?>' >
          <div class="control-group" id="form_user_div_name">
            <label class="control-label" for="descr">Division Name</label>
            <div class="controls"><input class="span12" id="user_div_name" name="user_div_name" type="text" value="<?php echo $user_div_name ?>" autofocus=""/></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>