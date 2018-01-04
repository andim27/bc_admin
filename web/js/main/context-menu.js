$(document).ready(function(){

    var contextMenu = $("#contextMenu");
    contextMenu.preloader = contextMenu.find('.preloader');
    $(document).ready(function () {
        contextMenu.preloader.waiting({
            className: 'waiting-nonfluid',
            elements: 5,
            fluid: false,
            auto: true
        });
    });

    $("body").on("contextmenu", ".children", function (e) {
        e.preventDefault();
        contextMenu.find('li').hide();
        contextMenu.find('li span').html("");
        contextMenu.preloader.show();
        contextMenu.position(e);
        if (contextMenu.xhr)
            contextMenu.xhr.abort();
        var id = $(this).attr('data-id');
        contextMenu.xhr = $.ajax({
            type: 'GET',
            url: '/' + LANG + "/business/team/partner-info",
            data: {id: id},
            success: function (data) {
                if (data) {
                    var info = JSON.parse(data);
                    contextMenu.preloader.hide();
                    contextMenu.find('li').show();
                    contextMenu.write(info);
                    contextMenu.position(e);
                }
            }
        });
    });
    contextMenu.write = function (info) {
        for (var key in info) {
            contextMenu.find('li[data-name="' + key + '"] span').html(info[key]);
        }
        if (! info.settings.showEmail) {
            contextMenu.find('li[data-name="email"]').hide();
        } else {
            contextMenu.find('li[data-name="email"] span').html('<a href="mailto:' + info.email + '">' + info.email + '</a>');
        }
        if (info.settings.showName) {
            if (info.firstName) {
                contextMenu.find('li[data-name="name"] span').html(info.firstName);
            } else {
                contextMenu.find('li[data-name="name"]').hide();
            }

            if (info.secondName) {
                contextMenu.find('li[data-name="surname"] span').html(info.secondName);
            } else {
                contextMenu.find('li[data-name="surname"]').hide();
            }
        } else {
            contextMenu.find('li[data-name="name"]').hide();
            contextMenu.find('li[data-name="surname"]').hide();
        }
        if (info.birthday) {
            contextMenu.find('li[data-name="birthday"] span').html(date('d.m.Y', info.birthday));
        } else {
            contextMenu.find('li[data-name="birthday"]').hide();
        }
        if (info.username) {
            contextMenu.find('li[data-name="username"] span').html(info.username);
        } else {
            contextMenu.find('li[data-name="username"]').hide();
        }
        if (info.sponsor) {
            contextMenu.find('li[data-name="sponsor"] span').html(info.sponsor.username + ' (' + info.sponsor.firstName + ' ' + info.sponsor.secondName + ')');
        } else {
            contextMenu.find('li[data-name="sponsor"]').hide();
        }
        contextMenu.find('li[data-name="expirationDateBS"] span').html(info.bs ? 'Есть, срок действия до ' + info.expirationDateBS : '-');
        if (! info.status) {
            contextMenu.find('li[data-name="status"]').hide();
        }
        if (! info.state) {
            contextMenu.find('li[data-name="state"]').hide();
        }
        if (! info.city) {
            contextMenu.find('li[data-name="city"]').hide();
        }
        if (! info.country) {
            contextMenu.find('li[data-name="country"]').hide();
        }
        if (! info.address) {
            contextMenu.find('li[data-name="address"]').hide();
        }
        if (! info.zipCode) {
            contextMenu.find('li[data-name="zipCode"]').hide();
        }
        if (! info.skype) {
            contextMenu.find('li[data-name="skype"]').hide();
        }
        if (info.settings.showMobile) {
            if (!info.phoneNumber) {
                contextMenu.find('li[data-name="phoneNumber"]').hide();
            }
            if (!info.phoneNumber2) {
                contextMenu.find('li[data-name="phoneNumber2"]').hide();
            }
        } else {
            contextMenu.find('li[data-name="phoneNumber"]').hide();
            contextMenu.find('li[data-name="phoneNumber2"]').hide();
        }
        contextMenu.find('li[data-name="status"] span').html(info.rankString);

        if (! info.linkSite) {
            contextMenu.find('li[data-name="linkSite"]').hide();
        } else {
            contextMenu.find('li[data-name="linkSite"] span').html('<a href="' + info.linkSite + '">' + info.linkSite + '</a>');
        }
        if (! info.linkOdnoklassniki) {
            contextMenu.find('li[data-name="linkOdnoklassniki"]').hide();
        } else {
            contextMenu.find('li[data-name="linkOdnoklassniki"] span').html('<a href="' + info.linkOdnoklassniki + '">' + info.linkOdnoklassniki + '</a>');
        }
        if (! info.linkVk) {
            contextMenu.find('li[data-name="linkVk"]').hide();
        } else {
            contextMenu.find('li[data-name="linkVk"] span').html('<a href="' + info.linkVk + '">' + info.linkVk + '</a>');
        }
        if (! info.linkFb) {
            contextMenu.find('li[data-name="linkFb"]').hide();
        } else {
            contextMenu.find('li[data-name="linkFb"] span').html('<a href="' + info.linkFb + '">' + info.linkFb + '</a>');
        }
        if (! info.linkYoutube) {
            contextMenu.find('li[data-name="linkYoutube"]').hide();
        } else {
            contextMenu.find('li[data-name="linkYoutube"] span').html('<a href="' + info.linkYoutube + '">' + info.linkYoutube + '</a>');
        }
    };
    contextMenu.position = function (e) {
        var h = contextMenu.outerHeight();
        var w = contextMenu.outerWidth();
        var offsetY = (e.pageY + h) > $(window).height() ? e.pageY - h : e.pageY;
        var offsetX = (e.pageX + w) > $(window).width() ? e.pageX - w : e.pageX;
        contextMenu.css({display: "block"});
        contextMenu.offset({top: offsetY, left: offsetX});
    };
    contextMenu.on("click", function (e) {
        e.stopPropagation();
        if (!e.target.is("a"))
            return false;
    });
    $(document).click(function () {
        contextMenu.hide();
    });

});