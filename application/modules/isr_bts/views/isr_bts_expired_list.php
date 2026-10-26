<div id="tb_expired" class="form-horizontal" style="padding: 5px; width: auto">
  <div class="control-group">
    <label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
    <div class="input-append">
      <input name="q_expired" id="q_expired" type="text" placeholder="Type text to search ..">
      <button  onclick="searchExpired()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
    </div>	
  </div>
</div>
<table id="dg_expired" style="height: 450px"></table>

<script>
        $(document).ready(function() {

          $('#dg_expired').datagrid({
            url: '<?php echo base_url('isr_bts/get_isr_bts_expired_list'); ?>',
            type: 'post',
            toolbar: '#tb_expired',
            rownumbers: true,
            pagination: true,
            singleSelect: true,
            fitColumns: false,
            columns: [[
                {field: 'app_no', title: 'App No', width: 125},
                {field: 'license_number', title: 'License Number', width: 125},
                {field: 'app_issued', title: 'Issued Date', width: 100},
                {field: 'app_expired', title: 'Expired Date', width: 100},
                {field: 'doc', title: 'Document', width: 300},
                {field: 'total_link', title: 'Total Links', width: 100, align: 'center'},
                {field: 'remark', title: 'Remark', width: 200},
                {field: 'action', title: 'Action', width: 150, align: 'center'},
              ]],
            view: detailview,
            detailFormatter: function(index, row) {
              return '<div style="padding:2px"><table class="ddv"></table></div>';
            },
            onExpandRow: function(index, row) {
              var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv');
              ddv.datagrid({
                url: '<?php echo base_url("isr_bts/get_isr_bts_detail_list"); ?>?app_id=' + row.app_id,
                fitColumns: false,
                pagination: false,
                singleSelect: true,
                height: 'auto',
                columns: [[
                    {field: 'station_identifier', title: 'Station ID', width: '80', align: 'center'},
                    {field: 'station_name', title: 'Station Name', width: '150'},
                    {field: 'vendor_id', title: 'Vendor ID', width: '100'},
                    {field: 'status_active', title: 'Status', width: '80', align: 'center'},
                    {field: 'milestone_status', title: 'Document Status', width: '150'},
                    {field: 'height_asl', title: 'Height ASL', width: '80', align: 'right'},
                    {field: 'latitude', title: 'Latitude', width: '100', align: 'right'},
                    {field: 'longitude', title: 'Longitude', width: '100', align: 'right'},
                    {field: 'radius_of_operation', title: 'Radius of Operation', width: '125', align: 'right'},
                    {field: 'site_province', title: 'Site Province', width: '125'},
                    {field: 'site_city', title: 'Site City', width: '125'},
                    {field: 'site_district', title: 'Site District', width: '125'},
                    {field: 'area_of_service', title: 'Area of Service', width: '200'},
                    {field: 'equipment_type', title: 'Equipment Type', width: '100'},
                    {field: 'equipment_output_power', title: 'Equipment Output Power', width: '150', align: 'right'},
                    {field: 'bandwidth', title: 'Bandwidth', width: '100', align: 'right'},
                    {field: 'transmitter_freq', title: 'Tx Freq', width: '100', align: 'right'},
                    {field: 'receiver_freq', title: 'Rx Freq', width: '100', align: 'right'},
                    {field: 'antenna_type', title: 'Antenna Type', width: '100'},
                    {field: 'feeding_loss', title: 'Feeding Loss', width: '100', align: 'right'},
                    {field: 'connector_loss', title: 'Connector Loss', width: '100', align: 'right'},
                    {field: 'branch_loss', title: 'Branch Loss', width: '100', align: 'right'},
                    {field: 'antenna_height_agl', title: 'Antenna Height AGL', width: '125', align: 'right'},
                    {field: 'transmitter_polarization_desc', title: 'Transmitter Polatization', width: '125'},
                  ]],
                onResize: function() {
                  $('#dg_expired').datagrid('fixDetailRowHeight', index);
                },
                onLoadSuccess: function() {
                  setTimeout(function() {
                    $('#dg_expired').datagrid('fixDetailRowHeight', index);
                  }, 0);
                }
              });
              $('#dg_expired').datagrid('fixDetailRowHeight', index);
            }
          });

          $("#q_expired").keydown(function(e) {
            var code = e.which; // recommended to use e.which, it's normalized across browsers
            if (code == 13) {
              searchExpired();
              e.preventDefault();
            }
          });

        });

        function searchExpired() {
          $('#dg_expired').datagrid('load', {
            q: $('#q_expired').val(),
          });
        }

</script>