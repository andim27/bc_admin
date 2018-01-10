jQuery(document).ready(function() {

    $('body').on('change', '.autoExtensionBS', function() {
        var autoExtensionBS;
        if ($('.autoExtensionBS').prop('checked') == true) {
            autoExtensionBS = 1;
        } else {
            autoExtensionBS = 0;
        }
        $.ajax({
            type: 'GET',
            url: '/' + LANG + "/business/finance/auto-extension-b-s",
            data: { autoExtensionBS: autoExtensionBS },
            success: function(data) {
                console.log(data);
            }
        });
   });

});