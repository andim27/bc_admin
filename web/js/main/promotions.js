
jQuery(document).ready(function() {


    $('body').on("click", "#preview", function () {
        var title = $('#promotionlist-title').val();
        var description = $('#promotionlist-description').val();
        var day = $('#promotionlist-post_at').val();
        var hours = $('#promotionlist-hours').val();
        var minutes = $('#promotionlist-minutes').val();
        var begin = $('#promotionlist-promotion_begin').val();
        var end = $('#promotionlist-promotion_end').val();
        $.ajax({
            url:'/'+LANG+"/promotions/prommodal",
            method:"GET",
            data: {
                'title' : title,
                'description' : description,
                'day' : day,
                'hours' : hours,
                'minutes' : minutes,
                'begin' : begin,
                'end' : end
            },
            success:function(data){
                setTimeout(function(){
                    $('.titl').html(''+ title +'');
                    $('.cont').html(''+ description +'');
                    $('.time_to_show').html(''+ day +''+' '+hours+'ч. '+minutes+'мин.');
                    $('.period_prom').html('Акция действует с '+ begin +' по'+' '+ end +'');
                }, 500);

            }
        });
    });

});