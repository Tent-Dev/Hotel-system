$(document).ready(function () {
    set = setInterval(refresh_div, 1000);
  });

  function refresh_div() {
      jQuery.ajax({
            url:'system/refresh.php',
            method:"POST",
            type: 'json',
            success:function(results) {
                $.each(JSON.parse(results), function(key,value){
                  console.log('Time status: '+value);
                  if(value == 0){
                    $.removeCookie('popup');
                    alert('Timeout');
                    clearInterval(set); //ยกเลิกการเซตเวลา
                    document.location.href = 'system_hotel_admin_rooms.php'
                  }
                });
            }
      });
  }