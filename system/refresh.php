<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();

if(isset($_SESSION['New_booking_Customer'])){

	$save = $_SESSION['New_booking_Customer'];

}else{
	$save = "0";
}


include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();



$sql = "SELECT * FROM h_transaction
		WHERE h_trans_codesession = '$save' ";

$result = $mysql->Select_db($sql);
$check = $mysql->numRows($sql);

if($check == 0){

	// $message = "Session Expried";
	// echo "<script type='text/javascript'>
	// 		alert('$message');
	// 		document.location.href = '../system_hotel_user.php';
	// 	</script>";
	//header("Location:../system_hotel_user_find.php");
	//session_unset($_SESSION['New_booking_Customer']);
	$time = 0;

	$arr = array('time' => $time);
	echo json_encode($arr);

}else {
	// echo $check." - ".$save;

	$time = 1;
	
	$arr = array('time' => $time);
	echo json_encode($arr);

}


?>