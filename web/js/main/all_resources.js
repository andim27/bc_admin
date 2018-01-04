

jQuery(document).ready(function() {

    $(".new_res").css('display', 'none');

    $.ajax({
        "url":'/'+LANG+'/bekofis/resources/show',
        "type":'GET',
        "data":{
        },
        success:function(data){
            if(data == 1){
                $(".new_res").css('display', 'block');
            }
        }
    });


    $('body').on("click", ".add_res", function(){
        var i = $(this).parent().find('input').val();
        $('#row-'+i+'').after($('.new_res').css('display', 'block'));
    });


});