$(document).ready(function(){

	var FormRequestClass = ".addrateDate";
  	datepicker_set(FormRequestClass);

	var ratestatustouser;
	//search ratelist real time
 	get_data();
  	$('.ratefilter').click(function(){
        get_data();
  	});

  	//เช็คเรทห้องที่เลือก (status)
	$(document).on("click", ".toggle", function () {
	  	var rateid = $(this).find('.statustype').data('rateid'); //หาid จาก elementลูก
	  	var check;

	  	if($(this).find('.statustype').prop('checked')){
	  		$("#boxof-"+rateid).animate({backgroundColor:''});
	  		check = 1;
	  	}else{
	  		$("#boxof-"+rateid).animate({backgroundColor:'#E5E4E2'});
	  		check = 2;
	  	}

	  	$.ajax({
			url: "../system/admin_cmd.php",
			data:{
			    command: "statusrate",
			    rateid: rateid,
			    statustouser: check
			},
			type: "POST",
			success:function(data){
			},
			error:function (){ alert('error');}
			}); 
	});

	//ส่งข้อมูลRateไป Insert
  	$("#sendRateForm").submit(function(event){
  		var rate_datestart;
  		var rate_dateend;
  		var discount;

    	event.preventDefault();
    	$('#successAlert_add').fadeIn({ duration: 1500});
    	$('#successAlert_add').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
    	for (instance in CKEDITOR.instances) {
	        CKEDITOR.instances[instance].updateElement();
	    }
	    if($(".rate_dateset:checked").val() == 0){
	    	rate_datestart = "0000-00-00";
	    	rate_dateend = "0000-00-00";
	    	console.log('null set '+rate_datestart);
	    }else{
	    	rate_datestart = moment(new Date($('.rate_datestart').val())).format("Y-MM-DD");
	        rate_dateend = moment(new Date($('.rate_dateend').val())).format("Y-MM-DD");
	    }
	    if($("#ratediscount").val() == ""){
	    	discount = 0;
	    }
	    else{
	    	discount = $("#ratediscount").val();
	    }
     	$.ajax({
	      	url: "../system/admin_cmd.php",
	      	data:{
	        	command: "addrate",
	        	ratename: $("#ratename").val(),
	        	ratedesc: $("#ratedesc").val(),
	        	ratediscount: discount,
	        	ratediscountset: $(".discountcheck:checked").val(),
	        	rate_dateset: $(".rate_dateset:checked").val(),
	        	rate_datestart: rate_datestart,
	        	rate_dateend: rate_dateend,
	        	rate_deposit: $(".rate_deposit:checked").val(),
	        	rate_statustouser: $(".statusset:checked").val(),
	        	type: getFilterDataTypeOnly()
	      	},
	      	type: "POST",
	      	success:function(data){
		    	$('#successAlert_add').html('');
		    	$('#successAlert_add').html('เพิ่มเรทราคาสำเร็จ <i class="fas fa-check-circle"></i>');
		    	$('#successAlert_add').fadeOut({ duration: 1500});
		    	get_data(thispage_is);
		    	$('#sendRateForm').trigger("reset");
		    	CKEDITOR.instances['ratedesc'].setData('');
	      	},
	      	error:function (){}
    	});
  	});

  	//ส่งค่าไปให้ Model ลบ รายการเดียว
	$(document).on("click", ".deletePop", function () {
		var test = $('.deletemodal').attr('id', 'delete-'+$(this).data('rateid'));
		var rateid = $(this).data('rateid');
		var ratename = $(this).data('ratename');
		delete_modal(rateid,ratename);
	 });

	//ปุ่ม แก้ไข rate
	$(document).on("click", ".updatePop", function () {
		var test = $('.editmodal').attr('id', 'edit-'+$(this).data('rateid'));
		var rateid = $(this).data('rateid');
		var ratename = $(this).data('ratename');
		var ratediscountset = $(this).data('ratediscountset');
		var ratediscount = $(this).data('ratediscount');
		var ratedeposit = $(this).data('ratedeposit');
		var ratedateset = $(this).data('ratedateset');
		var ratedatestart = $(this).data('ratedatestart');
		var ratedateend = $(this).data('ratedateend');
		var ratedesc = $('.savedesc-'+rateid+'').html();
		var ratestatustouser = $(this).data('ratestatustouser');
		console.log('Rate ID: '+rateid,'\nRate name: '+ratename,'\nDiscount set: '+ratediscount+ ' ' +ratediscountset);
		updatePop(rateid,ratename,ratediscountset,ratediscount,ratedeposit,ratedateset,ratedatestart,ratedateend,ratedesc,ratestatustouser);
		$('.editmodal').modal('show');
	});


  	//Option กำหนดวัน
  	$(document).on("click", ".rate_dateset", function () {
  		if($(".rate_dateset:checked").val() == 1){
  			$("#checkin2, #checkout2").prop('disabled',false);
  			$("#checkin2, #checkout2, .date-to-date").prop('hidden',false);
  		}
  		if($(".rate_dateset:checked").val() == 0){
  			$("#checkin2, #checkout2").prop('disabled',true);
  			$("#checkin2, #checkout2, .date-to-date").prop('hidden',true);
  			$("#checkin2, #checkout2").val('');
  		}

  	});

  	//Option กำหนดส่วนลด
  	$(document).on("click", ".discountcheck", function () {
  		console.log('Type discount: '+ $(this).val());
  		if($(".discountcheck:checked").val() == 'Bath' || $(".discountcheck:checked").val() == 'Percent'){
  			$("#ratediscount").prop('disabled',false);
  			$("#ratediscount").prop('hidden',false);
  			if($(".discountcheck:checked").val() == 'Bath'){
  				$(".discounttype").html('THB.');
  				$(".discounttype").prop('hidden',false);
  			}
  			else if($(".discountcheck:checked").val() == 'Percent'){
  				$(".discounttype").html('%');
  				$(".discounttype").prop('hidden',false);
  			}
  		}
  		if($(".discountcheck:checked").val() == 'None'){
  			$("#ratediscount").prop('disabled',true);
  			$("#ratediscount").prop('hidden',true);
  			$("#ratediscount").val('');
  			$(".discounttype").prop('hidden',true);
  		}
  	});

  	//Option กำหนดชำระเงินล่วงหน้า
  	$(document).on("click", ".rate_deposit", function () {
  		console.log('Type deposit: ' + $(this).val());
  	});

  	//Option ชนิดห้องที่ร่วมรายการ
  	$(document).on("click", ".type", function () {
  		getFilterDataTypeOnly();
  	});

  	//Option ชนิดห้องที่ร่วมรายการ เลือกทั้งหมด
  	$(document).on("click", ".typeall", function (e) {
  		e.preventDefault();
  		$('.type').prop('checked',true);
  		getFilterDataTypeOnly();

  		$('.typeall').prop('hidden',true);
  		$('.distypeall').prop('hidden',false);
  	});

  	//Option ชนิดห้องที่ร่วมรายการ ยกเลิกทั้งหมด
  	$(document).on("click", ".distypeall", function (e) {
  		e.preventDefault();
  		$('.type').prop('checked',false);
  		getFilterDataTypeOnly();

  		$('.typeall').prop('hidden',false);
  		$('.distypeall').prop('hidden',true);
  	});

  	$('#addrate').on('hidden.bs.modal', function () {
        $('#sendRateForm').trigger("reset");
        CKEDITOR.instances['ratedesc'].setData('');
    });
});

//เช็คว่า typeไหน ร่วมรายการบ้าง
function getFilterDataTypeOnly() {
	var filter2 = [];
	$('.type:checked').each(function(){
		filter2.push($(this).val());
		console.log("Type select: ",filter2);
	});
	return filter2;
}

//เช็คว่า typeไหน ร่วมรายการบ้าง (Update)
function getFilterDataTypeUpdateOnly() {
	var filter2 = [];
	$('.typeUP:checked').each(function(){
		filter2.push($(this).val());
		console.log("TypeUpdate select: ",filter2);
	});
	return filter2;
}

//เช็คว่า typeไหน แสดง/ซ่อนบ้าง และปรับสีdiv
function getFilterDataStatusOnly() {
		var rateid;
		var overall = [];
	   $('.toggle').find('.statustype:checked').each(function(){
	    	rateid = $(this).data('rateid');
	    	overall.push($(this).data('ratename'));
	    $("#boxof-"+rateid).css("background-color",'');
	    });

	    var overall = [];
	   $('.toggle').find(".statustype:checkbox:not(:checked)").each(function(){
	    	rateid = $(this).data('rateid');
	    	overall.push($(this).data('ratename'));
	    	$("#boxof-"+rateid).css("background-color",'#E5E4E2');
	    });
}
//Search ratelist
function get_data(page) {
	if(page == undefined) page = 1;
    $('#show_rate').html('<div id="loading"><h5><i class=\"fas fa-spinner fa-spin\"></i>Loading .....</h5></div>');
    var discount = $('.discount:checked').val();
    var deposit = $('.deposit:checked').val();
    var statustouser = $('.status:checked').val();
    $.ajax({
      url:"../system/search_cmd.php",
      method:"POST",
      type: 'json',
      data:{
        command: "search_ratelist",
        discount: discount,
        deposit: deposit,
        statustouser: statustouser,
        page: page
      },
      success:function(data){
      	data = JSON.parse(data);
  		new_pagination(data,page);
  		var now = moment().format("DD/MM/YYYY"); 
		if(data.html != undefined){
			$('#show_rate').html('No rate found.');
			$('.sumresult').html('&nbsp;0');
			console.log('after had deleted. This page is null!','\nCurrent page is: '+thispage_is);
			new_pagination(data,thispage_is,true);
		}
		else{
			$('#show_rate').html('');
			var typerecent;
			$.each(data.result,function(key,value){
				var json_data = '<div class=\"content-box mb-3 content-lighten sumfromcmd sm-font-admin\" id=\"boxof-'+value.h_rate_id+'\">'+
								'<div class=\"col-12 row\">'+
								'<div class=\"col-12 col-md-6\"> <b>Rate name: </b>'+value.h_rate_name+'</div>'+
								'<div class="float-right col-6"><b>Date: </b>';

								if(value.h_rate_dateset == 1){
									json_data += value.h_rate_datestart+' - '+value.h_rate_dateend;
									if(now > moment(value.h_rate_dateend,'DD/MMM/YYYY').add(1, 'days')){
										json_data += ' <span class="finish" style="color: red;">หมดเวลาแล้ว</span>';
									}
								}
								else{
									json_data += 'ไม่กำหนดระยะเวลา';
								}
								

					json_data += '</div></div><hr><div class=\"col-12 col-md-6\"> <b>Description: </b><span class= \"savedesc-'+value.h_rate_id+'\">'+value.h_rate_desc+'</span></div><br>';

					json_data += '<hr>'+
								'<div class=\"col-12 number\"> <b>ส่วนลด: </b>';
								if(value.h_rate_discount <= 0){
									json_data += 'ไม่มี';
								}
								else if(value.h_rate_discount > 0){
									json_data += value.h_rate_discount;
								}

								if(value.h_rate_discountset == 'Percent'){
									json_data +=' %';
								}
								else if(value.h_rate_discountset == 'Bath'){
									json_data +=' THB';
								}
								else
								{
									json_data +='';
								}

					json_data += '</div>'+
								'<div class=\"col-12\"> <b>ชำระเงินล่วงหน้า: </b>';

								if(value.h_rate_deposit == 0){
									json_data +='<i class=\"fas fa-times text-danger\"></i>';
								}
								else if(value.h_rate_deposit == 1){
									json_data +='<i class=\"fas fa-check text-success\"></i>';
								}
								
					json_data += '</div>'+
								'<div class=\"col-12\"> <b>ชนิดห้องที่ร่วมรายการ: </b>';

								if(value.type == null){
									json_data += 'ไม่มี';
								}
								else{
									$.each(value.type, function(key2,value2){
										//เช็คว่าถ้ามีตัวต่อไปแล้วไม่ซ้ำ ให้ใส่ลูกน้ำคั่น
										if((typerecent == '' || typerecent == undefined) && value2.type != typerecent){
											json_data += ' '+value2.type;
											typerecent = value2.type;
										}
										if(typerecent != '' && value2.type != typerecent){
											json_data += ', '+value2.type;
											typerecent = value2.type;
										}
									});
								}
								typerecent = '';

					json_data +='</div>'+
								'<div class=\"col-12\"><a type=\"input\" class=\"btn btn-primary btn-sm updatePop sm-font-admin\"'+
							    		'data-rateid=\"'+value.h_rate_id+'\"'+
							    		'data-ratename=\"'+value.h_rate_name+'\"'+
							    		'data-ratedatestart=\"'+value.h_rate_datestart+'\"'+
							    		'data-ratedateend=\"'+value.h_rate_dateend+'\"'+
							    		'data-ratediscountset=\"'+value.h_rate_discountset+'\"'+
							    		'data-ratediscount=\"'+value.h_rate_discount+'\"'+
							    		//'data-ratedesc=\"'+value.h_rate_desc+'\"'+
							    		'data-ratedateset=\"'+value.h_rate_dateset+'\"'+
							    		'data-ratedeposit=\"'+value.h_rate_deposit+'\"'+
							    		'data-ratestatustouser=\"'+value.h_rate_statustouser+'\"'+
							    		'href=\"#edit-'+value.h_rate_id+'\" data-toggle=\"modal\"><i class=\"fas fa-pen\"></i> แก้ไข</a>'+
							    	'&nbsp;<a type=\"input\" class=\"btn btn-danger btn-sm deletePop sm-font-admin\"'+
						    			'data-rateid=\"'+value.h_rate_id+'\"'+
						    			'data-ratename=\"'+value.h_rate_name+'\"'+
						    			'href=\"#delete-'+value.h_rate_id+'\" data-toggle=\"modal\"><i class=\"fas fa-trash-alt\"></i> ลบ</a>'+
						    			'<div class=\"float-right col-5\">'+
						    			'<input type=\"checkbox\" class=\"statustype\"';

						    			if(value.h_rate_statustouser == 1){
						    				$('.statustype[data-rateid="'+value.h_rate_id+'"]').bootstrapToggle('on');
						    				json_data += ' checked ';
						    			}
						    			else{
						    				$('.statustype[data-rateid="'+value.h_rate_id+'"]').bootstrapToggle('off');
						    				json_data += ' ';
						    			}

					json_data +=	    'data-toggle=\"toggle\" data-size=\"small\" data-onstyle=\"success\" data-offstyle=\"danger\" data-width=\"120\" data-height=\"20\" data-rateid=\"'+value.h_rate_id+'\" data-ratename=\"'+value.h_rate_name+'\"></div>'+
						    			'</div></div></div>';
			    $(json_data).appendTo('#show_rate');
			});
		    //Bootstrap toggle plugin
			$('.statustype[data-toggle="toggle"]').bootstrapToggle({
				on: 'แสดงในหน้าเว็บ',
	      		off: 'ซ่อนจากหน้าเว็บ',
			});
		}
		$('.number').formatNumber();
		getFilterDataStatusOnly();
      },
      error:function(){alert('error');}
    });
}

function updatePop(rateid,ratename,ratediscountset,ratediscount,ratedeposit,ratedateset,ratedatestart,ratedateend,ratedesc,ratestatustouser){
	var html;
	var htmlquery = '';
	console.log('Status: '+ratestatustouser,'\nDiscount set: '+ ratediscountset);
	html = 	'<form action=\"#\" method=\"POST\" class=\"\" id=\"sendUpdateRateForm\" enctype=\"multipart/form-data\">'+
		   	'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\"><b>ชื่อเรทราคา</b></label></div>'+
			'<div class=\"col-12 col-md-6\" id=\"user2\" align=\"center\"><input name=\"ratenameUP\" id=\"ratenameUP\" placeholder=\"ชื่อเรทราคาใหม่\" class=\"form-control sm-font-admin\" value=\"'+ratename+'\" required></div><br>'+
								     
			'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\"><b>รายละเอียด</b></label></div>'+
			'<div class=\"col-12\" id=\"user2\" align=\"center\"><textarea id=\"ratedescUP\" name=\"ratedescUP\" class=\"form-control\">'+ratedesc+'</textarea></div><hr>'+

			'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\"><b>ส่วนลด</b></label></div>'+
				'<div class=\"col-12 sm-font-admin\">'+
					'<div class=\"form-check form-check-inline\">'+
						'<input class=\"form-check-input discountcheckUP\" type=\"radio\" name=\"discountcheckUP\" id=\"discountcheckUP1\" value=\"Bath\">'+
						'<label class=\"form-check-label\" for=\"discountcheckUP1\">บาท(THB)</label>'+
					'</div>'+
					'<div class=\"form-check form-check-inline\">'+
						'<input class=\"form-check-input discountcheckUP\" type=\"radio\" name=\"discountcheckUP\" id=\"discountcheckUP2\" value=\"Percent\">'+
						'<label class=\"form-check-label\" for=\"discountcheckUP2\">เปอร์เซน(%)</label>'+
					'</div>'+
					'<div class=\"form-check form-check-inline\">'+
						'<input class=\"form-check-input discountcheckUP\" type=\"radio\" name=\"discountcheckUP\" id=\"discountcheckUP3\" value=\"None\">'+
						'<label class=\"form-check-label\" for=\"discountcheckUP3\">ไม่มี</label>'+
					'</div>'+
				'</div>'+
				'<div class=\"col-12 row\">'+
					'<div class=\"col-4 input-group\" id=\"user2\" align=\"center\">'+
						'<input type=\"number\" min=\"0\" name=\"ratediscountUP\" id=\"ratediscountUP\" placeholder=\"ส่วนลด\" class=\"form-control\" value=\"'+ratediscount+'\" disabled hidden>'+
						'<div class="input-group-prepend">'+
							'<div class="input-group-text discounttypeUP" hidden>THB</div>'+
						'</div>'+
					'</div>'+
				'</div><br>'+

				'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\"><b>ชำระเงินล่วงหน้า</b></label></div>'+
					'<div class=\"col-12 sm-font-admin\">'+
						'<div class=\"form-check form-check-inline\">'+
							'<input class=\"form-check-input rate_depositUP\" type=\"radio\" name=\"rate_depositUP\" id=\"rate_depositUP1\" value=\"1\">'+
							'<label class=\"form-check-label\" for="rate_depositUP1\">'+
								'<b><i class=\"fas fa-check text-success\"></i></b>'+
							'</label>'+
						'</div>'+
						'<div class=\"form-check form-check-inline\">'+
							'<input class=\"form-check-input rate_depositUP\" type=\"radio\" name=\"rate_depositUP\" id=\"rate_depositUP2\" value=\"0\">'+
							'<label class=\"form-check-label\" for=\"rate_depositUP2\">'+
								'<b><i class=\"fas fa-times text-danger\"></i></b>'+
							'</label>'+
						'</div>'+
					'</div><br>'+
					'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\"><b>วันที่เริ่ม - สิ้นสุด</b></label></div>'+
						'<div class=\"col-12 row\">'+
							'<div class=\"col-12 sm-font-admin\">'+
								'<div class=\"form-check form-check-inline\">'+
									'<input class=\"form-check-input rate_datesetUP\" type=\"radio\" name=\"rate_datesetUP\" id=\"datecheckUP1\" value=\"1\">'+
									'<label class=\"form-check-label\" for=\"rate_datesetUP1\">กำหนดวัน</label>'+
								'</div>'+
								'<div class=\"form-check form-check-inline\">'+
									'<input class=\"form-check-input rate_datesetUP\" type=\"radio\" name=\"rate_datesetUP\" id=\"datecheckUP2\" value=\"0\">'+
									'<label class=\"form-check-label\" for=\"rate_datesetUP2\">ไม่มีกำหนด</label>'+
								'</div>'+
							'</div>'+
							'<div class=\"input-daterange input-group updaterateDate\" id=\"datepicker\">'+
								'<div class=\"col-3\">'+
									'<input type=\"text\" class=\"datepicker form-control checkin rate_datestartUP\" id=\"checkin3\" name=\"checkinUP\" placeholder=\"วันเริ่มต้น\" required autocomplete=\"off\" value=\"'+ratedatestart+'\" disabled hidden>'+
								'</div> <span class=\"date-to-dateUP\" hidden>-</span>'+
								'<div class=\"col-3\">'+
									'<input type=\"text\" class=\"datepicker form-control checkout rate_dateendUP\" id=\"checkout3\" name=\"checkoutUP\" placeholder=\"วันสิ้นสุด\" required autocomplete=\"off\" value=\"'+ratedateend+'\" disabled hidden>'+
								'</div>'+
							'</div>'+
							'</div><hr>'+
							'<div class=\"col-12\"><label><b>ชนิดห้องที่ร่วมรายการ</b></label>'+
								'<a href=\"#\" class=\"typeallUP\">(เลือกทั้งหมด)</a>'+
								'<a href=\"#\" class=\"distypeallUP\" hidden>(ยกเลิก)</a>'+
							'</div>'+
							'<div class=\"col-12\ querytype sm-font-admin">';

							$.getJSON("../system/query_type.php",function(data){
								$.each(data, function(key,value){
									//console.log(value.h_type_name);
									htmlquery += ' <input type=\"checkbox\" class=\"typeUP\" name=\"filter-1\" value=\"'+value.h_type_id+'\"> '+value.h_type_name+'<br>';
									//console.log(htmlquery);
								});
								//console.log(htmlquery);
								$(htmlquery).appendTo('.querytype');
							});

					html += '</div><hr>'+
							'<div align="center"><input type=\"submit\" id=\"submitsignup\" value=\"บันทึก\" class=\"btn btn-success\"><br><br><span id="successAlert" class="text-success"></span></div>'+
							'</form>';

	$('#updateRateForm').html(html);
	checkhidden(ratediscountset,ratedateset);

	CKEDITOR.replace( 'ratedescUP',{
		filebrowserBrowseUrl: 'vendor/CKeditor/ckfinder/ckfinder.html',
		filebrowserUploadUrl: 'vendor/CKeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
	});

	//datepicker_admin
	var FormRequestClass = ".updaterateDate";
  	datepicker_set(FormRequestClass); //Function in myScript.js

	//set
	$('.discountcheckUP').filter("[value='"+ratediscountset+"']").attr('checked',true);
	$('.rate_depositUP').filter("[value='"+ratedeposit+"']").attr('checked',true);
	$('.rate_datesetUP').filter("[value='"+ratedateset+"']").attr('checked',true);

	//เช็คชนิดห้องที่่ร่วมรายการใน DB
	jQuery.ajax({
		    url: "../system/admin_cmd.php",
		    dataType: "json",
		    data:{
		       command: "search_mix_ratetype",
		       rateid: rateid
		    },
		    type: "POST",
		    success:function(data){
		    	var checktype_db = [];
		    	$.each(data, function(key,value){
		    		checktype_db.push(value.h_mix_ratetype_typeid);
		    		$('.typeUP').filter("[value='"+value.h_mix_ratetype_typeid+"']").attr('checked',true);
		    	});
		    	//console.log('Type checked in db: '+ checktype_db);
		    },
		    error:function (){ alert('error');}
		});

	//Option กำหนดวัน (Update)
  	$(document).on("click", ".rate_datesetUP", function () {
  		if($(".rate_datesetUP:checked").val() == 1){
  			$("#checkin3, #checkout3").prop('disabled',false);
  			$("#checkin3, #checkout3, .date-to-dateUP").prop('hidden',false);
  		}
  		if($(".rate_datesetUP:checked").val() == 0){
  			$("#checkin3, #checkout3").prop('disabled',true);
  			$("#checkin3, #checkout3, .date-to-dateUP").prop('hidden',true);
  			$("#checkin3, #checkout3").val('');
  		}

  	});

  	//Option กำหนดส่วนลด (Update)
  	$(document).on("click", ".discountcheckUP", function () {
  		//console.log('Type discount: '+ $(this).val());
  		if($(".discountcheckUP:checked").val() == 'Bath' || $(".discountcheckUP:checked").val() == 'Percent'){
  			$("#ratediscountUP").prop('disabled',false);
  			$("#ratediscountUP").prop('hidden',false);
  			if($(".discountcheckUP:checked").val() == 'Bath'){
  				$(".discounttypeUP").html('THB.');
  				$(".discounttypeUP").prop('hidden',false);
  			}
  			else if($(".discountcheckUP:checked").val() == 'Percent'){
  				$(".discounttypeUP").html('%');
  				$(".discounttypeUP").prop('hidden',false);
  			}
  		}
  		if($(".discountcheckUP:checked").val() == 'None'){
  			$("#ratediscountUP").prop('disabled',true);
  			$("#ratediscountUP").prop('hidden',true);
  			$("#ratediscountUP").val('');
  			$(".discounttypeUP").prop('hidden',true);
  		}
  	});

  	//Option กำหนดชำระเงินล่วงหน้า (Update)
  	$(document).on("click", ".rate_depositUP", function () {
  		//console.log('Type deposit: ' + $(this).val());
  	});

  	//Option ชนิดห้องที่ร่วมรายการ (Update)
  	$(document).on("click", ".typeUP", function () {
  		getFilterDataTypeUpdateOnly()
  	});

  	//Option ชนิดห้องที่ร่วมรายการ เลือกทั้งหมด (Update)
  	$(document).on("click", ".typeallUP", function (e) {
  		e.preventDefault();
  		$('.typeUP').prop('checked',true);
  		getFilterDataTypeUpdateOnly()

  		$('.typeallUP').prop('hidden',true);
  		$('.distypeallUP').prop('hidden',false);
  	});

  	//Option ชนิดห้องที่ร่วมรายการ ยกเลิกทั้งหมด (Update)
  	$(document).on("click", ".distypeallUP", function (e) {
  		e.preventDefault();
  		$('.typeUP').prop('checked',false);
  		getFilterDataTypeUpdateOnly()

  		$('.typeallUP').prop('hidden',false);
  		$('.distypeallUP').prop('hidden',true);
  	});

  	//ส่งข้อมูลRateไป Update
  	$("#sendUpdateRateForm").submit(function(event){
  		var rate_datestart;
  		var rate_dateend;
  		var discount;

    	event.preventDefault();
    	$('#successAlert').fadeIn({ duration: 1500});
    	$('#successAlert').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>Loading .....</h5></div>');
    	for (instance in CKEDITOR.instances) {
	        CKEDITOR.instances[instance].updateElement();
	    }
	    if($(".rate_datesetUP:checked").val() == 0){
	    	rate_datestart = "0000-00-00";
	    	rate_dateend = "0000-00-00";
	    	console.log('null set '+rate_datestart);
	    }else{
	    	rate_datestart = moment(new Date($('.rate_datestart').val())).format("Y-MM-DD");
	        rate_dateend = moment(new Date($('.rate_dateend').val())).format("Y-MM-DD");
	    }
	    if($("#ratediscountUP").val() == ""){
	    	discount = 0;
	    }
	    else{
	    	discount = $("#ratediscountUP").val();
	    }
	    console.log(discount);
     	$.ajax({
	      	url: "../system/admin_cmd.php",
	      	data:{
	        	command: "updaterate",
	        	rateid: rateid,
	        	ratename: $("#ratenameUP").val(),
	        	ratedesc: $("#ratedescUP").val(),
	        	ratediscount: discount,
	        	ratediscountset: $(".discountcheckUP:checked").val(),
	        	rate_dateset: $(".rate_datesetUP:checked").val(),
	        	rate_datestart: rate_datestart,
	        	rate_dateend: rate_dateend,
	        	rate_deposit: $(".rate_depositUP:checked").val(),
	        	rate_statustouser: ratestatustouser,
	        	type: getFilterDataTypeUpdateOnly()
	      	},
	      	type: "POST",
	      	success:function(data){
		    	$('#successAlert').html('');
		    	$('#successAlert').html('บันทึกสำเร็จ <i class="fas fa-check-circle"></i>');
		    	$('#successAlert').fadeOut({ duration: 1500});
		        get_data(thispage_is);
	      	},
	      	error:function (){}
    	});
  	});
}
//Check option ตอนเริ่ม
function checkhidden(ratediscountset,ratedateset){
	//console.log('Type discount: '+ ratediscountset+ '\nDateset: '+ ratedateset);
	if(ratediscountset != 'None'){
		$("#ratediscountUP").prop('disabled',false);
  		$("#ratediscountUP").prop('hidden',false);
	}
	if(ratedateset == 1){
		$("#checkin3, #checkout3").prop('disabled',false);
  		$("#checkin3, #checkout3, .date-to-dateUP").prop('hidden',false);
	}
	if(ratediscountset == 'None'){
		$("#ratediscountUP").val("");
	}
	if(ratedateset == 0){
		$("#checkin3, #checkout3").val("");
	}

	if(ratediscountset == 'Bath'){
  		$(".discounttypeUP").html('THB.');
  		$(".discounttypeUP").prop('hidden',false);
  	}
  	else if(ratediscountset == 'Percent'){
  		$(".discounttypeUP").html('%');
  		$(".discounttypeUP").prop('hidden',false);
  	}
}

function delete_modal(rateid,ratename){
	var html = '<form action="" method="POST" id="sendDeleteRateForm">'+
					'<div class="col-12" align="left"><label for="name" style=" text-align: left;">เรทราคาห้องพัก</label></div>'+
					'<div class="col-6" align="center"><input value="'+ratename+'" name="ratenameDe" id="ratenameDe" class="form-control" disabled></div><br>'+
					'<hr>'+
					'<input type="text" value="'+rateid+'" name="rateidDe" id="rateidDe" hidden>'+
					'<input type="submit" id="submitsignup" value="ยืนยันการลบเรทราคานี้" class="btn btn-danger">'+
				'</form>';
	$('#deleteRateForm').html(html);
	$('.deletemodal').modal('show');

	//ส่งข้อมูลRoomไป Delete
	$("#sendDeleteRateForm").submit(function(event){
		event.preventDefault();
		jQuery.ajax({
		    url: "../system/admin_cmd.php",
		    data:{
		       command: "deleterate",
		       rateid: $("#rateidDe").val()
		    },
		    type: "POST",
		    success:function(data){
		    	var recent_start_page_after_edit = parseInt(localStorage.getItem('recent_start'));
		    	var recent_limit_page_after_edit = parseInt(localStorage.getItem('recent_limit'));
		    	$("#deleteRateForm").html('ลบสำเร็จ');
		        get_data(thispage_is);
		    },
		    error:function (){ alert('error');}
		}); 
	});
}
