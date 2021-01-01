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

$sqlrank = "SELECT * FROM h_userrank";
$resultrank = $mysql->Select_db($sqlrank);

?>

<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<?php include("include/mytools.html"); ?>
	<link rel="stylesheet" href="css/myCss_admin.css?v=<?php echo VERSION_NUMBER;?>">
	<link rel="stylesheet" href="css/colorPick.css?v=<?php echo VERSION_NUMBER;?>">
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
					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<label>Search:&nbsp;</label>
							<div class="col-12 row">
							    <div class="col-8">
							    	<input class="form-control lock-while-delete" id="search" name="search" autocomplete="off" placeholder="Status name . . .">
							    </div>
							    <div class="col-4 justify-content-center align-self-center">
							    	<button class="btn btn-info btn-lg searchbar_btn lock-while-delete"><i class="fas fa-search"></i></button>
							    </div>
							</div><hr>
						    <label>สิทธิการจอง</label><br>
						    <div class="form-check form-check-inline">
							  <input class="form-check-input status_check lock-while-delete" type="radio" name="permission_search" id="status_search1" value="1">
							  <label class="form-check-label" for="status_search1">เปิด</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input status_check lock-while-delete" type="radio" name="permission_search" id="status_search2" value="2">
							  <label class="form-check-label" for="status_search2">ปิด</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input status_check lock-while-delete" type="radio" name="permission_search" id="status_search3" value="3" checked>
							  <label class="form-check-label" for="status_search3">ทั้งหมด</label>
							</div>
						    <hr>
						  	<div class="col-4 row">
								<a href="system_hotel_admin_statuslist.php">
									<button class="btn btn-sm btn-info"><i class="fas fa-sync-alt"></i> รีเซ็ต</button>
								</a>
							</div>
							<br>
						  	<div class="col-12 row">จำนวนรายการที่พบ:<span class="sumresult"></span></div>
						</div>
					</div>

					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<h3>Admin Manage</h3>
							<button class="btn btn-info lock-while-delete" id="addbtn" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-plus-square"></i> เพิ่มสถานะห้องใหม่</button>
							<button class="btn btn-danger" id="deleteMorebtn"><i class="fas fa-trash-alt"></i> ลบหลายรายการ</button>
							<input type="button" class="btn btn-danger unlock-while-delete" id="selectDeleteMorebtn" hidden value="ลบ">
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
						      <th scope="col"></th>
						      <th scope="col">สถานะ</th>
						      <th scope="col">สิทธิการจอง</th>
						      <th scope="col"></th>
						      <th scope="col" align="center" style="text-align: center;"><span class="unlock-while-delete" id="checkboxAll" hidden><a href="#">Check All</a></span><span class="unlock-while-delete" id="unCheckboxAll" hidden><a href="#">Uncheck All</a></span></th>
						    </tr>
						  </thead>
						  <tbody id="display_status">
						  </tbody>
						</table>
						<div class="pagination-div"></div>
					</div>
		</div>
	</div>

<!-- Modal Add -->
	<div class="modal fade status_addmodel" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มสถานะใหม่</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
			  <div class="col-12" align="center">
			  	<form action="#" method="POST" class="" id="sendStatusAddForm">
					<div class="col-12" align="left"><label for="status_name" style=" text-align: left;">ชื่อสถานะใหม่</label></div>
					<div class="col-6"><input name="status_name" id="status_name" placeholder="ชื่อสถานะใหม่" class="form-control sm-font-admin" required></div>
					<div class="col-12" align="left"><label for="permission" style=" text-align: left;">สิทธิการจอง</label></div>
					<div class="form-check form-check-inline">
						<input class="form-check-input status_select sm-font-admin" type="radio"
							   name="status_select" id="status_open" value="1" checked>
						<label class="form-check-label" for="status_open">เปิด</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input status_select" type="radio"
							   name="status_select" id="status_close" value="2">
						<label class="form-check-label" for="status_close">ปิด</label>
					</div>
					<div class="col-12" align="left"><label for="status_color" style=" text-align: left;">สีสถานะ</label></div>
					<div class="col-12" align="center">
						<div class="col-5 row" align="center">
							<div class="col-3 colorPickSelector"></div>
							<div class="col-5 color_code justify-content-center align-self-center"></div>
						</div>
					</div>
					<hr>
					<input type="submit" value="เพิ่มสถานะใหม่" class="btn btn-success"><br>
				</form>
				<span id="successAlert_add" class="text-success"></span>
			  </div>
	      </div>
	    </div>
	  </div>
	</div>
<!-- /Modal -->

<!-- Modal DeleteMoreconfirm -->
				<div class="modal fade status_deleteMoremodel" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบสถานะห้อง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteMoreStatusForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal Delete -->
				<div class="modal fade status_deletemodel" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบสถานะห้อง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteStatusForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal Update -->
				<div class="modal fade status_editmodel" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">แก้ไขสถานะห้อง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="statusEditForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

	<?php include("include/script.html"); ?>
	<script src="js/sidebar.menu.js"></script>
	<script src="js/colorPick.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/myScript_admin_ControlStatus.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/DML.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/myScript_pagination.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
</body>
</html>