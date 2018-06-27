!function ($) {
    var geocoder;
    var bounds;
    var map;
    
    
    function geocodeCall(addr, params){
            var addrString = '';
            if(Array.isArray(addr)){
                addr.forEach(function(part){
                    if(part) addrString += part + ', ';
                });
                addrString = addrString.slice(0,-2);
            } else {
                addrString = addr;
            }
            GMaps.geocode({
                address: addrString,
                callback: function(results, status) {
                    if(status === 'OK'){
                        setMarker(results, params);
                    } else if(status === 'ZERO_RESULTS'){
                        addr = addr.slice(0,-1);
                        if(addr){
                            geocodeCall(addr, params);
                        } else {
                            console.log(status);
                        }
                    }else{
                        console.log(status);
                    }
                }
            });
    }
    
    function setMarker(results, params){
        params = params || {};
        var latlng = results[0].geometry.location;
        geocoder = new google.maps.Geocoder(); 
        
        $.extend(params, { lat: latlng.lat(), lng: latlng.lng(), language: 'ru'});
        
        var marker = map.addMarker(params);
        bounds.extend(marker.position);
        map.fitBounds(bounds);
    }

    map = new GMaps({
        div: '#gmap_geocoding',
        lat: 50.0000,
        lng: 20.0000,
        zoom: 4
    });

    bounds = new google.maps.LatLngBounds();

    geocodeCall([$('#select-country').val(), $('#usr_city_here').val(), $('#user-address').val()], {
        draggable: true,
        title: 'You',
        infoWindow: {
        content: $('#user-firstname').val() + ' ' + $('#user-secondname').val()
    }});

}(window.jQuery);