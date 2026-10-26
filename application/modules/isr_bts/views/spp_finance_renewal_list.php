<div id="tb_renewal" class="form-horizontal" style="padding: 5px; width: auto">
  <div class="control-group">
    <label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
    <div class="input-append">
      <input name="q" id="q_renewal" type="text" placeholder="Type text to search ..">
      <button  onclick="searchRenewal()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
    </div>	
  </div>
</div>
<table id="dg_renewal" style="height: 450px"></table>

<script>
        $(document).ready(function() {

          $('#dg_renewal').datagrid({
            url: '<?php echo base_url("isr_bts/spp_finance/get_spp_finance_renewal_list"); ?>',
            type: 'post',
            toolbar: '#tb_renewal',
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
                {field: 'action', title: 'Action', width: 100, align: 'center'},
              ]],
            view: detailview,
            detailFormatter: function(index, row) {
              return '<div style="padding:2px"><table class="ddv"></table></div>';
            },
            onExpandRow: function(index, row) {
               var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv');
              ddv.datagrid({
                url: '<?php echo base_url("isr_bts/spp_finance/get_spp_finance_detail_list2"); ?>?app_id=' + row.app_id,
                fitColumns: false,
                pagination: false,
                singleSelect: true,
                height: 'auto',
                columns: [[
                    {field: 'station_identifier', title: 'Station ID', width: '80', align: 'center'},
                    {field: 'station_name', title: 'Station Name', width: '150'},
                    {field: 'vendor_id', title: 'Vendor ID', width: '100'},
                    {field: 'height_asl', title: 'Height ASL', width: '80', align: 'right'},
                    {field: 'latitude', title: 'Latitude', width: '100', align: 'right'},
                    {field: 'longitude', title: 'Longitude', width: '100', align: 'right'},
                    {field: 'radius_of_operation', title: 'Radius of Operation', width: '125', align: 'right'},
                    {field: 'site_province', title: 'Site Provin  ce', width: '125'},
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
                  $('#dg_renewal').datagrid('fixDetailRowHeight', index);
                },
                onLoadSuccess: function() {
                  setTimeout(function() {
                    $('#dg_renewal').datagrid('fixDetailRowHeight', index);
                  }, 0);
                }
              });
              $('#dg_renewal').datagrid('fixDetailRowHeight', index);
            }
          });

          $("#q_renewal").keydown(function(e) {
            var code = e.which; // recommended to use e.which, it's normalized across browsers
            if (code == 13) {
              searchRenewal();
              e.preventDefault();
            }
          });

        });

        function searchRenewal() {
          $('#dg_renewal').datagrid('load', {
            q: $('#q_renewal').val(),
          });
        }

        function uploadRenewal(appId) {
          $.fancybox({
            href: "<?php echo base_url() ?>isr_bts/spp_finance/add_renewal_spp_finance/" + appId, type: 'ajax',
            minWidth: 1000, autoCenter: false, scrolling: false, autoSize: true, fitToView: true, closeClick: false, openEffect: 'fade', closeEffect: 'fade', afterShow: function() {
              $('#app_no').focus();
            }
          });
        }

</script>