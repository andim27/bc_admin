
jQuery(document).ready(function() {


    $('body').on("click", "#preview", function () {
        var title = $('#news-title').val();
        var description = $('#news-description').val();
        var day = $('#news-post_at').val();
        var hours = $('#news-hours').val();
        var minutes = $('#news-minutes').val();
        $.ajax({
            url:'/'+LANG+"/news/news",
            method:"GET",
            data: {
                'title' : title,
                'description' : description,
                'day' : day,
                'hours' : hours,
                'minutes' : minutes
            },
            success:function(data){
                setTimeout(function(){
                      $('.titl').html(''+ title +'');
                      $('.cont').html(''+ description +'');
                      $('.time_to_show').html('Добавлено '+ day +','+' '+hours+':'+minutes);
                  }, 500);

            }
        });
    });

});