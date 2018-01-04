jQuery(document).ready(function(){
    jQuery('[href=#'+document.URL.split('#')[1]+']').trigger('click');
    jQuery('#lang_change').change(function(){
        var lang=$(this).val();
        jQuery.ajax({
            type: 'GET',
            url: '/'+LANG+"/settings/locale/locale-view",
            data: { lang: lang },
            success:function(data) {
                jQuery('.ajax_dt').html(data);
                jQuery('#dt-local').dataTable();
            }
        });
    });
});
