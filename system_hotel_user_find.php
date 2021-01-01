<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
include("include/mytools.html");
session_start();
unset($_SESSION["New_booking_Customer"]);
        unset($_SESSION['sessionTemp']);
include("system/class_db.php");

$checkin = date("Y/m/d");
$checkout = date("Y/m/d", strtotime("+1 day"));
$guests = 1;
$rooms = 1;
// $dteStart = new DateTime($checkin); 
// $dteEnd   = new DateTime($checkout);

if (isset($_GET['checkin'])) {
    $checkin = date('Y/m/d', strtotime($_GET['checkin']));
    $checkout = date('Y/m/d', strtotime($_GET['checkout']));
    $guests = $_GET['guests'];
    $rooms = $_GET['rooms'];
  }

$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$random = $mysql->ran(); //randomตัวเลขเพื่อใช้เก็บ sessionจองล่วงหน้า

$_SESSION['sessionTemp'] = $random;

$resultsearch = $mysql->SearchRoom($checkin,$checkout,$guests,$rooms);
$resultsearchsum = $mysql->Select_db($resultsearch);
$checksql = $mysql->numRows($resultsearch);
?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Tent's Hotel - Reservation</title>
<meta name="keywords" content="Hotel, Tent's Hotel"/>
<meta name="description" content="A luxury Tent's Hotel, Project Hotel reservation system by Chutipas Bosub. Thai-Nichi Institute of technology" />

<link href="css/magnific-popup.css" rel="stylesheet" />
<link href="css/magnific-popup_Fade.css" rel="stylesheet" />
</head>
<body>
  
<!-- navbar --> 
<?php include('include/hotel_user_nav.php');?>
<!-- end of navbar -->
<!-- section 1 -->
<section class="text-center" style="padding: 10px;">
  <div class="section-room section-reservation-page"></div>
  <div class="container img-thumb">
    <h1 class="text-custom-default">Reservation</h1>
    <p class="font-weight-light text-custom-quote">Search your time to vacation and select type of room. After that you can choose the payment rate for discount or special offer additionally.</p><hr class="hr-custom">
  </div>
</section>
<!-- end of section 1 -->

<!-- section 2 -->
<section class="text-custom-searchbar" style="padding: 10px;">
  <div class="col-12" align="center">
    <img data-src="../img/_page/step1.png" class="lazy step-img">
  </div>
  <div class="container setcenter-xs-only">
    <div class="row col-12 sendSearchUserForm" id="bookpopup" align="center">

      <?php include('include/searchRoom.php'); ?>

    </div>
  </div>
</section>
<!-- end of section 2 -->
<?php
if($checksql >= 1){
  $i = 0;
  foreach ($resultsearchsum as $readsearch){
    $sqlimage = "SELECT * FROM h_gallery
                 WHERE h_gallery_roomtypeid = '".$readsearch['h_type_id']."'
                 AND h_gallery_cover = 1
                 LIMIT 1"; 
    $resultsqlimage = $mysql->select_db($sqlimage);
    $checkimage = $mysql->numRows($sqlimage);
?>
  <br>
  <div class="card container text-custom-searchbar">
    <div class="card-body col-12 row">
      <div class="col-md-5 col-lg-3 p-0">
        <a href="#" class="image-link" data-typeid = "<?php echo $readsearch['h_type_id']; ?>">

          <?php foreach ($resultsqlimage as $readimage); ?>
          
          <img data-src="<?php if($checkimage > 0){echo $readimage['h_gallery_filename'];}
                          else{ echo "../system/upload_hotel/404.png";} ?>"
               id="#" class="rounded im lazy" style="max-width: 100%" >
        </a>
      </div>

      <div class="d-none d-sm-block col-md-7 col-lg-6">
        <div class="col-12">
          <span style="color: #B79C58"><b><?php echo $readsearch['h_type_name']; ?></b></span><br>
          <p class="font-detail-find"><?php echo $readsearch['h_type_desc']; ?></p>
        </div>
        <div class="d-lg-block d-none col-12" style="top: 10px;">
          <i class="fas fa-male" style="font-size: 30px;"></i>&nbsp;<?php echo $readsearch['h_type_capacity']; ?>
          &nbsp;&nbsp;
            <img src="<?php if($readsearch['h_type_bed_image'] != ""){echo $readsearch['h_type_bed_image'];}
            else{echo "../system/upload_icon/404.png";}  ?>" alt="" height="30px">
          <?php
            echo $readsearch['h_type_bed_name'];
           ?>
        </div>
      </div>

      <!-- for xs room desc -->
      <div class="d-block d-sm-none col-md-7 col-lg-6">
        <div class="col-12">
          <div class="col-12" style="color: #B79C58; text-align: center; margin-top: 10px;">
            <b><?php echo $readsearch['h_type_name']; ?></b>
          </div>
          <div class="col-12 room-desc-xs" id="summary">
            <p class="collapse" id="collapseSummary-<?php echo $i?>">
              <?php echo $readsearch['h_type_desc']; ?>
            </p>
            <b><a class="collapsed" data-toggle="collapse" href="#collapseSummary-<?php echo $i?>" aria-expanded="false" aria-controls="collapseSummary"></a></b>
          </div>
        </div>
      </div>
      <!-- /for xs room desc -->

      <div class="d-block d-lg-none col-12 setcenter-xs-only unset-top-xs" style="top: 10px;">
        <i class="fas fa-male" style="font-size: 30px;"></i>&nbsp;<?php echo $readsearch['h_type_capacity']; ?>
        &nbsp;&nbsp;
          <img src="<?php if($readsearch['h_type_bed_image'] != ""){echo $readsearch['h_type_bed_image'];}
          else{echo "../system/upload_icon/404.png";}  ?>" alt="" height="30px">
        <?php
          echo $readsearch['h_type_bed_name'];
         ?>
      </div>

      <div class="col-md-12 col-lg-3 border-left-custom price-find">
        <div>
          <span style="font-size: 2em;"><?php echo number_format($readsearch['h_type_price']) ?></span style="font-size: 1em;"><span>&nbsp;THB&nbsp;/&nbsp;Night</span><hr>

          <form method="POST" action="system_hotel_rate.php" id="type-<?php echo $readsearch['h_type_id'] ?>">
            <span hidden>
              <input type="text" name="checkin" value="<?php echo $checkin ?>">
              <input type="text" name="checkout" value="<?php echo $checkout ?>">
              <input type="text" name="typeid" value="<?php echo $readsearch['h_type_id'] ?>">
              <input type="text" name="type" value="<?php echo $readsearch['h_type_name'] ?>">
              <input type="text" name="guests" value="<?php echo $guests ?>">
              <input type="text" name="session" value="<?php echo $random ?>">
              <input type="text" name="capacity" value="<?php echo $readsearch['h_type_capacity'] ?>">
              <input type="text" name="rooms" value="<?php echo $rooms ?>">
            </span>
            <input type="submit" id="countdownbtn" class="btn button-dark .countdownbtn" value="SELECT ROOM" 
            <?php if($readsearch['sumOfRoomByType'] < $rooms){ echo "disabled"; } ?>><br>
            <?php if($readsearch['sumOfRoomByType'] < $rooms){
              echo "<h6 style=\"color: red;\">Only ".$readsearch['sumOfRoomByType']." rooms left</h6>";
             } ?>
          </form>
        </div>
      </div>
    </div>
</div>

<?php
  $i++;
  }
}else{?>
  <br>
  <div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-auto">
          <div class="alert alert-warning shadow-sm rounded" role="alert" align="left" style="font-size: 20px;"><i class="fas fa-exclamation-circle"></i>&nbsp;<span>The number of persons traveling for your stay exceeds the capacity of the room type selected.<br>&nbsp;&nbsp;&nbsp;&nbsp;Please select a different room type or increase the number of rooms.</span>
          </div>
        </div>
    </div>
</div>
<?php
}
?>
<br>
<!-- footer -->
<?php include("include/footer.php"); ?>
<!-- end of footer -->
<?php include("include/script.html"); ?>
<script src="js/jquery.magnific-popup.js"/></script>
<script type="text/javascript">
  //datepicker_admin
  $('.input-daterange').datepicker({
    //container: FormRequestClass,
    //format: "dd/M/yyyy",
    startDate: new Date(),
    numberOfMonths: 1,
    format: 'mm/dd/yyyy',
    clearBtn: true,
    todayHighlight: true,
    orientation: "top auto",
    autoclose: true

  });

  $('#checkin').each(function () {
    $(this).on('changeDate', function(e) {
      CheckIn = $(this).datepicker('getDate');
      CheckOut = moment(CheckIn).add(1, 'day').toDate();
      $('#checkout').datepicker('update', CheckOut).focus();
      $('#checkout').datepicker('setStartDate',CheckOut);
    });
  });
</script>
<script>
      (function() {
        function logElementEvent(eventName, element) {
          console.log(
            eventName,
            element.getAttribute("data-src")
          );
        }
        var callback_enter = function(element) {
          logElementEvent("🔑 ENTERED", element);
        };
        var callback_exit = function(element) {
          logElementEvent("🚪 EXITED", element);
        };
        var callback_reveal = function(element) {
          logElementEvent("👁️ REVEALED", element);
        };
        var callback_loaded = function(element) {
          logElementEvent("👍 LOADED", element);
        };
        var callback_error = function(element) {
          logElementEvent("💀 ERROR", element);
          element.src =
            "https://via.placeholder.com/440x560/?text=Error+Placeholder";
        };
        var callback_finish = function() {
          logElementEvent("✔️ FINISHED", document.documentElement);
        };
        var ll = new LazyLoad({
          elements_selector: ".lazy",
          // Assign the callbacks defined above
          callback_enter: callback_enter,
          callback_exit: callback_exit,
          callback_reveal: callback_reveal,
          callback_loaded: callback_loaded,
          callback_error: callback_error,
          callback_finish: callback_finish
        });
      })();
</script>
</body>

<script type="text/javascript">
  

  $(document).ready(function() {

    //Check rooms & guests url injection
    var guests_check = <?php echo $guests; ?>;
    var rooms_check = <?php echo $rooms; ?>;
    var checkin_check = $('#checkin').val();
    var checkout_check = $('#checkout').val();
    if(guests_check < rooms_check){
      document.location.href = 'http://myport.local/system_hotel_user_find.php?checkin='+checkin_check.toString()+'&checkout='+checkout_check.toString()+'&guests='+<?php echo $rooms ?>+'&rooms='+<?php echo $rooms ?>+'';
    }

    //Popup MagneficPopup plugin
    $('.image-link').on('click',function(event){
      event.preventDefault();
      var typeid = $(this).data('typeid');

      $.ajax({
          url: "../system/cmd.php",
          dataType: "json",
          data:{
            command: "findimage",
            typeid: typeid
          },
          type: "POST",
          success:function(data){
            $.magnificPopup.open({

              items: data,

              gallery: {
                enabled: true
              },

              type: 'image',
              mainClass: 'mfp-fade',
              removalDelay: 300,
              callbacks:{
                change: function() {
                    if (this.isOpen) {
                        this.wrap.addClass('mfp-open');
                    }
                }
              }

              });
          },
        error:function (){
          alert('error');
        }
      });

    });

    $( ".im" ).mouseenter(function() {

          $(this).animate({
            opacity: 0.50
          }, 400, function() {
            // Animation complete.
          });
    });

    $( ".im" ).mouseleave(function() {

          $(this).animate({
            opacity: 1
          }, 400, function() {
            // Animation complete.
          });
    });

  });

</script>
</html>