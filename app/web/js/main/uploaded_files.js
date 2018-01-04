jQuery(document).ready(function() {

    $('#conditionuploadedfiles-count').on('change',function(){
        var count = $('#conditionuploadedfiles-count option:selected').val();
        $.ajax({
            type: 'GET',
            data:{
                "count": count
            },
            url: '/'+LANG+'/users/download/count-condition-files',
            success: function(result){

            }
        });
    });

});