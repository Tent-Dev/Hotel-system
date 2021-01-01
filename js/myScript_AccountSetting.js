$( document ).ready(function() {

  //ส่งข้อมูล update account
  $("#sendUpdateForm").submit(function(event){
    event.preventDefault(); //รีหน้าเดิม
    Upload_image('upload_profile');
    setTimeout(Update_account, 500);
  });

  //Changepassword
  $("#sendChangePasswordForm").submit(function(event){
    $("#changePasswordSuccess").html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>กำลังบันทึก .....</h5></div>');
    event.preventDefault(); //รีหน้าเดิม
    var validateForm = changePassFormValidate(); //ให้ตัวแปรนี้ แทนฟังชั่นอื่น
    if(validateForm){
    
      jQuery.ajax({
      url: "../system/cmd.php",
      data:{
        command: "changePass",
        UID: $("#UID").val(),
        pass: $("#pass").val(),
        oldpassword: $("#oldpassword").val(),
        approve: $("#approve").val()
      },
      type: "POST",
      success:function(data){
        $("#changePasswordSuccess").html('');
        $("#changePasswordSuccess").html(data);//แสดงสถานะ
      },
      error:function (){}
      });
    }
  });


  $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });

  //ดูรูปตัวอย่างก่อนอัพโหลดรูปโปรไฟล์
  $("#fileUpload").change(function() {
    readURL(this);
  });

  $('#btnDelete_image_account').click(function(event){
    event.preventDefault();
    $('.imgPreview').attr('src', '../system/upload_profile/404.png');
    $('.custom-file-label').html('เลือกไฟล์');
    $('#fileUpload')[0].value = '';
    $('#hidFileId').val('../system/upload_profile/404.png');
  });
      
});

//ตัวอย่างภาพก่อนอัพโหลด
function readURL(input) {
  var acceptedImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
  var result_checktype = $.inArray(input.files[0].type, acceptedImageTypes);
  console.log(result_checktype,'\n'+input.files[0].type);
  if (input.files && input.files[0] && result_checktype !== -1) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('.imgPreview').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }else{
    alert('ไฟล์ไม่ถูกต้อง');
    $('.imgPreview').attr('src', '../system/upload_profile/404.png');
    $('.custom-file-label').html('เลือกไฟล์');
    $('#fileUpload')[0].value = '';
  }
}
//อัพโหลดภาพลง folder
function Upload_image(local){
  var fileUpload = $('#fileUpload')[0];
  var formData = new FormData();
  if (fileUpload.files.length == 1) {
    formData.append("fileUpload", fileUpload.files[0], fileUpload.files[0].name);
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
          $('.imgPreview').attr('src', '../system/upload_profile/' + data.fileId);
          $('#hidFileId').val('../system/upload_profile/' + data.fileId);
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
function Update_account(){
  $("#updateSuccess").html('<div id="loading"><h5><i class="fas fa-spinner fa-spin"></i>กำลังบันทึก .....</h5></div>');
  $.ajax({
    url: "../system/cmd.php",
    data:{
      command: "update",
      UID: $("#UID").val(),
      Fname: $("#Fname").val(),
      Lname: $("#Lname").val(),
      approve: $("#approve").val(),
      image: $("#hidFileId").val()
    },
    type: "POST",
    success:function(data){
      $("#updateSuccess").html('');
      $("#updateSuccess").html(data);//แสดงสถานะ
    },
    error:function (){ alert('oop');}
  });
}

//เช็คเงื่อนไขการเปลี่ยนพาส
function changePassFormValidate(){
  var valid = true; //ตั้งให้ ค่าเป็น true เวลาเอาฟังชั่นนี้ไปใช้ ถ้ามีอะไรผิดพลาด ในฟังชั่นนี้จะให้ค่า false  ตามเงื่อนไข
  var letters = /^[0-9a-zA-Z]+$/;
    if(!$('#pass').val().match(letters)){
        //$("#submitsignup").attr("disabled", true); 
        $('#message_alphapass').html('รหัสผ่านกรุณาใช้ตัวอักษร A-Z, a-z หรือ 0-9').css('color', 'red');
        valid = false;
    }
    if($('#pass').val() != $('#Conpass').val()){
        //$("#submitsignup").attr("disabled", true); 
        $('#message_pass').html('ยืนรหัสผ่านไม่ตรงกัน').css('color', 'red');
        valid = false;
    }
    if($('#pass').val() == $('#Conpass').val()){
        //$("#submitsignup").attr("disabled", true); 
        $('#message_pass').html('');
    }
    if($('#pass').val().match(letters)){
      $('#message_alphapass').html('');
    }
    if (valid == false){
      $('#message_alert').prop('hidden',false);
    }
    return valid;
}