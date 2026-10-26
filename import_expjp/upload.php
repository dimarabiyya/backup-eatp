<!DOCTYPE html>
<html>
<head>
	<title>Import GAC Data to eatp TRM</title>
</head>
<body>
<h2>Import GAC Data to eatp TRM</h2>

<button onclick="window.location.href = 'index.php';">BACK</button>
<br/><br/>
<?php 
include 'koneksi.php';
?>

<form method="post" enctype="multipart/form-data" action="upload_aksi.php">
	File Chosen: <input name="file_gac" type="file" required="required"><input name="upload" type="submit" value="Import Data">
</form>
<br/>

</body>
</html>