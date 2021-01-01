<?php

include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$sql = "SELECT * FROM h_userrank";
$result = $mysql->Select_db($sql);

foreach ($result as $read) {
	$arr[] = array(
		'h_userrank_id' => $read['h_userrank_id'],
		'h_userrank_name' => $read['h_userrank_name'],
				);
}


echo json_encode($arr);


?>