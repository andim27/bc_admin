/**
 * Created by Ivan on 21.08.2015.
 */
function Save(){
    jQuery('body #save').on("click", function(){
        $('.breadcrumb').val($('.breadcrumb li.active').html());
        var form=$('#f1').serialize();
        jQuery.ajax({
            type: 'GET',
            url: '/'+LANG+'/bekofis/buttons/save',
            data:form,
            success: function(result){
                $('#save').css("background-color", "#89CC97");
                setTimeout(function(){
                    $('#save').css("background-color", "#FAFAFA");
                },2000);
            }
        });
    });
}
$(document).ready(function() {

    $('.lang').on('click',function(){
        $(this).parents().find('a.change_color').removeClass('change_color');
        $(this).addClass('change_color');
        var id=$(this).data('id');
        var breadcrumb=$('.breadcrumb li.active').html();
        $.ajax({
            type: 'GET',
            data:{
                "id":id,
                "breadcrumb":breadcrumb
            },
            url: '/'+LANG+'/bekofis/buttons/link',
            success: function(data){
                console.log(data);
                $('.f').html(data);
                Save();
            }
        });
    });
});