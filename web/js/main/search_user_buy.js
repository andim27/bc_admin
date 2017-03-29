function search_lg(el){
    var login=el.val();
    $.ajax({
        type: 'GET',
        url: '/'+LANG+"/users/users-buy/search-login",
        data: { login: login },
        success:function(data) {
            $('.ajax').html(data);
        }
    });
}

$(document).ready(function(){
    $('.form-control').on('keyup',function (e) {
        e = e || window.event;
        if (e.keyCode === 13) {
            search_lg($('.log'));
        }
// Отменяем действие браузера
        return false;
    });

    $('.search').on('click',function(){
        search_lg($('.log'));
    });
    $('.input-group-addon').on('click',function(){
        search_lg($('.log'));
    });

});
