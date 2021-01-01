var checkbox_get_global = [];
var checkbox_get_global_name = [];
$(document).ready(function(){
	get_data();

	$(".searchbar_btn, .status_check").click(function() {
    	get_data();
  	});

  	$(document).on("keypress", function (e) {
  		// 13 = enter button
  		if(e.which == 13){
	        get_data();
	    }
  	});

	$(document).on("click", "#addbtn", function(){
		color_picker();
	});

  	//ส่งค่าไปให้ Model edit account
	$(document).on("click", ".editbtn", function(){
		var id = $(this).data('id');
		var ref = $('.status_editmodel').attr('id', 'edit-'+id);
		var send_data = {
				id: id,
				command_search: 'search_status_Info',
				form: '#statusEditForm',
				modal: '.status_editmodel',
				from_table: 'h_status',
				where_field: 'h_status_id'
			};
		generate_model_edit_dml(send_data); //DML.js
	});

	//ส่งข้อมูลไปให้ insert DB
	$("#sendStatusAddForm").submit(function(event){
	    event.preventDefault();
	    $('#successAlert_add').fadeIn({ duration: 1500});
	    $('#successAlert_add').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	    $.ajax({
	     	url: "../system/admin_cmd.php",
	      	data:{
	        command: "add_status",
		        status_name: $("#status_name").val(),
		        status_touser: $('.status_select:checked').val(),
		        status_color: $('.color_code').html()
		    },
		    type: "POST",
		    success:function(data){
		    	$('#successAlert_add').html('เพิ่มสถานะสำเร็จ <i class="fas fa-check-circle"></i>');
		    	$('#successAlert_add').fadeOut({ duration: 1500});
		    	$('#sendStatusAddForm').trigger("reset");
		    	color_picker();
		    	get_data(thispage_is);
		    },
		    error:function(){}
		});
	});

	//ส่งข้อมูลRankไปให้ Edit DB
	$(document).on('click','.Update_status',function(event){
	    event.preventDefault();
	    var id = $(this).data('id');
	    $('#successAlert').fadeIn({ duration: 1500});
	    $('#successAlert').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	    $.ajax({
	     	url: "../system/admin_cmd.php",
	     	type: "POST",
	      	data:{
	        	command: "edit_status",
		        id: id,
		        h_status_name: $("#status_nameUP").val(),
		        h_status_statustouser: $('.status_selectUP:checked').val(),
		        h_status_color: $('.color_code_edit').html()
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

	//ส่งค่าไปให้ Model delete
	$(document).on("click", ".deletebtn", function(){
		var id = $(this).data('id');
		var ref = $('.status_deletemodel').attr('id', 'delete-'+id);
		var send_data = {
				id: id,
				command_search: 'search_status_Info',
				form: '#deleteStatusForm',
				modal: '.status_deletemodel',
				from_table: 'h_status',
				where_field: 'h_status_id'
			};
		generate_model_delete_dml(send_data); //DML.js
	});

	//ปุ่ม ยืนยันลบที่เลือกทั้งหมด
	$(document).on('click','#selectDeleteMorebtn',function(){
		var send_data = {
			form: '#deleteMoreStatusForm',
			modal: '.status_deleteMoremodel'
		};
		send_to_generate_model_deleteMore_dml(send_data);
	});

	//ปุ่ม ส่งค่าไปลบทั้งหมดที่เลือก
	$(document).on("click", ".confirmDeleteMore", function(){
		var send_data = {
			form: '#deleteMoreStatusForm',
			from_table: 'h_status',
			where_field: 'h_status_id'
		};
		confirm_deleteMore_dml(send_data);
	});

	reset_modal_dml('.status_addmodel','#sendStatusAddForm'); //DML.js
});

function get_data(page){
	if(page == undefined || page < 1) page = 1;
	$('#display_status').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	$.ajax({
		url: '../system/search_cmd.php',
		method: 'POST',
		type: 'JSON',
		data:{
			command: 'search_status',
			name: $('#search').val(),
			status_touser: $('.status_check:checked').val(),
			page:page
		},
		success:function(data){
			data = JSON.parse(data);
	  		new_pagination(data,page);
			if(data.html != undefined){
				$('#display_status').html('<br>No status found.');
				$('.sumresult').html('&nbsp;0');
				console.log('after had deleted. This page is null!','\nCurrent page is: '+thispage_is);
				new_pagination(data,thispage_is,true);
			}
			else{
				$('#display_status').html('');
				$.each(data.result,function(key,value){
					var html =	'<tr id="id-'+value.h_status_id+'">'+
									'<td class="status_name"><div class="colorbox" style="background-color:'+value.h_status_color+';"></div></td>'+
									'<td class="status_name">'+value.h_status_name+'</td>'+
									'<td class="status_this">'+value.h_statustouser_name+'</td>'+
									'<td>'+
										'<div class="hide_in_table">'+
											'<button class="btn btn-primary btn-sm editbtn" data-id="'+value.h_status_id+'"><i class="fas fa-pencil-alt"></i></button>&nbsp;&nbsp;'+
											'<button class="btn btn-danger btn-sm deletebtn" data-id="'+value.h_status_id+'"><i class="fas fa-trash-alt"></i></button>'+
										'</div>'+
									'</td>'+
									'<td class="text-center">'+
										'<input class="form-check-input checkbox-delete unlock-while-delete" type="checkbox" value="'+value.h_status_id+'" data-name="'+value.h_status_name+'" hidden>'+
									'</td>'+
								'</tr>';
				    $(html).appendTo('#display_status');
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

function color_picker(color_db){
	console.log(color_db);
	var color;
	var color_default;
	if(color_db != undefined){
		color_default = color_db;
	}else{
		color_default = '#FFFFFF';
	}
	$(".colorPickSelector").colorPick({
	  'initialColor': color_default,
	  'allowRecent': true,
	  'recentMax': 5,
	  'palette': ['#FFFFFF', "#f8d7da", "#16a085", "#2ecc71", "#27ae60", "#3498db", "#2980b9", "#9b59b6", "#8e44ad", "#34495e", "#2c3e50", "#f1c40f", "#f39c12", "#e67e22", "#d35400", "#e74c3c", "#c0392b", "#ecf0f1", "#bdc3c7", "#95a5a6", "#7f8c8d"],
	  'onColorSelected': function() {
	    this.element.css({'backgroundColor': this.color, 'color': this.color});
	    if(color_db != undefined){
			$('.color_code_edit').html(this.color);
		}else{
			$('.color_code').html(this.color);
		}
	    color = this.color;
	  }
	});
	return color;
}