var checkbox_get_global = [];
var checkbox_get_global_name = [];
$(document).ready(function(){

	get_data();

	$( document ).on("change",".option_search_date",function(event){
	    get_data();
	});

	//เช็ครายการที่ซ้ำ
	$(document).on('blur','#room_name',function(){
		var send_data = {
				element: ".checksame",
				from_table: "h_room",
				where_field: "h_room_name"
			}
		checkAvailability_dml(send_data); //DML.js
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

  	//ส่งค่าไปให้ Model edit
	$(document).on("click", ".editbtn", function(){
		var id = $(this).data('id');
		var ref = $('.roomsetting_editmodel').attr('id', 'edit-'+id);
		var send_data = {
				id: id,
				command_search: 'search_room_Info',
				form: '#roomSettingEditForm',
				modal: '.roomsetting_editmodel',
				from_table: 'h_room',
				where_field: 'h_room_id'
			};
		generate_model_edit_dml(send_data); //DML.js
	});

	//ส่งข้อมูลไปให้ insert DB
	$("#sendRoomSettingAddForm").submit(function(event){
	    event.preventDefault();
	    $('#successAlert_add').fadeIn({ duration: 1500});
	    $('#successAlert_add').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	    var checksame = validate_same_dml();
	    console.log(checksame);
	    if(checksame){
	    	$.ajax({
		     	url: "../system/admin_cmd.php",
		      	data:{
		        command: "add_room",
			        room_name: $("#room_name").val(),
			        room_type: $("#room_type").val(),
			        room_status: $("#room_status").val()
			    },
			    type: "POST",
			    success:function(data){
			    	var recent_start_page_after_edit = parseInt(localStorage.getItem('recent_start'));
		    		var recent_limit_page_after_edit = parseInt(localStorage.getItem('recent_limit'));
			    	$.each(JSON.parse(data),function(key, value){
			    		if(value == 1){
			    			$('.same-validate').html('');
							$('#successAlert_add').html('<span class="text-success">เพิ่มห้องสำเร็จ <i class="fas fa-check-circle"></i></span>');
							$('#successAlert_add').fadeOut({ duration: 1500});
		    				$('#sendAccountAddForm').trigger("reset");
		    				$('#sendRoomSettingAddForm').trigger("reset");
			    		}else if(value == 0){
			    			$('#successAlert_add').html('');
			    		}
			    	});
			    	get_data(thispage_is);
			    },
			    error:function(){}
			});
	    }else{
	    	$('#successAlert_add').html('');
	    }
	});

	//ส่งข้อมูลRankไปให้ Edit DB
	$(document).on('click','.Update_room',function(event){
	    event.preventDefault();
	    var id = $(this).data('id');
	    $('#successAlert').fadeIn({ duration: 1500});
	    $('#successAlert').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	    $.ajax({
	     	url: "../system/admin_cmd.php",
	     	type: "POST",
	      	data:{
	        	command: "edit_room",
		        id: id,
		        h_room_name: $("#roomnoUP").val(),
		        h_room_type: $("#typeUP").val(),
		        h_room_status: $("#statusUP").val()
		    },
		    success:function(data){
		    	var recent_start_page_after_edit = parseInt(localStorage.getItem('recent_start'));
		    	var recent_limit_page_after_edit = parseInt(localStorage.getItem('recent_limit'));
		    	$('#successAlert').html('');
		    	$('#successAlert').html('บันทึกสำเร็จ <i class="fas fa-check-circle"></i>');
		    	$('#successAlert').fadeOut({ duration: 1500});
		    	get_data(thispage_is);
		    },
		    error:function(){alert('err');}
		});
	});

	//ส่งค่าไปให้ Model delete
	$(document).on("click", ".deletebtn", function(){
		var id = $(this).data('id');
		var ref = $('.roomsetting_deletemodel').attr('id', 'delete-'+id);
		var send_data = {
				id: id,
				command_search: 'search_room_Info',
				form: '#deleteRoomSettingForm',
				modal: '.roomsetting_deletemodel',
				from_table: 'h_room',
				where_field: 'h_room_id'
			};
		generate_model_delete_dml(send_data); //DML.js
	});

	//ปุ่ม ยืนยันลบที่เลือกทั้งหมด
	$(document).on('click','#selectDeleteMorebtn',function(){
		var ref = $('.roomsetting_deleteMoremodel').attr('id', 'delete-more');
		var send_data = {
				form: '#deleteMoreRoomSettingForm',
				modal: '.roomsetting_deleteMoremodel'
			}
		send_to_generate_model_deleteMore_dml(send_data);
	});

	//ปุ่ม ส่งค่าไปลบทั้งหมดที่เลือก
	$(document).on("click", ".confirmDeleteMore", function(){
		var send_data = {
			form: '#deleteMoreRoomSettingForm',
			from_table: 'h_room',
			where_field: 'h_room_id'
		};
		confirm_deleteMore_dml(send_data);
	});
    reset_modal_dml('.roomsetting_Addmodel','#sendRoomSettingAddForm'); //DML.js
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
											'<button class="btn btn-primary btn-sm editbtn" data-id="'+value.h_room_id+'"><i class="fas fa-pencil-alt"></i></button>&nbsp;&nbsp;'+
											'<button class="btn btn-danger btn-sm deletebtn" data-id="'+value.h_room_id+'"><i class="fas fa-trash-alt"></i></button>'+
										'</div>'+
									'</td>'+
									'<td class="text-center">'+
										'<input class="form-check-input checkbox-delete unlock-while-delete" type="checkbox" value="'+value.h_room_id+'" data-name="'+value.h_room_name+'" hidden>'+
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