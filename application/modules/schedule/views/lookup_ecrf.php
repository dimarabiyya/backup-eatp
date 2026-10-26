<div class="widget">
	<div class="widget-head">
		<h4 class="heading">ECRF List</h4>

	</div>

	<div class="widget-body">
		<div>

			<div class="input-append pull-right">
				<?php echo form_input('q', '', 'id="q" class="input-medium" placeholder="Search..." autocomplete="off"') ?>
				<button class="btn" type="button" onclick="return lookupECRF.search()">Search</button>
			</div>
		</div>

		<table id="gridECRFApproval"></table>

		<div class="form-actions">
			<button type="submit" class="submit btn btn-icon btn-primary glyphicons circle_ok" id="btnDataSelectedOK"><i></i>OK</button>
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
		</div>
	</div>
	</body>

	</html>


</div>

<script>
	var lookupECRF = {
		params: {
			cmd: 'getJson',
			token: "<?php echo $token ?>",
			token_type: " <?php echo $token_type ?>",
			query: '',
			total_search: '',
		},
		loadData: function() {
			$('#gridECRFApproval').datagrid({
				url: '<?php echo site_url('schedule/lookup_ecrf') ?>',
				queryParams: this.params,
				method: 'GET',
				pageSize: 15,
				pageList: [10, 15, 20, 30, 40, 50, 100],
				columns: [
					[{
							field: 'ecrfNumber',
							title: 'ECRF Number',
							width: 100,
							sortable: true
						},
						{
							field: 'siteId',
							title: 'Site ID',
							width: 100,
							sortable: true
						},
						{
							field: 'ecrfType',
							title: 'ECRF Type',
							width: 100,
							sortable: true
						},
						{
							field: 'projectName',
							title: 'Project Name',
							width: 100,
							sortable: true
						},
						{
							field: 'projectStatus',
							title: 'Project Status',
							width: 100,
							sortable: true
						},
						{
							field: 'projectType',
							title: 'Project Type',
							width: 100,
							sortable: true
						},

					]
				],
				iconCls: 'icon-save',
				toolbar: '#tb-users',
				striped: true,
				pagination: true,
				singleSelect: true,
				fitColumns: true,
				rownumbers: true,
				width: 740,
				height: 'auto',
				onLoadSuccess: function(data) {
					dateFormat.init();
					lookupECRF.params.total_search = data.total;
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

			document.getElementById('q').onkeydown = function(e) {
				if (e.keyCode == 13) {
					lookupECRF.search();
				}
			};
		}

	};
	$(function() {
		lookupECRF.init();
	});
</script>