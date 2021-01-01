<?php
session_start();
if($_SESSION['getPermission'] == ""){
	header("Location:system_login.php");
	die();
}
$UID = $_SESSION['getId'];

include("system/class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

///////////////////////image profile////////
$read = $mysql->UIDProfile($UID);         //
////////////////////////////////////////////
?>

<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<?php include("include/mytools.html"); ?>
	<link rel="stylesheet" href="css/myCss_admin.css?v=<?php echo VERSION_NUMBER;?>">
	<link rel="stylesheet" href="css/sidebar.css?v=<?php echo VERSION_NUMBER;?>">
	<title>Sidebar menu on bootstrap 4</title>
</head>

<body class="body body-lighten">
	<div class="d-flex" id="wrapper">

		<!-- sidebar -->
		<div class="sidebar sidebar-lighten">

			<!-- sidebar menu -->
			<div class="sidebar-menu">

				<!-- menu -->
				<ul class="list list-unstyled list-scrollbar">

					<!-- multi-level dropdown menu -->
					<?php include('include/hotel_admin_navbar.php')?>

			<!-- content container -->
			<div class="container-fluid">

				<!-- one box -->
				<div class="row justify-content-center">
					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<h3><i class="fas fa-user-alt"></i> เปลี่ยนรหัสผ่าน</h3>
							<p>กรุณาใช้ตัวอักษร A-Z, a-z หรือ 0-9 เท่านั้น</p>
						</div>
					</div>
				</div>

				<!-- two box -->
				<div class="row justify-content-center">
					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<form id="sendChangePasswordForm">
						    <div class="col-12" align="left"><label for="name" style=" text-align: left;">รหัสผ่านเก่า</label></div>
						    <div class="input-group col-12 col-md-6">
						      	<div class="input-group-prepend">
									<div class="input-group-text badge-textbox-black"><i class="fas fa-key"></i></div>
								</div>
								<input type="password" id="oldpassword" name="oldpassword" class="form-control" placeholder="รหัสผ่านเก่า" required>
							</div>
						    <br>
						    <div class="col-12" align="left"><label for="name" style=" text-align: left;">รหัสผ่านใหม่</label></div>
						    <div class="input-group col-12 col-md-6">
						      	<div class="input-group-prepend">
									<div class="input-group-text badge-textbox-black"><i class="fas fa-key"></i></div>
								</div>
								<input type="password" id="pass" name="pass" class="form-control" placeholder="รหัสผ่านใหม่" required>
							</div>
						    <br>
						    <div class="col-12" align="left"><label for="name" style=" text-align: left;">ยืนยันรหัสผ่านใหม่</label></div>
						    <div class="input-group col-12 col-md-6" id="user" align="center">
						      	<div class="input-group-prepend">
									<div class="input-group-text badge-textbox-black"><i class="fas fa-key"></i></div>
								</div>
								<input type="password" id="Conpass" name="Conpass" class="form-control" placeholder="ยืนยันรหัสผ่านใหม่" required>
							</div>
						    <hr>

					      	<input id="approve" name="approve" value="<?php echo $read["h_user_rank"];?>" hidden>
					      	<input id="UID" name="UID" value="<?php echo($UID);?>" hidden>
					      	<input id="Fname" name="Fname" value="<?php echo $read["h_user_firstname"];?>" hidden>
					      	<input id="Lname" name="Lname" value="<?php echo $read["h_user_lirstname"];?>" hidden>
					      	<input id="hidFileId" name="hidFileId" value="<?php echo $read["h_user_image"];?>" hidden>
					      
					      	<div align="center"><input type="submit" value="บันทึก" class="btn btn-success"></div>
					      	<br>
					      	<div align="center">
						      	<span id="changePasswordSuccess"></span>
							    <span align="center" id="message_alert" hidden>
								    <span id='message_pass'></span>
								    <span id='message_alpha'></span><br>
								    <span id='message_alphapass'></span><br>
								    <span id='message_alphaConpass'></span>
							  	</span>
						  	  </div>
						    </form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include("include/script.html"); ?>
	<script src="js/sidebar.menu.js"></script>
	<script src="js/myScript_AccountSetting.js?v=<?php echo VERSION_NUMBER;?>"></script>
</body>
</html>