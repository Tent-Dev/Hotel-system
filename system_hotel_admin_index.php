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
$year = date('Y');
$set_min_year = date_create('2010-12-12');
$year_min = date_format($set_min_year, 'Y');
?>

<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<?php include("include/mytools.html"); ?>
	<link rel="stylesheet" href="css/sidebar.css?v=<?php echo VERSION_NUMBER;?>">
	<link rel="stylesheet" href="css/myCss_admin.css?v=<?php echo VERSION_NUMBER;?>">
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

				<!-- two box -->
				<div class="row">
					<div class="col-sm-12 mb-3 col-md-12 col-lg-6">
						<div class="content-box content-lighten sm-font-admin">
							<h3>ผู้เข้าใช้ระบบล่าสุด</h3>
							<div id="last_login">
							</div><a href="#"><i class="fas fa-plus-square"></i>&nbsp;&nbsp;ดูทั้งหมด</a>
						</div>
					</div>
					<div class="col-sm-12 mb-3 col-md-12 col-lg-6">
						<div class="content-box content-lighten">
							<h3>สถานะห้องพักวันนี้</h3>
							<div id="today"></div><i class="fas fa-sync fa-spin sync"></i>&nbsp;&nbsp;<span class="sync_notice"></span>&nbsp;&nbsp; <a href="#"><i class="fas fa-pause pause"></i><i class="fas fa-play play" hidden></i></a>
						</div>
					</div>
				</div>

				<!-- one box -->
				<div class="content-box mb-3 content-lighten">
					<h3>ภาพรวม</h3>
					<div class="col-12 row sm-font-admin" align="center">
						<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 row" align="left">
							<div class="col-4 col-md-3 col-lg-3" style="margin-bottom: 20px;">
								<label>สถิติ</label>
								<select class="form-control sm-font-admin">
									<option value="">ทั้งหมด</option>
								</select>
							</div>

							<div class="col-4 col-md-3 col-lg-2">
								<label>ปี</label>
								<select class="form-control year_select sm-font-admin">
									<?php for($i=$year;$i>=$year_min;$i--){ ?>
									<option value="<?php echo $i ?>"><?php echo $i ?></option>
									<?php } ?>
								</select>
							</div>

						</div>
						<div class="col-12" align="center">
							<div class="col-12 col-md-12 col-lg-8">
								<canvas id="myChart"></canvas>
							</div>
						</div>
						<div class="col-12">
							รายการจองห้องพักทั้งหมด: <span id="total"></span> รายการ | ยกเลิก: 
							<span id="cancel"></span> รายการ
						</div>
					</div>
				</div>

				<!-- two box -->
				<div class="row">
					<div class="col-sm-12 mb-3 col-md-12 col-lg-6">
						<div class="content-box content-lighten sm-font-admin">
							<h3>สัดส่วนการจองห้องแต่ละประเภท</h3>
							<div class="col-4 col-md-3 col-lg-4 col-xl-2 col-lg-custom">
								<label>ปี</label>
								<select class="form-control chart2_select sm-font-admin">
									<?php for($i=$year;$i>=$year_min;$i--){ ?>
									<option value="<?php echo $i ?>"><?php echo $i ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-12 not_found" align="center" hidden>
								ไม่มีข้อมูล
							</div>
							<div class="col-12" id="chart2">
								<canvas id="myChart_doughnut"></canvas>
							</div>
						</div>
					</div>
					<div class="col-sm-12 mb-3 col-md-12 col-lg-6">
						<div class="content-box content-lighten sm-font-admin">
							<h3>ช่วงเวลาที่มีการทำรายการ</h3>
							<div class="col-4 col-md-3 col-lg-4 col-xl-2 col-lg-custom">
								<label>ปี</label>
								<select class="form-control chart3_select sm-font-admin">
									<?php for($i=$year;$i>=$year_min;$i--){ ?>
									<option value="<?php echo $i ?>"><?php echo $i ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-12 not_found3" align="center" hidden>
								ไม่มีข้อมูล
							</div>
							<div class="col-12" id="chart3">
								<canvas id="myChart_time"></canvas>
							</div>
						</div>
					</div>
				</div>

				<!-- two box -->
				<div class="row">
					<div class="col-sm-12 mb-3 col-md-12 col-lg-6">
						<div class="content-box content-lighten sm-font-admin">
							<h3>รายรับ</h3>
							<div class="col-12 row">
								
								<div class="col-4 col-md-3 col-lg-4 col-xl-2 col-lg-custom">
									<label>ปี</label>
									<select class="form-control chart4_select sm-font-admin">
										<?php for($i=$year;$i>=$year_min;$i--){ ?>
										<option value="<?php echo $i ?>"><?php echo $i ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="col-5  col-md-4 col-lg-5 col-xl-4">
									<label>แหล่ง</label>
									<select class="form-control chart4_select_source sm-font-admin">
										<option value="all">ทั้งหมด</option>
										<option value="other">เฉพาะรายการเพิ่มเติม</option>
									</select>
								</div>
							</div>
							
							<div class="col-12 not_found4" align="center" hidden>
								ไม่มีข้อมูล
							</div>
							<div class="col-12" id="chart4">
								<canvas id="myChart_income"></canvas>
							</div>
							<div class="col-12" align="center">
							รายรับทั้งหมด: <span class="number" id="total_income"></span> บาท
						</div>
						</div>
					</div>
					<div class="col-sm-12 mb-3 col-md-12 col-lg-6">
						<div class="content-box content-lighten sm-font-admin">
							<h3>สัดส่วนการชำระเงินแต่ละประเภท</h3>
							<div class="col-4 col-md-3 col-lg-4 col-xl-2 col-lg-custom">
								<label>ปี</label>
								<select class="form-control chart5_select sm-font-admin">
									<?php for($i=$year;$i>=$year_min;$i--){ ?>
									<option value="<?php echo $i ?>"><?php echo $i ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-12 not_found" align="center" hidden>
								ไม่มีข้อมูล
							</div>
							<div class="col-12" id="chart5">
								<canvas id="myChart_paymenttype"></canvas>
							</div>
						</div>
					</div>
				</div>

				<!-- two box -->
				<div class="row">
					<div class="col-sm-12 mb-3 col-md-12 col-lg-6">
						<div class="content-box content-lighten sm-font-admin">
							<h3>เรทราคาที่ถูกเลือก</h3>
							<div class="col-4 col-md-3 col-lg-4 col-xl-2 col-lg-custom">
								<label>ปี</label>
								<select class="form-control chart6_select sm-font-admin">
									<?php for($i=$year;$i>=$year_min;$i--){ ?>
									<option value="<?php echo $i ?>"><?php echo $i ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-12 not_found" align="center" hidden>
								ไม่มีข้อมูล
							</div>
							<div class="col-12" id="chart6">
								<canvas id="myChart_rate"></canvas>
							</div>
						</div>
					</div>
					<!-- <div class="col-sm-12 mb-3 col-md-12 col-lg-6">
						<div class="content-box content-lighten">
							<h3>สถานะห้องพักวันนี้</h3>
							<div id="today"></div><i class="fas fa-sync fa-spin sync"></i>&nbsp;&nbsp;<span class="sync_notice"></span>&nbsp;&nbsp; <a href="#"><i class="fas fa-pause pause"></i><i class="fas fa-play play" hidden></i></a>
						</div>
					</div> -->
				</div>

			</div>
		</div>
	</div>
	<?php include("include/script.html"); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css" integrity="sha256-aa0xaJgmK/X74WM224KMQeNQC2xYKwlAt08oZqjeF0E=" crossorigin="anonymous" />
	<script src="js/sidebar.menu.js"></script>
	<script src="js/jquery.formatNumber-0.1.1.js"></script>
	<script src="js/myScript_admin_index.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-colorschemes"></script>
</body>

</html>
