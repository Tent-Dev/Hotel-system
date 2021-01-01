<li class="list-item">
	<p class="list-title text-uppercase">Dashboard</p>
				<ul class="list-unstyled">
							<li><a href="system_hotel_admin_index.php" class="list-link"><span class="list-icon"><i class="fas fa-home"></i></span>หน้าหลัก</a></li>
						</ul>
					</li>

					<!-- multi-level dropdown menu -->
					<li class="list-item">
						<p class="list-title text-uppercase">Rooms</p>
						<ul class="list-unstyled">
							<li><a href="system_hotel_admin_rooms.php" class="list-link"><span class="list-icon"><i class="fas fa-clipboard-list"></i></span>รายการห้องพักทั้งหมด</a></li>
							<?php if($_SESSION['getPermission'] == 2){?>
							<li><a href="#" class="list-link link-arrow link-current"><span class="list-icon"><i class="fa fa-cog" aria-hidden="true"></i></span>Settings</a>
								<ul class="list-unstyled list-hidden">
									<li><a href="system_hotel_admin_rooms_setting.php" class="list-link"><span class="list-icon"><i class="fas fa-door-closed"></i></span>จัดการห้องพัก</a></li>
									<li><a href="system_hotel_admin_type.php" class="list-link"><span class="list-icon"><i class="fas fa-cogs"></i></span>จัดการชนิดห้องพัก</a></li>
									<li><a href="system_hotel_admin_rate.php" class="list-link"><span class="list-icon"><i class="fas fa-money-bill-wave"></i></span>จัดการเรทราคาห้องพัก</a></li>
									<li><a href="system_hotel_admin_bedlist.php" class="list-link"><span class="list-icon"><i class="fas fa-bed"></i></span>จัดการประเภทเตียง</a></li>
									<li><a href="system_hotel_admin_statuslist.php" class="list-link"><span class="list-icon"><i class="fas fa-eye"></i></span>จัดการสถานะห้องพัก</a></li>
								</ul>
							</li>
							<?php } ?>
						</ul>
					</li>

					<!-- simple menu -->
					<li class="list-item">
						<p class="list-title text-uppercase">Bills</p>
						<ul class="list-unstyled">
							<li><a href="system_hotel_admin_booklist.php" class="list-link"><span class="list-icon"><i class="fas fa-clipboard-list"></i></span>ดูรายการจองห้อง</a></li>
						</ul>
					</li>
					<?php if($_SESSION['getPermission'] == 2){?>
					<li class="list-item">
						<p class="list-title text-uppercase">Account</p>
						<ul class="list-unstyled">
							<li><a href="system_hotel_admin_accountlist.php" class="list-link"><span class="list-icon"><i class="fas fa-user-cog"></i></span>จัดการบัญชีผู้ใช้ระบบ</a></li>
							<li><a href="system_hotel_admin_ranklist.php" class="list-link"><span class="list-icon"><i class="fas fa-address-card"></i></span>จัดการสิทธิการใช้งาน</a></li>
						</ul>
					</li>
					<?php } ?>

					<li class="list-item d-md-none d-block">
						<p class="list-title text-uppercase">My Account</p>
						<ul class="list-unstyled">
							<li><a href="../system_hotel_admin_settingAccount.php" class="list-link"><span class="list-icon"><i class="fas fa-cog"></i></span>แก้ไขข้อมูลส่วนตัว</a></li>
							<li><a href="../system_hotel_admin_changepassword" class="list-link"><span class="list-icon"><i class="fas fa-key"></i></span>เปลี่ยนรหัสผ่าน</a></li>
							<li><a href="#" class="list-link logout"><span class="list-icon"><i class="fas fa-sign-out-alt"></i></span>ออกจากระบบ</a></li>
							<span class="logoutSuccess"></span>
						</ul>
					</li>
					<hr>
					<li>Server time:<br>
						<?php $currentDateTime = date('d-M-Y H:i:s');echo $currentDateTime; ?>
					</li>
				</ul>
			</div>
		</div>

		<!-- website content -->
		<div class="content">

			<!-- navbar top fixed -->
			<nav class="navbar navbar-expand-lg fixed-top navbar-dark" style="background-color: #4D4C48 !important; box-shadow: 0 4px 10px -2px gray;">

				<!-- navbar title -->
				<a class="navbar-brand navbar-link" href="#">Hotel Booking Management</a>

				<!-- navbar sidebar menu toggle -->
				<span class="navbar-text">
					<a href="#" id="sidebar-toggle" class="navbar-bars">
						<i class="fa fa-bars" aria-hidden="true"></i>
					</a>
				</span>

				<!-- navbar dropdown menu-->
				<div class="collapse navbar-collapse ">
					<div class="dropdown dropdown-logged dropdown-logged-lighten">
						<a href="#" data-toggle="dropdown" class="dropdown-logged-toggle dropdown-link">
							<span class="dropdown-user float-left" style="color: white;"><?php echo($_SESSION['getUsername']); ?></span>
							<img src="<?php if($read["h_user_image"] == null && $readSession["h_user_image"] == null){ echo('img/member_pic.png');}
                      else if( $readSession["h_user_image"] != null ){ echo $readSession["h_user_image"]; }
                      else { echo $read["h_user_image"]; } ?>" class="dropdown-avatar">
						</a>
						<span class="logoutSuccess"></span>
						<div class="dropdown-menu dropdown-logged-menu dropdown-menu-right border-0 dropdown-menu-lighten">
							<div class="dropdown-menu-arrow"><i class="fa fa-caret-down" aria-hidden="true"></i></div>
							<a class="dropdown-item dropdown-logged-item" href="system_hotel_admin_settingAccount.php"><i class="fas fa-cog"></i> Setting profile</a>
							<a class="dropdown-item dropdown-logged-item" href="system_hotel_admin_changepassword.php"><i class="fas fa-key"></i> Change password</a>
							<div class="dropdown-divider border-light"></div>
							<a class="dropdown-item dropdown-logged-item logout" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
						</div>
					</div>
				</div>
			</nav>