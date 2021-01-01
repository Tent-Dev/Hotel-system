var checkbox_get_global = [];
var checkbox_get_global_name = [];
$(document).ready(function(){
	get_data();

	$(".searchbar_btn, .permission_check").click(function() {
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
		var ref = $('.rank_editmodel').attr('id', 'edit-'+id);
		var send_data = {
				id: id,
				command_search: 'search_rank_Info',
				form: '#rankEditForm',
				modal: '.rank_editmodel',
				from_table: 'h_userrank',
				where_field: 'h_userrank_id'
			};
		generate_model_edit_dml(send_data); //DML.js
	});

	//ส่งข้อมูลไปให้ insert DB
	$("#sendRankAddForm").submit(function(event){
	    event.preventDefault();
	    $('#successAlert_add').fadeIn({ duration: 1500});
	    $('#successAlert_add').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	    $.ajax({
	     	url: "../system/admin_cmd.php",
	      	data:{
	        command: "add_rank",
		        rank_name: $("#rank_name").val(),
		        rank_permission: $(".permission_select:checked").val(),
		    },
		    type: "POST",
		    success:function(data){
		    	$('#successAlert_add').html('เพิ่มตำแหน่งสำเร็จ <i class="fas fa-check-circle"></i>');
		    	$('#successAlert_add').fadeOut({ duration: 1500});
		    	$('#sendRankAddForm').trigger("reset");
		    	get_data(thispage_is);
		    },
		    error:function(){alert('err');}
		});
	});

	//ส่งข้อมูลRankไปให้ Edit DB
	$(document).on('click','.Update_rank',function(event){
	    event.preventDefault();
	    var id = $(this).data('id');
	    $('#successAlert').fadeIn({ duration: 1500});
	    $('#successAlert').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	    $.ajax({
	     	url: "../system/admin_cmd.php",
	     	type: "POST",
	      	data:{
	        	command: "edit_rank",
		        id: id,
		        h_userrank_name: $("#rank_nameUP").val(),
		        h_userrank_permission: $(".permission_selectUP:checked").val()
		    },
		    success:function(data){
		    	var permission_word;
		    	if($(".permission_selectUP:checked").val() == 1){
					permission_word = "จำกัด";
				}else{
					permission_word = "ไม่จำกัด";
				}
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
		var ref = $('.rank_deletemodel').attr('id', 'delete-'+id);
		var send_data = {
				id: id,
				command_search: 'search_rank_Info',
				form: '#deleteRankForm',
				modal: '.rank_deletemodel',
				from_table: 'h_userrank',
				where_field: 'h_userrank_id'
			};
		generate_model_delete_dml(send_data); //DML.js
	});

	//ปุ่ม ยืนยันลบที่เลือกทั้งหมด
	$(document).on('click','#selectDeleteMorebtn',function(){
		var ref = $('.account_deleteMoremodel').attr('id', 'delete-more');
		var send_data = {
				form: '#deleteMoreRankForm',
				modal: '.rank_deleteMoremodel'
			};
		send_to_generate_model_deleteMore_dml(send_data);
	});

	//ปุ่ม ส่งค่าไปลบทั้งหมดที่เลือก
	$(document).on("click", ".confirmDeleteMore", function(){
		var send_data = {
			form: '#deleteMoreRankForm',
			from_table: 'h_userrank',
			where_field: 'h_userrank_id'
		};
		confirm_deleteMore_dml(send_data);
	});

	reset_modal_dml('.rank_addmodel','#sendRankAddForm'); //DML.js
});

function get_data(page){
	if(page == undefined || page < 1) page = 1;
	$('#display_rank').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
	$.ajax({
		url: '../system/search_cmd.php',
		method: 'POST',
		type: 'JSON',
		data:{
			command: 'search_rank',
			name: $('#search').val(),
			permission: $('.permission_check:checked').val(),
			page:page
		},
		success:function(data){
			data = JSON.parse(data);
	  		new_pagination(data,page);
			if(data.html != undefined){
				$('#display_status').html('<br>No bed found.');
				$('.sumresult').html('&nbsp;0');
				console.log('after had deleted. This page is null!','\nCurrent page is: '+thispage_is);
				new_pagination(data,thispage_is,true);
			}
			else{
				$('#display_rank').html('');
				var permission = '';
				$.each(data.result,function(key,value){
					if(value.h_userrank_permission == 1){
						permission = "จำกัด";
					}else{
						permission = "ไม่จำกัด";
					}
					var html =	'<tr id="id-'+value.h_userrank_id+'">'+
									'<td class="rank_name">'+value.h_userrank_name+'</td>'+
									'<td class="permission_this">'+permission+'</td>'+
									'<td>'+
										'<div class="hide_in_table">'+
											'<button class="btn btn-primary btn-sm editbtn" data-id="'+value.h_userrank_id+'"><i class="fas fa-pencil-alt"></i></button>&nbsp;&nbsp;'+
											'<button class="btn btn-danger btn-sm deletebtn" data-id="'+value.h_userrank_id+'"><i class="fas fa-trash-alt"></i></button>'+
										'</div>'+
									'</td>'+
									'<td class="text-center">'+
										'<input class="form-check-input checkbox-delete unlock-while-delete" type="checkbox" value="'+value.h_userrank_id+'" data-name="'+value.h_userrank_name+'" hidden>'+
									'</td>'+
								'</tr>';
				    $(html).appendTo('#display_rank');
				});
				checkbox_save();
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