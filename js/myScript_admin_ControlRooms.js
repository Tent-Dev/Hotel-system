var request_type = "booking"; //For DML condition
var checkbox_get_global = [];
var checkbox_get_global_name = [];
var checkbox_get_global_price = [];
var checkbox_get_global_guests = [];
var checkbox_get_global_type = [];
$(document).ready(function(){
	get_data();
	$( document ).on("change",".option_search_date",function(event){
	    get_data();
	});

	$(".option_search").click(function() {
    	get_data();
  	});

  	$(document).on("keypress", function (e) {
  		// 13 = enter button
  		if(e.which == 13){
	        get_data();
	    }
  	});

	//ส่งค่าไปให้ Model booking
	$(document).on("click", ".deletebtn", function(){
		if($('#checkin2').val() == "" || $('#checkout2').val() == ""){
			alert('โปรดระบุวัน Checkin, Checkout');
		}else{
			var id = $(this).data('id');
			var ref = $('.room_bookingMoremodel').attr('id', 'bookmore-'+id);
			var send_data = {
					id: id,
					command_search: 'search_room_Info',
					form: '#bookingMoreRoomForm',
					modal: '.room_bookingMoremodel',
					from_table: 'h_room',
					where_field: 'h_room_id'
				};
			$('#id-'+id).find('.checkbox-delete:checked');
			generate_model_delete_dml(send_data); //DML.js
		}
	});

	//ปุ่ม ยืนยันจองห้องที่เลือกทั้งหมด
	$(document).on('click','#selectDeleteMorebtn',function(){
		var selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination_booking','id');
		var selected_name = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination_booking','name');
		var ref = $('.roomsetting_deleteMoremodel').attr('id', 'bookmore');
		var send_data = {
			group_id: selected,
			group_name: selected_name,
			form: '#bookingMoreRoomForm',
			modal: '.room_bookingMoremodel'
		};
		if(selected.length > 0){
			generate_model_deleteMore_dml(send_data); //DML.js
		}
	});

	//ปุ่ม ส่งค่าไปทั้งหมดที่เลือก
	$(document).on("click", ".confirmBookingMore", function(){
		var selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination_booking','id');
		console.log(selected);
	    var price_selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination_booking','price');
	    var guests_selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination_booking','capacity');
	    var type_selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination_booking','type');
	    var checkin = moment(new Date($('#checkin2').val())).format("Y-MM-DD");
	    var checkout = moment(new Date($('#checkout2').val())).format("Y-MM-DD");
	    console.log(
	    			'Room ID: '+selected+
	    			'\nPrice: '+price_selected+
	    			'\nGuests: '+guests_selected+
	    			'\nType: '+type_selected
	    			);
	    $.ajax({
		    url:"../system/admin_cmd.php",
		    method:"POST",
		    dataType: "json",
		    data:{
		     	command: "bookcheckbox",
		    	roomid: selected,
		    	roomPrice: price_selected,
		    	checkin: checkin,
		    	checkout: checkout,
		    	guests: guests_selected,
		    	type: type_selected
		    },
		    success:function(data){
		    	$('#checkboxAll, #unCheckboxAll ,.checkbox-delete').prop('disabled', true);
		    	modal(data,checkin,checkout,selected.length); 
		    }
		});
	});

	//หยุด refresh timeout หลังปิด modal
	$('.room_bookingMoremodel').on('hidden.bs.modal', function () {
		try{
			clearInterval(set);
		}
		catch(e){}
        $(this).removeData('bs.modal');
    });
});

function get_data(page){
	if(page == undefined || page < 1) page = 1;
	$('#display_room').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');

	var capacity = getFilterData_dml('.capacity:checked','this');
    var price = getFilterData_dml('.price:checked','this');
    var checkin = moment(new Date($('.checkin2').val())).format("Y-MM-DD");
    var checkout = moment(new Date($('.checkout2').val())).format("Y-MM-DD");
    var type = getFilterData_dml('.type:checked','this');
    var status = getFilterData_dml('.status:checked','this');

	$.ajax({
		url: '../system/search_cmd.php',
		method: 'POST',
		type: 'JSON',
		data:{
			command: 'search_roomlist_2',
			capacity: capacity,
	        price: price,
	        type: type,
	        status: status,
	        checkinDb: checkin,
	        checkoutDb: checkout,
	        page:page
		},
		success:function(data){
			data = JSON.parse(data);
	  		new_pagination(data,page);
			if(data.html != undefined){
				$('#display_room').html('<br>No room found.');
				$('.sumresult').html('&nbsp;0');
				console.log('after had deleted. This page is null!','\nCurrent page is: '+thispage_is);
				new_pagination(data,thispage_is,true);
			}
			else{
				$('#display_room').html('');
				$.each(data.result,function(key,value){
					var html = 	'<tr id="id-'+value.h_room_id+'" class="tbl" style="background-color:'+value.h_status_color+';">'+
									'<td class="room_name">'+value.h_room_name+'</td>'+
									'<td class="type_name">'+value.h_type_name+'</td>'+
									'<td class="capacity">'+value.h_type_capacity+'</td>'+
									'<td class="number price">'+value.h_type_price+' THB</td>'+
									'<td class="status_name">'+value.h_status_name+'</td>'+
									'<td>'+
										'<div class="hide_in_table">'+
											'<button class="btn btn-primary btn-sm deletebtn sm-font-admin" data-id="'+value.h_room_id+'"><i class="fas fa-book-medical"></i> จองห้องนี้</button>'+
										'</div>'+
									'</td>'+
									'<td class="text-center">'+
										'<input hidden class="form-check-input checkbox-delete booking unlock-while-delete" type="checkbox" value="'+value.h_room_id+'"'+
											'data-name="'+value.h_room_name+'"'+
											'data-price="'+value.h_type_price+'"'+
											'data-type="'+value.h_type_name+'"'+
											'data-capacity="'+value.h_type_capacity+'"'+
										'>'+
									'</td>'+
								'</tr>';
				    $(html).appendTo('#display_room');
				});
				checkbox_save();
				$('.number').formatNumber();
				//Hover table
				$('tr').hover(function(){
					$(this).find('.hide_in_table').css('visibility','visible');
				},function(){
					$(this).find('.hide_in_table').css('visibility','collapse');
				});
				if($('#addbtn, .lock-while-delete').prop('disabled')){
					set_adminBtn_for_pagination_dml(); //DML.js
				}
			}
		},
		error:function(){alert('err');}
	});
}

//Generate Modal booking
function modal(data,checkin,checkout,roomidLength){
	set = setInterval(refresh_div_admin, 1000);
	console.log('Session: '+data.session +' >>'+roomidLength);
	var id_room_for_one = data.roomid[0];
	var html;
	var typetext = '';
	var typerecent= '';

	html = '<form action="" method="POST" id="sendBookMoreRoomForm_info" class="sm-font-admin">'+

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
			'<div class="col-6" align="center"><input name="fname" id="fname" placeholder="" class="form-control input-text-bx" required></div><br>'+

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

		$('#bookingMoreRoomForm').html(html);
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
		        $('#id-'+id_room_for_one).prop('hidden',true);
		        //Removetable();
		        get_data(thispage_is);
		        setBtn_cancel_dml();
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
	});
}