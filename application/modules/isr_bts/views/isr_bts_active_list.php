<div id="tb" class="form-horizontal" style="padding: 5px; width: auto">
  <div class="control-group">
    <label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
    <div class="input-append">
      <input name="q" id="q" type="text" placeholder="Type text to search ..">
      <button  onclick="searchData()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
    </div>	
  </div>
</div>
<table id="dg" style="height: 450px"></table>

<script>
        $(document).ready(function() {

          $('#dg').datagrid({
            url: '<?php echo base_url('isr_bts/get_isr_bts_active_list'); ?>',
            type: 'post',
            toolbar: '#tb',
            rownumbers: true,
            pagination: true,
            singleSelect: true,
            fitColumns: false,
            columns: [[
                {field: 'app_no', title: 'App No'},
                {field: 'license_number', title: 'License Number'},
                {field: 'app_issued', title: 'Issued Date'},
                {field: 'app_expired', title: 'Expired Date'},
                {field: 'doc', title: 'Document'},
                {field: 'status', title: 'Status'},
                {field: 'total_link', title: 'Total Links', align: 'center'},
                {field: 'action', title: 'Action', align: 'center'},
              ]],
            view: detailview,
            detailFormatter: function(index, row) {
              return '<div style="padding:2px"><table rownumbers="true" pagination="true" id="ddv-' + index + '"></table></div>';
            },
            onExpandRow: function(index, row) {
              $('#ddv-' + index).datagrid({
                url: '<?php echo base_url("isr_bts/get_isr_bts_detail_list"); ?>?app_id=' + row.app_id,
                fitColumns: false,
                pagination: false,
                singleSelect: true,
                height: 'auto',
                columns: [[
                    {field: 'station_identifier', title: 'Station ID', align: 'center'},
                    {field: 'station_name', title: 'Station Name'},
                    {field: 'vendor_id', title: 'Vendor ID'},
                    {field: 'status_active', title: 'Status', align: 'center'},
                    {field: 'milestone_status', title: 'Document Status'},
                    {field: 'height_asl', title: 'Height ASL', align: 'right'},
                    {field: 'latitude', title: 'Latitude', align: 'right'},
                    {field: 'longitude', title: 'Longitude', align: 'right'},
                    {field: 'radius_of_operation', title: 'Radius of Operation', align: 'right'},
                    {field: 'site_province', title: 'Site Province'},
                    {field: 'site_city', title: 'Site City'},
                    {field: 'site_district', title: 'Site District'},
                    {field: 'area_of_service', title: 'Area of Service'},
                    {field: 'equipment_type', title: 'Equipment Type'},
                    {field: 'equipment_output_power', title: 'Equipment Output Power', align: 'right'},
                    {field: 'bandwidth', title: 'Bandwidth', align: 'right'},
                    {field: 'transmitter_freq', title: 'Tx Freq', align: 'right'},
                    {field: 'receiver_freq', title: 'Rx Freq', align: 'right'},
                    {field: 'antenna_type', title: 'Antenna Type'},
                    {field: 'feeding_loss', title: 'Feeding Loss', align: 'right'},
                    {field: 'connector_loss', title: 'Connector Loss', align: 'right'},
                    {field: 'branch_loss', title: 'Branch Loss', align: 'right'},
                    {field: 'antenna_height_agl', title: 'Antenna Height AGL', align: 'right'},
                    {field: 'transmitter_polarization_desc', title: 'Transmitter Polatization'},
                  ]],
                onResize: function() {
                  $('#dg').datagrid('fixDetailRowHeight', index);
                },
                onLoadSuccess: function() {
                  setTimeout(function() {
                    $('#dg').datagrid('fixDetailRowHeight', index);
                  }, 0);
                }
              });
              $('#dg').datagrid('fixDetailRowHeight', index);
            }
          });

          $("#q").keydown(function(e) {
            var code = e.which; // recommended to use e.which, it's normalized across browsers
            if (code == 13) {
              searchData();
              e.preventDefault();
            }
          });

        });

        function searchData() {
          $('#dg').datagrid('load', {
            q: $('#q').val(),
          });
        }

</script>