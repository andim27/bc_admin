$(document).ready(function() {

    $('.show_alert').css('display', 'none');
    $('.about_user').css('display', 'none');

    $('.search_user').click(function(){

       var user = $('.search_user_input').val();

        $.ajax({
            type: 'GET',
            url: '/'+LANG+'/users/result/find-user',
            data: {
                user: user
            },
            success: function(data){
                if(data == 0){
                    $('.show_alert').css('display', 'block');
                    $('.about_user').css('display', 'none');
                } else {
                    $('.show_alert').css('display', 'none');
                    $('.about_user').css('display', 'block');
                    $('.html_here').html(data);
                }
            }
        });
    });


});
