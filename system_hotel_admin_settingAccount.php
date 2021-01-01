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
if ($read['h_user_image']!== ""){
  $urlPreview = $read['h_user_image'];
}
else{
  $urlPreview = "../system/upload_profile/404.png";
}

if (isset($_POST['hidFileId'])){
     $urlPreview = "../system/upload_profile/".$_POST['hidFileId'];
}
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
							<h3><i class="fas fa-user-alt"></i> แก้ไขข้อมูลส่วนตัว</h3>
							<p></p>
						</div>
					</div>
				</div>

				<!-- two box -->
				<div class="row justify-content-center">
					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<form id="sendUpdateForm" enctype="multipart/form-data">
						    	<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชื่อ</label></div>
						      	<div class="col-sm-12 col-md-6"><input id="Fname" name="Fname" placeholder="ชื่อจริง" class="form-control" value="<?php echo $read["h_user_firstname"];?>" required></div>
						      	<br>
						      	<div class="col-12" align="left"><label for="name" style=" text-align: left;">นามสกุล</label></div>
						      	<div class="col-sm-12 col-md-6"><input id="Lname" name="Lname" placeholder="นามสกุล" class="form-control" value="<?php echo $read["h_user_lastname"];?>" required></div>
						      	<hr>
						      	<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชื่อบัญชี</label></div>
						      	<div class="col-sm-12 col-md-6" id="user" align="center"><input name="user" placeholder="Username" class="form-control" value="<?php echo $read["h_user_username"];?>" required disabled></div>
						      	<hr>
						      	<div class="col-12" align="left"><label for="name" style=" text-align: left;">รูปประจำตัว</label></div>

						      	<div class="col-12" align="left"><input id="hidFileId" name="hidFileId" type="hidden" value="<?php echo $urlPreview ?>" /></div>
						      	<div class="col-12">
							      	<div class="col-12 row">
								      	<div class="col-10 col-sm-12 col-md-6" align="left" class="custom-file" style="margin-bottom: 10px;">
								      		<input type="file" id="fileUpload" class="custom-file-input" accept="image/*">
								      		<label class="custom-file-label" for="customFile">เลือกไฟล์</label>
								      	</div>
								      	<div class="col-4">
								      		<button class="btn btn-sm btn-danger" id="btnDelete_image_account" type="button"/><i class="fas fa-trash-alt"></i> ลบรูปภาพ</button>
								      	</div>
							      	</div>
						      	</div>
						      	<br>
						      	<div class="col-12" align="left"><img class="imgPreview" src="<?php echo $urlPreview ?>" style="width:200px;height:200px" />
						      	<hr>
						      	<input id="UID" name="UID" value="<?php echo($UID);?>" hidden>
						      	<input id="approve" name="approve" value="<?php echo $read["h_user_rank"];?>" hidden>
						      	<div align="center">
							      	<input type="submit" value="บันทึก" class="btn btn-success">
							      	<span id="updateSuccess"></span>
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