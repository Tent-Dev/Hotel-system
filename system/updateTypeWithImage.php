<?php

include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$targetDir = "../system/upload_hotel/";
$targetDir_inside = "upload_hotel/";
//update Type
$arr = array( //field ต่างๆ
            "h_type_id"=> $_POST['typeidUP'],
            "h_type_name"=> $_POST['typenameUP'],
            "h_type_desc"=> $_POST['typedescUP'],
            "h_type_price"=> $_POST['typepriceUP'],
            "h_type_capacity"=> $_POST['typecapacityUP'],
            "h_type_bed"=> $_POST['typebedUP'],
            "h_type_bedtotal"=> $_POST['typebedtotalUP']
            );
$key = array("h_type_id");

$t = $mysql->Update_db($arr,$key,"h_type");

if(!empty($_FILES)){

    foreach($_FILES['file']['tmp_name'] as $key => $value) {

        if($_FILES['file']['name'][$key] != "blob"){
            $tempFile = $_FILES['file']['tmp_name'][$key];

            $Filename = uniqid()."-".$_FILES['file']['name'][$key];

            $targetFile =  $targetDir.$Filename ;
            move_uploaded_file($tempFile,$targetFile);

            $arrimage = array(
                            "h_gallery_roomtypeid"=> $_POST['typeidUP'],
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

//Delete image selected
$imageDelName = explode(",",$_POST['imageNameDel']);

if(count($imageDelName) > 0 && !in_array("", $imageDelName)){ // in_array เช็คว่า ถ้ามี ... ในarray นั้นๆ

    foreach($imageDelName as $key => $value) {
        $sqlimage = "DELETE FROM h_gallery
                     WHERE h_gallery_filenameorigin = '".$imageDelName[$key]."'
                     AND h_gallery_roomtypeid = '".$_POST['typeidUP']."'
                     AND h_gallery_cover = 0";
        $checkimage = $mysql->Delete_db($sqlimage);

        //ลบไฟล์ออกจาก server
        if (file_exists($targetDir_inside.$imageDelName[$key])) { //file_exists เช็คว่ามีไฟล์อยู่ใน directory มั้ย
            unlink($targetDir_inside.$imageDelName[$key]);
        }
    }
}