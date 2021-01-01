<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();

if (isset($_GET['checkin'])) {
  $checkin = date('Y/m/d', strtotime($_GET['checkin']));
  $checkout = date('Y/m/d', strtotime($_GET['checkout']));
  $guests = $_GET['guests'];
  $rooms = $_GET['rooms'];
}

include("system/class_db.php");
?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Tent's Hotel - Home</title>
<meta name="keywords" content="Hotel, Tent's Hotel"/>
<meta name="description" content="A luxury Tent's Hotel, Project Hotel reservation system by Chutipas Bosub. Thai-Nichi Institute of technology" />
<?php include("include/mytools.html"); ?>
</head>
<body>
  
<!-- navbar --> 
  <?php include('include/hotel_user_nav.php'); ?>
<!-- end of navbar -->
  
<!-- section 1 -->
<section class="" style="background-color: #B79C58;">
  <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner thumb-post">
    <div class="carousel-item active">
      <img data-src="/img/_slide/1.jpg?v=<?php echo VERSION_NUMBER;?>" class="lazy d-block w-100" alt="..."
        data-srcset="/img/_slide/1_720.jpg?v=<?php echo VERSION_NUMBER;?> 720w, /img/_slide/1_1280.jpg?v=<?php echo VERSION_NUMBER;?> 1280w, /img/_slide/1.jpg?v=<?php echo VERSION_NUMBER;?> 1x">
    </div>
    <div class="carousel-item">
      <img data-src="/img/_slide/2.jpg?v=<?php echo VERSION_NUMBER;?>" class="lazy d-block w-100" alt="..."
        data-srcset="
        /img/_slide/2_720.jpg?v=<?php echo VERSION_NUMBER;?> 720w, /img/_slide/2_1280.jpg?v=<?php echo VERSION_NUMBER;?> 1280w, /img/_slide/2.jpg?v=<?php echo VERSION_NUMBER;?> 1x">
    </div>
    <div class="carousel-item">
      <img data-src="/img/_slide/3.jpg?v=<?php echo VERSION_NUMBER;?>" class="lazy d-block w-100" alt="..."
        data-srcset="/img/_slide/3_720.jpg?v=<?php echo VERSION_NUMBER;?> 720w, /img/_slide/3_1280.jpg?v=<?php echo VERSION_NUMBER;?> 1280w, /img/_slide/3.jpg?v=<?php echo VERSION_NUMBER;?> 1x">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
  </section>
<!-- end of section 1 -->
<!-- section 2 -->
<section class="sec1" style="padding-top: 10px; padding-bottom: 20px; background-color: #DCCCA3;">
  <div class="container" align="center">
    <br>

    <div class="col-12 d-none d-md-block" id="bookpopup" align="center">
      <?php include('include/searchRoom.php'); ?>
    </div>

    <div class="col-12 d-md-none d-block" id="bookpopup" align="center">
      <a href="system_hotel_user_find.php"><button class="btn button-dark text-custom-searchbar">Book now</button></a>
    </div>

  </div>
  </section>
<!-- end of section 2 -->

<!-- section 3 -->
<section class="text-center contrainer-welcome">
  <div class="container">
    <div class="row" style="justify-content: center;">
      <div class="d-none d-md-block col-1 text-custom-border-welcome align-self-center justify-content-center">{</div>
      <div class="col-10">
      <h1 class="text-custom-default" style="color: white;">Welcome to the Tent's Hotel</h1>
      <hr class="hr-custom-welcome">
      <p class="typewriter text-custom-welcome">“ Experience Modern Luxury ”</p>
      </div>
      <div class="d-none d-md-block col-1 text-custom-border-welcome align-self-center justify-content-center">}</div>
    </div>
  </div>
  </section>
<!-- end of section 3 -->

<!-- section 4 -->
<section class="text-center" style="padding: 10px;">
  <div class="section-room"></div>
  <div class="container img-thumb">
    <h1 class="text-custom-default">Rooms</h1>
    <p class="font-weight-light text-custom-quote">“ We have all types of rooms for your relaxation ”</p>
    <div class="col-12 row card-center" style="margin: 0px; padding: 0px;">
      <div class="col-md-12 col-lg-3 card card-custom-thumb">
        <img data-src="../img/_page/sroom.jpg?v=<?php echo VERSION_NUMBER;?>" class="lazy" />
        <h4 class="text-custom-default">Single room</h4>
        <p>For anyone who wants to relax alone. You can rest worry-free with reasonable price. Entertain yourself with flat screen tv or stay in touch from your ergonomic work station.</p>
      </div>
      <div class="col-md-12 col-lg-3 card card-custom-thumb">
        <img data-src="../img/_page/mroom.jpeg?v=<?php echo VERSION_NUMBER;?>" class="lazy"
          data-srcset="../img/_page/mroom_720.jpg?v=<?php echo VERSION_NUMBER;?> 720w, ../img/_page/mroom.jpeg?v=<?php echo VERSION_NUMBER;?> 1x"/>
        <h4 class="text-custom-default">Medium room</h4>
        <p>Stay comfortable in our spacious 33 sqm room with free high speed internet access and 21 inch lcd tv lounge at the sofa & cofee table area. Tea coffee maker separate bathroom washstand and toilet for your convenience.</p>
      </div>
      <div class="col-md-12 col-lg-3 card card-custom-thumb">
        <img data-src="../img/_page/house.jpeg?v=<?php echo VERSION_NUMBER;?>" class="lazy" 
          data-srcset="../img/_page/house_720.jpg?v=<?php echo VERSION_NUMBER;?> 720w, ../img/_page/house.jpeg?v=<?php echo VERSION_NUMBER;?> 1x"/>
        <h4 class="text-custom-default">King La Residence</h4>
        <p>The spacious, 220 sqm. Elegant designed villa is spread over two bedrooms of indulgent comfort. Garden and an inviting infinity pool with complemented your pvt butler and privilege of Club benefits.</p>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="col-12" align="center">
      <div class="col-2 underline-btn"></div>
      <div class="col-md-12 col-lg-2"><h3><a href="system_hotel_rooms.php" class="text-custom-default">View more</a></h3></div>
    </div>
  </div>
  </section>
<!-- end of section 4 -->

<!-- section 5 -->
<section class="text-center" style="padding: 10px;">
  <div class="section-room section-restaurant"></div>
  <div class="blackdrop"></div>
  <div class="blackdrop blackdrop-2"></div>
  <div class="container">
    <div class="row">
      <div class="col-12 row" align="right" style="padding-top: 20px; padding-bottom: 20px;">
        <div class="col-12 col-md-4 col-lg-5" align="center" style="color: white;">
          <div id="food-slide" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img class="d-block w-100 lazy" data-src="../img/_page/food1.png?v=<?php echo VERSION_NUMBER;?>">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100 lazy" data-src="../img/_page/food2.png?v=<?php echo VERSION_NUMBER;?>">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100 lazy" data-src="../img/_page/food3.png?v=<?php echo VERSION_NUMBER;?>">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100 lazy" data-src="../img/_page/food4.png?v=<?php echo VERSION_NUMBER;?>">
              </div>
            </div>
            <a class="carousel-control-prev" href="#food-slide" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#food-slide" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        </div>
        <div class="col-md-8 col-lg-7" align="right" style="color: white; padding-right: 0px;">
          <h1 class="text-custom-default">RESTAURANTS, BARS & CLUB</h1>
          <p class="font-weight-light text-custom-quote">“ Have everything you need ”</p>
          <p>Here has everything Including Thai, Japanese, Italian,<br class="d-none d-sm-block">
            Indian, Chinese, Korean food, and others.<br class="d-none d-sm-block">
          You can also enjoy cocktails with your friends at any time.<br class="d-none d-sm-block">
        And of course, relax with live music every night.</p>
        </div>
      </div>
    </div>
  </div>
  </section>
<!-- end of section 5 -->

<!-- Book sticky -->
<div class="fixed-bottom-custom">
  <div class="col-12 sticky-booking-panel">
    <div class="row">
      <div class="col-2 justify-content-center align-self-center">
        <a href="#book-sticky" class="book-sticky-icon"><i class="icon-sticky fas fa-chevron-right" data-toggle="collapse" data-target="#book-sticky" aria-expanded="false" aria-controls="collapseExample"></i></a>
      </div>
      <div class="col-10" align="center">
        <div class="col-12 collapse" id="book-sticky" align="center" style="padding: 15px;">
          <div style="margin-bottom: 10px;" class="text-custom-quote font-small-xs">Are you looking<br>for a room?</div>
          <a href="../system_hotel_user_find.php"><button class="btn button-dark text-custom-default font-small-xs" type="button">Book now</button></a>
        </div>

      </div>
    </div>
  </div>
</div>
<!-- end of book sticky -->

<!-- footer -->
<?php include("include/footer.php"); ?>
<?php include("include/script.html"); ?>
<!-- end of footer -->
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
<script type="text/javascript">

  $(document).ready(function () {
    $.removeCookie('popup');
    $.removeCookie('remember_guests');
    $.removeCookie('remember_rooms');

    //Book-sticky collapse
    $("#book-sticky").hide();
    $(".icon-sticky").addClass('fa-bookmark').removeClass('fa-chevron-right');
    $(".fa-bookmark").css({'font-size':'30','padding-bottom':'20'});

    $('#book-sticky').on('shown.bs.collapse', function(e) {
      $("#book-sticky").addClass('show-by-user');
      $("#book-sticky").show();
      $(".icon-sticky").addClass('fa-chevron-right').removeClass('fa-chevron-left');
      $(".fa-chevron-right").css({'font-size':'16','padding-bottom':'0'});
    });

    $('#book-sticky').on('hidden.bs.collapse', function() {
      $("#book-sticky").addClass('hide-by-user');
      $("#book-sticky").hide();
      $(".icon-sticky").addClass('fa-bookmark').removeClass('fa-chevron-right');
      $(".fa-bookmark").css({'font-size':'30','padding-bottom':'20'});
    });

    $(document).on('scroll', function() {
      if($(this).scrollTop()<$('.sec1').position().top && !$("#book-sticky").hasClass('show-by-user')){
        $("#book-sticky").removeClass('show');
        $("#book-sticky").hide();
        $(".icon-sticky").addClass('fa-bookmark').removeClass('fa-chevron-right');
        $(".fa-bookmark").css({'font-size':'30','padding-bottom':'20'});
      }
      else if($(this).scrollTop()>=$('.sec1').position().top && !$("#book-sticky").hasClass('hide-by-user')){
        $("#book-sticky").addClass('show');
        $("#book-sticky").show();
        $(".icon-sticky").addClass('fa-chevron-right').removeClass('fa-chevron-left');
        $(".fa-chevron-right").css({'font-size':'16','padding-bottom':'0'});
      }
    });

    $('.sticky-booking-panel').hover(function(){
      $(this).stop().animate({"opacity": 1});
      },function(){
        $(this).stop().animate({"opacity": 0.5});
    });
  });
</script>
<!-- Lazy load Script -->
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


</html>