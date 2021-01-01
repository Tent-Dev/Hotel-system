<?php

include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$sql = "SELECT * FROM h_type_bed";
$result = $mysql->Select_db($sql);

foreach ($result as $read) {
	$arr[] = array(
		'h_type_bed_id' => $read['h_type_bed_id'],
		'h_type_bed_name' => $read['h_type_bed_name'],
				);
}


echo json_encode($arr);


?>