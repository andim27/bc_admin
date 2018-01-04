$(document).ready(function() {
    $('#sec_show').css('display', 'none');
    $('.add_cell_user').attr('disabled', 'disabled');

    $('.log_or_mail').keyup(function() {
        var user = $(this).val();
        $.ajax({
            type: 'GET',
            url: '/' + LANG + '/business/setting/find-user',
            data: {
                user: user
            },
            success: function(data) {
                if (data) {
                    data = JSON.parse(data);
                    $('#sec_show').css('display', 'block');
                    $('#login_here').html(data['username']);
                    if (data['avatar'] == '') {
                        $('#img_here').html('<img src="/images/avatar_default.png" class="img-circle">');
                    } else {
                        $('#img_here').html('<img src="' + data['avatar'] + '" class="img-circle">');
                    }
                    $('.add_cell_user').removeAttr('disabled');
                    $('#hid_login').val(data['username']);
                } else {
                    $('#sec_show').css('display', 'none');
                    $('.add_cell_user').attr('disabled', 'disabled');
                }
            }
        });
    });
});
