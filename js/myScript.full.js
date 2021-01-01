$( document ).ready(function() {
  $(".load").hide();
  $('img[src="https://cdn.000webhost.com/000webhost/logo/footer-powered-by-000webhost-white2.png"]').prop('hidden',true);

  //เช็คคุกกี้ เลือก  select option  ค้นหาห้องพัก
  if($.cookie('remember_guests') != null) {
    $('.guests').val($.cookie('remember_guests'));
  }
  // when a new option is selected this is triggered
  $('.guests').change(function() {
    // new cookie is set when the option is changed
    $.cookie('remember_guests', $('.guests option:selected').val(), { expires: 90, path: '/'});
  });
  if($.cookie('remember_rooms') != null) {
    $('.rooms').val($.cookie('remember_rooms'));
  }
  // when a new option is selected this is triggered
  $('.rooms').change(function() {
    // new cookie is set when the option is changed
    $.cookie('remember_rooms', $('.rooms option:selected').val(), { expires: 90, path: '/'});
  });

  //ส่งข้อมูลbookingไปให้ insert.php
  $("#sendBookingForm").submit(function(event){
    event.preventDefault();
    $(".input-text-bx").attr("disabled","disabled");
    $("#submitsignup").attr("hidden",true);
    $("#confirmbook").attr("hidden",false);
    $("#edit").attr("hidden",false);
  });

  //Edit booking information
  $( document ).on("click","#edit",function(event){
      event.preventDefault();
      $(".input-text-bx").attr("disabled",false);
      $("#confirmbook").attr("hidden",true);
      $("#submitsignup").attr("hidden",false);
      $("#edit").attr("hidden",true);
    });

  //Confirm Booking
  $( document ).on("click","#confirmbook",function(event){
    event.preventDefault();
    $(".load").show();
      jQuery.ajax({
      url: "../system/cmd.php",
      data:{
        command: "confirmBooking",
        session: $("#session").val(),
        special: $("#special").val(),
        guest_paymenttype: $("#paymenttype2").val(),
        guest_total: $("#total").val(),
        guest_email: $("#email").val(),
        guest_firstname: $("#Fname").val(),
        guest_lastname: $("#Lname").val(),
        guest_region: $("#region").val(),
        guest_address: $("#address").val(),
        guest_city: $("#city").val(),
        guest_postal: $("#postal").val(),
        guest_phone: $("#phone").val(),
        guest_special: $("#special").val(),
        guest_rate: $("#rate").val(),
        guest_rateid: $("#rateid").val(),
        guest_special: $("#special").val(),
        guest_codesession: $("#session").val(),
        checkin: $("#checkin").val(),
        checkout: $("#checkout").val(),
        guests: $("#guests").val(),
        rooms: $("#rooms").val(),
        typename : $("#typename").val()

      },
      type: "POST",
      success:function(data){
        $("#sendBookingForm").html(data); //แสดงสถานะ
        $(".load").hide();
      },
      error:function (){ alert('a'); }
      });
  });

  //select guests and rooms
  $(document).on('change','.rooms', function(event) {
    event.preventDefault();
    var guestsSelect = parseInt($('.guests').val());
    var roomsSelect = parseInt($('.rooms').val());

    if(roomsSelect > guestsSelect){
      alert('You can\'t select rooms more guests');
      $('.rooms').val(guestsSelect);
    }
  });

  $(document).on('change','.guests', function(event) {
    event.preventDefault();
    var guestsSelect = parseInt($('.guests').val());
    var roomsSelect = parseInt($('.rooms').val());

    if(roomsSelect > guestsSelect){
      alert('You can\'t select rooms more guests');
      $('.rooms').val(guestsSelect);
    }
  });

  //Paymenttype input
  $(document).on('change','.pay', function(event) {
    event.preventDefault();
    var pay = parseInt($('.pay').val());
    var input = parseInt($('.pay').val());
      $('.inp').val(pay);
  });

  //ส่งข้อมูล login
  $("#sendLoginForm").submit(function(event){
      $('#loginSuccess').html('<div class="col-12" style="text-align: center; padding: 20px;"><h5>logging in</h5><h5><i class="fas fa-spinner fa-spin"></i></h5></div>');
      event.preventDefault();
      jQuery.ajax({
      url: "../system/cmd.php",
      data:{
        command: "login",
        user: $("#userlogin").val(),
        pass: $("#passlogin").val(),
      },
      type: "POST",
      success:function(data){
      $('#loginSuccess').html('');
      data = JSON.parse(data);
      console.log(data.check);
      if(data.check == 1){
        document.location.href = 'system_hotel_admin_index.php';
      }else{
        $('#sendLoginForm').trigger("reset");
        $('#loginSuccess').html('<span style="color:red;">บัญชีผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</span>');
      }

      },
      error:function (){ alert('oop');}
      });
    });

  //logout
  $( document ).on("click",".logout",function(event){
      event.preventDefault();
      jQuery.ajax({
        url: "../system/cmd.php",
        data:{
          command: "logout"
        },
        type: "POST",
        success:function(data){
        $(".logoutSuccess").html(data);//แสดงสถานะ

        },
        error:function (){ alert('oop');}
        });
    });

  //Popup Room infomation
  $( document ).on("click",".on_white",function(event){
      event.preventDefault();
      var id = $(this).data('id');
      var ref = $('.room_detail').attr('id', 'detail-'+id);
      console.log(id);
      generate_modal_room_detail(ref,id)
  });

  //Image gallery click and change
  $( document ).on("click",".thumbnail",function(event){
      event.preventDefault();
      var target = $(this).attr('src');
      $('.show-img').fadeOut("fast").attr('src',target).fadeIn("fast");
      $('.thumbnail').removeClass('active-img');
      $(this).addClass('active-img');
  });

});

function generate_modal_room_detail(ref,id){
  $.ajax({
    url: "../system/cmd.php",
    data:{
      command: "search_room_info_user",
      id:id
    },
    type: "POST",
    success:function(data){
      data = JSON.parse(data);
      var html = '<div class="col-12">'+
                    '<div class="row">'+

                      '<div class="col-md-12 col-lg-6">'+
                        '<div class="col-12">'+
                          '<div>'+
                            '<img class="show-img" width="100%">'+
                          '</div>'+
                        '</div><br>'+
                        '<div class="col-12 setcenter-xs-only">';
                        if(data[0].imagegroup === null){
                          html += '<a href="#" class="link-img"><img src="../system/upload_hotel/404.png" class="lazy thumbnail" width="100" height="70"></a>';
                        }
                        else{
                          $.each(data[0].imagegroup,function(key, value){
                            html += '<a href="#" class="link-img"><img src="'+value.image+'" class="lazy thumbnail" width="100" height="70"></a>';
                          });
                        }

          html +=       '</div>'+
                      '</div>'+

                      '<div class="col-md-12 col-lg-6">'+
                        '<div class="col-12" align="center">'+
                          '<h3 class="text-custom-default" style="color:#B79C58;">'+data[0].h_type_name+'</h3><hr class="hr-custom hr-custom-gallery">'+
                        '</div>'+
                        '<div class="col-12 text-custom-detail">'+
                          '<p>'+data[0].h_type_desc+'</p>'+
                        '</div>'+
                        '<div class="col-12 text-custom-detail">'+
                          '<i class="fas fa-male" style="font-size: 30px;"></i> '+data[0].h_type_capacity+'&nbsp;&nbsp;&nbsp; <img height="30px" src="'+data[0].h_type_bed_image+'"> '+data[0].h_type_bed+' (x'+data[0].h_type_bedtotal+')'+
                        '</div><br>'+
                        '<div class="col-12">'+
                          '<table class="table-custom text-custom-detail">'+
                            '<tr>'+
                              '<td><i class="fas fa-wifi"></i></td>'+
                              '<td>Wifi</td>'+
                            '</tr>'+
                            '<tr>'+
                              '<td><i class="fas fa-tv"></i></td>'+
                              '<td>Cable TV</td>'+
                            '</tr>'+
                            '<tr>'+
                              '<td><i class="fas fa-phone-volume"></i></td>'+
                              '<td>Telephone</td>'+
                            '</tr>'+
                            '<tr>'+
                              '<td><i class="fas fa-wind"></i></td>'+
                              '<td>A/C</td>'+
                            '</tr>'+
                            '<tr>'+
                              '<td><i class="fas fa-bath"></i></td>'+
                              '<td>Bath and Shower</td>'+
                            '</tr>'+
                            '<tr>'+
                              '<td><i class="fas fa-cocktail"></i></td>'+
                              '<td>Free mini-bar</td>'+
                            '</tr>'+
                          '</table>'+
                        '</div>'+
                        '<br><div class="col-12 set-center-responsive">'+
                          '<a href="../system_hotel_user_find.php"><button class="btn button-dark" type="button">Book now</button></a>'+
                        '</div>'+
                      '</div>'+

                    '</div>'+
                 '</div>';

      $(".detail").html(html);
      $(".room_detail").modal('show');
      $('.thumbnail').first().addClass('active-img');
      var imurl = $('.thumbnail').first().attr('src');
      console.log(imurl);
      $('.show-img').attr('src',imurl);
    },
    error:function (){ alert('oop');}
  });
}