<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
include("include/mytools.html");
session_start();
// if($_SESSION['getPermission'] == ""){
//     header("Location:index.php");
//   }
//$UID = $_SESSION['getId'];

include("system/class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

///////////////////////image profile////////
// $result = $mysql->UIDProfile($UID);       //
// foreach ($result as $read);               //
///////////////////////////////////////////

$sql = "SELECT * FROM h_type
        JOIN h_type_bed
        ON h_type_bed_id = h_type_bed";
$result = $mysql->Select_db($sql);
$count = $mysql->numRows($sql);
?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Tent's Hotel - Our room</title>
<meta name="keywords" content="Hotel, Tent's Hotel"/>
<meta name="description" content="A luxury Tent's Hotel, Project Hotel reservation system by Chutipas Bosub. Thai-Nichi Institute of technology" />
</head>
<body>
  
<!-- navbar --> 
  <?php include('include/hotel_user_nav.php'); ?>
<!-- end of navbar -->
  
<!-- section 4 -->
<section class="text-center" style="padding: 10px;">
  <div class="section-room section-room-page"></div>
  <div class="container img-thumb">
    <h1 class="text-custom-default">Rooms</h1>
    <p class="font-weight-light text-custom-quote">Our <?php echo $count ?> guest rooms and suites boast a modern, urban design. Each room has been carefully soundproofed and decorated to offer you a genuine hideaway overlooking the Mediterranean Sea or the inspiring Merce Street in the Gothic Quarter. Our rooms come with the latest state-of-the-art technology, including a complimentary Wi-Fi, smart HD TV and wireless sound system.</p><hr class="hr-custom">
  </div>
  </section>
<!-- end of section 4 -->

<!-- section 5 -->
<section class="text-center" style="padding: 10px; margin-bottom: 20px;">
  <div class="container">
    <div class="row">
        <?php $num = 0; foreach ($result as $read) { 
            $sqlimage = "SELECT * FROM h_gallery
                         WHERE h_gallery_roomtypeid = '".$read['h_type_id']."'
                         AND h_gallery_cover = 1
                         LIMIT 1"; 
            $resultsqlimage = $mysql->Select_db_one($sqlimage);
            $checkimage = $mysql->numRows($sqlimage);
        ?>

        <?php if($num == 0){ ?>
          <div class="col-12 row card-center" style="margin: 0px; padding: 0px; margin-bottom: 20px;">
        <?php } $num++; ?>

          <div class="col-xs-custom-12 col-8 col-md-10 col-lg-3 card card-custom-thumb">

            <img data-src="<?php if($checkimage > 0){echo $resultsqlimage['h_gallery_filename'];}
                          else{ echo "../system/upload_hotel/404.png";} ?>" class="lazy img-thumb-query" />

            <h5>
              <a href="#" data-id="<?php echo $read['h_type_id'] ?>" class="on_white">
                <?php echo $read['h_type_name'] ?>
                <h6><span style="font-size: smaller; color: black;"><i class="fas fa-eye"></i> view</span></h6>
              </a>
          </h5>

            <div style="margin-bottom: 10px;"><i class="fas fa-male" style="font-size: 30px;"></i> <?php echo $read['h_type_capacity'] ?>

            &nbsp;&nbsp;&nbsp;

             <img class="lazy" data-src="<?php if($read['h_type_bed_image'] != ""){echo $read['h_type_bed_image'];}
              else{echo "../system/upload_icon/404.png";}  ?>" alt="" height="30px"> <?php echo $read['h_type_bed_name'] ?> 
              <span style="font-size: 10px;">(x<?php echo $read['h_type_bedtotal']?>)</span></div>
            <div class="underline-footer"></div>
          </div>

          <?php if($num == 3){ $num = 0; ?>
            </div>
          <?php } ?>
        <?php } ?>
    </div>
  </div>
  </section>
<!-- end of section 5 -->

<!-- footer -->
<?php include("include/footer.php"); ?>
<?php include("include/script.html"); ?>
<!-- end of footer -->

<div class="modal fade room_detail" id="detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div id="dialog_lg" class="modal-dialog modal-dialog-centered mw-75 modal-responsive" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-info"></i>&nbsp;Room Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-12 detail">
          </div>
        </div>
    </div>
  </div>
</div>

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

  $(document).ready(function () {
    $.removeCookie('popup');
    $.removeCookie('remember_guests');
    $.removeCookie('remember_rooms');
  });
  
</script>
</html>