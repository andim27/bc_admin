function ajax_cancel(temp_id){
    jQuery('tr[data-id='+temp_id+']').removeClass('editfrom');
    jQuery.ajax({
        type: 'GET',
        url: '/settings/admins/view',
        data: { id: temp_id },
        success: function(result){
            jQuery('tr[data-id='+temp_id+']').html(result);
        }
    });
}

function ajax_success(temp_id){
    var fd = new FormData($('#update_admins').get(0));
    jQuery.ajax({
        type: 'POST',
        url: '/settings/admins/update',
        data: fd,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(result){
            var output = JSON.parse(result);
            jQuery('tr[data-id='+temp_id+']').html(output.result);
            jQuery('tr[data-id='+temp_id+']').removeClass('editfrom');
            if(output.error != null) {
                jQuery('#admins_index .alert').removeClass('alert-success');
                jQuery('#admins_index .alert').show(100);
                jQuery('#admins_index .alert').addClass('alert-danger alert-error');
                jQuery('#admins_index .alert').html(output.error);
                jQuery('#admins_index .alert').delay(5000).slideUp(100);
            }
            if(output.success != null) {
                jQuery('#admins_index .alert').removeClass('alert-danger alert-error');
                jQuery('#admins_index .alert').show(100);
                jQuery('#admins_index .alert').addClass('alert-success');
                jQuery('#admins_index .alert').html(output.success);
                jQuery('#admins_index .alert').delay(5000).slideUp(100);
            }
        }
    });
}

function ajax_add_editform(temp_id, countryid){
    if( jQuery('tr').hasClass('editfrom') ) {
        var id_open = jQuery('tr.editfrom').attr('data-id');
        ajax_cancel(id_open);
    }
    jQuery('tr[data-id='+temp_id+']').addClass('editfrom');
    jQuery.ajax({
        type: 'GET',
        url: '/settings/admins/ajax',
        data: { id: temp_id },
        success: function(result){
            jQuery('tr[data-id='+temp_id+']').html(result);
        }
    });
    ajaxCity(countryid, temp_id);
}

function ajaxCity(id_country, user_id){
    $.ajax({
        type: 'GET',
        url: '/'+LANG+'/settings/admins/ajax-city',
        data: { id: id_country, user: user_id },
        success: function(result){
            $('.city_dropdown').html(result);
        }
    });
}