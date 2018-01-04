
$(document).ready(function(){

    $(document).on("shown.bs.modal",'#ajaxModal', function() {
        setTimeout(function(){
            $('#prombuy-sku_id').change();
            $('#promstep-sku_id').change();
            $('#promstatus-status').change();

            $('#prombuy-promotion_begin').datepicker();
            $('#promstep-promotion_begin').datepicker();
            $('#promstatus-promotion_begin').datepicker();

            $('#prombuy-promotion_end').datepicker();
            $('#promstep-promotion_end').datepicker();
            $('#promstatus-promotion_end').datepicker();

            $('.datepicker').css("z-index", "1151");

           // $('#stockstep-product_id').change();
           // $('#stockstatus-carrier_id').change();
        },500);
    });


$(document).on('change', '#prombuy-sku_id', function(){
    var id = $(this).val();
    $.ajax({
        type: 'GET',
        data:{
            "id" : id
        },
        url: '/'+LANG+'/bekofis/promotions/change',
        success: function(result){
            if(result!=false){
                $('#prombuy-product_title').val(result);
            }
        }
    });
});

    $(document).on('change', '#promstep-sku_id', function(){
        var id = $(this).val();
        $.ajax({
            type: 'GET',
            data:{
                "id" : id
            },
            url:'/'+LANG+'/bekofis/promotions/change',
            success: function(result){
                if(result!=false){
                    $('#promstep-product_title').val(result);
                }
            }
        });
    });

    $(document).on('change', '#promstatus-status', function(){
        var id = $(this).val();
        $.ajax({
            type: 'GET',
            data:{
                "id" : id
            },
            url: '/'+LANG+'/bekofis/promotions/changestatus',
            success: function(result){
                if(result!=false){
                    $('#promstatus-status_title').val(result);
                }
            }
        });
    });

});