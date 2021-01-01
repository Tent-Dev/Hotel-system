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
							    	<input class="form-control lock-while-delete" id="search" name="search" autocomplete="off" placeholder="Type name . . .">
							    </div>
							    <div class="col-4 justify-content-center align-self-center">
							    	<button class="btn btn-info btn-lg searchbar_btn lock-while-delete"><i class="fas fa-search"></i></button>
							    </div>
							</div><hr>
						  	<div class="col-4 row">
								<a href="system_hotel_admin_bedlist.php">
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
							<button class="btn btn-info lock-while-delete" id="addbtn" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-plus-square"></i> เพิ่มประเภทเตียงใหม่</button>
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
						      <th scope="col">ไอคอน</th>
						      <th scope="col">ประเภทเตียง</th>
						      <th scope="col">รายละเอียด</th>
						      <th scope="col"></th>
						      <th scope="col" align="center" style="text-align: center;"><span class="unlock-while-delete" id="checkboxAll" hidden><a href="#">Check All</a></span><span class="unlock-while-delete" id="unCheckboxAll" hidden><a href="#">Uncheck All</a></span></th>
						    </tr>
						  </thead>
						  <tbody id="display_bed">
						  </tbody>
						</table>
						<div class="pagination-div"></div>
					</div>
		</div>
	</div>

<!-- Modal Add -->
	<div class="modal fade bed_addmodel" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มประเภทเตียงใหม่</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
			  <div class="col-12" align="center">
			  	<form action="#" method="POST" class="" id="sendBedAddForm" enctype="multipart/form-data">
					<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชื่อประเภทเตียง</label></div>
			      	<div class="col-12 col-md-6"><input id="bed_name" placeholder="ชื่อประเภทเตียง" class="form-control" required></div>
			      	<br>
			      	<div class="col-12" align="left"><label for="name" style=" text-align: left;">รายละเอียด</label></div>
			      	<div class="col-12 col-md-6" align="center"><input id="bed_desc" placeholder="รายละเอียด" class="form-control" required></div>
			      	<hr>
			      	<div class="col-12" align="left"><label for="name" style=" text-align: left;">ไอคอน</label></div>
			      	<div class="col-12 row">
			      		<div class="col-2" align="left"><img class="imgPreview_add" height="30px" src=""/></div>
				      	<div class="col-10 col-md-6" align="left" class="custom-file" style="margin-bottom: 10px;">
				      		<input type="file" id="fileUpload" class="custom-file-input upload" data-command="upload_icon_add" data-element=".imgPreview_add" accept="image/*">
				      		<label class="custom-file-label" for="customFile">เลือกไฟล์</label>
				      	</div>
				      	<div class="col-12 col-md-4">
				      		<button class="btn btn-sm btn-danger" id="btnDelete_image_bed_add" type="button" data-element=".imgPreview_add">
				      			<i class="fas fa-trash-alt"></i> ลบไอคอน
				      		</button>
				      	</div>
			      	</div>
			      	<br>
			      	<hr>
			      	<div align="center">
				      	<input type="submit" value="เพิ่มประเภทเตียงใหม่" class="btn btn-success Add" data-command="upload_icon_add" data-element=".imgPreview_add"><br><br>
						<span id="successAlert_add" class="text-success"></span>
			      	</div>
				</form>
				<span id="successAlert_add" class="text-success"></span>
			  </div>
	      </div>
	    </div>
	  </div>
	</div>
<!-- /Modal -->

<!-- Modal DeleteMoreconfirm -->
				<div class="modal fade bed_deleteMoremodel" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบประเภทเตียง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteMoreBedForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal Delete -->
				<div class="modal fade bed_deletemodel" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบประเภทเตียง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteBedForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal Update -->
				<div class="modal fade bed_editmodel" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">แก้ไขประเภทเตียง</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="bedEditForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

	<?php include("include/script.html"); ?>
	<script src="js/sidebar.menu.js"></script>
	<script src="js/myScript_admin_ControlBed.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/DML.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/myScript_pagination.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
</body>
</html>