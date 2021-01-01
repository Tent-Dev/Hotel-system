<?php

include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$targetDir = "../system/upload_hotel/";
$targetDir_inside = "upload_hotel/";

if(!empty($_FILES) && $_FILES['file']['name'] != "blob"){

    $sql = "DELETE FROM h_gallery WHERE h_gallery_roomtypeid = '".$_POST['typeidCo']."'
            AND h_gallery_cover = 1";
    $check = $mysql->Delete_db($sql);

    $tempFile = $_FILES['file']['tmp_name'];

    $Filename = uniqid()."-".$_FILES['file']['name'];

    $targetFile =  $targetDir.$Filename ;
    move_uploaded_file($tempFile,$targetFile);

    $arrimage = array(
                    "h_gallery_roomtypeid"=> $_POST['typeidCo'],
                    "h_gallery_filename"=> $targetFile,
                    "h_gallery_filenameorigin" => $Filename,
                    "h_gallery_cover" => "1"
                    );

    $t2 = $mysql->Insert_db($arrimage,"h_gallery");

    //ถ้าอัพโหลดมา แสดงว่าต้องมีไฟล์ใหม่มาแทนแน่นอน เลยดักไว้ว่า ต้องลบไฟล์เก่าทิ้ง
    if ($_POST['oldimage'] !== "" && file_exists($targetDir_inside.$_POST['oldimage'])) { 
        unlink($targetDir_inside.$_POST['oldimage']);
    }
}
else{
    $t2 = true; //ไม่มีไฟล์ เลยให้เป็น true
}

if($t2){ ?>
    <div class="container">
        <div style="margin-top: 60px; text-align: center; padding: 20px;">
            <i class="fas fa-check-circle" style="font-size: 80px;"></i>
            <h1 style="margin-bottom: 20px;">Update success</h1>
            loading...<hr>
            <h2><i class="fas fa-spinner fa-spin" style="width: 50%;"></i></h2>
        </div>
    </div>
        <?php
        echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_type.php'/>";
} else{ ?>
    <div style="text-align: center; padding: 20px;">
        <h5>Update fail</h5>
        <h5><i class="fas fa-spinner fa-spin"></i></h5>
    </div>
    <?php
        echo "<meta http-equiv='refresh' content='5;URL=../system_hotel_admin_type.php'/>";
        // error_reporting(E_ALL);
        //  ini_set("display_errors", 1);
        }
?>