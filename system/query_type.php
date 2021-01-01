<?php

include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$sql = "SELECT * FROM h_type";
$result = $mysql->Select_db($sql);

foreach ($result as $read) {
	$arr[] = array(
		'h_type_id' => $read['h_type_id'],
		'h_type_name' => $read['h_type_name'],
				);
}


echo json_encode($arr);


?>