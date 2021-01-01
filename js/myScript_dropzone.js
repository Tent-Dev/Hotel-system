/////อัพโหลดรูปแบบ ลากวางได้ และหลายไฟล์พร้อมกัน Dropzone Plugin///////
//Disabling autoDiscover
Dropzone.autoDiscover = false;
$(document).ready(function(){
    //Dropzone class
    var myDropzone = new Dropzone(".dropzone", {
		url: "system/uploadimage_hotel_multi.php",
		paramName: "file",
		uploadMultiple: true,
		parallelUploads: 100,
		maxFilesize: 10,
		maxFiles: 100,
		acceptedFiles: "image/*,application/pdf",
		autoProcessQueue: false,
		dictRemoveFile: "ยกเลิก",
		addRemoveLinks: true,
		dictCancelUpload: "ยกเลิก",
		dictCancelUploadConfirmation: "ยกเลิก",
		dictDefaultMessage: "คลิก หรือลากไฟล์รูปภาพมาไว้ที่นี่",
		dictFallbackMessage: "คลิก หรือลากไฟล์รูปภาพมาไว้ที่นี่", //For not browser not support
		thumbnailWidth: null,
    	thumbnailHeight: null,

		init: function(){
			//ปรับขนาดรูป thumnail
			this.on("thumbnail", function(file, dataUrl) {
            	$('.dz-image').last().find('img').attr({width: '100%', height: '100%'});
        	});

		    //เอาvalue ในฟอร์มทั้งหมดออกมา เพื่อส่งไปพร้อมกับรูป
			this.on('sendingmultiple', function(file, xhr, formData) {
				var data = $('#dropzone').serializeArray();
				console.log(data);
				    $.each(data, function(key, el) {
				       formData.append(el.name, el.value);
				    });
			});

			this.on("processing", function() {
		    	myDropzone.options.autoProcessQueue = true;
		    	$('#successAlert_add').html('<i class="fas fa-spinner fa-spin"></i>&nbsp;กำลังบันทึกข้อมูล .....');
			});

			this.on("successmultiple", function(file, response) {
				myDropzone.options.autoProcessQueue = false;
				$('#successAlert_add').fadeIn({ duration: 1500});
				get_data(thispage_is);
			  	$('#successAlert_add').html('เพิ่มชนิดห้องสำเร็จ <i class="fas fa-check-circle"></i>');
	    		$('#successAlert_add').fadeOut({ duration: 1500});
	    		$('#dropzone').trigger("reset");
			  	myDropzone.removeAllFiles(file);
			});
		}
	});
	
	$("#dropzone").submit(function(e){
		e.preventDefault();
		//เช็คว่า ถ้าไม่มีการอัพโหลดไฟล์ ก็ส่งค่าไปได้
		if (myDropzone.getQueuedFiles().length === 0) {
		    var blob = new Blob();
		    blob.upload = { 'chunked': myDropzone.defaultOptions.chunking };
		    myDropzone.uploadFile(blob);
		} else {
		    myDropzone.processQueue();
		}
	});
	////////////////////////////////////////////////////////////////

	//ปุ่ม แก้ไข Type of room
	$(document).on("click", ".updatePop", function () {
				var test = $('.editmodal').attr('id', 'edit-'+$(this).data('typeid'));
				var typeid = $(this).data('typeid');
				var typename = $(this).data('typename');
				var typeprice = $(this).data('typeprice');
				var typecapacity = $(this).data('typecapacity');
				var typebedid = $(this).data('typebedid');
				var typebed = $(this).data('typebed');
				var typebedtotal = $(this).data('typebedtotal');
				var typedesc = $(this).data('typedesc');
				updatePop(typeid,typename,typeprice,typecapacity,typebed,typedesc,typebedtotal,typebedid);
				$('.editmodal').modal('show');
	});

	//ปุ่ม แก้ไข Cover image
	$(document).on("click", ".coverPop", function () {
				var test = $('.covermodal').attr('id', 'cover-'+$(this).data('typeid'));
				var typeid = $(this).data('typeid');
				var typename = $(this).data('typename');
				coverPop(typeid,typename);
				$('.covermodal').modal('show');
	});
});

function updatePop(typeid,typename,typeprice,typecapacity,typebed,typedesc,typebedtotal,typebedid){

  	var html;
  	var imageDelSelect = getFilterDataImageOnly();
  	var htmlquery = "";
  	html = '<form action=\"\" method=\"POST\" id=\"dropzoneUpdate\" class="sm-font-admin" enctype=\"multipart/form-data\">'+
				'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\">ชื่อชนิดห้อง</label></div>'+
				'<div class=\"col-6\" id=\"user2\" align=\"center\"><input value=\"'+typename+'\" name=\"typenameUP\" id=\"typenameUP\" placeholder=\"ชื่อชนิดห้อง\" class=\"form-control sm-font-admin\" required></div><br>'+
								     
				'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\">รายละเอียด</label></div>'+
				'<div class=\"col-6\" id=\"user2\" align=\"center\"><textarea id=\"typedescUP\" name=\"typedescUP\" class=\"form-control sm-font-admin\" id=\"exampleFormControlTextarea1\" rows=\"3\">'+typedesc+'</textarea></div><br>'+

				'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\">รูปภาพประกอบ</label> <a class=\"showimagecheckbtn\" style=\"color: red;\" href=\"#\"><b>ลบรูปภาพ</b></a>'+
				'&nbsp;<a class=\"showimagecheckDelbtn\" style=\"color: red;\" hidden><b>ลบ 0 รูป</b></a>'+
				'&nbsp;<a class=\"showimagecheckAllDelbtn\" href=\"#\" style=\"color: red;\" hidden><b>เลือกทั้งหมด</b></a>'+
				'&nbsp;<a class=\"showimagecheckCancelAllDelbtn\" href=\"#\" style=\"color: red;\" hidden><b>ไม่เลือกทั้งหมด</b></a>'+
				'&nbsp;<a class=\"showimagecheckCancelbtn\" href=\"#\" hidden><b>ยกเลิก</b></a></div>'+

				'<div class=\"dropzone dropzone2 col-10 uploaddiv\" id=\"dropzone2\"></div>'+
				'<br>'+

				'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\">ราคา</label></div>'+
				'<div class=\"col-6\" id=\"user2\" align=\"center\"><input value=\"'+typeprice+'\" name=\"typepriceUP\" id=\"typepriceUP\" placeholder=\"ราคา\" class=\"form-control sm-font-admin\" required></div><br>'+

				'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\">จำนวนคนสูงสุดต่อห้อง</label></div>'+
				'<div class=\"col-6\" id=\"user2\" align=\"center\"><input value=\"'+typecapacity+'\" name=\"typecapacityUP\" id=\"typecapacityUP\" placeholder=\"จำนวนคนสูงสุดต่อห้อง\" class=\"form-control sm-font-admin\" required></div><br>'+

				'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\">ประเภทเตียง</label></div>'+
				'<div class=\"col-6\" id=\"user2\" align=\"center\"><select class=\"form-control querybed sm-font-admin\" name=\"typebedUP\" id=\"typebedUP\" required>';


					$.getJSON("../system/query_bed.php",function(data){
						$.each(data, function(key,value){
							htmlquery += '<option value=\"'+value.h_type_bed_id+'\"';
											if(value.h_type_bed_id == typebedid){
												htmlquery += ' selected';
											}
							htmlquery += '>'+value.h_type_bed_name+'</option>';
						});
						$(htmlquery).appendTo('.querybed');
					});
			
			html +='</select></div><br>'+

				'<div class=\"col-12\" align=\"left\"><label for=\"name\" style=\" text-align: left;\">จำนวนประเภทเตียงต่อ 1 ห้อง</label></div>'+
				'<div class=\"col-6\" id=\"user2\" align=\"center\"><input value=\"'+typebedtotal+'\" name=\"typebedtotalUP\" id=\"typebedtotalUP\" placeholder=\"จำนวนประเภทเตียงต่อ 1 ห้อง\" class=\"form-control sm-font-admin\" required></div><br>'+
				'<hr>'+
				'<input type=\"text\" value=\"'+typeid+'\" name=\"typeidUP\" id=\"typeidUP\" hidden>'+
				'<input type=\"text\" value=\"\" name=\"imageNameDel\" id=\"imageNameDel\" hidden>'+
				'<input type=\"submit\" id=\"submitsignup\" value=\"บันทึก\" class=\"btn btn-success\"><br><br>'+
				'<span id="successAlert" class="text-success"></span>'+
			'</form>';

	$('#updateTypeForm').html(html);
	var myDropzoneUpdate = new Dropzone(".dropzone2", {
		url: "system/updateTypeWithImage.php",
		paramName: "file",
		uploadMultiple: true,
		parallelUploads: 100,
		maxFilesize: 10,
		maxFiles: 100,
		acceptedFiles: "image/*,application/pdf",
		autoProcessQueue: false,
		dictRemoveFile: "",
		addRemoveLinks: true,
		dictCancelUpload: "ยกเลิก",
		dictCancelUploadConfirmation: "ยกเลิก",
		dictDefaultMessage: "คลิก หรือลากไฟล์รูปภาพมาไว้ที่นี่",
		dictFallbackMessage: "คลิก หรือลากไฟล์รูปภาพมาไว้ที่นี่", //For not browser not support
		thumbnailWidth: null,
    	thumbnailHeight: null,
    	dictRemoveFileConfirmation: "ลบภาพนี้?",

		init: function() {

			//ปรับขนาดรูป thumnail
			this.on("thumbnail", function(file, dataUrl) {
            	$('.dz-image').last().find('img').attr({width: '100%', height: '100%'});
            	//myDropzoneUpdate.options.dictRemoveFile = "ลบออกจากระบบ";
        	});

        	this.on("addedfile", function(file) {
			    /* Maybe display some more file information on your page */
			    if($('.showimagecheck').attr('hidden') == undefined){
			    	$('.showimagecheck').attr('hidden',false);
			    }
			});

			//ลบรูปออกจาก database (functionนี้ ไม่ได้ใช้แล้ว)
			this.on("removedfile", function (file) {
				var typeid = $(".updatePop").data('typeid');
	            $.post({
	                url: '../system/admin_cmd.php',
	                data: {
	                	command: "deleteimage",
	                	image_name: file.name,
	                	typeid: typeid
	                	//image_path: path //ยังไม่ได้ใช้
	                },
	                dataType: 'json',
	                success: function (data) {
	                 
	                }
	            });
	        });

	        //เอาvalue ในฟอร์มทั้งหมดออกมา เพื่อส่งไปพร้อมกับรูป
			this.on('sendingmultiple', function(file, xhr, formData) {
				var data = $('#dropzoneUpdate').serializeArray();
				console.log('Get data in form\n',data);
				$.each(data, function(key, el) {
					formData.append(el.name, el.value);
				});
			});

			this.on("processing", function() {
				myDropzoneUpdate.options.autoProcessQueue = true;
				$('#successAlert').html('<i class="fas fa-spinner fa-spin"></i>&nbsp;กำลังอัพเดท .....');
			});

			//เมื่ออัพเดทสำเร็จ
			this.on("successmultiple", function(file, response) {
				myDropzoneUpdate.removeFile(file);
				//$('#dropzoneUpdate').html(response);
			});

			this.on("completemultiple",function(file){
				$('.dropzone2').html('');
				$('.imagedelcheck').not(this).prop('checked', false);
				$('.showimagecheck').attr('hidden',true);
				$('.showimagecheckDelbtn').attr('hidden',true);
				$('.showimagecheckCancelbtn').attr('hidden',true);
				$('.showimagecheckAllDelbtn').attr('hidden',true);
				$('.dz-remove').attr('hidden',false);
				$('.showimagecheckbtn').attr('hidden',false);
				$('.showimagecheckCancelAllDelbtn').attr('hidden',true);
				$('#imageNameDel').val("");
				myDropzoneUpdate.options.autoProcessQueue = false;
				myDropzoneUpdate.removeAllFiles();
				jQuery.ajax({
				    url: "../system/getimage_hotel.php",
				    data:{
				      command: "getimagegallery",
				      typeid: typeid
				    },
				    type: "POST",
				    success:function(data,file){
				    	var checkboxhtml;
						$.each(data, function(key,value){
							var mockFile = { name: value.name, size: value.size };
							myDropzoneUpdate.emit("addedfile", mockFile);
							myDropzoneUpdate.emit("thumbnail", mockFile, "../system/upload_hotel/"+value.name);
				            myDropzoneUpdate.emit("complete", mockFile);
				            var existingFileCount = 1; // The number of files already uploaded
							myDropzoneUpdate.options.maxFiles = myDropzoneUpdate.options.maxFiles - existingFileCount;
				        });
				    },
					error:function (){ alert('error get');}
				});
				$('#successAlert').fadeIn({ duration: 1500});
			  	$('#successAlert').html('บันทึกสำเร็จ <i class="fas fa-check-circle"></i>');
	    		$('#successAlert').fadeOut({ duration: 1500});
	    		get_data(thispage_is);
			});

			//ส่งค่าไปให้ส่งไปให้ getimage_hotel
			jQuery.ajax({
			    url: "../system/getimage_hotel.php",
			    data:{
			      command: "getimagegallery",
			      typeid: typeid
			    },
			    type: "POST",
			    success:function(data,file){
			    	var checkboxhtml;
					$.each(data, function(key,value){
						var mockFile = { name: value.name, size: value.size };
						myDropzoneUpdate.emit("addedfile", mockFile);
						myDropzoneUpdate.emit("thumbnail", mockFile, "../system/upload_hotel/"+value.name);
			            myDropzoneUpdate.emit("complete", mockFile);
			            var existingFileCount = 1; // The number of files already uploaded
						myDropzoneUpdate.options.maxFiles = myDropzoneUpdate.options.maxFiles - existingFileCount;
			        });
			    },
				error:function (){ alert('error get');}
			});

	  }
	});

	//ปุ่มลบหลายรายการ image
	$('.showimagecheckbtn').on('click',function(event){
		event.preventDefault();
		var imageDelSelect = getFilterDataImageOnly();
		$('.showimagecheck').attr('hidden',false);
		$('.showimagecheckDelbtn').html('<b>ลบ '+imageDelSelect.length+' รูป</b> |');
		$('.showimagecheckDelbtn').attr('hidden',false);
		$('.showimagecheckCancelbtn').attr('hidden',false);
		$('.showimagecheckAllDelbtn').attr('hidden',false);
		$('.dz-remove').attr('hidden',true);
		$('.showimagecheckbtn').attr('hidden',true);

	});

	//ปุ่มยกเลิกลบหลายรายการ image
	$('.showimagecheckCancelbtn').on('click',function(event){
		event.preventDefault();
		$('.imagedelcheck').not(this).prop('checked', false);
		$('.showimagecheck').attr('hidden',true);
		$('.showimagecheckDelbtn').attr('hidden',true);
		$('.showimagecheckCancelbtn').attr('hidden',true);
		$('.showimagecheckAllDelbtn').attr('hidden',true);
		$('.dz-remove').attr('hidden',false);
		$('.showimagecheckbtn').attr('hidden',false);
		$('.showimagecheckCancelAllDelbtn').attr('hidden',true);
		$('#imageNameDel').val("");
	});

	//Checkรูปที่เลือก
	$(document).on("click", ".imagedelcheck", function () {	
		var imageDelSelect = getFilterDataImageOnly();
		$('.showimagecheckDelbtn').html('<b>ลบ '+imageDelSelect.length+' รูป</b> |');
		$('#imageNameDel').val(imageDelSelect);

	});

	//เลือกทั้งหมด
	$('.showimagecheckAllDelbtn').on('click',function(event){
		event.preventDefault();
		$('.imagedelcheck').not(this).prop('checked', true);
		var imageDelSelect = getFilterDataImageOnly();
		$('.showimagecheckDelbtn').html('<b>ลบ '+imageDelSelect.length+' รูป</b> |');
		$('.showimagecheckAllDelbtn').attr('hidden',true);
		$('.showimagecheckCancelAllDelbtn').attr('hidden',false);
		$('#imageNameDel').val(imageDelSelect);
	});

	//ยกเลิกเลือกทั้งหมด
	$('.showimagecheckCancelAllDelbtn').on('click',function(event){
		event.preventDefault();
		$('.imagedelcheck').not(this).prop('checked', false);
		var imageDelSelect = getFilterDataImageOnly();
		$('.showimagecheckDelbtn').html('<b>ลบ '+imageDelSelect.length+' รูป</b> |');
		$('.showimagecheckAllDelbtn').attr('hidden',false);
		$('.showimagecheckCancelAllDelbtn').attr('hidden',true);
		$('#imageNameDel').val("");
	});

	//เช็คว่า ถ้าไม่มีการอัพโหลดไฟล์ ก็ส่งค่าไปได้
	$("#dropzoneUpdate").submit(function(e){
		e.preventDefault();
		if (myDropzoneUpdate.getQueuedFiles().length === 0) {
			var blob = new Blob();
			blob.upload = { 'chunked': myDropzoneUpdate.defaultOptions.chunking };
			myDropzoneUpdate.uploadFile(blob);
		}else {
			myDropzoneUpdate.processQueue();
		}
		$('#successAlert').fadeIn({ duration: 1500});
		$('#successAlert').html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>&nbsp;Loading .....</h5></div>');
	});

	$(".editmodal").on("hidden.bs.modal", function () {
		myDropzoneUpdate.removeAllFiles();
	});
}

function coverPop(typeid,typename){

  	var html;

  	html = '<form action=\"\" method=\"POST\" id=\"dropzoneCover\" enctype=\"multipart/form-data\">'+
				'<div class=\"col-12\" align=\"center\"><label for=\"name\" style=\" text-align: left;\"><b>ชื่อชนิดห้อง: </b></label>'+' '+typename+'</div>'+
				'<div class=\"dropzone dropzone3 col-10 uploaddiv\" id=\"dropzone2\"></div>'+

				'<hr>'+
				'<input type=\"text\" value=\"'+typeid+'\" name=\"typeidCo\" id=\"typeidCo\" hidden>'+
				'<input type=\"text\" value=\"1\" name=\"covercheck\" id=\"covercheck\" hidden>'+
				'<input type=\"text\" value=\"\" name=\"oldimage\" id=\"oldimage\" hidden>'+
				'<button type=\"submit\" id=\"submitupload\" class=\"btn btn-success\" disabled><i class="fas fa-upload"></i> อัปโหลด</button><br><br>'+
				'<span id="successAlert" class="text-success"></span>'+
			'</form>';

	$('#coverForm').html(html);

	var myDropzoneCover = new Dropzone(".dropzone3", {
		url: "system/updateCoverImage.php",
		paramName: "file",
		uploadMultiple: false,
		parallelUploads: 1,
		maxFilesize: 10,
		maxFiles: 1,
		acceptedFiles: "image/*,application/pdf",
		autoProcessQueue: false,
		dictRemoveFile: "ลบ",
		addRemoveLinks: true,
		dictCancelUpload: "ยกเลิก",
		dictCancelUploadConfirmation: "ยกเลิก",
		dictDefaultMessage: "คลิก หรือลากไฟล์รูปภาพมาไว้ที่นี่",
		dictFallbackMessage: "คลิก หรือลากไฟล์รูปภาพมาไว้ที่นี่", //For not browser not support
		thumbnailWidth: null,
    	thumbnailHeight: null,
    	dictRemoveFileConfirmation: "ลบรูปภาพตัวอย่างนี้?",

		init: function() {

			this.on('addedfile', function(file) {
				console.log('File length: ' +myDropzoneCover.files.length);
				if (this.files.length > 1) {
					myDropzoneCover.options.dictRemoveFile = "ยกเลิก";
					myDropzoneCover.options.dictRemoveFileConfirmation = "";
					console.log('Prepare deleteing old file: '+ this.files[0].name);

					if(this.files[0].name !== old_file){
						console.log('Just remove this file: '+ this.files[0].name);
						this.removeFile(this.files[0]);
						console.log('%c File length: '+this.files.length,'background: #222; color: #bada55');
					}
					else{
						console.log('%c Auto remove Thumbnail (db) (for clear array): '+ this.files[0].name ,'background: #222; color: #bada55');
						this.removeFile_custom(this.files[0]);
						console.log('%c File length: '+this.files.length,'background: #222; color: #bada55');
						$('.dz-remove').html('ยกเลิก');
					}
	                $('#submitupload').prop('disabled',false);
            	}
            	if (this.files.length == 1) {
					myDropzoneCover.options.dictRemoveFile = "ยกเลิก";
					myDropzoneCover.options.dictRemoveFileConfirmation = "";
                	$('#submitupload').prop('disabled',false);
            	}
			});

			// ตั้งให้อัพโหลดได้แค่ 1 รูปต่อครั้ง ถ้ากด เลือกไฟล์ ให้ทับอันก่อนหน้า (ใช้ได้แค่ตอนเพิ่มไฟล์ครั้งแรก)
            this.on("maxfilesexceeded", function (file) {
                this.removeAllFiles();
                this.addFile(file);
            });

            this.on("error", function(file, message) { 
                alert(message);
                this.removeAllFiles();
    		});

			//ปรับขนาดรูป thumnail
			this.on("thumbnail", function(file, dataUrl) {
            	$('.dz-image').last().find('img').attr({width: '100%', height: '100%'});
        	});

			//ลบรูปออกจาก database
			this.on("removedfile", function (file) {
				console.log('Delete file: '+file.name);
	            $.ajax({
	                url: '../system/admin_cmd.php',
	                data: {
	                	command: "deleteimage",
	                	image_name: file.name,
	                	typeid: typeid
	                },
	                type: "POST",
	                dataType: 'json',
	                success: function (data,file) {
	                	alert('ลบสำเร็จ');
	                },error: function(file){
	                	if(myDropzoneCover.files.length == 0){
							console.log('Removed file (before uploade)');
							$('#submitupload').prop('disabled',true);
						}
	                }
	            });
	        });

	        //เอาvalue ในฟอร์มทั้งหมดออกมา เพื่อส่งไปพร้อมกับรูป
			this.on('sending', function(file, xhr, formData) {
				var data = $('#dropzoneCover').serializeArray();
				console.log(data);
				$.each(data, function(key, el) {
					formData.append(el.name, el.value);
				});
			});

			this.on("processing", function() {
				myDropzoneCover.options.autoProcessQueue = true;
				$('#successAlert').html('<i class="fas fa-spinner fa-spin"></i>&nbsp;กำลังบันทึกข้อมูล .....');
			});

			//เมื่ออัพโหลดเสร็จ
			this.on("success", function(file, response) {
				myDropzoneCover.options.dictRemoveFile = "ลบ";
				myDropzoneCover.options.dictRemoveFileConfirmation = "ลบรูปภาพตัวอย่างนี้"
				myDropzoneCover.removeFile(file);
				$('.dropzone3').html('');
				$.ajax({
				    url: "../system/getimage_hotel.php",
				    data:{
				      command: "getimagecover",
				      typeid: typeid
				    },
				    type: "POST",
				    success:function(data,file){
				    	var checkboxhtml;
				    	
						$.each(data, function(key,value){
							var mockFile = { name: value.name, size: value.size };
							myDropzoneCover.emit("addedfile", mockFile);
							myDropzoneCover.emit("thumbnail", mockFile, "../system/upload_hotel/"+value.name);
				            myDropzoneCover.emit("complete", mockFile);
				            myDropzoneCover.files.push(mockFile); //เอาไฟล์ปัจจุบันเข้าไปใน Array เพื่อให้นับจำนวนไฟล์ได้
				            old_file = value.name;
							console.log('File from Database(img Cover) added.\nFile length: ' + myDropzoneCover.files.length+
										'\nFile name: '+ value.name);
				        });
						console.log('File from Db name: '+ old_file);
						$('#oldimage').val(old_file);
				    },
					error:function (){ alert('error send data to getimagecover');}
				});
				myDropzoneCover.options.autoProcessQueue = false;
				$('#successAlert').fadeIn({ duration: 1500});
				get_data(thispage_is);
			  	$('#successAlert').html('บันทึกสำเร็จ <i class="fas fa-check-circle"></i>');
	    		$('#successAlert').fadeOut({ duration: 1500});
			});

			//ส่งค่าไปให้ Model และส่งไปให้ getimage_hotel เรียกรูปปัจจุบันออกมา
			console.log('Type ID: '+typeid,'\nType name: '+typename);
			var old_file;
			$.ajax({
			    url: "../system/getimage_hotel.php",
			    data:{
			      command: "getimagecover",
			      typeid: typeid
			    },
			    type: "POST",
			    success:function(data,file){
			    	var checkboxhtml;
			    	
					$.each(data, function(key,value){
						var mockFile = { name: value.name, size: value.size };
						myDropzoneCover.emit("addedfile", mockFile);
						myDropzoneCover.emit("thumbnail", mockFile, "../system/upload_hotel/"+value.name);
			            myDropzoneCover.emit("complete", mockFile);
			            myDropzoneCover.files.push(mockFile); //เอาไฟล์ปัจจุบันเข้าไปใน Array เพื่อให้นับจำนวนไฟล์ได้
			            old_file = value.name;
						console.log('File from Database(img Cover) added.\nFile length: ' + myDropzoneCover.files.length+
									'\nFile name: '+ value.name);
			        });
					console.log('File from Db name: '+ old_file);
					$('#oldimage').val(old_file);
			    },
				error:function (){ alert('error send data to getimagecover');}
			});

	  }
	});

	//เช็คว่า ถ้าไม่มีการอัพโหลดไฟล์ ก็ส่งค่าไปได้
	$("#dropzoneCover").submit(function(e){
		e.preventDefault();
		if (myDropzoneCover.getQueuedFiles().length === 0) {
			var blob = new Blob();
			blob.upload = { 'chunked': myDropzoneCover.defaultOptions.chunking };
			myDropzoneCover.uploadFile(blob);
		}else {
			myDropzoneCover.processQueue();
		}
	});
}

function getFilterDataImageOnly() {
	var filter2 = [];
	$('.imagedelcheck:checked').each(function(){
		filter2.push($(this).val());
		console.log("Image select: ",filter2);
	});
	return filter2;
}