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
    $('.user_enter').attr('disabled', 'disabled');

    $('#find_user').keyup(function(){
        var user = $(this).val();
        $.ajax({
            url:'/'+LANG+"/users/signin/find",
            method:"GET",
            data: {
                'user' : user
            },
            success:function(data){
                data = JSON.parse(data);
                if(data == 0){
                    $('.user_info').css('display', 'none');
                    $('.user_enter').attr('disabled', 'disabled');

                } else {
                    $('#login').html(data['login']);
                    $('#status').html(data['status']);
                    if(data['avatar_img'] == ''){
                        $('#img').html('');
                    } else $('#img').html('<img src="/uploads/'+data['avatar_img']+'" class="img-circle">');
                    $('#date').html(convertsTimestamp(data['created_at']));
                    $('#fio').html(data['name']+' '+data['second_name']+' '+data['middle_name']);
                    $('.user_info').css('display', 'block');
                    $('.user_enter').removeAttr('disabled');
                }
            }

        });
    });



    $('body').on("click", ".user_enter",function(){
       window.open('http://business','','Toolbar=1,Location=0,Directories=0,Status=0,Menubar=0,Scrollbars=0,Resizable=0,Width=auto,Height=auto');
       var login = $('#login').text();
        $.ajax({
            url:'/'+LANG+"/users/signin/enter",
            method:"GET",
            data: {
                'login' : login
            },
            success:function(data){

            }

        });
    });


});