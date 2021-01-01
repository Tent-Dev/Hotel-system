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
								    <div class="col-11 row input-daterange input-group adminSettingSearchDate" id="datepicker">
									    <label>Check in:&nbsp;</label>
									    <div class="col-5 row">
									    	<input class="productDate datepicker form-control checkin2" id="checkin2" name="checkin2" autocomplete="off">
									    </div>
									    <label>&nbsp;Check out:&nbsp;</label>
									    <div class="col-5 row">
									    	<input class="productDate datepicker form-control checkout2" id="checkout2" name="checkout2" autocomplete="off">
									    </div>
									</div>&nbsp;&nbsp;<div class="col-2 row"><a href="system_hotel_admin_rooms_setting.php"><button class="btn btn-sm btn-info"><i class="fas fa-sync-alt"></i> รีเซ็ต</button></a></div>
							  	</div>
						  	</form>
						  	<div class="col-12 row">จำนวนห้องที่พบ:<span class="sumresult"></span></div>
						</div>
					</div>

					<div class="col-sm-6 mb-3 col-md-6">
						<div class="content-box content-lighten">
							<h3>Admin Manage</h3>
							<button class="btn btn-info" id="addroom" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-plus-square"></i> เพิ่มห้อง</button>
							<button class="btn btn-danger" id="submitdelcheck"><i class="fas fa-trash-alt"></i> ลบห้องหลายรายการ</button>
							<input type="button" class="btn btn-danger" id="submitdelcheckC" hidden value="ลบ">
							<button class="btn btn-warning" id="delcheckCancel" hidden>ยกเลิก</button>
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
							      <th scope="col">Manage</th>
							      <th scope="col" align="center" style="text-align: center;"><span id="checkAll" hidden><a href="#">Check All</a></span><span id="discheckAll" hidden><a href="#">Check All</a></span></th>
							    </tr>
							  </thead>
							  <tbody id="showTable_hotel">
							  </tbody>
							</table>
					</div>
		</div>
	</div>

<!-- Modal Add -->
	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มห้องพัก</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
			  <div class="col-12" align="center" id="RoomsForm">
			  <form action="#" method="POST" class="" id="sendRoomForm">
				<div class="col-12" align="left"><label for="name" style=" text-align: left;">หมายเลขห้อง</label></div>
				<div class="col-6" id="user2" align="center"><input name="roomno" id="roomno" placeholder="หมายเลขห้อง" class="form-control" required onblur="checkRoomAvailability()"></div><br>
	      <div class="col-12"><span id="room-availability-status"></span><i id="loadicon" class="fas fa-spinner fa-spin"></i></div>
				<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชนิดห้อง</label></div>
				<div class="col-6">
					<select class="form-control" id="type" name="type">
	                	<?php foreach ($resulttype as $readtype){ ?>
	      					<option class="input-text-bx" value="<?php echo $readtype['h_type_id'] ?>"><?php echo $readtype['h_type_name'] ?></option>
	    				<?php } ?>
	    			</select>
	    		</div>
				<div class="col-12" align="left"><label for="name" style=" text-align: left;">สถานะ</label></div>
				<div class="col-6" align="center">
					<select class="form-control pay" id="status" name="status">
	                	<?php foreach ($resultstatus as $readstatus){ ?>
	      					<option class="input-text-bx" value="<?php echo $readstatus['h_status_id'] ?>"><?php echo $readstatus['h_status_name'] ?></option>
	    				<?php } ?>
	    			</select>
				</div><br>
				<hr>
				<input type="submit" id="submitsignup" value="เพิ่มห้องพัก" class="btn btn-success"><br><br>
				<span id='message_alpha'></span><br>
	      <span id='message_alphapass'></span><br>
	      <span id='message_alphaConpass'></span>
				</form>

			  </div>
	      </div>
	    </div>
	  </div>
	</div>
<!-- /Modal -->

<!-- Modal DeleteMoreconfirm -->
				<div class="modal fade deletemoremodal" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบห้องพัก</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteMoreRoomsForm">
		  							<form action="" method="POST" id="sendDeleteMoreRoomForm">
										<div class="col-6 sumroom" align="center"></div>
										<hr>
										<input type="text" value="" name="roomidDeMore" id="roomidDeMore" hidden>
										<input type="submit" id="submitsignup" value="ยืนยันการลบห้อง" class="btn btn-danger">
									</form>
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
        						<h5 class="modal-title" id="exampleModalLongTitle2">ลบห้องพัก</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="deleteRoomsForm">
		  							<form action="" method="POST" id="sendDeleteRoomForm">
										<div class="col-12" align="left"><label for="name" style=" text-align: left;">หมายเลขห้อง</label></div>
										<div class="col-6" align="center"><input value="" name="roomnoDe" id="roomnoDe" class="form-control" disabled></div><br>
										<hr>
										<input type="text" value="" name="roomidDe" id="roomidDe" hidden>
										<input type="submit" id="submitsignup" value="ยืนยันการลบห้อง" class="btn btn-danger">
									</form>
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->

<!-- Modal Update -->
				<div class="modal fade editmodal" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  					<div class="modal-dialog modal-dialog-centered" role="document">
    					<div class="modal-content">
      						<div class="modal-header">
        						<h5 class="modal-title" id="exampleModalLongTitle2">แก้ไขห้องพัก</h5>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          						<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
      						<div class="modal-body">
		  						<div class="col-12" align="center" id="updateRoomsForm">
		  							<form action="" method="POST" id="sendUpdateRoomForm">
										<div class="col-12" align="left"><label for="name" style=" text-align: left;">หมายเลขห้อง</label></div>
										<div class="col-6" align="center"><input value="" name="roomnoUP" id="roomnoUP" class="form-control" disabled></div><br>
										<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชนิดห้อง</label></div>
										<div class="col-6">
											<select class="form-control" id="typeUP" name="typeUP">
                								<?php foreach ($resulttype as $readtype){ ?>
      											<option class="input-text-bx" value="<?php echo $readtype['h_type_id'];?>">
      													<?php echo $readtype['h_type_name'] ?></option>
    											<?php } ?>
    										</select>
    									</div>
										<div class="col-12" align="left"><label for="name" style=" text-align: left;">สถานะ</label></div>
										<div class="col-6" align="center">
											<select class="form-control pay" id="statusUP" name="statusUP">
                								<?php foreach ($resultstatus as $readstatus){ ?>
      											<option class="input-text-bx" value="<?php echo $readstatus['h_status_id'] ?>">
      													<?php echo $readstatus['h_status_name'] ?></option>
    											<?php } ?>
    										</select>
										</div><br>
										<hr>
										<input type="text" value="" name="roomidUP" id="roomidUP" hidden>
										<input type="submit" id="submitsignup" value="แก้ไข" class="btn btn-success">
									</form>
		  						</div>
      						</div>
    					</div>
  					</div>
				</div>
<!-- /Modal -->
</body>

<!-- javascript library -->
<script src="js/sidebar.menu.js"></script>
<script src="js/myScript_admin_ControlRooms.js" type="text/javascript" charset="utf-8"></script>
<script src="js/DML.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	var FormRequestClass = ".adminSettingSearchDate";
	  datepicker_set(FormRequestClass);
</script>
</html>