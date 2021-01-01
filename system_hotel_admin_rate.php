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

$sqltype = "SELECT * FROM h_type
			ORDER BY h_type_price ASC";
$resulttype = $mysql->Select_db($sqltype);

?>

<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<?php include("include/mytools.html"); ?>
	<link rel="stylesheet" href="css/myCss_admin.css?v=<?php echo VERSION_NUMBER;?>">
	<link rel="stylesheet" href="css/sidebar.css?v=<?php echo VERSION_NUMBER;?>">
	<link href="css/bootstrap4-toggle.css" rel="stylesheet">
	<title>Admin Control</title>

	<style>
	.ck-editor__editable_inline {
	    min-height: 15em;
	}
	.baseline{
		vertical-align: baseline;
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
							    <label>ส่วนลด</label><br>
							    <div class="form-check form-check-inline">
								  <input class="form-check-input ratefilter discount" type="radio" name="discount" id="discount1" value="1">
								  <label class="form-check-label" for="discount1">มี</label>
								</div>
								<div class="form-check form-check-inline">
								  <input class="form-check-input ratefilter discount" type="radio" name="discount" id="discount2" value="0">
								  <label class="form-check-label" for="discount2">ไม่มี</label>
								</div>
								<div class="form-check form-check-inline">
								  <input class="form-check-input ratefilter discount" type="radio" name="discount" id="discount3" value="2" checked>
								  <label class="form-check-label" for="discount3">ทั้งหมด</label>
								</div>
							<hr>
								<label>ชำระเงินล่วงหน้า</label><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input ratefilter deposit" type="radio" name="deposit" id="deposit1" value="1">
									<label class="form-check-label" for="deposit1">ต้องชำระ</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input ratefilter deposit" type="radio" name="deposit" id="deposit2" value="0">
									<label class="form-check-label" for="deposit2">ไม่ต้องชำระ</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input ratefilter deposit" type="radio" name="deposit" id="deposit3" value="2" checked>
									<label class="form-check-label" for="deposit3">ทั้งหมด</label>
								</div>
							<hr>
							<label>สถานะ</label><br>
							    <div class="form-check form-check-inline">
									<input class="form-check-input ratefilter status" type="radio" name="status" id="status1" value="1">
									<label class="form-check-label" for="status1">แสดงในหน้าเว็บ</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input ratefilter status" type="radio" name="status" id="status2" value="2">
									<label class="form-check-label" for="status2">ซ่อนจากหน้าเว็บ</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input ratefilter status" type="radio" name="status" id="status3" value="0" checked>
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
							<button class="btn btn-info" data-toggle="modal" data-target="#addrate"><i class="fas fa-plus-square"></i> เพิ่มเรทราคาห้องพัก</button>
						</div>
					</div>
				</div>
				<!-- one box -->
				<div class="pagination-div"></div>
				<span id="show_rate"></span>
				<div class="pagination-div"></div>
			</div>
		</div>
	</div>

<!-- Modal Add -->
					<div class="modal fade" id="addrate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-focus="false">
  						<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    						<div class="modal-content">
      							<div class="modal-header">
       								<h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-user"></i>&nbsp;Add rate</h5>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          							<span aria-hidden="true">&times;</span>
        							</button>
      							</div>
      							<div class="modal-body">
					  				<div class="col-12" align="left" id="RateForm">
										  <form action="#" method="POST" class="" id="sendRateForm" enctype="multipart/form-data">
											<div class="col-12" align="left"><label for="name" style=" text-align: left;"><b>ชื่อเรทราคาใหม่</b></label></div>
											<div class="col-12 row sm-font-admin">
												<div class="col-12 col-md-6" id="user2" align="center">
													<input name="ratename" id="ratename" placeholder="ชื่อเรทราคาใหม่" class="form-control sm-font-admin" required>
												</div>
												<div class="col-12 col-md-6 border-left-custom2 baseline" style="padding-top: 5px;">
													<div class="form-check form-check-inline col-12">
														<input class="form-check-input statusset" type="radio" name="statusset" id="statusset1" value="1">
														<label class="form-check-label " for="statusset1">แสดงในหน้าเว็บ
														</label>&nbsp;&nbsp;
														<input class="form-check-input statusset" type="radio" name="statusset" id="statusset2" value="2" checked>
														<label class="form-check-label" for="statusset2">ซ่อนจากหน้าเว็บ
														</label>
													</div>
												</div>
											</div>
											<br>
								     
											<div class="col-12" align="left"><label for="name" style=" text-align: left;"><b>รายละเอียด</b></label></div>
											<div class="col-12" id="user2" align="center"><textarea id="ratedesc" name="ratedesc" class="form-control"></textarea></div><hr>

											<div class="col-12" align="left"><label for="name" style=" text-align: left;"><b>ส่วนลด</b></label></div>
											<div class="col-12 sm-font-admin">
												<div class="form-check form-check-inline">
												  <input class="form-check-input discountcheck" type="radio" name="discountcheck" id="discountcheck1" value="Bath">
												  <label class="form-check-label" for="discountcheck1">บาท(THB)</label>
												</div>
												<div class="form-check form-check-inline">
												  <input class="form-check-input discountcheck" type="radio" name="discountcheck" id="discountcheck2" value="Percent">
												  <label class="form-check-label" for="discountcheck2">เปอร์เซน(%)</label>
												</div>
												<div class="form-check form-check-inline">
												  <input class="form-check-input discountcheck" type="radio" name="discountcheck" id="discountcheck3" value="None" checked>
												  <label class="form-check-label" for="discountcheck3">ไม่มี</label>
												</div>
											</div>
											<div class="col-12 row">
												<div class="col-4 input-group" id="user2" align="center">
													<input type="number" min="0" name="ratediscount" id="ratediscount" placeholder="ส่วนลด" class="form-control" disabled hidden>
													<div class="input-group-prepend">
											          <div class="input-group-text discounttype" hidden>THB</div>
											        </div>
												</div>
												<!-- <div class="col-3 row">
													<span class="discounttype" hidden>THB</span>
												</div> -->
											</div><br>

											<div class="col-12" align="left"><label for="name" style=" text-align: left;"><b>ชำระเงินล่วงหน้า</b></label></div>
											<div class="col-12 sm-font-admin">
												<div class="form-check form-check-inline">
												  <input class="form-check-input rate_deposit" type="radio" name="rate_deposit" id="rate_deposit1" value="1">
												  <label class="form-check-label" for="rate_deposit1">
												  	<b><i class="fas fa-check text-success"></i></b>
												  </label>
												</div>
												<div class="form-check form-check-inline">
												  <input class="form-check-input rate_deposit" type="radio" name="rate_deposit" id="rate_deposit2" value="0" checked>
												  <label class="form-check-label" for="rate_deposit2">
												  	<b><i class="fas fa-times text-danger"></i></b>
												  </label>
												</div>
											</div><br>

											<div class="col-12" align="left"><label for="name" style=" text-align: left;"><b>วันที่เริ่ม - สิ้นสุด</b></label></div>
											<div class="col-12 row">
												<div class="col-12 sm-font-admin">
													<div class="form-check form-check-inline">
													  <input class="form-check-input rate_dateset" type="radio" name="rate_dateset" id="datecheck1" value="1">
													  <label class="form-check-label" for="rate_dateset1">กำหนดวัน</label>
													</div>
													<div class="form-check form-check-inline">
													  <input class="form-check-input rate_dateset" type="radio" name="rate_dateset" id="datecheck2" value="0" checked>
													  <label class="form-check-label" for="rate_dateset2">ไม่มีกำหนด</label>
													</div>
												</div>
												<div class="input-daterange input-group addrateDate" id="datepicker">
													<div class="col-3">
												        <input type="text" class="datepicker form-control checkin rate_datestart" id="checkin2" name="checkin" required autocomplete="off" placeholder="วันเริ่มต้น" value="<?php echo $checkinsave ?>" disabled hidden>
												    </div> <span class="date-to-date" hidden>-</span> 
												    <div class="col-3">
												        <input type="text" class="datepicker form-control checkout rate_dateend" id="checkout2" name="checkout" required autocomplete="off" placeholder="วันสิ้นสุด" value="<?php echo $checkoutsave ?>" disabled hidden>
												    </div>
												</div>
											</div><hr>
											<div class="col-12"><label><b>ชนิดห้องที่ร่วมรายการ</b></label>
												<a href="#" class="typeall">(เลือกทั้งหมด)</a>
												<a href="#" class="distypeall" hidden>(ยกเลิก)</a>
											</div>
											    <div class="col-12 sm-font-admin"><?php foreach ($resulttype as $readtype){ ?>
												    <input type="checkbox" class="type" name="filter-1" value="<?php echo $readtype['h_type_id'] ?>">
												    <?php echo $readtype['h_type_name']; ?><br>
													<?php } ?>
												</div>
											<hr>
											<div align="center">
												<input type="submit" id="submitsignup" value="เพิ่มเรทราคาห้องพัก" class="btn btn-success"><br><br><span id="successAlert_add" class="text-success"></span>
											</div>
											</form>
		  							</div>
		  						</div>
      						</div>
   					 	</div>
  					</div>
<!-- /Modal -->

<!-- Modal Update -->
				<div class="modal fade editmodal" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-focus="false">
  					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">แก้ไขเรทราคาห้องพัก</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="left" id="updateRateForm">
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
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบเรทราคาห้องพัก</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteRateForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->
	<?php include("include/script.html"); ?>
	<script src="js/bootstrap4-toggle.js"></script>
	<script src="vendor/CKeditor/ckeditor.js"></script>
	<script src="js/myScript_admin.js?v=<?php echo VERSION_NUMBER;?>"></script>
	<script src="js/myScript_admin_ControlRate.js?v=<?php echo VERSION_NUMBER;?>"></script>
	<script src="js/sidebar.menu.js"></script>
	<script src="js/jquery.formatNumber-0.1.1.js"></script>
	<script src="js/myScript_pagination.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
	$(document).ready(function(){

	CKEDITOR.replace( 'ratedesc',{
		filebrowserBrowseUrl: 'vendor/CKeditor/ckfinder/ckfinder.html',
		filebrowserUploadUrl: 'vendor/CKeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
	});

	$('#toggle2').bootstrapToggle({
				on: 'แสดงในหน้าเว็บ',
	      		off: 'ซ่อนจากหน้าเว็บ',
			});

	});
	</script>
</body>
</html>