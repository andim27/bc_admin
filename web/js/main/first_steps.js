
function Save(){
    jQuery('body #save').on("click", function(){
        var text=$(this).parents().find('#editor').val();
        var breadcrumb=$('.breadcrumb li.active').html();
        var id=$(this).data('save_id');
        jQuery.ajax({
            type: 'GET',
            url: '/'+LANG+'/bekofis/first-steps/save',
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
$(document).ready(function() {

    $('.lang').on('click',function(){
        $(this).parents().find('a.change_color').removeClass('change_color');
        var id=$(this).data('id');
        $(this).addClass('change_color');
        var breadcrumb=$('.breadcrumb li.active').html();
        $.ajax({
            type: 'GET',
            data:{
                "id":id,
                "breadcrumb":breadcrumb
            },
            url: '/'+LANG+'/bekofis/first-steps/first',
            success: function(result){
                $('.f').html('<aside>'+result+'</aside>');
                Save();
            }
        });
    });
});