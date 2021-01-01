<?php 
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
if(isset($_SESSION['getPermission'])){
	header("Location:system_hotel_admin_index.php");
}

?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<?php include("include/mytools.html"); ?>
		<link rel="stylesheet" href="css/myCss_admin.css">
	</head>
	<body>
	<section class="resume-section p-3 p-lg-5 d-flex align-items-center" id="system">
		<div class="container">
	     	<div class="col-12">
		        <h1 class="mb-0 text-center admin-font-head">TENT's
		          <span style="color: #B79C58;">Hotel reservation system</span>
		        </h1>
		        <div class="subheading mb-5 text-center" style="opacity: 0.5;">hotel management system.
		        </div>
		        <div class="text-center">
					<div class="col-12" align="center">
						<form id="sendLoginForm">
							<div class="input-group col-md-4 col-sm-6">
								<div class="input-group-prepend">
									<div class="input-group-text badge-textbox-black"><i class="fas fa-user"></i></div>
								</div>
								<input id="userlogin" name="user" placeholder="Username" class="form-control" required>
							</div>
							<br>
							<div class="input-group col-md-4 col-sm-6">
								<div class="input-group-prepend">
									<div class="input-group-text badge-textbox-black"><i class="fas fa-key"></i></div>
								</div>
								<input id="passlogin" name="pass" placeholder="Password" type="password" class="form-control" required>
							</div>
							<br>
							<div class="form-group col-6">
					        	<input type="submit" value="Login" class="btn" style="background-color: #4D4C48;color: white;">
					        	<br>
							</div>
							<span id="loginSuccess" class="sm-font-admin"></span>
			    		</form>
					</div>
					<hr>
					<span class="text-center" style="font-size: 12px; opacity: 0.5; ">By Chutipas Borsub | Trainee @ Orisma Technology Co., Ltd.</span>
				</div>
		    </div>
	  	</div>
	    </section>
	    <?php include("include/script.html"); ?>
	</body>
</html>