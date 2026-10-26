<?php

//echo copy("C:/xampp/htdocs/e-atp/file/upload/fop/2017/03/14/FOPSIGN_20170314_190305.png","C:/xampp/htdocs/e-atp/file/upload/fop_if_empty/20170314.png");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trm_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id_data, user_id, filename FROM tbl_filename WHERE id_data IN (SELECT MAX(id_data) FROM tbl_filename GROUP BY user_id)";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "filename: " . $row["filename"]. " - user_id: " . $row["user_id"]. "<br/>";
		$ext = pathinfo($row["filename"], PATHINFO_EXTENSION);
		copy($row["filename"],"C:/xampp/htdocs/file/upload/fop_if_empty/".$row["user_id"].".".$ext);
    }
} else {
    echo "0 results<br/>";
}
$conn->close();

?>