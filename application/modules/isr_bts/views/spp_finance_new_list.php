<div id="tb_new" class="form-horizontal" style="padding: 5px; width: auto">
  <div class="control-group">
    <label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
    <div class="input-append">
      <input name="q" id="q" type="text" placeholder="Type text to search ..">
      <button  onclick="searchNew()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
    </div>	
  </div>
</div>
<table id="dg_new" style="height: 450px"></table>

<script>
        $(document).ready(function() {

          $('#dg_new').datagrid({
            url: '<?php echo base_url("isr_bts/spp_finance/get_spp_finance_new_list"); ?>',
            type: 'post',
            toolbar: '#tb_new',
            rownumbers: true,
            pagination: true,
            singleSelect: true,
            fitColumns: false,
            columns: [[
                {field: 'app_no', title: 'Resi No', width: 100},
                {field: 'app_issued', title: 'Issued Date', width: 100},
                {field: 'doc', title: 'Document', width: 250},
                {field: 'remark', title: 'Remark', width: 190},
                {field: 'total_links', title: 'Total Links', width: 100, align: 'center'},
                {field: 'created_by', title: 'Creator', width: 150},
                {field: 'action', title: 'Action', width: 100},
              ]],
            view: detailview,
            detailFormatter: function(index, row) {
              return '<div style="padding:2px"><table rownumbers="true" pagination="true" id="ddv-' + index + '"></table></div>';
            },
            onExpandRow: function(index, row) {
              $('#ddv-' + index).datagrid({
                url: '<?php echo base_url("isr_bts/spp_finance/get_spp_finance_detail_list"); ?>?app_resi_id=' + row.app_resi_id,
                fitColumns: false,
                pagination: false,
                singleSelect: true,
                height: 'auto',
                columns: [[
                    {field: 'station_identifier', title: 'Station ID', align: 'center'},
                    {field: 'station_name', title: 'Station Name'},
                    {field: 'vendor_id', title: 'Vendor ID'},
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
                  $('#dg_new').datagrid('fixDetailRowHeight', index);
                },
                onLoadSuccess: function() {
                  setTimeout(function() {
                    $('#dg_new').datagrid('fixDetailRowHeight', index);
                  }, 0);
                }
              });
              $('#dg_new').datagrid('fixDetailRowHeight', index);
            }
          });

          $("#q").keydown(function(e) {
            var code = e.which; // recommended to use e.which, it's normalized across browsers
            if (code == 13) {
              searchNew();
              e.preventDefault();
            }
          });

        });

        function searchNew() {
          $('#dg_new').datagrid('load', {
            q: $('#q').val(),
          });
        }

        function uploadNew(appId) {
          $.fancybox({
            href: "<?php echo base_url() ?>isr_bts/spp_finance/add_new_spp_finance/" + appId, type: 'ajax',
            minWidth: 1000, autoCenter: false, scrolling: false, autoSize: true, fitToView: true, closeClick: false, openEffect: 'fade', closeEffect: 'fade', afterShow: function() {
              $('#app_no').focus();
            }
          });
        }

</script>