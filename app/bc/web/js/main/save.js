/**
 * Created by Ivan on 10.08.2015.
*/
function Save(){
    jQuery('body #save').on("click", function(){
        var text=$(this).parents().find('#editor').html();
        var breadcrumb=$('.breadcrumb li.active').html();
        var id=$(this).data('save_id');
        jQuery.ajax({
            type: 'GET',
            url: '/bekofis/conditions/save',
            data: {
                "text":text,
                "breadcrumb":breadcrumb,
                "id":id
            },
            success: function(result){
                $('#save').css("background-color", "#89CC97");
                setTimeout(function(){
                    $('#save').css("background-color", "#FAFAFA");
                },2000);
            }
        });
    });
}
jQuery(document).ready(function() {
    //var inProgress = false;
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
            url: '/'+LANG+'/bekofis/conditions/conditions',
            success: function(result){
                $('.f').html('<aside>'+result+'</aside>');
                tratata();
                Save();
            }
        });
    });
});