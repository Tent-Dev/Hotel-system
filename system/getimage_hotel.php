<?php

include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$cmd = $_POST['command'];
if ($cmd != "") {

	//Get image gallery
	if ($cmd == "getimagegallery") {
		$sql = "SELECT * FROM h_gallery WHERE h_gallery_roomtypeid = ".$_POST['typeid']."
				AND h_gallery_cover = 0";
		$result = $mysql->Select_db($sql);


		foreach($result as $file){
		        $obj['name'] = $file['h_gallery_filenameorigin']; //get the filename in array
		        $obj['size'] = filesize("../system/upload_hotel/".$file['h_gallery_filenameorigin']); //get the flesize in array
		        $obj['path'] = $file['h_gallery_filename'];
		        $result2[] = $obj; // copy it to another array
		      }
		       header('Content-Type: application/json');
		       echo json_encode($result2); // now you have a json response which you can use in client side
    }

    //Get image cover
	if ($cmd == "getimagecover") {
		$sql = "SELECT * FROM h_gallery WHERE h_gallery_roomtypeid = ".$_POST['typeid']."
				AND h_gallery_cover = 1";
		$result = $mysql->Select_db($sql);


		foreach($result as $file){
		        $obj['name'] = $file['h_gallery_filenameorigin']; //get the filename in array
		        $obj['size'] = filesize("../system/upload_hotel/".$file['h_gallery_filenameorigin']); //get the flesize in array
		        $obj['path'] = $file['h_gallery_filename'];
		        $result2[] = $obj; // copy it to another array
		      }
		       header('Content-Type: application/json');
		       echo json_encode($result2); // now you have a json response which you can use in client side
    }
}
?>