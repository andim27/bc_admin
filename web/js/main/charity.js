$(document).ready(function(){

    $('#save-percent').click(function() {
        var thisButton = $(this);
        var spinner = $('#spinner');
        var percent = $('input[name="CharityForm[percent]"]');
        spinner.show();
        thisButton.hide();
        percent.attr('disabled', 'disabled');
        $.ajax({
            type: 'GET',
            url: '/' + LANG + '/business/charity/save-percent',
            data: {
                percent: $('#percent').val()
            },
            success: function(data){
                thisButton.show();
                spinner.hide();
                percent.removeAttr('disabled');
            }
        });
        return false;
    });

});