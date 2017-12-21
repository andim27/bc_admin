$(document).ready(function() {

    setTimeout(function(){
        $('#select2-option').change();
    }, 500);

    $('body').on("change", "#select2-option", function(){

        var i = $('#select2-option option:selected').val();
        var j = $('#user_city_id').val();
        $.ajax({
            type: 'GET',
            url: '/'+LANG+'/users/user/get-city',
            data: {country:i, user_city:j},
            success: function(data){
               setTimeout(function(){
                   $('#get_city').html(data);
               }, 2000);
            }
        });

    });

});
