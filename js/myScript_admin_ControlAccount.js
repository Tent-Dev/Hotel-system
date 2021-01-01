var checkbox_get_global = [];
var checkbox_get_global_name = [];
$(document).ready(function(){
	get_data();

	if($('.imgPreview_add').attr('src') == ""){
		$('.imgPreview_add').attr('src','../system/upload_icon/404.png');
	}

	$(".searchbar_btn, .status").click(function() {
    	get_data();
  	});

  	$(document).on("keypress", function (e) {
  		// 13 = enter button
  		if(e.which == 13){
	        get_data();
	    }
  	});

	//ส่งค่าไปให้ Model edit account
	$(document).on("click", ".editbtn", function(){
		var id = $(this).data('id');
		var ref = $('.account_editmodel').attr('id', 'edit-'+id);
		var send_data = {
				id: id,
				command_search: 'search_account_Info',
				form: '#accountEditForm',
				modal: '.account_editmodel',
				from_table: 'h_user',
				where_field: 'h_user_id'
			};
		generate_model_edit_dml(send_data); //DML.js
	 });

	//ส่งค่าไปให้ Model delete account
	$(document).on("click", ".deletebtn", function(){
		var id = $(this).data('id');
		var ref = $('.account_deletemodel').attr('id', 'delete-'+id);
		var send_data = {
				id: id,
				command_search: 'search_account_Info',
				form: '#deleteAccountForm',
				modal: '.account_deletemodel',
				from_table: 'h_user',
				where_field: 'h_user_id',
				image_field: 'h_user_image'
			};
		generate_model_delete_dml(send_data); //DML.js
	});

	//เช็คชื่อผู้ใช้ซ้ำ
	$(document).on('blur','.checksame',function(){
		var send_data = {
				element: ".checksame",
				from_table: "h_user",
				where_field: "h_user_username"
			}
		checkAvailability_dml(send_data);
	});

	//ส่งข้อมูลสมัครUserไปให้ insert
	$("#sendAccountAddForm").submit(function(event){
	    event.preventDefault();
	    $('#successAlert_add').fadeIn({ duration: 1500});
	    $('#successAlert_add').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	    var validateForm = signupFormValidate();
	    var checksame = validate_same_dml();
	    if(validateForm && checksame){
		    $.ajax({
		     	url: "../system/admin_cmd.php",
		      	data:{
		        command: "signup",
			        user: $("#user").val(),
			        pass: $("#pass").val(),
			        Fname: $("#Fname").val(),
			        Lname: $("#Lname").val(),
			        rank: $("#rank").val()
			    },
			    type: "POST",
			    success:function(data){
			    	$.each(JSON.parse(data),function(key, value){
			    		console.log(value);
			    		if(value == 1){
			    			$('.same-validate').html('');
							$('#successAlert_add').html('<span class="text-success">เพิ่มบัญชีสำเร็จ <i class="fas fa-check-circle"></i></span>');
							$('#successAlert_add').fadeOut({ duration: 1500});
		    				$('#sendAccountAddForm').trigger("reset");
			    		}else if(value == 0){
			    			$('.same-validate').html('');
			    			$('#successAlert_add').html('<span class="text-danger">ชื่อบัญชีผู้ใช้หรือรหัสผ่าน ไม่ถูกต้อง <i class="fas fa-times-circle"></i></span>');
			    		}else if(value == 2){
			    			$('#successAlert_add').html('');
			    		}
			    	});
			    	get_data(thispage_is);
			    },
			    error:function(){}
			    })
	    } else{
	    	$('#successAlert_add').html('');
	    }
	});

	//ส่งข้อมูลUserไปให้ Edit
	$(document).on('click','.Update_account',function(event){
	    event.preventDefault();
	    $('#successAlert').fadeIn({ duration: 1500});
	    $('#successAlert').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	    $.ajax({
	     	url: "../system/admin_cmd.php",
	     	type: "POST",
	      	data:{
	        	command: "edit_account",
		        userid: $("#userUP").data('id'),
		        Fname: $("#FnameUP").val(),
		        Lname: $("#LnameUP").val(),
		        rank: $("#rankUP").val(),
		        reset_password: $("#generate_password").val()
		    },
		    success:function(data){
		    	$('#successAlert').html('');
		    	$('#successAlert').html('บันทึกสำเร็จ <i class="fas fa-check-circle"></i>');
		    	$('#successAlert').fadeOut({ duration: 1500});
		    	get_data(thispage_is);
		    },
		    error:function(){alert('err');}
		});
	});

	//ปุ่ม ยืนยันลบที่เลือกทั้งหมด
	$(document).on('click','#selectDeleteMorebtn',function(){
		//var ref = $('.account_deleteMoremodel').attr('id', 'delete-more');
		var send_data = {
				form: '#deleteMoreAccountForm',
				modal: '.account_deleteMoremodel'
			};
		send_to_generate_model_deleteMore_dml(send_data);
	});	

	//ปุ่ม ส่งค่าไปลบทั้งหมดที่เลือก
	$(document).on("click", ".confirmDeleteMore", function(){
		var send_data = {
			form: '#deleteMoreAccountForm',
			from_table: 'h_user',
			where_field: 'h_user_id',
			image_field: 'h_user_image'
		};
		confirm_deleteMore_dml(send_data);
	});

	//สร้างรหัสผ่านใหม่
	$(document).on("click", ".resetpass", function(e){
		e.preventDefault();
		$('.btn-copy').html('Copy');
		var new_password = generate_password_length(6);
		$('#generate_password').val(new_password);
	});

	$(document).on("click", ".btn-copy", function(e){
		e.preventDefault();
		if($('#generate_password').val() != ""){
			$(this).html('Copied');
			$('#generate_password').select();
			document.execCommand("copy");
		}
	});

	reset_modal_dml('.account_addmodel','#sendAccountAddForm'); //DML.js
});

function get_data(page){
	if(page == undefined || page < 1) page = 1;
	$('#display_account').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	$.ajax({
		url: '../system/search_cmd.php',
		method: 'POST',
		type: 'JSON',
		data:{
			command: 'search_account',
			name: $('#search').val(),
			status: getFilterData_dml('.status:checked','this'),
			page:page
		},
		success:function(data){
			data = JSON.parse(data);
	  		new_pagination(data,page);
			if(data.html != undefined){
				$('#display_account').html('<br>No account found.');
				$('.sumresult').html('&nbsp;0');
				console.log('after had deleted. This page is null!','\nCurrent page is: '+thispage_is);
				new_pagination(data,thispage_is,true);
			}
			else{
				$('#display_account').html('');
				$.each(data.result,function(key,value){
					var html =	'<tr class="align-middle" id="id-'+value.h_user_id+'">'+
									'<td class="user_image d-md-block d-none"><img src="';
										if(value.h_user_image == ""){
											html += '../img/member_pic.png';
										}else{
											html += value.h_user_image;
										}
										
						html +=		'" height="50px" width="50px"></td>'+
									'<td class="user">'+value.h_user_username+'</td>'+
									'<td class="Fname">'+value.h_user_firstname+'</td>'+
									'<td class="Lname">'+value.h_user_lastname+'</td>'+
									'<td class="rank">'+value.h_user_rank+'</td>'+
									'<td>'+
										'<div class="hide_in_table">'+
											'<button class="btn btn-primary btn-sm editbtn" data-id="'+value.h_user_id+'"><i class="fas fa-pencil-alt"></i></button>&nbsp;&nbsp;'+
											'<button class="btn btn-danger btn-sm deletebtn" data-id="'+value.h_user_id+'"><i class="fas fa-trash-alt"></i></button>'+
										'</div>'+
									'</td>'+
									'<td class="text-center">'+
										'<input class="form-check-input checkbox-delete unlock-while-delete" type="checkbox" value="'+value.h_user_id+'" data-name="'+value.h_user_username+'" hidden>'+
									'</td>'+
								'</tr>';
				    $(html).appendTo('#display_account');
				});
				checkbox_save();
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
		error:function(){ alert('err');}
	});
}

//เช็คเงื่อนไขการกรอกข้อมูลมัคร account
function signupFormValidate(){
  var valid = true; //ตั้งให้ ค่าเป็น true เวลาเอาฟังชั่นนี้ไปใช้ ถ้ามีอะไรผิดพลาด ในฟังชั่นนี้จะให้ค่า false  ตามเงื่อนไข
  var letters = /^[0-9a-zA-Z]+$/;
   if(!$('#user').val().match(letters)){
        $('#message_alpha').html('ชื่อผู้ใช้กรุณาใช้ตัวอักษร A-Z, a-z หรือ 0-9 <br>').css('color', 'red');
        valid = false;
    }
    if(!$('#pass').val().match(letters)){
        $('#message_alphapass').html('รหัสผ่านกรุณาใช้ตัวอักษร A-Z, a-z หรือ 0-9 <br>').css('color', 'red');
        valid = false;
    }
    if($('#pass').val() != $('#Conpass').val()){
        $('#message_pass').html('ยืนรหัสผ่านไม่ตรงกัน').css('color', 'red');
        valid = false;
    }
    if($('#pass').val() == $('#Conpass').val()){
        $('#message_pass').html('');
    }
    if($('#user').val().match(letters)){
      $('#message_alpha').html('');
      //$('#message_alert').prop('hidden',true);
    }
    if($('#pass').val().match(letters)){
      $('#message_alphapass').html('');
    }

    if($(".checksame:first").is("#notAvailable")){ //เช็คว่า class นี้ มี id notAvailableอยู่
      valid = false;
    }
    if (valid == false){
    	$('#message_alert').prop('hidden',false);
    }
    else if (valid == false && $('#pass').val() != $('#Conpass').val()){
    	$('#message_alert').prop('hidden',true);
    }
    else if (valid == true){
    	$('#message_alert').prop('hidden',true);
    }
    return valid;
}

function generate_password_length(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}