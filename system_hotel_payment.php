<?php include("include/mytools.html");
session_start();
// if($_SESSION['getPermission'] == ""){
//     header("Location:index.php");
//   }
// $UID = $_SESSION['getId'];

include("system/class_db.php");

$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$guests = $_POST['guests'];
$typename = $_POST['type'];
$typeid = $_POST['typeid'];
$capacity = $_POST['capacity'];
$session = $_POST['session'];
$rooms = $_POST['rooms'];
$total = $_POST['total'];
$sumdate = $_POST['sumdate'];
$rate = $_POST['rate'];
$rateid = $_POST['rateid'];
$ratedeposit = $_POST['ratedeposit'];

if($_SESSION['sessionTemp'] != $session){ //เช็คว่า ผู้ใช้แอบเปลี่ยนเลข session หรือไม่
  header("Location:system_hotel_user.php");
  exit();
}

$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

///////////////////////image profile////////
// $result = $mysql->UIDProfile($UID);       //
// foreach ($result as $read);               //
///////////////////////////////////////////

$sqlpaymenttype = "SELECT * FROM h_paymenttype";
$resultpaymenttype = $mysql->Select_db($sqlpaymenttype);

$now = date("Y-m-d");
$sqlrate = "SELECT * FROM h_rate
            WHERE h_rate_id = '".$rateid."'";

$resultrate = $mysql->Select_db($sqlrate);
foreach ($resultrate as $readrate);
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Tent's Hotel - Payment</title>
<meta name="keywords" content="Hotel, Tent's Hotel"/>
<meta name="description" content="A luxury Tent's Hotel, Project Hotel reservation system by Chutipas Bosub. Thai-Nichi Institute of technology" />
<style>
  
  .hide option[disabled] {
    display: none;
  }

</style>
</head>
<body>
  
<!-- navbar --> 
  <?php include('include/hotel_user_nav.php'); ?>
<!-- end of navbar -->
  
<!-- section 1 -->
<section class="text-center" style="padding: 10px;">
  <div class="section-room section-reservation-page"></div>
  <div class="container img-thumb">
    <h1 class="text-custom-default">Reservation</h1>
    <p class="font-weight-light text-custom-quote">Finally, Fill out your information and wait to receive an email response.</p><hr class="hr-custom">
  </div>
</section>
<!-- end of section 1 -->
<?php if($readrate['h_rate_dateset'] == 0 || ($readrate['h_rate_dateset'] == 1 && $now < $readrate['h_rate_dateend'])){
 ?>
<form id="sendBookingForm" class="text-custom-default">
  <div class="col-12" align="center">
    <img src="../img/_page/step3.png" class="step-img">
  </div>
<div class="card container all-right-zero">
    <div class="card-body col-12 row all-right-zero">
      <div class="col-12 col-md-12 col-lg-8">
        <div class="col-12">
          <h4 class="font-small-xs">
          <?php echo (date('m/d/Y', strtotime($checkin))." - ".date('m/d/Y', strtotime($checkout))."<br class=\"d-sm-none d-block\"> | ".$sumdate." Night");
              if($sumdate > 1){ echo "s"; }
              echo(" | ".$guests." Guest");
              if($guests > 1){ echo "s"; }
              echo(" | ".$rooms." Room");
              if($rooms > 1){ echo "s"; }
          ?>
          </h4>
        </div>
        <br>
        <div class="col-12">
          <h4 class="font-small-xs"><?php echo ($typename."<br class=\"d-sm-none d-block\"> | ".$rate ) ?></h4>
        </div>

        <hr>

        <div class="col-12">
          <h5 class="font-small-xs">Total : <span style="float:right;"><?php echo " ".number_format($total,2)." THB" ?>
          </span></h5> 
          <h5 class="font-small-xs">VAT 7%: <span style="float:right;"><?php echo " ".number_format($total/100*7,2)." THB" ?>
          </span></h5> 
          <h3 class="font-small-xs">Total Price for Stay: <span style="float:right;">
            <?php echo " ".number_format($total+($total/100*7),2)." THB"; $total = $total+($total/100*7); ?>
          </span></h3> 
        </div>
      </div>
    </div>
</div>
<br>
<div class="card container">
  <div class="card-body col-12 row all-right-zero">
    <div class="col-12 col-md-12 col-lg-8 all-right-zero font-small-xs">
      <br>
      <div class="col-12">
        <h4>Guest Information</h4>
      </div>
      <div class="col-12 row all-right-zero">
        <div class="col-12 col-md-6 row all-right-zero">
          <div class="col-12">
            <label for="name" style=" text-align: left;">First Name</label>
          </div>
          <div class="col-12">
            <input id="Fname" name="Fname" placeholder="Firstname" class="form-control input-text-bx" required>
          </div>
        </div>
        <div class="col-12 col-md-6 row all-right-zero">
          <div class="col-12">
            <label for="name" style=" text-align: left;">Last Name</label>
          </div>
          <div class="col-12">
            <input id="Lname" name="Lname" placeholder="Lastname" class="form-control input-text-bx" required>
          </div>
        </div>
      </div>

      <br>
        
      <div class="col-12 col-md-6 row">
        <div class="col-12">
          <label for="name" style=" text-align: left;">Email Address</label>
        </div>
        <div class="col-12">
          <input id="email" name="email" placeholder="Email Address" class="form-control input-text-bx" required>
        </div>
      </div>

      <br>
        
      <div class="col-12 col-md-6 row">
        <div class="col-12">
          <label for="name" style=" text-align: left;">Country/Region</label>
        </div>
        <div class="col-12">
          <input id="region" name="region" placeholder="Country/Region" class="form-control input-text-bx" required>
        </div>
      </div>

      <br>
        
      <div class="col-12 col-md-6 row">
        <div class="col-12">
          <label for="name" style=" text-align: left;">Address</label>
        </div>
        <div class="col-12">
          <input id="address" name="address" placeholder="Address" class="form-control input-text-bx" required>
        </div>
      </div>

      <br>
        
      <div class="col-12 col-md-6 row">
        <div class="col-12">
          <label for="name" style=" text-align: left;">City/Town</label>
        </div>
        <div class="col-12">
          <input id="city" name="city" placeholder="City/Town" class="form-control input-text-bx" required>
        </div>
      </div>

      <br>
        
      <div class="col-12 col-md-6 row">
        <div class="col-12">
          <label for="name" style=" text-align: left;">Postal Code</label>
        </div>
        <div class="col-12">
          <input id="postal" name="postal" placeholder="Postal Code" class="form-control input-text-bx" required>
        </div>
      </div>

      <br>
        
      <div class="col-12 col-md-6 row">
        <div class="col-12">
          <label for="name" style=" text-align: left;">Phone Number</label>
        </div>
        <div class="col-12 col-md-8">
          <input id="phone" name="phone" placeholder="811111111" class="form-control input-text-bx" required>
        </div>
      </div>

      <br>
        
      <div class="col-12 row">
        <div class="col-12">
          <label for="name" style=" text-align: left;">Special Request</label>
        </div>
        <div class="col-12">
          <span style="font-size: 12px;">We will forward any special information that you would like sent with your reservation. Requests cannot be guaranteed and are based on availability and on a first-come-first-served basis.
          </span>
        </div>
        <div class="col-12">
          <textarea id="special" name="special" class="form-control input-text-bx"></textarea>
        </div>
      </div>
    </div>
  </div>
</div>

    <br>
    <?php if($ratedeposit == 1){ ?>
      <div class="card container">
        <div class="card-body col-12 row all-right-zero">
          <div class="col-12 all-right-zero">
            <h4>Payment Information</h4>
            <h6 class="font-small-xs">
              This payment card is used to hold your room until you arrive on your confirmed check-in date.
              <br><br>
              If you book a rate that requires a deposit or prepayment, this payment card will be charged anytime before your check-in date. For details, please review the rate description and rate rules.
            </h6>
            <br>
          </div>
          <div class="col-12 all-right-zero font-small-xs">
            <div class="col-12 row all-right-zero">
              <div class="col-12 col-md-5 col-lg-3 row all-right-zero">
                <div class="col-12 all-right-zero">
                  <label for="name" style=" text-align: left;">Payment Card Type</label>
                </div>
                <div class="col-12">
                  <select class="form-control pay hide" id="paymenttype" name="paymenttype">
                    <?php foreach ($resultpaymenttype as $readpaymenttype){ ?>
                    <option class="input-text-bx" value="<?php echo $readpaymenttype["h_paymenttype_id"] ?>" <?php if($readpaymenttype['h_paymenttype_id'] == 5){ echo "disabled";} ?>><?php echo $readpaymenttype['h_paymenttype_type'] ?>
                    </option>
                    <?php } ?>
                  </select>
                  <input id="paymenttype2" name="paymenttype2" class="inp" value="1" hidden="">
                </div>
              </div>
              <div class="col-12 col-md-5 col-lg-4 row all-right-zero">
                <div class="col-12 all-right-zero">
                  <label for="name" style=" text-align: left;">Payment Card Number</label>
                </div>
                <div class="col-12">
                  <input id="paymentnum" name="paymentnum" placeholder="Payment Card Number" class="form-control input-text-bx" required>
                </div>
              </div>
            </div>
            <br>
              <div class="col-12">
                <label for="name" style=" text-align: left;">Expiration Date</label>
              </div>
              <div class="col-12 row">
                <div class="col-12 col-md-3">
                  <input id="paymentexp" name="paymentexp" placeholder="Expiration Date" class="form-control input-text-bx" required>
                </div>
                <div class="col-12 col-md-3">
                  <input id="paymentyear" name="paymentyear" placeholder="Year" class="form-control input-text-bx" required>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php }else{ ?>
              <input id="paymenttype2" name="paymenttype2" class="inp" value="5" hidden>
      <?php
      } ?>
        <div class="container" align="center">
          <br>
        <!-- Save value   -->
        <input id="total" name="total" value="<?php echo $total ?>" hidden>
        <input id="session" name="session" value="<?php echo $session ?>" hidden>
        <input id="checkin" name="checkin" value="<?php echo $checkin ?>" hidden>
        <input id="checkout" name="checkout" value="<?php echo $checkout ?>" hidden>
        <input id="rooms" name="rooms" value="<?php echo $rooms ?>" hidden>
        <input id="guests" name="guests" value="<?php echo $guests ?>" hidden>
        <input id="typename" name="typename" value="<?php echo $typename ?>" hidden>
        <input id="rate" name="rate" value="<?php echo $rate ?>" hidden>
        <input id="rateid" name="rateid" value="<?php echo $rateid ?>" hidden>
        <!-- /Save value   -->
        <input type="button" id="confirmbook" value="Confirm Book" class="btn btn-lg btn-success" hidden>
        <input type="submit" id="submitsignup" value="Book Reservation" class="btn btn-lg button-dark">
        <input type="button" id="edit" value="Edit" class="btn btn-lg btn-warning" hidden>
        <div class="col-12"><i  class="fas fa-spinner fa-spin load"></i><span class="load"> Sending...</span></div>
        </form>
        <br>
        <br>
        </div>
      </div>
<?php }else{ ?>

      <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-auto">
              <div class="alert alert-warning shadow-sm rounded" role="alert" align="left" style="font-size: 20px;"><i class="fas fa-exclamation-circle"></i>&nbsp;<span class="font-small-xs">Sorry. This rate has expired.<br></span>
              </div>
            </div>
        </div>
      </div>

<?php } ?>
<!-- footer -->
<?php include("include/footer.php"); ?>
<?php include("include/script.html"); ?>
<!-- end of footer -->
    
</body>

<!-- เช็ค Session Timeout alert -->
<script type="text/javascript">

  //$(document).ready(function () {
  //   set = setInterval(refresh_div, 1000);
  //});

  // function refresh_div() {
  //     jQuery.ajax({
  //           url:'system/refresh.php',
  //           method:"POST",
  //           type: 'json',
  //           success:function(results) {
  //               $.each(JSON.parse(results), function(key,value){
  //                 console.log('Time status: '+value);
  //                 if(value == 0){
  //                   $.removeCookie('popup');
  //                   alert('Timeout');
  //                   clearInterval(set); //ยกเลิกการเซตเวลา
  //                   document.location.href = 'system_hotel_user.php'
  //                 }
  //               });
  //           }
  //     });
  // }
</script>

</html>