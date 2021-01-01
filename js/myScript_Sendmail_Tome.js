(function ($) {
    'use strict';
    var form = $('.sendmailForm'),
        message = $('.contact_msg')
    // Success function
    function done_func(response) {
        var data = JSON.parse(response);
        message.fadeIn();
        if(data.success == 1){
            message.html('Thank You! Your message has been sent. <i class="fas fa-check-circle"></i>');
            $(form).trigger("reset");
        }else{
            message.html('Cannot send mail. Please check your information. <i class="fas fa-times-circle"></i>');
        }
       setTimeout(function () {
            message.fadeOut();
        }, 2000);
    }
    // fail function
    function fail_func(data) {
        message.fadeIn();
        message.text('Cannot sned mail. Please check your information.');
        setTimeout(function () {
            message.fadeOut();
        }, 2000);
    }
    
    $('.sendmail').click(function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../system/sendmail_tome.php',
            data: {
                Fname: $('#Fname').val(),
                Lname: $('#Lname').val(),
                Tel: $('#Tel').val(),
                Comment: $('#Comment').val(),
                Email: $('#Email').val()
            }
        })
        .done(done_func)
        .fail(fail_func);
    });
    
})(jQuery);