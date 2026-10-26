<div class="widget">
  <div class="widget-head"><h1 class="heading glyphicons list"><i></i>Application Document </h1></div>
  <div class="widget-body">
    <form id="ff">
      <div id="tb_doc" class="form-horizontal" style="padding: 5px; width: auto">
        Document type : <?php echo form_dropdown('doc_type', $doc_type_list, '', 'id="doc_type"') ?> &nbsp; &nbsp; &nbsp; 
        From Year :
        <select id="year_from" name="year_from">
          <?php
          for ($i = 2001; $i <= 2050; $i++) {
            if ($i == date('Y'))
              echo "<option selected='selected' value='$i'>$i</option>";
            else
              echo "<option value='$i'>$i</option>";
          }
          ?>
        </select> &nbsp; &nbsp; &nbsp; 
        To Year :
        <select id="year_to" name="year_to">
          <?php
          for ($i = 2001; $i <= 2050; $i++) {
            if ($i == date('Y'))
              echo "<option selected='selected' value='$i'>$i</option>";
            else
              echo "<option value='$i'>$i</option>";
          }
          ?>
        </select> &nbsp; &nbsp; &nbsp; 
        <button class="btn" onclick="searchDoc()">Apply</button>
      </div>
    </form>
    <table id="dg_doc" style="height: 450px; width: 1000px"></table>

    <div class="form-actions">
      <button type="button" class="btn btn-icon btn-close btn-primary glyphicons remove"><i></i>Close</button>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    $('#dg_doc').datagrid({
      url: '<?php echo base_url("isr_bts/get_isr_bts_docs/{$app_id}"); ?>',
      type: 'post',
      toolbar: '#tb_doc',
      rownumbers: true,
      pagination: false,
      singleSelect: true,
      fitColumns: false,
      columns: [[
          {field: 'file_name', title: 'File Name', width: '400'},
          {field: 'document_type', title: 'Document Type', width: '250'},
          {field: 'created_by', title: 'Uploaded By', width: '150'},
          {field: 'created_on', title: 'Uploaded Date', width: '125'},
        ]],
    });

    $(".btn-close").click(function(e) {
      $.fancybox.close();

    });

  });

  function searchDoc() {
    $('#dg_doc').datagrid('load', {
      doc_type : $('#doc_type').val(),
      year_from : $('#year_from').val(),
      year_to : $('#year_to').val(),
    });
  }

</script>