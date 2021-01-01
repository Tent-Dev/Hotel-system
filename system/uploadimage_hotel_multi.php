<?php

include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$targetDir = "../system/upload_hotel/";

//Insert Type
$arr = array( //field ต่างๆ
            "h_type_name"=> $_POST['typename'],
            "h_type_desc"=> $_POST['typedesc'],
            "h_type_price"=> $_POST['typeprice'],
            "h_type_capacity"=> $_POST['typecapacity'],
            "h_type_bed"=> $_POST['typebed'],
            "h_type_bedtotal"=> $_POST['bedtotal']
            );

$t = $mysql->Insert_db($arr,"h_type");

$sql = "SELECT h_type_id FROM h_type
        ORDER BY h_type_id DESC LIMIT 1";
$result = $mysql->select_db($sql);

foreach ($result as $readsql);

if(!empty($_FILES)){

    foreach($_FILES['file']['tmp_name'] as $key => $value) {

        if($_FILES['file']['name'][$key] != "blob"){
            $tempFile = $_FILES['file']['tmp_name'][$key];

            $Filename = uniqid()."-".$_FILES['file']['name'][$key];

            $targetFile =  $targetDir.$Filename ;
            move_uploaded_file($tempFile,$targetFile);

            $arrimage = array(
                            "h_gallery_roomtypeid"=> $readsql['h_type_id'],
                            "h_gallery_filename"=> $targetFile,
                            "h_gallery_filenameorigin" => $Filename
                            );
            
            $t2 = $mysql->Insert_db($arrimage,"h_gallery");
        }
        else{
            $t2 = 1;
        }
    }
}
?>