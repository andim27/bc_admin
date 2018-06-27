$(document).ready(function() {
    var rememberMe = $('#registrationform-rememberme');

    rememberMe.change(function() {
        check();
    });

    var check = (function() {
        if (rememberMe.is(':checked')) {
            $('.next2').removeAttr('disabled');
        } else {
            $('.next2').attr('disabled', 'disabled');
        }
    });

    $('#messenger').change(function() {
        var value = $(this).val();
        var messengerNumberBlock = $('#messenger-number-block');

        if (value) {
            messengerNumberBlock.show();
        } else {
            messengerNumberBlock.hide();
        }
    });

    check();
});