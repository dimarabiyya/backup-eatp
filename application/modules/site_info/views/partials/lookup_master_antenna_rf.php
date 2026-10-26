<style>
.form-actions { padding:10px 0 10px !important; }
</style>

<div class="widget">
	<div class="widget-head"><h4 class="heading">RF Antenna Model</h4></div>
	
	<div class="widget-body">
	
		<div id="tb-master-antenna-rf" class="form-search">
			<div class="input-append">
				<?php echo form_input('q', '', 'id="q" class="input-medium search-query" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" onclick="return lookupMasterAntennaRF.search()"><i class="icon-search"></i></button>
			</div>
		</div>
		
		<table id="dg-master-antenna-rf"></table>
	
		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnDataSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	
	</div>
	
</div>

<script>
var lookupMasterAntennaRF = {
	params: {
		cmd: 'get_lookup_master_antenna_rf',
		act: 'get_data',
		query: ''
	},
	loadData: function() {
		$('#dg-master-antenna-rf').datagrid({
			url: '<?php echo site_url('/site_info/equipment') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			frozenColumns: [[{ field: 'chkData', checkbox: true }]],
			columns:[[
				{ field: 'antenna_id', hidden: true },
				{ field: 'brand', title: 'Brand', width: 120, sortable: true, rowspan: 2 },
				{ field: 'model', title: 'Model', width: 200, sortable: true, rowspan: 2 },
				{ field: 'band', title: 'Band', width: 120, sortable: true, align: 'center', rowspan: 2 },
				{ field: 'hbwd', title: 'HBWD', width: 120, sortable: true, align: 'center', rowspan: 2 },
				{ field: 'vbwd', title: 'VBWD', width: 120, sortable: true, align: 'center', rowspan: 2 },
				{ field: 'etilt', title: 'Beam Tilt / Etilt (degree)', width: 150, sortable: true, align: 'center', rowspan: 2 },
				{ field: 'polarization', title: 'Polarization', width: 100, sortable: true, align: 'center', rowspan: 2, formatter: function(value, row, index) {
					return value + '&deg;';
				} },
				{ field: 'vswr', title: 'VSWR', width: 100, sortable: true, align: 'center', rowspan: 2 },
				{ field: 'impedance', title: 'Impedance (Ohms)', width: 120, sortable: true, align: 'center', rowspan: 2 },
				{ field: 'gain', title: 'Gain (dBi)', width: 100, sortable: true, align: 'center', rowspan: 2 },
				{ field: 'max_input_power', title: 'Max. Input Power (Watts)', width: 150, sortable: true, align: 'center', rowspan: 2 },
				{ field: 'isolations', title: 'Isolations (dB)', width: 100, sortable: true, align: 'center', rowspan: 2 },
				{ field: 'weight', title: 'Weight (Kg)', width: 100, sortable: true, align: 'center', rowspan: 2 },
				{ title: 'Dimensions (mm)', colspan: 3 }
			], [
				{ field: 'dimension_long', title: 'L', width: 50, sortable: true, align: 'center' },
				{ field: 'dimension_width', title: 'W', width: 50, sortable: true, align: 'center' },
				{ field: 'dimension_height', title: 'H', width: 50, sortable: true, align: 'center' }
			]],
			iconCls: 'icon-save',
			toolbar: '#tb-master-antenna-rf',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: false,
			rownumbers: true,
			width: 740,
			height: 'auto',
			onLoadSuccess: function(data) {
				for (var i = 0; i < data.rows.length; i++) {
					if (data.rows[i].antenna_id == document.getElementsByName('<?php echo $this->input->get('e') ?>')[0].value) {
						$(this).datagrid('checkRow', i);
					}
				}
			},
			onDblClickCell: function(index, field, value) {
				document.getElementById('btnDataSelectedOK').click();
			}
		});
	},
	search: function() {
		this.params.query = document.getElementById('q').value;
		this.loadData();
		document.getElementById('q').select();
		
		return false;
	},
	init: function() {
		this.params.query = document.getElementById('q').value;
		this.loadData();
		
		document.getElementById('q').onkeypress = function(e) {
			if (e.keyCode == 13) {
				lookupMasterAntennaRF.search();
			}
		};
	}
};

$(function() {
	lookupMasterAntennaRF.init();
});
</script>