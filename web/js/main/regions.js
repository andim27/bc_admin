$(document).ready(function() {
    $('.ajaxDeleteCountry').on('click', function() {
        var id_country = $(this).data('id-country');
        if (confirm("Вы уверены?")){
            jQuery.ajax({
                type: 'GET',
                url: '/'+LANG+'/settings/country/delete-country',
                data: { id: id_country },
                success: function(){
                    $('tr[data-id='+id_country+']').fadeOut(200).remove();
                }
            });
        }
        return false;
    });

    $('.ajaxDeleteCity').on('click', function() {
        var id_city = $(this).data('id-city');
        if (confirm("Вы уверены?")){
            jQuery.ajax({
                type: 'GET',
                url: '/'+LANG+'/settings/city/delete-city',
                data: { id: id_city },
                success: function(){
                    $('tr[data-id='+id_city+']').fadeOut(200).remove();
                }
            });
        }
        return false;
    });
});
