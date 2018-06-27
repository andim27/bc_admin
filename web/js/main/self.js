
jQuery(document).ready(function() {

    $('.modal-header').prepend('<h4>Информация о реферрале</h4>');

    $('body').on("contextmenu", ".st", function () {
        $.ajax({
            url:'/'+LANG+"/business/team/see",
            method:"GET",
            data: $(this).data(),
            success:function(data){
                $('#myModal').modal();
                $('.myModal').html(data);
                $('.close').addClass('clos');
            }

        });
        return false;
    });

});