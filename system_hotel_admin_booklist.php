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

					<!-- multi-level dropdown menu -->
					<?php include('include/hotel_admin_navbar.php')?>

			<!-- content container -->
			<div class="container-fluid">

				<!-- two box -->
				<div class="row">
					<div class="col-12 col-sm-12 mb-3 col-md-6">
						<div class="content-box content-lighten">
						    <label>Search:&nbsp;</label>
						    <div class="col-12 row">
							    <div class="col-8">
							    	<input class="form-control" id="search" name="search" autocomplete="off" placeholder="Receipt No., Customer name, Room No.">
							    </div>
							    <div class="col-4 justify-content-center align-self-center">
							    	<button class="btn btn-info btn-lg search_booklist"><i class="fas fa-search"></i></button>
							    </div>
							</div><hr>
						    <label>สถานะ</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input bookfilter approve" type="radio" name="approve" id="approve1" value="1">
								<label class="form-check-label" for="approve1">รอการยืนยัน</label>
							</div>

							<div class="form-check form-check-inline">
								<input class="form-check-input bookfilter approve" type="radio" name="approve" id="approve6" value="6">
								<label class="form-check-label" for="approve6">ดำเนินการวันนี้</label>
							</div>

							<div class="form-check form-check-inline">
								<input class="form-check-input bookfilter approve" type="radio" name="approve" id="approve2" value="2">
								<label class="form-check-label" for="approve2">เช็คอินแล้ว</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input bookfilter approve" type="radio" name="approve" id="approve3" value="3">
								<label class="form-check-label" for="approve3">เช็คเอาส์แล้ว</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input bookfilter approve" type="radio" name="approve" id="approve4" value="4">
								<label class="form-check-label" for="approve4">ยกเลิก</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input bookfilter approve" type="radio" name="approve" id="approve5" value="5" checked>
								<label class="form-check-label" for="approve5">ทั้งหมด</label>
							</div>
							<hr>
							<label>ระยะเวลา</label><br>
							<div class="col-12 row">
								<div class="col-12 col-md-3 form-check form-check-inline">
									<select class="form-control day_select" id="day" name="day" style="margin-bottom: 10px;">
								        <option value="0"disabled selected>เลือกวัน</option>
								        <option value="0">วันนี้</option>
								        <option value="2">2 วันที่แล้ว</option>
								        <option value="5">5 วันที่แล้ว</option>
								        <option value="15">15 วันที่แล้ว</option>
								        <option value="all">ทั้งหมด</option>
								    </select>
								</div>
							    <div class="col-12 col-sm-12 col-md-8 input-daterange input-group adminSettingSearchDate_bottom" id="datepicker">
									<div class="col-12 col-sm-5 col-md-5">
								        <input type="text" class="datepicker form-control checkin2 datecheck" id="checkin2" name="checkin" required autocomplete="off" placeholder="วันเริ่มต้น">
								    </div><span>-</span> 
								    <div class="col-12 col-sm-5 col-md-5">
								        <input type="text" class="datepicker form-control checkout2 datecheck" id="checkout2" name="checkout" required autocomplete="off" placeholder="วันสิ้นสุด">
								    </div>
								</div>
							</div>
							<hr>
							<div class="col-2 row">
								<a href="system_hotel_admin_booklist.php">
									<button class="btn btn-sm btn-info"><i class="fas fa-sync-alt"></i> รีเซ็ต</button>
								</a>
							</div>
							<br>
							<div class="col-12 row">จำนวนรายการที่พบ:<span class="sumresult"></span></div>
						</div>
					</div>
					<div class="col-sm-6 mb-3 col-md-6 ">
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-sm-12 mb-3 col-md-6">
						<!-- one box -->
						<div class="pagination-div"></div>
						<div class="content-box col-12 mb-3 content-lighten" id="display">
						</div>
						<div class="pagination-div"></div>
					</div>

					<div class="d-none d-md-block col-sm-6 mb-3 col-md-6">
						<div class="sticky col-md-5 col-lg-4">
							<div class="content-box content-lighten" style="padding-bottom: 5px;">
								<h3><i class="fas fa-calendar-day"></i> สถานะห้องพักวันนี้</h3>
							</div>
							<div class="content-box col-12 mb-3 content-lighten" id="display_today">
							</div><i class="fas fa-sync fa-spin sync"></i>&nbsp;&nbsp;<span class="sync_notice"></span>&nbsp;&nbsp; <a href="#"><i class="fas fa-pause pause"></i><i class="fas fa-play play" hidden></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- Modal info -->
					<div class="modal fade infomodal" id="info-<?php echo $readsql['h_bill_customerid'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  						<div class="modal-dialog modal-dialog-centered" role="document">
    						<div class="modal-content">
      							<div class="modal-header">
       								<h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-user"></i>&nbsp;Customer Information</h5>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          							<span aria-hidden="true">&times;</span>
        							</button>
      							</div>
      							<div class="modal-body">
					  				<div class="col-12 info" align="left" id="">
					 				</div>
		  						</div>
      						</div>
   					 	</div>
  					</div>
<!-- /Modal -->

<!-- Modal Update -->
					<div class="modal fade updatemodal" id="info-<?php echo $readsql['h_bill_customerid'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  						<div class="modal-dialog modal-dialog-centered" role="document">
    						<div class="modal-content">
      							<div class="modal-header">
       								<h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-user"></i>&nbsp;Edit Customer Information</h5>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          							<span aria-hidden="true">&times;</span>
        							</button>
      							</div>
      							<div class="modal-body">
					  				<div class="col-12 update" align="left" id="">
					 				</div>
		  						</div>
      						</div>
   					 	</div>
  					</div>
<!-- /Modal -->

<!-- Modal Confirm Checkin -->
					<div class="modal fade concheckinmodal" id="concheckin-<?php echo $readsql['h_bill_customerid'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  						<div class="modal-dialog modal-dialog-centered" role="document">
    						<div class="modal-content">
      							<div class="modal-header">
       								<h5 class="modal-title" id="exampleModalLongTitle"><i class="far fa-calendar-check"></i>&nbsp;Confirm Check-in</h5>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          							<span aria-hidden="true">&times;</span>
        							</button>
      							</div>
      							<div class="modal-body">
					  				<div class="col-12 concheckin" align="left" id="">
					 				</div>
		  						</div>
      						</div>
   					 	</div>
  					</div>
<!-- /Modal -->

<!-- Modal Cancel Checkin -->
					<div class="modal fade cancheckinmodal" id="cancheckin-<?php echo $readsql['h_bill_customerid'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  						<div class="modal-dialog modal-dialog-centered" role="document">
    						<div class="modal-content">
      							<div class="modal-header">
       								<h5 class="modal-title" id="exampleModalLongTitle"><i class="far fa-calendar-times"></i>&nbsp;Cancel Check-in</h5>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          							<span aria-hidden="true">&times;</span>
        							</button>
      							</div>
      							<div class="modal-body">
					  				<div class="col-12 cancheckin" align="center" id="">
					 				</div>
		  						</div>
      						</div>
   					 	</div>
  					</div>
<!-- /Modal -->

<!-- Modal Confirm Checkout -->
					<div class="modal fade concheckoutmodal" id="concheckout-<?php echo $readsql['h_bill_customerid'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  						<div id="dialog_lg" class="modal-dialog modal-dialog-centered" role="document">
    						<div class="modal-content">
      							<div class="modal-header">
       								<h5 class="modal-title" id="exampleModalLongTitle"><i class="far fa-calendar-minus"></i>&nbsp;Confirm Check-out</h5>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          							<span aria-hidden="true">&times;</span>
        							</button>
      							</div>
      							<div class="modal-body">
					  				<div class="col-12 concheckout" align="" id="">
					 				</div>
		  						</div>
      						</div>
   					 	</div>
  					</div>
<!-- /Modal -->

<!-- Modal Confirm Checkout -->
					<div class="modal fade receiptmodal" id="receipt-<?php echo $readsql['h_bill_customerid'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  						<div id="dialog_lg_receipt" class="modal-dialog modal-dialog-centered" role="document">
    						<div class="modal-content">
      							<div class="modal-header">
       								<h5 class="modal-title" id="exampleModalLongTitle"><i class="far fa-calendar-minus"></i>&nbsp;Receipt History</h5>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          							<span aria-hidden="true">&times;</span>
        							</button>
      							</div>
      							<div class="modal-body">
					  				<div class="col-12 receipt" align="" id="">
					 				</div>
		  						</div>
      						</div>
   					 	</div>
  					</div>
<!-- /Modal -->

</body>
<?php include("include/script.html"); ?>
<script src="js/myScript_admin.js?v=<?php echo VERSION_NUMBER;?>"></script>
<script src="js/myScript_admin_ControlBooklist.js?v=<?php echo VERSION_NUMBER;?>"></script>
<script src="js/jquery.formatNumber-0.1.1.js"></script>
<script src="js/printThis.js"></script>
<script src="js/sidebar.menu.js"></script>
<script src="js/myScript_pagination.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
</html>