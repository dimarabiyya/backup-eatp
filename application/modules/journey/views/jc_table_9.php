<?php echo form_open('index.php/jc/journey/ds'); ?>
<div id="pesan" class="pesan"> </div>

	<table class="item-list">
		<thead>
			<tr>
				<th colspan="2">Maintained Agents</th>
			</tr>
		</thead>
		<?php
			$jumlah = 0;
			foreach ($channels_maintained[0] as $key) {
				echo '<tr>';
				echo '<td>'.$key['agent_status'].'</td>';
				echo '<td align="center">'.$key['count'].'</td>';
				echo '</tr>';
				$jumlah = $jumlah+$key['count'];
			};
		?>
		<tr>
			<td><strong>Jumlah (&Sigma;)</strong></td>
			<td align="center" class="channel-count"><strong><?php echo $jumlah?></strong></td>
		</tr>

	</table>
<?php echo form_close() ; ?>
<div class="clear"></div>

<?php echo form_open('index.php/jc/save_jc'); ?>

<table class="item-list" id="journey-cycle">
	<thead>
		<tr>
			<th colspan="3"><h3>Bulan: <?php echo date('F Y', mktime(0,0,0, substr($calendar, -2), 1, substr($calendar,0,4))); ?> </h3></th>
			<?php
				$span = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) + 1;
				echo '<th colspan="'.$span.'">Agent ID atau Agent Name: <input type="text" name="search" id="search" value=""> <button id="keyword">Search</button> </th>';
			?>
		</tr>
		<tr>
			<th>No</th>
			<th>Channel ID</th>
			<th>Channel Name</th>
			<?php
				$month = substr($calendar,-2);
				$year  = substr($calendar,0,4);

				for ($i=1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year) ; $i++) { 
					echo '<th class="day-'.$i.'">'.$i.'</th>';
				}
					// echo gmDate('d-m-Y',strtotime('20120812'));
			?>
		</tr>
	</thead>

	<?php // if ($jumlah > 0) : ?>
		<tfoot>
			<tr>
				<td colspan="3" style="text-align: right">&Sigma;</td>
				<?php 
					for ($i=1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year) ; $i++) { 
						echo '<td>'.$daily_counts["d$i"].'</td>';
					}
				?>
			</tr>
			<tr>
				<td colspan="<?php echo $span+4; ?>" class="load-more">
					<a id="loadmore" style="display:block; text-align:center">Load More Agents</a>
					<input type="hidden" name="page" id="page" value="1">
				</td>
			</tr>
		</tfoot>
	<?php // endif; ?>
	
	<tbody>
		<?php
			if (count($journey) == 0) {
				$colspan = cal_days_in_month(CAL_GREGORIAN, $month, $year)+4;
				$css = 'padding: 8px; font-size: 14px; color: #003DF5;';
				echo '<tr><td colspan = '.$colspan.' style="'.$css.'"><strong>Tidak ada Journey Cycle pada bulan '.date('F Y', mktime(0,0,0, substr($calendar, -2), 1, substr($calendar,0,4))).'</strong></td></tr>';
			}
			$x = 1;
			foreach ($journey as $key) {
				echo '<tr>';
				echo '<td align="center">'.$x++.'</td>';
				echo '<td>'.$key['partner_id'].'</td>';
				echo '<td>'.$key['territory_name'].'</td>';
				// echo '<td>'.$month.'</td>';
				

				for ($i=1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year) ; $i++) {
					$date = intval($year.$month.substr("00" .$i, -2));

					if (date("W", strtotime($date)) == date('W') ) 
						{ $current = 'current-week '.date("W", strtotime($date)).' '.$date ; }
					else if (date("W", strtotime($date)) < date('W') )
						{ $current = 'past '.date("W", strtotime($date)).' '.$date; }
					else 
						{ $current =  date("W", strtotime($date)).' '.$date;}
					if (date("l", strtotime($date)) == 'Sunday')
						{ $current = 'sunday'; }

					// Determine holidays
					foreach ($holidays as $freeday) {
						if(intval($freeday->tanggal) == $date)
							{ $current .= ' holidays'; }
					}

					if ($key["d$i"] == 1) 
						{
							if ($date < intval(date('Ymd', time())) )
								{ echo '<td class="d'.$i.' '.$current.'"><img src="'.base_url().'file/images/tick-2.png" /></td>'; } 
							else
								{ echo '<td class="d'.$i.' '.$current.'"><input type="checkbox" name="journey" class="jc" id="'.$key['partner_id'].'_'.$key['t_id'].'_'.$year.$month.'_d'.$i.'" onchange="simpan(\''.$key['partner_id'].'_'.$key['t_id'].'_'.$year.$month.'_d'.$i.'\')" checked="checked" ></td>'; }
						}
					else if ($date < intval(date('Ymd', time())) )
						{ echo '<td class="d'.$i.' '.$current.'"><img src="'.base_url().'file/images/minus-small.png" /> </td>'; }
					else 
						{ echo '<td class="d'.$i.' '.$current.'"><input type="checkbox" name="journey" class="jc" id="'.$key['partner_id'].'_'.$key['t_id'].'_'.$year.$month.'_d'.$i.'" onchange="simpan(\''.$key['partner_id'].'_'.$key['t_id'].'_'.$year.$month.'_d'.$i.'\')" ></td>'; }
					}
				echo '</tr>';
			}
		?>
		
	</tbody>
</table>

<?php echo form_close() ; ?>

<div class="clear"></div>
<?php if (count($holidays) > 0 ) : ?>
<div id="holidays">
	<h3>Hari Libur Nasional</h3>
	<ul class="holiday">
		<?php 
			foreach ($holidays as $freeday) {
				// print_r($freeday);
				$date = substr($freeday->tanggal, -2);
				$month = substr($freeday->tanggal, 4, 2);
				$year = substr($freeday->tanggal, 0, 4);
				echo '<li>'. date("d F Y", mktime(0,0,0, $month, $date, $year)) .' &raquo; '. $freeday->keterangan .'<li>';
			}
		?>
	</ul>
</div>
<?php endif; ?>

<script type="text/javascript">
	$(document).ready(function() {
		if($("td.channel-count").text() == $("table#journey-cycle>tbody>tr").length)
			{ $('table#journey-cycle tfoot td.load-more').show(); }

		$('.pesan').hide();

		$("#keyword").click(function(event){
			if( $('#sales-id').val() == '' || $('#sales-id').val() == null || $('#selling-type').val() == 0 ) 
				{ 
					event.preventDefault();
					alert('Nama Sales dan Tipe Penjualan \n tidak boleh kosong'); 
				}
			else 
			{
				var sales_id	   = $('#sales-id').val().split('_');
				var agent_type	 = $('#agent-type').val();
				var selling_type = $('#selling-type').val();
				var month		     = $('#month').val();
				var keyword      = $('#search').val()

				event.preventDefault();
				$('table#journey-cycle tbody').empty();
				// $('#jc-table').empty(); 
				$('#populate').show();
				$('.agents-maintained').empty();
				$.post(
					'<?php echo base_url()?>index.php/jc/load_journey',
					{sales_id:sales_id[1], user_group_id:sales_id[0], agent_type:agent_type,selling_type:selling_type,month_work:month, keyword:keyword},
					function(data) {
						if(data.channels_maintained == 0 ) {
							$('#jc-table').show(); $('#populate').hide();
							$('#jc-table').html('Tidak ada Agent yang dikelola');
						}
						else {
							$('table#journey-cycle tbody').append(data._summary);
							// $('#jc-table').show(); 
							$('#populate').hide();
							// $('#jc-table').html(data.journey_summary);
						}
					},"json"
				);
			}
		});

		var sales_id	 = $('#sales-id').val().split('_');
		var agent_type   = $('#agent-type').val();
		var selling_type = $('#selling-type').val();
		var month        = $('#month').val();
		var keyword      = $('#search').val();

		$('#loadmore').click(function(){
		var row = $('table#journey-cycle tbody').children().length ;
				$.post(
					'<?php echo base_url(); ?>index.php/jc/load_journey',
					{sales_id:sales_id[1], user_group_id:sales_id[0], agent_type:agent_type,selling_type:selling_type,month_work:month, keyword:$('#search').val(), _s1:true, _s2:row, _s3:10},
					function(data) {
						var new_data = JSON.parse(data);
						$('table#journey-cycle tbody').append(new_data._summary);
						if (new_data._count < 10 || $("td.channel-count").text() == $("table#journey-cycle>tbody>tr").length ) 
							{ $('table#journey-cycle tfoot td.load-more').hide(); }
					}
				);
		}) ;

	});
</script>