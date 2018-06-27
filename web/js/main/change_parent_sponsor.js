function convertsTimestamp(timestamp) {
    var d = new Date(timestamp * 1000),	// Convert the passed timestamp to milliseconds
        yyyy = d.getFullYear(),
        mm = ('0' + (d.getMonth() + 1)).slice(-2),	// Months are zero based. Add leading 0.
        dd = ('0' + d.getDate()).slice(-2),			// Add leading 0.
        time;


    time =   dd + '-' + mm + '-' +yyyy;

    return time;
}


jQuery(document).ready(function() {

    $('.user_info').css('display', 'none');
    $('.user_info1').css('display', 'none');
    $('.user_info2').css('display', 'none');
    $('.user_enter').attr('disabled', 'disabled');
    $('.user_enter1').attr('disabled', 'disabled');
    $('.user_enter2').attr('disabled', 'disabled');
    $('#find_user1').attr('disabled', 'disabled');
    $('#find_user2').attr('disabled', 'disabled');

    $('#find_user').keyup(function(){
        var user = $(this).val();
        $.ajax({
            url:'/'+LANG+"/users/change/find",
            method:"GET",
            data: {
                'user' : user
            },
            success:function(data){
                data = JSON.parse(data);
                if(data == 0){
                    $('.user_info').css('display', 'none');
                    $('.user_info1').css('display', 'none');
                    $('.user_info2').css('display', 'none');

                    $('.user_enter1').attr('disabled', 'disabled');
                    $('.user_enter2').attr('disabled', 'disabled');
                    $('#find_user1').attr('disabled', 'disabled');
                    $('#find_user2').attr('disabled', 'disabled');
                } else {
                    $('#login').html(data['login']);
                    $('#login1').html(data['sponsor_login']);
                    $('#login2').html(data['parent_login']);

                    $('#status').html(data['status']);
                    $('#status1').html(data['sponsor_status']);
                    $('#status2').html(data['parent_status']);

                    if(data['avatar_img'] == ''){
                        $('#img').html('');
                    } else $('#img').html('<img src="/uploads/'+data['avatar_img']+'" class="img-circle">');
                    if(data['sponsor_avatar_img'] == ''){
                        $('#img1').html('');
                    } else $('#img1').html('<img src="/uploads/'+data['sponsor_avatar_img']+'" class="img-circle">');
                    if(data['parent_avatar_img'] == ''){
                        $('#img2').html('');
                    } else $('#img2').html('<img src="/uploads/'+data['parent_avatar_img']+'" class="img-circle">');


                    $('#date').html(convertsTimestamp(data['created_at']));
                    $('#date1').html(convertsTimestamp(data['sponsor_created_at']));
                    $('#date2').html(convertsTimestamp(data['parent_created_at']));


                    $('#fio').html(data['name']+' '+data['second_name']+' '+data['middle_name']);
                    $('#fio1').html(data['sponsor_name']+' '+data['sponsor_second_name']+' '+data['sponsor_middle_name']);
                    $('#fio2').html(data['parent_name']+' '+data['parent_second_name']+' '+data['parent_middle_name']);

                    $('.user_info').css('display', 'block');
                    $('.user_info1').css('display', 'block');
                    $('.user_info2').css('display', 'block');


                    $('#find_user1').removeAttr('disabled');
                    $('#find_user2').removeAttr('disabled');
                    $('#sp').val(data['sponsor_login']);
                    $('#par').val(data['parent_login']);
                }
            }

        });
    });

    $('#find_user1').keyup(function(){
        var sponsor = $(this).val();
        var fuck =  $('#sp').val();
        $.ajax({
            url:'/'+LANG+"/users/change/find-sponsor",
            method:"GET",
            data: {
                'sponsor' : sponsor,
                'fuck' : fuck
            },
            success:function(data){
                data = JSON.parse(data);

                    $('#login1').html(data['login']);
                    $('#status1').html(data['status']);
                    if(data['avatar_img'] == ''){
                        $('#img1').html('');
                    } else $('#img1').html('<img src="/uploads/'+data['avatar_img']+'" class="img-circle">');
                    $('#date1').html(convertsTimestamp(data['created_at']));
                    $('#fio1').html(data['name']+' '+data['second_name']+' '+data['middle_name']);
                    if(data['a1'] == 5){
                        $('.user_enter1').removeAttr('disabled');
                    } else $('.user_enter1').attr('disabled', 'disabled');
            }

        });
    });

    $('#find_user2').keyup(function(){
        var parent = $(this).val();
        var kitty =  $('#par').val();
        $.ajax({
            url:'/'+LANG+"/users/change/find-parent",
            method:"GET",
            data: {
                'parent' : parent,
                'kitty' : kitty
            },
            success:function(data){
                data = JSON.parse(data);

                $('#login2').html(data['login']);
                $('#status2').html(data['status']);
                if(data['avatar_img'] == ''){
                    $('#img2').html('');
                } else $('#img2').html('<img src="/uploads/'+data['avatar_img']+'" class="img-circle">');
                $('#date2').html(convertsTimestamp(data['created_at']));
                $('#fio2').html(data['name']+' '+data['second_name']+' '+data['middle_name']);
                if(data['a1'] == 13){
                    $('.user_enter2').removeAttr('disabled');
                } else $('.user_enter2').attr('disabled', 'disabled');
            }

        });
    });

    $('.user_enter1').click(function(){
        var spon =  $('#login1').text();
        var us =  $('#login').text();
        $.ajax({
            url:'/'+LANG+"/users/change/change-sponsor",
            method:"GET",
            data: {
                'spon' : spon,
                'us' : us
            },
            success:function(){
                $('#sp').val($('#login1').text());
                $('#show_alert1').css('display', 'block');
                setTimeout(function(){
                    $('#show_alert1').css('display', 'none');
                }, 2000);
            }

        });
    });

    $('.user_enter2').click(function(){
        var paren =  $('#login2').text();
        var use =  $('#login').text();
        $.ajax({
            url:'/'+LANG+"/users/change/change-parent",
            method:"GET",
            data: {
                'paren' : paren,
                'use' : use
            },
            success:function(){
                $('#par').val($('#login2').text());
                $('#show_alert2').css('display', 'block');
                setTimeout(function(){
                    $('#show_alert2').css('display', 'none');
                }, 2000);
            }

        });
    });

});