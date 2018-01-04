function for_change(params){
        if(params==1){
            $('#productlist-change_actives').parent().append(
                '<div class="block_change"><label class="control-label" for="productlist-where_buyers">При какой покупке</label>'+
                    '<select id="productlist-where_buyers" class="form-control" name="ProductList[where_buyers]"> ' +
                        '<option value="first">При первой</option> ' +
                        '<option value="all">При каждой</option> ' +
                    '</select>'+
                    '<label class="control-label" for="productlist-where_buyers">Время оплаты в месяцах</label>' +
                    '<input type="text" class="form-control" name="ProductList[month]></div>'+
                '</div>' +
                '<div class="help-block"></div>');
        }
        else{
            $('.block_change').remove();
            $('.help-block:last-child').remove();
        }
}


jQuery(document).ready(function() {
    //var inProgress = false;
    $('.lang').on('click',function(){
        $(this).parents().find('a.change_color').removeClass('change_color');
        $(this).addClass('change_color');
        var id=$(this).data('id');
        $.ajax({
            type: 'GET',
            data:{
                "id":id
            },
            url: '/'+LANG+'/product/open',
            success: function(result){
                $('.card').html(result);
                for_change($('#productlist-change_actives').val());
            }
        });
    });

});