<!DOCTYPE html>
<html lang="en">
<head>
  <title>Import GAC Data to eatp TRM</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Import GAC Data to eatp TRM</h2>
	<?php 
	if(isset($_GET['berhasil'])){
		echo "<p>".$_GET['berhasil']." Data berhasil di import.</p>";
	}
	?>
	<button onclick="window.location.href = 'upload.php';">IMPORT DATA</button>
  <table class="table table-striped">
    <thead>
      <tr>		
			<th>No</th>
			<th>Purch.Doc.</th>
			<th>Item</th>
			<th>Material Code</th>
			<th>Short Text</th>
			<th>PO Qty</th>
			<th>Region</th>
			<th>EWO</th>
			<th>LINK</th>
      </tr>
    </thead>
    <tbody>
		<?php 
		include 'koneksi.php';
		$no=1;
		$data = mysqli_query($koneksi,"SELECT * FROM tbl_gac_data ORDER BY created_datetime DESC");
		//$data = mysqli_query($koneksi,"SELECT * FROM tbl_gac_data WHERE link='".$_GET['link']."' ORDER BY created_datetime DESC");
		while($d = mysqli_fetch_array($data)){
			?>
			<tr>
				<td><?php echo $no++; ?></td>
				<td><?php echo $d['purch_doc']; ?></td>
				<td><?php echo $d['item']; ?></td>
				<td><?php echo $d['material_code']; ?></td>
				<td><?php echo $d['short_text']; ?></td>
				<td><?php echo $d['po_qty']; ?></td>
				<td><?php echo $d['region']; ?></td>
				<td><?php echo $d['ewo']; ?></td>
				<td><?php echo $d['link']; ?></td>
			</tr>
			<?php 
		}
		?>
    </tbody>
  </table>
</div>

</body>
</html>
