$(document).ready(function() {

    setTimeout(function(){
        $('#select2-option').change();
    }, 500);

    $('body').on("change", "#select2-option", function(){

        var i = $('#select2-option option:selected').val();
        $.ajax({
            type: 'GET',
            url: '/'+LANG+'/users/user/get-cities',
            data: {country:i},
            success: function(data){
                $('#get_city').html(data);
            }
        });

    });

});
