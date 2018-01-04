!function($) {


    var map = new GMaps({
        div: '#gmap_geocoding',
        lat: 50.0000,
        lng: 20.0000,
        zoom: 3
    });

    var bounds = new google.maps.LatLngBounds();
    var geocoder;

    function geocodeCall(addr, params, accountId) {
        GMaps.geocode({
            address: addr,
            callback: function(results, status) {
                if (results) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        setMarker(results, params, accountId);
                    } else if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                        setTimeout(function () {
                            geocodeCall(addr, params, accountId);
                        }, 100);
                    }
                } else {
                    setTimeout(function () {
                        geocodeCall(addr, params, accountId);
                    }, 100);
                }
            }
        });
    }
    
    function setMarker(results, params, accountId) {
        params = params || {};
        var latlng = results[0].geometry.location;

        $.ajax({
            url: '/' + LANG + '/business/setting/save-lat-lng',
            method: 'POST',
            data: {
                lat: latlng.lat(),
                lng: latlng.lng(),
                accountId: accountId
            }
        });

        geocoder = new google.maps.Geocoder();
        
        $.extend(params, { lat: latlng.lat(), lng: latlng.lng(), language: 'ru'});
        
        var marker = map.addMarker(params);
        bounds.extend(marker.position);
    }

    function setMarketByLatLng(lat, lng) {
        lat = parseInt(lat);
        lng = parseInt(lng);
        lat += Math.random() / 1000;
        lng += Math.random() / 1000;
        var params = {};
        geocoder = new google.maps.Geocoder();
        $.extend(params, { lat: lat, lng: lng, language: 'ru'});
        var marker = map.addMarker(params);
        bounds.extend(marker.position);
    }

    var url = '';
    switch (action) {
        case 'statistic':
            url = '/' + LANG + '/business/statistic/personal-partners';
        break;
        case 'geography':
            url = '/' + LANG + '/business/team/referrals';
        break;
    }

    $.ajax({
        url: url,
        method: "GET",
        success: function (result) {
            var i;
            var resultArr = JSON.parse(result);
            for (i = 0; i < resultArr.length; i++) {
                if (resultArr[i].lat && resultArr[i].lng) {
                    setMarketByLatLng(resultArr[i].lat, resultArr[i].lng);
                } else if (resultArr[i].address) {
                    geocodeCall(resultArr[i].address, {}, resultArr[i].accountId);
                }
            }
        }
    });

    $('#geocoding_form').submit(function(e) {
        e.preventDefault();
        var addr = $('#address').val().trim();
        geocodeCall(addr);
    });

}(window.jQuery);