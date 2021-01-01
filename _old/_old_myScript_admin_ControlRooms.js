//for search_cmd.php
$(document).ready(function(){

	var FormRequestClass = ".addrateDate";
  	datepicker_set(FormRequestClass);


	//Delete checkbox เลือกลบหลายรายการ
	$( document ).on("click","#submitdelcheck",function(event){
	    event.preventDefault();
	    var roomid = getFilterDataRoomOnly(); //classname only. Without "."
	    disablebtn();
	    $('#bookingadmin').attr("disabled",true);
	    $('#checkAll').attr("hidden",false);
		$('.showcheck').attr("hidden",false);
	 	$('#submitdelcheckC').attr("hidden",false);
	 	$('#submitdelcheck').attr("hidden",true);
	 	$('#delcheckCancel').attr("hidden",false);
	 	$('#submitdelcheckC').val('ลบ '+roomid.length+' ห้อง');
	});

	//ปุ่ม เลือกทั้งหมด
	$( document ).on("click","#checkAll",function(event){
	  	event.preventDefault();
    	$('.delcheck').not(this).prop('checked', true);
    	var roomid = getFilterDataRoomOnly();
	  	$('#submitdelcheckC').val('ลบ '+roomid.length+' ห้อง');
	  	$('#bookingC').val('จอง '+roomid.length+' ห้อง');
	  	$('#checkAll').attr('hidden', true);
	  	$('#discheckAll').attr('hidden', false);
	});

	//ยกเลิกปุ่ม เลือกทั้งหมด
	$( document ).on("click","#discheckAll",function(event){
	  	event.preventDefault();
    	$('.delcheck').not(this).prop('checked', false);
    	var roomid = getFilterDataRoomOnly();
	  	$('#submitdelcheckC').val('ลบ '+roomid.length+' ห้อง');
	  	$('#bookingC').val('จอง '+roomid.length+' ห้อง');
	  	$('#checkAll').attr('hidden', false);
	  	$('#discheckAll').attr('hidden', true);
	});

	//เช็ค จำนวนห้องที่ลบและจอง หลายรายการ
	$(document).on("click", ".delcheck", function () {
	  	var roomid = getFilterDataRoomOnly();
	  	$('#submitdelcheckC').val('ลบ '+roomid.length+' ห้อง');
	  	$('#bookingC').val('จอง '+roomid.length+' ห้อง');
	});

	//ยกเลิก
	$(document).on("click", "#delcheckCancel", function () {
	  	enablebtn();
	  	$('#bookingadmin').attr("disabled",false);
	  	$('#checkAll').attr("hidden",true);
		$('.showcheck').attr("hidden",true);
		$('.delcheck').prop('checked', false);
		$('#submitdelcheckC').attr("hidden",true);
		$('#submitdelcheck').attr("hidden",false);
		$('#delcheckCancel').attr("hidden",true);
		$('#discheckAll').attr('hidden', true);
	});

	//ยืนยันลบห้อง หลายรายการ
	$( document ).on("click","#submitdelcheckC",function(event){
	    event.preventDefault();
	 	var roomid = getFilterDataRoomOnly(); //classname only. Without "."
	 	if(roomid.length > 0){
			var test = $('.deletemoremodal').attr('id', 'deletemore-'+roomid);
			$('.deletemoremodal').modal('show');
			$(".sumroom").html( 'ลบ '+roomid.length+' ห้อง' );
		}
	});

	//ส่งข้อมูลไป DeleteMore
	$("#sendDeleteMoreRoomForm").submit(function(event){
		event.preventDefault();
	    var roomid = getFilterDataRoomOnly();
	    $.ajax({
			url:"../system/admin_cmd.php",
			method:"POST",
			data:{
			    command: "deletecheckbox",
			    roomid: roomid
			},
			success:function(data){
			    $('#deleteMoreRoomsForm').html(data).show();
			}
		}); 
	});
	  //////////////////////////////////////////////////////////////////////////////////
	//Booking checkbox เลือกจองหลายรายการ
	$( document ).on("click","#bookingadmin",function(event){
	    event.preventDefault();
	    if($('#checkin2').val() == "" || $('#checkout2').val() == ""){
	    	alert('โปรดระบุวัน Checkin, Checkout');
	    }else{
	    	var roomid = getFilterDataRoomOnly();
	    	var roomPrice = getFilterDataPriceOnly();
	    	disablebtn();
		    $('#submitdelcheck').attr("disabled",true);
		    $('#checkAll').attr("hidden",false);
			$('.showcheck').attr("hidden",false);
		 	$('#bookingC').attr("hidden",false);
		 	$('#bookingadmin').attr("hidden",true);
		 	$('#bookingCancel').attr("hidden",false);
		 	$('#bookingC').val('จอง '+roomid.length+' ห้อง');
	    }
	});

	//Booking ยกเลิก
	$(document).on("click", "#bookingCancel", function () {
	  	enablebtn();
	  	$('#submitdelcheck').attr("disabled",false);
	  	$('#checkAll').attr("hidden",true);
		$('.showcheck').attr("hidden",true);
		$('.delcheck').prop('checked', false);
		$('#bookingC').attr("hidden",true);
		$('#bookingadmin').attr("hidden",false);
		$('#bookingCancel').attr("hidden",true);
		$('#discheckAll').attr('hidden', true);
		$('.delcheck').prop('disabled', false);
	});

	//ยืนยันจองห้อง หลายรายการ
	$( document ).on("click","#bookingC",function(event){
	    event.preventDefault();
	 	var roomid = getFilterDataRoomOnly(); //classname only. Without "."
	 	var roomPrice = getFilterDataPriceOnly();
	 	if(roomid.length > 0){
			var test = $('.bookmoremodal').attr('id', 'bookmore-'+roomid);
			modalBookingStart(roomid.length,roomid,roomPrice);
			$('.bookmoremodal').modal('show');
		}
	});

	$('.bookmoremodal').on('hidden.bs.modal', function () {
        // remove the bs.modal data attribute from it
        clearInterval(set);
        $(this).removeData('bs.modal');
        // and empty the modal-content element
    });
});

	function enablebtn(){
		$('#checkin2').attr("disabled",false);
		$('#checkout2').attr("disabled",false);
		$('.capacity').attr("disabled",false);
		$('.type').attr("disabled",false);
		$('.status').attr("disabled",false);
		$('#addroom').attr("disabled",false);
		$('#bookingadmin').attr("disabled",false);
	}

	function disablebtn(){
		$('#checkin2').attr("disabled",true);
		$('#checkout2').attr("disabled",true);
		$('.capacity').attr("disabled",true);
		$('.type').attr("disabled",true);
		$('.status').attr("disabled",true);
		$('#addroom').attr("disabled",true);
		$('#bookingadmin').attr("disabled",true);
	}

	function getFilterDataRoomOnly() {
	    var filter2 = [];
	    $('.delcheck:checked').each(function(){
	      filter2.push($(this).val());
	      //console.log("(Control.js)ROOM ID:",filter2);
	    });
	    return filter2;
	}

	//Check checkbox price (from Search roomlist)
	function getFilterDataPriceOnly() {
	    var sumPrice = [];
	    $('.delcheck:checked').each(function(){
	      sumPrice.push($(this).data('price'));
	     $.each(sumPrice,function(i) {
		});
	      //console.log("(Control.js)Price: ",sumPrice);
	    });
	    return sumPrice;
	}

	 //Check checkbox guests maximum (from Search roomlist)
	function getFilterDataGuestsOnly() {
	    var sumGuests = [];
	    $('.delcheck:checked').each(function(){
	      sumGuests.push($(this).data('capacity'));
	     $.each(sumGuests,function(i) {
		});
	      //console.log("(Control.js)Guests Maximum: ",sumGuests);
	    });
	    return sumGuests;
	}

	//Check checkbox Type (from Search roomlist)
	function getFilterDataTypeOnly() {
	    var sumType = [];
	    $('.delcheck:checked').each(function(){
	      sumType.push($(this).data('type'));
	     $.each(sumType,function(i) {
		});
	      //console.log("(Control.js)Type: ",sumType);
	    });
	    return sumType;
	}

	//Generate Modal booking admin1
	function modalBookingStart(roomidLength,roomid,roomPrice){
		var html;
		// var getroomid = roomid;
		// var getroomPrice = roomPrice;
		console.log('function modalBookingStart'+'\n[roomid]= '+roomid+'\n[roomPrice]= '+roomPrice);

		html = '<form action="" method="POST" id="sendBookMoreRoomForm">'+
					'<div class="col-6 sumroom" align="center">จอง '+roomidLength+' ห้อง</div>'+
					'<hr>'+
					'<input type="text" value="" name="roomidBookMore" id="roomidBookMore" hidden>'+
					'<input type="submit" id="submitsignup" value="ยืนยันการจองห้อง" class="btn btn-info">'+
					'</form>';

		$('#bookMoreRoomsForm').html(html);

		//ส่งข้อมูลไป BookingMore
		$("#sendBookMoreRoomForm").on('submit', function(event){
	    event.preventDefault();
	    var roomid = getFilterDataRoomOnly();
	    var roomPrice = getFilterDataPriceOnly();
	    var checkin = moment(new Date($('#checkin2').val())).format("Y-MM-DD");
	    var checkout = moment(new Date($('#checkout2').val())).format("Y-MM-DD");
	    var guests = getFilterDataGuestsOnly();
	    var type = getFilterDataTypeOnly();
	    console.log(
	    			'Room ID: '+roomid+
	    			'\nPrice: '+roomPrice+
	    			'\nGuests: '+guests+
	    			'\nType: '+type
	    			);
	    	$.ajax({
			      url:"../system/admin_cmd.php",
			      method:"POST",
			      dataType: "json",
			      data:{
			        command: "bookcheckbox",
			        roomid: roomid,
			        roomPrice: roomPrice,
			        checkin: checkin,
			        checkout: checkout,
			        guests: guests,
			        type: type
			      },
			      success:function(data){
			        $('#checkall, #discheckAll ,.delcheck').prop('disabled', true);
			        modal(data,checkin,checkout,roomidLength); 
			      }
			});
	  });

		//return [getroomid,getroomPrice];
	}

	//Generate Modal booking admin2
	function modal(data,checkin,checkout,roomidLength){
		set = setInterval(refresh_div_admin, 1000);
		console.log('Session: '+data.session);
		var html;
		var typetext = '';
		var typerecent= '';

		html = '<form action="" method="POST" id="sendBookMoreRoomForm_info">'+

			'<div class="col-12" align="left">'+
					'<b>Check in:</b> '+data.checkin+
					'<br><b>Check out:</b> '+data.checkout+
					'<br><b>Type of room:</b> ';

					//เช็คชนิดห้องว่า แต่ละชนิดมีจำนวนเท่าไร
					$.each(data.type, function(key,value) {
						var numOccr = $.grep(data.type, function (elem) {
						    return elem === value;
						}).length;

						if(typerecent =='' && value != typerecent){
							html+= value+' x'+numOccr;
							typetext+= value+' x'+numOccr;
							typerecent = value;
						}
						if(typerecent != '' && value != typerecent){
							html+= ', '+value+' x'+numOccr;
							typetext+= ', '+value+' x'+numOccr;
							typerecent = value;
						}
					});
		html += '<br><b>Maximum guests:</b> '+data.sumguests+
			   '<br><span class="number"><b>Total Price:</b> '+data.sumprice+'</span> THB (Included VAT 7%)'+
			'</div>'+
			'<hr>'+
			'<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชื่ิอ</label></div>'+
				'<div class="col-6" align="center"><input name="fname" id="fname" placeholder="class="form-control input-text-bx" required></div><br>'+

			'<div class="col-12" align="left"><label for="name" style=" text-align: left;">นามสกุล</label></div>'+
				'<div class="col-6" align="center"><input name="lname" id="lname" placeholder="" class="form-control input-text-bx" required></div><br>'+

			'<div class="col-12" align="left"><label for="name" style=" text-align: left;">Email</label></div>'+
				'<div class="col-6" align="center"><input name="email" id="email" placeholder="" class="form-control input-text-bx" required></div><br>'+

			'<div class="col-12" align="left"><label for="name" style=" text-align: left;">เบอร์โทรศัพท์</label></div>'+
				'<div class="col-6" align="center"><input name="phone" id="phone" placeholder="" class="form-control input-text-bx" required></div><br>'+

			'<div class="col-12" align="left"><label for="name" style=" text-align: left;">จำนวนผู้เข้าพัก</label></div>'+
				'<div class="col-6" id="" align="center">'+
					'<select class="form-control guests input-text-bx" id="guests" name="guests" required>';
						for(var i=data.room; i<=data.sumguests; i++){
				      	html += '<option value="'+i+'">'+i+'</option>';
				  		}
    	html += '</select>'+
    			'</div><br>'+
			'<hr>'+
			'<input type="submit" id="submitBook" value="ยืนยัน" class="btn btn-info">'+
			'<input type="button" id="confirmBook" value="ยืนยันข้อมูลผู้เข้าพัก" class="btn btn-info" hidden>'+
			'&nbsp;<input type="button" id="edit" value="แก้ไข" class="btn btn-warning" hidden>'+
			'<div class="col-12"><i  class="fas fa-spinner fa-spin load"></i><span class="load"> Sending...</span></div>'+
			'</form>';

			$('#bookMoreRoomsForm').html(html);
			//Format เป็นค่าเงิน
			$('.number').formatNumber();
			$(".load").hide();

		 	//ส่งข้อมูลผู้จองไป BookingMore
		  	$("#sendBookMoreRoomForm_info").submit(function(event){
			    event.preventDefault();
				$(".input-text-bx").attr("disabled","disabled");
				$("#submitBook").attr("hidden",true);
				$("#confirmBook").attr("hidden",false);
				$("#edit").attr("hidden",false);
		    });
			//Edit booking information
			$( '#edit' ).unbind('click'); // unbindรีเซ็ต element นี้ที่มีการสร้างก่อนหน้านี้
		  	$( '#edit' ).on("click",function(event){
		      event.preventDefault();
		      $(".input-text-bx").attr("disabled",false);
		      $("#confirmBook").attr("hidden",true);
		      $("#submitBook").attr("hidden",false);
		      $("#edit").attr("hidden",true);
		    });

		  //Confirm Booking
		  	$( '#confirmBook' ).unbind('click');
			$( '#confirmBook' ).on("click",function(event){
				clearInterval(set);
			    event.preventDefault();
			    $(".load").show();
			      jQuery.ajax({
			      url: "../system/cmd.php",
			      data:{
			        command: "confirmBooking",
			        path: "admin",
			        //session: data.session,
			        special: $("#special").val(),
			        guest_paymenttype: "5",
			        guest_total: data.sumprice,
			        guest_email: $("#email").val(),
			        guest_firstname: $("#fname").val(),
			        guest_lastname: $("#lname").val(),
			        guest_phone: $("#phone").val(),
			        guest_rate: "Walk in",
			        guest_rateid: "11",
			        guest_special: $("#special").val(),
			        guest_codesession: data.session,
			        checkin: checkin,
			        checkout: checkout,
			        guests: $("#guests").val(),
			        rooms: roomidLength,
			      	typename: typetext
			      },
			      type: "POST",
			      success:function(data){
			        $("#sendBookMoreRoomForm_info").html(data); //แสดงสถานะ
			        console.log('Success');
			        $(".load").hide();
			        Removetable();
			        enablebtn();
				  	$('#submitdelcheck').attr("disabled",false);
				  	$('#checkAll').attr("hidden",true);
					$('.showcheck').attr("hidden",true);
					$('.delcheck').prop('checked', false);
					$('#bookingC').attr("hidden",true);
					$('#bookingadmin').attr("hidden",false);
					$('#bookingCancel').attr("hidden",true);
					$('#discheckAll').attr('hidden', true);
					$('#checkall, #discheckAll, .delcheck').prop('disabled', false);
			      },
			      error:function (){ alert('error'); }
			    });
			});
	}

	function refresh_div_admin() {
      jQuery.ajax({
            url:'system/refresh.php',
            method:"POST",
            type: 'json',
            success:function(results) {
                $.each(JSON.parse(results), function(key,value){
                  console.log('Time status: '+value);
                  if(value == 0){
                    $.removeCookie('popup');
                    alert('Timeout');
                    clearInterval(set); //ยกเลิกการเซตเวลา
                    document.location.href = 'system_hotel_admin_rooms.php'
                  }
                });
            }
      });
  	}

  	function Removetable() {
	    $('.delcheck:checked').each(function(){
	    	$('tr[data-roomid="'+$(this).val()+'"]').prop('hidden',true);
	      //console.log("Del check" +$(this).val());
	    });
	}
