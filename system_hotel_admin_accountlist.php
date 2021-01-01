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
							    	<input class="form-control lock-while-delete" id="search" name="search" autocomplete="off" placeholder="Username, Firstname, Lastname . . .">
							    </div>
							    <div class="col-4 justify-content-center align-self-center">
							    	<button class="btn btn-info btn-lg searchbar_btn lock-while-delete"><i class="fas fa-search"></i></button>
							    </div>
							</div><hr>
						    <label>ตำแหน่ง</label><br>
						    <div class="col-12 row">
							    <?php foreach ($resultrank as $readrank){ ?>
							    <div><input type="checkbox" class="accountDetail status lock-while-delete" name="filter-1" value="<?php echo $readrank['h_userrank_id'] ?>">
							    <?php echo $readrank['h_userrank_name'] ?></div>&nbsp;
								<?php } ?>
							</div>
						    <hr>
						  	<div class="col-4 row">
								<a href="system_hotel_admin_accountlist.php">
									<button class="btn btn-sm btn-info"><i class="fas fa-sync-alt"></i> รีเซ็ต</button>
								</a>
							</div>
							<br>
						  	<div class="col-12 row">จำนวนบัญชีที่พบ:<span class="sumresult"></span></div>
						</div>
					</div>

					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<h3>Admin Manage</h3>
							<button class="btn btn-info lock-while-delete" id="addbtn" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-plus-square"></i> เพิ่มบัญชีผู้ใช้ใหม่</button>
							<button class="btn btn-danger unlock-while-delete" id="deleteMorebtn"><i class="fas fa-trash-alt"></i> ลบหลายรายการ</button>
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
						      <th scope="col" class="d-md-block d-none">รูปประจำตัว</th>
						      <th scope="col">Username</th>
						      <th scope="col">ชื่อ</th>
						      <th scope="col">นามสกุล</th>
						      <th scope="col">ตำแหน่ง</th>
						      <th scope="col"></th>
						      <th scope="col" align="center" style="text-align: center;"><span class="unlock-while-delete" id="checkboxAll" hidden><a href="#">Check All</a></span><span class="unlock-while-delete" id="unCheckboxAll" hidden><a href="#">Uncheck All</a></span></th>
						    </tr>
						  </thead>
						  <tbody id="display_account">
						  </tbody>
						</table>
						<div class="pagination-div"></div>
					</div>
		</div>
	</div>

<!-- Modal Add -->
	<div class="modal fade account_addmodel" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มบัญชีผู้ใช้ใหม่</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
			  <div class="col-12" align="center">
			  	<form action="#" method="POST" class="" id="sendAccountAddForm">
					<div class="col-12" align="left"><label for="Fname" style=" text-align: left;">Name</label></div>
					<div class="col-6"><input name="Fname" id="Fname" placeholder="ชื่อจริง" class="form-control" required></div>
					<div class="col-12" align="left"><label for="Lname" style=" text-align: left;">Surname</label></div>
					<div class="col-6"><input name="Lname" id="Lname" placeholder="นามสกุล" class="form-control" required></div>
					<div class="col-12" align="left"><label for="rank" style=" text-align: left;">Position</label></div>
					<div class="col-6">
						<select class="form-control guests" id="rank" name="rank">
					          <?php foreach ($resultrank as $readrank){ ?>
					          <option value="<?php echo $readrank['h_userrank_id']; ?>"><?php echo $readrank['h_userrank_name']; ?></option>
					          <?php } ?>
					    </select>
					</div>
					<hr>
					<div class="col-12" align="left"><label for="name" style=" text-align: left;">Username</label></div>
					<div class="col-6" id="user2" align="center"><input name="user" id="user" placeholder="Username" class="form-control checksame" required autocomplete="off"></div><br>
		      		<div class="col-12 same-validate"><span id="user-availability-status"></span></div>
		      		<br>
					<div class="col-12"><span id="message_user"></span></div>
					<div class="col-12" align="left"><label for="name" style=" text-align: left;">Password</label></div>
					<div class="col-6" align="center"><input id="pass" name="pass" placeholder="Password" class="form-control" type="password" required></div><br>
					
					<div class="col-6" align="center"><input id="Conpass" name="Conpass" placeholder="Confirm Password" class="form-control" type="password" required></div>
		      		<div class="col-12"><span id="message_pass"></span></div>
					<hr>
					<input type="submit" value="เพิ่มบัญชีผู้ใช้" class="btn btn-success"><br><br>
					<span id="message_alert" hidden>
						<span class="validate" id="message_alpha"></span>
					    <span class="validate" id="message_alphapass"></span>
					    <span class="validate" id="message_alphaConpass"></span>
					</span>
				</form>
				<span id="successAlert_add"></span>
			  </div>
	      </div>
	    </div>
	  </div>
	</div>
<!-- /Modal -->

<!-- Modal DeleteMoreconfirm -->
				<div class="modal fade account_deleteMoremodel" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบบัญชีผู้ใช้</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteMoreAccountForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal Delete -->
				<div class="modal fade account_deletemodel" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบบัญชีผู้ใช้</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteAccountForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal Update -->
				<div class="modal fade account_editmodel" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">แก้ไขบัญชีผู้ใช้</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="accountEditForm">
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

	<?php include("include/script.html"); ?>
	<script src="js/sidebar.menu.js"></script>
	<script src="js/myScript_admin_ControlAccount.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/DML.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
	<script src="js/myScript_pagination.js?v=<?php echo VERSION_NUMBER;?>" type="text/javascript" charset="utf-8"></script>
</body>
</html>