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
$sqltype = "SELECT * FROM h_type";
$resulttype = $mysql->Select_db($sqltype);

$sqltype_ca = "SELECT DISTINCT h_type_capacity FROM h_type ORDER BY h_type_capacity ASC";
$resulttype_ca = $mysql->Select_db($sqltype_ca);

$sqlstatus = "SELECT * FROM h_status";
$resultstatus = $mysql->Select_db($sqlstatus);

?>

<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<?php include("include/mytools.html"); ?>
	<link rel="stylesheet" href="css/myCss_admin.css?v=<?php echo VERSION_NUMBER;?>">
	<link rel="stylesheet" href="css/sidebar.css?v=<?php echo VERSION_NUMBER;?>">
	<title>Admin Control</title>
</head>

<body class="body body-lighten">
	<div class="d-flex" id="wrapper">

		<!-- sidebar -->
		<div class="sidebar sidebar-lighten">

			<!-- sidebar menu -->
			<div class="sidebar-menu">

				<!-- menu -->
				<ul class="list list-unstyled list-scrollbar">

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
								    <?php foreach ($resulttype_ca as $readtype_ca){ ?>
								    <div><input type="checkbox" class="option_search lock-while-delete capacity" name="filter-1" value="<?php echo $readtype_ca['h_type_capacity'] ?>">
									    <?php echo $readtype_ca['h_type_capacity'] ?></div>&nbsp;
									<?php } ?>
								</div>
							    <hr>
							    <label>ประเภทห้องพัก</label><br>
							    <div class="col-12 row">
								    <?php foreach ($resulttype as $readtype){ ?>
								    <div><input type="checkbox" class="option_search lock-while-delete type" name="filter-1" value="<?php echo $readtype['h_type_id'] ?>">
								    <?php echo $readtype['h_type_name'] ?></div>&nbsp;&nbsp;
									<?php } ?>
								</div>
							    <hr>
							    <label>สถานะ</label><br>
							    <div class="col-12 row">
								    <?php foreach ($resultstatus as $readstatus){ ?>
								    <div><input type="checkbox" class="option_search lock-while-delete status" name="filter-1" value="<?php echo $readstatus['h_status_id'] ?>">
								    <?php echo $readstatus['h_status_name'] ?></div>&nbsp;
									<?php } ?>
								</div>
							    <hr>
							    <div class="col-12 row">
								    <div class="col-12 col-md-11 col-lg-9 row input-daterange input-group adminSettingSearchDate" id="datepicker">
									    <label>Check in:&nbsp;</label>
									    <div class="col-12 col-md-5 row">
									    	<input class="option_search_date lock-while-delete datepicker form-control checkin2" id="checkin2" name="checkin2" autocomplete="off">
									    </div>
									    <label>&nbsp;Check out:&nbsp;</label>
									    <div class="col-12 col-md-5 row">
									    	<input class="option_search_date lock-while-delete datepicker form-control checkout2" id="checkout2" name="checkout2" autocomplete="off">
									    </div>
									</div>&nbsp;&nbsp;<div class="col-2 col-lg-3 row"><a href="system_hotel_admin_rooms.php"><button class="btn btn-sm btn-info"><i class="fas fa-sync-alt"></i> รีเซ็ต</button></a></div>
							  	</div>
						  	</form>
						  	<div class="col-12 row">จำนวนห้องที่พบ:<span class="sumresult"></span></div>
						</div>
					</div>

					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<h3>Admin Manage</h3>
							<button class="btn btn-info unlock-while-delete booking" data-toggle="#" id="deleteMorebtn" data-target="#exampleModalCenter"><i class="fas fa-book-medical"></i> จองห้องหลายรายการ</button>
							<input type="button" class="btn btn-info unlock-while-delete" id="selectDeleteMorebtn" hidden value="จอง">
							<button class="btn btn-warning unlock-while-delete" id="cancelDeleteMorebtn" hidden>ยกเลิก</button>
						</div>
					</div>
				</div>
				<!-- one box -->
					<div class="content-box mb-3 content-lighten">
						<div class="pagination-div"></div>
							<table class="table table-hover table-responsive sm-font-admin">
							  <thead class="thead-dark">
							    <tr>
							      <th scope="col">Room No.</th>
							      <th scope="col">Type</th>
							      <th scope="col">Capacity</th>
							      <th scope="col">Price</th>
							      <th scope="col">Status</th>
							      <th scope="col"></th>
							      <th scope="col" align="center" style="text-align: center;"><span class="unlock-while-delete" id="checkboxAll" hidden><a href="#">Check All</a></span><span class="unlock-while-delete" id="unCheckboxAll" hidden><a href="#">Uncheck All</a></span></th>
							    </tr>
							  </thead>
							  <tbody id="display_room">
							  </tbody>
							</table>
							<div class="pagination-div"></div>	
					</div>
		</div>
	</div>

<!-- Modal BookingMore -->
				<div class="modal fade room_bookingMoremodel" id="bookmore" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">จองห้องพัก</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="bookingMoreRoomForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->
	<?php include("include/script.html"); ?>
	<script src="js/myScript_admin.js?v=<?php echo VERSION_NUMBER;?>"></script>
	<script src="js/sidebar.menu.js"></script>
	<script src="js/jquery.formatNumber-0.1.1.js"></script>
	<script src="js/myScript_admin_ControlRooms.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/myScript_pagination.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/DML.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		//datepicker_admin (Search Room and Setting Room)
		var FormRequestClass = ".adminSettingSearchDate";
		datepicker_Bookingadmin(FormRequestClass); //Function in myScript.js
	</script>
</body>
</html>