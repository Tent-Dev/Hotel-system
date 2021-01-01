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
$sqltype = "SELECT DISTINCT h_type_capacity FROM h_type ORDER BY h_type_capacity ASC";
$resulttype = $mysql->Select_db($sqltype);

$sqlbed = "SELECT * FROM h_type_bed ORDER BY h_type_bed_id ASC";
$resultbed = $mysql->Select_db($sqlbed);

?>

<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<?php include("include/mytools.html"); ?>
	<link rel="stylesheet" href="css/sidebar.css?v=<?php echo VERSION_NUMBER;?>">
	<link rel="stylesheet" href="css/myCss_admin.css?v=<?php echo VERSION_NUMBER;?>">
	<link href="css/bootstrap4-toggle.css" rel="stylesheet">
	<link href="css/magnific-popup.css" rel="stylesheet" />
	<link href="css/magnific-popup_Fade.css" rel="stylesheet" />
	<link href="css/dropzone.css" rel="stylesheet">
	<title>Admin Control</title>

	<style type="text/css" media="screen">
		.uploaddiv{
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;
			border-width:3px;  
    		border-style:dashed;
		}
	</style>
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

				<!-- two box -->
				<div class="row">
					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<form id="sendSearch">
							    <label>จำนวนผู้เข้าพักสูงสุดต่อห้อง</label><br>
							    <div class="col-12 row">
								    <?php foreach ($resulttype as $readtype){ ?>
								    <div><input type="checkbox" class="typefilter capacity" name="filter-1" value="<?php echo $readtype['h_type_capacity'] ?>">
								    <?php echo $readtype['h_type_capacity'] ?></div>&nbsp;
									<?php } ?>
								</div>
							<hr>
							<label>ประเภทเตียง</label><br>
							<div class="col-12 row">
							    <?php foreach ($resultbed as $readbed){ ?>
							    <div><input type="checkbox" class="typefilter bed" name="filter-1" value="<?php echo $readbed['h_type_bed_id'] ?>">
							    <?php echo $readbed['h_type_bed_name'] ?></div>&nbsp;
								<?php } ?>
							</div>
							<hr>
							<label>สถานะ</label><br>
							    <div class="form-check form-check-inline">
									<input class="form-check-input typefilter status" type="radio" name="status" id="status1" value="1">
									<label class="form-check-label" for="status1">แสดงในหน้าเว็บ</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input typefilter status" type="radio" name="status" id="status2" value="2">
									<label class="form-check-label" for="status2">ซ่อนจากหน้าเว็บ</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input typefilter status" type="radio" name="status" id="status3" value="0" checked>
									<label class="form-check-label" for="status3">ทั้งหมด</label>
								</div>
							<hr>
							<div class="col-2 row">
								<button class="btn btn-sm btn-info"><i class="fas fa-sync-alt"></i> รีเซ็ต</button>
							</div>
						  	</form>
						  	<div class="col-12 row">จำนวนห้องที่พบ:<span class="sumresult"></span></div>
						</div>
					</div>
					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<h3>Admin Manage</h3>
							<button class="btn btn-info" data-toggle="modal" data-target="#addtype"><i class="fas fa-plus-square"></i> เพิ่มชนิดห้อง</button>
						</div>
					</div>
				</div>

				<!-- one box -->
				<div class="pagination-div"></div>
				<div id="show_type" class="clearfix"></div>
				<div class="pagination-div"></div>
			</div>
		</div>
	</div>

<!-- Modal Add -->
					<div class="modal fade" id="addtype" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  						<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    						<div class="modal-content">
      							<div class="modal-header">
       								<h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-user"></i>&nbsp;Add type</h5>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          							<span aria-hidden="true">&times;</span>
        							</button>
      							</div>
      							<div class="modal-body">
					  				<div class="col-12" align="center" id="TypeForm">
										  <form action="#" method="POST" class="sm-font-admin" id="dropzone" enctype="multipart/form-data">
											<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชื่อชนิดห้องใหม่</label></div>
											<div class="col-6" id="user2" align="center"><input name="typename" id="typename" placeholder="ชื่อชนิดห้องใหม่" class="form-control sm-font-admin" required></div><br>
								     
											<div class="col-12" align="left"><label for="name" style=" text-align: left;">รายละเอียด</label></div>
											<div class="col-6" id="user2" align="center"><textarea id="typedesc" name="typedesc" class="form-control sm-font-admin" id="exampleFormControlTextarea1" rows="3"></textarea></div><br>

											<div class="col-12" align="left"><label for="name" style=" text-align: left;">รูปภาพประกอบ</label></div>

										      <div class="dropzone col-10 uploaddiv"></div>
										      <br>

											<div class="col-12" align="left"><label for="name" style=" text-align: left;">ราคา</label></div>
											<div class="col-6 input-group" id="user2" align="center">
												<input name="typeprice" id="typeprice" placeholder="ราคา" class="form-control sm-font-admin" required>
												<div class="input-group-prepend">
													<div class="input-group-text">
														฿
													</div>
												</div>
											</div><br>

											<div class="col-12" align="left"><label for="name" style=" text-align: left;">จำนวนคนสูงสุดต่อห้อง</label></div>
											<div class="col-6" id="user2" align="center"><input name="typecapacity" id="typecapacity" placeholder="จำนวนคนสูงสุดต่อห้อง" class="form-control sm-font-admin" required></div><br>

											<div class="col-12" align="left"><label for="name" style=" text-align: left;">ประเภทเตียง</label></div>
											<div class="col-6" id="user2" align="center">
												<select class="form-control sm-font-admin" name="typebed" id="typebed" required="">
												<?php foreach ($resultbed as $readbed){ ?>
												  <option value="<?php echo $readbed['h_type_bed_id'] ?>">
												  	<?php echo $readbed['h_type_bed_name'] ?>
												  </option>
												<?php } ?>
												</select>
											</div><br>

											<div class="col-12" align="left"><label for="name" style=" text-align: left;">จำนวนประเภทเตียงต่อ	1 ห้อง</label></div>
											<div class="col-6" id="user2" align="center"><input name="bedtotal" id="bedtotal" placeholder="จำนวนประเภทเตียงต่อ 1 ห้อง" class="form-control sm-font-admin" required></div><br>
											<hr>
											<input type="submit" id="submitsignup" value="เพิ่มชนิดห้อง" class="btn btn-success"><br><br><span id="successAlert_add" class="text-success"></span>
											</form>
		  							</div>
		  						</div>
      						</div>
   					 	</div>
  					</div>
<!-- /Modal -->

<!-- Modal Update -->
				<div class="modal fade editmodal" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">แก้ไขชนิดห้อง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="updateTypeForm">
		  						</div>
		  						
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal Delete -->
				<div class="modal fade deletemodal" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบชนิดห้องพัก</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteTypeForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal cover -->
				<div class="modal fade covermodal" id="cover" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">รูปตัวอย่าง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="coverForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->
<?php include("include/script.html"); ?>
<script src="js/bootstrap4-toggle.js"></script>
<script src="js/myScript_admin.js?v=<?php echo VERSION_NUMBER;?>"></script>
<script src="js/jquery.magnific-popup.js"/></script>
<script src="js/myScript_admin_ControlType.js?v=<?php echo VERSION_NUMBER;?>"></script>
<script src="js/dropzone.js?v=<?php echo VERSION_NUMBER;?>"></script>
<script src="js/sidebar.menu.js"></script>
<script src="js/myScript_dropzone.js?v=<?php echo VERSION_NUMBER;?>"></script>
<script src="js/jquery.formatNumber-0.1.1.js"></script>
<script src="js/myScript_pagination.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
</body>
</html>