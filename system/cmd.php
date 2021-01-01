<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
include("class_db.php");

$First_name = $_POST['Fname'];
$Last_name = $_POST['Lname'];
$username = $_POST['user'];
$password = $_POST['pass'];
$confirm_password = $_POST['Conpass'];
$oldpassword =  $_POST['oldpassword'];

$Approve = $_POST['approve'];
$UID = $_POST['UID'];
$image = $_POST['image'];

$capacity = $_POST['capacity'];

$cusFname = $_POST['guest_firstname'];
$cusLname = $_POST['guest_lastname'];
$cusEmail = $_POST['guest_email'];
$cusRegion = $_POST['guest_region'];
$cusAddress = $_POST['guest_address'];
$cusCity = $_POST['guest_city'];
$cusPostal = $_POST['guest_postal'];
$cusRegioncode = $_POST['guest_regioncode'];
$cusPhone = $_POST['guest_phone'];
$cusSession = $_POST['guest_codesession'];
$cusTotal = $_POST['guest_total'];
$cusPaymenttype = $_POST['guest_paymenttype'];
$cusRate = $_POST['guest_rate'];
$cusRateid = $_POST['guest_rateid'];
$cusSpecial = $_POST['guest_special'];

$discount = "This price is included with your rate select.";

if($cusSpecial ==""){
	$cusSpecial = "-";
}


$checkin = date('d/m/Y', strtotime($_POST['checkin']));
$checkout = date('d/m/Y', strtotime($_POST['checkout']));

$checkin2 = date('Y/m/d', strtotime($_POST['checkin']));
$checkout2 = date('Y/m/d', strtotime($_POST['checkout']));

/////Hotel management System///////
$checkinDb = date('Y-m-d', strtotime($_POST['checkinDb']));
$checkoutDb = date('Y-m-d', strtotime($_POST['checkoutDb']));

$roomno = $_POST['roomno'];
$roomtype = $_POST['roomtype'];
$roomstatus = $_POST['roomstatus'];
//////////////////////////////////

$guests = $_POST['guests'];
$rooms = $_POST['rooms'];
$type_room = $_POST['typename'];

$session = $_POST['session'];
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$cmd = isset($_POST["command"]) ? $_POST["command"] : "";
if ($cmd != "") {
	//Signup
	if ($cmd == "signup") {
		$arr = array( //field ต่างๆ
				"h_user_username"=> $username,
				"h_user_password"=> password_hash($password, PASSWORD_BCRYPT, array('cost'=>12)),
				"h_approve"=> "0",
				"h_user_firstname"=> $First_name,
				"h_user_lastname"=> $Last_name
				);
		$mysql->Insert_db($arr,"h_user"); //Check echo >>>> arr,ชื่อtable(พว่งหับ $tableName ใน Class_db.php)
		$mysql->Close_db();
	}

	//login
	if ($cmd == "login") {
			$result = $mysql->login($username);
			$checkApprove = $result['h_userrank_permission'];

			if(password_verify($password,$result['h_user_password'])){

			    $_SESSION['getUsername']= $result['h_user_username'];
				$_SESSION['getId']= $result['h_user_id'];
				$_SESSION['getPassword']= $result['h_user_password'];
				$_SESSION['getPermission']= $checkApprove;

				$timestamp = array(
								'h_user_id' => $result['h_user_id'],
								'h_user_login' =>  date('Y-m-d H:i:s')
							);
				$key_user = array("h_user_id");
				$mysql->Update_db($timestamp,$key_user,"h_user");
		
				if($checkApprove == "2"){
					$_SESSION['getType']= "Full Permission";
				}
				else{
					$_SESSION['getType']= "จำกัดสิทธิ์";
				}

				$check = 1;
			}
			else{
				$check = 0;
			}
			echo json_encode($check_login = array('check' => $check ));
			$mysql->Close_db();
	}
	

	//update
	if ($cmd == "update") {
		//echo $image;
		$sql = "SELECT h_user_image FROM h_user WHERE h_user_id = '".$UID."'";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read);
		if($read['h_user_image'] != "" && $image != $read['h_user_image'] && $image != "" && $read['h_user_image'] != "../system/upload_profile/404.png"){
			unlink($read['h_user_image']);
		}

		$arr = array( //field ต่างๆ
			"h_user_id"=> $UID, //กำหนดไว้แล้ว แก้ไม่ได้
			"h_user_rank"=> $Approve,
			"h_user_firstname"=> $First_name,
			"h_user_lastname"=> $Last_name,
			"h_user_image"=> $image
		);

		$key = array("h_user_id"); //กำหนดkey ไว้เป็นเงื่อนไข
		$check = $mysql->Update_db($arr,$key,"h_user"); //ตารางที่จะupdate

		if($check == 1){
			?>
					<h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Update success</h5>
			<?php
				echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_settingAccount.php'/>";
		} else{
			?> <div style="text-align: center; padding: 20px;">
					<h5>Login fail</h5>
					<h5><i class="fas fa-spinner fa-spin"></i></h5>
				</div>
		<?php
		}
	}

	//changePass
	if ($cmd == "changePass") {
		session_start();
		if(password_verify($oldpassword, $_SESSION['getPassword'])){
			$arr = array( //field ต่างๆ
				"h_user_id"=> $UID, //กำหนดไว้แล้ว แก้ไม่ได้
				"h_user_password"=> password_hash($password, PASSWORD_BCRYPT, array('cost'=>12)),
				"h_user_rank"=> $Approve
			);
			$key = array("h_user_id"); //กำหนดkey ไว้เป็นเงื่อนไข
			$mysql->Update_db($arr,$key,"h_user");
			?>
			<i class="fas fa-spinner fa-spin"></i>&nbsp;Change password success
			<?php
			$_SESSION['getPassword'] = password_hash($password, PASSWORD_BCRYPT, array('cost'=>12));
			echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_index.php'/>";
		} else{
			?>
			<span style="color: red;">รหัสผ่านเก่าไม่ถูกต้อง</span>
		<?php
		}
	}

	//logout
	if ($cmd == "logout") {
		session_destroy();
		?>
		<h5 style="color: orange;"><i class="fas fa-spinner fa-spin"></i>&nbsp;logout</h5>
		<?php
		echo "<meta http-equiv='refresh' content='2;URL=../system_login.php' />";
	}

	//Confirm booking
	if ($cmd == "confirmBooking") {
		$now = date("Y-m-d");
		$sqlrate = "SELECT * FROM h_rate WHERE h_rate_id = '".$_POST['guest_rateid']."'";
		$resultrate = $mysql->Select_db($sqlrate);
		foreach ($resultrate as $readrate);

		if($readrate['h_rate_dateset'] == 0 || ($readrate['h_rate_dateset'] == 1 && $now < $readrate['h_rate_dateend'])){

	 		// error_reporting(E_ALL);
	 		// ini_set("display_errors", 1);

			$sql = "SELECT * FROM h_transaction
					WHERE h_trans_codesession = '$cusSession' ";
			$sqlCheck = $mysql->numRows($sql);
			$result = $mysql->Select_db($sql);

			if($sqlCheck >= 1){

				$arr = array(
					"h_customer_firstname"=> $cusFname,
					"h_customer_lastname"=> $cusLname,
					"h_customer_email"=> $cusEmail,
					"h_customer_region"=> $cusRegion,
					"h_customer_address"=> $cusAddress,
					"h_customer_city"=> $cusCity,
					"h_customer_postal"=> $cusPostal,
					"h_customer_phone" => $cusPhone,
					"h_customer_codesession"=> $cusSession
				);
				$t = $mysql->Insert_db($arr,"h_customer");

				$sqlcustomer = "SELECT * FROM h_customer
								WHERE h_customer_codesession = '$cusSession'
								ORDER BY h_customer_id DESC LIMIT 1";

				$sqlCheckCustomer = $mysql->Select_db($sqlcustomer);
				foreach ($sqlCheckCustomer as $readcus);
				$cusid = $readcus['h_customer_id'];

				foreach ($result as $read) {

					$arr = array(
						"h_trans_id" => $read['h_trans_id'],
						"h_trans_codesession"=> "",
						"h_trans_customerid"=> $cusid

					);

					$key = array("h_trans_id"); 
					$c = $mysql->Update_db($arr,$key,"h_transaction");

				}

				//เช็คชนิดห้องที่เลือกทั้งหมด
					$sqltype = "SELECT h_type.h_type_name FROM h_room
								JOIN h_transaction
								ON h_transaction.h_trans_roomid = h_room.h_room_id
								JOIN h_customer
								ON h_transaction.h_trans_customerid = h_customer.h_customer_id
								JOIN h_type
								ON h_room.h_room_type = h_type.h_type_id
								WHERE h_customer.h_customer_id = $cusid GROUP BY h_type.h_type_id ORDER BY h_type.h_type_capacity";
					$sqlCheckType = $mysql->Select_db($sqltype);

				$arr2 = array(
					"h_bill_session" => $cusSession,
					"h_bill_customerid"=> $cusid,
					"h_bill_rateid"=> $cusRateid,
					"h_bill_paymenttypeid" => $cusPaymenttype,
					"h_bill_price" => $cusTotal,
					"h_bill_special" => $cusSpecial,
					"h_bill_guests" => $guests,
					"h_bill_checkin" => $checkin2,
					"h_bill_checkout" => $checkout2,
					"h_bill_rooms" => $rooms,
					"h_bill_status" => "1"
				);

				$checkinsert = $mysql->Insert_db($arr2,"h_bill");

				// echo json_encode($arr2);
				// echo "<br>";
				// var_dump($checkinsert);
				// echo "<br>";

				///////////////////////Taxi mail API//////////////////////////////

				// set post API fields
				$postAPI = [
				    'username' => 'bo.chutipas_st@tni.ac.th',
				    'password' => 'TaTent591220306'
				];

				$urlpost = "https://api.taximail.com/v2/user/login";
				$response = $mysql->curl($postAPI,$urlpost);
				//var_dump($response);

				///////////////////////Taxi mail API Transactional//////////////////////////////
				
				$user_data = [
					"CF_cusFname" => $cusFname,
					"CF_cusLname" => $cusLname,
					"CF_cus_id" => $readcus['h_customer_id'],
					"CF_session" => $cusSession,
					"CF_checkin" => $checkin,
					"CF_checkout" => $checkout,
					"CF_type_room" => $type_room,
					"CF_guests" => $guests,
					"CF_rooms" => $rooms,
					"CF_total" => number_format($cusTotal,2),
					"CF_rate" => $cusRate,
					"CF_discount" => $discount,
					"CF_special" => $cusSpecial
				];

				$postAPI = [
				    'session_id' => $response['data']['session_id'],
				    'message_id' => time(),
				    'transactional_group_name' => 'default',
				    'subject' => 'Confirm your booking room.',
				    'to_name' => $cusFname.' '.$cusLname,
				    'to_email' => $cusEmail,
				    'from_name' => 'Tent Reservation System',
				    'from_email' => 'Tent.Reservation@tni.ac.th',
				    'template_key' => '31555d0b02483a6c0',
				    'content_html' => json_encode($user_data),
				    'report_type' => 'False',
				    'priority' => 0
				];

				$urlpost = "https://api.taximail.com/v2/transactional";
				$responseTran =$mysql->curl($postAPI,$urlpost);
				// var_dump($responseTran);
				// var_dump($type_room);
				?>

				<div align="center">
					<div align="center" class="col-12">
						<i class="far fa-envelope w3-animate-top" style="font-size: 120px;"></i>
					</div>

					<?php if($_POST['path'] == "admin"){?>

						<h1>Booking Success.</h1>
						<h3>The email will be sent to customer.</h3>

					<?php }else { ?>
					<h1 class="font-small-xs">Thank for reservation.</h1>
					<h3 class="font-small-xs">We will send email to you.</h3>
				<?php } ?>
				</div>
				<div align="center">
					<?php if($_POST['path'] == "admin"){?>
					<a href="../system_hotel_admin_rooms.php"><input type="button" class="btn button-dark" value="Go to home">
						</a>
					<?php
					}else {?>
					<a href="../system_hotel_user.php"><input type="button" class="btn button-dark" value="Go to home"></a>
				</div> 
			<?php } ?>

				<?php
				unset($_SESSION["New_booking_Customer"]);
				unset($_SESSION['sessionTemp']);
			}
			else{ ?>
				<div align="center">
					<h1 class="font-small-xs">Sorry. Your session expired...</h1>
					<h3 class="font-small-xs"><i class="fas fa-spinner fa-spin"></i></h3>
				</div>
				<div align="center" style="margin-bottom: 20px;">
					<a href="../system_hotel_user.php"><input type="button" class="btn button-dark" value="Go to home"></a>
				</div>
				
				<?php
				$mysql->Close_db();
				if($_POST['path'] == "admin"){
					echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_rooms.php'/>";
				}else{
					echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_user.php'/>";
				}
			}
		}
		else{ ?>
			<div align="center">
					<h1 class="font-small-xs">Sorry. This rate has expired....</h1>
					<h3 class="font-small-xs"><i class="fas fa-spinner fa-spin"></i></h3>
				</div>
				<div align="center">
					<a href="../system_hotel_user.php"><input type="button" class="btn button-dark" value="Go to home"></a>
				</div>
				
				<?php
				$mysql->Close_db();
				if($_POST['path'] == "admin"){
					echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_admin_rooms.php'/>";
				}else{
					echo "<meta http-equiv='refresh' content='2;URL=../system_hotel_user.php'/>";
				}
		}
    }

	//findimage
	if ($cmd == "findimage") {
			
		$sql = "SELECT h_gallery_filename FROM h_gallery
				WHERE h_gallery_roomtypeid = '".$_POST['typeid']."'
				AND h_gallery_cover = 0";
		$result = $mysql->Select_db($sql);

		foreach ($result as $read) {
			$obj['src'] = $read['h_gallery_filename'];
			$sumObj[] = $obj;
		}
		echo json_encode($sumObj);
	}

	if ($cmd == "search_room_info_user"){
		$sql = "SELECT * FROM h_type
				JOIN h_type_bed
				ON h_type_bed.h_type_bed_id = h_type_bed
				WHERE h_type.h_type_id = '".$_POST['id']."'";

		$result = $mysql->select_db($sql);
        $rowcount = $mysql->numRows($sql);

		if($rowcount > 0) {
			foreach ($result as $read) {
				$sqlimage = "SELECT * FROM h_gallery
						  	 WHERE h_gallery_roomtypeid = '".$read['h_type_id']."'"; 
				$resultsqlimage = $mysql->select_db($sqlimage);
				foreach ($resultsqlimage as $readsqlimage){
					if($readsqlimage['h_gallery_cover'] == 0){
						$arrimage[] = array('image' => $readsqlimage['h_gallery_filename']);
					}
				};

				$arr[] = array(
						'h_type_id' => $read['h_type_id'],
						'h_type_name' => $read['h_type_name'],
						'h_type_desc' => $read['h_type_desc'],
						'h_type_price' => $read['h_type_price'],
						'h_type_bed_id' => $read['h_type_bed'],
						'h_type_bed' => $read['h_type_bed_name'],
						'h_type_bed_image' => $read['h_type_bed_image'],
						'h_type_bedtotal' => $read['h_type_bedtotal'],
						'h_type_capacity' => $read['h_type_capacity'],
						'h_type_statustouser' => $read['h_type_statustouser'],
						'imagegroup' => $arrimage
						);
				//reset array to empty
				unset($arrimage);
				unset($arrcover);
				
				
			}
			echo json_encode($arr);
			exit();
		
		}else {
			$arr = array('html' => "<h5>No room found</h5>");
			echo json_encode($arr);
			exit();
		}
	}
}
?>
