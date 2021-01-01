/*
	สำหรับหน้าที่จัดการข้อมูลด้วยตาราง

	การประกาศใช้ตัวแปรเพื่อส่งค่า

	** Note: หากต้องการใใช้กับหน้าที่ไม่มีการลบข้อมูล ให้ประกาศ ตัวแปรใน js ที่เรียกใช้อันดับแรกของหน้านั้น ด้วย var request_type = "Booking" **
	** Note: Booking จะใช้ Confirmbtn เดียวกัน เพื่อลดความซับซ้อนของ code **

	- Edit, Delete one item

		var send_data = {
				id: id, 								//ข้อมูลที่จะใช้ในการดำเนินการ เช่น ไอดีที่จะแก้ไข
				command_search: 'search_rank_Info', 	//command ที่ใช้เพื่อเลือกสิ่งที่จะทำ php
				form: '#rankEditForm', 					//ฟอร์มใน modal ที่จะแสดงผล
				modal: '.rank_editmodel', 				//modal ที่อ้างอิง
				from_table: 'h_userrank', 				//เลือก table ที่ติดต่อ database
				where_field: 'h_userrank_id'			//เลือก field ที่ติดต่อ database
				image_field: '.............'			//เลือก field ที่เก็บ URL รูปภาพ เพื่อลบออกจากเซิฟเวอร์
			};

	- Delete more select

		var send_data = {
				group_id: selected, 					//group_ ต้องใช้ getFilterData_dml เพื่อเก็บค่าเป็น Array
				group_name: selected_name,
				form: '#deleteMoreRankForm',
				modal: '.rank_deleteMoremodel'
			};

	- Confirm Delete more (เรียกใช้เมื่อกดปุ่ม หรือเกิด event)

		var send_data = {
				form: '#deleteMoreRankForm',
				from_table: 'h_userrank',
				where_field: 'h_userrank_id'
				image_field: '.............'			//เลือก field ที่เก็บ URL รูปภาพ เพื่อลบออกจากเซิฟเวอร์
			};

	- Check Available (เช็คข้อมูลที่ซ้ำกัน, ใช้ร่วมกับfunction validate_same_dml เพื่อคืนค่าเป็น boolean)

		var send_data = {
				element: ".checksame",					//element ที่ต้องการเช็ค value
				from_table: "h_user",
				where_field: "h_user_username"
			};

	- getFilterData (เช็ค value ตามที่เลือก checkbox)

		var selected = getFilterData_dml('element','command_dml','data_select'); 

		//command_dml มี 'this', 'data(ถ้าเลือก ต้องใส่ชื่อเพิ่มด้วย)', 'ชื่อdataที่ต้องการ'

	- getFilter_checkbox_pagination_dml (เช็ค value ตามที่เลือก checkbox และจำค่าเมื่อเปลี่ยน pagination)

		var selected = getFilter_checkbox_pagination_dml('element','command_dml','data_select'); 
*/
var quote;
var class_confirmbtn;

if(request_type == null){
	var request_type = "default";
}

$(document).ready(function(){
	
	if(request_type == "booking"){
		quote = "จอง";
		class_confirmbtn = "confirmBookingMore";
		class_semiSelect = "confirmBooking";
		classbtn = "btn-info";
	}
	else{
		quote = "ลบ";
		class_confirmbtn = "confirmDeleteMore";
		class_semiSelect = "deleteConbtn";
		classbtn = "btn-danger";
	}
	
	//ปุ่ม ลบหลายรายการ
	$(document).on('click','#deleteMorebtn',function(){
		if($(this).hasClass('booking') && ($('#checkin2').val() == "" || $('#checkout2').val() == "")){
			alert('โปรดระบุวัน Checkin, Checkout');
		}
		else{
			$('#checkboxAll, .checkbox-delete, #selectDeleteMorebtn, #cancelDeleteMorebtn').prop('hidden',false);
			$('#deleteMorebtn, .hide_in_table').prop('hidden',true);
			$('#addbtn, .lock-while-delete').prop('disabled',true);
		}
	});
	//ปุ่ม เลือกทั้งหมด
	$(document).on('click','#checkboxAll',function(event){
		event.preventDefault();
		$('.checkbox-delete').prop('checked',true);
		$('#checkboxAll').prop('hidden',true);
		$('#unCheckboxAll').prop('hidden',false);
		if($('.checkbox-delete').hasClass('booking')){
			var selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination_booking','id');
		}else{
			var selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination','id');
		}
		$('#selectDeleteMorebtn').val(quote+' '+selected.length+' รายการ');
	});
	//ปุ่ม checkbox-delete
	$(document).on('click','.checkbox-delete',function(){
		if($('.checkbox-delete').hasClass('booking')){
			var selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination_booking','id');
		}else{
			var selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination','id');
		}
    	$('#selectDeleteMorebtn').val(quote+' '+selected.length+' รายการ');
	});
	//ปุ่ม ยกเลิกเลือกทั้งหมด
	$(document).on('click','#unCheckboxAll',function(event){
		event.preventDefault();
		$('.checkbox-delete').prop('checked',false);
		$('#checkboxAll').prop('hidden',false);
		$('#unCheckboxAll').prop('hidden',true);
		if($('.checkbox-delete').hasClass('booking')){
			var selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination_booking','id');
		}else{
			var selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination','id');
		}
		$('#selectDeleteMorebtn').val(quote+' '+selected.length+' รายการ');
	});
	//ปุ่ม ยกเลิกลบหลายรายการ
	$(document).on('click','#cancelDeleteMorebtn',function(){
		setBtn_cancel_dml();
		checkbox_get_global = [];
		checkbox_get_global_name = [];
		checkbox_get_global_price = [];
		checkbox_get_global_guests = [];
		checkbox_get_global_type = [];
		console.log('Recent_checkbox\n',checkbox_get_global);
	});
});

function send_to_generate_model_deleteMore_dml(send_data){
	var selected = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination','id');
	var selected_name = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination','name');
	console.log(selected);
	var ref = $(send_data.modal).attr('id', 'delete-more');
	var send_data = {
		group_id: selected,
		group_name: selected_name,
		form: send_data.form,
		modal: send_data.modal
	};
	if(selected.length > 0){
		generate_model_deleteMore_dml(send_data); //DML.js
	}
}

function generate_model_edit_dml(send_data){
	$.ajax({
		url:"../system/search_cmd.php",
		method: "POST",
		type: "JSON",
		data:{
			command:send_data.command_search,
			id:send_data.id
		},
		success:function(data){
			var html = '';
			var html_query = '';
			var html_query2 = '';
			$.each(JSON.parse(data),function(key,value){
				if(send_data.form == "#accountEditForm"){
					html +=	'<form action="#" method="" class="" id="sendAccountEditForm">'+
								'<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชื่อ</label></div>'+
								'<div class="col-6"><input name="FnameUP" id="FnameUP" placeholder="ชื่อจริง" class="form-control" value="'+value.h_user_firstname+'" required></div>'+
								'<div class="col-12" align="left"><label for="name" style=" text-align: left;">นามสกุล</label></div>'+
								'<div class="col-6"><input name="LnameUP" id="LnameUP" placeholder="นามสกุล" class="form-control" value="'+value.h_user_lastname+'" required></div>'+
								'<div class="col-12" align="left"><label for="rank" style=" text-align: left;">ตำแหน่ง</label></div>'+
								'<div class="col-6">'+
									'<select class="form-control rankUP" id="rankUP" name="rankUP">';
					         		$.getJSON("../system/query_rank.php",function(data){
										$.each(data, function(key2,value2){
											html_query+= ' <option value="'+value2.h_userrank_id+'"';
												if(value2.h_userrank_id == value.h_user_rankid){
														html_query += ' selected';
													}
											html_query+= '>'+value2.h_userrank_name+'</option>';
										});
										$(html_query).appendTo('.rankUP');
									});
					html+=    		'</select>'+
								'</div>'+
								'<hr>'+
								'<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชื่อบัญชีผู้ใช้</label></div>'+
								'<div class="col-6" id="user2" align="center"><input name="userUP" id="userUP" data-id="'+value.h_user_id+'" placeholder="Username" class="form-control checksame" value="'+value.h_user_username+'" required disabled></div>'+
					      		'<br>'+
								'<div class="col-12" align="left"><label for="name" style=" text-align: left;">รหัสผ่าน</label></div>'+
								'<div class="col-12 row" align="center">'+
									'<div class="input-group col-6">'+
										'<input name="generate_password" id="generate_password" placeholder="รหัสผ่านใหม่" class="form-control" readonly>'+
										'<div class="input-group-prepend">'+
											'<div class="btn btn-copy input-group-text badge-textbox-black">Copy</div>'+
										'</div>'+
									'</div>'+
									'<div class="col-6" align="left">'+
										'<button class="btn btn-warning resetpass"><i class="fas fa-sync"></i> รีเซ็ตรหัสผ่าน</button>'+
									'</div>'+
								'</div>'+
								'<br>'+
								'<hr>'+
								'<input type="submit" value="บันทึก" class="btn btn-success Update_account"><br><br>'+
								'<span id="successAlert" class="text-success"></span>'+
							'</form>';
				}
				else if(send_data.form == "#rankEditForm"){
					html += '<div class="col-12" align="left"><label for="rank_nameUP" style=" text-align: left;">ชื่อตำแหน่ง</label></div>'+
							'<div class="col-6"><input name="rank_nameUP" id="rank_nameUP" placeholder="ชื่อตำแหน่ง" class="form-control" value="'+value.h_userrank_name+'" required></div>'+
							'<div class="col-12" align="left"><label for="permission" style=" text-align: left;">สิทธิการใช้งาน</label></div>'+
							'<div class="form-check form-check-inline">'+
								'<input class="form-check-input permission_selectUP" type="radio" name="permission_selectUP" id="permission_lockUP" value="1"';
									if(value.h_userrank_permission == 1){
										html+= ' checked';
									}
					html +=		'>'+
								'<label class="form-check-label" for="permission_lockUP">จำกัด</label>'+
							'</div>'+
							'<div class="form-check form-check-inline">'+
								'<input class="form-check-input permission_selectUP" type="radio" name="permission_selectUP" id="permission_unlockUP" value="2"';
									if(value.h_userrank_permission == 2){
										html+= ' checked';
									}
					html +=		'>'+
								'<label class="form-check-label" for="permission_unlockUP">ไม่จำกัด</label>'+
							'</div>'+
							'<hr>'+
							'<input type="submit" value="บันทึก" class="btn btn-success Update_rank" data-id="'+value.h_userrank_id+'"><br><br>'+
							'<span id="successAlert" class="text-success"></span>';
				}
				else if(send_data.form == "#roomSettingEditForm"){
					html += '<div class="col-12" align="left"><label for="name" style=" text-align: left;">หมายเลขห้อง</label></div>'+
										'<div class="col-6" align="center"><input value="'+value.h_room_name+'" id="roomnoUP" class="form-control" disabled></div><br>'+
										'<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชนิดห้อง</label></div>'+
										'<div class="col-6">'+
											'<select class="form-control" id="typeUP">';

											$.getJSON("../system/query_type.php",function(data){
												$.each(data, function(key2,value2){
													html_query+= ' <option value="'+value2.h_type_id+'"';
														if(value2.h_type_id == value.h_room_type){
																html_query += ' selected';
															}
													html_query+= '>'+value2.h_type_name+'</option>';
												});
												$(html_query).appendTo('#typeUP');
											});

    				html +=				'</select>'+
    									'</div>'+
										'<div class="col-12" align="left"><label for="name" style=" text-align: left;">สถานะ</label></div>'+
										'<div class="col-6" align="center">'+
											'<select class="form-control pay" id="statusUP">';

											$.getJSON("../system/query_status.php",function(data){
												$.each(data, function(key3,value3){
													html_query2+= ' <option value="'+value3.h_status_id+'"';
														if(value3.h_status_id == value.h_room_status){
																html_query2 += ' selected';
															}
													html_query2+= '>'+value3.h_status_name+'</option>';
												});
												$(html_query2).appendTo('#statusUP');
											});
    				html +=					'</select>'+
										'</div><br>'+
										'<hr>'+
										'<input type="submit" data-id="'+value.h_room_id+'" value="บันทึก" class="btn btn-success Update_room"><br><br>'+
										'<span id="successAlert" class="text-success"></span>';
				}
				else if(send_data.form == "#bedEditForm"){
					html_query2 = value.h_type_bed_image;
					html += '<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชื่อประเภทเตียง</label></div>'+
						      	'<div class="col-12 col-md-6"><input id="bed_nameUP" placeholder="ชื่อประเภทเตียง" class="form-control" value="'+value.h_type_bed_name+'" required></div>'+
						      	'<br>'+
						      	'<div class="col-12" align="left"><label for="name" style=" text-align: left;">รายละเอียด</label></div>'+
						      	'<div class="col-12 col-md-6" align="center"><input id="bed_descUP" placeholder="รายละเอียด" class="form-control" value="'+value.h_type_bed_desc+'" required></div>'+
						      	'<hr>'+
						      	'<div class="col-12" align="left"><label for="name" style=" text-align: left;">ไอคอน</label></div>'+
						      	'<div class="col-12 row">'+
						      		'<div class="col-2" align="left"><img class="imgPreview" height="30px" src=""/></div>'+
							      	'<div class="col-10 col-md-6" align="left" class="custom-file" style="margin-bottom: 10px;">'+
							      		'<input type="file" id="fileUpload" class="custom-file-input upload_edit" data-command="upload_icon_edit" data-element=".imgPreview" accept="image/*">'+
							      		'<label class="custom-file-label filename_edit" for="customFile">เลือกไฟล์</label>'+
							      	'</div>'+
							      	'<div class="col-12 col-md-4">'+
							      		'<button class="btn btn-sm btn-danger" id="btnDelete_image_bed" type="button" data-element=".imgPreview"><i class="fas fa-trash-alt"></i> ลบไอคอน</button>'+
							      	'</div>'+
						      	'</div>'+
						      	'<br>'+
						      	'<hr>'+
						      	'<div align="center">'+
							      	'<input type="submit" value="บันทึก" class="btn btn-success Update_bed" data-id="'+value.h_type_bed_id+'" data-command="upload_icon_edit" data-element=".imgPreview"><br><br>'+
									'<span id="successAlert" class="text-success"></span>'+
						      	'</div>'
				}
				else if(send_data.form == "#statusEditForm"){
					html += '<div class="col-12" align="left"><label for="status_nameUP" style=" text-align: left;">ชื่อสถานะ</label></div>'+
							'<div class="col-6"><input name="status_nameUP" id="status_nameUP" placeholder="ชื่อสถานะใหม่" class="form-control" value="'+value.h_status_name+'" required></div>'+
							'<div class="col-12" align="left"><label for="permission" style=" text-align: left;">สิทธิการจอง</label></div>'+
							'<div class="form-check form-check-inline">'+
								'<input class="form-check-input status_selectUP" type="radio" name="status_select" id="status_openUP" value="1"';
									if(value.h_status_statustouser == 1){
										html+= ' checked';
									}
					html +=		'>'+
								'<label class="form-check-label" for="status_openUP">เปิด</label>'+
							'</div>'+
							'<div class="form-check form-check-inline">'+
								'<input class="form-check-input status_selectUP" type="radio" name="status_select" id="status_closeUP" value="2"';
									if(value.h_status_statustouser == 2){
										html+= ' checked';
									}
					html +=		'>'+
								'<label class="form-check-label" for="status_closeUP">ปิด</label>'+
							'</div>'+
							'<div class="col-12" align="left"><label for="status_color" style=" text-align: left;">สีสถานะ</label></div>'+
							'<div class="col-12" align="center">'+
								'<div class="col-5 row" align="center">'+
									'<div class="col-3 colorPickSelector"></div>'+
									'<div class="col-5 color_code_edit justify-content-center align-self-center"></div>'+
								'</div>'+
							'</div>'+
							'<hr>'+
							'<input type="submit" value="บันทึก" class="btn btn-success Update_status" data-id="'+value.h_status_id+'"><br><br>'+
							'<span id="successAlert" class="text-success"></span>';
					html_query += value.h_status_color;
				}
			});
			$(send_data.form).html(html);
			$(send_data.modal).modal('show');
			if(send_data.form == "#bedEditForm"){
				console.log(html_query2);
				if($('.imgPreview').attr('src') == ""){
					$('.imgPreview').attr('src',html_query2);
				}
				if($('.imgPreview').attr('src') == "" && html_query2 == ""){
					$('.imgPreview').attr('src','../system/upload_icon/404.png');
				}

				$(".upload_edit").change(function() {
					var element = $(this).data('element');
					readURL(this,element);
				});

				$('#btnDelete_image_bed').click(function(event){
					event.preventDefault();
				    var element = $(this).data('element');
				    $(element).attr('src','../system/upload_icon/404.png');
				    $('.filename_edit').html('เลือกไฟล์');
				    $('#fileUpload')[0].value = '';
				});
			}
			if(send_data.form == "#statusEditForm"){
				color_picker(html_query);
			}
		},
		error:function(){alert('err');}
	});
}

function generate_model_delete_dml(send_data){
	console.log(send_data);
	$.ajax({
		url:"../system/search_cmd.php",
		method: "POST",
		type: "JSON",
		data:{
			command: send_data.command_search,
			id: send_data.id
		},
		success:function(data){
			var html = '';
			$.each(JSON.parse(data),function(key,value){
				if(send_data.form == "#deleteRankForm"){
					html+= 	'<div class="col-12" align="center"><label for="name" style=" text-align: center;">ตำแหน่ง</label></div>'+
							'<div class="col-6" align="center"><input value="'+value.h_userrank_name+'" class="form-control" disabled></div>'+
							'<hr>'+
							'<input type="submit" value="ยืนยันการลบตำนแหน่งนี้" class="btn btn-danger '+class_semiSelect+'">';
				}
				else if(send_data.form == "#deleteAccountForm"){
					html+= 	'<div class="col-12" align="center"><label for="name" style=" text-align: center;">บัญชีผู้ใช้</label></div>'+
							'<div class="col-6" align="center"><input value="'+value.h_user_username+'" class="form-control" disabled></div>'+
							'<hr>'+
							'<input type="submit" value="ยืนยันการลบบัญชีนี้" class="btn btn-danger '+class_semiSelect+'">';
				}
				if(send_data.form == "#deleteRoomSettingForm"){
					html+= 	'<div class="col-12" align="center"><label for="name" style=" text-align: center;">หมายเลขห้อง</label></div>'+
							'<div class="col-6" align="center"><input value="'+value.h_room_name+'" class="form-control" disabled></div>'+
							'<hr>'+
							'<input type="submit" value="ยืนยันการลบห้องนี้" class="btn btn-danger '+class_semiSelect+'">';
				}
				if(send_data.form == "#deleteBedForm"){
					html+= 	'<div class="col-12" align="center"><label for="name" style=" text-align: center;">ประเภทเตียง</label></div>'+
							'<div class="col-6" align="center"><input value="'+value.h_type_bed_name+'" class="form-control" disabled></div>'+
							'<hr>'+
							'<input type="submit" value="ยืนยันการลบประเภทเตียงนี้" class="btn btn-danger '+class_semiSelect+'">';
				}
				if(send_data.form == "#deleteStatusForm"){
					html+= 	'<div class="col-12" align="center"><label for="name" style=" text-align: center;">สถานะห้อง</label></div>'+
							'<div class="col-6" align="center"><input value="'+value.h_status_name+'" class="form-control" disabled></div>'+
							'<hr>'+
							'<input type="submit" value="ยืนยันการลบสถานะห้องนี้" class="btn btn-danger '+class_semiSelect+'">';
				}
				if(send_data.form == "#bookingMoreRoomForm"){
					html+= 	'<div class="col-12" align="center"><label for="name" style=" text-align: center;">หมายเลขห้อง</label></div>'+
							'<div class="col-6" align="center"><input value="'+value.h_room_name+'" class="form-control" disabled></div>'+
							'<hr>'+
							'<input type="submit" value="ยืนยันการจองห้องนี้" class="btn btn-info '+class_semiSelect+'">';
					//ปุ่ม ส่งค่าที่เลือก (Booking)
					$(document).on("click", ".confirmBooking", function(){
					    var checkin = moment(new Date($('#checkin2').val())).format("Y-MM-DD");
					    var checkout = moment(new Date($('#checkout2').val())).format("Y-MM-DD");
					    console.log(
					    			'Room ID: '+value.h_room_id+
					    			'\nPrice: '+value.h_type_price+
					    			'\nGuests: '+value.h_type_capacity+
					    			'\nType: '+value.h_type_name
					    			);
					    $.ajax({
						    url:"../system/admin_cmd.php",
						    method:"POST",
						    dataType: "json",
						    data:{
						     	command: "bookcheckbox",
						    	roomid: [value.h_room_id],
						    	roomPrice: [value.h_type_price],
						    	checkin: checkin,
						    	checkout: checkout,
						    	guests: [value.h_type_capacity],
						    	type: [value.h_type_name]
						    },
						    success:function(data){
						    	$('#checkboxAll, #unCheckboxAll ,.checkbox-delete').prop('disabled', true);
						    	modal(data,checkin,checkout,value.h_room_id.length); 
						    }
						});
					});
				}
			});
			$(send_data.form).html(html);			
			$(send_data.modal).modal('show');

			//ส่งค่าไปให้ delete 
			$('.deleteConbtn').click(function(){
				$(send_data.form).html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Deleting .....</h5></div>');
				$.ajax({
					url:"../system/admin_cmd.php",
					method:"POST",
					type:"JSON",
					data:{
						command: "delete_dml",
						id: send_data.id,
						from_table: send_data.from_table,
						where_field: send_data.where_field,
						image_field: send_data.image_field
					},
					success:function(){
						$(send_data.form).html('ลบสำเร็จ');
						// $('#id-'+send_data.id).fadeTo(1000, 0.01, function(){ 
						//     $(this).slideUp(150, function() {
						//         $(this).remove(); 
						//     });
						// });
						get_data(thispage_is);
					},
					error:function(){alert('err');}
				});
			});
		},
		error:function(){alert('err');}
	});
}

function generate_model_deleteMore_dml(send_data){
	console.log(send_data);
	var html = '';
	var sentense;

	html += '<div class="col-6" align="center">'+quote+'ทั้งหมด '+send_data.group_id.length+' รายการ</div><br>'+
			'<div class="col-12" align="center">';
				for(var i = 0;i < send_data.group_id.length;i++){
					html += send_data.group_name[i]
					if(i < (send_data.group_id.length-1)){	
						html += ', ';
					}
				}
	html +=	'</div><hr>';
	if(send_data.form == "#deleteMoreAccountForm") sentense = 'ยืนยันการลบบัญชี';
	else if(send_data.form == "#deleteMoreRankForm") sentense = 'ยืนยันการลบตำแหน่ง';
	else if(send_data.form == "#deleteMoreRoomSettingForm") sentense = 'ยืนยันการลบห้อง';
	else if(send_data.form == "#bookingMoreRoomForm") sentense = 'ยืนยันการจองห้อง';
	else if(send_data.form == "#deleteMoreBedForm") sentense = 'ยืนยันการลบประเภทห้องพัก';
	else if(send_data.form == "#deleteMoreStatusForm") sentense = 'ยืนยันการลบสถานะห้อง';
	html += '<input type="submit" value="'+sentense+'" class="btn '+classbtn+' '+class_confirmbtn+'">';
	$(send_data.form).html(html);
	$(send_data.modal).modal('show');
}

function confirm_deleteMore_dml(send_data){
	var id = getFilter_checkbox_pagination_dml('.checkbox-delete','checkbox_pagination','id');
	$.ajax({
		url: "../system/admin_cmd.php",
		method: "POST",
		type: "JSON",
		data:{
			command: "deletemore_dml",
			from_table: send_data.from_table,
			where_field: send_data.where_field,
			image_field: send_data.image_field,
			id:id
		},
		success:function(data){
			$(send_data.form).html('ลบสำเร็จ');
			// for(var i = 0; i < id.length; i++){
			// 	$('#id-'+id[i]).fadeTo(1000, 0.01, function(){ 
			// 	    $(this).slideUp(150, function() {
			// 	        $(this).remove(); 
			// 	    });
			// 	});
			// }
			setBtn_cancel_dml();
			get_data(thispage_is);
			checkbox_get_global = [];
			checkbox_get_global_name = [];
			checkbox_get_global_price = [];
			checkbox_get_global_guests = [];
			checkbox_get_global_type = [];
		},
		error:function(){alert('err');}
	});
}
//เช็ค Checkbox array
function getFilterData_dml(element,command_dml,data_select) {
    var checkbox = [];
    $(element).each(function(){
    	if(command_dml == "this"){
    		checkbox.push($(this).val());
    	}
    	else if(command_dml == "data"){
    		checkbox.push($(this).data(data_select));
    	}
    });
    return checkbox;
}

function getFilter_checkbox_pagination_dml(element,command_dml,data_select){
	var result;
    		var checkbox_checked = [];
	    	var checkbox_uncheck = [];
	    	var checkbox_uncheck_name = [];
	    	var checkbox_uncheck_price = [];
			var checkbox_uncheck_guests = [];
			var checkbox_uncheck_type = [];
	    	$(element+':checked').each(function(){
	    		if($.inArray($(this).val(), checkbox_get_global) === -1){
	    			checkbox_get_global.push($(this).val());
	    			checkbox_get_global_name.push($(this).data('name'));
	    			if(command_dml == "checkbox_pagination_booking"){
	    				checkbox_get_global_price.push($(this).data('price'));
			    		checkbox_get_global_guests.push($(this).data('capacity'));
			    		checkbox_get_global_type.push($(this).data('type'));
	    			}
	    		} 
	    		checkbox_checked.push($(this).val());
	    	});

	    	$(element+':not(:checked)').each(function(){
	    		checkbox_uncheck.push($(this).val());
	    		checkbox_uncheck_name.push($(this).data('name'));
	    		if(command_dml == "checkbox_pagination_booking"){
		    		checkbox_uncheck_price.push($(this).data('price'));
		    		checkbox_uncheck_guests.push($(this).data('capacity'));
		    		checkbox_uncheck_type.push($(this).data('type'));
	    		}
	    	});
	    	console.log('Recent_checkbox before delete\n',checkbox_get_global,'\n',checkbox_get_global_name)
	    	$.each(checkbox_uncheck,function(key, value){
			    var index = checkbox_get_global.indexOf(value);
			    if (index > -1) {
			    	checkbox_get_global.splice(index, 1); //เอาvalue ใน array ออก
			    	checkbox_get_global_name.splice(index, 1);
			    	if(command_dml == "checkbox_pagination_booking"){
				    	checkbox_get_global_price.splice(index, 1);
			    		checkbox_get_global_guests.splice(index, 1);
			    		checkbox_get_global_type.splice(index, 1);
			    	}
			    }
	    	});
	    	console.log('Recent_checkbox this page\n',checkbox_checked,'\nAll\n',checkbox_get_global);
	    	if(data_select == 'id') result = checkbox_get_global;
	    	else if(data_select =='name') result = checkbox_get_global_name;
	    	else if(data_select =='price') result = checkbox_get_global_price;
	    	else if(data_select =='capacity') result = checkbox_get_global_guests;
	    	else if(data_select =='type') result = checkbox_get_global_type;
	return result;
};
//ยกเลิกล๊อคปุ่มขณะลบหลายรายการ
function setBtn_cancel_dml(){
	$('.unlock-while-delete').prop('disabled',false);
	$('.unlock-while-delete').prop('hidden',true);
	$('#deleteMorebtn, .hide_in_table').prop('hidden',false);
	$('.checkbox-delete').prop('checked',false);
	$('.lock-while-delete').prop('disabled',false);
	$('#selectDeleteMorebtn').val(quote);
}
//เช็คสิ่งที่ซ้ำกัน
function checkAvailability_dml(send_data){
  $(".status").html('<i id="loadicon" class="fas fa-spinner fa-spin"></i>');
  $.ajax({
	  url: "../system/admin_cmd.php",
	  type: "POST",
	  data:{
	    command: "checkSame_dml",
	    element: send_data.element,
	    value: $(send_data.element).val(),
	    from_table: send_data.from_table,
	    where_field: send_data.where_field
	  },
	  success:function(data){
	  	$(".same-validate").html(data);
	  },
	  error:function (){}
  });
}
function validate_same_dml(){
	var result = true;
	if($(".status-available:first").is("#notAvailable")){
		result = false;
	}
	return result;
}

function reset_modal_dml(modal,form){
	$(modal).on('hidden.bs.modal', function () {
        $(form).trigger("reset");
        if($(form).find('.same-validate').hasClass('same-validate')){
        	$('.same-validate').html('');
        }
        if($(form).find('.validate').hasClass('validate')){
        	$('.validate').html('');
        }
        if($(form).find('.custom-file-label').hasClass('custom-file-label')){
        	$('.custom-file-label').html('เลือกไฟล์');
        	$('#fileUpload')[0].value = '';
        	$('.imgPreview_add').attr('src', '../system/upload_icon/404.png');
        }
    });
}

function set_adminBtn_for_pagination_dml(){
	$('#checkboxAll, .checkbox-delete, #selectDeleteMorebtn, #cancelDeleteMorebtn').prop('hidden',false);
	$('#deleteMorebtn, .hide_in_table').prop('hidden',true);
	$('#unCheckboxAll').prop('hidden',true);
	if($('.checkbox-delete:checked').length == $('.checkbox-delete').length){
			$('.checkbox-delete').prop('checked',true);
			$('#checkboxAll').prop('hidden',true);
			$('#unCheckboxAll').prop('hidden',false);
		}
	$('#selectDeleteMorebtn').val(quote+' '+checkbox_get_global.length+' รายการ');
}