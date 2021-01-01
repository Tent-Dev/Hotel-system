<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
include("include/mytools.html");
session_start();

if(empty($_SESSION['New_booking_Customer'])){ //สำหรับเช็คว่า ผู้ใช้ที่อยู่หน้านี้ เพิ่งเข้ามา หรือจะกดรีเฟรชเพจ
  $_SESSION['New_booking_Customer'] = '0';
}

include("system/class_db.php");

$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$guests = $_POST['guests'];
$typename = $_POST['type'];
$typeid = $_POST['typeid'];
$capacity = $_POST['capacity'];
$session = $_POST['session'];
$rooms = $_POST['rooms'];

$dteStart = new DateTime($checkin); 
$dteEnd   = new DateTime($checkout);
$dteDiff  = $dteStart->diff($dteEnd);
$sumdate = $dteDiff->format("%a");

$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

//////////////////check session/////////////////

$sqlsession = "SELECT h_trans_codesession
               FROM h_transaction
               WHERE h_trans_codesession = '$session' ";

$checksession = $mysql->numRows($sqlsession);

/////////////////////////////////////////////////

  if($_SESSION['New_booking_Customer'] == $session){ //ถ้าผู้ใช้กดรีเฟรช ให้เรียกข้อมูลที่เลือกไว้มาแสดง

    $sqlsearch = $mysql->findRoomOld($typeid,$rooms,$session);


  } else{ //ถ้าเป็นผู้ใช้ที่เพิ่งกดเข้ามา ก็ให้เลือกห้องที่ว่าง

    $sqlsearch = $mysql->findRoom($typeid,$checkin,$checkout,$rooms);
    foreach ($sqlsearch as $readsearch){
      $arr = array (
              "h_trans_roomid"=> $readsearch['h_room_id'],
              "h_trans_customerid"=> "1",
              "h_trans_checkindate"=> $checkin,
              "h_trans_checkoutdate"=> $checkout,
              "h_trans_codesession"=> $session
              );
      //var_dump($arr);
      $mysql->Insert_db($arr,"h_transaction");
    }
    $_SESSION['New_booking_Customer'] = $session; //จากนั้นเซ็ต Session ให้เป็นผู้ใช้ที่เข้ามาดูแล้ว
  }

$now = date("Y-m-d");

$sqlrate = "SELECT * FROM h_rate
            JOIN h_mix_ratetype
            ON h_mix_ratetype.h_mix_ratetype_rateid = h_rate.h_rate_id
            WHERE h_mix_ratetype.h_mix_ratetype_typeid = $typeid AND h_rate.h_rate_statustouser = 1
            AND (h_rate_dateset = 0 OR (h_rate_dateset = 1
                                    AND '$now' >= h_rate_datestart AND '$now' <= h_rate_dateend))
            AND h_mix_ratetype.h_mix_ratetype_typeid = $typeid 
            AND h_rate.h_rate_statustouser = 1
            GROUP BY h_rate_id
            ORDER BY h_rate_id ASC";

$resultrate = $mysql->Select_db($sqlrate);
$checksqlrate = $mysql->numRows($sqlrate);
foreach ($sqlsearch as $readsearch);

//////////////////Process Price///////////////////////
$totalpayment = $readsearch['h_type_price']*$sumdate*$rooms;
//////////////////////////////////////////////////////

//$totalpaymentdiscount = ($readsearch['h_type_price']*$sumdate*$rooms)-700;

$sqlimage = "SELECT * FROM h_gallery
             WHERE h_gallery_roomtypeid = '".$typeid."'
             LIMIT 1";
$resultsqlimage = $mysql->select_db($sqlimage);
$checkimage = $mysql->numRows($sqlimage);
foreach ($resultsqlimage as $readimage);

?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Tent's Hotel - Rate of room</title>
<meta name="keywords" content="Hotel, Tent's Hotel"/>
<meta name="description" content="A luxury Tent's Hotel, Project Hotel reservation system by Chutipas Bosub. Thai-Nichi Institute of technology" />
<style type="text/css" media="screen">
  .discount{
    color: green;
  }
</style>
<?php include("include/script.html"); ?>
<script src="/js/jquery.countdown.js" type="text/javascript" charset="utf-8"></script>
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
    <p class="font-weight-light text-custom-quote">Second, Choose the payment rate for discount or special offer additionally.</p><hr class="hr-custom">
  </div>
</section>
<!-- end of section 1 -->

<!-- section 2 -->
<section class="text-custom-searchbar" style="padding: 10px;">
  <div class="col-12" align="center">
    <img data-src="../img/_page/step2.png" class="lazy step-img">
  </div>
  <div class="container">
    <div class="row col-12 sendSearchUserForm" id="bookpopup" align="center">
      <?php include('include/searchRoom.php'); ?>
    </div>
  </div>
</section>
<!-- end of section 2 -->
  <br>
  <div class="card container text-custom-searchbar">
    <div class="card-body col-12 row">

      <div class="col-xs-custom-12 col-md-5 col-lg-3 p-0">
         <img data-src="<?php if($checkimage > 0){echo $readimage['h_gallery_filename'];}
                          else{ echo "../system/upload_hotel/404.png";} ?>"
               id="#" class="rounded-left im lazy" style="max-width: 100%" >
      </div>

      <div class="col-xs-custom-12 col-md-7 col-lg-9 font-detail-find">

        <div class="col-12 setcenter-xs-only">
          <span style="color: #B79C58"><b><?php echo $readsearch['h_type_name']
          /* ." [ ROOM: "; foreach ($sqlsearch as $readsearch){ echo($readsearch['h_room_name']." "); }echo("]")*/ ?></b></span>
        </div>

        <div class="d-sm-block d-none col-12">
          <?php echo $readsearch['h_type_desc']; ?>
        </div>

        <!-- for xs desc room -->
        <div class="d-block d-sm-none room-desc-xs col-12" id="summary">
          <p class="collapse" id="collapseSummary">
            <?php echo $readsearch['h_type_desc']; ?>
          </p>
          <b><a class="collapsed" data-toggle="collapse" href="#collapseSummary" aria-expanded="false" aria-controls="collapseSummary"></a></b>
        </div>

        <div class="col-12 setcenter-xs-only unset-top-xs" style="top: 10px;">
          <i class="fas fa-male" style="font-size: 30px;"></i>&nbsp;<?php echo $readsearch['h_type_capacity']; ?>
          &nbsp;&nbsp;
            <img src="<?php if($readsearch['h_type_bed_image'] != ""){echo $readsearch['h_type_bed_image'];}
            else{echo "../system/upload_icon/404.png";}  ?>" alt="" height="30px">
          <?php
            echo $readsearch['h_type_bed_name'];
           ?>
        </div>

      </div>
        
    </div>
  </div>
  <br>
  <!-- /////////////////////////////// -->

  <?php
  if($checksqlrate >= 1){
    foreach ($resultrate as $readrate){ ?>

      <div class="card container">
        <div class="card-body col-12 row text-custom-default">

          <div class="col-xs-custom-12 col-9 rate-name-xs">
            <div class="col-12">
              <span><h3><b><?php echo $readrate['h_rate_name'] ?></b></h3></span>
              <?php if($readrate['h_rate_dateset'] == 1){ ?>
              <h6><i class="far fa-clock"></i>
                <i style="font-size: 13px;">Time rate expires in: 
                    <span class="countdown-<?php echo $readrate['h_rate_id']; ?>"
                          data-dateend="<?php echo date('Y-m-d', strtotime($readrate['h_rate_dateend']."+1 days")); ?>">
                    </span>
                </i>
              </h6>
              <?php
               ?>
              <script type="text/javascript">
                // Countdown rate Time
                $(document).ready(function () {
                  var timevalue = $('.countdown-'+<?php echo $readrate['h_rate_id']; ?>+'').data('dateend');
                  var rateid = <?php echo $readrate['h_rate_id']; ?>;
                  time_count(timevalue,rateid);
                });
              </script>
              <?php } ?>
              <br>
              <div class="font-small-xs">
                <?php echo $readrate['h_rate_desc'] ?>
              </div>
            </div>
          </div>

          <div class="col-md-12 col-lg-3 border-left-custom price-find">
            <div>
              <h3><?php echo $sumdate  ?>&nbsp; Nights</h3>
              <h6><?php echo ($rooms." Rooms") ?></h6>

              <?php
              ///////////////////Process Price /////////////////////////////
              if($readrate['h_rate_discount'] == 0){
                $totalpayment_final = $totalpayment;
                echo ("<h2>".number_format($totalpayment_final)." THB</h2> ");
              }
              else{
                if($readrate['h_rate_discountset'] == 'Bath'){
                  $totalpayment_final = $totalpayment-$readrate['h_rate_discount'];
                  echo("<h6><s>".number_format($totalpayment).
                       "</s> THB <span class=\"discount\">(-".
                       $readrate['h_rate_discount']." THB)</span></h6><h2>".
                       number_format($totalpayment_final)." THB</h2> ");
                }
                else if($readrate['h_rate_discountset'] == 'Percent'){
                  $totalpayment_final = $totalpayment-(($readrate['h_rate_discount']/100)*$totalpayment);
                  echo("<h6><s>".number_format($totalpayment).
                       "</s> THB <span class=\"discount\">(-".
                       $readrate['h_rate_discount']."%)</span></h6><h2>".
                       number_format($totalpayment_final)." THB</h2> ");
                }
              }
              ?>
              <hr>
              <form method="POST" action="system_hotel_payment.php" id="rate-<?php echo $readrate['h_rate_id'] ?>">
                <span hidden>
                  <input type="text" name="type" value="<?php echo $readsearch['h_type_name'] ?>">
                  <input type="text" name="checkin" value="<?php echo $checkin ?>">
                  <input type="text" name="checkout" value="<?php echo $checkout ?>">
                  <input type="text" name="typeid" value="<?php echo $readsearch['h_type_id'] ?>">
                  <input type="text" name="guests" value="<?php echo $guests ?>">
                  <input type="text" name="session" value="<?php echo $session ?>">
                  <input type="text" name="capacity" value="<?php echo $readsearch['h_type_capacity'] ?>">
                  <input type="text" name="rooms" value="<?php echo $rooms ?>">
                  <input type="text" name="sumdate" value="<?php echo $sumdate ?>">
                  <input type="text" name="total" value="<?php echo $totalpayment_final ?>">
                  <input type="text" name="rate" value="<?php echo $readrate['h_rate_name'] ?>">
                  <input type="text" name="rateid" value="<?php echo $readrate['h_rate_id'] ?>">
                  <input type="text" name="ratedeposit" value="<?php echo $readrate['h_rate_deposit'] ?>">
                </span>
                
                <input type="submit" id="btn-<?php echo $readrate['h_rate_id'] ?>" class="btn button-dark" value="SELECT RATE">
              </form>
          </div> 
        </div>
      </div>
    </div>
    <br>
  <?php }
  }else{?>
    <br>
    <div class="container">
      <div class="row justify-content-md-center">
          <div class="col-md-auto">
            <div class="alert alert-warning shadow-sm rounded" role="alert" align="left" style="font-size: 20px;"><i class="fas fa-exclamation-circle"></i>&nbsp;<span>Sorry. Rate of this room is being edited. Please come back again.<br></span>
            </div>
          </div>
      </div>
    </div>
  <?php } ?>

<br>
<!-- footer -->
<?php include("include/footer.php"); ?>
<!-- end of footer -->

<!-- Warning Session Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        Warning
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        You have 5 minutes to booking.<br>
        After that. Your session will expried.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
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
  <script type="text/javascript">
    $(document).ready(function () {

      //เช็ค Session Timeout alert
      $(function() {
        //ตั้ง popup เตือนว่ามีเวลาทำรายการ 5 นาที
        if ($ .cookie("popup") == null) {
          $("#myModal").modal('show');
          $ .cookie("popup", "1");
        }
      });
      set = setInterval(refresh_div, 1000);
    });

    function refresh_div() {
        jQuery.ajax({
              url:'system/refresh.php',
              method:"POST",
              type: 'json',
              success:function(results) {
                  $.each(JSON.parse(results), function(key,value){
                    //console.log('Time status: '+value);
                    if(value == 0){
                      $.removeCookie('popup');
                      alert('Timeout');
                      console.log('Timeout');
                      clearInterval(set); //ยกเลิกการเซตเวลา
                      document.location.href = 'system_hotel_user.php';
                    }
                  });
              }
        });
    }

    function time_count(timevalue,rateid){
      //Countdown Rate
      var timevalues = timevalue.toString();

      $('.countdown-'+rateid+'').countdown(timevalues, function(event) {
        $(this).html(event.strftime('%-w weeks %-d days %H:%M:%S'));

      }).on('finish.countdown', function() { 
          $(this).html('Timeout');
          $('#btn-'+rateid+'').prop('disabled',true);
          $('#btn-'+rateid+'').val('Timeout');
        });
    }
  </script>
</body>


</html>