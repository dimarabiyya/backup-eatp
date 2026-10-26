<?php
/*
 * Andedi Tarmadi
 * 
 * 
 * ----------------
 * Tumbas Wiralodra
 * Email : andedi.foss@gmail.com
 * 087829986468
 */
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

//error_reporting(0);
?>

<style>
  .form-actions {
    padding: 10px;
  }
</style>

<div class="widget" style="width: 800px; overflow: auto;">
  <div class="widget-head heading-outer">
    <h4 class="heading glyphicons list"><i></i>Detail Tabel <?php echo $table_name; ?></h4>
  </div>
  <div class="widget-body">
    <table class="table table-bordered tabbable-bordered table-striped">
      <tr>
        <th style="width: 5%">No</th>
        <th style="width: 25%">Field</th>
        <th style="width: 70%">Value</th>
      </tr>
      <?php
      $i = 1;
      foreach ($row as $key => $value) {
        echo "<tr><td class='center'>{$i}</td><td>{$key}</td><td>{$value}</td></tr>";
        $i++;
      }
      ?>
    </table>
    <div class="form-actions">
      <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" id="btn_close"><i></i>Close</button>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    $('#btn_close').click(function() {
      $.fancybox.close();
    });

  });
</script>