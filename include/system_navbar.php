
<!-- navbar -->	
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Hotel Booking Management</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav mr-3">
      <a class="nav-item nav-link active" href="system_index.php">Home <span class="sr-only">(current)</span></a>
      <a class="nav-item nav-link" href="../system_hotel.php">Hotel System</a>
      <a class="nav-item nav-link" href="#">Pricing</a>
      <a class="nav-item nav-link disabled" href="#">Disabled</a>
    </div>
	  <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white">
		  <img src="<?php if($read["h_user_image"] == null && $readSession["h_user_image"] == null){ echo('img/member_pic.png');}
                      else if( $readSession["h_user_image"] != null ){ echo $readSession["h_user_image"]; }
                      else { echo $read["h_user_image"]; } ?>" width="30px" height="30px">
          <?php
          echo($_SESSION['getUsername']." - ".$_SESSION['getType']);
          ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="../system_hotel_admin_settingAccount.php"><i class="fas fa-cog"></i>&nbsp;setting</a><hr style="margin: 1px;">
          <a class="dropdown-item" href="../system_changePassword.php"><i class="fas fa-key"></i>&nbsp;change pasword</a><hr style="margin: 1px;">
          <a class="dropdown-item logout" href="system/logout.php"><i class="fas fa-sign-out-alt"></i>&nbsp;logout</a>
        </div>
      </div>
      <span id="logoutSuccess"></span>
      <div class="session_status" style="color: white;"></div>
	  </div>
  </div>
</nav>
<!-- end of navbar -->