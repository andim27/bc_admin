jQuery(document).ready(function() {
    var currentUrl = window.top.location.href;
    var indexOfAnchor = currentUrl.indexOf('#');
    var currentNewsId;

    if (indexOfAnchor != -1) {
        currentNewsId = currentUrl.substring(indexOfAnchor + 1)
        showNews(currentNewsId);
    } else {
        setTimeout(function(){
            $(".news-it:first-child").addClass('act').trigger('click');
        }, 500);
    }

    countUnreadedNews();

    function countUnreadedNews() {
        $.ajax({
            url: '/' + LANG + '/business/news/seen-news',
            method: 'GET',
            success: function(data){
                data = JSON.parse(data);
                var totalUnreadedNotifications = data.unreadedNews + data.unreadedPromotions;
                if (data.unreadedNews !== 0) {
                    $('.non_seen').html('');
                    $('.non_seen').css('display', 'inline-block').append(data.unreadedNews);
                } else {
                    $('.non_seen').css('display', 'none');
                }

                $('.non_seen_notifications').css('display', 'inline-block');

                if (totalUnreadedNotifications !== 0) {
                    $('.non_seen_notifications').html('');
                    $('.non_seen_notifications').append(totalUnreadedNotifications);
                }
            }
        });
    }

    $('body').on("click", ".news-it", function () {
        showNews($(this).data().id);
    });

    function showNews(newsId) {
        $('.news-it').removeClass('act');
        $.ajax({
            url: '/' + LANG + '/business/news/show-news',
            method: "GET",
            data: {
                id : newsId
            },
            success: function(data) {
                data = JSON.parse(data);

                $('.here').html("<div>" + data['title'] + "</div>");
                $('.cont').html("<div>" + data['body'] + "</div>");
                $('.dat').html(gmdate('d.m.Y H:i', data['dateOfPublication']));
                $('.op').css('visibility', 'visible');
                $('#note-' + data['id']).addClass('act').removeClass('active');
                $('#notification-from-menu-' + newsId).remove();

                countUnreadedNews();
                $('.bg-light').scrollTop(0, 0);
            }
        });
    }
});