<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$cmd = isset($_POST["command"]) ? $_POST["command"] : "";

if ($cmd != "") {

	//Add room
	if ($cmd == "add_room") {
		$has_same_room = $mysql->Check_same('h_room','h_room_name',$_POST['room_name']);
		$success = 0;
		if(!$has_same_room){
			$arr = array(
					"h_room_name"=> $_POST['room_name'],
					"h_room_type"=> $_POST['room_type'],
					"h_room_status"=> $_POST['room_status']
					);
			$success = $mysql->Insert_db($arr,"h_room");
		}
		$arr = array('success' => $success);
		echo json_encode($arr);
		exit();
		$mysql->Close_db();
	}

	//edit Room
	if ($cmd == "edit_room") {
		$arr = array(
				"h_room_id"=> $_POST['id'],
				"h_room_name"=> $_POST['h_room_name'],
				"h_room_type"=> $_POST['h_room_type'],
				"h_room_status"=> $_POST['h_room_status']
				);
		$key = array("h_room_id");
		$check = $mysql->Update_db($arr,$key,"h_room");
	}

	//Insert bookadmin Transaction
	if ($cmd == "bookcheckbox") {

		$random = $mysql->ran();
		$sumprice = 0;

		session_start();
		$_SESSION['New_booking_Customer'] = $random;

		$dteStart = new DateTime($_POST["checkin"]);
		$dteEnd   = new DateTime($_POST["checkout"]);
		$dteDiff  = $dteStart->diff($dteEnd);
		$sumdate = $dteDiff->format("%a");

		for($i=0;$i<count($_POST["roomid"]);$i++){

			if($_POST["roomid"][$i] != ""){
			  $arr = array (
			          "h_trans_roomid"=> $_POST["roomid"][$i],
			          "h_trans_customerid"=> "1",
			          "h_trans_checkindate"=> $_POST["checkin"],
			          "h_trans_checkoutdate"=> $_POST["checkout"],
			          "h_trans_codesession"=> $random
			          );
			  $mysql->Insert_db($arr,"h_transaction"); 
			  $sumprice += $_POST['roomPrice'][$i]*$sumdate;
			  $sumguests += $_POST['guests'][$i];
			}
		}

		$sumprice = $sumprice+($sumprice/100*7);

		$summary = array (
						"roomid" => $_POST["roomid"],
						"checkin" => date('d/m/Y', strtotime($_POST['checkin'])),
						"checkout" => date('d/m/Y', strtotime($_POST['checkout'])),
						"room" => count($_POST["roomid"]),
						"type" => $_POST["type"],
						"date_sum" => $sumdate,
						"sumprice" => $sumprice,
						"sumguests" => $sumguests,
						"session" => $random
						);
		echo json_encode($summary);
		exit();
	}

	//updateType
	if ($cmd == "updatetype") {
		$arr = array( //field ต่างๆ
			"h_type_id"=> $_POST['typeid'],
			"h_type_name"=> $_POST['typename'],
			"h_type_price"=> $_POST['typeprice'],
			"h_type_desc"=> $_POST['typedesc'],
			"h_type_bed"=> $_POST['typebed'],
			"h_type_bedtotal"=> $_POST['typebedtotal'],
			"h_type_capacity"=> $_POST['typecapacity']
		);
		$key = array("h_type_id");
		$check = $mysql->Update_db($arr,$key,"h_type"); //ตารางที่จะupdate
		if($check == 1){
			?>
			<h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Update success</h5>
			<?php
			echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_type.php'/>";
		} else{
			?> <div style="text-align: center; padding: 20px;">
					<h5>update fail</h5>
					<h5><i class="fas fa-spinner fa-spin"></i></h5>
				</div>
		<?php
		}
	}

	//deleteType
	if ($cmd == "deletetype") {
			//ลบไฟล์รูปออกจากเซิฟก่อน
			$targetDir_inside = "upload_hotel/";

			$sqlimageDirectory = "SELECT h_gallery_filenameorigin FROM h_gallery
								  WHERE h_gallery_roomtypeid = '".$_POST['typeid']."'";
			$checkimageDirectory = $mysql->select_db($sqlimageDirectory);

			foreach ($checkimageDirectory as $readimageDirectory) {
				//file_exists เช็คว่ามีไฟล์อยู่ใน directory มั้ย
				if (file_exists($targetDir_inside.$readimageDirectory['h_gallery_filenameorigin'])) {
	            unlink($targetDir_inside.$readimageDirectory['h_gallery_filenameorigin']);
	            //echo $targetDir_inside.$readimageDirectory['h_gallery_filenameorigin']."<br>";
        		}
			}
			$sqlimage = "DELETE FROM h_gallery WHERE h_gallery_roomtypeid = '".$_POST['typeid']."'";
			$checkimage = $mysql->Delete_db($sqlimage);

			$sql = "DELETE FROM h_type WHERE h_type_id = '".$_POST['typeid']."'";
			$check = $mysql->Delete_db($sql);
			$mysql->Close_db();

			if($check == 1){
				?>
				<h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Delete success</h5>
				<?php
				echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_type.php'/>";
			}else{
				?>
				<h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Fail</h5>
				<?php
				echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_type.php'/>";
			}
	}

	//deleteImage
	if ($cmd == "deleteimage") {
		$targetDir_inside = "upload_hotel/";
		$sqlimage = "DELETE FROM h_gallery
					 WHERE h_gallery_filenameorigin = '".$_POST['image_name']."'
					 AND h_gallery_roomtypeid = '".$_POST['typeid']."'";
		$checkimageDb = $mysql->Delete_db($sqlimage);

		//Delete image selected (แบบ รูปเดี่ยว ไม่ใช้ Array)
		$imageDelName = $_POST['image_name'];

		//ลบไฟล์ออกจาก server
		if (file_exists($targetDir_inside.$imageDelName)) { //file_exists เช็คว่ามีไฟล์อยู่ใน directory มั้ย
			unlink($targetDir_inside.$imageDelName);
		}
	}

	//statustype
	if ($cmd == "statustype") {
		$arr = array( //field ต่างๆ
			"h_type_id"=> $_POST['typeid'],
			"h_type_statustouser"=> $_POST['statustouser']
		);
		$key = array("h_type_id");
		$check = $mysql->Update_db($arr,$key,"h_type"); //ตารางที่จะupdate
	}

	//statusrate
	if ($cmd == "statusrate") {
		$arr = array( //field ต่างๆ
			"h_rate_id"=> $_POST['rateid'],
			"h_rate_statustouser"=> $_POST['statustouser']
		);
		$key = array("h_rate_id");
		$check = $mysql->Update_db($arr,$key,"h_rate"); //ตารางที่จะupdate
	}

	//insertrate
	if ($cmd == "addrate") {
		$arr = array( //field ต่างๆ
				"h_rate_name"=> $_POST['ratename'],
				"h_rate_desc"=> $_POST['ratedesc'],
				"h_rate_discount"=> $_POST['ratediscount'],
				"h_rate_discountset"=> $_POST['ratediscountset'],
				"h_rate_statustouser"=> $_POST['rate_statustouser'],
				"h_rate_dateset"=> $_POST['rate_dateset'],
				"h_rate_datestart"=> $_POST['rate_datestart'],
				"h_rate_dateend"=> $_POST['rate_dateend'],
				"h_rate_deposit"=> $_POST['rate_deposit']
				);

		$t = $mysql->Insert_db($arr,"h_rate");

		$sqlrate = "SELECT h_rate_id FROM h_rate
					ORDER BY h_rate_id DESC LIMIT 1 ";
		$resultrate = $mysql->Select_db($sqlrate);
		foreach ($resultrate as $readrate);

		for($i=0; $i< count($_POST['type']); $i++){
			$arr2 = array( //field ต่างๆ
				"h_mix_ratetype_rateid"=> $readrate['h_rate_id'],
				"h_mix_ratetype_typeid"=> $_POST['type'][$i]
				);

			$t2 = $mysql->Insert_db($arr2,"h_mix_ratetype");
		}
		
		if ($t == 1){ ?>
			<div class="container">
			<div style="margin-top: 60px; text-align: center; padding: 20px;">
			<i class="fas fa-check-circle" style="font-size: 80px;"></i>
			<h1 style="margin-bottom: 20px;">Added</h1>
			loading...<hr>
			<h2><i class="fas fa-spinner fa-spin" style="width: 50%;"></i></h2>
			</div>
			<?php echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_rate.php'/>";
		}
		else{
			echo json_encode($arr);
		}
		//echo json_encode($arr);
		$mysql->Close_db();
	}

	//deleteRate
	if ($cmd == "deleterate") {
			//ลบใน h_mix ก่อน เพราะเป็น forienkey
			$sqlmix = "DELETE FROM h_mix_ratetype WHERE h_mix_ratetype_rateid = '".$_POST['rateid']."'";
			$checkmix = $mysql->Delete_db($sqlmix);

			$sql = "DELETE FROM h_rate WHERE h_rate_id = '".$_POST['rateid']."'";
			$check = $mysql->Delete_db($sql);
			$mysql->Close_db();

			if($check == 1){
				?>
				<h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Delete success</h5>
				<?php
				echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_rate.php'/>";
			}
	}

	//search_mix_ratetype
	if ($cmd == "search_mix_ratetype") {

		$sql= "SELECT h_mix_ratetype_typeid FROM h_mix_ratetype
			   WHERE h_mix_ratetype_rateid = '".$_POST['rateid']."'";

		$result = $mysql->select_db($sql);

		foreach ($result as $read) {
			$arr[] = array('h_mix_ratetype_typeid' => $read['h_mix_ratetype_typeid']);
		}
		echo json_encode($arr);
	}

	//updateRate
	if ($cmd == "updaterate") {
		$arr = array( //field ต่างๆ
				"h_rate_id"=> $_POST['rateid'],
				"h_rate_name"=> $_POST['ratename'],
				"h_rate_desc"=> $_POST['ratedesc'],
				"h_rate_discount"=> $_POST['ratediscount'],
				"h_rate_discountset"=> $_POST['ratediscountset'],
				"h_rate_statustouser"=> $_POST['rate_statustouser'],
				"h_rate_dateset"=> $_POST['rate_dateset'],
				"h_rate_datestart"=> $_POST['rate_datestart'],
				"h_rate_dateend"=> $_POST['rate_dateend'],
				"h_rate_deposit"=> $_POST['rate_deposit']
		);

		$key = array("h_rate_id");
		$check = $mysql->Update_db($arr,$key,"h_rate"); //ตารางที่จะupdate

		//Delete mix record for add new mix
		$sqlmix = "DELETE FROM h_mix_ratetype
				   WHERE h_mix_ratetype_rateid = '".$_POST['rateid']."'";
		$checkmix = $mysql->Delete_db($sqlmix);

		//add new mix
		for($i=0; $i< count($_POST['type']); $i++){
			$arr2 = array( //field ต่างๆ
				"h_mix_ratetype_rateid"=> $_POST['rateid'],
				"h_mix_ratetype_typeid"=> $_POST['type'][$i]
				);
			$t2 = $mysql->Insert_db($arr2,"h_mix_ratetype");
		}

		if($check == 1){
			?>
			<h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Update success</h5>
			<?php
			echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_rate.php'/>";
		} else{
			?> <div style="text-align: center; padding: 20px;">
					<h5>update fail</h5>
					<h5><i class="fas fa-spinner fa-spin"></i></h5>
				</div>
		<?php
		}
	}

	//Customer Edit information
	if ($cmd == "customerEdit_fromAdmin") {
		$arr = array( //field ต่างๆ
				"h_customer_id"=> $_POST['cusId'],
				"h_customer_firstname"=> $_POST['cusFname'],
				"h_customer_lastname"=> $_POST['cusLname'],
				"h_customer_email"=> $_POST['cusEmail'],
				"h_customer_phone"=> $_POST['cusPhone'],
				"h_customer_region"=> $_POST['cusRegion'],
				"h_customer_address"=> $_POST['cusAddress'],
				"h_customer_city"=> $_POST['cusCity'],
				"h_customer_postal"=> $_POST['cusPostal']
		);

		$key = array("h_customer_id");

		$check = $mysql->Update_db($arr,$key,"h_customer"); //ตารางที่จะupdate

		if($check == 1){
			?>
			<h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Update success</h5>
			<?php
			echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_booklist.php'/>";
		} else{
			?> <div style="text-align: center; padding: 20px;">
					<h5>update fail</h5>
					<h5><i class="fas fa-spinner fa-spin"></i></h5>
				</div>
		<?php
			echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_booklist.php'/>";
		}
	}

	//Confirm Check in Bill
	if ($cmd == "billConCheckin_fromAdmin") {

		//Update Field
		$arr = array(
					"h_bill_customerid" => $_POST['cusId'],
					"h_bill_status" => "2"
				);
		$key = array("h_bill_customerid");
		$check = $mysql->Update_db($arr,$key,"h_bill");

		//Update Transaction
		$arrTrans = array(
					"h_trans_customerid" => $_POST['cusId'],
					"h_trans_bill_status" => "2"
				);
		$keyTrans = array("h_trans_customerid");
		$checkTrans = $mysql->Update_db($arrTrans,$keyTrans,"h_transaction");

		if($check && $checkTrans){
			?>
				<h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Check in Confirmed success</h5>
				<?php echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_booklist.php'/>";
		}
	}

	//Confirm Check out Bill
	if ($cmd == "billConCheckout_fromAdmin") {

		//Update Field
		$arr = array( //field ต่างๆ
			"h_bill_customerid" => $_POST['cusId'],
			"h_bill_status" => "3",
			"h_bill_copy" => $_POST['saveall'],
			"h_bill_otherprice" => $_POST['other_price'],
			"h_bill_price" => $_POST['total_price']
		);
		$key = array("h_bill_customerid");
		$check = $mysql->Update_db($arr,$key,"h_bill");

		//Update Transaction
		$arrTrans = array( //field ต่างๆ
			"h_trans_customerid" => $_POST['cusId'],
			"h_trans_bill_status" => "3"
		);
		$keyTrans = array("h_trans_customerid");
		$checkTrans = $mysql->Update_db($arrTrans,$keyTrans,"h_transaction");

		if($check && $checkTrans){
			?>
				<h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Check out Confirmed success</h5>
				<?php echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_booklist.php'/>";
		}
	}

	//deleteBill
	if ($cmd == "billCancel_fromAdmin") {

		//Update Field
		$arr = array( //field ต่างๆ
			"h_bill_customerid" => $_POST['cusId'],
			"h_bill_status" => "4"
		);
		$key = array("h_bill_customerid");
		$check = $mysql->Update_db($arr,$key,"h_bill");

		//Update Transaction
		$arrTrans = array( //field ต่างๆ
			"h_trans_customerid" => $_POST['cusId'],
			"h_trans_bill_status" => "4"
		);
		$keyTrans = array("h_trans_customerid");
		$checkTrans = $mysql->Update_db($arrTrans,$keyTrans,"h_transaction");

		if($check && $checkTrans){
			?>
				<h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Canceled success</h5>
				<?php echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_booklist.php'/>";
		}
	}

	//Add account
	if ($cmd == "signup") {
		$has_specialChars_user = $mysql->Check_special_chars($_POST['user']);
		$has_specialChars_password = $mysql->Check_special_chars($_POST['pass']);
		$has_same_username = $mysql->Check_same('h_user','h_user_username',$_POST['user']);
		$success = 0;
		if(!$has_specialChars_user && !$has_specialChars_password && !$has_same_username){
			$arr = array(
					"h_user_username"=> $_POST['user'],
					"h_user_password"=> password_hash($_POST['pass'], PASSWORD_BCRYPT, array('cost'=>12)),
					"h_user_rank"=> $_POST['rank'],
					"h_user_firstname"=> $_POST['Fname'],
					"h_user_lastname"=> $_POST['Lname'],
					"h_user_image" => ""
					);
			$success = $mysql->Insert_db($arr,"h_user");
		}
		if($has_same_username){
			$success = 2;
		}
		$arr = array('success' => $success);
		echo json_encode($arr);
		exit();
		$mysql->Close_db();
	}

	//Edit account
	if ($cmd == "edit_account") {
		$arr = array( //field ต่างๆ
				"h_user_id"=> $_POST['userid'],
				"h_user_firstname"=> $_POST['Fname'],
				"h_user_lastname"=> $_POST['Lname'],
				"h_user_rank"=> $_POST['rank']
				);
		$key = array("h_user_id");
		$check = $mysql->Update_db($arr,$key,"h_user"); //ตารางที่จะupdate

		if($_POST['reset_password'] != ""){
			$new_password = password_hash($_POST['reset_password'], PASSWORD_BCRYPT, array('cost'=>12));
			$arr = array( "h_user_id"=> $_POST['userid'], "h_user_password"=> $new_password );
			$check = $mysql->Update_db($arr,$key,"h_user");
		}
	}

	//Add Rank
	if ($cmd == "add_rank") {
		$arr = array(
				"h_userrank_name"=> $_POST['rank_name'],
				"h_userrank_permission"=> $_POST['rank_permission']
				);

		$t = $mysql->Insert_db($arr,"h_userrank");
		$mysql->Close_db();
	}

	//Edit rank
	if ($cmd == "edit_rank") {
		$arr = array(
				"h_userrank_id"=> $_POST['id'],
				"h_userrank_name"=> $_POST['h_userrank_name'],
				"h_userrank_permission"=> $_POST['h_userrank_permission']
				);
		$key = array("h_userrank_id");
		$check = $mysql->Update_db($arr,$key,"h_userrank");
	}

	//Add Bed
	if ($cmd == "add_bed") {
		$arr = array(
				"h_type_bed_name"=> $_POST['bed_name'],
				"h_type_bed_desc"=> $_POST['bed_desc'],
				"h_type_bed_image"=> $_POST['bed_image']
				);

		$t = $mysql->Insert_db($arr,"h_type_bed");
		var_dump($arr);
		$mysql->Close_db();
	}

	//Edit bed
	if($cmd == "edit_bed"){

		$sql = "SELECT h_type_bed_image FROM h_type_bed WHERE h_type_bed_id = '".$_POST['id']."'";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read);
		if($read['h_type_bed_image'] != "" && $_POST['h_type_bed_image'] != $read['h_type_bed_image'] && $_POST['h_type_bed_image'] != "" && $read['h_type_bed_image'] != "../system/upload_icon/404.png"){
			unlink($read['h_type_bed_image']);
		}

		$arr = array(
				"h_type_bed_id"=> $_POST['id'],
				"h_type_bed_name"=> $_POST['h_type_bed_name'],
				"h_type_bed_desc"=> $_POST['h_type_bed_desc'],
				"h_type_bed_image"=> $_POST['h_type_bed_image']
				);
		$key = array("h_type_bed_id");
		$check = $mysql->Update_db($arr,$key,"h_type_bed");
	}

	//Add Status
	if ($cmd == "add_status") {
		$arr = array(
				"h_status_name"=> $_POST['status_name'],
				"h_status_statustouser"=> $_POST['status_touser'],
				"h_status_color"=> $_POST['status_color']
				);
		$t = $mysql->Insert_db($arr,"h_status");
		$mysql->Close_db();
	}

	//Edit Status
	if($cmd == "edit_status"){
		$arr = array(
				"h_status_id"=> $_POST['id'],
				"h_status_name"=> $_POST['h_status_name'],
				"h_status_statustouser"=> $_POST['h_status_statustouser'],
				"h_status_color"=> $_POST['h_status_color']
				);
		$key = array("h_status_id");
		$check = $mysql->Update_db($arr,$key,"h_status");
	}

	//Delete DML
	if ($cmd == "delete_dml") {
		if(isset($_POST['image_field'])){
			$mysql->delete_Image($_POST['image_field'],$_POST['from_table'],$_POST['where_field'],$_POST['id']);
		}
		$sql = "DELETE FROM ".$_POST['from_table']." WHERE ".$_POST['where_field']." = '".$_POST['id']."'";
		$check = $mysql->Delete_db($sql);
		$mysql->Close_db();
	}

	//Delete more DML
	if ($cmd == "deletemore_dml") {
		for($i=0;$i<count($_POST["id"]);$i++){
			if($_POST["id"][$i] != ""){
				if(isset($_POST['image_field'])){
					$mysql->delete_Image($_POST['image_field'],$_POST['from_table'],$_POST['where_field'],$_POST['id'][$i]);
				}
				$sql = "DELETE FROM ".$_POST['from_table']." WHERE ".$_POST['where_field']." = '".$_POST["id"][$i]."' ";
				$check = $mysql->Delete_db($sql);
			}
		}
		$mysql->Close_db();
	}

	//Checkข้อมูลซ้ำ DML
	if ($cmd == "checkSame_dml") {
		if(!empty($_POST["element"]) && !empty($_POST["value"])) {
			  $sql = "SELECT * FROM ".$_POST["from_table"]." WHERE ".$_POST["where_field"]." = '".$_POST["value"]."'";
			  $count = $mysql->numRows($sql);
			  if($count>0) {
			  	if($_SERVER['HTTP_REFERER'] == $BASE_domain.'/system_hotel_admin_rooms_setting.php'){
			    	echo "<span class='status-available' id='notAvailable' style='color: red;'> ห้องนี้มีอยู่ในระบบแล้ว</span>";
			  	}else{
			  		echo "<span class='status-available' id='notAvailable' style='color: red;'> ชื่อบัญชีนี้มีผู้ใช้แล้ว</span>";
			  	}
			  }else{
			  	if($_SERVER['HTTP_REFERER'] == $BASE_domain.'/system_hotel_admin_rooms_setting.php'){
			  		echo "<span class='status-available' id='available' style='color: green;'> ห้องนี้ยังไม่มีในระบบ</span>";
			  	}else{
			  		echo "<span class='status-available' id='available' style='color: green;'> ชื่อบัญชีนี้ยังไม่มีผู้ใช้</span>";
			  	}   
			  }
		}else if($_POST["value"] == ""){
			echo "<span class='status-available' style='color: red;'></span>";
		}
	}
}

?>