<?php
session_start();
if($_SESSION['getPermission'] == ""){
	header("Location:system_login.php");
}
/*else if($_SESSION['getPermission'] == 1){
	header("Location:system_index.php");
}*/
$UID = $_SESSION['getId'];

include("system/class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

///////////////////////image profile////////
$result = $mysql->UIDProfile($UID);       //
foreach ($result as $read);               //
///////////////////////////////////////////
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
	<?php include("include/mytools.html"); ?>
	<script src="js/myScript_admin.js"></script>
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
							    <?php foreach ($resulttype_ca as $readtype_ca){ ?>
							    <input type="checkbox" class="productDetail capacity" name="filter-1" value="<?php echo $readtype_ca['h_type_capacity'] ?>">
								    <?php echo $readtype_ca['h_type_capacity'] ?>&nbsp;
								<?php } ?>
							    <hr>
							    <label>ประเภทห้องพัก</label><br>
							    <?php foreach ($resulttype as $readtype){ ?>
							    <input type="checkbox" class="productDetail type" name="filter-1" value="<?php echo $readtype['h_type_id'] ?>">
							    <?php echo $readtype['h_type_name'] ?>&nbsp;
							<?php } ?>
							    <hr>
							    <label>สถานะ</label><br>
							    <?php foreach ($resultstatus as $readstatus){ ?>
							    <input type="checkbox" class="productDetail status" name="filter-1" value="<?php echo $readstatus['h_status_id'] ?>">
							    <?php echo $readstatus['h_status_name'] ?>&nbsp;
							<?php } ?>
							    <hr>
							    <div class="col-12 row">
								    <div class="col-11 row input-daterange input-group adminSearchDate" id="datepicker">
									    <label>Check in:&nbsp;</label>
									    <div class="col-5 row">
									    	<input class="productDate datepicker form-control checkin2" id="checkin2" name="checkin2" autocomplete="off">
									    </div>
									    <label>&nbsp;Check out:&nbsp;</label>
									    <div class="col-5 row">
									    	<input class="productDate datepicker form-control checkout2" id="checkout2" name="checkout2" autocomplete="off">
									    </div>
									</div>&nbsp;&nbsp;<div class="col-2 row"><button class="btn btn-sm btn-info"><i class="fas fa-sync-alt"></i> รีเซ็ต</button></div>
							  	</div>

						  	</form>
						  	<div class="col-12 row">จำนวนห้องที่พบ:<span class="sumresult"></span></div>
						</div>
					</div>

					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<h3>Admin Manage</h3>
							<button class="btn btn-info" data-toggle="#" id="bookingadmin" data-target="#exampleModalCenter"><i class="fas fa-book-medical"></i> จองห้อง</button>
							<input type="button" class="btn btn-info" id="bookingC" hidden value="จอง">
							<button class="btn btn-warning" id="bookingCancel" hidden>ยกเลิก</button>
						</div>
					</div>
				</div>
				<!-- one box -->
					<div class="content-box mb-3 content-lighten">
							<table class="table">
							  <thead class="thead-dark">
							    <tr>
							      <th scope="col">Room No.</th>
							      <th scope="col">Type</th>
							      <th scope="col">Capacity</th>
							      <th scope="col">Price</th>
							      <th scope="col">Status</th>
							      <th scope="col" align="center" style="text-align: center;"><span id="checkAll" hidden><a href="#">Check All</a></span><span id="discheckAll" hidden><a href="#">Check All</a></span></th>
							    </tr>
							  </thead>
							  <tbody id="showTable_hotel">
							  </tbody>
							</table>
<!-- 					  		<div id="showTable_hotel">
					  		</div> -->
					</div>
			
		</div>
	</div>

<!-- Modal BookingMore -->
				<div class="modal fade bookmoremodal" id="bookmore" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">จองห้องพัก</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="bookMoreRoomsForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

</body>

<!-- javascript library -->
<script src="js/sidebar.menu.js"></script>
<script src="js/jquery.formatNumber-0.1.1.js"></script>
<script src="js/myScript_admin_ControlRooms.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	//datepicker_admin (Search Room and Setting Room)
		var FormRequestClass = ".adminSearchDate";
	  	datepicker_Bookingadmin(FormRequestClass); //Function in myScript.js
</script>


</html>