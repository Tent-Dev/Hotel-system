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
							    	<input class="form-control lock-while-delete" id="search" name="search" autocomplete="off" placeholder="Position name . . .">
							    </div>
							    <div class="col-4 justify-content-center align-self-center">
							    	<button class="btn btn-info btn-lg searchbar_btn lock-while-delete"><i class="fas fa-search"></i></button>
							    </div>
							</div><hr>
						    <label>สิทธิการใช้งาน</label><br>
						    <div class="form-check form-check-inline">
							  <input class="form-check-input permission_check lock-while-delete" type="radio" name="permission_search" id="permission_search1" value="1">
							  <label class="form-check-label" for="permission_search1">จำกัด</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input permission_check lock-while-delete" type="radio" name="permission_search" id="permission_search2" value="2">
							  <label class="form-check-label" for="permission_search2">ไม่จำกัด</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input permission_check lock-while-delete" type="radio" name="permission_search" id="permission_search3" value="3" checked>
							  <label class="form-check-label" for="permission_search3">ทั้งหมด</label>
							</div>
						    <hr>
						  	<div class="col-4 row">
								<a href="system_hotel_admin_ranklist.php">
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
							<button class="btn btn-info lock-while-delete" id="addbtn" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-plus-square"></i> เพิ่มตำแหน่งใหม่</button>
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
						      <th scope="col">ตำแหน่ง</th>
						      <th scope="col">สิทธิการใช้งาน</th>
						      <th scope="col"></th>
						      <th scope="col" align="center" style="text-align: center;"><span class="unlock-while-delete" id="checkboxAll" hidden><a href="#">Check All</a></span><span class="unlock-while-delete" id="unCheckboxAll" hidden><a href="#">Uncheck All</a></span></th>
						    </tr>
						  </thead>
						  <tbody id="display_rank">
						  </tbody>
						</table>
						<div class="pagination-div"></div>
					</div>
		</div>
	</div>

<!-- Modal Add -->
	<div class="modal fade rank_addmodel" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มตำแหน่งใหม่</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
			  <div class="col-12" align="center">
			  	<form action="#" method="POST" class="" id="sendRankAddForm">
					<div class="col-12" align="left"><label for="rank_name" style=" text-align: left;">ชื่อตำแหน่งใหม่</label></div>
					<div class="col-6"><input name="rank_name" id="rank_name" placeholder="ชื่อตำแหน่งใหม่" class="form-control" required></div>
					<div class="col-12" align="left"><label for="permission" style=" text-align: left;">สิทธิการใช้งาน</label></div>
					<div class="form-check form-check-inline">
						<input class="form-check-input permission_select" type="radio"
							   name="permission_select" id="permission_lock" value="1" checked>
						<label class="form-check-label" for="permission_lock">จำกัด</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input permission_select" type="radio"
							   name="permission_select" id="permission_unlock" value="2">
						<label class="form-check-label" for="permission_unlock">ไม่จำกัด</label>
					</div>
					<hr>
					<input type="submit" value="เพิ่มตำแหน่งใหม่" class="btn btn-success"><br>
				</form>
				<span id="successAlert_add" class="text-success"></span>
			  </div>
	      </div>
	    </div>
	  </div>
	</div>
<!-- /Modal -->

<!-- Modal DeleteMoreconfirm -->
				<div class="modal fade rank_deleteMoremodel" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบตำแหน่ง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteMoreRankForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal Delete -->
				<div class="modal fade rank_deletemodel" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบตำแหน่ง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteRankForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal Update -->
				<div class="modal fade rank_editmodel" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">แก้ไขตำแหน่ง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="rankEditForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

	<?php include("include/script.html"); ?>
	<script src="js/sidebar.menu.js"></script>
	<script src="js/myScript_admin_ControlRank.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/DML.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/myScript_pagination.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
</body>
</html>