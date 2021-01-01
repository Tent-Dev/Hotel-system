<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
include("include/mytools.html");
session_start();

include("system/class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

///////////////////////image profile////////
// $result = $mysql->UIDProfile($UID);       //
// foreach ($result as $read);               //
///////////////////////////////////////////
?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Tent's Hotel - Contact us</title>
<meta name="keywords" content="Hotel, Tent's Hotel"/>
<meta name="description" content="A luxury Tent's Hotel, Project Hotel reservation system by Chutipas Bosub. Thai-Nichi Institute of technology" />
<style>
  #map {
    height: 40%;
  }
</style>
</head>
<body>
  
<!-- navbar --> 
  <?php include('include/hotel_user_nav.php'); ?>
<!-- end of navbar -->
  
<!-- section 1 -->
<section class="" style="background-color: #B79C58;">
  <div id="map">
  <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 13.738622, lng: 100.615935},
          zoom: 16
        });

        var marker = new google.maps.Marker({
            position: {lat: 13.738622, lng: 100.615935},
            map: map,
            title: 'Tent\'Hotel'
          });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDdqQK-gkGWZ-Lm6lf2Yd5cuh0w6xSV0jg&callback=initMap"
    async defer></script>
  </div>
</section>
<!-- end of section 1 -->

<!-- section 3 -->
<section class="text-center" style="padding: 10px; background-color: #DCCCA3;">
  <div class="container">
    <div class="row" style="margin-top: 20px; margin-bottom: 20px;">
      <div class="col-md-12 col-lg-5 border-right-custom textcontact text-custom-searchbar set-center-responsive font-small-xs" align="left">
        Tent's Hotel reservation System Co.,Ltd.<br>
        235/233 Pattanakarn Rd. Bangkok 10250<br><br>
        <i class="fas fa-phone"></i> <a href="tel:+66830884161">+66 1234 5678</a><br>
        <i class="fas fa-envelope"></i> <a href="mailto:bo.chutipas_st@tni.ac.th?subject=From%20contact%20page">Tent.Reservation@tni.ac.th</a><br><br>
        <div class="socialcontact">
          <a href="#"><i class="fab fa-facebook-square"></i></a>  
          <a href="#"><i class="fab fa-instagram"></i></a> 
          <a href="#"><i class="fab fa-twitter-square"></i></a>
        </div>
      </div>
      
      <hr class="hr-custom d-block d-lg-none">

      <div class="col-md-12 col-lg-7" align="left">
        <div class="col-12 textcontact text-custom-searchbar set-center-responsive font-small-xs">
          Thanks for your interest. Please complete the form below to send us<br class="d-none d-sm-block">
          your question or comment and we'll get back to you as soon as possible!
        </div>
        <br>
        <form class="sendmailForm text-custom-searchbar set-center-responsive">
          <div class="col-12">
            <div class="row">
              <div class="col-6 row form-email">
                <div class="col-12 text-fix-left">
                  <label class="label-custom">First name</label>
                </div>
                <div class="col-12">
                  <input type="text" id="Fname" class="form-control textbox-foruser" placeholder="First name" required>
                </div>
              </div>

              <div class="col-6 row form-email">
                <div class="col-12 text-fix-left">
                  <label class="label-custom">Last name</label>
                </div>
                <div class="col-12">
                  <input type="text" id="Lname" class="form-control textbox-foruser" placeholder="Last name" required>
                </div>
              </div>

              <div class="col-6 row form-email">
                <div class="col-12 text-fix-left">
                  <label class="label-custom">E-mail</label>
                </div>
                <div class="col-12">
                  <input type="text" id="Email" class="form-control textbox-foruser" placeholder="E-mail" required>
                </div>
              </div>

              <div class="col-6 row form-email">
                <div class="col-12 text-fix-left">
                  <label class="label-custom">Tel</label>
                </div>
                <div class="col-12">
                  <input type="text" id="Tel" class="form-control textbox-foruser" placeholder="Telephone number" required>
                </div>
              </div>

              <div class="col-12 row form-email">
                <div class="col-12 text-fix-left">
                  <label class="label-custom">Comment</label>
                </div>
                <div class="col-12">
                  <textarea rows="4" id="Comment" type="textarea" class="form-control textbox-foruser" placeholder="Comment . . ." required></textarea>
                </div>
              </div>

              <div class="col-12 row form-email">
                <div class="col-12" align="center">
                  <button class="btn button-dark sendmail">Send</button><br>
                  <span class="contact_msg font-small-xs"></span>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  </section>
<!-- end of section 3 -->

<!-- footer -->
<?php include("include/footer.php"); ?>
<?php include("include/script.html"); ?>
<!-- end of footer -->
</body>
<script src="../js/myScript_Sendmail_Tome.js"></script>
</html>