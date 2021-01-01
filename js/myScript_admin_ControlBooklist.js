$(document).ready(function(){

	//Status_today Drag by mouse
	$(".sticky").hover(function(){
		$(this).fadeTo( "fast", 0.33 );
		$(this).css('cursor', 'move');
		$(this).draggable({
			containment: 'window',
			cancel: false,
			drag: function (event, ui) {
			    var offset = 50;
			    var maxHeight = (window.innerHeight - this.offsetHeight - offset);

			    if (ui.position.top < offset) {
			      ui.position.top = offset;
			      return false;
			    } 
			    else if (ui.position.top > maxHeight) {
			      ui.position.top = maxHeight;
			      return false;
   				}
   			}
		});
	},function(){
		$(this).fadeTo( "fast", 1 );
		$(this).css('cursor', 'default');
	});
	//datepicker_admin
	var FormRequestClass = ".adminSettingSearchDate_bottom";
  	datepicker_set(FormRequestClass); //Function in myScript.js

	//search booklist real time
  	get_data();
  	today_status();
  	$('.sync_notice').html('อัพเดทข้อมูลทุก 10 วินาที');
  	//ตั้งให้ดึงข้อมูล today_status ทุกๆ 10 วิ
  	today_status_set = setInterval(today_status, 10000);   // 1000 = 1 second

  	$(".search_booklist").click(function() {
    	get_data();
  	});

  	$('.bookfilter').click(function(){
        get_data();
  	});

  	$( document ).on("change",".datecheck",function(){
  		$('#day').val(0);
        get_data();
  	});

  	$( document ).on("change",".day_select",function(){
  		$('.checkin2, .checkout2').val('');
        get_data();
  	});

  	$(document).on("keypress", function (e) {
  		// 13 = enter button
  		if(e.which == 13){
	        get_data();
	    }
  	});

  	$(document).on("click",".pause", function(e){
		e.preventDefault();
		clearInterval(today_status_set);
		$('.pause, .sync').prop('hidden',true);
		$('.play').prop('hidden',false);
		$('.sync_notice').html('หยุดการอัพเดทข้อมูล');
	});

	$(document).on("click",".play", function(e){
		e.preventDefault();
		today_status_set = setInterval(today_status, 10000);
		$('.play').prop('hidden',true);
		$('.pause, .sync').prop('hidden',false);
		$('.sync_notice').html('อัพเดทข้อมูลทุก 10 วินาที');
	});

  	$("#approve6").click(function() {
    	$('.checkin2, .checkout2').val('');
    	$('#day').val(0);
  	});

  	//ส่งค่าไปให้ Model
	$(document).on("click", ".infoPop", function () {
		var cusid = $(this).data('id');
		var ref = $('.infomodal').attr('id', 'info-'+cusid);
		var command_js = "info";
		Info_ConfirmCheckin_and_Update(ref,cusid,command_js);
	});

	//ส่งค่าไปให้ Model Update info
	$(document).on("click", ".UpdatePop", function () {
		var cusid = $(this).data('id');
		var ref = $('.updatemodal').attr('id', 'update-'+cusid);
		var command_js = "update";
		Info_ConfirmCheckin_and_Update(ref,cusid,command_js);	
	});

	//ส่งค่าไปให้ Model Submit Update info
	$(document).on("click", "#submit_update", function (e) {
		e.preventDefault();
		$('.successAlert').fadeIn({ duration: 1500});
	    $('.successAlert').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
		var id = $(this).data('id');
		var firstname = $('#firstname').val();
		var lastname = $('#lastname').val();
		var email = $('#email').val();
		var phone = $('#phone').val();
		var region = $('#region').val();
		var address = $('#address').val();
		var city = $('#city').val();
		var postal = $('#postal').val();

		$.ajax({
			url:"../system/admin_cmd.php",
			method:"POST",
			data:{
				command: "customerEdit_fromAdmin",
				cusId: id,
				cusFname: firstname,
				cusLname: lastname,
				cusEmail: email,
				cusPhone: phone,
				cusRegion: region,
				cusAddress: address,
				cusCity: city,
				cusPostal: postal
			},
			success:function(data){
				$('.successAlert').html('');
		    	$('.successAlert').html('บันทึกสำเร็จ <i class="fas fa-check-circle"></i>');
		    	$('.successAlert').fadeOut({ duration: 1500});
			}
		});
	});

	//ส่งค่าไปให้ Model Checkin Confirm
	$(document).on("click", ".ConCheckinPop", function () {
		var cusid = $(this).data('id');
		var ref = $('.concheckinmodal').attr('id', 'conckeckin-'+cusid);
		var command_js = "confirm_checkin";
		Info_ConfirmCheckin_and_Update(ref,cusid,command_js);
	});

	//ส่งค่าไปให้ Model Checkin Cancel
	$(document).on("click", ".CanCheckinPop", function () {
		var ref = $('.cancheckinmodal').attr('id', 'canckeckin-'+$(this).data('id'));
		var id = $(this).data('id');
		var session = $(this).data('session');
		var firstname = $(this).data('firstname');
		var lastname = $(this).data('lastname');
		Cancel(ref,id,session,firstname,lastname);
	});

	//ส่งค่าไปให้ Model Checkout Confirm
	$(document).on("click", ".ConCheckoutPop", function () {
		var ref = $('.concheckoutmodal').attr('id', 'conckeckout-'+$(this).data('id'));
		var id = $(this).data('id');
		var session = $(this).data('session');
		var firstname = $(this).data('firstname');
		var lastname = $(this).data('lastname');
		var checkin = $(this).data('checkin');
		var checkout = $(this).data('checkout');
		var rateid = $(this).data('rateid');
		var ratename = $(this).data('ratename');
		Confirm_Checkout(ref,id,session,firstname,lastname,checkin,checkout,rateid,ratename);
	});

	//ส่งค่าไปให้ Model Checkin Cancel
	$(document).on("click", ".ReceiptPop", function () {
		var ref = $('.receiptmodal').attr('id', 'receipt-'+$(this).data('id'));
		var id = $(this).data('id');
		$('.receiptmodal').modal('show');
		Receipt_history(ref,id);
	});

	$('.concheckoutmodal').on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal');
        $('#dialog_lg').removeClass('mw-100');
    });

	$(document).on('focus','.price, .qty',function(){
		var dis = parseFloat($(this).val().replace(/,/g,''));
		$(this).val(dis);
	});

	$(document).on('blur','.price, .qty',function(){
		var dis = parseFloat($(this).val().replace(/,/g,'')).toLocaleString();
		$(this).val(dis);
	});

});

function get_data(page){
	if(page == undefined || page < 1) page = 1;
	$('#display').html('<div id="loading"><h5><i class=\"fas fa-spinner fa-spin\"></i>Loading .....</h5></div>');
   	var name = $('#search').val();
   	var approve = $('.approve:checked').val();
   	var from_date = moment(new Date($('.checkin2').val())).format("Y-MM-DD");
    var to_date = moment(new Date($('.checkout2').val())).format("Y-MM-DD");
    var day_select = $('#day').val();
    if(from_date == "Invalid date" || to_date == "Invalid date"){
    	from_date = "";
    	to_date = "";
    }
   $.ajax({
       url: "../system/search_cmd.php",
       method: "POST",
       type: "json",
       data:{
        command: "search_booklist",
        search: name,
        approve: approve,
        from_date: from_date,
        to_date: to_date,
        day_select: day_select,
        page:page
       },
       success: function(data) {
       		data = JSON.parse(data);
	  		new_pagination(data,page);
			if(data.html != undefined){
				$('#display').html('No Booklist found.');
				$('.sumresult').html('&nbsp;0');
				console.log('after had deleted. This page is null!','\nCurrent page is: '+thispage_is);
				new_pagination(data,thispage_is,true);
			}else{
				$('#display').html('');
				var typerecent;
				var text ='';
				var text_room ='';
				$.each(data.result,function(key,value){
				    var json_data = '<div class=\"col-12 statuscheck\" data-status="'+value.h_bill_status+'" data-cusid="'+value.h_bill_customerid+'" >'+
										'<div class=\"col-12\" style=\"background-color: black; color: white; padding: 5px;\"> <b>Receipt No. </b>'+value.h_bill_customerid+'-'+value.h_bill_session;
											if(value.h_bill_status == 1){
												json_data +='<span class=\"float-right\" style="color: yellow;">'+value.h_bill_status_name+'</span></div>';
											}
											else if(value.h_bill_status == 2){
												json_data +='<span class=\"float-right\" style="color: #00e600;">'+value.h_bill_status_name+'</span></div>';
											}
											else if(value.h_bill_status == 3){
												json_data +='<span class=\"float-right\" style="color: white;">'+value.h_bill_status_name+'</span></div>';
											}
											else if(value.h_bill_status == 4){
												json_data +='<span class=\"float-right\" style="color: #ff704d;">'+value.h_bill_status_name+'</span></div>';
											}
										
										json_data += '</div><div class=\"col-12\"> <b>Customer Name: </b>'+value.h_customer_firstname+' '+value.h_customer_lastname+
												' <a class=\"infoPop\" href=\"#\"'+
													'data-toggle=\"modal\"'+
													'data-id="'+value.h_bill_customerid+'"'+
													'data-target=\"#info-'+value.h_bill_customerid+'"><i class=\"fas fa-address-card\" style=\"font-size: 18px;\"></i>'+
												'</a>'+
											'</div>'+
										'<div class=\"col-12\"> <b>Check in: </b>'+moment(value.h_bill_checkin).format("DD-MMM-YYYY")+'<b> Check out:</b> '+moment(value.h_bill_checkout).format("DD-MMM-YYYY")+'</div>'+
										'<div class=\"col-12\"> <b>Type of room: </b>';

										$.each(value.type, function(key2,value2){
											//เช็คว่าถ้ามีตัวต่อไปแล้วไม่ซ้ำ ให้ใส่ลูกน้ำคั่น
											if((typerecent == undefined || typerecent == '') && key2 != typerecent){
												json_data+= key2+' x'+value2;
												text+= key2+' x'+value2;
												typerecent = key2;
											}
											if(typerecent != '' && key2 != typerecent){
												json_data+= ', '+key2+' x'+value2;
												text+= ', '+key2+' x'+value2;
												typerecent = key2;
											}
										});

										json_data += '</div><div class=\"col-12\"> <b>No. of room: </b>';
										typerecent ='';

										$.each(value.room, function(key2,value2){
											//เช็คว่าถ้ามีตัวต่อไปแล้วไม่ซ้ำ ให้ใส่ลูกน้ำคั่น
											if(typerecent =='' && value2 != typerecent){
												json_data+= value2;
												text_room+= value2;
												typerecent = value2;
											}
											if(typerecent != '' && value2 != typerecent){
												json_data+= ', '+value2;
												text_room+= ', '+value2;
												typerecent = value2;
											}
										});
										typerecent ='';

										json_data += '</div><div class=\"col-12\"> <b>Number of room: </b>'+value.h_bill_rooms+'</div>'+
										'<div class=\"col-12\"> <b>Number of guest: </b>'+value.h_bill_guests+'</div>'+
										'<div class=\"col-12\"> <b>Rate type: </b>'+value.h_rate_name+'</div>'+
										'<div class=\"col-12\"> <b>Payment type: </b>'+value.h_paymenttype_type+'</div>'+
										'<div class=\"col-12 number\"> <b>Total price: </b>'+value.h_bill_price+' THB</div>'+
										'<div class=\"col-12\"> <b>Special request: </b>'+value.h_bill_special+'</div>'+
										'<div class=\"col-12\">';

										if(value.h_bill_status !=3 && value.h_bill_status !=4){
											json_data +='<button class=\"btn btn-primary btn-sm UpdatePop\"'+
														'data-id="'+value.h_bill_customerid+'"'+
														'data-target=\"#info-'+value.h_bill_customerid+'"><i class="fas fa-user-edit"></i> แก้ไขข้อมูลผู้จอง</button> '+

											'<button class=\"btn btn-success btn-sm ConCheckinPop\"'+
														'data-id="'+value.h_bill_customerid+'"'+
														'data-target=\"#conckeckin-'+value.h_bill_customerid+'\"'+
														'data-firstname=\"'+value.h_customer_firstname+'\"'+
														'data-lastname=\"'+value.h_customer_lastname+'\"'+
														'data-session="'+value.h_bill_session+'"'+
														'data-type="'+text+'"'+
														'data-room="'+text_room+'">'+
											'<i class="far fa-calendar-check"></i> ยืนยันเช็คอิน</button> '+

											'<button class=\"btn btn-danger btn-sm CanCheckinPop\"'+
														'data-id="'+value.h_bill_customerid+'"'+
														'data-target=\"#canckeckin-'+value.h_bill_customerid+'\"'+
														'data-firstname=\"'+value.h_customer_firstname+'\"'+
														'data-lastname=\"'+value.h_customer_lastname+'\"'+
														'data-session="'+value.h_bill_session+'"><i class="far fa-calendar-times"></i> ยกเลิก</button> '+
											'<button class=\"btn btn-success btn-sm ConCheckoutPop\"'+
														'data-id="'+value.h_bill_customerid+'"'+
														'data-firstname=\"'+value.h_customer_firstname+'\"'+
														'data-lastname=\"'+value.h_customer_lastname+'\"'+
														'data-session="'+value.h_bill_session+'"'+
														'data-checkin="'+moment(value.h_bill_checkin).format("DD-MMM-YYYY")+'"'+
														'data-checkout="'+moment(value.h_bill_checkout).format("DD-MMM-YYYY")+'"'+
														'data-rateid="'+value.h_rate_id+'"'+
														'data-ratename="'+value.h_rate_name+'"'+
														'data-target=\"#conckeckout-'+value.h_bill_customerid+'\"><i class="far fa-calendar-minus"></i> ยืนยันเช็คเอาท์</button>';
										}
										if(value.h_bill_status == 3){
											json_data +='<button class="btn btn-info btn-sm ReceiptPop" data-id="'+value.h_bill_customerid+'">'+
											'<i class="fas fa-file-invoice"></i> ดูใบเสร็จ</button> ';
										}
										json_data +='</div>'+
													'</div>'+
													'<hr>';
				    $(json_data).appendTo('#display');
				});
			}
		  	$('.number').formatNumber();
			checkstatus();
       },
       error: function(){ alert('err');}
   });
}

function Info_ConfirmCheckin_and_Update(ref,cusid,command_js){
	$.ajax({
		url:"../system/search_cmd.php",
		type: "json",
		method: "POST",
		data:{
			command: "search_booklist_Info",
			cusid: cusid
		},
		success:function(data){
			var text;
			$.each(JSON.parse(data),function(key, value){

				if(command_js == "info"){
						text = 	'<b>Name: </b>'+value.h_customer_firstname+' '+value.h_customer_lastname+'<br>'+
								'<b>Email: </b>'+value.h_customer_email+'<br>'+
								'<b>Phone: </b>'+value.h_customer_phone+'<br>'+
								'<b>Region: </b>'+value.h_customer_region+'<br>'+
								'<b>Address: </b>'+value.h_customer_address+' '+value.h_customer_city+' '+value.h_customer_postal;

					$('.infomodal').modal('show');
					$('.info').html(text);
				}
				else if(command_js == "update"){
					text = 	'<form id="update_cus"><div class="col-12 row"><div class="col-2"><b>Name: </b></div><div class="col-5"><input value="'+value.h_customer_firstname+'" name="firstname" id="firstname" class="form-control" placeholder="First name" required></div>'+
							'<div class="col-5"><input value="'+value.h_customer_lastname+'" name="lastname" id="lastname" class="form-control" placeholder="Last name" required></div></div><br>'+
							'<div class="col-12 row"><div class="col-2"><b>Email: </b></div><div class="col-10"><input value="'+value.h_customer_email+'" name="email" id="email" class="form-control" placeholder="Email" required></div>'+
							'</div><br>'+
							'<div class="col-12 row"><div class="col-2"><b>Phone: </b></div><div class="col-5"><input value="'+value.h_customer_phone+'" name="phone" id="phone" class="form-control" required></div>'+
							'</div><br>'+
							'<div class="col-12 row"><div class="col-2"><b>Region: </b></div><div class="col-5"><input value="'+value.h_customer_region+'" name="region" id="region" class="form-control checknull" placeholder="Region" required></div>'+
							'</div><br>'+
							'<div class="col-12 row"><div class="col-2"><b>Address: </b></div><div class="col-10"><input value="'+value.h_customer_address+'" name="address" id="address" class="form-control checknull" placeholder="Address" required></div>'+
							'</div><br>'+
							'<div class="col-12 row"><div class="col-2"><b>City: </b></div><div class="col-5"><input value="'+value.h_customer_city+'" name="city" id="city" class="form-control checknull" placeholder="City" required></div>'+
							'</div><br>'+
							'<div class="col-12 row"><div class="col-2"><b>Postal: </b></div><div class="col-5"><input value="'+value.h_customer_postal+'" name="postal" id="postal" class="form-control checknull" placeholder="Postal code" required></div>'+
							'</div><hr>'+
							'<div align="center"><input type="submit" id="submit_update" data-id="'+cusid+'" value="บันทึกข้อมูลผู้จอง" class="btn btn-success"></div>'+
							'<br><div align="center"><span class="successAlert text-success"></span></div></form>';

					$('.updatemodal').modal('show');
					$('.update').html(text);
					$('input[value="null"]').val("");
				}
				else if(command_js == "confirm_checkin"){
					text = 	'<div align="center"><b>ยืนยันรายการเช็คอิน</b><br>'+cusid+'-'+value.h_customer_codesession+'<br>'+
							'<b>Customer name: </b> '+value.h_customer_firstname+' '+value.h_customer_lastname+'</div>'+
							'<hr>'+
							'<div align="center"><button class="btn btn-success btn-sm" id="submit_conCheckin"><i class="far fa-calendar-check"></i> ยืนยันรายการนี้</button></div>'+
							'<div align="center" class="alert" hidden><span class="text-danger">ข้อมูลผู้เข้าพักยังไม่สมบูรณ์</span></div>';			

					$('.concheckinmodal').modal('show');
					$('.concheckin').html(text);
				}
			});

			//หาvalue ที่มีค่าว่างใน json array
			data = JSON.parse(data);
			data.forEach(function (el) {
				Object.keys(el).forEach(function (value_in_array) {
					if (el[value_in_array] === '') {
					    $('#submit_conCheckin').prop('disabled',true);
					    $('.alert').prop('hidden',false);
					}
				});
			});

			//ส่งค่าไป Submit Checkin Confirm
			$(document).on("click", "#submit_conCheckin", function () {
				$.ajax({
					url:"../system/admin_cmd.php",
					method:"POST",
					data:{
						command: "billConCheckin_fromAdmin",
						cusId: cusid
					},
					success:function(data){
						$('.concheckin').html(data);
					}
				});
			});
		},
		error:function(){alert("error");}
	});
}

function Confirm_Checkout(ref,id,session,firstname,lastname,checkin,checkout,rateid,ratename){
	var sum_out_of_room_price;
	var text = 	'<div align="center"><b>ยืนยันรายการเช็คเอาท์</b><br>'+id+'-'+session+'<br>'+
				'<b>Customer name: </b> '+firstname+' '+lastname+'</div>'+
				'<hr>'+
				'<div align="center"><button class="btn btn-success btn-sm" id="submit_conCheckout"><i class="far fa-calendar-minus"></i> ดำเนินการต่อ</button></div>';

	$('.concheckoutmodal').modal('show');
	$('.concheckout').html(text);

	
	$(document).on("click", "#submit_conCheckout", function (e) {
		e.preventDefault();
		$.ajax({
			url:"../system/search_cmd.php",
			method:"POST",
			data:{
				command: "search_booklist_Type",
				cusId: id,
				rateid: rateid
			},
			success:function(data){
				var date_today = moment(new Date(),"YYYY-MM-DD").utcOffset(7, true).format("ddd DD-MMM-YYYY | HH:mm:ss A z(Z)");
				var text2 = '<form id="saveall">'+
							'<div class="container sm-font-admin">'+
							  '<div class="row print_now">'+
							    '<div class="col-md-12">'+
								    '<div class="col-12 row">'+
								    	'<div class="col-12" align="center"><h1>Receipt</h></div>'+
								    	'<div class="col-12 col-md-7" style="padding-top:10px;">'+
										    '<div><b>Receip No.</b> '+id+'-'+session+'</div>'+
										    '<div><b>Date: </b> '+date_today+'</div><br>'+
										    '<div><b>Customer name:</b> '+firstname+' '+lastname+'</div>'+
										    '<div><b>Check in:</b> '+checkin+' <b>Check out:</b> '+checkout+'</div>'+
										    '<div><b>Rate select:</b> '+ratename+'</div>'+
									    '</div>'+
									    '<div class="col-5 bill-watermark d-md-block d-none">'+
											'<div class="float-right" style="font-size: 12px;">'+
												'<img class="bill-watermark-img" src="../img/_page/logo_email.png"><br>'+
												'Tax Payer No. <span id="tax_pay_no">0901108123442</span><br>'+
												'<span id="company_name">Tent\'s Hotel reservation System Co.,Ltd.</span><br>'+
												'<span id="company_address">235/233 Pattanakarn Rd. Bangkok 10250</span><br>'+
												'<i class="fas fa-phone"></i> <span id="company_tel">(+66)830884161</span> | <i class="fas fa-envelope"></i> <span id="company_email">bo.chutipas_st@tni.ac.th</span>'+
											'</div>'+
										'</div>'+
							    	'</div><hr>'+
							      '<table class="table table-bordered table-hover sm-font-admin" id="tab_logic">'+
							        '<thead>'+
							          '<tr>'+
							            '<th class="text-center" hidden> # </th>'+
							            '<th class="text-center"> Product </th>'+
							            '<th class="text-center"> Qty </th>'+
							            '<th class="text-center"> Price </th>'+
							            '<th class="text-center"> Total </th>'+
							            '<th class="text-center hide_print"> Action </th>'+
							          '</tr>'+
							        '</thead>'+
							        '<tbody>'+
							          '<tr id="addr0">'+
							            '<td hidden>1</td>'+
							            '<td><input type="text" name="product[]"  placeholder="Enter Product Name" class="form-control product checksize sm-font-admin"/></td>'+
							            '<td><input type="text" name="qty[]" placeholder="Enter Qty" class="form-control qty print_text_center checksize sm-font-admin" step="0" min="0" value="0"/>'+
							            '<td><input type="text" name="price[]" placeholder="Enter Unit Price" class="form-control price print_text_center checksize sm-font-admin" step="0.00" min="0" value="0"/>'+
							            '<td><input type="text" name="total[]" placeholder="0.00" class="form-control total print_text_center checksize sm-font-admin" readonly/>'+
							            '<td class="hide_print text-center btndel">'+
							            '</td>'+
							          '</tr>'+
							        '</tbody>'+
							      '</table>'+
							    '</div>'+
							  '</div>'+
							  '<div class="row">'+
								  '<div class="col-md-12">'+
								    '<button id="add_row" class="btn btn-info float-left sm-font-admin">Add Row</button>&nbsp;'+
								  '</div>'+
								  '<div class="col-12 margin-zero">'+
								  	'<div class="row col-12 print_now margin-zero" style="margin-top:20px">'+
								    	'<div class="col-12 margin-zero">'+
								    		'<div class="col-12 col-md-5 float-right zoom_main">'+
										      '<table class="table table-bordered fit" id="tab_logic_total">'+
										        '<tbody>'+
										          '<tr>'+
										            '<th class="text-right align-middle sm-font-admin">Sub Total</th>'+
										            '<td class="text-center">'+
										            	'<div class="input-group col-10 mb-2 mb-sm-0 zoom"><input type="text" name="sub_total" placeholder="0.00" class="form-control number_width print_text_right sm-font-admin" id="sub_total" readonly/>'+
										            		'<div class="input-group-prepend">'+
																'<div class="input-group-text">&#3647;</div>'+
															'</div>'+
														'</div>'+
													'</td>'+
										          '</tr>'+
										          '<tr>'+
										            '<th class="text-right align-middle sm-font-admin">Discount<br><span style="font-size:12px;">(Room only)</span></th>'+
										            '<td class="text-center">'+
										            	'<div class="input-group col-10 mb-2 mb-sm-0 zoom"><input type="text" name="discount" placeholder="0.00" class="form-control number_width print_text_right sm-font-admin" id="discount" readonly/>'+
										            		'<div class="input-group-prepend">'+
																'<div class="input-group-text money"></div>'+
															'</div>'+
														'</div>'+
													'</td>'+
										          '</tr>'+
										          '<tr>'+
										            '<th class="text-right align-middle sm-font-admin">Total</th>'+
										            '<td class="text-center">'+
										            	'<div class="input-group col-10 mb-2 mb-sm-0 zoom"><input type="text" name="sub_total_dis" placeholder="0.00" class="form-control number_width print_text_right sm-font-admin" id="sub_total_dis" readonly/>'+
										            		'<div class="input-group-prepend">'+
																'<div class="input-group-text">&#3647;</div>'+
															'</div>'+
														'</div>'+
													'</td>'+
										          '</tr>'+
										          '<tr class="hide_print">'+
										            '<th class="text-right align-middle sm-font-admin">Vat</th>'+
										            '<td class="text-center">'+
										            	'<div class="input-group col-7 mb-2 mb-sm-0">'+
										                	'<input type="number" class="form-control number sm-font-admin" id="tax" placeholder="0" value="7">'+
										                	'<div class="input-group-prepend">'+
																'<div class="input-group-text">%</div>'+
															'</div>'+
										              	'</div>'+
										            '</td>'+
										          '</tr>'+
										          '<tr>'+
										            '<th class="text-right align-middle sm-font-admin">Vat Amount <span class="print_ready print_vat" hidden></span></th>'+
										            '<td class="text-center">'+
										            	'<div class="input-group col-10 mb-2 mb-sm-0 zoom"><input type="text" name="tax_amount" id="tax_amount" placeholder="0.00" class="form-control number print_text_right sm-font-admin" readonly/>'+
										            		'<div class="input-group-prepend">'+
																'<div class="input-group-text">&#3647;</div>'+
															'</div>'+
														'</div>'+
													'</td>'+
										          '</tr>'+
										         '<tr>'+
										            '<th class="text-right align-middle sm-font-admin">Grand Total</th>'+
										            '<td class="text-center">'+
										            	'<div class="input-group col-10 mb-2 mb-sm-0 zoom"><input type="text" name="total_amount" id="total_amount" placeholder="0.00" class="form-control number_width print_text_right sm-font-admin" readonly/>'+
										            		'<div class="input-group-prepend">'+
																'<div class="input-group-text">&#3647;</div>'+
															'</div>'+
														'</div>'+
													'</td>'+
										          '</tr>'+
										        '</tbody>'+
										      '</table>'+
										    '</div>'+
										'</div>'+
									'</div>'+
									'<div class="col-12 float-left print_now print_ready" align="left" hidden>'+
										'<div class="col-6" align="left">'+
											'<p align="center">_______________________<br>'+
											'Receiver</p>'+
										'</div>'+
									'</div>'+
									'<div class="col-12 float-left print_now print_ready" align="center" hidden>'+
										'<hr>'+
										'<span style="font-size:12px;">บริษัท เต็นท์โฮเทลรีเซอเวชั่นซิสเตมส์ จำกัด 235/233 ถนนพัฒนาการ เขตสวนหลวง แขวงสวนหลวง กรุงเทพมหานคร 10250 โทร 0830884161 อีเมล Tent.Reservation@tni.ac.th</span>'+
									'</div>'+
									'<div class="col-12 hide_print" align="center">'+
										'<div align="center"><button class="btn btn-success btn-sm" id="submit_print">'+
											'<i class="fas fa-print"></i> Print</button> <button class="btn btn-success btn-sm" id="submit_conCheckout_final"><i class="far fa-calendar-minus"></i> ดำเนินการต่อ</button>'+
										'</div>'+
									'</div>'+
								  '</div>'+
							  '</div>'+
							'</div>'+
							'</form>';

				$('#dialog_lg').addClass('mw-100',{duration:500});
				$('.concheckout').html(text2);
				$('.print_vat').html('('+$('#tax').val()+'%)');

				///////////Bill function////////////////
				var remove_same_room = 0;
				var room_from_db_count = 0;
				var i = 0;
				var j = 0;
				var typerecent = '';
				var qty = 1;
				var rate_discountset;
				var rate_discount;

				//สรุปบิล
				$.each(JSON.parse(data), function(key3,value3){
					b=i-1;
					if(typerecent =='' && value3.Type != typerecent){
						qty = 1;
				      	$('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html(i+1);
				      	$('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');

						$('tr[id="addr'+j+'"]').find('input[name="product[]"]').val(value3.Type);
						$('tr[id="addr'+j+'"]').find('input[name="qty[]"]').val(qty);
						$('tr[id="addr'+j+'"]').find('input[name="price[]"]').val(parseFloat(value3.Price).toLocaleString());
						$('tr[id="addr'+j+'"]').find('input[name="total[]"]').addClass('roomonly');
					}
					else if(typerecent != ''){
						if(value3.Type != typerecent && i <= (value3.h_bill_rooms-remove_same_room)){
							qty = 1;
					      	$('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html(i+1);
					      	$('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');

							$('tr[id="addr'+j+'"]').find('input[name="product[]"]').val(value3.Type);
							$('tr[id="addr'+j+'"]').find('input[name="qty[]"]').val(qty);
							$('tr[id="addr'+j+'"]').find('input[name="price[]"]').val(parseFloat(value3.Price).toLocaleString());
							$('tr[id="addr'+j+'"]').find('input[name="total[]"]').addClass('roomonly');
						}
						else if (value3.Type == typerecent){
							remove_same_room ++;
							qty++;
							room_from_db_count--;
							j--;
							i--;
							$('tr[id="addr'+j+'"]').find('input[name="qty[]"]').val(qty);
						}
					}
					typerecent = value3.Type;
					room_from_db_count++;
					j++;
					i++;
					rate_discountset = value3.h_rate_discountset;
					rate_discount = value3.h_rate_discount;
					calc(rate_discountset);
				});

				//Rate and Discount add table
				$('#discount').val(parseFloat(-rate_discount).toLocaleString());
				$('#discount').width($('#discount').val().length*8);

				display_set();
				calc(rate_discountset,rate_discount);
			
				///////////////////////////
				//var i=1;
			    $("#add_row").click(function(e){
			    	e.preventDefault();
			    	//i = test();
			    	b=i-1;

			    	$('#addr'+i).html($('#addr0').html()).find('td:first-child').html(i+1);
			    	$('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
			    	$('#addr'+i).find('.btndel').html('<button class="btn btn-sm btn-danger delete_row2 sm-font-admin" data-id="'+i+'">Delete</button>');
			    	$('tr[id="addr'+i+'"]').find('input[name="total[]"]').removeClass('roomonly');
			    	$('tr[id="addr'+i+'"]').find('input[name="total[]"]').addClass('other');
			    	i++;

			    	//ลบแถวที่ค้างอยู่ใน html หลังจากกดปุ่มลบไปแล้ว
			    	for(var k = 0; k<i;k++){
			    		if($('#addr'+k).html() == ""){
				    		$('#addr'+k).remove();
				    	}
			    	}
			      	delete_row_btn(rate_discountset,rate_discount,i);
			  	});

			    $("#delete_row").click(function(e){
			    	e.preventDefault();
			    	if(i>room_from_db_count){
					$("#addr"+(i-1)).html('');
					i--;
					}
					calc(rate_discountset,rate_discount);
				});

				$('#tab_logic tbody').on('keyup change',function(){
					calc(rate_discountset,rate_discount);
				});
				$('#tax').on('keyup change',function(){
					calc(rate_discountset,rate_discount);
				});

				$('#submit_conCheckout_final').click(function (e) {
					e.preventDefault();
					var saveall_arr = {
							date_print: date_today,
							sub_total: $('#sub_total').val().replace(',',''),
							discount: rate_discount,
							discount_type: rate_discountset,
							total: $('#sub_total_dis').val().replace(',',''),
							vat: $('#tax').val().replace(',',''),
							vat_amount: $('#tax_amount').val().replace(',',''),
							grand_total: $('#total_amount').val().replace(',',''),
							rate_select: ratename,
							session: session,
							checkin: checkin,
							checkout: checkout,
							firstname: firstname,
							lastname: lastname,
							id: id
						};
					var roomonly_price = 0;
					saveall_arr["product_list"] = [];
					saveall_arr["company_info"] = [];
					$('#tab_logic tbody tr').each(function(i, element) {
						var html = $(this).html();
						if(html!='')
						{
							//สร้าง JSON Array
							var saveall = {
									product: $(this).find('.product').val(),
									qty: $(this).find('.qty').val().replace(',',''),
									price: $(this).find('.price').val().replace(',',''),
									total: $(this).find('.total').val().replace(',','')
								};
							saveall_arr["product_list"].push(saveall);

							if($(this).find('.total').hasClass("roomonly")){
								roomonly_price += parseFloat($(this).find('.roomonly').val().replace(',',''));
							}
						}
				    });

				    //คำนวณค่าห้องเท่านั้น เอาไปลบกับผลรวมทั้งหมด
					if(rate_discountset == "Percent"){
						roomonly_price = roomonly_price - ((parseFloat(rate_discount)/100)*roomonly_price);
					}
					else if(rate_discountset == "Bath"){
						roomonly_price = roomonly_price - (parseFloat(rate_discount));
					}
					roomonly_price = roomonly_price + (roomonly_price/100*parseFloat($('#tax').val().replace(',','')));
					var other_price = $('#total_amount').val().replace(',','')-roomonly_price;

					var saveall_company = {
							company_tax_payer_no: $('#tax_pay_no').html(),
							company_name: $('#company_name').html(),
							company_address: $('#company_address').html(),
							company_tel: $('#company_tel').html(),
							company_email: $('#company_email').html()
						};
					saveall_arr ["company_info"].push(saveall_company);

					$.ajax({
						url:"../system/admin_cmd.php",
						method:"POST",
						data:{
							command: "billConCheckout_fromAdmin",
							cusId: id,
							cusFname: firstname,
							cusLname: lastname,
							cusSession: session,
							saveall: JSON.stringify(saveall_arr),
							other_price: other_price,
							total_price: $('#total_amount').val().replace(',','')
						},
						success:function(data){
							$('.concheckout').html(data);
						}
					});
				});
				$( '#submit_print' ).unbind('click');
				$('#submit_print').click(function (e) {
					e.preventDefault();
					printThis('.print_now');
				});
			}
		});
	});
}

function Receipt_history(ref,id){
	$.ajax({
		url: "../system/search_cmd.php",
		method: "POST",
		type: "JSON",
		data:{
			command: "receipt_history",
			id: id
		},
		success:function(data){
			var json = JSON.parse(data);
			var company_name, company_tax_payer_no, company_address, company_tel, company_email;

			$.each(json.company_info,function(key, value){
				company_name = value.company_name;
				company_tax_payer_no = value.company_tax_payer_no;
				company_address = value.company_address;
				company_tel = value.company_tel;
				company_email = value.company_email;
			});

			var text2 = '<form id="saveall">'+
						'<div class="container sm-font-admin">'+
						  '<div class="row print_history">'+
						    '<div class="col-md-12">'+
							    '<div class="col-12 row">'+
							    	'<div class="col-12" align="center"><h1>Receipt</h></div>'+
							    	'<div class="col-12 col-md-7" style="padding-top:10px;">'+
									    '<div><b>Receip No.</b> '+json.id+'-'+json.session+'</div>'+
									    '<div><b>Date: </b> '+json.date_print+'</div><br>'+
									    '<div><b>Customer name:</b> '+json.firstname+' '+json.lastname+'</div>'+
									    '<div><b>Check in:</b> '+json.checkin+' <b>Check out:</b> '+json.checkout+'</div>'+
									    '<div><b>Rate select:</b> '+json.rate_select+'</div>'+
								    '</div>'+
								    '<div class="col-5 bill-watermark d-md-block d-none">'+
										'<div class="float-right" style="font-size: 12px;">'+
											'<img class="bill-watermark-img" src="../img/_page/logo_email.png"><br>'+
											'Tax Payer No. <span id="tax_pay_no">'+company_tax_payer_no+'</span><br>'+
											'<span id="company_name">'+company_name+'</span><br>'+
											'<span id="company_address">'+company_address+'</span><br>'+
											'<i class="fas fa-phone"></i> <span id="company_tel">'+company_tel+'</span> | <i class="fas fa-envelope"></i> <span id="company_email">'+company_email+'</span>'+
										'</div>'+
									'</div>'+
						    	'</div><hr>'+
						      '<table class="table table-bordered table-hover sm-font-admin" id="tab_logic">'+
						        '<thead>'+
						          '<tr>'+
						            '<th class="text-center" hidden> # </th>'+
						            '<th class="text-center"> Product </th>'+
						            '<th class="text-center"> Qty </th>'+
						            '<th class="text-center"> Price </th>'+
						            '<th class="text-center"> Total </th>'+
						          '</tr>'+
						        '</thead>'+
						        '<tbody>'+
						          '<tr id="addr0">'+
						            '<td hidden>1</td>'+
						            '<td><input type="text" name="product[]"  placeholder="Enter Product Name" class="form-control product checksize sm-font-admin" readonly/></td>'+
						            '<td><input type="text" name="qty[]" placeholder="Enter Qty" class="form-control qty print_text_center checksize sm-font-admin" step="0" min="0" value="0" readonly/>'+
						            '<td><input type="text" name="price[]" placeholder="Enter Unit Price" class="form-control price print_text_center checksize sm-font-admin" step="0.00" min="0" value="0" readonly/>'+
						            '<td><input type="text" name="total[]" placeholder="0.00" class="form-control total print_text_center checksize sm-font-admin" readonly/>'+
						            '</td>'+
						          '</tr>'+
						        '</tbody>'+
						      '</table>'+
						    '</div>'+
						  '</div>'+
						  '<div class="row">'+
							  '<div class="col-12 margin-zero">'+
							  	'<div class="row col-12 print_history margin-zero" style="margin-top:20px">'+
							    	'<div class="col-12 margin-zero">'+
							    		'<div class="col-24 col-md-5 float-right zoom_main">'+
									      '<table class="table table-bordered fit" id="tab_logic_total">'+
									        '<tbody>'+
									          '<tr>'+
									            '<th class="text-right align-middle sm-font-admin">Sub Total</th>'+
									            '<td class="text-center">'+
									            	'<div class="input-group col-10 mb-2 mb-sm-0 zoom"><input type="text" name="sub_total" placeholder="0.00" class="form-control number_width print_text_right sm-font-admin" id="sub_total" readonly/>'+
									            		'<div class="input-group-prepend">'+
															'<div class="input-group-text">&#3647;</div>'+
														'</div>'+
													'</div>'+
												'</td>'+
									          '</tr>'+
									          '<tr>'+
									            '<th class="text-right align-middle sm-font-admin">Discount<br><span style="font-size:12px;">(Room only)</span></th>'+
									            '<td class="text-center">'+
									            	'<div class="input-group col-10 mb-2 mb-sm-0 zoom"><input type="text" name="discount" placeholder="0.00" class="form-control number_width print_text_right sm-font-admin" id="discount" readonly/>'+
									            		'<div class="input-group-prepend">'+
															'<div class="input-group-text money"></div>'+
														'</div>'+
													'</div>'+
												'</td>'+
									          '</tr>'+
									          '<tr>'+
									            '<th class="text-right align-middle sm-font-admin">Total</th>'+
									            '<td class="text-center">'+
									            	'<div class="input-group col-10 mb-2 mb-sm-0 zoom"><input type="text" name="sub_total_dis" placeholder="0.00" class="form-control number_width print_text_right sm-font-admin" id="sub_total_dis" readonly/>'+
									            		'<div class="input-group-prepend">'+
															'<div class="input-group-text">&#3647;</div>'+
														'</div>'+
													'</div>'+
												'</td>'+
									          '</tr>'+
									          '<tr class="hide_print">'+
									            '<th class="text-right align-middle sm-font-admin">Vat</th>'+
									            '<td class="text-center">'+
									            	'<div class="input-group col-7 mb-2 mb-sm-0">'+
									                	'<input type="number" class="form-control number sm-font-admin" id="tax" placeholder="0" value="'+json.vat+'" readonly>'+
									                	'<div class="input-group-prepend">'+
															'<div class="input-group-text">%</div>'+
														'</div>'+
									              	'</div>'+
									            '</td>'+
									          '</tr>'+
									          '<tr>'+
									            '<th class="text-right align-middle sm-font-admin">Vat Amount <span class="print_ready print_vat" hidden></span></th>'+
									            '<td class="text-center">'+
									            	'<div class="input-group col-10 mb-2 mb-sm-0 zoom"><input type="text" name="tax_amount" id="tax_amount" placeholder="0.00" class="form-control number print_text_right sm-font-admin" readonly/>'+
									            		'<div class="input-group-prepend">'+
															'<div class="input-group-text">&#3647;</div>'+
														'</div>'+
													'</div>'+
												'</td>'+
									          '</tr>'+
									         '<tr>'+
									            '<th class="text-right align-middle sm-font-admin">Grand Total</th>'+
									            '<td class="text-center">'+
									            	'<div class="input-group col-10 mb-2 mb-sm-0 zoom"><input type="text" name="total_amount" id="total_amount" placeholder="0.00" class="form-control number_width print_text_right sm-font-admin" readonly/>'+
									            		'<div class="input-group-prepend">'+
															'<div class="input-group-text">&#3647;</div>'+
														'</div>'+
													'</div>'+
												'</td>'+
									          '</tr>'+
									        '</tbody>'+
									      '</table>'+
									    '</div>'+
									'</div>'+
								'</div>'+
								'<div class="col-12 float-left print_history print_ready" align="left" hidden>'+
									'<div class="col-6" align="left">'+
										'<p align="center">_______________________<br>'+
										'Receiver</p>'+
									'</div>'+
								'</div>'+
								'<div class="col-12 float-left print_history print_ready" align="center" hidden>'+
									'<hr>'+
									'<span style="font-size:12px;">บริษัท เต็นท์โฮเทลรีเซอเวชั่นซิสเตมส์ จำกัด 235/233 ถนนพัฒนาการ เขตสวนหลวง แขวงสวนหลวง กรุงเทพมหานคร 10250 โทร 0830884161 อีเมล Tent.Reservation@tni.ac.th</span>'+
								'</div>'+
								'<div class="col-12 hide_print" align="center">'+
									'<div align="center"><button class="btn btn-success btn-sm" id="submit_print">'+
										'<i class="fas fa-print"></i> Print</button>'+
									'</div>'+
								'</div>'+
							  '</div>'+
						  '</div>'+
						'</div>'+
						'</form>';
			$('#dialog_lg_receipt').addClass('mw-100',{duration:500});
			$('.receipt').html(text2);
			$('.print_vat').html('('+$('#tax').val()+'%)');


			///////////Bill function////////////////
			var i = 0;
			var j = 0;
			var rate_discountset;
			var rate_discount;

			//สรุปบิล
			$.each(json.product_list, function(key3,value3){
				b=i-1;
			      	$('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html(i+1);
			      	$('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');

					$('tr[id="addr'+j+'"]').find('input[name="product[]"]').val(value3.product);
					$('tr[id="addr'+j+'"]').find('input[name="qty[]"]').val(value3.qty);
					$('tr[id="addr'+j+'"]').find('input[name="price[]"]').val(parseFloat(value3.price).toLocaleString());
					$('tr[id="addr'+j+'"]').find('input[name="total[]"]').addClass('roomonly');
				j++;
				i++;
				calc(json.discount_type);
			});

			//Rate and Discount add table
			$('#discount').val(parseFloat(-json.discount).toLocaleString());
			$('#discount').width($('#discount').val().length*8);

			display_set();
			calc(json.discount_type,json.discount);
			$('#submit_print').unbind('click');
			$('#submit_print').click(function (e) {
				e.preventDefault();
				printThis('.print_history');
			});
		},
		error:function(){alert('error')}
	});
}

function delete_row_btn(rate_discountset,rate_discount,i){
	$(".delete_row2").click(function(){
		$("#addr"+$(this).data('id')).html('');
		i= $(this).data('id');
		calc(rate_discountset,rate_discount);
	});
}

function returnToDefault_print(){
	$('.print_ready').prop('hidden',true);
	$('.print_text_right, .print_text_center').css('text-align','');
	$('input').css({'border-color':'','background-color':''});
	$('.hide_print').prop('hidden',false);
	$('#tab_logic_total').removeClass('table-custom');
	$('#tab_logic_total').addClass('table-bordered');
	$('.input-group-text').css({'border-color':'','background-color':''});
	$('#total_amount').css('border-bottom','');
}

function Cancel(ref,id,session,firstname,lastname){
	var text = 	'<b>ยกเลิกรายการ</b><br>'+id+'-'+session+'<br>'+
				'<b>Customer name:</b> '+firstname+' '+lastname+'<hr>'+
				'<button class="btn btn-danger btn-sm" id="submit_cancel"><i class="far fa-calendar-times"></i> ยกเลิกรายการนี้</button>';
	$('.cancheckinmodal').modal('show');
	$('.cancheckin').html(text);

	//ส่งค่าไปให้ Model Submit Update info
	$(document).on("click", "#submit_cancel", function () {
		$.ajax({
			url:"../system/admin_cmd.php",
			method:"POST",
			data:{
				command: "billCancel_fromAdmin",
				cusId: id,
				cusFname: firstname,
				cusLname: lastname,
				cusSession: session
			},
			success:function(data){
				$('.cancheckin').html(data);
			}
		});
	});
}

function checkstatus(){
	var cusid;
   	$('.statuscheck[data-status="1"]').each(function(){
    	cusid = $(this).data('cusid');
    	$('.ConCheckoutPop[data-id="'+cusid+'"]').prop('hidden',true);
    });

    $('.statuscheck[data-status="2"]').each(function(){
    	cusid = $(this).data('cusid');
    	$('.UpdatePop[data-id="'+cusid+'"]').prop('hidden',true);
    	$('.ConCheckinPop[data-id="'+cusid+'"]').prop('hidden',true);
    	$('.CanCheckinPop[data-id="'+cusid+'"]').prop('hidden',true);
    	$('.ConCheckoutPop[data-id="'+cusid+'"]').prop('hidden',false);
    });
}

function calc(rate_discountset,rate_discount){

	$('#tab_logic tbody tr').each(function(i, element) {
		var html = $(this).html();
		if(html!='')
		{
			var qty = parseFloat($(this).find('.qty').val().replace(/,/g,''));
			var price = parseFloat($(this).find('.price').val().replace(/,/g,''));
			if(isNaN(qty) || isNaN(price)){
				$(this).find('.total').val(parseFloat(0).toLocaleString());
			}
			if(isNaN(price)){
				$(this).find('.price').val(0);
			}
			if(isNaN(qty)){
				$(this).find('.qty').val(0);
			}
			else{
				$(this).find('.total').val(parseFloat(qty*price).toLocaleString());
			}
			calc_total(rate_discountset,rate_discount);
		}
		display_set();
    });
}

function calc_total(rate_discountset,rate_discount){
	total=0;
	total_roomonly=0;

	//หาผลรวมทั้งหมด
	$('.total').each(function() {
        total += parseFloat($(this).val().replace(/,/g,''));
    });

	//หาผลรวมเฉพาะห้องพัก
    $('.roomonly').each(function(){
    	total_roomonly += parseFloat($(this).val().replace(/,/g,''));
    });

    $('#sub_total').val(parseFloat(total.toFixed(2)).toLocaleString());

    if(rate_discountset == "Bath"){
    	total = total - parseFloat(rate_discount);
    	$('.money').html('&#3647;');
    }
    else if(rate_discountset == "Percent"){
    	total = total - (total_roomonly - ((parseFloat(rate_discount)/100)*total_roomonly));
    	$('.money').html('%');
    }
    else if(rate_discountset == "None"){
    	total = total;
    	$('.money').html('');
    	$('#discount').val('-');
    }
    
	$('#sub_total_dis').val(parseFloat(total).toLocaleString());
	tax_sum=total/100*$('#tax').val();
	$('#tax_amount').val(parseFloat(tax_sum.toFixed(2)).toLocaleString());
	$('#total_amount').val(parseFloat((tax_sum+total).toFixed(2)).toLocaleString());
}

function display_set(){

	var checksize_arr = [];
	var sum_textbox_width = $('.number_width').val().length;

	$('.checksize').each(function(){
		if($(this).val().length >= 20){
			$('.price, .total, .qty').css('font-size','13px');
			$('.print_price, .print_total, .print_qty').css('font-size','13px');
			checksize_arr.push("overSize");
		}
	});
	if(jQuery.inArray("overSize", checksize_arr) == -1){
			$('.price, .total, .qty').css('font-size','1rem');
			$('.print_price, .print_total, .print_qty').css('font-size','1rem');
	}

	$('.product').each(function(){
		if($(this).val().length >= 20){
			$('.product').css('font-size','13px');
			checksize_arr.push("overSize_name");
		}
	});
	if(jQuery.inArray("overSize_name", checksize_arr) == -1){
			$('.product').css('font-size','1rem');
	}

	//add space
	if(sum_textbox_width < 10){
		$('.zoom_main').removeClass('col-6');
		$('.zoom_main').removeClass('col-7');
		$('#tab_logic_total').find('.zoom').removeClass('col-12');
	}
	if(sum_textbox_width >=  10 && sum_textbox_width <  13){
		$('.zoom_main').removeClass('col-6');
		$('#tab_logic_total').find('.zoom').addClass('col-12');
	}
	if(sum_textbox_width >=  14 && sum_textbox_width <  20){
		$('.zoom_main').removeClass('col-7');
		$('.zoom_main').addClass('col-6');
	}
	if(sum_textbox_width >=  21){
		$('.zoom_main').addClass('col-7');
	}
}

function printThis(element){
	$('.print_ready').prop('hidden',false);
	$('.print_text_right').css('text-align','right');
	$('.print_text_center').css('text-align','center');
	$('input').css({'border-color':'white','background-color':'transparent'});
	$('.hide_print').prop('hidden',true);
	$('#tab_logic_total').removeClass('table-bordered');
	$('.input-group-text').css({'border-color':'white','background-color':'transparent'});
	$('#total_amount').css('border-bottom','4px double');
	$(element).printThis({
	    debug: false,               // show the iframe for debugging
	    importCSS: true,            // import parent page css
	    importStyle: true,         // import style tags
	    printContainer: true,       // print outer container/$.selector
	    loadCSS: "",                // path to additional css file - use an array [] for multiple
	    pageTitle: "",              // add title to print page
	    removeInline: false,        // remove inline styles from print elements
	    removeInlineSelector: "*",  // custom selectors to filter inline styles. removeInline must be true
	    printDelay: 333,            // variable print delay
	    header: null,               // prefix to html
	    footer: null,               // postfix to html
	    base: false,                // preserve the BASE tag or accept a string for the URL
	    formValues: true,           // preserve input/form values
	    canvas: true,              // copy canvas content
	    doctypeString: '',       // enter a different doctype for older markup
	    removeScripts: false,       // remove script tags from print content
	    copyTagClasses: false,      // copy classes from the html & body tag
	    beforePrintEvent: null,     // function for printEvent in iframe
	    beforePrint: null,          // function called before iframe is filled
	    afterPrint: returnToDefault_print            // function called before iframe is removed
	});
}

//////today status///////
function today_status(){
	$('#display_today').html('<div id="loading"><h5><i class=\"fas fa-spinner fa-spin\"></i>Loading .....</h5></div>');
	$.ajax({
		url: "../system/search_cmd.php",
		type: "json",
		method: "POST",
		data:{
			command: "search_booklist_today"
		},
		success:function(data){
			var html;
			html = 	'<table class="table" style="font-size: 14px; text-align:center;>'+
				    	'<thead>'+
				    		'<tr class="text-center">'+
				    			'<th>ประเภทห้อง</th>'+
				    			'<th>จำนวนห้องที่ต้องCheck out</th>'+
				    			'<th class="text-left">จำนวนห้องว่าง</th>'+
				    		'</tr>';

			$.each(JSON.parse(data),function(key, value){
				html += '<tr>'+
					    	'<td class="text-left">'+value.h_type_name+'</td>'+
					    	'<td class="text-center">'+value.summary_of_checkout+'</td>';
					    	if(value.summary_of_empty != 0){
					    		html+= '<td class="text-left text-success">';
					    	}
					    	else{
					    		html+= '<td class="text-left text-danger">';
					    	}
				html +=	    value.summary_of_empty;

							if(value.session > 0){
								html+= ' <span class="badge badge-pill badge-secondary"> '+value.session+' ห้องกำลังถูกเลือก <span class="spinner-grow text-light" role="status" style="width: 1rem; height: 1rem;"></span> </span>';
							}
				html +=		'</td>'+
					    '</tr>';
			});
			html += '</thead></table>';
			$('#display_today').html('');
			$(html).appendTo('#display_today');

		},
		error:function(){alert('error');}
	});
}