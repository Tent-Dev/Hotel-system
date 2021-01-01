//datepicker
function datepicker_set(FormRequestClass){
  // //datepicker_admin
  if(FormRequestClass === ".adminSettingSearchDate_bottom"){
    $(".input-daterange").datepicker({
    container: FormRequestClass,
    //format: "dd/M/yyyy",
    numberOfMonths: 1,
    format: 'dd-M-yyyy',
    clearBtn: true,
    todayHighlight: true,
    orientation: "bottom auto",
    autoclose: true
    });
  }else{
    $(".input-daterange").datepicker({
    container: FormRequestClass,
    //format: "dd/M/yyyy",
    numberOfMonths: 1,
    format: 'dd-M-yyyy',
    clearBtn: true,
    todayHighlight: true,
    orientation: "top auto",
    autoclose: true
    });
  }

  //Check same day
  $('#checkin2').each(function () {
    $(this).on('changeDate', function(e) {
      CheckIn = $(this).datepicker('getDate');
      CheckOut = moment(CheckIn).add(1, 'day').toDate();
      $('#checkout2').datepicker('update', CheckOut).focus();
      $('#checkout2').datepicker('setStartDate',CheckOut);
    });       
  });

  //Check same day
  $('#checkin3').each(function () {
    $(this).on('changeDate', function(e) {
      CheckIn = $(this).datepicker('getDate');
      CheckOut = moment(CheckIn).add(1, 'day').toDate();
      $('#checkout3').datepicker('update', CheckOut).focus();
      $('#checkout3').datepicker('setStartDate',CheckOut);
    });       
  });
}

//datepicker (With check Start today)
function datepicker_Bookingadmin(FormRequestClass){
  // //datepicker_admin
  $(".input-daterange").datepicker({
    container: FormRequestClass,
    startDate: new Date(),
    numberOfMonths: 1,
    format: 'dd-M-yyyy',
    clearBtn: true,
    todayHighlight: true,
    orientation: "top auto",
    autoclose: true

  });
  //Check same day
  $('#checkin2').each(function () {
    $(this).on('changeDate', function(e) {
      CheckIn = $(this).datepicker('getDate');
      CheckOut = moment(CheckIn).add(1, 'day').toDate();
      $('#checkout2').datepicker('update', CheckOut).focus();
      $('#checkout2').datepicker('setStartDate',CheckOut);   
    });       
  });
}

//Check checkbox (from Search roomlist)
function getFilterData(className){
    var filter = [];
    $('.'+className+':checked').each(function(){
      filter.push($(this).val());
      console.log(className,filter);
    });
    return filter;
}