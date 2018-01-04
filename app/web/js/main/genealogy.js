function build_tree(id, side) {
    if (id != "000000000000000000000000") {
        //$('.left_bottom_tree').attr('lb-id', id);
        //$('.right_bottom_tree').attr('rb-id', id);
        $.ajax({
            type: 'GET',
            url: '/' + LANG + "/business/user/build-tree",
            data: {id: id, side: side},
            success: function (data) {
                if (data) {
                    $('#content_tree').html(data);

                    $.each($('.children'), function (index, value) {
                        if ($(value).width() < 100) {
                            $(value).find("a.thumb").remove()
                            $(value).find(".icon").remove()
                            //$(value.childNodes[1]).remove();
                            $(value).attr('style', 'width:90%');
                        }
                        else {
                            if ($(value).width() < 200) {
                                $(value).attr('style', 'min-width:240px');
                            }
                        }
                    });
                    $('.above_tree').attr('parent-above', $('#content_tree').find('.bg-danger').attr('parent-id'));
                }
            }
        });
    }
}

$(document).ready(function() {
    if (window.innerWidth < 992) {
        $('.control_tree').css('position', 'relative');
    }

    if ($('.switch input').is(':checked')) {
        $('.limit').removeAttr('disabled');
    } else {
        $('.limit').attr('disabled', 'disabled');
    }

    $('.search_text').keyup(function(e) {
        if (e.keyCode == 13) {
            searchLogin($(this).val());
        }
    });

    $('.tree').on('click', function () {
        var id = $('.user_id').data('id');
        $('.upstairs_tree').attr('data-id', id);
        build_tree(id);
    });

    $('#content_tree').on('click', '.children', function () {

        var id = $(this).attr("data-id");

        if ($(this).hasClass('bg-danger')) {
            var id = $(this).attr("parent-id");
        }
        setTimeout(build_tree(id), 1000);
    });

    $('.tree').click();

    var searchLogin = (function (login) {
        if (login.length != 0) {
            $.ajax({
                type: 'GET',
                url: '/' + LANG + "/business/user/search-login-in-tree",
                data: {login: login, iduser: $('.user_id').data('id')},
                success: function (data) {
                    if (data) {
                        build_tree(data);
                    } else {
                        $('#content_tree').html('');
                    }
                }
            })
        }
    });

    $('.search_login').click(function() {
        searchLogin($('.search_text').val());
    });
});

$('.upstairs_tree').click(function () {
    build_tree($(this).attr('top-id'));
});

$('.above_tree').click(function () {
    build_tree($(this).attr('parent-above'));
});

$('.left_bottom_tree').click(function () {
    build_tree($(this).attr('lb-id'), 1);
});

$('.right_bottom_tree').click(function () {
    build_tree($(this).attr('rb-id'), 0);
});
