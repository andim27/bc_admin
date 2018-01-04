
jQuery(document).ready(function() {
    $('body').on("click", ".profile_save", function(){
        var addressaa = $('.apiaddress').val();
        var i = parseInt($('.ac_id').val());
        var login = $('.log_usr_prof').val();
        var name = $('.name_usr_prof').val();
        var surname = $('.surname_usr_prof').val();
        var email = $('.email_usr_prof').val();
        var skype = $('.skype_usr_prof').val();
        var mobile = $('.mobile_usr_prof').val();
        var smobile = $('.smobile_usr_prof').val();
        var birthday =  $('.birthday_usr_prof').val();
        var qwer = birthday.split('/');
        var avatar = $('#usr_avat').val();
        var address = $('.user-address').val();
        var city = $('#usr_city_here').val();
        var country = $('#usr_country_here').val();
        var state = $('#usr_state_here').val();


        if($('#show_name_usr').prop("checked") == true) {
           var show_name_usr = 1;
        } else show_name_usr = 0;
        if($('#show_email_usr').prop("checked") == true) {
            var show_email_usr = 1;
        } else show_email_usr = 0;
        if($('#show_phone_usr').prop("checked") == true) {
           var show_phone_usr = 1;
        } else show_phone_usr = 0;

        $.ajax({
            url:''+addressaa+'user/',
            method:"PUT",
            data: {
                accountId: i,
                username: login,
                fname: name,
                sname: surname,
                email: email,
                skype: skype,
                phone: mobile,
                phone2: smobile,
                avatar: avatar,
                country: country,
                city: city,
                state: state,
                address: address,
                birthday: ''+qwer[2]+'-'+qwer[0]+'-'+qwer[1]+'T00:00:00.000Z',
                showMobile: show_phone_usr,
                showEmail: show_email_usr,
                showName: show_name_usr
            },
            success: function(){
                setTimeout(function(){
                    location.reload();
                }, 200);
            },
            error: function(){
                alert('Такой логин и/или email уже есть или вы не заполнили все обязательные поля!');
            }
        });

    });

});