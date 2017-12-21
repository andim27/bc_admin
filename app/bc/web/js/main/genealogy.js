function build_tree(id, side) {
    if (id !== "000000000000000000000000") {
        getMainUserData(id);
        //$('.left_bottom_tree').attr('lb-id', id);
        //$('.right_bottom_tree').attr('rb-id', id);
        $.ajax({
            type: 'GET',
            url: '/' + LANG + "/business/team/build-tree",
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

function getMainUserData(id) {
    $.ajax({
        type: 'GET',
        url: '/' + LANG + "/business/team/main-user-data",
        data: {
            id: id
        },
        success: function (data) {
            if (data) {
                data = JSON.parse(data);
                if (data.avatar) {
                    $('#partner-data-avatar').attr('src', data.avatar);
                } else {
                    $('#partner-data-avatar').attr('src', '/images/avatar_default.png');
                }
                $('#partner-data-status').html(data.rankString);
                $('#partner-data-username').html(data.username);
                $('#left-side-number-users').html(data.leftSideNumberUsers);
                $('#number-users').html(data.leftSideNumberUsers + data.rightSideNumberUsers);
                $('#right-side-number-users').html(data.rightSideNumberUsers);

                if (data.side === 0) {
                    $('#user-side-0').show();
                    $('#user-side-1').hide();
                } else if (data.side === 1) {
                    $('#user-side-1').show();
                    $('#user-side-0').hide();
                }

            }
        }
    });
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

    $('.switch input').change(function() {
        var value;
        if ($(this).is(':checked')) {
            value = 1;
        } else {
            value = 0;
        }

        console.log(value);

        $.ajax({
            type: 'GET',
            url: '/' + LANG + '/business/team/change-manual-registration',
            data: {
                manualRegistrationControl: value
            },
            success: function(data) {
                if (value == 0) {
                    if (data.success) {
                        $('.limit').attr('disabled', 'disabled');
                        $('.limit').val('');
                        window.location = '/' + LANG + '/business/team/genealogy';
                    } else {
                        $('.limit').removeAttr('disabled');
                        $('.limit').val($('.limit').data('next'));
                        $('.switch input').removeAttr('checked');
                    }
                } else {
                    if (! data.success && data.error) {
                        $('.switch input').removeAttr('checked');
                        $('#manual-registration-error').html(data.error).show();
                    } else {
                        $('#manual-registration-error').html('').hide();
                    }
                }
            }
        });
    });

    $('.search_text').keyup(function(e) {
        if (e.keyCode === 13) {
            searchLogin($(this).val());
        }
    });

    $('#login-list').keyup(function(e) {
        if (e.keyCode === 13) {
            nextReg($(this).val());
        }
    });

    $('#manual-reg-save-btn').click(function() {
        nextReg($('#login-list').val());
    });

    var nextReg = (function(username) {
        $('#login-list').parent().removeClass('has-error');
        $('#login-list-error').html('').hide();
        $.ajax({
            type: 'GET',
            url: '/' + LANG + '/business/team/next-registration',
            data: {
                username: username
            },
            success: function (data) {
                console.log(data);
                if (data.result) {
                    location.reload();
                } else {
                    $('#login-list').parent().addClass('has-error');
                    $('#login-list-error').html(data.error).show();
                }
            }
        });
    });

    var $tree = $('.tree');

    $tree.on('click', function () {
        var id = $('.user_id').data('id');
        $('.upstairs_tree').attr('data-id', id);
        build_tree(id);
    });

    $('#content_tree').on('click', '.children', function () {
        var id = $(this).attr("data-id");

        if ($(this).hasClass('bg-danger')) {
            id = $(this).attr("parent-id");
        }
        setTimeout(build_tree(id), 1000);
    });

    $tree.click();


    var searchLogin = (function (login) {
        var $preLoader = $('.preloader-img');

        if (login.length !== 0) {
            $.ajax({
                type: 'GET',
                url: '/' + LANG + "/business/team/search-login-in-tree",
                data: {
                    login: login,
                    iduser: $('.user_id').data('id')
                },
                beforeSend: function( xhr ) {
                    $preLoader.fadeIn(300);
                },
                success: function (data) {
                    $preLoader.fadeOut();

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

    $('#search-reg-username').click(function() {
        searchLogin($('#next-reg-username').html());
    });

    $('.sideToNextUser').click(function () {
        $(this).parent().find('.sideToNextUser').removeClass('active');
        obj = $(this);
        $.ajax({
            type: 'GET',
            url: '/' + LANG + "/business/team/save-settings",
            data: {sideToNextUser: $(this).attr("data-side")},
            success: function (data) {
                console.log(data);
                if (data.id) {
                    obj.addClass('active');
                }
            }
        });

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
