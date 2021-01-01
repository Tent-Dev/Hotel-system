var checkbox_get_global = [];
var checkbox_get_global_name = [];
$(document).ready(function(){
	get_data();

	if($('.imgPreview_add').attr('src') == ""){
					$('.imgPreview_add').attr('src','../system/upload_icon/404.png');
	}

	$(".searchbar_btn").click(function() {
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
		var ref = $('.bed_editmodel').attr('id', 'edit-'+id);
		var send_data = {
				id: id,
				command_search: 'search_bed_Info',
				form: '#bedEditForm',
				modal: '.bed_editmodel',
				from_table: 'h_type_bed',
				where_field: 'h_type_bed_id'
			};
		generate_model_edit_dml(send_data); //DML.js
	});

	//ส่งข้อมูลไปให้ insert DB
	$(document).on('submit','#sendBedAddForm',function(event){
	    event.preventDefault();
	    Upload_image('upload_icon',$('.Add').data('command'),$('.Add').data('element'));
	    setTimeout(Add, 2500);
	});

	//ส่งข้อมูลbedไปให้ Edit DB
	$(document).on('click','.Update_bed',function(event){
	    event.preventDefault();
	    var id = $(this).data('id');
	    Upload_image('upload_icon',$(this).data('command'),$(this).data('element'));
	    setTimeout(Update, 2500, id);
	 
	});

	//ส่งค่าไปให้ Model delete
	$(document).on("click", ".deletebtn", function(){
		var id = $(this).data('id');
		var ref = $('.bed_deletemodel').attr('id', 'delete-'+id);
		var send_data = {
				id: id,
				command_search: 'search_bed_Info',
				form: '#deleteBedForm',
				modal: '.bed_deletemodel',
				from_table: 'h_type_bed',
				where_field: 'h_type_bed_id',
				image_field: 'h_type_bed_image'
			};
		generate_model_delete_dml(send_data); //DML.js
	});

	//ปุ่ม ยืนยันลบที่เลือกทั้งหมด
	$(document).on('click','#selectDeleteMorebtn',function(){
		var ref = $('.bed_deleteMoremodel').attr('id', 'delete-more');
		var send_data = {
				form: '#deleteMoreBedForm',
				modal: '.bed_deleteMoremodel'
			};
		send_to_generate_model_deleteMore_dml(send_data);
	});

	//ปุ่ม ส่งค่าไปลบทั้งหมดที่เลือก
	$(document).on("click", ".confirmDeleteMore", function(){
		var send_data = {
				form: '#deleteMoreBedForm',
				from_table: 'h_type_bed',
				where_field: 'h_type_bed_id',
				image_field: 'h_type_bed_image'
			};
		confirm_deleteMore_dml(send_data);
	});

	$(document).on("change",".custom-file-input", function() {
	    var fileName = $(this).val().split("\\").pop();
		$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});

	//ดูรูปตัวอย่างก่อนอัพโหลดรูปโปรไฟล์
	$(".upload").change(function() {
		var element = $(this).data('element');
		readURL(this,element);
	});

	$('#btnDelete_image_bed_add').click(function(event){
    event.preventDefault();
    var element = $(this).data('element');
    $(element).attr('src','../system/upload_icon/404.png');
    $('.custom-file-label').html('เลือกไฟล์');
    $('#fileUpload')[0].value = '';
  });

	reset_modal_dml('.bed_addmodel','#sendBedAddForm'); //DML.js
});

function get_data(page){
	if(page == undefined || page < 1) page = 1;
	$('#display_bed').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	$.ajax({
		url: '../system/search_cmd.php',
		method: 'POST',
		type: 'JSON',
		data:{
			command: 'search_bed',
			name: $('#search').val(),
			page:page
		},
		success:function(data){
			data = JSON.parse(data);
	  		new_pagination(data,page)
			if(data.html != undefined){
				$('#display_bed').html('<br>No bed found.');
				$('.sumresult').html('&nbsp;0');
				console.log('after had deleted. This page is null!','\nCurrent page is: '+thispage_is);
				new_pagination(data,thispage_is,true);
			}
			else{
				$('#display_bed').html('');
				$.each(data.result,function(key,value){
					var html =	'<tr id="id-'+value.h_type_bed_id+'">'+
									'<td class="bed_image"><img src="';
										if(value.h_type_bed_image == ""){
											html += '../system/upload_icon/404.png';
										}else{
											html+= value.h_type_bed_image;
										}
										
					html +=			'" height="30px"></td>'+
									'<td class="bed_name">'+value.h_type_bed_name+'</td>'+
									'<td class="bed_desc">'+value.h_type_bed_desc+'</td>'+
									'<td>'+
										'<div class="hide_in_table">'+
											'<button class="btn btn-primary btn-sm editbtn" data-id="'+value.h_type_bed_id+'"><i class="fas fa-pencil-alt"></i></button>&nbsp;&nbsp;'+
											'<button class="btn btn-danger btn-sm deletebtn" data-id="'+value.h_type_bed_id+'"><i class="fas fa-trash-alt"></i></button>'+
										'</div>'+
									'</td>'+
									'<td class="text-center">'+
										'<input class="form-check-input checkbox-delete unlock-while-delete" type="checkbox" value="'+value.h_type_bed_id+'" data-name="'+value.h_type_bed_name+'" hidden>'+
									'</td>'+
								'</tr>';
				    $(html).appendTo('#display_bed');
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

function Add(){
	$('#successAlert_add').fadeIn({ duration: 1500});
	$('#successAlert_add').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	$.ajax({
	 	url: "../system/admin_cmd.php",
	  	data:{
	    command: "add_bed",
	        bed_name: $("#bed_name").val(),
	        bed_desc: $("#bed_desc").val(),
	        bed_image: $('.imgPreview_add').attr('src')
	    },
	    type: "POST",
	    success:function(data){
	    	$('#successAlert_add').html('เพิ่มประเภทเตียงสำเร็จ <i class="fas fa-check-circle"></i>');
	    	$('#successAlert_add').fadeOut({ duration: 1500});
	    	$('#sendBedAddForm').trigger("reset");
	    	$('.imgPreview_add').attr('src','../system/upload_icon/404.png');
	    	$('.custom-file-label').html('เลือกไฟล์');
	    	$('#fileUpload')[0].value = '';
	    	get_data(thispage_is);
	    },
	    error:function(){}
	});
}

function Update(id){
	$('#successAlert').fadeIn({ duration: 1500});
	$('#successAlert').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	$.ajax({
	 	url: "../system/admin_cmd.php",
	 	type: "POST",
	  	data:{
	    	command: "edit_bed",
	        id: id,
	        h_type_bed_name: $("#bed_nameUP").val(),
	        h_type_bed_desc: $("#bed_descUP").val(),
	        h_type_bed_image: $('.imgPreview').attr('src')
	    },
	    success:function(data){
	    	$('#successAlert').html('');
	    	$('#successAlert').html('บันทึกสำเร็จ <i class="fas fa-check-circle"></i>');
	    	$('#successAlert').fadeOut({ duration: 1500});
	    	get_data(thispage_is);
	    },
	    error:function(){alert('err');}
	});
}

//ตัวอย่างภาพก่อนอัพโหลด
function readURL(input,element) {
  var acceptedImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
  var result_checktype = $.inArray(input.files[0].type, acceptedImageTypes);
  console.log(result_checktype,'\n'+input.files[0].type);
  if (input.files && input.files[0] && result_checktype !== -1) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $(element).attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }else{
    alert('ไฟล์ไม่ถูกต้อง');
    $(element).attr('src', '../system/upload_icon/404.png');
    setTimeout(reset_filename,500);
    $('#fileUpload')[0].value = '';
  }
}

//อัพโหลดภาพลง folder
function Upload_image(local,command,element){
 	if(command == "upload_icon_add"){
  		var fileUpload_element = 'fileUpload[data-command="upload_icon_add"]';
  	}else{
  	  	var fileUpload_element = 'fileUpload[data-command="upload_icon_edit"]';
  	}
    var fileUpload = $('#'+fileUpload_element)[0];
    var formData = new FormData();

    if (fileUpload.files.length == 1) {
	    formData.append('fileUpload', fileUpload.files[0], fileUpload.files[0].name);
	    formData.append('local',local);
	    	$.ajax({
		        url: '../system/uploadimage.php',
		        type: 'POST',
		        data: formData,
		        processData: false,
		        contentType: false,
		        success: function(data, textStatus, jqXHR) {
		        	console.log(data.status);
			        fileUpload.value = null;
			        if(data.status === "ok"){
			          $(element).attr('src', '../system/'+local+'/' + data.fileId);
			          $('#hidFileId').val('../system/'+local+'/' + data.fileId);
			        }else{
			          alert('ไฟล์ไม่ถูกต้อง');
			        }
		        },
		        error: function(jqXHR, textStatus, errorThrown) {
		          alert('An error occurred uploading the file!');
		        }
	      	});
    }
}

function reset_filename(){
	$('.custom-file-label').html('เลือกไฟล์');
}