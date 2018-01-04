jQuery(document).ready(function() {

    $.ajax({
        url: '/' + LANG + '/business/uploaded/find',
        method: 'GET',
        data: {
            'id' : $(this).data()
        },
        success: function(data){
            data = JSON.parse(data);

            $.each(data, function(index, object) {
                var key = index + 1;
                $('.inp-' + key).val(object.fileName);
                $('.but-' + key).removeClass('btn-default').attr('disabled', 'disabled').html('<i class="fa fa-check text-active"></i>' + $('#uploaded').data().tr).addClass('btn-success active');
                $('#del-' + key).attr('data-id', object.id);
                $('#del-' + key).attr('data-index', key);
                $('#del-' + key).attr('data-path', object.body);
                $('#del-' + key).show();
            });
        }
    });

    $('body').on('click', '.btn-del', function() {
        var fileId = $(this).data().id;
        var index = $(this).data().index;
        var path = $(this).data().path;
        if (fileId !== undefined && path !== undefined) {
            $.ajax({
                url: '/' + LANG + '/business/uploaded/delete',
                method: 'GET',
                data: {
                    'id': fileId,
                    'path': path
                },
                success: function () {
                    changeInputs(index);
                }
            });
        } else {
            changeInputs(index);
        }

        return false;
    });

    var changeInputs = (function(index) {
        $('.inp-' + index).val('');
        $('#filestyle-' + index).val('');
        $('#del-' + index).hide();
        $('.but-' + index).removeAttr('disabled').html('<i class="fa fa-cloud-upload text"></i> ' + $('#upload').data().tr).removeClass('btn-success active').addClass('btn-default').css('margin-left', '10px');
    });

    $('#w0').change(function(object) {
        var key = $(object.target).data().key;
        if ($('.inp-' + key).val() != '') {
            $('.but-' + key).removeClass('btn-default').html('<i class="fa fa-check text-active"></i> ' + $('#uploaded').data().tr).addClass('btn-success active');
            $('#del-' + key).show();
        } else {
            $('#del-' + key).hide();
            $('.but-' + key).html('<i class="fa fa-cloud-upload text"></i> ' + $('#upload').data().tr).removeClass('btn-primary').addClass('btn-default').css('margin-left', '10px');
        }
    });

    $('.btn-default').html('<i class="fa fa-cloud-upload text"></i> ' + $('#upload').data().tr).removeClass('btn-primary').addClass('btn-default').css('margin-left', '10px');
});