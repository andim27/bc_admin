/**
 * Created by ivan on 07.09.2015.
 */
function Select(el){
    Active(el);
}
function Active(el){
    el.parent().find('.active').removeClass('active');
    el.addClass('active');
}
function Remove(el){
    var id = $('.view').data('id');
    $.ajax({
        "url":'/'+LANG+'/users/edit/remove',
        "method":"GET",
        "data":{
            "id":id
        },
        success:function(resalt){
            if(resalt==true){
                el.remove();
            }
            else{
                console.log(resalt);
            }
        }
    });
}
$(document).ready(function() {
    $('.close').on('click',function(){
        Remove($(this).parents());
    });
});
