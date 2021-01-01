$(document).ready(function(){
 	//search roomlist real time
 	get_data(page);
  	$('.typefilter').click(function(){
        get_data(page);
  	});

	//ส่งค่าไปให้ Model ลบ รายการเดียว
	$(document).on("click", ".deletePop", function () {
		var test = $('.deletemodal').attr('id', 'delete-'+$(this).data('typeid'));
	    var typeid = $(this).data('typeid');
	    var typename = $(this).data('typename');
	    delete_model(typeid,typename);
 	});

	//เช็คชนิดห้องที่เลือก (status)
	$(document).on("click", ".toggle", function (e) {
  		e.preventDefault();
  		var typeid = $(this).find('.statustype').data('typeid'); //หาid จาก elementลูก
  		var check;

  		if($(this).find('.statustype').prop('checked')){
  			$("#boxof-"+typeid).animate({backgroundColor:''});
  			check = 1;
  		}else{
  			$("#boxof-"+typeid).animate({backgroundColor:'#E5E4E2'});
  			check = 2;
  		}
  		$.ajax({
		    url: "../system/admin_cmd.php",
		    data:{
		    	command: "statustype",
		        typeid: typeid,
		        statustouser: check
		    },
		    type: "POST",
		    success:function(data,e){
		    },
		    error:function (){ alert('error');}
		});
	});

	$('#addtype').on('hidden.bs.modal', function () {
        $('#dropzone').trigger("reset");
    });
});
//เช็คว่า typeไหน แสดง/ซ่อนบ้าง และปรับสีdiv
function getFilterDataStatusOnly() {
	var typeid;
	var overall = [];
   	$('.toggle').find('.statustype:checked').each(function(){
    	typeid = $(this).data('typeid');
    	overall.push($(this).data('typename'));
    $("#boxof-"+typeid).css("background-color",'');
    });
    var overall = [];
   	$('.toggle').find(".statustype:checkbox:not(:checked)").each(function(){
    	typeid = $(this).data('typeid');
    	overall.push($(this).data('typename'));
    	$("#boxof-"+typeid).css("background-color",'#E5E4E2');
    });
}
var page;
if(page == undefined) page = 1;
 //Search typelist
function get_data(page) {
	if(page == undefined) page = 1;
    $('#show_type').append('<div id="loading"><h5><i class=\"fas fa-spinner fa-spin\"></i>Loading .....</h5></div>');
    console.log('Page send: '+page);
    var capacity = getFilterData('capacity');
    var bed = getFilterData('bed');
    var statustouser = $('.status:checked').val();
    $.ajax({
    	url:"../system/search_cmd.php",
    	method:"POST",
    	type: 'json',
    	data:{
        	command: "search_typelist",
        	capacity: capacity,
       		bed: bed,
        	statustouser: statustouser,
        	page: page
     	},
      	success:function(data){
      		data = JSON.parse(data);
      		new_pagination(data,page);
			if(data.html != undefined){
				$('#show_type').html('No Typelist found.');
				$('.sumresult').html('&nbsp;0');
				console.log('after had deleted. This page is null!','\nCurrent page is: '+thispage_is);
				new_pagination(data,thispage_is,true);
			}
			else{
				$('#show_type').html('');
				$.each(data.result,function(key,value){
					var json_data = '<div class=\"content-box mb-3 content-lighten sumfromcmd sm-font-admin\" id=\"boxof-'+value.h_type_id+'\">'+
									'<div class=\"col-12\">'+
										'<div class=\"col-12\"> <b>Type name: </b>'+value.h_type_name+'</div><hr>'+
										'<div class=\"col-12 col-md-6\"> <b>Description: </b>'+value.h_type_desc+'</div><br>'+
										 '<div class=\"col-12\"> <b>Image: </b>';

										$.each(value.imagegroup, function(key2,value2){
											if(value2.image.length !== 0){
												json_data +='<a href=\"#\" class=\"image-link\" data-typeid = \"'+value.h_type_id+'\">'+
															'<img class=\"rounded img-thumb-admin\" src=\"'+value2.image+'\" style=\"width:150px;height:100px\"></a>&nbsp;';
											}
										});
							json_data += '</div><br>'+
										'<div class=\"col-12\"> <b>Image Cover: </b>';

										$.each(value.imagecover, function(key3,value3){
											if(value3.image.length !== 0){
												json_data +='<img class=\"rounded img-thumb-admin\" src=\"'+value3.image+'\" style=\"width:150px;height:100px\">&nbsp;';
											}
										});

							json_data += '</div><hr>'+
										'<div class=\"col-12 number\"> <b>Price: </b>'+value.h_type_price+' THB</div>'+
										'<div class=\"col-12\"> <b>Capacity: </b>'+value.h_type_capacity+'</div>'+
										'<div class=\"col-12\"> <b>Bed: </b>'+value.h_type_bed+' x'+value.h_type_bedtotal+'</div>'+
										'<div class=\"col-12\"><a type=\"input\" class=\"btn btn-primary btn-sm updatePop sm-font-admin\"'+
									    		'data-typeid=\"'+value.h_type_id+'\"'+
									    		'data-typename=\"'+value.h_type_name+'\"'+
									    		'data-typeprice=\"'+value.h_type_price+'\"'+
									    		'data-typecapacity=\"'+value.h_type_capacity+'\"'+
									    		'data-typebedid=\"'+value.h_type_bed_id+'\"'+
									    		'data-typebed=\"'+value.h_type_bed+'\"'+
									    		'data-typedesc=\"'+value.h_type_desc+'\"'+
									    		'data-typebedtotal=\"'+value.h_type_bedtotal+'\"'+
									    		'href=\"#edit-'+value.h_type_id+'\" data-toggle=\"modal\"><i class=\"fas fa-pen\"></i> แก้ไข</a>'+
									    	'&nbsp;<a type=\"input\" class=\"btn btn-info btn-sm coverPop sm-font-admin\"'+
								    			'data-typeid=\"'+value.h_type_id+'\"'+
								    			'data-typename=\"'+value.h_type_name+'\"'+
								    			'href=\"#cover-'+value.h_type_id+'\" data-toggle=\"modal\"><i class=\"fas fa-images\"></i> เลือกรูปตัวอย่าง</a>'+
									    	'&nbsp;<a type=\"input\" class=\"btn btn-danger btn-sm deletePop sm-font-admin\"'+
								    			'data-typeid=\"'+value.h_type_id+'\"'+
								    			'data-typename=\"'+value.h_type_name+'\"'+
								    			'href=\"#delete-'+value.h_type_id+'\" data-toggle=\"modal\"><i class=\"fas fa-trash-alt\"></i> ลบ</a>'+
								    			'<div class=\"float-right col-5\">'+
								    			'<input type=\"checkbox\" class=\"statustype\"';
								    			if(value.h_type_statustouser == 1){
								    				$('.statustype[data-typeid="'+value.h_type_id+'"]').bootstrapToggle('on');
								    				json_data += ' checked ';
								    			}
								    			else{
								    				$('.statustype[data-typeid="'+value.h_type_id+'"]').bootstrapToggle('off');
								    				json_data += ' ';
								    			}
								    			//$('.toggle').bootstrapToggle({typeid: data[i].h_type_id});

							json_data +=	    'data-toggle=\"toggle\" data-size=\"small\" data-onstyle=\"success\" data-offstyle=\"danger\" data-width=\"120\" data-height=\"20\" data-typeid=\"'+value.h_type_id+'\" data-typename=\"'+value.h_type_name+'\"></div>'+
								    			'</div>'+
									'</div>'+
								'</div>';
				    $(json_data).appendTo('#show_type');
				});
			}
		    //Bootstrap toggle plugin
			$('.statustype[data-toggle="toggle"]').bootstrapToggle({
				on: 'แสดงในหน้าเว็บ',
	      		off: 'ซ่อนจากหน้าเว็บ',
			});

			$('.number').formatNumber();
			getFilterDataStatusOnly();
			//Popup MagneficPopup plugin
		    $('.image-link').on('click',function(event){
		      event.preventDefault();
		      var typeid = $(this).data('typeid');
		      $.ajax({
		        	url: "../system/cmd.php",
		        	dataType: "json",
		        	data:{
		            	command: "findimage",
		            	typeid: typeid
		          	},
		          	type: "POST",
		          	success:function(data){
			            console.log('success');
			            $.magnificPopup.open({
			              items: data,
			              gallery: {
			                enabled: true
			              },
			              type: 'image',
			              mainClass: 'mfp-fade',
		              	  removalDelay: 300,
			              callbacks:{
			                change: function() {
			                    if (this.isOpen) {
			                        this.wrap.addClass('mfp-open');
			                    }
			                }
			              }
			            });
		          	},
		        	error:function (){alert('error');}
		      });
		    });
      	},
      	error:function(){alert('error');}
    });
}

function delete_model(typeid,typename){
	var html = 	'<form action="" method="POST" id="sendDeleteTypeForm">'+
					'<div class="col-12" align="left"><label for="name" style=" text-align: left;">ชนิดห้องพัก</label></div>'+
					'<div class="col-6" align="center"><input value="'+typename+'" name="typenameDe" id="typenameDe" class="form-control" disabled></div><br>'+
					'<hr>'+
					'<input type="text" value="'+typeid+'" name="typeidDe" id="typeidDe" hidden>'+
					'<input type="submit" id="submitsignup" value="ยืนยันการลบชนิดห้องพักนี้" class="btn btn-danger">'+
				'</form>';

	$('#deleteTypeForm').html(html);
	$('.deletemodal').modal('show');

	//ส่งข้อมูลRoomไป Delete
  	$("#sendDeleteTypeForm").submit(function(event){
	    event.preventDefault();
	    	jQuery.ajax({
	    	url: "../system/admin_cmd.php",
	    	data:{
	        	command: "deletetype",
	        	typeid: $("#typeidDe").val()
	    	},
	    	type: "POST",
	    	success:function(data){
	        	$("#deleteTypeForm").html('ลบสำเร็จ'); //แสดงสถานะ
	        	get_data(thispage_is);
	    	},
	    	error:function (){ alert('error');}
	    }); 
	});
}