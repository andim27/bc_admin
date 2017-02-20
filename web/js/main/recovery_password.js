$(document).on('beforeSubmit', '#recovery-form', function() {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(data) {
            form.hide();
            $('#recovery-success').show();
            setTimeout(function() {
                $('.close').click();
            }, 3000);
            return false;
        }
    });
    return false;
});