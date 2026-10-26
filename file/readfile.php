<?php

//$files = scandir("C:/xampp/htdocs/e-atp/file/upload/fop");
//print_r($files);
/*
//Get a list of file paths using the glob function.
$fileList = glob('C:/xampp/htdocs/e-atp/file/upload/fop/*');

//Loop through the array that glob returned.
foreach($fileList as $filename){
   //Simply print them out onto the screen.
   echo $filename, '<br>'; 
}
*/
/*
$it = new RecursiveTreeIterator(new RecursiveDirectoryIterator("C:/xampp/htdocs/e-atp/file/upload/fop/", RecursiveDirectoryIterator::SKIP_DOTS));
foreach($it as $path) {
  echo $path."<br>";
  if(is_file($path)){
	  //echo ("$file is a regular file");
	  echo $path."<br>";
  }
}
*/

function getDirContents($dir, &$results = array()){
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

    $files = scandir($dir);
	
	//echo "START READFILE<br/>";
	
    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            $results[] = $path;
        } else if($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
		if(is_file($path)){
			
			if (strpos($path, 'FOPSIGN') !== false) {
				echo $path."<br/>";
				$i=0;
				foreach(explode("FOPSIGN",$path) as $element){
					//echo $i."#".$element."<br/>";
					if($i==1){
						$j=0;
						foreach(explode("_",$element) as $sub_element){
							//echo $sub_element."<br/>";
							if($j==1){
								echo $sub_element."<br/>";
								//$path = str_replace("//","/",$path);
								$path = str_replace("\\","/",$path);
								$sql = "INSERT INTO tbl_filename (filename, user_id) VALUES ('".$path."', '".$sub_element."');";
								if ($conn->query($sql) === TRUE) {
									echo "New record created successfully<br/>";
								} else {
									echo "Error: " . $sql . "<br>" . $conn->error;
								}
							}
							$j++;
						}
					}
					$i++;
				}
			}
			
		}
    }
	//$conn->close();
	//echo "END READFILE<br/>";
    return $results;
}
getDirContents('C:/xampp/htdocs/file/upload/fop/');

//var_dump(getDirContents('C:/xampp/htdocs/e-atp/file/upload/fop/'));
?>