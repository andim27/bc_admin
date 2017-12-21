jQuery(document).ready(function() {
    var currentUrl = window.top.location.href;
    var indexOfAnchor = currentUrl.indexOf('#');
    var currentPromotionId;

    if (indexOfAnchor != -1) {
        currentPromotionId = currentUrl.substring(indexOfAnchor + 1)
        showPromotions(currentPromotionId);
    } else {
        setTimeout(function(){
            $(".prom-it:first-child").addClass('act').trigger('click');
        }, 500);
    }

    countUnreadedPromotions();

    function convertTimestamp(timestamp) {
        var date = new Date(timestamp * 1000);

        var hours = ('0' + date.getHours()).slice(-2);
        var minutes = ('0' + date.getMinutes()).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var year = date.getFullYear();

        return day + '-' + month + '-' + year + ', ' + hours + ':' + minutes;

    }

    function countUnreadedPromotions() {
        $.ajax({
            url: '/' + LANG + '/business/information/seen-promotions',
            method: 'GET',
            success: function(data) {
                data = JSON.parse(data);
                var totalUnreadedNotifications = data.unreadedNews + data.unreadedPromotions;
                if (data.unreadedPromotions !== 0) {
                    $('.non_seen_promo').html('');
                    $('.non_seen_promo').css('display', 'inline-block').append(data.unreadedPromotions);
                } else {
                    $('.non_seen_promo').css('display', 'none');
                }

                $('.non_seen_notifications').css('display', 'inline-block');

                if (totalUnreadedNotifications !== 0) {
                    $('.non_seen_notifications').html('');
                    $('.non_seen_notifications').append(totalUnreadedNotifications);
                }
            }
        });
    }

    $('body').on('click', '.prom-it', function () {
        showPromotions($(this).data().id);
    });

    function showPromotions(promotionId) {
        $('.prom-it').removeClass('act');
        $.ajax({
            url: '/' + LANG + '/business/information/show-promotions',
            method: 'GET',
            data: {
                id : promotionId
            },
            success: function(data) {
                data = JSON.parse(data);

                $('.he').html('<div>' + data['title'] + '</div>');
                $('.co').html('<div>' + data['body'] + '</div>');
                $('.da1').html(convertTimestamp(data['dateStart']));
                $('.da2').html(convertTimestamp(data['dateFinish']));
                $('.op').css('visibility', 'visible');
                $('#note-' + data['id']).addClass('act').removeClass('active');
                $('#notification-from-menu-' + promotionId).remove();

                countUnreadedPromotions();
            }
        });
    }
});