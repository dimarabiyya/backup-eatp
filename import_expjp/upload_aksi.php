<?php 
// menghubungkan dengan koneksi
include 'koneksi.php';
// menghubungkan dengan library excel reader
include "excel_reader2.php";
?>

<?php
// upload file xls
$target = basename($_FILES['file_gac']['name']) ;
move_uploaded_file($_FILES['file_gac']['tmp_name'], $target);

// beri permisi agar file xls dapat di baca
chmod($_FILES['file_gac']['name'],0777);

// mengambil isi file xls
$data = new Spreadsheet_Excel_Reader($_FILES['file_gac']['name'],false);
// menghitung jumlah baris data yang ada
$jumlah_baris = $data->rowcount($sheet_index=0);

if(! $koneksi ) {
	die('Could not connect: ' . mysqli_error());
}
//echo 'Connected successfully<br>';   

$_link = "";
for ($i=2; $i<=$jumlah_baris; $i++){
	$link = $data->val($i, 8);
	if($link != ""){
		$_link = $link;
		//$sql = "DELETE FROM tbl_gac_data WHERE link='$_link'";
	}
}
$sql = "DELETE FROM tbl_gac_data WHERE link='$_link'";
//echo $sql; 
if (mysqli_query($koneksi, $sql)) {
	//echo "Record deleted successfully";
} else {
	echo "Error deleting record: " . mysqli_error($koneksi);
}

// jumlah default data yang berhasil di import
$berhasil = 0;
$link = "";
for ($i=2; $i<=$jumlah_baris; $i++){
	// menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
	$purch_doc     = $data->val($i, 1);
	$item   = $data->val($i, 2);
	$material_code  = $data->val($i, 3);
	$short_text     = $data->val($i, 4);
	$po_qty   = $data->val($i, 5);
	$region  = $data->val($i, 6);
	$ewo  = $data->val($i, 7);
	$link  = $data->val($i, 8);

	if($purch_doc != "" && $item != "" && $material_code != "" && $short_text != "" && $po_qty != "" && $region != "" && $ewo != "" && $link != ""){
		// input data ke database (table data_pegawai)
		$sql = "INSERT into tbl_gac_data(purch_doc,item,material_code,short_text,po_qty,region,ewo,link) VALUES('$purch_doc','$item','$material_code','$short_text','$po_qty','$region','$ewo','$link')";
		if (mysqli_query($koneksi, $sql)) {
		//echo "Record deleted successfully";
		} else {
			echo "Error deleting record: " . mysqli_error($koneksi);
		}
		$berhasil++;
	}
}

mysqli_close($koneksi);

// hapus kembali file .xls yang di upload tadi
unlink($_FILES['file_gac']['name']);

// alihkan halaman ke index.php
header("location:index.php?berhasil=$berhasil&link=$link");
//header("location:index.php?berhasil=$berhasil");
?>