
$(document).ready(function(){


    //делаем кнопку вперед активной только, когда согласен с условиями
    $('#registrationform-rememberme').change(function() {
        if ($(this).is(':checked'))
            $('.next1').removeAttr('disabled');
        else
            $('.next1').attr('disabled', 'disabled');
    });




    //кнопка регистрации и перехода на 3 шаг
    $('.next1').on("click", function () {
        var form = $("#w0").serialize();
        var login = $('#registrationform-login').val();
        var name = $('#registrationform-name').val();
        var surname = $('#registrationform-second_name').val();
        var email = $('#registrationform-email').val();
        var mobile = $('#registrationform-mobile').val();
        var skype = $('#registrationform-skype').val();
        var pass = $('#registrationform-pass').val();
        var reppass = $('#registrationform-password_repeat').val();
        var finpass = $('#registrationform-finance_pass').val();
        var repfinpass = $('#registrationform-password_repeat_finance').val();
        $.ajax({
            url:'/'+LANG+"/reg/success",
            method:"GET",
            data: form,
            success: function(data){
                data = JSON.parse(data);
                if (data) {
                    $('.lo').html(data.username);
                    $('.spa1').html('BPT-' + data.accountId);
                    $('#step2').addClass('active');
                    $('#step1').removeClass('active');
                    $('.s2').addClass('active');
                    $('.s1').removeClass('active');
                    $('.sp1').addClass('badge badge-success');
                    $('.sp2').addClass('badge badge-info');
                }
            }
        });
    });

    $('#registrationform-login').focusout( function () {
        var login = $('#registrationform-login').val();
        $.ajax({
            url:'/'+LANG+"/reg/searchlogin",
            method:"GET",
            data:{
                "login" : login
            },
            success:function(data){
                if(data == true){
                    $('#registrationform-login').nextAll().eq(1).html($('.errname').html());
                    $('.field-registrationform-login').addClass("has-error");
                }
                else {
                    $('.errname').css("display", "none");
                }
            }
        });
    });

    $('#registrationform-email').focusout( function () {
        var email = $('#registrationform-email').val();
        $.ajax({
            url:'/'+LANG+"/reg/searchemail",
            method:"GET",
            data:{
                "email" : email
            },
            success:function(data){
                if(data == true){
                    $('#registrationform-email').next().html($('.errmail').html());
                    $('.field-registrationform-email').addClass("has-error");
                }
                else {
                    $('.errmail').css("display", "none");
                }
            }
        });
    });

});