<?php

include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$sql = "SELECT * FROM h_status";
$result = $mysql->Select_db($sql);

foreach ($result as $read) {
	$arr[] = array(
		'h_status_id' => $read['h_status_id'],
		'h_status_name' => $read['h_status_name'],
				);
}
echo json_encode($arr);
?>